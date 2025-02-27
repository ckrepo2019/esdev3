<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use DateTime;
use DateInterval;
use DatePeriod;
class HRPayrollV3Controller extends Controller
{
    public function index(Request $request)
    {
        $employees = DB::table('teacher')
            ->select('teacher.id','lastname','firstname','middlename','suffix','amount as salaryamount','utype as designation')
            ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->where('employee_basicsalaryinfo.deleted','0')
            ->where('teacher.deleted','0')
            ->where('teacher.isactive','1')
            ->orderBy('lastname','asc')
            ->get();

        // return $employees;
        return view('hr.payroll.v3.index')
        ->with('employees',$employees);
    }
    public function getsalaryinfo(Request $request)
    {
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
        
        // try{

        // }catch(\Exception $error)
        // {}
            
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
                $basicsalaryinfo->amount = ($basicsalaryinfo->amount*count($dates));
            }
            elseif(strtolower($basicsalaryinfo->salarytype) == 'hourly')
            {
                $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount*$basicsalaryinfo->hoursperday)*count($dates);
                $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
            }
        }
        //return ($basicsalaryinfo->amount/2)/11;
        $payrollinfo = DB::table('hr_payrollv2history')
            ->where('payrollid',$payrollperiod->id)
            ->where('employeeid',$request->get('employeeid'))
            ->where('deleted','0')
            ->first();
        
        $configured = 0;
        $released = 0;
        if($payrollinfo)
        {
            $configured = $payrollinfo->configured;
            $released = $payrollinfo->released;
        }

        $attendance = \App\Models\HR\HREmployeeAttendance::gethours($dates, $request->get('employeeid'));
        $timebrackets = array();
        // // $attendance = array();

        if(count($attendance)>0)
        {
            foreach($attendance as $eachdate)
            {
                $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date,$employeeinfo,($basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday),$basicsalaryinfo);
                $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                $eachdate->lateminutes = $latedeductiondetail->lateminutes;

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
        // return $attendance;
        $tardiness_computations = DB::table('hr_tardinesscomp')
            ->where('hr_tardinesscomp.deleted','0')
            ->where('hr_tardinesscomp.isactive','1')
            ->get();
 
        $tardinessamount = 0; 
        $lateduration = 0; 
        $durationtype = 0; 
        $tardinessallowance = 0; 
        $tardinessallowancetype = 0; 
        
        if(count($attendance)>0 && count($tardiness_computations)>0)
        {
            foreach($attendance as $eachatt)
            {
                $eachatt->lateamminutes = ($eachatt->lateamhours*60);
                $eachatt->latepmminutes = number_format($eachatt->latepmhours*60);
                $eachatt->lateminutes = number_format($eachatt->latehours*60);
                $eachcomputations = collect($tardiness_computations)->where('latefrom','<=', $eachatt->lateminutes)->where('lateto','>=', $eachatt->lateminutes);                
                $fromcomputations = collect($tardiness_computations)->where('latefrom','<=', $eachatt->lateminutes);
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
        // return $attendance;
        $latecomputationdetails = (object)array(
            'tardinessamount'         => $tardinessamount,
            'lateduration'            => $lateduration,
            'durationtype'            => $durationtype,
            'tardinessallowance'      => $tardinessallowance,
            'tardinessallowancetype'  => $tardinessallowancetype
        );
        // return collect($latecomputationdetails);
        ##allowances
        $standardallowances = Db::table('allowance_standard')
            ->select(
                'allowance_standard.id',
                'employee_allowancestandard.id as empallowanceid',
                'allowance_standard.description',
                'employee_allowancestandard.amount as eesamount'
            )
            ->join('employee_allowancestandard','allowance_standard.id','=','employee_allowancestandard.allowance_standardid')
            ->where('employee_allowancestandard.employeeid', $request->get('employeeid'))
            ->where('employee_allowancestandard.status','1')
            ->where('allowance_standard.deleted','0')
            ->get();

            
        if(count($standardallowances)>0)
        {
            foreach($standardallowances as $allowancetype)
            {
                $eachallowance = \App\Models\HR\HRAllowances::getstandardallowances($request->get('employeeid'), $payrollperiod, $allowancetype->empallowanceid);
                // return collect($eachallowance);
                $allowancetype->amount = $eachallowance->amount;
                $allowancetype->lock = $eachallowance->lock;
                $allowancetype->paidforthismonth = $eachallowance->paidforthismonth;
                $allowancetype->totalamount = $eachallowance->totalamount;
                $allowancetype->paymenttype = $eachallowance->paymenttype;
            }
        }
        $otherallowances = Db::table('employee_allowanceother')
            ->select(
                'employee_allowanceother.id',
                'employee_allowanceother.description',
                'employee_allowanceother.amount',
                'employee_allowanceother.term'
            )
            ->where('employee_allowanceother.employeeid', $request->get('employeeid'))
            ->where('employee_allowanceother.deleted','0')
            ->get();
            
        $otherallowancesarray = array();
        if(count($otherallowances)>0)
        {
            foreach($otherallowances as $eachotherallowance)
            {
                $paidallowances = DB::table('hr_payrollv2history')
                ->select(DB::raw('SUM(`amountpaid`) as amountpaid'))
                ->join('hr_payrollv2historydetail','hr_payrollv2history.id','=','hr_payrollv2historydetail.headerid')
                ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
                ->where('hr_payrollv2history.released','1')
                ->where('hr_payrollv2history.deleted','0')
                ->where('hr_payrollv2historydetail.particulartype','4')
                ->where('hr_payrollv2historydetail.particularid',$eachotherallowance->id)
                ->where('hr_payrollv2history.payrollid','<',$payrollperiod->id)
                ->first()->amountpaid;
                
                if($paidallowances == null || $paidallowances == 0)
                {
                    $eachotherallowance->paidforthismonth = 0;
                    $eachotherallowance->lock = 0;
                    $eachotherallowance->totalamount = 0;
                    $eachotherallowance->amounttopay = 0;
                    $eachotherallowance->paymenttype = 0; // 0 = full; 1 = half;
                    if(count($monthlypayroll) == 0)
                    {
                        if($eachotherallowance->term == 0)
                        {
                            $eachotherallowance->amounttopay = $eachotherallowance->amount;
                            $eachotherallowance->totalamount = $eachotherallowance->totalamount;
                        }else{
                            $eachotherallowance->amounttopay = ($eachotherallowance->amount/$eachotherallowance->term);
                            $eachotherallowance->totalamount = ($eachotherallowance->amount/$eachotherallowance->term);
                        }
            
                    }
                    elseif(count($monthlypayroll) == 1)
                    {
                        if($payrollinfo)
                        {
                            if($payrollinfo->id == $monthlypayroll[0]->id)
                            {
                                $allowanceinfo = DB::table('hr_payrollv2historydetail')
                                    ->where('headerid', $payrollinfo->id)
                                    ->where('particulartype',4)
                                    ->where('deleted','0')
                                    ->where('particularid',$eachotherallowance->id)
                                    ->first();

                                if($allowanceinfo)
                                {
                                    $eachotherallowance->paymenttype = $allowanceinfo->paymenttype;
                                    $eachotherallowance->amounttopay = $allowanceinfo->amountpaid;
                                    $eachotherallowance->totalamount = $allowanceinfo->totalamount;
                                }else{
                                    $eachotherallowance->paymenttype = null;
                                    $eachotherallowance->amounttopay = ($eachotherallowance->amount/$eachotherallowance->term);
                                    $eachotherallowance->totalamount = ($eachotherallowance->amount/$eachotherallowance->term);
                                }
                            }else{
                                if(date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom)))
                                {
                                    $allowanceinfo = DB::table('hr_payrollv2historydetail')
                                    ->where('headerid', $monthlypayroll[0]->id)
                                        ->where('particulartype',4)
                                        ->where('deleted','0')
                                        ->where('particularid',$eachotherallowance->id)
                                        ->first();
                        
                                    if($allowanceinfo)
                                    {
                                        $eachotherallowance->paymenttype = $allowanceinfo->paymenttype;
                                        $eachotherallowance->amounttopay = $allowanceinfo->amountpaid;
                                        $eachotherallowance->totalamount = $allowanceinfo->totalamount;
                                    }else{
                                        if($eachotherallowance->term == 0)
                                        {
                                            $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                            $eachotherallowance->totalamount = $eachotherallowance->totalamount;
                                        }else{
                                            $eachotherallowance->amounttopay = ($eachotherallowance->amount/$eachotherallowance->term);
                                            $eachotherallowance->totalamount = ($eachotherallowance->amount/$eachotherallowance->term);
                                        }
                                        $eachotherallowance->lock        = 1;
                                    }
                                    
                                }else{
                                    if($eachotherallowance->term == 0)
                                    {
                                        $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                        $eachotherallowance->totalamount = $eachotherallowance->totalamount;
                                    }else{
                                        $eachotherallowance->amounttopay = ($eachotherallowance->amount/$eachotherallowance->term);
                                        $eachotherallowance->totalamount = ($eachotherallowance->amount/$eachotherallowance->term);
                                    }
                                }
    
                            }
                
                            // return collect($deductinfo);
                        }else{
                            $allowanceinfo = DB::table('hr_payrollv2historydetail')
                                ->where('headerid', $monthlypayroll[0]->id)
                                ->where('particulartype',4)
                                ->where('deleted','0')
                                ->where('particularid',$eachotherallowance->id)
                                ->first();

                            if(date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom)))
                            {
                    
                                if($allowanceinfo)
                                {
                                    if($allowanceinfo->paymenttype == 1)
                                    {
                                        $eachotherallowance->paymenttype = 1;
                                    }else{
                                        $eachotherallowance->paidforthismonth = 1;
                                    }
                                    // return $alloweinfo->paymenttype;
                                    $eachotherallowance->amounttopay = $allowanceinfo->amountpaid;
                                    $eachotherallowance->totalamount = $allowanceinfo->totalamount;
                                    $eachotherallowance->lock        = 1;
                                }else{
                                    if($allowanceinfo)
                                    {
                                        $eachotherallowance->amounttopay = $allowanceinfo->amount;
                                        $eachotherallowance->totalamount = $allowanceinfo->amount;
                                        $eachotherallowance->lock        = 1;
                                    }
    
                                }
                                
                            }else{
                                if($eachotherallowance->term == 0)
                                {
                                    $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                    $eachotherallowance->totalamount = $eachotherallowance->totalamount;
                                }else{
                                    $eachotherallowance->amounttopay = ($eachotherallowance->amount/$eachotherallowance->term);
                                    $eachotherallowance->totalamount = ($eachotherallowance->amount/$eachotherallowance->term);
                                }
                            }
                        }  
            
                    }elseif(count($monthlypayroll) == 2)
                    {         
                        $allowanceinfo = DB::table('hr_payrollv2historydetail')
                        ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->id)
                            ->where('particulartype',4)
                            ->where('deleted','0')
                            ->where('particularid',$eachotherallowance->id)
                            ->first();     
                            
                        if($allowanceinfo)
                        {
                            if(collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->released == 1)
                            {
                                
                                if($allowanceinfo)
                                {
                                    if($allowanceinfo->paymenttype == 1)
                                    {
                                        $eachotherallowance->paymenttype = 1;
                                    }else{
                                        $eachotherallowance->paidforthismonth = 1;
                                    }
                                    $eachotherallowance->amounttopay = $allowanceinfo->amountpaid;
                                    $eachotherallowance->totalamount = $allowanceinfo->totalamount;
                                    $eachotherallowance->lock        = 1;
                                }
                
                            }else{
                                // return collect($deductinfo);
                                if($allowanceinfo)
                                {
                                    if($allowanceinfo->paymenttype == 1)
                                    {
                                        $eachotherallowance->paymenttype = 1;
                                    }else{
                                        // $eachotherdeduction->paidforthismonth = 1;
                                        $eachotherallowance->paidforthismonth = 0;
                                    }
                                    $eachotherallowance->amounttopay = ($eachotherallowance->totalamount/$eachotherallowance->term);
                                    $eachotherallowance->totalamount = ($eachotherallowance->amount/$eachotherallowance->term);
                                    $eachotherallowance->lock        = 1;
                                }else{
                                    if($allowanceinfo)
                                    {
                                        $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                        $eachotherallowance->totalamount = $eachotherallowance->amount;
                                        $eachotherallowance->lock        = 1;
                                    }
                
                                }
                            }
                        }else{
                            $allowanceinfo = DB::table('hr_payrollv2historydetail')
                            ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->id)
                                ->where('particulartype',4)
                                ->where('deleted','0')
                                ->where('particularid',$eachotherallowance->id)
                                ->first(); 
                                
                            if($allowanceinfo)
                            {
                                if($allowanceinfo->paymenttype == 0)
                                {
                                    $eachotherallowance->paidforthismonth = 1;
                                    $eachotherallowance->amounttopay = $allowanceinfo->amountpaid;
                                    $eachotherallowance->totalamount = $allowanceinfo->totalamount;
                                    $eachotherallowance->lock        = 1;
                                }
                            }
                        }
                    }
                    array_push($otherallowancesarray, $eachotherallowance);

                }else{
                    if($eachotherallowance->amount>$paidallowances)
                    {
                        $paidallowances = DB::table('hr_payrollv2history')
                        ->select(DB::raw('SUM(`amountpaid`) as amountpaid'))
                        ->join('hr_payrollv2historydetail','hr_payrollv2history.id','=','hr_payrollv2historydetail.headerid')
                        ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
                        ->where('hr_payrollv2history.released','1')
                        ->where('hr_payrollv2history.deleted','0')
                        ->where('hr_payrollv2historydetail.particulartype','4')
                        ->where('hr_payrollv2historydetail.particularid',$eachotherallowance->id)
                        ->where('hr_payrollv2history.payrollid','<',$payrollperiod->id)
                        ->first()->amountpaid;
                        
        
                        $eachotherallowance->paidforthismonth = 0;
                        $eachotherallowance->lock = 0;
                        $eachotherallowance->totalamount = 0;
                        $eachotherallowance->amounttopay = 0;
                        $eachotherallowance->paymenttype = 0; // 0 = full; 1 = half;
                        if(count($monthlypayroll) == 0)
                        {
                            if($eachotherallowance->term == 0)
                            {
                                $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                $eachotherallowance->totalamount = $eachotherallowance->amount;
                            }else{
                                $eachotherallowance->amounttopay = ($eachotherallowance->amount/$eachotherallowance->term);
                                $eachotherallowance->totalamount = ($eachotherallowance->amount/$eachotherallowance->term);
                            }
                
                        }
                        elseif(count($monthlypayroll) == 1)
                        {
                            if($payrollinfo)
                            {
                                if($payrollinfo->id == $monthlypayroll[0]->id)
                                {
                                    $alloweinfo = DB::table('hr_payrollv2historydetail')
                                        ->where('headerid', $payrollinfo->id)
                                        ->where('particulartype',4)
                                        ->where('deleted','0')
                                        ->where('particularid',$eachotherallowance->id)
                                        ->first();
                        
                                    if($alloweinfo)
                                    {
                                        $eachotherallowance->paymenttype = $alloweinfo->paymenttype;
                                        $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                        $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                    }
                                }else{
                                    if(date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom)))
                                    {
                                        $alloweinfo = DB::table('hr_payrollv2historydetail')
                                        ->where('headerid', $monthlypayroll[0]->id)
                                            ->where('particulartype',4)
                                            ->where('deleted','0')
                                            ->where('particularid',$eachotherallowance->id)
                                            ->first();
                            
                                        if($alloweinfo)
                                        {
                                            $eachotherallowance->paymenttype = $alloweinfo->paymenttype;
                                            $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                            $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                        }else{
                                            if($allowanceinfo)
                                            {
                                                $eachotherallowance->amounttopay = $allowanceinfo->amount;
                                                $eachotherallowance->totalamount = $allowanceinfo->amount;
                                                $eachotherallowance->lock        = 1;
                                            }
        
                                        }
                                        
                                    }else{
                                        if($allowanceinfo)
                                        {
                                            $eachotherallowance->amounttopay = $allowanceinfo->amount;
                                            $eachotherallowance->totalamount = $allowanceinfo->amount;
                                        }
                                    }
                                }
                            }else{
                                $alloweinfo = DB::table('hr_payrollv2historydetail')
                                    ->where('headerid', $monthlypayroll[0]->id)
                                    ->where('particulartype',4)
                                    ->where('deleted','0')
                                    ->where('particularid',$eachotherallowance->id)
                                    ->first();
                                if(date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom)))
                                {                    
                                    // return collect($alloweinfo);
                                    if($alloweinfo)
                                    {
                                        if($alloweinfo->paymenttype == 1)
                                        {
                                            $eachotherallowance->paymenttype = 1;
                                        }else{
                                            $eachotherallowance->paidforthismonth = 1;
                                        }
                                        // return $alloweinfo->paymenttype;
                                        $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                        $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                        $eachotherallowance->lock        = 1;
                                    }else{
                                        if($allowanceinfo)
                                        {
                                            $eachotherallowance->amounttopay = $allowanceinfo->amount;
                                            $eachotherallowance->totalamount = $allowanceinfo->amount;
                                            $eachotherallowance->lock        = 1;
                                        }
        
                                    }
                                    
                                }else{
                                    if($eachotherallowance->term == 0)
                                    {
                                        $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                        $eachotherallowance->totalamount = $eachotherallowance->amount;
                                    }else{
                                        $eachotherallowance->amounttopay = ($eachotherallowance->amount/$eachotherallowance->term);
                                        $eachotherallowance->totalamount = ($eachotherallowance->amount/$eachotherallowance->term);
                                    }
                                }
                            }
                
                        }elseif(count($monthlypayroll) == 2)
                        {
                            $alloweinfo = DB::table('hr_payrollv2historydetail')
                                ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->id ?? 0)
                                ->where('particulartype',4)
                                ->where('deleted','0')
                                ->where('particularid',$eachotherallowance->id)
                                ->first();
                                
                            if($alloweinfo)
                            {
                                if(collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->released == 1)
                                {
                                    
                                    if($alloweinfo)
                                    {
                                        if($alloweinfo->paymenttype == 1)
                                        {
                                            $eachotherallowance->paymenttype = 1;
                                        }else{
                                            $eachotherallowance->paidforthismonth = 1;
                                        }
                                        $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                        $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                        $eachotherallowance->lock        = 1;
                                    }
                    
                                }else{
                                    if($alloweinfo)
                                    {
                                        if($alloweinfo->paymenttype == 1)
                                        {
                                            $eachotherallowance->paymenttype = 1;
                                        }else{
                                            $eachotherallowance->paidforthismonth = 1;
                                        }
                                        $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                        $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                        $eachotherallowance->lock        = 1;
                                    }else{
                                        if($allowanceinfo)
                                        {
                                            $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                            $eachotherallowance->totalamount = $eachotherallowance->amount;
                                            $eachotherallowance->lock        = 1;
                                        }
                    
                                    }
                                }
                            }else{
								if(isset($payrollinfo->id))
								{
									$alloweinfo = DB::table('hr_payrollv2historydetail')
										->where('headerid', collect($monthlypayroll)->where('payrollid', '!=',$payrollinfo->id)->first()->id)
										->where('particulartype',4)
										->where('deleted','0')
										->where('particularid',$eachotherallowance->id)
										->first();
										
									if($alloweinfo)
									{
										if($alloweinfo->paymenttype == 0)
										{
											$eachotherallowance->paidforthismonth = 1;
											$eachotherallowance->amounttopay = $alloweinfo->amountpaid;
											$eachotherallowance->totalamount = $alloweinfo->totalamount;
											$eachotherallowance->lock        = 1;
										}
									}
								}
                            }
                        }
                        array_push($otherallowancesarray, $eachotherallowance);
                    }
                }
            }
        }
        $otherallowances = $otherallowancesarray;
        ##deductions
        $deductiontypes = Db::table('deduction_standard')
        ->where('deleted','0')
        ->get();
        $standarddeductions = array();
        // return $deductiontypes;
        if(count($deductiontypes)>0)
        {
            foreach($deductiontypes as $deductiontype)
            {
                $checkifapplied = DB::table('employee_deductionstandard')
                    ->where('employeeid', $request->get('employeeid'))
                    ->where('deduction_typeid', $deductiontype->id)
                    ->where('deleted','0')
                    ->where('status','1')
                    ->first();

                if($checkifapplied)
                {
                    // return collect($deductiontype);
                    // try{
                        $eachdeduction = \App\Models\HR\HRDeductions::getstandarddeductions($request->get('employeeid'), $payrollperiod, $deductiontype->id);
                        // return collect($eachdeduction);
                        $deductiontype->amount = $eachdeduction->amount;
                        $deductiontype->lock = $eachdeduction->lock;
                        $deductiontype->paidforthismonth = $eachdeduction->paidforthismonth;
                        $deductiontype->totalamount = $eachdeduction->totalamount;
                        $deductiontype->paymenttype = $eachdeduction->paymenttype;
                        $deductiontype->balances = $eachdeduction->balances;
                        // array_push($standarddeductions, $deductiontype);
                        if($deductiontype->amount < 1 && count($deductiontype->balances) == 0)
                        {
        
                        }else{
                            if($deductiontype->amount>0)
                            {
                                array_push($standarddeductions, $deductiontype);
                            }
                        }
                    // }catch(\Exception $error)
                    // {
                    //     $eachdeduction = \App\Models\HR\HRDeductions::getstandarddeductions($request->get('employeeid'), $payrollperiod, $deductiontype->id);
                    //     return collect($eachdeduction);
                    // }
                        // return collect($eachdeduction);
                }
                // else{
                //     $amount = \App\Http\Controllers\HRControllers\HRDeductionSetupController::getbracket_standard($request->get('employeeid'), $basicsalaryinfo->amount, $deductiontype->id, 'amount');
                //     $deductiontype->amount = $amount;
                //     $deductiontype->lock = 0;
                //     $deductiontype->paidforthismonth = 0;
                //     $deductiontype->totalamount = $amount;
                //     $deductiontype->paymenttype = 0;
                //     $deductiontype->balances = array();
                // }
            }
        }
        // return $standarddeductions;
        $otherdeductions = Db::table('employee_deductionother')
            ->select(
                'employee_deductionother.id',
                'employee_deductionother.description',
                'employee_deductionother.amount',
                'employee_deductionother.term'
            )
            ->where('employee_deductionother.employeeid', $request->get('employeeid'))
            ->where('employee_deductionother.paid','0')
            ->where('employee_deductionother.status','1')
            ->where('employee_deductionother.deleted','0')
            ->where('deductionotherid','!=',null)
            ->get();
            
        // return $otherdeductions;
        $otherdeductionsarray = array();
        if(count($otherdeductions)>0)
        {
            foreach($otherdeductions as $eachotherdeduction)
            {
                $paiddeductions = DB::table('hr_payrollv2history')
                ->select(DB::raw('SUM(`amountpaid`) as amountpaid'))
                ->join('hr_payrollv2historydetail','hr_payrollv2history.id','=','hr_payrollv2historydetail.headerid')
                ->join('hr_payrollv2','hr_payrollv2history.payrollid','=','hr_payrollv2.id')
                ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
                ->where('hr_payrollv2history.released','1')
                ->where('hr_payrollv2history.deleted','0')
                ->where('hr_payrollv2.deleted','0')
                ->where('hr_payrollv2historydetail.particulartype','2')
                ->where('hr_payrollv2historydetail.particularid',$eachotherdeduction->id)
                ->where('hr_payrollv2history.payrollid','<',$payrollperiod->id)
                ->first()->amountpaid;
                if($paiddeductions == null || $paiddeductions == 0)
                {
                    $eachotherdeduction->paidforthismonth = 0;
                    $eachotherdeduction->lock = 0;
                    $eachotherdeduction->totalamount = 0;
                    $eachotherdeduction->amounttopay = 0;
                    $eachotherdeduction->paymenttype = 0; // 0 = full; 1 = half;
                    if(count($monthlypayroll) == 0)
                    {
                        if($eachotherdeduction->term == 0)
                        {
                            $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                            $eachotherdeduction->totalamount = $eachotherdeduction->amount;
                        }else{
                            $eachotherdeduction->amounttopay = ($eachotherdeduction->amount/$eachotherdeduction->term);
                            $eachotherdeduction->totalamount = ($eachotherdeduction->amount/$eachotherdeduction->term);
                        }
            
                    }
                    elseif(count($monthlypayroll) == 1)
                    {
                        if($payrollinfo)
                        {
                            if($payrollinfo->id == $monthlypayroll[0]->id)
                            {
                                $deductinfo = DB::table('hr_payrollv2historydetail')
                                    ->select('hr_payrollv2historydetail.*')
                                    ->join('hr_payrollv2','hr_payrollv2historydetail.payrollid','=','hr_payrollv2.id')
                                    ->where('headerid', $payrollinfo->id)
                                    ->where('particulartype',2)
                                    ->where('hr_payrollv2.deleted','0')
                                    ->where('hr_payrollv2historydetail.deleted','0')
                                    ->where('particularid',$eachotherdeduction->id)
                                    ->first();
    
                                if($deductinfo)
                                {
                                    $eachotherdeduction->paymenttype = $deductinfo->paymenttype;
                                    $eachotherdeduction->amounttopay = $deductinfo->amountpaid;
                                    $eachotherdeduction->totalamount = $deductinfo->totalamount;
                                }else{
                                    $eachotherdeduction->paymenttype = null;
                                    $eachotherdeduction->amounttopay = ($eachotherdeduction->amount > 0 && $eachotherdeduction->term > 0) ? ($eachotherdeduction->amount/$eachotherdeduction->term) : $eachotherdeduction->amount;
                                    $eachotherdeduction->totalamount = $eachotherdeduction->amounttopay;
                                }
                            }else{
                                if(date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom)))
                                {
									$deductinfo = DB::table('hr_payrollv2historydetail')
										->select('hr_payrollv2historydetail.*')
										->join('hr_payrollv2','hr_payrollv2historydetail.payrollid','=','hr_payrollv2.id')
										->where('headerid', $monthlypayroll[0]->id)
										->where('particulartype',2)
										->where('hr_payrollv2.deleted','0')
										->where('hr_payrollv2historydetail.deleted','0')
										->where('particularid',$eachotherdeduction->id)
										->first();
                                    if($deductinfo)
                                    {
                                        $eachotherdeduction->paymenttype = $deductinfo->paymenttype;
                                        $eachotherdeduction->amounttopay = $deductinfo->amountpaid;
                                        $eachotherdeduction->totalamount = $deductinfo->totalamount;
                                    }else{
                                        if($eachotherdeduction->term == 0)
                                        {
                                            $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                            $eachotherdeduction->totalamount = $eachotherdeduction->totalamount;
                                        }else{
                                            $eachotherdeduction->amounttopay = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                            $eachotherdeduction->totalamount = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                        }
                                        $eachotherdeduction->lock        = 1;
                                    }
                                    
                                }else{
                                    if($eachotherdeduction->term == 0)
                                    {
                                        $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                        $eachotherdeduction->totalamount = $eachotherdeduction->totalamount;
                                    }else{
                                        $eachotherdeduction->amounttopay = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                        $eachotherdeduction->totalamount = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                    }
                                }
    
                            }
                
                            // return collect($deductinfo);
                        }else{
                            $deductinfo = DB::table('hr_payrollv2historydetail')
                                ->select('hr_payrollv2historydetail.*')
                                ->join('hr_payrollv2','hr_payrollv2historydetail.payrollid','=','hr_payrollv2.id')
                                ->where('headerid', $monthlypayroll[0]->id)
                                ->where('particulartype',2)
                                ->where('hr_payrollv2.deleted','0')
                                ->where('hr_payrollv2historydetail.deleted','0')
                                ->where('particularid',$eachotherdeduction->id)
                                ->first();
                            if(date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom)))
                            {
                    
                                if($deductinfo)
                                {
                                    if($deductinfo->paymenttype == 1)
                                    {
                                        $eachotherdeduction->paymenttype = 1;
                                    }else{
                                        $eachotherdeduction->paidforthismonth = 1;
                                    }
                                    // return $alloweinfo->paymenttype;
                                    $eachotherdeduction->amounttopay = $deductinfo->amountpaid;
                                    $eachotherdeduction->totalamount = $deductinfo->totalamount;
                                    $eachotherdeduction->lock        = 1;
                                }else{
                                    if($deductinfo)
                                    {
                                        $eachotherdeduction->amounttopay = $deductinfo->amount;
                                        $eachotherdeduction->totalamount = $deductinfo->amount;
                                        $eachotherdeduction->lock        = 1;
                                    }
    
                                }
                                
                            }else{
                                if($eachotherdeduction->term == 0)
                                {
                                    $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                    $eachotherdeduction->totalamount = $eachotherdeduction->totalamount;
                                }else{
                                    $eachotherdeduction->amounttopay = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                    $eachotherdeduction->totalamount = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                }
                            }
                        }  
            
                    }elseif(count($monthlypayroll) == 2)
                    {                       
                        try{
                            $deductinfo = DB::table('hr_payrollv2historydetail')
                                ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->id)
                                ->where('particulartype',2)
                                ->where('deleted','0')
                                ->where('particularid',$eachotherdeduction->id)
                                ->first();
                        }catch(\Exception $error)
                        {
                            $deductinfo = false;
                        } 
                            
                        if($deductinfo)
                        {
                            if(collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->released == 1)
                            {
                                
                                if($deductinfo)
                                {
                                    if($deductinfo->paymenttype == 1)
                                    {
                                        $eachotherdeduction->paymenttype = 1;
                                    }else{
                                        $eachotherdeduction->paidforthismonth = 1;
                                    }
                                    $eachotherdeduction->amounttopay = $deductinfo->amountpaid;
                                    $eachotherdeduction->totalamount = $deductinfo->totalamount;
                                    $eachotherdeduction->lock        = 1;
                                }
                
                            }else{
                                // return collect($deductinfo);
                                if($deductinfo)
                                {
                                    if($deductinfo->paymenttype == 1)
                                    {
                                        $eachotherdeduction->paymenttype = 1;
                                    }else{
                                        // $eachotherdeduction->paidforthismonth = 1;
                                        $eachotherdeduction->paidforthismonth = 0;
                                    }
                                    $eachotherdeduction->amounttopay = ($eachotherdeduction->totalamount/$eachotherdeduction->term);
                                    $eachotherdeduction->totalamount = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                    $eachotherdeduction->lock        = 1;
                                }else{
                                    if($allowanceinfo)
                                    {
                                        $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                        $eachotherdeduction->totalamount = $eachotherdeduction->amount;
                                        $eachotherdeduction->lock        = 1;
                                    }
                
                                }
                            }
                        }else{
                            if($payrollinfo)
                            {
                                $deductinfo = DB::table('hr_payrollv2historydetail')
                                    ->where('headerid', collect($monthlypayroll)->where('payrollid', '!=',$payrollinfo->id)->first()->id)
                                    ->where('particulartype',4)
                                    ->where('deleted','0')
                                    ->where('particularid',$eachotherdeduction->id)
                                    ->first();
                                    
                                if($deductinfo)
                                {
                                    if($deductinfo->paymenttype == 0)
                                    {
                                        $eachotherdeduction->paidforthismonth = 1;
                                        $eachotherdeduction->amounttopay = $deductinfo->amountpaid;
                                        $eachotherdeduction->totalamount = $deductinfo->totalamount;
                                        $eachotherdeduction->lock        = 1;
                                    }
                                }
                            }
                        }
                    }
                    array_push($otherdeductionsarray, $eachotherdeduction);
                }else{
                    if($eachotherdeduction->amount>$paiddeductions)
                    {
                        $eachotherdeduction->paidforthismonth = 0;
                        $eachotherdeduction->lock = 0;
                        $eachotherdeduction->totalamount = 0;
                        $eachotherdeduction->amounttopay = 0;
                        $eachotherdeduction->paymenttype = 0; // 0 = full; 1 = half;
                        if(count($monthlypayroll) == 0)
                        {
                            if($eachotherdeduction->term == 0)
                            {
                                $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                $eachotherdeduction->totalamount = $eachotherdeduction->totalamount;
                            }else{
                                $eachotherdeduction->amounttopay = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                $eachotherdeduction->totalamount = ($eachotherdeduction->amount/$eachotherdeduction->term);
                            }
                
                        }
                        elseif(count($monthlypayroll) == 1)
                        {
                            if($payrollinfo)
                            {
                                if($payrollinfo->id == $monthlypayroll[0]->id)
                                {
                                    $deductinfo = DB::table('hr_payrollv2historydetail')
                                        ->where('headerid', $payrollinfo->id)
                                        ->where('particulartype',2)
                                        ->where('deleted','0')
                                        ->where('particularid',$eachotherdeduction->id)
                                        ->first();
        
                                    if($deductinfo)
                                    {
                                        $eachotherdeduction->paymenttype = $deductinfo->paymenttype;
                                        $eachotherdeduction->amounttopay = $deductinfo->amountpaid;
                                        $eachotherdeduction->totalamount = $deductinfo->totalamount;
                                    }
                                }else{
                                    if(date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom)))
                                    {
                                        $deductinfo = DB::table('hr_payrollv2historydetail')
                                        ->where('headerid', $monthlypayroll[0]->id)
                                            ->where('particulartype',2)
                                            ->where('deleted','0')
                                            ->where('particularid',$eachotherdeduction->id)
                                            ->first();
                            
                                        if($deductinfo)
                                        {
                                            $eachotherdeduction->paymenttype = $deductinfo->paymenttype;
                                            $eachotherdeduction->amounttopay = $deductinfo->amountpaid;
                                            $eachotherdeduction->totalamount = $deductinfo->totalamount;
                                        }else{
                                            if($eachotherdeduction->term == 0)
                                            {
                                                $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                                $eachotherdeduction->totalamount = $eachotherdeduction->totalamount;
                                            }else{
                                                $eachotherdeduction->amounttopay = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                                $eachotherdeduction->totalamount = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                            }
                                            $eachotherdeduction->lock        = 1;
                                        }
                                        
                                    }else{
                                        if($eachotherdeduction->term == 0)
                                        {
                                            $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                            $eachotherdeduction->totalamount = $eachotherdeduction->totalamount;
                                        }else{
                                            $eachotherdeduction->amounttopay = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                            $eachotherdeduction->totalamount = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                        }
                                    }
        
                                }
                    
                                // return collect($deductinfo);
                            }else{
                                $deductinfo = DB::table('hr_payrollv2historydetail')
                                    ->where('headerid', $monthlypayroll[0]->id)
                                    ->where('particulartype',2)
                                    ->where('deleted','0')
                                    ->where('particularid',$eachotherdeduction->id)
                                    ->first();
                                if(date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom)))
                                {
                        
                                    // return collect($alloweinfo);
                                    if($deductinfo)
                                    {
                                        if($deductinfo->paymenttype == 1)
                                        {
                                            $eachotherdeduction->paymenttype = 1;
                                        }else{
                                            $eachotherdeduction->paidforthismonth = 1;
                                        }
                                        // return $alloweinfo->paymenttype;
                                        $eachotherdeduction->amounttopay = $deductinfo->amountpaid;
                                        $eachotherdeduction->totalamount = $deductinfo->totalamount;
                                        $eachotherdeduction->lock        = 1;
                                    }else{
                                        if($deductinfo)
                                        {
                                            $eachotherdeduction->amounttopay = $deductinfo->amount;
                                            $eachotherdeduction->totalamount = $deductinfo->amount;
                                            $eachotherdeduction->lock        = 1;
                                        }
        
                                    }
                                    
                                }else{
                                    if($eachotherdeduction->term == 0)
                                    {
                                        $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                        $eachotherdeduction->totalamount = $eachotherdeduction->totalamount;
                                    }else{
                                        $eachotherdeduction->amounttopay = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                        $eachotherdeduction->totalamount = ($eachotherdeduction->amount/$eachotherdeduction->term);
                                    }
                                }
                            }  
                
                        }elseif(count($monthlypayroll) == 2)
                        {                        
                            $deductinfo = DB::table('hr_payrollv2historydetail')
                                ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->id ?? 0)
                                ->where('particulartype',2)
                                ->where('deleted','0')
                                ->where('particularid',$eachotherdeduction->id)
                                ->first();
                                
                            if($deductinfo)
                            {
                                if(collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->released == 1)
                                {
                                    
                                    if($deductinfo)
                                    {
                                        if($deductinfo->paymenttype == 1)
                                        {
                                            $eachotherdeduction->paymenttype = 1;
                                        }else{
                                            $eachotherdeduction->paidforthismonth = 1;
                                        }
                                        $eachotherdeduction->amounttopay = $deductinfo->amountpaid;
                                        $eachotherdeduction->totalamount = $deductinfo->totalamount;
                                        $eachotherdeduction->lock        = 1;
                                    }
                    
                                }else{
                                    if($deductinfo)
                                    {
                                        if($deductinfo->paymenttype == 1)
                                        {
                                            $eachotherdeduction->paymenttype = 1;
                                        }else{
                                            $eachotherdeduction->paidforthismonth = 1;
                                        }
                                        $eachotherdeduction->amounttopay = $deductinfo->amountpaid;
                                        $eachotherdeduction->totalamount = $deductinfo->totalamount;
                                        $eachotherdeduction->lock        = 1;
                                    }else{
                                        if($allowanceinfo)
                                        {
                                            $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                            $eachotherdeduction->totalamount = $eachotherdeduction->amount;
                                            $eachotherdeduction->lock        = 1;
                                        }
                    
                                    }
                                }
                            }else{
								if(isset($payrollinfo->id))
								{
									$deductinfo = DB::table('hr_payrollv2historydetail')
										->where('headerid', collect($monthlypayroll)->where('payrollid', '!=',$payrollinfo->id)->first()->id)
										->where('particulartype',4)
										->where('deleted','0')
										->where('particularid',$eachotherdeduction->id)
										->first();
										
									if($deductinfo)
									{
										if($deductinfo->paymenttype == 0)
										{
											$eachotherdeduction->paidforthismonth = 1;
											$eachotherdeduction->amounttopay = $deductinfo->amountpaid;
											$eachotherdeduction->totalamount = $deductinfo->totalamount;
											$eachotherdeduction->lock        = 1;
										}
									}
								}
                            }
                        }
                        array_push($otherdeductionsarray, $eachotherdeduction);
                    }
                }
            }
        }
        $otherdeductions = $otherdeductionsarray;
        // return $otherdeductions;
        
        $deductionsetup = DB::table('employee_basicsalaryinfo')
        ->where('employeeid', $request->get('employeeid'))
        ->where('deleted','0')
        ->first()
        ->deductionsetup;        
        $deductiontypes = Db::table('deduction_standard')
        ->where('deleted','0')
        ->get();

        $addedparticulars = array();

        if($payrollinfo)
        {
            $addedparticulars = DB::table('hr_payrollv2addparticular')
                ->where('payrollid', $payrollperiod->id)
                ->where('employeeid', $request->get('employeeid'))
                ->where('deleted','0')
                ->get();
        }

        
        $getholidays = DB::table('schoolcal')
            ->leftJoin('schoolcaltype','schoolcal.type','=','schoolcaltype.id')
            ->where('schoolcal.syid',DB::table('sy')->where('isactive','1')->first()->id)
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
                    // foreach($holidays as $holidaydate)
                    // {
                    //     if(in_array($holidaydate, $attendanceabsent))
                    //     {
                    //         //no work
                    //         $holidaypay += ($dailyrate * ($holiday->ratepercentagenowork/100));
                    //     }
                    //     if(in_array($holidaydate, $attendancepresent))
                    //     {

                    //         $holidaypay+=($dailyrate * ($holiday->ratepercentageworkon/100));
                    //     }
                    // }
                }

            }
        }

        $perdaysalary = 0;
        $perhour = 0;
        if($basicsalaryinfo)
        {
            $perdaysalary = $basicsalaryinfo->amountperday;
            $perhour = ($basicsalaryinfo->amountperday)/$basicsalaryinfo->hoursperday;
        }

        if($configured == 0)
        {
            
            $leavedetails = \App\Models\HR\HRSalaryDetails::getleavesapplied($request->get('employeeid'),$payrollperiod);
            // return $leavedetails;
        
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
                        // return $basicsalaryinfo->saturdays;
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
                        $leave->amount = round($leave->amount,2);
                    }elseif($leave->dayshift == 1)
                    {
                        $leave->leave_type = 'AM - '.$leave->leave_type;
                        $leave->amount = round(($leave->amount/2),2);
                    }elseif($leave->dayshift == 2)
                    {
                        $leave->leave_type = 'PM - '.$leave->leave_type;
                        $leave->amount = round(($leave->amount/2),2);
                    }
                    
                }
            }
        }else{
            $leavedetails = DB::table('hr_payrollv2historydetail')
                ->select('hr_payrollv2historydetail.amountpaid as amount','hr_payrollv2historydetail.description as leave_type','hr_payrollv2historydetail.leavedateid as ldateid','hr_payrollv2historydetail.employeeleaveid','hr_leaveempdetails.ldate','hr_leaveempdetails.dayshift')
                ->join('hr_leaveemployees','hr_payrollv2historydetail.employeeleaveid','hr_leaveemployees.id')
                ->join('hr_leaveempdetails','hr_payrollv2historydetail.leavedateid','=','hr_leaveempdetails.id')
                ->where('hr_payrollv2historydetail.headerid', $payrollinfo->id)
                // ->where('particulartype', 6)
                ->where('hr_payrollv2historydetail.employeeid',$request->get('employeeid'))
                ->where('hr_payrollv2historydetail.deleted', 0)
                ->where('hr_payrollv2historydetail.leavedateid','>', 0)
                ->get();
            // return $leavedetails;
        }
        // return $leavedetails[1]->ldateid ?? collect($leavedetails[1]);
        if($released == 0)
        {
            $filedovertimes = DB::table('employee_overtime')
                ->where('deleted','0')
                ->where('overtimestatus','1')
                ->where('payrolldone','0')
                ->whereIn('datefrom',$dates)
                ->where('employeeid',$request->get('employeeid'))
                ->get();
    
            if(count($filedovertimes)>0)
            {
                foreach($filedovertimes as $filedovertime)
                {
                    $timefrom = strtotime($filedovertime->timefrom);
                    $timeto = strtotime($filedovertime->timeto);
                    $difference = round(abs($timeto - $timefrom) / 3600,2);
                    $filedovertime->totalhours = $difference;
                    $filedovertime->amount = $difference*$perhour;
                }
            }
        }else{
            $filedovertimes = DB::table('hr_payrollv2historydetail')
                ->select('employee_overtime.*','hr_payrollv2historydetail.amountpaid as amount','hr_payrollv2historydetail.description as totalhours')
                ->join('employee_overtime','hr_payrollv2historydetail.particularid','employee_overtime.id')
                ->where('headerid', $payrollinfo->id)
                ->where('particulartype', 6)
                ->where('hr_payrollv2historydetail.deleted', 0)
                ->get();

    
            if(count($filedovertimes)>0)
            {
                foreach($filedovertimes as $filedovertime)
                {
                    $filedovertime->totalhours = (int) filter_var($filedovertime->totalhours, FILTER_SANITIZE_NUMBER_INT);
                }
            }
            // return collect($payrollinfo);
        }

        
        // return collect($basicsalaryinfo);
        // deduction_tardinessdetail
        // deduction_tardinessapplication
        // return collect($latecomputationdetails);
        // return $latecomputationdetails;
        // return $released;
        // return collect($attendance)->where('totalworkinghours','>',0)->pluck('totalworkinghours');
        // return 
        // return collect($attendance)->pluck('totalworkinghours');
        // return $leavedetails;
        return view('hr.payroll.v3.getsalaryinfo')
        ->with('leaves', $leavedetails)
        ->with('timebrackets', $timebrackets)
        ->with('employeeinfo', $employeeinfo)
        ->with('latecomputationdetails', $latecomputationdetails)
        ->with('addedparticulars',$addedparticulars)
        ->with('dates',$dates)
        ->with('configured',$configured)
        ->with('released',$released)
        ->with('attendance',$attendance)
        ->with('basicsalaryinfo',$basicsalaryinfo)
        ->with('otherallowances',$otherallowances)
        ->with('otherdeductions',$otherdeductions)
        ->with('standardallowances',$standardallowances)
        ->with('standarddeductions',$standarddeductions)
        ->with('filedovertimes',$filedovertimes)
        ->with('payrollinfo',$payrollinfo)
        ->with('employeeid',$request->get('employeeid')); 
    
	}
	public function addedparticular(Request $request)
    {
        if($request->get('action') == 'delete')
        {
            DB::table('hr_payrollv2addparticular')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
         
            return 1;
        }
    }
    public function configuration(Request $request)
    {            
        // return $request->all();
        $employeeid = $request->get('employeeid');
        $payrollid = $request->get('payrollid');
        $particulars = json_decode($request->get('particulars'));
        $additionalparticulars = json_decode($request->get('additionalparticulars'));
        $lateminutes = $request->get('lateminutes');
        $undertimeminutes = $request->get('undertimeminutes');
        $totalworkedhours = $request->get('totalworkedhours');
        $amountperday = str_replace( ',', '', $request->get('amountperday'));
        $totalearnings = str_replace( ',', '', $request->get('totalearnings'));
        $totaldeductions = str_replace( ',', '', $request->get('totaldeductions'));
        $amountperday = str_replace( ',', '', $request->get('amountperday'));
        $tardinessamount = str_replace( ',', '', $request->get('tardinessamount'));
        $amountabsent = str_replace( ',', '', $request->get('amountabsent'));
        $amountlate = str_replace( ',', '', $request->get('amountlate'));
        $netsalary = str_replace( ',', '', $request->get('netsalary'));
        $monthlysalary = $request->get('monthlysalary');
        // $newsalary = str_replace( ',', '', $request->get('newsalary'));
        // return gettype($payrollid);/
        // return $particulars;
        $leaves = array();
        if(count($particulars)>0)
        {
            foreach($particulars as $eachleave)
            {
                if(collect($eachleave)->contains('ldateid'))
                {
                    array_push($leaves, $eachleave);
                }
            }
        }
        // return $leaves;
        $particulars = collect($particulars)->where('particularid','!=','0')->values();
        
        $checkhistoryifexists = DB::table('hr_payrollv2history')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $employeeid)
            ->where('deleted','0')
            ->first();
        
        if($tardinessamount == 'NaN')
        {
            $tardinessamount = 0;;
        }
        if($checkhistoryifexists)
        {
            DB::table('hr_payrollv2history')
                ->where('id', $checkhistoryifexists->id)
                ->update([
                    'dailyrate'           => $amountperday,
                    'daysabsentamount'           => $amountabsent,
                    'lateminutes'           => $lateminutes,
                    'lateamount'            => $amountlate,
                    'undertimeminutes'      => $undertimeminutes,
                    'totalhoursworked'      => $totalworkedhours,
                    'totalearning'          => $totalearnings,
                    'totaldeduction'          => $totaldeductions,
                    'amountperday'          => $amountperday,
                    'presentdays'           => $request->get('dayspresent'),
                    'absentdays'            => $request->get('daysabsent'),
                    'basicsalaryamount'     => str_replace( ',', '', $request->get('basicsalary')),
                    'netsalary'             => str_replace( ',', '', $request->get('netsalary')),
                    'basicsalarytype'       => $request->get('salarytype'),
                    'monthlysalary'         => $monthlysalary,
                    'updatedby'             => auth()->user()->id,
                    'updateddatetime'       => date('Y-m-d H:i:s')
                ]);
                
            // if(count($leaves)>0)
            // {
            //     foreach($leaves as $eachleave)
            //     {
            //         DB::table('hr_payrollv2historydetail')
            //         ->insert([
            //             'payrollid'             => $payrollid,
            //             'employeeid'            => $employeeid,
            //             'headerid'              => $checkhistoryifexists->id,
            //             'description'           => $eachleave->description,
            //             'totalamount'           => str_replace( ',', '', $eachleave->totalamount),
            //             'amountpaid'           => str_replace( ',', '', $eachleave->amountpaid),
            //             // 'paymenttype'           => $particular->paymenttype,
            //             // 'particulartype'           => $particular->particulartype,
            //             'days'                  => 1,
            //             'particularid'           => $eachleave->particularid,
            //             'employeeleaveid'           => $eachleave->particularid,
            //             'leavedateid'           => $eachleave->ldateid,
            //             'createdby'             => auth()->user()->id,
            //             'createddatetime'       => date('Y-m-d H:i:s')
            //         ]);
            //     }
            // }
            if(count($particulars) == 0)
            {
                DB::table('hr_payrollv2historydetail')
                    ->where('headerid', $checkhistoryifexists->id)
                    ->where('type', 0)
                    ->update([
                        'deleted'               => 1,
                        'deletedby'             => auth()->user()->id,
                        'deleteddatetime'       => date('Y-m-d H:i:s')
                    ]);
            }else{
                $allparts = DB::table('hr_payrollv2historydetail')
                    ->where('headerid', $checkhistoryifexists->id)
                    ->where('type','0')
                    ->where('deleted','0')
                    ->get();
                    // return $particulars;
                if(count($allparts) == 0)
                {
                    foreach($particulars as $particular)
                    {
                        $type = 0;
                        if($particular->particularid)
                        {
                            $type = $particular->particulartype;
                        }

                        $detailid = DB::table('hr_payrollv2historydetail')
                            ->insertGetId([
                                'payrollid'             => $payrollid,
                                'employeeid'            => $employeeid,
                                'headerid'              => $checkhistoryifexists->id,
                                'description'           => $particular->description,
                                'totalamount'           => str_replace( ',', '', $particular->totalamount),
                                'amountpaid'           => str_replace( ',', '', $particular->amountpaid),
                                'paymenttype'           => $particular->paymenttype,
                                'particulartype'           => $particular->particulartype,
                                'particularid'           => $particular->particularid,
                                'createdby'             => auth()->user()->id,
                                'createddatetime'       => date('Y-m-d H:i:s')
                            ]);
    
                        $balance = $particular->totalamount - $particular->amountpaid;
                        if($balance>0.00)
                        {
                            DB::table('hr_payrollv2balance')
                            ->insert([
                                'payrollid'             => $payrollid,
                                'detailid'            => $detailid,
                                'balance'              => str_replace( ',', '', $balance),
                                'createdby'             => auth()->user()->id,
                                'createddatetime'       => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }else{
                    // return $particulars;
                    foreach($particulars as $particular)
                    {
                        $type = 0;
                        if($particular->particularid)
                        {
                            $type = $particular->particulartype;
                        }

                        if(collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->count()>0)
                        {
                            // return collect($particular);
                            // return collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid);
                            DB::table('hr_payrollv2historydetail')
                                ->where('id', collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->first()->id)
                                ->update([
                                    'description'           => $particular->description,
                                    'totalamount'           => str_replace( ',', '', $particular->totalamount),
                                    'amountpaid'           => str_replace( ',', '', $particular->amountpaid),
                                    'paymenttype'           => $particular->paymenttype,
                                    'updatedby'             => auth()->user()->id,
                                    'updateddatetime'       => date('Y-m-d H:i:s')
                                ]);

                                $checkbalanceifexists = DB::table('hr_payrollv2balance')
                                ->where('payrollid', $payrollid)
                                ->where('detailid', collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->first()->id)
                                ->where('deleted','0')
                                ->first();
                                $balance = $particular->totalamount - $particular->amountpaid;
                                if($balance>0.00)
                                {
                                    if($checkbalanceifexists)
                                    {
                                        DB::table('hr_payrollv2balance')
                                        ->where('id', $checkbalanceifexists->id)
                                        ->update([
                                            'balance'              => str_replace( ',', '', $balance),
                                            'updatedby'             => auth()->user()->id,
                                            'updateddatetime'       => date('Y-m-d H:i:s')
                                        ]);
                                    }else{
                                        DB::table('hr_payrollv2balance')
                                        ->insert([
                                            'payrollid'             => $payrollid,
                                            'detailid'            => collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->first()->id,
                                            'balance'              => str_replace( ',', '', $balance),
                                            'createdby'             => auth()->user()->id,
                                            'createddatetime'       => date('Y-m-d H:i:s')
                                        ]);
                                    }
                                }else{
                                    if($checkbalanceifexists)
                                    {
                                        DB::table('hr_payrollv2balance')
                                        ->where('id', $checkbalanceifexists->id)
                                        ->insert([
                                            'paid'              => 1,
                                            'updatedby'             => auth()->user()->id,
                                            'updateddatetime'       => date('Y-m-d H:i:s')
                                        ]);
                                    }
                                }
                        }else{
                            DB::table('hr_payrollv2historydetail')
                                // ->where('id', collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->first()->id)
                                ->insert([
                                    'payrollid'           => $payrollid,
                                    'employeeid'           => $employeeid,
                                    'headerid'           => $checkhistoryifexists->id,
                                    'description'           => $particular->description,
                                    'totalamount'           => str_replace( ',', '', $particular->totalamount),
                                    'amountpaid'           => str_replace( ',', '', $particular->amountpaid),
                                    'paymenttype'           => $particular->paymenttype,
                                    'particulartype'           => $particular->particulartype,
                                    'particularid'           => $particular->particularid,
                                    'type'           => $type,
                                    'updatedby'             => auth()->user()->id,
                                    'updateddatetime'       => date('Y-m-d H:i:s')
                                ]);

                        }
                    }
                }
            }
            if(count($additionalparticulars) == 0)
            {
                DB::table('hr_payrollv2addparticular')
                    ->where('headerid', $checkhistoryifexists->id)
                    // ->where('type', '>','0')
                    ->update([
                        'deleted'               => 1,
                        'deletedby'             => auth()->user()->id,
                        'deleteddatetime'       => date('Y-m-d H:i:s')
                    ]);
            }else{
                $allparts = DB::table('hr_payrollv2addparticular')
                    ->where('headerid', $checkhistoryifexists->id)
                    ->where('deleted','0')
                    ->get();

                // return $additionalparticulars;
                if(count($allparts) == 0)
                {
                    foreach($additionalparticulars as $addedparticular)
                    {

                        $detailid = DB::table('hr_payrollv2addparticular')
                            ->insertGetId([
                                'payrollid'             => $payrollid,
                                'employeeid'             => $employeeid,
                                'headerid'             =>  $checkhistoryifexists->id,
                                'description'            => $addedparticular->description,
                                'amount'            => str_replace( ',', '', $addedparticular->amount),
                                'type'              => $addedparticular->type,
                                'createdby'             => auth()->user()->id,
                                'createddatetime'       => date('Y-m-d H:i:s')
                            ]);
                    }
                }else{
                    foreach($additionalparticulars as $addedparticular)
                    {
                        if($addedparticular->id == 0)
                        {
                            DB::table('hr_payrollv2addparticular')
                            ->insert([
                                'payrollid'             => $payrollid,
                                'employeeid'             => $employeeid,
                                'headerid'             => $checkhistoryifexists->id,
                                'description'            => $addedparticular->description,
                                'amount'            => str_replace( ',', '', $addedparticular->amount),
                                'type'              => $addedparticular->type,
                                'createdby'             => auth()->user()->id,
                                'createddatetime'       => date('Y-m-d H:i:s')
                            ]);
                        }else{
                            if(collect($allparts)->where('id', $addedparticular->id)->count()>0)
                            {
                                DB::table('hr_payrollv2addparticular')
                                    ->where('id', collect($allparts)->where('id', $addedparticular->id)->first()->id)
                                    ->update([
                                        'description'           => $addedparticular->description,
                                        'amount'           => str_replace( ',', '', $addedparticular->amount),
                                        'type'           => $addedparticular->type,
                                        'updatedby'             => auth()->user()->id,
                                        'updateddatetime'       => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                }
            }
        }else{

            $headerid = DB::table('hr_payrollv2history')
                ->insertGetId([
                    'dailyrate'           => $amountperday,
                    'payrollid'             => $payrollid,
                    'employeeid'            => $employeeid,
                    'lateminutes'           => $lateminutes,
                    'lateamount'            => $amountlate,
                    'undertimeminutes'      => $undertimeminutes,
                    'totalhoursworked'      => $totalworkedhours,
                    'totalearning'          => $totalearnings,
                    'totaldeduction'          => $totaldeductions,
                    'amountperday'          => $amountperday,
                    'presentdays'           => $request->get('dayspresent'),
                    'absentdays'            => $request->get('daysabsent'),
                    'daysabsentamount'           => $amountabsent,
                    'basicsalaryamount'     => str_replace( ',', '', $request->get('basicsalary')),
                    'netsalary'             =>  str_replace( ',', '', $request->get('netsalary')),
                    'basicsalarytype'       => $request->get('salarytype'),
                    'monthlysalary'         => $monthlysalary,
                    'configured'            =>  1,
                    'createdby'             => auth()->user()->id,
                    'createddatetime'       => date('Y-m-d H:i:s')
                ]);

            if(count($leaves)>0)
            {
                foreach($leaves as $eachleave)
                {
                    DB::table('hr_payrollv2historydetail')
                    ->insert([
                        'payrollid'             => $payrollid,
                        'employeeid'            => $employeeid,
                        'headerid'              => $headerid,
                        'description'           => $eachleave->description,
                        'totalamount'           => str_replace( ',', '', $eachleave->totalamount),
                        'amountpaid'           => str_replace( ',', '', $eachleave->amountpaid),
                        // 'paymenttype'           => $particular->paymenttype,
                        // 'particulartype'           => $particular->particulartype,
                        'days'                  => 1,
                        'particularid'           => $eachleave->particularid,
                        'employeeleaveid'           => $eachleave->employeeleaveid,
                        'leavedateid'           => $eachleave->ldateid,
                        'createdby'             => auth()->user()->id,
                        'createddatetime'       => date('Y-m-d H:i:s')
                    ]);
                }
            }
            if(count($particulars)> 0)
            {
                foreach($particulars as $particular)
                {
                    $detailid = DB::table('hr_payrollv2historydetail')
                        ->insertGetId([
                            'payrollid'             => $payrollid,
                            'employeeid'            => $employeeid,
                            'headerid'              => $headerid,
                            'description'           => $particular->description,
                            'totalamount'           => str_replace( ',', '', $particular->totalamount),
                            'amountpaid'           => str_replace( ',', '', $particular->amountpaid),
                            'paymenttype'           => $particular->paymenttype,
                            'particulartype'           => $particular->particulartype,
                            'particularid'           => $particular->particularid,
                            'createdby'             => auth()->user()->id,
                            'createddatetime'       => date('Y-m-d H:i:s')
                        ]);

                    $balance = $particular->totalamount - $particular->amountpaid;
                    if($balance>0.00)
                    {
                        DB::table('hr_payrollv2balance')
                        ->insert([
                            'payrollid'             => $payrollid,
                            'detailid'            => $detailid,
                            'balance'              => str_replace( ',', '', $balance),
                            'createdby'             => auth()->user()->id,
                            'createddatetime'       => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
            if(count($additionalparticulars)>0)
            {
                foreach($additionalparticulars as $eachparticular)
                {
                    DB::table('hr_payrollv2addparticular')
                    ->insert([
                        'payrollid'             => $payrollid,
                        'employeeid'            => $employeeid,
                        'headerid'              => $headerid,
                        'description'           => $eachparticular->description,
                        'amount'                => $eachparticular->amount,
                        'type'                  => $eachparticular->type,
                        'createdby'             => auth()->user()->id,
                        'createddatetime'       => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        return 1;
        
    }
    public function payrolldates(Request $request)
    {
        if($request->get('action') != 'closepayroll' && $request->get('action') != 'getnumberofreleased')
        {
            $dates = explode(' - ', $request->get('dates'));
            $datefrom = date('Y-m-d', strtotime($dates[0]));
            $dateto   = date('Y-m-d', strtotime($dates[1]));
        }

        if($request->get('action') == 'update')
        {
            try{
                $checkifexists = DB::table('hr_payrollv2')
                    ->where('status','1')
                    ->first();

                if($checkifexists)
                {
                    DB::table('hr_payrollv2')
                        ->where('id',$checkifexists->id)
                        ->update([
                            'datefrom'          => $datefrom,
                            'dateto'            => $dateto,
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
        
                }else{
                    DB::table('hr_payrollv2')
                        ->update([
                            'status'            => 0
                        ]);
                    DB::table('hr_payrollv2')
                        ->insert([
                            'datefrom'          => $datefrom,
                            'dateto'            => $dateto,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
                return 1;
            }catch(\Exception $error)
            {
                return 0;
            }
        }
        elseif($request->get('action') == 'closepayroll')
        {
            try{
                DB::table('hr_payrollv2')
                    ->where('id', $request->get('payrollid'))
                    ->update([
                        'status'    => 0,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
                return 1;
            }catch(\Exception $error)
            {
                return $error;
            }
        }
        elseif($request->get('action') == 'getnumberofreleased')
        {
                return  DB::table('hr_payrollv2history')
                    ->where('payrollid', $request->get('payrollid'))
                    ->where('deleted','0')
                    ->where('released','1')
                    ->count();
        }else{
            try{
                DB::table('hr_payrollv2')
                    ->update([
                        'status'            => 0
                    ]);
                DB::table('hr_payrollv2')
                    ->insert([
                        'datefrom'          => $datefrom,
                        'dateto'            => $dateto,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
    
                return 1;
            }catch(\Exception $error)
            {
                return 0;
            }
        }
    }
    public static function getstandarddeductions($employeeid)
    {
        $dates = explode(' - ', $request->get('dates'));
        $datefrom = date('Y-m-d', strtotime($dates[0]));
        $dateto   = date('Y-m-d', strtotime($dates[1]));
        try{
            DB::table('hr_payrollv2')
                ->insert([
                    'datefrom'          => $datefrom,
                    'dateto'            => $dateto,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }
    }
    public function exportpayslip(Request $request)
    {
        // return $request->all();
        $payrollid = $request->get('payrollid');
        if($request->get('exporttype') == 1)
        {
			$schoolinfo = DB::table('schoolinfo')
				->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
				->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
				->select('schoolinfo.*','refregion.regDesc','refcitymun.citymunDesc')
				->get();
			//return $schoolinfo;
            $employeeid = $request->get('employeeid');

            $header = DB::table('hr_payrollv2history')
                ->select('hr_payrollv2history.*','teacher.title','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix','teacher.tid','usertype.utype')
                ->join('teacher','hr_payrollv2history.employeeid','=','teacher.id')
                ->join('usertype','teacher.usertypeid','=','usertype.id')
                ->where('payrollid',$payrollid)
                ->where('employeeid',$employeeid)
                ->where('hr_payrollv2history.deleted','0')
                ->first();

            
            $payrollinfo = DB::table('hr_payrollv2')
                ->where('id', $payrollid)
                ->where('deleted','0')
                ->first();
			
			$balance = 0;
            $totaldeduction = 0;
            $particulars = DB::table('hr_payrollv2historydetail')			
                ->select('payrollid','employeeid','headerid','description','totalamount','amountpaid','paymenttype','particulartype','remarks','particularid','type','leavedateid','deductionid','deductiondesc','allowanceid','allowancedesc','paymentoption','employeeleaveid','days','employeeovertimeid','overtimehours','amount','dateissued')
                ->where('payrollid',$payrollid)
                ->where('employeeid',$employeeid)
                ->where('deleted','0')
				->distinct()
                ->get();
				
			foreach ($particulars as $particular) {
                $totalAmount = floatval($particular->totalamount);
                $amountPaid = floatval($particular->amountpaid);
                $balance = $totalAmount - $amountPaid;
            
                // Add the "balance" key to each item in the $particulars array
                $particular->balance = number_format($balance, 2); // Format the balance with 2 decimal places if needed
            }
                
            if(count($particulars)>0)
            {
                foreach($particulars as $particular)
                {
                    if($particular->particulartype == 6)
                    {
                        DB::table('employee_overtime')
                            ->where('id', $particular->particularid)
                            ->update([
                                'payrolldone'   => 1
                            ]);
                    }
                    $totaldeduction+=$particular->amountpaid;
                }
            }

            if($header->middlename != null)
            {
                $header->middlename = $header->middlename[0].'.';
            }

            $addedparticulars = DB::table('hr_payrollv2addparticular')
            ->where('payrollid',$payrollid)
            ->where('employeeid',$employeeid)
                ->where('deleted','0')
                ->get();


            $checkifexistsreleased = DB::table('hr_payrollv2history')
            ->where('id', $header->id)
            ->first();

            if($checkifexistsreleased->released == 0)
            {
                DB::table('hr_payrollv2history')
                    ->where('id', $header->id)
                    ->update([
                        'released'          => 1,
                        'releasedby'        => auth()->user()->id,
                        'releaseddatetime'  => date('Y-m-d H:i:s')
                    ]);
            }

                
            $leavedetails = DB::table('hr_payrollv2historydetail')
            ->select('hr_payrollv2historydetail.amountpaid as amount','hr_payrollv2historydetail.description as leave_type','hr_payrollv2historydetail.leavedateid as ldateid','hr_payrollv2historydetail.employeeleaveid','hr_leaveempdetails.ldate','hr_leaveempdetails.dayshift')
            ->join('hr_leaveemployees','hr_payrollv2historydetail.employeeleaveid','hr_leaveemployees.id')
            ->join('hr_leaveempdetails','hr_payrollv2historydetail.leavedateid','=','hr_leaveempdetails.id')
            ->where('hr_payrollv2historydetail.headerid', $header->payrollid)
            // ->where('particulartype', 6)
            ->where('hr_payrollv2historydetail.employeeid',$header->employeeid)
            ->where('hr_payrollv2historydetail.deleted', 0)
            ->where('hr_payrollv2historydetail.leavedateid','>', 0)
            ->get();
            
            $payrolldetail = $checkifexistsreleased;
            $payrolldetail->totaldeduction+=$totaldeduction;
            $payrolldetail->netsalary = ($payrolldetail->totalearning - $payrolldetail->totaldeduction) ?? 0.00;
            // return collect($payrollinfo);
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
            {
                $pdf = PDF::loadview('hr/payroll/v3/pdf_single',compact('header','particulars','payrollinfo','addedparticulars','leavedetails'));
            }else{
                $pdf = PDF::loadview('hr/payroll/v3/pdf_singlev4',compact('schoolinfo','header','particulars','payrollinfo','addedparticulars','payrolldetail','leavedetails'));
            }
            // $pdf = PDF::loadview('hr/payroll/v3/pdf_single',compact('header','particulars','payrollinfo','addedparticulars'));
            // return $particulars;
    
            return $pdf->stream('Payslip - '.$header->lastname.'_'.$header->firstname.'_'.date('Y').'.pdf');
        }elseif($request->get('exporttype') == 2)
        {
            $payrolldates = DB::table('hr_payrollv2')
                ->where('id', $payrollid)
                ->where('deleted','0')
                ->get();

            $employees = DB::table('hr_payrollv2history')
                ->select('teacher.*','hr_payrollv2history.id as headerid')
                ->join('teacher','hr_payrollv2history.employeeid','=','teacher.id')
                ->where('hr_payrollv2history.payrollid', $payrollid)
                ->where('hr_payrollv2history.deleted','0')
                ->where('released','1')
                ->get();

            if(count($employees)>0)
            {
                foreach($employees as $employee)
                {
                    $employee->header = DB::table('hr_payrollv2history') 
                        ->where('id', $employee->headerid)
                        ->first();
                    
                    $employee->netsalary = $employee->header->netsalary;
                    $employee->particulars = DB::table('hr_payrollv2historydetail') 
                        ->where('headerid', $employee->headerid)
                        ->where('deleted','0')
                        ->get();

                    $employee->totalstandarddeductions = collect($employee->particulars)->whereIn('particulartype',[1,2])->sum('amountpaid');
                    $employee->totalstandardallowances = collect($employee->particulars)->whereIn('particulartype',[3,4])->sum('amountpaid');
                        

                    $employee->addedparticulars = DB::table('hr_payrollv2addparticular') 
                        ->where('headerid', $employee->headerid)
                        ->where('deleted','0')
                        ->get();

                    $employee->totaladdeddeductions = collect($employee->addedparticulars)->where('type',2)->sum('amount');
                    $employee->totaladdedallowances = collect($employee->addedparticulars)->where('type',1)->sum('amount');
                        
                }
            }
            

            // $particulars = collect($employees)->pluck('particulars')->toArray();
            // return $particulars;
            $standarddeductions = DB::table('deduction_standard')
                ->where('deleted','0')
                ->get();

            $pdf = PDF::loadview('hr/payroll/v3/pdf_summary',compact('payrolldates','employees','standarddeductions'))->setPaper('8.5x14','landscape');
    
            return $pdf->stream('Payroll Summary - '.date('Y').'.pdf');
        }
    }
    public function payrollhistory(Request $request)
    {
        if(!$request->has('action'))
        {
            $payrollperiods = DB::table('hr_payrollv2')
                ->where('deleted','0')
                ->get();
    
            return view('hr.payroll.v3.summary_index')
                ->with('payrollperiods', $payrollperiods); 
        }else{
            if($request->get('action') == 'gethistory')
            {
                $histories = DB::table('hr_payrollv2history')
                    ->select(
                        'hr_payrollv2history.id',
                        'hr_payrollv2history.employeeid',
                        'hr_payrollv2history.presentdays',
                        'hr_payrollv2history.absentdays',
                        'hr_payrollv2history.basicsalaryamount',
                        'hr_payrollv2history.basicsalarytype',
                        'hr_payrollv2history.daysabsentamount',
                        'hr_payrollv2history.lateamount',
                        'hr_payrollv2history.undertimeminutes',
                        'hr_payrollv2history.totalhoursworked',
                        'hr_payrollv2history.amountperday',
                        'hr_payrollv2history.netsalary',
                        'hr_payrollv2history.totalearning',
                        'hr_payrollv2history.totaldeduction',
                        'hr_payrollv2history.monthlysalary',
                        'hr_payrollv2history.releaseddatetime',
                        'teacher.lastname',
                        'teacher.middlename',
                        'teacher.firstname',
                        'teacher.suffix',
                        'teacher.title',
                        'teacher.tid',
                        'employee_personalinfo.gender',
                        'usertype.utype',
                        'teacher.picurl'
                        )
                    ->join('teacher','hr_payrollv2history.employeeid','=','teacher.id')
                    ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                    ->join('usertype','teacher.id','=','usertype.id')
                    ->where('hr_payrollv2history.payrollid', $request->get('payrollid'))
                    ->where('hr_payrollv2history.deleted','0')
                    ->where('hr_payrollv2history.released','1')
                    ->orderBy('lastname','asc')
                    ->get();

                return view('hr.payroll.v3.summary_results')
                    ->with('histories', $histories); 
            }elseif($request->get('action') == 'getdetails')
            {
                $history = DB::table('hr_payrollv2history')
                    ->select(
                        'hr_payrollv2history.id',
                        'hr_payrollv2history.employeeid',
                        'hr_payrollv2history.presentdays',
                        'hr_payrollv2history.absentdays',
                        'hr_payrollv2history.basicsalaryamount',
                        'hr_payrollv2history.basicsalarytype',
                        'hr_payrollv2history.daysabsentamount',
                        'hr_payrollv2history.lateamount',
                        'hr_payrollv2history.undertimeminutes',
                        'hr_payrollv2history.totalhoursworked',
                        'hr_payrollv2history.amountperday',
                        'hr_payrollv2history.netsalary',
                        'hr_payrollv2history.totalearning',
                        'hr_payrollv2history.totaldeduction',
                        'hr_payrollv2history.monthlysalary',
                        'hr_payrollv2history.releaseddatetime',
                        'teacher.lastname',
                        'teacher.middlename',
                        'teacher.firstname',
                        'teacher.suffix',
                        'teacher.title',
                        'teacher.tid',
                        'employee_personalinfo.gender',
                        'usertype.utype',
                        'teacher.picurl'
                        )
                    ->join('teacher','hr_payrollv2history.employeeid','=','teacher.id')
                    ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                    ->join('usertype','teacher.id','=','usertype.id')
                    ->where('hr_payrollv2history.id', $request->get('historyid'))
                    ->orderBy('lastname','asc')
                    ->first();

                $particulars = DB::table('hr_payrollv2historydetail')
                        ->where('headerid',$request->get('historyid'))
                        ->where('deleted','0')
                        ->get();

                $addedparticulars = DB::table('hr_payrollv2addparticular') 
                    ->where('headerid',$request->get('historyid'))
                    ->where('deleted','0')
                    ->get();

                return view('hr.payroll.v3.summary_details')
                    ->with('history', $history)
                    ->with('particulars', $particulars)
                    ->with('addedparticulars', $addedparticulars); 
            }
        }
    }

}
