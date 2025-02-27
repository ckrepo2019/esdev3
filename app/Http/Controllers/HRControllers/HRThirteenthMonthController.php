<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use DateTime;
use DateInterval;
use DatePeriod;
use PDF;
use Crypt;
class HRThirteenthMonthController extends Controller
{
    public function thirteenthmonthindex($id,Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');

        $action = Crypt::decrypt($id);


        $employees = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix',
                'teacher.datehired',
                'usertype.utype as designation',
                'employee_basicsalaryinfo.amount',
                'employee_basicsalaryinfo.saturdays',
                'employee_basicsalaryinfo.sundays'
            )
            ->leftjoin('employee_basicsalaryinfo', 'teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->leftjoin('usertype', 'teacher.usertypeid','=','usertype.id')
            ->leftjoin('hr_school_department', 'usertype.departmentid','=','hr_school_department.id')
            ->where('employee_basicsalaryinfo.salarybasistype','4')
            ->where('teacher.isactive','1')
            ->where('teacher.datehired','!=',null)
            ->get();
                

        if(count($employees)>0)
        {
            foreach($employees as $employee){
    
                if($employee->datehired != null){
    
                    $employee->datehired = date('F d, Y', strtotime($employee->datehired));
                    
                }
    
                $monthlypay = 0;
    
                for($month=1; $month<=12; $month++){
                    
                    $begin = new DateTime(date('Y-m-01', strtotime(date('Y').'-'.$month)));
                    
                    $end = new DateTime(date('Y-m-t', strtotime(date('Y').'-'.$month)));
    
                    $end = $end->modify( '+1 day' ); 
                    
                    $interval = new DateInterval('P1D');
    
                    $daterange = new DatePeriod($begin, $interval ,$end);
    
                    $daysperiod = array();
    
                    $daysinaweek = 5;
    
                    $saturday = 0;
    
                    $sunday = 0;
                    
                    foreach($daterange as $date){
    
                        if($date->format("l") == 'Sunday'){
    
                            if($employee->sundays == '1'){
    
                                if($sunday == 0){
                                    $sunday+=1;
                                    $daysinaweek+=1;
                                }
                                array_push($daysperiod,$date->format("Y-m-d"));
                            }
    
                        }
                        elseif($date->format("l") == 'Saturday'){
    
                            if($employee->saturdays == '1'){
    
                                if($saturday == 0){
                                    $saturday+=1;
                                    $daysinaweek+=1;
                                }
                                array_push($daysperiod,$date->format("Y-m-d"));
                            }
    
                        }else{
    
                            array_push($daysperiod,$date->format("Y-m-d"));
    
                        }
                        
                    }
                    
                    $absentdays = array();
    
                    foreach($daysperiod as $dayatt){
    
                        $absentday = DB::table('teacherattendance')
                            ->where('teacher_id',$employee->id)
                            ->where('tdate', $dayatt)
                            ->where('deleted', 0)
                            ->get();
    
                        if(count($absentday) == 0){
    
                            $currentyear = date('Y');
    
                            $employeeid  = $employee->id;
    
                            $leaves = Db::table('employee_leaves')
                                    ->select(
                                        'employee_leaves.datefrom',
                                        'employee_leaves.dateto',
                                        'hr_leaves.withpay'
                                    )
                                    ->join('hr_leaves','employee_leaves.leaveid','=','hr_leaves.id')
                                    ->where('employee_leaves.employeeid',$employeeid)
                                    ->whereYear('employee_leaves.datefrom', $currentyear)
                                    ->orWhere(function($nest) use($currentyear, $employeeid) {
                                        $nest->where('employee_leaves.employeeid', '=', $employeeid);
                                        $nest->whereYear('employee_leaves.dateto', '=', $currentyear);
                                    })
                                    ->where('employee_leaves.deleted','0')
                                    ->where('employee_leaves.status','approved')
                                    ->get();
                                    
                            if(count($leaves) == 0){
    
                                array_push($absentdays, $dayatt);
    
                            }else{
                                
                                foreach($leaves as $leave){
                                    
                                    $beginleave         = new DateTime($leave->datefrom);
                                    
                                    $endleave           = new DateTime($leave->dateto);
    
                                    $endleave           = $endleave->modify( '+1 day' ); 
                                    
                                    $intervalleave      = new DateInterval('P1D');
    
                                    $daterangeleave     = new DatePeriod($beginleave, $intervalleave ,$endleave);
    
                                    foreach($daterangeleave as $daterangeleaveday){
    
                                        if($daterangeleaveday->format("l") == 'Sunday'){
                    
                                            if($employee->sundays == '1'){
    
                                                if($leave->withpay != 1){
    
                                                    array_push($absentdays, $dayatt);
    
                                                }
    
                                            }
                    
                                        }
                                        elseif($daterangeleaveday->format("l") == 'Saturday'){
                    
                                            if($employee->saturdays == '1'){
                                                
                                                if($leave->withpay != 1){
    
                                                    array_push($absentdays, $dayatt);
    
                                                }
    
                                            }
                    
                                        }else{
                                                
                                            if($leave->withpay != 1){
    
                                                array_push($absentdays, $dayatt);
    
                                            }
                    
                                        }
                                        
                                    }
    
                                }
    
                            }
    
                        }
    
                    }
    
                    if(count($absentdays) < count($daysperiod)){
    
                        $countpresentdays = (count($daysperiod) - count($absentdays));
    
                        $leapyear = Db::table('payroll')
                            ->where('status','1')
                            ->first()
                            ->leapyear;
    
                        if($leapyear == 0){
    
                            $dailyrate =  ($employee->amount*12)/($daysinaweek*52);
                            
                        }else{
    
                            $dailyrate =  ($employee->amount*12)/(($daysinaweek*52)+1);
    
                        }
                        
                        $monthlypay += ($dailyrate*$countpresentdays);
                        
                    }
                
                }
                
                $paystatus = Db::table('payroll_thirteenthmonth')
                    ->where('employeeid', $employee->id)
                    ->whereYear('releaseddatetime', date('Y'))
                    ->get();
                
                if(count($paystatus) == 0){
    
                    $paystatus = 0;
    
                }else{
    
                    if($paystatus[0]->isreleased == 0){
    
                        $paystatus = 0;
    
                    }elseif($paystatus[0]->isreleased == 1){
    
                        $paystatus = 1;
    
                    }
    
                }
    
                if(date('m') == 12){
    
                    $currentmonthstatus = '1';
                
                }else{
    
                    $currentmonthstatus = '0';
    
                }
                $employee->pay = $monthlypay/12;
                $employee->paystatus = $paystatus;
                $employee->currentmonthstatus = $currentmonthstatus;    
            }
        }
        // return $employeesthirteentmonthpay;
        if($action == 'view'){

            if(date('m') == 12){

                $currentmonthstatus = '1';
            
            }else{

                $currentmonthstatus = '0';

            }
            // return $employees;
            return view('hr.thirteenthmonthpay')
                ->with('employees', $employees)
                ->with('currentmonthstatus', $currentmonthstatus);

        }else{
            
            $schoolinfo = Db::table('schoolinfo')
                ->select(
                    'schoolinfo.picurl',
                    'schoolinfo.schoolid',
                    'schoolinfo.schoolname',
                    'schoolinfo.authorized',
                    'refcitymun.citymunDesc as province',
                    'schoolinfo.district',
                    'schoolinfo.address',
                    'refregion.regDesc as region'
                )
                ->join('refregion','schoolinfo.region','=','refregion.regCode')
                ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
                ->first();
    
            $preparedby = Db::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first();
    
            $finance = Db::table('teacher')
                ->where('usertypeid','15')
                ->where('isactive','1')
                ->get();
    
            $currentdate = date('F d,Y');
            
            $pdf = PDF::loadview('hr/pdf/thirteenthmonthpaysummary',compact('employeesthirteentmonthpay','schoolinfo','amountpay','preparedby','finance','currentdate'))
                    ->setPaper('8.5x11');
    
            return $pdf->stream('13th Month Pay Summary - '.date('Y').'.pdf');
        }

    }
    public function thirteenthmonthpayslip(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');

        $checkifexists = Db::table('payroll_thirteenthmonth')
            ->where('employeeid', $request->get('employeeid'))
            ->whereYear('releaseddatetime', date('Y'))
            ->get();
        
        $releasedby = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        if(count($checkifexists) == 0){

            Db::table('payroll_thirteenthmonth')
                ->insert([
                    'employeeid'        => $request->get('employeeid'),
                    'amount'            => $request->get('amountpay'),
                    'isreleased'        => '1',
                    'releasedby'        => $releasedby->id,
                    'releaseddatetime'  => date('Y-m-d H:i:s'),
                ]);

        }

        $employeeinfo = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix',
                'teacher.datehired',
                'usertype.utype as designation',
                'hr_school_department.department',
                'employee_basicsalaryinfo.amount',
                'employee_basicsalaryinfo.saturdays',
                'employee_basicsalaryinfo.sundays'
            )
            ->leftjoin('employee_basicsalaryinfo', 'teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->leftjoin('usertype', 'teacher.usertypeid','=','usertype.id')
            ->leftjoin('hr_school_department', 'usertype.departmentid','=','hr_school_department.id')
            ->where('employee_basicsalaryinfo.salarybasistype','4')
            ->where('teacher.id',$request->get('employeeid'))
            ->where('teacher.isactive','1')
            ->first();
        
        return $employeeinfo;
        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.picurl',
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as province',
                'schoolinfo.district',
                'schoolinfo.address',
                'refregion.regDesc as region'
            )
            ->join('refregion','schoolinfo.region','=','refregion.regCode')
            ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();

        $preparedby = Db::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        $finance = Db::table('teacher')
            ->where('usertypeid','15')
            ->where('isactive','1')
            ->get();

        $currentdate = date('F d,Y');

        $GLOBALS['bodyHeight'] = 0;

        $amountpay = $request->get('amountpay');

        $pdf = PDF::loadview('hr/pdf/thirteenthmonthpaysingle',compact('employeeinfo','schoolinfo','amountpay','preparedby','finance','currentdate'))
                ->setPaper(array(0,0,600,$GLOBALS['bodyHeight']+460));

        return $pdf->stream('13th Month Payslip - '.$employeeinfo->lastname.', '.$employeeinfo->firstname.'.pdf');
        
    }
    
}
