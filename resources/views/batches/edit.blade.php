@extends('layouts.app')

@section('title', 'Edit Batch')

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

    /* Form Card */

    .card-style {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.75rem;
        box-shadow: 0 2px 10px rgba(180,110,76,.08);
    }

    /* Labels */

    .form-label {
        font-weight: 600;
        color: #374151;
    }

    /* Inputs */

    .form-control,
    .form-select {
        border-radius: .75rem;
        border: 1px solid #d9d9d9;
        padding: .7rem .9rem;
        transition: .2s;
        background: #fff;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: #b46e4c;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #b46e4c;
        box-shadow: 0 0 0 .2rem rgba(180,110,76,.15);
    }

    /* Primary Button */

    .btn-success {
        background: #b46e4c;
        border-color: #b46e4c;
        border-radius: 50px;
        transition: .2s;
    }

    .btn-success:hover {
        background: #832b00;
        border-color: #832b00;
    }

    /* Secondary Button */

    .btn-secondary {
        background: #f7e3d8;
        border-color: #edd7ca;
        color: #832b00;
        border-radius: 50px;
        transition: all .2s ease;
    }

    .btn-secondary:hover,
    .btn-secondary:focus {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

    .btn-secondary:active {
        background: #832b00 !important;
        border-color: #832b00 !important;
        color: #ffffff !important;
    }

    /* Alert */

    .alert {
        border-radius: .75rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-pencil-square me-2" style="color:#832b00;"></i>
                Edit Batch
            </h5>

            <small class="text-muted">
                Update batch details and institute assignment.
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

                <form action="{{ route('batches.update', $batch->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="institute_id" class="form-label">
                            Institute
                        </label>

                        <select name="institute_id"
                                id="institute_id"
                                class="form-select"
                                required>

                            @foreach ($institutes as $institute)
                                <option value="{{ $institute->id }}"
                                    {{ old('institute_id', $batch->institute_id) == $institute->id ? 'selected' : '' }}>
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
                               value="{{ old('name', $batch->name) }}"
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
                            Update Batch
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
@endsection