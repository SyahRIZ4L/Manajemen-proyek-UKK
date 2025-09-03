<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('management_projek_projects', function (Blueprint $table) {
            $table->integer('project_id', true);
            $table->string('project_name', 100);
            $table->text('description')->nullable();
            $table->integer('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->date('deadline')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('management_projek_projects');
    }
};
