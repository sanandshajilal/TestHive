@php
    $question = $question ?? null;
    $options = old('options', $question->options ?? []);
    $correctOptions = old('correct_options', $question->correct_answers ?? []);
@endphp

<div id="options-section" class="d-none mb-3">
    <label class="form-label">Options</label>
  

    @foreach(['a', 'b', 'c', 'd'] as $opt)
        <div class="input-group mb-2">
            <span class="input-group-text">Option {{ strtoupper($opt) }}</span>
            <input
                type="text"
                name="options[{{ $opt }}]"
                class="form-control"
                value="{{ $options[$opt] ?? '' }}"
            >
            <div class="input-group-text">
                <input
                    type="checkbox"
                    name="correct_options[]"
                    value="{{ $opt }}"
                    id="correct_{{ $opt }}"
                    {{ in_array($opt, $correctOptions) ? 'checked' : '' }}
                >
            </div>  
            <label class="input-group-text" for="correct_{{ $opt }}">Correct</label>
        </div>
    @endforeach

        @if($errors->has('correct_options'))
                <div class="text-danger mt-1">
                    {{ $errors->first('correct_options') }}
                </div>
            @endif

            @if($errors->has('options'))
            <div class="text-danger mt-1">
                {{ $errors->first('options') }}
            </div>
        @endif

    <small class="text-muted">
        ☑ Check correct option(s). For MCQ, select only one. For Multiple Select, select all correct ones.
    </small>
</div>
