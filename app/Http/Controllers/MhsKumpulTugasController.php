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



    public function listrequest(Request $request)
    {
        // Ambil ID mahasiswa login
        $mhsId = auth()->user()->mahasiswa->mahasiswa_id;

        // Ambil data request tugas hanya untuk mahasiswa login
        $requesttugass = RequestModel::with(['tugas', 'pembuat', 'mahasiswa'])
            ->where('mhs_id', $mhsId) // Filter berdasarkan mahasiswa yang login
            ->get();

        return DataTables::of($requesttugass)
            ->addIndexColumn()
            ->addColumn('pembuat', function ($requesttugas) {
                if ($requesttugas->pembuat && in_array($requesttugas->pembuat->level_id, [1, 2, 3])) {
                    return $requesttugas->pembuat->nama_pembuat;
                }
                return null;
            })
            ->make(true);
    }

    public function edit_ajax(Request $request, string $id)
    {
        $tugas = TugasMahasiswaModel::with(['tugas.user', 'tugas.pengumpulan'])->find($id);

        if (!$tugas) {
            return response()->json(['status' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        return view('mhs_view.pengumpulan.edit_ajax', compact('tugas'));
    }
    public function confirm_ajax(string $id)
    {
        // Find the task (tugaskompen) by ID
        $tugas = TugasModel::find($id);

        // If task is found, return the confirmation view with the task data
        if ($tugas) {
            return view('mhs_view.list.confirm_ajax', ['tugas' => $tugas]);
        }

        // If task is not found, return a response indicating failure
        return response()->json([
            'status' => false,
            'message' => 'Tugas tidak ditemukan'
        ]);
    }
    public function request_ajax(Request $request, $id)
    {
        $tugas = TugasModel::find($id);

        if (!$tugas) {
            return response()->json(['status' => false, 'message' => 'Tugas tidak ditemukan']);
        }

        // Ambil mhs_id berdasarkan user_id dari mahasiswa yang sedang login
        $mahasiswa = MahasiswaModel::where('user_id', auth()->user()->user_id)->first();

        if (!$mahasiswa) {
            return response()->json(['status' => false, 'message' => 'Data mahasiswa tidak ditemukan']);
        }
        // Simpan data request ke tabel `t_request`
        RequestModel::create([
            'tugas_id' => $tugas->tugas_id,
            'mhs_id' => $mahasiswa->mahasiswa_id,
            'tugas_pembuat_id' => $tugas->tugas_pembuat_id,
            'status_request' => 'pending',
            'tgl_request' => now(),
        ]);

        return response()->json(['status' => true, 'message' => 'Request berhasil diajukan']);
    }
}
