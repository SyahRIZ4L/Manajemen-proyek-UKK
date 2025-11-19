// Timer Display Function for Team Lead Card Detail Modal
function displayTimerInfo(card) {
    let timerHtml = '';

    // Status: TODO - Belum di start
    if (card.status === 'todo') {
        timerHtml = `
            <div class="alert alert-secondary mb-3">
                <i class="bi bi-clock-history me-2"></i>
                <strong>Belum di start</strong> - Tugas belum dikerjakan
            </div>
        `;
    }
    // Status: IN_PROGRESS - Timer Active (Real-time)
    else if (card.status === 'in_progress' && card.is_timer_active && card.timer_started_at) {
        const timerId = 'cardTimerDisplay-' + card.card_id;
        timerHtml = `
            <div class="alert alert-info mb-3 d-flex align-items-center">
                <i class="bi bi-stopwatch fs-4 me-3"></i>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Timer Active</h6>
                    <p class="mb-0">Working time: <strong id="${timerId}">Calculating...</strong></p>
                    <small class="text-muted">Started at ${new Date(card.timer_started_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' })}</small>
                </div>
            </div>
        `;

        // Start timer update after DOM is ready
        setTimeout(() => {
            const startTime = new Date(card.timer_started_at).getTime();
            const timerElement = document.getElementById(timerId);

            function updateTimer() {
                if (!timerElement) return;

                const now = new Date().getTime();
                const elapsed = Math.floor((now - startTime) / 1000);

                const hours = Math.floor(elapsed / 3600);
                const minutes = Math.floor((elapsed % 3600) / 60);
                const seconds = elapsed % 60;

                timerElement.textContent =
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');
            }

            updateTimer();
            const interval = setInterval(updateTimer, 1000);

            // Cleanup when modal is closed
            const modalElement = document.getElementById('cardDetailModal');
            if (modalElement) {
                modalElement.addEventListener('hidden.bs.modal', function() {
                    clearInterval(interval);
                }, { once: true });
            }
        }, 100);
    }
    // Status: REVIEW - Timer Paused
    else if (card.status === 'review') {
        timerHtml = `
            <div class="alert alert-warning mb-3">
                <i class="bi bi-pause-circle me-2"></i>
                <strong>Timer Paused</strong> - Card is in review
                ${card.actual_hours ? `<br><small>Total working time: ${card.actual_hours} hours</small>` : ''}
            </div>
        `;
    }
    // Status: DONE - Timer Stopped
    else if (card.status === 'done' && card.actual_hours) {
        timerHtml = `
            <div class="alert alert-success mb-3">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Completed</strong> - Total working time: ${card.actual_hours} hours
            </div>
        `;
    }

    return timerHtml;
}
