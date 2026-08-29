<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institute;
use App\Models\Batch;
use App\Models\MockTest;
use App\Models\Question;
use App\Models\StudentTestAttempt;
use App\Models\StudentAnswer;
use App\Models\Student;

class StudentController extends Controller
{
            public function showLoginForm()
            {
                return view('student.landing');
            }

            public function validateAccess(Request $request)
        {
          $request->validate([
                'access_code' => 'required|string',
                'email' => 'required|email',
            ]);

            $studentEmail = strtolower(trim($request->email));

            $student = Student::with('batch')
                ->where('email', $studentEmail)
                ->where('is_active', true)
                ->first();

            if (!$student) {
                return back()
                    ->withErrors([
                        'email' => 'Email address is not registered.'
                    ])
                    ->withInput();
            }

            $batchId = $student->batch_id;
            $instituteId = $student->batch->institute_id;

            $now = \Carbon\Carbon::now('Asia/Kolkata');

            // Verify access code
            $mockTest = MockTest::where('access_code', $request->access_code)
                ->where('start_time', '<=', $now)
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_time')
                    ->orWhere('end_time', '>=', $now);
                })
                ->first();

            if (!$mockTest) {
                return back()
                    ->withErrors([
                        'access_code' => 'Invalid or inactive access code.'
                    ])
                    ->withInput();
            }

            // Verify test is assigned to selected batch
            $batchAllowed = $mockTest->batches()
                ->where('batches.id', $batchId)
                ->exists();

            if (!$batchAllowed) {
                return back()
                    ->withErrors([
                        'access_code' => 'This test is not available for the selected batch.'
                    ])
                    ->withInput();
            }

            // Check if attempt already exists
            $existingAttempt = StudentTestAttempt::where('mock_test_id', $mockTest->id)
                ->where('email', $studentEmail)
                ->first();

            if ($existingAttempt) {

                session([
                    'student_info' => [
                        'name' => $existingAttempt->student_name,
                        'institute_id' => $existingAttempt->institute_id,
                        'batch_id' => $existingAttempt->batch_id,
                        'email' => $existingAttempt->email,
                    ],
                    'mock_test_id' => $mockTest->id,
                    'attempt_id' => $existingAttempt->id,
                ]);

                return redirect()->route('student.instructions');
            }

            // Create new attempt
            $newAttempt = StudentTestAttempt::create([
                'student_name' => $student->name,
                'institute_id' => $instituteId,
                'batch_id' => $batchId,
                'mock_test_id' => $mockTest->id,
                'email' => $studentEmail,
                'access_code' => $request->access_code,
            ]);

            session([
                'student_info' => [
                    'name' => $newAttempt->student_name,
                    'institute_id' => $newAttempt->institute_id,
                    'batch_id' => $newAttempt->batch_id,
                    'email' => $newAttempt->email,
                ],
                'mock_test_id' => $mockTest->id,
                'attempt_id' => $newAttempt->id,
            ]);

            return redirect()->route('student.instructions');
        }

    public function instructions()
    {
        $student = session('student_info');
        $mockTestId = session('mock_test_id');
          $attemptId = session('attempt_id');

        if (!$student || !$mockTestId) {
            return redirect()->route('student.index')->withErrors(['error' => 'Session expired. Please login again.']);
        }
        
         // ✅ One-liner status check:
            if (StudentTestAttempt::where('id', $attemptId)->where('status', 'completed')->exists()) {
                return redirect()
                    ->route('student.results', $attemptId)
                    ->with('info', 'You have already submitted this test. Here are your results.');
            }
        $mockTest = MockTest::with('paper')->findOrFail($mockTestId);
        $mockTest->questions_count = $mockTest->questions()->count();
        $mockTest->duration = $mockTest->duration_minutes;
        $mockTest->pass_mark = 50;

        return view('student.instructions', compact('student', 'mockTest'));
    }

  public function startTest()
        {
            $student = session('student_info');
            $mockTestId = session('mock_test_id');
            $attemptId = session('attempt_id');

            if (!$student || !$mockTestId || !$attemptId) {
                return redirect()->route('student.index')->withErrors(['error' => 'Session expired. Please login again.']);
            }

            $attempt = StudentTestAttempt::find($attemptId);

            // ✅ START TEST: Set start_time if not set
            if (!$attempt->start_time) {
                $attempt->update(['start_time' => now()]);
            }

            // ✅ Redirect to last visited question if available
            $lastQ = $attempt->last_question_number ?? 1;

            return redirect()->route('student.test', ['mock_test_id' => $mockTestId, 'questionNumber' => $lastQ]);
        }




    public function showQuestion($mock_test_id, $questionNumber = 1)
    {
        $student = session('student_info');
        $attemptId = session('attempt_id');

        if (!$student || !$attemptId) {
            return redirect()
                ->route('student.index')
                ->withErrors(['error' => 'Session expired. Please login again.']);
        }


        // ✅ 1. Check status
        $attempt = StudentTestAttempt::find($attemptId);
        if ($attempt && $attempt->status === 'completed') {
        return redirect()
                ->route('student.results', $attemptId) // ✅ Redirect using attemptId
                ->with('info', 'Test has already been submitted. You cannot revisit questions.');

        }

        $mockTest = MockTest::findOrFail($mock_test_id);

        /*
        |--------------------------------------------------------------------------
        | Test Items
        |--------------------------------------------------------------------------
        | One item can be:
        | 1. Standalone Question
        | 2. Scenario (Paragraph) containing child questions
        |--------------------------------------------------------------------------
        */

        $items = $this->getTestItems($mockTest);

        /*
        |--------------------------------------------------------------------------
        | Navigation Items
        |--------------------------------------------------------------------------
        |
        | These are the actual screens/pages of the test.
        | A Scenario occupies one screen.
        |
        */

        $totalItems = $items->count();
        $isLastQuestion = ($questionNumber == $totalItems);

        if ($questionNumber < 1 || $questionNumber > $totalItems) {
            return redirect()->route(
                'student.test',
                [$mock_test_id, 1]
            );
        }

        $item = $items[$questionNumber - 1];



        /*
        |--------------------------------------------------------------------------
        | Question numbers represented by this screen
        |--------------------------------------------------------------------------
        */

        $displayQuestionStart = 1;

        for ($i = 0; $i < $questionNumber - 1; $i++) {

            $previousItem = $items[$i];

            if ($previousItem->question_type === 'paragraph') {

                $displayQuestionStart += $previousItem->children->count();

            } else {

                $displayQuestionStart++;

            }
        }

        if ($item->question_type === 'paragraph') {

            $displayQuestionEnd =
                $displayQuestionStart + $item->children->count() - 1;

        } else {

            $displayQuestionEnd = $displayQuestionStart;
        }

        /*
        |--------------------------------------------------------------------------
        | Actual Scorable Questions
        |--------------------------------------------------------------------------
        |
        | Standalone questions count individually.
        | Scenario parent does not count.
        | Scenario children count individually.
        |
        */

        $scorableQuestions = collect();

        foreach ($items as $testItem) {

            if ($testItem->question_type === 'paragraph') {

                foreach ($testItem->children as $child) {
                    $scorableQuestions->push($child);
                }

            } else {

                $scorableQuestions->push($testItem);

            }
        }

        $totalQuestions = $scorableQuestions->count();

        $savedAnswer = StudentAnswer::where('attempt_id', $attemptId)
            ->where('question_id', $item->id)
            ->first();

       $selectedOption = null;

            if ($savedAnswer) {

                if (
                    in_array(
                        $item->question_type,
                        ['multiple_select', 'table_mcq', 'drag_and_drop', 'dropdown']
                    )
                ) {
                    $selectedOption = json_decode(
                        $savedAnswer->selected_option,
                        true
                    );
                } else {
                    $selectedOption = $savedAnswer->selected_option;
                }

            }

            // ✅ ✅ ✅  Save current question number
                if ($attempt) {
                    $attempt->last_question_number = $questionNumber;
                    $attempt->save();
                }

        // ✅ 3. Calculate actual remaining seconds
        $remainingSeconds = $attempt->remaining_seconds ?? ($mockTest->duration_minutes * 60);
        
            $isFlagged = \App\Models\StudentAnswer::where('attempt_id', $attemptId)
                ->where('question_id', $item->id)
                ->value('is_flagged') ?? false;

            // Handle dynamic labels for table_mcq
               $statements = [];
                    $labels = [];

                    if ($item->question_type === 'table_mcq') {

                        $statements = is_array($item->options)
                            ? $item->options
                            : json_decode($item->options, true) ?? [];

                        $labels = explode(',', $item->table_mcq_labels ?? 'Debit,Credit');

                        $labels = array_map('trim', $labels);
                    }



        // ✅ 2. Return view with no-cache headers
        return response()
            ->view('student.test', compact(
                'mockTest',
                'item',
                'questionNumber',
                'totalItems',
                'totalQuestions',
                'displayQuestionStart',
                'displayQuestionEnd',
                'selectedOption',
                'attempt',
                'remainingSeconds',
                'isFlagged',
                'statements',
                'labels',
                'isLastQuestion'
            ))
            ->header('Cache-Control','no-cache, no-store, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','0');
    }

protected function extractAnswerFromRequest(Request $request)
{
    $question = Question::find($request->input('question_id'));

    if (!$question) {
        return null;
    }

    switch ($question->question_type) {
        case 'mcq':
        case 'one_word':
            return $request->input('answer');

        case 'multiple_select':
        case 'table_mcq':
            return (array) $request->input('answer', []);

        case 'drag_and_drop':
            // Input is: [bIndex => aIndex]
            $raw = $request->input('student_answer'); 
            $flipped = [];

            if (is_array($raw)) {
                foreach ($raw as $bIndex => $aIndex) {
                    if ($aIndex !== null && $aIndex !== '') {
                        $flipped[(int)$aIndex] = (int)$bIndex;
                    }
                }
            }

            return $flipped; // Returns: [aIndex => bIndex]

                case 'dropdown':
                    // dropdown sends: dropdown_answers[label] = selected_value
                    $dropdownAnswers = $request->input('dropdown_answers');
                    return is_array($dropdownAnswers) ? $dropdownAnswers : [];

                default:
                    return null;
            }
        }


    public function getAnswerCounts($mockTestId)
{
    $student = session('student_info');
    $attemptId = session('attempt_id');

    if (!$student || !$attemptId) {
        return response()->json([
            'error' => 'Session expired'
        ], 401);
    }

    $mockTest = MockTest::findOrFail($mockTestId);

    /*
    |--------------------------------------------------------------------------
    | Get Student-Facing Test Items
    |--------------------------------------------------------------------------
    |
    | These are the navigation screens.
    | A Scenario is one screen containing multiple child questions.
    |
    */

    $questions = $this->getTestItems($mockTest);

    /*
    |--------------------------------------------------------------------------
    | Build Scorable Questions
    |--------------------------------------------------------------------------
    |
    | For the review modal, each actual question must be counted
    | separately.
    |
    | Example:
    |
    | Q1
    | Q2
    | Q3
    | Q4
    | Scenario
    |   Q1
    |   Q2
    |   Q3
    |
    | Total scorable questions = 7
    |
    */

    $scorableQuestions = collect();

    foreach ($questions as $question) {

        if ($question->question_type === 'paragraph') {

            foreach ($question->children as $child) {
                $scorableQuestions->push($child);
            }

        } else {

            $scorableQuestions->push($question);

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Student Answers
    |--------------------------------------------------------------------------
    */

    $studentAnswers = StudentAnswer::where('attempt_id', $attemptId)
        ->get()
        ->keyBy('question_id');

    $answered = 0;
    $unanswered = 0;
    $answeredFlagged = 0;
    $unansweredFlagged = 0;

    /*
    |--------------------------------------------------------------------------
    | Count Individual Scorable Questions
    |--------------------------------------------------------------------------
    */

    foreach ($scorableQuestions as $question) {

        $answer = $studentAnswers->get($question->id);

        /*
        |--------------------------------------------------------------------------
        | Determine whether this question is answered
        |--------------------------------------------------------------------------
        */

        $isAnswered = $this->isAnswerRecordAnswered($answer);

        /*
        |--------------------------------------------------------------------------
        | Flag Status
        |--------------------------------------------------------------------------
        */

        $isFlagged = $answer && $answer->is_flagged;

        /*
        |--------------------------------------------------------------------------
        | Answered / Unanswered
        |--------------------------------------------------------------------------
        */

        if ($isAnswered) {

            $answered++;

            if ($isFlagged) {
                $answeredFlagged++;
            }

        } else {

            $unanswered++;

            if ($isFlagged) {
                $unansweredFlagged++;
            }
        }
    }

    return response()->json([
        'answered' => $answered,
        'not_answered' => $unanswered,
        'answered_flagged' => $answeredFlagged,
        'unanswered_flagged' => $unansweredFlagged,

        // Useful for the frontend if we want to display total later
        'total' => $scorableQuestions->count(),
    ]);
}
    

    public function saveAnswer(Request $request, $mock_test_id)
    {
        \Log::debug('saveAnswer called', [
            'student' => session('student_info'),
            'attempt_id' => session('attempt_id'),
            'question_id' => $request->input('question_id'),
            'user_answer' => $request->all(),
        ]);

        $student = session('student_info');
        $attemptId = session('attempt_id');

        if (!$student || !$attemptId) {
            return response()->json(['error' => 'Session expired. Please login again.'], 401);
        }

            // ✅ New Logic for Periodic Time Save
            if ($request->has('save_only') && $request->has('remaining_seconds')) {
                StudentTestAttempt::where('id', $attemptId)->update([
                    'remaining_seconds' => $request->remaining_seconds,
                ]);

                return response()->json(['success' => true]);
            }

        $questionId = $request->input('question_id');
        $userAnswer = $this->extractAnswerFromRequest($request);
        $question = Question::find($questionId);

        if (!$question) {
            return response()->json(['error' => 'Invalid question.'], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Scenario Question
        |--------------------------------------------------------------------------
        */

        if ($question->question_type === 'paragraph') {

            $scenarioAnswers = $request->input('scenario_answers', []);

            foreach ($scenarioAnswers as $childQuestionId => $answer) {

                $childQuestion = Question::find($childQuestionId);

                if (!$childQuestion) {
                    continue;
                }

                $correct = $this->checkAnswer($childQuestion, $answer);

                StudentAnswer::updateOrCreate(
                    [
                        'attempt_id' => $attemptId,
                        'question_id' => $childQuestion->id,
                    ],
                    [
                        'selected_option' => is_array($answer)
                            ? json_encode($answer)
                            : $answer,

                        'is_correct' => $correct,

                        'marks_awarded' => $correct
                            ? $childQuestion->marks
                            : 0,
                    ]
                );

            }

            if ($request->has('remaining_seconds')) {

                StudentTestAttempt::where('id', $attemptId)->update([
                    'remaining_seconds' => $request->remaining_seconds,
                ]);

            }

            return response()->json([
                'success' => true
            ]);
        }

        $correct = $this->checkAnswer($question, $userAnswer);
        $marks = $correct ? $question->marks : 0;

        StudentAnswer::updateOrCreate(
            [
                'attempt_id' => $attemptId,
                'question_id' => $question->id,
            ],
            [
                'selected_option' => is_array($userAnswer) ? json_encode($userAnswer) : $userAnswer,
                'is_correct' => $correct,
                'marks_awarded' => $marks,
            ]
        );

        if ($request->has('remaining_seconds')) {
        $student = session('student_info');
        $attemptId = session('attempt_id');
        if ($student && $attemptId) {
            StudentTestAttempt::where('id', $attemptId)->update([
                'remaining_seconds' => $request->remaining_seconds,
            ]);
        }
    }

        return response()->json(['success' => true]);
    }

  private function checkAnswer($question, $userAnswer)
{
    if (!$userAnswer) {
        return false;
    }

    $correctAnswers = $question->correct_answers;

    if (!is_array($correctAnswers)) {
        $correctAnswers = json_decode($correctAnswers, true) ?? [$correctAnswers];
    }

    switch ($question->question_type) {
        case 'table_mcq':

        if (!is_array($userAnswer) || !is_array($correctAnswers)) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize both arrays
        |--------------------------------------------------------------------------
        */

        $normalize = function ($value) {
            return strtolower(trim((string) $value));
        };

        $userAnswer = array_map($normalize, $userAnswer);
        $correctAnswers = array_map($normalize, $correctAnswers);

        /*
        |--------------------------------------------------------------------------
        | Compare by row
        |--------------------------------------------------------------------------
        |
        | Table MCQ answers correspond to specific rows.
        | Therefore we compare each row individually rather than relying
        | on array order.
        |
        */

        foreach ($correctAnswers as $index => $correctAnswer) {

            $studentAnswer = $userAnswer[$index] ?? null;

            if ($studentAnswer === null) {
                return false;
            }

            if ($studentAnswer !== $correctAnswer) {
                return false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure student has not supplied extra answers
        |--------------------------------------------------------------------------
        */

        if (count($userAnswer) !== count($correctAnswers)) {
            return false;
        }

        return true;

        case 'one_word':
            $normalize = function($string) {
                return preg_replace("/[\"'`\x{200B}-\x{200D}\s]+/u", '', strtolower(trim($string)));
            };
            if (!is_array($correctAnswers)) {
                $correctAnswers = [$correctAnswers];
            }
            $user = $normalize($userAnswer);
            $correctAnswers = array_map($normalize, $correctAnswers);
            return in_array($user, $correctAnswers);

        case 'multiple_select':
            if (is_string($userAnswer)) {
                $userAnswer = json_decode($userAnswer, true) ?? [$userAnswer];
            }
            if (!is_array($userAnswer) || !is_array($correctAnswers)) {
                return false;
            }
            sort($userAnswer);
            sort($correctAnswers);
            return $userAnswer === $correctAnswers;

        case 'mcq':
            if (!is_array($correctAnswers)) {
                $correctAnswers = [$correctAnswers];
            }
            return in_array($userAnswer, $correctAnswers);

        case 'dropdown':
            if (!is_array($userAnswer) || !is_array($correctAnswers)) {
                return false;
            }
            $normalizedUser = array_map('strtolower', array_map('trim', $userAnswer));
            $normalizedCorrect = array_map('strtolower', array_map('trim', $correctAnswers));
            return $normalizedUser === $normalizedCorrect;

        case 'drag_and_drop':
            if (!is_array($userAnswer) || !is_array($correctAnswers)) {
                return false;
            }
            ksort($userAnswer);
            ksort($correctAnswers);
            return $userAnswer === $correctAnswers;

        default:
            return false;
    }
}

/**
 * Determine whether a test item has been answered.
 *
 * Standalone Question:
 *     Answered if a StudentAnswer exists with a non-empty answer.
 *
 * Scenario Question:
 *     Answered if ANY child question has been answered.
 */
private function isItemAnswered($item, $studentAnswers)
{
    // Standalone Question
    if ($item->question_type !== 'paragraph') {

        return $this->isAnswerRecordAnswered(
            $studentAnswers->get($item->id)
        );

    }

    // Scenario Question
    foreach ($item->children as $child) {

        if ($this->isAnswerRecordAnswered(
            $studentAnswers->get($child->id)
        )) {

            return true;

        }

    }

    return false;
}


    /**
     * Check whether a StudentAnswer actually contains an answer.
     */
    private function isAnswerRecordAnswered($answer)
    {
        if (!$answer) {
            return false;
        }

        $value = $answer->selected_option;

        if (is_null($value)) {
            return false;
        }

        // JSON arrays
        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {

            return count($decoded) > 0;

        }

        return trim((string)$value) !== '';
    }

   public function getQuestionStatuses($mockTestId)
    {
        $student = session('student_info');
        $attemptId = session('attempt_id');

        if (!$student || !$attemptId) {
            return response()->json([
                'error' => 'Session expired'
            ], 401);
        }

        $mockTest = MockTest::findOrFail($mockTestId);

        /*
        |--------------------------------------------------------------------------
        | Student-facing test items
        |--------------------------------------------------------------------------
        |
        | A Scenario is one navigation item.
        |
        */

        $questions = $this->getTestItems($mockTest);

        /*
        |--------------------------------------------------------------------------
        | Student Answers
        |--------------------------------------------------------------------------
        */

        $studentAnswers = StudentAnswer::where('attempt_id', $attemptId)
            ->get()
            ->keyBy('question_id');

        /*
        |--------------------------------------------------------------------------
        | Build Statuses
        |--------------------------------------------------------------------------
        */

        $statuses = $questions->map(function ($question, $index) use ($studentAnswers) {

            /*
            |--------------------------------------------------------------------------
            | Answered Status
            |--------------------------------------------------------------------------
            |
            | For a normal question, this checks that question.
            |
            | For a Scenario, this checks its child questions.
            |
            */

            $isAnswered = $this->isItemAnswered(
                $question,
                $studentAnswers
            );

            /*
            |--------------------------------------------------------------------------
            | Flag Status
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | A Scenario has ONE flag.
            | The flag is stored against the Scenario parent question ID.
            |
            | Therefore, do NOT check the child questions here.
            |
            */

            $answer = $studentAnswers->get($question->id);

            $isFlagged = $answer
                ? (bool) $answer->is_flagged
                : false;

            return [
                'index' => $index + 1,
                'is_answered' => $isAnswered,
                'is_flagged' => $isFlagged,
            ];
        });

        return response()->json($statuses);
    }

public function toggleFlag(Request $request, $mockTestId)
{
    $student = session('student_info');
    $attemptId = session('attempt_id');

    if (!$student || !$attemptId) {
        return response()->json(['error' => 'Session expired'], 401);
    }

    $questionId = $request->input('question_id');

    $studentAnswer = StudentAnswer::firstOrCreate(
        ['attempt_id' => $attemptId, 'question_id' => $questionId],
        ['selected_option' => null]
    );

    $studentAnswer->is_flagged = !$studentAnswer->is_flagged;
    $studentAnswer->save();

    return response()->json(['is_flagged' => $studentAnswer->is_flagged]);
}

    

public function submitTest($mock_test_id)
{
    $student = session('student_info');
    $attemptId = session('attempt_id');

    if (!$student || !$attemptId) {
        return redirect()
            ->route('student.index')
            ->withErrors([
                'error' => 'Session expired. Please login again.'
            ]);
    }

    $mockTest = MockTest::findOrFail($mock_test_id);

    /*
    |--------------------------------------------------------------------------
    | Student-facing test items
    |--------------------------------------------------------------------------
    |
    | Used for the grouped Scenario structure.
    | We don't use this collection directly for scoring.
    |
    */

    $questions = $this->getTestItems($mockTest);

    /*
    |--------------------------------------------------------------------------
    | Build Scorable Questions
    |--------------------------------------------------------------------------
    |
    | Standalone question = one scorable question
    |
    | Scenario/paragraph = container only
    | Scenario children = individual scorable questions
    |
    | Example:
    |
    | Q1
    | Scenario
    |   Q1
    |   Q2
    |   Q3
    |
    | Scorable questions:
    |
    | Q1
    | Scenario Q1
    | Scenario Q2
    | Scenario Q3
    |
    */

    $scorableQuestions = collect();

    foreach ($questions as $question) {

        if ($question->question_type === 'paragraph') {

            foreach ($question->children as $child) {

                $scorableQuestions->push($child);

            }

        } else {

            $scorableQuestions->push($question);

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Result
    |--------------------------------------------------------------------------
    */

    $correctCount = 0;
    $wrongCount = 0;
    $notAttempted = 0;

    foreach ($scorableQuestions as $question) {

        $studentAnswer = StudentAnswer::where('attempt_id', $attemptId)
            ->where('question_id', $question->id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Determine whether the question was actually attempted
        |--------------------------------------------------------------------------
        */

        $userAnswer = $studentAnswer
            ? $studentAnswer->selected_option
            : null;

        $isAttempted = false;

        /*
        |--------------------------------------------------------------------------
        | Array-based question types
        |--------------------------------------------------------------------------
        */

            if (in_array($question->question_type, [
                'multiple_select',
                'table_mcq',
                'dropdown',
                'drag_and_drop'
            ])) {

                if (is_array($userAnswer)) {

                    $userAnswer = $userAnswer;

                } else {

                    $userAnswer = json_decode($userAnswer, true) ?? [];

                }

                $isAttempted = is_array($userAnswer)
                    && count($userAnswer) > 0;
            }

        /*
        |--------------------------------------------------------------------------
        | One Word
        |--------------------------------------------------------------------------
        */

        elseif ($question->question_type === 'one_word') {

            $isAttempted =
                $userAnswer !== null
                && trim((string) $userAnswer) !== '';

        }

        /*
        |--------------------------------------------------------------------------
        | MCQ
        |--------------------------------------------------------------------------
        */

        else {

            $isAttempted =
                $userAnswer !== null
                && trim((string) $userAnswer) !== '';

        }

        /*
        |--------------------------------------------------------------------------
        | Not Attempted
        |--------------------------------------------------------------------------
        */

        if (!$isAttempted) {

            $notAttempted++;

            /*
            |--------------------------------------------------------------------------
            | Make sure an answer record exists
            |--------------------------------------------------------------------------
            |
            | This is useful for keeping the response sheet consistent.
            |
            */

            if (!$studentAnswer) {

                StudentAnswer::create([
                    'attempt_id' => $attemptId,
                    'question_id' => $question->id,
                    'selected_option' => null,
                    'is_correct' => false,
                    'marks_awarded' => 0,
                ]);

            } else {

                $studentAnswer->update([
                    'is_correct' => false,
                    'marks_awarded' => 0,
                ]);

            }

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Check Answer
        |--------------------------------------------------------------------------
        */

        if ($question->question_type === 'table_mcq') {

            \Log::debug('TABLE MCQ CHECK', [
                'question_id' => $question->id,
                'user_answer' => $userAnswer,
                'correct_answers' => $question->correct_answers,
                'is_array_user' => is_array($userAnswer),
                'is_array_correct' => is_array($question->correct_answers),
            ]);
        }

        $isCorrect = $this->checkAnswer(
            $question,
            $userAnswer
        );

        $marks = $isCorrect
            ? $question->marks
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Update Student Answer
        |--------------------------------------------------------------------------
        */

        if ($studentAnswer) {

            $studentAnswer->update([
                'is_correct' => $isCorrect,
                'marks_awarded' => $marks,
            ]);

        } else {

            /*
            |--------------------------------------------------------------------------
            | Safety fallback
            |--------------------------------------------------------------------------
            |
            | Normally saveAnswer() will already have created the record.
            | This handles the unlikely case where it doesn't exist.
            |
            */

            $studentAnswer = StudentAnswer::create([
                'attempt_id' => $attemptId,
                'question_id' => $question->id,
                'selected_option' => is_array($userAnswer)
                    ? json_encode($userAnswer)
                    : $userAnswer,
                'is_correct' => $isCorrect,
                'marks_awarded' => $marks,
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Count Result
        |--------------------------------------------------------------------------
        */

        if ($isCorrect) {

            $correctCount++;

        } else {

            $wrongCount++;

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Total Marks Awarded
    |--------------------------------------------------------------------------
    |
    | This is the student's actual score.
    |
    */

    $totalMarks = StudentAnswer::where('attempt_id', $attemptId)
        ->sum('marks_awarded');

    /*
    |--------------------------------------------------------------------------
    | Mark Attempt as Completed
    |--------------------------------------------------------------------------
    */

    StudentTestAttempt::findOrFail($attemptId)->update([
        'end_time' => now(),
        'correct_count' => $correctCount,
        'wrong_count' => $wrongCount,
        'not_attempted' => $notAttempted,
        'total_marks' => $totalMarks,
        'status' => 'completed',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Redirect to Results
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('student.results', $attemptId);
}



    /**
     * Get top-level test items (Standalone Questions + Scenario Questions)
     */
    private function getTestItems(MockTest $mockTest)
    {
        return $mockTest->questions()
            ->whereNull('parent_question_id')
            ->with([
                'children',
                'topic',
                'subTopic',
                'children.topic',
                'children.subTopic'
            ])
            ->get();
    }

public function results($attemptId)
{
    $student = session('student_info');
    $sessionAttemptId = session('attempt_id');

    // 1. Redirect if session expired
    if (!$student || !$sessionAttemptId) {
        return redirect()
            ->route('student.index')
            ->withErrors([
                'error' => 'Session expired. Please login again.'
            ]);
    }

    // 2. Ensure the student can only access their own attempt
    if ($attemptId != $sessionAttemptId) {
        return redirect()
            ->route('student.results', $sessionAttemptId)
            ->with(
                'warning',
                'You are not authorized to view that result.'
            );
    }

    // 3. Get attempt and mock test
    $attempt = StudentTestAttempt::findOrFail($attemptId);

    $mockTest = MockTest::findOrFail($attempt->mock_test_id);

    /*
    |--------------------------------------------------------------------------
    | Student-facing test items
    |--------------------------------------------------------------------------
    |
    | This keeps Scenario questions grouped as one item for display.
    |
    */

    $questions = $this->getTestItems($mockTest);

    /*
    |--------------------------------------------------------------------------
    | Scorable Questions
    |--------------------------------------------------------------------------
    |
    | A Scenario/paragraph itself is NOT a question for marking.
    | Its child questions are the actual scorable questions.
    |
    | Example:
    |
    | Q1          -> 1 question
    | Scenario
    |   Q1        -> 1 question
    |   Q2        -> 1 question
    |   Q3        -> 1 question
    |
    | Total = 4 questions
    |
    */

$scorableQuestions = collect();

foreach ($questions as $question) {

    if ($question->question_type === 'paragraph') {

        // Scenario parent is only a container.
        // Its child questions are the actual scorable questions.
        foreach ($question->children as $child) {
            $scorableQuestions->push($child);
        }

    } else {

        // Normal standalone question.
        $scorableQuestions->push($question);

    }
}

    /*
    |--------------------------------------------------------------------------
    | Student Answers
    |--------------------------------------------------------------------------
    */

    $studentAnswers = StudentAnswer::with('question')
        ->where('attempt_id', $attemptId)
        ->get()
        ->keyBy('question_id');

    /*
    |--------------------------------------------------------------------------
    | Calculate Result
    |--------------------------------------------------------------------------
    */

    $correctCount = 0;
    $wrongCount = 0;
    $unattemptedCount = 0;

    foreach ($scorableQuestions as $question) {

        $answer = $studentAnswers->get($question->id);

        /*
        |--------------------------------------------------------------------------
        | Not Attempted
        |--------------------------------------------------------------------------
        */

        if (!$this->isAnswerRecordAnswered($answer)) {

            $unattemptedCount++;

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Correct
        |--------------------------------------------------------------------------
        */

        if ($answer->is_correct) {

            $correctCount++;

        }

        /*
        |--------------------------------------------------------------------------
        | Wrong
        |--------------------------------------------------------------------------
        */

        else {

            $wrongCount++;

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Question & Marks Totals
    |--------------------------------------------------------------------------
    */

    // Actual student-visible/scorable questions
    $totalQuestions = $scorableQuestions->count();

    // Total possible marks for all scorable questions
    $totalMarks = $scorableQuestions->sum('marks');

    // Marks actually awarded to the student
    $marksAwarded = $studentAnswers->sum('marks_awarded');

    /*
    |--------------------------------------------------------------------------
    | Student Information
    |--------------------------------------------------------------------------
    */

    $studentInfo = [
        'name' => $attempt->student_name,
        'email' => $attempt->email,
        'institute_id' => $attempt->institute_id,
        'batch_id' => $attempt->batch_id,
    ];

    /*
    |--------------------------------------------------------------------------
    | Results View
    |--------------------------------------------------------------------------
    */

    return view('student.results', [
        'student' => $studentInfo,
        'attempt' => $attempt,

        // All saved answers
        'answers' => $studentAnswers->values(),

        // Result counts
        'correctCount' => $correctCount,
        'wrongCount' => $wrongCount,
        'unattemptedCount' => $unattemptedCount,

        // Student-facing grouped questions
        'questions' => $questions,

        // Scoring totals
        'totalQuestions' => $totalQuestions,
        'totalMarks' => $totalMarks,
        'marksAwarded' => $marksAwarded,
    ]);
}

    
}
