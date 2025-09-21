<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Task Baru - Manajemen Proyek</title>
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
        .form-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
                    <h2>Tambah Task Baru</h2>
                    <a href="<?= route('tasks.index') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card form-card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Form Task Baru</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?= route('tasks.store') ?>">
                                    <?= csrf_field() ?>

                                    <div class="mb-3">
                                        <label for="title" class="form-label">Judul Task <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?= $errors->has('title') ? 'is-invalid' : '' ?>"
                                               id="title" name="title" value="<?= old('title') ?>"
                                               placeholder="Masukkan judul task..." required>
                                        <?php if ($errors->has('title')): ?>
                                            <div class="invalid-feedback"><?= $errors->first('title') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control <?= $errors->has('description') ? 'is-invalid' : '' ?>"
                                                  id="description" name="description" rows="4"
                                                  placeholder="Jelaskan detail task yang akan dikerjakan..."><?= old('description') ?></textarea>
                                        <?php if ($errors->has('description')): ?>
                                            <div class="invalid-feedback"><?= $errors->first('description') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="project_id" class="form-label">Proyek <span class="text-danger">*</span></label>
                                            <select class="form-select <?= $errors->has('project_id') ? 'is-invalid' : '' ?>"
                                                    id="project_id" name="project_id" required>
                                                <option value="">Pilih Proyek</option>
                                                <?php foreach ($projects as $project): ?>
                                                <option value="<?= $project['id'] ?>" <?= old('project_id') == $project['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($project['name']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php if ($errors->has('project_id')): ?>
                                                <div class="invalid-feedback"><?= $errors->first('project_id') ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                                            <select class="form-select <?= $errors->has('priority') ? 'is-invalid' : '' ?>"
                                                    id="priority" name="priority" required>
                                                <option value="">Pilih Prioritas</option>
                                                <option value="Low" <?= old('priority') == 'Low' ? 'selected' : '' ?>>Low</option>
                                                <option value="Medium" <?= old('priority') == 'Medium' ? 'selected' : '' ?>>Medium</option>
                                                <option value="High" <?= old('priority') == 'High' ? 'selected' : '' ?>>High</option>
                                            </select>
                                            <?php if ($errors->has('priority')): ?>
                                                <div class="invalid-feedback"><?= $errors->first('priority') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="due_date" class="form-label">Tanggal Deadline <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control <?= $errors->has('due_date') ? 'is-invalid' : '' ?>"
                                                   id="due_date" name="due_date" value="<?= old('due_date') ?>"
                                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                                            <?php if ($errors->has('due_date')): ?>
                                                <div class="invalid-feedback"><?= $errors->first('due_date') ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="estimated_hours" class="form-label">Estimasi Jam Kerja</label>
                                            <input type="number" class="form-control <?= $errors->has('estimated_hours') ? 'is-invalid' : '' ?>"
                                                   id="estimated_hours" name="estimated_hours" value="<?= old('estimated_hours') ?>"
                                                   step="0.5" min="0.5" max="999" placeholder="8">
                                            <div class="form-text">Dalam jam (contoh: 8 untuk 8 jam)</div>
                                            <?php if ($errors->has('estimated_hours')): ?>
                                                <div class="invalid-feedback"><?= $errors->first('estimated_hours') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Task Details -->
                                    <div class="mb-3">
                                        <label class="form-label">Detail Tambahan</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="requires_review" name="requires_review" value="1">
                                                    <label class="form-check-label" for="requires_review">
                                                        Memerlukan Review
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_urgent" name="is_urgent" value="1">
                                                    <label class="form-check-label" for="is_urgent">
                                                        Task Urgent
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tags -->
                                    <div class="mb-4">
                                        <label for="tags" class="form-label">Tags</label>
                                        <input type="text" class="form-control" id="tags" name="tags"
                                               placeholder="frontend, api, database (pisahkan dengan koma)">
                                        <div class="form-text">Tambahkan tags untuk memudahkan pencarian (opsional)</div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="<?= route('tasks.index') ?>" class="btn btn-secondary me-md-2">
                                            <i class="fas fa-times me-2"></i>Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Simpan Task
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Tips Card -->
                        <div class="card form-card mt-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips Membuat Task</h6>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0 small">
                                    <li>Gunakan judul yang jelas dan spesifik</li>
                                    <li>Berikan deskripsi detail untuk memudahkan pengerjaan</li>
                                    <li>Set estimasi waktu yang realistis</li>
                                    <li>Pilih prioritas sesuai dengan tingkat kepentingan</li>
                                    <li>Gunakan tags untuk memudahkan kategorisasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-calculate estimated hours based on priority
        document.getElementById('priority').addEventListener('change', function() {
            const estimatedHours = document.getElementById('estimated_hours');
            if (!estimatedHours.value) {
                switch(this.value) {
                    case 'Low':
                        estimatedHours.value = '4';
                        break;
                    case 'Medium':
                        estimatedHours.value = '8';
                        break;
                    case 'High':
                        estimatedHours.value = '16';
                        break;
                }
            }
        });

        // Auto-set due date based on estimated hours
        document.getElementById('estimated_hours').addEventListener('change', function() {
            const dueDate = document.getElementById('due_date');
            if (!dueDate.value) {
                const hours = parseFloat(this.value);
                const days = Math.ceil(hours / 8); // Assuming 8 hours per day
                const today = new Date();
                today.setDate(today.getDate() + days);
                dueDate.value = today.toISOString().split('T')[0];
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const projectId = document.getElementById('project_id').value;
            const priority = document.getElementById('priority').value;
            const dueDate = document.getElementById('due_date').value;

            if (!title || !projectId || !priority || !dueDate) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi (*)');
                return false;
            }

            // Check if due date is in the future
            const selectedDate = new Date(dueDate);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate <= today) {
                e.preventDefault();
                alert('Tanggal deadline harus lebih dari hari ini');
                return false;
            }
        });
    </script>
</body>
</html>
