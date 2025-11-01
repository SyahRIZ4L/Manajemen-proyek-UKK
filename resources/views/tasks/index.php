<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List - Manajemen Proyek</title>
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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .priority-high { border-left: 4px solid #dc3545; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .status-badge {
            font-size: 0.8rem;
            padding: 6px 12px;
        }
        .action-btn {
            margin: 2px;
        }
        .progress-text {
            font-size: 0.9rem;
            font-weight: 500;
        }
        .task-meta {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .quick-stats {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .filter-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .filter-btn {
            margin: 2px;
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
                    <h2>Tugas Saya</h2>
                    <a href="<?= route('tasks.create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Buat Task Baru
                    </a>
                </div>

                <?php if (session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Quick Stats -->
                <div class="quick-stats">
                    <div class="row">
                        <div class="col-3 stat-item">
                            <div class="stat-number"><?= count($tasks) ?></div>
                            <div class="stat-label">Total Tasks</div>
                        </div>
                        <div class="col-3 stat-item">
                            <div class="stat-number"><?= count(array_filter($tasks, fn($t) => $t['status'] === 'In Progress')) ?></div>
                            <div class="stat-label">In Progress</div>
                        </div>
                        <div class="col-3 stat-item">
                            <div class="stat-number"><?= count(array_filter($tasks, fn($t) => $t['status'] === 'Review')) ?></div>
                            <div class="stat-label">Review</div>
                        </div>
                        <div class="col-3 stat-item">
                            <div class="stat-number"><?= count(array_filter($tasks, fn($t) => $t['status'] === 'Completed')) ?></div>
                            <div class="stat-label">Completed</div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-2">Filter Tasks:</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all">
                                    Semua
                                </button>
                                <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="To Do">
                                    To Do
                                </button>
                                <button type="button" class="btn btn-outline-info filter-btn" data-filter="In Progress">
                                    In Progress
                                </button>
                                <button type="button" class="btn btn-outline-warning filter-btn" data-filter="Review">
                                    Review
                                </button>
                                <button type="button" class="btn btn-outline-success filter-btn" data-filter="Completed">
                                    Completed
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-2">Prioritas:</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-danger filter-btn" data-priority="High">
                                    High
                                </button>
                                <button type="button" class="btn btn-outline-warning filter-btn" data-priority="Medium">
                                    Medium
                                </button>
                                <button type="button" class="btn btn-outline-success filter-btn" data-priority="Low">
                                    Low
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks Grid -->
                <div class="row" id="tasksContainer">
                    <?php foreach ($tasks as $task): ?>
                    <div class="col-md-6 col-lg-4 mb-4 task-item"
                         data-status="<?= $task['status'] ?>"
                         data-priority="<?= $task['priority'] ?>">
                        <div class="card task-card priority-<?= strtolower($task['priority']) ?>">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-truncate me-2"><?= htmlspecialchars($task['title']) ?></h6>
                                <span class="badge status-badge bg-<?=
                                    $task['status'] === 'Completed' ? 'success' :
                                    ($task['status'] === 'In Progress' ? 'primary' :
                                    ($task['status'] === 'Review' ? 'warning' : 'secondary')) ?>">
                                    <?= $task['status'] ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-muted mb-3"><?= substr($task['description'], 0, 80) ?>...</p>

                                <div class="task-meta mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><i class="fas fa-project-diagram me-1"></i><?= $task['project'] ?></span>
                                        <span class="badge bg-<?= $task['priority'] === 'High' ? 'danger' :
                                                                    ($task['priority'] === 'Medium' ? 'warning' : 'success') ?>">
                                            <?= $task['priority'] ?>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><i class="fas fa-calendar me-1"></i><?= date('d M Y', strtotime($task['due_date'])) ?></span>
                                        <span><i class="fas fa-clock me-1"></i><?= $task['estimated_hours'] ?>h</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <?php $progress = $task['estimated_hours'] > 0 ? ($task['actual_hours'] / $task['estimated_hours']) * 100 : 0; ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="progress-text">Progress</span>
                                        <span class="progress-text"><?= round($progress) ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar"
                                             style="width: <?= min($progress, 100) ?>%"
                                             aria-valuenow="<?= $progress ?>"
                                             aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="<?= route('tasks.show', $task['id']) ?>" class="btn btn-sm btn-outline-primary action-btn flex-fill">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>

                                    <?php if ($task['status'] === 'To Do'): ?>
                                    <button class="btn btn-sm btn-success action-btn flex-fill" onclick="quickStartTask(<?= $task['id'] ?>)">
                                        <i class="fas fa-play me-1"></i>Start
                                    </button>
                                    <?php endif; ?>

                                    <?php if ($task['status'] === 'In Progress'): ?>
                                    <button class="btn btn-sm btn-warning action-btn flex-fill" onclick="quickSubmitReview(<?= $task['id'] ?>)">
                                        <i class="fas fa-paper-plane me-1"></i>Submit
                                    </button>
                                    <?php endif; ?>

                                    <?php if ($task['status'] !== 'Completed'): ?>
                                    <a href="<?= route('tasks.edit', $task['id']) ?>" class="btn btn-sm btn-outline-secondary action-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>

                                <!-- Deadline Warning -->
                                <?php
                                $deadline = new DateTime($task['due_date']);
                                $today = new DateTime();
                                $diff = $today->diff($deadline);
                                $daysLeft = $deadline > $today ? $diff->days : -$diff->days;
                                ?>
                                <?php if ($daysLeft <= 3 && $task['status'] !== 'Completed'): ?>
                                <div class="alert alert-<?= $daysLeft < 0 ? 'danger' : 'warning' ?> mt-2 mb-0 py-1 small">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <?php if ($daysLeft < 0): ?>
                                        Terlambat <?= abs($daysLeft) ?> hari
                                    <?php elseif ($daysLeft == 0): ?>
                                        Deadline hari ini!
                                    <?php else: ?>
                                        <?= $daysLeft ?> hari lagi
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- No Tasks Found -->
                <div id="noTasksMessage" class="text-center py-5" style="display: none;">
                    <i class="fas fa-tasks fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada task ditemukan</h5>
                    <p class="text-muted">Silakan ubah filter atau buat task baru</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Modals -->
    <!-- Quick Start Modal -->
    <div class="modal fade" id="quickStartModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mulai Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-play fa-3x text-success mb-3"></i>
                    <p>Mulai mengerjakan task ini sekarang?</p>
                    <p class="small text-muted">Status akan diubah menjadi "In Progress"</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="confirmStartTask()">Ya, Mulai</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Submit Modal -->
    <div class="modal fade" id="quickSubmitModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit untuk Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="quickSubmitForm">
                        <div class="mb-3">
                            <label for="quickComment" class="form-label">Catatan untuk Reviewer <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="quickComment" name="comment" rows="3" required
                                      placeholder="Jelaskan apa yang sudah dikerjakan..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning" onclick="confirmSubmitReview()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentTaskId = null;

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Status filter
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all filter buttons
                    document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;
                    filterTasks(filter, 'status');
                });
            });

            // Priority filter
            document.querySelectorAll('[data-priority]').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Toggle active state
                    this.classList.toggle('active');

                    // Get all active priority filters
                    const activePriorities = Array.from(document.querySelectorAll('[data-priority].active'))
                                                  .map(b => b.dataset.priority);

                    filterTasks(activePriorities, 'priority');
                });
            });
        });

        function filterTasks(filter, type) {
            const taskItems = document.querySelectorAll('.task-item');
            let visibleCount = 0;

            taskItems.forEach(item => {
                let show = true;

                if (type === 'status') {
                    if (filter !== 'all' && item.dataset.status !== filter) {
                        show = false;
                    }
                } else if (type === 'priority') {
                    if (Array.isArray(filter) && filter.length > 0) {
                        if (!filter.includes(item.dataset.priority)) {
                            show = false;
                        }
                    }
                }

                if (show) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide no tasks message
            const noTasksMessage = document.getElementById('noTasksMessage');
            if (visibleCount === 0) {
                noTasksMessage.style.display = 'block';
            } else {
                noTasksMessage.style.display = 'none';
            }
        }

        // Quick actions
        function quickStartTask(taskId) {
            currentTaskId = taskId;
            const modal = new bootstrap.Modal(document.getElementById('quickStartModal'));
            modal.show();
        }

        function confirmStartTask() {
            fetch(`/tasks/${currentTaskId}/start`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('quickStartModal')).hide();
                    showAlert('success', data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                showAlert('danger', 'Terjadi kesalahan sistem');
            });
        }

        function quickSubmitReview(taskId) {
            currentTaskId = taskId;
            const modal = new bootstrap.Modal(document.getElementById('quickSubmitModal'));
            modal.show();
        }

        function confirmSubmitReview() {
            const comment = document.getElementById('quickComment').value.trim();

            if (!comment) {
                showAlert('warning', 'Harap isi catatan untuk reviewer');
                return;
            }

            const formData = new FormData();
            formData.append('comment', comment);

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
                    bootstrap.Modal.getInstance(document.getElementById('quickSubmitModal')).hide();
                    showAlert('success', data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                showAlert('danger', 'Terjadi kesalahan sistem');
            });
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
    </script>
</body>
</html>
