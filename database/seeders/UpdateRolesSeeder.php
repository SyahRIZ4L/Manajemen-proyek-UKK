<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // First, update the column type to VARCHAR to allow all values
            DB::statement("ALTER TABLE members MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'Member'");

            echo "Step 1: Changed role column to VARCHAR\n";

            // Update existing role values
            DB::table('members')
                ->where('role', 'admin')
                ->update(['role' => 'Team_Lead']);

            DB::table('members')
                ->where('role', 'super admin')
                ->update(['role' => 'Project_Admin']);

            DB::table('members')
                ->where('role', 'member')
                ->update(['role' => 'Member']);

            echo "Step 2: Updated existing role values\n";

            // Now change back to ENUM with new values
            DB::statement("ALTER TABLE members MODIFY COLUMN role ENUM('Project_Admin', 'Team_Lead', 'Developer', 'Designer', 'Member') NOT NULL DEFAULT 'Member'");

            echo "Step 3: Changed role column to ENUM with new values\n";

            // Display current roles
            $roles = DB::table('members')->select('role')->distinct()->get();
            echo "\nCurrent roles in database:\n";
            foreach ($roles as $role) {
                echo "- {$role->role}\n";
            }

            echo "\n✅ Role system updated successfully!\n";

        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
}
