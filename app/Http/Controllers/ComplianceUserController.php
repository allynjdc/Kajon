<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Redirector;
use App\Document;
use App\User; 
use App\Admin; 
use App\Reminder;
use App\ComplianceMunicipality;
use App\ComplianceUser;
use Auth;

class ComplianceUserController extends Controller
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

    public function checkCompliants($id) {
        
        $reminder_id = $id;
        $reminder = Reminder::findOrFail($reminder_id);

        if($reminder->municipality == '0'){

            //
            // Getting the User Compliants
            //
            $user_compliants = ComplianceUser::where('score','>','0')
                        ->where('reminder_id','=',$reminder_id)
                        ->where('date_complied','!=',null)
                        ->where('rejected','=','0')
                        ->where('accepted_by','!=',null)
                        ->where('rejected_by','=',null)
                        ->get();

            $user_pcompliants = ComplianceUser::where('score','=','0')
                        ->where('reminder_id','=',$reminder_id)
                        ->where('date_complied','!=',null)
                        ->where('rejected','=','0')
                        ->where('accepted_by','=',null)
                        ->where('rejected_by','=',null)
                        ->get();

            $user_ncompliants = ComplianceUser::where('score','=','0')
                        ->where('reminder_id','=',$reminder_id)
                        ->where('date_complied','=',null)
                        ->where('rejected','=','0')
                        ->where('accepted_by','=',null)
                        ->where('rejected_by','=',null)
                        ->get();

            $user_rcompliants = ComplianceUser::where('score','=','0')
                        ->where('reminder_id','=',$reminder_id)
                        ->where('date_complied','=',null)
                        ->where('rejected','!=','0')
                        ->where('accepted_by','=',null)
                        ->where('rejected_by','!=',null)
                        ->get();

            //
            //Getting the Municipality Compliants
            //
            $municipal_compliants = null;

            $municipal_pcompliants = null;

            $municipal_ncompliants = null;

            $municipal_rcompliants = null;

        } else {

            //
            // Getting the User Compliants
            //
            $user_compliants = null;

            $user_pcompliants = null;

            $user_ncompliants = null;

            $user_rcompliants = null;

            //
            //Getting the Municipality Compliants
            //
            $municipal_compliants = ComplianceMunicipality::where('score','>','0')
                        ->where('reminder_id','=',$reminder_id)
                        ->where('date_complied','!=',null)
                        ->where('rejected','=','0')
                        ->where('accepted_by','!=',null)
                        ->where('rejected_by','=',null)
                        ->get();

            $municipal_pcompliants = ComplianceMunicipality::where('score','=','0')
                        ->where('reminder_id','=',$reminder_id)
                        ->where('date_complied','!=',null)
                        ->where('rejected','=','0')
                        ->where('accepted_by','=',null)
                        ->where('rejected_by','=',null)
                        ->get();

            $municipal_ncompliants = ComplianceMunicipality::where('score','=','0')
                        ->where('reminder_id','=',$reminder_id)
                        ->where('date_complied','=',null)
                        ->where('rejected','=','0')
                        ->where('accepted_by','=',null)
                        ->where('rejected_by','=',null)
                        ->get();

            $municipal_rcompliants = ComplianceMunicipality::where('score','=','0')
                        ->where('reminder_id','=',$reminder_id)
                        ->where('date_complied','=',null)
                        ->where('rejected','!=','0')
                        ->where('accepted_by','=',null)
                        ->where('rejected_by','!=',null)
                        ->get();
        }

        return view('user.compliance', compact('user_compliants','user_pcompliants','user_ncompliants','user_rcompliants','municipal_compliants','municipal_pcompliants','municipal_ncompliants','municipal_rcompliants','reminder'));
    }


    public function checkNotice($id){

        $reminder = Reminder::findOrFail($id);

        if($reminder->compliancesMunicipality()){

            // 
            // IF Reminder is for per-municipality
            //

            $loc = Auth::user()->location;
            $comply = ComplianceMunicipality::where('reminder_id','=',$id)
                    ->where('location','=', $loc)
                    ->pluck("id");

            $compliance = ComplianceMunicipality::findOrFail($comply);

            if($compliance[0] != null){
                $compliance[0]->update([
                    'date_complied' => date('Y-m-d'),
                    'rejected' => '0',
                    'rejected_by' => null
                ]);
            } else {
                $compliance->update([
                    'date_complied' => date('Y-m-d'),
                    'rejected' => '0',
                    'rejected_by' => null
                ]);
            }            

        } else {

            //
            // IF Reminder is for individual USERS
            //

            $user_id = Auth::user()->id;
            $comply = ComplianceUser::where('compliance_users.reminder_id','=',$id)
                    ->where('compliance_users.user_id','=', $user_id)
                    ->pluck("id");
            
            $compliance = ComplianceUser::findOrFail($comply);

            if($compliance[0] != null){
                $compliance[0]->update([
                    'date_complied' => date('Y-m-d'),
                    'rejected' => '0',
                    'rejected_by' => null
                ]);
            } else {
                $compliance->update([
                    'date_complied' => date('Y-m-d'),
                    'rejected' => '0',
                    'rejected_by' => null
                ]);
            }

        }

        return redirect('/reminders');
    }
}
