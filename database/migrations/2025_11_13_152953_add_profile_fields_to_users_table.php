<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('email');
            $table->text('bio')->nullable()->after('profile_photo');
            $table->string('phone', 20)->nullable()->after('bio');
            $table->text('address')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('address');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
            $table->string('website')->nullable()->after('gender');
            $table->json('skills')->nullable()->after('website');
            $table->enum('status', ['active', 'inactive', 'busy', 'available'])->default('active')->after('skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo',
                'bio',
                'phone',
                'address',
                'birth_date',
                'gender',
                'website',
                'skills',
                'status'
            ]);
        });
    }
};
