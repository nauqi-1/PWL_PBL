<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\TugasModel;
use App\Models\DosenModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;


class TugasKompenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Tugas Kompen',
            'list' => ['Home', 'Daftar Tugas Kompen']
        ];

        $page = (object) [
            'title' => 'Daftar Tugas Kompen yang ada dalam sistem'
        ];

        $activeMenu = 'tugaskompen'; //set menu yang sedang aktif

        return view('tugaskompen.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $tugass = TugasModel::select(
            'tugas_id',
            'tugas_nama',
            'tugas_desc',
            'tugas_bobot',
            'tugas_file',
            'tugas_status',
            'tugas_tgl_dibuat',
            'tugas_tgl_deadline',
            'tugas_pembuat_id',
            'tugas_progress',
            'tugas_jenis'
        )->with('user');
        return DataTables::of($tugass)
            ->addIndexColumn()
            ->addColumn('pembuat', function ($tugas) {
                return $tugas->user ? $tugas->user->nama_pembuat : '-';
            })
            ->addColumn('aksi', function ($tugas) {
                $btn = '<button onclick="modalAction(\'' . url('/tugaskompen/' . $tugas->tugas_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tugaskompen/' . $tugas->tugas_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tugaskompen/' . $tugas->tugas_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function show_ajax(Request $request, string $id)
    {
        // Retrieve the tugas data with its related user and kompetensi
        $tugas = TugasModel::with(['user', 'kompetensi'])->find($id);

        if (!$tugas) {
            return response()->json(['status' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        return view('tugaskompen.show_ajax', compact('tugas'));
    }
}
