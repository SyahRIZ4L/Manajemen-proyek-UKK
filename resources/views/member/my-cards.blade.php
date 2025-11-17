@extends('member.layout')

@section('title', 'My Cards')
@section('page-title', 'My Cards')

@section('content')
<!-- Projects Information Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-diagram-3 me-2"></i>
                    Projects I'm Following
                </h5>
                @if(isset($memberProjects) && count($memberProjects) > 0)
                    <span class="badge bg-white text-info">{{ count($memberProjects) }}</span>
                @else
                    <span class="badge bg-white text-info">0</span>
                @endif
            </div>
            <div class="card-body">
                @if(isset($memberProjects) && count($memberProjects) > 0)
                    <div class="row">
                        @foreach($memberProjects as $project)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-0 shadow-sm project-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title text-info mb-1" title="{{ $project->project_name }}">
                                                {{ Str::limit($project->project_name, 25) }}
                                            </h6>
                                            @php
                                                $statusClass = match($project->status ?? 'active') {
                                                    'active', 'in_progress' => 'success',
                                                    'completed', 'done' => 'primary',
                                                    'paused', 'on_hold' => 'warning',
                                                    'cancelled', 'inactive' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }} ms-2">{{ $project->status ?? 'Active' }}</span>
                                        </div>

                                        <p class="card-text text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ $project->description ?? 'No description available' }}
                                        </p>

                                        @if(isset($project->progress_percentage))
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <small class="text-muted">Progress</small>
                                                    <small class="fw-bold text-info">{{ $project->progress_percentage }}%</small>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-info" style="width: {{ $project->progress_percentage }}%"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="text-info fw-bold">{{ $project->total_cards ?? 0 }}</div>
                                                <small class="text-muted">Cards</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-success fw-bold">{{ $project->completed_cards ?? 0 }}</div>
                                                <small class="text-muted">Done</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-primary fw-bold">{{ $project->team_size ?? 0 }}</div>
                                                <small class="text-muted">Team</small>
                                            </div>
                                        </div>

                                        <hr class="my-3">

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-person-circle me-1"></i>
                                                {{ $project->member_role ?? 'Member' }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ isset($project->joined_at) ? $project->joined_at->format('M d, Y') : ($project->created_at ? $project->created_at->format('M d, Y') : 'N/A') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-folder2-open fs-2 d-block mb-2"></i>
                            <h6>No Projects Found</h6>
                            <p class="mb-0">You are not currently following any projects.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <!-- Status Filter Tabs -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'all' ? 'active' : '' }}"
                           href="{{ route('member.my-cards', ['status' => 'all']) }}">
                            All <span class="badge bg-secondary ms-1">{{ $statusCounts['all'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'todo' ? 'active' : '' }}"
                           href="{{ route('member.my-cards', ['status' => 'todo']) }}">
                            To Do <span class="badge bg-secondary ms-1">{{ $statusCounts['todo'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'in_progress' ? 'active' : '' }}"
                           href="{{ route('member.my-cards', ['status' => 'in_progress']) }}">
                            In Progress <span class="badge bg-primary ms-1">{{ $statusCounts['in_progress'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'review' ? 'active' : '' }}"
                           href="{{ route('member.my-cards', ['status' => 'review']) }}">
                            Review <span class="badge bg-warning ms-1">{{ $statusCounts['review'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'done' ? 'active' : '' }}"
                           href="{{ route('member.my-cards', ['status' => 'done']) }}">
                            Done <span class="badge bg-success ms-1">{{ $statusCounts['done'] }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if($assignments->count() > 0)
            @foreach($assignments as $assignment)
                @php
                    $card = $assignment->card;
                    $isOverdue = $card->is_overdue;
                @endphp
                <div class="card mb-3 priority-{{ $card->priority }} {{ $isOverdue ? 'border-danger' : '' }}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <!-- Project Badge -->
                                        @if($card->board && $card->board->project)
                                            <div class="mb-2">
                                                <span class="badge bg-info text-white">
                                                    <i class="bi bi-folder me-1"></i>
                                                    {{ $card->board->project->project_name }}
                                                </span>
                                            </div>
                                        @endif

                                        <h5 class="card-title mb-2">
                                            {{ $card->card_title }}
                                            @if($card->estimated_hours)
                                                <span class="text-muted">({{ $card->estimated_hours }} jam)</span>
                                            @endif
                                            @if($card->priority)
                                                <span class="text-{{ $card->priority === 'high' ? 'danger' : ($card->priority === 'medium' ? 'warning' : 'success') }}">
                                                    - priority: {{ $card->priority }}
                                                </span>
                                            @endif
                                            @if($isOverdue)
                                                <i class="bi bi-exclamation-triangle text-danger ms-1"
                                                   title="Overdue"></i>
                                            @endif
                                        </h5>                                        <p class="card-text text-muted small mb-2">
                                            {{ Str::limit($card->description, 100) }}
                                        </p>

                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-diagram-3 me-1"></i>
                                                {{ $card->board->board_name ?? 'No Board' }}
                                            </small>

                                            @if($card->board && $card->board->project)
                                                <small class="text-primary fw-semibold">
                                                    <i class="bi bi-folder me-1"></i>
                                                    Project: {{ $card->board->project->project_name }}
                                                </small>
                                            @endif

                                            @if($card->due_date)
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    Due: {{ $card->due_date->format('M d, Y') }}
                                                    @if($isOverdue)
                                                        <span class="text-danger">({{ $card->due_date->diffForHumans() }})</span>
                                                    @endif
                                                </small>
                                            @endif

                                            @if($card->creator)
                                                <small class="text-muted">
                                                    <i class="bi bi-person me-1"></i>
                                                    Created by {{ $card->creator->full_name }}
                                                </small>
                                            @endif

                                            @if($card->estimated_hours)
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Est: {{ $card->estimated_hours }}h
                                                    @if($card->actual_hours)
                                                        / Actual: {{ $card->actual_hours }}h
                                                    @endif
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 text-end">
                                <div class="mb-2">
                                    @php
                                        $statusColors = [
                                            'todo' => 'secondary',
                                            'in_progress' => 'primary',
                                            'review' => 'warning',
                                            'done' => 'success'
                                        ];
                                        $statusColor = $statusColors[$card->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }} me-1">
                                        {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                                    </span>

                                    @if($card->priority)
                                        <span class="badge bg-{{ $card->priority === 'high' ? 'danger' : ($card->priority === 'medium' ? 'warning' : 'success') }}">
                                            {{ ucfirst($card->priority) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="btn-group" role="group">
                                    <button onclick="showCardDetailModal({{ $card->card_id }})"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </button>

                                    @if($card->status === 'todo')
                                        <button class="btn btn-sm btn-success start-work-btn"
                                                data-card-id="{{ $card->card_id }}"
                                                data-card-title="{{ $card->card_title }}">
                                            <i class="bi bi-play"></i> Start
                                        </button>
                                    @elseif($card->status === 'in_progress')
                                        <button class="btn btn-sm btn-warning submit-review-btn"
                                                data-card-id="{{ $card->card_id }}"
                                                data-card-title="{{ $card->card_title }}">
                                            <i class="bi bi-check-circle"></i> Submit
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No Cards Found</h4>
                    <p class="text-muted">
                        @if($status === 'all')
                            You don't have any cards assigned to you yet.
                        @else
                            You don't have any cards with {{ ucfirst(str_replace('_', ' ', $status)) }} status.
                        @endif
                    </p>
                    <a href="{{ route('member.my-cards', ['status' => 'all']) }}" class="btn btn-primary">
                        View All Cards
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Start Timer Modal -->
<div class="modal fade" id="startTimerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start Timer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="startTimerForm">
                    <div class="mb-3">
                        <label class="form-label">Card:</label>
                        <p class="fw-bold" id="timerCardTitle"></p>
                    </div>
                    <div class="mb-3">
                        <label for="timerDescription" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="timerDescription" rows="3"
                                  placeholder="What are you working on?"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmStartTimer">Start Timer</button>
            </div>
        </div>
    </div>
</div>

<!-- Card Detail Modal -->
<div class="modal fade" id="cardDetailModal" tabindex="-1" aria-labelledby="cardDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cardDetailModalLabel">Card Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="cardDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, setting up event listeners...');
    initializeCardButtons();
});

function initializeCardButtons() {
    console.log('Initializing card buttons...');

    // Start work buttons dengan konfirmasi
    const startWorkButtons = document.querySelectorAll('.start-work-btn');
    console.log('Found ' + startWorkButtons.length + ' start work buttons');

    startWorkButtons.forEach(function(button) {
        const cardId = button.getAttribute('data-card-id');
        const cardTitle = button.getAttribute('data-card-title');
        console.log('Setting up start button for card ' + cardId + ': ' + cardTitle);

        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('Start button clicked for card ' + cardId);

            if (button.disabled) {
                console.log('Button already processing, ignoring click');
                return;
            }

            const currentCardId = this.getAttribute('data-card-id');
            const currentCardTitle = this.getAttribute('data-card-title');

            console.log('Showing confirmation modal for start card...');
            showConfirmationModal(
                'Mulai Mengerjakan Card',
                'Apakah Anda yakin ingin mulai mengerjakan "' + currentCardTitle + '"? Status akan berubah menjadi In Progress dan timer akan dimulai secara otomatis.',
                'Mulai Kerjakan',
                'btn-success',
                function() {
                    console.log('Confirmed: Starting card ' + currentCardId);
                    updateCardStatus(currentCardId, 'in_progress', 'Card berhasil dimulai!');
                }
            );
        });
    });

    // Submit for review buttons dengan konfirmasi
    const submitReviewButtons = document.querySelectorAll('.submit-review-btn');
    console.log('Found ' + submitReviewButtons.length + ' submit review buttons');

    submitReviewButtons.forEach(function(button) {
        const cardId = button.getAttribute('data-card-id');
        const cardTitle = button.getAttribute('data-card-title');
        console.log('Setting up submit button for card ' + cardId + ': ' + cardTitle);

        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('Submit button clicked for card ' + cardId);

            // Prevent double-clicks
            if (button.disabled) {
                console.log('Button already processing, ignoring click');
                return;
            }

            const currentCardId = this.getAttribute('data-card-id');
            const currentCardTitle = this.getAttribute('data-card-title');

            console.log('Showing confirmation modal for submit card...');
            showConfirmationModal(
                'Submit untuk Review',
                'Apakah Anda yakin ingin submit "' + currentCardTitle + '" untuk review? Pastikan pekerjaan sudah selesai.',
                'Submit Review',
                'btn-warning',
                function() {
                    console.log('Confirmed: Submitting card ' + currentCardId + ' for review');
                    updateCardStatus(currentCardId, 'review', 'Card berhasil disubmit untuk review!');
                }
            );
        });
    });

    console.log('All card buttons initialized successfully');

    // Start timer buttons setup
    setupTimerButtons();
}

function setupTimerButtons() {
    var currentCardId = null;
    const startTimerButtons = document.querySelectorAll('.start-timer-btn');
    console.log('Found ' + startTimerButtons.length + ' start timer buttons');

    startTimerButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            currentCardId = this.getAttribute('data-card-id');
            const cardTitle = this.getAttribute('data-card-title');
            console.log('Timer button clicked for card ' + currentCardId + ': ' + cardTitle);

            document.getElementById('timerCardTitle').textContent = cardTitle;
            document.getElementById('timerDescription').value = '';

            const modal = new bootstrap.Modal(document.getElementById('startTimerModal'));
            modal.show();
        });
    });

    // Setup confirm timer start button
    const confirmTimerBtn = document.getElementById('confirmStartTimer');
    if (confirmTimerBtn) {
        confirmTimerBtn.addEventListener('click', function() {
            if (currentCardId) {
                const description = document.getElementById('timerDescription').value;
                startTimer(currentCardId, description);
            }
        });
    }
}

function updateCardStatus(cardId, status, successMessage) {
    console.log('updateCardStatus called for card ' + cardId + ' with status: ' + status);

    // Prevent duplicate requests
    if (window.processingCardUpdate && window.processingCardUpdate[cardId]) {
        console.log('Already processing update for card ' + cardId + ', skipping...');
        return;
    }

    // Initialize processing tracker
    if (!window.processingCardUpdate) {
        window.processingCardUpdate = {};
    }
    window.processingCardUpdate[cardId] = true;

    // Validasi status transition yang diizinkan
    const validTransitions = {
        'todo': ['in_progress'],
        'in_progress': ['review', 'todo'], // Bisa kembali ke todo jika diperlukan
        'review': ['done', 'in_progress'], // Bisa kembali ke in_progress jika ada revisi
        'done': [] // Status final
    };

    // Ambil status saat ini dari card element
    const cardElement = document.querySelector('[data-card-id="' + cardId + '"]').closest('.card');
    if (!cardElement) {
        console.error('Card element not found for cardId: ' + cardId);
        delete window.processingCardUpdate[cardId];
        return;
    }

    const currentStatusBadge = cardElement.querySelector('.badge.bg-secondary, .badge.bg-primary, .badge.bg-warning, .badge.bg-success');
    let currentStatus = 'todo'; // Default

    if (currentStatusBadge) {
        const statusText = currentStatusBadge.textContent.toLowerCase();
        if (statusText.includes('progress')) currentStatus = 'in_progress';
        else if (statusText.includes('review')) currentStatus = 'review';
        else if (statusText.includes('done')) currentStatus = 'done';
        else currentStatus = 'todo';
    }

    console.log('Current status: ' + currentStatus + ', Target status: ' + status);

    // Validasi transisi status
    if (!validTransitions[currentStatus] || !validTransitions[currentStatus].includes(status)) {
        showAlert('warning', 'Tidak dapat mengubah status dari ' + currentStatus + ' ke ' + status);
        delete window.processingCardUpdate[cardId];
        return;
    }

    // Tampilkan loading indicator segera
    const loadingMessages = {
        'in_progress': 'Memulai card dan mengaktifkan timer...',
        'review': 'Mengirim untuk review...',
        'done': 'Menyelesaikan card...',
        'todo': 'Mengubah status...'
    };

    showLoadingAlert('Sedang memproses...', loadingMessages[status] || 'Memproses perubahan status...');

    // Update button visual langsung
    updateButtonLoading(cardId, status, true);

    fetch('/member/card/' + cardId + '/status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: status,
            current_status: currentStatus // Kirim status saat ini untuk validasi di backend
        })
    })
    .then(response => {
        console.log('Response status:', response.status);

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        return response.json();
    })
    .then(data => {
        console.log('Update response:', data);

        // Clear processing flag
        delete window.processingCardUpdate[cardId];

        // Hapus loading alert
        hideLoadingAlert();

        if (data.success) {
            // Update tampilan card langsung sebelum reload
            updateCardDisplay(cardId, status);

            // Pesan sukses berdasarkan status
            const successMessages = {
                'in_progress': 'Card berhasil dimulai! Timer telah diaktifkan.',
                'review': 'Card berhasil disubmit untuk review!',
                'done': 'Card berhasil diselesaikan!',
                'todo': 'Status card berhasil diubah!'
            };

            const finalMessage = successMessages[status] || successMessage;

            // Tambahan info jika timer dimulai
            if (data.timer_started) {
                showAlert('success', finalMessage + ' Timer otomatis sudah dimulai.');
            } else {
                showAlert('success', finalMessage);
            }

            // Reload halaman dengan delay untuk visual feedback
            setTimeout(() => {
                window.location.reload();
            }, 1200);

        } else {
            // Reset button jika gagal
            updateButtonLoading(cardId, status, false);

            const errorMsg = data.error || data.message || 'Gagal mengupdate status card';
            console.error('Backend error:', errorMsg);
            showAlert('danger', errorMsg);
        }
    })
    .catch(error => {
        console.error('Error updating card status:', error);

        // Clear processing flag
        delete window.processingCardUpdate[cardId];

        // Hapus loading dan reset button
        hideLoadingAlert();
        updateButtonLoading(cardId, status, false);

        let errorMessage = 'Terjadi kesalahan saat mengupdate card';

        // Handle specific error cases
        if (error.message.includes('403')) {
            errorMessage = 'Anda tidak memiliki izin untuk mengubah status card ini';
        } else if (error.message.includes('404')) {
            errorMessage = 'Card tidak ditemukan';
        } else if (error.message.includes('422')) {
            errorMessage = 'Data yang dikirim tidak valid';
        } else if (error.message.includes('500')) {
            errorMessage = 'Terjadi kesalahan server. Silakan coba lagi.';
        }

        showAlert('danger', errorMessage);
    });
}

function startTimer(cardId, description) {
    fetch(`/member/card/${cardId}/timer/start`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ description: description })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('startTimerModal'));
            modal.hide();

            showAlert('success', 'Timer started successfully!');

            // Refresh to show active timer
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert('danger', data.error || 'Failed to start timer');
        }
    })
    .catch(error => {
        console.error('Error starting timer:', error);
        showAlert('danger', 'An error occurred while starting the timer');
    });
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
    alertDiv.setAttribute('role', 'alert');

    const iconClass = {
        'success': 'bi-check-circle',
        'danger': 'bi-exclamation-triangle',
        'warning': 'bi-exclamation-triangle',
        'info': 'bi-info-circle'
    }[type] || 'bi-info-circle';

    alertDiv.innerHTML = `
        <i class="bi ${iconClass} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Insert at the top of the main content
    const mainContent = document.querySelector('.col-12');
    mainContent.insertBefore(alertDiv, mainContent.firstChild);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Show confirmation modal untuk Start dan Submit button
function showConfirmationModal(title, message, confirmText, confirmClass, onConfirm) {
    console.log('showConfirmationModal called with:', { title, confirmText });

    const modalId = 'confirmActionModal';
    const uniqueId = modalId + '_' + Date.now(); // Add timestamp to prevent conflicts

    // Remove any existing modals
    const existingModals = document.querySelectorAll('[id^="confirmActionModal"]');
    existingModals.forEach(modal => {
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        if (bootstrapModal) {
            bootstrapModal.dispose();
        }
        modal.remove();
    });

    // Create modal HTML with unique ID
    const modalHTML = `
        <div class="modal fade" id="${uniqueId}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <div class="modal-header border-bottom-0 bg-light">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-question-circle-fill me-2 text-primary"></i>${title}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <i class="bi bi-info-circle-fill text-info fs-3"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fs-6">${message}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x me-1"></i>Batal
                        </button>
                        <button type="button" class="btn ${confirmClass}" id="confirmBtn_${uniqueId}">
                            <i class="bi bi-check me-1"></i>${confirmText}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    const modalElement = document.getElementById(uniqueId);
    const confirmButton = document.getElementById(`confirmBtn_${uniqueId}`);

    console.log('Modal created, showing...', uniqueId);

    // Show modal
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: 'static',
        keyboard: false
    });

    modal.show();

    // Handle confirm button click
    confirmButton.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Confirm button clicked');

        // Disable button to prevent double-clicks
        confirmButton.disabled = true;
        confirmButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Processing...';

        // Hide modal
        modal.hide();

        // Execute callback after modal is hidden
        setTimeout(() => {
            if (onConfirm && typeof onConfirm === 'function') {
                console.log('Executing confirm callback');
                onConfirm();
            }
        }, 300);
    });

    // Clean up modal after hide
    modalElement.addEventListener('hidden.bs.modal', function() {
        console.log('Modal hidden, cleaning up');
        modal.dispose();
        this.remove();
    });

    // Focus on confirm button after modal is shown
    modalElement.addEventListener('shown.bs.modal', function() {
        confirmButton.focus();
    });
}

// Fungsi untuk menampilkan loading alert
function showLoadingAlert(title, message) {
    const existingAlert = document.getElementById('loadingAlert');
    if (existingAlert) {
        existingAlert.remove();
    }

    const alertDiv = document.createElement('div');
    alertDiv.id = 'loadingAlert';
    alertDiv.className = 'alert alert-info alert-dismissible fade show shadow-sm d-flex align-items-center';
    alertDiv.setAttribute('role', 'alert');

    alertDiv.innerHTML = `
        <div class="spinner-border spinner-border-sm me-3" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div>
            <strong>${title}</strong><br>
            <small>${message}</small>
        </div>
    `;

    const mainContent = document.querySelector('.col-12');
    mainContent.insertBefore(alertDiv, mainContent.firstChild);
}

// Fungsi untuk menyembunyikan loading alert
function hideLoadingAlert() {
    const loadingAlert = document.getElementById('loadingAlert');
    if (loadingAlert) {
        loadingAlert.remove();
    }
}

// Fungsi untuk update visual button saat loading
function updateButtonLoading(cardId, status, isLoading) {
    const cardElement = document.querySelector(`[data-card-id="${cardId}"]`).closest('.card');
    if (!cardElement) {
        console.log(`Card element not found for updateButtonLoading: ${cardId}`);
        return;
    }

    console.log(`Updating button loading state for card ${cardId}: ${isLoading ? 'loading' : 'normal'}`);

    const buttons = cardElement.querySelectorAll('.start-work-btn, .submit-review-btn');

    buttons.forEach(btn => {
        if (isLoading) {
            // Set loading state
            btn.disabled = true;
            btn.classList.add('pe-none');

            const icon = btn.querySelector('i');
            const originalIconClass = icon.className;

            // Store original icon class for restore
            btn.setAttribute('data-original-icon', originalIconClass);

            // Show spinner
            icon.className = 'spinner-border spinner-border-sm';

            // Update button text based on status
            const originalText = btn.innerHTML;
            btn.setAttribute('data-original-text', originalText);

            const loadingTexts = {
                'in_progress': '<i class="spinner-border spinner-border-sm"></i> Starting...',
                'review': '<i class="spinner-border spinner-border-sm"></i> Submitting...',
                'done': '<i class="spinner-border spinner-border-sm"></i> Finishing...',
                'todo': '<i class="spinner-border spinner-border-sm"></i> Processing...'
            };

            btn.innerHTML = loadingTexts[status] || '<i class="spinner-border spinner-border-sm"></i> Processing...';

        } else {
            // Restore normal state
            btn.disabled = false;
            btn.classList.remove('pe-none');

            // Restore original text and icon
            const originalText = btn.getAttribute('data-original-text');
            const originalIcon = btn.getAttribute('data-original-icon');

            if (originalText) {
                btn.innerHTML = originalText;
                btn.removeAttribute('data-original-text');
            } else {
                // Fallback: restore based on button type
                const icon = btn.querySelector('i');
                if (btn.classList.contains('start-work-btn')) {
                    icon.className = 'bi bi-play';
                    btn.innerHTML = '<i class="bi bi-play"></i> Start';
                } else if (btn.classList.contains('submit-review-btn')) {
                    icon.className = 'bi bi-check-circle';
                    btn.innerHTML = '<i class="bi bi-check-circle"></i> Submit';
                }
            }

            if (originalIcon) {
                btn.removeAttribute('data-original-icon');
            }
        }
    });

    // Visual feedback on the card itself
    if (isLoading) {
        cardElement.style.opacity = '0.7';
        cardElement.style.pointerEvents = 'none';
    } else {
        cardElement.style.opacity = '1';
        cardElement.style.pointerEvents = 'auto';
    }
}

// Fungsi untuk update tampilan card secara visual sebelum reload
function updateCardDisplay(cardId, newStatus) {
    const cardElement = document.querySelector(`[data-card-id="${cardId}"]`).closest('.card');
    if (!cardElement) {
        console.log(`Card element not found for cardId: ${cardId}`);
        return;
    }

    console.log(`Updating card display for ${cardId} to status: ${newStatus}`);

    // Update badge status
    const statusBadge = cardElement.querySelector('.badge.bg-secondary, .badge.bg-primary, .badge.bg-warning, .badge.bg-success');
    if (statusBadge) {
        const statusColors = {
            'todo': { class: 'bg-secondary', text: 'To Do' },
            'in_progress': { class: 'bg-primary', text: 'In Progress' },
            'review': { class: 'bg-warning', text: 'Review' },
            'done': { class: 'bg-success', text: 'Done' }
        };

        const newStatusConfig = statusColors[newStatus];
        if (newStatusConfig) {
            // Hapus class lama
            statusBadge.className = statusBadge.className.replace(/bg-(secondary|primary|warning|success)/g, '');
            // Tambah class baru
            statusBadge.className = `badge ${newStatusConfig.class} me-1`;
            statusBadge.textContent = newStatusConfig.text;
        }
    }

    // Update action buttons berdasarkan status baru
    const buttonGroup = cardElement.querySelector('.btn-group');
    if (buttonGroup) {
        const startBtn = buttonGroup.querySelector('.start-work-btn');
        const submitBtn = buttonGroup.querySelector('.submit-review-btn');

        // Hapus tombol yang ada (kecuali tombol View)
        if (startBtn) startBtn.remove();
        if (submitBtn) submitBtn.remove();

        // Tambahkan tombol sesuai status baru
        const viewBtn = buttonGroup.querySelector('button[onclick*="showCardDetailModal"]');
        if (newStatus === 'in_progress' && viewBtn) {
            // Tambah tombol Submit untuk status in_progress
            const newSubmitBtn = document.createElement('button');
            newSubmitBtn.className = 'btn btn-sm btn-warning submit-review-btn';
            newSubmitBtn.setAttribute('data-card-id', cardId);
            newSubmitBtn.setAttribute('data-card-title', cardElement.querySelector('.card-title').textContent.trim());
            newSubmitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Submit';

            // Tambah event listener untuk tombol baru
            newSubmitBtn.addEventListener('click', function() {
                const cardId = this.getAttribute('data-card-id');
                const cardTitle = this.getAttribute('data-card-title');

                showConfirmationModal(
                    'Submit untuk Review',
                    `Apakah Anda yakin ingin submit "${cardTitle}" untuk review? Pastikan pekerjaan sudah selesai.`,
                    'Submit Review',
                    'btn-warning',
                    function() {
                        updateCardStatus(cardId, 'review', 'Card berhasil disubmit untuk review!');
                    }
                );
            });

            viewBtn.parentNode.insertBefore(newSubmitBtn, viewBtn.nextSibling);
        } else if (newStatus === 'todo' && viewBtn) {
            // Tambah tombol Start untuk status todo
            const newStartBtn = document.createElement('button');
            newStartBtn.className = 'btn btn-sm btn-success start-work-btn';
            newStartBtn.setAttribute('data-card-id', cardId);
            newStartBtn.setAttribute('data-card-title', cardElement.querySelector('.card-title').textContent.trim());
            newStartBtn.innerHTML = '<i class="bi bi-play"></i> Start';

            // Tambah event listener untuk tombol baru
            newStartBtn.addEventListener('click', function() {
                const cardId = this.getAttribute('data-card-id');
                const cardTitle = this.getAttribute('data-card-title');

                showConfirmationModal(
                    'Mulai Mengerjakan Card',
                    `Apakah Anda yakin ingin mulai mengerjakan "${cardTitle}"? Status akan berubah menjadi In Progress.`,
                    'Mulai Kerjakan',
                    'btn-success',
                    function() {
                        updateCardStatus(cardId, 'in_progress', 'Card berhasil dimulai!');
                    }
                );
            });

            viewBtn.parentNode.insertBefore(newStartBtn, viewBtn.nextSibling);
        }
    }

    // Visual effect dengan warna berdasarkan status
    const statusEffectColors = {
        'todo': 'rgba(108, 117, 125, 0.3)',
        'in_progress': 'rgba(13, 110, 253, 0.3)',
        'review': 'rgba(255, 193, 7, 0.3)',
        'done': 'rgba(25, 135, 84, 0.3)'
    };

    cardElement.style.transition = 'all 0.4s ease';
    cardElement.style.transform = 'scale(1.02)';
    cardElement.style.boxShadow = `0 4px 15px ${statusEffectColors[newStatus] || 'rgba(0,123,255,0.3)'}`;

    // Tambah border untuk highlight
    const originalBorder = cardElement.style.border;
    cardElement.style.border = `2px solid ${statusEffectColors[newStatus] || '#007bff'}`;

    setTimeout(() => {
        cardElement.style.transform = 'scale(1)';
        cardElement.style.boxShadow = '';
        cardElement.style.border = originalBorder;
    }, 400);

    // Tambah notifikasi visual kecil di card
    const notification = document.createElement('div');
    notification.className = 'position-absolute top-0 end-0 translate-middle p-1';
    notification.innerHTML = '<span class="badge rounded-pill bg-success"><i class="bi bi-check"></i></span>';
    cardElement.style.position = 'relative';
    cardElement.appendChild(notification);

    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 2000);
}

// Show card detail modal
function showCardDetailModal(cardId) {
    const modal = new bootstrap.Modal(document.getElementById('cardDetailModal'));
    const content = document.getElementById('cardDetailContent');

    // Show loading
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;

    modal.show();

    // Fetch card details
    fetch(`/api/cards/${cardId}/detail`)
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers.get('content-type'));

            if (!response.ok) {
                return response.text().then(text => {
                    console.log('Error response body:', text);
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                });
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    console.log('Non-JSON response:', text);
                    throw new Error('Server returned HTML instead of JSON. Check if you are logged in.');
                });
            }

            return response.json();
        })
        .then(data => {
            console.log('Card details response:', data);
            if (data.success) {
                displayCardDetail(data.card, data.comments || []);
            } else {
                const errorMsg = data.message || 'Unknown error occurred';
                console.error('API Error:', data);

                // Handle authentication required
                if (data.redirect === '/login') {
                    content.innerHTML = `
                        <div class="alert alert-warning">
                            <strong>Authentication Required</strong>
                            <p>${errorMsg}</p>
                            <a href="/login" class="btn btn-primary btn-sm">Login</a>
                            <button class="btn btn-secondary btn-sm" onclick="window.location.reload()">Refresh Page</button>
                        </div>
                    `;
                } else {
                    content.innerHTML = `
                        <div class="alert alert-danger">
                            <strong>Error loading card details:</strong> ${errorMsg}
                            <br><small>Card ID: ${cardId}</small>
                            ${data.error_details ? `<br><small>Details: ${data.error_details}</small>` : ''}
                        </div>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <strong>Network Error:</strong> ${error.message}
                    <br><small>Card ID: ${cardId}</small>
                    <br><small>Please check if you are logged in and have access to this card.</small>
                </div>
            `;
        });
}

// Display card details in modal
function displayCardDetail(card, comments) {
    const content = document.getElementById('cardDetailContent');

    // Format dates
    const createdDate = new Date(card.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    const dueDate = card.due_date ? new Date(card.due_date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }) : 'No due date';

    // Get status and priority colors
    const statusColors = {
        'todo': 'secondary',
        'in_progress': 'primary',
        'review': 'warning',
        'done': 'success'
    };
    const statusClass = statusColors[card.status] || 'secondary';
    const priorityClass = card.priority === 'high' ? 'danger' : (card.priority === 'medium' ? 'warning' : 'success');

    content.innerHTML = `
        <div class="container-fluid p-4">
            <!-- Card Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="card-title mb-2">${card.card_title}</h3>
                            <p class="text-muted mb-3">${card.description || 'No description provided'}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-${statusClass} mb-2">${card.status.replace('_', ' ').toUpperCase()}</span><br>
                            <span class="badge bg-${priorityClass}">${card.priority ? card.priority.toUpperCase() + ' PRIORITY' : 'NORMAL PRIORITY'}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Card Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Card Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="form-label text-muted small">Card ID</label>
                                        <div class="fw-semibold">#${card.card_id}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <label class="form-label text-muted small">Project</label>
                                        <div class="fw-semibold">${card.project_name || 'No Project'}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <label class="form-label text-muted small">Board</label>
                                        <div class="fw-semibold">${card.board_name || 'No Board'}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="form-label text-muted small">Created Date</label>
                                        <div class="fw-semibold">${createdDate}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <label class="form-label text-muted small">Due Date</label>
                                        <div class="fw-semibold">${dueDate}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <label class="form-label text-muted small">Estimated Hours</label>
                                        <div class="fw-semibold">${card.estimated_hours || 'Not set'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Comments (${comments ? comments.length : 0})</h6>
                            <button class="btn btn-sm btn-primary" onclick="showAddCommentForm(${card.card_id})">
                                <i class="bi bi-plus me-1"></i>Add Comment
                            </button>
                        </div>
                        <div class="card-body" id="cardCommentsSection">
                            ${displayCardComments(comments)}
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Actions -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/member/card/${card.card_id}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>View Full Details
                                </a>
                                ${card.status === 'todo' ? `
                                    <button class="btn btn-success btn-sm"
                                            data-card-id="${card.card_id}"
                                            data-card-title="${card.card_title.replace(/"/g, '&quot;')}"
                                            onclick="showModalConfirmationForCard(this.getAttribute('data-card-id'), this.getAttribute('data-card-title'), 'start')">
                                        <i class="bi bi-play me-1"></i>Start Work
                                    </button>
                                ` : ''}
                                ${card.status === 'in_progress' ? `
                                    <button class="btn btn-warning btn-sm"
                                            data-card-id="${card.card_id}"
                                            data-card-title="${card.card_title.replace(/"/g, '&quot;')}"
                                            onclick="showModalConfirmationForCard(this.getAttribute('data-card-id'), this.getAttribute('data-card-title'), 'submit')">
                                        <i class="bi bi-check-circle me-1"></i>Submit for Review
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Display comments
function displayCardComments(comments) {
    if (!comments || comments.length === 0) {
        return '<p class="text-muted text-center">No comments yet. Be the first to comment!</p>';
    }

    return comments.map(comment => {
        const createdDate = new Date(comment.created_at).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        const roleColor = comment.user_role === 'Team_Lead' ? 'primary' : (comment.user_role === 'Developer' ? 'success' : 'info');

        return `
            <div class="comment-item mb-3 p-3 border rounded">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-${roleColor} rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-person text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0">
                                ${comment.user_name}
                                <small class="badge bg-${roleColor} ms-2">${comment.user_role.replace('_', ' ')}</small>
                            </h6>
                            <small class="text-muted">${createdDate}</small>
                        </div>
                        <div class="comment-content">
                            <p class="mb-0">${comment.comment_text}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Show add comment form
function showAddCommentForm(cardId) {
    const commentsSection = document.getElementById('cardCommentsSection');

    // Check if form already exists
    if (document.getElementById('addCommentForm')) {
        return;
    }

    const formHtml = `
        <div id="addCommentForm" class="border rounded p-3 mb-3 bg-light">
            <h6 class="mb-3"><i class="bi bi-plus-circle me-2"></i>Add New Comment</h6>
            <form onsubmit="submitCardComment(event, ${cardId})">
                <div class="mb-3">
                    <textarea class="form-control" id="commentText" rows="3" placeholder="Write your comment here..." required></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-send me-1"></i>Post Comment
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="cancelAddComment()">
                        <i class="bi bi-x me-1"></i>Cancel
                    </button>
                </div>
            </form>
        </div>
    `;

    commentsSection.insertAdjacentHTML('afterbegin', formHtml);
    document.getElementById('commentText').focus();
}

// Cancel add comment
function cancelAddComment() {
    const form = document.getElementById('addCommentForm');
    if (form) {
        form.remove();
    }
}

// Submit comment
function submitCardComment(event, cardId) {
    event.preventDefault();

    const commentText = document.getElementById('commentText').value.trim();
    if (!commentText) {
        return;
    }

    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    // Show loading
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Posting...';
    submitBtn.disabled = true;

    fetch(`/api/cards/${cardId}/comments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            comment_text: commentText
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh card detail to show new comment
            showCardDetailModal(cardId);
            showNotification('success', 'Success', 'Comment added successfully');
        } else {
            throw new Error(data.message || 'Failed to add comment');
        }
    })
    .catch(error => {
        console.error('Error adding comment:', error);
        showNotification('error', 'Error', error.message);

        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Show confirmation for card actions in modal
function showModalConfirmationForCard(cardId, cardTitle, action) {
    console.log(`Modal confirmation for card ${cardId}, action: ${action}`);

    let title, message, confirmText, confirmClass, targetStatus, successMessage;

    if (action === 'start') {
        title = 'Mulai Mengerjakan Card';
        message = `Apakah Anda yakin ingin mulai mengerjakan "${cardTitle}"? Status akan berubah menjadi In Progress.`;
        confirmText = 'Mulai Kerjakan';
        confirmClass = 'btn-success';
        targetStatus = 'in_progress';
        successMessage = 'Card berhasil dimulai!';
    } else if (action === 'submit') {
        title = 'Submit untuk Review';
        message = `Apakah Anda yakin ingin submit "${cardTitle}" untuk review? Pastikan pekerjaan sudah selesai.`;
        confirmText = 'Submit Review';
        confirmClass = 'btn-warning';
        targetStatus = 'review';
        successMessage = 'Card berhasil disubmit untuk review!';
    }

    showConfirmationModal(
        title,
        message,
        confirmText,
        confirmClass,
        function() {
            console.log(`Confirmed: ${action} card ${cardId}`);
            // Close the card detail modal first
            const cardModal = bootstrap.Modal.getInstance(document.getElementById('cardDetailModal'));
            if (cardModal) {
                cardModal.hide();
            }

            // Then update the card status
            updateCardStatus(cardId, targetStatus, successMessage);
        }
    );
}

// Update card status from modal
function updateCardStatusInModal(cardId, status) {
    fetch(`/member/card/${cardId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Success', 'Card status updated successfully');
            // Close modal and refresh page
            const modal = bootstrap.Modal.getInstance(document.getElementById('cardDetailModal'));
            modal.hide();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', 'Error', data.error || 'Failed to update card status');
        }
    })
    .catch(error => {
        console.error('Error updating card status:', error);
        showNotification('error', 'Error', 'An error occurred while updating the card');
    });
}

// Show notification
function showNotification(type, title, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        <strong>${title}:</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
</script>

<style>
/* Project Cards Enhancement */
.project-card {
    transition: all 0.2s ease;
    border: 1px solid rgba(23, 162, 184, 0.1) !important;
}

.project-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(23, 162, 184, 0.15) !important;
    border: 1px solid rgba(23, 162, 184, 0.3) !important;
}

.project-card .progress {
    background: rgba(23, 162, 184, 0.1);
    border-radius: 10px;
}

.project-card .progress-bar {
    background: linear-gradient(90deg, #17a2b8, #138496);
    border-radius: 10px;
}

/* Badge animations for project cards */
.project-card .badge {
    animation: pulse-soft 3s ease-in-out infinite;
}

@keyframes pulse-soft {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
    }
}

/* Card hover effects for existing cards */
.card:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.2s ease;
}

/* Priority cards styling */
.priority-high {
    border-left: 4px solid #dc3545 !important;
}

.priority-medium {
    border-left: 4px solid #ffc107 !important;
}

.priority-low {
    border-left: 4px solid #28a745 !important;
}

/* Responsive design enhancements */
@media (max-width: 768px) {
    .project-card .card-title {
        font-size: 0.9rem;
    }

    .project-card .small {
        font-size: 0.75rem !important;
    }
}
</style>
@endpush
