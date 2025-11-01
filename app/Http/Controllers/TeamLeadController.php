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
        // Simplified middleware - hanya cek role Team_Lead
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
        $user = Auth::user();

        // Get basic data without complex permission checks
        $teamLeadProjects = $this->getTeamLeadProjects($user);
        $stats = $this->getTeamStats($user);

        return view('teamlead.dashboard', compact(
            'user',
            'teamLeadProjects',
            'stats'
        ));
    }

    /**
     * Task Assignment Interface
     */
    public function tasks()
    {
        $user = Auth::user();
        $projects = $this->getTeamLeadProjects($user);
        $tasks = $this->getTeamTasks($user);
        $teamMembers = $this->getTeamMembers($user);

        return view('teamlead.tasks.index', compact(
            'projects',
            'tasks',
            'teamMembers'
        ));
    }

    /**
     * Create new task
     */
    public function createTask()
    {
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
        $user = Auth::user();

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,project_id',
            'board_id' => 'required|exists:boards,board_id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,user_id',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date|after:today'
        ]);

        try {
            $taskId = DB::table('cards')->insertGetId([
                'board_id' => $validated['board_id'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? '',
                'priority' => $validated['priority'],
                'status' => 'pending',
                'assigned_to' => $validated['assigned_to'],
                'assigned_by' => $user->user_id,
                'due_date' => $validated['due_date'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if ($validated['assigned_to']) {
                DB::table('card_assignments')->insert([
                    'card_id' => $taskId,
                    'user_id' => $validated['assigned_to'],
                    'assigned_by' => $user->user_id,
                    'assigned_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return redirect()->route('teamlead.tasks')->with('success', 'Task berhasil dibuat.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper Methods - Simplified without complex checks
     */
    private function getTeamLeadProjects($user)
    {
        return DB::table('projects')
            ->join('project_members', 'projects.project_id', '=', 'project_members.project_id')
            ->where('project_members.user_id', $user->user_id)
            ->where('project_members.role', 'Team Lead')
            ->select('projects.*')
            ->get();
    }

    private function getTeamTasks($user)
    {
        $projectIds = $this->getTeamLeadProjects($user)->pluck('project_id');

        return DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->whereIn('boards.project_id', $projectIds)
            ->select('cards.*', 'boards.project_id')
            ->orderBy('cards.priority', 'desc')
            ->orderBy('cards.due_date', 'asc')
            ->get();
    }

    private function getTeamMembers($user)
    {
        $projectIds = $this->getTeamLeadProjects($user)->pluck('project_id');

        return DB::table('users')
            ->join('project_members', 'users.user_id', '=', 'project_members.user_id')
            ->whereIn('project_members.project_id', $projectIds)
            ->where('users.user_id', '!=', $user->user_id)
            ->select('users.*', 'project_members.role', 'project_members.project_id')
            ->distinct()
            ->get();
    }

    private function getTeamStats($user)
    {
        $projectIds = collect($this->getTeamLeadProjects($user))->pluck('project_id');

        return [
            'total_tasks' => DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->whereIn('boards.project_id', $projectIds)
                ->count(),
            'completed_tasks' => DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->whereIn('boards.project_id', $projectIds)
                ->where('cards.status', 'completed')
                ->count(),
            'team_members' => DB::table('project_members')
                ->whereIn('project_id', $projectIds)
                ->count(),
            'active_projects' => $projectIds->count()
        ];
    }
}
