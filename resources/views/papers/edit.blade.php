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
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark fw-semibold">Edit Paper</h5>
        <a href="{{ route('papers.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Papers
        </a>
    </div>

    <div class="card-style">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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
            <h6 class="mb-3 text-dark fw-semibold">Topics & Subtopics</h6>
            <div id="topic-container">
                @foreach($paper->topics as $topicIndex => $topic)
                    <div class="card mb-3 topic-block p-3 shadow-sm">
                        <div class="mb-2">
                            <label class="form-label">Topic</label>
                            <input type="text" name="topics[{{ $topicIndex }}][name]" class="form-control" value="{{ $topic->name }}" required>
                        </div>

                        <div class="sub-topic-container">
                            @foreach($topic->subTopics as $subIndex => $subTopic)
                                <div class="mb-2">
                                    <label class="form-label">Sub Topic</label>
                                    <input type="text" name="topics[{{ $topicIndex }}][sub_topics][]" class="form-control mb-1" value="{{ $subTopic->name }}">
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-secondary add-sub-topic mt-2">+ Add Sub-topic</button>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-topic float-end mt-2">Remove Topic</button>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-primary mb-4" id="add-topic-btn">+ Add New Topic</button>

            <!-- Form Buttons -->
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success px-4">Update Paper</button>
                <a href="{{ route('papers.index') }}" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </form>
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
        topicBlock.classList.add('card', 'mb-3', 'topic-block', 'p-3', 'shadow-sm');
        topicBlock.innerHTML = `
            <div class="mb-2">
                <label class="form-label">Topic</label>
                <input type="text" name="topics[${topicIndex}][name]" class="form-control" required>
            </div>
            <div class="sub-topic-container"></div>
            <button type="button" class="btn btn-sm btn-outline-secondary add-sub-topic mt-2">+ Add Sub-topic</button>
            <button type="button" class="btn btn-sm btn-outline-danger remove-topic float-end mt-2">Remove Topic</button>
        `;
        container.appendChild(topicBlock);
        topicIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-sub-topic')) {
            const subTopicContainer = e.target.closest('.topic-block').querySelector('.sub-topic-container');
            const topicBlock = e.target.closest('.topic-block');
            const index = Array.from(document.querySelectorAll('.topic-block')).indexOf(topicBlock);
            const count = subTopicContainer.querySelectorAll('input').length;

            const input = document.createElement('div');
            input.classList.add('mb-2');
            input.innerHTML = `
                <label class="form-label">Sub Topic</label>
                <input type="text" name="topics[${index}][sub_topics][${count}]" class="form-control mb-1">
            `;
            subTopicContainer.appendChild(input);
        }

        if (e.target.classList.contains('remove-topic')) {
            e.target.closest('.topic-block').remove();
        }
    });
});
</script>
@endsection
