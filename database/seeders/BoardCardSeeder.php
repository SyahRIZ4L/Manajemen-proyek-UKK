<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class BoardCardSeeder extends Seeder
{
    public function run()
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $this->command->error('No projects found. Please run ProjectSeeder first.');
            return;
        }

        foreach ($projects as $project) {
            // Create a main board for each project
            $boardId = DB::table('boards')->insertGetId([
                'project_id' => $project->project_id,
                'board_name' => $project->project_name . ' Board',
                'description' => 'Main project board for ' . $project->project_name,
                'position' => 1,
                'created_at' => now()
            ]);

            // Create sample cards with different statuses
            $cardData = [
                ['title' => 'Setup Project Structure', 'status' => 'done', 'priority' => 'high'],
                ['title' => 'Design Database Schema', 'status' => 'done', 'priority' => 'high'],
                ['title' => 'Implement User Authentication', 'status' => 'in_progress', 'priority' => 'high'],
                ['title' => 'Create API Endpoints', 'status' => 'in_progress', 'priority' => 'medium'],
                ['title' => 'Design UI Components', 'status' => 'review', 'priority' => 'medium'],
                ['title' => 'Write Unit Tests', 'status' => 'todo', 'priority' => 'medium'],
                ['title' => 'Deploy to Production', 'status' => 'todo', 'priority' => 'low'],
                ['title' => 'Documentation Update', 'status' => 'todo', 'priority' => 'low'],
            ];

            // Get first user as card creator
            $creator = DB::table('users')->first();

            foreach ($cardData as $index => $card) {
                DB::table('cards')->insert([
                    'board_id' => $boardId,
                    'card_title' => $card['title'],
                    'description' => 'Sample card description for ' . $card['title'],
                    'status' => $card['status'],
                    'priority' => $card['priority'],
                    'position' => $index + 1,
                    'created_by' => $creator->user_id,
                    'created_at' => now()->subDays(rand(1, 15))
                ]);
            }
        }

        $this->command->info('Board and cards seeder completed successfully!');
    }
}
