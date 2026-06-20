<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'batch_id',
        'name',
        'email',
        'is_active'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
