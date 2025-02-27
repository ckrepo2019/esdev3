<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use DateTime;
use DateInterval;
use DatePeriod;
class HRPayrollWeeklyController extends Controller
{
    // Gian Additional

    public static function salarytype(Request $request){

        $salarytype = DB::table('employee_basistype')
            ->where('deleted', 0)
            ->select('id', 'type as text')
            ->get();

        return $salarytype;
    }

    public static function weekly_index(Request $request){
        $employee_type = $request->get('employee_type');

        $employees = DB::table('teacher')
            ->select('teacher.id','lastname','firstname','middlename','suffix','amount as salaryamount','utype as designation')
            ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->where('employee_basicsalaryinfo.deleted','0')
            ->where('employee_basicsalaryinfo.salarybasistype', $employee_type)
            ->where('employee_basicsalaryinfo.salarybasistype', '!=', null)
            ->where('teacher.deleted','0')
            ->where('teacher.isactive','1')
            ->orderBy('lastname','asc')
            ->get();

        return view('hr.payroll.v3.weekly_index')
            ->with('employees',$employees);;
    }
    

}
