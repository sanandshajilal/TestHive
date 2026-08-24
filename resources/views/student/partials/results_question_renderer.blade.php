@php
    $raw = $answer->selected_option ?? '';

    $studentAns = (Str::startsWith($raw, '[') || Str::startsWith($raw, '{'))
        ? json_decode($raw, true)
        : $raw;

    if (is_array($studentAns)) {
        $studentAnsDisplay = collect($studentAns)
            ->map(fn($v, $k) => is_numeric($k) ? $v : "$k → $v")
            ->implode(', ');
    } else {
        $studentAnsDisplay = $studentAns ?: '-';
    }

    $correctAns = $question->correct_answers;

    $correctArray = is_array($correctAns)
        ? $correctAns
        : (json_decode($correctAns, true) ?: [$correctAns]);

    $correctAnsDisplay = collect($correctArray)
        ->map(fn($v, $k) => is_numeric($k) ? $v : "$k → $v")
        ->implode(', ');
@endphp


@php
    $isCorrect = $answer && $answer->is_correct;

    $isNotAttempted =
        !$answer ||
        is_null($answer->selected_option) ||
        (is_array($studentAns) &&
            empty(array_filter($studentAns, fn($v) => !is_null($v) && $v !== '')));
@endphp


@if(in_array($question->question_type, ['mcq', 'multiple_select']) && is_array($question->options))
                    <ul class="list-group mb-2">
                        @foreach($question->options as $key => $option)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $key }}. {{ $option }}</span>
                                <span>
                                    @if(in_array($key, $correctArray))
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