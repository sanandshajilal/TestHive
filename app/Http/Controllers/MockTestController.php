<?php

namespace App\Http\Controllers;

use App\Models\MockTest;
use App\Models\Paper;
use App\Models\Batch;
use App\Models\Topic;
use App\Models\Subtopic;
use App\Models\Question;
use App\Models\StudentTestAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MockTestController extends Controller
{
    public function create()
    {
        $papers = Paper::all();
        $batches = Batch::with('institute')->get();
        $topics = Topic::all(); 

        return view('admin.mock_tests.create', compact('papers', 'batches', 'topics'));
    }

    public function store(Request $request)
            {
                $validated = $request->validate([
                    'paper_id' => 'required|exists:papers,id',
                    'title' => 'required|string',
                    'start_time' => 'required|date',
                    'end_time' => 'required|date|after_or_equal:start_time',
                    'duration_minutes' => 'required|integer|min:1',
                    'question_ids_serialized' => 'required|string',
                    'batch_id' => 'required|exists:batches,id',
                ]);

                $questionIds = json_decode($validated['question_ids_serialized'], true);

                if (!is_array($questionIds) || empty($questionIds)) {
                    return back()->withErrors(['question_ids_serialized' => 'Please select at least one question.']);
                }

                $mockTest = MockTest::create([
                    'paper_id' => $validated['paper_id'],
                    'title' => $validated['title'],
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'duration_minutes' => $validated['duration_minutes'],
                    'access_code' => Str::upper(Str::random(6)),
                ]);

                $mockTest->questions()->attach($questionIds);
                $mockTest->batches()->attach($validated['batch_id']);

                return redirect()->route('mock-tests.index')->with('success', 'Mock test created successfully!');
            }


    public function index()
{
    $mockTests = MockTest::with([
        'paper',
        'questions.children'
    ])
    ->orderBy('start_time', 'desc')
    ->get()
    ->map(function ($test) {

        $now = Carbon::now();

        $start = Carbon::parse($test->start_time);
        $end   = Carbon::parse($test->end_time);

        if ($now->lt($start)) {
            $test->status = 'Upcoming';
        } elseif ($now->between($start, $end)) {
            $test->status = 'Active';
        } else {
            $test->status = 'Expired';
        }


        /*
        |--------------------------------------------------------------------------
        | Student-Facing Question Count
        |--------------------------------------------------------------------------
        |
        | Normal question = 1
        |
        | Scenario = container only
        | Scenario children = individual questions
        |
        */

        $totalQuestions = 0;

        foreach ($test->questions as $question) {

            if ($question->question_type === 'paragraph') {

                $totalQuestions += $question->children->count();

            } else {

                $totalQuestions++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Total Possible Marks
        |--------------------------------------------------------------------------
        |
        | Normal question = its own marks
        |
        | Scenario = sum of child marks
        |
        */

        $totalMarks = 0;

        foreach ($test->questions as $question) {

            if ($question->question_type === 'paragraph') {

                $totalMarks += $question->children->sum('marks');

            } else {

                $totalMarks += $question->marks ?? 0;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Temporary Values for Index View
        |--------------------------------------------------------------------------
        */

        $test->total_questions = $totalQuestions;

        $test->total_marks = $totalMarks;


        return $test;
    });

    return view(
        'admin.mock_tests.index',
        compact('mockTests')
    );
}

    public function view(MockTest $mockTest)
    {
        $questions = $mockTest->questions()->paginate(20);
        return view('admin.mock_tests.view', compact('mockTest', 'questions'));
    }

    public function destroy(MockTest $mockTest)
    {
        $mockTest->delete();
        return redirect()->route('mock-tests.index')->with('success', 'Mock test deleted.');
    }

    public function edit(MockTest $mockTest)
    {
        $papers = Paper::all();

        $batches = Batch::with('institute')->get();

        $topics = Topic::all();

        $allQuestions = Question::where('paper_id', $mockTest->paper_id)
            ->whereNull('parent_question_id')
            ->get();

        $selectedQuestionIds = $mockTest->questions->pluck('id')->toArray();

        $selectedQuestions = $mockTest->questions()
            ->with(['topic', 'subTopic'])
            ->get();

        return view('admin.mock_tests.edit', compact(
            'mockTest',
            'papers',
            'batches',
            'topics',
            'allQuestions',
            'selectedQuestionIds',
            'selectedQuestions'
        ));
    }

public function update(Request $request, MockTest $mockTest)
{

    $validated = $request->validate([
        'title' => 'required|string',
        'paper_id' => 'required|exists:papers,id',
        'duration_minutes' => 'required|integer|min:1',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after_or_equal:start_time',
        'batch_id' => 'required|exists:batches,id',
        'question_ids_serialized' => 'required|string',
    ]);

    
    $questionIds = json_decode($validated['question_ids_serialized'], true);

    if (!is_array($questionIds) || empty($questionIds)) {
        return back()->withErrors([
            'question_ids_serialized' => 'Please select at least one question.'
        ]);
    }

    $mockTest->update([
        'title' => $validated['title'],
        'paper_id' => $validated['paper_id'],
        'duration_minutes' => $validated['duration_minutes'],
        'start_time' => $validated['start_time'],
        'end_time' => $validated['end_time'],
    ]);

    $mockTest->batches()->sync([$validated['batch_id']]);
    $mockTest->questions()->sync($questionIds);

    return redirect()
        ->route('mock-tests.index')
        ->with('success', 'Mock test updated successfully.');
}

    /**
     * AJAX: Filter questions by paper, topic, subtopic, type
     */
    public function getQuestionsByPaper(Request $request)
{
    $query = Question::whereNull('parent_question_id');

    if ($request->paper_id) {
        $query->where('paper_id', $request->paper_id);
    }

    if ($request->topic_id) {
        $query->where('topic_id', $request->topic_id);
    }

    if ($request->filled('subtopic_id')) {
        $query->where('sub_topic_id', $request->subtopic_id);
    }

    if ($request->type) {
        $query->where('question_type', $request->type);
    }

    $questions = $query->with(['topic:id,name', 'subTopic:id,name'])->get([
        'id',
        'question_text',
        'question_type',
        'topic_id',
        'sub_topic_id',
    ]);

    $questions->each(function ($q) {
        $q->topic_name = $q->topic->name ?? 'Unknown';
        $q->subtopic_name = $q->subTopic->name ?? 'N/A';
    });

    return response()->json($questions);
}


    /**
     * AJAX: Get subtopics for a topic
     */
    public function getSubtopics(Request $request)
    {
        $topicId = $request->input('topic_id');
        $subtopics = Subtopic::where('topic_id', $topicId)->get(['id', 'name']);
        return response()->json($subtopics);
    }

    public function preview($id)
        {
            $mockTest = MockTest::with([
                'paper',
                'batches',
                'questions.children',
                'questions.topic',
                'questions.subTopic'
            ])->findOrFail($id);

            return view('admin.mock_tests.preview', compact('mockTest'));
        }

public function results($id)
{
    $mockTest = \App\Models\MockTest::with([
        'paper',
        'questions' => function ($query) {
            $query->whereNull('parent_question_id')
                  ->with('children');
        }
    ])->findOrFail($id);


    /*
    |--------------------------------------------------------------------------
    | Build Scorable Questions
    |--------------------------------------------------------------------------
    |
    | Standalone question = 1 question
    |
    | Scenario = container only
    | Scenario children = individual questions
    |
    */

    $scorableQuestions = collect();

    foreach ($mockTest->questions as $question) {

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
    | Attempts
    |--------------------------------------------------------------------------
    */

    $attempts = \App\Models\StudentTestAttempt::with([
        'institute',
        'batch',
        'answers'
    ])
    ->where('mock_test_id', $id)
    ->get();


    /*
    |--------------------------------------------------------------------------
    | Total Students
    |--------------------------------------------------------------------------
    */

    $batch = $mockTest->batches->first();

    $totalStudents = $batch
        ? $batch->students()
            ->where('is_active', true)
            ->count()
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Completed Students
    |--------------------------------------------------------------------------
    */

    $completedStudents = \App\Models\StudentTestAttempt::where(
        'mock_test_id',
        $mockTest->id
    )
    ->where('status', 'completed')
    ->count();


    $completionPercentage = $totalStudents > 0
        ? round(($completedStudents / $totalStudents) * 100)
        : 0;


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

    $totalPossibleMarks = $scorableQuestions->sum(function ($question) {

        return $question->marks ?? 0;

    });


    /*
    |--------------------------------------------------------------------------
    | Recalculate Each Student's Result
    |--------------------------------------------------------------------------
    */

    foreach ($attempts as $attempt) {

        $answers = $attempt->answers->keyBy('question_id');


        $correctCount = 0;
        $wrongCount = 0;
        $notAttempted = 0;
        $totalMarks = 0;


        foreach ($scorableQuestions as $question) {

            $answer = $answers->get($question->id);


            /*
            |--------------------------------------------------------------------------
            | Determine Whether Answered
            |--------------------------------------------------------------------------
            */

            $isAnswered = false;


            if ($answer) {

                $value = $answer->selected_option;


                if (!is_null($value)) {

                    $decoded = json_decode($value, true);


                    if (
                        json_last_error() === JSON_ERROR_NONE
                        &&
                        is_array($decoded)
                    ) {

                        $isAnswered = count($decoded) > 0;

                    } else {

                        $isAnswered = trim((string) $value) !== '';

                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Not Attempted
            |--------------------------------------------------------------------------
            */

            if (!$isAnswered) {

                $notAttempted++;

                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | Correct / Wrong
            |--------------------------------------------------------------------------
            */

            if ($answer->is_correct) {

                $correctCount++;

            } else {

                $wrongCount++;

            }


            /*
            |--------------------------------------------------------------------------
            | Marks Awarded
            |--------------------------------------------------------------------------
            */

            $totalMarks += $answer->marks_awarded ?? 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Attach Fresh Values to Attempt
        |--------------------------------------------------------------------------
        |
        | These are temporary values for this page.
        | We are NOT updating the database here.
        |
        */

        $attempt->correct_count = $correctCount;

        $attempt->wrong_count = $wrongCount;

        $attempt->not_attempted = $notAttempted;

        $attempt->total_marks = $totalMarks;

    }


    /*
    |--------------------------------------------------------------------------
    | Average Score
    |--------------------------------------------------------------------------
    */

    $averageScore = $attempts
        ->where('status', 'completed')
        ->avg(function ($attempt) use ($totalPossibleMarks) {

            return $totalPossibleMarks > 0
                ? ($attempt->total_marks / $totalPossibleMarks) * 100
                : 0;

        });


    $averageScore = round($averageScore ?? 0, 1);


    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.mock_tests.results',
        compact(
            'mockTest',
            'attempts',
            'totalStudents',
            'completedStudents',
            'completionPercentage',
            'averageScore',
            'totalQuestions',
            'totalPossibleMarks'
        )
    );
}


public function duplicate(MockTest $mockTest)
{
    $newTest = $mockTest->replicate();

    $newTest->title = $mockTest->title . ' - Copy';
    $newTest->access_code = strtoupper(Str::random(6));

    $newTest->save();

    $questionIds = $mockTest->questions()
        ->pluck('questions.id')
        ->toArray();

    $pivotRows = [];

    foreach ($questionIds as $questionId) {
        $pivotRows[] = [
            'mock_test_id' => $newTest->id,
            'question_id' => $questionId,
        ];
    }

    if (!empty($pivotRows)) {
        DB::table('mock_test_question')->insert($pivotRows);
    }

    return redirect()
        ->route('mock-tests.edit', $newTest)
        ->with('success', 'Mock Test duplicated successfully.');
}

}
