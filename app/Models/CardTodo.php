<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardTodo extends Model
{
    use HasFactory;

    protected $table = 'card_todos';
    protected $primaryKey = 'todo_id';

    protected $fillable = [
        'card_id',
        'user_id',
        'text',
        'completed'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the card that owns the todo.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id', 'card_id');
    }

    /**
     * Get the user that created the todo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope a query to only include completed todos.
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    /**
     * Scope a query to only include active todos.
     */
    public function scopeActive($query)
    {
        return $query->where('completed', false);
    }

    /**
     * Scope a query to filter by card.
     */
    public function scopeByCard($query, $cardId)
    {
        return $query->where('card_id', $cardId);
    }
}
