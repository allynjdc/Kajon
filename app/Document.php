<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Auth;

class Document extends Model
{
    protected $fillable = [
    	'user_id', 'filename', 'filepath', 'description', 'deleted', 'public', 'shared_by_admin', 'tag_id'
    ];

    public function user(){
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function locatedAt(){
    	return $this->user->location;
    }

    public function ownedByUser(){
    	return $this->user->id == Auth::user()->id;
    }

    public function tag(){
        return $this->belongsTo('App\Tag');
    }
}
