<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
class HREmployeeAllowancesController extends Controller
{
    public function taballowancesindex(Request $request)
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
                
            return view('hr.employees.info.allowances')
                ->with('standardallowances',$standardallowances)
                ->with('profileinfoid',$teacherid)
                ->with('mystandardallowances',$mystandardallowances)
                ->with('myallowances',$myallowances);
    }
    public function taballowancesupdatestandardallowance(Request $request)
    {
    
        date_default_timezone_set('Asia/Manila');
        
        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->first();

        foreach($request->get('allowanceid') as $allowancekey => $allowancevalue){
            
            $status = $request->get('status')[$allowancekey];

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
        
        // return back()->with('linkid',$request->get('linkid'));
    }
    public function taballowancesaddallowance(Request $request)
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

    }
    public function taballowancesupdateallowance(Request $request)
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
    public function taballowancesdeleteallowance(Request $request)
    {
        
        Db::table('employee_allowanceother')
            ->where('id',$request->get('otherallowanceid'))
            ->where('employeeid',$request->get('employeeid'))
            ->update([
                'deleted'   => '1'
            ]);

        // return back()->with('linkid',$request->get('linkid'));
    }

    // Gian Additional

    // load tanan standard allowances
    public function loadallallowance(Request $request){
        $all_allowance = DB::table('allowance_standard')
            ->where('deleted', 0)
            ->get();

        return  $all_allowance;
    }

    // add didto sa table nga standard allowances
    public function addstandardallowance(Request $request){

        $particular = $request->get('particular');
        $amount = $request->get('amount');
        $boa_amount = $request->get('boa_amount');
        $baseonattendance = $request->get('baseonattendance');
        $amountbaseonsalary = $request->get('amountbaseonsalary');
        $days = $request->get('days');
        $mon = 0;
        $tue = 0;
        $wed = 0;
        $thu = 0;
        $fri = 0;
        $sat = 0;
        $sun = 0;
		
		if(!$days){
			$days = [];
		}
        if (count($days) > 0) {
            foreach ($days as $dayNumber) {
                switch ($dayNumber) {
                    case "1":
                        $mon = 1;
                        break;
                    case "2":
                        $tue = 1;
                        break;
                    case "3":
                        $wed = 1;
                        break;
                    case "4":
                        $thu = 1;
                        break;
                    case "5":
                        $fri = 1;
                        break;
                    case "6":
                        $sat = 1;
                        break;
                    case "7":
                        $sun = 1;
                        break;
                    // Add cases for other days if needed
                }
            }
        } else {
            $days = [];
        }
        
        $ifexist = DB::table('allowance_standard')
            ->where('description', $particular)
            ->where('deleted', 0)
            ->count();

        if ($ifexist) {
            return 3;
        } else {
            $data = DB::table('allowance_standard')
                ->insert([
                    'description'       => $particular,
                    'amount'            => $amount,
                    'amountperday'            => $boa_amount,
                    'baseonattendance'            => $baseonattendance,
                    'monday'   => $mon,
                    'tuesday'   => $tue,
                    'wednesday'   => $wed,
                    'thursday'   => $thu,
                    'friday'   => $fri,
                    'saturday'   => $sat,
                    'sunday'   => $sun,
                    'amountbaseonsalary'   => $amountbaseonsalary,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }
    }

    // add per employee og standard allowances
    public function addemployeestandardallowance(Request $request){

        // return $request->all();
        $allowance_standardid = $request->get('stanallowance_id');
        $employeeid = $request->get('empid');
        $amount = $request->get('amount');
        $action = $request->get('action');
        $amountperday = $request->get('amountperday');
        $baseonattendance = $request->get('baseonattendance');
        $amountbaseonsalary = $request->get('amountbaseonsalary');
        $days =$request->get('days');
        $mon = 0;
        $tue = 0;
        $wed = 0;
        $thu = 0;
        $fri = 0;
        $sat = 0;
        $sun = 0;
		if(!$days){
			$days = [];
		}
        if (count($days) > 0) {
            foreach ($days as $dayNumber) {
                switch ($dayNumber) {
                    case "1":
                        $mon = 1;
                        break;
                    case "2":
                        $tue = 1;
                        break;
                    case "3":
                        $wed = 1;
                        break;
                    case "4":
                        $thu = 1;
                        break;
                    case "5":
                        $fri = 1;
                        break;
                    case "6":
                        $sat = 1;
                        break;
                    case "7":
                        $sun = 1;
                        break;
                    // Add cases for other days if needed
                }
            }
        } else {
            $days = [];
        }


        $ifexist = DB::table('employee_allowancestandard')
            ->where('allowance_standardid', $allowance_standardid)
            ->where('employeeid', $employeeid)
            ->where('deleted', 0)
            ->count();
        // return $request->all();

        if ($action == 'update') {

            $data = DB::table('employee_allowancestandard')
                ->where('employeeid', $employeeid)
                ->where('id', $allowance_standardid)
                ->update([
                    // 'allowance_standardid'       => $allowance_standardid,
                    'employeeid'       => $employeeid,
                    'amount'       => $amount,
                    'amountperday'       => $amountperday,
                    'baseonattendance'       => $baseonattendance,
                    'monday'   => $mon,
                    'tuesday'   => $tue,
                    'wednesday'   => $wed,
                    'thursday'   => $thu,
                    'friday'   => $fri,
                    'saturday'   => $sat,
                    'sunday'   => $sun,
                    'amountbaseonsalary'   => $amountbaseonsalary,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 2;
        } else {
            if ($ifexist) {
                return 3;
            } else {
                $data = DB::table('employee_allowancestandard')
                    ->insert([
                        'allowance_standardid'       => $allowance_standardid,
                        'employeeid'       => $employeeid,
                        'amount'       => $amount,
                        'amountperday'       => $amountperday,
                        'baseonattendance'       => $baseonattendance,
                        'status'       => 1,
                        'monday'   => $mon,
                        'tuesday'   => $tue,
                        'wednesday'   => $wed,
                        'thursday'   => $thu,
                        'friday'   => $fri,
                        'saturday'   => $sat,
                        'sunday'   => $sun,
                        'amountbaseonsalary'   => $amountbaseonsalary,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
    
                return 1;
            }
        }
        
    }

    // Load employee standard allowances
    public function loademployeestandardallowance(Request $request){
        $employeeid = $request->get('empid');

        $data = DB::table('employee_allowancestandard')
            ->select('employee_allowancestandard.*', 'allowance_standard.description')
            ->leftJoin('allowance_standard', 'employee_allowancestandard.allowance_standardid', '=', 'allowance_standard.id')
            ->where('employeeid', $employeeid)
            ->where('employee_allowancestandard.deleted', 0)
            ->where('allowance_standard.deleted', 0)
            ->get();

        return $data;
    }

    public function deleteemployeestandardallowance(Request $request){
        $emp_stanid = $request->get('emp_stanid');
        $empid = $request->get('empid');

        // return $request->all();
        $data = DB::table('employee_allowancestandard')
        ->where('allowance_standardid', $emp_stanid)
        ->where('employeeid', $empid)
        ->where('deleted', 0)
        ->update([
            'deleted' => 1,
            'deletedby'  => auth()->user()->id,
            'deleteddatetime'   => date('Y-m-d H:i:s')
        ]);

        return array((object)[
            'status'=>1,
            'message'=>'Deleted Successfully!',
        ]);
    }

    //save per employee allowance apply all 
    public function saveperemployeestandardallowance(Request $request){
        $peremployeedata = json_decode($request->get('per_employeedata'));
        // return $peremployeedata;
        
        if (!empty($peremployeedata) && is_array($peremployeedata)) {
            foreach ($peremployeedata as $perempdata) {
                if (is_object($perempdata) &&
                    property_exists($perempdata, 'employeeid') &&
                    property_exists($perempdata, 'standardallowance_id') &&
                    property_exists($perempdata, 'mod_amountperday') &&
                    property_exists($perempdata, 'mod_baseonattendance') &&
                    property_exists($perempdata, 'mod_amount') &&
                    property_exists($perempdata, 'desc') && 
                    property_exists($perempdata, 'days') && 
                    property_exists($perempdata, 'mod_amountbaseondaily')
                ) {
                    $checkifexists = DB::table('employee_allowancestandard')
                        ->where('employeeid', $perempdata->employeeid)
                        ->where('allowance_standardid', $perempdata->standardallowance_id)
                        ->where('deleted', 0)
                        ->count();
    
                    if ($checkifexists) {
                        return array((object)[
                            'status' => 0,
                            'message' => 'Already Exist!',
                        ]);
                    } else {
                        $amountbaseonsalary = $perempdata->mod_amountbaseondaily;
                        $days = $perempdata->days;
                        $mon = 0;
                        $tue = 0;
                        $wed = 0;
                        $thu = 0;
                        $fri = 0;
                        $sat = 0;
                        $sun = 0;
						
                        if (count($days) > 0) {
                            foreach ($days as $dayNumber) {
                                switch ($dayNumber) {
                                    case "1":
                                        $mon = 1;
                                        break;
                                    case "2":
                                        $tue = 1;
                                        break;
                                    case "3":
                                        $wed = 1;
                                        break;
                                    case "4":
                                        $thu = 1;
                                        break;
                                    case "5":
                                        $fri = 1;
                                        break;
                                    case "6":
                                        $sat = 1;
                                        break;
                                    case "7":
                                        $sun = 1;
                                        break;
                                    // Add cases for other days if needed
                                }
                            }
                        } else {
                            $days = [];
                        }

                        DB::table('employee_allowancestandard')
                            ->insert([
                                'employeeid' => $perempdata->employeeid,
                                'allowance_standardid' => $perempdata->standardallowance_id,
                                'amount' => $perempdata->mod_amount,
                                'amountperday' => $perempdata->mod_amountperday,
                                'baseonattendance' => $perempdata->mod_baseonattendance,
                                'monday'   => $mon,
                                'tuesday'   => $tue,
                                'wednesday'   => $wed,
                                'thursday'   => $thu,
                                'friday'   => $fri,
                                'saturday'   => $sat,
                                'sunday'   => $sun,
                                'amountbaseonsalary'   => $amountbaseonsalary,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                } else {
                    return 0;
                }
            } 
            return 1;

        } else {
            return 2;
        }
    }

    // Get all Standard Allowance 
    
    public function getallemployeestandardallowance(Request $request){
        $data = DB::table('employee_allowancestandard')
            ->where('deleted', 0)
            ->get();

        return $data;
    }
}
