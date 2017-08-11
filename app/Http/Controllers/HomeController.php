<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Routing\Redirector;
use Carbon\Carbon;
use App\Document; 
use App\User;
use App\Admin;
use Auth;
use Session;
use Helper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if(Auth::user()->active == '0'){
            return redirect('/edit_profile');
        } else {
            $files = $this->filter();
            $allfiles = $this->all();
            $search = Input::get('search');
    
            return view('home', ['files' => $files, 'allfiles' => $allfiles, 'search' => $search, 'allfile_count' => count(Auth::user()->documents()->where('tag_id', '0')->where('deleted', '0')->get())]);
        }
    }

    public function arrayConvert($outputs){
        $output_array = array();
        foreach($outputs as $output){
            $output_array[] = array(
                'id' => $output->id,
                'user_id' => $output->user_id,
                'filename' => $output->filename,
                'filepath' => $output->filepath,
                'description' => $output->description,
                'deleted' => $output->deleted,
                'public' => $output->deleted,
                'shared_by_admin' => $output->shared_by_admin,
                'tag_id' => $output->tag_id,
                'updated_at' => $output->updated_at,
                'parsed_updated_at' => Carbon::parse($output->updated_at)->diffForHumans()
            );
        }
        return $output_array;
    }

    public function loadMore(Request $request){
        $output = '';
        $reqid = $request->id;
        $limit = 9;
        if($request->current == "date"){
            if(Auth::user()->isAdmin()){
                $count = Document::where('user_id',Auth::user()->id)
                    ->where('updated_at', '<=', $request->updated_at)
                    ->where('tag_id', '0')
                    ->whereNotIn('id', $request->file_list)
                    ->orderBy('updated_at', 'desc')
                    ->count();
                $outputs = Document::where('user_id',Auth::user()->id)
                    ->where('updated_at', '<=', $request->updated_at)
                    ->where('tag_id', '0')
                    ->whereNotIn('id', $request->file_list)
                    ->orderBy('updated_at', 'desc')
                    ->limit($limit)
                    ->get();
            }else{
                $count = Document::where('user_id',Auth::user()->id)
                    ->where('updated_at', '<=', $request->updated_at)
                    ->where('tag_id', '0')
                    ->where('deleted', '0')
                    ->whereNotIn('id', $request->file_list)
                    ->orderBy('updated_at', 'desc')
                    ->count();
                $outputs = Document::where('user_id',Auth::user()->id)
                    ->where('updated_at', '<=', $request->updated_at)
                    ->where('tag_id', '0')
                    ->where('deleted', '0')
                    ->whereNotIn('id', $request->file_list)
                    ->orderBy('updated_at', 'desc')
                    ->limit($limit)
                    ->get();
            }
        }else{
            if(Auth::user()->isAdmin()){
                $count = Document::where('user_id',Auth::user()->id)
                    ->where('tag_id', '0')
                    ->whereNotIn('id', $request->file_list)
                    ->orderBy('filename', 'asc')
                    ->count();
                $outputs = Document::where('user_id',Auth::user()->id)
                    ->where('tag_id', '0')
                    ->whereNotIn('id', $request->file_list)
                    ->orderBy('filename', 'asc')
                    ->limit($limit)
                    ->get();
            }else{
                $count = Document::where('user_id',Auth::user()->id)
                    ->where('tag_id', '0')
                    ->where('deleted', '0')
                    ->whereNotIn('id', $request->file_list)
                    ->orderBy('filename', 'asc')
                    ->count();
                $outputs = Document::where('user_id',Auth::user()->id)
                    ->where('tag_id', '0')
                    ->where('deleted', '0')
                    ->whereNotIn('id', $request->file_list)
                    ->orderBy('filename', 'asc')
                    ->limit($limit)
                    ->get();
            }
        }
        return array($this->arrayConvert($outputs), $count);
    }

    public function sortAllByDate(){
        $id = Auth::user()->id;
        $locId = Auth::user()->location;
        $search = Input::get('search');

        $owner_id = User::findOrFail($id)
            ->where('location', $locId)
            ->pluck('id');

         if (Auth::user()->isAdmin() AND in_array(Auth::user()->adminRole(),[0,3])) {
                if (empty($search)) {

                    $files = Document::where('user_id',$id)
                                ->orderBy('updated_at', 'desc')
                                ->get();

                    $allfiles = Document::where('user_id','!=',$id)
                                ->orderBy('updated_at', 'desc')
                                ->get();

                    $owners = User::findOrFail($id)
                        ->get();
                }

            }

            // User, File Admin and Admin

            else{

                if (empty($search)) {

                    $files = Document::where('user_id',$id)
                                ->orderBy('updated_at', 'desc')
                                ->get();

                    $allfiles = Document::where('user_id','!=',$id)
                                ->orderBy('updated_at', 'desc')
                                ->get();
                    
                    $owners = User::findOrFail($id)
                        ->where('location', $locId)
                        ->get();
                }
            }
            return array($allfiles, $owners, $files);
        }


    public function sortAllByName(){
        $id = Auth::user()->id;
        $locId = Auth::user()->location;
        // $search = Input::get('search');
        $search = Session::get('search');

        $owner_id = User::findOrFail($id)
            ->where('location', $locId)
            ->pluck('id');

         if (Auth::user()->isAdmin() AND in_array(Auth::user()->adminRole(),[0,3])) {
                if (empty($search)) {

                    $files = Document::where('user_id',$id)
                                ->orderBy('filename', 'asc')
                                ->get();

                    $allfiles = Document::where('user_id','!=',$id)
                                ->orderBy('filename', 'asc')
                                ->get();

                    $owners = User::findOrFail($id)
                        ->get();
                }

            // // ============ Searching ============== 
                else{


                    $files = Document::whereIn('user_id', $owner_id)
                            ->where('user_id', $id)
                            ->where('filename','like', '%'.$search.'%')
                            ->orWhere('description','like', '%'.$search.'%')
                            ->orderBy('filename', 'asc')
                            ->get();

                    $allfiles = Document::whereIn('user_id', $owner_id)
                            ->where('user_id', '!=', $id)
                            ->where('filename','like', '%'.$search.'%')
                            ->orWhere('description','like', '%'.$search.'%')
                            ->orderBy('filename', 'asc')
                            ->get();

                    $owners = User::findOrFail($id)
                        ->get();

                }
                    
            }

            // User, File Admin and Admin

            else{

                if (empty($search)) {

                    $files = Document::where('user_id',$id)
                                ->orderBy('filename', 'asc')
                                ->get();

                    $allfiles = Document::where('user_id','!=',$id)
                                ->orderBy('filename', 'asc')
                                ->get();
                    
                    $owners = User::findOrFail($id)
                        ->where('location', $locId)
                        ->get();
                }

            // ============ Searching ============== 
                // else{

                //     $allfiles = Document::whereIn('user_id', $owner_id)
                //             ->where('deleted','0')
                //             ->where('filename','like', '%'.$search.'%')
                //             ->orWhere('description','like', '%'.$search.'%')
                //             ->orderBy('filename', 'asc')
                //             ->get();

                //      $owners = User::findOrFail($id)
                //         ->where('location', $locId)
                //         ->get();

                // }    

            } 
            return array($allfiles, $owners, $files);
        }

    public function sortByName(){
        $id = Auth::user()->id;
        $search = Input::get('search');
        $limit = 9;
        if (empty($search)) {
            $files = Document::where('user_id',$id)
                                ->orderBy('filename', 'asc')
                                ->limit(7)
                                ->get();
            $allfiles = Document::where('user_id',$id)
                                ->orderBy('filename', 'asc')
                                ->limit($limit)
                                ->get();
        }
        else{
            // System Admin and Developer
            if (Auth::user()->isAdmin() AND in_array(Auth::user()->adminRole(),[0,3])) {
                 $files = Document::where('filename','like', '%'.$search.'%')
                            ->where('user_id',$id)
                            ->orderBy('filename', 'asc')
                            ->limit(7)
                            ->get();
                 $allfiles = Document::where('filename','like', '%'.$search.'%')
                                ->where('user_id', $id)
                                ->orderBy('filename', 'asc')
                                ->limit($limit)
                                ->get();
            }
            // User, File Admin and Admin
            else{
                $files = Document::where('filename','like', '%'.$search.'%')
                        ->where('deleted','0')
                        ->where('user_id', $id)
                        ->orderBy('filename', 'asc')
                        ->limit(7)
                        ->get();
                $allfiles = Document::where('filename','like', '%'.$search.'%')
                    ->where('user_id', $id)
                    ->where('deleted','0')
                    ->orderBy('filename', 'asc')
                    // ->get();
                    ->limit($limit)
                    ->get();
            }
        }
        return array($this->arrayConvert($files), $this->arrayConvert($allfiles), $search);
    }

    public function sortByDate(){
        $id = Auth::user()->id;
        $search = Input::get('search');
        $limit = 9;
        if (empty($search)) {
            $files = Document::where('user_id',$id)
                                ->orderBy('updated_at', 'desc')
                                ->limit(7)
                                ->get();
            $allfiles = Document::where('user_id',$id)
                                ->orderBy('updated_at', 'desc')
                                ->limit($limit)
                                ->get();
        }
        else{
            // System Admin and Developer
            if (Auth::user()->isAdmin() AND in_array(Auth::user()->adminRole(),[0,3])) {
                 $files = Document::where('filename','like', '%'.$search.'%')
                            ->where('user_id',$id)
                            ->orderBy('updated_at', 'desc')
                            ->limit(7)
                            ->get();
                 $allfiles = Document::where('filename','like', '%'.$search.'%')
                                ->where('user_id', $id)
                                ->orderBy('updated_at', 'desc')
                                ->limit($limit)
                                -> get();
            }
            // User, File Admin and Admin
            else{
                $files = Document::where('filename','like', '%'.$search.'%')
                        ->where('deleted','0')
                        ->where('user_id', $id)
                        ->orderBy('updated_at', 'desc')
                        ->limit(7)
                        ->get();
                $allfiles = Document::where('filename','like', '%'.$search.'%')
                    ->where('user_id', $id)
                    ->where('deleted','0')
                    ->orderBy('updated_at', 'desc')
                    ->limit($limit)
                    ->get();
            }
        }
        return array($this->arrayConvert($files), $this->arrayConvert($allfiles), $search);
    }

    public function adminQuery(){
        $id = Auth::user()->id;
        $locId = Auth::user()->location;

        $owner_id = User::findOrFail($id)
            ->where('location', $locId)
            ->pluck('id');


        $allfiles = Document::where('tag_id', '0')
                            ->orderBy('updated_at', 'desc')
                            ->get();

        return $allfiles;
    }

    public function userQuery(){
        $id = Auth::user()->id;
        $locId = Auth::user()->location;

        $owner_id = User::findOrFail($id)
            ->where('location', $locId)
            ->pluck('id');

        $allfiles = Document::whereIn('user_id', $owner_id)
                                ->where('tag_id', '0')
                                ->where('deleted','0')
                                ->orderBy('updated_at', 'desc')
                                ->get();
        return $allfiles;
    }

    public function municipalityFiles(){
        $id = Auth::user()->id;
        $locId = Auth::user()->location;
        $search = Input::get('search');

        $owner_id = User::findOrFail($id)
            ->where('location', $locId)
            ->pluck('id');

            // echo(app('request')->create(URL::previous())->getName());
        
        // System Admin and Developers 
        if (Auth::user()->isAdmin() AND in_array(Auth::user()->adminRole(),[0,3])) {
            if (empty($search)) {
                $allfiles = $this->adminQuery();

                $owners = User::findOrFail($id)
                    ->get();
            }

        // ============ Searching ============== 
            else{

                $allfiles = Document::whereIn('user_id', $owner_id)
                        ->where('filename','like', '%'.$search.'%')
                        ->orWhere('description','like', '%'.$search.'%')
                        ->where('tag_id', '0')
                        ->orderBy('updated_at', 'desc')
                        ->get();

                $owners = User::findOrFail($id)
                    ->get();
                // return redirect('/home/allfiles');
                // return redirect('/home?search='.$search);

            }
                
        }

        // USER, File Admin and Administrator
       else{

            if (empty($search)) {

                $allfiles = $this->userQuery();
                
                $owners = User::findOrFail($id)
                    ->where('location', $locId)
                    ->get();
            }

        // ============ Temporary ============== 
            else{
                // return redirect('/home?search='.$search);
                // return redirect('/home/allfiles');
                $allfiles = Document::whereIn('user_id', $owner_id)
                        ->where('deleted','0')
                        ->where('filename','like', '%'.$search.'%')
                        ->where('tag_id', '0')
                        ->orWhere('description','like', '%'.$search.'%')
                        ->orderBy('updated_at', 'desc')
                        ->get();

                 $owners = User::findOrFail($id)
                    ->where('location', $locId)
                    ->get();

            }    

        } 

        return view('allfiles', ['allfiles' => $allfiles, 'owners' => $owners]);

    }

    // displays all of user's own files
    public function filter(){

        if(Auth::user()->active == '0'){
            return redirect('/edit_profile');
        } else {
            $search = Input::get('search');
            $id = Auth::user()->id;
            $user_id = User::findOrFail($id)
                    ->where('id', $id)
                    ->pluck('id');


            if (Auth::user()->isAdmin() AND in_array(Auth::user()->adminRole(),[0,3])) {

                // Admin and Developers can view all files (deleted and not deleted)
                if (!empty($search)) {

                    $files = Document::where('filename','like', '%'.$search.'%')
                            ->where('user_id',$id)
                            ->orderBy('updated_at', 'desc')
                            ->paginate(7);
                }

                else{

                    $files = Document::where('user_id',$id)
                            ->orderBy('updated_at', 'desc')
                            ->paginate(7);
                }

                

            }

            // User, Administratior and File Admin can only view files that are not deleted
            else{

                if (!empty($search)) {

                    $files = Document::where('filename','like', '%'.$search.'%')
                            ->where('deleted','0')
                            ->where('user_id', $id)
                            ->orderBy('updated_at', 'desc')
                            ->paginate(7);
                }

                else{

                    $files = Document::where('user_id',$id)
                            ->where('deleted','0')
                            ->orderBy('updated_at', 'desc')
                            ->paginate(7);
                }
            }

            return $files;
        }
    }

    // displays all of the files within the municipality of user
    public function all(){
        $search = Input::get('search');
        $id = Auth::user()->id;
        $locId = Auth::user()->location;

        $user_id = User::findOrFail($id)
                ->where('id', $id)
                ->pluck('id');

        $owner = User::findOrFail($id)
                    ->where('location', $locId)
                    ->pluck('id');
        // ADMIN
        if (Auth::user()->isAdmin()) {
           // System Administrator and Developers
           if (in_array(Auth::user()->adminRole(),[0,3])) {
                if (!empty($search)) {
                    $allfiles = Document::where('filename','like', '%'.$search.'%')
                                ->where('user_id', '!=', $id)
                                ->orderBy('updated_at', 'desc')
                                // -> get();
                                ->limit(9)
                                ->get();
                }
                else{
                    $allfiles = Document::where('user_id',$id)
                            ->where('tag_id', '0')
                            ->orderBy('updated_at', 'desc')
                            // ->get();
                            ->limit(9)
                            ->get();


                }
            }

           // File Admin and Administrator
           else{

                if (!empty($search)) {
                    $user_id = User::where('role', '1')
                                ->pluck('id');

                    $allfiles = Document::where('user_id', $user_id)
                                ->where('filename','like', '%'.$search.'%')
                                ->orderBy('updated_at', 'desc')
                                // -> get();
                                ->limit(9)
                                ->get();
                }
                else{
                    $allfiles = Document::where('user_id', $user_id)
                                ->orderBy('updated_at', 'desc')
                                // -> get();
                                ->limit(9)
                                ->get();
                }
            }
        }

        // USER 
        else{
            if (!empty($search)) {

                // add query where all documents are selected from user's location
                 $allfiles = Document::where('filename','like', '%'.$search.'%')
                        ->where('user_id', '!=', $id)
                        ->where('deleted','0')
                        ->orderBy('updated_at', 'desc')
                        // ->get();
                        ->limit(9)
                        ->get();
            }
            else{
                $allfiles = Document::where('user_id',$id)
                        ->where('tag_id', '0')
                        ->where('deleted','0')
                        ->orderBy('updated_at', 'desc')
                        // ->get();
                        ->limit(9)
                        ->get();
            }
        }

        return $allfiles;

    }

    public function sharedFiles() {

        $id = Auth::user()->id;

        $owners = User::whereIn('users.id', function($q) {
                        $q->from('documents')
                          ->where('documents.shared_by_admin','=','1')
                          ->select('documents.user_id');
                    })
                ->get();

        $sharedowner = Document::where('shared_by_admin','1')
                ->where('deleted','0')
                ->where('user_id',$id)
                ->orderBy('updated_at','desc')
                ->get();

        $shared = Document::where('shared_by_admin','1')
                ->where('deleted','0')
                ->orderBy('updated_at','desc')
                ->get();

        return view('sharedfiles', compact('shared','owners','sharedowner'));
    }

    public function publicFiles() {

        $id = Auth::user()->id;

        $owners = User::whereIn('users.id', function($q) {
                        $q->from('documents')
                          ->where('documents.public','=','1')
                          ->select('documents.user_id');
                    })
                ->get();

        $sharedowner = Document::where('public','=','1')
                ->where('deleted','0')
                ->where('user_id',$id)
                ->orderBy('updated_at','desc')
                ->get();

        $shared = Document::where('public','=','1')
                ->where('deleted','0')
                ->orderBy('updated_at','desc')
                ->get();

        return view('sharedfiles', compact('shared','owners','sharedowner'));
    }

}
