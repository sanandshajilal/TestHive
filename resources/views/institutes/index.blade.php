@extends('layouts.app')

@section('title', 'Institutes')

@section('styles')
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        background-color: #fff;
        padding: 1.5rem;
    }

    .btn-soft-warning {
        background-color: #fff3cd;
        color: #856404;
        border: none;
        transition: all .2s ease;
    }

    .btn-soft-danger {
        background-color: #fdecea;
        color: #dc3545;
        border: none;
        transition: all .2s ease;
    }

    .btn-soft-warning:hover {
        background-color: #ffc107;
        color: #212529;
    }

    .btn-soft-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    .action-buttons .btn {
        margin-right: 0.4rem;
    }

    .table-bordered th,
    .table-bordered td {
        vertical-align: middle;
    }

    .alert {
        margin-top: 1rem;
        border-radius: 0.5rem;
    }

    .table tbody tr {
        transition: all .2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    .institute-name {
        font-weight: 600;
        color: #212529;
    }

    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 2.5rem;
        color: #adb5bd;
    }

    .empty-state-text {
        margin-top: 0.75rem;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <div>
           <h4 class="fw-semibold text-dark mb-0">
                <i class="bi bi-buildings text-primary me-2"></i>
                Institutes
            </h4>

            <small class="text-muted">
                Manage registered institutes ({{ $institutes->count() }})
            </small>
        </div>

        <a href="{{ route('institutes.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle me-1"></i>
            Add Institute
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-style">

        <div class="table-responsive">

            <table class="table table-bordered align-middle mb-0">

                <thead class="table-light">
                    <tr class="small text-muted">
                        <th style="width:60px;">#</th>
                        <th>Institute Name</th>
                        <th class="text-center" style="width:180px;">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($institutes as $institute)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <div class="institute-name">
                                    {{ $institute->name }}
                                </div>
                            </td>

                            <td class="text-center action-buttons">

                                <a href="{{ route('institutes.edit', $institute) }}"
                                   class="btn btn-sm btn-soft-warning"
                                   title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('institutes.destroy', $institute) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this institute?');">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-soft-danger"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3">

                                <div class="empty-state">

                                    <i class="bi bi-building"></i>

                                    <div class="empty-state-text">
                                        No institutes found.
                                    </div>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection