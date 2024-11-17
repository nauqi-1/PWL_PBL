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
            $table->char('status', 1); //O = Open, R = Requested, W = Working, P = pending approval, D= Done 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tugas_mahasiswa', function (Blueprint $table) {
            //
        });
    }
};
