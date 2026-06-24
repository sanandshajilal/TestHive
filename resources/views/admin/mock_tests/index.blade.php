@php
    $now = \Carbon\Carbon::now('Asia/Kolkata');
@endphp

@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<style>

body {
    background: #f9fafb;
}

/* ===================================
   HEADER
=================================== */

.header-box {
    position: relative;
    background: #fff;
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

/* ===================================
   MAIN CARD
=================================== */

.card-style {
    background: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(180,110,76,.08);
}

/* ===================================
   SUMMARY CARDS
=================================== */

.summary-card {

    background: #fff;

    border: 1px solid #edd7ca;

    border-radius: 1rem;

    padding: 1rem 1.25rem;

    height: 100%;

    transition: all .25s ease;

    box-shadow: 0 2px 8px rgba(180,110,76,.08);

}

.summary-card:hover {

    border-color: #d7b29d;

    transform: translateY(-2px);

    box-shadow: 0 6px 16px rgba(180,110,76,.12);

}

.summary-label {

    font-size: .8rem;

    color: #9a5631;

    margin-bottom: .35rem;

}

.summary-value {

    font-size: 1.45rem;

    font-weight: 700;

    color: #1f2937;

}

/* ===================================
   BUTTONS
=================================== */

.btn-primary {

    background: #b46e4c;

    border-color: #b46e4c;

}

.btn-primary:hover {

    background: #832b00;

    border-color: #832b00;

}

.btn-soft-info {

    background: #e8f3f3;

    color: #2f6d73;

    border: none;

}

.btn-soft-info:hover {

    background: #2f6d73;

    color: #fff;

}

.btn-soft-warning {

    background: #f7e3d8;

    color: #832b00;

    border: none;

}

.btn-soft-warning:hover {

    background: #b46e4c;

    color: #fff;

}

.btn-soft-danger {

    background: #fdecea;

    color: #c0392b;

    border: none;

}

.btn-soft-danger:hover {

    background: #c0392b;

    color: #fff;

}

.btn-sm.btn-soft-info,
.btn-sm.btn-soft-warning,
.btn-sm.btn-soft-danger {

    width: 32px;

    height: 32px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

}

/* ===================================
   ACTION BUTTONS
=================================== */

.action-buttons {

    display: flex;

    justify-content: center;

    gap: .35rem;

}

/* ===================================
   TABLE
=================================== */

.table-responsive {

    border-radius: .75rem;

    overflow: hidden;

}

.table thead {

    background: #fcf7f3;

}

.table thead th {

    color: #9a5631;

    font-weight: 600;

    border: none;

    padding: .9rem .75rem;

}

#mockTestsTable {

    border-collapse: separate;

    border-spacing: 0 10px;

}

/* ===================================
   ROW CARD STYLE
=================================== */

#mockTestsTable tbody tr {

    background: #fff;

    transition: all .2s ease;

}

#mockTestsTable tbody tr:hover {

    transform: translateY(-2px);

    box-shadow: 0 6px 16px rgba(180,110,76,.08);

}

#mockTestsTable tbody td {

    background: #fff;

    border-top: 1px solid #edd7ca;

    border-bottom: 1px solid #edd7ca;

    border-left: none;

    border-right: none;

    padding: 1rem .9rem;

    vertical-align: middle;

}

#mockTestsTable tbody td + td {

    box-shadow: inset 1px 0 0 rgba(180,110,76,.06);

}

#mockTestsTable tbody td:first-child {

    border-left: 1px solid #edd7ca;

    border-radius: 14px 0 0 14px;

    width: 70px;

    text-align: center;

}

#mockTestsTable tbody td:last-child {

    border-right: 1px solid #edd7ca;

    border-radius: 0 14px 14px 0;

}

#mockTestsTable tbody tr:hover td:first-child {

    border-left: 3px solid #b46e4c;

}

/* ===================================
   TEST NUMBER
=================================== */

.test-index {

    width: 70px;

}

.test-number {

    width: 34px;

    height: 34px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #f7e3d8;

    color: #832b00;

    font-size: .85rem;

    font-weight: 700;

    transition: .2s;

}

#mockTestsTable tbody tr:hover .test-number {

    background: #b46e4c;

    color: #fff;

}

/* ===================================
   TEST DETAILS
=================================== */

.test-name {

    font-weight: 600;

    color: #1f2937;

    font-size: .98rem;

    text-transform: uppercase;

    letter-spacing: .03em;

}

.paper-meta {

    color: #6c757d;

    font-size: .88rem;

    font-weight: 500;

}

.batch-meta {

    color: #495057;

    font-weight: 500;

}

.institute-meta {

    color: #6c757d;

    font-size: .82rem;

}

.access-code {

    font-weight: 700;

    letter-spacing: .06em;

    color: #1f2937;

}

/* ===================================
   BADGES
=================================== */

.badge {

    padding: .45rem .75rem;

    font-weight: 600;

    border-radius: 999px;

}

/* ===================================
   DATATABLES
=================================== */

.dataTables_wrapper .dt-buttons {

    display: flex;

    flex-wrap: wrap;

    gap: .5rem;

    margin-bottom: 1rem;

}

.dataTables_wrapper .dt-buttons .btn {

    border-radius: 999px !important;

}

.dataTables_wrapper .dataTables_filter {

    margin-top: .5rem;

    margin-bottom: 1rem;

}

.dataTables_wrapper .dataTables_filter input {

    border-radius: .75rem !important;

    border: 1px solid #d9d9d9 !important;

    padding: .4rem .8rem !important;

}

.dataTables_wrapper .dataTables_filter input:focus {

    border-color: #b46e4c !important;

    box-shadow: 0 0 0 .2rem rgba(180,110,76,.15) !important;

}

/* ===================================
   ALERT
=================================== */

.alert {

    border-radius: .75rem;

}

.dt-button {

    background: #fff !important;

    color: #9a5631 !important;

    border: 1px solid #edd7ca !important;

    border-radius: 50px !important;

    padding: .45rem 1rem !important;

    font-size: .84rem !important;

    font-weight: 600 !important;

    box-shadow: 0 2px 8px rgba(180,110,76,.06);

    transition: all .2s ease;

}

.dt-button:hover {

    background: #b46e4c !important;

    color: #fff !important;

    border-color: #b46e4c !important;

    box-shadow: 0 4px 12px rgba(180,110,76,.15);

}

/* ===================================
   MOBILE
=================================== */

@media(max-width:768px){

    .action-buttons{

        justify-content:flex-start;

    }

    .dataTables_wrapper .dt-buttons{

        justify-content:flex-start;

    }

}

</style>   
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <div>

            <h4 class="fw-semibold text-dark mb-0">
                <i class="bi bi-clipboard-check me-2" style="color:#832b00;"></i>
                All Tests
            </h4>

            <small class="text-muted">
                Create, manage and monitor mock tests across batches.
            </small>

        </div>
        <a href="{{ route('mock-tests.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle me-1"></i> Create New Test
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-label">Total Tests</div>
                <div class="summary-value">{{ $mockTests->count() }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-label">Upcoming</div>
                <div class="summary-value">
                    {{ $mockTests->where('status','Upcoming')->count() }}
                </div>
            </div>
        </div>

        <div class="col-md-3">
                <div class="summary-card">
                <div class="summary-label">Active</div>
                <div class="summary-value">
                        {{ $mockTests->where('status','Active')->count() }}
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-label">Ended</div>
                <div class="summary-value">
                        {{ $mockTests->where('status','Expired')->count() }}
                </div>
            </div>
        </div>

    </div>

    <div class="card-style">
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="mockTestsTable">
                <thead class="table-light">
                    <tr class="small text-muted">
                         <th class="text-center">#</th>
                         <th class="text-center">Test Name</th>
                         <th class="text-center">Batch</th>
                         <th class="text-center">Scheduled Time</th>
                         <th class="text-center">Status</th>
                         <th class="text-center">Access Code</th>
                         <th class="text-center">Results</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mockTests as $mockTest)
                        <tr>
                             <td class="test-index">
                                <span class="test-number">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <div class="test-name">
                                    {{ $mockTest->title }}
                                </div>
                                <div class="paper-meta">
                                    {{ $mockTest->paper->name ?? 'N/A' }}
                                </div>
                            </td>
                                    <td class="text-center">

                                        @if($mockTest->batches->first())

                                            <div class="batch-meta">

                                                {{ $mockTest->batches->first()->name }}

                                            </div>

                                            <div class="institute-meta">

                                                {{ optional($mockTest->batches->first()->institute)->name ?? '' }}

                                            </div>

                                        @else

                                            -

                                        @endif

                                    </td>
                            <td class="text-center">

                                    {{ \Carbon\Carbon::parse($mockTest->start_time)->format('d M Y, h:i A') }}

                                </td>
                             <td class="text-center">
                                @if($now->between($mockTest->start_time, $mockTest->end_time))
                                    <span class="badge bg-success">Ongoing</span>
                                @elseif(now()->lt($mockTest->start_time))
                                    <span class="badge bg-warning text-dark">Upcoming</span>
                                @else
                                    <span class="badge bg-secondary">Completed</span>
                                @endif
                            </td>
                            <td class="text-center">

                                <span class="access-code">

                                    {{ $mockTest->access_code }}

                                </span>

                            </td>
                            <td class="text-center">
                                <a href="{{ route('mock-tests.results', $mockTest->id) }}"
                                class="btn btn-sm btn-soft-info"
                                title="Results">

                                    <i class="bi bi-bar-chart"></i>

                                </a>
                            </td>
                            <td>

                            <div class="action-buttons">
                                <a href="{{ route('mock-tests.preview', $mockTest->id) }}" class="btn btn-sm btn-soft-info" title="Preview">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('mock-tests.edit', $mockTest->id) }}" class="btn btn-sm btn-soft-warning" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('mock-tests.destroy', $mockTest->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this test?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-soft-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
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


<script>
    $(document).ready(function () {

        $('#mockTestsTable').DataTable({

            dom: 'Bfrtip',

            buttons: [

                {
                    extend: 'copy',
                    text: '<i class="bi bi-clipboard me-1"></i> Copy',
                    className: 'dt-export-btn buttons-copy',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },

                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                    className: 'dt-export-btn buttons-excel',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },

                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
                    className: 'dt-export-btn buttons-pdf',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },

                {
                    extend: 'print',
                    text: '<i class="bi bi-printer me-1"></i> Print',
                    className: 'dt-export-btn buttons-print',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }

            ]

        });

        setTimeout(function () {
            $('#success-alert').fadeOut('slow');
        }, 5000);

    });
</script>
@endsection
