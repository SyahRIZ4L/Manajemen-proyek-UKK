<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TeamLeadTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('card_assignments')->truncate();
        DB::table('comments')->truncate();
        DB::table('time_logs')->truncate();
        DB::table('subtasks')->truncate();
        DB::table('cards')->truncate();
        DB::table('boards')->truncate();
        DB::table('members')->truncate();
        DB::table('projects')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert Users with new role system
        $users = [
            [
                'user_id' => 1,
                'username' => 'admin',
                'full_name' => 'Admin System',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'Project_Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'username' => 'teamlead',
                'full_name' => 'John Team Lead',
                'email' => 'teamlead@example.com',
                'password' => Hash::make('password'),
                'role' => 'Team_Lead',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'username' => 'sarah',
                'full_name' => 'Sarah Developer',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'role' => 'Developer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'username' => 'mike',
                'full_name' => 'Mike Designer',
                'email' => 'mike@example.com',
                'password' => Hash::make('password'),
                'role' => 'Designer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'username' => 'lisa',
                'full_name' => 'Lisa Member',
                'email' => 'lisa@example.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'username' => 'david',
                'full_name' => 'David Developer',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
                'role' => 'Developer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

        // Insert Projects
        $projects = [
            [
                'project_id' => 1,
                'project_name' => 'E-Commerce Website',
                'description' => 'Full-stack e-commerce platform with payment integration',
                'status' => 'In Progress',
                'deadline' => Carbon::now()->addDays(60),
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => 2,
                'project_name' => 'Mobile App Development',
                'description' => 'Cross-platform mobile app for task management',
                'status' => 'In Progress',
                'deadline' => Carbon::now()->addDays(90),
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => 3,
                'project_name' => 'Data Analytics Dashboard',
                'description' => 'Real-time analytics dashboard for business intelligence',
                'status' => 'Planning',
                'deadline' => Carbon::now()->addDays(120),
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => 4,
                'project_name' => 'Legacy System Migration',
                'description' => 'Migrate old system to modern architecture',
                'status' => 'Completed',
                'deadline' => Carbon::now()->subDays(10),
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('projects')->insert($projects);

        // Insert Members (Project assignments)
        $members = [
            // Project 1 - E-Commerce (Team Lead: John)
            ['member_id' => 1, 'project_id' => 1, 'user_id' => 2, 'role' => 'Team_Lead', 'joined_at' => now()],
            ['member_id' => 2, 'project_id' => 1, 'user_id' => 3, 'role' => 'Developer', 'joined_at' => now()],
            ['member_id' => 3, 'project_id' => 1, 'user_id' => 4, 'role' => 'Designer', 'joined_at' => now()],
            ['member_id' => 4, 'project_id' => 1, 'user_id' => 5, 'role' => 'Member', 'joined_at' => now()],

            // Project 2 - Mobile App (Team Lead: John)
            ['member_id' => 5, 'project_id' => 2, 'user_id' => 2, 'role' => 'Team_Lead', 'joined_at' => now()],
            ['member_id' => 6, 'project_id' => 2, 'user_id' => 6, 'role' => 'Developer', 'joined_at' => now()],
            ['member_id' => 7, 'project_id' => 2, 'user_id' => 4, 'role' => 'Designer', 'joined_at' => now()],

            // Project 3 - Analytics (No Team Lead yet - Admin will assign)
            ['member_id' => 8, 'project_id' => 3, 'user_id' => 3, 'role' => 'Developer', 'joined_at' => now()],
            ['member_id' => 9, 'project_id' => 3, 'user_id' => 6, 'role' => 'Developer', 'joined_at' => now()],

            // Project 4 - Legacy (Completed - Team Lead: John)
            ['member_id' => 10, 'project_id' => 4, 'user_id' => 2, 'role' => 'Team_Lead', 'joined_at' => now()],
            ['member_id' => 11, 'project_id' => 4, 'user_id' => 3, 'role' => 'Developer', 'joined_at' => now()],
        ];

        DB::table('members')->insert($members);

        // Insert Boards
        $boards = [
            // Project 1 Boards
            ['board_id' => 1, 'project_id' => 1, 'board_name' => 'Frontend Development', 'description' => 'Frontend components and UI', 'position' => 1, 'created_at' => now()],
            ['board_id' => 2, 'project_id' => 1, 'board_name' => 'Backend API', 'description' => 'Server-side development', 'position' => 2, 'created_at' => now()],
            ['board_id' => 3, 'project_id' => 1, 'board_name' => 'UI/UX Design', 'description' => 'Design and user experience', 'position' => 3, 'created_at' => now()],

            // Project 2 Boards
            ['board_id' => 4, 'project_id' => 2, 'board_name' => 'Mobile UI', 'description' => 'Mobile interface design', 'position' => 1, 'created_at' => now()],
            ['board_id' => 5, 'project_id' => 2, 'board_name' => 'App Logic', 'description' => 'Application functionality', 'position' => 2, 'created_at' => now()],

            // Project 3 Boards
            ['board_id' => 6, 'project_id' => 3, 'board_name' => 'Data Processing', 'description' => 'Data analysis and processing', 'position' => 1, 'created_at' => now()],

            // Project 4 Boards
            ['board_id' => 7, 'project_id' => 4, 'board_name' => 'Migration Tasks', 'description' => 'System migration activities', 'position' => 1, 'created_at' => now()],
        ];

        DB::table('boards')->insert($boards);

        // Insert Cards (Tasks)
        $cards = [
            // Project 1 - E-Commerce Cards
            ['card_id' => 1, 'board_id' => 1, 'card_title' => 'Setup React Components', 'description' => 'Create base components for product catalog', 'position' => 1, 'status' => 'done', 'priority' => 'high', 'due_date' => Carbon::now()->addDays(5), 'created_by' => 2],
            ['card_id' => 2, 'board_id' => 1, 'card_title' => 'Implement Shopping Cart', 'description' => 'Add cart functionality with state management', 'position' => 2, 'status' => 'in_progress', 'priority' => 'high', 'due_date' => Carbon::now()->addDays(10), 'created_by' => 2],
            ['card_id' => 3, 'board_id' => 1, 'card_title' => 'Product Search Feature', 'description' => 'Implement search with filters', 'position' => 3, 'status' => 'todo', 'priority' => 'medium', 'due_date' => Carbon::now()->addDays(15), 'created_by' => 2],

            ['card_id' => 4, 'board_id' => 2, 'card_title' => 'User Authentication API', 'description' => 'JWT-based auth system', 'position' => 1, 'status' => 'done', 'priority' => 'high', 'due_date' => Carbon::now()->subDays(2), 'created_by' => 2],
            ['card_id' => 5, 'board_id' => 2, 'card_title' => 'Payment Gateway Integration', 'description' => 'Integrate Stripe/PayPal', 'position' => 2, 'status' => 'in_progress', 'priority' => 'high', 'due_date' => Carbon::now()->addDays(8), 'created_by' => 2],
            ['card_id' => 6, 'board_id' => 2, 'card_title' => 'Order Management System', 'description' => 'CRUD for orders and tracking', 'position' => 3, 'status' => 'todo', 'priority' => 'medium', 'due_date' => Carbon::now()->addDays(20), 'created_by' => 2],

            ['card_id' => 7, 'board_id' => 3, 'card_title' => 'Design System', 'description' => 'Create consistent design components', 'position' => 1, 'status' => 'done', 'priority' => 'high', 'due_date' => Carbon::now()->subDays(5), 'created_by' => 2],
            ['card_id' => 8, 'board_id' => 3, 'card_title' => 'Responsive Layout', 'description' => 'Mobile-first responsive design', 'position' => 2, 'status' => 'in_progress', 'priority' => 'medium', 'due_date' => Carbon::now()->addDays(12), 'created_by' => 2],

            // Project 2 - Mobile App Cards
            ['card_id' => 9, 'board_id' => 4, 'card_title' => 'Login Screen Design', 'description' => 'Design mobile login interface', 'position' => 1, 'status' => 'done', 'priority' => 'high', 'due_date' => Carbon::now()->subDays(1), 'created_by' => 2],
            ['card_id' => 10, 'board_id' => 4, 'card_title' => 'Dashboard Layout', 'description' => 'Main dashboard for task management', 'position' => 2, 'status' => 'in_progress', 'priority' => 'high', 'due_date' => Carbon::now()->addDays(7), 'created_by' => 2],
            ['card_id' => 11, 'board_id' => 4, 'card_title' => 'Task List View', 'description' => 'List and grid view for tasks', 'position' => 3, 'status' => 'todo', 'priority' => 'medium', 'due_date' => Carbon::now()->addDays(14), 'created_by' => 2],

            ['card_id' => 12, 'board_id' => 5, 'card_title' => 'State Management', 'description' => 'Redux/Context API setup', 'position' => 1, 'status' => 'in_progress', 'priority' => 'high', 'due_date' => Carbon::now()->addDays(9), 'created_by' => 2],
            ['card_id' => 13, 'board_id' => 5, 'card_title' => 'Push Notifications', 'description' => 'Firebase notification system', 'position' => 2, 'status' => 'todo', 'priority' => 'low', 'due_date' => Carbon::now()->addDays(30), 'created_by' => 2],

            // Project 4 - Legacy (Completed)
            ['card_id' => 14, 'board_id' => 7, 'card_title' => 'Database Migration', 'description' => 'Migrate old DB to new schema', 'position' => 1, 'status' => 'done', 'priority' => 'high', 'due_date' => Carbon::now()->subDays(20), 'created_by' => 2],
            ['card_id' => 15, 'board_id' => 7, 'card_title' => 'API Refactoring', 'description' => 'Update API endpoints', 'position' => 2, 'status' => 'done', 'priority' => 'medium', 'due_date' => Carbon::now()->subDays(15), 'created_by' => 2],

            // Some overdue tasks
            ['card_id' => 16, 'board_id' => 1, 'card_title' => 'Performance Optimization', 'description' => 'Optimize loading speed', 'position' => 4, 'status' => 'todo', 'priority' => 'medium', 'due_date' => Carbon::now()->subDays(3), 'created_by' => 2],
            ['card_id' => 17, 'board_id' => 2, 'card_title' => 'Security Audit', 'description' => 'Check for vulnerabilities', 'position' => 4, 'status' => 'in_progress', 'priority' => 'high', 'due_date' => Carbon::now()->subDays(1), 'created_by' => 2],
        ];

        foreach ($cards as $card) {
            $card['created_at'] = now();
            $card['updated_at'] = now();
        }

        DB::table('cards')->insert($cards);

        // Insert Card Assignments
        $assignments = [
            // E-Commerce assignments
            ['assignment_id' => 1, 'card_id' => 1, 'user_id' => 3, 'assignment_status' => 'completed', 'assigned_at' => now(), 'started_at' => Carbon::now()->subDays(2), 'completed_at' => Carbon::now()->subDays(1)], // Sarah
            ['assignment_id' => 2, 'card_id' => 2, 'user_id' => 3, 'assignment_status' => 'in_progress', 'assigned_at' => now(), 'started_at' => Carbon::now()->subDays(2), 'completed_at' => null], // Sarah
            ['assignment_id' => 3, 'card_id' => 3, 'user_id' => 6, 'assignment_status' => 'assigned', 'assigned_at' => now(), 'started_at' => null, 'completed_at' => null], // David
            ['assignment_id' => 4, 'card_id' => 4, 'user_id' => 3, 'assignment_status' => 'completed', 'assigned_at' => now(), 'started_at' => Carbon::now()->subDays(4), 'completed_at' => Carbon::now()->subDays(3)], // Sarah
            ['assignment_id' => 5, 'card_id' => 5, 'user_id' => 3, 'assignment_status' => 'in_progress', 'assigned_at' => now(), 'started_at' => Carbon::now()->subDays(1), 'completed_at' => null], // Sarah
            ['assignment_id' => 6, 'card_id' => 6, 'user_id' => 6, 'assignment_status' => 'assigned', 'assigned_at' => now(), 'started_at' => null, 'completed_at' => null], // David
            ['assignment_id' => 7, 'card_id' => 7, 'user_id' => 4, 'assignment_status' => 'completed', 'assigned_at' => now(), 'started_at' => Carbon::now()->subDays(6), 'completed_at' => Carbon::now()->subDays(5)], // Mike
            ['assignment_id' => 8, 'card_id' => 8, 'user_id' => 4, 'assignment_status' => 'in_progress', 'assigned_at' => now(), 'started_at' => now(), 'completed_at' => null], // Mike
            ['assignment_id' => 9, 'card_id' => 16, 'user_id' => 5, 'assignment_status' => 'assigned', 'assigned_at' => now(), 'started_at' => null, 'completed_at' => null], // Lisa
            ['assignment_id' => 10, 'card_id' => 17, 'user_id' => 3, 'assignment_status' => 'in_progress', 'assigned_at' => now(), 'started_at' => Carbon::now()->subDays(1), 'completed_at' => null], // Sarah

            // Mobile App assignments
            ['assignment_id' => 11, 'card_id' => 9, 'user_id' => 4, 'assignment_status' => 'completed', 'assigned_at' => now(), 'started_at' => Carbon::now()->subDays(2), 'completed_at' => Carbon::now()->subDays(1)], // Mike
            ['assignment_id' => 12, 'card_id' => 10, 'user_id' => 4, 'assignment_status' => 'in_progress', 'assigned_at' => now(), 'started_at' => now(), 'completed_at' => null], // Mike
            ['assignment_id' => 13, 'card_id' => 11, 'user_id' => 4, 'assignment_status' => 'assigned', 'assigned_at' => now(), 'started_at' => null, 'completed_at' => null], // Mike
            ['assignment_id' => 14, 'card_id' => 12, 'user_id' => 6, 'assignment_status' => 'in_progress', 'assigned_at' => now(), 'started_at' => Carbon::now()->subDays(2), 'completed_at' => null], // David
            ['assignment_id' => 15, 'card_id' => 13, 'user_id' => 6, 'assignment_status' => 'assigned', 'assigned_at' => now(), 'started_at' => null, 'completed_at' => null], // David
        ];

        DB::table('card_assignments')->insert($assignments);

        // Insert some comments
        $comments = [
            ['comment_id' => 1, 'card_id' => 2, 'subtask_id' => null, 'user_id' => 2, 'comment_text' => 'Great progress on the shopping cart! Keep it up.', 'comment_type' => 'card', 'created_at' => now()],
            ['comment_id' => 2, 'card_id' => 5, 'subtask_id' => null, 'user_id' => 3, 'comment_text' => 'Payment integration is 70% complete. Testing with sandbox.', 'comment_type' => 'card', 'created_at' => now()],
            ['comment_id' => 3, 'card_id' => 10, 'subtask_id' => null, 'user_id' => 4, 'comment_text' => 'Dashboard mockups are ready for review.', 'comment_type' => 'card', 'created_at' => now()],
            ['comment_id' => 4, 'card_id' => 17, 'subtask_id' => null, 'user_id' => 2, 'comment_text' => 'Security audit found 2 minor issues. Please address ASAP.', 'comment_type' => 'card', 'created_at' => now()],
        ];

        DB::table('comments')->insert($comments);

        // Insert time logs
        $timeLogs = [
            ['log_id' => 1, 'card_id' => 1, 'subtask_id' => null, 'user_id' => 3, 'start_time' => Carbon::now()->subDays(2)->setHour(9), 'end_time' => Carbon::now()->subDays(2)->setHour(17)->setMinute(30), 'duration_minutes' => 510, 'description' => 'Component setup and configuration'],
            ['log_id' => 2, 'card_id' => 2, 'subtask_id' => null, 'user_id' => 3, 'start_time' => Carbon::now()->subDays(1)->setHour(10), 'end_time' => Carbon::now()->subDays(1)->setHour(16), 'duration_minutes' => 360, 'description' => 'Shopping cart state management'],
            ['log_id' => 3, 'card_id' => 4, 'subtask_id' => null, 'user_id' => 3, 'start_time' => Carbon::now()->subDays(3)->setHour(8), 'end_time' => Carbon::now()->subDays(3)->setHour(20), 'duration_minutes' => 720, 'description' => 'JWT authentication implementation'],
            ['log_id' => 4, 'card_id' => 5, 'subtask_id' => null, 'user_id' => 3, 'start_time' => Carbon::now()->setHour(13), 'end_time' => Carbon::now()->setHour(17)->setMinute(30), 'duration_minutes' => 270, 'description' => 'Payment gateway API integration'],
            ['log_id' => 5, 'card_id' => 7, 'subtask_id' => null, 'user_id' => 4, 'start_time' => Carbon::now()->subDays(4)->setHour(8), 'end_time' => Carbon::now()->subDays(3)->setHour(24), 'duration_minutes' => 960, 'description' => 'Complete design system creation'],
            ['log_id' => 6, 'card_id' => 9, 'subtask_id' => null, 'user_id' => 4, 'start_time' => Carbon::now()->subDays(1)->setHour(14), 'end_time' => Carbon::now()->subDays(1)->setHour(17)->setMinute(30), 'duration_minutes' => 210, 'description' => 'Mobile login screen design'],
            ['log_id' => 7, 'card_id' => 12, 'subtask_id' => null, 'user_id' => 6, 'start_time' => Carbon::now()->setHour(9), 'end_time' => Carbon::now()->setHour(14), 'duration_minutes' => 300, 'description' => 'Redux store setup'],
        ];

        DB::table('time_logs')->insert($timeLogs);

        $this->command->info('✅ Team Lead test data inserted successfully!');
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Team Lead: teamlead@example.com / password');
        $this->command->info('Developer: sarah@example.com / password');
        $this->command->info('');
        $this->command->info('📊 Data Summary:');
        $this->command->info('- 6 Users (1 Admin, 1 Team Lead, 2 Developers, 1 Designer, 1 Member)');
        $this->command->info('- 4 Projects (3 assigned to Team Lead, 1 completed)');
        $this->command->info('- 17 Tasks with various statuses');
        $this->command->info('- Task assignments and time tracking data');
        $this->command->info('');
        $this->command->info('🎯 Team Lead "John" manages:');
        $this->command->info('- E-Commerce Website (4 members, 10 tasks)');
        $this->command->info('- Mobile App Development (3 members, 5 tasks)');
        $this->command->info('- Legacy System Migration (completed)');
    }
}
