<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MemberPanelTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users if they don't exist
        $teamLeadId = DB::table('users')->insertGetId([
            'username' => 'teamlead_test',
            'password' => Hash::make('password'),
            'full_name' => 'Team Lead Test',
            'email' => 'teamlead@test.com',
            'role' => 'Team_Lead',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $developerId = DB::table('users')->insertGetId([
            'username' => 'developer_test',
            'password' => Hash::make('password'),
            'full_name' => 'Developer Test',
            'email' => 'developer@test.com',
            'role' => 'Developer',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $designerId = DB::table('users')->insertGetId([
            'username' => 'designer_test',
            'password' => Hash::make('password'),
            'full_name' => 'Designer Test',
            'email' => 'designer@test.com',
            'role' => 'Designer',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create test project
        $projectId = DB::table('projects')->insertGetId([
            'project_name' => 'Member Panel Test Project',
            'description' => 'Test project for member panel functionality',
            'status' => 'Active',
            'created_by' => $teamLeadId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Add project members
        DB::table('project_members')->insert([
            ['project_id' => $projectId, 'user_id' => $teamLeadId, 'role' => 'Team_Lead', 'joined_at' => now()],
            ['project_id' => $projectId, 'user_id' => $developerId, 'role' => 'Developer', 'joined_at' => now()],
            ['project_id' => $projectId, 'user_id' => $designerId, 'role' => 'Designer', 'joined_at' => now()],
        ]);

        // Create test board
        $boardId = DB::table('boards')->insertGetId([
            'project_id' => $projectId,
            'board_name' => 'Test Board - Development',
            'description' => 'Test board for development tasks',
            'created_by' => $teamLeadId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create test cards
        $cards = [
            [
                'board_id' => $boardId,
                'card_title' => 'Implement User Authentication',
                'description' => 'Create login and registration functionality with proper validation',
                'status' => 'todo',
                'priority' => 'high',
                'due_date' => Carbon::now()->addDays(7),
                'estimated_hours' => 8,
                'created_by' => $teamLeadId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'board_id' => $boardId,
                'card_title' => 'Design Dashboard Layout',
                'description' => 'Create responsive dashboard design with modern UI components',
                'status' => 'todo',
                'priority' => 'medium',
                'due_date' => Carbon::now()->addDays(5),
                'estimated_hours' => 6,
                'created_by' => $teamLeadId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'board_id' => $boardId,
                'card_title' => 'API Documentation',
                'description' => 'Write comprehensive API documentation for all endpoints',
                'status' => 'in_progress',
                'priority' => 'low',
                'due_date' => Carbon::now()->addDays(10),
                'estimated_hours' => 4,
                'created_by' => $teamLeadId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'board_id' => $boardId,
                'card_title' => 'Database Optimization',
                'description' => 'Optimize database queries and add necessary indexes',
                'status' => 'review',
                'priority' => 'medium',
                'due_date' => Carbon::now()->addDays(3),
                'estimated_hours' => 5,
                'created_by' => $teamLeadId,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        $cardIds = [];
        foreach ($cards as $card) {
            $cardIds[] = DB::table('cards')->insertGetId($card);
        }

        // Assign cards to members
        $assignments = [
            ['card_id' => $cardIds[0], 'user_id' => $developerId, 'assignment_status' => 'assigned'],
            ['card_id' => $cardIds[1], 'user_id' => $designerId, 'assignment_status' => 'assigned'],
            ['card_id' => $cardIds[2], 'user_id' => $developerId, 'assignment_status' => 'in_progress', 'started_at' => now()],
            ['card_id' => $cardIds[3], 'user_id' => $developerId, 'assignment_status' => 'in_progress', 'started_at' => Carbon::now()->subDays(1)],
        ];

        foreach ($assignments as $assignment) {
            DB::table('card_assignments')->insert(array_merge($assignment, [
                'assigned_at' => now()
            ]));
        }

        // Create some test time logs
        $timeLogs = [
            [
                'card_id' => $cardIds[2],
                'user_id' => $developerId,
                'start_time' => Carbon::now()->subHours(2),
                'end_time' => Carbon::now()->subHours(1),
                'duration_minutes' => 60,
                'description' => 'Working on API documentation structure'
            ],
            [
                'card_id' => $cardIds[3],
                'user_id' => $developerId,
                'start_time' => Carbon::now()->subDay()->subHours(3),
                'end_time' => Carbon::now()->subDay()->subHours(1),
                'duration_minutes' => 120,
                'description' => 'Database query analysis and optimization'
            ],
            [
                'card_id' => $cardIds[2],
                'user_id' => $developerId,
                'start_time' => Carbon::now()->subMinutes(30),
                'end_time' => Carbon::now(),
                'duration_minutes' => 30,
                'description' => 'Writing endpoint documentation'
            ]
        ];

        foreach ($timeLogs as $log) {
            DB::table('time_logs')->insert($log);
        }

        // Create test notifications
        $notifications = [
            [
                'user_id' => $developerId,
                'project_id' => $projectId,
                'triggered_by' => $teamLeadId,
                'type' => 'card_assignment',
                'title' => 'New Card Assigned',
                'message' => 'You have been assigned to work on: Implement User Authentication',
                'data' => json_encode([
                    'card_id' => $cardIds[0],
                    'card_title' => 'Implement User Authentication',
                    'assigned_by' => 'Team Lead Test',
                    'priority' => 'high',
                    'action_url' => '/member/card/' . $cardIds[0]
                ]),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $designerId,
                'project_id' => $projectId,
                'triggered_by' => $teamLeadId,
                'type' => 'card_assignment',
                'title' => 'New Card Assigned',
                'message' => 'You have been assigned to work on: Design Dashboard Layout',
                'data' => json_encode([
                    'card_id' => $cardIds[1],
                    'card_title' => 'Design Dashboard Layout',
                    'assigned_by' => 'Team Lead Test',
                    'priority' => 'medium',
                    'action_url' => '/member/card/' . $cardIds[1]
                ]),
                'is_read' => false,
                'created_at' => Carbon::now()->subMinutes(30),
                'updated_at' => Carbon::now()->subMinutes(30)
            ]
        ];

        foreach ($notifications as $notification) {
            DB::table('notifications')->insert($notification);
        }

        $this->command->info('Member Panel test data seeded successfully!');
        $this->command->info('Test users created:');
        $this->command->info('- Team Lead: teamlead_test / password');
        $this->command->info('- Developer: developer_test / password');
        $this->command->info('- Designer: designer_test / password');
    }
}
