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
        ]);

        $paper->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

                    // Topics removed from the form
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
                            'topic_delete' => "Cannot delete topic '{$topic->name}' because questions are linked to it."
                        ]);
                }
            }

            // Safe deletion
            foreach ($deletedTopics as $topic) {

                $topic->subTopics()->delete();
                $topic->delete();
            }

        foreach ($request->topics ?? [] as $topicData) {

            // Existing Topic
            if (!empty($topicData['id'])) {

                $topic = Topic::find($topicData['id']);

                if ($topic) {

                    $topic->update([
                        'name' => $topicData['name']
                    ]);

                    foreach ($topicData['sub_topics'] ?? [] as $index => $subName) {

                        $subTopicId =
                            $topicData['subtopic_ids'][$index] ?? null;

                        if ($subTopicId) {

                            $subTopic = SubTopic::find($subTopicId);

                            if ($subTopic) {
                                $subTopic->update([
                                    'name' => $subName
                                ]);
                            }

                        } elseif (trim($subName) !== '') {

                            SubTopic::create([
                                'name' => $subName,
                                'topic_id' => $topic->id
                            ]);
                        }
                    }
                }

            }
            // New Topic
            else {

                $topic = Topic::create([
                    'name' => $topicData['name'],
                    'paper_id' => $paper->id
                ]);

                foreach ($topicData['sub_topics'] ?? [] as $subName) {

                    if (trim($subName) !== '') {

                        SubTopic::create([
                            'name' => $subName,
                            'topic_id' => $topic->id
                        ]);
                    }
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
