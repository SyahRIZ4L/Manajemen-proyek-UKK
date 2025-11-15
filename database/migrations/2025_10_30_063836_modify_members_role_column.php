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
            // Change role column to support proper role-based access control
            DB::statement("ALTER TABLE members MODIFY COLUMN role ENUM('Project_Admin', 'Team_Lead', 'Developer', 'Designer', 'Member') NOT NULL DEFAULT 'Member'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Revert back to old enum values
            DB::statement("ALTER TABLE members MODIFY COLUMN role ENUM('super admin', 'admin', 'member') NOT NULL DEFAULT 'member'");
        });
    }
};
