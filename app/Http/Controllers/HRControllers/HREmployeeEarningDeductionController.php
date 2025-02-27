<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;
use Crypt;
use File;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Models\HR\HRDeductions;
use App\Models\HR\HREmployeeAttendance;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class HREmployeeEarningDeductionController extends Controller
{
    public function index(Request $request)
    {
        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();
        
        $semester = DB::table('semester')
            ->where('isactive', 1)
            ->first();

        return view('hr.employees.additionals')
               ->with('sy', $sy)
               ->with('semester', $semester);
    }
    public function loademployees(Request $request)
    {
        $employees = DB::table('teacher')
            ->select('teacher.id', DB::raw('CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) AS full_name'), 'suffix', 'amount as salaryamount', 'utype as designation', 'employee_basicsalaryinfo.salarybasistype','teacher.picurl')
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get();

        
        return $employees;
    }
    public function loadpayrollperiods(Request $request){
        $payrollperiods = DB::table('hr_payrollv2')
        ->select([
            'id',
            DB::raw("CONCAT(DATE_FORMAT(datefrom, '%b %d'), ' - ', DATE_FORMAT(dateto, '%b %d, %Y'), 
                    CASE 
                        WHEN salarytypeid = 4 THEN ' Monthly'
                        WHEN salarytypeid = 5 THEN ' Daily'
                        ELSE ''
                    END) as text"),
            'status',
            'leapyear',
            'updatedby',
            'updateddatetime',
            'createdby',
            'createddatetime',
            'deleted',
            'deletedby',
            'deleteddatetime',
            'salarytypeid',
        ])
        ->where('deleted', '0')
        //->where('status', '1')
        ->orderBy('datefrom', 'asc') // Order by datefrom in ascending order
        ->get();

        return $payrollperiods;
    }

    public function getearnings(Request $request){
        $payrollid = $request->get('payrollid');
        $teacherid = $request->get('teacherid');

        if ($payrollid == 0) {
            $addedparticulars = DB::table('hr_payrollv2additionalearndeduct')
                ->select([
                    'hr_payrollv2additionalearndeduct.*',
                    DB::raw("CONCAT(DATE_FORMAT(hr_payrollv2.datefrom, '%b %d'), ' - ', DATE_FORMAT(hr_payrollv2.dateto, '%b %d, %Y')) as daterange"),
                    'hr_payrollv2.status',
                    'hr_payrollv2.leapyear',
                    'hr_payrollv2.salarytypeid',
                ])
                ->leftJoin('hr_payrollv2', 'hr_payrollv2additionalearndeduct.payrollid', '=', 'hr_payrollv2.id')
                ->where('hr_payrollv2additionalearndeduct.deleted', 0)
                ->where('hr_payrollv2additionalearndeduct.employeeid', $teacherid)
                ->where('hr_payrollv2additionalearndeduct.type', 1)
                ->get();

        } else {
            $addedparticulars = DB::table('hr_payrollv2additionalearndeduct')
                ->select([
                    'hr_payrollv2additionalearndeduct.*',
                    DB::raw("CONCAT(DATE_FORMAT(hr_payrollv2.datefrom, '%b %d'), ' - ', DATE_FORMAT(hr_payrollv2.dateto, '%b %d, %Y')) as daterange"),
                    'hr_payrollv2.status',
                    'hr_payrollv2.leapyear',
                    'hr_payrollv2.salarytypeid',
                ])
                ->leftJoin('hr_payrollv2', 'hr_payrollv2additionalearndeduct.payrollid', '=', 'hr_payrollv2.id')
                ->where('hr_payrollv2additionalearndeduct.deleted', 0)
                ->where('hr_payrollv2additionalearndeduct.payrollid', $payrollid)
                ->where('hr_payrollv2additionalearndeduct.employeeid', $teacherid)
                ->where('hr_payrollv2additionalearndeduct.type', 1)
                ->get();
        }
        

        return $addedparticulars;
    }

    public function saveearnings(Request $request){
        $description = $request->get('earning_description');
        $amount = $request->get('earning_amount');
        $remarks = $request->get('earning_remarks');
        $payrollid = $request->get('payrollid');
        $teacherid = $request->get('teacherid');
        

        $checkhistoryifexists = DB::table('hr_payrollv2history')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $teacherid)
            ->where('deleted','0')
            ->first();
        
        // $ifexist = DB::table('hr_payrollv2addparticular')
        //         ->where('description', $description)
        //         ->where('payrollid', $payrollid)
        //         ->where('employeeid', $teacherid)
        //         ->count();

        $ifexist = DB::table('hr_payrollv2additionalearndeduct')
            ->where('description', $description)
            ->where('payrollid', $payrollid)
            ->where('employeeid', $teacherid)
            ->where('type', 1)
			->where('deleted','0')
            ->count();

        if ($ifexist) {

            return array((object)[
                'status' => 0,
                'message' => 'Already Exist!',
            ]);

        } else {
            $data = DB::table('hr_payrollv2additionalearndeduct')
                ->insert([
                    'payrollid'             => $payrollid,
                    'employeeid'            => $teacherid,
                    'description'           => $description,
                    'remarks'               => $remarks,
                    'type'                  => 1,
                    'amount'                => $amount,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            return array((object)[
                'status' => 1,
                'message' => 'Added Successfully!',
            ]);
        }
        
    }


    public function updateearning(Request $request){
        $description = $request->get('earning_description');
        $amount = $request->get('earning_amount');
        $remarks = $request->get('earning_remarks');
        $payrollid = $request->get('payrollid');
        $teacherid = $request->get('teacherid');
        $particularid = $request->get('particularid');
		
        // $ifexist = DB::table('hr_payrollv2additionalearndeduct')
        //         ->where('description', $description)
        //         ->where('payrollid', $payrollid)
        //         ->where('employeeid','!=', $teacherid)
        //         ->where('type', 1)
        //         ->where('id', '!=', $particularid)
        //         ->count();

        $ifexist = DB::table('hr_payrollv2additionalearndeduct')
            ->where('description', $description)
            ->where('employeeid', $teacherid)
            ->where('type', 1)
            ->where('id', $particularid)
            ->count();

       
        // if (!$ifexist) {

        //     return array((object)[
        //         'status' => 0,
        //         'message' => 'Already Exist!',
        //     ]);

        // } else {
            $data = DB::table('hr_payrollv2additionalearndeduct')
                ->where('id', $particularid)
                ->where('employeeid', $teacherid)
                ->update([
                    'description'           => $description,
                    'remarks'               => $remarks,
                    'amount'                => $amount,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

            $particulardata = DB::table('hr_payrollv2addparticular')
                ->where('additionalid', $particularid)
                ->where('employeeid', $teacherid)
                ->where('deleted', 0)
                ->update([
                    'description'           => $description,
                    'amount'                => $amount,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

            return array((object)[
                'status' => 1,
                'message' => 'Updated Successfully!',
            ]);
        // }
        
    }


    public function getemployeeearnings(Request $request){
        $particularid = $request->get('particularid');
        $teacherid = $request->get('teacherid');

        $data = DB::table('hr_payrollv2additionalearndeduct')
            ->where('id', $particularid)
            ->where('employeeid', $teacherid)
            ->where('type', 1)
            ->where('deleted', 0)
            ->first();
        
        return collect($data);
    }

    public function deleteearning(Request $request){
        $teacherid = $request->get('teacherid');
        $particularid = $request->get('particularid');

     
        $data = DB::table('hr_payrollv2additionalearndeduct')
            ->where('id', $particularid)
            ->where('type', 1)
            ->update([
                'deleted'                => 1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);

        return array((object)[
            'status' => 1,
            'message' => 'Deleted Successfully!',
        ]);
    }

    public function savededuction(Request $request){
        $description = $request->get('description');
        $amount = $request->get('amount');
        $remarks = $request->get('remarks');
        $payrollid = $request->get('payrollid');
        $teacherid = $request->get('teacherid');
        

        $checkhistoryifexists = DB::table('hr_payrollv2history')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $teacherid)
            ->where('deleted','0')
            ->first();
        
        // $ifexist = DB::table('hr_payrollv2addparticular')
        //         ->where('description', $description)
        //         ->where('payrollid', $payrollid)
        //         ->where('employeeid', $teacherid)
        //         ->count();

        $ifexist = DB::table('hr_payrollv2additionalearndeduct')
            ->where('description', $description)
            ->where('payrollid', $payrollid)
            ->where('employeeid', $teacherid)
            ->where('type', 2)
			->where('deleted','0')
            ->count();

        if ($ifexist) {
            return array((object)[
                'status' => 0,
                'message' => 'Already Exist!',
            ]);
        } else {
            $data = DB::table('hr_payrollv2additionalearndeduct')
                ->insert([
                    'payrollid'             => $payrollid,
                    'employeeid'            => $teacherid,
                    'description'           => $description,
                    'remarks'               => $remarks,
                    'type'                  => 2,
                    'amount'                => $amount,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            return array((object)[
                'status' => 1,
                'message' => 'Added Successfully!',
            ]);
        }
        
    }

    public function getdeductions(Request $request){
        $payrollid = $request->get('payrollid');
        $teacherid = $request->get('teacherid');

        if ($payrollid == 0) {
            $addedparticulars = DB::table('hr_payrollv2additionalearndeduct')
                ->select([
                    'hr_payrollv2additionalearndeduct.*',
                    DB::raw("CONCAT(DATE_FORMAT(hr_payrollv2.datefrom, '%b %d'), ' - ', DATE_FORMAT(hr_payrollv2.dateto, '%b %d, %Y')) as daterange"),
                    'hr_payrollv2.status',
                    'hr_payrollv2.leapyear',
                    'hr_payrollv2.salarytypeid',
                ])
                ->leftJoin('hr_payrollv2', 'hr_payrollv2additionalearndeduct.payrollid', '=', 'hr_payrollv2.id')
                ->where('hr_payrollv2additionalearndeduct.deleted', 0)
                ->where('hr_payrollv2additionalearndeduct.employeeid', $teacherid)
                ->where('hr_payrollv2additionalearndeduct.type', 2)
                ->get();


        } else {
            $addedparticulars = DB::table('hr_payrollv2additionalearndeduct')
                ->select([
                    'hr_payrollv2additionalearndeduct.*',
                    DB::raw("CONCAT(DATE_FORMAT(hr_payrollv2.datefrom, '%b %d'), ' - ', DATE_FORMAT(hr_payrollv2.dateto, '%b %d, %Y')) as daterange"),
                    'hr_payrollv2.status',
                    'hr_payrollv2.leapyear',
                    'hr_payrollv2.salarytypeid',
                ])
                ->leftJoin('hr_payrollv2', 'hr_payrollv2additionalearndeduct.payrollid', '=', 'hr_payrollv2.id')
                ->where('hr_payrollv2additionalearndeduct.deleted', 0)
                ->where('hr_payrollv2additionalearndeduct.payrollid', $payrollid)
                ->where('hr_payrollv2additionalearndeduct.employeeid', $teacherid)
                ->where('hr_payrollv2additionalearndeduct.type', 2)
                ->get();
        }
        

        return $addedparticulars;
    }
    
    public function getemployeedeductions(Request $request){
        $particularid = $request->get('particularid');
        $teacherid = $request->get('teacherid');

        $data = DB::table('hr_payrollv2additionalearndeduct')
            ->where('id', $particularid)
            ->where('employeeid', $teacherid)
            ->where('type', 2)
            ->where('deleted', 0)
            ->first();
        
        return collect($data);
    }

    public function updatededuction(Request $request){
        $description = $request->get('description');
        $amount = $request->get('amount');
        $remarks = $request->get('remarks');
        $payrollid = $request->get('payrollid');
        $teacherid = $request->get('teacherid');
        $particularid = $request->get('particularid');

        $ifexist = DB::table('hr_payrollv2additionalearndeduct')
                ->where('description', $description)
                ->where('payrollid', $payrollid)
                ->where('employeeid', $teacherid)
                ->where('type', 2)
                ->where('id', '!=', $particularid)
                ->count();

        if ($ifexist) {
            return array((object)[
                'status' => 0,
                'message' => 'Already Exist!',
            ]);
        } else {
            $data = DB::table('hr_payrollv2additionalearndeduct')
                ->where('id', $particularid)
                ->update([
                    'description'           => $description,
                    'remarks'               => $remarks,
                    'amount'                => $amount,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

            return array((object)[
                'status' => 1,
                'message' => 'Updated Successfully!',
            ]);
        }
        
    }

    public function deletededuction(Request $request){
        $teacherid = $request->get('teacherid');
        $particularid = $request->get('particularid');

     
        $data = DB::table('hr_payrollv2additionalearndeduct')
            ->where('id', $particularid)
            ->where('type', 2)
            ->update([
                'deleted'                => 1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);

        return array((object)[
            'status' => 1,
            'message' => 'Deleted Successfully!',
        ]);
    }
    
    
   
}
