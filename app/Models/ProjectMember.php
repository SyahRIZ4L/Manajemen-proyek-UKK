<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $primaryKey = 'member_id';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'joined_at'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    // Relasi dengan Project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
