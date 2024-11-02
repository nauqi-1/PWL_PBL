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
        Schema::create('t_detail_mahasiswa', function (Blueprint $table) {
            $table->id('detail_mahasiswa_id');
            $table->unsignedBigInteger('tugas_id')->index();
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->timestamps();

            $table->foreign('tugas_id')->references('tugas_id')->on('m_tugas');
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_detail_mahasiswa');
    }
};
