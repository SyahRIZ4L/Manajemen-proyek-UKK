<?php
// Simple test script to check boards API data

// Load Composer autoload
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Now we can use facades
use Illuminate\Support\Facades\DB;

// Simulate the query that happens in TeamLeadBoardController
try {
    echo "Testing Boards API Query\n";
    echo "========================\n\n";

    $boards = DB::table('boards')
        ->join('projects', 'boards.project_id', '=', 'projects.project_id')
        ->join('members', 'projects.project_id', '=', 'members.project_id')
        ->leftJoin('cards', 'boards.board_id', '=', 'cards.board_id')
        ->where('members.user_id', 2) // Team Lead user_id
        ->where('members.role', 'Team_Lead')
        ->select(
            'boards.board_id as id',
            'boards.board_name as name',
            'boards.description',
            'boards.created_at',
            'projects.project_name',
            DB::raw('COUNT(cards.card_id) as total_cards'),
            DB::raw('COUNT(CASE WHEN cards.status = "todo" THEN 1 END) as todo_cards'),
            DB::raw('COUNT(CASE WHEN cards.status = "in_progress" THEN 1 END) as in_progress_cards'),
            DB::raw('COUNT(CASE WHEN cards.status = "review" THEN 1 END) as review_cards'),
            DB::raw('COUNT(CASE WHEN cards.status = "done" THEN 1 END) as done_cards')
        )
        ->groupBy('boards.board_id', 'boards.board_name', 'boards.description', 'boards.created_at', 'projects.project_name')
        ->orderBy('boards.created_at', 'desc')
        ->get();

    echo "Total boards found: " . count($boards) . "\n\n";

    if (count($boards) > 0) {
        foreach ($boards as $board) {
            echo "Board ID: " . $board->id . "\n";
            echo "Board Name: " . $board->name . "\n";
            echo "Project: " . $board->project_name . "\n";
            echo "Total Cards: " . $board->total_cards . "\n";
            echo "In Progress: " . $board->in_progress_cards . "\n";
            echo "Review: " . $board->review_cards . "\n";
            echo "Done: " . $board->done_cards . "\n";
            echo "Description: " . ($board->description ?: 'No description') . "\n";
            echo "Created: " . $board->created_at . "\n";
            echo "------------------------\n\n";
        }
    } else {
        echo "No boards found for Team Lead user_id = 2\n";

        // Let's check the data separately
        echo "\nDebugging info:\n";
        echo "Total Boards: " . DB::table('boards')->count() . "\n";
        echo "Total Projects: " . DB::table('projects')->count() . "\n";
        echo "Members with Team_Lead role: " . DB::table('members')->where('role', 'Team_Lead')->count() . "\n";
        echo "Members for user_id 2: " . DB::table('members')->where('user_id', 2)->count() . "\n";

        // Check if user 2 has a project
        $member = DB::table('members')->where('user_id', 2)->first();
        if ($member) {
            echo "User 2 project_id: " . $member->project_id . "\n";
            echo "User 2 role: " . $member->role . "\n";

            // Check boards for that project
            $projectBoards = DB::table('boards')->where('project_id', $member->project_id)->count();
            echo "Boards in project " . $member->project_id . ": " . $projectBoards . "\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
