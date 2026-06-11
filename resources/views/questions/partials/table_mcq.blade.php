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

        <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Label 1</label>
            <input type="text"
                id="label1"
                class="form-control"
                value="{{ !empty(explode(',', $labels)[0]) ? explode(',', $labels)[0] : 'Debit' }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Label 2</label>
          <input type="text"
                id="label2"
                class="form-control"
                value="{{ !empty(explode(',', $labels)[1]) ? explode(',', $labels)[1] : 'Credit' }}">
        </div>
    </div>

    <input type="hidden"
        name="table_mcq_labels"
        id="table_mcq_labels"
        value="{{ $labels }}">

        <div class="table-responsive">
        <table class="table table-bordered align-middle" id="tableMcqTable">

            <thead class="table-light">
                <tr>
                    <th style="width:70%">Statement</th>

                    <th class="text-center label1-header">
                        {{ !empty(explode(',', $labels)[0]) ? explode(',', $labels)[0] : 'Debit' }}
                    </th>

                    <th class="text-center label2-header">
                        {{ !empty(explode(',', $labels)[1]) ? explode(',', $labels)[1] : 'Credit' }}
                    </th>

                    <th width="80">Action</th>
                </tr>
            </thead>

            <tbody id="table-mcq-rows">

                @forelse ($statements as $index => $statement)

                    <tr class="table-mcq-row">

                        <td>
                            <input type="text"
                                name="table_mcq_statements[]"
                                class="form-control table-mcq-statement"
                                value="{{ $statement }}"
                                placeholder="Enter statement"
                                required>
                        </td>

                        <td class="text-center">
                            <input type="radio"
                                name="row_answer_{{ $index }}"
                                value="{{ !empty(explode(',', $labels)[0]) ? explode(',', $labels)[0] : 'Debit' }}"
                                {{ ($answers[$index] ?? '') == (!empty(explode(',', $labels)[0]) ? explode(',', $labels)[0] : 'Debit') ? 'checked' : '' }}>
                        </td>

                        <td class="text-center">
                            <input type="radio"
                                name="row_answer_{{ $index }}"
                                value="{{ !empty(explode(',', $labels)[1]) ? explode(',', $labels)[1] : 'Credit' }}"
                                {{ ($answers[$index] ?? '') == (!empty(explode(',', $labels)[1]) ? explode(',', $labels)[1] : 'Credit') ? 'checked' : '' }}>
                        </td>

                        <td class="text-center">

                            <button type="button"
                                    class="btn btn-sm btn-danger remove-row">
                                <i class="bi bi-trash"></i>
                            </button>

                            <input type="hidden"
                                name="table_mcq_answers[]"
                                value="{{ $answers[$index] ?? '' }}"
                                class="hidden-answer">

                        </td>

                    </tr>

                @empty

                    <tr class="table-mcq-row">

                        <td>
                            <input type="text"
                                name="table_mcq_statements[]"
                                class="form-control table-mcq-statement"
                                placeholder="Enter statement"
                                required>
                        </td>

                        <td class="text-center">
                            <input type="radio"
                                name="row_answer_0"
                                value="Debit">
                        </td>

                        <td class="text-center">
                            <input type="radio"
                                name="row_answer_0"
                                value="Credit">
                        </td>

                        <td class="text-center">

                            <button type="button"
                                    class="btn btn-sm btn-danger remove-row">
                                <i class="bi bi-trash"></i>
                            </button>

                            <input type="hidden"
                                name="table_mcq_answers[]"
                                class="hidden-answer">

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    <button type="button"
            class="btn btn-sm btn-light border"
            id="addRow">
        <i class="bi bi-plus-circle me-1"></i>
        Add Statement
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let rowCounter =
        document.querySelectorAll('.table-mcq-row').length;

    function updateLabels() {

        const label1 =
            document.getElementById('label1').value || 'Debit';

        const label2 =
            document.getElementById('label2').value || 'Credit';

        document.getElementById('table_mcq_labels').value =
            label1 + ',' + label2;

        document.querySelector('.label1-header').textContent =
            label1;

        document.querySelector('.label2-header').textContent =
            label2;

        document.querySelectorAll('.table-mcq-row').forEach(row => {

            const radios =
                row.querySelectorAll('input[type=radio]');

            if (radios.length === 2) {

                radios[0].value = label1;
                radios[1].value = label2;

            }

        });
    }

    document.getElementById('label1')
        .addEventListener('input', updateLabels);

    document.getElementById('label2')
        .addEventListener('input', updateLabels);

    document.getElementById('addRow')
        .addEventListener('click', function () {

        const label1 =
            document.getElementById('label1').value || 'Debit';

        const label2 =
            document.getElementById('label2').value || 'Credit';

        const rowHtml = `
            <tr class="table-mcq-row">

                <td>
                    <input type="text"
                           name="table_mcq_statements[]"
                           class="form-control table-mcq-statement"
                           placeholder="Enter statement"
                           required>
                </td>

                <td class="text-center">
                    <input type="radio"
                           name="row_answer_${rowCounter}"
                           value="${label1}">
                </td>

                <td class="text-center">
                    <input type="radio"
                           name="row_answer_${rowCounter}"
                           value="${label2}">
                </td>

                <td class="text-center">

                <button type="button"
                        class="btn btn-sm btn-danger remove-row">
                    <i class="bi bi-trash"></i>
                </button>

                <input type="hidden"
                    name="table_mcq_answers[]"
                    class="hidden-answer">

            </td>

            </tr>
        `;

        document.getElementById('table-mcq-rows')
            .insertAdjacentHTML('beforeend', rowHtml);

        rowCounter++;

        bindPasteHandlers();
    });

    document.addEventListener('change', function(e){

        if(e.target.type === 'radio'){

            const row =
                e.target.closest('.table-mcq-row');

            row.querySelector('.hidden-answer').value =
                e.target.value;
        }
    });

    document.addEventListener('click', function(e){

        if(e.target.closest('.remove-row')){

            e.target.closest('.table-mcq-row').remove();
        }
    });

    function bindPasteHandlers() {

        document.querySelectorAll('.table-mcq-statement')
            .forEach(input => {

            if(input.dataset.bound) return;

            input.dataset.bound = true;

            input.addEventListener('paste', function(e){

                const pastedText =
                    (e.clipboardData || window.clipboardData)
                    .getData('text');

                const lines = pastedText
                    .split(/\r?\n/)
                    .map(x => x.trim())
                    .filter(x => x !== '')
                    .map(x =>
                        x.replace(/^\d+\.\s*/, '')
                         .replace(/^\d+\)\s*/, '')
                         .trim()
                    );

                if(lines.length <= 1) return;

                e.preventDefault();

                this.value = lines[0];

                for(let i=1;i<lines.length;i++){

                    document.getElementById('addRow').click();

                    const rows =
                        document.querySelectorAll('.table-mcq-row');

                    const lastRow =
                        rows[rows.length - 1];

                    lastRow.querySelector(
                        '.table-mcq-statement'
                    ).value = lines[i];
                }
            });
        });
    }

    bindPasteHandlers();

    updateLabels();

});
</script>

