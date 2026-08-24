<?php

namespace App\Http\Controllers;

use App\Models\StudentTestAttempt;
use App\Models\StudentAnswer;
use App\Models\MockTest;

class ResponseController extends Controller
{
    /**
     * Display a student's response sheet.
     */
    public function show($id)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Attempt
        |--------------------------------------------------------------------------
        */

        $attempt = StudentTestAttempt::with([
            'answers',
            'mockTest',
            'institute',
            'batch',
        ])->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Mock Test
        |--------------------------------------------------------------------------
        */

        $mockTest = $attempt->mockTest;


        /*
        |--------------------------------------------------------------------------
        | Get Student-Facing Test Items
        |--------------------------------------------------------------------------
        |
        | This returns:
        |
        | - Standalone questions
        | - Scenario/paragraph questions with their children
        |
        | The Scenario parent itself is NOT a scorable question.
        |
        */

        $questions = $this->getTestItems($mockTest);


        /*
        |--------------------------------------------------------------------------
        | Build Scorable Questions
        |--------------------------------------------------------------------------
        |
        | Standalone:
        |
        |     Question
        |
        | Scenario:
        |
        |     Scenario
        |       ├── Child 1
        |       ├── Child 2
        |       └── Child 3
        |
        | The Scenario is only a container.
        | Its children are the actual questions.
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

        $studentAnswers = StudentAnswer::with('question')
            ->where('attempt_id', $attempt->id)
            ->get()
            ->keyBy('question_id');


        /*
        |--------------------------------------------------------------------------
        | Calculate Result Counts
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
        | Total Questions
        |--------------------------------------------------------------------------
        */

        $totalQuestions = $scorableQuestions->count();


        /*
        |--------------------------------------------------------------------------
        | Total Possible Marks
        |--------------------------------------------------------------------------
        */

        $totalMarks = $scorableQuestions->sum(function ($question) {

            return $question->marks ?? 0;

        });


        /*
        |--------------------------------------------------------------------------
        | Marks Awarded
        |--------------------------------------------------------------------------
        */

        $marksAwarded = $studentAnswers->sum(function ($answer) {

            return $answer->marks_awarded ?? 0;

        });


        /*
        |--------------------------------------------------------------------------
        | Return Admin Response Sheet
        |--------------------------------------------------------------------------
        */

        return view('admin.responses.show', [

            'attempt' => $attempt,

            'answers' => $studentAnswers->values(),

            'studentAnswers' => $studentAnswers,

            'questions' => $questions,

            'totalQuestions' => $totalQuestions,

            'correctCount' => $correctCount,

            'wrongCount' => $wrongCount,

            'unattemptedCount' => $unattemptedCount,

            'marksAwarded' => $marksAwarded,

            'totalMarks' => $totalMarks,

        ]);
    }


    /**
     * Get top-level test items.
     *
     * Returns:
     *
     * - Standalone questions
     * - Scenario/paragraph questions
     *
     * Child questions are loaded with their parent Scenario.
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
                'children.subTopic',
            ])
            ->get();
    }


    /**
     * Determine whether a StudentAnswer actually contains an answer.
     *
     * This is the same logic currently used by StudentController.
     */
    private function isAnswerRecordAnswered($answer)
    {
        if (!$answer) {

            return false;

        }


        $value = $answer->selected_option;


        /*
        |--------------------------------------------------------------------------
        | No Answer
        |--------------------------------------------------------------------------
        */

        if (is_null($value)) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | JSON Array
        |--------------------------------------------------------------------------
        |
        | Used by:
        |
        | - Multiple Select
        | - Table MCQ
        | - Dropdown
        | - Drag & Drop
        |
        */

        $decoded = json_decode($value, true);


        if (
            json_last_error() === JSON_ERROR_NONE
            &&
            is_array($decoded)
        ) {

            return count($decoded) > 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Normal String Answer
        |--------------------------------------------------------------------------
        */

        return trim((string) $value) !== '';
    }
}