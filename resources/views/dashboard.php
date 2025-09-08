<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        .status-idle {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .status-working {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
        .role-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .quick-action-btn {
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-rocket me-2"></i>Project Manager
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>

                    @if(auth()->user()->isProjectAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users') }}">
                                <i class="fas fa-users me-1"></i>Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.projects') }}">
                                <i class="fas fa-project-diagram me-1"></i>Projects
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->isTeamLead() || auth()->user()->isProjectAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('team.members') }}">
                                <i class="fas fa-user-friends me-1"></i>Team
                            </a>
                        </li>
                    @endif
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->full_name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-2">Welcome back, {{ auth()->user()->full_name }}! 👋</h2>
                                <p class="mb-3 opacity-75">Ready to make today productive?</p>
                                <div class="d-flex align-items-center">
                                    <span class="role-badge me-3">{{ auth()->user()->role }}</span>
                                    <span class="status-badge {{ auth()->user()->isWorking() ? 'status-working' : 'status-idle' }}">
                                        <i class="fas fa-circle me-1"></i>{{ auth()->user()->current_task_status }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <i class="fas fa-user-circle" style="font-size: 5rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                        <h4 class="fw-bold">12</h4>
                        <p class="text-muted mb-0">Active Tasks</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <h4 class="fw-bold">8</h4>
                        <p class="text-muted mb-0">Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h4 class="fw-bold">3</h4>
                        <p class="text-muted mb-0">Pending Review</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-calendar fa-2x"></i>
                        </div>
                        <h4 class="fw-bold">2</h4>
                        <p class="text-muted mb-0">This Week</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(auth()->user()->isDeveloper() || auth()->user()->isTeamLead() || auth()->user()->isProjectAdmin())
                                <div class="col-md-3 mb-2">
                                    <button class="btn btn-outline-primary quick-action-btn w-100">
                                        <i class="fas fa-code me-2"></i>New Task
                                    </button>
                                </div>
                            @endif

                            @if(auth()->user()->isDesigner() || auth()->user()->isTeamLead() || auth()->user()->isProjectAdmin())
                                <div class="col-md-3 mb-2">
                                    <button class="btn btn-outline-success quick-action-btn w-100">
                                        <i class="fas fa-palette me-2"></i>Design Review
                                    </button>
                                </div>
                            @endif

                            @if(auth()->user()->isTeamLead() || auth()->user()->isProjectAdmin())
                                <div class="col-md-3 mb-2">
                                    <button class="btn btn-outline-warning quick-action-btn w-100">
                                        <i class="fas fa-users me-2"></i>Team Meeting
                                    </button>
                                </div>
                            @endif

                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-info quick-action-btn w-100" onclick="toggleTaskStatus()">
                                    <i class="fas fa-{{ auth()->user()->isWorking() ? 'pause' : 'play' }} me-2"></i>
                                    {{ auth()->user()->isWorking() ? 'Take Break' : 'Start Working' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Tasks -->
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                        <small class="text-muted">Last 7 days</small>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex align-items-center border-0">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-plus text-white small"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Task Created: "Update login system"</h6>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center border-0">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-check text-white small"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Task Completed: "Database migration"</h6>
                                    <small class="text-muted">Yesterday</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center border-0">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="fas fa-comment text-white small"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">New comment on "Homepage design"</h6>
                                    <small class="text-muted">2 days ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Today's Tasks</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">
                                        <s>Review pull requests</s>
                                    </label>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">
                                        Update documentation
                                    </label>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">
                                        Team standup meeting
                                    </label>
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
        function toggleTaskStatus() {
            // This would typically make an AJAX call to update the user's task status
            alert('Feature coming soon! This will toggle your work status.');
        }
    </script>
</body>
</html>
