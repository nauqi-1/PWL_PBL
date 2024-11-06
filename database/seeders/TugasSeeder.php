<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'tugas_id'              => 1,
                'tugas_nama'            => 'Membersihkan Ruang LSI-1',
                'tugas_desc'            => 'Dibutuhkan 2 - 3 mahasiswa untuk membersihkan ruangan LSI-1.',
                'tugas_bobot'           => 5,
                'tugas_status'          => 'O',
                'tugas_tgl_dibuat'      => '2024-11-05 08:00:00', // contoh date and time
                'tugas_tgl_deadline'    => '2024-11-30 17:00:00', // contoh deadline
                'tugas_pembuat_id'      => 1
            ],
            [
                'tugas_id'              => 2,
                'tugas_nama'            => 'Desain Poster Webinar',
                'tugas_desc'            => 'Membuat poster untuk webinar GameDevJam tanggal 20 November.',
                'tugas_bobot'           => 7,
                'tugas_status'          => 'O',
                'tugas_tgl_dibuat'      => '2024-11-05 08:00:00', // contoh date and time
                'tugas_tgl_deadline'    => '2024-11-15 17:00:00', // contoh deadline
                'tugas_pembuat_id'      => 2
            ],
            [
                'tugas_id'              => 3,
                'tugas_nama'            => 'Pemateri Kelas Singkat Front End Website',
                'tugas_desc'            => 'Membuat bahan ajar / memberikan materi untuk kursus front end web tingkat SMK/SMA',
                'tugas_bobot'           => 15,
                'tugas_status'          => 'O',
                'tugas_tgl_dibuat'      => '2024-11-05 08:00:00', // contoh date and time
                'tugas_tgl_deadline'    => '2024-12-05 17:00:00', // contoh deadline
                'tugas_pembuat_id'      => 5
            ]
            ];

            DB::table('t_tugas')->insert($data);
    }
}
