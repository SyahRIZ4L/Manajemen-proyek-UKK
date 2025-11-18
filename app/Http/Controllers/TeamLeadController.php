<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\TeamLeadMiddleware;
use App\Models\Card;
use App\Models\CardReview;
use App\Models\CardHistory;
use App\Models\User;

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
        $recentActivities = $this->getRecentActivitiesOld($user);

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
     * Team Lead Panel - New comprehensive interface
     */
    public function panel()
    {
        $user = Auth::user();
        return view('teamlead.panel', compact('user'));
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
                    'status' => $request->status
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
                    'priority' => $request->priority
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

    private function getRecentActivitiesOld($user)
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
                    'completed_at' => now()
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
                    'status' => 'In Progress'
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

    /**
     * API: Get current project for Team Lead
     */
    public function getCurrentProject()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'User not authenticated'
            ]);
        }

        // Get Team Lead project using the correct table name 'members'
        $project = DB::table('projects')
            ->join('members', 'projects.project_id', '=', 'members.project_id')
            ->where('members.user_id', $user->user_id)
            ->where('members.role', 'Team_Lead')
            ->select('projects.*')
            ->first();

        if (!$project) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'No project assigned to this Team Lead'
            ]);
        }

        // Map database fields to frontend expected fields
        $project->id = $project->project_id;
        $project->name = $project->project_name;

        // Format dates
        $project->start_date = $project->created_at ? date('M d, Y', strtotime($project->created_at)) : null;
        $project->end_date = $project->deadline ? date('M d, Y', strtotime($project->deadline)) : null;

        // Add basic stats (simplified)
        $project->team_count = DB::table('members')->where('project_id', $project->project_id)->where('role', '!=', 'Team_Lead')->count();
        $project->task_count = 5; // Mock for now
        $project->progress = 25; // Mock for now
        $project->completed_tasks = 1; // Mock for now
        $project->pending_tasks = 4; // Mock for now
        $project->active_members = $project->team_count;

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * API: Get cards for Team Lead's projects
     */
    public function getCards()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get Team Lead's project(s)
        $projects = DB::table('projects')
            ->join('members', 'projects.project_id', '=', 'members.project_id')
            ->where('members.user_id', $user->user_id)
            ->where('members.role', 'Team_Lead')
            ->select('projects.*')
            ->get();

        if ($projects->isEmpty()) {
            return response()->json([
                'success' => true,
                'cards' => [],
                'statistics' => [
                    'total_cards' => 0,
                    'todo_cards' => 0,
                    'in_progress_cards' => 0,
                    'completed_cards' => 0
                ]
            ]);
        }

        $projectIds = $projects->pluck('project_id');

        // Get cards from all Team Lead's projects with assignment info
        $cards = DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->leftJoin('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
            ->leftJoin('users as assigned_users', 'card_assignments.user_id', '=', 'assigned_users.user_id')
            ->whereIn('boards.project_id', $projectIds)
            ->select(
                'cards.*',
                'boards.board_name',
                'projects.project_name',
                'projects.project_id',
                'card_assignments.assignment_id',
                'card_assignments.assigned_at',
                'card_assignments.assignment_status',
                'assigned_users.full_name as assigned_user_name',
                'assigned_users.email as assigned_user_email'
            )
            ->orderBy('cards.created_at', 'desc')
            ->get();

        // Calculate statistics
        $statistics = [
            'total_cards' => $cards->count(),
            'todo_cards' => $cards->where('status', 'To Do')->count(),
            'in_progress_cards' => $cards->where('status', 'In Progress')->count(),
            'completed_cards' => $cards->where('status', 'Done')->count(),
            'assigned_cards' => $cards->whereNotNull('assignment_id')->count(),
            'unassigned_cards' => $cards->whereNull('assignment_id')->count()
        ];

        return response()->json([
            'success' => true,
            'cards' => $cards->toArray(),
            'statistics' => $statistics
        ]);
    }

    /**
     * API: Get assigned cards for Team Lead
     */
    public function getAssignedCards()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get cards directly assigned to the Team Lead
        $assignedCards = DB::table('card_assignments')
            ->join('cards', 'card_assignments.card_id', '=', 'cards.card_id')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->where('card_assignments.user_id', $user->user_id)
            ->select(
                'cards.*',
                'boards.board_name',
                'projects.project_name',
                'projects.project_id',
                'card_assignments.assigned_at'
            )
            ->orderBy('card_assignments.assigned_at', 'desc')
            ->get();

        // Calculate statistics
        $statistics = [
            'total_assigned' => $assignedCards->count(),
            'todo_assigned' => $assignedCards->where('status', 'To Do')->count(),
            'in_progress_assigned' => $assignedCards->where('status', 'In Progress')->count(),
            'completed_assigned' => $assignedCards->where('status', 'Done')->count()
        ];

        return response()->json([
            'success' => true,
            'cards' => $assignedCards->toArray(),
            'statistics' => $statistics
        ]);
    }

    /**
     * API: Create new card
     */
    public function createCard(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                Log::warning('Create card attempt without authentication');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            Log::info('Create card request', [
                'user_id' => $user->user_id,
                'request_data' => $request->all()
            ]);

            $request->validate([
                'board_id' => 'required|integer|min:1',
                'card_title' => 'required|string|max:100|min:3',
                'description' => 'nullable|string|max:1000',
                'priority' => 'required|in:low,medium,high',
                'due_date' => 'nullable|date|after:today'
            ]);

            // Verify the board belongs to Team Lead's project
            $board = DB::table('boards')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('members', 'projects.project_id', '=', 'members.project_id')
                ->where('boards.board_id', $request->board_id)
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('boards.*', 'projects.project_name')
                ->first();

            if (!$board) {
                Log::warning('Create card attempt on unauthorized board', [
                    'user_id' => $user->user_id,
                    'board_id' => $request->board_id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Board not found or you do not have permission to create cards on this board'
                ], 403);
            }

            // Get the next position for this board
            $nextPosition = DB::table('cards')
                ->where('board_id', $request->board_id)
                ->max('position') + 1;

            // Prepare card data
            $cardData = [
                'board_id' => $request->board_id,
                'card_title' => trim($request->card_title),
                'description' => $request->description ? trim($request->description) : null,
                'position' => $nextPosition ?? 1,
                'status' => 'todo',
                'priority' => strtolower($request->priority),
                'due_date' => $request->due_date,
                'created_by' => $user->user_id,
                'created_at' => now()
            ];

            // Create the card
            $cardId = DB::table('cards')->insertGetId($cardData);

            Log::info('Card created successfully', [
                'card_id' => $cardId,
                'user_id' => $user->user_id,
                'board_id' => $request->board_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Card created successfully',
                'card_id' => $cardId,
                'card_title' => $cardData['card_title'],
                'board_name' => $board->board_name
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Create card validation failed', [
                'user_id' => $user->user_id ?? null,
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error creating card', [
                'user_id' => $user->user_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the card. Please try again.'
            ], 500);
        }
    }

    /**
     * API: Get My Cards (cards created by Team Lead)
     */
    public function getMyCards(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get cards created by this Team Lead
            $myCards = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->leftJoin('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
                ->leftJoin('users as assigned_user', 'card_assignments.user_id', '=', 'assigned_user.user_id')
                ->where('cards.created_by', $user->user_id)
                ->select(
                    'cards.card_id as id',
                    'cards.card_title as title',
                    'cards.description',
                    'cards.status',
                    'cards.priority',
                    'cards.due_date',
                    'cards.created_at',
                    'cards.estimated_hours',
                    'cards.actual_hours',
                    'boards.board_name',
                    'projects.project_id',
                    'projects.project_name',
                    'assigned_user.username as assigned_to',
                    'assigned_user.full_name as assigned_to_name',
                    'card_assignments.assigned_at'
                )
                ->orderBy('cards.created_at', 'desc')
                ->get();

            // Group cards by status for better organization
            $cardsByStatus = [
                'todo' => [],
                'in_progress' => [],
                'review' => [],
                'done' => []
            ];

            foreach ($myCards as $card) {
                $status = strtolower($card->status);
                if (!isset($cardsByStatus[$status])) {
                    $cardsByStatus[$status] = [];
                }
                $cardsByStatus[$status][] = $card;
            }

            return response()->json([
                'success' => true,
                'data' => $cardsByStatus,
                'total_cards' => count($myCards)
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting my cards: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching cards: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Update card (Team Lead only)
     */
    public function updateCard(Request $request, $cardId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            Log::info('Update card request', [
                'user_id' => $user->user_id,
                'card_id' => $cardId,
                'request_data' => $request->all()
            ]);

            $request->validate([
                'card_title' => 'required|string|max:100|min:3',
                'description' => 'nullable|string|max:1000',
                'priority' => 'required|in:low,medium,high',
                'due_date' => 'nullable|date|after:today'
            ]);

            // Verify the card exists and belongs to Team Lead's project
            $card = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('members as team_lead', 'projects.project_id', '=', 'team_lead.project_id')
                ->where('cards.card_id', $cardId)
                ->where('team_lead.user_id', $user->user_id)
                ->where('team_lead.role', 'Team_Lead')
                ->select('cards.*', 'projects.project_id')
                ->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found or access denied'
                ], 404);
            }



            DB::beginTransaction();

            // Update card (no updated_at column in cards table)
            $updated = DB::table('cards')
                ->where('card_id', $cardId)
                ->update([
                    'card_title' => $request->card_title,
                    'description' => $request->description,
                    'priority' => $request->priority,
                    'due_date' => $request->due_date
                ]);

            if (!$updated) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update card'
                ], 500);
            }

            // Log card history
            DB::table('card_histories')->insert([
                'card_id' => $cardId,
                'user_id' => $user->user_id,
                'action' => 'updated',
                'description' => 'Card details updated by Team Lead',
                'created_at' => now()
            ]);

            DB::commit();

            Log::info('Card updated successfully', [
                'card_id' => $cardId,
                'updated_by' => $user->user_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Card updated successfully',
                'card_id' => $cardId
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating card', [
                'card_id' => $cardId,
                'user_id' => $user->user_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the card. Please try again.'
            ], 500);
        }
    }

    /**
     * API: Assign card to project member
     */
    public function assignCardToMember(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $request->validate([
            'card_id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);

        // Verify the card belongs to Team Lead's project
        $card = DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->join('members as team_lead', 'projects.project_id', '=', 'team_lead.project_id')
            ->where('cards.card_id', $request->card_id)
            ->where('team_lead.user_id', $user->user_id)
            ->where('team_lead.role', 'Team_Lead')
            ->select('cards.*', 'projects.project_id')
            ->first();

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Card not found or access denied'
            ]);
        }

        // Verify the user is a member of the same project
        $projectMember = DB::table('members')
            ->where('project_id', $card->project_id)
            ->where('user_id', $request->user_id)
            ->first();

        if (!$projectMember) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a member of this project'
            ]);
        }

        // Check if already assigned
        $existingAssignment = DB::table('card_assignments')
            ->where('card_id', $request->card_id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existingAssignment) {
            return response()->json([
                'success' => false,
                'message' => 'User is already assigned to this card'
            ]);
        }

        // Create the assignment
        $assignmentId = DB::table('card_assignments')->insertGetId([
            'card_id' => $request->card_id,
            'user_id' => $request->user_id,
            'assigned_at' => now(),
            'assignment_status' => 'assigned'
        ]);

        // Get assigned user info
        $assignedUser = DB::table('users')
            ->where('user_id', $request->user_id)
            ->select('full_name', 'email')
            ->first();

        // Get card details for notification
        $cardDetails = DB::table('cards')
            ->where('card_id', $request->card_id)
            ->select('card_title', 'description', 'priority', 'due_date')
            ->first();

        // Create notification for the assigned user
        DB::table('notifications')->insert([
            'user_id' => $request->user_id,
            'project_id' => $card->project_id,
            'triggered_by' => $user->user_id,
            'type' => 'card_assignment',
            'title' => 'New Card Assigned',
            'message' => 'You have been assigned to work on: ' . $cardDetails->card_title,
            'data' => json_encode([
                'card_id' => $request->card_id,
                'card_title' => $cardDetails->card_title,
                'assigned_by' => $user->full_name,
                'priority' => $cardDetails->priority,
                'due_date' => $cardDetails->due_date,
                'action_url' => '/member/card/' . $request->card_id
            ]),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Card assigned successfully to ' . $assignedUser->full_name,
            'assignment_id' => $assignmentId,
            'assigned_user' => $assignedUser
        ]);
    }

    /**
     * API: Get card assignments for a specific card
     */
    public function getCardAssignments($cardId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Verify the card belongs to Team Lead's project
        $card = DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->join('members', 'projects.project_id', '=', 'members.project_id')
            ->where('cards.card_id', $cardId)
            ->where('members.user_id', $user->user_id)
            ->where('members.role', 'Team_Lead')
            ->first();

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Card not found or access denied'
            ]);
        }

        // Get card assignments
        $assignments = DB::table('card_assignments')
            ->join('users', 'card_assignments.user_id', '=', 'users.user_id')
            ->where('card_assignments.card_id', $cardId)
            ->select(
                'card_assignments.assignment_id',
                'card_assignments.user_id',
                'users.full_name',
                'users.email',
                'card_assignments.assigned_at',
                'card_assignments.assignment_status',
                'card_assignments.started_at',
                'card_assignments.completed_at'
            )
            ->orderBy('card_assignments.assigned_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'assignments' => $assignments
        ]);
    }

    /**
     * API: Remove card assignment
     */
    public function removeCardAssignment(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $request->validate([
            'assignment_id' => 'required|integer'
        ]);

        // Get assignment and verify access
        $assignment = DB::table('card_assignments')
            ->join('cards', 'card_assignments.card_id', '=', 'cards.card_id')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->join('members', 'projects.project_id', '=', 'members.project_id')
            ->where('card_assignments.assignment_id', $request->assignment_id)
            ->where('members.user_id', $user->user_id)
            ->where('members.role', 'Team_Lead')
            ->select('card_assignments.*')
            ->first();

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or access denied'
            ]);
        }

        // Delete assignment
        DB::table('card_assignments')
            ->where('assignment_id', $request->assignment_id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Card assignment removed successfully'
        ]);
    }

    /**
     * API: Get unassigned cards for manual assignment
     */
    public function getUnassignedCards()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get Team Lead's project(s)
        $projects = DB::table('projects')
            ->join('members', 'projects.project_id', '=', 'members.project_id')
            ->where('members.user_id', $user->user_id)
            ->where('members.role', 'Team_Lead')
            ->select('projects.*')
            ->get();

        if ($projects->isEmpty()) {
            return response()->json([
                'success' => true,
                'cards' => []
            ]);
        }

        $projectIds = $projects->pluck('project_id');

        // Get unassigned cards
        $unassignedCards = DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->leftJoin('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
            ->whereIn('boards.project_id', $projectIds)
            ->whereNull('card_assignments.card_id') // No assignment exists
            ->select(
                'cards.*',
                'boards.board_name',
                'projects.project_name',
                'projects.project_id'
            )
            ->orderBy('cards.priority', 'desc')
            ->orderBy('cards.due_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'cards' => $unassignedCards
        ]);
    }

    /**
     * API: Create new board
     */
    public function createBoard(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            $request->validate([
                'board_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500'
            ]);

            // Get the project where user is Team Lead
            $project = DB::table('members')
                ->join('projects', 'members.project_id', '=', 'projects.project_id')
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('projects.*')
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'No project assigned to you as Team Lead'
                ]);
            }

            // Check if board name already exists in this project
            $existingBoard = DB::table('boards')
                ->where('project_id', $project->project_id)
                ->where('board_name', $request->board_name)
                ->first();

            if ($existingBoard) {
                return response()->json([
                    'success' => false,
                    'message' => 'Board with this name already exists in the project'
                ]);
            }

            // Create the board
            $boardId = DB::table('boards')->insertGetId([
                'project_id' => $project->project_id,
                'board_name' => $request->board_name,
                'description' => $request->description,
                'created_by' => $user->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Board created successfully',
                'board_id' => $boardId,
                'board' => [
                    'board_id' => $boardId,
                    'board_name' => $request->board_name,
                    'description' => $request->description,
                    'project_name' => $project->project_name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating board: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Update board
     */
    public function updateBoard(Request $request, $boardId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            $request->validate([
                'board_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500'
            ]);

            // Verify the board belongs to Team Lead's project
            $board = DB::table('boards')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('members', 'projects.project_id', '=', 'members.project_id')
                ->where('boards.board_id', $boardId)
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('boards.*', 'projects.project_name')
                ->first();

            if (!$board) {
                return response()->json([
                    'success' => false,
                    'message' => 'Board not found or access denied'
                ]);
            }

            // Update the board
            DB::table('boards')
                ->where('board_id', $boardId)
                ->update([
                    'board_name' => $request->board_name,
                    'description' => $request->description,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Board updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating board: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Delete board
     */
    public function deleteBoard($boardId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            // Verify the board belongs to Team Lead's project
            $board = DB::table('boards')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('members', 'projects.project_id', '=', 'members.project_id')
                ->where('boards.board_id', $boardId)
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('boards.*')
                ->first();

            if (!$board) {
                return response()->json([
                    'success' => false,
                    'message' => 'Board not found or access denied'
                ]);
            }

            // Check if board has cards
            $cardCount = DB::table('cards')->where('board_id', $boardId)->count();

            if ($cardCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete board. It contains {$cardCount} cards. Please move or delete all cards first."
                ]);
            }

            // Delete the board
            DB::table('boards')->where('board_id', $boardId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Board deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting board: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get board detail with cards
     */
    public function getBoardDetail($boardId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            // Verify the board belongs to Team Lead's project and get board info
            $board = DB::table('boards')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('members', 'projects.project_id', '=', 'members.project_id')
                ->where('boards.board_id', $boardId)
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select(
                    'boards.*',
                    'projects.project_name',
                    'projects.project_id'
                )
                ->first();

            if (!$board) {
                return response()->json([
                    'success' => false,
                    'message' => 'Board not found or access denied'
                ]);
            }

            // Get cards in this board with assignment info
            $cards = DB::table('cards')
                ->leftJoin('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
                ->leftJoin('users as assigned_users', 'card_assignments.user_id', '=', 'assigned_users.user_id')
                ->where('cards.board_id', $boardId)
                ->select(
                    'cards.*',
                    'card_assignments.assignment_id',
                    'card_assignments.assigned_at',
                    'card_assignments.assignment_status',
                    'assigned_users.full_name as assigned_user_name',
                    'assigned_users.email as assigned_user_email'
                )
                ->orderBy('cards.created_at', 'desc')
                ->get();

            // Group cards by status for kanban view
            $cardsByStatus = [
                'To Do' => $cards->where('status', 'To Do')->values(),
                'In Progress' => $cards->where('status', 'In Progress')->values(),
                'Done' => $cards->where('status', 'Done')->values()
            ];

            // Calculate board statistics
            $statistics = [
                'total_cards' => $cards->count(),
                'todo_cards' => $cards->where('status', 'To Do')->count(),
                'in_progress_cards' => $cards->where('status', 'In Progress')->count(),
                'done_cards' => $cards->where('status', 'Done')->count(),
                'assigned_cards' => $cards->whereNotNull('assignment_id')->count(),
                'unassigned_cards' => $cards->whereNull('assignment_id')->count()
            ];

            return response()->json([
                'success' => true,
                'board' => $board,
                'cards' => $cards,
                'cards_by_status' => $cardsByStatus,
                'statistics' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading board detail: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get boards for Team Lead's projects
     */
    public function getBoards()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get boards from Team Lead's projects with enhanced info
        $boards = DB::table('boards')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->join('members', 'projects.project_id', '=', 'members.project_id')
            ->leftJoin('cards', 'boards.board_id', '=', 'cards.board_id')
            ->where('members.user_id', $user->user_id)
            ->where('members.role', 'Team_Lead')
            ->select(
                'boards.board_id as id',
                'boards.board_name as name',
                'boards.description',
                'boards.created_at',
                'projects.project_name',
                DB::raw('COUNT(cards.card_id) as total_cards'),
                DB::raw('COUNT(CASE WHEN cards.status = "To Do" THEN 1 END) as todo_cards'),
                DB::raw('COUNT(CASE WHEN cards.status = "In Progress" THEN 1 END) as in_progress_cards'),
                DB::raw('COUNT(CASE WHEN cards.status = "Done" THEN 1 END) as done_cards')
            )
            ->groupBy('boards.board_id', 'boards.board_name', 'boards.description', 'boards.created_at', 'projects.project_name')
            ->orderBy('boards.created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'boards' => $boards
        ]);
    }

    /**
     * API: Get boards for card creation dropdown
     */
    public function getBoardsForCard()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get boards list with card count for dropdown
        $boards = DB::table('boards')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->join('members', 'projects.project_id', '=', 'members.project_id')
            ->leftJoin('cards', 'boards.board_id', '=', 'cards.board_id')
            ->where('members.user_id', $user->user_id)
            ->where('members.role', 'Team_Lead')
            ->select(
                'boards.board_id',
                'boards.board_name',
                'projects.project_name',
                DB::raw('COUNT(cards.card_id) as card_count')
            )
            ->groupBy('boards.board_id', 'boards.board_name', 'projects.project_name')
            ->orderBy('projects.project_name')
            ->orderBy('boards.board_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $boards->toArray()
        ]);
    }

    /**
     * API: Get project detail for Team Lead
     */
    public function getProjectDetail()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            // Get the project where user is Team Lead
            $project = DB::table('members')
                ->join('projects', 'members.project_id', '=', 'projects.project_id')
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('projects.*')
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'No project assigned to you as Team Lead'
                ]);
            }

            // Get project statistics
            $stats = DB::table('boards')
                ->leftJoin('cards', 'boards.board_id', '=', 'cards.board_id')
                ->leftJoin('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
                ->where('boards.project_id', $project->project_id)
                ->select(
                    DB::raw('COUNT(DISTINCT boards.board_id) as total_boards'),
                    DB::raw('COUNT(DISTINCT cards.card_id) as total_cards'),
                    DB::raw('COUNT(DISTINCT card_assignments.assignment_id) as total_assignments'),
                    DB::raw('COUNT(DISTINCT CASE WHEN cards.status = "Done" THEN cards.card_id END) as completed_cards'),
                    DB::raw('COUNT(DISTINCT CASE WHEN cards.status = "In Progress" THEN cards.card_id END) as in_progress_cards'),
                    DB::raw('COUNT(DISTINCT CASE WHEN cards.status = "To Do" THEN cards.card_id END) as todo_cards')
                )
                ->first();

            // Get team members count
            $memberStats = DB::table('members')
                ->where('project_id', $project->project_id)
                ->select(
                    DB::raw('COUNT(*) as total_members'),
                    DB::raw('COUNT(CASE WHEN role = "Developer" THEN 1 END) as developers'),
                    DB::raw('COUNT(CASE WHEN role = "Designer" THEN 1 END) as designers'),
                    DB::raw('COUNT(CASE WHEN role = "Member" THEN 1 END) as members')
                )
                ->first();

            // Calculate project progress
            $progressPercentage = 0;
            if ($stats->total_cards > 0) {
                $progressPercentage = round(($stats->completed_cards / $stats->total_cards) * 100, 1);
            }

            return response()->json([
                'success' => true,
                'project' => $project,
                'statistics' => [
                    'boards' => $stats->total_boards,
                    'cards' => [
                        'total' => $stats->total_cards,
                        'completed' => $stats->completed_cards,
                        'in_progress' => $stats->in_progress_cards,
                        'todo' => $stats->todo_cards
                    ],
                    'assignments' => $stats->total_assignments,
                    'members' => [
                        'total' => $memberStats->total_members,
                        'developers' => $memberStats->developers,
                        'designers' => $memberStats->designers,
                        'members' => $memberStats->members
                    ],
                    'progress_percentage' => $progressPercentage
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading project detail: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get project timeline
     */
    public function getProjectTimeline()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Mock timeline data for now
        $timeline = [
            [
                'id' => 1,
                'title' => 'Project Started',
                'description' => 'Project has been officially started and team assigned',
                'date' => '2025-11-10',
                'type' => 'milestone',
                'status' => 'completed'
            ],
            [
                'id' => 2,
                'title' => 'Initial Planning Complete',
                'description' => 'Project requirements and initial planning completed',
                'date' => '2025-11-11',
                'type' => 'task',
                'status' => 'in_progress'
            ],
            [
                'id' => 3,
                'title' => 'Development Phase',
                'description' => 'Start of development phase',
                'date' => '2025-11-12',
                'type' => 'milestone',
                'status' => 'pending'
            ]
        ];

        return response()->json([
            'success' => true,
            'timeline' => $timeline
        ]);
    }

    /**
     * API: Get recent activities
     */
    public function getRecentActivities()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Mock activities data for now
        $activities = [
            [
                'id' => 1,
                'title' => 'New task assigned',
                'description' => 'Task "UI Design" assigned to John Doe',
                'time' => '2 hours ago',
                'type' => 'assignment',
                'user' => 'John Doe'
            ],
            [
                'id' => 2,
                'title' => 'Task completed',
                'description' => 'Task "Database Setup" marked as completed',
                'time' => '5 hours ago',
                'type' => 'completion',
                'user' => 'Jane Smith'
            ],
            [
                'id' => 3,
                'title' => 'Team member joined',
                'description' => 'Sarah Wilson joined the project team',
                'time' => '1 day ago',
                'type' => 'team',
                'user' => 'Sarah Wilson'
            ]
        ];

        return response()->json([
            'success' => true,
            'activities' => $activities
        ]);
    }

    /**
     * API: Get statistics
     */
    public function getStatistics()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get projects count
        $projectsCount = DB::table('members')
            ->where('user_id', $user->user_id)
            ->where('role', 'Team_Lead')
            ->count();

        // Get total cards
        $totalCards = DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->join('members', 'projects.project_id', '=', 'members.project_id')
            ->where('members.user_id', $user->user_id)
            ->where('members.role', 'Team_Lead')
            ->count();

        // Get team members count
        $teamMembersCount = DB::table('members as m1')
            ->join('members as m2', 'm1.project_id', '=', 'm2.project_id')
            ->where('m1.user_id', $user->user_id)
            ->where('m1.role', 'Team_Lead')
            ->where('m2.role', '!=', 'Team_Lead')
            ->distinct('m2.user_id')
            ->count();

        return response()->json([
            'success' => true,
            'statistics' => [
                'total_projects' => $projectsCount,
                'total_cards' => $totalCards,
                'team_members' => $teamMembersCount,
                'completion_rate' => 75 // Mock for now
            ]
        ]);
    }

    /**
     * Get available users (developers and designers) that can be added to the project
     */
    public function getAvailableUsers()
    {
        try {
            $user = Auth::user();

            // Get the project where user is Team Lead
            $project = DB::table('members')
                ->join('projects', 'members.project_id', '=', 'projects.project_id')
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('projects.*')
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'No project assigned to you as Team Lead'
                ]);
            }

            // Get users that are not already in this project
            $availableUsers = DB::table('users')
                ->leftJoin('members', function($join) use ($project) {
                    $join->on('users.user_id', '=', 'members.user_id')
                         ->where('members.project_id', '=', $project->project_id);
                })
                ->whereIn('users.role', ['developer', 'designer', 'member'])
                ->whereNull('members.user_id') // Not already in project
                ->select('users.user_id as id', 'users.full_name', 'users.email', 'users.role')
                ->get();

            return response()->json([
                'success' => true,
                'users' => $availableUsers,
                'project' => $project
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading available users: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Add user to project
     */
    public function addUserToProject(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
            ]);

            $user = Auth::user();

            // Get the project where user is Team Lead
            $project = DB::table('members')
                ->join('projects', 'members.project_id', '=', 'projects.project_id')
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('projects.*')
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'No project assigned to you as Team Lead'
                ]);
            }

            // Get user details
            $userToAdd = DB::table('users')->where('user_id', $request->user_id)->first();

            if (!$userToAdd) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // Check if user is already in project
            $existingMember = DB::table('members')
                ->where('project_id', $project->project_id)
                ->where('user_id', $request->user_id)
                ->first();

            if ($existingMember) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already in this project'
                ]);
            }

            // Add user to project
            DB::table('members')->insert([
                'project_id' => $project->project_id,
                'user_id' => $request->user_id,
                'role' => $userToAdd->role,
                'joined_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User successfully added to project',
                'user' => $userToAdd
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding user to project: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove user from project
     */
    public function removeUserFromProject(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
            ]);

            $user = Auth::user();

            // Get the project where user is Team Lead
            $project = DB::table('members')
                ->join('projects', 'members.project_id', '=', 'projects.project_id')
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('projects.*')
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'No project assigned to you as Team Lead'
                ]);
            }

            // Remove user from project (but don't remove Team Lead)
            $deleted = DB::table('members')
                ->where('project_id', $project->project_id)
                ->where('user_id', $request->user_id)
                ->where('role', '!=', 'Team_Lead')
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'User successfully removed from project'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found in project or cannot remove Team Lead'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing user from project: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get project members
     */
    public function getProjectMembers()
    {
        try {
            $user = Auth::user();

            // Get the project where user is Team Lead
            $project = DB::table('members')
                ->join('projects', 'members.project_id', '=', 'projects.project_id')
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('projects.*')
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'No project assigned to you as Team Lead'
                ]);
            }

            // Get all members of the project
            $members = DB::table('members')
                ->join('users', 'members.user_id', '=', 'users.user_id')
                ->where('members.project_id', $project->project_id)
                ->select('users.user_id', 'users.full_name', 'users.email', 'members.role', 'members.joined_at as created_at')
                ->orderBy('members.role')
                ->orderBy('users.full_name')
                ->get();

            return response()->json([
                'success' => true,
                'members' => $members,
                'project' => $project
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading project members: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get team members for assignment dropdown
     */
    public function getTeamMembersForAssignment(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            // Get project_id from board_id if provided, otherwise get default project
            $projectId = null;

            if ($request->has('board_id') && $request->board_id) {
                // Get project from board
                $board = DB::table('boards')
                    ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                    ->join('members', 'projects.project_id', '=', 'members.project_id')
                    ->where('boards.board_id', $request->board_id)
                    ->where('members.user_id', $user->user_id)
                    ->where('members.role', 'Team_Lead')
                    ->select('projects.project_id', 'projects.project_name')
                    ->first();

                if ($board) {
                    $projectId = $board->project_id;
                }
            }

            // If no specific project from board, get the first project where user is Team Lead
            if (!$projectId) {
                $project = DB::table('members')
                    ->join('projects', 'members.project_id', '=', 'projects.project_id')
                    ->where('members.user_id', $user->user_id)
                    ->where('members.role', 'Team_Lead')
                    ->select('projects.*')
                    ->first();

                if (!$project) {
                    // Debug: Check all memberships for this user
                    $allMemberships = DB::table('members')
                        ->join('projects', 'members.project_id', '=', 'projects.project_id')
                        ->where('members.user_id', $user->user_id)
                        ->select('projects.project_name', 'members.role', 'members.project_id')
                        ->get();

                    return response()->json([
                        'success' => false,
                        'message' => 'No project assigned to you as Team Lead',
                        'debug' => [
                            'user_id' => $user->user_id,
                            'looking_for_role' => 'Team_Lead',
                            'all_memberships' => $allMemberships
                        ]
                    ]);
                }

                $projectId = $project->project_id;
            }

            // Get project info
            $projectInfo = DB::table('projects')->where('project_id', $projectId)->first();

            // Get project members (excluding Team Lead and Project Admin)
            $members = DB::table('members')
                ->join('users', 'members.user_id', '=', 'users.user_id')
                ->where('members.project_id', $projectId)
                ->whereIn('members.role', ['Developer', 'Designer', 'Member'])
                ->select(
                    'users.user_id',
                    'users.full_name',
                    'users.email',
                    'members.role',
                    'users.current_task_status'
                )
                ->orderBy('members.role')
                ->orderBy('users.full_name')
                ->get();

            // Add workload calculation for each member
            foreach ($members as $member) {
                // Count active assignments
                $activeAssignments = DB::table('card_assignments')
                    ->join('cards', 'card_assignments.card_id', '=', 'cards.card_id')
                    ->where('card_assignments.user_id', $member->user_id)
                    ->whereIn('card_assignments.assignment_status', ['assigned', 'in_progress'])
                    ->count();

                // Calculate workload level
                if ($activeAssignments == 0) {
                    $member->workload_level = 'Low';
                    $member->workload_color = 'success';
                } elseif ($activeAssignments <= 2) {
                    $member->workload_level = 'Medium';
                    $member->workload_color = 'warning';
                } else {
                    $member->workload_level = 'High';
                    $member->workload_color = 'danger';
                }

                $member->active_assignments = $activeAssignments;
            }

            return response()->json([
                'success' => true,
                'data' => $members,
                'project' => $projectInfo,
                'project_id' => $projectId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading team members: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get cards pending review from developers/designers
     */
    public function getPendingCardReviews()
    {
        try {
            $user = Auth::user();

            // Get TeamLead's projects
            $teamLeadProjects = DB::table('members')
                ->where('user_id', $user->user_id)
                ->where('role', 'Team_Lead')
                ->pluck('project_id');

            // Get cards in 'review' status from TeamLead's projects
            $pendingCards = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->leftJoin('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
                ->leftJoin('users', 'card_assignments.user_id', '=', 'users.user_id')
                ->where('cards.status', 'review')
                ->whereIn('projects.project_id', $teamLeadProjects)
                ->select(
                    'cards.card_id',
                    'cards.card_title as title',
                    'cards.description',
                    'cards.status',
                    'cards.priority',
                    'cards.created_at as submitted_at',
                    'users.username as submitted_by',
                    'users.full_name as submitted_by_name',
                    'boards.board_name',
                    'projects.project_name'
                )
                ->orderBy('cards.created_at', 'desc')
                ->get()
                ->map(function ($card) {
                    // Get latest comment/feedback if any
                    $latestReview = DB::table('card_reviews')
                        ->where('card_id', $card->card_id)
                        ->where('status', 'pending')
                        ->orderBy('created_at', 'desc')
                        ->first();

                    $card->comment = $latestReview->feedback ?? 'No comment provided';
                    return $card;
                });



            return response()->json([
                'success' => true,
                'data' => $pendingCards->toArray(),
                'count' => $pendingCards->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pending reviews: ' . $e->getMessage()
            ], 500);
        }
    }    /**
     * Approve card - change status to 'done'
     */
    public function approveCard(Request $request, $cardId)
    {
        try {
            $user = Auth::user();
            $feedback = $request->input('feedback', '');

            Log::info("Card approval request", [
                'card_id' => $cardId,
                'reviewer_id' => $user->user_id,
                'feedback_provided' => !empty($feedback),
                'timestamp' => now()
            ]);

            // Find the card and validate it exists and is in review status
            $card = Card::find($cardId);
            if (!$card) {
                Log::warning("Card not found for approval", ['card_id' => $cardId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found'
                ], 404);
            }

            if ($card->status !== 'review') {
                Log::warning("Card not in review status", [
                    'card_id' => $cardId,
                    'current_status' => $card->status
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Card is not in review status'
                ], 400);
            }

            // Get the assigned user (submitter) from card_assignments
            $assignment = DB::table('card_assignments')
                ->where('card_id', $cardId)
                ->first();

            if (!$assignment) {
                Log::warning("No assignment found for card", ['card_id' => $cardId]);
                return response()->json([
                    'success' => false,
                    'message' => 'No assignment found for this card'
                ], 404);
            }

            // Use database transaction
            DB::beginTransaction();

            try {
                // Create card review record
                $review = CardReview::create([
                    'card_id' => $cardId,
                    'reviewer_id' => $user->user_id,
                    'submitter_id' => $assignment->user_id,
                    'action' => 'approve',
                    'feedback' => $feedback,
                    'status' => 'completed'
                ]);

                // Update card status to 'done'
                $oldStatus = $card->status;
                $card->update(['status' => 'done', 'completed_at' => now()]);

                // Update assignment status
                DB::table('card_assignments')
                    ->where('card_id', $cardId)
                    ->update([
                        'completed_at' => now(),
                        'assignment_status' => 'completed'
                        // Note: submitted_at column doesn't exist in card_assignments table
                    ]);

                // Log to card history
                CardHistory::create([
                    'card_id' => $cardId,
                    'user_id' => $user->user_id,
                    'action' => 'approved',
                    'old_status' => $oldStatus,
                    'new_status' => 'done',
                    'comment' => 'Card approved by Team Lead',
                    'feedback' => $feedback,
                    'action_date' => now()
                ]);

                // Create notification (but don't let it fail the transaction)
                try {
                    $this->createApprovalNotification($cardId, $user, 'approve', $feedback);
                } catch (\Exception $e) {
                    Log::error("Failed to create approval notification", [
                        'card_id' => $cardId,
                        'error' => $e->getMessage()
                    ]);
                }

                DB::commit();

                Log::info("Card approved successfully", [
                    'card_id' => $cardId,
                    'reviewer_id' => $user->user_id,
                    'review_id' => $review->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Card approved successfully',
                    'data' => [
                        'card_id' => $cardId,
                        'status' => 'done',
                        'approved_by' => $user->username,
                        'feedback' => $feedback
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Database error during card approval", [
                    'card_id' => $cardId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error("Unexpected error in approveCard", [
                'card_id' => $cardId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while approving the card. Please try again.',
                'debug_info' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Reject card - change status back to 'in_progress'
     */
    public function rejectCard(Request $request, $cardId)
    {
        try {
            $user = Auth::user();
            $feedback = $request->input('feedback', '');

            Log::info("Card rejection request", [
                'card_id' => $cardId,
                'reviewer_id' => $user->user_id,
                'feedback_provided' => !empty($feedback),
                'timestamp' => now()
            ]);

            if (empty($feedback)) {
                Log::warning("Feedback required for card rejection", ['card_id' => $cardId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Feedback is required when rejecting a card'
                ], 400);
            }

            // Find the card and validate it exists and is in review status
            $card = Card::find($cardId);
            if (!$card) {
                Log::warning("Card not found for rejection", ['card_id' => $cardId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found'
                ], 404);
            }

            if ($card->status !== 'review') {
                Log::warning("Card not in review status for rejection", [
                    'card_id' => $cardId,
                    'current_status' => $card->status
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Card is not in review status'
                ], 400);
            }

            // Get the assigned user (submitter) from card_assignments
            $assignment = DB::table('card_assignments')
                ->where('card_id', $cardId)
                ->first();

            if (!$assignment) {
                Log::warning("No assignment found for card rejection", ['card_id' => $cardId]);
                return response()->json([
                    'success' => false,
                    'message' => 'No assignment found for this card'
                ], 404);
            }

            // Use database transaction
            DB::beginTransaction();

            try {
                // Create card review record
                $review = CardReview::create([
                    'card_id' => $cardId,
                    'reviewer_id' => $user->user_id,
                    'submitter_id' => $assignment->user_id,
                    'action' => 'reject',
                    'feedback' => $feedback,
                    'status' => 'completed'
                ]);

                // Update card status back to 'in_progress'
                $oldStatus = $card->status;
                $card->update(['status' => 'in_progress']);

                // Update assignment status back to in_progress
                DB::table('card_assignments')
                    ->where('card_id', $cardId)
                    ->update([
                        'assignment_status' => 'in_progress'
                        // Note: submitted_at column doesn't exist in card_assignments table
                    ]);

                // Log to card history
                CardHistory::create([
                    'card_id' => $cardId,
                    'user_id' => $user->user_id,
                    'action' => 'rejected',
                    'old_status' => $oldStatus,
                    'new_status' => 'in_progress',
                    'comment' => 'Card rejected by Team Lead',
                    'feedback' => $feedback,
                    'action_date' => now()
                ]);

                // Create notification (but don't let it fail the transaction)
                try {
                    $this->createApprovalNotification($cardId, $user, 'reject', $feedback);
                } catch (\Exception $e) {
                    Log::error("Failed to create rejection notification", [
                        'card_id' => $cardId,
                        'error' => $e->getMessage()
                    ]);
                }

                DB::commit();

                Log::info("Card rejected successfully", [
                    'card_id' => $cardId,
                    'reviewer_id' => $user->user_id,
                    'review_id' => $review->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Card rejected successfully',
                    'data' => [
                        'card_id' => $cardId,
                        'status' => 'in_progress',
                        'rejected_by' => $user->username,
                        'feedback' => $feedback
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Database error during card rejection", [
                    'card_id' => $cardId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error("Unexpected error in rejectCard", [
                'card_id' => $cardId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while rejecting the card. Please try again.',
                'debug_info' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Create notification for developer when card is approved/rejected
     */
    private function createApprovalNotification($cardId, $user, $action, $feedback)
    {
        try {
            // TODO: Create notification in database
            // $developerId = DB::table('card_assignments')->where('card_id', $cardId)->value('user_id');
            // DB::table('notifications')->insert([
            //     'user_id' => $developerId,
            //     'type' => 'card_' . $action,
            //     'title' => 'Card ' . ucfirst($action),
            //     'message' => $user->username . ' has ' . $action . ' your card submission',
            //     'data' => json_encode(['card_id' => $cardId, 'feedback' => $feedback]),
            //     'created_at' => now()
            // ]);

            // For now, just log the notification
            Log::info("Approval notification created for card $cardId - $action by user {$user->username}");

        } catch (\Exception $e) {
            Log::error("Error creating approval notification: " . $e->getMessage());
        }
    }

    /**
     * Create notification for developer/designer when assigned a card
     */
    private function createAssignmentNotification($cardId, $assignedUserId)
    {
        try {
            // Get card and project details
            $cardInfo = DB::table(
'
cards
'
)
                ->join(
'
boards
'
,
'
cards.board_id
'
,
'
=
'
,
'
boards.board_id
'
)
                ->join(
'
projects
'
,
'
boards.project_id
'
,
'
=
'
,
'
projects.project_id
'
)
                ->join(
'
users as creator
'
,
'
cards.created_by
'
,
'
=
'
,
'
creator.user_id
'
)
                ->where(
'
cards.card_id
'
, $cardId)
                ->select(

'
cards.card_title
'
,

'
projects.project_name
'
,

'
boards.board_name
'
,

'
creator.full_name as team_lead_name
'

                )
                ->first();

            if (!$cardInfo) {
                Log::warning("Card not found for assignment notification: $cardId");
                return;
            }

            // Create notification message
            $message = "You have been assigned a new card:
'
{$cardInfo->card_title}
'
 in project
'
{$cardInfo->project_name}
'
 by {$cardInfo->team_lead_name}";

            // Create notification in database
            DB::table(
'
notifications
'
)->insert([

'
user_id
'
 => $assignedUserId,

'
type
'
 =>
'
card_assignment
'
,

'
title
'
 =>
'
New Card Assignment
'
,

'
message
'
 => $message,

'
data
'
 => json_encode([

'
card_id
'
 => $cardId,

'
card_title
'
 => $cardInfo->card_title,

'
project_name
'
 => $cardInfo->project_name,

'
board_name
'
 => $cardInfo->board_name,

'
assigned_by
'
 => $cardInfo->team_lead_name
                ]),

'
is_read
'
 => false,

'
created_at
'
 => now()
            ]);

            Log::info("Assignment notification created for user $assignedUserId - Card: $cardId");

        } catch (\Exception $e) {
            Log::error("Error creating assignment notification: " . $e->getMessage());
        }
    }

    /**
     * Get card details with comments and time logs
     */
    public function getCardDetails($cardId)
    {
        try {
            $user = Auth::user();

            // Debug logging
            Log::info("Getting card details for card ID: $cardId, User ID: " . ($user ? $user->user_id : 'null'));

            if (!$user) {
                Log::warning("Unauthenticated user trying to access card details for card: $cardId");
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated. Please login first.',
                    'redirect' => '/login'
                ], 401);
            }

            // Validate card ID
            if (!is_numeric($cardId) || $cardId <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid card ID provided'
                ], 400);
            }

            // First, try to get just the card to test basic query
            $basicCard = DB::table('cards')->where('card_id', $cardId)->first();
            Log::info("Basic card query result: " . json_encode($basicCard));

            if (!$basicCard) {
                Log::warning("Basic card query failed for card ID: $cardId");
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found (basic query)'
                ], 404);
            }

            // Get card with project and board info
            $card = DB::table('cards as c')
                ->leftJoin('boards as b', 'c.board_id', '=', 'b.board_id')
                ->leftJoin('projects as p', 'b.project_id', '=', 'p.project_id')
                ->leftJoin('users as u', 'c.created_by', '=', 'u.user_id')
                ->where('c.card_id', $cardId)
                ->select(
                    'c.card_id',
                    'c.card_title',
                    DB::raw('COALESCE(c.description, "") as description'),
                    'c.status',
                    DB::raw('COALESCE(c.priority, "medium") as priority'),
                    'c.due_date',
                    'c.created_at',
                    'c.created_at as updated_at',
                    'c.estimated_hours',
                    'c.actual_hours',
                    DB::raw('COALESCE(c.position, 0) as position'),
                    'c.created_by',
                    DB::raw('COALESCE(b.board_name, "No Board") as board_name'),
                    DB::raw('COALESCE(p.project_name, "No Project") as project_name'),
                    DB::raw('COALESCE(u.full_name, u.username, "Unknown") as creator_name'),
                    DB::raw('COALESCE(u.username, "unknown") as creator_username')
                )
                ->first();

            if (!$card) {
                Log::warning("Complex card query failed, falling back to basic card for: $cardId");
                // Fallback to basic card data
                $card = (object) [
                    'card_id' => $basicCard->card_id,
                    'card_title' => $basicCard->card_title,
                    'description' => $basicCard->description ?? '',
                    'status' => $basicCard->status,
                    'priority' => $basicCard->priority ?? 'medium',
                    'due_date' => $basicCard->due_date,
                    'created_at' => $basicCard->created_at,
                    'updated_at' => $basicCard->created_at,
                    'estimated_hours' => $basicCard->estimated_hours,
                    'actual_hours' => $basicCard->actual_hours,
                    'position' => $basicCard->position ?? 0,
                    'created_by' => $basicCard->created_by,
                    'board_name' => 'Unknown Board',
                    'project_name' => 'Unknown Project',
                    'creator_name' => 'Unknown User',
                    'creator_username' => 'unknown'
                ];
                Log::info("Using fallback card data");
            }

            Log::info("Card found: " . json_encode($card));

            // Get comments with user details (with error handling)
            $comments = collect([]);
            try {
                $comments = DB::table('card_comments as cc')
                    ->leftJoin('users as u', 'cc.user_id', '=', 'u.user_id')
                    ->where('cc.card_id', $cardId)
                    ->select(
                        'cc.comment_id',
                        DB::raw('COALESCE(cc.comment, "") as comment_text'),
                        'cc.created_at',
                        'u.user_id',
                        DB::raw('COALESCE(u.full_name, u.username, "Unknown") as user_name'),
                        DB::raw('COALESCE(u.email, "") as user_email'),
                        DB::raw('COALESCE(u.role, "Member") as user_role')
                    )
                    ->orderBy('cc.created_at', 'asc')
                    ->get();
                Log::info("Comments loaded: " . count($comments));
            } catch (\Exception $e) {
                Log::error("Error loading comments: " . $e->getMessage());
                Log::error("Comments error details: " . $e->getTraceAsString());
            }

            // Get time logs (with error handling)
            $timeLogs = collect([]);
            try {
                $timeLogs = DB::table('time_logs as tl')
                    ->leftJoin('users as u', 'tl.user_id', '=', 'u.user_id')
                    ->where('tl.card_id', $cardId)
                    ->select(
                        'tl.log_id',
                        'tl.duration_minutes',
                        DB::raw('ROUND(tl.duration_minutes / 60.0, 2) as hours'),
                        'tl.description',
                        'tl.start_time as logged_date',
                        'tl.start_time as created_at',
                        DB::raw('COALESCE(u.username, "Unknown") as username'),
                        DB::raw('COALESCE(u.full_name, "Unknown User") as full_name')
                    )
                    ->orderBy('tl.start_time', 'desc')
                    ->get();
                Log::info("Time logs loaded: " . count($timeLogs));
            } catch (\Exception $e) {
                Log::error("Error loading time logs: " . $e->getMessage());
            }

            // Get assigned users (with error handling)
            $assignedUsers = collect([]);
            try {
                $assignedUsers = DB::table('card_assignments as ca')
                    ->leftJoin('users as u', 'ca.user_id', '=', 'u.user_id')
                    ->where('ca.card_id', $cardId)
                    ->select(
                        'u.user_id',
                        DB::raw('COALESCE(u.username, "Unknown") as username'),
                        DB::raw('COALESCE(u.full_name, "Unknown User") as full_name'),
                        DB::raw('COALESCE(u.role, "Unknown") as role'),
                        'ca.assigned_at'
                    )
                    ->get();
                Log::info("Assigned users loaded: " . count($assignedUsers));
            } catch (\Exception $e) {
                Log::error("Error loading assigned users: " . $e->getMessage());
            }

            Log::info("Successfully retrieved card details for card: $cardId, Comments: " . count($comments) . ", Time logs: " . count($timeLogs) . ", Assigned users: " . count($assignedUsers));

            return response()->json([
                'success' => true,
                'card' => $card,
                'comments' => $comments,
                'time_logs' => $timeLogs,
                'assigned_users' => $assignedUsers
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting card details for card: ' . $cardId . ' - ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error getting card details: ' . $e->getMessage(),
                'error_details' => env('APP_DEBUG') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Add comment to card
     */
    public function addCardComment(Request $request, $cardId)
    {
        try {
            $user = Auth::user();

            // Validate request
            $request->validate([
                'comment_text' => 'required|string|max:1000'
            ]);

            // Check if card exists and user has access
            $card = DB::table('cards as c')
                ->leftJoin('boards as b', 'c.board_id', '=', 'b.board_id')
                ->leftJoin('projects as p', 'b.project_id', '=', 'p.project_id')
                ->leftJoin('members as m', function($join) use ($user) {
                    $join->on('p.project_id', '=', 'm.project_id')
                         ->where('m.user_id', '=', $user->user_id);
                })
                ->leftJoin('card_assignments as ca', function($join) use ($user) {
                    $join->on('c.card_id', '=', 'ca.card_id')
                         ->where('ca.user_id', '=', $user->user_id);
                })
                ->where('c.card_id', $cardId)
                ->where(function($query) use ($user) {
                    $query->whereNotNull('m.project_id') // User is project member
                          ->orWhere('c.created_by', $user->user_id) // User created the card
                          ->orWhereNotNull('ca.assignment_id'); // User is assigned to card
                })
                ->select('c.*')
                ->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found or access denied'
                ], 404);
            }

            // Insert comment (no updated_at column in card_comments table)
            $commentId = DB::table('card_comments')->insertGetId([
                'card_id' => $cardId,
                'user_id' => $user->user_id,
                'comment' => $request->comment_text,
                'created_at' => now()
            ]);

            // Get the inserted comment with user details
            $comment = DB::table('card_comments as cc')
                ->join('users as u', 'cc.user_id', '=', 'u.user_id')
                ->where('cc.comment_id', $commentId)
                ->select(
                    'cc.comment_id',
                    'cc.comment as comment_text',
                    'cc.created_at',
                    'u.user_id',
                    'u.username',
                    'u.full_name as user_name',
                    'u.role as user_role'
                )
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => $comment
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding card comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get card comments with universal access
     * Accessible by Team Lead, Developer, Designer who has access to the card
     */
    public function getCardComments($cardId)
    {
        try {
            $user = Auth::user();

            // Check if card exists and user has access
            $card = DB::table('cards as c')
                ->leftJoin('boards as b', 'c.board_id', '=', 'b.board_id')
                ->leftJoin('projects as p', 'b.project_id', '=', 'p.project_id')
                ->leftJoin('members as m', function($join) use ($user) {
                    $join->on('p.project_id', '=', 'm.project_id')
                         ->where('m.user_id', '=', $user->user_id);
                })
                ->leftJoin('card_assignments as ca', function($join) use ($user) {
                    $join->on('c.card_id', '=', 'ca.card_id')
                         ->where('ca.user_id', '=', $user->user_id);
                })
                ->where('c.card_id', $cardId)
                ->where(function($query) {
                    $query->whereNotNull('m.member_id')  // User is project member
                          ->orWhereNotNull('ca.assignment_id'); // User is assigned to card
                })
                ->select('c.*')
                ->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found or access denied'
                ], 404);
            }

            // Get comments with user details
            $comments = DB::table('card_comments as cc')
                ->leftJoin('users as u', 'cc.user_id', '=', 'u.user_id')
                ->where('cc.card_id', $cardId)
                ->select([
                    'cc.comment_id',
                    DB::raw('COALESCE(cc.comment_text, cc.comment, "") as comment_text'),
                    'cc.created_at',
                    DB::raw('COALESCE(cc.updated_at, cc.created_at) as updated_at'),
                    'u.user_id',
                    DB::raw('COALESCE(u.full_name, u.username, "Unknown") as user_name'),
                    DB::raw('COALESCE(u.email, "") as user_email'),
                    DB::raw('COALESCE(u.role, "Member") as user_role')
                ])
                ->orderBy('cc.created_at', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching card comments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching comments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get assigned history for TeamLead
     */
    public function getAssignedHistory()
    {
        try {
            $user = Auth::user();

            // Get all card histories for cards in projects where user is team lead
            $histories = DB::table('card_history as ch')
                ->join('cards as c', 'ch.card_id', '=', 'c.card_id')
                ->join('boards as b', 'c.board_id', '=', 'b.board_id')
                ->join('projects as p', 'b.project_id', '=', 'p.project_id')
                ->join('users as u', 'ch.user_id', '=', 'u.user_id')
                ->join('project_members as pm', function($join) use ($user) {
                    $join->on('pm.project_id', '=', 'p.project_id')
                         ->where('pm.user_id', '=', $user->user_id)
                         ->where('pm.role', '=', 'Team_Lead');
                })
                ->select([
                    'ch.history_id',
                    'ch.action',
                    'ch.old_status',
                    'ch.new_status',
                    'ch.comment',
                    'ch.feedback',
                    'ch.action_date',
                    'c.card_title as title',
                    'c.estimated_hours',
                    'c.priority',
                    'c.status as current_status',
                    'p.project_name',
                    'b.board_name',
                    'u.full_name as assigned_to',
                    'u.username as assigned_username',
                    'u.user_id',
                    'p.project_id'
                ])
                ->orderBy('ch.action_date', 'desc')
                ->limit(100)
                ->get();

            // Transform data for UI
            $assignmentHistory = $histories->map(function($history) {
                return [
                    'history_id' => $history->history_id,
                    'title' => $history->title,
                    'board_name' => $history->board_name,
                    'project_name' => $history->project_name,
                    'project_id' => $history->project_id,
                    'assigned_to' => $history->assigned_to,
                    'assigned_username' => $history->assigned_username,
                    'user_id' => $history->user_id,
                    'action' => $history->action,
                    'old_status' => $history->old_status,
                    'new_status' => $history->new_status,
                    'assignment_status' => $history->current_status,
                    'priority' => $history->priority,
                    'estimated_hours' => $history->estimated_hours,
                    'assigned_at' => $history->action_date,
                    'completed_at' => $history->new_status === 'done' ? $history->action_date : null,
                    'feedback' => $history->feedback
                ];
            });

            // Calculate summary statistics
            $totalCount = $assignmentHistory->count();
            $submittedCount = $assignmentHistory->where('action', 'submitted')->count();
            $approvedCount = $assignmentHistory->where('action', 'approved')->count();
            $rejectedCount = $assignmentHistory->where('action', 'rejected')->count();

            return response()->json([
                'success' => true,
                'assignment_history' => $assignmentHistory,
                'summary' => [
                    'total_assignments' => $totalCount,
                    'submitted_count' => $submittedCount,
                    'approved_count' => $approvedCount,
                    'rejected_count' => $rejectedCount
                ],
                'pagination' => [
                    'current_page' => 1,
                    'total_pages' => 1,
                    'total_items' => $totalCount
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching assigned history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching assigned history: ' . $e->getMessage()
            ], 500);
        }
    }
}

