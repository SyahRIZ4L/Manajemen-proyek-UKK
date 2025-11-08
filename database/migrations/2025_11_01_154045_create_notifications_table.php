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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->unsignedInteger('user_id'); // User yang menerima notifikasi
            $table->unsignedInteger('project_id')->nullable(); // Project terkait
            $table->unsignedInteger('triggered_by')->nullable(); // User yang melakukan action
            $table->string('type'); // task_update, status_change, project_update, etc
            $table->string('title'); // Judul notifikasi
            $table->text('message'); // Pesan notifikasi
            $table->json('data')->nullable(); // Data tambahan dalam format JSON
            $table->boolean('is_read')->default(false); // Status sudah dibaca atau belum
            $table->timestamp('read_at')->nullable(); // Waktu dibaca
            $table->timestamps();

            // Indexes untuk performa
            $table->index(['user_id', 'is_read']);
            $table->index(['project_id']);
            $table->index(['type']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
