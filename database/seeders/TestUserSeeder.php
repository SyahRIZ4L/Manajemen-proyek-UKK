<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing test users
        DB::table('users')->where('email', 'like', '%test.com')->delete();

        // Create test users with different roles
        $users = [
            [
                'username' => 'admin_test',
                'password' => Hash::make('password123'),
                'full_name' => 'Admin Test User',
                'email' => 'admin@test.com',
                'role' => 'Project_Admin',
                'current_task_status' => 'idle',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'teamlead_test',
                'password' => Hash::make('password123'),
                'full_name' => 'Team Lead Test User',
                'email' => 'teamlead@test.com',
                'role' => 'Team_Lead',
                'current_task_status' => 'idle',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'developer_test',
                'password' => Hash::make('password123'),
                'full_name' => 'Developer Test User',
                'email' => 'developer@test.com',
                'role' => 'Developer',
                'current_task_status' => 'working',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'designer_test',
                'password' => Hash::make('password123'),
                'full_name' => 'Designer Test User',
                'email' => 'designer@test.com',
                'role' => 'Designer',
                'current_task_status' => 'idle',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'member_test',
                'password' => Hash::make('password123'),
                'full_name' => 'Member Test User',
                'email' => 'member@test.com',
                'role' => 'member',
                'current_task_status' => 'idle',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }

        $this->command->info('Test users created successfully!');
        $this->command->info('Login credentials (password: password123):');
        $this->command->info('- admin@test.com (Project Admin)');
        $this->command->info('- teamlead@test.com (Team Lead)');
        $this->command->info('- developer@test.com (Developer)');
        $this->command->info('- designer@test.com (Designer)');
        $this->command->info('- member@test.com (Member)');
    }
}
