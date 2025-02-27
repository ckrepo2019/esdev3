<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class StudentGradeEvaluation extends \App\Http\Controllers\Controller
{
      public static function schoolinfo(){
            return DB::table('schoolinfo')->first()->abbreviation;
      }

      public static function grades_detail(Request $request){

            $studid = $request->get('studid');
            $levelid = $request->get('levelid');
            $syid = $request->get('syid');
            
            $grades_detail = DB::table('gradesdetail')
                              ->where('studid',$studid)
                              ->join('grades',function($join){
                                    $join->on('gradesdetail.headerid','=','grades.id');
                                    $join->where('grades.deleted',0);
                              });

            if($levelid == 14 || $levelid == 15){
                  $grades_detail = $grades_detail->join('sh_subjects',function($join){
                        $join->on('grades.subjid','=','sh_subjects.id');
                        $join->where('sh_subjects.deleted',0);
                  })
                  ->select('subjtitle as subjdesc','sh_subj_sortid as subjsor');
            }else{
                  $grades_detail = $grades_detail->join('subjects',function($join){
                        $join->on('grades.subjid','=','subjects.id');
                        $join->where('subjects.deleted',0);
                  })
                  ->select('subjdesc','subj_sortid as subjsor');
            }

            $grades_detail = $grades_detail->join('teacher',function($join){
                  $join->on('grades.createdby','=','teacher.id');
                  $join->where('subjects.deleted',0);
            });

            $grades_detail = $grades_detail->addSelect(
                                                'quarter',
                                                'gradesdetail.*',
                                                'grades.semid'
                                          )
                                          ->orderBy('subjsor')
                                          ->get();

            return $grades_detail;

      }


      public static function students(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('syid');
            return \App\Models\SuperAdmin\SuperAdminData::enrollment_record($syid, $semid);
            
      }

      public static function sf9(Request $request){

            $studid = $request->get('studid');
            $syid = $request->get('syid');

            $studentInfo = \App\Models\Principal\SPP_EnrolledStudent::getStudent(null,null,$studid,null);
            $studentInfo = $studentInfo[0]->count == 0 ? SPP_EnrolledStudent::getStudent(null,null,$studid,null,5) : $studentInfo;
            $student = $studentInfo[0]->data[0];
            $acad = $studentInfo[0]->data[0]->acadprogid;
            $strand = $studentInfo[0]->data[0]->strandid;

          
            $subjects = \App\Models\Principal\SPP_Subject::getSubject(null,null,null,$student->ensectid,null,null,null,null,'sf9',$syid)[0]->data;

            //mcs
            if($acad != 5){

                  $temp_subject = array();

                  foreach($subjects as $item){
                        array_push($temp_subject,$item);
                  }

                  array_push($temp_subject, (object)[
                        'id'=>'TLE1',
                        'subjdesc'=>'COMPUTER / HELE',
                        "inMAPEH"=> 0,
                        "teacherid"=> 14,
                        "inSF9"=> 1,
                        "mapeh"=>0,
                        "inTLE"=>0,
                        "semid"=>1,
                        "subj_per"=> 0,
                        "subj_sortid"=> "3T0"
                  ]);

                  array_push($temp_subject, (object)[
                        'id'=>'M1',
                        'subjdesc'=>'MAPEH',
                        "inMAPEH"=> 0,
                        "teacherid"=> 14,
                        "inSF9"=> 1,
                        "mapeh"=>0,
                        "inTLE"=>0,
                        "semid"=>1,
                        "subj_per"=> 0,
                        "subj_sortid"=> "2M0"
                  ]);

                  $subjects = $temp_subject;

            }

      

            $studgrades = \App\Models\Grades\GradesData::student_grades_detail($syid,null,$student->ensectid,null,$studid, $student->levelid,$strand,null,$subjects);
            $studgrades =  \App\Models\Grades\GradesData::get_finalrating($studgrades,$acad);
            $finalgrade =  \App\Models\Grades\GradesData::general_average($studgrades);
            $finalgrade =  \App\Models\Grades\GradesData::get_finalrating($finalgrade,$acad);


            return $studgrades;

      }

      public static function subjsetup(Request $request){

            $studid = $request->get('studid');
            $syid = $request->get('syid');

            $studentInfo = \App\Models\Principal\SPP_EnrolledStudent::getStudent(null,null,$studid,null);
            $studentInfo = $studentInfo[0]->count == 0 ? App\Models\Principal\SPP_EnrolledStudent::getStudent(null,null,$studid,null,5) : $studentInfo;
            $student = $studentInfo[0]->data[0];
            $acad = $studentInfo[0]->data[0]->acadprogid;
            $strand = $studentInfo[0]->data[0]->strandid;

            $subjects_setup = \App\Models\Principal\SPP_Subject::getSubject(null,null,null,$student->ensectid,null,null,null,null,'sf9',$syid)[0]->data;

            foreach($subjects_setup as $item){
                  $setup = Db::table('gradessetup')
                                    ->where('subjid',$item->id)
                                    ->where('levelid',$student->levelid)
                                    ->get();

                  $item->setup = $setup;

            }

            return $subjects_setup;

            

      }

      public static function sf9_subjects($levelid = null, $syid = null , $isforsp = true){

            $schoolinfo = self::schoolinfo();
            if($syid == 2 && $schoolinfo == 'SMA'){
                  $subjects = DB::table('subjects')
                        ->where('subjects.deleted',0)
                        ->join('gradessetup',function($join) use($levelid){
                              $join->on('subjects.id','=','gradessetup.subjid');
                              $join->where('gradessetup.deleted',0);
                              $join->where('gradessetup.levelid',$levelid);
                        })
                        ->where('syid',$syid)
                        ->where('inSF9',1)
                        ->select(
                              'subjects.id as subjid',
                              'subjects.id',
                              'subjdesc',
                              'subjcode',
                              'first',
                              'second',
                              'third',
                              'fourth',
                              'subjCom',
                              'subj_per',
                              'subj_sortid as sortid',
                              'isVisible',
                              'isCon',
                              'isSP',
                              'inSF9'
                        )
                        ->distinct('subjid')
                        ->orderBy('subj_sortid')
                        ->get();


                  foreach($subjects as $item){
                        if($item->subjCom != null){
                              $item->mapeh = 1;
                              $item->inTLE = 1;
                              $item->inMAPEH = 1;
                        }else{
                              $item->mapeh = 0;
                              $item->inTLE = 0;
                              $item->inMAPEH = 0;
                        }
                  }

                  return collect($subjects)->unique('subjid')->values();

            }else{
                  $subjects = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null, null, $levelid, null, $syid , null, null, array(), $isforsp);
      
                  $final_subject = array();
      
                  foreach($subjects as $item){
                      if($item->inSF9 == 1){
                          $item->first = 1;
                          $item->second = 1;;
                          $item->third = 1;
                          $item->fourth = 1;
                            
                          $item->id = $item->subjid;
          
                          if($item->subjCom != null){
                              $item->mapeh = 1;
                              $item->inTLE = 1;
                              $item->inMAPEH = 1;
                          }else{
                              $item->mapeh = 0;
                              $item->inTLE = 0;
                              $item->inMAPEH = 0;
                          }
                            
                          array_push($final_subject, $item);
                      }else{
                        $item->first = 1;
                        $item->second = 1;;
                        $item->third = 1;
                        $item->fourth = 1;
                          
                        $item->id = $item->subjid;
        
                        if($item->subjCom != null){
                            $item->mapeh = 1;
                            $item->inTLE = 1;
                            $item->inMAPEH = 1;
                        }else{
                            $item->mapeh = 0;
                            $item->inTLE = 0;
                            $item->inMAPEH = 0;
                        }
                      
                        array_push($final_subject, $item);
                      }
                  }
      
                  return $final_subject;
            }
          

      }

      public static function sf9_subjects_sh($levelid = null, $strand = null, $semid = null,$syid = null){

            $schoolinfo = self::schoolinfo();

            if($syid == 2 && $schoolinfo == 'SMA'){

                  $core = DB::table('sh_subjects')
                        ->where('sh_subjects.deleted',0)
                        ->join('gradessetup',function($join) use($levelid){
                              $join->on('sh_subjects.id','=','gradessetup.subjid');
                              $join->where('gradessetup.deleted',0);
                              $join->where('gradessetup.levelid',$levelid);
                        })
                        ->leftJoin('sh_subjstrand',function($join){
                              $join->on('sh_subjects.id','=','sh_subjstrand.subjid');
                              $join->where('sh_subjstrand.deleted',0);
                        });

                  if($semid != null){
                        $core =  $core->where('sh_subjects.semid',$semid);
                  }

                  $core =  $core->where('type',1)
                              ->where('inSF9',1)
                              ->select(
                                    'sh_subjects.id as subjid',
                                    'sh_subjects.id',
                                    'subjtitle as subjdesc',
                                    'subjcode',
                                    'first',
                                    'second',
                                    'third',
                                    'fourth',
                                    'type',
                                    'sh_subj_sortid as sortid',
                                    'sh_subjstrand.strandid',
                                    'sh_subjects.semid',
                                    'sh_isSP as isSP',
                                    'sh_isCon as isCon',
                                    'sh_subjCom as subjCom',
                                    'sh_isVisible as isVisible',
                                    'inSF9'
                              )
                              ->distinct('subjdesc')
                              ->get();


            

                  foreach($core as $key=>$item){
                        if($item->strandid != null){
                              unset($core[$key]);
                        }
                  }     
            
                  $strand_subj = DB::table('sh_subjects')
                              ->where('sh_subjects.deleted',0)
                              ->join('gradessetup',function($join) use($levelid){
                                    $join->on('sh_subjects.id','=','gradessetup.subjid');
                                    $join->where('gradessetup.deleted',0);
                                    $join->where('gradessetup.levelid',$levelid);
                              })
                              ->join('sh_subjstrand',function($join) use($strand){
                                    $join->on('sh_subjects.id','=','sh_subjstrand.subjid');
                                    $join->where('sh_subjstrand.deleted',0);
                                    $join->where('sh_subjstrand.strandid',$strand);
                              });

                  if($semid != null){
                        $strand_subj =  $strand_subj->where('sh_subjects.semid',$semid);
                  }

                  $strand_subj =  $strand_subj->where('inSF9',1)
                              ->select(
                                    'sh_subjects.id as subjid',
                                    'sh_subjects.id',
                                    'subjtitle as subjdesc',
                                    'subjcode',
                                    'first',
                                    'second',
                                    'third',
                                    'type',
                                    'fourth',
                                    'sh_subj_sortid as sortid',
                                    'sh_subjstrand.strandid',
                                    'sh_subjects.semid',
                                    'sh_isSP as isSP',
                                    'sh_isCon as isCon',
                                    'sh_isVisible as isVisible',
                                    'sh_subjCom as subjCom',
                                    'inSF9'
                              )
                              ->distinct('subjdesc')
                              ->get();

                  $subjects = [];
                  foreach($core as $item){
                        array_push($subjects,$item);
                  }    
      
                  foreach($strand_subj as $item){
                        array_push($subjects,$item);
                  }    
            }else{
                  $subjects = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null, null, $levelid, null, $syid, $semid, $strand);
      

                  $final_subject = array();
      
                  foreach($subjects as $item){
                        $item->first = 1;
                        $item->second = 1;
                        $item->third = 1;
                        $item->fourth = 1;
                        $item->id = $item->subjid;
                        $item->isVisible = 1;
                        array_push($final_subject, $item);
                  }
      
                  return $subjects;
            }
           
            
           

            return $subjects;

      }


      public static function sf9_grades_request(Request $request){

            $levelid = $request->get('levelid');
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $strand = $request->get('strandid');
            $semid = $request->get('semid');
            $sectionid = $request->get('section');
            
            $grading_version = \App\Models\Grading\GradingSystem::checkVersion();
         
            if($grading_version->version == 'v2' && $syid == 2){
                  return self::sf9_grades_gv2($levelid,$studid,$syid,$strand,$semid,$sectionid);
            }else{
               
                  return self::sf9_grades($levelid,$studid,$syid,$strand,$semid,$sectionid);
            }

            
      }

      public static function sf9_grades($levelid = null, $studid = null, $syid = null, $strand = null, $semid = null, $sectionid = null, $sf9 = false , $quarter = null){
          
            // $grades = DB::table('gradesdetail')
            //                   ->where('studid',$studid)
            //                   ->where('syid',$syid)
            //                   ->join('grades',function($join) use ($semid,$sectionid,$sf9){
            //                         $join->on('gradesdetail.headerid','=','grades.id');
            //                         $join->where('grades.deleted',0);
            //                         $join->where('grades.sectionid',$sectionid);
            //                         if(auth()->user()->type == 7 || auth()->user()->type == 9){
            //                              $join->where('gradesdetail.gdstatus',4);
            //                         }else{
            //                               if($sf9){
            //                                     $join->where(function($join_query){
            //                                           $join_query->where('gradesdetail.gdstatus',2);
            //                                           $join_query->orWhere('gradesdetail.gdstatus',4);
            //                                     });
            //                               }else{
            //                                     $join->where(function($join_query){
            //                                           $join_query->orWhere('gradesdetail.gdstatus',3);
            //                                           $join_query->orWhere('gradesdetail.gdstatus',2);
            //                                           $join_query->orWhere('gradesdetail.gdstatus',4);
            //                                           $join_query->orWhere('gradesdetail.gdstatus',1);
            //                                     });
            //                               }
            //                         }
            //                         if($semid != null){
            //                             $join->where('grades.semid',$semid);
            //                         }
            //                   })
            //                   ->select(
            //                         'subjid',
            //                         'qg',
            //                         'quarter',
            //                         'gradesdetail.gdstatus as status',
            //                         'gradesdetail.id'
            //                   )
            //                   ->get();




            $grades = DB::table('grades')
                        ->select(
                              'subjid',
                              'qg',
                              'quarter',
                              'gradesdetail.gdstatus as status',
                              'gradesdetail.id',
                              'semid'
                        )
                        ->where('syid',$syid)
                        ->where('sectionid',$sectionid)
                        ->where('grades.deleted',0)
                        ->join('gradesdetail',function($join) use($studid,$sf9){
                              $join->on('grades.id','=','gradesdetail.headerid');
                              $join->where('gradesdetail.studid',$studid);
                              if(auth()->user()->type == 7 || auth()->user()->type == 9){
                                    $join->where('gradesdetail.gdstatus',4);
                              }else{
                                    if($sf9){
                                          $join->where(function($join_query){
                                                $join_query->whereIn('gradesdetail.gdstatus',[2,4]);
                                          });
                                    }else{
                                          $join->where(function($join_query){
                                                $join_query->whereIn('gradesdetail.gdstatus',[1,2,3,4]);
                                          });
                                    }
                              }
                        });

            if($semid != null){
                  $grades = $grades->where('grades.semid',$semid);
            }
            if($quarter != null){
                  $grades = $grades->where('grades.quarter',$quarter);
            }
            
            $grades = $grades->get();

            $temp_grades = array();


            $student_special_class = DB::table('student_specsubj')
                                          ->where('deleted',0)
                                          ->where('levelid',$levelid)
                                          ->where('studid',$studid)
                                          ->get();
										  
			$student_exclsubj = DB::table('student_exclsubj')
                                          ->where('deleted',0)
                                          ->where('levelid',$levelid)
                                          ->where('studid',$studid)
                                          ->get();
										  
			$student_quarter = DB::table('student_quarter')
                                    ->where('syid',$syid)
                                    ->where('studid',$studid)
                                    ->first();
										  
										  
            if(isset($student_quarter->id) > 0){
                  if($levelid == 14 || $levelid == 15){
                        $check_enrollmentstatus = DB::table('sh_enrolledstud')
                                                      ->where('syid',$syid)
                                                      ->where('deleted',0)
                                                      ->where('studid',$studid)
                                                      ->whereIn('studstatus',[3,5,6])
                                                      ->count();
                  }else{
                        $check_enrollmentstatus = DB::table('enrolledstud')
                                                      ->where('syid',$syid)
                                                      ->where('deleted',0)
                                                      ->where('studid',$studid)
                                                      ->whereIn('studstatus',[3,5,6])
                                                      ->count();
                  }
                  
                  if($check_enrollmentstatus > 0){
                        $temp_grades = array();
                        foreach($grades as $item){
                              if($student_quarter->quarter1 == 1 && $item->quarter == 1){
                                    array_push($temp_grades,$item);
                              }elseif($student_quarter->quarter2 == 1 && $item->quarter == 2){
                                    array_push($temp_grades,$item);
                              }elseif($student_quarter->quarter3 == 1 && $item->quarter == 3){
                                    array_push($temp_grades,$item);
                              }elseif($student_quarter->quarter4 == 1 && $item->quarter == 4){
                                    array_push($temp_grades,$item);
                              }
                        }
      
                        $grades = $temp_grades;
                  }
            }

            if(count($student_special_class) > 0){          

                  $student_special_grades =  DB::table('grades')
                                          ->select(
												'semid',
                                                'subjid',
                                                'qg',
                                                'quarter',
                                                'gradesdetail.gdstatus as status',
                                                'gradesdetail.id'
                                          )
                                          ->where('syid',$syid)
                                          ->whereIn('levelid',collect($student_special_class)->pluck('levelid'))
                                          ->where('grades.deleted',0)
                                          ->join('gradesdetail',function($join) use($studid,$sf9){
                                                $join->on('grades.id','=','gradesdetail.headerid');
                                                $join->where('gradesdetail.studid',$studid);
                                                if(auth()->user()->type == 7 || auth()->user()->type == 9){
                                                      $join->where('gradesdetail.gdstatus',4);
                                                }else{
                                                      if($sf9){
                                                            $join->where(function($join_query){
                                                                  $join_query->whereIn('gradesdetail.gdstatus',[2,4]);
                                                            });
                                                      }else{
                                                            $join->where(function($join_query){
                                                                  $join_query->whereIn('gradesdetail.gdstatus',[1,2,3,4]);
                                                            });
                                                      }
                                                }
                                          });

                                          if($semid != null){
                                                $student_special_grades = $student_special_grades->where('grades.semid',$semid);
                                          }
                                          if($quarter != null){
                                                $student_special_grades = $student_special_grades->where('grades.quarter',$quarter);
                                          }
                                          
                                          $student_special_grades = $student_special_grades->get();

            }else{
                  $student_special_grades = array();
            }

            foreach($grades as $item){
                  if($item->qg == 0 || $item->qg == 60){
                        $item->qg = 60;
                  }
                  array_push($temp_grades,$item);
            }

            $grades = DB::table('grades_tranf')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('sectionid',$sectionid)
                              ->where('deleted',0)
                              ->select(
									'semid',
                                    'subjid',
                                    'qg',
                                    'quarter',
                                    'grades_tranf.gdstatus as status'
                              )
                              ->get();

            foreach($grades as $item){
                  $check = collect($temp_grades)->where('subjid',$item->subjid)->where('quarter',$item->quarter)->count();
                  $item->id = null;
                  if($check > 0){
                        $temp_grades = collect($temp_grades)->values()->map(function ($map_grade, $key) use($item) {
                              if ($map_grade->subjid == $item->subjid && $map_grade->quarter == $item->quarter) {
                                  $map_grade->qg = $item->qg;
                              } 
                              return $map_grade;
                        })->toArray();
                  }else{
                        array_push($temp_grades,$item);
                  }
                  
            }

            foreach($temp_grades as $item){
                  $check_sp_grades = collect($student_special_grades)
                                                      ->where('levelid',$levelid)
                                                      ->where('subjid',$item->subjid)
                                                      ->where('quarter',$item->quarter)
                                                ->first();

              if(isset($check_sp_grades->qg)){
                              $item->qg = $check_sp_grades->qg;
                        }
            }

            
            foreach($student_special_grades as $item){

                        $check_sp_grades = collect($temp_grades)
                                                      ->where('levelid',$levelid)
                                                      ->where('subjid',$item->subjid)
                                                      ->where('quarter',$item->quarter)
                                                      ->first();

              if(!isset($check_sp_grades->qg)){
                               array_push($temp_grades,$item);
                        }
            }

            $grades = $temp_grades;

            if($levelid == 14 || $levelid == 15){
                  $subjects =  self::sf9_subjects_sh($levelid,$strand,$semid,$syid);
				  
				  
				  if(collect($student_exclsubj)->count() > 0){
                        $temp_subj = array();
                        foreach($subjects as $item){
                              $check = collect($student_exclsubj)->where('subjid',$item->subjid)->count();
                             
                              if($check == 0){
                                    array_push($temp_subj,$item);
                              }
                        }
                        $subjects = $temp_subj;
                  }

                  if(collect($student_special_class)->count() > 0){
					
                        $additional_subj = DB::table('sh_subjects')
                                                ->where('id',collect($student_special_class)->pluck('subjid'))
                                                ->select(
                                                            'sh_subjects.id',
                                                            'subjtitle as subjdesc',
                                                            'subjcode',
                                                            'inSF9',
                                                            'type',
                                                            'sh_subjCom as subjCom',
                                                            'sh_subjects.sh_isVisible as isVisble'
                                                            
                                                  )
                                                ->get();
                                                
                        $subjects = collect($subjects)->toArray();
                                                
                        foreach($additional_subj as $item){
                              $item->semid = collect($student_special_class)->where('subjid',$item->id)->first()->semid;
                              $item->first = 1;
                              $item->second = 1;
                              $item->third = 1;
                              $item->fourth = 1;
                              $item->isVisible = 1;
                              $item->strandid = $strand ;
                              array_push($subjects,$item);
                        }
                  }

                  $enrollment = DB::table('sh_enrolledstud')
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->where('studid',$studid)
                                    ->whereIn('studstatus',[1,2,4])
                                    ->select(
                                          'semid'
                                    )
                                    ->get();

                  // return $enrollment;

                  $enroll_sem1 = collect($enrollment)->where('semid',1)->count() > 0 ? true : false;
                  $enroll_sem2 = collect($enrollment)->where('semid',2)->count() > 0 ? true : false;

                  foreach($grades as $item){
                        if($item->semid == 1 && !$enroll_sem1){
                              $item->qg = null;
                              $item->quarter = null;
                        }else if($item->semid == 2 && !$enroll_sem2){
                              $item->qg = null;
                              $item->quarter = null;
                        }
                  }

                  return self::generate_sh_grade($subjects,$grades,$studid,$syid,$semid);
            }else {

                  $isforsp = false;

                  $sectioninfo = DB::table('sectiondetail')
                                              ->where('syid',$syid)
                                              ->where('sectionid',$sectionid)
                                              ->where('deleted',0)
                                              ->first();
      
                  if($sectioninfo->sd_issp == 1){
                      $isforsp = true;
                  }
      

                  $subjects =  self::sf9_subjects($levelid,$syid,$isforsp);
				  
				  if(collect($student_exclsubj)->count() > 0){
                        $temp_subj = array();
                        foreach($subjects as $item){
                              $check = collect($student_exclsubj)->where('subjid',$item->subjid)->count();
                             
                              if($check == 0){
                                    array_push($temp_subj,$item);
                              }
                        }
                        $subjects = $temp_subj;
                  }


                  $schoolinfo = DB::table('schoolinfo')->select('abbreviation')->first();

                  if(collect($student_special_class)->count() > 0){
					  
                        $additional_subj = DB::table('subjects')
                                                    ->where('id',collect($student_special_class)->pluck('subjid'))
                                                    ->select(
                                                                'id',
                                                                'subjdesc',
                                                                'subjcode',
                                                                'isSP',
                                                                'isCon',
                                                                'subjCom',
                                                                'isVisible',
                                                                'inSF9',
                                                                'subj_per'
                                                                
                                                      )
                                                    ->get();
                                                    
                            $subjects = collect($subjects)->toArray();
                                                    
                            foreach($additional_subj as $item){
                                  $item->semid = collect($student_special_class)->where('subjid',$item->id)->first()->semid;
                                  $item->first = 1;
                                  $item->second = 1;
                                  $item->third = 1;
                                  $item->fourth = 1;
                                  $item->isVisible = 1;
                                  $item->strandid = $strand ;
                                  array_push($subjects,$item);
                            }
                  }
                  
                        if($schoolinfo->abbreviation == strtoupper('HCHS CP')){
                              $final_subject = array();
                              foreach($subjects as $item){
                                    if($sectionid == 4 || $sectionid == 6 || $sectionid == 10 || $sectionid == 4){
                                    array_push($final_subject, $item);
                                    }else{
                                          if(
                                                $item->id == 10 || //grade 7
                                                $item->id == 11 || //grade 7
                                                $item->id == 12 || //grade 7
                                                $item->id == 48 || //grade 7
                                                $item->id == 22 || //grade 8
                                                $item->id == 24 || //grade 8
                                                $item->id == 47 || //grade 8
                                                $item->id == 34 || //grade 9
                                                $item->id == 35 || //grade 9
                                                $item->id == 36 || //grade 9
                                                $item->id == 46    //grade 9
                                          ){
            
                                          }else{
                                                array_push($final_subject, $item);
                                          }
                                          
                                    }
                              }
                        $subjects = $final_subject;
                        
                        }
                  return self::generate_grade($subjects,$grades,$studid,$syid);
            }

      }


      public static function sf9_grades_gv2($levelid = null, $studid = null, $syid = null, $strand = null, $semid = null, $sectionid = null){
          
            $fgrades = array();
        
            $acad = DB::table('gradelevel')->where('id',$levelid)->select('acadprogid')->first()->acadprogid;
            
            if($acad == 4){
                  $temp_grades = DB::table('grading_system_grades_hs')
                        ->where('grading_system_grades_hs.syid',$syid)
                        ->where('grading_system_grades_hs.studid',$studid)
                        ->where('grading_system_grades_hs.deleted',0)
                        ->join('grading_sytem_gradestatus',function($join){
                              $join->on('grading_system_grades_hs.sectionid','=','grading_sytem_gradestatus.sectionid');
                              $join->on('grading_system_grades_hs.syid','=','grading_sytem_gradestatus.syid');
                              $join->on('grading_system_grades_hs.subjid','=','grading_sytem_gradestatus.subjid');
                              $join->where('grading_sytem_gradestatus.deleted',0);

                        })
                        ->select(
                              'qgq1',
                              'qgq2',
                              'qgq3',
                              'qgq4',
                              'grading_system_grades_hs.subjid',
                              'grading_system_grades_hs.id',
                              'q1status',
                              'q2status',
                              'q3status',
                              'q4status')
                        ->get();
            }elseif($acad == 2){

                  $temp_grades = DB::table('grading_system_grades_psper')
                        ->where('grading_system_grades_psper.syid',$syid)
                        ->where('grading_system_grades_psper.studid',$studid)
                        ->where('grading_system_grades_psper.deleted',0)
                        ->join('grading_sytem_gradestatus',function($join){
                              $join->on('grading_system_grades_psper.sectionid','=','grading_sytem_gradestatus.sectionid');
                              $join->on('grading_system_grades_psper.syid','=','grading_sytem_gradestatus.syid');
                              $join->on('grading_system_grades_psper.subjid','=','grading_sytem_gradestatus.subjid');
                              $join->where('grading_sytem_gradestatus.deleted',0);

                        })
                        ->select(
                              'qgq1',
                              'qgq2',
                              'qgq3',
                              'qgq4',
                              'grading_system_grades_psper.subjid',
                              'grading_system_grades_psper.id',
                              'q1status',
                              'q2status',
                              'q3status',
                              'q4status')
                        ->get();
            }
            elseif($acad == 3){
                  $temp_grades = DB::table('grading_system_gsgrades')
                        ->where('grading_system_gsgrades.syid',$syid)
                        ->where('grading_system_gsgrades.studid',$studid)
                        ->where('grading_system_gsgrades.deleted',0)
                        ->join('grading_sytem_gradestatus',function($join){
                              $join->on('grading_system_gsgrades.sectionid','=','grading_sytem_gradestatus.sectionid');
                              $join->on('grading_system_gsgrades.syid','=','grading_sytem_gradestatus.syid');
                              $join->on('grading_system_gsgrades.subjid','=','grading_sytem_gradestatus.subjid');
                              $join->where('grading_sytem_gradestatus.deleted',0);

                        })
                        ->select(
                              'qgq1',
                              'qgq2',
                              'qgq3',
                              'qgq4',
                              'grading_system_gsgrades.subjid',
                              'grading_system_gsgrades.id',
                              'q1status',
                              'q2status',
                              'q3status',
                              'q4status')
                        ->get();
            }
            elseif($acad == 5){
                  $temp_grades = DB::table('grading_system_grades_sh')
                        ->where('grading_system_grades_sh.syid',$syid)
                        ->where('grading_system_grades_sh.studid',$studid)
                        ->where('grading_system_grades_sh.deleted',0)
                        ->join('grading_sytem_gradestatus',function($join){
                              $join->on('grading_system_grades_sh.sectionid','=','grading_sytem_gradestatus.sectionid');
                              $join->on('grading_system_grades_sh.syid','=','grading_sytem_gradestatus.syid');
                              $join->on('grading_system_grades_sh.subjid','=','grading_sytem_gradestatus.subjid');
                              $join->where('grading_sytem_gradestatus.deleted',0);

                        })
                        ->select(
                              'grading_system_grades_sh.semid',
                              'qgq1',
                              'qgq2',
                              'grading_system_grades_sh.subjid',
                              'grading_system_grades_sh.id',
                              'q1status',
                              'q2status',
                              'q3status',
                              'q4status')
                        ->get();

                  foreach($temp_grades as $item){
                        if($item->semid == 2){
                              $item->qgq3 = $item->qgq1;
                              $item->qgq4 = $item->qgq2;
                              $item->q3status = $item->q1status;
                              $item->q4status = $item->q2status;
                        }else{
                              $item->qgq3 = null;
                              $item->qgq4 = null;
                        }
                  }
                  
            }
            
            foreach($temp_grades as $item){
                  if($item->q1status == 3){
                        $item->q1status = 4;
                  }
                  else if($item->q1status == 4){
                        $item->q1status = 3;
                  }

                  if($item->q2status == 3){
                        $item->q2status = 4;
                  }
                  else if($item->q2status == 4){
                        $item->q2status = 3;
                  }

                  if($item->q3status == 3){
                        $item->q3status = 4;
                  }
                  else if($item->q3status == 4){
                        $item->q3status = 3;
                  }

                  if($item->q4status == 3){
                        $item->q4status = 4;
                  }
                  else if($item->q4status == 4){
                        $item->q4status = 3;
                  }
                  array_push($fgrades,(object)[
                        'id'=>$item->id,
                        'subjid'=>$item->subjid,
                        'quarter'=>1,
                        'status'=>$item->q1status,
                        'qg'=>$item->q1status != null ? number_format($item->qgq1) : null,
                  ]);
                  array_push($fgrades,(object)[
                        'id'=>$item->id,
                        'subjid'=>$item->subjid,
                        'quarter'=>2,
                        'status'=>$item->q2status,
                        'qg'=>$item->q2status != null ? number_format($item->qgq2) : null,
                  ]);
                  array_push($fgrades,(object)[
                        'id'=>$item->id,
                        'subjid'=>$item->subjid,
                        'quarter'=>3,
                        'status'=>$item->q3status,
                        'qg'=>$item->q3status != null ? number_format($item->qgq3) : null,
                  ]);
                  array_push($fgrades,(object)[
                        'id'=>$item->id,
                        'subjid'=>$item->subjid,
                        'quarter'=>4,
                        'status'=>$item->q4status,
                        'qg'=>$item->q4status != null ? number_format($item->qgq4) : null,
                  ]);
            }

            $grades = $fgrades;

            if($levelid == 14 || $levelid == 15){
                  $subjects =  self::sf9_subjects_sh($levelid,$strand,$semid,$syid);
                  return self::generate_sh_grade($subjects,$grades,$studid,$syid);
            }else {
                  $subjects =  self::sf9_subjects($levelid,$syid);
                  return self::generate_grade($subjects,$grades,$studid,$syid);
            }

      }


      public static function check_award(
            $grade = null,
            $quarter = null,
            $lowest = null,
            $syid = null
      ){
            $award = '';

            $schoolinfo = DB::table('schoolinfo')->first();

            $award_setup_all = DB::table('grades_ranking_setup')
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->select(
                                    'id',
                                    'award',
                                    'gto',
                                    'gfrom'
                              )
                              ->get();

            $award_setup = collect($award_setup_all)->where('award','!=','lowest grade')->values();
            $award_setup = collect($award_setup)->where('award','!=','base grade')->values();
            $lowest_setup = collect($award_setup_all)->where('award','lowest grade')->first();
            $base_setup = collect($award_setup_all)->where('award','base grade')->first();

            foreach($award_setup as $item){
                  if(isset($base_setup->id)){
                      if($base_setup->gto == 1){
                          if( number_format($grade,3) >= number_format($item->gfrom,2) && number_format($grade,3) <= number_format($item->gto,2)){
                                $award = $item->award;
                          }
                      }else if($base_setup->gfrom == 1){
                          if( number_format($grade) >= number_format($item->gfrom,2) && number_format($grade) <= number_format($item->gto,2)){
                                $award = $item->award;
                          }
                      }
                      else{
                          if( number_format($grade,3) >= number_format($item->gfrom,2) && number_format($grade,3) <= number_format($item->gto,2)){
                                $award = $item->award;
                          }
                      }
                  } 
            }

            if(isset($lowest_setup->gto) && $quarter != null){
                  if($lowest < $lowest_setup->gto){
                        $award = '';
                  }
            }

            return $award;     

            // if($schoolinfo->abbreviation == 'zps'){
            //       if( number_format($grade,3) >= 90 && number_format($grade,3) <= 94.999){
            //             $award = 'With Honors';
            //       }
            //       else if( number_format($grade,3) >= 95 && number_format($grade,3) <= 97.999){
            //             $award = 'With High Honors';
            //       }
            //       else if( number_format($grade,3) >= 98 && number_format($grade,3) <= 100){
            //             $award = 'With Highest Honors';
            //       }
            // }
            // else if(strtoupper($schoolinfo->abbreviation) == 'HCB'){
            //       if( number_format($grade,3) >= 90 && number_format($grade,3) <= 94.999){
            //             $award = 'With Honors';
            //       }
            //       else if( number_format($grade,3) >= 95 && number_format($grade,3) <= 97.999){
            //             $award = 'With High Honors';
            //       }
            //       else if( number_format($grade,3) >= 98 && number_format($grade,3) <= 100){
            //             $award = 'With Highest Honors';
            //       }
            //       else if( number_format($grade,3) >= 85 && number_format($grade,3) <= 89.99){
            //             $award = 'Commendable';
            //       }
            //       if($quarter == 1 && $lowest < 85){
            //             $award = '';
            //       }elseif($quarter == 2 && $lowest < 86){
            //             $award = '';
            //       }elseif($quarter == 3 && $lowest <= 87){
            //             $award = '';
            //       }elseif($quarter == 4 && $lowest <= 88){
            //             $award = '';
            //       }
            // }
            // else if(strtoupper($schoolinfo->abbreviation) == 'SPCT'){
            //       if( number_format($grade,3) >= 90 && number_format($grade,3) <= 94.999){
            //             $award = 'With Honors';
            //       }
            //       else if( number_format($grade,3) >= 95 && number_format($grade,3) <= 97.999){
            //             $award = 'With High Honors';
            //       }
            //       else if( number_format($grade,3) >= 98 && number_format($grade,3) <= 100){
            //             $award = 'With Highest Honors';
            //       }
            // }
            // else if(strtoupper($schoolinfo->abbreviation) == 'GBBC'){
            //       if( number_format($grade) >= 90 && number_format($grade) <= 94.999){
            //             $award = 'With Honor';
            //       }
            //       else if( number_format($grade) >= 95 && number_format($grade) <= 97.999){
            //             $award = 'With High Honor';
            //       }
            //       else if( number_format($grade) >= 98 && number_format($grade) <= 100){
            //             $award = 'With Highest Honor';
            //       }
            // }else if( strtoupper($schoolinfo->abbreviation) == 'HCHS CP'){
            //       if( ( number_format($grade) >= 90 && number_format($grade) <= 94.999 )){
            //             $award = 'With Honor';
            //       }
            //       else if( ( number_format($grade) >= 95 && number_format($grade) <= 97.999 )){
            //             $award = 'With High Honor';
            //       }
            //       else if( ( number_format($grade) >= 98 && number_format($grade) <= 100 )){
            //             $award = 'With Highest Honor';
            //       }
            //       if($lowest < 85){
            //           $award = '';
            //       }
            // }
            // else{
            //       if( number_format($grade,3) >= 90 && number_format($grade,3) <= 92.999){
            //             $award = 'With Honors';
            //       }
            //       if( number_format($grade,3) >= 93 && number_format($grade,3) <= 94.999){
            //             $award = 'With High Honors';
            //       }
            //       if( number_format($grade,3) >= 95 && number_format($grade,3) <= 100){
            //             $award = 'With Highest Honors';
            //       }
            //       if(  number_format($grade,3) >= $min_dist && number_format($grade,3) <= $max_dist){
            //             $award = 'With Distinction';
            //       }
            //       if(  number_format($grade,3) >= $min_sp && number_format($grade,3) <= $max_sp){
            //             $award = 'Special Recognition';
            //       }



            // }
           
            // return $award;
      }


      public static function generate_grade($subjects = [] , $grades = [], $studid = null, $syid = null){

            $schoolinfo = DB::table('schoolinfo')->first();

            foreach(collect($subjects)->where('isCon',0) as $item){
                  
                  $tem_grades =  collect($grades)->where('subjid',$item->id);
                  $item->q1status = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->status) ? $tem_grades->where('quarter',1)->first()->status : null : null;
                  $item->q2status = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->status) ? $tem_grades->where('quarter',2)->first()->status : null : null;
                  $item->q3status = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->status) ? $tem_grades->where('quarter',3)->first()->status : null : null;
                  $item->q4status = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->status) ? $tem_grades->where('quarter',4)->first()->status : null : null;
                  
                  $item->q1gid = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->id) ? $tem_grades->where('quarter',1)->first()->id : null : null;
                  $item->q2gid = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->id) ? $tem_grades->where('quarter',2)->first()->id : null : null;
                  $item->q3gid = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->id) ? $tem_grades->where('quarter',3)->first()->id : null : null;
                  $item->q4gid = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->id) ? $tem_grades->where('quarter',4)->first()->id : null : null;

                  $item->q1 = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->qg) ? $tem_grades->where('quarter',1)->first()->qg : null : null;
                  $item->q2 = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->qg) ? $tem_grades->where('quarter',2)->first()->qg : null : null;
                  $item->q3 = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->qg) ? $tem_grades->where('quarter',3)->first()->qg : null : null;
                  $item->q4 = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->qg) ? $tem_grades->where('quarter',4)->first()->qg : null : null;
                  $item->quarter1 = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->qg) ? $tem_grades->where('quarter',1)->first()->qg : null : null;
                  $item->quarter2 = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->qg) ? $tem_grades->where('quarter',2)->first()->qg : null : null;
                  $item->quarter3 = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->qg) ? $tem_grades->where('quarter',3)->first()->qg : null : null;
                  $item->quarter4 = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->qg) ? $tem_grades->where('quarter',4)->first()->qg : null : null;
            
                  if(strtoupper($schoolinfo->abbreviation) == 'GBBC'){
                        if($syid == 3){
                            
                          if($item->id == 30 || $item->id == 31 || $item->id == 32){
                              $tem_grades =  collect($grades)->where('subjid',29);
                              $item->q1status = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->status) ? $tem_grades->where('quarter',1)->first()->status : null : null;
                              $item->q2status = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->status) ? $tem_grades->where('quarter',2)->first()->status : null : null;
                              $item->q3status = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->status) ? $tem_grades->where('quarter',3)->first()->status : null : null;
                              $item->q4status = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->status) ? $tem_grades->where('quarter',4)->first()->status : null : null;
                            
                              $item->q1gid = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->id) ? $tem_grades->where('quarter',1)->first()->id : null : null;
                              $item->q2gid = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->id) ? $tem_grades->where('quarter',2)->first()->id : null : null;
                              $item->q3gid = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->id) ? $tem_grades->where('quarter',3)->first()->id : null : null;
                              $item->q4gid = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->id) ? $tem_grades->where('quarter',4)->first()->id : null : null;
              
                              $item->q1 = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->qg) ? $tem_grades->where('quarter',1)->first()->qg : null : null;
                              $item->q2 = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->qg) ? $tem_grades->where('quarter',2)->first()->qg : null : null;
                              $item->q3 = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->qg) ? $tem_grades->where('quarter',3)->first()->qg : null : null;
                              $item->q4 = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->qg) ? $tem_grades->where('quarter',4)->first()->qg : null : null;
                              $item->quarter1 = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->qg) ? $tem_grades->where('quarter',1)->first()->qg : null : null;
                              $item->quarter2 = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->qg) ? $tem_grades->where('quarter',2)->first()->qg : null : null;
                              $item->quarter3 = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->qg) ? $tem_grades->where('quarter',3)->first()->qg : null : null;
                              $item->quarter4 = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->qg) ? $tem_grades->where('quarter',4)->first()->qg : null : null;
                              
                          }
                      }
                  }
            
            }

            foreach(collect($subjects)->where('isCon',1) as $item){

                  $schoolinfo = DB::table('schoolinfo')
                                    ->select('abbreviation')
                                    ->first();

                  if($schoolinfo->abbreviation == 'SPCT' && $item->id == 9){
                        $components = collect($subjects)->where('subjCom',$item->id)->values();
                        $item->spcon = 0;
                        $item->q1status = 4;
                        $item->q2status = 4;
                        $item->q3status = 4;
                        $item->q4status = 4;

                        $item->q1gid = 0;
                        $item->q2gid = 0;
                        $item->q3gid = 0;
                        $item->q4gid = 0;

                        $item->q1 = null;
                        $item->q2 = null;
                        $item->q3 = null;
                        $item->q4 = null;
                        $item->quarter1 = null;
                        $item->quarter2 = null;
                        $item->quarter3 = null;
                        $item->quarter4 = null;

                        //calculation
                        if(isset(collect($grades)->where('subjid',7)->where('quarter',1)->first()->qg)){
                              $item->q1 = number_format( ( ( collect($grades)->where('subjid',7)->where('quarter',1)->first()->qg * 2 ) + collect($grades)->where('subjid',8)->where('quarter',1)->first()->qg ) / 3 );
                              $item->quarter1 = number_format( ( ( collect($grades)->where('subjid',7)->where('quarter',1)->first()->qg * 2 ) + collect($grades)->where('subjid',8)->where('quarter',1)->first()->qg ) / 3 );
                        }
                     
                        if(isset(collect($grades)->where('subjid',7)->where('quarter',2)->first()->qg)){
                              $item->q2 = number_format( ( ( collect($grades)->where('subjid',7)->where('quarter',2)->first()->qg * 2 ) + collect($grades)->where('subjid',8)->where('quarter',2)->first()->qg ) / 3 );
                              $item->quarter2 = number_format( ( ( collect($grades)->where('subjid',7)->where('quarter',2)->first()->qg * 2 ) + collect($grades)->where('subjid',8)->where('quarter',2)->first()->qg ) / 3 );
                        }

                        if(isset(collect($grades)->where('subjid',7)->where('quarter',3)->first()->qg)){
                              $item->q3 = number_format(( ( collect($grades)->where('subjid',7)->where('quarter',3)->first()->qg * 2 ) + collect($grades)->where('subjid',8)->where('quarter',3)->first()->qg ) / 3 );
                              $item->quarter3 = number_format(( ( collect($grades)->where('subjid',7)->where('quarter',3)->first()->qg * 2 ) + collect($grades)->where('subjid',8)->where('quarter',3)->first()->qg ) / 3 );
                        }

                        if(isset(collect($grades)->where('subjid',7)->where('quarter',3)->first()->qg)){
                              $item->q4 = number_format( ( ( collect($grades)->where('subjid',7)->where('quarter',4)->first()->qg * 2 ) + collect($grades)->where('subjid',8)->where('quarter',4)->first()->qg ) / 3 );
                              $item->quarter4 = number_format( ( ( collect($grades)->where('subjid',7)->where('quarter',4)->first()->qg * 2 ) + collect($grades)->where('subjid',8)->where('quarter',4)->first()->qg ) / 3 );
                        }
                    

                  }else{
           
                        $components = collect($subjects)->where('subjCom',$item->id)->values();
                        $item->spcon = 0;
                        $with_percentage = collect($subjects)->where('subjCom',$item->id)->where('subj_per','!=',0)->count() > 0 ? true : false;
                        $with_sp = collect($subjects)->where('subjCom',$item->id)->where('isSP',1)->count() > 0 ? true : false;

                        $tem_grades =  collect($grades)->where('subjid',$item->id);

                        $item->q1status = 4;
                        $item->q2status = 4;
                        $item->q3status = 4;
                        $item->q4status = 4;

                        $item->q1gid = 0;
                        $item->q2gid = 0;
                        $item->q3gid = 0;
                        $item->q4gid = 0;

                        $item->q1 = null;
                        $item->q2 = null;
                        $item->q3 = null;
                        $item->q4 = null;
                        $item->quarter1 = null;
                        $item->quarter2 = null;
                        $item->quarter3 = null;
                        $item->quarter4 = null;

                        if($with_percentage){
                              $tle1 = 0;
                              $tle2 = 0;
                              $tle3 = 0; 
                              $tle4 = 0; 
                              $with_grade1 = true;
                              $with_grade2 = true;
                              $with_grade3 = true;
                              $with_grade4 = true;
                              $mapehcount = 0;
                  
                              foreach($components as $component_item){
                                    $tle1 += $component_item->q1 * ( $component_item->subj_per / 100 ) ;
                                    $tle2 += $component_item->q2 * ( $component_item->subj_per / 100 ) ;
                                    $tle3 += $component_item->q3 * ( $component_item->subj_per / 100 ) ;
                                    $tle4 += $component_item->q4 * ( $component_item->subj_per / 100 ) ;
                              }

                              $item->q1 = number_format($tle1) != 0 ? number_format($tle1) : null;
                              $item->q2 = number_format($tle2) != 0 ? number_format($tle2) : null;
                              $item->q3 = number_format($tle3) != 0 ? number_format($tle3) : null;
                              $item->q4 = number_format($tle4) != 0 ? number_format($tle4) : null;
                              $item->quarter1 = number_format($tle1) != 0 ? number_format($tle1) : null;
                              $item->quarter2 = number_format($tle2) != 0 ? number_format($tle2) : null;
                              $item->quarter3 = number_format($tle3) != 0 ? number_format($tle3) : null;
                              $item->quarter4 = number_format($tle4) != 0 ? number_format($tle4) : null;


                        }
                        elseif($with_sp){
                              $item->spcon = 1;
                              $specialized_subjects = DB::table('subjects_studspec')
                                    ->where('deleted',0)
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->get();
                        
                              foreach($specialized_subjects as $component_item){
                              
                                    $isSP  = collect($subjects)->where('id',$component_item->subjid)->where('isSP',1)->count() > 0 ? true : false;
                                    $gradeinfo = collect($grades)->where('subjid',$component_item->subjid);

                                    if($component_item->q1 == 1 && $isSP){
                                          if(isset(collect($gradeinfo)->where('quarter',1)->first()->qg)){
                                                $item->q1 = collect($gradeinfo)->where('quarter',1)->first()->qg != null ? collect($gradeinfo)->where('quarter',1)->first()->qg : null;
                                                $item->quarter1 = collect($gradeinfo)->where('quarter',1)->first()->qg != null ? collect($gradeinfo)->where('quarter',1)->first()->qg : null;
                                          }
                                    }
                              
                                    if($component_item->q2 == 1 && $isSP){
                                          if(isset($gradeinfo->where('quarter',2)->first()->qg)){
                                                $item->q2 = collect($gradeinfo)->where('quarter',2)->first()->qg != null ? collect($gradeinfo)->where('quarter',2)->first()->qg : null;
                                                $item->quarter2 = collect($gradeinfo)->where('quarter',2)->first()->qg != null ? collect($gradeinfo)->where('quarter',2)->first()->qg : null;
                                          }
                                    }
                              
                                    if($component_item->q3 == 1 && $isSP){
                                          if(isset(collect($gradeinfo)->where('quarter',3)->first()->qg)){
                                                $item->q3 = collect($gradeinfo)->where('quarter',3)->first()->qg != null ? collect($gradeinfo)->where('quarter',3)->first()->qg : null;
                                                $item->quarter3 = collect($gradeinfo)->where('quarter',3)->first()->qg != null ?collect($gradeinfo)->where('quarter',3)->first()->qg : null;
                                          }
                                    }
                                    

                                    if($component_item->q4 == 1 && $isSP){
                                          if(isset(collect($gradeinfo)->where('quarter',4)->first()->qg)){
                                                $item->q4 = collect($gradeinfo)->where('quarter',4)->first()->qg != null ? collect($gradeinfo)->where('quarter',4)->first()->qg : null;
                                                $item->quarter4 = collect($gradeinfo)->where('quarter',4)->first()->qg != null ? collect($gradeinfo)->where('quarter',4)->first()->qg : null;
                                          }
                                    }
                                    
                              
                              
                              }
                        }
                        else{
                              
                              $item->q1 = collect($components)->where('first',1)->where('q1',null)->count() == 0 ? number_format(collect($components)->avg('q1')) : null;
                              $item->q2 = collect($components)->where('second',1)->where('q2',null)->count() == 0 ? number_format(collect($components)->avg('q2')) : null;
                              $item->q3 = collect($components)->where('third',1)->where('q3',null)->count() == 0 ? number_format(collect($components)->avg('q3')) : null;
                              $item->q4 = collect($components)->where('fourth',1)->where('q4',null)->count() == 0 ? number_format(collect($components)->avg('q4')) : null;
                              $item->quarter1 = collect($components)->where('first',1)->where('q1',null)->count() == 0 ? number_format(collect($components)->avg('q1')) : null;
                              $item->quarter2 = collect($components)->where('second',1)->where('q2',null)->count() == 0 ? number_format(collect($components)->avg('q2')) : null;
                              $item->quarter3 = collect($components)->where('third',1)->where('q3',null)->count() == 0 ? number_format(collect($components)->avg('q3')) : null;
                              $item->quarter4 = collect($components)->where('fourth',1)->where('q4',null)->count() == 0 ? number_format(collect($components)->avg('q4')) : null;
                        
                        }

                  }
           

            }

			foreach(collect($subjects)->where('isSP',1) as $item){
                  $specialized_subjects = DB::table('subjects_studspec')
                                      ->where('deleted',0)
                                      ->where('subjid',$item->subjid)
                                      ->where('studid',$studid)
                                      ->where('syid',$syid)
                                      ->first();
                                      
                  if(!isset($specialized_subjects)){
                      $item->q1  = null;
                      $item->q2  = null;
                      $item->q3 = null; 
                      $item->q4 = null;
                      $item->quarter1  = null;
                      $item->quarter2  = null;
                      $item->quarter3 = null; 
                      $item->quarter4 = null;
                      $item->q1status = null;
                      $item->q2status = null;
                      $item->q3status = null;
                      $item->q4status = null;
                  }
            }
           

            $with_genave1 = true;
            $with_genave2 = true;
            $with_genave3 = true;
            $with_genave4 = true;

            foreach($subjects as $item){
                        
                  $with_finalrating = true;
                  $subjcount = 0;
                  $temp_genave = 0;

                  if($item->first == 1  && $item->subjCom == null){
                        if($item->q1 != null){
                              $temp_genave += $item->q1;
                              $subjcount += 1;
                        }else{
							if($item->inSF9 == 1){
                              $with_finalrating = false;
                              $with_genave1 = false;
							}
                        }
                  }
                  if($item->second == 1  && $item->subjCom == null){
                        if($item->q2 != null){
                              $temp_genave += $item->q2;
                              $subjcount += 1;
                        }else{
							if($item->inSF9 == 1){
                              $with_finalrating = false;
                              $with_genave2 = false;
							}
                        }
                  }
                  if($item->third == 1  && $item->subjCom == null){
                        if($item->q3 != null){
                              $temp_genave += $item->q3;
                              $subjcount += 1;
                        }else{
							if($item->inSF9 == 1){
                              $with_finalrating = false;
                              $with_genave3 = false;
							}
                        }
                  }
                  if($item->fourth == 1  && $item->subjCom == null){
                        if($item->q4 != null){
                              $temp_genave += $item->q4;
                              $subjcount += 1;
                        }else{
							if($item->inSF9 == 1){
                              $with_finalrating = false;
                              $with_genave4 = false;
							}
                        }
                  }

                  if($subjcount != 0){
                        $genave = number_format($temp_genave /  $subjcount);
                  }else{
                        $with_finalrating = false;
                  }
                  
                

                  $item->finalrating = $with_finalrating ? $genave : null;
                  $item->actiontaken = $with_finalrating ? $genave >= 75 ? 'PASSED' : 'FAILED' : null;
                  
                  if($item->isSP == 1){
                    //   return collect($item);
                      $with_finalrating = true;
                      $subjcount = 0;
                      if($item->first == 1){
                            if($item->q1 != null){
                                  $temp_genave += $item->q1;
                                  $subjcount += 1;
                            }else{
                                  $with_finalrating = false;
                            }
                      }
                      if($item->second == 1){
                            if($item->q2 != null){
                                  $temp_genave += $item->q2;
                                  $subjcount += 1;
                            }else{
                                  $with_finalrating = false;
                            }
                      }
                      if($item->third == 1){
                            if($item->q3 != null){
                                  $temp_genave += $item->q3;
                                  $subjcount += 1;
                            }else{
                                  $with_finalrating = false;
                            }
                      }
                      if($item->fourth == 1 ){
                            if($item->q4 != null){
                                  $temp_genave += $item->q4;
                                  $subjcount += 1;
                            }else{
                                  $with_finalrating = false;
                            }
                      }
                      
                      if($subjcount != 0){
                            $genave = number_format($temp_genave /  $subjcount);
                      }else{
                            $with_finalrating = false;
                      }

                        $item->finalrating = $with_finalrating ? $genave : null;
                        $item->actiontaken = $with_finalrating ? $genave >= 75 ? 'PASSED' : 'FAILED' : null;
                      
                  }

            }


            // foreach(collect($subjects)->whereNotNull('subjCom',null)->values() as $item){
            //       $item->finalrating =  null;
            //       $item->actiontaken =  null;
            // }

            $with_finalrating = collect($subjects)->where('subjCom',null)->where('finalrating',null)->count() > 0 ? false : true;

           
            
            $temp_subj = array();

            $q1 = $with_genave1 ? collect($subjects)->where('inSF9',1)->where('first',1)->where('subjCom',null)->avg('q1') : null;
            $q2 = $with_genave2 ? collect($subjects)->where('inSF9',1)->where('second',1)->where('subjCom',null)->avg('q2') : null;
            $q3 = $with_genave3 ? collect($subjects)->where('inSF9',1)->where('third',1)->where('subjCom',null)->avg('q3') : null;
            $q4 = $with_genave4 ? collect($subjects)->where('inSF9',1)->where('fourth',1)->where('subjCom',null)->avg('q4') : null;

            $q1award = $with_genave1 ? self::check_award($q1,1,collect($subjects)->where('inSF9',1)->where('subjCom',null)->min('q1'),$syid) : null;
            $q2award = $with_genave2 ? self::check_award($q2,2,collect($subjects)->where('inSF9',1)->where('subjCom',null)->min('q2'),$syid) : null;
            $q3award = $with_genave3 ? self::check_award($q3,3,collect($subjects)->where('inSF9',1)->where('subjCom',null)->min('q3'),$syid) : null;
            $q4award = $with_genave4 ? self::check_award($q4,4,collect($subjects)->where('inSF9',1)->where('subjCom',null)->min('q4'),$syid) : null;

            $fr = $with_finalrating ? collect($subjects)->where('subjCom',null)->where('inSF9',1)->avg('finalrating') : null;
            $fraward = $with_finalrating ? self::check_award(number_format($fr,3),null,100,$syid) : null;

            $genave = (object)[
                  'subjdesc'=>'GENERAL AVERAGE',
                  'sortid'=>'ZZ',
                  'isVisible'=>1,
                  'subjCom'=>null,
                  'id'=>'G1',
                  'subjid'=>'G1',
                  'q1status'=>1,
                  'q2status'=>1,
                  'q3status'=>1,
                  'q4status'=>1,
                  'q1gid'=>0,
                  'q2gid'=>0,
                  'q3gid'=>0,
                  'q4gid'=>0,
                  'q1'=>$q1 != null ? number_format($q1) : null,
                  'q2'=>$q2 != null ?number_format($q2) : null,
                  'q3'=>$q3 != null ?number_format($q3) : null,
                  'q4'=>$q4 != null ?number_format($q4) : null,
                  'q1award'=>$q1 != null ? $q1award : null,
                  'q2award'=>$q2 != null ? $q2award : null,
                  'q3award'=>$q3 != null ? $q3award : null,
                  'q4award'=>$q4 != null ? $q4award : null,
                  'q1comp'=>$q1 != null ? number_format($q1,3) : null,
                  'q2comp'=>$q2 != null ? number_format($q2,3) : null,
                  'q3comp'=>$q3 != null ? number_format($q3,3) : null,
                  'q4comp'=>$q4 != null ? number_format($q4,3) : null,
                  'quarter1'=>$q1 != null ? number_format($q1) : null,
                  'quarter2'=>$q2 != null ? number_format($q2) : null,
                  'quarter3'=>$q3 != null ? number_format($q3) : null,
                  'quarter4'=>$q4 != null ? number_format($q4) : null,
                  'finalrating'=>$fr != null ? number_format($fr) : null,
                  'fcomp'=>$fr != null ? number_format($fr,3) : null,
                  'fraward'=>$fraward,
                  'actiontaken'=>$with_finalrating ? $fr != null ? $fr >= 75 ? 'PASSED':'FAILED' : null : null,
                  'lq1'=>$q1 != null ? collect($subjects)->where('subjCom',null)->min('q1'): null,
                  'lq2'=>$q2 != null ? collect($subjects)->where('subjCom',null)->min('q2'): null,
                  'lq3'=>$q3 != null ? collect($subjects)->where('subjCom',null)->min('q3'): null,
                  'lq4'=>$q4 != null ? collect($subjects)->where('subjCom',null)->min('q4'): null,
                  'lfr'=>collect($subjects)->min('finalrating'),
                  'vr'=>5
            ];  

            array_push($temp_subj,$genave);

            foreach($subjects as $item){
                  $item->ver = 'v5';
                  if($item->isCon == 1 && $item->spcon == 0){
                        $item->q1status = collect($subjects)->where('subjCom',$item->id)->where('q1status',0)->count() > 0 ? 0 : 4;
                        $item->q2status = collect($subjects)->where('subjCom',$item->id)->where('q2status',0)->count() > 0 ? 0 : 4;
                        $item->q3status = collect($subjects)->where('subjCom',$item->id)->where('q3status',0)->count() > 0 ? 0 : 4;
                        $item->q4status = collect($subjects)->where('subjCom',$item->id)->where('q4status',0)->count() > 0 ? 0 : 4;
                  }
                  array_push($temp_subj,$item);
            }

            $subjects = $temp_subj;

            return $subjects;

      }

      public static function generate_sh_grade($subjects = [] , $grades = [],  $studid = null, $syid = null,  $semid = null){

            $schoolinfo = DB::table('schoolinfo')->first();

            foreach(collect($subjects)->where('isCon',0)->values() as $item){
                  
                  $tem_grades =  collect($grades)->where('subjid',$item->id);
                  $item->q1status = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->status) ? $tem_grades->where('quarter',1)->first()->status : null : null;
                  $item->q2status = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->status) ? $tem_grades->where('quarter',2)->first()->status : null : null;
                  $item->q3status = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->status) ? $tem_grades->where('quarter',3)->first()->status : null : null;
                  $item->q4status = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->status) ? $tem_grades->where('quarter',4)->first()->status : null : null;
                  
                  $item->q1gid = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->id) ? $tem_grades->where('quarter',1)->first()->id : null : null;
                  $item->q2gid = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->id) ? $tem_grades->where('quarter',2)->first()->id : null : null;
                  $item->q3gid = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->id) ? $tem_grades->where('quarter',3)->first()->id : null : null;
                  $item->q4gid = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->id) ? $tem_grades->where('quarter',4)->first()->id : null : null;

                  $item->q1 = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->qg) ? $tem_grades->where('quarter',1)->first()->qg : null : null;
                  $item->q2 = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->qg) ? $tem_grades->where('quarter',2)->first()->qg : null : null;
                  $item->q3 = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->qg) ? $tem_grades->where('quarter',3)->first()->qg : null : null;
                  $item->q4 = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->qg) ? $tem_grades->where('quarter',4)->first()->qg : null : null;
                  $item->quarter1 = $item->first == 1 ? isset($tem_grades->where('quarter',1)->first()->qg) ? $tem_grades->where('quarter',1)->first()->qg : null : null;
                  $item->quarter2 = $item->second == 1 ? isset($tem_grades->where('quarter',2)->first()->qg) ? $tem_grades->where('quarter',2)->first()->qg : null : null;
                  $item->quarter3 = $item->third == 1 ? isset($tem_grades->where('quarter',3)->first()->qg) ? $tem_grades->where('quarter',3)->first()->qg : null : null;
                  $item->quarter4 = $item->fourth == 1 ? isset($tem_grades->where('quarter',4)->first()->qg) ? $tem_grades->where('quarter',4)->first()->qg : null : null;
                
            }

            foreach(collect($subjects)->where('isCon',1)->values() as $item){
           
                  $components = collect($subjects)->where('subjCom',$item->id)->values();
                  $item->spcon = 0;
                  $with_percentage = collect($subjects)->where('subjCom',$item->id)->where('subj_per','!=',0)->count() > 0 ? true : false;
                  $with_sp = collect($subjects)->where('subjCom',$item->id)->where('isSP',1)->count() > 0 ? true : false;

                  $tem_grades =  collect($grades)->where('subjid',$item->id);

                  $item->q1status = 4;
                  $item->q2status = 4;
                  $item->q3status = 4;
                  $item->q4status = 4;

                  $item->q1gid = 0;
                  $item->q2gid = 0;
                  $item->q3gid = 0;
                  $item->q4gid = 0;

                  $item->q1 = null;
                  $item->q2 = null;
                  $item->q3 = null;
                  $item->q4 = null;
                  $item->quarter1 = null;
                  $item->quarter2 = null;
                  $item->quarter3 = null;
                  $item->quarter4 = null;

                  if($with_percentage){
                        $tle1 = 0;
                        $tle2 = 0;
                        $tle3 = 0; 
                        $tle4 = 0; 
                        $with_grade1 = true;
                        $with_grade2 = true;
                        $with_grade3 = true;
                        $with_grade4 = true;
                        $mapehcount = 0;
                
                        foreach($components as $component_item){
                            $tle1 += $component_item->q1 * ( $component_item->subj_per / 100 ) ;
                            $tle2 += $component_item->q2 * ( $component_item->subj_per / 100 ) ;
                            $tle3 += $component_item->q3 * ( $component_item->subj_per / 100 ) ;
                            $tle4 += $component_item->q4 * ( $component_item->subj_per / 100 ) ;
                        }

                        $item->q1 = number_format($tle1);
                        $item->q2 = number_format($tle2);
                        $item->q3 = number_format($tle3);
                        $item->q4 = number_format($tle4);
                        $item->quarter1 = number_format($tle1);
                        $item->quarter2 = number_format($tle2);
                        $item->quarter3 = number_format($tle3);
                        $item->quarter4 = number_format($tle4);


                  }
                  elseif($with_sp){

                        $item->spcon = 1;
                        $specialized_subjects = DB::table('subjects_studspec')
                              ->where('deleted',0)
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->get();
                     
                        foreach($specialized_subjects as $component_item){
                            
                              $isSP  = collect($subjects)->where('id',$component_item->subjid)->where('isSP',1)->count() > 0 ? true : false;
                              $gradeinfo = collect($grades)->where('subjid',$component_item->subjid);

                              if($component_item->q1 == 1 && $isSP){
                                    if(isset(collect($gradeinfo)->where('quarter',1)->first()->qg)){
                                          $item->q1 = collect($gradeinfo)->where('quarter',1)->first()->qg != null ? collect($gradeinfo)->where('quarter',1)->first()->qg : null;
                                          $item->quarter1 = collect($gradeinfo)->where('quarter',1)->first()->qg != null ? collect($gradeinfo)->where('quarter',1)->first()->qg : null;
                                    }
                              }
                            
                              if($component_item->q2 == 1 && $isSP){
                                    if(isset($gradeinfo->where('quarter',2)->first()->qg)){
                                          $item->q2 = collect($gradeinfo)->where('quarter',2)->first()->qg != null ? collect($gradeinfo)->where('quarter',2)->first()->qg : null;
                                          $item->quarter2 = collect($gradeinfo)->where('quarter',2)->first()->qg != null ? collect($gradeinfo)->where('quarter',2)->first()->qg : null;
                                    }
                              }
                          
                              if($component_item->q3 == 1 && $isSP){
                                    if(isset(collect($gradeinfo)->where('quarter',3)->first()->qg)){
                                          $item->q3 = collect($gradeinfo)->where('quarter',3)->first()->qg != null ? collect($gradeinfo)->where('quarter',3)->first()->qg : null;
                                          $item->quarter3 = collect($gradeinfo)->where('quarter',3)->first()->qg != null ?collect($gradeinfo)->where('quarter',3)->first()->qg : null;
                                    }
                              }
                                 

                              if($component_item->q4 == 1 && $isSP){
                                    if(isset(collect($gradeinfo)->where('quarter',4)->first()->qg)){
                                          $item->q4 = collect($gradeinfo)->where('quarter',4)->first()->qg != null ? collect($gradeinfo)->where('quarter',4)->first()->qg : null;
                                          $item->quarter4 = collect($gradeinfo)->where('quarter',4)->first()->qg != null ? collect($gradeinfo)->where('quarter',4)->first()->qg : null;
                                    }
                              }
                                  
                             
                             
                        }
                  }
                  else{
                        
                        $item->q1 = collect($components)->where('first',1)->where('q1',null)->count() == 0 ? number_format(collect($components)->avg('q1')) : null;
                        $item->q2 = collect($components)->where('second',1)->where('q2',null)->count() == 0 ? number_format(collect($components)->avg('q2')) : null;
                        $item->q3 = collect($components)->where('third',1)->where('q3',null)->count() == 0 ? number_format(collect($components)->avg('q3')) : null;
                        $item->q4 = collect($components)->where('fourth',1)->where('q4',null)->count() == 0 ? number_format(collect($components)->avg('q4')) : null;
                        $item->quarter1 = collect($components)->where('first',1)->where('q1',null)->count() == 0 ? number_format(collect($components)->avg('q1')) : null;
                        $item->quarter2 = collect($components)->where('second',1)->where('q2',null)->count() == 0 ? number_format(collect($components)->avg('q2')) : null;
                        $item->quarter3 = collect($components)->where('third',1)->where('q3',null)->count() == 0 ? number_format(collect($components)->avg('q3')) : null;
                        $item->quarter4 = collect($components)->where('fourth',1)->where('q4',null)->count() == 0 ? number_format(collect($components)->avg('q4')) : null;
                      
                  }

           

            }

            $with_finalrating = true;

            $with_genave1 = true;
            $with_genave2 = true;
            $with_genave3 = true;
            $with_genave4 = true;

            foreach(collect($subjects)->where('subjCom',null) as $item){
                  $with_finalrating = true;
                  $subjcount = 0;
                  $temp_genave = 0;

                  if($item->semid == 1){
                        if($item->first == 1){
                              if($item->q1 != null || $item->q1 != 0){
                                    $temp_genave += $item->q1;
                                    $subjcount += 1;
                              }else{
								  if($item->inSF9 == 1){
                                    $with_finalrating = false;
                                    $with_genave1 = false;
								  }
                              }
                        }
                        if($item->second == 1){
                              if($item->q2 != null || $item->q2 != 0){
                                    $temp_genave += $item->q2;
                                    $subjcount += 1;
                              }else{
								  if($item->inSF9 == 1){
                                    $with_finalrating = false;
                                    $with_genave2 = false;
								  }
                              }
                        }
                  }else{
                        if($item->third == 1){
                              if($item->q3 != null){
                                    $temp_genave += $item->q3;
                                    $subjcount += 1;
                              }else{
								  if($item->inSF9 == 1){
                                    $with_finalrating = false;
                                    $with_genave3 = false;
								  }
                              }
                        }
                        if($item->fourth == 1){
                              if($item->q4 != null){
                                    $temp_genave += $item->q4;
                                    $subjcount += 1;
                              }else{
								  if($item->inSF9 == 1){
                                    $with_finalrating = false;
                                    $with_genave4 = false;
								  }
                              }
                        }

                  }


                  if($subjcount != 0){
                        $genave = number_format($temp_genave /  $subjcount);
                  }else{
                        $with_finalrating = false;
                  }
                  
                  $item->finalrating = $with_finalrating ? $genave : null;
                  $item->actiontaken = $with_finalrating ? $genave >= 75 ? 'PASSED' : 'FAILED' : null;

            }



            $q1 = $with_genave1 ? collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('first',1)->where('semid',1)->avg('q1') : null;
            $q2 = $with_genave2 ? collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('second',1)->where('semid',1)->avg('q2') : null;
            $q3 = $with_genave3 ? collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('third',1)->where('semid',2)->avg('q3') : null;
            $q4 = $with_genave4 ? collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('fourth',1)->where('semid',2)->avg('q4') : null;

            $q1award = $with_genave1 ? self::check_award($q1,1,collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q1'),$syid) : null;
            $q2award = $with_genave2 ? self::check_award($q2,2,collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q2'),$syid) : null;
            $q3award = $with_genave3 ? self::check_award($q3,3,collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q3'),$syid) : null;
            $q4award = $with_genave4 ? self::check_award($q4,4,collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q4'),$syid) : null;

            $with_finalrating = true;
            if(collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('semid',1)->where('finalrating',null)->count() > 0 || collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('semid',1)->where('finalrating',0)->count() > 0){
                  $with_finalrating = false;
            }

            $fr = null;
            if($schoolinfo->abbreviation == 'SJAES'){
                if(collect($subjects)->whereIn('subjid',[7,14,21,43,44,88,89,98,99])->where('subjCom',null)->where('semid',2)->count() > 0){
                    if($with_finalrating){
                         $final_rating = 0;
                         $subj_count = 0;
                         foreach(collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('semid',1)->values() as $subjitem){
                             if($subjitem->subjdesc == 'PHYSICAL EDUCATION'){
                                  $final_rating += $subjitem->finalrating * .25;
                                  $subj_count += .25;
                             }else{
                                  $final_rating += $subjitem->finalrating;
                                  $subj_count += 1;
                             }
                         }
                         $fr = $final_rating / $subj_count;
                    }
                }else{
                    $fr = $with_finalrating ? collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('semid',1)->avg('finalrating') : null;
                }
                 
            }else{
                $fr = $with_finalrating ? collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('semid',1)->avg('finalrating') : null;
            }

            $fraward = $with_finalrating ? self::check_award($fr,null,100,$syid) : null;

            $temp_subj = array();

            foreach(collect($subjects)->where('subjCom',null) as $item){
                  if($item->isVisible == 1){
                        array_push($temp_subj,$item);
                  }
            }

            $fr_1st = $fr;

            $genave = (object)[
                  'subjdesc'=>'GENERAL AVERAGE',
                  'sortid'=>'ZZ',
                  'isVisible'=>1,
                  'subjCom'=>null,
                  'id'=>'G1',
                  'subjid'=>'G1',
                  'q1status'=>1,
                  'q2status'=>1,
                  'q3status'=>null,
                  'q4status'=>null,
                  'q1gid'=>0,
                  'q2gid'=>0,
                  'q3gid'=>null,
                  'q4gid'=>null,
                  'q1'=>$q1 != null ? number_format($q1) : null,
                  'q2'=>$q2!= null ? number_format($q2) : null,
                  'q3'=>null,
                  'q4'=>null,
                  'q1award'=>$q1award,
                  'q2award'=>$q2award,
                  'q3award'=>null,
                  'q4award'=>null,
                  'q1comp'=>$q1 != null ? number_format($q1,3)  : null,
                  'q2comp'=>$q2 != null ? number_format($q2,3)  : null,
                  'q3comp'=>null,
                  'q4comp'=>null,
                  'quarter1'=>$q1 != null ? number_format($q1)  : null,
                  'quarter2'=>$q2 != null ? number_format($q2)  : null,
                  'quarter3'=>null,
                  'quarter4'=>null,
                  'finalrating'=> $fr != null ? number_format($fr) : null,
                  'fcomp'=>number_format($fr,3),
                  'fraward'=>$fraward,
                  'actiontaken'=>$with_finalrating ? $fr != null ? $fr >= 75 ? 'PASSED':'FAILED' : null : null,
                  'semid'=>1,
                  'lq1'=>$q1 != null ? collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q1'): null,
                  'lq2'=>$q2 != null ? collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q2'): null,
                  'lq3'=>$q3 != null ? collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q3'): null,
                  'lq4'=>$q4 != null ? collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q4'): null,
                  'lfr'=>collect($subjects)->where('inSF9',1)->min('finalrating'),
                  'vr'=>5
            ];  
            array_push($temp_subj,$genave);

            $with_finalrating = true;
            if(collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('semid',2)->where('finalrating',null)->count() > 0 || collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('semid',2)->where('finalrating',0)->count() > 0){
                  $with_finalrating = false;
            }

            $fr = null;
            if($schoolinfo->abbreviation == 'SJAES'){
                if(collect($subjects)->whereIn('subjid',[7,14,21,43,44,88,89,98,99])->where('subjCom',null)->where('semid',2)->count() > 0){
                    if($with_finalrating){
                         $final_rating = 0;
                         $subj_count = 0;
                         foreach(collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('semid',2)->values() as $subjitem){
                             if($subjitem->subjdesc == 'PHYSICAL EDUCATION'){
                                  $final_rating += $subjitem->finalrating * .25;
                                  $subj_count += .25;
                             }else{
                                  $final_rating += $subjitem->finalrating;
                                  $subj_count += 1;
                             }
                         }
                         $fr = $final_rating / $subj_count;
                    }
                }else{
                    $fr = $with_finalrating ? collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('semid',2)->avg('finalrating') : null;
                }
                 
            }else{
                $fr = $with_finalrating ? collect($subjects)->where('inSF9',1)->where('subjCom',null)->where('semid',2)->avg('finalrating') : null;
            }
            
            $fr_2nd = $fr;
            $fraward = $with_finalrating ? self::check_award(number_format($fr,3),null,100,$syid) : null;

            $genave = (object)[
                  'subjdesc'=>'GENERAL AVERAGE',
                  'sortid'=>'ZZ',
                  'isVisible'=>1,
                  'subjCom'=>null,
                  'id'=>'G1',
                  'subjid'=>'G1',
                  'q1status'=>null,
                  'q2status'=>null,
                  'q3status'=>1,
                  'q4status'=>1,
                  'q1gid'=>null,
                  'q2gid'=>null,
                  'q3gid'=>0,
                  'q4gid'=>0,
                  'q1'=>null,
                  'q2'=>null,
                  'q3'=>$q3 != null ? number_format($q3) : null,
                  'q4'=>$q4 != null ? number_format($q4) : null,
                  'q1award'=>null,
                  'q2award'=>null,
                  'q3award'=>$q3 != null ? $q3award : null,
                  'q4award'=>$q4 != null ? $q4award : null,
                  'q1comp'=>null,
                  'q2comp'=>null,
                  'q3comp'=>$q3 != null ? number_format($q3,3) : null,
                  'q4comp'=>$q4 != null ? number_format($q4,3) : null,
                  'quarter1'=>null,
                  'quarter2'=>null,
                  'quarter3'=>$q3 != null ? number_format($q3) : null,
                  'quarter4'=>$q4 != null ? number_format($q4) : null,
                  'finalrating'=>$fr != null ? number_format($fr) : null,
                  'fcomp'=>$fr != null ? number_format($fr,3) : null,
                  'fraward'=>$fr != null ? $fraward : null,
                  'actiontaken'=>$with_finalrating ? $fr != null ? $fr >= 75 ? 'PASSED':'FAILED' : null : null,
                  'semid'=>2,
                  'lq1'=>$q1 != null ? collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q1'): null,
                  'lq2'=>$q2 != null ? collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q2'): null,
                  'lq3'=>$q3 != null ? collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q3'): null,
                  'lq4'=>$q4 != null ? collect($subjects)->where('subjCom',null)->where('inSF9',1)->min('q4'): null,
                  'lfr'=>collect($subjects)->where('inSF9',1)->min('finalrating'),
                  'vr'=>5
				
            ];  
            array_push($temp_subj,$genave);


            $fr_1st_2nd = $fr_1st != null && $fr_2nd != null ? ( $fr_1st + $fr_2nd ) / 2 : null;
            $fr_1st_2nd_award = $fr_1st_2nd != null ? self::check_award($fr_1st_2nd,null,100,$syid) : null;

            $fr_1st_2nd_genave = (object)[
                  'subjdesc'=>'GENERAL AVERAGE',
                  'sortid'=>'ZZGENAVE',
                  'isVisible'=>1,
                  'subjCom'=>null,
                  'id'=>'FRBOTH',
                  'subjid'=>'FRBOTH',
                  'q1status'=>null,
                  'q2status'=>null,
                  'q3status'=>1,
                  'q4status'=>1,
                  'q1gid'=>null,
                  'q2gid'=>null,
                  'q3gid'=>null,
                  'q4gid'=>null,
                  'q1'=>null,
                  'q2'=>null,
                  'q3'=>null,
                  'q4'=>null,
                  'q1award'=>null,
                  'q2award'=>null,
                  'q3award'=>null,
                  'q4award'=>null,
                  'q1comp'=>null,
                  'q2comp'=>null,
                  'q3comp'=>null,
                  'q4comp'=>null,
                  'quarter1'=>null,
                  'quarter2'=>null,
                  'quarter3'=>null,
                  'quarter4'=>null,
                  'finalrating'=>$fr_1st_2nd != null ? number_format($fr_1st_2nd) : null,
                  'fcomp'=>$fr_1st_2nd != null ? number_format($fr_1st_2nd,3) : null,
                  'fraward'=>$fr_1st_2nd != null ? $fr_1st_2nd_award : null,
                  'actiontaken'=> $fr_1st_2nd != null ? number_format($fr_1st_2nd) >= 75 ? 'PASSED':'FAILED' : null,
                  'semid'=>null,
                  'lq1'=> null,
                  'lq2'=>null,
                  'lq3'=>null,
                  'lq4'=>null,
                  'lfr'=>null,
                  'vr'=>5,
				  'strandid'=>null
            ];  

            array_push($temp_subj, $fr_1st_2nd_genave);
            
            
            
            $subjects = $temp_subj;

            return $subjects;


      }
      

}
