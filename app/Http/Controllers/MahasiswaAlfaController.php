<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaAlfaModel;
use App\Models\MahasiswaModel;
use App\Models\PeriodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class MahasiswaAlfaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Alfa Mahasiswa',
            'list' => ['Home', 'Alfa']
        ];

        $page = (object) [
            'title' => 'Daftar jam alfa mahasiswa per periode.'
        ];

        $mahasiswa = MahasiswaModel::all();
        $periode = PeriodeModel::all();

        $activeMenu = 'mahasiswa_alfa'; //set menu yang sedang aktif

        return view('mahasiswa_alfa.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu, 
            'mahasiswa' => $mahasiswa,
            'periode'   => $periode
        ]);
    }

    public function list(Request $request)
    {
        $alfas = MahasiswaAlfaModel::select(
            'mahasiswa_alfa_id',
            'mahasiswa_id',
            'periode_id',
            'jumlah_alfa',
        )
        ->with('mahasiswa', 'periode');

        if ($request->periode_id) {
            $alfas->where('periode_id', $request->periode_id);
        }
        if ($request->mahasiswa_id) {
            $alfas->where('mahasiswa_id', $request->mahasiswa_id);
        }
        if (Auth::user()->level->level_kode == 'ADM'){
        return DataTables::of($alfas)
            ->addIndexColumn()
            ->addColumn('aksi', function ($alfa) {
                $btn = '<button onclick="modalAction(\'' . url('/mahasiswa_alfa/' . $alfa->mahasiswa_alfa_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa_alfa/' . $alfa->mahasiswa_alfa_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
        } elseif (Auth::user()->level->level_kode != 'ADM'){
            return DataTables::of($alfas)
            ->addIndexColumn()
            ->addColumn('aksi', function ($alfa) {
                $btn = '<button onclick="modalAction(\'' . url('/mahasiswa_alfa/' . $alfa->mahasiswa_alfa_id . '/show_ajax') . '\')" class="btn btn-warning btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
        }
    }
    public function create_ajax() {
        $periode = PeriodeModel::select('periode_id', 'periode') -> get();
        $mahasiswa = MahasiswaModel::select('mahasiswa_id', 'mahasiswa_nama') ->get();        
        return view('mahasiswa_alfa.create_ajax') -> with(['periode' => $periode, 'mahasiswa' => $mahasiswa]);
    }
    public function store_ajax(Request $request) {

        if ($request -> ajax() || $request -> wantsJson()) {
            $rules = [
                'periode_id'    => 'required|integer',
                'mahasiswa_id'     => 'required|integer',
                'jumlah_alfa'     => 'required|integer'
                
            ];

            $validator = Validator::make($request -> all(),$rules);

            if ($validator -> fails()) {
                return response() -> json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            MahasiswaAlfaModel:: create($request->all());
            return response() -> json([
                'status' => true,
                'message' => 'Data berhasil disimpan!'
            ]);
        }

    }
    public function edit_ajax(string $id) {
        $mahasiswa_alfa = MahasiswaAlfaModel::find($id);
        $periode = PeriodeModel::select('periode_id', 'periode_nama') -> get();
        $mahasiswa = MahasiswaModel::select('mahasiswa_id', 'mahasiswa_nama') -> get();

        return view('mahasiswa_alfa.edit_ajax', [
            'mahasiswa_alfa' => $mahasiswa_alfa, 
            'mahasiswa' => $mahasiswa,
            'periode'   => $periode
        ]);
    }
    public function update_ajax(Request $request, $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'periode_id'    => 'required|integer',
            'mahasiswa_id'     => 'required|integer',
            'jumlah_alfa'     => 'required|integer'
            
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $check = MahasiswaAlfaModel::find($id);
        
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
    }
    
    public function export_excel() {
        // Get all unique periods (columns)
        $periodes = PeriodeModel::orderBy('periode_id')->get();
        $mahasiswa_alfa = MahasiswaAlfaModel::with(['mahasiswa'])
            ->select('mahasiswa_id', 'periode_id', 'jumlah_alfa')
            ->get();
    
        // Create the spreadsheet and set the active sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // === HEADER ROW (First Row) ===
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Mahasiswa');
    
        // Dynamic headers for each period
        $colIndex = 'C'; // Column C starts after A (No) and B (Nama Mahasiswa)
        foreach ($periodes as $periode) {
            $sheet->setCellValue($colIndex . '1', 'Periode ' . $periode->periode);
            $colIndex++;
        }
        $sheet->setCellValue($colIndex . '1', 'Total'); // Last column is 'Total'
    
        // === DATA ROWS (Starts from 2nd row) ===
        $mahasiswaList = MahasiswaModel::orderBy('mahasiswa_nama')->get();
        $rowNum = 2;
        $no = 1;
    
        foreach ($mahasiswaList as $mahasiswa) {
            $sheet->setCellValue('A' . $rowNum, $no); // Row number
            $sheet->setCellValue('B' . $rowNum, $mahasiswa->mahasiswa_nama); // Mahasiswa name
    
            $totalJumlahAlfa = 0; 
            $colIndex = 'C'; // Start from column C again
            foreach ($periodes as $periode) {
                // Get jumlah_alfa for this mahasiswa and periode
                $jumlah_alfa = $mahasiswa_alfa->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                                              ->where('periode_id', $periode->periode_id)
                                              ->pluck('jumlah_alfa')
                                              ->first();
                $jumlah_alfa = $jumlah_alfa ?? 0; // Default to 0 if no record
                $sheet->setCellValue($colIndex . $rowNum, $jumlah_alfa); 
                $totalJumlahAlfa += $jumlah_alfa; // Sum total jumlah_alfa for each row
                $colIndex++;
            }
            $sheet->setCellValue($colIndex . $rowNum, $totalJumlahAlfa); // Total jumlah_alfa for this mahasiswa
            $rowNum++;
            $no++;
        }
    
        // === AUTO-SIZE COLUMNS ===
        foreach (range('A', $colIndex) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        // === SET SHEET TITLE ===
        $sheet->setTitle('Data Alfa Periodik');
    
        // === EXPORT FILE AS XLSX ===
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Alfa_Periodik_' . date('Y-m-d_His') . '.xlsx';
    
        // === HEADERS FOR DOWNLOAD ===
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 22 Aug 2025 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    
        // Save to output
        $writer->save('php://output');
        exit;
    }
    
    
}
