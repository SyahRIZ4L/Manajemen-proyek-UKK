<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Task - Manajemen Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .task-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 8px 16px;
        }
        .priority-high { border-left: 4px solid #dc3545; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .todo-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            background: white;
            transition: all 0.3s ease;
        }
        .todo-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .todo-item.completed {
            background: #f8f9fa;
            text-decoration: line-through;
            opacity: 0.7;
        }
        .action-btn {
            margin: 5px 0;
        }
        .status-timeline {
            position: relative;
            padding-left: 30px;
        }
        .status-timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #007bff;
            border: 2px solid white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="text-center mb-4">
                    <h4><i class="fas fa-project-diagram"></i> ProManage</h4>
                    <small>Sistem Manajemen Proyek</small>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link mb-2" href="<?= route('home') ?>"><i class="fas fa-home me-2"></i> Dashboard</a>
                    <a class="nav-link mb-2" href="<?= route('profile.show') ?>"><i class="fas fa-user me-2"></i> Profile</a>
                    <a class="nav-link active mb-2" href="<?= route('tasks.index') ?>"><i class="fas fa-tasks me-2"></i> Tugas Saya</a>
                    <a class="nav-link mb-2" href="<?= route('tasks.history') ?>"><i class="fas fa-history me-2"></i> History Task</a>
                    <a class="nav-link mb-2" href="<?= route('notifications.index') ?>"><i class="fas fa-bell me-2"></i> Notifikasi</a>
                    <a class="nav-link mb-2" href="#"><i class="fas fa-project-diagram me-2"></i> Proyek</a>
                    <a class="nav-link mb-2" href="#"><i class="fas fa-calendar me-2"></i> Kalender</a>
                    <a class="nav-link mb-2" href="#"><i class="fas fa-user-friends me-2"></i> Tim</a>
                </nav>

                <div class="mt-auto pt-4">
                    <form method="POST" action="<?= route('logout') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Detail Task</h2>
                    <div>
                        <a href="<?= route('tasks.edit', $task['id']) ?>" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit Task
                        </a>
                        <a href="<?= route('tasks.index') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>

                <?php if (session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Task Information -->
                    <div class="col-md-8 mb-4">
                        <div class="card task-card priority-<?= strtolower($task['priority']) ?>">
                            <div class="card-header bg-primary text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><?= htmlspecialchars($task['title']) ?></h5>
                                    <span class="badge status-badge bg-<?=
                                        $task['status'] === 'Completed' ? 'success' :
                                        ($task['status'] === 'In Progress' ? 'primary' :
                                        ($task['status'] === 'Review' ? 'warning' : 'secondary')) ?>">
                                        <?= $task['status'] ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6>Informasi Task</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Proyek:</strong></td>
                                                <td><?= htmlspecialchars($task['project']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Prioritas:</strong></td>
                                                <td>
                                                    <span class="badge bg-<?= $task['priority'] === 'High' ? 'danger' :
                                                                                ($task['priority'] === 'Medium' ? 'warning' : 'success') ?>">
                                                        <?= $task['priority'] ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Deadline:</strong></td>
                                                <td><?= date('d M Y', strtotime($task['due_date'])) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Estimasi:</strong></td>
                                                <td><?= $task['estimated_hours'] ?> jam</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Actual:</strong></td>
                                                <td><?= $task['actual_hours'] ?> jam</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Progress</h6>
                                        <?php $progress = ($task['actual_hours'] / $task['estimated_hours']) * 100; ?>
                                        <div class="progress mb-3" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: <?= min($progress, 100) ?>%"
                                                 aria-valuenow="<?= $progress ?>"
                                                 aria-valuemin="0" aria-valuemax="100">
                                                <?= round($progress) ?>%
                                            </div>
                                        </div>

                                        <h6>Waktu Tersisa</h6>
                                        <?php
                                        $deadline = new DateTime($task['due_date']);
                                        $today = new DateTime();
                                        $diff = $today->diff($deadline);
                                        $daysLeft = $deadline > $today ? $diff->days : -$diff->days;
                                        ?>
                                        <div class="alert alert-<?= $daysLeft < 0 ? 'danger' : ($daysLeft <= 3 ? 'warning' : 'info') ?> text-center">
                                            <?php if ($daysLeft < 0): ?>
                                                <strong>Terlambat <?= abs($daysLeft) ?> hari</strong>
                                            <?php elseif ($daysLeft == 0): ?>
                                                <strong>Deadline hari ini!</strong>
                                            <?php else: ?>
                                                <strong><?= $daysLeft ?> hari lagi</strong>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6>Deskripsi</h6>
                                    <p class="text-muted"><?= nl2br(htmlspecialchars($task['description'])) ?></p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mb-4">
                                    <h6>Update Status</h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <?php if ($task['status'] === 'To Do'): ?>
                                        <button class="btn btn-primary action-btn" onclick="startTask(<?= $task['id'] ?>)">
                                            <i class="fas fa-play me-2"></i>Mulai Task
                                        </button>
                                        <?php endif; ?>

                                        <?php if ($task['status'] === 'In Progress'): ?>
                                        <button class="btn btn-warning action-btn" onclick="submitForReview(<?= $task['id'] ?>)">
                                            <i class="fas fa-paper-plane me-2"></i>Submit untuk Review
                                        </button>
                                        <button class="btn btn-success action-btn" onclick="completeTask(<?= $task['id'] ?>)">
                                            <i class="fas fa-check me-2"></i>Mark as Completed
                                        </button>
                                        <?php endif; ?>

                                        <?php if ($task['status'] === 'Review'): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-clock me-2"></i>Task sedang dalam review
                                        </div>
                                        <?php endif; ?>

                                        <?php if ($task['status'] !== 'Completed'): ?>
                                        <button class="btn btn-outline-primary action-btn" onclick="updateStatus(<?= $task['id'] ?>)">
                                            <i class="fas fa-sync me-2"></i>Update Status Manual
                                        </button>
                                        <button class="btn btn-outline-success action-btn" onclick="logTime(<?= $task['id'] ?>)">
                                            <i class="fas fa-clock me-2"></i>Log Waktu
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Todolist & History -->
                    <div class="col-md-4 mb-4">
                        <!-- Todolist -->
                        <div class="card task-card mb-4">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-list-check me-2"></i>Todolist</h6>
                                <button class="btn btn-light btn-sm" onclick="addTodoItem()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                <div id="todoList">
                                    <!-- Default todo items -->
                                    <div class="todo-item" data-id="1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check flex-grow-1">
                                                <input class="form-check-input" type="checkbox" id="todo1" onchange="toggleTodo(1)">
                                                <label class="form-check-label" for="todo1">
                                                    Setup development environment
                                                </label>
                                            </div>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteTodo(1)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="todo-item" data-id="2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check flex-grow-1">
                                                <input class="form-check-input" type="checkbox" id="todo2" onchange="toggleTodo(2)">
                                                <label class="form-check-label" for="todo2">
                                                    Create database migrations
                                                </label>
                                            </div>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteTodo(2)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="todo-item completed" data-id="3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check flex-grow-1">
                                                <input class="form-check-input" type="checkbox" id="todo3" checked onchange="toggleTodo(3)">
                                                <label class="form-check-label" for="todo3">
                                                    Research authentication methods
                                                </label>
                                            </div>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteTodo(3)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="newTodoInput"
                                               placeholder="Tambah todo baru..." onkeypress="handleTodoKeyPress(event)">
                                        <button class="btn btn-success" onclick="addTodoFromInput()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- History -->
                        <div class="card task-card">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-history me-2"></i>History</h6>
                            </div>
                            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                <div class="status-timeline">
                                    <?php foreach ($history as $item): ?>
                                    <div class="timeline-item">
                                        <div class="fw-bold"><?= ucfirst(str_replace('_', ' ', $item['action'])) ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($item['description']) ?></div>
                                        <div class="text-muted small">
                                            <i class="fas fa-user me-1"></i><?= $item['user'] ?>
                                            <i class="fas fa-clock ms-2 me-1"></i><?= $item['created_at']->diffForHumans() ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        <div class="mb-3">
                            <label for="taskStatus" class="form-label">Status Baru</label>
                            <select class="form-select" id="taskStatus" name="status" required>
                                <option value="To Do">To Do</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Review">Review</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="statusComment" class="form-label">Komentar (Opsional)</label>
                            <textarea class="form-control" id="statusComment" name="comment" rows="3"
                                      placeholder="Jelaskan alasan perubahan status..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveStatus()">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit for Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit untuk Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="reviewComment" class="form-label">Catatan untuk Reviewer <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reviewComment" name="comment" rows="4" required
                                      placeholder="Jelaskan apa yang sudah dikerjakan dan hal-hal yang perlu direview..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="reviewAttachments" class="form-label">Lampiran (Opsional)</label>
                            <input type="file" class="form-control" id="reviewAttachments" name="attachments[]" multiple>
                            <div class="form-text">Upload file pendukung (screenshot, dokumen, dll.) Max 10MB per file</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning" onclick="submitForReviewAction()">Submit untuk Review</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Task Modal -->
    <div class="modal fade" id="completeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Selesaikan Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="completeForm">
                        <div class="mb-3">
                            <label for="completionNote" class="form-label">Catatan Penyelesaian <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="completionNote" name="completion_note" rows="4" required
                                      placeholder="Jelaskan hasil pekerjaan dan hal-hal yang telah diselesaikan..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="actualHours" class="form-label">Total Jam Kerja Aktual <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="actualHours" name="actual_hours"
                                   step="0.5" min="0.1" value="<?= $task['actual_hours'] ?>" required>
                            <div class="form-text">Total jam yang benar-benar dihabiskan untuk task ini</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="completeTaskAction()">Selesaikan Task</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Log Modal -->
    <div class="modal fade" id="timeLogModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log Waktu Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="timeLogForm">
                        <div class="mb-3">
                            <label for="hours" class="form-label">Jam Kerja</label>
                            <input type="number" class="form-control" id="hours" name="hours"
                                   step="0.5" min="0.5" max="24" required>
                        </div>
                        <div class="mb-3">
                            <label for="workDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="workDate" name="date"
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="workDescription" class="form-label">Deskripsi Pekerjaan</label>
                            <textarea class="form-control" id="workDescription" name="description"
                                      rows="3" required placeholder="Jelaskan apa yang sudah dikerjakan..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveTimeLog()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentTaskId = <?= $task['id'] ?>;
        let todoCounter = 4; // Start from 4 since we have 3 default items

        // Task Status Functions
        function startTask(taskId) {
            if (confirm('Mulai mengerjakan task ini?')) {
                fetch(`/tasks/${taskId}/start`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        location.reload();
                    }
                });
            }
        }

        function updateStatus(taskId) {
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }

        function saveStatus() {
            const form = document.getElementById('statusForm');
            const formData = new FormData(form);

            fetch(`/tasks/${currentTaskId}/status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
                    showAlert('success', data.message);
                    location.reload();
                }
            });
        }

        function submitForReview(taskId) {
            const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
            modal.show();
        }

        function submitForReviewAction() {
            const form = document.getElementById('reviewForm');
            const formData = new FormData(form);

            fetch(`/tasks/${currentTaskId}/submit-review`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
                    showAlert('success', data.message);
                    location.reload();
                }
            });
        }

        function completeTask(taskId) {
            const modal = new bootstrap.Modal(document.getElementById('completeModal'));
            modal.show();
        }

        function completeTaskAction() {
            const form = document.getElementById('completeForm');
            const formData = new FormData(form);

            fetch(`/tasks/${currentTaskId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('completeModal')).hide();
                    showAlert('success', data.message);
                    location.reload();
                }
            });
        }

        function logTime(taskId) {
            const modal = new bootstrap.Modal(document.getElementById('timeLogModal'));
            modal.show();
        }

        function saveTimeLog() {
            const form = document.getElementById('timeLogForm');
            const formData = new FormData(form);

            fetch(`/tasks/${currentTaskId}/time`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('timeLogModal')).hide();
                    showAlert('success', data.message);
                    location.reload();
                }
            });
        }

        // Todo Functions
        function addTodoItem() {
            const input = document.getElementById('newTodoInput');
            input.focus();
        }

        function addTodoFromInput() {
            const input = document.getElementById('newTodoInput');
            const text = input.value.trim();

            if (text) {
                addTodo(text);
                input.value = '';
            }
        }

        function handleTodoKeyPress(event) {
            if (event.key === 'Enter') {
                addTodoFromInput();
            }
        }

        function addTodo(text) {
            const todoList = document.getElementById('todoList');
            const todoId = todoCounter++;

            const todoItem = document.createElement('div');
            todoItem.className = 'todo-item';
            todoItem.dataset.id = todoId;
            todoItem.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check flex-grow-1">
                        <input class="form-check-input" type="checkbox" id="todo${todoId}" onchange="toggleTodo(${todoId})">
                        <label class="form-check-label" for="todo${todoId}">
                            ${text}
                        </label>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteTodo(${todoId})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            todoList.appendChild(todoItem);
            saveTodoState();
        }

        function toggleTodo(todoId) {
            const todoItem = document.querySelector(`[data-id="${todoId}"]`);
            const checkbox = document.getElementById(`todo${todoId}`);

            if (checkbox.checked) {
                todoItem.classList.add('completed');
            } else {
                todoItem.classList.remove('completed');
            }

            saveTodoState();
        }

        function deleteTodo(todoId) {
            if (confirm('Hapus todo item ini?')) {
                const todoItem = document.querySelector(`[data-id="${todoId}"]`);
                todoItem.remove();
                saveTodoState();
            }
        }

        function saveTodoState() {
            // Save todo state to localStorage or send to server
            const todos = [];
            document.querySelectorAll('.todo-item').forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                const label = item.querySelector('label');
                todos.push({
                    id: item.dataset.id,
                    text: label.textContent.trim(),
                    completed: checkbox.checked
                });
            });

            localStorage.setItem(`todos_task_${currentTaskId}`, JSON.stringify(todos));
        }

        function loadTodoState() {
            // Load todo state from localStorage
            const savedTodos = localStorage.getItem(`todos_task_${currentTaskId}`);
            if (savedTodos) {
                // Implementation for loading saved todos
                console.log('Loaded todos:', JSON.parse(savedTodos));
            }
        }

        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            const container = document.querySelector('.col-md-9.col-lg-10');
            container.insertAdjacentHTML('afterbegin', alertHtml);
        }

        // Load todo state on page load
        document.addEventListener('DOMContentLoaded', loadTodoState);
    </script>
</body>
</html>
