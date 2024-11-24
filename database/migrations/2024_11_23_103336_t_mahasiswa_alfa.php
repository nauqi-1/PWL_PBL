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
        Schema::create('t_mahasiswa_alfa', function (Blueprint $table) {
            $table->id('mahasiswa_alfa_id');
            $table->unsignedBigInteger('periode_id')->index();
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->integer('jumlah_alfa');
            $table->timestamps();

            $table->foreign('periode_id')->references('periode_id')->on('m_periode');
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mahasiswa_alfa');
    }
};
