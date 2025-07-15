<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Paper;
use App\Models\Topic;
use App\Models\SubTopic;

class QuestionController extends Controller
{
    public function index()
    {
        return view('questions.index', [
            'questions' => Question::with(['paper', 'topic', 'subTopic'])->latest()->get(),
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
        $request->validate([
            'paper_id' => 'required|exists:papers,id',
            'topic_id' => 'required|exists:topics,id',
            'sub_topic_id' => 'nullable|exists:sub_topics,id',
            'question_type' => 'required|in:mcq,multiple_select,one_word,table_mcq,drag_and_drop,dropdown',
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

                $dragData = [
                    'column_a_label' => $request->input('column_a_label', 'Column A'),
                    'column_b_label' => $request->input('column_b_label', 'Column B'),
                    'column_a' => array_values(array_filter($request->input('column_a', []))),
                    'column_b' => array_values(array_filter($request->input('column_b', []))),
                ];

                $question->options = $dragData;

                // ✅ Correct format: [A_index => B_index]
                $matches = [];
                $from = $request->input('matching_from', []);
                $to = $request->input('matching_to', []);
                for ($i = 0; $i < count($from); $i++) {
                    if (isset($from[$i], $to[$i]) && is_numeric($from[$i]) && is_numeric($to[$i])) {
                        $matches[(int)$from[$i]] = (int)$to[$i];
                    }
                }

                $question->correct_answers = json_encode($matches); // ✅ Store as JSON map
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
                    $options = array_map('trim', explode(',', $rawOptions));
                }

                $blanks[] = [
                    'label' => $labels[$i],
                    'options' => $options,
                ];
            }

            $question->options = $blanks;
            $question->correct_answers = array_slice($answers, 0, $blankCount);
            break;


        }

        $question->save();

        return redirect()->route('questions.index')->with('success', 'Question created successfully.');
    }

        public function edit(Question $question)
        {
            // Decode relevant fields depending on question type
            switch ($question->question_type) {
                case 'mcq':
                case 'multiple_select':
                    $question->options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                    $question->correct_answers = is_array($question->correct_answers) ? $question->correct_answers : json_decode($question->correct_answers, true);
                    break;

                case 'one_word':
                    $question->correct_answers = is_array($question->correct_answers) ? $question->correct_answers : json_decode($question->correct_answers, true);
                    break;

                case 'table_mcq':
                    $question->options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                    $question->correct_answers = is_array($question->correct_answers) ? $question->correct_answers : json_decode($question->correct_answers, true);
                    break;

                case 'drag_and_drop':
                    $options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                    $question->column_a_label = $options['column_a_label'] ?? 'Column A';
                    $question->column_b_label = $options['column_b_label'] ?? 'Column B';
                    $question->column_a = $options['column_a'] ?? [];
                    $question->column_b = $options['column_b'] ?? [];

                    $correct = is_array($question->correct_answers) ? $question->correct_answers : json_decode($question->correct_answers, true) ?? [];

                    $question->matching_from = array_keys($correct);
                    $question->matching_to = array_values($correct);
                    break;

            case 'dropdown':
                        $decodedOptions = is_array($question->options) ? $question->options : json_decode($question->options ?? '[]', true);
                        $decodedAnswers = is_array($question->correct_answers) ? $question->correct_answers : json_decode($question->correct_answers ?? '[]', true);

                        $question->options = $decodedOptions;
                        $question->correct_answers = $decodedAnswers;

                        // These are used by Blade
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

            $request->validate([
                'paper_id' => 'required|exists:papers,id',
                'topic_id' => 'required|exists:topics,id',
                'sub_topic_id' => 'nullable|exists:sub_topics,id',
                'question_type' => 'required|in:mcq,multiple_select,one_word,table_mcq,drag_and_drop,dropdown',
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

                    // 1. Validate question text
                    if (trim(strip_tags($request->question_text)) === '') {
                        return back()->withInput()->withErrors([
                            'question_text' => 'Question text must not be empty.'
                        ]);
                    }

                    // 2. Validate columns
                    $columnA = array_values(array_filter($request->input('column_a', [])));
                    $columnB = array_values(array_filter($request->input('column_b', [])));

                    if (empty($columnA)) {
                        return back()->withInput()->withErrors([
                            'column_a' => 'Please provide at least one item in Column A.'
                        ]);
                    }

                    if (empty($columnB)) {
                        return back()->withInput()->withErrors([
                            'column_b' => 'Please provide at least one item in Column B.'
                        ]);
                    }

                    // 3. Validate matching pairs
                    $from = $request->input('matching_from', []);
                    $to = $request->input('matching_to', []);
                    $matches = [];

                    foreach ($from as $i => $fromIndex) {
                        $toIndex = $to[$i] ?? null;

                        if ($fromIndex === '' || $toIndex === '' || !is_numeric($fromIndex) || !is_numeric($toIndex)) {
                            return back()->withInput()->withErrors([
                                "matching.$i" => "Invalid match in row " . ($i + 1) . "."
                            ]);
                        }

                        if (!isset($columnA[$fromIndex])) {
                            return back()->withInput()->withErrors([
                                "matching.$i" => "Invalid Column A index in match row " . ($i + 1) . "."
                            ]);
                        }

                        if (!isset($columnB[$toIndex])) {
                            return back()->withInput()->withErrors([
                                "matching.$i" => "Invalid Column B index in match row " . ($i + 1) . "."
                            ]);
                        }

                        $matches[(int)$fromIndex] = (int)$toIndex;
                    }

                    if (empty($matches)) {
                        return back()->withInput()->withErrors([
                            'matching' => 'At least one valid match is required.'
                        ]);
                    }

                    // Save data
                    $question->options = [
                        'column_a_label' => $request->input('column_a_label', 'Column A'),
                        'column_b_label' => $request->input('column_b_label', 'Column B'),
                        'column_a' => $columnA,
                        'column_b' => $columnB,
                    ];

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
                        $options = array_map('trim', explode(',', $rawOptions));
                    }

                    $blanks[] = [
                        'label' => $labels[$i],
                        'options' => $options,
                    ];
                }

                $question->options = $blanks;
                $question->correct_answers = array_slice($answers, 0, $blankCount);
                break;
            }
            $question->save();

            return redirect()->route('questions.index')->with('success', 'Question updated successfully!');
        }


    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('questions.index')->with('success', 'Question deleted successfully!');
    }

public function preview($id)
{
    $question = Question::with(['topic', 'subtopic', 'paper'])->findOrFail($id);
    return view('admin.mock_tests.questionsmodal', compact('question'));
}


}
