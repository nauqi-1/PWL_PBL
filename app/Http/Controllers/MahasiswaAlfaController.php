<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaAlfaModel;
use App\Models\MahasiswaModel;
use App\Models\PeriodeModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaAlfaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Alfa Mahasiswa',
            'list' => ['Home', 'Alfa']
        ];

        $page = (object) [
            'title' => 'Daftar jam alfa mahasiswa per periode.'
        ];

        $mahasiswa = MahasiswaModel::all();
        $periode = PeriodeModel::all();

        $activeMenu = 'mahasiswa_alfa'; //set menu yang sedang aktif

        return view('mahasiswa_alfa.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu, 
            'mahasiswa' => $mahasiswa,
            'periode'   => $periode
        ]);
    }

    public function list(Request $request)
    {
        $alfas = MahasiswaAlfaModel::select(
            'mahasiswa_alfa_id',
            'mahasiswa_id',
            'periode_id',
            'jumlah_alfa',
        )
        ->with('mahasiswa', 'periode');

        if ($request->periode_id) {
            $alfas->where('periode_id', $request->periode_id);
        }
        if ($request->mahasiswa_id) {
            $alfas->where('mahasiswa_id', $request->mahasiswa_id);
        }

        return DataTables::of($alfas)
            ->addIndexColumn()
            ->addColumn('aksi', function ($alfa) {
                $btn = '<button onclick="modalAction(\'' . url('/mahasiswa_alfa/' . $alfa->mahasiswa_alfa_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa_alfa/' . $alfa->mahasiswa_alfa_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa_alfa/' . $alfa->mahasiswa_alfa_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
