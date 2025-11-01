<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Tugas Baru - Team Lead</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
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
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .form-section {
            border-left: 4px solid #11998e;
            padding: 20px;
            margin-bottom: 20px;
            background: #f8f9fa;
            border-radius: 0 10px 10px 0;
        }
        .permission-badge {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 2px;
        }
        .form-floating > label {
            color: #6c757d;
        }
        .btn-assign {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-assign:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
            color: white;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .team-member-card {
            border: 2px solid transparent;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .team-member-card:hover {
            border-color: #11998e;
            background: #f8f9fa;
        }
        .team-member-card.selected {
            border-color: #11998e;
            background: linear-gradient(135deg, #e8f5e8 0%, #f0fdf4 100%);
        }
        .priority-option {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
        }
        .priority-option:hover {
            border-color: #11998e;
        }
        .priority-option.selected {
            border-color: #11998e;
            background: linear-gradient(135deg, #e8f5e8 0%, #f0fdf4 100%);
        }
        .priority-critical { border-color: #dc3545 !important; background: #ffe6e6; }
        .priority-high { border-color: #fd7e14 !important; background: #fff3e0; }
        .priority-medium { border-color: #ffc107 !important; background: #fffbf0; }
        .priority-low { border-color: #28a745 !important; background: #e8f5e8; }
        .project-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .workload-indicator {
            background: white;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
        }
        .workload-bar {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            margin-top: 5px;
        }
        .workload-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        .workload-low { background: linear-gradient(90deg, #28a745, #20c997); }
        .workload-medium { background: linear-gradient(90deg, #ffc107, #fd7e14); }
        .workload-high { background: linear-gradient(90deg, #fd7e14, #dc3545); }
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
                    <a class="nav-link mb-1" href="<?= route('teamlead.tasks') ?>">
                        <i class="fas fa-tasks me-2"></i> Kelola Tugas
                    </a>
                    <a class="nav-link active mb-1" href="<?= route('teamlead.tasks.create') ?>">
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
                                <i class="fas fa-plus-circle me-2"></i>Assign Tugas Baru
                            </h2>
                            <p class="mb-0">Distribusi tugas kepada anggota tim dengan prioritas yang tepat</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="<?= route('teamlead.tasks') ?>" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Tasks
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Permission Indicators -->
                <div class="mb-4">
                    <span class="permission-badge"><i class="fas fa-tasks me-1"></i>Assign Tugas</span>
                    <span class="permission-badge"><i class="fas fa-flag me-1"></i>Set Priority</span>
                    <span class="permission-badge"><i class="fas fa-users me-1"></i>Distribusi Tim</span>
                    <span class="permission-badge"><i class="fas fa-calendar me-1"></i>Set Deadline</span>
                </div>

                <?php if (session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" action="<?= route('teamlead.tasks.store') ?>" id="assignTaskForm">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-card">
                                <!-- Task Information -->
                                <div class="form-section">
                                    <h5><i class="fas fa-info-circle me-2"></i>Informasi Tugas</h5>
                                    <p class="text-muted mb-0">Tentukan detail tugas yang akan didistribusikan</p>
                                </div>

                                <div class="p-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="title" name="title"
                                               placeholder="Judul Tugas" required maxlength="255">
                                        <label for="title" class="required-field">Judul Tugas</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="description" name="description"
                                                  placeholder="Deskripsi Tugas" style="height: 120px;" required></textarea>
                                        <label for="description" class="required-field">Deskripsi Tugas</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="project_id" name="project_id" required onchange="updateProjectInfo()">
                                                    <option value="">Pilih Proyek</option>
                                                    <?php foreach ($projects as $project): ?>
                                                    <option value="<?= $project['id'] ?>"
                                                            data-name="<?= htmlspecialchars($project['name']) ?>"
                                                            data-status="<?= $project['status'] ?>"
                                                            data-progress="<?= $project['progress'] ?>"
                                                            data-deadline="<?= $project['end_date'] ?>">
                                                        <?= htmlspecialchars($project['name']) ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <label for="project_id" class="required-field">Proyek</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="due_date" name="due_date"
                                                       placeholder="Deadline" required>
                                                <label for="due_date" class="required-field">Deadline</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="estimated_hours" name="estimated_hours"
                                               placeholder="Estimasi Jam" min="0" step="0.5">
                                        <label for="estimated_hours">Estimasi Jam Kerja</label>
                                    </div>
                                </div>

                                <!-- Priority Selection -->
                                <div class="form-section">
                                    <h5><i class="fas fa-flag me-2"></i>Set Priority</h5>
                                    <p class="text-muted mb-0">Tentukan prioritas tugas berdasarkan urgency dan impact</p>
                                </div>

                                <div class="p-4">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="priority-option priority-critical" data-priority="Critical">
                                                <i class="fas fa-exclamation-triangle fa-2x mb-2 text-danger"></i>
                                                <h6>Critical</h6>
                                                <small>Urgent & High Impact</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="priority-option priority-high" data-priority="High">
                                                <i class="fas fa-arrow-up fa-2x mb-2 text-warning"></i>
                                                <h6>High</h6>
                                                <small>Important & Time-sensitive</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="priority-option priority-medium" data-priority="Medium">
                                                <i class="fas fa-minus fa-2x mb-2 text-info"></i>
                                                <h6>Medium</h6>
                                                <small>Standard Priority</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="priority-option priority-low" data-priority="Low">
                                                <i class="fas fa-arrow-down fa-2x mb-2 text-success"></i>
                                                <h6>Low</h6>
                                                <small>Can be delayed</small>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="priority" name="priority" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Project Info -->
                            <div class="project-info" id="projectInfo" style="display: none;">
                                <h6><i class="fas fa-project-diagram me-2"></i>Info Proyek</h6>
                                <div id="projectDetails">
                                    <!-- Project details will be populated by JavaScript -->
                                </div>
                            </div>

                            <!-- Team Member Selection -->
                            <div class="form-card">
                                <div class="form-section">
                                    <h5><i class="fas fa-users me-2"></i>Assign ke Anggota Tim</h5>
                                    <p class="text-muted mb-0">Pilih anggota tim yang akan mengerjakan tugas ini</p>
                                </div>

                                <div class="p-4">
                                    <?php foreach ($teamMembers as $member): ?>
                                    <div class="team-member-card" data-member-id="<?= $member['id'] ?>">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?= htmlspecialchars($member['name']) ?></h6>
                                                <p class="small text-muted mb-0"><?= htmlspecialchars($member['role']) ?></p>
                                            </div>
                                        </div>

                                        <!-- Workload Indicator -->
                                        <div class="workload-indicator">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small>Current Workload:</small>
                                                <small><strong><?= $member['current_tasks'] ?? 0 ?> tasks</strong></small>
                                            </div>
                                            <div class="workload-bar">
                                                <?php
                                                $workload = $member['current_tasks'] ?? 0;
                                                $workloadPercentage = min(($workload / 10) * 100, 100); // Assume 10 tasks = 100%
                                                $workloadClass = $workload <= 3 ? 'workload-low' : ($workload <= 6 ? 'workload-medium' : 'workload-high');
                                                ?>
                                                <div class="workload-fill <?= $workloadClass ?>" style="width: <?= $workloadPercentage ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>

                                    <input type="hidden" id="assigned_to" name="assigned_to" required>
                                </div>
                            </div>

                            <!-- Team Lead Actions -->
                            <div class="form-card">
                                <div class="form-section">
                                    <h5><i class="fas fa-cogs me-2"></i>Opsi Koordinasi</h5>
                                    <p class="text-muted mb-0">Pengaturan koordinasi tim</p>
                                </div>

                                <div class="p-4">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="notify_assignee"
                                               name="notify_assignee" value="1" checked>
                                        <label class="form-check-label" for="notify_assignee">
                                            Notify assignee immediately
                                        </label>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="track_progress"
                                               name="track_progress" value="1" checked>
                                        <label class="form-check-label" for="track_progress">
                                            Enable progress tracking
                                        </label>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="require_review"
                                               name="require_review" value="1">
                                        <label class="form-check-label" for="require_review">
                                            Require Team Lead review
                                        </label>
                                    </div>
                                </div>

                                <!-- Assign Button -->
                                <div class="p-4 border-top">
                                    <button type="submit" class="btn btn-assign w-100 btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>Assign Tugas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Team Lead Guidelines -->
                <div class="alert alert-info mt-4">
                    <h6><i class="fas fa-lightbulb me-2"></i>Tips Team Lead untuk Assignment:</h6>
                    <ul class="mb-0">
                        <li>Pertimbangkan workload saat ini sebelum assign task</li>
                        <li>Set priority berdasarkan impact terhadap project deadline</li>
                        <li>Berikan estimasi jam yang realistis untuk planning</li>
                        <li>Gunakan review requirement untuk task yang critical</li>
                        <li>Monitor progress regularly untuk identify blockers early</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize date picker
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#due_date", {
                dateFormat: "Y-m-d",
                minDate: "today"
            });

            // Priority selection
            document.querySelectorAll('.priority-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.priority-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    document.getElementById('priority').value = this.dataset.priority;
                });
            });

            // Team member selection
            document.querySelectorAll('.team-member-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.team-member-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    document.getElementById('assigned_to').value = this.dataset.memberId;
                });
            });

            // Form validation
            document.getElementById('assignTaskForm').addEventListener('submit', function(e) {
                const priority = document.getElementById('priority').value;
                const assignedTo = document.getElementById('assigned_to').value;

                if (!priority) {
                    e.preventDefault();
                    alert('Silakan pilih priority untuk tugas ini');
                    return false;
                }

                if (!assignedTo) {
                    e.preventDefault();
                    alert('Silakan pilih anggota tim untuk assign tugas ini');
                    return false;
                }

                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Assigning Task...';
                submitBtn.disabled = true;
            });
        });

        function updateProjectInfo() {
            const projectSelect = document.getElementById('project_id');
            const selectedOption = projectSelect.options[projectSelect.selectedIndex];
            const projectInfo = document.getElementById('projectInfo');
            const projectDetails = document.getElementById('projectDetails');

            if (selectedOption.value) {
                const projectName = selectedOption.dataset.name;
                const projectStatus = selectedOption.dataset.status;
                const projectProgress = selectedOption.dataset.progress;
                const projectDeadline = selectedOption.dataset.deadline;

                projectDetails.innerHTML = `
                    <p class="mb-2"><strong>Status:</strong>
                        <span class="badge bg-${projectStatus === 'Active' ? 'success' : 'secondary'}">${projectStatus}</span>
                    </p>
                    <p class="mb-2"><strong>Progress:</strong> ${projectProgress}%</p>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-light" style="width: ${projectProgress}%"></div>
                    </div>
                    <p class="mb-0"><strong>Deadline:</strong> ${new Date(projectDeadline).toLocaleDateString('id-ID')}</p>
                `;

                projectInfo.style.display = 'block';

                // Set default task deadline based on project deadline
                const taskDeadline = new Date(projectDeadline);
                taskDeadline.setDate(taskDeadline.getDate() - 1); // 1 day before project deadline
                document.getElementById('due_date').value = taskDeadline.toISOString().split('T')[0];
            } else {
                projectInfo.style.display = 'none';
            }
        }

        // Real-time validation
        document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
            field.addEventListener('blur', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
        });
    </script>
</body>
</html>
