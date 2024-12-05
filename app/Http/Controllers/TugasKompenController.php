<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\TugasModel;
use App\Models\UserModel;
use App\Models\KompetensiModel;
use App\Models\LevelModel;
use App\Models\TugasJenisModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


class TugasKompenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Tugas Kompen',
            'list' => ['Home', 'Tugas']
        ];
        if(Auth::user()->level->level_kode == 'ADM' || Auth::user()->level->level_kode == 'MHS') {
            $page = (object) [
                'title' => 'Daftar tugas kompen yang ada dalam sistem.'
            ];
        } else if(Auth::user()->level->level_kode == 'DSN' || Auth::user()->level->level_kode == 'TDK') {
            $page = (object) [
                'title' => 'Daftar tugas kompen yang telah dibuat.'
            ];
        }
        $activeMenu = 'tugaskompen'; //set menu yang sedang aktif
        $level = LevelModel::all();

        return view('tugaskompen.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $tugass = TugasModel::select(
            'tugas_id',
            'tugas_nama',
            'tugas_desc',
            'tugas_bobot',
            'tugas_file',
            'tugas_status',
            'tugas_tgl_dibuat',
            'tugas_tgl_deadline',
            'tugas_pembuat_id',
            'tugas_progress',
            'jenis_id',
            'kuota'
        )->with('user', 'jenis');
        // Filter tugas berdasarkan level
        if ($request->level_id) {
            $tugass->whereHas('user', function ($query) use ($request) {
                $query->where('level_id', $request->level_id);
            });
        }

        if (Auth::user()->level->level_kode == 'DSN' || Auth::user()->level->level_kode == 'TDK') {
            $tugass->where('tugas_pembuat_id', Auth::user()->user_id);
        }

        return DataTables::of($tugass)
            ->addIndexColumn()
            ->addColumn('pembuat', function ($tugas) {
                if ($tugas->user && in_array($tugas->user->level_id, [1, 2, 3])) {
                    return $tugas->user->nama_pembuat;
                }
                return null; // Return null 
            })

            ->addColumn('jenis', function ($tugas) {
                return $tugas->jenis ? $tugas->jenis->jenis_nama : '-'; // Menampilkan nama jenis tugas
            })
            ->addColumn('aksi', function ($tugas) {
                $btn = '<button onclick="modalAction(\'' . url('/tugaskompen/' . $tugas->tugas_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tugaskompen/' . $tugas->tugas_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tugaskompen/' . $tugas->tugas_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_ajax(Request $request, string $id)
    {
        // Retrieve the tugas data with its related user and kompetensi
        $tugas = TugasModel::with(['user', 'kompetensi', 'jenis'])->find($id);

        if (!$tugas) {
            return response()->json(['status' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        return view('tugaskompen.show_ajax', compact('tugas'));
    }
    public function create_ajax()
    {
        // Fetch kompetensi data
        $kompetensi = KompetensiModel::select('kompetensi_id', 'kompetensi_nama')->get();
        $jenisTugas = TugasJenisModel::all();

        // Fetch pembuat (users) data
        $pembuat = UserModel::with(['dosen', 'admin', 'tendik'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->user_id,
                    'nama' => $user->nama_pembuat,
                ];
            });

        // Return the view with the data
        return view('tugaskompen.create_ajax', [
            'kompetensi' => $kompetensi,
            'jenisTugas' => $jenisTugas,
            'pembuat' => $pembuat,
        ]);
    }
    public function store_ajax(Request $request)
    {
        // Check if the request is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'tugas_nama' => 'required|string|max:255',
                'tugas_desc' => 'required|string',
                'tugas_bobot' => 'required|integer',
                'tugas_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,avi,mkv,txt,zip|max:51200', // Maksimum 50MB
                'tugas_tgl_deadline' => 'required|date',
                'jenis_id' => 'required|string',
                'kuota' => 'required|integer',
                'tugas_pembuat_id' => 'required|exists:m_user,user_id',
                'kompetensi' => 'required', // Kompetensi harus berupa array
                'kompetensi.*' => 'exists:m_kompetensi,kompetensi_id', // Setiap ID harus valid
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            try {
                DB::beginTransaction();

                // Buat tugas tanpa file
                $tugas = TugasModel::create($request->except('tugas_file'));

                // Simpan file jika ada
                if ($request->hasFile('tugas_file')) {
                    $file = $request->file('tugas_file');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('uploads/tugas', $filename, 'public');
                    $tugas->update(['tugas_file' => $filePath]); // Update kolom tugas_file
                }

                // Simpan kompetensi ke tabel pivot
                $tugas->kompetensi()->attach($request->kompetensi);

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Data Tugas berhasil disimpan',
                    'file_path' => $tugas->tugas_file,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                ]);
            }
        }

        return redirect('/');
    }
    public function edit_ajax($id)
    {
        // Ambil data tugas berdasarkan ID
        $tugas = TugasModel::with('kompetensi', 'user')->find($id);

        if (!$tugas) {
            return response()->json([
                'status' => false,
                'message' => 'Tugas tidak ditemukan',
            ]);
        }

        // Ambil data kompetensi dan jenis tugas
        $kompetensi = KompetensiModel::select('kompetensi_id', 'kompetensi_nama')->get();
        $jenisTugas = TugasJenisModel::all();
        $users = UserModel::with('dosen', 'admin', 'tendik', 'mahasiswa')->get();

        // Return view dengan data
        return view('tugaskompen.edit_ajax', [
            'tugas' => $tugas,
            'kompetensi' => $kompetensi,
            'jenisTugas' => $jenisTugas,
            'users' => $users,
        ]);
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'tugas_nama' => 'required|string|max:255',
                'tugas_desc' => 'required|string',
                'tugas_bobot' => 'required|integer',
                'tugas_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,avi,mkv,txt,zip|max:51200', // Maksimum 50MB
                'tugas_tgl_deadline' => 'required|date',
                'jenis_id' => 'required|string',
                'kuota' => 'required|integer',
                'tugas_pembuat_id' => 'required|exists:m_user,user_id',
                'kompetensi' => 'required', // Kompetensi harus berupa array
                'kompetensi.*' => 'exists:m_kompetensi,kompetensi_id', // Setiap ID harus valid
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            try {
                DB::beginTransaction();

                // Ambil tugas yang ingin diperbarui
                $tugas = TugasModel::find($id);

                if (!$tugas) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tugas tidak ditemukan',
                    ]);
                }

                // Update data tugas
                $tugas->update($request->except('tugas_file', 'kompetensi'));

                // Update file jika ada file baru
                if ($request->hasFile('tugas_file')) {
                    // Hapus file lama jika ada
                    if ($tugas->tugas_file && Storage::exists('public/' . $tugas->tugas_file)) {
                        Storage::delete('public/' . $tugas->tugas_file);
                    }

                    // Simpan file baru
                    $file = $request->file('tugas_file');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('uploads/tugas', $filename, 'public');
                    $tugas->update(['tugas_file' => $filePath]);
                }

                // Update kompetensi di tabel pivot
                $tugas->kompetensi()->sync($request->kompetensi);

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Data Tugas berhasil diperbarui',
                    'file_path' => $tugas->tugas_file,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage(),
                ]);
            }
        }

        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        // Find the task (tugaskompen) by ID
        $tugas = TugasModel::find($id);

        // If task is found, return the confirmation view with the task data
        if ($tugas) {
            return view('tugaskompen.confirm_ajax', ['tugas' => $tugas]);
        }

        // If task is not found, return a response indicating failure
        return response()->json([
            'status' => false,
            'message' => 'Tugas tidak ditemukan'
        ]);
    }
    // Delete an item via AJAX
    public function delete_ajax(Request $request, $id)
    {
        // Check if the request is an AJAX request or wants JSON response
        if ($request->ajax() || $request->wantsJson()) {
            // Find the task (tugaskompen) by ID
            $tugas = TugasModel::find($id);

            if ($tugas) {
                // Delete the task
                $tugas->delete();

                // Return a success message
                return response()->json([
                    'status' => true,
                    'message' => 'Tugas berhasil dihapus'
                ]);
            } else {
                // If the task is not found, return an error message
                return response()->json([
                    'status' => false,
                    'message' => 'Tugas tidak ditemukan'
                ]);
            }
        }

        // If the request is not AJAX, redirect to the homepage
        return redirect('/');
    }
    public function import()
    {
        return view('tugaskompen.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_tugas' => ['required', 'mimes:xlsx', 'max:1024'], // File Excel, max 1MB
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $file = $request->file('file_tugas');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            if (count($data) > 1) { // Jika ada data selain header
                DB::beginTransaction();

                try {
                    $insertedTugas = [];

                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // Lewati header
                            $excelDate = $value['G']; // Raw date value from Excel

                            // Check if the date is a numeric Excel serial date
                            if (is_numeric($excelDate)) {
                                // Convert Excel serial date to a Unix timestamp and format as Y-m-d H:i:s
                                $unixDate = ($excelDate - 25569) * 86400; // Excel serial date to Unix timestamp conversion
                                $formattedDate = Carbon::createFromTimestamp($unixDate)->format('Y-m-d H:i:s');
                            } else {
                                // Attempt to parse the date if it's a normal string date format
                                try {
                                    $formattedDate = Carbon::parse($excelDate)->format('Y-m-d H:i:s');
                                } catch (\Exception $e) {
                                    // If the date format is invalid, you can handle it here (e.g., set to null or skip)
                                    $formattedDate = null;
                                }
                            }
                            // Ambil data kompetensi sebagai array dari kolom tertentu (misal kolom H)
                            $kompetensiArray = explode(',', $value['H']); // Contoh format: "1,2,3"

                            // Buat tugas baru
                            $tugas = TugasModel::create([
                                'tugas_nama' => $value['A'],
                                'tugas_desc' => $value['B'],
                                'tugas_pembuat_id' => $value['C'],
                                'tugas_bobot' => $value['D'],
                                'kuota' => $value['E'],
                                'jenis_id' => $value['F'],
                                'tugas_tgl_deadline' => $formattedDate,
                                'created_at' => now(),
                            ]);

                            // Simpan kompetensi ke tabel pivot
                            foreach ($kompetensiArray as $kompetensi_id) {
                                DB::table('t_tugas_kompetensi')->insert([
                                    'tugas_id' => $tugas->tugas_id,
                                    'kompetensi_id' => $kompetensi_id,
                                    'created_at' => now(),
                                ]);
                            }

                            $insertedTugas[] = $tugas;
                        }
                    }

                    DB::commit();

                    return response()->json([
                        'status' => true,
                        'message' => count($insertedTugas) . ' Data Tugas berhasil diimport',
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport',
            ]);
        }

        return redirect('/');
    }
    public function export_pdf()
    {
        $tugas = TugasModel::select(
            'tugas_id',
            'tugas_nama',
            'tugas_desc',
            'tugas_bobot',
            'tugas_tgl_dibuat',
            'tugas_tgl_deadline',
            'tugas_pembuat_id',
            'jenis_id',
            'kuota'
        )
            ->orderBy('tugas_id')
            ->with('jenis')
            ->get();

        $pdf = Pdf::loadView('tugaskompen.export_pdf', ['tugas' => $tugas]);
        $pdf->setPaper('a4', 'portrait'); //set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); //set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data tugas ' . date('Y-m-d H:i:s') . 'pdf');
    }
    public function export_excel()
    {
        // Ambil data Tugas dengan relasi kompetensi
        $tugaskompen = TugasModel::with('kompetensi')->orderBy('tugas_tgl_deadline')->get();

        // Load Excel library
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers untuk Excel sheet
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Tugas');
        $sheet->setCellValue('C1', 'Deskripsi');
        $sheet->setCellValue('D1', 'Pembuat');
        $sheet->setCellValue('E1', 'Bobot');
        $sheet->setCellValue('F1', 'Kuota');
        $sheet->setCellValue('G1', 'Jenis');
        $sheet->setCellValue('H1', 'Deadline');
        $sheet->setCellValue('I1', 'Kompetensi');

        // Bold header row
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        // Isi data ke dalam sheet
        $no = 1;
        $row = 2;
        foreach ($tugaskompen as $tugas) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $tugas->tugas_nama);
            $sheet->setCellValue('C' . $row, $tugas->tugas_desc);
            $sheet->setCellValue('D' . $row, $tugas->user->nama_pembuat); // Relasi pembuat (asumsi relasi 'pembuat' ada)
            $sheet->setCellValue('E' . $row, $tugas->tugas_bobot);
            $sheet->setCellValue('F' . $row, $tugas->kuota);
            $sheet->setCellValue('G' . $row, $tugas->jenis->jenis_nama); // Asumsi relasi jenis ada
            $sheet->setCellValue('H' . $row, $tugas->tugas_tgl_deadline);

            // Gabungkan kompetensi menjadi string dengan koma
            $kompetensi = $tugas->kompetensi->pluck('kompetensi_nama')->join(', ');
            $sheet->setCellValue('I' . $row, $kompetensi);

            $row++;
            $no++;
        }

        // Auto size the columns
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Tugas Kompensasi');

        // Create Excel file and prompt download
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Tugas_Kompensasi_' . date('Y-m-d_H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }
}
