<?php

namespace App\Http\Controllers;

use App\Models\AdminModel;
use App\Models\TugasModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index() {

        $breadcrumb = (object) [
            'title' => 'Daftar Admin',
            'list' => ['Home','Admin']
         ];

         $page = (object) [
            'title' => 'Admin yang terdaftar dalam sistem'
         ];

         $activeMenu = 'admin'; //set menu yang sedang aktif
         $prodiList = AdminModel::select('admin_prodi')->distinct()->pluck('admin_prodi')->toArray();

         return view('admin.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'prodiList' => $prodiList]);
    }

    public function list(Request $request){
        $admins = AdminModel::select(
            'admin_id', 
            'admin_nama',
            'admin_prodi',
            'admin_noHp',
            'user_id'

        ) -> with('user');

        $filters = [];
        
    
        if ($request->has('admin_prodi') && $request->admin_prodi != '') {
            $filters['admin_prodi'] = $request->admin_prodi;
        }
    
        // filter gabungan dengan array
        if (!empty($filters)) {
            $admins->where($filters);
        }
        
        return DataTables::of($admins)
        ->addIndexColumn()  
        ->addColumn('aksi', function ($admin) { 
                   $btn = '<button onclick="modalAction(\''.url('/admin/' . $admin->admin_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/admin/' . $admin->admin_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/admin/' . $admin->admin_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            
                    return $btn; 
        }) 
        ->rawColumns(['aksi'])
        ->make(true);
    }
    public function create_ajax() {

        return view('admin.create_ajax');
    }

    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username'              => 'required|string|unique:m_user,username|max:100',
                'password'              => 'required|string|min:6|max:100',
                'admin_nama'        => 'required|string|max:100',
                'admin_prodi'       => 'required|string|max:50',
                'admin_noHp'        => 'required|string|max:50',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $user = new UserModel(); 
            $user->username = $request->input('username');
            $user->password = bcrypt($request->input('password'));
            $user->level_id = 1; 
            $user->save();
    
            $admin = new AdminModel();
            $admin->user_id = $user->user_id; 
            $admin->admin_nama = $request->input('admin_nama');
            $admin->admin_prodi = $request->input('admin_prodi');
            $admin->admin_noHp = $request->input('admin_noHp');
            $admin->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan!',
            ]);
        }
    
        return redirect('/');
    }

    public function show_ajax(string $id) {
        $admin= AdminModel::find($id);
        $tugasCount = TugasModel::where('tugas_pembuat_id', $admin->user_id)->count();

        return view('admin.show_ajax', ['admin' => $admin, 'tugasCount' => $tugasCount]);
    } 

    public function edit_ajax(string $id) {
        $admin = AdminModel::find($id);

        return view('admin.edit_ajax', ['admin' => $admin]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username'              => 'nullable|string|unique:m_user,username,' . $id . ',user_id|max:100', // Allow existing username for the same user
                'password'              => 'nullable|string|min:6|max:100',
                'admin_nama'        => 'required|string|max:100',
                'admin_prodi'       => 'required|string|max:50',
                'admin_noHp'        => 'required|string|max:50',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
    
            $admin = AdminModel::find($id);
    
            if ($admin) {
                $admin->update([
                    'admin_nama'        => $request->admin_nama,
                    'admin_prodi'       => $request->admin_prodi,
                    'admin_noHp'        => $request->admin_noHp,
                ]);
                $user = UserModel::find($admin->user_id);
                if ($user) {
                    $userData = [];
                    if ($request->filled('username')) {
                        $userData['username'] = $request->username;
                    }
                    if ($request->filled('password')) {
                        $userData['password'] = Hash::make($request->password);
                    }
                    if (!empty($userData)) {
                        $user->update($userData);
                    }
                }
    
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

    public function confirm_ajax(string $id) {
        $admin = AdminModel::find($id);

        return view('admin.confirm_ajax', ['admin' => $admin]);
    } 

    public function delete_ajax(Request $request, $id) {
        if ($request -> ajax() || $request -> wantsJson()) {
            $admin = AdminModel::find($id);

            if ($admin) {
                $admin->delete();
                $admin->user->delete();
                return response() -> json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus!'
                ]);
            } else {
                return response() -> json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan!'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_pdf() {
        $admin = AdminModel::select('admin_nama','admin_prodi','admin_noHp', 'user_id') 
        ->with(['user' => function($query) {
            $query->select('user_id','username'); 
        }])
        ->withCount('tugas')    
        ->orderBy('admin_nama')
            
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('admin.export_pdf', ['admin' => $admin]);
        $pdf->setPaper('a4', 'landscape'); // set ukuran kertas dan orientasi $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream ('Data Admin '.date('Y-m-d H:i:s').'.pdf');
    }
    

}
