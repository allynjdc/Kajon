<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Redirector;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\Document;
use App\User;
use App\Admin;
use App\Reminder;
use App\ComplianceMunicipality;
use App\ComplianceUser;
use Auth;

class RemindersController extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Auth::user()->active == '0') {
            return redirect('/edit_profile');
        } else {
          
            $id = Auth::User()->id;
            $user = User::findOrFail($id);
            $location = $user->location;
            $today = date('Y-m-d');
            $reminders = collect();

            if(Auth::user()->role == '0') {
                
                //
                // ONLY THE LIST OF REMINDERS THE ADMIN CAN VIEW
                //
                
                $pending = Reminder::where('reminders.deleted','=','0')
                        ->paginate(10);
                        //->get();
                $late = null;
                $submitted = null;

                return view('user.reminders',compact('pending','late','submitted', 'today'));

            } else {

                $pending = Reminder::where('reminders.active','=','0')
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
                        ->where('reminders.active','=','0')
                        ->orderBy('reminders.created_at','asc')
                        ->get();
                        //->paginate(10);
                // $pending = Reminder::join('compliance_municipalities','compliance_municipalities.reminder_id','=','reminders.id')
                //            ->join('compliance_users','compliance_users.reminder_id','=','reminders.id')
                //            ->where('compliance_users.score','0')
                //            ->where('compliance_municipalities.score','0')
                //            ->where('reminders.active','0')
                //            ->where('reminders.deleted','0')
                //            ->where('reminders.due_date','>',date('Y-m-d'))
                //            ->get();


                //$late = null;
                $late = Reminder::where('reminders.active','=','0')
                        ->whereIn('reminders.id', function($q) use ($user) {
                            $q->from('compliance_users')
                              ->where('compliance_users.user_id','=',$user->id)
                              ->where('compliance_users.score','=','0')
                              ->where('reminders.due_date','<',date('Y-m-d'))
                              ->select('compliance_users.reminder_id');
                        })
                        ->orWhereIn('reminders.id', function($q) use ($user) {
                            $q->from('compliance_municipalities')
                              ->where('compliance_municipalities.location','=',$user->location)
                              ->where('compliance_municipalities.score','=','0')
                              ->where('reminders.due_date','<',date('Y-m-d'))
                              ->select('compliance_municipalities.reminder_id');
                        })
                        ->where('reminders.deleted', '=', '0')
                        ->where('reminders.active','=','0')
                        ->orderBy('reminders.created_at','asc')
                        ->get();
                        //->paginate(10);
                // $late = Reminder::join('compliance_municipalities','compliance_municipalities.reminder_id','=','reminders.id')
                //            ->join('compliance_users','compliance_users.reminder_id','=','reminders.id')
                //            ->where('compliance_users.score','0')
                //            ->where('compliance_municipalities.score','0')
                //            ->where('reminders.active','0')
                //            ->where('reminders.deleted','0')
                //            ->where('reminders.due_date','<',date('Y-m-d'))
                //            ->select('reminders.id','reminders.due_date','reminders.user_id','reminders.created_at','reminders.title','reminders.description');

                $submitted = Reminder::where('reminders.active','=','0')
                        ->whereIn('reminders.id', function($q) use ($user) {
                            $q->from('compliance_users')
                              ->where('compliance_users.user_id','=',$user->id)
                              ->where('compliance_users.score','>','0')
                              ->select('compliance_users.reminder_id');
                        })
                        ->orWhereIn('reminders.id', function($q) use ($user) {
                            $q->from('compliance_municipalities')
                              ->where('compliance_municipalities.location','=',$user->location)
                              ->where('compliance_municipalities.score','>','0')
                              ->select('compliance_municipalities.reminder_id');
                        })
                        ->where('reminders.deleted', '=', '0')
                        ->where('reminders.active','=','0')
                        ->orderBy('reminders.created_at','asc')
                        ->get();
                        //->paginate(10);
            
            // $reminders = $pending;
            // $reminders->push($late);
            // $reminders->push($submitted);
            // $paginator = new Paginator($reminders->forPage($page,$per_page),$reminders->count(),$per_page,$page);
            //$reminders = Paginator::make($reminders, count($reminders), 10);
            //$reminders->paginate(10);

            //echo($paginator);
            
            return view('user.reminders_all',compact('pending','late','submitted', 'today'));
            
            }            
        }
    }


    public function checkUncomplied(){
        $id = Auth::User()->id;
        $user = User::findOrFail($id);
        $location = $user->location;

        if($user->role == '1') {
            // $pending = null;
            // $pending = Reminder::where('reminders.active','0')
            //         ->where('reminders.deleted','0')
            //         ->where('reminders.due_date','>',date('Y-m-d'))
            //         ->whereNotIn('reminders.id', function($q) use ($user) {
            //             $q->from('compliances')
            //               ->where('compliances.user_id','=',$user->id)
            //               ->orWhere('compliances.location','=',$user->location)
            //               ->select('compliances.reminder_id');
            //         })
            //         ->orderBy('reminders.due_date','asc')
            //         ->get();
            $pending = Reminder::where('reminders.active','0')
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
                    ->where('reminders.active','=','0')
                    ->orderBy('reminders.created_at','asc')
                    //->get();
                    ->paginate(10);


            $late = null;

            $submitted = null;

            //dd('checkUncomplied');
            return view('user.reminders',compact('pending','late','submitted'));

        } else {

            return redirect('/reminders');
        }
    }

    public function checkComplied(){
        $id = Auth::User()->id;
        $user = User::findOrFail($id);
        $location = $user->location;

        if($user->role == '1'){

            $pending = null;

            $late = null;

            // $submitted = null;
            // $submitted = Reminder::join('compliances','compliances.reminder_id','=','reminders.id')
            //         ->where('reminders.active','0')
            //         ->where('compliances.user_id',$id)
            //         ->orWhere('compliances.location',$location)
            //         ->orderBy('compliances.created_at','asc')
            //         ->get();
            $submitted = Reminder::where('reminders.active','0')
                    ->whereIn('reminders.id', function($q) use ($user) {
                        $q->from('compliance_users')
                          ->where('compliance_users.user_id','=',$user->id)
                          ->where('compliance_users.score','>','0')
                          ->select('compliance_users.reminder_id');
                    })
                    ->orWhereIn('reminders.id', function($q) use ($user) {
                        $q->from('compliance_municipalities')
                          ->where('compliance_municipalities.location','=',$user->location)
                          ->where('compliance_municipalities.score','>','0')
                          ->select('compliance_municipalities.reminder_id');
                    })
                    ->where('reminders.deleted', '=', '0')
                    ->where('reminders.active','=','0')
                    ->orderBy('reminders.created_at','asc')
                    //->get();
                    ->paginate(10);


            //dd('checkComplied');
            return view('user.reminders',compact('pending','late','submitted'));

        } else {

            return redirect('/reminders');
        }
    }

    public function checkOverdue(){
        $id = Auth::User()->id;
        $user = User::findOrFail($id);
        $location = $user->location;

        if($user->role == '1'){

            $pending = null;

            // $late = null;
            // $late = Reminder::where('reminders.active','0')
            //         ->where('reminders.deleted','0')
            //         ->where('reminders.due_date','<',date('Y-m-d'))
            //         ->whereNotIn('reminders.id', function($q) use ($user) {
            //             $q->from('compliances')
            //               ->where('compliances.user_id','=',$user->id)
            //               ->orWhere('compliances.location','=',$user->location)
            //               ->select('compliances.reminder_id');
            //         })
            //         ->orderBy('reminders.due_date','asc')
            //         ->get();
            $late = Reminder::where('reminders.active','0')
                    ->whereIn('reminders.id', function($q) use ($user) {
                        $q->from('compliance_users')
                          ->where('compliance_users.user_id','=',$user->id)
                          ->where('compliance_users.score','=','0')
                          ->where('reminders.due_date','<',date('Y-m-d'))
                          ->select('compliance_users.reminder_id');
                    })
                    ->orWhereIn('reminders.id', function($q) use ($user) {
                        $q->from('compliance_municipalities')
                          ->where('compliance_municipalities.location','=',$user->location)
                          ->where('compliance_municipalities.score','=','0')
                          ->where('reminders.due_date','<',date('Y-m-d'))
                          ->select('compliance_municipalities.reminder_id');
                    })
                    ->where('reminders.deleted', '=', '0')
                    ->where('reminders.active','=','0')
                    ->orderBy('reminders.created_at','asc')
                    //->get();
                    ->paginate(10);


            $submitted = null;

            //dd('checkOverdue');
            return view('user.reminders',compact('pending','late','submitted'));

        } else {

            return redirect('/reminders');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //dd('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Auth::User()->id;

        $reminder = new Reminder;
        $reminder->user_id = $id;
        $reminder->due_date = $request->due_date;
        $reminder->title = $request->reminder_title;
        $reminder->description = $request->reminder_description;
        $reminder->updated_by = $id;
        $reminder->municipality = ($request->reminder_target == "mun"? '1' : '0' );
        $reminder->active = '0';
        $reminder->deleted = '0';
        $reminder->save();

        $new_reminder = Reminder::where('title',$request->reminder_title)
                  ->select('municipality','id');

        //dd($reminder_id);

        // approved_by: current id sng currently logged in user 
        if($reminder->municipality == '1'){
          // location: 0 -> Admin, 1 - 17 -> Municipalities
          for($loc = 1 ; $loc <= 17 ; $loc++){
            $comply = new ComplianceMunicipality;
            $comply->location = $loc;
            //$comply->date_complied = null;
            $comply->reminder_id = $reminder->id;
            $comply->score = '0';
            $comply->rejected = '0';
            $comply->rejected_by = null;
            $comply->accepted_by = null;
            $comply->approved_by = '0';
            $comply->save();
          }
        } else {
          // (users) id: 1-5 -> admin, 6-39 -> users.. WHAT IF? 40 -> admin, 41-43 -> users, 44-46 -> admin
          //We need to count all the users from the table users, and query

          //$numUsers = User::select(count('id'));
          $numUsers = User::count();
          for($user = 1 ; $user <= $numUsers ; $user++){
            $idv_user = User::findOrFail($user);
            if($idv_user->role == '1'){
              $comply = new ComplianceUser;
              $comply->user_id = $idv_user->id;
              //$comply->date_complied = null;
              $comply->reminder_id = $reminder->id;
              $comply->score = '0';
              $comply->rejected = '0';
              $comply->rejected_by = null;
              $comply->accepted_by = null;
              $comply->approved_by = '0';
              $comply->save();
            }
          }
        }

        return redirect('/reminders');
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
        $reminder = Reminder::findOrFail($id);
        $reminder->update([
            "due_date" => $request->edit_due_date,
            "title" => $request->edit_reminder_title,
            "description" => $request->edit_reminder_description,
            "user_id" => Auth::user()->id,
            "updated_by" => 1,
          ]);
        $message = "Success!|You have successfully updated the notice!";
        $reminders = Reminder::where('reminders.deleted','=','0')
                ->orderBy('reminders.updated_at','desc')
                ->get();
        $reminder_users = array();
        foreach($reminders as $reminder){
          $reminder_users[] = array(
              'id' => $reminder->id,
              'user_id' => $reminder->user_id,
              'title' => $reminder->title,
              'due_date' => date('m-d-Y', strtotime($reminder->due_date)),
              'description' => $reminder->description,
              'municipality' => $reminder->municipality,
              'active' => $reminder->active,
              'deleted' => $reminder->deleted,
              'created_at' => Carbon::parse($reminder->created_at)->diffForHumans(),
              'updated_at' => Carbon::parse($reminder->updated_at)->diffForHumans(),
              'user_profile' => $reminder->user->profile_picture,
              'user_name' => $reminder->user->name
            );
        }
        return array($message, $reminder_users);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reminder = Reminder::findOrFail($id);
        
        if($reminder[0] != null){
            $reminder[0]->update([
              "deleted" => 1,
            ]);
        } else {
            $reminder->update([
              "deleted" => 1,
            ]);
        }

        $message = "Success!|You have successfully deleted the reminder!";
        $reminders = Reminder::where('reminders.deleted','=','0')
                //->orderBy('reminders.updated_at','desc')
                //->get();
                ->paginate(10);
        $reminder_users = array();
        foreach($reminders as $reminder){
          $reminder_users[] = array(
              'id' => $reminder->id,
              'user_id' => $reminder->user_id,
              'title' => $reminder->title,
              'due_date' => date('m-d-Y', strtotime($reminder->due_date)),
              'description' => $reminder->description,
              'municipality' => $reminder->municipality,
              'active' => $reminder->active,
              'deleted' => $reminder->deleted,
              'created_at' => Carbon::parse($reminder->created_at)->diffForHumans(),
              'updated_at' => Carbon::parse($reminder->updated_at)->diffForHumans(),
              'user_profile' => $reminder->user->profile_picture,
              'user_name' => $reminder->user->name
            );
        }
        return array($message, $reminder_users);
    }

    public function checkInactive($id){
      
      //dd("inactivate");
      $reminder = Reminder::findOrFail($id);

      if($reminder[0] != null){
          $reminder[0]->update([
            "active" => 1,
          ]);
      } else {
          $reminder->update([
            "active" => 1,
          ]);
      }

      return redirect('/reminders');
    }

    public function checkActive($id){
      
      //dd("activate");
      $reminder = Reminder::findOrFail($id);

      if($reminder[0] != null){
          $reminder[0]->update([
            "active" => 0,
          ]); 
      } else {
          $reminder->update([
            "active" => 0,
          ]);
      }

      return redirect('/reminders');
    }
}
