<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaAlfaModel;
use App\Models\MahasiswaModel;
use App\Models\PeriodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

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

        return DataTables::of($alfas)
            ->addIndexColumn()
            ->addColumn('aksi', function ($alfa) {
                $btn = '<button onclick="modalAction(\'' . url('/mahasiswa_alfa/' . $alfa->mahasiswa_alfa_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa_alfa/' . $alfa->mahasiswa_alfa_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
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
    public function import() {
        return view('mahasiswa_alfa.import');
    }
 
    public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_mahasiswa_alfa' => ['required', 'mimes:xlsx', 'max:1024'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $file = $request->file('file_mahasiswa_alfa');

        try {
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            if (count($data) <= 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport',
                ]);
            }

            $mahasiswaList = MahasiswaModel::whereIn('mahasiswa_nim', array_column($data, 'A'))
                ->pluck('mahasiswa_id', 'mahasiswa_nim');
            $periodeList = PeriodeModel::whereIn('periode', array_column($data, 'B'))
                ->pluck('periode_id', 'periode');

            $insert = [];
            $errors = [];

            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    $mahasiswa_id = $mahasiswaList[$value['A']] ?? null;
                    $periode_id = $periodeList[$value['B']] ?? null;

                    if ($mahasiswa_id && $periode_id) {
                        $insert[] = [
                            'mahasiswa_id' => $mahasiswa_id,
                            'periode_id'   => $periode_id,
                            'jumlah_alfa'  => $value['C'],
                            'created_at'   => now(),
                        ];
                    } else {
                        $errors[] = "Baris $baris: Data tidak ditemukan.";
                    }
                }
            }

            if (!empty($insert)) {
                MahasiswaAlfaModel::insert($insert);

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport',
                    'errors' => $errors,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang valid untuk diimport',
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage(),
            ]);
        }
    }

    return redirect('/');
}

}
