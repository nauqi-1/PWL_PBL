<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TugasModel;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index() {
        return TugasModel::all();
    }

    public function store(Request $request) {
        $tugas = TugasModel::create($request->all());
        return response()->json($tugas, 201);
    }

    public function show(TugasModel $tugas) {
        return TugasModel::find($tugas);
    }

    public function update(Request $request, TugasModel $tugas) {
        $tugas->update($request->all());
        return TugasModel::find($tugas);
    }

    public function destroy(TugasModel $tugas) {
        $tugas->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }
}
