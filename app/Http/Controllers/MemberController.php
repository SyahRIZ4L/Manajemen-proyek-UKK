<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckPermission;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'member') {
                return redirect()->route('home')->with('error', 'Akses ditolak. Anda bukan Member.');
            }
            return $next($request);
        });
    }

    /**
     * Member Dashboard
     */
    public function dashboard()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'view_own_tasks')) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki permission untuk melihat dashboard.');
        }

        $user = Auth::user();
        $userRole = 'Team Member';

        // Get assigned tasks
        $assignedTasks = $this->getAssignedTasks($user);

        // Get basic statistics
        $taskStats = $this->getTaskStats($user);

        // Get recent activities
        $recentActivities = $this->getRecentActivities($user);

        // Get time logs
        $timeSpent = $this->getTimeSpent($user);

        return view('member.dashboard', compact(
            'user',
            'userRole',
            'assignedTasks',
            'taskStats',
            'recentActivities',
            'timeSpent'
        ));
    }

    /**
     * My Tasks page
     */
    public function myTasks()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'view_own_tasks')) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $tasks = $this->getAssignedTasks($user);

        return view('member.tasks', compact('tasks'));
    }

    /**
     * Update own task status (limited options)
     */
    public function updateTaskStatus(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'update_own_task_status')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $validated = $request->validate([
            'task_id' => 'required|exists:cards,id',
            'status' => 'required|in:in_progress,completed' // Limited status options for members
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
            'comment' => 'required|string|max:500' // Limited comment length for members
        ]);

        try {
            $user = Auth::user();

            // Verify user has access to this task
            $hasAccess = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.id')
                ->join('project_members', 'boards.project_id', '=', 'project_members.project_id')
                ->where('cards.id', $validated['task_id'])
                ->where('project_members.user_id', $user->user_id)
                ->exists();

            if (!$hasAccess) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke task ini']);
            }

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
        if (!CheckPermission::hasPermission(Auth::user(), 'log_own_time')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $validated = $request->validate([
            'task_id' => 'required|exists:cards,id',
            'hours' => 'required|numeric|min:0.5|max:12', // Max 12 hours per day for members
            'description' => 'required|string|max:255',
            'work_date' => 'required|date|before_or_equal:today'
        ]);

        try {
            $user = Auth::user();

            // Verify task is assigned to user
            $task = DB::table('cards')->where('id', $validated['task_id'])->first();
            if ($task->assigned_to != $user->user_id) {
                return response()->json(['success' => false, 'message' => 'Task ini tidak ditugaskan kepada Anda']);
            }

            // Check if already logged time for this date
            $existingLog = DB::table('time_logs')
                ->where('user_id', $user->user_id)
                ->where('card_id', $validated['task_id'])
                ->where('logged_date', $validated['work_date'])
                ->exists();

            if ($existingLog) {
                return response()->json(['success' => false, 'message' => 'Anda sudah mencatat waktu untuk task ini pada tanggal tersebut']);
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
     * My timesheet
     */
    public function timesheet()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'view_own_timesheet')) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $timeLogs = $this->getTimeLogs($user);
        $weeklyStats = $this->getWeeklyStats($user);

        return view('member.timesheet', compact('timeLogs', 'weeklyStats'));
    }

    /**
     * Download files
     */
    public function downloadFile($fileId)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'download_files')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();

        // Verify user has access to this file
        $file = DB::table('task_attachments')
            ->join('cards', 'task_attachments.card_id', '=', 'cards.id')
            ->join('boards', 'cards.board_id', '=', 'boards.id')
            ->join('project_members', 'boards.project_id', '=', 'project_members.project_id')
            ->where('task_attachments.id', $fileId)
            ->where('project_members.user_id', $user->user_id)
            ->select('task_attachments.*')
            ->first();

        if (!$file) {
            return redirect()->back()->with('error', 'File tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $filePath = storage_path('app/public/' . $file->path);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        return response()->download($filePath, $file->original_name);
    }

    /**
     * Upload own files (limited)
     */
    public function uploadFile(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'upload_own_files')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $validated = $request->validate([
            'task_id' => 'required|exists:cards,id',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,txt|max:5120', // 5MB max for members
            'description' => 'nullable|string|max:255'
        ]);

        try {
            $user = Auth::user();

            // Verify task is assigned to user
            $task = DB::table('cards')->where('id', $validated['task_id'])->first();
            if ($task->assigned_to != $user->user_id) {
                return response()->json(['success' => false, 'message' => 'Task ini tidak ditugaskan kepada Anda']);
            }

            // Store file
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('task_attachments', $filename, 'public');

            // Save to database
            DB::table('task_attachments')->insert([
                'card_id' => $validated['task_id'],
                'user_id' => $user->user_id,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'description' => $validated['description'],
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'File berhasil diupload']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper Methods
     */

    /**
     * Get tasks assigned to member
     */
    private function getAssignedTasks($user)
    {
        return DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->where('cards.assigned_to', $user->user_id)
            ->select('cards.*', 'projects.project_name', 'boards.board_name')
            ->orderBy('cards.due_date', 'asc')
            ->get();
    }

    /**
     * Get basic task statistics
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
     * Get recent activities
     */
    private function getRecentActivities($user)
    {
        return DB::table('comments')
            ->join('cards', 'comments.card_id', '=', 'cards.id')
            ->where('comments.user_id', $user->user_id)
            ->select('comments.*', 'cards.title as task_title')
            ->orderBy('comments.created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get time spent this week
     */
    private function getTimeSpent($user)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        return DB::table('time_logs')
            ->where('user_id', $user->user_id)
            ->whereBetween('logged_date', [$startOfWeek, $endOfWeek])
            ->sum('hours');
    }

    /**
     * Get detailed time logs
     */
    private function getTimeLogs($user)
    {
        return DB::table('time_logs')
            ->join('cards', 'time_logs.card_id', '=', 'cards.id')
            ->where('time_logs.user_id', $user->user_id)
            ->select('time_logs.*', 'cards.title as task_title')
            ->orderBy('time_logs.logged_date', 'desc')
            ->paginate(10);
    }

    /**
     * Get weekly statistics
     */
    private function getWeeklyStats($user)
    {
        $weeks = [];

        for ($i = 0; $i < 4; $i++) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();

            $hours = DB::table('time_logs')
                ->where('user_id', $user->user_id)
                ->whereBetween('logged_date', [$weekStart, $weekEnd])
                ->sum('hours');

            $weeks[] = [
                'week' => $weekStart->format('M d') . ' - ' . $weekEnd->format('M d'),
                'hours' => $hours
            ];
        }

        return array_reverse($weeks);
    }
}
