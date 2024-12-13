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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;


class MhslistTugasController extends Controller
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

        $activeMenu = 'mhs_listtugas'; //set menu yang sedang aktif
        $level = LevelModel::all();

        return view('mhs_view.list.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $mhsId = auth()->user()->mahasiswa->mahasiswa_id; // ID mahasiswa login
        Log::info('Mahasiswa yang Login:', ['user_id' => $mhsId]);

        $query = TugasModel::where('tugas_status', 'o') // Filter tugas status open
            ->whereDoesntHave('requests', function ($query) use ($mhsId) {
                $query->where('mhs_id', $mhsId);
            })
            ->with(['user', 'jenis']);

        // Filter tugas berdasarkan level (tambahkan ini pada query builder)
        if ($request->level_id) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('level_id', $request->level_id);
            });
        }

        $tugass = $query->get(); // Eksekusi query setelah semua filter selesai

        $debug = RequestModel::where('mhs_id', $mhsId)->pluck('tugas_id');
        Log::info('Daftar Tugas yang Sudah Direquest oleh Mahasiswa:', $debug->toArray());

        return DataTables::of($tugass)
            ->addIndexColumn()
            ->addColumn('pembuat', function ($tugas) {
                if ($tugas->user && in_array($tugas->user->level_id, [1, 2, 3])) {
                    return $tugas->user->nama_pembuat;
                }
                return null; // Return null 
            })
            ->addColumn('jenis', function ($tugas) {
                return $tugas->jenis ? $tugas->jenis->jenis_nama : '-'; // Menampilkan nama jenis tugas
            })
            ->addColumn('aksi', function ($tugas) {
                $btn = '<button onclick="modalAction(\'' . url('/mhs_listtugas/' . $tugas->tugas_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mhs_listtugas/' . $tugas->tugas_id . '/confirm_ajax') . '\')" class="btn btn-success btn-sm">Request</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
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
            ->rawColumns(['pembuat'])
            ->make(true);
    }

    public function show_ajax(Request $request, string $id)
    {
        // Retrieve the tugas data with its related user and kompetensi
        $tugas = TugasModel::with(['user', 'kompetensi', 'jenis'])->find($id);

        if (!$tugas) {
            return response()->json(['status' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        return view('mhs_view.list.show_ajax', compact('tugas'));
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
