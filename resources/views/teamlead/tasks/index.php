<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tugas - Team Lead</title>
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
            border-radius: 8px;
            margin: 2px 0;
        }
        .nav-link:hover, .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .teamlead-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .task-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        .task-card:hover {
            transform: translateY(-3px);
        }
        .priority-critical { border-left: 4px solid #dc3545; }
        .priority-high { border-left: 4px solid #fd7e14; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .status-todo { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); }
        .status-inprogress { background: linear-gradient(135deg, #fff3cd 0%, #fef9e7 100%); }
        .status-review { background: linear-gradient(135deg, #cff4fc 0%, #e7f3ff 100%); }
        .status-done { background: linear-gradient(135deg, #d1e7dd 0%, #d4edda 100%); }
        .status-blocked { background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%); }
        .permission-badge {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 2px;
        }
        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stats-overview {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .assign-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .assign-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .priority-selector {
            width: 100px;
            font-size: 0.85rem;
        }
        .status-selector {
            width: 120px;
            font-size: 0.85rem;
        }
        .kanban-board {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding: 10px 0;
        }
        .kanban-column {
            min-width: 280px;
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .kanban-header {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
        .kanban-todo { background: #f8f9fa; color: #6c757d; }
        .kanban-progress { background: #fff3cd; color: #856404; }
        .kanban-review { background: #cff4fc; color: #055160; }
        .kanban-done { background: #d1e7dd; color: #0f5132; }
        .kanban-blocked { background: #f8d7da; color: #721c24; }
        .task-item {
            background: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .task-item:hover {
            transform: translateY(-2px);
        }
        .view-toggle {
            background: white;
            border-radius: 10px;
            padding: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Team Lead Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="text-center mb-4">
                    <h4><i class="fas fa-users-cog"></i> Team Lead</h4>
                    <small>Team Leadership Panel</small>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link mb-1" href="<?= route('teamlead.dashboard') ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link active mb-1" href="<?= route('teamlead.tasks') ?>">
                        <i class="fas fa-tasks me-2"></i> Kelola Tugas
                    </a>
                    <a class="nav-link mb-1" href="<?= route('teamlead.tasks.create') ?>">
                        <i class="fas fa-plus-circle me-2"></i> Assign Tugas
                    </a>
                    <a class="nav-link mb-1" href="<?= route('teamlead.coordination') ?>">
                        <i class="fas fa-project-diagram me-2"></i> Koordinasi Tim
                    </a>

                    <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">

                    <a class="nav-link mb-1" href="<?= route('home') ?>">
                        <i class="fas fa-home me-2"></i> User Dashboard
                    </a>
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
                <div class="teamlead-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1">
                                <i class="fas fa-tasks me-2"></i>Kelola Tugas Tim
                            </h2>
                            <p class="mb-0">Distribusi tugas, set priority, dan monitoring progress tim</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="<?= route('teamlead.tasks.create') ?>" class="btn btn-light btn-lg">
                                <i class="fas fa-plus me-2"></i>Assign Tugas Baru
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Permission Indicators -->
                <div class="mb-4">
                    <span class="permission-badge"><i class="fas fa-tasks me-1"></i>Assign Tugas</span>
                    <span class="permission-badge"><i class="fas fa-flag me-1"></i>Set Priority</span>
                    <span class="permission-badge"><i class="fas fa-sync me-1"></i>Update Status</span>
                    <span class="permission-badge"><i class="fas fa-edit me-1"></i>Edit Team Tasks</span>
                    <span class="permission-badge"><i class="fas fa-eye me-1"></i>View All Progress</span>
                </div>

                <?php if (session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Task Statistics -->
                <div class="stats-overview">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-secondary"><?= $taskStats['by_status'][0]['count'] ?? 0 ?></h4>
                                <small>To Do</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-warning"><?= $taskStats['by_status'][1]['count'] ?? 0 ?></h4>
                                <small>In Progress</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-info"><?= $taskStats['by_status'][2]['count'] ?? 0 ?></h4>
                                <small>Review</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-success"><?= $taskStats['by_status'][3]['count'] ?? 0 ?></h4>
                                <small>Done</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-danger"><?= $taskStats['by_status'][4]['count'] ?? 0 ?></h4>
                                <small>Blocked</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-primary"><?= count($tasks) ?></h4>
                                <small>Total Tasks</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Toggle & Filters -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="view-toggle">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-primary active" id="listView" onclick="switchView('list')">
                                    <i class="fas fa-list me-2"></i>List View
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="kanbanView" onclick="switchView('kanban')">
                                    <i class="fas fa-columns me-2"></i>Kanban Board
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-section">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <select class="form-select form-select-sm" id="priorityFilter" onchange="filterTasks()">
                                        <option value="">All Priorities</option>
                                        <option value="Critical">Critical</option>
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select form-select-sm" id="statusFilter" onchange="filterTasks()">
                                        <option value="">All Status</option>
                                        <option value="To Do">To Do</option>
                                        <option value="In Progress">In Progress</option>
                                        <option value="Review">Review</option>
                                        <option value="Done">Done</option>
                                        <option value="Blocked">Blocked</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control form-control-sm" id="searchTasks"
                                           placeholder="Search tasks..." onkeyup="filterTasks()">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- List View -->
                <div id="listViewContainer">
                    <?php foreach ($tasks as $task): ?>
                    <div class="task-card priority-<?= strtolower($task['priority']) ?> task-item-filter"
                         data-priority="<?= $task['priority'] ?>"
                         data-status="<?= $task['status'] ?>"
                         data-title="<?= htmlspecialchars($task['title']) ?>">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h6 class="mb-1"><?= htmlspecialchars($task['title']) ?></h6>
                                    <p class="small text-muted mb-1"><?= htmlspecialchars($task['project_name']) ?></p>
                                    <p class="small text-muted mb-0">
                                        <i class="fas fa-user me-1"></i><?= htmlspecialchars($task['assigned_to_name']) ?>
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select priority-selector"
                                            onchange="updateTaskPriority(<?= $task['id'] ?>, this.value)">
                                        <option value="Low" <?= $task['priority'] === 'Low' ? 'selected' : '' ?>>Low</option>
                                        <option value="Medium" <?= $task['priority'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
                                        <option value="High" <?= $task['priority'] === 'High' ? 'selected' : '' ?>>High</option>
                                        <option value="Critical" <?= $task['priority'] === 'Critical' ? 'selected' : '' ?>>Critical</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select status-selector"
                                            onchange="updateTaskStatus(<?= $task['id'] ?>, this.value)">
                                        <option value="To Do" <?= $task['status'] === 'To Do' ? 'selected' : '' ?>>To Do</option>
                                        <option value="In Progress" <?= $task['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="Review" <?= $task['status'] === 'Review' ? 'selected' : '' ?>>Review</option>
                                        <option value="Done" <?= $task['status'] === 'Done' ? 'selected' : '' ?>>Done</option>
                                        <option value="Blocked" <?= $task['status'] === 'Blocked' ? 'selected' : '' ?>>Blocked</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('d M Y', strtotime($task['due_date'])) ?>
                                    </small>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a href="<?= route('teamlead.tasks.edit', $task['id']) ?>"
                                       class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($task['status'] === 'Blocked'): ?>
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="resolveBlocker(<?= $task['id'] ?>)">
                                        <i class="fas fa-unlock"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Kanban View -->
                <div id="kanbanViewContainer" style="display: none;">
                    <div class="kanban-board">
                        <!-- To Do Column -->
                        <div class="kanban-column">
                            <div class="kanban-header kanban-todo">
                                <i class="fas fa-clipboard-list me-2"></i>To Do
                                <span class="badge bg-secondary ms-2"><?= count(array_filter($tasks, fn($t) => $t['status'] === 'To Do')) ?></span>
                            </div>
                            <div class="kanban-tasks" data-status="To Do">
                                <?php foreach ($tasks as $task): ?>
                                <?php if ($task['status'] === 'To Do'): ?>
                                <div class="task-item priority-<?= strtolower($task['priority']) ?>"
                                     data-task-id="<?= $task['id'] ?>" draggable="true">
                                    <h6 class="mb-1"><?= htmlspecialchars($task['title']) ?></h6>
                                    <p class="small text-muted mb-2"><?= htmlspecialchars($task['assigned_to_name']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-<?=
                                            $task['priority'] === 'Critical' ? 'danger' :
                                            ($task['priority'] === 'High' ? 'warning' :
                                            ($task['priority'] === 'Medium' ? 'info' : 'success')) ?>">
                                            <?= $task['priority'] ?>
                                        </span>
                                        <small class="text-muted"><?= date('d M', strtotime($task['due_date'])) ?></small>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- In Progress Column -->
                        <div class="kanban-column">
                            <div class="kanban-header kanban-progress">
                                <i class="fas fa-play-circle me-2"></i>In Progress
                                <span class="badge bg-warning ms-2"><?= count(array_filter($tasks, fn($t) => $t['status'] === 'In Progress')) ?></span>
                            </div>
                            <div class="kanban-tasks" data-status="In Progress">
                                <?php foreach ($tasks as $task): ?>
                                <?php if ($task['status'] === 'In Progress'): ?>
                                <div class="task-item priority-<?= strtolower($task['priority']) ?>"
                                     data-task-id="<?= $task['id'] ?>" draggable="true">
                                    <h6 class="mb-1"><?= htmlspecialchars($task['title']) ?></h6>
                                    <p class="small text-muted mb-2"><?= htmlspecialchars($task['assigned_to_name']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-<?=
                                            $task['priority'] === 'Critical' ? 'danger' :
                                            ($task['priority'] === 'High' ? 'warning' :
                                            ($task['priority'] === 'Medium' ? 'info' : 'success')) ?>">
                                            <?= $task['priority'] ?>
                                        </span>
                                        <small class="text-muted"><?= date('d M', strtotime($task['due_date'])) ?></small>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Review Column -->
                        <div class="kanban-column">
                            <div class="kanban-header kanban-review">
                                <i class="fas fa-eye me-2"></i>Review
                                <span class="badge bg-info ms-2"><?= count(array_filter($tasks, fn($t) => $t['status'] === 'Review')) ?></span>
                            </div>
                            <div class="kanban-tasks" data-status="Review">
                                <?php foreach ($tasks as $task): ?>
                                <?php if ($task['status'] === 'Review'): ?>
                                <div class="task-item priority-<?= strtolower($task['priority']) ?>"
                                     data-task-id="<?= $task['id'] ?>" draggable="true">
                                    <h6 class="mb-1"><?= htmlspecialchars($task['title']) ?></h6>
                                    <p class="small text-muted mb-2"><?= htmlspecialchars($task['assigned_to_name']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-<?=
                                            $task['priority'] === 'Critical' ? 'danger' :
                                            ($task['priority'] === 'High' ? 'warning' :
                                            ($task['priority'] === 'Medium' ? 'info' : 'success')) ?>">
                                            <?= $task['priority'] ?>
                                        </span>
                                        <small class="text-muted"><?= date('d M', strtotime($task['due_date'])) ?></small>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Done Column -->
                        <div class="kanban-column">
                            <div class="kanban-header kanban-done">
                                <i class="fas fa-check-circle me-2"></i>Done
                                <span class="badge bg-success ms-2"><?= count(array_filter($tasks, fn($t) => $t['status'] === 'Done')) ?></span>
                            </div>
                            <div class="kanban-tasks" data-status="Done">
                                <?php foreach ($tasks as $task): ?>
                                <?php if ($task['status'] === 'Done'): ?>
                                <div class="task-item priority-<?= strtolower($task['priority']) ?>"
                                     data-task-id="<?= $task['id'] ?>" draggable="true">
                                    <h6 class="mb-1"><?= htmlspecialchars($task['title']) ?></h6>
                                    <p class="small text-muted mb-2"><?= htmlspecialchars($task['assigned_to_name']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-<?=
                                            $task['priority'] === 'Critical' ? 'danger' :
                                            ($task['priority'] === 'High' ? 'warning' :
                                            ($task['priority'] === 'Medium' ? 'info' : 'success')) ?>">
                                            <?= $task['priority'] ?>
                                        </span>
                                        <small class="text-muted"><?= date('d M', strtotime($task['due_date'])) ?></small>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Blocked Column -->
                        <div class="kanban-column">
                            <div class="kanban-header kanban-blocked">
                                <i class="fas fa-exclamation-triangle me-2"></i>Blocked
                                <span class="badge bg-danger ms-2"><?= count(array_filter($tasks, fn($t) => $t['status'] === 'Blocked')) ?></span>
                            </div>
                            <div class="kanban-tasks" data-status="Blocked">
                                <?php foreach ($tasks as $task): ?>
                                <?php if ($task['status'] === 'Blocked'): ?>
                                <div class="task-item priority-<?= strtolower($task['priority']) ?>"
                                     data-task-id="<?= $task['id'] ?>" draggable="true">
                                    <h6 class="mb-1"><?= htmlspecialchars($task['title']) ?></h6>
                                    <p class="small text-muted mb-2"><?= htmlspecialchars($task['assigned_to_name']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-<?=
                                            $task['priority'] === 'Critical' ? 'danger' :
                                            ($task['priority'] === 'High' ? 'warning' :
                                            ($task['priority'] === 'Medium' ? 'info' : 'success')) ?>">
                                            <?= $task['priority'] ?>
                                        </span>
                                        <button class="btn btn-sm btn-outline-danger"
                                                onclick="resolveBlocker(<?= $task['id'] ?>)">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resolve Blocker Modal -->
    <div class="modal fade" id="resolveBlockerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-unlock me-2"></i>Resolve Blocker
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="resolveBlockerForm" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Resolution Details</label>
                            <textarea class="form-control" name="resolution" rows="3"
                                      placeholder="Jelaskan bagaimana blocker ini diselesaikan..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Status</label>
                            <select class="form-select" name="new_status" required>
                                <option value="To Do">To Do</option>
                                <option value="In Progress" selected>In Progress</option>
                                <option value="Review">Review</option>
                                <option value="Done">Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-unlock me-2"></i>Resolve Blocker
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function switchView(view) {
            const listView = document.getElementById('listViewContainer');
            const kanbanView = document.getElementById('kanbanViewContainer');
            const listBtn = document.getElementById('listView');
            const kanbanBtn = document.getElementById('kanbanView');

            if (view === 'list') {
                listView.style.display = 'block';
                kanbanView.style.display = 'none';
                listBtn.classList.add('active');
                kanbanBtn.classList.remove('active');
            } else {
                listView.style.display = 'none';
                kanbanView.style.display = 'block';
                listBtn.classList.remove('active');
                kanbanBtn.classList.add('active');
            }
        }

        function filterTasks() {
            const priorityFilter = document.getElementById('priorityFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const searchTerm = document.getElementById('searchTasks').value.toLowerCase();

            const tasks = document.querySelectorAll('.task-item-filter');

            tasks.forEach(task => {
                const priority = task.dataset.priority;
                const status = task.dataset.status;
                const title = task.dataset.title.toLowerCase();

                const showPriority = !priorityFilter || priority === priorityFilter;
                const showStatus = !statusFilter || status === statusFilter;
                const showSearch = !searchTerm || title.includes(searchTerm);

                if (showPriority && showStatus && showSearch) {
                    task.style.display = 'block';
                } else {
                    task.style.display = 'none';
                }
            });
        }

        function updateTaskStatus(taskId, status) {
            fetch(`/teamlead/tasks/${taskId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI or refresh page
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate status');
            });
        }

        function updateTaskPriority(taskId, priority) {
            fetch(`/teamlead/tasks/${taskId}/priority`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ priority: priority })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI or refresh page
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate prioritas');
            });
        }

        function resolveBlocker(taskId) {
            document.getElementById('resolveBlockerForm').action = `/teamlead/tasks/${taskId}/resolve-blocker`;
            const modal = new bootstrap.Modal(document.getElementById('resolveBlockerModal'));
            modal.show();
        }

        // Kanban drag and drop functionality
        document.addEventListener('DOMContentLoaded', function() {
            const taskItems = document.querySelectorAll('.task-item[draggable="true"]');
            const kanbanColumns = document.querySelectorAll('.kanban-tasks');

            taskItems.forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    e.dataTransfer.setData('text/plain', this.dataset.taskId);
                });
            });

            kanbanColumns.forEach(column => {
                column.addEventListener('dragover', function(e) {
                    e.preventDefault();
                });

                column.addEventListener('drop', function(e) {
                    e.preventDefault();
                    const taskId = e.dataTransfer.getData('text/plain');
                    const newStatus = this.dataset.status;

                    updateTaskStatus(taskId, newStatus);
                });
            });
        });
    </script>
</body>
</html>
