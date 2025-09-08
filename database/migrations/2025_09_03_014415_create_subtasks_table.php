<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->integer('subtask_id', true);
            $table->integer('card_id');
            $table->string('subtask_title', 100);
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'done'])->default('todo');
            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->decimal('actual_hours', 5, 2)->nullable();
            $table->integer('position');
            $table->timestamp('created_at')->useCurrent();

            // Foreign key
            $table->foreign('card_id')->references('card_id')->on('cards')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subtasks');
    }
};
