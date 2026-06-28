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

        $studentCount = Student::where('is_active', true)->count();

        // Average Score %
        $attempts = StudentTestAttempt::with('mockTest.questions')
            ->where('status', 'completed')
            ->get();

        $averagePercentage = 0;

       

        if ($attempts->count()) {

            $averagePercentage = round(
                $attempts->avg(function ($attempt) {

                    $totalPossible = $attempt->mockTest
                        ? $attempt->mockTest->questions->sum('marks')
                        : 0;

                    return $totalPossible > 0
                        ? ($attempt->total_marks / $totalPossible) * 100
                        : 0;
                }),
                1
            );
        }

        $latestMockTest = MockTest::where('start_time', '<=', now())
            ->latest('start_time')
            ->with(['questions', 'paper'])
            ->first();

            $latestAttempts = 0;
            $latestAverageScore = 0;
            $latestHighestScore = 0;

            if ($latestMockTest) {

                $latestResponses = StudentTestAttempt::where(
                    'mock_test_id',
                    $latestMockTest->id
                )
                ->where('status', 'completed')
                ->get();

                $latestAttempts = $latestResponses->count();

                $latestAverageScore = round(
                    $latestResponses->avg(function ($attempt) use ($latestMockTest) {

                        $totalPossible = $latestMockTest->questions->sum('marks');

                        return $totalPossible > 0
                            ? ($attempt->total_marks / $totalPossible) * 100
                            : 0;
                    }) ?? 0,
                    1
                );

                $latestHighestScore = round(
                    $latestResponses->max(function ($attempt) use ($latestMockTest) {

                        $totalPossible = $latestMockTest->questions->sum('marks');

                        return $totalPossible > 0
                            ? ($attempt->total_marks / $totalPossible) * 100
                            : 0;
                    }) ?? 0,
                    1
                );
            }

            $activeTests = MockTest::where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->with([
                    'paper',
                    'batches.institute',
                    'batches.students'
                ])
                ->orderBy('start_time')
                ->get();

                foreach ($activeTests as $test) {

                    $batch = $test->batches->first();

                    $totalStudents = $batch
                        ? $batch->students->where('is_active', true)->count()
                        : 0;

                    $completedStudents = StudentTestAttempt::where('mock_test_id', $test->id)
                        ->where('status', 'completed')
                        ->count();

                    $test->total_students = $totalStudents;

                    $test->completed_students = $completedStudents;

                    $test->completion_percentage = $totalStudents > 0
                        ? round(($completedStudents / $totalStudents) * 100)
                        : 0;
                }
        

        return view('admin.dashboard', [
            'instituteCount' => Institute::count(),
            'batchCount' => Batch::count(),
            'paperCount' => Paper::count(),
            'questionCount' => Question::count(),
            'mockTestCount' => MockTest::count(),
            'responseCount' => StudentTestAttempt::count(),

            'activeMockTests' => $activeMockTests,
            'completedAttempts' => $completedAttempts,
            'averagePercentage' => $averagePercentage,

            'recentMockTests' => MockTest::where('start_time', '<=', now())
                    ->with('paper')
                    ->orderByDesc('start_time')
                    ->take(5)
                    ->get(),

            'recentResponses' => StudentTestAttempt::latest()
                ->take(5)
                ->with(['mockTest.paper'])
                ->get(),

                'latestMockTest' => $latestMockTest,
                'latestAttempts' => $latestAttempts,
                'latestAverageScore' => $latestAverageScore,
                'latestHighestScore' => $latestHighestScore,
                'activeTests' => $activeTests,
                'studentCount' => $studentCount,
        ]);
    }
}