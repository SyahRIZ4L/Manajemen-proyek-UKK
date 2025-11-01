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
     * Start working on a task
     */
    public function startTask($id)
    {
        try {
            $task = $this->getTaskById($id);
            
            if ($task['status'] !== 'To Do') {
                return response()->json([
                    'success' => false,
                    'message' => 'Task tidak dapat dimulai dari status ini'
                ]);
            }

            // Update status to In Progress
            // In real implementation, update database
            
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dimulai!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit task for review
     */
    public function submitForReview(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|min:10',
            'attachments.*' => 'file|max:10240' // Max 10MB per file
        ]);

        try {
            $task = $this->getTaskById($id);
            
            if ($task['status'] !== 'In Progress') {
                return response()->json([
                    'success' => false,
                    'message' => 'Task harus dalam status In Progress untuk submit review'
                ]);
            }

            // Handle file uploads
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('task_attachments');
                    $attachments[] = $path;
                }
            }

            // Update status to Review
            // In real implementation, update database
            
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil disubmit untuk review!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete a task
     */
    public function completeTask(Request $request, $id)
    {
        $request->validate([
            'completion_note' => 'required|string|min:10',
            'actual_hours' => 'required|numeric|min:0.1'
        ]);

        try {
            $task = $this->getTaskById($id);
            
            // Update status to Completed
            // In real implementation, update database
            
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil diselesaikan!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }    /**
     * Submit task for review.
     */
    public function submitForReview(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max per file
        ]);

        $task = $this->getTaskById($id);
        
        // Handle file attachments if any
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->move(public_path('uploads/task_attachments'), $filename);
                $attachmentPaths[] = 'uploads/task_attachments/' . $filename;
            }
        }

        // Log submission
        $this->logTaskHistory($id, 'submitted_for_review', [
            'comment' => $request->comment,
            'attachments' => $attachmentPaths,
            'submitted_by' => Auth::user()->name,
            'timestamp' => now()
        ]);

        // Update status to Review
        $this->updateTaskStatus($id, 'Review');

        // Send notification to project manager/admin
        $this->sendReviewNotification($id, $request->comment);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil disubmit untuk review!'
        ]);
    }

    /**
     * Start working on task (set to In Progress).
     */
    public function startTask($id)
    {
        $task = $this->getTaskById($id);
        
        if ($task['status'] === 'To Do') {
            $this->updateTaskStatus($id, 'In Progress');
            $this->logTaskHistory($id, 'started', [
                'started_by' => Auth::user()->name,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dimulai!',
                'new_status' => 'In Progress'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Task tidak dapat dimulai dari status saat ini.'
        ]);
    }

    /**
     * Complete task.
     */
    public function completeTask(Request $request, $id)
    {
        $request->validate([
            'completion_note' => 'required|string|max:1000',
            'actual_hours' => 'required|numeric|min:0.1',
        ]);

        $task = $this->getTaskById($id);
        
        $this->logTaskHistory($id, 'completed', [
            'completion_note' => $request->completion_note,
            'actual_hours' => $request->actual_hours,
            'completed_by' => Auth::user()->name,
            'timestamp' => now()
        ]);

        $this->updateTaskStatus($id, 'Completed');

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil diselesaikan!',
            'new_status' => 'Completed'
        ]);
    }    /**
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
    private function logTaskHistory($taskId, $action, $data = null)
    {
        // This will be implemented when models are ready
        // For now, we just return true to simulate logging
        return true;
    }

    /**
     * Update task status helper.
     */
    private function updateTaskStatus($taskId, $newStatus)
    {
        // Mock implementation - will be replaced with actual database update
        return true;
    }

    /**
     * Send status change notification.
     */
    private function sendStatusNotification($taskId, $oldStatus, $newStatus)
    {
        // Mock implementation - will send notification to relevant users
        return true;
    }

    /**
     * Send review notification.
     */
    private function sendReviewNotification($taskId, $comment)
    {
        // Mock implementation - will send notification to reviewers
        return true;
    }
}
