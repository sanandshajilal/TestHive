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
            border-top: 4px solid var(--primary);
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
        RESULT SUMMARY
        ========================================================= */

        .result-summary-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            overflow: hidden;
        }


        /* ---------- Main Result ---------- */

        .result-main {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            align-items: stretch;
            min-height: 105px;
        }


        /* Percentage + Marks */

        .result-highlight {
            grid-column: span 1;
            text-align: center;
            padding: 1.25rem 1rem;
            border-right: 1px solid #edf0f2;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .result-score {
            font-size: 2.35rem;
            line-height: 1.1;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .result-highlight-label {
            margin-top: .35rem;
            font-size: .75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .5px;
        }


        /* ---------- Feedback ---------- */

        .result-feedback-box {
            grid-column: span 2;
            padding: 1.25rem 1.5rem;
            text-align: center;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .result-feedback-label {
            font-size: .75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: .4rem;
        }

        .result-feedback {
            font-size: .95rem;
            font-weight: 600;
            line-height: 1.45;
            color: var(--primary-dark);
        }


        /* ---------- Detailed Breakdown ---------- */

        .result-breakdown {
            display: flex;
            align-items: stretch;
            justify-content: center;
            border-top: 1px solid #edf0f2;
        }

        .result-stat {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 1rem;
            min-width: 140px;
            border-right: 1px solid #edf0f2;
        }

        .result-stat:last-child {
            border-right: none;
        }

        .result-stat-label {
            font-size: .75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .result-stat-value {
            margin-top: 2px;
            font-size: 1.1rem;
            font-weight: 700;
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

        .student-info-wrapper {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .student-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary-dark);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 1.8rem;
            flex-shrink: 0;
            border: 1px solid #ead9cf;
        }

        .student-info-layout {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .student-main-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 250px;
        }

        .student-info-divider {
            width: 1px;
            height: 58px;
            background: #e5e7eb;
            flex-shrink: 0;
        }

        .student-test-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            flex: 1;
        }

        .student-detail {
            min-width: 0;
        }

        .student-detail .info-label {
            margin-bottom: .25rem;
        }

        .student-detail .info-value {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            font-family: 'Segoe UI', sans-serif;
            font-size: 16px;
            font-weight: 400;
            line-height: 1.7;
            color: #1f2937;
        }

        .question-content * {
            font-family: 'Segoe UI', sans-serif !important;
            font-size: 16px !important;
        }

        .question-content strong,
        .question-content b {
            font-weight: 700;
        }

        .question-content em,
        .question-content i {
            font-style: italic;
        }

        .question-content u {
            text-decoration: underline;
        }

        .question-content img {
            max-width: 100%;
            height: auto;
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
            font-family: 'Segoe UI', sans-serif;
            font-size: 16px;
            line-height: 1.7;
        }

        .results-scenario-content * {
            font-family: 'Segoe UI', sans-serif !important;
            font-size: 16px !important;
        }

        .results-scenario-content strong,
        .results-scenario-content b {
            font-weight: 700;
        }

        .results-scenario-content em,
        .results-scenario-content i {
            font-style: italic;
        }

        .results-scenario-content u {
            text-decoration: underline;
        }

        .results-scenario-content img {
            max-width: 100%;
            height: auto;
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

        .response-title + .standalone-question {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
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

        @media (max-width: 767.98px) {

            .student-info-layout {
                display: block;
            }

            .student-main-info {
                margin-bottom: 1.25rem;
            }

            .student-info-divider {
                width: 100%;
                height: 1px;
                margin-bottom: 1.25rem;
            }

            .student-test-info {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

        }

        @media (max-width: 767.98px) {

            .result-main {
                grid-template-columns: 1fr 1fr;
            }

            .result-highlight {
                padding: 1rem .75rem;
            }

            .result-score {
                font-size: 1.9rem;
            }

            .result-feedback-box {
                grid-column: 1 / -1;
                border-top: 1px solid #edf0f2;
                padding: 1rem;
            }

            .result-breakdown {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .result-stat {
                min-width: 0;
                border-right: 1px solid #edf0f2;
                border-bottom: 1px solid #edf0f2;
            }

            .result-stat:nth-child(2n) {
                border-right: none;
            }

            .result-stat:nth-last-child(-n+2) {
                border-bottom: none;
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

        <div class="student-info-layout">

            {{-- Student --}}

            <div class="student-main-info">

                <div class="student-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div>

                    <div class="fs-5 fw-semibold">
                        {{ $attempt->student_name }}
                    </div>

                    <div class="text-muted small">
                        {{ $attempt->email }}
                    </div>
                </div>

            </div>


            {{-- Divider --}}

            <div class="student-info-divider"></div>


            {{-- Test Information --}}

            <div class="student-test-info">

                <div class="student-detail">

                    <div class="info-label">
                        Institute
                    </div>

                    <div class="info-value">
                        {{ $attempt->institute->name ?? '-' }}
                    </div>

                </div>


                <div class="student-detail">

                    <div class="info-label">
                        Batch
                    </div>

                    <div class="info-value">
                        {{ $attempt->batch->name ?? '-' }}
                    </div>

                </div>


                <div class="student-detail">

                    <div class="info-label">
                        Test
                    </div>

                    <div class="info-value">
                        {{ $attempt->mockTest->title ?? 'N/A' }}
                    </div>

                </div>


                <div class="student-detail">

                    <div class="info-label">
                        Duration
                    </div>

                    <div class="info-value">
                        {{ $attempt->mockTest->duration_minutes ?? '-' }} mins
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


    {{-- ========================================================= --}}
    {{-- Result Summary --}}
    {{-- ========================================================= --}}

    @php
        $percentage = $totalMarks > 0
            ? ($marksAwarded / $totalMarks) * 100
            : 0;

        if ($percentage >= 80) {

            $feedback = 'Excellent! Keep up the good work.';

        } elseif ($percentage >= 60) {

            $feedback = 'Good work! Keep up the consistency and aim for an even higher score.';

        } elseif ($percentage >= 50) {

            $feedback = 'Barely satisfactory. If you do not work harder, you will fail.';

        } elseif ($percentage >= 40) {

            $feedback = 'More practice is required. Focus on strengthening your concepts and solving as many questions as possible. You need to improve significantly to pass.';

        } else {

            $feedback = 'Immediate and serious improvement is required. Without a major change in your preparation, you will definitely fail.';

        }
    @endphp

    <div class="result-summary-card mb-4">

    {{-- Main Result --}}

    <div class="result-main">

        {{-- Percentage --}}

        <div class="result-highlight">

            <div class="result-score">
                {{ number_format($percentage, 1) }}%
            </div>

            <div class="result-highlight-label">
                Score
            </div>

        </div>


        {{-- Marks --}}

        <div class="result-highlight">

            <div class="result-score">
                {{ $marksAwarded }} / {{ $totalMarks }}
            </div>

            <div class="result-highlight-label">
                Marks
            </div>

        </div>


        {{-- Feedback --}}

        <div class="result-feedback-box">

            <div class="result-feedback">
                {{ $feedback }}
            </div>

        </div>

    </div>


    {{-- Detailed Statistics --}}

    <div class="result-breakdown">

        <div class="result-stat">

            <div>
                <div class="result-stat-label">
                    Total Questions
                </div>

                <div class="result-stat-value summary-total">
                    {{ $totalQuestions }}
                </div>
            </div>

        </div>


        <div class="result-stat">

            <div>
                <div class="result-stat-label">
                    Correct Answers
                </div>

                <div class="result-stat-value text-success">
                    {{ $correctCount }}
                </div>
            </div>

        </div>


        <div class="result-stat">

            <div>
                <div class="result-stat-label">
                    Wrong Answers
                </div>

                <div class="result-stat-value text-danger">
                    {{ $wrongCount }}
                </div>
            </div>

        </div>


        <div class="result-stat">

            <div>
                <div class="result-stat-label">
                    Not Attempted
                </div>

                <div class="result-stat-value text-secondary">
                    {{ $unattemptedCount }}
                </div>
            </div>

        </div>

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