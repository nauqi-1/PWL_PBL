<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // Mendapatkan ID user yang sedang login
        return NotificationsModel::where('penerima_notification', $userId)->get();
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

    // public function show(TugasModel $tugas)
    // {
    //     // Memuat relasi 'jenis' untuk mendapatkan data jenis_nama
    //     $tugas->load('jenis');
    //     $tugas->load('kompetensi');
    //     // Langsung kembalikan data tugas sebagai respons JSON
    //     return response()->json($tugas);
    // }
    // public function getTaskById($id)
    // {
    //     $task = TugasModel::with('kompetensi')->find($id);

    //     if (!$task) {
    //         return response()->json(['message' => 'Task not found'], 404);
    //     }

    //     return response()->json($task);
    // }


    // public function update(Request $request, TugasModel $tugas)
    // {
    //     $tugas->update($request->all());
    //     return TugasModel::find($tugas);
    // }

    // public function destroy(TugasModel $tugas)
    // {
    //     $tugas->delete();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Data terhapus'
    //     ]);
    // }
}
