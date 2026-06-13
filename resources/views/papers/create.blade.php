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

    .topic-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.topic-title {
    font-weight: 600;
    color: #4e73df;
}

.subtopic-row {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.subtopic-row input {
    flex: 1;
}

.section-heading {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 0.75rem;
    margin-bottom: 1rem;
}

</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-journal-plus text-primary me-2"></i>
                Add New Paper
            </h5>

            <small class="text-muted">
                Create a paper and organize its topics and sub-topics.
            </small>
        </div>

        <a href="{{ route('papers.index') }}"
        class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Papers
        </a>

    </div>

        <div class="row justify-content-center">
        <div class="col-lg-12">
        <div class="card-style">
        <form action="{{ route('papers.store') }}" method="POST">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please correct the following:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
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
            <div class="section-heading">

                <h6 class="fw-semibold mb-1">
                    <i class="bi bi-diagram-3 me-2"></i>
                    Topics & Sub-Topics
                </h6>

                <small class="text-muted">
                    Define the structure used for categorizing questions.
                </small>

            </div>
            <div id="topics-wrapper">
                <div class="card mb-3 topic-group p-3 shadow-sm">

                    <div class="topic-header">

                        <div class="topic-title">
                            Topic #1
                        </div>

                        <button type="button"
                                class="btn btn-sm btn-outline-danger remove-topic">
                            <i class="bi bi-trash"></i>
                        </button>

                    </div>

                    <div class="mb-2">
                        <label class="form-label">
                            Topic Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            name="topics[0][name]"
                            class="form-control"
                            placeholder="Enter topic name"
                            required>
                    </div>

                    <div class="subtopics-wrapper">

                        <label class="form-label">Sub-Topics</label>

                        <div class="subtopic-row">
                            <input type="text"
                                name="topics[0][subtopics][]"
                                class="form-control"
                                placeholder="Enter sub-topic name">

                            <button type="button"
                                    class="btn btn-outline-danger remove-subtopic">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>

                    </div>

                    <button type="button"
                            class="btn btn-sm btn-outline-secondary add-subtopic mt-2">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add Sub-topic
                    </button>
</div>
                </div>

            <!-- Add Topic Button -->
           <div class="mb-4">
                <button type="button"
                        class="btn btn-sm btn-outline-primary"
                        id="add-topic">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add New Topic
                </button>
            </div>
            <!-- Form Buttons -->
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success px-4">Create Paper</button>
                <a href="{{ route('papers.index') }}" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
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

            <div class="topic-header">

                <div class="topic-title">
                    Topic #${topicIndex + 1}
                </div>

                <button type="button"
                        class="btn btn-sm btn-outline-danger remove-topic">
                    <i class="bi bi-trash"></i>
                </button>

            </div>

            <div class="mb-2">

                <label class="form-label">
                    Topic Name
                    <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="topics[${topicIndex}][name]"
                       class="form-control"
                       placeholder="Enter topic name"
                       required>

            </div>

            <div class="subtopics-wrapper">

                <label class="form-label">Sub-Topics</label>

                <div class="subtopic-row">

                    <input type="text"
                           name="topics[${topicIndex}][subtopics][]"
                           class="form-control"
                           placeholder="Enter sub-topic name">

                    <button type="button"
                            class="btn btn-outline-danger remove-subtopic">
                        <i class="bi bi-x"></i>
                    </button>

                </div>

            </div>

            <button type="button"
                    class="btn btn-sm btn-outline-secondary add-subtopic mt-2">
                <i class="bi bi-plus-circle me-1"></i>
                Add Sub-topic
            </button>

        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);

    topicIndex++;
});

document.addEventListener('click', function (e) {

    if (e.target.closest('.add-subtopic')) {

        const topicGroup = e.target.closest('.topic-group');

        const inputName =
            topicGroup.querySelector('input[name^="topics"]').name;

        const topicIndex =
            inputName.match(/\d+/)[0];

        const subWrapper =
            topicGroup.querySelector('.subtopics-wrapper');

        const html = `
            <div class="subtopic-row">

                <input type="text"
                       name="topics[${topicIndex}][subtopics][]"
                       class="form-control"
                       placeholder="Enter sub-topic name">

                <button type="button"
                        class="btn btn-outline-danger remove-subtopic">
                    <i class="bi bi-x"></i>
                </button>

            </div>
        `;

        subWrapper.insertAdjacentHTML('beforeend', html);
    }

    if (e.target.closest('.remove-topic')) {

        const topics =
            document.querySelectorAll('.topic-group');

        if (topics.length > 1) {
            e.target.closest('.topic-group').remove();
        }
    }

    if (e.target.closest('.remove-subtopic')) {

        const wrapper =
            e.target.closest('.subtopics-wrapper');

        const rows =
            wrapper.querySelectorAll('.subtopic-row');

        if (rows.length > 1) {
            e.target.closest('.subtopic-row').remove();
        }
    }
});
</script>
@endsection
