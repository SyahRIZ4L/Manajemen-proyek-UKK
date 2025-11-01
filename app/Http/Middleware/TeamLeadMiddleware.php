<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TeamLeadMiddleware
{
    /**
     * Handle an incoming request for Team Lead role validation
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Auth::user();

        // Check if user has Team Lead role
        if (!$this->hasTeamLeadRole($user)) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki hak akses Team Lead.');
        }

        return $next($request);
    }

    /**
     * Check if user has Team Lead role
     */
    private function hasTeamLeadRole($user): bool
    {
        // Team Lead emails (can be configured)
        $teamLeadEmails = [
            'teamlead@example.com',
            'lead@company.com',
            'tl@project.com'
        ];

        // Check by email first
        if (in_array($user->email, $teamLeadEmails)) {
            return true;
        }

        // Check by role field in users table
        if (isset($user->role) && $user->role === 'Team Lead') {
            return true;
        }

        // Check by project_members table for Team Lead role in any project
        if (method_exists($user, 'projectMembers')) {
            $hasTeamLeadRole = $user->projectMembers()
                ->where('role', 'Team Lead')
                ->exists();

            if ($hasTeamLeadRole) {
                return true;
            }
        }

        // Check user permissions/roles table if exists
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('Team Lead');
        }

        return false;
    }

    /**
     * Check if user has Team Lead permissions for specific actions
     */
    public static function userHasTeamLeadPermission($user, $permission): bool
    {
        if (!$user) {
            return false;
        }

        // Team Lead permissions
        $teamLeadPermissions = [
            // Task Management
            'assign_tasks' => true,
            'set_task_priority' => true,
            'update_task_status' => true,
            'view_all_progress' => true,
            'distribute_tasks' => true,
            'coordinate_team' => true,
            'review_work' => true,
            'solve_blockers' => true,

            // View permissions
            'view_team_dashboard' => true,
            'view_team_tasks' => true,
            'view_team_progress' => true,
            'view_project_overview' => true,

            // Limited permissions
            'create_tasks' => true,
            'edit_own_tasks' => true,
            'edit_team_tasks' => true,
            'comment_on_tasks' => true,
            'log_time' => true,

            // Restrictions - Team Lead CANNOT do these
            'delete_project' => false,
            'remove_team_members' => false,
            'delete_historical_data' => false,
            'modify_project_budget' => false,
            'add_project_members' => false, // Only admin/PM can add
            'change_project_manager' => false,
        ];

        return $teamLeadPermissions[$permission] ?? false;
    }

    /**
     * Get all Team Lead permissions
     */
    public static function getTeamLeadPermissions(): array
    {
        return [
            'task_management' => [
                'assign_tasks',
                'set_task_priority',
                'update_task_status',
                'view_all_progress',
                'distribute_tasks',
                'coordinate_team',
                'review_work',
                'solve_blockers'
            ],
            'team_coordination' => [
                'view_team_dashboard',
                'view_team_tasks',
                'view_team_progress',
                'view_project_overview'
            ],
            'restrictions' => [
                'delete_project',
                'remove_team_members',
                'delete_historical_data',
                'modify_project_budget',
                'add_project_members',
                'change_project_manager'
            ]
        ];
    }

    /**
     * Check if user can access specific project as Team Lead
     */
    public static function canAccessProject($user, $projectId): bool
    {
        if (!$user) {
            return false;
        }

        // Check if user is Team Lead in this specific project
        if (method_exists($user, 'projectMembers')) {
            return $user->projectMembers()
                ->where('project_id', $projectId)
                ->where('role', 'Team Lead')
                ->exists();
        }

        return false;
    }

    /**
     * Get projects where user is Team Lead
     */
    public static function getTeamLeadProjects($user): array
    {
        if (!$user || !method_exists($user, 'projectMembers')) {
            return [];
        }

        return $user->projectMembers()
            ->where('role', 'Team Lead')
            ->with('project')
            ->get()
            ->pluck('project')
            ->toArray();
    }
}
