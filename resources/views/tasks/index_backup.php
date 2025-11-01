<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Saya - Manajemen Proyek</title>
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
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        .task-card:hover {
            transform: translateY(-5px);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 6px 12px;
        }
        .priority-high { border-left: 4px solid #dc3545; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .filter-card {
            background: #f8f9fa;
            border-radius: 15px;
            border: none;
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
                    <h2>Tugas Saya</h2>
                    <a href="<?= route('tasks.create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Task Baru
                    </a>
                </div>

                <?php if (session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Filter Section -->
                <div class="card filter-card p-3 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="To Do">To Do</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Review">Review</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="priorityFilter">
                                <option value="">Semua Prioritas</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchTask" placeholder="Cari task...">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" id="clearFilter">
                                <i class="fas fa-times"></i> Clear
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tasks Grid -->
                <div class="row" id="tasksContainer">
                    <?php foreach ($tasks as $task): ?>
                    <div class="col-md-6 col-lg-4 task-item"
                         data-status="<?= $task['status'] ?>"
                         data-priority="<?= $task['priority'] ?>"
                         data-title="<?= strtolower($task['title']) ?>">
                        <div class="card task-card priority-<?= strtolower($task['priority']) ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title"><?= htmlspecialchars($task['title']) ?></h5>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?= route('tasks.show', $task['id']) ?>">
                                                <i class="fas fa-eye me-2"></i>Detail
                                            </a></li>
                                            <li><a class="dropdown-item" href="<?= route('tasks.edit', $task['id']) ?>">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteTask(<?= $task['id'] ?>)">
                                                <i class="fas fa-trash me-2"></i>Hapus
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>

                                <p class="card-text text-muted"><?= htmlspecialchars($task['description']) ?></p>

                                <div class="mb-3">
                                    <small class="text-muted">Proyek:</small>
                                    <div class="fw-bold"><?= htmlspecialchars($task['project']) ?></div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge status-badge
                                        <?= $task['status'] === 'Completed' ? 'bg-success' :
                                            ($task['status'] === 'In Progress' ? 'bg-primary' :
                                            ($task['status'] === 'Review' ? 'bg-warning' : 'bg-secondary')) ?>">
                                        <?= $task['status'] ?>
                                    </span>
                                    <span class="badge bg-<?= $task['priority'] === 'High' ? 'danger' :
                                                                ($task['priority'] === 'Medium' ? 'warning' : 'success') ?>">
                                        <?= $task['priority'] ?>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Due: <?= date('d M Y', strtotime($task['due_date'])) ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= $task['actual_hours'] ?>/<?= $task['estimated_hours'] ?>h
                                    </small>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <?php $progress = ($task['actual_hours'] / $task['estimated_hours']) * 100; ?>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar"
                                             style="width: <?= min($progress, 100) ?>%"
                                             aria-valuenow="<?= $progress ?>"
                                             aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">Progress: <?= round($progress) ?>%</small>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2">
                                    <?php if ($task['status'] !== 'Completed'): ?>
                                    <button class="btn btn-sm btn-outline-primary flex-fill"
                                            onclick="updateTaskStatus(<?= $task['id'] ?>, 'In Progress')">
                                        <i class="fas fa-play me-1"></i>Start
                                    </button>
                                    <?php endif; ?>

                                    <button class="btn btn-sm btn-outline-success flex-fill"
                                            onclick="logTime(<?= $task['id'] ?>)">
                                        <i class="fas fa-clock me-1"></i>Log Time
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (empty($tasks)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                    <h4>Belum ada task</h4>
                    <p class="text-muted">Mulai dengan membuat task pertama Anda</p>
                    <a href="<?= route('tasks.create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Buat Task Baru
                    </a>
                </div>
                <?php endif; ?>
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
                        <input type="hidden" id="taskId" name="task_id">
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
        // Filter functions
        function filterTasks() {
            const statusFilter = document.getElementById('statusFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;
            const searchTerm = document.getElementById('searchTask').value.toLowerCase();

            const taskItems = document.querySelectorAll('.task-item');

            taskItems.forEach(item => {
                const status = item.dataset.status;
                const priority = item.dataset.priority;
                const title = item.dataset.title;

                const statusMatch = !statusFilter || status === statusFilter;
                const priorityMatch = !priorityFilter || priority === priorityFilter;
                const searchMatch = !searchTerm || title.includes(searchTerm);

                if (statusMatch && priorityMatch && searchMatch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Event listeners for filters
        document.getElementById('statusFilter').addEventListener('change', filterTasks);
        document.getElementById('priorityFilter').addEventListener('change', filterTasks);
        document.getElementById('searchTask').addEventListener('input', filterTasks);

        // Clear filters
        document.getElementById('clearFilter').addEventListener('click', function() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('priorityFilter').value = '';
            document.getElementById('searchTask').value = '';
            filterTasks();
        });

        // Log time function
        function logTime(taskId) {
            document.getElementById('taskId').value = taskId;
            new bootstrap.Modal(document.getElementById('timeLogModal')).show();
        }

        // Save time log
        function saveTimeLog() {
            const form = document.getElementById('timeLogForm');
            const formData = new FormData(form);
            const taskId = document.getElementById('taskId').value;

            fetch(`/tasks/${taskId}/time`, {
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
                    location.reload();
                }
            });
        }

        // Update task status
        function updateTaskStatus(taskId, status) {
            fetch(`/tasks/${taskId}/status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        // Delete task
        function deleteTask(taskId) {
            if (confirm('Apakah Anda yakin ingin menghapus task ini?')) {
                fetch(`/tasks/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }
    </script>
</body>
</html>
