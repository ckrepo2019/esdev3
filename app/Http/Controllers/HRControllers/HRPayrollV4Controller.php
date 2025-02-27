<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class HRPayrollV4Controller extends Controller
{
    public function index(Request $request)
    {
        $employees = DB::table('teacher')
            ->select('teacher.id','lastname','firstname','middlename','suffix','amount as salaryamount','utype as designation')
            ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
           ->where('employee_basicsalaryinfo.deleted','0')
            ->where('teacher.deleted','0')
            ->where('teacher.isactive','1')
            ->orderBy('lastname','asc')
            ->get();

        // return $employees;
        $payrollperiod = DB::table('hr_payrollv2')
            ->where('status', 1)
            ->first();

        return view('hr.payroll.v4.indexv4')
            ->with('employees',$employees)
            ->with('payrollperiod',$payrollperiod);
    }
}
