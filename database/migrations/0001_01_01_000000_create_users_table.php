<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('management_projek_users', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('full_name', 100);
            $table->string('email', 100)->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->enum('current_task_status', ['idle', 'working'])->default('idle');
        });
    }

    public function down()
    {
        Schema::dropIfExists('management_projek_users');
    }
};
