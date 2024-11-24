<?php

namespace App\Http\Controllers;

use App\Models\PeriodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Securities\Price;
use Yajra\DataTables\Facades\DataTables;

class PeriodeController extends Controller
{
    public function index() {

        $breadcrumb = (object) [
            'title' => 'Daftar Periode',
            'list' => ['Home','Periode']
         ];

         $page = (object) [
            'title' => 'Laman manajemen periode perkuliahan.'
         ];

         $activeMenu = 'periode'; //set menu yang sedang aktif

         return view('periode.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    public function list(){
        $periodes = PeriodeModel::select('periode_id', 'periode');

        
        return DataTables::of($periodes)
        ->addIndexColumn()  
        ->addColumn('aksi', function ($periodes) { 
                   //$btn = '<button onclick="modalAction(\''.url('/periode/' . $periode->periode_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn = '<button onclick="modalAction(\''.url('/periode/' . $periodes->periode_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/periode/' . $periodes->periode_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            
                    return $btn; 
        }) 
        ->rawColumns(['aksi'])
        ->make(true);
    }
    public function create_ajax() {

        return view('periode.create_ajax');
    }

    public function store_ajax(Request $request) {

        if ($request -> ajax() || $request -> wantsJson()) {
            $rules = [
                'periode'  => 'required|unique:m_periode,periode|string|max:20',
            ];

            $validator = Validator::make($request -> all(),$rules);

            if ($validator -> fails()) {
                return response() -> json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            PeriodeModel:: create($request->all());
            return response() -> json([
                'status' => true,
                'message' => 'Data berhasil disimpan!'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id) {
        $periode = PeriodeModel::find($id);

        return view('periode.edit_ajax', ['periode' => $periode]);
    }

    public function update_ajax(Request $request, $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
                'periode'  => 'required|string|max:20|unique:m_periode,periode',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $check = PeriodeModel::find($id);
        
        if ($check) {
            $check->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    return redirect('/');
    }

}
