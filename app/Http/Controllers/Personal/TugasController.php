<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $breadcrumb = (object) [
            'title' => 'Daftar Tugas',
            'list' => ['Home', 'Tugas']
        ];

        $page = (object) [
            'title' => 'Tugas kompen yang telah dibuat.'
        ];

        $activeMenu = 'tugas_list'; //set menu yang sedang aktif

        if ($user->level->level_kode === 'ADM') {
        return view('personal.admin.tugas_list', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
        ]);
    } elseif ($user->level->level_kode === 'DSN') {
        return view('personal.dosen.tugas_list', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
        ]);
    } elseif ($user->level->level_kode === 'TDK') {
        return view('personal.tendik.tugas_list', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
        ]);
    } elseif ($user->level->level_kode === 'MHS') {
        return view('personal.mahasiswa.tugas_list', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
        ]);
    } else {
        abort(403, 'Hak akses tidak sesuai. Kembali ke laman sebelumnya.');
    }
    }
}
