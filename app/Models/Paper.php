<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public function questions()
{
    return $this->hasMany(Question::class);
}

public function mockTests()
{
    return $this->hasMany(MockTest::class);
}

public function topics()
{
    return $this->hasMany(Topic::class);
}



}
