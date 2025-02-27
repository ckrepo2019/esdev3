<?php

namespace App\Models\Grading;
use DB;
use PDF;
use App\Models\Grading\GradingSystem;
use App\Models\Grading\GradeCalculation;
use App\Models\Grading\GradeStatus;
use App\Models\Subjects\Subjects;
use Illuminate\Database\Eloquent\Model;


class SeniorHigh extends Model
{

      public static function acadprog(){

            return 5;

      }


      public static function section_count($teacherid = null){

            $activesy = DB::table('sy')->where('isactive',1)->first();
            $activeSem = DB::table('semester')->where('isactive',1)->first();

            if($teacherid != null && $teacherid != null){

                  $teacherid = $teacherid;

            }else{

                  $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;

            }

            $section = DB::table('sh_classsched')
                              ->where('sh_classsched.deleted',0)
                              ->where('sh_classsched.teacherid',$teacherid)
                              ->where('sh_classsched.syid',$activesy->id)
                              ->where('sh_classsched.semid',$activeSem->id)
                              ->select('sh_classsched.sectionid')
                              ->distinct('sectionid')
                              ->count();
                           
            $block = DB::table('sh_blocksched')
                              ->where('sh_blocksched.deleted',0)
                              ->where('sh_blocksched.teacherid',$teacherid)
                              ->join('sh_sectionblockassignment',function($join){
                                    $join->on('sh_blocksched.blockid','=','sh_sectionblockassignment.blockid');
                                    $join->where('sh_sectionblockassignment.deleted',0);
                              })
                              ->join('sh_subjects',function($join){
                                    $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                                    $join->where('sh_subjects.deleted',0);
                                    $join->where('sh_subjects.type',2);
                              })
                              ->where('sh_blocksched.syid',$activesy->id)
                              ->where('sh_blocksched.semid',$activeSem->id)
                              ->select('sh_sectionblockassignment.sectionid')
                              ->distinct('sectionid')
                              ->count();

            

            return $section + $block;

      }


      public static function get_sections($teacherid = null, $syid = null, $semid = null){

            if($teacherid != null && $teacherid != null){
                  $teacherid = $teacherid;
            }else{
                  $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
            }

         

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }

            if($semid == null){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;
            }

           
          
            $subjects = DB::table('sh_classsched')
                              ->where('sh_classsched.teacherid',$teacherid)
                              ->where('sh_classsched.deleted',0)
                              ->join('sh_subjects',function($join){
                                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                                    $join->where('sh_subjects.deleted',0);
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_classsched.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->where('sh_classsched.syid',$syid)
                              ->where('sh_classsched.semid',$semid)
                              ->select('sectionname','subjcode','sectionid as id','subjid','sections.levelid as levelid')
                              ->get();

            

            $blocksched = DB::table('sh_blocksched')
                              ->where('sh_blocksched.teacherid',$teacherid)
                              ->where('sh_blocksched.deleted',0)
                              ->join('sh_subjects',function($join){
                                    $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                                    $join->where('sh_subjects.deleted',0);
                              })
                              ->join('sh_sectionblockassignment',function($join){
                                    $join->on('sh_blocksched.blockid','=','sh_sectionblockassignment.blockid');
                                    $join->where('sh_sectionblockassignment.deleted',0);
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_sectionblockassignment.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->where('sh_blocksched.syid',$syid)
                              ->where('sh_blocksched.semid',$semid)
                              ->select('sectionname','subjcode','sectionid as id','subjid','sections.levelid as levelid')
                              ->get();

            $allsubjects = array();

            foreach($blocksched as $item){
                  
                  array_push($allsubjects,$item);
                  
            }  
            
             foreach($subjects as $item){
                  
                  array_push($allsubjects,$item);
                  
            }  

            return $allsubjects;

      }


      public static function get_sections_subject_all($syid = null, $semid = null){

            if($semid == null){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;
            }
            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }

            // $activesy = DB::table('sy')->where('isactive',1)->first();
            // $activeSem = DB::table('semester')->where('isactive',1)->first();

            $subjects = DB::table('sh_classsched')
                              ->join('sh_subjects',function($join){
                                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                                    $join->where('sh_subjects.deleted',0);
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_classsched.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->leftJoin('grading_sytem_gradestatus',function($join){
                                    $join->on('sections.id','=','grading_sytem_gradestatus.sectionid');
                                    $join->on('sh_subjects.id','=','grading_sytem_gradestatus.subjid');
                              })
                              ->join('teacher',function($join){
                                    $join->on('sh_classsched.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                              })
                              ->where('sh_classsched.syid',$syid)
                              ->where('sh_classsched.deleted',0)
                              ->where('sh_classsched.semid',$semid)
                              ->select(
                                    'gradelevel.acadprogid',
                                    'sectionname',
                                    'subjcode',
                                    'sh_classsched.sectionid as id',
                                    'sh_classsched.subjid',
                                    'sections.levelid as levelid',
                                    'teacher.firstname',
                                    'teacher.lastname',
                                    'grading_sytem_gradestatus.id as gstatus',
                                    'grading_sytem_gradestatus.q1status',
                                    'grading_sytem_gradestatus.q2status',
                                    'grading_sytem_gradestatus.q3status',
                                    'grading_sytem_gradestatus.q4status',
                                    'gradelevel.levelname',
                                    'teacher.id as teacherid'
                                    
                                    )
                              ->get();

            $blocksched = DB::table('sh_blocksched')
                              ->where('sh_blocksched.deleted',0)
                              ->join('sh_subjects',function($join){
                                    $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                                    $join->where('sh_subjects.deleted',0);
                                    $join->where('sh_subjects.type',2);
                              })
                              ->join('sh_sectionblockassignment',function($join){
                                    $join->on('sh_blocksched.blockid','=','sh_sectionblockassignment.blockid');
                                    $join->where('sh_sectionblockassignment.deleted',0);
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_sectionblockassignment.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->leftJoin('grading_sytem_gradestatus',function($join){
                                    $join->on('sections.id','=','grading_sytem_gradestatus.sectionid');
                                    $join->on('sh_subjects.id','=','grading_sytem_gradestatus.subjid');
                                  
                              })
                              ->join('teacher',function($join){
                                    $join->on('sh_blocksched.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                              })
                              ->where('sh_blocksched.syid',$syid)
                              ->where('sh_blocksched.semid',$semid)
                              ->select(
                                    'sectionname',
                                    'subjcode',
                                    'gradelevel.acadprogid',
                                    'sh_sectionblockassignment.sectionid as id',
                                    'sh_blocksched.subjid',
                                    'sections.levelid as levelid',
                                    'teacher.firstname',
                                    'teacher.lastname',
                                    'teacher.id as teacherid',
                                    'grading_sytem_gradestatus.id as gstatus',
                                    'grading_sytem_gradestatus.q1status',
                                    'grading_sytem_gradestatus.q2status',
                                    'grading_sytem_gradestatus.q3status',
                                    'gradelevel.levelname',
                                    'grading_sytem_gradestatus.q4status'
                                    
                                    )
                              ->get();
            
          

            $allsubjects = array();

            foreach($blocksched as $item){
                  
                  array_push($allsubjects,$item);
                  
            }  
            
             foreach($subjects as $item){
                  
                  array_push($allsubjects,$item);
                  
            }  

            return $allsubjects;

      }
   
      public static function grade_info(
            $gsid = null,
            $teacherid = null,
            $syid = null,
            $semid = null
      ){
            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }

            if($semid == null){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;
            }

            $subjects = DB::table('sh_classsched')
                              ->where('sh_classsched.teacherid',$teacherid)
                              ->where('sh_classsched.deleted',0)
                              ->join('sh_subjects',function($join){
                                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                                    $join->where('sh_subjects.deleted',0);
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_classsched.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->where('sh_classsched.syid',$syid)
                              ->where('sh_classsched.semid',$semid)
                              ->select('sectionname','subjcode','sectionid as id','subjid')
                              ->get();

            $blocksched = DB::table('sh_blocksched')
                              ->where('sh_blocksched.teacherid',$teacherid)
                              ->where('sh_blocksched.deleted',0)
                              ->join('sh_subjects',function($join){
                                    $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                                    $join->where('sh_subjects.deleted',0);
                                    $join->where('sh_subjects.type',2);
                              })
                              ->join('sh_sectionblockassignment',function($join){
                                    $join->on('sh_blocksched.blockid','=','sh_sectionblockassignment.blockid');
                                    $join->where('sh_sectionblockassignment.deleted',0);
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_sectionblockassignment.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->where('sh_blocksched.syid',$syid)
                              ->where('sh_blocksched.semid',$semid)
                              ->select('sectionname','subjcode','sectionid as id','subjid')
                              ->get();

            $allsubjects = array();


            foreach($blocksched as $item){
                  
                  array_push($allsubjects,$item);
                  
            }  
            
             foreach($subjects as $item){
                  
                  array_push($allsubjects,$item);
                  
            }  

            if(count($allsubjects ) == 0){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"You are not assigned to a section."
                  ]);

                  return $data;

            }

            $sectionsDistinct = collect($allsubjects)->unique('sectionname')->sortBy('sectionname');

            return view('superadmin.pages.gradingsystem.sh_grading')
                              ->with('sections',$sectionsDistinct)
                              ->with('subjects',$allsubjects)
                              ->with('semester',$semid);
                              // ->with('grading_system',$grading_system);

      }

      public static function evaluate_student_grade(
            $gsid = null,
            $studid = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null,
            $syid = null,
            $semid = null
      ){

            $gsdetailArray = array();

            $checkStatus = GradeStatus::check_grade_status_sh($sectionid, $subjectid, $quarter,$syid,$semid);

            $subjectStatus = Subjects::get_sh_subject($subjectid);

            if(count($subjectStatus) == 0){
                  
                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Subject does not exist"
                  ]);

                  return $data;
            }

            $grading_system = self::subject_grading_system($subjectid);

            // $students = DB::table('studinfo')   
            //             ->where('studinfo.studstatus',1)
            //             ->join('sh_enrolledstud',function($join){
            //                   $join->on('studinfo.id','=','sh_enrolledstud.studid');
            //                   $join->where('sh_enrolledstud.deleted',0);
            //             })
            //             ->join('sh_strand',function($join){
            //                   $join->on('studinfo.strandid','=','sh_strand.id');
            //                   $join->where('sh_strand.deleted',0);
            //             })
            //             ->where('studinfo.sectionid',$sectionid)
            //             ->where('studinfo.deleted',0)
            //             ->whereIn('studinfo.studstatus',[1,2,3])
            //             ->orderBy('gender','desc')
            //             ->orderBy('lastname')
            //             ->select('firstname','lastname','studinfo.id','gender','trackid','studinfo.strandid','strandcode')
            //             ->get();

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }
            if($semid == null){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;
            }

            $students = DB::table('sh_enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('sh_strand',function($join){
                                    $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                    $join->where('sh_strand.deleted',0);
                              })
                              ->where('sh_enrolledstud.sectionid',$sectionid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->whereIn('sh_enrolledstud.studstatus',[1,2,3])
                              ->orderBy('gender','desc')
                              ->orderBy('lastname')
                              ->orderBy('firstname')
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.semid',$semid)
                              ->select('firstname','lastname','studinfo.id','gender','trackid','sh_enrolledstud.strandid','strandcode')
                              ->get();

            if($grading_system[0]->status == 0){

                  return $grading_system;

            }
            else{

                  $grading_system = $grading_system[0]->data;
            }

            
            if($subjectStatus[0]->type == 1){

                  $grading_system[0]->trackid = 1;

            }

            

            $subjectStrand =  DB::table('sh_subjstrand')
                                    ->where('subjid',$subjectid)
                                    ->select('strandid')
                                    ->get();

       

            if(count($students) ==0){


                  $data = array((object)[
                        'status'=>0,
                        'data'=>"No students Enrolled"
                  ]);

                  return $data;
            }
            else{

                  if($subjectStatus[0]->type == 1){

                        $acadgs = $grading_system[0];

                        $grading_system_detail = DB::table('grading_system')
                                                      ->join('grading_system_detail',function($join){
                                                            $join->on('grading_system.id','=','grading_system_detail.headerid');
                                                            $join->where('grading_system_detail.deleted',0);
                                                      })
                                                      ->where('acadprogid',self::acadprog())
                                                      ->where('grading_system.id',$grading_system[0]->id)
                                                      ->where('grading_system.deleted',0)
                                                      ->select('grading_system_detail.*')
                                                      ->get();

                        if(count($grading_system_detail) == 0){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"This grading system does not contain any detail. \n Please add details to continue.",
                              ]);

                              return $data;

                        }

                        array_push($gsdetailArray,(object)[
                              'trackid'=>1,
                              'gsdetail'=>$grading_system_detail
                        ]);

                  }else{

                        if(collect($grading_system)->where('trackid',1)->count() == 1){

                              $acadgs = collect($grading_system)->where('trackid',1)->first();

                              $grading_system_detail = DB::table('grading_system')
                                                            ->join('grading_system_detail',function($join){
                                                                  $join->on('grading_system.id','=','grading_system_detail.headerid');
                                                                  $join->where('grading_system_detail.deleted',0);
                                                            })
                                                            ->where('acadprogid',self::acadprog())
                                                            ->where('grading_system.id',$acadgs->id)
                                                            ->where('grading_system.deleted',0)
                                                            ->select('grading_system_detail.*')
                                                            ->get();

                              if(count($grading_system_detail) == 0){

                                    $data = array((object)[
                                          'status'=>0,
                                          'data'=>"Academic track grading system does not contain any detail. \n Please add details to continue.",
                                    ]);
      
                                    return $data;
      
                              }

                              array_push($gsdetailArray,(object)[
                                    'trackid'=>1,
                                    'gsdetail'=>$grading_system_detail
                              ]);

                        }else if(collect($grading_system)->where('trackid',1)->count() > 1){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"Multiple grading system is active for academic track. "
                              ]);

                              return $data;
                              
                        }else if(collect($grading_system)->where('trackid',1)->count() == 0){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"No grading setup for academic track"
                              ]);

                              return $data;
                              
                        }


                        if(collect($grading_system)->where('trackid',2)->count() == 1){


                              $tvlgs = collect($grading_system)->where('trackid',2)->first();


                              $grading_system_detail = DB::table('grading_system')
                                          ->join('grading_system_detail',function($join){
                                                $join->on('grading_system.id','=','grading_system_detail.headerid');
                                                $join->where('grading_system_detail.deleted',0);
                                          })
                                          ->where('acadprogid',self::acadprog())
                                          ->where('grading_system.id',$tvlgs->id)
                                          ->where('grading_system.deleted',0)
                                          ->select('grading_system_detail.*')
                                          ->get();

                              if(count($grading_system_detail) == 0){

                                    $data = array((object)[
                                          'status'=>0,
                                          'data'=>"TVL track grading system does not contain any detail. \n Please add details to continue.",
                                    ]);
      
                                    return $data;
      
                              }

                              array_push($gsdetailArray,(object)[
                                    'trackid'=>2,
                                    'gsdetail'=>$grading_system_detail
                              ]);

                        }else if(collect($grading_system)->where('trackid',2)->count() > 1){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"Multiple grading system is active for TVL track. "
                              ]);

                              return $data;
                              
                        }else if(collect($grading_system)->where('trackid',2)->count() == 0){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"No grading setup for TVL track"
                              ]);

                              return $data;
                              
                        }

                  }
              

            }

            if($checkStatus[0]->status == 0){

                  return $checkStatus;

            }
            else{
                  
                  $checkStatus = $checkStatus[0]->data;

            }

            if($subjectStatus[0]->type == 1){

                  foreach($students as $item){

                        $item->trackid = 1;

                  }

            }


            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }
            if($semid == null){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;

            }

            
         
            $nogscount = 0;

            $acadTrackCount = 0;
            $tvlTrackCount = 0;

            $strands = collect($students)->map(function ($students) {
                              return (object)[
                                    'strandid'=>$students->strandid,
                                    'strandcode'=>$students->strandcode,
                              ];
                        })->unique();

            foreach($students as $key=>$item){

                  $valid_student = false;

                  foreach($subjectStrand as $subject_strand_item){

                        if($subject_strand_item->strandid == $item->strandid){

                              $valid_student = true;

                        }

                  }

                  $valid_student = true;

                  if( $valid_student){

                        $gsdget = DB::table('grading_system_grades_sh')
                                    ->join('grading_system_detail',function($join){
                                          $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                          $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->join('grading_system',function($join){
                                          $join->on('grading_system_detail.headerid','=','grading_system.id');
                                          $join->where('grading_system.deleted',0);
                                    })
                                    ->where('grading_system_grades_sh.deleted',0)
                                    ->where('studid',$item->id)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('subjid',$subjectid)
                                    ->select('grading_system_grades_sh.gsdid','grading_system_grades_sh.id');

                        

                        if($item->trackid == 1 ){

                              $acadTrackCount += 1;
                              $gsdget = $gsdget->where('grading_system.id',$acadgs->id);

                        }elseif($item->trackid == 2){

                              $tvlTrackCount += 1;
                              $gsdget = $gsdget->where('grading_system.id',$tvlgs->id);

                        }
                              
                        for($x = 1; $x <= 10; $x++){

                              $gsdget =  $gsdget->addSelect('g'.$x.'q'.$quarter);

                        }

                        

                        $gsdget =  $gsdget->addSelect('psq'.$quarter);
                        $gsdget =  $gsdget->addSelect('wsq'.$quarter);
                        $gsdget =  $gsdget->addSelect('q'.$quarter.'total');
                        $gsdget =  $gsdget->addSelect('igq'.$quarter);
                        $gsdget =  $gsdget->addSelect('qgq'.$quarter);

                        $gsdget =  $gsdget->get();

                        if(count($gsdget) == 0){

                              $nogscount += 1;
                              $item->nogs = 0;
                              $item->gsdget = [];

                        }
                        else{
                              $item->nogs = 1;
                              $item->gsdget = $gsdget;
                        }

                  }else{

                        unset($students[$key]);

                  }

            }

            // return $


            $gsheaderArray = array();

            if($acadTrackCount > 0){

                  $gsheader = DB::table('grading_system_grades_sh')
                                          ->join('grading_system_detail',function($join){
                                                $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                                $join->where('grading_system_detail.deleted',0);
                                          })
                                          ->join('grading_system',function($join){
                                                $join->on('grading_system_detail.headerid','=','grading_system.id');
                                                $join->where('grading_system.deleted',0);
                                          })
                                          ->where('grading_system.id',$acadgs->id)
                                          ->where('studid',0)
                                          ->where('grading_system_grades_sh.deleted',0)
                                          ->where('syid',$syid)
                                          ->where('semid',$semid)
                                          ->where('sectionid',$sectionid)
                                          ->where('subjid',$subjectid)
                                          ->select('grading_system_grades_sh.gsdid','grading_system_grades_sh.id');
                              
                  for($x = 1; $x <= 10; $x++){

                        $gsheader =  $gsheader->addSelect('g'.$x.'q'.$quarter);

                  }

                  $gsheader =  $gsheader->addSelect('psq'.$quarter);
                  $gsheader =  $gsheader->addSelect('wsq'.$quarter);
                  $gsheader =  $gsheader->addSelect('q'.$quarter.'total');
                  $gsheader =  $gsheader->get();

                  if(count($gsheader) == 0){

                        $nogscount += 1;

                        $gsdetail = (object)[
                              'id'=>0,
                              'nogs'=>0,
                              'gsdget' => []
                        ];
                  }
                  else{

                        $gsdetail = (object)[
                              'id'=>0,
                              'nogs'=>1,
                              'studid'=>0,
                              'gsdget' => $gsheader
                        ];

                  }

                  // return collect($gsdetail);

                  array_push($gsheaderArray,(object)[
                        'trackid'=>1,
                        'gsheader'=>$gsdetail
                  ]);

            }

            if($tvlTrackCount > 0){

                  $gsheader = DB::table('grading_system_grades_sh')
                                    ->join('grading_system_detail',function($join){
                                          $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                          $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->join('grading_system',function($join){
                                          $join->on('grading_system_detail.headerid','=','grading_system.id');
                                          $join->where('grading_system.deleted',0);
                                    })
                                    ->where('grading_system.id',$tvlgs->id)
                                    ->where('grading_system_grades_sh.deleted',0)
                                    ->where('studid',0)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjectid)
                                    ->select('grading_system_grades_sh.gsdid','grading_system_grades_sh.id');
      
                        for($x = 1; $x <= 10; $x++){

                        $gsheader =  $gsheader->addSelect('g'.$x.'q'.$quarter);

                        }

                        $gsheader =  $gsheader->addSelect('psq'.$quarter);
                        $gsheader =  $gsheader->addSelect('wsq'.$quarter);
                        $gsheader =  $gsheader->addSelect('q'.$quarter.'total');
                        $gsheader =  $gsheader->get();

                        if(count($gsheader) == 0){

                              $nogscount += 1;

                              $gsdetail = (object)[
                                    'id'=>0,
                                    'nogs'=>0,
                                    'gsdget' => []
                              ];

                        }
                        else{

                              $gsdetail = (object)[
                                    'id'=>0,
                                    'nogs'=>1,
                                    'studid'=>0,
                                    'gsdget' => $gsheader
                              ];

                        }


                  array_push($gsheaderArray,(object)[
                        'trackid'=>2,
                        'gsheader'=>$gsdetail
                  ]);


            }

            // return $grading_system;

            foreach($grading_system as $key=>$item){

                  if($item->trackid == 1 && $acadTrackCount == 0){

                        unset($grading_system[$key]);

                  }
                  elseif($item->trackid == 2 && $tvlTrackCount == 0){

                        unset($grading_system[$key]);

                  }

            }

            $gradelogs = DB::table('grading_system_gradestatus_logs')
                              ->join('users',function($join){
                                    $join->on('grading_system_gradestatus_logs.createdby','=','users.id');
                                    $join->where('users.deleted',0);
                              })
                              ->where('headerid',$checkStatus->id)
                              ->select('users.name','status','createddatetime')
                              ->get();

            // return $gsheaderArray;


            return view('superadmin.pages.gradingsystem.sh_grading_table')
                        ->with('nogscount',$nogscount)
                        ->with('gsheader',$gsheaderArray)
                        ->with('gs',$grading_system)
                        ->with('gradelogs',$gradelogs)
                        ->with('quarter',$quarter)
                        ->with('strands',$strands)
                        ->with('checkStatus',$checkStatus)
                        ->with('grading_system_detail',$gsdetailArray)
                        ->with('students',$students);

      }


      

      public static function generate_student_grade(
            $gsid = null,
            $studid = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null,
            $levelid = null
      ){

       
          

            $activeSy = DB::table('sy')->where('isactive',1)->first();
            $activeSem = DB::table('semester')->where('isactive',1)->first();

            if($levelid == null){

                  $levelid = DB::table('sections')->where('id',$sectionid)->first()->levelid;

            }

            $proccesCount = 0;

            $grading_system_detail = DB::table('grading_system')
                        ->join('grading_system_detail',function($join){
                              $join->on('grading_system.id','=','grading_system_detail.headerid');
                              $join->where('grading_system_detail.deleted',0);
                        })
                        ->where('acadprogid',self::acadprog())
                        ->where('grading_system.id',$gsid)
                        ->where('grading_system.deleted',0)
                        ->select('grading_system_detail.id','grading_system_detail.headerid')
                        ->get();


            // $gsgradescount = DB::table('grading_system_grades_sh')
            //                         ->where('levelid',$levelid)
            //                         ->where('sectionid',$sectionid)
            //                         ->where('studid',$studid)
            //                         ->where('syid',$activeSy->id)
            //                         ->where('semid',$activeSem->id)
            //                         ->where('subjid',$subjectid)
            //                         ->where('grading_system_grades_sh.deleted',0)
            //                         ->update([
            //                               'deleted'=>1,
            //                               'deletedby'=>auth()->user()->id,
            //                               'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            //                         ]);
                  


            foreach($grading_system_detail as $item){

                  $gsgradescount = DB::table('grading_system_grades_sh')
                              ->where('gsdid',$item->id)
                              ->where('levelid',$levelid)
                              ->where('sectionid',$sectionid)
                              ->where('studid',$studid)
                              ->where('syid',$activeSy->id)
                              ->where('semid',$activeSem->id)
                              ->where('grading_system_grades_sh.deleted',0)
                              ->where('subjid',$subjectid)
                              ->count();

                  if($gsgradescount == 0){

                        $proccesCount +=1;

                        DB::table('grading_system_grades_sh')
                              ->insert([
                                    'studid'=>$studid,
                                    'syid'=>$activeSy->id,
                                    'semid'=>$activeSem->id,
                                    'gsdid'=>$item->id,
                                    'levelid'=>$levelid,
                                    'sectionid'=>$sectionid,
                                    'subjid'=>$subjectid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }

            }


            //removeExisting

            


           

            return $proccesCount;

      }

      public static function submit_student_grade(
            $studid = null,
            $gradid = null,
            $field  = null,
            $value = 0
      ){

            $gradeDetail = DB::table('grading_system_grades_sh')
                                    ->where('studid',$studid)
                                    ->where('grading_system_grades_sh.deleted',0)
                                    ->where('id',$gradid);

            $forHPS = $gradeDetail->select('syid','sectionid','subjid','gsdid')->first();

            $hps = DB::table('grading_system_grades_sh')
                              ->where('studid',0)
                              ->where('grading_system_grades_sh.deleted',0)
                              ->where('syid',$forHPS->syid)
                              ->where('sectionid',$forHPS->sectionid)
                              ->where('gsdid',$forHPS->gsdid)
                              ->where('subjid',$forHPS->subjid);

            $gradeDetail->update([
                        $field=>$value,
                        'updatedby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);


            $studentgsd =   DB::table('grading_system_grades_sh')
                              ->where('grading_system_grades_sh.deleted',0)
                              ->where('studid',$studid)
                              ->where('id',$gradid);

            if(strpos($field , 'q1')){

            for($x = 1; $x <= 10; $x++){

                  $studentgsd =  $studentgsd->addSelect('g'.$x.'q1');
                  $hps =  $hps->addSelect('g'.$x.'q1');

            }

            $quarter = 1;

            }
            elseif(strpos($field , 'q2')){

            for($x = 1; $x <= 10; $x++){

                  $studentgsd =  $studentgsd->addSelect('g'.$x.'q2');
                  $hps =  $hps->addSelect('g'.$x.'q2');

            }

            $quarter = 2;

            }
            elseif(strpos($field , 'q3')){

            for($x = 1; $x <= 10; $x++){

                  $studentgsd =  $studentgsd->addSelect('g'.$x.'q3');
                  $hps =  $hps->addSelect('g'.$x.'q3');

            }

            $quarter = 3;



            }

            elseif(strpos($field , 'q4')){

            for($x = 1; $x <= 10; $x++){

                  $studentgsd =  $studentgsd->addSelect('g'.$x.'q4');
                  $hps =  $hps->addSelect('g'.$x.'q4');

            }

            $quarter = 4;


            }

            $qtotal = collect(  $studentgsd->first())->sum();

            $gsdid = $studentgsd->select('gsdid')->first();

            $gsdetail = DB::table('grading_system_detail')     
                              ->where('deleted',0)
                              ->where('id',$gsdid->gsdid)
                              ->select('value')
                              ->first();

            $hpssum = collect($hps->first())->sum();

            if($studid != 0){

            if($hpssum == 0){

                  $ps = 0;
                  $ws = 0;

            }
            else{

                  $ps = ( $qtotal /  $hpssum ) * 100;
                  $ws = $ps * ( $gsdetail->value / 100 );

            }

            }else{

                  $ps = 0;
                  $ws = 0;
                  $ig = 0;
            }

            DB::table('grading_system_grades_sh')
                  ->where('studid',$studid)
                  ->where('id',$gradid)
                  ->update([
                        'q'.$quarter.'total'=>$qtotal,
                        'psq'.$quarter=>$ps,
                        'wsq'.$quarter=>$ws,
                        'updatedby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

            if($studid != 0){

            $ig = self::calcig(
                  $studid,
                  $forHPS->syid,
                  $forHPS->subjid,
                  $forHPS->sectionid,
                  $quarter
            );
            

            if($studid != null && $forHPS->syid != null  && $forHPS->sectionid != null && $forHPS->subjid != null ){

                  DB::table('grading_system_grades_sh')
                              ->where('studid',$studid)
                              ->where('syid',$forHPS->syid)
                              ->where('sectionid',$forHPS->sectionid)
                              ->where('subjid',$forHPS->subjid)
                              ->update([
                                    'igq'.$quarter=>$ig,
                                    'updatedby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                        
                  try{

                        $ig = number_format($ig , 2);

                        $qg = GradeCalculation::grade_transmutation($ig);

                        DB::table('grading_system_grades_sh')
                              ->where('studid',$studid)
                              ->where('syid',$forHPS->syid)
                              ->where('sectionid',$forHPS->sectionid)
                              ->where('subjid',$forHPS->subjid)
                              ->update([
                                    'qgq'.$quarter=>$qg,
                                    'updatedby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);


                  }
                  catch(\Exception $e){

                        DB::table('grading_system_grades_sh')
                              ->where('studid',$studid)
                              ->where('syid',$forHPS->syid)
                              ->where('sectionid',$forHPS->sectionid)
                              ->where('subjid',$forHPS->subjid)
                              ->update([
                                    'qgq'.$quarter=>0,
                                    'updatedby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        return 0;

                  }

                 
               
            }


            }

            return 1;

      }


      public static function calcig($student = null, $syid = null, $subject = null, $section = null, $quarter = null){

            $studgd = DB::table('grading_system_grades_sh')
                                    ->where('studid',$student)
                                    ->where('syid',$syid)
                                    ->where('subjid',$subject)
                                    ->where('sectionid',$section)
                                    ->where('deleted',0)
                                    ->select('wsq'.$quarter)
                                    ->sum('wsq'.$quarter);

            return  $studgd;
            

      }


      public static function subject_grading_system($subject = null){

            $grading_system = DB::table('sh_subjects')
                        ->join('grading_system_subjassignment',function($join){
                              $join->on('sh_subjects.id','=','grading_system_subjassignment.subjid');
                              $join->where('grading_system_subjassignment.deleted',0);
                        })
                        ->join('grading_system',function($join){
                              $join->on('grading_system_subjassignment.gsid','=','grading_system.id');
                              $join->where('grading_system.deleted',0);
                              $join->where('grading_system.acadprogid',self::acadprog());
                        })
                        ->where('inSF9',1)
                        ->where('sh_subjects.id',$subject)
                        ->select('grading_system.*','sh_subjects.type')
                        ->get();

            if(count($grading_system ) == 1 && $grading_system[0]->id != null){

                  $gsdetail =  DB::table('grading_system_detail')
                                    ->where('headerid',$grading_system[0]->id)
                                    ->where('deleted',0)
                                    ->count();

                  if($gsdetail == 0){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"This grading system does not contain any detail. \n Please add details to continue.",
                        ]);

                        return $data;

                  }

            }
            else if(count($grading_system ) == 1 && $grading_system[0]->id == null){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"This subject is not yet assigned to a grading system",
                  ]);

                  return $data;

            }
            else if(count($grading_system) == 0){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"No available grading for this subject."
                  ]);

                  return $data;

            }
            
            return    $data =  array((object)[
                              'status'=>1,
                              'data'=> $grading_system
                        ]);
                        
      }

      public static function view_sf9(
            $gsid = null,
            $studid = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null,
            $track = null,
            $syid = null,
            $semid = null
      ){

            // $activeSy = DB::table('sy')->where('isactive',1)->first();
            // $activeSem = DB::table('semester')->where('isactive',1)->first();

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }
            if($semid == null){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;
            }

            $teacherid = DB::table('sh_classsched')
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where('sectionid',$sectionid)
                        ->where('subjid',$subjectid)
                        ->where('sh_classsched.deleted','0')
                        ->select('teacherid')
                        ->first();

            if(!isset($teacherid->teacherid)){

                  $getSectionBlock = DB::table('sh_sectionblockassignment')  
                                          ->where('sectionid',$sectionid)
                                          ->where('sh_sectionblockassignment.deleted','0')
                                          ->where('syid',$syid)
                                          ->where('semid',$semid)
                                          ->select('blockid')
                                          ->first();

                  if(!isset($getSectionBlock->blockid)){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"Something went wrong"
                        ]);
      
                  }

                  $teacherid = DB::table('sh_blocksched')
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('subjid',$subjectid)
                                    ->where('blockid',$getSectionBlock->blockid)
                                    ->where('sh_blocksched.deleted','0')
                                    ->select('teacherid')
                                    ->first();

            }

            $teacher = DB::table('teacher')->where('id',$teacherid->teacherid)->select('firstname','lastname','userid')->first();

            if($teacher->userid != auth()->user()->id){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Unable to access"
                  ]);

                  return $data;
            }

            $gsdetailArray = array();

            $checkStatus = GradeStatus::check_grade_status_sh($sectionid, $subjectid, $quarter, $syid, $semid);
            $subjectStatus = Subjects::get_sh_subject($subjectid);

            if(count($subjectStatus) == 0){
                  
                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Subject does not exist"
                  ]);

                  return $data;
            }

            $grading_system = self::subject_grading_system($subjectid);

            
            $students = DB::table('sh_enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('sh_strand',function($join){
                                    $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                    $join->where('sh_strand.deleted',0);
                              })
                              ->where('sh_enrolledstud.sectionid',$sectionid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->whereIn('sh_enrolledstud.studstatus',[1,2,3])
                              ->orderBy('gender','desc')
                              ->orderBy('lastname')
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.semid',$semid)
                              ->select('firstname','lastname','studinfo.id','gender','trackid','sh_enrolledstud.strandid','strandcode','sh_enrolledstud.levelid')
                              ->get();
           
           

            if($grading_system[0]->status == 0){

                  return $grading_system;

            }
            else{

                  if(count($subjectStatus) == 0){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"Grade status is not yet generated"
                        ]);
      
                        return $data;

                  }

                  if($subjectStatus[0]->type == 1){

                        $grading_system = collect($grading_system[0]->data)->first();

                  }
                  else{

                        $grading_system = collect($grading_system[0]->data)->where('trackid',$track)->first();

                  }

                  
            }


           
            
            
            if(count($students) ==0){


                  $data = array((object)[
                        'status'=>0,
                        'data'=>"No students Enrolled"
                  ]);

                  return $data;
            }
            else{

                  $grading_system_detail = DB::table('grading_system')
                                                ->join('grading_system_detail',function($join){
                                                      $join->on('grading_system.id','=','grading_system_detail.headerid');
                                                      $join->where('grading_system_detail.deleted',0);
                                                })
                                                ->where('acadprogid',self::acadprog())
                                                ->where('grading_system.id',$grading_system->id)
                                                ->where('grading_system.deleted',0)
                                                ->select('grading_system_detail.*')
                                                ->get();

                  if(count($grading_system_detail) == 0){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"This grading system does not contain any detail. \n Please add details to continue.",
                        ]);

                        return $data;

                  }

            }

            $nogscount = 0;

            $acadTrackCount = 0;
            $tvlTrackCount = 0;

            $gradelevel = null;
            
            foreach($students as $item){

                  if($gradelevel == null){

                        $gradelevel = $item->levelid;
                  }


                  $gsdget = DB::table('grading_system_grades_sh')
                              ->join('grading_system_detail',function($join){
                                    $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                              ->join('grading_system',function($join){
                                    $join->on('grading_system_detail.headerid','=','grading_system.id');
                                    $join->where('grading_system.deleted',0);
                              })
                              ->where('grading_system_grades_sh.deleted',0)
                              ->where('studid',$item->id)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->where('subjid',$subjectid)
                              ->select('grading_system_grades_sh.gsdid','grading_system_grades_sh.id');

                  for($x = 1; $x <= 10; $x++){

                        $gsdget =  $gsdget->addSelect('g'.$x.'q'.$quarter);

                  }

                  

                  $gsdget =  $gsdget->addSelect('psq'.$quarter);
                  $gsdget =  $gsdget->addSelect('wsq'.$quarter);
                  $gsdget =  $gsdget->addSelect('q'.$quarter.'total');
                  $gsdget =  $gsdget->addSelect('igq'.$quarter);
                  $gsdget =  $gsdget->addSelect('qgq'.$quarter);

                  $gsdget =  $gsdget->get();

                  if(count($gsdget) == 0){

                        $nogscount += 1;
                        $item->nogs = 0;
                        $item->gsdget = [];

                  }
                  else{
                        $item->nogs = 1;
                        $item->gsdget = $gsdget;
                  }

            }

            $gsheader = DB::table('grading_system_grades_sh')
                                    ->join('grading_system_detail',function($join){
                                          $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                          $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->join('grading_system',function($join){
                                          $join->on('grading_system_detail.headerid','=','grading_system.id');
                                          $join->where('grading_system.deleted',0);
                                    })
                                    ->where('grading_system.id',$grading_system->id)
                                    ->where('studid',0)
                                    ->where('grading_system_grades_sh.deleted',0)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjectid)
                                    ->select('grading_system_grades_sh.gsdid','grading_system_grades_sh.id');
                        
            for($x = 1; $x <= 10; $x++){

                  $gsheader =  $gsheader->addSelect('g'.$x.'q'.$quarter);

            }

            $gsheader =  $gsheader->addSelect('psq'.$quarter);
            $gsheader =  $gsheader->addSelect('wsq'.$quarter);
            $gsheader =  $gsheader->addSelect('q'.$quarter.'total');
            $gsheader =  $gsheader->get();

            if(count($gsheader) == 0){

                  $nogscount += 1;

                  $gsdetail = (object)[
                        'id'=>0,
                        'nogs'=>0,
                        'gsdget' => []
                  ];
            }
            else{

                  $gsdetail = (object)[
                        'id'=>0,
                        'nogs'=>1,
                        'studid'=>0,
                        'gsdget' => $gsheader
                  ];

            }

            $gsheader = $gsdetail;


            $schoolinfo = DB::table('schoolinfo')->get();
            $schoolyear = DB::table('sy')->where('isactive',1)->get();

            $section = DB::table('sections')
                              ->where('sections.id',$sectionid)
                              ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','gradelevel.id');
                                    $join->where('gradelevel.deleted','0');
                              })
                              ->select('sectionname','levelname')
                              ->where('sections.deleted',0)
                              ->first();

          
          
            $students = collect($students)->where('trackid',$track);

            $signatory = array();

            array_push($signatory,(object)[
                  'title'=>"Subject Teacher",
                  'name'=>$teacher->firstname.' '.$teacher->lastname,
                  'description'=>'Submitted by:'
            ]);
      
            $temp_signatory = DB::table('signatory')->where('form','ecl')->get();

            foreach($temp_signatory as $item){
                  array_push($signatory,$item);
            }

            $pdf = PDF::loadView('teacher.grading.sf9forms.shsf9',compact('nogscount','schoolinfo','schoolyear','gsheader','quarter','checkStatus','gradelevel','grading_system_detail','students','teacher','section','subjectStatus','signatory'))->setPaper('legal', 'landscape');
            $pdf->getDomPDF()->set_option("enable_php", true);
    
            return $pdf->stream();

      }


      public static function reload_student_grade(
            $student = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null
      ){


            $gsdetailArray = array();

            $checkStatus = GradeStatus::check_grade_status_sh($sectionid, $subjectid, $quarter);
            $subjectStatus = Subjects::get_sh_subject($subjectid);

            if(count($subjectStatus) == 0){
                  
                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Subject does not exist"
                  ]);

                  return $data;
            }

            $grading_system = self::subject_grading_system($subjectid);
            $activeSy = DB::table('sy')->where('isactive',1)->first();
            $activeSem = DB::table('semester')->where('isactive',1)->first();


            if($student == 0){

                  $tvlcount =  DB::table('studinfo')   
                                    ->where('studinfo.studstatus',1)
                                    ->join('sh_enrolledstud',function($join) use($activeSem, $activeSy){
                                          $join->on('studinfo.id','=','sh_enrolledstud.studid');
                                          $join->where('sh_enrolledstud.deleted',0);
                                          $join->where('sh_enrolledstud.syid',$activeSy->id);
                                          $join->where('sh_enrolledstud.semid',$activeSem->id);
                                    })
                                    ->join('sh_strand',function($join){
                                          $join->on('studinfo.strandid','=','sh_strand.id');
                                          $join->where('sh_strand.deleted',0);
                                    })
                                    ->where('studinfo.sectionid',$sectionid)
                                    ->where('studinfo.deleted',0)
                                    ->where('trackid',2)
                                    ->count();

                  $acadcount =  DB::table('studinfo')   
                                    ->where('studinfo.studstatus',1)
                                    ->join('sh_enrolledstud',function($join) use($activeSem, $activeSy){
                                          $join->on('studinfo.id','=','sh_enrolledstud.studid');
                                          $join->where('sh_enrolledstud.deleted',0);
                                          $join->where('sh_enrolledstud.syid',$activeSy->id);
                                          $join->where('sh_enrolledstud.semid',$activeSem->id);
                                    })
                                    ->join('sh_strand',function($join){
                                          $join->on('studinfo.strandid','=','sh_strand.id');
                                          $join->where('sh_strand.deleted',0);
                                    })
                                    ->where('studinfo.sectionid',$sectionid)
                                    ->where('studinfo.deleted',0)
                                    ->where('trackid',1)
                                    ->count();


                  $track_count = array((object)
                        [
                              'trackid'=>'1',
                              'count'=>$acadcount
                        ],
                        [
                              'trackid'=>'2',
                              'count'=>$tvlcount
                        ],
                        
                  );


                  foreach($grading_system as $item){


                        if(collect($track_count)->where('trackid',$item->data[0]->trackid)->count() > 0){

                              $check_subeject_gs = DB::table('grading_system_grades_sh')
                                                ->where('grading_system_grades_sh.sectionid',$sectionid)
                                                ->where('grading_system_grades_sh.subjid',$subjectid)
                                                ->where('grading_system_grades_sh.deleted',0)
                                                ->where('studid',$student)
                                                ->where('syid',$activeSy->id)
                                                ->where('semid',$activeSem->id)
                                                ->join('grading_system_detail',function($join){
                                                      $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                                      $join->where('grading_system_detail.deleted',0);
                                                })
                                                ->join('grading_system',function($join) use($item){
                                                      $join->on('grading_system_detail.headerid','=','grading_system.id');
                                                      $join->where('grading_system.deleted',0);
                                                      $join->where('grading_system.trackid',$item->data[0]->trackid);
                                                })
                                                ->select('grading_system_detail.headerid')
                                                ->first();


                              $grading_system_detail = DB::table('grading_system')
                                          ->join('grading_system_detail',function($join){
                                                $join->on('grading_system.id','=','grading_system_detail.headerid');
                                                $join->where('grading_system_detail.deleted',0);
                                          })
                                          ->where('acadprogid',5)
                                          ->where('grading_system.id',$item->data[0]->id)
                                          ->where('grading_system.deleted',0)
                                          ->select('grading_system_detail.*')
                                          ->get();

                              if(isset($check_subeject_gs->headerid)){

                                    if($item->data[0]->id != $check_subeject_gs->headerid){

                                          $check_subeject_gs = DB::table('grading_system_grades_sh')
                                                                  ->where('grading_system_grades_sh.sectionid',$sectionid)
                                                                  ->where('grading_system_grades_sh.subjid',$subjectid)
                                                                  ->where('grading_system_grades_sh.deleted',0)
                                                                  ->where('studid',$student)
                                                                  ->where('syid',$activeSy->id)
                                                                  ->where('semid',$activeSem->id)
                                                                  ->join('grading_system_detail',function($join){
                                                                        $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                                                        $join->where('grading_system_detail.deleted',0);
                                                                  })
                                                                  ->join('grading_system',function($join) use($item){
                                                                        $join->on('grading_system_detail.headerid','=','grading_system.id');
                                                                        $join->where('grading_system.deleted',0);
                                                                        $join->where('grading_system.trackid',$item->data[0]->trackid);
                                                                  })
                                                                  ->select('grading_system_detail.headerid')
                                                                  ->get();


                                          foreach($get_studentgrades_detail as $item){

                                                DB::table('grading_system_grades_sh')
                                                            ->where('id',$item->id)
                                                            ->update([
                                                                  'deleted'=>1,
                                                            ]);
                                                      
                                                unset($item->id);
                                                unset($item->gsdid);
                  
                                                $gsdid = collect($grading_system_detail)->where('sf9val',$item->sf9val)->first();
                                                $item->gsdid =  $gsdid->id;
                  
                                                DB::table('grading_system_grades_sh')
                                                            ->insert([
                                                                  'syid'=>$item->syid,
                                                                  'studid'=>$item->studid,
                                                                  'sectionid'=>$item->sectionid,
                                                                  'subjid'=>$item->subjid,
                                                                  'levelid'=>$item->levelid,
                                                                  'gsdid'=>$item->gsdid,
                                                                  'g1q1'=>$item->g1q1,
                                                                  'g2q1'=>$item->g2q1,
                                                                  'g3q1'=>$item->g3q1,
                                                                  'g4q1'=>$item->g4q1,
                                                                  'g5q1'=>$item->g5q1,
                                                                  'g6q1'=>$item->g6q1,
                                                                  'g7q1'=>$item->g7q1,
                                                                  'g8q1'=>$item->g8q1,
                                                                  'g9q1'=>$item->g9q1,
                                                                  'g10q1'=>$item->g10q1,
                                                                  'g1q2'=>$item->g1q2,
                                                                  'g2q2'=>$item->g2q2,
                                                                  'g3q2'=>$item->g3q2,
                                                                  'g4q2'=>$item->g4q2,
                                                                  'g5q2'=>$item->g5q2,
                                                                  'g6q2'=>$item->g6q2,
                                                                  'g7q2'=>$item->g7q2,
                                                                  'g8q2'=>$item->g8q2,
                                                                  'g9q2'=>$item->g9q2,
                                                                  'g10q2'=>$item->g10q2,
                                                                  'q1total'=>$item->q1total,
                                                                  'q2total'=>$item->q2total,
                                                                  'createdby'=>auth()->user()->id,
                                                                  'semid'=>$item->semid,
                                                                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                                            ]);
                  
                  
                                          }
                  
                  
                                    }

                              }

                        }else{


                              $check_subject_gs = DB::table('grading_system_grades_sh')
                                                            ->where('grading_system_grades_sh.sectionid',$sectionid)
                                                            ->where('grading_system_grades_sh.subjid',$subjectid)
                                                            ->where('grading_system_grades_sh.deleted',0)
                                                            ->where('studid',$student)
                                                            ->where('semid',$activeSem->id)
                                                            ->where('syid',$activeSy->id)
                                                            ->join('grading_system_detail',function($join){
                                                                  $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                                                  $join->where('grading_system_detail.deleted',0);
                                                            })
                                                            ->join('grading_system',function($join) use($item){
                                                                  $join->on('grading_system_detail.headerid','=','grading_system.id');
                                                                  $join->where('grading_system.deleted',0);
                                                                  $join->where('grading_system.trackid',$item->data[0]->trackid);
                                                            })
                                                            ->select('grading_system_detail.id')
                                                            ->get();

                              foreach($check_subject_gs as $item){

                                    DB::table('grading_system_grades_sh')
                                                ->where('id',$item->id)
                                                ->update([
                                                      'deleted'=>1,
                                                ]);

                              }

                              return 2;

                        }

                     
                  }

            }else{

                  $trackid =  DB::table('studinfo')   
                                    ->where('studinfo.studstatus',1)
                                    ->join('sh_enrolledstud',function($join) use($activeSem, $activeSy){
                                          $join->on('studinfo.id','=','sh_enrolledstud.studid');
                                          $join->where('sh_enrolledstud.deleted',0);
                                          $join->where('sh_enrolledstud.syid',$activeSy->id);
                                          $join->where('sh_enrolledstud.semid',$activeSem->id);
                                    })
                                    ->join('sh_strand',function($join){
                                          $join->on('studinfo.strandid','=','sh_strand.id');
                                          $join->where('sh_strand.deleted',0);
                                    })
                                    ->where('studinfo.id',$student)
                                    ->where('studinfo.deleted',0)
                                    ->select('trackid')
                                    ->first();


                                   

                  if( $grading_system[0]->data[0]->trackid == null){

                        $grading_system[0]->data[0]->trackid = $trackid->trackid;

                  }

                  foreach( $grading_system[0]->data as $item){

                        if($item->trackid == $trackid->trackid){

                              $student_grading_system = $item;

                        }

                  }

                  $grading_system = array($student_grading_system);

                  $check_subeject_gs = DB::table('grading_system_grades_sh')
                                          ->where('grading_system_grades_sh.sectionid',$sectionid)
                                          ->where('grading_system_grades_sh.subjid',$subjectid)
                                          ->where('grading_system_grades_sh.deleted',0)
                                          ->where('studid',$student)
                                          ->where('syid',$activeSy->id)
                                          ->where('semid',$activeSem->id)
                                          ->join('grading_system_detail',function($join){
                                                $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                                $join->where('grading_system_detail.deleted',0);
                                          })
                                          ->join('grading_system',function($join) use($item){
                                                $join->on('grading_system_detail.headerid','=','grading_system.id');
                                                $join->where('grading_system.deleted',0);
                                          })
                                          ->select('grading_system_detail.headerid')
                                          ->first();

                  $grading_system_detail = DB::table('grading_system')
                                          ->join('grading_system_detail',function($join){
                                                $join->on('grading_system.id','=','grading_system_detail.headerid');
                                                $join->where('grading_system_detail.deleted',0);
                                          })
                                          ->where('acadprogid',5)
                                          ->where('grading_system.id',$grading_system[0]->id)
                                          ->where('grading_system.deleted',0)
                                          ->select('grading_system_detail.*')
                                          ->get();


              

                  if(isset($check_subeject_gs->headerid)){

                        if($grading_system[0]->id != $check_subeject_gs->headerid){

                              $get_studentgrades_detail = DB::table('grading_system_grades_sh')
                                                      ->where('grading_system_grades_sh.sectionid',$sectionid)
                                                      ->where('grading_system_grades_sh.subjid',$subjectid)
                                                      ->where('grading_system_grades_sh.deleted',0)
                                                      ->where('studid',$student)
                                                      ->where('syid',$activeSy->id)
                                                      ->where('semid',$activeSem->id)
                                                      ->join('grading_system_detail',function($join){
                                                            $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                                            $join->where('grading_system_detail.deleted',0);
                                                      })
                                                      ->select('grading_system_grades_sh.*','grading_system_detail.sf9val')
                                                      ->get();


                              foreach($get_studentgrades_detail as $item){

                                    DB::table('grading_system_grades_sh')
                                                ->where('id',$item->id)
                                                ->update([
                                                      'deleted'=>1,
                                                ]);
                                          
                                    unset($item->id);
                                    unset($item->gsdid);
      
                                    $gsdid = collect($grading_system_detail)->where('sf9val',$item->sf9val)->first();
                                    $item->gsdid =  $gsdid->id;
      
                                    DB::table('grading_system_grades_sh')
                                                ->insert([
                                                      'syid'=>$item->syid,
                                                      'studid'=>$item->studid,
                                                      'sectionid'=>$item->sectionid,
                                                      'subjid'=>$item->subjid,
                                                      'levelid'=>$item->levelid,
                                                      'gsdid'=>$item->gsdid,
                                                      'g1q1'=>$item->g1q1,
                                                      'g2q1'=>$item->g2q1,
                                                      'g3q1'=>$item->g3q1,
                                                      'g4q1'=>$item->g4q1,
                                                      'g5q1'=>$item->g5q1,
                                                      'g6q1'=>$item->g6q1,
                                                      'g7q1'=>$item->g7q1,
                                                      'g8q1'=>$item->g8q1,
                                                      'g9q1'=>$item->g9q1,
                                                      'g10q1'=>$item->g10q1,
                                                      'g1q2'=>$item->g1q2,
                                                      'g2q2'=>$item->g2q2,
                                                      'g3q2'=>$item->g3q2,
                                                      'g4q2'=>$item->g4q2,
                                                      'g5q2'=>$item->g5q2,
                                                      'g6q2'=>$item->g6q2,
                                                      'g7q2'=>$item->g7q2,
                                                      'g8q2'=>$item->g8q2,
                                                      'g9q2'=>$item->g9q2,
                                                      'g10q2'=>$item->g10q2,
                                                      'semid'=>$item->semid,
                                                      'q1total'=>$item->q1total,
                                                      'q2total'=>$item->q2total,
                                                      'createdby'=>auth()->user()->id,
                                                      'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                                ]);
      
      
                              }

                              return self::reCalculateGrade( 
                                    $student,
                                    $sectionid,
                                    $subjectid,
                                    $quarter,
                                    $trackid
                              );
      
      
                        }else{

                              return self::reCalculateGrade( 
                                    $student,
                                    $sectionid,
                                    $subjectid,
                                    $quarter,
                                    $trackid->trackid
                              );

                        }

                       

                  }

            }


            return 2;

      }

      public static function reCalculateGrade($studid = null, $sectionid = null, $subjid = null, $quarter = null, $trackid = null ){

            $grading_system = self::subject_grading_system($subjid);

            $grading_system = $grading_system[0]->data;

            if($grading_system[0]->trackid == null){

                  $grading_system[0]->trackid = $trackid;

            }
          
            $grading_system = collect($grading_system)->where('trackid',$trackid)->first();

           

            $gradeDetail = DB::table('grading_system_grades_sh')
                              ->where('studid',$studid)
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjid)
                              ->where('syid',self::activeSy())
                              ->where('grading_system_grades_sh.deleted',0)
                              ->get();

            $gradeDetailHPS = DB::table('grading_system_grades_sh')
                                    ->where('studid',0)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjid)
                                    ->where('syid',self::activeSy())
                                    ->where('grading_system_grades_sh.deleted',0)
                                    ->select('q1total','q2total','gsdid')
                                    ->get();

            $grading_system_detail = DB::table('grading_system_detail')
                                                ->where('grading_system_detail.headerid',$grading_system->id)
                                                ->where('grading_system_detail.deleted',0)
                                                ->select('grading_system_detail.*')
                                                ->get();

            $totalig = 0;

            foreach($gradeDetail as $item){
                             
                  $field = 'g1q'.$quarter;
                  self::submit_student_grade($studid , $item->id , $field , $item->$field);

            }


            $gradeDetail = DB::table('grading_system_grades_sh')
                                    ->where('studid',$studid)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjid)
                                    ->where('syid',self::activeSy())
                                    ->where('grading_system_grades_sh.deleted',0)
                                    ->get();

            foreach($gradeDetail as $item){

                  $field = 'q'.$quarter.'total';
                  $hps = collect($gradeDetailHPS)->where('gsdid',$item->gsdid)->first()->$field;
                  $gsp = collect($grading_system_detail)->where('id',$item->gsdid)->first()->value;

                  if($hps == 0){

                        $ps = 0;
                        $ws = 0;

                  }else{

                        $ps = ( $item->$field / $hps) * 100;
                        $ws =  $ps * ( $gsp / 100 );

                  }

                  $totalig += number_format($ws,2);

                  DB::table('grading_system_grades_sh')
                        ->where('id',$item->id)
                        ->update([
                              'wsq'.$quarter=>  $ws ,
                              'psq'.$quarter=>  $ps ,
                        ]);


            }

            foreach($gradeDetail as $item){

                  DB::table('grading_system_grades_sh')
                              ->where('id',$item->id)
                              ->update([
                                    'igq'.$quarter =>  number_format($totalig,2) ,
                              ]);
            
            }

            $totalig = number_format($totalig , 2);

            $qg = GradeCalculation::grade_transmutation($totalig);

            foreach($gradeDetail as $item){

                  DB::table('grading_system_grades_sh')
                              ->where('id',$item->id)
                              ->update([
                                    'qgq'.$quarter =>  $qg ,
                              ]);
            
            }


            return count($gradeDetail);


      }

      public static function activeSy(){

            return  DB::table('sy')->where('isactive',1)->first()->id;

      }


      public static function evaluate_student_grade_seniorhigh_pending(
            $gsid = null,
            $studid = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null
      ){

            $gsdetailArray = array();

            $checkStatus = GradeStatus::check_grade_status_sh($sectionid, $subjectid, $quarter);
            $subjectStatus = Subjects::get_sh_subject($subjectid);

            if(count($subjectStatus) == 0){
                  
                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Subject does not exist"
                  ]);

                  return $data;
            }

            $grading_system = self::subject_grading_system($subjectid);

            $students = DB::table('studinfo')   
                        ->where('studinfo.studstatus',1)
                        ->join('sh_enrolledstud',function($join){
                              $join->on('studinfo.id','=','sh_enrolledstud.studid');
                              $join->where('sh_enrolledstud.deleted',0);
                        })
                        ->join('sh_strand',function($join){
                              $join->on('studinfo.strandid','=','sh_strand.id');
                              $join->where('sh_strand.deleted',0);
                        })
                        ->where('studid',$studid)
                        ->where('studinfo.sectionid',$sectionid)
                        ->where('studinfo.deleted',0)
                        ->whereIn('studinfo.studstatus',[1,2,3])
                        ->orderBy('gender','desc')
                        ->orderBy('lastname')
                        ->select('firstname','lastname','studinfo.id','gender','trackid','studinfo.strandid','strandcode')
                        ->get();

            if($grading_system[0]->status == 0){

                  return $grading_system;

            }
            else{

                  $grading_system = $grading_system[0]->data;
            }

            
            if($subjectStatus[0]->type == 1){

                  $grading_system[0]->trackid = 1;

            }

            

            $subjectStrand =  DB::table('sh_subjstrand')
                                    ->where('subjid',$subjectid)
                                    ->select('strandid')
                                    ->get();

       

            if(count($students) ==0){


                  $data = array((object)[
                        'status'=>0,
                        'data'=>"No students Enrolled"
                  ]);

                  return $data;
            }
            else{

                  if($subjectStatus[0]->type == 1){

                        $acadgs = $grading_system[0];

                        $grading_system_detail = DB::table('grading_system')
                                                      ->join('grading_system_detail',function($join){
                                                            $join->on('grading_system.id','=','grading_system_detail.headerid');
                                                            $join->where('grading_system_detail.deleted',0);
                                                      })
                                                      ->where('acadprogid',self::acadprog())
                                                      ->where('grading_system.id',$grading_system[0]->id)
                                                      ->where('grading_system.deleted',0)
                                                      ->select('grading_system_detail.*')
                                                      ->get();

                        if(count($grading_system_detail) == 0){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"This grading system does not contain any detail. \n Please add details to continue."
                              ]);

                              return $data;

                        }

                        array_push($gsdetailArray,(object)[
                              'trackid'=>1,
                              'gsdetail'=>$grading_system_detail
                        ]);

                  }else{

                        if(collect($grading_system)->where('trackid',1)->count() == 1){

                              $acadgs = collect($grading_system)->where('trackid',1)->first();

                              $grading_system_detail = DB::table('grading_system')
                                                            ->join('grading_system_detail',function($join){
                                                                  $join->on('grading_system.id','=','grading_system_detail.headerid');
                                                                  $join->where('grading_system_detail.deleted',0);
                                                            })
                                                            ->where('acadprogid',self::acadprog())
                                                            ->where('grading_system.id',$acadgs->id)
                                                            ->where('grading_system.deleted',0)
                                                            ->select('grading_system_detail.*')
                                                            ->get();

                              if(count($grading_system_detail) == 0){

                                    $data = array((object)[
                                          'status'=>0,
                                          'data'=>"Academic track grading system does not contain any detail. \n Please add details to continue."
                                    ]);
      
                                    return $data;
      
                              }

                              array_push($gsdetailArray,(object)[
                                    'trackid'=>1,
                                    'gsdetail'=>$grading_system_detail
                              ]);

                        }else if(collect($grading_system)->where('trackid',1)->count() > 1){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"Multiple grading system is active for academic track. "
                              ]);

                              return $data;
                              
                        }else if(collect($grading_system)->where('trackid',1)->count() == 0){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"No grading setup for academic track"
                              ]);

                              return $data;
                              
                        }


                        if(collect($grading_system)->where('trackid',2)->count() == 1){


                              $tvlgs = collect($grading_system)->where('trackid',2)->first();


                              $grading_system_detail = DB::table('grading_system')
                                          ->join('grading_system_detail',function($join){
                                                $join->on('grading_system.id','=','grading_system_detail.headerid');
                                                $join->where('grading_system_detail.deleted',0);
                                          })
                                          ->where('acadprogid',self::acadprog())
                                          ->where('grading_system.id',$tvlgs->id)
                                          ->where('grading_system.deleted',0)
                                          ->select('grading_system_detail.*')
                                          ->get();

                              if(count($grading_system_detail) == 0){

                                    $data = array((object)[
                                          'status'=>0,
                                          'data'=>"TVL track grading system does not contain any detail. \n Please add details to continue.",
                                    ]);
      
                                    return $data;
      
                              }

                              array_push($gsdetailArray,(object)[
                                    'trackid'=>2,
                                    'gsdetail'=>$grading_system_detail
                              ]);

                        }else if(collect($grading_system)->where('trackid',2)->count() > 1){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"Multiple grading system is active for TVL track. "
                              ]);

                              return $data;
                              
                        }else if(collect($grading_system)->where('trackid',2)->count() == 0){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"No grading setup for TVL track"
                              ]);

                              return $data;
                              
                        }

                  }
              

            }

            if($checkStatus[0]->status == 0){

                  return $checkStatus;

            }
            else{
                  
                  $checkStatus = $checkStatus[0]->data;

            }

            if($subjectStatus[0]->type == 1){

                  foreach($students as $item){

                        $item->trackid = 1;

                  }

            }


            $activeSy = DB::table('sy')->where('isactive',1)->first();
            $activeSem = DB::table('semester')->where('isactive',1)->first();

            $nogscount = 0;

            $acadTrackCount = 0;
            $tvlTrackCount = 0;

            $strands = collect($students)->map(function ($students) {
                              return (object)[
                                    'strandid'=>$students->strandid,
                                    'strandcode'=>$students->strandcode
                              ];
                        })->unique();

            foreach($students as $key=>$item){

                  $valid_student = false;

                  foreach($subjectStrand as $subject_strand_item){

                        if($subject_strand_item->strandid == $item->strandid){

                              $valid_student = true;

                        }

                  }

                  $valid_student = true;

                  if( $valid_student){

                        $gsdget = DB::table('grading_system_grades_sh')
                                    ->join('grading_system_detail',function($join){
                                          $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                          $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->join('grading_system',function($join){
                                          $join->on('grading_system_detail.headerid','=','grading_system.id');
                                          $join->where('grading_system.deleted',0);
                                    })
                                    ->where('grading_system_grades_sh.deleted',0)
                                    ->where('studid',$item->id)
                                    ->where('syid',$activeSy->id)
                                    ->where('semid',$activeSem->id)
                                    ->where('subjid',$subjectid)
                                    ->select('grading_system_grades_sh.gsdid','grading_system_grades_sh.id');

                        

                        if($item->trackid == 1 ){

                              $acadTrackCount += 1;
                              $gsdget = $gsdget->where('grading_system.id',$acadgs->id);

                        }elseif($item->trackid == 2){

                              $tvlTrackCount += 1;
                              $gsdget = $gsdget->where('grading_system.id',$tvlgs->id);

                        }
                              
                        for($x = 1; $x <= 10; $x++){

                              $gsdget =  $gsdget->addSelect('g'.$x.'q'.$quarter);

                        }

                        

                        $gsdget =  $gsdget->addSelect('psq'.$quarter);
                        $gsdget =  $gsdget->addSelect('wsq'.$quarter);
                        $gsdget =  $gsdget->addSelect('q'.$quarter.'total');
                        $gsdget =  $gsdget->addSelect('igq'.$quarter);
                        $gsdget =  $gsdget->addSelect('qgq'.$quarter);

                        $gsdget =  $gsdget->get();

                        if(count($gsdget) == 0){

                              $nogscount += 1;
                              $item->nogs = 0;
                              $item->gsdget = [];

                        }
                        else{
                              $item->nogs = 1;
                              $item->gsdget = $gsdget;
                        }

                  }else{

                        unset($students[$key]);

                  }

            }

            // return $


            $gsheaderArray = array();

            if($acadTrackCount > 0){

                  $gsheader = DB::table('grading_system_grades_sh')
                                          ->join('grading_system_detail',function($join){
                                                $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                                $join->where('grading_system_detail.deleted',0);
                                          })
                                          ->join('grading_system',function($join){
                                                $join->on('grading_system_detail.headerid','=','grading_system.id');
                                                $join->where('grading_system.deleted',0);
                                          })
                                          ->where('grading_system.id',$acadgs->id)
                                          ->where('studid',0)
                                          ->where('grading_system_grades_sh.deleted',0)
                                          ->where('syid',$activeSy->id)
                                          ->where('sectionid',$sectionid)
                                          ->where('subjid',$subjectid)
                                          ->select('grading_system_grades_sh.gsdid','grading_system_grades_sh.id');
                              
                  for($x = 1; $x <= 10; $x++){

                        $gsheader =  $gsheader->addSelect('g'.$x.'q'.$quarter);

                  }

                  $gsheader =  $gsheader->addSelect('psq'.$quarter);
                  $gsheader =  $gsheader->addSelect('wsq'.$quarter);
                  $gsheader =  $gsheader->addSelect('q'.$quarter.'total');
                  $gsheader =  $gsheader->get();

                  if(count($gsheader) == 0){

                        $nogscount += 1;

                        $gsdetail = (object)[
                              'id'=>0,
                              'nogs'=>0,
                              'gsdget' => []
                        ];
                  }
                  else{

                        $gsdetail = (object)[
                              'id'=>0,
                              'nogs'=>1,
                              'studid'=>0,
                              'gsdget' => $gsheader
                        ];

                  }

                  // return collect($gsdetail);

                  array_push($gsheaderArray,(object)[
                        'trackid'=>1,
                        'gsheader'=>$gsdetail
                  ]);

            }

            if($tvlTrackCount > 0){

                  $gsheader = DB::table('grading_system_grades_sh')
                                    ->join('grading_system_detail',function($join){
                                          $join->on('grading_system_grades_sh.gsdid','=','grading_system_detail.id');
                                          $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->join('grading_system',function($join){
                                          $join->on('grading_system_detail.headerid','=','grading_system.id');
                                          $join->where('grading_system.deleted',0);
                                    })
                                    ->where('grading_system.id',$tvlgs->id)
                                    ->where('grading_system_grades_sh.deleted',0)
                                    ->where('studid',0)
                                    ->where('syid',$activeSy->id)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjectid)
                                    ->select('grading_system_grades_sh.gsdid','grading_system_grades_sh.id');
      
                        for($x = 1; $x <= 10; $x++){

                        $gsheader =  $gsheader->addSelect('g'.$x.'q'.$quarter);

                        }

                        $gsheader =  $gsheader->addSelect('psq'.$quarter);
                        $gsheader =  $gsheader->addSelect('wsq'.$quarter);
                        $gsheader =  $gsheader->addSelect('q'.$quarter.'total');
                        $gsheader =  $gsheader->get();

                        if(count($gsheader) == 0){

                              $nogscount += 1;

                              $gsdetail = (object)[
                                    'id'=>0,
                                    'nogs'=>0,
                                    'gsdget' => []
                              ];

                        }
                        else{

                              $gsdetail = (object)[
                                    'id'=>0,
                                    'nogs'=>1,
                                    'studid'=>0,
                                    'gsdget' => $gsheader
                              ];

                        }


                  array_push($gsheaderArray,(object)[
                        'trackid'=>2,
                        'gsheader'=>$gsdetail
                  ]);


            }

            // return $grading_system;

            foreach($grading_system as $key=>$item){

                  if($item->trackid == 1 && $acadTrackCount == 0){

                        unset($grading_system[$key]);

                  }
                  elseif($item->trackid == 2 && $tvlTrackCount == 0){

                        unset($grading_system[$key]);

                  }

            }

            $gradelogs = DB::table('grading_system_gradestatus_logs')
                              ->join('users',function($join){
                                    $join->on('grading_system_gradestatus_logs.createdby','=','users.id');
                                    $join->where('users.deleted',0);
                              })
                              ->where('headerid',$checkStatus->id)
                              ->select('users.name','status','createddatetime')
                              ->get();

            $field = 'q'.$quarter.'status';
            $checkStatus->$field = 4;


            return view('teacher.pendinggrades.v2.shpending')
                        ->with('nogscount',$nogscount)
                        ->with('gsheader',$gsheaderArray)
                        ->with('gs',$grading_system)
                        ->with('gradelogs',$gradelogs)
                        ->with('quarter',$quarter)
                        ->with('strands',$strands)
                        ->with('checkStatus',$checkStatus)
                        ->with('grading_system_detail',$gsdetailArray)
                        ->with('students',$students);

      }


      public static function checkActualGrades($sectionid = null, $subjid = null, $quarter = null, $trackid = null, $syid = null, $semid = null ){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }
            if($semid == null){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;

            }

            $students = DB::table('sh_enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('sh_strand',function($join){
                                    $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                    $join->where('sh_strand.deleted',0);
                              })
                              ->where('sh_enrolledstud.sectionid',$sectionid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->whereIn('sh_enrolledstud.studstatus',[1,2,3])
                              ->orderBy('gender','desc')
                              ->orderBy('lastname')
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.semid',$semid)
                              ->select('firstname','lastname','studinfo.id','gender','trackid','sh_enrolledstud.strandid','strandcode')
                              ->get();

            $grading_system = self::subject_grading_system($subjid);

            $grading_system = $grading_system[0]->data;

         

            if($grading_system[0]->trackid == null){

                  $grading_system[0]->trackid = $trackid;

            }

           
          
            $grading_system = collect($grading_system)->where('trackid',$trackid)->first();


            $array_conflict = array();

            foreach($students as $stud_item){

                  

                  $gradeDetail = DB::table('grading_system_grades_sh')
                                    ->where('studid',$stud_item->id)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjid)
                                    ->where('syid',self::activeSy())
                                    ->where('grading_system_grades_sh.deleted',0)
                                    ->get();

                  $gradeDetailHPS = DB::table('grading_system_grades_sh')
                                          ->where('studid',0)
                                          ->where('sectionid',$sectionid)
                                          ->where('subjid',$subjid)
                                          ->where('syid',self::activeSy())
                                          ->where('grading_system_grades_sh.deleted',0)
                                          ->select('q1total','q2total','gsdid')
                                          ->get();

                  $grading_system_detail = DB::table('grading_system_detail')
                                                      ->where('grading_system_detail.headerid',$grading_system->id)
                                                      ->where('grading_system_detail.deleted',0)
                                                      ->select('grading_system_detail.*')
                                                      ->get();
                                                      

                  $totalig = 0;

                  // return $gradeDetail;

                  foreach($gradeDetail as $item){

                        $field = 'q'.$quarter.'total';
                        $hps = collect($gradeDetailHPS)->where('gsdid',$item->gsdid)->first()->$field;
                        $gsp = collect($grading_system_detail)->where('id',$item->gsdid)->first()->value;

                        $check_total = 0;
                        for($x = 1; $x <= 10; $x++){
                              $column_field = 'g'.$x.'q'.$quarter;
                              $check_total += $item->$column_field;

                        }

                        if($check_total != $item->$field){
                              array_push($array_conflict,(object)[
                                    'gradeid'=>$item->id,
                                    'studid'=>$stud_item->id,
                                    'field'=>$field,
                                    'gval'=>$item->$field,
                                    'aval'=> $check_total
                              ]);
                        }

                        if($hps == 0){

                              $ps = 0;
                              $ws = 0;

                        }else{

                              $ps = number_format( ( $check_total / $hps) * 100 , 2);
                              $ws =  number_format( $ps * ( $gsp / 100 ), 2);

                        }

                        $psfield = 'psq'.$quarter;
                        
                        if($ps != $item->$psfield){
                              array_push($array_conflict,(object)[
                                    'gradeid'=>$item->id,
                                    'studid'=>$stud_item->id,
                                    'field'=>$psfield,
                                    'gval'=>$item->$psfield,
                                    'aval'=> $ps
                              ]);
                        }

                        $wsfield = 'wsq'.$quarter;

                        if($ws != $item->$wsfield){
                              array_push($array_conflict,(object)[
                                    'gradeid'=>$item->id,
                                    'studid'=>$stud_item->id,
                                    'field'=>$wsfield,
                                    'gval'=>$item->$wsfield,
                                    'aval'=> $ws
                              ]);
                        }

                        $totalig += $ws;
                      

                  }

                  $igfield = 'igq'.$quarter;
                  $totalig = number_format($totalig,2);

                  if($totalig != $gradeDetail[0]->$igfield){
                        array_push($array_conflict,(object)[
                              'gradeid'=>$gradeDetail[0]->id,
                              'studid'=>$stud_item->id,
                              'field'=>$igfield,
                              'gval'=>$gradeDetail[0]->$igfield,
                              'aval'=> $totalig
                        ]);
                  }
                 
                  $qg = GradeCalculation::grade_transmutation($totalig);

                  $qgfield = 'qgq'.$quarter;
                  if($qg != $gradeDetail[0]->$qgfield){
                        array_push($array_conflict,(object)[
                              'gradeid'=>$gradeDetail[0]->id,
                              'studid'=>$stud_item->id,
                              'field'=>$qgfield,
                              'gval'=>$gradeDetail[0]->$qgfield,
                              'aval'=> $qg
                        ]);
                  }

            }

            return $array_conflict;

      }

     


}
