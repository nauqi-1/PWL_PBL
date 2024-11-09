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
        Schema::create('t_tugas_kompetensi', function (Blueprint $table) {
            $table->id('tugas_kompetensi_id');
            $table->unsignedBigInteger('tugas_id')->index();
            $table->unsignedBigInteger('kompetensi_id')->index();
            $table->timestamps();

            $table->foreign('tugas_id')->references('tugas_id')->on('t_tugas');
            $table->foreign('kompetensi_id')->references('kompetensi_id')->on('m_kompetensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_detail_kompetensi');
    }
};
