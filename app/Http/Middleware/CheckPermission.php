<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request - SIMPLIFIED
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        // SIMPLIFIED PERMISSION CHECK - BERDASARKAN ROLE SAJA
        if (!$this->userHasBasicAccess($user)) {
            abort(403, "Akses ditolak.");
        }

        return $next($request);
    }

    /**
     * Basic access check - hanya cek apakah user punya role yang valid
     */
    private function userHasBasicAccess($user)
    {
        $validRoles = ['Project_Admin', 'Team_Lead', 'Developer', 'Designer', 'Member'];
        return in_array($user->role, $validRoles);
    }

    /**
     * Static method untuk check permission - SIMPLIFIED
     */
    public static function hasPermission($user, $permission)
    {
        // SIMPLIFIED - Langsung return true untuk user dengan role valid
        $validRoles = ['Project_Admin', 'Team_Lead', 'Developer', 'Designer', 'Member'];
        return in_array($user->role, $validRoles);
    }

    /**
     * Get user permissions - BASIC PERMISSIONS ONLY
     */
    public static function getUserPermissions($user)
    {
        switch ($user->role) {
            case 'Project_Admin':
                return [
                    'full_system_access',
                    'manage_projects',
                    'manage_users',
                    'view_all_data'
                ];

            case 'Team_Lead':
                return [
                    'view_team_progress',
                    'assign_tasks_to_team',
                    'coordinate_team',
                    'manage_team_tasks'
                ];

            case 'Developer':
                return [
                    'manage_code',
                    'view_assigned_tasks',
                    'update_task_progress'
                ];

            case 'Designer':
                return [
                    'manage_design',
                    'view_assigned_tasks',
                    'create_mockups'
                ];

            case 'Member':
                return [
                    'view_assigned_tasks',
                    'update_own_tasks'
                ];

            default:
                return [];
        }
    }
}
