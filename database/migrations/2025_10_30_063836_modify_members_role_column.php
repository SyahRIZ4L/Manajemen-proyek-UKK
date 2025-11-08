<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Change role column from enum to varchar to accept all user roles
            DB::statement("ALTER TABLE members MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'member'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Revert back to enum
            DB::statement("ALTER TABLE members MODIFY COLUMN role ENUM('super admin', 'admin', 'member') NOT NULL DEFAULT 'member'");
        });
    }
};
