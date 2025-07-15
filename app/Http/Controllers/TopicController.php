<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Paper;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function getByPaper($paperId)
    {
        $topics = Topic::where('paper_id', $paperId)->get(['id', 'name']);
        return response()->json($topics);
    }

    public function byPaper(Paper $paper)
    {
        $topics = $paper->topics()->with('subTopics')->get();
        return view('topics.by-paper', compact('paper', 'topics'));
    }

    public function create(Paper $paper)
    {
        return view('topics.create', compact('paper'));
    }

    public function store(Request $request, Paper $paper)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_topics' => 'nullable|array',
            'sub_topics.*' => 'nullable|string|max:255'
        ]);

        $topic = $paper->topics()->create(['name' => $request->name]);

        if ($request->has('sub_topics')) {
            foreach ($request->sub_topics as $subTopicName) {
                if ($subTopicName) {
                    $topic->subTopics()->create(['name' => $subTopicName]);
                }
            }
        }

        return redirect()->route('topics.by-paper', $paper->id)->with('success', 'Topic created successfully.');
    }

    public function edit(Topic $topic)
    {
        $paper = $topic->paper;
        $topic->load('subTopics');
        return view('topics.edit', compact('paper', 'topic'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_topics' => 'nullable|array',
            'sub_topics.*' => 'nullable|string|max:255'
        ]);

        $topic->update(['name' => $request->name]);
        $topic->subTopics()->delete();

        if ($request->has('sub_topics')) {
            foreach ($request->sub_topics as $subTopicName) {
                if ($subTopicName) {
                    $topic->subTopics()->create(['name' => $subTopicName]);
                }
            }
        }

        return redirect()->route('topics.by-paper', $topic->paper_id)->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $paperId = $topic->paper_id;
        $topic->subTopics()->delete();
        $topic->delete();

        return redirect()->route('topics.by-paper', $paperId)->with('success', 'Topic deleted successfully.');
    }
}
