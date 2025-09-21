<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Manajemen Proyek</title>
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
        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dee2e6;
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
                    <h2>Edit Profile</h2>
                    <a href="<?= route('profile.show') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>

                <?php if (session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Profile Information -->
                    <div class="col-md-8 mb-4">
                        <div class="card form-card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Informasi Profile</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?= route('profile.update') ?>" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <?= method_field('PUT') ?>

                                    <!-- Avatar Upload -->
                                    <div class="row mb-4">
                                        <div class="col-md-3 text-center">
                                            <img id="avatar-preview"
                                                 src="<?= $user->avatar ? asset($user->avatar) : 'https://via.placeholder.com/100x100/6c63ff/ffffff?text=' . substr($user->name, 0, 1) ?>"
                                                 alt="Avatar" class="avatar-preview mb-3">
                                            <div>
                                                <label for="avatar" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-camera me-1"></i>Ubah Foto
                                                </label>
                                                <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                       value="<?= old('name', $user->name) ?>" required>
                                                <?php if ($errors->has('name')): ?>
                                                    <div class="text-danger small mt-1"><?= $errors->first('name') ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                       value="<?= old('email', $user->email) ?>" required>
                                                <?php if ($errors->has('email')): ?>
                                                    <div class="text-danger small mt-1"><?= $errors->first('email') ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">Nomor Telepon</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                   value="<?= old('phone', $user->phone ?? '') ?>" placeholder="+62">
                                            <?php if ($errors->has('phone')): ?>
                                                <div class="text-danger small mt-1"><?= $errors->first('phone') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea class="form-control" id="bio" name="bio" rows="4"
                                                  placeholder="Ceritakan sedikit tentang diri Anda..."><?= old('bio', $user->bio ?? '') ?></textarea>
                                        <?php if ($errors->has('bio')): ?>
                                            <div class="text-danger small mt-1"><?= $errors->first('bio') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="col-md-4 mb-4">
                        <div class="card form-card">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Ubah Password</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?= route('profile.password.update') ?>">
                                    <?= csrf_field() ?>
                                    <?= method_field('PUT') ?>

                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        <?php if ($errors->has('current_password')): ?>
                                            <div class="text-danger small mt-1"><?= $errors->first('current_password') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <?php if ($errors->has('password')): ?>
                                            <div class="text-danger small mt-1"><?= $errors->first('password') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-key me-2"></i>Ubah Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Account Info -->
                        <div class="card form-card mt-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Akun</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <small class="text-muted">Akun dibuat:</small>
                                    <div><?= date('d F Y', strtotime($user->created_at)) ?></div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Terakhir login:</small>
                                    <div><?= date('d F Y H:i', strtotime($user->updated_at)) ?></div>
                                </div>
                                <div>
                                    <small class="text-muted">Status:</small>
                                    <div><span class="badge bg-success">Aktif</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Avatar preview
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
