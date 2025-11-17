@extends('member.layout')

@section('title', 'Time Logs')
@section('page-title', 'Time Logs')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <!-- Date Filter -->
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('member.time-logs') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Select Date</label>
                        <input type="date" class="form-control" id="date" name="date"
                               value="{{ $date }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('member.time-logs') }}" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-arrow-clockwise"></i> Today
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Daily Summary -->
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4>{{ floor($totalDuration / 60) }}h {{ $totalDuration % 60 }}m</h4>
                <p class="mb-0">Total Time - {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Time Logs List -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Time Entries</h5>
            </div>
            <div class="card-body">
                @if($timeLogs->count() > 0)
                    @foreach($timeLogs as $log)
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('member.card-detail', $log->card_id) }}"
                                       class="text-decoration-none">
                                        {{ $log->card->card_title }}
                                    </a>
                                </h6>

                                @if($log->card->board)
                                    <small class="text-muted">
                                        <i class="bi bi-diagram-3 me-1"></i>{{ $log->card->board->board_name }}
                                    </small>
                                @endif

                                <div class="mt-1">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $log->start_time->format('H:i') }} -
                                        {{ $log->end_time ? $log->end_time->format('H:i') : 'Running' }}
                                    </small>
                                </div>

                                @if($log->description)
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-chat-text me-1"></i>{{ $log->description }}
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <div class="text-end">
                                <h5 class="mb-0 text-primary">
                                    {{ floor($log->duration_minutes / 60) }}h {{ $log->duration_minutes % 60 }}m
                                </h5>
                                @if(!$log->end_time)
                                    <small class="text-danger">
                                        <i class="bi bi-play-circle"></i> Running
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-clock fs-1 mb-3"></i>
                        <h4>No Time Logs</h4>
                        <p>No time entries found for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}.</p>
                        <a href="{{ route('member.my-cards') }}" class="btn btn-primary">
                            <i class="bi bi-play-circle"></i> Start Working
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Weekly Summary -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Weekly Summary</h5>
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($date)->startOfWeek()->format('M d') }} -
                    {{ \Carbon\Carbon::parse($date)->endOfWeek()->format('M d, Y') }}
                </small>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h4 class="text-primary">{{ floor($weeklyTotal / 60) }}h {{ $weeklyTotal % 60 }}m</h4>
                    <small class="text-muted">Total This Week</small>
                </div>

                <hr>

                @foreach($dailySummary as $day)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="{{ $day['date']->format('Y-m-d') === $date ? 'fw-bold' : 'text-muted' }}">
                            {{ $day['date']->format('D, M d') }}
                        </small>
                        <small class="{{ $day['date']->format('Y-m-d') === $date ? 'fw-bold text-primary' : 'text-muted' }}">
                            {{ $day['total_hours'] }}h
                        </small>
                    </div>

                    @if($day['total_minutes'] > 0)
                        <div class="progress mb-2" style="height: 4px;">
                            @php
                                $maxDaily = max(array_column($dailySummary, 'total_minutes'));
                                $percentage = $maxDaily > 0 ? ($day['total_minutes'] / $maxDaily) * 100 : 0;
                            @endphp
                            <div class="progress-bar {{ $day['date']->format('Y-m-d') === $date ? 'bg-primary' : 'bg-light' }}"
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Stats</h6>
            </div>
            <div class="card-body">
                @php
                    $avgDaily = $weeklyTotal > 0 ? $weeklyTotal / 7 : 0;
                    $workingDays = collect($dailySummary)->where('total_minutes', '>', 0)->count();
                    $avgWorkingDay = $workingDays > 0 ? $weeklyTotal / $workingDays : 0;
                @endphp

                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h6 class="text-primary">{{ floor($avgDaily / 60) }}h {{ round($avgDaily % 60) }}m</h6>
                        <small class="text-muted">Avg/Day</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h6 class="text-success">{{ $workingDays }}</h6>
                        <small class="text-muted">Work Days</small>
                    </div>
                </div>

                @if($workingDays > 0)
                    <div class="text-center">
                        <h6 class="text-info">{{ floor($avgWorkingDay / 60) }}h {{ round($avgWorkingDay % 60) }}m</h6>
                        <small class="text-muted">Avg/Working Day</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Cards Worked On -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Recent Cards</h6>
            </div>
            <div class="card-body">
                @php
                    $recentCards = $timeLogs->groupBy('card_id')->take(5);
                @endphp

                @if($recentCards->count() > 0)
                    @foreach($recentCards as $cardId => $cardLogs)
                        @php
                            $card = $cardLogs->first()->card;
                            $cardTime = $cardLogs->sum('duration_minutes');
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="flex-grow-1">
                                <a href="{{ route('member.card-detail', $cardId) }}"
                                   class="text-decoration-none small">
                                    {{ Str::limit($card->card_title, 30) }}
                                </a>
                            </div>
                            <small class="text-muted">
                                {{ floor($cardTime / 60) }}h {{ $cardTime % 60 }}m
                            </small>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-3 text-muted">
                        <small>No cards worked on today</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit form when date changes
document.getElementById('date').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush
