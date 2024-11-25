<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TugasJenisModel;
use Yajra\DataTables\Facades\DataTables;

class TugasJenisController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Jenis Tugas',
            'list' => ['Home', 'Jenis Tugas']
        ];

        $page = (object) [
            'title' => 'Jenis Tugas yang terdaftar dalam sistem'
        ];

        $activeMenu = 'tugasjenis'; // Set menu yang sedang aktif

        return view('tugasjenis.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $tugasJenis = TugasJenisModel::select('jenis_id', 'jenis_nama');

        if ($request->has('jenis_nama') && $request->jenis_nama != '') {
            $tugasJenis->where('jenis_nama', 'like', '%' . $request->jenis_nama . '%');
        }

        return DataTables::of($tugasJenis)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenis) {
                $btn = '<button onclick="modalAction(\'' . url('/tugasjenis/' . $jenis->jenis_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tugasjenis/' . $jenis->jenis_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tugasjenis/' . $jenis->jenis_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
