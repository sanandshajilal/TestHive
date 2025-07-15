<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentTestAttempt;
use App\Models\Question;

class ResponseController extends Controller
{
  public function show($id)
{
    $attempt = StudentTestAttempt::with([
        'answers',
        'mockTest.questions',
        'institute',
        'batch'
    ])->findOrFail($id);

    $answers = $attempt->answers;

    return view('admin.responses.show', [
        'attempt' => $attempt,
        'answers' => $answers
    ]);
}
}
