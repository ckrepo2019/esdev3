<?php

namespace App\Http\Controllers\HRControllers\HREmployeeProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class HRAllowanceInfoController extends Controller
{
    public function index(Request $request)
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
                'employee_basicsalaryinfo.holidays'
                )
            ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
            ->where('employee_basicsalaryinfo.employeeid',$teacherid)
            ->where('employee_basicsalaryinfo.deleted','0')
            ->where('employee_basistype.deleted','0')
            ->get();

            $standardallowances = Db::table('allowance_standard')
                ->where('deleted','0')
                ->get();
                
            $mystandardallowances = array();

            if(count($standardallowances) > 0){

                foreach($standardallowances as $standardallowance){

                    $myallowances = Db::table('employee_allowancestandard')
                        ->select(
                            'employee_allowancestandard.id as employeeallowancestandardid',
                            'employee_allowancestandard.amount',
                            'employee_allowancestandard.status'
                            )
                        ->where('employee_allowancestandard.employeeid',$teacherid)
                        ->where('employee_allowancestandard.allowance_standardid',$standardallowance->id)
                        ->get();

                    if(count($myallowances) == 0){

                        array_push($mystandardallowances, (object)array(
                            'allowance_standardid'          => $standardallowance->id,
                            'description'                   => $standardallowance->description,
                            'employeeallowancestandardid'   => '',
                            'amount'                        => '',
                            'status'                        => ''
                        ));

                    }else{
                        array_push($mystandardallowances, (object)array(
                            'allowance_standardid'          => $standardallowance->id,
                            'description'                   => $standardallowance->description,
                            'employee_allowancestandard'   => $myallowances[0]->employeeallowancestandardid,
                            'amount'                        => $myallowances[0]->amount,
                            'status'                        => $myallowances[0]->status
                        ));
                    }

                }

            }
                
            $myallowances = Db::table('employee_allowanceother')
                ->where('employeeid',$teacherid)
                ->where('deleted','0')
                ->get();
                
            // return $deductiontypes;
            return view('hr.employeeprofile.allowances')
                ->with('standardallowances',$standardallowances)
                ->with('profileinfoid',$teacherid)
                ->with('mystandardallowances',$mystandardallowances)
                ->with('myallowances',$myallowances);
    }
    public function employeestandardallowances(Request $request)
    {
        

        date_default_timezone_set('Asia/Manila');
        
        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            // ->where('isactive','1')
            // ->where('deleted','0')
            ->first();
            // return $request->all();
            // return $request->except('employeeid','allowanceid','amounts','linkid');
        foreach($request->get('allowanceid') as $allowancekey => $allowancevalue){
            
            $status = $request->get('status')[$allowancekey];

            // foreach($request->except('employeeid','allowanceid','amounts','linkid') as $statuskey => $statusvalue){
                
            //     // if(str_replace('allowancestatus', '', $statuskey) == $allowancevalue){

            //         if($statusvalue == 'active'){

            //             $status = 1;

            //         }
            //         elseif($statusvalue == 'inactive'){

            //             $status = 0;

            //         }

            //     // }
                
            // }
            
            if($status == 'active'){

                $status = 1;

            }
            elseif($status == 'inactive'){

                $status = 0;

            }
            // return $status;
            $checkifexists = Db::table('employee_allowancestandard')
                ->where('employeeid', $request->get('employeeid'))
                ->where('allowance_standardid', $allowancevalue)
                ->get();

            if(count($checkifexists) == 0){

                DB::table('employee_allowancestandard')
                    ->insert([
                        'employeeid'            => $request->get('employeeid'),
                        'allowance_standardid'  => $allowancevalue,
                        'amount'                => $request->get('amounts')[$allowancekey],
                        'status'                => $status
                        
                    ]);

            }
            else{

                DB::table('employee_allowancestandard')
                    ->where('employeeid',$request->get('employeeid'))
                    ->where('allowance_standardid',$allowancevalue)
                    ->update([
                        'amount'         => $request->get('amounts')[$allowancekey],
                        'status'         => $status
                    ]);

            }

        }
        
        return back()->with('linkid',$request->get('linkid'));
    }
    public function employeeallowanceinfo(Request $request)
    {
        // return $request->all();
        date_default_timezone_set('Asia/Manila');
        
        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            // ->where('isactive','1')
            // ->where('deleted','0')
            ->first();

        foreach($request->get('descriptions') as $allowancekey => $allowancevalue){

            $checkifexists = Db::table('employee_allowanceother')
                ->where('description','like','%'.$allowancevalue)
                ->where('employeeid',$request->get('employeeid'))
                ->get();

            if(count($checkifexists) == 0){

                Db::table('employee_allowanceother')
                    ->insert([
                        'employeeid'    => $request->get('employeeid'),
                        'description'   => $allowancevalue,
                        'amount'        => $request->get('amounts')[$allowancekey],
                        'term'          => $request->get('terms')[$allowancekey]
                    ]);
                
            }

        }

        // return back()->with('linkid',$request->get('linkid'));
    }
    public function employeeotherallowanceinfoedit(Request $request)
    {
        // return $request->all();
        Db::table('employee_allowanceother')
        ->where('id',$request->get('otherallowanceid'))
        ->where('employeeid',$request->get('employeeid'))
        ->update([
            'description'   =>  $request->get('description'),
            'amount'        =>  str_replace( ',', '', $request->get('amount') ),
            'term'          =>  $request->get('term')
        ]);
    
    // return back()->with('linkid',$request->get('linkid'));
    }
    public function employeeotherallowanceinfodelete(Request $request)
    {
        
        Db::table('employee_allowanceother')
            ->where('id',$request->get('otherallowanceid'))
            ->where('employeeid',$request->get('employeeid'))
            ->update([
                'deleted'   => '1'
            ]);

        // return back()->with('linkid',$request->get('linkid'));
    }
    
}
