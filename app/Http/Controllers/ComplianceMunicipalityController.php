<?php 
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Collection;
use Illuminate\Routing\Redirector;
use App\Document; 
use App\User;  
use App\Admin;
use App\Reminder;
use App\ComplianceMunicipality;
use App\ComplianceUser;
use DateTime;
use Session;
use Auth;


class ComplianceMunicipalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkScores(){

        $count = Reminder::count();

        if($count == 0){

            $municipal_ranks = null;
            $alluser_ranks = null;
            $overall_ranks = null;  

        } else {

            //
            // FOR MUNICIPALITY
            //
            $comply = new ComplianceMunicipality;
            $location = new ComplianceMunicipality;

            $user_ranks = array();
            for($loc=1; $loc<=17; $loc++){
                $sum_scores = 0;
                // $qs = ComplianceMunicipality::where('location', $loc)
                //     ->get();

                //
                // will fetch only those reminders which its due_date is within the current year (for annual score reset)
                //
                $qs = Reminder::join('compliance_municipalities','reminders.id','=','compliance_municipalities.reminder_id')
                    ->whereYear('reminders.due_date','=',date('Y'))
                    ->where('compliance_municipalities.location',$loc)
                    ->get();

                //
                // SUMMATION OF SCORES
                //
                if(count($qs) > 0) {
                    foreach ($qs as $q) {
                        $sum_scores += $q->score;
                        $comply = $sum_scores;
                    }

                    $user_ranks[] = $comply;
                } else {
                    $user_ranks[] = 0;
                }
                
            }

            //
            // SAVING IT INTO AN ARRAY
            //
            $municipal_ranks = collect();
            for ($x=0; $x < count($user_ranks) ; $x++) {
                $municipal_ranks->push(array('name' => $location->location($x+1), 'score' => $user_ranks[$x]));
            }

            // For Printing only
            // to check if its really working
            // for ($i = 0 ; $i < count($municipal_ranks) ; $i++) {
                // echo($municipal_ranks[0][0][0]);
            // }

            //
            // FOR USERS & OVERALL
            //
            $users = User::count();
            $users_ranks = array();
            $alluser_ranks = collect();
            $overall_ranks = collect();
            $complyUser = new ComplianceUser;
            for($user = 1 ; $user <= $users ; $user++){
                $idv_user = User::findOrFail($user);
                if($idv_user->role == '1'){
                    $sum_scores = 0;
                    $overall_score = 0;
                    //$qs = ComplianceUser::where('user_id', $user)
                    //    ->get();

                    //
                    // will fetch only those reminders which its due_date is within the current year (for annual score reset)
                    //
                    $qs = Reminder::join('compliance_users','reminders.id','=','compliance_users.reminder_id')
                        ->whereYear('reminders.due_date','=',date('Y'))
                        ->where('compliance_users.user_id',$user)
                        ->get();

                    //
                    // SUMMATION OF SCORES
                    //
                    if(count($qs) > 0) {
                        foreach ($qs as $q) {
                            $sum_scores += $q->score;
                            $complyUser = $sum_scores;
                        }
                    } else {
                        $complyUser = 0;
                    }

                    $users_ranks[] = $complyUser;

                    if($user_ranks[$idv_user->location - 1] == null){
                        $overall_score = $complyUser;
                    } else {
                        $overall_score = $complyUser + $user_ranks[$idv_user->location - 1];
                    }

                    //
                    // SAVING THEM INTO THEIR RESPECTIVE ARRAYS
                    //
                    $alluser_ranks->push(array('name' => $idv_user->name, 'profile_picture' => $idv_user->profile_picture, 'score' => $complyUser));
                    $overall_ranks->push(array('name' => $idv_user->name, 'profile_picture' => $idv_user->profile_picture, 'score' => $overall_score));
                }
            }

            //
            // SORTING THE SCORES
            //
            $municipal_ranks = $municipal_ranks->sortByDesc('score');
            $alluser_ranks = $alluser_ranks->sortByDesc('score');
            $overall_ranks = $overall_ranks->sortByDesc('score');

        }

        return view('leaderboard', compact('municipal_ranks','alluser_ranks','overall_ranks'));
    }

    public function complianceAction($id, Request $request) {

        $admin = Auth::user()->id;
        $user = User::findOrFail($admin);

        $reminder = Reminder::findOrFail($id);
        $deadline = new DateTime($reminder->due_date);

        $compliant = Input::get('compliants');

        $accept = Input::get('accept');
        $reject = Input::get('reject');

        $pass = Input::get('password');

        //dd($compliant);
        if(!(empty($compliant))){

            if($accept == 'accept' && $reject == null){
                
                //
                // if compliants are going to be accepted
                //

                if($reminder->compliancesMunicipality()){
                    
                    //
                    // The Reminder is per municipal compliance
                    //

                    foreach($compliant as $com){

                        $com_id = ComplianceMunicipality::where('location',$com)
                                ->where('reminder_id',$reminder->id)
                                ->pluck('id');

                        $compliance = ComplianceMunicipality::findOrFail($com_id);

                        if($compliance[0] != null){
                            $complied = new DateTime($compliance[0]->date_complied);
                            $created = new DateTime($compliance[0]->created_at);     
                        } else {
                            $complied = new DateTime($compliance->date_complied);
                            $created = new DateTime($compliance->created_at);
                        }
                        
                        $length = $deadline->diff($complied);
                        $interval = $deadline->diff($created);
 
                        if($interval->days >= 6) {

                            if($length->days >=  10 AND $complied > $deadline){
                                $score = 1;
                            } elseif($length->days >= 4 AND $length->days < 10 AND $complied > $deadline){
                                $score = 2;
                            } elseif($length->days >= 0 AND $length->days < 4 AND $complied >= $deadline){
                                $score = 3;
                            } elseif($length->days >= 1 AND $length->days < 5 AND $complied < $deadline){
                                $score = 4;             
                            } else {
                                $score = 5;
                            }

                        } else {

                            if($length->days >=  10 AND $complied > $deadline){
                                $score = 1;
                            } elseif($length->days >= 4 AND $length->days < 10 AND $complied > $deadline){
                                $score = 2;
                            } elseif($length->days > 0 AND $length->days < 4 AND $complied > $deadline){
                                $score = 3;
                            } elseif($length->days == 0 AND $complied == $deadline){
                                $score = 4;             
                            } else {
                                $score = 5;
                            }

                        }
                        

                        if($compliance[0] != null){
                            $compliance[0]->update([
                                'score' => $score,
                                'accepted_by' => $admin
                            ]);
                        } else {
                            $compliance->update([
                                'score' => $score,
                                'accepted_by' => $admin
                            ]);
                        }

                        
                        
                    }

                } else {

                    //
                    // The Reminder is per individual user compliance
                    //

                    foreach($compliant as $com){

                        $com_id = ComplianceUser::where('user_id',$com)
                                ->where('reminder_id',$reminder->id)
                                ->pluck('id');

                        $compliance = ComplianceUser::findOrFail($com_id);

                        if($compliance[0] != null){
                            $complied = new DateTime($compliance[0]->date_complied);
                            $created = new DateTime($compliance[0]->created_at);     
                        } else {
                            $complied = new DateTime($compliance->date_complied);
                            $created = new DateTime($compliance->created_at);
                        }

                        $length = $deadline->diff($complied);
                        $interval = $deadline->diff($created);
 
                        if($interval->days >= 6) {

                            if($length->days >=  10 AND $complied > $deadline){
                                $score = 1;
                            } elseif($length->days >= 4 AND $length->days < 10 AND $complied > $deadline){
                                $score = 2;
                            } elseif($length->days >= 0 AND $length->days < 4 AND $complied >= $deadline){
                                $score = 3;
                            } elseif($length->days >= 1 AND $length->days < 5 AND $complied < $deadline){
                                $score = 4;             
                            } else {
                                $score = 5;
                            }

                        } else {

                            if($length->days >=  10 AND $complied > $deadline){
                                $score = 1;
                            } elseif($length->days >= 4 AND $length->days < 10 AND $complied > $deadline){
                                $score = 2;
                            } elseif($length->days > 0 AND $length->days < 4 AND $complied > $deadline){
                                $score = 3;
                            } elseif($length->days == 0 AND $complied == $deadline){
                                $score = 4;             
                            } else {
                                $score = 5;
                            }

                        }
                        
                        if($compliance[0] != null){
                            $compliance[0]->update([
                                'score' => $score,
                                'accepted_by' => $admin
                            ]);
                        } else {
                            $compliance->update([
                                'score' => $score,
                                'accepted_by' => $admin
                            ]);
                        }
                    }

                }
                
            } else {

                //
                // if compliants are going to be rejected
                //

                //dd($request->password);

                $validation = Validator::make( $request->all(), [
                    'password' => 'hash:'.$user->password
                ]);

                if (Hash::check($request->password, $user->password)) {

                    if($reminder->compliancesMunicipality()){
                    
                        //
                        // The Reminder is per municipal compliance
                        //

                        foreach($compliant as $com){
                            $com_id = ComplianceMunicipality::where('location',$com)
                                    ->where('reminder_id',$reminder->id)
                                    ->pluck('id');

                            $compliance = ComplianceMunicipality::findOrFail($com_id);

                            if($compliance[0] != null){
                                $compliance[0]->update([
                                    'date_complied' => null,
                                    'rejected' => '1',
                                    'rejected_by' => $admin
                                ]);
                            } else {
                                $compliance->update([
                                    'date_complied' => null,
                                    'rejected' => '1',
                                    'rejected_by' => $admin
                                ]);
                            }                            
                        }

                    } else {

                        //
                        // The Reminder is per individual user compliance
                        //

                        foreach($compliant as $com){

                            $com_id = ComplianceUser::where('user_id',$com)
                                    ->where('reminder_id',$reminder->id)
                                    ->pluck('id');

                            $compliance = ComplianceUser::findOrFail($com_id);

                            if($compliance[0] != null){
                                $compliance[0]->update([
                                    'date_complied' => null,
                                    'rejected' => '1',
                                    'rejected_by' => $admin
                                ]);
                            } else {
                                $compliance->update([
                                    'date_complied' => null,
                                    'rejected' => '1',
                                    'rejected_by' => $admin
                                ]);
                            }
                        }


                    }

                } else {

                    //dd("error");
                    //$request->session()->flash('error','Password does not match');
                    Session::flash('alert-danger', 'Inputed password does not match on your current password. Please try rejecting again.');
                    return back();
                }
                
            }
        } else {

            Session::flash('alert-danger', "You haven't any compliants. Please try again.");
            return back();
        }
 
        return redirect('/compliances/'.$id);
    }

}
