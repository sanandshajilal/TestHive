<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubTopic extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'topic_id'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
