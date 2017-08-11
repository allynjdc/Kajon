<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Document;

class Tag extends Model
{
    protected $fillable = [
    	'location', 'name'
    ];
    
    public function documents(){
    	return $this->hasMany('App\Document');
    }

    public static function locationTags($location){
    	$documents = Document::where('deleted', '0')->get();
    	$tag_array = array();
    	foreach($documents as $document){
    		if($document->locatedAt() == $location){
    			$tag_array[] = $document->tag_id;
    		}
    	}
    	return Tag::whereIn('id', $tag_array)->get();
    }
}
