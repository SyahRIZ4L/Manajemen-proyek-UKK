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
            // Check and add columns only if they don't exist
            if (!Schema::hasColumn('cards', 'is_timer_active')) {
                $table->boolean('is_timer_active')->default(false)->after('actual_hours');
            }
            if (!Schema::hasColumn('cards', 'timer_started_at')) {
                $table->dateTime('timer_started_at')->nullable()->after('actual_hours');
            }
            if (!Schema::hasColumn('cards', 'deadline')) {
                $table->dateTime('deadline')->nullable()->after('actual_hours');
            }
            if (!Schema::hasColumn('cards', 'progress_percentage')) {
                $table->decimal('progress_percentage', 5, 2)->default(0)->after('actual_hours');
            }
            if (!Schema::hasColumn('cards', 'started_at')) {
                $table->dateTime('started_at')->nullable()->after('actual_hours');
            }
            if (!Schema::hasColumn('cards', 'completed_at')) {
                $table->dateTime('completed_at')->nullable()->after('actual_hours');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn([
                'is_timer_active',
                'timer_started_at',
                'deadline',
                'progress_percentage',
                'started_at',
                'completed_at'
            ]);
        });
    }
};
