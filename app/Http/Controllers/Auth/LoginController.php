<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use Auth;
use App\Models\Principal\LoadData;
use App\Models\Principal\SPP_Student;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_Teacher;
use App\Models\Principal\SPP_Subject;
use App\Models\Principal\SPP_GradeSetup;
use App\Models\Principal\SPP_Blocks;
use App\Models\Principal\Section;
use App\Models\Principal\SPP_SchoolYear;
use Crypt;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['deleted'] = 0;

        return $credentials;
    }

    public function logout(){

        try{

            DB::table('users')
                ->where('id',auth()->user()->id)
                ->update([
                    'loggedIn'=>'0',
                    'loggedOut'=>'1',
                    'dateLoggedOut'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            Auth::logout(); // log the user out of our application
            return Redirect::to('/');


        } catch (\Exception $e) {

            DB::table('zerrorlogs')
                        ->insert([
                            'error'=>$e,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            Auth::logout(); 
            return Redirect::to('/');

        }

    }
    //     public function login(Request $request)
    // {

    //     // if(Auth::loginUsingId(1)){

    //     //     return redirect('home');

    //     // }

    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'isActive' => '1']))
    //     {
    //         // Updated this line
    //         return $this->sendLoginResponse($request);

    //         // OR this one
    //         // return $this->authenticated($request, auth()->user());
    //     }
    //     else
    //     {
    //         return $this->sendFailedLoginResponse($request, 'auth.failed_status');
    //     }

    // }
    
    protected function authenticated(Request $request)
    {
		
		$check_status = DB::table('usertype')
                            ->where('id',auth()->user()->type)
                            ->first();

        if(isset($check_status->type_active)){
   
            if($check_status->type_active == 0){


                $faspriv = DB::table('faspriv')
                                ->where('faspriv.deleted',0)
                                ->where('userid',auth()->user()->id)
                                ->join('usertype',function($join){
                                    $join->on('faspriv.usertype','=','usertype.id');
                                    $join->where('type_active',1);
                                })
                                ->select('type_active','usertype.id')
                                ->first();

                if(isset($faspriv->type_active)){
                    Session::put('currentPortal', $faspriv->id);
                    return redirect('/');
                }else{
                    Auth::logout();
                    Session::flush();
                    toast('Access Restricted!','info')->autoClose(2000)->toToast($position = 'top-right');
                    return redirect('/login');

                }

              
            }

        }
	
	
		if(auth()->user()->type != 7 && auth()->user()->type != 9){
            $check = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->select('isactive')
                        ->first();

            if(isset($check->isactive)){
                if($check->isactive == 0){
                    Auth::logout();
                    Session::flush();
                    toast('Account Deactivated!','info')->autoClose(2000)->toToast($position = 'top-right');
                    return redirect('/login');
                }
            }
        }
	
        $currentSchoolYear = SPP_SchoolYear::getActiveSchoolYear();
        $currentSem = DB::table('semester')->where('isactive','1')->first();

        Session::put('schoolYear',$currentSchoolYear);
        Session::put('semester',$currentSem);

      


        try{

            DB::table('users')
                ->where('id',auth()->user()->id)
                ->update([
                    'loggedOut'=>'0',
                    'loggedIn'=>'1',
                    'dateLoggedIn'=>\Carbon\Carbon::now('Asia/Manila')
                ]);


        } catch (\Exception $e) {
                
            DB::table('zerrorlogs')
                ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

        }

       
        

        Session::put('currentPortal',auth()->user()->type);

        // if(auth()->user()->type!='7' || auth()->user()->type!='9'){

        //     $privelege = DB::table('faspriv')
        //                     ->join('usertype','faspriv.usertype','=','usertype.id')
        //                     ->select('faspriv.*','usertype.utype')
        //                     ->where('userid', auth()->user()->id)
        //                     ->where('faspriv.deleted','0')
        //                     ->get();

        //     Session::get('currentPortal',0);

        //     foreach($privelege as $item){

        //         if($item->usertype == '1'){

        //             Session::put('isTeacher',true);

        //         }

        //         if($item->usertype == '2' || auth()->user()->type == 2){

        //             Session::put('isPrincipal',true);

        //         }

        //     }

        // }


        $duplicates = DB::table('users')
                        ->where('email',auth()->user()->email)
                        ->where('deleted','0')
                        ->count();


        if($duplicates==1){
			self::checkUserType();
        }else{
               
            Auth::logout();

          
        }

    }
	
	 public static function checkUserType(){
		 
		if(auth()->user()->type=='2'){

                $req = DB::table('perreq')
                        ->join('perreqdetail',function($join){
                            $join->on('perreq.id','=','perreqdetail.headerid');
                        })
                        ->join('users','perreqdetail.approvedby','=','users.id')
                        ->leftJoin('users as senderinfo','perreq.createdby','=','senderinfo.id')
                        ->join('sy','perreq.reqid','=','sy.id')
                        ->select(
                            'status',
                            'users.name',
                            'senderinfo.name as sendername',
                            'perreqtype',
                            'perreq.id',
                            'perreqdetail.id as perreqdetialid',
                            'perreqdetail.response',
                            'sy.sydesc'
                        )
                        ->where('perreqdetail.approvedby',auth()->user()->id)
                        ->where('status','0')
                        ->count();

                
             
							$activesy = DB::table('sy')
											->where('isactive',1)
											->first();
											
							$teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
											
							$principalInfo = DB::table('teacheracadprog')
                                                ->join('academicprogram',function($join){
                                                      $join->on('teacheracadprog.acadprogid','=','academicprogram.id');
                                                })
												->join('teacher',function($join){
                                                      $join->on('teacheracadprog.teacherid','=','teacher.id');
                                                })
                                                ->where('teacherid',$teacherid)
                                                ->where('acadprogutype',2)
                                                ->where('syid',$activesy->id)
                                                ->select( 
														'teacher.*',
                                                      'academicprogram.progname',
                                                      'academicprogram.id as acadid'
                                                )
                                                ->where('teacheracadprog.deleted',0)
                                                ->orderBy('acadprogid')
                                                ->get();
  

                

                $prinInfo = DB::table('teacher')
                                ->join('usertype',function($join){
                                    $join->on('teacher.usertypeid','=','usertype.id');
                                    $join->where('teacher.deleted',0);
                                })
                                ->select(
                                    'teacher.id',
                                    'teacher.lastname',
                                    'teacher.firstname',
                                    'usertype.refid'
                                )
                                ->where('userid',auth()->user()->id)
                                ->where('teacher.deleted','0')
                                ->first();

                $isSeniorHighPrincipal = false;
                $isJuniorHighPrinicpal = false;
                $isPreSchoolPrinicpal = false;
                $isGradeSchoolPrinicpal = false;

                foreach($principalInfo as $item){

                    if($item->acadid=='5'){

                        $isSeniorHighPrincipal = true;
                    
                    }
                    else if($item->acadid=='2'){

                        $isPreSchoolPrinicpal = true;

                    }
                    else if($item->acadid=='3'){

                        $isGradeSchoolPrinicpal = true;

                    }
                    else if($item->acadid=='4'){

                        $isJuniorHighPrinicpal = true;

                    }
                    

                }

                Session::put('isSeniorHighPrincipal',$isSeniorHighPrincipal);
                Session::put('isPreSchoolPrinicpal', $isPreSchoolPrinicpal);
                Session::put('isGradeSchoolPrinicpal', $isGradeSchoolPrinicpal);
                Session::put('isJuniorHighPrinicpal', $isJuniorHighPrinicpal);

                Session::put('principalInfo', $principalInfo);
                Session::put('prinInfo', $prinInfo);
                Session::put('requestCount', $req);

                $teacherCount = count(SPP_Teacher::filterTeacherFaculty()[0]->data);
                
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

                $sections = Section::getSections(null,1,null,null,null, $prinInfo->id)[0]->count;
                Session::put('sectionCount', $sections);

                $blocks = SPP_Blocks::getBlock(null,6,null,null)[0]->count;
                Session::put('blockCount', $blocks);

            }
            else if(auth()->user()->type=='7'){
                $studentInfo = DB::table('studinfo')
                                          ->where('sid',str_replace("S", "", auth()->user()->email))
                                          ->where('studinfo.deleted',0)
                                          ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                                          ->select(
                                              'firstname',
                                              'lastname',
                                              'studinfo.id',
                                              'studinfo.gender',
                                              'studinfo.levelid',
                                              'studinfo.picurl',
                                              'gradelevel.acadprogid'
                                             )
                                          ->first();
                                          
                Session::put('studentInfo', $studentInfo);
            }
            else if(auth()->user()->type=='9'){
                
                $studendinfo = DB::table('studinfo')
                                          ->where('sid',str_replace("P", "", auth()->user()->email))
                                          ->where('studinfo.deleted',0)
                                          ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                                          ->select(
                                              'studinfo.id',
                                              'studinfo.gender',
                                              'studinfo.levelid',
                                              'studinfo.picurl',
                                              'gradelevel.acadprogid'
                                             )
                                          ->first();
                      
                Session::put('studentInfo', $studendinfo);
            }
            else{

                $usertype = DB::table('usertype')->where('id',auth()->user()->type)->first();

                if(isset($usertype->refid) && ( $usertype->refid == 20 || $usertype->refid == 22 )){

                    
                    $principalInfo = DB::table('teacher')
                                    ->join('teacheracadprog',function($join){
                                        $join->on('teacher.id','=','teacheracadprog.teacherid');
                                        $join->where('teacheracadprog.deleted',0);
                                    })
                                    ->where('userid',auth()->user()->id)
                                    ->select( 'teacher.*',
                                                'teacheracadprog.acadprogid as acadid'
                                                )
                                    ->get();


                    $isSeniorHighPrincipal = false;
                    $isJuniorHighPrinicpal = false;
                    $isPreSchoolPrinicpal = false;
                    $isGradeSchoolPrinicpal = false;

                    foreach($principalInfo as $item){

                        if($item->acadid=='5'){

                            $isSeniorHighPrincipal = true;
                        
                        }
                        else if($item->acadid=='2'){

                            $isPreSchoolPrinicpal = true;

                        }
                        else if($item->acadid=='3'){

                            $isGradeSchoolPrinicpal = true;

                        }
                        else if($item->acadid=='4'){

                            $isJuniorHighPrinicpal = true;

                        }
                        

                    }

                    Session::put('isSeniorHighPrincipal',$isSeniorHighPrincipal);
                    Session::put('isPreSchoolPrinicpal', $isPreSchoolPrinicpal);
                    Session::put('isGradeSchoolPrinicpal', $isGradeSchoolPrinicpal);
                    Session::put('isJuniorHighPrinicpal', $isJuniorHighPrinicpal);

                    
                    $prinInfo = DB::table('teacher')
                                ->join('usertype',function($join){
                                    $join->on('teacher.usertypeid','=','usertype.id');
                                    $join->where('teacher.deleted',0);
                                })
                                ->select(
                                    'teacher.id',
                                    'teacher.lastname',
                                    'teacher.firstname',
                                    'usertype.refid'
                                )
                                ->where('userid',auth()->user()->id)
                                ->where('teacher.deleted','0')
                                ->first();

                    Session::put('principalInfo', $principalInfo);
                    Session::put('prinInfo', $prinInfo);
                    Session::put('isAssistantPrin',true);
                   

                    $teacherCount = count(SPP_Teacher::filterTeacherFaculty()[0]->data);
                    
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

                    $sections = Section::getSections(null,1,null,null,null, $prinInfo->id)[0]->count;
                    Session::put('sectionCount', $sections);

                    $blocks = SPP_Blocks::getBlock(null,6,null,null)[0]->count;
                    Session::put('blockCount', $blocks);

                }

            }
           
        
		 
		 
	 }
	
}
