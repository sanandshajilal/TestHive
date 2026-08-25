<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Paper;
use App\Models\Topic;
use App\Models\SubTopic;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller
{
    public function index()
    {
        return view('questions.index', [
            'questions' => Question::with([
            'paper',
            'topic',
            'subTopic',
            'children'
        ])
        ->whereNull('parent_question_id')
        ->latest()
        ->get(),
            'papers' => Paper::all(),
            'topics' => Topic::all(),
            'subtopics' => SubTopic::all(),
        ]);
    }

    public function create()
    {
        return view('questions.create', [
            'papers' => Paper::all(),
            'topics' => Topic::all(),
            'subtopics' => SubTopic::all(),
        ]);
    }

    public function store(Request $request)
    {
            \Log::info('QUESTION STORE HIT', [
                'question_type' => $request->input('question_type'),
                'all' => $request->all(),
            ]);

        $request->validate([
            'paper_id' => 'required|exists:papers,id',
            'topic_id' => 'required|exists:topics,id',
            'sub_topic_id' => 'nullable|exists:sub_topics,id',
            'question_type' => 'required|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Scenario
        |--------------------------------------------------------------------------
        |
        | The Scenario/paragraph itself has no marks.
        | Marks are assigned to its child questions.
        |
        */

            if ($request->question_type === 'paragraph') {

                $request->validate([
                    'paper_id' => 'required|exists:papers,id',
                    'topic_id' => 'required|exists:topics,id',
                    'sub_topic_id' => 'nullable|exists:sub_topics,id',
                    'question_text' => 'required|string',
                    'child_questions' => 'required|array|min:1',

                    'child_questions.*.question_type'
                        => 'required|in:mcq,multiple_select,one_word',

                    'child_questions.*.question'
                        => 'required|string',

                    'child_questions.*.marks'
                        => 'required|integer|min:1',
                ]);

                
                
                return $this->storeScenario($request);
            }

        /*
        |--------------------------------------------------------------------------
        | Standalone Question
        |--------------------------------------------------------------------------
        |
        | All normal questions require marks.
        |
        */

        $request->validate([
            'marks' => 'required|integer|min:1',
        ]);

        $question = new Question();
        $question->paper_id = $request->paper_id;
        $question->topic_id = $request->topic_id;
        $question->sub_topic_id = $request->sub_topic_id;
        $question->question_type = $request->question_type;
        $question->marks = $request->marks;

        switch ($request->question_type) {
            case 'mcq':

            $this->validateMcq($request->all());

            $question->question_text = $request->question_text;
            $question->options = array_map('trim', $request->options ?? []);
            $question->correct_answers = $request->correct_options ?? [];

            break;


             case 'multiple_select':

            $this->validateMultipleSelect($request->all());

            $question->question_text = $request->question_text;
            $question->options = array_map('trim', $request->options ?? []);
            $question->correct_answers = $request->correct_options ?? [];

            break;

            case 'one_word':

            $this->validateOneWord($request->all());

            $question->question_text = $request->question_text;
            $question->correct_answers = [
                trim($request->answer)
            ];

            break;

                case 'table_mcq':
                    $question->question_text = $request->question_text;

                    $statements = $request->table_mcq_statements ?? [];
                    $answers = $request->table_mcq_answers ?? [];
                    $labels = explode(',', $request->table_mcq_labels ?? 'Debit,Credit');

                    $validLabels = array_map('trim', $labels);

                    // 1. Validate question text
                    if (trim(strip_tags($request->question_text)) === '') {
                        return back()->withInput()->withErrors(['question_text' => 'Question text must not be empty.']);
                    }

                    // 2. At least one statement
                    if (empty(array_filter($statements))) {
                        return back()->withInput()->withErrors(['table_mcq_statements' => 'Please enter at least one statement.']);
                    }

                    // 3. Check all statements have answers and are valid
                    foreach ($statements as $i => $stmt) {
                        $stmt = trim($stmt);
                        $ans = trim($answers[$i] ?? '');

                        if ($stmt === '') {
                            return back()->withInput()->withErrors(["table_mcq_statements.$i" => "Statement #".($i+1)." is empty."]);
                        }

                        if ($ans === '' || !in_array($ans, $validLabels)) {
                            return back()->withInput()->withErrors(["table_mcq_answers.$i" => "Answer for statement #".($i+1)." must be one of: ".implode(', ', $validLabels)."."]);
                        }
                    }

                    // Optional: Check for duplicate statements
                    $filtered = array_filter($statements);
                    if (count($filtered) !== count(array_unique($filtered))) {
                        return back()->withInput()->withErrors(['table_mcq_statements' => 'Duplicate statements are not allowed.']);
                    }

                    $question->options = $statements;
                    $question->correct_answers = $answers;
                    $question->table_mcq_labels = $request->table_mcq_labels;
                    break;


            case 'drag_and_drop':

                    $question->question_text = $request->question_text;

                    $columnA = array_values(array_filter(
                        $request->input('column_a', []),
                        fn($v) => trim($v) !== ''
                    ));

                    $columnB = array_values(array_filter(
                        $request->input('column_b', []),
                        fn($v) => trim($v) !== ''
                    ));

                    if (empty($columnA)) {
                        return back()->withInput()->withErrors([
                            'column_a' => 'Please enter at least one Column A item.'
                        ]);
                    }

                    if (empty($columnB)) {
                        return back()->withInput()->withErrors([
                            'column_b' => 'Please enter at least one Column B item.'
                        ]);
                    }

                    if (count($columnA) !== count($columnB)) {
                        return back()->withInput()->withErrors([
                            'column_b' => 'Column A and Column B must contain the same number of items.'
                        ]);
                    }

                    $question->options = [
                        'column_a_label' => $request->input('column_a_label', 'Column A'),
                        'column_b_label' => $request->input('column_b_label', 'Column B'),
                        'column_a' => $columnA,
                        'column_b' => $columnB,
                    ];

                    $matches = [];

                    for ($i = 0; $i < count($columnA); $i++) {
                        $matches[$i] = $i;
                    }

                    $question->correct_answers = json_encode($matches);

                    break;


            case 'dropdown':
            // Use question_text field (with [blank]) as main sentence
            $question->question_text = $request->question_text;

            // Detect [blank] count
            preg_match_all('/\[blank\]/', $request->question_text, $matches);
            $blankCount = count($matches[0]);

            // Get inputs
            $labels = $request->dropdown_blank_labels ?? [];
            $optionsList = $request->dropdown_blank_options ?? [];
            $answers = $request->dropdown_blank_answers ?? [];

            // Validate blank count matches
                if (
                        $blankCount !== count($labels) ||
                        $blankCount !== count($answers) ||
                        $blankCount !== count($optionsList)
                    ) {
                        return back()
                            ->withInput()
                            ->withErrors([
                                'dropdown_mismatch' => "Number of [blank] placeholders ($blankCount) must match the number of labels/options/answers (" . count($labels) . ")."
                            ]);
                    }


            // Build options array
            $blanks = [];
            for ($i = 0; $i < $blankCount; $i++) {
                $rawOptions = $optionsList[$i];

                if (is_array($rawOptions)) {
                    if (count($rawOptions) === 1 && str_contains($rawOptions[0], ',')) {
                        $options = array_map('trim', explode(',', $rawOptions[0]));
                    } else {
                        $options = array_map('trim', $rawOptions);
                    }
                } else {
                    $options = preg_split('/\r\n|\r|\n/', $rawOptions);
                    $options = array_values(array_filter(
                        array_map('trim', $options)
                    ));
                }

                $blanks[] = [
                    'label' => $labels[$i],
                    'options' => $options,
                ];
            }

            $question->options = $blanks;
            $question->correct_answers = array_slice($answers, 0, $blankCount);
            break;

            case 'paragraph':

            $question->question_text = $request->question_text;

            if (trim(strip_tags($request->question_text)) === '') {
                return back()->withInput()->withErrors([
                    'question_text' => 'Paragraph cannot be empty.'
                ]);
            }

            // Paragraph itself has no options or answers
            $question->options = null;
            $question->correct_answers = null;

            break;


        }

      $question->save();

        return redirect()
            ->route('questions.create')
            ->with('success', 'Question created successfully.')
            ->withInput([
                'paper_id' => $request->paper_id,
                'topic_id' => $request->topic_id,
                'sub_topic_id' => $request->sub_topic_id,
                'question_type' => $request->question_type,
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Validation Helpers
    |--------------------------------------------------------------------------
    */

    private function validateMcq(array $data)
    {
        $questionText = trim(strip_tags($data['question_text'] ?? $data['question'] ?? ''));

        if ($questionText === '') {
            throw ValidationException::withMessages([
                'question_text' => 'Question text is required.',
            ]);
        }

        $options = array_map('trim', $data['options'] ?? []);
        $filteredOptions = array_filter($options, fn($opt) => $opt !== '');

        if (count($filteredOptions) !== 4) {
            throw ValidationException::withMessages([
                'options' => 'All four options must be filled in.',
            ]);
        }

        if (count($filteredOptions) !== count(array_unique($filteredOptions))) {
            throw ValidationException::withMessages([
                'options' => 'Options must be unique.',
            ]);
        }

        $correct = $data['correct_options'] ?? [];

        if (!is_array($correct)) {
            $correct = [$correct];
        }

        if (count($correct) !== 1) {
            throw ValidationException::withMessages([
                'correct_options' => 'Please select exactly one correct answer.',
            ]);
        }
    }


    private function validateMultipleSelect(array $data)
    {
        $questionText = trim(strip_tags($data['question_text'] ?? $data['question'] ?? ''));

        if ($questionText === '') {
            throw ValidationException::withMessages([
                'question_text' => 'Question text is required.',
            ]);
        }

        $options = array_map('trim', $data['options'] ?? []);
        $filteredOptions = array_filter($options, fn($opt) => $opt !== '');

        if (count($filteredOptions) !== 4) {
            throw ValidationException::withMessages([
                'options' => 'All four options must be filled in.',
            ]);
        }

        if (count($filteredOptions) !== count(array_unique($filteredOptions))) {
            throw ValidationException::withMessages([
                'options' => 'Options must be unique.',
            ]);
        }

        $correct = $data['correct_options'] ?? [];

        if (!is_array($correct)) {
            $correct = [$correct];
        }

        if (count($correct) < 1) {
            throw ValidationException::withMessages([
                'correct_options' => 'Please select at least one correct answer.',
            ]);
        }
    }


    private function validateOneWord(array $data)
    {
        $questionText = trim(strip_tags($data['question_text'] ?? $data['question'] ?? ''));

        if ($questionText === '') {
            throw ValidationException::withMessages([
                'question_text' => 'Question text is required.',
            ]);
        }

        $answer = trim($data['answer'] ?? '');

        if ($answer === '') {
            throw ValidationException::withMessages([
                'answer' => 'Please provide the correct answer.',
            ]);
        }
    }

private function storeScenario(Request $request)
{
    
    try {

        \Log::info('SCENARIO CREATION STARTED', [
            'question_type' => $request->input('question_type'),
            'paper_id' => $request->input('paper_id'),
            'topic_id' => $request->input('topic_id'),
            'sub_topic_id' => $request->input('sub_topic_id'),
            'child_count' => count($request->input('child_questions', [])),
            'child_questions' => $request->input('child_questions', []),
        ]);

        DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | Parent Scenario
            |--------------------------------------------------------------------------
            */

            $scenario = new Question();

            $scenario->paper_id = $request->paper_id;
            $scenario->topic_id = $request->topic_id;
            $scenario->sub_topic_id = $request->sub_topic_id;

            $scenario->question_type = 'paragraph';
            $scenario->question_text = $request->question_text;
            $scenario->marks = 0;

            dd('PARENT DATA ASSIGNED');

            $scenario->save();

            \Log::info('SCENARIO PARENT SAVED', [
                'scenario_id' => $scenario->id,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Child Questions
            |--------------------------------------------------------------------------
            */

            foreach ($request->input('child_questions', []) as $index => $childData) {

                \Log::info('SAVING SCENARIO CHILD', [
                    'index' => $index,
                    'data' => $childData,
                ]);

                $child = new Question();

                $child->paper_id = $scenario->paper_id;
                $child->topic_id = $scenario->topic_id;
                $child->sub_topic_id = $scenario->sub_topic_id;

                $child->parent_question_id = $scenario->id;

                $child->question_type = $childData['question_type'];
                $child->question_text = $childData['question'];
                $child->marks = $childData['marks'] ?? 2;


                switch ($childData['question_type']) {

                    case 'mcq':

                        $this->validateMcq($childData);

                        $child->options = array_map(
                            'trim',
                            $childData['options'] ?? []
                        );

                        $child->correct_answers =
                            $childData['correct_options'] ?? [];

                        break;


                    case 'multiple_select':

                        $this->validateMultipleSelect($childData);

                        $child->options = array_map(
                            'trim',
                            $childData['options'] ?? []
                        );

                        $child->correct_answers =
                            $childData['correct_options'] ?? [];

                        break;


                    case 'one_word':

                        $this->validateOneWord($childData);

                        $child->options = null;

                        $child->correct_answers = [
                            trim($childData['answer'] ?? '')
                        ];

                        break;
                }


                $child->save();

                \Log::info('SCENARIO CHILD SAVED', [
                    'child_id' => $child->id,
                    'parent_id' => $scenario->id,
                ]);
            }

        });

        \Log::info('SCENARIO CREATION COMPLETED');

        return redirect()
            ->route('questions.create')
            ->with('success', 'Scenario created successfully.');

    } catch (\Throwable $e) {

        \Log::error('SCENARIO CREATION FAILED', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        throw $e;
    }
}


        public function edit(Question $question)
        {
            // --------------------------------------------------
            // Paragraph (Scenario)
            // --------------------------------------------------

            if ($question->question_type === 'paragraph') {

                $question->load('children');

                foreach ($question->children as $child) {

                    switch ($child->question_type) {

                        case 'mcq':
                        case 'multiple_select':

                            $child->options = is_array($child->options)
                                ? $child->options
                                : json_decode($child->options, true);

                            $child->correct_answers = is_array($child->correct_answers)
                                ? $child->correct_answers
                                : json_decode($child->correct_answers, true);

                            break;

                        case 'one_word':

                            $child->correct_answers = is_array($child->correct_answers)
                                ? $child->correct_answers
                                : json_decode($child->correct_answers, true);

                            break;

                    }

                }

            }

            // --------------------------------------------------
            // Existing Question Types
            // --------------------------------------------------

            switch ($question->question_type) {

                case 'mcq':
                case 'multiple_select':

                    $question->options = is_array($question->options)
                        ? $question->options
                        : json_decode($question->options, true);

                    $question->correct_answers = is_array($question->correct_answers)
                        ? $question->correct_answers
                        : json_decode($question->correct_answers, true);

                    break;

                case 'one_word':

                    $question->correct_answers = is_array($question->correct_answers)
                        ? $question->correct_answers
                        : json_decode($question->correct_answers, true);

                    break;

                case 'table_mcq':

                    $question->options = is_array($question->options)
                        ? $question->options
                        : json_decode($question->options, true);

                    $question->correct_answers = is_array($question->correct_answers)
                        ? $question->correct_answers
                        : json_decode($question->correct_answers, true);

                    break;

                case 'drag_and_drop':

                    $options = is_array($question->options)
                        ? $question->options
                        : json_decode($question->options, true);

                    $question->column_a_label = $options['column_a_label'] ?? 'Column A';
                    $question->column_b_label = $options['column_b_label'] ?? 'Column B';
                    $question->column_a = $options['column_a'] ?? [];
                    $question->column_b = $options['column_b'] ?? [];

                    break;

                case 'dropdown':

                    $decodedOptions = is_array($question->options)
                        ? $question->options
                        : json_decode($question->options ?? '[]', true);

                    $decodedAnswers = is_array($question->correct_answers)
                        ? $question->correct_answers
                        : json_decode($question->correct_answers ?? '[]', true);

                    $question->options = $decodedOptions;
                    $question->correct_answers = $decodedAnswers;

                    $question->dropdown_labels = array_column($decodedOptions, 'label');
                    $question->dropdown_options = array_column($decodedOptions, 'options');

                    break;
            }

            return view('questions.edit', [
                'question' => $question,
                'papers' => Paper::all(),
                'topics' => Topic::where('paper_id', $question->paper_id)->get(),
                'subtopics' => SubTopic::where('topic_id', $question->topic_id)->get(),
            ]);
        }


        public function update(Request $request, $id)
        {
            $question = Question::findOrFail($id);

            if ($question->question_type === 'paragraph') {
                return $this->updateScenario($request, $question);
            }

            $request->validate([
                'paper_id' => 'required|exists:papers,id',
                'topic_id' => 'required|exists:topics,id',
                'sub_topic_id' => 'nullable|exists:sub_topics,id',
                'question_type' => 'required|string',
                'marks' => 'required|integer|min:1',
            ]);

            $question->paper_id = $request->paper_id;
            $question->topic_id = $request->topic_id;
            $question->sub_topic_id = $request->sub_topic_id;
            $question->question_type = $request->question_type;
            $question->marks = $request->marks;

            switch ($request->question_type) {
                case 'mcq':
                case 'multiple_select':
                    $question->question_text = $request->question_text;
                    $options = array_map('trim', $request->options ?? []);
                    $correct = $request->correct_options ?? [];

                    // Remove empty ones
                    $filteredOptions = array_filter($options, fn($opt) => $opt !== '');

                    if (count($filteredOptions) !== count($options)) {
                        return back()->withInput()->withErrors([
                            'options' => 'All MCQ options must be filled in.',
                        ]);
                    }

                    if (count($filteredOptions) !== count(array_unique($filteredOptions))) {
                        return back()->withInput()->withErrors([
                            'options' => 'MCQ options must be unique.',
                        ]);
                    }

                    if (empty($correct)) {
                        return back()->withInput()->withErrors([
                            'correct_options' => 'Please select at least one correct answer.',
                        ]);
                    }

                    $question->options = $filteredOptions;
                    $question->correct_answers = $correct;

                    $correct = $request->correct_options ?? [];

                        if (empty($correct)) {
                            return back()->withInput()->withErrors([
                                'correct_options' => 'Please select at least one correct answer.',
                            ]);
                        }

                        $question->correct_answers = $correct;

                    break;

                case 'one_word':
                    $question->question_text = $request->question_text;

                    if (trim($request->question_text) === '') {
                        return back()->withInput()->withErrors([
                            'question_text' => 'Question text is required.',
                        ]);
                    }

                    $answer = trim($request->answer);

                    if ($answer === '') {
                        return back()->withInput()->withErrors([
                            'answer' => 'Please provide an answer.',
                        ]);
                    }

                    $question->correct_answers = [$answer];
                    break;

                 case 'table_mcq':
                    $question->question_text = $request->question_text;

                    $statements = $request->table_mcq_statements ?? [];
                    $answers = $request->table_mcq_answers ?? [];
                    $labels = explode(',', $request->table_mcq_labels ?? 'Debit,Credit');

                    $validLabels = array_map('trim', $labels);

                    // 1. Validate question text
                    if (trim(strip_tags($request->question_text)) === '') {
                        return back()->withInput()->withErrors(['question_text' => 'Question text must not be empty.']);
                    }

                    // 2. At least one statement
                    if (empty(array_filter($statements))) {
                        return back()->withInput()->withErrors(['table_mcq_statements' => 'Please enter at least one statement.']);
                    }

                    // 3. Check all statements have answers and are valid
                    foreach ($statements as $i => $stmt) {
                        $stmt = trim($stmt);
                        $ans = trim($answers[$i] ?? '');

                        if ($stmt === '') {
                            return back()->withInput()->withErrors(["table_mcq_statements.$i" => "Statement #".($i+1)." is empty."]);
                        }

                        if ($ans === '' || !in_array($ans, $validLabels)) {
                            return back()->withInput()->withErrors(["table_mcq_answers.$i" => "Answer for statement #".($i+1)." must be one of: ".implode(', ', $validLabels)."."]);
                        }
                    }

                    // Optional: Check for duplicate statements
                    $filtered = array_filter($statements);
                    if (count($filtered) !== count(array_unique($filtered))) {
                        return back()->withInput()->withErrors(['table_mcq_statements' => 'Duplicate statements are not allowed.']);
                    }

                    $question->options = $statements;
                    $question->correct_answers = $answers;
                    $question->table_mcq_labels = $request->table_mcq_labels;
                    break;


               case 'drag_and_drop':

                    $question->question_text = $request->question_text;

                    $columnA = array_values(array_filter(
                        $request->input('column_a', []),
                        fn($v) => trim($v) !== ''
                    ));

                    $columnB = array_values(array_filter(
                        $request->input('column_b', []),
                        fn($v) => trim($v) !== ''
                    ));

                    if (empty($columnA)) {
                        return back()->withInput()->withErrors([
                            'column_a' => 'Please enter at least one Column A item.'
                        ]);
                    }

                    if (empty($columnB)) {
                        return back()->withInput()->withErrors([
                            'column_b' => 'Please enter at least one Column B item.'
                        ]);
                    }

                    if (count($columnA) !== count($columnB)) {
                        return back()->withInput()->withErrors([
                            'column_b' => 'Column A and Column B must contain the same number of items.'
                        ]);
                    }

                    $question->options = [
                        'column_a_label' => $request->input('column_a_label', 'Column A'),
                        'column_b_label' => $request->input('column_b_label', 'Column B'),
                        'column_a' => $columnA,
                        'column_b' => $columnB,
                    ];

                    $matches = [];

                    for ($i = 0; $i < count($columnA); $i++) {
                        $matches[$i] = $i;
                    }

                    $question->correct_answers = json_encode($matches);

                    break;


               case 'dropdown':
                // Use question_text field (with [blank]) as main sentence
                $question->question_text = $request->question_text;

                // Detect [blank] count
                preg_match_all('/\[blank\]/', $request->question_text, $matches);
                $blankCount = count($matches[0]);

                // Get inputs
                $labels = $request->dropdown_blank_labels ?? [];
                $optionsList = $request->dropdown_blank_options ?? [];
                $answers = $request->dropdown_blank_answers ?? [];

                // Validate blank count matches
                if (
                    $blankCount !== count($labels) ||
                    $blankCount !== count($answers) ||
                    $blankCount !== count($optionsList)
                ) {
                    return back()
                        ->withInput()
                       ->withErrors([
                            'dropdown_mismatch' => "Number of [blank] placeholders ($blankCount) must match the number of labels/options/answers (" . count($labels) . ").",
                        ]);

                }

                // Build options array
                $blanks = [];
                for ($i = 0; $i < $blankCount; $i++) {
                    $rawOptions = $optionsList[$i];

                    if (is_array($rawOptions)) {
                        if (count($rawOptions) === 1 && str_contains($rawOptions[0], ',')) {
                            $options = array_map('trim', explode(',', $rawOptions[0]));
                        } else {
                            $options = array_map('trim', $rawOptions);
                        }
                    } else {
                        $options = preg_split('/\r\n|\r|\n/', $rawOptions);
                        $options = array_values(array_filter(
                            array_map('trim', $options)
                        ));
                    }

                    $blanks[] = [
                        'label' => $labels[$i],
                        'options' => $options,
                    ];
                }

                $question->options = $blanks;
                $question->correct_answers = array_slice($answers, 0, $blankCount);
                break;

                case 'paragraph':

                $question->question_text = $request->question_text;

                if (trim(strip_tags($request->question_text)) === '') {
                    return back()->withInput()->withErrors([
                        'question_text' => 'Paragraph cannot be empty.'
                    ]);
                }

                // Paragraph itself has no options or answers
                $question->options = null;
                $question->correct_answers = null;

                break;
            }
            $question->save();

            return redirect()->route('questions.index')->with('success', 'Question updated successfully!');
        }

        private function updateScenario(Request $request, Question $scenario)
        {
            DB::transaction(function () use ($request, $scenario) {

                /*
                |--------------------------------------------------------------------------
                | Update Parent Scenario
                |--------------------------------------------------------------------------
                */

                $scenario->paper_id = $request->paper_id;
                $scenario->topic_id = $request->topic_id;
                $scenario->sub_topic_id = $request->sub_topic_id;

                $scenario->question_text = $request->question_text;
                $scenario->marks = 0;

                $scenario->save();


                /*
                |--------------------------------------------------------------------------
                | Track submitted child IDs
                |--------------------------------------------------------------------------
                */

                $submittedChildIds = [];


                /*
                |--------------------------------------------------------------------------
                | Update / Create Child Questions
                |--------------------------------------------------------------------------
                */

                foreach ($request->child_questions as $childData) {

                    /*
                    |--------------------------------------------------------------------------
                    | Existing Child
                    |--------------------------------------------------------------------------
                    */

                    if (!empty($childData['id'])) {

                        $child = Question::findOrFail($childData['id']);

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | New Child
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $child = new Question();

                        $child->parent_question_id = $scenario->id;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Common Fields
                    |--------------------------------------------------------------------------
                    */

                    $child->paper_id = $scenario->paper_id;
                    $child->topic_id = $scenario->topic_id;
                    $child->sub_topic_id = $scenario->sub_topic_id;

                    $child->question_type = $childData['question_type'];

                    $child->question_text = $childData['question'];

                    $child->marks = $childData['marks'] ?? 2;


                    /*
                    |--------------------------------------------------------------------------
                    | Question Type
                    |--------------------------------------------------------------------------
                    */

                    switch ($childData['question_type']) {

                        /*
                        |--------------------------------------------------------------------------
                        | MCQ
                        |--------------------------------------------------------------------------
                        */

                        case 'mcq':

                            $child->options = array_map(
                                'trim',
                                $childData['options'] ?? []
                            );

                            $child->correct_answers =
                                $childData['correct_options'] ?? [];

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | Multiple Select
                        |--------------------------------------------------------------------------
                        */

                            case 'multiple_select':

                                $child->options = array_map(
                                    'trim',
                                    $childData['options'] ?? []
                                );

                                $child->correct_answers =
                                    $childData['correct_options'] ?? [];

                                break;


                        /*
                        |--------------------------------------------------------------------------
                        | One Word
                        |--------------------------------------------------------------------------
                        */

                        case 'one_word':

                            $child->options = null;

                            $child->correct_answers = [
                                trim($childData['answer'])
                            ];

                            break;

                    }


                    $child->save();

                    $submittedChildIds[] = $child->id;

                }


                /*
                |--------------------------------------------------------------------------
                | Delete Removed Child Questions
                |--------------------------------------------------------------------------
                */

                Question::where('parent_question_id', $scenario->id)
                    ->whereNotIn('id', $submittedChildIds)
                    ->delete();

            });

            return redirect()
                ->route('questions.index')
                ->with('success', 'Scenario updated successfully.');
        }


            public function destroy($id)
            {
                $question = Question::findOrFail($id);

                if ($question->question_type === 'paragraph') {

                    $question->children()->delete();

                }

                $question->delete();

                return redirect()
                    ->route('questions.index')
                    ->with('success', 'Question deleted successfully!');
            }

public function preview($id)
{
    $question = Question::with([
        'paper',
        'topic',
        'subTopic',
        'children'
    ])->findOrFail($id);

    return view('admin.mock_tests.questionsmodal', compact('question'));
}


}
