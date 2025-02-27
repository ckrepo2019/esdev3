<?php

namespace App\Http\Controllers\AdminAdminController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use DB;
// use App\Models\Principal\SPP_SchoolYear;
// use App\Models\Principal\SPP_AcademicProg;
// use App\Models\Principal\SPP_Rooms;
// use App\Models\Principal\SPP_Calendar;
// use App\Models\Principal\SPP_Privelege;
// use App\Models\Principal\SPP_Truncanator;
// use App\Models\Principal\SPP_PermissionRequest;
// use App\Models\Principal\SPP_Announcement;
use App\Models\Principal\SPP_Gradelevel;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_Teacher;
use File;
use Session;
use Crypt;
use App\FilePath;
use Image;
use Redirect;
use Hash;
use PDF;
use DateTime;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use App\Models\HR\HREmployeeAttendance;

class AdminAdminController extends \App\Http\Controllers\Controller
{
    public static function switchschool()
    {
        
    }
    public static function passData(Request $request)
    {        
        if($request->get('action') == 'getschoolyears')
        {
            return DB::table('sy')->get();
        }
        elseif($request->get('action') == 'getsemesters')
        {
            return DB::table('semester')->where('deleted','0')->get();
        }
        elseif($request->get('action') == 'getemployeeattendance')
        {
            $employees = DB::table('teacher')
                ->where('deleted','0')
                ->where('isactive','1')
                ->orderBy('lastname','asc')
                ->get();            
            if(count($employees) > 0)
            {
                foreach($employees as $employee)
                {
                    // return collect(HREmployeeAttendance::getattendance(date('Y-m-d'),$employee));
                    $employee->lastactivity = HREmployeeAttendance::getattendance(date('Y-m-d'),$employee)->lastactivity;
                    $employee->amin = HREmployeeAttendance::getattendance(date('Y-m-d'),$employee)->amin;
                    $employee->amout = HREmployeeAttendance::getattendance(date('Y-m-d'),$employee)->amout;
                    $employee->pmin = HREmployeeAttendance::getattendance(date('Y-m-d'),$employee)->pmin;
                    $employee->pmout = HREmployeeAttendance::getattendance(date('Y-m-d'),$employee)->pmout;
                    $employee->customamin = HREmployeeAttendance::getattendance(date('Y-m-d'),$employee)->customamin;
                    $employee->customamout = HREmployeeAttendance::getattendance(date('Y-m-d'),$employee)->customamout;
                    $employee->custompmin = HREmployeeAttendance::getattendance(date('Y-m-d'),$employee)->custompmin;
                    $employee->custompmout = HREmployeeAttendance::getattendance(date('Y-m-d'),$employee)->custompmout;
                    $employee->lasttap = $employee->lastactivity == 'AM IN' ? $employee->amin : ($employee->lastactivity == 'AM OUT' ? $employee->amout : ($employee->lastactivity == 'PM IN' ? $employee->pmin : ($employee->lastactivity == 'PM OUT' ? $employee->pmout : '')));
                }
            }
            return $employees;
        }
        elseif($request->get('action') == 'getemployees')
        {            
            $employees = DB::table('teacher')
              ->select('teacher.id','teacher.userid','lastname','firstname','middlename','suffix','tid',DB::raw('UPPER(`gender`) as gender'),'utype as designation','isactive','hr_empstatus.description as employmentstatus','teacher.datehired','employee_personalinfo.dob','employee_personalinfo.address','employee_personalinfo.email','teacher.picurl')
              ->leftJoin('employee_personalinfo','teacher.id','=', 'employee_personalinfo.employeeid')
              ->leftJoin('hr_empstatus','teacher.employmentstatus','=', 'hr_empstatus.id')
              ->join('usertype', 'teacher.usertypeid','=','usertype.id')
              ->where('teacher.deleted','0')
              ->where('teacher.isactive','1')
              ->orderBy('lastname','asc')
              ->get();          
            if(count($employees)>0)
            {
              foreach($employees as $employee)
              {
                $countrecords = 0;
                $checktap = DB::table('taphistory')
                  ->where('studid', $employee->id)
                  ->where('tdate', date('Y-m-d'))
                  ->where('deleted','0')
                  ->count();
                $countrecords+=$checktap;
                $checkatt = DB::table('hr_attendance')
                  ->where('studid', $employee->id)
                  ->where('tdate', date('Y-m-d'))
                  ->where('deleted','0')
                  ->count();
                $countrecords+=$checkatt;
                if($countrecords == 0)
                {
                  $employee->attendancestatus = 0;
                }else{
                  $employee->attendancestatus = 1;
                }
                if($employee->datehired == null)
                {
                    $employee->worked = "";
                }else{
                    $datetime1 = new DateTime($employee->datehired);
                    $datetime2 = new DateTime(date('Y-m-d'));
                    $interval = $datetime1->diff($datetime2);
                    $employee->worked = $interval->format('%y yrs %m mths');
                }    
                $educationinfo = DB::table('employee_educationinfo')
                    ->where('employeeid', $employee->id)
                    ->where('deleted','0')
                    ->get();
                $employee->educationinfo = $educationinfo;    
                $otherportals = DB::table('faspriv')
                    ->select('faspriv.*','usertype.utype')
                    ->join('usertype', 'faspriv.usertype','=','usertype.id')
                    ->where('faspriv.userid', $employee->userid)
                    ->where('faspriv.deleted','0')
                    ->get();    
                $employee->otherportals = $otherportals;
              }
            }  
            return $employees;
        }elseif($request->get('action') == 'getfinancestudents')
        {
            $semid = $request->get('semid');
            $syid = $request->get('syid');
            $students = collect();    
            $enrolledstud_1 = DB::table('enrolledstud')
                ->select('studid')
                ->where('syid', $syid)
                ->where('studstatus','!=',0)
                ->where('deleted', 0)
                ->get();    
            $enrolledstud_2 = DB::table('sh_enrolledstud')
                ->select('studid')
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('studstatus','!=',0)
                ->where('deleted', 0)
                ->get();    
            $enrolledstud_3 = DB::table('college_enrolledstud')
                ->select('studid')
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('studstatus','!=',0)
                ->where('deleted', 0)
                ->get();    
            $students = $students->merge($enrolledstud_1);
            $students = $students->merge($enrolledstud_2);
            $students = $students->merge($enrolledstud_3);
            $students = $students->unique()->values()->all();            
            return $students;
        }elseif($request->get('action') == 'getreceivables')
        {
            $semid = $request->get('semid');
            $syid = $request->get('syid');
            $receivables = DB::table('studledger')
                ->where('deleted','0')
                ->where('semid',$semid)
                ->where('syid',$syid)
                ->where('void','0')
                ->get();
            return $receivables;
        }elseif($request->get('action') == 'getincome')
        {
            $semid = $request->get('semid');
            $syid = $request->get('syid');            
            $income = DB::table('chrngtrans')
                ->select('transdate',DB::raw('SUM(amountpaid) as totalamountpaid'))
                ->where('cancelled','0')
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->orderByDesc('transdate')
                ->groupBy(DB::raw('YEAR(transdate), MONTH(transdate), DAY(transdate)'))
                ->get();                
            return $income;
        }elseif($request->get('action') == 'getterminals')
        {
            $semid = $request->get('semid');
            $syid = $request->get('syid');            
            $terminals = DB::table('chrngterminals')
                ->get();                
            return $terminals;
        }elseif($request->get('action') == 'getpaymenttypes')
        {
            $semid = $request->get('semid');
            $syid = $request->get('syid');            
            $paymenttypes = DB::table('paymenttype')
                ->where('deleted','0')
                ->get();                
            return $paymenttypes;
        }elseif($request->get('action') == 'getcashiertransactions')
        {
            $datefrom = $request->get('datefrom');
            $dateto = $request->get('dateto');
            $terminalno = $request->get('terminalno');
            $paymenttype = $request->get('paymenttype');
            $paymenttypes = DB::table('paymenttype')
                ->where('deleted','0')
                ->get();
            $transactions = DB::table('chrngtrans')
                ->select('transdate', 'ornum', 'studname', 'amountpaid', 'paytype', 'description','terminalno','chrngtrans.paytype as paymenttype','users.name as transby')
                ->join('chrngterminals','chrngtrans.terminalno','=','chrngterminals.id')
                ->leftJoin('users','chrngtrans.transby','=','users.id')
                ->whereBetween('transdate',[$datefrom.' 00:00',$dateto.' 23:59'])
                ->where('cancelled','0')
                ->get();

            if($paymenttype>0)
            {
                $transactions = collect($transactions)->where('paymenttype', collect($paymenttypes)->where('id', $paymenttype)->first()->description)->values();
            }
            if($terminalno>0)
            {
                $transactions = collect($transactions)->where('terminalno', $terminalno)->values();
            }
            if(count($transactions)>0)
            {
                foreach($transactions as $transaction)
                {
                    $transaction->sort = date('Y-m-d', strtotime($transaction->transdate));
                    // $transaction->sort = date('Y-m-d', strtotime($transaction->transdate)).' '.$transaction->studname;
                    $transaction->transdate = date('m/d/Y', strtotime($transaction->transdate));
                }
            }       
            $transactions = collect($transactions)->sortBy('studname')->sortByDesc('sort')->values()->all();               
            return $transactions;
        }
    }
    public function viewadmiadmin($id)
    {
        Session::put('schoolid', $id);
        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
		
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        if(isset($schoolInfo->id)){
            Session::put('schoolinfo',$schoolInfo);
            Session::put('schoolid',$id);
        }
        else{
            return back();
        }    
        $infoCount = DB::table('schoolinfo')->count();
        $teachersinfo = SPP_Teacher::filterTeacherFaculty(null,null,null,null,null);
        $glevel = SPP_Gradelevel::getGradeLevel(null,null,null,null,null)[0]->data;        
        $sydetails = DB::table('sy')
            ->where('isactive',1)
            ->get();
        foreach($glevel as $item){
            $gsStudents = SPP_EnrolledStudent::getStudent(null,null,null,null,null,null,null,$item->id)[0]->count;
            $item->studCount = $gsStudents;
        }
        return view('adminITPortal.pages.home')
            ->with('fsinfo', $teachersinfo)
            ->with('schoolInfo', $schoolInfo)
            ->with('sydetails', $sydetails)
            ->with('glevel', $glevel);
    }
    public function enrollment(Request $request)
    {
        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        try{
            
        $schoolyears = $guzzleClient->request('GET', $url->eslink.'/passData?action=getschoolyears');
        $schoolyears = $schoolyears->getBody()->getContents();
        $schoolyears =  json_decode($schoolyears, true);
        $schoolyears = json_decode(json_encode($schoolyears), FALSE);
        $semesters = $guzzleClient->request('GET', $url->eslink.'/passData?action=getsemesters');
        $semesters = $semesters->getBody()->getContents();
        $semesters =  json_decode($semesters, true);
        $semesters = json_decode(json_encode($semesters), FALSE);
        }catch(\Exception $error)
        {
            $schoolyears = DB::table('sy')
                ->get();
            $semesters = DB::table('semester')
                ->get();
        }

        return view('adminITPortal.pages.enrollment.index')
            ->with('schoolyears', $schoolyears)
            ->with('semesters', $semesters);
    }
    public function financeindex(Request $request)
    {
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_HEADER => true,), ));
        try{
            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
            $schoolyears = $guzzleClient->request('GET', $url->eslink.'/passData?action=getschoolyears');
            $schoolyears = $schoolyears->getBody()->getContents();
            $schoolyears =  json_decode($schoolyears, true);
            $schoolyears = json_decode(json_encode($schoolyears), FALSE);
            
            $semesters = $guzzleClient->request('GET', $url->eslink.'/passData?action=getsemesters');
            $semesters = $semesters->getBody()->getContents();
            $semesters =  json_decode($semesters, true);
            $semesters = json_decode(json_encode($semesters), FALSE);

            $terminals = $guzzleClient->request('GET', $url->eslink.'/passData?action=getterminals');
            $terminals = $terminals->getBody()->getContents();
            $terminals =  json_decode($terminals, true);
            $terminals = json_decode(json_encode($terminals), FALSE);

            $paymentoptions = $guzzleClient->request('GET', $url->eslink.'/passData?action=getpaymenttypes');
            $paymentoptions = $paymentoptions->getBody()->getContents();
            $paymentoptions =  json_decode($paymentoptions, true);
            $paymentoptions = json_decode(json_encode($paymentoptions), FALSE);
            
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = collect($schoolyears)->where('isactive','1')->first()->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = collect($semesters)->where('isactive','1')->first()->id;
            }
        }catch(\Exception $error)
        {
            $terminals = DB::table('chrngterminals')
                ->get();

            $paymentoptions = DB::table('paymenttype')
                ->where('deleted','0')
                ->get();

            $schoolyears = DB::table('sy')
                ->get();
            $semesters = DB::table('semester')
                ->get();
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = DB::table('sy')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = DB::table('semester')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
        }
        
        $result = CarbonPeriod::create(collect($schoolyears)->where('id',$syid)->first()->sdate, '1 month', collect($schoolyears)->where('id',$syid)->first()->edate);
        $months = array();
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::today()->startOfMonth()->subMonth($i);
            $year = Carbon::today()->startOfMonth()->subMonth($i)->format('Y');
            array_push($months, (object) array(
                'month' => $month->shortMonthName,
                'monthint'   => (int) date('m', strtotime($month)),
                'monthdesc'   => str_replace('"', "'", date('M', strtotime($month))),
                'monthname'   => str_replace('"', "'", date('F', strtotime($month))),
                'year' => $year
            ));
        }
        
        try{
            $students = $guzzleClient->request('GET', $url->eslink.'/passData?action=getfinancestudents&syid='.$syid.'&semid='.$semid);
            $students = $students->getBody()->getContents();
            $students =  json_decode($students, true);
            $students = json_decode(json_encode($students), FALSE);
        }catch(\Exception $error)
        {
            $semid = $request->get('semid');
            $syid = $request->get('syid');
            $students = collect();    
            $enrolledstud_1 = DB::table('enrolledstud')
                ->select('studid')
                ->where('syid', $syid)
                ->where('studstatus','!=',0)
                ->where('deleted', 0)
                ->get();    
            $enrolledstud_2 = DB::table('sh_enrolledstud')
                ->select('studid')
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('studstatus','!=',0)
                ->where('deleted', 0)
                ->get();    
            $enrolledstud_3 = DB::table('college_enrolledstud')
                ->select('studid')
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('studstatus','!=',0)
                ->where('deleted', 0)
                ->get();    
            $students = $students->merge($enrolledstud_1);
            $students = $students->merge($enrolledstud_2);
            $students = $students->merge($enrolledstud_3);
            $students = $students->unique()->values()->all();  
        }
        
        try{
            $receivables = $guzzleClient->request('GET', $url->eslink.'/passData?action=getreceivables&syid='.$syid.'&semid='.$semid);
            $receivables = $receivables->getBody()->getContents();
            $receivables =  json_decode($receivables, true);
            $receivables = json_decode(json_encode($receivables), FALSE);
        }catch(\Exception $error)
        {
            $semid = $request->get('semid');
            $syid = $request->get('syid');
            $receivables = DB::table('studledger')
                ->where('deleted','0')
                ->where('semid',$semid)
                ->where('syid',$syid)
                ->where('void','0')
                ->get();
        }
        
        $receivables = collect($receivables)->whereIn('studid', collect($students)->pluck('studid'))->values();
        if(count($receivables)>0)
        {
            foreach($receivables as $receivable)
            {
                $receivable->receivables = ($receivable->amount - $receivable->payment);
                $receivable->monthint = (int) date('m', strtotime($receivable->createddatetime));
            }
        }
        
        try{
            $income = $guzzleClient->request('GET', $url->eslink.'/passData?action=getincome&syid='.$syid.'&semid='.$semid);
            $income = $income->getBody()->getContents();
            $income =  json_decode($income, true);
            $income = json_decode(json_encode($income), FALSE);
        }catch(\Exception $error)
        {
            $semid = $request->get('semid');
            $syid = $request->get('syid');            
            $income = DB::table('chrngtrans')
                ->select('transdate',DB::raw('SUM(amountpaid) as totalamountpaid'))
                ->where('cancelled','0')
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->orderByDesc('transdate')
                ->groupBy(DB::raw('YEAR(transdate), MONTH(transdate), DAY(transdate)'))
                ->get();          
        }
        
        if(count($income)>0)
        {
            foreach($income as $eachincome)
            {
                $eachincome->monthint = (int) date('m', strtotime($eachincome->transdate));
            }
        }
        // return $income;
        foreach($months as $month)
        {
            $month->totalreceivables = round(collect($receivables)->where('monthint', $month->monthint)->sum('receivables'),2) > 0 ?  round(collect($receivables)->where('monthint', $month->monthint)->sum('receivables'),2) : 0;
            $month->totalincome = round(collect($income)->where('monthint', $month->monthint)->sum('totalamountpaid'),2);
        }
        // return $months;
        if(!$request->has('action'))
        {
            return view('adminITPortal.pages.finance.index')
                ->with('terminals', $terminals)
                ->with('paymentoptions', $paymentoptions)
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters)
                ->with('months', $months);
        }else{
            if(!$request->has('export'))
            {
                if($request->get('action') == 'gettransactions')
                {
                    $datefrom = $request->get('datefrom');
                    $dateto = $request->get('dateto');
                    $terminalno = $request->get('terminalno');
                    $paymenttype = $request->get('paymenttype');
                    $transactions = $guzzleClient->request('GET', $url->eslink.'/passData?action=getcashiertransactions&terminalno='.$terminalno.'&datefrom='.$datefrom.'&dateto='.$dateto.'&paymenttype='.$paymenttype);
                    $transactions = $transactions->getBody()->getContents();
                    $transactions =  json_decode($transactions, true);
                    $transactions = json_decode(json_encode($transactions), FALSE);
                    return view('adminITPortal.pages.finance.transactions')
                        ->with('transactions', $transactions);
                }else{
                    return view('adminITPortal.pages.finance.results')
                        ->with('sydesc', collect($schoolyears)->where('id',$syid)->first()->sydesc)
                        ->with('months', $months);
                }
            }else{
                if($request->get('report') == 'receivables')
                {
                    $syinfo = collect($schoolyears)->where('id', $syid)->first();
                    $seminfo = collect($semesters)->where('id', $semid)->first();
                    $pdf = PDF::loadview('adminITPortal.pages.finance.pdf_receivables',compact('months','syinfo','seminfo'));
                    return $pdf->stream('Receivables.pdf');
                }elseif($request->get('report') == 'income')
                {
                    $syinfo = collect($schoolyears)->where('id', $syid)->first();
                    $seminfo = collect($semesters)->where('id', $semid)->first();
                    $pdf = PDF::loadview('adminITPortal.pages.finance.pdf_income',compact('months','syinfo','seminfo'));
                    return $pdf->stream('Income.pdf');

                }
            }
        }
    }
    public function academicindex(Request $request)
    {
        // $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        // if($schoolInfo->islocal == 0)
        // {
        //     Config::set("database.connections.mysql", [
        //         'driver' => 'mysql',
        //         "host" => env('DB_HOST', '141.164.36.7'),
        //         "database" => $schoolInfo->db,
        //         "username" => "ckgroup_dev",
        //         "password" => "Sels2019",
        //         "port" => '3306',
        //         'charset'   => 'utf8',
        //         'collation' => 'utf8_unicode_ci',
        //         'prefix'    => '',
        //         'strict'    => false,
        //     ]);
        //     DB::purge('mysql');
        // }
        
        if($request->has('syid'))
        {
            $syid = $request->get('syid');
        }else{
            $syid = DB::table('sy')
                ->where('isactive','1')
                ->first()
                ->id;
        }
        if($request->has('semid'))
        {
            $semid = $request->get('semid');
        }else{
            $semid = DB::table('semester')
                ->where('isactive','1')
                ->first()
                ->id;
        }
            
        $gradelevels = DB::table('gradelevel')
            ->select('id','levelname','acadprogid','sortid')
            ->where('deleted','0')
            ->orderBy('sortid','asc')
            ->get();

        $enrolled = 0;
        $withdrawn = 0;
        $droppedout = 0;
        $transferredin = 0;
        $transferredout = 0;
        if($request->get('action') == 'getenrollmentresults')
        {
            foreach($gradelevels as $gradelevel)
            {           
            
                try{
                    $acadprogcode = DB::table('gradelevel')
                        ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                        ->where('gradelevel.id', $gradelevel->id)
                        ->first()->acadprogcode;
                }catch(\Exception $error)
                {
                    $acadprogcode = 0;
                }
    
                $sections = array();
                if(strtolower($acadprogcode) == 'college')
                {
                    $sections = DB::table('college_sections')
                        ->select('college_sections.*','sectionDesc as sectionname')
                        ->where('yearID', $gradelevel->id)
                        ->where('semesterID', $request->get('semid'))
                        ->where('syID', $request->get('syid'))
                        ->where('deleted','0')
                        ->get();
                }else
                {
                    $sections = DB::table('sections')
                        ->select('sections.*')
                        ->where('levelid', $gradelevel->id)
                        ->where('deleted','0')
                        ->get();
                }
                if($gradelevel->acadprogid == 6)
                {
                    $enrollees = DB::table('college_enrolledstud')
                        ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','college_enrolledstud.yearLevel as levelid','college_enrolledstud.sectionID as sectionid','college_enrolledstud.studstatus')
                        ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                        // ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                        ->where('college_enrolledstud.deleted','0')
                        ->where('college_enrolledstud.syid',$syid)
                        ->where('college_enrolledstud.semid',$semid)
                        ->where('college_enrolledstud.yearLevel',$gradelevel->id)
                        ->get();

                }elseif($gradelevel->acadprogid == 5)
                {
                    $enrollees = DB::table('sh_enrolledstud')
                        ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','sh_enrolledstud.levelid','sh_enrolledstud.sectionid','sh_enrolledstud.studstatus')
                        ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                        // ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                        ->where('sh_enrolledstud.deleted','0')
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.semid',$semid)
                        ->where('sh_enrolledstud.levelid',$gradelevel->id)
                        ->get();
                }else{
                    $enrollees = DB::table('enrolledstud')
                        ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','enrolledstud.levelid','enrolledstud.sectionid','enrolledstud.studstatus')
                        ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                        // ->whereIn('enrolledstud.studstatus',[1,2,4])
                        ->where('enrolledstud.deleted','0')
                        ->where('enrolledstud.syid',$syid)
                        ->where('enrolledstud.levelid',$gradelevel->id)
                        ->get();
                }
                if(count($enrollees) > 0)
                {
                    foreach($enrollees as $enrollee)
                    {
                        $enrollee->gender = strtolower($enrollee->gender);
                    }
                }
                if(count($sections)>0)
                {
                    foreach($sections as $section)
                    {
                        $section->male = collect($enrollees)->where('sectionid', $section->id)->where('gender','male')->count();
                        $section->female = collect($enrollees)->where('sectionid', $section->id)->where('gender','female')->count();
                        $section->total = collect($enrollees)->where('sectionid', $section->id)->count();
                    }
                }
                $enrolled += collect($enrollees)->where('studstatus',1)->count();
                $withdrawn += collect($enrollees)->where('studstatus',6)->count();
                $droppedout += collect($enrollees)->where('studstatus',3)->count();
                $transferredin += collect($enrollees)->where('studstatus',4)->count();
                $transferredout += collect($enrollees)->where('studstatus',5)->count();
                $gradelevel->enrollees = collect($enrollees)->whereIn('studstatus',[1,2,4])->values();
                $gradelevel->label = ucwords(strtolower($gradelevel->levelname)).': '.count($gradelevel->enrollees).' enrollees';
                $gradelevel->enrolleesmalecount = collect($enrollees)->whereIn('gender',['male','Male','MALE'])->whereIn('studstatus',[1,2,4])->count();
                $gradelevel->enrolleesfemalecount = collect($enrollees)->whereIn('gender',['female','Female','FEMALE'])->whereIn('studstatus',[1,2,4])->count();
                $gradelevel->enrolleescount = collect($enrollees)->whereIn('studstatus',[1,2,4])->count();
                $gradelevel->sections = $sections;
            }
            return view('adminITPortal.pages.academic.filterresults.enrollees')
            ->with('sydesc', DB::table('sy')->where('id', $syid)->first()->sydesc)
            ->with('semdesc', DB::table('semester')->where('id', $semid)->first()->semester)
            ->with('droppedout', $droppedout)
            ->with('transferredin', $transferredin)
            ->with('transferredout', $transferredout)
            ->with('gradelevels', $gradelevels)
            ->with('gradelevels', $enrolled)
            ->with('gradelevels', $withdrawn);
        }
        elseif($request->get('action') == 'getteachingloadsresults')
        {
            $getteachers1 = DB::table('teacher')
                ->select('id','userid','firstname','middlename','lastname','suffix',DB::raw("CONCAT(teacher.lastname,' ',teacher.firstname) as sortname"))
                ->where('usertypeid','1')
                ->where('deleted','0')
                ->where('isactive','1')
                ->get();
            $getteachers2 = DB::table('faspriv')
                ->select('teacher.id','teacher.userid','firstname','middlename','lastname','suffix',DB::raw("CONCAT(teacher.lastname,' ',teacher.firstname) as sortname"))
                ->join('teacher','faspriv.userid','=','teacher.userid')
                ->where('faspriv.usertype','1')
                ->where('faspriv.deleted','0')
                ->where('teacher.deleted','0')
                ->where('teacher.isactive','1')
                ->get();

            $teachers = collect();
            $teachers = collect($teachers)->merge($getteachers1);
            $teachers = collect($teachers)->merge($getteachers2);
            $teachers = collect($teachers)->unique('id')->values();
            $teachers = collect($teachers)->sortBy('sortname')->values();
            return view('adminITPortal.pages.academic.filterresults.enrollees')
                ->with('gradelevels', $gradelevels)
                ->with('sydesc', DB::table('sy')->where('id', $syid)->first()->sydesc);
        }
        else{
            $levelid = $request->get('levelid');      
            
            $enrolledstud = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    DB::raw('LOWER(`gender`) as gender'),
                    'enrolledstud.sectionid as ensectid',
                    'enrolledstud.sectionid',
                    'enrolledstud.levelid',
                    'sections.sectionname',
                    'gradelevel.acadprogid'
                    )
                ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                ->leftJoin('sections','enrolledstud.sectionid','=','sections.id')
                ->whereIn('enrolledstud.studstatus',[1,2,4])
                ->where('enrolledstud.deleted','0')
                ->where('enrolledstud.syid',$syid)
                ->get();
                    
            $sh_enrolledstud = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    DB::raw('LOWER(`gender`) as gender'),
                    'sh_enrolledstud.sectionid as ensectid',
                    'sh_enrolledstud.levelid',
                    'sh_enrolledstud.sectionid',
                    'sh_enrolledstud.strandid',
                    'sections.sectionname',
                    'sh_strand.strandcode',
                    'gradelevel.levelname',
                    'gradelevel.acadprogid'
                    )
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                ->leftJoin('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                ->leftJoin('sections','sh_enrolledstud.sectionid','=','sections.id')
                ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                ->where('sh_enrolledstud.deleted','0')
                ->where('sh_enrolledstud.syid',$syid)
                ->where('sh_enrolledstud.semid',$semid)
                ->get();
    
            $students = collect();
            $students = $students->merge($enrolledstud);
            $students = $students->merge($sh_enrolledstud);
            $students = collect($students)->unique('id')->all();
            if($request->has('action'))
            {
                foreach($gradelevels as $gradelevel)
                {
                    $gradelevel->female = collect($students)->where('levelid', $gradelevel->id)->where('gender','female')->count();
                    $gradelevel->male = collect($students)->where('levelid', $gradelevel->id)->where('gender','male')->count();
                    $gradelevel->total = collect($students)->where('levelid', $gradelevel->id)->count();
                }
                if($request->get('action') == 'filter')
                {
                    // $students = $students->merge($college_enrolledstud);
                    
                    
                    return view('adminITPortal.pages.academic.filterresults')
                        ->with('gradelevels', $gradelevels);
                }elseif($request->get('action') == 'getcatrecords'){
                    $gradelevels = collect($gradelevels)->where('id', $levelid)->values();
                    foreach($gradelevels as $gradelevel)
                    {
                        if($gradelevel->acadprogid == 5)
                        {
                            $gradelevel->tracks = array();
                            $gradelevel->sections = array();
                            $gradelevel->strands = DB::table('sh_track')
                                ->select('sh_track.trackname','sh_strand.id as strandid','sh_strand.strandcode')
                                ->join('sh_strand','sh_track.id','=','sh_strand.trackid')
                                ->where('sh_track.deleted','0')
                                ->where('sh_strand.deleted','0')
                                ->get();
                        }else{
                            $gradelevel->sections = DB::table('sections')
                                ->where('levelid', $gradelevel->id)
                                ->where('deleted','0')
                                ->get();
                            $gradelevel->strands = array();
                            $gradelevel->tracks = array();
                        }
                    }
                    
                    $checkGradingVersion = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                    
                    if($request->get('filtertype') == 'basiced')
                    {
                        $students = collect($students)->whereIn('levelid',collect($gradelevels)->pluck('id'))->values();
                        
                        if(count($students)>0)
                        {
                            foreach($students as $eachenstud)
                            {
        
                                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                // {
                                    // if($checkGradingVersion->version == 'v1'){
                                    //     $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV4($eachenstud, true, 'sf9',$syid);
                                    
                                    // }
                                    // if($checkGradingVersion->version == 'v2'){
                                    //     $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($eachenstud, true, 'sf9',$syid);    
                                    // }
                                        // return collect($eachenstud);
                                    $schoolyear = DB::table('sy')->where('id',$syid)->first();
                                    Session::put('schoolYear', $schoolyear);
                                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                    {
                                        $eachenstud->acadprogid = 4;
                                        $checkGradingVersion = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                                    
                                        if($checkGradingVersion->version == 'v1'){
                                            $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV4($student, true, 'sf9',$schoolyear->id);
                                        
                                        }
                                        if($checkGradingVersion->version == 'v2'){
                                            $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($student, true, 'sf9',$schoolyear->id);    
                                            
                                        }
                                        $grades = $gradesv4;
                                
                                        $grades = collect($grades)->unique('subjectcode');
                                        $generalaverage = array();
                                    }
                                    elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                                    {
                                        $schoolyear = DB::table('sy')->where('id',$syid)->first();
                                        Session::put('schoolYear', $schoolyear);
                                        $grades = GenerateGrade::reportCardV4($student, true, 'sf9');
                                        
                                        $generalaverage =  \App\Models\Grades\GradesData::general_average($grades);
                                        $grades =  \App\Models\Grades\GradesData::get_finalrating($grades,$eachenstud->acadprogid);
                                        $generalaverage =  \App\Models\Grades\GradesData::get_finalrating($generalaverage,$eachenstud->acadprogid);
                                    }else{
    
                                        // return $eachenstud->id;
                                        // $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,$sy->strandid,null,$sy->ensectid,true);
                                        $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($eachenstud->levelid,$eachenstud->id,$schoolyear->id,null,null,$eachenstud->ensectid,true);
                                        // return $studgrades;
                                        $temp_grades = array();
                                        $generalaverage = array();
                                        foreach($studgrades as $item){
                                            if($item->id == 'G1'){
                                                array_push($generalaverage,$item);
                                                // array_push($temp_grades,$item);
                                            }else{
                                                array_push($temp_grades,$item);
                                            }
                                        }
                                    
                                        $studgrades = $temp_grades;
                                        $grades = collect($studgrades)->sortBy('sortid')->values();
                                    }
                                $grades = collect($grades)->values();
                                // return $grades;
                                if(count($grades)>0)
                                {
                                    foreach($grades as $grade)
                                    {
                                        if(!collect($grade)->contains('mapeh'))
                                        {
                                            $grade->mapeh = 0;
                                        }
                                        // if($grade->mapeh == 0)
                                        // {
                                        //     $finalrate = $grade->quarter1+$grade->quarter2+$grade->quarter3+$grade->quarter4;
                                        //     if($finalrate>0)
                                        //     {
                                        //         $grade->finalrating = $finalrate/4;
                                        //     }else{
                                        //         $grade->finalrating = 0;
                                        //     }
                                        // }
                                    }
                                }
                                $genave = collect($grades)->where('mapeh','0')->average('finalrating');
                                
                                if(count($generalaverage) == 0)
                                {
                                    $eachenstud->generalaverage = $genave;
    
                                }else{
                                    $eachenstud->generalaverage = $generalaverage[0]->finalrating;
                                }
                                
                                if($eachenstud->generalaverage <= 74 && $eachenstud->generalaverage != null)
                                {
                                    $eachenstud->genavecat = 'B';
                                }
    
                                if($eachenstud->generalaverage >= 75 && $eachenstud->generalaverage <= 79)
                                {
                                    $eachenstud->genavecat = 'D';
                                }
    
                                if($eachenstud->generalaverage >= 80 && $eachenstud->generalaverage <= 84)
                                {
                                    $eachenstud->genavecat = 'AP';
                                }
    
                                if($eachenstud->generalaverage >= 85 && $eachenstud->generalaverage <= 89)
                                {
                                    $eachenstud->genavecat = 'P';
                                }
    
                                if($eachenstud->generalaverage >= 90)
                                {
                                    $eachenstud->genavecat = 'A';
                                }
                                // }
                                
                            }
                            
                        }
                        // return $students;
                        $category = $request->get('category');
                        
                        $sections = array();
                        $strands = array();
                        if(!$request->has('export'))
                        {
                            foreach($gradelevels as $gradelevel)
                            {
                                $eachlevelstud = collect($students)->where('levelid', $gradelevel->id)->values();
                                
                                $studrecord = collect($eachlevelstud)->filter(function ($item) use($category) {
                                    if($category == 'B')
                                    {
                                        if($item->generalaverage <= 74 && $item->generalaverage != null)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'D')
                                    {
                                        if($item->generalaverage >= 75 && $item->generalaverage <= 79)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'AP')
                                    {
                                        if($item->generalaverage >= 80 && $item->generalaverage <= 84)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'P')
                                    {
                                        if($item->generalaverage >= 85 && $item->generalaverage <= 89)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'A')
                                    {
                                        if($item->generalaverage >= 90)
                                        {
                                            return $item;
                                        }
                                    }
                                    
                                })->values();
                                if(count($gradelevel->sections)>0)
                                {
                                    foreach($gradelevel->sections as $eachsection)
                                    {
                                        $eachsection->levelname = $gradelevel->levelname;
                                        $eachsection->male = collect($studrecord)->where('sectionid', $eachsection->id)->where('gender', 'male')->count();
                                        $eachsection->female = collect($studrecord)->where('sectionid', $eachsection->id)->where('gender', 'female')->count();
                                        $eachsection->total = collect($studrecord)->where('sectionid', $eachsection->id)->count();
                                        array_push($sections, $eachsection);
                                    }
                                }
                        
            
                            }
                            return $sections;
                        }else{
                            $students = collect($students)->where('genavecat', $category)->all();
                            // return $students;
                            if(count($students)>0)
                            {
                                foreach($students as $student)
                                {
                                    $student->sortname = $student->lastname.', '.$student->firstname;
                                }
                            }
                            $levelname = DB::table('gradelevel')->where('id', $request->get('levelid'))->first()->levelname;
                            $students = collect($students)->sortBy('sortname')->values();
                            $syinfo = db::table('sy')->where('id', $syid)->first();
                            $seminfo = db::table('semester')->where('id', $semid)->first();
                            $pdf = PDF::loadview('adminITPortal.pages.academic.pdf_gradescat',compact('students','syinfo','seminfo','category','levelname'));
                            return $pdf->stream('SHS Students With '.$category.' Grade.pdf');
                        }
                    }
                    elseif($request->get('filtertype') == 'shs')
                    {
                        $students = collect($students)->whereIn('levelid',collect($gradelevels)->pluck('id'))->values();
                        if(count($students)>0)
                        {
                            foreach($students as $eachenstud)
                            {
        
                                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                // {
                                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $eachenstud->levelid,$eachenstud->id,$syid,$eachenstud->strandid,null,$eachenstud->ensectid);
                                    $studgrades = collect($studgrades)->where('semid', $semid)->values();
                                    $temp_grades = array();
                                    $generalaverage = array();
                                    foreach($studgrades as $item){
                                        if($item->id == 'G1'){
                                            array_push($generalaverage,$item);
                                        }else{
                                            if($item->strandid == $eachenstud->strandid){
                                                array_push($temp_grades,$item);
                                            }
                                            if($item->strandid == null){
                                                array_push($temp_grades,$item);
                                            }
                                        }
                                    }
                                    $grades = $temp_grades;
                                    $grades = collect($grades)->values();
                                    if(count($grades)>0)
                                    {
                                        foreach($grades as $grade)
                                        {
                                            if(!collect($grade)->contains('mapeh'))
                                            {
                                                $grade->mapeh = 0;
                                            }
                                        }
                                    }
                                        
                                    foreach($grades as $key=>$item){
                                        $checkStrand = DB::table('sh_subjstrand')
                                                            ->where('subjid',$item->subjid)
                                                            ->where('deleted',0)
                                                            ->get();
                                        if( count($checkStrand) > 0 ){
                                            $check_same_strand = collect($checkStrand)->where('strandid',$eachenstud->strandid)->count();
                                            if( $check_same_strand == 0){
                                                unset($grades[$key]); 
                                            }
                                        }
                                    }
                                    $genave = collect($grades)->where('mapeh','0')->average('finalrating');
                                    if(count($generalaverage) == 0)
                                    {
                                        $eachenstud->generalaverage = $genave;
    
                                    }else{
                                        $eachenstud->generalaverage = $generalaverage[0]->finalrating;
                                    }
                                    
                                    if($eachenstud->generalaverage <= 74 && $eachenstud->generalaverage != null)
                                    {
                                        $eachenstud->genavecat = 'B';
                                    }
    
                                    if($eachenstud->generalaverage >= 75 && $eachenstud->generalaverage <= 79)
                                    {
                                        $eachenstud->genavecat = 'D';
                                    }
    
                                    if($eachenstud->generalaverage >= 80 && $eachenstud->generalaverage <= 84)
                                    {
                                        $eachenstud->genavecat = 'AP';
                                    }
    
                                    if($eachenstud->generalaverage >= 85 && $eachenstud->generalaverage <= 89)
                                    {
                                        $eachenstud->genavecat = 'P';
                                    }
    
                                    if($eachenstud->generalaverage >= 90)
                                    {
                                        $eachenstud->genavecat = 'A';
                                    }
                            }
                            
                        }
                        
                        $category = $request->get('category');
                        
                        $strands = array();
                        
                        if(!$request->has('export'))
                        {
                            foreach($gradelevels as $gradelevel)
                            {
                                $eachlevelstud = collect($students)->where('levelid', $gradelevel->id)->values();
                                
                                $studrecord = collect($eachlevelstud)->filter(function ($item) use($category) {
                                    if($category == 'B')
                                    {
                                        if($item->generalaverage <= 74 && $item->generalaverage != null)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'D')
                                    {
                                        if($item->generalaverage >= 75 && $item->generalaverage <= 79)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'AP')
                                    {
                                        if($item->generalaverage >= 80 && $item->generalaverage <= 84)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'P')
                                    {
                                        if($item->generalaverage >= 85 && $item->generalaverage <= 89)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'A')
                                    {
                                        if($item->generalaverage >= 90)
                                        {
                                            return $item;
                                        }
                                    }
                                    
                                })->values();
        
                                if(count($gradelevel->strands)>0)
                                {
                                    foreach($gradelevel->strands as $eachstrand)
                                    {
                                        $eachstrand->levelname = $gradelevel->levelname;
                                        $eachstrand->male = collect($studrecord)->where('strandid', $eachstrand->strandid)->where('gender', 'male')->count();
                                        $eachstrand->female = collect($studrecord)->where('strandid', $eachstrand->strandid)->where('gender', 'female')->count();
                                        $eachstrand->total = collect($studrecord)->where('strandid', $eachstrand->strandid)->count();
                                        array_push($strands, $eachstrand);
                                    }
                                }
            
                            }
                            return $strands;
                        }else{
                            $students = collect($students)->where('genavecat', $category)->all();
                            // return $students;
                            if(count($students)>0)
                            {
                                foreach($students as $student)
                                {
                                    $student->sortname = $student->lastname.', '.$student->firstname;
                                }
                            }
                            $levelname = DB::table('gradelevel')->where('id', $request->get('levelid'))->first()->levelname;
                            $students = collect($students)->sortBy('sortname')->values();
                            $syinfo = db::table('sy')->where('id', $syid)->first();
                            $seminfo = db::table('semester')->where('id', $semid)->first();
                            $pdf = PDF::loadview('adminITPortal.pages.academic.pdf_gradescatshs',compact('students','syinfo','seminfo','category','levelname'));
                            return $pdf->stream('SHS Students With '.$category.' Grade.pdf');
                        }
    
                    }
    
                }elseif($request->get('action') == 'export')
                {
                    if($request->get('exporttype') == 'numberofenrollees')
                    {
                        $syinfo = db::table('sy')->where('id', $syid)->first();
                        $pdf = PDF::loadview('adminITPortal.pages.academic.pdf_numberofenrollees',compact('gradelevels','syinfo'));
                        return $pdf->stream('Number of Enrollees.pdf');
                    }
                }
            }else{
                return view('adminITPortal.pages.academic.index')
                    ->with('gradelevels', $gradelevels);
            }
        }

    }
    public function academicstudents(Request $request)
    {
        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        try{
            $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
            $schoolyears = $guzzleClient->request('GET', $url->eslink.'/passData?action=getschoolyears');
            $schoolyears = $schoolyears->getBody()->getContents();
            $schoolyears =  json_decode($schoolyears, true);
    
            $semesters = $guzzleClient->request('GET', $url->eslink.'/passData?action=getsemesters');
            $semesters = $semesters->getBody()->getContents();
            $semesters =  json_decode($semesters, true);
            
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = collect($schoolyears)->where('isactive','1')->values()[0]['id'];
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = collect($semesters)->where('isactive','1')->values()[0]['id'];
            }
        }catch(\Exception $error)
        {
            $semesters = DB::table('semester')
                ->get();
            $schoolyears = DB::table('sy')
                ->get();
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = DB::table('sy')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = DB::table('semester')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
        }
        $gradelevels = DB::table('gradelevel')
            ->select('id','levelname','acadprogid','sortid')
            ->where('deleted','0')
            ->orderBy('sortid','asc')
            ->get();

        foreach($gradelevels as $gradelevel)
        {
            if($gradelevel->acadprogid == 6)
            {
                $enrollees = DB::table('college_enrolledstud')
                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','college_enrolledstud.yearLevel as levelid','college_enrolledstud.sectionID as sectionid','college_enrolledstud.studstatus','college_courses.courseabrv','college_colleges.collegeabrv')
                    ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                    ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                    ->join('college_colleges','college_courses.collegeid','=','college_colleges.id')
                    ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                    ->where('college_enrolledstud.deleted','0')
                    ->where('college_enrolledstud.syid',$syid)
                    ->where('college_enrolledstud.semid',$semid)
                    ->where('college_enrolledstud.yearLevel',$gradelevel->id)
                    ->get();

            }elseif($gradelevel->acadprogid == 5)
            {
                $enrollees = DB::table('sh_enrolledstud')
                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','sh_enrolledstud.levelid','sh_enrolledstud.sectionid','sh_enrolledstud.studstatus','sections.sectionname','sh_strand.strandcode','sh_track.trackname')
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                    ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                    ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                    ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                    ->where('sh_enrolledstud.deleted','0')
                    ->where('sh_enrolledstud.syid',$syid)
                    ->where('sh_enrolledstud.semid',$semid)
                    ->where('sh_enrolledstud.levelid',$gradelevel->id)
                    ->get();
            }else{
                $enrollees = DB::table('enrolledstud')
                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','enrolledstud.levelid','enrolledstud.sectionid','enrolledstud.studstatus','sections.sectionname')
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    ->join('sections','enrolledstud.sectionid','=','sections.id')
                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                    ->where('enrolledstud.deleted','0')
                    ->where('enrolledstud.syid',$syid)
                    ->where('enrolledstud.levelid',$gradelevel->id)
                    ->get();
            }
            $gradelevel->label = ucwords(strtolower($gradelevel->levelname)).': '.count($enrollees).' enrollees';
            $gradelevel->enrollees = $enrollees;
            $gradelevel->enrolleesmalecount = collect($enrollees)->whereIn('gender',['male','Male','MALE'])->count();
            $gradelevel->enrolleesfemalecount = collect($enrollees)->whereIn('gender',['female','Female','FEMALE'])->count();
            $gradelevel->enrolleescount = count($enrollees);
        }
        if($request->get('action') == 'getenrollmentresults')
        {
            $sydesc = DB::table('sy')->where('id', $syid)->first()->sydesc;
            $semdesc = DB::table('semester')->where('id', $semid)->first()->semester;
            return view('adminITPortal.pages.academic.filterresults.enrollees')
                ->with('gradelevels', $gradelevels)
                ->with('sydesc', $sydesc)
                ->with('semdesc', $semdesc);
        }
        elseif($request->get('action') == 'getstatistics_sf5')
        {
            if($request->has('levelid'))
            {
                $results = collect($gradelevels)->where('id', $request->get('levelid'))->values();
                $levelid = $request->get('levelid');
            }else{
                $results = collect($gradelevels)->take(1);
                $levelid = $results[0]->id;
            }
            $schoolyear = DB::table('sy')->where('id',$syid)->first();
            Session::put('schoolYear', $schoolyear);
            foreach($results as $gradelevel)
            {
                if(count($gradelevel->enrollees)>0)
                {
                    foreach($gradelevel->enrollees as $eachenrollee)
                    {
                        $eachenrollee->ensectid = $eachenrollee->sectionid;
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                        {
                            if($syid == 2){
                                $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$eachenrollee->id,null);
                                
                                
                                if($request->has('action'))
                                {
                                    $studentInfo[0]->data = DB::table('studinfo')
                                                        ->select('studinfo.*','studinfo.sectionid as ensectid','studinfo.levelid as enlevelid','gradelevel.levelname','acadprogid')
                                                        ->where('studinfo.id',$eachenrollee->id)
                                    
                                                        ->join('gradelevel','studinfo.levelid','=','gradelevel.id')->get();
                                    $studentInfo[0]->count = 1;
                                    $studentInfo[0]->data[0]->teacherfirstname = "";
                                    $studentInfo[0]->data[0]->teachermiddlename = " ";
                                    $studentInfo[0]->data[0]->teacherlastname = "";
                                }
                        
                                if($studentInfo[0]->count == 0){
                        
                                    $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$eachenrollee->id,null,4);
                                    
                                    $studentInfo = DB::table('enrolledstud')
                                        ->where('studid',$eachenrollee->id)
                                        ->where('enrolledstud.deleted',0)
                                        ->select(
                                            'enrolledstud.sectionid as ensectid',
                                            'acadprogid',
                                            'enrolledstud.studid as id',
                                            'enrolledstud.strandid',
                                            'enrolledstud.semid',
                                            'lastname',
                                            'firstname',
                                            'middlename',
                                            'lrn',
                                            'dob',
                                            'gender',
                                            'levelname',
                                            'sections.sectionname as ensectname'
                                            )
                                        ->join('gradelevel',function($join){
                                            $join->on('enrolledstud.levelid','=','gradelevel.id');
                                            $join->where('gradelevel.deleted',0);
                                        })
                                        ->join('sections',function($join){
                                            $join->on('enrolledstud.sectionid','=','sections.id');
                                            $join->where('sections.deleted',0);
                                        })
                                            ->join('studinfo',function($join){
                                            $join->on('enrolledstud.studid','=','studinfo.id');
                                            $join->where('gradelevel.deleted',0);
                                        })
                                        ->get();
                                                        
                                    $studentInfo = array((object)[
                                            'data'=>   $studentInfo                             
                                        ]);
                                                        
                                                        
                                }
                                $acad = $eachenrollee->acadprogid;
                                $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($studentInfo[0]->data[0], true, 'sf9',2);    
                                       
                                $grades = $gradesv4;
                                $grades = collect($grades)->unique('subjectcode');
                                
                            }else{
                                $temp_grades = array();
                                $finalgrade = array();
                                try{
                                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $gradelevel->id,$eachenrollee->id,$syid,null,null,$eachenrollee->sectionid);
                               
                                    foreach($studgrades as $item){
                                        if($item->id == 'G1'){
                                            array_push($finalgrade,$item);
                                        }else{
                                            array_push($temp_grades,$item);
                                        }
                                    }
                                   
                                }catch(\Exception $error)
                                {

                                }
                                $studgrades = $temp_grades;
                                $grades = collect($studgrades)->sortBy('sortid')->values();
                            }
                            $generalaverage =  array();
                        }
                        elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                        {
                            $grades = GenerateGrade::reportCardV4($eachenrollee, true, 'sf9');
                              
                            $generalaverage =  \App\Models\Grades\GradesData::general_average($grades);
                            $grades =  \App\Models\Grades\GradesData::get_finalrating($grades,$gradelevel->acadprogid);
                            $generalaverage =  \App\Models\Grades\GradesData::get_finalrating($generalaverage,$gradelevel->acadprogid);
                        }else{
                            $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel->levelid,$eachenrollee->id,$schoolyear->id,null,null,$eachenrollee->sectionid,true);
                            $temp_grades = array();
                            $generalaverage = array();
                            foreach($studgrades as $item){
                                if($item->id == 'G1'){
                                    if($item->finalrating == null)
                                    {
                                        $item->finalrating = $item->lfr;
                                    }
                                    array_push($generalaverage,$item);
                                }else{
                                    array_push($temp_grades,$item);
                                }
                            }
                        
                            $studgrades = $temp_grades;
                            $grades = collect($studgrades)->sortBy('sortid')->values();
                        }
                        $genave = null;
                        
                        if(count($generalaverage)>0)
                        {
                            $genave = $generalaverage[0]->finalrating;
                        }else{
                            if(collect($grades)->where('finalrating',null)->where('mapeh','0')->where('inTLE','0')->count() == 0)
                            {
                                $genave = number_format(collect($grades)->where('mapeh','0')->where('inTLE','0')->avg('finalrating'));
                            }
                        }
                        $eachenrollee->genave = $genave;
                        $teachername ='';

                        $getteachername = DB::table('sectiondetail')
                            ->select('teacher.*')
                            ->leftJoin('teacher','sectiondetail.teacherid','=','teacher.id')
                            ->where('sectiondetail.sectionid', $eachenrollee->sectionid)
                            ->where('sectiondetail.syid', $syid)
                            // ->where('sectiondetail.semid', $semid)
                            ->where('sectiondetail.deleted','0')
                            ->first();

                        if($getteachername)
                        {
                            $teachername =$getteachername->firstname.' '.$getteachername->middlename.' '.$getteachername->lastname.' '.$getteachername->suffix;;
                        }
                        $eachenrollee->teachername = $teachername;
                    }
                }
            }

            $didnotmeet_m = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','<','75')->whereIn('gender',['MALE','Male','male'])->count();
            $didnotmeet_f = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','<','75')->whereIn('gender',['FEMALE','Female','female'])->count();
            $didnotmeet_t = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','<','75')->count();

            $fairlysatisfactory_m = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','75')->where('genave','<=','79')->whereIn('gender',['MALE','Male','male'])->count();
            $fairlysatisfactory_f = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','75')->where('genave','<=','79')->whereIn('gender',['FEMALE','Female','female'])->count();
            $fairlysatisfactory_t = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','75')->where('genave','<=','79')->count();

            $satisfactory_m = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','80')->where('genave','<=','84')->whereIn('gender',['MALE','Male','male'])->count();
            $satisfactory_f = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','80')->where('genave','<=','84')->whereIn('gender',['FEMALE','Female','female'])->count();
            $satisfactory_t = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','80')->where('genave','<=','84')->count();

            $verysatisfactory_m = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','85')->where('genave','<=','89')->whereIn('gender',['MALE','Male','male'])->count();
            $verysatisfactory_f = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','85')->where('genave','<=','89')->whereIn('gender',['FEMALE','Female','female'])->count();
            $verysatisfactory_t = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','85')->where('genave','<=','89')->count();

            $outsatanding_m = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','90')->whereIn('gender',['MALE','Male','male'])->count();
            $outsatanding_f = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','90')->whereIn('gender',['FEMALE','Female','female'])->count();
            $outsatanding_t = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','90')->count();
            
            return view('adminITPortal.pages.academic.filterresults.statistics_sf5')
                ->with('didnotmeet_m', $didnotmeet_m)
                ->with('didnotmeet_f', $didnotmeet_f)
                ->with('didnotmeet_t', $didnotmeet_t)
                ->with('fairlysatisfactory_m', $fairlysatisfactory_m)
                ->with('fairlysatisfactory_f', $fairlysatisfactory_f)
                ->with('fairlysatisfactory_t', $fairlysatisfactory_t)
                ->with('satisfactory_m', $satisfactory_m)
                ->with('satisfactory_f', $satisfactory_f)
                ->with('satisfactory_t', $satisfactory_t)
                ->with('verysatisfactory_m', $verysatisfactory_m)
                ->with('verysatisfactory_f', $verysatisfactory_f)
                ->with('verysatisfactory_t', $verysatisfactory_t)
                ->with('outsatanding_m', $outsatanding_m)
                ->with('outsatanding_f', $outsatanding_f)
                ->with('outsatanding_t', $outsatanding_t)
                ->with('results', $results)
                ->with('levelid', $levelid)
                ->with('gradelevels', $gradelevels);
        }else{
            // return $semesters[0]['semester'];
            return view('adminITPortal.pages.academic.students')
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters)
                ->with('gradelevels', $gradelevels);
        }

    }
    public function hrindex(Request $request)
    {     
        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        // self::switchschool();
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        try{
            $employees = $guzzleClient->request('GET', $url->eslink.'/passData?action=getemployees');
            $employees = $employees->getBody()->getContents();
    
            $employees =  json_decode($employees, true);
            $employees = json_decode(json_encode($employees), FALSE);
        }catch(\Exception $error)
        { 
        // $users = DB::connection('mysql')->select('Select id from users');
        // return $users;
            $request->merge(['action' => 'getemployees']);
            $employees = self::passData($request);
        }
        

        if(!$request->has('action'))
        {
            return view('adminITPortal.pages.hr.index')
                ->with('url',$url)
                ->with('employees',$employees);
        }else{
            if($request->get('action') == 'getemployees')
            {
                return view('adminITPortal.pages.hr.index')
                    ->with('url',$url)
                    ->with('employees',$employees);
            }else{
                $pdf = PDF::loadview('adminITPortal/pages/hr/printables/pdf_employees',compact('employees'))->setPaper('8.5x11');
                return $pdf->stream('Employees Msaterlist.pdf');
            }
        }
    }
    public function studentmasterlist(Request $request){

        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }

        $students = DB::table('studinfo')
                        ->where('studinfo.deleted',0);

        $data = (object)[];

        if($request->has('enrolled') && $request->get('enrolled') == 'enrolled'  ){

            $students = $students->where('studinfo.studstatus',1);

       

            $activeSy = DB::table('sy')->where('isactive',1)->first()->id;
            $activeSem = DB::table('semester')->where('isactive',1)->first()->id;



            if($request->has('sy') && $request->get('sy') != null  ){
                $activeSy = $request->get('sy');
            }
            if($request->has('sem') && $request->get('sem') != null  ){

                $activeSem = $request->get('sem');
            }



            if( ( $request->has('announcement') && $request->get('announcement') != null ) ){

                if(( $request->has('annID') && $request->get('annID') != null )){

                    $students = $students
                                ->whereIn('studinfo.levelid',$request->get('gradelevel'))
                                ->leftJoin('notifications',function($join) use($request){
                                    $join->on('studinfo.userid','=','notifications.recieverid');
                                    $join->where('notifications.type','=',1);
                                    $join->where('notifications.deleted',0);
                                    $join->where('notifications.headerid',$request->get('annID'));
                                })
                                ->select('studinfo.*','notifications.status','notifications.created_at as createddatetime')
                                ->distinct()
                                ->get();

                    if( ( $request->has('parents') && $request->get('parents') != null ) ){

                        foreach($students as $item){

                            if($item->ismothernum == 1){

                                $item->parentName = ucwords(strtolower($item->mothername));

                            }
                            else if($item->isfathernum == 1){

                                $item->parentName = ucwords(strtolower($item->fathername));

                            }
                            else if($item->isguardannum == 1){

                                $item->parentName = ucwords(strtolower($item->guardianname));
                            }

                            try{

                                

                                $user = DB::table('users')
                                            ->where('email','P'.$item->sid)
                                            ->leftJoin('notifications',function($join) use($request){
                                                $join->on('users.id','=','notifications.recieverid');
                                                $join->where('notifications.type','=',1);
                                                $join->where('notifications.deleted',0);
                                                $join->where('notifications.headerid',$request->get('annID'));
                                            })
                                            ->select('users.id','notifications.status','notifications.created_at as createddatetime')
                                            ->first();
                                            
                                $item->parentUserid = $user->id;
                                $item->status = $user->status;

                            } catch (\Exception $e) {

                               

                            }
                        }

                    }


                    return $students;

                }
                else{

                    return "Announcement ID is required!";

                }

            }


            if( ( $request->has('textblast') && $request->get('textblast') != null ) ){

                if(( $request->has('annID') && $request->get('annID') != null )){

                    $currentDay = \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD');

                    $students = $students
                                ->whereIn('studinfo.levelid',$request->get('gradelevel'))
                                ->leftJoin('smsbunkertextblast',function($join) use($request,$currentDay) {
                                    $join->on('studinfo.userid','=','smsbunkertextblast.studid');
                                    // $join->where('smsbunkertextblast.messageid',$request->get('annID'));
                                    $join->whereDate('smsbunkertextblast.createddatetime', '=', \Carbon\Carbon::today()->toDateString());
                                })
                                ->select('studinfo.*','smsbunkertextblast.smsstatus','smsbunkertextblast.createddatetime')
                                ->distinct()
                                ->get();

                    if( ( $request->has('parents') && $request->get('parents') != null ) ){

                        foreach($students as $item){

                            if($item->ismothernum == 1){

                                $item->parentName = ucwords(strtolower($item->mothername));

                            }
                            else if($item->isfathernum == 1){

                                $item->parentName = ucwords(strtolower($item->fathername));

                            }
                            else if($item->isguardannum == 1){

                                $item->parentName = ucwords(strtolower($item->guardianname));
                            }

                            try{

                                

                                $user = DB::table('users')
                                            ->where('email','P'.$item->sid)
                                            ->leftJoin('smsbunkertextblast',function($join) use($request){
                                                $join->on('users.id','=','smsbunkertextblast.studid');
                                                // $join->where('smsbunkertextblast.messageid',$request->get('annID'));
                                                $join->whereDate('smsbunkertextblast.createddatetime', '=', \Carbon\Carbon::today()->toDateString());
                                            })
                                            ->select(
                                                'users.id',
                                                'smsbunkertextblast.smsstatus',
                                                'smsbunkertextblast.createddatetime')
                                            ->first();
                                            
                                $item->parentUserid = $user->id;
                                $item->status = $user->smsstatus;

                            } catch (\Exception $e) {

                               

                            }
                        }

                    }


                    return $students;

                }
                else{

                    return "Announcement ID is required!";

                }

            }
            
            
            $students = $students->leftJoin('enrolledstud',function($join) use($activeSy){
                            $join->on('studinfo.id','=','enrolledstud.studid');
                            $join->where('enrolledstud.deleted','0');
                            $join->where('enrolledstud.syid',$activeSy);
                        })
                        ->leftJoin('sh_enrolledstud',function($join) use($activeSy,$activeSem){
                            $join->on('studinfo.id','=','sh_enrolledstud.studid');
                            $join->where('sh_enrolledstud.deleted','0');
                            $join->where('sh_enrolledstud.syid',$activeSy);
                            $join->where('sh_enrolledstud.semid',$activeSem);
                        });

            if( ( $request->has('datefrom') && $request->get('datefrom') != null ) ){
            
                $datefrom = \Carbon\Carbon::create($request->get('datefrom'))->isoFormat('YYYY-MM-DD');

                $students =  $students->where(function($query)use($datefrom){
                    $query->whereDate('enrolledstud.dateenrolled','>=',$datefrom);
                    $query->orWhereDate('sh_enrolledstud.dateenrolled','>=',$datefrom);
                });

            }
            
            if( ( $request->has('dateto') && $request->get('dateto') != null ) ){

                $dateTo = \Carbon\Carbon::create($request->get('dateto'))->isoFormat('YYYY-MM-DD');

                $students =  $students->where(function($query)use($dateTo){

                    $query->whereDate('enrolledstud.dateenrolled','<=',$dateTo);
                    $query->orWhereDate('sh_enrolledstud.dateenrolled','<=',$dateTo);

                });

            }

            $enrolledstud = $students
                            ->join('gradelevel',function($join){
                                $join->on('studinfo.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted','0');
                            })
                            ->select('studinfo.*','gradelevel.acadprogid')
                            ->get();

            if($request->has('withacadprog') && $request->get('withacadprog') == 'withacadprog'  ){

                $data->enrrolledseniorhigh = collect($enrolledstud)->where('acadprogid',5)->count();
                $data->nursery = collect($enrolledstud)->where('acadprogid',2)->count();
                $data->gradeschool =  collect($enrolledstud)->where('acadprogid',3)->count();
                $data->juniorhigh =  collect($enrolledstud)->where('acadprogid',4)->count();
                $data->college =  collect($enrolledstud)->where('acadprogid',6)->count();
            }
            
            if($request->has('withgradelevel') && $request->get('withgradelevel') == 'withgradelevel'){
                $data->n = collect($enrolledstud)->where('levelid',4)->count();
                $data->k1 = collect($enrolledstud)->where('levelid',2)->count();
                $data->k2 = collect($enrolledstud)->where('levelid',3)->count();
                $data->g1 = collect($enrolledstud)->where('levelid',1)->count();
                $data->g2 = collect($enrolledstud)->where('levelid',5)->count();
                $data->g3 = collect($enrolledstud)->where('levelid',6)->count();
                $data->g4 = collect($enrolledstud)->where('levelid',7)->count();
                $data->g5 = collect($enrolledstud)->where('levelid',16)->count();
                $data->g6 = collect($enrolledstud)->where('levelid',9)->count();
                $data->g7 = collect($enrolledstud)->where('levelid',10)->count();
                $data->g8 = collect($enrolledstud)->where('levelid',11)->count();
                $data->g9 = collect($enrolledstud)->where('levelid',12)->count();
                $data->g10 = collect($enrolledstud)->where('levelid',13)->count();
                $data->g11 = collect($enrolledstud)->where('levelid',14)->count();
                $data->g12 = collect($enrolledstud)->where('levelid',15)->count();
                $data->year1 = collect($enrolledstud)->where('levelid',17)->count();
                $data->year2 = collect($enrolledstud)->where('levelid',18)->count();
                $data->year3 = collect($enrolledstud)->where('levelid',19)->count();
                $data->year4 = collect($enrolledstud)->where('levelid',20)->count();
            }

            if($request->has('students') && $request->get('students') == 'students'  ){

                if($request->has('gradelevel') && $request->get('gradelevel') != null){

                    $students->whereIn('studinfo.levelid',$request->get('gradelevel'));

                }

                $enrolledstud = $students->get();

                return $enrolledstud;

            }
           
            if($request->has('count') && $request->get('count') == 'count'  ){

                $data->totalenrolledstudents = $enrolledstud->count();

            }

        }

        return collect($data);

    }

   


    public function adminviewenrolledstudents(){

        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        $client = new \GuzzleHttp\Client();
        $result = $client->request('GET', 'http://essentielv2.ck/studentmasterlist?enrolled=enrolled&count=count&withacadprog=withacadprog&withgradelevel=withgradelevel');
        $students = $result->getBody()->getContents();

        $students =  json_decode($students, true);

        return $students;




        return view('adminITPortal.pages.students');

    }


    public function chrngtrans(Request $request){

        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        $date = Carbon::create('2020-06-11')->isoFormat('YYYY-MM-DD');

        $transdate = DB::table('chrngtrans')
                ->select(
                    'ornum',
                    'totalamount',
                    'amountpaid',
                    'cancelled',
                    'transdate'
                    )
                ->where('cancelled','0')
                ->where('syid',DB::table('sy')->where('isactive','1')->first()->id)
                ->where('semid',DB::table('semester')->where('isactive','1')->first()->id);


        $activeSy = DB::table('sy')->where('isactive',1)->first()->id;
        $activeSem = DB::table('semester')->where('isactive',1)->first()->id;

        if($request->has('sy') && $request->get('sy') != null  ){
            $activeSy = $request->get('sy');
        }
        if($request->has('sem') && $request->get('sem') != null  ){

            $activeSem = $request->has('sem');
        }


        if( ( $request->has('datefrom') && $request->get('datefrom') != null ) ){

            $datefrom = \Carbon\Carbon::create($request->get('datefrom'))->isoFormat('YYYY-MM-DD');

            $transdate =  $transdate->where('transdate','>=',$datefrom);

        }
        
        if( ( $request->has('dateto') && $request->get('dateto') != null ) ){

            $dateTo = \Carbon\Carbon::create($request->get('dateto'))->isoFormat('YYYY-MM-DD');

            $transdate =  $transdate->where('transdate','<=',$dateTo);

        }

        if( ( $request->has('skip') && $request->get('skip') != null ) ){

            $transdate =  $transdate->skip(( $request->get('skip') - 1 ) * 10);

        }

        if($request->has('detail') && $request->get('detail') == 'detail'){

            return $transdate->take(10)->get();

        }

        if($request->has('count') && $request->get('count') == 'count'){

            return array( (object)['count'=>$transdate->count()]);

        }
            
        if($request->has('total') && $request->get('total') == 'total'){

            return array( (object)[
                                'totalAmountPaid'=> $transdate->sum('amountpaid'), 
                                'totalAmount'=>$transdate->sum('totalamount')
                            ]);

        }   
    }

    public static function targetcollection(){

        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        $transdate = DB::table('chrngtrans')
                            ->select(
                                'ornum',
                                'totalamount',
                                'amountpaid',
                                'cancelled',
                                'transdate'
                                )
                            ->join('studinfo',function($join){
                                $join->on('chrngtrans.studid','=','studinfo.id');
                                $join->where('studinfo.studstatus','1');
                            })
                            ->where('cancelled','0')
                            ->where('chrngtrans.syid',DB::table('sy')->where('isactive','1')->first()->id)
                            ->where('chrngtrans.semid',DB::table('semester')->where('isactive','1')->first()->id)
                            ->sum('amountpaid');

        $studpaysched = DB::table('studpayscheddetail')
                    ->where('studpayscheddetail.syid',DB::table('sy')->where('isactive','1')->first()->id)
                    ->where('studpayscheddetail.semid',DB::table('semester')->where('isactive','1')->first()->id)
                    ->join('studinfo',function($join){
                        $join->on('studpayscheddetail.studid','=','studinfo.id');
                        $join->where('studinfo.studstatus','1');
                    })
                    ->get();

        $countUnbalance = 0;
        $id = array();

        foreach($studpaysched as $item){

            if($item->amountpay + $item->balance != $item->amount){
               
                array_push($id,(object)[
                    'studid'=>$item->studid,
                    'balance'=>$item->balance,
                    'amountpay'=>$item->amountpay,
                    'amount'=>$item->amount
                ]
                );

                $item->balance = $item->amount - $item->amountpay;

            }

        }

        foreach($studpaysched as $item){

            if($item->amountpay + $item->balance != $item->amount){

                $countUnbalance += 1;

            }
        }


        return collect($studpaysched)->sum('amountpay');

        
        return $billings;

    }

    public function enrollmentReport()
    {

        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        // $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        // $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        // // return $url->eslink.'/passData?action=getemployees';
        // $employees = $guzzleClient->request('GET', $url->eslink.'/passData?action=getemployees');
        // $employees = $employees->getBody()->getContents();
        // $employees =  json_decode($employees, true);
        // $employees = json_decode(json_encode($employees), FALSE);
        // return view('adminITPortal.pages.reports.enrollment')->with('students',$students);
        // try{

        //     $client = new \GuzzleHttp\Client();

        //     $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

        //     $result = $client->request('GET',$url->eslink.'/studentmasterlist?enrolled=enrolled&count=count&withacadprog=withacadprog&withgradelevel=withgradelevel');
        //     $students = $result->getBody()->getContents();
        //     $students =  (object)json_decode($students, true);

        //     return view('adminITPortal.pages.reports.enrollment')->with('students',$students);

        // } catch (\Exception $e) {

        //     $client = new \GuzzleHttp\Client();

        //     $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

        //     $result = $client->request('GET',$url->eslink.'/studentmasterlist?enrolled=enrolled&count=count&withacadprog=withacadprog&withgradelevel=withgradelevel');
        //     $students = $result->getBody()->getContents();
        //     $students =  (object)json_decode($students, true);

        //     return view('adminITPortal.pages.reports.enrollment')->with('students',$students); 

        // }

        return $students;

    }

    public function filterEnrollmentReport(Request $request){

        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        try{

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/studentmasterlist?enrolled=enrolled&count=count&withacadprog=withacadprog&withgradelevel=withgradelevel&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto'));
            $students = $result->getBody()->getContents();
            $students =  (object)json_decode($students, true);
            // return collect($students);
            return view('adminITPortal.pages.reports.enrolledsection')
            ->with('students',$students);

        } catch (\Exception $e) {

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/studentmasterlist?enrolled=enrolled&count=count&withacadprog=withacadprog&withgradelevel=withgradelevel&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto'));
            $students = $result->getBody()->getContents();
            $students =  (object)json_decode($students, true);

            return view('adminITPortal.pages.reports.enrolledsection')
            ->with('students',$students);
        }     

    }

    public function cashtransReport(Request $request){


        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        try{

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/cashtransaction?detail=detail');
            $cashtrans = $result->getBody()->getContents();
            $cashtrans =  (object)json_decode($cashtrans, true);

            $amountpaidresult = $client->request('GET',$url->eslink.'/cashtransaction?total=total');
            $amountpaid = $amountpaidresult->getBody()->getContents();
            $amountpaid =  json_decode($amountpaid, true);
        
            $countresult = $client->request('GET',$url->eslink.'/cashtransaction?count=count');
            $count = $countresult->getBody()->getContents();
            $count =  json_decode($count, true);

            return  view('adminITPortal.pages.reports.cashtrans')
                            ->with('cashtrans',$cashtrans)
                            ->with('amountpaid',$amountpaid)
                            ->with('count',$count);

        } catch (\Exception $e) {

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/cashtransaction?detail=detail');
            $cashtrans = $result->getBody()->getContents();
            $cashtrans =  (object)json_decode($cashtrans, true);

            $amountpaidresult = $client->request('GET',$url->eslink.'/cashtransaction?total=total');
            $amountpaid = $amountpaidresult->getBody()->getContents();
            $amountpaid =  json_decode($amountpaid, true);
        
            $countresult = $client->request('GET',$url->eslink.'/cashtransaction?count=count');
            $count = $countresult->getBody()->getContents();
            $count =  json_decode($count, true);

            return  view('adminITPortal.pages.reports.cashtrans')
                            ->with('cashtrans',$cashtrans)
                            ->with('amountpaid',$amountpaid)
                            ->with('count',$count);

        }
        
    }

    public function filtercashtrans(Request $request){

        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        try{

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/cashtransaction?detail=detail&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto').'&skip='.$request->get('pagenum'));
            $cashtrans = $result->getBody()->getContents();
            $cashtrans =  (object)json_decode($cashtrans, true);

            $amountpaidresult = $client->request('GET',$url->eslink.'/cashtransaction?total=total');
            $amountpaid = $amountpaidresult->getBody()->getContents();
            $amountpaid =  json_decode($amountpaid, true);
        
            $countresult = $client->request('GET',$url->eslink.'/cashtransaction?count=count'.'&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto'));
            $count = $countresult->getBody()->getContents();
            $count =  json_decode($count, true);

            return view('adminITPortal.pages.reports.cashtranssection')
                            ->with('count',$count)
                            ->with('amountpaid',$amountpaid)
                            ->with('cashtrans',$cashtrans);

        } catch (\Exception $e) {

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/cashtransaction?detail=detail&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto').'&skip='.$request->get('pagenum'));
            $cashtrans = $result->getBody()->getContents();
            $cashtrans =  (object)json_decode($cashtrans, true);

            $amountpaidresult = $client->request('GET',$url->eslink.'/cashtransaction?total=total');
            $amountpaid = $amountpaidresult->getBody()->getContents();
            $amountpaid =  json_decode($amountpaid, true);
        
            $countresult = $client->request('GET',$url->eslink.'/cashtransaction?count=count'.'&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto'));
            $count = $countresult->getBody()->getContents();
            $count =  json_decode($count, true);

            return view('adminITPortal.pages.reports.cashtranssection')
                            ->with('count',$count)
                            ->with('amountpaid',$amountpaid)
                            ->with('cashtrans',$cashtrans);

        }

    }




}
