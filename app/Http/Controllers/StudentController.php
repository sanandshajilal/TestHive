<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institute;
use App\Models\Batch;
use App\Models\MockTest;
use App\Models\Question;
use App\Models\StudentTestAttempt;
use App\Models\StudentAnswer;

class StudentController extends Controller
{
    public function showLoginForm()
    {
        $institutes = Institute::all();
        $batches = Batch::all();

        return view('student.landing', compact('institutes', 'batches'));
    }

    public function validateAccess(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'institute_id' => 'required|exists:institutes,id',
            'batch_id' => 'required|exists:batches,id',
            'access_code' => 'required|string',
            'email' => 'required|email',
        ]);

        $now = \Carbon\Carbon::now('Asia/Kolkata');

        $mockTest = MockTest::where('access_code', $request->access_code)
            ->where('start_time', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_time')->orWhere('end_time', '>=', $now);
            })
            ->first();

        if (!$mockTest) {
            return back()
                ->withErrors(['access_code' => 'Invalid or inactive access code.'])
                ->withInput();
        }

        $studentEmail = $request->email;

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

        $newAttempt = StudentTestAttempt::create([
            'student_name' => $request->name,
            'institute_id' => $request->institute_id,
            'batch_id' => $request->batch_id,
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

        $mockTest = MockTest::with('questions')->findOrFail($mock_test_id);
        $questions = $mockTest->questions;

        if ($questionNumber < 1 || $questionNumber > $questions->count()) {
            return redirect()->route('student.test', [$mock_test_id, 1]);
        }

        $question = $questions[$questionNumber - 1];
        $totalQuestions = $questions->count();

        $savedAnswer = StudentAnswer::where('attempt_id', $attemptId)
            ->where('question_id', $question->id)
            ->first();

        $selectedOption = null;

        if ($savedAnswer) {
            if (in_array($question->question_type, ['multiple_select', 'table_mcq'])) {
                $selectedOption = json_decode($savedAnswer->selected_option, true);
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
            ->where('question_id', $question->id)
            ->value('is_flagged') ?? false;

            // Handle dynamic labels for table_mcq
               $statements = [];
                    $labels = [];

                    if ($question->question_type === 'table_mcq') {
                        $statements = is_array($question->options) ? $question->options : json_decode($question->options, true) ?? [];
                        $labels = explode(',', $question->table_mcq_labels ?? 'Debit,Credit');

                        // Trim labels just in case
                        $labels = array_map('trim', $labels);
                    }



        // ✅ 2. Return view with no-cache headers
        return response()
            ->view('student.test', compact(
                'mockTest',
                'question',
                'questionNumber',
                'totalQuestions',
                'selectedOption',
                'attempt',
                'remainingSeconds',
                'isFlagged',
                'statements',
                'labels'
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
        return response()->json(['error' => 'Session expired'], 401);
    }

    $mockTest = MockTest::with('questions')->findOrFail($mockTestId);
    $questions = $mockTest->questions;

    $studentAnswers = StudentAnswer::where('attempt_id', $attemptId)->get()->keyBy('question_id');

    $answered = 0;
    $unanswered = 0;
    $answeredFlagged = 0;
    $unansweredFlagged = 0;

    foreach ($questions as $question) {
        $answer = $studentAnswers->get($question->id);
        $userAns = $answer ? $answer->selected_option : null;

        $userAnsDecoded = $userAns ? json_decode($userAns, true) : null;

        $isAnswered = $answer && (!is_null($userAns) && (!is_array($userAnsDecoded) || !empty($userAnsDecoded)));
        $isFlagged = $answer && $answer->is_flagged;

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
            $userAnswerLower = array_map('strtolower', $userAnswer);
            $correctAnswerLower = array_map('strtolower', $correctAnswers);
            return $userAnswerLower == $correctAnswerLower;

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

    public function getQuestionStatuses($mockTestId)
        {
            $student = session('student_info');
            $attemptId = session('attempt_id');

            if (!$student || !$attemptId) {
                return response()->json(['error' => 'Session expired'], 401);
            }

            $mockTest = MockTest::with('questions')->findOrFail($mockTestId);
            $questions = $mockTest->questions;

            $studentAnswers = StudentAnswer::where('attempt_id', $attemptId)->get()->keyBy('question_id');

            $statuses = $questions->map(function ($question, $index) use ($studentAnswers) {
                $answer = $studentAnswers->get($question->id);
                $userAns = $answer ? $answer->selected_option : null;

                $userAnsDecoded = $userAns ? json_decode($userAns, true) : null;

                $isAnswered = $answer && (!is_null($userAns) && (!is_array($userAnsDecoded) || !empty($userAnsDecoded)));

                return [
                    'index' => $index + 1,
                    'is_answered' => $isAnswered,
                    'is_flagged' => $answer && $answer->is_flagged,
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
        return redirect()->route('student.index')->withErrors(['error' => 'Session expired. Please login again.']);
    }

    $mockTest = MockTest::with('questions')->findOrFail($mock_test_id);
    $questions = $mockTest->questions;

    $correctCount = 0;
    $wrongCount = 0;
    $notAttempted = 0;

    foreach ($questions as $question) {
        $studentAnswer = StudentAnswer::where('attempt_id', $attemptId)
            ->where('question_id', $question->id)
            ->first();

        $userAnswer = $studentAnswer ? $studentAnswer->selected_option : null;

        $isAttempted = false;

        if (in_array($question->question_type, ['multiple_select', 'table_mcq', 'dropdown', 'drag_and_drop'])) {
            $decoded = is_array($userAnswer) ? $userAnswer : json_decode($userAnswer, true);
            $isAttempted = is_array($decoded) && count($decoded) > 0;
            $userAnswer = $decoded;
        } elseif ($question->question_type === 'one_word') {
            $isAttempted = $userAnswer !== null && trim($userAnswer) !== '';
        } else {
            $isAttempted = $userAnswer !== null;
        }


        if (!$isAttempted) {
            $notAttempted++;

            // Ensure there's a student_answer record for consistency
            if (!$studentAnswer) {
                StudentAnswer::create([
                    'attempt_id' => $attemptId,
                    'question_id' => $question->id,
                    'selected_option' => null,
                    'is_correct' => false,
                    'marks_awarded' => 0,
                ]);
            }

            continue;
        }

        $isCorrect = $this->checkAnswer($question, $userAnswer);
        $marks = $isCorrect ? $question->marks : 0;

        if ($studentAnswer) {
            $studentAnswer->update([
                'is_correct' => $isCorrect,
                'marks_awarded' => $marks,
            ]);
        }

        if ($isCorrect) {
            $correctCount++;
        } else {
            $wrongCount++;
        }
    }

    $totalMarks = StudentAnswer::where('attempt_id', $attemptId)->sum('marks_awarded');

    StudentTestAttempt::find($attemptId)->update([
        'end_time' => now(),
        'correct_count' => $correctCount,
        'wrong_count' => $wrongCount,
        'not_attempted' => $notAttempted,
        'total_marks' => $totalMarks,
        'status' => 'completed',
    ]);

    return redirect()->route('student.results', $attemptId);
}



public function results($attemptId)
{
    $student = session('student_info');
    $sessionAttemptId = session('attempt_id');

    // 1. Redirect if session expired
    if (!$student || !$sessionAttemptId) {
        return redirect()->route('student.index')->withErrors(['error' => 'Session expired. Please login again.']);
    }

    // 2. Ensure the student can only access their own attempt
    if ($attemptId != $sessionAttemptId) {
        return redirect()->route('student.results', $sessionAttemptId)->with('warning', 'You are not authorized to view that result.');
    }

    $attempt = StudentTestAttempt::with('mockTest.questions')->findOrFail($attemptId);
    $mockTest = $attempt->mockTest;
    $questions = $mockTest->questions;

    $studentAnswers = StudentAnswer::with('question')
        ->where('attempt_id', $attemptId)
        ->get()
        ->keyBy('question_id');

    $correctCount = 0;
    $wrongCount = 0;
    $unattemptedCount = 0;

    foreach ($questions as $question) {
        $answer = $studentAnswers->get($question->id);

        if (!$answer || is_null($answer->selected_option) || (is_array(json_decode($answer->selected_option, true)) && empty(json_decode($answer->selected_option, true)))) {
            $unattemptedCount++;
        } elseif ($answer->is_correct) {
            $correctCount++;
        } else {
            $wrongCount++;
        }
    }

    $totalQuestions = $questions->count();
    $totalMarks = $studentAnswers->sum('marks_awarded');

    $studentInfo = [
        'name' => $attempt->student_name,
        'email' => $attempt->email,
        'institute_id' => $attempt->institute_id,
        'batch_id' => $attempt->batch_id,
    ];

    return view('student.results', [
        'student' => $studentInfo,
        'attempt' => $attempt,
        'answers' => $studentAnswers->values(),
        'correctCount' => $correctCount,
        'wrongCount' => $wrongCount,
        'unattemptedCount' => $unattemptedCount,
        'questions' => $questions,
        'totalQuestions' => $totalQuestions,
        'totalMarks' => $totalMarks,
    ]);
}

    
}
