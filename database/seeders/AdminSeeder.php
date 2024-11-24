<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data = [
            [
                'admin_id' => 1,
                'admin_nama' => 'Admin Dummy',
                'admin_prodi' => 'SIB',
                'admin_noHp' => '08123456873',
                'user_id' => 1
            ]
            ];

            DB::table('m_admin')->insert($data);
    }
}
