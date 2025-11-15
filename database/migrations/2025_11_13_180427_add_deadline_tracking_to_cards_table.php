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
        Schema::table('cards', function (Blueprint $table) {
            // Add datetime fields for better deadline tracking
            $table->datetime('deadline')->nullable()->after('due_date');
            $table->timestamp('started_at')->nullable()->after('deadline');
            $table->timestamp('completed_at')->nullable()->after('started_at');

            // Add time tracking fields
            $table->boolean('is_timer_active')->default(false)->after('completed_at');
            $table->timestamp('timer_started_at')->nullable()->after('is_timer_active');

            // Add deadline status tracking
            $table->enum('deadline_status', ['on_time', 'urgent', 'overdue', 'completed'])->default('on_time')->after('timer_started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn([
                'deadline',
                'started_at',
                'completed_at',
                'is_timer_active',
                'timer_started_at',
                'deadline_status'
            ]);
        });
    }
};
