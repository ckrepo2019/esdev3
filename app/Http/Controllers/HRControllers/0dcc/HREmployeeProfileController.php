<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
class HREmployeeProfileController extends Controller
{
    public function index(Request $request)
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
                // 'employee_personalinfo.designationid',
                'employee_personalinfo.date_joined as datehired'
                )
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            ->leftJoin('hr_empstatus','teacher.employmentstatus','=','hr_empstatus.id')
            ->where('teacher.id', $teacherid)
            ->first();

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
}
