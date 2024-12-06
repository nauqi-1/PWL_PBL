<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TugasModel;
use App\Models\RequestModel;
use Yajra\DataTables\Facades\DataTables;

class RequestTugasController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Request Tugas',
            'list' => ['Home', 'Request Tugas']
        ];

        $page = (object) [
            'title' => 'Request Tugas yang terdaftar dalam sistem'
        ];

        $activeMenu = 'requesttugas'; // Set menu yang sedang aktif

        return view('requesttugas.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $requesttugass = RequestModel::with(['tugas', 'pembuat', 'mahasiswa'])->get();
        return DataTables::of($requesttugass)
            ->addIndexColumn()
            ->addColumn('pembuat', function ($requesttugas) {
                if ($requesttugas->pembuat && in_array($requesttugas->pembuat->level_id, [1, 2, 3])) {
                    return $requesttugas->pembuat->nama_pembuat;
                }
                return null; // Return null 
            })
            ->addColumn('aksi', function ($requesttugas) {
                $btn = '<button onclick="modalAction(\'' . url('/requesttugas/' . $requesttugas->request_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/requesttugas/' . $requesttugas->request_id . '/accept_ajax') . '\')" class="btn btn-success btn-sm">Terima</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/requesttugas/' . $requesttugas->request_id . '/denied_ajax') . '\')" class="btn btn-danger btn-sm">Tolak</button> ';

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function show_ajax(Request $request, string $id)
    {
        // Retrieve the tugas data with its related user and kompetensi
        $requests = RequestModel::with(['tugas', 'mahasiswa', 'pembuat'])->find($id);

        if (!$requests) {
            return response()->json(['status' => false, 'message' => 'Request tidak ditemukan'], 404);
        }

        return view('requesttugas.show_ajax', compact('requests'));
    }
}
