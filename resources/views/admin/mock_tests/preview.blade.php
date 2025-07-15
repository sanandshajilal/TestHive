@extends('layouts.app')

@section('title', 'Mock Test Preview')

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

    .question-card {
        background-color: #fff;
        border: 1px solid #e3e6ea;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.025);
        margin-bottom: 1.5rem;
    }

    .question-meta {
        font-size: 0.875rem;
        color: #6c757d;
        border-top: 1px dashed #dee2e6;
        padding-top: 0.75rem;
        margin-top: 1rem;
        text-align: right;
    }

    .edit-link {
        font-size: 0.875rem;
        color: #6c757d;
        position: absolute;
        top: 1rem;
        right: 1.25rem;
        text-decoration: none;
    }

    .edit-link:hover {
        text-decoration: underline;
        color: #0d6efd;
    }

    .question-content {
        word-break: break-word;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark fw-semibold">Mock Test Preview</h5>
        <a href="{{ route('mock-tests.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Mock Tests
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
        <h5 class="mb-3">Total Questions: {{ count($mockTest->questions) }}</h5>

        @forelse($mockTest->questions as $index => $question)
            <div class="question-card position-relative">
                <!-- Edit Button -->
                <a href="{{ route('questions.edit', $question->id) }}" class="edit-link">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>

                <!-- Question Heading -->
                <h6 class="fw-semibold text-dark mb-2">Q{{ $index + 1 }}:</h6>

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
                    {{ $question->topic->name ?? 'Topic N/A' }} -> {{ $question->subTopic->name ?? 'Subtopic N/A' }} | {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                </div>
            </div>
        @empty
            <p>No questions found.</p>
        @endforelse
    </div>
</div>
@endsection
