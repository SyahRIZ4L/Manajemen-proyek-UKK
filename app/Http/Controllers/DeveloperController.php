<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckPermission;

class DeveloperController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'Developer') {
                return redirect()->route('home')->with('error', 'Akses ditolak. Anda bukan Developer.');
            }
            return $next($request);
        });
    }

    /**
     * Developer Panel - Modern interface
     */
    public function panel()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'view_assigned_tasks')) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki permission untuk melihat panel developer.');
        }

        return view('developer.panel');
    }

    /**
     * Developer Dashboard
     */
    public function dashboard()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'view_assigned_tasks')) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki permission untuk melihat dashboard developer.');
        }

        $user = Auth::user();
        $userRole = 'Developer';

        // Get assigned tasks
        $assignedTasks = $this->getAssignedTasks($user);

        // Get task statistics
        $taskStats = $this->getTaskStats($user);

        // Get recent code commits (mock data)
        $recentCommits = $this->getRecentCommits($user);

        // Get bug reports assigned to user
        $bugReports = $this->getAssignedBugs($user);

        return view('developer.dashboard', compact(
            'user',
            'userRole',
            'assignedTasks',
            'taskStats',
            'recentCommits',
            'bugReports'
        ));
    }

    /**
     * My Tasks page
     */
    public function myTasks()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'view_assigned_tasks')) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $tasks = $this->getAssignedTasks($user);

        return view('developer.tasks', compact('tasks'));
    }

    /**
     * Update own task status
     */
    public function updateTaskStatus(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'update_own_task_status')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $validated = $request->validate([
            'task_id' => 'required|exists:cards,id',
            'status' => 'required|in:pending,in_progress,review,completed'
        ]);

        try {
            $user = Auth::user();

            // Verify task is assigned to user
            $task = DB::table('cards')->where('id', $validated['task_id'])->first();
            if ($task->assigned_to != $user->user_id) {
                return response()->json(['success' => false, 'message' => 'Task ini tidak ditugaskan kepada Anda']);
            }

            DB::table('cards')->where('id', $validated['task_id'])->update([
                'status' => $validated['status'],
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Status task berhasil diupdate']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Add comment to task
     */
    public function addTaskComment(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'add_task_comments')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $validated = $request->validate([
            'task_id' => 'required|exists:cards,id',
            'comment' => 'required|string|max:1000'
        ]);

        try {
            $user = Auth::user();

            DB::table('comments')->insert([
                'card_id' => $validated['task_id'],
                'user_id' => $user->user_id,
                'content' => $validated['comment'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Komentar berhasil ditambahkan']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Log work time
     */
    public function logWorkTime(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'log_work_time')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $validated = $request->validate([
            'task_id' => 'required|exists:cards,id',
            'hours' => 'required|numeric|min:0.5|max:24',
            'description' => 'required|string|max:255',
            'work_date' => 'required|date'
        ]);

        try {
            $user = Auth::user();

            // Verify task is assigned to user
            $task = DB::table('cards')->where('id', $validated['task_id'])->first();
            if ($task->assigned_to != $user->user_id) {
                return response()->json(['success' => false, 'message' => 'Task ini tidak ditugaskan kepada Anda']);
            }

            DB::table('time_logs')->insert([
                'user_id' => $user->user_id,
                'card_id' => $validated['task_id'],
                'hours' => $validated['hours'],
                'description' => $validated['description'],
                'logged_date' => $validated['work_date'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Waktu kerja berhasil dicatat']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Bug reporting page
     */
    public function bugReports()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'report_bugs')) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $reportedBugs = $this->getReportedBugs($user);
        $assignedBugs = $this->getAssignedBugs($user);

        return view('developer.bugs', compact('reportedBugs', 'assignedBugs'));
    }

    /**
     * Create bug report
     */
    public function createBugReport(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'report_bugs')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'steps_to_reproduce' => 'required|string'
        ]);

        try {
            $user = Auth::user();

            DB::table('cards')->insert([
                'board_id' => 1, // Bug tracking board
                'title' => '[BUG] ' . $validated['title'],
                'description' => $validated['description'] . "\n\nSteps to reproduce:\n" . $validated['steps_to_reproduce'],
                'priority' => $validated['severity'],
                'status' => 'pending',
                'assigned_to' => $user->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'Bug report berhasil dibuat');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper Methods
     */

    /**
     * API Methods for Panel
     */
    public function getStatistics()
    {
        try {
            $user = Auth::user();
            $taskStats = $this->getTaskStats($user);

            $timeLogs = DB::table('time_logs')
                ->where('user_id', $user->user_id)
                ->whereBetween('logged_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('hours');

            $stats = [
                'assigned_tasks' => $taskStats['total'],
                'active_tasks' => $taskStats['in_progress'],
                'completed_tasks' => $taskStats['completed'],
                'hours_logged' => $timeLogs ?: 32 // fallback for demo
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get developer's assigned tasks for API
     */
    public function getTasks(Request $request)
    {
        try {
            $user = Auth::user();
            $status = $request->get('status');

            $query = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->where('cards.assigned_to', $user->user_id)
                ->select('cards.*', 'projects.project_name', 'boards.board_name');

            if ($status) {
                $query->where('cards.status', $status);
            }

            $tasks = $query->get()->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'project' => $task->project_name,
                    'priority' => ucfirst($task->priority),
                    'status' => ucfirst(str_replace('_', ' ', $task->status)),
                    'due_date' => $task->due_date,
                    'progress' => $this->calculateTaskProgress($task)
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $tasks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving tasks: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get developer's projects for API
     */
    public function getProjects()
    {
        try {
            $user = Auth::user();

            $projects = DB::table('projects')
                ->join('project_members', 'projects.project_id', '=', 'project_members.project_id')
                ->where('project_members.user_id', $user->user_id)
                ->select('projects.*')
                ->distinct()
                ->get()
                ->map(function($project) {
                    $memberCount = DB::table('project_members')
                        ->where('project_id', $project->project_id)
                        ->count();

                    return [
                        'id' => $project->project_id,
                        'name' => $project->project_name,
                        'description' => $project->description,
                        'deadline' => $project->deadline,
                        'members' => $memberCount,
                        'status' => ucfirst($project->status),
                        'progress' => $this->calculateProjectProgress($project->project_id)
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving projects: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log time for API
     */
    public function logTime(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'log_work_time')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $request->validate([
            'task_id' => 'required|integer',
            'hours' => 'required|numeric|min:0.5|max:12',
            'description' => 'required|string|max:500',
            'date' => 'nullable|date'
        ]);

        try {
            $user = Auth::user();

            DB::table('time_logs')->insert([
                'user_id' => $user->user_id,
                'card_id' => $request->task_id,
                'hours' => $request->hours,
                'description' => $request->description,
                'logged_date' => $request->date ?? now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Time logged successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error logging time: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get developer's recent activities for API
     */
    public function getRecentActivities()
    {
        try {
            $user = Auth::user();

            $activities = collect();

            // Recent time logs
            $timeLogs = DB::table('time_logs')
                ->where('user_id', $user->user_id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($timeLogs as $log) {
                $activities->push([
                    'type' => 'time',
                    'title' => 'Time logged',
                    'description' => $log->hours . ' hours - ' . $log->description,
                    'time' => $this->timeAgo($log->created_at)
                ]);
            }

            // Recent task updates
            $taskUpdates = DB::table('cards')
                ->where('assigned_to', $user->user_id)
                ->where('updated_at', '>', now()->subDays(7))
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($taskUpdates as $task) {
                $activities->push([
                    'type' => 'task',
                    'title' => 'Task updated',
                    'description' => $task->title . ' - ' . ucfirst($task->status),
                    'time' => $this->timeAgo($task->updated_at)
                ]);
            }

            // Sort by time and limit
            $activities = $activities->sortByDesc('time')->take(10)->values();

            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving activities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get time logs for developer API
     */
    public function getTimeLogs(Request $request)
    {
        try {
            $user = Auth::user();
            $startDate = $request->get('start_date', now()->startOfWeek()->toDateString());
            $endDate = $request->get('end_date', now()->endOfWeek()->toDateString());

            $timeLogs = DB::table('time_logs')
                ->join('cards', 'time_logs.card_id', '=', 'cards.id')
                ->where('time_logs.user_id', $user->user_id)
                ->whereBetween('logged_date', [$startDate, $endDate])
                ->select('time_logs.*', 'cards.title as task_title')
                ->orderBy('logged_date', 'desc')
                ->get()
                ->map(function($log) {
                    return [
                        'date' => $log->logged_date,
                        'task' => $log->task_title,
                        'hours' => $log->hours,
                        'description' => $log->description
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $timeLogs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving time logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper Methods
     */

    /**
     * Get tasks assigned to developer
     */
    private function getAssignedTasks($user)
    {
        return DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->where('cards.assigned_to', $user->user_id)
            ->select('cards.*', 'projects.project_name', 'boards.board_name')
            ->orderBy('cards.priority', 'desc')
            ->orderBy('cards.due_date', 'asc')
            ->get();
    }

    /**
     * Get task statistics for developer
     */
    private function getTaskStats($user)
    {
        $tasks = DB::table('cards')->where('assigned_to', $user->user_id);

        return [
            'total' => $tasks->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
            'overdue' => $tasks->where('due_date', '<', now())->where('status', '!=', 'completed')->count()
        ];
    }

    /**
     * Get recent commits (mock data)
     */
    private function getRecentCommits($user)
    {
        return [
            [
                'hash' => 'abc123',
                'message' => 'Fix authentication bug',
                'date' => now()->subHours(2),
                'files_changed' => 3
            ],
            [
                'hash' => 'def456',
                'message' => 'Implement user profile update',
                'date' => now()->subHours(5),
                'files_changed' => 5
            ],
            [
                'hash' => 'ghi789',
                'message' => 'Add validation to forms',
                'date' => now()->subDay(),
                'files_changed' => 7
            ]
        ];
    }

    /**
     * Get bugs assigned to developer
     */
    private function getAssignedBugs($user)
    {
        return DB::table('cards')
            ->where('assigned_to', $user->user_id)
            ->where('title', 'LIKE', '[BUG]%')
            ->orderBy('priority', 'desc')
            ->get();
    }

    /**
     * Get bugs reported by developer
     */
    private function getReportedBugs($user)
    {
        return DB::table('cards')
            ->where('assigned_by', $user->user_id)
            ->where('title', 'LIKE', '[BUG]%')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Calculate task progress based on status
     */
    private function calculateTaskProgress($task)
    {
        switch ($task->status) {
            case 'pending':
                return 0;
            case 'in_progress':
                return 60;
            case 'review':
                return 90;
            case 'completed':
                return 100;
            default:
                return 0;
        }
    }

    /**
     * Calculate project progress
     */
    private function calculateProjectProgress($projectId)
    {
        $totalTasks = DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->where('boards.project_id', $projectId)
            ->count();

        if ($totalTasks == 0) return 0;

        $completedTasks = DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->where('boards.project_id', $projectId)
            ->where('cards.status', 'completed')
            ->count();

        return round(($completedTasks / $totalTasks) * 100);
    }

    /**
     * Convert timestamp to time ago format
     */
    private function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';
        if ($time < 31104000) return floor($time/2592000) . ' months ago';
        return floor($time/31104000) . ' years ago';
    }
}
