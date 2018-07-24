<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function video() {
        return $this->belongsTo('App/Video');
    }

    public function parentComment() {
        return $this->belongsTo('App/Comment');
    }

    public function childComments() {
        return $this->hasMany('App/Comment');
    }

    public function reactions() {
        return $this->hasMany('App/Reaction');
    }
}
