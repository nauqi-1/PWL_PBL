<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanModel;
use Illuminate\Http\Request;

class PengumpulanController extends Controller
{
    public function index() {
        return PengumpulanModel::all();
    }

    public function store(Request $request) {
        $pengumpulan = PengumpulanModel::create($request->all());
        return response()->json($pengumpulan, 201);
    }

    public function show(PengumpulanModel $pengumpulan) {
        return PengumpulanModel::find($pengumpulan);
    }

    public function update(Request $request, PengumpulanModel $pengumpulan) {
        $pengumpulan->update($request->all());
        return PengumpulanModel::find($pengumpulan);
    }

    public function destroy(PengumpulanModel $pengumpulan) {
        $pengumpulan->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }

    
}
