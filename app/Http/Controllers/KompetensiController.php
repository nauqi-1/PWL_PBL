<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

use Yajra\DataTables\Facades\DataTables;
use App\Models\KompetensiModel;

class KompetensiController extends Controller
{
    public function index() {

        $breadcrumb = (object) [
            'title' => 'Daftar Kompetensi',
            'list' => ['Home','Kompetensi']
         ];

         $page = (object) [
            'title' => 'Daftar kompetensi untuk tugas'
         ];

         $activeMenu = 'kompetensi'; //set menu yang sedang aktif

         return view('kompetensi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $kompetensis = KompetensiModel::select('kompetensi_id', 'kompetensi_nama');

        
        return DataTables::of($kompetensis)
        ->addIndexColumn()  
        ->addColumn('aksi', function ($kompetensi) { 
                   //$btn = '<button onclick="modalAction(\''.url('/kompetensi/' . $kompetensi->kompetensi_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn = '<button onclick="modalAction(\''.url('/kompetensi/' . $kompetensi->kompetensi_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/kompetensi/' . $kompetensi->kompetensi_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            
                    return $btn; 
        }) 
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create_ajax() {

        return view('kompetensi.create_ajax');
    }

    public function store_ajax(Request $request) {

        if ($request -> ajax() || $request -> wantsJson()) {
            $rules = [
                'kompetensi_nama'  => 'required|unique:m_kompetensi,kompetensi_nama|string|max:50',
            ];

            $validator = Validator::make($request -> all(),$rules);

            if ($validator -> fails()) {
                return response() -> json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            KompetensiModel:: create($request->all());
            return response() -> json([
                'status' => true,
                'message' => 'Data berhasil disimpan!'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id) {
        $kompetensi = KompetensiModel::find($id);

        return view('kompetensi.edit_ajax', ['kompetensi' => $kompetensi]);
    }

    public function update_ajax(Request $request, $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
                'kompetensi_nama'  => 'required|string|max:50|unique:m_kompetensi,kompetensi_nama',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $check = KompetensiModel::find($id);
        
        if ($check) {
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
        $kompetensi = KompetensiModel::find($id);

        return view('kompetensi.confirm_ajax', ['kompetensi' => $kompetensi]);
    } 

    public function delete_ajax(Request $request, $id) {
        if ($request -> ajax() || $request -> wantsJson()) {
            $kompetensi = KompetensiModel::find($id);

            if ($kompetensi) {
                $kompetensi->delete();
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
        $kompetensi = KompetensiModel::select('kompetensi_nama',) 
            ->orderBy('kompetensi_nama') 
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('kompetensi.export_pdf', ['kompetensi' => $kompetensi]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream ('Data Barang '.date('Y-m-d H:i:s').'.pdf');
    }

    public function show_ajax(string $id) {
        $kompetensi = KompetensiModel::find($id);

        return view('kompetensi.show_ajax', ['kompetensi' => $kompetensi]);
    } 

    public function import() {
        return view('kompetensi.import');
    }
 
    public function import_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            
            $rules = [
                'file_kompetensi' => ['required', 'mimes:xlsx', 'max:1024'], 
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_kompetensi');
    
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
                            $insert[] = [
                                'kompetensi_nama' => $value['A'],
                                'created_at' => now(),
                            ];
                        }
                    }
    
                    if (count($insert) > 0) {
                        KompetensiModel::insertOrIgnore($insert);
                    }
    
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data yang diimport'
                    ]);
                }
    
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage()
                ]);
            }
        }
    
        return redirect('/');
    }

    public function export_excel() {
        $kompetensi = KompetensiModel::select( 'kompetensi_nama') 
                -> orderBy('kompetensi_nama')
                -> get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet-> getActiveSheet();

        $sheet->setCellValue('A1','No');
        $sheet->setCellValue('B1','Kompetensi');

        $no = 1;
        $baris = 2;
        foreach($kompetensi as $key => $value) {
            $sheet->setCellValue('A'.$baris,$no);
            $sheet->setCellValue('B'.$baris,$value -> kompetensi_nama);
            $baris++;
            $no++;
        }

        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); //set ukuran kolom otomatis
        }

        $sheet->setTitle('Data Kompetensi');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kompetensi' . date('Y-m-d H:i:s'). '.xlsx';

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
