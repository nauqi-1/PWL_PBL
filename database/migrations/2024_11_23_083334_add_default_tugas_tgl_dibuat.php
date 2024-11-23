<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('t_tugas', function (Blueprint $table) {
            $table->timestamp('tugas_tgl_dibuat')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tugas', function (Blueprint $table) {
            $table->timestamp('tugas_tgl_dibuat')->nullable(false)->change();
        });
    }
};
