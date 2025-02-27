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
class HREmployeePrintablesController extends Controller
{
    public function allowancesprintables(Request $request)
    {
        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();
        
        $semester = DB::table('semester')
            ->where('isactive', 1)
            ->first();

        return view('hr.employees.allowanceprintindex')
            ->with('sy', $sy)
            ->with('semester', $semester);
    }
    public function deductionsprintables(Request $request)
    {
        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();
        
        $semester = DB::table('semester')
            ->where('isactive', 1)
            ->first();

        return view('hr.employees.deductionprintindex')
            ->with('sy', $sy)
            ->with('semester', $semester);
    }
    public function deductionslist(Request $request)
    {
        $standarddeductions = DB::table('deduction_standard')
            ->where('deleted', 0)
            ->get();

        $otherdeductions = DB::table('deduction_others')
            ->where('deleted', 0)
            ->get();

        $deductions = $standarddeductions->merge($otherdeductions);

        return $deductions;
    }
    public function allowanceslist(Request $request)
    {
        $allowances = DB::table('allowance_standard')
            ->where('deleted', 0)
            ->get();

        return $allowances;
    }
    public function printdeduction(Request $request)
    {

        $payrollid = $request->get('payrollid');
        $deductionid = $request->get('deductionid');
        $deductiondesc = $request->get('deductiondesc');

        
        if ($payrollid == null) {
            $payrollid = 0;
        }
        $schoolinfo = DB::table('schoolinfo')
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->select('schoolinfo.*','refregion.regDesc','refcitymun.citymunDesc')
            ->get();

        

        // return collect($deductioninfo);

        $payrollinfo = DB::table('hr_payrollv2')
            ->where('id', $payrollid)
            ->where('deleted','0')
            ->first();

        if ($deductiondesc == 'PAG-IBIG' || $deductiondesc == 'PHILHEALTH' || $deductiondesc == 'SSS') {
            $deductioninfo = DB::table('deduction_standard')
                ->where('id', $deductionid)
                ->where('deleted', 0)
                ->first();

            $particulars = DB::table('hr_payrollv2historydetail')			
                ->select('teacher.id','employee_personalinfo.sssid','employee_personalinfo.philhealtid','employee_personalinfo.pagibigid', DB::raw('CONCAT_WS(" ", COALESCE(lastname, ""), COALESCE(firstname, ""), COALESCE(middlename, "")) AS full_name'), 'suffix', 'teacher.picurl', 'hr_payrollv2historydetail.amountpaid')
                ->leftJoin('teacher', 'hr_payrollv2historydetail.employeeid', '=', 'teacher.id')
                ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                ->where('employee_personalinfo.departmentid','!=',6)
                ->where('hr_payrollv2historydetail.payrollid', $payrollid)
                ->where('hr_payrollv2historydetail.particularid', $deductionid)
                ->where('hr_payrollv2historydetail.particulartype', 1)
                ->where('hr_payrollv2historydetail.deleted', '0')
                ->where('teacher.deleted', '0')
                ->orderBy('teacher.lastname', 'ASC')
                ->distinct()
                ->get();
        } else {
            $deductioninfo = DB::table('deduction_others')
                ->where('id', $deductionid)
                ->where('deleted', 0)
                ->first();

            $particulars = DB::table('hr_payrollv2historydetail')			
                ->select('teacher.id','employee_personalinfo.sssid','employee_personalinfo.philhealtid','employee_personalinfo.pagibigid', DB::raw('CONCAT_WS(" ", COALESCE(lastname, ""), COALESCE(firstname, ""), COALESCE(middlename, "")) AS full_name'), 'suffix', 'teacher.picurl', 'hr_payrollv2historydetail.amountpaid')
                ->leftJoin('teacher', 'hr_payrollv2historydetail.employeeid', '=', 'teacher.id')
                ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                ->where('employee_personalinfo.departmentid','!=',6)
                ->where('hr_payrollv2historydetail.payrollid', $payrollid)
                ->where('hr_payrollv2historydetail.deductionid', $deductionid)
                ->where('hr_payrollv2historydetail.particulartype', 2)
                ->where('hr_payrollv2historydetail.deleted', '0')
                ->where('teacher.deleted', '0')
                ->orderBy('teacher.lastname', 'ASC')
                ->distinct()
                ->get();
        }
        
        
        $totalpaiddeduction = 0;
        
        if (count($particulars) > 0) {
            foreach ($particulars as $particular) {
                $totalpaiddeduction += $particular->amountpaid;
                $particular->totalpaiddeduction = $totalpaiddeduction;
            }
        }
        
        $pdf = PDF::loadview('hr/employees/pdf_deduction',compact('schoolinfo','particulars','payrollinfo','deductioninfo','totalpaiddeduction'));
        return $pdf->stream('Payslip - ' . ($payrollinfo->datefrom ?? 'unknown') . '_' . ($payrollinfo->dateto ?? 'unknown') . '_' . date('Y') . '.pdf');
    }

    public function printallowance(Request $request)
    {
        $payrollid = $request->get('payrollid');
        $allowanceid = $request->get('allowanceid');

        if ($payrollid == null) {
            $payrollid = 0;
        }
        // return $request->all();
        $schoolinfo = DB::table('schoolinfo')
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->select('schoolinfo.*','refregion.regDesc','refcitymun.citymunDesc')
            ->get();

        $allowanceinfo = DB::table('allowance_standard')
            ->where('id', $allowanceid)
            ->where('deleted', 0)
            ->first();

        // return collect($allowanceinfo);

        $payrollinfo = DB::table('hr_payrollv2')
            ->where('id', $payrollid)
            ->where('deleted','0')
            ->first();

        $particulars = DB::table('hr_payrollv2historydetail')			
            ->select('teacher.id', DB::raw('CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) AS full_name'), 'suffix', 'teacher.picurl', 'hr_payrollv2historydetail.amountpaid')
            ->leftJoin('teacher', 'hr_payrollv2historydetail.employeeid', '=', 'teacher.id')
            ->leftJoin('hr_payrollv2history', 'hr_payrollv2historydetail.employeeid', '=', 'hr_payrollv2history.employeeid')
            ->where('hr_payrollv2historydetail.payrollid', $payrollid)
            ->where('hr_payrollv2historydetail.particularid', $allowanceid)
            ->where('hr_payrollv2history.released', 1)
            ->where('hr_payrollv2historydetail.deleted', '0')
            ->where('teacher.deleted', '0')
            ->distinct()
            ->get();
            
        $totalpaidallowance = 0;
        
        if (count($particulars) > 0) {
            foreach ($particulars as $particular) {
                $totalpaidallowance += $particular->amountpaid;
                $particular->totalpaidallowance = $totalpaidallowance;
            }
        }
        
        $pdf = PDF::loadview('hr/employees/pdf_allowance',compact('schoolinfo','particulars','payrollinfo','allowanceinfo','totalpaidallowance'));
        return $pdf->stream('Payslip - ' . ($payrollinfo->datefrom ?? 'unknown') . '_' . ($payrollinfo->dateto ?? 'unknown') . '_' . date('Y') . '.pdf');
    }

}
