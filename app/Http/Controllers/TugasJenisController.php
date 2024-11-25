<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
    public function create_ajax()
    {
        // Mengembalikan view untuk form tambah jenis tugas
        return view('tugasjenis.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'jenis_nama' => 'required|string|unique:t_tugas_jenis,jenis_nama|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Menyimpan data ke database
            $jenis = new TugasJenisModel();
            $jenis->jenis_nama = $request->input('jenis_nama');
            $jenis->save();

            return response()->json([
                'status' => true,
                'message' => 'Jenis Tugas berhasil disimpan!',
            ]);
        }

        return redirect('/');
    }
    public function edit_ajax(string $id)
    {
        $tugasjenis = TugasJenisModel::find($id);

        if (!$tugasjenis) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return view('tugasjenis.edit_ajax', ['tugasjenis' => $tugasjenis]);
    }
    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'jenis_nama' => 'required|string|unique:t_tugas_jenis,jenis_nama,' . $id . ',jenis_id|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $tugasjenis = TugasJenisModel::find($id);

            if ($tugasjenis) {
                $tugasjenis->update([
                    'jenis_nama' => $request->jenis_nama,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        }

        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $tugasJenis = TugasJenisModel::find($id);

        return view('tugasjenis.confirm_ajax', ['tugasJenis' => $tugasJenis]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $tugasJenis = TugasJenisModel::find($id);

            if ($tugasJenis) {
                $tugasJenis->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus!'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan!'
                ]);
            }
        }
        return redirect('/');
    }
}
