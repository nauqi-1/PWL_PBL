<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TugasModel;
use App\Models\TugasMahasiswaModel;
use App\Models\RequestModel;
use Yajra\DataTables\Facades\DataTables;

class RequestTugasController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Request Tugas',
            'list' => ['Home', 'Request Tugas']
        ];

        $page = (object) [
            'title' => 'Request Tugas yang terdaftar dalam sistem'
        ];

        $activeMenu = 'requesttugas'; // Set menu yang sedang aktif

        return view('requesttugas.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $requesttugass = RequestModel::with(['tugas', 'pembuat', 'mahasiswa'])
            ->where('status_request', 'pending') // Filter hanya yang status_request = 'pending'
            ->get();
        return DataTables::of($requesttugass)
            ->addIndexColumn()
            ->addColumn('pembuat', function ($requesttugas) {
                if ($requesttugas->pembuat && in_array($requesttugas->pembuat->level_id, [1, 2, 3])) {
                    return $requesttugas->pembuat->nama_pembuat;
                }
                return null; // Return null r
            })
            ->addColumn('aksi', function ($requesttugas) {
                $btn = '<button onclick="modalAction(\'' . url('/requesttugas/' . $requesttugas->id_request . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/requesttugas/' . $requesttugas->id_request . '/confirm_accept_ajax') . '\')" class="btn btn-success btn-sm">Terima</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/requesttugas/' . $requesttugas->id_request . '/confirm_denied_ajax') . '\')" class="btn btn-danger btn-sm">Tolak</button> ';

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function show_ajax(Request $request, string $id)
    {
        // Retrieve the tugas data with its related user and kompetensi
        $requests = RequestModel::with(['tugas', 'mahasiswa', 'pembuat'])->find($id);

        if (!$requests) {
            return response()->json(['status' => false, 'message' => 'Request tidak ditemukan'], 404);
        }

        return view('requesttugas.show_ajax', compact('requests'));
    }
    public function accept_confirm_ajax(string $id)
    {
        // Find the task (tugaskompen) by ID
        $requests = RequestModel::find($id);

        // If task is found, return the confirmation view with the task data
        if ($requests) {
            return view('requesttugas.accept_confirm_ajax', ['requests' => $requests]);
        }

        // If task is not found, return a response indicating failure
        return response()->json([
            'status' => false,
            'message' => 'Tugas tidak ditemukan'
        ]);
    }
    public function accept_ajax(Request $request, string $id_request)
    {
        $requestData = RequestModel::find($id_request);

        if ($requestData) {
            // Cek kuota pada tugas
            $tugas = TugasModel::find($requestData->tugas_id);
            $acceptedCount = RequestModel::where('tugas_id', $requestData->tugas_id)
                ->where('status_request', 'accepted')
                ->count();

            if ($acceptedCount < $tugas->kuota) {
                // Terima request ini
                $requestData->update([
                    'status_request' => 'accepted',
                    'tgl_update_status' => now(),
                ]);
                // Buat entri di TugasMahasiswaModel
                TugasMahasiswaModel::create([
                    'tugas_id' => $requestData->tugas_id,
                    'mahasiswa_id' => $requestData->mhs_id,
                    'file_path' => null,   // Nullable, tidak perlu diisi
                    'progress' => 0,        // Default progres
                ]);

                // Periksa jika kuota penuh setelah menerima request ini
                $acceptedCount++; // Tambahkan request yang baru saja diterima
                if ($acceptedCount >= $tugas->kuota) {
                    $this->deny_remaining_requests($tugas->tugas_id); // Deny semua pending lainnya

                    // Jika kuota sudah penuh, ubah status tugas menjadi 'w' (working)
                    $tugas->update([
                        'tugas_status' => 'W',  // Ubah status tugas menjadi 'working'
                    ]);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Request berhasil diterima.',
                ]);
            }

            // Jika kuota sudah penuh
            return response()->json([
                'status' => false,
                'message' => 'Kuota untuk tugas ini sudah penuh.',
            ]);
        }

        // Jika request tidak ditemukan
        return response()->json([
            'status' => false,
            'message' => 'Request tidak ditemukan.',
        ]);
    }

    private function deny_remaining_requests(int $tugas_id)
    {
        RequestModel::where('tugas_id', $tugas_id)
            ->where('status_request', 'pending')
            ->update([
                'status_request' => 'rejected',
                'tgl_update_status' => now(),
            ]);
    }

    public function denied_confirm_ajax(string $id)
    {
        // Find the task (tugaskompen) by ID
        $requests = RequestModel::find($id);

        // If task is found, return the confirmation view with the task data
        if ($requests) {
            return view('requesttugas.denied_confirm_ajax', ['requests' => $requests]);
        }

        // If task is not found, return a response indicating failure
        return response()->json([
            'status' => false,
            'message' => 'Tugas tidak ditemukan'
        ]);
    }
    public function denied_ajax(Request $request, string $id_request)
    {
        $requestData = RequestModel::find($id_request);

        if ($requestData) {
            // Update status menjadi denied
            $requestData->update([
                'status_request' => 'rejected',
                'tgl_update_status' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Request berhasil ditolak.',
            ]);
        }

        // Jika request tidak ditemukan
        return response()->json([
            'status' => false,
            'message' => 'Request tidak ditemukan.',
        ]);
    }
}
