<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Board extends Model
{
    use HasFactory;

    protected $table = 'boards';
    protected $primaryKey = 'board_id';
    public $timestamps = false; // Hanya ada created_at

    protected $fillable = [
        'board_name',
        'description',
        'project_id',
        'position'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relasi dengan Project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Relasi dengan Cards
    public function cards()
    {
        return $this->hasMany(Card::class, 'board_id');
    }
}
