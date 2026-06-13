@extends('layouts.app')

@section('title', 'Edit Institute')

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

    .btn-primary {
        border-radius: 50px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-pencil-square me-2 text-warning"></i>
                Edit Institute
            </h5>

            <small class="text-muted">
                Update institute details and keep your records up to date.
            </small>
        </div>

        <a href="{{ route('institutes.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Institutes
        </a>

    </div>

    <div class="row justify-content-center">

        <div class="col-lg-12">

            <div class="card-style">

                <form action="{{ route('institutes.update', $institute) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('institutes.form')

                </form>

            </div>

        </div>

    </div>

</div>
@endsection