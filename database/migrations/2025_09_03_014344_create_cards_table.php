<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('management_projek_cards', function (Blueprint $table) {
            $table->integer('card_id', true);
            $table->integer('board_id');
            $table->string('card_title', 100);
            $table->text('description')->nullable();
            $table->integer('position');
            $table->integer('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->date('due_date')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'review', 'done'])->default('todo');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->decimal('actual_hours', 5, 2)->nullable();

            // Foreign keys
            $table->foreign('board_id')->references('board_id')->on('management_projek_boards')->onDelete('cascade');
            $table->foreign('created_by')->references('user_id')->on('management_projek_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('management_projek_cards');
    }
};
