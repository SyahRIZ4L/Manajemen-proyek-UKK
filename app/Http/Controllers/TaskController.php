<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $tasks = $this->getUserTasks($user->id);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:High,Medium,Low',
            'due_date' => 'required|date|after:today',
            'estimated_hours' => 'required|numeric|min:1',
        ]);

        // Mock task creation - will be replaced with actual model
        return redirect()->route('tasks.index')->with('success', 'Task berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = $this->getTaskById($id);
        $history = $this->getTaskHistory($id);

        return view('tasks.show', compact('task', 'history'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $task = $this->getTaskById($id);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:High,Medium,Low',
            'due_date' => 'required|date',
            'estimated_hours' => 'required|numeric|min:1',
        ]);

        // Mock task update - will be replaced with actual model
        return redirect()->route('tasks.show', $id)->with('success', 'Task berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Mock task deletion - will be replaced with actual model
        return redirect()->route('tasks.index')->with('success', 'Task berhasil dihapus!');
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
     * Update task status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:To Do,In Progress,Review,Completed',
            'comment' => 'nullable|string'
        ]);

        try {
            $task = $this->getTaskById($id);
            $currentStatus = $this->getCurrentTaskStatus($id);

            // Status validation logic
            $newStatus = $request->status;
            $validTransitions = [
                'To Do' => ['In Progress'],
                'In Progress' => ['Review', 'Completed', 'To Do'],
                'Review' => ['In Progress', 'Completed'],
                'Completed' => [] // No transitions from completed
            ];

            if (!in_array($newStatus, $validTransitions[$currentStatus] ?? [])) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat mengubah status dari {$currentStatus} ke {$newStatus}"
                ]);
            }

            // In real implementation, update database here
            // For now, we'll just return success

            return response()->json([
                'success' => true,
                'message' => 'Status task berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
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
     * Get user tasks - to be implemented with actual database queries.
     */
    private function getUserTasks($userId)
    {
        // TODO: Implement with actual database queries
        return [];
    }

    /**
     * Get single task by ID - to be implemented with actual database queries.
     */
    private function getTaskById($id)
    {
        // TODO: Implement with actual database queries
        abort(404, 'Task not found');
    }

    /**
     * Get task history - to be implemented with actual database queries.
     */
    private function getTaskHistory($taskId)
    {
        // TODO: Implement with actual database queries
        return [];
    }

    /**
     * Get all task history for user - to be implemented with actual database queries.
     */
    private function getAllTaskHistory($userId)
    {
        // TODO: Implement with actual database queries
        return [];
    }

    /**
     * Helper method to get current task status
     */
    private function getCurrentTaskStatus($taskId)
    {
        $task = $this->getTaskById($taskId);
        return $task['status'];
    }

    /**
     * Helper method to update task status
     */
    private function updateTaskStatus($taskId, $status)
    {
        // In real implementation, update the database
        // For now, just return true
        return true;
    }

    /**
     * Helper method to log task history
     */
    private function logTaskHistory($taskId, $action, $data = [])
    {
        // In real implementation, save to database
        // For now, just return true
        return true;
    }

    /**
     * Helper method to send review notification
     */
    private function sendReviewNotification($taskId, $comment)
    {
        // In real implementation, send notification
        // For now, just return true
        return true;
    }
}
