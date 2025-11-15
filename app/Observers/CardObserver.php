<?php

namespace App\Observers;

use App\Models\Card;
use Illuminate\Support\Facades\Log;

class CardObserver
{
    /**
     * Handle the Card "created" event.
     */
    public function created(Card $card): void
    {
        //
    }

    /**
     * Handle the Card "updated" event.
     */
    public function updated(Card $card)
    {
        // Handle status changes for auto timer
        if ($card->isDirty('status')) {
            Log::info('CardObserver: Status change detected', [
                'card_id' => $card->card_id,
                'old_status' => $card->getOriginal('status'),
                'new_status' => $card->status
            ]);

            $card->handleStatusChange($card->getOriginal('status'), $card->status);
        }
    }

    /**
     * Handle the Card "deleted" event.
     */
    public function deleted(Card $card): void
    {
        // Stop any active timers when card is deleted
        $card->stopAutoTimer();
    }

    /**
     * Handle the Card "restored" event.
     */
    public function restored(Card $card): void
    {
        //
    }

    /**
     * Handle the Card "force deleted" event.
     */
    public function forceDeleted(Card $card): void
    {
        //
    }
}
