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
        // Remove 'status' from 't_tugas_mahasiswa'
        Schema::table('t_tugas_mahasiswa', function (Blueprint $table) {
            if (Schema::hasColumn('t_tugas_mahasiswa', 'status')) {
                $table->dropColumn('status');
            }
        });

        // Remove 'progress' from 't_tugas'
        Schema::table('t_tugas', function (Blueprint $table) {
            if (Schema::hasColumn('t_tugas', 'progress')) {
                $table->dropColumn('progress');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add 'status' back to 't_tugas_mahasiswa'
        Schema::table('t_tugas_mahasiswa', function (Blueprint $table) {
            $table->string('status', 1)->nullable(); // Adjust the type and length as needed
        });

        // Add 'progress' back to 't_tugas'
        Schema::table('t_tugas', function (Blueprint $table) {
            $table->integer('progress')->default(0); // Adjust the default value as needed
        });
    }
};
