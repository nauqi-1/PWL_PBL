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
        Schema::table('m_mahasiswa', function (Blueprint $table) {
            // Rename 'mahasiswa_alfa_sisa' to 'mahasiswa_alfa_lunas'
            $table->renameColumn('mahasiswa_alfa_sisa', 'mahasiswa_alfa_lunas');
            
            // Drop 'mahasiswa_alfa_total' column
            $table->dropColumn('mahasiswa_alfa_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_mahasiswa', function (Blueprint $table) {
            // Reverse the renaming of the column
            $table->renameColumn('mahasiswa_alfa_lunas', 'mahasiswa_alfa_sisa');
            
            // Add the 'mahasiswa_alfa_total' column back (if needed)
            $table->integer('mahasiswa_alfa_total')->nullable();  // Adjust the column type if needed
        });
    }
};
