<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use DateTime;
use DateInterval;
use DatePeriod;
class HRPayrollDailyController extends Controller
{
    // Gian Additional

    public static function salarytype(Request $request){

        $salarytype = DB::table('employee_basistype')
            ->where('deleted', 0)
            ->select('id', 'type as text')
            ->get();

        return $salarytype;
    }

    public static function daily_index(Request $request){
        $employee_type = $request->get('employee_type');

        $employees = DB::table('teacher')
            ->select('teacher.id','lastname','firstname','middlename','suffix','amount as salaryamount','utype as designation')
            ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->where('employee_basicsalaryinfo.deleted','0')
            ->where('employee_basicsalaryinfo.salarybasistype', $employee_type)
            ->where('employee_basicsalaryinfo.salarybasistype', '!=', null)
            ->where('teacher.deleted','0')
            ->where('teacher.isactive','1')
            ->orderBy('lastname','asc')
            ->get();

        // return $employees;

        return view('hr.payroll.v3.daily_index')
        ->with('employees',$employees);
    }

    public static function daily_salaryinfo(Request $request){
        $employee_id = $request->get('employeeid');

        $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
            ->select('employee_basicsalaryinfo.*','employee_basistype.type as salarytype','employee_basistype.type as ratetype')
            ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
            ->where('employee_basicsalaryinfo.deleted','0')
            ->where('employee_basicsalaryinfo.employeeid', $request->get('employeeid'))
            ->first();

        $employeeinfo = DB::table('teacher')
            ->select('teacher.*','employee_personalinfo.gender','utype','teacher.id as employeeid','employee_personalinfo.departmentid')
            ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->where('teacher.id', $request->get('employeeid'))
            ->where('teacher.deleted','0')
            ->first();

        $payrollperiod = DB::table('hr_payrollv2')
            ->where('id',$request->get('payrollid'))
            ->first();

        $monthlypayroll = DB::table('hr_payrollv2')
            ->select('hr_payrollv2history.*','hr_payrollv2.datefrom','hr_payrollv2.dateto')
            ->join('hr_payrollv2history','hr_payrollv2.id','=','hr_payrollv2history.payrollid')
            ->whereYear('hr_payrollv2.datefrom',date('Y', strtotime($payrollperiod->datefrom)))
            ->whereMonth('hr_payrollv2.datefrom',date('m', strtotime($payrollperiod->datefrom)))
            ->where('hr_payrollv2.deleted','0')
            ->where('hr_payrollv2history.deleted','0')
            ->where('hr_payrollv2history.employeeid',$request->get('employeeid'))
            ->get();

        $dates = array();
    
        if($basicsalaryinfo)
        {
        
            $interval = new DateInterval('P1D');    
            $realEnd = new DateTime($payrollperiod->dateto);
            $realEnd->add($interval);    
            $period = new DatePeriod(new DateTime($payrollperiod->datefrom), $interval, $realEnd);    
            // return collect($basicsalaryinfo);
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
            $employeeinfo->ratetype = $basicsalaryinfo->ratetype;
            if(strtolower($basicsalaryinfo->salarytype) == 'monthly')
            {
                // return $dates;
                $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount/2)/count($dates);
                //return count($dates);
                $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
            }
            elseif(strtolower($basicsalaryinfo->salarytype) == 'daily')
            {
                $basicsalaryinfo->amountperday = $basicsalaryinfo->amount;
                $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
            }
            elseif(strtolower($basicsalaryinfo->salarytype) == 'hourly')
            {
                $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount*$basicsalaryinfo->hoursperday)*count($dates);
                $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
            }
        }

        $attendance = \App\Models\HR\HREmployeeAttendance::gethours($dates, $request->get('employeeid'));

        //return ($basicsalaryinfo->amount/2)/11;
        $payrollinfo = DB::table('hr_payrollv2history')
            ->where('payrollid',$payrollperiod->id)
            ->where('employeeid',$request->get('employeeid'))
            ->where('deleted','0')
            ->first();


        return view('hr.payroll.v3.getsalaryinfodaily')
            ->with('employeeinfo',$employeeinfo)
            ->with('basicsalaryinfo',$basicsalaryinfo)
            ->with('attendance',$attendance)
            ->with('payrollinfo',$payrollinfo);
    }
    

}
