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
        Schema::table('time_logs', function (Blueprint $table) {
            $table->boolean('is_active')->default(false)->after('description');
            $table->boolean('is_paused')->default(false)->after('is_active');
            $table->dateTime('paused_at')->nullable()->after('is_paused');
            $table->integer('paused_duration')->default(0)->after('paused_at');
            $table->string('auto_timer_type', 50)->nullable()->after('paused_duration');
            $table->json('auto_timer_metadata')->nullable()->after('auto_timer_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'is_paused',
                'paused_at',
                'paused_duration',
                'auto_timer_type',
                'auto_timer_metadata'
            ]);
        });
    }
};
