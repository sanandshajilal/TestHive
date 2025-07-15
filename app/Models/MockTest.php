<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockTest extends Model
{
    protected $fillable = [
        'paper_id', 'title', 'start_time', 'end_time', 'access_code', 'duration_minutes'
    ];

    protected $casts = [
        'options' => 'array',
        'table_mcq_rows' => 'array',
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'mock_test_question');
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'mock_test_batch');
    }

}
