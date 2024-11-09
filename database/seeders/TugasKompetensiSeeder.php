<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasKompetensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [ 'tugas_id' => 1, 'kompetensi_id' => 7],
            [ 'tugas_id' => 2, 'kompetensi_id' => 3],
            [ 'tugas_id' => 3, 'kompetensi_id' => 5],
            [ 'tugas_id' => 3, 'kompetensi_id' => 6],
        ];

        DB::table('t_tugas_kompetensi')->insert($data);
    }
}
