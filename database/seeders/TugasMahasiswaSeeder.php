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
            ['mahasiswa_id' => 1, 'tugas_id' => 1],
            ['mahasiswa_id' => 2, 'tugas_id' => 1],
            ['mahasiswa_id' => 1, 'tugas_id' => 2],
            ['mahasiswa_id' => 2, 'tugas_id' => 3],
            ['mahasiswa_id' => 1, 'tugas_id' => 3],
        ];

        DB::table('t_tugas_mahasiswa')->insert($data);
    }
}
