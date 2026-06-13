@extends('layouts.app')

@section('title', 'Edit Paper')

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

    .topic-block {
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

.section-heading {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 0.75rem;
    margin-bottom: 1rem;
}

.subtopic-row {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.subtopic-row input {
    flex: 1;
}

</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-pencil-square text-warning me-2"></i>
                Edit Paper
            </h5>
            <small class="text-muted">
                Update paper details, topics and sub-topics.
            </small>
        </div>
        <a href="{{ route('papers.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Papers
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
                    <i class="bi bi-diagram-3 me-2"></i>
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
                <button type="submit"
                        class="btn btn-success px-4">
                    Update Paper
                </button>
                <a href="{{ route('papers.index') }}" class="btn btn-secondary px-4">Cancel</a>
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
