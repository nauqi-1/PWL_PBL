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
        Schema::create('m_dosen', function (Blueprint $table) {
            $table->id('dosen_id');
            $table->string('dosen_nama', 100);
            $table->string('dosen_prodi', 50);
            $table->string('dosen_noHp', 20);
            $table->string('dosen_nip', 50);
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
        Schema::dropIfExists('m_dosen');
    }
};
