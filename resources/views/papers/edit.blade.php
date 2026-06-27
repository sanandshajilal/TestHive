@extends('layouts.app')

@section('title', 'Edit Paper')

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

    /* Main Card */

    .card-style {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.75rem;
        box-shadow: 0 2px 10px rgba(180,110,76,.08);
    }

    /* Topic Cards */

    .topic-block {
        background: #fcf7f3;
        border: 1px solid #edd7ca;
        border-radius: .9rem;
    }

    .topic-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .topic-title {
        font-weight: 600;
        color: #832b00;
    }

    /* Labels */

    .form-label {
        font-weight: 600;
        color: #374151;
    }

    /* Inputs */

    .form-control,
    textarea {
        border-radius: .75rem;
        border: 1px solid #d9d9d9;
        transition: .2s;
    }

    .form-control:focus,
    textarea:focus {
        border-color: #b46e4c;
        box-shadow: 0 0 0 .2rem rgba(180,110,76,.15);
    }

    /* Section */

    .section-heading {
        border-bottom: 1px solid #edd7ca;
        padding-bottom: .75rem;
        margin-bottom: 1rem;
    }

    /* Sub Topics */

    .subtopic-row {
        display: flex;
        gap: .5rem;
        margin-bottom: .5rem;
    }

    .subtopic-row input {
        flex: 1;
    }

    /* Update Button */

    .btn-success {
        background: #b46e4c;
        border-color: #b46e4c;
        border-radius: 50px;
    }

    .btn-success:hover {
        background: #832b00;
        border-color: #832b00;
    }

    /* Cancel Button */

    .btn-secondary {
        background: #f7e3d8;
        border-color: #edd7ca;
        color: #832b00;
        border-radius: 50px;
    }

    .btn-secondary:hover {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

    /* Outline Buttons */

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

    .btn-outline-secondary {
        color: #832b00;
        border-color: #b46e4c;
        border-radius: 50px;
    }

    .btn-outline-secondary:hover {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

    .btn-outline-danger {
        border-radius: 50px;
    }

    /* Alerts */

    .alert {
        border-radius: .75rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-pencil-square me-2" style="color:#832b00;"></i>
                Edit Paper
            </h5>
            <small class="text-muted">
                Update paper details, topics and sub-topics.
            </small>
        </div>
        <a href="{{ route('papers.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i>
            <span class="d-none d-md-inline ms-1">Back to Papers</span>
        </a>
    </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if($errors->has('topic_delete'))
            <div class="alert alert-danger">
                {{ $errors->first('topic_delete') }}
            </div>
        @endif

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card-style">

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

        <form action="{{ route('papers.update', $paper->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Paper Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Paper Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $paper->name }}" required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="form-label">Description (optional)</label>
                <textarea class="form-control" id="description" name="description" rows="2">{{ $paper->description }}</textarea>
            </div>

            <!-- Topics Section -->
            <div class="section-heading">

                <h6 class="fw-semibold mb-1">
                    <i class="bi bi-diagram-3 me-2" style="color:#9a5631;"></i>
                    Topics & Sub-Topics
                </h6>

                <small class="text-muted">
                    Organize the structure used for question categorization.
                </small>

            </div>
            <div id="topic-container">
                @foreach($paper->topics as $topicIndex => $topic)

                    <div class="card mb-3 topic-block p-3 shadow-sm">

                        <div class="topic-header">

                            <div class="topic-title">
                                Topic #{{ $loop->iteration }}
                            </div>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger remove-topic"
                                    title="Remove Topic">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Topic Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="hidden"
                                name="topics[{{ $topicIndex }}][id]"
                                value="{{ $topic->id }}">

                            <input type="text"
                                name="topics[{{ $topicIndex }}][name]"
                                class="form-control"
                                value="{{ $topic->name }}"
                                required>

                        </div>

                        <div class="sub-topic-container">

                            <label class="form-label">
                                Sub-Topics
                            </label>

                       @foreach($topic->subTopics as $subIndex => $subTopic)

                        <div class="subtopic-row mb-2">

                            <input type="hidden"
                                name="topics[{{ $topicIndex }}][subtopic_ids][]"
                                value="{{ $subTopic->id }}">

                            <input type="text"
                                name="topics[{{ $topicIndex }}][sub_topics][]"
                                class="form-control"
                                value="{{ $subTopic->name }}">

                            <button type="button"
                                    class="btn btn-outline-danger remove-subtopic">
                                <i class="bi bi-x"></i>
                            </button>

                        </div>

                    @endforeach

                        </div>

                        <button type="button"
                                class="btn btn-sm btn-outline-secondary add-sub-topic mt-2">
                            <i class="bi bi-plus-circle me-1"></i>
                            Add Sub-topic
                        </button>

                    </div>

                @endforeach
            </div>

            <div class="mb-4">

                <button type="button"
                        class="btn btn-sm btn-outline-primary"
                        id="add-topic-btn">

                    <i class="bi bi-plus-circle me-1"></i>
                    Add New Topic

                </button>

            </div>

            <!-- Form Buttons -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('papers.index') }}" class="btn btn-secondary px-4">
                    Cancel
                </a>

                <button type="submit" class="btn btn-success px-4">
                   
                    Update Paper
                </button>
            </div>
        </form>
    </div>

</div>
</div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    let topicIndex = {{ $paper->topics->count() }};

    document.getElementById('add-topic-btn').addEventListener('click', function () {

        const container = document.getElementById('topic-container');

        const topicBlock = document.createElement('div');

        topicBlock.classList.add(
            'card',
            'mb-3',
            'topic-block',
            'p-3',
            'shadow-sm'
        );

        topicBlock.innerHTML = `
            <div class="topic-header">

                <div class="topic-title">
                    Topic #${topicIndex + 1}
                </div>

                <button type="button"
                        class="btn btn-sm btn-outline-danger remove-topic"
                        title="Remove Topic">
                    <i class="bi bi-trash"></i>
                </button>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Topic Name
                    <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="topics[${topicIndex}][name]"
                       class="form-control"
                       required>

            </div>

            <div class="sub-topic-container">

                <label class="form-label">
                    Sub-Topics
                </label>

                <div class="subtopic-row mb-2">

                    <input type="text"
                           name="topics[${topicIndex}][sub_topics][0]"
                           class="form-control"
                           placeholder="Enter sub-topic name">

                    <button type="button"
                            class="btn btn-outline-danger remove-subtopic">
                        <i class="bi bi-x"></i>
                    </button>

                </div>

            </div>

            <button type="button"
                    class="btn btn-sm btn-outline-secondary add-sub-topic mt-2">
                <i class="bi bi-plus-circle me-1"></i>
                Add Sub-topic
            </button>
        `;

        container.appendChild(topicBlock);

        topicIndex++;
    });

    document.addEventListener('click', function (e) {

        /* Add Sub Topic */
        if (e.target.closest('.add-sub-topic')) {

            const subTopicContainer =
                e.target.closest('.topic-block')
                    .querySelector('.sub-topic-container');

            const topicBlock =
                e.target.closest('.topic-block');

            const index =
                Array.from(document.querySelectorAll('.topic-block'))
                    .indexOf(topicBlock);

            const count =
                subTopicContainer.querySelectorAll('.subtopic-row').length;

            const input = document.createElement('div');

            input.classList.add('subtopic-row', 'mb-2');

            input.innerHTML = `
                <input type="text"
                       name="topics[${index}][sub_topics][${count}]"
                       class="form-control"
                       placeholder="Enter sub-topic name">

                <button type="button"
                        class="btn btn-outline-danger remove-subtopic">
                    <i class="bi bi-x"></i>
                </button>
            `;

            subTopicContainer.appendChild(input);
        }

        /* Remove Topic */
        if (e.target.closest('.remove-topic')) {

            const topics =
                document.querySelectorAll('.topic-block');

            if (topics.length > 1) {

                e.target.closest('.topic-block').remove();

            }
        }

        /* Remove Sub Topic */
        if (e.target.closest('.remove-subtopic')) {

            const wrapper =
                e.target.closest('.sub-topic-container');

            const rows =
                wrapper.querySelectorAll('.subtopic-row');

            if (rows.length > 1) {

                e.target.closest('.subtopic-row').remove();

            }
        }

    });

});
</script>
@endsection
