@extends('layouts.app')

@section('title', 'Test Results')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<style>
    body {
        background-color: #f9fafb;
    }

    /* Header */

    .header-box {
        position: relative;
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 10px rgba(180,110,76,.08);
        overflow: hidden;
    }

    .header-box::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: #832b00;
    }

    /* Card */

    .card-style {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(180,110,76,.08);
    }

    /* Summary */

    .summary-label {
        font-size: .85rem;
        color: #9a5631;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .summary-value {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1f2937;
    }

    /* Section Divider */

    .border-bottom {
        border-color: #edd7ca !important;
    }

    /* Tables */

    .table thead {
        background: #fcf7f3;
    }

    .table thead th {
        color: #9a5631;
        font-weight: 600;
        border-bottom: 1px solid #edd7ca;
    }

    .table-bordered td,
    .table-bordered th {
        border-color: #edd7ca;
        vertical-align: middle;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #fffdfb;
    }

    .table tbody tr:hover {
        background: #fcf7f3;
    }

    /* Header icon */

    .page-icon {
        color: #832b00;
    }

    /* Secondary Button */

    .btn-secondary {
        background: #f7e3d8;
        border-color: #edd7ca;
        color: #832b00;
        border-radius: 50px;
        transition: .2s;
    }

    .btn-secondary:hover {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

    /* View Button */

    .btn-outline-primary {
        border-color: #b46e4c;
        color: #832b00;
        border-radius: 50px;
        transition: .2s;
    }

    .btn-outline-primary:hover {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

/* ===================================
   DATATABLES
=================================== */

.dataTables_wrapper .dt-buttons {

    display:flex;

    flex-wrap:wrap;

    gap:.5rem;

    margin-bottom:1rem;

}

.dataTables_wrapper .dt-buttons .btn{

    border-radius:999px !important;

}

.dt-button{

    background:#fff !important;

    color:#9a5631 !important;

    border:1px solid #edd7ca !important;

    border-radius:50px !important;

    padding:.45rem 1rem !important;

    font-size:.84rem !important;

    font-weight:600 !important;

    box-shadow:0 2px 8px rgba(180,110,76,.06);

    transition:all .2s ease;

}

.dt-button:hover{

    background:#b46e4c !important;

    color:#fff !important;

    border-color:#b46e4c !important;

    box-shadow:0 4px 12px rgba(180,110,76,.15);

}

    /* Search */

    .dataTables_filter input {
        border-radius: .6rem !important;
        border: 1px solid #d9d9d9 !important;
        padding: .35rem .7rem !important;
    }

    .dataTables_filter input:focus {
        border-color: #b46e4c !important;
        box-shadow: 0 0 0 .2rem rgba(180,110,76,.15) !important;
    }

    @media (max-width:768px) {
        .dataTables_wrapper .dt-buttons {
            justify-content: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header Box: Title + Back Button -->
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-semibold text-dark mb-0">
                <i class="bi bi-clipboard-data page-icon me-2"></i>
                Test Results
            </h5>
            <small class="text-muted">
                View student performance and response statistics.
            </small>
        </div>
        <a href="{{ route('mock-tests.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i>
            <span class="d-none d-md-inline ms-1">Back to All Tests</span>
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
        <div class="border-bottom pb-2 mb-3">
            <h5 class="fw-semibold mb-1">
                <i class="bi bi-bar-chart-line me-2" style="color:#832b00;"></i>    
                Student Performance
            </h5>
            <small class="text-muted">
                Individual student scores and response sheets.
            </small>
        </div>
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

                    {
                        extend: 'copy',
                        text: '<i class="bi bi-clipboard me-1"></i> Copy',
                        className: 'dt-export-btn buttons-copy'
                    },

                    {
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                        className: 'dt-export-btn buttons-excel'
                    },

                    {
                        extend: 'pdf',
                        text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
                        className: 'dt-export-btn buttons-pdf'
                    },

                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer me-1"></i> Print',
                        className: 'dt-export-btn buttons-print'
                    }

                ]
            });
        }
    });
</script>
@endsection
