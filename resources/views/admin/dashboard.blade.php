@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f9fafb;
    }

    .card {
        box-shadow:
            0 0 0 1px rgba(0,0,0,.03),
            0 10px 25px rgba(78,115,223,.08) !important;
    }

    .quick-action-card {
        transition: 0.2s;
        border: 1px solid rgba(78,115,223,.08) !important;
    }

    .quick-action-card:hover {
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
        border-color: rgba(78,115,223,.25) !important;
    }

    .quick-action-card .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 80px;
    }

    .quick-action-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #0d6efd;
    }
    .quick-action-card:hover .quick-action-icon {
        color: #4e73df;
    }

    .stat-icon {
        font-size: 1.8rem;
        color: #ffc107;
        margin-bottom: 0.25rem;
    }
      .clickable-row {
        cursor: pointer;
    }

    .card-header {
    font-size: 1rem;
    font-weight: 700;
}

    .clickable-row td {
        transition: background-color 0.2s ease-in-out;
    }

    .clickable-row:hover td {
        background-color: #e9f2ff !important; /* soft blue */
    }

    .kpi-card {
        border: none;
        border-radius: 18px;
        transition: .2s;
        min-height: 120px;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
    }

    .kpi-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
    }

    .kpi-label {
        color: #6c757d;
        font-size: .9rem;
    }

    .dashboard-list-item {
        border-bottom: 1px solid #eef2f7;
        padding: 12px 0;
    }

    .dashboard-stat-card {
    cursor: pointer;
    transition: all .25s ease;
}

.dashboard-stat-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 10px 25px rgba(78,115,223,.12) !important;
}

.dashboard-stat-card:hover .card-arrow {
    transform: translateX(4px);
    color: #4e73df;
}

.dashboard-stat-card:hover .stat-icon {
    color: #4e73df;
}

.card-arrow {
    color: #adb5bd;
    font-size: 1.1rem;
    transition: all .25s ease;
}

.dashboard-stat-card:active {
    transform: scale(0.98);
}

.dashboard-action-item {
display: flex;
align-items: center;
padding: 1rem 1.25rem;
color: #212529;
border-bottom: 1px solid #f1f3f5;
transition: all .2s ease;
}

.dashboard-action-item:last-child {
border-bottom: none;
}

.dashboard-action-item:hover {
background: #f8fafc;
color: #4e73df;
padding-left: 1.4rem;
}

.upcoming-test-item {
padding: 1rem 1.25rem;
border-bottom: 1px solid #f1f3f5;
}

.upcoming-test-item:last-child {
border-bottom: none;
}

.upcoming-test-card {
    display: flex;
    align-items: center;
    border: 1px solid #edf2f7;
    border-radius: 16px;
    padding: 14px;
    margin-bottom: 12px;
    transition: all .2s ease;
}

.upcoming-test-card:last-child {
    margin-bottom: 0;
}

.upcoming-test-card:hover {
    background: #fafcff;
    border-color: #dbe7ff;
}

.date-card {
    width: 72px;
    min-width: 72px;
    text-align: center;
    border-radius: 14px;
    background: #f8f5e9;
    padding: 8px 0;
}

.date-day {
    font-size: 1.8rem;
    font-weight: 700;
    line-height: 1;
    color: #111827;
}

.date-month {
    margin-top: 6px;
    font-size: .75rem;
    font-weight: 700;
    color: #6b7280;
    letter-spacing: .5px;
}

    
</style>

<div class="container py-4">
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        
        <div>
            <h3 class="mb-1 fw-bold">
                Welcome back, {{ auth()->user()->name }} 👋
            </h3>
            <p class="text-muted mb-0">
               Monitor tests, manage content, and track student progress from your dashboard.
            </p>
        </div>

       <div class="text-end d-none d-md-block">
            <div class="small text-muted">
                <i class="bi bi-clock-history me-1"></i>
                Last login
            </div>
            <div style="font-size: 0.85rem; color:#6c757d;">
                Today, 11:11 AM
            </div>
        </div>

    </div>

</div>

<div class="row g-3 mb-4">


<div class="col-md-3">
    <div class="card border-0 rounded-4 kpi-card h-100">
        <div class="card-body">
            <small class="text-muted d-block mb-2">
                Latest Test
            </small>

            <div
                    class="kpi-value"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    title="{{ $latestMockTest?->title }}"
                >
                    {{ Str::limit($latestMockTest?->title, 12) }}
                </div>

            @if($latestMockTest)
                <div class="small text-muted mt-1">
                    {{ $latestMockTest->paper?->name }}
                    •
                    {{ $latestMockTest->duration_minutes }} mins
                </div>
            @endif
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card border-0 rounded-4 kpi-card h-100">
        <div class="card-body">
            <small class="text-muted d-block mb-2">
                Attempts
            </small>

            <div class="kpi-value">
                {{ $latestAttempts }}
            </div>

            <div class="small text-muted">
                Completed submissions
            </div>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card border-0 rounded-4 kpi-card h-100">
        <div class="card-body">
            <small class="text-muted d-block mb-2">
                Average Score
            </small>

            <div class="kpi-value">
                {{ $latestAverageScore }}%
            </div>

            <div class="small text-muted">
                Latest test average
            </div>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card border-0 rounded-4 kpi-card h-100">
        <div class="card-body">
            <small class="text-muted d-block mb-2">
                Highest Score
            </small>

            <div class="kpi-value">
                {{ $latestHighestScore }}%
            </div>

            <div class="small text-muted">
                Best performer
            </div>
        </div>
    </div>
</div>


</div>


        
    {{-- Quick Stats --}}
    <div class="row g-4 mb-4">
        @php
            $cards = [
                ['title' => 'Institutes', 'count' => $instituteCount ?? 0, 'icon' => 'bi-building', 'url' => route('institutes.index')],
                ['title' => 'Batches', 'count' => $batchCount ?? 0, 'icon' => 'bi-people', 'url' => route('batches.index')],
                ['title' => 'Papers', 'count' => $paperCount ?? 0, 'icon' => 'bi-journal-text', 'url' => route('papers.index')],
                ['title' => 'Questions', 'count' => $questionCount ?? 0, 'icon' => 'bi-question-circle', 'url' => route('questions.index')],
                ['title' => 'Mock Tests', 'count' => $mockTestCount ?? 0, 'icon' => 'bi-ui-checks-grid', 'url' => route('mock-tests.index')],
                ['title' => 'Responses', 'count' => $responseCount ?? 0, 'icon' => 'bi-bar-chart-line', 'url' => route('mock-tests.index')],
            ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-lg-2 col-md-4 col-6">
            <a href="{{ $card['url'] }}" class="text-decoration-none">
                <div class="card dashboard-stat-card shadow-sm border-0 rounded-4 bg-white">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="d-flex align-items-center">

                                <div class="stat-icon me-3">
                                    <i class="bi {{ $card['icon'] }}"></i>
                                </div>

                                <div>
                                    <div class="text-muted small">{{ $card['title'] }}</div>
                                    <div class="fw-bold fs-5 text-dark">
                                        {{ $card['count'] }}
                                    </div>
                                </div>

                            </div>

                            <div class="card-arrow">
                                <i class="bi bi-arrow-right-circle"></i>
                            </div>

                        </div>

                    </div>

                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">

{{-- Quick Actions --}}
<div class="col-lg-4">

    <div class="card border-0 rounded-4 shadow-sm h-100">

        <div class="card-header bg-white border-0 fw-semibold">
            <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
            Quick Actions
        </div>

        <div class="card-body p-0">

            <a href="/admin/mock-tests/create"
               class="dashboard-action-item text-decoration-none">
                <i class="bi bi-plus-circle text-primary me-2"></i>
                Create New Test
                <i class="bi bi-chevron-right ms-auto text-muted"></i>
            </a>

            <a href="/admin/questions/create"
               class="dashboard-action-item text-decoration-none">
                <i class="bi bi-plus-square text-success me-2"></i>
                Add New Question
                <i class="bi bi-chevron-right ms-auto text-muted"></i>
            </a>

            <a href="/admin/questions"
               class="dashboard-action-item text-decoration-none">
                <i class="bi bi-journal-text text-warning me-2"></i>
                View All Questions
                <i class="bi bi-chevron-right ms-auto text-muted"></i>
            </a>

            <a href="{{ route('admin.reports.index') }}"
               class="dashboard-action-item text-decoration-none">
                <i class="bi bi-bar-chart-line text-info me-2"></i>
                View Reports
                <i class="bi bi-chevron-right ms-auto text-muted"></i>
            </a>

        </div>

    </div>

</div>

{{-- Upcoming Tests --}}
<div class="col-lg-8">

    <div class="card border-0 rounded-4 shadow-sm h-100">

        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

            <div class="fw-semibold">
                <i class="bi bi-calendar-event text-primary me-2"></i>
                Upcoming Tests
            </div>

          <a href="{{ route('mock-tests.index') }}"
            class="text-muted text-decoration-none">
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

        <div class="card-body pt-1">

            @forelse($upcomingTests ?? [] as $test)

                <div class="upcoming-test-card">

                    {{-- Date Block --}}
                    <div class="date-card">

                        <div class="date-day">
                            {{ \Carbon\Carbon::parse($test->start_time)->format('d') }}
                        </div>

                        <div class="date-month">
                            {{ strtoupper(\Carbon\Carbon::parse($test->start_time)->format('M')) }}
                        </div>

                    </div>

                    {{-- Test Details --}}
                    <div class="flex-grow-1 ms-3">

                        <div class="fw-semibold fs-6">
                            {{ $test->title }}
                        </div>

                       <div class="small text-muted mt-1">
                            {{ $test->paper?->name }}
                            •
                            {{ $test->duration_minutes }} mins
                        </div>

                        @if($test->batches->first())
                            <div class="small text-muted">

                                {{ $test->batches->first()->name }}

                                @if($test->batches->first()->institute)
                                    • {{ $test->batches->first()->institute->name }}
                                @endif

                            </div>
                        @endif

                    </div>

                    {{-- Schedule --}}
                    <div class="text-end">

                        @php
                            $testDate = \Carbon\Carbon::parse($test->start_time);
                        @endphp

                        <div class="fw-semibold small">
                            @if($testDate->isTomorrow())
                                Tomorrow
                            @elseif($testDate->isToday())
                                Today
                            @else
                                {{ $testDate->format('d M Y') }}
                            @endif
                        </div>

                        <div class="small text-muted">
                            {{ $testDate->format('h:i A') }}
                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted py-5">
                    <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                    No upcoming tests scheduled.
                </div>

            @endforelse

        </div>

    </div>
</div>
</div>

    {{-- Recent Activity --}}
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-clipboard-check text-success me-2"></i>
                    Recent Tests
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light small text-muted">
                            <tr>
                                <th>Name</th>
                                <th>Paper</th>
                                <th>Duration</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentMockTests ?? [] as $test)
                                <tr class="clickable-row" title="View Test Results" onclick="window.location.href='{{ route('mock-tests.results', $test->id) }}'">
                                    <td>{{ $test->title }}</td>
                                    <td>{{ $test->paper->name }}</td>
                                    <td>{{ $test->duration_minutes }} min</td>
                                    <td>{{ $test->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No mock tests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-chat-square-text text-success me-2"></i>
                    Recent Student Responses
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light small text-muted">
                            <tr>
                                <th>Name</th>
                                <th>Test</th>
                                <th>Paper</th>
                                <th>Score</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentResponses ?? [] as $response)
                                <tr class="clickable-row" title="View Response Sheet"
                                    onclick="window.location.href='{{ route('response.show', $response->id) }}'">
                                    <td>{{ $response->student_name }}</td>
                                    <td>{{ $response->mockTest->title }}</td>
                                    <td>{{ $response->mockTest->paper->name }}</td>
                                    <td>{{ $response->total_marks }}</td>
                                    <td>{{ $response->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No responses found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="border-top pt-2 mt-4">
    <div class="text-center text-muted small">
        ACCAPrep with Malasri v1.1 · Developed & Maintained by Sanand S
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');

    tooltipTriggerList.forEach(function (el) {
        new bootstrap.Tooltip(el, {
            container: 'body'
        });
    });

});
</script>
