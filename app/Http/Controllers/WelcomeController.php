<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];
        $user = Auth::user();
        $activemenu = 'dashboard';
        switch($user->level_id) {
            case 1:
                return view('welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activemenu]);
            case 2:
                return view('dosen.welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activemenu]);
            case 3:
                return view('tendik.welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activemenu]);
            case 4:
                return view('mahasiswa.welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activemenu]);
            

        }    
    }
}
