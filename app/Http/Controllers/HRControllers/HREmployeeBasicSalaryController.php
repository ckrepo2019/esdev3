<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Hash;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Models\HR\HRDeductions;
class HREmployeeBasicSalaryController extends Controller
{
    public function tabbasicsalaryindex(Request $request)
    {
        
        $teacherid = $request->get('employeeid');
        $employeebasicsalaryinfo = Db::table('employee_basicsalaryinfo')
        ->select(
            'employee_basicsalaryinfo.*',
            'employee_basistype.id as basistypeid',
            'employee_basistype.type'
            )
        ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
        ->where('employee_basicsalaryinfo.employeeid',$teacherid)
        ->where('employee_basicsalaryinfo.deleted','0')
        ->where('employee_basistype.deleted','0')
        ->first();

    $salarybasistypes = Db::table('employee_salary')
        ->where('deleted','0')
        ->get();

    $salarydeductionbasistypes = Db::table('employee_basistype')
        ->where('deleted','0')
        ->get();

    $employeerateelevation = DB::table('hr_rateelevation')
        ->where('employeeid',$teacherid)
        ->where('deleted','0')
        ->get();
        
    return view('hr.employees.info.basicsalary')
        ->with('profileinfoid',$teacherid)
        ->with('employeebasicsalaryinfo',$employeebasicsalaryinfo)
        ->with('salarybasistypes',$salarydeductionbasistypes)
        ->with('salarybasisranks',$salarybasistypes)
        ->with('employeerateelevation',$employeerateelevation);
    }
    public function tabbasicsalaryselectbasistype(Request $request)
    {
        
        // return $request->all();
        if($request->get('typeid') == 4) //Monthly
        {
            
            return view('hr.employees.info.basicsalary_monthly')
                ->with('employeeid', $request->get('employeeid'));

        }elseif($request->get('typeid') == 5){ //Daily

            return view('hr.employees.info.basicsalary_daily')
                ->with('employeeid', $request->get('employeeid'));

        }elseif($request->get('typeid') == 6){ // Hourly

            return view('hr.employees.info.basicsalary_hourly')
                ->with('employeeid', $request->get('employeeid'));

            
        // }elseif($request->get('typeid') == 7){
            
        }elseif($request->get('typeid') == 8){ //Project

            return view('hr.employees.info.basicsalary_project')
                ->with('employeeid', $request->get('employeeid'));

        }
    }
    public function tabbasicsalaryupdaterate(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        if($request->get('action') == 'request')
        {
        
            if(Hash::check($request->get('authorizedpassword'), auth()->user()->password))
            {
        
                DB::table('employee_basicsalaryinfo')
                    ->where('employeeid', $request->get('employeeid'))
                    ->update([
                        'amount'            => $request->get('newsalary'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
        
                return 1;
                
            }else{

                return 3;

            }

        }elseif($request->get('action') == 'viewrequest'){
        
            $newsalary = DB::table('hr_rateelevation')
                ->where('employeeid', $request->get('employeeid'))
                ->where('status','0')
                ->where('deleted','0')
                ->first()->newsalary;

            return $newsalary;

        }elseif($request->get('action') == 'undorequest'){

            DB::table('hr_rateelevation')
                ->where('employeeid', $request->get('employeeid'))
                ->update([
                    'deleted'           => 1,
                    'status'            => 0,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

            DB::table('employee_basicsalaryinfo')
                    ->where('employeeid', $request->get('employeeid'))
                    ->update([
                        'rateelevationstatus'   => 0
                    ]);

            return 1;

        }


    }
    public function tabbasicsalaryupdateinfo(Request $request)
    {
        
        
        date_default_timezone_set('Asia/Manila');

        // return $request->all();
        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            // ->where('isactive','1')
            // ->where('deleted','0')
            ->first();

        $checkifexists = DB::table('employee_basicsalaryinfo')
            ->where('employeeid',$request->get('employeeid'))
            ->where('deleted','0')
            ->get();
            // return $checkifexists;
        if(count($checkifexists) == 0)
        {
            if($request->get('basistypeid') == '4' || $request->get('basistypeid') == '5') // Monthly //Daily
            {
                try{
                    DB::table('employee_basicsalaryinfo')
                        ->insert([
                            'employeeid'        => $request->get('employeeid'),
                            'salarybasistype'   => $request->get('basistypeid'),
                            'paymenttype'       => $request->get('paymenttype'),
                            'amount'            => $request->get('salaryamount'),
                            'hoursperday'       => $request->get('hoursperday'),
                            'mondays'           => 1,
                            'tuesdays'          => 1,
                            'wednesdays'        => 1,
                            'thursdays'         => 1,
                            'fridays'           => 1,
                            'saturdays'         => $request->get('saturdaywork'),
                            'sundays'           => $request->get('sundaywork'),
                            'shiftid'           => 0,
                            'createdby'         => $getMyid->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return 1;

                }catch(\Exception $error)
                {
                    return 0;
                }

            }
            elseif($request->get('basistypeid') == '6') // Hourly
            {
                $monstatus = 0;
                $monhours = 0;
                if (in_array("monday",$request->get('workingdays'))) {
                    $monstatus = 1;
                    $monkey = array_search('monday', $request->get('workingdays'));
                    $monhours = $request->get('workinghours')[$monkey];
                }

                $tuestatus = 0;
                $tuehours = 0;
                if (in_array("tuesday",$request->get('workingdays'))) {
                    $tuestatus = 1;
                    $tuekey = array_search('tuesday', $request->get('workingdays'));
                    $tuehours = $request->get('workinghours')[$tuekey];
                }

                $wedstatus = 0;
                $wedhours = 0;
                if (in_array("wednesday",$request->get('workingdays'))) {
                    $wedstatus = 1;
                    $wedkey = array_search('wednesday', $request->get('workingdays'));
                    $wedhours = $request->get('workinghours')[$wedkey];
                }

                $thustatus = 0;
                $thuhours = 0;
                if (in_array("thursday",$request->get('workingdays'))) {
                    $thustatus = 1;
                    $thukey = array_search('thursday', $request->get('workingdays'));
                    $thuhours = $request->get('workinghours')[$thukey];
                }

                $fristatus = 0;
                $frihours = 0;
                if (in_array("friday",$request->get('workingdays'))) {
                    $fristatus = 1;
                    $frikey = array_search('friday', $request->get('workingdays'));
                    $frihours = $request->get('workinghours')[$frikey];
                }

                $satstatus = 0;
                $sathours = 0;
                if (in_array("saturday",$request->get('workingdays'))) {
                    $satstatus = 1;
                    $satkey = array_search('saturday', $request->get('workingdays'));
                    $sathours = $request->get('workinghours')[$satkey];
                }

                $sunstatus = 0;
                $sunhours = 0;
                if (in_array("sunday",$request->get('workingdays'))) {
                    $sunstatus = 1;
                    $sunkey = array_search('sunday', $request->get('workingdays'));
                    $sunhours = $request->get('workinghours')[$sunkey];
                }

                

                try{
                    DB::table('employee_basicsalaryinfo')
                        ->insert([
                            'employeeid'        => $request->get('employeeid'),
                            'salarybasistype'   => $request->get('basistypeid'),
                            'paymenttype'       => $request->get('paymenttype'),
                            'amount'            => $request->get('salaryamount'),
                            'hoursperweek'      => $request->get('hoursperweek'),
                            'mondays'           => $monstatus,
                            'tuesdays'          => $tuestatus,
                            'wednesdays'        => $wedstatus,
                            'thursdays'         => $thustatus,
                            'fridays'           => $fristatus,
                            'saturdays'         => $satstatus,
                            'sundays'           => $sunstatus,
                            'mondayhours'       => $monhours,
                            'tuesdayhours'      => $tuehours,
                            'wednesdayhours'    => $wedhours,
                            'thursdayhours'     => $thuhours,
                            'fridayhours'       => $frihours,
                            'saturdayhours'     => $sathours,
                            'sundayhours'       => $sunhours,
                            'shiftid'           => 0,
                            'createdby'         => $getMyid->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return 1;

                }catch(\Exception $error)
                {
                    return 0;
                }
            }
            elseif($request->get('basistypeid') == '8') //Project based
            {
                try{
                    DB::table('employee_basicsalaryinfo')
                        ->insert([
                            'employeeid'        => $request->get('employeeid'),
                            'salarybasistype'   => $request->get('basistypeid'),
                            'paymenttype'       => $request->get('paymenttype'),
                            'amount'            => $request->get('salaryamount'),
                            'projectbasedtype'  => $request->get('projectbasedtype'),
                            'hoursperday'       => $request->get('hoursperday'),
                            // 'mondays'           => 1,
                            // 'tuesdays'          => 1,
                            // 'wednesdays'        => 1,
                            // 'thursdays'         => 1,
                            // 'fridays'           => 1,
                            // 'saturdays'         => $request->get('saturdaywork'),
                            // 'sundays'           => $request->get('sundaywork'),
                            'shiftid'           => 0,
                            'createdby'         => $getMyid->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return 1;

                }catch(\Exception $error)
                {
                    return 0;
                }

            }
        }else{
            if($request->get('basistypeid') == '4' || $request->get('basistypeid') == '5') // Monthly // Daily
            {
                // return $request->all();
                try{
                    DB::table('employee_basicsalaryinfo')
                        ->where('employeeid',$request->get('employeeid'))
                        ->update([
                            'salarybasistype'   => $request->get('basistypeid'),
                            'paymenttype'       => $request->get('paymenttype'),
                            'amount'            => $request->get('salaryamount'),
                            'hoursperday'       => $request->get('hoursperday'),
                            'projectbasedtype'  => null,
                            'mondays'           => 1,
                            'tuesdays'          => 1,
                            'wednesdays'        => 1,
                            'thursdays'         => 1,
                            'fridays'           => 1,
                            'saturdays'         => $request->get('saturdaywork'),
                            'sundays'           => $request->get('sundaywork'),
                            'updatedby'         => $getMyid->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return 1;

                }catch(\Exception $error)
                {
                    return $error;
                }

            }
            elseif($request->get('basistypeid') == '6') // Hourly
            {
                $monstatus = 0;
                $monhours = 0;
                if (in_array("monday",$request->get('workingdays'))) {
                    $monstatus = 1;
                    $monkey = array_search('monday', $request->get('workingdays'));
                    $monhours = $request->get('workinghours')[$monkey];
                }

                $tuestatus = 0;
                $tuehours = 0;
                if (in_array("tuesday",$request->get('workingdays'))) {
                    $tuestatus = 1;
                    $tuekey = array_search('tuesday', $request->get('workingdays'));
                    $tuehours = $request->get('workinghours')[$tuekey];
                }

                $wedstatus = 0;
                $wedhours = 0;
                if (in_array("wednesday",$request->get('workingdays'))) {
                    $wedstatus = 1;
                    $wedkey = array_search('wednesday', $request->get('workingdays'));
                    $wedhours = $request->get('workinghours')[$wedkey];
                }

                $thustatus = 0;
                $thuhours = 0;
                if (in_array("thursday",$request->get('workingdays'))) {
                    $thustatus = 1;
                    $thukey = array_search('thursday', $request->get('workingdays'));
                    $thuhours = $request->get('workinghours')[$thukey];
                }

                $fristatus = 0;
                $frihours = 0;
                if (in_array("friday",$request->get('workingdays'))) {
                    $fristatus = 1;
                    $frikey = array_search('friday', $request->get('workingdays'));
                    $frihours = $request->get('workinghours')[$frikey];
                }

                $satstatus = 0;
                $sathours = 0;
                if (in_array("saturday",$request->get('workingdays'))) {
                    $satstatus = 1;
                    $satkey = array_search('saturday', $request->get('workingdays'));
                    $sathours = $request->get('workinghours')[$satkey];
                }

                $sunstatus = 0;
                $sunhours = 0;
                if (in_array("sunday",$request->get('workingdays'))) {
                    $sunstatus = 1;
                    $sunkey = array_search('sunday', $request->get('workingdays'));
                    $sunhours = $request->get('workinghours')[$sunkey];
                }

                

                try{
                    DB::table('employee_basicsalaryinfo')
                        ->where('employeeid',$request->get('employeeid'))
                        ->update([
                            'salarybasistype'   => $request->get('basistypeid'),
                            'paymenttype'       => $request->get('paymenttype'),
                            'amount'            => $request->get('salaryamount'),
                            'hoursperweek'      => $request->get('hoursperweek'),
                            'projectbasedtype'  => null,
                            'mondays'           => $monstatus,
                            'tuesdays'          => $tuestatus,
                            'wednesdays'        => $wedstatus,
                            'thursdays'         => $thustatus,
                            'fridays'           => $fristatus,
                            'saturdays'         => $satstatus,
                            'sundays'           => $sunstatus,
                            'mondayhours'       => $monhours,
                            'tuesdayhours'      => $tuehours,
                            'wednesdayhours'    => $wedhours,
                            'thursdayhours'     => $thuhours,
                            'fridayhours'       => $frihours,
                            'saturdayhours'     => $sathours,
                            'sundayhours'       => $sunhours,
                            'updatedby'         => $getMyid->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return 1;

                }catch(\Exception $error)
                {
                    return 0;
                }
            }
            elseif($request->get('basistypeid') == '8') //Project based
            {
                try{
                    DB::table('employee_basicsalaryinfo')
                        ->where('employeeid',$request->get('employeeid'))
                        ->update([
                            'salarybasistype'   => $request->get('basistypeid'),
                            'paymenttype'       => $request->get('paymenttype'),
                            'amount'            => $request->get('salaryamount'),
                            'projectbasedtype'  => $request->get('projectbasedtype'),
                            'hoursperday'       => $request->get('hoursperday'),
                            // 'mondays'           => 1,
                            // 'tuesdays'          => 1,
                            // 'wednesdays'        => 1,
                            // 'thursdays'         => 1,
                            // 'fridays'           => 1,
                            // 'saturdays'         => $request->get('saturdaywork'),
                            // 'sundays'           => $request->get('sundaywork'),
                            // 'shiftid'           => 0,
                            'updatedby'         => $getMyid->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return 1;

                }catch(\Exception $error)
                {
                    return 0;
                }

            }
        }
    }
    public function tabbasicsalaryupdatetimesched(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        // return $request->all();
        if($request->get('action') == 'get')
        {

            $timescheds = DB::table('employee_basishourly')
                ->where('employeeid', $request->get('employeeid'))
                ->where('day', $request->get('selectedday'))
                ->where('deleted','0')
                ->get();

            if(count($timescheds) > 0)
            {
                foreach($timescheds as $timesched)
                {
                    if($timesched->timeshift != 'mor')
                    {
                        $timesched->timein = date('h:i:s',strtotime($timesched->timein));
                        $timesched->timeout = date('h:i:s',strtotime($timesched->timeout));
                    }
                }
            }
    
            return view('hr.employees.info.basicsalary_hourlyschedule')
                ->with('timescheds',$timescheds)
                ->with('selectedday',$request->get('selectedday'))
                ->with('employeeid',$request->get('employeeid'));

        }elseif($request->get('action') == 'add')
        {
            
            try{

                if($request->get('selectedshift') == 'mor')
                {
                    $timein     = $request->get('timein');
                    $timeout    =  $request->get('timeout');
                }else{
                    $timein     = date('H:i:s',strtotime($request->get('timein').' PM'));
                    $timeout    = date('H:i:s',strtotime($request->get('timeout').' PM'));
                }

                DB::table('employee_basishourly')
                    ->insert([
                        'employeeid'        => $request->get('employeeid'),
                        'day'               => $request->get('selectedday'),
                        'timein'            => $timein,
                        'timeout'           => $timeout,
                        'timeshift'         => $request->get('selectedshift'),
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);

                return 1;
            }catch(\Exception $error){

                DB::table('zerrorlogs')
                    ->insert([
                        'error'=>$error,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>date('Y-m-d H:i:s')
                    ]);

                return 0;

            }

        }elseif($request->get('action') == 'update'){
            try{

                if($request->get('selectedshift') == 'mor')
                {
                    $timein     = $request->get('timein');
                    $timeout    =  $request->get('timeout');
                }else{
                    $timein     = date('H:i:s',strtotime($request->get('timein').' PM'));
                    $timeout    = date('H:i:s',strtotime($request->get('timeout').' PM'));
                }

                DB::table('employee_basishourly')
                    ->where('id', $request->get('timeschedid'))
                    ->update([
                        'timein'            => $timein,
                        'timeout'           => $timeout,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);

                return 1;
            }catch(\Exception $error){

                DB::table('zerrorlogs')
                    ->insert([
                        'error'=>$error,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>date('Y-m-d H:i:s')
                    ]);

                return 0;

            }

        }elseif($request->get('action') == 'delete'){
            try{

                DB::table('employee_basishourly')
                    ->where('id', $request->get('timeschedid'))
                    ->update([
                        'deleted'           => 1,
                        'deletedby'         => auth()->user()->id,
                        'deleteddatetime'   => date('Y-m-d H:i:s')
                    ]);

                return 1;
            }catch(\Exception $error){

                DB::table('zerrorlogs')
                    ->insert([
                        'error'=>$error,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>date('Y-m-d H:i:s')
                    ]);

                return 0;

            }
        }

    }
}
