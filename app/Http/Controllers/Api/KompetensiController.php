<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KompetensiModel;
use Illuminate\Http\Request;

class KompetensiController extends Controller
{
    public function index() {
        return KompetensiModel::all();
    }

    public function store(Request $request) {
        $kompetensi = KompetensiModel::create($request->all());
        return response()->json($kompetensi, 201);
    }

    public function show(KompetensiModel $kompetensi) {
        return KompetensiModel::find($kompetensi);
    }

    public function update(Request $request, KompetensiModel $kompetensi) {
        $kompetensi->update($request->all());
        return KompetensiModel::find($kompetensi);
    }

    public function destroy(KompetensiModel $kompetensi) {
        $kompetensi->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }

    public function search(Request $request) {
        $keyword = $request->input('keyword');
        $kompetensi = KompetensiModel::where('kompetensi_id', 'LIKE', "%{$keyword}%")->get();
        return response()->json($kompetensi);
    }
    
}
