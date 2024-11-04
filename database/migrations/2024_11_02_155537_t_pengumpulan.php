<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\SerializableClosure\UnsignedSerializableClosure;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_pengumpulan', function (Blueprint $table) {
            $table->id('pengumpulan_id');
            $table->unsignedBigInteger('pengumpulan_tugas_id')->index();
            $table->unsignedBigInteger('pengumpulan_pembuat_id')->index();
            $table->date('pengumpulan_tanggal');
            $table->string('pengumpulan_file')->nullable();
            $table->timestamps();

            $table->foreign('pengumpulan_tugas_id')->references('tugas_id')->on('t_tugas');
            $table->foreign('pengumpulan_pembuat_id')->references('tugas_pembuat_id')->on('t_tugas');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pengumpulan');
    }
};
