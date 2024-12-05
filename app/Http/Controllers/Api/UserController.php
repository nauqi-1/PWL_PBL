<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\MahasiswaAlfaModel;
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
        // Fetch mahasiswa data by user_id
        $mahasiswaData = MahasiswaModel::where('user_id', $userId)->first();
    
        // Handle the case where no mahasiswa is found
        if (!$mahasiswaData) {
            return response()->json([
                'error' => 'Mahasiswa not found'
            ], 404); // 404 Not Found
        }
    
        // Sum jumlah_alfa for the mahasiswa
        $mahasiswaAlfa = MahasiswaAlfaModel::where('mahasiswa_id', $mahasiswaData->mahasiswa_id)->sum('jumlah_alfa');
    
        // Return the data with a valid HTTP status code
        return response()->json([
            'mahasiswa' => $mahasiswaData,
            'jumlah_alfa' => $mahasiswaAlfa
        ], 200); // 200 OK
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
