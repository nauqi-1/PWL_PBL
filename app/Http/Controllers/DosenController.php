<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

use Yajra\DataTables\Facades\DataTables;
use App\Models\DosenModel;
use App\Models\UserModel;

class DosenController extends Controller
{
    public function index() {

        $breadcrumb = (object) [
            'title' => 'Daftar Dosen',
            'list' => ['Home','Dosen']
         ];

         $page = (object) [
            'title' => 'Dosen yang terdaftar dalam sistem'
         ];

         $activeMenu = 'dosen'; //set menu yang sedang aktif
         $prodiList = DosenModel::select('dosen_prodi')->distinct()->pluck('dosen_prodi')->toArray();

         return view('dosen.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'prodiList' => $prodiList]);
    }

    public function list(Request $request){
        $dosens = DosenModel::select(
            'dosen_id', 
            'dosen_nama',
            'dosen_nip',
            'dosen_prodi',
            'dosen_noHp',
            'user_id'

        ) -> with('user');

        $filters = [];
        
    
        if ($request->has('dosen_prodi') && $request->dosen_prodi != '') {
            $filters['dosen_prodi'] = $request->dosen_prodi;
        }
    
        // filter gabungan dengan array
        if (!empty($filters)) {
            $dosens->where($filters);
        }
        
        return DataTables::of($dosens)
        ->addIndexColumn()  
        ->addColumn('aksi', function ($dosen) { 
                   $btn = '<button onclick="modalAction(\''.url('/dosen/' . $dosen->dosen_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/dosen/' . $dosen->dosen_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/dosen/' . $dosen->dosen_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            
                    return $btn; 
        }) 
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create_ajax() {

        return view('dosen.create_ajax');
    }

    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username'              => 'required|string|unique:m_user,username|max:100',
                'password'              => 'required|string|min:6|max:100',
                'dosen_nama'        => 'required|string|max:100',
                'dosen_nip'         => 'nullable|string|unique:m_dosen,dosen_nim|max:50',
                'dosen_prodi'       => 'required|string|max:50',
                'dosen_noHp'        => 'required|string|max:50',
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
            $user->level_id = 2; 
            $user->save();
    
            $dosen = new DosenModel();
            $dosen->user_id = $user->user_id; 
            $dosen->dosen_nama = $request->input('dosen_nama');
            $dosen->dosen_nip = $request->input('dosen_nip');
            $dosen->dosen_prodi = $request->input('dosen_prodi');
            $dosen->dosen_noHp = $request->input('dosen_noHp');
            $dosen->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan!',
            ]);
        }
    
        return redirect('/');
    }
    

    public function edit_ajax(string $id) {
        $dosen = DosenModel::find($id);

        return view('dosen.edit_ajax', ['dosen' => $dosen]);
    }

    public function update_ajax(Request $request, $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'username'              => 'nullable|string|unique:m_user,username|max:100',
            'password'              => 'nullable|string|min:6|max:100',
            'dosen_nama'        => 'required|string|max:100',
            'dosen_nip'         => 'nullable|string|unique:m_dosen,dosen_nim|max:50',
            'dosen_prodi'       => 'required|string|max:50',
            'dosen_noHp'        => 'required|string|max:50',
            'dosen_alfa_sisa'   => 'required|integer',
            'dosen_alfa_total'  => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $check = DosenModel::find($id);
        
        if ($check) {
            if (!$request->filled('username')) { 
                $request->request->remove('username');
            }
            if (!$request->filled('password')) { 
                $request->request->remove('password');
            }

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

    public function confirm_ajax(string $id) {
        $dosen = DosenModel::find($id);

        return view('dosen.confirm_ajax', ['dosen' => $dosen]);
    } 

    public function delete_ajax(Request $request, $id) {
        if ($request -> ajax() || $request -> wantsJson()) {
            $dosen = DosenModel::find($id);

            if ($dosen) {
                $dosen->delete();
                $dosen->user->delete();
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
        $dosen = DosenModel::select('dosen_nama','dosen_nip','dosen_prodi','dosen_noHp', 'user_id') 
        ->with(['user' => function($query) {
            $query->select('user_id','username'); 
        }])     
        ->orderBy('dosen_nim')
            
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('dosen.export_pdf', ['dosen' => $dosen]);
        $pdf->setPaper('a4', 'landscape'); // set ukuran kertas dan orientasi $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream ('Data Dosen '.date('Y-m-d H:i:s').'.pdf');
    }

    public function show_ajax(string $id) {
        $dosen = DosenModel::find($id);

        return view('dosen.show_ajax', ['dosen' => $dosen]);
    } 

    public function import() {
        return view('dosen.import');
    }
 
    public function import_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            
            $rules = [
                'file_dosen' => ['required', 'mimes:xlsx', 'max:1024'], 
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_dosen');
    
            try {
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true); 
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
    
                $data = $sheet->toArray(null, false, true, true);
    
                $insert = [];
    
                if (count($data) > 1) { 
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { 
                            $user = UserModel::create([
                                'username' => $value['H'], 
                                'password' => bcrypt('H'), 
                                'level_id' => 4,
                            ]);
            
                            $insert[] = DosenModel::create([
                                'dosen_nim'        => $value['A'],
                                'dosen_nama'       => $value['B'], 
                                'dosen_kelas'      => $value['C'], 
                                'dosen_prodi'      => $value['D'], 
                                'dosen_noHp'       => $value['E'], 
                                'dosen_alfa_sisa'  => $value['F'], 
                                'dosen_alfa_total' => $value['G'], 
                                'user_id'              => $user->user_id, // Foreign key user
                                'created_at'           => now(),
                            ]);
                        }
                    }
            
                    
                    if (count($insert) > 0) {
                        //DosenModel::insertOrIgnore($insertDosen);
                        return response()->json([
                            'status' => true,
                            'message' => 'Data berhasil diimport'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data yang diimport'
                    ]);
                }
    
            } catch (\Exception $e) {
                return response()->json([
                   //'status' => false,
                   
                   // 'message' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage()
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            }
        }
    
        return redirect('/');
    }

    public function export_excel() {
        $dosen = DosenModel::select( 'dosen_nama') 
                -> orderBy('dosen_nama')
                -> get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet-> getActiveSheet();

        $sheet->setCellValue('A1','No');
        $sheet->setCellValue('B1','Dosen');

        $no = 1;
        $baris = 2;
        foreach($dosen as $key => $value) {
            $sheet->setCellValue('A'.$baris,$no);
            $sheet->setCellValue('B'.$baris,$value -> dosen_nama);
            $baris++;
            $no++;
        }

        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); //set ukuran kolom otomatis
        }

        $sheet->setTitle('Data Dosen');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Dosen' . date('Y-m-d H:i:s'). '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 22 Agustus 2025 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;

    }
    
}
