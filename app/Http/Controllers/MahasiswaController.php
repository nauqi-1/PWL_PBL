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
use Illuminate\Support\Facades\Hash;

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
            'mahasiswa_alfa_lunas',
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
                'mahasiswa_nim'         => 'required|unique:m_mahasiswa,mahasiswa_nim|max:50|regex:/^\d+$/',
                'mahasiswa_prodi'       => 'required|string|max:50',
                'mahasiswa_noHp'        => 'required|string|max:50|regex:/^\d+$/',
                'mahasiswa_alfa_lunas'   => 'required|integer',
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
            $mahasiswa->mahasiswa_alfa_lunas = $request->input('mahasiswa_alfa_lunas');
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
                'username'              => 'nullable|string|unique:m_user,username,' . $id . ',user_id|max:100', // Allow existing username for the same user
                'password'              => 'nullable|string|min:6|max:100',
                'mahasiswa_nama'        => 'required|string|max:100',
                'mahasiswa_kelas'       => 'required|string|max:50',
                'mahasiswa_nim'         => 'nullable|string|unique:m_mahasiswa,mahasiswa_nim,' . $id . ',mahasiswa_id|max:50', // Allow existing NIM for the same mahasiswa
                'mahasiswa_prodi'       => 'required|string|max:50',
                'mahasiswa_noHp'        => 'required|string|max:50',
                'mahasiswa_alfa_lunas'   => 'required|integer',

            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
    
            $mahasiswa = MahasiswaModel::find($id);
    
            if ($mahasiswa) {
                // Update Mahasiswa 
                $mahasiswa->update([
                    'mahasiswa_nama'        => $request->mahasiswa_nama,
                    'mahasiswa_kelas'       => $request->mahasiswa_kelas,
                    'mahasiswa_prodi'       => $request->mahasiswa_prodi,
                    'mahasiswa_noHp'        => $request->mahasiswa_noHp,
                    'mahasiswa_alfa_lunas'   => $request->mahasiswa_alfa_lunas,
                ]);
                //update usernya
                $user = UserModel::find($mahasiswa->user_id);
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
        $mahasiswa = MahasiswaModel::find($id);
        $totalJumlahAlfa = $mahasiswa->mahasiswa_alfa->sum('jumlah_alfa');

        return view('mahasiswa.confirm_ajax', ['mahasiswa' => $mahasiswa, 'totalJumlahAlfa' => $totalJumlahAlfa]);
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
        $mahasiswa = MahasiswaModel::select('mahasiswa_nama', 'mahasiswa_kelas', 'mahasiswa_nim', 'mahasiswa_prodi', 'mahasiswa_noHp', 'mahasiswa_alfa_lunas', 'user_id')
            ->with(['user' => function($query) {
                $query->select('user_id', 'username'); 
            }])
            ->with(['mahasiswa_alfa'])
            ->orderBy('mahasiswa_nim')
            ->get();

    
        $pdf = Pdf::loadView('mahasiswa.export_pdf', ['mahasiswa' => $mahasiswa]);
        $pdf->setPaper('a4', 'landscape'); 
        return $pdf->stream('Data Mahasiswa '.date('Y-m-d H:i:s').'.pdf');
    }
    

    public function show_ajax(string $id) {
        $mahasiswa = MahasiswaModel::find($id);
        $totalJumlahAlfa = $mahasiswa->mahasiswa_alfa->sum('jumlah_alfa');

        return view('mahasiswa.show_ajax', ['mahasiswa' => $mahasiswa, 'totalJumlahAlfa' => $totalJumlahAlfa]);
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
                                'password' => Hash::make('H'),
                                'level_id' => 4,
                            ]);
            
                            $insert[] = MahasiswaModel::create([
                                'mahasiswa_nim'        => $value['A'],
                                'mahasiswa_nama'       => $value['B'], 
                                'mahasiswa_kelas'      => $value['C'], 
                                'mahasiswa_prodi'      => $value['D'], 
                                'mahasiswa_noHp'       => $value['E'], 
                                'mahasiswa_alfa_lunas'  => $value['F'], 
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
        $mahasiswa = MahasiswaModel::select('mahasiswa_nim', 'mahasiswa_nama', 'mahasiswa_kelas', 'mahasiswa_prodi', 'mahasiswa_noHp', 'user_id')
                ->with(['user' => function($query) {
                    $query->select('user_id','username'); 
                }]) 
                -> orderBy('mahasiswa_nama')
                -> get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet-> getActiveSheet();

        $sheet->setCellValue('A1','No');
        $sheet->setCellValue('B1','Username');
        $sheet->setCellValue('C1','NIM');
        $sheet->setCellValue('D1','Nama');
        $sheet->setCellValue('E1','Kelas');
        $sheet->setCellValue('F1','Program Studi');
        $sheet->setCellValue('G1','No. HP');

        $no = 1;
        $baris = 2;
        foreach($mahasiswa as $key => $value) {
            $sheet->setCellValue('A'.$baris,$no);
            $sheet->setCellValue('B'.$baris,$value->user->username);
            $sheet->setCellValue('C'.$baris,$value -> mahasiswa_nim);
            $sheet->setCellValue('D'.$baris,$value -> mahasiswa_nama);
            $sheet->setCellValue('E'.$baris,$value -> mahasiswa_kelas);
            $sheet->setCellValue('F'.$baris,$value -> mahasiswa_prodi);
            $sheet->setCellValue('G'.$baris,$value -> mahasiswa_noHp);
            $baris++;
            $no++;
        }

        foreach(range('A', 'G') as $columnID) {
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
