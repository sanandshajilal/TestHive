<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubTopic;
use App\Models\Topic;

class SubTopicController extends Controller
{
    public function getByTopic($topicId)
{
    $subtopics = \App\Models\SubTopic::where('topic_id', $topicId)->get(['id', 'name']);
    return response()->json($subtopics);
}

}
