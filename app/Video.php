<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = [];

    public function event() {
        return $this->belongsTo('App\Event');
    }

    public function emotion() {
        return $this->belongsTo('App\Emotion');
    }

    public function comments() {
        return $this->hasMany('App\Comment');
    }

    public function reactions() {
        return $this->hasMany('App\Reaction');
    }
}
