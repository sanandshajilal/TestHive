@extends('layouts.app')

@section('title', 'Add Student')

@section('styles')
<style>
    body {
        background-color: #f9fafb;
    }

    .form-card {
        background: #fff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }

    .page-header {
        background: #fff;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }

    .form-label {
        font-weight: 600;
    }

    .required {
        color: #dc3545;
    }
</style>
@endsection

@section('content')

<div class="container py-4">

    <div class="page-header">

        <h4 class="mb-1">
            <i class="bi bi-person-plus-fill text-primary me-2"></i>
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