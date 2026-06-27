@extends('layouts.app')

@section('title', 'Students')

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

    /* Buttons */

    .btn-primary {
        background: #b46e4c;
        border-color: #b46e4c;
        border-radius: 50px;
    }

    .btn-primary:hover {
        background: #832b00;
        border-color: #832b00;
    }

    .btn-outline-primary {
        color: #832b00;
        border-color: #b46e4c;
        border-radius: 50px;
    }

    .btn-outline-primary:hover {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

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

    /* Accordion */

    .accordion-item {
        border: none;
        margin-bottom: 1rem;
        border-radius: 1rem !important;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(180,110,76,.08);
    }

    .accordion-button {
        background: #ffffff;
        padding: 1rem 1.25rem;
        box-shadow: none !important;
    }

    .accordion-button:not(.collapsed) {
        background: #fcf7f3;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: transparent;
    }

    /* Batch */

    .batch-title {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
    }

    .batch-meta {
        font-size: .85rem;
        color: #6c757d;
        margin-top: .15rem;
    }

    .student-count {
        background: #f7e3d8;
        color: #832b00;
        padding: .35rem .8rem;
        border-radius: 999px;
        font-size: .8rem;
        font-weight: 600;
    }

    .accordion-body {
        background: #ffffff;
        padding: 1rem;
    }

    /* Student List */

    .list-group {
        padding: 0;
        margin: 0;
    }

    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: .8rem;
        padding: .9rem 1rem;
        margin-bottom: .6rem;
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

    .student-name {
        font-weight: 600;
        color: #212529;
    }

    .student-email {
        font-size: .85rem;
        color: #6c757d;
    }

    .student-actions .btn {
        margin-left: .25rem;
    }

    /* Badges */

    .bg-success-subtle {
        background: #eef9f0 !important;
    }

    .text-success {
        color: #198754 !important;
    }

    /* Empty */

    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: #6c757d;
    }

    .empty-state i {
        display: block;
        font-size: 2rem;
        margin-bottom: .6rem;
        color: #b46e4c;
        opacity: .7;
    }

    
</style>

@endsection

@section('content')

<div class="container py-4">


<div class="header-box mb-4 d-flex justify-content-between align-items-center">

    <div>
        <h4 class="fw-semibold text-dark mb-0">
            <i class="bi bi-mortarboard-fill me-2" style="color:#832b00;"></i>
            Students
        </h4>

        <small class="text-muted">
            {{ $studentCount }} student(s) registered
        </small>
    </div>

    <div>

        <a href="{{ route('students.bulk-upload.form') }}"
        class="btn btn-outline-primary rounded-pill me-2">
            <i class="bi bi-upload"></i>
            <span class="d-none d-md-inline ms-1">Bulk Upload</span>
        </a>

        <a href="{{ route('students.create') }}"
        class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle"></i>
            <span class="d-none d-md-inline ms-1">Add Student</span>
        </a>

    </div>

</div>

<div class="accordion" id="studentAccordion">

    @foreach($batches as $batch)

    <div class="accordion-item">

        <h2 class="accordion-header">

            <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#batch{{ $batch->id }}">

                <div class="w-100 d-flex justify-content-between align-items-center">

                    <div>

                        <div class="batch-title">

                            <i class="bi bi-people-fill me-2" style="color:#b46e4c;"></i>

                            {{ $batch->name }}

                        </div>

                        <div class="batch-meta">

                            {{ $batch->institute->name ?? 'No Institute' }}

                        </div>

                    </div>
                        <span class="student-count me-3">
                            <i class="bi bi-person-fill me-1"></i>
                            {{ $batch->students->count() }}
                        </span>

                </div>

            </button>

        </h2>

        <div id="batch{{ $batch->id }}"
             class="accordion-collapse collapse"
             data-bs-parent="#studentAccordion">

            <div class="accordion-body">

                @if($batch->students->count())

                    <ul class="list-group">

                        @foreach($batch->students as $student)

                        <li class="list-group-item">

                            <div>

                                <div class="student-name">
                                    {{ $student->name }}
                                </div>

                                    <div class="student-email">
                                        {{ $student->email }}
                                    </div>

                                    <div class="mt-1">

                                        @if($student->is_active)

                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>

                                        @endif

                                    </div>

                            </div>

                            <span class="student-actions">

                                <a href="{{ route('students.edit', $student) }}"
                                   class="btn btn-sm btn-soft-warning"
                                   title="Edit">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                            <form action="{{ route('students.destroy', $student) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('{{ $student->is_active ? 'Deactivate' : 'Activate' }} this student?');">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm {{ $student->is_active ? 'btn-soft-danger' : 'btn-success' }}"
                                        title="{{ $student->is_active ? 'Deactivate' : 'Activate' }}">

                                    @if($student->is_active)

                                        <i class="bi bi-person-dash"></i>

                                    @else

                                        <i class="bi bi-person-check"></i>

                                    @endif

                                </button>

                            </form>

                            </span>

                        </li>

                        @endforeach

                    </ul>

                @else

                    <div class="empty-state">

                        <i class="bi bi-person-x"></i>

                        No students added yet.

                    </div>

                @endif

            </div>

        </div>

    </div>

    @endforeach

</div>


</div>

@endsection
