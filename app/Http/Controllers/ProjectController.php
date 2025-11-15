<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index()
    {
        $projects = Project::with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date|after:today',
        ]);

        try {
            DB::beginTransaction();

            $project = Project::create([
                'project_name' => $request->project_name,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'deadline' => $request->deadline,
            ]);

            // Set created_at manually karena tidak menggunakan timestamps Laravel
            DB::table('projects')
                ->where('project_id', $project->project_id)
                ->update(['created_at' => now()]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Project created successfully!',
                'data' => $project->load('creator')
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified project.
     */
    public function show($id)
    {
        $project = Project::with(['creator', 'members.user', 'completedByUser', 'cancelledByUser'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'project_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'nullable|in:Planning,In Progress,On Hold,Completed'
        ]);

        try {
            $project = Project::findOrFail($id);
            $oldStatus = $project->status;
            $oldDeadline = $project->deadline;

            $project->update([
                'project_name' => $request->project_name,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'status' => $request->status ?? $project->status,
            ]);

            // Send notification for status change (only if status actually changed)
            if ($request->status && $oldStatus !== $request->status) {
                Notification::createStatusChangeNotification(
                    $project->project_id,
                    Auth::id(),
                    $oldStatus,
                    $request->status
                );
            }

            // Send notification for deadline change (only if deadline actually changed)
            if ($request->deadline && $oldDeadline !== $request->deadline) {
                Notification::createTaskUpdateNotification(
                    $project->project_id,
                    Auth::id(),
                    "Project deadline updated to " . date('Y-m-d', strtotime($request->deadline))
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully!',
                'data' => $project->load('creator')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get project statistics for dashboard
     */
    public function getStatistics()
    {
        $totalProjects = Project::count();
        $nearDeadline = Project::whereNotNull('deadline')
            ->whereDate('deadline', '<=', now()->addDays(7))
            ->whereDate('deadline', '>=', now())
            ->count();
        $recentProjects = Project::whereDate('created_at', '>=', now()->subDays(7))->count();

        // Get total team members (distinct users in members table)
        $totalMembers = DB::table('members')
            ->distinct('user_id')
            ->count('user_id');

        return response()->json([
            'success' => true,
            'data' => [
                'total_projects' => $totalProjects,
                'near_deadline' => $nearDeadline,
                'recent_projects' => $recentProjects,
                'total_members' => $totalMembers ?: 0
            ]
        ]);
    }

    /**
     * Get project members
     */
    public function getMembers($id)
    {
        try {
            $project = Project::findOrFail($id);

            // Get members with fresh user role data
            $members = $project->members()->with('user')->get()->map(function ($member) {
                // Use user's current system role instead of stored member role
                $member->role = $member->user->role;
                return $member;
            });

            return response()->json([
                'success' => true,
                'data' => $members
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get project members: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get project board statistics
     */
    public function getBoardStats($id)
    {
        try {
            $project = Project::findOrFail($id);

            // Get card statistics from boards and cards related to this project
            $boardStats = DB::table('boards')
                ->leftJoin('cards', 'boards.board_id', '=', 'cards.board_id')
                ->where('boards.project_id', $id)
                ->select('cards.status', DB::raw('count(cards.card_id) as count'))
                ->groupBy('cards.status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // Ensure all statuses are present with default 0
            $stats = [
                'todo' => $boardStats['todo'] ?? 0,
                'in_progress' => $boardStats['in_progress'] ?? 0,
                'review' => $boardStats['review'] ?? 0,
                'done' => $boardStats['done'] ?? 0,
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => true, // Return success but with zero stats
                'data' => [
                    'todo' => 0,
                    'in_progress' => 0,
                    'review' => 0,
                    'done' => 0,
                ]
            ]);
        }
    }

    /**
     * Add member to project
     */
    public function addMember(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id'
        ]);

        try {
            $project = Project::findOrFail($id);

            // Check if project is completed
            if ($project->status === 'Completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add members to completed project'
                ], 400);
            }

            // Get the user to add
            $user = User::findOrFail($request->user_id);

            // Check if user is already a member
            $existingMember = $project->members()->where('user_id', $request->user_id)->first();
            if ($existingMember) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already a member of this project'
                ], 400);
            }

            // Add member with their system role
            $project->members()->create([
                'user_id' => $request->user_id,
                'role' => $user->role, // Use user's system role
                'joined_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Member added successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add member: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove member from project
     */
    public function removeMember($id, $memberId)
    {
        try {
            $project = Project::findOrFail($id);

            // Check if project is completed
            if ($project->status === 'Completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove members from completed project'
                ], 400);
            }

            $member = $project->members()->findOrFail($memberId);

            $member->delete();

            return response()->json([
                'success' => true,
                'message' => 'Member removed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove member: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update member role
     */
    public function updateMemberRole(Request $request, $id, $memberId)
    {
        $request->validate([
            'role' => 'required|in:member,team_lead'
        ]);

        try {
            $project = Project::findOrFail($id);
            $member = $project->members()->findOrFail($memberId);

            $member->update([
                'role' => $request->role
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Member role updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update member role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available users to add as members
     */
    public function getAvailableUsers($id)
    {
        try {
            $project = Project::findOrFail($id);

            // Get users who are not already members of this project
            $existingMemberIds = $project->members()->pluck('user_id')->toArray();

            $availableUsers = User::whereNotIn('user_id', $existingMemberIds)
                ->where('user_id', '!=', $project->created_by) // Exclude project creator
                ->select('user_id', 'full_name', 'email', 'role')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $availableUsers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get available users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete a project
     */
    public function completeProject(Request $request, $id)
    {
        try {
            // Check if user has permission
            if (!Gate::allows('manage-projects')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only Project Admin and Team Lead can complete projects.'
                ], 403);
            }

            $project = Project::findOrFail($id);

            // Check if project is already completed or cancelled
            if (in_array($project->status, ['Completed', 'On Hold'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project is already completed or cancelled.'
                ], 400);
            }

            // Validate completion notes if provided
            $request->validate([
                'completion_notes' => 'nullable|string|max:1000'
            ]);

            $project->update([
                'status' => 'Completed',
                'completed_at' => now(),
                'completed_by' => Auth::id(),
                'completion_notes' => $request->completion_notes,
                'updated_at' => now()
            ]);

            // Create notification for project completion
            Notification::createStatusChangeNotification(
                $project->project_id,
                Auth::id(),
                'In Progress',
                'Completed'
            );

            // Update Team Lead status when project is completed
            $this->updateTeamLeadStatusOnCompletion($project->project_id);

            // Load relationships for response
            $project->load(['creator', 'completedByUser']);

            return response()->json([
                'success' => true,
                'message' => 'Project completed successfully.',
                'data' => $project
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a project
     */
    public function cancelProject(Request $request, $id)
    {
        try {
            // Check if user has permission
            if (!Gate::allows('manage-projects')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only Project Admin and Team Lead can cancel projects.'
                ], 403);
            }

            $project = Project::findOrFail($id);

            // Check if project is already completed or cancelled
            if (in_array($project->status, ['Completed', 'On Hold'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project is already completed or cancelled.'
                ], 400);
            }

            // Validate cancellation reason
            $request->validate([
                'cancellation_reason' => 'required|string|max:1000'
            ]);

            $project->update([
                'status' => 'On Hold',
                'cancelled_at' => now(),
                'cancelled_by' => Auth::id(),
                'cancellation_reason' => $request->cancellation_reason,
                'updated_at' => now()
            ]);

            // Load relationships for response
            $project->load(['creator', 'cancelledByUser']);

            return response()->json([
                'success' => true,
                'message' => 'Project cancelled successfully.',
                'data' => $project
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reactivate a cancelled project
     */
    public function reactivateProject($id)
    {
        try {
            // Check if user has permission
            if (!Gate::allows('manage-projects')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only Project Admin and Team Lead can reactivate projects.'
                ], 403);
            }

            $project = Project::findOrFail($id);

            // Check if project is cancelled
            if ($project->status !== 'On Hold') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only cancelled projects can be reactivated.'
                ], 400);
            }

            $project->update([
                'status' => 'In Progress',
                'cancelled_at' => null,
                'cancelled_by' => null,
                'cancellation_reason' => null,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Project reactivated successfully.',
                'data' => $project
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reactivate project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Team Lead status when project is completed
     */
    private function updateTeamLeadStatusOnCompletion($projectId)
    {
        try {
            // Get Team Lead from this project
            $teamLead = DB::table('members')
                ->where('project_id', $projectId)
                ->where('role', 'Team_Lead')
                ->first();

            if (!$teamLead) {
                return; // No Team Lead found for this project
            }

            // Check if Team Lead has other active projects
            $otherActiveProjects = DB::table('members')
                ->join('projects', 'members.project_id', '=', 'projects.project_id')
                ->where('members.user_id', $teamLead->user_id)
                ->where('members.role', 'Team_Lead')
                ->where('projects.project_id', '!=', $projectId)
                ->where('projects.status', '!=', 'Completed')
                ->count();

            // If no other active projects, set status to idle
            if ($otherActiveProjects == 0) {
                DB::table('users')
                    ->where('user_id', $teamLead->user_id)
                    ->update([
                        'current_task_status' => 'idle',
                        'updated_at' => now()
                    ]);

                // Create notification for Team Lead status change
                if (class_exists('App\Models\Notification')) {
                    Notification::create([
                        'user_id' => $teamLead->user_id,
                        'type' => 'status_change',
                        'title' => 'Status Updated to Idle',
                        'message' => 'Your status has been updated to idle as all assigned projects are completed.',
                        'is_read' => false,
                        'created_at' => now()
                    ]);
                }
            }

        } catch (\Exception $e) {
            // Log error but don't break project completion
            Log::warning('Failed to update Team Lead status on project completion: ' . $e->getMessage());
        }
    }
}
