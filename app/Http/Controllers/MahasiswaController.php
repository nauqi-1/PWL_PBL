<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

use Yajra\DataTables\Facades\DataTables;
use App\Models\MahasiswaModel;
use App\Models\UserModel;

class MahasiswaController extends Controller
{
    public function index() {

        $breadcrumb = (object) [
            'title' => 'Daftar Mahasiswa',
            'list' => ['Home','Mahasiswa']
         ];

         $page = (object) [
            'title' => 'Mahasiswa yang terdaftar dalam sistem'
         ];

         $activeMenu = 'mahasiswa'; //set menu yang sedang aktif
         $kelasList = MahasiswaModel::select('mahasiswa_kelas')->distinct()->pluck('mahasiswa_kelas')->toArray();
         $prodiList = MahasiswaModel::select('mahasiswa_prodi')->distinct()->pluck('mahasiswa_prodi')->toArray();

         return view('mahasiswa.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kelasList' => $kelasList, 'prodiList' => $prodiList]);
    }

    public function list(Request $request){
        $mahasiswas = MahasiswaModel::select(
            'mahasiswa_id', 
            'mahasiswa_nama', 
            'mahasiswa_kelas',
            'mahasiswa_nim',
            'mahasiswa_prodi',
            'mahasiswa_noHp',
            'mahasiswa_alfa_sisa',
            'mahasiswa_alfa_total',
            'user_id'

        ) -> with('user');

        $filters = [];
        if ($request->has('mahasiswa_kelas') && $request->mahasiswa_kelas != '') {
            $filters['mahasiswa_kelas'] = $request->mahasiswa_kelas;
        }
    
        if ($request->has('mahasiswa_prodi') && $request->mahasiswa_prodi != '') {
            $filters['mahasiswa_prodi'] = $request->mahasiswa_prodi;
        }
    
        // filter gabungan dengan array
        if (!empty($filters)) {
            $mahasiswas->where($filters);
        }
        
        return DataTables::of($mahasiswas)
        ->addIndexColumn()  
        ->addColumn('aksi', function ($mahasiswa) { 
                   $btn = '<button onclick="modalAction(\''.url('/mahasiswa/' . $mahasiswa->mahasiswa_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/mahasiswa/' . $mahasiswa->mahasiswa_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/mahasiswa/' . $mahasiswa->mahasiswa_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            
                    return $btn; 
        }) 
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create_ajax() {

        return view('mahasiswa.create_ajax');
    }

    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username'              => 'required|string|unique:m_user,username|max:100',
                'password'              => 'required|string|min:6|max:100',
                'mahasiswa_nama'        => 'required|string|max:100',
                'mahasiswa_kelas'       => 'required|string|max:50',
                'mahasiswa_nim'         => 'required|string|unique:m_mahasiswa,mahasiswa_nim|max:50',
                'mahasiswa_prodi'       => 'required|string|max:50',
                'mahasiswa_noHp'        => 'required|string|max:50',
                'mahasiswa_alfa_sisa'   => 'required|integer',
                'mahasiswa_alfa_total'  => 'required|integer',
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
            $user->level_id = 4; 
            $user->save();
    
            $mahasiswa = new MahasiswaModel();
            $mahasiswa->user_id = $user->user_id; 
            $mahasiswa->mahasiswa_nama = $request->input('mahasiswa_nama');
            $mahasiswa->mahasiswa_kelas = $request->input('mahasiswa_kelas');
            $mahasiswa->mahasiswa_nim = $request->input('mahasiswa_nim');
            $mahasiswa->mahasiswa_prodi = $request->input('mahasiswa_prodi');
            $mahasiswa->mahasiswa_noHp = $request->input('mahasiswa_noHp');
            $mahasiswa->mahasiswa_alfa_sisa = $request->input('mahasiswa_alfa_sisa');
            $mahasiswa->mahasiswa_alfa_total = $request->input('mahasiswa_alfa_total');
            $mahasiswa->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan!',
            ]);
        }
    
        return redirect('/');
    }
    

    public function edit_ajax(string $id) {
        $mahasiswa = MahasiswaModel::find($id);

        return view('mahasiswa.edit_ajax', ['mahasiswa' => $mahasiswa]);
    }

    public function update_ajax(Request $request, $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'username'              => 'nullable|string|unique:m_user,username|max:100',
            'password'              => 'nullable|string|min:6|max:100',
            'mahasiswa_nama'        => 'required|string|max:100',
            'mahasiswa_kelas'       => 'required|string|max:50',
            'mahasiswa_nim'         => 'nullable|string|unique:m_mahasiswa,mahasiswa_nim|max:50',
            'mahasiswa_prodi'       => 'required|string|max:50',
            'mahasiswa_noHp'        => 'required|string|max:50',
            'mahasiswa_alfa_sisa'   => 'required|integer',
            'mahasiswa_alfa_total'  => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $check = MahasiswaModel::find($id);
        
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
        $mahasiswa = MahasiswaModel::find($id);

        return view('mahasiswa.confirm_ajax', ['mahasiswa' => $mahasiswa]);
    } 

    public function delete_ajax(Request $request, $id) {
        if ($request -> ajax() || $request -> wantsJson()) {
            $mahasiswa = MahasiswaModel::find($id);

            if ($mahasiswa) {
                $mahasiswa->delete();
                $mahasiswa->user->delete();
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
        $mahasiswa = MahasiswaModel::select('mahasiswa_nama','mahasiswa_kelas','mahasiswa_nim','mahasiswa_prodi','mahasiswa_noHp','mahasiswa_alfa_sisa','mahasiswa_alfa_total', 'user_id') 
        ->with(['user' => function($query) {
            $query->select('user_id','username'); 
        }])     
        ->orderBy('mahasiswa_nim')
            
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('mahasiswa.export_pdf', ['mahasiswa' => $mahasiswa]);
        $pdf->setPaper('a4', 'landscape'); // set ukuran kertas dan orientasi $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream ('Data Mahasiswa '.date('Y-m-d H:i:s').'.pdf');
    }

    public function show_ajax(string $id) {
        $mahasiswa = MahasiswaModel::find($id);

        return view('mahasiswa.show_ajax', ['mahasiswa' => $mahasiswa]);
    } 

    public function import() {
        return view('mahasiswa.import');
    }
 
    public function import_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            
            $rules = [
                'file_mahasiswa' => ['required', 'mimes:xlsx', 'max:1024'], 
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_mahasiswa');
    
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
            
                            $insert[] = MahasiswaModel::create([
                                'mahasiswa_nim'        => $value['A'],
                                'mahasiswa_nama'       => $value['B'], 
                                'mahasiswa_kelas'      => $value['C'], 
                                'mahasiswa_prodi'      => $value['D'], 
                                'mahasiswa_noHp'       => $value['E'], 
                                'mahasiswa_alfa_sisa'  => $value['F'], 
                                'mahasiswa_alfa_total' => $value['G'], 
                                'user_id'              => $user->user_id, // Foreign key user
                                'created_at'           => now(),
                            ]);
                        }
                    }
            
                    
                    if (count($insert) > 0) {
                        //MahasiswaModel::insertOrIgnore($insertMahasiswa);
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
        $mahasiswa = MahasiswaModel::select( 'mahasiswa_nama') 
                -> orderBy('mahasiswa_nama')
                -> get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet-> getActiveSheet();

        $sheet->setCellValue('A1','No');
        $sheet->setCellValue('B1','Mahasiswa');

        $no = 1;
        $baris = 2;
        foreach($mahasiswa as $key => $value) {
            $sheet->setCellValue('A'.$baris,$no);
            $sheet->setCellValue('B'.$baris,$value -> mahasiswa_nama);
            $baris++;
            $no++;
        }

        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); //set ukuran kolom otomatis
        }

        $sheet->setTitle('Data Mahasiswa');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Mahasiswa' . date('Y-m-d H:i:s'). '.xlsx';

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
