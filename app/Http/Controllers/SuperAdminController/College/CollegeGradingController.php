<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use Session;

class CollegeGradingController extends \App\Http\Controllers\Controller
{


      public static function update_section(){

            $syid = 3;
            $semid = 1;

            $grades = DB::table('college_studentprospectus')
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->get();

            foreach($grades as $item){

                  $p_id = $item->prospectusID;
                  
                  $check_sched = DB::table('college_studsched')
                                    ->join('college_classsched',function($join) use($p_id){
                                          $join->on('college_studsched.schedid','=','college_classsched.id');
                                          $join->where('college_classsched.subjectID',$p_id);
                                    })
                                    ->where('studid',$item->studid)
                                    ->first();

                  if(isset($check_sched->sectionID)){
                        if($item->sectionid != $check_sched->sectionID){
                              DB::table('college_studentprospectus')
                                    ->where('studid',$item->studid)
                                    ->where('prospectusID',$p_id)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->take(1)
                                    ->update([
                                          'sectionid'=>$check_sched->sectionID
                                    ]);
                        }
                  }
            
            }

            return $grades;

      }


      //grade status
            // 1 - submitted
            // 2 - Dean Approve
            // 3 - Pending
            // 4 - Posted
            // 7 - Program Head Approved
            // 8 - INC
            // 9 - Dropped
            // 10 - Unpost


      public static function unpost_grades_ph(Request $request){
            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $term = $request->get('term');
                  $selected = $request->get('selected');
                  $termholder = $term;
                  $remarks = $request->get('remarks');
                  
                  if($term == "prelemgrade"){
                        $term = 'prelemstatus';
                  }else if($term == "midtermgrade"){
                        $term = 'midtermstatus';
                  }else if($term == "prefigrade"){
                        $term = 'prefistatus';
                  }else if($term == "finalgrade"){
                        $term = 'finalstatus';
                  }
                  DB::table('college_studentprospectus')
                        ->whereIn('id',$selected)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where(function($query) use($term){
                              $query->where($term,4);
                              $query->orWhere($term,7);
                        })
                        ->update([
                              $term => 10,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  $reflist = DB::table('college_studentprospectus')
                              ->whereIn('id',$selected)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereIn($term,[10])
                              ->select([
                                    'id',
                                    'prelimstatref',
                                    'midstatref',
                                    'prefistatref',
                                    'finaltermstatref',
                              ])
                              ->get();


                  foreach($selected as $item){

                        if($termholder == "prelemstatus" || $termholder == "prelemgrade"){
                              $refterm = 'prelimstatref';
                        }else if($termholder == "midtermstatus" || $termholder == "midtermgrade"){
                              $refterm = 'midstatref';
                        }else if($termholder == "prefigrade" || $termholder == "prefigrade"){
                              $refterm = 'prefistatref';
                        }else if($termholder == "finalstatus" || $termholder == "finalgrade"){
                              $refterm = 'finaltermstatref';
                        }

                        $refid = collect($reflist)->where('id',$item)->values();

                        
                        $remarkinfo = collect($remarks)->where('id',$item)->first();
                        $remarks_text = isset( $remarkinfo['remarks']) ? $remarkinfo['remarks'] : '';

                        if(count($refid) > 0){
                              $refid =  $refid[0]->$refterm;
                              if($refid == null){
                                    $refid = self::create_college_studentprosstat($termholder,$item,$syid,$semid,$refterm);
                              }
                              DB::table('college_studentprosstat')
                                    ->where('id',$refid)
                                    ->take(1)
                                    ->update([
                                          'unpoststat'=>1,
                                          'unpostby'=>auth()->user()->id,
                                          'unpoststatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                              
                  }

                  return array((object)[
                        'status'=>1,
                  ]);
            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0
                  ]);
            }
            
      }

      public static function pending_grades_ph(Request $request){
            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $term = $request->get('term');
                  $selected = $request->get('selected');
                  $termholder = $term;
                  $remarks = $request->get('remarks');
               
                  if($term == "prelemgrade"){
                        $term = 'prelemstatus';
                  }else if($term == "midtermgrade"){
                        $term = 'midtermstatus';
                  }else if($term == "prefigrade"){
                        $term = 'prefistatus';
                  }else if($term == "finalgrade"){
                        $term = 'finalstatus';
                  }
                  DB::table('college_studentprospectus')
                        ->whereIn('id',$selected)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where(function($query) use($term){
                              $query->where($term,1);
                              $query->orWhere($term,7);
                              $query->orWhere($term,2);
                              $query->orWhere($term,4);
                              $query->orWhere($term,10);
                        })
                        ->update([
                              $term => 3,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  $reflist = DB::table('college_studentprospectus')
                              ->whereIn('id',$selected)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereIn($term,[3])
                              ->select([
                                    'id',
                                    'prelimstatref',
                                    'midstatref',
                                    'prefistatref',
                                    'finaltermstatref',
                              ])
                              ->get();


                  foreach($selected as $item){

                        if($termholder == "prelemstatus" || $termholder == "prelemgrade"){
                              $refterm = 'prelimstatref';
                        }else if($termholder == "midtermstatus" || $termholder == "midtermgrade"){
                              $refterm = 'midstatref';
                        }else if($termholder == "prefistatus" || $termholder == "prefigrade"){
                              $refterm = 'prefistatref';
                        }else if($termholder == "finalstatus" || $termholder == "finalgrade"){
                              $refterm = 'finaltermstatref';
                        }

                        $refid = collect($reflist)->where('id',$item)->values();

                       
                        $remarkinfo = collect($remarks)->where('id',$item)->first();
                        $remarks_text = isset( $remarkinfo['remarks']) ? $remarkinfo['remarks'] : '';

                        if(count($refid) > 0){
                              $refid =  $refid[0]->$refterm;
                              if($refid == null){
                                    $refid = self::create_college_studentprosstat($termholder,$item,$syid,$semid,$refterm);
                              }
                              DB::table('college_studentprosstat')
                                    ->where('id',$refid)
                                    ->take(1)
                                    ->update([
                                          'pendcom'=>$remarks_text,
                                          'pendstat'=>1,
                                          'pendby'=>auth()->user()->id,
                                          'pendstatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                              
                  }

                  return array((object)[
                        'status'=>1,
                  ]);
            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0
                  ]);
            }
            
      }


      public static function approve_grades_ph(Request $request){
            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $term = $request->get('term');
                  $selected = $request->get('selected');
                  $termholder = $term;
                
                  if($term == "prelemgrade"){
                      $term = 'prelemstatus';
                  }else if($term == "midtermgrade"){
                      $term = 'midtermstatus';
                  }else if($term == "prefigrade"){
                      $term = 'prefistatus';
                  }else if($term == "finalgrade"){
                      $term = 'finalstatus';
                  }

                  if(Session::get('currentPortal') == 14){

                        DB::table('college_studentprospectus')
                        ->whereIn('id',$selected)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->whereIn($term,[1,7,3,10])
                        ->update([
                              $term => 2,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  }else{
                      
                        if(Session::get('currentPortal') == 3 || Session::get('currentPortal') == 17 ){
                              
                              DB::table('college_studentprospectus')
                                    ->whereIn('id',$selected)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where(function($query) use($term){
                                          $query->whereIn($term,[1,7,3,10]);
                                          $query->orWhereNull($term);
                                    })
                                    ->update([
                                          $term => 7,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }else{
                              DB::table('college_studentprospectus')
                                    ->whereIn('id',$selected)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->whereIn($term,[1,7,3,10])
                                    ->update([
                                          $term => 7,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                              
                  }

                  
                  $reflist = DB::table('college_studentprospectus')
                              ->whereIn('id',$selected)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereIn($term,[2,7])
                              ->select([
                                    'id',
                                    'prelimstatref',
                                    'midstatref',
                                    'prefistatref',
                                    'finaltermstatref',
                              ])
                              ->get();

                  // return $reflist;

                  foreach($selected as $item){

                        if($termholder == "prelemstatus" || $termholder == "prelemgrade"){
                              $refterm = 'prelimstatref';
                        }else if($termholder == "midtermstatus" || $termholder == "midtermgrade"){
                              $refterm = 'midstatref';
                        }else if($termholder == "prefistatus" || $termholder == "prefigrade"){
                              $refterm = 'prefistatref';
                        }else if($termholder == "finalstatus" || $termholder == "finalgrade"){
                              $refterm = 'finaltermstatref';
                        }

                        $refid = collect($reflist)->where('id',$item)->values();

                       

                        if(count($refid) > 0){
                              $refid =  $refid[0]->$refterm;

                              if($refid != null){
                                    $check_status = DB::table('college_studentprosstat')
                                                      ->where('id',$refid)
                                                      ->first();

                                    if($check_status->pendstat == 1){
                                          $refid = null;
                                    }
                              }

                              if($refid == null){
                                    $refid = self::create_college_studentprosstat($termholder,$item,$syid,$semid,$refterm);
                              }

                              DB::table('college_studentprosstat')
                                    ->where('id',$refid)
                                    ->take(1)
                                    ->update([
                                          'appstat'=>1,
                                          'appby'=>auth()->user()->id,
                                          'appstatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                              
                  }




                  return array((object)[
                      'status'=>1,
                  ]);
            }catch(\Exception $e){
                  return $e;
                  return array((object)[
                        'status'=>0
                  ]);
            }
      }

      public static function approve_grades_dean(Request $request){
            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $term = $request->get('term');
                  $selected = $request->get('selected');
                  $termholder = $term;

                  if($term == "prelemgrade"){
                      $term = 'prelemstatus';
                  }else if($term == "midtermgrade"){
                      $term = 'midtermstatus';
                  }else if($term == "prefigrade"){
                      $term = 'prefistatus';
                  }else if($term == "finalgrade"){
                      $term = 'finalstatus';
                  }
                  
                  DB::table('college_studentprospectus')
                      ->whereIn('id',$selected)
                      ->where('syid',$syid)
                      ->where('semid',$semid)
                      ->update([
                          $term => 2,
                          'updatedby'=>auth()->user()->id,
                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                      ]);

                  $reflist = DB::table('college_studentprospectus')
                              ->whereIn('id',$selected)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereIn($term,[2])
                              ->select([
                                    'id',
                                    'prelimstatref',
                                    'midstatref',
                                    'prefistatref',
                                    'finaltermstatref',
                              ])
                              ->get();


                  foreach($selected as $item){

                        if($termholder == "prelemstatus" || $termholder == "prelemgrade"){
                              $refterm = 'prelimstatref';
                        }else if($termholder == "midtermstatus" || $termholder == "midtermgrade"){
                              $refterm = 'midstatref';
                        }else if($termholder == "prefistatus" || $termholder == "prefigrade"){
                              $refterm = 'prefistatref';
                        }else if($termholder == "finalstatus" || $termholder == "finalgrade"){
                              $refterm = 'finaltermstatref';
                        }

                        $refid = collect($reflist)->where('id',$item)->values();

                        if(count($refid) > 0){

                              $refid = $refid[0]->$refterm;

                              if($refid != null){
                                    $check_status = DB::table('college_studentprosstat')
                                                      ->where('id',$refid)
                                                      ->first();

                                    if($check_status->pendstat == 1){
                                          $refid = null;
                                    }
                              }


                              if($refid == null){
                                    $refid = self::create_college_studentprosstat($termholder,$item,$syid,$semid,$refterm);
                              }

                              DB::table('college_studentprosstat')
                                    ->where('id',$refid)
                                    ->take(1)
                                    ->update([
                                          'appstat'=>1,
                                          'appby'=>auth()->user()->id,
                                          'appstatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }

                  }

                      
                  return array((object)[
                      'status'=>1,
                  ]);
            }catch(\Exception $e){
                  return $e;
                  return array((object)[
                        'status'=>0
                  ]);
            }
      }

      public static function post_grades_dean(Request $request){
            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $term = $request->get('term');
                  $selected = $request->get('selected');
                  $termholder = $term;

                  if($term == "prelemgrade"){
                      $term = 'prelemstatus';
                  }else if($term == "midtermgrade"){
                      $term = 'midtermstatus';
                  }else if($term == "prefigrade"){
                      $term = 'prefistatus';
                  }else if($term == "finalgrade"){
                      $term = 'finalstatus';
                  }
                  
                  DB::table('college_studentprospectus')
                      ->whereIn('id',$selected)
                      ->where('syid',$syid)
                      ->where('semid',$semid)
                      ->update([
                          $term => 4,
                          'updatedby'=>auth()->user()->id,
                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                      ]);

                  $reflist = DB::table('college_studentprospectus')
                              ->whereIn('id',$selected)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereIn($term,[4])
                              ->select([
                                    'id',
                                    'prelimstatref',
                                    'midstatref',
                                    'prefistatref',
                                    'finaltermstatref',
                              ])
                              ->get();


                  foreach($selected as $item){

                        if($termholder == "prelemstatus" || $termholder == "prelemgrade"){
                              $refterm = 'prelimstatref';
                        }else if($termholder == "midtermstatus" || $termholder == "midtermgrade"){
                              $refterm = 'midstatref';
                        }else if($termholder == "prefistatus" || $termholder == "prefigrade"){
                              $refterm = 'prefistatref';
                        }else if($termholder == "finalstatus" || $termholder == "finalgrade"){
                              $refterm = 'finaltermstatref';
                        }



                        $refid = collect($reflist)->where('id',$item)->values();

                        if(count($refid) > 0){

                              $refid = $refid[0]->$refterm;

                              if($refid != null){
                                    $check_status = DB::table('college_studentprosstat')
                                                      ->where('id',$refid)
                                                      ->first();

                                    if($check_status->pendstat == 1){
                                          $refid = null;
                                    }
                              }


                              if($refid == null){
                                    $refid = self::create_college_studentprosstat($termholder,$item,$syid,$semid,$refterm);
                              }

                              DB::table('college_studentprosstat')
                                    ->where('id',$refid)
                                    ->take(1)
                                    ->update([
                                          'poststat'=>1,
                                          'postby'=>auth()->user()->id,
                                          'poststatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                              
                  }

                  return array((object)[
                      'status'=>1,
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0
                  ]);
            }
      }

      public static function create_college_studentprosstat($termholder,$selected,$syid,$semid,$refterm){

            $refid = DB::table('college_studentprosstat')
                        ->insertGetId([
                              'term'=>str_replace('grade','',$termholder),
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'headerid'=>$selected
                        ]);

            DB::table('college_studentprospectus')
                    ->where('id',$selected)
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->take(1)
                    ->update([
                        $refterm => $refid
                    ]);

            return $refid;
      }

      public static function all_grades(Request $request){

            $syid = 11;
            $semid = 1;
            $courseid = $request->get('courseid');

            $enrolled = DB::table('college_enrolledstud')
                              ->where('college_enrolledstud.syid',$syid)
                              ->where('college_enrolledstud.semid',$semid)
                              ->where('college_enrolledstud.deleted',0)
                              ->select(
                                    'studid'
                              )
                              ->get();

            $courses = DB::table('college_courses')
                              ->where('id',$courseid)
                              ->where('deleted',0)
                              ->get();

            foreach($courses as $course){

                  $enrolled = DB::table('college_enrolledstud')
                        ->where('courseID',$course->id)
                        ->where('college_enrolledstud.syid',$syid)
                        ->where('college_enrolledstud.semid',$semid)
                        ->where('college_enrolledstud.deleted',0)
                        ->select(
                              'studid'
                        )
                        ->get();

                  $students = array();

                  foreach($enrolled as $item){
                        array_push($students,$item->studid);
                  }

                  $student_sched = Db::table('college_studsched')
                                          ->join('college_classsched',function($join) use($syid,$semid){
                                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                                $join->where('college_classsched.deleted',0);
                                                $join->where('college_classsched.syid',$syid);
                                                $join->where('college_classsched.semid',$semid);
                                          })
                                          ->whereIn('studid',$students)
                                          ->where('college_studsched.deleted',0)
                                          ->select(
                                                'college_studsched.studid',
                                                'college_classsched.subjectID'      
                                          )
                                          ->get();

                  $student_grade = Db::table('college_studentprospectus')
                                          ->whereIn('studid',$students)
                                          ->where('college_studentprospectus.deleted',0)
                                          ->select(
                                                'college_studentprospectus.id',
                                                'college_studentprospectus.studid',
                                                'college_studentprospectus.prospectusID'
                                          )
                                          ->get();

                  
                  foreach($enrolled as $item){
                        $temp_sched = collect($student_sched)->where('studid',$item->studid)->values();
                  }
                

            }

            return $student_sched;

      }

      public static function enrolled_students(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $enrolled = DB::table('college_enrolledstud')
                                    ->join('studinfo',function($join) {
                                          $join->on('college_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('college_courses',function($join){
                                          $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->where('college_enrolledstud.syid',$syid)
                                    ->where('college_enrolledstud.semid',$semid)
                                    ->where('college_enrolledstud.deleted',0)
                                    ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                                    ->select(
                                          'yearLevel as levelid',
                                          'courseabrv',
                                          'gender',
                                          'sid',
                                          'college_enrolledstud.studid',
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'college_enrolledstud.sectionid',
                                          DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                                    )
                                    ->orderBy('studentname')
                                    ->orderBy('studentname')
                                    ->get();

            return $enrolled;

      }

      public static function college_sections(Request $request){

            $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->select('id')
                        ->first()
                        ->id;

            $courseid = $request->get('courseid');

            if(Session::get('currentPortal') == 14){

                  $cp_course = DB::table('college_colleges')
                                    ->join('college_courses',function($join){
                                          $join->on('college_colleges.id','=','college_courses.collegeid');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->where('college_colleges.deleted',0)
                                    ->where('college_colleges.dean',$teacherid)
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv'
                                          )
                                    ->get();
            }else{
                  $cp_course = DB::table('college_courses')
                                    ->where('courseChairman',$teacherid)
                                    ->select('id','courseDesc','courseabrv')
                                    ->get();
            }

         

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $array_course = array();

            foreach($cp_course as $item){
                  array_push($array_course,$item->id);
            }


            $schedule = DB::table('college_sections')
                              ->join('college_classsched',function($join){
                                    $join->on('college_sections.id','=','college_classsched.sectionID');
                                    $join->where('college_classsched.deleted',0);
                              })
                              ->join('college_prospectus',function($join){
                                    $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                    $join->where('college_prospectus.deleted',0);
                              })
                              ->where('college_sections.deleted',0)
                              ->where('college_sections.syID',$syid)
                              ->where('college_sections.semesterID',$semid)
                              ->whereIn('college_sections.courseID',$array_course)
                              ->select(
                                    'teacherID',
                                    'college_classsched.subjectID',
                                    'college_classsched.id',
                                    'college_classsched.sectionID',
                                    'subjDesc',
                                    'subjCode'
                              )
                              ->get();


            $sections = DB::table('college_sections')
                              ->join('gradelevel',function($join) use($syid,$semid){
                                    $join->on('college_sections.yearID','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('college_sections.deleted',0)
                              ->where('college_sections.syID',$syid)
                              ->where('college_sections.semesterID',$semid)
                              ->whereIn('college_sections.courseID',$array_course)
                              ->select(
                                    'gradelevel.levelname',
                                    'college_sections.sectionDesc',
                                    'college_sections.id'
                              )
                              ->get();

            return array((object)[
                  'sections'=>$sections,
                  'sectionsched'=> $schedule
            ]);

      }

      public static function college_teachers(Request $request){

            
            $teacher = Db::table('teacher')
                        ->where('teacher.deleted',0)
                        ->select(
                              'id',
                              'tid',
                              'lastname',
                              'firstname',
                              'middlename',
                              DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as teachername")
                        )
                        ->get();           


            return $teacher;
      }

      public static function college_subjects(Request $request){

            $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->select('id')
                        ->first()
                        ->id;

            $courseid = $request->get('courseid');

            $array_course = array();

            $syid = $request->get('syid');
            $semid = $request->get('semid');

           
            if(Session::get('currentPortal') == 14){
                  $cp_course = DB::table('college_colleges')
                                    ->join('college_courses',function($join){
                                          $join->on('college_colleges.id','=','college_courses.collegeid');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->where('college_colleges.deleted',0)
                                    ->where('college_colleges.dean',$teacherid)
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv'
                                          )
                                    ->get();
            }else{
                  $cp_course = DB::table('college_courses')
                                    ->where('courseChairman',$teacherid)
                                    ->select('id','courseDesc','courseabrv')
                                    ->get();
            }

            foreach($cp_course as $item){
                  array_push($array_course,$item->id);
            }
            

            $prospectus = DB::table('college_prospectus')
                              ->whereIn('courseID',$array_course)
                              ->where('semesterID', $semid)
                              ->join('college_courses',function($join) use($syid,$semid){
                                    $join->on('college_prospectus.courseID','=','college_courses.id');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($syid,$semid){
                                    $join->on('college_prospectus.yearID','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->select(
                                    'levelname',
                                    'courseabrv',
                                    'college_prospectus.subjectID',
                                    'college_prospectus.id',
                                    'college_prospectus.subjCode',
                                    'college_prospectus.subjDesc'
                              )
                              ->get();

            return $prospectus;

      }


      public static function college_studsched(Request $request){

            $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->select('id')
                        ->first()
                        ->id;

            $courseid = $request->get('courseid');

            if(Session::get('currentPortal') == 14){
                  $cp_course = DB::table('college_colleges')
                                    ->join('college_courses',function($join) use($courseid){
                                          $join->on('college_colleges.id','=','college_courses.collegeid');
                                          $join->where('college_courses.deleted',0);
                                          $join->where('college_courses.id',$courseid);
                                    })
                                    ->where('college_colleges.deleted',0)
                                    ->where('college_colleges.dean',$teacherid)
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv'
                                          )
                                    ->get();
            }else{
                  $cp_course = DB::table('college_courses')
                                    ->where('courseChairman',$teacherid)
                                    ->where('id',$courseid)
                                    ->select('id','courseDesc','courseabrv')
                                    ->get();
            }

           

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $array_course = array();

            foreach($cp_course as $item){
                  array_push($array_course,$item->id);
            }

            $temp_sched = Db::table('college_sections')
                        ->join('college_classsched',function($join) use($syid,$semid){
                              $join->on('college_sections.id','=','college_classsched.sectionID');
                              $join->where('college_classsched.deleted',0);
                              $join->where('college_classsched.syID',$syid);
                              $join->where('college_classsched.semesterID',$semid);
                        })
                        ->join('college_prospectus',function($join){
                              $join->on('college_classsched.subjectID','=','college_prospectus.id');
                              $join->where('college_prospectus.deleted',0);
                        })
                        ->where('college_sections.courseID',$courseid)
                        ->where('college_sections.syID',$syid)
                        ->where('college_sections.semesterID',$semid)
                        ->where('college_sections.deleted',0)
                        ->select('college_classsched.id')
                        ->get();

            $sched_array = array();

            foreach($temp_sched as $item){
            array_push($sched_array,$item->id);
            }

            $student_sched = Db::table('college_studsched')
                        ->where('college_studsched.deleted',0)
                        ->whereIn('schedid',$sched_array)
                        ->join('college_enrolledstud',function($join)  use($syid,$semid){
                              $join->on('college_studsched.studid','=','college_enrolledstud.studid');
                              $join->where('college_enrolledstud.deleted',0);
                              $join->where('college_enrolledstud.syid',$syid);
                              $join->where('college_enrolledstud.semid',$semid);
                              $join->whereIn('studstatus',[1,2,3]);
                        })
                        ->join('college_classsched',function($join) use($syid,$semid){
                              $join->on('college_studsched.schedid','=','college_classsched.id');
                              $join->where('college_classsched.deleted',0);
                              $join->where('college_classsched.syID',$syid);
                              $join->where('college_classsched.semesterID',$semid);
                        })
                        ->join('college_prospectus',function($join){
                              $join->on('college_classsched.subjectID','=','college_prospectus.id');
                              $join->where('college_prospectus.deleted',0);
                        })
                        ->select(
                              'schedid',
                              'college_studsched.studid',
                              'college_classsched.subjectID',
                              'college_prospectus.subjectID as subjid'
                        )
                        ->distinct('studid')
                        ->get();

            return  $student_sched;

      }

      public static function student_grades(Request $request){
            
            $teacherid = DB::table('teacher')
                              ->where('tid',auth()->user()->email)
                              ->select('id')
                              ->first()
                              ->id;

            $courseid = $request->get('courseid');

            // if(Session::get('currentPortal') == 14){
            //       $cp_course = DB::table('college_colleges')
            //                         ->join('college_courses',function($join) use($courseid){
            //                               $join->on('college_colleges.id','=','college_courses.collegeid');
            //                               $join->where('college_courses.deleted',0);
            //                               $join->where('college_courses.id',$courseid);
            //                         })
            //                         ->where('college_colleges.deleted',0)
            //                         ->where('college_colleges.dean',$teacherid)
            //                         ->select(
            //                               'college_courses.id',
            //                               'college_courses.courseDesc',
            //                               'college_courses.courseabrv'
            //                               )
            //                         ->get();
            // }else{
                  $cp_course = DB::table('college_courses')
                                    // ->where('courseChairman',$teacherid)
                                    ->where('id',$courseid)
                                    ->select('id','courseDesc','courseabrv')
                                    ->get();
            // }

            // $cp_course = DB::table('college_courses')
            //                   ->where('courseChairman',$teacherid)
            //                   ->where('id',$courseid)
            //                   ->select('id','courseDesc','courseabrv')
            //                   ->get();

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $array_course = array();

            foreach($cp_course as $item){
                  array_push($array_course,$item->id);
            }

            $student_grade = Db::table('college_studentprospectus')
                                    ->join('college_studsched',function($join) use($syid,$semid){
                                          $join->on('college_studsched.studid','=','college_studentprospectus.studid');
                                          $join->where('college_studsched.deleted',0);
                                    })
                                    ->join('college_classsched',function($join) use($syid,$semid){
                                          $join->on('college_classsched.id','=','college_studsched.schedid');
                                          $join->on('college_classsched.subjectID','=','college_studentprospectus.prospectusID');
                                          $join->where('college_studsched.deleted',0);
                                          $join->where('college_classsched.syid',$syid);
                                          $join->where('college_classsched.semesterID',$semid);
                                    })
                                    ->join('college_prospectus',function($join) use($syid,$semid){
                                          $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                          $join->where('college_prospectus.deleted',0);
                                    })
                                    ->whereIn('college_studentprospectus.courseid',$array_course)
                                    ->where('college_studentprospectus.deleted',0)
                                    ->where('college_studentprospectus.syid',$syid)
                                    ->where('college_studentprospectus.semid',$semid)
                                    ->select(
                                          'college_prospectus.subjectID as subjid',
                                          'college_classsched.sectionID',
                                          'prospectusID',
                                          'college_studentprospectus.id',
                                          'college_studentprospectus.studid',
                                          'college_studentprospectus.prelemgrade',
                                          'college_studentprospectus.midtermgrade',
                                          'college_studentprospectus.prefigrade',
                                          'college_studentprospectus.finalgrade',
                                          'college_studentprospectus.prelemstatus',
                                          'college_studentprospectus.midtermstatus',
                                          'college_studentprospectus.prefistatus',
                                          'college_studentprospectus.finalstatus'
                                    )
                                    ->get();

            return $student_grade;

      }



      public static function section_ajax(Request $request){

            $sectionid = $request->get('sectionid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $course = $request->get('course');

            return self::section($sectionid, $syid, $semid, $levelid, $course);
            
      }

      public static function section(
            $sectionid = null,
            $syid = null,
            $semid = null,
            $levelid = null,
            $course = null
      ){

            $temp_courses = null;

            if(auth()->user()->type == 16){
            //chairperson

                  $teacher = DB::table('teacher')
                                    ->where('userid',auth()->user()->id)
                                    ->first();

                  $courses = DB::table('college_courses')
                                    ->join('college_colleges',function($join){
                                          $join->on('college_courses.collegeid','=','college_colleges.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->where('courseChairman',$teacher->id)
                                    ->where('college_courses.deleted',0)
                                    ->select('college_courses.id','courseDesc','collegeDesc')
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

            }else if(auth()->user()->type == 14){
            //dean

                  $teacher = DB::table('teacher')
                                    ->where('userid',auth()->user()->id)
                                    ->first();

                  $courses = DB::table('college_colleges')
                                    ->join('college_courses',function($join){
                                          $join->on('college_colleges.id','=','college_courses.collegeid');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->where('dean',$teacher->id)
                                    ->where('college_colleges.deleted',0)
                                    ->select('college_courses.*')
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

            $sections = DB::table('college_sections')
                              ->leftJoin('college_courses',function($join){
                                    $join->on('college_sections.courseID','=','college_courses.id');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->leftJoin('gradelevel',function($join){
                                    $join->on('college_sections.yearID','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
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
                  $sections = $sections->where('courseID',$course);
            }

            if($temp_courses != null){
                  $sections = $sections->whereIn('courseID',$temp_courses);
            }

            $sections = $sections
                        ->select(
                              'college_sections.id',
                              'sectionDesc',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv',
                              'levelname'
                        )
                        ->get();

            foreach($sections as $item){


                  $subjects = DB::table('college_classsched')
                                    ->join('college_prospectus',function($join){
                                          $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                          $join->where('college_prospectus.deleted',0);
                                    })
                                    ->leftJoin('teacher',function($join){
                                          $join->on('college_classsched.teacherid','=','teacher.id');
                                          $join->where('teacher.deleted',0);
                                    })
                                    ->where('college_classsched.deleted',0)
                                    ->where('sectionID',$item->id)
                                    ->select(
                                          'college_classsched.id',
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'subjDesc',
                                          'subjCode',
                                          'lecunits',
                                          'labunits'
                                    )
                                    ->get();


                  $item->subjects  = $subjects;

            }
      

            return $sections;

      }

}
