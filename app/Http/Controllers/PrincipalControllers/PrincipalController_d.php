<?php

namespace App\Http\Controllers\PrincipalControllers;

use Illuminate\Http\Request;
use App\Models\Principal\Subject;
use App\Models\Principal\Section;
use App\Models\Principal\ClassSubject;
use App\Models\Principal\ClassSchedDetail;
use App\Models\Principal\ClassSched;
use DB;
use Crypt;
use App\Models\Principal\TeacherAttendance;
use \Carbon\Carbon;
use App\Models\Principal\AttendanceReport;
use App\Models\Principal\GenerateGrade;
use Hash;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\Principal\Notification;
use App\Models\Principal\GenerateAwards;
use App\Models\Principal\LoadPersonality;
// use App\Models\Principal\SubjectAssignment;
use App\Models\Principal\Management;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_GradeSetup;
use App\Models\Principal\SPP_Gradelevel;
use App\Models\Principal\SPP_ClassSchedule;
use App\Models\Principal\SPP_Blocks;
use App\Models\Principal\SPP_Days;
use App\Models\Principal\SPP_Section;
use App\Models\Principal\SPP_Student;
use App\Models\Principal\SPP_Subject;
use App\Models\Principal\SPP_SHClassSchedule;
use App\Models\Principal\SPP_AcademicProg;
use App\Models\Principal\SPP_Teacher;
use App\Models\Principal\SPP_GradeLogs;
use App\Models\Principal\SPP_Announcement;
use App\Models\Principal\SPP_Prerequisite;
use App\Models\Principal\SPP_Notification;
use App\Models\Principal\SPP_Attendance;
use App\Models\Principal\SPP_Calendar;
use Illuminate\Validation\Rule;
use App\Models\Principal\SPP_Session;
use App\Models\Principal\SPP_StudentPromotion;
use App\Models\Principal\SPP_PermissionRequest;
use App\Models\Principal\SPP_Fixer;
use Illuminate\Support\Facades\View;

use App\Models\Grading\GradingSystem;
// use App\Models\Grading\SubjectAssignment;


use RealRashid\SweetAlert\Facades\Alert;


class PrincipalController extends \App\Http\Controllers\Controller
{


    //----------------------------------- Student Blade -----------------------------------------

    public function loadStudents()
    {

        return view('principalsportal.pages.students')->with('data',array((object)['data'=>[],'count'=>'-1']));

    }

    public function searchstudentajax(Request $request){

        $studentCount = 6;

        if($request->get('tableform')){
            $studentCount = 10;
            $isTableForm = true;

        }

        $students =  SPP_EnrolledStudent::getStudent(
            $request->get('pagenum'),
            $studentCount,
            null,
            $request->get('data'),
            Crypt::decrypt($request->get('apid')),
            null,
            null,
            $request->get('gl'),
            null,
            false,
            false,
            'all',
            $request->get('gender'),
            $request->get('grantee')
        );

        $isTableForm = false;

        if($request->get('tableform')){

            $isTableForm = true;

            return view('search.principal.student')
                            ->with('data',$students)
                            ->with('isTableForm',$isTableForm);
                         

        }else{

            return view('search.principal.student')
                        ->with('data',$students)
                        ->with('isTableForm',$isTableForm);
        }

       
                        

    }
    public function searchbygradelevel(Request $request){
   
        $acadid  = Crypt::decrypt($request->get('apid'));
        
        $aglevel = DB::table('gradelevel')
                    ->where('gradelevel.acadprogid', $acadid)
                    ->orderBy('sortid')
                    ->get();
        return $aglevel;

    }

    public function searchbygradelevelid(Request $request){
   
        $aglevelid  = $request->get('aglevel');
        
        return $aglevelid;
       

    }

    public function loadSections()
    {

        $syid = DB::table('sy')
                    ->where('isactive',1)
                    ->first()
					->id;
      
         
		$sectionDetail = DB::table('sectiondetail')
							->where('syid',$syid)
							->where('deleted',0)
							->count();

        $allsectiondetail = DB::table('sectiondetail')
                                ->where('deleted',0)
                                ->count();

        return view('principalsportal.pages.schedule')
            ->with('data',Section::getSections(null,6,null,null,null,Session::get('prinInfo')->id))
            ->with('sectiondeatailcount',$sectionDetail)
            ->with('allsectiondetail',$allsectiondetail);

    }
	
	public function dupsectdetwithoutdetail(Request $request){

        $all_sections = DB::table('sections')
                            ->where('deleted',0)
                            ->get();

        $syid = DB::table('sy')
                    ->where('isactive',1)
                    ->first()
                    ->id;

       

        foreach($all_sections as $item){

            $check = DB::table('sectiondetail')
                        ->where('syid',$syid )
                        ->where('deleted',0)
                        ->where('sectionid',$item->id)
                        ->count();

            if($check == 0){

                DB::table('sectiondetail')
                    ->insert([
                        'sectname'=>$item->sectname,
                        'sectionid'=>$item->id,
                        'syid'=>$syid ,
                        'semid'=>1,
                        'deleted'=>0,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            }

        }


        $last_sy = DB::table('sh_sectionblockassignment')
                    ->orderBy('sy','desc')
                    ->where('deleted',0)
                    ->first();

        $temp_sy = null;

        if(isset($last_sy->syid)){
            $temp_sy = $last_sy->syid;
        }

        if($temp_sy != null){

            $sectionstrand = DB::table('sh_sectionblockassignment')
                        ->where('syid',$temp_sy)
                        ->where('deleted',0)
                        ->get();

            foreach($sectionstrand as $item){

                $check = DB::table('sh_sectionblockassignment')
                            ->where('syid',$syid )
                            ->where('deleted',0)
                            ->where('sectionid',$item->sectionid)
                            ->where('blockid',$item->blockid)
                            ->count();

                if($check == 0){
                    DB::table('sh_sectionblockassignment')
                    ->insert([
                        'blockid'=>$item->blockid,
                        'sectionid'=>$item->sectionid,
                        'blockid'=>$item->blockid,
                        'syid'=>$syid ,
                        'deleted'=>0,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
                }

            }

        }
        

    }
   
    public function dupsectdetwithdetail(Request $request){
		
		
        if($request->has('sectinfo')){
			
			$syid = DB::table('sy')
                    ->where('isactive',1)
                    ->first()
                    ->id;
					
            $sectionDetail = DB::table('sectiondetail')
                                ->where('syid',$syid)
                                ->where('deleted',0)
                                ->count();
								
			

            if($sectionDetail == 0){
            
                 $sectionDetail = DB::table('sectiondetail')
                                        ->where('syid',Crypt::decrypt($request->get('sy')))
                                        ->where('deleted',0)
                                        ->get();
										
										

                foreach($sectionDetail as $item){

                    $check = DB::table('sectiondetail')
                                ->where('syid',$syid )
                                ->where('deleted',0)
                                ->where('sectionid',$item->id)
                                ->count();

                    if($check == 0){
                        DB::table('sectiondetail')
                            ->insert([
                                'sectname'=>$item->sectname,
                                'sectionid'=>$item->sectionid,
                                'blockid'=>$item->blockid,
                                'teacherid'=>$item->teacherid,
                                'syid'=>$syid ,
                                'semid'=>1
                            ]);
                    }

                }
				
				$sectionstrand = DB::table('sh_sectionblockassignment')
                                    ->where('syid',Crypt::decrypt($request->get('sy')))
                                    ->where('deleted',0)
                                    ->get();

                foreach($sectionstrand as $item){

                    $check = DB::table('sh_sectionblockassignment')
                                ->where('syid',$syid )
                                ->where('deleted',0)
                                ->where('sectionid',$item->sectionid)
                                ->where('blockid',$item->blockid)
                                ->count();

                    if($check == 0){
                        DB::table('sh_sectionblockassignment')
                            ->insert([
                                'blockid'=>$item->blockid,
                                'sectionid'=>$item->sectionid,
                                'blockid'=>$item->blockid,
                                'syid'=>$syid ,
                                'deleted'=>0,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                        

                    }

                }

                if($request->has('sectclasssched')){

                    $classsched = DB::table('classsched')->where('deleted','0')->where('syid',Crypt::decrypt($request->get('sy')))->get();
        
                    $classscheddetail = DB::table('classsched')
                                        ->where('classsched.deleted','0')
                                        ->where('syid',Crypt::decrypt($request->get('sy')))
                                        ->join('classscheddetail',function($join){
                                            $join->on('classsched.id','=','classscheddetail.headerid');
                                            $join->where('classscheddetail.deleted','0');
                                        })
                                        ->get();
        
                    $assignsubj = DB::table('assignsubj')->where('deleted','0')->where('syid',Crypt::decrypt($request->get('sy')))->get();
        
                    $assignsubjdetail = DB::table('assignsubj')
                                        ->where('assignsubj.deleted','0')
                                        ->where('syid',Crypt::decrypt($request->get('sy')))
                                        ->join('assignsubjdetail',function($join){
                                            $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                                            $join->where('assignsubjdetail.deleted','0');
                                        })
                                        ->get();
        
        
                    foreach( $classsched as $item){
        
                        $classscshedid = DB::table('classsched')->insertGetId([
                            'glevelid'=>$item->glevelid,
                            'sectionid'=>$item->sectionid,
                            'subjid'=>$item->subjid,
                            'deleted'=>$item->deleted,
                            'syid'=>$syid,
                            'createdby'=>$item->createdby,
                            'createddatetime'=>$item->createddatetime,
                            'updateddatetime'=>$item->updateddatetime,
                            'updatedby'=>$item->updatedby,
                        ]);

                        $classscheddetailToBeInserted = collect( $classscheddetail)->where('headerid',$item->id);

                        foreach( $classscheddetailToBeInserted as $item){
        
                            DB::table('classscheddetail')->insert([
                                'headerid'=>$classscshedid,
                                'days'=>$item->days,
                                'stime'=>$item->stime,
                                'etime'=>$item->etime,
                                'roomid'=>$item->roomid,
                                'deleted'=>$item->deleted,
                                'createdby'=>$item->createdby,
                                'createddatetime'=>$item->createddatetime,
                                'updateddatetime'=>$item->updateddatetime,
                                'updatedby'=>$item->updatedby,
                            ]);
            
                        }
                    }
                   
                    foreach( $assignsubj as $item){
        
                        $assignsubjid = DB::table('assignsubj')->insertGetId([
                            'glevelid'=>$item->glevelid,
                            'sectionid'=>$item->sectionid,
                            'deleted'=>$item->deleted,
                            'syid'=>$syid ,
                            'createdby'=>$item->createdby,
                            'createddatetime'=>$item->createddatetime,
                            'updateddatetime'=>$item->updateddatetime,
                            'updatedby'=>$item->updatedby,
                        ]);

                        $assignsubjdetailToBeInserted = collect($assignsubjdetail)->where('headerid',$item->ID);

                        foreach( $assignsubjdetailToBeInserted as $item){
        
                            DB::table('assignsubjdetail')->insert([
                                'headerid'=>$assignsubjid,
                                'subjid'=>$item->subjid,
                                'teacherid'=>$item->teacherid,
                                'deleted'=>$item->deleted,
                                'createdby'=>$item->createdby,
                                'createddatetime'=>$item->createddatetime,
                                'updateddatetime'=>$item->updateddatetime,
                                'updatedby'=>$item->updatedby,
                            ]);
            
                        }
        
                    }
                    
        
        
                }

                return back();
            }
            else{
                return back();
            }
        }

       

        

        
    }





    public function searchsectionajax(Request $request){

        $sections = Section::getSections($request->get('pagenum'),6,null,$request->get('data'),null,Session::get('prinInfo')->id);

        return view('search.principal.section')->with('data',$sections);   

    }

    public function managestoreSections(Request $request){

        // return $request->all();

        $validator = Section::validateSectionForm($request);

        if ($validator->fails()) {

            toast('Invalid Inputs','error')->autoClose(2000)->toToast($position = 'top-right');

            return back()->withErrors($validator)->withInput();

        }
        else{

            Section::storeSection($request);

            toast('Section successfully created','success')->autoClose(2000)->toToast($position = 'top-right');

            return back();

        }

    }

    public static function updateSectionInformation(Request $request){

        $sectionname = $request->get('sectionname');
        $section = $request->get('section');
        $levelid = $request->get('levelid');
        $teacherid = $request->get('teacherid');
        $roomid = $request->get('roomid');
        $session = $request->get('session');
        $sunday = $request->get('sunday');

        $syid = DB::table('sy')
                    ->where('isactive',1)
                    ->select('id')
                    ->first()
                    ->id;

        $check_enrollment_count = self::enrollment_count($request);

        try{
            if($check_enrollment_count > 0 ){

                DB::table('sections')
                    ->where('id',$section)
                    ->take(1)
                    ->update([
                        'teacherid'=>$teacherid,
                        'roomid'=>$roomid,
                        'session'=>$session,
                        'sundaySchool'=>$sunday,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            }else{

                $check = DB::table('sections')
                            ->where('id','!=',$section)
                            ->where('sectionname','like','%'.$sectionname.'%')
                            ->where('deleted',0)
                            ->count();
                
                if($check > 0){
                    return array((object)[
                        'status'=>2,
                        'message'=>'Section already exist!'
                    ]);
                }
                
                DB::table('sections')
                    ->where('id',$section)
                    ->take(1)
                    ->update([
                        'levelid'=>$levelid,
                        'sectionname'=>$sectionname,
                        'teacherid'=>$teacherid,
                        'roomid'=>$roomid,
                        'session'=>$session,
                        'sundaySchool'=>$sunday,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

            DB::table('sectiondetail')
                    ->where('sectionid',$section)
                    ->where('syid',$syid)
                    ->where('deleted',0)
                    ->take(1)
                    ->update([
                        'teacherid'=>$teacherid,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return array((object)[
                'status'=>1,
                'message'=>'Section updated!'
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
        

        // Section::updateSection($request);
        // toast('Section successfully updated','success')->autoClose(2000)->toToast($position = 'top-right');
        // return back();
    }

    //----------------------------------- Sections Blade -----------------------------------------

    //----------------------------------- Blocks -----------------------------------------

    public static function principalPortalBlocks(){

        return view('principalsportal.pages.management.block.blocks')
                ->with('data',SPP_Blocks::getBlock(null,6,null,null));

    }

    public static function prinicipalstoreblock(Request $request){

        return SPP_Blocks::storeblock($request);

    }

    public static function updateblocksched(Request $request){

        return SPP_Blocks::updateblocksched($request);

    }

    public static function prinicipalsearchblock(Request $request){

        $blocks = SPP_Blocks::getBlock($request->get('pagenum'),6,null,$request->get('data'));

        return view('search.principal.block')->with('data',$blocks);


    }

    public static function principalupdateblock(Request $request){

        // return $request->all();

        return SPP_Blocks::updateblock(
            $request->get('id'),
            $request->get('bn'),
            $request->get('si'),
            $request->get('gradelevel')
        );

    }

    public static function prinicipalblockinfo($blockid){

        // $subjects = SPP_Blocks::getblockSubjects($blockid);
        // $schedules = SPP_Blocks::getAllBlockSched($blockid);
        // $schedulesummary = array();

        // $firstgroup = collect($schedules)->groupby('schedclass');

        // foreach($firstgroup  as $item){

        //     $secondgroup = collect($item)->groupby('stime')->sortBy('days');
          
        //     foreach($secondgroup  as $itemsecond){

        //         $dayString = '';
        //         $data = null;

        //         foreach($itemsecond  as $itemThird){
        //             $data = $itemThird;
        //             $dayString.= substr($itemThird->description, 0,3).' / ';

        //         }

        //         array_push($schedulesummary,(object)[
        //             'daysum'=>$dayString,
        //             'subjinfo'=>$data
        //         ]);

        //     }

        // }

    
        // return  $schedulesummary;

        // return $schedulesummary[0]->daysum;

        // foreach($subjects as $item){

        //     $datetime = array();
        //     $timeholderstime = null;
        //     $timeholderetime = null;
        //     $scheddetail = null;
        //     $dayString = '';
        //     $firstCount = 0;

        //     foreach($schedules as $itemSched){

        //         if($item->id == $itemSched->subjid){

        //             $item->csid = $itemSched->id;

        //             if($timeholderstime != $itemSched->stime && $timeholderetime != $itemSched->etime){

        //                 $scheddetail = $itemSched;

        //                 if($firstCount==0){

        //                     $dayString.= substr($itemSched->description, 0,3).' / ';

        //                 }
        //                 else{

        //                     array_push($datetime,(object)array(
        //                         'day'=>$dayString,
        //                         'scheddetail'=>$scheddetail
        //                     ));

        //                     $dayString = '';
        //                     $firstCount = 0;
        //                 }
        //             }
        //             else{

        //                 $dayString.= substr($itemSched->description, 0,3).' / ';
        //             }
        //         }

        //     }

            

        //     $dayString = substr_replace($dayString, "", -3);

        //     array_push($datetime,(object)array(
        //         'daysum'=>$dayString,
        //         'scheddetail'=>$scheddetail
        //     ));

        //     array_push($schedulesummary, (object) array(
        //         'subject'=>$item,
        //         'datetime'=>$datetime ,
        //     ));

        // }

        // return collect(SPP_Blocks::getBlock(null,null,$blockid)[0]->data[0]);


        return view('principalsportal.pages.management.block.blockinfo')
                    ->with('blockinfo',SPP_Blocks::getBlock(null,null,$blockid)[0]->data[0]);
                  

    }

    public static function prinicipalblocksched(Request $request){

        return SPP_Blocks::getblocksched($request->get('bid'),$request->get('day'));

    }

    public static function prinicipalstoreblocksched(Request $request){

        // return SPP_Blocks::storeblocksched($request);

        return SPP_Blocks::storeblockschedv2($request);

    }

    
    public static function prinicipalblockinfoby(Request $request){

        return SPP_Blocks::blockinfo($request->get('blockid'));

    }


    //----------------------------------- Blocks -----------------------------------------

    
    
    //----------------------------------- Grade Setup -----------------------------------------

    public function principalgradesetup($id){

        return view('principalsportal.pages.management.gradesetup')->with('data', SPP_GradeSetup::getAllGradeSetup(null,10,null,null,Crypt::decrypt($id)))->with('apid',$id);
    
    }

    public function storegradesetup(Request $request){

        return SPP_GradeSetup::insertGradeSetup(
            $request->get('gradelevel'),
            $request->get('subject'),
            $request->get('ww'),
            $request->get('pt'),
            $request->get('qa'),
            $request->get('q')
        );

    }

    public function searchgradesetup(Request $request){

        try{
            $acadid = Crypt::decrypt($request->get('apid'));
        }
        catch (\Exception $e) {

            return back();
            
        }

        $gradesetup = SPP_GradeSetup::getAllGradeSetup($request->get('pagenum'),10,null,$request->get('data'),$acadid);

        return view('search.principal.gradesetup')->with('data',$gradesetup);

    }

    public function updategradesetup(Request $request){

        return SPP_GradeSetup::updategradesetup($request);

    }

    //----------------------------------- Grade Setup -----------------------------------------


    // ------------------------- Notifications Start

    public function viewAllNotifications(){

        // return SPP_Notification::viewNotifications(null,10,auth()->user()->id,null,null);

        return view('principalsportal.pages.notifications.viewAllNotifications')
            ->with('data',SPP_Notification::viewNotifications(null,10,auth()->user()->id,null,null));

    }

    public function filterNotifications(Request $request){


        $notifications = SPP_Notification::viewNotifications($request->get('pagenum'),10,auth()->user()->id,null,
        
        null,$request->get('data'));
    
        $notificationString = SPP_Notification::tableString($notifications[0]->data);

        $data = array();

        array_push($data,(object)['data'=>$notificationString,'count'=>$notifications[0]->count]);

        return $data;
    
    }



    // ------------------------- Notifications End

    public function updateNotGradeInfo($id){
 
        DB::table('notifications')->where('id',$id)->update(['status'=>'1']);

        $notification = DB::table('notifications')
                        ->join('gradelogs','notifications.headerid','=','gradelogs.id')
                        ->join('teacher','gradelogs.user_id','=','teacher.id')
                        ->where('notifications.id',$id)
                        ->select('teacher.id as teacherid')
                        ->get();



        return $this->loadteacherProfile( $notification[0]->teacherid);
        
    }

    


    public function loadteacherProfile($id){

        $teacherProfile = SPP_Teacher::getTeacherInfo($id,false,false,false,false,true);
        
        $teacherSchedule = SPP_Teacher::getTeacherInfo($id,true,true,true);
       
        $schedulesummary = array();

        $sectionGroup =  collect($teacherSchedule)->groupBy('sectionid');

        foreach($sectionGroup  as $sectionGroupitem){

            $firstgroup =  collect($sectionGroupitem)->groupBy('classification');

            foreach($firstgroup  as $item){

                $subjGroup = collect($item)->groupBy('subjcode');

                foreach($subjGroup  as $subjGroupitem){

                    $secondgroup = collect($subjGroupitem)->groupby('time')->sortBy('days');

                    foreach($secondgroup  as $itemsecond){

                        $dayString = '';
                        $data = null;

                        foreach($itemsecond  as $itemThird){
                            $data = $itemThird;
                            $dayString.= substr($itemThird->description, 0,3).' / ';

                        }

                        $dayString = substr($dayString, 0 , -2);

                        array_push($schedulesummary,(object)[
                            'daysum'=>$dayString,
                            'subject'=>$itemThird->subjcode,
                            'subjid'=>$itemThird->subjid,
                            'subjinfo'=>$data,
                            
                            
                        ]);

                    }
                }

            }

        }

        $advisoryClass = DB::table('sectiondetail')
                            ->join('sections',function($join){
                                $join->on('sectiondetail.sectionid','=','sections.id');
                                $join->where('sections.deleted',0);
                            })
                            ->join('sy',function($join){
                                $join->on('sectiondetail.syid','=','sy.id');
                                $join->where('sy.isactive',1);
                            })
                            ->where('sectiondetail.deleted',0)
                            ->where('sectiondetail.teacherid',$id)
                            ->select('sections.sectionname')
                            ->get();
        
        $teacherSubjects = SPP_Teacher::getTeacherInfo($id,true,true);

        $teacherSubmittedGrades = SPP_Teacher::getTeacherInfo($id,true,true,false,true);

        try {

            $gradelogs = SPP_GradeLogs::getGradeLogs(null,null,null,null, $teacherProfile[0]->userid);
        } 
        catch (\Exception $e) {
          
        }

        $data =  AttendanceReport::teacherAttendanceReport($id);

        $day = SPP_Days::loadDays();

        return view('principalsportal.pages.teachersProfile')
                ->with('monthlyReports',$data[0]->monthly)
                ->with('dailyReport',$data[0]->daily)
                ->with('yearlyReport',$data[0]->yearly)
                ->with('submittedGrades',$teacherSubmittedGrades)
                ->with('teachersSubjects',$teacherSubjects)
                ->with('teacherInfo',$teacherProfile)
                ->with('teaacherClassSched', $schedulesummary)
                ->with('gradelogs',$gradelogs)
                ->with('advisoryClass',$advisoryClass)
                ->with('days',$day);
    }

    public function loadStudentProfile($id,$acadprog){


        //student information
        $studentInfo = SPP_EnrolledStudent::getStudent(null,null,Crypt::decrypt($id),null,Crypt::decrypt($acadprog),null,null,null,Session::get('schoolYear')->id);
        $strand = $studentInfo[0]->data[0]->strandid;
        $acad = $studentInfo[0]->data[0]->acadprogid;

        //grades
        $grades = GenerateGrade::reportCardV3($studentInfo[0]->data[0],false,'sf9');
        $checkGradingVersion = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
        if($checkGradingVersion->version == 'v1'){
            $grades = GenerateGrade::reportCardV4($studentInfo[0]->data[0], true, 'sf9');
        }
        if($checkGradingVersion->version == 'v2'){
            $grades = GenerateGrade::reportCardV5($studentInfo[0]->data[0], true, 'sf9');
        }

        if(  $acad == 5){
            foreach($grades as $key=>$item){
                $checkStrand = DB::table('sh_subjstrand')
                                    ->where('subjid',$item->subjid)
                                    ->where('strandid', $strand)
                                    ->where('deleted',0)
                                    ->count();
                if($checkStrand == 0){
                    unset($grades[$key]);
                }
            }
        }

        $finalgrade =  \App\Models\Grades\GradesData::general_average($grades);
        $grades =  \App\Models\Grades\GradesData::get_finalrating($grades,$acad);
        $finalgrade =  \App\Models\Grades\GradesData::get_finalrating($finalgrade,$acad);

        //Attendance
        $schoolyear = Db::table('sy')->where('isactive','1')->first();
        $attendance_setup = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($schoolyear->id);
        foreach( $attendance_setup as $item){
            $month_count = \App\Models\Attendance\AttendanceData::monthly_attendance_count($schoolyear->id,$item->month,Crypt::decrypt($id));
            $item->present = $month_count;
        }

        $daily_attendance = array();

        foreach( $attendance_setup as $item){
            $daily = \App\Models\Attendance\AttendanceData::daily_attendance_count($schoolyear->id,$item->month,Crypt::decrypt($id));
            foreach($daily as $daily_item){ 
                $daily_item->formated = \Carbon\Carbon::create($daily_item->tdate)->isoFormat('MMM DD, YYYY');
                if($daily_item->present == 1){ $daily_item->status = 'PRESENT'; }
                else if($daily_item->absent == 1){ $daily_item->status = 'ABSENT'; }
                else if($daily_item->cc == 1){ $daily_item->status = 'CUTTING CLASS'; }
                else if($daily_item->tardy == 1){ $daily_item->status = 'TARDY'; }
                array_push($daily_attendance, $daily_item); 
            }
        }
        $daily_attendance = $daily_attendance;

    

        if(  $acad == 5){
            foreach($grades as $key=>$item){
                $checkStrand = DB::table('sh_subjstrand')
                                    ->where('subjid',$item->subjid)
                                    ->where('deleted',0)
                                    ->get();
                if( count($checkStrand) > 0 ){
                    $check_same_strand = collect($checkStrand)->where('strandid',$strand)->count();
                    if( $check_same_strand == 0){
                        unset($grades[$key]);
                    }
                }
            }
        }

        // return $daily_attendance;

        return view('principalsportal.pages.studentsProfile')
                ->with('studentInfo',$studentInfo[0]->data)
                ->with('grades',$grades)
                ->with('finalgrade',$finalgrade)
                ->with('daily_attendance',$daily_attendance)
                ->with('attendance_setup',$attendance_setup);

    }



    public static function loadGradeInfo($id, $schoolyear = null){

        try {

            $gradesInfo = DB::table('grades')
                        ->leftJoin('sections',function($join){
                            $join->on('grades.sectionid','=','sections.id');
                            $join->where('sections.deleted','0');
                        })
                        ->leftJoin('gradelevel',function($join){
                            $join->on('grades.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->leftJoin('subjects',function($join){
                            $join->on('grades.subjid','=','subjects.id');
                            $join->where('subjects.deleted','0');
                        })
                        ->leftJoin('assignsubj',function($join) use($schoolyear){
                            $join->on('grades.sectionid','=','assignsubj.sectionid');
                            $join->on('grades.levelid','=','assignsubj.glevelid');
                            if($schoolyear == null){
                                $join->whereIn('assignsubj.syid',function($query){
                                    $query->select('id')->from('sy')->where('sy.isactive','1');
                                });
                            }
                            else{
                                $join->whereIn('assignsubj.syid',function($query) use($schoolyear){
                                    $query->select('id')->from('sy')->where('id',$schoolyear);
                                });
                            }
                            $join->where('assignsubj.deleted','0');
                        })
                        ->leftJoin('assignsubjdetail',function($join){
                            $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                            $join->on('assignsubjdetail.subjid','=','grades.subjid');
                        })
                        ->leftJoin('teacher',function($join){
                            $join->on('sections.teacherid','=','teacher.id');
                        })
                        ->where('grades.id',$id)
                        ->select(
                            'status',
                            'grades.id as id',
                            'grades.quarter',
                            'grades.date_submitted',
                            'grades.createddatetime',
                            'grades.levelid',
                            'grades.subjid',
                            'sections.sectionname',
                            'gradelevel.levelname',
                            'gradelevel.id as levelid',
                            'subjects.subjdesc',
                            'teacher.firstname',
                            'teacher.lastname',
                            'gradelevel.acadprogid',
                            'teacher.userid',
                            'teacher.id as tid',
                            'grades.sectionid',
                            'grades.syid'
                            )
                        ->get();

            // $setup = DB::table('gradessetup')
            //         ->where('subjid',$gradesInfo[0]->subjid)
            //         ->where('levelid',$gradesInfo[0]->levelid)
            //         ->get();
     
        } catch (\Exception $e) {
            
            $gradesInfo =  DB::table('grades')
                            ->leftJoin('sections',function($join){
                                $join->on('grades.sectionid','=','sections.id');
                                $join->where('sections.deleted','0');
                            })
                            ->leftJoin('gradelevel',function($join){
                                $join->on('grades.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted','0');
                            })
                            ->leftJoin('sh_subjects',function($join){
                                $join->on('grades.subjid','=','sh_subjects.id');
                                $join->where('sh_subjects.deleted','0');
                            })
                            ->leftJoin('teacher',function($join){
                                $join->on('sections.teacherid','=','teacher.id');
                            })
                            ->where('grades.id',$id)
                            ->select(
                                'status',
                                'grades.id as id',
                                'grades.quarter',
                                'grades.date_submitted',
                                'grades.createddatetime',
                                'grades.levelid',
                                'grades.subjid',
                                'sections.sectionname',
                                'gradelevel.levelname',
                                'gradelevel.id as levelid',
                                'gradelevel.acadprogid',
                                'sh_subjects.subjtitle as subjdesc',
                                'teacher.firstname',
                                'teacher.lastname',
                                'teacher.userid',
                                'teacher.id as tid',
                                'grades.sectionid',
                                'grades.syid'
                                )
                            ->get();


        }

        if(count($gradesInfo) == 0){

            return view('pincipalsportal.pages.error500');
            
        }

        $mode_of_learning = '';
        $is_modular = false;

        $finalGrades = DB::table('grades')
                            ->where('grades.id',$gradesInfo[0]->id)
                            ->join('gradesdetail',function($join){
                                $join->on('grades.id','=','gradesdetail.headerid');
                            })
                            ->join('studinfo',function($join) {
                                $join->on('gradesdetail.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->select('gradesdetail.*','grades.*','studinfo.gender')
                            ->orderby('gender','desc')
                            ->orderby('lastname')
                            ->get();


        foreach($finalGrades as $item){

            if($mode_of_learning == ''){

                $mode = DB::table('studinfo')
                                ->where('studinfo.id',$item->studid)
                                ->leftJoin('modeoflearning',function($join){
                                    $join->on('studinfo.mol','=','modeoflearning.id');
                                    $join->where('modeoflearning.deleted',0);
                                })
                                ->select('description')
                                ->first();

                if($mode->description != null){

                    if(strpos($mode->description,'MODULAR') !== false){

                        $is_modular = true;

                    }

                    $mode_of_learning = $mode->description;

                }

            }

        }


        $ubject_grading = DB::table('grading_system_subjassignment')
                                ->join('grading_system',function($join) use($gradesInfo){
                                    $join->on('grading_system_subjassignment.gsid','=','grading_system.id');
                                    $join->where('grading_system_subjassignment.deleted',0);
                                    $join->where('grading_system.acadprogid',$gradesInfo[0]->acadprogid);
                                })
                                ->join('grading_system_detail',function($join){
                                    $join->on('grading_system.id','=','grading_system_detail.headerid');
                                    $join->where('grading_system_detail.deleted',0);
                                    
                                })
                                ->where('grading_system.description','like','%'.$mode_of_learning.'%')
                                ->where('subjid',$gradesInfo[0]->subjid)
                                ->where('grading_system_subjassignment.deleted',0)
                                ->get();

        if(count($ubject_grading) > 0){


            $setup =  array((object)[
                                    'levelid'=>$gradesInfo[0]->levelid,
                                    'writtenworks'=>collect( $ubject_grading )->where('sf9val',1)->first()->value,
                                    'performancetask'=>collect( $ubject_grading )->where('sf9val',2)->first()->value,
                                    'qassesment'=>collect( $ubject_grading )->where('sf9val',3)->first()->value,
                                ]);

        }
        else{

            $setup = array(DB::table('gradessetup')
                            ->join('sy',function($join){
                                $join->on('gradessetup.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->where('subjid',$gradesInfo[0]->subjid)
                            ->where('levelid',$gradesInfo[0]->levelid)
                            ->first());
        }


        if(count($finalGrades) == 0){

            return view('pincipalsportal.pages.error500');
            
        }

        $gradeLogs = DB::table('gradelogs')
                        ->join('teacher',function($join){
                            $join->on('gradelogs.user_id','=','teacher.userid');
                        })
                        ->where('gradeid',$id)
                        ->select(
                            'teacher.userid',
                            'teacher.id as tid',
                            'teacher.usertypeid',
                            'teacher.firstname','teacher.lastname',
                            'gradelogs.createddatetime',
                            'gradelogs.action'
                            )
                        ->orderBy('createddatetime','desc')
                        ->distinct()
                        ->get();

        return view('principalsportal.pages.gradeInformation')
                    ->with('gradeInfo',$gradesInfo[0])
                    ->with('is_modular',$is_modular)
                    ->with('submittedGrades',$finalGrades)
                    ->with('gradeLogs',$gradeLogs)
                    ->with('setup',$setup);

    }

    public static function activeSy(){
            return DB::table('sy')->where('isactive',1)->first();
    }
    public static function activeSem(){
            return DB::table('semester')->where('isactive',1)->first();
    }
    

    public static function grades_status(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('section');
        $acadprogid = $request->get('acadprogid');
        $levelid = $request->get('levelid');
        $strandid = $request->get('strandid');

        $sectioninfo = DB::table('sectiondetail')
                        ->where('syid',$syid)
                        ->where('sectionid',$sectionid)
                        ->where('deleted',0)
                        ->first();

        $issp = false;

        if($sectioninfo->sd_issp == 1){
            $issp = true;
        }

        $subjects = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null, null, $levelid, null, $syid, $semid , $strandid,array(),$issp);
        $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();

        foreach($subjects as $item){

            if($grading_version->version == 'v2'){
                if($acadprogid == 5){

                    $checkStatus = DB::table('grading_sytem_gradestatus')
                                    ->where('sectionid',$sectionid)
                                    ->where('levelid',$levelid)
                                    ->where('subjid',$item->id)
                                    ->where('syid',self::activeSy()->id)
                                    ->where('semid',self::activeSem()->id)
                                    ->select('q1status','q2status','q3status','q4status')
                                    ->first();

                }else{

                    $checkStatus = DB::table('grading_sytem_gradestatus')
                                    ->where('sectionid',$sectionid)
                                    ->where('levelid',$levelid)
                                    ->where('subjid',$item->id)
                                    ->where('syid',self::activeSy()->id)
                                    ->select('q1status','q2status','q3status','q4status')
                                    ->first();


                }

                $temp_gradesstatus = array();
                $quarter = 1;
                for($x = 1; $x <= 4; $x++){
                    $string = 'q'.$x.'status';
                    $status = 0;
                    if($checkStatus->$string == 2){
                        $status = 2;
                    }
                    else if($checkStatus->$string == 4){
                        $status = 3;
                    }
                    else if($checkStatus->$string == 1){
                        $status = 1;
                    }
                    else if($checkStatus->$string == 3){
                        $status = 4;
                    }
                    array_push($temp_gradesstatus,(object)[
                        'quarter'=>$x,
                        'status'=>$status,
                        'gradeid'=>'#',
                        'submitted'=>1
                    ]);
                }

                $item->gradestatus = $temp_gradesstatus;
                
            }else{
                if(isset($item->id)){
                    $gradestatus = DB::table('grades')
                        ->where('grades.deleted','0')
                        ->where('sectionid',$sectionid)
                        ->where('subjid',$item->subjid)
                        ->where('syid',$syid);
                    if($acadprogid == 5){
                        $gradestatus->where('semid',$semid);
                    }
                    $gradestatus->select(
                                    'grades.quarter',
                                    'grades.status',
                                    'grades.id as gradeid',
                                    'grades.submitted'
                                );
                    $item->gradestatus = $gradestatus->get();
    
                }
            }
          
        }
        return view('principalsportal.pages.section.gradestatustable')->with('classassignsubj',$subjects);
       
    }

    public function loadSectionProfile($id){

            try{
				$id = Crypt::decrypt($id);
			}catch(\Exception $e){}
            $syid = DB::table('sy')
                        ->where('isactive',1)
                        ->first()
                        ->id;

            $sectioninfo = DB::table('sections')
                                ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted','0');
                                })
                                ->leftJoin('sectiondetail',function($join) use($syid){
                                    $join->on('sections.id','=','sectiondetail.sectionid');
                                    $join->where('sectiondetail.deleted','0');
                                    $join->where('sectiondetail.syid',$syid);
                                })
                                ->leftJoin('teacher',function($join){
                                    $join->on('sectiondetail.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted','0');
                                })
                                ->leftJoin('rooms',function($join){
                                    $join->on('sections.roomid','=','rooms.id');
                                    $join->where('rooms.deleted','0');
                                })
                                ->where('sections.id',$id)
                                ->select(
                                    'sections.*',
                                    'gradelevel.levelname',
                                    'acadprogid',
                                    'tid',
                                    'sectiondetail.teacherid',
                                    'firstname',
                                    'lastname',
                                    'roomname',
                                    'sectionname as sn',
									'sd_issp',
                                    'sd_ismwsp',
                                    'sd_session as session'
                                )
                                ->first();

            return view('principalsportal.pages.section.sectioninfo')
                    ->with('sectionInfo',$sectioninfo);

    }

    public static function enrollment_count(Request $request){

        $sectionid = $request->get('section');
        $levelid = $request->get('levelid');

        if($levelid == 14 || $levelid == 15){
            $count = Db::table('sh_enrolledstud')
                        ->where('sectionid',$sectionid)
                        ->where('deleted',0)
                        ->count();
        }else{
            $count = Db::table('enrolledstud')
                        ->where('sectionid',$sectionid)
                        ->where('deleted',0)
                        ->count();
        }

        return $count;

    }


    public static function section_adviser(Request $request){
        $syid = $request->get('syid');
        $sectionid = $request->get('section');

        $teacher = DB::table('sectiondetail')
                    ->join('teacher',function($join){
                        $join->on('sectiondetail.teacherid','=','teacher.id');
                        $join->where('teacher.deleted',0);
                    })
                    ->where('sectiondetail.deleted',0)
                    ->where('syid',$syid)
                    ->where('sectionid',$sectionid)
                    ->select(
                        'lastname',
                        'firstname',
                        'middlename',
                        'tid'
                    )
                    ->get();

        return $teacher;

    }

    public static function block_assignment(Request $request){

        $sectionid = $request->get('section');
        $syid = $request->get('syid');

        $blockassignment = DB::table('sh_sectionblockassignment')
                                    ->join('sh_block',function($join){
                                        $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                        $join->where('sh_block.deleted','0');
                                    })
                                    ->leftJoin('sh_strand',function($join){
                                        $join->on('sh_block.strandid','=','sh_strand.id');
                                        $join->where('sh_strand.deleted',0);
                                    })
                                    ->where('sh_sectionblockassignment.sectionid',$sectionid)
                                    ->where('sh_sectionblockassignment.deleted','0')
                                    ->where('sh_sectionblockassignment.syid',$syid)
                                    ->select(
                                        'strandname',
                                        'strandcode',
                                        'sh_block.*',
                                        'sh_sectionblockassignment.blockid'
                                    )
                                    ->get();

        return $blockassignment;

    }

    public static function enrolled_students(Request $request){


            $strandid = $request->get('strand');
            $sectionid = $request->get('section');
            $acadprog = $request->get('acad');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $studid = $request->get('studid');
            $gradelevel = $request->get('gradelevel');

            $enrolled = [];

            if($acadprog == 4 || $acadprog == 3 || $acadprog == 2){

                $enrolled = DB::table('enrolledstud')
                                    ->where('enrolledstud.deleted',0)
                                    ->join('studinfo',function($join){
                                          $join->on('enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('studentstatus',function($join){
                                        $join->on('enrolledstud.studstatus','=','studentstatus.id');
                                    })
                                    ->join('gradelevel',function($join){
                                          $join->on('enrolledstud.levelid','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    });
                                

                if($syid != null){
                    $enrolled = $enrolled->where('enrolledstud.syid',$syid);
                }
                if($gradelevel != null){
                    $enrolled = $enrolled->where('enrolledstud.levelid',$gradelevel);
                }
                if($sectionid != null){
                    $enrolled = $enrolled->where('enrolledstud.sectionid',$sectionid);
                }
                if($studid != null){
                    $enrolled = $enrolled->where('enrolledstud.studid',$studid);
                }

                $enrolled = $enrolled
                            ->orderBy('studentname','asc')
                            ->select(
                                'lastname',
                                'firstname',
                                'middlename',
                                'suffix',
                                'gradelevel.levelname',
                                'sid',
                                'enrolledstud.id',
                                'studid',
                                'enrolledstud.levelid',
                                'enrolledstud.sectionid',
                                'enrolledstud.studstatus',
                                'description',
                                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                            )
                            ->get();

            }else if($acadprog == 5){

                  $enrolled = DB::table('sh_enrolledstud')
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->join('studinfo',function($join){
                                          $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('gradelevel',function($join){
                                          $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->join('studentstatus',function($join){
                                        $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                                    })
                                    ->join('sh_strand',function($join){
                                        $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                        $join->where('sh_strand.deleted',0);
                                    });
                               
                    if($syid != null){
                            $enrolled = $enrolled->where('sh_enrolledstud.syid',$syid);
                    }
                    if($semid != null){
                            $enrolled = $enrolled->where('sh_enrolledstud.semid',$semid);
                    }
                    if($studid != null){
                            $enrolled = $enrolled->where('sh_enrolledstud.studid',$studid);
                    }
                    if($sectionid != null){
                        $enrolled = $enrolled->where('sh_enrolledstud.sectionid',$sectionid);
                    }
                    if($strandid != null){
                        $enrolled = $enrolled->where('sh_enrolledstud.strandid',$strandid);
                    }
                    if($gradelevel != null){
                            $enrolled = $enrolled->where('sh_enrolledstud.levelid',$gradelevel);
                    }

                    $enrolled = $enrolled
                                    ->orderBy('studentname','asc')
                                    ->select(
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'gradelevel.levelname',
                                          'sid',
                                          'sh_enrolledstud.id',
                                          'studid',
                                          'sh_enrolledstud.levelid',
                                          'sh_enrolledstud.sectionid',
                                          'sh_enrolledstud.strandid',
                                          'strandname',
                                          'strandcode',
                                          'sh_enrolledstud.studstatus',
                                          'description',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                     )
                                    ->get();
               
            }
            

         
            foreach($enrolled as $item){
                  
                  $item->actiontaken = null;

                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                      foreach ($middlename as $middlename_item) {
                          if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                          } 
                      }
                  }
                  $item->student=$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;

                  $item->checked = 0;

            }
            
            return $enrolled;


    }


    public function remove_block(Request $request){

        $syid = $request->get('syid');
        $blockid = $request->get('b');
        $sectionid = $request->get('section');
        try{
            DB::table('sh_sectionblockassignment')
                ->where('sectionid',$sectionid)
                ->where('blockid',$blockid)
                ->where('syid',$syid)
                ->where('deleted',0)
                ->take(1)
                ->update([
                    'deleted'=>1,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                    'status'=>1,
                    'data'=>'Deleted Successfully!'
            ]);

        }catch(\Exception $e){
            return $e;
            return self::store_error($e);
        }

    }

    public static function store_error($e){
        DB::table('zerrorlogs')
        ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
        return array((object)[
              'status'=>0,
              'data'=>'Something went wrong!'
        ]);
    }




    public static function removeshsched($id){

        $schdetail = DB::table('sh_classscheddetail')->where('id',Crypt::decrypt($id))->get();

        DB::table('sh_classscheddetail')
                ->where('headerid',$schdetail[0]->headerid)
                ->where('stime',$schdetail[0]->stime)
                ->where('classification',$schdetail[0]->classification)
                ->where('etime',$schdetail[0]->etime)
                ->update(['deleted'=>'1']);

        $count = DB::table('sh_classscheddetail')
                    ->where('headerid',$schdetail[0]->headerid)
                    ->where('sh_classscheddetail.deleted','0')
                    ->count();

        if($count==0){

            DB::table('sh_classsched')
                    ->where('id',$schdetail[0]->headerid)
                    ->update(['deleted'=>'1']);
        
        }


        return back();

    }
    public static function removesched($id){

        $schdetail = DB::table('classscheddetail')->where('id',Crypt::decrypt($id))->get();

        DB::table('classscheddetail')
                    ->where('headerid',$schdetail[0]->headerid)
                    ->where('stime',$schdetail[0]->stime)
                    ->where('etime',$schdetail[0]->etime)
                    ->where('classification',$schdetail[0]->classification)
                    ->update(['deleted'=>'1']);

        $count = DB::table('classscheddetail')
                    ->where('headerid',$schdetail[0]->headerid)
                    ->where('classscheddetail.deleted','0')
                    ->count();

        if($count==0){

            $sched = DB::table('classsched')
                            ->where('id',$schdetail[0]->headerid)
                            ->get();

            DB::table('classsched')
                    ->where('id',$sched[0]->id)
                    ->update(['deleted'=>'1']);

            $subject = $sched[0]->subjid;

            DB::table('assignsubj')
                ->where('sectionid',$sched[0]->sectionid)
                ->join('sy',function($join){
                    $join->on('assignsubj.syid','=','sy.id');
                    $join->where('isactive','1');
                })
                ->join('assignsubjdetail',function($join) use($subject){
                    $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                    $join->where('assignsubjdetail.subjid',$subject);
                })
                ->update(['assignsubjdetail.deleted'=>'1']);

            $asssubj = DB::table('assignsubj')
                    ->where('sectionid',$sched[0]->sectionid)
                    ->join('sy',function($join){
                        $join->on('assignsubj.syid','=','sy.id');
                        $join->where('isactive','1');
                    })
                    ->join('assignsubjdetail',function($join) {
                        $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                        $join->where('assignsubjdetail.deleted','0');
                    })->count();

            if($asssubj == 0){

                DB::table('assignsubj')
                    ->where('sectionid',$sched[0]->sectionid)
                    ->join('sy',function($join){
                        $join->on('assignsubj.syid','=','sy.id');
                        $join->where('isactive','1');
                    })
                    ->update(['assignsubj.deleted'=>'1']);

            }

                
        
        }


        return back();
        
    }

    public function loadDashboard(){

        $teachers = DB::table('teacher')->get();

        $todate = Carbon::now();
        $countPresentTeachers = 0;
        $countAbsentTeachers = 0;
        $countLateTeacher = 0;
        $countOnTimeTeachers = 0;

        $data = array();

        $schoolcalendar = DB::table('schoolcalendar')
                        ->select('title','start','end')
                        ->where('createdby',auth()->user()->id)
                        ->get();

        foreach($teachers as $teacher){
            $date = $teacher->id;

            $teachersAttendances = DB::table('teacherattendance')
                                    ->where('teacher_id',$teacher->id)
                                    ->where('tdate',Carbon::now()->isoFormat('Y-MM   -DD'))
                                    ->take(1)->get();

            if(count($teachersAttendances)>0){
                $time = new Carbon($teachersAttendances[0]->in_am);
                if($time->format('H:i')<="07:30"){
                    $countOnTimeTeachers += 1;
                    array_push($data,array(
                        "teacher"=>$teacher->firstname,
                        "attendance"=>count($teachersAttendances),
                        "time"=>$time->format('H:i'),
                    ));
                }
                else{
                    $countLateTeacher += 1;
                }

                $countPresentTeachers+=1;
            }
            else{
                array_push($data,array(
                    "teacher"=>$teacher->firstname,
                    "attendance"=>count($teachersAttendances),
                    "time"=>"00:00",
                ));
                $countAbsentTeachers += 1;
                $countLateTeacher += 1;
            }
        }


        $presentPercentage = round(( $countPresentTeachers / count($teachers) ) * 100 );
        $absentPercentage = round(( $countAbsentTeachers / count($teachers) ) * 100) ;

        return view('principalsportal.pages.home')
                ->with('schoolcalendar',$schoolcalendar)
                ->with('present',$presentPercentage)
                ->with('absent',$absentPercentage)
                ->with('late', round(( $countLateTeacher / count($teachers) ) * 100))
                ->with('ontime', round(( $countOnTimeTeachers / count($teachers) ) * 100));
    }

    public function approveGrade($id,$userid = null){
      
        DB::table('grades')
                ->where('id',$id)
                ->update(['status'=>'2']);

        //grade status
        DB::table('gradesdetail')
            ->where('headerid',$id)
            ->update([
                'gdstatus'=>2,
                'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
        //grade status

        $datetime = Carbon::now('Asia/Manila');

        DB::table('gradelogs')
                    ->updateOrInsert(
                        ['user_id' => auth()->user()->id,'gradeid'=>$id,'action'=>'2'],
                        ['createddatetime'=>$datetime]
                    );



        $logid =  DB::table('gradelogs')
                        ->where('user_id', auth()->user()->id)
                        ->where('gradeid',$id)
                        ->where('action',2)
                        ->select('id')
                        ->first();

        SPP_Notification::insertNotifications($logid->id,$userid,3);

        toast('Successful!','success')->autoClose(2000)->toToast($position = 'top-right');
        
        return back();
   
    }

    public function pendingGrade($id,$userid){

        DB::table('grades')
                ->where('id',$id)
                ->update(['status'=>'3','submitted'=>'0']);
                
        try{
            //grade status
            DB::table('gradesdetail')
            ->where('headerid',$id)
            ->update([
                'gdstatus'=>3,
                'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
            //grade status
        }catch(\Exception $e){}
      

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $datetime = Carbon::now('Asia/Manila');

        $logid = DB::table('gradelogs')->insertGetId(
                        ['user_id' => auth()->user()->id,
                        'gradeid'=>$id,'action'=>'3',
                        'createddatetime'=>$datetime,
                        'createdby'=>auth()->user()->id
                        ]
                    );
      
        SPP_Notification::insertNotifications($logid,$userid,3);

        toast('Successful!','success')->autoClose(2000)->toToast($position = 'top-right');
        return back();

    }

    public function postGrade($id,$userid, Request $request){

        $gradeInfo = DB::table('grades')
                        ->where('grades.id',$id)
                        ->join('gradelevel',function($join){
                            $join->on('grades.levelid','=','gradelevel.id');
                        })
                        ->select('grades.sectionid','gradelevel.acadprogid','grades.subjid','grades.quarter','grades.syid','grades.semid')
                        ->first();

       
        $students = SPP_EnrolledStudent::getStudent(null,null,null,null,$gradeInfo->acadprogid,$gradeInfo->sectionid);

        DB::table('grades')
            ->where('id',$id)
            ->update(['status'=>'4']);

        //grade status
        DB::table('gradesdetail')
        ->where('headerid',$id)
        ->update([
            'gdstatus'=>4,
            'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
        ]);
        //grade status

        $datetime = Carbon::now('Asia/Manila');

        DB::table('gradelogs')
                    ->updateOrInsert(
                        ['user_id' => auth()->user()->id,'gradeid'=>$id,'action'=>'4'],
                        [
                            'createddatetime'=>$datetime,
                            'createdby'=>auth()->user()->id
                        ]
                    );

        $logid =  DB::table('gradelogs')
                    ->where('user_id', auth()->user()->id)
                    ->where('gradeid',$id)
                    ->where('action',4)
                    ->select('id')
                    ->first();

        SPP_Notification::insertNotifications($logid->id,$userid,3);
       

        foreach($request->get('qg') as $item){

            $quarter = '';

            if($gradeInfo->quarter == 1){
                $quarter = 'q1';
            }
            else if($gradeInfo->quarter == 2){
                $quarter = 'q2';
            }
            else if($gradeInfo->quarter == 3){
                $quarter = 'q3';
            }
            else if($gradeInfo->quarter == 4){
                $quarter = 'q4';
            }

            DB::table('tempgradesum')
                ->updateOrInsert (
                    [
                        'studid'=>$item['id'], 
                        'subjid'=>$gradeInfo->subjid,
                        'syid'=>$gradeInfo->syid,
                        'semid'=>$gradeInfo->semid
                    ],
                    [
                        $quarter=>$item['qg'],
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>Carbon::now('Asia/Manila')
                    ]
                );

        }

   

        foreach($students[0]->data as $student){

            $parent = DB::table('users')
                        ->where('email',"P".$student->sid)
                        ->first();

            SPP_Notification::insertNotifications($id,$student->userid,2);

            if(isset($parent->id)){

                SPP_Notification::insertNotifications($id,$parent->id,2);
                
            }
                
        }

        toast('Successful!','success')->autoClose(2000)->toToast($position = 'top-right');
        return back();
   

    }

    public function loadSF6(){

        $acadid = collect(Session::get('principalInfo'))->map(function($value){
            return $value->acadid;
        });

    

        $data = array();

        // foreach($acadid as $acaditem){

        //     $student = SPP_EnrolledStudent::getStudent(
        //         null,
        //         null,
        //         null,
        //         null,
        //         $acaditem,
        //         null,
        //         null,
        //         null,
        //         null,
        //         true,
        //         false,
        //         'sf6'
        //     );
            
            


        //     $gradelevel = SPP_Gradelevel::getGradeLevel(null,null,null,null,Crypt::encrypt($acaditem));

        //     foreach($gradelevel[0]->data as $levelitem){

        //         $malepromtstud = 0;

        //         $fempromtstud = 0;

        //         $maleretstud =0;

        //         $femretstud = 0;

        //         $maleconstud = 0;

        //         $femconstud = 0;

        //         $devmale = 0;
        //         $begmale = 0;
        //         $approfmale = 0;
        //         $profmale = 0;
        //         $addmale = 0;

        //         $devfemale = 0;
        //         $begfemale = 0;
        //         $approffemale = 0;
        //         $proffemale = 0;
        //         $addfemale = 0;


        //         if(collect($student)->where('enlevelid',$levelitem->id)->count() != 0){

        //             $studidmale = collect($student)->where('enlevelid',$levelitem->id)->where('gender','MALE')->map(function($value){
        //                 return $value->id;
        //             });

        //             $studidfemale = collect($student)->where('enlevelid',$levelitem->id)->where('gender','FEMALE')->map(function($value){
        //                 return $value->id;
        //             });

        //             $gradesmale = collect(
                        
        //                     DB::table('tempgradesum')
        //                         ->whereIn('studid',$studidmale)
        //                         ->join('sy',function($join){
        //                             $join->on('tempgradesum.syid','=','sy.id');
        //                             $join->where('sy.id',Session::get('schoolYear')->id);
        //                         })
        //                         ->join('semester',function($join){
        //                             $join->on('tempgradesum.semid','=','semester.id');
        //                             $join->where('semester.id',Session::get('semester')->id);
        //                         })
        //                         ->select()
        //                         ->get()
                            
        //                     )
        //                     ->groupBy('studid')
        //                     ->map(function($value){

        //                         $q1 = collect($value)->avg('q1');
        //                         $q2 = collect($value)->avg('q2');
        //                         $q3 = collect($value)->avg('q3');
        //                         $q4 = collect($value)->avg('q4');
        //                         return ['ave' => round( ( $q1 + $q2 + $q3 + $q4 ) / 4 ) ];
        //                     });

        //             $gradesfemale = collect(DB::table('tempgradesum')
        //                             ->whereIn('studid',$studidfemale)
        //                             ->join('sy',function($join){
        //                                 $join->on('tempgradesum.syid','=','sy.id');
        //                                 $join->where('sy.id',Session::get('schoolYear')->id);
        //                             })
        //                             ->select()
        //                             ->get())
        //                             ->groupBy('studid')
        //                             ->map(function($value){

        //                                 $q1 = collect($value)->avg('q1');
        //                                 $q2 = collect($value)->avg('q2');
        //                                 $q3 = collect($value)->avg('q3');
        //                                 $q4 = collect($value)->avg('q4');
        //                                 return ['ave' => round( ( $q1 + $q2 + $q3 + $q4 ) / 4 ) ];
        //                             });
                    
        //             $begmale = $gradesmale->where('ave','<=','74')->count();
        //             $devmale = $gradesmale->where('ave','>=','75')->where('ave','<=','79')->count();
        //             $approfmale = $gradesmale->where('ave','>=','80')->where('ave','<=','84')->count();
        //             $profmale = $gradesmale->where('ave','>=','85')->where('ave','<=','89')->count();
        //             $addmale = $gradesmale->where('ave','>=','90')->count();

        //             $begfemale = $gradesfemale->where('ave','<=','74')->count();
        //             $devfemale = $gradesfemale->where('ave','>=','75')->where('ave','<=','79')->count();
        //             $approffemale = $gradesfemale->where('ave','>=','80')->where('ave','<=','84')->count();
        //             $proffemale = $gradesfemale->where('ave','>=','85')->where('ave','<=','89')->count();
        //             $addfemale = $gradesfemale->where('ave','>=','90')->count();

        //         }

        //         $malepromtstud = $devmale + $approfmale + $profmale + $addmale;

        //         $fempromtstud = $devfemale + $approffemale + $proffemale + $addfemale;

        //         $maleretstud = collect($student)->where('enlevelid',$levelitem->id)->where('gender','MALE')->where('studstatus','1')->count() - $malepromtstud;

        //         $femretstud = collect($student)->where('enlevelid',$levelitem->id)->where('gender','FEMALE')->where('studstatus','1')->count() -  $fempromtstud;

        //         $maleconstud = collect($student)->where('enlevelid',$levelitem->id)->where('gender','MALE')->where('studstatus','2')->count();

        //         $femconstud = collect($student)->where('enlevelid',$levelitem->id)->where('gender','FEMALE')->where('studstatus','2')->count();

        //         array_push($data,(object)[

        //             'sortid'=> $levelitem->sortid,

        //             'malepromtstud'=>$malepromtstud,
        //             'fempromtstud'=>$fempromtstud,

        //             'maleretstud'=>$maleretstud,
        //             'femretstud'=>$femretstud,

        //             'maleconstud'=>$maleconstud,
        //             'femconstud'=>$femconstud,

        //             'begmale'=>$begmale,
        //             'devmale'=>$devmale,
        //             'approfmale'=>$approfmale,
        //             'profmale'=>$profmale,
        //             'addmale'=>$addmale,

        //             'begfemale'=>$begfemale,
        //             'devfemale'=>$devfemale,
        //             'approffemale'=>$approffemale,
        //             'proffemale'=>$proffemale,
        //             'addfemale'=>$addfemale
                    

        //         ]);


        //     }

        // }

        foreach($acadid as $acaditem){

            $student = SPP_EnrolledStudent::getStudent(
                null,
                null,
                null,
                null,
                $acaditem,
                null,
                null,
                null,
                null,
                true,
                false,
                'sf6'
            );
            
            
            $gradelevel = SPP_Gradelevel::getGradeLevel(null,null,null,null,Crypt::encrypt($acaditem));

            foreach($gradelevel[0]->data as $levelitem){

              

                $devmale = 0;
                $begmale = 0;
                $approfmale = 0;
                $profmale = 0;
                $addmale = 0;

                $devfemale = 0;
                $begfemale = 0;
                $approffemale = 0;
                $proffemale = 0;
                $addfemale = 0;

            

                if(collect($student)->where('enlevelid',$levelitem->id)->count() != 0){

                    $studidmale = collect($student)->where('enlevelid',$levelitem->id)->where('gender','MALE')->map(function($value){
                        return $value->id;
                    });

                    $studidfemale = collect($student)->where('enlevelid',$levelitem->id)->where('gender','FEMALE')->map(function($value){
                        return $value->id;
                    });

                    $gradesmale = collect(DB::table('tempgradesum')
                            ->whereIn('studid',$studidmale)
                            ->join('sy',function($join){
                                $join->on('tempgradesum.syid','=','sy.id');
                                $join->where('sy.id',Session::get('schoolYear')->id);
                            })
                            ->select()
                            ->get())
                            ->groupBy('studid')
                            ->map(function($value){

                                $q1 = collect($value)->avg('q1');
                                $q2 = collect($value)->avg('q2');
                                $q3 = collect($value)->avg('q3');
                                $q4 = collect($value)->avg('q4');
                                return ['ave' => round( ( $q1 + $q2 + $q3 + $q4 ) / 4 ) ];
                            });


                    $gradesfemale = collect(DB::table('tempgradesum')
                        ->whereIn('studid',$studidfemale)
                        ->join('sy',function($join){
                            $join->on('tempgradesum.syid','=','sy.id');
                            $join->where('sy.id',Session::get('schoolYear')->id);
                        })
                        ->select()
                        ->get())
                        ->groupBy('studid')
                        ->map(function($value){

                            $q1 = collect($value)->avg('q1');
                            $q2 = collect($value)->avg('q2');
                            $q3 = collect($value)->avg('q3');
                            $q4 = collect($value)->avg('q4');
                            return ['ave' => round( ( $q1 + $q2 + $q3 + $q4 ) / 4 ) ];
                        });

                  
                    
                    $begmale = $gradesmale->where('ave','<=','74')->count();
                    $devmale = $gradesmale->where('ave','>=','75')->where('ave','<=','79')->count();
                    $approfmale = $gradesmale->where('ave','>=','80')->where('ave','<=','84')->count();
                    $profmale = $gradesmale->where('ave','>=','85')->where('ave','<=','89')->count();
                    $addmale = $gradesmale->where('ave','>=','90')->count();

                    $begfemale = $gradesfemale->where('ave','<=','74')->count();
                    $devfemale = $gradesfemale->where('ave','>=','75')->where('ave','<=','79')->count();
                    $approffemale = $gradesfemale->where('ave','>=','80')->where('ave','<=','84')->count();
                    $proffemale = $gradesfemale->where('ave','>=','85')->where('ave','<=','89')->count();
                    $addfemale = $gradesfemale->where('ave','>=','90')->count();

                 

                }

                $malepromtstud = $devmale + $approfmale + $profmale + $addmale;

                $fempromtstud = $devfemale + $approffemale + $proffemale + $addfemale;

                $maleretstud = collect($student)->where('enlevelid',$levelitem->id)->where('gender','MALE')->where('studstatus','1')->count() - $malepromtstud;

                $femretstud = collect($student)->where('enlevelid',$levelitem->id)->where('gender','FEMALE')->where('studstatus','1')->count() -  $fempromtstud;

                $maleconstud = collect($student)->where('enlevelid',$levelitem->id)->where('gender','MALE')->where('studstatus','2')->count();

                $femconstud = collect($student)->where('enlevelid',$levelitem->id)->where('gender','FEMALE')->where('studstatus','2')->count();


                array_push($data,(object)[

                    'sortid'=> $levelitem->sortid,

                    'malepromtstud'=>$malepromtstud,
                    'fempromtstud'=>$fempromtstud,

                    'maleretstud'=>$maleretstud,
                    'femretstud'=>$femretstud,

                    'maleconstud'=>$maleconstud,
                    'femconstud'=>$femconstud,

                    'begmale'=>$begmale,
                    'devmale'=>$devmale,
                    'approfmale'=>$approfmale,
                    'profmale'=>$profmale,
                    'addmale'=>$addmale,

                    'begfemale'=>$begfemale,
                    'devfemale'=>$devfemale,
                    'approffemale'=>$approffemale,
                    'proffemale'=>$proffemale,
                    'addfemale'=>$addfemale
                    

                ]);


            }

        }

        $allGradeLevel = SPP_Gradelevel::getGradeLevel();

        foreach($allGradeLevel[0]->data as $item){
            if(count(collect($data)->where('sortid',$item->sortid)) == 0 && $item->acadprogid != 2){
                array_push($data,(object)[
                    'sortid'=> $item->sortid,
                    'malepromtstud'=>0,
                    'fempromtstud'=>0,
                    'maleretstud'=>0,
                    'femretstud'=>0,
                    'maleconstud'=>0,
                    'femconstud'=>0,
                    'begmale'=>0,
                    'devmale'=>0,
                    'approfmale'=>0,
                    'profmale'=>0,
                    'addmale'=>0,
                    'begfemale'=>0,
                    'devfemale'=>0,
                    'approffemale'=>0,
                    'proffemale'=>0,
                    'addfemale'=>0
                ]);
            }
        }

       

        return view('principalsportal.pages.formsf6')->with('data',$data);

    }

    public function sf4_table_blade(Request $request){
       
        $month = $request->get('month');
        $days = $request->get('days');
        $year = $request->get('year');
        $syid = $request->get('syid');
        $data = \App\Models\Forms\SF4::generate($year,$month,$days,$syid);
        return view('principalsportal.pages.tables.sf4_table')->with('data',$data);

    }

    public function get_calendar(Request $request){

        $month = $request->get('month');
        $year = $request->get('year');
        return \App\Models\Forms\SF4::get_calendar($month,$year);

    }

    
    public function loadSF4(){

        // $data = \App\Models\Forms\SF4::generate($year,$month,$days);


        return view('principalsportal.pages.formsf4');

        date_default_timezone_set('Asia/Manila');

        $month = date('m');
        
        $prevmonth =  Carbon::create(date('Y'),$month-1)->isoFormat('MM');

        $schooldays = SPP_Attendance::schoolDays(Session::get('schoolYear')->id);

        if($month == 1){

            $prevmonth = 12;

        }

        $schooldays = collect($schooldays)->where('month',Carbon::create($month)->isoFormat('MMMM'));

        $studenattendance = DB::table('studattendance')
                    ->join('sy',function($join){
                        $join->on('studattendance.syid','=','sy.id');
                        $join->where('sy.id',Session::get('schoolYear')->id);
                    })
                    ->select('studid','tdate')
                    ->get();

        return $studenattendance;

        foreach( $sections[0]->data  as $key=>$item){

                        $item->femaleAtt = 0;
                        $item->maleAtt = 0;
            
                        $male = 0;
                        $female = 0;
                        $maleid = null;
                        $femaleid = null;
            
                        $students = SPP_EnrolledStudent::getStudent(
                            null,
                            null,
                            null,
                            null,
                            $item->acadprogid,
                            $item->id,
                            null,
                            null,
                            null,
                            true,
                            true
                        );

                      

                        foreach($students[0]->data as $student){
            
                            if($student->updateddatetime != null){
            
                                $student->curmonth = date('m',strtotime($student->updateddatetime));
            
                            }
                            else{
            
                                $student->curmonth = date('m',strtotime($student->dateenrolled));
            
                            }
            
                        }
            
                        $gender = collect($students[0]->data)->countBy('gender');

                        if(isset($gender['FEMALE'])){
                        
                            $female = $gender['FEMALE'];
                            
                            $femaleid = collect($students[0]->data)->map(function($value){
                                            if($value->gender == 'FEMALE'){
                                                return $value->id;
                                            }
                                        });
            
                            
            
                            foreach($schooldays as $days){
                                $item->femaleAtt = collect($days->days)->map(function($value) use ($studenattendance,$femaleid){
                                                $attCount = collect($studenattendance)
                                                                ->whereIn('studid',$femaleid)
                                                                ->where('tdate')
                                                                ->where('tdate',$value->day)
                                                                ->count();
                                                return $attCount;
                                            })->avg();
                                
                            }
            
                        }
                        if(isset($gender['MALE'])){
            
                            $male = $gender['MALE']; 
            
                            $maleid = collect($students[0]->data)->map(function($value){
                                            if($value->gender == 'MALE'){
                                                return $value->id;
                                            }
                                        });
            
                            foreach($schooldays as $days){
            
                                $item->maleAtt = collect($days->days)->map(function($value) use ($studenattendance,$femaleid){
            
                                                $attCount = collect($studenattendance)
                                                                ->whereIn('studid',$femaleid)
                                                                ->where('tdate',$value->day)
                                                                ->count();
                                                return $attCount;
                                            })->avg();
            
            
                            }
            
                        }

                        if($male == 0 && $female == 0){
            
                            unset($sections[0]->data[$key]);
            
                        }
                        else{
            
                            $item->prevtransinmale =   collect($students)->where('curmonth', $prevmonth)->where('studstatus','4')->where('gender','MALE')->count();
                            $item->prevtransinfemale =   collect($students)->where('prevemonth', $prevmonth)->where('studstatus','4')->where('gender','FEMALE')->count();
            
                            $item->prevtransoutmale =   collect($students)->where('curmonth', $prevmonth)->where('studstatus','5')->where('gender','MALE')->count();
                            $item->prevtransoutfemale =   collect($students)->where('curmonth', $prevmonth)->where('studstatus','5')->where('gender','FEMALE')->count();
            
                            $item->prevdropoutmale =   collect($students)->where('curmonth', $prevmonth)->where('studstatus','3')->where('gender','MALE')->count();
                            $item->prevdropoutfemale =   collect($students)->where('curmonth', $prevmonth)->where('studstatus','3')->where('gender','FEMALE')->count();
                           
            
                            $item->transinmale =   collect($students)->where('curmonth',$month)->where('studstatus','4')->where('gender','MALE')->count();
                            $item->transinfemale =   collect($students)->where('curmonth',$month)->where('studstatus','4')->where('gender','FEMALE')->count();
            
                            $item->transoutmale =   collect($students)->where('curmonth',$month)->where('studstatus','5')->where('gender','MALE')->count();
                            $item->transoutfemale =   collect($students)->where('curmonth',$month)->where('studstatus','5')->where('gender','FEMALE')->count();
            
                            $item->dropoutmale =   collect($students)->where('curmonth',$month)->where('studstatus','3')->where('gender','MALE')->count();
                            $item->dropoutfemale =   collect($students)->where('curmonth',$month)->where('studstatus','3')->where('gender','FEMALE')->count();
                         
                            $item->male = $male;
                            $item->female = $female;
                         
                        }
            
            
                    }

        if($sections[0]->count == 0){

            return view('generalPages.principalErrorBlade')
                        ->with('message',' Unable to generate School Form 4')
                        ->with('messagenote','No students enrolled. Please contact registrar to enroll students.');

        }

        return view('principalsportal.pages.formsf4')
                ->with('sections',collect($sections)->sortBy('levelname'));
    }

    public static function sf4changemonth(Request $request){

        $sections = Section::getSections(null,null,null,null,null,Session::get('prinInfo')->id);
        date_default_timezone_set('Asia/Manila');

        $month = $request->get('data');
        $prevmonth =  $month-1;

        $schooldays = SPP_Attendance::schoolDays(Session::get('schoolYear')->id);

        if($month == 1){

            $prevmonth = 12;

        }

        $schooldays = collect($schooldays)->where('month',Carbon::create($month)->isoFormat('MMMM'));

        $studenattendance = DB::table('studattendance')
                    ->join('sy',function($join){
                        $join->on('studattendance.syid','=','sy.id');
                        $join->where('sy.isactive','1');
                    })->get();

       

        foreach( $sections[0]->data  as $key=>$item){

            $item->femaleAtt = 0;
            $item->maleAtt = 0;

            $male = 0;
            $female = 0;
            $maleid = null;
            $femaleid = null;

            $students = SPP_EnrolledStudent::getStudent(
                null,
                null,
                null,
                null,
                $item->acadprogid,
                $item->id,
                null,
                null,
                null,
                true,
                true
            );

            foreach($students as $student){

                if($student->updateddatetime != null){

                    $student->curmonth = date('m',strtotime($student->updateddatetime));

                }
                else{

                    $student->curmonth = date('m',strtotime($student->dateenrolled));

                }

            }

            $gender = collect($students)->countBy('gender');

            if(isset($gender['FEMALE'])){

                $female = $gender['FEMALE'];
                
                $femaleid = collect($students)->map(function($value){
                                if($value->gender == 'FEMALE'){
                                    return $value->id;
                                }
                            });

                

                foreach($schooldays as $days){
                    $item->femaleAtt = collect($days->days)->map(function($value) use ($studenattendance,$femaleid){
                                    $attCount = collect($studenattendance)
                                                    ->whereIn('studid',$femaleid)
                                                    ->where('tdate')
                                                    ->where('tdate',$value->day)
                                                    ->count();
                                    return $attCount;
                                })->avg();
                    
                }

            }
            if(isset($gender['MALE'])){

                $male = $gender['MALE']; 

                $maleid = collect($students)->map(function($value){
                                if($value->gender == 'MALE'){
                                    return $value->id;
                                }
                            });

                foreach($schooldays as $days){

                    $item->maleAtt = collect($days->days)->map(function($value) use ($studenattendance,$femaleid){

                                    $attCount = collect($studenattendance)
                                                    ->whereIn('studid',$femaleid)
                                                    ->where('tdate',$value->day)
                                                    ->count();
                                    return $attCount;
                                })->avg();


                }

            }

            if($male == 0 && $female == 0){

                unset($sections[0]->data[$key]);
                $sections[0]->count = $sections[0]->count -1;

            }
            else{

              

                $item->prevtransinmale =   collect($students)->where('curmonth', $prevmonth)->where('studstatus','4')->where('gender','MALE')->count();
                $item->prevtransinfemale =   collect($students)->where('prevemonth', $prevmonth)->where('studstatus','4')->where('gender','FEMALE')->count();

                $item->prevtransoutmale =   collect($students)->where('curmonth', $prevmonth)->where('studstatus','5')->where('gender','MALE')->count();
                $item->prevtransoutfemale =   collect($students)->where('curmonth', $prevmonth)->where('studstatus','5')->where('gender','FEMALE')->count();

                $item->prevdropoutmale =   collect($students)->where('curmonth', $prevmonth)->where('studstatus','3')->where('gender','MALE')->count();
                $item->prevdropoutfemale =   collect($students)->where('curmonth', $prevmonth)->where('studstatus','3')->where('gender','FEMALE')->count();
               

                $item->transinmale =   collect($students)->where('curmonth',$month)->where('studstatus','4')->where('gender','MALE')->count();
                $item->transinfemale =   collect($students)->where('curmonth',$month)->where('studstatus','4')->where('gender','FEMALE')->count();

                $item->transoutmale =   collect($students)->where('curmonth',$month)->where('studstatus','5')->where('gender','MALE')->count();
                $item->transoutfemale =   collect($students)->where('curmonth',$month)->where('studstatus','5')->where('gender','FEMALE')->count();

                $item->dropoutmale =   collect($students)->where('curmonth',$month)->where('studstatus','3')->where('gender','MALE')->count();
                $item->dropoutfemale =   collect($students)->where('curmonth',$month)->where('studstatus','3')->where('gender','FEMALE')->count();
             
                $item->male = collect($students)->where('studstatus','1')->where('gender','MALE')->count();
                $item->female = collect($students)->where('studstatus','1')->where('gender','FEMALE')->count();;
             
            }

          


        }




        return view('search.principal.sf4')
                ->with('sections',collect($sections)->sortBy('levelname'));

    }

    public static function student_list(){
        return view('principalsportal.pages.formsf9');
    }

    public static function prinsf9getstudent(Request $request){

        $refid = DB::table('usertype')->where('id',auth()->user()->type)->where('deleted',0)->select('refid')->first();
        $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
		
		$syid = $request->get('syid');
        $semid = $request->get('semid');

        if(auth()->user()->type == 2){
            $academicprogram = DB::table('academicprogram')
                            ->where('principalid',$teacherid)
                            ->select('id')
                            ->get();
							
							$teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
											
							$academicprogram = DB::table('teacheracadprog')
								->join('academicprogram',function($join){
									$join->on('teacheracadprog.acadprogid','=','academicprogram.id');
								})
								->where('teacherid',$teacherid)
								->where('acadprogutype',2)
								->where('syid',$syid)
								->select('academicprogram.*')
								->where('deleted',0)
								->orderBy('acadprogid')
								->select('academicprogram.id')
								->get();
        }else{
            if( $refid->refid == 20){
                $xtend = 'principalassistant.layouts.app2';
            }elseif( $refid->refid == 22){
                $xtend = 'principalcoor.layouts.app2';
            }

            $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;

            $academicprogram = DB::table('teacheracadprog')
                            ->where('teacherid',$teacherid)
                            ->where('syid',$syid)
                            ->select('acadprogid as id')
                            ->where('deleted',0)
                            ->get();
        }

       
        $acad = array();

        $students = array();
        foreach($academicprogram as $item){
            if($item->id != 5){
                $acad = $item->id;
                $student = DB::table('enrolledstud')
                            ->where('enrolledstud.deleted',0)
                            ->where('enrolledstud.syid',$syid)
                            ->join('studinfo',function($join){
                                $join->on('studinfo.id','=','enrolledstud.studid');
                                $join->where('studinfo.deleted',0);
                            })
                            ->join('sections',function($join){
                                $join->on('enrolledstud.sectionid','=','sections.id');
                                $join->where('sections.deleted',0);
                            })
                            ->join('gradelevel',function($join) use($acad){
                                $join->on('enrolledstud.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                                $join->where('acadprogid',$acad);
                            })
                            ->select(
                                'lastname',
                                'firstname',
                                'middlename',
                                'suffix',
                                'acadprogid',
                                'enrolledstud.levelid',
                                'enrolledstud.sectionid',
                                'dob',
                                'gender',
                                'levelname',
                                'sections.sectionname',
                                'lrn',
                                'sid',
                                'studinfo.id'
                            )
                            ->get();
            }else{
                
                $student = DB::table('sh_enrolledstud')
                            ->where('sh_enrolledstud.deleted',0)
                            ->where('sh_enrolledstud.syid',$syid)
                            ->where('sh_enrolledstud.semid',$semid)
                            ->join('studinfo',function($join){
                                $join->on('studinfo.id','=','sh_enrolledstud.studid');
                                $join->where('studinfo.deleted',0);
                            })
                            ->join('sections',function($join){
                                $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                $join->where('sections.deleted',0);
                            })
                            ->join('gradelevel',function($join){
                                $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->select(
                                'lastname',
                                'firstname',
                                'middlename',
                                'suffix',
                                'acadprogid',
                                'sh_enrolledstud.levelid',
                                'sh_enrolledstud.sectionid',
                                'dob',
                                'gender',
                                'levelname',
                                'sections.sectionname',
                                'lrn',
                                'sid',
                                'studinfo.id'
                            )
                            ->distinct()
                            ->get();
            }

            foreach($student as $stud_item){
                $item->actiontaken = null;
                $middlename = explode(" ",$stud_item->middlename);
                $temp_middle = '';
                if($middlename != null){
                    foreach ($middlename as $middlename_item) {
                        if(strlen($middlename_item) > 0){
                            $temp_middle .= $middlename_item[0].'.';
                        } 
                    }
                }

                $stud_item->student = $stud_item->lastname.', '.$stud_item->firstname.' '.$stud_item->suffix.' '.$temp_middle;
                array_push($students, $stud_item);
                $stud_item->search = $stud_item->student.' '.$stud_item->levelname.' '.$stud_item->sectionname.' '.$stud_item->sid;
            }
            
        }


        return $students;
        
    }

    
    public function loadCalendar(){

        $schoolcalendar = DB::table('schoolcal')
                            ->where('deleted','0')
                            ->get();

        return view('principalsportal.pages.schoolCalendar')
                ->with('schoolcalendar',$schoolcalendar);
    }

    public function principalgetevent(Request $request){


        return SPP_Calendar::getHoliday(null,null,$request->get('id'));

    }

    public function principalgeteventtype(Request $request){

        return SPP_Calendar::getEventType($request->get('id'));

    }

    public function principalinsertEvent(Request $request){

        DB::table('schoolcalendar')
            ->insert([
                "title"=>$request->get('title'),
                "start"=>$request->get('start'),
                "end"=>$request->get('end'),
                "type"=>$request->get('eventtype'),
                "createdby"=>auth()->user()->id,
                "deleted"=>'0'
            ]);
    }

    public function principalupdateEvent(Request $request){
        DB::table('schoolcalendar')
             ->where('id',$request->get('id'))
            ->update([
                "start"=>$request->get('start'),
                "end"=>$request->get('end'),
            ]);
    }

    public function attributes()
    {
        return [
            'title' => 'testing',
        ];
    }

    public function messages()
    {
        return [
            'G.required' => 'Group is required',
        ];
    }

    public function principalpostannouncement(Request $request){

        if($request->get('announcetype') == 2){

            $newContent = strip_tags($request->get('content'));

            $newData = [
                'content'=>$newContent,
            ];

            $validator = Validator::make($newData, [
                'content' => 'max:150|required',
            ]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'content' => 'required',
                'title'=>'required'
            ]);
        }

       
        
        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput();

        }
        else{

            toast('Success','success')->autoClose(2000)->toToast($position = 'top-right');

            return SPP_Announcement::storeAnnouncement($request);
        }


        // return SPP_AcademicProg::getPrincipalAcadProg(Session::get('prinInfo')->id);

        // $acadprog = SPP_AcademicProg::getPrincipalAcadProg(Session::get('prinInfo')->id);

        // foreach($acadprog as $item){

        //     $students =  SPP_EnrolledStudent::getStudent(null,null,null,null,$item->id,null,null,null,null,null,null,'basic');

        //     if($students[0]->count > 0){

        //         $sid =  collect($students[0]->data)->map(function($value){
        //                 return 'P'.$value->sid;
        //         });

        //         return DB::table('users')
        //                 ->where('type','9')
        //                 ->whereIn('email',  $sid)
        //                 ->select('id')
        //                 ->get();

        //     }   
         

            

        // }

        return SPP_Announcement::storeAnnouncement($request);

    }

    public function viewAnnouncements(){

        
        try{

            $announcements = SPP_Announcement::getAnnouncement(null,10,null,null);

        }

        catch (\Exception $e) {
            
            return view('principalsportal.pages.error500');
        }

       

        return view('principalsportal.pages.management.announcement.viewcreatedannouncements')
                ->with('data',$announcements);

    }

    public function composeAnnouncemenent(){

        // $currentDay = \Carbon\Carbon::now('Asia/Manila')->isoFormat('DD');
        // $currentMonth = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MM');
        // $currentYear= \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY');
        // // return date('Y-m-d');

        // // return date('Y-m-d').' 00:00:00';

        // // return date('Y-m-d');

        // // return  \Carbon\Carbon::today()->toDateString();

        // $smsbunkertextblast = DB::table('smsbunkertextblast')
        //                             ->whereDate('createddatetime', '=', \Carbon\Carbon::today()->toDateString())
        //                             ->count();

        // return $smsbunkertextblast;



        try{

            return view('principalsportal.pages.management.announcement.composeAnnouncements');

        }
        catch (\Exception $e) {
        
            return view('principalsportal.pages.error500');
        }
       
    }

    public static function principalsearchannouncement(Request $request){

        $announcements = SPP_Announcement::getAnnouncement($request->get('pagenum'),10,null,$request->get('data'));

        return view('search.principal.announcement')->with('data',$announcements);

    }

    public static function principalgradeannouncement($id,$headerid){

        $query = DB::table('notifications')
                    ->where('headerid',Crypt::decrypt($headerid))
                    ->where('recieverid',auth()->user()->id)
                    ->join('gradelogs','notifications.headerid','=','gradelogs.id')
                    ->join('grades','gradelogs.gradeid','=','grades.id')
                    ->select('notifications.status','grades.syid');

        $query->update(['notifications.status'=>'1']);

        return self::loadGradeInfo(Crypt::decrypt($id), $query->get()[0]->syid);

    }


    public function principalReadAnnouncement($id){

        try {

            $id = Crypt::decrypt($id);
            
        } catch (\Exception $e) {
            
            $id = $id;
        }

        

        $content = DB::table('announcements')->where('announcements.id',$id)
                    ->join('users','announcements.createdby','=','users.id')
                    ->select('announcements.*', 'users.name')
                    ->get();

        $reciever = DB::table('announcements')->where('announcements.id',$id)
                    ->join('users','announcements.createdby','=','users.id')
                    ->join('notifications',function($join) {
                        $join->on('announcements.id','=','notifications.headerid');
                    })
                    ->select('recieverid')
                    ->get();

        DB::table('notifications')
                ->where('notifications.headerid',$id)
                ->where('notifications.type','1')
                ->update(['status'=>'1']);
                

        return view('principalsportal.pages.notifications.readAnnouncement')->with('content',$content);

    }

    public function gradeLevels($id){

        $gradelevels = DB::table('academicprogram')
                ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
                ->join('gradelevel',function($join){
                    $join->on('academicprogram.id','=','gradelevel.acadprogid');
                    $join->where('gradelevel.deleted','0');
                })
                ->select('gradelevel.id','gradelevel.levelname')
                ->orderBy('gradelevel.sortid')
                ->get();

    
        return view('principalsportal.pages.gradelevels')
                ->with('gradelevels',$gradelevels)
                ->with('quarter',$id);
    }

    public function loadAwardees($quarter,$gradelevel){

        $subjects = DB::table('academicprogram')
                     ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
                     ->join('subjects','academicprogram.id','=','subjects.acadprogid')
                     ->select('subjects.id','subjects.subjdesc')
                     ->get();

        $topGradesbySubject = array();

        foreach($subjects as $subject){
            $topgraders = GenerateGrade::topGrader($quarter,$subject->id,$gradelevel); 
            if($topgraders=="Empty"){
                array_push($topGradesbySubject, (object) array('data' => (object) array(
                    'subject'=>$subject->subjdesc),
                    'message'=>"Empty"
                
            ));
            }
            else{
                array_push($topGradesbySubject, (object) array("data" => $topgraders,
                'message'=>"Not Empty"));
            }
           
        }

     
        return view('principalsportal.pages.awardees')
                ->with('topGradesbySubject',$topGradesbySubject);
  
    }

    


    
    public function gradelevelajax(){

        $gradelevels = DB::table('academicprogram')
            ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
            ->join('gradelevel',function($join){
                $join->on('academicprogram.id','=','gradelevel.acadprogid');
                $join->where('gradelevel.deleted','0');
            })
            ->select('gradelevel.id','gradelevel.levelname')
            ->orderBy('gradelevel.sortid')
            ->get();

        $returnString = '';

        foreach($gradelevels as $gradelevel){
            $returnString .= '<div class="icheck-primary d-inline  mr-4">';
            $returnString .= '<input checked type="checkbox" name="G[]" id="G'.$gradelevel->id.'"  value= "'.$gradelevel->id.'" class="custom-control-input gradelevel" @if(old("G")) {{ in_array('.$gradelevel->id.',old("G")) ? "checked": ""}} @endif><label style="width:50%" for="G'.$gradelevel->id.'">'.$gradelevel->levelname.'</label>';
            
        }

        return $returnString;

    }

    public function sectionajax(){

        $sections = DB::table('academicprogram')
            ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
            ->join('gradelevel',function($join){
                $join->on('academicprogram.id','=','gradelevel.acadprogid');
                $join->where('gradelevel.deleted','0');
            })
            ->join('sections','gradelevel.id','=','sections.levelid')
            ->select('sections.id','sections.sectionname')
            ->orderBy('gradelevel.sortid')
            ->get();

        $returnString = '';

        foreach($sections as $section){
            $returnString .= '<div class="icheck-primary d-inline">';
            $returnString .= '<input checked type="checkbox" name="S[]" id="S'.$section->id.'"  value= "'.$section->id.'" class="custom-control-input section" @if(old("S")) {{ in_array('.$section->id.',old("S")) ? "checked": ""}} @endif><label style="width:50%" for="S'.$section->id.'">'.$section->sectionname.'</label></div>';
            
        }

        return $returnString;

    }


    public function removeeventajax(Request $request){

        DB::table('schoolcalendar')->where('id',$request->get('id'))->update(['deleted'=>'1']);

    }

    
   

    //------------------------------------ Teacher ------------------------------------------------

    public function searchteacherajax(Request $request){

        $teachers =  SPP_Teacher::filterTeacherFaculty($request->get('pagenum'),6,null,null,$request->get('data'));

        return view('search.principal.facultystaff')->with('data',$teachers);

    }

    public function loadTeachers(){

        return view('principalsportal.pages.teacher')->with('data',SPP_Teacher::filterTeacherFaculty(null,6,null,null,null,null,'basic'));

    }

    public function principalGetTeacher(Request $request){

        $levelInfo = SPP_Gradelevel::getGradeLevel(null,null,$request->get('data'));

        return SPP_Teacher::filterTeacherFaculty(null,null,null,null,null,$levelInfo[0]->data[0]->acadprogid);

    }
   
    //------------------------------------ Teacher ------------------------------------------------


    //------------------------------------ Sections ------------------------------------------------

     
    public static function getSectionInformation(Request $request){

        return Section::getSectionInformation($request);

    }


    public static function prinicipaladdblocktoshsection(Request $request){

        try{
                  
            $syid = $request->get('syid');
            $strandid = $request->get('strandid');
            $sectionid = $request->get('sectionid');
            $levelid = $request->get('levelid');

            $check = DB::table('sh_block')
                        ->where('strandid',$strandid)
                        ->where('levelid',$levelid)
                        ->where('deleted',0)
                        ->select('id')
                        ->first();

            if(!isset($check->id)){

                $strand_info = DB::table('sh_strand')
                                    ->where('id',$strandid)
                                    ->select('strandcode')
                                    ->first();

                $blockid = DB::table('sh_block')
                            ->insertGetId([
                                'blockname'=>$strand_info->strandcode,
                                'strandid'=>$strandid,
                                'levelid'=>$levelid,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);


            }else{
                $blockid = $check->id;
            }

            $check = DB::table('sh_sectionblockassignment')
                        ->join('sh_block',function($join) use($strandid){
                            $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                            $join->where('strandid',$strandid);
                            $join->where('sh_block.deleted',0);
                        })
                        ->where('sh_sectionblockassignment.deleted',0)
                        ->where('sh_sectionblockassignment.sectionid',$sectionid)
                        ->where('sh_sectionblockassignment.syid',$syid)
                        ->select('sh_sectionblockassignment.id')
                        ->first();

            if(isset($check->id)){
                return array((object)[
                    'status'=>2,
                    'message'=>'Strand Already Exist'
                ]);
            }

            DB::table('sh_sectionblockassignment')
                        ->insert([
                            'syid'=>$syid,
                            'deleted'=>0,
                            'sectionid'=>$sectionid,
                            'blockid'=>$blockid,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            return array((object)[
                'status'=>1,
                'message'=>'Strand Added!'
            ]);
           
            

        }catch(\Exception $e){
                return self::store_error($e);
        }

        // return SPP_Section::addBlockToSHSection($request->get('section'),$request->get('b'));

    }

    //------------------------------------ Sections ------------------------------------------------


    //------------------------------------ SUBJECTS ------------------------------------------------

    public function viewSubjects($id){

        $checkVersion = GradingSystem::checkVersion();

        if(!isset($checkVersion->version)){

            return back();

        }

        $did = Crypt::decrypt($id);

        $subjects = SPP_Subject::getAllSubject(null,null,null,null,$id);

        $acadprogs = DB::table('academicprogram')
                    ->where('academicprogram.id', $did)
                    ->get();

        $subjects[0]->data = SPP_Subject::getSubjectUsage( $subjects[0]->data,null,Crypt::decrypt($id));

        if($checkVersion->version == 'v1'){

            return view('principalsportal.pages.management.subject.subject')
                        ->with('data', $subjects )
                        ->with('acadid',$id)
                        ->with('acadprogs', $acadprogs);

        }
        else if($checkVersion->version == 'v2'){

            $subjects = $subjects[0]->data;

            return view('principalsportal.pages.management.subject.subject_v2')
                        ->with('subjects', $subjects )
                        ->with('acadid',$id)
                        ->with('acadprogs', $acadprogs);
            
        }

        

    }

    public static function principalsearchsubjects(Request $request){

        $subject = SPP_Subject::getAllSubject($request->get('pagenum'),10,null,$request->get('data'),$request->get('acad'));

        $subject[0]->data = SPP_Subject::getSubjectUsage( $subject[0]->data,null,Crypt::decrypt($request->get('acad')));

        return view('search.principal.subject')->with('data',$subject)->with('acadid',$request->get('acad'));


    }

    public static function insertSubject(Request $request){


        $checkVersion = GradingSystem::checkVersion();

        if(!isset($checkVersion->version)){

            return back();

        }

        $sf9 = 0;
        $mapeh = 0;

        if($request->has('insf9')){
            $sf9 = 1;
        }
        if($request->has('inMAPEH')){
            $mapeh = 1;
        }
       
        
        $acadid = Crypt::decrypt($request->get('acad'));

        $data = [
            'gradelevel' => $request->get('gradelevel'),
            'sn' => $request->get('sn'),
            'ww' => $request->get('ww'),
            'pt' => $request->get('pt'),
            'qa' => $request->get('qa'),
            'q' => $request->get('q'),
            'sc' => $request->get('sc'),
            'type' => $request->get('type'),
            'semester' => $request->get('semester'),
        ];


        if($acadid == 5){

            $message = [
                'sn.required'=>'Subject name is required.',
                'sc.required'=>'Subject code is required.',
                'sn.unique'=>'Subject already exists.'
            ];

        }
        else{

            $message = [
                'sn.required'=>'Subject name is required.',
                'sc.required'=>'Subject code is required.',
                'sn.unique'=>'Subject already exists.',
                'type.required'=>'Subject type is required.',
                'strand.required_if'=>'Strand is required.'
            ];
        }

        if($checkVersion->version == 'v1'){

            if($acadid == 5){

                $type = $request->get('type');

                $validator = Validator::make($data, [

                    'sn' => ['required',Rule::unique('sh_subjects','subjtitle')->where(function($query)use($request){
                        return $query->where('deleted','0')->where('subjtrackid',$request->get('track'));
                    })],
                    'sc' => 'required',
                    'type'=> 'required',
                    'gradelevel' => 'required',
                    'semester'=> 'required'
                
                ], $message);

            }
            else{

                $validator = Validator::make($data, [
                    'sn' => ['required',Rule::unique('subjects','subjdesc')->where(function($query) use($acadid){
                        return $query->where('acadprogid',$acadid);
                    })->where('deleted','0')],
                    'sc' => 'required',
                    'gradelevel' => 'required'
                
                ], $message);

            }


            $ww = $request->get('ww');
            $pt = $request->get('pt');
            $qa = $request->get('qa');

            if( $request->get('q1') == null && $request->get('q1') == null && $request->get('q1') == null && $request->get('q1') == null){

                $validator->errors()->add('total', 'Please specify quarter');

            }


            $validator->after(function ($validator) use($ww, $pt, $qa){
                if (($ww + $pt + $qa) != 100) {
                    $validator->errors()->add('total', 'Written works, Performance Task and Quarter Assesment should equal to 100');
                }
            });

        }else if($checkVersion->version == 'v2'){


            if($acadid == 5){

                $type = $request->get('type');

                $validator = Validator::make($data, [

                    'sn' => ['required',Rule::unique('sh_subjects','subjtitle')->where(function($query)use($request){
                        return $query->where('deleted','0')->where('subjtrackid',$request->get('track'));
                    })],
                    'sc' => 'required',
                    'type'=> 'required',
                    'semester'=> 'required'
                
                ], $message);

            }
            else{

                $validator = Validator::make($data, [
                    'sn' => ['required',Rule::unique('subjects','subjdesc')->where(function($query) use($acadid){
                        return $query->where('acadprogid',$acadid);
                    })->where('deleted','0')],
                    'sc' => 'required',
                
                ], $message);

            }


        }

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            return back()->withErrors($validator)->withInput();

        }
        else{


            $subjid = SPP_Subject::insertSubject(
                        $request->get('sn'),
                        $request->get('sc'), 
                        $acadid,
                        $request->get('type'),
                        $request->get('strand'),
                        $request->get('prereq'),
                        $sf9,
                        $mapeh,
                        $request->get('semester'),
                        $request->get('track')
                    );

        
            if(gettype($subjid) == 'integer'){

               SPP_GradeSetup::insertGradeSetup(
                    $request->get('gradelevel'),
                    [$subjid],
                    $request->get('ww'),
                    $request->get('pt'),
                    $request->get('qa'),
                    $request->get('q')
                );

                toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                return back();

            }
            else{

                // return $subjid;

                toast('Somethin went wrong!','error')->autoClose(2000)->toToast($position = 'top-right');
                return back();

            }

        }

        return back();

    }

    public static function principalremovesubject($id , $acadid){

        $usage = SPP_Subject::getSubjectUsage(null,Crypt::decrypt($id),Crypt::decrypt($acadid));

        if($usage){
      

            return back();

        }
        else{
          

            date_default_timezone_set('Asia/Manila');
            $date = date('Y-m-d H:i:s');

            if(Crypt::decrypt($acadid) != 5){

               
            
                DB::table('subjects')
                    ->where('id',Crypt::decrypt($id))
                    ->update([
                        'deleted'=>1,
                        'deletedby'=>auth()->user()->id,
                        'deleteddatetime'=>$date
                        ]);

            }
            else{

                DB::table('sh_subjects')
                    ->where('id',Crypt::decrypt($id))
                    ->update([
                        'deleted'=>1,
                        'deletedby'=>auth()->user()->id,
                        'deleteddatetime'=>$date
                        ]);

            }

            return back();

        }

        

    }

    public function principalupdatesubject(Request $request){

        $checkVersion = GradingSystem::checkVersion();

        if(!isset($checkVersion->version)){

            return back();

        }

        $strand = null;

        $sf9 = 0;
        $mapeh = 0;

        if($request->has('insf9')){
            $sf9 = 1;
        }
        if($request->has('inMAPEH')){
            $mapeh = 1;
        }

        if( Crypt::decrypt($request->get('acad')) == 5){

            try {

                $subjid = Crypt::decrypt($request->get('shsi'));

            } catch (\Exception $e) {

                $subjid = null;

            }

            try {

                $strand = Crypt::decrypt($request->get('strand'));

            } catch (\Exception $e) {

                $strand = null;

            }
            
        }
        else{

            try {

                $subjid = Crypt::decrypt($request->get('si'));

            } catch (\Exception $e) {

                    $subjid = $request->get('si');

            }

        }

        if(Crypt::decrypt($request->get('acad')) == 5){
    
            $message = [
                'sn.required'=>'Subject name is required.',
                'sc.required'=>'Subject code is required.',
                'sn.unique'=>'Subject already exists.'
            ];

        }
        else{

            $message = [
                'sn.required'=>'Subject name is required.',
                'sc.required'=>'Subject code is required.',
                'sn.unique'=>'Subject already exists.',
                'type.required'=>'Subject type is required.',
                'strand.required_if'=>'Strand is required.'
            ];
            
        }
     
        $acadid = Crypt::decrypt($request->get('acad'));

        $data = [
            'gradelevel' => $request->get('gradelevel'),
            'sn' => $request->get('sn'),
            'ww' => $request->get('ww'),
            'pt' => $request->get('pt'),
            'qa' => $request->get('qa'),
            'q' => $request->get('q'),
            'sc' => $request->get('sc'),
            'type' => $request->get('type'),
            'semester'=>$request->get('semester')
        ];

        if($checkVersion->version == 'v1'){
    
            if(Crypt::decrypt($request->get('acad')) == 5){
    
                $type = $request->get('type');
    
                $validator = Validator::make($data, [
    
                    'sn' => ['required',Rule::unique('sh_subjects','subjtitle')->where(function($query) use($request){
                        return $query->where('deleted','0')->where('subjtrackid',$request->get('track'));
                    })->where('deleted','0')->ignore(Crypt::decrypt($request->get('shsi')),'id')],
                    'sc' => 'required',
                    'type'=> 'required',
                    'gradelevel' => 'required',
                    'semester'=> 'required'
                   
                
                ], $message);
    
            }
            else{
    
                $validator = Validator::make($data, [
                    'sn' => ['required',Rule::unique('subjects','subjdesc')->where(function($query) use($acadid){
                        return $query->where('acadprogid',$acadid);
                    })->where('deleted','0')->ignore(Crypt::decrypt($request->get('si')),'id')],
                    'sc' => 'required',
                    'gradelevel' => 'required'
                
                ], $message);
    
            }
    
    
            $ww = $request->get('ww');
            $pt = $request->get('pt');
            $qa = $request->get('qa');
    
    
            $validator->after(function ($validator) use($ww, $pt, $qa, $request){
                if (($ww + $pt + $qa) != 100) {
                    $validator->errors()->add('total', 'Written works, Performance Task and Quarter Assesment should equal to 100');
                }
    
                if( !$request->has('q') ){
    
                    $validator->errors()->add('q', 'Please specify quarter');
        
                }
            });
    
    

        }
        else if($checkVersion->version == 'v2'){

            $data = [
                'sn' => $request->get('sn'),
                'sc' => $request->get('sc'),
                'type' => $request->get('type'),
                'semester'=>$request->get('semester')
            ];

            if(Crypt::decrypt($request->get('acad')) == 5){
    
                $type = $request->get('type');
    
                $validator = Validator::make($data, [
                    'sn' => ['required',Rule::unique('sh_subjects','subjtitle')->where(function($query) use($request){
                        return $query->where('deleted','0')->where('subjtrackid',$request->get('track'));
                    })->where('deleted','0')->ignore(Crypt::decrypt($request->get('shsi')),'id')],
                    'sc' => 'required',
                    'type'=> 'required',
                    'semester'=> 'required'
                ], $message);
    
            }
            else{
    
                $validator = Validator::make($data, [
                    'sn' => ['required',Rule::unique('subjects','subjdesc')->where(function($query) use($acadid){
                        return $query->where('acadprogid',$acadid);
                    })->where('deleted','0')->ignore(Crypt::decrypt($request->get('si')),'id')],
                    'sc' => 'required',
                ], $message);
    
            }

        }

        


        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput();

        }
        else{

            $sf9 = 0;

            if($request->has('insf9')){
                $sf9 = 1;
            }

            $subjidreturn = SPP_Subject::updateSubject(
                $request->get('sn'),
                $request->get('sc'), 
                $acadid,
                $subjid,
                $request->get('type'),
                $strand,
                $request->get('prereq'),
                $sf9,
                $mapeh,
                $request->get('semester'),
                $request->get('track')
            );

            $first = 0;
            $second = 0;
            $third = 0;
            $fourth = 0;
        
            if(gettype($subjidreturn) == 'integer'){

                $grade_level = SPP_Gradelevel::getGradeLevel(null,null,null,null,Crypt::encrypt($acadid));

                foreach($grade_level[0]->data as $item){

                    $inItem = false;

                    if($request->get('gradelevel') != null){

                        foreach($request->get('gradelevel') as $levelitem){

                            if($item->id == $levelitem){
    
                                $inItem = true;
                            }
    
                        }

                    }

                    if($inItem){

                        if(in_array('1',$request->get('q'))){
                            $first = 1;
                        }
                        
                        if(in_array('2',$request->get('q'))){
                            $second = 1;
                        }
                
                        if(in_array('3',$request->get('q'))){
                            $third = 1;
                        }
                
                        if(in_array('4',$request->get('q'))){
                            $fourth = 1;
                        }

                        DB::table('gradessetup')
                            ->updateOrInsert  (
                                [
                                    'subjid'=>$subjid,
                                    'levelid'=>$item->id,
                                    'syid'=>Session::get('schoolYear')->id,
                                ],
                                [
                                'writtenworks'=>$request->get('ww'),
                                'performancetask'=>$request->get('pt'),
                                'qassesment'=>$request->get('qa'),
                                'first'=>$first,
                                'second'=>$second,
                                'third'=> $third,
                                'fourth'=> $fourth,
                                'deleted'=>'0',
                                'updatedby'=>auth()->user()->id
                                ]
                            );
                        
                    }
                    else{

                        DB::table('gradessetup')
                            ->where('subjid',$subjid)
                            ->where('levelid',$item->id)
                            ->where('syid', Session::get('schoolYear')->id)
                            ->updateOrInsert(
                                [
                                    'subjid'=>$subjid,
                                    'levelid'=>$item->id,
                                    'syid'=>Session::get('schoolYear')->id,
                                ],
                                [
                                    'deleted'=>'1',
                                    'updatedby'=>auth()->user()->id
                                ]
                            );

                    }

                }

                return back();
             

            }
            else{

                return "done";

            }

        }

        // return back();

    }

    public function prinicipalGetSubject(Request $request){

        $subjid = Crypt::decrypt($request->get('i'));

        $subject = SPP_Subject::getAllSubject(null,10,$subjid ,null,$request->get('acad'))[0]->data;

        if(isset($subject[0]->strandid)){
            $subject[0]->strandid = Crypt::encrypt($subject[0]->strandid);
        }

       
        
        return $subject;

    }

    public function principalGetPrereq(Request $request){

        try {
            $subjid = Crypt::decrypt($request->get('si'));

        } catch (\Exception $e) {
            
           $subjid = 0;
        }

        

        return SPP_Subject::getPreRequisite(null,null, $subjid,$request->get('prereqid'));

    }


    public function viewSHSubjectsbyStrand(Request $request){

        if(!is_array($request->get('tp'))){
            $tp = [$request->get('tp')];
        }
        
        else{
            $tp = $request->get('tp');
        }
        
        try {

            $acadid = Crypt::decrypt($request->get('acad'));
            $strand = Crypt::decrypt($request->get('st'));
            
        } catch (\Exception $e) {
            
            return back();
        }

        return SPP_Subject::getAllSubject(
                                null,
                                null,
                                null,
                                null,
                                $request->get('acad'),
                                $tp,
                                $strand
                            )[0]->data;


    }
    


     //------------------------------------ SUBJECTS ------------------------------------------------


    //------------------------------------ Class Schedule ------------------------------------------------


    public static function evaluateSchedule(Request $request){

        // return SPP_ClassSchedule::evaluateInsertedInformation($request);
        // return $request->all();

        $sectionid = $request->get('section');
        $subjid = $request->get('s');
        $teacherid = $request->get('tea');
        $roomid = $request->get('r');
        $class = $request->get('class');
        $syid = $request->get('syid');
        $time = explode(" - ", $request->get('t'));
        $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
        $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
        $levelid = DB::table('sections')->where('id',$sectionid)->select('levelid')->first()->levelid;
        $days = $request->get('days');

        $check_classched = DB::table('classsched')
                                ->where('syid',$syid)
                                ->where('sectionid',$sectionid)
                                ->where('subjid',$subjid)
                                ->where('deleted',0)
                                ->get();

        if(count($check_classched) == 0){
            $headerid = DB::table('classsched')
                                ->insertGetId([
                                    'glevelid'=>$levelid,
                                    'sectionid'=>$sectionid,
                                    'subjid'=>$subjid,
                                    'syid'=> $syid,
                                    'deleted'=>'0',
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                ]);

        }else{
            $headerid = $check_classched[0]->id;
        }
        

       
        foreach($days as $item){
            $check_detail = Db::table('classscheddetail')
                                ->where('headerid',$headerid)
                                ->where('days',$item)
                                ->where('stime',$stime)
                                ->where('etime',$etime)
                                ->where('deleted',0)
                                ->get();

            if(count($check_detail) > 0){
                DB::table('classscheddetail')
                        ->where('id',$check_detail[0]->id)
                        ->where('deleted',0)
                        ->take(1)
                        ->update([
                            'roomid'=>$roomid,
                            'classification'=>$class,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            }else{
                DB::table('classscheddetail')
                        ->insert([
                            'headerid'=> $headerid,
                            'days'=>$item,
                            'stime'=>$stime,
                            'etime'=>$etime,
                            'roomid'=>$roomid,
                            'deleted'=>'0',
                            'createdby'=>auth()->user()->id,
                            'classification'=>$class,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            }
        }
   


        $check_classched = DB::table('assignsubj')
                                ->where('glevelid',$levelid)
                                ->where('sectionid',$sectionid)
                                ->where('syid',$syid)
                                ->where('deleted',0)
                                ->get();

        if(count($check_classched) == 0){
            $headerid = DB::table('assignsubj')
                                    ->insertGetId([
                                        'glevelid'=>$levelid->levelid,
                                        'sectionid'=>$request->get('section'),
                                        'syid'=> $activesy->id,
                                        'deleted'=>'0',
                                        'createdby'=>auth()->user()->id,
                                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

        }else{
            $headerid = $check_classched[0]->ID;
        }


        $check_detail = DB::table('assignsubjdetail')
                            ->where('headerid',$headerid)
                            ->where('subjid',$subjid)
                            ->where('deleted',0)
                            ->get();

        if(count($check_detail) == 0){
            DB::table('assignsubjdetail')->insert([
                'headerid'=> $headerid,
                'subjid'=>$subjid,
                'teacherid'=>$teacherid,
                'deleted'=>'0',
                'createdby'=>auth()->user()->id,
                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);

        }else{
            DB::table('assignsubjdetail')
                ->where('headerid',$headerid)
                ->where('subjid',$subjid)
                ->where('deleted',0)
                ->take(1)
                ->update([
                    'teacherid'=>$teacherid,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
        }

        return 'success';

        // return SPP_ClassSchedule::evaluateInsertedInformationv2($request); 
       
    }

    public function principalupdateshclassschedulejhs(Request $request){

        return SPP_ClassSchedule::updateClassSchedJNS($request);

    }

    public function searchschedulebyday(Request $request){

        return ClassSched::filterClassSchedule($request->get('section'),$request->get('days'));
    }

    public function searchshschedulebyday(Request $request){

        $sectionInfo = SPP_Section::getSeniorHighSectionInfo($request->get('section'));

        return SPP_SHClassSchedule::searchshschedulebyday($sectionInfo->id,$sectionInfo->blockid,$request->get('days'));

    }
    public function storeshclassschedule(Request $request){
      
        return SPP_SHClassSchedule::storeshclassschedulev2($request);
    }

    public function updateshclassschedule(Request $request){


        return SPP_SHClassSchedule::updateshclassschedule($request);

    }

    //------------------------------------ Class Schedule ------------------------------------------------


    //------------------------------------ Fixed Schedule ------------------------------------------------

    public function principalViewFixedSchedules(Request $request){

        return view('principalsportal.pages.error500');

    }

    public function principalOverrideFixedSchedules(Request $request){

        return view('principalsportal.pages.error500');

    }

    //------------------------------------ Fixed Schedule ------------------------------------------------


    public function studentpromotion(){

        $student =  SPP_EnrolledStudent::getStudent(null,null,null,null,3);

        return view('principalsportal.pages.promotions.viewpromotions')->with('count','-1');
                 
    }

    public function searchstudentpromotion(Request $request){

        $student =  SPP_EnrolledStudent::getStudent($request->get('pagenum'),10,null,$request->get('data'),Crypt::decrypt($request->get('apid')));

        $promotionsummary = collect(SPP_StudentPromotion::getstudentPromotion($student, Crypt::decrypt($request->get('apid'))))->sortBy('name');

        return view('search.principal.promotions')
                    ->with('finalGrades',$promotionsummary)
                    ->with('apid',$request->get('apid'))
                    ->with('count',$student[0]->count);

    }

    public static function getPromotionSummary(Request $request){

        $student =  SPP_EnrolledStudent::getStudent(null,null,null,null,Crypt::decrypt($request->get('apid')));

        $promotionsummary = SPP_StudentPromotion::getstudentPromotion($student,Crypt::decrypt($request->get('apid')), true);

        return  $promotionsummary ;

    }




    public static function promoteallstudents($id){

        $students = SPP_EnrolledStudent::getStudent(null,null,null,null,3);

        $student =  SPP_EnrolledStudent::getStudent(null,null,null,null,Crypt::decrypt($id));

        SPP_StudentPromotion::promoteStudents($student,Crypt::decrypt($id));

        toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                
        return '1';
      

    }


    public static function changeSchoolYear($id){


        $currentSchoolYear = DB::table('sy')->where('id',Crypt::decrypt($id))->first();

        Session::put('schoolYear',$currentSchoolYear);

        SPP_Session::principalSession();

        return redirect()->route('home'); 

    }
    
    public static function changeSemester($id){

        $currentSem = DB::table('semester')->where('id',Crypt::decrypt($id))->first();

        Session::put('semester',$currentSem);

        SPP_Session::principalSession();

        return redirect()->route('home'); 

    }



    public function principalallviewrequest(){

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
                    'sy.sydesc',
                    'perreq.createddatetime',
                    'perreq.reqid'
                )
                ->orderBy('perreq.createddatetime')
                ->where('perreqdetail.approvedby',auth()->user()->id)
                ->get();

        // return  $req;

        $countPendingStatus = collect($req)->where('status','0')->count();

        return view('principalsportal.pages.permissionrequest.viewallperreq')
                    ->with('perreq',$req)
                    ->with('countPendingStatus',$countPendingStatus);

    }

    public function updateResponse(Request $request){

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        DB::table('perreqdetail')
            ->where('id',Crypt::decrypt($request->get('perreq')))
            ->update([
                'response'=>Crypt::decrypt($request->get('response')),
                'updatedby'=>auth()->user()->id,
                'updateddatetime'=>$date 
            ]);
        
        $headerid = DB::table('perreqdetail')->where('id',Crypt::decrypt($request->get('perreq')))->select('headerid')->first();

        $countRequest = DB::table('perreqdetail')->where('headerid',$headerid->headerid)->count();

        $countResponse = DB::table('perreqdetail')->where('headerid',$headerid->headerid)->where('response',1)->count();

        if( $countResponse == $countRequest){

            DB::table('perreq')->where('id',$headerid->headerid)->update(['status'=>'1']);

        }

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
                        ->where('perreqdetail.response','0')
                        ->count();

        if($req == 0){
            $request->session()->forget('requestCount');
        }
        else{
            Session::put('requestCount', $req);
        }

        return back();
    }


    public function viewsubjectInfo($id, $acadid){

        $checkVersion = GradingSystem::checkVersion();
        
        if(!isset($checkVersion->version)){

            return back();

        }

        try{
            $id =  Crypt::decrypt($id);
        }catch(\Exception $e){
            $id =  Crypt::encrypt($id);
        }

      

        $id =  Crypt::encrypt($id);
       
        $subjStrand = DB::table('sh_subjstrand')
                        ->join('sh_strand',function($join){
                            $join->on('sh_subjstrand.strandid','=','sh_strand.id');
                        })
                        ->where('sh_subjstrand.deleted',0)
                        ->where('subjid',Crypt::decrypt($id))
                        ->select('sh_subjstrand.*','sh_strand.strandcode')
                        ->get();

        $grade_level = SPP_Gradelevel::getGradeLevel(null,null,null,null,$acadid);

        $subject_info = SPP_Subject::getAllSubject(null,null,Crypt::decrypt($id),null,$acadid);

        $did = Crypt::decrypt($acadid);
       
        $subjects = SPP_Subject::getAllSubject(null,10,null,null,$id);

        $acadprogs = DB::table('academicprogram')
                    ->where('academicprogram.id', $did)
                    ->get();

        if($subject_info[0]->count == 0){

            return view('principalsportal.pages.management.subject.subjectinformation')
                ->with('deleted',true)
                ->with('acadprogs', $acadprogs);

        }

       

        $subject_info[0]->data[0]->usage = SPP_Subject::getSubjectUsage( null,Crypt::decrypt($id),Crypt::decrypt($acadid));

        if($checkVersion->version == 'v1'){

            $grades_setup =  SPP_GradeSetup::getAllGradeSetup(null,null,null,null,Crypt::decrypt($acadid),Crypt::decrypt($id));

        }
        else if($checkVersion->version == 'v2'){

            $grades_setup = \App\Models\Grading\SubjectAssignment::subjectAssignment($subject_info[0]->data[0]->id , $did);

            $grades_setup = collect($grades_setup)->groupby('gsdesc');

        }

        if($checkVersion->version == 'v1'){

            return view('principalsportal.pages.management.subject.subjectinformation')
                            ->with('subject_info',$subject_info[0]->data)
                            ->with('grade_level',$grade_level)
                            ->with('acadid',$acadid)
                            ->with('grades_setup',$grades_setup)
                            ->with('deleted',false)
                            ->with('acadprogs', $acadprogs)
                            ->with('subjStrand',$subjStrand);

        }
        else if($checkVersion->version == 'v2'){

            return view('principalsportal.pages.management.subject.subj_info_v2')
                        ->with('subject_info',$subject_info[0]->data)
                        ->with('grade_level',$grade_level)
                        ->with('acadid',$acadid)
                        ->with('grades_setup',$grades_setup)
                        ->with('deleted',false)
                        ->with('acadprogs', $acadprogs)
                        ->with('subjStrand',$subjStrand);


        }

        
                        
    }

    public static function principalAwardsAndRecognitions(){

        $data = array((object)['data'=>null,'count'=>-1]);
        return view('principalsportal.pages.awards.academicexcellenceaward')->with('data',$data);

    }

    public function searchStudentWithHonors(Request $request){
        $semid = $request->get('semid');
        $syid = $request->get('sy');
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $strandid = $request->get('strand');
        if($gradelevel == 14 || $gradelevel == 15){
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects_sh($gradelevel,$strandid,$syid);
        }else{
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel);
        }


        if($section == null){
            $sections = DB::table('sections')
                            ->where('levelid',$gradelevel)
                            ->where('deleted',0)
                            ->select(
                                'id',
                                'sectionname'
                            )
                            ->get();
            $gradelevel_students = array();
            foreach($sections as $section_item){
                $request->request->add(['section' => $section_item->id]);
                $temp_students = \App\Http\Controllers\TeacherControllers\TeacherGradingV4::get_student_data($request);
                foreach($temp_students as $student){
                    array_push($gradelevel_students,$student);
                }
            }
            $students = collect($gradelevel_students);
        }else{
            $students = \App\Http\Controllers\TeacherControllers\TeacherGradingV4::get_student_data($request);

            $gradelevel_students = array();
            $temp_students = array();
            if($strandid != null){
                foreach($students as $student){
                    if($student->student != "SUBJECTS"){
                        if(isset($student->strand)){
                            if($strandid == $student->strand){
                                array_push($gradelevel_students,$student);
                                array_push($temp_students,$student);
                            }
                        }
                    }else{
                        array_push($temp_students,$student);
                    }
                   
                }
                $students = $temp_students;
            }
            
            


        }
      
        $version = "V5";
        return $students;
    }
    

    public function viewsection($section){

            $sectioninfo = DB::table('sections')
                            ->join('gradelevel',function($join){
                                $join->on('sections.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted','0');
                            })
                            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                            ->select('sections.id', 'gradelevel.acadprogid')
                            ->where('sections.sectionname',strtoupper(str_replace('-',' ',$section)))
                            ->first();

            // return $sectioninfo;

            $accessGranted = false;

            foreach(Session::get('principalInfo') as $item){
                if($item->acadid == $sectioninfo->acadprogid){
                    $accessGranted = true;
                }
            }

            
            if(!$accessGranted){
                return redirect('principalPortalSchedule');
            }

            $blockassignment = DB::table('sh_sectionblockassignment')
                                    ->join('sh_block',function($join){
                                        $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                        $join->where('sh_block.deleted','0');
                                    });
                        
            if(Session::has('schoolYear')){
                $blockassignment->join('sy',function($join) {
                                    $join->on('sy.id','=','sh_sectionblockassignment.syid');
                                    $join->where('sy.id',Session::get('schoolYear')->id);
                                });
                
            }
            else{
                $blockassignment->join('sy',function($join){
                        $join->on('sy.id','=','sh_sectionblockassignment.syid');
                        $join->where('sy.isactive','1');
                    });
            }
                    
            $blockassignment = $blockassignment->where('sh_sectionblockassignment.sectionid',$sectioninfo->id)
                    ->where('sh_sectionblockassignment.deleted','0')
                    ->select(
                        'sh_block.*',
                        'sh_sectionblockassignment.blockid'
                    )
                    ->get();

        $enrolledstud = SPP_EnrolledStudent::getStudent(null,null,null,null,$sectioninfo->acadprogid,$sectioninfo->id,null,null,null,null,null,'namId');

        $studentusage = SPP_Section::sectionUsage(0);

        return view('principalsportal.pages.section.sectioninfo')
                    ->with('scheds',ClassSched::dayswithbgcolors())
                    ->with('enrolledstud',$enrolledstud)
                    ->with('status','Subjects Added')
                    ->with('sectionInfo',SPP_Section::getSeniorHighSectionInfo($sectioninfo->id))
                    ->with('blockassignment',$blockassignment);
                    
    }

    public function getsectionsubject($section){

        $sectioninfo = DB::table('sections')
                        ->select('sections.id')
                        ->where('sections.sectionname',strtoupper(str_replace('-',' ',$section)))
                        ->first();

        $subjects = SPP_Subject::getSubject(null,null,null,$sectioninfo->id);

        $subjects = $subjects[0]->data;

        foreach($subjects as $item){

            $gradestatus = DB::table('grades')
                            ->where('grades.deleted','0')
                            ->where('sectionid',$sectioninfo->id)
                            ->where('subjid',$item->id);
                          
            if(Session::has('schoolYear')){

                $gradestatus->join('sy',function($join) {
                                    $join->on('sy.id','=','grades.syid');
                                    $join->where('sy.id',Session::get('schoolYear')->id);
                                });
                
            }
            else{
                
                $gradestatus->join('sy',function($join){
                        $join->on('sy.id','=','grades.syid');
                        $join->where('sy.isactive','1');
                    });

            }

            $gradestatus->select(
                            'grades.quarter',
                            'grades.status',
                            'grades.id as gradeid',
                            'grades.submitted'
                         );
                        

            $item->gradestatus = $gradestatus->get();
        }

        return view('principalsportal.pages.section.sectionsubjecttable')->with('classassignsubj',$subjects);

    }

    public function removesectionschedule($section,$dataid){

        $sectioninfo = DB::table('sections')
                        ->select('sections.id')
                        ->where('id',$section)
                        // ->where('sections.sectionname',strtoupper(str_replace('-',' ',$section)))
                        ->first();

        $schdetail = DB::table('classscheddetail')
                                ->join('classsched',function($join){
                                    $join->on('classscheddetail.headerid','=','classsched.id');
                                })
                                ->select('headerid','stime','etime','classsched.sectionid','classsched.id','classsched.subjid','classification')
                                ->where('classscheddetail.id',$dataid)
                                ->first();

        if($sectioninfo->id == $schdetail->sectionid){

            DB::table('classscheddetail')
                    ->where('headerid',$schdetail->headerid)
                    ->where('stime',$schdetail->stime)
                    ->where('etime',$schdetail->etime)
                    ->where('classification',$schdetail->classification)
                    ->update(['deleted'=>'1']);

            $count = DB::table('classscheddetail')
                        ->where('headerid',$schdetail->headerid)
                        ->where('deleted','0')
                        ->count();

            if($count==0){

                DB::table('classsched')
                        ->where('id',$schdetail->id)
                        ->update(['deleted'=>'1']);

                $subject = $schdetail->subjid;

                DB::table('assignsubj')
                    ->where('sectionid',$schdetail->sectionid)
                    ->join('sy',function($join){
                        $join->on('assignsubj.syid','=','sy.id');
                        $join->where('isactive','1');
                    })
                    ->join('assignsubjdetail',function($join) use($subject){
                        $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                        $join->where('assignsubjdetail.subjid',$subject);
                    })
                    ->update(['assignsubjdetail.deleted'=>'1']);

                $asssubj = DB::table('assignsubj')
                        ->where('sectionid',$schdetail->sectionid)
                        ->join('sy',function($join){
                            $join->on('assignsubj.syid','=','sy.id');
                            $join->where('isactive','1');
                        })
                        ->join('assignsubjdetail',function($join) {
                            $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                            $join->where('assignsubjdetail.deleted','0');
                        })->count();

                if($asssubj == 0){
                    DB::table('assignsubj')
                        ->where('sectionid',$schdetail->sectionid)
                        ->join('sy',function($join){
                            $join->on('assignsubj.syid','=','sy.id');
                            $join->where('isactive','1');
                        })
                        ->update(['assignsubj.deleted'=>'1']);
                }
            }
        }

    }

    public function sectionschedule($section){

            $sectioninfo = DB::table('sections')
                            ->join('gradelevel',function($join){
                                $join->on('sections.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted','0');
                            })
                            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                            ->select('sections.id', 'gradelevel.acadprogid')
                            ->where('sections.sectionname',strtoupper(str_replace('-',' ',$section)))
                            ->first();

            $schedule = SPP_Subject::getSchedule(null,null,$sectioninfo->id);

            $schedulesummary = array();
 
            $firstgroup =  collect($schedule)->groupBy('subjdesc');

            foreach($firstgroup  as $item){

                $secondgroup = collect($item)->groupby('time')->sortBy('days');

                foreach($secondgroup  as $itemsecond){

                    $dayString = '';
                    $data = null;

                    foreach($itemsecond  as $itemThird){
                        $data = $itemThird;
                        $dayString.= substr($itemThird->description, 0,3).' / ';

                    }

                    $dayString = substr($dayString, 0 , -2);

                    array_push($schedulesummary,(object)[
                        'daysum'=>$dayString,
                        'subjinfo'=>$data
                    ]);

                }

            }

            return view('principalsportal.pages.section.sectionscheduletable')
                            ->with('sectionInfo',$sectioninfo)
                            ->with('testingSched',$schedulesummary);


    }

     public function removeshsectionschedule($section,$dataid){

        $sectioninfo = DB::table('sections')
                            ->select('sections.id')
                            ->where('id',$section)
                            ->first();

        $schdetail = DB::table('sh_classscheddetail')
                            ->select('headerid','stime','etime','sectionid','classification')
                            ->join('sh_classsched',function($join){
                                $join->on('sh_classscheddetail.headerid','=','sh_classsched.id');
                            })
                            ->where('sh_classscheddetail.id',$dataid)
                            ->first();

        if($sectioninfo->id == $schdetail->sectionid){

            DB::table('sh_classscheddetail')
                    ->where('headerid',$schdetail->headerid)
                    ->where('stime',$schdetail->stime)
                    ->where('etime',$schdetail->etime)
                    ->where('classification',$schdetail->classification)
                    ->update(['deleted'=>'1']);

            $count = DB::table('sh_classscheddetail')
                        ->where('headerid',$schdetail->headerid)
                        ->where('deleted','0')
                        ->count();

            if($count==0){

                DB::table('sh_classsched')
                        ->where('id',$schdetail->headerid)
                        ->update(['deleted'=>'1']);
            
            }

        }


        // return back();


    }




    public function fixgradeinfo(){


        DB::table('grades')
                    ->join('gradelevel',function($join){
                        $join->on('grades.levelid','=','gradelevel.id');
                        $join->whereIn('gradelevel.acadprogid',['2','3','4']);
                    })
                    ->update([
                        'semid'=>1
                    ]);


    }
    

    public function studentgrades($acadprogid){

        $students = SPP_EnrolledStudent::getStudent(null,null,null,null,$acadprogid);

        foreach($students[0]->data as $student){

            if($acadprogid != 5){

                $grades =  DB::table('gradesdetail')
                            ->where('studid',$student->id)
                            ->join('grades',function($join){
                                $join->on('gradesdetail.headerid','=','grades.id');
                            })
                            ->join('sy',function($join){
                                $join->on('grades.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->where('grades.semid','1')
                            ->get();

            }
            else{


                $grades =  DB::table('gradesdetail')
                            ->where('studid',$student->id)
                            ->join('grades',function($join){
                                $join->on('gradesdetail.headerid','=','grades.id');
                            })
                            ->join('sy',function($join){
                                $join->on('grades.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->get();
            }


  
            
            foreach($grades as $gradeItems){

                $quarter = 0;

                if($gradeItems->quarter == 1){
                    $quarter = 'q1';
                }
                elseif($gradeItems->quarter == 2){
                    $quarter = 'q2';
                }
                elseif($gradeItems->quarter == 3){
                    $quarter = 'q3';
                }
                else{
                    $quarter = 'q4';
                }

                if($acadprogid != 5){

                    DB::table('tempgradesum')
                            ->updateOrInsert (
                                [
                                    'studid'=>$student->id, 
                                    'subjid'=>$gradeItems->subjid,
                                    'syid'=>$gradeItems->syid,
                                    'semid'=>$gradeItems->semid, 
                                ],
                                [
                                    $quarter=>$gradeItems->qg,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>Carbon::now('Asia/Manila')
                                ]
                            );

                    DB::table('tempgradesum')
                            ->where('subjid',$gradeItems->subjid)
                            ->where('syid',$gradeItems->syid)
                            ->where('studid',$student->id)
                            ->where('semid','2')
                            ->delete();


                }
                else{


                    DB::table('tempgradesum')
                    ->updateOrInsert (
                        [
                            'studid'=>$student->id, 
                            'subjid'=>$gradeItems->subjid,
                            'syid'=>$gradeItems->syid,
                            'semid'=>$gradeItems->semid, 
                        ],
                        [
                            $quarter=>$gradeItems->qg,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>Carbon::now('Asia/Manila')
                        ]
                    );

                }

            } 


        }

        return "done";

    }

    public function announcementDetail(Request $request){

        $annoucement = DB::table('announcements')
                            ->where('createdby',auth()->user()->id);


        if($request->has('annID') && $request->get('annID') != null){

            $annoucement = $annoucement->where('id',$request->get('annID'));

        }
        
        if($request->has('send') && $request->get('send') == 'send'){

            $annoucement = $annoucement->get();

            if($request->get('type') == 1){

                if($request->has('receiverid') && $request->get('receiverid') != null && 
                    $request->has('annID') && $request->get('annID') != null){

                        DB::table('notifications')
                                ->insert([
                                    'headerid'=> $annoucement[0]->id,
                                    'type'=>1,
                                    'recieverid'=>$request->get('receiverid')
                                ]);

                }

            }
            else if($request->get('type') == 2){

                $contents = array();

                if($request->has('receiverid') && $request->get('receiverid') != null && 
                $request->has('annID') && $request->get('annID') != null){

                    $contactno = null;

                    $contents = array();

                    if(strlen($annoucement[0]->content) > 160){

                        array_push($contents , substr($annoucement[0]->content, 0, 160));
                        array_push($contents , substr($annoucement[0]->content, 160, 320));
                     
                    }
                    else{

                        array_push($contents,$annoucement[0]->content);

                    }

                    if(substr($request->get('phonenumber'), 0,1)=='0')
                    {
                        $contactno = '+63' . substr($request->get('phonenumber'), 1);
                    }

                    if($request->geT('recievertype') == 'sendStudent'){

                        $recievertype = 1;

                    }
                    elseif($request->geT('recievertype') == 'sendTeacher'){

                        $recievertype = 2;

                    }
                    elseif($request->geT('recievertype') == 'sendParent'){

                        $recievertype = 3;
                        
                    }

                        $currentDay = \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD');

                        foreach($contents as $contentitem){

                            $countIfExist = DB::table('smsbunkertextblast')
                                                    ->where('studid',$request->get('receiverid'))
                                                    ->where('messageid',$request->get('annID'))
                                                    ->whereDate('createddatetime', '=', \Carbon\Carbon::today()->toDateString())
                                                    ->count();

                            if($countIfExist <= 2){

                                DB::table('smsbunkertextblast')
                                        ->insert([
                                            'message'=> $contentitem,
                                            'receiver'=>$contactno,
                                            'smsstatus'=>0,
                                            'messageid'=>$request->get('annID'),
                                            'createdby'=>auth()->user()->id,
                                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                            'studid'=>$request->get('receiverid'),
                                            'receivertype'=>$recievertype
                                        ]);

                            }

                        }

                    // }

                }


            }

          

        }
        else if($request->has('compose') && $request->get('compose') == 'compose'){

            DB::table('announcements')
                    ->updateOrInsert(
                        [
                            'id'=>$request->get('messageID'),
                            'createdby'=>auth()->user()->id,
                            
                        ],
                        [
                        'title'=>$request->get('title'),
                        'content'=>$request->get('content'),
                        'recievertype'=>'1',
                        'created_at'=>\Carbon\Carbon::now('Asia/Manila'),
                    ]);

        }
        else{

            if(count($annoucement->get()) == 0){

                return 'Announcement not found';
    
            }
    
    
            return $annoucement->orderBy('id','desc')->get();

        }

      

    }

    public function teacherdetails(Request $request){

        $activeSy = DB::table('sy')->where('isactive',1)->first()->id;
        $activeSem = DB::table('semester')->where('isactive',1)->first()->id;

        $teacher = DB::table('teacheracadprog')
                    ->where('teacheracadprog.deleted',0)
                    ->where('teacheracadprog.syid',$activeSy)
                    ->join('teacher',function($join){
                        $join->on('teacheracadprog.teacherid','=','teacher.id');
                        $join->where('teacher.deleted',0);
                        $join->where('isactive',1);
                    })
                    ->select(
                        'teacher.firstname',
                        'teacher.lastname',
                        'teacher.userid',
                        'teacher.usertypeid',
                        'teacher.id');

        if(auth()->user()->type == 2){

            $teacherInfo = DB::table('teacher')->where('userid',auth()->user()->id)->first();

            $acadprog = DB::table('academicprogram')->get()->where('principalid',$teacherInfo->id);

            $acadprog = collect($acadprog)->map(function ($acadprog) {
                return $acadprog->id;
            })->toArray();

            $teacher = $teacher->whereIn('teacheracadprog.acadprogid',$acadprog);

        }

        if( ( $request->has('announcement') && $request->get('announcement') != null ) ){
    
            if(( $request->has('annID') && $request->get('annID') != null )){

                    $teacher = $teacher->leftJoin('notifications',function($join) use($request){
                                    $join->on('teacher.userid','=','notifications.recieverid');
                                    $join->where('notifications.type','=',1);
                                    $join->where('notifications.deleted',0);
                                    $join->where('notifications.headerid',$request->get('annID'));
                                })
                                ->addSelect('notifications.status');
                    
            }
            else{

                return "Announcement ID is required!";

            }


        }

        $teacher = $teacher->orderby('lastname')->distinct()->get();

        foreach($teacher as $key=>$item){

            if($item->usertypeid != 1){

                $checkWithPriv = DB::table('faspriv')
                                    ->where('userid',$item->userid)
                                    ->where('usertype',1)
                                    ->count();

                if($checkWithPriv == 0){

                    unset($teacher[$key]);

                }

            }

            
        }
        
        return $teacher;

    }


    public function checktextblaststatus(Request $request){

        $nowdate = \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD');

        $status = DB::table('smsbunkertextblast')
                    ->whereDate('createddatetime', '=', $nowdate)
                    ->where('createdby',auth()->user()->id);

        if($request->has('recievertype') && $request->get('recievertype') != null){

            $status = $status->where('receivertype',$request->get('recievertype'));

        }

        return $status->count();

     


    }


    public function parentdetails(Request $request){


        $parents = DB::table('users')->where('type',9)->get();

        return $parents;

    }

    public function calendarsetup(){

        return view('principalsportal.pages.management.calendar.calendarsetup')->with('data',SPP_Calendar::getHoliday(null,10));

    }


    public function sectionScheduleV2(Request $request){
        $strand = array();
        if($request->get('table') == 'table' && $request->has('table')){
            $schedulesummary = array();
            if($request->get('section') != null && $request->has('section') || $request->get('block') != null && $request->has('block')){
                $schedule = SPP_Subject::getSchedule(null,null,$request->get('section'),$request->get('syid'),$request->get('semid'),$request->get('block'));
                $schedulesummary = array();
                $firstgroup =  collect($schedule)->groupBy('subjdesc');
                $firstgroup =  collect($schedule)->groupBy('classification');
                foreach($firstgroup  as $item){
                    $subjGroup = collect($item)->groupBy('subjdesc');
                    foreach($subjGroup  as $subjGroupitem){
                        $secondgroup = collect($subjGroupitem)->groupby('time')->sortBy('days');
                        foreach($secondgroup  as $itemsecond){
                            $dayString = '';
                            $data = null;
                            foreach($itemsecond  as $itemThird){
                                $data = $itemThird;
                                $dayString.= substr($itemThird->description, 0,3).' / ';
                            }
                            $dayString = substr($dayString, 0 , -2);
                            array_push($schedulesummary,(object)[
                                'daysum'=>$dayString,
                                'subject'=>$itemThird->subjcode,
                                'subjid'=>$itemThird->subjid,
                                'subjinfo'=>$data,
                            ]);
                        }
                    }
                }
            }   
            $schedulesummary = collect($schedulesummary)->sortBy('subject');
            if($request->get('acadid') == 5 && $request->has('acadid')){
                $strand = DB::table('sh_strand')->where('id',$request->get('strand'))->first();
                if($request->get('semid') != null){
                    $activeSem = DB::table('semester')->where('id',$request->get('semid'))->first();
                }
                else if(Session::has('semester')){
                        $activeSem = Session::get('semester');
                }else{
                    $activeSem = DB::table('semester')->where('isactive',1)->first();
                }
                
                if($request->get('syid') != null){
                    $activesy = DB::table('sy')->where('id',$request->get('syid'))->first();
                }
                else if(Session::has('schoolYear')){
                    $activesy = Session::get('schoolYear');
                }else{
                    $activesy= DB::table('sy')->where('isactive',1)->first();
                }

                if($request->get('section') != null && $request->has('section')){
                    $subjects = DB::table('sh_subjects')
                                ->leftJoin('sh_subjstrand',function($query) use($request) {
                                    if($request->get('strand') != null && $request->has('strand')){
                                        $query->on('sh_subjects.id','=' ,'sh_subjstrand.subjid');
                                        $query->where('sh_subjstrand.strandid', $request->get('strand'));
                                        $query->where('sh_subjstrand.deleted',0);
                                    }
                                    else{
                                        $query->on('sh_subjects.id','=' ,'sh_subjstrand.subjid');
                                        $query->where('sh_subjstrand.deleted',0);
                                    }
                                })
                                ->where('sh_subjects.semid', $activeSem->id)
                                ->where(function($query) use ($request){
                                    if($request->get('strand') != null && $request->has('strand')){
                                        $query->where('sh_subjects.type',3);
                                        $query->whereNotNull('sh_subjstrand.id');
                                    }
                                    else{
                                        $query->where('sh_subjects.type',1);
                                        $query->whereNull('sh_subjstrand.id');
                                    }
                                })
                                ->select('sh_subjects.id','sh_subjects.subjtitle as subjdesc','sh_subjects.subjcode','sh_subjects.type','sh_subjects.acadprogid','sh_subjstrand.id as subjstrandid')
                                ->where('sh_subjects.deleted',0)
                                ->orderBy('sh_subjects.sh_subj_sortid')
                                ->orderBy('sh_subjects.type')
                                ->orderBy('sh_subjects.subjcode')
                                ->get();
 
                    foreach($subjects as $key=>$item){
                        $checkForSetup = DB::table('gradessetup')
                                            ->where('subjid',$item->id)
                                            ->where('deleted',0)
                                            ->where('levelid',$request->get('levelid'))
                                            ->count();
                        if( $checkForSetup == 0){
                            unset($subjects[$key]);
                        }
                    }  
                    
                   
                }

                if($request->get('block') != null && $request->has('block')){
                    $subjects = DB::table('sh_subjects')
                                ->join('sh_subjstrand',function($query) use($request){
                                    $query->on('sh_subjects.id','=' ,'sh_subjstrand.subjid');
                                    $query->where('sh_subjstrand.strandid', $request->get('strand'));
                                    $query->where('sh_subjstrand.deleted',0);
                                })
                                ->where('sh_subjects.semid', $activeSem->id)
                                ->where('sh_subjects.type',2)
                                ->where('sh_subjects.deleted',0)
                                ->select(
                                    'sh_subjects.id',
                                    'sh_subjects.subjtitle as subjdesc',
                                    'sh_subjects.subjcode',
                                    'sh_subjects.type',
                                    'sh_subjects.acadprogid'
                                    )
                                ->orderBy('sh_subjects.sh_subj_sortid')
                                ->orderBy('sh_subjects.type')
                                ->orderBy('sh_subjects.subjcode')
                                ->get();
                    foreach($subjects as $key=>$item){
                        if($request->get('levelid') != null && $request->has('levelid')){
                            $checkForSetup = DB::table('gradessetup')
                                                    ->where('subjid',$item->id)
                                                    ->where('syid',$activesy->id)
                                                    ->where('deleted',0)
                                                    ->where('levelid',$request->get('levelid'))
                                                    ->count();
                            if( $checkForSetup == 0){
                                unset($subjects[$key]);
                            }
                        }
                    }   
                }
            }
            else{
                $subjects = DB::table('subjects')
                                ->where('subjects.acadprogid',$request->get('acadid'))
                                ->where('deleted',0)
                                ->get();
            }

            if($request->get('section') != null && $request->has('section')){
               
                return view('principalsportal.pages.section.sectionscheduletable')
                            ->with('schedulesummary',$schedulesummary)
                            ->with('acadprogid',$request->get('acadid'))
                            ->with('strand',$strand)
                            ->with('subjects',$subjects);
            }

           if($request->get('block') != null && $request->has('block')){
                // return $
                return view('principalsportal.pages.management.block.blockschedtable')
                        ->with('schedulesummary',$schedulesummary)
                        ->with('acadprogid',$request->get('acadid'))
                        ->with('strand',$strand)
                        ->with('subjects',$subjects);
           }
        }
    }

    public function subjectstrand(Request $request){

        $subjectStrand = DB::table('sh_subjstrand')
                            ->where('deleted','0');

        if($request->get('subject') != null && $request->has('subject')){

            $subjectStrand = $subjectStrand->where('subjid',$request->get('subject'));

        }

        if($request->get('create') != null && $request->has('create')){

            if($request->get('sujbstrand') != null && $request->has('sujbstrand')){

                foreach($request->get('sujbstrand') as $item){
                    
                    $subjcount = DB::table('sh_subjstrand')
                                            ->where('deleted','0')
                                            ->where('subjid',$request->get('subject'))
                                            ->where('strandid',$item)->count();

                    

                    if($subjcount == 0){

                        DB::table('sh_subjstrand')
                                    ->insert([
                                        'subjid'=>$request->get('subject'),
                                        'strandid'=>$item,
                                        'createdby'=>auth()->user()->id,
                                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                    }

                }

            }

            foreach($subjectStrand->get() as $item){

                if(!collect($request->get('sujbstrand'))->contains($item->strandid)){

                    DB::table('sh_subjstrand')
                            ->where('id',$item->id)
                            ->update([
                                'deleted'=>1,
                                'deletedby'=>auth()->user()->id,
                                'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                            
                }
              

            }

           

            toast('Section successfully created','success')->autoClose(2000)->toToast($position = 'top-right');
            return back();

        }
    }

    public function updateSubjectTeacher(Request $request){

        $section = DB::table('sections')
                        ->where('sections.id',$request->get('section'))
                        ->join('gradelevel',function($join){
                            $join->on('sections.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                        })
                        ->where('sections.deleted',0)
                        ->select('sections.id','gradelevel.acadprogid')
                        ->first();

        if(isset($section->id)){

            if($section->acadprogid == '2' || $section->acadprogid == '3' || $section->acadprogid == '4'){

                        DB::table('assignsubj')
                            ->join('sy',function($join){
                                $join->on('assignsubj.syid','=','sy.id');
                                $join->where('isactive','1');
                            })
                            ->join('assignsubjdetail',function($join) {
                                $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                                $join->where('assignsubjdetail.deleted','0');
                            })
                            ->where('sectionid',$section->id)
                            ->where('assignsubjdetail.subjid',$request->get('subject'))
                            ->update([
                                'assignsubjdetail.teacherid'=>$request->get('teacher'),
                                'assignsubjdetail.updatedby'=>auth()->user()->id
                            ]);
                          

            }
            elseif($section->acadprogid == 5){


                $subjInfo = Db::table('sh_subjects')
                                ->where('id',$request->get('subject'))
                                ->where('deleted',0)
                                ->first();


                if(isset($subjInfo->id)){

                    if($subjInfo->type == 2){

                    }
                    else if($subjInfo->type == 1 || $subjInfo->type == 3){

                       DB::table('sh_classsched')
                            ->join('sy',function($join){
                                $join->on('sh_classsched.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->join('semester',function($join){
                                $join->on('sh_classsched.semid','=','semester.id');
                                $join->where('semester.isactive','1');
                            })
                            ->where('sectionid',$section->id)
                            ->where('sh_classsched.deleted',0)
                            ->where('sh_classsched.subjid',$request->get('subject'))
                            ->update([
                                'sh_classsched.teacherid'=>$request->get('teacher'),
                                'sh_classsched.updatedby'=>auth()->user()->id
                            ]);

                    }


                }
              
            }

        }

    }

    //02022021
    public function getSubjSchedInfo(Request $request){
        if($request->get('semid') != null){
            $activeSem = DB::table('semester')->where('id',$request->get('semid'))->first();
        }
        else if(Session::has('semester')){
            $activeSem = Session::get('semester');
        }else{
            $activeSem = DB::table('semester')->where('isactive',1)->first();
        }
        if($request->get('syid') != null){
            $activeSy = DB::table('sy')->where('id',$request->get('syid'))->first();
        }
        else if(Session::has('schoolYear')){
            $activeSy = Session::get('schoolYear');
        }else{
            $activeSy= DB::table('sy')->where('isactive',1)->first();
        }
        if($request->get('type') != null && $request->has('type')){
            if($request->get('view') == 'view' && $request->has('view')){
                if($request->get('type') == 'seniorhigh'){
                    $data = array((object)[
                        'teacherid'=>null,
                        'stime'=>null,
                        'etime'=>null,
                        'days'=>null,
                        'classification'=>null,
                        'room'=>null
                    ]);
                    $sched = DB::table('sh_classsched')
                                ->join('sh_classscheddetail',function($join) use($request){
                                    $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                                    $join->where('sh_classscheddetail.deleted',0);
                                    $join->where('sh_classscheddetail.id',$request->get('detailid'));
                                })
                                ->where('syid',$activeSy->id)
                                ->where('semid',$activeSem->id)
                                ->where('sh_classsched.deleted',0)
                                ->where('sectionid',$request->get('section'))
                                ->where('subjid',$request->get('subject'))
                                ->select(
                                    'headerid',
                                    'teacherid',
                                    'roomid',
                                    'stime',
                                    'etime',
                                    'classification'
                                )
                                ->first();
                    if(isset($sched->headerid) > 0){
                        $days = DB::table('sh_classscheddetail')
                                ->where('sh_classscheddetail.deleted',0)
                                ->where('roomid',$sched->roomid)
                                ->where('headerid',$sched->headerid)
                                ->where('stime',$sched->stime)
                                ->where('etime',$sched->etime)
                                ->where('classification',$sched->classification)
                                ->select(
                                  'day as days'
                                )
                                ->get();
                        $data[0]->teacherid = $sched->teacherid;
                        $data[0]->stime = \Carbon\Carbon::create($sched->stime)->isoFormat('hh:mm A') ;
                        $data[0]->etime = \Carbon\Carbon::create($sched->etime)->isoFormat('hh:mm A') ;
                        $data[0]->classification = $sched->classification;
                        $data[0]->room = $sched->roomid;
                        $data[0]->days =  $days;
                    }
                    return $data;
                }
                else if($request->get('type') == 'juniorhigh'){
                    $data = array((object)[
                        'teacherid'=>null,
                        'stime'=>null,
                        'etime'=>null,
                        'days'=>null,
                        'classification'=>null,
                        'room'=>null
                    ]);
                    $teacher = DB::table('assignsubj')
                                    ->where('syid',$activeSy->id)
                                    ->where('assignsubj.deleted',0)
                                    ->where('assignsubj.sectionid',$request->get('section'))
                                    ->join('assignsubjdetail',function($join) use($request){
                                        $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                                        $join->where('assignsubjdetail.subjid',$request->get('subject'));
                                        $join->where('assignsubjdetail.deleted',0);
                                    })
                                    ->select(
                                        'teacherid'
                                        )
                                    ->get();

                    $schedule = DB::table('classsched')
                        ->where('syid',$activeSy->id)
                        ->where('classsched.deleted',0)
                        ->where('classsched.sectionid',$request->get('section'))
                        ->where('classsched.subjid',$request->get('subject'))
                        ->join('classscheddetail',function($join) use($request){
                            $join->on('classsched.id','=','classscheddetail.headerid');
                            $join->where('classscheddetail.id',$request->get('detailid'));
                            $join->where('classscheddetail.deleted',0);
                        })
                        ->select(
                            'headerid',
                            'days',
                            'classification',
                            'stime',
                            'etime',
                            'roomid'
                        )
                        ->get();


                

                    if(count($teacher) > 0){

                        $data[0]->teacherid = $teacher[0]->teacherid;

                    }
                    if(count($schedule) > 0){


                        $days = DB::table('classscheddetail')
                                    ->where('deleted',0)
                                    ->where('headerid',$schedule[0]->headerid)
                                    ->where('classification',$schedule[0]->classification)
                                    ->where('stime',$schedule[0]->stime)
                                    ->where('etime',$schedule[0]->etime)
                                    ->where('roomid',$schedule[0]->roomid)
                                    ->select('days')
                                    ->get();

                        $data[0]->stime = \Carbon\Carbon::create($schedule[0]->stime)->isoFormat('hh:mm A') ;
                        $data[0]->etime = \Carbon\Carbon::create($schedule[0]->etime)->isoFormat('hh:mm A') ;
                        $data[0]->classification = $schedule[0]->classification;
                        $data[0]->room = $schedule[0]->roomid;
                        $data[0]->days = $days;
                    
                        
                    }

                    return $data;


                }
                else if($request->get('type') == 'block'){

                    $data = array((object)[
                        'teacherid'=>null,
                        'stime'=>null,
                        'etime'=>null,
                        'days'=>null,
                        'classification'=>null,
                        'room'=>null
                    ]);

                    $sched = DB::table('sh_blocksched')
                                ->join('sh_blockscheddetail',function($join) use($request){
                                    $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                                    $join->where('sh_blockscheddetail.deleted',0);
                                    $join->where('sh_blockscheddetail.id',$request->get('detailid'));
                                })
                                ->where('syid',$activeSy->id)
                                ->where('semid',$activeSem->id)
                                ->where('sh_blocksched.deleted',0)
                                ->where('blockid',$request->get('section'))
                                ->where('subjid',$request->get('subject'))
                                ->select(
                                    'headerid',
                                    'teacherid',
                                    'roomid',
                                    'stime',
                                    'etime',
                                    'classification'
                                )
                                ->first();

                    if(isset($sched->headerid) > 0){

                        $days = DB::table('sh_blockscheddetail')
                                ->where('sh_blockscheddetail.deleted',0)
                                ->where('roomid',$sched->roomid)
                                ->where('headerid',$sched->headerid)
                                ->where('stime',$sched->stime)
                                ->where('etime',$sched->etime)
                                ->where('classification',$sched->classification)
                                ->select(
                                  'day as days'
                                )
                                ->get();

                        $data[0]->teacherid = $sched->teacherid;
                        $data[0]->stime = \Carbon\Carbon::create($sched->stime)->isoFormat('hh:mm A') ;
                        $data[0]->etime = \Carbon\Carbon::create($sched->etime)->isoFormat('hh:mm A') ;
                        $data[0]->classification = $sched->classification;
                        $data[0]->room = $sched->roomid;
                        $data[0]->days =  $days;
                    
                    }
                    
                    return $data;


                }

            }
            else if($request->get('update') == 'update'){

                if($request->get('type') == 'seniorhigh'){

                    $sched_detail_info = DB::table('sh_classscheddetail')
                                            ->where('id',$request->get('detailid'))
                                            ->where('deleted',0)
                                            ->select('id','headerid','stime','etime','roomid','day','classification')
                                            ->first();

                    $time = explode(' - ',$request->get('time'));
                    $stime =  $time[0];
                    $etime =  $time[1];

                    $all_sched_detail = DB::table('sh_classscheddetail')
                                            ->where('deleted',0)
                                            ->where('headerid',$sched_detail_info->headerid)
                                            ->where('stime',$sched_detail_info->stime)
                                            ->where('etime',$sched_detail_info->etime)
                                            ->where('roomid',$sched_detail_info->roomid)
                                            ->where('classification',$sched_detail_info->classification)
                                            ->select('id','headerid','stime','etime','roomid','day')
                                            ->get();

                    foreach($request->get('days') as $item){

                        $checkSched = DB::table('sh_classscheddetail')
                                                ->where('deleted',0)
                                                ->where('headerid',$sched_detail_info->headerid)
                                                ->where('stime',$sched_detail_info->stime)
                                                ->where('etime',$sched_detail_info->etime)
                                                ->where('roomid',$sched_detail_info->roomid)
                                                ->where('classification',$sched_detail_info->classification)
                                                ->where('day',$item)
                                                ->count();

                        if($checkSched == 0){

                            DB::table('sh_classscheddetail')
                                ->insert([
                                    'headerid'=>$sched_detail_info->headerid,
                                    'classification'=>$request->get('classification'),
                                    'stime'=>Carbon::create($stime)->isoFormat('HH:mm:ss'),
                                    'etime'=>Carbon::create($etime)->isoFormat('HH:mm:ss'),
                                    'roomid'=>$request->get('roomid'),
                                    'day'=>$item,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>Carbon::now('Asia/Manila')
                                ]);

                        }
                        
                    }

                    DB::table('sh_classsched')
                            ->where('id',$sched_detail_info->headerid)
                            ->where('deleted',0)
                            ->take(1)
                            ->update([
                                'sh_classsched.teacherid'=>$request->get('teacher'),
                                'sh_classsched.updatedby'=>auth()->user()->id,
                                'sh_classsched.updateddatetime'=>Carbon::now('Asia/Manila')
                            ]);

                    foreach($all_sched_detail as $item){


                        if(in_array( $item->day ,collect($request->get('days'))->toArray())){

                            DB::table('sh_classscheddetail')
                                ->where('id',$item->id)
                                ->where('deleted',0)
                                ->take(1)
                                ->update([
                                    'sh_classscheddetail.stime'=>Carbon::create($stime)->isoFormat('HH:mm:ss'),
                                    'sh_classscheddetail.etime'=>Carbon::create($etime)->isoFormat('HH:mm:ss'),
                                    'sh_classscheddetail.roomid'=>$request->get('roomid'),
                                    'sh_classscheddetail.updatedby'=>auth()->user()->id,
                                    'sh_classscheddetail.updateddatetime'=>Carbon::now('Asia/Manila')
                                ]);

                        }else{

                            DB::table('sh_classscheddetail')
                                    ->where('id',$item->id)
                                    ->where('deleted',0)
                                    ->take(1)
                                    ->update([
                                        'deleted'=>1,
                                        'sh_classscheddetail.deletedby'=>auth()->user()->id,
                                        'sh_classscheddetail.deleteddatetime'=>Carbon::now('Asia/Manila')
                                    ]);

                        }

                    }

                    




                }
                else if($request->get('type') == 'juniorhigh'){

                    //update teacher
                    $schedule = DB::table('classsched')
                                    ->where('syid',$activeSy->id)
                                    ->where('classsched.deleted',0)
                                    ->where('classsched.sectionid',$request->get('section'))
                                    ->where('classsched.subjid',$request->get('subject'))
                                    ->join('classscheddetail',function($join) use($request){
                                        $join->on('classsched.id','=','classscheddetail.headerid');
                                        $join->where('classscheddetail.id',$request->get('detailid'));
                                        $join->where('classscheddetail.deleted',0);
                                    })
                                    ->select(
                                        'headerid',
                                        'days',
                                        'classification',
                                        'stime',
                                        'etime',
                                        'roomid'
                                    )
                                    ->get();

                    if(count($schedule) > 0){

                        // return $schedule;
                        DB::table('classscheddetail')
                                ->where('headerid',$schedule[0]->headerid)
                                ->where('classification',$schedule[0]->classification)
                                ->where('stime',$schedule[0]->stime)
                                ->where('etime',$schedule[0]->etime)
                                ->where('roomid',$schedule[0]->roomid)
                                ->where('deleted',0)
                                ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>Carbon::now('Asia/Manila')
                                ]);


                        $time = explode(' - ',$request->get('time'));

                        $stime =  $time[0];
                        $etime =  $time[1];

                        // DB::table('classscheddetail')
                        //             ->where('headerid',$schedule[0]->headerid)
                        //             ->where('classification',$schedule[0]->classification)
                        //             ->where('stime',$schedule[0]->stime)
                        //             ->where('etime',$schedule[0]->etime)
                        //             ->where('roomid',$schedule[0]->roomid)
                        //             ->where('deleted',0)
                        //             ->update([
                        //                 'roomid'=>$request->get('roomid'),
                        //                 'stime'=>$stime,
                        //                 'etime'=>$etime,
                        //                 'classification'=>$request->get('classification'),
                        //                 'updatedby'=>auth()->user()->id,
                        //                 'updateddatetime'=>Carbon::now('Asia/Manila')
                        //             ]);
                                    
                                    
                        //  DB::table('classsched')
                        //         ->join('classscheddetail',function($join) use($request){
                        //             $join->on('classsched.id','=','classscheddetail.headerid');
                        //             $join->where('classscheddetail.deleted',0);
                        //             $join->whereNotIn('classscheddetail.days',$request->get('days'));
                        //         })
                        //         ->where('syid',$activeSy->id)
                        //         ->where('classscheddetail.deleted',0)
                        //         ->where('sectionid',$request->get('section'))
                        //         ->where('subjid',$request->get('subject'))
                        //         ->update([
                        //            'classscheddetail.deleted'=>1,
                        //            'classscheddetail.updatedby'=>auth()->user()->id,
                        //            'classscheddetail.updateddatetime'=>Carbon::now('Asia/Manila')
                        //         ]);

                        foreach($request->get('days') as $item){

                            // $checkSched = DB::table('classscheddetail')
                            //         ->where('headerid',$schedule[0]->headerid)
                            //         ->where('classification',$request->get('classification'))
                            //         ->where('stime',Carbon::create($stime)->isoFormat('HH:mm:ss'))
                            //         ->where('etime',Carbon::create($etime)->isoFormat('HH:mm:ss'))
                            //         ->where('roomid',$request->get('roomid'))
                            //         ->where('deleted',0)
                            //         ->where('days',$item)
                            //         ->count();
                                    
                            // if($checkSched == 0){

                                DB::table('classscheddetail')
                                    ->insert([
                                        'headerid'=>$schedule[0]->headerid,
                                        'classification'=>$request->get('classification'),
                                        'stime'=>$stime,
                                        'etime'=>$etime,
                                        'roomid'=>$request->get('roomid'),
                                        'days'=>$item,
                                        'createdby'=>auth()->user()->id,
                                        'createddatetime'=>Carbon::now('Asia/Manila')
                                    ]);

                            // }
                           
                        }
                   
                    }
                

                    if($request->get('teacher') != null && $request->has('teacher')){

                        DB::table('assignsubj')
                            ->where('syid',$activeSy->id)
                            ->where('assignsubj.deleted',0)
                            ->where('assignsubj.sectionid',$request->get('section'))
                            ->join('assignsubjdetail',function($join) use($request){
                                $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                                $join->where('assignsubjdetail.subjid',$request->get('subject'));
                                $join->where('assignsubjdetail.deleted',0);
                            })
                            ->update([
                                'teacherid'=>$request->get('teacher'),
                                'assignsubjdetail.updatedby'=>auth()->user()->id,
                                'assignsubjdetail.updateddatetime'=>Carbon::now('Asia/Manila')
                            ]);

                    }

                }
                else if($request->get('type') == 'block'){

                    $sched_detail_info = DB::table('sh_blockscheddetail')
                                            ->where('id',$request->get('detailid'))
                                            ->where('deleted',0)
                                            ->select('id','headerid','stime','etime','roomid','day','classification')
                                            ->first();

                    $time = explode(' - ',$request->get('time'));
                    $stime =  $time[0];
                    $etime =  $time[1];

                    $all_sched_detail = DB::table('sh_blockscheddetail')
                                            ->where('deleted',0)
                                            ->where('headerid',$sched_detail_info->headerid)
                                            ->where('stime',$sched_detail_info->stime)
                                            ->where('etime',$sched_detail_info->etime)
                                            ->where('roomid',$sched_detail_info->roomid)
                                            ->where('classification',$sched_detail_info->classification)
                                            ->select('id','headerid','stime','etime','roomid','day')
                                            ->get();

                    DB::table('sh_blocksched')
                            ->where('id',$sched_detail_info->headerid)
                            ->where('deleted',0)
                            ->take(1)
                            ->update([
                                'sh_blocksched.teacherid'=>$request->get('teacher'),
                                'sh_blocksched.updatedby'=>auth()->user()->id,
                                'sh_blocksched.updateddatetime'=>Carbon::now('Asia/Manila')
                            ]);

                    foreach($request->get('days') as $item){

                        $checkSched = DB::table('sh_blockscheddetail')
                                                ->where('deleted',0)
                                                ->where('headerid',$sched_detail_info->headerid)
                                                ->where('stime',$sched_detail_info->stime)
                                                ->where('etime',$sched_detail_info->etime)
                                                ->where('roomid',$sched_detail_info->roomid)
                                                ->where('classification',$sched_detail_info->classification)
                                                ->where('day',$item)
                                                ->count();

                        if($checkSched == 0){

                            DB::table('sh_blockscheddetail')
                                ->insert([
                                    'headerid'=>$sched_detail_info->headerid,
                                    'classification'=>$request->get('classification'),
                                    'stime'=>Carbon::create($stime)->isoFormat('HH:mm:ss'),
                                    'etime'=>Carbon::create($etime)->isoFormat('HH:mm:ss'),
                                    'roomid'=>$request->get('roomid'),
                                    'day'=>$item,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>Carbon::now('Asia/Manila')
                                ]);

                        }
                        
                    }

                    foreach($all_sched_detail as $item){


                        if(in_array( $item->day ,collect($request->get('days'))->toArray())){

                            DB::table('sh_blockscheddetail')
                                ->where('id',$item->id)
                                ->where('deleted',0)
                                ->take(1)
                                ->update([
                                    'sh_blockscheddetail.stime'=>Carbon::create($stime)->isoFormat('HH:mm:ss'),
                                    'sh_blockscheddetail.etime'=>Carbon::create($etime)->isoFormat('HH:mm:ss'),
                                    'sh_blockscheddetail.roomid'=>$request->get('roomid'),
                                    'sh_blockscheddetail.updatedby'=>auth()->user()->id,
                                    'sh_blockscheddetail.updateddatetime'=>Carbon::now('Asia/Manila')
                                ]);

                        }else{

                            DB::table('sh_blockscheddetail')
                                    ->where('id',$item->id)
                                    ->where('deleted',0)
                                    ->take(1)
                                    ->update([
                                        'deleted'=>1,
                                        'sh_blockscheddetail.deletedby'=>auth()->user()->id
                                    ]);

                        }

                    }

                    

                }

            }

        }

    }


    public function updateBlockSubjectTeacher(Request $request){



        $block = DB::table('sh_block')
                        ->where('sh_block.id',$request->get('bid'))
                        ->select('id')
                        ->first();

        if(isset($block->id)){

            DB::table('sh_blocksched')
                        ->join('sy',function($join){
                            $join->on('sh_blocksched.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('semester',function($join){
                            $join->on('sh_blocksched.semid','=','semester.id');
                            $join->where('semester.isactive','1');
                        })
                        ->where('blockid',$block->id)
                        ->where('sh_blocksched.deleted',0)
                        ->where('sh_blocksched.subjid',$request->get('subject'))
                        ->update([
                            'sh_blocksched.teacherid'=>$request->get('teacher'),
                            'sh_blocksched.updatedby'=>auth()->user()->id
                        ]);


        }


    }

    public function studentPromotionv2(Request $request){


        // return "sfsfsd";

        // resources\views\principalsportal\pages\promotions\student_promotion.blade.php

        if($request->get('blade') == 'blade' && $request->has('blade')){

            return  view('principalsportal.pages.promotions.student_promotion');


        }
        else if($request->get('students') == 'students' && $request->has('students')){

                    $activeSy = Session::get('schoolYear')->id;
                    $activeSem = Session::get('semester')->id;
                    $gradelevel = $request->get('gradelevel');

                    if( $gradelevel == 14 || $gradelevel == 15){

                        $students = DB::table('sh_enrolledstud')
                                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                        ->where('sh_enrolledstud.deleted',0)
                                        ->where('sh_enrolledstud.syid',$activeSy)
                                        ->where('sh_enrolledstud.semid',$activeSem)
                                        ->join('studinfo',function($join){
                                            $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                        });

                    }else{

                        $students = DB::table('enrolledstud')
                                        ->whereIn('enrolledstud.studstatus',[1,2,4])
                                        ->where('enrolledstud.deleted',0)
                                        ->where('enrolledstud.syid',$activeSy)
                                        ->join('studinfo',function($join){
                                            $join->on('enrolledstud.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                        });
                    }


                    if( ( $request->get('section') != null || $request->get('section') != '' )  && $request->has('section') ){

                    
                        if( $gradelevel == 14 || $gradelevel == 15){
                            $students = $students->where('sh_enrolledstud.sectionid',$request->get('section'));
                        }else{
                            $students = $students->where('enrolledstud.sectionid',$request->get('section'));
                        }

                    }

                    if( ( $request->get('gradelevel') != null || $request->get('gradelevel') != '' )  && $request->has('gradelevel') ){

                        if( $gradelevel == 14 || $gradelevel == 15){
                            $students = $students->where('sh_enrolledstud.levelid',$request->get('gradelevel'));
                        }else{
                            $students = $students->where('enrolledstud.levelid',$request->get('gradelevel'));
                        }
                        

                    }


                    $students = $students->select('studinfo.id','firstname','lastname','sid','promotionstatus')
                                            ->get();

                                        

                    return $students;


                }
                else if($request->get('finalgrade') == 'finalgrade' && $request->has('finalgrade')){

                    $activeSy = Session::get('schoolYear')->id;
                    $activeSem = Session::get('semester')->id;

                    $userid = DB::table('studinfo')->where('id',$request->get('studid'))->first()->userid;

                    $userid = DB::table('studinfo')->where('id',$request->get('studid'))->first()->userid;

                    $studendinfo =  SPP_EnrolledStudent::getStudent(null,null,null,null,null,null,$userid);
        
                    if(count($studendinfo[0]->data) == 0){
        
                        $studendinfo =  SPP_EnrolledStudent::getStudent(null,null,null,null,5,null,$userid);
                        
                        if(count($studendinfo[0]->data) == 0){
        
                            $studendinfo =  SPP_EnrolledStudent::getStudent(null,null,null,null,6,null,$userid);
                            
                        }
        
                    }

                    $gradesv4 = GenerateGrade::reportCardV3($studendinfo[0]->data[0], true, 'sf9');
                   
                    $checkGradingVersion = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();

                    if($checkGradingVersion->version == 'v1'){
            
                        $gradesv4 = GenerateGrade::reportCardV4($studendinfo[0]->data[0], true, 'sf9');
            
                    }
                    if($checkGradingVersion->version == 'v2'){
            
                        $gradesv4 = GenerateGrade::reportCardV5($studendinfo[0]->data[0], true, 'sf9');
               
            
                    }
                    
                    $grades = $gradesv4;
             
                    $subject_grades = collect($gradesv4)->where('mapeh',0);

                    $strand = $studendinfo[0]->data[0]->strandid;
                    $acad = $studendinfo[0]->data[0]->acadprogid;
                    $subject_status;
                    $subject_count = 0;
                    $subjects_with_grades = 0;

                 
                    $incomplete_list = array();
                    $incomplete_status = false;
               

                    if(  $acad == 5){

                        foreach($grades as $key=>$item){

                            $checkStrand = DB::table('sh_subjstrand')
                                                ->where('subjid',$item->subjid)
                                                ->where('strandid', $strand)
                                                ->where('deleted',0)
                                                ->count();
                            
                            if($checkStrand == 0 && $item->type != 1){

                                unset($grades[$key]);

                            }

                        }
                       
                        $subject_count = collect($grades)->count();
                
                        if($activeSem == 1){
                          
                            for($x = 1; $x <=2; $x ++){
                                $field = 'quarter'.$x;
                                if(collect($grades)->where($field,'!=',null)->count() != $subject_count){
                                    $incomplete_status = true;
                                    foreach(collect($grades)->where($field,null) as $item){
                                        array_push($incomplete_list,(object)[
                                            'subject'=>$item->subjectcode,
                                            'quarter'=>'Quarter '.$x
                                        ]);
                                    }
                                }
                            }
                        }
                        elseif($activeSem == 2){
                            for($x = 3; $x <= 4; $x ++){
                                $field = 'quarter'.$x;
                                if(collect($grades)->where($field,'!=',null)->count() != $subject_count){
                                    $incomplete_status = true;
                                    foreach(collect($field)->where($field,null) as $item){
                                        array_push($incomplete_list,(object)[
                                            'subject'=>$item->subjectcode,
                                            'quarter'=>'Quarter '.$x
                                        ]);
                                    }
                                }
                            }
                        }


                        $generalave = 'INC';

                        if(!$incomplete_status){

                            if($activeSem == 1){

                                $quarter1genave = collect($grades)->sum('quarter1') / $subject_count;
                                $quarter2genave = collect($grades)->sum('quarter2') / $subject_count;
                                $generalave = number_format(  ( $quarter1genave + $quarter2genave ) / 2 );

                            }
                            elseif($activeSem == 2){

                                $quarter3genave = collect($grades)->sum('quarter3') / $subject_count;
                                $quarter4genave = collect($grades)->sum('quarter4') / $subject_count;
                                $generalave = number_format( ( $quarter3genave + $quarter4genave ) / 2);

                            }

                        }

                    }else{

                     
                        $subject_count = collect($grades)->count();
                    
                        for($x = 1; $x <=4; $x ++){
                            $field = 'quarter'.$x;
                            if(collect($grades)->where($field,'!=',null)->count() != $subject_count){
                                $incomplete_status = true;
                                foreach(collect($grades)->where($field,null) as $item){
                                    array_push($incomplete_list,(object)[
                                        'subject'=>$item->subjectcode,
                                        'quarter'=>'Quarter '.$x
                                    ]);
                                }
                            }
                        }
                      
                        $generalave = 'INC';

                        if(!$incomplete_status){
                           
                            $frating = 0;
                            foreach($grades as $grade_item){
                                $frating += number_format( ( $grade_item->quarter1 + $grade_item->quarter2 + $grade_item->quarter3 + $grade_item->quarter4 ) / 4 );
                            }
                            $generalave = number_format(  $frating / $subject_count );

                        }
                    }

                  
                    $promotion_status = array((object)[
                        'status'=>null,
                        'incomplete'=>$incomplete_status,
                        'incomplete_list'=>$incomplete_list,
                        'genave'=>$generalave
                    ]);


                    return $promotion_status;

                }

                else if($request->get('promote') == 'promote' && $request->has('promote')){

                    $student = DB::table('studinfo')
                                ->where('studinfo.id',$request->get('studid'))
                                ->join('gradelevel',function($join){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted','0');
                                })
                                ->select('studinfo.levelid','gradelevel.acadprogid')
                                ->first();

                    if($student->acadprogid == 5){

                        // DB::table('studinfo')
                        //             ->where('id',$request->get('studid'))
                        //             ->where('studstatus','!=','0')
                        //             ->take(1)
                        //             ->update([
                        //                 'studstatus'=>'0',
                        //                 'sectionid'=>null,
                        //                 'blockid'=>null,
                        //                 'sectionname'=>null,
                        //                 'semid'=>null
                        //             ]);


                        // DB::table('sh_enrolledstud')
                        //             ->join('sy',function($join){
                        //                 $join->on('sh_enrolledstud.syid','=','sy.id');
                        //                 $join->where('isactive','1');
                        //             })
                        //             ->where('studid',$request->get('studid'))
                        //             ->take(1)
                        //             ->update([
                        //                 'promotionstatus'=>1
                        //             ]);

                        $check_status = DB::table('studinfo')
                              ->where('id',$request->get('studid'))
                              ->where('deleted',0)
                              ->select('studstatus','levelid')
                              ->first();

                        $promoted = 0;

                        if(isset($check_status->studstatus)){
                            if($check_status->studstatus != 0){
                                    
                                    $gradelevel_sort = DB::table('gradelevel')
                                                            ->where('id',$check_status->levelid)
                                                            ->where('deleted',0)
                                                            ->select('sortid','levelname','id')
                                                            ->first();

                                    $next_gradelevel = DB::table('gradelevel')
                                                            ->where('sortid','>',$gradelevel_sort->sortid)
                                                            ->orderBy('sortid')
                                                            ->where('deleted',0)
                                                            ->select('sortid','levelname','id')
                                                            ->first();

                                    $current_enrollment = DB::table('sh_enrolledstud')
                                            ->where('deleted',0)
                                            ->where('syid',$schoolyear)
                                            ->where('semid',$semester)
                                            ->where('studid',$studid)
                                            ->take(1)
                                            ->update([
                                                'updatedby'=>auth()->user()->id,
                                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                                'promotionstatus'=>1
                                            ]);

                                    DB::table('studinfo')
                                        ->where('id',$studid)
                                        ->where('deleted',0)
                                        ->take(1)
                                        ->update([
                                                'studstatus'=>0,
                                                'sectionid'=>null,
                                                'sectionname'=>null,
                                                'levelid'=>$next_gradelevel->id,
                                                'updatedby'=>auth()->user()->id,
                                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                                'feesid'=>null,
                                                'nodp'=>0
                                        ]);

                                    $promoted = 1;
                            }
                        }

                        return "done";


                    }
                    else if( $student->acadprogid == 2 || $student->acadprogid == 3 || $student->acadprogid == 4){
                        
                        
                        $activeSy = Session::get('schoolYear')->id;
                        $activeSem = Session::get('semester')->id;

                        // DB::table('studinfo')
                        //     ->where('id',$request->get('studid'))
                        //     ->where('studstatus','!=','0')
                        //     ->take(1)
                        //     ->update([
                        //         'studstatus'=>'0',
                        //         'sectionid'=>null,
                        //         'blockid'=>null,
                        //         'sectionname'=>null,
                        //         'semid'=>null
                        //     ]);


                        // DB::table('enrolledstud')
                        //     ->join('sy',function($join){
                        //         $join->on('enrolledstud.syid','=','sy.id');
                        //         $join->where('isactive','1');
                        //     })
                        //     ->where('studid',$request->get('studid'))
                        //     ->take(1)
                        //     ->update([
                        //         'promotionstatus'=>1
                        //     ]);

                        // return "done";
                        
                        $check_status = DB::table('studinfo')
                              ->where('id',$request->get('studid'))
                              ->where('deleted',0)
                              ->select('studstatus','levelid')
                              ->first();

                        $promoted = 0;
            
                        if(isset($check_status->studstatus)){
                              if($check_status->studstatus != 0){
                                    
                                    $gradelevel_sort = DB::table('gradelevel')
                                                            ->where('id',$check_status->levelid)
                                                            ->where('deleted',0)
                                                            ->select('sortid','levelname','id')
                                                            ->first();
            
                                    $next_gradelevel = DB::table('gradelevel')
                                                            ->where('sortid','>',$gradelevel_sort->sortid)
                                                            ->orderBy('sortid')
                                                            ->where('deleted',0)
                                                            ->select('sortid','levelname','id')
                                                            ->first();
            
                                    $current_enrollment = DB::table('enrolledstud')
                                        ->where('deleted',0)
                                        ->where('syid',$activeSy)
                                        ->where('studid',$request->get('studid'))
                                        ->take(1)
                                        ->update([
                                              'updatedby'=>auth()->user()->id,
                                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                              'promotionstatus'=>1
                                        ]);
            
                                    DB::table('studinfo')
                                          ->where('id',$request->get('studid'))
                                          ->where('deleted',0)
                                          ->take(1)
                                          ->update([
                                                'studstatus'=>0,
                                                'sectionid'=>null,
                                                'sectionname'=>null,
                                                'levelid'=>$next_gradelevel->id,
                                                'updatedby'=>auth()->user()->id,
                                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                                'feesid'=>null,
                                                'nodp'=>0
                                          ]);
            
                                    $promoted = 1;
                              }
                        }
                        
                        return "done";
                        
                        
                        

                    }


                }

    }

    public function student_pending(Request $request){

        if($request->has('insert') && $request->get('insert') == 'insert'){

            try{

                $syid = DB::table('sy')->where('isactive',1)->first()->id;

                $semid = 1;
                
                if($request->get('levelid') == 14 && $request->get('levelid') == 15){

                    $semid = DB::table('semester')->where('isactive',1)->first()->id;

                }

                $check_if_exist = DB::table('grading_system_pending_grade')
                                    ->where('studid',$request->get('studid'))
                                    ->where('levelid',$request->get('levelid'))
                                    ->where('subjid',$request->get('subjid'))
                                    ->where('sectionid',$request->get('sectionid'))
                                    ->where('teacherid',$request->get('teacherid'))
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('quarter',$request->get('quarter'))
                                    ->where('isactive',1)
                                    ->where('deleted',0)
                                    ->count();

                if($check_if_exist > 0){

                    return array((object)[
                        'status'=>0,
                        'data'=>'Student grade is already added to pending'
                    ]);

                }

                DB::table('grading_system_pending_grade')
                        ->insert([
                            'studid'=>$request->get('studid'),
                            'levelid'=>$request->get('levelid'),
                            'subjid'=>$request->get('subjid'),
                            'syid'=>$syid,
                            'quarter'=>$request->get('quarter'),
                            'semid'=> $semid,
                            'subjid'=>$request->get('subjid'),
                            'sectionid'=>$request->get('sectionid'),
                            'teacherid'=>$request->get('teacherid')
                        ]);

                return array((object)[
                    'status'=>1,
                    'data'=>'Successfully added to pending'
                ]);

            }catch (\Exception $e) {

                DB::table('zerrorlogs')
                        ->insert([
                        'error'=>$e,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                return array((object)[
                    'status'=>0,
                    'data'=>'Something went wrong'
                ]);

            }

        }elseif($request->has('evaluate') && $request->get('evaluate') == 'evaluate'){

            $syid = DB::table('sy')->where('isactive',1)->first()->id;

            $semid = 1;
            
            if($request->get('levelid') == 14 && $request->get('levelid') == 15){

                $semid = DB::table('semester')->where('isactive',1)->first()->id;

            }

            $check_if_exist = DB::table('grading_system_pending_grade')
                        ->where('studid',$request->get('studid'))
                        ->where('levelid',$request->get('levelid'))
                        ->where('subjid',$request->get('subjid'))
                        ->where('sectionid',$request->get('sectionid'))
                        ->where('teacherid',$request->get('teacherid'))
                        ->where('syid',$syid)
                        ->where('quarter',$request->get('quarter'))
                        ->where('semid',$semid)
                        ->where('isactive',1)
                        ->where('deleted',0)
                        ->count();

            if($check_if_exist > 0){

                return 1;

            }else{

                return 0;

            }

        }elseif($request->has('cancel') && $request->get('cancel') == 'cancel'){

            $syid = DB::table('sy')->where('isactive',1)->first()->id;

            $semid = 1;
            
            if($request->get('levelid') == 14 && $request->get('levelid') == 15){

                $semid = DB::table('semester')->where('isactive',1)->first()->id;

            }

            $check_if_exist = DB::table('grading_system_pending_grade')
                                ->where('studid',$request->get('studid'))
                                ->where('levelid',$request->get('levelid'))
                                ->where('subjid',$request->get('subjid'))
                                ->where('sectionid',$request->get('sectionid'))
                                ->where('teacherid',$request->get('teacherid'))
                                ->where('quarter',$request->get('quarter'))
                                ->where('syid',$syid)
                                ->where('semid',$semid)
                                ->where('isactive',1)
                                ->where('deleted',0)
                                ->update([
                                    'deleted'=>1
                                ]);

            

            return array((object)[
                'status'=>1,
                'data'=>'Pending was canceled successfully'
            ]);

        }


    }

   
    //subject start
    public function subjects_blade(Request $request){
        $acadprog = $request->get('acadprog');
        
        if($acadprog == 'pre-school' || $acadprog == 'gradeschool' || $acadprog == 'juniorhighschool' || $acadprog == 'seniorhighschool'){
           return view('principalsportal.pages.subject_v2.subjects')->with('acadprog',$acadprog);
        }else{
            return redirect('/home');
        }
    }
    public function view_subjects(Request $request){
        $acadprog = $request->get('acadprog');
        $subjid = $request->get('subjid');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectData::ps_get_subjects($subjid);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectData::gs_get_subjects($subjid);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectData::jh_get_subjects($subjid);
        }
        else if($acadprog == 'seniorhighschool'){
            return \App\Models\Subjects\SubjectData::sh_get_subjects($subjid);
        }
    }

    public function update_sort(Request $request){
        $acadprog = $request->get('acadprog');
        $sort_val = $request->get('sort_val');
        $subj_id = $request->get('subj_id');

        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject_sort($subj_id, $sort_val);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_sort($subj_id, $sort_val);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_sort($subj_id, $sort_val);
        }
        else if($acadprog == 'seniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_sort_sh($subj_id, $sort_val);
        }
    }
    public function subject_blade(Request $request){
        $acadprog = $request->get('acadprog');
        $subjid = $request->get('subjid');
        if($acadprog == 'pre-school' || $acadprog == 'gradeschool' || $acadprog == 'juniorhighschool' || $acadprog == 'seniorhighschool'){
           return view('principalsportal.pages.subject_v2.subject_view')
                        ->with('acadprog',$acadprog)
                        ->with('subjid',$subjid);
        }else{
            return redirect('/home');
        }
    }
    public function update_subject(Request $request){
        $acadprog = $request->get('acadprog');
        $subjid = $request->get('subjid');
        $subjcode = $request->get('subjcode');
        $subjdesc = $request->get('subjdesc');
        $semid = $request->get('semid');
        $type = $request->get('type');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject($subjid, $subjcode, $subjdesc);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject($subjid, $subjcode, $subjdesc);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject($subjid, $subjcode, $subjdesc);
        }
        else if($acadprog == 'seniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_sh($subjid, $subjcode, $subjdesc,$type,$semid);
        }else{
            return redirect('/home');
        }
    }

    public function create_subject(Request $request){
        $acadprog = $request->get('acadprog');
        $subjcode = $request->get('subjcode');
        $subjdesc = $request->get('subjdesc');
        $subjsort = $request->get('subjsort');
        $inSF9 = $request->get('insf9');
        $inMAPEH = $request->get('mapeh');
        $tlecon = $request->get('tlecon');
        $mapehcon = $request->get('mapehcon');
        $inTLE = $request->get('tle');
        $isCon = $request->get('isCon');
        $isSP = $request->get('isSP');
        $isVisible = $request->get('isVisible');
        $semid = $request->get('semid');
        $strand = $request->get('strand');
        $type = $request->get('type');
        if($acadprog == 'pre-school'){
            $acadprogid = 2;
            return \App\Models\Subjects\SubjectProccess::create_subject($subjdesc, $subjcode, $acadprogid,  $inSF9 , $inMAPEH , $inTLE, $subjsort,null,null,null,$tlecon,$mapehcon,$isCon,$isVisible,$isSP);
        }
        else if($acadprog == 'gradeschool'){
            $acadprogid = 3;
            return \App\Models\Subjects\SubjectProccess::create_subject($subjdesc, $subjcode, $acadprogid, $inSF9 , $inMAPEH , $inTLE, $subjsort,null,null,null,$tlecon,$mapehcon,$isCon,$isVisible,$isSP);
        }
        else if($acadprog == 'juniorhighschool'){
            $acadprogid = 4;
            return \App\Models\Subjects\SubjectProccess::create_subject($subjdesc, $subjcode, $acadprogid, $inSF9 , $inMAPEH , $inTLE, $subjsort,null,null,null,$tlecon,$mapehcon,$isCon,$isVisible,$isSP);
        }
        else if($acadprog == 'seniorhighschool'){
            $acadprogid = 5;
            return \App\Models\Subjects\SubjectProccess::create_subject($subjdesc, $subjcode, $acadprogid, $inSF9 , $inMAPEH , $inTLE, $subjsort, $type, $semid, $strand,$tlecon,$mapehcon,$isCon,$isVisible,$isSP);
        }else{
            return redirect('/home');
        }
    }
    public function update_mapeh(Request $request){
        $acadprog = $request->get('acadprog');
        $subjid = $request->get('subjid');
        $mapeh = $request->get('mapeh');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject_mapeh($subjid, $mapeh);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_mapeh($subjid, $mapeh);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_mapeh($subjid, $mapeh);
        }
        else if($acadprog == 'seniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_mapeh($subjid, $mapeh);
        }else{
            return redirect('/home');
        }
    }
    public function update_sf9(Request $request){
        $acadprog = $request->get('acadprog');
        $subjid = $request->get('subjid');
        $sf9 = $request->get('sf9');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject_sf9($subjid, $sf9);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_sf9($subjid, $sf9);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_sf9($subjid, $sf9);
        }
        else if($acadprog == 'seniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_sf9_sh($subjid, $sf9);
        }else{
            return redirect('/home');
        }
    }
    public function update_tle(Request $request){
        $acadprog = $request->get('acadprog');
        $subjid = $request->get('subjid');
        $tle = $request->get('tle');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject_tle($subjid, $tle);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_tle($subjid, $tle);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_tle($subjid, $tle);
        }
        else if($acadprog == 'seniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_tle($subjid, $tle);
        }else{
            return redirect('/home');
        }
    }

    public function update_issp(Request $request){
        $acadprog = $request->get('acadprog');
        $subjid = $request->get('subjid');
        $isSP = $request->get('isSP');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject_issp($subjid, $isSP);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_issp($subjid, $isSP);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_issp($subjid, $isSP);
        }
        else if($acadprog == 'seniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_issp($subjid, $isSP);
        }else{
            return redirect('/home');
        }
    }

    public function update_tlecon(Request $request){
        $acadprog = $request->get('acadprog');
        $subjid = $request->get('subjid');
        $tlecon = $request->get('tlecon');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject_tlecon($subjid, $tlecon);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_tlecon($subjid, $tlecon);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_tlecon($subjid, $tlecon);
        }
        else if($acadprog == 'seniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_tlecon($subjid, $tlecon);
        }else{
            return redirect('/home');
        }
    }

    public function update_mapehcon(Request $request){
        $acadprog = $request->get('acadprog');
        $subjid = $request->get('subjid');
        $mapehcon = $request->get('mapehcon');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject_mapehcon($subjid, $mapehcon);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_mapehcon($subjid, $mapehcon);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_mapehcon($subjid, $mapehcon);
        }
        else if($acadprog == 'seniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_mapehcon($subjid, $mapehcon);
        }else{
            return redirect('/home');
        }
    }
    public function update_percentage(Request $request){
        $acadprog = $request->get('acadprog');
        $subjid = $request->get('subjid');
        $percentage = $request->get('percentage');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject_percentage($subjid, $percentage);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_percentage($subjid, $percentage);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_percentage($subjid, $percentage);
        }
        else if($acadprog == 'seniorhighschool'){
            // return \App\Models\Subjects\SubjectProccess::update_subject_percentage($subjid, $percentage);
        }else{
            return redirect('/home');
        }
    }
    public function update_visible(Request $request){
        $acadprog = $request->get('acadprog');
        $visible = $request->get('visible');
        $subjid = $request->get('subjid');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject_visible($subjid, $visible);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_visible($subjid, $visible);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_visible($subjid, $visible);
        }
        else if($acadprog == 'seniorhighschool'){
            // return \App\Models\Subjects\SubjectProccess::update_subject_percentage($subjid, $percentage);
        }else{
            return redirect('/home');
        }
    }
    public function update_consolidated(Request $request){
        $acadprog = $request->get('acadprog');
        $consolidated = $request->get('consolidated');
        $subjid = $request->get('subjid');
        if($acadprog == 'pre-school'){
            return \App\Models\Subjects\SubjectProccess::update_subject_consolidated($subjid, $consolidated);
        }
        else if($acadprog == 'gradeschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_consolidated($subjid, $consolidated);
        }
        else if($acadprog == 'juniorhighschool'){
            return \App\Models\Subjects\SubjectProccess::update_subject_consolidated($subjid, $consolidated);
        }
        else if($acadprog == 'seniorhighschool'){
            // return \App\Models\Subjects\SubjectProccess::update_subject_percentage($subjid, $percentage);
        }else{
            return redirect('/home');
        }
    }
    public function subject_strand(Request $request){
        $subjid = $request->get('subjid');
        return \App\Models\Subjects\SubjectData::subject_strand($subjid);
    }
    public function subj_strand_create(Request $request){
        $subjid = $request->get('subjid');
        $strandid = $request->get('strandid');
        return \App\Models\Subjects\SubjectProccess::add_strand($subjid,$strandid);
    }
    public function subj_strand_delete(Request $request){
        $subj_strand_id = $request->get('subj_strand_id');
        return \App\Models\Subjects\SubjectProccess::remove_strand($subj_strand_id);
    }

    //subject component module
    public function subject_component_list(Request $request){
       $subjid = $request->get('subjid');
       return \App\Models\Subjects\SubjectData::subject_component_list($subjid);
    }
    public function subject_component_list_na(Request $request){
        $acadprogid = $request->get('acadprog');
        return \App\Models\Subjects\SubjectData::subject_component_list_na($acadprogid);
    }

    public function subject_component_update(Request $request){
        $selectedsubj = $request->get('selectedsubj');
        $subjid = $request->get('subjid');
        return \App\Models\Subjects\SubjectProccess::update_subject_component($selectedsubj,$subjid);
        
    }

    public function subject_component_remove(Request $request){
        $subjid = $request->get('subjid');
        return \App\Models\Subjects\SubjectProccess::subject_component_remove($subjid);
    }
    //subject component module


    //subject end

    //grade setup start
    public function get_gradesetup(Request $request){
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $subjid = $request->get('subjid');
        $acadprog = $request->get('acadprog');

        if($acadprog == 'pre-school'){
            $acadprogid = 2;
        }
        else if($acadprog == 'gradeschool'){
            $acadprogid = 3;
        }
        else if($acadprog == 'juniorhighschool'){
            $acadprogid = 4;
        }
        else if($acadprog == 'seniorhighschool'){
            $acadprogid = 5;
        }
        else if($acadprog == 5){
            $acadprogid = 5;
        }else{
            $acadprogid = null;
        }
        return \App\Models\GradeSetup\GradeSetupData::get_grade_setup($syid,$subjid,$acadprogid,$levelid);
    }

    public function update_gradesetup(Request $request){
        $gsid = $request->get('gsid');
        $ww = $request->get('ww');
        $pt = $request->get('pt');
        $qa = $request->get('qa');
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $subjid = $request->get('subjid');
        return \App\Models\GradeSetup\GradeSetupProccess::update_grade_setup($gsid,$ww,$pt,$qa,$syid,$levelid,$subjid);
    }

    public function gradestatus_update_quarter(Request $request){
        $setupid = $request->get('setupid');
        $quarter = $request->get('quarter');
        $status = $request->get('status');
        return \App\Models\GradeSetup\GradeSetupProccess::gradestatus_update_quarter($setupid,$quarter,$status);
    }

    public function delete_gradesetup(Request $request){
        $gsid = $request->get('gsid');
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $subjid = $request->get('subjid');
        if($levelid != 14 && $levelid != 15){
            $acadprogid = null;
        }else{
            $acadprogid = 5;
        }
        return \App\Models\GradeSetup\GradeSetupProccess::delete_grade_setup($gsid,$subjid,$syid,$acadprogid);
    }

    //grade setup end

    //ps grade status
    public function ps_gradestatus_list_list(Request $request){
        $studid = $request->get('studid');
        $sectionid = $request->get('sectionid');
        $syid = $request->get('syid');

        $students = DB::table('enrolledstud')->where('sectionid',$sectionid)->where('deleted',0)->select('studid')->get();

        foreach($students as $item){
            \App\Models\PreSchool\PSGradeStatus\PSGradeStatusProcess::ps_grade_status_create($item->studid,$sectionid,$syid);
        }

        return \App\Models\PreSchool\PSGradeStatus\PSGradeStatusData::ps_grade_status_list($studid,$sectionid,$syid);
    }

    public function ps_gradestatus_list_create(Request $request){
        $gsid = $request->get('gsid');
        return \App\Models\GradeSetup\GradeSetupProccess::delete_grade_setup($gsid);
    }

    public function ps_gradestatus_update(Request $request){
        $studid = $request->get('studid');
        $psgradestatusid = $request->get('psgradestatusid');
        $status = $request->get('status');
        $quarter = $request->get('quarter');
        return \App\Models\PreSchool\PSGradeStatus\PSGradeStatusProcess::ps_gradestatus_update($studid,$psgradestatusid,$quarter,$status);
    }

    public function ps_gradestatus_delete(Request $request){
        $gsid = $request->get('gsid');
        return \App\Models\GradeSetup\GradeSetupProccess::delete_grade_setup($gsid);
    }

    //sf9 signatory
    public static function list_sf9_signatory(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $signatory = DB::table('signatory')
            ->where('form','report_card')
            ->where('syid',$syid)
            ->where('deleted',0)
            ->select(
                'id',
                'name',
                'title',
                'acadprogid'
            )
            ->get();


        return $signatory;
    }

    public static function create_sf9_signatory(Request $request){

        try{

            $syid = $request->get('syid');
            $name = $request->get('name');
            $title = $request->get('title');
            $acadprogid = $request->get('acadprogid');

            $check = DB::table('signatory')
                        ->where('form','report_card')
                        ->where('acadprogid',$acadprogid)
                        ->where('deleted',0)
						->where('syid',$syid)
                        ->count();

            if($check > 0 ){
                    return array((object)[
                        'status'=>0,
                        'message'=>'Already Exists!'
                    ]);
            }

            DB::table('signatory')
                    ->insert([
                        'syid'=>$syid,
                        'name'=>$name,
                        'title'=>$title,
                        'acadprogid'=>$acadprogid,
                        'form'=>'report_card',
                        'deleted'=>0,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return array((object)[
                    'status'=>1,
                    'message'=>'Signatory Created!'
            ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function update_sf9_signatory(Request $request){
            try{

                $name = $request->get('name');
                $title = $request->get('title');
                $acadprogid = $request->get('acadprogid');
                $id = $request->get('id');
                $syid = $request->get('syid');

                DB::table('signatory')
                        ->where('id',$id)
                        ->update([
                            'syid'=>$syid,
                            'name'=>$name,
                            'title'=>$title,
                            'acadprogid'=>$acadprogid,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                return array((object)[
                        'status'=>1,
                        'message'=>'Signatory Updated!'
                ]);
                
            }catch(\Exception $e){
                return self::store_error($e);
            }
    }

    public static function delete_sf9_signatory(Request $request){
            try{

                DB::table('signatory')
                        ->where('id',$id)
                        ->update([
                            'deleted'=>1,
                            'deletedby'=>auth()->user()->id,
                            'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                return array((object)[
                        'status'=>1,
                        'message'=>'Track Deleted!'
                ]);
                
            }catch(\Exception $e){
                return self::store_error($e);
            }
    }
    //sf9 signatory






}
