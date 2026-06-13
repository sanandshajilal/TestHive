@extends('layouts.app')

@section('title', 'Batches')

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
        color: #fff;
    }

    .institute-header {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #1f2937;
        font-weight: 600;
        padding: 0.85rem 1.25rem;
        border-radius: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-group {
        padding: 0;
        margin: 0;
    }

    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 0.75rem;
        padding: 0.85rem 1rem;
        margin-bottom: 0.5rem;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        transition: all .2s ease;
    }

    .list-group-item:hover {
        background-color: #f8fafc;
        border-color: #dbe7ff;
    }

    .batch-actions .btn {
        margin-left: 0.25rem;
    }

    .batch-name {
        font-weight: 600;
        color: #212529;
    }

    .empty-state {
        text-align: center;
        padding: 1rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 1.75rem;
        display: block;
        margin-bottom: 0.5rem;
        color: #adb5bd;
    }

    .section-spacing {
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <div>
            <h4 class="fw-semibold text-dark mb-0">
                <i class="bi bi-people-fill text-primary me-2"></i>
                Batches
            </h4>

            <small class="text-muted">
                {{ $institutes->sum(fn($i) => $i->batches->count()) }} batch(es) registered
            </small>
        </div>

        <a href="{{ route('batches.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle me-1"></i>
            Add Batch
        </a>

    </div>

    @foreach($institutes as $institute)

        <div class="card-style mb-4">

            <div class="institute-header">

                <div>
                    <i class="bi bi-building me-2 text-primary"></i>
                    {{ $institute->name }}
                </div>

                <span class="badge bg-light text-dark border">
                    {{ $institute->batches->count() }}
                </span>

            </div>

            <div class="section-spacing">

                @if($institute->batches->count())

                    <ul class="list-group">

                        @foreach($institute->batches as $batch)

                            <li class="list-group-item">

                                <div class="batch-name">
                                    {{ $batch->name }}
                                </div>

                                <span class="batch-actions">

                                    <a href="{{ route('batches.edit', $batch) }}"
                                       class="btn btn-sm btn-soft-warning"
                                       title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('batches.destroy', $batch) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this batch?');">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-soft-danger"
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </span>

                            </li>

                        @endforeach

                    </ul>

                @else

                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        No batches added yet.
                    </div>

                @endif

            </div>

        </div>

    @endforeach

</div>
@endsection