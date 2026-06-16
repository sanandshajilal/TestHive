@php use Illuminate\Support\Str; @endphp

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

        /* ---------- HEADER ---------- */

        .header-box {
            background: #fff;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
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

        /* ---------- SCORE BANNER ---------- */

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
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
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

        /* ---------- CARDS ---------- */

        .card-style {
            border-radius: 1rem;
            background: #fff;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
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
            box-shadow: 0 4px 12px rgba(0,0,0,.04);
        }

        /* ---------- STUDENT INFO ---------- */

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

        /* ---------- SUMMARY ---------- */

        .summary-card {
            height: 100%;
            padding: 1rem 0.75rem;
        }

        .summary-total {
            color: var(--primary-dark);
        }

        .summary-marks {
            color: var(--primary);
        }

        /* ---------- QUESTIONS ---------- */

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

        /* ---------- BADGES ---------- */

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

        /* ---------- TABLES ---------- */

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

        /* ---------- OPTION LIST ---------- */

        .list-group-item {
            border-color: #edf0f2;
        }

     

        /* ---------- RESPONSE SHEET TITLE ---------- */

        .response-title {
            color: var(--primary-dark);
            font-weight: 700;
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: .75rem;
            margin-bottom: 1.5rem;
        }

        /* ---------- RESPONSIVE ---------- */

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
        <h4 class="fw-bold mb-0">
            <i class="bi bi-award me-2"></i>
            Test Results
        </h4>
        <a href="{{ route('student.index') }}" class="btn btn-back">
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
            ['label' => 'Total', 'value' => $totalQuestions, 'icon' => 'list-check', 'class' => 'summary-total'],
            ['label' => 'Correct', 'value' => $correctCount, 'icon' => 'check-circle', 'class' => 'text-success'],
            ['label' => 'Wrong', 'value' => $wrongCount, 'icon' => 'x-circle', 'class' => 'text-danger'],
            ['label' => 'Not Attempted', 'value' => $unattemptedCount, 'icon' => 'dash-circle', 'class' => 'text-secondary'],
            ['label' => 'Marks', 'value' => "$totalMarks / $totalPossible", 'icon' => 'award', 'class' => 'summary-marks'],
        ] as $stat)

            <div class="px-3 py-2 flex-fill text-center" style="min-width: 120px;">
                <i class="bi bi-{{ $stat['icon'] }} {{ $stat['class'] }} fs-5"></i>

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


     <!-- Questions with Student Response -->
    <div class="card-style">
        <h5 class="response-title">
            <i class="bi bi-journal-check me-2"></i>
            Response Sheet
        </h5>

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


            <div class="question-card">
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
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $key }}. {{ $option }}</span>
                                <span>
                                    @if(in_array($key, (array) $question->correct_answers))
                                        <span class="badge bg-success">Correct</span>
                                    @endif
                                    @if(in_array($key, (array) $studentAns))
                                        <span class="badge ms-1 border"
                                            style="background:#fdf6f2;
                                                    color:#832b00;
                                                    border-color:#e5d2c8 !important;">
                                            Selected
                                        </span>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @elseif($question->question_type === 'one_word')
                    <div class="mb-2"><strong>Your Answer:</strong> {{ $studentAnsDisplay ?: '—' }}</div>
                    <div class="mb-2"><strong>Correct Answer:</strong> {{ $correctAnsDisplay }}</div>
                @elseif($question->question_type === 'table_mcq')

                    @php
                        $labels = array_map(
                            'trim',
                            explode(',', $question->table_mcq_labels ?? 'True,False')
                        );

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
                                    <th>Your Answer</th>
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
                                            && strtolower((string)$studentValue) === strtolower((string)$correctValue);
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            {{ $stmt }}
                                        </td>

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
