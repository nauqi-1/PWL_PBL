<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalDisubmitToTTugasMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_tugas_mahasiswa', function (Blueprint $table) {
            $table->timestamp('tanggal_disubmit')->nullable()->after('progress');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_tugas_mahasiswa', function (Blueprint $table) {
            $table->dropColumn('tanggal_disubmit');
        });
    }
}
