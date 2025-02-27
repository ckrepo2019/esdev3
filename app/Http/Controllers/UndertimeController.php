<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
class UndertimeController extends Controller
{
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

        if(Session::get('currentPortal') == 1){

            $extends = "teacher.layouts.app";
            
        }elseif(Session::get('currentPortal') == 2){

            $extends = "principalsportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 3  ||  Session::get('currentPortal') == 8){

            $extends = "registrar.layouts.app";

        }elseif(Session::get('currentPortal') == 4  ||  Session::get('currentPortal') == 15){

            $extends = "finance.layouts.app";

        }elseif(Session::get('currentPortal') == 6){

            $extends = "adminPortal.layouts.app2";

        }elseif(Session::get('currentPortal') == 10 || $refid == 26){

            $extends = "hr.layouts.app";

        }elseif(Session::get('currentPortal') == 12){

            $extends = "adminITPortal.layouts.app";

        }elseif(Session::get('currentPortal') == 14){

            $extends = "deanportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 16){

            $extends = "chairpersonportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 18){

            $extends = "ctportal.layouts.app2";

        }else{

            $extends = "general.defaultportal.layouts.app";

        }
        $myapprovals = DB::table('undertime_approval')
            ->where('appruserid', auth()->user()->id)
            ->where('deleted','0')
            ->get();

        $applications  = DB::table('undertime_application')
            ->select('undertime_application.*','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix')
            ->join('teacher','undertime_application.employeeid','=','teacher.id')
            ->where('undertime_application.deleted','0')
            ->whereIn('teacher.id',collect($myapprovals)->pluck('employeeid'))
            ->get();

            
        if(count($applications)>0)
        {
            foreach($applications as $application)
            {
                $getstatus = DB::table('undertime_appstatus')
                    ->where('appid', $application->id)
                    ->where('approvalid', collect($myapprovals)->where('employeeid',$application->employeeid)->first()->id)
                    ->where('deleted','0')
                    ->first();

                if($getstatus)
                {
                    $application->status = $getstatus->appstatus;
                    $application->statusupdateddatetime = $getstatus->updateddatetime;
                    $application->reason = $getstatus->remarks;
                }else{
                    $application->status = 0;
                }
                $application->approvalid = collect($myapprovals)->where('employeeid',$application->employeeid)->first()->id;
            }
        }
        // return $applications;
        if(!$request->has('action'))
        {
            return view('general.undertime.monitorindex')
                ->with('applications', $applications)
                ->with('extends', $extends);
        }else{

        }
    }
    public function updatestatus(Request $request)
    {
        $id_application = $request->get('appid');
        $id_approval = $request->get('approvalid');
        $status = $request->get('appstatus');
        $reason = $request->get('reasondisapproval');
            
        $checkifapplicationisdeleted = DB::table('undertime_application')
            ->where('id', $id_application)
            ->first()->deleted;

        if($checkifapplicationisdeleted == 0)
        {
            $checkifexists = DB::table('undertime_appstatus')
                ->where('appid', $id_application)
                ->where('approvalid', $id_approval)
                ->where('deleted','0')
                ->first();
    
            if($checkifexists)
            {
                DB::table('undertime_appstatus')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'appstatus'         => $status,
                        'remarks'           => $reason,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('undertime_appstatus')
                    ->insert([
                        'appid'             => $id_application,
                        'approvalid'        => $id_approval,
                        'appstatus'         => $status,
                        'remarks'           => $reason,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
    
            }
        }
        return 1;
    }
    public function application(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

        if(Session::get('currentPortal') == 1){

            $extends = "teacher.layouts.app";
            
        }elseif(Session::get('currentPortal') == 2){

            $extends = "principalsportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 3  ||  Session::get('currentPortal') == 8){

            $extends = "registrar.layouts.app";

        }elseif(Session::get('currentPortal') == 4  ||  Session::get('currentPortal') == 15){

            $extends = "finance.layouts.app";

        }elseif(Session::get('currentPortal') == 6){

            $extends = "adminPortal.layouts.app2";

        }elseif(Session::get('currentPortal') == 10 || $refid == 26){

            $extends = "hr.layouts.app";

        }elseif(Session::get('currentPortal') == 12){

            $extends = "adminITPortal.layouts.app";

        }elseif(Session::get('currentPortal') == 14){

            $extends = "deanportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 16){

            $extends = "chairpersonportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 18){

            $extends = "ctportal.layouts.app2";

        }else{

            $extends = "general.defaultportal.layouts.app";

        }

        if(!$request->has('action'))
        {
            $approvals = DB::table('undertime_approval')
                ->select('undertime_approval.id','teacher.lastname','teacher.firstname')
                ->join('teacher','undertime_approval.appruserid','=','teacher.userid')
                ->where('employeeid', DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->id)
                ->where('undertime_approval.deleted','0')
                ->get();

            $applications = DB::table('undertime_application')
                ->where('employeeid', DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->id)
                ->where('deleted','0')
                ->get();

            if(count($applications)>0)
            {
                foreach($applications as $application)
                {
                    if(count($approvals)>0)
                    {
                        foreach($approvals as $approval)
                        {
                            $appstatus = DB::table('undertime_appstatus')
                                ->where('appid', $application->id)
                                ->where('approvalid', $approval->id)
                                ->where('deleted','0')
                                ->first();

                            if($appstatus)
                            {
                                $approval->status = $appstatus->appstatus;
                                $approval->reason = $appstatus->remarks;
                            }else{
                                $approval->status = 0;
                                $approval->reason = '';
                            }
                        }
                    }

                    $application->approvals =   $approvals;
                    // else{
                    //     $application->status = 0;
                    // }
                }
            }
            // return $applications;
            return view('general.undertime.applyindex')
                ->with('approvals', $approvals)
                ->with('applications', $applications)
                ->with('extends', $extends);
        }else{
            if($request->get('action') == 'apply')
            {
                $checkifexists = DB::table('undertime_application')
                    ->where('employeeid', DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->id)
                    ->where('udate', $request->get('apply-date'))
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    // return 0;
                }else{
                    DB::table('undertime_application')
                        ->insert([
                            'employeeid'            => DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->id,
                            'udate'                 => $request->get('apply-date'),
                            'timefrom'              => $request->get('apply-timefrom'),
                            'timeto'                => $request->get('apply-timeto'),
                            'remarks'               => $request->get('apply-remarks'),
                            'createdby'             => auth()->user()->id,
                            'createddatetime'       => date('Y-m-d H:i:s')
                        ]);
                    // return 1;
                }
                return back();
            }
            elseif($request->get('action') == 'delete')
            {
                DB::table('undertime_application')
                ->where('id', $request->get('appid'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
                return 1;
            }
        }
    }
}
