<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check Mike's projects (designer)
echo "=== MIKE'S PROJECTS (DESIGNER) ===\n";
$mikeProjects = DB::table('members')
    ->join('projects', 'members.project_id', '=', 'projects.project_id')
    ->join('users', 'members.user_id', '=', 'users.user_id')
    ->where('users.username', 'desainer')
    ->get(['projects.project_id', 'projects.project_name', 'members.role', 'users.user_id']);

foreach ($mikeProjects as $project) {
    echo "User ID: {$project->user_id}, Project: {$project->project_name} (ID: {$project->project_id}) - Role: {$project->role}\n";
}

if (count($mikeProjects) > 0) {
    $designer = $mikeProjects[0];

    // Find boards for Mike's projects
    echo "\n=== BOARDS IN MIKE'S PROJECTS ===\n";
    $boards = DB::table('boards')
        ->join('projects', 'boards.project_id', '=', 'projects.project_id')
        ->join('members', 'projects.project_id', '=', 'members.project_id')
        ->where('members.user_id', $designer->user_id)
        ->get(['boards.board_id', 'boards.board_name', 'projects.project_name']);

    foreach ($boards as $board) {
        echo "Board: {$board->board_name} (ID: {$board->board_id}) in Project: {$board->project_name}\n";
    }

    if (count($boards) > 0) {
        $selectedBoard = $boards[0];

        echo "\n=== CREATING TEST CARDS FOR MIKE (DESIGNER) ===\n";

        // Create a test card for designer
        $cardId = DB::table('cards')->insertGetId([
            'board_id' => $selectedBoard->board_id,
            'card_title' => 'Design Landing Page UI',
            'description' => 'Create a modern and responsive landing page design with mobile-first approach. Include hero section, features, and call-to-action.',
            'status' => 'todo',
            'priority' => 'high',
            'due_date' => date('Y-m-d', strtotime('+5 days')),
            'estimated_hours' => 12,
            'created_by' => 2, // Team lead
            'position' => 3
        ]);

        echo "Created design card with ID: $cardId\n";

        // Assign card to Mike (designer)
        DB::table('card_assignments')->insert([
            'card_id' => $cardId,
            'user_id' => $designer->user_id
        ]);

        echo "Assigned card to Mike (user_id: {$designer->user_id})\n";

        // Create another design card
        $cardId2 = DB::table('cards')->insertGetId([
            'board_id' => $selectedBoard->board_id,
            'card_title' => 'Create Brand Guidelines',
            'description' => 'Develop comprehensive brand guidelines including color palette, typography, logos, and usage instructions.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'due_date' => date('Y-m-d', strtotime('+10 days')),
            'estimated_hours' => 16,
            'created_by' => 2, // Team lead
            'position' => 4
        ]);

        echo "Created second design card with ID: $cardId2\n";

        // Assign second card to Mike
        DB::table('card_assignments')->insert([
            'card_id' => $cardId2,
            'user_id' => $designer->user_id
        ]);

        echo "Assigned second card to Mike\n";

        echo "\n=== TEST CARDS CREATED SUCCESSFULLY ===\n";
        echo "Mike (Designer) now has 2 test cards assigned to him.\n";
        echo "You can test the designer panel at: /designer/panel\n";
    } else {
        echo "No boards found for Mike's projects. Cannot create test cards.\n";
    }
} else {
    echo "No projects found for Mike (desainer). Cannot create test cards.\n";
}
?>
