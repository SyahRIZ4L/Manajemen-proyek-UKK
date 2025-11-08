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
        Schema::table('projects', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            $table->string('completed_by')->nullable()->after('cancelled_at');
            $table->string('cancelled_by')->nullable()->after('completed_by');
            $table->text('completion_notes')->nullable()->after('cancelled_by');
            $table->text('cancellation_reason')->nullable()->after('completion_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'completed_at',
                'cancelled_at',
                'completed_by',
                'cancelled_by',
                'completion_notes',
                'cancellation_reason'
            ]);
        });
    }
};
