@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Student Response Sheet')

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


    /* =========================================================
       PAGE
       ========================================================= */

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

        box-shadow:
            0 4px 12px rgba(0, 0, 0, .04);

    }


    /* =========================================================
       STUDENT / TEST INFORMATION
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
       RESULT BADGES
       ========================================================= */

.badge-correct,
.badge-wrong,
.badge-unattempted {
    display: inline-block;
    border-radius: 999px;
    padding: .25rem .55rem;
    font-size: .75rem;
    font-weight: 600;
    line-height: 1.2;
    white-space: nowrap;
}

.badge-correct {
    background: #e8f7ee;
    color: var(--success);
    border: 1px solid #ccebd8;
}

.badge-wrong {
    background: #fdecec;
    color: var(--danger);
    border: 1px solid #f3cccc;
}

.badge-unattempted {
    background: #f1f3f5;
    color: var(--secondary);
    border: 1px solid #dee2e6;
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
       SCENARIO
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

<div class="container py-4">


    {{-- ========================================================= --}}
    {{-- Page Header --}}
    {{-- ========================================================= --}}

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <h4 class="fw-bold mb-0">

            <i class="bi bi-file-earmark-text me-2"></i>

            Student Response Sheet

        </h4>


        <a
            href="{{ route('mock-tests.results', $attempt->mock_test_id) }}"
            class="btn btn-back"
        >

            <i class="bi bi-arrow-left me-1"></i>

            <span class="back-label">
                Back to Results
            </span>

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- Student Answers --}}
    {{-- ========================================================= --}}

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
    {{-- Response Sheet --}}
    {{-- ========================================================= --}}

    <div class="card-style">

        <h5 class="response-title">

            <i class="bi bi-journal-check me-2"></i>

            Questions & Answers

        </h5>

                {{-- ========================================================= --}}
        {{-- Questions --}}
        {{-- ========================================================= --}}

        @php
            $displayQuestionNumber = 1;
        @endphp

        @forelse ($questions as $question)

            {{-- ===================================================== --}}
            {{-- SCENARIO QUESTION --}}
            {{-- ===================================================== --}}

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

                        @php

                            $answer = $studentAnswers->get($child->id);

                            $raw = $answer->selected_option ?? '';

                            /*
                            |--------------------------------------------------------------------------
                            | Decode Student Answer
                            |--------------------------------------------------------------------------
                            */

                            $studentAns = (
                                Str::startsWith($raw, '[') ||
                                Str::startsWith($raw, '{')
                            )
                                ? json_decode($raw, true)
                                : $raw;


                            /*
                            |--------------------------------------------------------------------------
                            | Display Student Answer
                            |--------------------------------------------------------------------------
                            */

                            if (is_array($studentAns)) {

                                $studentAnsDisplay = collect($studentAns)
                                    ->map(function ($val, $key) {

                                        return is_numeric($key)
                                            ? $val
                                            : "{$key} → {$val}";

                                    })
                                    ->implode(', ');

                            } else {

                                $studentAnsDisplay = $studentAns ?: '-';

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Correct Answers
                            |--------------------------------------------------------------------------
                            */

                            $correctAns = $child->correct_answers;

                            $correctArray = is_array($correctAns)
                                ? $correctAns
                                : (json_decode($correctAns, true) ?: [$correctAns]);


                            $correctAnsDisplay = collect($correctArray)
                                ->map(function ($val, $key) {

                                    return is_numeric($key)
                                        ? $val
                                        : "{$key} → {$val}";

                                })
                                ->implode(', ');


                            /*
                            |--------------------------------------------------------------------------
                            | Result Status
                            |--------------------------------------------------------------------------
                            */

                            $isCorrect = $answer && $answer->is_correct;

                            $isNotAttempted =
                                !$answer ||
                                is_null($answer->selected_option) ||
                                (
                                    is_array($studentAns) &&
                                    empty(
                                        array_filter(
                                            $studentAns,
                                            fn($v) =>
                                                !is_null($v) &&
                                                $v !== ''
                                        )
                                    )
                                );

                        @endphp


                        <div class="scenario-child-question">

                            {{-- ===================================== --}}
                            {{-- Question Number --}}
                            {{-- ===================================== --}}

                            <div class="question-number">

                                Question {{ $displayQuestionNumber }}

                            </div>


                            {{-- ===================================== --}}
                            {{-- Question Text --}}
                            {{-- ===================================== --}}

                            @php

                                $displayQuestion = $child->question_text;

                                if ($child->question_type === 'dropdown') {

                                    $displayQuestion = preg_replace(
                                        '/\[blank\]/i',
                                        '<u class="text-muted">__________</u>',
                                        $child->question_text
                                    );

                                }

                            @endphp


                            <div class="question-content mb-3">

                                {!! $displayQuestion !!}

                            </div>


                            {{-- ===================================== --}}
                            {{-- MCQ / Multiple Select --}}
                            {{-- ===================================== --}}

                            @if(
                                in_array(
                                    $child->question_type,
                                    ['mcq', 'multiple_select']
                                )
                                &&
                                is_array($child->options)
                            )

                                <ul class="list-group mb-2">

                                    @foreach($child->options as $key => $option)

                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center
                                            @if(in_array($key, (array) $studentAns))
                                                bg-light
                                            @endif"
                                        >

                                            <span>
                                                {{ $key }}. {{ $option }}
                                            </span>


                                            <span>

                                                @if(
                                                    in_array(
                                                        $key,
                                                        (array) $child->correct_answers
                                                    )
                                                )

                                                    <span class="badge-correct">
                                                        Correct
                                                    </span>

                                                @endif


                                                @if(
                                                    in_array(
                                                        $key,
                                                        (array) $studentAns
                                                    )
                                                )

                                                    <span
                                                        class="badge ms-1 border"
                                                        style="
                                                            background:#fdf6f2;
                                                            color:#832b00;
                                                            border-color:#e5d2c8 !important;
                                                        "
                                                    >
                                                        Selected
                                                    </span>

                                                @endif

                                            </span>

                                        </li>

                                    @endforeach

                                </ul>


                            {{-- ===================================== --}}
                            {{-- One Word --}}
                            {{-- ===================================== --}}

                            @elseif($child->question_type === 'one_word')

                                <div class="mb-2">

                                    <strong>
                                        Student's Answer:
                                    </strong>

                                    {{ $studentAnsDisplay ?: '—' }}

                                </div>


                                <div class="mb-2">

                                    <strong>
                                        Correct Answer:
                                    </strong>

                                    {{ $correctAnsDisplay }}

                                </div>


                            {{-- ===================================== --}}
                            {{-- Table MCQ --}}
                            {{-- ===================================== --}}

                            @elseif($child->question_type === 'table_mcq')

                                @php

                                    $correctAnswers =
                                        is_array($child->correct_answers)
                                            ? $child->correct_answers
                                            : json_decode(
                                                $child->correct_answers,
                                                true
                                            );

                                    $tableStudentAnswers =
                                        is_array($studentAns)
                                            ? $studentAns
                                            : [];

                                @endphp


                                <div class="table-responsive mb-2">

                                    <table class="table table-bordered align-middle">

                                        <thead class="table-light">

                                            <tr>

                                                <th width="50">
                                                    #
                                                </th>

                                                <th>
                                                    Statement
                                                </th>

                                                <th>
                                                    Student's Answer
                                                </th>

                                                <th>
                                                    Correct Answer
                                                </th>

                                                <th width="90">
                                                    Result
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach($child->options as $i => $stmt)

                                                @php

                                                    $studentValue =
                                                        $tableStudentAnswers[$i] ?? null;

                                                    $correctValue =
                                                        $correctAnswers[$i] ?? null;

                                                    $rowCorrect =
                                                        !is_null($studentValue)
                                                        &&
                                                        strtolower(
                                                            (string) $studentValue
                                                        )
                                                        ===
                                                        strtolower(
                                                            (string) $correctValue
                                                        );

                                                @endphp


                                                <tr>

                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>


                                                    <td>
                                                        {{ $stmt }}
                                                    </td>


                                                    <td>

                                                        @if($studentValue)

                                                            {{ ucfirst((string) $studentValue) }}

                                                        @else

                                                            <span class="text-muted">
                                                                —
                                                            </span>

                                                        @endif

                                                    </td>


                                                    <td>

                                                        {{ ucfirst((string) $correctValue) }}

                                                    </td>


                                                    <td class="text-center">

                                                        @if(!$studentValue)

                                                            <span class="badge bg-secondary">
                                                                —
                                                            </span>

                                                        @elseif($rowCorrect)

                                                            <span class="badge bg-success">
                                                                ✓
                                                            </span>

                                                        @else

                                                            <span class="badge bg-danger">
                                                                ✕
                                                            </span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>


                            {{-- ===================================== --}}
                            {{-- Drag & Drop --}}
                            {{-- ===================================== --}}

                            @elseif($child->question_type === 'drag_and_drop')

                                @php

                                    $options = $child->options ?? [];

                                    $aLabel =
                                        $options['column_a_label']
                                        ?? 'Column A';

                                    $bLabel =
                                        $options['column_b_label']
                                        ?? 'Column B';

                                    $colA =
                                        $options['column_a']
                                        ?? [];

                                    $colB =
                                        $options['column_b']
                                        ?? [];

                                    $correct =
                                        is_array($child->correct_answers)
                                            ? $child->correct_answers
                                            : (
                                                json_decode(
                                                    $child->correct_answers,
                                                    true
                                                ) ?? []
                                            );

                                @endphp


                                <div class="table-responsive mb-2">

                                    <table class="table table-bordered table-sm">

                                        <thead>

                                            <tr>

                                                <th>
                                                    {{ $aLabel }}
                                                </th>

                                                <th>
                                                    Student's Match
                                                </th>

                                                <th>
                                                    Correct Match
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach($colA as $i => $item)

                                                @php

                                                    $studentMatchIndex =
                                                        $studentAns[$i] ?? null;

                                                    $correctMatchIndex =
                                                        $correct[$i] ?? null;

                                                @endphp


                                                <tr>

                                                    <td>
                                                        {{ $item }}
                                                    </td>


                                                    <td>

                                                        {{ $colB[$studentMatchIndex] ?? '—' }}


                                                        @if(
                                                            $studentMatchIndex !== null
                                                            &&
                                                            (string) $studentMatchIndex
                                                            ===
                                                            (string) $correctMatchIndex
                                                        )

                                                            <span class="badge bg-success text-white ms-1">

                                                                <i class="bi bi-check-circle-fill"></i>

                                                            </span>

                                                        @elseif($studentMatchIndex !== null)

                                                            <span class="badge bg-danger text-white ms-1">

                                                                <i class="bi bi-x-circle-fill"></i>

                                                            </span>

                                                        @endif

                                                    </td>


                                                    <td>

                                                        {{ $colB[$correctMatchIndex] ?? '—' }}

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>


                            {{-- ===================================== --}}
                            {{-- Dropdown --}}
                            {{-- ===================================== --}}

                            @elseif($child->question_type === 'dropdown')

                                @php

                                    $correct =
                                        is_array($child->correct_answers)
                                            ? $child->correct_answers
                                            : (
                                                json_decode(
                                                    $child->correct_answers,
                                                    true
                                                ) ?: []
                                            );


                                    $dropdownOptionsRaw =
                                        $child->options ?? [];


                                    $dropdownOptions =
                                        is_array($dropdownOptionsRaw)
                                            ? $dropdownOptionsRaw
                                            : json_decode(
                                                $dropdownOptionsRaw,
                                                true
                                            );


                                    if (!is_array($dropdownOptions)) {

                                        $dropdownOptions = [];

                                    }

                                @endphp


                                <div class="table-responsive mt-3">

                                    <table class="table table-bordered table-sm">

                                        <thead>

                                            <tr>

                                                <th>
                                                    Dropdown #
                                                </th>

                                                <th>
                                                    Options Given
                                                </th>

                                                <th>
                                                    Correct Answer
                                                </th>

                                                <th>
                                                    Student's Answer
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach($correct as $i => $corr)

                                                <tr>

                                                    <td>
                                                        {{ $i + 1 }}
                                                    </td>


                                                    <td>

                                                        @if(
                                                            isset(
                                                                $dropdownOptions[$i]['options']
                                                            )
                                                            &&
                                                            is_array(
                                                                $dropdownOptions[$i]['options']
                                                            )
                                                        )

                                                            {{ implode(
                                                                ', ',
                                                                $dropdownOptions[$i]['options']
                                                            ) }}

                                                        @else

                                                            —

                                                        @endif

                                                    </td>


                                                    <td>

                                                        {{ ucfirst((string) $corr) }}

                                                    </td>


                                                    <td>

                                                        @php
                                                            $selected =
                                                                $studentAns[$i]
                                                                ?? null;
                                                        @endphp


                                                        @if(
                                                            strtolower(
                                                                trim(
                                                                    (string) $selected
                                                                )
                                                            )
                                                            ===
                                                            strtolower(
                                                                trim(
                                                                    (string) $corr
                                                                )
                                                            )
                                                        )

                                                            <span class="text-success fw-semibold">

                                                                {{ $selected }}

                                                                <i class="bi bi-check-circle-fill"></i>

                                                            </span>

                                                        @else

                                                            <span class="text-danger">

                                                                {{ $selected ?? '—' }}

                                                            </span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            @endif


                            {{-- ===================================== --}}
                            {{-- Result --}}
                            {{-- ===================================== --}}

                            <div class="mt-3">

                                <span
                                    class="badge rounded-pill
                                    @if($isNotAttempted)
                                        bg-secondary
                                    @elseif($isCorrect)
                                        bg-success
                                    @else
                                        bg-danger
                                    @endif"
                                >

                                    @if($isNotAttempted)

                                        Not Attempted

                                    @elseif($isCorrect)

                                        Correct

                                    @else

                                        Wrong

                                    @endif

                                </span>


                                <span class="ms-2">

                                    Marks Awarded:
                                    {{ $answer->marks_awarded ?? 0 }}

                                </span>

                            </div>

                        </div>


                        @php
                            $displayQuestionNumber++;
                        @endphp

                    @endforeach

                </div>


            {{-- ===================================================== --}}
            {{-- STANDALONE QUESTION --}}
            {{-- ===================================================== --}}

            @else

                @php

                    $answer = $studentAnswers->get($question->id);

                    $raw = $answer->selected_option ?? '';


                    /*
                    |--------------------------------------------------------------------------
                    | Decode Student Answer
                    |--------------------------------------------------------------------------
                    */

                    $studentAns = (
                        Str::startsWith($raw, '[') ||
                        Str::startsWith($raw, '{')
                    )
                        ? json_decode($raw, true)
                        : $raw;


                    /*
                    |--------------------------------------------------------------------------
                    | Display Student Answer
                    |--------------------------------------------------------------------------
                    */

                    if (is_array($studentAns)) {

                        $studentAnsDisplay = collect($studentAns)
                            ->map(function ($val, $key) {

                                return is_numeric($key)
                                    ? $val
                                    : "{$key} → {$val}";

                            })
                            ->implode(', ');

                    } else {

                        $studentAnsDisplay = $studentAns ?: '-';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Correct Answers
                    |--------------------------------------------------------------------------
                    */

                    $correctAns = $question->correct_answers;

                    $correctArray = is_array($correctAns)
                        ? $correctAns
                        : (
                            json_decode(
                                $correctAns,
                                true
                            ) ?: [$correctAns]
                        );


                    $correctAnsDisplay = collect($correctArray)
                        ->map(function ($val, $key) {

                            return is_numeric($key)
                                ? $val
                                : "{$key} → {$val}";

                        })
                        ->implode(', ');


                    /*
                    |--------------------------------------------------------------------------
                    | Result Status
                    |--------------------------------------------------------------------------
                    */

                    $isCorrect =
                        $answer &&
                        $answer->is_correct;


                    $isNotAttempted =
                        !$answer ||
                        is_null($answer->selected_option) ||
                        (
                            is_array($studentAns) &&
                            empty(
                                array_filter(
                                    $studentAns,
                                    fn($v) =>
                                        !is_null($v) &&
                                        $v !== ''
                                )
                            )
                        );

                @endphp


                <div class="standalone-question">

                    {{-- ============================================= --}}
                    {{-- Question Number --}}
                    {{-- ============================================= --}}

                    <div class="question-number">

                        Question {{ $displayQuestionNumber }}

                    </div>


                    {{-- ============================================= --}}
                    {{-- Question Text --}}
                    {{-- ============================================= --}}

                    @php

                        $displayQuestion =
                            $question->question_text;


                        if ($question->question_type === 'dropdown') {

                            $displayQuestion =
                                preg_replace(
                                    '/\[blank\]/i',
                                    '<u class="text-muted">__________</u>',
                                    $question->question_text
                                );

                        }

                    @endphp


                    <div class="question-content mb-3">

                        {!! $displayQuestion !!}

                    </div>


                    {{-- ============================================= --}}
                    {{-- MCQ / Multiple Select --}}
                    {{-- ============================================= --}}

                    @if(
                        in_array(
                            $question->question_type,
                            ['mcq', 'multiple_select']
                        )
                        &&
                        is_array($question->options)
                    )

                        <ul class="list-group mb-2">

                            @foreach($question->options as $key => $option)

                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center
                                    @if(in_array($key, (array) $studentAns))
                                        bg-light
                                    @endif"
                                >

                                    <span>
                                        {{ $key }}. {{ $option }}
                                    </span>


                                    <span>

                                        @if(
                                            in_array(
                                                $key,
                                                (array) $question->correct_answers
                                            )
                                        )

                                            <span class="badge-correct">
                                                Correct
                                            </span>

                                        @endif


                                        @if(
                                            in_array(
                                                $key,
                                                (array) $studentAns
                                            )
                                        )

                                            <span
                                                class="badge ms-1 border"
                                                style="
                                                    background:#fdf6f2;
                                                    color:#832b00;
                                                    border-color:#e5d2c8 !important;
                                                "
                                            >
                                                Selected
                                            </span>

                                        @endif

                                    </span>

                                </li>

                            @endforeach

                        </ul>


                    {{-- ============================================= --}}
                    {{-- One Word --}}
                    {{-- ============================================= --}}

                    @elseif($question->question_type === 'one_word')

                        <div class="mb-2">

                            <strong>
                                Student's Answer:
                            </strong>

                            {{ $studentAnsDisplay ?: '—' }}

                        </div>


                        <div class="mb-2">

                            <strong>
                                Correct Answer:
                            </strong>

                            {{ $correctAnsDisplay }}

                        </div>


                    {{-- ============================================= --}}
                    {{-- Table MCQ --}}
                    {{-- ============================================= --}}

                    @elseif($question->question_type === 'table_mcq')

                        @php

                            $correctAnswers =
                                is_array($question->correct_answers)
                                    ? $question->correct_answers
                                    : json_decode(
                                        $question->correct_answers,
                                        true
                                    );


                            $tableStudentAnswers =
                                is_array($studentAns)
                                    ? $studentAns
                                    : [];

                        @endphp


                        <div class="table-responsive mb-2">

                            <table class="table table-bordered align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th width="50">
                                            #
                                        </th>

                                        <th>
                                            Statement
                                        </th>

                                        <th>
                                            Student's Answer
                                        </th>

                                        <th>
                                            Correct Answer
                                        </th>

                                        <th width="90">
                                            Result
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($question->options as $i => $stmt)

                                        @php

                                            $studentValue =
                                                $tableStudentAnswers[$i]
                                                ?? null;

                                            $correctValue =
                                                $correctAnswers[$i]
                                                ?? null;


                                            $rowCorrect =
                                                !is_null($studentValue)
                                                &&
                                                strtolower(
                                                    (string) $studentValue
                                                )
                                                ===
                                                strtolower(
                                                    (string) $correctValue
                                                );

                                        @endphp


                                        <tr>

                                            <td>
                                                {{ $loop->iteration }}
                                            </td>


                                            <td>
                                                {{ $stmt }}
                                            </td>


                                            <td>

                                                @if($studentValue)

                                                    {{ ucfirst((string) $studentValue) }}

                                                @else

                                                    <span class="text-muted">
                                                        —
                                                    </span>

                                                @endif

                                            </td>


                                            <td>

                                                {{ ucfirst((string) $correctValue) }}

                                            </td>


                                            <td class="text-center">

                                                @if(!$studentValue)

                                                    <span class="badge bg-secondary">
                                                        —
                                                    </span>

                                                @elseif($rowCorrect)

                                                    <span class="badge bg-success">
                                                        ✓
                                                    </span>

                                                @else

                                                    <span class="badge bg-danger">
                                                        ✕
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                    {{-- ============================================= --}}
                    {{-- Drag & Drop --}}
                    {{-- ============================================= --}}

                    @elseif($question->question_type === 'drag_and_drop')

                        @php

                            $options =
                                $question->options ?? [];


                            $aLabel =
                                $options['column_a_label']
                                ?? 'Column A';


                            $bLabel =
                                $options['column_b_label']
                                ?? 'Column B';


                            $colA =
                                $options['column_a']
                                ?? [];


                            $colB =
                                $options['column_b']
                                ?? [];


                            $correct =
                                is_array($question->correct_answers)
                                    ? $question->correct_answers
                                    : (
                                        json_decode(
                                            $question->correct_answers,
                                            true
                                        ) ?? []
                                    );

                        @endphp


                        <div class="table-responsive mb-2">

                            <table class="table table-bordered table-sm">

                                <thead>

                                    <tr>

                                        <th>
                                            {{ $aLabel }}
                                        </th>

                                        <th>
                                            Student's Match
                                        </th>

                                        <th>
                                            Correct Match
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($colA as $i => $item)

                                        @php

                                            $studentMatchIndex =
                                                $studentAns[$i] ?? null;


                                            $correctMatchIndex =
                                                $correct[$i] ?? null;

                                        @endphp


                                        <tr>

                                            <td>
                                                {{ $item }}
                                            </td>


                                            <td>

                                                {{ $colB[$studentMatchIndex] ?? '—' }}


                                                @if(
                                                    $studentMatchIndex !== null
                                                    &&
                                                    (string) $studentMatchIndex
                                                    ===
                                                    (string) $correctMatchIndex
                                                )

                                                    <span class="badge bg-success text-white ms-1">

                                                        <i class="bi bi-check-circle-fill"></i>

                                                    </span>

                                                @elseif($studentMatchIndex !== null)

                                                    <span class="badge bg-danger text-white ms-1">

                                                        <i class="bi bi-x-circle-fill"></i>

                                                    </span>

                                                @endif

                                            </td>


                                            <td>

                                                {{ $colB[$correctMatchIndex] ?? '—' }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                    {{-- ============================================= --}}
                    {{-- Dropdown --}}
                    {{-- ============================================= --}}

                    @elseif($question->question_type === 'dropdown')

                        @php

                            $correct =
                                is_array($question->correct_answers)
                                    ? $question->correct_answers
                                    : (
                                        json_decode(
                                            $question->correct_answers,
                                            true
                                        ) ?: []
                                    );


                            $dropdownOptionsRaw =
                                $question->options ?? [];


                            $dropdownOptions =
                                is_array($dropdownOptionsRaw)
                                    ? $dropdownOptionsRaw
                                    : json_decode(
                                        $dropdownOptionsRaw,
                                        true
                                    );


                            if (!is_array($dropdownOptions)) {

                                $dropdownOptions = [];

                            }

                        @endphp


                        <div class="table-responsive mt-3">

                            <table class="table table-bordered table-sm">

                                <thead>

                                    <tr>

                                        <th>
                                            Dropdown #
                                        </th>

                                        <th>
                                            Options Given
                                        </th>

                                        <th>
                                            Correct Answer
                                        </th>

                                        <th>
                                            Student's Answer
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($correct as $i => $corr)

                                        @php

                                            $selected =
                                                $studentAns[$i]
                                                ?? null;

                                        @endphp


                                        <tr>

                                            <td>
                                                {{ $i + 1 }}
                                            </td>


                                            <td>

                                                @if(
                                                    isset(
                                                        $dropdownOptions[$i]['options']
                                                    )
                                                    &&
                                                    is_array(
                                                        $dropdownOptions[$i]['options']
                                                    )
                                                )

                                                    {{ implode(
                                                        ', ',
                                                        $dropdownOptions[$i]['options']
                                                    ) }}

                                                @else

                                                    —

                                                @endif

                                            </td>


                                            <td>

                                                {{ ucfirst((string) $corr) }}

                                            </td>


                                            <td>

                                                @if(
                                                    strtolower(
                                                        trim(
                                                            (string) $selected
                                                        )
                                                    )
                                                    ===
                                                    strtolower(
                                                        trim(
                                                            (string) $corr
                                                        )
                                                    )
                                                )

                                                    <span class="text-success fw-semibold">

                                                        {{ $selected }}

                                                        <i class="bi bi-check-circle-fill"></i>

                                                    </span>

                                                @else

                                                    <span class="text-danger">

                                                        {{ $selected ?? '—' }}

                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @endif


                    {{-- ============================================= --}}
                    {{-- Result --}}
                    {{-- ============================================= --}}

                    <div class="mt-3">

                        <span
                            class="badge rounded-pill
                            @if($isNotAttempted)
                                bg-secondary
                            @elseif($isCorrect)
                                bg-success
                            @else
                                bg-danger
                            @endif"
                        >

                            @if($isNotAttempted)

                                Not Attempted

                            @elseif($isCorrect)

                                Correct

                            @else

                                Wrong

                            @endif

                        </span>


                        <span class="ms-2">

                            Marks Awarded:
                            {{ $answer->marks_awarded ?? 0 }}

                        </span>

                    </div>

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