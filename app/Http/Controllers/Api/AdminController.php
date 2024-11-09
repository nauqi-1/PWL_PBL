<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        return AdminModel::all();
    }

    public function store(Request $request) {
        $admin = AdminModel::create($request->all());
        return response()->json($admin, 201);
    }

    public function show(AdminModel $tugas) {
        return AdminModel::find($tugas);
    }

    public function update(Request $request, AdminModel $admin) {
        $admin->update($request->all());
        return AdminModel::find($admin);
    }

    public function destroy(AdminModel $admin) {
        $admin->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }
}
