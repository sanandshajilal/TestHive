{{-- ========================================================= --}}
{{-- Scenario Child Question Renderer --}}
{{-- Supports:
     - MCQ
     - Multiple Select
     - One Word
--}}
{{-- ========================================================= --}}

<div class="question-header mb-3">

    <div class="question-text">
        {!! $item->question_text !!}
    </div>

</div>

{{-- ========================= --}}
{{-- MCQ --}}
{{-- ========================= --}}

@if($item->question_type == 'mcq')

    @php
        $options = is_array($item->options)
            ? $item->options
            : json_decode($item->options, true);

        $selected = old("scenario_answers.$item->id");
    @endphp

    @foreach($options as $key => $text)

        <div class="option mb-2">

            <label class="w-100 mb-0">

                <input
                    type="radio"
                    name="scenario_answers[{{ $item->id }}]"
                    value="{{ $key }}"
                    @checked($selected == $key)
                >

                <span class="ms-2">
                    {!! $text !!}
                </span>

            </label>

        </div>

    @endforeach

{{-- ========================= --}}
{{-- Multiple Select --}}
{{-- ========================= --}}

@elseif($item->question_type == 'multiple_select')

    @php
        $options = is_array($item->options)
            ? $item->options
            : json_decode($item->options, true);

        $selected = old("scenario_answers.$item->id", []);

        if (!is_array($selected)) {
            $selected = [];
        }
    @endphp

    @foreach($options as $key => $text)

        <div class="option mb-2">

            <label class="w-100 mb-0">

                <input
                    type="checkbox"
                    name="scenario_answers[{{ $item->id }}][]"
                    value="{{ $key }}"
                    @checked(in_array($key, $selected))
                >

                <span class="ms-2">
                    {!! $text !!}
                </span>

            </label>

        </div>

    @endforeach

{{-- ========================= --}}
{{-- One Word --}}
{{-- ========================= --}}

@elseif($item->question_type == 'one_word')

    @php
        $selected = old("scenario_answers.$item->id");
    @endphp

    <div class="mb-3">

        <label class="form-label fw-semibold">

            Type your answer

        </label>

        <input
            type="text"
            class="form-control"
            name="scenario_answers[{{ $item->id }}]"
            value="{{ $selected }}"
            autocomplete="off"
        >

    </div>

{{-- ========================= --}}
{{-- Unsupported --}}
{{-- ========================= --}}

@else

    <div class="alert alert-warning mb-0">

        Unsupported question type.

    </div>

@endif

