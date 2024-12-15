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
use App\Models\NotificationsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;


class PengumpulanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Pengumpulan Tugas Kompen',
            'list' => ['Home', 'Pengumpulan Tugas']
        ];

        $page = (object) [
            'title' => 'Pengumpulan Tugas'
        ];

        $activeMenu = 'pengumpulan_tugas'; //set menu yang sedang aktif
        $level = LevelModel::all();
        $mahasiswa = MahasiswaModel::all();

        return view('pengumpulan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'mahasiswa' => $mahasiswa, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $pembuatId = auth()->user()->user_id; // ID user yang sedang login

        // Query TugasMahasiswaModel untuk tugas yang dibuat oleh user yang login
        $tugass = TugasMahasiswaModel::whereHas('tugas', function ($query) use ($pembuatId) {
            $query->where('tugas_pembuat_id', $pembuatId) // Filter tugas berdasarkan pembuat
                ->whereIn('tugas_status', ['W', 'S']); // Filter status tugas pada tabel t_tugas
        })
            ->with(['tugas.user', 'mahasiswa']) // Eager load relasi untuk mengurangi query tambahan
            ->get();


        return DataTables::of($tugass)
            ->addIndexColumn()
            ->addColumn('aksi', function ($tugasMahasiswa) {
                $tugasId = $tugasMahasiswa->tugas_mahasiswa_id;
                $btn = '<button onclick="modalAction(\'' . url('/pengumpulan_tugas/' . $tugasId . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengumpulan_tugas/' . $tugasId . '/confirm_accept_ajax') . '\')" class="btn btn-success btn-sm">Terima</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengumpulan_tugas/' . $tugasId . '/confirm_denied_ajax') . '\')" class="btn btn-danger btn-sm">Tolak</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function listriwayat(Request $request)
    {
        $pembuatId = auth()->user()->user_id; // ID user yang sedang login

        // Query TugasMahasiswaModel untuk tugas yang dibuat oleh user yang login
        $tugass = TugasMahasiswaModel::whereHas('tugas', function ($query) use ($pembuatId) {
            $query->where('tugas_pembuat_id', $pembuatId) // Filter tugas berdasarkan pembuat
                ->whereIn('tugas_status', ['D', 'F']); // Filter status tugas pada tabel t_tugas
        })
            ->with(['tugas.user', 'mahasiswa']) // Eager load relasi untuk mengurangi query tambahan
            ->get();


        return DataTables::of($tugass)
            ->addIndexColumn()
            ->addColumn('aksi', function ($tugasMahasiswa) {
                $tugasId = $tugasMahasiswa->tugas_mahasiswa_id;
                $btn = '<button onclick="modalAction(\'' . url('/pengumpulan_tugas/' . $tugasId . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function show_ajax(Request $request, string $id)
    {
        // Retrieve the tugas data with its related user and kompetensi
        $tugas = TugasMahasiswaModel::with(['tugas.user', 'tugas.kompetensi', 'mahasiswa'])->find($id);

        if (!$tugas) {
            return response()->json(['status' => false, 'message' => 'Pengumpulan tidak ditemukan'], 404);
        }

        return view('pengumpulan.show_ajax', compact('tugas'));
    }
    public function accept_confirm_ajax(string $id)
    {
        // Find the task (tugaskompen) by ID
        $tugass = TugasMahasiswaModel::find($id);

        // If task is found, return the confirmation view with the task data
        if ($tugass) {
            return view('pengumpulan.accept_confirm_ajax', ['tugass' => $tugass]);
        }

        // If task is not found, return a response indicating failure
        return response()->json([
            'status' => false,
            'message' => 'Tugas tidak ditemukan'
        ]);
    }
    public function denied_confirm_ajax(string $id)
    {
        // Find the task (tugaskompen) by ID
        $tugass = TugasMahasiswaModel::find($id);

        // If task is found, return the confirmation view with the task data
        if ($tugass) {
            return view('pengumpulan.denied_confirm_ajax', ['tugass' => $tugass]);
        }

        // If task is not found, return a response indicating failure
        return response()->json([
            'status' => false,
            'message' => 'Tugas tidak ditemukan'
        ]);
    }
    public function accept_ajax(string $id)
    {
        // Find the task (tugaskompen) by ID
        $tugasMahasiswa = TugasMahasiswaModel::find($id);

        // If the task is found
        if ($tugasMahasiswa) {
            // Find the related TugasModel (the task)
            $tugasModel = $tugasMahasiswa->tugas; // Assuming you have the relationship defined
            $mahasiswaModel = $tugasMahasiswa->mahasiswa; // Assuming you have the relationship defined

            // Check if the TugasModel is found
            if ($tugasModel) {
                // Update the tugas_status to 'D'
                $tugasModel->update([
                    'tugas_status' => 'D', // 'D' stands for 'Done' or 'Disetujui' (approved)
                ]);
                $mahasiswaModel->update([
                    'mahasiswa_alfa_lunas' => $mahasiswaModel->mahasiswa_alfa_lunas + $tugasModel->tugas_bobot
                ]);

                // Create notification
                $pembuatNotifId = auth()->user()->user_id;
                NotificationsModel::create([
                    'jenis_notification' => 'tugas diterima',
                    'pembuat_notification' => $pembuatNotifId,
                    'penerima_notification' => $tugasMahasiswa->mahasiswa->user->user_id,
                    'konten_notification' => 'Tugas Anda telah diterima.',
                    'tgl_notification' => now(),
                ]);
                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Tugas berhasil diterima'
                ]);
            }

            // If TugasModel is not found
            return response()->json([
                'status' => false,
                'message' => 'Tugas model tidak ditemukan'
            ]);
        }

        // If TugasMahasiswaModel is not found
        return response()->json([
            'status' => false,
            'message' => 'Tugas mahasiswa tidak ditemukan'
        ]);
    }
    public function denied_ajax(string $id)
    {
        // Find the task (tugaskompen) by ID
        $tugasMahasiswa = TugasMahasiswaModel::find($id);

        // If the task is found
        if ($tugasMahasiswa) {
            // Find the related TugasModel (the task)
            $tugasModel = $tugasMahasiswa->tugas; // Assuming you have the relationship defined

            // Check if the TugasModel is found
            if ($tugasModel) {
                // Update the tugas_status to 'D'
                $tugasModel->update([
                    'tugas_status' => 'F', // 'D' stands for 'Done' or 'Disetujui' (approved)
                ]);

                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Tugas berhasil ditolak'
                ]);
            }

            // If TugasModel is not found
            return response()->json([
                'status' => false,
                'message' => 'Tugas model tidak ditemukan'
            ]);
        }

        // If TugasMahasiswaModel is not found
        return response()->json([
            'status' => false,
            'message' => 'Tugas mahasiswa tidak ditemukan'
        ]);
    }
}
