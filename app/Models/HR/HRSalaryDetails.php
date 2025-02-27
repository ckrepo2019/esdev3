<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
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
class HRSalaryDetails extends Model
{
    public static function salarydetails($employeeid,$payrolldates)
    {
        date_default_timezone_set('Asia/Manila');
        $picurl = DB::table('teacher')
        ->where('id', $employeeid)
        ->first()->picurl;

       $personalinfo = DB::table('teacher')
        ->select(
            'employee_personalinfo.gender',
            'civilstatus.civilstatus',
            'hr_departments.department',
            'hr_departments.id as departmentid',
            'teacher.id as employeeid',
            'teacher.lastname',
            'teacher.firstname',
            'teacher.middlename',
            'teacher.suffix',
            'usertype.utype',
            'usertype.id as usertypeid'
            )
        ->leftJoin('employee_personalinfo','teacher.id','employee_personalinfo.employeeid')
        ->leftJoin('civilstatus','employee_personalinfo.maritalstatusid','civilstatus.civilstatus')
        ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
        ->leftJoin('hr_departments','teacher.schooldeptid','hr_departments.id')
        ->where('teacher.id', $employeeid)
        ->first();

        $office = DB::table('usertype')
            ->leftJoin('hr_school_department','usertype.departmentid','=','hr_school_department.id')
            ->where('usertype.id', $personalinfo->usertypeid)
            ->where('hr_school_department.deleted','0')
            ->first();
        
        if(count(collect($office)) == 0)
        {
            $personalinfo->office = "";
        }else{
            $personalinfo->office = $office->department;
        }


        $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
            ->select(
                'employee_basicsalaryinfo.amount as basicsalary',
                'employee_basicsalaryinfo.shiftid',
                'employee_basistype.type as salarytype',
                'employee_basicsalaryinfo.amount',
                'employee_basicsalaryinfo.hoursperday',
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
                'employee_basicsalaryinfo.attendancebased',
                'employee_basicsalaryinfo.projectbasedtype'
            )
            ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
            ->where('employeeid', $employeeid)
            ->first();

            // return collect($basicsalaryinfo);

        if(count(collect($basicsalaryinfo)) == 0)
        {
            $basicsalaryinfo = (object)array(
                'basicsalary'   => '0.00',
                'shiftid'   => null,
                'salarytype'   => null,
                'mondays'   => null,
                'tuesdays'   => null,
                'wednesdays'   => null,
                'thursdays'   => null,
                'fridays'   => null,
                'saturdays'   => null,
                'sundays'   => null,
                'attendancebased'   => null
                // 'employee_basicsalaryinfo.projectbased' => null
            );
            $personalinfo->ratetype=null;
        }else{
            
            $personalinfo->ratetype=$basicsalaryinfo->salarytype;
        }

        // return collect($payrolldates);

        $employeeworkdaysinaweek = 0;

        $payrollworkingdays = array();

        $begin = new DateTime($payrolldates->datefrom);

        $end = new DateTime($payrolldates->dateto);

        $end = $end->modify( '+1 day' ); 
        
        $intervalday = new DateInterval('P1D');

        $daterange = new DatePeriod($begin, $intervalday ,$end);
        // return collect($daterange);
        $totalhours = 0;
        foreach($daterange as $date){
            
            if(strtolower($date->format("D")) == 'mon' && $basicsalaryinfo->mondays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
                if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                {
                    $totalhours+=$basicsalaryinfo->mondayhours;
                }
            }
            elseif(strtolower($date->format("D")) == 'tue' && $basicsalaryinfo->tuesdays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
                if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                {
                    $totalhours+=$basicsalaryinfo->tuesdayhours;
                }
            }
            elseif(strtolower($date->format("D")) == 'wed' && $basicsalaryinfo->wednesdays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
                if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                {
                    $totalhours+=$basicsalaryinfo->wednesdayhours;
                }
            }
            elseif(strtolower($date->format("D")) == 'thu' && $basicsalaryinfo->thursdays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
                if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                {
                    $totalhours+=$basicsalaryinfo->thursdayhours;
                }
            }
            elseif(strtolower($date->format("D")) == 'fri' && $basicsalaryinfo->fridays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
                if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                {
                    $totalhours+=$basicsalaryinfo->fridayhours;
                }
            }
            elseif(strtolower($date->format("D")) == 'sat' && $basicsalaryinfo->saturdays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
                if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                {
                    $totalhours+=$basicsalaryinfo->saturdayhours;
                }
            }
            elseif(strtolower($date->format("D")) == 'sun' && $basicsalaryinfo->sundays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
                if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                {
                    $totalhours+=$basicsalaryinfo->sundayhours;
                }
            }

        }

        // return $payrollworkingdays;
        
        $basicsalary    = 0;
        $permonthhalfsalary = 0;
        $perdaysalary       = 0;
        $hourlyrate         = 0;
        if(strtolower($basicsalaryinfo->salarytype) == 'monthly'){
            // return $workdays;
            if(count($payrollworkingdays) == 0){

                $dailyrate =  $basicsalaryinfo->amount / (int)(date('t') - date('01'));

            }else{
                // round($getrate[0]->amount / $monthdaycount, 2);
                // return $getrate[0]->amount / count($monthworkdays);
                // return $getrate[0]->amount;
                if($payrolldates->leapyear == 0){
                    
                    $dailyrate =  ($basicsalaryinfo->amount*12)/($employeeworkdaysinaweek*52);
                    
                }else{

                    $dailyrate =  ($basicsalaryinfo->amount*12)/(($employeeworkdaysinaweek*52)+1);

                }
                
                
                
            }
            // return $dailyrate;
            if($dailyrate == 0 || $basicsalaryinfo->hoursperday == 0){
                // return 'asdasd';
                $hourlyrate = 0;
            }else{
                
                $hourlyrate = ($dailyrate)/$basicsalaryinfo->hoursperday;
            }
            // return $payrollworkingdays;
            $permonthhalfsalary = $basicsalaryinfo->amount/2;
            if(count($payrollworkingdays) == 0)
            {
                $perdaysalary = $dailyrate;
            }else{
                $perdaysalary = $permonthhalfsalary/count($payrollworkingdays);
            }
            $basicsalary = $permonthhalfsalary;

        }
        elseif(strtolower($basicsalaryinfo->salarytype) == 'daily'){

            $dailyrate =  round($basicsalaryinfo->amount, 2);
    
            $hourlyrate = ($dailyrate)/$basicsalaryinfo->hoursperday;

            $perdaysalary = $dailyrate;
            $permonthhalfsalary = $perdaysalary*count($payrollworkingdays);
            $basicsalary = $permonthhalfsalary;

            // return $hourlyrate;

        }
        elseif(strtolower($basicsalaryinfo->salarytype) == 'hourly'){
            // return $payrollworkingdays;
            
            // $totalhours = 0;
            // if(count($payrollworkingdays)>0)
            // {
            //     foreach($payrollworkingdays as $payrollworkingday)
            //     {
            //         if($basicsalaryinfo->mondayhours == 1){
            //             $totalhours += $basicsalaryinfo->mondayhours;
            //         }
            //         if($basicsalaryinfo->tuesdayhours > 0){
            //             $hoursperday = $basicsalaryinfo->tuesdayhours;
            //         }
            //         if($basicsalaryinfo->wednesdayhours > 0){
            //             $hoursperday = $basicsalaryinfo->wednesdayhours;
            //         }
            //         if($basicsalaryinfo->thursdayhours > 0){
            //             $hoursperday = $basicsalaryinfo->thursdayhours;
            //         }
            //         if($basicsalaryinfo->fridayhours > 0){
            //             $hoursperday = $basicsalaryinfo->fridayhours;
            //         }
            //         if($basicsalaryinfo->saturdayhours > 0){
            //             $hoursperday = $basicsalaryinfo->saturdayhours;
            //         }
            //         if($basicsalaryinfo->sundayhours > 0){
            //             $hoursperday = $basicsalaryinfo->sundayhours;
            //         }
            //     }
            // }
            // return $totalhours;
            $dailyrate = 0;
            $hourlyrate = $basicsalaryinfo->amount;
            $perdaysalary = 0;
            $basicsalary = $basicsalaryinfo->amount*$totalhours;


        }
        elseif(strtolower($basicsalaryinfo->salarytype) == 'project'){
            
            if($basicsalaryinfo->projectbasedtype == 'persalaryperiod'){
                
                $dailyrate =  $basicsalaryinfo->amount/count($workdays); 

                $hourlyrate =  ($basicsalaryinfo->amount/count($workdays))/$basicsalaryinfo->hoursperday; 

            }
            elseif($basicsalaryinfo->projectbasedtype == 'perday'){
                
                $dailyrate = $basicsalaryinfo->amount;

                $hourlyrate = $basicsalaryinfo->amount/$basicsalaryinfo->hoursperday;

            }
            elseif($basicsalaryinfo->projectbasedtype == 'permonth'){
                
                $payrollworkingdays = ($basicsalaryinfo->amount/count($monthworkdays))/$basicsalaryinfo->hoursperday;

                $dailyrate =  $basicsalaryinfo->amount/count($monthworkdays); 

                
                // return
                
            }
            $perdaysalary = $dailyrate;
            $basicsalary = $perdaysalary*count($payrollworkingdays);
            
        }

        // return $perdaysalary;
        
        $attendancelate         = array();
        $attendancepresent      = array();
        $attendanceabsent       = array();
        $attendanceunchecked    = array();
        $attendanceabsentdeductions = 0;

        $holidays               = array();

        
        $latedeductionamount    = 0;

        $lateminutes            = 0;

        $presentminutes         = 0;
        $absentminutes          = 0;

        $undertimeminutes       = 0;
        
        $holidaypay             = 0;

        $dailynumofhours        = 0;
        $minuteslate            = 0;
        $minuteslatehalfday     = 0;

        $lateamin               = 0;
        $undertimeamout         = 0;
        $latepmin               = 0;
        $undertimepmout         = 0;
        $undertimepmout         = 0;

        $hoursrendered          = 0;
        $presentdaysamount      = 0;


        if($basicsalaryinfo->attendancebased == 1)
        {
            if(count($payrollworkingdays)>0)
            { 
                $deductioncomputation = array();
                $customlateduration = 0;
                $customlateamount = 0;
                foreach($payrollworkingdays as $payrollworkingday)
                {
                    $attendance = HREmployeeAttendance::payrollattendancev2($payrollworkingday,$personalinfo,$hourlyrate,$basicsalaryinfo);
                    // return collect($attendance);
                    // if($payrollworkingday == '2022-01-03')
                    // {
                    //     return collect($attendance);
                    // }
                    $latedeductionamount+=$attendance->latedeductionamount;
                    // $deductioncomputation = $attendance->deductioncomputation;
                    // if(collect($attendance->customlateduration)->count()>0)
                    // {
                    //     return $payrollworkingday;
                    // }
                    $customlateduration = $attendance->customlateduration;
                    $customlateamount = $attendance->customlateamount;
                    
                    
                    $holidaypay             += $attendance->holidaypay;
                    if($payrollworkingday>date('Y-m-d'))
                    {
                        array_push($attendanceunchecked, $payrollworkingday);
                    }else{
                        if($attendance->status == 1)
                        {
                    
                            $lateminutes            += $attendance->lateminutes;
                            
                            
                    
                            $presentminutes         += $attendance->presentminutes;
                            
                            
                            $undertimeminutes       += $attendance->undertimeminutes;
                            
                            $lateamin               += $attendance->lateamin;
                            $undertimeamout         += $attendance->undertimeamout;
                            $latepmin               += $attendance->latepmin;
                            $undertimepmout         += $attendance->undertimepmout;
                            
                            $hoursrendered         += $attendance->hoursrendered;
                            $presentdaysamount         += $attendance->presentdaysamount;
                            
                            array_push($attendancepresent, $payrollworkingday);
        
                        }elseif($attendance->status == 2){
                            if($payrollworkingday<=date('Y-m-d'))
                            {
                                array_push($attendanceabsent, $payrollworkingday);
                                if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                                {
                                    
                                    $selectedday = strtolower(date('D', strtotime($payrollworkingday)));
                                    if(strtolower($selectedday) == 'mon')
                                    {
                                        $hoursperday = $basicsalaryinfo->mondayhours;
                                    }
                                    elseif(strtolower($selectedday) == 'tue')
                                    {
                                        $hoursperday = $basicsalaryinfo->tuesdayhours;
                                    }
                                    elseif(strtolower($selectedday) == 'wed')
                                    {
                                        $hoursperday = $basicsalaryinfo->wednesdayhours;
                                    }
                                    elseif(strtolower($selectedday) == 'thu')
                                    {
                                        $hoursperday = $basicsalaryinfo->thursdayhours;
                                    }
                                    elseif(strtolower($selectedday) == 'fri')
                                    {
                                        $hoursperday = $basicsalaryinfo->fridayhours;
                                    }
                                    elseif(strtolower($selectedday) == 'sat')
                                    {
                                        $hoursperday = $basicsalaryinfo->saturdayhours;
                                    }
                                    elseif(strtolower($selectedday) == 'sun')
                                    {
                                        $hoursperday = $basicsalaryinfo->sundayhours;
                                    }
                                    else
                                    {
                                        $hoursperday = 0;
                                    }
        
                                    if($hoursperday >  0)
                                    {
                                        $absentminutes+= ($hoursperday*60);
                                    }
        
                                }
                            }
        
                        }
                        if($attendance->lateamin>0 || $attendance->latepmin > 0)
                        {
                            array_push($attendancelate,(object)array(
                                'date'          => $payrollworkingday,
                                'lateamin'      => $attendance->lateamin,
                                'latepmin'      => $attendance->latepmin,
                                'latedeductionamount'      => $attendance->latedeductionamount
                            ));
                        }
                    }
                }
            }
        }
        // return $latedeductionamount;
        // return $attendanceunchecked;
        
        
        $st_date = $payrolldates->datefrom;
        $ed_date = $payrolldates->dateto;
    
        $syid = DB::table('sy')
            ->where('isactive','1')
            ->first();

        $getholidays = DB::table('schoolcal')
            ->leftJoin('schoolcaltype','schoolcal.type','=','schoolcaltype.id')
            ->where('schoolcal.syid',$syid->id)
            ->where('schoolcal.deleted','0')
            ->where('schoolcaltype.type','1')
            ->get();
            
        $holidaypay = 0;

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
                        if(in_array($holidaydate, $attendanceabsent))
                        {
                            //no work
                            $holidaypay += ($dailyrate * ($holiday->ratepercentagenowork/100));
                        }
                        if(in_array($holidaydate, $attendancepresent))
                        {

                            $holidaypay+=($dailyrate * ($holiday->ratepercentageworkon/100));
                        }
                    }
                }

            }
        }

        $leavedetails = self::getleavesapplied($employeeid,$payrolldates);
        

        $numofdaysleave = count($leavedetails);

        if(count($leavedetails) > 0){
            
            foreach($leavedetails as $leave){

                $leave->amount = 0.00;

                $getpay             = DB::table('hr_leaves')
                                    ->where('id',$leave->id)
                                    ->first();            

                if(strtolower(date('D',strtotime($leave->ldate))) == 'mon')
                {
                     if($basicsalaryinfo->mondays == 1 && $getpay->withpay == 1)
                     {
                        if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                        {
                           $leave->amount = ($basicsalaryinfo->saturdayhours*$basicsalaryinfo->amount);
                        }else{
                            $leave->amount = $perdaysalary;
                        }
                     }
                } 
                if(strtolower(date('D',strtotime($leave->ldate))) == 'tue')
                {
                     if($basicsalaryinfo->tuesdays == 1 && $getpay->withpay == 1)
                     {
                        if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                        {
                            $leave->amount = ($basicsalaryinfo->saturdayhours*$basicsalaryinfo->amount);
                        }else{
                            $leave->amount = $perdaysalary;
                        }
                     }
                } 
                if(strtolower(date('D',strtotime($leave->ldate))) == 'wed')
                {
                     if($basicsalaryinfo->wednesdays == 1 && $getpay->withpay == 1)
                     {
                        if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                        {
                            $leave->amount = ($basicsalaryinfo->saturdayhours*$basicsalaryinfo->amount);
                        }else{
                            $leave->amount = $perdaysalary;
                        }
                     }
                } 
                if(strtolower(date('D',strtotime($leave->ldate))) == 'thu')
                {
                     if($basicsalaryinfo->thursdays == 1 && $getpay->withpay == 1)
                     {
                        if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                        {
                            $leave->amount = ($basicsalaryinfo->saturdayhours*$basicsalaryinfo->amount);
                        }else{
                            $leave->amount = $perdaysalary;
                        }
                     }
                } 
                if(strtolower(date('D',strtotime($leave->ldate))) == 'fri')
                {
                     if($basicsalaryinfo->fridays == 1 && $getpay->withpay == 1)
                     {
                        if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                        {
                            $leave->amount = ($basicsalaryinfo->saturdayhours*$basicsalaryinfo->amount);
                        }else{
                            $leave->amount = $perdaysalary;
                        }
                     }
                } 
                if(strtolower(date('D',strtotime($leave->ldate))) == 'sat')
                {
                    // return date('D',strtotime($leavedatesperiod));
                    // return 'asd';
                     if($basicsalaryinfo->saturdays == 1 && $getpay->withpay == 1)
                     {
                        // return 'asdsa';
                         if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                         {
                            $leave->amount = ($basicsalaryinfo->saturdayhours*$basicsalaryinfo->amount);
                         }else{
                            $leave->amount = $perdaysalary;
                        }
                     }
                } 
                if(strtolower(date('D',strtotime($leave->ldate))) == 'sun')
                {
                     if($basicsalaryinfo->sundays == 1 && $getpay->withpay == 1)
                     {
                        if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                        {
                            $leave->amount = ($basicsalaryinfo->saturdayhours*$basicsalaryinfo->amount);
                        }else{
                            $leave->amount = $perdaysalary;
                        }
                     }
                } 
                if($leave->dayshift == 0)
                {
                    $leave->leave_type = 'WD - '.$leave->leave_type;
                }elseif($leave->dayshift == 1)
                {
                    $leave->leave_type = 'AM - '.$leave->leave_type;
                    $leave->amount = number_format($leave->amount/2);
                }elseif($leave->dayshift == 2)
                {
                    $leave->leave_type = 'PM - '.$leave->leave_type;
                    $leave->amount = number_format($leave->amount/2);
                }
                
            }
        }
        
        $overtimedetails = array();
        
        $overtimedetails = Db::table('employee_overtime')
            ->where('employee_overtime.deleted','0')
            // ->where('employee_overtime.payrolldone','0')
            ->where('employee_overtime.overtimestatus','1')
            ->whereBetween('employee_overtime.datefrom',[$payrolldates->datefrom,$payrolldates->dateto])
            ->where('employee_overtime.employeeid',$employeeid)
            ->get();
            
        $holidayovertimepay = 0;
        $overtimesalary     = 0;
        $dailyovertimehours = 0;

        $daysperiodholiday = array();

        if(count($overtimedetails) > 0){
            
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

            foreach($overtimedetails as $overtime)
            {
                
                $time1 = strtotime($overtime->timefrom);
                $time2 = strtotime($overtime->timeto);
                $overtime->numofhours = round(abs($time2 - $time1) / 3600,2);
                // $difference = round(abs($time2 - $time1) / 60,2);

                $holiday = 0;
                if(in_array($overtime->datefrom,$daysperiodholiday)){
                    
                    $overtimepay=(($hourlyrate* $getholiday->workon)/100)*$overtime->numofhours;
                    $holiday = 1;
                    
                }else{
                    $overtimepay=$hourlyrate*$overtime->numofhours;
                }
                $overtime->holiday = $holiday;
                $overtime->amount = number_format((float)$overtimepay, 2, '.', '');
            }
        }

        if($basicsalaryinfo->attendancebased == 1)
        {
            if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
            {
                // return $presentminutes/60;
                $attendanceearnings = ($basicsalaryinfo->amount*($presentminutes/60));
                $attendancedeductions = ($basicsalaryinfo->amount*($absentminutes/60));
            }else{
                $attendanceearnings = ($perdaysalary*count($attendancepresent));
                $attendancedeductions = ($perdaysalary*count($attendanceabsent));
            }
        }else{
            $attendanceearnings = $basicsalary;
            $attendancedeductions = 0.00;
        }
        
        
        $attendancedetails = (object)array(
            'attendanceearnings'        => number_format((float)$attendanceearnings, 2, '.', ''),
            'attendancedeductions'      => number_format((float)$attendancedeductions, 2, '.', ''),
            'latedeductionamount'       => number_format((float)$latedeductionamount, 2, '.', ''),
            'lateminutes'               => $lateminutes,
            'presentdaysamount'         => $presentdaysamount,
            'hoursrendered'             => $hoursrendered,
            'presentminutes'            => $presentminutes,
            'undertimeminutes'          => $undertimeminutes,
            'holidaypay'                => number_format((float)$holidaypay, 2, '.', ''),
            'dailynumofhours'           => $dailynumofhours,
            'attendancepresent'         => $attendancepresent,
            'attendanceabsent'          => $attendanceabsent,
            'minuteslate'               => $minuteslate,
            'minuteslatehalfday'        => $minuteslatehalfday,
            'lateamin'                  => $lateamin,
            'undertimeamout'            => $undertimeamout,
            'latepmin'                  => $latepmin,
            'undertimepmout'            => $undertimepmout
        );
        $basicsalaryinfo->payrollbasic = $basicsalary;
        return (object)array(
            'payrollinfo'           => $payrolldates,
            'picurl'                => $picurl,
            'personalinfo'          => $personalinfo,
            'basicsalaryinfo'       => $basicsalaryinfo,
            'attendancedetails'     => $attendancedetails,
            'payrollworkingdays'    => $payrollworkingdays,
            'perdaysalary'          => $perdaysalary,
            'leavedetails'          => $leavedetails,
            'overtimedetails'       => $overtimedetails,
            'permonthhalfsalary'    => $permonthhalfsalary
        );
    }
    public static function getleavesapplied($employeeid, $payrolldates)
    {
        $datesapproved = array();
        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
        {
            $leavesapplied = DB::table('employee_leaves')
                ->select(
                    'hr_leaves.id',
                    'hr_leaves.leave_type',
                    'employee_leaves.id as employeeleaveid',
                    'employee_leavesdetail.id as ldateid',
                    'employee_leavesdetail.ldate',
                    'employee_leavesdetail.dayshift'
                    )
                ->join('hr_leaves','employee_leaves.leaveid','=','hr_leaves.id')
                ->join('employee_leavesdetail','employee_leaves.id','=','employee_leavesdetail.headerid')
                ->whereBetween('employee_leavesdetail.ldate',[$payrolldates->datefrom,$payrolldates->dateto])
                ->where('employee_leaves.employeeid', $employeeid)
                ->where('employee_leaves.deleted','0')
                ->where('employee_leavesdetail.deleted','0')
                ->orderByDesc('employee_leaves.createddatetime')
                ->get();
                
            if(count($leavesapplied)>0)
            {
                foreach($leavesapplied as $leaveapp)
                {
                    $approvalheads = DB::table('hr_leaveemployees')
                        ->select('teacher.id','teacher.userid','teacher.lastname','teacher.firstname','teacher.middlename')
                        ->join('hr_leaveemployeesappr', 'hr_leaveemployees.id','=','hr_leaveemployeesappr.headerid')
                        ->join('teacher', 'hr_leaveemployeesappr.appuserid','=','teacher.userid')
                        ->where('hr_leaveemployees.leaveid', $leaveapp->id)
                        ->where('hr_leaveemployees.employeeid', $employeeid)
                        ->where('hr_leaveemployees.deleted','0')
                        ->where('hr_leaveemployeesappr.deleted','0')
                        ->get();
                    
                    if(count($approvalheads)>0)
                    {
    
                        foreach($approvalheads as $approvalhead)
                        {
                            $checkapproval = DB::table('employee_leavesappr')   
                                ->where('ldateid', $leaveapp->employeeleaveid)
                                ->where('appuserid', $approvalhead->userid)
                                ->where('deleted','0')
                                ->first();
    
                            if($checkapproval)
                            {
                                $approvalhead->appstatus = $checkapproval->appstatus;
                            }else{
                                $approvalhead->appstatus = 0;
                            }
                        }
                        if(collect($approvalheads)->where('appstatus','1')->count() == count($approvalheads))
                        {
                            $leaveapp->leavestatus = 1;
                        }
                    }
                }
            }
        }else{
            $leavesapplied = DB::table('hr_leaveemployees')
                ->select(
                    'hr_leaves.id',
                    'hr_leaves.leave_type',
                    'hr_leaveemployees.id as employeeleaveid',
                    'hr_leaveempdetails.id as ldateid',
                    'hr_leaveempdetails.ldate',
                    'hr_leaveempdetails.dayshift',
                    'hr_leaveempdetails.remarks',
                    'hr_leaveempdetails.appstatus',
                    'hr_leaveempdetails.halfday'
                    )
                ->join('hr_leaves','hr_leaveemployees.leaveid','=','hr_leaves.id')
                ->join('hr_leaveempdetails','hr_leaveemployees.id','=','hr_leaveempdetails.headerid')
                ->whereBetween('hr_leaveempdetails.ldate',[$payrolldates->datefrom,$payrolldates->dateto])
                ->where('hr_leaveemployees.employeeid', $employeeid)
                ->where('hr_leaveemployees.deleted','0')
                ->where('hr_leaveempdetails.deleted','0')
                ->orderByDesc('hr_leaveemployees.createddatetime')
                ->get();
            
            // return $leavesapplied;
            if(count($leavesapplied)>0)
            {
                foreach($leavesapplied as $leaveapp)
                {
                    $leaveapp->leavestatus = 0;
                    $approvalheads = DB::table('hr_leaveemployees')
                        ->select('teacher.id','teacher.userid','teacher.lastname','teacher.firstname','teacher.middlename','hr_leaveemployeesappr.appstatus')
                        ->join('hr_leaveemployeesappr', 'hr_leaveemployees.id','=','hr_leaveemployeesappr.headerid')
                        ->join('teacher', 'hr_leaveemployeesappr.appuserid','=','teacher.userid')
                        ->where('hr_leaveemployees.leaveid', $leaveapp->id)
                        ->where('hr_leaveemployees.employeeid', $employeeid)
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
        }
        return collect($leavesapplied)->where('leavestatus','1')->values();
    }
}
