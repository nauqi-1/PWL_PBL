<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'mahasiswa_id' => 1,
                'mahasiswa_nama' => 'Mahasiswa Dummy',
                'mahasiswa_kelas' => '3B',
                'mahasiswa_nim' => '1234567890',
                'mahasiswa_prodi' => 'TI',
                'mahasiswa_noHp' => '01234567890',
                'mahasiswa_alfa_sisa' => 0,
                'mahasiswa_alfa_total' => 20,
                'user_id' => 4
            ],
            [
                'mahasiswa_id' => 2,
                'mahasiswa_nama' => 'Muhammad Naufal Assyauqi',
                'mahasiswa_kelas' => '3B',
                'mahasiswa_nim' => '2241760046',
                'mahasiswa_prodi' => 'SIB',
                'mahasiswa_noHp' => '081959048314',
                'mahasiswa_alfa_sisa' => 0,
                'mahasiswa_alfa_total' => 10,
                'user_id' => 6
            ]
            ];

            DB::table('m_mahasiswa')->insert($data);
    }
}
