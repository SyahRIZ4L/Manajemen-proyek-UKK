<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardAssignment extends Model
{
    protected $table = 'card_assignments';
    protected $primaryKey = 'assignment_id';
    public $timestamps = false;

    protected $fillable = [
        'card_id',
        'user_id',
        'assignment_status',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the card that this assignment belongs to
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id', 'card_id');
    }

    /**
     * Get the user that this assignment belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
