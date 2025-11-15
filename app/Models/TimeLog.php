<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TimeLog extends Model
{
    protected $table = 'time_logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'card_id',
        'subtask_id',
        'user_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'description'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer'
    ];

    // Relationships
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id', 'card_id');
    }

    public function subtask(): BelongsTo
    {
        return $this->belongsTo(Subtask::class, 'subtask_id', 'subtask_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Scopes
    public function scopeForCard($query, $cardId)
    {
        return $query->where('card_id', $cardId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('end_time');
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('end_time');
    }

    // Accessors and Mutators
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return '0 minutes';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $minutes . ' minutes';
    }

    public function getIsActiveAttribute()
    {
        return is_null($this->end_time);
    }

    // Helper Methods
    public function calculateDuration()
    {
        if ($this->start_time && $this->end_time) {
            $start = Carbon::parse($this->start_time);
            $end = Carbon::parse($this->end_time);
            return $start->diffInMinutes($end);
        }

        return null;
    }

    public function getCurrentDuration()
    {
        if ($this->start_time && !$this->end_time) {
            $start = Carbon::parse($this->start_time);
            $now = Carbon::now();
            return $start->diffInMinutes($now);
        }

        return $this->duration_minutes;
    }

    public function stop()
    {
        if ($this->is_active) {
            $this->end_time = Carbon::now();
            $this->duration_minutes = $this->calculateDuration();
            $this->save();
        }

        return $this;
    }

    // Static Methods
    public static function startTimer($cardId, $userId, $description = null, $subtaskId = null)
    {
        // Stop any active timer for this user
        static::where('user_id', $userId)
            ->whereNull('end_time')
            ->each(function ($timer) {
                $timer->stop();
            });

        // Create new timer
        return static::create([
            'card_id' => $cardId,
            'subtask_id' => $subtaskId,
            'user_id' => $userId,
            'start_time' => Carbon::now(),
            'description' => $description
        ]);
    }

    public static function getActiveTimer($userId)
    {
        return static::where('user_id', $userId)
            ->where('is_active', true)
            ->whereNull('end_time')
            ->with(['card', 'subtask'])
            ->first();
    }

    public static function getTotalTimeForCard($cardId)
    {
        return static::where('card_id', $cardId)
            ->whereNotNull('duration_minutes')
            ->sum('duration_minutes');
    }

    public static function getTotalTimeForUser($userId, $dateFrom = null, $dateTo = null)
    {
        $query = static::where('user_id', $userId)
            ->whereNotNull('duration_minutes');

        if ($dateFrom) {
            $query->where('start_time', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('start_time', '<=', $dateTo);
        }

        return $query->sum('duration_minutes');
    }

    // Auto Timer Methods
    public static function autoStartForCard($cardId, $userId, $description = null)
    {
        // Stop any existing active timer for the user
        static::stopActiveTimerForUser($userId);

        // Create auto timer
        return static::create([
            'card_id' => $cardId,
            'user_id' => $userId,
            'start_time' => Carbon::now(),
            'description' => $description ?? 'Auto-started: Card status changed'
        ]);
    }

    public static function stopActiveTimerForCard($cardId)
    {
        $activeTimer = static::where('card_id', $cardId)
            ->whereNull('end_time')
            ->first();

        if ($activeTimer) {
            $activeTimer->stop();
        }

        return $activeTimer;
    }

    public static function stopActiveTimerForUser($userId)
    {
        $activeTimers = static::where('user_id', $userId)
            ->whereNull('end_time')
            ->get();

        foreach ($activeTimers as $timer) {
            $timer->stop();
        }

        return $activeTimers;
    }

    public static function getActiveTimerForCard($cardId)
    {
        return static::where('card_id', $cardId)
            ->whereNull('end_time')
            ->with(['user', 'card'])
            ->first();
    }

    // Check if timer is auto-started
    public function isAutoTimer()
    {
        return !empty($this->auto_timer_type) || str_contains($this->description ?? '', 'Auto-started:');
    }

    // Pause timer (for when card goes to review)
    public function pause()
    {
        if ($this->is_active) {
            $this->end_time = Carbon::now();
            $this->duration_minutes = $this->calculateDuration();
            $this->save();
        }

        return $this;
    }

    // Resume timer (for when card is rejected from review)
    public function resume()
    {
        if (!$this->is_active) {
            // Create new timer entry for resumption
            return static::create([
                'card_id' => $this->card_id,
                'user_id' => $this->user_id,
                'start_time' => Carbon::now(),
                'description' => 'Auto-resumed: ' . ($this->description ?? 'Continued work')
            ]);
        }

        return $this;
    }

    // Get timer history for a card (for tracking pauses/resumes)
    public static function getTimerHistoryForCard($cardId)
    {
        return static::where('card_id', $cardId)
            ->orderBy('start_time', 'desc')
            ->with(['user'])
            ->get();
    }
}
