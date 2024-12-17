<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TugasJenisModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisController extends Controller
{
    public function index()
    {
        return TugasJenisModel::all();
    }

    // public function store(Request $request)
    // {
    //     $tugas = TugasModel::create($request->all());
    //     return response()->json($tugas, 201);
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
