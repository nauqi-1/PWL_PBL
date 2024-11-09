<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_pengumpulan_mahasiswa', function (Blueprint $table) {
            $table->id('pengumpulan_mahasiswa_id');
            $table->unsignedBigInteger('pengumpulan_id');
            $table->unsignedBigInteger('mahasiswa_id');

            $table->foreign('pengumpulan_id')->references('pengumpulan_id')->on('t_pengumpulan')->onDelete('cascade');
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_pengumpulan_pekerja');
    }
};
