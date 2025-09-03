<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('management_projek_project_members', function (Blueprint $table) {
            $table->integer('member_id', true);
            $table->integer('project_id');
            $table->integer('user_id');
            $table->enum('role', ['super admin', 'admin', 'member'])->default('member');
            $table->timestamp('joined_at')->useCurrent();

            // Foreign keys
            $table->foreign('project_id')->references('project_id')->on('management_projek_projects')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('management_projek_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('management_projek_project_members');
    }
};
