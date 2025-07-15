@php
    $question = $question ?? null;
    $answer = old('answer', is_array($question->correct_answers ?? null) 
        ? ($question->correct_answers[0] ?? '') 
        : ($question->correct_answers ?? '')
    );
@endphp

<div id="oneword-section" class="d-none mb-3">
    <label class="form-label">Answer</label>
    <input type="text" name="answer" class="form-control" value="{{ $answer }}">
    
    @error('answer')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>
