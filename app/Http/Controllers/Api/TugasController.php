<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TugasModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index()
    {
        return TugasModel::all();
    }

    public function store(Request $request)
    {
        $tugas = TugasModel::create($request->all());
        return response()->json($tugas, 201);
    }
    public function index1()
    {
        // Ambil ID pengguna yang sedang login
        $userId = auth()->user()->user_id;

        // Tampilkan tugas berdasarkan pembuatnya
        return TugasModel::where('tugas_pembuat_id', $userId)->get();
    }

    public function show(TugasModel $tugas)
    {
        return TugasModel::find($tugas);
    }

    public function update(Request $request, TugasModel $tugas)
    {
        $tugas->update($request->all());
        return TugasModel::find($tugas);
    }

    public function destroy(TugasModel $tugas)
    {
        $tugas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data terhapus'
        ]);
    }
}
