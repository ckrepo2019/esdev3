<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;
use Crypt;
use File;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Models\HR\HRDeductions;
use App\Models\HR\HREmployeeAttendance;
class HREmployeeCredentialsController extends Controller
{
    public function tabcredsindex(Request $request)
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
        return view('hr.employees.info.credentials')
            ->with('profileinfoid',$teacherid)
            ->with('credentials',$credentials)
            ->with('employeecredentials',$employeecredentials);
    }
    public function tabcredsupload(Request $request)
    {
        
        // return $action;
        date_default_timezone_set('Asia/Manila');

        // $action = Crypt::decrypt($action);


        $employeename = DB::table('teacher')
            ->join('users', 'teacher.id','=','users.id')
            ->where('teacher.id', $request->get('employeeid'))
            ->first();
            
        // if($action == 'add'){

            $credentialdescription = Db::table('employee_credentialtypes')
                ->where('id', $request->get('credentialid'))
                ->first();

            $urlFolder = str_replace('http://','',$request->root());

            if (! File::exists(public_path().'employeecredentials/'.$credentialdescription->description)) {

                $path = public_path('employeecredentials/'.$credentialdescription->description);

                if(!File::isDirectory($path)){
                    
                    File::makeDirectory($path, 0777, true, true);

                }
                
            }
            
            if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/employeecredentials/'.$credentialdescription->description)) {

                $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/employeecredentials/'.$credentialdescription->description;
                
                if(!File::isDirectory($cloudpath)){

                    File::makeDirectory($cloudpath, 0777, true, true);
                    
                }
                
            }


            $file = $request->file('credential');
            
            $extension = $file->getClientOriginalExtension();
    
            $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/employeecredentials/'.$credentialdescription->description.'/';
    
    
            try{
    
                $file->move($clouddestinationPath, $employeename->email.'-'.strtoupper($employeename->lastname).' '.strtoupper($employeename->firstname[0].'.').$extension);
    
            }
            catch(\Exception $e){
               
        
            }
    
            $destinationPath = public_path('employeecredentials/'.$credentialdescription->description.'/');
    
            
            try{
    
                $file->move($destinationPath, $employeename->email.'-'.strtoupper($employeename->lastname).' '.strtoupper($employeename->firstname[0].'.').$extension);
    
            }
            catch(\Exception $e){
               
        
            }
    
            // copy($destinationPath.$employeename->email.'-'.strtoupper($employeename->lastname).' '.strtoupper($employeename->firstname[0].'.').$extension, $destinationPath.$employeename->email.'-'.strtoupper($employeename->lastname).' '.strtoupper($employeename->firstname[0].'.').$extension);
    
            $uploadedby = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first();
    
            $checkifexists = DB::table('employee_credentials')
                ->where('employeeid', $request->get('employeeid'))
                ->where('credentialtypeid',$request->get('credentialid'))
                ->where('deleted','0')
                ->get();
    
            if(count($checkifexists) == 0){
    
                DB::table('employee_credentials')
                    ->insert([
                        'employeeid'            => $request->get('employeeid'),
                        'credentialtypeid'      => $request->get('credentialid'),
                        'filepath'              => 'employeecredentials/'.$credentialdescription->description.'/'.$employeename->email.'-'.strtoupper($employeename->lastname).' '.strtoupper($employeename->firstname[0].'.').$extension,
                        'extension'             => $extension,
                        'uploadedby'            => $uploadedby->id,
                        'uploadeddatetime'      => date('Y-m-d H:i:s')
                    ]);
    
            }else{
    
                DB::table('employee_credentials')
                    ->where('employeeid',$request->get('employeeid'))
                    ->where('credentialtypeid',$request->get('credentialid'))
                    ->update([
                        'filepath'              => 'employeecredentials/'.$credentialdescription->description.'/'.strtoupper($employeename->lastname).' '.strtoupper($employeename->firstname[0].'.').$extension,
                        'extension'             => $extension,
                        'uploadedby'            => $uploadedby->id,
                        'uploadeddatetime'      => date('Y-m-d H:i:s')
                    ]);
    
            }
    
            return back();

        // // }
        // // elseif($action == 'delete'){
        //     DB::table('employee_credentials')
        //         ->where('employeeid',$request->get('employeeid'))
        //         ->where('id',$request->get('credentialid'))
        //         ->update([
        //             'deleted'   => 1
        //         ]);
        // // }
    }
    public function tabcredsdelete(Request $request)
    {
        
        DB::table('employee_credentials')
        ->where('employeeid',$request->get('employeeid'))
        ->where('credentialtypeid',$request->get('credentialid'))
        ->update([
            'deleted'   => 1
        ]);
        
    }
}
