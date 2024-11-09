<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'dosen_id'      => 1,
                'dosen_nama'    => 'Dosen Dummy',
                'dosen_prodi'   => 'TI',
                'dosen_noHp'    => '08123456789',
                'dosen_nip'     => '1234567890',
                'user_id'       => 2
            ],
            [
                'dosen_id'      => 2,
                'dosen_nama'    => 'Usman Nurhasan, S.Kom., MT.',
                'dosen_prodi'   => 'SIB',
                'dosen_noHp'    => '08123456789',
                'dosen_nip'     => '198609232015041001',
                'user_id'       => 5
            ],
        ];

        DB::table('m_dosen')->insert($data);
    }
}
