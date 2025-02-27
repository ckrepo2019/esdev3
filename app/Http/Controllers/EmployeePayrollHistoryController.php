<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
class EmployeePayrollHistoryController extends Controller
{
    public function employeepayrolldetails(Request $request)
    {
        
        if(auth()->user()->type == '1' || Session::get('currentPortal') == '1'){

            $extends = "teacher.layouts.app";
            
        }elseif(auth()->user()->type == '2' || Session::get('currentPortal') == '2'){

            $extends = "principalsportal.layouts.app2";

        }elseif(auth()->user()->type == '3' || auth()->user()->type == '8'  || Session::get('currentPortal') == '3' || Session::get('currentPortal') == '8'){

            $extends = "registrar.layouts.app";

        }elseif(auth()->user()->type == '4' || auth()->user()->type == '15' || Session::get('currentPortal') == '4' || Session::get('currentPortal') == '15'){

            $extends = "finance.layouts.app";

        }elseif(auth()->user()->type == '6' || Session::get('currentPortal') == '6'){

            $extends = "adminPortal.layouts.app2";

        }elseif(auth()->user()->type == '10' || Session::get('currentPortal') == '10'){

            $extends = "hr.layouts.app";

        }elseif(auth()->user()->type == '12' || Session::get('currentPortal') == '12'){

            $extends = "adminITPortal.layouts.app";

        }else{
            $extends = "general.defaultportal..layouts.app";
        }
        $pay = DB::table('payroll_history')
            ->select('payroll_history.*')
            ->join('teacher','payroll_history.employeeid','=','teacher.id')
            ->where('teacher.userid', auth()->user()->id)
            // ->where('payroll_history.isreleased','1')
            ->where('payroll_history.deleted','0')
            ->orderByDesc('datereleased')
            ->get();
            
        if(count($pay)>0)
        {
            foreach($pay as $paydetail)
            {
                $paydetails = DB::table('payroll_historydetail')
                    ->where('headerid', $paydetail->id)
                    ->where('payroll_historydetail.deleted','0')
                    ->get();

                $paydetail->details = $paydetails;
            }
        }
        // return $pay;
        return view('globalfiles.blades.employeepayrollhistory')->with('pays', $pay)->with('extends', $extends);
    }
}
