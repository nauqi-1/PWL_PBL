<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index() {
        return DosenModel::all();
    }

    public function store(Request $request) {
        $dosen = DosenModel::create($request->all());
        return response()->json($dosen, 201);
    }

    public function show(DosenModel $tugas) {
        return DosenModel::find($tugas);
    }

    public function update(Request $request, DosenModel $dosen) {
        $dosen->update($request->all());
        return DosenModel::find($dosen);
    }

    public function destroy(DosenModel $dosen) {
        $dosen->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }
}
