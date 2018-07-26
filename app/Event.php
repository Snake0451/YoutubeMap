<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $fillable = ['title'];

    public function videos() {
        return $this->hasMany('App\Video');
    }
    
}
