<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TendikModel;
use Illuminate\Http\Request;

class TendikController extends Controller
{
    public function index() {
        return TendikModel::all();
    }

    public function store(Request $request) {
        $tendik = TendikModel::create($request->all());
        return response()->json($tendik, 201);
    }

    public function show(TendikModel $tugas) {
        return TendikModel::find($tugas);
    }

    public function update(Request $request, TendikModel $tendik) {
        $tendik->update($request->all());
        return TendikModel::find($tendik);
    }

    public function destroy(TendikModel $tendik) {
        $tendik->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }
}
