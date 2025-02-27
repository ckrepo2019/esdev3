<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Models\HR\HRDeductions;
class HREmployeeDeductionsController extends Controller
{
    public function tabdeductionsindex(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');
        
        $teacherid = $request->get('employeeid');

        $employee_basicsalaryinfo = Db::table('employee_basicsalaryinfo')
            ->select(
                'employee_basicsalaryinfo.id',
                'employee_basicsalaryinfo.amount',
                'employee_basicsalaryinfo.paymenttype',
                'employee_basistype.id as basistypeid',
                'employee_basistype.type',
                'employee_basicsalaryinfo.noofmonths',
                'employee_basicsalaryinfo.projectbasedtype',
                'employee_basicsalaryinfo.hoursperday',
                'employee_basicsalaryinfo.hoursperweek',
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
                'employee_basicsalaryinfo.deductionsetup',
                'employee_basicsalaryinfo.deductionfixed'
                )
            ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
            ->where('employee_basicsalaryinfo.employeeid',$teacherid)
            ->where('employee_basicsalaryinfo.deleted','0')
            ->where('employee_basistype.deleted','0')
            ->get();

        $tardinesssetup = DB::table('deduction_tardinesssetup')
            ->where('status','1')
            ->get();
            
        $department = Db::table('hr_school_department')
            ->where('deleted','0')
            ->get();

            
        $designations = Db::table('usertype')
            ->select(
                'id',
                'utype as designation',
                'departmentid'
            )
            ->where('deleted','0')
            ->get();
    
        $deductiontypes = Db::table('deduction_standard')
            ->where('deleted','0')
            ->get();

        if(count($employee_basicsalaryinfo) > 0){
            $setuptype = $employee_basicsalaryinfo[0]->deductionsetup;
            // return $setuptype;
            if($setuptype == 1)
            {
                $mystandarddeductions = array();
                if(count($deductiontypes)>0)
                {
                    foreach($deductiontypes as $deductiontype)
                    {
                        $mystandard_deductions = Db::table('employee_deductionstandard')
                            ->select(
                                'employee_deductionstandard.id as contributiondetailid',
                                'employee_deductionstandard.ersamount',
                                'employee_deductionstandard.eesamount',
                                'employee_deductionstandard.status'
                                )
                            ->where('employee_deductionstandard.employeeid',$teacherid)
                            ->where('employee_deductionstandard.deduction_typeid',$deductiontype->id)
                            ->where('employee_deductionstandard.deleted','0')
                            ->get();
                            
                        if(count($mystandard_deductions)>0)
                        {
                            array_push($mystandarddeductions, (object)array(
                                'contributionid'    => $deductiontype->id,
                                'description'    => $deductiontype->description,
                                'contributiondetailid'    => "",
                                'ersamount'    => $mystandard_deductions[0]->ersamount,
                                'eesamount'    => $mystandard_deductions[0]->eesamount,
                                'status'    => $mystandard_deductions[0]->status,
                            ));
                        }
                    }
                }
            }else{
                
                $mystandarddeductions = HRDeductions::updatestandarddeductions($teacherid,$employee_basicsalaryinfo[0]->amount, $employee_basicsalaryinfo[0]->type);
            }
        }else{
            $mystandarddeductions = [];
            $setuptype = '';
        }
        
        $myotherdeductions = Db::table('employee_deductionother')
            ->where('employee_deductionother.employeeid',$teacherid)
            ->where('employee_deductionother.deleted','0')
            ->get();
                
        foreach($myotherdeductions as $myotherdeduction){

            foreach($myotherdeduction as $myotherdeductionkey => $myotherdeductionvalue){

                if($myotherdeductionkey == 'dateissued'){


                    $myotherdeduction->dateissued = date('F d,Y ', strtotime($myotherdeductionvalue));

                }
                elseif($myotherdeductionkey == 'amount'){

                    $myotherdeduction->amount = number_format($myotherdeductionvalue, 2, '.', ',');

                }
                elseif($myotherdeductionkey == 'amountpaid'){

                    $myotherdeduction->amountpaid = number_format($myotherdeductionvalue, 2, '.', ',');

                }

            }

        }

                
            
        $deductiondetails = Db::table('deduction_standarddetail')
            ->where('deleted','0')
            ->get();

        $salarybasistypes = Db::table('employee_salary')
            ->where('deleted','0')
            ->get();

        $salarydeductionbasistypes = Db::table('employee_basistype')
            ->where('deleted','0')
            ->get();

        $deductionbased = 0;
        
        if(count($employee_basicsalaryinfo)>0)
        {
            $deductionbased = $employee_basicsalaryinfo[0]->deductionfixed;
        }
            // return $
        return view('hr.employees.info.deductions')
            ->with('profileinfoid',$teacherid)
            ->with('deductionbased',$deductionbased)
            ->with('setuptype',$setuptype)
            // ->with('benefitsnotapplied',$benefitsnotapplied)
            ->with('deductiontypes',$deductiontypes)
            ->with('deductiondetails',$deductiondetails)
            ->with('mycontributions',$mystandarddeductions)
            ->with('myotherdeductions',$myotherdeductions);

    }
    public function tabdeductionsupdatesetuptype(Request $request)
    {
        if($request->has('setupdeductiontype'))
        {
            DB::table('employee_basicsalaryinfo')
                ->where('employeeid', $request->get('employeeid'))
                ->update([
                    'deductionfixed' => $request->get('setupdeductiontype')
                ]);
        }else{
            DB::table('employee_basicsalaryinfo')
                ->where('employeeid', $request->get('employeeid'))
                ->update([
                    'deductionsetup' => $request->get('setuptype')
                ]);
        }
    }
    public function tabdeductionsupdatedeductions(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');
        // return $request->all();
        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            // ->where('isactive','1')
            // ->where('deleted','0')
            ->first();

        foreach($request->except('employeeid','deductiontypes','ersamounts','eesamounts') as $statuskey => $statusvalue){

            // $status = 0;

            foreach($request->get('deductiontypes') as $deductiontypekey => $deductiontypevalue){
                
                $checkifexists = Db::table('employee_deductionstandard')
                    ->where('employeeid', $request->get('employeeid'))
                    ->where('deduction_typeid', $deductiontypevalue)
                    ->get();
                    
                if(count($checkifexists) == 0){
    
                    DB::table('employee_deductionstandard')
                        ->insert([
                            'employeeid'        => $request->get('employeeid'),
                            'deduction_typeid'  => $deductiontypevalue,
                            'ersamount'         => $request->get('ersamounts')[$deductiontypekey],
                            'eesamount'         => $request->get('eesamounts')[$deductiontypekey],
                            'status'            => $request->get('contributionstatus')[$deductiontypekey],
                            'updatedby'         => $getMyid->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
    
                }
                else{
                    // return $checkifexists;
                    DB::table('employee_deductionstandard')
                        ->where('employeeid',$request->get('employeeid'))
                        ->where('deduction_typeid',$deductiontypevalue)
                        ->update([
                            'ersamount'         => $request->get('ersamounts')[$deductiontypekey],
                            'eesamount'         => $request->get('eesamounts')[$deductiontypekey],
                            'status'            => $request->get('contributionstatus')[$deductiontypekey],
                            'updatedby'         => $getMyid->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
    
                }

                
            }

        }

    }
    public function tabdeductionsadddeduction(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->first();

        foreach($request->get('description') as $otherdeductkey => $otherdeductvalue){

            $checkifexists = Db::table('employee_deductionother')
                ->where('description','like','%'.$otherdeductvalue)
                ->where('employeeid',$request->get('employeeid'))
                ->where('deleted','0')
                ->where('paid','0')
                ->get();

            if(count($checkifexists) == 0){

                if($request->get('totalamount')[$otherdeductkey] != null && $request->get('term')[$otherdeductkey] != null){

                    if($request->get('startdates')[$otherdeductkey]> date('Y-m-d'))
                    {
                        $status = 0;
                    }
                    elseif($request->get('startdates')[$otherdeductkey]<=date('Y-m-d'))
                    {
                        $status = 1;
                    }
                    $getOtherDeductid = Db::table('employee_deductionother')
                    ->insertGetId([
                        'employeeid'        => $request->get('employeeid'),
                        'description'       => $otherdeductvalue,
                        'amount'            => $request->get('totalamount')[$otherdeductkey],
                        'status'            => $status,
                        'term'              => $request->get('term')[$otherdeductkey],
                        'dateissued'        => $request->get('startdates')[$otherdeductkey]
                    ]);

                Db::table('employee_deductionotherdetail')
                    ->insert([
                        'headerid'          => $getOtherDeductid,
                        'amountpaid'        => '0',
                        'status'            => $status,
                        'updatedby'         => $getMyid->id,
                        'updateddatetime'   => $request->get('startdates')[$otherdeductkey]
                    ]);
                }
                
            }

        }

    }
    public function tabdeductionseditdeduction(Request $request)
    {
        
        Db::table('employee_deductionother')
        ->where('id',$request->get('otherdeductionid'))
        ->where('employeeid',$request->get('employeeid'))
        ->update([
            'description'   =>  $request->get('description'),
            'amount'        =>  str_replace( ',', '', $request->get('amount') ),
            'term'          =>  $request->get('term'),
            'status'          =>  $request->get('status')
        ]);
    }
    public function tabdeductionsdeletededuction(Request $request)
    {
        // return $request->all();
        Db::table('employee_deductionother')
            ->where('id',$request->get('otherdeductionid'))
            ->where('employeeid',$request->get('employeeid'))
            ->update([
                'deleted'   =>  '1'
            ]);

    }
}
