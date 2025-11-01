<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // Ambil user pertama sebagai creator
        $admin = User::where('role', 'Project_Admin')->first();

        if (!$admin) {
            $admin = User::first();
        }

        if (!$admin) {
            $this->command->error('No users found. Please run UserManagementSeeder first.');
            return;
        }

        // Buat beberapa project dummy
        $projects = [
            [
                'project_name' => 'Website Redesign',
                'description' => 'Merancang ulang website perusahaan dengan desain modern dan responsif.',
                'created_by' => $admin->user_id,
                'deadline' => now()->addDays(30),
                'status' => 'In Progress'
            ],
            [
                'project_name' => 'Mobile App Development',
                'description' => 'Pengembangan aplikasi mobile untuk manajemen inventori.',
                'created_by' => $admin->user_id,
                'deadline' => now()->addDays(60),
                'status' => 'Planning'
            ],
            [
                'project_name' => 'Database Optimization',
                'description' => 'Optimasi performa database dan perbaikan query.',
                'created_by' => $admin->user_id,
                'deadline' => now()->addDays(14),
                'status' => 'Completed'
            ],
            [
                'project_name' => 'API Integration',
                'description' => 'Integrasi API pihak ketiga untuk sistem pembayaran.',
                'created_by' => $admin->user_id,
                'deadline' => now()->addDays(45),
                'status' => 'On Hold'
            ]
        ];

        foreach ($projects as $projectData) {
            // Gunakan DB untuk insert manual karena ada issue dengan timestamps
            DB::table('projects')->insert([
                'project_name' => $projectData['project_name'],
                'description' => $projectData['description'],
                'created_by' => $projectData['created_by'],
                'deadline' => $projectData['deadline'],
                'status' => $projectData['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->command->info('Project seeder completed successfully!');
    }
}
