@extends('layouts.app')

@section('title', 'Bulk Upload Students')

@section('styles')
<style>
    body {
        background-color: #f9fafb;
    }

    .page-header {
        background: #fff;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }

    .form-card {
        background: #fff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }

    .form-label {
        font-weight: 600;
    }

    .required {
        color: #dc3545;
    }

    .sample-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: .75rem;
        padding: 1rem;
        margin-top: 1rem;
    }

    .sample-box pre {
        margin-bottom: 0;
        font-size: .9rem;
        color: #495057;
    }

    .upload-icon {
        font-size: 3rem;
        color: #6c757d;
    }
</style>
@endsection

@section('content')

<div class="container py-4">

    <div class="page-header">

        <h4 class="mb-1">
            <i class="bi bi-upload text-primary me-2"></i>
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