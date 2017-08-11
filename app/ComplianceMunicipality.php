<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplianceMunicipality extends Model
{
    protected $fillable = [
    	'location', 'date_complied', 'reminder_id', 'score', 'approved_by', 'rejected', 'rejected_by', 'accepted_by'
    ];

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

    public function location($loc){
    	//$loc = $this->location;
        switch ($loc) {
            case 1:
                return 'Altavas';
                break;
            case 2:
                return 'Balete';
                break;
            case 3:
                return 'Banga';
                break;
            case 4:
                return 'Batan';
                break;
            case 5:
                return 'Buruanga';
                break;
            case 6:
                return 'Ibajay';
                break;
            case 7:
                return 'Kalibo';
                break;
            case 8:
                return 'Lezo';
                break;
            case 9:
                return 'Libacao';
                break;
            case 10:
                return 'Madalag';
                break;
            case 11:
                return 'Makato';
                break;
            case 12:
                return 'Malay';
                break;
            case 13:
                return 'Malinao';
                break;
            case 14:
                return 'Nabas';
                break;
            case 15:
                return 'New Washington';
                break;
            case 16:
                return 'Numancia';
                break;
            case 17:
                return 'Tangalan';
                break;
            default:
                return 'OPES';
                break;
        }
   }

}
