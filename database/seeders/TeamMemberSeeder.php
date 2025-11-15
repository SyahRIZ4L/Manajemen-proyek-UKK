<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create developers
        $developers = [
            [
                'user_id' => 101,
                'full_name' => 'John Developer',
                'email' => 'john.dev@example.com',
                'username' => 'johndev',
                'password' => Hash::make('password123'),
                'role' => 'developer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 102,
                'full_name' => 'Sarah Coder',
                'email' => 'sarah.coder@example.com',
                'username' => 'sarahcoder',
                'password' => Hash::make('password123'),
                'role' => 'developer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 103,
                'full_name' => 'Mike Frontend',
                'email' => 'mike.frontend@example.com',
                'username' => 'mikefront',
                'password' => Hash::make('password123'),
                'role' => 'developer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Create designers
        $designers = [
            [
                'user_id' => 201,
                'full_name' => 'Emma Designer',
                'email' => 'emma.design@example.com',
                'username' => 'emmadesign',
                'password' => Hash::make('password123'),
                'role' => 'designer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 202,
                'full_name' => 'Alex UX',
                'email' => 'alex.ux@example.com',
                'username' => 'alexux',
                'password' => Hash::make('password123'),
                'role' => 'designer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 203,
                'full_name' => 'Lisa Graphic',
                'email' => 'lisa.graphic@example.com',
                'username' => 'lisagraphic',
                'password' => Hash::make('password123'),
                'role' => 'designer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Create members
        $members = [
            [
                'user_id' => 301,
                'full_name' => 'Tom Member',
                'email' => 'tom.member@example.com',
                'username' => 'tommember',
                'password' => Hash::make('password123'),
                'role' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 302,
                'full_name' => 'Anna Support',
                'email' => 'anna.support@example.com',
                'username' => 'annasupport',
                'password' => Hash::make('password123'),
                'role' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Insert all users
        foreach (array_merge($developers, $designers, $members) as $user) {
            DB::table('users')->updateOrInsert(
                ['user_id' => $user['user_id']],
                $user
            );
        }

        echo "Team member users created successfully!\n";
        echo "Developers: " . count($developers) . "\n";
        echo "Designers: " . count($designers) . "\n";
        echo "Members: " . count($members) . "\n";
        echo "\nAll users have password: password123\n";
    }
}
