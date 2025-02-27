<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Crypt;
class HRAllowanceSetupController extends Controller
{
    public function allowancesetup(Request $request)
    {
        if(!$request->has('action'))
        {
            $standardallowances = Db::table('allowance_standard')
                ->where('deleted','0')
                ->get();
            $otherallowances = Db::table('employee_allowanceother')
                ->where('deleted','0')
                ->get();
                
            $employees = DB::table('teacher')
                ->select(
                    'employee_basicsalaryinfo.*',
                    'teacher.id as employeeid',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix',
                    'teacher.picurl',
                    'teacher.tid',
                    'usertype.id as usertypeid',
                    'usertype.utype',
                    'employee_basistype.type as salarytype'
                    )
                ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                ->leftJoin('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                ->where('teacher.deleted','0')
                ->where('teacher.isactive','1')
                ->get();

                // return $deductiontypes;
            foreach($employees as $employee)
            {
                $employee->sortname = $employee->lastname.', '.$employee->firstname.' '.$employee->suffix.' '.($employee->middlename != null ? $employee->middlename[0].'.' : '');
                $allowances = array();
                if(count($standardallowances)>0)
                {
                    foreach($standardallowances as $eachstandard)
                    {
                        $automatic = 0;
                        $standardstatus = 1;
                        $getallowanceamount = null;
                        $defaultamount = $eachstandard->amount;

                        $info = DB::table('employee_allowancestandard')
                            ->where('employee_allowancestandard.allowance_standardid', $eachstandard->id)
                            ->where('employee_allowancestandard.employeeid', $employee->employeeid)
                            ->where('employee_allowancestandard.deleted', 0)
                            ->first();

                        if($info)
                        {
                            $automatic = $info->amount == $defaultamount  ? 1 : 0;
                            $standardstatus = 1;
                            $getallowanceamount = $info->amount;
                            $standardstatus = $info->status;
                        }
                        // else{                            
                        //     $automatic = 1;
                        //     $getallowanceamount = $defaultamount;

                        //     if ($eachstandard->baseonattendance == 1) {
                        //         DB::table('employee_allowancestandard')
                        //         ->insert([
                        //             'employeeid'        =>  $employee->employeeid,
                        //             'allowance_standardid'  => $eachstandard->id,
                        //             'amount'         => 0,
                        //             'amountperday'       => $eachstandard->amountperday,
                        //             'baseonattendance'       => $eachstandard->baseonattendance,
                        //             'status'            => 1,
                        //             'createdby'         => 0,
                        //             'createddatetime'   => date('Y-m-d H:i:s')
                        //         ]);
                        //     } else {
                        //         DB::table('employee_allowancestandard')
                        //         ->insert([
                        //             'employeeid'        =>  $employee->employeeid,
                        //             'allowance_standardid'  => $eachstandard->id,
                        //             'amount'         => $getallowanceamount,
                        //             'amountperday'       => 0,
                        //             'baseonattendance'       => 0,
                        //             'status'            => 1,
                        //             'createdby'         => 0,
                        //             'createddatetime'   => date('Y-m-d H:i:s')
                        //         ]);
                        //     }
                            
                        // }
                        // return $employee->amount;
                        array_push($allowances, (object)array(
                            'id'            => $eachstandard->id,
                            'description'   => $eachstandard->description,
                            'cont_amount_default'   => $defaultamount,
                            'cont_amount'   => $getallowanceamount == null ? '0.00' : $getallowanceamount,
                            'status'   => $standardstatus,
                            'automatic'    => $automatic,
                            'amountperday'    => $eachstandard->amountperday,
                            'baseonattendance'    => $eachstandard->baseonattendance
                        ));
                    }
                }
                // return $contributions;
                $employee->allowances = $allowances;
                $otherallows = array();
                if(count($otherallowances)>0)
                {
                    foreach($otherallowances as $eachother)
                    {
                        $info = DB::table('employee_allowanceother')
                            // ->where('employee_allowanceother.deductionotherid', $eachother->id)
                            ->where('employee_allowanceother.employeeid', $employee->employeeid)
                            ->where('employee_allowanceother.deleted', 0)
                            ->first();

                        if($info)
                        {
                            $getdeductionamount = $info->amount;
                            $custom = ($eachother->amount != $info->amount ? 1 : 0);
                            array_push($otherallows, (object)array(
                                'id'            => $eachother->id,
                                'description'   => $eachother->description,
                                'cont_amount_default'   => $eachother->amount,
                                'cont_amount'   => $getdeductionamount,
                                'status'   => $info->status,
                                'custom'    => $custom
                            ));
                        }
                    }
                }
                $employee->otherallowances = $otherallows;
                
            }
            // return $employees;
            return view('hr.settings.allowances.index')
                ->with('employees',collect($employees)->sortBy('sortname')->values()->all())
                ->with('otherallowances',$otherallowances)
                ->with('standardallowances',$standardallowances);
        }else{
            if($request->get('action') == 'standardallowance_add')
            {
                // $checkifexists = DB::table('deduction_standard')
                //     ->where('description','like','%'.$request->get('desc').'%')
                //     // ->where('id','!=',$request->get('id'))
                //     ->where('deleted','0')
                //     ->first();

                // if($checkifexists)
                // {
                //     return 0 ;
                // }else{
                    $id = DB::table('allowance_standard')
                        ->insertGetId([
                            'description'       => $request->get('desc'),
                            'amount'            => $request->get('amount'),
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return $id;
                // }
            }
            if($request->get('action') == 'standardallowance_edit')
            {
                // $checkifexists = DB::table('deduction_standard')
                //     ->where('description','like','%'.$request->get('desc').'%')
                //     // ->where('id','!=',$request->get('id'))
                //     ->where('deleted','0')
                //     ->first();

                // if($checkifexists)
                // {
                //     return 0 ;
                // }else{
                    DB::table('allowance_standard')
                        ->where('id', $request->get('id'))
                        ->update([
                            // 'description'       => $request->get('desc'),
                            'amount'            => $request->get('amount'),
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return 1;
                // }
            }
            else if($request->get('action') == 'standardallowance_delete')
            {
                DB::table('allowance_standard')
                    ->where('id', $request->get('id'))
                    ->update([
                        // 'description'       => $request->get('desc'),
                        'deleted'           => 1,
                        'deletedby'         => auth()->user()->id,
                        'deleteddatetime'   => date('Y-m-d H:i:s')
                    ]);

                return 1;
            }
            // elseif($request->get('action') == 'add_otherdeduction')
            // {
            //     // $checkifexists = DB::table('deduction_standard')
            //     //     ->where('description','like','%'.$request->get('desc').'%')
            //     //     // ->where('id','!=',$request->get('id'))
            //     //     ->where('deleted','0')
            //     //     ->first();

            //     // if($checkifexists)
            //     // {
            //     //     return 0 ;
            //     // }else{
            //         $id = DB::table('deduction_others')
            //             ->insertGetId([
            //                 'description'       => $request->get('desc'),
            //                 'amount'            => $request->get('amount'),
            //                 'createdby'         => auth()->user()->id,
            //                 'createddatetime'   => date('Y-m-d H:i:s')
            //             ]);

            //         return $id;
            //     // }
            // }
            // if($request->get('action') == 'update_otherdeduction')
            // {
            //     // $checkifexists = DB::table('deduction_standard')
            //     //     ->where('description','like','%'.$request->get('desc').'%')
            //     //     // ->where('id','!=',$request->get('id'))
            //     //     ->where('deleted','0')
            //     //     ->first();

            //     // if($checkifexists)
            //     // {
            //     //     return 0 ;
            //     // }else{
            //         DB::table('deduction_others')
            //             ->where('id', $request->get('id'))
            //             ->update([
            //                 // 'description'       => $request->get('desc'),
            //                 'amount'            => $request->get('amount'),
            //                 'updatedby'         => auth()->user()->id,
            //                 'updateddatetime'   => date('Y-m-d H:i:s')
            //             ]);

            //         return 1;
            //     // }
            // }
            // else if($request->get('action') == 'delete_otherdeduction')
            // {
            //     DB::table('deduction_others')
            //         ->where('id', $request->get('id'))
            //         ->update([
            //             // 'description'       => $request->get('desc'),
            //             'deleted'           => 1,
            //             'deletedby'         => auth()->user()->id,
            //             'deleteddatetime'   => date('Y-m-d H:i:s')
            //         ]);

            //     return 1;
            // }
        }
    }
    public function allowance_customcontribution(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        if($request->ajax())
        {
            if($request->get('action') == 'custom_add')
            {
                
                if($request->get('startdate') == date('Y-m-d')){
                    // return '1';
                    $status = 1;
                }elseif($request->get('startdate') < date('Y-m-d')){
                    // return '0';
                    $status = 1;
                }elseif($request->get('startdate') > date('Y-m-d')){
                    // return '0';
                    $status = 0;
                }else{
                    $status = 1;
                }
                $checkifexists = DB::table('employee_allowancestandard')
                    ->where('employee_allowancestandard.allowance_standardid', $request->get('particularid'))
                    ->where('employee_allowancestandard.employeeid', $request->get('employeeid'))
                    ->where('employee_allowancestandard.deleted', 0)
                    ->first();
                    
                if($checkifexists)
                {
                    DB::table('employee_allowancestandard')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'employeeid'        => $request->get('employeeid'),
                            'allowance_standardid'  => $request->get('particularid'),
                            'amount'         => $request->get('amount'),
                            'status'            => $status,
                            // 'datestarted'       => $request->has('startdate') ?  $request->get('startdate') : date('Y-m-d'),
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
                else{
                    DB::table('employee_allowancestandard')
                        ->insert([
                            'employeeid'        => $request->get('employeeid'),
                            'allowance_standardid'  => $request->get('particularid'),
                            'amount'         => $request->get('amount'),
                            'status'            => $status,
                            // 'datestarted'       => $request->has('startdate') ?  $request->get('startdate') : date('Y-m-d'),
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
        
            }elseif($request->get('action') == 'custom_reset')
            { // please change this; don't delete the existing but change the amount to default amount;
                DB::table('employee_allowancestandard')
                ->where('employee_allowancestandard.allowance_standardid', $request->get('particularid'))
                ->where('employee_allowancestandard.employeeid', $request->get('employeeid'))
                    ->update([
                        'amount'            => $request->get('amount'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }elseif($request->get('action') == 'custom_disable')
            {
                $checkifexists = DB::table('employee_allowancestandard')
                    ->where('employee_allowancestandard.allowance_standardid', $request->get('particularid'))
                    ->where('employee_allowancestandard.employeeid', $request->get('employeeid'))
                    ->where('employee_allowancestandard.deleted', 0)
                    ->first();
    
                if($checkifexists)
                {
                    DB::table('employee_allowancestandard')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'status'            => 0,
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }elseif($request->get('action') == 'custom_enable')
            {
                $checkifexists = DB::table('employee_allowancestandard')
                    ->where('employee_allowancestandard.allowance_standardid', $request->get('particularid'))
                    ->where('employee_allowancestandard.employeeid', $request->get('employeeid'))
                    ->where('employee_allowancestandard.deleted', 0)
                    ->first();
    
                if($checkifexists)
                {
                    DB::table('employee_allowancestandard')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'status'            => 1,
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        return 1;
    }
    public function otherallowances(Request $request)
    {
        // return $request->all();            
        if($request->get('action') == 'add_otherdeductiontoemployee')
        {
            // return $request->all();
            DB::table('employee_deductionother')
                ->insert([
                    'employeeid'        => $request->get('employeeid'),
                    'deductionotherid'        => $request->get('particularid'),
                    'description'        => $request->get('desc'),
                    'fullamount'        => $request->get('fullamount'),
                    'amount'        => $request->get('amount'),
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);
        }        
        else if($request->get('action') == 'edit_otherdeductiontoemployee')
        {
            // return $request->all();
            DB::table('employee_deductionother')
                ->where('deductionotherid', $request->get('particularid'))
                ->where('employeeid', $request->get('employeeid'))
                ->where('deleted', '0')
                ->update([
                    'amount'        => $request->get('amount'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

        }
        elseif($request->get('action') == 'otherdeduction_disable')
        {
            $checkifexists = DB::table('employee_deductionother')
                ->where('employee_deductionother.deductionotherid', $request->get('particularid'))
                ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                ->where('employee_deductionother.deleted', 0)
                ->first();

            if($checkifexists)
            {
                DB::table('employee_deductionother')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'status'            => 0,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }elseif($request->get('action') == 'otherdeduction_enable')
        {
            $checkifexists = DB::table('employee_deductionother')
                ->where('employee_deductionother.deductionotherid', $request->get('particularid'))
                ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                ->where('employee_deductionother.deleted', 0)
                ->first();

            if($checkifexists)
            {
                DB::table('employee_deductionother')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'status'            => 1,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }elseif($request->get('action') == 'delete_otherdeduction')
        {
            $checkifexists = DB::table('employee_deductionother')
                ->where('employee_deductionother.deductionotherid', $request->get('particularid'))
                ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                ->where('employee_deductionother.deleted', 0)
                ->first();

            if($checkifexists)
            {
                DB::table('employee_deductionother')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'deleted'            => 1,
                        'deletedby'         => auth()->user()->id,
                        'deleteddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }
        return 1;
    }
}
