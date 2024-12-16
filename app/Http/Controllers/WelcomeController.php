<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Sistem Kompensasi JTI Polinema',
            'list' => ['Home', 'Dashboard']
        ];
        $user = Auth::user();
        $activemenu = 'dashboard';

        //menghitung tugas
        $totalTugas = DB::table('t_tugas')->count();

        //menghitung tugas per user
        $totalTugasUser = DB::table('t_tugas')
        ->where('tugas_pembuat_id', $user->user_id)
        ->count();

        //menghitung tugas per level
        $totalTugasLevel = DB::table('m_level')
        ->leftJoin('m_user', 'm_level.level_id', '=', 'm_user.level_id')
        ->leftJoin('t_tugas', 'm_user.user_id', '=', 't_tugas.tugas_pembuat_id')
        ->select('m_level.level_nama', DB::raw('COALESCE(COUNT(t_tugas.tugas_id), 0) as total'))
        ->groupBy('m_level.level_nama')
        ->get();


        //menghitung tugas baru dibuat per bulan
        $totalTugasBulan = DB::table('t_tugas')
        ->select(DB::raw('MONTH(tugas_tgl_dibuat) as month'), DB::raw('COUNT(*) as count'))
        ->where('tugas_pembuat_id', $user->user_id)
        ->groupBy(DB::raw('MONTH(tugas_tgl_dibuat)'))
        ->orderBy(DB::raw('MONTH(tugas_tgl_dibuat)'))
        ->pluck('count', 'month');

        //menghitung tugas per jenis
        $totalTugasJenis = DB::table('t_tugas_jenis')
        ->where('tugas_pembuat_id', $user->user_id)
        ->leftJoin('t_tugas', 't_tugas_jenis.jenis_id', '=', 't_tugas.jenis_id')
        ->select('t_tugas_jenis.jenis_nama', DB::raw('COUNT(t_tugas.tugas_id) as total'))
        ->groupBy('t_tugas_jenis.jenis_nama')
        ->pluck('total', 't_tugas_jenis.jenis_nama')
        ->toArray();

        //menghitung tugas per status
        $totalTugasStatus = DB::table('t_tugas')
        ->where('tugas_pembuat_id', $user->user_id)
        ->select(DB::raw("tugas_status, COUNT(*) as count"))
        ->groupBy('tugas_status')
        ->pluck('count', 'tugas_status');

        //menghitung request tugas
        $totalRequest = DB::table('t_request')
        ->where('tugas_pembuat_id', $user->user_id)
        ->where('status_request', 'pending')
        ->count();

        //menghitung mhs alfa
        $totalMhsAlfa = DB::table('t_mahasiswa_alfa')->count();

        //mengambil data periode
        $periods = DB::table('m_periode')->pluck('periode', 'periode_id');

        //menghitung jumlah mahasiswa alfa dan kompen per periodik
        $totalAlfaKompen = [];
        foreach ($periods as $periode_id => $periode) {
            $totalAlfa = DB::table('t_mahasiswa_alfa')
                ->where('periode_id', $periode_id)
                ->count();

            $totalKompen = DB::table('t_mahasiswa_alfa')
                ->join('m_mahasiswa', 't_mahasiswa_alfa.mahasiswa_id', '=', 'm_mahasiswa.mahasiswa_id')
                ->where('t_mahasiswa_alfa.periode_id', $periode_id)
                ->whereColumn('t_mahasiswa_alfa.jumlah_alfa', '=', 'm_mahasiswa.mahasiswa_alfa_lunas')
                ->count();

            $totalAlfaKompen[] = [
                'periode' => $periode,
                'alfa' => $totalAlfa,
                'kompen' => $totalKompen,
            ];
        }

        //menghitung total alfa per user mahasiswa
        $totalAlfa = DB::table('t_mahasiswa_alfa')
        ->join('m_mahasiswa', 't_mahasiswa_alfa.mahasiswa_id', '=', 'm_mahasiswa.mahasiswa_id')
        //->join('m_user', 'm_mahasiswa.user_id', '=', 'm_user.user_id')
        //->where('m_user.user_id', $user->user_id)
        ->where('m_mahasiswa.user_id', '=', $user->user_id)
        ->sum('t_mahasiswa_alfa.jumlah_alfa');

        //menghitung total alfa lunas per user mahasiswa
        $totalAlfaLunas = DB::table('t_mahasiswa_alfa')
        ->join('m_mahasiswa', 't_mahasiswa_alfa.mahasiswa_id', '=', 'm_mahasiswa.mahasiswa_id')
        ->where('m_mahasiswa.user_id', $user->user_id)
        //->whereColumn('m_mahasiswa.mahasiswa_alfa_lunas', '>=', 't_mahasiswa_alfa.jumlah_alfa')
        //->sum('t_mahasiswa_alfa.jumlah_alfa');
        ->select(DB::raw('LEAST(SUM(t_mahasiswa_alfa.jumlah_alfa), m_mahasiswa.mahasiswa_alfa_lunas) AS total_alfa_lunas'))
        ->groupBy('m_mahasiswa.mahasiswa_id', 'm_mahasiswa.mahasiswa_alfa_lunas')
        ->value('total_alfa_lunas');
        
        //menghitung total alfa belum lunas per user mahasiswa
        $totalAlfaHutang = DB::table('t_mahasiswa_alfa')
        //->where('m_mahasiswa.user_id', $user->user_id)
        ->join('m_mahasiswa', 't_mahasiswa_alfa.mahasiswa_id', '=', 'm_mahasiswa.mahasiswa_id')
        ->where('m_mahasiswa.user_id', $user->user_id)
        //->whereColumn('m_mahasiswa.mahasiswa_alfa_lunas', '<', 't_mahasiswa_alfa.jumlah_alfa')
        //->whereColumn('t_mahasiswa_alfa.jumlah_alfa', '>', 'm_mahasiswa.mahasiswa_alfa_lunas')
        //->sum(DB::raw('t_mahasiswa_alfa.jumlah_alfa - m_mahasiswa.mahasiswa_alfa_lunas'));
        //->sum(DB::raw('(t_mahasiswa_alfa.jumlah_alfa - COALESCE(m_mahasiswa.mahasiswa_alfa_lunas, 0))'))
        //->select(DB::raw('SUM(GREATEST(SUM(t_mahasiswa_alfa.jumlah_alfa) - m_mahasiswa.mahasiswa_alfa_lunas, 0)) as total_hutang'))
        //->select(DB::raw('SUM(GREATEST(t_mahasiswa_alfa.jumlah_alfa - COALESCE(m_mahasiswa.mahasiswa_alfa_lunas, 0), 0)) as total_hutang'))
        //->selectRaw('SUM(GREATEST(t_mahasiswa_alfa.jumlah_alfa - COALESCE(m_mahasiswa.mahasiswa_alfa_lunas, 0), 0)) as total_hutang')
        ->select(DB::raw('GREATEST(SUM(t_mahasiswa_alfa.jumlah_alfa) - COALESCE(m_mahasiswa.mahasiswa_alfa_lunas, 0), 0) as total_hutang'))
        ->groupBy('t_mahasiswa_alfa.mahasiswa_id') // Kelompokkan data berdasarkan mahasiswa_id
        ->value('total_hutang');

        //menentukan status UAS berdasarkan lunas/tidak lunasnya alfa
        //$statusUAS = ($totalAlfaHutang > 0) ? 'Belum bisa<br>mengikuti!' : 'Sudah bisa<br>mengikuti!';
        $statusUAS = ($totalAlfaHutang > 0) ? '<i class="fas fa-times"></i>' : '<i class="fas fa-check"></i>';

        //menentukan warna small box berdasarkan status UAS
        $warnaUAS = ($totalAlfaHutang > 0) ? 'bg-danger' : 'bg-success';

        //menghitung total alfa per periode
        $totalAlfaPeriode = DB::table('t_mahasiswa_alfa')
        ->join('m_mahasiswa', 't_mahasiswa_alfa.mahasiswa_id', '=', 'm_mahasiswa.mahasiswa_id')
        ->join('m_periode', 't_mahasiswa_alfa.periode_id', '=', 'm_periode.periode_id')
        ->select(
            'm_periode.periode',
            DB::raw('SUM(t_mahasiswa_alfa.jumlah_alfa) as total_alfa'),
            DB::raw('SUM(CASE WHEN m_mahasiswa.mahasiswa_alfa_lunas >= t_mahasiswa_alfa.jumlah_alfa THEN t_mahasiswa_alfa.jumlah_alfa ELSE 0 END) as total_lunas')
        )
        ->where('m_mahasiswa.user_id', $user->user_id)
        ->groupBy('m_periode.periode')
        ->get();

        switch($user->level_id) {
            case 1:
                return view('welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activemenu, 'totalTugas' => $totalTugas, 'totalTugasUser' => $totalTugasUser, 'totalTugasStatus' =>$totalTugasStatus, 'totalTugasBulan' => $totalTugasBulan, 'totalTugasJenis' => $totalTugasJenis, 'totalRequest' => $totalRequest, 'totalMhsAlfa' => $totalMhsAlfa, 'totalAlfaKompen' => $totalAlfaKompen]);
            case 2:
                return view('dosen.welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activemenu, 'totalTugasLevel' => $totalTugasLevel, 'totalTugasUser' => $totalTugasUser, 'totalTugasStatus' =>$totalTugasStatus, 'totalTugasBulan' => $totalTugasBulan, 'totalTugasJenis' => $totalTugasJenis, 'totalRequest' => $totalRequest]);
            case 3:
                return view('tendik.welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activemenu, 'totalTugasLevel' => $totalTugasLevel, 'totalTugasUser' => $totalTugasUser, 'totalTugasStatus' =>$totalTugasStatus, 'totalTugasBulan' => $totalTugasBulan, 'totalTugasJenis' => $totalTugasJenis, 'totalRequest' => $totalRequest]);
            case 4:
                return view('mahasiswa.welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activemenu, 'totalAlfa' => $totalAlfa, 'totalAlfaLunas' => $totalAlfaLunas, 'totalAlfaHutang' => $totalAlfaHutang, 'statusUAS' => $statusUAS, 'warnaUAS' => $warnaUAS, 'totalAlfaPeriode' => $totalAlfaPeriode, 'totalTugasStatus' =>$totalTugasStatus, 'totalTugasJenis' => $totalTugasJenis]);
            

        }    
    }
}
