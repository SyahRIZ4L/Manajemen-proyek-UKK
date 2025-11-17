<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('card_history', function (Blueprint $table) {
            $table->id('history_id');
            $table->integer('card_id');
            $table->integer('user_id');
            $table->enum('action', ['assigned', 'submitted', 'approved', 'rejected', 'status_changed', 'created']);
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('comment')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('action_date')->useCurrent();
            $table->json('metadata')->nullable(); // For additional data like estimated hours, priority, etc.

            // Foreign keys
            $table->foreign('card_id')->references('card_id')->on('cards')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['card_id', 'action_date']);
            $table->index(['user_id', 'action_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('card_history');
    }
};
