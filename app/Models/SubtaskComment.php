<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubtaskComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtask_id',
        'user_id',
        'comment'
    ];

    /**
     * Get the subtask that owns the comment.
     */
    public function subtask(): BelongsTo
    {
        return $this->belongsTo(Subtask::class);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get formatted created time.
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
