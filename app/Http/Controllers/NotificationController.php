<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $perPage = $request->get('per_page', 10);
            $type = $request->get('type');

            $query = Notification::forUser($user->user_id)
                ->with(['project', 'triggeredBy'])
                ->orderBy('created_at', 'desc');

            if ($type) {
                $query->byType($type);
            }

            $notifications = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notifications count for the authenticated user.
     */
    public function getUnreadCount()
    {
        try {
            $user = Auth::user();
            $count = Notification::forUser($user->user_id)->unread()->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving notification count: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent unread notifications for dropdown/bell icon
     */
    public function getRecent(Request $request)
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 5);

            $notifications = Notification::forUser($user->user_id)
                ->unread()
                ->with(['project', 'triggeredBy'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($notification) {
                    return [
                        'notification_id' => $notification->notification_id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'time_ago' => $notification->time_ago,
                        'project_name' => $notification->project ? $notification->project->project_name : null,
                        'triggered_by' => $notification->triggeredBy ? $notification->triggeredBy->full_name : null,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving recent notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($notificationId)
    {
        try {
            $user = Auth::user();
            $notification = Notification::where('notification_id', $notificationId)
                ->where('user_id', $user->user_id)
                ->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            $count = Notification::markAllAsReadForUser($user->user_id);

            return response()->json([
                'success' => true,
                'message' => "Marked {$count} notifications as read"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking all notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a notification.
     */
    public function destroy($notificationId)
    {
        try {
            $user = Auth::user();
            $notification = Notification::where('notification_id', $notificationId)
                ->where('user_id', $user->user_id)
                ->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test notification creation (for development)
     */
    public function testNotification(Request $request)
    {
        try {
            $user = Auth::user();
            $projectId = $request->get('project_id', 1);
            $type = $request->get('type', 'task_update');

            switch ($type) {
                case 'task_update':
                    Notification::createTaskUpdateNotification(
                        $projectId,
                        $user->user_id,
                        'Test task update notification'
                    );
                    break;

                case 'status_change':
                    Notification::createStatusChangeNotification(
                        $projectId,
                        $user->user_id,
                        'In Progress',
                        'Completed'
                    );
                    break;

                default:
                    Notification::createProjectUpdateNotification(
                        $projectId,
                        $user->user_id,
                        'general',
                        'Test project update notification'
                    );
            }

            return response()->json([
                'success' => true,
                'message' => 'Test notification created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating test notification: ' . $e->getMessage()
            ], 500);
        }
    }
}
