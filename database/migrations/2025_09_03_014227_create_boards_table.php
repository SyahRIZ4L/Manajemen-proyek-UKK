<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->integer('board_id', true);
            $table->integer('project_id');
            $table->string('board_name', 100);
            $table->text('description')->nullable();
            $table->integer('position');
            $table->timestamp('created_at')->useCurrent();

            // Foreign key
            $table->foreign('project_id')->references('project_id')->on('projects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('boards');
    }
};
