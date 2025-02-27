<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;
use Crypt;
use File;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Models\HR\HRDeductions;
use App\Models\HR\HREmployeeAttendance;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class HREmployeeParttimeController extends Controller
{
    public function index(Request $request)
    {
        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();
        
        // $semester = DB::table('semester')
        //     ->where('isactive', 1)
        //     ->first();

        $semester = DB::table('hr_semester')
            ->where('isactive', 1)
            ->first();

        $schedules = DB::table('schedulecoding')
            ->leftJoin('schedulecodingdetails', 'schedulecoding.id', '=', 'schedulecodingdetails.headerid')
            ->leftJoin('college_subjects', 'schedulecoding.subjid', '=', 'college_subjects.id')
            ->where('schedulecoding.syid', $sy->id)
            ->when(isset($semester) && isset($semester->id), function($query) use ($semester) {
               return $query->where('schedulecoding.semid', $semester->id) ;
            })
            // ->where('schedulecoding.teacherid',  77)
            ->where('schedulecoding.deleted', '0')
            ->where('schedulecodingdetails.deleted', '0')
            ->get();

        $groupedData = $schedules->groupBy(function ($item) {
            return $item->id;
        });

        return view('hr.employees.parttime')
               ->with('sy', $sy)
               ->with('semester', $semester)
               ->with('parttimesched', $groupedData);
    }

    public function perhour(Request $request)
    {
        $employees = DB::table('teacher')
            ->select('teacher.id', DB::raw('CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) AS full_name'), 'suffix', 'amount as salaryamount', 'utype as designation', 'employee_basicsalaryinfo.salarybasistype')
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get();

        
        return view('hr.employees.clperhour')
        ->with('employees', $employees);

    }

    public function loadhourlyrates(Request $request)
    {
        $hourlyrates = DB::table('employee_clhourlyrate')
            ->where('deleted', '0')
            ->get();

        return $hourlyrates;
    }

    public function addhourlyrate(Request $request)
    {
        $hourlyamount = $request->get('hourlyamount');
        $ifexist = DB::table('employee_clhourlyrate')
            ->where('amount', $hourlyamount)
            ->count();

        if ($ifexist) {
            return array((object)[
                'status' => 0,
                'message' => 'Amount Exist!',
            ]);
        } else {
            $data = DB::table('employee_clhourlyrate')
                ->insert([
                    'amount' => $hourlyamount,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);
            
            return array((object)[
                'status' => 1,
                'message' => 'Added Successfully!',
            ]);
        }
    }

    public function addhourlyrateperemp(Request $request)
    {
        $teacherid = $request->get('teacherid');
        $hourlyamount = $request->get('hourlyamount');
       
        $data = DB::table('employee_basicsalaryinfo')
            ->where('employeeid', $teacherid)
            ->where('deleted', 0)
            ->update([
                'clsubjperhour' => $hourlyamount
            ]);
        
        return array((object)[
            'status' => 1,
            'message' => 'Updated Successfully!',
        ]);
    
    }

    public function addhourlyrateperrow(Request $request)
    {
        $peremployeesrates = $request->get('employeesarray');
        $responses = [];
        if (empty($peremployeesrates)) {
            return response()->json([
                (object)[
                    'status' => 0,
                    'message' => 'Nothing to be Updated!',
                ]
            ]);
        }

        foreach ($peremployeesrates as $peremployeesrate) {
            // Update the existing record
            DB::table('employee_basicsalaryinfo')
                ->where('employeeid', $peremployeesrate['teacherid']) // Use the ID of the existing record
                ->update([
                    'clsubjperhour' =>  $peremployeesrate['amount']
                ]);

            $responses[] = (object)[
                'status' => 1,
                'message' => 'Updated Successfully!'
            ];
            
        }

        
    
        return response()->json($responses);
    }
    
    // This function is to load all employees
    public function loademployees(Request $request)
    {
        $employees = DB::table('teacher')
            ->select('teacher.id', DB::raw('CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) AS full_name'), 'suffix', 'amount as salaryamount', 'utype as designation', 'employee_basicsalaryinfo.salarybasistype', 'employee_basicsalaryinfo.clsubjperhour')
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get();

        
        return $employees;
    }

    // This function is to load all employees
    public function allsched(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $schedules = DB::table('schedulecoding')
            ->leftJoin('schedulecodingdetails', 'schedulecoding.id', '=', 'schedulecodingdetails.headerid')
            ->leftJoin('college_subjects', 'schedulecoding.subjid', '=', 'college_subjects.id')
            ->where('schedulecoding.syid', $syid)
            ->where('schedulecoding.semid', $semid)
            ->where('schedulecoding.deleted', '0')
            // ->where('schedulecoding.teacherid', '!=', null)
            ->where('schedulecodingdetails.deleted', '0')
            ->get();

        // $peremployees = $schedules->groupBy('teacherid');
        $groupedDatas = $schedules->groupBy(['teacherid','subjDesc','subjid']);
        $groupedDatasperday = $schedules;

        $transformedData = [];

        foreach ($groupedDatas as $teacherid => $teacherData) {
            $teacherArray = [
                'teacherid' => $teacherid,
                'teacherdata' => [],
            ];

            foreach ($teacherData as $subjectDesc => $subjectData) {
                foreach ($subjectData as $subjid => $subjectLogs) {
                    $firstLog = $subjectLogs->first();
                    if ($firstLog) {
                        $lecunits = $firstLog->lecunits;
                        $labunits = $firstLog->labunits;
                        $subjcode = $firstLog->subjCode;
                        $syid = $firstLog->syid;
                        $semid = $firstLog->semid;
                    }
                    $subjectArray = [
                        'subjectname' => $subjectDesc,
                        'subjid' => $subjid,
                        'subjcode' => $subjcode,
                        'labunits' => $labunits,
                        'lecunits' => $lecunits,
                        'syid' => $syid,
                        'semid' => $semid,
                        'subjectdata' => [
                            'subjectlogs' => $subjectLogs,
                        ],
                    ];

                    $teacherArray['teacherdata'][] = $subjectArray;
                }
            }
            $transformedData[] = $teacherArray;
            
        }
        

        foreach ($transformedData as $peremployeedata) {
            $subjects =  $peremployeedata['teacherdata'];
            $teacherid = $peremployeedata['teacherid'];

            foreach ($subjects as $subject) {
                $ifexistsubj = DB::table('employee_collegeloads')
                    ->where('subjid', $subject['subjid'])
                    ->where('employeeid', $teacherid)
                    ->where('deleted', 0)
                    ->count();

                if (!$ifexistsubj) {
                    DB::table('employee_collegeloads')
                        ->insert([
                            'employeeid'        => $teacherid,
                            'subjdesc'          => $subject['subjectname'],
                            'subjcode'          => $subject['subjcode'],
                            'subjid'            => $subject['subjid'],
                            'lecunits'          => $subject['lecunits'],
                            'labunits'          => $subject['labunits'],
                            'semid'             => $subject['semid'],
                            'syid'              => $subject['syid'],
                            'subjtype'          => 1,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        // return $groupedDatasperday;
        foreach ($groupedDatasperday as $perdaysched) {
            
            // return collect($perdaysched);
            $existsched = DB::table('employee_collegeschedules')
                ->where('subjid', $perdaysched->subjid)
                ->where('syid', $perdaysched->syid)
                ->where('semid', $perdaysched->semid)
                ->where('timestart', $perdaysched->timestart)
                ->where('timeend', $perdaysched->timeend)
                ->where('day', $perdaysched->day)
                ->where('employeeid', $perdaysched->teacherid)
                ->count();

            // return collect($perdaysched);
            if (!$existsched) {
                DB::table('employee_collegeschedules')
                    ->insert([
                        'employeeid'        => $perdaysched->teacherid,
                        'subjDesc'          => $perdaysched->subjDesc,
                        'code'          => $perdaysched->code,
                        'subjid'            => $perdaysched->subjid,
                        'lecunits'          => $perdaysched->lecunits,
                        'labunits'          => $perdaysched->labunits,
                        'semid'             => $perdaysched->semid,
                        'syid'              => $perdaysched->syid,
                        'day'              => $perdaysched->day,
                        'timestart'              => $perdaysched->timestart,
                        'timeend'              => $perdaysched->timeend,
                        'subjtype'          => 1,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }

        }
        

        return response()->json($transformedData);
        // $codesarr = [];
        // $dataarr = [];

        // foreach ($groupedDatas as $groupedData) {
            
        //     $dataobj = new \stdClass();
        //     $subjobj = new \stdClass();
        //     $dataobj->subjectCount = count($groupedData);

        //     foreach ($groupedData as $subjdatas) {
        //         foreach ($subjdatas as $subjdata) {
        //             $dataobj->teacherid = $subjdata->first()->teacherid;
        //         }
        //     }
        //     $dataobj->subjects = $groupedData;
        //     $dataarr[] = $dataobj;
        // }

        // return response()->json($dataarr);

    }   
    public static function loadteachersschedule(Request $request){
        $teacherid = $request->get('teacherid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $teachingloads = [];

        $loads = DB::table('employee_collegeloads')
            ->where('employeeid', $teacherid)
            ->where('deleted', 0)
            ->get();

        $subjid = $loads->pluck('subjid');

        $schedules = DB::table('schedulecoding')
            ->leftJoin('schedulecodingdetails', 'schedulecoding.id', '=', 'schedulecodingdetails.headerid')
            ->leftJoin('college_subjects', 'schedulecoding.subjid', '=', 'college_subjects.id')
            ->where('schedulecoding.syid', $syid)
            ->where('schedulecoding.semid', $semid)
            ->where('schedulecoding.teacherid', $teacherid)
            ->where('schedulecoding.deleted', '0')
            ->orderBy('schedulecodingdetails.day', 'ASC')
            ->where('schedulecodingdetails.deleted', '0')
            ->get();

            
        // mao ning dayschedule ni teacher
        $collegeschedules = DB::table('employee_collegeschedules')
            ->where('semid', $semid)
            ->where('syid', $syid)
            ->where('employeeid', $teacherid)
            ->get();

        $groupedDatas = $schedules->groupBy(['subjid']);

        foreach ($groupedDatas as $data) {
            $days = []; 
            $subjectname = collect($data)->where('deleted', 0)->first()->subjDesc;
            $lecunits = collect($data)->where('deleted', 0)->first()->lecunits;
            $labunits = collect($data)->where('deleted', 0)->first()->labunits;
            $subjcode = collect($data)->where('deleted', 0)->first()->subjCode;
            $subjid = collect($data)->where('deleted', 0)->first()->id;

            foreach ($data as $day) {
                $d = $day->day;

                if ($d == 1) {
                    $daydesc = 'Monday';
                } elseif ($d == 2){
                    $daydesc = 'Tuesday';
                } elseif ($d == 3){
                    $daydesc = 'Wednesday';
                } elseif ($d == 4){
                    $daydesc = 'Thursday';
                } elseif ($d == 5){
                    $daydesc = 'Friday';
                } elseif ($d == 6){
                    $daydesc = 'Saturday';
                } elseif ($d == 7){
                    $daydesc = 'Sunday';
                }

                array_push($days, $daydesc);
            }
            
            $teacherArray = [
                'teacherid' => $teacherid,
                'subjectdesc' => $subjectname,
                'subjid' => $subjid,
                'lecunits' => $lecunits,
                'labunits' => $labunits,
                'subjcode' => $subjcode,
                'days' => $days,
                'schedule' => [],
            ];

            foreach ($data as $daySchedules) {

                $totalHours = 0;
                $startTime = '';
                $endTime = '';
                $schedule = json_decode(json_encode($daySchedules));
                $startTime = new DateTime($schedule->timestart);
                $endTime = new DateTime($schedule->timeend);
                $hours = $startTime->diff($endTime)->h;
                
                if ($schedule->day == 1) {
                    $dayname = 'Monday';
                } elseif ($schedule->day == 2){
                    $dayname = 'Tuesday';
                } elseif ($schedule->day == 3){
                    $dayname = 'Wednesday';
                } elseif ($schedule->day == 4){
                    $dayname = 'Thursday';
                } elseif ($schedule->day == 5){
                    $dayname = 'Friday';
                } elseif ($schedule->day == 6){
                    $dayname = 'Saturday';
                } elseif ($schedule->day == 7){
                    $dayname = 'Sunday';
                }
         
                $dayArray = [
                    'day' => $daySchedules->day,
                    'dayname' => $dayname,
                    'totalhours' => $hours,
                    'subjcode' => $daySchedules->code,
                    'subjid' => $daySchedules->subjid,
                    'starttime' =>Carbon::createFromFormat('H:i:s', $schedule->timestart)->format('h:i A'),
                    'endtime' => Carbon::createFromFormat('H:i:s', $schedule->timeend)->format('h:i A'),
                    'stime' => $schedule->timestart,
                    'etime' => $schedule->timeend,
                    'schedules' => $daySchedules,
                ];
                $teacherArray['schedule'][] = $dayArray;
            }
      
           
            $teachingloads[] = $teacherArray;
        }

       
        foreach ($teachingloads as &$teachingload) {
            $teachingload['subjtype'] = 0;
            $schedules = $teachingload['schedule']; // this variable here will filter or get the schedules per subject
            
            foreach ($loads as $load) {
                if ($teachingload['subjid'] == $load->subjid) {
                    $teachingload['subjtype'] = $load->subjtype;
                    break; // Break the loop when a match is found
                }
            }

            foreach ($schedules as &$schedule) {
                foreach ($collegeschedules as $collegeschedule) { // this foreach will loop collegeschedules 
                    if (
                        $collegeschedule->subjid == $schedule['subjid'] && 
                        $collegeschedule->day == $schedule['day'] && 
                        $collegeschedule->timestart ==  $schedule['stime'] && 
                        $collegeschedule->timeend ==  $schedule['etime'] &&
                        $collegeschedule->code ==  $schedule['subjcode']
                        ) {
                        $schedule['subjtype'] = $collegeschedule->subjtype;
                    }
                }
            }
            $teachingload['schedule'] = $schedules;
            
        }
        
        return [
            'teachingloads' => collect($teachingloads), 
            'load' => $loads
        ];
    }

    public static function changesubjtype(Request $request){
        $teacherid = $request->get('teacherid');
        $optionstatus = $request->get('optionstatus');
        $subjectid = $request->get('subjectid');
       
        $data = DB::table('employee_collegeloads')
            ->where('subjid', $subjectid)
            ->where('employeeid', $teacherid)
            ->where('deleted', 0)
            ->update([
                'subjtype' => $optionstatus,
                'updatedby'         => auth()->user()->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);

        return response()->json([
            (object)[
                'status' => 1,
                'message' => 'Updated Successfully!',
            ]
        ]);
    }

    public static function changesubjtypepersubj(Request $request){
        $teacherid = $request->get('teacherid');
        $optionstatus = $request->get('optionstatus');
        $subjectid = $request->get('subjectid');
        $day = $request->get('day');
        $code = $request->get('code');
        $stime = Carbon::createFromFormat('h:i A', $request->get('stime'))->format('H:i:s');
        $etime = Carbon::createFromFormat('h:i A', $request->get('etime'))->format('H:i:s');
        
        $data = DB::table('employee_collegeschedules')
            ->where('subjid', $subjectid)
            ->where('employeeid', $teacherid)
            ->where('code', $code)
            ->where('timestart', $stime)
            ->where('timeend', $etime)
            ->where('day', $day)
            ->where('deleted', 0)
            ->update([
                'subjtype' => $optionstatus,
                'updatedby'         => auth()->user()->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);
            
        return response()->json([
            (object)[
                'status' => 1,
                'message' => 'Updated Successfully!',
            ]
        ]);
    }

    public static function allschedteacher(Request $request){

        $dataid = $request->get('dataid');
        $syid = $request->get('syid');
        $semid =  $request->get('semid');
        $teacherid =  $request->get('teacherid');
        $startdate =  $request->get('startdate');
        $enddate =  $request->get('enddate');

        $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
            ->select('employee_basicsalaryinfo.*','employee_basistype.type as salarytype','employee_basistype.type as ratetype')
            ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
            ->where('employee_basicsalaryinfo.deleted','0')
            ->where('employee_basicsalaryinfo.employeeid', $teacherid)
            ->first();

        // return collect($basicsalaryinfo);
        // get attendance 
        $employeecustomsched = DB::table('employee_customtimesched')
            ->where('employeeid', $teacherid)
            ->where('shiftid', '!=', null)
            ->where('createdby', '!=', null)
            ->where('deleted', 0)
            ->first();

        // return collect($employeecustomsched);
        $interval = new DateInterval('P1D');    
        $realEnd = new DateTime($enddate);
        $realEnd->add($interval);    
        $period = new DatePeriod(new DateTime($startdate), $interval, $realEnd);  
        $dates = array();


        foreach($period as $date) {  
            if(strtolower($date->format('l')) == 'monday')
            {
                $dates[] = $date->format('Y-m-d'); 
            }   
            elseif(strtolower($date->format('l')) == 'tuesday')
            {
                $dates[] = $date->format('Y-m-d'); 
            }   
            elseif(strtolower($date->format('l')) == 'wednesday')
            {
                $dates[] = $date->format('Y-m-d'); 
            }   
            elseif(strtolower($date->format('l')) == 'thursday')
            {
                $dates[] = $date->format('Y-m-d'); 
            }   
            elseif(strtolower($date->format('l')) == 'friday')
            {
                $dates[] = $date->format('Y-m-d'); 
            }   
            elseif(strtolower($date->format('l')) == 'saturday')
            {
                $dates[] = $date->format('Y-m-d'); 
            }   
            elseif(strtolower($date->format('l')) == 'sunday')
            {
                $dates[] = $date->format('Y-m-d'); 
            }  
        }

        $startDate = new DateTime($startdate);
        $endDate = new DateTime($enddate);

        $mondayCount = 0;
        $tuesdayCount = 0;
        $wednesdayCount = 0;
        $thursdayCount = 0;
        $fridayCount = 0;
        $saturdayCount = 0;
        $sundayCount = 0;
        $totalworkinghours = 0;
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
        }
        
        $taphistory = DB::table('taphistory')
            // ->where('tdate', $dates)
            ->where('studid', $teacherid)
            ->whereBetween('tdate', [$startdate,$enddate])
            ->where('utype', '!=','7')
            ->orderBy('ttime','asc')
            ->where('deleted','0')
            ->get();

        $hr_attendance = DB::table('hr_attendance')
            // ->where('tdate', $dates)
            ->where('studid', $teacherid)
            ->whereBetween('tdate', [$startdate,$enddate])
            ->where('deleted',0)
            ->orderBy('ttime','asc')
            ->get();

        // $departmentid = null;
        $departmentid = DB::table('teacher')
            ->select(
                'hr_departments.id as departmentid',
                'teacher.id as tid'
                )
            ->leftJoin('employee_personalinfo','teacher.id','employee_personalinfo.employeeid')
            ->leftJoin('civilstatus','employee_personalinfo.maritalstatusid','civilstatus.civilstatus')
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('hr_departments','employee_personalinfo.departmentid','hr_departments.id')
            ->where('teacher.id',  $teacherid)
            ->first()->departmentid;


        if (!empty($dates)) {
            $attendance = \App\Models\HR\HREmployeeAttendance::gethours($dates, $teacherid,$taphistory,$hr_attendance,$departmentid);
        } else {
            $attendance = [];
        }
        
        if (!$employeecustomsched) {
           foreach ($attendance as $at) {
            $at->amtimein = $at->timeinam;
            $at->amtimeout = $at->timeoutam;
            $at->pmtimein = $at->timeinpm;
            $at->pmtimeout = $at->timeoutpm;
           }
        }
        //$attendance = collect($attendance)->where('day', 'Saturday')->values();
        
        // get holiday
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
                foreach ($attendance as $att) {
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
        } else {

            if (count($holidaydates) > 0) {
                foreach ($attendance as $att) {
                    $att->holiday = 0;
                    $att->holidayname = "";

                    foreach ($holidaydates as $holidaydate) {
                        // if ($att->date == $holidaydate['date'] && $att->status == 2 && ($holidaydate['holidaytype'] == 1 || $holidaydate['type'] == 'Regular Holiday')) {
                        if ($att->date == $holidaydate['date'] && $att->status == 2) {
                            $att->status = 1;
                            $att->totalworkinghours = $totalworkinghours;
                            $att->holiday = 1;
                            $att->holidayname = $holidaydate['holidayname'];
                            
                        } else if($att->date == $holidaydate['date'] && $att->status == 1){
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
        // for saturday
        // return $attendance;

        if ($employeecustomsched) {
            if ($basicsalaryinfo) {
                if ($basicsalaryinfo->halfdaysat == 1) {
                    foreach ($attendance as $attt) {
                        if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                        
                            $attt->amtimein = $employeecustomsched->amin;
                            $attt->amtimeout = $employeecustomsched->amout;
                            $attt->timeinam = $employeecustomsched->amin;
                            $attt->timeoutam = $employeecustomsched->amout;
                            $attt->amin = $employeecustomsched->amin;
                            $attt->amout = $employeecustomsched->amout;
                            $attt->status = 1;
                            
                            
                            
                        }
                        
                    }
                } else if($basicsalaryinfo->halfdaysat == 2){
                    foreach ($attendance as $attt) {
                        if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                            $attt->pmtimein = $employeecustomsched->pmin;
                            $attt->pmtimeout = $employeecustomsched->pmout;
                            $attt->timeinpm = $employeecustomsched->pmin;
                            $attt->timeoutpm = $employeecustomsched->pmout;
                            $attt->pmin = $employeecustomsched->pmin;
                            $attt->pmout = $employeecustomsched->pmout;
                            $attt->status = 1;
    
                            if ($attt->amin == null || $attt->amout == null ) {
                                $attt->totalworkinghours = $pmhours;
                                // $attt->totalworkinghoursrender = $pmhours;
                                $attt->lateamminutes = $pmhours * 60;
                                $attt->lateamhours = $pmhours;
                                // $attt->undertimeamhours = $pmhours;
                            } else {
                                $attt->totalworkinghours = $pmhours + ($amhours - $attt->lateamhours);
                                // $attt->totalworkinghoursrender = $pmhours + ($amhours - $attt->lateamhours);
                                $attt->latepmminutes = 0;
                                $attt->latepmhours = 0;
                                $attt->undertimepmhours = 0;
                            }
                        }
                        
                    }
                  
                } else if($basicsalaryinfo->halfdaysat == 0) {
                    if ($basicsalaryinfo->attendancebased == 1) {
                        foreach ($attendance as $attt) {
                            if ($attt->status == 1) {
                                
                            } else {
                                if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                                    $attt->amtimein = $employeecustomsched->amin;
                                    $attt->amtimeout = $employeecustomsched->amout;
                                    $attt->timeinam = $employeecustomsched->amin;
                                    $attt->timeoutam = $employeecustomsched->amout;
                                    $attt->amin = $employeecustomsched->amin;
                                    $attt->amout = $employeecustomsched->amout;
                                    $attt->pmtimein = $employeecustomsched->pmin;
                                    $attt->pmtimeout = $employeecustomsched->pmout;
                                    $attt->timeinpm = $employeecustomsched->pmin;
                                    $attt->timeoutpm = $employeecustomsched->pmout;
                                    $attt->pmin = $employeecustomsched->pmin;
                                    $attt->pmout = $employeecustomsched->pmout;
                                    $attt->status = 1;
                                    $attt->totalworkinghours = $totalworkinghours;
                                    $attt->lateamminutes = 0;
                                    $attt->lateamhours = 0;
                                    $attt->undertimeamhours = 0;
                                    $attt->latepmminutes = 0;
                                    $attt->latepmhours = 0;
                                    $attt->undertimepmhours = 0;
                                }
                            }
                            
                        }
                    } else {
                        foreach ($attendance as $attt) {
                            if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                                // $attt->amtimein = $employeecustomsched->amin;
                                // $attt->amtimeout = $employeecustomsched->amout;
                                // $attt->timeinam = $employeecustomsched->amin;
                                // $attt->timeoutam = $employeecustomsched->amout;
                                // $attt->amin = $employeecustomsched->amin;
                                // $attt->amout = $employeecustomsched->amout;
                                // $attt->pmtimein = $employeecustomsched->pmin;
                                // $attt->pmtimeout = $employeecustomsched->pmout;
                                // $attt->timeinpm = $employeecustomsched->pmin;
                                $attt->timeoutpm = $employeecustomsched->pmout;
                                // $attt->pmin = $employeecustomsched->pmin;
                                // $attt->pmout = $employeecustomsched->pmout;
                                // $attt->status = 1;
                                // $attt->totalworkinghours = $totalworkinghours;
                                // $attt->lateamminutes = 0;
                                // $attt->lateamhours = 0;
                                // $attt->undertimeamhours = 0;
                                // $attt->latepmminutes = 0;
                                // $attt->latepmhours = 0;
                                // $attt->undertimepmhours = 0;
                                
                            }
                        }
                    }
                    
                }
            }
        }
        
        // return $attendance;
        // close get attendance

        // for leaves
        $payrollperiod = new \stdClass();

        $payrollperiod->id = 1;
        $payrollperiod->datefrom = date('Y-m-d', strtotime($startdate));
        $payrollperiod->dateto = date('Y-m-d', strtotime($enddate));
        $payrollperiod->status = 1;
        $payrollperiod->leapyear = 0;
        $payrollperiod->updatedby = null;
        $payrollperiod->updateddatetime = null;
        $payrollperiod->createdby = null;
        $payrollperiod->createddatetime = null;
        $payrollperiod->deleted = 0;
        $payrollperiod->deletedby = null;
        $payrollperiod->deleteddatetime = null;
        $payrollperiod->salarytypeid = null;


        $leavedetails = \App\Models\HR\HRSalaryDetails::getleavesapplied($teacherid,$payrollperiod);
        // return $leavedetails;

        if (count($leavedetails)>0) {
            foreach ($leavedetails as $leavesdetail) {
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

        // return $leavedetails;
        if(count($attendance)>0){
            foreach ($attendance as $lognull) {
                $lognull->leavetype = '';
                $lognull->leavedaystatus = '';
                $lognull->daycoverd = '';
                $lognull->leaveremarks = '';
               
                if (count($leavedetails)>0) {

                    foreach ($leavedetails as $employeeleavesapp) {
                        if ($employeeleavesapp->ldate == $lognull->date) {
                            $lognull->leavetype = $employeeleavesapp->leave_type;
                            $lognull->leavedaystatus = $employeeleavesapp->halfday;
                            $lognull->daycoverd = $employeeleavesapp->daycoverd;
                            $lognull->leaveremarks = $employeeleavesapp->remarks ?? '';
                        }
                    }
                }
            }

        }


        if ($employeecustomsched) {
            foreach ($attendance as $att) {
                $att->appliedleave = 0;
                if ($att->status == 1) {
    
                    if ($att->leavedaystatus === 0) {
    
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
                        // $att->totalworkinghoursrender = $totalworkinghours;
                        // $att->leavetype = $leavedetail->leave_type;
                        $att->latepmminutes = 0;
                        $att->latepmhours = 0;
                        $att->undertimepmhours = 0;
                        $att->lateamminutes = 0;
                        $att->lateamhours = 0;
                        $att->undertimeamhours = 0;
                        $att->latehours = 0;
                        $att->appliedleave = 1;
    
    
                    } else if($att->leavedaystatus == 1){
    
                        $att->amtimein = $employeecustomsched->amin;
                        $att->amtimeout = $employeecustomsched->amout;
                        $att->timeinam = $employeecustomsched->amin;
                        $att->timeoutam = $employeecustomsched->amout;
                        $att->amin = $employeecustomsched->amin;
                        $att->amout = $employeecustomsched->amout;
              
                        $att->totalworkinghours = floor(($att->totalworkinghours + $amhours) * 100) / 100;
                        // $att->totalworkinghoursrender = floor(($att->totalworkinghours) * 100) / 100;
                        $att->totalworkinghoursflexi = floor(($att->totalworkinghours) * 100) / 100;
                        // $att->flexihours = floor(($att->totalworkinghours) * 100) / 100;
                 
    
                        if (($att->timeinpm != null || $att->pmtimein != null) && ($att->timeoutpm == null || $att->pmtimeout == null)) {
                            $att->latepmminutes = 0;
                            $att->latepmhours = 0;
                            $att->undertimepmhours = $pmhours - $att->latepmhours;
                            $att->undertimeminutes = ($pmhours -$att->latepmhours) * 60;
                        }else {
                            $att->latepmminutes = $att->latepmhours * 60;
                            $att->latepmhours = $att->latepmhours;
                            $att->undertimeamhours = 0;
                            $att->lateamhours = 0;
                            $att->lateamminutes = 0;
                            
                        }
                     
                    } else if($att->leavedaystatus == 2){
                        $att->pmtimein = $employeecustomsched->pmin;
                        $att->pmtimeout = $employeecustomsched->pmout;
                        $att->timeinpm = $employeecustomsched->pmin;
                        $att->timeoutpm = $employeecustomsched->pmout;
                        $att->pmin = $employeecustomsched->pmin;
                        $att->pmout = $employeecustomsched->pmout;
                        // $att->leavetype = $leavedetail->leave_type;
                        $att->status = 1;
                        // return $att->date;
    
    
                        $att->totalworkinghours = floor(($att->totalworkinghours + $pmhours) * 100) / 100;
                        // $att->totalworkinghours = floor(($att->totalworkinghoursrender + $pmhours) * 100) / 100;
                        // $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $pmhours) * 100) / 100;
                        $att->totalworkinghoursflexi = floor(($att->totalworkinghoursflexi + $pmhours) * 100) / 100;
                        // $att->flexihours = floor(($att->flexihours + $pmhours) * 100) / 100;
    
    
                        if (($att->timeinam == null || $att->amtimein == null) ) {
                            // return 'a';
                            $att->lateamminutes = $amhours * 60;
                            $att->lateamhours = $amhours;
                            $att->undertimepmhours = 0;
                            $att->undertimeminutes = 0;
                            $att->latepmminutes = 0;
                            $att->latepmhours = 0;
                        }else {
    
                            $att->lateaminutes = $att->lateamhours * 60;
                            $att->lateamhours = $att->lateamhours;
                            $att->undertimeamhours = 0;
    
                            $att->latepmhours = 0;
                            $att->latepmminutes = 0;
                        }
                        $att->appliedleave = 1;
    
                    }
                } else {

                    if ($att->leavedaystatus === 0) {
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
                        $att->status = 2;
                        $att->totalworkinghours = $totalworkinghours;
                        // $att->totalworkinghoursrender = $totalworkinghours;
                        // $att->leavetype = $leavedetail->leave_type;
                        $att->latepmminutes = 0;
                        $att->latepmhours = 0;
                        $att->undertimepmhours = 0;
                        $att->lateamminutes = 0;
                        $att->lateamhours = 0;
                        $att->undertimeamhours = 0;
                        $att->latehours = 0;
                        $att->appliedleave = 1;
    
    
                    } else if($att->leavedaystatus == 1){
    
                        $att->amtimein = $employeecustomsched->amin;
                        $att->amtimeout = $employeecustomsched->amout;
                        $att->timeinam = $employeecustomsched->amin;
                        $att->timeoutam = $employeecustomsched->amout;
                        $att->amin = $employeecustomsched->amin;
                        $att->amout = $employeecustomsched->amout;
                        // $att->leavetype = $leavedetail->leave_type;
                        $att->status = 1;
    
    
    
                        $att->totalworkinghours = $amhours;
                        // $att->totalworkinghoursrender = $amhours;
                        $att->totalworkinghoursflexi = $amhours;
                        // $att->flexihours = $amhours;
                        $att->latepmminutes = 0;
                        $att->latepmhours = 0;
                        $att->undertimepmhours = $pmhours;
                        $att->undertimeminutes = $pmhours * 60;
                        $att->appliedleave = 1;
    
                        // return collect($att);
    
                    } else if($att->leavedaystatus == 2){
                        $att->pmtimein = $employeecustomsched->pmin;
                        $att->pmtimeout = $employeecustomsched->pmout;
                        $att->timeinpm = $employeecustomsched->pmin;
                        $att->timeoutpm = $employeecustomsched->pmout;
                        $att->pmin = $employeecustomsched->pmin;
                        $att->pmout = $employeecustomsched->pmout;
                        // $att->leavetype = $leavedetail->leave_type;
                        $att->status = 1;
                        $att->totalworkinghours = $pmhours;
                        $att->totalworkinghours = $pmhours;
                        // $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $att->latepmhours) * 100) / 100;
                        $att->totalworkinghoursflexi =  $pmhours;
                        // $att->flexihours = $pmhours;
    
                        $att->lateamminutes = $amhours * 60;
                        $att->lateamhours = $amhours;
                        $att->undertimeamhours = 0;
                        $att->appliedleave = 1;
    
                    }
                }
    
            }

        } else { // if null employeecustomsched
            foreach ($attendance as $att) {
                $att->appliedleave = 0;
                if ($att->status == 1) {
    
                    if ($att->leavedaystatus === 0) {
                        $att->status = 1;
                        $att->latepmminutes = 0;
                        $att->latepmhours = 0;
                        $att->undertimepmhours = 0;
                        $att->lateamminutes = 0;
                        $att->lateamhours = 0;
                        $att->undertimeamhours = 0;
                        $att->latehours = 0;
                        $att->appliedleave = 1;
    
    
                    } else if($att->leavedaystatus == 1){
                   
                        if (($att->timeinpm != null || $att->pmtimein != null) && ($att->timeoutpm == null || $att->pmtimeout == null)) {
                            $att->latepmminutes = 0;
                            $att->latepmhours = 0;
                            $att->undertimepmhours = $pmhours - $att->latepmhours;
                            $att->undertimeminutes = ($pmhours -$att->latepmhours) * 60;
                        }else {
                            $att->latepmminutes = $att->latepmhours * 60;
                            $att->latepmhours = $att->latepmhours;
                            $att->undertimeamhours = 0;
                            $att->lateamhours = 0;
                            $att->lateamminutes = 0;
                            
                        }
                        $att->appliedleave = 1;
                     
                    } else if($att->leavedaystatus == 2){
                        $att->status = 1;
                        $att->totalworkinghours = floor(($att->totalworkinghours + $pmhours) * 100) / 100;
                        $att->totalworkinghoursflexi = floor(($att->totalworkinghoursflexi + $pmhours) * 100) / 100;
    
    
                        if (($att->timeinam == null || $att->amtimein == null) ) {
                            $att->lateamminutes = $amhours * 60;
                            $att->lateamhours = $amhours;
                            $att->undertimepmhours = 0;
                            $att->undertimeminutes = 0;
                            $att->latepmminutes = 0;
                            $att->latepmhours = 0;
                        }else {
    
                            $att->lateaminutes = $att->lateamhours * 60;
                            $att->lateamhours = $att->lateamhours;
                            $att->undertimeamhours = 0;
    
                            $att->latepmhours = 0;
                            $att->latepmminutes = 0;
                        }
                        $att->appliedleave = 1;
    
                    }

                } else {
                    if ($att->leavedaystatus === 0) {
                       
                        $att->status = 1;
                        $att->latepmminutes = 0;
                        $att->latepmhours = 0;
                        $att->undertimepmhours = 0;
                        $att->lateamminutes = 0;
                        $att->lateamhours = 0;
                        $att->undertimeamhours = 0;
                        $att->latehours = 0;
                        $att->appliedleave = 1;
    
    
                    } else if($att->leavedaystatus == 1){
    
                        $att->status = 1;
                        $att->latepmminutes = 0;
                        $att->latepmhours = 0;
                        $att->undertimepmhours = $pmhours;
                        $att->undertimeminutes = $pmhours * 60;
                        $att->appliedleave = 1;
    
    
                    } else if($att->leavedaystatus == 2){
                        $att->status = 1;
                        $att->lateamminutes = $amhours * 60;
                        $att->lateamhours = $amhours;
                        $att->undertimeamhours = 0;
                        $att->appliedleave = 1;
    
                    }
                }
    
            }
        }
        
        // return $attendance;
        if ($dataid == 1) {
            $collegesched = DB::table('employee_collegeloads')
                ->where('employeeid', $teacherid)
                ->where('deleted', 0)
                ->get();

            
            $regularload = collect($collegesched)->where('subjtype', 1)->values()->pluck('subjid');
            $overload = collect($collegesched)->where('subjtype', 2)->values()->pluck('subjid');
            $partimeload = collect($collegesched)->where('subjtype', 3)->values()->pluck('subjid');


            // return $partimeload;
            $schedules = DB::table('schedulecoding')
                ->leftJoin('schedulecodingdetails', 'schedulecoding.id', '=', 'schedulecodingdetails.headerid')
                ->leftJoin('college_subjects', 'schedulecoding.subjid', '=', 'college_subjects.id')
                ->where('schedulecoding.syid', $syid)
                ->where('schedulecoding.semid', $semid)
                ->where('schedulecoding.teacherid', $teacherid)
                ->where('schedulecoding.deleted', '0')
                ->where('schedulecodingdetails.deleted', '0')
                ->orderBy('schedulecodingdetails.day', 'asc')
                ->orderBy('schedulecodingdetails.timestart', 'asc')
                ->get();

            $regularloadschedules = collect($schedules)->whereIn('subjid', $regularload)->values();
            $overloadschedules = collect($schedules)->whereIn('subjid', $overload)->values();
            $partimeloadschedules = collect($schedules)->whereIn('subjid', $partimeload)->values();

            $groupedDatasrl = $regularloadschedules->groupBy(['teacherid','day']);
            $groupedDatasol = $overloadschedules->groupBy(['teacherid','day']);
            $groupedDataspt = $partimeloadschedules->groupBy(['teacherid','day']);

        } else if($dataid == 2){

            $collegeschedules = DB::table('employee_collegeschedules')
                ->where('semid', $semid)
                ->where('syid', $syid)
                ->where('employeeid', $teacherid)
                ->orderBy('day', 'asc')
                ->orderBy('timestart', 'asc')
                ->get();
            
            $regularloadschedules = collect($collegeschedules)->where('subjtype', 1)->values();
            $overloadschedules = collect($collegeschedules)->where('subjtype', 2)->values();
            $partimeloadschedules = collect($collegeschedules)->where('subjtype', 3)->values();

            $groupedDatasrl = $regularloadschedules->groupBy(['employeeid','day']);
            $groupedDatasol = $overloadschedules->groupBy(['employeeid','day']);
            $groupedDataspt = $partimeloadschedules->groupBy(['employeeid','day']);
        }

        
        // $groupedDatasrl = $regularloadschedules->groupBy([t='day']);
        // $groupedDatasol = $overloadschedules->groupBy(['day']);
        // $groupedDataspt = $partimeloadschedules->groupBy(['day']);
        // return $attendance;
        // $groupedDatas = $schedules->groupBy(['teacherid','day']);

        function getDayName($day) {
            switch ($day) {
                case 1: return 'Monday';
                case 2: return 'Tuesday';
                case 3: return 'Wednesday';
                case 4: return 'Thursday';
                case 5: return 'Friday';
                case 6: return 'Saturday';
                case 7: return 'Sunday';
                default: return ''; // Handle invalid day numbers
            }

        }
        $transformedDataregload = [];
        $transformedDataoverload = [];
        $transformedDataparttimeload = [];

        // for Regular Load
        foreach ($groupedDatasrl as $teacherid => $teacherDays) {

            $teacherArray = [
                'teacherid' => $teacherid,
                'schedule' => [],
            ];

            foreach ($teacherDays as $day => $daySchedules) {
                $totalHours = 0;

                // foreach ($daySchedules as $schedule) {
                //     $startTime = new DateTime($schedule->timestart);
                //     $endTime = new DateTime($schedule->timeend);
        
                //     // Calculate the time difference in hours
                //     $hours = $startTime->diff($endTime)->h;
        
                //     // Accumulate total hours for the day
                //     $totalHours += $hours;
                // }

                foreach ($daySchedules as $schedule) {

                    $startTime = new DateTime($schedule->timestart);
                    $endTime = new DateTime($schedule->timeend);
                
                    // Calculate the time difference
                    $diff = $startTime->diff($endTime);
                
                    // Get total hours and minutes
                    $hours = $diff->h;
                    $minutes = $diff->i;
                
                    // Convert minutes to decimal hours (e.g., 30 minutes = 0.5 hours)
                    $minutesDecimal = $minutes / 60;
                
                    // Total hours including minutes
                    $totalHours += $hours + $minutesDecimal;
                }


                $dayArray = [
                    'day' => getDayName($day),
                    'dayid' => $day,
                    'totalhours' => $totalHours,
                    'starttime' => $daySchedules[0]->timestart,
                    'endtime' => !empty($daySchedules) ? $daySchedules[count($daySchedules) - 1]->timeend : null,
                    'schedules' => $daySchedules,
                ];
                $teacherArray['schedule'][] = $dayArray;
            }
      
            $transformedDataregload[] = $teacherArray;
        }

        // return $attendance;
        // return $transformedDataregload;

        // for overload 
        foreach ($groupedDatasol as $teacherid => $teacherDays) {
            $teacherArray = [
                'teacherid' => $teacherid,
                'schedule' => [],
            ];

            foreach ($teacherDays as $day => $daySchedules) {
                $totalHours = 0;

                foreach ($daySchedules as $schedule) {
                    $startTime = new DateTime($schedule->timestart);
                    $endTime = new DateTime($schedule->timeend);
        
                    // Calculate the time difference in hours
                    // $hours = $startTime->diff($endTime)->h;
                    $interval = $startTime->diff($endTime);
                    $hours = $interval->h + ($interval->i / 60);
                    
                    // Accumulate total hours for the day
                    $totalHours += $hours;
                }

                $dayArray = [
                    'day' => getDayName($day),
                    'dayid' => $day,
                    'totalhours' => $totalHours,
                    'starttime' => $daySchedules[0]->timestart,
                    'endtime' => !empty($daySchedules) ? $daySchedules[count($daySchedules) - 1]->timeend : null,
                    'schedules' => $daySchedules,
                ];
                $teacherArray['schedule'][] = $dayArray;
            }
      
            $transformedDataoverload[] = $teacherArray;
        }

        
        // return $transformedDataoverload;
        // for Part Time
        foreach ($groupedDataspt as $teacherid => $teacherDays) {
            $teacherArray = [
                'teacherid' => $teacherid,
                'schedule' => [],
            ];

            foreach ($teacherDays as $day => $daySchedules) {
                $totalHours = 0;

                foreach ($daySchedules as $schedule) {
                    $startTime = new DateTime($schedule->timestart);
                    $endTime = new DateTime($schedule->timeend);
        
                    // Calculate the time difference in hours
                    // $hours = $startTime->diff($endTime)->h;

                    $interval = $startTime->diff($endTime);
                    $hours = $interval->h + ($interval->i / 60);
        
                    // Accumulate total hours for the day
                    $totalHours += $hours;
                }

                $dayArray = [
                    'day' => getDayName($day),
                    'dayid' => $day,
                    'totalhours' => $totalHours,
                    'starttime' => $daySchedules[0]->timestart,
                    'endtime' => !empty($daySchedules) ? $daySchedules[count($daySchedules) - 1]->timeend : null,
                    'schedules' => $daySchedules,
                ];
                $teacherArray['schedule'][] = $dayArray;
            }
      
            $transformedDataparttimeload[] = $teacherArray;
        }

        foreach ($attendance as $attdefault) {
            $attdefault->regularlate = 0;
            $attdefault->regularundertime = 0;
            $attdefault->overloadlate = 0;
            $attdefault->overloadundertime = 0;
            $attdefault->parttimelate = 0;
            $attdefault->parttimeundertime = 0;
            $attdefault->regularabsent = 0;
            $attdefault->overloadabsent = 0;
            $attdefault->parttimeabsent = 0;
        }

        if ($transformedDataregload) {
            $daysactiveloadsrl =  $transformedDataregload[0]['schedule'];

            // return collect($daysactiveloadsrl)->where('dayid', 2)->values();
             // for Regular Subjects

            foreach ($attendance as $att) {
                foreach ($daysactiveloadsrl as $activeloadregular) {

                    if ($att->day == $activeloadregular['day']) {
                        if ($att->timeinam != null || $att->amtimein != null) {

                            
                            $attTimeInAM = $att->timeinam ?? $att->amtimein;
                            $startTime = $activeloadregular['starttime'];

                            
                            $attTimeOutPM = $att->timeoutpm ?? $att->pmtimeout;
                            $endTime = $activeloadregular['endtime'];
    
                            if ($att->appliedleave == 1) {
                                $att->regularlate = 0;
                                $att->regularundertime = 0;

                            } else {
                                if (strtotime($attTimeInAM) > strtotime($startTime)) {
                                    $lateTimeInSeconds = strtotime($attTimeInAM) - strtotime($startTime);
                                    $lateTimeInMinutes = round($lateTimeInSeconds / 60);
                                    $att->regularlate = $lateTimeInMinutes;
                                }

                                if (strtotime($attTimeOutPM) < strtotime($endTime)) {

                                    $undertimeInSeconds = strtotime($endTime) - strtotime($attTimeOutPM);
                                    // return $endTime;
                                    $undertimeInMinutes = round($undertimeInSeconds / 60);
    
                                    if ($att->timeoutpm == null || $att->timeoutpm != '') {
                                        $att->regularundertime = 0;
                                    } else {
                                        $att->regularundertime = $undertimeInMinutes;
                                    }
                                     
    
                                }
                            }
                            
                        } else if ($att->status == 2) {

                            // return $activeloadregular['totalhours'];
                            // $att->regularabsent = $activeloadregular['totalhours'] * 60;

                            if ($employeecustomsched) {
                                $att->regularabsent = $totalworkinghours * 60;
                            } else {
                                $att->regularabsent = $activeloadregular['totalhours'] * 60;
                            }
                        }
                    }
                }
            }
        }

        if ($transformedDataoverload) {
            $daysactiveloadsol =  $transformedDataoverload[0]['schedule'];

            // for Overload Subjects
            foreach ($attendance as $att) {
                
                foreach ($daysactiveloadsol as $activeloadoverload) {
                    if ($att->day == $activeloadoverload['day']) {
                        if (($att->timeinam != null || $att->amtimein != null) && $att->status != 2) {

                            $attTimeInAM = $att->timeinam ?? $att->amtimein;
                            $startTime = $activeloadoverload['starttime'];

                            if ($att->timeoutpm == null || $att->pmtimeout == null) {
                                $attTimeOutPM = $att->timeoutam ?? $att->amtimeout;
                            } else {
                                $attTimeOutPM = $att->timeoutpm ?? $att->pmtimeout;
                            }
                            
                            $endTime = $activeloadoverload['endtime'];


                            if (strtotime($attTimeInAM) > strtotime($startTime)) {
                                $lateTimeInSeconds = strtotime($attTimeInAM) - strtotime($startTime);
                                $lateTimeInMinutes = round($lateTimeInSeconds / 60);
                                $att->overloadlate = $lateTimeInMinutes;
                            }

                            if (strtotime($attTimeOutPM) < strtotime($endTime)) {
                                $undertimeInSeconds = strtotime($endTime) - strtotime($attTimeOutPM);
                                $undertimeInMinutes = round($undertimeInSeconds / 60);
                                $att->overloadundertime = $undertimeInMinutes;
                            }
                        } else if ($att->status == 2 && $att->leavetype != 'SERVICE LEAVE (SERV LEAVE)') {
                            $att->overloadabsent = $activeloadoverload['totalhours'] * 60;
                        }
                    }
                }
            }
        }
        
        if ($transformedDataparttimeload) {
            $daysactiveloadspt =  $transformedDataparttimeload[0]['schedule'];
            
            // for Part Time Subjects
            foreach ($attendance as $att) {
                foreach ($daysactiveloadspt as $activeloadparttime) {
                    if ($att->day == $activeloadparttime['day']) {
                        if (($att->timeinam != null || $att->amtimein != null) && $att->status != 2) {

                            $attTimeInAM = $att->timeinam ?? $att->amtimein;
                            $startTime = $activeloadparttime['starttime'];

                            if ($att->timeoutpm == null || $att->pmtimeout == null) {
                                $attTimeOutPM = $att->timeoutam ?? $att->amtimeout;
                            } else {
                                $attTimeOutPM = $att->timeoutpm ?? $att->pmtimeout;
                            }
                            $endTime = $activeloadparttime['endtime'];

                            if (strtotime($attTimeInAM) > strtotime($startTime)) {
                                $lateTimeInSeconds = strtotime($attTimeInAM) - strtotime($startTime);
                                $lateTimeInMinutes = round($lateTimeInSeconds / 60);
                                $att->parttimelate = $lateTimeInMinutes;
                            }

                            if (strtotime($attTimeOutPM) < strtotime($endTime)) {
                                $undertimeInSeconds = strtotime($endTime) - strtotime($attTimeOutPM);
                                $undertimeInMinutes = round($undertimeInSeconds / 60);
                                $att->parttimeundertime = $undertimeInMinutes;
                            }
                        } else if ($att->status == 2 && $att->leavetype != 'SERVICE LEAVE (SERV LEAVE)') {
                            $att->parttimeabsent = $activeloadparttime['totalhours'] * 60;
                        }
                    }
                }
            }
        }
        
        // return $attendance;
        $tardyTypes = [
            'Regular Late',
            'Overload Late',
            'Emergency Late',
            'Regular Undertime',
            'Overload Undertime',
            'Emergency Undertime',
            'Regular Absent',
            'Overload Absent',
            'Emergency Absent',
        ];
        
        $tardy = [];
        
        foreach ($tardyTypes as $type) {
            $lateType = str_contains($type, 'Late');
            $undertimeType = str_contains($type, 'Undertime');
        
            $entry = new \stdClass();
            $entry->type = $type;
            $entry->minutes = 0; // Set the initial value, you need to calculate this based on your logic
        
            $tardy[] = $entry;
        }

        $laterl = 0;
        $lateol = 0;
        $latept = 0;
        $undertimerl = 0;
        $undertimeol = 0;
        $undertimept = 0;
        $absentrl = 0;
        $absentol = 0;
        $absentpt = 0;

        // return $attendance;
        foreach ($attendance as $sumatt) {
            if ($sumatt->holiday == 1 || $sumatt->appliedleave == 1 && ($sumatt->leavetype == 'SERVICE LEAVE (SERV LEAVE)')) {
                $absentol += $sumatt->overloadabsent;
                $absentpt += $sumatt->parttimeabsent;
            } else {
                $laterl += $sumatt->regularlate;
                $lateol += $sumatt->overloadlate;
                $latept += $sumatt->parttimelate;
                $undertimerl += $sumatt->regularundertime;
                $undertimeol += $sumatt->overloadundertime;
                $undertimept += $sumatt->parttimeundertime;
                $absentrl += $sumatt->regularabsent;
                $absentol += $sumatt->overloadabsent;
                $absentpt += $sumatt->parttimeabsent;
            }

        }

        foreach ($tardy as $tally) {

            if ($tally->type == 'Regular Late') {
                $tally->minutes = $laterl;
            } else if ($tally->type == 'Overload Late'){
                $tally->minutes = $lateol;
            } else if ($tally->type == 'Emergency Late'){
                $tally->minutes = $latept;
            } else if ($tally->type == 'Regular Undertime'){
                $tally->minutes = $undertimerl;
            } else if ($tally->type == 'Overload Undertime'){
                $tally->minutes = $undertimeol;
            } else if ($tally->type == 'Emergency Undertime'){
                $tally->minutes = $undertimept;
            } else if ($tally->type == 'Regular Absent'){
                $tally->minutes = $absentrl;
            } else if ($tally->type == 'Overload Absent'){
                $tally->minutes = $absentol;
            } else if ($tally->type == 'Emergency Absent'){
                $tally->minutes = $absentpt;
            }
        }
        

        // return $attendance;
        // return collect($attendance)->where('holiday', 1)->values();
        return [
            'tardy' => collect($tardy), 
            'regularload' => $transformedDataregload,
            'overload' => $transformedDataoverload,
            'parttime' => $transformedDataparttimeload,
            'attendance' => $attendance
        ];
        return response()->json($transformedDataregload);
    }
    
    
    // public function perteacher(Request $request)
    // {
    //     $syid = $request->get('syid');
    //     $semid = $request->get('semid');
    //     $empid = $request->get('empid');
    //     $clsubjectrate = 0;

    //     $semesterdate = DB::table('semester_date')
    //         ->where('syid', $syid)
    //         ->where('deleted', 0)
    //         ->get();
        
    //     // count how many days in a sem
    //     // Function to count the number of weekdays in a date range
    //     function countWeekdays($startDate, $endDate, $day) {
    //         $count = 0;
    //         $currentDate = strtotime($startDate);

    //         while ($currentDate <= strtotime($endDate)) {
    //             if (date('N', $currentDate) == $day) {
    //                 $count++;
    //             }
    //             $currentDate = strtotime('+1 day', $currentDate);
    //         }

    //         return $count;
    //     }

    //     foreach ($semesterdate as $semester) {
    //         $startDate = $semester->sdate;
    //         $endDate = $semester->edate;
        
    //         $mondays = countWeekdays($startDate, $endDate, 1);
    //         $tuesdays = countWeekdays($startDate, $endDate, 2);
    //         $wednesdays = countWeekdays($startDate, $endDate, 3);
    //         $thursdays = countWeekdays($startDate, $endDate, 4);
    //         $fridays = countWeekdays($startDate, $endDate, 5);
    //         $saturdays = countWeekdays($startDate, $endDate, 6);
    //         $sundays = countWeekdays($startDate, $endDate, 7);
        
    //         // Add the counts to the semester object
    //         $semester->mondays = $mondays;
    //         $semester->tuesdays = $tuesdays;
    //         $semester->wednesdays = $wednesdays;
    //         $semester->thursdays = $thursdays;
    //         $semester->fridays = $fridays;
    //         $semester->saturdays = $saturdays;
    //         $semester->sundays = $sundays;
    //     }
        
    //     // return $semesterdate;

    //     $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
    //         ->where('employeeid', $empid)
    //         ->where('deleted', 0)
    //         ->first();
        
    //     if ($basicsalaryinfo) {
    //         $clsubjectrate = $basicsalaryinfo->clsubjperhour;
    //     } else {
    //         $clsubjectrate = 0;
    //     }
    //     // return collect($basicsalaryinfo);
    //     $schedules = DB::table('schedulecoding')
    //         ->leftJoin('schedulecodingdetails', 'schedulecoding.id', '=', 'schedulecodingdetails.headerid')
    //         ->leftJoin('college_subjects', 'schedulecoding.subjid', '=', 'college_subjects.id')
    //         ->where('schedulecoding.syid', $syid)
    //         // ->where('schedulecoding.semid', $semid)
    //         ->where('schedulecoding.teacherid', $empid)
    //         ->where('schedulecoding.deleted', '0')
    //         ->where('schedulecodingdetails.deleted', '0')
    //         ->get();
    //     $groupedData = $schedules->groupBy(['subjid', 'subjDesc']);

    //     $formattedData = [];

    //     foreach ($groupedData as $items) {
    //         $formattedData[] = [
    //             'subjid' => $items->first()[0]->subjid,
    //             'semid' => $items->first()[0]->semid,
    //             'syid' => $items->first()[0]->syid,
    //             'subjdesc' => $items->first()[0]->subjDesc,
    //             'lec' => $items->first()[0]->lecunits,
    //             'clsubjperhour' => $clsubjectrate,
    //             'lab' => $items->first()[0]->labunits,
    //             'totalhourss' => $items->sum(function ($item) {
    //                 $totalHours = 0;
    //                 foreach ($item as $detail) {
    //                     // Calculate the duration in hours
    //                     $startTime = strtotime($detail->timestart);
    //                     $endTime = strtotime($detail->timeend);
    //                     $duration = ($endTime - $startTime) / 3600; // Convert seconds to hours
        
    //                     $totalHours += $duration; // Add to the total hours
    //                 }
    //                 return $totalHours;
    //             }),
    //             'scheddetails' => $items->map(function ($item) {
                    
    //                 foreach ($item as $detail) {
    //                     $totalHours = 0;
    //                     // Calculate the duration in hours
    //                     $startTime = strtotime($detail->timestart); // Use array notation here
    //                     $endTime = strtotime($detail->timeend); // Use array notation here
    //                     $duration = ($endTime - $startTime) / 3600; // Convert seconds to hours

    //                     $totalHours += $duration; // Add to the total hours

    //                     $detail->totalHours = $totalHours;
    //                 }
                   
                   

                    
    //                 return $item;
    //             })->toArray(),
    //         ];
    //     }
    //     return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
    // }
    public function perteacher(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $empid = $request->get('empid');
        $clsubjectrate = 0;

        $semesterdate = DB::table('semester_date')
            ->where('syid', $syid)
            ->where('semester', $semid)
            ->where('deleted', 0)
            ->get();

        if ($semesterdate->isEmpty()) {
            return response()->json([], 200, [], JSON_PRETTY_PRINT);
            $semesterinterval = 0;
        } else {
            $semesterinterval = $semesterdate[0]->interval;
        }

        // return $semesterinterval;
        // Function to count the number of weekdays in a date range
        function countWeekdays($startDate, $endDate, $day) {
            $count = 0;
            $currentDate = strtotime($startDate);

            while ($currentDate <= strtotime($endDate)) {
                if (date('N', $currentDate) == $day) {
                    $count++;
                }
                $currentDate = strtotime('+1 day', $currentDate);
            }

            return $count;
        }

        foreach ($semesterdate as $semester) {
            $startDate = $semester->sdate;
            $endDate = $semester->edate;
            // return $endDate;
            // Count occurrences of each day in the semester date range
            $dayOccurrences = [
                1 => countWeekdays($startDate, $endDate, 1), // Monday
                2 => countWeekdays($startDate, $endDate, 2), // Tuesday
                3 => countWeekdays($startDate, $endDate, 3), // Wednesday
                4 => countWeekdays($startDate, $endDate, 4), // Thursday
                5 => countWeekdays($startDate, $endDate, 5), // Friday
                6 => countWeekdays($startDate, $endDate, 6), // Saturday
                7 => countWeekdays($startDate, $endDate, 7), // Sunday
            ];

            $mondays = $dayOccurrences[1];
            $tuesdays = $dayOccurrences[2];
            $wednesdays = $dayOccurrences[3];
            $thursdays = $dayOccurrences[4];
            $fridays = $dayOccurrences[5];
            $saturdays = $dayOccurrences[6];
            $sundays = $dayOccurrences[7];

            // Add the counts to the semester object
            $semester->mondays = $mondays;
            $semester->tuesdays = $tuesdays;
            $semester->wednesdays = $wednesdays;
            $semester->thursdays = $thursdays;
            $semester->fridays = $fridays;
            $semester->saturdays = $saturdays;
            $semester->sundays = $sundays;
        }

        $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
            ->where('employeeid', $empid)
            ->where('deleted', 0)
            ->first();

        if ($basicsalaryinfo) {
            $clsubjectrate = $basicsalaryinfo->clsubjperhour;
        } else {
            $clsubjectrate = 0;
        }

        $schedules = DB::table('schedulecoding')
            ->leftJoin('schedulecodingdetails', 'schedulecoding.id', '=', 'schedulecodingdetails.headerid')
            ->leftJoin('college_subjects', 'schedulecoding.subjid', '=', 'college_subjects.id')
            ->where('schedulecoding.syid', $syid)
            ->where('schedulecoding.semid', $semid)
            ->where('schedulecoding.teacherid', $empid)
            ->where('schedulecoding.deleted', '0')
            ->where('schedulecodingdetails.deleted', '0')
            ->get();

        $groupedData = $schedules->groupBy(['subjid', 'subjDesc']);

        $formattedData = [];

        foreach ($groupedData as $items) {
            $unitslec = $items->first()[0]->lecunits;

            $formattedData[] = [
                'subjid' => $items->first()[0]->subjid,
                'semid' => $items->first()[0]->semid,
                'syid' => $items->first()[0]->syid,
                'subjdesc' => $items->first()[0]->subjDesc,
                'lec' => $unitslec,
                'clsubjperhour' => $clsubjectrate,
                'lab' => $items->first()[0]->labunits,
                'intervals' => $semesterinterval,
                'totalhourss' => $unitslec == 3 ? 54 : $items->sum(function ($item) use ($dayOccurrences) {
                    $totalHours = 0;
        
                    foreach ($item as $detail) {
                        $startTime = strtotime($detail->timestart);
                        $endTime = strtotime($detail->timeend);
                        $duration = ($endTime - $startTime) / 3600;
        
                        $totalHours += $duration * $dayOccurrences[$detail->day];
                    }
        
                    return $totalHours;
                }),
                'amountinasem' => $unitslec == 3 ? 54 * $clsubjectrate : $items->sum(function ($item) use ($dayOccurrences, $clsubjectrate) {
                    $totalAmount = 0;
        
                    foreach ($item as $detail) {
                        $startTime = strtotime($detail->timestart);
                        $endTime = strtotime($detail->timeend);
                        $duration = ($endTime - $startTime) / 3600;
        
                        $totalAmount += $duration * $dayOccurrences[$detail->day] * $clsubjectrate;
                    }
        
                    return $totalAmount;
                }),
                'totalamountinasem' => $items->sum('amountinasem'),
                'scheddetails' => $items->map(function ($item) use ($dayOccurrences) {
                    foreach ($item as $detail) {
                        $totalHours = 0;
                        $startTime = strtotime($detail->timestart);
                        $endTime = strtotime($detail->timeend);
                        $duration = ($endTime - $startTime) / 3600;
        
                        $totalHours += $duration;
                        $detail->totalHours = $totalHours;
        
                        // Add the total hours for the specific day to each detail
                        $detail->totalhoursinasem = $totalHours * $dayOccurrences[$detail->day];
                    }
        
                    return $item;
                })->toArray(),
            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
    }
    // public function perteacher(Request $request)
    // {
    //     $syid = $request->get('syid');
    //     $semid = $request->get('semid');
    //     $empid = $request->get('empid');
    //     $clsubjectrate = 0;

    //     $semesterdate = DB::table('semester_date')
    //         ->where('syid', $syid)
    //         ->where('semester', $semid)
    //         ->where('deleted', 0)
    //         ->get();

    //     $startDate = Carbon::parse($semesterdate[0]->sdate);
    //     $endDate = Carbon::parse($semesterdate[0]->edate);

    //     $numberOfWeeks = $startDate->diffInWeeks($endDate, false);
    //     // return $endDate;

    //     if ($semesterdate->isEmpty()) {
    //         return response()->json([], 200, [], JSON_PRETTY_PRINT);
    //         $semesterinterval = 0;
    //     } else {
    //         $semesterinterval = $semesterdate[0]->interval;
    //     }
    //     // return $semesterinterval;
    //     // Function to count the number of weekdays in a date range
    //     function countWeekdays($startDate, $endDate, $day) {
    //         $count = 0;
    //         $currentDate = strtotime($startDate);

    //         while ($currentDate <= strtotime($endDate)) {
    //             if (date('N', $currentDate) == $day) {
    //                 $count++;
    //             }
    //             $currentDate = strtotime('+1 day', $currentDate);
    //         }

    //         return $count;
    //     }

    //     foreach ($semesterdate as $semester) {
    //         $startDate = $semester->sdate;
    //         $endDate = $semester->edate;

    //         // Count occurrences of each day in the semester date range
    //         $dayOccurrences = [
    //             1 => countWeekdays($startDate, $endDate, 1), // Monday
    //             2 => countWeekdays($startDate, $endDate, 2), // Tuesday
    //             3 => countWeekdays($startDate, $endDate, 3), // Wednesday
    //             4 => countWeekdays($startDate, $endDate, 4), // Thursday
    //             5 => countWeekdays($startDate, $endDate, 5), // Friday
    //             6 => countWeekdays($startDate, $endDate, 6), // Saturday
    //             7 => countWeekdays($startDate, $endDate, 7), // Sunday
    //         ];

    //         $mondays = $dayOccurrences[1];
    //         $tuesdays = $dayOccurrences[2];
    //         $wednesdays = $dayOccurrences[3];
    //         $thursdays = $dayOccurrences[4];
    //         $fridays = $dayOccurrences[5];
    //         $saturdays = $dayOccurrences[6];
    //         $sundays = $dayOccurrences[7];

    //         // Add the counts to the semester object
    //         $semester->mondays = $mondays;
    //         $semester->tuesdays = $tuesdays;
    //         $semester->wednesdays = $wednesdays;
    //         $semester->thursdays = $thursdays;
    //         $semester->fridays = $fridays;
    //         $semester->saturdays = $saturdays;
    //         $semester->sundays = $sundays;
    //     }

    //     $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
    //         ->where('employeeid', $empid)
    //         ->where('deleted', 0)
    //         ->first();

    //     if ($basicsalaryinfo) {
    //         $clsubjectrate = $basicsalaryinfo->clsubjperhour;
    //     } else {
    //         $clsubjectrate = 0;
    //     }

    //     $schedules = DB::table('schedulecoding')
    //         ->leftJoin('schedulecodingdetails', 'schedulecoding.id', '=', 'schedulecodingdetails.headerid')
    //         ->leftJoin('college_subjects', 'schedulecoding.subjid', '=', 'college_subjects.id')
    //         ->where('schedulecoding.syid', $syid)
    //         ->where('schedulecoding.semid', $semid)
    //         ->where('schedulecoding.teacherid', $empid)
    //         ->where('schedulecoding.deleted', '0')
    //         ->where('schedulecodingdetails.deleted', '0')
    //         ->get();

    //     $groupedData = $schedules->groupBy(['subjid', 'subjDesc']);

    //     $formattedData = [];

    //     foreach ($groupedData as $items) {
    //         $codeCountsPerSched = $items->map(function ($item) {
    //             $codeCounts = $item->mapWithKeys(function ($detail) {
    //                 return [$detail->code => 1]; // Extract the 'code' and count occurrences
    //             })->toArray();
    
    //             $uniqueCodeCount = count($codeCounts);
    
    //             return [
    //                 'uniqueCodeCount' => $uniqueCodeCount,
    //                 'codeCounts' => $codeCounts,
    //             ];
    //         });
    //         // return $codeCountsPerSched->first()['uniqueCodeCount'];
    //         $formattedData[] = [
    //             'subjid' => $items->first()[0]->subjid,
    //             'semid' => $items->first()[0]->semid,
    //             'syid' => $items->first()[0]->syid,
    //             'subjdesc' => $items->first()[0]->subjDesc,
    //             'lec' => $items->first()[0]->lecunits,
    //             'clsubjperhour' => $clsubjectrate,
    //             'lab' => $items->first()[0]->labunits,
    //             'intervals' => $semesterinterval,
    //             'totalhourss' => ($semesterinterval/2) * ($items->first()[0]->lecunits * $codeCountsPerSched->first()['uniqueCodeCount']),
    //             'amountinasem' => ($numberOfWeeks * ($items->first()[0]->lecunits * $codeCountsPerSched->first()['uniqueCodeCount'])) * $clsubjectrate,
    //             'totalamountinasem' => $items->sum('amountinasem'),
    //             'codeCountsPerSched' => $codeCountsPerSched, // Add the code counts to the result
    //             'scheddetails' => $items->map(function ($item) use ($dayOccurrences) {
    //                 foreach ($item as $detail) {
    //                     $totalHours = 0;
    //                     $startTime = strtotime($detail->timestart);
    //                     $endTime = strtotime($detail->timeend);
    //                     $duration = ($endTime - $startTime) / 3600;
        
    //                     $totalHours += $duration;
    //                     $detail->totalHours = $totalHours;
        
    //                     // Add the total hours for the specific day to each detail
    //                     $detail->totalhoursinasem = $totalHours * $dayOccurrences[$detail->day];
    //                 }
        
    //                 return $item;
    //             })->toArray(),
    //         ];
    //     }

    //     return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
    // }

    public function profileloadsemester(Request $request)
    {
        $semester = DB::table('semester')
            ->select('semester as text', 'id', 'isactive')
            ->where('deleted', 0)
            ->get();

        return $semester;
    }

    public function profileloadsy(Request $request)
    {
        $sy = DB::table('sy')
            ->select('sydesc as text', 'id', 'isactive', 'sdate', 'edate')
            ->orderBy('sydesc', 'desc')
            // ->where('deleted', 0)
            ->get();

        return $sy;
    }


    // Semester Date
    public function semesterdate(Request $request)
    {
        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();
        
        $semester = DB::table('semester')
            ->where('isactive', 1)
            ->first();

        return view('hr.employees.semesterdate')
            ->with('sy', $sy)
            ->with('semester', $semester);
    }

    // Semester Date
    public function loadsemester(Request $request)
    {
        $semesterdata = DB::table('semester')
            ->where('deleted', 0)
            ->get();

        return $semesterdata;
    }

    public function loadsemesterdate(Request $request)
    {   
        $syid = $request->get('syid');
        $semesterdates = DB::table('semester_date')
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->get();

        return $semesterdates;
    }

    public function addsemesterdate(Request $request)
    {
        $sdate = $request->get('sdate');
        $edate =$request->get('edate');
        $syid =$request->get('syid');
        $semid =$request->get('semid');

         // Convert string dates to DateTime objects
        $startDate = new \DateTime($sdate);
        $endDate = new \DateTime($edate);

        $monthsDifference = ($endDate->format('Y') - $startDate->format('Y')) * 12 + $endDate->format('m') - $startDate->format('m') + 1;
        $every15 = $monthsDifference * 2;

        $ifexist = DB::table('semester_date')
            ->where('syid', $syid)
            ->where('semester', $semid)
            ->count();

        if ($ifexist) {
            $semesterdates = DB::table('semester_date')
                ->where('syid', $syid)
                ->where('semester', $semid)
                ->update([
                    'sdate' => $sdate,
                    'edate' => $edate,
                    'interval' => $every15,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

                return array((object)[
                    'status' => 1,
                    'message' => 'Updated Successfully!',
                ]);
        } else {
            $semesterdates = DB::table('semester_date')
                ->insert([
                    'semester' => $semid,
                    'syid' => $syid,
                    'sdate' => $sdate,
                    'edate' => $edate,
                    'interval' => $every15,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);
                return array((object)[
                    'status' => 1,
                    'message' => 'Added Successfully!',
                ]);
        }
    }

    public function deletesemesterdate(Request $request)
    {
        $syid =$request->get('syid');
        $semid =$request->get('semid');

        $ifexist = DB::table('semester_date')
            ->where('syid', $syid)
            ->where('semester', $semid)
            ->count();

        if ($ifexist) {
            $semesterdates = DB::table('semester_date')
                ->where('syid', $syid)
                ->where('semester', $semid)
                ->update([
                    'deleted' => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);

                return array((object)[
                    'status' => 1,
                    'message' => 'Deleted Successfully!',
                ]);
        } else {
           
            return array((object)[
                'status' => 0,
                'message' => 'Nothing to Delete!',
            ]);
        }

    }

    public function activatesemesterdate(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $status = $request->get('status');

        $data = DB::table('semester_date')
            ->where('syid', '=', $syid)
            ->where('semester', '=', $semid)
            ->update([
                'active' => $status
            ]);

        // $semesterdates = DB::table('semester_date')
        //     ->where('syid', $syid)
        //     ->where('semester', $semid)
        //     ->update([
        //         'active' => 1
        //     ]);
        if ($status == 1) {
            return array((object)[
                'status' => 1,
                'message' => 'Activated Successfully!',
            ]);
    
        } else {
            return array((object)[
                'status' => 1,
                'message' => 'Deactivated Successfully!',
            ]);
    
        }

        
    }
    
    // Part Time
    public function saveempsubjects(Request $request)
    {
        $empallsubjs = $request->json('employeesubjects');
        // return 'masaya';
        foreach ($empallsubjs as $subject) {
            $subjtype = isset($subject['subjtype']) ? $subject['subjtype'] : null;
            DB::table('employee_clloadsubjects')->updateOrInsert(
                ['subjdesc' => $subject['subjdesc']], // Column(s) to check for existence
                [
                    'employeeid' => $subject['empid'],
                    'syid' => $subject['activesy'],
                    'semid' => $subject['semid'],
                    'subjid' => $subject['subjid'],
                    'numberofhourslec' => $subject['lechours'],
                    'numberofhourslab' => $subject['labhours'],
                    'hourlyrate' => $subject['clsubjperhour'],
                    'totalpersem' => $subject['amountpersem'],
                    'amountperhalfmonth' => $subject['per15'],
                    'subjtype' => $subjtype
                ]
            );
        }
    
        return response()->json(['message' => 'Data saved successfully']);
    }

    public function persubject(Request $request)
    {
        $empid = $request->get('empid');
        $subjid = $request->get('subjid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $lechours = $request->get('lechours');
        $labhours = $request->get('labhours');
        $perhour = $request->get('perhour');
        $totalpersem = $request->get('totalpersem');
        $interval = $request->get('interval');
        $per15 = $request->get('per15');


        $updatecomputation = DB::table('employee_clloadsubjects')
            ->where('subjid', $subjid)
            ->where('employeeid', $empid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('deleted', 0)
            ->update([
                'numberofhourslec' => $lechours,
                'numberofhourslab' => $labhours,
                'hourlyrate' => $perhour,
                'totalpersem' => $totalpersem,
                'amountperhalfmonth' => $per15,
                'updatedby'         => auth()->user()->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);
        
        return array((object)[
            'status' => 1,
            'message' => 'Updated Successfully!',
        ]);
    }
    
    public function updatesubjecttype(Request $request)
    {
        $subjid = $request->get('subjid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $subjtype = $request->get('subjtype');
        $empid = $request->get('empid');
        $data = DB::table('employee_clloadsubjects')
            ->where('subjid', $subjid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('employeeid', $empid)
            ->update([
                'subjtype' => $subjtype
            ]);

        return array((object)[
            'status' => 1,
            'message' => 'Updated Successfully!',
        ]);
    }
    
    public function loadclloadsubjects(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $empid = $request->get('empid');
        $data = DB::table('employee_clloadsubjects')
            ->where('employeeid', $empid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->get();

        return $data;
    }
    
    public function getactivesem(Request $request){
        $syid = $request->get('syid');
        $activesem = DB::table('semester_date')
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->where('active', 1)
            ->get();

        return $activesem;
    }

    public function adddaterange(Request $request){
        $datefrom = $request->get('datefrom');
        $dateto = $request->get('dateto');

        // return $request->all();
        $checkifexists = DB::table('employee_cldaterange')
            ->where('status','1')
            ->first();
        
        $checkifexistsclose = DB::table('employee_cldaterange')
            ->where('datefrom',$datefrom)
            ->where('dateto',$dateto)
            ->where('status','0')
            ->first();


        if($checkifexists)
        {
            DB::table('employee_cldaterange')
                ->where('id',$checkifexists->id)
                ->update([
                    'datefrom'          => $datefrom,
                    'dateto'            => $dateto,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
            
            return array((object)[
                'status' => 1,
                'message' => 'Updated Successfully!',
            ]);

        }else{
            
            if ($checkifexistsclose) {
                DB::table('employee_cldaterange')
                ->where('datefrom',$datefrom)
                ->where('dateto',$dateto)
                ->where('status','0')
                ->update([
                    'status'          => 1,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
            } else {
                DB::table('employee_cldaterange')
                    ->update([
                        'status'            => 0
                    ]);

                DB::table('employee_cldaterange')
                    ->insert([
                        'datefrom'          => $datefrom,
                        'dateto'            => $dateto,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
               
            }

            return array((object)[
                'status' => 1,
                'message' => 'Added Successfully!',
            ]);
            
        }
    }

    public function closedaterange(Request $request){
        $status = $request->get('status');

        $checkifactive = DB::table('employee_cldaterange')
            ->where('status','1')
            ->get();

        if($checkifactive)
        {
            DB::table('employee_cldaterange')
                ->where('status',1)
                ->update([
                    'status'          => 0,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
            
            return array((object)[
                'status' => 1,
                'message' => 'Close Successfully!',
            ]);

        }
    }

    public function loadactivedaterange(Request $request){
        

        $activerange = DB::table('employee_cldaterange')
            ->where('status','1')
            ->first();

        return response()->json($activerange);
    }
    
    public function savetardy(Request $request){
        $activepayroll = DB::table('hr_payrollv2')
            ->where('deleted', 0)
            ->where('status', 1)
            ->first()->id;


        $activedaterange = DB::table('employee_cldaterange')
            ->where('status', 1)
            ->where('deleted', 0)
            ->first()->id;

        $checkedData = $request->input('checkedData');
        $teacherid = $request->get('teacherid');
        $dataid = $request->get('dataid');

        foreach ($checkedData as $data) {
            $type = $data['type'];
            $minutes = $data['minutes'];

            $ifexist = DB::table('employee_cltardy')
                ->where('type', $type)
                ->where('employeeid', $teacherid)
                ->where('datastatus', $dataid)
                ->where('payrollid', $activepayroll)
                ->where('deleted', 0)
                ->get();

            if ($ifexist->isNotEmpty()) {
                DB::table('employee_cltardy')
                    ->where('type', $type)
                    ->where('employeeid', $teacherid)
                    ->where('datastatus', $dataid)
                    ->where('payrollid', $activepayroll)
                    ->where('deleted', 0)
                    ->update([
                        'totalminutes' => $minutes,
                        'headerid' => $activedaterange,
                        'payrollid' => $activepayroll,
                        'status' => 1,
                        'datastatus' => $dataid,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                DB::table('employee_cltardy')
                    ->insert([
                        'type' => $type,
                        'employeeid' => $teacherid,
                        'totalminutes' => $minutes,
                        'headerid' => $activedaterange,
                        'payrollid' => $activepayroll,
                        'status' => 1,
                        'datastatus' => $dataid,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);
            }

        }
    
        return array((object)[
            'status' => 1,
            'message' => 'Process Successfully!',
        ]);
    }

    public function load_totaltardines(Request $request){

        $activepayroll_cldaterange = DB::table('employee_cldaterange')
            ->where('deleted', 0)
            ->where('status', 1)
            ->first();

        $activepayroll = DB::table('hr_payrollv2')
            ->where('deleted', 0)
            ->where('datefrom', $activepayroll_cldaterange->datefrom)
            ->where('dateto', $activepayroll_cldaterange->dateto)
            // ->where('status', 1)
            ->first()->id;

        $teacherid = $request->get('teacherid');
        $dataid = $request->get('dataid');

        $data =  DB::table('employee_cltardy')
            ->where('employeeid', $teacherid)
            ->where('datastatus', $dataid)
            ->where('payrollid', $activepayroll)
            ->where('status', 1)
            ->where('deleted', 0)
            ->get();

        return $data;
    }

    public function disapprovepertype(Request $request){
        $teacherid = $request->get('teacherid');
        $rid = $request->get('rid');
        $type = $request->get('type');
        $minutes = $request->get('minutes');

        // return $request->all();
        $data =  DB::table('employee_cltardy')
            // ->where('type', $type)
            ->where('id', $rid)
            ->where('employeeid', $teacherid)
            //->where('totalminutes', $minutes)
            ->where('status', 1)
            ->where('deleted', 0)
            ->update([
                'status' => 0,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => date('Y-m-d H:i:s')
            ]);

        return array((object)[
            'status' => 1,
            'message' => 'Disapproved Successfully!',
        ]);
    }

    public function approvepertype(Request $request){
        $teacherid = $request->get('teacherid');
        $type = $request->get('type');
        $minutes = $request->get('minutes');
        $daterangeactive = $request->get('daterangeactive');
        $dataid = $request->get('dataid');

        $activepayroll = DB::table('hr_payrollv2')
            ->where('deleted', 0)
            ->where('status', 1)
            ->first()->id;

        $activedaterange = DB::table('employee_cldaterange')
            ->where('id', $daterangeactive)
            ->where('deleted', 0)
            ->where('status', 1)
            ->first()->id;

        $checkifexists = DB::table('employee_cltardy')
            ->where('headerid', $daterangeactive)
            ->where('employeeid', $teacherid)
            ->where('type', $type)
            ->first();

        if ($checkifexists) {
            $data =  DB::table('employee_cltardy')
                ->where('type', $type)
                ->where('employeeid', $teacherid)
                ->where('totalminutes', $minutes)
                ->where('status', 0)
                ->where('deleted', 0)
                ->update([
                    'status' => 1,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);

            return array((object)[
                'status' => 1,
                'message' => 'Updated Successfully!',
            ]);
        } else {
            DB::table('employee_cltardy')
                ->insert([
                    'type' => $type,
                    'employeeid' => $teacherid,
                    'totalminutes' => $minutes,
                    'headerid' => $activedaterange,
                    'payrollid' => $activepayroll,
                    'status' => 1,
                    'datastatus' => $dataid,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);

            return array((object)[
                'status' => 1,
                'message' => 'Approved Successfully!',
            ]);
        }
    }

    public function changestatus(Request $request){
        $status = $request->get('status');

        $activerange = DB::table('employee_cldaterange')
            ->where('status','1')
            ->update([
                'setupstatus' => $status
            ]);

        if ($status == 1) {
            $message = 'Per Subject Activated!';
        } elseif($status == 2){
            $message = 'Per Schedule Activated!';
        }
        return array((object)[
            'status' => 1,
            'message' => $message,
        ]);
    }
    

}
