<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\ProjectMember;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getStatistics()
    {
        try {
            $totalProjects = Project::count();
            $completedProjects = Project::where('status', 'Completed')->count();
            $inProgressProjects = Project::where('status', 'In Progress')->count();
            $activeUsers = User::count();

            // Monthly data
            $currentMonth = Carbon::now()->startOfMonth();
            $projectsThisMonth = Project::where('created_at', '>=', $currentMonth)->count();
            $completedThisMonth = Project::where('status', 'Completed')
                ->where('completed_at', '>=', $currentMonth)
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_projects' => $totalProjects,
                    'completed_projects' => $completedProjects,
                    'in_progress_projects' => $inProgressProjects,
                    'active_users' => $activeUsers,
                    'projects_this_month' => $projectsThisMonth,
                    'completed_this_month' => $completedThisMonth
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProjectReport(Request $request)
    {
        try {
            $query = Project::with(['members.user']);

            // Apply filters
            if ($request->filled('date_from')) {
                $query->where('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('created_at', '<=', $request->date_to);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $projects = $query->orderBy('created_at', 'desc')
                ->paginate(10)
                ->through(function ($project) {
                    return [
                        'project_id' => $project->project_id,
                        'project_name' => $project->project_name,
                        'description' => $project->description,
                        'status' => $project->status,
                        'start_date' => $project->start_date,
                        'deadline' => $project->deadline,
                        'created_at' => $project->created_at,
                        'completed_at' => $project->completed_at,
                        'member_count' => $project->members->count(),
                        'duration_days' => $project->start_date && $project->deadline ?
                            Carbon::parse($project->start_date)->diffInDays(Carbon::parse($project->deadline)) : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving project report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUserReport(Request $request)
    {
        try {
            $query = User::with(['createdProjects', 'projectMemberships.project']);

            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            $users = $query->orderBy('created_at', 'desc')
                ->paginate(10)
                ->through(function ($user) {
                    return [
                        'user_id' => $user->user_id,
                        'username' => $user->username,
                        'full_name' => $user->full_name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'created_at' => $user->created_at,
                        'current_task_status' => $user->current_task_status,
                        'projects_created' => $user->createdProjects->count(),
                        'projects_member' => $user->projectMemberships->count(),
                        'active_projects' => $user->projectMemberships->where('project.status', 'In Progress')->count()
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving user report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTimelineReport(Request $request)
    {
        try {
            $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

            // Project creation timeline
            $projectTimeline = Project::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('"created" as type')
                )
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('date')
                ->orderBy('date');

            // Project completion timeline
            $completionTimeline = Project::select(
                    DB::raw('DATE(completed_at) as date'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('"completed" as type')
                )
                ->whereNotNull('completed_at')
                ->whereBetween('completed_at', [$dateFrom, $dateTo])
                ->groupBy('date')
                ->orderBy('date');

            $timeline = $projectTimeline->union($completionTimeline)->get();

            return response()->json([
                'success' => true,
                'data' => $timeline
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving timeline report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportReport(Request $request)
    {
        try {
            $type = $request->get('type', 'projects');
            $filename = $type . '_report_' . Carbon::now()->format('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($type, $request) {
                $file = fopen('php://output', 'w');

                if ($type === 'projects') {
                    fputcsv($file, ['Project ID', 'Name', 'Status', 'Start Date', 'Deadline', 'Members', 'Created At']);

                    $projects = Project::with('members');
                    if ($request->filled('status')) {
                        $projects->where('status', $request->status);
                    }

                    $projects->chunk(100, function ($projects) use ($file) {
                        foreach ($projects as $project) {
                            fputcsv($file, [
                                $project->project_id,
                                $project->project_name,
                                $project->status,
                                $project->start_date,
                                $project->deadline,
                                $project->members->count(),
                                $project->created_at
                            ]);
                        }
                    });
                } elseif ($type === 'users') {
                    fputcsv($file, ['User ID', 'Username', 'Full Name', 'Email', 'Role', 'Created At']);

                    $users = User::query();
                    if ($request->filled('role')) {
                        $users->where('role', $request->role);
                    }

                    $users->chunk(100, function ($users) use ($file) {
                        foreach ($users as $user) {
                            fputcsv($file, [
                                $user->user_id,
                                $user->username,
                                $user->full_name,
                                $user->email,
                                $user->role,
                                $user->created_at
                            ]);
                        }
                    });
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting report: ' . $e->getMessage()
            ], 500);
        }
    }
}
