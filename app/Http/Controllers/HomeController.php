<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Display the homepage with dashboard statistics.
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's project statistics
        $stats = $this->getUserStatistics($user->id);

        // Get recent tasks
        $recentTasks = $this->getRecentTasks($user->id);

        // Get notifications
        $notifications = $this->getNotifications($user->id);

        return view('home.index', compact('user', 'stats', 'recentTasks', 'notifications'));
    }

    /**
     * Get user statistics for dashboard.
     */
    private function getUserStatistics($userId)
    {
        // Mock data - will be replaced with actual database queries
        return [
            'total_projects' => 8,
            'completed_projects' => 5,
            'pending_tasks' => 12,
            'completed_tasks' => 45,
            'total_time_logged' => 156, // in hours
            'avg_completion_time' => 7.5, // in days
            'current_month_hours' => 42,
            'current_week_hours' => 18,
        ];
    }

    /**
     * Get recent tasks for the user.
     */
    private function getRecentTasks($userId)
    {
        // Mock data - will be replaced with actual database queries
        return [
            [
                'id' => 1,
                'title' => 'Develop user authentication system',
                'status' => 'In Progress',
                'project' => 'E-commerce Website',
                'due_date' => '2025-09-25',
                'priority' => 'High',
                'updated_at' => now()->subHours(2),
            ],
            [
                'id' => 2,
                'title' => 'Design database schema',
                'status' => 'Completed',
                'project' => 'CRM System',
                'due_date' => '2025-09-20',
                'priority' => 'Medium',
                'updated_at' => now()->subHours(5),
            ],
            [
                'id' => 3,
                'title' => 'Create API documentation',
                'status' => 'Review',
                'project' => 'Mobile App Backend',
                'due_date' => '2025-09-28',
                'priority' => 'Low',
                'updated_at' => now()->subDay(),
            ],
        ];
    }

    /**
     * Get notifications for the user.
     */
    private function getNotifications($userId)
    {
        // Mock data - will be replaced with actual database queries
        return [
            [
                'id' => 1,
                'type' => 'task_assigned',
                'title' => 'Task Baru Ditugaskan',
                'message' => 'Anda telah ditugaskan untuk task "Develop payment gateway"',
                'created_at' => now()->subMinutes(30),
                'read' => false,
            ],
            [
                'id' => 2,
                'type' => 'task_completed',
                'title' => 'Task Selesai',
                'message' => 'Task "Database optimization" telah diselesaikan',
                'created_at' => now()->subHours(2),
                'read' => false,
            ],
            [
                'id' => 3,
                'type' => 'project_update',
                'title' => 'Update Proyek',
                'message' => 'Proyek "E-commerce Website" mengalami perubahan deadline',
                'created_at' => now()->subHours(4),
                'read' => true,
            ],
        ];
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationRead($notificationId)
    {
        // Implementation will be added when notification model is ready
        return response()->json(['success' => true]);
    }

    /**
     * Get time tracking data for charts.
     */
    public function getTimeTrackingData()
    {
        // Mock data for time tracking chart
        $data = [
            'daily_hours' => [
                ['date' => '2025-09-15', 'hours' => 8],
                ['date' => '2025-09-16', 'hours' => 7.5],
                ['date' => '2025-09-17', 'hours' => 9],
                ['date' => '2025-09-18', 'hours' => 6],
                ['date' => '2025-09-19', 'hours' => 8.5],
                ['date' => '2025-09-20', 'hours' => 7],
                ['date' => '2025-09-21', 'hours' => 5],
            ],
            'project_distribution' => [
                ['project' => 'E-commerce Website', 'hours' => 25, 'percentage' => 40],
                ['project' => 'CRM System', 'hours' => 18, 'percentage' => 28],
                ['project' => 'Mobile App Backend', 'hours' => 12, 'percentage' => 19],
                ['project' => 'Admin Dashboard', 'hours' => 8, 'percentage' => 13],
            ]
        ];

        return response()->json($data);
    }
}
