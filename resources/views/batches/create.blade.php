@extends('layouts.app')

@section('title', 'Create Batch')

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
        padding: 1.75rem;
    }

    .form-label {
        font-weight: 500;
    }

    .btn-success,
    .btn-secondary {
        border-radius: 50px;
    }

    .alert {
        border-radius: 0.75rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-people-fill me-2 text-primary"></i>
                Create Batch
            </h5>

            <small class="text-muted">
                Create a new batch and assign it to an institute.
            </small>
        </div>

        <a href="{{ route('batches.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Batches
        </a>

    </div>

    <div class="row justify-content-center">

        <div class="col-lg-12">

            <div class="card-style">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="fw-semibold mb-2">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Please correct the following errors:
                        </div>

                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('batches.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="institute_id" class="form-label">
                            Institute
                        </label>

                        <select name="institute_id"
                                id="institute_id"
                                class="form-select"
                                required>

                            <option value="">-- Select Institute --</option>

                            @foreach ($institutes as $institute)
                                <option value="{{ $institute->id }}"
                                    {{ old('institute_id') == $institute->id ? 'selected' : '' }}>
                                    {{ $institute->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="name" class="form-label">
                            Batch Name
                        </label>

                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Eg. Batch A, Weekend Batch, June 2026"
                               required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('batches.index') }}"
                           class="btn btn-secondary">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-success">
                            Create Batch
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
@endsection