<?php

namespace App\Http\Controllers\HRControllers\HREmployeeProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class HRCredentialInfoController extends Controller
{
    public function index(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');
        
        $teacherid = $request->get('employeeid');
    
        $credentials = Db::table('employee_credentialtypes')
            ->where('deleted','0')
            ->get();

        $employeecredentials = DB::table('employee_credentials')
            ->where('employeeid',$teacherid)
            ->where('deleted','0')
            ->get();
            

            // return $deductiontypes;
        return view('hr.employeeprofile.credentials')
            ->with('profileinfoid',$teacherid)
            ->with('credentials',$credentials)
            ->with('employeecredentials',$employeecredentials);
    }
    public function employeecredentialdelete(Request $request)
    {
        // return $request->all();
        DB::table('employee_credentials')
        ->where('employeeid',$request->get('employeeid'))
        ->where('credentialtypeid',$request->get('credentialid'))
        ->update([
            'deleted'   => 1
        ]);
        
    }
}
