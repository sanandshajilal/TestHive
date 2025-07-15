<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_option',
        'is_correct',
        'marks_awarded',
        'is_flagged',
    ];
    

    // Relationships
    public function attempt()
    {
        return $this->belongsTo(StudentTestAttempt::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    
}
