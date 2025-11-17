<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardHistory extends Model
{
    protected $table = 'card_history';
    protected $primaryKey = 'history_id';
    public $timestamps = false;

    protected $fillable = [
        'card_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'comment',
        'feedback',
        'action_date',
        'metadata'
    ];

    protected $casts = [
        'action_date' => 'datetime',
        'metadata' => 'array'
    ];

    // Relationships
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopeForCard($query, $cardId)
    {
        return $query->where('card_id', $cardId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('action_date', '>=', now()->subDays($days));
    }

    // Helper methods
    public function getFormattedActionAttribute()
    {
        $actionMap = [
            'assigned' => 'Assigned to member',
            'submitted' => 'Submitted for review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'status_changed' => 'Status changed',
            'created' => 'Created'
        ];

        return $actionMap[$this->action] ?? $this->action;
    }

    public function getActionIconAttribute()
    {
        $iconMap = [
            'assigned' => 'bi-person-plus',
            'submitted' => 'bi-upload',
            'approved' => 'bi-check-circle',
            'rejected' => 'bi-x-circle',
            'status_changed' => 'bi-arrow-repeat',
            'created' => 'bi-plus-circle'
        ];

        return $iconMap[$this->action] ?? 'bi-clock-history';
    }

    public function getActionColorAttribute()
    {
        $colorMap = [
            'assigned' => 'info',
            'submitted' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'status_changed' => 'secondary',
            'created' => 'primary'
        ];

        return $colorMap[$this->action] ?? 'secondary';
    }
}
