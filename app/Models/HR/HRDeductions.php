<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

use DB;
use Crypt;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use App\MoneyCurrency;
use PDF;
use DateTime;
use DateInterval;
use DatePeriod;
use Conversion;
class HRDeductions extends Model
{
    public static function getstandarddeductions($employeeid,$payrollinfo, $deductionid)
    {
        // return $payrollinfo;
        if ($payrollinfo) {

            $datefrom_day = date('d', strtotime($payrollinfo->datefrom));
            $dateto_day = date('d', strtotime($payrollinfo->dateto));
            $payrollyear = date('Y', strtotime($payrollinfo->dateto));
            $payrollmonth = date('m', strtotime($payrollinfo->dateto));
            
            if ($datefrom_day == 21 && $dateto_day == 05) {
                $cutoff = 1; // first cutoff of the month
            } else {
                $cutoff = 2; // second cutoff of the month
            }
        }

        // return $deductionid;
        $monthlypayroll = DB::table('hr_payrollv2')
            ->select('hr_payrollv2history.*','hr_payrollv2.datefrom','hr_payrollv2.dateto')
            ->join('hr_payrollv2history','hr_payrollv2.id','=','hr_payrollv2history.payrollid')
            ->where(function($query) use ($payrollmonth) {
                $query->whereMonth('hr_payrollv2.dateto', $payrollmonth)
                    ->orWhereMonth('hr_payrollv2.datefrom', $payrollmonth);
            })
            ->where('hr_payrollv2.deleted','0')
            ->where('hr_payrollv2history.deleted','0')
            ->where('hr_payrollv2history.employeeid',$employeeid)
            ->get();
                    
            
        $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
            ->select('employee_basicsalaryinfo.*','employee_basistype.type as salarytype','employee_basistype.type as ratetype')
            ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
            ->where('employee_basicsalaryinfo.deleted','0')
            ->where('employee_basicsalaryinfo.employeeid', $employeeid)
            ->first();

        // return $monthlypayroll;
           
        $balance = DB::table('hr_deductionbalance')
            // ->select(DB::raw("SUM(balance) as balance"))
            ->where('employeeid', $employeeid)
            ->where('deleted','0')
            ->where('paid','0')
            ->where('deductiontype','0')
            ->get();

        $deductioninfo = DB::table('employee_deductionstandard')
            ->where('employeeid', $employeeid)
            ->where('deduction_typeid', $deductionid)
            ->where('deleted','0')
            ->first();
        
        // return collect($deductioninfo);
        $historyinfo = DB::table('hr_payrollv2history')
            ->select('id','configured','released')
            ->where('hr_payrollv2history.employeeid', $employeeid)
            // ->where('payrollid', $payrollinfo->id)
            ->first();

        // return $historyinfo;
        $paidforthismonth = 0;
        $lock = 0;
        $totalamount = 0;
        $amounttopay = 0;
        $paymenttype = 0; // 0 = full; 1 = half;
        $paidstatus = 0;
        
        if(count($monthlypayroll) == 0)
        {
            if($deductioninfo)
            {
                // $amounttopay = $deductioninfo->eesamount;
                // $totalamount = $deductioninfo->eesamount;

                $amounttopay = $deductioninfo->eesamount;
                $totalamount = $deductioninfo->eesamount;
                $paymenttype = 1;
                $paidstatus = 1;

                // April 10 2024 ako gi comment kay di ko sure para asa ni 
                // if($payrollinfo->dateto > date('Y-m-15', strtotime($payrollinfo->dateto)))
                // {
                //     $lock = 1;
                // }
            }

        }
        elseif(count($monthlypayroll) == 1)
        {
            
            if($historyinfo)
            {

                if($historyinfo->id == $monthlypayroll[0]->id)
                {
                    
                    $deductinfo = DB::table('hr_payrollv2historydetail')
                        ->where('headerid', $historyinfo->id)
                        ->where('particulartype',1)
                        ->where('deleted','0')
                        ->where('particularid',$deductionid)
                        ->first();

                    if($deductinfo)
                    {
                        
                        // return collect($deductinfo);
                        // $paymenttype = $deductinfo->paymenttype;
                        $paymenttype = 1;
                        $amounttopay = $deductinfo->amountpaid;
                        $totalamount = $deductinfo->totalamount;
                        if($deductinfo->paymenttype == 0)
                            {
                                $paidforthismonth = 1;
                            }
                        // if($payrollinfo->dateto > date('Y-m-15', strtotime($payrollinfo->dateto)))
                        // {
                        //     $lock = 1;
                        // }
                        // $lock = 1;
                        $paidstatus = $deductinfo->paidstatus;
                    }
                    else{
                        // $paymenttype = 3;
                        $paymenttype = 1;
                        $amounttopay = $deductioninfo->eesamount;
                        $totalamount = $deductioninfo->eesamount;
                        $paidstatus = 1;
                        
                    }
                    
                }else{
                    
                    if(date('m', strtotime($monthlypayroll[0]->dateto)) == $payrollmonth)
                    // if(date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollinfo->datefrom)))
                    {
                       
                        $deductinfo = DB::table('hr_payrollv2historydetail')
                            ->where('headerid', $monthlypayroll[0]->id)
                            ->where('particulartype',1)
                            ->where('deleted','0')
                            ->where('particularid',$deductionid)
                            ->first();

                        if($deductinfo)
                        {
                            
                            if($deductinfo->paymenttype == 0)
                            {
                                $paidforthismonth = 1;
                            }else{
                                $lock        = 1;
                            }
                            $amounttopay = $deductinfo->totalamount;
                            $totalamount = $deductinfo->totalamount;
                            $paymenttype = $deductinfo->paymenttype;
                            $paidstatus = $deductinfo->paidstatus;
                        }else{
                            
                            // return collect($deductioninfo);
                            if($deductioninfo)
                            {
                                $amounttopay = $deductioninfo->eesamount;
                                $totalamount = $deductioninfo->eesamount;
                                $lock        = 1;
                                $paidstatus = 1;
                            }
                        }
                        
                    }else{
                        if($deductioninfo)
                        {
                            $amounttopay = $deductioninfo->eesamount;
                            $totalamount = $deductioninfo->eesamount;
                        }
                    }
                }
            }else{
                if(date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollinfo->datefrom)))
                {
                    if ($basicsalaryinfo->salarybasistype == 9) {
                        $deductinfo = DB::table('hr_payrollv2historydetail')
                            ->where('headerid', $monthlypayroll[0]->id)
                            ->where('particulartype',1)
                            ->where('deleted','0')
                            ->where('particularid',$deductionid)
                            // ->where('payrollid', $payrollinfo->id)
                            ->first();
                        
                    } else {
                        $deductinfo = DB::table('hr_payrollv2historydetail')
                            ->where('headerid', $monthlypayroll[0]->id)
                            ->where('particulartype',1)
                            ->where('deleted','0')
                            ->where('particularid',$deductionid)
                            ->first();
                    }
                    // return collect($deductinfo);
                    if($deductinfo)
                    {
                        if($deductinfo->paymenttype == 1)
                        {
                            $paymenttype = 1;
                        }else{
                            $paidforthismonth = 1;
                        }

                        $amounttopay = $deductinfo->totalamount;
                        $totalamount = $deductinfo->totalamount;
                        $lock        = 1;
                        $paidstatus = $deductinfo->paidstatus;

                    }else{
                        if($deductioninfo)
                        {
                            $amounttopay = $deductioninfo->eesamount;
                            $totalamount = $deductioninfo->eesamount;
                            $lock        = 1;
                        }
                    }
                    
                }else{
                    if($deductioninfo)
                    {
                        $amounttopay = $deductioninfo->eesamount;
                        $totalamount = $deductioninfo->eesamount;
                    }
                }
            }

        }elseif(count($monthlypayroll) == 2)
        {
            
            $deductinfo = DB::table('hr_payrollv2historydetail')
                ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollinfo->id)->first()->id)
                ->where('particulartype',1)
                ->where('deleted','0')
                ->where('particularid',$deductionid)
                ->first();
            
            if ($basicsalaryinfo->salarybasistype == 5 || $basicsalaryinfo->salarytype == 'Daily' ) {
                // return collect($monthlypayroll)->where('payrollid', $payrollinfo->id)->first()->id;
                $deductinfo = DB::table('hr_payrollv2historydetail')
                    ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollinfo->id)->first()->id)
                    ->where('particulartype',1)
                    ->where('deleted','0')
                    ->where('particularid',$deductionid)
                    ->first();
            } else {
                
                $deductinfo = DB::table('hr_payrollv2historydetail')
                    ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollinfo->id)->first()->id)
                    ->where('particulartype',1)
                    ->where('deleted','0')
                    ->where('particularid',$deductionid)
                    ->first();
            }
            
            if($deductinfo)
            {
                if(collect($monthlypayroll)->where('payrollid', $payrollinfo->id)->first()->released == 1)
                {
                    if($deductinfo)
                    {
                        if($deductinfo->paymenttype == 1)
                        {
                            $paymenttype = 1;
                        }else{
                            $paidforthismonth = 1;
                        }
                        $amounttopay = $deductinfo->amountpaid;
                        $totalamount = $deductinfo->totalamount;
                        $lock        = 1;
                        $paidstatus = $deductinfo->paidstatus;
                    }
    
                }else{
                    if($deductinfo)
                    {
                        if($deductinfo->paymenttype == 1)
                        {
                            $paymenttype = 1;
                        }else{
                            $paidforthismonth = 1;
                        }
                        $amounttopay = $deductinfo->amountpaid;
                        $totalamount = $deductinfo->totalamount;
                        $lock        = 1;
                        $paidstatus = $deductinfo->paidstatus;
                    }else{
                        if($allowanceinfo)
                        {
                            $amounttopay = $deductioninfo->eesamount;
                            $totalamount = $deductioninfo->eesamount;
                            $lock        = 1;
                            $paidstatus = $deductinfo->paidstatus;
                        }
    
                    }
                }
            }else{

                // $deductinfo = DB::table('hr_payrollv2historydetail')
                //     ->where('headerid', collect($monthlypayroll)->where('payrollid', '!=', $payrollinfo->id)->first()->id)
                //     ->where('particulartype',1)
                //     ->where('deleted','0')
                //     ->where('particularid',$deductionid)
                //     ->first();

                $monthlyPayrollFiltered = collect($monthlypayroll)->where('payrollid', '!=', $payrollinfo->id)->first();

                if ($monthlyPayrollFiltered) {
                    $deductinfo = DB::table('hr_payrollv2historydetail')
                                    ->where('headerid', $monthlyPayrollFiltered->id)
                                    ->where('particulartype', 1)
                                    ->where('deleted', '0')
                                    ->where('particularid', $deductionid)
                                    ->first();
                } else {
                    $deductinfo = null;
                }
                   
                    
                if($deductinfo)
                {
                    if($deductinfo->paymenttype == 0)
                    {
                        $paidforthismonth = 1;
                        $amounttopay = $deductinfo->amountpaid;
                        $totalamount = $deductinfo->totalamount;
                        $lock        = 1;
                        $paidstatus = $deductinfo->paidstatus;
                    }
                }else{
                    // return collect($deductioninfo);
                    $paidforthismonth = 0;
                    $amounttopay = $deductioninfo->eesamount;
                    $totalamount = $deductioninfo->eesamount;
                    $lock        = 1;
                    $paidstatus = $deductinfo->paidstatus;
                    // $paidstatus = 1;
                }
            }

        }


        // if(count($balance) == 0)
        // {
        //     if($deductioninfo)
        //     {
        //         $amounttopay = $deductioninfo->eesamount;
        //     }
        // }else{
        //     $amounttopay = collect($balance)->whereMonth('createdatetime',date('m', strtotime($payrollinfo->datefrom)))->sum('balance');
        // }
        
        $deductioninfo = (object)array(
            'lock'    => $lock,
            'paidforthismonth'    => $paidforthismonth,
            'totalamount'    => $totalamount,
            'amount'    => $amounttopay,
            'paymenttype'    => $paymenttype,
            'balances'    => $balance,
            'paidstatus'  => $paidstatus
        );

        return $deductioninfo;

        
    }
    public static function updatestandarddeductions($employeeid, $monthlysalary, $salarytype)
    {
        $deductionsetup = DB::table('employee_basicsalaryinfo')
            ->where('employeeid', $employeeid)
            ->where('deleted','0')
            ->first()
            ->deductionsetup;
        
            
        $deductiontypes = Db::table('deduction_standard')
        ->where('deleted','0')
        ->get();
        
        $mystandarddeductions = array();

        $monthlypi      = 0;
        $monthlypierrate    = 0;

        $monthlyph = 0;
        // $monthlypherrate = 0;

        $monthlyss = 0;
        $monthlyerssamount = 0;

        $monthlywt = 0;

        // if($deductiontypes[0]->id == '1'){                                

        foreach($deductiontypes as $deductiontype){

            $mydeductions = Db::table('employee_deductionstandard')
                ->select(
                    'employee_deductionstandard.id as contributiondetailid',
                    'employee_deductionstandard.ersamount',
                    'employee_deductionstandard.eesamount',
                    'employee_deductionstandard.status'
                    )
                ->where('employee_deductionstandard.employeeid',$employeeid)
                ->where('employee_deductionstandard.deduction_typeid',$deductiontype->id)
                ->where('employee_deductionstandard.deleted','0')
                ->get();
                
            if($deductiontype->constant == 0){

                if(count($mydeductions) > 0){

                    array_push($mystandarddeductions, (object)array(
                        'contributionid'        => $deductiontype->id,
                        'description'           => $deductiontype->description,
                        'contributiondetailid'  => $mydeductions[0]->contributiondetailid,
                        'ersamount'             => $mydeductions[0]->ersamount,
                        'eesamount'             => $mydeductions[0]->eesamount,
                        'status'                => $mydeductions[0]->status
                    ));
                }

            }else{
                if($deductiontype->id == 1){

                    $getmonthlyamountpi = Db::table('hr_bracketpi')
                        ->where('deleted','0')
                        ->get();
                        
                    foreach($getmonthlyamountpi as $piamount){
    
                        if($piamount->rangeto == 0.00){
    
                            if($monthlysalary >= $piamount->rangefrom){
    
                                $monthlypi += ($monthlysalary * ($piamount->eescrate/100));
                                $monthlypierrate += $piamount->erscrate;
                                
                            }
    
                        }else{
                            
                            if($monthlysalary >= $piamount->rangefrom && $monthlysalary <= $piamount->rangeto){
                                
                                $monthlypi += ($monthlysalary * ($piamount->eescrate/100));
                                $monthlypierrate += $piamount->erscrate;
                                // return $monthlypi;
    
                            }
    
                        }
    
                    }

                    if(count($mydeductions) > 0){
                        
                        $checkpiifexists = Db::table('employee_deductionstandard')
                            ->where('employeeid', $employeeid)
                            ->where('deduction_typeid', 1)
                            ->get();

                        if(count($checkpiifexists) == 0){
                            Db::table('employee_deductionstandard')
                                ->insert([
                                    'employeeid'        => $employeeid,
                                    'deduction_typeid'  => '1',
                                    'eesamount'         => $monthlypi,
                                    'ersamount'             => ($monthlypi*$monthlypierrate)
                                ]);
    
                            $getpistatus = Db::table('employee_deductionstandard')
                                ->where('employeeid', $employeeid)
                                ->where('deduction_typeid', 1)
                                ->first()
                                ->status;
                            array_push($mystandarddeductions, (object)array(
                                'contributionid'        => $deductiontypes[0]->id,
                                'description'           => $deductiontypes[0]->description,
                                'contributiondetailid'  => '',
                                'ersamount'             => ($monthlypi*$monthlypierrate),
                                'eesamount'             => $monthlypi,
                                'status'                => $getpistatus
                            ));
                        }else{
                            Db::table('employee_deductionstandard')
                                ->where('employeeid', $employeeid)
                                ->where('deduction_typeid', 1)
                                ->update([
                                    'eesamount'             => $monthlypi,
                                    'ersamount'             => ($monthlypi*$monthlypierrate),
                                ]);
    
                            $getpistatus = Db::table('employee_deductionstandard')
                                ->where('employeeid', $employeeid)
                                ->where('deduction_typeid', 1)
                                ->first()
                                ->status;
    
                            array_push($mystandarddeductions, (object)array(
                                'contributionid'        => $deductiontypes[0]->id,
                                'description'           => $deductiontypes[0]->description,
                                'contributiondetailid'  => '',
                                'ersamount'             => ($monthlypi*$monthlypierrate),
                                'eesamount'             => $monthlypi,
                                'status'                => $getpistatus
                            ));
                        }

                    }

                }
                if($deductiontype->id == 2){
                    
                    $getmonthlyamountph = Db::table('hr_bracketph')
                        ->join('hr_bracketphdetail','hr_bracketph.id','=','hr_bracketphdetail.bracketphid')
                        ->where('hr_bracketph.year',date('Y'))
                        ->where('hr_bracketph.deleted','0')
                        ->get();

                    $premiumrate = 0;
                        
                    foreach($getmonthlyamountph as $phamount){

                        if($phamount->rangeto == 0.00){

                            if($monthlysalary >= $phamount->rangefrom){

                                $monthlyph += (($monthlysalary * ($phamount->premiumrate/100))/2);

                            }

                        }else{

                            if($monthlysalary >= $phamount->rangefrom && $monthlysalary <= $phamount->rangeto){

                                $monthlyph += (($monthlysalary * ($phamount->premiumrate/100))/2);

                            }

                        }
                    }

                    if(count($mydeductions) == 0){


                    }else{
                        
                        $checkphifexists = Db::table('employee_deductionstandard')
                            ->where('employeeid', $employeeid)
                            ->where('deduction_typeid', 2)
                            ->get();

                        if(count($checkphifexists) == 0){
                            Db::table('employee_deductionstandard')
                                ->insert([
                                    'employeeid'        => $employeeid,
                                    'deduction_typeid'  => '2',
                                    'ersamount'         => $monthlyph,
                                    'eesamount'         => $monthlyph
                                ]);
    
                            $getphstatus = Db::table('employee_deductionstandard')
                                ->where('employeeid', $employeeid)
                                ->where('deduction_typeid', 2)
                                ->first()
                                ->status;
                            array_push($mystandarddeductions, (object)array(
                                'contributionid'        => $deductiontypes[1]->id,
                                'description'           => $deductiontypes[1]->description,
                                'contributiondetailid'  => '',
                                'ersamount'             => $monthlyph,
                                'eesamount'             => $monthlyph,
                                'status'                => $getphstatus
                            ));
                        }else{
                            Db::table('employee_deductionstandard')
                                ->where('employeeid', $employeeid)
                                ->where('deduction_typeid', 2)
                                ->update([
                                    'ersamount'             => $monthlyph,
                                    'eesamount'             => $monthlyph
                                ]);
    
                            $getphstatus = Db::table('employee_deductionstandard')
                                ->where('employeeid', $employeeid)
                                ->where('deduction_typeid', 2)
                                ->first()
                                ->status;
    
                            array_push($mystandarddeductions, (object)array(
                                'contributionid'        => $deductiontypes[1]->id,
                                'description'           => $deductiontypes[1]->description,
                                'contributiondetailid'  => '',
                                'ersamount'             => $monthlyph,
                                'eesamount'             => $monthlyph,
                                'status'                => $getphstatus
                            ));
                        }


                    }
                }
                if($deductiontype->id == 3){
                    $getmonthlyamountss = Db::table('hr_bracketss')
                        ->where('hr_bracketss.deleted','0')
                        ->get();
                        
                    foreach($getmonthlyamountss as $ssamount){

                        if($ssamount->rangeto == 0.00){

                            if($monthlysalary >= $ssamount->rangefrom){

                                $monthlyss += $ssamount->eesamount;
                                $monthlyerssamount += $ssamount->ersamount;

                            }

                        }else{

                            if($monthlysalary >= $ssamount->rangefrom && $monthlysalary <= $ssamount->rangeto){

                                $monthlyss += $ssamount->eesamount;
                                $monthlyerssamount += $ssamount->ersamount;


                            }
                        }

                    }


                    if(count($mydeductions) > 0){
                        
                        $checkssifexists = Db::table('employee_deductionstandard')
                            ->where('employeeid', $employeeid)
                            ->where('deduction_typeid', 3)
                            ->get();

                        if(count($checkssifexists) == 0){
                            Db::table('employee_deductionstandard')
                                ->insert([
                                    'employeeid'        => $employeeid,
                                    'deduction_typeid'  => '3',
                                    'ersamount'         => $monthlyerssamount,
                                    'eesamount'         => $monthlywt
                                ]);
    
                            $getssstatus = Db::table('employee_deductionstandard')
                                ->where('employeeid', $employeeid)
                                ->where('deduction_typeid', 3)
                                ->first()
                                ->status;
                            array_push($mystandarddeductions, (object)array(
                                'contributionid'        => $deductiontypes[2]->id,
                                'description'           => $deductiontypes[2]->description,
                                'contributiondetailid'  => '',
                                'ersamount'             => $monthlyerssamount,
                                'eesamount'             => $monthlyss,
                                'status'                => $getssstatus
                            ));
                        }else{
                            Db::table('employee_deductionstandard')
                                ->where('employeeid', $employeeid)
                                ->where('deduction_typeid', 3)
                                ->update([
                                    'ersamount'             => $monthlyerssamount,
                                    'eesamount'             => $monthlyss
                                ]);
    
                            $getssstatus = Db::table('employee_deductionstandard')
                                ->where('employeeid', $employeeid)
                                ->where('deduction_typeid', 3)
                                ->first()
                                ->status;
    
                            array_push($mystandarddeductions, (object)array(
                                'contributionid'        => $deductiontypes[2]->id,
                                'description'           => $deductiontypes[2]->description,
                                'contributiondetailid'  => '',
                                'ersamount'             => $monthlyerssamount,
                                'eesamount'             => $monthlyss,
                                'status'                => $getssstatus
                            ));
                        }
                    }
                }
                if($deductiontype->id == 4){
                    
                    $monthlyleft = ($monthlysalary - ($monthlyss+$monthlypi+$monthlyph));
                    // return $monthlyleft;
                    if(strtolower($salarytype) == 'daily'){
                        $type = 1;
                    }elseif(strtolower($salarytype) == 'monthly'){
                        $type = 4;
                    }elseif(strtolower($salarytype) == 'project'){
                        $type = 8;
                    }elseif(strtolower($salarytype) == 'hourly'){
                        $type = 0;
                    }
                    if($type != 0)
                    {
                    
                        $getmonthlyamountwt = Db::table('hr_bracketwt')
                            ->where('hr_bracketwt.deleted','0')
                            ->where('hr_bracketwt.salarytypeid',$type)
                            ->get();
                        $prescribedamount = 0;
                        $prescribedrate = 0;
                        $prescribedover = 0;
                        // return $getmonthlyamountwt;
                        
                        foreach($getmonthlyamountwt as $monthlyamountwt){
    
                            if($monthlyamountwt->rangeto == 0.00){
    
                                if($monthlyleft >= $monthlyamountwt->rangefrom){
    
                                    $prescribedamount = $monthlyamountwt->prescribeamount;
                                    $prescribedrate = $monthlyamountwt->prescriberate;
                                    $prescribedover = $monthlyamountwt->prescribeover;
    
                                }
    
                            }else{
    
                                if($monthlyleft >= $monthlyamountwt->rangefrom && $monthlyleft <= $monthlyamountwt->rangeto){
                                    $prescribedamount = $monthlyamountwt->prescribeamount;
                                    $prescribedrate = $monthlyamountwt->prescriberate;
                                    $prescribedover = $monthlyamountwt->prescribeover;
    
    
                                }
                            }
    
                        }
                        
                        if($monthlysalary >= 90000.00){
    
                            $monthlyleft = $monthlyleft+833.33;
    
                        }
                        
                        $monthlywt = (($monthlyleft - $prescribedover) * ($prescribedrate/100)) + $prescribedamount;
                        if(count($mydeductions) == 0){
    
                        }else{
                            
                            $checkwtifexists = Db::table('employee_deductionstandard')
                                ->where('employeeid', $employeeid)
                                ->where('deduction_typeid', 4)
                                ->get();
    
                            if(count($checkwtifexists) == 0){
                                Db::table('employee_deductionstandard')
                                    ->insert([
                                        'employeeid'        => $employeeid,
                                        'deduction_typeid'  => '4',
                                        'eesamount'         => $monthlywt
                                    ]);
        
                                $getwtstatus = Db::table('employee_deductionstandard')
                                    ->where('employeeid', $employeeid)
                                    ->where('deduction_typeid', 4)
                                    ->first()
                                    ->status;
                                array_push($mystandarddeductions, (object)array(
                                    'contributionid'        => $deductiontypes[3]->id,
                                    'description'           => $deductiontypes[3]->description,
                                    'contributiondetailid'  => '',
                                    'ersamount'             => '',
                                    'eesamount'             => $monthlywt,
                                    'status'                => $getwtstatus
                                ));
    
                            }else{
                                Db::table('employee_deductionstandard')
                                    ->where('employeeid', $employeeid)
                                    ->where('deduction_typeid', 4)
                                    ->update([
                                        'eesamount'             => $monthlywt
                                    ]);
        
                                $getwtstatus = Db::table('employee_deductionstandard')
                                    ->where('employeeid', $employeeid)
                                    ->where('deduction_typeid', 4)
                                    ->first()
                                    ->status;
                                    
                                array_push($mystandarddeductions, (object)array(
                                    'contributionid'        => $deductiontypes[3]->id,
                                    'description'           => $deductiontypes[3]->description,
                                    'contributiondetailid'  => '',
                                    'ersamount'             => '',
                                    'eesamount'             => $monthlywt,
                                    'status'                => $getwtstatus
                                ));
                            }
                        }
                    }
                }
            }

        }
        return $mystandarddeductions;
    }
    public static function standarddeductions($payrolldatefrom, $payrollid, $employeeid)
    {
        
        $standarddeductions = array();

        $standarddeductionsfullamount = 0;

        $getdeductionstandard = Db::table('deduction_standard')
            ->select(
                'deduction_standard.id',
                'deduction_standard.description',
                'employee_deductionstandard.eesamount'
            )
            ->join('employee_deductionstandard','deduction_standard.id','=','employee_deductionstandard.deduction_typeid')
            ->where('employee_deductionstandard.employeeid',$employeeid)
            ->where('employee_deductionstandard.status','1')
            ->where('deduction_standard.deleted','0')
            ->where('employee_deductionstandard.deleted','0')
            ->distinct()
            ->get();
            
        $row = DB::table('payroll')
            ->orderBy('id','asc')
            ->count();

        $newsid = $row -2;
        $lastpayroll = DB::table('payroll')
        ->orderBy('id','asc')
        ->skip($newsid)->take($newsid)->first();
        
        $currentpayrollmonth = strtolower(date('M', strtotime($payrolldatefrom)));
                
        $lastpayrollmonth = strtolower(date('M', strtotime($lastpayroll->dateto)));

        if(count($getdeductionstandard) > 0){
            
            $checkifreleased = DB::table('payroll_history')
                            ->where('payroll_history.payrollid', $payrollid)
                            ->where('payroll_history.employeeid', $employeeid)
                            ->distinct()
                            ->get();

            $standarddeductionscontainer = array();

            if(count($checkifreleased) == 0){                
                $payrollstatus = 0;

                if($currentpayrollmonth == $lastpayrollmonth && $row != 1)
                {
                    foreach($getdeductionstandard as $deductionstandard){
    
                        $checkifsaved =  DB::table('payroll_historydetail')
                            ->where('payroll_historydetail.payrollid', $lastpayroll->id)
                            ->where('payroll_historydetail.employeeid', $employeeid)
                            ->where('payroll_historydetail.deductionid', $deductionstandard->id)
                            ->where('payroll_historydetail.type', 'standard')
                            ->where('payroll_historydetail.deleted', '0')
                            ->distinct()
                            ->get();
                            
                        if(count($checkifsaved) == 0)
                        {
    
                            $standarddeductionsfullamount+=($deductionstandard->eesamount);
        
                            $deductionstandard->paymentoption = '2';
                            $deductionstandard->saved = '0';
                            $deductionstandard->forcefull = '1';
                            $deductionstandard->paidamount =  ($deductionstandard->eesamount);

                        }else{
                            
                            if($checkifsaved[0]->paymentoption == 1)
                            {
                                $standarddeductionsfullamount+=($checkifsaved[0]->amount);
            
                                $deductionstandard->eesamount = $checkifsaved[0]->amount;
                                $deductionstandard->paymentoption = $checkifsaved[0]->paymentoption;
                                $deductionstandard->saved = '1';
                                $deductionstandard->forcefull = '1';
                                $deductionstandard->paidamount =  $checkifsaved[0]->amount;
                            }
                            elseif($checkifsaved[0]->paymentoption == 2)
                            {
                                // $standarddeductionsfullamount+=($checkifsaved[0]->amount);
                                // $standarddeductionsfullamount+=0;
            
                                $deductionstandard->eesamount = 0.00; //$checkifsaved[0]->amount;
                                $deductionstandard->paymentoption = $checkifsaved[0]->paymentoption;
                                $deductionstandard->saved = '1';
                                $deductionstandard->forcefull = '1';
                                $deductionstandard->paidamount =  $checkifsaved[0]->amount;

                                $payrollstatus = 1;
                            }
                        }
    
                        array_push($standarddeductionscontainer,$deductionstandard);
    
                    }
                    
                    array_push($standarddeductions,(object)array(
                        'standarddeductions' => $standarddeductionscontainer,
                        'payrollstatus'      => $payrollstatus,
                        'fullamount'      => $standarddeductionsfullamount
                    ));
                }else{

                

                    foreach($getdeductionstandard as $deductionstandard){
    
                        $checkifsaved =  DB::table('payroll_historydetail')
                            ->where('payroll_historydetail.payrollid', $payrollid)
                            ->where('payroll_historydetail.employeeid', $employeeid)
                            ->where('payroll_historydetail.deductionid', $deductionstandard->id)
                            ->where('payroll_historydetail.type', 'standard')
                            ->where('payroll_historydetail.deleted', '0')
                            ->distinct()
                            ->get();
                        if(count($checkifsaved) == 0)
                        {
    
                            $standarddeductionsfullamount+=($deductionstandard->eesamount);
        
                            $deductionstandard->paymentoption = '2';
                            $deductionstandard->saved = '0';
                            $deductionstandard->forcefull = '0';
                            $deductionstandard->paidamount =  ($deductionstandard->eesamount);
                        }else{
                            $standarddeductionsfullamount+=($checkifsaved[0]->amount);
        
                            $deductionstandard->eesamount = $checkifsaved[0]->amount;
                            $deductionstandard->paymentoption = $checkifsaved[0]->paymentoption;
                            $deductionstandard->saved = '1';
                            $deductionstandard->forcefull = '0';
                            $deductionstandard->paidamount =  $checkifsaved[0]->amount;
                        }
    
                        array_push($standarddeductionscontainer,$deductionstandard);
    
                    }
                    
                    array_push($standarddeductions,(object)array(
                        'standarddeductions' => $standarddeductionscontainer,
                        'payrollstatus'      => $payrollstatus,
                        'fullamount'      => $standarddeductionsfullamount
                    ));
                }

            }else{

                if($currentpayrollmonth == $lastpayrollmonth && $row != 1)
                {

                    foreach($getdeductionstandard as $deductionstandard){
    
                        $checkifsaved =  DB::table('payroll_historydetail')
                            ->where('payroll_historydetail.payrollid', $lastpayroll->id)
                            ->where('payroll_historydetail.employeeid', $employeeid)
                            ->where('payroll_historydetail.deductionid', $deductionstandard->id)
                            ->where('payroll_historydetail.type', 'standard')
                            ->where('payroll_historydetail.deleted', '0')
                            ->distinct()
                            ->get();
                            
                        if(count($checkifsaved) == 0)
                        {
    
                            $standarddeductionsfullamount+=($deductionstandard->eesamount);
        
                            $deductionstandard->paymentoption = '2';
                            $deductionstandard->saved = '0';
                            $deductionstandard->paidamount =  ($deductionstandard->eesamount);
                            $deductionstandard->forcefull = '1';

                        }else{
                            if($checkifsaved[0]->paymentoption == 1)
                            {
                                $standarddeductionsfullamount+=($checkifsaved[0]->amount);
            
                                $deductionstandard->eesamount = $checkifsaved[0]->amount;
                                $deductionstandard->paymentoption = $checkifsaved[0]->paymentoption;
                                $deductionstandard->saved = '1';
                                $deductionstandard->forcefull = '1';
                                $deductionstandard->paidamount = $checkifsaved[0]->amount;
                            }
                            elseif($checkifsaved[0]->paymentoption == 2)
                            {
                                $standarddeductionsfullamount+=($checkifsaved[0]->amount);
            
                                $deductionstandard->eesamount = $checkifsaved[0]->amount;
                                $deductionstandard->paymentoption = $checkifsaved[0]->paymentoption;
                                $deductionstandard->saved = '1';
                                $deductionstandard->forcefull = '1';
                                $deductionstandard->paidamount = $checkifsaved[0]->amount;
                            }
                        }
    
                        array_push($standarddeductionscontainer,$deductionstandard);
    
                    }
                    
                    array_push($standarddeductions,(object)array(
                        'standarddeductions' => $standarddeductionscontainer,
                        'payrollstatus'      => 0,
                        'fullamount'      => $standarddeductionsfullamount
                    ));
                }else{

                    foreach($getdeductionstandard as $deductionstandard){
    
                        $checkifsaved =  DB::table('payroll_historydetail')
                            ->where('payroll_historydetail.payrollid', $payrollid)
                            ->where('payroll_historydetail.employeeid', $employeeid)
                            ->where('payroll_historydetail.deductionid', $deductionstandard->id)
                            ->where('payroll_historydetail.type', 'standard')
                            ->where('payroll_historydetail.deleted', '0')
                            ->distinct()
                            ->get();
                        if(count($checkifsaved) == 0)
                        {
    
                            $standarddeductionsfullamount+=($deductionstandard->eesamount);
        
                            $deductionstandard->paymentoption = '2';
                            $deductionstandard->saved = '0';
                            $deductionstandard->forcefull = '0';
                            $deductionstandard->paidamount = ($deductionstandard->eesamount);
                        }else{
                            $standarddeductionsfullamount+=($checkifsaved[0]->amount);
        
                            $deductionstandard->eesamount = $checkifsaved[0]->amount;
                            $deductionstandard->paymentoption = $checkifsaved[0]->paymentoption;
                            $deductionstandard->saved = '1';
                            $deductionstandard->forcefull = '0';
                            $deductionstandard->paidamount = $checkifsaved[0]->amount;
                        }
    
                        array_push($standarddeductionscontainer,$deductionstandard);
    
                    }
                    
                    array_push($standarddeductions,(object)array(
                        'standarddeductions' => $standarddeductionscontainer,
                        'payrollstatus'      => 0,
                        'fullamount'      => $standarddeductionsfullamount
                    ));
                }
            }

        }

        return $standarddeductions;

    }
    
    public static function otherdeductions($payrolldatefrom, $payrollid, $employeeid)
    {

        $otherdeductions = array();

        $otherdeductionsfullamount = 0;

        $getdeductionothers = Db::table('employee_deductionother')
            ->select(
                'employee_deductionother.id',
                'employee_deductionother.description',
                'employee_deductionother.amount',
                'employee_deductionother.term'
            )
            ->where('employee_deductionother.employeeid',$employeeid)
            ->where('employee_deductionother.paid','0')
            ->where('employee_deductionother.status','1')
            ->where('employee_deductionother.deleted','0')
            ->get();

        $row = DB::table('payroll')
            ->orderBy('id','asc')
            ->count();

        $newsid = $row -2;

        $lastpayroll = DB::table('payroll')
        ->orderBy('id','asc')
        ->skip($newsid)->take($newsid)->first();
        
        $currentpayrollmonth = strtolower(date('M', strtotime($payrolldatefrom)));
                
        $lastpayrollmonth = strtolower(date('M', strtotime($lastpayroll->dateto)));
        
        if(count($getdeductionothers) > 0){

            $checkifreleased = DB::table('payroll_history')
                            ->where('payroll_history.payrollid', $payrollid)
                            ->where('payroll_history.employeeid', $employeeid)
                            ->distinct()
                            ->get();
                            
            $otherdeductionscontainer = array();

            if(count($checkifreleased) == 0){

                $payrollstatus = 0;
                
                if($currentpayrollmonth == $lastpayrollmonth && $row != 1)
                {
                    // return 'asda';
                    foreach($getdeductionothers as $deductionother)
                    {
            
                        $checkifsaved =  DB::table('payroll_historydetail')
                            ->where('payroll_historydetail.payrollid', $lastpayroll->id)
                            ->where('payroll_historydetail.employeeid', $employeeid)
                            ->where('payroll_historydetail.deductionid', $deductionother->id)
                            ->where('payroll_historydetail.type', 'other')
                            ->where('payroll_historydetail.deleted', '0')
                            ->distinct()
                            ->get();

                            // return $checkifsaved;
                        if(count($checkifsaved) == 0)
                        {
                            $otherdeductionsfullamount+=number_format($deductionother->amount/$deductionother->term,2);
        
                            // $deductionother->amount = ($deductionother->amount/$deductionother->term);
                            $deductionother->paymentoption = '2';
                            $deductionother->saved = '0';
                            $deductionother->forcefull = '1';
                            // return collect($deductionother);
                        }else{
                            
                            
                            if($checkifsaved[0]->paymentoption == 1)
                            {
                                $otherdeductionsfullamount+=number_format($checkifsaved[0]->amount,2);
            
                                $deductionother->amount = number_format($checkifsaved[0]->amount,2);
                                $deductionother->paymentoption = $checkifsaved[0]->paymentoption;
                                $deductionother->saved = '1';
                                $deductionother->forcefull = '1';
                                $deductionother->paidamount =  number_format($checkifsaved[0]->amount,2);
                            }
                            elseif($checkifsaved[0]->paymentoption == 2)
                            {
                                // $standarddeductionsfullamount+=($checkifsaved[0]->amount);
                                // $standarddeductionsfullamount+=0;
            
                                $deductionother->amount = 0.00; //$checkifsaved[0]->amount;
                                $deductionother->paymentoption = $checkifsaved[0]->paymentoption;
                                $deductionother->saved = '1';
                                $deductionother->forcefull = '1';
                                $deductionother->paidamount =  number_format($checkifsaved[0]->amount,2);
                                $payrollstatus = 1;
                            }
                        }
    
                        array_push($otherdeductionscontainer,$deductionother);
        
                    }
                    
                    array_push($otherdeductions,(object)array(
                        'otherdeductions' => $otherdeductionscontainer,
                        'payrollstatus'      => $payrollstatus,
                        'fullamount'      => $otherdeductionsfullamount
                    ));
                }else{
                    
                    foreach($getdeductionothers as $deductionother)
                    {
            
                        $checkifsaved =  DB::table('payroll_historydetail')
                            ->where('payroll_historydetail.payrollid', $payrollid)
                            ->where('payroll_historydetail.employeeid', $employeeid)
                            ->where('payroll_historydetail.deductionid', $deductionother->id)
                            ->where('payroll_historydetail.type', 'other')
                            ->where('payroll_historydetail.deleted', '0')
                            ->distinct()
                            ->get();
                            
                        if(count($checkifsaved) == 0)
                        {
                            // $floatnum = (21351/$deductionother->term);
                            // return number_format($deductionother->amount/$deductionother->term,2);
                            $otherdeductionsfullamount+=round($deductionother->amount/$deductionother->term,2);
                            
                            $deductionother->amount = round($deductionother->amount/$deductionother->term,2);
                            $deductionother->paymentoption = '2';
                            $deductionother->saved = '0';
                            $deductionother->forcefull = '0';

                        }else{
                            // $otherdeductionsfullamount+=($checkifsaved[0]->amount);
        
                            // $deductionother->amount = $checkifsaved[0]->amount;
                            // $deductionother->paymentoption = $checkifsaved[0]->paymentoption;
                            // $deductionother->saved = '1';
                            
                            $otherdeductionsfullamount+=round($checkifsaved[0]->amount,2);
                            
                            $deductionother->amount = number_format($checkifsaved[0]->amount,2);
                            $deductionother->paymentoption = $checkifsaved[0]->paymentoption;
                            $deductionother->saved = '1';
                            $deductionother->forcefull = '0';
                        }
    
                        array_push($otherdeductionscontainer,$deductionother);
        
                    }
                    
                    array_push($otherdeductions,(object)array(
                        'otherdeductions' => $otherdeductionscontainer,
                        'payrollstatus'      => $payrollstatus,
                        'fullamount'      => number_format($otherdeductionsfullamount,2)
                    ));
                }
            }else{

                if($currentpayrollmonth == $lastpayrollmonth && $row != 1)
                {

                    foreach($getdeductionothers as $deductionother)
                    {
            
                        $checkifsaved =  DB::table('payroll_historydetail')
                            ->where('payroll_historydetail.payrollid', $lastpayroll->id)
                            ->where('payroll_historydetail.employeeid', $employeeid)
                            ->where('payroll_historydetail.deductionid', $deductionother->id)
                            ->where('payroll_historydetail.type', 'other')
                            ->where('payroll_historydetail.deleted', '0')
                            ->distinct()
                            ->get();
    
                        if(count($checkifsaved) == 0)
                        {
    
                            $otherdeductionsfullamount+=number_format($deductionother->amount/$deductionother->term,2);
        
                            $deductionother->amount = number_format($deductionother->amount/$deductionother->term,2);
                            $deductionother->paymentoption = '2';
                            $deductionother->saved = '0';
                            $deductionother->paidamount =  number_format($deductionother->amount/$deductionother->term,2);
                            $deductionstandard->forcefull = '1';
                        }else{
                            
                            
                            if($checkifsaved[0]->paymentoption == 1)
                            {
                                $otherdeductionsfullamount+=number_format($checkifsaved[0]->amount,2);
            
                                $deductionother->amount = number_format($checkifsaved[0]->amount,2);
                                $deductionother->paymentoption = $checkifsaved[0]->paymentoption;
                                $deductionother->saved = '1';
                                $deductionother->paidamount =  number_format($checkifsaved[0]->amount,2);
                                $deductionother->forcefull = '1';
                            }
                            elseif($checkifsaved[0]->paymentoption == 2)
                            {
                                // $standarddeductionsfullamount+=($checkifsaved[0]->amount);
                                // $standarddeductionsfullamount+=0;
            
                                $deductionother->amount = 0.00; //$checkifsaved[0]->amount;
                                $deductionother->paymentoption = $checkifsaved[0]->paymentoption;
                                $deductionother->saved = '1';
                                $deductionother->paidamount =  number_format($checkifsaved[0]->amount,2);
                                $deductionother->forcefull = '1';

                            }
                        }
    
                        array_push($otherdeductionscontainer,$deductionother);
        
                    }
                    
                    array_push($otherdeductions,(object)array(
                        'otherdeductions' => $otherdeductionscontainer,
                        'payrollstatus'      => 0,
                        'fullamount'      => $otherdeductionsfullamount
                    ));
                }else{
                    foreach($getdeductionothers as $deductionother)
                    {
            
                        $checkifsaved =  DB::table('payroll_historydetail')
                            ->where('payroll_historydetail.payrollid', $payrollid)
                            ->where('payroll_historydetail.employeeid', $employeeid)
                            ->where('payroll_historydetail.deductionid', $deductionother->id)
                            ->where('payroll_historydetail.type', 'other')
                            ->where('payroll_historydetail.deleted', '0')
                            ->distinct()
                            ->get();
                            
                        if(count($checkifsaved) == 0)
                        {
    
                            $otherdeductionsfullamount+=number_format($deductionother->amount/$deductionother->term,2);
        
                            $deductionother->amount = number_format($deductionother->amount/$deductionother->term,2);
                            $deductionother->paymentoption = '2';
                            $deductionother->saved = '0';
                            $deductionother->forcefull = '0';
                            $deductionother->paidamount = number_format($deductionother->amount,2);

                        }else{
                            // $otherdeductionsfullamount+=($checkifsaved[0]->amount);
        
                            // $deductionother->amount = $checkifsaved[0]->amount;
                            // $deductionother->paymentoption = $checkifsaved[0]->paymentoption;
                            // $deductionother->saved = '1';
                            
                            $otherdeductionsfullamount+=round($checkifsaved[0]->amount,2);
        
                            $deductionother->amount = $checkifsaved[0]->amount;
                            $deductionother->paymentoption = $checkifsaved[0]->paymentoption;
                            $deductionother->saved = '1';
                            $deductionother->forcefull = '0';
                            $deductionother->paidamount = number_format($checkifsaved[0]->amount,2);
                        }
    
                        array_push($otherdeductionscontainer,$deductionother);
        
                    }
                    
                    array_push($otherdeductions,(object)array(
                        'otherdeductions' => $otherdeductionscontainer,
                        'payrollstatus'      => 0,
                        'fullamount'      => number_format($otherdeductionsfullamount,2)
                    ));
                }
            }
        }

                // $paidsofar = 0;

                // $checkpaid = Db::table('payroll_historydetail')
                //         ->where('payrollid', '!=',$payrollid)
                //         ->where('deductionid', $deductionother->id)
                //         ->where('type', 'otherdeduction')
                //         ->get();

                // if(count($checkpaid) == 0)
                // {
                //     $deductionother->deductionpermonth = $deductionother->amount/$deductionother->term;

                // }else{

                // }

                // return collect($deductionother);

                // if(count($checkpaid)>0)
                // {
                //     foreach($checkpaid as $paid)
                //     {
                //         $paidsofar+=$paid->amount;
                //     }
                // }
                // if($paidsofar >= $deductionother->amount)
                // {
                //     Db::table('employee_deductionother')
                //         ->where('id', $deductionother->id)
                //         ->update([
                //             'paid' => 1
                //         ]);
                //     // Db::table('employee_deductionotherdetail')
                //     //     ->where('headerid', $deductionother->id)
                //     //     ->update([
                //     //         'paid' => 1
                //     //     ]);
                // }
                // else{
                //     array_push($otherdeductionsarray, $deductionother);

                // }
       
        return $otherdeductions;
    }
}
