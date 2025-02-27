<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
class HREmployeeInfoController extends Controller
{
    // public function employeeprofile(Request $request)
    // {

    //     $teacherid = $request->get('employeeid');
            
    //     $civilstatus = Db::table('civilstatus')
    //         ->where('deleted','0')
    //         ->get();

    //     $nationality = Db::table('nationality')
    //         ->where('deleted','0')
    //         ->get();

    //     $religion = Db::table('religion')
    //         ->where('deleted','0')
    //         ->get();

    //     $profile = Db::table('teacher')
    //         ->select(
    //             'teacher.id',
    //             'teacher.lastname',
    //             'teacher.middlename',
    //             'teacher.firstname',
    //             'teacher.suffix',
    //             'teacher.title',
    //             'teacher.licno',
    //             'teacher.tid',
    //             'teacher.deleted',
    //             'teacher.isactive',
    //             'teacher.picurl',
    //             'teacher.rfid',
    //             'teacher.employmentstatus',
    //             'usertype.utype',
    //             'usertype.id as designationid',
    //             'employee_personalinfo.nationalityid',
    //             'employee_personalinfo.religionid',
    //             'employee_personalinfo.dob',
    //             'employee_personalinfo.gender',
    //             'employee_personalinfo.address',
    //             'employee_personalinfo.contactnum',
    //             'employee_personalinfo.email',
    //             'employee_personalinfo.maritalstatusid',
    //             'employee_personalinfo.spouseemployment',
    //             'employee_personalinfo.numberofchildren',
    //             'employee_personalinfo.emercontactname',
    //             'employee_personalinfo.emercontactrelation',
    //             'employee_personalinfo.emercontactnum',
    //             'employee_personalinfo.departmentid',
    //             // 'employee_personalinfo.designationid',
    //             'employee_personalinfo.date_joined as datehired'
    //             )
    //         ->join('usertype','teacher.usertypeid','=','usertype.id')
    //         ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
    //         ->where('teacher.id', $teacherid)
    //         ->first();


    //     if($profile->nationalityid == 0){

    //         $profile->nationality = "";

    //     }else{

    //         $getnationality = Db::table('nationality')
    //             ->where('id', $profile->nationalityid)
    //             ->first();
                
    //         $profile->nationality = $getnationality->nationality;

    //     }

    //     if($profile->religionid == 0){

    //         $profile->religionname = "";

    //     }else{

    //         $getreligionname = Db::table('religion')
    //             ->where('id', $profile->religionid)
    //             ->first();
                
    //         $profile->religionname = $getreligionname->religionname;

    //     }

    //     if($profile->maritalstatusid == 0){

    //         $profile->civilstatus = "";

    //     }else{

    //         $getcivilstatus = Db::table('civilstatus')
    //             ->where('id', $profile->maritalstatusid)
    //             ->first();

    //         $profile->civilstatus = $getcivilstatus->civilstatus;

    //     }

    //     if($profile->dob == null){

    //         $profile->dobstring = "";

    //     }else{

    //         $profile->dobstring = date('F d, Y', strtotime($profile->dob));
    //     }

    //     if($profile->datehired == null){

    //         $profile->datehired = "";

    //         $profile->datehiredstring = "";

    //     }else{

    //         $profile->datehired = date('Y-m-d', strtotime($profile->datehired));

    //         $profile->datehiredstring = date('F d, Y', strtotime($profile->datehired));

    //     }

    //     // return collect($profile);

    //     $employee_accounts = Db::table('employee_accounts')
    //         ->where('employeeid',$teacherid)
    //         ->where('employee_accounts.deleted','0')
    //         ->get();
            
    //     $employee_familyinfo = Db::table('employee_familyinfo')
    //         ->where('employeeid',$teacherid)
    //         ->where('deleted','0')
    //         ->get();

    //     $employee_educationinfo = Db::table('employee_educationinfo')
    //         ->where('employeeid',$teacherid)
    //         ->where('deleted','0')
    //         ->get();

    //     $employee_experience = Db::table('employee_experience')
    //         ->where('employeeid',$teacherid)
    //         ->where('deleted','0')
    //         ->get();

    //     $profile->accounts = $employee_accounts;
    //     $profile->familyinfo = $employee_familyinfo;
    //     $profile->educationalbackground = $employee_educationinfo;
    //     $profile->experiences = $employee_experience;
        
    //     $nationality = Db::table('nationality')
    //         ->get();
    //     $civilstatus = Db::table('civilstatus')
    //         ->get();
    //     $religions = Db::table('religion')
    //         ->get();

    //     $departments = Db::table('hr_school_department')
    //         ->where('deleted','0')
    //         ->get();
    //     $designations = Db::table('usertype')
    //         ->where('deleted','0')
    //         ->get();

        
        
    //     if(count(DB::table('employee_basicsalaryinfo')->where('employeeid', $teacherid)->get()) == 0)
    //     {
    //         DB::table('employee_basicsalaryinfo')
    //             ->insert([
    //                 'employeeid'    => $teacherid,
    //                 'createdby'     => auth()->user()->id,
    //                 'createddatetime'   => date('Y-m-d H:i:s')
    //             ]);
    //     }
    //     return view('hr.employeeprofile.index')
    //         ->with('profileinfo',$profile)
    //         ->with('nationality',$nationality)
    //         ->with('civilstatus',$civilstatus)
    //         ->with('religions',$religions)
    //         ->with('departments',$departments)
    //         ->with('designations',$designations);
    // }
    // public function employeeprofilechangepic(Request $request)

    // {
    //     $sy = DB::table('sy')
    //         ->where('isactive','1')
    //         ->first();

    //     $urlFolder = str_replace('http://','',$request->root());

    //     if (! File::exists(public_path().'employeeprofile/'.$sy->sydesc)) {

    //         $path = public_path('employeeprofile/'.$sy->sydesc);

    //         if(!File::isDirectory($path)){
                
    //             File::makeDirectory($path, 0777, true, true);

    //         }else{
                
    //         }
            
    //     }
        
    //     if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc)) {

    //         $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc;
            
    //         if(!File::isDirectory($cloudpath)){

    //             File::makeDirectory($cloudpath, 0777, true, true);
                
    //         }
            
    //     }
        
    //     $lastname = str_replace(' ', '_', $request->lastname);
    //     $lastname = str_replace('', '.', $lastname);
            
    //     $data = $request->image;

    //     list($type, $data) = explode(';', $data);

    //     list(, $data)      = explode(',', $data);

    //     $data = base64_decode($data);

    //     $extension = 'png';

    //     $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc.'/'.$request->username.'_'.$lastname.'.'.$extension;
        
    //     try{

    //         file_put_contents($clouddestinationPath, $data);
            
    //     }
    //     catch(\Exception $e){
           
    
    //     }

    //     $destinationPath = public_path('employeeprofile/'.$sy->sydesc.'/'. $request->username.'_'.$lastname.'.'.$extension);
        
    //     file_put_contents($destinationPath, $data);

    //     DB::table('teacher')
    //         ->where('id',$request->employeeid)
    //         ->update([
    //             'picurl' => 'employeeprofile/'.$sy->sydesc.'/'. $request->username.'_'.$lastname.'.'.$extension
    //         ]);

    //     return asset('employeeprofile/'.$sy->sydesc.'/'. $request->username.'_'.$lastname.'.'.$extension);

    // }
    // public function employeeprofileupdaterfid(Request $request)
    // {
    //     $checkifregistered = DB::table('rfidcard')
    //         ->where('rfidcode', $request->get('rfid'))
    //         ->where('deleted','0')
    //         ->count();

    //     if($checkifregistered == 0)
    //     {
    //         return '2';
    //     }else{
    //         $checkifexists = Db::table('teacher')
    //             ->where('rfid',$request->get('rfid') )
    //             ->get();
    //         // return $request->get('rfid');
    //         if(count($checkifexists) == 0){
    
    //             DB::table('teacher')
    //                 ->where('id', $request->get('id'))
    //                 ->update([
    //                     'rfid'              => $request->get('rfid'),
    //                     'updateddatetime'   => date('Y-m-d H:i:s')
    //                 ]);
    
    //                 // return back();
    //             return '1';
    
    //         }else{
    
    //             return '0';
    
    //         }
    //     }
    // }
    // public function tabprofile(Request $request)
    // {

    //     $teacherid = $request->get('employeeid');
            
    //     $civilstatus = Db::table('civilstatus')
    //         ->where('deleted','0')
    //         ->get();

    //     $nationality = Db::table('nationality')
    //         ->where('deleted','0')
    //         ->get();

    //     $religion = Db::table('religion')
    //         ->where('deleted','0')
    //         ->get();

    //     $profile = Db::table('teacher')
    //         ->select(
    //             'teacher.id',
    //             'teacher.lastname',
    //             'teacher.middlename',
    //             'teacher.firstname',
    //             'teacher.suffix',
    //             'teacher.title',
    //             'teacher.licno',
    //             'teacher.tid',
    //             'teacher.deleted',
    //             'teacher.isactive',
    //             'teacher.picurl',
    //             'teacher.rfid',
    //             'teacher.employmentstatus',
    //             'usertype.utype',
    //             'usertype.id as designationid',
    //             'employee_personalinfo.nationalityid',
    //             'employee_personalinfo.religionid',
    //             'employee_personalinfo.dob',
    //             'employee_personalinfo.gender',
    //             'employee_personalinfo.address',
    //             'employee_personalinfo.contactnum',
    //             'employee_personalinfo.email',
    //             'employee_personalinfo.maritalstatusid',
    //             'employee_personalinfo.spouseemployment',
    //             'employee_personalinfo.numberofchildren',
    //             'employee_personalinfo.emercontactname',
    //             'employee_personalinfo.emercontactrelation',
    //             'employee_personalinfo.emercontactnum',
    //             'employee_personalinfo.departmentid',
    //             // 'employee_personalinfo.designationid',
    //             'employee_personalinfo.date_joined as datehired'
    //             )
    //         ->join('usertype','teacher.usertypeid','=','usertype.id')
    //         ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
    //         ->where('teacher.id', $teacherid)
    //         ->first();


    //     if($profile->nationalityid == 0){

    //         $profile->nationality = "";

    //     }else{

    //         $getnationality = Db::table('nationality')
    //             ->where('id', $profile->nationalityid)
    //             ->first();
                
    //         $profile->nationality = $getnationality->nationality;

    //     }

    //     if($profile->religionid == 0){

    //         $profile->religionname = "";

    //     }else{

    //         $getreligionname = Db::table('religion')
    //             ->where('id', $profile->religionid)
    //             ->first();
                
    //         $profile->religionname = $getreligionname->religionname;

    //     }

    //     if($profile->maritalstatusid == 0){

    //         $profile->civilstatus = "";

    //     }else{

    //         $getcivilstatus = Db::table('civilstatus')
    //             ->where('id', $profile->maritalstatusid)
    //             ->first();

    //         $profile->civilstatus = $getcivilstatus->civilstatus;

    //     }

    //     if($profile->dob == null){

    //         $profile->dobstring = "";

    //     }else{

    //         $profile->dobstring = date('F d, Y', strtotime($profile->dob));
    //     }

    //     if($profile->datehired == null){

    //         $profile->datehired = "";

    //         $profile->datehiredstring = "";

    //     }else{

    //         $profile->datehired = date('Y-m-d', strtotime($profile->datehired));

    //         $profile->datehiredstring = date('F d, Y', strtotime($profile->datehired));

    //     }

    //     // return collect($profile);

    //     $employee_accounts = Db::table('employee_accounts')
    //         ->where('employeeid',$teacherid)
    //         ->where('employee_accounts.deleted','0')
    //         ->get();
            
    //     $employee_familyinfo = Db::table('employee_familyinfo')
    //         ->where('employeeid',$teacherid)
    //         ->where('deleted','0')
    //         ->get();

    //     $employee_educationinfo = Db::table('employee_educationinfo')
    //         ->where('employeeid',$teacherid)
    //         ->where('deleted','0')
    //         ->get();

    //     $employee_experience = Db::table('employee_experience')
    //         ->where('employeeid',$teacherid)
    //         ->where('deleted','0')
    //         ->get();

    //     $profile->accounts = $employee_accounts;
    //     $profile->familyinfo = $employee_familyinfo;
    //     $profile->educationalbackground = $employee_educationinfo;
    //     $profile->experiences = $employee_experience;
        
    //     $nationality = Db::table('nationality')
    //         ->get();
    //     $civilstatus = Db::table('civilstatus')
    //         ->get();
    //     $religions = Db::table('religion')
    //         ->get();

    //     $departments = Db::table('hr_school_department')
    //         ->where('deleted','0')
    //         ->get();
    //     $designations = Db::table('usertype')
    //         ->where('deleted','0')
    //         ->get();

    //     return view('hr.employeeprofile.basicprofile')
    //         ->with('profileinfo',$profile)
    //         ->with('nationality',$nationality)
    //         ->with('civilstatus',$civilstatus)
    //         ->with('religions',$religions)
    //         ->with('departments',$departments)
    //         ->with('designations',$designations);
    // }
    // public function updatepersonalinfo(Request $request)
    // {
            
    //     DB::table('teacher')
    //     ->where('id', $request->get('employeeid'))
    //     ->update([
    //         'title'         =>  $request->get('profiletitle'),
    //         'suffix'        =>  $request->get('profilesuffix'),
    //         'lastname'      =>  $request->get('profilelname'),
    //         'firstname'     =>  $request->get('profilefname'),
    //         'middlename'    =>  $request->get('profilemname')
    //     ]);

    //     $checkifexists = DB::table('employee_personalinfo')
    //         ->where('employeeid',$request->get('employeeid'))
    //         ->get();
            
    //     if(count($checkifexists)==0){

    //         Db::table('employee_personalinfo')
    //             ->insert([
    //                 'employeeid'        => $request->get('employeeid'),
    //                 'dob'               => $request->get('profiledob'),
    //                 'gender'            => $request->get('profilegender'),
    //                 'address'           => $request->get('profileaddress'),
    //                 'contactnum'        => $request->get('contactnum'),
    //                 'email'             => $request->get('profileemail'),
    //                 'spouseemployment'  => $request->get('profilespouseemployment'),
    //                 'numberofchildren'  => $request->get('profilenumofchildren'),
    //                 // 'designationid'     => $request->get('designationid'),
    //                 'maritalstatusid'   => $request->get('profilecivilstatusid'),
    //                 'religionid'        => $request->get('profilereligionid'),
    //                 'nationalityid'     => $request->get('profilenationalityid'),
    //                 'date_joined'       => $request->get('profiledatehired')
    //             ]);

    //         DB::table('teacher')
    //             ->where('id', $request->get('employeeid'))
    //             ->update([
    //                 'datehired'         => $request->get('profiledatehired')
    //             ]);

    //     }
    //     else{

    //         DB::table('employee_personalinfo')
    //             ->where('employeeid', $request->get('employeeid'))
    //             ->update([
    //                 'dob'               =>  $request->get('profiledob'),
    //                 'gender'            =>  $request->get('profilegender'),
    //                 'address'           =>  $request->get('profileaddress'),
    //                 'contactnum'        =>  $request->get('contactnum'),
    //                 'email'             =>  $request->get('profileemail'),
    //                 'spouseemployment'  =>  $request->get('profilespouseemployment'),
    //                 'numberofchildren'  =>  $request->get('profilenumofchildren'),
    //                 // 'designationid'     => $request->get('designationid'),
    //                 'maritalstatusid'   => $request->get('profilecivilstatusid'),
    //                 'religionid'        => $request->get('profilereligionid'),
    //                 'nationalityid'     => $request->get('profilenationalityid'),
    //                 'date_joined'       => $request->get('profiledatehired')
    //             ]);

    //         DB::table('teacher')
    //             ->where('id', $request->get('employeeid'))
    //             ->update([
    //                 'datehired'         => $request->get('profiledatehired')
    //             ]);

    //     }
    //     return $request->all();
    // }
    // public function updateemergencycontact(Request $request)
    // {
    //     // return $request->all();
    //     $checkifexists = DB::table('employee_personalinfo')
    //     ->where('employeeid',$request->get('employeeid'))
    //     ->get();

    //     if(count($checkifexists)==0){

    //         Db::table('employee_personalinfo')
    //             ->insert([
    //                 'employeeid'            => $request->get('employeeid'),
    //                 'emercontactname'       => $request->get('emergencyname'),
    //                 'emercontactrelation'   => $request->get('emergencyrelationship'),
    //                 'emercontactnum'        => $request->get('emergencycontactnumber')
    //             ]);

    //     }else{

    //         DB::table('employee_personalinfo')
    //             ->where('employeeid', $request->get('employeeid'))
    //             ->update([
    //                 'emercontactname'       =>  $request->get('emergencyname'),
    //                 'emercontactrelation'   =>  $request->get('emergencyrelationship'),
    //                 'emercontactnum'        =>  $request->get('emergencycontactnumber')
    //             ]);

    //         }

    //     // return back()->with('linkid',$request->get('linkid'));
    // }
    public function getdesignations(Request $request)
    {
        
        $designations = DB::table('usertype')
            ->where('departmentid',$request->get('departmentid'))
            ->where('deleted','0')
            ->where('utype','!=', 'PARENT')
            ->where('utype','!=', 'STUDENT')
            ->where('utype','!=', 'ADMINADMIN')
            ->where('utype','!=', 'COLLEGE ADMIN')
            ->where('utype','!=', 'SUPER ADMIN')
            ->get();
        
        return $designations;
    }
    public function updatedesignation(Request $request)
    {
        
        $checkifexists = Db::table('employee_personalinfo')
        ->where('employeeid',$request->get('employeeid'))
        ->get();
        
        if(count($checkifexists) == 0){

            DB::table('employee_personalinfo')
                ->insert([
                    'employeeid'        => $request->get('employeeid'),
                    'departmentid'      => $request->get('departmentid'),
                    'designationid'     => $request->get('designationid')
                ]);

        }else{

            DB::table('employee_personalinfo')
                ->where('employeeid', $request->get('employeeid'))
                ->update([
                    'departmentid'      => $request->get('departmentid'),
                    'designationid'     => $request->get('designationid')
                ]);

            // $userid = Db::table('teacher')
            // ->where('id',$request->get('id'))
            // ->first()->userid;
            DB::table('teacher')
                ->where('id', $request->get('employeeid'))
                ->update([
                    'usertypeid'        => $request->get('designationid')
                ]);
        }
        $userid = DB::table('teacher')
            ->where('id',$request->get('employeeid'))
            ->first()
            ->userid;

        DB::table('users')
            ->where('id', $userid)
            ->update([
                'type'  => $request->get('designationid')
            ]);
        // return back();
        
    }
    // public function updateaccounts(Request $request)
    // {
        
    //         // return $request->all();
    //         $createdby      = DB::table('teacher')
    //                         ->where('userid', auth()->user()->id)
    //                         ->first()
    //                         ->id;
    //         if($request->get('oldaccountid') == true){

                
    //             foreach($request->get('oldaccountid') as $oldaccountkey => $accountid){

    //                 DB::table('employee_accounts')
    //                     ->where('id',$accountid)
    //                     ->update([
    //                         'accountdescription'    => $request->get('oldaccountdescription')[$oldaccountkey],
    //                         'accountnum'            => $request->get('oldaccountnumber')[$oldaccountkey]
    //                     ]);

    //             }

    //         }
            
    //         if($request->get('newdescriptions') == true){

    //             foreach($request->get('newdescriptions') as $newaccountkey => $description){

    //                 $checkifexists = DB::table('employee_accounts')
    //                                 ->where('employeeid',$request->get('employeeid'))
    //                                 ->where('accountdescription', 'like','%'.$description)
    //                                 ->get();
    
    //                 if(count($checkifexists) == 0){
    
    //                     DB::table('employee_accounts')
    //                         ->insert([
    //                             'employeeid'            => $request->get('employeeid'),
    //                             'accountdescription'    => strtoupper($description),
    //                             'accountnum'            => $request->get('newaccountnumber')[$newaccountkey],
    //                             'createdby'             => $createdby,
    //                             'createddatetime'       => date('Y-m-d H:i:s')
    //                         ]);
    
    //                 }
    
    //             }
                    
    //         }

    //         // return back()->with('linkid', $request->get('linkid'));       
        
    // }
    // public function deleteaccount(Request $request)
    // {
    //     try{
    //         DB::table('employee_accounts')
    //         ->where('id',$request->get('accountid'))
    //         ->update([
    //             'deleted'   => '1'
    //         ]);
    //         return 1;
    //     }catch(\Exception $error)
    //     {
    //         return 0;
    //     }
    // }
    public function updatefamilyinfo(Request $request)
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

        // return back();
    }
    // public function deletefamilyinfo(Request $request)
    // {
    //     // return $request->all();
    //     $getMyid = DB::table('teacher')
    //     ->select('id')
    //     ->where('userid', auth()->user()->id)
    //     ->first();
    //     Db::table('employee_familyinfo')
    //         ->where('employeeid',$request->get('employeeid'))
    //         ->where('id',$request->get('familymemberid'))
    //         ->where('deleted','0')
    //         ->update([
    //             'deleted' => '1',
    //             'updated_by' => $getMyid->id,
    //             'updated_on' => date('Y-m-d H:i:s')
    //         ]);
    // }
    public function updateeducationinfo(Request $request)
    {
        // return $request->all();
        
        date_default_timezone_set('Asia/Manila');
        $getMyid = DB::table('teacher')
        ->select('id')
        ->where('userid', auth()->user()->id)
        ->first();
        
        $employeeeducationinfo = Db::table('employee_educationinfo')
            ->where('deleted','0')
            ->get();

        if($request->get('oldid') == true){

            foreach($employeeeducationinfo as $educinfo){

                $exists = 0;

                if(in_array($educinfo->id, $request->get('oldid'))){

                }
                else{

                    DB::table('employee_educationinfo')
                        ->where('employeeid',$request->get('employeeid'))
                        ->where('id',$educinfo->id)
                        ->update([
                            'deleted'       => '1',
                            'updated_by'    => $getMyid->id,
                            'updated_on'    => date('Y-m-d H:i:s')
                        ]);

                }

            }

            foreach($request->get('oldid') as $key => $value){

                DB::table('employee_educationinfo')
                    ->where('employeeid', $request->get('employeeid'))
                    ->where('id',$value)
                    ->update([
                        'schoolname'        => $request->get('oldschoolname')[$key],
                        'schooladdress'     => $request->get('oldaddress')[$key],
                        'coursetaken'       => $request->get('oldcoursetaken')[$key],
                        'major'             => $request->get('oldmajor')[$key],
                        'completiondate'    => $request->get('olddatecompleted')[$key],
                        'updated_by'        => $getMyid->id,
                        'updated_on'        => date('Y-m-d H:i:s')
                    ]);

            }

        }
        else{
            
            if(count($request->except('employeeid'))==0){

                foreach($employeeeducationinfo as $education){
                    
                    $erer = DB::table('employee_educationinfo')
                        ->where('employeeid', $request->get('employeeid'))
                        ->where('id',$education->id)
                        ->update([
                            'deleted'       => '1',
                            'updated_by'    => $getMyid->id,
                            'updated_on'    => date('Y-m-d H:i:s')
                        ]);
                        
                }

            }else{

                foreach($request->get('schoolname') as $key => $value){
                    // return 'asdasd';
                    $checkifexists = Db::table('employee_educationinfo')
                        ->where('employeeid',$request->get('employeeid'))
                        ->where('completiondate',$request->get('datecompleted')[$key])
                        ->get();
                        
                    if(count($checkifexists) == 0){

                        DB::table('employee_educationinfo')
                            ->insert([
                                'employeeid'        => $request->get('employeeid'),
                                'schoolname'        => $value,
                                'schooladdress'     => $request->get('address')[$key],
                                'coursetaken'       => $request->get('coursetaken')[$key],
                                'major'             => $request->get('major')[$key],
                                'completiondate'    => $request->get('datecompleted')[$key],
                                'updated_by'        => $getMyid->id,
                                'updated_on'        => date('Y-m-d H:i:s')
                            ]);

                    }else{

                        DB::table('employee_educationinfo')
                            ->where('employeeid', $request->get('employeeid'))
                            ->where('id',$checkifexists[0]->id)
                            ->update([
                                'schoolname'        => $value,
                                'schooladdress'     => $request->get('address')[$key],
                                'coursetaken'       => $request->get('coursetaken')[$key],
                                'major'             => $request->get('major')[$key],
                                'completiondate'    => $request->get('datecompleted')[$key],
                                'updated_by'        => $getMyid->id,
                                'updated_on'        => date('Y-m-d H:i:s')
                            ]);

                    }

                }

            }

        }
        // return back();
    }
    public function employeeexperience($action, Request $request)
    {
        // return $request->all();
        date_default_timezone_set('Asia/Manila');
        
        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            // ->where('isactive','1')
            // ->where('deleted','0')
            ->first();
        if($action == 'updateexperience'){
            $employeeexperience = Db::table('employee_experience')
                ->where('employeeid',$request->get('employeeid'))
                ->where('deleted','0')
                ->get();
                
            if($request->get('oldid') == true){
                
                foreach($request->get('oldid') as $key => $value){
                        DB::table('employee_experience')
                            ->where('employeeid', $request->get('employeeid'))
                            ->where('id',$value)
                            ->update([
                                'companyname'       => $request->get('oldcompanyname')[$key],
                                'companyaddress'    => $request->get('oldlocation')[$key],
                                'position'          => $request->get('oldjobposition')[$key],
                                'periodfrom'        => $request->get('oldperiodfrom')[$key],
                                'periodto'          => $request->get('oldperiodto')[$key],
                                'updated_by'        => $getMyid->id,
                                'updated_on'        => date('Y-m-d H:i:s')
                            ]);
                }
    
            }
            else{
                
                if(count($request->except('employeeid'))==0){
                    
                    foreach($employeeexperience as $experience){
                        
                        $erer = DB::table('employee_experience')
                            ->where('employeeid', $request->get('employeeid'))
                            ->where('id',$experience->id)
                            ->update([
                                'deleted'       => '1',
                                'updated_by'    => $getMyid->id,
                                'updated_on'    => date('Y-m-d H:i:s')
                            ]);
                            
                    }
    
                }
                else{
                    
                    foreach($request->get('companyname') as $key => $value){
    
                        $checkifexists = Db::table('employee_experience')
                            ->where('companyname','like','%'.$value)
                            ->where('employeeid',$request->get('employeeid'))
                            ->where('deleted','0')
                            ->get();
                            
                        if(count($checkifexists) == 0){
    
                            DB::table('employee_experience')
                                ->insert([
                                    'employeeid'        => $request->get('employeeid'),
                                    'companyname'       => $value,
                                    'companyaddress'    => $request->get('location')[$key],
                                    'position'          => $request->get('jobposition')[$key],
                                    'periodfrom'        => $request->get('periodfrom')[$key],
                                    'periodto'          => $request->get('periodto')[$key],
                                    'updated_by'        => $getMyid->id,
                                    'updated_on'        => date('Y-m-d H:i:s')
                                ]);
    
                        }else{
    
                            DB::table('employee_experience')
                                ->where('employeeid', $request->get('employeeid'))
                                ->where('id',$checkifexists[0]->id)
                                ->update([
                                    'companyname'       => $value,
                                    'companyaddress'    => $request->get('location')[$key],
                                    'position'          => $request->get('position')[$key],
                                    'periodfrom'        => $request->get('periodfrom')[$key],
                                    'periodto'          => $request->get('periodto')[$key],
                                    'updated_by'        => $getMyid->id,
                                    'updated_on'        => date('Y-m-d H:i:s')
                                ]);
    
                        }
    
                    }
    
                }
    
            }
        }else{

            // return $request->all();
            DB::table('employee_experience')
                ->where('employeeid', $request->get('employeeid'))
                ->where('id', $request->get('experienceid'))
                ->update([
                    'deleted'   => 1
                ]);
        }

        return back();

    }
    
}
