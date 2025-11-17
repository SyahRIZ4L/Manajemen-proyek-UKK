@extends('member.layout')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <!-- Profile Photo Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 150px; height: 150px;">
                    <i class="bi bi-person fs-1 text-white"></i>
                </div>

                <h4>{{ auth()->user()->full_name }}</h4>
                <p class="text-muted">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>

                @if(auth()->user()->bio)
                    <p class="text-muted small">{{ auth()->user()->bio }}</p>
                @endif

                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit Profile
                </a>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Email:</strong>
                    <p class="mb-0">{{ auth()->user()->email }}</p>
                </div>

                @if(auth()->user()->phone)
                    <div class="mb-3">
                        <strong>Phone:</strong>
                        <p class="mb-0">{{ auth()->user()->phone }}</p>
                    </div>
                @endif

                @if(auth()->user()->address)
                    <div class="mb-3">
                        <strong>Address:</strong>
                        <p class="mb-0">{{ auth()->user()->address }}</p>
                    </div>
                @endif

                @if(auth()->user()->website)
                    <div class="mb-3">
                        <strong>Website:</strong>
                        <p class="mb-0">
                            <a href="{{ auth()->user()->website }}" target="_blank" class="text-decoration-none">
                                {{ auth()->user()->website }}
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Username:</strong>
                        <p class="mb-0">{{ auth()->user()->username }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong>
                        <p class="mb-0">{{ auth()->user()->full_name }}</p>
                    </div>

                    @if(auth()->user()->birth_date)
                        <div class="col-md-6 mb-3">
                            <strong>Birth Date:</strong>
                            <p class="mb-0">{{ auth()->user()->birth_date->format('M d, Y') }}</p>
                        </div>
                    @endif

                    @if(auth()->user()->gender)
                        <div class="col-md-6 mb-3">
                            <strong>Gender:</strong>
                            <p class="mb-0">{{ ucfirst(auth()->user()->gender) }}</p>
                        </div>
                    @endif
                </div>

                @if(auth()->user()->skills)
                    <div class="mb-3">
                        <strong>Skills:</strong>
                        <div class="mt-2">
                            @foreach(auth()->user()->skills as $skill)
                                <span class="badge bg-secondary me-1 mb-1">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Account Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Account Status</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ auth()->user()->status === 'active' ? 'success' : 'secondary' }} ms-2">
                            {{ ucfirst(auth()->user()->status ?? 'active') }}
                        </span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Role:</strong>
                        <span class="badge bg-primary ms-2">
                            {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                        </span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Member Since:</strong>
                        <p class="mb-0">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                    </div>

                    @if(auth()->user()->current_task_status)
                        <div class="col-md-6 mb-3">
                            <strong>Current Task Status:</strong>
                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', auth()->user()->current_task_status)) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Activity Summary</h5>
            </div>
            <div class="card-body">
                @php
                    $assignments = \App\Models\CardAssignment::where('user_id', auth()->user()->user_id)->count();
                    $completedCards = \App\Models\CardAssignment::where('user_id', auth()->user()->user_id)
                        ->where('assignment_status', 'completed')->count();
                    $totalTimeLogged = \App\Models\TimeLog::where('user_id', auth()->user()->user_id)
                        ->sum('duration_minutes');
                @endphp

                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-primary">{{ $assignments }}</h4>
                            <small class="text-muted">Total Cards Assigned</small>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-success">{{ $completedCards }}</h4>
                            <small class="text-muted">Cards Completed</small>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-info">{{ floor($totalTimeLogged / 60) }}h {{ $totalTimeLogged % 60 }}m</h4>
                            <small class="text-muted">Total Time Logged</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
