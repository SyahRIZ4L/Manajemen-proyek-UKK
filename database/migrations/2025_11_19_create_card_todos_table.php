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
        Schema::create('card_todos', function (Blueprint $table) {
            $table->id('todo_id');
            $table->integer('card_id'); // Match the cards table type
            $table->integer('user_id'); // Match the users table type
            $table->text('text');
            $table->boolean('completed')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('card_id')->references('card_id')->on('cards')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('card_id');
            $table->index('user_id');
            $table->index('completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_todos');
    }
};
