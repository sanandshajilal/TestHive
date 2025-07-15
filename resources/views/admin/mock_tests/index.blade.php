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

    .card-style {
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        background-color: #fff;
        padding: 1.5rem;
    }

    .table thead {
        background-color: #f1f3f5;
    }

    .btn-sm {
        padding: 0.35rem 0.6rem;
        font-size: 0.8rem;
    }

    .table-responsive {
        border-radius: 0.75rem;
        overflow: hidden;
    }

    

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .dataTables_wrapper .dt-buttons .btn,
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 0.5rem !important;
    }

    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
        margin-top: 0.5rem;
    }

    .action-buttons .btn {
        margin-right: 4px;
    }

    .btn-soft-info {
        background-color: #e7f1ff;
        color: #0d6efd;
        border: none;
    }

    .btn-soft-warning {
        background-color: #fff3cd;
        color: #856404;
        border: none;
    }

    .btn-soft-danger {
        background-color: #fdecea;
        color: #dc3545;
        border: none;
    }

    .btn-soft-danger:hover,
    .btn-soft-info:hover,
    .btn-soft-warning:hover {
        opacity: 0.85;
    }

    .header-box {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
    }

    @media screen and (max-width: 768px) {
        .dataTables_wrapper .dataTables_filter {
            float: none;
            text-align: left;
        }
        .dataTables_wrapper .dt-buttons {
            justify-content: start;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-semibold text-dark mb-0">All Mock Tests</h4>
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

    <div class="card-style">
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="mockTestsTable">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>#</th>
                        <th>Test Name</th>
                        <th>Paper</th>
                        <th>Scheduled Time</th>
                        <th>Status</th>
                        <th>Access Code</th>
                        <th>Results</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mockTests as $mockTest)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $mockTest->title ?? 'N/A' }}</td>
                            <td>{{ $mockTest->paper->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($mockTest->start_time)->format('d M Y, h:i A') }}</td>
                            <td>
                                @if($now->between($mockTest->start_time, $mockTest->end_time))
                                    <span class="badge bg-success">Ongoing</span>
                                @elseif(now()->lt($mockTest->start_time))
                                    <span class="badge bg-warning text-dark">Upcoming</span>
                                @else
                                    <span class="badge bg-secondary">Completed</span>
                                @endif
                            </td>
                            <td>{{ $mockTest->access_code }}</td>
                            <td>
                                <a href="{{ route('mock-tests.results', $mockTest->id) }}" class="text-primary small text-decoration-none">
                                    View
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
