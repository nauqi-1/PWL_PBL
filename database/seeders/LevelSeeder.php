<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['level_id' => 1, 'level_kode' => 'ADM', 'level_nama' => 'Administrator'],
            ['level_id' => 2, 'level_kode' => 'DSN', 'level_nama' => 'Dosen'],
            ['level_id' => 3, 'level_kode' => 'TDK', 'level_nama' => 'Tendik'],
            ['level_id' => 4, 'level_kode' => 'MHS', 'level_nama' => 'Mahasiswa'],
        ];
        DB::table('m_level') -> insert($data);
    }
}
