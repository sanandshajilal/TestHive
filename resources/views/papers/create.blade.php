@extends('layouts.app')

@section('title', 'Add Paper')

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
        padding: 1.5rem;
    }

    .topic-group {
        background-color: #f8f9fa;
        border-radius: 0.75rem;
    }

    .form-label {
        font-weight: 500;
    }

    .btn {
        border-radius: 50px;
    }

    .btn-outline-secondary {
        border-radius: 30px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark fw-semibold">Add New Paper</h5>
        <a href="{{ route('papers.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Papers
        </a>
    </div>

    <div class="card-style">
        <form action="{{ route('papers.store') }}" method="POST">
            @csrf

            <!-- Paper Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Paper Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="Eg: FR, PM, TX">
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="form-label">Description (optional)</label>
                <textarea class="form-control" id="description" name="description" rows="2" placeholder="Brief about the paper"></textarea>
            </div>

            <!-- Topics Section -->
            <h6 class="mb-3 text-dark fw-semibold">Topics & Subtopics</h6>
            <div id="topics-wrapper">
                <div class="card mb-3 topic-group p-3 shadow-sm">
                    <div class="mb-2">
                        <label class="form-label">Topic Name <span class="text-danger">*</span></label>
                        <input type="text" name="topics[0][name]" class="form-control" placeholder="Enter topic name" required>
                    </div>

                    <div class="subtopics-wrapper">
                        <label class="form-label">Sub-Topics</label>
                        <input type="text" name="topics[0][subtopics][]" class="form-control mb-2" placeholder="Enter sub-topic name">
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-secondary add-subtopic mt-2">+ Add Sub-topic</button>
                </div>
            </div>

            <!-- Add Topic Button -->
            <button type="button" class="btn btn-outline-primary mb-4" id="add-topic">+ Add Another Topic</button>

            <!-- Form Buttons -->
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success px-4">Save Paper</button>
                <a href="{{ route('papers.index') }}" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let topicIndex = 1;

    document.getElementById('add-topic').addEventListener('click', function () {
        const wrapper = document.getElementById('topics-wrapper');

        const html = `
            <div class="card mb-3 topic-group p-3 shadow-sm">
                <div class="mb-2">
                    <label class="form-label">Topic Name <span class="text-danger">*</span></label>
                    <input type="text" name="topics[${topicIndex}][name]" class="form-control" placeholder="Enter topic name" required>
                </div>

                <div class="subtopics-wrapper">
                    <label class="form-label">Sub-Topics</label>
                    <input type="text" name="topics[${topicIndex}][subtopics][]" class="form-control mb-2" placeholder="Enter sub-topic name">
                </div>

                <button type="button" class="btn btn-sm btn-outline-secondary add-subtopic mt-2">+ Add Sub-topic</button>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', html);
        topicIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('add-subtopic')) {
            const subWrapper = e.target.previousElementSibling;
            const topicGroup = e.target.closest('.topic-group');
            const inputName = topicGroup.querySelector('input[name^="topics"]').name;
            const topicIndex = inputName.match(/\d+/)[0];

            const input = `<input type="text" name="topics[${topicIndex}][subtopics][]" class="form-control mb-2" placeholder="Enter sub-topic name">`;
            subWrapper.insertAdjacentHTML('beforeend', input);
        }
    });
</script>
@endsection
