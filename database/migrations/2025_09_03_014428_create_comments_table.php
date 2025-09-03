<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('management_projek_comments', function (Blueprint $table) {
            $table->integer('comment_id', true);
            $table->integer('card_id')->nullable();
            $table->integer('subtask_id')->nullable();
            $table->integer('user_id');
            $table->text('comment_text');
            $table->enum('comment_type', ['card', 'subtask']);
            $table->timestamp('created_at')->useCurrent();

            // Foreign keys
            $table->foreign('card_id')->references('card_id')->on('management_projek_cards')->onDelete('cascade');
            $table->foreign('subtask_id')->references('subtask_id')->on('management_projek_subtasks')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('management_projek_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('management_projek_comments');
    }
};
