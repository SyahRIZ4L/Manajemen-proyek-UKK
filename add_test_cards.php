<?php

use Illuminate\Support\Facades\DB;

// Add test cards for existing team lead user (user_id = 2)
$teamLeadId = 2;
$projectId = DB::table('projects')->first()->project_id ?? 1;
$boardId = DB::table('boards')->first()->board_id ?? 1;

DB::table('cards')->insert([
    [
        'board_id' => $boardId,
        'card_title' => 'Team Lead Task 1',
        'description' => 'First test card created by existing team lead',
        'position' => 10,
        'created_by' => $teamLeadId,
        'status' => 'todo',
        'priority' => 'high',
        'created_at' => now(),
    ],
    [
        'board_id' => $boardId,
        'card_title' => 'Team Lead Task 2',
        'description' => 'Second test card created by existing team lead',
        'position' => 11,
        'created_by' => $teamLeadId,
        'status' => 'in_progress',
        'priority' => 'medium',
        'created_at' => now(),
    ]
]);

echo "Added 2 test cards for existing team lead (user_id: $teamLeadId)\n";
