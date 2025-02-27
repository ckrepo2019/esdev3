<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class HREmployeeSalarySettingController extends Controller
{
    public function employeesalaryupdate(Request $request){

        date_default_timezone_set('Asia/Manila');
        // return $request->all();

        $getpayrolldates        = DB::table('payroll')
                                ->where('id', $request->get('payrollid'))
                                ->first();
        
        $employeeid             =   $request->get('employeeid');
        $payrollid              =   $request->get('payrollid');
        $payrolldatefrom        =   $getpayrolldates->datefrom;
        $payrolldateto          =   $getpayrolldates->dateto;
        $basicpay               =   str_replace( ',', '', $request->get('basicpay'));
        $ratetype               =   $request->get('ratetype');
        $projectbasedtype       =   $request->get('projectbasedtype');
        $attendancesalary       =   str_replace( ',', '', $request->get('attendancesalary'));

        $numofabsentdays        =   $request->get('numofabsentdays');
        $absentdeduction        =   str_replace( ',', '', $request->get('absentdeduction'));

        $overtimepay            =   str_replace( ',', '', $request->get('overtimepay'));
        $holidaypay             =   str_replace( ',', '', $request->get('holidaypay'));
        $holidayovertimepay     =   str_replace( ',', '', $request->get('holidayovertimepay'));

        $leavesnumdays          =   $request->get('leavesnumdays');
        $leaveid                =   $request->get('leaveid');
        $earnedleaves           =   str_replace( ',', '', $request->get('earnedleaves'));
        $deductedleaves         =   str_replace( ',', '', $request->get('deductedleaves'));
        $tardiness              =   str_replace( ',', '', $request->get('tardiness'));
        // return $request->get('exists');
        $totalearnings          =   str_replace( ',', '', $request->get('totalearnings'));
        $totaldeductions        =   str_replace( ',', '', $request->get('totaldeductions'));
        $netpay                 =   str_replace( ',', '', $request->get('netpay'));
        // return $earnedleaves;
        if($request->get('exists') == 0){

            $totalearnings      = 0;
            $totaldeductions    = 0;

            $payrollhistoryid = Db::table('payroll_history')
                                    ->insertGetId([
                                        'employeeid'           => $employeeid,
                                        'payrollid'            => $payrollid,
                                        'payrolldatefrom'      => $payrolldatefrom,
                                        'payrolldateto'        => $payrolldateto,
                                        // 'netpay'               => $netpay,
                                        'basicpay'             => $basicpay,
                                        'ratetype'             => $ratetype,
                                        'projectbasedtype'     => $projectbasedtype,
                                        'attendancesalary'     => $attendancesalary,
                                        'tardinessamount'      => $tardiness,
                                        'numofdaysabsent'      => $numofabsentdays,
                                        'absenttotalamount'    => $absentdeduction,
                                        // 'totalearnings'        => $totalearnings,
                                        // 'totaldeductions'      => $totaldeductions,
                                        'overtimepay'          => $overtimepay,
                                        'holidaypay'           => $holidaypay,
                                        'holidayovertimepay'   => $holidayovertimepay
                                    ]);


                                    $totalearnings             += $attendancesalary;
                                    $totalearnings             += $overtimepay;
                                    $totalearnings             += $holidaypay;
                                    $totalearnings             += $holidayovertimepay;
                                    $totalearnings             += $earnedleaves;
                                    $totalearnings             += $holidayovertimepay;

                                    $totaldeductions           += $tardiness;
                                    $totaldeductions           += $deductedleaves;



            if($earnedleaves != 0.00){

                $getleavedescription = DB::table('hr_leaves')
                                    ->where('id',$leaveid)
                                    ->first();

                             
                DB::table('payroll_historydetail')
                                    ->insert([
                                        'headerid'             => $payrollhistoryid,
                                        'employeeleaveid'      => $leaveid,
                                        'description'          => $getleavedescription->leave_type,
                                        'amount'               => $earnedleaves,
                                        'type'                 => 'earnedleave',
                                        'dateissued'           => date('Y-m-d H:i:s')
                                    ]);
            }
            if($deductedleaves != 0.00){

                $getleavedescription = DB::table('hr_leaves')
                                    ->where('id',$leaveid)
                                    ->first();

                             
                DB::table('payroll_historydetail')
                                    ->insert([
                                        'headerid'             => $payrollhistoryid,
                                        'employeeleaveid'      => $leaveid,
                                        'description'          => $getleavedescription->leave_type,
                                        'amount'               => $deductedleaves,
                                        'type'                 => 'deductedleave',
                                        'dateissued'           => date('Y-m-d H:i:s')
                                    ]);
            }

            if($request->get('standardallowanceids') == true){
            
                foreach($request->get('standardallowanceids') as $standardallowance){
    
                    $standardallowanceid                           = intval($standardallowance);
                    // return str_replace($standardallowanceid, '', $standardallowance);
                    if(str_replace($standardallowanceid, '', $standardallowance) == 'half'){
    
                        $paymentoption                             = 1;
    
                    }elseif(str_replace($standardallowanceid, '', $standardallowance) == 'full'){
    
                        $paymentoption                             = 2;
    
                    }  
    
                    $allowancedescription                          = Db::table('allowance_standard')
                                                                    ->where('id', $standardallowanceid)
                                                                    ->first()
                                                                    ->description;
                                                                        
                    $totalearnings                                  += $request->get('standardallowance'.$standardallowanceid);

                    DB::table('payroll_historydetail')
                                        ->insert([
                                            'headerid'             => $payrollhistoryid,
                                            'allowanceid'          => $standardallowanceid,
                                            'paymentoption'        => $paymentoption,
                                            'description'          => $allowancedescription,
                                            'amount'               => $request->get('standardallowance'.$standardallowanceid),
                                            'type'                 => 'standardallowance',
                                            'dateissued'           => date('Y-m-d H:i:s')
                                        ]);
    
                }

            }

            if($request->get('otherallowanceids') == true){

                foreach($request->get('otherallowanceids') as $otherallowance){
    
                    $otherallowanceid                              = intval($otherallowance);
    
                    if(str_replace($otherallowanceid, '', $otherallowance) == 'half'){
    
                        $paymentoption                             = 1;
    
                    }elseif(str_replace($otherallowanceid, '', $otherallowance) == 'full'){
    
                        $paymentoption                             = 2;
    
                    }            
    
                    $allowancedescription                          = Db::table('employee_allowanceother')
                                                                    ->where('id', $otherallowanceid)
                                                                    ->first()
                                                                    ->description;

                    $totalearnings                                  += $request->get('otherallowance'.$otherallowanceid);

                    DB::table('payroll_historydetail')
                                        ->insert([
                                            'headerid'             => $payrollhistoryid,
                                            'allowanceid'          => $otherallowanceid,
                                            'paymentoption'        => $paymentoption,
                                            'description'          => $allowancedescription,
                                            'amount'               => $request->get('otherallowance'.$otherallowanceid),
                                            'type'                 => 'otherallowance',
                                            'dateissued'           => date('Y-m-d H:i:s')
                                        ]);
    
                }
            }
            
            if($request->get('standarddeductionids') == true){

                foreach($request->get('standarddeductionids') as $standarddeduction){
    
                    $standarddeductionid                           = intval($standarddeduction);
    
                    if(str_replace($standarddeductionid, '', $standarddeduction) == 'half'){
    
                        $paymentoption                             = 1;
    
                    }elseif(str_replace($standarddeductionid, '', $standarddeduction) == 'full'){
    
                        $paymentoption                             = 2;
    
                    }  
    
                    $deductiondescription                          = Db::table('deduction_standard')
                                                                    ->where('id', $standarddeductionid)
                                                                    ->first()
                                                                    ->description;

                    $totaldeductions                               += $request->get('standarddeduction'.$standarddeductionid);
                                                                    
                    DB::table('payroll_historydetail')
                                        ->insert([
                                            'headerid'             => $payrollhistoryid,
                                            'deductionid'          => $standarddeductionid,
                                            'paymentoption'        => $paymentoption,
                                            'description'          => $deductiondescription,
                                            'amount'               => $request->get('standarddeduction'.$standarddeductionid),
                                            'type'                 => 'standarddeduction',
                                            'dateissued'           => date('Y-m-d H:i:s')
                                        ]);
    
                }

            }

            if($request->get('otherdeductionids') == true){

                foreach($request->get('otherdeductionids') as $otherdeduction){
    
                    $otherdeductionid                              = intval($otherdeduction);
    
                    if(str_replace($otherdeductionid, '', $otherdeduction) == 'half'){
    
                        $paymentoption                             = 1;
    
                    }elseif(str_replace($otherdeductionid, '', $otherdeduction) == 'full'){
    
                        $paymentoption                             = 2;
    
                    }  
    
                    $deductiondescription                          = Db::table('employee_deductionother')
                                                                    ->where('id', $otherdeductionid)
                                                                    ->first()
                                                                    ->description;
                      
                    $totaldeductions                               += $request->get('otherdeduction'.$otherdeductionid);

                    DB::table('employee_deductionotherdetail')
                                        ->insert([
                                            'headerid'             => $otherdeductionid,
                                            'payrollid'            => $request->get('payrollid'),
                                            'amountpaid'           => $request->get('otherdeduction'.$otherdeductionid),
                                            'datepaid'             => date('Y-m-d H:i:s')
                                        ]);

                    DB::table('payroll_historydetail')
                                        ->insert([
                                            'headerid'             => $payrollhistoryid,
                                            'deductionid'          => $otherdeductionid,
                                            'paymentoption'        => $paymentoption,
                                            'description'          => $deductiondescription,
                                            'amount'               => $request->get('otherdeduction'.$otherdeductionid),
                                            'type'                 => 'otherdeduction',
                                            'dateissued'           => date('Y-m-d H:i:s')
                                        ]);
    
                }

            }

            
            DB::table('payroll_history')
                ->where('id',$payrollhistoryid)
                ->where('employeeid',$employeeid)
                ->update([
                    'netpay'            => $totalearnings - $totaldeductions,
                    'totalearnings'     => $totalearnings,
                    'totaldeductions'   => $totaldeductions
                ]);



        }elseif($request->get('exists') == 1){
            // return $request->all();
            $totalearnings      = 0;
            $totaldeductions    = 0;

            $payrollhistoryid                                  = DB::table('payroll_history')
                                                                ->where('employeeid',$employeeid)
                                                                ->where('payrollid',$payrollid)
                                                                ->first()
                                                                ->id;
                                                                
                                    $totalearnings             += $attendancesalary;
                                    $totalearnings             += $overtimepay;
                                    $totalearnings             += $holidaypay;
                                    $totalearnings             += $holidayovertimepay;
                                    $totalearnings             += $earnedleaves;
                                    $totalearnings             += $holidayovertimepay;

                                    $totaldeductions           += $tardiness;
                                    $totaldeductions           += $deductedleaves;



            Db::table('payroll_history')
                                    ->where('employeeid',$employeeid)
                                    ->where('payrollid',$payrollid)
                                    ->update([
                                        'employeeid'           => $employeeid,
                                        'payrollid'            => $payrollid,
                                        'payrolldatefrom'      => $payrolldatefrom,
                                        'payrolldateto'        => $payrolldateto,
                                        // 'netpay'               => $netpay,
                                        'basicpay'             => $basicpay,
                                        'ratetype'             => $ratetype,
                                        'projectbasedtype'     => $projectbasedtype,
                                        'attendancesalary'     => $attendancesalary,
                                        'tardinessamount'      => $tardiness,
                                        'numofdaysabsent'      => $numofabsentdays,
                                        'absenttotalamount'    => $absentdeduction,
                                        // 'totalearnings'        => $totalearnings,
                                        // 'totaldeductions'      => $totaldeductions,
                                        'overtimepay'          => $overtimepay,
                                        'holidaypay'           => $holidaypay,
                                        'holidayovertimepay'   => $holidayovertimepay
                                    ]);

            if($earnedleaves != 0.00){

                $getleavedescription = DB::table('hr_leaves')
                                    ->where('id',$leaveid)
                                    ->first();

                $checkearnedleaveifexists                       = DB::table('payroll_historydetail')
                                                                ->where('headerid', $payrollhistoryid)
                                                                ->where('employeeleaveid', $leaveid)
                                                                ->where('type', 'earnedleave')
                                                                ->get();

                if(count($checkearnedleaveifexists) == 0){

                             
                    DB::table('payroll_historydetail')
                                    ->insert([
                                        'headerid'             => $payrollhistoryid,
                                        'employeeleaveid'      => $leaveid,
                                        'description'          => $getleavedescription->leave_type,
                                        'amount'               => $earnedleaves,
                                        'type'                 => 'earnedleave',
                                        'dateissued'           => date('Y-m-d H:i:s')
                                    ]);
                }else{

                    
                    DB::table('payroll_historydetail')
                                    ->where('headerid', $payrollhistoryid)
                                    ->where('employeeleaveid', $leaveid)
                                    ->where('type', 'earnedleave')
                                    ->update([
                                        'description'          => $getleavedescription->leave_type,
                                        'amount'               => $earnedleaves
                                    ]);

                }
            }
            if($deductedleaves != 0.00){

                $getleavedescription = DB::table('hr_leaves')
                                    ->where('id',$leaveid)
                                    ->first();

                $checkdeductedleaveifexists                      = DB::table('payroll_historydetail')
                                                                ->where('headerid', $payrollhistoryid)
                                                                ->where('employeeleaveid', $leaveid)
                                                                ->where('type', 'deductedleave')
                                                                ->get();

                if(count($checkdeductedleaveifexists) == 0){

                             
                    DB::table('payroll_historydetail')
                                    ->insert([
                                        'headerid'             => $payrollhistoryid,
                                        'employeeleaveid'      => $leaveid,
                                        'description'          => $getleavedescription->leave_type,
                                        'amount'               => $deductedleaves,
                                        'type'                 => 'deductedleave',
                                        'dateissued'           => date('Y-m-d H:i:s')
                                    ]);
                }else{

                    
                    DB::table('payroll_historydetail')
                                    ->where('headerid', $payrollhistoryid)
                                    ->where('employeeleaveid', $leaveid)
                                    ->where('type', 'deductedleave')
                                    ->update([
                                        'description'          => $getleavedescription->leave_type,
                                        'amount'               => $deductedleaves
                                    ]);

                }
            }


            if($request->get('standardallowanceids') == true){

                foreach($request->get('standardallowanceids') as $standardallowance){
                    
                    $standardallowanceid                           = intval($standardallowance);
    
                    if(str_replace($standardallowanceid, '', $standardallowance) == 'half'){
    
                        $paymentoption                             = 1;
    
                    }elseif(str_replace($standardallowanceid, '', $standardallowance) == 'full'){
    
                        $paymentoption                             = 2;
    
                    }
                    
                    $allowancedescription                          = Db::table('allowance_standard')
                                                                    ->where('id', $standardallowanceid)
                                                                    ->first()
                                                                    ->description;
    
                    $checkstandardallowanceifexists                = DB::table('payroll_historydetail')
                                                                    ->where('headerid', $payrollhistoryid)
                                                                    ->where('allowanceid', $standardallowanceid)
                                                                    ->where('type', 'standardallowance')
                                                                    ->get();
    
                    $totalearnings                                += $request->get('standardallowance'.$standardallowanceid);

                    if(count($checkstandardallowanceifexists) == 0){
    
                        DB::table('payroll_historydetail')
                                        ->insert([
                                            'headerid'             => $payrollhistoryid,
                                            'allowanceid'          => $standardallowanceid,
                                            'paymentoption'        => $paymentoption,
                                            'description'          => $allowancedescription,
                                            'amount'               => $request->get('standardallowance'.$standardallowanceid),
                                            'type'                 => 'standardallowance',
                                            'dateissued'           => date('Y-m-d H:i:s')
                                        ]);
                    }else{
    
                        
                        DB::table('payroll_historydetail')
                                        ->where('headerid', $payrollhistoryid)
                                        ->where('allowanceid', $standardallowanceid)
                                        ->where('type', 'standardallowance')
                                        ->update([
                                            'paymentoption'        => $paymentoption,
                                            'description'          => $allowancedescription,
                                            'amount'               => $request->get('standardallowance'.$standardallowanceid),
                                            'dateissued'           => date('Y-m-d H:i:s')
                                        ]);
    
                    }
    
                }

            }

            if($request->get('otherallowanceids') == true){

                foreach($request->get('otherallowanceids') as $otherallowance){

                    $otherallowanceid                              = intval($otherallowance);
    
                    if(str_replace($otherallowanceid, '', $otherallowance) == 'half'){
    
                        $paymentoption                             = 1;
    
                    }elseif(str_replace($otherallowanceid, '', $otherallowance) == 'full'){
    
                        $paymentoption                             = 2;
    
                    }
    
                    $allowancedescription                          = Db::table('employee_allowanceother')
                                                                    ->where('id', $otherallowanceid)
                                                                    ->first()
                                                                    ->description;
    
                    $checkotherallowanceifexists                = DB::table('payroll_historydetail')
                                                                    ->where('headerid', $payrollhistoryid)
                                                                    ->where('allowanceid', $otherallowanceid)
                                                                    ->where('type', 'otherallowance')
                                                                    ->get();
    
                    $totalearnings                                += $request->get('otherallowance'.$otherallowanceid);

                    if(count($checkotherallowanceifexists) == 0){
    
                                                                    
                        DB::table('payroll_historydetail')
                                    ->insert([
                                        'headerid'             => $payrollhistoryid,
                                        'allowanceid'          => $otherallowanceid,
                                        'paymentoption'        => $paymentoption,
                                        'description'          => $allowancedescription,
                                        'amount'               => $request->get('otherallowance'.$otherallowanceid),
                                        'type'                 => 'otherallowance',
                                        'dateissued'           => date('Y-m-d H:i:s')
                                    ]);
                    }else{
    
                        
                        DB::table('payroll_historydetail')
                                        ->where('headerid', $payrollhistoryid)
                                        ->where('allowanceid', $otherallowanceid)
                                        ->where('type', 'otherallowance')
                                        ->update([
                                            'paymentoption'        => $paymentoption,
                                            'description'          => $allowancedescription,
                                            'amount'               => $request->get('otherallowance'.$otherallowanceid),
                                            'dateissued'           => date('Y-m-d H:i:s')
                                        ]);
    
                    }
    
                }

            }

            if($request->get('standarddeductionids') == true){

                foreach($request->get('standarddeductionids') as $standarddeduction){

                    $standarddeductionid                           = intval($standarddeduction);
    
                    if(str_replace($standarddeductionid, '', $standarddeduction) == 'half'){
    
                        $paymentoption                             = 1;
    
                    }elseif(str_replace($standarddeductionid, '', $standarddeduction) == 'full'){
    
                        $paymentoption                             = 2;
    
                    }
    
                    $deductiondescription                          = Db::table('deduction_standard')
                                                                    ->where('id', $standarddeductionid)
                                                                    ->first()
                                                                    ->description;
    
                    $checkstandarddeductionifexists                = DB::table('payroll_historydetail')
                                                                    ->where('headerid', $payrollhistoryid)
                                                                    ->where('deductionid', $standarddeductionid)
                                                                    ->where('type', 'standarddeduction')
                                                                    ->get();
                                                                    
                    $totaldeductions                                += $request->get('standarddeduction'.$standarddeductionid);
    
                    if(count($checkstandarddeductionifexists) == 0){
                    
                        DB::table('payroll_historydetail')
                                        ->insert([
                                            'headerid'             => $payrollhistoryid,
                                            'deductionid'          => $standarddeductionid,
                                            'paymentoption'        => $paymentoption,
                                            'description'          => $deductiondescription,
                                            'amount'               => $request->get('standarddeduction'.$standarddeductionid),
                                            'type'                 => 'standarddeduction',
                                            'dateissued'           => date('Y-m-d H:i:s')
                                        ]);
                    }else{
    
                        
                        DB::table('payroll_historydetail')
                                        ->where('headerid', $payrollhistoryid)
                                        ->where('deductionid', $standarddeductionid)
                                        ->where('type', 'standarddeduction')
                                        ->update([
                                            'paymentoption'        => $paymentoption,
                                            'description'          => $deductiondescription,
                                            'amount'               => $request->get('standarddeduction'.$standarddeductionid),
                                            'dateissued'           => date('Y-m-d H:i:s')
                                        ]);
    
                    }
                                                    
    
                }

            }

            if($request->get('otherdeductionids') == true){

                foreach($request->get('otherdeductionids') as $otherdeduction){
    
                    $otherdeductionid                              = intval($otherdeduction);
    
                    if(str_replace($otherdeductionid, '', $otherdeduction) == 'half'){
    
                        $paymentoption                             = 1;
    
                    }elseif(str_replace($otherdeductionid, '', $otherdeduction) == 'full'){
    
                        $paymentoption                             = 2;
    
                    }
    
                    $deductiondescription                          = Db::table('employee_deductionother')
                                                                    ->where('id', $otherdeductionid)
                                                                    ->first()
                                                                    ->description;
    
                    $checkotherdeductionifexists                = DB::table('payroll_historydetail')
                                                                    ->where('headerid', $payrollhistoryid)
                                                                    ->where('deductionid', $otherdeductionid)
                                                                    ->where('type', 'otherdeduction')
                                                                    ->get();
    
                    $totaldeductions                                += $request->get('otherdeduction'.$otherdeductionid);   

                    if(count($checkotherdeductionifexists) == 0){
                                                 
                        DB::table('employee_deductionotherdetail')
                                        ->insert([
                                            'headerid'             => $otherdeductionid,
                                            'amountpaid'           => $request->get('otherdeduction'.$otherdeductionid),
                                            'datepaid'             => date('Y-m-d H:i:s')
                                        ]);    

                        DB::table('payroll_historydetail')
                                            ->insert([
                                                'headerid'             => $payrollhistoryid,
                                                'deductionid'          => $otherdeductionid,
                                                'paymentoption'        => $paymentoption,
                                                'description'          => $deductiondescription,
                                                'amount'               => $request->get('otherdeduction'.$otherdeductionid),
                                                'type'                 => 'otherdeduction',
                                                'dateissued'           => date('Y-m-d H:i:s')
                                            ]);
                    }else{
    
                        DB::table('employee_deductionotherdetail')
                                        ->where('headerid', $otherdeductionid)
                                        ->where('payrollid', $request->get('payrollid'))
                                        ->update([
                                            'amountpaid'        => $request->get('otherdeduction'.$otherdeductionid)
                                        ]);
                        
                        DB::table('payroll_historydetail')
                                        ->where('headerid', $payrollhistoryid)
                                        ->where('deductionid', $otherdeductionid)
                                        ->where('type', 'otherdeduction')
                                        ->update([
                                            'paymentoption'        => $paymentoption,
                                            'description'          => $deductiondescription,
                                            'amount'               => $request->get('otherdeduction'.$otherdeductionid),
                                            'dateissued'           => date('Y-m-d H:i:s')
                                        ]);
    
                    }
    
                }

            }

            
        }
        
        DB::table('payroll_history')
            ->where('id',$payrollid)
            ->where('employeeid',$employeeid)
            ->update([
                'netpay'            => $totalearnings - $totaldeductions,
                'totalearnings'     => $totalearnings,
                'totaldeductions'   => $totaldeductions
            ]);

        return back();

        

    }
}
