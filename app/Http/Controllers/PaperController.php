<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Topic;
use App\Models\SubTopic;
use Illuminate\Http\Request;

class PaperController extends Controller
{
    public function index()
    {
        $papers = Paper::all();
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

        $paper->update($request->all());

        return redirect()->route('papers.index')->with('success', 'Paper updated successfully.');
    }

    public function destroy(Paper $paper)
    {
        $paper->delete();
        return redirect()->route('papers.index')->with('success', 'Paper deleted.');
    }
}
