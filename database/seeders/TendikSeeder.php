<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TendikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'tendik_id'     => 1,
                'tendik_nama'   => 'Tendik Dummy',
                'tendik_noHp'   => '08123456789',
                'tendik_nip'    => '1234567890',
                'user_id'       => 3
            ]
            ];
        DB::table('m_tendik')->insert($data);
    }
}
