<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\User;
use App\Admin;
use App\Document;
use App\Reminder; 
use App\ComplianceMunicipality;
use App\ComplianceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
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
        if(Auth::user()->active == '0'){
            return redirect('/edit_profile');
        }
        return view('form.registration');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'The officer\'s name is required',
            'username.required' => 'The officer\'s username is required',
            'role.required' => 'The officer\'s account type is required',
            'role.in' => 'The officer\'s account type must only be from the given values',
            'municipality.required' => 'The officer must have a municipality',
            'municipality.in' => 'The officer\'s municipality must only be from the given values',
            'password.min' => 'Password must be at least :min characters long',
            'password.regex' => 'Password must be at least 8 characters long with alphanumeric characters only',
        ];

        $validation = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8|regex:/^[A-Za-z\d]{8,}$/',
            'role' => 'required|in:1,2,3,4,5',
            'municipality' => 'required|in:0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17',
        ], $messages);

        if($validation->fails()){
            return redirect('/user/create')
                    ->withErrors($validation)
                    ->withInput();
        }

        $request->name = filter_var($request->name, FILTER_SANITIZE_STRING);
        $request->username = filter_var($request->username, FILTER_SANITIZE_STRING);
        $request->email = filter_var($request->email, FILTER_SANITIZE_STRING);

        $user = new User;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->profile_picture = "images/default-profile-image.jpg";
        $user->location = $request->municipality;
        $user->active = 0;
        if($request->role != 1){
            $user->role = 0;
            $user->save();
            $admin = new Admin;
            $admin->user_id = $user->id;
            $admin->role = (int) $request->role - 2;
            $admin->save();
        }else{
            $user->role = 1;
            $user->save();
        }

        $request->session()->flash('alert-success', 'You have successfully added an account for '.$user->name);
        return redirect('/user/create');
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
    public function update(Request $request)
    {

        $id = $request->id;
        $user = User::findOrFail($id);

        //
        //CHECK THE VALIDATION AGEEEEN!
        //
        $validation = Validator::make( $request->all(), [
            'password' => 'hash:'.$user->password,
            'new_password' => 'required_with:conf_password|different:password|confirmed',
            'conf_password' => 'same:new_password|confirmed'
        ]);

        if (Hash::check($request->password, $user->password)) {
            //dd("updated");
            $user->fill([
                    'password' => Hash::make($request->new_password),
                    'active' => '1',
                ])->save();

            return redirect('/home');

        } else {
            //dd("error");
            $request->session()->flash('error','Password does not match');
            return redirect()->back();
        }
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

    public function changePass(){
        return view('user.edit_profile');
    }

    public function upload(Request $data){
        $path = 'images'; //upload path
        $extension = $data->file('image')->getClientOriginalExtension(); //gets image extension
        $fileName = rand(0, 99999).'_'.rand(0, 99999); //renaming the image
        // $fileName = $data->file('image')->getClientOriginalName();
        $fileName = $fileName.".".$extension;

        $msg = [
          'image.required' => 'Image size limited to 1 MB',
        ];
        $picValidator = Validator::make($data->all(), [
            'image' => 'required|mimes:jpeg,jpg,png,JPG,JPEG,PNG|max:2048',
        ], $msg);

        $user = User::findOrFail($data->id);

        // different file format
        if ($picValidator->fails()) {
            $user->profile_picture = "images/default-profile-image.jpg";
            return redirect('/profile')
                    ->withErrors($picValidator);
        }

        // if picture does not exist
        if (!file_exists(public_path('images'.'/'.$user->profile_picture)) || $user->profile_picture == "images/default-profile-image.jpg") {
                $user->profile_picture = $path."/".$fileName;
                $user->update();
                $data->file('image')->move($path, $fileName);
                $data->session()->flash('alert-success', 'Your profile picture has been successfully uploaded.');
        }

        else {

            unlink(public_path($user->profile_picture));
            $user->profile_picture = $path."/".$fileName;
            $user->update();
            $data->file('image')->move($path, $fileName);
            $data->session()->flash('alert-success', 'Your profile picture has been successfully uploaded.');

        }
            return redirect('/profile');

    }

    public function viewProfile(){

        if(Auth::user()->active == '0'){
            return redirect('/edit_profile');
        } else {

            $id = Auth::user()->id;
            $user = User::findOrFail($id);
            $location = $user->location;
            
            //
            // COUNT THE OWNED FILES
            //
            $owned = Document::where('user_id',$id)
                    ->where('deleted','0')
                    ->count();

            //
            // COUNT THE SHARED FILES
            //
            $shared = Document::where('user_id',$id)
                    ->where('deleted','0')
                    ->where('public','1')
                    ->count();

            //
            // COUNT THE SCORE BASE ON THE REMINDERS COMPLIED
            //

            //
            // will fetch only those reminders which its due_date is within the current year (for annual score reset)
            //
            $individual = Reminder::join('compliance_users','reminders.id','=','compliance_users.reminder_id')
                    ->whereYear('reminders.due_date','=',date('Y'))
                    ->where('compliance_users.date_complied','!=',null)
                    ->where('compliance_users.user_id','=',$id)
                    ->sum('compliance_users.score');

            //
            // will fetch only those reminders which its due_date is within the current year (for annual score reset)
            //
            $municipal = Reminder::join('compliance_municipalities','reminders.id','=','compliance_municipalities.reminder_id')
                    ->whereYear('reminders.due_date','=',date('Y'))
                    ->where('compliance_municipalities.date_complied','!=',null)
                    ->where('compliance_municipalities.location','=',$location)
                    ->sum('compliance_municipalities.score');
 
            $points = $individual + $municipal;

            //
            // FETCH ALL THE REMINDERS COMPLIED
            //

            //
            // will fetch only those reminders which its due_date is within the current year (for annual score reset)
            //
            $idv = ComplianceUser::join('reminders','reminders.id','=','compliance_users.reminder_id')
                    ->whereYear('reminders.due_date','=',date('Y'))
                    ->where('compliance_users.date_complied','!=',null)
                    ->where('compliance_users.accepted_by','!=',null)
                    ->where('compliance_users.user_id','=',$id)
                    ->where('compliance_users.score','>','0')
                    ->get();

            //
            // will fetch only those reminders which its due_date is within the current year (for annual score reset)
            //
            $mun = ComplianceMunicipality::join('reminders','reminders.id','=','compliance_municipalities.reminder_id')
                    ->whereYear('reminders.due_date','=',date('Y'))
                    ->where('compliance_municipalities.date_complied','!=',null)
                    ->where('compliance_municipalities.accepted_by','!=',null)
                    ->where('compliance_municipalities.location','=',$location)
                    ->where('compliance_municipalities.score','>','0')
                    ->get();

            return view('user.profile', compact('owned','shared','points','idv','mun'));
        }
    }

    public function retrieveUsers(){
        $input = Input::get('name');

        $users = User::orderBy('name', 'asc')
                    ->get();

        return view('password_reset', compact('users'));
    }

    public function resetPassword(Request $request){
        $admin = Auth::user()->id;
        $user = User::findOrFail($admin);
        $password = Input::get('confirm_pass');

        // echo($request);

        $validation = Validator::make( $request->all(), [
                    'confirm_pass' => 'hash:'.$user->password
        ]);

        if (Hash::check($request->confirm_pass, $user->password)) {
            $resetUser = User::findOrFail($request->user_id);

            $resetUser->update([
                'active'=> 0,
                'password'=>bcrypt('12345678')
            ]);

             $request->session()->flash('alert-success', 'Password reset successfull.');

        }
        else{
 
            return redirect('/password_reset')
                    ->withErrors($validation);
            
        }

            return redirect('/password_reset');

    }

}
