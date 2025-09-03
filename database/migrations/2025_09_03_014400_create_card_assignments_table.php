<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('management_projek_card_assignments', function (Blueprint $table) {
            $table->integer('assignment_id', true);
            $table->integer('card_id');
            $table->integer('user_id');
            $table->timestamp('assigned_at')->useCurrent();
            $table->enum('assignment_status', ['assigned', 'in_progress', 'completed'])->default('assigned');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            // Foreign keys
            $table->foreign('card_id')->references('card_id')->on('management_projek_cards')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('management_projek_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('management_projek_card_assignments');
    }
};
