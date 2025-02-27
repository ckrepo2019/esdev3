<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Grading\PendingGrades;
use App\Models\Grading\GradingSystem;
use App\Models\Grading\HighSchool;
use App\Models\Grading\GradeSchool;
use App\Models\Grading\SeniorHigh;
use \Carbon\Carbon;
use Session;

class TeacherGradingV2 extends \App\Http\Controllers\Controller
{

    public static function update_grade_section(){

        $syid = 11;
        $semid = 3;

        $grades = DB::table('college_studentprospectus')
                        ->where('syid',$syid)
                        ->semid('semid',$semid)
                        ->get();


        foreach($grades as $item){

            $get_sched = DB::table('college_studsched')
                            ->where('studid',$item->studid)
                            ->get();

        }
        
        


    }



    //11132021 - grades
    //11132021 - grades
    public static function check_pending(Request $request){

        $subject = array();

        $syid = DB::table('sy')
                    ->where('isactive',1)
                    ->first()
                    ->id;

        $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->first()->id;
        
       

        $sched = DB::table('sh_classsched')
                ->where('sh_classsched.syid',$syid)
                ->where('sh_classsched.deleted',0)
                ->where('sh_classsched.teacherid',$teacherid)
                 ->join('sh_subjects',function($join){
                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                    $join->where('sh_subjects.deleted',0);
                })
                ->select(
                    'sectionid',
                    'subjid',
                    'glevelid as levelid',
                    'sectionid',
                    'sh_isSP as isSP'
                )
                ->get();

        foreach($sched as $item){
        
            $check_if_exist_in_plot = DB::table('subject_plot')
                                            ->where('deleted',0)
                                            ->where('levelid',$item->levelid)
                                            ->where('subjid',$item->subjid)
                                            ->where('syid',$syid)
                                            ->count();

            if($check_if_exist_in_plot > 0){
                    array_push($subject, $item);
            }      

        }

        $asssubj = DB::table('assignsubj')
                ->where('assignsubj.syid',$syid)
                ->where('assignsubj.deleted',0)
                ->join('assignsubjdetail',function($join) use($teacherid){
                    $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                    $join->where('assignsubjdetail.deleted',0);
                    $join->where('assignsubjdetail.teacherid',$teacherid);
                })
                ->join('subjects',function($join){
                    $join->on('assignsubjdetail.subjid','=','subjects.id');
                    $join->where('subjects.deleted',0);
                })
                ->select(
                    'sectionid',
                    'subjid',
                    'glevelid as levelid',
                    'sectionid',
                    'isSP'
                )
                ->get();
                
               
        foreach($asssubj as $item){

            $check_if_exist_in_plot = DB::table('subject_plot')
                                            ->where('deleted',0)
                                            ->where('levelid',$item->levelid)
                                            ->where('subjid',$item->subjid)
                                            ->where('syid',$syid)
                                            ->select(
                                                'subject_plot.id'
                                             )
                                            ->count();

            if($check_if_exist_in_plot > 0){
                    array_push($subject, $item);
            }                    
        }
        
        //   if($teacherid == 5){
        //     return $subject;
        // }

        $with_pending = false;
        $section_pending_count = 0;
        $student_pending_count = 0;



       
        foreach($subject as $item){
            
            
            if($item->isSP == 0){
                 
                $grades = DB::table('grades')
                            ->where('levelid',$item->levelid)
                            ->where('sectionid',$item->sectionid)
                            ->where('syid',$syid)
                            ->where('subjid',$item->subjid)
                            ->where('deleted',0)
                            ->where('status',3)
                            ->count();

                if($grades > 0){
                    $section_pending_count +=  $grades;
                    $with_pending = true;
                }

                $temp_students = array();

                $acad = DB::table('gradelevel')
                            ->where('id',$item->levelid)
                            ->first();

                if($acad->acadprogid == 5){
                    $enrolledstud = DB::table('sh_enrolledstud')
                        ->where('sh_enrolledstud.sectionid',$item->sectionid)
                        ->where('sh_enrolledstud.levelid',$item->levelid)
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.deleted',0)
                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                        ->select(
                            'sh_enrolledstud.studid'
                        )
                        ->get();
                }else{
                    $enrolledstud = DB::table('enrolledstud')
                        ->where('enrolledstud.sectionid',$item->sectionid)
                        ->where('enrolledstud.levelid',$item->levelid)
                        ->where('enrolledstud.syid',$syid)
                        ->where('enrolledstud.deleted',0)
                        ->whereIn('enrolledstud.studstatus',[1,2,4])
                        ->select(
                            'enrolledstud.studid'
                        )
                        ->get();
                }

            
                $temp_students = $enrolledstud->merge($enrolledstud);  
				
				$enrolledstud  = $temp_students;
                          
                $grades = DB::table('grades')
                            ->where('levelid',$item->levelid)
                            ->where('sectionid',$item->sectionid)
                            ->where('syid',$syid)
                            ->where('subjid',$item->subjid)
                            ->where('deleted',0)
                            ->where('status','!=',3)
                            ->join('gradesdetail',function($join) use($teacherid, $enrolledstud){
                                $join->on('grades.id','=','gradesdetail.headerid');
                                $join->where('gradesdetail.gdstatus',3);
                                $join->whereIn('gradesdetail.studid',collect($enrolledstud)->pluck('studid'));
                            })
                            ->count();
            }else{
                
                
                $grades = DB::table('grades')
                            ->where('levelid',$item->levelid)
                            ->where('sectionid',$item->sectionid)
                            ->where('syid',$syid)
                            ->where('subjid',$item->subjid)
                            ->where('deleted',0)
                            ->where('status',3)
                            ->count();
                            
                $enrolledstud = DB::table('enrolledstud')
                                    ->join('subjects_studspec',function($join) use($item,$syid){
                                        $join->on('enrolledstud.studid','=','subjects_studspec.studid');
                                        $join->where('subjects_studspec.deleted',0);
                                        $join->where('subjects_studspec.syid',$syid);
                                        $join->where('subjects_studspec.subjid',$item->subjid);
                                    })
                                    ->where('enrolledstud.sectionid',$item->sectionid)
                                    ->where('enrolledstud.levelid',$item->levelid)
                                    ->where('enrolledstud.syid',$syid)
                                    ->where('enrolledstud.deleted',0)
                                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                                    ->select(
                                        'subjects_studspec.studid'
                                    )
                                    ->disctinct()
                                    ->get();

                $enrolledstud = $temp_students;
                                    
                
                // return $temp_students;

                if($grades > 0){
                    $section_pending_count +=  $grades;
                    $with_pending = true;
                }
                          
                $grades = DB::table('grades')
                            ->where('levelid',$item->levelid)
                            ->where('sectionid',$item->sectionid)
                            ->where('syid',$syid)
                            ->where('subjid',$item->subjid)
                            ->where('deleted',0)
                            ->where('status','!=',3)
                            ->join('gradesdetail',function($join) use($teacherid,$enrolledstud){
                                $join->on('grades.id','=','gradesdetail.headerid');
                                $join->where('gradesdetail.gdstatus',3);
                                $join->whereIn('gradesdetail.studid',collect($enrolledstud)->pluck('studid'));
                            })
                            ->count();
                
                
            }

           

            if($grades > 0){
                $student_pending_count += $grades;
                $with_pending = true;
            }

        }   

           

        return array((object)[
                'with_pending'=>$with_pending,
                'student_pending_count'=>$student_pending_count,
                'section_pending_count'=>$section_pending_count,
            ]);

    }

    public static function get_grade_header(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $gradelevelid = $request->get('gradelevelid');
        $subjectid = $request->get('subjectid');
        $quarter =  $request->get('quarter');
        $sectionid =  $request->get('sectionid');

        $grade_header = DB::table('grades')
                            ->where('sectionid',$sectionid)
                            ->where('levelid',$gradelevelid)
                            ->where('quarter',$quarter)
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->where('subjid',$subjectid)
                            ->get();

        return $grade_header;

    }
    
    public function getGrades(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $gradelevelid = $request->get('gradelevelid');
        $subjectid = $request->get('subjectid');
        $quarter =  $request->get('quarter');
        $sectionid =  $request->get('sectionid');
        

        if($request->get('syid') == null){
            $syid =  DB::table('sy')->where('isactive',1)->select('id')->first()->id;
        }

        if($request->get('semid') == null){
            $semid =  DB::table('semester')->where('isactive',1)->select('id')->first()->id;
        }

        $levelInfo = DB::table('gradelevel')->where('id',$gradelevelid)->select('id','acadprogid')->first();

        $gradesetup = DB::table('subject_plot')
                        ->where('syid',$syid)
                        ->where('levelid',$gradelevelid)
                        ->where('subjid',$subjectid)
                        ->where('strandid',$request->get('strandid'))
                        ->where('subject_plot.deleted',0)
                        ->join('subject_gradessetup',function($join){
                            $join->on('subject_plot.gradessetup','=','subject_gradessetup.id');
                            $join->where('subject_gradessetup.deleted',0);
                        })
                        ->select(
                            'subject_plot.id',
                            'ww as writtenworks',
                            'pt as performancetask',
                            'qa as qassesment'
                        )
                        ->first();

       
        if($levelInfo->acadprogid != 5){

            $semid = 1;
        }
		
		$checkteachergradesetup = DB::table('teacher_subjectcomponentdetail')
										->where('syid',$syid)
										->where('subjid',$subjectid)
										->where('sectionid',$sectionid)
										->where('teacher_subjectcomponentdetail.deleted',0)
										 ->join('subject_gradessetup',function($join){
											$join->on('teacher_subjectcomponentdetail.subjcomid','=','subject_gradessetup.id');
											$join->where('subject_gradessetup.deleted',0);
										})
										 ->select(
											'teacher_subjectcomponentdetail.id',
											'ww as writtenworks',
											'pt as performancetask',
											'qa as qassesment'
										)
										->first();

										
		if(isset($checkteachergradesetup)){
			$gradesetup = $checkteachergradesetup;
		}

        if(!isset($gradesetup->id)){
            return "NGS";
        }

        $enrolledstud = [];



        $grade_header = DB::table('grades')
                            ->where('sectionid',$sectionid)
                            ->where('levelid',$gradelevelid)
                            ->where('quarter',$quarter)
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->where('subjid',$subjectid)
                            ->get();

        if($levelInfo->acadprogid != 5){

            $check_subject = DB::table('subjects')
                    ->where('deleted',0)
                    ->where('id',$subjectid)
                    ->first();

            if( isset($check_subject->isSP)){
                if($check_subject->isSP == 1){
                    $enrolledstud = DB::table('enrolledstud')
                                        ->join('studinfo',function($join){
                                            $join->on('enrolledstud.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                        })
                                        ->join('subjects_studspec',function($join) use($subjectid,$quarter,$syid){
                                            $join->on('enrolledstud.studid','=','subjects_studspec.studid');
                                            $join->where('subjects_studspec.deleted',0);
                                            $join->where('subjects_studspec.syid',$syid);
                                            $join->where('subjects_studspec.subjid',$subjectid);
                                            if($quarter == 1){
                                                $join->where('subjects_studspec.q1',1);
                                            }else if($quarter == 2){
                                                $join->where('subjects_studspec.q2',1);
                                            }else if($quarter == 3){
                                                $join->where('subjects_studspec.q3',1);
                                            }else if($quarter == 4){
                                                $join->where('subjects_studspec.q4',1);
                                            }
                                        })
                                        ->where('enrolledstud.sectionid',$sectionid)
                                        ->where('enrolledstud.levelid',$gradelevelid)
                                        ->where('enrolledstud.syid',$syid)
                                        ->where('enrolledstud.deleted',0)
                                        //->whereIn('enrolledstud.studstatus',[1,2,4])
                                        ->where('enrolledstud.studstatus','!=',0)
                                        ->orderBy('gender','desc')
                                        ->orderBy('lastname')
                                        ->select(
                                            'enrolledstud.studid',
                                            'firstname',
                                            'lastname',
                                            'middlename',
                                            'suffix',
                                            'mol',
                                            'gender',
                                            'enrolledstud.studstatus',
                                            DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                        )
                                        ->orderBy('gender','desc')
                                        ->orderBy('studentname','asc')
                                        ->get();
                }else{

                    $temp_students = array();

                    $enrolledstud = DB::table('enrolledstud')
                        ->join('studinfo',function($join){
                            $join->on('enrolledstud.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->where('enrolledstud.sectionid',$sectionid)
                        ->where('enrolledstud.levelid',$gradelevelid)
                        ->where('enrolledstud.syid',$syid)
                        ->where('enrolledstud.deleted',0)
                        // ->whereIn('enrolledstud.studstatus',[1,2,4])
                        ->where('enrolledstud.studstatus','!=',0)
                        ->orderBy('gender','desc')
                        ->orderBy('lastname')
                        ->select(
                            'studid',
                            'firstname',
                            'lastname',
                            'middlename',
                            'suffix',
                            'mol',
                            'gender',
                            'enrolledstud.studstatus',
                            DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('gender','desc')
                        ->orderBy('studentname','asc')
                        ->get();

                    foreach($enrolledstud as $item){
                        array_push($temp_students,$item);
                    }

                    $enrolledstud = DB::table('student_specsubj')
                            ->join('studinfo',function($join){
                                $join->on('student_specsubj.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->join('enrolledstud',function($join) use($syid){
                                $join->on('student_specsubj.studid','=','enrolledstud.studid');
                                // $join->whereIn('enrolledstud.studstatus',[1,2,4]);
                                $join->where('enrolledstud.studstatus','!=',0);
                                $join->where('enrolledstud.deleted',0);
                                $join->where('enrolledstud.syid',$syid);
                            })
                            ->where('student_specsubj.status','ADDITIONAL')
                            ->where('student_specsubj.syid',$syid)
                            ->where('student_specsubj.deleted',0)
                            ->where('student_specsubj.sectionid',$sectionid)
                            ->where('student_specsubj.subjid',$subjectid)
                            ->select(
                                'enrolledstud.studid',
                                'firstname',
                                'lastname',
                                'middlename',
                                'suffix',
                                'mol',
                                'gender',
                                'enrolledstud.studstatus',
                                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                            )
                            ->get();

                    foreach($enrolledstud as $item){
                        array_push($temp_students,$item);
                    }

                    $enrolledstud = collect($temp_students)->sortBy(function($item) {
                        $gsort = $item->gender == 'MALE' ? 0 : 1;
                        return $gsort.'-'.$item->studentname;
                    })->values();
                }
            }

           

        }else{

            $temp_strand = $request->get('strandid');

            if($temp_strand != null){
                $subject_strand = DB::table('sh_strand')
                                    ->where('id',$temp_strand)
                                    ->select('id as strandid')
                                    ->get();
            }else{
                $subject_strand = DB::table('subject_plot')
                                        ->where('syid',$syid)
                                        ->where('levelid',$gradelevelid)
                                        ->where('subjid',$subjectid)
                                        ->where('semid',$semid)
                                        ->where('deleted',0)
                                        ->select('strandid')
                                        ->get();

            }

     
           
            $temp_strand = array();

            foreach($subject_strand as $item){
                array_push($temp_strand , $item->strandid);
            }

            $temp_students = array();

            $enrolledstud = DB::table('sh_enrolledstud')
                            ->join('studinfo',function($join){
                                $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->join('sh_strand',function($join){
                                $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                $join->where('sh_strand.deleted',0);
                            })
                            ->where('sh_enrolledstud.sectionid',$sectionid)
                            ->where('sh_enrolledstud.levelid',$gradelevelid)
                            ->where('sh_enrolledstud.syid',$syid)
                            ->where('sh_enrolledstud.semid',$semid)
                            ->where('sh_enrolledstud.deleted',0)
                            // ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                            ->where('sh_enrolledstud.studstatus','!=',0)
                            ->whereIn('sh_enrolledstud.strandid',$temp_strand)
                            ->orderBy('gender','desc')
                            ->orderBy('lastname')
                            ->select(
                                'studid',
                                'firstname',
                                'lastname',
                                'middlename',
                                'suffix',
                                'mol',
                                'gender',
                                'strandcode',
                                'sh_enrolledstud.studstatus',
                                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                            )
                            ->get();

                foreach($enrolledstud as $item){
                    array_push($temp_students,$item);
                }

                $enrolledstud = DB::table('student_specsubj')
                            ->join('studinfo',function($join){
                                $join->on('student_specsubj.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->join('sh_enrolledstud',function($join) use($syid,$semid){
                                $join->on('student_specsubj.studid','=','sh_enrolledstud.studid');
                                // $join->whereIn('sh_enrolledstud.studstatus',[1,2,4]);
                                $join->where('sh_enrolledstud.studstatus','!=',0);
                                $join->where('sh_enrolledstud.deleted',0);
                                $join->where('sh_enrolledstud.syid',$syid);
                                $join->where('sh_enrolledstud.semid',$semid);
                            })
                            ->join('sh_strand',function($join){
                                $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                $join->where('sh_strand.deleted',0);
                            })
                            ->where('student_specsubj.status','ADDITIONAL')
                            ->where('student_specsubj.syid',$syid)
                            ->where('student_specsubj.semid',$semid)
                            ->where('student_specsubj.sectionid',$sectionid)
                            ->where('student_specsubj.subjid',$subjectid)
                            ->where('student_specsubj.deleted',0)
                            ->select(
                                'sh_enrolledstud.studid',
                                'firstname',
                                'lastname',
                                'middlename',
                                'suffix',
                                'mol',
                                'gender',
                                'strandcode',
                                'sh_enrolledstud.studstatus',
                                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                            )
                            ->get();

                    foreach($enrolledstud as $item){
                        array_push($temp_students,$item);
                    }

                    $enrolledstud = collect($temp_students)->sortBy(function($item) {
                        $gsort = $item->gender == 'MALE' ? 0 : 1;
                        return $gsort.'-'.$item->studentname;
                    })->values();
            
        }
      
        if( count($enrolledstud) == 0){
            return "NSE";
        }


        $student_quarter = DB::table('student_quarter')
							->where('syid',$syid)
                            ->whereIn('studid',collect($enrolledstud)->whereNotIn('studstatus',[1,2,4])->pluck('studid'))
                            ->get();


        $not_included = array();
        foreach($student_quarter as $item){
            $quarter_text  = 'quarter'.$quarter;
            if($item->$quarter_text == 0){
                array_push($not_included,$item->studid);
            }
        }

        $enrolledstud = collect($enrolledstud)->whereNotIn('studid',$not_included)->values();

 
        if(count($grade_header) == 0){

            $gradeId = DB::table('grades')->insertGetId([
                'syid'=>$syid,
                'levelid'=>$gradelevelid,
                'quarter'=>$quarter,
                'sectionid'=>$sectionid,
                'subjid'=>$subjectid,
                'deleted'=>0,
                'createdby'=>auth()->user()->id,
                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                'submitted'=>0,
                'status'=>0,
                'wwhr1'=>0,
                'wwhr2'=>0,
                'wwhr3'=>0,
                'wwhr4'=>0,
                'wwhr5'=>0,
                'wwhr6'=>0,
                'wwhr7'=>0,
                'wwhr8'=>0,
                'wwhr9'=>0,
                'wwhr0'=>0,
                'pthr1'=>0,
                'pthr2'=>0,
                'pthr3'=>0,
                'pthr4'=>0,
                'pthr5'=>0,
                'pthr6'=>0,
                'pthr7'=>0,
                'pthr8'=>0,
                'pthr9'=>0,
                'pthr0'=>0,
                'qahr1'=>0,
                'semid'=>$semid
            ]);

            $grade_header = DB::table('grades')
                                    ->where('id',$gradeId)
                                    ->where('deleted',0)
                                    ->get();

        }
    

        $hps = $grade_header;

        $is_modular = false;
        $mode_of_learning = '';
        $grades = [];
        $gradesdetail = DB::table('gradesdetail')
                        ->where('headerid',$hps[0]->id)
                        ->get();
        
        if(count($grade_header) > 0){

            foreach($enrolledstud as $item){

                $middlename = explode(" ",$item->middlename);
                $temp_middle = '';
                if($middlename != null){
                    foreach ($middlename as $middlename_item) {
                        if(strlen($middlename_item) > 0){
                            $temp_middle .= $middlename_item[0].'.';
                        } 
                    }
                }

                $item->student = $item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;

                $ifGradeExist = collect($gradesdetail)->where('studid',$item->studid)->first();
               
                if(!isset($ifGradeExist->id)){

                    $student_gradesdetail = DB::table('gradesdetail')
                                    ->insertGetID([
                                        'headerid'=>$hps[0]->id,
                                        'studid'=>$item->studid,
                                        'studname'=>$item->student,
                                        'wwws'=>0,
                                        'ptws'=>0,
                                        'qaws'=>0,
                                        'wwps'=>0,
                                        'ptps'=>0,
                                        'qaps'=>0,
                                        'wwtotal'=>0,
                                        'pttotal'=>0,
                                        'qatotal'=>0,
                                        'ig'=>0,
                                        'qg'=>0,
                                        'ww1'=>0,
                                        'ww2'=>0,
                                        'ww3'=>0,
                                        'ww4'=>0,
                                        'ww5'=>0,
                                        'ww6'=>0,
                                        'ww7'=>0,
                                        'ww8'=>0,
                                        'ww9'=>0,
                                        'ww0'=>0,
                                        'pt1'=>0,
                                        'pt2'=>0,
                                        'pt3'=>0,
                                        'pt4'=>0,
                                        'pt5'=>0,
                                        'pt6'=>0,
                                        'pt7'=>0,
                                        'pt8'=>0,
                                        'pt9'=>0,
                                        'pt0'=>0,
                                        'qa1'=>0,
                                        'remarks'=>0,
                                        'createdby'=>auth()->user()->id,
                                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                    $ifGradeExist = DB::table('gradesdetail')
                                                ->where('id',$student_gradesdetail)
                                                ->first();
                }

                $ifGradeExist->student = $item->student;
                $ifGradeExist->gender = $item->gender;

                if($levelInfo->acadprogid != 5){
                    $ifGradeExist->stradname = null;
                }else{
                    $ifGradeExist->stradname = $item->strandcode;
                }

                array_push($grades,$ifGradeExist);

            }

       }


        $submitted = $hps[0]->submitted == 0 ? false : true;
        $is_modular = false;

        $schoolinfo = DB::table('schoolinfo')->first();

        $transmutation = DB::table('gradetransmutation')->get();
        
        if(strtoupper($schoolinfo->abbreviation) == 'MCS'){
            if(isset($temp_sectioninfo->sectionname)){
                if (str_contains($temp_sectioninfo->sectionname, 'MODULAR')) {
                    $transmutation = DB::table('gradetransmutation_modular')->get();
                    $transmutation_array = array();
                    foreach($transmutation as $item){
                        $item->gfrom = number_format($item->gfrom,2);
                        $item->gto = number_format($item->gto,2);
                        if($item->gto == 90.00){
                            $item->gto = number_format(99.99,2);
                     
                        }
                         array_push($transmutation_array,$item);
                    }
                    
                    array_push($transmutation_array,(object)[
                            'id'=>32,
                            'gto'=>number_format(100,2),
                            'gfrom'=>number_format(100,2),
                            'gvalue'=>90
                        ]);
                        
                    $transmutation = $transmutation_array;
                }
            }
        }else if(strtoupper($schoolinfo->abbreviation) == 'HCHS' && $syid != 2){

            if($levelInfo->acadprogid != 5){
                $transmutation = DB::table('hchs_gradetrans_1_10')
                                    ->select(
                                        'gfrom',
                                        'gto',
                                        'transgrade as gvalue'
                                    )
                                    ->get();
            }else{
                $strand_detail = DB::table('sh_strand')
                                    ->where('id',$subject_strand[0]->strandid)
                                    ->first();

                if(isset($strand_detail->trackid)){
                    if($strand_detail->trackid == 1){
                        
                        $subj_info = DB::table('sh_subjects')
                                        ->where('id',$subjectid)
                                        ->where('deleted',0)
                                        ->first();

                        if($subj_info->type == 1){
                            $transmutation = DB::table('hchs_gradetrans_shs_coresubjects')
                                            ->select(
                                                'gfrom',
                                                'gto',
                                                'transgrade as gvalue'
                                            )
                                            ->get();
                        }else{
                            $transmutation = DB::table('hchs_gradetrans_shs_academictrack')
                                                ->select(
                                                    'gfrom',
                                                    'gto',
                                                    'transgrade as gvalue'
                                                )
                                                ->get();
                        }
                        
                    }else{
                        $subj_info = DB::table('sh_subjects')
                                        ->where('id',$subjectid)
                                        ->where('deleted',0)
                                        ->first();

                        if($subj_info->type != 1){
                            $transmutation = DB::table('hchs_gradetrans_1_10')
                                                ->select(
                                                    'gfrom',
                                                    'gto',
                                                    'transgrade as gvalue'
                                                )
                                                ->get();
                        }else{
                            $transmutation = DB::table('hchs_gradetrans_shs_coresubjects')
                                                ->select(
                                                    'gfrom',
                                                    'gto',
                                                    'transgrade as gvalue'
                                                )
                                                ->get();

                        }
                    }
                }
            }

        }

        // return $transmutation;

        $hps[0]->date_submitted = \Carbon\Carbon::create($hps[0]->date_submitted)->isoFormat('MMMM DD, YYYY hh:mm a');

        return view('teacher.grading.gradestable')
                            ->with('grades',$grades)
                            ->with('hps',$hps)
                            ->with('is_modular',$is_modular)
                            ->with('submitted',$submitted)
                            ->with('transmutation',$transmutation)
                            ->with('activeSem',$semid)
                            ->with('gradesetup',$gradesetup);
        
      

    }

    function udpate_grade_detail(Request $request){

        $data = $request->get('data');
        // $id  = $request->get('id');
        // $field  = $request->get('field');
        // $grade  = $request->get('grade');
        // $studid  = $request->get('studid');

     

        try{

            foreach($data as $item){
                $temp_data = collect($item);
                DB::table('gradesdetail')
                        ->where('id',$item['id'])
                        ->where('studid',$item['studid'])
                        ->take(1)
                        ->update([
                            $item['field'] => $item['grade'],
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            }
            return array((object)[
                    'status'=>1
            ]);

        }catch(\Exception $e){
            return array((object)[
                    'status'=>0
            ]);
        }

    }

    function udpate_grade_header(Request $request){

        $data = $request->get('data');

        try{

            foreach($data as $item){
                if(isset($item['field'])){
                    DB::table('grades')
                            ->where('id',$item['id'])
                            ->where('syid',$item['syid'])
                            ->where('sectionid',$item['sectionid'])
                            ->where('subjid',$item['subjid'])
                            ->update([
                                $item['field'] => $item['grade'],
                                'updatedby'=>auth()->user()->id,
                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                }
            }

            return array((object)[
                    'status'=>1
            ]);

        }catch(\Exception $e){
            
            // return $e;

            return array((object)[
                    'status'=>0
            ]);
        }

    }


    public function showGrades($id,$syid,$gradelevelid,$sectionid,$semid,Request $request){

        $quarter = null;

        if($request->has('quarter') && $request->get('quarter') != null){
            $quarter = $request->get('quarter');
        }

        $sy_id = $syid; 
        $grade_level_id = $gradelevelid;
        $section_id = $sectionid;
      
        $activeSem = DB::table('semester')->where('id',$semid)->first()->id;
      
        $acadProg = DB::table('gradelevel')
            ->select('academicprogram.progname','levelname')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id',$grade_level_id)
            ->get();
        
        $getsectionname = DB::table('sections') 
            ->select('sectionname')
            ->where('id',$section_id)
            ->where('deleted',0)
            ->get();

        if($acadProg[0]->progname == "SENIOR HIGH SCHOOL"){
            $getsubjectname = DB::table('sh_subjects') 
                ->select('subjtitle as subjdesc','subjcode')
                ->where('id',$id)
                ->where('deleted',0)
                ->distinct()
                ->get();
        }
        else{
            $getsubjectname = DB::table('subjects') 
                ->select('subjdesc','subjcode')
                ->where('id',$id)
                ->where('deleted',0)
                ->distinct()
                ->get();
        } 

        $gradessetup = DB::table('gradessetup')
                            ->where('gradessetup.syid',$syid)
                            ->where('gradessetup.levelid',$gradelevelid)
                            ->where('gradessetup.subjid',$id)
                            ->get();

        //11132021 - grades
        $moreinfo = array((object)[
            'levelname'=>$acadProg[0]->levelname,
            'sectionname'=>$getsectionname[0]->sectionname,
            'subjdesc'=>$getsubjectname[0]->subjdesc,
            'subjcode'=>$getsubjectname[0]->subjcode
        ]);

        $grade_status = array();

        for($x = 1; $x <= 4; $x ++){
            $grades = DB::table('grades')
                    ->where('syid',$syid)
                    ->where('levelid',$gradelevelid)
                    ->where('subjid',$id)
                    ->where('quarter',$x)
                    ->where('deleted',0)
                    ->where('sectionid',$section_id)
                    ->select(
                        'quarter',
                        'status',
                        'submitted'
                    )
                    ->get();

            if(count($grades)){
                $status = 'Not Submitted';

                if($grades[0]->status == 2){
                    $status = 'Approved';
                }else if($grades[0]->status == 3){
                    $status = 'Pending';
                }else if($grades[0]->status == 4){
                    $status = 'Posted';
                }else if(($grades[0]->status == 0 || $grades[0]->status == 1) && $grades[0]->submitted == 1){
                    $status = 'Submitted';
                }

                array_push($grade_status,(object)[
                    'quarter'=>$x,
                    'status'=>$status
                ]);
            }else{
                array_push($grade_status,(object)[
                    'quarter'=>$x,
                    'status'=>'Not Submitted'
                ]);
            }
        }   
        //11132021 - grades

        $subject_strand = array();

        if($grade_level_id == 14 || $grade_level_id == 15){
            $strand_list = DB::table('sh_sectionblockassignment')
                                ->where('syid',$syid)
                                ->where('sectionid',$section_id)
                                ->where('sh_sectionblockassignment.deleted',0)
                                ->join('sh_block',function($join){
                                    $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                    $join->where('sh_block.deleted',0);
                                })
                                ->join('sh_strand',function($join){
                                    $join->on('sh_block.strandid','=','sh_strand.id');
                                    $join->where('sh_strand.deleted',0);
                                })
                                ->select(
                                    'strandid',
                                    'strandcode'
                                )
                                ->get();

            foreach($strand_list as $item){
                array_push($subject_strand,$item);
            }
        }

        return view('teacher.grading.teachergrading')
                        ->with('gradeLevelid',$grade_level_id)
                        ->with('schoolyearid',$sy_id)
                        ->with('sectionid',$section_id)
                        ->with('subjstrand',$subject_strand)
                        ->with('subjectid',$id)
                        ->with('grade_status',$grade_status) //11132021 - grades
                        ->with('gradessetup',$gradessetup)
                        ->with('activeSem',$activeSem)
                        ->with('sectionname',$getsectionname[0]->sectionname)
                        ->with('quarter',$quarter)
                        ->with('moreinfo',$moreinfo)
                        ->with('subjectname',$getsubjectname[0]->subjdesc);

    }

    public function getGradesV2(Request $request){

        $mutable = Carbon::now();
        $created_date_time = $mutable->toDateTimeString();

        $semid = DB::table('semester')->where('isactive','1')->first();

        $chekifGradesSetupExist = DB::table('gradessetup')
                                        ->where('levelid',$request->get('gradelevelid'))
                                        ->where('subjid',$request->get('subjectid'))
                                        ->where('syid',$request->get('syid'))
                                        ->count();

        $levelInfo = DB::table('gradelevel')->where('id',$request->get('gradelevelid'))->first();

        if($chekifGradesSetupExist == 0){

            return "NGS";

        }

        // $chekifGradesPosted = 1;

        // if($request->get('quarter') > 1 ){

        //     if($levelInfo->acadprogid != 5){

        //         $chekifGradesPosted = DB::table('grades')
        //                         ->where('sectionid',$request->get('sectionid'))
        //                         ->where('levelid',$request->get('gradelevelid'))
        //                         ->where('quarter',$request->get('quarter') - 1)
        //                         ->where('syid',$request->get('syid'))
        //                         ->where('subjid',$request->get('subjectid'))
        //                         ->whereIn('status',['2','4'])
        //                         ->count();

        //     }else{

        //         $chekifGradesPosted = DB::table('grades')
        //                                 ->where('sectionid',$request->get('sectionid'))
        //                                 ->where('levelid',$request->get('gradelevelid'))
        //                                 ->where('quarter',$request->get('quarter') - 1)
        //                                 ->where('syid',$request->get('syid'))
        //                                 ->where('semid',$semid->id)
        //                                 ->where('subjid',$request->get('subjectid'))
        //                                 ->whereIn('status',['2','4'])
        //                                 ->count();


        //     }

        //     if($chekifGradesPosted == 0){

        //         return "NYP";
                
        //     }

          
        // }

        

        

        if($levelInfo->acadprogid != 5){

            $chekifGradesExist = DB::table('grades')
                                ->where('sectionid',$request->get('sectionid'))
                                ->where('levelid',$request->get('gradelevelid'))
                                ->where('quarter',$request->get('quarter'))
                                ->where('syid',$request->get('syid'))
                                ->where('subjid',$request->get('subjectid'))
                                ->count();

        }else{

            $chekifGradesExist = DB::table('grades')
                ->where('sectionid',$request->get('sectionid'))
                ->where('levelid',$request->get('gradelevelid'))
                ->where('quarter',$request->get('quarter'))
                ->where('syid',$request->get('syid'))
                ->where('semid',$semid->id)
                ->where('subjid',$request->get('subjectid'))
                ->count();

        }

       

        $enrolledstud = SPP_EnrolledStudent::getStudent(null,null,null,null,$levelInfo->acadprogid,$request->get('sectionid'),null,null,null,null,null,'namId');

        //if( $enrolledstud == 0){
            
            //return "NSE";

        //}
 
        if($chekifGradesExist == 0){

            if($levelInfo->acadprogid != 5){

               

                $gradeId = DB::table('grades')->insertGetId([
                    'syid'=>$request->get('syid'),
                    'levelid'=>$request->get('gradelevelid'),
                    'quarter'=>$request->get('quarter'),
                    'sectionid'=>$request->get('sectionid'),
                    'subjid'=>$request->get('subjectid'),
                    'deleted'=>0,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>$created_date_time,
                    'submitted'=>0,
                    'status'=>0,
                    'wwhr1'=>0,
                    'wwhr2'=>0,
                    'wwhr3'=>0,
                    'wwhr4'=>0,
                    'wwhr5'=>0,
                    'wwhr6'=>0,
                    'wwhr7'=>0,
                    'wwhr8'=>0,
                    'wwhr9'=>0,
                    'wwhr0'=>0,
                    'pthr1'=>0,
                    'pthr2'=>0,
                    'pthr3'=>0,
                    'pthr4'=>0,
                    'pthr5'=>0,
                    'pthr6'=>0,
                    'pthr7'=>0,
                    'pthr8'=>0,
                    'pthr9'=>0,
                    'pthr0'=>0,
                    'qahr1'=>0,
                    'semid'=>'1'
                ]);

                if(config('app.type') == 'Offline'){

                    DB::table('grades')->where('id',$gradeId)
                                        ->update([
                                            'grefid'=>'OF'.$gradeId
                                        ]);

                }
                elseif(config('app.type') == 'Online'){

                    DB::table('grades')->where('id',$gradeId)
                                        ->update([
                                            'grefid'=>'ON'.$gradeId
                                        ]);

                }

                

            }
            else{

                $gradeId = DB::table('grades')->insertGetId([
                    'syid'=>$request->get('syid'),
                    'levelid'=>$request->get('gradelevelid'),
                    'quarter'=>$request->get('quarter'),
                    'sectionid'=>$request->get('sectionid'),
                    'subjid'=>$request->get('subjectid'),
                    'deleted'=>0,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>$created_date_time,
                    'submitted'=>0,
                    'status'=>0,
                    'wwhr1'=>0,
                    'wwhr2'=>0,
                    'wwhr3'=>0,
                    'wwhr4'=>0,
                    'wwhr5'=>0,
                    'wwhr6'=>0,
                    'wwhr7'=>0,
                    'wwhr8'=>0,
                    'wwhr9'=>0,
                    'wwhr0'=>0,
                    'pthr1'=>0,
                    'pthr2'=>0,
                    'pthr3'=>0,
                    'pthr4'=>0,
                    'pthr5'=>0,
                    'pthr6'=>0,
                    'pthr7'=>0,
                    'pthr8'=>0,
                    'pthr9'=>0,
                    'pthr0'=>0,
                    'qahr1'=>0,
                    'semid'=>$semid->id

                ]);

                if(config('app.type') == 'Offline'){

                    DB::table('grades')->where('id',$gradeId)
                                        ->update([
                                            'grefid'=>'OF'.$gradeId
                                        ]);

                }
                elseif(config('app.type') == 'Online'){

                    DB::table('grades')->where('id',$gradeId)
                                        ->update([
                                            'grefid'=>'ON'.$gradeId
                                        ]);

                }

                
            }

        }
    

        if($levelInfo->acadprogid != 5){

            $hps = DB::table('grades')
                        ->join('sy',function($join){
                            $join->on('grades.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->where('sectionid',$request->get('sectionid'))
                        ->where('levelid',$request->get('gradelevelid'))
                        ->where('quarter',$request->get('quarter'))
                        ->where('syid',$request->get('syid'))
                        ->where('subjid',$request->get('subjectid'))
                        ->select('grades.*')
                        ->get();

        }else{

            $hps = DB::table('grades')
                        ->join('sy',function($join){
                            $join->on('grades.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->where('sectionid',$request->get('sectionid'))
                        ->where('levelid',$request->get('gradelevelid'))
                        ->where('quarter',$request->get('quarter'))
                        ->where('syid',$request->get('syid'))
                        ->where('subjid',$request->get('subjectid'))
                        ->where('semid',$semid->id)
                        ->select('grades.*')
                        ->get();

            
        }


        if(count($hps) > 0){

            foreach($enrolledstud[0]->data as $item){

                $ifGradeExist = DB::table('gradesdetail')
                                    ->where('headerid',$hps[0]->id)
                                    ->where('studid',$item->id)
                                    ->count();

                if(config('app.type') == 'Offline'){

                    $headerid = 'OF'.$hps[0]->id;

                }
                elseif(config('app.type') == 'Online'){

                    $headerid = 'ON'.$hps[0]->id;

                }

                if($ifGradeExist == 0){

                    $gradedetailId = DB::table('gradesdetail')->insertGetId([
                            'headerid'=>$hps[0]->id,
                            'studid'=>$item->id,
                            'studname'=>$item->lastname.', '.$item->firstname,
                            'wwws'=>0,
                            'ptws'=>0,
                            'qaws'=>0,
                            'wwps'=>0,
                            'ptps'=>0,
                            'qaps'=>0,
                            'wwtotal'=>0,
                            'pttotal'=>0,
                            'qatotal'=>0,
                            'ig'=>0,
                            'qg'=>0,
                            'ww1'=>0,
                            'ww2'=>0,
                            'ww3'=>0,
                            'ww4'=>0,
                            'ww5'=>0,
                            'ww6'=>0,
                            'ww7'=>0,
                            'ww8'=>0,
                            'ww9'=>0,
                            'ww0'=>0,
                            'pt1'=>0,
                            'pt2'=>0,
                            'pt3'=>0,
                            'pt4'=>0,
                            'pt5'=>0,
                            'pt6'=>0,
                            'pt7'=>0,
                            'pt8'=>0,
                            'pt9'=>0,
                            'pt0'=>0,
                            'qa1'=>0,
                            'remarks'=>0,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>Carbon::now('Asia/Manila'),
                            'gheaderid'=>$headerid
                        ]);

                    if(config('app.type') == 'Offline'){

                        DB::table('gradesdetail')->where('id',$gradedetailId)
                                            ->update([
                                                'grefid'=>'OF'.$gradedetailId
                                            ]);
    
                    }
                    elseif(config('app.type') == 'Online'){
    
                        DB::table('gradesdetail')->where('id',$gradedetailId)
                                            ->update([
                                                'grefid'=>'ON'.$gradedetailId
                                            ]);
    
                    }
                }

            }

       }

       if($levelInfo->acadprogid != 5){

            $grades = DB::table('grades')
                        ->join('gradesdetail',function($join){
                            $join->on('grades.id','=','gradesdetail.headerid');
                        })
                        ->join('sy',function($join){
                            $join->on('grades.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->where('sectionid',$request->get('sectionid'))
                        ->where('levelid',$request->get('gradelevelid'))
                        ->where('quarter',$request->get('quarter'))
                        ->where('subjid',$request->get('subjectid'))
                        ->select('gradesdetail.*')
                        ->where('grades.deleted','0')
                        ->get();

        }
        else{
            
            $grades = DB::table('grades')
                        ->join('gradesdetail',function($join){
                            $join->on('grades.id','=','gradesdetail.headerid');
                        })
                        ->join('sy',function($join){
                            $join->on('grades.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->where('semid',$semid->id)
                        ->where('sectionid',$request->get('sectionid'))
                        ->where('levelid',$request->get('gradelevelid'))
                        ->where('quarter',$request->get('quarter'))
                        ->where('subjid',$request->get('subjectid'))
                        ->select('gradesdetail.*')
                        ->where('grades.deleted','0')
                        ->get();
        }
                                
        $gradesetup = DB::table('gradessetup')
                        ->join('sy',function($join){
                            $join->on('gradessetup.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->where('gradessetup.levelid',$levelInfo->id)
                        ->where('gradessetup.subjid',$request->get('subjectid'))
                        ->first();


        if($hps[0]->submitted == 0){

            $submitted = false;

        }else{
        
            $submitted = true;

        }
        
        $transmutation = DB::table('gradetransmutation')->get();

        return view('teacher.grading.gradestable')
                            ->with('grades',$grades)
                            ->with('hps',$hps)
                            ->with('activeSem',$semid->id)
                            ->with('submitted',$submitted)
                            ->with('transmutation',$transmutation)
                            ->with('gradesetup',$gradesetup);
        
      

    }


    public function getGradesData(Request $request){

        $grades = DB::table('grades')
                                ->where('sectionid',$request->get('sectionid'))
                                ->where('levelid',$request->get('gradelevelid'))
                                ->where('quarter',$request->get('quarter'))
                                ->where('syid',$request->get('syid'))
                                ->where('subjid',$request->get('subjectid'))
                                ->first();

        $syncsetup = DB::table('syncsetup')->first();

        $client = new \GuzzleHttp\Client();

        try{

            $response = $client->request('GET', $syncsetup->url.'/uploadgradestocloud?data='.json_encode($grades,true));

        }catch (\Exception $e) {

            return $e;

        }

        $grefid = $response->getBody()->getContents(); 

        if( $grefid != 'submitted'){

            $gradedetails = DB::table('gradesdetail')
                                ->where('headerid',$grades->id)
                                ->get();
        
            foreach($gradedetails as $items){

                $client->request('GET', $syncsetup->url.'/uploadgradedetailstocloud?data='.json_encode($items,true).'&headerid='.$grefid);

            }

        }else if( $grefid == 'submitted'){

            return "submitted";

        }


    }

    public function uploadgradestocloud(Request $request){

        $gradeData = json_decode($request->get('data'));

        $chekifGradesPosted = DB::table('grades')
                            ->where('sectionid',$gradeData->sectionid)
                            ->where('levelid',$gradeData->levelid)
                            ->where('quarter',$gradeData->quarter)
                            ->where('syid',$gradeData->syid)
                            ->where('subjid',$gradeData->subjid)
                            ->first();
     
        if(isset($chekifGradesPosted->id)){
     
            unset($gradeData->id);
            unset($gradeData->grefid);

            if( $chekifGradesPosted->submitted == 0){

                DB::table('grades')
                        ->where('id',$chekifGradesPosted->id)
                        ->update(
                            collect($gradeData)->toArray()
                        );

                if($gradeData->submitted == 1){

                    DB::table('gradelogs')
                        ->insert([
                            'action'=>1,
                            'user_id'=>$gradeData->createdby,
                            'gradeid'=>$chekifGradesPosted->id,
                            'createdby'=>$gradeData->createdby,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                }

                return   $chekifGradesPosted->id;

            }
            else if( $chekifGradesPosted->submitted == 1){  

                return "submitted";

            }

        }
        else{

            unset($gradeData->id);
            unset($gradeData->grefid);

            $gradeId = DB::table('grades')
                    ->insertGetId(
                        collect($gradeData)->toArray()
                    );

            DB::table('grades')
                    ->where('id',$gradeId)
                    ->update([
                        'grefid'=>'OL'. $gradeId
                    ]);

            if($gradeData->submitted == 1){

                DB::table('gradelogs')
                    ->insert([
                        'action'=>1,
                        'user_id'=>$gradeData->createdby,
                        'gradeid'=>$gradeId,
                        'createdby'=>$gradeData->createdby,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            }

            return $gradeId;
                    
        }


    }

    public function uploadgradedetailstocloud(Request $request){

        $gradeData = json_decode($request->get('data'));

        $chekifGradedetailExist = DB::table('gradesdetail')
                            ->where('studid',$gradeData->studid)
                            ->where('headerid',$request->get('headerid'))
                            ->first();
     
        if(isset($chekifGradedetailExist->id)){
     
            unset($gradeData->id);
            unset($gradeData->grefid);

            $gradeData->headerid = $request->get('headerid');

            DB::table('gradesdetail')
                    ->where('id',$chekifGradedetailExist->id)
                    ->update(
                        collect($gradeData)->toArray()
                    );

        }
        else{

            unset($gradeData->id);
            unset($gradeData->grefid);

            $gradedetailId = DB::table('gradesdetail')
                    ->insertGetId(
                        collect($gradeData)->toArray()
                    );

            DB::table('gradesdetail')
                    ->where('id',$gradedetailId)
                    ->update([
                        'headerid'=>$request->get('headerid')
                    ]);

           
        }

    }


    public function getgradesdatafromcloud(Request $request){

        $grades = DB::table('grades')
                                ->where('sectionid',$request->get('sectionid'))
                                ->where('levelid',$request->get('gradelevelid'))
                                ->where('quarter',$request->get('quarter'))
                                ->where('syid',$request->get('syid'))
                                ->where('subjid',$request->get('subjectid'))
                                ->first();

        $syncsetup = DB::table('syncsetup')->first();

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $syncsetup->url. '/uploadgradestocloud?data='.json_encode($grades,true));
        $grefid = json_decode($response->getBody()->getContents()); 

        $gradedetails = DB::table('gradesdetail')
                            ->where('headerid',$grades->id)
                            ->get();
      
        foreach($gradedetails as $items){

            $client->request('GET', $syncsetup->url. '/uploadgradedetailstocloud?data='.json_encode($items,true).'&headerid='.$grefid);

        }


    }


    public function getgradesdetailfromcloud(Request $request){

        $grades = DB::table('grades')
                            ->where('sectionid',$request->get('sectionid'))
                            ->where('levelid',$request->get('gradelevelid'))
                            ->where('quarter',$request->get('quarter'))
                            ->where('syid',$request->get('syid'))
                            ->where('subjid',$request->get('subjectid'))
                            ->first();


        if(isset($grades->id)){

            $client = new \GuzzleHttp\Client();
            $syncsetup = DB::table('syncsetup')->first();
            $response = $client->request('GET', $syncsetup->url. '/returngradesdetailtolocal?sectionid='.$request->get('sectionid').'&gradelevelid='.$request->get('gradelevelid').'&quarter='.$request->get('quarter').'&subjectid='.$request->get('subjectid').'&syid='.$request->get('syid'));
            $gradesdetail = json_decode($response->getBody()->getContents()); 

            if(count($gradesdetail) > 0){

                if(isset( $gradesdetail[0]->grades->id)){

                    $gradesheader = $gradesdetail[0]->grades;

                    unset($gradesheader->id);

                    DB::table('grades')
                        ->where('id',$grades->id)
                        ->update(
                            collect($gradesheader)->toArray()
                        );

                    foreach($gradesdetail[0]->gradesdetail as $item){

                        unset($item->id);
                        unset($item->headerid);

                        DB::table('gradesdetail')
                            ->where('headerid', $grades->id)
                            ->where('studid', $item->studid)
                            ->take(1)
                            ->update(
                                collect($item)->toArray()
                            );

                    }
                    
                }

            }

        }

        return "done";

    }


    // public function returnstatusfromcloud(Request $request){

    //     $grades = DB::table('grades')
    //                 ->where('sectionid',$request->get('sectionid'))
    //                 ->where('levelid',$request->get('gradelevelid'))
    //                 ->where('quarter',$request->get('quarter'))
    //                 ->where('syid',$request->get('syid'))
    //                 ->where('subjid',$request->get('subjectid'))
    //                 ->first();
  
    //     return json_encode( collect($grades)->toArray());

    // }

    // public function checkgradestatusfromcloud(Request $request){


     

    //     $client = new \GuzzleHttp\Client();
    //     $syncsetup = DB::table('syncsetup')->first();

    //     $response = $client->request('GET', $syncsetup->url. '/returnstatusfromcloud?sectionid='.$request->get('sectionid').'&gradelevelid='.$request->get('gradelevelid').'&quarter='.$request->get('quarter').'&subjectid='.$request->get('subjectid').'&syid='.$request->get('syid'));

    //     $gradesdetail = json_decode($response->getBody()->getContents()); 

    //     return  $gradesdetail;


    // }


    public function returngradesdetailtolocal(Request $request){
        
        $grades = DB::table('grades')
                            ->where('sectionid',$request->get('sectionid'))
                            ->where('levelid',$request->get('gradelevelid'))
                            ->where('quarter',$request->get('quarter'))
                            ->where('syid',$request->get('syid'))
                            ->where('subjid',$request->get('subjectid'))
                            ->first();

        $gradedetails = [];

        if(isset($grades->id)){

            $gradedetails = DB::table('gradesdetail')
                        ->where('headerid',$grades->id)
                        ->get();

            $data = array((object)[
                'grades'=>$grades,
                'gradesdetail'=>$gradedetails
            ]);

        }
        else{

            $data = array((object)[
                'grades'=>(object)[],
                'gradesdetail'=>array()
            ]);

        }

        return  $data;

    }

    public function getCloudGradeStatus(Request $request){


        $gradeData = json_decode($request->get('data'));

        $grades = DB::table('grades')
                                    ->where('sectionid',$gradeData->sectionid)
                                    ->where('levelid',$gradeData->levelid)
                                    ->where('quarter',$gradeData->quarter)
                                    ->where('syid',$gradeData->syid)
                                    ->where('subjid',$gradeData->subjid)
                                    ->first();

        return  collect($grades)->toArray();

    }


    public function checkCloudGradeStatus(Request $request){

        $grades = DB::table('grades')
                        ->where('sectionid',$request->get('sectionid'))
                        ->where('levelid',$request->get('gradelevelid'))
                        ->where('quarter',$request->get('quarter'))
                        ->where('syid',$request->get('syid'))
                        ->where('subjid',$request->get('subjectid'))
                        ->first();

        $syncsetup = DB::table('syncsetup')->first();

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $syncsetup->url. '/getCloudGradeStatus?data='.json_encode($grades,true));
        $gradesfromcloud = json_decode($response->getBody()->getContents()); 

        return  collect($gradesfromcloud)->toArray();

    }

    public function pendinggrades(Request $request){


        if($request->get('list') == 'list' && $request->has('list')){

            $pending_grades = PendingGrades::get_teacher_grade();

            return view('teacher.pendinggrades.pendinggrades')
                    ->with('pending_grades',$pending_grades);


        }
        else if($request->get('pending') == 'pending' && $request->has('pending')){

            $pending_grades = PendingGrades::get_teacher_grade();

            return $pending_grades;


        }
        elseif($request->get('grade_table') == 'grade_table' && $request->has('grade_table')){


            $version_check = GradingSystem::checkVersion();

            $sectionid = $request->get('sectionid');
            $levelid = $request->get('levelid');
            $subjid = $request->get('subjid');
            $studid = $request->get('studid');
            $quarter  = $request->get('quarter');

            if($version_check->version == 'v2'){

                $acadprgid = DB::table('gradelevel')->where('id',$levelid)->select('acadprogid')->first()->acadprogid;

                if($acadprgid == 2){

                }elseif($acadprgid == 3){
                    return GradeSchool::evaluate_student_grade_gradeschool_pending(null,$studid,$sectionid,$subjid,$quarter);
                }elseif($acadprgid == 4){
                    return HighSchool::evaluate_student_grade_highschool_pending(null,$studid,$sectionid,$subjid,$quarter);
                }
                elseif($acadprgid == 5){
                    return SeniorHigh::evaluate_student_grade_seniorhigh_pending(null,$studid,$sectionid,$subjid,$quarter);
                }
                
               
    
            }else if($version_check->version == 'v1'){
    
                $mutable = Carbon::now();
                $created_date_time = $mutable->toDateTimeString();
        
                $semid = DB::table('semester')->where('isactive','1')->first();
                $activeSy = DB::table('sy')->where('isactive','1')->first();

                $chekifGradesSetupExist = DB::table('gradessetup')
                                                ->where('levelid',$request->get('levelid'))
                                                ->where('subjid',$request->get('subjid'))
                                                ->where('syid',$activeSy->id)
                                                ->count();

                $levelInfo = DB::table('gradelevel')->where('id',$request->get('levelid'))->first();
        
                if($chekifGradesSetupExist == 0){
        
                    return "NGS";
        
                }

                if($levelInfo->acadprogid != 5){

                    $hps = DB::table('grades')
                                ->join('sy',function($join){
                                    $join->on('grades.syid','=','sy.id');
                                    $join->where('sy.isactive','1');
                                })
                                ->where('sectionid',$request->get('sectionid'))
                                ->where('levelid',$request->get('levelid'))
                                ->where('quarter',$request->get('quarter'))
                                ->where('syid',$activeSy->id)
                                ->where('subjid',$request->get('subjid'))
                                ->select('grades.*')
                                ->get();
        
                }else{
        
                    $hps = DB::table('grades')
                                ->join('sy',function($join){
                                    $join->on('grades.syid','=','sy.id');
                                    $join->where('sy.isactive','1');
                                })
                                ->where('sectionid',$request->get('sectionid'))
                                ->where('levelid',$request->get('levelid'))
                                ->where('quarter',$request->get('quarter'))
                                ->where('syid',$activeSy->id)
                                ->where('subjid',$request->get('subjid'))
                                ->where('semid',$semid->id)
                                ->select('grades.*')
                                ->get();
        
                }

                if($levelInfo->acadprogid != 5){

                    $grades = DB::table('grades')
                                ->join('gradesdetail',function($join){
                                    $join->on('grades.id','=','gradesdetail.headerid');
                                })
                                ->join('sy',function($join){
                                    $join->on('grades.syid','=','sy.id');
                                    $join->where('sy.isactive','1');
                                })
                                ->join('studinfo',function($join){
                                    $join->on('gradesdetail.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                                })
                                ->where('studid',$request->get('studid'))
                                ->where('grades.sectionid',$request->get('sectionid'))
                                ->where('grades.levelid',$request->get('levelid'))
                                ->where('quarter',$request->get('quarter'))
                                ->where('subjid',$request->get('subjid'))
                                ->select('gradesdetail.*')
                                ->where('grades.deleted','0')
                                ->select('gradesdetail.*','studinfo.gender')
                                ->orderby('gender','desc')
                                ->orderby('lastname')  
                                ->get();
                
                }
                else{
                    
                    $grades = DB::table('grades')
                                ->join('gradesdetail',function($join){
                                    $join->on('grades.id','=','gradesdetail.headerid');
                                })
                                ->join('sy',function($join){
                                    $join->on('grades.syid','=','sy.id');
                                    $join->where('sy.isactive','1');
                                })
                                ->join('studinfo',function($join){
                                    $join->on('gradesdetail.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                                })
                                ->where('studid',$request->get('studid'))
                                ->where('grades.semid',$semid->id)
                                ->where('grades.sectionid',$request->get('sectionid'))
                                ->where('grades.levelid',$request->get('levelid'))
                                ->where('quarter',$request->get('quarter'))
                                ->where('subjid',$request->get('subjid'))
                                ->select('gradesdetail.*','studinfo.gender')
                                ->where('grades.deleted','0')
                                ->orderby('gender','desc')
                                ->orderby('lastname')
                                ->get();
                            
                }

                $transmutation = DB::table('gradetransmutation')->get();
                
                $submitted = false;

                $hps[0]->submitted = 0;
                $hps[0]->status = 0;

                $gradesetup = DB::table('gradessetup')
                                ->where('syid',$activeSy->id)
                                ->where('gradessetup.levelid',$levelInfo->id)
                                ->where('gradessetup.subjid',$request->get('subjid'))
                                ->first();

                return view('teacher.pendinggrades.student_grades_table')
                            ->with('grades',$grades)
                            ->with('hps',$hps)
                            ->with('submitted',$submitted)
                            ->with('transmutation',$transmutation)
                            ->with('gradesetup',$gradesetup);

            }


        }

        elseif($request->get('updategrade') == 'updategrade' && $request->has('updategrade')){


            foreach($request->get('inputedData') as $item){
            
                DB::table('gradesdetail')
                            ->where('id', $item[0])
                            ->update([
                                    'ww0'=>$item[1],
                                    'ww1'=>$item[2],
                                    'ww2'=>$item[3],
                                    'ww3'=>$item[4],
                                    'ww4'=>$item[5],
                                    'ww5'=>$item[6],
                                    'ww6'=>$item[7],
                                    'ww7'=>$item[8],
                                    'ww8'=>$item[9],
                                    'ww9'=>$item[10],
                                    'wwtotal'=>$item[11],
                                    'pt0'=>$item[12],
                                    'pt1'=>$item[13],
                                    'pt2'=>$item[14],
                                    'pt3'=>$item[15],
                                    'pt4'=>$item[16],
                                    'pt5'=>$item[17],
                                    'pt6'=>$item[18],
                                    'pt7'=>$item[19],
                                    'pt8'=>$item[20],
                                    'pt9'=>$item[21],
                                    'pttotal'=>$item[22],
                                    'qa1'=>$item[23],
                                    'ig'=>$item[25],
                                    'qg'=>$item[26],
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>Carbon::now('Asia/Manila'),
                                    'gdstatus'=>1,
                                    'statusdatetime'=>Carbon::now('Asia/Manila')
                            ]);


                        $studid = DB::table('gradesdetail')
                                            ->where('id',$item[0])
                                            ->select('studid')
                                            ->first();



            }


            foreach($request->get('inputedDataHPS') as $item){

                $grade_information = DB::table('grades')
                                            ->where('id',$item[0])
                                            ->first();

            }

            if($grade_information->levelid == 14 && $grade_information->levelid == 15){

                DB::table('grading_system_pending_grade')
                                ->where('studid',$studid->studid)
                                ->where('grading_system_pending_grade.syid',$grade_information->syid)
                                ->where('grading_system_pending_grade.semid',$grade_information->semid)
                                ->where('grading_system_pending_grade.sectionid',$grade_information->sectionid)
                                ->where('grades.levelid',$grade_information->levelid)
                                ->where('quarter',$grade_information->quarter)
                                ->where('subjid',$grade_information->subjid)
                                ->update(['isactive'=>0]);
                              

            }else{

                DB::table('grading_system_pending_grade')
                            ->where('studid',$studid->studid)
                            ->where('grading_system_pending_grade.syid',$grade_information->syid)
                            ->where('grading_system_pending_grade.sectionid',$grade_information->sectionid)
                            ->where('grading_system_pending_grade.levelid',$grade_information->levelid)
                            ->where('quarter',$grade_information->quarter)
                            ->where('subjid',$grade_information->subjid)
                            ->update(['isactive'=>0]);
                       
                            

            }


            return array((object)[
                'status'=>1,
                'data'=>'Updated Successfully'
            ]);


        }



    }

    //final grade input
    public function for_final_grading(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $levelid = $request->get('gradelevelid');
        $sectionid = $request->get('sectionid');
        $subjid = $request->get('subjectid');
        $quarter = $request->get('quarter');


        $status = \App\Models\Grading\FinalGrading::check_grade_status($syid,$semid,$levelid,$sectionid,$subjid,$quarter);
        $grading = \App\Models\Grading\FinalGrading::get_finalGrade($syid,$semid,$levelid,$sectionid,$subjid,$quarter);

        if($grading == 'NSE'){
            return $grading;
        }

        return view('teacher.grading.final_grade')
                    ->with('final',$grading)
                    ->with('status',$status);

    }

    public function save_final_grade(Request $request){

        $gdid = $request->get('gdid');
        $studid = $request->get('studid');
        $fg = $request->get('fg');

       return  \App\Models\Grading\FinalGrading::save_final_grade($gdid,$studid,$fg);
        
    }

    public function update_grade_type(Request $request){

        $gdid = $request->get('id');
        $type = $request->get('type');

        return  \App\Models\Grading\FinalGrading::update_grade_type($gdid, $type);
        
    }

    public function submit_final_grade(Request $request){

        $gdid = $request->get('id');
        $levelid = $request->get('gradelevelid');
        return  \App\Models\Grading\FinalGrading::submit_final_grade($gdid,$levelid);
        
    }

    public function check_grade_type(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $levelid = $request->get('gradelevelid');
        $sectionid = $request->get('section');
        $subjid = $request->get('subjectid');
        $quarter = $request->get('quarter');

        $status = \App\Models\Grading\FinalGrading::check_grade_status($syid,$semid,$levelid,$sectionid,$subjid,$quarter);

      
        return array($status);
    }

    public function gradesDetail(){

        
    }

    


}


     