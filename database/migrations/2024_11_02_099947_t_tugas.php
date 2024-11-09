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
        Schema::create('t_tugas', function (Blueprint $table) {
            $table->id('tugas_id');
            $table->string('tugas_nama', 50);
            $table->text('tugas_desc', 500)->nullable();
            $table->integer('tugas_bobot');
            $table->string('tugas_file')->nullable();
            $table->char('tugas_status', 1);
            $table->date('tugas_tgl_dibuat');
            $table->date('tugas_tgl_deadline');
            $table->unsignedBigInteger('tugas_pembuat_id')->index();
            $table->timestamps();

            $table->foreign('tugas_pembuat_id')->references('user_id')->on('m_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_tugas');
    }
};
