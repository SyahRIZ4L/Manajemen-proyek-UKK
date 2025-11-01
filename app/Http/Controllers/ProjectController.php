<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $project = Project::with(['creator', 'members.user'])
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
        ]);

        try {
            $project = Project::findOrFail($id);

            $project->update([
                'project_name' => $request->project_name,
                'description' => $request->description,
                'deadline' => $request->deadline,
            ]);

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

            $members = $project->members()->with('user')->get();

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
}
