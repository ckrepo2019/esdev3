<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use Session;

class CollegeTeacherGradeMonitoringController extends \App\Http\Controllers\Controller
{

      public static function subjects(
            $syid  = null,
            $semid  = null,
            $teacherid = null,
            $schedid = null
        ){
    
            $schoolinfo = DB::table('schoolinfo')->first();
    
            $subjects = DB::table('college_classsched')
                            ->join('college_prospectus',function($join){
                                $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                $join->where('college_prospectus.deleted',0);
                            })
                            ->join('college_sections',function($join){
                                $join->on('college_classsched.sectionid','=','college_sections.id');
                                $join->where('college_sections.deleted',0);
                            })
                            ->join('college_courses',function($join){
                                $join->on('college_sections.courseid','=','college_courses.id');
                                $join->where('college_sections.deleted',0);
                            })
                            ->leftJoin('gradelevel',function($join){
                                $join->on('college_sections.yearID','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                            });
    
            if($schedid != null){
                $subjects = $subjects->where('college_classsched.id',$schedid);
            }                       
    
            if($teacherid != null){
                $subjects = $subjects
                              ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                              ->where('college_instructor.teacherID',$teacherid);
            }
                    
    
            $subjects = $subjects
                            ->where('college_classsched.syID',$syid)
                            ->where('college_classsched.semesterID',$semid)
                            ->where('college_classsched.deleted',0)
                            ->distinct('college_prospectus.id')
                            ->select(
                                'college_prospectus.subjectID',
                                'levelname',
                                'courseDesc',
                                'courseabrv',
                                'subjDesc',
                                'subjCode',
                                'labunits',
                                'lecunits',
                                'sectionDesc',
                                'college_classsched.id as schedid',
                                'college_classsched.teacherID as teacherid',
                                'college_prospectus.id as pid',
                                'college_classsched.sectionID',
                                'college_classsched.syID as syid'
                            );
    
            if(strtoupper($schoolinfo->abbreviation) == 'SPCT' || strtoupper($schoolinfo->abbreviation) == 'GBBC' ){
                $subjects = $subjects->groupBy('subjectID');
            }
    
            $subjects = $subjects->get();

            foreach($subjects as $item){
                  $check_group = DB::table('college_schedgroup_detail')
                                    ->where('college_schedgroup_detail.deleted',0)
                                    ->join('college_schedgroup',function($join){
                                          $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                                          $join->where('college_schedgroup.deleted',0);
                                    })
                                    ->where('schedid',$item->schedid)
                                    ->select(
                                          'schedgroupdesc'
                                    )
                                    ->first();

                  if(isset($check_group)){
                        $item->sectionDesc = $check_group->schedgroupdesc;
                  }
            }
    
            return $subjects;
        }

        public static function enrolled_learners($syid = null, $semid = null, $schedid = null, $subjctid = null){

        }

      public static function grade_subjects_ajax(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $teacherid = $request->get('teacherid');

            $subjects = self::subjects($syid,$semid,$teacherid);

            foreach($subjects as $item){
                  
    
                $grade_status = self::check_grade_status($item->subjectID,$item->schedid,$syid,$semid);
                  
				  
				  //if($item->schedid == 145){
					  //return $grade_status;
				  //}

                  $item->uns = false;
                  $item->sub = false;
                  $item->app = false;
                  $item->pen = false;
                  $item->deanapp = false;
                  $item->posted = false;
				   $item->inc = false;

                  if(count($grade_status) > 0){
                        if( collect($grade_status)->whereNull('prelemstatus')->count() > 0 && collect($grade_status)->whereNull('midtermstatus')->count() > 0 && collect($grade_status)->whereNull('prefistatus')->count() > 0 && collect($grade_status)->whereNull('finalstatus')->count() > 0  ){
                              $item->uns = true;
                        }
                        if(collect($grade_status)->where('prelemstatus',1)->count() > 0 || collect($grade_status)->where('midtermstatus',1)->count() > 0 || collect($grade_status)->where('prefistatus',1)->count() > 0 || collect($grade_status)->where('finalstatus',1)->count() > 0){
                              $item->sub = true;
                        }
                        if(collect($grade_status)->where('prelemstatus',3)->count() > 0 || collect($grade_status)->where('midtermstatus',3)->count() > 0 || collect($grade_status)->where('prefistatus',3)->count() > 0 || collect($grade_status)->where('finalstatus',3)->count() > 0){
                              $item->pen = true;
                        }
                        if(collect($grade_status)->where('prelemstatus',7)->count() > 0 || collect($grade_status)->where('midtermstatus',7)->count() > 0 || collect($grade_status)->where('prefistatus',7)->count() > 0 || collect($grade_status)->where('finalstatus',7)->count() > 0  ){
                              $item->app = true;
                        }
                        if(collect($grade_status)->where('prelemstatus',2)->count() > 0 || collect($grade_status)->where('midtermstatus',2)->count() > 0 || collect($grade_status)->where('prefistatus',2)->count() > 0 || collect($grade_status)->where('finalstatus',2)->count() > 0  ){
                              $item->deanapp = true;
                        }
                        if(collect($grade_status)->where('prelemstatus',4)->count() > 0 || collect($grade_status)->where('midtermstatus',4)->count() > 0 || collect($grade_status)->where('prefistatus',4)->count() > 0 || collect($grade_status)->where('finalstatus',4)->count() > 0  ){
                              $item->posted = true;
                        }
                        if(collect($grade_status)->where('prelemstatus',8)->count() > 0 || collect($grade_status)->where('midtermstatus',8)->count() > 0 || collect($grade_status)->where('prefistatus',8)->count() > 0 || collect($grade_status)->where('finalstatus',8)->count() > 0  ){
                              $item->inc = true;
                        }
      
                        if(collect($grade_status)->where('prelemstatus',9)->count() > 0 || collect($grade_status)->where('midtermstatus',9)->count() > 0 || collect($grade_status)->where('prefistatus',9)->count() > 0 || collect($grade_status)->where('finalstatus',9)->count() > 0  ){
                              $item->drop = true;
                        }
                  }else{
                        $item->uns = true;
                  }
              
                 

              
            }
    
            return $subjects;
    
        }

        public static function check_grade_status($subjid = null , $schedid = null, $syid = null, $semid = null){

            $schoolinfo = DB::table('schoolinfo')->first();

            
            $prospectusid =  DB::table('college_prospectus')
                              ->where('college_prospectus.subjectID',$subjid)
                              ->where('college_prospectus.deleted',0)
                              ->select('id')
                              ->get();

            $p_array = array();

            foreach($prospectusid as $item){
                  array_push($p_array,$item->id);
            }
            
            $status_count = DB::table('college_prospectus')
                              ->join('college_classsched',function($join) use($syid,$semid,$schedid){
                                    $join->on('college_prospectus.id','=','college_classsched.subjectID');
                                    $join->where('college_classsched.deleted',0);
                                    $join->where('college_classsched.syid',$syid);
                                    $join->where('college_classsched.semesterID',$semid);
                                    $join->where('college_classsched.id',$schedid);
                              })
                              ->join('college_loadsubject', 'college_classsched.id', '=', 'college_loadsubject.schedid')
                              ->join('college_enrolledstud',function($join) use($syid,$semid){
                                    $join->on('college_loadsubject.studid','=','college_enrolledstud.studid');
                                    $join->where('college_enrolledstud.deleted',0);
                                    $join->whereIn('college_enrolledstud.studstatus',[1,2,4]);
                                    $join->where('college_enrolledstud.syid',$syid);
                                    $join->where('college_enrolledstud.semid',$semid);
                              })
                              ->join('studinfo',function($join){
                                    $join->on('college_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('college_studentprospectus',function($join) use($syid,$semid,$p_array){
                                    $join->on('studinfo.sid','=','college_studentprospectus.studid');
                                    $join->where('college_studentprospectus.deleted',0);
                                    $join->where('college_studentprospectus.syid',$syid);
                                    $join->whereIn('college_studentprospectus.prospectusID',$p_array);
                                    $join->where('college_studentprospectus.semid',$semid);
                              })
                              ->where('college_prospectus.subjectID',$subjid)
                              ->where('college_prospectus.deleted',0)
                              ->select(
                                    'college_studentprospectus.id',
                                    'prelemstatus',
                                    'midtermstatus',
                                    'prefistatus',
                                    'finalstatus'
                              )
                              ->get();

            return $status_count;


      }

      public static function view_grades(Request $request){

            $schedid = $request->get('schedid');
            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $prospectusid =  DB::table('college_prospectus')
                                    ->where('college_prospectus.subjectID',$subjid)
                                    ->where('college_prospectus.deleted',0)
                                    ->select('id')
                                    ->get();

                  $p_array = array();

                  foreach($prospectusid as $item){
                        array_push($p_array,$item->id);
                  }

            $students = DB::table('college_prospectus')
                              ->join('college_classsched',function($join) use($syid,$semid,$schedid){
                                    $join->on('college_prospectus.id','=','college_classsched.subjectID');
                                    $join->where('college_classsched.deleted',0);
                                    $join->where('college_classsched.syid',$syid);
                                    $join->where('college_classsched.id',$schedid);
                                    $join->where('college_classsched.semesterID',$semid);
                              })
                              ->join('college_loadsubject', 'college_classsched.id', '=', 'college_loadsubject.schedid')
                              ->join('college_enrolledstud',function($join) use($syid,$semid){
                                    $join->on('college_loadsubject.studid','=','college_enrolledstud.studid');
                                    $join->where('college_enrolledstud.deleted',0);
                                    $join->whereIn('college_enrolledstud.studstatus',[1,2,4]);
                                    $join->where('college_enrolledstud.syid',$syid);
                                    $join->where('college_enrolledstud.semid',$semid);
                              })
                              ->join('studinfo',function($join){
                                    $join->on('college_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('college_courses',function($join){
                                    $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('college_prospectus.subjectID',$subjid)
                              ->where('college_prospectus.deleted',0)
                              ->select(
                                    'college_classsched.subjectID as pid',
                                    'college_classsched.sectionID as sectionid',
                                    'college_enrolledstud.courseid',
                                    'levelname',
                                    'courseabrv',
                                    'gender',
                                    'firstname',
                                    'lastname',
                                    'middlename',
                                    'suffix',
                                    'sid',
                                    'college_enrolledstud.yearLevel as levelid',
                                    'college_enrolledstud.studid',
                                    DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                                )
                                ->distinct('studid')
                                ->orderBy('gender','desc')
                                ->orderBy('lastname')
                                ->get();

                  $status_count = DB::table('college_prospectus')
                                    ->join('college_classsched',function($join) use($syid,$semid){
                                          $join->on('college_prospectus.id','=','college_classsched.subjectID');
                                          $join->where('college_classsched.deleted',0);
                                          $join->where('college_classsched.syid',$syid);
                                          $join->where('college_classsched.semesterID',$semid);
                                    })
                                    ->join('college_loadsubject', function($join){
                                          $join->on('college_classsched.id', '=', 'college_loadsubject.schedid');
                                          $join->where('college_loadsubject.deleted',0);
                                    })
                                    ->join('college_enrolledstud',function($join) use($syid,$semid){
                                          $join->on('college_loadsubject.studid','=','college_enrolledstud.studid');
                                          $join->where('college_enrolledstud.deleted',0);
                                          $join->whereIn('college_enrolledstud.studstatus',[1,2,4]);
                                          $join->where('college_enrolledstud.syid',$syid);
                                          $join->where('college_enrolledstud.semid',$semid);
                                    })
                                    ->join('studinfo',function($join){
                                          $join->on('college_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('college_studentprospectus',function($join) use($syid,$semid,$p_array){
                                          $join->on('studinfo.sid','=','college_studentprospectus.studid');
                                          $join->where('college_studentprospectus.deleted',0);
                                          $join->where('college_studentprospectus.syid',$syid);
                                          $join->whereIn('college_studentprospectus.prospectusID',$p_array);
                                          $join->where('college_studentprospectus.semid',$semid);
                                    })
                                    ->where('college_prospectus.subjectID',$subjid)
                                    ->where('college_prospectus.deleted',0)
                                    ->select(
                                          'college_studentprospectus.*'
                                    )
                                    ->get();

                  return array((object)[
                        'students'=>$students,
                        'grades'=>$status_count
                  ]);
      }
      
      public static function teachers(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            
            if(Session::get('currentPortal') != 17){

                  $teacherid = DB::table('teacher')
                              ->where('tid',auth()->user()->email)
                              ->select('id')
                              ->first()
                              ->id;

            }

            if(Session::get('currentPortal') == 16){

                  $teacher = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->first();

                  $courseID = DB::table('teacherprogramhead')
                                    ->where('teacherprogramhead.deleted',0)
                                    ->where('teacherprogramhead.syid',$syid)
                                    //->where('teacherprogramhead.semid',$semid)
                                    ->where('teacherid',$teacher->id)
                                    ->join('college_courses',function($join){
                                          $join->on('teacherprogramhead.courseid','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv'
                                    )
                                    ->get();

            }else if(Session::get('currentPortal') == 14){

                  $teacher = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->first();

                  $courseID = DB::table('teacherdean')
                              ->where('teacherdean.deleted',0)
                              ->where('teacherdean.syid',$syid)
                              //->where('teacherdean.semid',$semid)
                              ->where('teacherid',$teacher->id)
                              ->leftjoin('college_colleges',function($join){
                                    $join->on('teacherdean.collegeid','=','college_colleges.id');
                                    $join->where('college_colleges.deleted',0);
                              })
                              ->leftjoin('college_courses',function($join){
                                    $join->on('college_colleges.id','=','college_courses.collegeid');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->select(
                                    'college_courses.id',
                                    'college_courses.courseDesc',
                                    'college_courses.courseabrv'
                              )
                              ->get();
            }else{
                  $courseID = DB::table('college_courses')
                                    ->where('deleted',0)
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv'
                                    )
                                    ->get();


            }

            $array_course = array();

            foreach($courseID as $item){
                  array_push($array_course,$item->id);
            }
            $teachers = DB::table('college_prospectus')
                              ->leftjoin('college_classsched',function($join) use($syid,$semid){
                                    $join->on('college_prospectus.id','=','college_classsched.subjectID');
                                    $join->where('college_classsched.syid',$syid);
                                    $join->where('college_classsched.semesterID',$semid);
                                    $join->where('college_classsched.deleted',0);
                              })
                              ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                              ->join('teacher',function($join) use($syid,$semid){
                                    $join->on('college_instructor.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                              })
                              ->select(
                                    'teacher.id',
                                    'teacher.tid',
                                    'teacher.lastname',
                                    'teacher.firstname',
                                    'teacher.middlename',
                                    DB::raw("CONCAT(teacher.tid,' - ',teacher.lastname,', ',teacher.firstname) as text")
                              )
                              ->distinct('tid')
                              ->whereIn('college_prospectus.courseID',$array_course)
                              ->get();

            return $teachers;

      }

}
