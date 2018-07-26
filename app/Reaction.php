<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    public function comment() {
        return $this->belongsTo('App/Comment');
    }

    public function video() {
        return $this->belongsTo('App/Video');
    }

    public function user() {
        return $this->belongsTo('App/User');
    }

    public function emotion() {
        return $this->hasOne('App/User');
    }
}
