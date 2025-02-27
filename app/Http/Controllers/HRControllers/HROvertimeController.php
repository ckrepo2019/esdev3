<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use DatePeriod;
use DateTime;
use DateInterval;
use \Carbon\CarbonPeriod;
class HROvertimeController extends Controller
{
    public function index()
    {

        $employees = DB::table('teacher')
            ->select('teacher.*')
            ->join('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->where('teacher.isactive','1')
            ->where('teacher.deleted','0')
            ->get();
        
        $overtimes = Db::table('employee_overtime')
            ->select(
                'employee_overtime.*',
                'employee_overtime.remarks',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix'
                )
            ->join('teacher','.employee_overtime.employeeid','=','teacher.id')
            ->where('employee_overtime.deleted','0')
            ->get();

        $countpending = collect($overtimes)->where('overtimestatus','0')->count();
        $countapproved = collect($overtimes)->where('overtimestatus','1')->count();
        $countdisapproved = collect($overtimes)->where('overtimestatus','2')->count();
        return view('hr.overtime.index')
            ->with('employees', $employees)
            ->with('countpending', $countpending)
            ->with('countapproved', $countapproved)
            ->with('countdisapproved', $countdisapproved);
    }
    public function filter(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');
        
        $dates = explode(' - ', $request->get('daterange'));
        $datefrom = date('Y-m-d',strtotime($dates[0]));
        $dateto = date('Y-m-d',strtotime($dates[1]));
        
        $my_id = DB::table('teacher')
            ->select('id')
            ->where('userid',auth()->user()->id)
            ->where('isactive','1')
            ->first();

        $hr_approvals = DB::table('hr_leavesappr')
            ->where('deleted','0')
            ->get();



        $approvals = DB::table('hr_leavesappr')
            ->where('deleted','0')
            ->get();
            
        $employees = DB::table('teacher')
            ->select('teacher.*')
            ->join('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->where('teacher.isactive','1')
            ->where('teacher.deleted','0')
            ->get();

        $overtimes = Db::table('employee_overtime')
            ->select(
                'employee_overtime.*',
                'employee_overtime.remarks',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix'
                )
            ->join('teacher','.employee_overtime.employeeid','=','teacher.id')
            ->where('employee_overtime.deleted','0')
            ->whereBetween('employee_overtime.datefrom',[$datefrom, $dateto])
            ->get();
            
        $countpending = collect($overtimes)->where('overtimestatus','0')->count();
        $countapproved = collect($overtimes)->where('overtimestatus','1')->count();
        $countdisapproved = collect($overtimes)->where('overtimestatus','2')->count();
        if(count($overtimes)>0)
        {
            foreach($overtimes as $overtime)
            {
                // $overtime->dates = DB::table('employee_overtimedetail')
                //                 ->where('headerid', $overtime->id)
                //                 ->where('deleted','0')
                //                 ->get();

                if(count($approvals) == 0)
                {
                    
                    $overtime->approvals = array();

                }else{
                    $checkstatus = DB::table('hr_overtimesapprdetails')
                        ->where('employeeovertimeid', $overtime->id)
                        // ->where('statusby', $approval->employeeid)
                        ->where('deleted', '0')
                        ->get();
                    
                    $disapproved = 0;

                    if(count($approvals) == count($checkstatus))
                    {

                        foreach($checkstatus as $checkstat)
                        {
                            
                            
                            if($checkstat->status == 3)
                            {
                                $disapproved+=1;
                            }
                            

                        }

                    }

                    $approvalstats = array();

                    foreach($approvals as $checkapprove)
                    {
                        $checkstatus = DB::table('hr_overtimesapprdetails')
                        ->where('employeeovertimeid', $overtime->id)
                        ->where('statusby', $checkapprove->employeeid)
                        ->where('deleted', '0')
                        ->first();

                        $nameofapproval = DB::table('teacher')
                            ->where('id',$checkapprove->employeeid)
                            ->first();

                        if($nameofapproval->middlename!= null)
                        {
                            $nameofapproval->middlename = $nameofapproval->middlename[0].'.';
                        }

                        if($checkstatus)
                        {
                            $checkstatus->name = $nameofapproval->firstname.' '.$nameofapproval->middlename.' '.$nameofapproval->lastname.' '.$nameofapproval->suffix;
                            array_push($approvalstats,$checkstatus);
                        }else{
                            array_push($approvalstats,(object)array(
                                'employeeovertimeid'   => $overtime->id,
                                'status'            => 2,
                                'statusby'          =>  $checkapprove->employeeid,
                                'name'              =>  $nameofapproval->firstname.' '.$nameofapproval->middlename.' '.$nameofapproval->lastname.' '.$nameofapproval->suffix
                            ));
                        }
                        

                    }
                    $overtime->approvals = $approvalstats;

                    $overtime->attachments = DB::table('employee_overtimeattachments')
                        ->where('headerid', $overtime->id)
                        ->where('deleted','0')
                        ->get();


                }

            }

        }
        return view('hr.overtime.results')
            ->with('countpending',$countpending)
            ->with('countapproved',$countapproved)
            ->with('countdisapproved',$countdisapproved)
            ->with('overtimes',$overtimes);
    }
    public function fileovertime(Request $request)
    {
        $employeeids    = $request->get('employeeids');
        $remarks        = $request->get('remarks');
        $selecteddates  = json_decode($request->get('selecteddates'));
        
        foreach($employeeids as $employeeid)
        {
            foreach($selecteddates as $date)
            {
                $checkifexists = DB::table('employee_overtime')
                    ->where('employeeid', $employeeid)
                    ->where('datefrom',$date->daterange)
                    ->where('deleted','0')
                    ->where('payrolldone','0')
                    ->first();
    
                if(!$checkifexists)
                {
                    DB::table('employee_overtime')
                        ->insert([
                            'employeeid'        => $employeeid,
                            'datefrom'          => $date->daterange,
                            'timefrom'          => $date->timefrom,
                            'timeto'            => $date->timeto,
                            'remarks'           => $remarks,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }  
        // foreach($selecteddates as $daterange)
        // {
        //     $datefrom = date('Y-m-d',strtotime(explode(' - ',$daterange->daterange)[0]));
        //     $dateto   = date('Y-m-d',strtotime(explode(' - ',$daterange->daterange)[1]));
        //     $period = CarbonPeriod::create($datefrom, $dateto);

        //     $dates = array();

        //     // Iterate over the period
        //     foreach ($period as $date) {
        //         array_push($dates,date('Y-m-d',strtotime($date)));
        //     }
        //     $daterange->dates = $dates;
        // }
    
        // foreach($employeeids as $employeeid)
        // {
        //     $checkifexists = DB::table('employee_overtime')
        //         ->where('employeeid', $employeeid)
        //         ->where('overtimestatus',0)
        //         ->where('deleted','0')
        //         ->count();

        //     if($checkifexists == 0)
        //     {
        //         foreach($selecteddates as $selecteddate)
        //         {
        //             $datefrom = date('Y-m-d',strtotime(explode(' - ',$selecteddate->daterange)[0]));
        //             $dateto   = date('Y-m-d',strtotime(explode(' - ',$selecteddate->daterange)[1]));
        //             $id =  DB::table('employee_overtime')
        //                     ->insertGetId([
        //                         'employeeid'        => $employeeid,
        //                         'datefrom'          => $datefrom,
        //                         'dateto'            => $dateto,
        //                         'timefrom'          => $selecteddate->timefrom,
        //                         'timeto'            => $selecteddate->timeto,
        //                         'remarks'           => $remarks,
        //                         'createdby'         => auth()->user()->id,
        //                         'createddatetime'   => date('Y-m-d H:i:s')
        //                     ]);
        //             foreach($selecteddate->dates as $date)
        //             {
        //                 DB::table('employee_overtimedetail')
        //                     ->insert([
        //                         'headerid'          => $id,
        //                         'odate'             => $date,
        //                         'timefrom'          => $selecteddate->timefrom,
        //                         'timeto'            => $selecteddate->timeto,
        //                         'createdby'         => auth()->user()->id,
        //                         'createddatetime'   => date('Y-m-d H:i:s')
        //                     ]);
        //             }
        //         }
        //     }
        // }        
    }
    public function delete(Request $request)
    {
        try{

            DB::table('employee_overtime')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);


                DB::table('employee_overtimedetail')
                ->where('headerid', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }

    }
    public function pending(Request $request)
    {
        try{
            DB::table('employee_overtime')
                ->where('id', $request->get('id'))
                ->update([
                    'overtimestatus'    => 0,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);


            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }

    }
    public function approve(Request $request)
    {
        // return $request->all();
        try{

            DB::table('employee_overtime')
                ->where('id', $request->get('id'))
                ->update([
                    'overtimestatus'    => 1,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);


                // DB::table('employee_overtimedetail')
                // ->where('headerid', $request->get('id'))
                // ->update([
                //     'deleted'           => 1,
                //     'deletedby'         => auth()->user()->id,
                //     'deleteddatetime'   => date('Y-m-d H:i:s')
                // ]);

            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }

    }
    public function disapprove(Request $request)
    {
        try{
            DB::table('employee_overtime')
                ->where('id', $request->get('id'))
                ->update([
                    'overtimestatus'    => 2,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }

    }
}
