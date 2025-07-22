@php use Illuminate\Support\Str; @endphp

@extends('layouts.student')

@section('title', 'Response Sheet')

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
    .summary-card {
        height: 100%;
        padding: 1rem 0.75rem;
    }
    .question-content p {
        margin-bottom: 0.5rem;
    }

    @media (min-width: 768px) {
    .min-w-md-0 {
        min-width: 0 !important;
        
    }
    .back-label {
                        display: none !important;
                    }
}

</style>
@endsection

@section('content')

@if(session('info'))
    <div id="infoAlert" class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <script>
        setTimeout(() => {
            const alertEl = document.getElementById('infoAlert');
            if (alertEl) {
                alertEl.classList.remove('show');
                alertEl.classList.add('fade');
                // Optionally remove it from DOM after fade
                setTimeout(() => alertEl.remove(), 150, alertEl);
            }
        }, 5000);
    </script>
@endif

<div class="container py-4">
    <!-- Heading -->
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-semibold text-dark mb-0">📄 TEST RESULTS</h4>
        <a href="{{ route('student.index') }}" class="btn btn-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> 
            <span class="back-label">Back to Home </span>
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
    <div class="card shadow-sm border-0 rounded-4 mb-4">
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


<!-- Summary Bar Card -->
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body d-flex justify-content-around flex-wrap text-center gap-3">
        @foreach([
            ['label' => 'Total', 'value' => $totalQuestions, 'icon' => 'list-check', 'color' => 'primary'],
            ['label' => 'Correct', 'value' => $correctCount, 'icon' => 'check-circle', 'color' => 'success'],
            ['label' => 'Wrong', 'value' => $wrongCount, 'icon' => 'x-circle', 'color' => 'danger'],
            ['label' => 'Not Attempted', 'value' => $unattemptedCount, 'icon' => 'dash-circle', 'color' => 'warning'],
            ['label' => 'Marks', 'value' => "$totalMarks / $totalPossible", 'icon' => 'award', 'color' => 'info'],
        ] as $stat)
            <div class="px-3 py-2 flex-fill text-center" style="min-width: 120px;">
                <i class="bi bi-{{ $stat['icon'] }} text-{{ $stat['color'] }} fs-5"></i>
                <div class="small text-muted mt-1">{{ $stat['label'] }}</div>
                <div class="fw-bold text-{{ $stat['color'] }}">{{ $stat['value'] }}</div>
            </div>
        @endforeach
    </div>
</div>


     <!-- Questions with Student Response -->
    <div class="card-style">
        <h5 class="mb-4">Response Sheet</h5>

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
                        $displayQuestion = preg_replace('/\[blank\]/i', '<u class="text-muted">__________</u>', $question->question_text);
                    }
                @endphp
                <div class="question-content mb-2">{!! $displayQuestion !!}</div>

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
                    <div class="mb-2"><strong>Your Answer:</strong> {{ $studentAnsDisplay ?: '—' }}</div>
                    <div class="mb-2"><strong>Correct Answer:</strong> {{ $correctAnsDisplay }}</div>
                @elseif($question->question_type === 'table_mcq')
                    <div class="table-responsive mb-2">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Statement</th>
                                    <th>Your Answer</th>
                                    <th>Correct Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($question->options as $i => $stmt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $stmt }}</td>
                                        <td>{{ $studentAns[$i] ?? '—' }}</td>
                                        <td>{{ $question->correct_answers[$i] ?? '—' }}</td>
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
                                    <th>Your Match</th>
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
                                        @if($studentMatchIndex === $correctMatchIndex)
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
                                    <th>Your Answer</th>
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
                                        <td>{{ $corr }}</td>
                                        <td>
                                            @php $selected = $studentAns[$i] ?? null; @endphp
                                            @if($selected === $corr)
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
