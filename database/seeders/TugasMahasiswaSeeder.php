<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['mahasiswa_id' => 1, 'tugas_id' => 1, 'status' => 'P'],
            ['mahasiswa_id' => 2, 'tugas_id' => 7, 'status' => 'P'],
            ['mahasiswa_id' => 1, 'tugas_id' => 8, 'status' => 'P'],
            ['mahasiswa_id' => 2, 'tugas_id' => 10, 'status' => 'P'],
        ];

        DB::table('t_tugas_mahasiswa')->insert($data);
    }
}
