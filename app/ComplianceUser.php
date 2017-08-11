<?php

namespace App;

use Illuminate\Database\Eloquent\Model; 

class ComplianceUser extends Model
{
    protected $fillable = [
    	'user_id', 'date_complied', 'reminder_id', 'score', 'approved_by', 'rejected', 'rejected_by', 'accepted_by'
    ];

    public function user(){
    	return $this->belongsTo('App\User');
    }
 
    public function approvedBy(){
    	return $this->belongsTo('App\User', 'approved_by');
    }

    public function rejectedBy(){
    	return $this->belongsTo('App\User', 'rejected_by');
    }

    public function acceptedBy(){
    	return $this->belongsTo('App\User', 'accepted_by');
    }

    public function reminderBy(){
        return $this->belongsTo('App\Reminder', 'reminder_id');
    }

}
