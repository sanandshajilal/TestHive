<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'institute_id',
        'batch_id',
        'mock_test_id',
        'email',
        'start_time',
        'end_time',
        'correct_count',
        'wrong_count',
        'not_attempted',
        'total_marks',
        'access_code',
        'status', // ✅ Add this
    ];
    
    // Relationships
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function mockTest()
    {
        return $this->belongsTo(MockTest::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'attempt_id');
    }
}
