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
        Schema::create('t_detail_kompetensi', function (Blueprint $table) {
            $table->id('detail_kompetensi_id');
            $table->unsignedBigInteger('tugas_id')->index();
            $table->unsignedBigInteger('kompetensi_id')->index();
            $table->timestamps();

            
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
