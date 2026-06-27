@extends('layouts.app')

@section('title', 'Institutes')

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
/* Card */

.card-style {
    background: #ffffff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(180,110,76,.08);
}

/* Primary Button */

.btn-primary {
    background: #b46e4c;
    border-color: #b46e4c;
}

.btn-primary:hover {
    background: #832b00;
    border-color: #832b00;
}

/* Edit Button */

.btn-soft-warning {
    background: #f7e3d8;
    color: #832b00;
    border: none;
    transition: .2s;
}

.btn-soft-warning:hover {
    background: #b46e4c;
    color: #fff;
}

/* Delete Button */

.btn-soft-danger {
    background: #fdecea;
    color: #c0392b;
    border: none;
    transition: .2s;
}

.btn-soft-danger:hover {
    background: #c0392b;
    color: #fff;
}

/* Tables */

.table thead {
    background: #fcf7f3;
}

.table thead th {
    color: #9a5631;
    font-weight: 600;
    border-bottom: 1px solid #edd7ca;
}

.table-bordered th,
.table-bordered td {
    vertical-align: middle;
}

.table tbody tr {
    transition: .2s;
}

.table tbody tr:hover {
    background: #fcf7f3;
}

/* Institute Name */

.institute-name {
    font-weight: 600;
    color: #1f2937;
}

/* Alert */

.alert {
    border-radius: .75rem;
}

/* Action Buttons */

.action-buttons .btn {
    margin-right: .4rem;
}

/* Empty State */

.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    font-size: 2.8rem;
    color: #d6b29d;
}

.empty-state-text {
    margin-top: .75rem;
    color: #9a5631;
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
           <h4 class="fw-semibold text-dark mb-0">
                <i class="bi bi-buildings me-2" style="color:#832b00;"></i>
                Institutes
            </h4>

            <small class="text-muted">
                Manage registered institutes 
            </small>
        </div>

        <a href="{{ route('institutes.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle"></i>
            <span class="d-none d-md-inline ms-1">Add Institute</span>
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-style">

        <div class="table-responsive">

            <table class="table table-bordered align-middle mb-0">

                <thead class="table-light">
                    <tr class="small text-muted">
                        <th style="width:60px;">#</th>
                        <th>Institute Name</th>
                        <th class="text-center" style="width:180px;">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($institutes as $institute)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <div class="institute-name">
                                    {{ $institute->name }}
                                </div>
                            </td>

                            <td class="text-center action-buttons">

                                <a href="{{ route('institutes.edit', $institute) }}"
                                   class="btn btn-sm btn-soft-warning"
                                   title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('institutes.destroy', $institute) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this institute?');">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-soft-danger"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3">

                                <div class="empty-state">

                                    <i class="bi bi-building"></i>

                                    <div class="empty-state-text">
                                        No institutes found.
                                    </div>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection