<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Manajemen Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
        }
        .info-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
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
                    <a class="nav-link active mb-2" href="<?= route('profile.show') ?>"><i class="fas fa-user me-2"></i> Profile</a>
                    <a class="nav-link mb-2" href="<?= route('tasks.index') ?>"><i class="fas fa-tasks me-2"></i> Tugas Saya</a>
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
                    <h2>Profile Saya</h2>
                    <a href="<?= route('profile.edit') ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>

                <?php if (session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Profile Header -->
                <div class="profile-header p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <img src="<?= $user->avatar ? asset($user->avatar) : 'https://via.placeholder.com/120x120/6c63ff/ffffff?text=' . substr($user->name, 0, 1) ?>"
                                 alt="Avatar" class="profile-avatar">
                        </div>
                        <div class="col-md-9">
                            <h2><?= htmlspecialchars($user->name) ?></h2>
                            <p class="mb-2"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($user->email) ?></p>
                            <?php if (isset($user->phone) && $user->phone): ?>
                            <p class="mb-2"><i class="fas fa-phone me-2"></i><?= htmlspecialchars($user->phone) ?></p>
                            <?php endif; ?>
                            <p class="mb-0"><i class="fas fa-calendar me-2"></i>Bergabung sejak <?= date('d F Y', strtotime($user->created_at)) ?></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-md-6 mb-4">
                        <div class="card info-card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Personal</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Nama Lengkap:</strong></div>
                                    <div class="col-sm-8"><?= htmlspecialchars($user->name) ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Email:</strong></div>
                                    <div class="col-sm-8"><?= htmlspecialchars($user->email) ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Telepon:</strong></div>
                                    <div class="col-sm-8"><?= isset($user->phone) && $user->phone ? htmlspecialchars($user->phone) : '-' ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Status:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-success">Member Aktif</span>
                                    </div>
                                </div>
                                <?php if (isset($user->bio) && $user->bio): ?>
                                <div class="row">
                                    <div class="col-sm-4"><strong>Bio:</strong></div>
                                    <div class="col-sm-8"><?= nl2br(htmlspecialchars($user->bio)) ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Statistics -->
                    <div class="col-md-6 mb-4">
                        <div class="card info-card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik Aktivitas</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="border-end">
                                            <h3 class="text-primary mb-1">8</h3>
                                            <small class="text-muted">Total Proyek</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h3 class="text-success mb-1">45</h3>
                                        <small class="text-muted">Task Selesai</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="border-end">
                                            <h3 class="text-warning mb-1">12</h3>
                                            <small class="text-muted">Task Pending</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h3 class="text-info mb-1">156</h3>
                                        <small class="text-muted">Total Jam</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <p class="mb-1"><strong>Rata-rata Penyelesaian:</strong></p>
                                    <h4 class="text-primary">7.5 Hari</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card info-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-tasks text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Task "Develop authentication" diperbaharui</div>
                                    <small class="text-muted">2 jam yang lalu</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Task "Database schema" diselesaikan</div>
                                    <small class="text-muted">5 jam yang lalu</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Mencatat 8 jam waktu kerja</div>
                                    <small class="text-muted">1 hari yang lalu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
