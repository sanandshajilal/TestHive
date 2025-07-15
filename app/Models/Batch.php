<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    public function institute()
{
    return $this->belongsTo(Institute::class);
}

protected $fillable = [
    'name',
    'institute_id',
];

public function mockTests()
{
    return $this->belongsToMany(MockTest::class, 'mock_test_batch');
}


}

