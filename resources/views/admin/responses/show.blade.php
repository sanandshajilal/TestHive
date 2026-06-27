@php use Illuminate\Support\Str; @endphp

@extends('layouts.app')

@section('title', 'Student Response Sheet')

@section('styles')
<style>
body{
    background:#f9fafb;
}

.back-btn{
    border-color:#b46e4c;
    color:#832b00;
    background:#f7e3d8;
    transition:.2s;
}

.back-btn:hover{
    background:#b46e4c;
    border-color:#b46e4c;
    color:#fff;
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

.card-style{
    background:#fff;
    border-radius:1rem;
    padding:1.5rem;
    box-shadow:0 2px 10px rgba(180,110,76,.08);
}

/* Summary */

.summary-stat{
    flex:1;
    text-align:center;
    padding:.75rem 1rem;
}

.summary-stat:not(:last-child){
    border-right:1px solid #edf0f2;
}

.summary-label{
    font-size:.8rem;
    color:#6c757d;
}

.summary-value{
    font-size:1.35rem;
    font-weight:700;
}

/* Question */

.question-block{
    background:#fff;
    border:1px solid #eef0f2;
    border-radius:1rem;
    padding:1.5rem;
    margin-bottom:1.5rem;
    transition:.2s;
}

.question-block:hover{
    box-shadow:0 4px 12px rgba(0,0,0,.05);
}

.question-content p{
    margin-bottom:.5rem;
}

.question-content table{
    width:auto;
    border-collapse:collapse;
    margin-top:.5rem;
}

.question-content th,
.question-content td{
    border:1px solid #dee2e6;
    padding:6px 10px;
}

/* Tables */

.table{
    margin-bottom:0;
}

.table thead{
    background:#fcf7f3;
}

.table th{
    color:#9a5631;
    font-weight:600;
}

/* Badges */

.badge{
    font-weight:500;
}

/* Mobile */

@media(min-width:768px){

    .min-w-md-0{
        min-width:0!important;
    }

}
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Heading -->
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-semibold text-dark mb-0">
            <i class="bi bi-file-earmark-text me-2" style="color:#832b00;"></i>
            Response Sheet
        </h4>
        <a href="{{ route('mock-tests.results', $attempt->mock_test_id) }}"
        class="btn btn-outline-secondary rounded-pill back-btn">
            <i class="bi bi-arrow-left"></i>
            <span class="d-none d-md-inline ms-1">Back to Results</span>
        </a>
    </div>

    @php
        $questions = $attempt->mockTest->questions ?? collect();
        $studentAnswers = $answers->keyBy('question_id');

        $correctCount = 0;
        $wrongCount = 0;
        $unattemptedCount = 0;
        $totalMarks = 0;

        foreach ($questions as $q) {
            $a = $studentAnswers->get($q->id);
            $selected = json_decode($a->selected_option ?? '', true);

            if (!$a || is_null($a->selected_option) || (is_array($selected) && empty($selected))) {
                $unattemptedCount++;
            } elseif ($a->is_correct) {
                $correctCount++;
            } else {
                $wrongCount++;
            }

            $totalMarks += $a->marks_awarded ?? 0;
        }

        $totalQuestions = $questions->count();
        $totalPossible = $questions->sum('marks') ?: ($totalQuestions * 2);
    @endphp

    <!-- Student & Test Info -->
    <div class="card-style mb-4">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-md-4"><strong>Student:</strong><br>{{ $attempt->student_name }}</div>
                <div class="col-md-4"><strong>Email:</strong><br>{{ $attempt->email }}</div>
                <div class="col-md-4"><strong>Institute:</strong><br>{{ $attempt->institute->name ?? '-' }}</div>
                <div class="col-md-4"><strong>Batch:</strong><br>{{ $attempt->batch->name ?? '-' }}</div>
                <div class="col-md-4"><strong>Test:</strong><br>{{ $attempt->mockTest->title ?? 'N/A' }}</div>
                <div class="col-md-4"><strong>Duration:</strong><br>{{ $attempt->mockTest->duration_minutes ?? '-' }} mins</div>

            </div>
        </div>
    </div>

 <!-- Clean Horizontal Summary Bar -->
<div class="card-style d-flex justify-content-around flex-wrap text-center mb-4">
    @foreach([
        ['label' => 'Total', 'value' => $totalQuestions, 'icon' => 'list-check', 'color' => 'primary'],
        ['label' => 'Correct', 'value' => $correctCount, 'icon' => 'check-circle', 'color' => 'success'],
        ['label' => 'Wrong', 'value' => $wrongCount, 'icon' => 'x-circle', 'color' => 'danger'],
        ['label' => 'Not Attempted', 'value' => $unattemptedCount, 'icon' => 'dash-circle', 'color' => 'warning'],
        ['label' => 'Marks', 'value' => "$totalMarks / $totalPossible", 'icon' => 'award', 'color' => 'info'],
    ] as $stat)
        <div class="px-3 py-2 flex-fill min-w-100 min-w-md-0">
            <i class="bi bi-{{ $stat['icon'] }} text-{{ $stat['color'] }} fs-5"></i>
            <div class="small text-muted mt-1">{{ $stat['label'] }}</div>
            <div class="fw-bold text-{{ $stat['color'] }}">{{ $stat['value'] }}</div>
        </div>
    @endforeach
</div>


    <!-- Questions with Student Response -->
    <div class="card-style">
        <h5 class="mb-4">Questions & Answers</h5>

        @forelse ($questions as $index => $question)
           @php
                

                $answer = $studentAnswers->get($question->id);
                $raw = $answer->selected_option ?? '';

                // Decode student answer
                $studentAns = (Str::startsWith($raw, '[') || Str::startsWith($raw, '{'))
                    ? json_decode($raw, true)
                    : $raw;

                // Display student answer safely
                if (is_array($studentAns)) {
                    $studentAnsDisplay = collect($studentAns)
                        ->map(function ($val, $key) {
                            return is_numeric($key)
                                ? $val
                                : "{$key} → {$val}";
                        })->implode(', ');
                } else {
                    $studentAnsDisplay = $studentAns ?: '-';
                }

                // Decode correct answers
                $correctAns = $question->correct_answers;
                $correctArray = is_array($correctAns)
                    ? $correctAns
                    : (json_decode($correctAns, true) ?: [$correctAns]);

                $correctAnsDisplay = collect($correctArray)
                    ->map(function ($val, $key) {
                        return is_numeric($key)
                            ? $val
                            : "{$key} → {$val}";
                    })->implode(', ');

                $isCorrect = $answer && $answer->is_correct;
                $isNotAttempted = !$answer || is_null($answer->selected_option) ||
                    (is_array($studentAns) && empty(array_filter($studentAns, fn($v) => !is_null($v) && $v !== '')));
            @endphp


            <div class="mb-4 pb-3 border-bottom">
                <h6 class="fw-semibold text-dark mb-2">Q{{ $index + 1 }}:</h6>
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

                <div class="question-content mb-2">
                    {!! $displayQuestion !!}
                </div>

                @if(in_array($question->question_type, ['mcq', 'multiple_select']) && is_array($question->options))
                    <ul class="list-group mb-2">
                        @foreach($question->options as $key => $option)
                            <li class="list-group-item d-flex justify-content-between align-items-center @if(in_array($key, (array) $studentAns)) bg-light @endif">
                                <span>{{ $key }}. {{ $option }}</span>
                                <span>
                                    @if(in_array($key, (array) $question->correct_answers))
                                        <span class="badge bg-success">Correct</span>
                                    @endif
                                    @if(in_array($key, (array) $studentAns))
                                        <span class="badge bg-primary ms-1">Selected</span>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @elseif($question->question_type === 'one_word')
                    <div class="mb-2"><strong>Student's Answer:</strong> {{ $studentAnsDisplay ?: '—' }}</div>
                    <div class="mb-2"><strong>Correct Answer:</strong> {{ $correctAnsDisplay }}</div>
                @elseif($question->question_type === 'table_mcq')
                    @php
                        $correctAnswers = is_array($question->correct_answers)
                            ? $question->correct_answers
                            : json_decode($question->correct_answers, true);

                        $tableStudentAnswers = is_array($studentAns)
                            ? $studentAns
                            : [];
                    @endphp

                    <div class="table-responsive mb-2">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Statement</th>
                                    <th>Student's Answer</th>
                                    <th>Correct Answer</th>
                                    <th width="90">Result</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($question->options as $i => $stmt)

                                    @php
                                        $studentValue = $tableStudentAnswers[$i] ?? null;
                                        $correctValue = $correctAnswers[$i] ?? null;

                                        $rowCorrect =
                                            !is_null($studentValue)
                                            && strtolower((string)$studentValue)
                                            === strtolower((string)$correctValue);
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>{{ $stmt }}</td>

                                        <td>
                                            @if($studentValue)
                                                {{ ucfirst((string)$studentValue) }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ ucfirst((string)$correctValue) }}
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
                    @elseif($question->question_type === 'drag_and_drop')
                    @php
                        $options = $question->options ?? [];
                        $aLabel = $options['column_a_label'] ?? 'Column A';
                        $bLabel = $options['column_b_label'] ?? 'Column B';
                        $colA = $options['column_a'] ?? [];
                        $colB = $options['column_b'] ?? [];
                        $correct = json_decode($question->correct_answers, true) ?? [];
                    @endphp

                    <div class="table-responsive mb-2">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>{{ $aLabel }}</th>
                                    <th>Student's Match</th>
                                    <th>Correct Match</th>
                                </tr>
                            </thead>
                        <tbody>
                            @foreach($colA as $i => $item)
                                @php
                                    $studentMatchIndex = $studentAns[$i] ?? null;
                                    $correctMatchIndex = $correct[$i] ?? null;
                                @endphp
                                <tr>
                                    <td>{{ $item }}</td>
                                    <td>
                                        {{ $colB[$studentMatchIndex] ?? '—' }}
                                        @if(
                                                $studentMatchIndex !== null &&
                                                (string)$studentMatchIndex === (string)$correctMatchIndex
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
                                    <td>{{ $colB[$correctMatchIndex] ?? '—' }}</td>
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

                        $dropdownOptionsRaw = $question->options ?? '[]'; // <-- FIXED HERE

                        $dropdownOptions = is_array($dropdownOptionsRaw)
                            ? $dropdownOptionsRaw
                            : json_decode($dropdownOptionsRaw, true);

                        // Ensure it's a valid array after decoding
                        if (!is_array($dropdownOptions)) {
                            $dropdownOptions = [];
                        }
                    @endphp

                    <!-- Dropdown Options Table -->
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Dropdown #</th>
                                    <th>Options Given</th>
                                    <th>Correct Answer</th>
                                    <th>Student's Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($correct as $i => $corr)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            @if(isset($dropdownOptions[$i]['options']) && is_array($dropdownOptions[$i]['options']))
                                                {{ implode(', ', $dropdownOptions[$i]['options']) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ ucfirst((string)$corr) }}</td>
                                        <td>
                                            @php $selected = $studentAns[$i] ?? null; @endphp
                                            @if(
                                                    strtolower(trim((string)$selected))
                                                    ===
                                                    strtolower(trim((string)$corr))
                                                )
                                                <span class="text-success fw-semibold">{{ $selected }} <i class="bi bi-check-circle-fill"></i></span>
                                            @else
                                                <span class="text-danger">{{ $selected ?? '—' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif




                <!-- Result Badge -->
                <div class="mt-2">
                    <span class="badge rounded-pill 
                        @if($isNotAttempted) bg-secondary 
                        @elseif($isCorrect) bg-success 
                        @else bg-danger 
                        @endif">
                        @if($isNotAttempted) Not Attempted
                        @elseif($isCorrect) Correct
                        @else Wrong
                        @endif
                    </span>
                    <span class="ms-2">Marks Awarded: {{ $answer->marks_awarded ?? 0 }}</span>
                </div>
            </div>
        @empty
            <p>No questions found.</p>
        @endforelse
    </div>
</div>
@endsection
