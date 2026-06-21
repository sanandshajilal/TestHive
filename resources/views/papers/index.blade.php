@extends('layouts.app')

@section('styles')
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

    /* Primary Button */

    .btn-primary {
        background: #b46e4c;
        border-color: #b46e4c;
    }

    .btn-primary:hover {
        background: #832b00;
        border-color: #832b00;
    }

    /* Edit Button */

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

    /* Delete Button */

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

    /* Table */

    .table thead {
        background: #fcf7f3;
    }

    .table thead th {
        color: #9a5631;
        font-weight: 600;
        border-bottom: 1px solid #edd7ca;
    }

    .table tbody tr {
        transition: .2s;
    }

    .table tbody tr:hover {
        background: #fcf7f3;
    }

    /* Paper Name */

    .paper-name {
        font-weight: 600;
        color: #1f2937;
    }

    /* Content Badge */

    .content-badge {
        background: #f7e3d8;
        border: 1px solid #edd7ca;
        color: #832b00;
        font-weight: 600;
        transition: .2s;
    }

    .content-badge:hover {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

    .content-badge:hover i {
        color: #ffffff !important;
    }

    /* Alerts */

    .alert {
        margin-top: 1rem;
        border-radius: .75rem;
    }

    /* Action Buttons */

    .action-buttons .btn {
        margin-right: .4rem;
    }

    /* Empty State */

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 2.8rem;
        color: #d6b29d;
    }

    .empty-state-text {
        margin-top: .75rem;
        color: #9a5631;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <div>
            <h4 class="fw-semibold text-dark mb-0">
                <i class="bi bi-journal-text me-2" style="color:#832b00;"></i>
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

                                <i class="bi bi-diagram-3 me-1" style="color:#9a5631;"></i>
                                {{ $paper->topics_count }}

                                <span class="mx-1">|</span>

                                <i class="bi bi-question-circle me-1" style="color:#b46e4c;"></i>
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