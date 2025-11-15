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
        Schema::create('card_reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->integer('card_id');
            $table->integer('reviewer_id'); // TeamLead user_id
            $table->integer('submitter_id'); // Developer/Designer user_id
            $table->enum('action', ['approve', 'reject']);
            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('card_id')->references('card_id')->on('cards')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('submitter_id')->references('user_id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['card_id', 'status']);
            $table->index('reviewer_id');
            $table->index('submitter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_reviews');
    }
};
