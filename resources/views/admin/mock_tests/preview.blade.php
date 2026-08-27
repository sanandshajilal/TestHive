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
    font-family: 'Segoe UI', sans-serif;
    font-size: 16px;
    line-height: 1.7;
    color: #1f2937;
}

/*
 * Normalize pasted font family and font size.
 * Bold, italic, underline, lists, tables, etc.
 * are preserved.
 */
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

    .question-content table {
        max-width: 100%;
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

    {{-- ============================================= --}}
    {{-- Header --}}
    {{-- ============================================= --}}

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">

        <div>

            <h5 class="mb-0 text-dark fw-semibold">

                <i class="bi bi-file-earmark-text me-2"
                   style="color:#832b00;"></i>

                Test Preview

            </h5>

            <small class="text-muted">

                Review the complete mock test exactly as students will experience it.

            </small>

        </div>

        <a href="{{ route('mock-tests.index') }}"
           class="btn btn-secondary rounded-pill">

            <i class="bi bi-arrow-left me-1"></i>

            <span class="d-none d-md-inline">

                Back to All Tests

            </span>

        </a>

    </div>


    {{-- ============================================= --}}
    {{-- Test Information --}}
    {{-- ============================================= --}}

    <div class="card-style mb-4">

        <h4 class="mb-3">

            {{ $mockTest->title }}

        </h4>

        <div class="row mb-2">

            <div class="col-md-6">

                <strong>Paper :</strong>

                {{ $mockTest->paper->name ?? 'N/A' }}

            </div>

            <div class="col-md-6">

                <strong>Access Code :</strong>

                {{ $mockTest->access_code }}

            </div>

        </div>

        <div class="row mb-2">

            <div class="col-md-6">

                <strong>Start Time :</strong>

                {{ $mockTest->start_time }}

            </div>

            <div class="col-md-6">

                <strong>End Time :</strong>

                {{ $mockTest->end_time }}

            </div>

        </div>

        <div class="row mb-2">

            <div class="col-md-6">

                <strong>Duration :</strong>

                {{ $mockTest->duration_minutes }}

                Minutes

            </div>

            <div class="col-md-6">

                <strong>Batches :</strong>

                @if($mockTest->batches && $mockTest->batches->count())

                    @foreach($mockTest->batches as $batch)

                        {{ $batch->institute->name ?? '' }}
                        -
                        {{ $batch->name }}

                        @unless($loop->last)

                            ,

                        @endunless

                    @endforeach

                @else

                    N/A

                @endif

            </div>

        </div>

    </div>


    {{-- ============================================= --}}
    {{-- Summary --}}
    {{-- ============================================= --}}

    @php

        $scenarioCount = $mockTest->questions
            ->where('question_type', 'paragraph')
            ->count();

        $standaloneCount = $mockTest->questions
            ->where('question_type', '!=', 'paragraph')
            ->count();

        $actualQuestionCount = 0;

        foreach($mockTest->questions as $q){

            if($q->question_type == 'paragraph'){

                $actualQuestionCount += $q->children->count();

            }else{

                $actualQuestionCount++;

            }

        }

        $totalMarks = 0;

        foreach($mockTest->questions as $q){

            if($q->question_type == 'paragraph'){

                $totalMarks += $q->children->sum('marks');

            }else{

                $totalMarks += $q->marks;

            }

        }

    @endphp


    <div class="card-style mb-4">

        <div class="row text-center">

            <div class="col-md-3">

                <h4 class="mb-1">

                    {{ $mockTest->questions->count() }}

                </h4>

                <small class="text-muted">

                    Items

                </small>

            </div>

            <div class="col-md-3">

                <h4 class="mb-1">

                    {{ $actualQuestionCount }}

                </h4>

                <small class="text-muted">

                    Questions

                </small>

            </div>

            <div class="col-md-3">

                <h4 class="mb-1">

                    {{ $scenarioCount }}

                </h4>

                <small class="text-muted">

                    Scenarios

                </small>

            </div>

            <div class="col-md-3">

                <h4 class="mb-1">

                    {{ $totalMarks }}

                </h4>

                <small class="text-muted">

                    Total Marks

                </small>

            </div>

        </div>

    </div>


    {{-- ============================================= --}}
    {{-- Questions --}}
    {{-- ============================================= --}}

    <div class="card-style">

        <div class="border-bottom pb-2 mb-4">

            <h5 class="fw-semibold mb-1">

                <i class="bi bi-journal-check me-2"
                   style="color:#832b00;"></i>

                Question Preview

            </h5>

            <small class="text-muted">

                Review every question before publishing this mock test.

            </small>

        </div>

        @forelse($mockTest->questions as $index => $question)

        {{-- ====================================================== --}}
        {{-- Scenario --}}
        {{-- ====================================================== --}}

        @if($question->question_type === 'paragraph')

        <div class="question-card position-relative">

            <a href="{{ route('questions.edit', $question->id) }}"
            class="edit-link">

                <i class="bi bi-pencil-square me-1"></i>

                Edit Scenario

            </a>

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="mb-0">

                    Question {{ $loop->iteration }} - Scenario

                </h5>

            </div>

            <div class="question-content mb-4">

                {!! $question->question_text !!}

            </div>

            <hr class="mb-4">

            @foreach($question->children as $child)

                @php

                    $options = is_array($child->options)
                        ? $child->options
                        : json_decode($child->options, true) ?? [];

                    $correct = is_array($child->correct_answers)
                        ? $child->correct_answers
                        : json_decode($child->correct_answers, true) ?? [];

                @endphp

                <div
                    class="card shadow-sm border-0 mb-4"
                    style="background:#fcfaf8;">

                    <div
                        class="card-header d-flex justify-content-between align-items-center"
                        style="background:#f7e3d8;">

                        <div>

                            <strong>

                                Scenario Q{{ $loop->iteration }}

                            </strong>

                        </div>


                    </div>

                    <div class="card-body">

                        <div class="question-content mb-3">

                            {!! str_replace('[blank]','__________',$child->question_text) !!}

                        </div>

                        {{-- ========================================== --}}
                        {{-- MCQ / Multiple Select --}}
                        {{-- ========================================== --}}

                        @if(in_array($child->question_type,['mcq','multiple_select']))

                            <ul class="mb-3">

                                @foreach($options as $key=>$option)

                                    <li class="mb-1">

                                        <strong>

                                            {{ strtoupper($key) }}.

                                        </strong>

                                        {!! $option !!}

                                        @if(in_array($key,$correct))

                                            <span
                                                class="badge bg-success ms-2">

                                                Correct

                                            </span>

                                        @endif

                                    </li>

                                @endforeach

                            </ul>

                        {{-- ========================================== --}}
                        {{-- One Word --}}
                        {{-- ========================================== --}}

                        @elseif($child->question_type=='one_word')

                            <p>

                                <strong>

                                    Correct Answer :

                                </strong>

                                {{ $correct[0] ?? '-' }}

                            </p>

                        {{-- ========================================== --}}
                        {{-- Table MCQ --}}
                        {{-- ========================================== --}}

                        @elseif($child->question_type=='table_mcq')

                            <table
                                class="table table-bordered table-sm">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Statement</th>

                                        <th>Correct</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($options as $i=>$statement)

                                        <tr>

                                            <td>

                                                {{ $loop->iteration }}

                                            </td>

                                            <td>

                                                {{ $statement }}

                                            </td>

                                            <td>

                                                {{ $correct[$i] ?? '-' }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        {{-- ========================================== --}}
                        {{-- Drag & Drop --}}
                        {{-- ========================================== --}}

                        @elseif($child->question_type=='drag_and_drop')

                            @php

                                $colA = $options['column_a'] ?? [];

                                $colB = $options['column_b'] ?? [];

                            @endphp

                            <table
                                class="table table-bordered table-sm">

                                <thead>

                                    <tr>

                                        <th>Column A</th>

                                        <th>Matches</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($colA as $i=>$value)

                                        <tr>

                                            <td>

                                                {{ $value }}

                                            </td>

                                            <td>

                                                {{ $colB[$correct[$i] ?? -1] ?? '-' }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        {{-- ========================================== --}}
                        {{-- Dropdown --}}
                        {{-- ========================================== --}}

                        @elseif($child->question_type=='dropdown')

                            <table
                                class="table table-bordered table-sm">

                                <thead>

                                    <tr>

                                        <th>Blank</th>

                                        <th>Options</th>

                                        <th>Answer</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($correct as $i=>$ans)

                                        <tr>

                                            <td>

                                                {{ $i+1 }}

                                            </td>

                                            <td>

                                                {{ implode(', ',$options[$i]['options'] ?? []) }}

                                            </td>

                                            <td>

                                                {{ $ans }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        @endif

                        <div class="question-meta">

                            Marks :
                            {{ $child->marks }}

                            &nbsp;&nbsp;|&nbsp;&nbsp;

                            {{ ucfirst(str_replace('_',' ',$child->question_type)) }}

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

        @else

        <div class="question-card position-relative">

    {{-- Edit Button --}}
    <a href="{{ route('questions.edit', $question->id) }}"
       class="edit-link">

        <i class="bi bi-pencil-square me-1"></i>

        Edit Question

    </a>

    {{-- Heading --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="mb-0">

            Question {{ $loop->iteration }}

        </h5>



    </div>

    {{-- Question --}}

    <div class="question-content mb-3">

        {!! str_replace('[blank]','__________',$question->question_text) !!}

    </div>

    @php

        $options = is_array($question->options)
            ? $question->options
            : json_decode($question->options,true) ?? [];

        $correct = is_array($question->correct_answers)
            ? $question->correct_answers
            : json_decode($question->correct_answers,true) ?? [];

    @endphp

    {{-- ============================================== --}}
    {{-- MCQ / Multiple Select --}}
    {{-- ============================================== --}}

    @if(in_array($question->question_type,['mcq','multiple_select']))

        <ul class="mb-3">

            @foreach($options as $key=>$option)

                <li class="mb-2">

                    <strong>

                        {{ strtoupper($key) }}.

                    </strong>

                    {!! $option !!}

                    @if(in_array($key,$correct))

                        <span
                            class="badge bg-success ms-2">

                            Correct

                        </span>

                    @endif

                </li>

            @endforeach

        </ul>

    {{-- ============================================== --}}
    {{-- One Word --}}
    {{-- ============================================== --}}

    @elseif($question->question_type=='one_word')

        <p class="mb-0">

            <strong>

                Correct Answer :

            </strong>

            {{ $correct[0] ?? '-' }}

        </p>

    {{-- ============================================== --}}
    {{-- Table MCQ --}}
    {{-- ============================================== --}}

    @elseif($question->question_type=='table_mcq')

        <table class="table table-bordered table-sm">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Statement</th>

                    <th>Correct Answer</th>

                </tr>

            </thead>

            <tbody>

                @foreach($options as $i=>$statement)

                    <tr>

                        <td>

                            {{ $loop->iteration }}

                        </td>

                        <td>

                            {{ $statement }}

                        </td>

                        <td>

                            {{ $correct[$i] ?? '-' }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

            {{-- ============================================== --}}
    {{-- Drag & Drop --}}
    {{-- ============================================== --}}

    @elseif($question->question_type=='drag_and_drop')

        @php

            $colA = $options['column_a'] ?? [];

            $colB = $options['column_b'] ?? [];

        @endphp

        <table class="table table-bordered table-sm">

            <thead>

                <tr>

                    <th>Column A</th>

                    <th>Correct Match</th>

                </tr>

            </thead>

            <tbody>

                @foreach($colA as $i=>$value)

                    <tr>

                        <td>

                            {{ $value }}

                        </td>

                        <td>

                            {{ $colB[$correct[$i] ?? -1] ?? '-' }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    {{-- ============================================== --}}
    {{-- Dropdown --}}
    {{-- ============================================== --}}

    @elseif($question->question_type=='dropdown')

        <table class="table table-bordered table-sm">

            <thead>

                <tr>

                    <th>Blank</th>

                    <th>Options</th>

                    <th>Correct Answer</th>

                </tr>

            </thead>

            <tbody>

                @foreach($correct as $i=>$answer)

                    <tr>

                        <td>

                            {{ $i + 1 }}

                        </td>

                        <td>

                            {{ implode(', ', $options[$i]['options'] ?? []) }}

                        </td>

                        <td>

                            {{ $answer }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @endif

    <div class="question-meta">

        {{ $question->topic->name ?? 'Topic N/A' }}

        <i class="bi bi-arrow-right-short"></i>

        {{ $question->subTopic->name ?? 'Subtopic N/A' }}

        |

        {{ ucfirst(str_replace('_',' ',$question->question_type)) }}

        |

        <strong>

            Marks :

        </strong>

        {{ $question->marks }}

    </div>

</div>

@endif

@empty

    <div class="text-center py-5">

        <i class="bi bi-journal-x display-5 text-muted"></i>

        <p class="mt-3 mb-0 text-muted">

            No questions have been added to this mock test.

        </p>

    </div>

@endforelse

</div>

</div>

@endsection

            
