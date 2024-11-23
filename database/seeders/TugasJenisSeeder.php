<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasJenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_tugas_jenis')->insert([
            ['jenis_nama' => 'Teknis'],
            ['jenis_nama' => 'Penelitian'],
            ['jenis_nama' => 'Pengabdian'],
        ]);
    }
}
