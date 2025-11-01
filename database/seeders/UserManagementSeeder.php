<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users except test users
        DB::table('users')->where('email', 'not like', '%@test.com')->delete();

        // Create dummy users for user management demo
        $dummyUsers = [
            [
                'username' => 'john_doe',
                'password' => Hash::make('password123'),
                'full_name' => 'John Doe',
                'email' => 'john.doe@company.com',
                'role' => 'Developer',
                'current_task_status' => 'working',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(5),
            ],
            [
                'username' => 'jane_smith',
                'password' => Hash::make('password123'),
                'full_name' => 'Jane Smith',
                'email' => 'jane.smith@company.com',
                'role' => 'Designer',
                'current_task_status' => 'idle',
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(3),
            ],
            [
                'username' => 'mike_wilson',
                'password' => Hash::make('password123'),
                'full_name' => 'Mike Wilson',
                'email' => 'mike.wilson@company.com',
                'role' => 'Team_Lead',
                'current_task_status' => 'working',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(1),
            ],
            [
                'username' => 'sarah_johnson',
                'password' => Hash::make('password123'),
                'full_name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@company.com',
                'role' => 'Developer',
                'current_task_status' => 'working',
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(2),
            ],
            [
                'username' => 'david_brown',
                'password' => Hash::make('password123'),
                'full_name' => 'David Brown',
                'email' => 'david.brown@company.com',
                'role' => 'Designer',
                'current_task_status' => 'idle',
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(4),
            ],
            [
                'username' => 'emily_davis',
                'password' => Hash::make('password123'),
                'full_name' => 'Emily Davis',
                'email' => 'emily.davis@company.com',
                'role' => 'Developer',
                'current_task_status' => 'working',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subHours(6),
            ],
            [
                'username' => 'alex_martinez',
                'password' => Hash::make('password123'),
                'full_name' => 'Alex Martinez',
                'email' => 'alex.martinez@company.com',
                'role' => 'member',
                'current_task_status' => 'idle',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(1),
            ],
            [
                'username' => 'lisa_garcia',
                'password' => Hash::make('password123'),
                'full_name' => 'Lisa Garcia',
                'email' => 'lisa.garcia@company.com',
                'role' => 'Designer',
                'current_task_status' => 'working',
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subHours(12),
            ],
            [
                'username' => 'chris_lee',
                'password' => Hash::make('password123'),
                'full_name' => 'Chris Lee',
                'email' => 'chris.lee@company.com',
                'role' => 'Developer',
                'current_task_status' => 'idle',
                'created_at' => now()->subDays(14),
                'updated_at' => now()->subDays(6),
            ],
            [
                'username' => 'anna_white',
                'password' => Hash::make('password123'),
                'full_name' => 'Anna White',
                'email' => 'anna.white@company.com',
                'role' => 'member',
                'current_task_status' => 'working',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(3),
            ],
            [
                'username' => 'robert_taylor',
                'password' => Hash::make('password123'),
                'full_name' => 'Robert Taylor',
                'email' => 'robert.taylor@company.com',
                'role' => 'Team_Lead',
                'current_task_status' => 'idle',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(8),
            ],
            [
                'username' => 'maria_rodriguez',
                'password' => Hash::make('password123'),
                'full_name' => 'Maria Rodriguez',
                'email' => 'maria.rodriguez@company.com',
                'role' => 'Designer',
                'current_task_status' => 'working',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subHours(18),
            ],
            [
                'username' => 'kevin_anderson',
                'password' => Hash::make('password123'),
                'full_name' => 'Kevin Anderson',
                'email' => 'kevin.anderson@company.com',
                'role' => 'Developer',
                'current_task_status' => 'idle',
                'created_at' => now()->subDays(11),
                'updated_at' => now()->subDays(7),
            ],
            [
                'username' => 'michelle_thomas',
                'password' => Hash::make('password123'),
                'full_name' => 'Michelle Thomas',
                'email' => 'michelle.thomas@company.com',
                'role' => 'member',
                'current_task_status' => 'working',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subHours(9),
            ],
            [
                'username' => 'james_jackson',
                'password' => Hash::make('password123'),
                'full_name' => 'James Jackson',
                'email' => 'james.jackson@company.com',
                'role' => 'Developer',
                'current_task_status' => 'idle',
                'created_at' => now()->subDays(13),
                'updated_at' => now()->subDays(3),
            ]
        ];

        DB::table('users')->insert($dummyUsers);

        $this->command->info('User management dummy data created successfully!');
        $this->command->info('Created 15 dummy users with various roles and statuses');
        $this->command->info('All dummy users have password: password123');
    }
}
