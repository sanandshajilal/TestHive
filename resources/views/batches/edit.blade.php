@extends('layouts.app')

@section('title', 'Edit Batch')

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

    .form-label {
        font-weight: 500;
    }

    .btn-primary,
    .btn-secondary {
        border-radius: 50px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark fw-semibold">Edit Batch</h5>
        <a href="{{ route('batches.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Batches
        </a>
    </div>

    <div class="card-style">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('batches.update', $batch->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="institute_id" class="form-label">Institute</label>
                <select name="institute_id" id="institute_id" class="form-select" required>
                    @foreach ($institutes as $institute)
                        <option value="{{ $institute->id }}" {{ $batch->institute_id == $institute->id ? 'selected' : '' }}>
                            {{ $institute->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Batch Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $batch->name }}" required>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2">Update Batch</button>
                <a href="{{ route('batches.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

</div>
@endsection
