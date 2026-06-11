@php
    $question = $question ?? null;

    $labels = old('dropdown_blank_labels', $question->dropdown_labels ?? []);
    $options = old('dropdown_blank_options', $question->dropdown_options ?? []);
    $answers = old('dropdown_blank_answers', $question->correct_answers ?? []);

    // Ensure all are arrays
    $labels = is_array($labels) ? $labels : [];
    $options = is_array($options) ? $options : [];
    $answers = is_array($answers) ? $answers : [];

    // Always show the max number of blanks across fields
    $blankCount = max(count($labels), count($options), count($answers));

    $active = old('question_type', $question->question_type ?? '') === 'dropdown';
@endphp


<div id="dropdown-section" class="{{ $active ? '' : 'd-none' }} mb-3">

    <p class="mb-3">
        <strong>Note:</strong>
        Use <code>[blank]</code> in the <strong>Question</strong> field to add dropdowns.
    </p>

    @if($errors->has('dropdown_mismatch'))
        <div class="alert alert-warning">
            {{ $errors->first('dropdown_mismatch') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="80">Blank</th>
                    <th>Label</th>
                    <th>Options (Comma Separated)</th>
                    <th>Correct Answer</th>
                    <th width="80">Action</th>
                </tr>
            </thead>

            <tbody id="dropdown-blanks">

                @if($blankCount > 0)

                    @for($i = 0; $i < $blankCount; $i++)

                        <tr class="dropdown-blank-set">

                            <td class="text-center fw-semibold">
                                {{ $i + 1 }}
                            </td>

                            <td>
                                <input type="text"
                                    name="dropdown_blank_labels[]"
                                    class="form-control"
                                    placeholder="e.g. Country"
                                    value="{{ $labels[$i] ?? '' }}">
                            </td>

                            <td>
                                <input type="text"
                                    name="dropdown_blank_options[]"
                                    class="form-control"
                                    placeholder="India,USA,UK"
                                    value="{{ is_array($options[$i] ?? '') ? implode(', ', $options[$i]) : ($options[$i] ?? '') }}">
                            </td>

                            <td>
                                <input type="text"
                                    name="dropdown_blank_answers[]"
                                    class="form-control"
                                    placeholder="Correct Answer"
                                    value="{{ $answers[$i] ?? '' }}">
                            </td>

                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-sm btn-danger remove-blank">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>

                        </tr>

                    @endfor

                @else

                    <tr class="dropdown-blank-set">

                        <td class="text-center fw-semibold">1</td>

                        <td>
                            <input type="text"
                                name="dropdown_blank_labels[]"
                                class="form-control"
                                placeholder="e.g. Country">
                        </td>

                        <td>
                            <input type="text"
                                name="dropdown_blank_options[]"
                                class="form-control"
                                placeholder="India,USA,UK">
                        </td>

                        <td>
                            <input type="text"
                                name="dropdown_blank_answers[]"
                                class="form-control"
                                placeholder="Correct Answer">
                        </td>

                        <td class="text-center">
                            <button type="button"
                                    class="btn btn-sm btn-danger remove-blank">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>

                    </tr>

                @endif

            </tbody>

        </table>
    </div>

<button type="button"
        class="btn btn-sm btn-light border"
        id="addDropdownBlank">
    <i class="bi bi-plus-circle me-1"></i>
    Add Blank
</button>

</div>


<script>
   document.addEventListener('DOMContentLoaded', function () {

    const container =
        document.getElementById('dropdown-blanks');

    const addBtn =
        document.getElementById('addDropdownBlank');

    function renumberRows() {

        document.querySelectorAll('.dropdown-blank-set')
            .forEach((row, index) => {

                row.querySelector('.blank-number')
                    ?.remove();

                row.children[0].innerHTML =
                    `<span class="blank-number">${index + 1}</span>`;
            });
    }

    addBtn?.addEventListener('click', function () {

        const rowCount =
            document.querySelectorAll('.dropdown-blank-set').length + 1;

        const html = `
            <tr class="dropdown-blank-set">

                <td class="text-center fw-semibold">
                    ${rowCount}
                </td>

                <td>
                    <input type="text"
                        name="dropdown_blank_labels[]"
                        class="form-control"
                        placeholder="e.g. Country">
                </td>

                <td>
                    <input type="text"
                        name="dropdown_blank_options[]"
                        class="form-control"
                        placeholder="India,USA,UK">
                </td>

                <td>
                    <input type="text"
                        name="dropdown_blank_answers[]"
                        class="form-control"
                        placeholder="Correct Answer">
                </td>

                <td class="text-center">
                    <button type="button"
                            class="btn btn-sm btn-danger remove-blank">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>

            </tr>
        `;

        container.insertAdjacentHTML('beforeend', html);
    });

    document.addEventListener('click', function (e) {

        if (e.target.closest('.remove-blank')) {

            const rows =
                document.querySelectorAll('.dropdown-blank-set');

            if (rows.length > 1) {

                e.target.closest('.dropdown-blank-set').remove();

                renumberRows();
            }
        }
    });

});
</script>

