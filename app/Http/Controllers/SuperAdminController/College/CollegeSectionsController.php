<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use Session;

class CollegeSectionsController extends \App\Http\Controllers\Controller
{

      public static function collegesection_select2(Request $request){

            $search = $request->get('search');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

    
            $collegesections = DB::table('college_sections')
                                    ->where('college_sections.deleted',0)
                                    ->where('college_sections.syid',$syid)
                                    ->where('college_sections.semesterID',$semid)
                                    ->where('college_sections.issubjsched',0)
                                    ->leftJoin('college_courses',function($join){
                                          $join->on('college_sections.courseID','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->leftJoin('gradelevel',function($join){
                                          $join->on('college_sections.yearID','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->leftJoin('college_colleges',function($join){
                                          $join->on('college_sections.collegeID','=','college_colleges.id');
                                          $join->where('college_colleges.deleted',0);
                                    })
                                    ->where(function($query) use($search){
                                          if($search != null && $search != ""){
                                                $query->orWhere('sectionDesc','like','%'.$search.'%');
                                          }
                                    })
                                    ->select(
                                          'college_sections.collegeID',
                                          'college_sections.courseID',
                                          'courseabrv',
                                          'collegeabrv',
                                          'college_sections.id',
                                          'sectionDesc',
                                          'levelname'
                                          // 'college_courses.*'
                                    )
                                    ->take(10)
                                    ->skip($request->get('page')*10)
                                    ->get();

            foreach($collegesections as $item){

                  $text = '';

                  if($item->courseID != null){
                        $text = $item->courseabrv;
                  }else{
                        $text = $item->courseabrv;
                  }

                  $text .= '-'.$item->levelname[0] . ' '.$item->sectionDesc;
                  $item->text = $text;

            }

            $collegesections_count = DB::table('college_sections')
                        ->where('college_sections.deleted',0)
                        ->where('college_sections.syid',$syid)
                        ->where('college_sections.semesterID',$semid)
                        ->where(function($query) use($search){
                              if($search != null && $search != ""){
                                    $query->orWhere('sectionDesc','like','%'.$search.'%');
                              }
                        })
                        ->count();

            return @json_encode((object)[
                  "results"=>$collegesections,
                  "pagination"=>(object)[
                        "more"=>$collegesections_count > 10  ? true :false
                  ],
                  "count_filtered"=>$collegesections_count
            ]);
            

      }



      public static function all_college_subjects(Request $request){

            $search = $request->get('search');

            $all_subjects = DB::table('college_subjects')
                              ->where('deleted',0)
                              ->where(function($query) use($search){
                                    if($search != null && $search != ""){
                                          $query->orWhere('subjCode','like','%'.$search.'%');
                                          $query->orWhere('subjDesc','like','%'.$search.'%');
                                    }
                              })
                              ->take(10)
                              ->skip($request->get('page')*10)
                              ->select(
                                    'id',
                                    'subjCode',
                                    'subjDesc',
                                    DB::raw("CONCAT(college_subjects.subjCode,' - ',college_subjects.subjDesc) as text")
                              )
                        
                              ->get();


            $all_subjects_count = DB::table('college_subjects')
                  ->where('deleted',0)
                  ->where(function($query) use($search){
                        if($search != null && $search != ""){
                              $query->orWhere('subjCode','like','%'.$search.'%');
                              $query->orWhere('subjDesc','like','%'.$search.'%');
                        }
                  })
                  ->select(
                        'id',
                        'subjCode',
                        'subjDesc',
                        DB::raw("CONCAT(college_subjects.subjCode,' - ',college_subjects.subjDesc) as text")
                  )
            
                  ->count();       
               
            $more = false;
            if($all_subjects_count > 10){
                  $more = true;
            }
                              
            return @json_encode((object)[
                  "results"=>$all_subjects,
                  "pagination"=>(object)[
                        "more"=>$more
                  ],
                  "count_filtered"=>$all_subjects_count
            ]);
        

      }



      public static function sched_loaded_learners(Request $request){

            $schedid = $request->get('schedid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $enroll_students = DB::table('college_studsched')
                              ->join('college_enrolledstud',function($join) use($syid,$semid){
                                    $join->on('college_studsched.studid','=','college_enrolledstud.studid');
                                    $join->where('college_enrolledstud.deleted',0);
                                    $join->whereIn('studstatus',[1,2,4]);
                                    $join->where('syid',$syid);
                                    $join->where('semid',$semid);
                              })
                              ->where('college_studsched.schedid',$schedid)
                              ->where('college_studsched.schedstatus','!=','DROPPED')
                              ->where('college_studsched.deleted',0)
                              ->select(
                                    'college_enrolledstud.studid'
                              )
                              ->get();

            $students = DB::table('college_studsched')
                              ->join('studinfo',function($join){
                                    $join->on('college_studsched.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->leftJoin('college_courses',function($join){
                                    $join->on('studinfo.courseid','=','college_courses.id');
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                              })
                              ->where('college_studsched.schedid',$schedid)
                              ->where('college_studsched.schedstatus','!=','DROPPED')
                              ->where('college_studsched.deleted',0)
                              ->select(
                                    'sid',
                                    'college_studsched.studid',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'levelname',
                                    'courseabrv'
                              )
                              ->orderBy('lastname')
                              ->get();

            foreach($students as $eitem){
                  $middlename = explode(" ",$eitem->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                              if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                              } 
                        }
                  }
                  $check = collect($enroll_students)->where('studid',$eitem->studid)->count();
                  if($check > 0){
                        $eitem->isenrolled = 1;
                  }else{
                        $eitem->isenrolled = 0;
                  }

                  $eitem->student = $eitem->lastname.', '.$eitem->firstname.' '.$eitem->suffix.' '.$temp_middle;
            }

            return $students;


      }

      public static function sched_enrolled_learners(Request $request){
            

            $schedid = $request->get('schedid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $students = DB::table('college_studsched')
                              ->join('college_enrolledstud',function($join) use($syid,$semid){
                                    $join->on('college_studsched.studid','=','college_enrolledstud.studid');
                                    $join->where('college_enrolledstud.deleted',0);
                                    $join->whereIn('studstatus',[1,2,4]);
                                    $join->where('syid',$syid);
                                    $join->where('semid',$semid);
                              })
                              ->join('studinfo',function($join){
                                    $join->on('college_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->leftJoin('college_courses',function($join){
                                    $join->on('college_enrolledstud.courseid','=','college_courses.id');
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                              })
                              ->where('college_studsched.schedid',$schedid)
                              ->where('college_studsched.schedstatus','!=','DROPPED')
                              ->where('college_studsched.deleted',0)
                              ->select(
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'levelname',
                                    'courseabrv',
									'college_studsched.studid'
                              )
                              ->orderBy('lastname')
                              ->get();

            foreach($students as $eitem){
                  $middlename = explode(" ",$eitem->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                              if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                              } 
                        }
                  }
                  $eitem->isenrolled = 1;
                  $eitem->student = $eitem->lastname.', '.$eitem->firstname.' '.$eitem->suffix.' '.$temp_middle;
            }

            return $students;


      }


      public static function get_subjects(Request $request){

            $levelid = $request->get('levelid');
            $courseid = $request->get('courseid');
            $curriculum = $request->get('curriculum');
            $specification = $request->get('specification');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $sectionid = $request->get('tempsect');


            

            if($specification == 1){

                  $prospectussubjects = DB::table('college_prospectus')
                        ->where('college_prospectus.deleted','0')
                        ->where('courseID',$courseid)
                        ->where('yearID',$levelid)
                        ->where('semesterID',$semid)
                        ->where('curriculumID',$curriculum)
                        ->select(
                              'college_prospectus.id',
                              'college_prospectus.subjDesc',
                              'college_prospectus.subjCode'
                        )
                        ->get();


                  if($sectionid != null || $sectionid != ""){

                        $check_added_sched = DB::table('college_classsched')
                                                      ->where('sectionID',$sectionid)
                                                      ->where('deleted',0)
                                                      ->select('subjectID')
                                                      ->get();

                        foreach($prospectussubjects as $item){
                              if(collect( $check_added_sched)->where('subjectID',$item->id)->count() > 0){
                                    $item->included = 1;
                              }else{
                                    $item->included = 0;
                              }

                        }

                  }else{

                        foreach($prospectussubjects as $item){
                              $item->included = 1;
                        }

                  }

                  

                  return $prospectussubjects;
            }

            return array();
    
      }

      

      public static function get_allsubjects(Request $request){

            // $collegesubjects = DB::table('college_subjects')
            //                         ->where('college_subjects.deleted','0')
            //                         ->select(
            //                               'college_subjects.id',
            //                               'college_subjects.subjDesc',
            //                               'college_subjects.subjCode'
            //                         )
            //                         ->get();

            // foreach($collegesubjects as $item){
            //       $item->text = $item->subjCode.' - '.$item->subjDesc;
            // }
            
            // return $collegesubjects;

            
            $courseid = $request->get('courseid');
            $curriculumid = $request->get('curriculumid');

            $collegesubjects = Db::table('college_prospectus')
                              ->where('deleted',0)
                              ->where('courseID',$courseid)
                              ->where('curriculumID',$curriculumid)
                              ->select(
                                    'courseID',
                                    'subjectID as id',
                                    'semesterID',
                                    'yearID',
                                    'lecunits',
                                    'labunits',
                                    'subjDesc',
                                    'subjCode',
                                    'subjClass',
                                    'curriculumID',
                                    'psubjsort',
                                    DB::raw("CONCAT(college_prospectus.subjCode,' - ',college_prospectus.subjDesc) as text")
                              )
                              ->get();


            return $collegesubjects;
    
      }

      public static function add_subject(Request $request){

            $sectionid = $request->get('sectionid');
            $subjid = $request->get('subjid');
            $semid = $request->get('semid');
            $courseid = $request->get('courseid');
            $syid = $request->get('syid');

            $section_info = DB::table('college_sections')
                              ->where('id',$sectionid)
                              ->where('deleted',0)
                              ->first();

            if(!isset($section_info->id)){
                  return array((object)[
                        'status'=>0,
                        'data'=>'Section not found.'
                    ]);
            }


            if($section_info->section_specification == 1){

                  $prospectussubjects = DB::table('college_prospectus')
                                              ->where('college_prospectus.deleted','0')
                                              ->where('courseID',$section_info->courseID)
                                              ->where('yearID',$section_info->yearID)
                                              ->where('semesterID',$section_info->semesterID)
                                              ->where('curriculumID',$section_info->curriculumid)
                                              ->select('college_prospectus.id')
                                              ->get();

                  $p_count = 0;


                  foreach($prospectussubjects as $prospectussubject){

                        $check = DB::table('college_classsched')
                                    ->where('college_classsched.deleted',0)
                                    ->where('college_classsched.syid',$syid)
                                    ->where('college_classsched.semesterID',$semid)
                                    ->where('college_classsched.sectionID',$sectionid)
                                    ->where('college_classsched.subjectID',$prospectussubject->id)
                                    ->count();

                        

                        if($check == 0){
                              DB::table('college_classsched')->insert([
                                    'syID'=>$syid,
                                    'semesterID'=>$semid,
                                    'sectionID'=> $sectionid,
                                    'teacherID'=>null,
                                    'subjectID'=>$prospectussubject->id,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                              $p_count += 1;
                        }
                        
                  }

                  if($p_count == 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'No subject added!'
                        ]);
                  }else{
                        return \App\Http\Controllers\SuperAdminController\College\CollegeSchedController::collegesched_plot($request);
                  }




            }else{
            
                  $available_subjects = DB::table('college_prospectus')
                                          ->join('college_curriculum',function($join){
                                                $join->on('college_prospectus.curriculumID','=','college_curriculum.id');
                                                $join->where('college_curriculum.deleted',0);
                                                // $join->where('college_curriculum.isactive',1);
                                          })
                                          ->where('college_prospectus.courseid',$courseid)
                                          ->where('college_prospectus.subjectID',$subjid)
                                          ->where('college_prospectus.deleted',0)
                                          ->select('college_prospectus.*')
                                          ->first();

                  if(!isset($available_subjects->id)){
                        return array((object)[
                              'status'=>0,
                              'data'=>'Subject does not exist for this course!'
                        ]);
                  }

                  try{

                  


                        $check = DB::table('college_classsched')
                                    ->join('college_prospectus',function($join){
                                          $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                          $join->where('college_prospectus.deleted',0);
                                    })
                                    ->where('college_classsched.deleted',0)
                                    ->where('college_classsched.semesterID',$semid)
                                    ->where('college_classsched.sectionID',$sectionid)
                                    ->where('college_classsched.syID',$syid)
                                    ->where('college_classsched.subjectID',$available_subjects->id)
                                    ->count();
                        if($check > 0){
                              return array((object)[
                                    'status'=>0,
                                    'data'=>'Subject already exist!'
                              ]);
                        }
                        if(isset($available_subjects->id)){
                              DB::table('college_classsched')->insert([
                                    'syID'=>$syid,
                                    'semesterID'=>$semid,
                                    'sectionID'=> $sectionid,
                                    'teacherID'=>null,
                                    'subjectID'=>$available_subjects->id,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                        }
                        return \App\Http\Controllers\SuperAdminController\College\CollegeSchedController::collegesched_plot($request);
                

                  }catch(\Exception $e){
                        
                        return array((object)[
                        'status'=>0,
                        'data'=>'Something went wrong!'
                        ]);
                  }
            }
      }

      public static function remove_subject(Request $request){
           
            try{

                  $sectionid = $request->get('sectionid');
                  $schedid = $request->get('schedid');

                  $check = Db::table('college_studsched')
                              ->where('schedid',$schedid)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'With Enrolled Students!'
                        ]);
                  }

                  DB::table('college_classsched')
                        ->where('sectionid',$sectionid)
                        ->where('id',$schedid)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  
                  return array((object)[
                        'status'=>1,
                        'data'=>'Subject Removed!'
                  ]);

                  return \App\Http\Controllers\SuperAdminController\College\CollegeSchedController::collegesched_plot($request);

            }catch(\Exception $e){
                  return array((object)[
                      'status'=>0,
                      'data'=>'Something went wrong!'
                  ]);
            }
      }


      public static function delete_section(Request $request){

            $sectionid = $request->get('sectionid');


            $check = DB::table('college_enrolledstud')
                        // ->join('gradelevel',function($join){
                        //       $join->on('studinfo.levelid','=','gradelevel.id');
                        //       $join->where('gradelevel.deleted',0);
                        //       $join->where('acadprogid',6);
                        // })
                        ->where('college_enrolledstud.sectionID',$sectionid)
                        ->where('college_enrolledstud.deleted',0)
                        ->count();

            if($check > 0){
                  return array((object)[
                        'status'=>0,
                        'data'=>'Sections contains enrollees'
                  ]);
            }

          

            $check = DB::table('college_enrolledstud')
                              ->where('sectionid',$sectionid)
                              ->where('deleted',0)
                              ->count();

            if($check > 0){
                  return array((object)[
                        'status'=>0,
                        'data'=>'Sections contains enrollees'
                  ]);
            }

        

            $check = DB::table('college_studsched')
                              ->join('college_classsched',function($join) use ($sectionid){
                                    $join->on('college_studsched.schedid','=','college_classsched.id');
                                    $join->where('college_classsched.deleted',0);
                                    $join->where('college_classsched.sectionID',$sectionid);
                              })
                              ->where('college_studsched.deleted',0)
                              ->count();

            if($check > 0){
                  return array((object)[
                        'status'=>0,
                        'data'=>'Sections contains enrollees'
                  ]);
            }

            try{

                  DB::table('college_sections')
                        ->where('id',$sectionid)
                        ->where('deleted',0)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  $classsched = DB::table('college_classsched')
                                    ->where('sectionid',$sectionid)
                                    ->where('deleted',0)
                                    ->get();

                  foreach($classsched as $item){

                        DB::table('college_classsched')
                                    ->where('id',$item->id)
                                    ->where('deleted',0)
                                    ->take(1)
                                    ->update([
                                          'deleted'=>1,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                        DB::table('college_scheddetail')
                              ->where('headerID',$item->id)
                              ->where('deleted',0)
                              ->update([
                                    'deleted'=>1,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }


                  return array((object)[
                        'status'=>1,
                        'data'=>'Delete successfully!'
                  ]);

            }catch(\Exception $e){

                  return array((object)[
                      'status'=>0,
                      'data'=>'Something went wrong!'
                  ]);

              }

                  

      }

      


      public static function create_section(Request $request){

            $levelid = $request->get('levelid');
            $courseid = $request->get('courseid');
            $curriculum = $request->get('curriculum');
            $specification = $request->get('specification');
            $sectionname = $request->get('sectionname');
            $syid = $request->get('syid');
            $collegeid = $request->get('collegeid');
            $semid = $request->get('semid');
            $excluded_subj = $request->get('excluded_subj');
            $capacity = $request->get('capacity');

            if($excluded_subj == null || $excluded_subj == ""){
                  $excluded_subj = array();
            }

            if($excluded_subj == null || $excluded_subj == ""){
                  $capacity = 50;
            }


            // return $excluded_subj;
         
            try{

                  $check = DB::table('college_sections')
                                    ->where('deleted',0)
                                    ->where('sectionDesc',$sectionname)
                                    ->where('syID',$syid)
                                    ->where('courseID',$courseid)
                                    ->where('collegeID',$syid)
                                    ->where('semesterID',$semid)
                                    ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'Section already exist.'
                        ]);
                  }

                  if($specification == 1){
      
                      $sectionID = DB::table('college_sections')
                                      ->insertGetId([
                                          'syID'=>$syid,
                                          'semesterID'=>$semid,
                                          'courseID'=>$courseid,
                                          'collegeID'=>$collegeid,
                                          'yearID'=>$levelid,
                                          'curriculumid'=>$curriculum,
                                          'section_specification'=>$specification,
                                          'sectionDesc'=>$sectionname,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                      ]);
      
                      $prospectussubjects = DB::table('college_prospectus')
                                              ->where('college_prospectus.deleted','0')
                                              ->where('courseID',$courseid)
                                              ->where('yearID',$levelid)
                                              ->where('semesterID',$semid)
                                              ->where('curriculumID',$curriculum)
                                              ->select('college_prospectus.id')
                                              ->whereNotIn('id',$excluded_subj)
                                              ->get();

                        // return $prospectussubjects;
      
                      foreach($prospectussubjects as $prospectussubject){
                          DB::table('college_classsched')->insert([
                                    'syID'=>$syid,
                                    'semesterID'=>$semid,
                                    'sectionID'=> $sectionID,
                                    'teacherID'=>null,
                                    'subjectID'=>$prospectussubject->id,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'capacity'=>$capacity
                              ]);
                      }
      
      
                      return array((object)[
                          'status'=>1,
                          'data'=>'Created Successfully'
                      ]);
      
                  }else{
      
                      $sectionID = DB::table('college_sections')
                                      ->insertGetId([
                                          'collegeID'=>$collegeid,
                                          'syID'=>$syid,
                                          'semesterID'=>$semid,
                                          'courseID'=>$courseid,
                                          'curriculumid'=>$curriculum,
                                          'section_specification'=>$specification,
                                          'sectionDesc'=>$sectionname,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                      ]);
      
                      return array((object)[
                          'status'=>1,
                          'data'=>'Created Successfully'
                      ]);

                  }
      
                 
      
              }catch(\Exception $e){

                  return $e;

                  return array((object)[
                      'status'=>0,
                      'data'=>'Something went wrong!'
                  ]);

              }

      }

      public static function update_section(Request $request){

            $sectionname = $request->get('sectionname');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $sectionid = $request->get('sectionid');

            try{

                  $check = DB::table('college_sections')
                              ->where('deleted',0)
                              ->where('sectionDesc',$sectionname)
                              ->where('syID',$syid)
                              ->where('semesterID',$semid)
                              ->where('id','!=',$sectionid)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'Section already exist.'
                        ]);
                  }

                  DB::table('college_sections')
                        ->where('deleted',0)
                        ->where('id',$sectionid)
                        ->where('syID',$syid)
                        ->where('semesterID',$semid)
                        ->update([
                              'sectionDesc'=>$sectionname,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
      
                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully'
                  ]);
      
              }catch(\Exception $e){

                  return array((object)[
                      'status'=>0,
                      'data'=>'Something went wrong!'
                  ]);

              }

      }
      

      public static function curriculum(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $courseid = $request->get('courseid');

            $search = $request->get('search');


            $curriculum = DB::table('college_curriculum')
                              ->where('courseID',$courseid)
                              ->where('deleted',0)
                              ->select(
                                    'id',
                                    'curriculumname',
                                    'curriculumname as text'
                              )
                              ->get();

            $curriculum_count = DB::table('college_curriculum')
                              ->where('courseID',$courseid)
                              ->where('deleted',0)
                              ->count();


            return @json_encode((object)[
                  "results"=>$curriculum,
                  "pagination"=>(object)[
                        "more"=>true
                  ],
                  "count_filtered"=>$courses_count
            ]);

            return $curriculum;

      }

      public static function courses(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $search = $request->get('search');

            if(Session::get('currentPortal') == 16){

                  $teacher = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->first();

                  $courses = DB::table('teacherprogramhead')
                                    ->where('teacherprogramhead.deleted',0)
                                    ->where('teacherprogramhead.syid',$syid)
                                    ->skip($request->get('page')*10)
                                    ->where('teacherid',$teacher->id)
                                    ->join('college_courses',function($join) use($search){
                                          $join->on('teacherprogramhead.courseid','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                          $join->where(function($query) use($search){
                                                if($search != null && $search != ""){
                                                      $query->orWhere('courseDesc','like','%'.$search.'%');
                                                      $query->orWhere('courseabrv','like','%'.$search.'%');
                                                }
                                          });
                                    })
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv',
                                          'courseDesc as text'
                                    )
                                    ->get();

            }else if(Session::get('currentPortal') == 14){

                  $teacher = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->first();

                  $courses = DB::table('teacherdean')
                              ->where('teacherdean.deleted',0)
                              ->where('teacherdean.syid',$syid)
                              ->where('teacherid',$teacher->id)
                              ->skip($request->get('page')*10)
                              ->join('college_colleges',function($join) use($search){
                                    $join->on('teacherdean.collegeid','=','college_colleges.id');
                                    $join->where('college_colleges.deleted',0);
                                    $join->where(function($query) use($search){
                                          if($search != null && $search != ""){
                                                $query->orWhere('courseDesc','like','%'.$search.'%');
                                                $query->orWhere('courseabrv','like','%'.$search.'%');
                                          }
                                    });
                              })
                              ->join('college_courses',function($join){
                                    $join->on('college_colleges.id','=','college_courses.collegeid');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->select(
                                    'college_courses.id',
                                    'college_courses.courseDesc',
                                    'college_courses.courseabrv',
                                    'courseDesc as text'
                              )
                              ->get();
            }else{


                  $courses = DB::table('college_courses')
                                    ->where('deleted',0)
                                    ->where(function($query) use($search){
                                          if($search != null && $search != ""){
                                                $query->orWhere('courseDesc','like','%'.$search.'%');
                                                $query->orWhere('courseabrv','like','%'.$search.'%');
                                          }
                                    })
                                    ->take(5)
                                    ->skip($request->get('page')*5)
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv',
                                          'courseDesc as text'
                                    )
                                    ->get();


                  $courses_count = DB::table('college_courses')
                                    ->where('deleted',0)
                                    ->where(function($query) use($search){
                                          if($search != null && $search != ""){
                                                $query->orWhere('courseDesc','like','%'.$search.'%');
                                                $query->orWhere('courseabrv','like','%'.$search.'%');
                                          }
                                    })
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv',
                                          'courseDesc as text'
                                    )
                                    ->count();

            }

            
            return @json_encode((object)[
                  "results"=>$courses,
                  "pagination"=>(object)[
                        "more"=>true
                  ],
                  "count_filtered"=>$courses_count
            ]);

            $temp_courses = array();

            foreach($courses as $item){

                  $item->text =     $item->courseabrv.' - '.$item->courseDesc;

                  $curriculum = DB::table('college_curriculum')
                                    ->where('courseID',$item->id)
                                    ->where('deleted',0)
                                    ->select(
                                          'id',
                                          'curriculumname',
                                          'curriculumname as text'
                                    )
                                    ->get();
                  $item->curriculum = $curriculum;
            }

            return $courses;
      }


      public static function collegesection_list_ajax(Request $request){

            $sectionid = $request->get('sectionid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $course = $request->get('course');

            return self::collegesection_list($request, $sectionid, $syid, $semid, $levelid, $course);
           

      }
     
      public static function collegesection_list(
            Request $request,
            $sectionid = null,
            $syid = null,
            $semid = null,
            $levelid = null,
            $course = null
      ){

            try{

                  $temp_courses = null;

                  if(Session::get('currentPortal') == 16){

                        $teacher = DB::table('teacher')
                                          ->where('tid',auth()->user()->email)
                                          ->first();
      
                        $courses = DB::table('teacherprogramhead')
                                          ->where('teacherprogramhead.deleted',0)
                                          ->where('teacherprogramhead.syid',$syid)
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

                        $temp_courses = array();
                        
                        foreach($courses as $item){
                              array_push( $temp_courses, $item->id);
                        }

                        if(count($temp_courses) == 0){
                              return array((object)[
                                    'status'=>0,
                                    'data'=>'No section found.',
                                    'info'=>array()
                              ]);
                        }

                  }else if(Session::get('currentPortal') == 14){
                  //dean

                        $teacher = DB::table('teacher')
                                          ->where('tid',auth()->user()->email)
                                          ->first();

                        $courses = DB::table('teacherdean')
                                          ->where('teacherdean.deleted',0)
                                          ->where('teacherdean.syid',$syid)
                                          ->where('teacherid',$teacher->id)
                                          ->join('college_colleges',function($join){
                                                $join->on('teacherdean.collegeid','=','college_colleges.id');
                                                $join->where('college_colleges.deleted',0);
                                          })
                                          ->join('college_courses',function($join){
                                                $join->on('college_colleges.id','=','college_courses.collegeid');
                                                $join->where('college_courses.deleted',0);
                                          })
                                          ->select(
                                                'college_courses.id',
                                                'college_courses.courseDesc',
                                                'college_courses.courseabrv'
                                          )
                                          ->get();

                        $temp_courses = array();
                        
                        foreach($courses as $item){
                              array_push( $temp_courses, $item->id);
                        }

                        if(count($temp_courses) == 0){
                              return array((object)[
                                    'status'=>0,
                                    'data'=>'No section found.',
                                    'info'=>array()
                              ]);
                        }

                  }else{
                        $courses = DB::table('college_courses')
                                          ->where('deleted',0)
                                          ->select(
                                                'college_courses.id',
                                                'college_courses.courseDesc',
                                                'college_courses.courseabrv'
                                          )
                                          ->get();
      
                        $temp_courses = array();
      
                        foreach($courses as $item){
                              array_push( $temp_courses, $item->id);
                        }

                        if(count($temp_courses) == 0){
                              return array((object)[
                                    'status'=>0,
                                    'data'=>'No section found.',
                                    'info'=>array()
                              ]);
                        }
                  }

                  $search = $request->get('search');
                  $search = $search['value'];

                  $sections = DB::table('college_sections')
                                    ->leftJoin('college_courses',function($join){
                                          $join->on('college_sections.courseID','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->leftJoin('college_colleges',function($join){
                                          $join->on('college_sections.collegeID','=','college_colleges.id');
                                          $join->where('college_colleges.deleted',0);
                                    })
                                    ->leftJoin('gradelevel',function($join){
                                          $join->on('college_sections.yearID','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->leftJoin('college_curriculum',function($join){
                                          $join->on('college_sections.curriculumid','=','college_curriculum.id');
                                          $join->where('college_curriculum.deleted',0);
                                    })
                                    ->where(function($query) use($search){
                                          $query->orWhere('courseDesc','like','%'.$search.'%');
                                          $query->orWhere('courseabrv','like','%'.$search.'%');
                                          $query->orWhere('sectionDesc','like','%'.$search.'%');
                                    })
                                    ->where('college_sections.deleted',0);

                  if($sectionid != null){
                        $sections = $sections->where('id',$sectionid);
                  }

                  if($syid != null){
                        $sections = $sections->where('syID',$syid);
                  }

                  if($semid != null){
                        $sections = $sections->where('semesterID',$semid);
                  }

                  if($levelid != null){
                        $sections = $sections->where('yearID',$levelid);
                  }

                  if($course != null){
                        $sections = $sections->where('college_sections.courseID',$course);
                  }

                  if($temp_courses != null){
                        $sections = $sections->whereIn('college_sections.courseID',$temp_courses);
                  }

                  $sections = $sections
                              ->take($request->get('length'))
                              ->skip($request->get('start'))
                              ->select(
                                    'curriculumname',
                                    'college_courses.courseDesc',
                                    'college_courses.courseabrv',
                                    'college_colleges.collegeDesc',
                                    'college_colleges.collegeabrv',
                                    'college_courses.courseabrv',
                                    'levelname',
                                    'college_sections.id',
                                    'college_sections.sectionDesc',
                                    'college_sections.yearID',
                                    'college_sections.semesterID',
                                    'college_sections.syID',
                                    'college_sections.courseID',
                                    'college_sections.collegeID',
                                    'college_sections.curriculumid',
                                    'college_sections.section_specification',
                                    'college_sections.issubjsched'
                              )
                              ->get();

                  $sections_count = DB::table('college_sections')
                                    ->leftJoin('college_courses',function($join){
                                          $join->on('college_sections.courseID','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->leftJoin('college_colleges',function($join){
                                          $join->on('college_sections.collegeID','=','college_colleges.id');
                                          $join->where('college_colleges.deleted',0);
                                    })
                                    ->leftJoin('gradelevel',function($join){
                                          $join->on('college_sections.yearID','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->leftJoin('college_curriculum',function($join){
                                          $join->on('college_sections.curriculumid','=','college_curriculum.id');
                                          $join->where('college_curriculum.deleted',0);
                                    })
                                    ->where(function($query) use($search){
                                          $query->orWhere('courseDesc','like','%'.$search.'%');
                                          $query->orWhere('courseabrv','like','%'.$search.'%');
                                          $query->orWhere('sectionDesc','like','%'.$search.'%');
                                    })
                                    ->where('college_sections.deleted',0);

                  if($sectionid != null){
                        $sections_count = $sections_count->where('id',$sectionid);
                  }

                  if($syid != null){
                        $sections_count = $sections_count->where('syID',$syid);
                  }

                  if($semid != null){
                        $sections_count = $sections_count->where('semesterID',$semid);
                  }

                  if($levelid != null){
                        $sections_count = $sections_count->where('yearID',$levelid);
                  }

                  if($course != null){
                        $sections_count = $sections_count->where('college_sections.courseID',$course);
                  }

                  if($temp_courses != null){
                        $sections_count = $sections_count->whereIn('college_sections.courseID',$temp_courses);
                  }

                  $sections_count = $sections_count->count();

                  $subjsched = array();
                  
                  if(collect($sections)->where('issubjsched',1)->count() > 0){

                        $subjsched = DB::table('college_classsched')
                                          ->where('sectionid',collect($sections)->where('issubjsched',1)->pluck('id'))
                                          ->where('deleted',0)
                                          ->select(
                                                'sectionID',
                                                'id'
                                                )
                                          ->get();
                  }

                  foreach($sections as $item){

                        $enrolled = DB::table('college_enrolledstud')
                                          ->join('studinfo',function($join){
                                                $join->on('college_enrolledstud.studid','=','studinfo.id');
                                                $join->where('college_enrolledstud.deleted',0);
                                          })
                                          ->leftJoin('college_courses',function($join){
                                                $join->on('college_enrolledstud.courseID','=','college_courses.id');
                                          })
                                          ->join('gradelevel',function($join){
                                                $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                          })
                                          ->where('college_enrolledstud.deleted',0)
                                          ->where('college_enrolledstud.syid',$item->syID)
                                          ->where('college_enrolledstud.semid',$item->semesterID)
                                          ->where('college_enrolledstud.sectionID',$item->id)
                                          ->select('lastname','firstname','middlename','suffix','courseabrv','levelname')
                                          ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                                          ->orderBy('lastname')
                                          ->get();

                        $item->schedid = null;

                        if(count($subjsched) != 0){
                              $schedsubj_detail = collect($subjsched)->where('sectionID',$item->id)->first();

                              if(isset($schedsubj_detail)){
                                    $item->schedid = $schedsubj_detail->id;
                              }
                        }

                        foreach($enrolled as $eitem){

                              $middlename = explode(" ",$eitem->middlename);
                              $temp_middle = '';
                              if($middlename != null){
                                    foreach ($middlename as $middlename_item) {
                                          if(strlen($middlename_item) > 0){
                                          $temp_middle .= $middlename_item[0].'.';
                                          } 
                                    }
                              }

                              $eitem->student = $eitem->lastname.', '.$eitem->firstname.' '.$eitem->suffix.' '.$temp_middle;
            
                        }

                        $item->enrolled = count($enrolled);
                        $item->students = $enrolled;
                        $item->search = 'CS'.$item->id.' '.'CC'.$item->courseID.' '.$item->courseDesc.' '.$item->sectionDesc.' '.$item->courseabrv;
                  }


                  return @json_encode((object)[
                        'data'=>$sections,
                        'recordsTotal'=>$sections_count,
                        'recordsFiltered'=>$sections_count
                  ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Successfull.',
                        'info'=>$sections
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

      public static function create_logs($message = null, $id = null){
           DB::table('logs') 
             ->insert([
                  'dataid'=>$id,
                  'module'=>4,
                  'message'=>$message,
                  'createdby'=>auth()->user()->id,
                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
      }

}
