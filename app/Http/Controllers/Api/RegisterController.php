<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\UserModel;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    public function __invoke(Request $request) {
        //set validation
        $validator = Validator::make($request->all() , [
          'username' => 'required',
          'password' => 'required|min:5|confirmed',
          'level_id' => 'required',

        ]);

        //jika validation gagal
        if($validator->fails()) {
            return response() -> json($validator->errors(), 422);
        }

        //create user
        $user = UserModel::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
        ]);

        //return response JSON jika user berhasil dibuat
        if($user) {
            return response()->json([
                'success' => true,
                'user' => $user,
            ], 201);
        }

        //return response JSON jika create user gagal :(
        return response()->json([
            'success' => false
        ], 409);
    }
    
        
    
}
