<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use App\Models\TugasModel;
use App\Models\TugasMahasiswaModel;
use App\Models\NotificationsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        return RequestModel::all();
    }

    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'tugas_nama' => 'required|string',
    //         'tugas_bobot' => 'required|integer',
    //         'kuota' => 'required|integer',
    //         'tugas_tgl_deadline' => 'required|date',
    //         'tugas_desc' => 'required|string',
    //         'tugas_pembuat_id' => 'required|integer',
    //         'kompetensi' => 'required|array', // Validasi kompetensi sebagai array
    //         'kompetensi.*' => 'integer',      // Validasi setiap elemen kompetensi adalah integer
    //     ]);

    //     // Simpan tugas
    //     $tugas = TugasModel::create($validatedData);

    //     // Simpan kompetensi ke tabel pivot
    //     $tugas->kompetensi()->attach($request->kompetensi);

    //     return response()->json(['message' => 'Tugas berhasil dibuat'], 201);
    // }

    // public function index1()
    // {
    //     // Ambil ID pengguna yang sedang login
    //     $userId = auth()->user()->user_id;

    //     // Tampilkan tugas berdasarkan pembuatnya
    //     return TugasModel::where('tugas_pembuat_id', $userId)->get();
    // }

    public function show(RequestModel $request)
    {
        // Memuat relasi 'jenis' untuk mendapatkan data jenis_nama
        $request->load('tugas');
        $request->load('mahasiswa');
        // Langsung kembalikan data request sebagai respons JSON
        return response()->json($request);
    }
    // public function getTaskById($id)
    // {
    //     $task = TugasModel::with('kompetensi')->find($id);

    //     if (!$task) {
    //         return response()->json(['message' => 'Task not found'], 404);
    //     }

    //     return response()->json($task);
    // }


    public function accept_ajax(Request $request)
    {
        $id_request = $request->input('id_request'); // Ambil id_request dari body request
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
                    'file_path' => null,
                    'progress' => 0,
                ]);

                // Create notification
                $pembuatNotifId = auth()->user()->user_id;
                NotificationsModel::create([
                    'jenis_notification' => 'request diterima',
                    'pembuat_notification' => $pembuatNotifId,
                    'penerima_notification' => $requestData->mahasiswa->user_id,
                    'konten_notification' => 'Request Anda diterima.',
                    'tgl_notification' => now(),
                ]);

                // Periksa jika kuota penuh setelah menerima request ini
                $acceptedCount++;
                if ($acceptedCount >= $tugas->kuota) {
                    $this->deny_remaining_requests($tugas->tugas_id);

                    // Jika kuota sudah penuh, ubah status tugas menjadi 'w' (working)
                    $tugas->update([
                        'tugas_status' => 'W',
                    ]);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Request berhasil diterima.',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Kuota untuk tugas ini sudah penuh.',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Request tidak ditemukan.',
        ]);
    }
    private function deny_remaining_requests(int $tugas_id)
    {
        // Update semua request yang statusnya pending menjadi rejected
        RequestModel::where('tugas_id', $tugas_id)
            ->where('status_request', 'pending')
            ->update([
                'status_request' => 'rejected',
                'tgl_update_status' => now(),
            ]);

        // Ambil semua user_id yang status_requestnya telah diubah menjadi rejected
        $rejectedUsers = RequestModel::where('tugas_id', $tugas_id)
            ->where('status_request', 'rejected')
            ->pluck('mhs_id'); // Mengambil daftar mhs_id

        // Ambil ID pembuat notifikasi
        $pembuatNotifId = auth()->user()->user_id;

        // Buat notifikasi untuk setiap user
        foreach ($rejectedUsers as $userId) {
            NotificationsModel::create([
                'jenis_notification' => 'tugas ditolak',
                'pembuat_notification' => $pembuatNotifId,
                'penerima_notification' => $userId,
                'konten_notification' => 'Tugas Anda ditolak.',
                'tgl_notification' => now(),
            ]);
        }
    }


    public function denied_ajax(Request $request, string $id_request)
    {
        $requestData = RequestModel::find($id_request);

        if (!$requestData) {
            return response()->json([
                'status' => false,
                'message' => 'Request tidak ditemukan.',
            ], 404);
        }

        // Optional: Tambahkan validasi atau hak akses di sini
        // if (!auth()->user()->can('deny-request', $requestData)) { ... }

        try {
            DB::transaction(function () use ($requestData) {
                // Update status menjadi rejected
                $requestData->update([
                    'status_request' => 'rejected',
                    'tgl_update_status' => now(),
                ]);

                // Create notification
                $pembuatNotifId = auth()->user()->user_id;
                NotificationsModel::create([
                    'jenis_notification' => 'Request ditolak',
                    'pembuat_notification' => $pembuatNotifId,
                    'penerima_notification' => $requestData->mahasiswa->user_id,
                    'konten_notification' => 'Request Anda ditolak.',
                    'tgl_notification' => now(),
                ]);
            });

            return response()->json([
                'status' => true,
                'message' => 'Request berhasil ditolak.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server.',
            ], 500);
        }
    }
}
