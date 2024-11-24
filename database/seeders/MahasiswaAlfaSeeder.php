<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaAlfaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'periode_id'    => 9,
                'mahasiswa_id'  => 1,
                'jumlah_alfa'   => 4
            ],
            [
                'periode_id'    => 9,
                'mahasiswa_id'  => 2,
                'jumlah_alfa'   => 10
            ],
            [
                'periode_id'    => 4,
                'mahasiswa_id'  => 1,
                'jumlah_alfa'   => 6
            ],
            [
                'periode_id'    => 7,
                'mahasiswa_id'  => 17,
                'jumlah_alfa'   => 3
            ],

        ];

        DB::table('t_mahasiswa_alfa')->insert($data);
    }
}
