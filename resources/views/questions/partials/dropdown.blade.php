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
    <p><strong>Note:</strong> Use <code>[blank]</code> in the <strong>Question</strong> field to add dropdowns.</p>

    @if($errors->has('dropdown_mismatch'))
        <div class="alert alert-warning">{{ $errors->first('dropdown_mismatch') }}</div>
    @endif

    <div id="dropdown-blanks">
        @for($i = 0; $i < max(count($labels), count($options), count($answers)); $i++)
            <div class="mb-3 dropdown-blank-set">
                <div class="mb-1">
                    <input type="text" name="dropdown_blank_labels[]" class="form-control mb-1"
                        placeholder="Label for blank {{ $i+1 }}" value="{{ $labels[$i] ?? '' }}">
                </div>
                <input type="text" name="dropdown_blank_options[]" class="form-control mb-1"
                    placeholder="Options comma-separated (e.g., India,USA,UK)" value="{{ is_array($options[$i] ?? '') ? implode(', ', $options[$i]) : ($options[$i] ?? '') }}">
                <input type="text" name="dropdown_blank_answers[]" class="form-control"
                    placeholder="Correct Answer" value="{{ $answers[$i] ?? '' }}">
                <button type="button" class="btn btn-sm btn-danger mt-1 remove-blank">Remove</button>
            </div>
        @endfor
    </div>

    <button type="button" class="btn btn-sm btn-secondary" id="addDropdownBlank">+ Add Dropdown Blank</button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const blanksContainer = document.getElementById('dropdown-blanks');
        const addBtn = document.getElementById('addDropdownBlank');

        addBtn?.addEventListener('click', function () {
            const i = blanksContainer.children.length;
            const html = `
                <div class="mb-3 dropdown-blank-set">
                    <div class="mb-1">
                        <input type="text" name="dropdown_blank_labels[]" class="form-control mb-1"
                            placeholder="Label for blank ${i + 1}">
                    </div>
                    <input type="text" name="dropdown_blank_options[]" class="form-control mb-1"
                        placeholder="Options comma-separated (e.g., India,USA,UK)">
                    <input type="text" name="dropdown_blank_answers[]" class="form-control"
                        placeholder="Correct Answer">
                    <button type="button" class="btn btn-sm btn-danger mt-1 remove-blank">Remove</button>
                </div>
            `;
            blanksContainer.insertAdjacentHTML('beforeend', html);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-blank')) {
                e.target.closest('.dropdown-blank-set')?.remove();
            }
        });
    });
</script>
@endpush
