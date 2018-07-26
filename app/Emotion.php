<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emotion extends Model
{
    public function videos() {
        return $this->hasMany('App/Video');
    }

    public function reactions() {
        return $this->hasMany('App/Reaction');
    }
}
