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
        Schema::create('t_tugas_mahasiswa', function (Blueprint $table) {
            $table->id('tugas_mahasiswa_id');
            $table->unsignedBigInteger('tugas_id')->index();
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->char('status', 1); // O = Open, R = Requested, W = Working, P = Pending approval, D = Done
            $table->timestamps();

            // Foreign key with cascade delete
            $table->foreign('tugas_id')
                ->references('tugas_id')
                ->on('t_tugas')
                ->onDelete('cascade');

            $table->foreign('mahasiswa_id')
                ->references('mahasiswa_id')
                ->on('m_mahasiswa')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_tugas_mahasiswa');
    }
};
