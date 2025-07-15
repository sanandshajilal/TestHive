<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'paper_id',
        'topic_id',
        'sub_topic_id',
        'question_type',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'correct_answers',
        'marks',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function subTopic()
    {
        return $this->belongsTo(SubTopic::class, 'sub_topic_id');
    }

    public function mockTests()
    {
        return $this->belongsToMany(MockTest::class, 'mock_test_question');
    }
}
