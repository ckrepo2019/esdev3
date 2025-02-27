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

class HREmployeeThirteenthMonthController extends Controller
{
    public function index(Request $request)
    {
        $employees = DB::table('teacher')
            ->select('teacher.id', DB::raw('CONCAT_WS(" ", COALESCE(lastname, ""), COALESCE(firstname, ""), COALESCE(middlename, "")) AS full_name'),
                'suffix',
                'amount as salaryamount',
                'utype as designation',
                'employee_basicsalaryinfo.salarybasistype',
                'employee_basicsalaryinfo.amount',
                'hr_departments.department',
                'employee_personalinfo.departmentid')
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->leftJoin('hr_departments', 'employee_personalinfo.departmentid', '=', 'hr_departments.id')
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->where('employee_basicsalaryinfo.deleted', 0)
            ->where('employee_basicsalaryinfo.salarybasistype', '!=', NULL)
            ->orderBy('lastname', 'asc')
            ->get();
        
        return view('hr.employees.employeethirteenthmonth')
            ->with('employees', $employees);
    }


    public function loadrange(Request $request){
        $range = DB::table('thirteenth_range')
            ->select('id', 'description as text')
            ->where('deleted', 0)
            ->get();

        return response()->json($range);
    }

    public function loaddepartments(Request $request){
        $departments = DB::table('hr_departments')
            ->select('id', 'department as text')
            ->where('deleted', 0)
            ->get();

        return response()->json($departments);
    }
    
}
