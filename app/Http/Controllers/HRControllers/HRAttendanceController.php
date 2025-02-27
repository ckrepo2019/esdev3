<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;
use Crypt;
use File;
use DateTime;
use DateInterval;
use DatePeriod;
use PDF;
use Session;
use App\Models\HR\HREmployeeAttendance;
class HRAttendanceController extends Controller
{
    
    public function index(Request $request)
    {   
        date_default_timezone_set('Asia/Manila');
        
        if($request->get('changedate') == true){
            
            $date = $request->get('changedate');

        }else{

            $date = date('Y-m-d');

        }
        
        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->first();
        
        $employees = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix',
                'teacher.picurl',
                'employee_personalinfo.gender',
                'usertype.id as usertypeid',
                'usertype.utype'
                )
            ->join('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            ->where('teacher.deleted','0')
            ->where('teacher.isactive','1')
            // ->take(20)
            ->orderBy('lastname','asc')
            ->get();
          
    
        $detecttimeschedsetup = DB::table('deduction_tardinesssetup')
            ->where('status','1')
            ->first();

        $attendancearray = array();
        
        foreach($employees as $employee){

            $attendance = HREmployeeAttendance::getattendance($date, $employee);
            // return $attendance;
            array_push($attendancearray,(object)array(
                'employeeinfo'      => $employee,
                'attendance'        => (object)array(
                                            'in_am'             =>     $attendance->amin,
                                            'out_am'            =>     $attendance->amout,
                                            'in_pm'             =>     $attendance->pmin,
                                            'out_pm'            =>     $attendance->pmout,
                                            'taphistorystatus'  =>     $attendance->status
                                        )
            ));

        }
        if($request->get('changedate') == true){
            
            $attendance = array();

            return view('hr.attendance.changedate')
                ->with('currentdate',$date)
                ->with('attendance',$attendancearray);

        }else{
            // return $attendancearray;
            return view('hr.attendance.index')
                ->with('currentdate',$date)
                ->with('attendance',$attendancearray);

        }
    }
    public function indexv2(Request $request)
    {
        // return $request->all();
        if($request->has('action'))
        {
            $search = $request->get('search');
            $search = $search['value'];

            $employees = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix',
                'teacher.picurl',
                'teacher.tid',
                'employee_personalinfo.gender',
                'usertype.id as usertypeid',
                'usertype.utype'
                )
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            ->where('teacher.deleted','0')
            ->where('teacher.isactive','1');
            
            if($search != null){
                    $employees = $employees->where(function($query) use($search){
                                        $query->orWhere('firstname','like','%'.$search.'%');
                                        $query->orWhere('lastname','like','%'.$search.'%');
                                });
            }
            
            $employees = $employees->take($request->get('length'))
                ->skip($request->get('start'))
                ->orderBy('lastname','asc')
                // ->whereIn('studinfo.studstatus',[1,2,4])
                ->get();
                
            $employeescount = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix',
                'teacher.picurl',
                'employee_personalinfo.gender',
                'usertype.id as usertypeid',
                'usertype.utype'
                )
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            ->where('teacher.deleted','0')
            ->where('teacher.isactive','1');
                
            if($search != null){
                    $employeescount = $employeescount->where(function($query) use($search){
                                    $query->orWhere('firstname','like','%'.$search.'%');
                                    $query->orWhere('lastname','like','%'.$search.'%');
                                });
            }
            
            
            $employeescount = $employeescount
                ->orderBy('lastname','asc')
                // ->whereIn('studinfo.studstatus',[1,2,4])
                ->count();
                
                
            if($request->has('changedate')){
                
                $date = $request->get('changedate');

            }else{

                $date = date('Y-m-d');

            }
            foreach($employees as $employee)
            {
                $employee->lastname = strtoupper($employee->lastname);
                $employee->firstname = strtoupper($employee->firstname);
    
                $attendance = HREmployeeAttendance::getattendance($date, $employee);
                // return $attendance;
                $employee->amin = $attendance->amin != '00:00:00' ? $attendance->amin : '';
                $employee->amout = $attendance->amout != '00:00:00' ? $attendance->amout : '';
                $employee->pmin = $attendance->pmin != '00:00:00' ? $attendance->pmin : '';
                $employee->pmout = $attendance->pmout != '00:00:00' ? $attendance->pmout : '';
                $employee->attstatus = $attendance->status;

                $remarks = DB::table('hr_attendanceremarks')
                    ->where('tdate',$date)
                    ->where('employeeid', $employee->id)
                    ->where('deleted','0')
                    ->first();

                    
                $employee->remarks = $remarks->remarks ?? null;
    
            }
            return @json_encode((object)[
                'data'=>$employees,
                'recordsTotal'=>$employeescount,
                'recordsFiltered'=>$employeescount
            ]);
        }else{
            return view('hr.attendance.indexv2');
        }
          
    
	}
	public function gettimelogs(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $customtimesched = DB::table('employee_customtimesched')
            ->where('employeeid',$request->get('employeeid'))
            ->where('deleted','0')
            ->get();
        if(count($customtimesched) > 0)
        {
            if(strtolower(date('A', strtotime($customtimesched[0]->pmin))) == 'pm')
            {
                $customtimesched[0]->pmin = date('h:i:s', strtotime($customtimesched[0]->pmin));
            }
            if(strtolower(date('A', strtotime($customtimesched[0]->pmout))) == 'pm')
            {
                $customtimesched[0]->pmout = date('h:i:s', strtotime($customtimesched[0]->pmout));
            }
        }else{
            $customtimesched = array((object)[
                'amin'  => '08:00:00',
                'amout' => '12:00:00',
                'pmin'  => '01:00:00',
                'pmout' => '05:00:00'
            ]);
        }
        // $changedate = explode('-',$request->get('selecteddate'));

        // $date = $changedate[2].'-'.$changedate[0].'-'.$changedate[1];
        $date = $request->get('selecteddate');

        $employeeinfo = DB::table('teacher')
            ->where('id', $request->get('employeeid'))
            ->first();

        $taphistory = DB::table('taphistory')
            ->where('tdate', $date)
            ->where('studid', $request->get('employeeid'))
            ->where('utype', '!=','7')
            ->where('deleted',0)
            ->get();

        // return $taphistory;
        if(count($taphistory)>0)
        {
            foreach($taphistory as $tapatt)
            {
                $tapatt->mode = 0;
            }
        }

        $hr_attendance = DB::table('hr_attendance')
            ->where('tdate', $date)
            ->where('studid', $request->get('employeeid'))
			->where('deleted',0)
            ->get();

        if(count($hr_attendance)>0)
        {
            foreach($hr_attendance as $hratt)
            {
                $hratt->mode = 1;
            }
        }


        $checkifexists = collect();
        $checkifexists = $checkifexists->merge($taphistory);
        $checkifexists = $checkifexists->merge($hr_attendance);
        $checkifexists = $checkifexists->sortBy('ttime');
        $checkifexists = $checkifexists->unique('ttime');
        
        if(count($checkifexists)>0)
        {
            foreach($checkifexists as $log)
            {
                $log->ttime = date('h:i:s A',strtotime($log->ttime));
            }
        }

        $remarks = DB::table('hr_attendanceremarks')
            ->where('tdate',$date)
            ->where('employeeid', $request->get('employeeid'))
            ->where('deleted','0')
            ->first();

        // return $checkifexists;
        return view('hr.attendance.timelogs')
            ->with('customtimesched', $customtimesched)
            ->with('employeeinfo', $employeeinfo)
            ->with('remarks', $remarks)
            ->with('logs', $checkifexists);
    }


    public function getapproveleaves(Request $request){
        $approveleaves = DB::table('hr_leaveemployeesappr')
            ->select('hr_leaveemployeesappr.*','hr_leaveemployees.remarks','hr_leaveemployees.datefrom', 'hr_leaveemployees.dateto','hr_leaveemployees.employeeid','hr_leaves.leave_type','hr_leaveempdetails.halfday')
            ->leftJoin('hr_leaveemployees', 'hr_leaveemployeesappr.headerid', '=', 'hr_leaveemployees.id')
            ->leftJoin('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
            ->leftJoin('hr_leaveempdetails', 'hr_leaveemployeesappr.headerid', '=', 'hr_leaveempdetails.headerid')
            ->where('hr_leaves.deleted', 0)
            ->where('hr_leaveempdetails.deleted', 0)
            ->where('hr_leaveemployees.deleted', 0)
            ->where('hr_leaveemployeesappr.deleted', 0)
            ->where('hr_leaveemployeesappr.appstatus', 1)
            ->get();



        // if ($request->get('departmentid') != null) {
        //     $employeeids = DB::table('employee_personalinfo')
        //         ->where('departmentid', $request->get('departmentid'))
        //         ->where('deleted', 0)
        //         ->pluck('employeeid')
        //         ->toArray();
        //         // ->get();
            
        //     // return $employeeids;
        // }
    
        $leavesappr = DB::table('hr_leaveemployees')
            ->select(
                'hr_leaves.id',
                'hr_leaves.leave_type',
                'hr_leaveemployees.id as employeeleaveid',
                'hr_leaveemployees.employeeid',
                'hr_leaveempdetails.id as ldateid',
                'hr_leaveempdetails.ldate',
                'hr_leaveempdetails.dayshift',
                'hr_leaveempdetails.halfday',
                'hr_leaveempdetails.remarks'
                )
            ->join('hr_leaves','hr_leaveemployees.leaveid','=','hr_leaves.id')
            ->join('hr_leaveempdetails','hr_leaveemployees.id','=','hr_leaveempdetails.headerid')
            // ->whereBetween('hr_leaveempdetails.ldate',[$payrolldates->datefrom,$payrolldates->dateto])
            // ->whereIn('hr_leaveemployees.employeeid', $employeeids)
            // ->where('hr_leaveemployees.employeeid', 57)
            ->where('hr_leaveemployees.deleted','0')
            ->where('hr_leaveempdetails.deleted','0')
            ->orderByDesc('hr_leaveemployees.createddatetime')
            ->get();

            
        if(count($leavesappr)>0)
        {
            foreach($leavesappr as $leaveapp)
            {
                $leaveapp->leavestatus = 0;
                $approvalheads = DB::table('hr_leaveemployees')
                    ->select('teacher.id','teacher.userid','teacher.lastname','teacher.firstname','teacher.middlename','hr_leaveemployeesappr.appstatus')
                    ->join('hr_leaveemployeesappr', 'hr_leaveemployees.id','=','hr_leaveemployeesappr.headerid')
                    ->join('teacher', 'hr_leaveemployeesappr.appuserid','=','teacher.userid')
                    ->where('hr_leaveemployees.leaveid', $leaveapp->id)
                    // ->whereIn('hr_leaveemployees.employeeid', $employeeids)
                    // ->where('hr_leaveemployees.employeeid', 57)
                    ->where('hr_leaveemployees.deleted','0')
                    ->where('hr_leaveemployeesappr.deleted','0')
                    ->get();
                
                if(count($approvalheads)>0)
                {

                    if(collect($approvalheads)->where('appstatus','1')->count() == count($approvalheads))
                    {
                        $leaveapp->leavestatus = 1;
                    }
                }
            }
        }
            
        if (count($leavesappr)>0) {
            foreach ($leavesappr as $leavesdetail) {
                if ($leavesdetail->halfday == 0) {
                    $leavesdetail->daycoverd = 'Whole Day';
                } else if ($leavesdetail->halfday == 1) {
                    $leavesdetail->daycoverd = 'Half Day AM';
                } else if ($leavesdetail->halfday == 12) {
                    $leavesdetail->daycoverd = 'Half Day PM';
                } else {
                    $leavesdetail->daycoverd = '';
                }
            }
        }
        return $leavesappr;

    }

    public function updateremarks(Request $request)
    {
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
    public function addtimelog(Request $request)
    {   
        date_default_timezone_set('Asia/Manila');

        $date = $request->get('selecteddate');
        $action = $request->get('action');
        // return $request->all();
        if ($action == 'checkbox') {
            $times = ['08:00:00', '17:00:00']; // Times for morning and evening

            foreach ($times as $time) {
                $hour = (int) date('H', strtotime($time)); // Extract the hour from the time
                $timeshift = $hour >= 12 ? 'PM' : 'AM'; // Determine AM or PM
                $tapstate = $hour >= 12 ? 'OUT' : 'IN';
                DB::table('hr_attendance')->insert([
                    'tdate'         => $date,
                    'ttime'         => $time,
                    'tapstate'      => $tapstate,
                    'timeshift'     => $timeshift, // Set AM or PM
                    'studid'        => $request->get('employeeid'),
                    'utype'         => $request->get('usertypeid'),
                    'createdby'     => auth()->user()->id,
                    'createddatetime' => now() // Use the current timestamp
                ]);
            }

            return 1;
        } else {
            $taphistory = DB::table('taphistory')
            ->where('tdate',$date)
            ->where('ttime',$request->get('timelog'))
            ->where('studid',$request->get('employeeid'))
			->where('tapstate',strtoupper($request->get('tapstate')))
			->where('deleted',0)
            ->where('utype', '!=','7')
            ->get();

            $hr_attendance = DB::table('hr_attendance')
                ->where('tdate',$date)
                ->where('ttime',$request->get('timelog'))
                ->where('studid',$request->get('employeeid'))
                ->where('tapstate',strtoupper($request->get('tapstate')))
                ->where('deleted',0)
                ->get();


            $checkifexists = collect();
            $checkifexists = $checkifexists->merge($taphistory);
            $checkifexists = $checkifexists->merge($hr_attendance);
            $checkifexists = $checkifexists->sortBy('ttime');
            $checkifexists = $checkifexists->unique('ttime');

            if(count($checkifexists) == 0)
            {
                DB::table('hr_attendance')
                    ->insert([
                        'tdate'                 => $date,
                        'ttime'                 =>  $request->get('timelog'),
                        'tapstate'              => strtoupper($request->get('tapstate')),
                        'timeshift'             => strtoupper(date('A',strtotime($request->get('timelog')))),
                        'studid'                => $request->get('employeeid'),
                        'utype'                 => $request->get('usertypeid'),
                        // 'mode'                  => 1,
                        'createdby'             => auth()->user()->id,
                        'createddatetime'       => date('Y-m-d H:i:s')
                    ]);

                return '1';
            }else{
                return '0';
            }
        }

        
    }
    public function deletetimelog(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        DB::table('hr_attendance')
            ->where('id', $request->get('id'))
            ->update([
                'deleted'   => '1',
                'deletedby' => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function deletetimelogtapping(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        DB::table('taphistory')
            ->where('id', $request->get('id'))
            ->update([
                'deleted'   => '1',
                'deletedby' => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    }

    public function summaryindex(Request $request)
    {
        $employees = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix',
                'teacher.picurl',
                'employee_personalinfo.gender',
                'usertype.id as usertypeid',
                'usertype.utype'
                )
            ->join('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            ->where('teacher.deleted','0')
            ->where('teacher.isactive','1')
            ->where('teacher.isactive','1')
            // ->take(20)
            ->orderBy('lastname','asc')
            ->get();

        if(Session::get('currentPortal') == 10)
        {
            $extends = 'hr.layouts.app';
        }else{
            $extends = 'principalsportal.layouts.app2';
        }
        $departments = Db::table('hr_departments')
            ->where('deleted','0')
            ->get();
        return view('hr.attendance.summaryindex')
            ->with('departments', $departments)
            ->with('employees', $employees)
            ->with('extends', $extends);
    }
    public function summarygenerate(Request $request)
    {

        

        // return $request->all();

        if ($request->get('departmentid') != null) {
            $employeeids = DB::table('employee_personalinfo')
                ->where('departmentid', $request->get('departmentid'))
                ->where('deleted', 0)
                ->pluck('employeeid')
                ->toArray();
                // ->get();
            
            // return $employeeids;
        }

        // $basicsalaryinfos = DB::table('employee_basicsalaryinfo')
        //     ->where('deleted', 0)
        //     ->whereIn('employeeid', $employeeids)
        //     ->get();

        $leavesappr = DB::table('hr_leaveemployees')
            ->select(
                'hr_leaves.id',
                'hr_leaves.leave_type',
                'hr_leaveemployees.id as employeeleaveid',
                'hr_leaveemployees.employeeid',
                'hr_leaveempdetails.id as ldateid',
                'hr_leaveempdetails.ldate',
                'hr_leaveempdetails.dayshift',
                'hr_leaveempdetails.halfday',
                'hr_leaveempdetails.remarks'
                )
            ->join('hr_leaves','hr_leaveemployees.leaveid','=','hr_leaves.id')
            ->join('hr_leaveempdetails','hr_leaveemployees.id','=','hr_leaveempdetails.headerid')
            // ->whereBetween('hr_leaveempdetails.ldate',[$payrolldates->datefrom,$payrolldates->dateto])
            // ->whereIn('hr_leaveemployees.employeeid', $employeeids)
            // ->where('hr_leaveemployees.employeeid', 213)
            ->where('hr_leaveemployees.deleted','0')
            ->where('hr_leaveempdetails.deleted','0')
            ->orderByDesc('hr_leaveemployees.createddatetime')
            ->get();

            
        if(count($leavesappr)>0)
        {
            foreach($leavesappr as $leaveapp)
            {
                $leaveapp->leavestatus = 0;
                $approvalheads = DB::table('hr_leaveemployees')
                    ->select('teacher.id','teacher.userid','teacher.lastname','teacher.firstname','teacher.middlename','hr_leaveemployeesappr.appstatus')
                    ->join('hr_leaveemployeesappr', 'hr_leaveemployees.id','=','hr_leaveemployeesappr.headerid')
                    ->join('teacher', 'hr_leaveemployeesappr.appuserid','=','teacher.userid')
                    ->where('hr_leaveemployees.leaveid', $leaveapp->id)
                    // ->whereIn('hr_leaveemployees.employeeid', $employeeids)
                    // ->where('hr_leaveemployees.employeeid', 213)
                    ->where('hr_leaveemployees.deleted','0')
                    ->where('hr_leaveemployeesappr.deleted','0')
                    // ->where('hr_leaveemployeesappr.deleted',1)
                    ->get();

                    
                // return $approvalheads;
                if(count($approvalheads)>0)
                {

                    if(collect($approvalheads)->where('appstatus','1')->count() == count($approvalheads))
                    {
                        $leaveapp->leavestatus = 1;
                    }
                }
            }
        }
          
        if (count($leavesappr)>0) {
            foreach ($leavesappr as $leavesdetail) {
                if ($leavesdetail->halfday == 0) {
                    $leavesdetail->daycoverd = 'Whole Day';
                } else if ($leavesdetail->halfday == 1) {
                    $leavesdetail->daycoverd = 'Half Day AM';
                } else if ($leavesdetail->halfday == 2) {
                    $leavesdetail->daycoverd = 'Half Day PM';
                } else {
                    $leavesdetail->daycoverd = '';
                }
            }
        }

        
		// return $request->all();
        if(is_string($request->get('id')))
        {
            $request->merge([
                'id'    => json_decode($request->get('id'))
            ]);
        }
        try{
            if(count($request->get('id')) == 0 )
            {                
                $request->request->remove('id');
            }
        }catch(\Exception $error)
        {
            if($request->get('id') == null)
            {
                $request->request->remove('id');
            }
        }
        
        // return $request->all();
        $dates = explode(' - ',$request->get('dates'));
        $datefrom   = $dates[0];
        $dateto   = $dates[1];

        $alldays        = array();

        $beginmonth             = new DateTime($datefrom);
    
        $endmonth               = new DateTime($dateto);

        $endmonth               = $endmonth->modify( '+1 day' ); 
        
        $intervalmonth          = new DateInterval('P1D');

        $daterangemonth         = new DatePeriod($beginmonth, $intervalmonth ,$endmonth);

        foreach($daterangemonth as $datemonth){

                array_push($alldays,$datemonth->format("Y-m-d"));

        }
		// return $leavesappr;
        
        if(!$request->has('id') || count($request->get('id')) > 1)
        {
            if($request->has('id'))
            {
                
                $employees = DB::table('teacher')
                    ->select(
                        'teacher.id',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.lastname',
                        'teacher.suffix',
                        'teacher.picurl',
                        'employee_personalinfo.gender',
                        'employee_personalinfo.departmentid',
                        'teacher.schooldeptid',
                        'usertype.id as usertypeid',
                        'usertype.utype'
                        )
                    ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                    ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                    ->where('teacher.deleted','0')
                    ->where('teacher.isactive','1')
                    ->whereIn('teacher.id',$request->get('id'))
                    // ->take(20)
                    ->orderBy('lastname','asc')
                    // ->take(5)
                    ->get();
                    
            }else{
                
                $employees = DB::table('teacher')
                    ->select(
                        'teacher.id',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.lastname',
                        'teacher.suffix',
                        'teacher.picurl',
                        'employee_personalinfo.gender',
                        'employee_personalinfo.departmentid',
                        'teacher.schooldeptid',
                        'usertype.id as usertypeid',
                        'usertype.utype',
                        'employee_basicsalaryinfo.flexitime'
                        )
                    ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                    ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                    ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                    ->where('teacher.deleted','0')
                    ->where('teacher.isactive','1')
                    // ->take(20)
                    ->orderBy('lastname','asc')
                    // ->take(5)
                    ->get();

                // return $employees;
            }

            // return $employees;
            if(count($employees)>0)
            {
                foreach($employees as $employee)
                {
                    if($employee->departmentid == null)
                    {
                        $employee->departmentid = $employee->schooldeptid;
                    }
                }
            }

            

            if($request->get('departmentid')>0)
            {
                $employees = collect($employees)->where('departmentid',$request->get('departmentid'))->values();
            }
            
        //    return $employees;
            if(count($employees)>0)
            {
                foreach($employees as $employee)
                {
                    // $employeeleavesappr = collect($leavesappr)->where('employeeid', $employee->id)->where('leavestatus', 1)->values();
                    $employeeleavesappr = collect($leavesappr)->where('employeeid', $employee->id)->values();
                    $dateRange = explode(" - ", $request->get('dates'));
                    $datefrom = new DateTime($dateRange[0]);
                    $dateto = new DateTime($dateRange[1]);
                    $result = (object) [
                        'datefrom' => $datefrom->format('Y-m-d'),
                        'dateto' => $dateto->format('Y-m-d'),
                    ];

                    $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
                        ->select('employee_basicsalaryinfo.*','employee_basistype.type as salarytype','employee_basistype.type as ratetype')
                        ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                        ->where('employee_basicsalaryinfo.deleted','0')
                        ->where('employee_basicsalaryinfo.employeeid', $employee->id)
                        ->first();

                    $employeeinfo = DB::table('teacher')
                        ->select('teacher.*','employee_personalinfo.gender','utype','teacher.id as employeeid','employee_personalinfo.departmentid')
                        ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                        ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                        ->where('teacher.id', $employee->id)
                        ->where('teacher.deleted','0')
                        ->first();

                    $taphistory = DB::table('taphistory')
                        // ->where('tdate', $dates)
                        ->where('studid', $employee->id)
                        ->whereBetween('tdate', [$result->datefrom,$result->dateto])
                        ->where('utype', '!=','7')
                        ->orderBy('ttime','asc')
                        ->where('deleted','0')
                        ->get();
            
                    $hr_attendance = DB::table('hr_attendance')
                        // ->where('tdate', $dates)
                        ->where('studid',$employee->id)
                        ->whereBetween('tdate', [$result->datefrom,$result->dateto])
                        ->where('deleted',0)
                        ->orderBy('ttime','asc')
                        ->get();

                    $dates = array();
                    $summarylogs = array();
                    if ($basicsalaryinfo) {
                        $employeeinfo->ratetype = $basicsalaryinfo->ratetype;
                    } else {
                        $employeeinfo->ratetype = null;
                    }
                    if($basicsalaryinfo)
                    {
                    
                        $interval = new DateInterval('P1D');    
                        $realEnd = new DateTime($result->dateto);
                        $realEnd->add($interval);    
                        $period = new DatePeriod(new DateTime($result->datefrom), $interval, $realEnd);    
                        // return collect($period);
                        foreach($period as $date) {  
                                if(strtolower($date->format('l')) == 'monday')
                                {
                                    if($basicsalaryinfo->mondays == 1)
                                    {
                                        $dates[] = $date->format('Y-m-d'); 
                                    }
                                }   
                                elseif(strtolower($date->format('l')) == 'tuesday')
                                {
                                    if($basicsalaryinfo->tuesdays == 1)
                                    {
                                        $dates[] = $date->format('Y-m-d'); 
                                    }
                                }   
                                elseif(strtolower($date->format('l')) == 'wednesday')
                                {
                                    if($basicsalaryinfo->wednesdays == 1)
                                    {
                                        $dates[] = $date->format('Y-m-d'); 
                                    }
                                }   
                                elseif(strtolower($date->format('l')) == 'thursday')
                                {
                                    if($basicsalaryinfo->thursdays == 1)
                                    {
                                        $dates[] = $date->format('Y-m-d'); 
                                    }
                                }   
                                elseif(strtolower($date->format('l')) == 'friday')
                                {
                                    if($basicsalaryinfo->fridays == 1)
                                    {
                                        $dates[] = $date->format('Y-m-d'); 
                                    }
                                }   
                                elseif(strtolower($date->format('l')) == 'saturday')
                                {
                                    if($basicsalaryinfo->saturdays == 1)
                                    {
                                        $dates[] = $date->format('Y-m-d'); 
                                    }
                                }   
                                elseif(strtolower($date->format('l')) == 'sunday')
                                {
                                    if($basicsalaryinfo->sundays == 1)
                                    {
                                        $dates[] = $date->format('Y-m-d'); 
                                    }
                                }  
                        }
                        
                        // return collect($employeeinfo);
                        $startDate = new DateTime($result->datefrom);
                        $endDate = new DateTime($result->dateto);
                
                        $mondayCount = 0;
                        $tuesdayCount = 0;
                        $wednesdayCount = 0;
                        $thursdayCount = 0;
                        $fridayCount = 0;
                        $saturdayCount = 0;
                        $sundayCount = 0;
                        
                
                        // Iterate through the date range
                        while ($startDate <= $endDate) {
                            // Get the day of the week as a lowercase string (e.g., "monday")
                            $day_of_week = strtolower($startDate->format("l"));
                            // Check if the day is Monday
                            if ($day_of_week === "monday") {
                                $mondayCount++;
                            } elseif ($day_of_week === "tuesday") {
                                $tuesdayCount++;
                            } elseif ($day_of_week === "wednesday"){
                                $wednesdayCount++;
                            } elseif ($day_of_week === "thursday"){
                                $thursdayCount++;
                            } elseif ($day_of_week === "friday"){
                                $fridayCount++;
                            } elseif ($day_of_week === "saturday"){
                                $saturdayCount++;
                            } elseif ($day_of_week === "sunday"){
                                $sundayCount++;
                            }
                            // Move to the next day
                            $startDate->modify("+1 day");
                            if(strtolower($basicsalaryinfo->salarytype) == 'monthly' || strtolower($basicsalaryinfo->salarytype) == 'custom')
                            {
                                $basicsalaryinfo->amountperday = 0;
                                if ($basicsalaryinfo->amount == null || $basicsalaryinfo->amount == 0) {
                                    $basicsalaryinfo->amountperday = 0;
                                    $basicsalaryinfo->amountperhour = 0;
                                } else {
                                    if($dates == null){
										if($basicsalaryinfo->amount == 0 || $basicsalaryinfo->amount == null){
											$basicsalaryinfo->amountperday = 0;
											$basicsalaryinfo->amountperhour = 0;
										} else {
											
											$basicsalaryinfo->amountperday = $basicsalaryinfo->amount / 2;
    
											// Check if hoursperday is not 0 to avoid division by zero
											if ($basicsalaryinfo->hoursperday != 0) {
												$basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
											} else {
												// Handle the case when hoursperday is 0
												// You might set amountperhour to 0 or handle it differently based on your requirements
												$basicsalaryinfo->amountperhour = 0;
											}

										}
                                    } else {
                                        // return count($dates) + $saturdayCount;
                                        $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount/2) / (count($dates) + $saturdayCount);

                                        if ($basicsalaryinfo->hoursperday != 0) {
                                            $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                                        } else {
                                            $basicsalaryinfo->amountperhour = 0;
                                        }
                                       
                                    }
                                }
                                
                            }
                            elseif(strtolower($basicsalaryinfo->salarytype) == 'daily')
                            {
                                $basicsalaryinfo->amountperday = $basicsalaryinfo->amount;
                                $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
                                $basicsalaryinfo->amount = ($basicsalaryinfo->amount*count($dates));
                            }
                            elseif(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                            {
                                $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount*$basicsalaryinfo->hoursperday)*count($dates);
                                $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
                            }
                        }
            
                    }
                    $amountPerDay = $basicsalaryinfo->amountperday ?? 0;
                    $hoursPerDay = $basicsalaryinfo->hoursperday ?? 0;
                    if ($amountPerDay == 0 && $hoursPerDay == 0) {
                        $resultperday = 0;
                    } else if($hoursPerDay == 0){
                        $resultperday = 0;
                    }
                    else {
                        $resultperday = $amountPerDay/$hoursPerDay;
                    }
                    // return $basicsalaryinfo->amountperday;
                    $customamtimein = DB::table('employee_customtimesched')
                        ->where('employeeid', $employee->id)
                        ->where('deleted', 0)
                        ->first();

                    // return collect($customamtimein);
                    // if(!$customamtimein)
                    // {
                    //     $customamtimein = (object)array(
                    //         'amin'      => '08:00:00',
                    //         'amout'      => '12:00:00',
                    //         'pmin'      => '13:00:00',
                    //         'pmout'      => '17:00:00',
                    //     );
                    // }
                    if(!$customamtimein)
                    {
                        $customamtimein = [];
                        $totalworkinghours = 0;
                    } else {
                        $amin = new DateTime($customamtimein->amin);
                        $amout = new DateTime($customamtimein->amout);
                        $pmin = new DateTime($customamtimein->pmin);
                        $pmout = new DateTime($customamtimein->pmout);

                        // Calculate morning working hours
                        $morningWorkingHours = $amin->diff($amout);
                        $amhours = $morningWorkingHours->h + ($morningWorkingHours->i / 60); // Convert minutes to fraction of an hour
            
                        // Calculate afternoon working hours
                        $afternoonWorkingHours = $pmin->diff($pmout);
                        $pmhours = $afternoonWorkingHours->h + ($afternoonWorkingHours->i / 60); // Convert minutes to fraction of an hour
            
                        // Calculate total working hours
                        $totalworkinghours = $amhours + $pmhours;
                    }

                    
                    

                    // return $employee->id;
                    $taplogs = \App\Models\HR\HREmployeeAttendance::gethours($alldays, $employee->id);
                    // return $taplogs;
                    

                    $timebrackets = array();
                    // return collect($basicsalaryinfo);
                    if(count($taplogs)>0)
                    {
                        foreach($taplogs as $eachdate)
                        {   
                            $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date,$employeeinfo,$resultperday,$basicsalaryinfo,$taphistory,$hr_attendance);
                            // $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date,$employeeinfo,($basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday),$basicsalaryinfo);
                            // return collect($latedeductiondetail);
                            $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                            $eachdate->lateminutes = $latedeductiondetail->lateminutes;
                            $eachdate->holidayname = '';
							$eachdate->holiday = 0;
                            if(count($latedeductiondetail->brackets)>0)
                            {
                                foreach($latedeductiondetail->brackets as $eachbracket)
                                {
                                    array_push($timebrackets, $eachbracket);
                                }
                            }
                            $eachdate->amountdeduct = 0;
                            // $eachdateatt = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate,$employeeinfo,($basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday),$basicsalaryinfo);
                            // return collect($eachdateatt);
                        }
                    }

                    
                    // $attendance = \App\Models\HR\HREmployeeAttendance::gethours($dates, $request->get('employeeid'));
                    // return collect($attendance);
                    if ($employeeinfo->departmentid) {
                        $tardinessbaseonsalary = DB::table('hr_tardinesscomp')
                            ->where('hr_tardinesscomp.deleted','0')
                            ->where('departmentid', $employeeinfo->departmentid)
                            ->where('baseonattendance', 1)
                            ->where('isactive','1')
                            ->first();
                    }
                    
                    $tardiness_computations = DB::table('hr_tardinesscomp')
                        ->where('hr_tardinesscomp.deleted','0')
                        ->where('hr_tardinesscomp.isactive','1')
                        ->get();

                    $tardinessamount = 0; 
                    $lateduration = 0; 
                    $durationtype = 0; 
                    $tardinessallowance = 0; 
                    $tardinessallowancetype = 0; 

                    if(count($taplogs)>0 && count($tardiness_computations)>0)
                    {
                        foreach($taplogs as $eachatt)
                        {
                            $eachatt->lateamminutes = ($eachatt->lateamhours*60);
                            $eachatt->latepmminutes = ($eachatt->latepmhours*60);
                            $eachatt->lateminutes = ($eachatt->latehours*60);
                            $eachatt->appliedleave = null;

                            //return $eachatt->lateminutes;
                            $eachcomputations = collect($tardiness_computations)->where('latefrom','<=', $eachatt->lateminutes)->where('lateto','>=', $eachatt->lateminutes);
                            //return $eachcomputations;
                            $fromcomputations = collect($tardiness_computations)->where('latefrom','<=', $eachatt->lateminutes)->where('lateto','>=', $eachatt->lateminutes);
                            //return $fromcomputations;
                            $eachcomputations = $eachcomputations->merge($fromcomputations);
                            $eachcomputations = $eachcomputations->unique();
                            //return $eachcomputations;
                            
                            if ($basicsalaryinfo) {
                                if($basicsalaryinfo->attendancebased == 1)
                                {
                                    if(count($eachcomputations)>0)
                                    {
                                        foreach($eachcomputations as $eachcomputation)
                                        {
                                            if($eachcomputation->latetimetype == 1)
                                            {
                                                if($eachcomputation->deducttype == 1)
                                                {
                                                    $eachatt->amountdeduct = $eachcomputation->amount;
                                                }else{
                                                    $eachatt->amountdeduct = ($eachcomputation->amount/100)*$basicsalaryinfo->amountperday;
                                                }
                    
                                            }else
                                            {
                                                $computehours = ($eachatt->lateminutes/60);
                                                if($eachcomputation->deducttype == 1)
                                                {
                                                    $eachatt->amountdeduct = $eachcomputation->amount;
                                                }else{
                                                    $eachatt->amountdeduct = ($eachcomputation->amount/100)*$basicsalaryinfo->amountperday;
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    $eachatt->amountdeduct = 0;
                                }
                            }else{
                                $eachatt->amountdeduct = 0;
                            }
                            
                        }
                    }
                    $latecomputationdetails = (object)array(
                        'tardinessamount'         => $tardinessamount,
                        'lateduration'            => $lateduration,
                        'durationtype'            => $durationtype,
                        'tardinessallowance'      => $tardinessallowance,
                        'tardinessallowancetype'  => $tardinessallowancetype
                    );

                   
                    // return $taplogs;
                    $dayswithattendance = [];
                    if (!empty($dates)) {
                        if ($employeeinfo->employmentstatus == 1 || $employeeinfo->employmentstatus == 2 || $employeeinfo->employmentstatus == 3 || $employeeinfo->employmentstatus == 4 || $employeeinfo->employmentstatus == null) {
                            // if ($employeeinfo->employmentstatus) {
                            // For HOLIDAY
                            // get all the days where basic salary info is equal to 0 or get the rest day
                            $restday = [];
            
                            if ($basicsalaryinfo) {
                                if ($basicsalaryinfo->mondays == 0) {
                                    $restday[] = 'Monday';
                                }
                                if ($basicsalaryinfo->tuesdays == 0) {
                                    $restday[] = 'Tuesday';
                                }
                                if ($basicsalaryinfo->wednesdays == 0) {
                                    $restday[] = 'Wednesday';
                                }
                                if ($basicsalaryinfo->thursdays == 0) {
                                    $restday[] = 'Thursday';
                                }
                                if ($basicsalaryinfo->fridays == 0) {
                                    $restday[] = 'Friday';
                                }
                                if ($basicsalaryinfo->saturdays == 0) {
                                    $restday[] = 'Saturday';
                                }
                                if ($basicsalaryinfo->sundays == 0) {
                                    $restday[] = 'Sunday';
                                }
                            }
                            $datesAbsents = [];
                            $datesAbsences = [];
                            $datesPresent = [];
            
                            foreach ($taplogs as $attendanceData) {
                                $date = $attendanceData->date;
            
                                if ($attendanceData->status == 1) {
                                    $datesPresent[] = $date;
                                } else {
                                    $datesAbsents[] = ['date' => $date];
                                    $datesAbsences[] = $date;
                                }
                            }
                            // get the missing dates from payroll period start and end, usually 15days
                            $startDate = $result->datefrom;
                            $endDate = $result->dateto;
                            $datesInRange = [];
            
                            $currentDate = strtotime($startDate);
                            $endTimestamp = strtotime($endDate);
            
                            while ($currentDate <= $endTimestamp) {
                                $date = date("Y-m-d", $currentDate);
                                $dayName = date("l", $currentDate); // Get the full day name
                                $datesInRange[$date] = $dayName; // Store in an associative array
                                $currentDate = strtotime("+1 day", $currentDate);
                            }
                            
                            // this is the result together with the days name
                            $missingDates = array_diff_key($datesInRange, array_flip($datesPresent));
            
            
                            // compare missing dates if nag match sa gi return nga rest day 
                            $matchingMissingDates = [];
            
                            foreach ($missingDates as $date => $dayName) {
                                if (in_array($dayName, $restday)) {
                                    $matchingMissingDates[$date] = $dayName;
                                }
                            }
                            
                            $matchingDates = array_keys($matchingMissingDates);
                            $attendanceforrestday = \App\Models\HR\HREmployeeAttendance::gethours($matchingDates, $employeeinfo->id,$taphistory,$hr_attendance,$request->get('departmentid'));
            
                            $timebracketsrestday = array();
                            $amountPerDay = $basicsalaryinfo->amountperday ?? 0;
                            $hoursPerDay = $basicsalaryinfo->hoursperday ?? 0;
                            if ($amountPerDay == 0 && $hoursPerDay == 0) {
                                $resultperday = 0;
                            } else if($hoursPerDay == 0){
                                $resultperday = 0;
                            }
                             else {
                                $resultperday = $amountPerDay/$hoursPerDay;
                            }
                            if(count($attendanceforrestday)>0)
                            {
                                foreach($attendanceforrestday as $eachdate)
                                {
                                    $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date,$employeeinfo,$resultperday,$basicsalaryinfo,$taphistory,$hr_attendance);
                                    $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                                    $eachdate->lateminutes = $latedeductiondetail->lateminutes;
                                    $eachdate->restday = 1;
            
                                    if(count($latedeductiondetail->brackets)>0)
                                    {
                                        foreach($latedeductiondetail->brackets as $eachbracket)
                                        {
                                            array_push($timebracketsrestday, $eachbracket);
                                        }
                                    }
                                    $eachdate->amountdeduct = 0;
                                }
                            }
            
                            // return $attendance;
            
                            $rtardiness_computations = DB::table('hr_tardinesscomp')
                                ->where('hr_tardinesscomp.deleted','0')
                                ->where('hr_tardinesscomp.isactive','1')
                                ->get();
                    
                            $rtardinessamount = 0; 
                            $rlateduration = 0; 
                            $rdurationtype = 0; 
                            $rtardinessallowance = 0; 
                            $rtardinessallowance = 0; 
                            
                            if(count($attendanceforrestday)>0 && count($rtardiness_computations)>0)
                            {
                                foreach($attendanceforrestday as $eachatt)
                                {
                                    $eachatt->lateamminutes = ($eachatt->lateamhours*60);
                                    $eachatt->latepmminutes = ($eachatt->latepmhours*60);
                                    $eachatt->lateminutes = ($eachatt->latehours*60);
            
                                    $eachcomputations = collect($rtardiness_computations)->where('latefrom','<=', $eachatt->lateminutes)->where('lateto','>=', $eachatt->lateminutes);
                                    $fromcomputations = collect($rtardiness_computations)->where('latefrom','<=', $eachatt->lateminutes)->where('lateto','>=', $eachatt->lateminutes);
                                    $eachcomputations = $eachcomputations->merge($fromcomputations);
                                    $eachcomputations = $eachcomputations->unique();
                                    
                                    if($basicsalaryinfo->attendancebased == 1)
                                    {
                                        if(count($eachcomputations)>0)
                                        {
                                            foreach($eachcomputations as $eachcomputation)
                                            {
                                                if($eachcomputation->latetimetype == 1)
                                                {
                                                    if($eachcomputation->deducttype == 1)
                                                    {
                                                        $eachatt->amountdeduct = $eachcomputation->amount;
                                                    }else{
                                                        $eachatt->amountdeduct = ($eachcomputation->amount/100)*$basicsalaryinfo->amountperday;
                                                    }
                        
                                                }else
                                                {
                                                    $computehours = ($eachatt->lateminutes/60);
                                                    if($eachcomputation->deducttype == 1)
                                                    {
                                                        $eachatt->amountdeduct = $eachcomputation->amount;
                                                    }else{
                                                        $eachatt->amountdeduct = ($eachcomputation->amount/100)*$basicsalaryinfo->amountperday;
                                                    }
                                                }
                                            }
                                        }
                                    }else{
                                        $eachatt->amountdeduct = 0;
                                    }
                                }
                            }
                            
                            $attendanceArray = json_decode($attendanceforrestday, true);
                            $filteredAttendance = array_filter($attendanceArray, function($record) {
                                return $record['status'] == 1;
                            });
            
                            $datesArrayrestdays = [];
            
                            foreach ($filteredAttendance as $record) {
                                $datesArrayrestdays[] = $record['date'];
                            }
            
                            $dayswithattendance = collect(array());
                            $dayswithattendance = $dayswithattendance->merge($datesPresent);
                            $dayswithattendance = $dayswithattendance->merge($datesArrayrestdays);
            
                            $holidays = DB::table('school_calendar')
                                ->select(
                                    'school_calendar.*',
                                    'schoolcaltype.typename',
                                    'hr_holidaytype.ifwork',
                                    'hr_holidaytype.ifnotwork',
                                    'hr_holidaytype.restdayifwork',
                                    'hr_holidaytype.restdayifnotwork',
                                    'hr_holidaytype.description'
                                )
                                ->leftJoin('schoolcaltype', 'school_calendar.holiday', '=', 'schoolcaltype.id')
                                ->leftJoin('hr_holidaytype', 'school_calendar.holidaytype', '=', 'hr_holidaytype.id')
                                ->where('schoolcaltype.type', 1)
                                ->where('school_calendar.deleted', 0)
                                ->get();
                                
                                $holidaydates = [];
                                foreach ($holidays as $holiday) {
                                    $startDate = new \DateTime($holiday->start);
                                    $endDate = new \DateTime($holiday->end);
            
                                    $interval = new \DateInterval('P1D'); // 1 day interval
                                    $dateRange = new \DatePeriod($startDate, $interval, $endDate);
            
                                    foreach ($dateRange as $date) {
                                        // $dates[] =  $date->format('Y-m-d');
                                        $holidaydates[] = ['date' => $date->format('Y-m-d'),
                                                            'type'       => $holiday->description,
                                                            'holidaytype'       => $holiday->holidaytype,
                                                            'holidayname' => $holiday->title
                                                        ];
            
                                    }
                                }
                             
                                $employeecustomsched = DB::table('employee_customtimesched')
                                    ->where('employeeid', $employeeinfo->id)
                                    ->where('shiftid', '!=', null)
                                    ->where('createdby', '!=', null)
                                    ->where('deleted', 0)
                                    ->first();
            
                                if ($employeecustomsched) {
            
                                    $amin = new DateTime($employeecustomsched->amin);
                                    $amout = new DateTime($employeecustomsched->amout);
                                    $pmin = new DateTime($employeecustomsched->pmin);
                                    $pmout = new DateTime($employeecustomsched->pmout);
            
                                    // Calculate morning working hours
                                    $morningWorkingHours = $amin->diff($amout);
                                    $amhours = $morningWorkingHours->h;
            
                                    // Calculate afternoon working hours
                                    $afternoonWorkingHours = $pmin->diff($pmout);
                                    $pmhours = $afternoonWorkingHours->h;
                                    $totalworkinghours = $amhours + $pmhours;
            
            
            
                                    // return $attendance;
                                    if (count($holidaydates) > 0) {
                                        foreach ($taplogs as $att) {
                                            $att->holiday = 0;
                                            $att->holidayname = "";
            
                                            foreach ($holidaydates as $holidaydate) {
                                                // if ($att->date == $holidaydate['date'] && $att->status == 2 && ($holidaydate['holidaytype'] == 1 || $holidaydate['type'] == 'Regular Holiday')) {
                                                    if ($att->date == $holidaydate['date'] && $att->status == 2) {
                                                    $att->amtimein = $employeecustomsched->amin;
                                                    $att->amtimeout = $employeecustomsched->amout;
                                                    $att->pmtimein = $employeecustomsched->pmin;
                                                    $att->pmtimeout = $employeecustomsched->pmout;
                                                    $att->timeinam = $employeecustomsched->amin;
                                                    $att->timeoutam = $employeecustomsched->amout;
                                                    $att->timeinpm = $employeecustomsched->pmin;
                                                    $att->timeoutpm = $employeecustomsched->pmout;
                                                    $att->amin = $employeecustomsched->amin;
                                                    $att->amout = $employeecustomsched->amout;
                                                    $att->pmin = $employeecustomsched->pmin;
                                                    $att->pmout = $employeecustomsched->pmout;
                                                    $att->status = 1;
                                                    $att->totalworkinghours = $totalworkinghours;
                                                    $att->holiday = 1;
                                                    $att->holidayname = $holidaydate['holidayname'];
                                                    
                                                } else if($att->date == $holidaydate['date'] && $att->status == 1){
													$att->amtimein = $employeecustomsched->amin;
													$att->amtimeout = $employeecustomsched->amout;
													$att->pmtimein = $employeecustomsched->pmin;
													$att->pmtimeout = $employeecustomsched->pmout;
													$att->timeinam = $employeecustomsched->amin;
													$att->timeoutam = $employeecustomsched->amout;
													$att->timeinpm = $employeecustomsched->pmin;
													$att->timeoutpm = $employeecustomsched->pmout;
													$att->amin = $employeecustomsched->amin;
													$att->amout = $employeecustomsched->amout;
													$att->pmin = $employeecustomsched->pmin;
													$att->pmout = $employeecustomsched->pmout;
													$att->status = 1;
													$att->totalworkinghours = $totalworkinghours;
													$att->holiday = 1;
													$att->holidayname = $holidaydate['holidayname'];


													$att->lateminutes = 0;
													$att->lateamminutes = 0;
													$att->latepmminutes = 0;
													$att->lateamhours = 0;
													$att->latepmhours = 0;
													$att->latehours = 0;
													$att->undertimeamhours = 0;
													$att->undertimepmhours = 0;
												
												}
                                            }
                                        }
                                    }
                                }
                                
                                if ($result) {
                                    $datefrom = $result->datefrom;
                                    $dateto = $result->dateto;
                                    $holidays_within_range = [];
            
                                    
                                    foreach ($holidays as $holiday) {
                                        $start_date = strtotime($holiday->start); 
                                        $end_date = strtotime($holiday->end); 
                                
                                        if ($start_date <= strtotime($dateto) && $end_date >= strtotime($datefrom)) {
                                            // Check if the start date of the holiday matches any date in $datesArrayrestdays
                                            if (in_array(date("Y-m-d", $start_date), $datesArrayrestdays)) {
                                                // If it matches, set attendance to 'present'
                                                $holiday->attendance = 'present';
                                            } else {
                                                // If it doesn't match, set attendance to 'not present'
                                                $holiday->attendance = 'not present';
                                            }
                                
                                            // Calculate holiday pay percentage
                                            $duration = ceil(($end_date - $start_date) / (60 * 60 * 24)); // Calculate duration in days
                                
                                            // Calculate the amount per day
                                            // $amountPerDay = floor($basicsalaryinfo->amountperday * 100) / 100;
                                            $amountPerDay = $basicsalaryinfo->amountperday;
                                            $holiday->matching = "";
                                            $holiday->hoursperday = $basicsalaryinfo->hoursperday;
                                            $holiday->duration = $duration; // Add duration as a property
                                            $holiday->amountPerDay = $amountPerDay; // Add amount per day as a property
                                            $holiday->holidaypay = $amountPerDay;
                                            
                                            $holidays_within_range[] = $holiday;
                                        }
                                    }
                                }
                        
                            foreach ($dayswithattendance as $datesp) {
                                if (in_array($datesp, $datesArrayrestdays)) {
                                    $datepresentstart = strtotime($datesp); // Access the date directly since $datesp is a string
                                    foreach ($holidays_within_range as $holiday_within) { // Use &$holiday_within to modify the original array
                                        $start = strtotime($holiday_within->start);
                                        if ($start == $datepresentstart) { // Use == for comparison, not =
                                            $holiday_within->matching = "Matching"; // Add a property to indicate the match
                                            $holiday_within->attendance = 'present';
                                            $holidaypercentage = $holiday_within->restdayifwork / 100;
                                            $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
                                            $holidayperday =  $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
                                            $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                            $holidaywithdurationpay = $holidaywithdurationpay + $holiday_within->amountPerDay;
                                            $holiday_within->holidaypay = round($holidaywithdurationpay, 2); 
                                        }
                                    }
                                }
                                 else {
                                    $datepresentstart = strtotime($datesp); // Access the date directly since $datesp is a string
                                    foreach ($holidays_within_range as $holiday_within) { // Use &$holiday_within to modify the original array
                                        $start = strtotime($holiday_within->start);
                                        if ($start == $datepresentstart) { // Use == for comparison, not =
                                            $holiday_within->matching = "Matching"; // Add a property to indicate the match
                                            $holiday_within->attendance = 'present';
                                            $holidaypercentage = $holiday_within->ifwork / 100;
            
                                            $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
            
                                            $holidayperday =  $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
            
                                            $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                            // $holidaywithdurationpay = $holiday_within->amountPerDay * $holiday_within->duration;
                                            // $holiday_within->holidaypay = number_format($holidaywithdurationpay - .005, 2);
                                            $holiday_within->holidaypay = floor($holidaywithdurationpay * 100) / 100 ;
            
                                        }
                                    }
                                }
                            }
            
                            foreach ($datesAbsences as $datesp) {
                                $dateabsentstart = strtotime($datesp);
                                foreach ($holidays_within_range as $holiday_within) {
                                    if ($holiday_within->ifnotwork > 0) {
                                        $start = strtotime($holiday_within->start);
                                        if ($start == $dateabsentstart) { 
                                            $holiday_within->matching = "Matching";
                                            $holidaypercentage = $holiday_within->ifwork / 100;
                                            $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
                                            $holidayperday =  $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
                                            $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                            $holiday_within->holidaypay = floor($holidaywithdurationpay * 100) / 100;
                                        }
                                    }
                                    
                                }
                            }
            
            
                            // Get the total holiday payment
                            $totalPresentHolidaypay = 0; // Initialize the counter
            
            
                            foreach ($holidays_within_range as $holidaypayments) {
                                if ($holidaypayments->matching == 'Matching') {
                                    $totalPresentHolidaypay += $holidaypayments->holidaypay; // Increment the counter
                                }
                                
                            }
                            
            
                            $holidays_within_range = json_decode(json_encode($holidays_within_range), true);
                         
                            $filtered_holidays = $holidays_within_range;
                
                        } else {
                            foreach ($standardallowances as &$sallowancestandard) {
            
                                if ($sallowancestandard->baseonattendance == 1) {
                                    $amountPerDay = $sallowancestandard->amountperday;
                                    $totalDaysAmount = $amountPerDay * ( count($dayswithattendance) + $saturdayCount);
                                    $sallowancestandard->totalDaysAmount = $totalDaysAmount;
            
                                    //$sallowancestandard->totalDayspresent = count($dayswithattendance);
                                    $sallowancestandard->totalDayspresent = collect($attendance)->where('totalworkinghours','>',0)->count() + $saturdayCount;
                                } else {
                                    $sallowancestandard->totalDaysAmount = 0;
                                }
                            }
                            unset($sallowancestandard); // Unset the reference to the last element
                        }
                    }
                    foreach ($taplogs as $item) {
                        $item->exceed8hours = $item->totalworkinghours >= 8 ? 1 : 0;
                    }
            
                    // Now you can safely use count() on $dayswithattendance
                    $totaldayspresent = count($dayswithattendance);
                    
                    
                    // return $taplogs;
                   
                    $totalLateHours = 0;
                    $totalUndertimeHours = 0;
                    $totalworkinghoursrender = 0;
                    $flexihours = 0;
                    $flexihoursundertime = 0;
            
                    foreach ($taplogs as $entry) {
                        if ($entry->totalworkinghours != 0 && $entry->totalworkinghours !== null && $entry->amin !== null && $entry->pmout !== null) {
                            // if ($entry->totalworkinghours != 0 && $entry->totalworkinghours !== null) {
                                // return $entry->date;
                            $totalLateHours = $entry->latehours;
                            $totalUndertimeHours = $entry->undertimehours;
                            $totalWorkingHoursRender = 8 - ($totalLateHours + $totalUndertimeHours);
                            $entry->totalworkinghoursrender = $totalWorkingHoursRender;
            
                            $flexihours = $entry->totalworkinghoursflexi;
                            $flexihoursundertime = 8 - $flexihours;
                            
                            if ($flexihours > 8) {
                                $entry->flexihours = 8;
                            } else {
                                // $entry->flexihours = number_format($flexihours - .005, 2);
                                $entry->flexihours = $flexihours;
                            }
                            if ($flexihoursundertime < 0) {
                                $entry->flexihoursundertime = 0;
                            } else {
                                $entry->flexihoursundertime = $flexihoursundertime;
                            }
                        } else {
                            $entry->totalworkinghoursrender = 0;
                            $entry->flexihours = 0;
                            $entry->flexihoursundertime = 0;
                        }
                    }

                    
                    if(count($taplogs)>0){
                        foreach ($taplogs as $lognull) {
                            $lognull->leavetype = '';
                            $lognull->leavedaystatus = '';
                            $lognull->daycoverd = '';
                            $lognull->leaveremarks = '';
                            if (($lognull->timeinam == null && $lognull->timeoutpm == null) || ($lognull->amtimein == null && $lognull->pmtimeout == null)) {
                                // $lognull->timeoutam = null;
                                // $lognull->timeinpm = null;
                            } else {
                                if ($lognull->timeinam != null && $lognull->timeoutpm == null) {
                                    // $lognull->timeoutam = null;
                                    // $lognull->timeinpm = null;
                                }
                            }


                            if (count($leavesappr)>0) {

                                // return $employeeleavesappr;

                                foreach ($employeeleavesappr as $employeeleavesapp) {
                                    if ($employeeleavesapp->ldate == $lognull->date) {
                                        $lognull->leavetype = $employeeleavesapp->leave_type;
                                        $lognull->leavedaystatus = $employeeleavesapp->halfday;
                                        $lognull->daycoverd = $employeeleavesapp->daycoverd;
                                        $lognull->leaveremarks = $employeeleavesapp->remarks;

                                        // if ($lognull->daycoverd == ) {
                                        //     # code...
                                        // }
                                    }
                                }
                            }
                        }
    
                    }
                    // return $taplogs;
                    // return collect($customamtimein);
                    
                    if(count($taplogs)>0)
                    {   
                        if ($customamtimein) {

                            $amin = new DateTime($customamtimein->amin);
                            $amout = new DateTime($customamtimein->amout);
                            $pmin = new DateTime($customamtimein->pmin);
                            $pmout = new DateTime($customamtimein->pmout);
                
                            // Calculate morning working hours
                            $morningWorkingHours = $amin->diff($amout);
                            $amhours = $morningWorkingHours->h + ($morningWorkingHours->i / 60); // Convert minutes to fraction of an hour
                
                            // Calculate afternoon working hours
                            $afternoonWorkingHours = $pmin->diff($pmout);
                            $pmhours = $afternoonWorkingHours->h + ($afternoonWorkingHours->i / 60); // Convert minutes to fraction of an hour
                
                            // Calculate total working hours
                            $totalworkinghours = $amhours + $pmhours;
                            
                            // return collect($basicsalaryinfo);
                
                            if ($basicsalaryinfo) {
                                if ($basicsalaryinfo->halfdaysat == 1) {
                                    foreach ($taplogs as $attt) {
                                        if ($attt->pmin != null || $attt->pmout != null) {
                                            if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                                            
                                                $attt->amtimein = $customamtimein->amin;
                                                $attt->amtimeout = $customamtimein->amout;
                                                $attt->timeinam = $customamtimein->amin;
                                                $attt->timeoutam = $customamtimein->amout;
                                                $attt->amin = $customamtimein->amin;
                                                $attt->amout = $customamtimein->amout;
                                                $attt->status = 1;
                                             
                    
                                                if ($attt->pmin == null || $attt->pmout == null ) {
                                                    $attt->totalworkinghours = $amhours;
                                                    $attt->totalworkinghoursrender = $amhours;
                                                    $attt->undertimepmhours = $pmhours;
                    
                                                } else {
                                                    $attt->totalworkinghours = $amhours + ($amhours - $attt->latepmhours);
                                                    $attt->totalworkinghoursrender = $amhours + ($amhours - $attt->latepmhours);
                                                    $attt->lateamminutes = 0;
                                                    $attt->lateamhours = 0;
                                                    $attt->undertimeamhours = 0;
                                                }
                                             
                                                
                                            }
                                        }
                                        
                                    }
                                } else if($basicsalaryinfo->halfdaysat == 2){
                                    foreach ($taplogs as $attt) {
                                        if ($attt->amin != null || $attt->amout != null) {
                                            if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                                                $attt->pmtimein = $customamtimein->pmin;
                                                $attt->pmtimeout = $customamtimein->pmout;
                                                $attt->timeinpm = $customamtimein->pmin;
                                                $attt->timeoutpm = $customamtimein->pmout;
                                                $attt->pmin = $customamtimein->pmin;
                                                $attt->pmout = $customamtimein->pmout;
                                                $attt->status = 1;
                    
                                                if ($attt->amin == null || $attt->amout == null ) {
                                                    $attt->totalworkinghours = $pmhours;
                                                    $attt->totalworkinghoursrender = $pmhours;
                                                    $attt->lateamminutes = $pmhours * 60;
                                                    $attt->lateamhours = $pmhours;
                                                    // $attt->undertimeamhours = $pmhours;
                                                } else {
                                                    $attt->totalworkinghours = $pmhours + ($amhours - $attt->lateamhours);
                                                    $attt->totalworkinghoursrender = $pmhours + ($amhours - $attt->lateamhours);
                                                    $attt->latepmminutes = 0;
                                                    $attt->latepmhours = 0;
                                                    $attt->undertimepmhours = 0;
                                                }
                                            }
                                        }
                                    }
                                  
                                }
                            } 
                            // return $taplogs;
                            // return $employeeleavesappr;
                            // return collect($basicsalaryinfo);
                            foreach ($taplogs as $att) {
                                $att->lateamminutes = 0;
                                $att->latepmminutes = 0;

                                $att->appliedleave = 0;
                                if ($att->leavedaystatus === 0) {
                                    $att->amtimein = $customamtimein->amin;
                                    $att->amtimeout = $customamtimein->amout;
                                    $att->pmtimein = $customamtimein->pmin;
                                    $att->pmtimeout = $customamtimein->pmout;
                                    $att->timeinam = $customamtimein->amin;
                                    $att->timeoutam = $customamtimein->amout;
                                    $att->timeinpm = $customamtimein->pmin;
                                    $att->timeoutpm = $customamtimein->pmout;
                                    $att->amin = $customamtimein->amin;
                                    $att->amout = $customamtimein->amout;
                                    $att->pmin = $customamtimein->pmin;
                                    $att->pmout = $customamtimein->pmout;
                                    $att->status = 1;
                                    $att->totalworkinghours = $totalworkinghours;
                                    $att->appliedleave = 1;
                                  
                                    $att->lateamminutes = 0;
                                    $att->latepmminutes = 0;
                                    $att->lateamhours = 0;
                                    $att->undertimeamhours = 0;
                                    $att->undertimepmhours = 0;

                                } else if($att->leavedaystatus == 1){

                                    $att->amtimein = $customamtimein->amin;
                                    $att->amtimeout = $customamtimein->amout;
                                    $att->timeinam = $customamtimein->amin;
                                    $att->timeoutam = $customamtimein->amout;
                                    $att->amin = $customamtimein->amin;
                                    $att->amout = $customamtimein->amout;
                                    $att->status = 1;
                                   
                                    $att->totalworkinghours = number_format($att->totalworkinghours + $amhours, 2);
                                    // $att->totalworkinghours = floor(($att->totalworkinghours + $amhours) * 100) / 100;
                                    $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $att->lateamhours) * 100) / 100;
                                    $att->totalworkinghoursflexi = floor(($att->totalworkinghoursflexi + $att->lateamhours) * 100) / 100;
                                    $att->flexihours = floor(($att->flexihours + $att->lateamhours) * 100) / 100;
                                    $att->lateamminutes = 0;
                                    $att->lateamhours = 0;
                                    $att->undertimeamhours = 0;
                                    // if ($att->date == "2024-02-16") {
                                    //     return $att->totalworkinghours;
                                    // }
                                   
                                } else if($att->leavedaystatus == 2){
                                    $att->pmtimein = $customamtimein->pmin;
                                    $att->pmtimeout = $customamtimein->pmout;
                                    $att->timeinpm = $customamtimein->pmin;
                                    $att->timeoutpm = $customamtimein->pmout;
                                    $att->pmin = $customamtimein->pmin;
                                    $att->pmout = $customamtimein->pmout;
                                    $att->status = 1;
    
                                    
                                    $att->totalworkinghours = floor(($att->totalworkinghours + $att->latepmhours) * 100) / 100;
                                    $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $att->latepmhours) * 100) / 100;
                                    $att->totalworkinghoursflexi = floor(($att->totalworkinghoursflexi + $att->latepmhours) * 100) / 100;
                                    $att->flexihours = floor(($att->flexihours + $att->latepmhours) * 100) / 100;
    
                                    $att->latepmminutes = 0;
                                    $att->latepmhours = 0;
                                    $att->undertimepmhours = 0;
                                }
                            }
                            
                            // return $taplogs;
                            foreach ($taplogs as $attendancefinal) {
                
                                $amlate = floatval($attendancefinal->lateamminutes);
                                $pmlate = floatval($attendancefinal->latepmminutes);
                                $amlatehours = floatval($attendancefinal->lateamhours);
                                $pmlatehours = floatval($attendancefinal->latepmhours);
                                $amundertime = floatval($attendancefinal->undertimeamhours);
                                $pmundertime = floatval($attendancefinal->undertimepmhours);
                    
                            
                            
                                // return ($amhours - $amundertime) * 60;
                                if ($attendancefinal->amin == null && $attendancefinal->amout != null) {
                                    $attendancefinal->lateamminutes = 0; // Initialize latepmminutes
                                    // $totalworkinghoursfinal = $totalworkinghours - $attendancefinal->totalworkinghours;
                                    $attendancefinal->lateamminutes = ($amhours - $amundertime) * 60;
                
                                    // return $attendancefinal->lateamminutes;
                                }
                                else if ($attendancefinal->amin != null && $attendancefinal->amout == null) {
                                    
                                    $attendancefinal->undertimeamhours = 0; // Initialize lateamminutes
                                    $attendancefinal->lateamminutes = ($amhours - $amlatehours) * 60;
                    
                                } 
                                 else if ($attendancefinal->pmin == null && $attendancefinal->pmout != null) {
                                    
                                    $attendancefinal->latepmminutes = 0; // Initialize latepmminutes
                                    
                                    $attendancefinal->latepmminutes = ($pmhours - $pmundertime) * 60;
                    
                                }  else if ($attendancefinal->pmin != null && $attendancefinal->pmout == null) {
                                    $attendancefinal->undertimepmhours = 0; // Initialize latepmminutes
                                    
                                    $attendancefinal->undertimepmhours = $pmhours - $pmlatehours;
                                    
                                } 
                    
                                $attendancefinal->latehours = ($attendancefinal->lateamminutes + $attendancefinal->latepmminutes) / 60;
                                $attendancefinal->lateminutes = $attendancefinal->lateamminutes + $attendancefinal->latepmminutes;
                                $attendancefinal->undertimehours = $attendancefinal->undertimeamhours + $attendancefinal->undertimepmhours;
                                
                            }
                        }

                        // return $taplogs;

                        // {{$log->latehours}}h{{$log->lateminutes}}m
                        foreach($taplogs as $log)
                        {
                            
                            array_push($summarylogs, (object)array(
                                'dateint'      => $log->dayint,
                                'timeinam'      => $log->timeinam,
                                'timeinpm'      => $log->timeinpm,
                                'timeoutam'      => $log->timeoutam,
                                'timeoutpm'      => $log->timeoutpm,
                                'remarks'      => $log->remarks,
                                'date'      => $log->date,
                                'day'      => $log->day,
                                'logs'      => $log->logs,
                                'latehours'     => floor($log->latehours),
                                'lateminutes'     => floor(round(($log->latehours - floor($log->latehours))*60)),
                                'totalworkinghours'     => $log->totalworkinghours,
                                'totalworkinghoursflexi'     => $log->totalworkinghoursflexi,
                                'hours'     => floor($log->totalworkinghours),
                                'minutes'   => floor(($log->totalworkinghours - floor($log->totalworkinghours))*60),
                                'leavetype'   => $log->leavetype,
                                'leavedaystatus'   => $log->leavedaystatus,
                                'daycoverd'   => $log->daycoverd,
                                'leaveremarks'   =>  $log->daycoverd,
                                'holiday' => $log->holiday ?? "",
                                'holidayname' => $log->holidayname
                            ));
                        }
                    }
                    // foreach($alldays as $day)
                    // {
                    //     $taphistory = DB::table('taphistory')
                    //         ->where('studid', $employee->id)
                    //         ->where('deleted','0')
                    //         ->where('tdate', $day)
                    //         ->where('utype', '!=', 7)
                    //         ->orderBy('ttime','asc')
                    //         ->get();

                    //     if(count($taphistory)>0)
                    //     {
                    //         foreach($taphistory as $tapatt)
                    //         {
                    //             $tapatt->mode = 0;
                    //         }
                    //     }

                    //     $hr_attendance = DB::table('hr_attendance')
                    //         ->where('studid', $employee->id)
                    //         ->where('tdate', $day)
                    //         ->where('deleted',0)
                    //         ->orderBy('ttime','asc')
                    //         ->get();

                    //     if(count($hr_attendance)>0)
                    //     {
                    //         foreach($hr_attendance as $hratt)
                    //         {
                    //             $hratt->mode = 1;
                    //         }
                    //     }


                    //     $logs = collect();
                    //     $logs = $logs->merge($taphistory);
                    //     $logs = $logs->merge($hr_attendance);
                    //     $logs = $logs->sortBy('ttime');
                    //     $logs = $logs->unique('ttime');
                        
                    //     $hours = 0;
                    //     $minutes = 0; 
                    //     $latehours = 0;
                    //     $lateminutes = 0;
                    //     $timeinam = collect($logs)->where('tapstate','IN')->first()->ttime ?? '';
                    //     $timeinpm = collect($logs)->where('ttime','>=',$customamtimein->amout)->where('tapstate','IN')->first()->ttime ?? '';
                    //     $timeoutam = collect($logs)->where('ttime','<=',$timeinpm)->where('tapstate','OUT')->last()->ttime ?? '';
                    //     $timeoutpm = collect($logs)->where('ttime','>',$timeinpm)->where('tapstate','OUT')->last()->ttime ?? '';
                    //     if(count($logs)>0)
                    //     {
                            
                    //         if(collect($logs)->where('ttime','<','12:00:00')->count() > 0)
                    //         {
                    //             if(collect($logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first())
                    //             {
                    //                 $amttimeamin = collect($logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first()->ttime;
                    //                 $amtimein = $day.' '.$amttimeamin;
                    //             }else{
                                    
                    //                 if($customamtimein)
                    //                 {
                    //                     $amttimeamin = $customamtimein->amin;
                    //                     $amtimein = $day.' '.$customamtimein->amin;
                    //                 }else{
                    //                     $amttimeamin = '08:00:00';
                    //                     $amtimein = $day.' 08:00:00';
                    //                 }
                    //             }

                    //             $am_customtimein = new DateTime($amtimein);
                    //             $am_latetimein = new DateTime($day.' '.$customamtimein->amin);
                    //             $amlateinterval = $am_customtimein->diff($am_latetimein);
                    //             $lateh = $amlateinterval->format('%h');
                    //             $latem = $amlateinterval->format('%i');
                                
                    //             if($amttimeamin>$customamtimein->amin)
                    //             {
                    //                 $latehours+=$lateh;
                    //                 $lateminutes+=$latem;
                    //             }

                    //             if(collect($logs)->where('ttime','<=',$timeinpm)->where('tapstate','OUT')->last())
                    //             {
                    //                 $amtimeout = $day.' '.collect($logs)->where('ttime','<=',$timeinpm)->where('tapstate','OUT')->last()->ttime;
                    //             }else{
                                    
                    //                 $customamtimeout = DB::table('employee_customtimesched')
                    //                     ->where('employeeid', $employee->id)
                    //                     ->where('deleted', 0)
                    //                     ->first();
                
                    //                 if($customamtimeout)
                    //                 {
                    //                     $amtimeout = $day.' '.$customamtimeout->amout;
                    //                 }else{
                    //                     $amtimeout = $day.' 12:00:00';
                    //                 }
                    //             }
                                
                    //             $am_timein = new DateTime($amtimein);
                    //             $am_timeout = new DateTime($amtimeout);
                    //             $aminterval = $am_timein->diff($am_timeout);
                                 
                    //             $hours += $aminterval->format('%h');
                    //             $minutes += $aminterval->format('%i');
                    //         }
                            
                    //         /////
                    //         // return collect($logs)->where('ttime','>=','12:00:00')->count();
                    //         if(collect($logs)->where('ttime','>=','12:00:00')->count() > 0)
                    //         {
                    //             if(collect($logs)->where('tapstate','IN')->where('ttime','>=','12:00:00')->first())
                    //             {
                    //                 $pmtimein = $day.' '.collect($logs)->where('tapstate','IN')->where('ttime','>=','12:00:00')->first()->ttime;
                    //             }else{
                                    
                    //                 $custompmtimein = DB::table('employee_customtimesched')
                    //                     ->where('employeeid', $employee->id)
                    //                     ->where('deleted', 0)
                    //                     ->first();
                
                    //                 if($custompmtimein)
                    //                 {
                    //                     $pmtimein = $day.' '.$custompmtimein->pmin;
                    //                 }else{
                    //                     $pmtimein = $day.' 13:00:00';
                    //                 }
                    //             }
                    //             if(collect($logs)->where('tapstate','OUT')->where('ttime','>=','12:00:00')->last())
                    //             {
                    //                 $pmtimeout = $day.' '.collect($logs)->where('tapstate','OUT')->where('ttime','>=','12:00:00')->last()->ttime;
                    //             }else{
                                    
                    //                 $custompmtimeout = DB::table('employee_customtimesched')
                    //                     ->where('employeeid', $employee->id)
                    //                     ->where('deleted', 0)
                    //                     ->first();
                
                    //                 if($custompmtimeout)
                    //                 {
                    //                     $pmtimeout = $day.' '.$custompmtimeout->pmout;
                    //                 }else{
                    //                     $pmtimeout = $day.' 17:00:00';
                    //                 }
                    //             }
                                
                    //             $pm_timein = new DateTime($pmtimein);
                    //             $pm_timeout = new DateTime($pmtimeout);
                    //             $pminterval = $pm_timein->diff($pm_timeout);
                                 
                    //             $hours += $pminterval->format('%h');
                    //             $minutes += $pminterval->format('%i');
                    //         }
            
                    //     }
            
                    //     while($minutes>=60)
                    //     {
                    //         $hours+=1;
                    //         $minutes-=60;
                    //     }
                    //     while($lateminutes>=60)
                    //     {
                    //         $latehours+=1;
                    //         $lateminutes-=60;
                    //     }
                    //     $employee->amin = $customamtimein->amin;
                    //     $employee->amout = $customamtimein->amout;
                    //     $employee->pmin = $customamtimein->pmin;
                    //     $employee->pmout = $customamtimein->pmout;
                        
                    //     $timeinam = collect($logs)->where('tapstate','IN')->first()->ttime ?? '';
                    //     $timeinpm = collect($logs)->where('ttime','>=',$customamtimein->amout)->where('tapstate','IN')->first()->ttime ?? '';
                    //     $timeoutam = collect($logs)->where('ttime','<=',$timeinpm)->where('tapstate','OUT')->last()->ttime ?? '';
                    //     $timeoutpm = collect($logs)->where('ttime','>',$timeinpm)->where('tapstate','OUT')->last()->ttime ?? '';
                    //     $remarks = DB::table('hr_attendanceremarks')
                    //         ->where('tdate',$day)
                    //         ->where('employeeid',  $employee->id)
                    //         ->where('deleted','0')
                    //         ->first();
                    //     array_push($summarylogs, (object)array(
                    //         'timeinam'      => $timeinam,
                    //         'timeinpm'      => $timeinpm,
                    //         'timeoutam'      => $timeoutam,
                    //         'timeoutpm'      => $timeoutpm,
                    //         'remarks'      => $remarks,      
                    //         'date'      => $day,
                    //         'logs'      => $logs,
                    //         'latehours'     => $latehours,
                    //         'lateminutes'     => $lateminutes,
                    //         'hours'     => $hours,
                    //         'minutes'   => $minutes,
                    //     ));
                    // }

                    $employee->logs = $summarylogs;
                    // return collect($employee);
                    // return collect($employee);
                }
            }


        }
        elseif(count($request->get('id')) == 1){
            // return $request->get('id');
            // $employeeleavesappr = collect($leavesappr)->whereIn('employeeid', $request->get('id'))->where('leavestatus', 1)->values();
            $employeeleavesappr = collect($leavesappr)->whereIn('employeeid', $request->get('id'))->values();
            $dateRange = explode(" - ", $request->get('dates'));
            
            $datefrom = new DateTime($dateRange[0]);
            $dateto = new DateTime($dateRange[1]);
            $result = (object) [
                'datefrom' => $datefrom->format('Y-m-d'),
                'dateto' => $dateto->format('Y-m-d'),
            ];

            // return collect($result);
            $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
                ->select('employee_basicsalaryinfo.*','employee_basistype.type as salarytype','employee_basistype.type as ratetype')
                ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                ->where('employee_basicsalaryinfo.deleted','0')
                ->where('employee_basicsalaryinfo.employeeid', $request->get('id'))
                ->first();

            $employeeinfo = DB::table('teacher')
                ->select('teacher.*','employee_personalinfo.gender','utype','teacher.id as employeeid','employee_personalinfo.departmentid')
                ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                ->where('teacher.id', $request->get('id'))
                ->where('teacher.deleted','0')
                ->first();

            $taphistory = DB::table('taphistory')
                // ->where('tdate', $dates)
                ->where('studid', $request->get('id'))
                ->whereBetween('tdate', [$result->datefrom,$result->dateto])
                ->where('utype', '!=','7')
                ->orderBy('ttime','asc')
                ->where('deleted','0')
                ->get();
    
            $hr_attendance = DB::table('hr_attendance')
                // ->where('tdate', $dates)
                ->where('studid',$request->get('id'))
                ->whereBetween('tdate', [$result->datefrom,$result->dateto])
                ->where('deleted',0)
                ->orderBy('ttime','asc')
                ->get();

            $dates = array();


            // return collect($employeeinfo);
            $customamtimein = DB::table('employee_customtimesched')
                ->where('employeeid', $request->get('id')[0])
                ->where('deleted', 0)
                ->first();

            // if(!$customamtimein)
            // {
            //     $customamtimein = (object)array(
            //         'amin'      => '08:00:00',
            //         'amout'      => '12:00:00',
            //         'pmin'      => '13:00:00',
            //         'pmout'      => '17:00:00',
            //     );
            // }
            $summarylogs = array();
            if ($basicsalaryinfo) {
                $employeeinfo->ratetype = $basicsalaryinfo->ratetype;
            } else {
                $employeeinfo->ratetype = null;
            }
            if($basicsalaryinfo)
            {
            
                $interval = new DateInterval('P1D');    
                $realEnd = new DateTime($result->dateto);
                $realEnd->add($interval);    
                $period = new DatePeriod(new DateTime($result->datefrom), $interval, $realEnd);    
                // return collect($period);
                foreach($period as $date) {  
                        if(strtolower($date->format('l')) == 'monday')
                        {
                            if($basicsalaryinfo->mondays == 1)
                            {
                                $dates[] = $date->format('Y-m-d'); 
                            }
                        }   
                        elseif(strtolower($date->format('l')) == 'tuesday')
                        {
                            if($basicsalaryinfo->tuesdays == 1)
                            {
                                $dates[] = $date->format('Y-m-d'); 
                            }
                        }   
                        elseif(strtolower($date->format('l')) == 'wednesday')
                        {
                            if($basicsalaryinfo->wednesdays == 1)
                            {
                                $dates[] = $date->format('Y-m-d'); 
                            }
                        }   
                        elseif(strtolower($date->format('l')) == 'thursday')
                        {
                            if($basicsalaryinfo->thursdays == 1)
                            {
                                $dates[] = $date->format('Y-m-d'); 
                            }
                        }   
                        elseif(strtolower($date->format('l')) == 'friday')
                        {
                            if($basicsalaryinfo->fridays == 1)
                            {
                                $dates[] = $date->format('Y-m-d'); 
                            }
                        }   
                        elseif(strtolower($date->format('l')) == 'saturday')
                        {
                            if($basicsalaryinfo->saturdays == 1)
                            {
                                $dates[] = $date->format('Y-m-d'); 
                            }
                        }   
                        elseif(strtolower($date->format('l')) == 'sunday')
                        {
                            if($basicsalaryinfo->sundays == 1)
                            {
                                $dates[] = $date->format('Y-m-d'); 
                            }
                        }  
                }
                
                $startDate = new DateTime($result->datefrom);
                $endDate = new DateTime($result->dateto);
        
                $mondayCount = 0;
                $tuesdayCount = 0;
                $wednesdayCount = 0;
                $thursdayCount = 0;
                $fridayCount = 0;
                $saturdayCount = 0;
                $sundayCount = 0;
                
        
                // Iterate through the date range
                while ($startDate <= $endDate) {
                    // Get the day of the week as a lowercase string (e.g., "monday")
                    $day_of_week = strtolower($startDate->format("l"));
                    // Check if the day is Monday
                    if ($day_of_week === "monday") {
                        $mondayCount++;
                    } elseif ($day_of_week === "tuesday") {
                        $tuesdayCount++;
                    } elseif ($day_of_week === "wednesday"){
                        $wednesdayCount++;
                    } elseif ($day_of_week === "thursday"){
                        $thursdayCount++;
                    } elseif ($day_of_week === "friday"){
                        $fridayCount++;
                    } elseif ($day_of_week === "saturday"){
                        $saturdayCount++;
                    } elseif ($day_of_week === "sunday"){
                        $sundayCount++;
                    }
                    // Move to the next day
                    $startDate->modify("+1 day");
                    if(strtolower($basicsalaryinfo->salarytype) == 'monthly' || strtolower($basicsalaryinfo->salarytype) == 'custom')
                    {
                            //return $dates;
                        if ($basicsalaryinfo->amount == null || $basicsalaryinfo->amount == 0) {
                            $basicsalaryinfo->amountperday = 0;
                            $basicsalaryinfo->amountperhour = 0;
                        } else {
                            //if($dates == null){
                                //$basicsalaryinfo->amountperday = ($basicsalaryinfo->amount/2);
                                //$basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
                            //} else {
                                //$basicsalaryinfo->amountperday = ($basicsalaryinfo->amount/2) / (count($dates) + $saturdayCount);
                                //$basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
                            //}
							if($basicsalaryinfo->amount == 0 || $basicsalaryinfo->amount == null){
								$basicsalaryinfo->amountperday = 0;
								$basicsalaryinfo->amountperhour = 0;
							} else {
								
								$basicsalaryinfo->amountperday = $basicsalaryinfo->amount / 2;

								// Check if hoursperday is not 0 to avoid division by zero
								if ($basicsalaryinfo->hoursperday != 0) {
									$basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
								} else {
									// Handle the case when hoursperday is 0
									// You might set amountperhour to 0 or handle it differently based on your requirements
									$basicsalaryinfo->amountperhour = 0;
								}

							}
                        }
                        
                    }
                    elseif(strtolower($basicsalaryinfo->salarytype) == 'daily')
                    {
                        $basicsalaryinfo->amountperday = $basicsalaryinfo->amount;
                        $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
                        $basicsalaryinfo->amount = ($basicsalaryinfo->amount*count($dates));
                    }
                    elseif(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                    {
                        $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount*$basicsalaryinfo->hoursperday)*count($dates);
                        $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
                    }
                }
    
            }
            
            $amountPerDay = $basicsalaryinfo->amountperday ?? 0;
            $hoursPerDay = $basicsalaryinfo->hoursperday ?? 0;
            if ($amountPerDay == 0 && $hoursPerDay == 0) {
                $resultperday = 0;
            } else if($hoursPerDay == 0){
                $resultperday = 0;
            }
                else {
                $resultperday = $amountPerDay/$hoursPerDay;
            }

            $amin = new DateTime($customamtimein->amin);
            $amout = new DateTime($customamtimein->amout);
            $pmin = new DateTime($customamtimein->pmin);
            $pmout = new DateTime($customamtimein->pmout);
            // Calculate morning working hours
            $morningWorkingHours = $amin->diff($amout);
            $amhours = $morningWorkingHours->h + ($morningWorkingHours->i / 60); // Convert minutes to fraction of an hour

            // Calculate afternoon working hours
            $afternoonWorkingHours = $pmin->diff($pmout);
            $pmhours = $afternoonWorkingHours->h + ($afternoonWorkingHours->i / 60); // Convert minutes to fraction of an hour

            // Calculate total working hours
            $totalworkinghours = $amhours + $pmhours;

            // return $employee->id;
            // return $leavesappr;
            $taplogs = \App\Models\HR\HREmployeeAttendance::gethours($alldays, $request->get('id'));
            // return $taplogs;

            $timebrackets = array();
            if(count($taplogs)>0)
            {
                foreach($taplogs as $eachdate)
                {   
                    $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date,$employeeinfo,$resultperday,$basicsalaryinfo,$taphistory,$hr_attendance);
                    // $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date,$employeeinfo,($basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday),$basicsalaryinfo);
                    // return collect($latedeductiondetail);
                    $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                    $eachdate->lateminutes = $latedeductiondetail->lateminutes;
                    $eachdate->holidayname = '';
					$eachdate->holiday = 0;
                    if(count($latedeductiondetail->brackets)>0)
                    {
                        foreach($latedeductiondetail->brackets as $eachbracket)
                        {
                            array_push($timebrackets, $eachbracket);
                        }
                    }
                    $eachdate->amountdeduct = 0;
                    // $eachdateatt = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate,$employeeinfo,($basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday),$basicsalaryinfo);
                    // return collect($eachdateatt);
                }
            }
            // $attendance = \App\Models\HR\HREmployeeAttendance::gethours($dates, $request->get('employeeid'));
            // return collect($attendance);
            if ($employeeinfo->departmentid) {
                $tardinessbaseonsalary = DB::table('hr_tardinesscomp')
                    ->where('hr_tardinesscomp.deleted','0')
                    ->where('departmentid', $employeeinfo->departmentid)
                    ->where('baseonattendance', 1)
                    ->where('isactive','1')
                    ->first();
            }
            
            $tardiness_computations = DB::table('hr_tardinesscomp')
                ->where('hr_tardinesscomp.deleted','0')
                ->where('hr_tardinesscomp.isactive','1')
                ->get();

            $tardinessamount = 0; 
            $lateduration = 0; 
            $durationtype = 0; 
            $tardinessallowance = 0; 
            $tardinessallowancetype = 0; 

            if(count($taplogs)>0 && count($tardiness_computations)>0)
            {
                foreach($taplogs as $eachatt)
                {
                    $eachatt->lateamminutes = ($eachatt->lateamhours*60);
                    $eachatt->latepmminutes = ($eachatt->latepmhours*60);
                    $eachatt->lateminutes = ($eachatt->latehours*60);
                    $eachatt->appliedleave = null;

                    //return $eachatt->lateminutes;
                    $eachcomputations = collect($tardiness_computations)->where('latefrom','<=', $eachatt->lateminutes)->where('lateto','>=', $eachatt->lateminutes);
                    //return $eachcomputations;
                    $fromcomputations = collect($tardiness_computations)->where('latefrom','<=', $eachatt->lateminutes)->where('lateto','>=', $eachatt->lateminutes);
                    //return $fromcomputations;
                    $eachcomputations = $eachcomputations->merge($fromcomputations);
                    $eachcomputations = $eachcomputations->unique();
                    //return $eachcomputations;
                    
                    if ($basicsalaryinfo) {
                        if($basicsalaryinfo->attendancebased == 1)
                        {
                            if(count($eachcomputations)>0)
                            {
                                foreach($eachcomputations as $eachcomputation)
                                {
                                    if($eachcomputation->latetimetype == 1)
                                    {
                                        if($eachcomputation->deducttype == 1)
                                        {
                                            $eachatt->amountdeduct = $eachcomputation->amount;
                                        }else{
                                            $eachatt->amountdeduct = ($eachcomputation->amount/100)*$basicsalaryinfo->amountperday;
                                        }
            
                                    }else
                                    {
                                        $computehours = ($eachatt->lateminutes/60);
                                        if($eachcomputation->deducttype == 1)
                                        {
                                            $eachatt->amountdeduct = $eachcomputation->amount;
                                        }else{
                                            $eachatt->amountdeduct = ($eachcomputation->amount/100)*$basicsalaryinfo->amountperday;
                                        }
                                    }
                                }
                            }
                        }else{
                            $eachatt->amountdeduct = 0;
                        }
                    }else{
                        $eachatt->amountdeduct = 0;
                    }
                    
                }
            }
            $latecomputationdetails = (object)array(
                'tardinessamount'         => $tardinessamount,
                'lateduration'            => $lateduration,
                'durationtype'            => $durationtype,
                'tardinessallowance'      => $tardinessallowance,
                'tardinessallowancetype'  => $tardinessallowancetype
            );

       
            $dayswithattendance = [];
            if (!empty($dates) || empty($dates) ) {
                if ($employeeinfo->employmentstatus == 1 || $employeeinfo->employmentstatus == 2 || $employeeinfo->employmentstatus == 3 || $employeeinfo->employmentstatus == 4 || $employeeinfo->employmentstatus == null) {
                    // if ($employeeinfo->employmentstatus) {
                    // For HOLIDAY
                    // get all the days where basic salary info is equal to 0 or get the rest day
                    $restday = [];
    
                    if ($basicsalaryinfo) {
                        if ($basicsalaryinfo->mondays == 0) {
                            $restday[] = 'Monday';
                        }
                        if ($basicsalaryinfo->tuesdays == 0) {
                            $restday[] = 'Tuesday';
                        }
                        if ($basicsalaryinfo->wednesdays == 0) {
                            $restday[] = 'Wednesday';
                        }
                        if ($basicsalaryinfo->thursdays == 0) {
                            $restday[] = 'Thursday';
                        }
                        if ($basicsalaryinfo->fridays == 0) {
                            $restday[] = 'Friday';
                        }
                        if ($basicsalaryinfo->saturdays == 0) {
                            $restday[] = 'Saturday';
                        }
                        if ($basicsalaryinfo->sundays == 0) {
                            $restday[] = 'Sunday';
                        }
                    }
                    $datesAbsents = [];
                    $datesAbsences = [];
                    $datesPresent = [];
    
                    foreach ($taplogs as $attendanceData) {
                        $date = $attendanceData->date;
    
                        if ($attendanceData->status == 1) {
                            $datesPresent[] = $date;
                        } else {
                            $datesAbsents[] = ['date' => $date];
                            $datesAbsences[] = $date;
                        }
                    }
                    // get the missing dates from payroll period start and end, usually 15days
                    $startDate = $result->datefrom;
                    $endDate = $result->dateto;
                    $datesInRange = [];
    
                    $currentDate = strtotime($startDate);
                    $endTimestamp = strtotime($endDate);
    
                    while ($currentDate <= $endTimestamp) {
                        $date = date("Y-m-d", $currentDate);
                        $dayName = date("l", $currentDate); // Get the full day name
                        $datesInRange[$date] = $dayName; // Store in an associative array
                        $currentDate = strtotime("+1 day", $currentDate);
                    }
                    
                    // this is the result together with the days name
                    $missingDates = array_diff_key($datesInRange, array_flip($datesPresent));
    
    
                    // compare missing dates if nag match sa gi return nga rest day 
                    $matchingMissingDates = [];
    
                    foreach ($missingDates as $date => $dayName) {
                        if (in_array($dayName, $restday)) {
                            $matchingMissingDates[$date] = $dayName;
                        }
                    }
                    
                    $matchingDates = array_keys($matchingMissingDates);
                    $attendanceforrestday = \App\Models\HR\HREmployeeAttendance::gethours($matchingDates, $employeeinfo->id,$taphistory,$hr_attendance,$request->get('departmentid'));
    
                    $timebracketsrestday = array();
                    $amountPerDay = $basicsalaryinfo->amountperday ?? 0;
                    $hoursPerDay = $basicsalaryinfo->hoursperday ?? 0;
                    if ($amountPerDay == 0 && $hoursPerDay == 0) {
                        $resultperday = 0;
                    } else if($hoursPerDay == 0){
                        $resultperday = 0;
                    }
                     else {
                        $resultperday = $amountPerDay/$hoursPerDay;
                    }
                    if(count($attendanceforrestday)>0)
                    {
                        foreach($attendanceforrestday as $eachdate)
                        {
                            $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date,$employeeinfo,$resultperday,$basicsalaryinfo,$taphistory,$hr_attendance);
                            $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                            $eachdate->lateminutes = $latedeductiondetail->lateminutes;
                            $eachdate->restday = 1;
    
                            if(count($latedeductiondetail->brackets)>0)
                            {
                                foreach($latedeductiondetail->brackets as $eachbracket)
                                {
                                    array_push($timebracketsrestday, $eachbracket);
                                }
                            }
                            $eachdate->amountdeduct = 0;
                        }
                    }
    
                    // return $attendance;
    
                    $rtardiness_computations = DB::table('hr_tardinesscomp')
                        ->where('hr_tardinesscomp.deleted','0')
                        ->where('hr_tardinesscomp.isactive','1')
                        ->get();
            
                    $rtardinessamount = 0; 
                    $rlateduration = 0; 
                    $rdurationtype = 0; 
                    $rtardinessallowance = 0; 
                    $rtardinessallowance = 0; 
                    
                    if(count($attendanceforrestday)>0 && count($rtardiness_computations)>0)
                    {
                        foreach($attendanceforrestday as $eachatt)
                        {
                            $eachatt->lateamminutes = ($eachatt->lateamhours*60);
                            $eachatt->latepmminutes = ($eachatt->latepmhours*60);
                            $eachatt->lateminutes = ($eachatt->latehours*60);
    
                            $eachcomputations = collect($rtardiness_computations)->where('latefrom','<=', $eachatt->lateminutes)->where('lateto','>=', $eachatt->lateminutes);
                            $fromcomputations = collect($rtardiness_computations)->where('latefrom','<=', $eachatt->lateminutes)->where('lateto','>=', $eachatt->lateminutes);
                            $eachcomputations = $eachcomputations->merge($fromcomputations);
                            $eachcomputations = $eachcomputations->unique();
                            
                            if($basicsalaryinfo->attendancebased == 1)
                            {
                                if(count($eachcomputations)>0)
                                {
                                    foreach($eachcomputations as $eachcomputation)
                                    {
                                        if($eachcomputation->latetimetype == 1)
                                        {
                                            if($eachcomputation->deducttype == 1)
                                            {
                                                $eachatt->amountdeduct = $eachcomputation->amount;
                                            }else{
                                                $eachatt->amountdeduct = ($eachcomputation->amount/100)*$basicsalaryinfo->amountperday;
                                            }
                
                                        }else
                                        {
                                            $computehours = ($eachatt->lateminutes/60);
                                            if($eachcomputation->deducttype == 1)
                                            {
                                                $eachatt->amountdeduct = $eachcomputation->amount;
                                            }else{
                                                $eachatt->amountdeduct = ($eachcomputation->amount/100)*$basicsalaryinfo->amountperday;
                                            }
                                        }
                                    }
                                }
                            }else{
                                $eachatt->amountdeduct = 0;
                            }
                        }
                    }
                    
                    $attendanceArray = json_decode($attendanceforrestday, true);
                    $filteredAttendance = array_filter($attendanceArray, function($record) {
                        return $record['status'] == 1;
                    });
    
                    $datesArrayrestdays = [];
    
                    foreach ($filteredAttendance as $record) {
                        $datesArrayrestdays[] = $record['date'];
                    }
    
                    $dayswithattendance = collect(array());
                    $dayswithattendance = $dayswithattendance->merge($datesPresent);
                    $dayswithattendance = $dayswithattendance->merge($datesArrayrestdays);
                    
                    $holidays = DB::table('school_calendar')
                        ->select(
                            'school_calendar.*',
                            'schoolcaltype.typename',
                            'hr_holidaytype.ifwork',
                            'hr_holidaytype.ifnotwork',
                            'hr_holidaytype.restdayifwork',
                            'hr_holidaytype.restdayifnotwork',
                            'hr_holidaytype.description'
                        )
                        ->leftJoin('schoolcaltype', 'school_calendar.holiday', '=', 'schoolcaltype.id')
                        ->leftJoin('hr_holidaytype', 'school_calendar.holidaytype', '=', 'hr_holidaytype.id')
                        ->where('schoolcaltype.type', 1)
                        ->where('school_calendar.deleted', 0)
                        ->get();
                        
                        $holidaydates = [];
                        foreach ($holidays as $holiday) {
                            $startDate = new \DateTime($holiday->start);
                            $endDate = new \DateTime($holiday->end);
    
                            $interval = new \DateInterval('P1D'); // 1 day interval
                            $dateRange = new \DatePeriod($startDate, $interval, $endDate);
    
                            foreach ($dateRange as $date) {
                                // $dates[] =  $date->format('Y-m-d');
                                $holidaydates[] = ['date' => $date->format('Y-m-d'),
                                                    'type'       => $holiday->description,
                                                    'holidaytype'       => $holiday->holidaytype,
                                                    'holidayname' => $holiday->title
                                                ];
    
                            }
                        }
                     
                        $employeecustomsched = DB::table('employee_customtimesched')
                            ->where('employeeid', $employeeinfo->id)
                            ->where('shiftid', '!=', null)
                            ->where('createdby', '!=', null)
                            ->where('deleted', 0)
                            ->first();
    
                        if ($employeecustomsched) {
    
                            $amin = new DateTime($employeecustomsched->amin);
                            $amout = new DateTime($employeecustomsched->amout);
                            $pmin = new DateTime($employeecustomsched->pmin);
                            $pmout = new DateTime($employeecustomsched->pmout);
    
                            // Calculate morning working hours
                            $morningWorkingHours = $amin->diff($amout);
                            $amhours = $morningWorkingHours->h;
    
                            // Calculate afternoon working hours
                            $afternoonWorkingHours = $pmin->diff($pmout);
                            $pmhours = $afternoonWorkingHours->h;
                            $totalworkinghours = $amhours + $pmhours;
    
    
    
                            if (count($holidaydates) > 0) {
                                foreach ($taplogs as $att) {
                                    $att->holiday = 0;
                                    $att->holidayname = "";
    
                                    foreach ($holidaydates as $holidaydate) {
                                        // if ($att->date == $holidaydate['date'] && $att->status == 2 && ($holidaydate['holidaytype'] == 1 || $holidaydate['type'] == 'Regular Holiday')) {
                                            if ($att->date == $holidaydate['date'] && $att->status == 2) {
                                            $att->amtimein = $employeecustomsched->amin;
                                            $att->amtimeout = $employeecustomsched->amout;
                                            $att->pmtimein = $employeecustomsched->pmin;
                                            $att->pmtimeout = $employeecustomsched->pmout;
                                            $att->timeinam = $employeecustomsched->amin;
                                            $att->timeoutam = $employeecustomsched->amout;
                                            $att->timeinpm = $employeecustomsched->pmin;
                                            $att->timeoutpm = $employeecustomsched->pmout;
                                            $att->amin = $employeecustomsched->amin;
                                            $att->amout = $employeecustomsched->amout;
                                            $att->pmin = $employeecustomsched->pmin;
                                            $att->pmout = $employeecustomsched->pmout;
                                            $att->status = 1;
                                            $att->totalworkinghours = $totalworkinghours;
                                            $att->holiday = 1;
                                            $att->holidayname = $holidaydate['holidayname'];
                                            
                                        } else if($att->date == $holidaydate['date'] && $att->status == 1){
											$att->amtimein = $employeecustomsched->amin;
											$att->amtimeout = $employeecustomsched->amout;
											$att->pmtimein = $employeecustomsched->pmin;
											$att->pmtimeout = $employeecustomsched->pmout;
											$att->timeinam = $employeecustomsched->amin;
											$att->timeoutam = $employeecustomsched->amout;
											$att->timeinpm = $employeecustomsched->pmin;
											$att->timeoutpm = $employeecustomsched->pmout;
											$att->amin = $employeecustomsched->amin;
											$att->amout = $employeecustomsched->amout;
											$att->pmin = $employeecustomsched->pmin;
											$att->pmout = $employeecustomsched->pmout;
											$att->status = 1;
											$att->totalworkinghours = $totalworkinghours;
											$att->holiday = 1;
											$att->holidayname = $holidaydate['holidayname'];


											$att->lateminutes = 0;
											$att->lateamminutes = 0;
											$att->latepmminutes = 0;
											$att->lateamhours = 0;
											$att->latepmhours = 0;
											$att->latehours = 0;
											$att->undertimeamhours = 0;
											$att->undertimepmhours = 0;
                                    
                                    }
                                    }
                                }
                            }
                        }
                        
                        if ($result) {
                            $datefrom = $result->datefrom;
                            $dateto = $result->dateto;
                            $holidays_within_range = [];
    
                            
                            foreach ($holidays as $holiday) {
                                $start_date = strtotime($holiday->start); 
                                $end_date = strtotime($holiday->end); 
                        
                                if ($start_date <= strtotime($dateto) && $end_date >= strtotime($datefrom)) {
                                    // Check if the start date of the holiday matches any date in $datesArrayrestdays
                                    if (in_array(date("Y-m-d", $start_date), $datesArrayrestdays)) {
                                        // If it matches, set attendance to 'present'
                                        $holiday->attendance = 'present';
                                    } else {
                                        // If it doesn't match, set attendance to 'not present'
                                        $holiday->attendance = 'not present';
                                    }
                        
                                    // Calculate holiday pay percentage
                                    $duration = ceil(($end_date - $start_date) / (60 * 60 * 24)); // Calculate duration in days
                        
                                    // Calculate the amount per day
                                    // $amountPerDay = floor($basicsalaryinfo->amountperday * 100) / 100;
                                    $amountPerDay = $basicsalaryinfo->amountperday;
                                    $holiday->matching = "";
                                    $holiday->hoursperday = $basicsalaryinfo->hoursperday;
                                    $holiday->duration = $duration; // Add duration as a property
                                    $holiday->amountPerDay = $amountPerDay; // Add amount per day as a property
                                    $holiday->holidaypay = $amountPerDay;
                                    
                                    $holidays_within_range[] = $holiday;
                                }
                            }
                        }
                
                    foreach ($dayswithattendance as $datesp) {
                        if (in_array($datesp, $datesArrayrestdays)) {
                            $datepresentstart = strtotime($datesp); // Access the date directly since $datesp is a string
                            foreach ($holidays_within_range as $holiday_within) { // Use &$holiday_within to modify the original array
                                $start = strtotime($holiday_within->start);
                                if ($start == $datepresentstart) { // Use == for comparison, not =
                                    $holiday_within->matching = "Matching"; // Add a property to indicate the match
                                    $holiday_within->attendance = 'present';
                                    $holidaypercentage = $holiday_within->restdayifwork / 100;
                                    $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
                                    $holidayperday =  $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
                                    $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                    $holidaywithdurationpay = $holidaywithdurationpay + $holiday_within->amountPerDay;
                                    $holiday_within->holidaypay = round($holidaywithdurationpay, 2); 
                                }
                            }
                        }
                         else {
                            $datepresentstart = strtotime($datesp); // Access the date directly since $datesp is a string
                            foreach ($holidays_within_range as $holiday_within) { // Use &$holiday_within to modify the original array
                                $start = strtotime($holiday_within->start);
                                if ($start == $datepresentstart) { // Use == for comparison, not =
                                    $holiday_within->matching = "Matching"; // Add a property to indicate the match
                                    $holiday_within->attendance = 'present';
                                    $holidaypercentage = $holiday_within->ifwork / 100;
    
                                    $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
    
                                    $holidayperday =  $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
    
                                    $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                    // $holidaywithdurationpay = $holiday_within->amountPerDay * $holiday_within->duration;
                                    // $holiday_within->holidaypay = number_format($holidaywithdurationpay - .005, 2);
                                    $holiday_within->holidaypay = floor($holidaywithdurationpay * 100) / 100 ;
    
                                }
                            }
                        }
                    }
    
                    foreach ($datesAbsences as $datesp) {
                        $dateabsentstart = strtotime($datesp);
                        foreach ($holidays_within_range as $holiday_within) {
                            if ($holiday_within->ifnotwork > 0) {
                                $start = strtotime($holiday_within->start);
                                if ($start == $dateabsentstart) { 
                                    $holiday_within->matching = "Matching";
                                    $holidaypercentage = $holiday_within->ifwork / 100;
                                    $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
                                    $holidayperday =  $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
                                    $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                    $holiday_within->holidaypay = floor($holidaywithdurationpay * 100) / 100;
                                }
                            }
                            
                        }
                    }
    
    
                    // Get the total holiday payment
                    $totalPresentHolidaypay = 0; // Initialize the counter
    
    
                    foreach ($holidays_within_range as $holidaypayments) {
                        if ($holidaypayments->matching == 'Matching') {
                            $totalPresentHolidaypay += $holidaypayments->holidaypay; // Increment the counter
                        }
                        
                    }
                    
    
                    $holidays_within_range = json_decode(json_encode($holidays_within_range), true);
                 
                    $filtered_holidays = $holidays_within_range;
        
                } else {
                    foreach ($standardallowances as &$sallowancestandard) {
    
                        if ($sallowancestandard->baseonattendance == 1) {
                            $amountPerDay = $sallowancestandard->amountperday;
                            $totalDaysAmount = $amountPerDay * ( count($dayswithattendance) + $saturdayCount);
                            $sallowancestandard->totalDaysAmount = $totalDaysAmount;
    
                            //$sallowancestandard->totalDayspresent = count($dayswithattendance);
                            $sallowancestandard->totalDayspresent = collect($attendance)->where('totalworkinghours','>',0)->count() + $saturdayCount;
                        } else {
                            $sallowancestandard->totalDaysAmount = 0;
                        }
                    }
                    unset($sallowancestandard); // Unset the reference to the last element
                }
            }
            foreach ($taplogs as $item) {
                $item->exceed8hours = $item->totalworkinghours >= 8 ? 1 : 0;
            }
    
            // Now you can safely use count() on $dayswithattendance
            $totaldayspresent = count($dayswithattendance);
            
            
    
           
            $totalLateHours = 0;
            $totalUndertimeHours = 0;
            $totalworkinghoursrender = 0;
            $flexihours = 0;
            $flexihoursundertime = 0;
			
			
    
            foreach ($taplogs as $entry) {
                if ($entry->totalworkinghours != 0 && $entry->totalworkinghours !== null && $entry->amin !== null && $entry->pmout !== null) {
                    // if ($entry->totalworkinghours != 0 && $entry->totalworkinghours !== null) {
                        // return $entry->date;
                    $totalLateHours = $entry->latehours;
                    $totalUndertimeHours = $entry->undertimehours;
                    $totalWorkingHoursRender = 8 - ($totalLateHours + $totalUndertimeHours);
                    $entry->totalworkinghoursrender = $totalWorkingHoursRender;
    
                    $flexihours = $entry->totalworkinghoursflexi;
                    $flexihoursundertime = 8 - $flexihours;
                    
                    if ($flexihours > 8) {
                        $entry->flexihours = 8;
                    } else {
                        // $entry->flexihours = number_format($flexihours - .005, 2);
                        $entry->flexihours = $flexihours;
                    }
                    if ($flexihoursundertime < 0) {
                        $entry->flexihoursundertime = 0;
                    } else {
                        $entry->flexihoursundertime = $flexihoursundertime;
                    }
                } else {
                    $entry->totalworkinghoursrender = 0;
                    $entry->flexihours = 0;
                    $entry->flexihoursundertime = 0;
                }
            }

			if (count($leavesappr)>0) {
                foreach ($leavesappr as $leavesdetail) {
                    if ($leavesdetail->halfday == 0) {
                        $leavesdetail->daycoverd = 'Whole Day';
                    } else if ($leavesdetail->halfday == 1) {
                        $leavesdetail->daycoverd = 'Half Day AM';
                    } else if ($leavesdetail->halfday == 2) {
                        $leavesdetail->daycoverd = 'Half Day PM';
                    } else {
                        $leavesdetail->daycoverd = '';
                    }
                }
            }
            
            if(count($taplogs)>0){
                foreach ($taplogs as $lognull) {
                    $lognull->leavetype = '';
                    $lognull->leavedaystatus = '';
                    $lognull->daycoverd = '';
                    $lognull->leaveremarks = '';
                    if (($lognull->timeinam == null && $lognull->timeoutpm == null) || ($lognull->amtimein == null && $lognull->pmtimeout == null)) {
                        $lognull->timeoutam = null;
                        $lognull->timeinpm = null;
                    } else {
                        if ($lognull->timeinam != null && $lognull->timeoutpm == null) {
                            $lognull->timeoutam = null;
                            $lognull->timeinpm = null;
                            // $lognull->totalworkinghours = 0;
                        }
                    }


                    if (count($leavesappr)>0) {

                        // return $employeeleavesappr;

                        foreach ($employeeleavesappr as $employeeleavesapp) {
                            if ($employeeleavesapp->ldate == $lognull->date) {
                                $lognull->leavetype = $employeeleavesapp->leave_type;
                                $lognull->leavedaystatus = $employeeleavesapp->halfday;
                                $lognull->daycoverd = $employeeleavesapp->daycoverd;
                                $lognull->leaveremarks = $employeeleavesapp->remarks;

                                // if ($lognull->daycoverd == ) {
                                //     # code...
                                // }
                            }
                        }
                    }
                }

            }
            
            if(count($taplogs)>0)
            {   
                if ($customamtimein) {

                    $amin = new DateTime($customamtimein->amin);
                    $amout = new DateTime($customamtimein->amout);
                    $pmin = new DateTime($customamtimein->pmin);
                    $pmout = new DateTime($customamtimein->pmout);
        
                    // Calculate morning working hours
                    $morningWorkingHours = $amin->diff($amout);
                    $amhours = $morningWorkingHours->h + ($morningWorkingHours->i / 60); // Convert minutes to fraction of an hour
        
                    // Calculate afternoon working hours
                    $afternoonWorkingHours = $pmin->diff($pmout);
                    $pmhours = $afternoonWorkingHours->h + ($afternoonWorkingHours->i / 60); // Convert minutes to fraction of an hour
        
                    // Calculate total working hours
                    $totalworkinghours = $amhours + $pmhours;
                    
                    // return collect($basicsalaryinfo);
        
                    if ($basicsalaryinfo) {
                        if ($basicsalaryinfo->halfdaysat == 1) {
                            foreach ($taplogs as $attt) {
                                if ($attt->pmin != null || $attt->pmout != null) {
                                    if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                                        
                                        $attt->amtimein = $customamtimein->amin;
                                        $attt->amtimeout = $customamtimein->amout;
                                        $attt->timeinam = $customamtimein->amin;
                                        $attt->timeoutam = $customamtimein->amout;
                                        $attt->amin = $customamtimein->amin;
                                        $attt->amout = $customamtimein->amout;
                                        $attt->status = 1;
                                    
            
                                        if ($attt->pmin == null || $attt->pmout == null ) {
                                            $attt->totalworkinghours = $amhours;
                                            $attt->totalworkinghoursrender = $amhours;
                                            $attt->undertimepmhours = $pmhours;
            
                                        } else {
                                            $attt->totalworkinghours = $amhours + ($amhours - $attt->latepmhours);
                                            $attt->totalworkinghoursrender = $amhours + ($amhours - $attt->latepmhours);
                                            $attt->lateamminutes = 0;
                                            $attt->lateamhours = 0;
                                            $attt->undertimeamhours = 0;
                                        }
                                    
                                    }
                                }
                            }
                        } else if($basicsalaryinfo->halfdaysat == 2){
                            foreach ($taplogs as $attt) {
                                if ($attt->amin != null || $attt->amout != null) {
                                    if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                                        $attt->pmtimein = $customamtimein->pmin;
                                        $attt->pmtimeout = $customamtimein->pmout;
                                        $attt->timeinpm = $customamtimein->pmin;
                                        $attt->timeoutpm = $customamtimein->pmout;
                                        $attt->pmin = $customamtimein->pmin;
                                        $attt->pmout = $customamtimein->pmout;
                                        $attt->status = 1;
            
                                        if ($attt->amin == null || $attt->amout == null ) {
                                            $attt->totalworkinghours = $pmhours;
                                            $attt->totalworkinghoursrender = $pmhours;
                                            $attt->lateamminutes = $pmhours * 60;
                                            $attt->lateamhours = $pmhours;
                                            // $attt->undertimeamhours = $pmhours;
                                        } else {
                                            $attt->totalworkinghours = $pmhours + ($amhours - $attt->lateamhours);
                                            $attt->totalworkinghoursrender = $pmhours + ($amhours - $attt->lateamhours);
                                            $attt->latepmminutes = 0;
                                            $attt->latepmhours = 0;
                                            $attt->undertimepmhours = 0;
                                        }
                                    
                                    }
                                }
                                
                            }
                          
                        }
                    } 
        
                    // return collect($basicsalaryinfo);
                    foreach ($taplogs as $att) {
                            $att->lateamminutes = 0;
                            $att->latepmminutes = 0;
                        

                            $att->appliedleave = 0;
                            if ($att->leavedaystatus === 0) {
                                $att->amtimein = $customamtimein->amin;
                                $att->amtimeout = $customamtimein->amout;
                                $att->pmtimein = $customamtimein->pmin;
                                $att->pmtimeout = $customamtimein->pmout;
                                $att->timeinam = $customamtimein->amin;
                                $att->timeoutam = $customamtimein->amout;
                                $att->timeinpm = $customamtimein->pmin;
                                $att->timeoutpm = $customamtimein->pmout;
                                $att->amin = $customamtimein->amin;
                                $att->amout = $customamtimein->amout;
                                $att->pmin = $customamtimein->pmin;
                                $att->pmout = $customamtimein->pmout;
                                $att->status = 1;
                                $att->totalworkinghours = $totalworkinghours;
                                $att->appliedleave = 1;
                            
                                $att->lateamminutes = 0;
                                $att->latepmminutes = 0;
                                $att->lateamhours = 0;
                                $att->undertimeamhours = 0;
                                $att->undertimepmhours = 0;

                            } else if($att->leavedaystatus == 1){

                                $att->amtimein = $customamtimein->amin;
                                $att->amtimeout = $customamtimein->amout;
                                $att->timeinam = $customamtimein->amin;
                                $att->timeoutam = $customamtimein->amout;
                                $att->amin = $customamtimein->amin;
                                $att->amout = $customamtimein->amout;
                                $att->status = 1;
                            
                                $att->totalworkinghours = number_format($att->totalworkinghours + $amhours, 2);
                                // $att->totalworkinghours = floor(($att->totalworkinghours + $amhours) * 100) / 100;
                                $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $att->lateamhours) * 100) / 100;
                                $att->totalworkinghoursflexi = floor(($att->totalworkinghoursflexi + $att->lateamhours) * 100) / 100;
                                $att->flexihours = floor(($att->flexihours + $att->lateamhours) * 100) / 100;
                                $att->lateamminutes = 0;
                                $att->lateamhours = 0;
                                $att->undertimeamhours = 0;
                                // if ($att->date == "2024-02-16") {
                                //     return $att->totalworkinghours;
                                // }
                            
                            } else if($att->leavedaystatus == 2){

                                $att->pmtimein = $customamtimein->pmin;
                                $att->pmtimeout = $customamtimein->pmout;
                                $att->timeinpm = $customamtimein->pmin;
                                $att->timeoutpm = $customamtimein->pmout;
                                $att->pmin = $customamtimein->pmin;
                                $att->pmout = $customamtimein->pmout;
                                $att->status = 1;

                                $att->totalworkinghours = floor(($att->totalworkinghours +  $pmhours) * 100) / 100;
                                $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $att->latepmhours) * 100) / 100;
                                $att->totalworkinghoursflexi = floor(($att->totalworkinghoursflexi + $att->latepmhours) * 100) / 100;
                                $att->flexihours = floor(($att->flexihours + $att->latepmhours) * 100) / 100;

                                $att->latepmminutes = 0;
                                $att->latepmhours = 0;
                                $att->undertimepmhours = 0;
                            }
                        
                    }

                    
                    foreach ($taplogs as $attendancefinal) {
        
                        $amlate = floatval($attendancefinal->lateamminutes);
                        $pmlate = floatval($attendancefinal->latepmminutes);
                        $amlatehours = floatval($attendancefinal->lateamhours);
                        $pmlatehours = floatval($attendancefinal->latepmhours);
                        $amundertime = floatval($attendancefinal->undertimeamhours);
                        $pmundertime = floatval($attendancefinal->undertimepmhours);
            
                    
                    
                        // return ($amhours - $amundertime) * 60;
                        if ($attendancefinal->amin == null && $attendancefinal->amout != null) {
                            $attendancefinal->lateamminutes = 0; // Initialize latepmminutes
                            // $totalworkinghoursfinal = $totalworkinghours - $attendancefinal->totalworkinghours;
                            $attendancefinal->lateamminutes = ($amhours - $amundertime) * 60;
        
                            // return $attendancefinal->lateamminutes;
                        }
                        else if ($attendancefinal->amin != null && $attendancefinal->amout == null) {
                            
                            $attendancefinal->undertimeamhours = 0; // Initialize lateamminutes
                            $attendancefinal->lateamminutes = ($amhours - $amlatehours) * 60;
            
                        } 
                         else if ($attendancefinal->pmin == null && $attendancefinal->pmout != null) {
                            
                            $attendancefinal->latepmminutes = 0; // Initialize latepmminutes
                            
                            $attendancefinal->latepmminutes = ($pmhours - $pmundertime) * 60;
            
                        }  else if ($attendancefinal->pmin != null && $attendancefinal->pmout == null) {
                            $attendancefinal->undertimepmhours = 0; // Initialize latepmminutes
                            
                            $attendancefinal->undertimepmhours = $pmhours - $pmlatehours;
                            
                        } 
            
                        $attendancefinal->latehours = ($attendancefinal->lateamminutes + $attendancefinal->latepmminutes) / 60;
                        $attendancefinal->lateminutes = $attendancefinal->lateamminutes + $attendancefinal->latepmminutes;
                        $attendancefinal->undertimehours = $attendancefinal->undertimeamhours + $attendancefinal->undertimepmhours;
                        
                    }
                }
                // return $taplogs;
                foreach($taplogs as $log)
                    {
                        
                        array_push($summarylogs, (object)array(
                            'dateint'      => $log->dayint,
                            'timeinam'      => $log->timeinam,
                            'timeinpm'      => $log->timeinpm,
                            'timeoutam'      => $log->amout,
							//'timeoutam'      => $log->timeoutam,
                            'timeoutpm'      => $log->timeoutpm,
                            'remarks'      => $log->remarks,
                            'date'      => $log->date,
                            'logs'      => $log->logs,
                            'latehours'     => floor($log->latehours),
                            'lateminutes'     => floor(round(($log->latehours - floor($log->latehours))*60)),
                            'totalworkinghours'     => $log->totalworkinghours,
                            'totalworkinghoursflexi'     => $log->totalworkinghoursflexi,
                            'hours'     => floor($log->totalworkinghours),
                            'minutes'   => floor(($log->totalworkinghours - floor($log->totalworkinghours))*60),
                            'leavetype'   => $log->leavetype,
                            'leavedaystatus'   => $log->leavedaystatus,
                            'daycoverd'   => $log->daycoverd,
                            'leaveremarks'   =>  $log->daycoverd,
                            'holiday' => $log->holiday ?? "",
                            'holidayname' => $log->holidayname

                        ));
                    }
            }

            // return $summarylogs;



            // foreach($alldays as $day)
            // {
    
            
            //     $taphistory = DB::table('taphistory')
            //         ->where('studid', $request->get('id'))
            //         ->where('deleted','0')
            //         ->where('tdate', $day)
            //         ->where('utype', '!=', 7)
            //         ->orderBy('ttime','asc')
            //         ->get();

            //     if(count($taphistory)>0)
            //     {
            //         foreach($taphistory as $tapatt)
            //         {
            //             $tapatt->mode = 0;
            //         }
            //     }

            //     $hr_attendance = DB::table('hr_attendance')
            //         ->where('studid', $request->get('id'))
            //         ->where('tdate', $day)
            //         ->where('deleted',0)
            //         ->orderBy('ttime','asc')
            //         ->get();

            //     if(count($hr_attendance)>0)
            //     {
            //         foreach($hr_attendance as $hratt)
            //         {
            //             $hratt->mode = 1;
            //         }
            //     }


            //     $logs = collect();
            //     $logs = $logs->merge($taphistory);
            //     $logs = $logs->merge($hr_attendance);
            //     $logs = $logs->sortBy('ttime');
            //     $logs = $logs->unique('ttime');

            //     // if($day == '2021-06-19')
            //     // {
            //     //     return $logs;
            //     // }
            //     // $logs = DB::table('taphistory')
            //     //     ->where('studid', $request->get('id'))
            //     //     ->where('utype', '!=', 7)
            //     //     ->where('deleted','0')
            //     //     ->where('tdate', $day)
            //     //     ->get();
    
            //     $hours = 0;
            //     $minutes = 0; 
            //     $latehours = 0;
            //     $lateminutes = 0;
               
            //     if(count($logs)>0)
            //     {
                    
            //         if(collect($logs)->where('ttime','<','12:00:00')->count() > 0)
            //         {
            //             if(collect($logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first())
            //             {
            //                 $amttimeamin = collect($logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first()->ttime;
            //                 $amtimein = $day.' '.$amttimeamin;
            //             }else{
                            
            //                 if($customamtimein)
            //                 {
            //                     $amttimeamin = $customamtimein->amin;
            //                     $amtimein = $day.' '.$customamtimein->amin;
            //                 }else{
            //                     $amttimeamin = '08:00:00';
            //                     $amtimein = $day.' 08:00:00';
            //                 }
            //             }

            //             $am_customtimein = new DateTime($amtimein);
            //             $am_latetimein = new DateTime($day.' '.$customamtimein->amin);
            //             $amlateinterval = $am_customtimein->diff($am_latetimein);
            //             $lateh = $amlateinterval->format('%h');
            //             $latem = $amlateinterval->format('%i');
                        
            //             if($amttimeamin>$customamtimein->amin)
            //             {
            //                 $latehours+=$lateh;
            //                 $lateminutes+=$latem;
            //             }
            //             // if(collect($logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first())
            //             // {
            //             //     $amtimein = $day.' '.collect($logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first()->ttime;
            //             // }else{
                            
        
            //             //     if($customamtimein)
            //             //     {
            //             //         $amtimein = $day.' '.$customamtimein->amin;
            //             //     }else{
            //             //         $amtimein = $day.' 08:00:00';
            //             //     }
            //             // }
            //             if(collect($logs)->where('tapstate','OUT')->where('ttime','<','12:00:00')->last())
            //             {
            //                 $amtimeout = $day.' '.collect($logs)->where('tapstate','OUT')->where('ttime','<','12:00:00')->last()->ttime;
            //             }else{
                            
            //                 $customamtimeout = DB::table('employee_customtimesched')
            //                     ->where('employeeid', $request->get('id'))
            //                     ->where('deleted', 0)
            //                     ->first();
        
            //                 if($customamtimeout)
            //                 {
            //                     $amtimeout = $day.' '.$customamtimeout->amout;
            //                 }else{
            //                     $amtimeout = $day.' 12:00:00';
            //                 }
            //             }
            //             $am_timein = new DateTime($amtimein);
            //             $am_timeout = new DateTime($amtimeout);
            //             $aminterval = $am_timein->diff($am_timeout);
                         
            //             $hours = $aminterval->format('%h');
            //             $minutes = $aminterval->format('%i');
            //         }
                    
            //         /////
            //         // return collect($logs)->where('ttime','>=','12:00:00')->count();
            //         if(collect($logs)->where('ttime','>=','12:00:00')->count() > 0)
            //         {
            //             if(collect($logs)->where('tapstate','IN')->where('ttime','>=','12:00:00')->first())
            //             {
            //                 $pmtimein = $day.' '.collect($logs)->where('tapstate','IN')->where('ttime','>=','12:00:00')->first()->ttime;
            //             }else{
                            
            //                 $custompmtimein = DB::table('employee_customtimesched')
            //                     ->where('employeeid', $request->get('id'))
            //                     ->where('deleted', 0)
            //                     ->first();
        
            //                 if($custompmtimein)
            //                 {
            //                     $pmtimein = $day.' '.$custompmtimein->pmin;
            //                 }else{
            //                     $pmtimein = $day.' 13:00:00';
            //                 }
            //             }
            //             if(collect($logs)->where('tapstate','OUT')->where('ttime','>=','12:00:00')->last())
            //             {
            //                 $pmtimeout = $day.' '.collect($logs)->where('tapstate','OUT')->where('ttime','>=','12:00:00')->last()->ttime;
            //             }else{
                            
            //                 $custompmtimeout = DB::table('employee_customtimesched')
            //                     ->where('employeeid', $request->get('id'))
            //                     ->where('deleted', 0)
            //                     ->first();
        
            //                 if($custompmtimeout)
            //                 {
            //                     $pmtimeout = $day.' '.$custompmtimeout->pmout;
            //                 }else{
            //                     $pmtimeout = $day.' 17:00:00';
            //                 }
            //             }
            //             $pm_timein = new DateTime($pmtimein);
            //             $pm_timeout = new DateTime($pmtimeout);
            //             $pminterval = $pm_timein->diff($pm_timeout);
                         
            //             $hours += $pminterval->format('%h');
            //             $minutes += $pminterval->format('%i');
            //         }
    
            //     }
    
            //     while($minutes>=60)
            //     {
            //         $hours+=1;
            //         $minutes-=60;
            //     }

            //     $remarks = DB::table('hr_attendanceremarks')
            //     ->where('tdate',$day)
            //     ->where('employeeid',  $request->get('id'))
            //     ->where('deleted','0')
            //     ->first();
            //     $timeinam = collect($logs)->where('tapstate','IN')->first()->ttime ?? '';
            //     $timeinpm = collect($logs)->where('ttime','>=',$customamtimein->amout)->where('tapstate','IN')->first()->ttime ?? '';
            //     $timeoutam = collect($logs)->where('ttime','<=',$timeinpm)->where('tapstate','OUT')->last()->ttime ?? '';
            //     $timeoutpm = collect($logs)->where('ttime','>',$timeinpm)->where('tapstate','OUT')->last()->ttime ?? '';

            //     array_push($summarylogs, (object)array(
            //         'remarks'      => $remarks,
            //         'timeinam'      => $timeinam,
            //         'timeinpm'      => $timeinpm,
            //         'timeoutam'      => $timeoutam,
            //         'timeoutpm'      => $timeoutpm,
            //         'date'      => $day,
            //         'logs'      => $logs,
            //         'latehours'     => $latehours,
            //         'lateminutes'     => $lateminutes,
            //         'hours'     => $hours,
            //         'minutes'   => $minutes
            //     ));
            // }
        }
        
        // return $summarylogs;
        if(!$request->has('exporttype'))
        {
            if(!$request->has('id') || count($request->get('id')) > 1)
            {
                return view('hr.attendance.summarylogs')
                    ->with('dates', $alldays)
                    ->with('employees', $employees);
            }elseif( count($request->get('id')) == 1){
                return view('hr.attendance.summaryemplogs')
                    ->with('logs', $summarylogs);
            }
        }else{
            

            if(!$request->has('id') || count($request->get('id')) > 1)
            {
                $alldays = collect($alldays)->toArray();
                $alldays = array_chunk($alldays, 5);
                
                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ck')
                // {
                
                 
                    // return count($employees);
                    //     $pdf = PDF::loadview('hr/pdf/summaryattendance',compact('alldays','datefrom','dateto','employees'))->setPaper('portrait');
                    // }else{
                        $pdf = PDF::loadview('hr/pdf/summaryattendance',compact('alldays','datefrom','dateto','employees','result'))->setPaper('portrait');
                    // }
                // }else{
                //     $pdf = PDF::loadview('hr/pdf/summaryattendance',compact('alldays','datefrom','dateto','employees'))->setPaper('landscape');
                // }
    
                return $pdf->stream('Summary of Attendance');
            }elseif( count($request->get('id')) == 1){
                $info = DB::table('teacher')
                        ->select('teacher.*','usertype.utype')
                        ->where('teacher.id', $request->get('id'))
                        ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                        ->first();

                // return $summarylogs;
                // return $summarylogs[0]->remarks->remarks;
                $pdf = PDF::loadview('hr/pdf/summaryattendanceemp',compact('summarylogs','datefrom','dateto','info','result'))->setPaper('portrait');
    
                return $pdf->stream('Summary of Attendance');
            }
        }
    }
    
    public function absencesindex(Request $request)
    {
        $offenses = DB::table('hr_offenses')
            ->where('deleted','0')
            ->where('type','0')
            ->get();

        if($request->get('action') == 'getoffenses')
        {
            return view('hr.attendance.absences.resultsoffenses')
                ->with('offenses', $offenses);
        }else{
            return view('hr.attendance.absences.index')
                ->with('offenses', $offenses);
        }
    }
    public function absencesoffense(Request $request)
    {
        $checkifexists = DB::table('hr_offenses')
            ->where('title','like','%'.$request->get('title').'%')
            ->where('deleted','0')
            ->where('type','0')
            ->first();

        if($request->get('action') == 'addoffense')
        {
            if($checkifexists)
            {
                return 0;
            }else{
                DB::table('hr_offenses')
                    ->insert([
                        'title'                 => $request->get('title'),
                        'description'           => $request->get('description'),
                        'createdby'             => auth()->user()->id,
                        'createddatetime'       => date('Y-m-d H:i:s')
                    ]);
    
                return 1;
            }
        }
        elseif($request->get('action') == 'editoffense')
        {
            try{
                // return $request->get('offenseid');
                DB::table('hr_offenses')
                    ->where('id', $request->get('offenseid'))
                    ->update([
                        'title'           => $request->get('title'),
                        'description'     => $request->get('description'),
                        'updatedby'       => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
                return 1;
            }catch(\Exception $error)
            {
                return 0;
            }
        }
        elseif($request->get('action') == 'deleteoffense')
        {
            try{
                // return $request->get('offenseid');
                DB::table('hr_offenses')
                    ->where('id', $request->get('offenseid'))
                    ->update([
                        'deleted'         => 1,
                        'deletedby'       => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
                return 1;
            }catch(\Exception $error)
            {
                return 0;
            }
        }
    }
    public function absencesgenerate(Request $request)
    {
        // return date("Y-m-d", strtotime($request->get('week')));
        // return $request->all();
        $employees = DB::table('teacher')
            ->select('title','id','firstname','middlename','lastname','suffix')
            ->where('deleted','0')
            ->orderBy('lastname','asc')
            ->get();

        if($request->has('employeeid'))
        {
            $employees = collect($employees)->where('id', $request->get('employeeid'))->values();
        }

        $days = array();
        $monthstart = date('Y-m-d', strtotime($request->get('week')));
        $monthend = date('Y-m-t', strtotime($request->get('week')));
        
        array_push($days,$monthstart);
        $initdate = $monthstart;
        for($x=0; $x<6; $x++)
        {
            array_push($days,date('Y-m-d', strtotime($initdate . ' +1 day')));
            $initdate = date('Y-m-d', strtotime($initdate . ' +1 day'));
        }
        // return $days;
        if(count($employees)>0)
        {
            foreach($employees as $employee)
            {
                $employee->firstname = ucwords(strtolower($employee->firstname));
                $employee->middlename = ucwords(strtolower($employee->middlename));
                $attrecords = collect();

                $atttap = DB::table('taphistory')
                    ->select('tdate')
                    ->where('studid', $employee->id)
                    ->where('deleted', 0)
                    ->whereIn('tdate', $days)
                    ->get();

                $atthr = DB::table('hr_attendance')
                    ->select('tdate')
                    ->where('studid', $employee->id)
                    ->where('deleted', 0)
                    ->whereIn('tdate', $days)
                    ->get();

                $attrecords = $attrecords->merge($atttap);
                $attrecords = $attrecords->merge($atthr);
                    
                $attremarks = DB::table('hr_attendanceremarks')
                    ->select('remarks','tdate')
                    ->where('employeeid', $employee->id)
                    ->where('deleted', 0)
                    ->whereIn('tdate', $days)
                    ->get();
                
                $daysabsent = array();

                foreach($days as $day)
                {
                    if(strtolower(date('l', strtotime($day))) != 'sunday' && strtolower(date('l', strtotime($day))) != 'saturday')
                    {
                        if(collect($attrecords)->where('tdate', $day)->count() == 0)
                        {
                            if(collect($attremarks)->where('tdate', $day)->count() == 0)
                            {
                                $remarks = "";
                            }else{
                                $remarks = collect($attremarks)->where('tdate', $day)->first()->remarks;
                            }
                            array_push($daysabsent, (object)array(
                                'date'  => $day,
                                'remarks' => $remarks
                            ));
                        }
                    }
                }
                $employee->daysabsent = $daysabsent;
                $employee->offenses   = DB::table('hr_offenseslist')
                    ->select('hr_offenseslist.*')
                    ->join('hr_offenses','hr_offenseslist.offenseid','=','hr_offenses.id')
                    ->where('employeeid',$employee->id)
                    ->where('weekid',$request->get('week'))
                    ->where('hr_offenseslist.deleted','0')
                    ->where('hr_offenses.deleted','0')
                    ->where('hr_offenses.type','0')
                    ->get();
            }
        }
        $offenses = DB::table('hr_offenses')
            ->where('deleted','0')
            ->where('type','0')
            ->get();
            
        if(!$request->has('export'))
        {
            return view('hr.attendance.absences.resultsemployees')
                ->with('employees', $employees)
                ->with('offenses', $offenses);
        }else{
            $weekid = $request->get('week');
            // pdf_tardiness.blade.php
            if($request->get('exportclass') == 1)
            {
                $employee = collect($employees)->where('id', $request->get('employeeid'))->first();
                $pdf = PDF::loadview('hr/attendance/absences/pdf_employeeabsences',compact('offenses','employee','weekid','days'))->setPaper('portrait');
    
                return $pdf->stream($weekid.' Absences - '.$employee->lastname.'_'.$employee->firstname.'.pdf');
            }else{
                $pdf = PDF::loadview('hr/attendance/absences/pdf_absences',compact('offenses','employees','weekid','days'))->setPaper('portrait');
    
                return $pdf->stream($weekid.' Absences.pdf');
            }
        }
    }
    public function absencesmarkoffense(Request $request)
    {
        $checkifexists = DB::table('hr_offenseslist')
            ->where('employeeid', $request->get('empid'))
            ->where('weekid', $request->get('weekid'))
            ->where('offenseid', $request->get('offenseid'))
            ->first();

        if($checkifexists)
        {
            if($request->get('status') == 0 && $checkifexists->deleted == 0)
            {
                DB::table('hr_offenseslist')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'deleted'         => 1,
                        'deletedby'       => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
            }
            if($request->get('status') == 1 && $checkifexists->deleted == 1)
            {
                DB::table('hr_offenseslist')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'deleted'         => 0,
                        'updatedby'       => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
            }
        }else{
            if($request->get('status') == 1)
            {
                DB::table('hr_offenseslist')
                    ->insert([
                        'employeeid'          => $request->get('empid'),
                        'weekid'              => $request->get('weekid'),
                        'offenseid'           => $request->get('offenseid'),
                        'createdby'           => auth()->user()->id,
                        'createddatetime'     => date('Y-m-d H:i:s')
                    ]);
            }
        }
        
    }
    public function absencesexport(Request $request)
    {
        // return $request->all();
        $employeeinfo = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix',
                'teacher.picurl',
                'employee_personalinfo.gender',
                'usertype.id as usertypeid',
                'usertype.utype'
                )
            ->join('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            ->where('teacher.deleted','0')
            ->where('teacher.isactive','1')
            ->where('teacher.id',$request->get('employeeid'))
            ->first();

        return collect($employeeinfo);
            
    }
    public function tardinessindex(Request $request)
    {
        $offenses = DB::table('hr_offenses')
            ->where('deleted','0')
            ->where('type','1')
            ->get();

        if($request->get('action') == 'getoffenses')
        {
            return view('hr.attendance.tardiness.resultsoffenses')
                ->with('offenses', $offenses);
        }else{
            return view('hr.attendance.tardiness.index')
                ->with('offenses', $offenses);
        }

    }
    public function tardinessoffense(Request $request)
    {
        $checkifexists = DB::table('hr_offenses')
            ->where('title','like','%'.$request->get('title').'%')
            ->where('deleted','0')
            ->where('type','1')
            ->first();

        if($request->get('action') == 'addoffense')
        {
            if($checkifexists)
            {
                return 0;
            }else{
                DB::table('hr_offenses')
                    ->insert([
                        'title'                 => $request->get('title'),
                        'description'           => $request->get('description'),
                        'type'                  => 1,
                        'createdby'             => auth()->user()->id,
                        'createddatetime'       => date('Y-m-d H:i:s')
                    ]);
    
                return 1;
            }
        }
        elseif($request->get('action') == 'editoffense')
        {
            try{
                // return $request->get('offenseid');
                DB::table('hr_offenses')
                    ->where('id', $request->get('offenseid'))
                    ->update([
                        'title'           => $request->get('title'),
                        'description'     => $request->get('description'),
                        'updatedby'       => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
                return 1;
            }catch(\Exception $error)
            {
                return 0;
            }
        }
        elseif($request->get('action') == 'deleteoffense')
        {
            try{
                // return $request->get('offenseid');
                DB::table('hr_offenses')
                    ->where('id', $request->get('offenseid'))
                    ->update([
                        'deleted'         => 1,
                        'deletedby'       => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
                return 1;
            }catch(\Exception $error)
            {
                return 0;
            }
        }
    }
    public function tardinessgenerate(Request $request)
    {
        // return date("Y-m-d", strtotime($request->get('week')));
        // return $request->all();
        $employees = DB::table('teacher')
            ->select('title','id','firstname','middlename','lastname','suffix')
            ->where('deleted','0')
            ->orderBy('lastname','asc')
            ->get();

        if($request->has('employeeid'))
        {
            $employees = collect($employees)->where('id', $request->get('employeeid'))->values();
        }
        $days = array();
        $monthstart = date('Y-m-d', strtotime($request->get('week')));
        $monthend = date('Y-m-t', strtotime($request->get('week')));
        
        array_push($days,$monthstart);
        $initdate = $monthstart;
        for($x=0; $x<6; $x++)
        {
            array_push($days,date('Y-m-d', strtotime($initdate . ' +1 day')));
            $initdate = date('Y-m-d', strtotime($initdate . ' +1 day'));
        }
        // return $days;
        if(count($employees)>0)
        {
            foreach($employees as $employee)
            {
                $employee->firstname = ucwords(strtolower($employee->firstname));
                $employee->middlename = ucwords(strtolower($employee->middlename));

                // try{
                $dates = \App\Models\HR\HREmployeeAttendance::gethours($days, $employee->id);
                    // $employee->records = $days;
                    // return $days;
                // }catch(\Exception $error)
                // {
                //     // return $days;
                //     $dates = \App\Models\HR\HREmployeeAttendance::gethours($days, $employee->id);
                //     // return $dates;
                // }
                $employee->records = collect($dates)->filter(function ($value, $key) {
                    return $value->latehours > 0  ||  $value->undertimehours > 0;
                })->values();
                // if($employee->id == 21)
                // {
                //     return $employee->records;
                // }
                $employee->offenses   = DB::table('hr_offenseslist')
                    ->select('hr_offenseslist.*')
                    ->join('hr_offenses','hr_offenseslist.offenseid','=','hr_offenses.id')
                    ->where('employeeid',$employee->id)
                    ->where('weekid',$request->get('week'))
                    ->where('hr_offenseslist.deleted','0')
                    ->where('hr_offenses.deleted','0')
                    ->where('hr_offenses.type','1')
                    ->get();
                
            }
        }
        // // $employees = collect($employees)->where('latehours','>',0)->orWhere('undertimehours','>',0)->values();
        // $employees = collect($employees)->filter(function ($value, $key) {
        //     return collect($value->records)->sum('latehours') > 0  ||  collect($value->records)->sum('undertimehours') > 0;
        // })->values();
        $offenses = DB::table('hr_offenses')
            ->where('deleted','0')
            ->where('type','1')
            ->get();

        if(!$request->has('export'))
        {
            return view('hr.attendance.tardiness.resultsemployees')
                ->with('employees', $employees)
                ->with('offenses', $offenses);
        }else{
            $weekid = $request->get('week');
            // pdf_tardiness.blade.php
            if($request->get('exportclass') == 1)
            {
                $employee = collect($employees)->where('id', $request->get('employeeid'))->first();
                $pdf = PDF::loadview('hr/attendance/tardiness/pdf_employeetardiness',compact('offenses','employee','weekid','days'))->setPaper('portrait');
    
                return $pdf->stream($weekid.' Tardiness - '.$employee->lastname.'_'.$employee->firstname.'.pdf');
            }else{
                $pdf = PDF::loadview('hr/attendance/tardiness/pdf_tardiness',compact('offenses','employees','weekid','days'))->setPaper('portrait');
    
                return $pdf->stream($weekid.' Tardiness.pdf');
            }
        }
    }
    public function tardinessmarkoffense(Request $request)
    {
        $checkifexists = DB::table('hr_offenseslist')
            ->select('hr_offenseslist.*')
            ->join('hr_offenses','hr_offenseslist.offenseid','=','hr_offenses.id')
            ->where('employeeid', $request->get('empid'))
            ->where('weekid', $request->get('weekid'))
            ->where('offenseid', $request->get('offenseid'))
            ->where('hr_offenses.type','1')
            ->where('hr_offenses.deleted','0')
            ->first();

        if($checkifexists)
        {
            if($request->get('status') == 0 && $checkifexists->deleted == 0)
            {
                DB::table('hr_offenseslist')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'deleted'         => 1,
                        'deletedby'       => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
            }
            if($request->get('status') == 1 && $checkifexists->deleted == 1)
            {
                DB::table('hr_offenseslist')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'deleted'         => 0,
                        'updatedby'       => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
            }
        }else{
            if($request->get('status') == 1)
            {
                DB::table('hr_offenseslist')
                    ->insert([
                        'employeeid'          => $request->get('empid'),
                        'weekid'              => $request->get('weekid'),
                        'offenseid'           => $request->get('offenseid'),
                        'createdby'           => auth()->user()->id,
                        'createddatetime'     => date('Y-m-d H:i:s')
                    ]);
            }
        }
        
    }

    public function updatetapstate(Request $request){

        $id = $request->get('logid');
        $timestatus = $request->get('timestatus');
        $empid = $request->get('empid');

        $taphistory = DB::table('taphistory')
            ->where('id', $id)
            ->where('deleted','0')
            ->where('studid',$empid)
            ->update([
                'tapstate' => $timestatus,
                'createdby'           => auth()->user()->id,
                'createddatetime'     => date('Y-m-d H:i:s')
            ]);

        return array((object)[
            'status' => 1,
            'message' => 'Updated Successfully!',
        ]);
    }
}
