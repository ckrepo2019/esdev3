<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Crypt;
use File;
use Image;
use Session;
class EmployeeOvertimeController extends Controller
{
    
    public function applyindex()
    {
        date_default_timezone_set('Asia/Manila');
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;
        if(Session::get('currentPortal') == 1){

            $extends = "teacher.layouts.app";
            
        }elseif(Session::get('currentPortal') == 2){

            $extends = "principalsportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 3  ||  Session::get('currentPortal') == 8){

            $extends = "registrar.layouts.app";

        }elseif(Session::get('currentPortal') == 4  ||  Session::get('currentPortal') == 15){

            $extends = "finance.layouts.app";

        }elseif(Session::get('currentPortal') == 6){

            $extends = "adminPortal.layouts.app2";

        }elseif(Session::get('currentPortal') == 10 || $refid == 26){

            $extends = "hr.layouts.app";

        }elseif(Session::get('currentPortal') == 12){

            $extends = "adminITPortal.layouts.app";

        }elseif(Session::get('currentPortal') == 14){

            $extends = "deanportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 16){

            $extends = "chairpersonportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 18){

            $extends = "ctportal.layouts.app2";

        }else{

            $extends = "general.defaultportal.layouts.app";

        }

        $id = DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->id;
        
        $overtimes = Db::table('employee_overtime')
            ->select(
                'employee_overtime.*',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix'
                )
            ->join('teacher','.employee_overtime.employeeid','=','teacher.id')
            ->where('employee_overtime.deleted','0')
            ->where('employee_overtime.employeeid', $id)
            ->orderByDesc('employee_overtime.createddatetime')
            ->get();
        
        return view('general.overtimeapplication.index')
            ->with('id', $id)
            ->with('overtimes', $overtimes)
            ->with('extends', $extends);
    }
    public function applysubmit(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        $employeeid     = $request->get('employeeid');
        $remarks        = $request->get('remarks');
        $selecteddates  = json_decode($request->get('selecteddates'));
        
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
        return 1;
    }
    public function updateremarks(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        if($request->ajax())
        {
            Db::table('employee_overtime')
                ->where('id',$request->get('overtimeid'))
                ->update([
                    'remarks'           => $request->get('remarks'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
    }
    public function deleteovertime(Request $request)
    {
        // return $request->all();
        date_default_timezone_set('Asia/Manila');
        if($request->ajax())
        {
            Db::table('employee_overtime')
                ->where('id',$request->get('overtimeid'))
                ->update([
                    'deleted'           =>  '1',
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
    }
}
