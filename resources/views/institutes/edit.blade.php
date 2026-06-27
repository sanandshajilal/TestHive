@extends('layouts.app')

@section('title', 'Edit Institute')

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

    /* Form Controls */

    .form-control,
    .form-select {
        border-radius: .75rem;
        border: 1px solid #e5e7eb;
        transition: all .2s ease;
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

    .btn-primary:hover,
    .btn-primary:focus {
        background: #832b00;
        border-color: #832b00;
    }

    /* Secondary Button */

    .btn-secondary {
        background: #f7e3d8;
        border-color: #f7e3d8;
        color: #832b00;
        border-radius: 50px;
        transition: .2s;
    }

    .btn-secondary:hover {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

        @media (max-width: 767.98px) {

    .header-box .btn {

        width: 42px;
        height: 42px;
        padding: 0;

        display: flex;
        align-items: center;
        justify-content: center;

    }
}
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-pencil-square me-2" style="color:#832b00;"></i>
                Edit Institute
            </h5>

            <small class="text-muted">
                Update institute details and keep your records up to date.
            </small>
        </div>

      <a href="{{ route('institutes.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i>
            <span class="d-none d-md-inline ms-1">Back to Institutes</span>
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