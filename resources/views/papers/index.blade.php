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

    .table tbody tr {
        transition: all .2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
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
        color: #fff;
    }

    .action-buttons .btn {
        margin-right: 0.4rem;
    }

    .alert {
        margin-top: 1rem;
        border-radius: 0.5rem;
    }

    .paper-name {
        font-weight: 600;
        color: #212529;
    }

    .content-badge {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #374151;
        font-weight: 500;
    }

    .content-badge:hover {
        background: #eef4ff;
        border-color: #dbe7ff;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
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
                <i class="bi bi-journal-text text-primary me-2"></i>
                Papers
            </h4>

            <small class="text-muted">
                {{ $papers->count() }} paper(s) available
            </small>
        </div>

        <a href="{{ route('papers.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle me-1"></i>
            Add New Paper
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <div class="card-style">

        <div class="table-responsive">

            <table class="table table-bordered align-middle mb-0">

                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Name</th>
                        <th>Description</th>
                        <th class="text-center">Content</th>
                        <th>Created</th>
                        <th class="text-center" style="width:180px;">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody>

                @forelse($papers as $paper)

                    <tr>

                        <td>
                            <div class="paper-name">
                                {{ $paper->name }}
                            </div>
                        </td>

                        <td>
                            {{ $paper->description ?? '-' }}
                        </td>

                        <td class="text-center">

                            <a href="{{ route('topics.by-paper', $paper->id) }}"
                               class="badge content-badge text-decoration-none px-3 py-2">

                                <i class="bi bi-diagram-3 text-info me-1"></i>
                                {{ $paper->topics_count }}

                                <span class="mx-1">|</span>

                                <i class="bi bi-question-circle text-warning me-1"></i>
                                {{ $paper->questions_count }}

                            </a>

                        </td>

                        <td>
                            {{ $paper->created_at->format('d M Y') }}
                        </td>

                        <td class="text-center action-buttons">

                            <a href="{{ route('papers.edit', $paper->id) }}"
                               class="btn btn-sm btn-soft-warning"
                               title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('papers.destroy', $paper->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete this paper?')">

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
                        <td colspan="5">

                            <div class="empty-state">

                                <i class="bi bi-journal-x"></i>

                                <div class="empty-state-text">
                                    No papers found.
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