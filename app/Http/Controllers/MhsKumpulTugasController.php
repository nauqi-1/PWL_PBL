<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\TugasModel;
use App\Models\UserModel;
use App\Models\KompetensiModel;
use App\Models\LevelModel;
use App\Models\RequestModel;
use App\Models\MahasiswaModel;
use App\Models\TugasJenisModel;
use App\Models\TugasMahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;


class MhsKumpulTugasController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Pengumpulan Tugas Kompen',
            'list' => ['Home', 'Pengumpulan Tugas']
        ];

        $page = (object) [
            'title' => 'Daftar Tugas Kompen yang ada dalam sistem'
        ];

        $activeMenu = 'mhs_kumpultugas'; //set menu yang sedang aktif
        $level = LevelModel::all();

        return view('mhs_view.pengumpulan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $mhsId = auth()->user()->mahasiswa->mahasiswa_id; // ID mahasiswa login

        // Ambil data dari TugasMahasiswaModel berdasarkan mahasiswa yang login
        $tugass = TugasMahasiswaModel::where('mahasiswa_id', $mhsId)
            ->with(['tugas.user', 'tugas.pengumpulan']) // Menampilkan relasi tugas dan atribut lainnya
            ->get();

        return DataTables::of($tugass)
            ->addIndexColumn()
            ->addColumn('pembuat', function ($tugasMahasiswa) {
                $user = $tugasMahasiswa->tugas->user ?? null;
                if ($user && in_array($user->level_id, [1, 2, 3])) {
                    return $user->nama_pembuat; // Pastikan nama_pembuat ada di user model
                }
                return null; // Return null jika tidak ada pembuat
            })
            ->addColumn('aksi', function ($tugasMahasiswa) {
                $tugasId = $tugasMahasiswa->tugas_mahasiswa_id;
                $btn = '<button onclick="modalAction(\'' . url('/mhs_kumpultugas/' . $tugasId . '/edit_ajax') . '\')" class="btn btn-info btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mhs_kumpultugas/' . $tugasId . '/confirm_submit_ajax') . '\')" class="btn btn-success btn-sm">Upload</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function update_progress(Request $request, $id)
    {
        Log::info('updateProgress called', ['id' => $id, 'request' => $request->all()]);
        // Validate the progress input
        $validated = $request->validate([
            'progress' => 'required|numeric|between:0,100',
        ]);

        // Find the task (tugas) by the provided ID
        $tugas = TugasMahasiswaModel::find($id);

        // Check if the task exists
        if (!$tugas) {
            return response()->json([
                'status' => false,
                'message' => 'Tugas tidak ditemukan',
            ], 404);
        }

        // Update the progress value
        // $tugas->progress = $request->input('progress');
        $tugas->progress = $validated['progress'];
        $tugas->save();

        // Return a success response
        return response()->json([
            'status' => true,
            'message' => 'Progres berhasil diperbarui',
        ]);
    }

    public function edit_ajax(Request $request, string $id)
    {
        $tugas = TugasMahasiswaModel::with(['tugas.user', 'tugas.pengumpulan'])->find($id);

        if (!$tugas) {
            return response()->json(['status' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        return view('mhs_view.pengumpulan.edit_ajax', compact('tugas'));
    }
    public function confirm_submit_ajax(Request $request, string $id)
    {
        // DB::enableQueryLog();
        $tugasMahasiswa = TugasMahasiswaModel::with('tugas.jenis', 'tugas.user')->find($id);
        Log::info('Executed Query', DB::getQueryLog());

        // Ambil data kompetensi dan jenis tugas
        $kompetensi = KompetensiModel::select('kompetensi_id', 'kompetensi_nama')->get();
        $jenisTugas = TugasJenisModel::all();
        $users = UserModel::with('dosen', 'admin', 'tendik', 'mahasiswa')->get();
        $pembuat = UserModel::with(['dosen', 'admin', 'tendik'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->user_id,
                    'nama' => $user->nama_pembuat,
                ];
            });
        return view('mhs_view.pengumpulan.confirm_submit_ajax', [
            'tugasMahasiswa' => $tugasMahasiswa,
            'kompetensi' => $kompetensi,
            'jenisTugas' => $jenisTugas,
            'users' => $users,
            'pembuat' => $pembuat

        ]);
    }
    public function submit_ajax(Request $request, $id)
    {
        try {
            // Validasi input file
            $request->validate([
                'file_path' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,avi,mkv,txt,zip|max:20480', // Maks 20MB
            ]);

            // Cari data tugas mahasiswa berdasarkan ID
            $tugasMahasiswa = TugasMahasiswaModel::findOrFail($id);
            Log::info('Tugas ditemukan', ['id' => $id, 'data' => $tugasMahasiswa]);

            // Handle file upload
            if ($request->hasFile('file_path')) {
                Log::info('File ditemukan pada request');

                // Hapus file lama jika ada
                if ($tugasMahasiswa->file_path && Storage::exists($tugasMahasiswa->file_path)) {
                    Log::info('Menghapus file lama', ['file_path' => $tugasMahasiswa->file_path]);
                    Storage::delete($tugasMahasiswa->file_path);
                }

                // Simpan file baru
                $filePath = $request->file('file_path')->store('tugas_mahasiswa_files', 'public');
                Log::info('File berhasil diupload', ['file_path' => $filePath]);

                // Update file_path di database
                $tugasMahasiswa->update(['file_path' => $filePath]);
                Log::info('file_path diupdate di database', ['file_path' => $filePath]);
            } else {
                Log::warning('Tidak ada file yang diupload');
            }

            // Berhasil
            return response()->json([
                'status' => true,
                'message' => 'Tugas berhasil dikumpulkan',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi gagal', ['errors' => $e->errors()]);
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $e->errors(),
            ]);
        } catch (\Exception $e) {
            Log::error('Kesalahan server', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server',
            ], 500);
        }
    }
}
