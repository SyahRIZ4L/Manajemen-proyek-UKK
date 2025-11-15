<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckPermission;
use App\Models\Card;

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
                ->join('members', 'projects.project_id', '=', 'members.project_id')
                ->where('members.user_id', $user->user_id)
                ->select('projects.*')
                ->distinct()
                ->get()
                ->map(function($project) {
                    $memberCount = DB::table('members')
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

    /**
     * Get cards assigned to developer - Optimized for slow networks
     */
    public function getCards(Request $request)
    {
        try {
            $user = Auth::user();
            $perPage = $request->input('per_page', 20); // Pagination untuk reduce payload
            $status = $request->input('status'); // Filter by status

            // Base query - optimized dengan minimal joins
            $query = DB::table('cards as c')
                ->leftJoin('card_assignments as ca', 'c.card_id', '=', 'ca.card_id')
                ->leftJoin('boards as b', 'c.board_id', '=', 'b.board_id')
                ->leftJoin('projects as p', 'b.project_id', '=', 'p.project_id')
                ->leftJoin('users as u', 'c.created_by', '=', 'u.user_id')
                ->where('ca.user_id', $user->user_id)
                ->select(
                    'c.card_id',
                    'c.card_title as title',
                    'c.description',
                    'c.status',
                    'c.priority',
                    'c.due_date',
                    'c.deadline',
                    'c.estimated_hours',
                    'c.actual_hours',
                    'c.is_timer_active',
                    'c.timer_started_at',
                    'c.started_at',
                    'c.created_at',
                    'b.board_name',
                    'p.project_name',
                    'u.username as assigned_by'
                )
                ->orderBy('c.created_at', 'desc');

            // Apply filter
            if ($status) {
                $query->where('c.status', $status);
            }

            // Paginate
            $paginatedCards = $query->paginate($perPage);
            $cards = $paginatedCards->items();

            // Paginate
            $paginatedCards = $query->paginate($perPage);
            $cards = $paginatedCards->items();

            // Format the response - Optimize payload size
            $formattedCards = array_map(function($card) {
                // Truncate description untuk save bandwidth
                $description = $card->description ?? '';
                if (strlen($description) > 150) {
                    $description = substr($description, 0, 150) . '...';
                }

                return [
                    'card_id' => $card->card_id,
                    'title' => $card->title,
                    'description' => $description,
                    'status' => $card->status,
                    'priority' => $card->priority,
                    'assigned_by' => $card->assigned_by ?? 'Unknown',
                    'board_name' => $card->board_name ?? '',
                    'project_name' => $card->project_name ?? '',
                    'created_at' => $card->created_at,
                    'due_date' => $card->due_date ?? $card->deadline,
                    'estimated_hours' => $card->estimated_hours,
                    'actual_hours' => $card->actual_hours,
                    'is_timer_active' => (bool)$card->is_timer_active,
                    'timer_started_at' => $card->timer_started_at,
                    'started_at' => $card->started_at,
                ];
            }, $cards);

            return response()->json([
                'success' => true,
                'cards' => $formattedCards,
                'data' => $formattedCards,
                'pagination' => [
                    'current_page' => $paginatedCards->currentPage(),
                    'total_pages' => $paginatedCards->lastPage(),
                    'total' => $paginatedCards->total(),
                    'per_page' => $paginatedCards->perPage()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching cards: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update card status (todo -> in_progress -> review)
     */
    public function updateCardStatus(Request $request, $cardId)
    {
        try {
            $user = Auth::user();
            $status = $request->input('status');

            // Validasi status yang diizinkan untuk developer
            $allowedStatuses = ['todo', 'in_progress', 'review'];
            if (!in_array($status, $allowedStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status. Allowed statuses: todo, in_progress, review'
                ], 400);
            }

            // Update card status in database - auto timer will be handled by CardObserver
            $card = Card::where('card_id', $cardId)->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found'
                ], 404);
            }

            // Update status - this will trigger CardObserver for auto timer
            $card->update(['status' => $status]);

            // Create notification for TeamLead if status is 'review'
            if ($status === 'review') {
                $this->createReviewNotification($cardId, $user);
            }

            return response()->json([
                'success' => true,
                'message' => 'Card status updated successfully',
                'data' => [
                    'card_id' => $cardId,
                    'status' => $status,
                    'updated_by' => $user->username
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating card status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit card to TeamLead for review
     */
    public function submitCardToTeamLead(Request $request, $cardId)
    {
        try {
            $user = Auth::user();
            $comment = $request->input('comment', '');

            // Find the card first
            $card = Card::where('card_id', $cardId)->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found'
                ], 404);
            }

            // Check if card is assigned to current user
            $assignment = DB::table('card_assignments')
                ->where('card_id', $cardId)
                ->where('user_id', $user->user_id)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to this card'
                ], 403);
            }

            // Update card status to 'review' - this will trigger CardObserver
            $card->update(['status' => 'review']);

            // Create notification for TeamLead
            $this->createReviewNotification($cardId, $user, $comment);

            return response()->json([
                'success' => true,
                'message' => 'Card submitted for review successfully',
                'data' => [
                    'card_id' => $cardId,
                    'status' => 'review',
                    'submitted_by' => $user->username,
                    'comment' => $comment
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting card: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create notification for TeamLead when card is submitted for review
     */
    private function createReviewNotification($cardId, $user, $comment = '')
    {
        try {
            // Get the card and project info
            $card = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->where('cards.card_id', $cardId)
                ->select('cards.*', 'projects.project_id', 'projects.project_name')
                ->first();

            if (!$card) {
                Log::warning("Card not found for notification: $cardId");
                return;
            }

            // Find TeamLead for this project
            $teamLead = DB::table('project_members')
                ->join('users', 'project_members.user_id', '=', 'users.user_id')
                ->where('project_members.project_id', $card->project_id)
                ->where('project_members.role', 'Team_Lead')
                ->select('users.*')
                ->first();

            if ($teamLead) {
                // Create notification in database
                DB::table('notifications')->insert([
                    'user_id' => $teamLead->user_id,
                    'type' => 'card_review',
                    'title' => 'Card Ready for Review',
                    'message' => "{$user->full_name} has submitted '{$card->card_title}' for review",
                    'data' => json_encode([
                        'card_id' => $cardId,
                        'card_title' => $card->card_title,
                        'project_name' => $card->project_name,
                        'submitted_by' => $user->full_name,
                        'comment' => $comment
                    ]),
                    'is_read' => false,
                    'created_at' => now()
                ]);

                Log::info("Review notification created for TeamLead {$teamLead->username} - Card: $cardId");
            } else {
                Log::warning("No TeamLead found for project {$card->project_id}");
            }

        } catch (\Exception $e) {
            Log::error("Error creating review notification: " . $e->getMessage());
        }
    }

    /**
     * Get cards specifically assigned to the developer (for My Cards section)
     */
    public function getMyCards()
    {
        try {
            $user = Auth::user();

            // Get cards assigned to this user using CardAssignment table
            $cards = DB::table('cards as c')
                ->join('card_assignments as ca', 'c.card_id', '=', 'ca.card_id')
                ->leftJoin('boards as b', 'c.board_id', '=', 'b.board_id')
                ->leftJoin('projects as p', 'b.project_id', '=', 'p.project_id')
                ->leftJoin('users as u', 'c.created_by', '=', 'u.user_id')
                ->where('ca.user_id', $user->user_id)
                ->select(
                    'c.card_id',
                    'c.card_title',
                    'c.description',
                    'c.status',
                    'c.priority',
                    'c.due_date',
                    'c.deadline',
                    'c.estimated_hours',
                    'c.actual_hours',
                    'c.created_at',
                    'b.board_name',
                    'p.project_name',
                    'u.username as assigned_by'
                )
                ->orderBy('c.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'cards' => $cards
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load cards: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics for developer
     */
    public function getDashboardStats()
    {
        try {
            $user = Auth::user();

            // Get all cards assigned to this user
            $totalCards = DB::table('cards')
                ->join('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
                ->where('card_assignments.user_id', $user->user_id)
                ->count();

            // Count pending cards (todo and in_progress)
            $pendingCards = DB::table('cards')
                ->join('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
                ->where('card_assignments.user_id', $user->user_id)
                ->whereIn('cards.status', ['todo', 'in_progress'])
                ->count();

            // Count completed cards
            $completedCards = DB::table('cards')
                ->join('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
                ->where('card_assignments.user_id', $user->user_id)
                ->where('cards.status', 'done')
                ->count();

            return response()->json([
                'success' => true,
                'stats' => [
                    'total' => $totalCards,
                    'pending' => $pendingCards,
                    'completed' => $completedCards
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard stats: ' . $e->getMessage()
            ], 500);
        }
    }
}
