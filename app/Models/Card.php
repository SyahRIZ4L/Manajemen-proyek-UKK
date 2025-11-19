<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Card extends Model
{
    protected $table = 'cards';
    protected $primaryKey = 'card_id';
    public $timestamps = false;

    protected $fillable = [
        'card_title',
        'card_description',
        'board_id',
        'created_by',
        'status',
        'priority',
        'due_date',
        'started_at',
        'completed_at'
    ];

    // Properties that should not be saved to database
    protected $guarded = [
        'handlingStatusChange' // Runtime-only flag to prevent recursion
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'due_date' => 'date',
        'deadline' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'timer_started_at' => 'datetime',
        'is_timer_active' => 'boolean',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2'
    ];

    // Relationships
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class, 'board_id', 'board_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class, 'card_id', 'card_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class, 'card_id', 'card_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(CardAssignment::class, 'card_id', 'card_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(CardHistory::class, 'card_id', 'card_id');
    }

    public function todos(): HasMany
    {
        return $this->hasMany(CardTodo::class, 'card_id', 'card_id');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', Carbon::today())
            ->whereNotIn('status', ['done']);
    }

    public function scopeDueSoon($query, $days = 3)
    {
        return $query->where('due_date', '>=', Carbon::today())
            ->where('due_date', '<=', Carbon::today()->addDays($days))
            ->whereNotIn('status', ['done']);
    }

    // Accessors
    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_date < Carbon::today() && $this->status !== 'done';
    }

    public function getDaysUntilDueAttribute()
    {
        if (!$this->due_date) {
            return null;
        }

        return Carbon::today()->diffInDays($this->due_date, false);
    }

    public function getTimeUntilDueAttribute()
    {
        if (!$this->due_date) {
            return null;
        }

        $due = Carbon::parse($this->due_date)->endOfDay();
        $now = Carbon::now();

        if ($due < $now) {
            // Overdue
            $diff = $now->diff($due);
            return [
                'overdue' => true,
                'days' => $diff->days,
                'hours' => $diff->h,
                'minutes' => $diff->i,
                'formatted' => $diff->days . 'd ' . $diff->h . 'h ' . $diff->i . 'm overdue'
            ];
        } else {
            // Time remaining
            $diff = $now->diff($due);
            return [
                'overdue' => false,
                'days' => $diff->days,
                'hours' => $diff->h,
                'minutes' => $diff->i,
                'formatted' => $diff->days . 'd ' . $diff->h . 'h ' . $diff->i . 'm remaining'
            ];
        }
    }

    public function getTotalTimeLoggedAttribute()
    {
        return $this->timeLogs()->whereNotNull('duration_minutes')->sum('duration_minutes');
    }

    public function getFormattedTotalTimeAttribute()
    {
        $totalMinutes = $this->total_time_logged;

        if (!$totalMinutes) {
            return '0 minutes';
        }

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $minutes . ' minutes';
    }

    public function getActiveTimeLogAttribute()
    {
        return $this->timeLogs()->whereNull('end_time')->first();
    }

    public function getProgressPercentageAttribute()
    {
        if (!$this->estimated_hours || !$this->total_time_logged) {
            return 0;
        }

        $estimatedMinutes = $this->estimated_hours * 60;
        return min(100, round(($this->total_time_logged / $estimatedMinutes) * 100, 1));
    }

    // Helper Methods
    public function startTimer($userId, $description = null)
    {
        return TimeLog::startTimer($this->card_id, $userId, $description);
    }

    public function getDeadlineStatus()
    {
        if (!$this->due_date) {
            return [
                'status' => 'no_deadline',
                'message' => 'No deadline set',
                'class' => 'text-muted'
            ];
        }

        $timeUntilDue = $this->time_until_due;

        if ($timeUntilDue['overdue']) {
            return [
                'status' => 'overdue',
                'message' => $timeUntilDue['formatted'],
                'class' => 'text-danger',
                'days' => -$timeUntilDue['days'],
                'hours' => $timeUntilDue['hours'],
                'minutes' => $timeUntilDue['minutes']
            ];
        } else {
            if ($timeUntilDue['days'] <= 1) {
                return [
                    'status' => 'urgent',
                    'message' => $timeUntilDue['formatted'],
                    'class' => 'text-warning',
                    'days' => $timeUntilDue['days'],
                    'hours' => $timeUntilDue['hours'],
                    'minutes' => $timeUntilDue['minutes']
                ];
            } else {
                return [
                    'status' => 'on_time',
                    'message' => $timeUntilDue['formatted'],
                    'class' => 'text-success',
                    'days' => $timeUntilDue['days'],
                    'hours' => $timeUntilDue['hours'],
                    'minutes' => $timeUntilDue['minutes']
                ];
            }
        }
    }

    public function updateActualHours()
    {
        $totalMinutes = $this->total_time_logged;
        $hours = $totalMinutes ? round($totalMinutes / 60, 2) : 0;

        // Use update method to bypass casting issues
        $this->update(['actual_hours' => $hours]);

        return $this;
    }

    // Auto Timer Management Methods
    public function autoStartTimer($userId = null)
    {
        // Get assigned user or use provided userId
        $assignedUser = $userId ?? $this->getAssignedUserId();

        if (!$assignedUser) {
            return false;
        }

        // Check if card should have auto timer based on status
        if (!$this->shouldHaveAutoTimer()) {
            return false;
        }

        // Stop existing timer for this card if any
        $this->stopAutoTimer();

        // Start new auto timer
        $timer = TimeLog::create([
            'card_id' => $this->card_id,
            'user_id' => $assignedUser,
            'start_time' => now(),
            'is_active' => true,
            'auto_timer_type' => 'status_change',
            'description' => 'Auto-started: ' . $this->getAutoTimerDescription()
        ]);

        // Use updateQuietly to prevent triggering observers
        $this->updateQuietly([
            'is_timer_active' => true,
            'timer_started_at' => now()
        ]);

        return $timer;
    }

    public function stopAutoTimer()
    {
        // Find active timer for this card
        $activeTimer = TimeLog::where('card_id', $this->card_id)
            ->whereNull('end_time')
            ->first();

        if ($activeTimer) {
            $activeTimer->stop();
        }

        // Use updateQuietly to prevent triggering observers
        $this->updateQuietly([
            'is_timer_active' => false,
            'timer_started_at' => null
        ]);

        return $activeTimer;
    }

    public function shouldHaveAutoTimer()
    {
        // Timer should be active for in_progress and review status
        return in_array($this->status, ['in_progress', 'review']);
    }

    private function getAssignedUserId()
    {
        // Try to get from card assignments first
        $assignment = \App\Models\CardAssignment::where('card_id', $this->card_id)->first();
        if ($assignment) {
            return $assignment->user_id;
        }

        // Fallback to current authenticated user if available
        if (Auth::check()) {
            return Auth::id();
        }

        // Last resort: use created_by
        return $this->created_by;
    }

    private function getAutoTimerDescription()
    {
        $statusDescriptions = [
            'in_progress' => 'Working on task',
            'review' => 'Task under review',
            'todo' => 'Task assigned'
        ];

        return $statusDescriptions[$this->status] ?? 'Task tracking';
    }

    // Status change handlers
    // Static property to prevent recursion across all card instances
    private static $handlingStatusChanges = [];

    public function handleStatusChange($oldStatus, $newStatus)
    {
        Log::info('Card handleStatusChange called', [
            'card_id' => $this->card_id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);

        // Prevent infinite recursion by checking static flag for this card
        if (isset(self::$handlingStatusChanges[$this->card_id])) {
            Log::info('Already handling status change, skipping recursion', [
                'card_id' => $this->card_id
            ]);
            return;
        }

        // Mark this card as being processed
        self::$handlingStatusChanges[$this->card_id] = true;

        try {
            // Update timestamps based on status
            $this->updateStatusTimestamps($newStatus);

            // Auto start timer when card moves to work states
            if ($this->shouldStartAutoTimer($oldStatus, $newStatus)) {
                Log::info('Starting auto timer for card', ['card_id' => $this->card_id]);
                $this->autoStartTimer();
            }

            // Stop timer when card is completed
            if ($this->shouldStopAutoTimer($oldStatus, $newStatus)) {
                Log::info('Stopping auto timer for card', ['card_id' => $this->card_id]);
                $this->stopAutoTimer();
            }

            // Handle rejected cards (continue timer)
            if ($this->isRejected($oldStatus, $newStatus)) {
                Log::info('Handling rejection for card', ['card_id' => $this->card_id]);
                $this->handleRejection();
            }
        } finally {
            // Remove the flag for this card
            unset(self::$handlingStatusChanges[$this->card_id]);
        }
    }

    private function shouldStartAutoTimer($oldStatus, $newStatus)
    {
        // START timer: todo → in_progress (first time start)
        // RESUME timer: review → in_progress (rejected, continue work)
        return ($oldStatus === 'todo' && $newStatus === 'in_progress') ||
               ($oldStatus === 'review' && $newStatus === 'in_progress');
    }

    private function shouldStopAutoTimer($oldStatus, $newStatus)
    {
        // PAUSE timer: in_progress → review (submitted for review)
        // STOP timer: → done (completed permanently)
        // STOP timer: → todo (reset)
        return $newStatus === 'review' ||
               $newStatus === 'done' ||
               $newStatus === 'todo';
    }

    private function isRejected($oldStatus, $newStatus)
    {
        // Card is rejected when it moves from review back to in_progress
        return $oldStatus === 'review' && $newStatus === 'in_progress';
    }

    private function handleRejection()
    {
        // When rejected, timer will be resumed by shouldStartAutoTimer
        // No need to manually start here to avoid double start
        Log::info('Card rejected, timer will resume via shouldStartAutoTimer');

        // Log the rejection for tracking
        $this->logRejection();
    }

    private function logRejection()
    {
        // Create a notification or log entry about rejection
        // This could be expanded to create actual notifications
        Log::info("Card {$this->card_id} - '{$this->card_title}' was rejected and timer resumed");
    }

    private function updateStatusTimestamps($newStatus)
    {
        $needsSave = false;

        // Update started_at when task starts
        if ($newStatus === 'in_progress' && !$this->started_at) {
            $this->started_at = now();
            $needsSave = true;
        }

        // Update completed_at when task is done
        if ($newStatus === 'done' && !$this->completed_at) {
            $this->completed_at = now();
            $needsSave = true;
        }

        // Clear completed_at if task is reopened
        if ($newStatus !== 'done' && $this->completed_at) {
            $this->completed_at = null;
            $needsSave = true;
        }

        // Use saveQuietly to prevent triggering observers
        if ($needsSave) {
            $this->saveQuietly();
        }
    }

    // Get current auto timer status
    public function getAutoTimerStatus()
    {
        $activeTimer = $this->timeLogs()->whereNull('end_time')->first();

        return [
            'is_active' => $this->is_timer_active,
            'started_at' => $this->timer_started_at,
            'current_duration' => $activeTimer ? $activeTimer->getCurrentDuration() : 0,
            'description' => $activeTimer ? $activeTimer->description : null,
            'is_auto' => $activeTimer ? str_contains($activeTimer->description, 'Auto-started:') : false
        ];
    }
}
