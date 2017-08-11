<?php

namespace App\Http\Controllers;

use Auth;
use App\Document;
use App\Tag;
use Carbon\Carbon;
// use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Session;

class DocumentController extends Controller
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
        $message = "";
        if($request->file('filename')->isValid() && !(empty($request->description))){
            $filename = $request->file('filename')->getClientOriginalName();
            $document = new Document;
            $document->user_id = Auth::user()->id;
            $document->filename = $filename;
            $document->filepath = "/storage/app/".$filename;
            $document->description = trim($request->description);
            $document->deleted = 0;
            $document->shared_by_admin = 0;
            $document->public = 0;
            if(isset($request->public)){
                if(Auth::user()->isAdmin()){
                    $document->shared_by_admin = 1; 
                }else{
                    $document->public = 1;
                }
            }
            $document->tag_id = 0;
            if(!empty($request->add_tag_name)){
                $tag = Tag::where('name', 'LIKE', strtolower($request->add_tag_name))
                            ->where('location', Auth::user()->location)
                            ->first();
                if(count($tag)){
                    $document->tag_id = $tag->id;
                }else{
                    $tag = new Tag;
                    $tag->name = $request->add_tag_name;
                    $tag->location = Auth::user()->location;
                    $tag->save();
                    $document->tag_id = $tag->id;
                }
            }
            $document->save();
            Storage::disk('local')->put($filename,  file_get_contents($request->file('filename')));
            $message = "Success!|You have successfully uploaded ".$filename." to our database!";
        }else{
            if(!$request->file('filename')->isValid()){
                $message = "Error!|The file you sent was not valid. Please input a different file and try again.";
            }
            if(empty($request->description)){
                $message .= "<br />The description you provided was purely white space characters. Please input a valid description and try again.";
            }
            // $message = "Error!|The file you sent was not valid. Please input a different file and try again.";
        }

        if($request->header('referer') == "http://localhost:8000/home/publicfiles"){
            $files = Auth::user()->documents()->where('public', 1)->where('deleted', '0')->orderBy('updated_at', 'desc')->get();
            $allfiles = Auth::user()->documents()->where('public', 1)->where('deleted', '0')->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(9)->get();
        }elseif($request->header('referer') == "http://localhost:8000/home/sharedfiles"){
            $files = Auth::user()->documents()->where('shared_by_admin', 1)->where('deleted', '0')->orderBy('updated_at', 'desc')->get();
            $allfiles = Auth::user()->documents()->where('shared_by_admin', 1)->where('deleted', '0')->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(9)->get();
        }elseif(strpos($request->header('referer'), "tag") !== false){
            $id = explode("/", $request->header('referer'))[4];
            $files = Auth::user()->documents();
            if($request->pageFrom == "http://localhost:8000/home"){
                if(!Auth::user()->isAdmin()){
                    $allfiles = Document::where('tag_id', $id)->where('deleted', '0')->where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
                }else{
                    $allfiles = Document::where('tag_id', $id)->where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
                }
            }else{
                if(!Auth::user()->isAdmin()){
                    $allfiles = Document::where('tag_id', $id)->where('deleted', '0')->orderBy('updated_at', 'desc')->get();
                }else{
                    $allfiles = Document::where('tag_id', $id)->orderBy('updated_at', 'desc')->get();
                }
            }
        }elseif($request->header('referer') == "http://localhost:8000/home/allfiles"){
            if(!Auth::user()->isAdmin()){
                $files = Auth::user()->documents()->where('deleted', '0')->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(7)->get();
                $allfiles = Auth::user()->documents()->where('deleted', '0')->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(9)->get();
            }else{
                $files = Auth::user()->documents()->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(7)->get();
                $allfiles = Auth::user()->documents()->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(9)->get();
            }
        }else{
            if(!Auth::user()->isAdmin()){
                $files = Auth::user()->documents()->where('deleted', '0')->orderBy('updated_at', 'desc')->limit(7)->get();
                $allfiles = Auth::user()->documents()->where('deleted', '0')->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(9)->get();
            }else{
                $files = Auth::user()->documents()->orderBy('updated_at', 'desc')->limit(7)->get();
                $allfiles = Auth::user()->documents()->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(9)->get();
            }
        }
        return array($message, $this->fileString($files), $this->fileString($allfiles), count(Auth::user()->documents()->where('tag_id', '0')->where('deleted', '0')->get()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        $message = "";
        $document = Document::findOrFail($id);
        $edit_success = false;
        if(!empty($request->file("edit_filename"))){
            if($request->file("edit_filename")->isValid() && !(empty($request->edit_description))){
                $old_file = $document->filename;
                $filename = $request->file("edit_filename")->getClientOriginalName();
                $document->update([
                        "filename" => $filename,
                        "filepath" => "/storage/app/".$filename,
                        "description" => trim($request->edit_description),
                    ]);
                Storage::disk('local')->put($filename, file_get_contents($request->file("edit_filename")));
                $edit_success = true;
                $message = "Success!|You have successfully updated ".$old_file." to ".$document->filename." in our database!";
            }
        }else{
            if(!empty($request->edit_description)){
                $document->update([
                        "description" => trim($request->edit_description),
                    ]);
                $edit_success = true;
                $message = "Success!|You have successfully updated ".$document->filename." in our database!";
            }
        }
        if($edit_success){
            if(isset($request->edit_public)){
                if(Auth::user()->isAdmin()){
                    $document->update([
                            "shared_by_admin" => 1,
                        ]);
                }else{
                    $document->update([
                            "public" => 1,
                        ]);
                }
            }else{
                $document->update([
                        "public" => 0,
                        "shared_by_admin" => 0,
                    ]);
            }
            if(!empty($request->edit_tag_name)){
                $tag = Tag::where('name', 'LIKE', strtolower($request->edit_tag_name))
                            ->where('location', Auth::user()->location)
                            ->first();
                if(!count($tag)){
                    $tag = new Tag;
                    $tag->name = $request->edit_tag_name;
                    $tag->location = Auth::user()->location;
                    $tag->save();
                }
                $document->update([
                        "tag_id" => $tag->id,
                    ]);
            }
        }
        if(!$request->file('edit_filename')->isValid()){
            $message = "Error!|The file you sent was not valid. Please input a different file and try again.";
        }
        if(empty($request->edit_description)){
            $message .= "<br />The description you provided was purely white space characters. Please input a valid description and try again.";
        }

        if($request->header('referer') == "http://localhost:8000/home/publicfiles"){
            $files = Auth::user()->documents()->where('public', 1)->where('deleted', '0')->orderBy('updated_at', 'desc')->get();
            $allfiles = Auth::user()->documents()->where('public', 1)->where('deleted', '0')->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(9)->get();
        }elseif($request->header('referer') == "http://localhost:8000/home/sharedfiles"){
            $files = Auth::user()->documents()->where('shared_by_admin', 1)->where('deleted', '0')->orderBy('updated_at', 'desc')->get();
            $allfiles = Auth::user()->documents()->where('shared_by_admin', 1)->where('deleted', '0')->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(9)->get();
        }elseif(strpos($request->header('referer'), "tag") !== false){
            $id = explode("/", $request->header('referer'))[4];
            $files = Auth::user()->documents();
            if($request->pageFrom == "http://localhost:8000/home"){
                if(!Auth::user()->isAdmin()){
                    $allfiles = Document::where('tag_id', $id)->where('deleted', '0')->where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
                }else{
                    $allfiles = Document::where('tag_id', $id)->where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
                }
            }else{
                if(!Auth::user()->isAdmin()){
                    $allfiles = Document::where('tag_id', $id)->where('deleted', '0')->orderBy('updated_at', 'desc')->get();
                }else{
                    $allfiles = Document::where('tag_id', $id)->orderBy('updated_at', 'desc')->get();
                }
            }
        }else{
            if(!Auth::user()->isAdmin()){
                $files = Auth::user()->documents()->where('deleted', '0')->orderBy('updated_at', 'desc')->limit(7)->get();
                $allfiles = Auth::user()->documents()->where('deleted', '0')->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(9)->get();
            }else{
                $files = Auth::user()->documents()->orderBy('updated_at', 'desc')->limit(7)->get();
                $allfiles = Auth::user()->documents()->where('tag_id', '0')->orderBy('updated_at', 'desc')->limit(9)->get();
            }
        }
        return array($message, $this->fileString($files), $this->fileString($allfiles), count(Auth::user()->documents()->where('tag_id', '0')->where('deleted', '0')->get()));
    }

    public function fileString($files){
        $fileString = "";
        foreach($files as $file){
            $public = 0;
            $deleted = "";
            $tag = "";
            if($file->shared_by_admin || $file->public){
                $public = 1;
            }
            if($file->deleted){
                $deleted = "deleted";
            }
            if(!is_null($file->tag)){
                $tag = $file->tag->name;
            }
            $fileString.=('<li class="drawer-item waves-effect '.$deleted.'" data-updated_at="'.$file->updated_at.'" data-public="'.$public.'" data-file-id="'.$file->id.'" data-owned-by-user="'.$file->ownedByUser().'">'.
                                '<i class="material-icons">description</i>'.
                                '<div>'.
                                '<p class="title">'.$file->filename.'</p>'.
                                '<span class="date"> '.
                                    Carbon::parse($file->updated_at)->diffForHumans().
                                ' </span>'.
                            '</div>'.
                            '<p class="description">'.$file->description.'</p>'.
                            '<p class="tag">'.$tag.'</p>'.
                        '</li>');
        }
        return $fileString;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd("destroy in");
        // dd("destroyed");
        $document = Document::findOrFail($id);
        $document->update(['deleted' => 1]);

        return back();
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);
        $filepath_array = explode("/", $document->filepath);
        if(file_exists(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().explode("/", $document->filepath)[3])){
           Session::flash('alert-success', 'You have successfully downloaded '.$document->filename);
           return response()->download(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().explode("/", $document->filepath)[3]);
        }
        Session::flash('alert-danger', 'There was a problem downloading the file. '.explode("/", $document->filepath)[3].' may have been corrupted.');
        return back();
    }

    public function taggedFile(Request $request, $id){
        $tag = Tag::findOrFail($id);
        if($request->header('referer') == "http://localhost:8000/home"){
            $documents = Document::where('tag_id', $id)->where('deleted', 0)->where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
        }else{
            $documents = Document::where('tag_id', $id)->where('deleted', 0)->orderBy('updated_at', 'desc')->get();
        }
        return view('tag', compact('documents', 'tag'));
    }
}
