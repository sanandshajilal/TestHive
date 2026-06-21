@php
    $now = \Carbon\Carbon::now('Asia/Kolkata');
@endphp

@extends('layouts.app')

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

    /* Main Card */

    .card-style {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(180,110,76,.08);
    }

    /* KPI Cards */

    .card.border-0.shadow-sm.rounded-4 {
        box-shadow: 0 2px 10px rgba(180,110,76,.08) !important;
        transition: .2s;
    }

    .card.border-0.shadow-sm.rounded-4:hover {
        transform: translateY(-2px);
    }

    /* Table */

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
        border-bottom: 1px solid #edd7ca;
    }

    .table tbody tr {
        transition: all .2s ease;
    }

    .table tbody tr:hover {
        background: #fcf7f3;
    }

    /* Test Name */

    .test-name {
        font-weight: 600;
        color: #1f2937;
    }

    /* Action Buttons */

    .action-buttons .btn {
        margin-right: 4px;
    }

    .btn-sm {
        padding: .35rem .6rem;
        font-size: .8rem;
    }

    /* Primary Button */

    .btn-primary {
        background: #b46e4c;
        border-color: #b46e4c;
    }

    .btn-primary:hover {
        background: #832b00;
        border-color: #832b00;
    }

    /* Soft View Button */

    .btn-soft-info {
        background: #f7e3d8;
        color: #832b00;
        border: none;
        transition: .2s;
    }

    .btn-soft-info:hover {
        background: #b46e4c;
        color: #ffffff;
    }

    /* Soft Edit Button */

    .btn-soft-warning {
        background: #f7e3d8;
        color: #832b00;
        border: none;
        transition: .2s;
    }

    .btn-soft-warning:hover {
        background: #b46e4c;
        color: #ffffff;
    }

    /* Soft Delete Button */

    .btn-soft-danger {
        background: #fdecea;
        color: #c0392b;
        border: none;
        transition: .2s;
    }

    .btn-soft-danger:hover {
        background: #c0392b;
        color: #ffffff;
    }

    /* DataTables */

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
        margin-top: .5rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: .5rem !important;
        border: 1px solid #edd7ca;
        padding: .35rem .75rem;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #b46e4c;
        box-shadow: 0 0 0 .2rem rgba(180,110,76,.15);
        outline: none;
    }

    .dt-button {
        background: #f7e3d8 !important;
        color: #832b00 !important;
        border: 1px solid #edd7ca !important;
        border-radius: 50px !important;
        transition: .2s;
    }

    .dt-button:hover {
        background: #b46e4c !important;
        color: #ffffff !important;
        border-color: #b46e4c !important;
    }

    /* Alerts */

    .alert {
        border-radius: .75rem;
    }

    /* Responsive */

    @media screen and (max-width: 768px) {

        .dataTables_wrapper .dataTables_filter {
            float: none;
            text-align: left;
        }

        .dataTables_wrapper .dt-buttons {
            justify-content: flex-start;
        }

    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-semibold text-dark mb-0">
            <i class="bi bi-clipboard-check me-2" style="color:#832b00;"></i>
            All Tests
        </h4>
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
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Total Tests</small>
                    <h4 class="mb-0">{{ $mockTests->count() }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Upcoming</small>
                    <h4 class="mb-0">
                        {{ $mockTests->where('status','Upcoming')->count() }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Active</small>
                    <h4 class="mb-0">
                        {{ $mockTests->where('status','Active')->count() }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Completed</small>
                    <h4 class="mb-0">
                        {{ $mockTests->where('status','Expired')->count() }}
                    </h4>
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
                             <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="test-name">{{ $mockTest->title }}</div>

                                <div class="small text-muted">
                                    {{ $mockTest->paper->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                @if($mockTest->batches->first())
                                    <div>{{ $mockTest->batches->first()->name }}</div>

                                    <div class="small text-muted">
                                        {{ optional($mockTest->batches->first()->institute)->name ?? '' }}
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($mockTest->start_time)->format('d M Y, h:i A') }}</td>
                             <td class="text-center">
                                @if($now->between($mockTest->start_time, $mockTest->end_time))
                                    <span class="badge bg-success">Ongoing</span>
                                @elseif(now()->lt($mockTest->start_time))
                                    <span class="badge bg-warning text-dark">Upcoming</span>
                                @else
                                    <span class="badge bg-secondary">Completed</span>
                                @endif
                            </td>
                            <td>{{ $mockTest->access_code }}</td>
                            <td class="text-center">
                                <a href="{{ route('mock-tests.results', $mockTest->id) }}"
                                class="btn btn-sm btn-soft-info"
                                title="Results">
                                    <i class="bi bi-bar-chart"></i>
                                </a>
                            </td>
                            <td class="text-center action-buttons">
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
                    className: 'btn btn-outline-secondary btn-sm rounded-pill',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-outline-success btn-sm rounded-pill',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-outline-danger btn-sm rounded-pill',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-dark btn-sm rounded-pill',
                    exportOptions: { columns: ':not(:last-child)' }
                }
            ]
        });

        setTimeout(function () {
            $('#success-alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection
