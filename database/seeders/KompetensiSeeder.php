<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KompetensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kompetensi_id'     => 1,
                'kompetensi_nama'   => 'Editor Foto'
            ],
            [
                'kompetensi_id'     => 2,
                'kompetensi_nama'   => 'Editor Video'
            ],
            [
                'kompetensi_id'     => 3,
                'kompetensi_nama'   => 'Desain Grafis'
            ],
            [
                'kompetensi_id'     => 4,
                'kompetensi_nama'   => 'Backend Web'
            ],
            [
                'kompetensi_id'     => 5,
                'kompetensi_nama'   => 'Frontend Web'
            ],
            [
                'kompetensi_id'     => 6,
                'kompetensi_nama'   => 'Desain Aplikasi'
            ],
            [
                'kompetensi_id'     => 7,
                'kompetensi_nama'   => 'Pekerjaan Manual'
            ]
            ];

            DB::table('m_kompetensi')->insert($data);
    }
}
