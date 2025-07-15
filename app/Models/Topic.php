<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'paper_id'];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function subTopics()
    {
        return $this->hasMany(SubTopic::class);
    }
}
