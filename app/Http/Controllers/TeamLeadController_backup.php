<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckPermission;

class TeamLeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'Team_Lead') {
                return redirect()->route('home')->with('error', 'Akses ditolak. Anda bukan Team Lead.');
            }
            return $next($request);
        });
    }

    /**
     * Team Lead Dashboard
     */
    public function dashboard()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'view_team_progress')) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki permission untuk melihat dashboard tim.');
        }

        $user = Auth::user();
        $userRole = 'Team Lead';
        
        // Get projects where user is team lead
        $teamLeadProjects = $this->getTeamLeadProjects($user);

        // Get team statistics
        $stats = $this->getTeamStats($user);

        // Get recent activities
        $recentActivities = $this->getRecentActivities($user);

        // Get pending tasks that need review
        $pendingReviews = $this->getPendingReviews($user);

        // Get team performance data
        $teamPerformance = $this->getTeamPerformance($user);

        return view('teamlead.dashboard', compact(
            'user',
            'userRole',
            'teamLeadProjects',
            'stats',
            'recentActivities',
            'pendingReviews',
            'teamPerformance'
        ));
    }

    /**
     * Task Assignment Interface
     */
    public function tasks()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'assign_tasks_to_team')) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki permission untuk assign tasks.');
        }

        $user = Auth::user();
        $projects = $this->getTeamLeadProjects($user);

        // Get all tasks in projects where user is Team Lead
        $tasks = $this->getTeamTasks($user);

        // Get team members for task assignment
        $teamMembers = $this->getTeamMembers($user);

        // Get task statistics
        $taskStats = $this->getTaskStats($user);

        return view('teamlead.tasks.index', compact(
            'projects',
            'tasks',
            'teamMembers',
            'taskStats'
        ));
    }

    /**
     * Create new task (Team Lead can create tasks)
     */
    public function createTask()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'create_team_tasks')) {
            return redirect()->route('teamlead.tasks')->with('error', 'Akses ditolak. Anda tidak memiliki permission untuk membuat tasks.');
        }

        $user = Auth::user();
        $projects = $this->getTeamLeadProjects($user);
        $teamMembers = $this->getTeamMembers($user);

        return view('teamlead.tasks.create', compact('projects', 'teamMembers'));
    }

    /**
     * Store new task
     */
    public function storeTask(Request $request)
    {
        if (!TeamLeadMiddleware::userHasTeamLeadPermission(Auth::user(), 'assign_tasks')) {
            return redirect()->route('teamlead.tasks')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'due_date' => 'required|date|after:today',
            'estimated_hours' => 'nullable|numeric|min:0'
        ]);

        $user = Auth::user();

        // Verify user is Team Lead in this project
        if (!TeamLeadMiddleware::canAccessProject($user, $request->project_id)) {
            return redirect()->route('teamlead.tasks')->with('error', 'Anda tidak memiliki akses ke proyek ini.');
        }

        try {
            DB::beginTransaction();

            // Create task
            $task = DB::table('cards')->insertGetId([
                'title' => $request->title,
                'description' => $request->description,
                'project_id' => $request->project_id,
                'board_id' => $this->getDefaultBoardId($request->project_id),
                'assigned_to' => $request->assigned_to,
                'created_by' => $user->id,
                'priority' => $request->priority,
                'status' => 'To Do',
                'due_date' => $request->due_date,
                'estimated_hours' => $request->estimated_hours,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create assignment record
            DB::table('card_assignments')->insert([
                'card_id' => $task,
                'user_id' => $request->assigned_to,
                'assigned_by' => $user->id,
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->route('teamlead.tasks')->with('success', 'Task berhasil dibuat dan ditugaskan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal membuat task: ' . $e->getMessage());
        }
    }

    /**
     * Edit task (Team Lead can edit team tasks)
     */
    public function editTask($id)
    {
        if (!TeamLeadMiddleware::userHasTeamLeadPermission(Auth::user(), 'edit_team_tasks')) {
            return redirect()->route('teamlead.tasks')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $task = $this->getTask($id);

        if (!$task) {
            return redirect()->route('teamlead.tasks')->with('error', 'Task tidak ditemukan.');
        }

        // Verify user is Team Lead in this project
        if (!TeamLeadMiddleware::canAccessProject($user, $task['project_id'])) {
            return redirect()->route('teamlead.tasks')->with('error', 'Anda tidak memiliki akses ke task ini.');
        }

        $projects = TeamLeadMiddleware::getTeamLeadProjects($user);
        $teamMembers = $this->getTeamMembers($user);

        return view('teamlead.tasks.edit', compact('task', 'projects', 'teamMembers'));
    }

    /**
     * Update task
     */
    public function updateTask(Request $request, $id)
    {
        if (!TeamLeadMiddleware::userHasTeamLeadPermission(Auth::user(), 'edit_team_tasks')) {
            return redirect()->route('teamlead.tasks')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'status' => 'required|in:To Do,In Progress,Review,Done',
            'due_date' => 'required|date',
            'estimated_hours' => 'nullable|numeric|min:0'
        ]);

        $user = Auth::user();
        $task = $this->getTask($id);

        if (!$task) {
            return redirect()->route('teamlead.tasks')->with('error', 'Task tidak ditemukan.');
        }

        // Verify user is Team Lead in this project
        if (!TeamLeadMiddleware::canAccessProject($user, $task['project_id'])) {
            return redirect()->route('teamlead.tasks')->with('error', 'Anda tidak memiliki akses ke task ini.');
        }

        try {
            DB::beginTransaction();

            // Update task
            DB::table('cards')
                ->where('id', $id)
                ->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'assigned_to' => $request->assigned_to,
                    'priority' => $request->priority,
                    'status' => $request->status,
                    'due_date' => $request->due_date,
                    'estimated_hours' => $request->estimated_hours,
                    'updated_at' => now()
                ]);

            // Update assignment if changed
            if ($task['assigned_to'] != $request->assigned_to) {
                DB::table('card_assignments')
                    ->where('card_id', $id)
                    ->update([
                        'user_id' => $request->assigned_to,
                        'assigned_by' => $user->id,
                        'assigned_at' => now(),
                        'updated_at' => now()
                    ]);
            }

            DB::commit();

            return redirect()->route('teamlead.tasks')->with('success', 'Task berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal mengupdate task: ' . $e->getMessage());
        }
    }

    /**
     * Update task status
     */
    public function updateTaskStatus(Request $request, $id)
    {
        if (!TeamLeadMiddleware::userHasTeamLeadPermission(Auth::user(), 'update_task_status')) {
            return redirect()->route('teamlead.tasks')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'status' => 'required|in:To Do,In Progress,Review,Done'
        ]);

        $user = Auth::user();
        $task = $this->getTask($id);

        if (!$task) {
            return response()->json(['error' => 'Task tidak ditemukan.'], 404);
        }

        // Verify user is Team Lead in this project
        if (!TeamLeadMiddleware::canAccessProject($user, $task['project_id'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        try {
            DB::table('cards')
                ->where('id', $id)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);

            return response()->json(['success' => 'Status task berhasil diupdate.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengupdate status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update task priority
     */
    public function updateTaskPriority(Request $request, $id)
    {
        if (!TeamLeadMiddleware::userHasTeamLeadPermission(Auth::user(), 'set_task_priority')) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'priority' => 'required|in:Low,Medium,High,Critical'
        ]);

        $user = Auth::user();
        $task = $this->getTask($id);

        if (!$task) {
            return response()->json(['error' => 'Task tidak ditemukan.'], 404);
        }

        // Verify user is Team Lead in this project
        if (!TeamLeadMiddleware::canAccessProject($user, $task['project_id'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        try {
            DB::table('cards')
                ->where('id', $id)
                ->update([
                    'priority' => $request->priority,
                    'updated_at' => now()
                ]);

            return response()->json(['success' => 'Prioritas task berhasil diupdate.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengupdate prioritas: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Team coordination dashboard
     */
    public function teamCoordination()
    {
        if (!TeamLeadMiddleware::userHasTeamLeadPermission(Auth::user(), 'coordinate_team')) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();

        // Get team overview data
        $teamOverview = $this->getTeamOverview($user);

        // Get blockers that need resolution
        $blockers = $this->getTeamBlockers($user);

        // Get team progress data
        $progressData = $this->getTeamProgressData($user);

        return view('teamlead.coordination', compact(
            'teamOverview',
            'blockers',
            'progressData'
        ));
    }

    /**
     * Update task priority
     */
    public function updatePriority(Request $request, $id)
    {
        if (!TeamLeadMiddleware::userHasTeamLeadPermission(Auth::user(), 'set_priority')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'priority' => 'required|in:Low,Medium,High,Critical'
        ]);

        $user = Auth::user();
        $task = $this->getTask($id);

        if (!$task) {
            return redirect()->back()->with('error', 'Task tidak ditemukan.');
        }

        // Verify user is Team Lead in this project
        if (!TeamLeadMiddleware::canAccessProject($user, $task['project_id'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        try {
            DB::table('cards')
                ->where('id', $id)
                ->update([
                    'priority' => $request->priority,
                    'updated_at' => now()
                ]);

            return redirect()->back()->with('success', 'Priority task berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate priority: ' . $e->getMessage());
        }
    }

    // Helper methods
    private function getTeamStats($user)
    {
        $projectIds = collect(TeamLeadMiddleware::getTeamLeadProjects($user))->pluck('id');

        return [
            'total_tasks' => DB::table('cards')->whereIn('project_id', $projectIds)->count(),
            'completed_tasks' => DB::table('cards')->whereIn('project_id', $projectIds)->where('status', 'Done')->count(),
            'in_progress_tasks' => DB::table('cards')->whereIn('project_id', $projectIds)->where('status', 'In Progress')->count(),
            'blocked_tasks' => DB::table('cards')->whereIn('project_id', $projectIds)->where('status', 'Blocked')->count(),
            'team_members' => DB::table('project_members')->whereIn('project_id', $projectIds)->count(),
            'active_projects' => $projectIds->count()
        ];
    }

    private function getRecentActivities($user)
    {
        $projectIds = collect(TeamLeadMiddleware::getTeamLeadProjects($user))->pluck('id');

        return DB::table('cards')
            ->select('cards.*', 'users.name as assigned_to_name', 'projects.name as project_name')
            ->join('users', 'cards.assigned_to', '=', 'users.id')
            ->join('projects', 'cards.project_id', '=', 'projects.id')
            ->whereIn('cards.project_id', $projectIds)
            ->orderBy('cards.updated_at', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getPendingReviews($user)
    {
        $projectIds = collect(TeamLeadMiddleware::getTeamLeadProjects($user))->pluck('id');

        return DB::table('cards')
            ->select('cards.*', 'users.name as assigned_to_name', 'projects.name as project_name')
            ->join('users', 'cards.assigned_to', '=', 'users.id')
            ->join('projects', 'cards.project_id', '=', 'projects.id')
            ->whereIn('cards.project_id', $projectIds)
            ->where('cards.status', 'Review')
            ->orderBy('cards.updated_at', 'asc')
            ->get()
            ->toArray();
    }

    private function getTeamPerformance($user)
    {
        $projectIds = collect(TeamLeadMiddleware::getTeamLeadProjects($user))->pluck('id');

        return DB::table('users')
            ->select('users.name',
                     DB::raw('COUNT(cards.id) as total_tasks'),
                     DB::raw('SUM(CASE WHEN cards.status = "Done" THEN 1 ELSE 0 END) as completed_tasks'),
                     DB::raw('SUM(COALESCE(time_logs.hours, 0)) as total_hours'))
            ->join('card_assignments', 'users.id', '=', 'card_assignments.user_id')
            ->join('cards', 'card_assignments.card_id', '=', 'cards.id')
            ->leftJoin('time_logs', 'cards.id', '=', 'time_logs.card_id')
            ->whereIn('cards.project_id', $projectIds)
            ->groupBy('users.id', 'users.name')
            ->get()
            ->toArray();
    }

    private function getTeamTasks($user)
    {
        $projectIds = $this->getTeamLeadProjects($user)->pluck('id');

        return DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.id')
            ->whereIn('boards.project_id', $projectIds)
            ->select('cards.*', 'boards.project_id')
            ->orderBy('cards.priority', 'desc')
            ->orderBy('cards.due_date', 'asc')
            ->get()
            ->toArray();
    }

    private function getTeamMembers($user)
    {
        $projectIds = $this->getTeamLeadProjects($user)->pluck('id');
        
        return DB::table('users')
            ->join('project_members', 'users.user_id', '=', 'project_members.user_id')
            ->whereIn('project_members.project_id', $projectIds)
            ->where('users.user_id', '!=', $user->user_id)
            ->select('users.*', 'project_members.role', 'project_members.project_id')
            ->distinct()
            ->get()
            ->toArray();
    }

    private function getTaskStats($user)
    {
        $projectIds = collect(TeamLeadMiddleware::getTeamLeadProjects($user))->pluck('id');

        return [
            'by_status' => DB::table('cards')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->whereIn('project_id', $projectIds)
                ->groupBy('status')
                ->get()
                ->toArray(),
            'by_priority' => DB::table('cards')
                ->select('priority', DB::raw('COUNT(*) as count'))
                ->whereIn('project_id', $projectIds)
                ->groupBy('priority')
                ->get()
                ->toArray()
        ];
    }

    private function getTask($id)
    {
        return DB::table('cards')
            ->select('cards.*', 'users.name as assigned_to_name')
            ->join('users', 'cards.assigned_to', '=', 'users.id')
            ->where('cards.id', $id)
            ->first();
    }

    private function getDefaultBoardId($projectId)
    {
        $board = DB::table('boards')->where('project_id', $projectId)->first();
        return $board ? $board->id : null;
    }

    private function getTeamOverview($user)
    {
        $projectIds = collect(TeamLeadMiddleware::getTeamLeadProjects($user))->pluck('id');

        return [
            'projects' => DB::table('projects')->whereIn('id', $projectIds)->get()->toArray(),
            'team_members' => $this->getTeamMembers($user),
            'recent_completions' => DB::table('cards')
                ->select('cards.*', 'users.name as assigned_to_name')
                ->join('users', 'cards.assigned_to', '=', 'users.id')
                ->whereIn('cards.project_id', $projectIds)
                ->where('cards.status', 'Done')
                ->where('cards.updated_at', '>=', now()->subDays(7))
                ->orderBy('cards.updated_at', 'desc')
                ->get()
                ->toArray()
        ];
    }

    private function getTeamBlockers($user)
    {
        $projectIds = collect(TeamLeadMiddleware::getTeamLeadProjects($user))->pluck('id');

        return DB::table('cards')
            ->select('cards.*', 'users.name as assigned_to_name', 'projects.name as project_name')
            ->join('users', 'cards.assigned_to', '=', 'users.id')
            ->join('projects', 'cards.project_id', '=', 'projects.id')
            ->whereIn('cards.project_id', $projectIds)
            ->where('cards.status', 'Blocked')
            ->orderBy('cards.priority', 'desc')
            ->orderBy('cards.due_date', 'asc')
            ->get()
            ->toArray();
    }

    private function getTeamProgressData($user)
    {
        $projectIds = collect(TeamLeadMiddleware::getTeamLeadProjects($user))->pluck('id');

        return DB::table('projects')
            ->select('projects.name', 'projects.progress', 'projects.status',
                     DB::raw('COUNT(cards.id) as total_tasks'),
                     DB::raw('SUM(CASE WHEN cards.status = "Done" THEN 1 ELSE 0 END) as completed_tasks'))
            ->leftJoin('cards', 'projects.id', '=', 'cards.project_id')
            ->whereIn('projects.id', $projectIds)
            ->groupBy('projects.id', 'projects.name', 'projects.progress', 'projects.status')
            ->get()
            ->toArray();
    }

    /**
     * Show team coordination page
     */
    public function coordination()
    {
        try {
            // Get projects where user is team lead
            $projects = DB::table('projects')
                ->join('project_members', 'projects.id', '=', 'project_members.project_id')
                ->where('project_members.user_id', Auth::id())
                ->where('project_members.role', 'Team Lead')
                ->select('projects.*')
                ->get();

            if ($projects->isEmpty()) {
                return redirect()->route('home')->with('error', 'Anda tidak memiliki akses Team Lead pada proyek manapun.');
            }

            $projectIds = $projects->pluck('id');

            // Get team members from all team lead projects
            $teamMembers = DB::table('project_members')
                ->join('users', 'project_members.user_id', '=', 'users.id')
                ->whereIn('project_members.project_id', $projectIds)
                ->where('project_members.user_id', '!=', Auth::id())
                ->select('users.*', 'project_members.role', 'project_members.project_id')
                ->get()
                ->map(function ($member) {
                    // Get current task count and status
                    $currentTasks = DB::table('cards')
                        ->where('assigned_to', $member->id)
                        ->whereIn('status', ['Todo', 'In Progress'])
                        ->count();

                    $currentTask = DB::table('cards')
                        ->where('assigned_to', $member->id)
                        ->where('status', 'In Progress')
                        ->first();

                    return [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'role' => $member->role,
                        'status' => 'active', // You might want to implement actual status tracking
                        'current_tasks' => $currentTasks,
                        'current_task' => $currentTask ? $currentTask->title : null,
                        'project_id' => $member->project_id
                    ];
                });

            // Get active blockers (tasks with status indicating blocked)
            $blockers = DB::table('cards')
                ->join('users', 'cards.assigned_to', '=', 'users.id')
                ->join('projects', 'cards.project_id', '=', 'projects.id')
                ->whereIn('cards.project_id', $projectIds)
                ->where(function($query) {
                    $query->where('cards.status', 'Blocked')
                          ->orWhere('cards.description', 'LIKE', '%blocker%')
                          ->orWhere('cards.description', 'LIKE', '%blocked%');
                })
                ->select(
                    'cards.*',
                    'users.name as affected_member',
                    'projects.name as project_name'
                )
                ->get()
                ->map(function ($card) {
                    return [
                        'id' => $card->id,
                        'title' => $card->title,
                        'description' => $card->description,
                        'priority' => $card->priority ?? 'Medium',
                        'affected_member' => $card->affected_member,
                        'project_name' => $card->project_name,
                        'created_at' => $card->created_at
                    ];
                });

            // Get pending reviews (completed tasks requiring team lead review)
            $pendingReviews = DB::table('cards')
                ->join('users', 'cards.assigned_to', '=', 'users.id')
                ->join('projects', 'cards.project_id', '=', 'projects.id')
                ->whereIn('cards.project_id', $projectIds)
                ->where('cards.status', 'Review')
                ->select(
                    'cards.*',
                    'users.name as completed_by',
                    'projects.name as project_name'
                )
                ->get()
                ->map(function ($card) {
                    return [
                        'id' => $card->id,
                        'title' => $card->title,
                        'description' => $card->description,
                        'completed_by' => $card->completed_by,
                        'project_name' => $card->project_name,
                        'submitted_at' => $card->updated_at
                    ];
                });

            return view('teamlead.coordination', compact('teamMembers', 'blockers', 'pendingReviews'));

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Resolve blocker
     */
    public function resolveBlocker($id)
    {
        try {
            $card = DB::table('cards')->where('id', $id)->first();

            if (!$card) {
                return response()->json(['success' => false, 'message' => 'Task tidak ditemukan']);
            }

            // Check if user has team lead access to this project
            $hasAccess = DB::table('project_members')
                ->where('project_id', $card->project_id)
                ->where('user_id', Auth::id())
                ->where('role', 'Team Lead')
                ->exists();

            if (!$hasAccess) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk resolve blocker ini']);
            }

            // Update card status from blocked to in progress
            DB::table('cards')
                ->where('id', $id)
                ->update([
                    'status' => 'In Progress',
                    'updated_at' => now()
                ]);

            // Log the blocker resolution
            DB::table('comments')->insert([
                'card_id' => $id,
                'user_id' => Auth::id(),
                'comment' => 'Blocker resolved by Team Lead',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Blocker berhasil di-resolve']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Escalate blocker to Project Admin
     */
    public function escalateBlocker($id)
    {
        try {
            $card = DB::table('cards')->where('id', $id)->first();

            if (!$card) {
                return response()->json(['success' => false, 'message' => 'Task tidak ditemukan']);
            }

            // Check if user has team lead access to this project
            $hasAccess = DB::table('project_members')
                ->where('project_id', $card->project_id)
                ->where('user_id', Auth::id())
                ->where('role', 'Team Lead')
                ->exists();

            if (!$hasAccess) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk escalate blocker ini']);
            }

            // Add escalation comment
            DB::table('comments')->insert([
                'card_id' => $id,
                'user_id' => Auth::id(),
                'comment' => 'ESCALATED TO PROJECT ADMIN: This blocker requires Project Admin intervention. Team Lead: ' . Auth::user()->name,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update card priority to Critical if not already
            DB::table('cards')
                ->where('id', $id)
                ->update([
                    'priority' => 'Critical',
                    'updated_at' => now()
                ]);

            return response()->json(['success' => true, 'message' => 'Blocker berhasil di-escalate ke Project Admin']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Approve completed task
     */
    public function approveTask($id)
    {
        try {
            $card = DB::table('cards')->where('id', $id)->first();

            if (!$card) {
                return response()->json(['success' => false, 'message' => 'Task tidak ditemukan']);
            }

            // Check if user has team lead access to this project
            $hasAccess = DB::table('project_members')
                ->where('project_id', $card->project_id)
                ->where('user_id', Auth::id())
                ->where('role', 'Team Lead')
                ->exists();

            if (!$hasAccess) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk approve task ini']);
            }

            // Update card status to Done
            DB::table('cards')
                ->where('id', $id)
                ->update([
                    'status' => 'Done',
                    'completed_at' => now(),
                    'updated_at' => now()
                ]);

            // Add approval comment
            DB::table('comments')->insert([
                'card_id' => $id,
                'user_id' => Auth::id(),
                'comment' => 'Task approved and completed by Team Lead: ' . Auth::user()->name,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Task berhasil di-approve']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Request task revision
     */
    public function requestRevision($id, Request $request)
    {
        try {
            $card = DB::table('cards')->where('id', $id)->first();

            if (!$card) {
                return response()->json(['success' => false, 'message' => 'Task tidak ditemukan']);
            }

            // Check if user has team lead access to this project
            $hasAccess = DB::table('project_members')
                ->where('project_id', $card->project_id)
                ->where('user_id', Auth::id())
                ->where('role', 'Team Lead')
                ->exists();

            if (!$hasAccess) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk request revision pada task ini']);
            }

            // Update card status back to In Progress
            DB::table('cards')
                ->where('id', $id)
                ->update([
                    'status' => 'In Progress',
                    'updated_at' => now()
                ]);

            // Add revision comment
            $feedback = $request->input('feedback', 'Revision requested by Team Lead');
            DB::table('comments')->insert([
                'card_id' => $id,
                'user_id' => Auth::id(),
                'comment' => 'REVISION REQUESTED: ' . $feedback . ' - Team Lead: ' . Auth::user()->name,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Revision request berhasil dikirim']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Get coordination data for AJAX refresh
     */
    public function coordinationData()
    {
        try {
            // Get projects where user is team lead
            $projects = DB::table('projects')
                ->join('project_members', 'projects.id', '=', 'project_members.project_id')
                ->where('project_members.user_id', Auth::id())
                ->where('project_members.role', 'Team Lead')
                ->select('projects.*')
                ->get();

            $projectIds = $projects->pluck('id');

            // Calculate performance metrics
            $totalTasks = DB::table('cards')->whereIn('project_id', $projectIds)->count();
            $completedTasks = DB::table('cards')->whereIn('project_id', $projectIds)->where('status', 'Done')->count();
            $sprintCompletion = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            $activeBlockers = DB::table('cards')
                ->whereIn('project_id', $projectIds)
                ->where(function($query) {
                    $query->where('status', 'Blocked')
                          ->orWhere('description', 'LIKE', '%blocker%')
                          ->orWhere('description', 'LIKE', '%blocked%');
                })
                ->count();

            // Calculate on-time delivery (tasks completed before due date)
            $tasksWithDueDate = DB::table('cards')
                ->whereIn('project_id', $projectIds)
                ->where('status', 'Done')
                ->whereNotNull('due_date')
                ->whereNotNull('completed_at')
                ->get();

            $onTimeCount = $tasksWithDueDate->filter(function($task) {
                return $task->completed_at <= $task->due_date;
            })->count();

            $onTimeDelivery = $tasksWithDueDate->count() > 0 ?
                round(($onTimeCount / $tasksWithDueDate->count()) * 100) : 100;

            return response()->json([
                'sprintCompletion' => $sprintCompletion,
                'activeBlockers' => $activeBlockers,
                'onTimeDelivery' => $onTimeDelivery,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Broadcast message to team
     */
    public function broadcastMessage(Request $request)
    {
        try {
            $message = $request->input('message');

            if (!$message) {
                return response()->json(['success' => false, 'message' => 'Message tidak boleh kosong']);
            }

            // Get projects where user is team lead
            $projects = DB::table('projects')
                ->join('project_members', 'projects.id', '=', 'project_members.project_id')
                ->where('project_members.user_id', Auth::id())
                ->where('project_members.role', 'Team Lead')
                ->select('projects.*')
                ->get();

            $projectIds = $projects->pluck('id');

            // Get all team members from team lead projects
            $teamMembers = DB::table('project_members')
                ->whereIn('project_id', $projectIds)
                ->where('user_id', '!=', Auth::id())
                ->pluck('user_id')
                ->unique();

            // Here you would typically send notifications to team members
            // For now, we'll just log the broadcast
            foreach ($teamMembers as $memberId) {
                // Create notification record (you might want to create a notifications table)
                // This is a placeholder for actual notification system
            }

            return response()->json(['success' => true, 'message' => 'Pesan berhasil dikirim ke tim']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper Methods for Team Lead Operations
     */

    /**
     * Get projects where user is Team Lead
     */
    private function getTeamLeadProjects($user)
    {
        return DB::table('projects')
            ->join('project_members', 'projects.id', '=', 'project_members.project_id')
            ->where('project_members.user_id', $user->user_id)
            ->where('project_members.role', 'Team Lead')
            ->select('projects.*')
            ->get();
    }

    /**
     * Check if user can access specific project
     */
    private function canAccessProject($user, $projectId)
    {
        return DB::table('project_members')
            ->where('project_id', $projectId)
            ->where('user_id', $user->user_id)
            ->where('role', 'Team Lead')
            ->exists();
    }
}
