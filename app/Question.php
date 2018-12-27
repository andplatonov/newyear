<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function pre_questions()
    {
        return $this->hasMany(PreQuestion::class);
    }

    public function post_questions()
    {
        return $this->hasMany(PostQuestion::class);
    }
}
