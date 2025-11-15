<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TimeLogController extends Controller
{
    /**
     * Get all time logs for the authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $query = TimeLog::where('user_id', $userId)
            ->with(['card', 'subtask', 'user'])
            ->orderBy('start_time', 'desc');

        // Filter by card if provided
        if ($request->has('card_id') && $request->card_id) {
            $query->where('card_id', $request->card_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('start_time', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('start_time', '<=', $request->date_to);
        }

        // Filter by status (active/completed)
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->whereNull('end_time');
            } elseif ($request->status === 'completed') {
                $query->whereNotNull('end_time');
            }
        }

        $timeLogs = $query->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'time_logs' => $timeLogs->items(),
            'pagination' => [
                'current_page' => $timeLogs->currentPage(),
                'last_page' => $timeLogs->lastPage(),
                'per_page' => $timeLogs->perPage(),
                'total' => $timeLogs->total(),
            ],
            'statistics' => [
                'total_time_today' => $this->getTotalTimeToday($userId),
                'total_time_week' => $this->getTotalTimeWeek($userId),
                'total_time_month' => $this->getTotalTimeMonth($userId),
                'active_timer' => TimeLog::getActiveTimer($userId)
            ]
        ]);
    }

    /**
     * Start a new time log
     */
    public function start(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'card_id' => 'required|exists:cards,card_id',
            'subtask_id' => 'nullable|exists:subtasks,subtask_id',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = Auth::id();

        try {
            // Stop any active timer for this user
            $activeTimer = TimeLog::getActiveTimer($userId);
            if ($activeTimer) {
                $activeTimer->stop();
            }

            // Create new timer
            $timeLog = TimeLog::startTimer(
                $request->card_id,
                $userId,
                $request->description,
                $request->subtask_id
            );

            // Update card status
            $card = Card::find($request->card_id);
            if ($card) {
                $card->is_timer_active = true;
                $card->timer_started_at = now();
                if ($card->status === 'todo') {
                    $card->status = 'in_progress';
                }
                if (!$card->started_at) {
                    $card->started_at = now();
                }
                $card->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Timer started successfully',
                'time_log' => $timeLog->load(['card', 'subtask']),
                'card' => $card
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start timer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stop the active time log
     */
    public function stop(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $activeTimer = TimeLog::getActiveTimer($userId);

        if (!$activeTimer) {
            return response()->json([
                'success' => false,
                'message' => 'No active timer found'
            ], 404);
        }

        try {
            $activeTimer->stop();

            // Update card status
            $card = Card::find($activeTimer->card_id);
            if ($card) {
                $card->is_timer_active = false;
                $card->timer_started_at = null;
                $card->updateActualHours();
                $card->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Timer stopped successfully',
                'time_log' => $activeTimer->fresh(['card', 'subtask']),
                'card' => $card
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to stop timer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the current active timer
     */
    public function getActiveTimer(): JsonResponse
    {
        $userId = Auth::id();
        $activeTimer = TimeLog::getActiveTimer($userId);

        if (!$activeTimer) {
            return response()->json([
                'success' => true,
                'active_timer' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'active_timer' => [
                'id' => $activeTimer->log_id,
                'card_id' => $activeTimer->card_id,
                'card_title' => $activeTimer->card->card_title,
                'subtask_id' => $activeTimer->subtask_id,
                'subtask_title' => $activeTimer->subtask->title ?? null,
                'description' => $activeTimer->description,
                'start_time' => $activeTimer->start_time,
                'current_duration' => $activeTimer->getCurrentDuration(),
                'formatted_duration' => $this->formatMinutes($activeTimer->getCurrentDuration()),
                'is_auto_timer' => $activeTimer->isAutoTimer(),
                'auto_timer_type' => $activeTimer->auto_timer_type ?? null,
                'card_status' => $activeTimer->card->status ?? null
            ]
        ]);
    }

    /**
     * Update time log description
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = Auth::id();
        $timeLog = TimeLog::where('log_id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$timeLog) {
            return response()->json([
                'success' => false,
                'message' => 'Time log not found'
            ], 404);
        }

        try {
            $timeLog->description = $request->description;
            $timeLog->save();

            return response()->json([
                'success' => true,
                'message' => 'Time log updated successfully',
                'time_log' => $timeLog->load(['card', 'subtask'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update time log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a time log
     */
    public function destroy($id): JsonResponse
    {
        $userId = Auth::id();
        $timeLog = TimeLog::where('log_id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$timeLog) {
            return response()->json([
                'success' => false,
                'message' => 'Time log not found'
            ], 404);
        }

        // Don't allow deletion of active timers
        if ($timeLog->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete active timer. Stop it first.'
            ], 400);
        }

        try {
            $cardId = $timeLog->card_id;
            $timeLog->delete();

            // Update card actual hours
            $card = Card::find($cardId);
            if ($card) {
                $card->updateActualHours();
            }

            return response()->json([
                'success' => true,
                'message' => 'Time log deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete time log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get time logs for a specific card
     */
    public function getCardTimeLogs($cardId): JsonResponse
    {
        $userId = Auth::id();

        $timeLogs = TimeLog::where('card_id', $cardId)
            ->where('user_id', $userId)
            ->with(['card', 'subtask', 'user'])
            ->orderBy('start_time', 'desc')
            ->get();

        $totalTime = TimeLog::getTotalTimeForCard($cardId);
        $card = Card::find($cardId);

        return response()->json([
            'success' => true,
            'time_logs' => $timeLogs,
            'total_time_minutes' => $totalTime,
            'total_time_formatted' => $this->formatMinutes($totalTime),
            'card' => $card ? [
                'id' => $card->card_id,
                'title' => $card->card_title,
                'deadline' => $card->deadline ?? $card->due_date,
                'deadline_status' => $card->getDeadlineStatus(),
                'estimated_hours' => $card->estimated_hours,
                'actual_hours' => $card->actual_hours,
                'progress_percentage' => $card->progress_percentage
            ] : null
        ]);
    }



    /**
     * Get user statistics
     */
    public function getStatistics(): JsonResponse
    {
        $userId = Auth::id();

        return response()->json([
            'success' => true,
            'statistics' => [
                'today' => [
                    'total_minutes' => $this->getTotalTimeToday($userId),
                    'formatted' => $this->formatMinutes($this->getTotalTimeToday($userId)),
                    'sessions' => $this->getSessionsCount($userId, 'today')
                ],
                'week' => [
                    'total_minutes' => $this->getTotalTimeWeek($userId),
                    'formatted' => $this->formatMinutes($this->getTotalTimeWeek($userId)),
                    'sessions' => $this->getSessionsCount($userId, 'week')
                ],
                'month' => [
                    'total_minutes' => $this->getTotalTimeMonth($userId),
                    'formatted' => $this->formatMinutes($this->getTotalTimeMonth($userId)),
                    'sessions' => $this->getSessionsCount($userId, 'month')
                ],
                'active_timer' => TimeLog::getActiveTimer($userId)
            ]
        ]);
    }

    // Helper methods
    private function getTotalTimeToday($userId): int
    {
        return TimeLog::getTotalTimeForUser($userId, Carbon::today(), Carbon::today()->endOfDay());
    }

    private function getTotalTimeWeek($userId): int
    {
        return TimeLog::getTotalTimeForUser($userId, Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
    }

    private function getTotalTimeMonth($userId): int
    {
        return TimeLog::getTotalTimeForUser($userId, Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());
    }

    private function getSessionsCount($userId, $period): int
    {
        $query = TimeLog::where('user_id', $userId)->whereNotNull('end_time');

        switch ($period) {
            case 'today':
                $query->whereDate('start_time', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('start_time', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                break;
        }

        return $query->count();
    }

    private function formatMinutes($minutes): string
    {
        if (!$minutes) {
            return '0m';
        }

        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $mins . 'm';
        }

        return $mins . 'm';
    }
}
