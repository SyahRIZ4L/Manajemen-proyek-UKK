<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Admin Dashboard - Minimal & Clean
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Simple admin check
        $adminEmails = [
            'admin@test.com',
            'admin@example.com',
            'syahrizal@admin.com'
        ];

        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'Access denied');
        }

        // Minimal dashboard data
        $stats = [
            'projects' => 8,
            'members' => 12,
            'tasks' => 35,
            'completed' => 28
        ];

        return view('admin.dashboard', compact('stats', 'user'));
    }

    /**
     * Projects Management
     */
    public function projects()
    {
        $user = Auth::user();

        $adminEmails = ['admin@test.com', 'admin@example.com', 'syahrizal@admin.com'];
        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'Access denied');
        }

        // Sample projects data
        $projects = [
            ['id' => 1, 'name' => 'Website Project', 'status' => 'Active', 'progress' => 75],
            ['id' => 2, 'name' => 'Mobile App', 'status' => 'Planning', 'progress' => 25],
            ['id' => 3, 'name' => 'API Development', 'status' => 'Completed', 'progress' => 100],
        ];

        return view('admin.projects', compact('projects', 'user'));
    }

    /**
     * Admin Panel - Full Control Interface
     */
    public function panel()
    {
        $user = Auth::user();

        // Simple admin check
        $adminEmails = [
            'admin@test.com',
            'admin@example.com',
            'syahrizal@admin.com'
        ];

        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'Access denied - Admin only');
        }

        return view('admin.panel', compact('user'));
    }

    /**
     * Users Management
     */
    public function users()
    {
        $user = Auth::user();

        $adminEmails = ['admin@test.com', 'admin@example.com', 'syahrizal@admin.com'];
        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'Access denied');
        }

        // Sample users data
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Developer'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'Team_Lead'],
            ['id' => 3, 'name' => 'Mike Johnson', 'email' => 'mike@example.com', 'role' => 'Member'],
        ];

        return view('admin.users', compact('users', 'user'));
    }

    /**
     * Check Team Lead availability and handle project assignment
     */
    public function checkTeamLeadAvailability(Request $request)
    {
        $user = Auth::user();

        // Admin check
        $adminEmails = ['admin@test.com', 'admin@example.com', 'syahrizal@admin.com'];
        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied - Admin only'
            ], 403);
        }

        $teamLeadId = $request->input('team_lead_id');
        $projectId = $request->input('project_id');

        // Check if Team Lead exists and get current status
        $teamLead = DB::table('users')
            ->where('user_id', $teamLeadId)
            ->where('role', 'Team_Lead')
            ->first();

        if (!$teamLead) {
            return response()->json([
                'success' => false,
                'message' => 'Team Lead not found'
            ], 404);
        }

        // Check current project assignment
        $currentProject = DB::table('members')
            ->join('projects', 'members.project_id', '=', 'projects.project_id')
            ->where('members.user_id', $teamLeadId)
            ->where('members.role', 'Team_Lead')
            ->where('projects.status', '!=', 'completed')
            ->select('projects.*', 'members.joined_at')
            ->first();

        $availability = [
            'team_lead' => [
                'user_id' => $teamLead->user_id,
                'full_name' => $teamLead->full_name,
                'username' => $teamLead->username,
                'current_task_status' => $teamLead->current_task_status
            ],
            'is_available' => !$currentProject,
            'current_project' => $currentProject,
            'can_assign' => !$currentProject || $currentProject->project_id == $projectId
        ];

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    /**
     * Assign Team Lead to project with status management
     */
    public function assignTeamLeadToProject(Request $request)
    {
        $user = Auth::user();

        // Admin check
        $adminEmails = ['admin@test.com', 'admin@example.com', 'syahrizal@admin.com'];
        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied - Admin only'
            ], 403);
        }

        $request->validate([
            'team_lead_id' => 'required|integer',
            'project_id' => 'required|integer',
            'force_assign' => 'boolean'
        ]);

        $teamLeadId = $request->input('team_lead_id');
        $projectId = $request->input('project_id');
        $forceAssign = $request->input('force_assign', false);

        try {
            DB::beginTransaction();

            // Check Team Lead availability
            $teamLead = DB::table('users')
                ->where('user_id', $teamLeadId)
                ->where('role', 'Team_Lead')
                ->first();

            if (!$teamLead) {
                throw new \Exception('Team Lead not found');
            }

            // Check current project assignment
            $currentProject = DB::table('members')
                ->join('projects', 'members.project_id', '=', 'projects.project_id')
                ->where('members.user_id', $teamLeadId)
                ->where('members.role', 'Team_Lead')
                ->where('projects.status', '!=', 'completed')
                ->first();

            // If Team Lead is busy and not forcing assignment
            if ($currentProject && !$forceAssign) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Team Lead is currently assigned to another active project',
                    'current_project' => [
                        'project_id' => $currentProject->project_id,
                        'project_name' => $currentProject->project_name,
                        'status' => $currentProject->status
                    ],
                    'requires_confirmation' => true
                ], 409);
            }

            // Remove from current project if forcing assignment
            if ($currentProject && $forceAssign) {
                DB::table('members')
                    ->where('user_id', $teamLeadId)
                    ->where('project_id', $currentProject->project_id)
                    ->where('role', 'Team_Lead')
                    ->delete();
            }

            // Check if already assigned to this project
            $existingAssignment = DB::table('members')
                ->where('user_id', $teamLeadId)
                ->where('project_id', $projectId)
                ->where('role', 'Team_Lead')
                ->first();

            if (!$existingAssignment) {
                // Assign to new project
                DB::table('members')->insert([
                    'project_id' => $projectId,
                    'user_id' => $teamLeadId,
                    'role' => 'Team_Lead',
                    'joined_at' => now()
                ]);
            }

            // Update Team Lead status to 'working'
            DB::table('users')
                ->where('user_id', $teamLeadId)
                ->update([
                    'current_task_status' => 'working',
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Team Lead successfully assigned to project',
                'data' => [
                    'team_lead_id' => $teamLeadId,
                    'project_id' => $projectId,
                    'status' => 'working'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign Team Lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove Team Lead from project and update status
     */
    public function removeTeamLeadFromProject(Request $request)
    {
        $user = Auth::user();

        // Admin check
        $adminEmails = ['admin@test.com', 'admin@example.com', 'syahrizal@admin.com'];
        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied - Admin only'
            ], 403);
        }

        $request->validate([
            'team_lead_id' => 'required|integer',
            'project_id' => 'required|integer'
        ]);

        $teamLeadId = $request->input('team_lead_id');
        $projectId = $request->input('project_id');

        try {
            DB::beginTransaction();

            // Remove from project
            $removed = DB::table('members')
                ->where('user_id', $teamLeadId)
                ->where('project_id', $projectId)
                ->where('role', 'Team_Lead')
                ->delete();

            if (!$removed) {
                throw new \Exception('Team Lead assignment not found');
            }

            // Check if Team Lead has other active project assignments
            $otherProjects = DB::table('members')
                ->join('projects', 'members.project_id', '=', 'projects.project_id')
                ->where('members.user_id', $teamLeadId)
                ->where('members.role', 'Team_Lead')
                ->where('projects.status', '!=', 'completed')
                ->count();

            // Update status to idle if no other active projects
            if ($otherProjects == 0) {
                DB::table('users')
                    ->where('user_id', $teamLeadId)
                    ->update([
                        'current_task_status' => 'idle',
                        'updated_at' => now()
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Team Lead removed from project successfully',
                'data' => [
                    'team_lead_id' => $teamLeadId,
                    'project_id' => $projectId,
                    'new_status' => $otherProjects == 0 ? 'idle' : 'working'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove Team Lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available Team Leads for project assignment
     */
    public function getAvailableTeamLeads()
    {
        $user = Auth::user();

        // Admin check
        $adminEmails = ['admin@test.com', 'admin@example.com', 'syahrizal@admin.com'];
        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied - Admin only'
            ], 403);
        }

        $teamLeads = DB::table('users')
            ->leftJoin(DB::raw('(SELECT m.user_id, COUNT(*) as active_projects,
                GROUP_CONCAT(p.project_name SEPARATOR ", ") as project_names
                FROM members m
                JOIN projects p ON m.project_id = p.project_id
                WHERE m.role = "Team_Lead" AND p.status != "completed"
                GROUP BY m.user_id) as active_assignments'),
                'users.user_id', '=', 'active_assignments.user_id')
            ->where('users.role', 'Team_Lead')
            ->select(
                'users.user_id',
                'users.username',
                'users.full_name',
                'users.email',
                'users.current_task_status',
                DB::raw('COALESCE(active_assignments.active_projects, 0) as active_projects'),
                DB::raw('COALESCE(active_assignments.project_names, "") as current_projects')
            )
            ->orderBy('users.current_task_status') // idle first
            ->orderBy('users.full_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $teamLeads
        ]);
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Simple admin check
        $adminEmails = [
            'admin@test.com',
            'admin@example.com',
            'syahrizal@admin.com'
        ];

        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return response()->json(['success' => false, 'message' => 'Access denied - Admin only'], 403);
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'bio' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'website' => 'nullable|url|max:255',
            'skills' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'username' => $request->username,
            'bio' => $request->bio,
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'website' => $request->website,
            'skills' => $request->skills ? explode(',', $request->skills) : null,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        DB::table('users')->where('user_id', $user->user_id)->update($updateData);

        // Get updated user data
        $updatedUser = DB::table('users')->where('user_id', $user->user_id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
            'user' => $updatedUser
        ]);
    }
}
