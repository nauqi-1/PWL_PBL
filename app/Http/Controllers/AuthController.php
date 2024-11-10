<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
//redirect ke home jika sudah login
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {

                $level = Auth::user();
                

                if ($level -> level_id == 1) {
                    $nama = Auth::user()->admin->admin_nama;
                return response()->json([
                    'status' => true,
                    'message' => 'Selamat datang, ' . $nama . '.',
                    'redirect' => url('/')
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Web hanya bisa diakses oleh Administrator.'
                ]);
            }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Login gagal, username atau password salah.'
                ]);
            }
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
