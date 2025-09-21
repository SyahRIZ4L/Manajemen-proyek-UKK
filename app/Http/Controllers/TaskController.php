<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display all tasks for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $tasks = $this->getUserTasks($user->id);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $projects = $this->getUserProjects(Auth::id());
        return view('tasks.create', compact('projects'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|integer',
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'required|date|after:today',
            'estimated_hours' => 'nullable|numeric|min:0.5|max:999',
        ]);

        // Mock task creation - will be replaced with actual model
        $taskData = $request->all();
        $taskData['user_id'] = Auth::id();
        $taskData['status'] = 'To Do';
        $taskData['created_at'] = now();

        return redirect()->route('tasks.index')->with('success', 'Task berhasil dibuat!');
    }

    /**
     * Display the specified task.
     */
    public function show($id)
    {
        $task = $this->getTaskById($id);
        $history = $this->getTaskHistory($id);

        return view('tasks.show', compact('task', 'history'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit($id)
    {
        $task = $this->getTaskById($id);
        $projects = $this->getUserProjects(Auth::id());

        return view('tasks.edit', compact('task', 'projects'));
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|integer',
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:To Do,In Progress,Review,Completed',
            'due_date' => 'required|date',
            'estimated_hours' => 'nullable|numeric|min:0.5|max:999',
        ]);

        // Mock task update - will be replaced with actual model
        return redirect()->route('tasks.show', $id)->with('success', 'Task berhasil diperbarui!');
    }

    /**
     * Update task status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:To Do,In Progress,Review,Completed',
            'comment' => 'nullable|string|max:500',
        ]);

        // Mock status update - will be replaced with actual model
        $this->logTaskHistory($id, 'status_changed', $request->status, $request->comment);

        return response()->json([
            'success' => true,
            'message' => 'Status task berhasil diperbarui!'
        ]);
    }

    /**
     * Display task history.
     */
    public function history()
    {
        $user = Auth::user();
        $history = $this->getAllTaskHistory($user->id);

        return view('tasks.history', compact('history'));
    }

    /**
     * Log time for a task.
     */
    public function logTime(Request $request, $id)
    {
        $request->validate([
            'hours' => 'required|numeric|min:0.1|max:24',
            'description' => 'required|string|max:500',
            'date' => 'required|date|before_or_equal:today',
        ]);

        // Mock time logging - will be replaced with actual model
        return response()->json([
            'success' => true,
            'message' => 'Waktu berhasil dicatat!'
        ]);
    }

    /**
     * Get user tasks (mock data).
     */
    private function getUserTasks($userId)
    {
        return [
            [
                'id' => 1,
                'title' => 'Develop user authentication system',
                'description' => 'Create login, register, and password reset functionality',
                'status' => 'In Progress',
                'priority' => 'High',
                'project' => 'E-commerce Website',
                'project_id' => 1,
                'due_date' => '2025-09-25',
                'estimated_hours' => 40,
                'actual_hours' => 25,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subHours(2),
            ],
            [
                'id' => 2,
                'title' => 'Design database schema',
                'description' => 'Create ERD and implement database structure',
                'status' => 'Completed',
                'priority' => 'Medium',
                'project' => 'CRM System',
                'project_id' => 2,
                'due_date' => '2025-09-20',
                'estimated_hours' => 16,
                'actual_hours' => 18,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subHours(5),
            ],
            [
                'id' => 3,
                'title' => 'Create API documentation',
                'description' => 'Document all API endpoints with examples',
                'status' => 'Review',
                'priority' => 'Low',
                'project' => 'Mobile App Backend',
                'project_id' => 3,
                'due_date' => '2025-09-28',
                'estimated_hours' => 12,
                'actual_hours' => 8,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDay(),
            ],
        ];
    }

    /**
     * Get user projects (mock data).
     */
    private function getUserProjects($userId)
    {
        return [
            ['id' => 1, 'name' => 'E-commerce Website'],
            ['id' => 2, 'name' => 'CRM System'],
            ['id' => 3, 'name' => 'Mobile App Backend'],
            ['id' => 4, 'name' => 'Admin Dashboard'],
        ];
    }

    /**
     * Get task by ID (mock data).
     */
    private function getTaskById($id)
    {
        $tasks = $this->getUserTasks(Auth::id());
        return collect($tasks)->firstWhere('id', $id);
    }

    /**
     * Get task history (mock data).
     */
    private function getTaskHistory($taskId)
    {
        return [
            [
                'id' => 1,
                'action' => 'created',
                'description' => 'Task dibuat',
                'user' => 'John Doe',
                'created_at' => now()->subDays(5),
            ],
            [
                'id' => 2,
                'action' => 'status_changed',
                'description' => 'Status diubah dari "To Do" ke "In Progress"',
                'user' => 'John Doe',
                'created_at' => now()->subDays(3),
            ],
            [
                'id' => 3,
                'action' => 'time_logged',
                'description' => 'Mencatat 8 jam kerja',
                'user' => 'John Doe',
                'created_at' => now()->subDays(2),
            ],
        ];
    }

    /**
     * Get all task history for user (mock data).
     */
    private function getAllTaskHistory($userId)
    {
        return [
            [
                'id' => 1,
                'task_title' => 'Develop user authentication system',
                'action' => 'status_changed',
                'description' => 'Status diubah ke "In Progress"',
                'created_at' => now()->subHours(2),
            ],
            [
                'id' => 2,
                'task_title' => 'Design database schema',
                'action' => 'completed',
                'description' => 'Task diselesaikan',
                'created_at' => now()->subHours(5),
            ],
            [
                'id' => 3,
                'task_title' => 'Create API documentation',
                'action' => 'submitted_for_review',
                'description' => 'Task diserahkan untuk review',
                'created_at' => now()->subDay(),
            ],
        ];
    }

    /**
     * Log task history (mock function).
     */
    private function logTaskHistory($taskId, $action, $newValue = null, $comment = null)
    {
        // This will be implemented when models are ready
        return true;
    }
}
