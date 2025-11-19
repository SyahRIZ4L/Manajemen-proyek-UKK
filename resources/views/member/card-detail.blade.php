@extends('member.layout')

@section('title', 'Card Detail')
@section('page-title', $card->card_title)

@section('content')
<div class="row">
    <!-- Card Details -->
    <div class="col-md-8 mb-4">
        <div class="card priority-{{ $card->priority }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Card Information</h5>
                <div>
                    @php
                        $statusColors = [
                            'todo' => 'secondary',
                            'in_progress' => 'primary',
                            'review' => 'warning',
                            'done' => 'success'
                        ];
                        $statusColor = $statusColors[$card->status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $statusColor }} me-2">
                        {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                    </span>
                    @if($card->priority)
                        <span class="badge bg-{{ $card->priority === 'high' ? 'danger' : ($card->priority === 'medium' ? 'warning' : 'success') }}">
                            {{ ucfirst($card->priority) }} Priority
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($card->description)
                    <div class="mb-4">
                        <h6 class="text-muted">Description</h6>
                        <p>{{ $card->description }}</p>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Board</h6>
                        <p>{{ $card->board->board_name ?? 'No Board' }}</p>

                        @if($card->creator)
                            <h6 class="text-muted">Created By</h6>
                            <p>{{ $card->creator->full_name }}</p>
                        @endif

                        @if($card->due_date)
                            <h6 class="text-muted">Due Date</h6>
                            <p class="{{ $card->is_overdue ? 'text-danger' : '' }}">
                                {{ $card->due_date->format('M d, Y') }}
                                @if($card->is_overdue)
                                    <small>({{ $card->due_date->diffForHumans() }})</small>
                                @endif
                            </p>
                        @endif
                    </div>

                    <div class="col-md-6">
                        @if($card->estimated_hours)
                            <h6 class="text-muted">Estimated Hours</h6>
                            <p>{{ $card->estimated_hours }}h</p>
                        @endif

                        @if($card->actual_hours)
                            <h6 class="text-muted">Actual Hours</h6>
                            <p>{{ $card->actual_hours }}h</p>
                        @endif

                        @if($assignment->started_at)
                            <h6 class="text-muted">Started At</h6>
                            <p>{{ $assignment->started_at->format('M d, Y H:i') }}</p>
                        @endif

                        @if($assignment->completed_at)
                            <h6 class="text-muted">Completed At</h6>
                            <p>{{ $assignment->completed_at->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Timer Status Display -->
                @if($card->is_timer_active && $card->timer_started_at)
                    <div class="alert alert-info mt-3 d-flex align-items-center">
                        <i class="bi bi-stopwatch fs-4 me-3"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Timer Active -7 Time</h6>
                            <p class="mb-0">Working time: <strong id="timer-display">Calculating...</strong></p>
                            <small class="text-muted">Started at {{ \Carbon\Carbon::parse($card->timer_started_at)->format('H:i:s') }}</small>
                        </div>
                    </div>
                    <script>
                        (function() {
                            const startTime = new Date('{{ $card->timer_started_at }}').getTime();
                            const timerElement = document.getElementById('timer-display');

                            function updateTimer() {
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
                            setInterval(updateTimer, 1000);
                        })();
                    </script>
                @elseif($card->status === 'review')
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-pause-circle me-2"></i>
                        <strong>Timer Paused</strong> - Card is in review
                        @if($card->actual_hours)
                            <br><small>Total working time: {{ $card->actual_hours }} hours</small>
                        @endif
                    </div>
                @elseif($card->status === 'done' && $card->actual_hours)
                    <div class="alert alert-success mt-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Completed</strong> - Total working time: {{ $card->actual_hours }} hours
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-4">
                    @if($card->status === 'todo')
                        <button class="btn btn-success me-2" id="startWorkBtn">
                            <i class="bi bi-play"></i> Start Work
                        </button>
                    @elseif($card->status === 'in_progress')
                        <button class="btn btn-warning me-2" id="submitReviewBtn">
                            <i class="bi bi-check-circle"></i> Submit for Review
                        </button>
                    @endif



                    <a href="{{ route('member.my-cards') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to My Cards
                    </a>
                </div>
            </div>
        </div>

        <!-- Subtasks -->
        @if($card->subtasks->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Subtasks</h5>
                </div>
                <div class="card-body">
                    @foreach($card->subtasks as $subtask)
                        <div class="d-flex align-items-center mb-2 p-2 bg-light rounded">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox"
                                       {{ $subtask->status === 'completed' ? 'checked' : '' }}
                                       disabled>
                            </div>
                            <div class="flex-grow-1">
                                <span class="{{ $subtask->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">
                                    {{ $subtask->subtask_title }}
                                </span>
                                @if($subtask->description)
                                    <br><small class="text-muted">{{ $subtask->description }}</small>
                                @endif
                            </div>
                            <small class="text-muted">
                                {{ ucfirst($subtask->status) }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Todo List -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-check2-square"></i> Todo List</h5>
                <button class="btn btn-sm btn-primary" onclick="showAddTodoForm()">
                    <i class="bi bi-plus-circle"></i> Add Todo
                </button>
            </div>
            <div class="card-body">
                <!-- Add Todo Form (Hidden by default) -->
                <div id="addTodoForm" style="display: none;" class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="newTodoText"
                               placeholder="Enter todo item..." maxlength="500">
                        <button class="btn btn-success" onclick="addTodo()">
                            <i class="bi bi-check"></i> Add
                        </button>
                        <button class="btn btn-secondary" onclick="hideAddTodoForm()">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                    </div>
                    <small class="text-muted">Max 500 characters</small>
                </div>

                <!-- Todo List -->
                <div id="todoList">
                    <p class="text-muted text-center py-3"><i class="bi bi-inbox"></i> No todos yet. Click "Add Todo" to get started!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Logs Sidebar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Time Logs</h5>
                <span class="badge bg-primary">{{ floor($totalTime / 60) }}h {{ $totalTime % 60 }}m</span>
            </div>
            <div class="card-body">
                @if($timeLogs->count() > 0)
                    <div style="max-height: 400px; overflow-y: auto;">
                        @foreach($timeLogs as $log)
                            <div class="mb-3 p-2 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <small class="text-muted">
                                        {{ $log->start_time->format('M d, H:i') }}
                                    </small>
                                    <small class="fw-bold">
                                        {{ floor($log->duration_minutes / 60) }}h {{ $log->duration_minutes % 60 }}m
                                    </small>
                                </div>
                                @if($log->description)
                                    <small class="text-muted">{{ $log->description }}</small>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('member.time-logs') }}?card={{ $card->card_id }}"
                           class="btn btn-sm btn-outline-primary">
                            View All Time Logs
                        </a>
                    </div>
                @else
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-clock fs-3 mb-2"></i>
                        <p class="small mb-0">No time logged yet.</p>
                        <small>Start a timer to track your work!</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Progress Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Progress Summary</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Status</small>
                    <div class="progress mt-1" style="height: 8px;">
                        @php
                            $progress = 0;
                            switch($card->status) {
                                case 'todo': $progress = 25; break;
                                case 'in_progress': $progress = 50; break;
                                case 'review': $progress = 75; break;
                                case 'done': $progress = 100; break;
                            }
                        @endphp
                        <div class="progress-bar bg-{{ $statusColor }}" style="width: {{ $progress }}%"></div>
                    </div>
                    <small class="text-muted">{{ $progress }}% Complete</small>
                </div>

                @if($card->estimated_hours && $card->actual_hours)
                    <div class="mb-3">
                        <small class="text-muted">Time Efficiency</small>
                        @php
                            $efficiency = ($card->estimated_hours / $card->actual_hours) * 100;
                            $efficiencyColor = $efficiency >= 100 ? 'success' : ($efficiency >= 80 ? 'warning' : 'danger');
                        @endphp
                        <div class="progress mt-1" style="height: 8px;">
                            <div class="progress-bar bg-{{ $efficiencyColor }}"
                                 style="width: {{ min($efficiency, 100) }}%"></div>
                        </div>
                        <small class="text-muted">{{ round($efficiency) }}% Efficient</small>
                    </div>
                @endif

                @if($card->subtasks->count() > 0)
                    @php
                        $completedSubtasks = $card->subtasks->where('status', 'completed')->count();
                        $subtaskProgress = ($completedSubtasks / $card->subtasks->count()) * 100;
                    @endphp
                    <div class="mb-3">
                        <small class="text-muted">Subtasks</small>
                        <div class="progress mt-1" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ $subtaskProgress }}%"></div>
                        </div>
                        <small class="text-muted">{{ $completedSubtasks }}/{{ $card->subtasks->count() }} Complete</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Comments Section -->

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
                        <p class="fw-bold">{{ $card->card_title }}</p>
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
@endsection

@push('scripts')
<script>
// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    checkActiveTimer();
    loadComments();
    loadTodos();

    // Comment form submission
    document.getElementById('commentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const commentText = document.getElementById('commentText').value.trim();
        if (!commentText) return;

        fetch(`/api/cards/{{ $card->card_id }}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ comment_text: commentText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('commentText').value = '';
                loadComments(); // Reload comments
                showAlert('Comment added successfully!', 'success');
            } else {
                showAlert('Error adding comment: ' + data.error, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred', 'danger');
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Start work button
    const startWorkBtn = document.getElementById('startWorkBtn');
    if (startWorkBtn) {
        startWorkBtn.addEventListener('click', function() {
            updateCardStatus('in_progress', 'Work started successfully!');
        });
    }

    // Submit for review button
    const submitReviewBtn = document.getElementById('submitReviewBtn');
    if (submitReviewBtn) {
        submitReviewBtn.addEventListener('click', function() {
            updateCardStatus('review', 'Card submitted for review!');
        });
    }

    // Start timer
    document.getElementById('confirmStartTimer').addEventListener('click', function() {
        const description = document.getElementById('timerDescription').value;
        startTimer(description);
    });
});

function updateCardStatus(status, successMessage) {
    const cardId = {{ $card->card_id }};

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
            showAlert('success', successMessage);
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert('danger', data.error || 'Failed to update card status');
        }
    })
    .catch(error => {
        console.error('Error updating card status:', error);
        showAlert('danger', 'An error occurred while updating the card');
    });
}

function startTimer(description) {
    const cardId = {{ $card->card_id }};

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

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Insert at the top of the main content
    const mainContent = document.querySelector('.col-md-8');
    mainContent.insertBefore(alertDiv, mainContent.firstChild);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Comments functionality
function loadComments() {
    fetch(`/api/cards/{{ $card->card_id }}/comments`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayComments(data.comments);
        }
    })
    .catch(error => console.error('Error loading comments:', error));
}

function displayComments(comments) {
    const commentsList = document.getElementById('commentsList');

    if (comments.length === 0) {
        commentsList.innerHTML = '<p class="text-muted text-center">No comments yet.</p>';
        return;
    }

    let html = '';
    comments.forEach(comment => {
        html += renderComment(comment);
    });

    commentsList.innerHTML = html;
}

function renderComment(comment) {
    const createdAt = new Date(comment.created_at).toLocaleString();
    const roleColor = comment.user_role === 'Team_Lead' ? 'primary' : (comment.user_role === 'Developer' ? 'success' : 'info');

    let html = `
        <div class="comment mb-3 p-3 border rounded">
            <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                    <div class="bg-${roleColor} rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-user text-white"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="mb-0">
                            ${comment.user_name}
                            <small class="badge bg-${roleColor} ms-2">${comment.user_role.replace('_', ' ')}</small>
                        </h6>
                        <small class="text-muted">${createdAt}</small>
                    </div>
                    <p class="mb-2">${comment.comment_text}</p>
                    <button class="btn btn-sm btn-outline-primary" onclick="showReplyForm(${comment.comment_id})">
                        <i class="fas fa-reply me-1"></i>Reply
                    </button>
                    <div id="replyForm_${comment.comment_id}" class="mt-2" style="display: none;">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" class="form-control form-control-sm" id="replyText_${comment.comment_id}" placeholder="Write a reply...">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-primary" onclick="addReply(${comment.comment_id})">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

    // Add replies
    if (comment.replies && comment.replies.length > 0) {
        html += '<div class="ms-5 mt-3">';
        comment.replies.forEach(reply => {
            const replyCreatedAt = new Date(reply.created_at).toLocaleString();
            const replyRoleColor = reply.role === 'Team_Lead' ? 'primary' : 'secondary';

            html += `
                <div class="reply p-2 border-start border-3 border-${replyRoleColor} mb-2">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="bg-${replyRoleColor} rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="fw-bold">
                                    ${reply.full_name}
                                    <span class="badge bg-${replyRoleColor} ms-1" style="font-size: 10px;">${reply.role.replace('_', ' ')}</span>
                                </small>
                                <small class="text-muted">${replyCreatedAt}</small>
                            </div>
                            <p class="mb-0 small">${reply.comment}</p>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
    }

    html += '</div>';
    return html;
}

function showReplyForm(commentId) {
    // Hide all reply forms first
    document.querySelectorAll('[id^="replyForm_"]').forEach(form => {
        form.style.display = 'none';
    });

    // Show the clicked reply form
    const replyForm = document.getElementById(`replyForm_${commentId}`);
    replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
}

function addReply(parentId) {
    const replyText = document.getElementById(`replyText_${parentId}`).value.trim();
    if (!replyText) return;

    fetch(`/member/cards/{{ $card->card_id }}/comments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            comment: replyText,
            parent_id: parentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`replyText_${parentId}`).value = '';
            document.getElementById(`replyForm_${parentId}`).style.display = 'none';
            loadComments(); // Reload comments
            showAlert('Reply added successfully!', 'success');
        } else {
            showAlert('Error adding reply: ' + data.error, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred', 'danger');
    });
}

// Todo List Functions
function loadTodos() {
    fetch(`/api/card-todos?card_id={{ $card->card_id }}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayTodos(data.todos);
        } else {
            showAlert('Failed to load todos', 'danger');
        }
    })
    .catch(error => {
        console.error('Error loading todos:', error);
        document.getElementById('todoList').innerHTML = '<p class="text-muted text-center">Failed to load todos</p>';
    });
}

function displayTodos(todos) {
    const todoList = document.getElementById('todoList');

    if (todos.length === 0) {
        todoList.innerHTML = '<p class="text-muted text-center py-3"><i class="bi bi-inbox"></i> No todos yet. Click "Add Todo" to get started!</p>';
        return;
    }

    let html = '<div class="list-group">';
    todos.forEach(todo => {
        const checked = todo.completed ? 'checked' : '';
        const strikethrough = todo.completed ? 'text-decoration-line-through text-muted' : '';
        const createdAt = new Date(todo.created_at).toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        html += `
            <div class="list-group-item d-flex align-items-center ${todo.completed ? 'bg-light' : ''}">
                <input type="checkbox" class="form-check-input me-3" ${checked}
                       onchange="toggleTodo(${todo.todo_id})" style="cursor: pointer;">
                <div class="flex-grow-1 ${strikethrough}" id="todoText_${todo.todo_id}">
                    ${escapeHtml(todo.text)}
                    <br><small class="text-muted">by ${todo.user.full_name || todo.user.username} - ${createdAt}</small>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-primary" onclick="editTodo(${todo.todo_id}, '${escapeHtml(todo.text).replace(/'/g, "\\'")}')">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteTodo(${todo.todo_id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    html += '</div>';

    todoList.innerHTML = html;
}

function showAddTodoForm() {
    document.getElementById('addTodoForm').style.display = 'block';
    document.getElementById('newTodoText').focus();
}

function hideAddTodoForm() {
    document.getElementById('addTodoForm').style.display = 'none';
    document.getElementById('newTodoText').value = '';
}

function addTodo() {
    const text = document.getElementById('newTodoText').value.trim();
    if (!text) {
        showAlert('Please enter todo text', 'warning');
        return;
    }

    fetch('/api/card-todos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            card_id: {{ $card->card_id }},
            text: text
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideAddTodoForm();
            loadTodos();
            showAlert('Todo added successfully!', 'success');
        } else {
            showAlert(data.message || 'Failed to add todo', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while adding todo', 'danger');
    });
}

function toggleTodo(todoId) {
    fetch(`/api/card-todos/${todoId}/toggle`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadTodos();
        } else {
            showAlert(data.message || 'Failed to toggle todo', 'danger');
            loadTodos(); // Reload to revert UI
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred', 'danger');
        loadTodos(); // Reload to revert UI
    });
}

function editTodo(todoId, currentText) {
    const newText = prompt('Edit todo:', currentText);
    if (newText === null || newText.trim() === '') return;

    if (newText.trim() === currentText) {
        return; // No changes
    }

    fetch(`/api/card-todos/${todoId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ text: newText.trim() })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadTodos();
            showAlert('Todo updated successfully!', 'success');
        } else {
            showAlert(data.message || 'Failed to update todo', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while updating todo', 'danger');
    });
}

function deleteTodo(todoId) {
    if (!confirm('Are you sure you want to delete this todo?')) return;

    fetch(`/api/card-todos/${todoId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadTodos();
            showAlert('Todo deleted successfully!', 'success');
        } else {
            showAlert(data.message || 'Failed to delete todo', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while deleting todo', 'danger');
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endpush
