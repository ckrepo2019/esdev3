<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class HRPayrollHistoryController extends Controller
{
    
    public function index()
    {
        date_default_timezone_set('Asia/Manila');
        $payrolldates = Db::table('payroll')
            ->get();
        $payrollArray = array();
        foreach($payrolldates as $dates){
            
            $employeesArray = array();
            $employees = Db::table('payrolldetail')
                ->select('payrolldetail.id','payrolldetail.headerid','payrolldetail.netpay','payrolldetail.payslipnumber','payrolldetail.releaseddatetime','teacher.id as employeeid','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix','teacher.picurl','usertype.utype')
                ->join('teacher','payrolldetail.employeeid','=','teacher.id')
                ->join('usertype','teacher.usertypeid','=','usertype.id')
                // ->join('payrolldeductiondetail','payrolldetail.employeeid','=','payrolldeductiondetail.employeeid')
                // ->join('job_deduction','payrolldeductiondetail.deductionid','=','job_deduction.id')
                // ->join('payrollearnings','payrolldetail.employeeid','=','payrollearnings.employeeid')
                ->where('payrolldetail.headerid',$dates->id)
                ->get();
            if(count($employees) == 0){

            }
            else{
                
                foreach($employees as $employee){
                    $deductions = Db::table('payrolldeductiondetail')
                        ->select('job_deduction.description','job_deduction.amount')
                        ->join('job_deduction','payrolldeductiondetail.deductionid','=','job_deduction.id')
                        ->where('payrolldeductiondetail.employeeid',$employee->employeeid)
                        ->where('payrolldeductiondetail.payrollid',$employee->headerid)
                        ->get();
                    $reimbursements = Db::table('payrollearnings')
                        ->select('payrollearnings.description','payrollearnings.amount','payrollearnings.type')
                        ->where('payrollearnings.employeeid',$employee->employeeid)
                        ->where('payrollearnings.payrollid',$employee->headerid)
                        ->where('payrollearnings.deleted','0')
                        ->get();
                    $employee->releaseddatetime = date('F d, Y h:i:s A', strtotime($employee->releaseddatetime));
                    array_push($employeesArray,(object)array(
                        'employeeinfo' => $employee,
                        'deductions' => $deductions,
                        'reimbursements' => $reimbursements
                    ));
                    
                }
            }
            // return $employeesArray;
            $dates->payrolldate = date('F d, Y', strtotime($dates->payrolldate));
            array_push($payrollArray,(object)array(
                'payroll' => $dates,
                'payrolldetail' => $employeesArray
            ));
        }
        // return $payrollArray;
        return view('hr.payrollhistory')
            ->with('payroll',$payrollArray);

    }
}
