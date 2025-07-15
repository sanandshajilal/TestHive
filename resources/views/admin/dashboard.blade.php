@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f9fafb;
    }

    .quick-action-card {
        transition: 0.2s;
    }

    .quick-action-card:hover {
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .quick-action-card .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100px;
    }

    .quick-action-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #0d6efd;
    }

    .stat-icon {
        font-size: 1.8rem;
        color: #ffc107;
        margin-bottom: 0.25rem;
    }
      .clickable-row {
        cursor: pointer;
    }

    .clickable-row td {
        transition: background-color 0.2s ease-in-out;
    }

    .clickable-row:hover td {
        background-color: #e9f2ff !important; /* soft blue */
    }
</style>

<div class="container py-4">
    <h3 class="mb-4 fw-semibold text-dark">Dashboard</h3>

    {{-- Quick Stats --}}
    <div class="row g-4 mb-4">
        @php
            $cards = [
                ['title' => 'Institutes', 'count' => $instituteCount ?? 0, 'icon' => 'bi-building'],
                ['title' => 'Batches', 'count' => $batchCount ?? 0, 'icon' => 'bi-people'],
                ['title' => 'Papers', 'count' => $paperCount ?? 0, 'icon' => 'bi-journal-text'],
                ['title' => 'Questions', 'count' => $questionCount ?? 0, 'icon' => 'bi-question-circle'],
                ['title' => 'Mock Tests', 'count' => $mockTestCount ?? 0, 'icon' => 'bi-ui-checks-grid'],
                ['title' => 'Student Responses', 'count' => $responseCount ?? 0, 'icon' => 'bi-bar-chart-line'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 bg-white">
                    <div class="card-body text-center">
                        <div class="stat-icon">
                            <i class="bi {{ $card['icon'] }}"></i>
                        </div>
                        <h6 class="text-muted mb-1">{{ $card['title'] }}</h6>
                        <h4 class="fw-bold text-dark">{{ $card['count'] }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Quick Actions (Card-based) --}}
    <div class="mb-5">
        <h6 class="mb-3 fw-semibold text-dark">Quick Actions</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <a href="/admin/mock-tests/create" class="text-decoration-none">
                    <div class="card quick-action-card border-0 rounded-4 shadow-sm bg-white">
                        <div class="card-body">
                            <div class="quick-action-icon">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <div class="fw-semibold text-dark">Create Mock Test</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="/admin/questions/create" class="text-decoration-none">
                    <div class="card quick-action-card border-0 rounded-4 shadow-sm bg-white">
                        <div class="card-body">
                            <div class="quick-action-icon">
                                <i class="bi bi-plus-square"></i>
                            </div>
                            <div class="fw-semibold text-dark">Add New Question</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.reports.index') }}" class="text-decoration-none">
                    <div class="card quick-action-card border-0 rounded-4 shadow-sm bg-white">
                        <div class="card-body">
                            <div class="quick-action-icon">
                                <i class="bi bi-bar-chart-line"></i>
                            </div>
                            <div class="fw-semibold text-dark">View Reports</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-header bg-white fw-semibold border-bottom">Recent Mock Tests</div>
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

        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 bg-white">
                <div class="card-header bg-white fw-semibold border-bottom">Recent Student Responses</div>
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
@endsection
