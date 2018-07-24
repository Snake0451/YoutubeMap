<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emotion extends Model
{
    public function videos() {
        return $this->belongsTo('App/Video');
    }

    public function reactions() {
        return $this->belongsTo('App/Reaction');
    }
}
