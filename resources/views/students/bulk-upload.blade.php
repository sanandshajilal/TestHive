@extends('layouts.app')

@section('title', 'Bulk Upload Students')

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

    /* Buttons */

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

    .btn-outline-secondary {
        background: #f7e3d8;
        border: 1px solid #edd7ca;
        color: #832b00;
        border-radius: 50px;
        transition: all .2s ease;
    }

    .btn-outline-secondary:hover {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

    /* Template Box */

    .sample-box {
        background: #fcf7f3;
        border: 1px solid #edd7ca;
        border-radius: .85rem;
        padding: 1.25rem;
        margin-top: 1rem;
    }

    .sample-box strong {
        color: #832b00;
    }

    .sample-box pre {
        margin-bottom: 0;
        margin-top: .5rem;
        padding: 1rem;
        border-radius: .6rem;
        background: #ffffff;
        border: 1px solid #f1e4dc;
        color: #495057;
        font-size: .9rem;
    }

    /* Download Button */

    .sample-box .btn-outline-secondary {
        background: #ffffff;
    }

    .sample-box .btn-outline-secondary:hover,
    .sample-box .btn-outline-secondary:focus {
        background: #f7e3d8;
        border-color: #b46e4c;
        color: #832b00 !important;
    }

    .sample-box .btn-outline-secondary i {
        color: inherit;
    }

    /* Form Text */

    .form-text {
        color: #6c757d;
    }

    /* Validation */

    .invalid-feedback {
        font-size: .875rem;
    }

    /* Upload Icon */

    .upload-icon {
        font-size: 3rem;
        color: #b46e4c;
        opacity: .7;
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
            <i class="bi bi-upload me-2" style="color:#832b00;"></i>
            Bulk Upload Students
        </h4>

        <small class="text-muted">
            Import multiple students into a batch using a CSV file.
        </small>

    </div>

    <div class="form-card">

        <form action="{{ route('students.bulk-upload') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <!-- Batch Selection -->
            <div class="mb-4">

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

            <!-- CSV Upload -->
            <div class="mb-4">

                <label class="form-label">
                    CSV File <span class="required">*</span>
                </label>

                <input type="file"
                       name="csv_file"
                       accept=".csv"
                       class="form-control @error('csv_file') is-invalid @enderror">

                <div class="form-text">
                    Upload a CSV file containing student names and email addresses.
                </div>

                @error('csv_file')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- Sample Format -->
            <div class="sample-box">

                <div class="d-flex justify-content-between align-items-center mb-2">

                    <strong>
                        <i class="bi bi-file-earmark-text me-1"></i>
                        CSV Format
                    </strong>

                <a href="{{ asset('templates/student_upload_template.csv') }}"
                            class="btn btn-sm btn-outline-secondary">

                                <i class="bi bi-download me-1"></i>
                                Download Template

                            </a>

                </div>

<pre>name,email
Sanand S,sanand@gmail.com
Mala Sri,maal@gmail.com</pre>

<div class="small text-muted mt-2">
    <i class="bi bi-info-circle me-1"></i>
    First row must contain the column headers name,email.
</div>

            </div>

            <div class="d-flex justify-content-between mt-4">

                <a href="{{ route('students.index') }}"
                   class="btn btn-outline-secondary">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-upload me-1"></i>
                    Upload Students

                </button>

            </div>

        </form>

    </div>

</div>

@endsection