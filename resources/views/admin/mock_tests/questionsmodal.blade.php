<div class="px-3 py-2">
    {{-- Question --}}
    <div class="mb-3">
        <div class="fw-bold">Question:</div>
        <div class="question-content">{!! $question->question_text !!}</div>
    </div>

    @php
        $options = is_array($question->options) ? $question->options : json_decode($question->options, true) ?? [];
        $correct = is_array($question->correct_answers) ? $question->correct_answers : json_decode($question->correct_answers, true) ?? [];
    @endphp

    {{-- Options / Answers --}}
    @if(in_array($question->question_type, ['mcq', 'multiple_select']))
        <div class="mb-3">
            <h6 class="fw-bold">Options:</h6>
            <ul class="list-group">
                @foreach($options as $key => $opt)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{!! $key !!}. {!! $opt !!}</span>
                        @if(in_array($key, $correct))
                            <span class="badge bg-success">Correct</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

    @elseif($question->question_type === 'one_word')
        <div class="mb-3">
            <h6 class="fw-bold">Correct Answer:</h6>
            <p class="mb-0">{{ $correct[0] ?? '-' }}</p>
        </div>

    @elseif($question->question_type === 'table_mcq')
        @php
            $labels = explode(',', $question->table_mcq_labels ?? 'Debit,Credit');
            $colLabel = count($labels) === 2 ? "{$labels[0]} / {$labels[1]}" : 'Correct Answer';
        @endphp
        <div class="mb-3">
            <h6 class="fw-bold">Statements:</h6>
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Statement</th>
                        <th>{{ $colLabel }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($options as $i => $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ is_array($row) ? $row['statement'] ?? '-' : $row }}</td>
                            <td>{{ ucfirst($correct[$i] ?? '-') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

 @elseif($question->question_type === 'drag_and_drop')
    @php
        $colA = $options['column_a'] ?? [];
        $colB = $options['column_b'] ?? [];
    @endphp

    @if(count($colA) && count($colB) && is_array($correct))
        <div class="mb-3">
            <h6 class="fw-bold">Correct Matches:</h6>
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Column A</th>
                        <th>Matches with</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($colA as $i => $aVal)
                        <tr>
                            <td>{{ $aVal }}</td>
                            <td>{{ $colB[$correct[$i] ?? -1] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">Drag and drop options or correct matches missing.</p>
    @endif


@elseif($question->question_type === 'dropdown')
    @php
        $dropdowns = is_array($options) ? $options : json_decode($options, true) ?? [];
        $corrects = $correct ?? [];
    @endphp

    @if(count($dropdowns))
        <div class="mb-3">
            <h6 class="fw-bold">Dropdown Options and Correct Answers:</h6>
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Blank #</th>
                        <th>Label</th>
                        <th>Options</th>
                        <th>Correct Answer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dropdowns as $i => $dropdown)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $dropdown['label'] ?? '-' }}</td>
                            <td>
                                @foreach($dropdown['options'] ?? [] as $opt)
                                    <span class="badge bg-secondary me-1">{{ $opt }}</span>
                                @endforeach
                            </td>
                            <td><strong>{{ $corrects[$i] ?? '-' }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">No dropdown options configured.</p>
    @endif
@endif

    {{-- Footer Row --}}
    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4 small">
        <div>
            <strong>Marks:</strong> {{ $question->marks ?? '-' }}
        </div>
        <div class="text-muted text-end">
            {{ $question->topic->name ?? 'No Topic' }} /
            {{ $question->subtopic->name ?? 'No Subtopic' }} /
            {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
        </div>
    </div>
</div>
