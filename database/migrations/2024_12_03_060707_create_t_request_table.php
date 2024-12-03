<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_request', function (Blueprint $table) {
            $table->id('id_request'); // Primary key
            $table->unsignedBigInteger('tugas_id'); // Foreign key tugas
            $table->unsignedBigInteger('mhs_id'); // Foreign key mahasiswa
            $table->unsignedBigInteger('tugas_pembuat_id'); // Foreign key pembuat tugas
            $table->enum('status_request', ['pending', 'accepted', 'rejected'])->default('pending'); // Status request
            $table->datetime('tgl_request')->nullable(); // Tanggal request dibuat
            $table->datetime('tgl_update_status')->nullable(); // Tanggal status diperbarui
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('tugas_id')->references('tugas_id')->on('t_tugas')->onDelete('cascade');
            $table->foreign('mhs_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('cascade');
            $table->foreign('tugas_pembuat_id')->references('tugas_pembuat_id')->on('t_tugas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_request');
    }
}
