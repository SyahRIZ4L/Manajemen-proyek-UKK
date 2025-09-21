<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = $this->getUserNotifications(Auth::id());

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($notificationId)
    {
        // Mock implementation - will be replaced with actual database logic
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil ditandai sebagai dibaca'
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        // Mock implementation - will be replaced with actual database logic
        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil ditandai sebagai dibaca'
        ]);
    }

    /**
     * Delete notification.
     */
    public function destroy($notificationId)
    {
        // Mock implementation - will be replaced with actual database logic
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }

    /**
     * Get unread notification count.
     */
    public function getUnreadCount()
    {
        $notifications = $this->getUserNotifications(Auth::id());
        $unreadCount = count(array_filter($notifications, fn($n) => !$n['read']));

        return response()->json([
            'count' => $unreadCount
        ]);
    }

    /**
     * Send notification (helper method).
     */
    public static function sendNotification($userId, $type, $title, $message, $data = [])
    {
        // Mock implementation - will be replaced with actual database logic
        // This would typically save to database and potentially send real-time notifications

        return [
            'id' => rand(1000, 9999),
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'read' => false,
            'created_at' => now(),
        ];
    }

    /**
     * Get user notifications (mock data).
     */
    private function getUserNotifications($userId)
    {
        return [
            [
                'id' => 1,
                'type' => 'task_assigned',
                'title' => 'Task Baru Ditugaskan',
                'message' => 'Anda telah ditugaskan untuk task "Develop payment gateway"',
                'data' => ['task_id' => 5, 'assigned_by' => 'Project Manager'],
                'read' => false,
                'created_at' => now()->subMinutes(30),
            ],
            [
                'id' => 2,
                'type' => 'task_status_changed',
                'title' => 'Status Task Diubah',
                'message' => 'Status task "Database optimization" diubah menjadi "Completed"',
                'data' => ['task_id' => 3, 'old_status' => 'In Progress', 'new_status' => 'Completed'],
                'read' => false,
                'created_at' => now()->subHours(2),
            ],
            [
                'id' => 3,
                'type' => 'project_deadline',
                'title' => 'Deadline Mendekat',
                'message' => 'Proyek "E-commerce Website" deadline dalam 3 hari',
                'data' => ['project_id' => 1, 'deadline' => '2025-09-24'],
                'read' => true,
                'created_at' => now()->subHours(4),
            ],
            [
                'id' => 4,
                'type' => 'task_comment',
                'title' => 'Komentar Baru',
                'message' => 'Admin menambahkan komentar pada task "API Documentation"',
                'data' => ['task_id' => 4, 'comment_by' => 'Admin'],
                'read' => true,
                'created_at' => now()->subDay(),
            ],
            [
                'id' => 5,
                'type' => 'time_log_approved',
                'title' => 'Time Log Disetujui',
                'message' => 'Time log 8 jam untuk task "Authentication System" telah disetujui',
                'data' => ['task_id' => 1, 'hours' => 8],
                'read' => true,
                'created_at' => now()->subDays(2),
            ],
        ];
    }
}
