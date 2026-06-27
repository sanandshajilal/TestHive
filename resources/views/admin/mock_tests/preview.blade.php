@extends('layouts.app')

@section('title', 'Test Preview')

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

    /* Main Cards */

    .card-style {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.75rem;
        box-shadow: 0 2px 10px rgba(180,110,76,.08);
    }

    /* Question Card */

    .question-card {
        background: #ffffff;
        border: 1px solid #edd7ca;
        border-radius: 1rem;
        padding: 1.4rem 1.5rem;
        margin-bottom: 1.5rem;
        transition: all .2s ease;
    }

    .question-card:hover {
        box-shadow: 0 4px 12px rgba(180,110,76,.12);
        border-color: #d6b29d;
    }

    /* Section Divider */

    .border-bottom {
        border-color: #edd7ca !important;
    }

    /* Heading Icons */

    h5 i,
    h6 i {
        color: #832b00;
    }

    /* Edit Link */

    .edit-link {
        position: absolute;
        top: 1rem;
        right: 1.25rem;
        font-size: .875rem;
        color: #9a5631;
        text-decoration: none;
        transition: .2s;
    }

    .edit-link:hover {
        color: #832b00;
        text-decoration: none;
    }

    /* Question */

    .question-content {
        word-break: break-word;
        line-height: 1.7;
        color: #1f2937;
    }

    /* Meta */

    .question-meta {
        font-size: .85rem;
        color: #9a5631;
        border-top: 1px dashed #edd7ca;
        padding-top: .75rem;
        margin-top: 1rem;
        text-align: right;
    }

    /* Tables */

    .table thead {
        background: #fcf7f3;
    }

    .table thead th {
        color: #9a5631;
        border-bottom: 1px solid #edd7ca;
        font-weight: 600;
    }

    .table-bordered td,
    .table-bordered th {
        border-color: #edd7ca;
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

    /* Test Information */

    .card-style h4 {
        color: #832b00;
        font-weight: 700;
    }

    strong {
        color: #374151;
    }

    /* Empty State */

    .card-style p {
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-file-earmark-text me-2" style="color:#832b00;"></i>
                Test Preview
            </h5>

            <small class="text-muted">
                Review test details and all included questions.
            </small>
        </div>
        <a href="{{ route('mock-tests.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i>
            <span class="d-none d-md-inline ms-1">Back to All Tests</span>
        </a>
    </div>

    <!-- Test Info -->
    <div class="card-style mb-4">
        <h4 class="mb-3">{{ $mockTest->title }}</h4>
        <div class="row mb-2">
            <div class="col-md-6"><strong>Paper:</strong> {{ $mockTest->paper->name ?? 'N/A' }}</div>
            <div class="col-md-6"><strong>Access Code:</strong> {{ $mockTest->access_code }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6"><strong>Start Time:</strong> {{ $mockTest->start_time }}</div>
            <div class="col-md-6"><strong>End Time:</strong> {{ $mockTest->end_time }}</div>
        </div>
        <div class="row">
            <div class="col-md-6"><strong>Duration:</strong> {{ $mockTest->duration_minutes }} minutes</div>
            <div class="col-md-6">
                <strong>Batches:</strong>
                @if ($mockTest->batches && count($mockTest->batches))
                    @foreach($mockTest->batches as $batch)
                        {{ $batch->institute->name ?? '' }} - {{ $batch->name }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                @else
                    N/A
                @endif
            </div>
        </div>
    </div>

    <!-- Questions -->
    <div class="card-style">
        <div class="border-bottom pb-2 mb-4">
            <h5 class="fw-semibold mb-1">
                <i class="bi bi-journal-check me-2" style="color:#832b00;"></i>
                Question Bank
            </h5>

            <small class="text-muted">
                Total Questions: {{ count($mockTest->questions) }}
            </small>
        </div>

        @forelse($mockTest->questions as $index => $question)
            <div class="question-card position-relative">
                <!-- Edit Button -->
                <a href="{{ route('questions.edit', $question->id) }}" class="edit-link">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>

                <!-- Question Heading -->
                <h6 class="fw-semibold text-dark mb-2">Question {{ $index + 1 }}:</h6>

                <!-- Question Content -->
                <div class="question-content mb-3">
                    {!! str_replace('[blank]', '__________', $question->question_text) !!}
                </div>

                <!-- Answer/Option Blocks -->
                @if(in_array($question->question_type, ['mcq', 'multiple_select']) && is_array($question->options))
                    <ul class="mb-2">
                        @foreach($question->options as $key => $opt)
                            <li>{{ $key }}. {{ $opt }}</li>
                        @endforeach
                    </ul>
                    <p><strong>Correct Answer:</strong> {{ is_array($question->correct_answers) ? implode(', ', $question->correct_answers) : $question->correct_answers }}</p>

                @elseif($question->question_type === 'one_word')
                    <p><strong>Correct Answer:</strong> {{ is_array($question->correct_answers) ? implode(', ', $question->correct_answers) : $question->correct_answers }}</p>

                @elseif($question->question_type === 'table_mcq')
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Statement</th>
                                    <th>Correct Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($question->options as $i => $stmt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $stmt }}</td>
                                        <td>{{ $question->correct_answers[$i] ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($question->question_type === 'drag_and_drop')
                    @php
                        $opts = $question->options ?? [];
                        $colA = $opts['column_a'] ?? [];
                        $colB = $opts['column_b'] ?? [];
                        $correct = json_decode($question->correct_answers, true) ?? [];
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Column A</th>
                                    <th>Correct Match (Column B)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($colA as $i => $a)
                                    <tr>
                                        <td>{{ $a }}</td>
                                        <td>{{ $colB[$correct[$i]] ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($question->question_type === 'dropdown')
                    @php
                        $correct = is_array($question->correct_answers)
                            ? $question->correct_answers
                            : (json_decode($question->correct_answers, true) ?: []);
                        $dropdownOpts = is_array($question->options)
                            ? $question->options
                            : json_decode($question->options, true) ?? [];
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Dropdown #</th>
                                    <th>Options</th>
                                    <th>Correct Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($correct as $i => $ans)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ implode(', ', $dropdownOpts[$i]['options'] ?? []) }}</td>
                                        <td>{{ $ans }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Meta Line -->
                <div class="question-meta">
                    {{ $question->topic->name ?? 'Topic N/A' }} <i class="bi bi-arrow-right-short"></i>{{ $question->subTopic->name ?? 'Subtopic N/A' }} | {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                </div>
            </div>
        @empty
            <p>No questions found.</p>
        @endforelse
    </div>
</div>
@endsection
