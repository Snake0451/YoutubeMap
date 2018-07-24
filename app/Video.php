<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = [];

    public function event() {
        return $this->hasOne('App/Event');
    }

    public function emotion() {
        return $this->hasOne('App/Emotion');
    }

    public function comments() {
        return $this->hasMany('App/Comment');
    }

    public function reactions() {
        return $this->hasOne('App/Reaction');
    }
}
