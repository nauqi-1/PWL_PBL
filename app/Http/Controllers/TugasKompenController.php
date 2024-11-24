<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\TugasModel;
use App\Models\UserModel;
use App\Models\KompetensiModel;
use App\Models\TugasJenisModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;


class TugasKompenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Tugas Kompen',
            'list' => ['Home', 'Daftar Tugas Kompen']
        ];

        $page = (object) [
            'title' => 'Daftar Tugas Kompen yang ada dalam sistem'
        ];

        $activeMenu = 'tugaskompen'; //set menu yang sedang aktif

        return view('tugaskompen.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $tugass = TugasModel::select(
            'tugas_id',
            'tugas_nama',
            'tugas_desc',
            'tugas_bobot',
            'tugas_file',
            'tugas_status',
            'tugas_tgl_dibuat',
            'tugas_tgl_deadline',
            'tugas_pembuat_id',
            'tugas_progress',
            'jenis_id',
            'kuota'
        )->with('user', 'jenis');
        return DataTables::of($tugass)
            ->addIndexColumn()
            ->addColumn('pembuat', function ($tugas) {
                return $tugas->user ? $tugas->user->nama_pembuat : '-';
            })
            ->addColumn('jenis', function ($tugas) {
                return $tugas->jenis ? $tugas->jenis->jenis_nama : '-'; // Menampilkan nama jenis tugas
            })
            ->addColumn('aksi', function ($tugas) {
                $btn = '<button onclick="modalAction(\'' . url('/tugaskompen/' . $tugas->tugas_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tugaskompen/' . $tugas->tugas_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tugaskompen/' . $tugas->tugas_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function show_ajax(Request $request, string $id)
    {
        // Retrieve the tugas data with its related user and kompetensi
        $tugas = TugasModel::with(['user', 'kompetensi', 'jenis'])->find($id);

        if (!$tugas) {
            return response()->json(['status' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        return view('tugaskompen.show_ajax', compact('tugas'));
    }
    public function create_ajax()
    {
        // Fetch kompetensi data
        $kompetensi = KompetensiModel::select('kompetensi_id', 'kompetensi_nama')->get();
        $jenisTugas = TugasJenisModel::all();

        // Fetch pembuat (users) data
        $pembuat = UserModel::with(['dosen', 'admin', 'tendik'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->user_id,
                    'nama' => $user->nama_pembuat,
                ];
            });

        // Return the view with the data
        return view('tugaskompen.create_ajax', [
            'kompetensi' => $kompetensi,
            'jenisTugas' => $jenisTugas,
            'pembuat' => $pembuat,
        ]);
    }
    public function store_ajax(Request $request)
    {
        // Check if the request is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'tugas_nama' => 'required|string|max:255',
                'tugas_desc' => 'required|string',
                'tugas_bobot' => 'required|integer',
                'tugas_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,avi,mkv,txt,zip|max:51200', // Maksimum 50MB
                'tugas_tgl_deadline' => 'required|date',
                'jenis_id' => 'required|string',
                'kuota' => 'required|integer',
                'tugas_pembuat_id' => 'required|exists:m_user,user_id',
                'kompetensi' => 'required', // Kompetensi harus berupa array
                'kompetensi.*' => 'exists:m_kompetensi,kompetensi_id', // Setiap ID harus valid
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            try {
                DB::beginTransaction();

                // Buat tugas tanpa file
                $tugas = TugasModel::create($request->except('tugas_file'));

                // Simpan file jika ada
                if ($request->hasFile('tugas_file')) {
                    $file = $request->file('tugas_file');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('uploads/tugas', $filename, 'public');
                    $tugas->update(['tugas_file' => $filePath]); // Update kolom tugas_file
                }

                // Simpan kompetensi ke tabel pivot
                $tugas->kompetensi()->attach($request->kompetensi);

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Data Tugas berhasil disimpan',
                    'file_path' => $tugas->tugas_file,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                ]);
            }
        }

        return redirect('/');
    }
    public function edit_ajax($id)
    {
        // Ambil data tugas berdasarkan ID
        $tugas = TugasModel::with('kompetensi')->find($id);

        if (!$tugas) {
            return response()->json([
                'status' => false,
                'message' => 'Tugas tidak ditemukan',
            ]);
        }

        // Ambil data kompetensi dan jenis tugas
        $kompetensi = KompetensiModel::select('kompetensi_id', 'kompetensi_nama')->get();
        $jenisTugas = TugasJenisModel::all();

        // Ambil data pembuat
        $pembuat = UserModel::with(['dosen', 'admin', 'tendik'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->user_id,
                    'nama' => $user->nama_pembuat,
                ];
            });

        // Return view dengan data
        return view('tugaskompen.edit_ajax', [
            'tugas' => $tugas,
            'kompetensi' => $kompetensi,
            'jenisTugas' => $jenisTugas,
            'pembuat' => $pembuat,
        ]);
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'tugas_nama' => 'required|string|max:255',
                'tugas_desc' => 'required|string',
                'tugas_bobot' => 'required|integer',
                'tugas_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,avi,mkv,txt,zip|max:51200', // Maksimum 50MB
                'tugas_tgl_deadline' => 'required|date',
                'jenis_id' => 'required|string',
                'kuota' => 'required|integer',
                'tugas_pembuat_id' => 'required|exists:m_user,user_id',
                'kompetensi' => 'required', // Kompetensi harus berupa array
                'kompetensi.*' => 'exists:m_kompetensi,kompetensi_id', // Setiap ID harus valid
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            try {
                DB::beginTransaction();

                // Ambil tugas yang ingin diperbarui
                $tugas = TugasModel::find($id);

                if (!$tugas) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tugas tidak ditemukan',
                    ]);
                }

                // Update data tugas
                $tugas->update($request->except('tugas_file', 'kompetensi'));

                // Update file jika ada file baru
                if ($request->hasFile('tugas_file')) {
                    // Hapus file lama jika ada
                    if ($tugas->tugas_file && Storage::exists('public/' . $tugas->tugas_file)) {
                        Storage::delete('public/' . $tugas->tugas_file);
                    }

                    // Simpan file baru
                    $file = $request->file('tugas_file');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('uploads/tugas', $filename, 'public');
                    $tugas->update(['tugas_file' => $filePath]);
                }

                // Update kompetensi di tabel pivot
                $tugas->kompetensi()->sync($request->kompetensi);

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Data Tugas berhasil diperbarui',
                    'file_path' => $tugas->tugas_file,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage(),
                ]);
            }
        }

        return redirect('/');
    }
}
