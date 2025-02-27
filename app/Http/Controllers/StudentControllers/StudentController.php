<?php

namespace App\Http\Controllers\StudentControllers;

use Illuminate\Http\Request;
use DB;
use \Carbon\Carbon;
use App\Models\Principal\GenerateGrade;
use App\Models\Principal\LoadAnnouncements;
use Session;
use App\Models\Principal\AttendanceReport;
use DateInterval;
use App\Models\Principal\LoadData;
use CarbonInterval;
use Carbon\CarbonInterface;
use App\Models\Principal\ClassSched;
use App\Models\Principal\SPP_SchoolYear;
use App\Models\Principal\SPP_SHClassSchedule;
use App\Models\Principal\SPP_Student;
use App\Models\Principal\SPP_Subject;
use App\Models\Principal\SPP_Notification;
use App\Models\Principal\SPP_Attendance;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Student\Student;
use App\Models\Student\TeacherEvaluation;
use App\Models\Principal\Billing;

use Crypt;
use Auth;


class StudentController extends \App\Http\Controllers\Controller
{

    public function loadStudentSchedule(){

        if(Session::get('enrollmentstatus')) {

            $studendinfo = Session::get('studentInfo');

            if($studendinfo->acadprogid == 6){

                $schedules = DB::table('college_studsched')
                                ->join('college_classsched',function($join){
                                    $join->on('college_studsched.schedid','=','college_classsched.id');
                                })
                                ->join('college_sections',function($join){
                                    $join->on('college_classsched.sectionID','=','college_sections.id');
                                })
                                ->leftJoin('college_scheddetail',function($join){
                                    $join->on('college_classsched.id','=','college_scheddetail.headerid');
                                    $join->where('college_scheddetail.deleted','0');
                                })
                                ->join('college_prospectus',function($join){
                                    $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                    $join->where('college_prospectus.deleted','0');
                                })
                                ->leftJoin('teacher',function($join){
                                    $join->on('college_classsched.teacherID','=','teacher.id');
                                    $join->where('teacher.deleted','0');
                                })
                                ->leftJoin('rooms',function($join){
                                    $join->on('college_scheddetail.roomID','=','rooms.id');
                                    $join->where('rooms.deleted','0');
                                })
                                ->leftJoin('days',function($join){
                                    $join->on('college_scheddetail.day','=','days.id');
                                })
                                ->join('sy',function($join){
                                    $join->on('college_classsched.syID','=','sy.id');
                                    $join->where('sy.isactive','1');
                                })
                                ->join('semester',function($join){
                                    $join->on('college_classsched.semesterID','=','semester.id');
                                    $join->where('semester.isactive','1');
                                })
                                ->where('college_studsched.studid',$studendinfo->id)
                                ->where('college_studsched.deleted',0)
                                ->select(
                                    'days.description',
                                    'college_classsched.id',
                                    'college_scheddetail.id as schedid',
                                    'rooms.roomname',
                                    'college_scheddetail.etime',
                                    'college_scheddetail.stime',
                                    'teacher.firstname',
                                    'teacher.lastname',
                                    'college_classsched.subjectUnit',
                                    'college_prospectus.subjDesc',
                                    'college_prospectus.subjCode',
                                    'college_prospectus.lecunits',
                                    'college_prospectus.labunits',
                                    'college_prospectus.id as subjID',
                                    'college_scheddetail.scheddetialclass',
                                    'teacher.firstname',
                                    'teacher.lastname'
                                    )
                                ->get();

                if(count($schedules) > 0){

                    $withSched = true;
        
                    $bySubject = collect($schedules)->groupBy('subjID');
        
                    $data = array();
        
                    foreach($bySubject as $subjitem){

                        $byClass = collect($subjitem)->groupBy('scheddetialclass');
        
                        foreach($byClass as $item){
        
                            $day = '';
                            
                            foreach(collect($item)->groupBy('etime') as $secondItem){
                                
                                foreach($secondItem as $thirdItem){
        
                                    $item->put('id','1');
        
                                
        
                                    $details = $thirdItem;
                                
                                    if($thirdItem->description == 'Thursday'){
                                        $day .= substr($thirdItem->description, 0 , 1).'h';
                                    }
                                    else{
                                        $day .= substr($thirdItem->description, 0 , 1).'';
                                    }
                                    
                                }
                                
                                if(!isset($details->id)){
                                    $details->id = null;
                                    $details->scheddetialclass = null;
                                    $details->stime = null;
                                    $details->etime = null;
                                    $details->lastname  = null;
                                    $details->firstname  = null;
                                    $details->lecunits  = $secondItem[0]->lecunits;
                                    $details->labunits  = $secondItem[0]->labunits;
                                    $details->roomname  = null;
                                    $details->sectionDesc   = null;
        
                                }
                                
        
                                
                                $details->description = $day;
        
                                array_push($data, $details);
                                
                            };
        
                            
                        }
        
                    }

                    $schedules = collect($data)->groupBy('subjID');

                    return view('studentPortal.pages.schedule')
                                    ->with('scheds',ClassSched::dayswithbgcolors())
                                    ->with('sampleScheds',$schedules)
                                    ->with('enrollmentstatus','Enrolled');
        
                }
                else{


                    
                    return view('studentPortal.pages.schedule')
                                    ->with('scheds',ClassSched::dayswithbgcolors())
                                    ->with('sampleScheds',$schedules)
                                    ->with('enrollmentstatus','Enrolled');
                }

                
                
            }
            else{

                $schedule  = SPP_Subject::getSchedule(null,null,$studendinfo->ensectid,$studendinfo->blockid);

            }

          

            return view('studentPortal.pages.schedule')
                    ->with('scheds',ClassSched::dayswithbgcolors())
                    ->with('sampleScheds',$schedule)
                    ->with('enrollmentstatus','Enrolled');
          

        }
        else{

            return view('studentPortal.pages.schedule');

        }
    
    }

    public function updategradenotification($id){


        DB::table('notifications')
                ->where('headerid',$id)
                ->where('recieverid',auth()->user()->id)
                ->update(['status'=>'1']);

        return $this->loadGrades();

    }

    public function loadGrades(){

        return view('studentPortal.pages.enrollment_report');

        $studinfo = DB::table('studinfo')
                    ->join('gradelevel',function($join){
                        $join->on('studinfo.levelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted',0);
                    })
                    ->where('studinfo.deleted',0)
                    ->where('studinfo.userid',auth()->user()->id)
                    ->select('acadprogid')
                    ->first();
        
        if($studinfo->acadprogid == 6){
             return view('studentPortal.pages.college.collegestudentgrading');
          
        }else{
             return view('studentPortal.pages.enrollment_report');
        }
 
    }


    public function studentgetevent(Request $request){

        return SPP_Calendar::getHoliday(null,null,$request->get('id'));

    }

    public function studentgeteventtype(Request $request){

        return SPP_Calendar::getEventType($request->get('id'));

    }

    public function loadCalendar(){

        $schoolcalendar = DB::table('schoolcal')
                                ->where('deleted','0')
                                ->get();

        return view('studentPortal.pages.schoolCalendar')
                ->with('schoolcalendar',$schoolcalendar);

    }

    public function viewAnnouncement($id){

        try{

            $id = Crypt::decrypt($id);

        }
        catch (\Exception $e){

            $id = $id;

        }

        $content =  DB::table('notifications')
                        ->join('announcements',function($join) use($id){
                            $join->on('notifications.headerid','=','announcements.id');
                            $join->where('announcements.id',$id);
                        })
                        ->join('users','announcements.createdby','=','users.id')
                        ->where('recieverid',auth()->user()->id)
                        ->select('users.name','announcements.title','announcements.content','notifications.created_at')
                        ->get();

        DB::table('notifications')
                ->where('headerid',$id)
                ->where('recieverid',auth()->user()->id)
                ->update(['status'=>'1']);

        return view('studentPortal.pages.announcements')
                ->with('content',$content);


    }

    public function viewAllAnnouncement(){

        $notification =  SPP_Notification::viewNotifications(null,10,auth()->user()->id,null,null);

        return view('studentPortal.pages.viewallannouncementes')
                ->with('data',$notification );

    }

    // gian 
    public function loadProfile(){

        $studendinfo = Session::get('studentInfo');


        // return collect($studendinfo);

        $studinformation = DB::table('studinfo')
                            // ->leftJoin('religion','studinfo.religionid', '=', 'religion.id')
                            // ->leftJoin('mothertongue','studinfo.mtid', '=', 'mothertongue.id')
                            // ->leftJoin('ethnic','studinfo.egid', '=', 'ethnic.id')
                            ->leftJoin('gradelevel','studinfo.levelid', '=', 'gradelevel.id')
                            ->leftJoin('sh_strand','studinfo.strandid', '=', 'sh_strand.id')
                            ->leftJoin('college_courses','studinfo.courseid', '=', 'college_courses.id')
                            ->where('studinfo.id',$studendinfo->id)
                            ->select('studinfo.*','levelname','acadprogid','courseDesc','strandname')
                            ->first();

        return view('studentPortal.pages.studentinformation')
                    ->with('item',$studinformation);
    }

     public function teacherevaluation(Request $request){

        $teacherEvalVersion = DB::table('zversion_control')->where('module',2)->where('isactive',1)->get();
        $syid = DB::table('sy')->where('isactive',1)->first()->id;
        
        if($syid != 2){
            
            $strandid = null;
            
            $studendinfo = Session::get('studentInfo');
            $studid = $studendinfo->id;
    
    
            $check_enrollment = DB::table('enrolledstud')   
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->first();
    
            if(!isset($check_enrollment->id)){
    
                $check_enrollment = DB::table('sh_enrolledstud')   
                                        ->where('studid',$studid)
                                        ->where('syid',$syid)
                                        ->where('deleted',0)
                                        ->first();
    
                if(isset($check_enrollment->id)){
                    $semid = $request->get('semid');
                    $strandid = $check_enrollment->strandid;
                }
            }

			if($request->get('blade') == 'blade'){
				if(!isset($check_enrollment->id)){
					return back();
				}
			}

            
            if(!isset($check_enrollment->id)){
                if($type == 'all'){
                    return view('studentPortal.pages.schedplot')->with('schedule',array());
                }else{
                    return array();
                }
            }

            $levelid = $check_enrollment->levelid;
            $sectionid = $check_enrollment->sectionid;
            
            
        }
        

            $quarter = self::activeQuarter();

            if($quarter == 0){
   
                return  view('studentPortal.pages.teacherevaluation.teachereval')
                                ->with('quarter',$quarter) ;

            }

            $studentInfo = Session::get('studentInfo');

            if($request->has('subjects') && $request->get('subjects') == 'subjects'){

            
                    if($levelid == 14 || $levelid == 15){
                        $semid = $quarter;
                        $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$sectionid,$semid,$strandid);
                    }else{
                        $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$sectionid);
                    }

                    foreach($subjects as $subject){
                        $subject->subject = $subject->subjdesc;
                        if(count($subject->schedule) == 0){
                            $subject->teacherid = null;
                            $subject->teacher = null;
                        }else{
                            foreach($subject->schedule as $schedule){
                                $subject->teacherid = $schedule->teacherid;
                                $subject->teacher = $schedule->teacher;
                            }
                        }
                      
                    }
                    
					
					
					$teachers = collect($subjects)->unique('teacherid');
              
					foreach($teachers as $item){
						
						$subjinfo = '';
						
						foreach(collect($subjects)->where('teacherid',$item->teacherid) as $key=>$subjitem){
							$subjinfo .= $subjitem->subjdesc;
							if($key+1 != collect($subjects)->where('teacherid',$item->teacherid)->count()){
								
								
								$subjinfo .=', ';
							}
							
						}
						
						$item->subject = $subjinfo;
					}
				
					$subjects = $teachers;
			  
                    $grading_header = DB::table('grading_system_student_header')
                                        ->where('studid',$studid)
                                        ->where('sectionid',$sectionid)
                                        ->where('levelid',$levelid)
                                        ->where('deleted',0)
                                        ->where('syid',$syid)
                                        ->where('semid',1)
                                        ->select(
                                            'teacherid',
                                            'subjid',
                                            'id'
                                        )
                                        ->get();

                $student_evaluation = DB::table('grading_system_student_evaluation')
                                        ->whereIn('studheader',collect($grading_header)->pluck('id'))
                                        ->where('deleted',0)
                                        ->select('q1val','q2val','q3val','q4val','gsid','studheader')
                                        ->get();

                foreach($subjects as $item){
                    
                    $check_header = collect($grading_header)
                                        ->where('subjid',$item->subjid)
                                        ->where('teacherid',$item->teacherid)
                                        ->values();

                    if(count($check_header) > 0){
                        
                        $field = 'q'.$quarter.'val';

                        $headerid = $check_header[0]->id;

                        $evalCount = collect($student_evaluation)
                                        ->where('studheader',$check_header[0]->id)
                                        ->where($field,'!=',null)
                                        ->count();

                        if($evalCount > 0){
                            $item->status = 1;
                        }
                        else{
                            $item->status = 0;
                        }
                    }
                    else{
                        $item->status = 0;
                    }

                }
                
                $subjects = collect($subjects)->where('teacherid','!=',null)->values();

            }



            if($request->has('blade') && $request->get('blade') == 'blade'){
				              
                return  view('studentPortal.pages.teacherevaluation.teachereval')
                                ->with('quarter',$quarter) ;

            }
            else if($request->has('table') && $request->get('table') == 'table'){
                
                return  view('studentPortal.pages.teacherevaluation.subjectstable')->with('subjects',$subjects)->with('quarter',$quarter);

            }
            else if($request->has('questions') && $request->get('questions') == 'questions'){
               
               
                    $subject_exist = Student::check_if_subject_exist(
                        $request->get('subject'),
                        $levelid,
                        $sectionid,
                        $request->get('teacher')
                    );

                if($subject_exist[0]->status == 0){
                    return $subject_exist;
                }

                $headerid = null;

                    
                    $get_header =  Student::get_grading_header(
                        $studid,
                        $sectionid,
                        $request->get('subject'),
                        $request->get('teacher'),
                        $levelid
                    );
               
                
                

                if($get_header[0]->status == 0){
                    
                    return $get_header;

                }else{

                    $get_header = $get_header[0]->data;
                    

                    if(count($get_header) == 0){

                              $get_header =  Student::generate_grading_header(
                                $studid,
                                $levelid,
                                $request->get('subject'),
                                $sectionid,
                                $request->get('teacher')
                            );
                       

                        $headerid = $get_header[0]->data;

                    }else{

                        $headerid = $get_header[0]->id;

                    }

                }

                $teacherEvaluation = TeacherEvaluation::getTeacherEvaluationSetup();

               

                if($teacherEvaluation[0]->status == 0){

                    return view('studentPortal.pages.error')->with('message',$teacherEvaluation[0]->data);

                }
                else{

                    $teacherEvaluation =  $teacherEvaluation[0]->data;

                }

                $evaluationDetail = TeacherEvaluation::evaluate_teacher_evaluation_setup($teacherEvaluation[0]->id);

                if($evaluationDetail[0]->status == 0){
                    return view('studentPortal.pages.error')->with('message',$evaluationDetail[0]->data);
                }
                else{
                    $evaluationDetail =  $evaluationDetail[0]->data;
                }

                $ratingComment = null;
                $evaluations = Student::check_evaluation($headerid);
                $ratingComment = Student::get_evalution_comment($headerid);

                if($ratingComment[0]->status == 1){

                    $ratingComment = $ratingComment[0]->data;

                }else{
                    
                    $ratingComment = array((object)['q1com'=>""]);
                    
                }

                $ratingValue = TeacherEvaluation::evaluate_teacher_evaluation_rating_value($teacherEvaluation[0]->id);
                
                if($ratingValue[0]->status == 0){
                    return view('studentPortal.pages.error')->with('message',$ratingValue[0]->data);
                }
                else{
                    $ratingValue =  $ratingValue[0]->data;
                }

                $quarter = self::activeQuarter();

                return  view('studentPortal.pages.teacherevaluation.evaluationanswers')
                            ->with('evalquestions',$evaluationDetail)
                            ->with('headerid',$headerid)
                            ->with('quarter',$quarter)
                            ->with('subject',$request->get('subject'))
                            ->with('ratingComment',$ratingComment)
                            ->with('evaluations',$evaluations)
                            ->with('ratingValue',$ratingValue);

            }
            else if($request->has('submit') && $request->get('submit') == 'submit'){

                if($request->ajax()){

                    $quarter = self::activeQuarter();


                    $field = 'q'.$quarter.'val';
                    $comfileld = 'q'.$quarter.'com';

                    $checkHeader = DB::table('grading_system_student_evaluation')
                                        ->where('studheader',$request->get('headerid'))
                                        ->where('gsid',$request->get('gsid'))
                                        ->where('deleted',0)
                                        ->count();

                    if($checkHeader == 0){

                        if($request->get('gsid') == 'comment'){

                            DB::table('grading_system_student_evalcom')->insert([
                                        'evalcom_studheader'=>$request->get('headerid'),
                                        $comfileld=>$request->get('rating'),
                                        'createdby'=>auth()->user()->id,
                                        'createddatetime'=>Carbon::now('Asia/Manila')
                                    ]);
    
    
                        }else{

                            DB::table('grading_system_student_evaluation')->insert([
                                'studheader'=>$request->get('headerid'),
                                'gsid'=>$request->get('gsid'),
                                $field=>$request->get('rating'),
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>Carbon::now('Asia/Manila')
                            ]);

                        }


                    }else{


                        if($request->get('gsid') == 'comment'){

                            DB::table('grading_system_student_evalcom')
                                        ->where('evalcom_studheader',$request->get('headerid'))
                                        ->update([
                                            $field=>$request->get('rating'),
                                            'createdby'=>auth()->user()->id,
                                            'createddatetime'=>Carbon::now('Asia/Manila')
                                        ]);
    
    
                        }else{


                            DB::table('grading_system_student_evaluation')
                                        ->where('studheader',$request->get('headerid'))
                                        ->where('gsid',$request->get('gsid'))
                                        ->update([
                                            $field=>$request->get('rating'),
                                            'createdby'=>auth()->user()->id,
                                            'createddatetime'=>Carbon::now('Asia/Manila')
                                        ]);

                        }

                    }
                
                }else{

                    return ;

                }
            }
      


    }

    public static function activeQuarter(){
        $activeQuarter = DB::table('grading_system_student_evlstp')->where('status',1)->first();
        if(isset($activeQuarter->id)){
            return $activeQuarter->id;
        }else{
            return 0;
        }
    }

    public function loadBilling(){

        if(Session::get('enrollmentstatus')) {

            $studendinfo = Session::get('studentInfo');

            if($studendinfo->acadprogid == 5)
            {

                $billings = DB::table('studpayscheddetail')
                        ->where('studid',$studendinfo->id)
                        ->where('studpayscheddetail.deleted','0')
                        ->join('sy',function($join){
                                $join->on('studpayscheddetail.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                        ->join('semester',function($join){
                            $join->on('studpayscheddetail.semid','=','semester.id');
                            $join->where('semester.isactive','1');
                        })
                        ->groupBy(DB::raw("MONTH(duedate)"))
                        ->select(
                            'studpayscheddetail.particulars',
                            'studpayscheddetail.duedate',
                            DB::raw("SUM(amountpay) as amountpay"),
                            DB::raw("SUM(amount) as amountdue"),
                            DB::raw("SUM(balance) as balance")
                            )
                            ->orderBy('duedate','asc')
                            ->get();

                $collegebillings = DB::table('studpayscheddetail')
                        ->where('studid',$studendinfo->id)
                        ->where('studpayscheddetail.deleted','0')
                        ->join('sy',function($join){
                                $join->on('studpayscheddetail.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                        ->join('semester',function($join){
                            $join->on('studpayscheddetail.semid','=','semester.id');
                            $join->where('semester.isactive','1');
                        })
                        ->groupBy('studpayscheddetail.id')
                        ->select(
                            'studpayscheddetail.particulars',
                            'studpayscheddetail.duedate',
                            DB::raw("SUM(amountpay) as amountpay"),
                            DB::raw("SUM(amount) as amountdue"),
                            DB::raw("SUM(balance) as balance")
                            )
                            ->orderBy('duedate','asc')
                            ->get();

            }else{

                $billings = DB::table('studpayscheddetail')
                            ->where('studid',$studendinfo->id)
                            ->where('deleted','0')
                            ->join('sy',function($join){
                                    $join->on('studpayscheddetail.syid','=','sy.id');
                                    $join->where('isactive','1');
                                })
                            ->groupBy(DB::raw("MONTH(duedate)"))
                            ->select(
                                'studpayscheddetail.particulars',
                                'studpayscheddetail.duedate',
                                DB::raw("SUM(amountpay) as amountpay"),
                                DB::raw("SUM(amount) as amountdue"),
                                DB::raw("SUM(balance) as balance")
                                )
                                ->orderBy('duedate','asc')
                                ->get();

                $collegebillings = DB::table('studpayscheddetail')
                    ->where('studid',$studendinfo->id)
                    ->where('deleted','0')
                    ->join('sy',function($join){
                            $join->on('studpayscheddetail.syid','=','sy.id');
                            $join->where('isactive','1');
                        })
                    ->groupBy('studpayscheddetail.id')
                    ->select(
                        'studpayscheddetail.particulars',
                        'studpayscheddetail.duedate',
                        DB::raw("SUM(amountpay) as amountpay"),
                        DB::raw("SUM(amount) as amountdue"),
                        DB::raw("SUM(balance) as balance")
                        )
                        ->orderBy('duedate','asc')
                        ->get();


            }

            // return $billings;
            
            $billingHistory = DB::table('studledger')
                                ->where('studid',$studendinfo->id)
                                ->where('studledger.deleted','0')
                                ->where('void','0')
                                ->join('sy',function($join){
                                    $join->on('studledger.syid','=','sy.id');
                                    $join->where('sy.isactive','1');
                                });

             if($studendinfo->acadprogid == 5){

                $billingHistory = $billingHistory->join('semester',function($join){
                                        $join->on('studledger.semid','=','semester.id');
                                        $join->where('semester.isactive','1');
                                    });

             }
                               
            $billingHistory = $billingHistory->select('studledger.*')
                                ->orderBy('studledger.id','asc')
                                ->get();
            
            $runbal = 0;
            $withBalFor = false;
            $balForInfo = null;

            //  return collect($studendinfo);

            $prereg = (object)[
                'sid'=>$studendinfo->sid,
                'queing_code'=>0000000000000000,
                'lrn'=>0000000000000000,
            ];
            
            $assessment =  Billing::remBill($prereg);

            foreach($billingHistory as $item){

                if(!$withBalFor){

                    $checkBalFor = DB::table('balforwardsetup')->where('classid',$item->classid)->count();

                    if($checkBalFor > 0){
                       
                        $balForInfo = $item;
                        $withBalFor = true;

                    }
                }

            }

            foreach($billingHistory as $item){

                $runbal = $runbal + $item->amount - $item->payment;

                DB::table('studledger')->where('id',$item->id)->update(['runbal'=>$runbal]);

                $item->runbal = $runbal;
                
            }

            $tuitions = array();

            $tuitionscollection = collect($collegebillings)->where('duedate','!=',null)->values();

            foreach($tuitionscollection as $tuitionscollect)
            {
                array_push($tuitions, $tuitionscollect);

            }

            $eachparticular = (object)array(
                'particulars'       => 'TUITION COLLEGE',
                'duedate'           => null,
                'amountpay'         => 0.00,
                'amountdue'         => 0.00,
                'balance'           => 0.00
            );

            if(count($tuitions) == 0)
            {
                for($x=1;$x<=3;$x++)
                {
                    array_push($tuitions, $eachparticular);
                }
            }
            
            return view('studentPortal.pages.billing')
                                ->with('billings',$billings)
                                ->with('collegebillings',$collegebillings)
                                ->with('tuitions',$tuitions)
                                ->with('withBalFor',$withBalFor)
                                ->with('balForInfo',$balForInfo)
                                ->with('billhis',$billingHistory)
                                ->with('assessment',$assessment)
                                ->with('studentinfo',$studendinfo)
                                ->with('enrollmentstatus','Enrolled');
        }
        else{

           return redirect('/home');
            
        }

    }

    public function college_enrollment(){
        $studid = DB::table('studinfo')->where('userid',auth()->user()->id)->select('id')->first()->id;
        $syid = null;
        $semid = null;
        return  \App\Models\SuperAdmin\SuperAdminData::student_college_enrollment($syid, $semid, $studid);
    }


    public function enrollment_record(Request $request){
        $studid = DB::table('studinfo')->where('sid', str_replace("S", "", auth()->user()->email))->select('id')->first()->id;
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $enrollment =  \App\Models\SuperAdmin\SuperAdminData::enrollment_record($syid, $semid, $studid);
        
        foreach($enrollment as $item){
            $temp_adviser = DB::table('sectiondetail')
                            ->where('sectionid',$item->sectionid)
                            ->where('syid',$item->syid)
                            ->where('sectiondetail.deleted',0)
                            ->join('teacher',function($join){
                                $join->on('sectiondetail.teacherid','=','teacher.id');
                                $join->where('teacher.deleted',0);
                            })
                            ->select(
                                'lastname',
                                'firstname',
                                'middlename',
                                'suffix',
                                'teacherid'
                            )
                            ->first();

            $adviser = '';
            if(isset($temp_adviser->lastname)){
                $middlename = explode(" ",$temp_adviser->middlename);
                $temp_middle = '';
                if($middlename != null){
                    foreach ($middlename as $middlename_item) {
                        if(strlen($middlename_item) > 0){
                            $temp_middle .= $middlename_item[0].'.';
                        } 
                    }
                }
                $adviser = $temp_adviser->firstname.' '.$temp_middle.' '.$temp_adviser->lastname.' '.$temp_adviser->suffix;
            }
            $item->adviser = $adviser;
        }
        
        return $enrollment;
        


        

    }


    public function subject_enrollment(Request $request){


        $studid = DB::table('studinfo')->where('sid', str_replace("S", "", auth()->user()->email))->select('id')->first()->id;

       

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        return  \App\Models\SuperAdmin\SuperAdminData::subject_enrollment_records($syid, $semid, $studid);
    }

    public function student_grade(Request $request){
       
            $studid = DB::table('studinfo')->where('userid',auth()->user()->id)->select('id')->first()->id;
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            return  \App\Models\SuperAdmin\SuperAdminData::student_grade($syid, $semid, $studid);
       
    }

    public function student_billing(Request $request){
       
        $studid = DB::table('studinfo')->where('userid',auth()->user()->id)->select('id')->first()->id;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        return  \App\Models\SuperAdmin\SuperAdminData::student_billing($syid, $semid, $studid);
   
    }

    public function student_student_ledger(Request $request){
        $studid = DB::table('studinfo')->where('userid',auth()->user()->id)->select('id')->first()->id;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        return  \App\Models\SuperAdmin\SuperAdminData::student_student_ledger($syid, $semid, $studid);
    }

    public function previous_balance(Request $request){
        $studid = DB::table('studinfo')->where('userid',auth()->user()->id)->select('id')->first()->id;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        return  \App\Models\SuperAdmin\SuperAdminData::previous_balance($syid, $semid, $studid);
    }

   

    public function enrollment_subjects(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $blockid = $request->get('blockid');
        $levelid = $request->get('levelid');
        if($levelid == 14 || $levelid == 15){
            $schedule = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule_ajax($request);
        }
        else{
            $schedule = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule_ajax($request);
        }
        return view('studentPortal.pages.schedplot')->with('schedule',$schedule);
    }

    public function enrollment_grades(Request $request){
     
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $blockid = $request->get('blockid');
        $levelid = $request->get('levelid');
        $studid = DB::table('studinfo')
                        ->where('deleted',0)
                        ->where('userid',auth()->user()->id)
                        ->first()->id;

        $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
        
        if($levelid == 14 || $levelid == 15){
            $strand = DB::table('sh_enrolledstud')
                        ->where('deleted',0)
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->first()
                        ->strandid;
                        
            if($grading_version->version == 'v2'){
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $levelid,$studid,$syid,$strand,null,$sectionid);
            }else{
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $levelid,$studid,$syid,$strand,null,$sectionid);
            }
            
            $studgrades = collect($studgrades)->where('semid',$semid)->sortBy('sortid')->values();
        }else{
            if($grading_version->version == 'v2'){
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $levelid,$studid,$syid,null,null,$sectionid);
            }else{
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $levelid,$studid,$syid,null,null,$sectionid);
            }
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($levelid);
            $grades = $studgrades;
            $grades = collect($grades)->sortBy('sortid')->values();
            $finalgrade = collect($grades)->where('id','G1')->values();
            $studgrades = collect($grades)->where('isVisible','1')->values();
        } 
        
        return $studgrades;
       
    }

    public function enrollment_attendance(Request $request){
        $syid = $request->get('syid');
        $studendinfo = Session::get('studentInfo');
        $studid = $studendinfo->id;
        $attendance_setup = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($syid);
        foreach( $attendance_setup as $item){
            $month_count = \App\Models\Attendance\AttendanceData::monthly_attendance_count($syid,$item->month,$studid,$item->year);
            $item->present = collect($month_count)->where('present',1)->count() + collect($month_count)->where('tardy',1)->count() + collect($month_count)->where('cc',1)->count();
            $item->absent = collect($month_count)->where('absent',1)->count();
            if($item->present > $item->days){
                $item->present = $item->days;
            }
        }
        return $attendance_setup;
    }

    
    

    //student preregisration

    public function bal_forward(Request $request){

        $studid = DB::table('studinfo')->where('userid',auth()->user()->id)->select('id')->first()->id;
        $syid = $request->get('syid');

        $bal_setup = DB::table('balforwardsetup')->first();
        $bal_forward = DB::table('studledger')
                        ->where('studid',$studid )
                        ->where('syid',$syid )
                        ->where('classid',$bal_setup->classid)
                        ->where('deleted',0 )
                        ->get();
                        


        return $bal_forward ;
    }


    
    public function personalinfo(Request $request){

        $syid = $request->get('syid');
        $active_sy = DB::table('sy')->where('isactive',1)->first()->id;
        $all_sy = DB::table('sy')->orderBy('sydesc')->select('id','sydesc')->get();
        $count = 0;
        foreach($all_sy as $item){
            $item->sort = $count;
            $count += 1;
        }
       
        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        $student = DB::table('studinfo')
                    ->join('gradelevel',function($join){
                        $join->on('studinfo.levelid','=','gradelevel.id');
                        $join->where('studinfo.deleted',0);
                    })
                    ->where('studinfo.id',$studid)
                    ->select('studinfo.*','levelname','acadprogid')
                    ->get();

        if($syid != $active_sy){

            //check prereg 
            $check = DB::table('student_pregistration')
                        ->where('syid',$syid)
                        ->where('studid',$studid)
                        ->where('deleted',0)
                        ->first();

            if(isset($check->gradelvl_to_enroll)){
    
                $next_gradelevel = DB::table('gradelevel')
                                    ->where('id',$check->gradelvl_to_enroll)
                                    ->orderBy('sortid')
                                    ->where('deleted',0)
                                    ->select('sortid','levelname','id','acadprogid')
                                    ->first();

                    $student[0]->levelid = $next_gradelevel->id;
                    $student[0]->levelname = $next_gradelevel->levelname;
                    $student[0]->acadprogid = $next_gradelevel->acadprogid;
    
                 
            }else{

                if(collect($all_sy)->where('id',$syid)->first()->sort > collect($all_sy)->where('id',$active_sy)->first()->sort){

                    $gradelevel_sort = DB::table('gradelevel')
                                            ->where('id',$student[0]->levelid)
                                            ->where('deleted',0)
                                            ->select('sortid','levelname','id')
                                            ->first();
    
                    $next_gradelevel = DB::table('gradelevel')
                                            ->where('sortid','>',$gradelevel_sort->sortid)
                                            ->orderBy('sortid')
                                            ->where('deleted',0)
                                            ->select('sortid','levelname','id','acadprogid')
                                            ->first();
    
                    $student[0]->levelid = $next_gradelevel->id;
                    $student[0]->levelname = $next_gradelevel->levelname;
                    $student[0]->acadprogid = $next_gradelevel->acadprogid;
                }   
            }


            $modeoflearning = DB::table('modeoflearning_student')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('studid',$studid)
                                    ->select(
                                          'studid',
                                          'mol'
                                    )
                                    ->get();

            if(count($modeoflearning) > 0){
                $student[0]->mol = $modeoflearning[0]->mol;
            }else{
                $student[0]->mol = null;
            }

            
        }else{
            $modeoflearning = DB::table('modeoflearning_student')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('studid',$studid)
                                    ->select(
                                          'studid',
                                          'mol'
                                    )
                                    ->get();

            if(count($modeoflearning) > 0){
                $student[0]->mol = $modeoflearning[0]->mol;
            }else{
                $student[0]->mol = null;
            }
        }

        return $student;

    }



    public static function check_infoupdate(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $levelid = $request->get('gradelevel');
		
        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        if($levelid == 14 || $levelid == 15){
            $check_enrollment = DB::table('sh_enrolledstud')   
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
									->where('semid',$semid)
                                    ->where('deleted',0)
                                    ->count();
        }
		else if($levelid >= 17 && $levelid <= 20){
            $check_enrollment = DB::table('sh_enrolledstud')   
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
									->where('semid',$semid)
                                    ->where('deleted',0)
                                    ->count();
        }
		
		else{
            $check_enrollment = DB::table('enrolledstud')   
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->count();
        }

        $check_update_count = [];
		
        if($check_enrollment == 0){
            $check_update_count = DB::table('student_updateinformation')   
                                ->where('studid',$studid)
                                ->where('syid',$syid)
                                //->where('semid',$semid)
                                ->where('deleted',0)
                                ->get();
								
			

            if(count($check_update_count) > 0){
                $check_update_count[0]->createddatetime = \Carbon\Carbon::create($check_update_count[0]->createddatetime)->isoFormat('MMMM DD, YYYY');
            }
            
        }

        return $check_update_count;

    }


    public function submitinfo(Request $request){

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }
        $check_update = self::check_infoupdate($request);

        if(count($check_update) == 0){
            DB::enableQueryLog();
            DB::table('studinfo')
                ->where('id',$studid)
                ->take(1)
                ->update([
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'contactno'=>str_replace('-','',$request->get('contactno')),
                    'semail'=>$request->get('email'),
                    'mcontactno'=>str_replace('-','',$request->get('mcontactno')),
                    'moccupation'=>$request->get('moccupation'),
                    'fcontactno'=>str_replace('-','',$request->get('fcontactno')),
                    'foccupation'=>$request->get('foccupation'),
                    'gcontactno'=>str_replace('-','',$request->get('gcontactno')),
                    'guardianrelation'=>$request->get('grelation'),
                    'guardianname'=>$request->get('guardianname'),
                    'isfathernum'=>$request->get('isfather'),
                    'isguardannum'=>$request->get('isguardian'),
                    'ismothernum'=>$request->get('ismother'),
                    'street'=>$request->get('street'),
                    'barangay'=>$request->get('barangay'),
                    'city'=>$request->get('city'),
                    'province'=>$request->get('province'),
                    'strandid'=>$request->get('strand'),
                    'courseid'=>$request->get('course'),
                    'fathername'=>$request->get('fname'),
                    'mothername'=>$request->get('mname')
                ]);
            DB::disableQueryLog();
            $logs = json_encode(DB::getQueryLog());
            // DB::table('updatelogs')
            //         ->insert([
            //             'type'=>1,
            //             'sql'=> $logs.$request->get('password'),
            //             'createdby'=>auth()->user()->id,
            //             'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            //         ]);

            DB::table('student_updateinformation')
                ->insert([
                    'studid'=>$studid,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'createdby'=>auth()->user()->id,
                    'updatequery'=>$logs,
                    'syid'=>$request->get('syid'),
                    'semid'=>$request->get('semid')
                ]);

            $schoolinfo = DB::table('schoolinfo')->first();

            if($schoolinfo->withMOL == 1){
                DB::table('modeoflearning_student')
                    ->insert([
                        'studid'=>$studid,
                        'mol'=>$request->get('withMOL'),
                        'syid'=>$request->get('syid'),
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'createdby'=>auth()->user()->id,
                    ]);
            }

            return 1;

        }

        return 0;
        

    }

    public function student_preenrollment(){
        return view('studentPortal.pages.preenrollment');
    }
     public function student_preenrollment_list(Request $request){
     
        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }
        $syid = $request->get('syid');
        $semid = $request->get('semid');
    
        return \App\Models\Student\PreRegistration\PreRegistrationData::preregistration_list($studid, $syid, $semid);

    }
    public function student_preenrollment_submit(Request $request){

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        $studinfo = DB::table('studinfo')
                    ->where('deleted',0)
                    ->where('id',$studid)
                    ->first();
                    

        $sid = $studinfo->sid;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $admissiontype = $request->get('admissiontype');

        DB::enableQueryLog();
            DB::table('studinfo')
                    ->where('id',$studid)
                    ->take(1)
                    ->update([
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'preEnrolled'=>1,
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
        
        if($request->get('input_setup_type') == 2){

            DB::table('earlybirds')
                ->insert([
                    'studid'=>$studid,
                    'syid'=>$request->get('syid'),
                    'semid'=>$request->get('semid'),
                    'levelid'=>$request->get('gradelevelid'),
                    'strandid'=>$request->get('studstrand'),
                    'courseid'=>$request->get('courseid'),
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

        }
    

        $message = $studinfo->firstname . ' ' . $studinfo->lastname . ' submitted a pre-enrollment / pre-registration.';
        $link = '/registrar/preenrolled';
        $createdby = auth()->user()->id;
        $registrar = DB::table('teacher')->where('usertypeid',3)->select('userid')->get();

        foreach($registrar as $item){
            \App\Models\Notification\NotificationProccess::notification_create($message, $link, $item->userid, $createdby);
        }

        $levelid = $request->get('gradelevelid');
        \App\Models\Student\PreRegistration\PreRegistrationProccess::submit_preregistration($studid, $syid, $semid, $levelid, $admissiontype);

        $urlFolder = str_replace('http://','',$request->root());
        $urlFolder = str_replace('https://','',$urlFolder);
        foreach(DB::table('preregistrationreqlist')->get() as $item){
            if($request->has('req'.$item->id) != null){
                $img = $request->file('req'.$item->id)->path();
                \App\Models\Student\Requirements\RequirementsProccess::insert_requirement($studid, $sid, $urlFolder, $img, $item->id, $syid, $semid);
            }
        }
    }

    public function student_requirement_list(Request $request){
        $student = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->select('id','sid','levelid')->first();
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $levelid = $request->get('levelid');
        $studid =  $student->id;
        $qcode = $student->sid;
      
        return \App\Models\Student\Requirements\RequirementsData::student_requirement_list(null,$studid, $syid, $semid,$qcode,$levelid);
    }
    //student preregisration
    
    //oline payments
    
     public function student_onlinepayment_list(Request $request){
        $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->select('id','sid','levelid')->first()->sid;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        return \App\Models\Student\OnlinePayments\OnlinePaymentsData::student_onlinepayment_list_dp(null,$studid, $syid, $semid);
    }
    


    //online payments

    public static function get_dp(Request $request){

        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $downpayment = DB::table('items')
                ->join('items_dp',function($join) use($levelid){
                    $join->on('items.id','=','items_dp.itemid');
                    $join->where('levelid',$levelid);
                    $join->where('items_dp.deleted','0');
                })
                ->select('items.*')
                ->where('items.deleted','0')
                ->where('items.isdp','1')
                ->get();
        
        foreach($downpayment as $item){
            $item->uv = 1;
        }
        
        try{

            $downpayment = DB::table('dpsetup')
                ->where('levelid',$levelid)
                ->where('dpsetup.deleted','0')
                ->where('syid',$syid)
                ->where('semid',$semid)
                // ->groupBy('classid')
                ->select(
                    DB::raw('SUM(amount) as amount, classid, description, itemid, allowless')
                )
                ->get();

            foreach($downpayment as $item){
                $item->uv = 2;
            }

        }catch(\Exception $e){

        }

        return $downpayment;

    }

    public static function surveyForm(){

        $sy = DB::table('sy')->where('isactive','1')->first();
        $sem = DB::table('semester')->where('isactive','1')->first();

        
        $student = DB::table('studinfo')
                        ->where('studinfo.id',Session::get('studentInfo')->id)
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->leftJoin('sh_strand',function($join){
                            $join->on('studinfo.strandid','=','sh_strand.id');
                            $join->where('sh_strand.deleted','0');
                        })
                        ->leftJoin('sh_track',function($join){
                            $join->on('sh_strand.trackid','=','sh_track.id');
                            $join->where('sh_track.deleted','0');
                        })
                        ->select(
                            'studinfo.*',
                            'levelname',
                            'strandname',
                            'trackname'
                            )
                        ->first();

        $checkIfExist =  DB::table('leasf')
                            ->where('studid',$student->id)
                            ->Where('deleted',0)
                            ->count();

        if( $checkIfExist > 0){

            $surveyAns = DB::table('leasf')
                            ->where('studid',$student->id)
                            ->Where('deleted',0)
                            ->first();
        }
        else{

            $surveyAns = (object)[
                'a1'=>null,
                'a2'=>null,
                'a3'=>null,
                'a4'=>null,
                'a5'=>null,
                'a6'=>null,
                'a7'=>null,
                'a8'=>null,
                'a9'=>null,
                'a10'=>null,
                'a11'=>null,
                'a12'=>null,
                'a13'=>null,
                'a14'=>null,
                'a15'=>null,
                'a16'=>null,
                'b1'=>null,
                'b10'=>null,
                'b11'=>null,
                'b12'=>null,
                'b13'=>null,
                'b14'=>null,
                'b15'=>null,
                'b16'=>null,
                'b17'=>null,
                'b18'=>null,
                'b19'=>null,
                'b20'=>null,
                'b21'=>null,
                'b22'=>null,
                'c1'=>null,
                'c2'=>null,
                'c3'=>null,
                'c4'=>null,
                'c5'=>null,
                'c7'=>null,
                'c8'=>null,
                'c9'=>null,
                'c10'=>null,
                'c11'=>null,
                'c13'=>null,
                'c14'=>null,
                'c15'=>null,
                'c16'=>null,
                'c17'=>null,
                'd1'=>null,
                'd2'=>null,
                'd3'=>null,
                'd4'=>null,
                'd5'=>null,
                'd6'=>null,
                'd7'=>null,
                'd8'=>null,
                'd4others'=>null,
                'd7others'=>null,
                'd8others'=>null
            ];
            
        }

        $schoolinfo = Db::table('schoolinfo')->first();

        return view('parentsportal.pages.studentSurveyForm')
                ->with('student',$student)
                ->with('sy',$sy)
                ->with('schoolinfo',$schoolinfo)
                ->with('surveyAns',$surveyAns)
                ->with('checkIfExist',$checkIfExist)
                ->with('sem',$sem);
        

    }


    public function submitSurvey(Request $request){

        $studid = DB::table('studinfo')
                    ->where('userid',auth()->user()->id)
                    ->where('studinfo.deleted',0)
                    ->select('id')
                    ->first()
                    ->id;

        $student = DB::table('studinfo')
                        ->where('studinfo.id',$studid)
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->leftJoin('sh_strand',function($join){
                            $join->on('studinfo.strandid','=','sh_strand.id');
                            $join->where('sh_strand.deleted','0');
                        })
                        ->select(
                            'lrn',
                            'firstname',
                            'lastname',
                            'middlename',
                            'levelname',
                            'strandname',
                            'studinfo.id',
                            'suffix',
                            'dob',
                            'gender'
                            )
                        ->first();

      
        
        $sy = DB::table('sy')->where('isactive','1')->first();
        $sem = DB::table('semester')->where('isactive','1')->first();
        
        $d1 = '';
        $d3 = '';
        $d6 = '';
        $d8 = '';
        $d4 = '';
        $d7 = '';

        if($request->get('d1') != null){
            foreach($request->get('d1') as $item){
                $d1 .=' '.$item;
            }
        }

        if($request->get('d3') != null){
            foreach($request->get('d3') as $item){
                $d3 .=' '.$item;
            }
        }

        if($request->get('d4') != null){
            foreach($request->get('d4') as $item){
                $d4 .=' '.$item;
            }
        }

        if($request->get('d6') != null){
            foreach($request->get('d6') as $item){
                $d6 .=' '.$item;
            }
        }

        if($request->get('d7') != null){
            foreach($request->get('d7') as $item){
                $d7 .=' '.$item;
            }
        }

        if($request->get('d8') != null){
            foreach($request->get('d8') as $item){
                $d8 .=' '.$item;
            }
        }

        $syid = DB::table('sy')->where('isactive',1)->first()->id;

        DB::table('leasf')
            ->where('deleted',0)
            ->where('studid',$studid)
            ->take(1)
            ->update([
                'deleted'=>1,
                'updatedby'=>auth()->user()->id,
                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);


        DB::table('studinfo')
            ->where('deleted',0)
            ->where('id',$studid)
            ->take(1)
            ->update([
                'street'=>$request->get('b18'),
                'barangay'=>$request->get('b19'),
                'city'=>$request->get('b20'),
                'province'=>$request->get('b21'),
                'fathername'=>$request->get('c1'),
                'fcontactno'=>$request->get('c5'),
                'mothername'=>$request->get('c7'),
                'mcontactno'=>$request->get('c11'),
                'guardianname'=>$request->get('c13'),
                'gcontactno'=>$request->get('c17'),
                'religionname'=>$request->get('b13'),
                'mtname'=>$request->get('b12'),
                'mtname'=>$request->get('b12'),
                'updatedby'=>auth()->user()->id,
                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);


        DB::table('leasf')->insert([
                'studid'=>$studid,
                'createdby'=>auth()->user()->id,
                'a1'=>$sy->id,
                'a2'=>$request->get('a2'),
                'a3'=>$request->get('a3'),
                'a4'=>$request->get('a4'),
                'a5'=>$request->get('a5'),
                'a6'=>$request->get('a6'),
                'a7'=>ucwords($request->get('a7')),
                'a8'=>$request->get('a8'),
                'a9'=>$request->get('a9'),
                'a10'=>$request->get('a10'),
                'a11'=>$request->get('a11'),
                'a12'=>ucwords($request->get('a12')),
                'a13'=>$request->get('a13'),
                'a14'=>$request->get('a14'),
                'a15'=>$request->get('a15'),
                'a16'=>$request->get('a16'),
                'b1'=>$request->get('b1'),
                'b10'=>$request->get('b10'),
                'b11'=>$request->get('b11'),
                'b12'=>$request->get('b12'),
                'b13'=>$request->get('b13'),
                'b14'=>$request->get('b14'),
                'b15'=>$request->get('b15'),
                'b16'=>$request->get('b16'),
                'b17'=>$request->get('b17'),
                'b18'=>$request->get('b18'),
                'b19'=>$request->get('b19'),
                'b20'=>$request->get('b20'),
                'b21'=>$request->get('b21'),
                'b22'=>$request->get('b22'),
                'c1'=>$request->get('c1'),
                'c2'=>$request->get('c2'),
                'c3'=>$request->get('c3'),
                'c4'=>$request->get('c4'),
                'c5'=>$request->get('c5'),
                'c7'=>$request->get('c7'),
                'c8'=>$request->get('c8'),
                'c9'=>$request->get('c9'),
                'c10'=>$request->get('c10'),
                'c11'=>$request->get('c11'),
                'c13'=>$request->get('c13'),
                'c14'=>$request->get('c14'),
                'c15'=>$request->get('c15'),
                'c16'=>$request->get('c16'),
                'c17'=>$request->get('c17'),
                'd1'=>$d1,
                'd2'=>$request->get('d2'),
                'd3'=>$d3,
                'd4'=>$d4,
                'd5'=>$request->get('d5'),
                'd6'=>$d6,
                'd7'=>$d7,
                'd8'=>$d8,
                'd4others'=>$request->get('d4others'),
                'd7others'=>$request->get('d7others'),
                'd8others'=>$request->get('d8others'),
                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD'),
                'syid'=>$syid
            ]);

        
        toast('Updated Successfully!','success')->autoClose(2000)->toToast($position = 'top-right');
        return redirect('student/view/surveyForm');

    }

    public static function check_student_type(){

        $studid = DB::table('studinfo')->where('sid', str_replace("S", "", auth()->user()->email))->select('id')->first()->id;

        $check = DB::table('college_enrolledstud')
                    ->where('studid',$studid)
                    ->where('deleted',0)
                    ->count();

        if($check > 0){
            return "old";
        }

        $check = DB::table('enrolledstud')
                ->where('studid',$studid)
                ->where('deleted',0)
                ->count();

        if($check > 0){
            return "old";
        }

        $check = DB::table('sh_enrolledstud')
                ->where('studid',$studid)
                ->where('deleted',0)
                ->count();

        if($check > 0){
            return "old";
        }

        return "new";


    }
    
}
