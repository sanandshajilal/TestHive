@extends('layouts.app')

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
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark fw-semibold">Edit Question</h5>
        <a href="{{ route('questions.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Questions
        </a>
    </div>

    <div class="card-style">
        <form method="POST" action="{{ route('questions.update', $question->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Select Paper</label>
                    <select class="form-select" name="paper_id" required>
                        <option value="">-- Select Paper --</option>
                        @foreach($papers as $paper)
                            <option value="{{ $paper->id }}" {{ $question->paper_id == $paper->id ? 'selected' : '' }}>
                                {{ $paper->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Select Topic</label>
                    <select class="form-select" name="topic_id" id="topic_id" required>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ $question->topic_id == $topic->id ? 'selected' : '' }}>
                                {{ $topic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Select Subtopic</label>
                    <select class="form-select" name="sub_topic_id" id="sub_topic_id">
                        <option value="">-- Optional Subtopic --</option>
                        @foreach($subtopics as $sub)
                            <option value="{{ $sub->id }}" {{ $question->sub_topic_id == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Question Type</label>
                    <select class="form-select" name="question_type" id="question_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="mcq" {{ $question->question_type == 'mcq' ? 'selected' : '' }}>MCQ</option>
                        <option value="multiple_select" {{ $question->question_type == 'multiple_select' ? 'selected' : '' }}>Multiple Select</option>
                        <option value="one_word" {{ $question->question_type == 'one_word' ? 'selected' : '' }}>One Word</option>
                        <option value="table_mcq" {{ $question->question_type == 'table_mcq' ? 'selected' : '' }}>Table MCQ</option>
                        <option value="drag_and_drop" {{ $question->question_type == 'drag_and_drop' ? 'selected' : '' }}>Drag and Drop</option>
                        <option value="dropdown" {{ $question->question_type == 'dropdown' ? 'selected' : '' }}>Drop Down List</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Question</label>
                <textarea class="form-control" name="question_text" id="question">{{ old('question_text', $question->question_text) }}</textarea>
                <noscript>
                    <textarea name="question_text" id="question" class="form-control" required>{{ old('question_text', $question->question_text) }}</textarea>
                </noscript>
            </div>
            @error('question_text')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror

                @php
                    $partial = in_array($question->question_type, ['mcq', 'multiple_select']) ? 'mcq' : $question->question_type;
                @endphp
                @includeIf('questions.partials.' . $partial)


            <div class="mb-3 mt-3">
                <label class="form-label">Marks</label>
                <input type="number" name="marks" class="form-control" value="{{ old('marks', $question->marks ?? 2) }}" min="1" required>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary rounded-pill">Update Question</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: 'textarea[name=question_text]',
        plugins: 'lists paste image table',
        toolbar: 'undo redo | bold italic underline | bullist numlist | image table',
        menubar: 'insert table format',
        paste_data_images: true,  // enables image paste directly into editor
        paste_as_text: true,
        elementpath: false,
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
</script>

<script src="{{ asset('js/question_form.js') }}"></script>
@endsection
