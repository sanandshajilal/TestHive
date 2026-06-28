@extends('layouts.app')

@section('title', 'Create Mock Test')

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

    /* Labels */

    .form-label {
        font-weight: 600;
        color: #374151;
    }

    /* Inputs */

    .form-control,
    .form-select {
        border-radius: .75rem;
        border: 1px solid #d9d9d9;
        padding: .7rem .9rem;
        transition: .2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #b46e4c;
        box-shadow: 0 0 0 .2rem rgba(180,110,76,.15);
    }

    /* Dropdown Arrow */

    .form-select {
        background-position: right .9rem center;
    }

    /* Section Headings */

    .border-bottom {
        border-color: #edd7ca !important;
    }

    h6 i {
        color: #832b00;
    }

    /* Question Bank */

    #questionList {
        background: #fcf7f3 !important;
        border: 1px solid #edd7ca !important;
        border-radius: .75rem;
    }

    #questionList .border {
        border: 1px solid #edd7ca !important;
    }

    #questionList .bg-white:hover {
        background: #fffaf7 !important;
    }

    /* Question Content */

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

    /* Preview Button */

    .icon-button {
        padding: 0;
        border: none;
        background: transparent;
        color: #9a5631;
        transition: all .2s ease;
    }

    .icon-button:hover {
        color: #832b00;
        transform: scale(1.1);
        cursor: pointer;
    }

    /* Summary Alert */

    .alert-info {
        background: #fcf7f3;
        border: 1px solid #edd7ca;
        color: #832b00;
    }

    /* Primary Button */

    .btn-success {
        background: #b46e4c;
        border-color: #b46e4c;
        border-radius: 50px;
        transition: .2s;
    }

    .btn-success:hover {
        background: #832b00;
        border-color: #832b00;
    }

    /* Secondary Button */

    .btn-secondary {
        background: #f7e3d8;
        border-color: #edd7ca;
        color: #832b00;
        border-radius: 50px;
        transition: .2s;
    }

    .btn-secondary:hover {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #ffffff;
    }

    /* Modal */

    .modal-content {
        border-radius: 1rem;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #edd7ca;
    }

    .modal-title {
        color: #832b00;
        font-weight: 600;
    }

    /* Scrollbar */

    #questionList::-webkit-scrollbar {
        width: 8px;
    }

    #questionList::-webkit-scrollbar-thumb {
        background: #d6b29d;
        border-radius: 20px;
    }

    #questionList::-webkit-scrollbar-thumb:hover {
        background: #b46e4c;
    }

    #summaryContent .badge{
    min-width:36px;
    font-weight:600;
    }

    #summaryContent .fw-semibold{
        color:#832b00;
    }

    #summaryContent .text-muted{
        font-size:.92rem;
    }

    #summaryContent .d-flex:hover{
        background:#fcf7f3;
        border-radius:.5rem;
    }

    .summary-badge{
        background:#f7e3d8;
        color:#832b00;
        border:1px solid #edd7ca;
        font-weight:600;
    }

    .topic-icon{
        color:#b46e4c;
    }

    .topic-badge{
        background:#fcf7f3;
        color:#832b00;
        border:1px solid #edd7ca;
        font-weight:500;
    }

    .subtopic-badge{
        background:#fff;
        color:#8b6b57;
        border:1px solid #edd7ca;
        font-weight:500;
    }

    .btn-soft-secondary{

        background:#fff;

        color:#832b00;

        border:1px solid #edd7ca;

        border-radius:50px;

        transition:.2s;

    }

    .btn-soft-secondary:hover{

        background:#b46e4c;

        color:#fff;

        border-color:#b46e4c;

    }

    .question-count-badge{

        background:#fcf7f3;

        color:#832b00;

        border:1px solid #edd7ca;

        font-weight:600;

    }

    .summary-count{

        background:#fcf7f3;

        color:#832b00;

        border:1px solid #edd7ca;

        min-width:36px;

    }

    /* Question checkbox */

    .form-check-input{

        border-color:#d7b29d;

        cursor:pointer;

    }

    .form-check-input:checked{

        background-color:#b46e4c;

        border-color:#b46e4c;

    }

    .form-check-input:focus{

        border-color:#b46e4c;

        box-shadow:0 0 0 .2rem rgba(180,110,76,.15);

    }



</style>
@endsection

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-pencil-square me-2" style="color:#832b00;"></i>
                Edit Test
            </h5>

            <small class="text-muted">
                Update test details and manage the selected question bank.
            </small>
        </div>

        <a href="{{ route('mock-tests.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i>
            <span class="d-none d-md-inline ms-1">Back to Tests</span>
        </a>
    </div>

    {{-- Form --}}
    <div class="card-style">

        <form method="POST" action="{{ route('mock-tests.update', $mockTest->id) }}">
            @csrf
            @method('PUT')

            <div class="border-bottom pb-2 mb-3">
                <h6 class="fw-semibold mb-1">
                    <i class="bi bi-gear me-2"></i>
                    Test Configuration
                </h6>
            </div>

            {{-- Paper and Title --}}
            <div class="row mb-4">

                <div class="col-md-6">
                    <label class="form-label">Paper</label>

                    <select name="paper_id" id="paper_id" class="form-select" required>

                        @foreach($papers as $paper)
                            <option value="{{ $paper->id }}"
                                {{ $mockTest->paper_id == $paper->id ? 'selected' : '' }}>
                                {{ $paper->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Title</label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="{{ old('title', $mockTest->title) }}"
                        required>
                </div>

            </div>

            {{-- Batch, Start Time, Duration --}}
            <div class="row mb-4">

                <div class="col-md-3">
                    <label class="form-label">Batch</label>

                    <select name="batch_id" class="form-select" required>

                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}"
                                {{ optional($mockTest->batches->first())->id == $batch->id ? 'selected' : '' }}>

                                {{ $batch->institute->name }} - {{ $batch->name }}

                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-3">

                    <label class="form-label">Start Time</label>

                    <input
                        type="datetime-local"
                        name="start_time"
                        class="form-control"
                        value="{{ old('start_time', \Carbon\Carbon::parse($mockTest->start_time)->format('Y-m-d\TH:i')) }}"
                        required>

                </div>

                <div class="col-md-3">

                    <label class="form-label">End Time</label>

                    <input
                        type="datetime-local"
                        name="end_time"
                        class="form-control"
                        value="{{ old('end_time', \Carbon\Carbon::parse($mockTest->end_time)->format('Y-m-d\TH:i')) }}"
                        required>

                </div>

                <div class="col-md-3">

                    <label class="form-label">Duration (minutes)</label>

                    <input
                        type="number"
                        name="duration_minutes"
                        class="form-control"
                        min="1"
                        value="{{ old('duration_minutes', $mockTest->duration_minutes) }}"
                        required>

                </div>

            </div>

            {{-- Filters --}}
            <div class="mb-3">

                <div class="border-bottom pb-2 mb-3 mt-4">

                    <h6 class="fw-semibold mb-1">
                        <i class="bi bi-funnel me-2"></i>
                        Question Filters
                    </h6>

                </div>

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

                <div id="questionSummary" class="card border-0 shadow-sm" style="display:none;">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-check2-square me-2"></i>
                                Selection Summary
                            </h6>

                            <span class="badge rounded-pill summary-badge" id="totalSelected">
                                0 Questions
                            </span>

                        </div>

                        <div id="summaryContent"></div>

                    </div>

                </div>

            </div>

            {{-- Question Bank --}}
            <input
                type="hidden"
                name="question_ids_serialized"
                id="question_ids_serialized">
            <div class="mb-4">

                <div class="border-bottom pb-2 mb-3 mt-4">
                    <h6 class="fw-semibold mb-1">
                        <i class="bi bi-journal-check me-2"></i>
                        Question Bank
                    </h6>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

                    <div>

                        <p class="text-muted mb-1">
                            Select the required questions from the list below.
                        </p>

                        <span class="badge question-count-badge" id="questionCountBadge">
                            0 Questions Found
                        </span>

                    </div>

                    <div class="d-flex gap-2 flex-wrap">

                        <button
                            type="button"
                            class="btn btn-soft-secondary btn-sm rounded-pill"
                            onclick="addFilteredQuestions()">

                            <i class="bi bi-check2-square me-1"></i>
                            Add All Filtered

                        </button>

                        <button
                            type="button"
                            class="btn btn-soft-secondary btn-sm rounded-pill"
                            onclick="removeFilteredQuestions()">

                            <i class="bi bi-x-circle me-1"></i>
                            Remove Filtered

                        </button>

                    </div>

                </div>

                <div
                    id="questionList"
                    class="p-3"
                    style="max-height:350px;overflow-y:auto;">

                    <p class="text-muted">
                        Loading questions...
                    </p>

                </div>

            </div>

            {{-- Submit --}}
            <div class="text-end">

                <button
                    type="submit"
                    class="btn btn-success px-4">

                    Update Test

                </button>

            </div>

        </form>

    </div>

</div>

{{-- Modal --}}
<div class="modal fade" id="questionPreviewModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Question Preview
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div
                class="modal-body"
                id="questionPreviewContent">

                <p class="text-muted">
                    Loading...
                </p>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')
<script>
const selectedQuestions = new Map();
let currentFilteredQuestions = [];

// Existing selected question ids
const existingQuestions = @json($selectedQuestions);

document.addEventListener("DOMContentLoaded", () => {



    document.getElementById('question_ids_serialized').value =
        JSON.stringify([...selectedQuestions.keys()]);

    updateQuestionSummary();

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

    // Load topics for existing paper
    loadTopics();

    // Load questions
    loadQuestions();
});

existingQuestions.forEach(q => {

    selectedQuestions.set(q.id, {

        topic: q.topic ? q.topic.name : 'Unknown',

        subtopic: q.sub_topic ? q.sub_topic.name : 'N/A'

    });

});

function loadTopics() {

    const paperId = document.getElementById('paper_id').value;
    const topicSelect = document.getElementById('filterTopic');
    const subtopicSelect = document.getElementById('filterSubtopic');

    topicSelect.innerHTML = '<option value="">-- Loading Topics... --</option>';
    subtopicSelect.innerHTML = '<option value="">-- Subtopic --</option>';

    if (!paperId) {

        topicSelect.innerHTML =
            '<option value="">-- Select Paper First --</option>';

        return;

    }

    fetch(`/api/topics/${paperId}`)
        .then(res => res.json())
        .then(data => {

            let options = '<option value="">-- Topic --</option>';

            data.forEach(topic => {

                options += `<option value="${topic.id}">
                                ${topic.name}
                            </option>`;

            });

            topicSelect.innerHTML = options;

        });

}

function loadSubtopics() {

    const topicId = document.getElementById('filterTopic').value;
    const subtopicSelect = document.getElementById('filterSubtopic');

    subtopicSelect.innerHTML =
        '<option value="">-- Loading... --</option>';

    if (!topicId) {

        subtopicSelect.innerHTML =
            '<option value="">-- Topic First --</option>';

        return;

    }

    fetch(`/api/subtopics/${topicId}`)
        .then(res => res.json())
        .then(data => {

            let options =
                '<option value="">-- Subtopic --</option>';

            data.forEach(subtopic => {

                options += `<option value="${subtopic.id}">
                                ${subtopic.name}
                            </option>`;

            });

            subtopicSelect.innerHTML = options;

        });

}

function loadQuestions() {

    const paperId = document.getElementById('paper_id').value;
    const topicId = document.getElementById('filterTopic').value;
    const subtopicId = document.getElementById('filterSubtopic').value;
    const type = document.getElementById('filterType').value;

    if (!paperId) {

        document.getElementById('questionList').innerHTML =
            '<p class="text-muted">Select a paper to load questions...</p>';

        document.getElementById('questionCountBadge').innerHTML =
            '0 Questions Found';

        currentFilteredQuestions = [];

        return;
    }

    let query = `paper_id=${paperId}`;

    if (topicId) query += `&topic_id=${topicId}`;
    if (subtopicId) query += `&subtopic_id=${subtopicId}`;
    if (type) query += `&type=${type}`;

    fetch(`/admin/mock-tests/questions-by-paper?${query}`)
        .then(res => res.json())
        .then(data => {

            currentFilteredQuestions = data;

            document.getElementById('questionCountBadge').innerHTML =
                `${data.length} Question${data.length !== 1 ? 's' : ''} Found`;

            let html = '';

            if (data.length === 0) {

                html = `
                    <div class="text-center py-5">
                        <i class="bi bi-search fs-1 text-muted"></i>
                        <p class="text-danger mt-3 mb-0">
                            No questions found.
                        </p>
                    </div>
                `;

            } else {

                data.forEach(q => {

                    const checked =
                        selectedQuestions.has(q.id) ? 'checked' : '';

                    html += `

                    <div class="border rounded p-3 mb-3 bg-white shadow-sm">

                        <div class="d-flex align-items-start justify-content-between gap-3">

                            <div class="form-check d-flex gap-2 w-100">

                                <input
                                    class="form-check-input mt-1"
                                    type="checkbox"
                                    id="q${q.id}"
                                    value="${q.id}"
                                    ${checked}
                                    onchange="handleSelection(this,'${q.topic_name}','${q.subtopic_name}')">

                                <label class="form-check-label w-100" for="q${q.id}">

                                    <div class="question-content">
                                        ${q.question_text}
                                    </div>

                                    <div class="mt-2">

                                        <span class="badge topic-badge me-2">
                                            ${q.topic_name}
                                        </span>

                                        <span class="badge subtopic-badge">
                                            ${q.subtopic_name}
                                        </span>

                                    </div>

                                </label>

                            </div>

                            <button
                                type="button"
                                class="icon-button"
                                onclick="previewQuestion(${q.id})">

                                <i class="bi bi-eye fs-6"></i>

                            </button>

                        </div>

                    </div>`;

                });

            }

            document.getElementById('questionList').innerHTML = html;

        });

}

function handleSelection(input, topicName, subtopicName) {

    const id = parseInt(input.value);

    if (input.checked) {

        selectedQuestions.set(id, {
            topic: topicName,
            subtopic: subtopicName
        });

    } else {

        selectedQuestions.delete(id);

    }

    document.getElementById('question_ids_serialized').value =
        JSON.stringify([...selectedQuestions.keys()]);

    updateQuestionSummary();

}

function addFilteredQuestions() {

    currentFilteredQuestions.forEach(q => {

        selectedQuestions.set(q.id, {
            topic: q.topic_name,
            subtopic: q.subtopic_name
        });

    });

    document.querySelectorAll('#questionList input[type="checkbox"]')
        .forEach(cb => cb.checked = true);

    document.getElementById('question_ids_serialized').value =
        JSON.stringify([...selectedQuestions.keys()]);

    updateQuestionSummary();

}

function removeFilteredQuestions() {

    currentFilteredQuestions.forEach(q => {

        selectedQuestions.delete(q.id);

    });

    document.querySelectorAll('#questionList input[type="checkbox"]')
        .forEach(cb => cb.checked = false);

    document.getElementById('question_ids_serialized').value =
        JSON.stringify([...selectedQuestions.keys()]);

    updateQuestionSummary();

}

function updateQuestionSummary() {

    const summary = {};

    selectedQuestions.forEach(question => {

        if (!summary[question.topic])
            summary[question.topic] = {};

        if (!summary[question.topic][question.subtopic])
            summary[question.topic][question.subtopic] = 0;

        summary[question.topic][question.subtopic]++;

    });

    const container = document.getElementById('summaryContent');

    container.innerHTML = '';

    if (selectedQuestions.size === 0) {

        document.getElementById('questionSummary').style.display = 'none';
        document.getElementById('totalSelected').innerHTML = '0 Questions';
        return;

    }

    document.getElementById('questionSummary').style.display = 'block';

    document.getElementById('totalSelected').innerHTML =
        `${selectedQuestions.size} Question${selectedQuestions.size !== 1 ? 's' : ''}`;

    let html = '';

    Object.keys(summary).sort().forEach(topic => {

        html += `<div class="mb-3">
            <div class="fw-semibold text-dark border-bottom pb-1 mb-2">
                <i class="bi bi-folder2-open me-2 topic-icon"></i>${topic}
            </div>`;

        Object.keys(summary[topic]).sort().forEach(sub => {

            html += `<div class="d-flex justify-content-between align-items-center ms-4 py-1">
                <span class="text-muted"><i class="bi bi-dot"></i>${sub}</span>
                <span class="badge summary-count">${summary[topic][sub]}</span>
            </div>`;

        });

        html += `</div>`;

    });

    container.innerHTML = html;

}

function previewQuestion(id) {

    const modal = new bootstrap.Modal(
        document.getElementById('questionPreviewModal')
    );

    document.getElementById('questionPreviewContent').innerHTML =
        `<p class="text-muted">Loading...</p>`;

    fetch(`/api/question-preview/${id}`)
        .then(res => res.text())
        .then(html => {

            document.getElementById('questionPreviewContent').innerHTML = html;

            modal.show();

        });

}

function redirectAfterSubmit() {

    document.getElementById('question_ids_serialized').value =
        JSON.stringify([...selectedQuestions.keys()]);

    return true;

}
</script>
@endsection
