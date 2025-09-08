<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('full_name', 100);
            $table->string('email', 100)->unique();
            $table->enum('role', [
                'Project_Admin',
                'Team_Lead',
                'Developer',
                'Designer',
                'member'
            ])->default('member');
            $table->enum('current_task_status', ['idle', 'working'])->default('idle');
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
