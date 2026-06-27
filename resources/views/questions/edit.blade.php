@extends('layouts.app')

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
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(180,110,76,.08);
    }

    /* Section Divider */

    .border-bottom {
        border-color: #edd7ca !important;
    }

    /* Labels */

    .form-label {
        font-weight: 600;
        color: #374151;
    }

    /* Inputs */

    .form-control,
    .form-select {
        border: 1px solid #d9d9d9;
        border-radius: .75rem;
        transition: all .2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #b46e4c;
        box-shadow: 0 0 0 .2rem rgba(180,110,76,.15);
    }

    /* Primary Button */

    .btn-success {
        background: #b46e4c;
        border-color: #b46e4c;
        color: #fff;
        border-radius: 50px;
        transition: .2s;
    }

    .btn-success:hover {
        background: #832b00;
        border-color: #832b00;
        color: #fff;
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
        color: #fff;
    }

    /* Alerts */

    .alert {
        border-radius: .75rem;
    }

    /* Validation */

    .text-danger {
        font-size: .9rem;
    }



    /* Section Icons */

    h5 i,
    h6 i {
        color: #832b00;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-pencil-square me-2" style="color:#832b00;"></i>
                Edit Question
            </h5>

            <small class="text-muted">
                Update and maintain questions in the question bank.
            </small>
        </div>
        <a href="{{ route('questions.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i>
            <span class="d-none d-md-inline ms-1">Back to Questions</span>
        </a>
    </div>

    <div class="card-style">
        <form method="POST" action="{{ route('questions.update', $question->id) }}">
            @csrf
            @method('PUT')
            <div class="border-bottom pb-2 mb-3">
                <h6 class="fw-semibold mb-1">
                    <i class="bi bi-sliders me-2" style="color:#832b00;"></i>
                    Question Configuration
                </h6>
            </div>
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

            <div class="border-bottom pb-2 mb-3 mt-4">
                <h6 class="fw-semibold mb-1">
                    <i class="bi bi-pencil-square me-2" style="color:#832b00;"></i>
                    Question Content
                </h6>
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

            <div class="border-bottom pb-2 mb-3 mt-4">
                <h6 class="fw-semibold mb-1">
                    <i class="bi bi-award me-2" style="color:#832b00;"></i>
                    Scoring
                </h6>
            </div>
            <div class="mb-3 mt-3">
                <label class="form-label">Marks</label>
                <input type="number" name="marks" class="form-control" value="{{ old('marks', $question->marks ?? 2) }}" min="1" required>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success rounded-pill">Update Question</button>
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
        paste_data_images: true,   // Enables image paste directly into editor
        paste_as_text: false,      // Keep formatting (important for table classes)
        table_class_list: [
            { title: 'Bootstrap Table', value: 'table table-bordered table-sm' }
        ],
        table_default_attributes: {
            border: '0'
        },
        table_default_styles: {
            width: '100%',
            borderCollapse: 'collapse'
        },
        setup: function (editor) {
            // Add Bootstrap table classes on insert
            editor.on('ExecCommand', function (e) {
                if (e.command === 'mceInsertTable') {
                    setTimeout(() => {
                        editor.dom.addClass(editor.dom.select('table'), 'table table-bordered table-sm');
                    }, 10);
                }
            });

            // Make pasted images responsive
            editor.on('PastePostProcess', function (e) {
                const imgs = e.node.querySelectorAll('img');
                imgs.forEach(img => {
                    img.removeAttribute('width');
                    img.removeAttribute('height');
                    img.style.maxWidth = '100%';
                    img.style.height = 'auto';
                });
            });

            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
</script>



<script src="{{ asset('js/question_form.js') }}"></script>
@endsection
