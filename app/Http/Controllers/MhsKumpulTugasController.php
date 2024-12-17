<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\TugasModel;
use App\Models\UserModel;
use App\Models\KompetensiModel;
use App\Models\LevelModel;
use App\Models\RequestModel;
use App\Models\MahasiswaModel;
use App\Models\TugasJenisModel;
use App\Models\NotificationsModel;
use App\Models\TugasMahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class MhsKumpulTugasController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Pengumpulan Tugas Kompen',
            'list' => ['Home', 'Pengumpulan Tugas']
        ];

        $page = (object) [
            'title' => 'Daftar Tugas Kompen yang ada dalam sistem'
        ];

        $activeMenu = 'mhs_kumpultugas'; //set menu yang sedang aktif
        $level = LevelModel::all();

        return view('mhs_view.pengumpulan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $mhsId = auth()->user()->mahasiswa->mahasiswa_id; // ID mahasiswa login

        // Ambil data dari TugasMahasiswaModel berdasarkan mahasiswa yang login
        $tugass = TugasMahasiswaModel::where('mahasiswa_id', $mhsId)
            ->whereHas('tugas', function ($query) {
                $query->where('tugas_status', 'W'); // Filter hanya status 'W' di tabel tugas
            })
            ->with(['tugas.user', 'tugas.pengumpulan']) // Menampilkan relasi tugas dan atribut lainnya
            ->get();

        return DataTables::of($tugass)
            ->addIndexColumn()
            ->addColumn('pembuat', function ($tugasMahasiswa) {
                $user = $tugasMahasiswa->tugas->user ?? null;
                if ($user && in_array($user->level_id, [1, 2, 3])) {
                    return $user->nama_pembuat; // Pastikan nama_pembuat ada di user model
                }
                return null; // Return null jika tidak ada pembuat
            })
            ->addColumn('aksi', function ($tugasMahasiswa) {
                $tugasId = $tugasMahasiswa->tugas_mahasiswa_id;
                $btn = '<button onclick="modalAction(\'' . url('/mhs_kumpultugas/' . $tugasId . '/edit_ajax') . '\')" class="btn btn-info btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mhs_kumpultugas/' . $tugasId . '/confirm_submit_ajax') . '\')" class="btn btn-success btn-sm">Upload</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function liststatuspengumpulan(Request $request)
    {
        $mhsId = auth()->user()->mahasiswa->mahasiswa_id; // ID mahasiswa login

        // Ambil data dari TugasMahasiswaModel berdasarkan mahasiswa yang login
        $tugass = TugasMahasiswaModel::where('mahasiswa_id', $mhsId)
            ->whereHas('tugas', function ($query) {
                // Filter hanya status tugas yang bukan 'W' dan 'O'
                $query->whereNotIn('tugas_status', ['W', 'O']);
            })
            ->with(['tugas.user', 'tugas.pengumpulan']) // Menampilkan relasi tugas dan atribut lainnya
            ->get();

        return DataTables::of($tugass)
            ->addIndexColumn()
            ->addColumn('pembuat', function ($tugasMahasiswa) {
                $user = $tugasMahasiswa->tugas->user ?? null;
                if ($user && in_array($user->level_id, [1, 2, 3])) {
                    return $user->nama_pembuat; // Pastikan nama_pembuat ada di user model
                }
                return null; // Return null jika tidak ada pembuat
            })
            ->addColumn('cetak', function ($tugasMahasiswa) {
                // Check if tugas_status is 'D' (Done) and return the link/button with dynamic URL
                if ($tugasMahasiswa->tugas->tugas_status == 'D') {
                    // Generate the URL dynamically using route() or url() helper
                    $url = url("mhs_kumpultugas/{$tugasMahasiswa->tugas_mahasiswa_id}/export_pdf");
                    return '<a href="' . $url . '" class="btn btn-primary" target="_blank">Cetak</a>';
                }
                return ''; // Return empty if status is not 'D'
            })
            ->rawColumns(['cetak']) // Allow raw HTML in the cetak column
            ->make(true);
    }

    public function update_progress(Request $request, $id)
    {
        Log::info('updateProgress called', ['id' => $id, 'request' => $request->all()]);
        // Validate the progress input
        $validated = $request->validate([
            'progress' => 'required|numeric|between:0,100',
            'progress_deskripsi' => 'required|string|max:255',
        ]);

        // Find the task (tugas) by the provided ID
        $tugas = TugasMahasiswaModel::find($id);

        // Check if the task exists
        if (!$tugas) {
            return response()->json([
                'status' => false,
                'message' => 'Tugas tidak ditemukan',
            ], 404);
        }

        // Update the progress value
        // $tugas->progress = $request->input('progress');
        $tugas->progress = $validated['progress'];
        $tugas->progress_deskripsi = $validated['progress_deskripsi'];
        $tugas->save();

        // Return a success response
        return response()->json([
            'status' => true,
            'message' => 'Progres berhasil diperbarui',
        ]);
    }

    public function edit_ajax(Request $request, string $id)
    {
        $tugas = TugasMahasiswaModel::with(['tugas.user', 'tugas.pengumpulan'])->find($id);

        if (!$tugas) {
            return response()->json(['status' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        return view('mhs_view.pengumpulan.edit_ajax', compact('tugas'));
    }
    public function confirm_submit_ajax(Request $request, string $id)
    {
        // DB::enableQueryLog();
        $tugasMahasiswa = TugasMahasiswaModel::with('tugas.jenis', 'tugas.user')->find($id);
        Log::info('Executed Query', DB::getQueryLog());

        // Ambil data kompetensi dan jenis tugas
        $kompetensi = KompetensiModel::select('kompetensi_id', 'kompetensi_nama')->get();
        $jenisTugas = TugasJenisModel::all();
        $users = UserModel::with('dosen', 'admin', 'tendik', 'mahasiswa')->get();
        $pembuat = UserModel::with(['dosen', 'admin', 'tendik'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->user_id,
                    'nama' => $user->nama_pembuat,
                ];
            });
        return view('mhs_view.pengumpulan.confirm_submit_ajax', [
            'tugasMahasiswa' => $tugasMahasiswa,
            'kompetensi' => $kompetensi,
            'jenisTugas' => $jenisTugas,
            'users' => $users,
            'pembuat' => $pembuat

        ]);
    }
    public function submit_ajax(Request $request, $id)
    {
        try {
            // Validasi input file
            $request->validate([
                'file_path' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,avi,mkv,txt,zip|max:20480', // Maks 20MB
            ]);

            // Cari data tugas mahasiswa berdasarkan ID
            $tugasMahasiswa = TugasMahasiswaModel::findOrFail($id);
            Log::info('Tugas ditemukan', ['id' => $id, 'data' => $tugasMahasiswa]);

            // Handle file upload
            if ($request->hasFile('file_path')) {
                Log::info('File ditemukan pada request');

                // Hapus file lama jika ada
                if ($tugasMahasiswa->file_path && Storage::exists($tugasMahasiswa->file_path)) {
                    Log::info('Menghapus file lama', ['file_path' => $tugasMahasiswa->file_path]);
                    Storage::delete($tugasMahasiswa->file_path);
                }

                // Simpan file baru
                $filePath = $request->file('file_path')->store('tugas_mahasiswa_files', 'public');
                Log::info('File berhasil diupload', ['file_path' => $filePath]);

                // Update file_path, status, dan tanggal_disubmit di database
                $tugasMahasiswa->update([
                    'file_path' => $filePath,
                    'tanggal_disubmit' => now(), // Set tanggal_disubmit ke waktu saat ini
                ]);
                // Update status di TugasModel menjadi 'S' jika semua tugas mahasiswa sudah submit
                $tugasModel = $tugasMahasiswa->tugas; // Relasi ke model Tugas
                if ($tugasModel) {
                    $mahasiswaBelumSubmit = $tugasModel->pengumpulan()
                        ->whereNull('tanggal_disubmit')
                        ->count();

                    // Jika semua mahasiswa sudah submit, update status tugas menjadi 'S'
                    $tugasModel->update([
                        'tugas_status' => $mahasiswaBelumSubmit === 0 ? 'S' : 'W', // 'S' jika semua selesai, 'W' jika masih ada yang belum
                    ]);
                }
                // Dapatkan `request_id` dari request yang baru dibuat
                $tugasMahasiswaId = $tugasMahasiswa->tugas_mahasiswa_id;
                // Create notification
                $pembuatNotifId = auth()->user()->user_id;
                NotificationsModel::create([
                    'jenis_notification' => 'kumpul tugas',
                    'pembuat_notification' => $pembuatNotifId,
                    'penerima_notification' => $tugasMahasiswa->user->user_id,
                    'konten_notification' => 'Mahasiswa Mengumpulkan Tugas',
                    'tgl_notification' => now(),
                    'ref_id' => $tugasMahasiswaId,

                ]);
                Log::info('Data berhasil diupdate', [
                    'file_path' => $filePath,
                    'status' => 'S',
                    'tanggal_disubmit' => now(),
                ]);
            } else {
                Log::warning('Tidak ada file yang diupload');
            }

            // Berhasil
            return response()->json([
                'status' => true,
                'message' => 'Tugas berhasil dikumpulkan',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi gagal', ['errors' => $e->errors()]);
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $e->errors(),
            ]);
        } catch (\Exception $e) {
            Log::error('Kesalahan server', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server',
            ], 500);
        }
    }

    
public function export_pdf($id)
{
    // Get the currently authenticated user's mahasiswa_id
    $mahasiswa_id = Auth::user()->mahasiswa_id;

    // Fetch the required data using Eloquent relationships
    $data = TugasMahasiswaModel::with([
        'mahasiswa',
        'tugas',
        'tugas.user' => function ($query) {
            $query->with(['admin', 'dosen', 'tendik']);
        },
    ])
    ->where('tugas_mahasiswa_id', $id)
    ->first();

    // Check if data exists
    if (!$data) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // Extract related data
    $mahasiswa = $data->mahasiswa;
    $tugas = $data->tugas;
    $pembuat = $tugas->user;

    // Determine the level and set pembuat details
    $level_id = $pembuat->level_id ?? null;
    $data->pembuat_nama = match ($level_id) {
        1 => $pembuat->admin->admin_nama ?? 'Tidak Diketahui',
        2 => $pembuat->dosen->dosen_nama ?? 'Tidak Diketahui',
        3 => $pembuat->tendik->tendik_nama ?? 'Tidak Diketahui',
        default => 'Tidak Diketahui',
    };

    $data->pembuat_nip = match ($level_id) {
        1 => $pembuat->admin->admin_nip ?? '-',
        2 => $pembuat->dosen->dosen_nip ?? '-',
        3 => $pembuat->tendik->tendik_nip ?? '-',
        default => '-',
    };

    // Add the current date to the data
    $data->current_date = now()->format('d F Y');
    // Generate the PDF using the 'pdf.document' view
    $pdf = Pdf::loadView('pengumpulan.export_pdf', ['data' => $data]);
    $pdf->setPaper('a4', 'landscape'); // Set paper size and orientation
    $pdf->setOption("isRemoteEnabled", true); // Set true if images are from URLs
    $pdf->render();

    return $pdf->stream('Berita Acara Kompensasi ' . date('Y-m-d H:i:s') . '.pdf');
}

}
