<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectMemberSeeder extends Seeder
{
    public function run()
    {
        // Get all projects and users
        $projects = Project::all();
        $users = User::all();

        if ($projects->isEmpty() || $users->isEmpty()) {
            $this->command->error('No projects or users found. Please run ProjectSeeder and UserManagementSeeder first.');
            return;
        }

        // Create project members for each project
        foreach ($projects as $project) {
            // Add 2-4 random members to each project
            $memberCount = rand(2, 4);
            $selectedUsers = $users->random($memberCount);

            foreach ($selectedUsers as $user) {
                // Don't add the creator as a member again
                if ($user->user_id == $project->created_by) {
                    continue;
                }

                // Assign roles based on user's main role
                $projectRole = $this->getProjectRole($user->role);

                DB::table('members')->insert([
                    'project_id' => $project->project_id,
                    'user_id' => $user->user_id,
                    'role' => $projectRole,
                    'joined_at' => now()->subDays(rand(1, 30))
                ]);
            }
        }

        $this->command->info('Project members seeder completed successfully!');
    }

    private function getProjectRole($userRole)
    {
        switch ($userRole) {
            case 'Project_Admin':
                return 'admin';
            case 'Team_Lead':
                return 'admin';
            case 'Developer':
            case 'Designer':
            default:
                return 'member';
        }
    }
}
