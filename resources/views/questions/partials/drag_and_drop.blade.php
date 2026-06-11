<div id="drag-drop-section"
     class="mb-3 {{ old('question_type', $question->question_type ?? '') !== 'drag_and_drop' ? 'd-none' : '' }}">

    @if ($errors->any())
        <div class="alert alert-warning">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row mb-3">

        <div class="col-md-6">
            <label class="form-label">Column A Label</label>
            <input type="text"
                   id="column_a_label"
                   name="column_a_label"
                   class="form-control"
                   placeholder="e.g. Statement"
                   value="{{ old('column_a_label', $question->column_a_label ?? 'Column A') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Column B Label</label>
            <input type="text"
                   id="column_b_label"
                   name="column_b_label"
                   class="form-control"
                   placeholder="e.g. Classification"
                   value="{{ old('column_b_label', $question->column_b_label ?? 'Column B') }}">
        </div>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered align-middle">

            <thead class="table-light">
                <tr>
                    <th id="column-a-header">
                        {{ old('column_a_label', $question->column_a_label ?? 'Column A') }}
                    </th>

                    <th id="column-b-header">
                        {{ old('column_b_label', $question->column_b_label ?? 'Column B') }}
                    </th>

                    <th width="80">Action</th>
                </tr>
            </thead>

            <tbody id="drag-drop-rows">

                @php
                    $columnA = old('column_a', $question->column_a ?? []);
                    $columnB = old('column_b', $question->column_b ?? []);
                    $maxRows = max(count($columnA), count($columnB), 1);
                @endphp

                @for($i = 0; $i < $maxRows; $i++)

                    <tr class="drag-row">

                        <td>
                            <input type="text"
                                   name="column_a[]"
                                   class="form-control column-a-input"
                                   value="{{ $columnA[$i] ?? '' }}"
                                   placeholder="Enter Column A Item">
                        </td>

                        <td>
                            <input type="text"
                                   name="column_b[]"
                                   class="form-control column-b-input"
                                   value="{{ $columnB[$i] ?? '' }}"
                                   placeholder="Enter Column B Item">
                        </td>

                        <td class="text-center">
                            <button type="button"
                                    class="btn btn-sm btn-danger remove-drag-row">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>

                    </tr>

                @endfor

            </tbody>

        </table>

    </div>

    <button type="button"
            class="btn btn-sm btn-light border"
            id="addDragRow">
        <i class="bi bi-plus-circle me-1"></i>
        Add Row
    </button>

</div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const rowsContainer =
            document.getElementById('drag-drop-rows');

        const columnALabel =
            document.getElementById('column_a_label');

        const columnBLabel =
            document.getElementById('column_b_label');

        const columnAHeader =
            document.getElementById('column-a-header');

        const columnBHeader =
            document.getElementById('column-b-header');

        function updateHeaders() {

            columnAHeader.textContent =
                columnALabel.value || 'Column A';

            columnBHeader.textContent =
                columnBLabel.value || 'Column B';
        }

        columnALabel?.addEventListener('input', updateHeaders);
        columnBLabel?.addEventListener('input', updateHeaders);

        function addRow() {

            const html = `
                <tr class="drag-row">

                    <td>
                        <input type="text"
                            name="column_a[]"
                            class="form-control column-a-input"
                            placeholder="Enter Column A Item">
                    </td>

                    <td>
                        <input type="text"
                            name="column_b[]"
                            class="form-control column-b-input"
                            placeholder="Enter Column B Item">
                    </td>

                    <td class="text-center">
                        <button type="button"
                                class="btn btn-sm btn-danger remove-drag-row">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>

                </tr>
            `;

            rowsContainer.insertAdjacentHTML('beforeend', html);

            bindPasteHandlers();
        }

        document.getElementById('addDragRow')
            ?.addEventListener('click', addRow);

        document.addEventListener('click', function(e){

            if(e.target.closest('.remove-drag-row')){

                const rows =
                    document.querySelectorAll('.drag-row');

                if(rows.length > 1){
                    e.target.closest('.drag-row').remove();
                }
            }

        });

        function bindPasteHandlers() {

            /*
            |--------------------------------------------------------------------------
            | COLUMN A BULK PASTE
            |--------------------------------------------------------------------------
            */
            document.querySelectorAll('.column-a-input')
                .forEach(input => {

                if(input.dataset.boundA) return;

                input.dataset.boundA = true;

                input.addEventListener('paste', function(e){

                    const text =
                        (e.clipboardData || window.clipboardData)
                        .getData('text');

                    const lines = text
                        .split(/\r?\n/)
                        .map(x => x.trim())
                        .filter(x => x !== '');

                    if(lines.length <= 1) return;

                    e.preventDefault();

                    let rows =
                        document.querySelectorAll('.drag-row');

                    while(rows.length < lines.length){

                        addRow();

                        rows =
                            document.querySelectorAll('.drag-row');
                    }

                    rows.forEach((row, index) => {

                        if(lines[index] !== undefined){

                            row.querySelector('.column-a-input')
                                .value = lines[index];
                        }

                    });

                });

            });

            /*
            |--------------------------------------------------------------------------
            | COLUMN B BULK PASTE
            |--------------------------------------------------------------------------
            */
            document.querySelectorAll('.column-b-input')
                .forEach(input => {

                if(input.dataset.boundB) return;

                input.dataset.boundB = true;

                input.addEventListener('paste', function(e){

                    const text =
                        (e.clipboardData || window.clipboardData)
                        .getData('text');

                    const lines = text
                        .split(/\r?\n/)
                        .map(x => x.trim())
                        .filter(x => x !== '');

                    if(lines.length <= 1) return;

                    e.preventDefault();

                    let rows =
                        document.querySelectorAll('.drag-row');

                    while(rows.length < lines.length){

                        addRow();

                        rows =
                            document.querySelectorAll('.drag-row');
                    }

                    rows.forEach((row, index) => {

                        if(lines[index] !== undefined){

                            row.querySelector('.column-b-input')
                                .value = lines[index];
                        }

                    });

                });

            });
        }

        bindPasteHandlers();
        updateHeaders();

    });
    </script>