@extends('member.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-kanban fs-1 mb-2"></i>
                <h3>{{ $stats['total_cards'] }}</h3>
                <p class="mb-0">Total Cards</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stats-card success">
            <div class="card-body text-center">
                <i class="bi bi-play-circle fs-1 mb-2"></i>
                <h3>{{ $stats['in_progress'] }}</h3>
                <p class="mb-0">In Progress</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stats-card warning">
            <div class="card-body text-center">
                <i class="bi bi-clipboard-check fs-1 mb-2"></i>
                <h3>{{ $stats['review'] }}</h3>
                <p class="mb-0">In Review</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stats-card danger">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle fs-1 mb-2"></i>
                <h3>{{ $stats['overdue'] }}</h3>
                <p class="mb-0">Overdue</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Cards -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Cards</h5>
                <a href="{{ route('member.my-cards') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentCards->count() > 0)
                    @foreach($recentCards as $assignment)
                        @php $card = $assignment->card; @endphp
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded priority-{{ $card->priority }}">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="javascript:void(0)" onclick="showCardDetailModal({{ $card->card_id }})"
                                       class="text-decoration-none">
                                        {{ $card->card_title }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="bi bi-diagram-3 me-1"></i>{{ $card->board->board_name ?? 'No Board' }}
                                </small>
                                @if($card->due_date)
                                    <small class="text-muted ms-2">
                                        <i class="bi bi-calendar me-1"></i>Due: {{ $card->due_date->format('M d, Y') }}
                                    </small>
                                @endif
                            </div>
                            <div class="text-end">
                                @php
                                    $statusColors = [
                                        'todo' => 'secondary',
                                        'in_progress' => 'primary',
                                        'review' => 'warning',
                                        'done' => 'success'
                                    ];
                                    $statusColor = $statusColors[$card->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusColor }} status-badge">
                                    {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                                </span>
                                @if($card->priority)
                                    <span class="badge bg-{{ $card->priority === 'high' ? 'danger' : ($card->priority === 'medium' ? 'warning' : 'success') }} status-badge ms-1">
                                        {{ ucfirst($card->priority) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 mb-3"></i>
                        <p>No cards assigned to you yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Today's Time Log -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Today's Work</h5>
                <span class="badge bg-primary">{{ floor($totalTimeToday / 60) }}h {{ $totalTimeToday % 60 }}m</span>
            </div>
            <div class="card-body">
                @if($todayTimeLogs->count() > 0)
                    @foreach($todayTimeLogs->take(5) as $log)
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <small class="fw-semibold">{{ $log->card->card_title ?? 'Unknown Card' }}</small>
                                <br>
                                <small class="text-muted">
                                    {{ $log->start_time->format('H:i') }} -
                                    {{ $log->end_time ? $log->end_time->format('H:i') : 'Running' }}
                                </small>
                            </div>
                            <small class="text-end">
                                {{ floor($log->duration_minutes / 60) }}h {{ $log->duration_minutes % 60 }}m
                            </small>
                        </div>
                    @endforeach

                    @if($todayTimeLogs->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('member.time-logs') }}" class="btn btn-sm btn-outline-primary">
                                View All ({{ $todayTimeLogs->count() }} logs)
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-clock fs-3 mb-2"></i>
                        <p class="small mb-0">No time logged today.</p>
                        <small>Start working on your cards!</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('member.my-cards') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-kanban me-2"></i>View My Cards
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('member.my-cards', ['status' => 'in_progress']) }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-play-circle me-2"></i>Continue Work
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('member.time-logs') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-clock-history me-2"></i>Time Tracking
                        </a>
                    </div>
                </div>
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
                                    <button class="btn btn-success btn-sm" onclick="updateCardStatus(${card.card_id}, 'in_progress')">
                                        <i class="bi bi-play me-1"></i>Start Work
                                    </button>
                                ` : ''}
                                ${card.status === 'in_progress' ? `
                                    <button class="btn btn-warning btn-sm" onclick="updateCardStatus(${card.card_id}, 'review')">
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

// Update card status
function updateCardStatus(cardId, status) {
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
@endpush
