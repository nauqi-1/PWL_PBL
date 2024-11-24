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
        Schema::table('t_tugas_kompetensi', function (Blueprint $table) {
            Schema::table('t_tugas_kompetensi', function (Blueprint $table) {
                // Drop the old foreign key constraint (if it exists)
                $table->dropForeign(['tugas_id']);

                // Add a new foreign key constraint with cascade delete
                $table->foreign('tugas_id')
                    ->references('tugas_id')
                    ->on('t_tugas')
                    ->onDelete('cascade');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tugas_kompetensi', function (Blueprint $table) {
            Schema::table('t_tugas_kompetensi', function (Blueprint $table) {
                // Drop the foreign key constraint
                $table->dropForeign(['tugas_id']);

                // Add the original foreign key constraint without cascade
                $table->foreign('tugas_id')
                    ->references('tugas_id')
                    ->on('t_tugas');
            });
        });
    }
};
