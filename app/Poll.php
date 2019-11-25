<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = ['title'];

    protected $hidden = ['questions'];

    // One-to-many relationship - one poll has many relationships

    public function questions() {
        return $this->hasMany('App\Question');
    }
}
