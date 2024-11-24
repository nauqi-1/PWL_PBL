<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

use Yajra\DataTables\Facades\DataTables;
use App\Models\TendikModel;
use App\Models\TugasModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class TendikController extends Controller
{
    public function index() {

        $breadcrumb = (object) [
            'title' => 'Daftar Tendik',
            'list' => ['Home','Tendik']
         ];

         $page = (object) [
            'title' => 'Tendik yang terdaftar dalam sistem'
         ];

         $activeMenu = 'tendik'; //set menu yang sedang aktif
         //$prodiList = TendikModel::select('tendik_prodi')->distinct()->pluck('tendik_prodi')->toArray();

         //return view('tendik.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'prodiList' => $prodiList]);
         return view('tendik.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $tendiks = TendikModel::select(
            'tendik_id', 
            'tendik_nama',
            'tendik_nip',
            //'tendik_prodi',
            'tendik_noHp',
            'user_id'

        ) -> with('user');

        $filters = [];
        
    
        /*if ($request->has('tendik_prodi') && $request->tendik_prodi != '') {
            $filters['tendik_prodi'] = $request->tendik_prodi;
        }
    
        // filter gabungan dengan array
        if (!empty($filters)) {
            $tendiks->where($filters);
        }*/
        
        return DataTables::of($tendiks)
        ->addIndexColumn()  
        ->addColumn('aksi', function ($tendik) { 
                   $btn = '<button onclick="modalAction(\''.url('/tendik/' . $tendik->tendik_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/tendik/' . $tendik->tendik_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/tendik/' . $tendik->tendik_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            
                    return $btn; 
        }) 
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create_ajax() {

        return view('tendik.create_ajax');
    }

    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username'              => 'required|string|unique:m_user,username|max:100',
                'password'              => 'required|string|min:6|max:100',
                'tendik_nama'        => 'required|string|max:100',
                'tendik_nip'         => 'nullable|string|unique:m_tendik,tendik_nip|max:50',
                //'tendik_prodi'       => 'required|string|max:50',
                'tendik_noHp'        => 'required|string|max:50',
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
            $user->level_id = 3; 
            $user->save();
    
            $tendik = new TendikModel();
            $tendik->user_id = $user->user_id; 
            $tendik->tendik_nama = $request->input('tendik_nama');
            $tendik->tendik_nip = $request->input('tendik_nip');
            //$tendik->tendik_prodi = $request->input('tendik_prodi');
            $tendik->tendik_noHp = $request->input('tendik_noHp');
            $tendik->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan!',
            ]);
        }
    
        return redirect('/');
    }
    

    public function edit_ajax(string $id) {
        $tendik = TendikModel::find($id);

        return view('tendik.edit_ajax', ['tendik' => $tendik]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username'              => 'nullable|string|unique:m_user,username,' . $id . ',user_id|max:100', // Allow existing username for the same user
                'password'              => 'nullable|string|min:6|max:100',
                'tendik_nama'        => 'required|string|max:100',
                'tendik_nip'         => 'nullable|string|unique:m_tendik,tendik_nip|max:50',
                //'tendik_prodi'       => 'required|string|max:50',
                'tendik_noHp'        => 'required|string|max:50',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
    
            $tendik = TendikModel::find($id);
    
            if ($tendik) {
                // Update Tendik 
                $tendik->update([
                    'tendik_nama'        => $request->tendik_nama,
                    //'tendik_prodi'       => $request->tendik_prodi,
                    'tendik_noHp'        => $request->tendik_noHp,
                ]);
                //update usernya
                $user = UserModel::find($tendik->user_id);
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
        $tendik = TendikModel::find($id);

        return view('tendik.confirm_ajax', ['tendik' => $tendik]);
    } 

    public function delete_ajax(Request $request, $id) {
        if ($request -> ajax() || $request -> wantsJson()) {
            $tendik = TendikModel::find($id);

            if ($tendik) {
                $tendik->delete();
                $tendik->user->delete();
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
        //$tendik = TendikModel::select('tendik_nama','tendik_nip','tendik_prodi','tendik_noHp', 'user_id')
        $tendik = TendikModel::select('tendik_nama','tendik_nip','tendik_noHp', 'user_id') 
        ->with(['user' => function($query) {
            $query->select('user_id','username'); 
        }])
        ->withCount('tugas')    
        ->orderBy('tendik_nama')
            
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('tendik.export_pdf', ['tendik' => $tendik]);
        $pdf->setPaper('a4', 'landscape'); // set ukuran kertas dan orientasi $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream ('Data Tendik '.date('Y-m-d H:i:s').'.pdf');
    }

    public function show_ajax(string $id) {
        $tendik = TendikModel::find($id);
        $tugasCount = TugasModel::where('tugas_pembuat_id', $tendik->user_id)->count();

        return view('tendik.show_ajax', ['tendik' => $tendik, 'tugasCount' => $tugasCount]);
    } 

    public function import() {
        return view('tendik.import');
    }
 
    public function import_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            
            $rules = [
                'file_tendik' => ['required', 'mimes:xlsx', 'max:1024'], 
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_tendik');
    
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
            
                            $insert[] = TendikModel::create([
                                'tendik_nim'        => $value['A'],
                                'tendik_nama'       => $value['B'], 
                                'tendik_kelas'      => $value['C'], 
                                //'tendik_prodi'      => $value['D'], 
                                //'tendik_noHp'       => $value['E'],
                                'tendik_noHp'       => $value['D'], 
                                //'tendik_alfa_sisa'  => $value['F'], 
                                //'tendik_alfa_total' => $value['G'], 
                                'user_id'              => $user->user_id, // Foreign key user
                                'created_at'           => now(),
                            ]);
                        }
                    }
            
                    
                    if (count($insert) > 0) {
                        //TendikModel::insertOrIgnore($insertTendik);
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
        $tendik = TendikModel::select('tendik_nip', 'tendik_nama', 'tendik_noHp', 'user_id')
                ->with(['user' => function($query) {
                    $query->select('user_id','username'); 
                }])
                ->withCount('tugas')
                -> orderBy('tendik_nama')
                -> get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet-> getActiveSheet();

        $sheet->setCellValue('A1','No');
        $sheet->setCellValue('B1','Username');
        $sheet->setCellValue('C1','NIP');
        $sheet->setCellValue('D1','Nama');
        $sheet->setCellValue('E1','No. HP');
        $sheet->setCellValue('F1','Jumlah Tugas');

        $no = 1;
        $baris = 2;
        foreach($tendik as $key => $value) {
            $sheet->setCellValue('A'.$baris,$no);
            $sheet->setCellValue('B'.$baris,$value->user->username);
            $sheet->setCellValue('C'.$baris,$value -> tendik_nip);
            $sheet->setCellValue('D'.$baris,$value -> tendik_nama);
            $sheet->setCellValue('E'.$baris,$value -> tendik_noHp);
            $sheet->setCellValue('F'.$baris,$value -> tugas_count);
            $baris++;
            $no++;
        }

        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); //set ukuran kolom otomatis
        }

        $sheet->setTitle('Data Tendik');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Tendik' . date('Y-m-d H:i:s'). '.xlsx';

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
