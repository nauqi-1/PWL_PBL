<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index() {
        return MahasiswaModel::all();
    }

    public function store(Request $request) {
        $mahasiswa = MahasiswaModel::create($request->all());
        return response()->json($mahasiswa, 201);
    }

    public function show(MahasiswaModel $tugas) {
        return MahasiswaModel::find($tugas);
    }

    public function update(Request $request, MahasiswaModel $mahasiswa) {
        $mahasiswa->update($request->all());
        return MahasiswaModel::find($mahasiswa);
    }

    public function destroy(MahasiswaModel $mahasiswa) {
        $mahasiswa->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }
}
