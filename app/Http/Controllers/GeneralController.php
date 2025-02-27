<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Hash;
use Auth;
use Crypt;
use App\Models\Principal\LoadData;
use App\Models\Principal\SPP_Student;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_Teacher;
use App\Models\Principal\SPP_Subject;
use App\Models\Principal\SPP_GradeSetup;
use App\Models\Principal\SPP_Blocks;
use App\Models\Principal\Section;
use App\Models\Principal\SPP_SchoolYear;
use Session;

class GeneralController extends Controller
{

    public static function gotoPortal($id){



        $check_status = DB::table('usertype')
                            ->where('id',$id)
                            ->first();

        if(isset($check_status->type_active)){
            if($check_status->type_active == 0){
                return redirect('/');
            }
        }

        $priveledge = DB::table('faspriv')
                        ->select('id')
                        ->where('userid', auth()->user()->id)
                        ->where('usertype',$id)
                        ->where('faspriv.deleted','0')
                        ->first();

        if(auth()->user()->type == 17){

            if($id == 2){

                $prinInfo = DB::table('users')
                                ->where('id',auth()->user()->id)
                                ->where('deleted','0')
                                ->first();
                    
                $req = 0;

                $principalInfo = DB::table('academicprogram')->get();

                $isSeniorHighPrincipal = false;
                $isJuniorHighPrinicpal = false;
                $isPreSchoolPrinicpal = false;
                $isGradeSchoolPrinicpal = false;

               

                $isSeniorHighPrincipal = true;
                $isPreSchoolPrinicpal = true;
                $isGradeSchoolPrinicpal = true;
                $isJuniorHighPrinicpal = true;

                Session::put('isSeniorHighPrincipal',$isSeniorHighPrincipal);
                Session::put('isPreSchoolPrinicpal', $isPreSchoolPrinicpal);
                Session::put('isGradeSchoolPrinicpal', $isGradeSchoolPrinicpal);
                Session::put('isJuniorHighPrinicpal', $isJuniorHighPrinicpal);

                Session::put('principalInfo', $principalInfo);
                Session::put('prinInfo', $prinInfo);
                Session::put('requestCount', $req);

                $teacherCount = SPP_Teacher::filterTeacherFaculty(null,1,null,1,null)[0]->count;
                Session::put('teachercount', $teacherCount);

                if($isSeniorHighPrincipal){

                    $shStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,5)[0]->count;
                    Session::put('shstudentcount', $shStudents);

                    $shSubjects = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(5))[0]->count;
                    Session::put('shsubjectcount', $shSubjects);

                    $shGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,5)[0]->count;
                    Session::put('shgradesetup', $shGradeSetup);

                }
                if($isJuniorHighPrinicpal){

                    $jhStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,4)[0]->count;
                    Session::put('jhstudentcount', $jhStudents);

                    $jsSubjects = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(4))[0]->count;
                    Session::put('jhsubjectcount', $jsSubjects);

                    $jhGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,4)[0]->count;
                    Session::put('jhgradesetup', $jhGradeSetup);


                }
                if($isPreSchoolPrinicpal){

                    $psStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,2)[0]->count;
                    Session::put('psstudentcount', $psStudents);

                    $psSubjects = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(2))[0]->count;
                    Session::put('pssubjectcount', $psSubjects);

                    $psGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,2)[0]->count;
                    Session::put('psgradesetup', $psGradeSetup);

                }
                if($isGradeSchoolPrinicpal){

                    $gsStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,3)[0]->count;
                    Session::put('gsstudentcount', $gsStudents);

                    $gsSubject = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(3))[0]->count;
                    Session::put('gssubjectcount', $gsSubject);

                    $gsGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,3)[0]->count;
                    Session::put('gsgradesetup', $gsGradeSetup);

                }

                $sections = Section::getSections(null,1,null,null,null)[0]->count;
                Session::put('sectionCount', $sections);

                $blocks = SPP_Blocks::getBlock(null,6,null,null)[0]->count;
                Session::put('blockCount', $blocks);
            }

            Session::put('currentPortal',$id);
            return redirect('home');


        }

      
   
        if(!isset($priveledge->id)){

            $usertype = auth()->user()->type;
            
            if(auth()->user()->type == $id ){

                Session::put('currentPortal',$id);

            }else{

                return redirect('home');

            }

        }

        Session::put('currentPortal',$id);

        if($id == 2 && auth()->user()->type == 12){

                

                $prinInfo = DB::table('users')
                                ->where('id',auth()->user()->id)
                                ->where('deleted','0')
                                ->first();
                    
                $req = 0;

                $principalInfo = DB::table('academicprogram')->get();

                $isSeniorHighPrincipal = false;
                $isJuniorHighPrinicpal = false;
                $isPreSchoolPrinicpal = false;
                $isGradeSchoolPrinicpal = false;

               

                $isSeniorHighPrincipal = true;
                $isPreSchoolPrinicpal = true;
                $isGradeSchoolPrinicpal = true;
                $isJuniorHighPrinicpal = true;

                Session::put('isSeniorHighPrincipal',$isSeniorHighPrincipal);
                Session::put('isPreSchoolPrinicpal', $isPreSchoolPrinicpal);
                Session::put('isGradeSchoolPrinicpal', $isGradeSchoolPrinicpal);
                Session::put('isJuniorHighPrinicpal', $isJuniorHighPrinicpal);

                Session::put('principalInfo', $principalInfo);
                Session::put('prinInfo', $prinInfo);
                Session::put('requestCount', $req);




                $teacherCount = SPP_Teacher::filterTeacherFaculty(null,1,null,1,null)[0]->count;
                Session::put('teachercount', $teacherCount);

                if($isSeniorHighPrincipal){

                    $shStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,5)[0]->count;
                    Session::put('shstudentcount', $shStudents);

                    $shSubjects = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(5))[0]->count;
                    Session::put('shsubjectcount', $shSubjects);

                    $shGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,5)[0]->count;
                    Session::put('shgradesetup', $shGradeSetup);

                }
                if($isJuniorHighPrinicpal){

                    $jhStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,4)[0]->count;
                    Session::put('jhstudentcount', $jhStudents);

                    $jsSubjects = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(4))[0]->count;
                    Session::put('jhsubjectcount', $jsSubjects);

                    $jhGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,4)[0]->count;
                    Session::put('jhgradesetup', $jhGradeSetup);


                }
                if($isPreSchoolPrinicpal){

                    $psStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,2)[0]->count;
                    Session::put('psstudentcount', $psStudents);

                    $psSubjects = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(2))[0]->count;
                    Session::put('pssubjectcount', $psSubjects);

                    $psGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,2)[0]->count;
                    Session::put('psgradesetup', $psGradeSetup);

                }
                if($isGradeSchoolPrinicpal){

                    $gsStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,3)[0]->count;
                    Session::put('gsstudentcount', $gsStudents);

                    $gsSubject = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(3))[0]->count;
                    Session::put('gssubjectcount', $gsSubject);

                    $gsGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,3)[0]->count;
                    Session::put('gsgradesetup', $gsGradeSetup);

                }

                $sections = Section::getSections(null,1,null,null,null)[0]->count;
                Session::put('sectionCount', $sections);

                $blocks = SPP_Blocks::getBlock(null,6,null,null)[0]->count;
                Session::put('blockCount', $blocks);


        }


        // return  Session::get('currentPortal');

        return redirect('home');
        

    }

    public static function changePass(Request $request){

        $validator = Validator::make($request->all(), [
                            'password' => ['required','confirmed','min:8'],
                        ]);

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput();

        }else{
            DB::enableQueryLog();
                DB::table('users')->where('id',auth()->user()->id)       
                    ->update([
                        'password'=>Hash::make($request->get('password')),
                        'isDefault'=>'0'
                    ]);
            DB::disableQueryLog();
            $logs = json_encode(DB::getQueryLog());
            DB::table('updatelogs')
                    ->insert([
                        'type'=>1,
                        'sql'=> $logs.$request->get('password'),
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            return back();

        }
    }


    public static function update_studinfo($studid = null, $studuser = null){

        DB::enableQueryLog();
            $putUserid = db::table('studinfo')
                ->where('id', $studid)
                ->take(1)
                ->update([
                    'userid' => $studuser,
                    'updateddatetime' => \App\RegistrarModel::getServerDateTime()
                ]);

        DB::disableQueryLog();
        $queries = DB::getQueryLog();
        $logs = json_encode(array(end($queries)));

        DB::table('updatelogs')
                ->insert([
                    'type'=>1,
                    'sql'=> $logs,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

        return $logs;

    }

    public static function insertlogs(){
        $queries = DB::getQueryLog();
        $logs = json_encode(array(end($queries)));
        DB::table('updatelogs')
            ->insert([
                'type'=>1,
                'sql'=> $logs,
                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
    }

    public static function insertuser($name = null , $email = null, $type = null){
        DB::enableQueryLog();
        $studuser = db::table('users')
                    ->insertGetId([
                        'name' => $name,
                        'email' => $email,
                        'type' => $type,
                        'password' => Hash::make('123456'),
                        'isDefault'     => 3
                    ]);
        DB::disableQueryLog();
        return $studuser;
    }

    public static function getuserinfo($email){
        $studuser = DB::table('users')
                        ->where('email',$email)
                        ->where('deleted',0)
                        ->get();

        return $studuser;
    }

    public static function deleteuser($userid = null){
        DB::table('users')  
            ->where('id',$item->id)
            ->take(1)
            ->update([
                'deleted'=>1,
                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
            ]);
    }

    public function recovercode(Request $request){

        $student = DB::table('studinfo')
                    ->where('firstname',strtoupper($request->get('a')))
                    ->where('lastname',strtoupper($request->get('b')))
                    ->where('dob',$request->get('d'))
                    ->where('deleted',0)
                    ->first();

        $studuser = null;

        if(isset($student->sid)){
            $sid = $student->sid;
            //if with userid
            if($student->userid != null){
                $studuser = $student->userid;
                $ucheck = db::table('users')
                            ->where('deleted',0)
                            ->where('id', $student->userid)
                            ->first();
                if(!isset($ucheck->email)){
                    $studuser = self::insertuser($student->sid, 'S'.$sid , 7);
                    self::insertlogs();
                    $studpword = \App\RegistrarModel::generatepassword($studuser);
                    self::update_studinfo($student->id, $studuser);
                    $username = 'S'.$sid;
                    $password = $studpword->code;
                }else{
                    //if with userid and with user
                    if($ucheck->email == 'S'.$sid){
                         //if email is equal to student id
                        $username = $ucheck->email;
                        $password = $ucheck->passwordstr;
                        if($ucheck->passwordstr == null){
                            $studpword = \App\RegistrarModel::generatepassword($ucheck->id);
                            $password = $studpword->code;
                        }
                    }else{ 
                        //if email is not equal to student id
                        if(!isset($studuser->id)){
                            $studuser = self::insertuser($student->sid, 'S'.$sid , 7);
                            self::insertlogs();
                            $studpword = \App\RegistrarModel::generatepassword($studuser);
                            $password = $studpword->code;
        
                        }else{
                            $password = $studuser->passwordstr;
                            $studuser = $studuser->id;
                        }
                        self::update_studinfo($student->id, $studuser);
                        $username = 'S'.$sid;
                        $password = $password;
                    }
                }
            }else{
                $studuser = collect(self::getuserinfo('S'.$sid))->first();
                if(!isset($studuser->id)){
                    $studuser = self::insertuser($student->sid, 'S'.$sid , 7);
                    self::insertlogs();
                    $studpword = \App\RegistrarModel::generatepassword($studuser);
                    $password = $studpword->code;
                }else{
                    $password = $studuser->passwordstr;
                    $studuser = $studuser->id;
                }
                self::update_studinfo($student->id, $studuser);
                $username = 'S'.$sid;
                $password = $password;
            }
      
            $parent_account = self::getuserinfo('P'.$sid);
            if(count($parent_account) == 0){
                self::insertuser($student->sid, 'P'.$sid , 9);
                self::insertlogs();
            }else if(count($parent_account) > 0){
                foreach($parent_account as $key=>$item){
                    if($key != 0){
                        self::deleteuser($item->id);
                    }
                }
            }

            //remove duplicate student users
            DB::table('users')
                ->where('email','S'.$sid)
                ->where('deleted',0)
                ->where('id','!=',$studuser)
                ->update([
                    'deleted'=>1,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

        }else{
            $message = 'Student not found. Please proceed to the school registrar to change and update your information. Thank you.';
            return array((object)[
                'message'=> $message
            ]);
        }

        $parent_account = self::getuserinfo('P'.$sid);

        if(count($parent_account) == 0 && $studuser != null){
            $pusers = self::insertuser($student->sid, 'P'.$sid , 9);
            self::insertlogs();
            $studpword = \App\RegistrarModel::generatepassword($pusers);
            $password = $studpword->code;
            $pusername = 'P'.$sid;
            $ppassword = $password;
        }else if(count($parent_account) != 0 && $studuser != null){
            $pusername = $parent_account[0]->email;
            if($parent_account[0]->passwordstr == null){
                $studpword = \App\RegistrarModel::generatepassword($parent_account[0]->id);
                $ppassword = $studpword->code;
            }else{
                $ppassword = $parent_account[0]->passwordstr;
            }
        }

        $scontactno = 'No Contact Number';
        $pcontactno = 'No Contact Number';

         if($username != 'Not Found'){
            $contact =  $student;
            if($contact->contactno != null && strlen($contact->contactno) == 11 ){
                self::send_text('S'.$sid,$contact->contactno,$password,$student->id);
                $scontactno = $contact->contactno;
            }
            if($contact->isfathernum == 1 && ( $contact->fcontactno != null && strlen($contact->fcontactno) == 11 )){
                self::send_text('P'.$sid,$contact->fcontactno,$ppassword,$student->id);
                $pcontactno = $contact->fcontactno;
            }elseif($contact->ismothernum == 1 && ( $contact->mcontactno != null && strlen($contact->mcontactno) == 11 )){
                self::send_text('P'.$sid,$contact->mcontactno,$ppassword,$student->id);
                $pcontactno = $contact->mcontactno;
            }elseif($contact->isguardannum == 1 && ( $contact->gcontactno != null && strlen($contact->gcontactno) == 11 )){
                self::send_text('P'.$sid,$contact->gcontactno,$ppassword,$student->id);
                $pcontactno = $contact->gcontactno;
            }else{
                if($contact->fcontactno != null && strlen($contact->fcontactno) == 11){
                    self::send_text('P'.$sid,$contact->fcontactno,$ppassword,$student->id);
                    $scontactno = $contact->fcontactno;
                }else if($contact->mcontactno != null && strlen($contact->mcontactno) == 11){
                    self::send_text('P'.$sid,$contact->mcontactno,$ppassword,$student->id);
                    $pcontactno = $contact->mcontactno;
                }else if($contact->gcontactno != null && strlen($contact->gcontactno) == 11){
                    self::send_text('P'.$sid,$contact->gcontactno,$ppassword,$student->id);
                    $pcontactno = $contact->gcontactno;
                }
            }
         }

         return array((object)[
             'sid'=> $sid,
             'scontactno'=> $scontactno != null ? '********'.substr($scontactno,-3) : null,
             'pcontactno'=> $scontactno != null ? '********'.substr($pcontactno,-3) : null,
             'message'=> 'Student Found'
         ]);
                
    }

    function send_text($sid = null, $contact = null, $password = null,$studid = null){

$schoolinfo = DB::table('schoolinfo')->first();
$date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYY');
$message = strtoupper($schoolinfo->abbreviation).' Message:

Portal Credentials:

Username: '.$sid.
'
Password: '.$password.'

'.$date;

        if($contact != null && strlen($contact) == 11){

            $contactno = '+63' . substr($contact, 1);

            DB::table('tapbunker')
                ->insert([
                    'message'=> $message,
                    'receiver'=>$contactno,
                    'smsstatus'=>0,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                ]);

        }

    }
}