<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Hash;
use Session;
use File;
use Image;

class TeacherProfileController extends \App\Http\Controllers\Controller
{

      public static function getsubjects(Request $request){

            try{
            
                  $levelid = $request->get('levelid');
                  $sectionid = $request->get('sectionid');
                  $sections = $request->get('sections');
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
      
                  if($levelid == 14 || $levelid == 15){
      
                      $sectionblockass = DB::table('sh_sectionblockassignment')
                                              ->join('sh_block',function($join){
                                                  $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                                  $join->where('sh_block.deleted',0);
                                              })
                                              ->where('sh_sectionblockassignment.syid',$syid)
                                              ->where('sh_sectionblockassignment.deleted',0)
                                              ->where('sh_sectionblockassignment.sectionid',$sectionid)
                                              ->select('strandid')
                                              ->get();

      
                      $subjects = DB::table('subject_plot')
                                      ->where('subject_plot.deleted',0)
                                      ->where('subject_plot.syid',$syid)
                                      ->where('subject_plot.levelid',$levelid)
                                      ->whereIn('subject_plot.strandid',collect($sectionblockass)->pluck('strandid'))
                                      ->where('subject_plot.semid',$semid)
                                      ->join('sh_subjects',function($join){
                                          $join->on('subject_plot.subjid','=','sh_subjects.id');
                                          $join->where('sh_subjects.deleted',0);
                                      })
                                      ->select(
                                          'subject_plot.strandid',
                                          'subjtitle as text',
                                          'subjid as id',
                                          DB::raw("CONCAT(sh_subjects.subjCode,' - ',sh_subjects.subjTitle) as text")
                                      )
                                      ->get();
      
                      foreach($subjects as $item){
                          $item->subjCom = null;
                      }

                      $subjects = collect($subjects)->unique('id')->values();
      
                  }else{
      
                      $subjects = DB::table('subject_plot')
                                      ->where('subject_plot.deleted',0)
                                      ->where('subject_plot.syid',$syid)
                                      ->where('subject_plot.levelid',$levelid)
                                      ->join('subjects',function($join){
                                          $join->on('subject_plot.subjid','=','subjects.id');
                                          $join->where('subjects.deleted',0);
                                          $join->where('subjects.isCon',0);
                                      })
                                      ->select(
                                          'subjdesc as text',
                                          'subjid as id',
                                          'subjCom',
                                           DB::raw("CONCAT(subjects.subjCode,' - ',subjects.subjDesc) as text")
                                      )
                                      ->get();
                  }
      
                  return array((object)[
                      'status'=>1,
                      'data'=>$subjects
                  ]);
              
              }catch(\Exception $e){
                  return self::store_error($e);
              }
      
      }



      public static function getsections(Request $request){

            try{
                
                $sections = DB::table('sectiondetail')
                                ->where('sectiondetail.deleted',0)
                                ->where('syid',$request->get('syid'))
                                ->join('sections',function($join){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                                })
                                ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                })
                                ->select(
                                    'sectiondetail.sd_roomid as roomid',
                                    'sectiondetail.teacherid',
                                    'sectionname as text',
                                    'sectionid as id',
                                    'levelid',
                                    'acadprogid'
                                )
                                ->get();
    
                return array((object)[
                    'status'=>1,
                    'data'=>$sections
                ]);
            
            }catch(\Exception $e){
    
                return self::store_error($e);
    
    
            }
    
        }



      public static function teacher_list(Request $request){

            $syid = $request->get('syid');

            if(auth()->user()->type != 17 && auth()->user()->type != 12){

                  $temp_teacherid = DB::table('teacher')->where('tid',auth()->user()->email)->first();
      
                  if(isset($temp_teacherid)){
                        $temp_teacherid = $temp_teacherid->id;
                  }
      
                  if(Session::get('currentPortal') == 2 || Session::get('currentPortal') == 1){

                        // $temp_acad = DB::table('academicprogram')
                        //                   ->where('principalid',$temp_teacherid)
                        //                   ->select('id')
                        //                   ->get();

                        $temp_acad = DB::table('teacheracadprog')
                                          ->where('acadprogutype',Session::get('currentPortal'))
                                          ->where('syid',$syid)
                                          //->where('acadprogutype',2)
                                          ->where('acadprogid','!=',6)
                                          ->select('acadprogid as id')
                                          ->where('deleted',0)
                                          ->get();

                        $acad = array();
                        foreach($temp_acad as $item){
                              array_push($acad,$item->id);
                        }

                        $teachers = DB::table('teacheracadprog')
                                          ->join('teacher',function($join){
                                                $join->on('teacheracadprog.teacherid','=','teacher.id');
                                                $join->where('teacher.deleted',0);
                                                $join->where('teacher.isactive',1);
                                          })
                                          ->where('teacheracadprog.deleted',0)
                                          ->distinct('teacher.id')
                                          // ->where('syid',$syid)
                                          ->whereIn('acadprogid',$acad)
                                          ->groupBy('tid')
                                          ->select(
                                                'userid',
                                                'teacher.id',
                                                'firstname',
                                                'lastname',
                                                'middlename',
                                                'suffix',
                                                'tid',
                                                'title',
                                                'usertypeid',
                                                'picurl'
                                          )
                                          ->get();

                                         

                        $temp_array = array();

                        foreach($teachers as $item){
                              if($item->usertypeid == 1){
                                    array_push($temp_array,$item);
                              }else{
                                    $fas_priv = DB::table('faspriv')
                                                      ->where('userid',$item->userid)
                                                      ->where('deleted',0)
                                                      ->where('usertype',1)
                                                      ->count();
                                    if($fas_priv > 0){
                                          array_push($temp_array,$item);
                                    }
                              }
                        }

                        $temp_teacher = DB::table('teacher')
                                          ->where('tid',auth()->user()->email)
                                          ->select(
                                                'userid',
                                                'teacher.id',
                                                'firstname',
                                                'lastname',
                                                'middlename',
                                                'suffix',
                                                'tid',
                                                'title',
                                                'usertypeid',
                                                'picurl'
                                          )
                                          ->first();

                        $check = collect($temp_array)->where('id',$temp_teacher->id)->count();

                        if($check == 0){
                              array_push($temp_array,$temp_teacher);
                        }

                       
                        $teachers = $temp_array;

                  }else if(Session::get('currentPortal') == 16 || Session::get('currentPortal') == 14){


                        $teachers = DB::table('teacher')
                                          ->whereIn('usertypeid',[16,18,14])
                                          ->where('teacher.deleted',0)
                                          ->select(
                                                'userid',
                                                'teacher.id',
                                                'firstname',
                                                'lastname',
                                                'middlename',
                                                'suffix',
                                                'tid',
                                                'title',
                                                'usertypeid',
                                                'picurl'
                                          )->get();

                        $teachers_faspriv = DB::table('faspriv')
                                          ->where('faspriv.deleted',0)
                                          ->join('teacher',function($join){
                                                $join->on('faspriv.userid','=','teacher.userid');
                                                $join->where('teacher.deleted',0);
                                                $join->where('teacher.isactive',1);
                                          })
                                          ->whereIn('faspriv.usertype',[16,18,14])
                                          ->select(
                                                'teacher.userid',
                                                'teacher.id',
                                                'firstname',
                                                'lastname',
                                                'middlename',
                                                'suffix',
                                                'tid',
                                                'title',
                                                'usertypeid',
                                                'picurl'
                                          )->get();


                        $teachers = collect($teachers)->merge($teachers_faspriv);


                  }

            }else{

                  $teachers = DB::table('teacher')
                              ->select(
                                    'teacher.id',
                                    'firstname',
                                    'lastname',
                                    'middlename',
                                    'suffix',
                                    'tid',
                                    'title',
                                    'picurl'
                              )
                              ->get();
            }

            foreach($teachers as $item){
                  $temp_middle = '';
                  $temp_suffix = '';
                  $temp_title = '';
                  if(isset($item->middlename)){
                        $temp_middle = $item->middlename[0].'.';
                  }
                  if(isset($item->title)){
                        $temp_title = $item->title.'. ';
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ', '.$item->suffix;
                  }
                  $adviser = $temp_title.$item->firstname.' '.$temp_middle.' '.$item->lastname.$temp_suffix;
                  $item->fullname = $adviser;
                  $item->text =  $item->tid.' - '.$item->fullname;
            }

            return collect($teachers)->unique('tid')->values();

      }


      public static function schedule(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $teacherid = $request->get('teacherid');
            $subject = array();
            
            if(Session::get('currentPortal') != 1){
                  $college = self::college_get_teacher_college_sched($request);
            }else{
                  $college = array();
            }
            
            $gs = self::gs_sched($request);
            $shs = self::shs_sched($request);
            $all_sched = array();

            foreach($college as $item){
                  array_push($all_sched, $item);
            }
            foreach($gs as $item){
                  array_push($all_sched, $item);
            }
            foreach($shs as $item){
                  array_push($all_sched, $item);
            }
           
            return $all_sched;
            
      }

      public static function subjects(Request $request){

            $acad = $request->get('acad');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            if($acad == 6){

                  $subjects = Db::table('college_subjects')
                        ->where('college_subjects.deleted',0)
                        ->select(
                              'id',
                              'subjCode',
                              'subjDesc',
                              DB::raw("CONCAT(college_subjects.subjCode,' - ',college_subjects.subjDesc) as text")
                        )
                        ->get();

            }else if($acad == 5){

                  $subjects = DB::table('academicprogram')
                                    ->join('gradelevel',function($join){
                                          $join->on('academicprogram.id','=','gradelevel.acadprogid');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->join('subject_plot',function($join) use($syid, $semid){
                                          $join->on('gradelevel.id','=','subject_plot.levelid');
                                          $join->where('subject_plot.deleted',0);
                                          $join->where('subject_plot.syid',$syid);
                                          $join->where('subject_plot.semid',$semid);
                                    })
                                    ->join('sh_subjects',function($join){
                                          $join->on('subject_plot.subjid','=','sh_subjects.id');
                                          $join->where('sh_subjects.deleted',0);
                                    })
                                    ->where('academicprogram.id',5)
                                    ->select(
                                          'sh_subjects.id',
                                          'subjcode',
                                          'subjtitle as subjdesc',
                                          DB::raw("CONCAT(sh_subjects.subjcode,' - ',sh_subjects.subjtitle) as text")
                                    )
                                    ->distinct('id')
                                    ->get();

                  return $subjects;

            }else if($acad == 2 || $acad == 3 || $acad == 4){

                  $subjects = DB::table('academicprogram')
                                    ->join('gradelevel',function($join){
                                          $join->on('academicprogram.id','=','gradelevel.acadprogid');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->join('subject_plot',function($join) use($syid, $semid){
                                          $join->on('gradelevel.id','=','subject_plot.levelid');
                                          $join->where('subject_plot.deleted',0);
                                          $join->where('subject_plot.syid',$syid);
                                    })
                                    ->join('subjects',function($join){
                                          $join->on('subject_plot.subjid','=','subjects.id');
                                          $join->where('subjects.deleted',0);
                                          $join->where('subjects.isCon',0);
                                    })
                                    ->where('academicprogram.id',$acad)
                                    ->select(
                                          'subjects.id',
                                          'subjcode',
                                          'subjdesc',
                                          DB::raw("CONCAT(subjects.subjcode,' - ',subjects.subjdesc) as text")
                                    )
                                    ->distinct('id')
                                    ->get();

                  return $subjects;
            }

            return $subjects;

      }


      public static function college_get_teacher_college_sched(Request $request, $teacherid = null, $syid = null, $semid = null, $subjid = null){

            try{

                  $syid = $syid == null ? $request->get('syid') : $syid;
                  $semid = $semid == null ? $request->get('semid') : $semid;
                  $teacherid = $teacherid == null ? $request->get('teacherid') : $teacherid;
                  $subjid = $subjid == null ? $request->get('subjid') : $subjid;

                  $subjects = DB::table('college_classsched')
                                    ->join('college_prospectus',function($join) use($subjid){
                                          $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                          $join->where('college_prospectus.deleted',0);
                                          if($subjid != null){
                                                $join->where('college_prospectus.subjectID',$subjid);
                                          }
                                    })
                                    ->join('college_sections',function($join){
                                          $join->on('college_classsched.sectionid','=','college_sections.id');
                                          $join->where('college_sections.deleted',0);
                                    })
                                    ->join('college_courses',function($join){
                                          $join->on('college_sections.courseID','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->join('gradelevel',function($join){
                                          $join->on('college_sections.yearID','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->where('college_classsched.deleted',0);

                  if($semid != null){
                        $subjects = $subjects->where('college_classsched.semesterID',$semid);
                  }
                  if($syid != null){
                        $subjects = $subjects->where('college_classsched.syID',$syid);
                  }
                  if($teacherid != null){
                        $subjects = $subjects ->where('college_classsched.teacherid',$teacherid);
                  }

                  $subjects = $subjects->select(
                                          'courseabrv',
                                          'levelname',
                                          'sectionDesc',
                                          'sectionDesc as sectionname',
                                          'college_classsched.teacherID',
                                          'college_classsched.id',
                                          'college_classsched.id as schedid',
                                          'subjDesc',
                                          'subjCode',
                                          'subjDesc as subjdesc',
                                          'subjCode as subjcode',
                                          'lecunits',
                                          'labunits',
                                          'acadprogid',
                                          'gradelevel.sortid'
                                    )
                                    ->get();

                  foreach($subjects as $item){

                        $item->units = $item->lecunits + $item->labunits;
                        $item->levelname = str_replace(' COLLEGE','',$item->levelname);

                        $sched = DB::table('college_scheddetail')
                                          ->where('college_scheddetail.headerid',$item->id)
                                          ->where('college_scheddetail.deleted',0)
                                          ->leftJoin('rooms',function($join){
                                                $join->on('college_scheddetail.roomid','=','rooms.id');
                                                $join->where('rooms.deleted',0);
                                          })
                                          ->join('days',function($join){
                                                $join->on('college_scheddetail.day','=','days.id');
                                          })
                                          ->select(
                                                'day',
                                                'day as days',
                                                'roomid',
                                                'college_scheddetail.id as detailid',
                                                'college_scheddetail.headerid as id',
                                                'roomname',
                                                'stime',
                                                'etime',
                                                'days.description',
                                                'schedotherclass'
                                          )
                                          ->get();

                        $student_count = DB::table('college_studsched')
                                                      ->join('college_enrolledstud',function($join) use($syid,$semid){
                                                            $join->on('college_studsched.studid','=','college_enrolledstud.studid');
                                                            $join->where('college_enrolledstud.deleted',0);
                                                            $join->where('college_enrolledstud.syid',$syid);
                                                            $join->where('college_enrolledstud.semid',$semid);
                                                            $join->whereIn('college_enrolledstud.studstatus',[1,2,4]);
                                                      })
                                                      ->where('schedid',$item->id)
                                                      ->where('college_studsched.deleted',0)
                                                      ->where('schedstatus','!=','DROPPED')
                                                      ->count();

                        $item->teacher = null;
                        $item->teacherid = null;
                        $item->tid = null;
                        $item->studentcount = $student_count;
                        $item->enrolled = $student_count;

                        $temp_teacher = (object)[
                              'firstname'=>'',
                              'lastname'=>'',
                              'middlename'=>'',
                              'tid'=>'',
                              'id'=>'',
                              'teacherid'=>''
                        ];

                        if(isset($item->teacherID)){
                              $temp_teacher = DB::table('teacher')
                                                ->where('id',$item->teacherID)
                                                ->select(
                                                      'id as teacherid',
                                                      'firstname',
                                                      'lastname',
                                                      'middlename',
                                                      'tid'
                                                )
                                                ->first();
                              $temp_middle = '';
                              if(isset($temp_teacher->middlename)){
                                    $temp_middle = $temp_teacher->middlename[0].'.';
                              }
                              $item->teacher = $temp_teacher->firstname.' '.$temp_middle.' '.$temp_teacher->lastname;
                              $item->teacherid = $temp_teacher->tid;
                              $item->tid = $item->teacherID;
                        }


                        $check_group = DB::table('college_schedgroup_detail')
                                                ->where('college_schedgroup_detail.deleted',0)
                                                ->join('college_schedgroup',function($join){
                                                      $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                                                      $join->where('college_schedgroup.deleted',0);
                                                })
                                                ->where('schedid',$item->schedid)
                                                ->leftJoin('college_courses',function($join){
                                                      $join->on('college_schedgroup.courseid','=','college_courses.id');
                                                      $join->where('college_courses.deleted',0);
                                                })
                                                ->leftJoin('gradelevel',function($join){
                                                      $join->on('college_schedgroup.levelid','=','gradelevel.id');
                                                      $join->where('gradelevel.deleted',0);
                                                })
                                                ->leftJoin('college_colleges',function($join){
                                                      $join->on('college_schedgroup.collegeid','=','college_colleges.id');
                                                      $join->where('college_colleges.deleted',0);
                                                })
                                                ->select(
                                                'college_schedgroup.courseid',
                                                'college_schedgroup.levelid',
                                                'college_schedgroup.collegeid',
                                                'courseDesc',
                                                'collegeDesc',
                                                'levelname',
                                                'courseabrv',
                                                'collegeabrv',
                                                'college_schedgroup.id',
                                                'college_schedgroup.schedgroupdesc'
                                                )
                                                ->get();

                        foreach($check_group as $schedgroupitem){
                              $text = '';
                              if($schedgroupitem->courseid != null){
                                          $text = $schedgroupitem->courseabrv;
                              }else{
                                          $text = $schedgroupitem->collegeabrv;
                              }
                              $text .= '-'.$schedgroupitem->levelname[0] . ' '.$schedgroupitem->schedgroupdesc;
                              $schedgroupitem->schedgroupdesc = $text;
                              $item->sectionDesc = $text;
                              $item->sectionname = $text;
                        }

                        if(count($check_group) == 0){
                              $item->sectionDesc = '';
                              $item->sectionname = '';
                        }

                        $generated_sched = self::generate_sched_detail($sched,$temp_teacher);
                        $item->datatype = 'college';
                        $item->schedule = $generated_sched[0]->sched_list;
                        $item->id = $generated_sched[0]->schedid;
                        $item->schedid = $generated_sched[0]->schedid;
                        $item->search = $generated_sched[0]->search.' '.$item->sectionname.' '.$item->levelname.' '.$item->subjcode.' '.$item->subjdesc;


                        

                  }

                  return $subjects;
                 
            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function shs_sched(Request $request, $syid = null, $semid = null, $teacherid = null, $subjid = null){

            $syid = $syid == null ? $request->get('syid') : $syid;
            $semid = $semid == null ? $request->get('semid') : $semid;
            $teacherid = $teacherid == null ? $request->get('teacherid') : $teacherid;
            $subjid = $subjid == null ? $request->get('subjid') : $subjid;

            $sched = DB::table('sh_classsched')
                        ->where('sh_classsched.syid',$syid)
                        ->where('sh_classsched.semid',$semid)
                        ->where('sh_classsched.deleted',0);
                  
            if($teacherid != null){
                  $sched = $sched->where('sh_classsched.teacherid',$teacherid);
            }
            if($subjid != null){
                  $sched = $sched->where('subjid',$subjid);
            }

            $sched = $sched->join('sh_subjects',function($join){
                              $join->on('sh_classsched.subjid','=','sh_subjects.id');
                              $join->where('sh_subjects.deleted',0);
                        })
                        ->join('gradelevel',function($join){
                              $join->on('sh_classsched.glevelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                        })
                        ->join('sections',function($join){
                              $join->on('sh_classsched.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->select(
                              'subjid',
                              'glevelid as levelid',
                              'sectionid',
                              'subjtitle as subjdesc',
                              'subjcode',
                              'type',
                              'sectionname',
                              'sectionname as sectionDesc',
                              'levelname',
                              'gradelevel.acadprogid',
                              'gradelevel.sortid'
                        )
                        ->get();

            $subject = self::check_subject_plot($syid, $sched);

            $temp_id = 1;
            foreach($subject as $item){

                  $item->temp_id = $temp_id;
                  $temp_id += 1;
                  $search = '';
                  $sectionid = $item->sectionid;
                  $stdudents = 0;

                  $strand = array();

                  $subjstrand = DB::table('sh_sectionblockassignment')
                                    ->where('sh_sectionblockassignment.syid',$syid)
                                    ->where('sh_sectionblockassignment.sectionid',$sectionid)
                                    ->where('sh_sectionblockassignment.deleted',0)
                                    ->leftJoin('sh_block',function($join){
                                          $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                          $join->where('sh_block.deleted',0);
                                    })
                                    ->leftJoin('sh_strand',function($join){
                                          $join->on('sh_block.strandid','=','sh_strand.id');
                                          $join->where('sh_strand.deleted',0);
                                    })
                                    ->select('strandid','strandcode')
                                    ->get();

                  $item->strand = $subjstrand;

                  foreach($subjstrand as $stranditem){
                        array_push($strand, $stranditem->strandid);
                  }

                  $subjstrand = DB::table('subject_plot')
                                            ->where('deleted',0)
                                            ->where('subjid',$item->subjid)
                                            ->where('levelid',$item->levelid)
                                            ->where('semid',$semid)
                                            ->whereIn('strandid',$strand)
                                            ->select('strandid')
                                            ->get();
                     
                  $strand = array();
                  
                  $item->strand = $subjstrand;

                  foreach($subjstrand as $stranditem){
                        array_push($strand, $stranditem->strandid);
                  } 

                  $students = DB::table('sh_enrolledstud')                                    
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.sectionid',$sectionid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                              ->distinct('studid')
                              ->whereIn('sh_enrolledstud.strandid',$strand)
                              ->count();

                  $item->enrolled = $students;
                  $item->studentcount  = $students;
                  
                  $sched = DB::table('sh_classsched')
                              ->where('sh_classsched.syid',$syid)
                              ->where('sh_classsched.semid',$semid)
                              ->where('sh_classsched.subjid',$item->subjid)
                              ->where('sh_classsched.sectionid',$sectionid)
                              ->where('sh_classsched.deleted',0)
                              ->join('sh_classscheddetail',function($join){
                                    $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                                    $join->where('sh_classscheddetail.deleted',0);
                              })
                              ->leftJoin('rooms',function($join){
                                    $join->on('sh_classscheddetail.roomid','=','rooms.id');
                                    $join->where('rooms.deleted',0);
                              })
                              ->join('days',function($join){
                                    $join->on('sh_classscheddetail.day','=','days.id');
                              })
                              ->join('schedclassification',function($join){
                                    $join->on('sh_classscheddetail.classification','=','schedclassification.id');
                              })
                              ->select(
                                    'day',
                                    'roomid',
                                    'sh_classscheddetail.id as detailid',
                                    'sh_classsched.id',
                                    'roomname',
                                    'stime',
                                    'etime',
                                    'days.description',
                                    'teacherid',
                                    'schedclassification.description as classification',
                                    'day as days'
                              )
                              ->get();

                  if(count($sched) == 0){

                        $sched = DB::table('sh_blocksched')
                              ->where('sh_blocksched.syid',$syid)
                              ->where('sh_blocksched.subjid',$item->subjid)
                              
                              ->where('sh_blocksched.deleted',0)
                              ->join('sh_blockscheddetail',function($join){
                                    $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                                    $join->where('sh_blockscheddetail.deleted',0);
                              })
                              ->leftJoin('rooms',function($join){
                                    $join->on('sh_blockscheddetail.roomid','=','rooms.id');
                                    $join->where('rooms.deleted',0);
                              })
                              ->join('days',function($join){
                                    $join->on('sh_blockscheddetail.day','=','days.id');
                              })
                              ->join('schedclassification',function($join){
                                    $join->on('sh_blockscheddetail.classification','=','schedclassification.id');
                              })
                              ->select(
                                    'day',
                                    'sh_blockscheddetail.id as detailid',
                                    'roomid',
                                    'sh_blocksched.id',
                                    'roomname',
                                    'stime',
                                    'etime',
                                    'teacherid',
                                    'days.description',
                                    'schedclassification.description as classification',
                                    'day as days'
                              )
                              ->get();

                  }

                  $teacher = null;
                  $tid = null;
                  $teacherid = null;

                  $item->tid = null;
                  $item->teacher = null;
                  $item->teacherid = null;

                  $temp_teacher = (object)[
                        'firstname'=>'',
                        'lastname'=>'',
                        'middlename'=>'',
                        'tid'=>'',
                        'id'=>'',
                        'teacherid'=>''
                  ];

                  if(isset($sched[0]->teacherid)){
                  
                        $temp_teacher = DB::table('teacher')
                                          ->where('id',$sched[0]->teacherid)
                                          ->select(
                                                'id as teacherid',
                                                'firstname',
                                                'lastname',
                                                'middlename',
                                                'tid'
                                          )
                                          ->first();

                        $temp_middle = '';
                        if(isset($temp_teacher->middlename)){
                              $temp_middle = $temp_teacher->middlename[0].'.';
                        }
                        $item->teacher = $temp_teacher->firstname.' '.$temp_middle.' '.$temp_teacher->lastname;
                        $item->teacherid = $temp_teacher->tid;
                        $item->tid = $sched[0]->teacherid;

                  }


                  $generated_sched = self::generate_sched_detail($sched,$temp_teacher);

                  $item->datatype = 'juniorhigh';
                  $item->schedule = $generated_sched[0]->sched_list;
                  if($generated_sched[0]->schedid != null){
                        $item->id = $generated_sched[0]->schedid;
                        $item->schedid = $generated_sched[0]->schedid;
                  }else{
					if(isset($item->id)){
					    $item->id = $item->id;
                       $item->schedid = $item->id;
					}else{
						$item->id = null;
                       $item->schedid = null;
					}
                     
                  }
                  $item->search = $generated_sched[0]->search.' '.$item->sectionname.' '.$item->levelname.' '.$item->subjcode.' '.$item->subjdesc;
            
      
            }

            $subject = collect($subject)->sortBy('sectionname')->values();

            return $subject;

      }

      public static function gs_sched(Request $request, $syid = null, $semid = null, $teacherid = null, $subjid = null, $acad = null){

            $syid = $syid == null ? $request->get('syid') : $syid;
            $semid = $semid == null ? $request->get('semid') : $semid;
            $teacherid = $teacherid == null ? $request->get('teacherid') : $teacherid;
            $acad = $acad == null ? $request->get('acad') : $acad;
            $subjid = $subjid == null ? $request->get('subjid') : $subjid;
           
            $sched = DB::table('assignsubj')
                        ->where('assignsubj.syid',$syid)
                        ->where('assignsubj.deleted',0)
                        ->join('assignsubjdetail',function($join) use($teacherid,$subjid){
                            $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                            $join->where('assignsubjdetail.deleted',0);
                              if($teacherid != null){
                                    $join->where('assignsubjdetail.teacherid',$teacherid);
                              }
                              if($subjid != null){
                                    $join->where('assignsubjdetail.subjid',$subjid);
                              }
                        })
                        ->join('subjects',function($join){
                              $join->on('assignsubjdetail.subjid','=','subjects.id');
                              $join->where('subjects.deleted',0);
                        })
                        ->join('gradelevel',function($join) use($acad){
                              $join->on('assignsubj.glevelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              if($acad != null){
                                    $join->where('gradelevel.acadprogid',$acad);
                              }
                        })
                        ->join('sections',function($join){
                              $join->on('assignsubj.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->leftJoin('teacher',function($join){
                            $join->on('assignsubjdetail.teacherid','=','teacher.id');
                            $join->where('teacher.deleted',0);
                        })
                        ->select(
                              'subjid',
                              'glevelid as levelid',
                              'sectionid',
                              'subjdesc',
                              'subjcode',
                              'isCon',
                              'isSP',
                              'subjCom',
                              'sectionname',
                              'sectionname as sectionDesc',
                              'levelname',
                              'gradelevel.acadprogid',
                              'gradelevel.sortid',
                              'subjects.term'
                        )
                        ->get();

            $subject = self::check_subject_plot($syid, $sched);

            $temp_id = 1;

            foreach($subject as $item){

                  $item->temp_id = $temp_id;
                  $temp_id += 1;
                  
                  $sectionid = $item->sectionid;
                  $stdudents = 0;

                  $students = DB::table('enrolledstud')
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.sectionid',$sectionid)
                              ->where('enrolledstud.deleted',0)
                              ->whereIn('enrolledstud.studstatus',[1,2,4])
                              ->count();

                  $check_subject = DB::table('subjects')
                              ->where('deleted',0)
                              ->where('id',$item->subjid)
                              ->first();

                  if( isset($check_subject->isSP)){
                        if($check_subject->isSP == 1){
                              $temp_subjid = $item->subjid;
                              $students = DB::table('enrolledstud')
                                                ->join('subjects_studspec',function($join) use($temp_subjid){
                                                      $join->on('enrolledstud.studid','=','subjects_studspec.studid');
                                                      $join->where('subjects_studspec.deleted',0);
                                                      $join->where('subjects_studspec.subjid',$temp_subjid);
                                                })
                                                ->where('enrolledstud.syid',$syid)
                                                ->where('enrolledstud.sectionid',$sectionid)
                                                ->where('enrolledstud.deleted',0)
                                                ->whereIn('enrolledstud.studstatus',[1,2,4])
                                                ->count();
                        }
                  }

                  $item->enrolled = $students;
                  $item->studentcount  = $students ;
                  
                  $sched = DB::table('classsched')
                              ->where('classsched.syid',$syid)
                              ->where('classsched.subjid',$item->subjid)
                              ->where('classsched.sectionid',$sectionid)
                              ->where('classsched.deleted',0)
                              ->leftJoin('classscheddetail',function($join){
                                    $join->on('classsched.id','=','classscheddetail.headerid');
                                    $join->where('classscheddetail.deleted',0);
                              })
                              ->leftJoin('rooms',function($join){
                                    $join->on('classscheddetail.roomid','=','rooms.id');
                                    $join->where('rooms.deleted',0);
                              })
                              ->leftJoin('days',function($join){
                                    $join->on('classscheddetail.days','=','days.id');
                              })
                              ->leftJoin('schedclassification',function($join){
                                    $join->on('classscheddetail.classification','=','schedclassification.id');
                              })
                              ->select(
                                    'roomid',
                                    'classsched.id',
                                    'roomname',
                                    'stime',
                                    'etime',
                                    'days.description',
                                    'classscheddetail.id as detailid',
                                    'schedclassification.description as classification',
                                    'roomid',
                                    'days'
                              )
                              ->get();

                  $temp_subj = $item->subjid;

                  $asssubj = DB::table('assignsubj')
                              ->where('assignsubj.syid',$syid)
                              ->where('assignsubj.sectionid',$sectionid)
                              ->where('assignsubj.deleted',0)
                              ->join('assignsubjdetail',function($join) use($temp_subj){
                                    $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                                    $join->where('assignsubjdetail.deleted',0);
                                    $join->where('assignsubjdetail.subjid',$temp_subj);
                              })
                              ->leftJoin('teacher',function($join){
                                    $join->on('assignsubjdetail.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                              })
                              ->select(
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'title',
                                    'teacherid',
                                    'tid'
                              )
                              ->first();

                  $teacher = null;
                  $tid = null;
                  $teacherid = null;

                  $item->tid = null;
                  $item->teacher = null;
                  $item->teacherid = null;

                  if(isset($asssubj->teacherid)){
                  
                        $temp_teacher = DB::table('teacher')
                                          ->where('id',$asssubj->teacherid)
                                          ->first();

                        $temp_middle = '';
                        if(isset($temp_teacher->middlename)){
                              $temp_middle = $temp_teacher->middlename[0].'.';
                        }
                        $item->teacher = $temp_teacher->firstname.' '.$temp_middle.' '.$temp_teacher->lastname;
                        $item->teacherid = $temp_teacher->tid;
                        $item->tid = $asssubj->teacherid;
                  }

                  $generated_sched = self::generate_sched_detail($sched,$asssubj);

                  $item->datatype = 'juniorhigh';
                  $item->schedule = $generated_sched[0]->sched_list;
                  $item->id = $generated_sched[0]->schedid;
                  $item->schedid = $generated_sched[0]->schedid;
                  $item->search = $generated_sched[0]->search.' '.$item->sectionname.' '.$item->levelname.' '.$item->subjcode.' '.$item->subjdesc;
            
            }

            $subject = collect($subject)->sortBy('sectionname')->values();
           
            return $subject;

      }

      public static function check_subject_plot($syid = null, $sched = null){
            $subject = array();
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
            return $subject;
      }

      public static function generate_sched_detail($sched = null, $asssubj = null){
            $sched_list = array();
            $sched_count = 1;
            $search = '';
            $schedid = null;
            foreach($sched as $sched_item){
                  $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
            }
            $starting = collect($sched)->groupBy('time');
            foreach($starting as $sched_item){
                  $dayString = '';
                  $days = array();
                  foreach($sched_item as $new_item){
                        $schedid = $new_item->id;
                        $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                        $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                        $dayString.= substr($new_item->description, 0,3).' / ';
                        $detailid = $new_item->detailid;
                        $roomname = $new_item->roomname;
                        $roomid = $new_item->roomid;
                        $classification = isset($new_item->classification) ? $new_item->classification : null;
                        $time = $new_item->time;
                        array_push($days,$new_item->days);
                  }
                  $dayString = substr($dayString, 0 , -2);
                  array_push($sched_list,(object)[
                        'day'=>$dayString,
                        'start'=>$start,
                        'end'=>$end,
                        'roomid',
                        'detailid'=>$detailid,
                        'roomname'=>$roomname,
                        'roomid'=>$roomid,
                        'classification'=>$classification,
                        'teacher'=> $asssubj->firstname.' '.$asssubj->middlename.' '.$asssubj->lastname,
                        'tid'=>$asssubj->tid,
                        'teacherid'=>$asssubj->teacherid,
                        'sched_count'=>$sched_count,
                        'time'=>$time,
                        'days'=>$days
                  ]);
                  $search .= $dayString.' ';
                  $sched_count += 1;
            }
            return array((object)[
                  'sched_list'=>$sched_list,
                  'search'=>$search,
                  'schedid'=>$schedid
            ]);
      }

      public static function collegesched_plot(Request $request){

            $acad = $request->get('acad');

            if($acad == 6){
                  $schedule =  self::college_get_teacher_college_sched($request);
            }elseif($acad == 5){
                  $schedule = self::shs_sched($request);
            }elseif($acad == 2 || $acad == 3 || $acad == 4){
                  $schedule = self::gs_sched($request);
            }

            return view('superadmin.pages.teacher.collegeschedplot')
                              ->with('schedule',$schedule);
      }

      public static function college_add_sched(Request $request){

            try{

                  $teacherid = $request->get('teacherid');
                  $schedule = $request->get('schedule');
                  $acadprogid = $request->get('acad');

                  if($acadprogid == 6){
                        foreach($schedule as $item){
                              DB::table('college_classsched')
                                    ->where('id',$item)
                                    ->update([
                                          'teacherid'=>$teacherid,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }
                  else if($acadprogid == 5){
                        foreach($schedule as $item){
                              DB::table('sh_classsched')
                                    ->where('id',$item)
                                    ->update([
                                          'teacherid'=>$teacherid,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }elseif($acadprogid == 2 || $acadprogid == 3 || $acadprogid == 4){
                        
                        foreach($schedule as $item){

                              $info = DB::table('classsched')
                                    ->where('id',$item)
                                    ->where('deleted',0)
                                    ->first();
                        
                              $header = DB::table('assignsubj')
                                          ->where('glevelid',$info->glevelid)
                                          ->where('sectionid',$info->sectionid)
                                          ->where('syid',$info->syid)
                                          ->where('deleted',0)
                                          ->select('id')
                                          ->first();

                              DB::table('assignsubjdetail')
                                    ->where('headerid',$header->id)
                                    ->where('subjid',$info->subjid)
                                    ->update([
                                          'teacherid'=>$teacherid,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>'Added Successfully!',
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function remove_teacher_sched(Request $request){

            $schedule = $request->get('schedid');
            $acadprogid = $request->get('acad');

            try{
                  if($acadprogid == 6){
                        DB::table('college_classsched')
                              ->where('id',$schedule)
                              ->update([
                                    'teacherid'=>null,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
                  else if($acadprogid == 5){
                        DB::table('sh_classsched')
                              ->where('id',$schedule)
                              ->update([
                                    'teacherid'=>null,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }elseif($acadprogid == 2 || $acadprogid == 3 || $acadprogid == 4){

                        $info = DB::table('classsched')
                                    ->where('id',$schedule)
                                    ->where('deleted',0)
                                    ->first();
                        
                        $header = DB::table('assignsubj')
                                    ->where('glevelid',$info->glevelid)
                                    ->where('sectionid',$info->sectionid)
                                    ->where('syid',$info->syid)
                                    ->where('deleted',0)
                                    ->select('id')
                                    ->first();

                        DB::table('assignsubjdetail')
                              ->where('headerid',$header->id)
                              ->where('subjid',$info->subjid)
                              ->update([
                                    'teacherid'=>null,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted Successfully!',
                  ]);

            }catch(\Exception $e){
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

      public static function update_profile(Request $request){

            try{
                  $firstname = $request->get('firstname');
                  $middlename = $request->get('middlename');
                  $lastname = $request->get('lastname');
                  $suffix = $request->get('suffix');

                  $nationalid = $request->get('nationalid');
                  $dob = $request->get('dob');
                  $gender = $request->get('gender');
                  $address = $request->get('address');
                  $maritalstat = $request->get('maritalstat');
                  $contactno = $request->get('contactno');
                  $email = $request->get('email');


                  $teacherinfo = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->first();

                  DB::table('teacher')
                            ->where('tid',auth()->user()->email)
                            ->take(1)
                            ->update([
                                    'firstname'=>$firstname,
                                    'middlename'=>$middlename,
                                    'lastname'=>$lastname,
                                    'suffix'=>$suffix,
                            ]);

                  $check_profile = Db::table('employee_personalinfo')
                                          ->where('employeeid',$teacherinfo->id)
                                          ->count();

                  if($check_profile == 0){
                        $check_profile = Db::table('employee_personalinfo')
                                                ->insert([
                                                      'employeeid'=>$teacherinfo->id,
                                                      'nationalityid'=>$nationalid,
                                                      'dob'=>$dob,
                                                      'gender'=>$gender,
                                                      'address'=>$address,
                                                      'contactnum'=>str_replace('-','',$contactno),
                                                      'email'=>$email,
                                                      'maritalstatusid'=>$maritalstat,
                                                      'updated_by'=>auth()->user()->id,
                                                      'updated_on'=>\Carbon\Carbon::now('Asia/Manila')
                                                ]);
                  }else{
                        $check_profile = Db::table('employee_personalinfo')
                                          ->where('employeeid',$teacherinfo->id)
                                          ->update([
                                                'employeeid'=>$teacherinfo->id,
                                                'employeeid'=>$teacherinfo->id,
                                                'nationalityid'=>$nationalid,
                                                'dob'=>$dob,
                                                'gender'=>$gender,
                                                'email'=>$email,
                                                'address'=>$address,
                                                'contactnum'=>str_replace('-','',$contactno),
                                                'maritalstatusid'=>$maritalstat,
                                                'created_by'=>auth()->user()->id,
                                                'created_on'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);
                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Profile Updated'
                  ]);

            }catch(\Exception $e){
                  
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong!'
                  ]);
            }

      }

      public static function my_profile(Request $request){

            $teacherid = $request->get('id');
            $teacherinfo = DB::table('teacher');

            if($teacherid != '' && $teacherid != null){
                if(Session::get('currentPortal') == 2 || Session::get('currentPortal') == 17  || Session::get('currentPortal') == 14){
                    $teacherinfo = $teacherinfo->where('teacher.id',$teacherid);
                }
            }else{
                $teacherinfo = $teacherinfo->where('tid',auth()->user()->email);
            }
                            
            $teacherinfo = $teacherinfo->leftJoin('employee_personalinfo',function($join){
                                $join->on('teacher.id','=','employee_personalinfo.employeeid');
                            })
                            ->leftJoin('nationality',function($join){
                                $join->on('employee_personalinfo.nationalityid','=','nationality.id');
                            })
                              ->join('usertype',function($join){
                                    $join->on('teacher.usertypeid','=','usertype.id');
                              })
                            ->select(
                                'title',
                                'acadtitle',
                                'teacher.id',
                                'firstname',
                                'lastname',
                                'middlename',
                                'suffix',
                                'dob',
                                'address',
                                'contactnum',
                                'gender',
                                'nationality',
                                'nationalityid',
                                'email',
                                'tid',
                                'picurl',
                                'maritalstatusid',
                                'date_joined',
                                'utype',
                                'userid'
                            )                        
                            ->get();

            $teacheracadprog = DB::table('teacheracadprog')
                                    ->join('academicprogram',function($join){
                                          $join->on('teacheracadprog.acadprogid','=','academicprogram.id');
                                    })
                                    ->where('teacheracadprog.deleted',0)
                                    ->where('teacheracadprog.teacherid',$teacherinfo[0]->id)
                                    ->distinct('acadprogid')
                                    ->select(
                                          'progname'
                                    )
                                    ->get();

            $faspriv = DB::table('faspriv')
                                    ->join('usertype',function($join){
                                          $join->on('faspriv.usertype','=','usertype.id');
                                    })
                                    ->where('faspriv.deleted',0)
                                    ->where('faspriv.userid',$teacherinfo[0]->userid)
                                    ->distinct('acadprogid')
                                    ->select(
                                          'utype'
                                    )
                                    ->get();

            foreach($teacherinfo as $item){

                  if($item->dob != null){
                        $item->dobinput = $item->dob;
                        $item->dob = \Carbon\Carbon::create($item->dob)->isoFormat('MMMM DD, YYYY');
                        $item->dob_format1 = \Carbon\Carbon::create($item->dob)->isoFormat('YYYY-MM-DD');
                        $item->date_joined = \Carbon\Carbon::create($item->date_joined)->isoFormat('MMMM DD, YYYY');
                  }else{
                        $item->dob_format1 = '';
                        $item->dob_format1 = '--';
                        
                  }
                  
                  if($item->maritalstatusid == 1){
                        $item->maritalstatus = 'Single';
                  }else if($item->maritalstatusid == 2){
                        $item->maritalstatus = 'Married';
                  }else if($item->maritalstatusid == 3){
                        $item->maritalstatus = 'Divorced';
                  }else if($item->maritalstatusid == 4){
                        $item->maritalstatus = 'Separated';
                  }else if($item->maritalstatusid == 5){
                        $item->maritalstatus = 'Widowed';
                  }else{
                        $item->maritalstatus = null;
                  }

                  if($item->picurl == null){
                        $item->picurl = "";
                  }

                  $item->acadprog = $teacheracadprog;
                  $item->faspriv = $faspriv;
            }
    
            return $teacherinfo;
        }
      
        public static function update_photo(Request $request){

    
            $teacherinfo =  DB::table('teacher')
                              ->where('tid',auth()->user()->email)
                               ->first();

            $link = DB::table('schoolinfo')
                              ->select('essentiellink')
                              ->first()
                              ->essentiellink;


                              
            $sy = DB::table('sy')
                        ->where('isactive',1)
                        ->first();

            if($link == null){
                  return array( (object)[
                        'status'=>'0',
                        'message'=>'Error',
                        'errors'=>array(),
                        'inputs'=>$request->all()
                  ]);
            }

                  $urlFolder = str_replace('https://','',$link);
                  $urlFolder = str_replace('http://','',$urlFolder);

                  if (! File::exists(public_path().'employeeprofile/'.$sy->sydesc)) {
                        $path = public_path('employeeprofile/'.$sy->sydesc);
                        if(!File::isDirectory($path)){
                              File::makeDirectory($path, 0777, true, true);
                        }
                  }

                  if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc)) {
                        $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc;
                        if(!File::isDirectory($cloudpath)){
                              File::makeDirectory($cloudpath, 0777, true, true);
                        }
                  }

                  $date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYYYHHmmss');
                  $data = $request->image;
                  list($type, $data) = explode(';', $data);
                  list(, $data)      = explode(',', $data);
                  $data = base64_decode($data);
                  $extension = 'png';
                  $destinationPath = public_path('employeeprofile/'.$sy->sydesc.'/'.$teacherinfo->tid.'_'.$teacherinfo->lastname.'.'.$extension);
                  $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc.'/'.$teacherinfo->tid.'_'.$teacherinfo->lastname.'.'.$extension;
                  file_put_contents($clouddestinationPath, $data);
                  file_put_contents($destinationPath, $data);

                  DB::table('teacher')
                        ->where('id',$teacherinfo->id)
                        ->take(1)
                        ->update(['picurl'=>'employeeprofile/'.$sy->sydesc.'/'.$teacherinfo->tid.'_'.$teacherinfo->lastname.'.'.$extension ]);

                  $data = array((object)
                        [
                              'status'=>'1',
                        ]);
      
                  return $data;
    
    
        }
}
