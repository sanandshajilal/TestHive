@extends('layouts.app')

@section('title', 'Create Mock Test')

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

    .form-label {
        font-weight: 500;
    }

    .btn-primary {
        border-radius: 50px;
    }

    .question-content {
        line-height: 1.5;
        word-break: break-word;
        white-space: normal;
    }

    .question-content p,
    .question-content ul,
    .question-content ol {
        margin: 0;
        padding: 0;
    }

        .icon-button {
        padding: 0;
        border: none;
        background: transparent;
        color: #6c757d; /* Bootstrap's text-secondary */
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .icon-button:hover {
        color: #0d6efd; /* Bootstrap's primary */
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark fw-semibold">Create Mock Test</h5>
        <a href="{{ route('mock-tests.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Tests
        </a>
    </div>

    {{-- Form --}}
    <div class="card-style">
        <form method="POST" action="{{ route('mock-tests.store') }}" onsubmit="return redirectAfterSubmit()">
            @csrf

            {{-- Paper and Title --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Paper</label>
                    <select name="paper_id" id="paper_id" class="form-select" required>
                        <option value="">-- Select Paper --</option>
                        @foreach($papers as $paper)
                            <option value="{{ $paper->id }}">{{ $paper->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
            </div>

            {{-- Batch, Start Time, Duration --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Batch</label>
                    <select name="batch_id" class="form-select" required>
                        <option value="">-- Select Batch --</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->institute->name }} - {{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Start Time</label>
                    <input type="datetime-local" name="start_time" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">End Time</label>
                    <input type="datetime-local" name="end_time" class="form-control" required>
                </div>


                <div class="col-md-3">
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" class="form-control" min="1" required>
                </div>
            </div>

            <hr>

            {{-- Filters --}}
            <div class="mb-3">
                <h6 class="fw-bold mb-2">Filter Questions</h6>
                <div class="row g-2">
                    <div class="col-md-4">
                        <select id="filterTopic" class="form-select">
                            <option value="">-- Topic --</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select id="filterSubtopic" class="form-select">
                            <option value="">-- Subtopic --</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select id="filterType" class="form-select">
                            <option value="">-- Type --</option>
                            <option value="mcq">MCQ</option>
                            <option value="multiple_select">Multiple Select</option>
                            <option value="one_word">One Word</option>
                            <option value="table_mcq">Table MCQ</option>
                            <option value="drag_and_drop">Drag and Drop</option>
                            <option value="dropdown">Drop Down</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Selected Question Summary --}}
            <div class="mb-3">
                <div class="alert alert-info" id="questionSummary" style="display:none;">
                    <strong>Selected Questions by Topic:</strong>
                    <ul class="mb-0" id="topicCounts" style="list-style: inside;"></ul>
                </div>
            </div>

            {{-- Question Bank --}}
            <input type="hidden" name="question_ids_serialized" id="question_ids_serialized">
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Question Bank</h6>
                <p class="text-muted">Select the required questions from the list below:</p>
                <div id="questionList" class="border p-3 rounded bg-light" style="max-height: 350px; overflow-y: auto;">
                    <p class="text-muted">Select a paper to load questions...</p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">Create Test</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="questionPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Question Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="questionPreviewContent">
                <p class="text-muted">Loading...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const selectedQuestions = new Map();

document.getElementById('paper_id').addEventListener('change', () => {
    loadTopics();
    loadQuestions();
});

document.getElementById('filterTopic').addEventListener('change', () => {
    loadSubtopics();
    loadQuestions();
});

document.getElementById('filterSubtopic').addEventListener('change', loadQuestions);
document.getElementById('filterType').addEventListener('change', loadQuestions);

function loadTopics() {
    const paperId = document.getElementById('paper_id').value;
    const topicSelect = document.getElementById('filterTopic');
    const subtopicSelect = document.getElementById('filterSubtopic');

    topicSelect.innerHTML = '<option value="">-- Loading Topics... --</option>';
    subtopicSelect.innerHTML = '<option value="">-- Subtopic --</option>';

    if (!paperId) {
        topicSelect.innerHTML = '<option value="">-- Select Paper First --</option>';
        return;
    }

    fetch(`/api/topics/${paperId}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">-- Topic --</option>';
            data.forEach(t => options += `<option value="${t.id}">${t.name}</option>`);
            topicSelect.innerHTML = options;
        });
}

function loadSubtopics() {
    const topicId = document.getElementById('filterTopic').value;
    const subtopicSelect = document.getElementById('filterSubtopic');

    subtopicSelect.innerHTML = '<option value="">-- Loading... --</option>';

    if (!topicId) {
        subtopicSelect.innerHTML = '<option value="">-- Topic First --</option>';
        return;
    }

    fetch(`/api/subtopics/${topicId}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">-- Subtopic --</option>';
            data.forEach(s => options += `<option value="${s.id}">${s.name}</option>`);
            subtopicSelect.innerHTML = options;
        });
}

function loadQuestions() {
    const paperId = document.getElementById('paper_id').value;
    const topicId = document.getElementById('filterTopic').value;
    const subtopicId = document.getElementById('filterSubtopic').value;
    const type = document.getElementById('filterType').value;

    if (!paperId) {
        document.getElementById('questionList').innerHTML = '<p class="text-muted">Select a paper to load questions...</p>';
        return;
    }

    let query = `paper_id=${paperId}`;
    if (topicId) query += `&topic_id=${topicId}`;
    if (subtopicId) query += `&subtopic_id=${subtopicId}`;
    if (type) query += `&type=${type}`;

    fetch(`/admin/mock-tests/questions-by-paper?${query}`)
        .then(res => res.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = '<p class="text-danger">No questions found for this filter.</p>';
            } else {
                data.forEach(q => {
                    const checked = selectedQuestions.has(q.id) ? 'checked' : '';
                    html += `
                        <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div class="form-check d-flex gap-2 w-100">
                                    <input class="form-check-input mt-1" type="checkbox" name="question_ids[]" value="${q.id}" id="q${q.id}" ${checked}
                                        onchange="handleSelection(this, '${q.topic_name}')">
                                    <label class="form-check-label w-100" for="q${q.id}">
                                        <div class="question-content">${q.question_text}</div>
                                        <small class="text-muted d-block mt-1">[${q.topic_name}]</small>
                                    </label>
                                </div>
                               <button type="button" class="icon-button" onclick="previewQuestion(${q.id})">
                                    <i class="bi bi-eye fs-6"></i>
                                </button>

                            </div>
                        </div>
                    `;
                });
            }
            document.getElementById('questionList').innerHTML = html;
        });
}

function handleSelection(input, topicName) {
    if (input.checked) {
        selectedQuestions.set(parseInt(input.value), topicName);
    } else {
        selectedQuestions.delete(parseInt(input.value));
    }
    updateQuestionSummary();
    document.getElementById('question_ids_serialized').value = JSON.stringify([...selectedQuestions.keys()]);
}

function updateQuestionSummary() {
    const counts = {};
    for (let topic of selectedQuestions.values()) {
        counts[topic] = (counts[topic] || 0) + 1;
    }

    const list = document.getElementById('topicCounts');
    list.innerHTML = '';
    Object.keys(counts).forEach(topic => {
        list.innerHTML += `<li>${topic}: ${counts[topic]} question(s)</li>`;
    });

    document.getElementById('questionSummary').style.display = selectedQuestions.size ? 'block' : 'none';
}

function previewQuestion(id) {
    const modal = new bootstrap.Modal(document.getElementById('questionPreviewModal'));
    document.getElementById('questionPreviewContent').innerHTML = `<p class="text-muted">Loading...</p>`;

    fetch(`/api/question-preview/${id}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('questionPreviewContent').innerHTML = html;
            modal.show();
        });
}

function redirectAfterSubmit() {
    document.getElementById('question_ids_serialized').value = JSON.stringify([...selectedQuestions.keys()]);
    setTimeout(() => {
        window.location.href = "/admin/mock-tests";
    }, 500);
    return true;
}
</script>
@endsection
