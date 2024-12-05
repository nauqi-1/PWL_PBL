<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\TendikModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index() {
        return UserModel::all();
    }

    public function store(Request $request) {
        $user = UserModel::create($request->all());
        return response()->json($user, 201);
    }

    public function show(UserModel $user) {
        return UserModel::find($user);
    }

    public function find_mahasiswa($userId) {
        $mahasiswaData = MahasiswaModel::where('user_id', $userId)->first();
        return response()->json($mahasiswaData, );
        }
    
    public function find_dosen($userId) {
        $dosenData = DosenModel::where('user_id', $userId)->first();
        return response()->json($dosenData);
        }
    public function find_tendik($userId) {
        $tendikData = TendikModel::where('user_id', $userId)->first();
        return response()->json($tendikData);
        }
    public function find_admin($userId) {
        $adminData = AdminModel::where('user_id', $userId)->first();
        return response()->json($adminData);
        }
    public function update(Request $request, UserModel $user) {
        $user->update($request->all());
        return UserModel::find($user);
    }

    public function destroy(UserModel $user) {
        $user->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }

}
