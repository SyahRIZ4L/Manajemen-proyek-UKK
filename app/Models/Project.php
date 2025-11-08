<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $primaryKey = 'project_id';
    public $timestamps = false; // Karena hanya ada created_at

    protected $fillable = [
        'project_name',
        'description',
        'created_by',
        'deadline',
        'status',
        'completed_at',
        'cancelled_at',
        'completed_by',
        'cancelled_by',
        'completion_notes',
        'cancellation_reason'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'created_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Accessor untuk id agar konsisten dengan frontend
    public function getIdAttribute()
    {
        return $this->project_id;
    }

    // Relasi dengan User
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi dengan user yang complete project
    public function completedByUser()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    // Relasi dengan user yang cancel project
    public function cancelledByUser()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // Relasi dengan ProjectMembers
    public function members()
    {
        return $this->hasMany(ProjectMember::class, 'project_id');
    }

    // Relasi dengan Boards
    public function boards()
    {
        return $this->hasMany(Board::class, 'project_id');
    }

    // Scope untuk project yang dibuat oleh user tertentu
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    // Accessor untuk deadline status project (berdasarkan deadline)
    public function getDeadlineStatusAttribute()
    {
        if (!$this->deadline) {
            return 'no_deadline';
        }

        $today = now()->startOfDay();
        $deadline = $this->deadline->startOfDay();

        if ($deadline->isPast()) {
            return 'overdue';
        } elseif ($deadline->diffInDays($today) <= 7) {
            return 'near_deadline';
        } else {
            return 'on_track';
        }
    }

    // Accessor untuk format deadline yang mudah dibaca
    public function getFormattedDeadlineAttribute()
    {
        if (!$this->deadline) {
            return 'No deadline set';
        }

        return $this->deadline->format('M d, Y');
    }
}
