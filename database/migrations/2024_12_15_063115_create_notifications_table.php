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
        Schema::create('t_notifications', function (Blueprint $table) {
            $table->id('notification_id'); // Primary key
            $table->string('jenis_notification'); // Tipe notifikasi (e.g., 'request baru')
            $table->unsignedBigInteger('pembuat_notification'); // Foreign key to users (creator)
            $table->unsignedBigInteger('penerima_notification'); // Foreign key to users (receiver)
            $table->string('konten_notification'); // Deskripsi notifikasi
            $table->timestamp('tgl_notification'); // Tanggal dibuatnya notifikasi
            $table->enum('status_notification', ['unread', 'read'])->default('unread'); // Status notifikasi
            $table->timestamp('tgl_notifdibaca')->nullable(); // Waktu notifikasi dibaca (nullable)
            $table->string('ref_table')->nullable(); // Nama tabel terkait (e.g., 'requests')
            $table->unsignedBigInteger('ref_id')->nullable(); // ID entitas terkait (e.g., request ID)
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraints
            $table->foreign('pembuat_notification')->references('user_id')->on('m_user')->onDelete('cascade');
            $table->foreign('penerima_notification')->references('user_id')->on('m_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_notifications');
    }
};
