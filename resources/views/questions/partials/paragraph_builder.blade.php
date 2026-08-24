
<div id="paragraph-builder-section" class="d-none">

    {{-- ===================================================== --}}
    {{-- Scenario Builder --}}
    {{-- ===================================================== --}}

    <div class="border-bottom pb-2 mb-3 mt-4">
        <h6 class="fw-semibold mb-1">
            <i class="bi bi-file-earmark-richtext me-2" style="color:#832b00;"></i>
           Scenario Questions
        </h6>

        <small class="text-muted">
            Add one or more questions based on the scenario entered above.
        </small>
    </div>

   

    {{-- ===================================================== --}}
    {{-- Child Questions --}}
    {{-- ===================================================== --}}

    
    <div id="childQuestionsContainer"></div>

    {{-- Add Button --}}
    <div class="text-center my-4">

        <button
            type="button"
            class="btn btn-secondary px-4 "
            id="addChildQuestion">

            <i class="bi bi-plus-circle me-2"></i>

            Add Next Question

        </button>

    </div>

    {{-- ===================================================== --}}
    {{-- Hidden Template --}}
    {{-- ===================================================== --}}

    <template id="childQuestionTemplate">

        <div class="card shadow-sm mb-4 child-question-card"
             data-index="__INDEX__">

            {{-- Header --}}
            <div
                class="card-header bg-light d-flex justify-content-between align-items-center child-header user-select-none"
                style="cursor:pointer;">

                <div>

                    <div class="fw-semibold child-title">
                        Question 1
                    </div>

                    <small class="text-muted child-subtitle">
                        MCQ
                    </small>

                </div>

                <div class="d-flex align-items-center">

                    <i class="bi bi-chevron-down me-3 child-toggle"></i>

                    <button
                        type="button"
                        class="btn btn-outline-danger btn-sm remove-child">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>

            </div>

            {{-- Body --}}
            <div class="card-body child-body collapse show">

                {{-- ============================================ --}}
                {{-- Question Type --}}
                {{-- ============================================ --}}

                <div class="row mb-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Question Type
                        </label>

                        <select
                            class="form-select child-question-type"
                            name="child_questions[__INDEX__][question_type]">

                            <option value="mcq">MCQ</option>

                            <option value="multiple_select">
                                Multiple Select
                            </option>

                            <option value="one_word">
                                One Word
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">
                            Marks
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            name="child_questions[__INDEX__][marks]"
                            value="2"
                            min="1">

                    </div>

                </div>

                {{-- ============================================ --}}
                {{-- Question --}}
                {{-- ============================================ --}}

                <div class="mb-4">

                    <label class="form-label">
                        Question
                    </label>

                    <textarea
                        class="form-control child-question-editor"
                        rows="5"
                        name="child_questions[__INDEX__][question]"></textarea>

                </div>

                {{-- ============================================ --}}
                {{-- MCQ / Multiple Select --}}
                {{-- ============================================ --}}

                <div class="child-options-section">

                    <label class="form-label">
                        Options
                    </label>

                    @foreach(['a','b','c','d'] as $opt)

                        <div class="input-group mb-2">

                            <span class="input-group-text">

                                Option {{ strtoupper($opt) }}

                            </span>

                            <input
                                type="text"
                                class="form-control child-option"
                                name="child_questions[__INDEX__][options][{{ $opt }}]">

                            <div class="input-group-text">

                                <input
                                    type="radio"
                                    class="child-correct"
                                    name="child_questions[__INDEX__][correct_options][]"
                                    value="{{ $opt }}">

                            </div>

                            <label class="input-group-text">

                                Correct

                            </label>

                        </div>

                    @endforeach

                    <small class="text-muted child-option-help">
                        Select the correct answer.
                    </small>

                </div>

                {{-- ============================================ --}}
                {{-- One Word --}}
                {{-- ============================================ --}}

                <div class="child-oneword-section d-none mt-3">

                    <label class="form-label">
                        Correct Answer
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="child_questions[__INDEX__][answer]"
                        placeholder="Enter the correct answer">

                </div>

            </div>

        </div>

    </template>

</div>