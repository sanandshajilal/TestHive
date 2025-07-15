<?php

namespace App\Http\Controllers;

use App\Models\MockTest;
use App\Models\Paper;
use App\Models\Batch;
use App\Models\Topic;
use App\Models\Subtopic;
use App\Models\Question;
use Illuminate\Http\Request;
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
        $mockTests = MockTest::with(['paper', 'questions'])
            ->orderBy('start_time', 'desc')
            ->get()
            ->map(function ($test) {
                $now = Carbon::now();
                $start = Carbon::parse($test->start_time);
                $end = $start->copy()->addMinutes($test->duration_minutes);

                if ($now->lt($start)) {
                    $test->status = 'Upcoming';
                } elseif ($now->between($start, $end)) {
                    $test->status = 'Active';
                } else {
                    $test->status = 'Expired';
                }

                return $test;
            });

        return view('admin.mock_tests.index', compact('mockTests'));
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
        $topics = Topic::all(); // ✅ Fix for $topics
        $allQuestions = Question::where('paper_id', $mockTest->paper_id)->get();
        $selectedQuestionIds = $mockTest->questions->pluck('id')->toArray();

        return view('admin.mock_tests.edit', compact(
            'mockTest',
            'papers',
            'batches',
            'topics',
            'allQuestions',
            'selectedQuestionIds'
        ));
    }

    public function update(Request $request, MockTest $mockTest)
    {
        $request->validate([
            'title' => 'required|string',
            'paper_id' => 'required|exists:papers,id',
            'duration_minutes' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'batch_id' => 'required|exists:batches,id',
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
        ]);

        $mockTest->update([
            'title' => $request->title,
            'paper_id' => $request->paper_id,
            'duration_minutes' => $request->duration_minutes,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        $mockTest->batches()->sync([$request->batch_id]);
        $mockTest->questions()->sync($request->question_ids);

        return redirect()->route('mock-tests.index')->with('success', 'Mock test updated successfully.');
    }

    /**
     * AJAX: Filter questions by paper, topic, subtopic, type
     */
    public function getQuestionsByPaper(Request $request)
{
    $query = Question::query();

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
                'questions.topic',
                'questions.subTopic'
            ])->findOrFail($id);

            return view('admin.mock_tests.preview', compact('mockTest'));
        }

        public function results($id)
            {
                $mockTest = \App\Models\MockTest::with('paper')->findOrFail($id);

                $attempts = \App\Models\StudentTestAttempt::with(['institute', 'batch'])
                    ->where('mock_test_id', $id)
                    ->get();

                return view('admin.mock_tests.results', compact('mockTest', 'attempts'));
            }


}
