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
    return view('admin.dashboard', [
            'instituteCount' => Institute::count(),
            'batchCount' => Batch::count(),
            'paperCount' => Paper::count(),
            'questionCount' => Question::count(),
            'mockTestCount' => MockTest::count(),
            'responseCount' => StudentTestAttempt::count(),
            'recentMockTests' => MockTest::latest()->take(5)->with('paper')->get(),
            'recentResponses' => StudentTestAttempt::latest()->take(5)->with(['mockTest.paper'])->get(),
        ]);
    }
}