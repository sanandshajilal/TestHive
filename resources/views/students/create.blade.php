@extends('layouts.app')

@section('title', 'Add Student')

@section('styles')
<style>
    body {
        background-color: #f9fafb;
    }

    /* Header */

    .page-header {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(180,110,76,.08);
        border-top: 5px solid #832b00;
    }

    /* Form Card */

    .form-card {
        background: #ffffff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(180,110,76,.08);
    }

    /* Labels */

    .form-label {
        font-weight: 600;
        color: #374151;
    }

    .required {
        color: #dc3545;
    }

    /* Inputs */

    .form-control,
    .form-select {
        border-radius: .75rem;
        border: 1px solid #d9d9d9;
        padding: .7rem .9rem;
        transition: .2s;
        background: #ffffff;
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

    /* Secondary Button */

    .btn-outline-secondary {
        background: #f7e3d8;
        border: 1px solid #edd7ca;
        color: #832b00;
        border-radius: 50px;
        transition: all .2s ease;
    }

    .btn-outline-secondary:hover,
    .btn-outline-secondary:focus {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

    /* Validation */

    .invalid-feedback {
        font-size: .875rem;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .form-select {
        border-radius: .75rem;
        border: 1px solid #d9d9d9;
        padding: .7rem .9rem;

        /* Keep space for arrow */
        padding-right: 2.5rem;

        /* ACCAPrep arrow */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%23832b00' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right .9rem center;
        background-size: 16px 12px;
    }

    .form-select:focus {
        border-color: #b46e4c;
        box-shadow: 0 0 0 .2rem rgba(180,110,76,.15);
    }
</style>
@endsection

@section('content')

<div class="container py-4">

    <div class="page-header">

        <h4 class="mb-1">
            <i class="bi bi-person-plus-fill me-2" style="color:#832b00;"></i>
            Add Student
        </h4>

        <small class="text-muted">
            Register a student under a batch
        </small>

    </div>

    <div class="form-card">

        <form action="{{ route('students.store') }}" method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Batch <span class="required">*</span>
                </label>

                <select name="batch_id"
                        class="form-select @error('batch_id') is-invalid @enderror">

                    <option value="">
                        Select Batch
                    </option>

                    @foreach($batches as $batch)

                        <option value="{{ $batch->id }}"
                            {{ old('batch_id') == $batch->id ? 'selected' : '' }}>

                            {{ $batch->name }}
                            ({{ $batch->institute->name ?? 'No Institute' }})

                        </option>

                    @endforeach

                </select>

                @error('batch_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Student Name <span class="required">*</span>
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror">

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Email Address <span class="required">*</span>
                </label>

                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror">

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="d-flex justify-content-between">

                <a href="{{ route('students.index') }}"
                   class="btn btn-outline-secondary">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle me-1"></i>
                    Save Student

                </button>

            </div>

        </form>

    </div>

</div>

@endsection