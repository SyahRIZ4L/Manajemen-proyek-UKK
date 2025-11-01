<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->integer('project_id', true);
            $table->string('project_name', 100);
            $table->text('description')->nullable();
            $table->integer('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->date('deadline')->nullable();
            $table->enum('status', ['Planning', 'In Progress', 'On Hold', 'Completed'])->default('Planning');
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
