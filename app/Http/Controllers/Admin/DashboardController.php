<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Institute;
use App\Models\Batch;
use App\Models\Paper;
use App\Models\Question;
use App\Models\MockTest;
use App\Models\StudentTestAttempt;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        // Active Mock Tests
        $activeMockTests = MockTest::count();

        // Completed Attempts
        $completedAttempts = StudentTestAttempt::where('status', 'completed')
            ->count();

        // Active Students
        $studentCount = Student::where('is_active', true)->count();

        /*
        |--------------------------------------------------------------------------
        | Average Score %
        |--------------------------------------------------------------------------
        |
        | Scenario questions:
        | - Parent Scenario carries 0 marks
        | - Child questions carry the actual marks
        |
        | Therefore, total possible marks must include:
        | - Normal question marks
        | - Scenario child question marks
        |
        */

        $attempts = StudentTestAttempt::with([
            'mockTest.questions.children'
        ])
        ->where('status', 'completed')
        ->get();

        $averagePercentage = 0;

        if ($attempts->count()) {

            $averagePercentage = round(
                $attempts->avg(function ($attempt) {

                    $totalPossible = 0;

                    if ($attempt->mockTest) {

                        foreach ($attempt->mockTest->questions as $question) {

                            if ($question->question_type === 'paragraph') {

                                $totalPossible += $question->children->sum('marks');

                            } else {

                                $totalPossible += $question->marks;
                            }
                        }
                    }

                    return $totalPossible > 0
                        ? ($attempt->total_marks / $totalPossible) * 100
                        : 0;
                }),
                1
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Latest Mock Test
        |--------------------------------------------------------------------------
        */

        $latestMockTest = MockTest::where('start_time', '<=', now())
            ->latest('start_time')
            ->with([
                'questions.children',
                'paper'
            ])
            ->first();

        $latestAttempts = 0;
        $latestAverageScore = 0;
        $latestHighestScore = 0;
        $latestTotalPossibleMarks = 0;

        if ($latestMockTest) {

            /*
            |--------------------------------------------------------------------------
            | Calculate Total Possible Marks
            |--------------------------------------------------------------------------
            */

            foreach ($latestMockTest->questions as $question) {

                if ($question->question_type === 'paragraph') {

                    $latestTotalPossibleMarks +=
                        $question->children->sum('marks');

                } else {

                    $latestTotalPossibleMarks +=
                        $question->marks;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Completed Attempts for Latest Test
            |--------------------------------------------------------------------------
            */

            $latestResponses = StudentTestAttempt::where(
                'mock_test_id',
                $latestMockTest->id
            )
            ->where('status', 'completed')
            ->get();

            $latestAttempts = $latestResponses->count();


            /*
            |--------------------------------------------------------------------------
            | Latest Test Average Score
            |--------------------------------------------------------------------------
            */

            $latestAverageScore = round(
                $latestResponses->avg(function ($attempt) use (
                    $latestTotalPossibleMarks
                ) {

                    return $latestTotalPossibleMarks > 0
                        ? ($attempt->total_marks / $latestTotalPossibleMarks) * 100
                        : 0;

                }) ?? 0,
                1
            );


            /*
            |--------------------------------------------------------------------------
            | Latest Test Highest Score
            |--------------------------------------------------------------------------
            */

            $latestHighestScore = round(
                $latestResponses->max(function ($attempt) use (
                    $latestTotalPossibleMarks
                ) {

                    return $latestTotalPossibleMarks > 0
                        ? ($attempt->total_marks / $latestTotalPossibleMarks) * 100
                        : 0;

                }) ?? 0,
                1
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Currently Active Tests
        |--------------------------------------------------------------------------
        */

        $activeTests = MockTest::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->with([
                'paper',
                'batches.institute',
                'batches.students'
            ])
            ->orderBy('start_time')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Active Test Completion Statistics
        |--------------------------------------------------------------------------
        */

        foreach ($activeTests as $test) {

            $batch = $test->batches->first();

            $totalStudents = $batch
                ? $batch->students
                    ->where('is_active', true)
                    ->count()
                : 0;

            $completedStudents = StudentTestAttempt::where(
                'mock_test_id',
                $test->id
            )
            ->where('status', 'completed')
            ->count();

            $test->total_students = $totalStudents;

            $test->completed_students = $completedStudents;

            $test->completion_percentage = $totalStudents > 0
                ? round(
                    ($completedStudents / $totalStudents) * 100
                )
                : 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', [

            'instituteCount' => Institute::count(),

            'batchCount' => Batch::count(),

            'paperCount' => Paper::count(),

            'questionCount' => Question::where('question_type', '!=', 'paragraph')->count(),

            'mockTestCount' => MockTest::count(),

            'responseCount' => StudentTestAttempt::count(),

            'activeMockTests' => $activeMockTests,

            'completedAttempts' => $completedAttempts,

            'averagePercentage' => $averagePercentage,


            /*
            |--------------------------------------------------------------------------
            | Recent Mock Tests
            |--------------------------------------------------------------------------
            */

            'recentMockTests' => MockTest::where(
                    'start_time',
                    '<=',
                    now()
                )
                ->with('paper')
                ->orderByDesc('start_time')
                ->take(5)
                ->get(),


            /*
            |--------------------------------------------------------------------------
            | Recent Responses
            |--------------------------------------------------------------------------
            */

            'recentResponses' => StudentTestAttempt::latest()
                ->take(5)
                ->with([
                    'mockTest.paper'
                ])
                ->get(),


            /*
            |--------------------------------------------------------------------------
            | Latest Mock Test Statistics
            |--------------------------------------------------------------------------
            */

            'latestMockTest' => $latestMockTest,

            'latestAttempts' => $latestAttempts,

            'latestAverageScore' => $latestAverageScore,

            'latestHighestScore' => $latestHighestScore,


            /*
            |--------------------------------------------------------------------------
            | Active Tests
            |--------------------------------------------------------------------------
            */

            'activeTests' => $activeTests,

            'studentCount' => $studentCount,
        ]);
    }
}