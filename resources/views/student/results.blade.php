@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.student')

@section('title', 'Response Sheet')

@section('styles')
    <style>
        :root {
            --primary: #b46e4c;
            --primary-dark: #832b00;
            --primary-light: #f7e3d8;

            --success: #198754;
            --danger: #dc3545;
            --secondary: #6c757d;

            --surface: #ffffff;
            --border: #e5e7eb;
        }

        body {
            background-color: #f7f8fa;
            background-image:
                radial-gradient(#e9ecef 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* =========================================================
           HEADER
           ========================================================= */

        .header-box {
            background: #fff;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
        }

        .header-box h4 {
            color: var(--primary-dark);
            font-weight: 700;
        }

        .btn-back {
            border: 1px solid #e5d2c8;
            background: #fff;
            color: var(--primary-dark);
            border-radius: 999px;
        }

        .btn-back:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-dark);
        }

        /* =========================================================
           SCORE BANNER
           ========================================================= */

        .score-banner {
            background: linear-gradient(
                135deg,
                var(--primary),
                var(--primary-dark)
            );
            color: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        .score-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }

        .score-label {
            margin-top: .4rem;
            opacity: .9;
        }

        /* =========================================================
           CARDS
           ========================================================= */

        .card-style {
            border-radius: 1rem;
            background: #fff;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
        }

        .question-card {
            background: #fff;
            border: 1px solid #edf0f2;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all .2s ease;
        }

        .question-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, .04);
        }

        /* =========================================================
           STUDENT INFO
           ========================================================= */

        .info-label {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6b7280;
            margin-bottom: .25rem;
        }

        .info-value {
            font-weight: 600;
            color: #111827;
        }

        /* =========================================================
           SUMMARY
           ========================================================= */

        .summary-card {
            height: 100%;
            padding: 1rem .75rem;
        }

        .summary-total {
            color: var(--primary-dark);
        }

        .summary-marks {
            color: var(--primary);
        }

        /* =========================================================
           QUESTION NUMBER
           ========================================================= */

        .question-number {
            display: inline-block;
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 4px 10px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 600;
            margin-bottom: .75rem;
        }

        .question-content {
            line-height: 1.7;
        }

        .question-content p {
            margin-bottom: .5rem;
        }

        /* =========================================================
           QUESTION TABLES
           ========================================================= */

        .question-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0;
            font-size: .95rem;
        }

        .question-content th {
            background: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
            border: 1px solid #d9d9d9;
            padding: 10px 12px;
        }

        .question-content td {
            border: 1px solid #e3e3e3;
            padding: 10px 12px;
        }

        .question-content tr:nth-child(even) {
            background: #fafafa;
        }

        .question-content td:first-child {
            font-weight: 600;
        }

        .question-content th:not(:first-child),
        .question-content td:not(:first-child) {
            text-align: right;
        }

        /* =========================================================
           BADGES
           ========================================================= */

        .badge-correct {
            background: #e8f7ee;
            color: var(--success);
            border-radius: 999px;
            padding: .45rem .8rem;
            font-weight: 600;
        }

        .badge-wrong {
            background: #fdecec;
            color: var(--danger);
            border-radius: 999px;
            padding: .45rem .8rem;
            font-weight: 600;
        }

        .badge-unattempted {
            background: #f1f3f5;
            color: var(--secondary);
            border-radius: 999px;
            padding: .45rem .8rem;
            font-weight: 600;
        }

        /* =========================================================
           TABLES
           ========================================================= */

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: var(--primary-light);
        }

        .table thead th {
            color: var(--primary-dark);
            font-weight: 600;
            border-bottom: none;
        }

        .table-bordered {
            border-color: #e5e7eb;
        }

        .table-light {
            background: var(--primary-light) !important;
        }

        /* =========================================================
           OPTION LIST
           ========================================================= */

        .list-group-item {
            border-color: #edf0f2;
        }

        /* =========================================================
           RESULTS PAGE - SCENARIO
           ========================================================= */

        .results-scenario {
            background: #fffaf7;
            border: 1px solid #ead9cf;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 30px;
        }

        .results-scenario-header {
            padding: 13px 18px;
            background: #f8eee8;
            border-bottom: 1px solid #ead9cf;
        }

        .results-scenario-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;

            background: #ffffff;
            color: #832b00;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-right: 12px;

            border: 1px solid #ead9cf;
        }

        .results-scenario-title {
            font-weight: 600;
            color: #832b00;
            font-size: 1rem;
        }

        .results-scenario-subtitle {
            font-size: .78rem;
            color: #7b6f68;
            margin-top: 1px;
        }

        .results-scenario-content {
            padding: 20px 22px;
            color: #292522;
            font-size: .96rem;
            line-height: 1.7;
        }

        .results-scenario-content p:last-child {
            margin-bottom: 0;
        }

        .results-scenario-content table {
            max-width: 100%;
        }

        .results-scenario-content img {
            max-width: 100%;
            height: auto;
        }

        /* =========================================================
           SCENARIO CHILD QUESTIONS
           ========================================================= */

        .scenario-questions {
            margin-bottom: 2rem;
        }

        .scenario-question-label {
            color: var(--primary-dark);
            font-size: .82rem;
            font-weight: 600;

            padding: 8px 4px 10px;

            border-bottom: 1px solid #ead9cf;
            margin-bottom: 4px;
        }

        .scenario-child-question {
            padding: 1rem 0;
            border-bottom: 1px solid #edf0f2;
        }

        .scenario-child-question:last-child {
            border-bottom: none;
        }

        /* =========================================================
           STANDALONE QUESTIONS
           ========================================================= */

        .standalone-question {
            border-top: 2px solid #e5e7eb;
            padding-top: 1.5rem;
            margin-top: 1rem;
        }

        /* =========================================================
           RESPONSE SHEET TITLE
           ========================================================= */

        .response-title {
            color: var(--primary-dark);
            font-weight: 700;
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: .75rem;
            margin-bottom: 1.5rem;
        }

        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (min-width: 768px) {

            .min-w-md-0 {
                min-width: 0 !important;
            }

        }

        @media (max-width: 767.98px) {

            .back-label {
                display: none !important;
            }

            .score-value {
                font-size: 1.6rem;
            }

            .question-card {
                padding: 1rem;
            }

            .results-scenario-content {
                padding: 16px 18px;
            }

        }
    </style>
@endsection


@section('content')

@if(session('info'))

    <div
        id="infoAlert"
        class="alert alert-success alert-dismissible fade show mt-3"
        role="alert"
    >
        {{ session('info') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>
    </div>

    <script>
        setTimeout(() => {

            const alertEl = document.getElementById('infoAlert');

            if (alertEl) {

                alertEl.classList.remove('show');
                alertEl.classList.add('fade');

                setTimeout(() => alertEl.remove(), 150);

            }

        }, 5000);
    </script>

@endif


<div class="container py-4">

    {{-- ========================================================= --}}
    {{-- Page Header --}}
    {{-- ========================================================= --}}

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <h4 class="fw-bold mb-0">

            <i class="bi bi-award me-2"></i>

            Test Results

        </h4>

        <a
            href="{{ route('student.index') }}"
            class="btn btn-back"
        >

            <i class="bi bi-arrow-left me-1"></i>

            <span class="back-label">
                Back to Home
            </span>

        </a>

    </div>


    @php
        $studentAnswers = $answers->keyBy('question_id');
    @endphp


    {{-- ========================================================= --}}
    {{-- Student & Test Information --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 rounded-4 mb-4">

        <div class="card-body">

            <div class="row gy-3">

                <div class="col-md-4">

                    <strong>Student:</strong><br>

                    {{ $attempt->student_name }}

                </div>

                <div class="col-md-4">

                    <strong>Email:</strong><br>

                    {{ $attempt->email }}

                </div>

                <div class="col-md-4">

                    <strong>Institute:</strong><br>

                    {{ $attempt->institute->name ?? '-' }}

                </div>

                <div class="col-md-4">

                    <strong>Batch:</strong><br>

                    {{ $attempt->batch->name ?? '-' }}

                </div>

                <div class="col-md-4">

                    <strong>Test:</strong><br>

                    {{ $attempt->mockTest->title ?? 'N/A' }}

                </div>

                <div class="col-md-4">

                    <strong>Duration:</strong><br>

                    {{ $attempt->mockTest->duration_minutes ?? '-' }} mins

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Result Summary --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 rounded-4 mb-4">

        <div class="card-body d-flex justify-content-around flex-wrap text-center gap-3">

            @foreach([

                [
                    'label' => 'Total',
                    'value' => $totalQuestions,
                    'icon' => 'list-check',
                    'class' => 'summary-total'
                ],

                [
                    'label' => 'Correct',
                    'value' => $correctCount,
                    'icon' => 'check-circle',
                    'class' => 'text-success'
                ],

                [
                    'label' => 'Wrong',
                    'value' => $wrongCount,
                    'icon' => 'x-circle',
                    'class' => 'text-danger'
                ],

                [
                    'label' => 'Not Attempted',
                    'value' => $unattemptedCount,
                    'icon' => 'dash-circle',
                    'class' => 'text-secondary'
                ],

                [
                    'label' => 'Marks',
                    'value' => "$marksAwarded / $totalMarks",
                    'icon' => 'award',
                    'class' => 'summary-marks'
                ],

            ] as $stat)

                <div
                    class="px-3 py-2 flex-fill text-center"
                    style="min-width: 120px;"
                >

                    <i
                        class="bi bi-{{ $stat['icon'] }} {{ $stat['class'] }} fs-5"
                    ></i>

                    <div class="small text-muted mt-1">
                        {{ $stat['label'] }}
                    </div>

                    <div class="fw-bold {{ $stat['class'] }}">
                        {{ $stat['value'] }}
                    </div>

                </div>

            @endforeach

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Response Sheet - Opening --}}
    {{-- ========================================================= --}}

    <div class="card-style">

        <h5 class="response-title">

            <i class="bi bi-journal-check me-2"></i>

            Response Sheet

        </h5>

                {{-- ========================================================= --}}
        {{-- Questions --}}
        {{-- ========================================================= --}}

        @php
            $displayQuestionNumber = 1;
        @endphp

        @forelse ($questions as $question)

            @if($question->question_type === 'paragraph')

                {{-- ================================================= --}}
                {{-- Scenario Reference --}}
                {{-- ================================================= --}}

                <div class="results-scenario mb-3">

                    <div class="results-scenario-header">

                        <div class="d-flex align-items-center">

                            <div class="results-scenario-icon">

                                <i class="bi bi-file-earmark-text"></i>

                            </div>

                            <div>

                                <div class="results-scenario-title">
                                    Scenario
                                </div>

                                <div class="results-scenario-subtitle">
                                    Reference information for the questions below
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="results-scenario-content">

                        {!! $question->question_text !!}

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- Scenario Child Questions --}}
                {{-- ================================================= --}}

                <div class="scenario-questions">

                    <div class="scenario-question-label">

                        <i class="bi bi-link-45deg me-1"></i>

                        {{ $question->children->count() }}
                        {{ Str::plural('question', $question->children->count()) }}
                        based on this scenario

                    </div>

                    @foreach($question->children as $child)

                        <div class="scenario-child-question">

                            {{-- Question Number --}}

                            <div class="question-number">

                                Question {{ $displayQuestionNumber }}

                            </div>


                            {{-- Question Text --}}

                            <div class="question-content mb-3">

                                {!! $child->question_text !!}

                            </div>


                            {{-- Answer / Correct Answer / Selected Answer --}}

                            @php
                                $answer = $studentAnswers->get($question->id);
                            @endphp

                            @include(
                                'student.partials.results_question_renderer',
                                [
                                    'question' => $child,
                                    'answer' => $studentAnswers->get($child->id)
                                ]
                            )

                        </div>


                        @php
                            $displayQuestionNumber++;
                        @endphp

                    @endforeach

                </div>


            @else

                {{-- ================================================= --}}
                {{-- Standalone Question --}}
                {{-- ================================================= --}}

                <div class="standalone-question">

                    {{-- Question Number --}}

                    <div class="question-number">

                        Question {{ $displayQuestionNumber }}

                    </div>


                    @php

                        $displayQuestion = $question->question_text;

                        if ($question->question_type === 'dropdown') {

                            $displayQuestion = preg_replace(
                                '/\[blank\]/i',
                                '<u class="text-muted">__________</u>',
                                $question->question_text
                            );

                        }

                    @endphp


                    {{-- Question Text --}}

                    <div class="question-content mb-3">

                        {!! $displayQuestion !!}

                    </div>


                    {{-- Answer / Correct Answer / Selected Answer --}}

                    @php
                        $answer = $studentAnswers->get($question->id);
                    @endphp

                    @include(
                        'student.partials.results_question_renderer',
                        [
                            'question' => $question,
                            'answer' => $answer
                        ]
                    )

                </div>


                @php
                    $displayQuestionNumber++;
                @endphp

            @endif


        @empty

            <p class="text-muted">
                No questions found.
            </p>

        @endforelse

    </div>

</div>

@endsection