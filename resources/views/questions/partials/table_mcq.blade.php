@php
    $question = $question ?? null;
    $statements = old('table_mcq_statements', is_array($question->options ?? null) ? $question->options : []);
    $answers = old('table_mcq_answers', is_array($question->correct_answers ?? null) ? $question->correct_answers : []);
    $labels = old('table_mcq_labels', is_array($question->table_mcq_labels ?? null) ? implode(',', $question->table_mcq_labels) : ($question->table_mcq_labels ?? ''));
@endphp

<div id="table-mcq-section" class="d-none mb-3">

    {{-- Combined Error Box --}}
    @php
        $errorList = [];

        if ($errors->has('table_mcq_statements')) {
            $errorList[] = $errors->first('table_mcq_statements');
        }

        foreach (old('table_mcq_statements', []) as $i => $val) {
            if ($errors->has("table_mcq_statements.$i")) {
                $errorList[] = $errors->first("table_mcq_statements.$i");
            }
        }

        foreach (old('table_mcq_answers', []) as $i => $val) {
            if ($errors->has("table_mcq_answers.$i")) {
                $errorList[] = $errors->first("table_mcq_answers.$i");
            }
        }
    @endphp

    @if (!empty($errorList))
        <div class="alert alert-warning d-flex align-items-start gap-2">
            <div class="flex-grow-1">
                @foreach ($errorList as $msg)
                    <div>{{ $msg }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <label class="form-label">Statements (One per row)</label>
    <div id="table-mcq-rows">
        @forelse ($statements as $statement)
            <div class="input-group mb-2 table-mcq-row">
                <input type="text" name="table_mcq_statements[]" class="form-control" value="{{ $statement }}" placeholder="Enter statement" required>
                <button type="button" class="btn btn-danger remove-row">X</button>
            </div>
        @empty
            <div class="input-group mb-2 table-mcq-row">
                <input type="text" name="table_mcq_statements[]" class="form-control" placeholder="Enter statement" required>
                <button type="button" class="btn btn-danger remove-row">X</button>
            </div>
        @endforelse
    </div>

    <button type="button" class="btn btn-sm btn-secondary mt-2" id="addRow">+ Add Row</button>

    <div class="mt-3">
        <label class="form-label">Answer Labels</label>
        <input type="text" name="table_mcq_labels" class="form-control mb-2"
               placeholder="Comma-separated (e.g., debit,credit)" value="{{ $labels }}" required>

        <label class="form-label mt-3">Correct Answers</label>
        <p class="text-muted">Provide answers for each statement in order, matching one of the labels.</p>

        <div id="table-mcq-answers">
            @forelse ($answers as $answer)
                <input type="text" name="table_mcq_answers[]" class="form-control mb-2" value="{{ $answer }}" placeholder="Answer (match label)" required>
            @empty
                <input type="text" name="table_mcq_answers[]" class="form-control mb-2" placeholder="Answer for Row 1 (match label)" required>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('addRow').addEventListener('click', function () {
            const rowHTML = `
                <div class="input-group mb-2 table-mcq-row">
                    <input type="text" name="table_mcq_statements[]" class="form-control" placeholder="Enter statement" required>
                    <button type="button" class="btn btn-danger remove-row">X</button>
                </div>`;
            document.getElementById('table-mcq-rows').insertAdjacentHTML('beforeend', rowHTML);
            document.getElementById('table-mcq-answers').insertAdjacentHTML('beforeend',
                `<input type="text" name="table_mcq_answers[]" class="form-control mb-2" placeholder="Answer for Row" required>`);
        });

        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-row')) {
                const row = e.target.closest('.table-mcq-row');
                const index = Array.from(row.parentNode.children).indexOf(row);
                row.remove();
                const answerInputs = document.querySelectorAll('#table-mcq-answers input');
                if (answerInputs[index]) answerInputs[index].remove();
            }
        });
    });
</script>
@endpush
