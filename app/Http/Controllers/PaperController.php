<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Topic;
use App\Models\SubTopic;
use Illuminate\Http\Request;
use App\Models\Question;

class PaperController extends Controller
{
        public function index()
        {
            $papers = Paper::withCount(['topics', 'questions'])
                ->orderBy('name')
                ->get();

            return view('papers.index', compact('papers'));
        }

    public function create()
    {
        return view('papers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:papers,name',
            'description' => 'nullable',
            'topics.*.name' => 'required|string',
            'topics.*.subtopics.*' => 'nullable|string',
        ]);

        // Create the paper
        $paper = Paper::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Loop through topics and subtopics
        foreach ($request->input('topics', []) as $topicData) {
            $topic = new Topic([
                'name' => $topicData['name'],
                'paper_id' => $paper->id,
            ]);
            $topic->save();

            // Save subtopics if any (skip empty ones)
            if (!empty($topicData['subtopics'])) {
                foreach ($topicData['subtopics'] as $subName) {
                    if (trim($subName) !== '') {
                        SubTopic::create([
                            'name' => $subName,
                            'topic_id' => $topic->id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('papers.index')->with('success', 'Paper and topics added successfully.');
    }

    public function edit(Paper $paper)
    {
        $paper->load('topics.subTopics');
        return view('papers.edit', compact('paper'));
    }

public function update(Request $request, Paper $paper)
{
    $request->validate([
        'name' => 'required|unique:papers,name,' . $paper->id,
        'description' => 'nullable',

        'topics' => 'nullable|array',
        'topics.*.name' => 'required|string',

        'topics.*.sub_topics' => 'nullable|array',
        'topics.*.sub_topics.*' => 'nullable|string',

        'topics.*.subtopic_ids' => 'nullable|array',
        'topics.*.subtopic_ids.*' => 'nullable|integer',
    ]);

    $paper->update([
        'name' => $request->name,
        'description' => $request->description,
    ]);


    // =========================================================
    // Topics removed from the form
    // =========================================================

    $submittedTopicIds = collect($request->topics ?? [])
        ->pluck('id')
        ->filter()
        ->toArray();

    $deletedTopics = $paper->topics()
        ->whereNotIn('id', $submittedTopicIds)
        ->get();


    // Check if deleted topics have linked questions

    foreach ($deletedTopics as $topic) {

        $questionCount = Question::where('topic_id', $topic->id)->count();

        if ($questionCount > 0) {

            return back()
                ->withInput()
                ->withErrors([
                    'topic_delete' =>
                        "Cannot delete topic '{$topic->name}' because questions are linked to it."
                ]);
        }
    }


    // Safe deletion of topics

    foreach ($deletedTopics as $topic) {

        $topic->subTopics()->delete();
        $topic->delete();
    }


    // =========================================================
    // Process submitted topics
    // =========================================================

    foreach ($request->topics ?? [] as $topicData) {


        // =====================================================
        // Existing Topic
        // =====================================================

        if (!empty($topicData['id'])) {

            $topic = Topic::find($topicData['id']);

            if (!$topic) {
                continue;
            }


            $topic->update([
                'name' => $topicData['name']
            ]);


            // -------------------------------------------------
            // Existing sub-topics belonging to this topic
            // -------------------------------------------------

            $existingSubTopicIds = $topic->subTopics()
                ->pluck('id')
                ->toArray();


            // -------------------------------------------------
            // Sub-topic IDs still present in the form
            // -------------------------------------------------

            $submittedSubTopicIds = collect(
                $topicData['subtopic_ids'] ?? []
            )
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->toArray();


            // -------------------------------------------------
            // Delete sub-topics removed from the form
            // -------------------------------------------------

            $deletedSubTopicIds = array_diff(
                $existingSubTopicIds,
                $submittedSubTopicIds
            );


            if (!empty($deletedSubTopicIds)) {

                SubTopic::whereIn('id', $deletedSubTopicIds)
                    ->where('topic_id', $topic->id)
                    ->delete();
            }


            // -------------------------------------------------
            // Update existing / create new sub-topics
            // -------------------------------------------------

            foreach ($topicData['sub_topics'] ?? [] as $index => $subName) {

                $subName = trim($subName);

                // Ignore empty sub-topic values

                if ($subName === '') {
                    continue;
                }


                $subTopicId =
                    $topicData['subtopic_ids'][$index] ?? null;


                // Existing sub-topic

                if ($subTopicId) {

                    $subTopic = SubTopic::where('id', $subTopicId)
                        ->where('topic_id', $topic->id)
                        ->first();

                    if ($subTopic) {

                        $subTopic->update([
                            'name' => $subName
                        ]);
                    }

                }

                // New sub-topic

                else {

                    SubTopic::create([
                        'name' => $subName,
                        'topic_id' => $topic->id
                    ]);
                }
            }

        }


        // =====================================================
        // New Topic
        // =====================================================

        else {

            $topic = Topic::create([
                'name' => $topicData['name'],
                'paper_id' => $paper->id
            ]);


            foreach ($topicData['sub_topics'] ?? [] as $subName) {

                $subName = trim($subName);

                if ($subName === '') {
                    continue;
                }

                SubTopic::create([
                    'name' => $subName,
                    'topic_id' => $topic->id
                ]);
            }
        }
    }


    return redirect()
        ->route('papers.index')
        ->with('success', 'Paper updated successfully.');
}

    public function destroy(Paper $paper)
        {
            $questionCount = Question::where('paper_id', $paper->id)->count();

            if ($questionCount > 0) {

                return redirect()
                    ->route('papers.index')
                    ->with('error',
                        "Cannot delete paper '{$paper->name}' because questions are linked to it.");
            }

            $paper->delete();

            return redirect()
                ->route('papers.index')
                ->with('success', 'Paper deleted successfully.');
        }
}
