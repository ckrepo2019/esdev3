<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;

class GradePostingController extends \App\Http\Controllers\Controller
{

      public static function get_grades($syid,$withdetails = false){

            $allgrades = DB::table('grades')
                              ->where('syid',$syid)
                              ->where('deleted',0);

            if($withdetails){
                  $allgrades = $allgrades->join('gradesdetail',function($join){
                        $join->on('grades.id','=','gradesdetail.headerid');
                  });
            }

            
            $allgrades = $allgrades->select(
                                          'coorapp',
                                          'coorappdatetime',
                                          'grades.id',
                                          'status',
                                          'date_submitted',
                                          'submitted',
                                          'quarter',
                                          'grades.updateddatetime',
                                          'subjid',
                                          'sectionid',
                                          'levelid'
            );
            
            
            if($withdetails){
                  $allgrades = $allgrades->addSelect(
                        'studid',
                        'gdstatus',
                        'qg'
                  );
            }

            $allgrades = $allgrades->get();

            return $allgrades;
            
      }

      public static function get_sections($syid, $acad = []){


            $sections = DB::table('sectiondetail')
                              ->where('sectiondetail.deleted',0)
                              ->where('sectiondetail.syid',$syid)
                              ->join('sections',function($join){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($acad){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    // if(count($acad) > 0){
                                          $join->whereIn('acadprogid',$acad);
                                    // }
                              })
                              ->select(
                                    'levelname',
                                    'sections.id',
                                    'sections.sectionname',
                                    'sections.levelid',
                                    'sd_issp'
                              )
                              ->get();
      
            return $sections;
      }


      public static function get_acad(){


      }

      public static function get_shssubjects($syid, $semid){

            $allshssubjects = DB::table('subject_plot')
                                    ->where('subject_plot.deleted',0)
                                    ->whereIn('subject_plot.levelid',[14,15])
                                    ->where('subject_plot.syid',$syid)
                                    ->join('sh_subjects',function($join){
                                          $join->on('subject_plot.subjid','=','sh_subjects.id');
                                          $join->where('sh_subjects.deleted',0);
                                    })
                                    ->where('subject_plot.semid',$semid)
                                    ->distinct('subject_plot.subjid')
                                    ->select(
                                          'subjtitle as subjdesc',
                                          'subjcode',
                                          'subject_plot.subjid',
                                          'plotsort',
                                          'subject_plot.levelid',
                                          'subject_plot.strandid',
                                          'isforsp',
                                          'subjcoor',
                                          'subject_plot.semid'
                                    )
                                    ->orderBy('plotsort')
                                    ->get();

            return $allshssubjects;
      }


      public static function get_gsjhssubjects($syid){

            $allgshsubjects = DB::table('subject_plot')
                              ->where('subject_plot.deleted',0)
                              ->whereNotIn('subject_plot.levelid',[14,15])
                              ->where('subject_plot.syid',$syid)
                              ->join('subjects',function($join){
                                    $join->on('subject_plot.subjid','=','subjects.id');
                                    $join->where('subjects.deleted',0);
                                    $join->where('subjects.isCon',0);
                              })
                              ->select(
                                    'subjdesc',
                                    'subjcode',
                                    'subject_plot.subjid',
                                    'plotsort',
                                    'subject_plot.levelid',
                                    'isforsp',
                                    'subjcoor',
                                    'isSP'
                              )
                              ->orderBy('plotsort')
                              ->get();

            return $allgshsubjects;
      }


      public static function get_sectionstrand($syid){

            $sectionstrand = DB::table('sh_sectionblockassignment')
                                    ->join('sh_block',function($join){
                                          $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                          $join->where('sh_block.deleted',0);
                                    })
                                    ->where('sh_sectionblockassignment.deleted',0)
                                    ->select(
                                          'strandid',
                                          'sectionid'
                                    )
                                    ->where('sh_sectionblockassignment.syid',$syid)
                                    ->distinct('strandid')
                                    ->get();

            return $sectionstrand;
      }


      public static function per_student_grades_status(Request $request){

            $syid = $request->get('syid');;
            $semid = $request->get('semid');

            $acad = array();
            $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
            
            $temp_teacherid = null;

            if(Session::get('currentPortal') == 2){

                  $teacherid = DB::table('teacher')
                                    ->where('deleted',0)
                                    ->where('userid',auth()->user()->id)
                                    ->first();

                  // $temp_acad = DB::table('academicprogram')
                  //             ->where('principalid',$teacherid->id)
                  //             ->where('syid',$syid)
                  //             ->select('id')
                  //             ->get();

                  $temp_acad = DB::table('teacheracadprog')
                              ->where('teacherid',$teacherid->id)
                              ->where('syid',$syid)
                              ->where('acadprogutype',2)
                              ->where('acadprogid','!=',6)
                              ->select('acadprogid as id')
                              ->where('deleted',0)
                              ->get();

                  foreach($temp_acad as $item){
                        array_push($acad,$item->id);
                  }
            }else{

                  $acad = array();

                  $teacherid = DB::table('teacher')
                                    ->where('deleted',0)
                                    ->where('userid',auth()->user()->id)
                                    ->first();

                  $temp_teacherid = $teacherid->id;

                  $temp_acad = DB::table('teacheracadprog')
                              ->where('teacherid',$teacherid->id)
                              ->where('syid',$syid)
                              ->where('acadprogutype',Session::get('currentPortal'))
                              ->select('acadprogid as id')
                              ->where('deleted',0)
                              ->groupBy('acadprogid')
                              ->get();

                  foreach($temp_acad as $item){
                        array_push($acad,$item->id);
                  }

            }


            $data = array();
            $allshssubjects = self::get_shssubjects($syid,$semid);
            if($check_refid->refid == 22){
                  $allshssubjects = collect($allshssubjects)->where('subjcoor',$temp_teacherid)->values();
            }

            $allgshsubjects = self::get_gsjhssubjects($syid);
            if($check_refid->refid == 22){
                  $allgshsubjects = collect($allgshsubjects)->where('subjcoor',$temp_teacherid)->values();
            }

            $sections = self::get_sections($syid,$acad);
            $allgrades = self::get_grades($syid,true);
            $allsectionstrand = self::get_sectionstrand($syid);

            // return "hsdfs";

            foreach($sections as $item){


                  //fetch section subject
                  if($item->levelid == 14 || $item->levelid == 15){

                        $strand = array();

                        $sectionstrand = collect($allsectionstrand)
                                          ->where('sectionid',$item->id)
                                          ->values();

                        foreach($sectionstrand as $stranditem){
                              array_push($strand, $stranditem->strandid);
                        }

                        $subjects = collect($allshssubjects)
                                          ->where('levelid',$item->levelid)
                                          ->whereIn('strandid',$strand)
                                          ->values();
                  }else{

                        $subjects = collect($allgshsubjects)
                                          ->where('levelid',$item->levelid)
                                          ->values();
                  }

                  //filter special subject
                  if($item->sd_issp == 0){
                        $subjects = collect($subjects)->where('isforsp',0)->values();
                  }

                  $subjects = collect($subjects)->unique('subjid')->values();
                  $sectionid = $item->id;

                  if($item->levelid == 14 || $item->levelid == 15){

                        $student = DB::table('sh_enrolledstud')
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->where('sh_enrolledstud.syid',$syid)
                                    ->where('sh_enrolledstud.sectionid',$sectionid)
                                    ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                    ->whereIn('sh_enrolledstud.strandid',$strand)
                                    ->join('studinfo',function($join){
                                          $join->on('studinfo.id','=','sh_enrolledstud.studid');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->orderBy('gender','desc')
                                    ->orderBy('studentname','asc')
                                    ->select(
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'sh_enrolledstud.levelid',
                                          'sh_enrolledstud.sectionid',
                                          'dob',
                                          'gender',
                                          'lrn',
                                          'sid',
                                          'studinfo.id',
                                          'sh_enrolledstud.strandid',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                    )
                                    ->distinct('studid')
                                    ->get();
                       
                  }else{
                        $student = DB::table('enrolledstud')
                              ->where('enrolledstud.deleted',0)
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.sectionid',$sectionid)
                              ->whereIn('enrolledstud.studstatus',[1,2,4])
                              ->join('studinfo',function($join){
                                    $join->on('studinfo.id','=','enrolledstud.studid');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->orderBy('gender','desc')
                              ->orderBy('lastname')
                              ->orderBy('studentname','asc')
                              ->select(
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'enrolledstud.levelid',
                                    'enrolledstud.sectionid',
                                    'dob',
                                    'gender',
                                    'lrn',
                                    'sid',
                                    'studinfo.id',
                                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                              )
                              ->get();
                  }

                  $student_studspec = DB::table('subjects_studspec')
                                          ->where('deleted',0)
                                          ->where('syid',$syid)
                                          ->get();

                  foreach($subjects as $subjitem){

                        $subjid = $subjitem->subjid;

                        $grades = collect($allgrades)
                                    ->where('subjid',$subjid)
                                    ->where('levelid',$item->levelid)
                                    ->where('sectionid',$item->id)
                                    ->values();

                        foreach($student as $student_item){

                              for($x=1;$x<=4;$x++){

                                    $isincluded = true;
                                    $isincluded = true;
                                    if($subjitem->levelid == 14 || $subjitem->levelid == 15){
                                          if($student_item->strandid != $subjitem->strandid){
                                              $isincluded = false;
                                          }
                                          if($semid == 2 && ( $x == 1 || $x == 2 )){
                                                $isincluded = false;
                                          }
                                          if($semid == 1 && ( $x == 3 || $x == 4 )){
                                                $isincluded = false;
                                          }
                                      }else{
                                          if($subjitem->isSP == 1){
                                              $check = collect($student_studspec)->where('studid',$student_item->id)->where('subjid',$subjitem->subjid)->where('q'.$x,1)->count();
                                              if($check == 0 ){
                                                  
                                                  $isincluded = false;
                                              }
                                          }
                                      }

                                    if($isincluded){

                                          $grade = (object)[
                                                'qg'=>null,
                                                'gdstatus'=>null,
                                                'detailid'=>null
                                          ];

                                          $grade_detail = collect($grades)
                                                            ->where('quarter',$x)
                                                            ->where('studid',$student_item->id)
                                                            ->first();

                                        //  return  collect($grade_detail);

                                          if(isset($grade_detail->qg)){
                                                $grade->qg = $grade_detail->qg;
                                                $grade->gdstatus = $grade_detail->gdstatus;
                                                $grade->detailid = $grade_detail->id;
                                          }

                                          array_push( $data, (object)[
                                                'sectionname'=>$item->sectionname,
                                                'levelname'=>$item->levelname,
                                                'subjdesc'=>$subjitem->subjdesc,
                                                'subjcode'=>$subjitem->subjcode,
                                                'studid'=>$student_item->id,
                                                'student'=>$student_item->lastname.', '.$student_item->firstname,
                                                'qg'=>$grade->qg,
                                                'detailid'=>$grade->detailid,
                                                'gdstatus'=>$grade->gdstatus,
                                                'quarter'=>$x
                                          ]);
                                    }
                              }

                        }

                  }
                  
            }

            return $data;

      }


      public static function all_grades(Request $request){

            $acad = array();
            $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
            $syid = $request->get('syid');;
            $semid = $request->get('semid');
            
            $temp_teacherid = null;

            if(Session::get('currentPortal') == 2){

                  $teacherid = DB::table('teacher')
                                    ->where('deleted',0)
                                    ->where('userid',auth()->user()->id)
                                    ->first();

                  // $temp_acad = DB::table('academicprogram')
                  //             ->where('principalid',$teacherid->id)
                  //             ->select('id')
                  //             ->get();


                  $temp_acad = DB::table('teacheracadprog')
                              ->where('teacherid',$teacherid->id)
                              ->where('syid',$syid)
                              ->where('acadprogutype',2)
                              ->where('acadprogid','!=',6)
                              ->select('acadprogid as id')
                              ->where('deleted',0)
                              ->get();

                  foreach($temp_acad as $item){
                        array_push($acad,$item->id);
                  }
            }else{

                  $acad = array();

                  $teacherid = DB::table('teacher')
                                    ->where('deleted',0)
                                    ->where('userid',auth()->user()->id)
                                    ->first();

                  $temp_teacherid = $teacherid->id;

                  $temp_acad = DB::table('teacheracadprog')
                              ->where('teacherid',$teacherid->id)
                              ->where('syid',$syid)
                              ->where('acadprogutype',Session::get('currentPortal'))
                              ->select('acadprogid as id')
                              ->where('deleted',0)
                              ->groupBy('acadprogid')
                              ->get();

                  foreach($temp_acad as $item){
                        array_push($acad,$item->id);
                  }

            }

           
            //fetch all the available section for the selected school year
            $sections = DB::table('sectiondetail')
                              ->where('sectiondetail.deleted',0)
                              ->where('sectiondetail.syid',$syid)
                              ->join('sections',function($join){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($acad){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    // if(count($acad) > 0){
                                          $join->whereIn('acadprogid',$acad);
                                    // }
                              })
                              ->select(
                                    'levelname',
                                    'sections.id',
                                    'sections.sectionname',
                                    'sections.levelid',
                                    'sd_issp'
                              )
                              ->get();

            $data = array();

            $allshssubjects = self::get_shssubjects($syid,$semid);
            if($check_refid->refid == 22){
                  $allshssubjects = collect($allshssubjects)->where('subjcoor',$temp_teacherid)->values();
            }

            $allgshsubjects = self::get_gsjhssubjects($syid);
            if($check_refid->refid == 22){
                  $allgshsubjects = collect($allgshsubjects)->where('subjcoor',$temp_teacherid)->values();
            }

            $sections = self::get_sections($syid,$acad);
            $allgrades = self::get_grades($syid);

            //fetch all gs teachers
            $shsteacher = DB::table('sh_classsched')
                              ->where('sh_classsched.syid',$syid)
                              ->where('sh_classsched.semid',$semid)
                              ->where('sh_classsched.deleted',0)
                              ->join('teacher',function($join){
                                    $join->on('sh_classsched.teacherid','=','teacher.id');
                                    $join->where('sh_classsched.deleted',0);
                              })
                              ->select(
                                    'lastname',
                                    'firstname',
                                    'suffix',
                                    'middlename',
                                    'title',
                                    'tid',
                                    'sectionid',
                                    'subjid'
                              )
                              ->get();

            $gsjhsteacher = DB::table('assignsubj')
                              ->where('assignsubj.syid',$syid)
                              ->where('assignsubj.deleted',0)
                              ->join('assignsubjdetail',function($join){
                                    $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                                    $join->where('assignsubjdetail.deleted',0);
                              })
                              ->join('teacher',function($join){
                                    $join->on('assignsubjdetail.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                              })
                              ->select(
                                    'lastname',
                                    'firstname',
                                    'suffix',
                                    'middlename',
                                    'title',
                                    'tid',
                                    'sectionid',
                                    'subjid'
                              )
                              ->get();

            $allsectionstrand = self::get_sectionstrand($syid);

            foreach($sections as $item){

                  //fetch section subject
                  if($item->levelid == 14 || $item->levelid == 15){

                        $strand = array();

                        $sectionstrand = collect($allsectionstrand)
                                          ->where('sectionid',$item->id)
                                          ->values();

                        foreach($sectionstrand as $stranditem){
                              array_push($strand, $stranditem->strandid);
                        }

                        $subjects = collect($allshssubjects)
                                          ->where('levelid',$item->levelid)
                                          ->whereIn('strandid',$strand)
                                          ->values();
                  }else{

                        $subjects = collect($allgshsubjects)
                                          ->where('levelid',$item->levelid)
                                          ->values();
                  }

                  //filter special subject
                  if($item->sd_issp == 0){
                        $subjects = collect($subjects)->where('isforsp',0)->values();
                  }

                  $subjects = collect($subjects)->unique('subjid')->values();

                  foreach($subjects as $subjitem){

                        $subjid = $subjitem->subjid;
                        $teacherinfo = null;
                        $teacherid = null;

                        if($item->levelid == 14 || $item->levelid == 15){

                             $teacher = collect($shsteacher)
                                          ->where('subjid',$subjid)
                                          ->where('sectionid',$item->id)
                                          ->first();

                        }else{

                              $teacher = collect($gsjhsteacher)
                                          ->where('subjid',$subjid)
                                          ->where('sectionid',$item->id)
                                          ->first();
                             
                        }

                        if(isset($teacher->lastname)){
                              $middlename = explode(" ",$teacher->middlename);
                              $temp_middle = '';
                              if($middlename != null){
                                    foreach ($middlename as $middlename_item) {
                                          if(strlen($middlename_item) > 0){
                                                $temp_middle .= $middlename_item[0].'.';
                                          } 
                                    }
                              }
                              $teacherinfo = $teacher->title.' '.$teacher->firstname.' '.$temp_middle.' '.$teacher->lastname.' '.$teacher->suffix;
                              $teacherid = $teacher->tid;
                        }

                        $grades = collect($allgrades)
                                          ->where('subjid',$subjid)
                                          ->where('levelid',$item->levelid)
                                          ->where('sectionid',$item->id)
                                          ->values();

                        foreach($grades as $gradeitem){

                              if($gradeitem->submitted == 1){
                                    if($gradeitem->status == 1 || $gradeitem->status == 0){
                                          if($gradeitem->coorapp != null){
                                                $gradeitem->stattext = 'COOR APPROVED';
                                          }else{
                                                $gradeitem->stattext = 'SUBMITTED';
                                          }
                                    }else if($gradeitem->status == 4){
                                          $gradeitem->stattext = 'POSTED';
                                    }else if($gradeitem->status == 2){
                                          $gradeitem->stattext = 'APPROVED';
                                    }else if($gradeitem->status == 3){
                                          $gradeitem->stattext = 'PENDING';
                                    }
                              }else{
                                    if($gradeitem->status == 3){
                                          $gradeitem->stattext = 'PENDING';
                                    }else{
                                          $gradeitem->stattext = 'NOT SUBMITTED';
                                    }
                              }
                        }

                        $search = $item->sectionname.' G'.str_replace('GRADE ','',$item->levelname).' '.$subjitem->subjdesc.' '.$subjitem->subjcode.' '.$teacherinfo.' '.$teacherid;

                        array_push( $data, (object)[
                              'sectionname'=>$item->sectionname,
                              'levelname'=>$item->levelname,
                              'subjdesc'=>$subjitem->subjdesc,
                              'subjcode'=>$subjitem->subjcode,
                              'teacher'=> $teacherinfo,
                              'tid'=>$teacherid,
                              'subjid'=>$subjitem->subjid,
                              'search'=>$search,
                              'plotsort'=>$subjitem->plotsort,
                              'grades'=>$grades,
                              'sectionid'=>$item->id,
                              'levelid'=>$item->levelid
                        ]);

                  }
                  

            }

            return $data;

      }

}
