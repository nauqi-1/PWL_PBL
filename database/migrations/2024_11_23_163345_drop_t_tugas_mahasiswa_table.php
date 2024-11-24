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
        // Drop the t_tugas_mahasiswa table
        Schema::dropIfExists('t_tugas_mahasiswa');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Recreate the t_tugas_mahasiswa table if needed
        Schema::create('t_tugas_mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('tugas_mahasiswa_id');
            $table->unsignedBigInteger('tugas_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->timestamps();

            // Add the foreign key constraints
            $table->foreign('tugas_id')->references('tugas_id')->on('t_tugas');
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('mahasiswa');
        });
    }
};
