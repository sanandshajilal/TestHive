@extends('layouts.app')

@section('title', 'Batches')

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

    /* Cards */

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
        border-radius: 50px;
        transition: .2s;
    }

    .btn-primary:hover {
        background: #832b00;
        border-color: #832b00;
    }

    /* Soft Buttons */

    .btn-soft-warning {
        background: #f7e3d8;
        color: #832b00;
        border: none;
        transition: all .2s ease;
    }

    .btn-soft-warning:hover {
        background: #b46e4c;
        color: #ffffff;
    }

    .btn-soft-danger {
        background: #fdecea;
        color: #dc3545;
        border: none;
        transition: all .2s ease;
    }

    .btn-soft-danger:hover {
        background: #dc3545;
        color: #ffffff;
    }

    /* Institute Header */

    .institute-header {
        background: #fcf7f3;
        border: 1px solid #edd7ca;
        color: #832b00;
        font-weight: 600;
        padding: .9rem 1.25rem;
        border-radius: .85rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .institute-header i {
        color: #b46e4c !important;
    }

    .institute-header .badge {
        background: #ffffff !important;
        color: #832b00 !important;
        border: 1px solid #edd7ca !important;
        font-weight: 600;
    }

    /* Batch List */

    .list-group {
        padding: 0;
        margin: 0;
    }

    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .9rem 1rem;
        margin-bottom: .6rem;
        border-radius: .85rem;
        border: 1px solid #f1e4dc;
        background: #ffffff;
        transition: all .2s ease;
    }

    .list-group-item:last-child {
        margin-bottom: 0;
    }

    .list-group-item:hover {
        background: #fcf7f3;
        border-color: #edd7ca;
        transform: translateY(-1px);
    }

    .batch-name {
        font-weight: 600;
        color: #374151;
    }

    .batch-actions .btn {
        margin-left: .3rem;
    }

    /* Empty State */

    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: #6b7280;
    }

    .empty-state i {
        display: block;
        font-size: 2rem;
        margin-bottom: .75rem;
        color: #b46e4c;
        opacity: .7;
    }

    /* Spacing */

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
                <i class="bi bi-people-fill me-2" style="color:#832b00;"></i>
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
                    <i class="bi bi-building me-2" style="color:#b46e4c;"></i>
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