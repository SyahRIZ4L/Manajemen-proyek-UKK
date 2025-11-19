<!-- Timer Status Display -->
@if(isset($card->status) && $card->status === 'todo')
    {{-- Status: TODO - Belum di start --}}
    <div class="alert alert-secondary mb-3">
        <i class="bi bi-clock-history me-2"></i>
        <strong>Belum di start</strong> - Card belum dikerjakan
    </div>
@elseif(isset($card->status) && $card->status === 'in_progress' && isset($card->is_timer_active) && $card->is_timer_active && $card->timer_started_at)
    {{-- Status: IN_PROGRESS - Timer Active (Real-time) --}}
    <div class="alert alert-info mb-3 d-flex align-items-center">
        <i class="bi bi-stopwatch fs-4 me-3"></i>
        <div class="flex-grow-1">
            <h6 class="mb-1">Timer Active</h6>
            <p class="mb-0">Working time: <strong id="cardTimerDisplay-{{ $card->card_id }}">Calculating...</strong></p>
            <small class="text-muted">Started at {{ \Carbon\Carbon::parse($card->timer_started_at)->format('H:i:s') }}</small>
        </div>
    </div>
    <script>
        (function() {
            const startTime = new Date('{{ $card->timer_started_at }}').getTime();
            const timerElement = document.getElementById('cardTimerDisplay-{{ $card->card_id }}');

            function updateTimer() {
                const now = new Date().getTime();
                const elapsed = Math.floor((now - startTime) / 1000);

                const hours = Math.floor(elapsed / 3600);
                const minutes = Math.floor((elapsed % 3600) / 60);
                const seconds = elapsed % 60;

                if (timerElement) {
                    timerElement.textContent =
                        String(hours).padStart(2, '0') + ':' +
                        String(minutes).padStart(2, '0') + ':' +
                        String(seconds).padStart(2, '0');
                }
            }

            updateTimer();
            const interval = setInterval(updateTimer, 1000);

            // Cleanup when modal is closed
            const modalElement = document.getElementById('cardDetailModal');
            if (modalElement) {
                modalElement.addEventListener('hidden.bs.modal', function() {
                    clearInterval(interval);
                });
            }
        })();
    </script>
@elseif(isset($card->status) && $card->status === 'review')
    {{-- Status: REVIEW - Timer Paused --}}
    <div class="alert alert-warning mb-3">
        <i class="bi bi-pause-circle me-2"></i>
        <strong>Timer Paused</strong> - Card is in review
        @if(isset($card->actual_hours) && $card->actual_hours)
            <br><small>Total working time: {{ $card->actual_hours }} hours</small>
        @endif
    </div>
@elseif(isset($card->status) && $card->status === 'done' && isset($card->actual_hours) && $card->actual_hours)
    {{-- Status: DONE - Timer Stopped --}}
    <div class="alert alert-success mb-3">
        <i class="bi bi-check-circle me-2"></i>
        <strong>Completed</strong> - Total working time: {{ $card->actual_hours }} hours
    </div>
@endif
