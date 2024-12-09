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
            $table->string('file_path')->nullable()->after('status'); // Menyimpan path file tugas mahasiswa
            $table->unsignedInteger('progress')->default(0)->after('file_path'); // Menyimpan progres (0-100%)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tugas_mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'progress']);
        });
    }
};
