@extends('member.layout')

@section('title', 'Member Panel')
@section('page-title', 'Welcome to Member Panel')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-person-workspace fs-1 text-primary mb-3"></i>
                <h3>Welcome to Member Panel</h3>
                <p class="text-muted mb-4">
                    This is your personal workspace where you can manage your assigned cards, track your time, and monitor your progress.
                </p>

                <div class="row justify-content-center">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('member.dashboard') }}" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('member.my-cards') }}" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-kanban me-2"></i>My Tasks
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('member.time-logs') }}" class="btn btn-info btn-lg w-100">
                            <i class="bi bi-clock-history me-2"></i>Time Logs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
