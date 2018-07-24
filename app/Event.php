<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function videos() {
        return $this->belongsTo('App/Video');
    }
    
}
