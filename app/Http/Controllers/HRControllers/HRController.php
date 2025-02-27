<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use \NumberFormatter\NumberFormatter;
use Illuminate\Http\Request;
use DB;
use Crypt;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use App\MoneyCurrency;
use PDF;
use FontLib\Font;
use DateTime;
use DateInterval;
use DatePeriod;
use Conversion;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\HR\HRAllowances;
use App\Models\HR\HRDeductions;
class HRController extends Controller
{
    public function payrollleapyear(Request $request)
    {
        // return $request->all();
        DB::table('payroll')
            ->where('status','1')
            ->update([
                'leapyear'  => $request->get('leapyearactivation')
            ]);

        return back();
    }
    
    public function payroll($id, Request $request)
    {
        
        $tardy = 0;
        
        date_default_timezone_set('Asia/Manila');

        $my_id = DB::table('teacher')
            // ->select('id')
            ->where('userid',auth()->user()->id)
            ->where('tid',auth()->user()->email)
            ->where('isactive','1')
            ->first();


        if($id == 'dashboard'){
            
            $payrolldate = Db::table('payroll')
                ->where('status','1')
                ->get();
            if(count($payrolldate) == 0){

                Db::table('payroll')
                    ->insert([
                        'datefrom'  =>date('Y-m-01'),
                        'dateto'    =>date('Y-m-15'),
                        'status'    =>1,
                        'createdby' => $my_id->id,
                        'createdon' =>date('Y-m-d H:i:s')
                    ]);

                $payrolldate = Db::table('payroll')
                    ->where('status','1')
                    ->get();

                foreach($payrolldate as $datepayroll){

                    $datepayroll->datefrom = date('m-d-Y',strtotime($datepayroll->datefrom));

                    $datepayroll->dateto = date('m-d-Y',strtotime($datepayroll->dateto));

                    $mindate = explode('-',$datepayroll->dateto);

                    $datepayroll->mindate = $mindate[2].'-'.$mindate[0].'-'.$mindate[1];
                }
            }
            else{
                foreach($payrolldate as $datepayroll){

                    $datepayroll->datefrom = date('m-d-Y',strtotime($datepayroll->datefrom));

                    $datepayroll->dateto = date('m-d-Y',strtotime($datepayroll->dateto));

                    $mindate = explode('-',$datepayroll->dateto);

                    $datepayroll->mindate = $mindate[2].'-'.$mindate[0].'-'.$mindate[1];

                }
                
            }
            $employeesmasterlist = array();
            
            if($request->get('filteremployees') == true){
                
                $employeesmasterlistfilter = Db::table('teacher')
                    ->select(
                        'teacher.id',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.lastname',
                        'teacher.suffix',
                        'teacher.picurl',
                        'usertype.utype',
                        'employee_personalinfo.gender',
                        'employee_personalinfo.departmentid'
                        )
                    ->join('usertype','teacher.usertypeid','usertype.id')
                    ->join('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                    ->where('usertype.utype','!=','PARENT')
                    ->where('usertype.utype','!=','STUDENT')
                    ->where('isactive','1')
                    ->orderby('id', 'asc')
                    ->get();
                if($request->get('filteremployees') == 'all'){

                    foreach($employeesmasterlistfilter as $employeefilter){

                        array_push($employeesmasterlist, $employeefilter);

                    }

                }else{
                    foreach($employeesmasterlistfilter as $employeefilter){
                        $getsalarytype = Db::table('employee_basicsalaryinfo')
                            ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                            ->where('employeeid',$employeefilter->id)
                            ->where('employee_basistype.type',$request->get('filteremployees'))
                            ->where('status','0')
                            ->get();

                        if(count($getsalarytype)>0){

                            array_push($employeesmasterlist, $employeefilter);

                        }
                    }
                }
            }else{
                $employeesmasterlistfilter = Db::table('teacher')
                    ->select(
                        'teacher.id',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.lastname',
                        'teacher.suffix',
                        'teacher.picurl',
                        'usertype.utype',
                        'employee_personalinfo.gender',
                        'usertype.departmentid'
                        )
                    ->join('usertype','teacher.usertypeid','usertype.id')
                    ->join('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                    ->where('usertype.utype','!=','PARENT')
                    ->where('usertype.utype','!=','STUDENT')
                    ->where('isactive','1')
                    ->where('usertype.deleted','0')
                    ->where('teacher.deleted','0')
                    ->orderby('id', 'asc')
                    ->get();
                    
                foreach($employeesmasterlistfilter as $employeefilter){

                    if($request->get('viewdetails') == true){
                        if($employeefilter->id == $request->get('employeeid')){

                            array_unshift($employeesmasterlist, $employeefilter);

                        }else{
                            array_push($employeesmasterlist, $employeefilter);
                        }
                    }else{
                        array_push($employeesmasterlist, $employeefilter);
                    }

                }

            }
            
            $employeeslist = array();

            foreach($employeesmasterlist as $employeemasterlist){

                $checkPayrollHistoryIfExists = Db::table('payroll_history')
                    ->where('employeeid',$employeemasterlist->id)
                    ->where('payrollid',$payrolldate[0]->id)
                    ->get();

                
                if(count($checkPayrollHistoryIfExists) == 0){

                    $employeemasterlist->payrollhistoryrecord = 0;
                    $employeemasterlist->released = 0;

                    if($request->get('viewdetails') == true){
                        if($employeemasterlist->id == $request->get('employeeid')){

                            array_unshift($employeeslist, $employeemasterlist);

                        }else{
                            array_push($employeeslist, $employeemasterlist);
                        }
    
                    }else{
                        array_push($employeeslist, $employeemasterlist);
                    }

                }else{

                    $employeemasterlist->payrollhistoryrecord = 1;

                    if($checkPayrollHistoryIfExists[0]->isreleased == 1){

                        $employeemasterlist->released = 1;

                    }else{

                        $employeemasterlist->released = 0;

                    }

                    if($request->get('viewdetails') == true){
                        if($employeemasterlist->id == $request->get('employeeid')){

                            array_unshift($employeeslist, $employeemasterlist);

                        }else{
                            array_push($employeeslist, $employeemasterlist);
                        }
                    }else{
                        array_push($employeeslist, $employeemasterlist);
                    }


                }

            }
            
            foreach($employeeslist as $employee){

                $getdepartmentid = DB::table('employee_personalinfo')
                    ->where('employeeid', $employeemasterlist->id)
                    ->where('departmentid','!=',null)
                    ->get();
                if(count($getdepartmentid) == 0){
                    $employee->departmentid = 0;
                }

            }

            $checkifPayrollExistsInHistory = Db::table('payroll_history')
                ->where('payrollid', $payrolldate[0]->id)
                ->get();

            if(count($checkifPayrollExistsInHistory) == 0){

                $existsinhistory = 0;

            }else{

                $existsinhistory = 1;

            }
            // return $employeesmasterlist;
            
            if(count($employeesmasterlist) == 0){
                return view('hr.employeesalary')
                    ->with('payrolldate',$payrolldate)
                    ->with('filteremployees','all')
                    ->with('noemployees','1')
                    ->with('existsinhistory',$existsinhistory);

            }
            // ====================================================================================================================== date range

            $getdaterange = Db::table('payroll')
                ->where('status','1')
                ->get();
            
            // ====================================================================================================================== get salary rate
            // return count($employeeslist);
            // return count($employeeslist);

            if(count($employeeslist)>0){
                // $employeeslist[0]->departmentid = 0;
                    $getrate = Db::table('employee_basicsalaryinfo')
                        ->select(
                            'employee_basicsalaryinfo.amount',
                            'employee_basistype.type',
                            'employee_basicsalaryinfo.shiftid',
                            'employee_basicsalaryinfo.mondays',
                            'employee_basicsalaryinfo.tuesdays',
                            'employee_basicsalaryinfo.wednesdays',
                            'employee_basicsalaryinfo.thursdays',
                            'employee_basicsalaryinfo.fridays',
                            'employee_basicsalaryinfo.saturdays',
                            'employee_basicsalaryinfo.sundays',
                            'employee_basicsalaryinfo.mondayhours',
                            'employee_basicsalaryinfo.tuesdayhours',
                            'employee_basicsalaryinfo.wednesdayhours',
                            'employee_basicsalaryinfo.thursdayhours',
                            'employee_basicsalaryinfo.fridayhours',
                            'employee_basicsalaryinfo.saturdayhours',
                            'employee_basicsalaryinfo.sundayhours',
                            'employee_basicsalaryinfo.holidays',
                            'employee_basicsalaryinfo.hoursperday',
                            'employee_basicsalaryinfo.projectbasedtype',
                            'employee_basicsalaryinfo.hoursperweek',
                            'employee_basicsalaryinfo.noofmonths',
                            'employee_basicsalaryinfo.deductionsetup as setuptype',
                            'employee_basicsalaryinfo.status'
                            )
                        ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                        ->where('employeeid',$employeeslist[0]->id)
                        ->where('status','0')
                        ->get();
                        
                // ====================================================================================================================== get working days
    
                $typecalendar = CAL_GREGORIAN;
    
                function getWorkingDays($startDate,$endDate){
    
                    $startDate = strtotime($startDate);
    
                    $endDate = strtotime($endDate);
                    
                    if($startDate <= $endDate){
    
                        $datediff = $endDate - $startDate;
    
                        return floor($datediff / (60 * 60 * 24));
    
                    }
    
                    return false;
                    
                    
                }

                

                $payrollworkingdays = array();

                $begin = new DateTime($getdaterange[0]->datefrom);

                $end = new DateTime($getdaterange[0]->dateto);

                $end = $end->modify( '+1 day' ); 
                
                $intervalday = new DateInterval('P1D');

                $daterange = new DatePeriod($begin, $intervalday ,$end);


                foreach($daterange as $date){

                    array_push($payrollworkingdays,$date->format("Y-m-d"));

                }

                
                
                if(count($getrate) == 0){
    
                    $day_count = getWorkingDays($getdaterange[0]->datefrom,$getdaterange[0]->dateto);
                    
                    $day_count+=1;

                    $workdays = array();

                    $begin = new DateTime($getdaterange[0]->datefrom);

                    $end = new DateTime($getdaterange[0]->dateto);

                    $end = $end->modify( '+1 day' ); 
                    
                    $interval = new DateInterval('P1D');

                    $daterange = new DatePeriod($begin, $interval ,$end);

                    $daysperiod = array();
    
                    foreach($daterange as $date){

                        array_push($daysperiod,$date->format("Y-m-d"));
                        
                    }
    
                    foreach($daysperiod as $dayperiod){
                        
                        $day_name = date('D', strtotime($dayperiod)); // Trim day name to 3 chars
                        
                        //if not a weekend add day to array
                        // if($day_name != 'Sun'){
                                
                            $workdays[] = $dayperiod;
    
                        // }
        
    
                    }
        
                    $beginmonth = new DateTime(date('Y-m-01', strtotime($getdaterange[0]->datefrom)));
    
                    $endmonth = new DateTime(date('Y-m-t', strtotime($getdaterange[0]->datefrom)));
    
                    $endmonth = $endmonth->modify( '+1 day' ); 
                    
                    $intervalmonth = new DateInterval('P1D');
    
                    $daterangemonth = new DatePeriod($beginmonth, $intervalmonth ,$endmonth);
    
                    $monthworkdays = array();
    
                    foreach($daterangemonth as $datemonth){
    
                            array_push($monthworkdays,$datemonth->format("Y-m-d"));
    
                    }
                }else{
                    
                    
                    $setuptype = $getrate[0]->setuptype;
                    
                    if($setuptype != 1)
                    {
                        HRDeductions::updatestandarddeductions($employeeslist[0]->id,$getrate[0]->amount, $getrate[0]->type);
                        // return $setuptype;
                    }
                    
                    $day_count = count($payrollworkingdays);
                    
                    if(strtolower($getrate[0]->type) == 'hourly'){
                        
                        $workdays = array();
                        foreach($payrollworkingdays as $dayperiod){
                            
                            $day_name = date('D', strtotime($dayperiod)); // Trim day name to 3 chars
                            
                            //if not a weekend add day to array
                            
                            if($day_name == 'Mon'){
                                
                                if(count($getrate) > 0){
                                    
                                    if($getrate[0]->mondays == 1){
            
                                        $workdays[] = $dayperiod;
            
                                    }
    
                                }
        
                            }elseif($day_name == 'Tue'){
                                
                                if(count($getrate) > 0){
                                    
                                    if($getrate[0]->tuesdays == 1){
            
                                        $workdays[] = $dayperiod;
            
                                    }
    
                                }
        
                            }elseif($day_name == 'Wed'){
                                
                                if(count($getrate) > 0){
                                    
                                    if($getrate[0]->wednesdays == 1){
            
                                        $workdays[] = $dayperiod;
            
                                    }
    
                                }
        
                            }elseif($day_name == 'Thu'){
                                
                                if(count($getrate) > 0){
                                    
                                    if($getrate[0]->thursdays == 1){
            
                                        $workdays[] = $dayperiod;
            
                                    }
    
                                }
        
                            }elseif($day_name == 'Fri'){
                                
                                if(count($getrate) > 0){
                                    
                                    if($getrate[0]->fridays == 1){
            
                                        $workdays[] = $dayperiod;
            
                                    }
    
                                }
        
                            }elseif($day_name == 'Sat'){
                                
                                if(count($getrate) > 0){
                                    
                                    if($getrate[0]->saturdays == 1){
            
                                        $workdays[] = $dayperiod;
            
                                    }
    
                                }
        
                            }elseif($day_name == 'Sun'){
                                
                                if(count($getrate) > 0){
                                    
                                    if($getrate[0]->sundays == 1){
    
                                        $workdays[] = $dayperiod;
            
                                    }
    
                                }
                                
        
                            }
    
                        }
                        
                        $beginmonth = new DateTime(date('Y-m-01', strtotime($getdaterange[0]->datefrom)));
    
                        $endmonth = new DateTime(date('Y-m-t', strtotime($getdaterange[0]->datefrom)));
    
                        $endmonth = $endmonth->modify( '+1 day' ); 
                        
                        $intervalmonth = new DateInterval('P1D');
    
                        $daterangemonth = new DatePeriod($beginmonth, $intervalmonth ,$endmonth);
    
                        $monthworkdays = array();
    
                        foreach($daterangemonth as $datemonth){
    
                            if($datemonth->format("D") == 'Sun'){
                                
                                if($getrate[0]->sundays == 1){
        
                                    array_push($monthworkdays,$datemonth->format("Y-m-d"));
        
                                }
        
                            }
                            elseif($datemonth->format("D") == 'Sat'){
                                
                                if($getrate[0]->saturdays == 1){
        
                                    array_push($monthworkdays,$datemonth->format("Y-m-d"));
        
                                }
        
                            }else{
        
                                array_push($monthworkdays,$datemonth->format("Y-m-d"));
        
                            }
    
                        }
        
                    }elseif(strtolower($getrate[0]->type) == 'daily'){
                         //automatic daily rate
    
    
    
                         
                        $workdays = array();
    
                        foreach($payrollworkingdays as $dayperiod){
                            
                            $day_name = date('D', strtotime($dayperiod)); // Trim day name to 3 chars
        
                                $workdays[] = $dayperiod;
    
                        }
                        
                        $monthworkdays = array();
        
                        
                        $beginmonth = new DateTime(date('Y-m-01', strtotime($getdaterange[0]->datefrom)));
    
                        $endmonth = new DateTime(date('Y-m-t', strtotime($getdaterange[0]->datefrom)));
    
                        $endmonth = $endmonth->modify( '+1 day' ); 
                        
                        $intervalmonth = new DateInterval('P1D');
    
                        $daterangemonth = new DatePeriod($beginmonth, $intervalmonth ,$endmonth);
    
                        $monthworkdays = array();
    
                        foreach($daterangemonth as $datemonth){
    
        
                                array_push($monthworkdays,$datemonth->format("Y-m-d"));
    
                        }
                        
        
                    }elseif(strtolower($getrate[0]->type) == 'monthly'){
    
                        $workdays = array();
        
                        $employeeworkdaysinaweek = 5;

                        $saturday = 0;
                        
                        $sunday = 0;
                        


                        foreach($payrollworkingdays as $dayperiod){
                            
                            $day_name = date('l', strtotime($dayperiod)); // Trim day name to 3 chars
                            // return $getrate[0]->saturdays;
                            // if(strtolower($day_name) == 'saturday' || strtolower($day_name) == 'sunday'){
                            if($day_name == 'Saturday'){
                                
                                if($getrate[0]->saturdays == 1){
                                    if($saturday == 0){
                                        $saturday+=1;
                                        $employeeworkdaysinaweek+=1;
                                    }
                                    $workdays[] = $dayperiod;
                                }
                                
                            }
                            
                            if($day_name == 'Sunday'){
                                
                                if($getrate[0]->sundays == 1){
                                    if($sunday == 0){
                                        $sunday+=1;
                                        $employeeworkdaysinaweek+=1;
                                    }
                                    $workdays[] = $dayperiod;
                                }
                            }

                            if($day_name != 'Saturday' && $day_name != 'Sunday'){
                                $workdays[] = $dayperiod;
                            }
                        }
                        

                        $workdays = array_unique($workdays);

                    
        
                        
                        $beginmonth = new DateTime(date('Y-m-01', strtotime($getdaterange[0]->datefrom)));
    
                        $endmonth = new DateTime(date('Y-m-t', strtotime($getdaterange[0]->datefrom)));
    
                        $endmonth = $endmonth->modify( '+1 day' ); 
                        
                        $intervalmonth = new DateInterval('P1D');
    
                        $daterangemonth = new DatePeriod($beginmonth, $intervalmonth ,$endmonth);
    
                        $monthworkdays = array();
    
                        foreach($daterangemonth as $datemonth){
                            if(strtolower($datemonth->format("l")) != 'saturday' && strtolower($datemonth->format("l")) != 'sunday'){
                                array_push($monthworkdays,$datemonth->format("Y-m-d"));
                            }
    
                        }
                        
        
                    }elseif(strtolower($getrate[0]->type) == 'project'){
    
                        $workdays = array();
        
                        foreach($payrollworkingdays as $dayperiod){
                            
                            $day_name = date('D', strtotime($dayperiod)); // Trim day name to 3 chars
    
                            if($getrate[0]->projectbasedtype == 'perday'){
    
                                // if($day_name != 'Sun'){
        
                                    $workdays[] = $dayperiod;
                                    
                                // }
    
                            }
                            elseif($getrate[0]->projectbasedtype == 'persalaryperiod'){
        
                                    $workdays[] = $dayperiod;
                                
                            }
                            elseif($getrate[0]->projectbasedtype == 'permonth'){
        
                                    $workdays[] = $dayperiod;
                                
                            }
        
    
                        }
        
                        $beginmonth = new DateTime(date('Y-m-01', strtotime($getdaterange[0]->datefrom)));
    
                        $endmonth = new DateTime(date('Y-m-t', strtotime($getdaterange[0]->datefrom)));
    
                        $endmonth = $endmonth->modify( '+1 day' ); 
                        
                        $intervalmonth = new DateInterval('P1D');
    
                        $daterangemonth = new DatePeriod($beginmonth, $intervalmonth ,$endmonth);
    
                        $monthworkdays = array();
    
                        foreach($daterangemonth as $datemonth){

                            array_push($monthworkdays,$datemonth->format("Y-m-d"));
    
                        }
        
                    }
                }
                // return $monthworkdays;
                // ====================================================================================================================== calculate daily/hpurly rates
                // return $getrate;
    
                if(count($getrate) == 0){
    
                    $getrate = array();
    
                    array_push($getrate,(object)array(
                        'amount'    =>  0,
                        'type'      =>  " ",
                        'shiftid'   =>  0,
                        'hoursperday'      =>  0,
                        'projectbasedtype' => ""
                    ));
    
                    $dailyrate = 0;
    
                    $hourlyrate = 0;

                    
                    $permonthhalfsalary = 0;
    
    
                }else{
    
                    
                    if(strtolower($getrate[0]->type) == 'monthly'){
                        // return $workdays;
                        if(count($workdays) == 0){
    
                            $dailyrate =  $getrate[0]->amount / (int)(date('t') - date('01'));
    
                        }else{
                            // round($getrate[0]->amount / $monthdaycount, 2);
                            // return $getrate[0]->amount / count($monthworkdays);
                            // return $getrate[0]->amount;
                            if($getdaterange[0]->leapyear == 0){
                                
                                $dailyrate =  ($getrate[0]->amount*12)/($employeeworkdaysinaweek*52);
                                
                            }else{

                                $dailyrate =  ($getrate[0]->amount*12)/(($employeeworkdaysinaweek*52)+1);

                            }
                            
                            
                            
                        }
                        // return $dailyrate;
                        if($dailyrate == 0 || $getrate[0]->hoursperday == 0){
                            // return 'asdasd';
                            $hourlyrate = 0;
                        }else{
                            
                            $hourlyrate = ($dailyrate)/$getrate[0]->hoursperday;
                        }
                        
                        $permonthhalfsalary = $getrate[0]->amount/2;

                        
    
                    }
                    elseif(strtolower($getrate[0]->type) == 'daily'){
        
                        $dailyrate =  round($getrate[0]->amount, 2);
                
                        $hourlyrate = ($dailyrate)/$getrate[0]->hoursperday;

                        // return $hourlyrate;
    
                    }
                    elseif(strtolower($getrate[0]->type) == 'hourly'){
                
                        $hoursperday = 0;
                        if($hoursperday == 0){
                            if($getrate[0]->mondayhours > 0){
                                $hoursperday = $getrate[0]->mondayhours;
                            }
                            if($getrate[0]->tuesdayhours > 0){
                                $hoursperday = $getrate[0]->tuesdayhours;
                            }
                            if($getrate[0]->wednesdayhours > 0){
                                $hoursperday = $getrate[0]->wednesdayhours;
                            }
                            if($getrate[0]->thursdayhours > 0){
                                $hoursperday = $getrate[0]->thursdayhours;
                            }
                            if($getrate[0]->fridayhours > 0){
                                $hoursperday = $getrate[0]->fridayhours;
                            }
                            if($getrate[0]->saturdayhours > 0){
                                $hoursperday = $getrate[0]->saturdayhours;
                            }
                            if($getrate[0]->sundayhours > 0){
                                $hoursperday = $getrate[0]->sundayhours;
                            }
                        }
                        
                        $dailyrate = ($getrate[0]->amount/$hoursperday);
                        
                        $hourlyrate = $getrate[0]->amount;
    
                    }
                    elseif(strtolower($getrate[0]->type) == 'project'){
                        // return $getrate[0]->hoursperday;
                        // return $workdays;
                        if($getrate[0]->projectbasedtype == 'persalaryperiod'){
                            
                            $dailyrate =  $getrate[0]->amount/count($workdays); 
    
                            $hourlyrate =  ($getrate[0]->amount/count($workdays))/$getrate[0]->hoursperday; 
    
                        }
                        elseif($getrate[0]->projectbasedtype == 'perday'){
                            
                            $dailyrate = $getrate[0]->amount;
    
                            $hourlyrate = $getrate[0]->amount/$getrate[0]->hoursperday;
    
                        }
                        elseif($getrate[0]->projectbasedtype == 'permonth'){
                            
                            $hourlyrate = ($getrate[0]->amount/count($monthworkdays))/$getrate[0]->hoursperday;
    
                            $dailyrate =  $getrate[0]->amount/count($monthworkdays); 

                            
                            // return
                            
                        }
                        
                    }
                    // return $dailyrate;
    
                }
                
                // ====================================================================================================================== calculate attendance salary
                $getlatedeductionsetup = Db::table('deduction_tardinesssetup')
                    ->where('status','1')
                    ->first();
                    
                if(strtolower($getlatedeductionsetup->type) == 'custom'){
                    
                    $deductiontardinessapplication = Db::table('deduction_tardinessapplication')
                        ->where('departmentid',$employeeslist[0]->departmentid)
                        ->where('deleted','0')
                        ->get();
                        
                    if(count($deductiontardinessapplication)>0){
                        $deductioncomputation = Db::table('deduction_tardinessdetail')
                            ->where('id',$deductiontardinessapplication[0]->tardinessdetailid)
                            ->where('deleted','0')
                            ->get();
                    } 
                }
                
    
                $gettimesched = DB::table('employee_customtimesched')
                    ->where('employeeid',$employeeslist[0]->id)
                    ->get();
                    
                if(count($gettimesched) == 0){

                    DB::table('employee_customtimesched')
                        ->insert([
                            'employeeid'    =>  $employeeslist[0]->id,
                            'amin'          =>  '08:00:00',
                            'amout'         =>  '12:00:00',
                            'pmin'          =>  '01:00:00',
                            'pmout'         =>  '05:00:00',
                            'createdby'     =>  $my_id->id,
                            'createdon'     =>  date('Y-m-d H:i:s')
                        ]);

                    $gettimesched = DB::table('employee_customtimesched')
                        ->where('employeeid',$employeeslist[0]->id)
                        ->get();
                }
                // get the shift
                // get the custom sched
                // get the days work
                // get absent -> filter holidays
                // get present -> filter holidays
                
                $latedeductionamount    = 0;

                $lateminutes            = 0;

                $undertimeminutes       = 0;
                
                $holidaypay             = 0;
    
                $dailynumofhours        = 0;

                $absentdeduction        = 0;

                $presentdays            = array();

                $absentdays             = array();

                $noabsentdays           = 0;
                foreach($workdays as $workday)
                {
                    $checkifexists = Db::table('teacherattendance')
                        ->where('teacher_id', $employeeslist[0]->id)
                        ->where('tdate', $workday)
                        ->where('deleted', '0')
                        ->first();

                    if(count(collect($checkifexists)) == 0)
                    {
                        $noabsentdays+=1;
                        array_push($absentdays, $workday);
                    }else{
                        if($checkifexists->out_am == '00:00:00')
                        {
                            $checkifexists->out_am = null;
                            DB::table('teacherattendance')
                                ->where('id', $checkifexists->id)
                                ->update([
                                    'out_am'    => null
                                ]);
                        }
                        if($checkifexists->in_pm == '00:00:00')
                        {
                            $checkifexists->in_pm = null;
                            DB::table('teacherattendance')
                                ->where('id', $checkifexists->id)
                                ->update([
                                    'in_pm'    => null
                                ]);
                        }
                        if($checkifexists->out_pm == '00:00:00')
                        {
                            $checkifexists->out_pm = null;
                            DB::table('teacherattendance')
                                ->where('id', $checkifexists->id)
                                ->update([
                                    'out_pm'    => null
                                ]);
                        }
                        array_push($presentdays, (object)array(
                            'presentdate'   => $workday,
                            'amin'          => $checkifexists->in_am,
                            'amout'         => $checkifexists->out_am,
                            'pmin'          => $checkifexists->in_pm,
                            'pmout'         => $checkifexists->out_pm
                        ));
                    }
                }
                
                
                if(count($workdays) == $noabsentdays){

                    $absentdeduction= ($permonthhalfsalary);

                }else{

                    $absentdeduction= ($dailyrate*$noabsentdays);
                }

                $latedeductionamount = 0;

                $minuteslate = 0;

                $minuteslatehalfday = 0;
                
                if(isset($deductioncomputation))
                {
                    
                    $customtimeamin = $gettimesched[0]->amin;
                    $customtimeamout = $gettimesched[0]->amout;
                    $customtimepmin = date('H:i:s',strtotime($gettimesched[0]->pmin.' PM'));
                    $customtimepmout = date('H:i:s',strtotime($gettimesched[0]->pmout.' PM'));
                }
                else{
                    $customtimeamin = '08:00';
                    $customtimeamout = '12:00';
                    $customtimepmin = date('H:i:s',strtotime('01:00 PM'));
                    $customtimepmout = date('H:i:s',strtotime('05:00 PM'));
                }
                // return $presentdays;
                if(count($presentdays)>0)
                {
                    foreach($presentdays as $presentday)
                    {
                        $logintimeamin = $presentday->amin;
                        $logintimeamout = $presentday->amout;
                        $logintimepmin = $presentday->pmin;
                        $logintimepmout = $presentday->pmout;
                        
                        if($getrate[0]->shiftid == 0 || $getrate[0]->shiftid == 1)
                        {
                            if($logintimeamin == null)
                            {
                                if($logintimeamout == null)
                                {
                                    $late =  strtotime($customtimeamout) - strtotime($customtimeamin);
                                    
                                    if($getrate[0]->shiftid == 1)
                                    {
                                        $noabsentdays+=1;
                                        
                                        $absentdeduction+= ($dailyrate);
                                    }
                                }else{
                                    $late =  strtotime($logintimeamout) - strtotime($customtimeamin);
                                    
                                    $dailynumofhours += $getrate[0]->hoursperday;
                                }
                                
                                if($late <= 0){
        
                                    $late = 0;
        
                                }else{
                                    
                                    $late = $late/60;
                                    
                                    if(isset($deductioncomputation))
                                    {
                                        if($deductioncomputation[0]->hours == '1')
                                        {
                                            $customlateminutes = ($deductioncomputation[0]->lateduration*60);
                                        }
                                        else{
                                            $customlateminutes = $deductioncomputation[0]->lateduration;
                                        }
                                        if($deductioncomputation[0]->timeallowancetype == 1)
                                        {
                                            $timeallowance = $deductioncomputation[0]->timeallowance;
                                        }else{
                                            $timeallowance = ($deductioncomputation[0]->timeallowance*60);
                                        }

                                        $finalminuteslate = $late-$timeallowance;
                                        
                                        if($deductioncomputation[0]->basisfixedamount == 1)
                                        {
                                            if($finalminuteslate>$customlateminutes)
                                            {
                                                $finalminuteslate = ($finalminuteslate - $customlateminutes);
                                                $latedeductionamount+=$deductioncomputation[0]->amount;
                                            }
                                            if($deductioncomputation[0]->deductfromrate == 1)
                                            {
                                                if($finalminuteslate>0)
                                                {
                                                    $minuteslate += $finalminuteslate;
                                                }
                                            }
                                        }else{
                                            if($finalminuteslate>$customlateminutes)
                                            {
                                                $finalminuteslate = ($finalminuteslate - $customlateminutes);
                                                $latedeductionamount+=($customlateminutes*($dailyrate*($deductioncomputation[0]->lateduration/100)));
                                            }
                                            if($deductioncomputation[0]->deductfromrate == 1)
                                            {
                                                if($finalminuteslate>0)
                                                {
                                                    $minuteslate += $finalminuteslate;
                                                }
                                            }
                                        }
                                        if($logintimeamout == null)
                                        {
                                            $minuteslatehalfday +=$finalminuteslate;
                                        }
                                    }
                                }
                                
                            }else{
                                
                                // if($logintimeamout == null)
                                // {                        logintimeamin                
                                    $late =  strtotime($logintimeamin) - strtotime($customtimeamin);
                                    
                                    $dailynumofhours += $getrate[0]->hoursperday;
                                // }else{
                                //     $late =  strtotime($logintimeamout) - strtotime($logintimeamin);
                                // }
                                // return $late;
                                if($late <= 0){
        
                                    $late = 0;
        
                                }else{
                                    $late = $late/60;
                                    
                                    if(isset($deductioncomputation))
                                    {
                                        if($deductioncomputation[0]->hours == '1')
                                        {
                                            $customlateminutes = ($deductioncomputation[0]->lateduration*60);
                                        }
                                        else{
                                            $customlateminutes = $deductioncomputation[0]->lateduration;
                                        }
                                        if($deductioncomputation[0]->timeallowancetype == 1)
                                        {
                                            $timeallowance = $deductioncomputation[0]->timeallowance;
                                        }else{
                                            $timeallowance = ($deductioncomputation[0]->timeallowance*60);
                                        }
                                        // return $timeallowance;
                                        $finalminuteslate = $late-$timeallowance;
                                        // return $finalminuteslate;
                                        if($deductioncomputation[0]->basisfixedamount == 1)
                                        {
                                            // return $deductioncomputation[0]->amount;
                                            if($finalminuteslate>$customlateminutes)
                                            {
                                                $finalminuteslate = ($finalminuteslate - $customlateminutes);
                                                $latedeductionamount+=$deductioncomputation[0]->amount;
                                            }
                                            
                                            if($deductioncomputation[0]->deductfromrate == 1)
                                            {
                                                if($finalminuteslate>0)
                                                {
                                                    $minuteslate += $finalminuteslate;
                                                }
                                            }
                                            
                                        }else{
                                            // return $customlateminutes;
                                            if($finalminuteslate>$customlateminutes)
                                            {
                                                $finalminuteslate = ($finalminuteslate - $customlateminutes);
                                                $latedeductionamount+=($customlateminutes*($dailyrate*($deductioncomputation[0]->lateduration/100)));
                                            }
                                            
                                            if($deductioncomputation[0]->deductfromrate == 1)
                                            {
                                                if($finalminuteslate>0)
                                                {
                                                    $minuteslate += $finalminuteslate;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if($getrate[0]->shiftid == 0 || $getrate[0]->shiftid == 2)
                        {
                            // return $logintimepmout;
                            if($logintimepmin == null)
                            {
                                if($logintimepmout == null)
                                {
                                    if(date('h:i:s')>=$customtimepmout)
                                    {
                                        $late =  strtotime($customtimepmout) - strtotime($customtimepmin);
                                    }
                                    else{
                                        $late = 0;
                                    }
                                    
                                    if($getrate[0]->shiftid == 2)
                                    {
                                        $noabsentdays+=1;
                                        
                                        $absentdeduction+= ($dailyrate);
                                    }
                                    
                                }else{
                                    $late =  strtotime($logintimepmout) - strtotime($customtimepmin);

                                    $dailynumofhours += $getrate[0]->hoursperday;
                                }
                                
                                if($late <= 0){
        
                                    $late = 0;
        
                                }else{
                                    
                                    $late = $late/60;
                                    
                                    if(isset($deductioncomputation))
                                    {
                                        if($deductioncomputation[0]->hours == '1')
                                        {
                                            $customlateminutes = ($deductioncomputation[0]->lateduration*60);
                                        }
                                        else{
                                            $customlateminutes = $deductioncomputation[0]->lateduration;
                                        }
                                        if($deductioncomputation[0]->timeallowancetype == 1)
                                        {
                                            $timeallowance = $deductioncomputation[0]->timeallowance;
                                        }else{
                                            $timeallowance = ($deductioncomputation[0]->timeallowance*60);
                                        }

                                        $finalminuteslate = $late-$timeallowance;
                                        
                                        if($deductioncomputation[0]->basisfixedamount == 1)
                                        {
                                            if($finalminuteslate>$customlateminutes)
                                            {
                                                $finalminuteslate = ($finalminuteslate - $customlateminutes);
                                                $latedeductionamount+=$deductioncomputation[0]->amount;
                                            }
                                            if($deductioncomputation[0]->deductfromrate == 1)
                                            {
                                                if($finalminuteslate>0)
                                                {
                                                    $minuteslate += $finalminuteslate;
                                                }
                                            }
                                        }else{
                                            if($finalminuteslate>$customlateminutes)
                                            {
                                                $finalminuteslate = ($finalminuteslate - $customlateminutes);
                                                $latedeductionamount+=($customlateminutes*($dailyrate*($deductioncomputation[0]->lateduration/100)));
                                            }
                                            if($deductioncomputation[0]->deductfromrate == 1)
                                            {
                                                if($finalminuteslate>0)
                                                {
                                                    $minuteslate += $finalminuteslate;
                                                }
                                            }
                                        }
                                        
                                        if($logintimeamout == null)
                                        {
                                            $minuteslatehalfday +=$finalminuteslate;
                                        }
                                    }
                                }
                                
                            }else{
                                
                                // if($logintimepmout == null)
                                // {                                 logintimepmin       
                                    $late =  strtotime($logintimepmin) - strtotime($customtimepmin);
                                    
                                    $dailynumofhours += $getrate[0]->hoursperday;
                                // }else{
                                //     $late =  strtotime($logintimepmout) - strtotime($logintimepmin);
                                // }
                                
                                
                                if($late <= 0){
        
                                    $late = 0;
        
                                }else{
                                    $late = $late/60;
                                    
                                    if(isset($deductioncomputation))
                                    {
                                        if($deductioncomputation[0]->hours == '1')
                                        {
                                            $customlateminutes = ($deductioncomputation[0]->lateduration*60);
                                        }
                                        else{
                                            $customlateminutes = $deductioncomputation[0]->lateduration;
                                        }
                                        if($deductioncomputation[0]->timeallowancetype == 1)
                                        {
                                            $timeallowance = $deductioncomputation[0]->timeallowance;
                                        }else{
                                            $timeallowance = ($deductioncomputation[0]->timeallowance*60);
                                        }

                                        $finalminuteslate = $late-$timeallowance;

                                        if($deductioncomputation[0]->basisfixedamount == 1)
                                        {
                                            
                                            if($finalminuteslate>$customlateminutes)
                                            {
                                                $finalminuteslate = ($finalminuteslate - $customlateminutes);
                                                $latedeductionamount+=$deductioncomputation[0]->amount;
                                            }
                                            if($deductioncomputation[0]->deductfromrate == 1)
                                            {
                                                if($finalminuteslate>0)
                                                {
                                                    $minuteslate += $finalminuteslate;
                                                }
                                            }

                                        }else{
                                            // return $customlateminutes;
                                            if($finalminuteslate>$customlateminutes)
                                            {
                                                $finalminuteslate = ($finalminuteslate - $customlateminutes);
                                                $latedeductionamount+=($customlateminutes*($dailyrate*($deductioncomputation[0]->lateduration/100)));
                                            }
                                            
                                            if($deductioncomputation[0]->deductfromrate == 1)
                                            {
                                                if($finalminuteslate>0)
                                                {
                                                    $minuteslate += $finalminuteslate;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                    // tried 'loginam = null; loginamout = null; basispercentage'
                    // TRIED 'loginam = null; loginamout = hasvalue; basispercentage'
                    // TRIED 'loginam = null; loginamout = hasvalue; basisamount'
                    // tried 'loginam = hasvalue; loginamout = null; basisamount'
                    // try 'loginam = hasvalue; loginamout = null; basispercentage'
                    // try 'loginam = hasvalue; loginamout = hasValue; basisamount'
                    // try 'loginam = hasvalue; loginamout = hasValue; basispercentage'
                    // T A R D I N E S S
                    // if($deductioncomputation[0]->basisfixedamount == 1)
                    // {
                    //     $latedeductionamount+=((($hourlyrate/60)*$minuteslate));
                    // }else{
                    //     // return $minuteslate;
                    //     // return $dailyrate*($deductioncomputation[0]->lateduration/100);
                    //     // $latedeductionamount+=($minuteslate*($dailyrate*($deductioncomputation[0]->lateduration/100)));
                    // }
                    
                // return $minuteslate;
                if($minuteslate>0)
                {
                    // return ($hourlyrate/60)*28;
                    $latedeductionamount+=($minuteslate*($hourlyrate/60));
                }
                

                $getsyid = DB::table('sy')
                    ->where('isactive','1')
                    ->first();
                // return collect($getsyid);
                $getholidays = DB::table('schoolcal')
                    ->leftJoin('schoolcaltype','schoolcal.type','=','schoolcaltype.id')
                    ->where('schoolcal.syid',$getsyid->id)
                    ->where('schoolcal.deleted','0')
                    ->where('schoolcaltype.type','1')
                    ->get();
                // return
                if(count($getholidays)>0)
                {
                    foreach($getholidays as $holiday)
                    {
                        
                        $holidays = array();

                        $holidaybegin = new DateTime($holiday->datefrom);

                        $holidayend = new DateTime($holiday->dateto);

                        $holidayend = $holidayend->modify( '+1 day' ); 
                        
                        $holidayintervalday = new DateInterval('P1D');

                        $holidaydaterange = new DatePeriod($holidaybegin, $holidayintervalday ,$holidayend);

                        foreach($holidaydaterange as $holidaydate){

                            array_push($holidays,$holidaydate->format("Y-m-d"));
                        }
                        if(count($holidays)>0)
                        {
                            foreach($holidays as $holidaydate)
                            {
                                if(in_array($holidaydate, $absentdays))
                                {
                                    //no work
                                    $holidaypay += ($dailyrate * ($holiday->ratepercentagenowork/100));
                                }
                                if(in_array($holidaydate, $presentdays))
                                {

                                    $holidaypay+=($dailyrate * ($holiday->ratepercentageworkon/100));
                                }
                            }
                        }

                    }
                }
                // return $absentdays;
                // if($minuteslatehalfday>0)
                // {
                //     $latedeductionamount+=($minuteslatehalfday*($hourlyrate/60));
                // }


                // ====================================================================================================================== standard allowance
    
                $standardallowancesmodel = HRAllowances::standardallowances($getdaterange[0]->datefrom, $getdaterange[0]->id, $employeeslist[0]->id);
                // return $standardallowancesmodel;

                $standardallowances = $standardallowancesmodel[0];

                $standardallowancesfullamount = $standardallowancesmodel[1];
                
                // ====================================================================================================================== other allowance

                $otherallowancesmodel = HRAllowances::otherallowances($getdaterange[0]->datefrom, $getdaterange[0]->id, $employeeslist[0]->id);
                // return $otherallowancesmodel;
                
                $otherallowances = $otherallowancesmodel[0];
    
                $otherallowancesfullamount = $otherallowancesmodel[1];
                
                // ====================================================================================================================== standard deductions

                // return $employeeslist[0]->id;
                // return $getdaterange[0]->datefrom;
                $standarddeductionsmodel = HRDeductions::standarddeductions($getdaterange[0]->datefrom, $getdaterange[0]->id, $employeeslist[0]->id);
                
                $standarddeductions = $standarddeductionsmodel[0];
                // return $standarddeductions;
                $standarddeductionsfullamount = $standarddeductionsmodel[1];

                    
                // ====================================================================================================================== other deductions

    
                $otherdeductionsmodel = HRDeductions::otherdeductions($getdaterange[0]->datefrom, $getdaterange[0]->id, $employeeslist[0]->id);
                
                // return $otherdeductionsmodel;
                $otherdeductions = $otherdeductionsmodel[0];
                $otherdeductionsfullamount = $otherdeductionsmodel[1];
                // ====================================================================================================================== salary summary
    
                $leaves = Db::table('employee_leaves')
                    ->join('hr_leaves','employee_leaves.leaveid','=','hr_leaves.id')
                    ->where('hr_leaves.deleted','0')
                    ->where('employee_leaves.payrolldone','0')
                    ->where('employee_leaves.status','approved')
                    ->where('employee_leaves.employeeid',$employeeslist[0]->id)
                    ->get();
                
                $leavesearn = 0;
                $leavesdeduct = 0;
                $leavesnumdays = 0;
                $leaveid = 0;
                
                $getdates = Db::table('payroll')
                    ->where('status','1')
                    ->first();

                $st_date = $getdates->datefrom;
                $ed_date = $getdates->dateto;
                $allworkingdates = range(strtotime($st_date), strtotime($ed_date),86400);
                $payrolldateperiod = array();

                foreach($allworkingdates as $allworkingdate)
                {   
                    array_push($payrolldateperiod, date('Y-m-d', $allworkingdate));
                }
                
                $checksched = Db::table('employee_basicsalaryinfo')
                   ->where('employeeid', $employeeslist[0]->id)
                   ->first();
                $empworkingdays = array();

				if(count(collect($checksched)) > 0)
				{
                    foreach ($payrolldateperiod as $payrolldateperiodvalue) {
                        if(strtolower(date('D',strtotime($payrolldateperiodvalue))) == 'mon')
                        {
                                if($checksched->mondays == 1)
                                {
                                    array_push($empworkingdays,date('Y-m-d',strtotime($payrolldateperiodvalue)));
                                }
                        } 
                        if(strtolower(date('D',strtotime($payrolldateperiodvalue))) == 'tue')
                        {
                                if($checksched->tuesdays == 1)
                                {
                                    array_push($empworkingdays,date('Y-m-d',strtotime($payrolldateperiodvalue)));
                                }
                        } 
                        if(strtolower(date('D',strtotime($payrolldateperiodvalue))) == 'wed')
                        {
                                if($checksched->wednesdays == 1)
                                {
                                    array_push($empworkingdays,date('Y-m-d',strtotime($payrolldateperiodvalue)));
                                }
                        } 
                        if(strtolower(date('D',strtotime($payrolldateperiodvalue))) == 'thu')
                        {
                                if($checksched->thursdays == 1)
                                {
                                    array_push($empworkingdays,date('Y-m-d',strtotime($payrolldateperiodvalue)));
                                }
                        } 
                        if(strtolower(date('D',strtotime($payrolldateperiodvalue))) == 'fri')
                        {
                                if($checksched->fridays == 1)
                                {
                                    array_push($empworkingdays,date('Y-m-d',strtotime($payrolldateperiodvalue)));
                                }
                        } 
                        if(strtolower(date('D',strtotime($payrolldateperiodvalue))) == 'sat')
                        {
                                if($checksched->saturdays == 1)
                                {
                                    array_push($empworkingdays,date('Y-m-d',strtotime($payrolldateperiodvalue)));
                                }
                        } 
                        if(strtolower(date('D',strtotime($payrolldateperiodvalue))) == 'sun')
                        {
                                if($checksched->sundays == 1)
                                {
                                    array_push($empworkingdays,date('Y-m-d',strtotime($payrolldateperiodvalue)));
                                }
                        } 
                    }
                
                    if(count($leaves) > 0){
                        
                        foreach($leaves as $leave){
    
                            $startdate = $leave->datefrom;
                            $enddate = $leave->dateto;
                            $allleavedates = range(strtotime($startdate), strtotime($enddate),86400);
                            $leavedatesperiod = array();
                            foreach($allleavedates as $allleavedate)
                            {   
                                array_push($leavedatesperiod, date('Y-m-d', $allleavedate));
                            }
                            
                            $daysleave = array();
                            foreach ($leavedatesperiod as $leavedatesperiod) {
                                if(strtolower(date('D',strtotime($leavedatesperiod))) == 'mon')
                                {
                                     if($checksched->mondays == 1)
                                     {
                                         array_push($daysleave,date('Y-m-d',strtotime($leavedatesperiod)));
                                     }
                                } 
                                if(strtolower(date('D',strtotime($leavedatesperiod))) == 'tue')
                                {
                                     if($checksched->tuesdays == 1)
                                     {
                                         array_push($daysleave,date('Y-m-d',strtotime($leavedatesperiod)));
                                     }
                                } 
                                if(strtolower(date('D',strtotime($leavedatesperiod))) == 'wed')
                                {
                                     if($checksched->wednesdays == 1)
                                     {
                                         array_push($daysleave,date('Y-m-d',strtotime($leavedatesperiod)));
                                     }
                                } 
                                if(strtolower(date('D',strtotime($leavedatesperiod))) == 'thu')
                                {
                                     if($checksched->thursdays == 1)
                                     {
                                         array_push($daysleave,date('Y-m-d',strtotime($leavedatesperiod)));
                                     }
                                } 
                                if(strtolower(date('D',strtotime($leavedatesperiod))) == 'fri')
                                {
                                     if($checksched->fridays == 1)
                                     {
                                         array_push($daysleave,date('Y-m-d',strtotime($leavedatesperiod)));
                                     }
                                } 
                                if(strtolower(date('D',strtotime($leavedatesperiod))) == 'sat')
                                {
                                     if($checksched->saturdays == 1)
                                     {
                                         array_push($daysleave,date('Y-m-d',strtotime($leavedatesperiod)));
                                     }
                                } 
                                if(strtolower(date('D',strtotime($leavedatesperiod))) == 'sun')
                                {
                                     if($checksched->sundays == 1)
                                     {
                                         array_push($daysleave,date('Y-m-d',strtotime($leavedatesperiod)));
                                     }
                                } 
                            }
                            
                            $leaveid = $leave->id;
    
                            // $datediff  = strtotime($leave->dateto) - strtotime($leave->datefrom);
                            // $leavesnumdays += round($datediff / (60 * 60 * 24)) + 1;
        
                            // $beginleave = new DateTime($leave->datefrom);
        
                            // $endleave = new DateTime($leave->dateto);
        
                            // $endleave = $endleave->modify( '+1 day' ); 
                            
                            // $intervalleave = new DateInterval('P1D');
        
                            // $daterangeleave = new DatePeriod($beginleave, $intervalleave ,$endleave);
        
                            $daysperiodleave = array();
    
        
                            foreach($daysleave as $dayleave){
        
                                // array_push($daysperiodleave,$dateleave);
                                if (in_array($dayleave, $empworkingdays)) {
                                    $leavesnumdays+=1;
                                    array_push($daysperiodleave, $dayleave);
                                }
        
                            }
                            
                            $daysperiodholiday = array();
        
                            foreach ($getholidays as $getholiday) {
        
                                $beginholiday = new DateTime($getholiday->datefrom);
        
                                $endholiday = new DateTime($getholiday->dateto);
        
                                $endholiday = $endholiday->modify( '+1 day' ); 
                                
                                $intervalholiday = new DateInterval('P1D');
        
                                $daterangeholiday = new DatePeriod($beginholiday, $intervalholiday ,$endholiday);
        
        
                                foreach($daterangeholiday as $dateholiday){
        
                                    array_push($daysperiodholiday,$dateholiday->format("Y-m-d"));
        
                                }
        
                            }
    
                            $numdaysholidays = 0;
    
                            $payholidaydays = 0;
                            foreach($daysperiodleave as $dayperiodleave){
    
                                if(in_array($dayperiodleave,$daysperiodholiday)){
                                    $numdaysholidays+=1;
                                    $payholidaydays+=($dailyrate* $getholiday->ratepercentagenowork)/100;
                                    // $payholidaydays +=($dailyrate* $getholiday->ratepercentagenowork)/100;
                                   
                                }
                                
                                $pos = array_search($dayperiodleave, $absentdays);
    
                                unset($absentdaysarray[$pos]);
    
                            }
                            
                            $getpay = DB::table('hr_leaves')
                                ->where('id',$leave->id)
                                ->where('deleted','0')
                                ->get();
                                
                            if(count($getpay) == 0){
                                $leavesdeduct+=($dailyrate*$leavesnumdays);
                            }else{
                                if($getpay[0]->withpay == 0){
                                    // $leavesdeduct+=($dailyrate*$leavesnumdays);
                                }else{
                                    $leavesearn+=($dailyrate*$leavesnumdays);
                                }
                            }
                            
                            if($numdaysholidays>0){
                                $leavesearn+=$payholidaydays;
                            }
                          
                        }
                    }
                }
                
                // ====================================================================================================================== overtime

                $holidayovertimepay = 0;

                $overtimesalary = 0;

                $dailyovertimehours = 0;

                $overtimes = Db::table('employee_overtime')
                    ->where('employee_overtime.deleted','0')
                    ->where('employee_overtime.payrolldone','0')
                    ->where('employee_overtime.status','approved')
                    ->where('employee_overtime.employeeid',$employeeslist[0]->id)
                    ->get();
// return $getholidays;
                if(count($overtimes) > 0){
                    
                    $daysperiodholiday = array();
    
                    foreach ($getholidays as $getholiday) {

                        $beginholiday = new DateTime($getholiday->datefrom);

                        $endholiday = new DateTime($getholiday->dateto);

                        $endholiday = $endholiday->modify( '+1 day' ); 
                        
                        $intervalholiday = new DateInterval('P1D');

                        $daterangeholiday = new DatePeriod($beginholiday, $intervalholiday ,$endholiday);


                        foreach($daterangeholiday as $dateholiday){

                            array_push($daysperiodholiday,$dateholiday->format("Y-m-d"));

                        }

                    }

                    foreach($overtimes as $overtime){

                        $daysovertimelist = array();

                        $beginovertime = new DateTime($overtime->datefrom);

                        $endovertime = new DateTime($overtime->dateto);

                        $endovertime = $endovertime->modify( '+1 day' ); 
                        
                        $intervalovertime = new DateInterval('P1D');

                        $daterangeovertime = new DatePeriod($beginovertime, $intervalovertime ,$endovertime);

                        foreach($daterangeovertime as $dateovertime){
                            array_push($daysovertimelist,$dateovertime->format("Y-m-d"));
                        }
                        // return 'ada';
                        // return $daterangeovertime;
                        // return $hourlyrate;
                        foreach($daysovertimelist as $dayovertime){
                            if(in_array($dayovertime,$daysperiodholiday)){
                                
                                $holidayovertimepay+=(($hourlyrate* $getholiday->workon)/100)*$overtime->numofhours;
                                
                            }else{
                                $overtimesalary+=$hourlyrate*$overtime->numofhours;
                            }
                        }
                    }
                }
                // return $dailynumofhours;
                
                // ====================================================================================================================== salary summary
                if(count($getrate) == 0){
                    $basicpay = 0;
        
                    $ratetype = "";
    
                    $projectbasedtype = "";
                
                    $attendancesalary = 0;
    
                    $overtimesalary = 0;
    
                    // $holidaypay = 0;
                    // $holidayovertimepay = 0;
    
                }else{
                    
                    $basicpay = ($getrate[0]->amount);
        
                    $ratetype = $getrate[0]->type;
    
                    $projectbasedtype = $getrate[0]->projectbasedtype;
                    if(strtolower($getrate[0]->type) == 'monthly'){
                        // $attendancesalary = $permonthhalfsalary-$absentdeduction;
                        // if($attendancesalary == 0 && count($presentdays) && $minuteslate == 0)
                        // {
                        //     $attendancesalary += $dailyrate;
                        //     $absentdeduction-=$dailyrate;
                        // }
                        $attendancesalary = count($presentdays)*$dailyrate;
                        $absentdeduction = $permonthhalfsalary - $attendancesalary;
                        $attendancesalary -= $latedeductionamount;
                    }else{
                        $attendancesalary = ($dailynumofhours*$hourlyrate);
                    }
                    
                }
                if($attendancesalary < 0){
                    $attendancesalary = 0;
                }
                $totalearnings = ($attendancesalary + $overtimesalary  + $holidaypay + $holidayovertimepay + $leavesearn + $standardallowancesfullamount + $otherallowancesfullamount);
                
                $totaldeductions = ($standarddeductionsfullamount + $otherdeductionsfullamount + $leavesdeduct + $latedeductionamount);
    
                $netsalary = ($totalearnings - $totaldeductions);
                
                $employeesalaryinfo = array();
                
                array_push($employeesalaryinfo, (object)array(
                    'employee_info'         => $employeeslist,
                    'basicpay'              => number_format($basicpay, 2, '.', ','),
                    'ratetype'              => $ratetype,
                    'projectratetype'       => $projectbasedtype,
                    'allowancestandards'    =>  $standardallowances,
                    'allowanceothers'       =>  $otherallowances,
                    'deductionstandards'    =>  $standarddeductions,
                    'deductionothers'       =>  $otherdeductions,
                    'attendancesalary'      => number_format($attendancesalary, 2, '.', ','),
                    'numberofabsent'        => $noabsentdays,
                    'absentdeduction'       => number_format($absentdeduction, 2, '.', ','),
                    'overtimepay'           => number_format($overtimesalary, 2, '.', ','),
                    'latedeductions'        => number_format($latedeductionamount, 2, '.', ','),
                    'holidaypay'            => number_format($holidaypay, 2, '.', ','),
                    'holidayovertimepay'    => number_format($holidayovertimepay, 2, '.', ','),
                    'leaves'                => $leaves,
                    'leaveid'               => $leaveid,
                    'leavesnumdays'         => $leavesnumdays,
                    'leavesearn'            => number_format($leavesearn, 2, '.', ','),
                    'leavesdeduct'          => number_format($leavesdeduct, 2, '.', ','),
                    'totalearnings'         => number_format($totalearnings, 2, '.', ','),
                    'totaldeductions'       => number_format($totaldeductions, 2, '.', ','),
                    'netsalary'             => number_format($netsalary, 2, '.', ','),
                    'netsalarystring'       => ucwords(Conversion::make($netsalary,' pesos'))
                ));
                set_time_limit(3000);
                return view('hr.employeesalary')
                    ->with('employees', $employeeslist)
                    ->with('payrolldate',$payrolldate)
                    ->with('filteremployees','all')
                    ->with('existsinhistory',$existsinhistory)
                    ->with('firstemployee',$employeesalaryinfo);

            }else{

                return view('hr.employeesalary')
                    ->with('employees', $employeeslist)
                    ->with('payrolldate',$payrolldate)
                    ->with('filteremployees','all')
                    ->with('existsinhistory',$existsinhistory);

            }

        }
        elseif($id == 'changedate'){
            
            $date = explode(' - ',$request->get('payrolldate'));

            $datefrom = explode('-',$date[0]);
            
            $dateto = explode('-',$date[1]);
            
            Db::table('payroll')
                ->where('id',$request->get('payrolldateid'))
                ->update([
                    'datefrom'  => date('Y-m-d', strtotime($datefrom[2].'-'.$datefrom[0].'-'.$datefrom[1])),
                    'dateto'    => date('Y-m-d', strtotime($dateto[2].'-'.$dateto[0].'-'.$dateto[1])),
                    'updatedby' => $my_id->id,
                    'updatedon' => date('Y-m-d H:i:s')
                ]);

            return back();

        }
        elseif($id == 'newdate'){
            
            $date = explode(' - ',$request->get('newpayrolldate'));
            
            Db::table('payroll')
                ->where('id',$request->get('oldpayrolldateid'))
                ->update([
                    'status'  => 0,
                    'updatedby' => $my_id->id,
                    'updatedon' => date('Y-m-d H:i:s')
                ]);
            $datefrom = explode('-',$date[0]);
            
            $dateto = explode('-',$date[1]);
            Db::table('payroll')
                ->insert([
                    'datefrom'  => date('Y-m-d', strtotime($datefrom[2].'-'.$datefrom[0].'-'.$datefrom[1])),
                    'dateto'    => date('Y-m-d', strtotime($dateto[2].'-'.$dateto[0].'-'.$dateto[1])),
                    'createdby' => $my_id->id,
                    'createdon' => date('Y-m-d H:i:s')
                ]);

            return back();

        } 
        
    }
    public function payrollgenerateslip(Request $request)
    {
        // return $request->all();
        date_default_timezone_set('Asia/Manila');

        $payrollinfo            = DB::table('payroll_history')
                                    ->where('employeeid',$request->get('employeeid'))
                                    ->where('payrollid',$request->get('payrolldateid'))
                                    ->first();
        // return $payrollinfo;
        if($payrollinfo->isreleased == 0){

                                DB::table('payroll_history')
                                    ->where('employeeid',$request->get('employeeid'))
                                    ->where('payrollid',$request->get('payrolldateid'))
                                    ->update([
                                        'isreleased'    => '1',
                                        'datereleased'  =>  date('Y-m-d H:i:s')
                                    ]);

        }
        

        $employeesalaryinfo     = array();

        $employeeinfo           = DB::table('teacher')
                                    ->select(
                                        'teacher.lastname',
                                        'teacher.firstname',
                                        'teacher.middlename',
                                        'teacher.suffix',
                                        'usertype.utype',
                                        'hr_school_department.department'
                                    )
                                    ->join('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                                    ->join('usertype','teacher.usertypeid','=','usertype.id')
                                    ->leftJoin('hr_school_department','usertype.departmentid','=','hr_school_department.id')
                                    ->where('teacher.id',$request->get('employeeid'))
                                    ->first();
        
        $payrollinfo            = DB::table('payroll_history')
                                    ->where('employeeid',$request->get('employeeid'))
                                    ->where('payrollid',$request->get('payrolldateid'))
                                    ->first();

        $payrollinfo->netpaystring =  ucwords(Conversion::make($payrollinfo->netpay,' pesos'));
        $payrollinfo->datereleased =  date('F d, Y h:i:s A',strtotime($payrollinfo->datereleased));

        $standardallowances     = array();
        $otherallowances        = array();

        $standarddeductions     = array();
        $otherdeductions        = array();

        $leavesearned           = array();
        $leavesdeducted         = array();
                                    
                            
        $payrolldetails         = DB::table('payroll_historydetail')
                                    ->where('headerid', $payrollinfo->id)
                                    ->get();
                                    
        foreach($payrolldetails as $payrolldetail){

            if($payrolldetail->type == 'standardallowance'){

                array_push($standardallowances, $payrolldetail);

            }
            elseif($payrolldetail->type == 'otherallowance'){

                array_push($otherallowances, $payrolldetail);

            }
            elseif($payrolldetail->type == 'standarddeduction'){

                array_push($standarddeductions, $payrolldetail);

            }
            elseif($payrolldetail->type == 'otherdeduction'){

                array_push($otherdeductions, $payrolldetail);

            }
            elseif($payrolldetail->type == 'earnedleave'){

                array_push($leavesearned, $payrolldetail);

            }
            elseif($payrolldetail->type == 'deductedleave'){

                array_push($leavesdeducted, $payrolldetail);

            }

        }
        // =========================================== Otherallowances
            
            $employeeotherallowances = Db::table('employee_allowanceother')
                                        ->select(
                                            'employee_allowanceother.id',
                                            'employee_allowanceother.description',
                                            'employee_allowanceother.amount',
                                            'employee_allowanceother.term'
                                        )
                                        ->where('employee_allowanceother.employeeid',$request->get('employeeid'))
                                        ->where('employee_allowanceother.paid','0')
                                        ->where('employee_allowanceother.deleted','0')
                                        ->get();
                                        
            if(count($employeeotherallowances) > 0){

                foreach($employeeotherallowances as $employeeotherallowance){
                    
                    $totalpaidotherallowance = 0;

                    $gethistorydetailotherallowances = DB::table('payroll_history')
                                                    ->select('payroll_historydetail.amount')
                                                    ->join('payroll_historydetail','payroll_history.id','=','payroll_historydetail.headerid')
                                                    ->where('payroll_historydetail.allowanceid',$employeeotherallowance->id)
                                                    ->where('payroll_history.employeeid',$request->get('employeeid'))
                                                    ->where('payroll_historydetail.type','otherallowance')
                                                    ->get();
                                                    
                    if(count($gethistorydetailotherallowances) > 0){

                        foreach($gethistorydetailotherallowances as $gethistorydetailotherallowance){

                            $totalpaidotherallowance += $gethistorydetailotherallowance->amount;

                        }

                    }

                    if($totalpaidotherallowance == $employeeotherallowance->amount){
                        
                        DB::table('employee_allowanceother')
                            ->where('id',$employeeotherallowance->id)
                            ->where('employeeid',$request->get('employeeid'))
                            ->update([
                                'paid'  => 1
                            ]);

                    }

                }


            }

        // ===========================================
        // =========================================== OtherDeductions
            
            $employeeotherdeductions = Db::table('employee_deductionother')
                                        ->select(
                                            'employee_deductionother.id',
                                            'employee_deductionother.description',
                                            'employee_deductionother.amount',
                                            'employee_deductionother.term'
                                        )
                                        ->where('employee_deductionother.employeeid',$request->get('employeeid'))
                                        ->where('employee_deductionother.paid','0')
                                        ->where('employee_deductionother.deleted','0')
                                        ->get();
                                        
            if(count($employeeotherdeductions) > 0){

                foreach($employeeotherdeductions as $employeeotherdeduction){
                    
                    $totalpaidotherdeduction = 0;

                    $gethistorydetailotherdeductions = DB::table('payroll_history')
                                                    ->select('payroll_historydetail.amount')
                                                    ->join('payroll_historydetail','payroll_history.id','=','payroll_historydetail.headerid')
                                                    ->where('payroll_historydetail.deductionid',$employeeotherdeduction->id)
                                                    ->where('payroll_history.employeeid',$request->get('employeeid'))
                                                    ->where('payroll_historydetail.type','otherdeduction')
                                                    ->get();
                                                    
                    if(count($gethistorydetailotherdeductions) > 0){

                        foreach($gethistorydetailotherdeductions as $gethistorydetailotherdeduction){

                            $totalpaidotherdeduction += $gethistorydetailotherdeduction->amount;

                        }

                    }

                    if($totalpaidotherdeduction == $employeeotherdeduction->amount){
                        
                        DB::table('employee_deductionother')
                            ->where('id',$employeeotherdeduction->id)
                            ->where('employeeid',$request->get('employeeid'))
                            ->update([
                                'paid'  => 1
                            ]);

                    }

                }


            }

        // ===========================================
        
        array_push($employeesalaryinfo, (object)array(
            'employeeinfo'          => $employeeinfo,
            'payrollinfo'           => $payrollinfo,
            'standardallowances'    => $standardallowances,
            'otherallowances'       => $otherallowances,
            'standarddeductions'    => $standarddeductions,
            'otherdeductions'       => $otherdeductions,
            'leavesearned'          => $leavesearned,
            'leavesdeducted'        => $leavesdeducted
        ));
        
        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->get();
        
        // $GLOBALS['bodyHeight'] = 0;

        $getdaterange               = DB::table('payroll')
                                        ->where('status','1')
                                        ->get();

        foreach($getdaterange as $daterange){

            $daterange->datefrom = date('M d, Y', strtotime($daterange->datefrom));
            $daterange->dateto = date('M d, Y', strtotime($daterange->dateto));

        }
        
        $preparedby                 = DB::table('teacher')
                                    ->where('teacher.userid', auth()->user()->id)
                                    ->first();

        $finance                    = Db::table('teacher')
                                    ->where('usertypeid','15')
                                    ->where('isactive','1')
                                    ->get();
        $pdf = PDF::loadview('hr/pdf/payslip',compact('employeesalaryinfo','schoolinfo','getdaterange','preparedby','finance'))->setPaper('8.5x11');
        return $pdf->stream('Payslip - '.$getdaterange[0]->datefrom.'-'.$getdaterange[0]->dateto.'.pdf');
    }
    public function printfilteredsalary($id,Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');
        
        $my_id = DB::table('teacher')
            ->where('userid',auth()->user()->id)
            ->where('isactive','1')
            ->first();
        // ====================================================================================================================== payrollid

        $getdaterange = Db::table('payroll')
            ->where('status','1')
            ->get();

        // ====================================================================================================================== get employee info

        if($request->get('salarytype') == 'all'){
            $employeeslist = Db::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix',
                    'teacher.picurl',
                    'usertype.utype',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.departmentid'
                    )
                ->join('usertype','teacher.usertypeid','=','usertype.id')
                ->join('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                ->where('usertype.utype','!=','PARENT')
                ->where('usertype.utype','!=','STUDENT')
                ->where('isactive','1')
                ->get();

        }elseif($request->get('salarytype') == 'Hourly'){
            $employeeslist = Db::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix',
                    'teacher.picurl',
                    'usertype.utype',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.departmentid'               
                    )
                ->join('usertype','teacher.usertypeid','=','usertype.id')
                ->join('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')                      
                ->join('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                ->where('usertype.utype','!=','PARENT')
                ->where('usertype.utype','!=','STUDENT')
                ->where('employee_basistype.type','Hourly')
                ->where('isactive','1')
                ->get();


        }elseif($request->get('salarytype') == 'Daily'){
            $employeeslist = Db::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix',
                    'teacher.picurl',
                    'usertype.utype',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.departmentid' 
                    )
                ->join('usertype','teacher.usertypeid','=','usertype.id')
                ->join('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')      
                ->join('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                ->where('usertype.utype','!=','PARENT')
                ->where('usertype.utype','!=','STUDENT')
                ->where('employee_basistype.type','Daily')
                ->where('isactive','1')
                ->get();


        }elseif($request->get('salarytype') == 'Monthly'){
            $employeeslist = Db::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix',
                    'teacher.picurl',
                    'usertype.utype',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.departmentid'
                    )
                ->join('usertype','teacher.usertypeid','=','usertype.id')
                ->join('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                ->join('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                ->where('usertype.utype','!=','PARENT')
                ->where('usertype.utype','!=','STUDENT')
                ->where('employee_basistype.type','Monthly')
                ->where('isactive','1')
                ->get();


        }elseif($request->get('salarytype') == 'Project'){
            $employeeslist = Db::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix',
                    'teacher.picurl',
                    'usertype.utype',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.departmentid'
                    )
                ->join('usertype','teacher.usertypeid','=','usertype.id')
                ->join('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                ->join('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                ->where('usertype.utype','!=','PARENT')
                ->where('usertype.utype','!=','STUDENT')
                ->where('employee_basistype.type','Project')
                ->where('isactive','1')
                ->get();


        }
        // ====================================================================================================================== get payrollinfo
        
        $employeesalaryinfo = array();

        foreach($employeeslist as $employee){
            
            $payrollinfo                = DB::table('payroll_history')
                                        ->where('employeeid', $employee->id)
                                        ->where('payrollid',$getdaterange[0]->id)
                                        ->get();

            if(count($payrollinfo)> 0){

                $standardallowances     = array();
                $otherallowances        = array();
    
                $standarddeductions     = array();
                $otherdeductions        = array();

                $leavesearned           = array();
                $leavesdeducted         = array();
                                            
                                    
                $payrolldetails         = DB::table('payroll_historydetail')
                                            ->where('headerid', $payrollinfo[0]->id)
                                            ->get();
    
                foreach($payrolldetails as $payrolldetail){
    
                    if($payrolldetail->type == 'standardallowance'){
    
                        array_push($standardallowances, $payrolldetail);
    
                    }
                    elseif($payrolldetail->type == 'otherallowance'){
    
                        array_push($otherallowances, $payrolldetail);
    
                    }
                    elseif($payrolldetail->type == 'standarddeduction'){
    
                        array_push($standarddeductions, $payrolldetail);
    
                    }
                    elseif($payrolldetail->type == 'otherdeduction'){
    
                        array_push($otherdeductions, $payrolldetail);
    
                    }
                    elseif($payrolldetail->type == 'earnedleave'){
        
                        array_push($leavesearned, $payrolldetail);
        
                    }
                    elseif($payrolldetail->type == 'deductedleave'){
        
                        array_push($leavesdeducted, $payrolldetail);
        
                    }
    
                }

                array_push($employeesalaryinfo, (object)array(
                    'employeeinfo'          => $employee,
                    'payrollinfo'           => $payrollinfo,
                    'standardallowances'    => $standardallowances,
                    'otherallowances'       => $otherallowances,
                    'standarddeductions'    => $standarddeductions,
                    'otherdeductions'       => $otherdeductions,
                    'leavesearned'          => $leavesearned,
                    'leavesdeducted'        => $leavesdeducted
                ));

            }

        }

        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();
        
        $salarytype                         = $request->get('salarytype');

        $preparedby                 = DB::table('teacher')
                                    ->where('teacher.userid', auth()->user()->id)
                                    ->first();

        $finance                    = Db::table('teacher')
                                    ->where('usertypeid','15')
                                    ->where('isactive','1')
                                    ->get();

        foreach($getdaterange as $daterange){

            $daterange->stringdatefrom = date('F d, Y', strtotime($daterange->datefrom));
            $daterange->stringdateto = date('F d, Y', strtotime($daterange->dateto));

        }

        $prepareddate                        = date('F d, Y h:i:s A');
        // return $employeesalaryinfo;
        
        if($request->get('displaytype') == 'individual'){
            $pdf = PDF::loadview('hr/pdf/multiplepayslip',compact('employeesalaryinfo','schoolinfo','getdaterange','prepareddate','salarytype','my_id','preparedby','finance'))->setPaper('letter','portrait');
    
            return $pdf->stream('Payslip.pdf');
        }else{
            $pdf = PDF::loadview('hr/pdf/paysliptablesummary',compact('employeesalaryinfo','schoolinfo','getdaterange','prepareddate','salarytype','preparedby'))->setPaper('letter','portrait');
    
            return $pdf->stream('Payslip Summary.pdf');
        }
        // return $employeesalaryinfo;

    }
    // public function payrollhistory(Request $request)
    // {
    //     date_default_timezone_set('Asia/Manila');
    //     $defaultview = array();
        
    //     // return $request->get('changepayrollhistory');
    //     // return $request->all();
    //     if($request->get('changepayrollhistory') == true){
    //             $getdaterange = Db::table('payroll')
    //                 ->orderByDesc('id')
    //                 ->get();
    //             // foreach()
    //             // return $request->get('payrollid');
    //             foreach($getdaterange as $daterange){
    //                 $daterange->datefrom = date('M d, Y', strtotime($daterange->datefrom));
    //                 $daterange->dateto = date('M d, Y', strtotime($daterange->dateto));  
    //                 if($daterange->id == $request->get('payrollid')){
    //                     $daterange->selected = 1;  
    //                 }else{
    //                     $daterange->selected = 0;  
    //                 }       
    //             }
    //             // return $getdaterange;
    //             $history = Db::table('payroll_history')
    //                 ->select(
    //                     'payroll_history.id',
    //                     'payroll_history.employeeid',
    //                     'payroll_history.payrollid',
    //                     'payroll_history.payrolldatefrom',
    //                     'payroll_history.payrolldateto',
    //                     'payroll_history.netpay',
    //                     'payroll_history.basicpay',
    //                     'payroll_history.ratetype',
    //                     'payroll_history.attendancesalary',
    //                     'payroll_history.tardinessamount',
    //                     'payroll_history.totalearnings',
    //                     'payroll_history.totaldeductions',
    //                     'payroll_history.overtimepay',
    //                     'payroll_history.holidaypay',
    //                     'payroll_history.holidayovertimepay',
    //                     'payroll_history.isreleased',
    //                     'teacher.lastname',
    //                     'teacher.firstname',
    //                     'teacher.middlename',
    //                     'teacher.suffix',
    //                     'usertype.utype as designation'
    //                     )
    //                 ->join('teacher', 'payroll_history.employeeid','=','teacher.id')
    //                 // ->join('usertype', 'teacher.usertypeid','=','usertype.id')
    //                 ->join('usertype', 'teacher.usertypeid','=','usertype.id')
    //                 ->where('payrollid', $request->get('payrollid'))
    //                 // ->where('isreleased', '1')
    //                 ->get();
    //                     // return $history;
    //             foreach($history as $historydetail){
    //                 $getrange = DB::table('payroll')
    //                     ->where('id',$request->get('payrollid'))
    //                     ->get();
    //                 $countdaysworked = Db::table('teacherattendance')
    //                     ->where('teacher_id',$historydetail->employeeid)
    //                     ->whereBetween('tdate',[$getrange[0]->datefrom,$getrange[0]->dateto])
    //                     ->get();
    //                 $begindate = new DateTime($getrange[0]->datefrom);
        
    //                 $enddate = new DateTime($getrange[0]->dateto);

    //                 $enddate = $enddate->modify( '+1 day' ); 
                    
    //                 $intervaldate = new DateInterval('P1D');

    //                 $daterange = new DatePeriod($begindate, $intervaldate ,$enddate);

    //                 $daysperiod = array();

    //                 foreach($daterange as $date){

    //                     array_push($daysperiod,$date->format("Y-m-d"));

    //                 }
    //                 $countdaysabsent = count($daysperiod) - count($countdaysworked);
                    
    //                 $details = Db::table('payroll_historydetail')
    //                     ->where('headerid', $historydetail->id)
    //                     ->get();
    //                 // return $details;
    //                 $historydetail->payrolldatefrom = date('M d, Y', strtotime($historydetail->payrolldatefrom));
    //                 $historydetail->payrolldateto = date('M d, Y', strtotime($historydetail->payrolldateto));
    //                 $historydetail->totalearnings = number_format($historydetail->totalearnings,2,'.',',');
    //                 $historydetail->totaldeductions = number_format($historydetail->totaldeductions,2,'.',',');
    //                 $historydetail->netpay = $historydetail->netpay;
    //                 $historydetail->holidaypay = number_format($historydetail->holidaypay,2,'.',',');
    //                 array_push($defaultview,(object)array(
    //                     'history'           => $historydetail,
    //                     'historydetail'     => $details,
    //                     'daysworked'        => count($countdaysworked),
    //                     'daysabsent'        => $countdaysabsent
    //                 ));
    //             }
    //     }else{
    //         $getdaterange = Db::table('payroll')
    //             ->orderByDesc('id')
    //             ->get();
            
    //         // foreach()
    //         foreach($getdaterange as $daterange){
    //             $daterange->datefrom = date('M d, Y', strtotime($daterange->datefrom));
    //             $daterange->dateto = date('M d, Y', strtotime($daterange->dateto));  
    //             if($daterange->status == 1){
    //                 $daterange->selected = 1;  
    //             }else{
    //                 $daterange->selected = 0; 
    //             }       
    //         }
    //         if(count($getdaterange)>0){
    //             $history = Db::table('payroll_history')
    //                 ->select(
    //                     'payroll_history.id',
    //                     'payroll_history.employeeid',
    //                     'payroll_history.payrollid',
    //                     'payroll_history.payrolldatefrom',
    //                     'payroll_history.payrolldateto',
    //                     'payroll_history.netpay',
    //                     'payroll_history.basicpay',
    //                     'payroll_history.ratetype',
    //                     'payroll_history.attendancesalary',
    //                     'payroll_history.numofdaysabsent',
    //                     'payroll_history.totalearnings',
    //                     'payroll_history.tardinessamount',
    //                     'payroll_history.totaldeductions',
    //                     'payroll_history.overtimepay',
    //                     'payroll_history.holidaypay',
    //                     'payroll_history.holidayovertimepay',
    //                     'payroll_history.isreleased',
    //                     'teacher.lastname',
    //                     'teacher.firstname',
    //                     'teacher.middlename',
    //                     'teacher.suffix',
    //                     'usertype.utype as designation'
    //                     )
    //                 ->join('teacher', 'payroll_history.employeeid','=','teacher.id')
    //                 ->join('usertype', 'teacher.usertypeid','=','usertype.id')
    //                 ->where('payrollid', $getdaterange[0]->id)
    //                 // ->where('isreleased', '1')
    //                 ->get();
                    
    //             // return $history;
    //             // return $history;
    //             foreach($history as $historydetail){
    //                 $getrange = DB::table('payroll')
    //                     ->where('id',$getdaterange[0]->id)
    //                     ->get();
    //                 $countdaysworked = Db::table('teacherattendance')
    //                     ->where('teacher_id',$historydetail->employeeid)
    //                     ->whereBetween('tdate',[$getrange[0]->datefrom,$getrange[0]->dateto])
    //                     ->get();
    //                 $begindate = new DateTime($getrange[0]->datefrom);
        
    //                 $enddate = new DateTime($getrange[0]->dateto);

    //                 $enddate = $enddate->modify( '+1 day' ); 
                    
    //                 $intervaldate = new DateInterval('P1D');

    //                 $daterange = new DatePeriod($begindate, $intervaldate ,$enddate);

    //                 $daysperiod = array();

    //                 foreach($daterange as $date){

    //                     array_push($daysperiod,$date->format("Y-m-d"));

    //                 }
    //                 // $countdaysabsent = count($daysperiod) - count($countdaysworked);
                    
    //                 $details = Db::table('payroll_historydetail')
    //                     ->where('headerid', $historydetail->id)
    //                     ->get();
                        
    //                 $historydetail->payrolldatefrom = date('M d, Y', strtotime($historydetail->payrolldatefrom));
    //                 $historydetail->payrolldateto = date('M d, Y', strtotime($historydetail->payrolldateto));
    //                 $historydetail->totalearnings = number_format($historydetail->totalearnings,2,'.',',');
    //                 $historydetail->totaldeductions = number_format($historydetail->totaldeductions,2,'.',',');
    //                 $historydetail->netpay = $historydetail->netpay;
    //                 $historydetail->holidaypay = number_format($historydetail->holidaypay,2,'.',',');
    //                 array_push($defaultview,(object)array(
    //                     'history'           => $historydetail,
    //                     'historydetail'     => $details,
    //                     'daysworked'        => count($countdaysworked),
    //                     'daysabsent'        => $historydetail->numofdaysabsent
    //                 ));
    //             }
    //         }
    //     }
        
    //     return view('hr.payrollhistory')
    //         ->with('getdaterange',$getdaterange)
    //         ->with('history',$defaultview);


    // }
    // public function printpayrollhistory($id,Request $request)
    // {
    //     // return $request->all();
    //     date_default_timezone_set('Asia/Manila');

    //     $id = Crypt::decrypt($id);

    //     $getpayrollhistoryinfo = array();

    //     if($id == 'individual'){
            
    //         $getpayrollhistory = Db::table('payroll_history')
    //             ->where('employeeid', $request->get('employeeid'))
    //             ->where('payrollid', $request->get('payrollid'))
    //             ->get();
            
    //         if($getpayrollhistory[0]->isreleased == 0){

    //             DB::table('payroll_history')
    //                 ->where('employeeid', $request->get('employeeid'))
    //                 ->where('payrollid', $request->get('payrollid'))
    //                 ->update([
    //                     'isreleased'    =>  1,
    //                     'datereleased'  =>  date('Y-m-d H:i:s')
    //                 ]);
            
    //             $getpayrollhistory = Db::table('payroll_history')
    //                 ->where('employeeid', $request->get('employeeid'))
    //                 ->where('payrollid', $request->get('payrollid'))
    //                 ->get();

    //         }
            
            
    //         foreach($getpayrollhistory as $payrollhistory){
    //             $getbasicinformation = Db::table('teacher')
    //                 ->where('id', $request->get('employeeid'))
    //                 ->where('isactive', 1)
    //                 ->first();
    //             // return $payrollhistory->payrolldatefrom;
    //             $payrollhistory->firstname          = $getbasicinformation->firstname;
    //             $payrollhistory->middlename         = $getbasicinformation->middlename;
    //             $payrollhistory->lastname           = $getbasicinformation->lastname;
    //             $payrollhistory->suffix             = $getbasicinformation->suffix;
    //             $payrollhistory->payrolldatefrom    = date('F d, Y',strtotime($payrollhistory->payrolldatefrom));
    //             $payrollhistory->payrolldateto      = date('F d, Y',strtotime($payrollhistory->payrolldateto));
    //             if($payrollhistory->datereleased == null){
    //                 $payrollhistory->datereleased      = "";
    //             }else{
                    
    //                 $payrollhistory->datereleased      = date('F d, Y',strtotime($payrollhistory->datereleased));
    //             }
                
    //             $gethistorydetail = Db::table('payroll_historydetail')
    //                 ->where('headerid',$payrollhistory->id)
    //                 ->get();
    //             // return $gethistorydetail;
    //             array_push($getpayrollhistoryinfo, (object)array(
    //                 'historyinfo'       => $payrollhistory,
    //                 'historydetail'     => $gethistorydetail
    //             ));
    //         }
            
    //         $schoolinfo = Db::table('schoolinfo')
    //             ->select(
    //                 'schoolinfo.schoolid',
    //                 'schoolinfo.schoolname',
    //                 'schoolinfo.authorized',
    //                 'refcitymun.citymunDesc as division',
    //                 'schoolinfo.district',
    //                 'schoolinfo.address',
    //                 'schoolinfo.picurl',
    //                 'refregion.regDesc as region'
    //             )
    //             ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
    //             ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
    //             ->first();

    //         $currentdateandtime = date('F d, Y h:i:s A');
    //         $preparedby = Db::table('teacher')
    //             ->where('userid', auth()->user()->id)
    //             ->first();
            
    //         $finance                  = Db::table('teacher')
    //                                 ->where('usertypeid','15')
    //                                 ->where('isactive','1')
    //                                 ->get();
    //                             // return $getpayrollhistoryinfo;
    //         $pdf = PDF::loadview('hr/pdf/payrollhistorypayslip',compact('getpayrollhistoryinfo','schoolinfo','currentdateandtime','preparedby','finance'))->setPaper('8.5x11');

    //         return $pdf->stream('Payroll History.pdf'); 
    //         // payrollhistorypayslip.blade.php
    //         // return $getpayrollhistoryinfo;
    //     }elseif($id == 'bypayrollperiod'){

    //         $histories = DB::table('payroll_history')
    //             ->select(
    //                 'payroll_history.id as historyid',
    //                 'payroll_history.netpay',
    //                 'payroll_history.payrollid',
    //                 'payroll_history.basicpay',
    //                 'payroll_history.ratetype',
    //                 'payroll_history.attendancesalary',
    //                 'payroll_history.tardinessamount',
    //                 'payroll_history.numofdaysabsent',
    //                 'payroll_history.absenttotalamount',
    //                 'payroll_history.totalearnings',
    //                 'payroll_history.totaldeductions',
    //                 'payroll_history.overtimepay',
    //                 'payroll_history.holidaypay',
    //                 'payroll_history.holidayovertimepay',
    //                 'payroll_history.isreleased',
    //                 'teacher.lastname',
    //                 'teacher.middlename',
    //                 'teacher.firstname',
    //                 'teacher.suffix',
    //                 'usertype.utype',
    //                 'employee_personalinfo.contactnum',
    //                 'employee_personalinfo.email',
    //                 'employee_basicsalaryinfo.amount',
    //                 'employee_basistype.type'
    //             )
    //             ->join('teacher', 'payroll_history.employeeid','teacher.id')
    //             ->leftJoin('employee_personalinfo', 'teacher.id','=','employee_personalinfo.employeeid')
    //             ->leftJoin('employee_accounts', 'teacher.id','=','employee_accounts.employeeid')
    //             ->leftJoin('employee_basicsalaryinfo', 'teacher.id','=','employee_basicsalaryinfo.employeeid')
    //             ->leftJoin('employee_basistype', 'employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
    //             ->leftJoin('usertype', 'teacher.usertypeid','=','usertype.id')
    //             ->where('payroll_history.payrollid', $request->get('payrollid'))
    //             // ->where('payroll_history.isreleased', '1')
    //             ->get();
    //         // return $histories;
    //         $allhistory = array();

    //         foreach($histories as $history){

    //             if($history->isreleased == 0){

    //                 DB::table('payroll_history')
    //                     // ->where('employeeid', $history->get('employeeid'))
    //                     ->where('payrollid', $history->payrollid)
    //                     ->update([
    //                         'isreleased'    =>  1,
    //                         'datereleased'  =>  date('Y-m-d H:i:s')
    //                     ]);

    //                 $history->isreleased = '1';
    //                 $history->datereleased = date('Y-m-d H:i:s');
    
    //             }

    //             $getstandarddeductions = Db::table('payroll_historydetail')
    //                 ->select(
    //                     'payroll_historydetail.description',
    //                     'payroll_historydetail.amount'
    //                 )
    //                 ->leftJoin('deduction_standard','payroll_historydetail.deductionid','=','deduction_standard.id')
    //                 ->where('payroll_historydetail.headerid', $history->historyid)
    //                 ->where('payroll_historydetail.type', 'standarddeduction')
    //                 ->get();


    //             $getotherdeductions = Db::table('payroll_historydetail')
    //                 ->select(
    //                     'payroll_historydetail.description',
    //                     'payroll_historydetail.amount'
    //                 )
    //                 ->leftJoin('employee_deductionother','payroll_historydetail.deductionid','=','employee_deductionother.id')
    //                 ->where('payroll_historydetail.headerid', $history->historyid)
    //                 ->where('payroll_historydetail.type', 'otherdeduction')
    //                 ->get();

    //             $getstandardallowances = Db::table('payroll_historydetail')
    //                 ->select(
    //                     'payroll_historydetail.description',
    //                     'payroll_historydetail.amount'
    //                 )
    //                 ->leftJoin('allowance_standard','payroll_historydetail.allowanceid','=','allowance_standard.id')
    //                 ->where('payroll_historydetail.headerid', $history->historyid)
    //                 ->where('payroll_historydetail.type', 'standardallowance')
    //                 ->get();

    //             $getotherallowances = Db::table('payroll_historydetail')
    //                 ->select(
    //                     'payroll_historydetail.description',
    //                     'payroll_historydetail.amount'
    //                 )
    //                 ->leftJoin('employee_allowanceother','payroll_historydetail.allowanceid','=','employee_allowanceother.id')
    //                 ->where('payroll_historydetail.headerid', $history->historyid)
    //                 ->where('payroll_historydetail.type', 'otherallowance')
    //                 ->get();

    //             $getearnedleaves = Db::table('payroll_historydetail')
    //                 ->select(
    //                     'payroll_historydetail.description',
    //                     'payroll_historydetail.amount'
    //                 )
    //                 // ->leftJoin('employee_leaves','payroll_historydetail.employeeleaveid','=','employee_leaves.leaveid')
    //                 ->where('payroll_historydetail.headerid', $history->historyid)
    //                 ->where('payroll_historydetail.type', 'earnedleave')
    //                 ->get();
                    
    //             array_push($allhistory, (object)array(
    //                 'historyinfo'           => $history,
    //                 'standarddeductions'    => $getstandarddeductions,
    //                 'otherdeductions'       => $getotherdeductions,
    //                 'standardallowances'    => $getstandardallowances,
    //                 'otherallowances'       => $getotherallowances,
    //                 'getearnedleaves'       => $getearnedleaves
    //             ));

    //         }

    //         $schoolinfo = Db::table('schoolinfo')
    //             ->select(
    //                 'schoolinfo.schoolid',
    //                 'schoolinfo.schoolname',
    //                 'schoolinfo.authorized',
    //                 'refcitymun.citymunDesc as division',
    //                 'schoolinfo.district',
    //                 'schoolinfo.address',
    //                 'schoolinfo.picurl',
    //                 'refregion.regDesc as region'
    //             )
    //             ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
    //             ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
    //             ->first();
            
    
    //         $getdaterange               = DB::table('payroll')
    //                                         ->where('id',$request->get('payrollid'))
    //                                         ->get();
    
    //         foreach($getdaterange as $daterange){
    
    //             $daterange->datefrom = date('M d, Y', strtotime($daterange->datefrom));
    //             $daterange->dateto = date('M d, Y', strtotime($daterange->dateto));
    
    //         }
            
    //         $currentdateandtime = date('F d, Y h:i:s A');
    //         $preparedby = Db::table('teacher')
    //             ->where('userid', auth()->user()->id)
    //             ->first();


    //         if($request->get('exporttype') == 'pdf'){
    //             if($request->get('format') == 'table'){
    //                 $pdf = PDF::loadview('hr/pdf/printallpaidsalarybyperiod',compact('allhistory','schoolinfo','getdaterange','currentdateandtime','preparedby'))->setPaper('8.5x11');
                    
    //                 return $pdf->stream('Payroll - '.$getdaterange[0]->datefrom.'-'.$getdaterange[0]->dateto.'.pdf');
    //             }else{
    //                 $employeesalaryinfo = $allhistory;
    //                 // return $employeesalaryinfo;
    //                 $pdf = PDF::loadview('hr/pdf/multiplepayslip',compact('employeesalaryinfo','schoolinfo','getdaterange','currentdateandtime','preparedby'))->setPaper('8.5x11');
                    
    //                 return $pdf->stream('Payroll - '.$getdaterange[0]->datefrom.'-'.$getdaterange[0]->dateto.'.pdf');
    //             }
    //         }else{
    //             // return $request->all();
    //             // return $allhistory;
                
    //             // return "C:\Users\\".getenv('username');
    //             // foreach ($spreadsheet->getWorksheetIterator() as $worksheet){
    //             //     $column = array('A','B','C');
    //             //     $lastRow = $worksheet->getHighestRow();
    //             //     foreach($column as $col){
    //             //         for ($row = 5; $row < $lastRow; $row++) {    
                    
    //             //             $sheet->getStyle($col.'5:'.$col.$lastRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

    //             //             $sheet->getStyle($col.'5:'.$col.$lastRow)
    //             //                 ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    //             //             $sheet->getStyle($col.'5:'.$col.$lastRow)
    //             //                 ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    //             //             $sheet->getStyle($col.'5:'.$col.$lastRow)
    //             //                 ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    //             //             $sheet->getStyle($col.'5:'.$col.$lastRow)
    //             //                 ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    //             //         }
    //             //     }
    //             // } 

    //             // $sheet->freezePane('E1');
    //             // $sheet->freezePane('E2');
    //             // $sheet->freezePane('E3');


    //             // $sheet->setCellValue('D1', 'Total Count:');
    //             // $sheet->setCellValue('D2', 'Total Amount:');
    //             // $sheet->setCellValue('D3', 'Batch No:');

    //             // $sheet->setCellValue('E1', '');
    //             // $sheet->setCellValue('E2', '');
    //             // $sheet->setCellValue('E3', '');

    //             // $sheet->setCellValue('D4', 'Account No. *');
    //             // $sheet->setCellValue('E4', 'Last Name');
    //             // $sheet->setCellValue('F4', 'First Name');
    //             // $sheet->setCellValue('G4', 'Middle Name');
    //             // $sheet->setCellValue('H4', 'Amount *');
    //             // $sheet->setCellValue('I4', 'Mobile No.');
    //             // $sheet->setCellValue('J4', 'Email Address');


    //             // for($x = 0; count($columnsstyle) > $x; $x++){
                    
    //             //     $sheet->getStyle($columnsstyle[$x].'1:'.$columnsstyle[$x].'3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffffff');

    //             //     $sheet->getStyle($columnsstyle[$x].'1:'.$columnsstyle[$x].'3')
    //             //         ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    //             //     $sheet->getStyle($columnsstyle[$x].'1:'.$columnsstyle[$x].'3')
    //             //         ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    //             //     $sheet->getStyle($columnsstyle[$x].'1:'.$columnsstyle[$x].'3')
    //             //         ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    //             //     $sheet->getStyle($columnsstyle[$x].'1:'.$columnsstyle[$x].'3')
    //             //         ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);


    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('bf3406');
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')-> getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('bf3406');
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('bf3406');
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('bf3406');
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('bf3406');
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('bf3406');
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('bf3406');
                    
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')
    //             //     //     ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')
    //             //     //     ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')
    //             //     //     ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')
    //             //     //     ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')
    //             //     //     ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')
    //             //     //     ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getAlignment()->applyFromArray( [ 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getAlignment()->applyFromArray( [ 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getAlignment()->applyFromArray( [ 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getAlignment()->applyFromArray( [ 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getAlignment()->applyFromArray( [ 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getAlignment()->applyFromArray( [ 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );
    //             //     // $sheet->getStyle($columnsstyle[$x].'4')->getAlignment()->applyFromArray( [ 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );
    //             // }
                
    //             // $sheet->getColumnDimension('D')->setWidth(25);
    //             // $sheet->getColumnDimension('E')->setWidth(25);
    //             // $sheet->getColumnDimension('F')->setWidth(25);
    //             // $sheet->getColumnDimension('G')->setWidth(25);
    //             // $sheet->getColumnDimension('H')->setWidth(20);
    //             // $sheet->getColumnDimension('I')->setWidth(20);
    //             // $sheet->getColumnDimension('J')->setWidth(40);

    //             // $sheet->getRowDimension('4')->setRowHeight(30);

    //             if($request->get('format') == 'table'){

    //                 $columnsstyle = array('D','E','F','G','H','I','J');

    //                 copy('format.xls', 'C:\Users\\'.getenv('username').'\Downloads\Payroll Summary As of '.$getdaterange[0]->datefrom.' - '.$getdaterange[0]->dateto.'.xls');
    //                 $filename = 'C:\Users\\'.getenv('username').'\Downloads\Payroll Summary As of '.$getdaterange[0]->datefrom.' - '.$getdaterange[0]->dateto.'.xls';
                    
    //                 if (file_exists($filename)) {
    //                     $date = new DateTime();
    //                     rename('C:\Users\\'.getenv('username').'\Downloads\Payroll Summary As of '.$getdaterange[0]->datefrom.' - '.$getdaterange[0]->dateto.'.xls', 'C:\Users\\'.getenv('username').'\Downloads\Payroll Summary As of '.$getdaterange[0]->datefrom.' - '.$getdaterange[0]->dateto.'.xls');
    //                     // echo "Rename done";
    //                 } else {
    //                     // echo "File not found";
    //                 }
    //                 $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
    //                 $sheet = $spreadsheet->getActiveSheet();
    //                 // $sheet->getActiveSheet()->getStyle('B1')
    //                 // ->getProtection()
    //                 // ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
    //                 $accountnum = 5;
    //                 // return count($allhistory);
    //                 // return $allhistory;
    
    //                 foreach($allhistory as $history){
    //                     // return 'D'.$accountnum;
    //                     $sheet->setCellValue('D'.$accountnum, '');
    //                     $sheet->setCellValue('E'.$accountnum, $history->historyinfo->lastname);
    //                     $sheet->setCellValue('F'.$accountnum, $history->historyinfo->firstname);
    //                     $sheet->setCellValue('G'.$accountnum, $history->historyinfo->middlename);
    //                     $sheet->setCellValue('H'.$accountnum, $history->historyinfo->netpay);
    //                     // $sheet->getStyle('H'.$accountnum)
    //                     //     ->getNumberFormat()
    //                     //     ->setFormatCode('###,###,###.00');
    
    //                     $sheet->setCellValue('I'.$accountnum, $history->historyinfo->contactnum);
    //                     $sheet->setCellValue('J'.$accountnum, $history->historyinfo->email);
    
    //                     // for($x = 0; count($columnsstyle) > $x; $x++){
    
    //                     //     $sheet->getStyle($columnsstyle[$x].$accountnum)
    //                     //         ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    //                     //     $sheet->getStyle($columnsstyle[$x].$accountnum)
    //                     //         ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    //                     //     $sheet->getStyle($columnsstyle[$x].$accountnum)
    //                     //         ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    //                     //     $sheet->getStyle($columnsstyle[$x].$accountnum)
    //                     //         ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    //                     // }
    
    
    //                     $accountnum+=1;
    //                 }
                    
    //                 $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
    //                 $writer->save('C:\Users\\'.getenv('username').'\Downloads\Payroll Summary As of '.$getdaterange[0]->datefrom.' - '.$getdaterange[0]->dateto.'.xls');
    //             }else{
    //                 return 'Payslip Format not yet supported';
    //             }
    //             return redirect()->back()->with('excelfeedback','C:/Users/'.getenv('username').'/Downloads');
                
    //             // $sheet->getStyle('D4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('bf3406');
    //             // $sheet->getColumnDimension('D')->setAutoSize(true);
    //             // $sheet->getRowDimension('4')->setRowHeight(30);
    //             // $sheet->getStyle('D4')->getAlignment()->applyFromArray( [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );



    //             // $writer = new Xlsx($spreadsheet);
    //             // $writer->save('yourspreadsheet.xls');
    //         }

    //     }
    // }
    
}