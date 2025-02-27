<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
class HRLeavesController extends Controller
{
    public function index(Request $request)
    {      
        // return $request->all();
        $refid = DB::table('usertype')
            ->where('id', Session::get('currentPortal'))
            ->first()->refid;

        $extends = "general.defaultportal.layouts.app";
        
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

        }
        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
        {
            $id = DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->where('isactive','1')->first()->id;
            $isDepartmentHead = false;

            $checkdepthead = DB::table('hr_departmentheads')
                ->where('deptheadid', $id)
                ->where('deleted','0')
                ->get();

            if(count($checkdepthead)>0)
            {
                $isDepartmentHead = true;
            }
            $isinSignatory = false;

            $checksign = DB::table('sait_leavesignatories')
                ->select('sait_leavesignatories.*','teacher.lastname','teacher.firstname','teacher.suffix')
                ->join('teacher','sait_leavesignatories.userid','=','teacher.userid')
                // ->where('sait_leavesignatories.userid',auth()->user()->id)
                ->where('sait_leavesignatories.deleted','0')
                ->where('teacher.deleted','0')
                ->orderBy('sait_leavesignatories.id','asc')
                ->get();

            if(count($checksign)>0)
            {
                $isinSignatory = true;
            }
            $leaveapplications = array();
            if($isDepartmentHead)
            {
                foreach($checkdepthead as $eachdept)
                {
                    $eachdeptemployees = DB::table('teacher')
                        ->select('sait_leaveapply.*','teacher.lastname','teacher.firstname','teacher.suffix','department','hr_leaves.leave_type')
                        ->join('sait_leaveapply','teacher.userid','=','sait_leaveapply.userid')
                        ->join('hr_departments','teacher.schooldeptid','=','hr_departments.id')
                        ->join('hr_leaves','sait_leaveapply.leavetypeid','=','hr_leaves.id')
                        ->where('teacher.deleted','0')
                        ->where('sait_leaveapply.deleted','0')
                        ->where('schooldeptid',$eachdept->deptid)
                        ->get();
                    
                    if(count($eachdeptemployees)>0)
                    {
                        foreach($eachdeptemployees as $eachdeptemployee)
                        {
                            $checkapprecord = DB::table('sait_approvaldetails')
                                ->where('applicationid', $eachdeptemployee->id)
                                ->where('approvaluserid', auth()->user()->id)
                                ->where('deleted','0')
                                ->first();

                            if($checkapprecord)
                            {
                                $eachdeptemployee->appstatus = $checkapprecord->appstatus;
                                $eachdeptemployee->appstatusdesc = $checkapprecord->appstatus == 0 ? 'Pending' : ($checkapprecord->appstatus == 1 ? 'Approved' : 'Rejected');
                                $eachdeptemployee->appstatusdate = date('m/d/Y', strtotime($checkapprecord->updateddatetime));
                                $eachdeptemployee->remarks =$checkapprecord->remarks;

                            }else{
                                $eachdeptemployee->appstatus = 0;
                                $eachdeptemployee->appstatusdesc = 'Pending';
                                $eachdeptemployee->appstatusdate = '';
                                $eachdeptemployee->remarks = '';
                            }
                            $eachdeptemployee->signatorylabel = 'Department Head';
                            array_push($leaveapplications, $eachdeptemployee);
                        }
                    }
                }
            }
            // return $checksign;
            if($isinSignatory)
            {
                $employees = DB::table('teacher')
                    ->select('sait_leaveapply.*','teacher.lastname','teacher.firstname','teacher.suffix','department','hr_leaves.leave_type')
                    ->join('sait_leaveapply','teacher.userid','=','sait_leaveapply.userid')
                    ->join('hr_departments','teacher.schooldeptid','=','hr_departments.id')
                    ->join('hr_leaves','sait_leaveapply.leavetypeid','=','hr_leaves.id')
                    ->where('teacher.deleted','0')
                    ->where('sait_leaveapply.deleted','0')
                    ->get();
                
                if(count($employees)>0)
                {
                    foreach($employees as $eachemployee)
                    {
                        $countapprecord = DB::table('sait_approvaldetails')
                            ->select('sait_approvaldetails.*','teacher.lastname','teacher.firstname')
                            ->where('applicationid', $eachemployee->id)
                            // ->where('approvaluserid', auth()->user()->id)
                            ->join('teacher','sait_approvaldetails.approvaluserid','=','teacher.userid')

                            ->where('sait_approvaldetails.deleted','0')
                            ->where('teacher.deleted','0')
                            ->get();
                            
                        // if(collect($countapprecord)->where('appstatus','1')->count()>0)
                        // {
                            foreach($checksign as $eachsign)
                            {
                                if(collect($countapprecord)->where('approvaluserid',$eachsign->userid)->count()>0)
                                {
                                    foreach(collect($countapprecord)->where('approvaluserid',$eachsign->userid)->values() as $eachentry)
                                    {
                                        $eachsign->appstatus = $eachentry->appstatus;
                                        $eachsign->appstatusdesc = $eachentry->appstatus == 0 ? 'Pending' : ($eachentry->appstatus == 1 ? 'Approved' : 'Rejected');
                                        $eachsign->appstatusdate = date('m/d/Y', strtotime($eachentry->updateddatetime));
                                        $eachsign->remarks =$eachentry->remarks;
                                        $eachsign->signatorylabel = $eachsign->description;
                                        $eachsign->signatoryname = $eachsign->lastname.', '.$eachsign->firstname;

                                        if($eachentry->approvaluserid == auth()->user()->id)
                                        {
                                            $eachemployee->appstatus = $eachentry->appstatus;
                                            $eachemployee->appstatusdesc = $eachentry->appstatus == 0 ? 'Pending' : ($eachentry->appstatus == 1 ? 'Approved' : 'Rejected');
                                            $eachemployee->appstatusdate = date('m/d/Y', strtotime($eachentry->updateddatetime));
                                            $eachemployee->remarks =$eachentry->remarks;
                                            $eachemployee->signatorylabel = $eachsign->description;
                                            $eachemployee->signatoryname = $eachsign->lastname.', '.$eachsign->firstname;
                                        }
                                    }
        
                                }else{
                                    $eachsign->appstatus = 0;
                                    $eachsign->appstatusdesc = 'Pending';
                                    $eachsign->appstatusdate = '';
                                    $eachsign->remarks = '';
                                    $eachsign->signatorylabel =  $eachsign->description;
                                    $eachsign->signatoryname = $eachsign->lastname.', '.$eachsign->firstname;
                                    if($eachsign->userid == auth()->user()->id)
                                    {
                                        $eachemployee->appstatus = 0;
                                        $eachemployee->appstatusdesc = 'Pending';
                                        $eachemployee->appstatusdate = '';
                                        $eachemployee->remarks = '';
                                        $eachemployee->signatorylabel =  $eachsign->description;
                                        $eachemployee->signatoryname = $eachsign->lastname.', '.$eachsign->firstname;
                                    }
                                }
                            }
                            $eachemployee->approvals = $checksign;
                            array_push($leaveapplications, $eachemployee);
                        // }
                    }
                }
            }
            // return $leaveapplications;
            // $leaveapplications = collect($leaveapplications)->unique();
            // sait_leavesignatories
            // return count($leaveapplications);
            // dd($isDepartmentHead);
            return view('hr.leaves.sait.index')
                ->with('leaveapplications',$leaveapplications)
                ->with('extends',$extends);

        }else{

            $payrollinfo = DB::table('hr_payrollv2')
                ->where('status','1')
                ->where('deleted','0')
                ->first();
    
            $getMyid = DB::table('teacher')
                ->select('id')
                ->where('userid', auth()->user()->id)
                ->first();
    
            $hr_approvals = DB::table('hr_leavesappr')
                ->where('deleted','0')
                ->get();
    
    
            $employees = DB::table('teacher')
                ->select('teacher.*')
                ->join('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                ->where('teacher.isactive','1')
                ->where('teacher.deleted','0')
                ->get();
    
    
            $appliedleaves = array();
            $leaves = array();
    
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                $filedleaves = DB::table('employee_leaves')
                    ->select(
                        'employee_leaves.id',
                        'employee_leaves.employeeid',
                        'employee_leaves.leaveid',
                        'employee_leaves.leavestatus',
                        'employee_leaves.remarks',
                        'employee_leaves.createdby',
                        'hr_leaves.leave_type',
                        'hr_leaves.days',
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.suffix',
                        'teacher.picurl',
                        'usertype.utype',
                        'employee_leaves.leavestatus',
                        'employee_leaves.createddatetime'
                        )
                    ->join('teacher','employee_leaves.employeeid','=','teacher.id')
                    ->join('usertype','teacher.usertypeid','=','usertype.id')
                    ->join('hr_leaves','employee_leaves.leaveid','=','hr_leaves.id')
                    ->where('hr_leaves.deleted','0')
                    ->where('employee_leaves.deleted','0')
                    ->orderByDesc('employee_leaves.createddatetime')
                    ->get();
            }else{
                $filedleaves = DB::table('hr_leaveemployees')
                    ->select(
                        'hr_leaveemployees.id',
                        'hr_leaveemployees.employeeid',
                        'hr_leaveemployees.leaveid',
                        'hr_leaveemployees.leavestatus',
                        'hr_leaveemployees.remarks',
                        'hr_leaveemployees.datefrom',
                        'hr_leaveemployees.dateto',
                        'hr_leaveemployees.createdby',
                        'hr_leaves.leave_type',
                        'hr_leaves.days',
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.suffix',
                        'teacher.picurl',
                        'usertype.utype',
                        'hr_leaveemployees.leavestatus',
                        'hr_leaveemployees.createddatetime'
                        )
                    ->join('teacher','hr_leaveemployees.employeeid','=','teacher.id')
                    ->join('usertype','teacher.usertypeid','=','usertype.id')
                    ->join('hr_leaves','hr_leaveemployees.leaveid','=','hr_leaves.id')
                    ->where('hr_leaves.deleted','0')
                    ->where('hr_leaveemployees.deleted','0')
                    ->orderByDesc('hr_leaveemployees.createddatetime')
                    ->get();

            }
            
            if($request->has('leavetypeid'))
            {
                if($request->get('leavetypeid') > 0)
                {
                    $filedleaves = collect($filedleaves)->where('leaveid', $request->get('leavetypeid'))->values();
                }
            }
            
            if(count($filedleaves)>0)
            {
                foreach($filedleaves as $filedleave)
                {
                    
                    $filedleave->display = 0;

                    if ($filedleave->datefrom == $filedleave->dateto) {
                        $filedleave->duration = date('F j, Y', strtotime($filedleave->datefrom));
                    } else {
                        $start_date = date('F j, Y', strtotime($filedleave->datefrom));
                        $end_date = date('F j, Y', strtotime($filedleave->dateto));
                        $filedleave->duration = $start_date . ' - ' . $end_date;
                    }
                    
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
                    {
                        $approvalheader = DB::table('hr_leaveemployees')
                            ->where('employeeid', $filedleave->employeeid)
                            ->where('leaveid', $filedleave->leaveid)
                            ->where('deleted','0')
                            ->first();
                        
                        $attachments = DB::table('employee_leavesatt')
                            ->where('headerid', $filedleave->id)
                            ->where('deleted','0')
                            ->get();
                        if($approvalheader)
                        {
                            $approvals = DB::table('hr_leaveemployeesappr')
                                ->select('teacher.id','teacher.userid','teacher.lastname','teacher.firstname','teacher.middlename')
                                ->join('teacher','hr_leaveemployeesappr.appuserid','=','teacher.userid')
                                ->where('hr_leaveemployeesappr.deleted','0')
                                ->where('hr_leaveemployeesappr.headerid',$approvalheader->id)
                                ->get();
                            foreach($approvals as $approvalhead)
                            {
                                
                                    $getapprdata = DB::table('employee_leavesappr')
                                        ->where('ldateid', $filedleave->id)
                                        ->where('appuserid', $approvalhead->userid)
                                        ->where('deleted','0')
                                        ->first();
                                    
                                if($getapprdata)
                                {
                                    $approvalhead->remarks = $getapprdata->remarks;
                                    $approvalhead->appstatus = $getapprdata->appstatus;
                                }else{
                                    $approvalhead->remarks = '';
                                    $approvalhead->appstatus = 0;
                                }
                            }
                            if(collect($approvals)->where('userid', auth()->user()->id)->count()>0)
                            {
                                $filedleave->display = 1;
                            }
                            if(collect($approvals)->where('appstatus',2)->count()>0)
                            {
                                $filedleave->leavestatus = 2;
                            }
                            
                                
                        }else{
                            $approvals = array();
                        }
                    }else{
                        $filedleave->display = 1;
                        $attachments = array();
                        // DB::table('hr_leaveempattach')
                        //     ->where('headerid', $filedleave->id)
                        //     ->where('deleted','0')
                            // ->get();
                        $approvals = DB::table('hr_leavesappr')
                            ->select('teacher.id','teacher.userid','teacher.userid','teacher.lastname','teacher.firstname','teacher.middlename','hr_leavesappr.appuserid')
                            ->join('teacher','hr_leavesappr.appuserid','=','teacher.userid')
                            // ->where('employeeid', $filedleave->employeeid)
                            ->where('leaveid', $filedleave->leaveid)
                            ->where('hr_leavesappr.deleted','0')
                            ->get();
    
                        // return $approvals;
                        if(count($approvals)>0)
                        {
                            
                            foreach($approvals as $approvalheader)
                            {
                                $getapprdata = DB::table('hr_leaveemployeesappr')
                                    ->where('headerid', $filedleave->id)
                                    ->where('appuserid', $approvalheader->appuserid)
                                    ->where('deleted','0')
                                    ->first();
                                    
                                if($getapprdata)
                                {
                                    $approvalheader->remarks = $getapprdata->remarks;
                                    $approvalheader->appstatus = $getapprdata->appstatus;
                                }else{
                                    $approvalheader->remarks = '';
                                    $approvalheader->appstatus = 0;
                                }
                            }
                            // if(collect($approvals)->where('userid', auth()->user()->id)->count()>0)
                            // {
                            //     $filedleave->display = 1;
                            // }
                            // if(collect($approvals)->where('appstatus',2)->count()>0)
                            // {
                            //     $filedleave->leavestatus = 2;
                            // }
                            
                                
                        }
                    }
                        
    
                    
    
                    if($payrollinfo)
                    {
                        $payrollhistory = DB::table('hr_payrollv2historydetail')
                            ->where('payrollid', $payrollinfo->id)
                            ->where('employeeid',$filedleave->employeeid)
                            ->where('employeeleaveid', $filedleave->id)
                            ->where('deleted','0')
                            ->count();
                            
                        if($payrollhistory >0)
                        {
                            $filedleave->display = 0;
                        }
                    }
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
                    {
                        $dates = DB::table('employee_leavesdetail')
                                ->select('id','ldate', 'dayshift', 'leavestatus')
                                ->where('headerid', $filedleave->id)
                                ->where('deleted','0')
                                ->get();
                    }else{
                        $dates = DB::table('hr_leaveempdetails')
                                ->select('id','ldate', 'dayshift', 'leavestatus','remarks','halfday')
                                ->where('headerid', $filedleave->id)
                                ->where('deleted','0')
                                ->get();

                            // return $dates;

                    }
                    
                    $numdays = 0;
                    if(count($dates)>0)
                    {
                        // return collect($approvals)->where('appstatus','1')->count();
                        foreach($dates as $date)
                        {
                            $date->daystatus = ' ';

                            if ($date->halfday == 0) {
                                $date->daystatus = 'Whole Day';
                            } else if($date->halfday == 1){
                                $date->daystatus = 'AM Half Day';
                            } else if($date->halfday == 2){
                                $date->daystatus = 'PM Half Day';
                            }
                            if(collect($approvals)->where('appstatus','1')->count() == count($approvals))
                            {
                                
                                if($date->dayshift == 0)
                                {
                                    $numdays+=1;
                                }else{
                                    $numdays+=0.5;
                                }
                            }
                            if(collect($approvals)->where('appstatus','0')->count() > 0)
                            {
                                if($date->dayshift == 0)
                                {
                                    $numdays+=1;
                                }else{
                                    $numdays+=0.5;
                                }
                            }
                        }
                    }

                    // return $approvals;
                    $filedleave->dates = $dates;

                    // return $dates;
                    $filedleave->attachments = $attachments;
                    $filedleave->approvals = $approvals;
                    $filedleave->numdays = $numdays;
    
                    
                    $numdaysleft = collect($filedleaves)->where('leaveid', $filedleave->id)->sum('numdays');
                    
                    $filedleave->numdaysleft = $numdaysleft;
                }
            }
            
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                $filedleaves = collect($filedleaves)->where('display','1')->values();
            }
            
            
            $leavetypes = DB::table('hr_leaves')
                ->where('isactive','1')
                ->where('deleted','0')
                ->get();
                
            // return $leavetypes;
            if($request->has('leavetypeid'))
            {

                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
                {
                    return view('hr.leaves.results')
                        ->with('appliedleaves',$appliedleaves)
                        ->with('filedleaves',$filedleaves)
                        ->with('employees',$employees);
                }else{
                    return view('hr.leaves.results_default')
                        ->with('appliedleaves',$appliedleaves)
                        ->with('filedleaves',$filedleaves)
                        ->with('employees',$employees);
                }

            }else{

                return view('hr.leaves.index')
                    ->with('extends',$extends)
                    ->with('appliedleaves',$appliedleaves)
                    ->with('filedleaves',$filedleaves)
                    ->with('employees',$employees)
                    ->with('leavetypes',$leavetypes);
            }
        }
    }
    public function fileleave(Request $request)
    {
        $employeeids    = $request->get('employeeids');
        $leaveid        = $request->get('leaveid');
        $selecteddates  = $request->get('selecteddates');
        $remarks        = $request->get('remarks');
        
        foreach($employeeids as $employeeid)
        {
            $checkifexists = DB::table('employee_leaves')
                ->where('employeeid', $employeeid)
                ->where('leaveid', $leaveid)
                ->where('deleted','0')
                ->get();
                
            if(count($checkifexists) == 0)
            {
                $id = DB::table('employee_leaves')
                    ->insertGetId([
                        'employeeid'         => $employeeid,
                        'leaveid'            => $leaveid,
                        'remarks'            => $remarks,
                        'numofdays'          => count($selecteddates),
                        'createdby'          => auth()->user()->id,
                        'createddatetime'    => date('Y-m-d H:i:s')
                    ]);

                foreach($selecteddates as $selecteddate)
                {
                    DB::table('employee_leavesdetail')
                        ->insert([
                            'headerid'           => $id,
                            'ldate'              => $selecteddate,
                            'createdby'          => auth()->user()->id,
                            'createddatetime'    => date('Y-m-d H:i:s')
                        ]);
                }
                
            }else{
                foreach($selecteddates as $selecteddate)
                {
                    $checkdateifexists =  DB::table('employee_leavesdetail')
                        ->where('headerid', $checkifexists[0]->id)
                        ->where('ldate', $selecteddate)
                        ->where('deleted','0')
                        ->get();

                    if(count($checkdateifexists) == 0)
                    {
                        DB::table('employee_leavesdetail')
                            ->insert([
                                'headerid'           => $checkifexists[0]->id,
                                'ldate'              => $selecteddate,
                                'createdby'          => auth()->user()->id,
                                'createddatetime'    => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }
        }
    }
    public function delete(Request $request)
    {
        $id = $request->get('id');
        try{
            DB::table('employee_leaves')
                ->where('id', $id)
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);

            DB::table('employee_leavesdetail')
                ->where('headerid', $id)
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }
    }
    public function changestatus(Request $request)
    {

        $id = $request->get('id');
        $status = $request->get('selectedstatus');
        $remarks = $request->get('reason');
        $leavedatesid = $request->get('leaveDatesArray');

        if($request->ajax())
        {
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                $checkifexists = DB::table('employee_leavesappr')
                    ->where('ldateid', $id)
                    ->where('deleted','0')
                    ->where('createdby',auth()->user()->id)
                    ->first();
        
                if($checkifexists)
                {
                    DB::table('employee_leavesappr')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'appstatus'         => $status,
                            'remarks'           => $remarks,
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    DB::table('employee_leavesappr')
                        ->insert([
                            'ldateid'            => $id,
                            'remarks'            => $remarks,
                            'appuserid'          => auth()->user()->id,
                            'appstatus'          => $status,
                            'createdby'          => auth()->user()->id,
                            'createddatetime'    => date('Y-m-d H:i:s')
                        ]);
        
                }
            }else{

                $checkifexists = DB::table('hr_leaveemployeesappr')
                    ->where('headerid', $id)
                    ->where('deleted','0')
                    ->where('createdby',auth()->user()->id)
                    ->first();

                if($checkifexists)
                {

                    if ($status == 3) {
                        DB::table('hr_leaveemployees')
                            ->where('id', $id)
                            ->update([
                                // 'appstatus'         => $status,
                                'deleted'         => 1,
                                'remarks'           => $remarks,
                                'updatedby'         => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);

                        
                        DB::table('hr_leaveemployeesappr')
                            ->where('id', $checkifexists->id)
                            ->update([
                                'appstatus'         => $status,
                                'deleted'         => 1,
                                'remarks'           => $remarks,
                                'updatedby'         => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    } else {

                        DB::table('hr_leaveemployeesappr')
                            ->where('id', $checkifexists->id)
                            ->update([
                                'appstatus'         => $status,
                                'remarks'           => $remarks,
                                'updatedby'         => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);

                            if ($leavedatesid == null) {
                                DB::table('hr_leaveempdetails')
                                ->where('headerid', $id)
                                ->update([
                                    'appstatus'           => $status,
                                    'updatedby'         => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                                
                            } else {
                                DB::table('hr_leaveempdetails')
                                    ->where('headerid', $id)
                                    ->update([
                                        'appstatus'           => $status,
                                        'updatedby'         => auth()->user()->id,
                                        'updateddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                    }

                }else{
                    if ($status == 3) {
                            DB::table('hr_leaveemployees')
                                ->where('id', $id)
                                ->update([
                                    'deleted'         => 1,
                                    'remarks'           => $remarks,
                                    'updatedby'         => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);

                            DB::table('hr_leaveemployeesappr')
                                ->where('headerid', $id)
                                ->update([
                                    'deleted'         => 1,
                                    'remarks'           => $remarks,
                                    'updatedby'         => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                
                    } else {

                        DB::table('hr_leaveemployeesappr')
                            ->insert([
                                'headerid'            => $id,
                                'remarks'            => $remarks,
                                'appuserid'          => auth()->user()->id,
                                'appstatus'          => $status,
                                'createdby'          => auth()->user()->id,
                                'createddatetime'    => date('Y-m-d H:i:s')
                            ]);

                        DB::table('hr_leaveempdetails')
                            ->whereIn('id', $leavedatesid)
                            ->update([
                                'appstatus'           => $status,
                                'updatedby'         => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }

                   
        
                }
            }
            return 1;
        }else{
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
            {
                $checkifexists = DB::table('sait_approvaldetails')
                    ->where('applicationid', $request->get('leaveapplicationid'))
                    ->where('approvaluserid', auth()->user()->id)
                    ->where('deleted','0')
                    ->first();
                    
                if($checkifexists)
                {
                    DB::table('sait_approvaldetails')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'appstatus'         => $request->get('leaveapp'.$request->get('leaveapplicationid')),
                            'remarks'           => $request->get('reasonfordisapproval'),
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{

                    DB::table('sait_approvaldetails')
                        ->insert([
                            'applicationid'      => $request->get('leaveapplicationid'),
                            'remarks'            => $request->get('reasonfordisapproval'),
                            'approvaluserid'     => auth()->user()->id,
                            'appstatus'          => $request->get('leaveapp'.$request->get('leaveapplicationid')),
                            'createdby'          => auth()->user()->id,
                            'createddatetime'    => date('Y-m-d H:i:s'),
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
                return back();
            }

        }
    }
    public function approve(Request $request)
    {
        $id = $request->get('id');
        try{

            // DB::table('employee_leaves')
            // DB::table('employee_leavesdetail')
            //     ->where('id', $id)
            //     ->update([
            //         'leavestatus'           => 1,
            //         'updatedby'             => auth()->user()->id,
            //         'updateddatetime'       => date('Y-m-d H:i:s')
            //     ]);
            // $checkifexists = DB::table('hr_leavesapprdetails')
            //     ->where('employeeleaveid', $id)
            //     ->where('deleted','0')
            //     ->where('createdby',auth()->user()->id)
            //     ->first();

            // if($checkifexists)
            // {
            //     DB::table('hr_leavesapprdetails')
            //         ->where('id', $checkifexists->id)
            //         ->update([
            //             'appstatus'         => 1,
            //             'updatedby'         => auth()->user()->id,
            //             'updateddatetime'   => date('Y-m-d H:i:s')
            //         ]);
            // }else{
            //     DB::table('hr_leavesapprdetails')
            //         ->insert([
            //             'employeeleaveid'    => $id,
            //             'appstatus'          => 1,
            //             'createdby'          => auth()->user()->id,
            //             'createddatetime'    => date('Y-m-d H:i:s')
            //         ]);

            // }
            $checkifexists = DB::table('employee_leavesappr')
                ->where('ldateid', $id)
                ->where('deleted','0')
                ->where('createdby',auth()->user()->id)
                ->first();

            if($checkifexists)
            {
                DB::table('employee_leavesappr')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'appstatus'         => 1,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('employee_leavesappr')
                    ->insert([
                        'ldateid'            => $id,
                        'appuserid'          => auth()->user()->id,
                        'appstatus'          => 1,
                        'createdby'          => auth()->user()->id,
                        'createddatetime'    => date('Y-m-d H:i:s')
                    ]);

            }
            return 1;
        }catch(\Exception $error)
        {
            // return $error;
            return 0;
        }
    }
    public function pending(Request $request)
    {
        $id = $request->get('id');
        try{

            // DB::table('employee_leaves')
            // DB::table('employee_leavesdetail')
            //     ->where('id', $id)
            //     ->update([
            //         'leavestatus'           => 0,
            //         'updatedby'             => auth()->user()->id,
            //         'updateddatetime'       => date('Y-m-d H:i:s')
            //     ]);
                
            // $checkifexists = DB::table('hr_leavesapprdetails')
            //     ->where('employeeleaveid', $id)
            //     ->where('deleted','0')
            //     ->where('createdby',auth()->user()->id)
            //     ->first();
            // if($checkifexists)
            // {
            //     DB::table('hr_leavesapprdetails')
            //         ->where('id', $checkifexists->id)
            //         ->update([
            //             'appstatus'         => 0,
            //             'updatedby'         => auth()->user()->id,
            //             'updateddatetime'   => date('Y-m-d H:i:s')
            //         ]);
            // }else{
            //     DB::table('hr_leavesapprdetails')
            //         ->insert([
            //             'employeeleaveid'    => $id,
            //             'appstatus'          => 0,
            //             'createdby'          => auth()->user()->id,
            //             'createddatetime'    => date('Y-m-d H:i:s')
            //         ]);

            // }
            // return 1;
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                $checkifexists = DB::table('employee_leavesappr')
                    ->where('ldateid', $id)
                    ->where('deleted','0')
                    ->where('createdby',auth()->user()->id)
                    ->first();
    
                if($checkifexists)
                {
                    DB::table('employee_leavesappr')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'appstatus'         => 0,
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    DB::table('employee_leavesappr')
                        ->insert([
                            'ldateid'            => $id,
                            'appuserid'          => auth()->user()->id,
                            'appstatus'          => 0,
                            'createdby'          => auth()->user()->id,
                            'createddatetime'    => date('Y-m-d H:i:s')
                        ]);
    
                }
            }else{
                $checkifexists = DB::table('hr_leaveemployeesappr')
                    ->where('headerid', $id)
                    ->where('deleted','0')
                    ->where('createdby',auth()->user()->id)
                    ->first();
    
                if($checkifexists)
                {
                    DB::table('hr_leaveemployeesappr')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'appstatus'         => 0,
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    DB::table('hr_leaveemployeesappr')
                        ->insert([
                            'headerid'            => $id,
                            'appuserid'          => auth()->user()->id,
                            'appstatus'          => 0,
                            'createdby'          => auth()->user()->id,
                            'createddatetime'    => date('Y-m-d H:i:s')
                        ]);
    
                }
            }
            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }
    }
    public function disapprove(Request $request)
    {
        $id = $request->get('id');
        try{

            // DB::table('employee_leaves')
            // DB::table('employee_leavesdetail')
            //     ->where('id', $id)
            //     ->update([
            //         'leavestatus'           => 2,
            //         'updatedby'             => auth()->user()->id,
            //         'updateddatetime'       => date('Y-m-d H:i:s')
            //     ]);
            // $checkifexists = DB::table('hr_leavesapprdetails')
            //     ->where('employeeleaveid', $id)
            //     ->where('deleted','0')
            //     ->where('createdby',auth()->user()->id)
            //     ->first();
            // if($checkifexists)
            // {
            //     DB::table('hr_leavesapprdetails')
            //         ->where('id', $checkifexists->id)
            //         ->update([
            //             'appstatus'         => 2,
            //             'updatedby'         => auth()->user()->id,
            //             'updateddatetime'   => date('Y-m-d H:i:s')
            //         ]);
            // }else{
            //     DB::table('hr_leavesapprdetails')
            //         ->insert([
            //             'employeeleaveid'    => $id,
            //             'appstatus'          => 2,
            //             'createdby'          => auth()->user()->id,
            //             'createddatetime'    => date('Y-m-d H:i:s')
            //         ]);

            // }
                
            // return 1;
            $checkifexists = DB::table('employee_leavesappr')
                ->where('ldateid', $id)
                ->where('deleted','0')
                ->where('createdby',auth()->user()->id)
                ->first();

            if($checkifexists)
            {
                DB::table('employee_leavesappr')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'appstatus'         => 2,
                        'remarks'           => $request->get('remarks'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('employee_leavesappr')
                    ->insert([
                        'ldateid'            => $id,
                        'appuserid'          => auth()->user()->id,
                        'appstatus'          => 2,
                        'remarks'            => $request->get('remarks'),
                        'createdby'          => auth()->user()->id,
                        'createddatetime'    => date('Y-m-d H:i:s')
                    ]);

            }
            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }
    }
    // public function updatestatus(Request $request)
    // {
    //     return $request->all();
    // }
}
