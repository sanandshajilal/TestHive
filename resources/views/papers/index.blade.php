@extends('layouts.app')

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

    .table thead {
        background-color: #f1f3f5;
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

    .btn-soft-warning:hover,
    .btn-soft-danger:hover {
        opacity: 0.85;
    }

    .action-buttons .btn {
        margin-right: 0.4rem;
    }

    .alert {
        margin-top: 1rem;
        border-radius: 0.5rem;
    }

    .badge {
        font-size: 0.75rem;
    }

    a.badge:hover {
    opacity: 0.85;
    }


</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-semibold text-dark mb-0">Papers</h4>
        <a href="{{ route('papers.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle me-1"></i> Add New Paper
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-style">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Name</th>
                        <th>Description</th>
                        <th>Topics</th>
                        <th>Created</th>
                        <th class="text-center" style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($papers as $paper)
                        <tr>
                            <td>{{ $paper->name }}</td>
                            <td>{{ $paper->description ?? '-' }}</td>
                            <td class="align-middle text-center">
                                @if($paper->topics->count() > 0)
                                    <a href="{{ route('topics.by-paper', $paper->id) }}"
                                    class="badge bg-info text-decoration-none d-inline-flex align-items-center gap-1 px-3 py-2"
                                    title="View Topics">
                                        <i class="bi bi-eye"></i> {{ $paper->topics->count() }}
                                    </a>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">0</span>
                                @endif
                            </td>



                            <td>{{ $paper->created_at->format('d M Y') }}</td>
                            <td class="text-center action-buttons">
                                <a href="{{ route('papers.edit', $paper->id) }}" class="btn btn-sm btn-soft-warning" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('papers.destroy', $paper->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this paper?')">
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
