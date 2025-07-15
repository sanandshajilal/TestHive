@extends('layouts.app')

@section('title', 'Edit Mock Test')

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

    .btn-primary,
    .btn-success {
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
        border: none;
        background: transparent;
        padding: 0;
        margin-left: 0.5rem;
        color: #6c757d;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .icon-button:hover {
        color: #0d6efd;
        transform: scale(1.2);
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark fw-semibold">Edit Mock Test</h5>
        <a href="{{ route('mock-tests.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Tests
        </a>
    </div>

    <div class="card-style">
        <form method="POST" action="{{ route('mock-tests.update', $mockTest->id) }}">
            @csrf
            @method('PUT')
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Paper</label>
                    <select name="paper_id" id="paper_id" class="form-select" required>
                        @foreach($papers as $paper)
                            <option value="{{ $paper->id }}" {{ $mockTest->paper_id == $paper->id ? 'selected' : '' }}>{{ $paper->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $mockTest->title) }}" required>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Batch</label>
                    <select name="batch_id" class="form-select" required>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ $mockTest->batches->first()->id == $batch->id ? 'selected' : '' }}>
                                {{ $batch->institute->name }} - {{ $batch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Start Time</label>
                    <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time', \Carbon\Carbon::parse($mockTest->start_time)->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">End Time</label>
                    <input type="datetime-local" name="end_time" class="form-control"
                        value="{{ old('end_time', \Carbon\Carbon::parse($mockTest->end_time)->format('Y-m-d\TH:i')) }}" required>
                </div>


                <div class="col-md-3">
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $mockTest->duration_minutes) }}" min="1" required>
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <h6 class="fw-bold mb-2">Filter Questions</h6>
                <div class="row g-2">
                    <div class="col-md-4">
                        <select name="topic_id" id="topic-select" class="form-select">
                            <option value="">-- Topic --</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select name="subtopic_id" id="subtopic-select" class="form-select">
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

            <div class="mb-3">
                <div class="alert alert-info" id="questionSummary" style="display:none;">
                    <strong>Selected Questions by Topic:</strong>
                    <ul class="mb-0" id="topicCounts" style="list-style: inside;"></ul>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold mb-2">Question Bank</h6>
                <p class="text-muted">Select the required questions from the list below:</p>
                <div id="question-list" class="border p-3 rounded bg-light" style="max-height: 400px; overflow-y: auto;"></div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success px-4">Update Test</button>
            </div>
        </form>
    </div>
</div>

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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const paperSelect = document.querySelector('select[name="paper_id"]');
    const topicSelect = document.getElementById("topic-select");
    const subtopicSelect = document.getElementById("subtopic-select");
    const typeSelect = document.getElementById("filterType");
    const questionList = document.getElementById("question-list");
    const questionSummary = document.getElementById("questionSummary");
    const topicCountsUl = document.getElementById("topicCounts");

    const selectedQuestionIds = new Set(@json($selectedQuestionIds));

        function fetchSubtopics(topicId) {
            subtopicSelect.innerHTML = '<option value="">-- Subtopic --</option>';
            if (!topicId) return;

            fetch(`/api/subtopics/${topicId}`) // ✅ Use correct API route
                .then(res => res.json())
                .then(data => {
                    data.forEach(subtopic => {
                        const opt = document.createElement("option");
                        opt.value = subtopic.id;
                        opt.textContent = subtopic.name;
                        subtopicSelect.appendChild(opt);
                    });
                });
        }


    function fetchQuestions() {
        const params = new URLSearchParams({
            paper_id: paperSelect.value,
            topic_id: topicSelect.value || '',
            subtopic_id: subtopicSelect.value || '',
            type: typeSelect.value || ''
        });

        fetch(`/admin/mock-tests/questions-by-paper?${params.toString()}`)
            .then(response => response.json())
            .then(questions => {
                questionList.innerHTML = "";
                const topicCountMap = {};

                if (questions.length === 0) {
                    questionList.innerHTML = `<p class="text-muted">No questions found.</p>`;
                    updateQuestionSummary(topicCountMap);
                    return;
                }

                questions.forEach(q => {
                    const isChecked = selectedQuestionIds.has(q.id);
                    if (isChecked) {
                        topicCountMap[q.topic_name] = (topicCountMap[q.topic_name] || 0) + 1;
                    }

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'question_ids[]';
                    checkbox.value = q.id;
                    checkbox.className = 'form-check-input me-2';
                    if (isChecked) checkbox.checked = true;

                    checkbox.addEventListener('change', function () {
                        if (this.checked) {
                            selectedQuestionIds.add(q.id);
                        } else {
                            selectedQuestionIds.delete(q.id);
                        }
                        updateQuestionSummaryFromState();
                    });

                    const div = document.createElement("div");
                    div.className = "border rounded bg-white p-2 mb-2";

                    div.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <div class="question-content">${q.question_text}</div>
                                    <small class="text-muted">(${q.topic_name}${q.subtopic_name ? ' → ' + q.subtopic_name : ''})</small>
                                </label>
                            </div>
                            <button type="button" class="icon-button preview-btn" data-id="${q.id}">
                                <i class="bi bi-eye fs-6"></i>
                            </button>
                        </div>
                    `;

                    div.querySelector('.form-check-label').prepend(checkbox);
                    questionList.appendChild(div);
                });

                updateQuestionSummary(topicCountMap);
                attachPreviewListeners();
            });
    }

    function updateQuestionSummary(counts) {
        topicCountsUl.innerHTML = "";
        const topics = Object.keys(counts);

        if (topics.length === 0) {
            questionSummary.style.display = "none";
            return;
        }

        topics.forEach(topic => {
            const li = document.createElement("li");
            li.textContent = `${topic}: ${counts[topic]} question(s)`;
            topicCountsUl.appendChild(li);
        });

        questionSummary.style.display = "block";
    }

    function updateQuestionSummaryFromState() {
        fetch(`/admin/mock-tests/questions-by-ids`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids: Array.from(selectedQuestionIds) })
        })
        .then(res => res.json())
        .then(data => {
            const counts = {};
            data.forEach(q => {
                counts[q.topic_name] = (counts[q.topic_name] || 0) + 1;
            });
            updateQuestionSummary(counts);
        });
    }

    function attachPreviewListeners() {
        document.querySelectorAll('.preview-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const questionId = this.getAttribute('data-id');
                fetch(`/api/question-preview/${questionId}`)
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById("questionPreviewContent").innerHTML = html;
                        const modal = new bootstrap.Modal(document.getElementById("questionPreviewModal"));
                        modal.show();
                    });
            });
        });
    }

    // Initial load (no preselected topic or subtopic)
    fetchSubtopics(null);
    fetchQuestions();
    updateQuestionSummaryFromState();

    // Event listeners
    paperSelect.addEventListener('change', fetchQuestions);

    topicSelect.addEventListener('change', function () {
        fetchSubtopics(topicSelect.value); // ✅ Now correctly fetches subtopics
        fetchQuestions();
    });

    subtopicSelect.addEventListener('change', fetchQuestions);
    typeSelect.addEventListener('change', fetchQuestions);
});
</script>


@endsection
