<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ComplianceMunicipality;
use App\ComplianceUser;
use App\Reminder;
use Auth; 

class Reminder extends Model
{
    protected $fillable = [
    	'user_id', 'due_date', 'title', 'description', 'updated_by', 'municipality', 'active', 'deleted'
    ];

    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function updatedBy(){
    	return $this->belongsTo('App\User', 'updated_by');
    }

    public function locatedAt(){
    	return $this->user()->location;
    }

    public function checkComplied(){

        $loc = Auth::user()->location;
        $uid = Auth::user()->id; 

        if(!$this->municipality){
            $id = ComplianceUser::where('reminder_id', $this->id)
                    ->where('user_id',$uid)
                    ->pluck('id');
            $complied = ComplianceUser::find($id);
        } else {
            $id= ComplianceMunicipality::where('reminder_id', $this->id)
                ->where('location',$loc)
                ->pluck('id');
            $complied = ComplianceMunicipality::findOrFail($id);
        }
        if($complied[0] != null){
            if($complied[0]->date_complied == null && $complied[0]->rejected == 0){
                return true;
            }
        } else {
             if($complied->date_complied == null && $complied->rejected == 0){
                return true;
            }
        }

        return false;
    }

    public function checkRejected(){

        $loc = Auth::user()->location;
        $uid = Auth::user()->id; 

        if($this->municipality){
            $id= ComplianceMunicipality::where('reminder_id', $this->id)
                    ->where('location',$loc)
                    ->pluck('id');
            $complied = ComplianceMunicipality::find($id);

        } else {
            $id = ComplianceUser::where('reminder_id', $this->id)
                    ->where('user_id',$uid)
                    ->pluck('id');
            $complied = ComplianceUser::find($id);
        }
        
        if($complied[0] != null){
            if($complied[0]->rejected == 1 && $complied[0]->rejected_by != null){
                $user = User::find($complied[0]->rejected_by);
                return $user->name;
            }
        } else {
            if($complied->rejected == 1 && $complied->rejected_by != null){
                $user = User::find($complied->rejected_by);
                return $user->name;
            }
        }

        
        return false;
    }

    public function checkAccepted(){

        $loc = Auth::user()->location;
        $uid = Auth::user()->id; 

        if($this->municipality){
            $id= ComplianceMunicipality::where('reminder_id', $this->id)
                    ->where('location',$loc)
                    ->pluck('id');
            $complied = ComplianceMunicipality::find($id);

        } else {
            $id = ComplianceUser::where('reminder_id', $this->id)
                    ->where('user_id',$uid)
                    ->pluck('id');
            $complied = ComplianceUser::find($id);
        }
        
        if($complied[0] != null){
            if($complied[0]->accepted_by != null){
                $user = User::find($complied[0]->accepted_by);
                return $user->name;
            }
        } else {
            if($complied->accepted_by != null){
                $user = User::find($complied->accepted_by);
                return $user->name;
            }
        }

        
        //return $complied;
        return false;
    }

    public function compliancesMunicipality(){
    	if($this->municipality){
    		return $this->hasMany('App\ComplianceMunicipality');
    	}
    }

    public function compliancesIndividual(){
    	if(!$this->municipality){
    		return $this->hasMany('App\ComplianceUser');
    	}
    }

}
