<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'password',
        'full_name',
        'email',
        'role',
        'current_task_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Define role constants
    const ROLES = [
        'PROJECT_ADMIN' => 'Project_Admin',
        'TEAM_LEAD' => 'Team_Lead',
        'DEVELOPER' => 'Developer',
        'DESIGNER' => 'Designer',
        'MEMBER' => 'member'
    ];

    // Define task status constants
    const TASK_STATUS = [
        'IDLE' => 'idle',
        'WORKING' => 'working'
    ];

    // Helper methods
    public function isProjectAdmin()
    {
        return $this->role === self::ROLES['PROJECT_ADMIN'];
    }

    public function isTeamLead()
    {
        return $this->role === self::ROLES['TEAM_LEAD'];
    }

    public function isDeveloper()
    {
        return $this->role === self::ROLES['DEVELOPER'];
    }

    public function isDesigner()
    {
        return $this->role === self::ROLES['DESIGNER'];
    }

    public function isMember()
    {
        return $this->role === self::ROLES['MEMBER'];
    }

    public function isWorking()
    {
        return $this->current_task_status === self::TASK_STATUS['WORKING'];
    }

    public function isIdle()
    {
        return $this->current_task_status === self::TASK_STATUS['IDLE'];
    }
}
