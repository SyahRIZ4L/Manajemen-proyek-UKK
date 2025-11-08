<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'user_id',
        'project_id',
        'triggered_by',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Constants for notification types
    const TYPE_TASK_UPDATE = 'task_update';
    const TYPE_STATUS_CHANGE = 'status_change';
    const TYPE_PROJECT_UPDATE = 'project_update';
    const TYPE_PROJECT_ASSIGNMENT = 'project_assignment';
    const TYPE_PROJECT_COMPLETION = 'project_completion';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => Carbon::now()
        ]);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedDataAttribute()
    {
        return $this->data ? json_encode($this->data, JSON_PRETTY_PRINT) : null;
    }

    // Static helper methods
    public static function createTaskUpdateNotification($projectId, $triggeredBy, $message, $additionalData = [])
    {
        $project = Project::find($projectId);
        if (!$project) return false;

        // Get all users who should receive notification (admin, team_lead)
        $recipients = User::whereIn('role', ['admin', 'team_lead'])->get();

        foreach ($recipients as $user) {
            // Don't send notification to the person who triggered it
            if ($user->user_id === $triggeredBy) continue;

            self::create([
                'user_id' => $user->user_id,
                'project_id' => $projectId,
                'triggered_by' => $triggeredBy,
                'type' => self::TYPE_TASK_UPDATE,
                'title' => 'Task Updated',
                'message' => $message,
                'data' => array_merge([
                    'project_name' => $project->project_name,
                    'project_status' => $project->status
                ], $additionalData)
            ]);
        }

        return true;
    }

    public static function createStatusChangeNotification($projectId, $triggeredBy, $oldStatus, $newStatus, $additionalData = [])
    {
        $project = Project::find($projectId);
        if (!$project) return false;

        // Get all users who should receive notification (admin, team_lead)
        $recipients = User::whereIn('role', ['admin', 'team_lead'])->get();

        foreach ($recipients as $user) {
            // Don't send notification to the person who triggered it
            if ($user->user_id === $triggeredBy) continue;

            self::create([
                'user_id' => $user->user_id,
                'project_id' => $projectId,
                'triggered_by' => $triggeredBy,
                'type' => self::TYPE_STATUS_CHANGE,
                'title' => 'Project Status Changed',
                'message' => "Project '{$project->project_name}' status changed from {$oldStatus} to {$newStatus}",
                'data' => array_merge([
                    'project_name' => $project->project_name,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ], $additionalData)
            ]);
        }

        return true;
    }

    public static function createProjectUpdateNotification($projectId, $triggeredBy, $updateType, $message, $additionalData = [])
    {
        $project = Project::find($projectId);
        if (!$project) return false;

        // Get all users who should receive notification (admin, team_lead)
        $recipients = User::whereIn('role', ['admin', 'team_lead'])->get();

        foreach ($recipients as $user) {
            // Don't send notification to the person who triggered it
            if ($user->user_id === $triggeredBy) continue;

            self::create([
                'user_id' => $user->user_id,
                'project_id' => $projectId,
                'triggered_by' => $triggeredBy,
                'type' => self::TYPE_PROJECT_UPDATE,
                'title' => 'Project Updated',
                'message' => $message,
                'data' => array_merge([
                    'project_name' => $project->project_name,
                    'update_type' => $updateType
                ], $additionalData)
            ]);
        }

        return true;
    }

    public static function markAllAsReadForUser($userId)
    {
        return self::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => Carbon::now()
            ]);
    }
}
