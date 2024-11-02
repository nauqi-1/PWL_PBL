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
        Schema::create('m_mahasiswa', function (Blueprint $table) {
            $table->id('mahasiswa_id');
            $table->string('mahasiswa_nama', 100);
            $table->string('mahasiswa_kelas', 50);
            $table->string('mahasiswa_nim', 50);
            $table->string('mahasiswa_prodi', 50);
            $table->string('mahasiswa_noHp', 50);
            $table->integer('mahasiswa_alfa_sisa');
            $table->integer('mahasiswa_alfa_total');
            $table->unsignedBigInteger('user_id')->index();


            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_mahasiswa');
    }
};
