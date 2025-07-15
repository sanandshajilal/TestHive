@extends('layouts.app')

@section('title', 'Mock Test Results')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<style>
    body {
        background-color: #f9fafb;
    }

    .header-box {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
    }

    .card-style {
        border-radius: 1rem;
        background: #fff;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .summary-value {
        font-weight: 600;
        color: #212529;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 1rem;
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        border-radius: 50px !important;
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
    }

    @media (max-width: 768px) {
        .dataTables_wrapper .dt-buttons {
            flex-wrap: wrap;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header Box: Title + Back Button -->
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-semibold text-dark mb-0">📝 Test Summary</h5>
        <a href="{{ route('mock-tests.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Mock Tests
        </a>
    </div>

    <!-- Test Info -->
    <div class="card-style mb-4">
        <div class="row gy-3">
            <div class="col-md-4">
                <div class="summary-label">Test Name</div>
                <div class="summary-value">{{ $mockTest->title }}</div>
            </div>
            <div class="col-md-4">
                <div class="summary-label">Paper</div>
                <div class="summary-value">{{ $mockTest->paper->name }}</div>
            </div>
            <div class="col-md-4">
                <div class="summary-label">Total Questions</div>
                <div class="summary-value">{{ $mockTest->questions->count() }}</div>
            </div>
            <div class="col-md-4">
                <div class="summary-label">Duration</div>
                <div class="summary-value">{{ $mockTest->duration_minutes }} minutes</div>
            </div>
            <div class="col-md-4">
                <div class="summary-label">Students Attempted</div>
                <div class="summary-value">{{ $attempts->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Student Performance Table -->
    <div class="card-style">
        <h5 class="fw-semibold mb-3 text-primary">📊 Student Performance</h5>
        <div class="table-responsive">
            <table id="resultsTable" class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Student Name</th>
                        <th>Institute</th>
                        <th>Batch</th>
                        <th>Correct</th>
                        <th>Wrong</th>
                        <th>Not Attempted</th>
                        <th>Total Marks</th>
                        <th>Response</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            <td>{{ $attempt->student_name }}</td>
                            <td>{{ $attempt->institute->name ?? '-' }}</td>
                            <td>{{ $attempt->batch->name ?? '-' }}</td>
                            <td>{{ $attempt->correct_count ?? '-' }}</td>
                            <td>{{ $attempt->wrong_count ?? '-' }}</td>
                            <td>{{ $attempt->not_attempted ?? '-' }}</td>
                            <td>{{ $attempt->total_marks ?? '-' }}</td>
                            <td>
                                <a href="{{ route('response.show', $attempt->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No students have attempted this test.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function () {
        let hasData = {{ $attempts->count() > 0 ? 'true' : 'false' }};
        if (hasData) {
            $('#resultsTable').DataTable({
                dom: 'Bfrtip',
                pageLength: 10,
                buttons: [
                    { extend: 'copy', text: 'Copy', className: 'btn btn-sm btn-outline-secondary' },
                    { extend: 'excel', text: 'Excel', className: 'btn btn-sm btn-outline-success' },
                    { extend: 'pdf', text: 'PDF', className: 'btn btn-sm btn-outline-danger' },
                    { extend: 'print', text: 'Print', className: 'btn btn-sm btn-outline-dark' }
                ]
            });
        }
    });
</script>
@endsection
