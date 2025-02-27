<?php
namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Illuminate\Support\Facades\View;
use Barryvdh\Snappy\Facades\SnappyPdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use DatePeriod;
class HRPayrollV4Controller extends Controller
{
    public function index(Request $request)
    {
        $employees = DB::table('teacher')
            ->select('teacher.id', 'lastname', 'firstname', 'middlename', 'suffix', 'amount as salaryamount', 'utype as designation')
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('employee_basicsalaryinfo.deleted', '0')
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get();

        // return $employees;
        $payrollperiod = DB::table('hr_payrollv2')
            ->where('status', 1)
            ->first();

        return view('hr.payroll.v4.indexv4')
            ->with('employees', $employees)
            ->with('payrollperiod', $payrollperiod);
    }

    public function payrolldates(Request $request)
    {
        $salid = $request->get('salid');
        if ($request->get('action') != 'closepayroll' && $request->get('action') != 'getnumberofreleased') {
            $dates = explode(' - ', $request->get('dates'));
            $datefrom = date('Y-m-d', strtotime($dates[0]));
            $dateto = date('Y-m-d', strtotime($dates[1]));
        }

        $payrolldate = DB::table('hr_payrollv2')->insertGetId([
            'datefrom' => $datefrom,
            'dateto' => $dateto,
            'salarytypeid' => $salid,
            'createdby' => auth()->user()->id,
            'createddatetime' => now(),
        ]);

        return 1;
    }
}
