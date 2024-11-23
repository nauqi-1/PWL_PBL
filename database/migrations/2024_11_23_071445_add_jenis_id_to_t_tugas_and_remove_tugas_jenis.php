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
        // Menambah kolom jenis_id (FK ke t_tugas_jenis) ke tabel t_tugas
        Schema::table('t_tugas', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_id')->nullable()->after('tugas_jenis');
            $table->foreign('jenis_id')->references('jenis_id')->on('t_tugas_jenis')->onDelete('set null');
        });

        // Menghapus kolom tugas_jenis di tabel t_tugas
        Schema::table('t_tugas', function (Blueprint $table) {
            $table->dropColumn('tugas_jenis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom jenis_id dan foreign key
        Schema::table('t_tugas', function (Blueprint $table) {
            $table->dropForeign(['jenis_id']);
            $table->dropColumn('jenis_id');
        });

        // Menambahkan kembali kolom tugas_jenis
        Schema::table('t_tugas', function (Blueprint $table) {
            $table->string('tugas_jenis')->nullable()->after('tugas_desc');
        });
    }
};
