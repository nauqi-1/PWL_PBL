<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['periode_id'    => 1,
            'periode'       => '2020/2021 Ganjil',],
            ['periode_id'    => 2,
            'periode'       => '2020/2021 Genap',],
            ['periode_id'    => 3,
            'periode'       => '2021/2022 Ganjil',],
            ['periode_id'    => 4,
            'periode'       => '2021/2022 Genap',],
            ['periode_id'    => 5,
            'periode'       => '2022/2023 Ganjil',],
            ['periode_id'    => 6,
            'periode'       => '2022/2023 Genap',],
            ['periode_id'    => 7,
            'periode'       => '2023/2024 Ganjil',],
            ['periode_id'    => 8,
            'periode'       => '2023/2024 Genap',],
            ['periode_id'    => 9,
            'periode'       => '2024/2025 Ganjil',],
            ['periode_id'    => 10,
            'periode'       => '2024/2025 Genap',],
        ];

        DB::table('m_periode')->insert($data);
    }
}
