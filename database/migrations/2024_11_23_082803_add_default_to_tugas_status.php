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
        Schema::table('t_tugas', function (Blueprint $table) {
            $table->char('tugas_status', 1)->default('O')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tugas', function (Blueprint $table) {
            $table->char('tugas_status', 1)->default(null)->change();
        });
    }
};
