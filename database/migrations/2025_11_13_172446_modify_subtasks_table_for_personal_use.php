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
        Schema::table('subtasks', function (Blueprint $table) {
            // Check and add columns for personal subtasks functionality
            if (!Schema::hasColumn('subtasks', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('description');
            }
            if (!Schema::hasColumn('subtasks', 'subtask_status')) {
                $table->enum('subtask_status', ['active', 'completed'])->default('active')->after('description');
            }
            if (!Schema::hasColumn('subtasks', 'due_date')) {
                $table->date('due_date')->nullable()->after('description');
            }
            if (!Schema::hasColumn('subtasks', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('description');
            }

            // Add user_id if not exists (personal subtasks)
            if (!Schema::hasColumn('subtasks', 'user_id')) {
                $table->integer('user_id')->nullable()->after('card_id');
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'subtask_status']);
            $table->dropIndex(['user_id', 'priority']);
            $table->dropColumn(['priority', 'subtask_status', 'due_date', 'completed_at']);
        });
    }
};
