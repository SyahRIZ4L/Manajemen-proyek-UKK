<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestTeamLeadCardSeeder extends Seeder
{
    public function run()
    {
        // Create test Team Lead user if not exists
        $teamLead = DB::table('users')->where('email', 'teamlead@test.com')->first();

        if (!$teamLead) {
            $teamLeadId = DB::table('users')->insertGetId([
                'username' => 'teamlead_test',
                'email' => 'teamlead@test.com',
                'full_name' => 'Test Team Lead',
                'role' => 'Team_Lead',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $teamLeadId = $teamLead->user_id;
        }

        // Create test project if not exists
        $project = DB::table('projects')->where('project_name', 'Test Project for Team Lead')->first();

        if (!$project) {
            $projectId = DB::table('projects')->insertGetId([
                'project_name' => 'Test Project for Team Lead',
                'description' => 'Test Project for My Cards functionality',
                'deadline' => now()->addMonths(3),
                'status' => 'In Progress',
                'created_by' => $teamLeadId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $projectId = $project->project_id;
        }

        // Create test board if not exists
        $board = DB::table('boards')->where('project_id', $projectId)->first();

        if (!$board) {
            $boardId = DB::table('boards')->insertGetId([
                'project_id' => $projectId,
                'board_name' => 'Test Board',
                'description' => 'Test Board for cards',
                'position' => 1,
                'created_at' => now(),
            ]);
        } else {
            $boardId = $board->board_id;
        }

        // Create test cards
        $cards = [
            [
                'board_id' => $boardId,
                'card_title' => 'Setup Project Structure',
                'description' => 'Create the basic project structure and setup development environment',
                'position' => 1,
                'created_by' => $teamLeadId,
                'status' => 'todo',
                'priority' => 'high',
                'due_date' => now()->addDays(7),
                'estimated_hours' => 8.0,
                'created_at' => now(),
            ],
            [
                'board_id' => $boardId,
                'card_title' => 'Design Database Schema',
                'description' => 'Design and implement the database schema for the application',
                'position' => 2,
                'created_by' => $teamLeadId,
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => now()->addDays(5),
                'estimated_hours' => 12.0,
                'created_at' => now()->subDays(2),
            ],
            [
                'board_id' => $boardId,
                'card_title' => 'Create User Authentication',
                'description' => 'Implement user registration, login, and authentication system',
                'position' => 3,
                'created_by' => $teamLeadId,
                'status' => 'review',
                'priority' => 'medium',
                'due_date' => now()->addDays(10),
                'estimated_hours' => 16.0,
                'created_at' => now()->subDays(5),
            ],
            [
                'board_id' => $boardId,
                'card_title' => 'Setup Testing Framework',
                'description' => 'Configure and setup automated testing framework for the project',
                'position' => 4,
                'created_by' => $teamLeadId,
                'status' => 'done',
                'priority' => 'low',
                'due_date' => now()->subDays(3),
                'estimated_hours' => 6.0,
                'actual_hours' => 5.5,
                'created_at' => now()->subDays(10),
            ],
            [
                'board_id' => $boardId,
                'card_title' => 'API Documentation',
                'description' => 'Create comprehensive API documentation for all endpoints',
                'position' => 5,
                'created_by' => $teamLeadId,
                'status' => 'todo',
                'priority' => 'medium',
                'due_date' => now()->addDays(14),
                'estimated_hours' => 10.0,
                'created_at' => now()->subHours(6),
            ],
        ];

        foreach ($cards as $card) {
            DB::table('cards')->insert($card);
        }

        echo "Test Team Lead and Cards data created successfully!\n";
        echo "Team Lead Email: teamlead@test.com\n";
        echo "Password: password123\n";
        echo "Created " . count($cards) . " test cards\n";
    }
}
