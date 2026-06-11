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

class DashboardController extends Controller
{
    public function index()
    {
        // Active Mock Tests
        $activeMockTests = MockTest::count();

        // Completed Attempts
        $completedAttempts = StudentTestAttempt::where('status', 'completed')
            ->count();

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

            'recentMockTests' => MockTest::latest()
                ->take(5)
                ->with('paper')
                ->get(),

            'recentResponses' => StudentTestAttempt::latest()
                ->take(5)
                ->with(['mockTest.paper'])
                ->get(),
        ]);
    }
}