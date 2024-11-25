<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LevelSeeder::class,
            KompetensiSeeder::class,
            PeriodeSeeder::class,
            TugasJenisSeeder::class,

            UserSeeder::class,
            AdminSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,
            TendikSeeder::class,
            TugasSeeder::class,

            MahasiswaAlfaSeeder::class,
            TugasKompetensiSeeder::class,
            TugasMahasiswaSeeder::class,
        ]);
    }
}
