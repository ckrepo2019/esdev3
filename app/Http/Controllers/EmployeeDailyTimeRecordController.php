<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Crypt;
use DB;
use PDF;
use DateTime;
use DateInterval;
use DatePeriod;
use Session;
use App\Models\HR\HREmployeeAttendance;
class EmployeeDailyTimeRecordController extends Controller
{
    public function employeedailytimerecord($id, Request $request){

        
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;
        if(Session::get('currentPortal') == '1'){

            $extends = "teacher.layouts.app";
            
        }elseif(Session::get('currentPortal') == '2'){

            $extends = "principalsportal.layouts.app2";

        }elseif(Session::get('currentPortal') == '3'){

            $extends = "registrar.layouts.app";

        }elseif(Session::get('currentPortal') == '8'){

            $extends = "admission.layouts.app2";

        }elseif(Session::get('currentPortal') == '4' || Session::get('currentPortal') == '15'){

            $extends = "finance.layouts.app";

        }elseif(Session::get('currentPortal') == '6'){

            $extends = "adminPortal.layouts.app2";

        }elseif(Session::get('currentPortal') == '10' || $refid == 26){

            $extends = "hr.layouts.app";

        }elseif(Session::get('currentPortal') == '12'){

            $extends = "adminITPortal.layouts.app";

        }elseif(Session::get('currentPortal') == 14){

            $extends = "deanportal.layouts.app2";

        }else{
            $extends = "general.defaultportal..layouts.app";
        }

        $myid = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.lastname',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.suffix',
                'teacher.usertypeid',
                'usertype.utype',
                'hr_school_department.department'
                )
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('hr_school_department','usertype.departmentid','=','hr_school_department.id')
            ->where('teacher.userid', auth()->user()->id)
            ->first();

        $currentmonthworkdays   = array();

        if($id == 'dashboard'){
            
            $beginmonth             = new DateTime(date('Y-m-01'));
    
            $endmonth               = new DateTime(date('Y-m-t'));

            $stringfrom             = date('F 01, Y');

            $stringto               = date('F t, Y');

        }else{


            $perioddate             = explode(' - ', $request->get('period'));

            $periodfrom             = explode('-',$perioddate[0]);
    
            $periodto               = explode('-',$perioddate[1]);

            $beginmonth             = new DateTime($periodfrom[2].'-'.$periodfrom[0].'-'.$periodfrom[1]);
    
            $endmonth               = new DateTime($periodto[2].'-'.$periodto[0].'-'.$periodto[1]);

            $stringfrom             = date('F d, Y', strtotime($periodfrom[2].'-'.$periodfrom[0].'-'.$periodfrom[1]));

            $stringto               = date('F d, Y', strtotime($periodto[2].'-'.$periodto[0].'-'.$periodto[1]));

        }

        $endmonth                   = $endmonth->modify( '+1 day' ); 
        
        $intervalmonth              = new DateInterval('P1D');

        $daterangemonth             = new DatePeriod($beginmonth, $intervalmonth ,$endmonth);

        foreach($daterangemonth as $datemonth){

                array_push($currentmonthworkdays,$datemonth->format("Y-m-d"));

        }

        $employeeattendance     = array();

    
        $detecttimeschedsetup = DB::table('deduction_tardinesssetup')
            ->where('status','1')
            ->first();

        
        // foreach($currentmonthworkdays as $workday){

        //     $attendance = HREmployeeAttendance::getattendance($workday, $myid);

        //     if($attendance->amin == '00:00:00')
        //     {
        //         $attendance->amin = "";
        //     }
        //     if($attendance->amout == '00:00:00')
        //     {
        //         $attendance->amout = "";
        //     }
        //     if($attendance->pmin == '00:00:00')
        //     {
        //         $attendance->pmin = "";
        //     }
        //     if($attendance->pmout == '00:00:00')
        //     {
        //         $attendance->pmout = "";
        //     }
        //     $checkifexists = DB::table('hr_attendanceremarks')
        //         ->where('tdate',$workday)
        //         ->where('employeeid', $myid->id)
        //         ->where('deleted','0')
        //         ->first();

        //     $remarks = '';
        //     if($checkifexists)
        //     {
        //         $remarks = $checkifexists->remarks;
        //     }
        //     array_push($employeeattendance,(object)array(
        //         'tdate'         => $workday,
        //         'date'          =>  date('M d, Y',strtotime($workday)),
        //         'day'           =>  date('l',strtotime($workday)),
        //         'dayint'        =>  date('d',strtotime($workday)),
        //         'remarks'        =>  $remarks,
        //         // 'undertime'     =>  "",
        //         // 'hoursrendered' =>  "",
        //         'timerecord'    =>  (object)array(
        //                                 'amin'  =>  $attendance->amin,
        //                                 'amout' =>  $attendance->amout,
        //                                 'pmin'  =>  $attendance->pmin,
        //                                 'pmout' =>  $attendance->pmout
        //                             )
        //         ));


        // }

        $employeeattendance = \App\Models\HR\HREmployeeAttendance::gethours($currentmonthworkdays, $myid->id);
        // return $employeeattendance;
        if(count($employeeattendance)>0)
        {
            foreach($employeeattendance as $empatt)
            {
                if($empatt->amtimein == null)
                {
                    $empatt->amtimein = "";
                }else{
                    $empatt->amtimein = date('h:i:s', strtotime($empatt->amtimein));
                }
                if($empatt->amtimeout == null)
                {
                    $empatt->amtimeout = "";
                }else{
                    $empatt->amtimeout = date('h:i:s', strtotime($empatt->amtimeout));
                }
                if($empatt->pmtimein == null)
                {
                    $empatt->pmtimein = "";
                }else{
                    $empatt->pmtimein = date('h:i:s', strtotime($empatt->pmtimein));
                }
                if($empatt->pmtimeout == null)
                {
                    $empatt->pmtimeout = "";
                }else{
                    $empatt->pmtimeout = date('h:i:s', strtotime($empatt->pmtimeout));
                }
            }                    
        }
        // return $employeeattendance;
        if($id == 'dashboard'){
            
            return view('employeedailytimerecord')
                ->with('employeeattendance',$employeeattendance)
                ->with('currentmonthfirstday',date('m-01-Y'))
                ->with('currentmonthlastday',date('m-t-Y'))
                ->with('myid',$myid->id)
                ->with('extends',$extends);
        }
        elseif($id == 'changeperiod'){

            return $employeeattendance;

        }
        elseif($id == 'print'){

            $GLOBALS['bodyHeight'] = 0;

            $dateperiod = $stringfrom.' to '.$stringto;

            $pdf = PDF::loadview('globalfiles/pdf/pdf_dtr',compact('employeeattendance','myid','dateperiod'))->setPaper(array(0,0,250,$GLOBALS['bodyHeight']+630));

            return $pdf->stream('DTR - '.$myid->lastname.'-'.$myid->firstname.'.pdf'); 

        }

    }
    public function updateremarks(Request $request)
    {
        // return $request->all();
        date_default_timezone_set('Asia/Manila');
        $checkifexists = DB::table('hr_attendanceremarks')
            ->where('tdate',$request->get('selecteddate'))
            ->where('employeeid', $request->get('id'))
            ->where('deleted','0')
            ->first();

        if($checkifexists)
        {
            DB::table('hr_attendanceremarks')
                ->where('id', $checkifexists->id)
                ->update([
                    'remarks'           => $request->get('remarks'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }else{
            DB::table('hr_attendanceremarks')
                ->insert([
                    'employeeid'        => $request->get('id'),
                    'tdate'             => $request->get('selecteddate'),
                    'remarks'           => $request->get('remarks'),
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);
            return 1;
        }
    }
    public function dtr_v2(Request $request)
    {
        if($request->has('employeeid'))
        {
            $myid = DB::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.lastname',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.suffix',
                    'teacher.usertypeid',
                    'usertype.utype',
                    'hr_school_department.department'
                    )
                ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                ->leftJoin('hr_school_department','usertype.departmentid','=','hr_school_department.id')
                ->where('teacher.id',$request->get('employeeid'))
                ->first();
        }else{
            $myid = DB::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.lastname',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.suffix',
                    'teacher.usertypeid',
                    'usertype.utype',
                    'hr_school_department.department'
                    )
                ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                ->leftJoin('hr_school_department','usertype.departmentid','=','hr_school_department.id')
                ->where('teacher.userid', auth()->user()->id)
                ->first();
        }
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

        }elseif($refid == 35){
            $extends = "tesda.layouts.app2";
        }
        
        else{

            $extends = "general.defaultportal.layouts.app";

        }
        if($request->has('action') == 'getattendance')
        {
            $dates = explode(' - ',$request->get('dateperiod'));
            $datefrom   = $dates[0];
            $dateto   = $dates[1];
            
            $alldays        = array();
    
            $periodfrom             = explode('-',$datefrom);
            
            $periodto               = explode('-',$dateto);

            $beginmonth             = new DateTime($periodfrom[2].'-'.$periodfrom[0].'-'.$periodfrom[1]);
    
            $endmonth               = new DateTime($periodto[2].'-'.$periodto[0].'-'.$periodto[1]);
    
            $endmonth               = $endmonth->modify( '+1 day' ); 
            
            $intervalmonth          = new DateInterval('P1D');
    
            $daterangemonth         = new DatePeriod($beginmonth, $intervalmonth ,$endmonth);
    
            foreach($daterangemonth as $datemonth){
    
                    array_push($alldays,$datemonth->format("Y-m-d"));
    
            }
            
            // $summarylogs = \App\Models\HR\HREmployeeAttendance::gethours($alldays, $myid->id);

            // return $summarylogs;

            $customamtimein = DB::table('employee_customtimesched')
                ->where('employeeid', $myid->id)
                ->where('deleted', 0)
                ->first();

            if(!$customamtimein)
            {
                $customamtimein = (object)array(
                    'amin'      => '08:00:00',
                    'amout'      => '12:00:00',
                    'pmin'      => '13:00:00',
                    'pmout'      => '17:00:00',
                );
            }
            $summarylogs = array();
            
            foreach($alldays as $day)
            {
    
                // $day = '2023-04-03';
            
                $taphistory = DB::table('taphistory')
                    ->where('studid', $myid->id)
                    ->where('deleted','0')
                    ->where('tdate', $day)
                    ->where('utype', '!=', 7)
                    ->orderBy('ttime','asc')
                    ->get();

                if(count($taphistory)>0)
                {
                    foreach($taphistory as $tapatt)
                    {
                        $tapatt->mode = 0;
                    }
                }

                $hr_attendance = DB::table('hr_attendance')
                    ->where('studid', $myid->id)
                    ->where('tdate', $day)
                    ->where('deleted',0)
                    ->orderBy('ttime','asc')
                    ->get();

                if(count($hr_attendance)>0)
                {
                    foreach($hr_attendance as $hratt)
                    {
                        $hratt->mode = 1;
                    }
                }


                $logs = collect();
                $logs = $logs->merge($taphistory);
                $logs = $logs->merge($hr_attendance);
                $logs = $logs->sortBy('ttime');
                $logs = $logs->unique('ttime');

                $hours = 0;
                $minutes = 0; 
                $latehours = 0;
                $lateminutes = 0;
                if(count($logs)>0)
                {
                    // return $logs;
                    
                    if(collect($logs)->where('ttime','<','12:00:00')->count() > 0)
                    {
                        if(collect($logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first())
                        {
                            $amttimeamin = collect($logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first()->ttime;
                            $amtimein = $day.' '.$amttimeamin;
                        }else{
                            
                            if($customamtimein)
                            {
                                $amttimeamin = $customamtimein->amin;
                                $amtimein = $day.' '.$customamtimein->amin;
                            }else{
                                $amttimeamin = '08:00:00';
                                $amtimein = $day.' 08:00:00';
                            }
                        }

                        $am_customtimein = new DateTime($amtimein);
                        $am_latetimein = new DateTime($day.' '.$customamtimein->amin);
                        $amlateinterval = $am_customtimein->diff($am_latetimein);
                        $lateh = $amlateinterval->format('%h');
                        $latem = $amlateinterval->format('%i');
                        
                        if($amttimeamin>$customamtimein->amin)
                        {
                            $latehours+=$lateh;
                            $lateminutes+=$latem;
                        }
                        // if(collect($logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first())
                        // {
                        //     $amtimein = $day.' '.collect($logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first()->ttime;
                        // }else{
                            
        
                        //     if($customamtimein)
                        //     {
                        //         $amtimein = $day.' '.$customamtimein->amin;
                        //     }else{
                        //         $amtimein = $day.' 08:00:00';
                        //     }
                        // }
                        if(collect($logs)->where('tapstate','OUT')->where('ttime','<','12:00:00')->last())
                        {
                            $amtimeout = $day.' '.collect($logs)->where('tapstate','OUT')->where('ttime','<','12:00:00')->last()->ttime;
                        }else{
                            
                            $customamtimeout = DB::table('employee_customtimesched')
                                ->where('employeeid', $myid->id)
                                ->where('deleted', 0)
                                ->first();
        
                            if($customamtimeout)
                            {
                                $amtimeout = $day.' '.$customamtimeout->amout;
                            }else{
                                $amtimeout = $day.' 12:00:00';
                            }
                        }
                        $am_timein = new DateTime($amtimein);
                        $am_timeout = new DateTime($amtimeout);
                        $aminterval = $am_timein->diff($am_timeout);
                         
                        $hours = $aminterval->format('%h');
                        $minutes = $aminterval->format('%i');
                    }
                    // if($day == '2023-04-03')
                    // {
                    //     return $amtimeout;
                    // }
                    
                    /////
                    // return collect($logs)->where('ttime','>=','12:00:00')->count();
                    if(collect($logs)->where('ttime','>=','12:00:00')->count() > 0)
                    {
                        if(collect($logs)->where('tapstate','IN')->where('ttime','>=','12:00:00')->first())
                        {
                            $pmtimein = $day.' '.collect($logs)->where('tapstate','IN')->where('ttime','>=','12:00:00')->first()->ttime;
                        }else{
                            
                            $custompmtimein = DB::table('employee_customtimesched')
                                ->where('employeeid', $myid->id)
                                ->where('deleted', 0)
                                ->first();
        
                            if($custompmtimein)
                            {
                                $pmtimein = $day.' '.$custompmtimein->pmin;
                            }else{
                                $pmtimein = $day.' 13:00:00';
                            }
                        }
                        if(collect($logs)->where('tapstate','OUT')->where('ttime','>=','12:00:00')->last())
                        {
                            $pmtimeout = $day.' '.collect($logs)->where('tapstate','OUT')->where('ttime','>=','12:00:00')->last()->ttime;
                        }else{
                            
                            $custompmtimeout = DB::table('employee_customtimesched')
                                ->where('employeeid', $myid->id)
                                ->where('deleted', 0)
                                ->first();
        
                            if($custompmtimeout)
                            {
                                $pmtimeout = $day.' '.$custompmtimeout->pmout;
                            }else{
                                $pmtimeout = $day.' 17:00:00';
                            }
                        }
                        $pm_timein = new DateTime($pmtimein);
                        $pm_timeout = new DateTime($pmtimeout);
                        $pminterval = $pm_timein->diff($pm_timeout);
                         
                        $hours += $pminterval->format('%h');
                        $minutes += $pminterval->format('%i');
                    }
    
                }
    
                while($minutes>=60)
                {
                    $hours+=1;
                    $minutes-=60;
                }

                $remarks = DB::table('hr_attendanceremarks')
                ->where('tdate',$day)
                ->where('employeeid',  $myid->id)
                ->where('deleted','0')
                ->first();

                $timeinam = collect($logs)->where('tapstate','IN')->first()->ttime ?? '';
                $timeoutam = collect($logs)->where('ttime','<=',$customamtimein->pmin)->where('tapstate','OUT')->last()->ttime ?? '';
                $timeinpm = collect($logs)->where('ttime','>=',$customamtimein->amout)->where('ttime','<=',$customamtimein->pmout)->where('tapstate','IN')->first()->ttime ?? '';
                $timeoutpm = collect($logs)->where('ttime','>',$timeinpm)->where('tapstate','OUT')->last()->ttime ?? '';

                // if($day == '2023-04-03')
                // {
                //     return $timeoutam;
                // }
                array_push($summarylogs, (object)array(
                    'remarks'      => $remarks->remarks ?? '',
                    'date'      => $day,
                    'customtimesched_amin'      => $customamtimein->amin,
                    'customtimesched_amout'      => $customamtimein->amout,
                    'customtimesched_pmin'      => $customamtimein->pmin,
                    'customtimesched_pmout'      => $customamtimein->pmout,
                    'timeinam'      => $timeinam,
                    'timeinpm'      => $timeinpm,
                    'timeoutam'      => $timeoutam,
                    'timeoutpm'      => $timeoutpm,
                    'logs'      => collect($logs)->values()->all(),
                    'latehours'     => $latehours,
                    'lateminutes'     => $lateminutes,
                    'hours'     => $hours,
                    'minutes'   => $minutes
                ));
            }
            // return $summarylogs[0]->timeinam;

            $schoolcalendar = DB::table('school_calendar')
                ->where('deleted','0')
                ->get();
              
            $finalcalendar = array();
            if(count($schoolcalendar)>0)
            {
                foreach($schoolcalendar as $eachdate)
                {
                    $eachdate->startwithtime = $eachdate->start;
                    $eachdate->endwithtime = $eachdate->end;
                    $eachdate->start = date('Y-m-d', strtotime($eachdate->start));
                    $eachdate->end = date('Y-m-d', strtotime($eachdate->end));

                    $eachdatefrom   = $eachdate->start;
                    $eachdateto   = $eachdate->end;
                    
                    $dayscovered        = array();
            
        
                    $begin             = new DateTime($eachdatefrom);
            
                    $end               = new DateTime($eachdateto);
            
                    $end               = $end->modify( '+1 day' ); 
                    
                    $interval          = new DateInterval('P1D');
            
                    $daterangemonth         = new DatePeriod($begin, $interval ,$end);
            
                    foreach($daterangemonth as $datemonth){
            
                            array_push($finalcalendar,(object)array(
                                'date'  => $datemonth->format("Y-m-d"),
                                'title' => $eachdate->title,
                                'venue' => $eachdate->venue,
                                'start' => $eachdate->start,
                                'end' => $eachdate->end,
                                'startwithtime' => $eachdate->startwithtime,
                                'endwithtime' => $eachdate->endwithtime
                            ));
            
                    }
                    
                    // if(in_array($alldays, ))
                }
            }
            // return $finalcalendar;
            // $schoolcalendar = collect($schoolcalendar)->whereIn()
            if($request->get('action') == 'getattendance')
            {
                return view('general.dtr.view_attendance')
                    ->with('attendance', $summarylogs)
                    ->with('datefrom', $periodfrom[2].'-'.$periodfrom[0].'-'.$periodfrom[1])
                    ->with('dateto', $periodto[2].'-'.$periodto[0].'-'.$periodto[1])
                    ->with('extends', $extends)
                    ->with('id', $request->get('id'))
                    ->with('finalcalendar', $finalcalendar);
            }else
            {
                $employee = $myid;
                // return $summarylogs;
                $datefrom = $periodfrom[2].'-'.$periodfrom[0].'-'.$periodfrom[1];
                $dateto = $periodto[2].'-'.$periodto[0].'-'.$periodto[1];
                $attendance = $summarylogs;
                $pdf = PDF::loadview('general.dtr.pdf_dtr',compact('attendance','datefrom','dateto','employee','finalcalendar'));
    
                return $pdf->stream('DTR - '.$myid->lastname.'-'.$myid->firstname.'.pdf'); 
            }
            // return date('F d, Y', strtotime($periodfrom[2].'-'.$periodfrom[0].'-'.$periodfrom[1]));
            // return date('F d, Y', strtotime($beginmonth));
        }else{
            return view('general.dtr.dtr_attendance')
                ->with('extends', $extends)
                ->with('id', $myid->id);
        }
    }
}
