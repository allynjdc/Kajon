<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Admin;
use App\Document;
use App\Tag;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','name', 'email', 'password', 'location', 'role', 'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function adminRole(){
        $adminRole = Admin::where('user_id', $this->id)->pluck('role')->first();
        if($this->isAdmin()){
            return $adminRole['role'];
        }
        return null;
    }
 
    public function documents(){
        return $this->hasMany('App\Document');
    }

    public function isAdmin(){
        return $this->role == 0;
    }

    public function reminders(){
        return $this->hasMany('App\Reminder');
    }

    public function availableTags(){
        return Tag::where('location', $this->location)
                    ->orWhere('location', 0)
                    ->get();
    }

    public function usedTags(){
        return Tag::whereIn('tags.id', $this->documents()->where('deleted', '0')->pluck('tag_id'))->get();
    }

    public function notices(){
        $id = Auth::User()->id;
        $user = User::findOrFail($id);
        $location = $user->location;

        $notices = Reminder::where('reminders.active','0')
                ->whereIn('reminders.id', function($q) use ($user) {
                    $q->from('compliance_users')
                      ->where('compliance_users.user_id','=',$user->id)
                      ->where('compliance_users.score','=','0')
                      ->where('reminders.due_date','>=',date('Y-m-d'))
                      ->select('compliance_users.reminder_id');
                })
                ->orWhereIn('reminders.id', function($q) use ($user) {
                    $q->from('compliance_municipalities')
                      ->where('compliance_municipalities.score','=','0')
                      ->where('compliance_municipalities.location','=',$user->location)
                      ->where('reminders.due_date','>=',date('Y-m-d'))
                      ->select('compliance_municipalities.reminder_id');
                })
                ->where('reminders.deleted', '=', '0')
                ->orderBy('reminders.created_at','asc')
                ->get();

        return $notices;
    }

    public function location(){
        $loc = $this->location;
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
