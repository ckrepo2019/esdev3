<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use File;
class HREmployeeProfileController extends Controller
{
    public function index(Request $request)
    {
        
        // return $request->query();
        $teacherid = $request->query('employeeid');
            
        $civilstatus = Db::table('civilstatus')
            ->where('deleted','0')
            ->get();

        $nationality = Db::table('nationality')
            ->where('deleted','0')
            ->get();

        $religion = Db::table('religion')
            ->where('deleted','0')
            ->get();

        $profile = Db::table('teacher')
            ->select(
                'teacher.id',
                'teacher.lastname',
                'teacher.middlename',
                'teacher.firstname',
                'teacher.suffix',
                'teacher.title',
                'teacher.licno',
                'teacher.tid',
                'teacher.deleted',
                'teacher.isactive',
                'teacher.picurl',
                'teacher.rfid',
                'teacher.employmentstatus',
                'hr_empstatus.description as empstatus',
                'usertype.utype',
                'usertype.id as designationid',
                'employee_personalinfo.nationalityid',
                'employee_personalinfo.religionid',
                'employee_personalinfo.dob',
                'employee_personalinfo.gender',
                'employee_personalinfo.address',
                'employee_personalinfo.contactnum',
                'employee_personalinfo.email',
                'employee_personalinfo.maritalstatusid',
                'employee_personalinfo.spouseemployment',
                'employee_personalinfo.numberofchildren',
                'employee_personalinfo.emercontactname',
                'employee_personalinfo.emercontactrelation',
                'employee_personalinfo.emercontactnum',
                'employee_personalinfo.departmentid',
                'employee_personalinfo.designationid',
                'employee_personalinfo.date_joined as datehired'
                )
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            ->leftJoin('hr_empstatus','teacher.employmentstatus','=','hr_empstatus.id')
            ->where('teacher.userid', $teacherid)
            ->first();

            // return collect($profile);
            $statustypes = DB::table('hr_empstatus')
            // ->where('title','like','%'.$request->get('title').'%')
            ->where('deleted','0')
            ->get();

        if(count($statustypes)>0)
        {
            foreach($statustypes as $statustype)
            {
                $statustype->count = DB::table('teacher')
                    ->where('isactive', '1')
                    // ->where('datehired', '!=', null)
                    ->where('employmentstatus', $statustype->id)
                    ->count();
                
            }
        }

        if($profile->nationalityid == 0){

            $profile->nationality = "";

        }else{

            $getnationality = Db::table('nationality')
                ->where('id', $profile->nationalityid)
                ->first();
                
            $profile->nationality = $getnationality->nationality;

        }

        if($profile->religionid == 0){

            $profile->religionname = "";

        }else{

            $getreligionname = Db::table('religion')
                ->where('id', $profile->religionid)
                ->first();
                
            $profile->religionname = $getreligionname->religionname;

        }

        if($profile->maritalstatusid == 0){

            $profile->civilstatus = "";

        }else{

            $getcivilstatus = Db::table('civilstatus')
                ->where('id', $profile->maritalstatusid)
                ->first();

            $profile->civilstatus = $getcivilstatus->civilstatus;

        }

        if($profile->dob == null){

            $profile->dobstring = "";

        }else{

            $profile->dobstring = date('F d, Y', strtotime($profile->dob));
        }

        if($profile->datehired == null){

            $profile->datehired = "";

            $profile->datehiredstring = "";

        }else{

            $profile->datehired = date('Y-m-d', strtotime($profile->datehired));

            $profile->datehiredstring = date('F d, Y', strtotime($profile->datehired));

        }

        // return collect($profile);

        $employee_accounts = Db::table('employee_accounts')
            ->where('employeeid',$teacherid)
            ->where('employee_accounts.deleted','0')
            ->get();
            
        $employee_familyinfo = Db::table('employee_familyinfo')
            ->where('employeeid',$teacherid)
            ->where('deleted','0')
            ->get();

        $employee_educationinfo = Db::table('employee_educationinfo')
            ->where('employeeid',$teacherid)
            ->where('deleted','0')
            ->get();

        $employee_experience = Db::table('employee_experience')
            ->where('employeeid',$teacherid)
            ->where('deleted','0')
            ->get();
            
        $profile->accounts = $employee_accounts;
        $profile->familyinfo = $employee_familyinfo;
        $profile->educationalbackground = $employee_educationinfo;
        $profile->experiences = $employee_experience;
        
        $nationality = Db::table('nationality')
            ->get();
        $civilstatus = Db::table('civilstatus')
            ->get();
        $religions = Db::table('religion')
            ->get();

        $departments = Db::table('hr_school_department')
            ->where('deleted','0')
            ->get();
        $designations = Db::table('usertype')
            ->where('deleted','0')
            ->get();

        
        
        if(count(DB::table('employee_basicsalaryinfo')->where('employeeid', $teacherid)->get()) == 0)
        {
            DB::table('employee_basicsalaryinfo')
                ->insert([
                    'employeeid'    => $teacherid,
                    'createdby'     => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        return view('hr.employees.info.index')
            ->with('profileinfo',$profile)
            ->with('nationality',$nationality)
            ->with('civilstatus',$civilstatus)
            ->with('religions',$religions)
            ->with('departments',$departments)
            ->with('designations',$designations);
    }
    public function getprofile(Request $request)
    {   
        $empid = $request->get('empid');
        $employeeid = $request->get('empid');
        $timeschedule = Db::table('employee_customtimesched')
        ->where('employeeid',$employeeid)
        ->where('deleted','0')
        ->get();


        // return $timeschedule;
        $nationalities = DB::table('nationality')
            ->where('deleted','0')
            ->get();
        $civilstatus = DB::table('civilstatus')
            ->where('deleted','0')
            ->get();
        $religion = DB::table('religion')
            ->where('deleted','0')
            ->get();
        // return $request->all();
        $employeeinfo = DB::table('teacher')
        ->select(
            'hr_departments.*',
            'employee_personalinfo.*',
            'employee_personalinfo.gender',
            'employee_personalinfo.dob',
            'employee_personalinfo.address',
            'employee_personalinfo.primaryaddress',
            'employee_personalinfo.email',
            'employee_personalinfo.contactnum',
            'employee_personalinfo.spouseemployment',
            'employee_personalinfo.numberofchildren',
            'employee_personalinfo.date_joined',
            'religion.religionname as religion',
            'civilstatus.civilstatus',
            'teacher.title',
            'teacher.lastname',
            'teacher.firstname',
            'teacher.middlename',
            'teacher.suffix',
            'teacher.licno',
            'teacher.isactive',
            'teacher.tid',
            'teacher.picurl',
            'teacher.rfid',
            'teacher.usertypeid',
            'teacher.employmentstatus as empstatus',
            'usertype.utype as designation',
            'hr_empstatus.description as employmentstatus')
        ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
        ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
        ->leftJoin('hr_empstatus','teacher.employmentstatus','=','hr_empstatus.id')
        ->leftJoin('religion','employee_personalinfo.religionid','=','religion.id')
        ->leftJoin('hr_departments','employee_personalinfo.departmentid','=','hr_departments.id')
        ->leftJoin('civilstatus','employee_personalinfo.maritalstatusid','=','civilstatus.id')
        ->where('teacher.id', $request->get('empid'))
        ->first();

        // return collect($employeeinfo);
        $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
            ->where('employeeid', $request->get('empid'))
            ->where('deleted','0')
            ->first();
        // return collect($basicsalaryinfo);
        $salarybasistypes = DB::table('employee_basistype')
            ->where('deleted','0')
            ->get();

        $salarytypes = DB::table('employee_basistype')
            ->select('id', 'type as text')
            ->where('deleted','0')
            ->get();
        $deductionstandards = DB::table('deduction_standard')
            ->where('deleted','0')
            ->get();

        $getcredentials = DB::table('employee_credentialtypes')
            ->where('deleted','0')
            ->get();

        $credentials = array();
        if(count($getcredentials)>0)
        {
            foreach($getcredentials as $credential)
            {
                $checkifexists  = DB::table('employee_credentials')
                    ->where('credentialtypeid', $credential->id)
                    ->where('employeeid', $request->get('empid'))
                    ->where('deleted', 0)
                    ->first();
            

                if($checkifexists)
                {
                    $credential->empcredid = $checkifexists->id;
                    $credential->filepath = $checkifexists->filepath;
                    $credential->filename = $checkifexists->filename;
                    $credential->extension = $checkifexists->extension;
                    $credential->uploadeddatetime = date('m/d/Y h:i a', strtotime($checkifexists->uploadeddatetime));
                    $credential->submitted = 1;
                    array_push($credentials, $credential);
                }else{
                    $credential->empcredid = 0;
                    $credential->filepath = null;
                    $credential->filename = null;
                    $credential->extension = null;
                    $credential->uploadeddatetime = null;
                    $credential->submitted = 0;
                    array_push($credentials, $credential);
                }
            }
        }
        $personalaccounts = DB::table('employee_accounts')
            ->where('employeeid', $request->get('empid'))
            ->where('deleted','0')
            ->get();
        // return collect($employeeinfo);

        // if(count(DB::table('employee_basicsalaryinfo')->where('employeeid', $empid)->get()) == 0)
        // {
        //     $shiftid = (object) array(
                
        //         'shiftid'   => 0
        //     );
        //     $attendancebased = 1;
        // }else{
        //     $shiftid = Db::table('employee_basicsalaryinfo')
        //         ->select('employee_basicsalaryinfo.shiftid')
        //         // ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
        //         ->where('employee_basicsalaryinfo.employeeid',$empid)
        //         ->where('employee_basicsalaryinfo.deleted','0')
        //         // ->where('employee_basistype.deleted','0')
        //         ->first();
        //     // return $shiftid
        //     $attendancebasedstatus = Db::table('employee_basicsalaryinfo')
        //         ->select('employee_basicsalaryinfo.attendancebased')
        //         ->where('employee_basicsalaryinfo.employeeid',$empid)
        //         ->where('employee_basicsalaryinfo.deleted','0')
        //         ->first();

        //     if(count(collect($attendancebasedstatus)) == 0)
        //     {
        //         $attendancebased = 1;
        //         DB::table('employee_basicsalaryinfo')
        //             ->where('employeeid', $empid)
        //             ->update([
        //                 'attendancebased' => 1
        //             ]);
        //     }else{
        //         $attendancebased = $attendancebasedstatus->attendancebased;
        //     }
        // }

        $attendancebased = DB::table('employee_basicsalaryinfo')
                            ->where('employeeid', $empid)
                            ->where('attendancebased', 1)
                            ->count();
        if ($attendancebased) {
            $attendancebased = 1;
        } else {
            $attendancebased = 0;
        }

        return view('hr.employees.profilev2.getprofile')
            ->with('employeeid',$request->get('empid'))
            ->with('nationalities',$nationalities)
            ->with('personalaccounts',$personalaccounts)
            ->with('employeeinfo',$employeeinfo)
            ->with('basicsalaryinfo',$basicsalaryinfo)
            ->with('civilstatus',$civilstatus)
            ->with('salarybasistypes',$salarybasistypes)
            ->with('deductionstandards',$deductionstandards)
            ->with('credentials',$credentials)
            ->with('salarytypes',$salarytypes)
            ->with('religion',$religion)
            ->with('employeeid',$employeeid)
            ->with('timeschedule',$timeschedule)
            ->with('attendancebased',$attendancebased);
            
            
    }
    public function profileinfoupdate(Request $request)
    {
        return $request->all();
    }
    public function tabsalaryinfo(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $alldays = array(
          'sundays' => 0,
          'mondays' => 0,
          'tuesdays' => 0,
          'wednesdays' => 0,
          'thursdays' => 0,
          'fridays' => 0,
          'saturdays' => 0
        );
        if($request->get('action') == 'updatesalaryinfo')
        {
            $typeid = $request->get('typeid');
            $employeeid = $request->get('employeeid');
            $days = $request->get('days');
            $hoursperday = $request->get('hoursperday');
            $rateperhour = $request->get('rateperhour');
            $rate = $request->get('rate');
            if(count($days) > 0)
            {
                foreach($alldays as $eachdaykey => $eachdayval)
                {
                    if(in_array($eachdaykey, $days))
                    {
                        $alldays[$eachdaykey] = 1;
                    }
                }   
            }
            
            $checkifexists = DB::table('employee_basicsalaryinfo')
                ->where('employeeid', $employeeid)
                ->where('deleted', 0)
                ->first();

            if($checkifexists)
            {
                $rowid = $checkifexists->id;
                DB::table('employee_basicsalaryinfo')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'salarybasistype'   => $typeid,
                        'amount'            => $rate,
                        'hoursperday'       => $hoursperday,
                        'rateperhour'       => $rateperhour,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }else{
                $rowid = DB::table('employee_basicsalaryinfo')
                    ->insertGetId([
                        'employeeid'        => $employeeid,
                        'salarybasistype'   => $salarybasistype,
                        'amount'            => $rate,
                        'hoursperday'       => $hoursperday,
                        'attendancebased'   => 1,
                        'rateperhour'       => $rateperhour,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }

            foreach($alldays as $eachdaykey => $eachdayval)
            {
                DB::table('employee_basicsalaryinfo')
                    ->where('id', $rowid)
                    ->update([
                        $eachdaykey   => $eachdayval,
                        rtrim($eachdaykey,'s').'hours'  => $rateperhour
                    ]);
            }
            return 1;

            
        }
    }






    public function changestatus(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        // return $request->all();
        try{
            DB::table('teacher')
                ->where('id', $request->get('id'))
                ->update([
                    'isactive'          => $request->get('status'),
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

            return back();
        }catch(\Exception $error)
        {
            return 0;
        }
    }
    public function uploadphoto(Request $request)
    {
        
        $sy = DB::table('sy')
            ->where('isactive','1')
            ->first();

        $urlFolder = str_replace('https://','',$request->root());

        if (! File::exists(public_path().'employeeprofile/'.$sy->sydesc)) {

            $path = public_path('employeeprofile/'.$sy->sydesc);

            if(!File::isDirectory($path)){
                
                File::makeDirectory($path, 0777, true, true);

            }else{
                
            }
            
        }
        
        if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc)) {

            $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc;
            
            if(!File::isDirectory($cloudpath)){

                File::makeDirectory($cloudpath, 0777, true, true);
                
            }
            
        }
        
        $lastname = str_replace(' ', '_', $request->lastname);
        $lastname = str_replace('', '.', $lastname);
            
        $data = $request->image;

        list($type, $data) = explode(';', $data);

        list(, $data)      = explode(',', $data);

        $data = base64_decode($data);

        $extension = 'png';

        $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc.'/'.$request->username.'_'.$lastname.'.'.$extension;
        
        try{

            file_put_contents($clouddestinationPath, $data);
            
        }
        catch(\Exception $e){
           
    
        }

        $destinationPath = public_path('employeeprofile/'.$sy->sydesc.'/'. $request->username.'_'.$lastname.'.'.$extension);
        
        file_put_contents($destinationPath, $data);

        DB::table('teacher')
            ->where('id',$request->employeeid)
            ->update([
                'picurl' => 'employeeprofile/'.$sy->sydesc.'/'. $request->username.'_'.$lastname.'.'.$extension
            ]);

        return asset('employeeprofile/'.$sy->sydesc.'/'. $request->username.'_'.$lastname.'.'.$extension);
    }
    public function updaterfid(Request $request)
    {
        $checkifregistered = DB::table('rfidcard')
            ->where('rfidcode', $request->get('rfid'))
            ->where('deleted','0')
            ->count();

        // if($checkifregistered == 0)
        // {
        //     return '2';
        // }else{
        //     $checkifexists = Db::table('teacher')
        //         ->where('rfid',$request->get('rfid') )
        //         ->get();
        //     // return $request->get('rfid');
        //     if(count($checkifexists) == 0){
    
                DB::table('teacher')
                    ->where('id', $request->get('id'))
                    ->update([
                        'rfid'              => $request->get('rfid'),
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
    
                    // return back();
                return '1';
    
        //     }else{
    
        //         return '0';
    
        //     }
        // }

    }
    public function tabprofileindex(Request $request)
    {

        $teacherid = $request->get('employeeid');
            
        $civilstatus = Db::table('civilstatus')
            ->where('deleted','0')
            ->get();

        $nationality = Db::table('nationality')
            ->where('deleted','0')
            ->get();

        $religion = Db::table('religion')
            ->where('deleted','0')
            ->get();

        $profile = Db::table('teacher')
            ->select(
                'teacher.id',
                'teacher.lastname',
                'teacher.middlename',
                'teacher.firstname',
                'teacher.suffix',
                'teacher.title',
                'teacher.licno',
                'teacher.tid',
                'teacher.deleted',
                'teacher.isactive',
                'teacher.picurl',
                'teacher.rfid',
                'teacher.employmentstatus',
                'usertype.utype',
                'usertype.id as designationid',
                'employee_personalinfo.nationalityid',
                'employee_personalinfo.religionid',
                'employee_personalinfo.dob',
                'employee_personalinfo.gender',
                'employee_personalinfo.address',
                'employee_personalinfo.primaryaddress',
                'employee_personalinfo.contactnum',
                'employee_personalinfo.email',
                'employee_personalinfo.maritalstatusid',
                'employee_personalinfo.spouseemployment',
                'employee_personalinfo.numberofchildren',
                'employee_personalinfo.emercontactname',
                'employee_personalinfo.emercontactrelation',
                'employee_personalinfo.emercontactnum',
                'employee_personalinfo.departmentid',
                // 'employee_personalinfo.designationid',
                'employee_personalinfo.date_joined as datehired'
                )
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            ->where('teacher.id', $teacherid)
            ->first();


        if($profile->nationalityid == 0){

            $profile->nationality = "";

        }else{

            $getnationality = Db::table('nationality')
                ->where('id', $profile->nationalityid)
                ->first();
                
            $profile->nationality = $getnationality->nationality;

        }

        if($profile->religionid == 0){

            $profile->religionname = "";

        }else{

            $getreligionname = Db::table('religion')
                ->where('id', $profile->religionid)
                ->first();
                
            $profile->religionname = $getreligionname->religionname;

        }

        if($profile->maritalstatusid == 0){

            $profile->civilstatus = "";

        }else{

            $getcivilstatus = Db::table('civilstatus')
                ->where('id', $profile->maritalstatusid)
                ->first();

            $profile->civilstatus = $getcivilstatus->civilstatus;

        }

        if($profile->dob == null){

            $profile->dobstring = "";

        }else{

            $profile->dobstring = date('F d, Y', strtotime($profile->dob));
        }

        if($profile->datehired == null){

            $profile->datehired = "";

            $profile->datehiredstring = "";

        }else{

            $profile->datehired = date('Y-m-d', strtotime($profile->datehired));

            $profile->datehiredstring = date('F d, Y', strtotime($profile->datehired));

        }

        // return collect($profile);

        $employee_accounts = Db::table('employee_accounts')
            ->where('employeeid',$teacherid)
            ->where('employee_accounts.deleted','0')
            ->get();
            
        $employee_familyinfo = Db::table('employee_familyinfo')
            ->where('employeeid',$teacherid)
            ->where('deleted','0')
            ->get();

        $employee_educationinfo = Db::table('employee_educationinfo')
            ->where('employeeid',$teacherid)
            ->where('deleted','0')
            ->get();

        $employee_experience = Db::table('employee_experience')
            ->where('employeeid',$teacherid)
            ->where('deleted','0')
            ->get();

        $profile->accounts = $employee_accounts;
        $profile->familyinfo = $employee_familyinfo;
        $profile->educationalbackground = $employee_educationinfo;
        $profile->experiences = $employee_experience;
        
        $nationality = Db::table('nationality')
            ->get();
        $civilstatus = Db::table('civilstatus')
            ->get();
        $religions = Db::table('religion')
            ->get();

        $departments = Db::table('hr_school_department')
            ->where('deleted','0')
            ->get();
        $designations = Db::table('usertype')
            ->where('deleted','0')
            ->get();

        return view('hr.employees.info.profile')
            ->with('profileinfo',$profile)
            ->with('nationality',$nationality)
            ->with('civilstatus',$civilstatus)
            ->with('religions',$religions)
            ->with('departments',$departments)
            ->with('designations',$designations);
    }
    public function tabprofileupdatepersonalinfo(Request $request)
    {
        DB::table('teacher')
        ->where('id', $request->get('employeeid'))
        ->update([
            'title'         =>  $request->get('profiletitle'),
            'suffix'        =>  $request->get('profilesuffix'),
            'lastname'      =>  $request->get('profilelname'),
            'firstname'     =>  $request->get('profilefname'),
            'middlename'    =>  $request->get('profilemname')
        ]);

        $checkifexists = DB::table('employee_personalinfo')
            ->where('employeeid',$request->get('employeeid'))
            ->get();
            
        if(count($checkifexists)==0){

            Db::table('employee_personalinfo')
                ->insert([
                    'employeeid'        => $request->get('employeeid'),
                    'dob'               => $request->get('profiledob'),
                    'gender'            => $request->get('profilegender'),
                    'address'           => $request->get('profileaddress'),
                    'primaryaddress'    => $request->get('profileprimaryaddress'),
                    'contactnum'        => $request->get('contactnum'),
                    'email'             => $request->get('profileemail'),
                    'spouseemployment'  => $request->get('profilespouseemployment'),
                    'numberofchildren'  => $request->get('profilenumofchildren'),
                    // 'designationid'     => $request->get('designationid'),
                    'maritalstatusid'   => $request->get('profilecivilstatusid'),
                    'religionid'        => $request->get('profilereligionid'),
                    'nationalityid'     => $request->get('profilenationalityid'),
                    'date_joined'       => $request->get('profiledatehired')
                ]);

            DB::table('teacher')
                ->where('id', $request->get('employeeid'))
                ->update([
                    'datehired'         => $request->get('profiledatehired'),
                    'licno'         => $request->get('profilelicenseno')
                ]);

        }
        else{

            DB::table('employee_personalinfo')
                ->where('employeeid', $request->get('employeeid'))
                ->update([
                    'dob'               =>  $request->get('profiledob'),
                    'gender'            =>  $request->get('profilegender'),
                    'address'           =>  $request->get('profileaddress'),
                    'primaryaddress'    => $request->get('profileprimaryaddress'),
                    'contactnum'        =>  $request->get('contactnum'),
                    'email'             =>  $request->get('profileemail'),
                    'spouseemployment'  =>  $request->get('profilespouseemployment'),
                    'numberofchildren'  =>  $request->get('profilenumofchildren'),
                    // 'designationid'     => $request->get('designationid'),
                    'maritalstatusid'   => $request->get('profilecivilstatusid'),
                    'religionid'        => $request->get('profilereligionid'),
                    'nationalityid'     => $request->get('profilenationalityid'),
                    'date_joined'       => $request->get('profiledatehired')
                ]);

            DB::table('teacher')
                ->where('id', $request->get('employeeid'))
                ->update([
                    'datehired'         => $request->get('profiledatehired'),
                    'licno'         => $request->get('profilelicenseno')
                ]);

        }
        return $request->all();
    }
    public function tabprofileupdateemergencycontact(Request $request)
    {
        $checkifexists = DB::table('employee_personalinfo')
        ->where('employeeid',$request->get('employeeid'))
        ->get();

        if(count($checkifexists)==0){

            Db::table('employee_personalinfo')
                ->insert([
                    'employeeid'            => $request->get('employeeid'),
                    'emercontactname'       => $request->get('emergencyname'),
                    'emercontactrelation'   => $request->get('emergencyrelationship'),
                    'emercontactnum'        => $request->get('emergencycontactnumber')
                ]);

        }else{

            DB::table('employee_personalinfo')
                ->where('employeeid', $request->get('employeeid'))
                ->update([
                    'emercontactname'       =>  $request->get('emergencyname'),
                    'emercontactrelation'   =>  $request->get('emergencyrelationship'),
                    'emercontactnum'        =>  $request->get('emergencycontactnumber')
                ]);

            }

    }
    public function tabprofileupdateaccounts(Request $request)
    {
        
        
            // return $request->all();
            $createdby      = DB::table('teacher')
                            ->where('userid', auth()->user()->id)
                            ->first()
                            ->id;
            if($request->get('oldaccountid') == true){

                
                foreach($request->get('oldaccountid') as $oldaccountkey => $accountid){

                    DB::table('employee_accounts')
                        ->where('id',$accountid)
                        ->update([
                            'accountdescription'    => $request->get('oldaccountdescription')[$oldaccountkey],
                            'accountnum'            => $request->get('oldaccountnumber')[$oldaccountkey]
                        ]);

                }

            }
            
            if($request->get('newdescriptions') == true){

                foreach($request->get('newdescriptions') as $newaccountkey => $description){

                    $checkifexists = DB::table('employee_accounts')
                                    ->where('employeeid',$request->get('employeeid'))
                                    ->where('accountdescription', 'like','%'.$description)
                                    ->get();
    
                    if(count($checkifexists) == 0){
    
                        DB::table('employee_accounts')
                            ->insert([
                                'employeeid'            => $request->get('employeeid'),
                                'accountdescription'    => strtoupper($description),
                                'accountnum'            => $request->get('newaccountnumber')[$newaccountkey],
                                'createdby'             => $createdby,
                                'createddatetime'       => date('Y-m-d H:i:s')
                            ]);
    
                    }
    
                }
                    
            }

            // return back()->with('linkid', $request->get('linkid'));       
        
    }
    public function tabprofiledeleteaccount(Request $request)
    {
        try{
            DB::table('employee_accounts')
            ->where('id',$request->get('accountid'))
            ->update([
                'deleted'   => '1'
            ]);
            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }

    }
    public function tabprofileupdatefamilyinfo(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        // return $request->all();
        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->first();

        $employee_familyinfo = Db::table('employee_familyinfo')
            ->select('id')
            ->where('employeeid',$request->get('employeeid'))
            ->where('deleted','0')
            ->get();
            
        if($request->get('oldid') == true){

            foreach($request->get('oldid') as $key => $value){
                
                Db::table('employee_familyinfo')
                    ->where('employeeid',$request->get('employeeid'))
                    ->where('id',$value)
                    ->where('deleted','0')
                    ->update([
                        'famname'       => $request->get('oldfamilyname')[$key],
                        'famrelation'   => $request->get('oldfamilyrelation')[$key],
                        'dob'           => $request->get('oldfamilydob')[$key],
                        'contactnum'    => $request->get('oldfamilynum')[$key],
                        'updated_by'    => $getMyid->id,
                        'updated_on'    => date('Y-m-d H:i:s')
                    ]);

            }

        }

        $familyarray = array();

        if($request->get('familyname') == true){

            foreach($request->get('familyname') as $key => $value){
                
                array_push($familyarray,(object)array(
                    'familyname'        => $value,
                    'familyrelation'    => $request->get('familyrelation')[$key],
                    'familydob'         => $request->get('familydob')[$key],
                    'familynum'         => $request->get('familynum')[$key]
                ));

            }

            foreach($familyarray as $family){

                $checkifexists = Db::table('employee_familyinfo')
                    ->where('employeeid',$request->get('employeeid'))
                    ->where('famname','like','%'.$family->familyname)
                    ->where('deleted','0')
                    ->get();

                if(count($checkifexists)==0){

                    Db::table('employee_familyinfo')
                        ->insert([
                            'employeeid'    => $request->get('employeeid'),
                            'famname'       => $family->familyname,
                            'famrelation'   => $family->familyrelation,
                            'dob'           => $family->familydob,
                            'contactnum'    => $family->familynum,
                            'updated_by'    => $getMyid->id,
                            'updated_on'    => date('Y-m-d H:i:s')
                        ]);

                }else{

                    Db::table('employee_familyinfo')
                        ->where('employeeid',$request->get('employeeid'))
                        ->where('deleted','0')
                        ->update([
                            'famname'       => $family->familyname,
                            'famrelation'   => $family->familyrelation,
                            'dob'           => $family->familydob,
                            'contactnum'    => $family->familynum,
                            'updated_by'    => $getMyid->id,
                            'updated_on'    => date('Y-m-d H:i:s')
                        ]);

                }

            }

        }

    }
    public function tabprofiledeletefamilyinfo(Request $request)
    {
        // return $request->all();
        $getMyid = DB::table('teacher')
        ->select('id')
        ->where('userid', auth()->user()->id)
        ->first();
        Db::table('employee_familyinfo')
            ->where('employeeid',$request->get('employeeid'))
            ->where('id',$request->get('familymemberid'))
            ->where('deleted','0')
            ->update([
                'deleted' => '1',
                'updated_by' => $getMyid->id,
                'updated_on' => date('Y-m-d H:i:s')
            ]);
    }
    public function tabprofileaddeducationinfo(Request $request)
    {
        $employeeid = $request->get('employeeid');
        $sy         = $request->get('sy');
        $university = $request->get('university');
        $address    = $request->get('address');
        $course     = $request->get('course');
        $major      = $request->get('major');
        $awards     = $request->get('awards');

        date_default_timezone_set('Asia/Manila');
        $getMyid = DB::table('teacher')
        ->select('id')
        ->where('userid', auth()->user()->id)
        ->first();
        
        DB::table('employee_educationinfo')
            ->insert([
                'employeeid'        => $employeeid,
                'schoolyear'        => $sy,
                'schoolname'        => $university,
                'schooladdress'     => $address,
                'coursetaken'       => $course,
                'major'             => $major,
                'awards'            => $awards,
                // 'completiondate'    => $request->get('datecompleted')[$key],
                'createdby'         => $getMyid->id,
                'createddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function tabprofileupdateeducationinfo(Request $request)
    {
        $id = $request->get('id');
        $sy         = $request->get('sy');
        $university = $request->get('schoolname');
        $address    = $request->get('schooladdress');
        $course     = $request->get('course');
        $major      = $request->get('major');
        $awards     = $request->get('awards');

        date_default_timezone_set('Asia/Manila');
        $getMyid = DB::table('teacher')
        ->select('id')
        ->where('userid', auth()->user()->id)
        ->first();
        
        DB::table('employee_educationinfo')
            ->where('id', $id)
            ->update([
                'schoolyear'        => $sy,
                'schoolname'        => $university,
                'schooladdress'     => $address,
                'coursetaken'       => $course,
                'major'             => $major,
                'awards'            => $awards,
                // 'completiondate'    => $request->get('datecompleted')[$key],
                'updatedby'         => $getMyid->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function tabprofiledeleteeducationinfo(Request $request)
    {
        $id = $request->get('id');

        date_default_timezone_set('Asia/Manila');
        $getMyid = DB::table('teacher')
        ->select('id')
        ->where('userid', auth()->user()->id)
        ->first();
        
        DB::table('employee_educationinfo')
            ->where('id', $id)
            ->update([
                'deleted'           => 1,
                'deletedby'         => $getMyid->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function tabprofileaddworexperience(Request $request)
    {
        
        $employeeid     = $request->get('employeeid');
        $companyname    = $request->get('companyname');
        $location       = $request->get('location');
        $jobposition    = $request->get('jobposition');
        $periodfrom     = $request->get('periodfrom');
        $periodto       = $request->get('periodto');

        date_default_timezone_set('Asia/Manila');
        $getMyid = DB::table('teacher')
        ->select('id')
        ->where('userid', auth()->user()->id)
        ->first();
        
        DB::table('employee_experience')
        ->insert([
            'employeeid'        => $employeeid,
            'companyname'       => $companyname,
            'companyaddress'    => $location,
            'position'          => $jobposition,
            'periodfrom'        => $periodfrom,
            'periodto'          => $periodto,
            'createdby'         => $getMyid->id,
            'createddatetime'   => date('Y-m-d H:i:s')
        ]);
    }
    public function tabprofileupdateworkexperience(Request $request)
    {
        $id             = $request->get('id');
        $companyname    = $request->get('companyname');
        $location       = $request->get('location');
        $jobposition    = $request->get('jobposition');
        $periodfrom     = $request->get('periodfrom');
        $periodto       = $request->get('periodto');

        date_default_timezone_set('Asia/Manila');
        $getMyid = DB::table('teacher')
        ->select('id')
        ->where('userid', auth()->user()->id)
        ->first();
        
        DB::table('employee_experience')
            ->where('id', $id)
            ->update([
                'companyname'        => $companyname,
                'companyaddress'        => $location,
                'position'     => $jobposition,
                'periodfrom'       => $periodfrom,
                'periodto'             => $periodto,
                'updatedby'         => $getMyid->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);

    }
    public function tabprofiledeleteworkexperience(Request $request)
    {
        $id = $request->get('id');

        date_default_timezone_set('Asia/Manila');
        $getMyid = DB::table('teacher')
        ->select('id')
        ->where('userid', auth()->user()->id)
        ->first();
        
        DB::table('employee_experience')
            ->where('id', $id)
            ->update([
                'deleted'           => 1,
                'deletedby'         => $getMyid->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    }

    public static function v2_loadpersonalinfo(Request $request){
        $empid = $request->get('empid');

        $teacherinfo = DB::table('teacher')
            ->where('id', $empid)
            ->where('deleted', 0)
            ->get();

        $personalinfo = DB::table('employee_personalinfo')
            ->where('deleted', 0)
            ->where('employeeid', $empid)
            ->get();

     
            return [
                'teacherinfo' => $teacherinfo,
                'personalinfo' => $personalinfo
            ];
    }

    public static function v2_loadsalaryinfo(Request $request){
        $empid = $request->get('empid');

        $timeschedule = Db::table('employee_customtimesched')
        ->where('employeeid',$empid)
        ->where('deleted','0')
        ->first();

        $personalinfo = DB::table('employee_personalinfo')
            ->where('deleted', 0)
            ->first();
        
        $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
            ->where('employeeid', $empid)
            ->where('deleted','0')
            ->first();
        
        return response()->json([
            $basicsalaryinfo,
            $timeschedule,
            $personalinfo
        ]);
    }


    // Gian Additional
    
    public static function select_designations(Request $request){
        $designations = DB::table('usertype')
            ->select('utype as text', 'id')
            ->where('deleted','0')
            ->get();

        return $designations;
    }


    public static function select_departments(Request $request){
        $departments = DB::table('hr_departments')
            ->select('department as text', 'id')
            ->where('deleted','0')
            ->get();

        return $departments;
    }


    public static function v2_storepersonalinfo(Request $request){
        $empid = $request->get('empid');
        $profiletitle = $request->get('profiletitle');
        $profilefname = $request->get('profilefname');
        $profilemname = $request->get('profilemname');
        $profilelname = $request->get('profilelname');
        $profilesuffix = $request->get('profilesuffix');
        $profilegender = $request->get('profilegender');
        $profiledob = $request->get('profiledob');
        
        $profilecivilstatusid = $request->get('profilecivilstatusid');
        $profilereligionid = $request->get('profilereligionid');
        $profilenationalityid = $request->get('profilenationalityid');
        $profilenumofchildren = $request->get('profilenumofchildren');
        $profilespouseemployment = $request->get('profilespouseemployment');
        $profileaddress = $request->get('profileaddress');
        $profileprimaryaddress = $request->get('profileprimaryaddress');
        $profileemail = $request->get('profileemail');
        $contactnum = $request->get('contactnum');
        $profilelicenseno = $request->get('profilelicenseno');
        $profiledatehired = $request->get('profiledatehired');
        $departmentid = $request->get('departmentid');
        $designationid = $request->get('designationid');
        
        // if($profiledob == null){

        //     $profiledob = "";

        // }else{

        //     $profiledob = date('F d, Y', strtotime($profiledob));
        // }

        if($profiledatehired == null){

            $profiledatehired = "";

        }else{

            $profiledatehired = date('Y-m-d', strtotime($profiledatehired));

        }

        $ifexistpersonalinfo = DB::table('employee_personalinfo')
            ->where('deleted', 0)
            ->where('employeeid', $empid)
            ->count();

        if ($ifexistpersonalinfo) {
            $updatenames = DB::table('teacher')
                ->where('id', $empid)
                ->update([
                    'title' => $profiletitle,
                    'firstname' => $profilefname,
                    'middlename' => $profilemname,
                    'lastname' => $profilelname,
                    'suffix' => $profilesuffix,
                    'licno' => $profilelicenseno,
                    'datehired' => $profiledatehired,
                    'usertypeid' => $designationid
                ]);

            $data = DB::table('employee_personalinfo')
            ->where('employeeid', $empid)
            ->update([
                'gender' => $profilegender,
                'dob' =>  $profiledob,
                'religionid' => $profilereligionid,
                'nationalityid' => $profilenationalityid,
                'maritalstatusid' =>  $profilecivilstatusid,
                'maritalstatusid' =>  $profilenumofchildren,
                'spouseemployment' =>  $profilespouseemployment,
                'numberofchildren' =>  $profilenumofchildren,
                'address' =>  $profileaddress,
                'primaryaddress' =>  $profileprimaryaddress,
                'email' =>  $profileemail,
                'contactnum' => $contactnum,
                'date_joined' => $profiledatehired,
                'departmentid' => $departmentid
            ]);
            return array((object)[
                'status'=>1,
                'message'=>'Updated Successfully!',
            ]);
        } else {

            $updatenames = DB::table('teacher')
                ->where('id', $empid)
                ->update([
                    'title' => $profiletitle,
                    'firstname' => $profilefname,
                    'middlename' => $profilemname,
                    'lastname' => $profilelname,
                    'suffix' => $profilesuffix,
                    'licno' => $profilelicenseno,
                    'datehired' => $profiledatehired,
                    'usertypeid' => $designationid
                ]);

            $data = DB::table('employee_personalinfo')
            ->insert([
                'employeeid' => $empid,
                'gender' => $profilegender,
                'dob' =>  $profiledob,
                'religionid' => $profilereligionid,
                'nationalityid' => $profilenationalityid,
                'maritalstatusid' =>  $profilecivilstatusid,
                'maritalstatusid' =>  $profilenumofchildren,
                'spouseemployment' =>  $profilespouseemployment,
                'numberofchildren' =>  $profilenumofchildren,
                'address' =>  $profileaddress,
                'primaryaddress' =>  $profileprimaryaddress,
                'email' =>  $profileemail,
                'contactnum' => $contactnum,
                'date_joined' => $profiledatehired,
                'departmentid' => $departmentid
            ]);

            return array((object)[
                'status'=>1,
                'message'=>'Updated Successfully!',
            ]);
        }

        
    }

    public static function v2_storesalaryinfo(Request $request){

        // return 'msaysa';
        date_default_timezone_set('Asia/Manila');
        $days = $request->get('days');
        $attendance_switch = $request->get('attendance_switch');
        $empid = $request->get('empid');
        $salarytype = $request->get('salarytype');
        $amountperday = $request->get('amountperday');
        $hoursperday = $request->get('hoursperday');
        $paymenttype = $request->get('paymenttype');
        $workshift = $request->get('workshift');
        $shiftid = $request->get('shiftid');

        $mon = 0;
        $tue = 0;
        $wed = 0;
        $thu = 0;
        $fri = 0;
        $sat = 0;
        $sun = 0;

        if ($attendance_switch == 1) {
            foreach ($days as $dayNumber) {
                switch ($dayNumber) {
                    case "1":
                        $mon = 1;
                        break;
                    case "2":
                        $tue = 1;
                        break;
                    case "3":
                        $wed = 1;
                        break;
                    case "4":
                        $thu = 1;
                        break;
                    case "5":
                        $fri = 1;
                        break;
                    case "6":
                        $sat = 1;
                        break;
                    case "7":
                        $sun = 1;
                        break;
                    // Add cases for other days if needed
                }
            }
        } else {
            $days = [];
        }

        

        // return array($mon, $tue, $wed, $thu,  $fri, $sat, $sun);
        



        $explodeamin = explode(':', $request->get('am_in'));
                
        if($explodeamin[0] == '00')
        {
            $amin = null;
        }else{
            $amin = $request->get('am_in');
        }

        $explodeamout = explode(':', $request->get('am_out'));
        if($explodeamout[0] == '00')
        {
            $amout = null;
        }else{
            $amout = $request->get('am_out');
        }

        $explodepmin = explode(':', $request->get('pm_in'));
        if($explodepmin[0] == '00')
        {
            $pmin = null;
        }else{
            $pmin = $request->get('pm_in');
        }

        $explodepmout = explode(':', $request->get('pm_out'));
        if($explodepmout[0] == '00')
        {
            $pmout = null;
        }else{
            $pmout = $request->get('pm_out');
        }

        
        if ($attendance_switch == 1) {
            $data = DB::table('employee_basicsalaryinfo')
            ->insert([
                'shiftid'        => $shiftid,
                'employeeid'        => $empid,
                'salarybasistype'   => $salarytype,
                'amount'            => $amountperday,
                'hoursperday'       => $hoursperday,
                'attendancebased'   => $attendance_switch,
                'mondays'   => $mon,
                'tuesdays'   => $tue,
                'wednesdays'   => $wed,
                'thursdays'   => $thu,
                'fridays'   => $fri,
                'saturdays'   => $sat,
                'sundays'   => $sun,
                // 'rateperhour'       => $rateperhour,
                'paymenttype'       => $paymenttype,
                'createdby'         => auth()->user()->id,
                'createddatetime'   => date('Y-m-d H:i:s')
            ]);

           
        
            $getMyid = DB::table('teacher')
                ->select('id')
                ->where('userid', auth()->user()->id)
                ->first();
                
            
            $checkifexists = Db::table('employee_customtimesched')
                ->where('employeeid',$request->get('employeeid'))
                ->where('deleted','0')
                ->get();

            if(count($checkifexists) == 0){

                    DB::table('employee_customtimesched')
                        ->insert([
                            'shiftid'    => $shiftid,
                            'employeeid'    => $empid,
                            'amin'          => $request->get('am_in'),
                            'amout'        => $request->get('am_out'),
                            'pmin'          => $request->get('pm_in'),
                            'pmout'         => $request->get('pm_out'),
                            'createdby'     => $getMyid->id,
                            'createdon'     =>  date('Y-m-d H:i:s')
                        ]);

            }else{
                
                

                DB::table('employee_customtimesched')
                    ->where('employeeid', $request->get('employeeid'))
                    ->update([
                        'shiftid'    => $shiftid,
                        'amin'              => $amin,
                        'amout'             => $amout,
                        'pmin'              => date('H:i:s',strtotime($pmin.' PM')),
                        'pmout'             => date('H:i:s',strtotime($pmout.' PM'))
                        // 'updatedby'         => auth()->user()->id,
                        // 'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);

            }

            return array((object)[
                'status'=>1,
                'message'=>'Created Successfully!',
            ]);
        } else {
            $data = DB::table('employee_basicsalaryinfo')
            ->insert([
                'employeeid'        => $empid,
                'salarybasistype'   => $salarytype,
                'amount'            => $amountperday,
                'hoursperday'       => $hoursperday,
                'attendancebased'   => $attendance_switch,
                'mondays'   => $mon,
                'tuesdays'   => $tue,
                'wednesdays'   => $wed,
                'thursdays'   => $thu,
                'fridays'   => $fri,
                'saturdays'   => $sat,
                'sundays'   => $sun,
                // 'rateperhour'       => $rateperhour,
                'paymenttype'       => $paymenttype,
                'shiftid'       => $workshift,
                'createdby'         => auth()->user()->id,
                'createddatetime'   => date('Y-m-d H:i:s')
            ]);


            return array((object)[
                'status'=>1,
                'message'=>'Created Successfully!',
            ]);
        }
       
            
    }

    public static function v2_updatesalaryinfo(Request $request){
        
        $days = $request->get('days');
        $attendance_switch = $request->get('attendance_switch');
        $empid = $request->get('empid');
        $salarytype = $request->get('salarytype');
        $amountperday = $request->get('amountperday');
        $hoursperday = $request->get('hoursperday');
        $paymenttype = $request->get('paymenttype');
        $workshift = $request->get('workshift');
        $shiftid = $request->get('shiftid');

        $mon = 0;
        $tue = 0;
        $wed = 0;
        $thu = 0;
        $fri = 0;
        $sat = 0;
        $sun = 0;
        
        if ($attendance_switch == 1) {
            foreach ($days as $dayNumber) {
                switch ($dayNumber) {
                    case "1":
                        $mon = 1;
                        break;
                    case "2":
                        $tue = 1;
                        break;
                    case "3":
                        $wed = 1;
                        break;
                    case "4":
                        $thu = 1;
                        break;
                    case "5":
                        $fri = 1;
                        break;
                    case "6":
                        $sat = 1;
                        break;
                    case "7":
                        $sun = 1;
                        break;
                    // Add cases for other days if needed
                }
            }
        } else {
            $days = [];
        }
        
        

        $explodeamin = explode(':', $request->get('am_in'));
                
        if($explodeamin[0] == '00')
        {
            $amin = null;
        }else{
            $amin = $request->get('am_in');
        }

        $explodeamout = explode(':', $request->get('am_out'));
        if($explodeamout[0] == '00')
        {
            $amout = null;
        }else{
            $amout = $request->get('am_out');
        }

        $explodepmin = explode(':', $request->get('pm_in'));
        if($explodepmin[0] == '00')
        {
            $pmin = null;
        }else{
            $pmin = $request->get('pm_in');
        }

        $explodepmout = explode(':', $request->get('pm_out'));
        if($explodepmout[0] == '00')
        {
            $pmout = null;
        }else{
            $pmout = $request->get('pm_out');
        }

        $data = DB::table('employee_basicsalaryinfo')
            ->where('employeeid', $empid)
            ->update([
                'salarybasistype'   => $salarytype,
                'amount'            => $amountperday,
                'hoursperday'       => $hoursperday,
                'mondays'   => $mon,
                'tuesdays'   => $tue,
                'wednesdays'   => $wed,
                'thursdays'   => $thu,
                'fridays'   => $fri,
                'saturdays'   => $sat,
                'sundays'   => $sun,
                'attendancebased'   => $attendance_switch,
                'shiftid'       => $shiftid,
                'updatedby'         => auth()->user()->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);

        DB::table('employee_customtimesched')
                ->where('employeeid', $empid)
                ->update([
                    'shiftid'    => $shiftid,
                    'amin'              => $amin,
                    'amout'             => $amout,
                    'pmin'              => date('H:i:s',strtotime($pmin.' PM')),
                    'pmout'             => date('H:i:s',strtotime($pmout.' PM')),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

        return array((object)[
            'status'=>1,
            'message'=>'Updated Successfully!',
        ]);
            
    }
	
	public static function v2_updateflexitime(Request $request){
        $empid = $request->get('empid');
        $flexistatus = $request->get('flexitime');

        $data = DB::table('employee_basicsalaryinfo')
            ->where('employeeid', $empid)
            ->update([
                'flexitime'       => $flexistatus,
                'updatedby'         => auth()->user()->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);
            
        return array((object)[
            'status'=>1,
            'message'=>'Flexi Time Updated Successfully!',
        ]);
    }


    // load standard deduction
    public static function v2_loadstandarddeduction(Request $request){

        $data = DB::table('deduction_standard')
            ->where('deleted', 0)
            ->get();

        return $data;
    }

    // load shifting
    public static function v2_loadshifts(Request $request){
        $data = DB::table('employee_shift')
        ->select('id', 'description as text', 'first_in', 'first_out', 'second_in', 'second_out')
        ->where('deleted', 0)
        ->get();

        return $data;
    }

    // load Employee shifting
    public static function v2_loademployeeshifts(Request $request){
        $empid = $request->get('empid');

        $data = DB::table('employee_customtimesched')
            ->where('employeeid', $empid)
            ->where('deleted', 0)
            ->first();

        return collect($data);
    }
    
    // Add Standard Deduction
    public static function v2_addstandarddeduction(Request $request){
        $particular = $request->get('particular');
        $amount = $request->get('amount');

        $ifexist = DB::table('deduction_standard')
            ->where('description', $particular)
            ->count();
        
        if ($ifexist) {
            return array((object)[
                'status'=>0,
                'message'=>'Already Exist!',
            ]);
        } else {
            $data = DB::table('deduction_standard')
                ->insert([
                    'description' => $particular,
                    'amount' => $amount,
                    'createdby' => auth()->user()->id,
                    'createddatetime' =>  date('Y-m-d H:i:s'),
                ]);

            return array((object)[
                'status'=>1,
                'message'=>'Added Successfully!',
            ]);
        }
        
    }

    public static function v2_getstandarddeductionamountdefault(Request $request){
        $salaryamount = $request->get('salaryamount');
        $empid = $request->get('empid');
        $standeductionid = $request->get('standeductionid');

        // return 'masaya';
        $defaultamount =\App\Http\Controllers\HRControllers\HRDeductionSetupController::getbracket_standard($empid, $salaryamount, $standeductionid,'amount');

        return $defaultamount;
    }
    
    public function v2_addemployeestandarddeduction(Request $request){
        $standeductionid = $request->get('standeductionid');
        $empid = $request->get('empid');
        $standarddeduction_amount = $request->get('standarddeduction_amount'); 
        $sc_me = $request->get('sc_me'); 
        // return $request->all();
        $data = DB::table('employee_deductionstandard')
            ->insert([
                'employeeid' => $empid,
                'deduction_typeid' => $standeductionid,
                'eesamount' => $standarddeduction_amount,
                'createdby' => auth()->user()->id,
                'status' => 1,
                'createddatetime' => date('Y-m-d H:i:s'),
                'sc_me' =>  $sc_me
            ]);

        return array((object)[
            'status'=>1,
            'message'=>'Added Successfully!',
        ]);
    }
    
    public function v2_deleteemployeestandarddeduction(Request $request){
        $standeductionid = $request->get('standeductionid');
        $empid = $request->get('empid');

        $data = DB::table('employee_deductionstandard')
            ->where('employeeid', $empid)
            ->where('deleted', 0)
            ->where('id', $standeductionid)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => date('Y-m-d H:i:s')
            ]);

        return array((object)[
            'status'=>1,
            'message'=>'Deleted Successfully!',
        ]);
    }


    public function load_employeestandarddeduction(Request $request){
        $empid = $request->get('empid');
        // $defaultamount =\App\Http\Controllers\HRControllers\HRDeductionSetupController::getbracket_standard($empid, $salaryamount, $standeductionid,'amount');
        $data = DB::table('employee_deductionstandard')
            ->where('employeeid', $empid)
            ->where('deleted', 0)
            ->get();

        return $data;
    }

    public function v2_loadotherdeduction(Request $request){
        $empid = $request->get('empid');

        $data = DB::table('deduction_others')
            ->where('deleted', 0)
            ->get();

        return $data;
    }
    
    public function v2_addotherdeduction(Request $request){
        $empid = $request->get('empid');
        $od_particular = $request->get('od_particular');
        $mod_odamount = $request->get('mod_odamount');
        $mod_odineterst = $request->get('mod_odineterst');
        $mod_odterms = $request->get('mod_odterms');
        $mod_oddatefrom = $request->get('mod_oddatefrom');
        $mod_oddateto = $request->get('mod_oddateto');
        $mod_odamountdeduct = $request->get('mod_odamountdeduct');
        $mod_period = $request->get('mod_period');
        

        $ifexist = DB::table('deduction_others')
            ->where('deleted', 0)
            ->where('description', $od_particular)
            ->count();
        
        if ($ifexist) {
            return array((object)[
                'status'=>0,
                'message'=>'Already Exist!',
            ]);
        } else {
            $data = DB::table('deduction_others')
            ->insert([
                'description' => $od_particular,
                'interest_rate' => $mod_odineterst,
                'amount' => $mod_odamount,
                'startdate' => $mod_oddatefrom,
                'enddate' => $mod_oddateto,
                'terms' => $mod_odterms,
                'periodid' => $mod_period,
                'amounttobededuct' => $mod_odamountdeduct,
                'createdby' => auth()->user()->id,
                'createddatetime' => date('Y-m-d H:i:s')
            ]);

            return array((object)[
                'status'=>1,
                'message'=>'Added Successfully!',
            ]);
        }
       
    }

    public function v2_editotherdeduction(Request $request){
        $otherdeduction_id = $request->get('odid');

        $data = DB::table('deduction_others')
            ->where('id', $otherdeduction_id)
            ->where('deleted', 0)
            ->get();
        
        return $data;
    }

    public function v2_updateotherdeduction(Request $request){
        $otherdeduction_id = $request->get('otherdeduction_id');
        $otherdeduction_desc = $request->get('otherdeduction_desc');

        $data = DB::table('deduction_others')
            ->where('id', $otherdeduction_id)
            ->where('deleted', 0)
            ->update([
                'description' => $otherdeduction_desc,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => date('Y-m-d H:i:s')
            ]);
        
        $data = DB::table('employee_deductionother')
            ->where('deductionotherid', $otherdeduction_id)
            ->where('deleted', 0)
            ->update([
                'description' => $otherdeduction_desc
            ]);

        return array((object)[
            'status'=>1,
            'message'=>'Description Updated Successfully!',
        ]);
        
    }

    
    

    // load all interest
    public function v2_loadinterest(Request $request){
        $data = DB::table('payroll_interest')
            ->select('description as text', 'id', 'interestrate')
            ->where('deleted', 0)
            ->first();

        return collect($data);
    }
    //view employee other deduction ledger
    
    public function v2_viewemployeeotherdeduction(Request $request){
        $otherdeduction_id = $request->get('otherdeduction_id');
        $employee_id = $request->get('empid');

        // return $request->all();
        $empotherdeduction = DB::table('employee_deductionother')
            ->where('deductionotherid', $otherdeduction_id)
            ->where('employeeid',  $employee_id)
            ->where('deleted', 0)
            ->first();

        // return collect($empotherdeduction);
        $payrollv2history = DB::table('hr_payrollv2history')
            ->leftJoin('hr_payrollv2', 'hr_payrollv2history.payrollid', '=', 'hr_payrollv2.id')
            ->where('hr_payrollv2history.released', 1)
            ->where('hr_payrollv2history.employeeid', $employee_id)
            ->get();

        $payrolldetails = DB::table('hr_payrollv2historydetail')
            ->where('particulartype', 2)
            ->where('employeeid',  $employee_id)
            ->where('deductionid', $otherdeduction_id)
            ->get();

        $totalotherdeductionpaid = 0;

       
        foreach ($payrolldetails as $payrolldetail) {
            $payrollv2history = DB::table('hr_payrollv2history')
                ->leftJoin('hr_payrollv2', 'hr_payrollv2history.payrollid', '=', 'hr_payrollv2.id')
                ->where('hr_payrollv2history.released', 1)
                ->where('hr_payrollv2history.payrollid', $payrolldetail->payrollid)
                ->get();
            // $totalotherdeductionpaid += $payrolldetail->amountpaid;
            // $payrolldetail->payrolldate = Carbon::parse($payrollv2history[0]->datefrom)->format('M j, Y').' - '.Carbon::parse($payrollv2history[0]->dateto)->format('M j, Y');

            if ($payrollv2history->isNotEmpty()) {
                $totalotherdeductionpaid += $payrolldetail->amountpaid;
                $payrolldetail->payrolldate = Carbon::parse($payrollv2history[0]->datefrom)->format('M j, Y').' - '.Carbon::parse($payrollv2history[0]->dateto)->format('M j, Y');
            } else {
                // Handle the case when $payrollv2history is empty
                // For example, set a default value for $payrolldetail->payrolldate
                $totalotherdeductionpaid = 0;
            }
            
        }

        if (count($payrollv2history) < 1) {
            $totalotherdeductionpaid = 0;
        }
        // return collect( $empotherdeduction);
        $interestrate = $empotherdeduction->od_interest / 100;

        if ($empotherdeduction->term == 0 || $empotherdeduction->term == '') {
            $interestamount = $empotherdeduction->fullamount * ( $interestrate /  1);
        } else {
            $interestamount = $empotherdeduction->fullamount * ( $interestrate);
        }
        
        $interestamount = $interestamount *  $empotherdeduction->term;
       
        
        $fullamount = $empotherdeduction->fullamount;
        $totalamount = $fullamount + $interestamount;
        

        

        // return $payrolldetails;
        

        $balance = $totalamount - $totalotherdeductionpaid;
        
        // Collect all the variables into an array
        $data = [
            'interestrate' => $interestrate,
            'interestamount' => $interestamount,
            'totalotherdeductionpaid' => $totalotherdeductionpaid,
            'totalamount' => $totalamount,
            'fullamount' => $fullamount,
            'payrolldetails' => $payrolldetails,
            'payrollv2history' => $payrollv2history,
            'balance' => $balance,
            'empotherdeduction' => $empotherdeduction
        ];

        
        // Return the array as a JSON response
        return response()->json($data);
    }

    // save employee other deduction
    public function v2_saveemployeeotherdeduction(Request $request){
        $action = $request->get('action');
        $od_interest = $request->get('od_interest');
        $od_loanamount = $request->get('od_loanamount');
        $od_datefrom = $request->get('od_datefrom');
        $od_dateto = $request->get('od_dateto');
        $od_terms = $request->get('od_terms');
        $od_periodid = $request->get('od_periodid');
        $amounttobededuct = $request->get('amounttobededuct'); 
        $od = $request->get('od'); 
        $employeeid = $request->get('empid'); 
        $od_particular = $request->get('od_particular'); 


        $ifexist = DB::table('employee_deductionother')
            ->where('deductionotherid', $od)
            ->where('employeeid', $employeeid)
            ->where('deleted', 0)
            ->count();
        
        if ($action == 'update') {
            $data = DB::table('employee_deductionother')
                ->where('id', $od)
                ->update([
                    'od_interest' => $od_interest,
                    'fullamount' => $od_loanamount,
                    'term' => $od_terms,
                    'periodid' => $od_periodid,
                    'startdate' => $od_datefrom,
                    'dateissued' => $od_datefrom,
                    'enddate' => $od_dateto,
                    'amounttobededuct' => $amounttobededuct,
                    'amount' => $amounttobededuct,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);
                
            return array((object)[
                'status'=>1,
                'message'=>'Updated Successfully!',
            ]);
        } else {
            if ($ifexist) {
                return array((object)[
                    'status'=>0,
                    'message'=>'Already Exist!',
                ]);
            } else {
                $data = DB::table('employee_deductionother')
                    ->insert([
                        'od_interest' => $od_interest,
                        'fullamount' => $od_loanamount,
                        'description' => $od_particular,
                        'term' => $od_terms,
                        'periodid' => $od_periodid,
                        'startdate' => $od_datefrom,
                        'dateissued' => $od_datefrom,
                        'enddate' => $od_dateto,
                        'amounttobededuct' => $amounttobededuct,
                        'amount' => $amounttobededuct,
                        'deductionotherid' => $od,
                        'employeeid' => $employeeid,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);
    
                return array((object)[
                    'status'=>1,
                    'message'=>'Added Successfully!',
                ]);
            }
        }

        

    }

    // load employee other deduction
    public function v2_loademployeeotherdeduction(Request $request){
        $employeeid = $request->get('empid'); 

        // return $request->all();
        $empotherdeductions = DB::table('employee_deductionother')
            ->where('deleted', 0)
            ->where('employeeid', $employeeid)
            ->get();

        // return collect($empotherdeduction);
        $payrollv2history = DB::table('hr_payrollv2history')
            ->leftJoin('hr_payrollv2', 'hr_payrollv2history.payrollid', '=', 'hr_payrollv2.id')
            ->where('hr_payrollv2history.released', 1)
            ->where('hr_payrollv2history.employeeid', $employeeid)
            ->get();

        $payrolldetails = DB::table('hr_payrollv2historydetail')
            ->where('particulartype', 2)
            ->where('employeeid',  $employeeid)
            ->get();

            
        // return $payrolldetails;

        $released = 0;
        $interestamount = 0;

        foreach ($empotherdeductions as $empotherdeduction) {
            
            $interestamount = $empotherdeduction->od_interest / 100;
            $interestamount = $interestamount * $empotherdeduction->fullamount;
            $empotherdeduction->interestamount = $interestamount;
            $empotherdeduction->nabayad = 0;
            $empotherdeduction->fullamountwithinterest = $interestamount + $empotherdeduction->fullamount;

            if (count($payrollv2history) > 0) {
                foreach ($payrollv2history as $payrollv2his) {
                    foreach ($payrolldetails as $payrolldetail) {
                        $payrollDetailMatch = DB::table('hr_payrollv2historydetail')
                            ->where('particulartype', 2)
                            ->where('deductionid', $empotherdeduction->deductionotherid)
                            ->where('employeeid', $employeeid)
                            ->where('payrollid', $payrollv2his->payrollid)
                            ->first(); // Use first() to get a single result or null
                        
                        if ($payrollDetailMatch) {
                            $empotherdeduction->released = 1;
                            $empotherdeduction->nabayad += $payrollDetailMatch->amountpaid;
                            break; // Break the inner loop if a match is found
                        } else {
                            $empotherdeduction->released = 0;
                        }
                    }
                }
            } else {
                $empotherdeduction->released = 0;
            }
        }

        
        if (count($empotherdeductions) > 0) {
            foreach ($empotherdeductions as $key => $value) {
                $value->startdate = date('Y-m-d', strtotime($value->startdate));
            }
        }
        
        return $empotherdeductions;

    }

    // delete employee other deduction
    public function v2_deleteemployeeotherdeduction(Request $request){
        $employeeid = $request->get('empid'); 
        $employeeootherdeductionid = $request->get('em_odid'); 
        
        $data = DB::table('employee_deductionother')
            ->where('deleted', 0)
            ->where('id', $employeeootherdeductionid)
            ->where('employeeid', $employeeid)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => date('Y-m-d H:i:s')
            ]);

        return array((object)[
            'status'=>1,
            'message'=>'Deleted Successfully!',
        ]);
    }

    // lock employee other deduction
    public function v2_lockemployeeotherdeduction(Request $request){
        $employeeid = $request->get('empid'); 
        $employeeootherdeductionid = $request->get('em_odid'); 
        $lock = $request->get('lock');

        if ($lock == 1) {

            $data = DB::table('employee_deductionother')
            ->where('deleted', 0)
            ->where('id', $employeeootherdeductionid)
            ->where('employeeid', $employeeid)
            ->update([
                'lock' => 0,
                'lockby' => auth()->user()->id,
                'lockunlockdatetime' => date('Y-m-d H:i:s')
            ]);

            return array((object)[
                'status'=>1,
                'message'=>'Unlock Successfully!',
            ]);

        } else {

            $data = DB::table('employee_deductionother')
            ->where('deleted', 0)
            ->where('id', $employeeootherdeductionid)
            ->where('employeeid', $employeeid)
            ->update([
                'lock' => 1,
                'lockby' => auth()->user()->id,
                'lockunlockdatetime' => date('Y-m-d H:i:s')
            ]);

            return array((object)[
                'status'=>1,
                'message'=>'Lock Successfully!',
            ]);

        }

        
    }

    // getperemployeelist

    public function getperemployeelist(Request $request){
        
        $teachers = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.lastname',
                'teacher.middlename',
                'teacher.firstname',
                'teacher.suffix',
                'teacher.title',
                'teacher.licno',
                'teacher.tid',
                'teacher.deleted',
                'teacher.isactive',
                'teacher.picurl',
                'teacher.rfid',
                DB::raw('CASE WHEN employee_basicsalaryinfo.employeeid IS NOT NULL THEN 1 ELSE 0 END AS withbasicsalaryinfo')
            )
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->where('teacher.deleted', 0)
            ->get();
        
        return $teachers;
    }

    // getalldepartments
    public function getalldepartments(Request $request){
        $otherdeductionid = $request->get('otherdeductionid');

        // $departments = DB::table('hr_departments')
        //     ->select(
        //         'hr_departments.id',
        //         'hr_departments.department',
        //         'department_setups.id as depsetupid',
        //         'department_setups.setuptype',
        //         'department_setups.setup',
        //         'department_setups.deptid',
        //         'department_setups.setupid',
        //         DB::raw('CASE WHEN department_setups.deptid IS NOT NULL THEN 1 ELSE 0 END AS withsetupperdepartment')
        //     )
        //     ->leftJoin('department_setups', 'hr_departments.id', '=', 'department_setups.deptid')
        //     ->where('hr_departments.deleted','0')
        //     ->get();
        $departmentadded = DB::table('department_setups')
            ->where('deleted', 0)
            ->where('setupid', $otherdeductionid)
            ->get();
            
        
        $departments = DB::table('hr_departments')
            ->where('deleted','0')
            ->get();

        $matchingDepIds = $departmentadded->pluck('deptid')->toArray();
        
        // return  $matchingDepIds;
        foreach ($departments as $department) {
            // Check if the 'deptid' in $department matches any value in $matchingDeptIds
            if (in_array($department->id, $matchingDepIds)) {
                // Add an indicator to show that this department has a match with $departmentadded
                $department->has_match = true;
            } else {
                // Add an indicator with a value of false for non-matching departments
                $department->has_match = false;
            }
        }
        return $departments;
    }

    // getallemployeeotherdeduction
    public function getallemployeeotherdeduction(Request $request){

        $allemployeeotherdeduction = DB::table('employee_deductionother')
            ->where('deleted','0')
            ->get();
        
        return $allemployeeotherdeduction;
    }

    // public function savealldepartments(Request $request){
    //     $departments = json_decode($request->get('setups'));

    //     // return $departments;
    //     if (count($departments) > 0) {

    //         foreach ($departments as $department) {
    //             $checkifexists = DB::table('department_setups')
    //                 ->where('setup', $department->setup)
    //                 ->where('deptid', $department->deptid)
    //                 ->where('deleted', 0)
    //                 ->count();
    //             }   
    //         if ($checkifexists) {
    //             return array((object)[
    //                 'status'=>0,
    //                 'message'=>'Already Exist!',
    //             ]);
    //         } else {
                
    //             DB::table('department_setups')
    //             ->insert([
    //                 'setuptype' => $departments->setuptype,
    //                 'setup' => $departments->setup,
    //                 'depid' => $departments->depid,
    //                 'createdby' => auth()->user()->id,
    //                 'createddatetime' => date('Y-m-d H:i:s')
    //             ]);

    //             return array((object)[
    //                 'status'=>0,
    //                 'message'=>'Assign Successfully!',
    //             ]);
    //         }
    //     }
        
    // }

    public function savealldepartments(Request $request){
        $departments = json_decode($request->get('setups'));
        
        // return $departments;
        if (!empty($departments) && is_array($departments)) {
            foreach ($departments as $department) {
                if (is_object($department) &&
                    property_exists($department, 'setuptype') &&
                    property_exists($department, 'setup') &&
                    property_exists($department, 'deptid')
                ) {
                    $checkifexists = DB::table('department_setups')
                        ->where('setup', $department->setup)
                        ->where('deptid', $department->deptid)
                        ->where('setupid', $department->setupid)
                        ->where('deleted', 0)
                        ->count();
    
                    if ($checkifexists) {
                        return array((object)[
                            'status' => 0,
                            'message' => 'Already Exist!',
                        ]);
                    } else {
                        DB::table('department_setups')
                            ->insert([
                                'setuptype' => $department->setuptype,
                                'setup' => $department->setup,
                                'deptid' => $department->deptid,
                                'setupid' => $department->setupid,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                } else {
                    // Handle the case where the required properties are missing
                    return array((object)[
                        'status' => 0,
                        'message' => 'Invalid data format!',
                    ]);
                }
            }
    
            return array((object)[
                'status' => 1,
                'message' => 'Assign Successfully!',
            ]);
        } else {
            // Handle the case where the `$departments` array is empty or not an array
            return array((object)[
                'status' => 0,
                'message' => 'No departments found!',
            ]);
        }
    }
    
    public function saveallperemployee(Request $request){
        $peremployeedata = json_decode($request->get('per_employeedata'));

        // return $peremployeedata;
        if (!empty($peremployeedata) && is_array($peremployeedata)) {
            foreach ($peremployeedata as $perempdata) {
                if (is_object($perempdata) &&
                    property_exists($perempdata, 'employeeid') &&
                    property_exists($perempdata, 'otherdeductionid') &&
                    property_exists($perempdata, 'mod_loanamount') &&
                    property_exists($perempdata, 'desc') &&
                    property_exists($perempdata, 'mod_amounttobededuct') &&
                    property_exists($perempdata, 'mod_terms') &&
                    property_exists($perempdata, 'mod_edate') &&
                    property_exists($perempdata, 'mod_sdate') &&
                    property_exists($perempdata, 'mod_interestrate')
                ) {

                    $checkifexists = DB::table('employee_deductionother')
                        ->where('employeeid', $perempdata->employeeid)
                        ->where('deductionotherid', $perempdata->otherdeductionid)
                        ->where('deleted', 0)
                        ->count();
    
                    if ($checkifexists) {
                        return array((object)[
                            'status' => 0,
                            'message' => 'Already Exist!',
                        ]);
                    } else {
                        DB::table('employee_deductionother')
                            ->insert([
                                'od_interest' => $perempdata->mod_interestrate,
                                'fullamount' => $perempdata->mod_loanamount,
                                'description' => $perempdata->desc,
                                'term' => $perempdata->mod_terms,
                                'startdate' => $perempdata->mod_sdate,
                                'dateissued' => $perempdata->mod_sdate,
                                'enddate' => $perempdata->mod_edate,
                                'amounttobededuct' => $perempdata->mod_amounttobededuct,
                                'amount' => $perempdata->mod_amounttobededuct,
                                'deductionotherid' => $perempdata->otherdeductionid,
                                'employeeid' => $perempdata->employeeid,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                } else {
                    //Handle the case where the required properties are missing
                    // return array((object)[
                    //     'status' => 0,
                    //     'message' => 'Invalid data format!',
                    // ]);
                    return 0;
                }
            } 

            // return array((object)[
            //     'status' => 1,
            //     'message' => 'Assign Successfully!',
            // ]);
            return 1;

        } else {
            // Handle the case where the `$departments` array is empty or not an array
            // return array((object)[
            //     'status' => 0,
            //     'message' => 'No Employee found!',
            // ]);
            return 2;
        }
    }

}
