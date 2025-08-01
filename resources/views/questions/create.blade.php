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

    .btn-primary {
        border-radius: 50px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark fw-semibold">Create Question</h5>
        <a href="{{ route('questions.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Questions
        </a>
    </div>

    <div class="card-style">
        <form method="POST" action="{{ route('questions.store') }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="paper_id" class="form-label">Select Paper</label>
                    <select class="form-select" name="paper_id" id="paper_id" required>
                        <option value="">-- Select Paper --</option>
                        @foreach($papers as $paper)
                            <option value="{{ $paper->id }}" {{ old('paper_id') == $paper->id ? 'selected' : '' }}>
                                {{ $paper->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="topic_id" class="form-label">Select Topic</label>
                    <select class="form-select" name="topic_id" id="topic_id" required>
                        <option value="">-- Select Topic --</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                {{ $topic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="sub_topic_id" class="form-label">Select Sub Topic</label>
                    <select class="form-select" name="sub_topic_id" id="sub_topic_id">
                        <option value="">-- Optional Sub Topic --</option>
                        @foreach($subtopics as $sub)
                            <option value="{{ $sub->id }}" {{ old('sub_topic_id') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="question_type" class="form-label">Question Type</label>
                    <select class="form-select" name="question_type" id="question_type" required>
                        <option value="">-- Select --</option>
                        <option value="mcq" {{ old('question_type') == 'mcq' ? 'selected' : '' }}>MCQ</option>
                        <option value="multiple_select" {{ old('question_type') == 'multiple_select' ? 'selected' : '' }}>Multiple Select</option>
                        <option value="one_word" {{ old('question_type') == 'one_word' ? 'selected' : '' }}>One Word</option>
                        <option value="table_mcq" {{ old('question_type') == 'table_mcq' ? 'selected' : '' }}>Table MCQ</option>
                        <option value="drag_and_drop" {{ old('question_type') == 'drag_and_drop' ? 'selected' : '' }}>Drag and Drop</option>
                        <option value="dropdown" {{ old('question_type') == 'dropdown' ? 'selected' : '' }}>Drop Down List</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Question</label>
                <textarea class="form-control" name="question_text" id="question" required>{{ old('question_text') }}</textarea>
            </div>
            @error('question_text')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror


            {{-- Include partials --}}
            @include('questions.partials.mcq')
            @include('questions.partials.one_word')
            @include('questions.partials.table_mcq')
            @include('questions.partials.drag_and_drop')
            @include('questions.partials.dropdown')

            <div class="mb-3">
                <label class="form-label">Marks</label>
                <input type="number" name="marks" class="form-control" value="{{ old('marks', 2) }}" min="1" required>
            </div>

            <button type="submit" class="btn btn-primary">Save Question</button>
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
