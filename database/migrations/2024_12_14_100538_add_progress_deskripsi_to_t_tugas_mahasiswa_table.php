<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('t_tugas_mahasiswa', function (Blueprint $table) {
            $table->text('progress_deskripsi')->nullable()->after('progress'); // Menambahkan kolom progress_deskripsi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tugas_mahasiswa', function (Blueprint $table) {
            $table->dropColumn('progress_deskripsi'); // Menghapus kolom progress_deskripsi jika rollback
        });
    }
};
