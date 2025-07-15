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

    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
    }

    .institute-header {
        background-color: #343a40;
        color: #fff;
        padding: 0.75rem 1.25rem;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .list-group {
        padding: 0;
        margin: 0;
    }

    .batch-actions .btn {
        margin-left: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-semibold text-dark mb-0">Batches</h4>
        <a href="{{ route('batches.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle me-1"></i> Add Batch
        </a>
    </div>

    @foreach($institutes as $institute)
        <div class="card-style mb-4">
            <div class="institute-header">
                {{ $institute->name }}
            </div>
            <div class="mt-3">
                @if($institute->batches->count())
                    <ul class="list-group">
                        @foreach($institute->batches as $batch)
                            <li class="list-group-item">
                                {{ $batch->name }}
                                <span class="batch-actions">
                                    <a href="{{ route('batches.edit', $batch) }}" class="btn btn-sm btn-soft-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('batches.destroy', $batch) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this batch?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-soft-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No batches added yet.</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
