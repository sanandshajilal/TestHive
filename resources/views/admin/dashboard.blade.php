@extends('layouts.app')

@section('content')
<style>

    body {
        background-color: #f9fafb;
    }

    /* Cards */

    .card {
        box-shadow:
            0 0 0 1px rgba(0,0,0,.03),
            0 10px 25px rgba(180,110,76,.08) !important;
    }

    .brand-top-card {
    position: relative;
    overflow: hidden;
    }

    .brand-top-card::before {
        content: "";
        display: block;
        height: 6px;
        background: #832b00;
    }


    /* Quick Action Cards */

    .quick-action-card {
        transition: .2s;
        border: 1px solid rgba(180,110,76,.08) !important;
    }

    .quick-action-card:hover {
        box-shadow: 0 0 12px rgba(0,0,0,.08);
        transform: translateY(-2px);
        border-color: rgba(180,110,76,.25) !important;
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
        margin-bottom: .5rem;
        color: #b46e4c;
    }

    .quick-action-card:hover .quick-action-icon {
        color: #832b00;
    }

    /* Dashboard Stat Icons */

    .stat-icon {
        font-size: 1.8rem;
        color: #b46e4c;
        margin-bottom: .25rem;
        transition: .2s;
    }

    /* Tables */

    .clickable-row {
        cursor: pointer;
    }

    .clickable-row td {
        transition: background-color .2s ease;
    }

    .clickable-row:hover td {
        background-color: #fdf6f1 !important;
    }

    /* Card Headers */

    .card-header {
        font-size: 1rem;
        font-weight: 700;
    }

    .card-header i{
            color:#832b00 !important;
        }

    /* KPI Cards */

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

    /* Lists */

    .dashboard-list-item {
        border-bottom: 1px solid #eef2f7;
        padding: 12px 0;
    }

    /* Dashboard Stat Cards */

    .dashboard-stat-card {
        cursor: pointer;
        transition: all .25s ease;
    }

    .dashboard-stat-card:hover {
        transform: translateY(-4px);
        box-shadow:
            0 10px 25px rgba(180,110,76,.12) !important;
    }

    .dashboard-stat-card:hover .card-arrow {
        transform: translateX(4px);
        color: #832b00;
    }

    .dashboard-stat-card:hover .stat-icon {
        color: #832b00;
    }

    .dashboard-stat-card:active {
        transform: scale(.98);
    }

    .card-arrow {
        color: #adb5bd;
        font-size: 1.1rem;
        transition: .25s;
    }

    /* Quick Actions */

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
        background: #fdf6f1;
        color: #832b00;
        padding-left: 1.4rem;
    }

   .active-tests-body{
        max-height:220px;
        overflow-y:auto;
        padding-right:6px;
    }

    .upcoming-test-card{
        display:flex;
        align-items:flex-start;
        gap:12px;
        padding:12px;
        border:1px solid #edd7ca;
        border-radius:16px;
        margin-bottom:10px;
        background:#fff;
        transition:.25s;
    }

    .upcoming-test-card:hover{
        border-color:#d8b39f;
        box-shadow:0 6px 16px rgba(180,110,76,.10);
    }



    .date-card i{
        color:#b46e4c !important;
         font-size:1.4rem !important;
    }

    .date-card small{
        color:#832b00 !important;
        font-size:.72rem;
    }

    .progress{
        background:#f3ece8;
        border-radius:999px;
        height:5px !important;
    }



    .progress-bar{
        background:#b46e4c !important;
    }

    .upcoming-test-card .fw-semibold.fs-6{
        font-size:1.08rem !important;
    }

    /* Scrollbar */

    .active-tests-body::-webkit-scrollbar{
        width:6px;
    }

    .active-tests-body::-webkit-scrollbar-track{
        background:#faf6f3;
    }

    .active-tests-body::-webkit-scrollbar-thumb{
        background:#d4b09b;
        border-radius:20px;
    }

    .active-tests-body::-webkit-scrollbar-thumb:hover{
        background:#b46e4c;
    }
    /* Date Block */

    .date-card {
        width: 52px;
        min-width: 56px;
        height:52px;  
        text-align: center;
        border-radius: 14px;
        background: #f7e3d8;
        padding: 6px 0;
    }

    .date-day {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
        color: #832b00;
    }

    .date-month {
        margin-top: 6px;
        font-size: .75rem;
        font-weight: 700;
        color: #9a5631;
        letter-spacing: .5px;
    }


    .kpi-icon {
        position: absolute;
        top: 16px;
        right: 16px;
        font-size: 2rem;
        color: #b46e4c;
        opacity: 0.50;
        transition: all .25s ease;
    }

    .kpi-card:hover .kpi-icon {
        opacity: 1;
        transform: scale(1.05);
    }

    .kpi-value {
        font-size: 1.9rem;
        font-weight: 700;
        line-height: 1.15;

        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;

        min-height: 4.3rem;
    }
    

</style>

<div class="container py-4">
<div class="card border-0 rounded-4 shadow-sm mb-4 brand-top-card">
    <div class="card-body d-flex justify-content-between align-items-center">
        
        <div>
            <h3 class="mb-1 fw-bold">
                Welcome back, {{ auth()->user()->name }} 
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
        <div class="card-body position-relative">

            <i class="bi bi-ui-checks-grid kpi-icon"></i>

            <small class="text-muted d-block mb-2">
                Latest Test
            </small>

            <div
                class="kpi-value"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="{{ $latestMockTest?->title }}"
            >
                {{ Str::limit($latestMockTest?->title, 40) }}
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
        <div class="card-body position-relative">

            <i class="bi bi-pencil-square kpi-icon"></i>

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
        <div class="card-body position-relative">

            <i class="bi bi-graph-up-arrow kpi-icon"></i>

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
        <div class="card-body position-relative">

            <i class="bi bi-trophy kpi-icon"></i>

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
                ['title' => 'Students', 'count' => $studentCount ?? 0, 'icon' => 'bi-mortarboard', 'url' => route('students.index')],
                ['title' => 'Papers', 'count' => $paperCount ?? 0, 'icon' => 'bi-journal-text', 'url' => route('papers.index')],
                ['title' => 'Questions', 'count' => $questionCount ?? 0, 'icon' => 'bi-question-circle', 'url' => route('questions.index')],
                ['title' => 'Mock Tests', 'count' => $mockTestCount ?? 0, 'icon' => 'bi-ui-checks-grid', 'url' => route('mock-tests.index')],
              
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
                <i class="bi bi-plus-circle me-2" style="color:#b46e4c;"></i>
                Create New Test
                <i class="bi bi-chevron-right ms-auto text-muted"></i>
            </a>

            <a href="/admin/questions/create"
               class="dashboard-action-item text-decoration-none">
                <i class="bi bi-plus-square me-2" style="color:#9a5631;"></i>
                Add New Question
                <i class="bi bi-chevron-right ms-auto text-muted"></i>
            </a>

            <a href="/admin/questions"
               class="dashboard-action-item text-decoration-none">
                <i class="bi bi-journal-text me-2" style="color:#832b00;"></i>
                View All Questions
                <i class="bi bi-chevron-right ms-auto text-muted"></i>
            </a>

            <a href="{{ route('admin.reports.index') }}"
               class="dashboard-action-item text-decoration-none">
                <i class="bi bi-bar-chart-line me-2" style="color:#b46e4c;"></i>
                View Reports
                <i class="bi bi-chevron-right ms-auto text-muted"></i>
            </a>

        </div>

    </div>

</div>

{{-- Active Tests --}}
<div class="col-lg-8">

    <div class="card border-0 rounded-4 shadow-sm h-100">

        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

            <div class="fw-semibold">
                <i class="bi bi-play-circle-fill text-success me-2"></i>
                Active Tests

                <span class="badge rounded-pill"
                    style="background:#fcf7f3;color:#832b00;border:1px solid #edd7ca;">
                    {{ $activeTests->count() }}
                </span>

            </div>

            <a href="{{ route('mock-tests.index') }}"
               class="text-muted text-decoration-none">
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

        <div class="card-body pt-2 active-tests-body">

            @forelse($activeTests as $test)

                <div class="upcoming-test-card">

                    {{-- LIVE --}}
                    <div class="date-card d-flex flex-column justify-content-center align-items-center">

                        <i class="bi bi-play-fill fs-4"></i>

                        <small class="fw-semibold">
                           ACTIVE
                        </small>

                    </div>

                    {{-- Details --}}
                    <div class="flex-grow-1 ms-3">

                        <div class="d-flex justify-content-between align-items-start flex-wrap">

                            <div>

                                <div class="fw-semibold fs-6">

                                    {{ $test->title }}

                                </div>

                                <div class="small text-muted mt-1">

                                    {{ $test->paper?->name }}
                                    •

                                    {{ $test->duration_minutes }} mins

                                    @if($test->batches->first())

                                        •

                                        {{ $test->batches->first()->name }}

                                        @if($test->batches->first()->institute)

                                            • {{ $test->batches->first()->institute->name }}

                                        @endif

                                    @endif

                                </div>

                            </div>

                            <span class="badge rounded-pill"
                                style="background:#f7e3d8;color:#832b00;border:1px solid #e5d2c8;">
                                Available
                            </span>

                        </div>

                        {{-- Availability --}}
                        <div class="small text-muted mt-1">

                            <i class="bi bi-calendar-event me-2" style="color:#832b00;"></i>

                            {{ \Carbon\Carbon::parse($test->start_time)->format('d M Y, h:i A') }}

                            <i class="bi bi-arrow-right mx-2"></i>

                            {{ \Carbon\Carbon::parse($test->end_time)->format('d M Y, h:i A') }}

                        </div>

                        {{-- Progress --}}
                        <div class="mt-2">

                            <div class="d-flex justify-content-between small mb-1">

                                <span>

                                    <i class="bi bi-people me-1"></i>

                                    Students Completed

                                </span>

                                <strong>

                                    {{ $test->completed_students }} / {{ $test->total_students }}

                                </strong>

                            </div>

                            <div class="progress" style="height:7px;">

                                <div class="progress-bar bg-success"
                                     role="progressbar"
                                     style="width: {{ $test->completion_percentage }}%;">
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted py-5">

                    <i class="bi bi-play-circle fs-2 d-block mb-2"></i>

                    No active tests at the moment.

                </div>

            @endforelse

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

        <div class="border-top pt-3 mt-4">
            <div class="text-center small text-muted">
                ACCAPrep with Malasri
                <span class="mx-2">•</span>
                v1.3.4
                <span class="mx-2">•</span>
                Developed & Maintained by <strong>Sanand S</strong>
            </div>
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
