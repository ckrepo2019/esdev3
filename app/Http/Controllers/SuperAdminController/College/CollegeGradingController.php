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

                  $student_sched = Db::table('college_loadsubject')
                                          ->join('college_classsched',function($join) use($syid,$semid){
                                                $join->on('college_loadsubject.schedid','=','college_classsched.id');
                                                $join->where('college_classsched.deleted',0);
                                                $join->where('college_classsched.syid',$syid);
                                                $join->where('college_classsched.semid',$semid);
                                          })
                                          ->whereIn('studid',$students)
                                          ->where('college_loadsubject.deleted',0)
                                          ->select(
                                                'college_loadsubject.studid',
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
            $courseid = $request->get('courseid');
            $gradelevel = $request->get('gradelevel');

            $enrolled = DB::table('college_enrolledstud')
                                    ->join('studinfo',function($join) {
                                          $join->on('college_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('college_courses',function($join){
                                          $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->join('college_loadsubject',function($join){
                                          $join->on('college_enrolledstud.studid','=','college_loadsubject.studid');
                                    })
                                    ->when($courseid, function ($query) use ($courseid) {
                                          $query->where('college_enrolledstud.courseid', $courseid);
                                    })
                                    ->when($gradelevel, function ($query) use ($gradelevel) {
                                          $query->where('college_enrolledstud.yearLevel', $gradelevel);
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
            $gradelevel = $request->get('gradelevel');

            // if(Session::get('currentPortal') == 14){

            //       $cp_course = DB::table('college_colleges')
            //                         ->join('college_courses',function($join){
            //                               $join->on('college_colleges.id','=','college_courses.collegeid');
            //                               $join->where('college_courses.deleted',0);
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
            //       $cp_course = DB::table('college_courses')
            //                         ->where('courseChairman',$teacherid)
            //                         ->select('id','courseDesc','courseabrv')
            //                         ->get();
            // }

            if($courseid == ''){

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
                  // $cp_course = DB::table('college_courses')
                  //                   ->where('courseChairman',$teacherid)
                  //                   ->select('id','courseDesc','courseabrv')
                  //                   ->get();
                  $cp_course = [(object)['id' => $courseid]];

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
                              ->join('college_instructor',function($join){
                                    $join->on('college_classsched.id','=','college_instructor.classschedID');
                                    $join->where('college_instructor.deleted',0);
                              })
                              ->where('college_sections.deleted',0)
                              ->where('college_sections.syID',$syid)
                              ->where('college_sections.semesterID',$semid)
                              ->when($gradelevel, function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', $gradelevel);
                              })
                              ->whereIn('college_sections.courseID',$array_course)
                              ->select(
                                    DB::raw('DISTINCT college_instructor.teacherID'),
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
                              ->when($gradelevel, function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', $gradelevel);
                              })
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
            $gradelevel = $request->get('gradelevel');

            $array_course = array();

            $syid = $request->get('syid');
            $semid = $request->get('semid');


            if($courseid == ''){

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
                  // $cp_course = DB::table('college_courses')
                  //                   ->where('courseChairman',$teacherid)
                  //                   ->select('id','courseDesc','courseabrv')
                  //                   ->get();
                  $cp_course = [(object)['id' => $courseid]];

            }


           
            // if(Session::get('currentPortal') == 14){
            //       $cp_course = DB::table('college_colleges')
            //                         ->join('college_courses',function($join){
            //                               $join->on('college_colleges.id','=','college_courses.collegeid');
            //                               $join->where('college_courses.deleted',0);
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
            //       $cp_course = DB::table('college_courses')
            //                         ->where('courseChairman',$teacherid)
            //                         ->select('id','courseDesc','courseabrv')
            //                         ->get();
            // }

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
                              ->when($gradelevel, function ($query) use ($gradelevel) {
                                    $query->where('college_prospectus.yearID', $gradelevel);
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
            $gradelevel = $request->get('gradelevel');

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
                        ->when($courseid, function ($query) use ($courseid) {
                              $query->where('college_sections.courseID',$courseid);
                        })
                        ->where('college_sections.syID',$syid)
                        ->where('college_sections.semesterID',$semid)
                        ->where('college_sections.deleted',0)
                        ->select('college_classsched.id')
                        ->get();
            $sched_array = array();

            foreach($temp_sched as $item){
            array_push($sched_array,$item->id);
            }

            $student_sched = Db::table('college_loadsubject')
                        ->where('college_loadsubject.deleted',0)
                        ->whereIn('schedid',$sched_array)
                        ->join('college_enrolledstud',function($join)  use($syid,$semid,$gradelevel){
                              $join->on('college_loadsubject.studid','=','college_enrolledstud.studid');
                              $join->where('college_enrolledstud.deleted',0);
                              $join->where('college_enrolledstud.syid',$syid);
                              $join->where('college_enrolledstud.semid',$semid);
                              $join->whereIn('studstatus',[1,2,3]);
                              $join->when($gradelevel, function ($query) use ($gradelevel) {
                                    $query->where('college_enrolledstud.yearLevel', $gradelevel);
                              });
                        })
                        ->join('college_classsched',function($join) use($syid,$semid){
                              $join->on('college_loadsubject.schedid','=','college_classsched.id');
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
                              'college_loadsubject.studid',
                              'college_classsched.subjectID as subjid',
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
            $gradelevel = $request->get('gradelevel');

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

            $student_grade = Db::table('college_stud_term_grades')
                                    ->join('studinfo',function($join){
                                          $join->on('studinfo.id','=','college_stud_term_grades.studid');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('college_loadsubject',function($join) use($syid,$semid){
                                          $join->on('college_loadsubject.studid','=','studinfo.id');
                                          // $join->where('college_loadsubject.deleted',0);
                                    })

                                    ->join('college_classsched',function($join) use($syid,$semid){
                                          $join->on('college_classsched.id','=','college_stud_term_grades.schedid');
                                          $join->where('college_classsched.deleted',0);
                                          $join->where('college_classsched.syid',$syid);
                                          $join->where('college_classsched.semesterID',$semid);
                                    })
                                    ->join('college_sections',function($join) use($syid,$semid,$courseid,$gradelevel){
                                          $join->on('college_classsched.sectionID','=','college_sections.id');
                                          $join->when($courseid != '', function ($query) use ($courseid) {
                                                $query->where('college_sections.courseID', '=', $courseid);
                                          });
                                          $join->when($gradelevel != '', function ($query) use ($gradelevel) {
                                                $query->where('college_sections.yearID', '=', $gradelevel);
                                          });
                                          $join->where('college_sections.deleted',0);
                                          $join->where('college_sections.syID',$syid);
                                          $join->where('college_sections.semesterID',$semid);
                                    })
                                    ->join('college_prospectus',function($join) use($syid,$semid){
                                          $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                          $join->where('college_prospectus.deleted',0);
                                    })
                                    ->where('college_stud_term_grades.deleted',0)
                                    ->groupBy('college_stud_term_grades.studid')
                                    ->select(
                                          'college_prospectus.id as subjid',
                                          'college_classsched.sectionID',
                                          'college_stud_term_grades.id',
                                          'college_loadsubject.studid',
                                          'college_stud_term_grades.prelim_grade',
                                          'college_stud_term_grades.midterm_grade',
                                          'college_stud_term_grades.prefinal_grade',
                                          'college_stud_term_grades.final_grade',
                                          'college_stud_term_grades.prelim_status',
                                          'college_stud_term_grades.midterm_status',
                                          'college_stud_term_grades.prefinal_status',
                                          'college_stud_term_grades.final_status'
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

      public function grade_status_subject(Request $request){
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $teacher = $request->get('teacher');
            $course = $request->get('courseid');
            $usertype = auth()->user()->type;
            $gradelevel = $request->get('gradelevel');
            // $cp_course = DB::table('college_courses')
            //       ->where('id',$course)
            //       ->select('id','courseDesc','courseabrv')
            //       ->get();

            // $array_course = array();
            // foreach($cp_course as $item){
            //       array_push($array_course,$item->id);
            // }

            $grade_status = DB::table('college_classsched')
                        ->join('college_prospectus',function($join){
                              $join->on('college_classsched.subjectID','=','college_prospectus.id');
                              $join->where('college_prospectus.deleted', 0);
                        })
                        ->join('college_sections', function ($join) use ($course, $gradelevel) {
                              $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                              $join->when($course != '', function ($query) use ($course) {
                                    $query->where('college_sections.courseID', $course);
                              });
                              $join->where('college_sections.deleted', 0);
                              $join->when($gradelevel != '', function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', $gradelevel);
                              });
                        })
                        ->join('college_instructor',function ($join) use($teacher){
                              $join->on('college_classsched.id', '=', 'college_instructor.classschedid');
                              $join->where('college_instructor.deleted', 0);
                              $join->when($teacher != null, function($query) use($teacher){
                                    $query->where('college_instructor.teacherid', $teacher);
                              });
                        })
                        ->join('teacher',function($join){
                              $join->on('college_instructor.teacherid','=','teacher.id');
                              $join->where('teacher.deleted', 0);
                        })
                        ->where('college_classsched.deleted', 0)
                        ->select(
                              DB::raw("CONCAT(teacher.firstname,' ',teacher.lastname) as teachername"),
                              'college_classsched.id as schedid',
                              'college_prospectus.subjDesc',
                              'college_prospectus.subjCode',
                              'college_sections.sectionDesc',
                              'college_prospectus.id as prospectusID',
                              'teacher.id as teacherid',
                              )
                        ->distinct('schedid')
                        ->get();

            $grade_status = $grade_status->map(function($item){
                  $student_grades = DB::table('college_stud_term_grades')
                              ->where('schedid',$item->schedid)
                              ->select(
                                    'id',
                                    'prelim_status',
                                    'midterm_status',
                                    'prefinal_status',
                                    'final_status',
                                    'schedid'
                              )
                              ->get();
                              $item->grades = $student_grades;
                              return $item;
            });

                        

            return $grade_status;
      }

      function show_grade_status_subject(Request $request){
            $id = $request->get('schedid');
            $prospectusID = $request->get('prospectusID');
            
            $schedule = DB::table('college_classsched')
                              ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                              ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                              ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                              ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                              ->join('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                              ->where('college_classsched.id', $id)
                              ->where('college_classsched.deleted', 0)
                              ->where('college_prospectus.id', $prospectusID)
                              ->select(
                                    'college_sections.sectionDesc',
                                    'college_prospectus.subjDesc',
                                    'college_prospectus.subjCode',
                                    DB::raw("CONCAT(teacher.firstname,' ',IFNULL(teacher.middlename, ''),' ',teacher.lastname) as teachername"),
                                    'gradelevel.levelname',
                                    'college_sections.sectionDesc',
                                    'teacher.id as teacherid',
                              )
                              ->first();

            return response()->json($schedule);
      }

      function show_grades_grade_status_subject(Request $request){
            $schedid = $request->get('schedid');
            $status = $request->get('status');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $grade_info = DB::table('college_enrolledstud')
                              ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
                              ->join('college_classsched', 'college_loadsubject.schedid', '=', 'college_classsched.id')
                              ->join('studinfo', 'college_loadsubject.studid', '=', 'studinfo.id')
                              ->where('college_loadsubject.schedid', $schedid)
                              // ->where('college_loadsubject.deleted', 0)
                              ->where('college_enrolledstud.studstatus', '<>', 0)
                              ->where('college_enrolledstud.syid', $syid)
                              ->where('college_enrolledstud.semid', $semid)
                              ->groupBy('college_enrolledstud.studid')
                              ->select(
                                    'college_loadsubject.studid',
                                    DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname,' ',IFNULL(SUBSTRING(studinfo.middlename,1,1),''),'.') as studname"),
                                    'studinfo.gender'
                                    )
                              ->get();


            $ecr_template = DB::table('college_ecr')
                        ->join('college_classsched', function($join) use($schedid){
                              $join->on('college_ecr.id', '=', 'college_classsched.ecr_template');
                              $join->where('college_classsched.id', $schedid);
                              $join->where('college_ecr.deleted', 0);
                        })
                        ->leftJoin('college_component_gradesetup', function($join) {
                              $join->on('college_ecr.id', '=', 'college_component_gradesetup.ecrID')
                              ->where('college_component_gradesetup.deleted', 0);
                        })
                        ->select(
                              'college_ecr.id as ecrid',
                              'college_component_gradesetup.id as componentid',
                              'college_component_gradesetup.descriptionComp as componentname',
                              'college_component_gradesetup.component',
                              'college_component_gradesetup.column_ECR'
                        )
                        ->get();

            $component_ids = $ecr_template->pluck('componentid')->filter();

            $subgrading_components = DB::table('college_subgradingcomponent')
                  ->whereIn('componentID', $component_ids)
                  ->where('deleted', 0)
                  ->get()
                  ->groupBy('componentID');

            $final_data = [];

            foreach($ecr_template as $ecr){
                  $components = [
                        'componentid' => $ecr->componentid,
                        'componentname' => $ecr->componentname,
                        'component_percentage' => $ecr->component,
                        'component_column' => $ecr->column_ECR,
                        'subgrading' => []
                  ];

                  if(isset($subgrading_components[$ecr->componentid])){
                        foreach($subgrading_components[$ecr->componentid] as $subgrading){
                              $components['subgrading'][] = [
                                    'subcompid' => $subgrading->id,
                                    'subcompname' => $subgrading->subDescComponent,
                                    'subcomp_percentage' => $subgrading->subComponent,
                                    'subcomponent_column' => $subgrading->subColumnECR
                              ];
                        }
                  }

                  if(!isset($final_data[$ecr->ecrid])){
                        $final_data[$ecr->ecrid] = [
                              'ecrid' => $ecr->ecrid,
                              'components' => []
                        ];
                  }

                  $final_data[$ecr->ecrid]['components'][] = $components;
            }
            
            $final_data[$ecr->ecrid]['studinfo'] = $grade_info;
            

            $final_data = array_values($final_data);
            // return $final_data;
            return view ('superadmin.pages.college.ecrtable',[
                  'component' => $final_data[0]['components'],
                  'students' => $final_data[0]['studinfo']
            ]);
      }

      function grade_info(Request $request){
            $grade_info = DB::table('college_loadsubject')
                              ->join('college_classsched', 'college_loadsubject.schedid', '=', 'college_classsched.id')
                              ->join('studinfo', 'college_loadsubject.studid', '=', 'studinfo.id')
                              ->where('college_loadsubject.schedid', $schedid)
                              ->where('college_loadsubject.deleted', 0)
                              ->select(
                                    DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname,' ',IFNULL(SUBSTRING(studinfo.middlename,1,1),''),'.') as studname"),
                                    'studinfo.gender'
                                    )
                              ->get();

            $grade_info = $grade_info->toArray();
      }

      public function view_system_grading($schedid, $syid, $semid){
            $exist = DB::table('college_classsched')
                        ->where('id', $schedid)
                        ->where('deleted', 0)
                        ->select('ecr_template')
                        ->first();
            if(empty($exist->ecr_template)){
                  return 'No Grading Template Selected';
            }

            return view('ctportal.pages.systemgrading', compact('schedid', 'syid', 'semid'));
      }
    
      public function display_scheddetail(Request $request){

            $schedid = $request->get('schedid');

            $sched = DB::table('college_classsched')
                        ->join('college_prospectus', 'college_classsched.subjectid', '=', 'college_prospectus.id')
                        ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerid')
                        ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                        ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                        ->join('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                        ->where('college_classsched.deleted', 0)                    
                        ->where('college_classsched.id', $schedid)
                        ->select(
                              'teacher.lastname',
                              'teacher.firstname',
                              'college_prospectus.subjDesc',
                              'college_prospectus.subjCode',
                              'gradelevel.levelname',
                              'college_sections.sectionDesc',
                              'college_classsched.ecr_template',
                              
                        )
                        ->first();

            return response()->json($sched);
      }

      public function display_ecr_template(Request $request){

            $schedid = $request->get('schedid');
            $semid = $request->get('semid');
            $syid = $request->get('syid');

            $grade_info = DB::table('college_enrolledstud')
                              ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
                              ->join('college_classsched', 'college_loadsubject.schedid', '=', 'college_classsched.id')
                              ->join('studinfo', 'college_loadsubject.studid', '=', 'studinfo.id')
                              ->where('college_loadsubject.schedid', $schedid)
                              ->where('college_loadsubject.deleted', 0)
                              ->where('college_enrolledstud.studstatus', '<>', 0)
                              ->where('college_enrolledstud.syid', $syid)
                              ->where('college_enrolledstud.semid', $semid)
                              ->groupBy('college_enrolledstud.studid')
                              ->select(
                                    'college_loadsubject.studid',
                                    DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname,' ',IFNULL(SUBSTRING(studinfo.middlename,1,1),''),'.') as studname"),
                                    'studinfo.gender'
                                    )
                              ->get();



            $ecr_template = DB::table('college_ecr')
                        ->join('college_classsched', function($join) use($schedid){
                              $join->on('college_ecr.id', '=', 'college_classsched.ecr_template');
                              $join->where('college_classsched.id', $schedid);
                              $join->where('college_ecr.deleted', 0);
                        })
                        ->leftJoin('college_component_gradesetup', function($join) {
                              $join->on('college_ecr.id', '=', 'college_component_gradesetup.ecrID')
                              ->where('college_component_gradesetup.deleted', 0);
                        })
                        ->select(
                              'college_ecr.id as ecrid',
                              'college_component_gradesetup.id as componentid',
                              'college_component_gradesetup.descriptionComp as componentname',
                              'college_component_gradesetup.component',
                              'college_component_gradesetup.column_ECR',
                        )
                        ->get();

            $component_ids = $ecr_template->pluck('componentid')->filter();

            $subgrading_components = DB::table('college_subgradingcomponent')
                  ->whereIn('componentID', $component_ids)
                  ->where('deleted', 0)
                  ->get()
                  ->groupBy('componentID');

            $final_data = [];

            foreach($ecr_template as $ecr){

                  $components = [
                        'componentid' => $ecr->componentid,
                        'componentname' => $ecr->componentname,
                        'component_percentage' => $ecr->component,
                        'component_column' => $ecr->column_ECR,
                        'subgrading' => []
                  ];

                  if(isset($subgrading_components[$ecr->componentid])){
                        foreach($subgrading_components[$ecr->componentid] as $subgrading){
                              $components['subgrading'][] = [
                                    'subcompid' => $subgrading->id,
                                    'subcompname' => $subgrading->subDescComponent,
                                    'subcomp_percentage' => $subgrading->subComponent,
                                    'subcomponent_column' => $subgrading->subColumnECR
                              ];
                        }
                  }

                  if(!isset($final_data[$ecr->ecrid])){
                        $final_data[$ecr->ecrid] = [
                              'ecrid' => $ecr->ecrid,
                              'components' => []
                        ];
                  }

                  $final_data[$ecr->ecrid]['components'][] = $components;
            }
            
            $final_data[$ecr->ecrid]['studinfo'] = $grade_info;
            

            $final_data = array_values($final_data);
            // return $final_data;
            return view ('ctportal.pages.ecrtable',[
                  'component' => $final_data[0]['components'],
                  'students' => $final_data[0]['studinfo']
            ]);
      }

      public function save_system_grades(Request $request){
            $highest_scores = $request->get('highest_scores');
            $grades = $request->get('scores');
            $term_averages = $request->get('term_averages');


            $equivalence = DB::table("college_grade_point_scale")
                                    ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
                                    ->where('college_grade_point_equivalence.isactive', 1)
                                    ->select(
                                          'college_grade_point_scale.grade_point',
                                          'college_grade_point_scale.letter_equivalence',
                                          'college_grade_point_scale.percent_equivalence',
                                          'college_grade_point_scale.grade_remarks'
                                          )
                                    ->get();
                                          

            foreach($highest_scores as $scores){

                  $exist = DB::table('college_highest_score')
                        ->where('schedid', $scores['schedid'])
                        ->where('component_id', $scores['component_id'])
                        ->where('subcomponent_id', $scores['subid'])
                        ->where('term', $scores['term'])
                        ->where('column_number', $scores['sort'])
                        ->first();

                  if($exist){
                        DB::table('college_highest_score')
                              ->where('schedid', $scores['schedid'])
                              ->where('component_id', $scores['component_id'])
                              ->where('subcomponent_id', $scores['subid'])
                              ->where('term', $scores['term'])
                              ->where('column_number', $scores['sort'])
                              ->update([
                                    'score' => $scores['highest_score'],
                                    'date' => $scores['date'],
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        DB::table('college_highest_score')
                              ->insert([
                                    'schedid'=> $scores['schedid'],
                                    'component_id'=> $scores['component_id'],
                                    'subcomponent_id' => $scores['subid'],
                                    'score' => $scores['highest_score'],
                                    'term' => $scores['term'],
                                    'date' => $scores['date'],
                                    'column_number' => $scores['sort'],
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
   
            }

            foreach($grades as $grade){

                  $exist = DB::table('college_grading_scores')
                        ->where('schedid', $grade['schedid'])
                        ->where('studid', $grade['studid'])
                        ->where('componentid', $grade['component_id'])
                        ->where('subcomponent_id', $grade['subid'])
                        ->where('term', $grade['term'])
                        ->where('column_number', $grade['sort'])
                        ->first();
                  
                  if($exist){
                        DB::table('college_grading_scores')
                              ->where('schedid', $grade['schedid'])
                              ->where('studid', $grade['studid'])
                              ->where('componentid', $grade['component_id'])
                              ->where('subcomponent_id', $grade['subid'])
                              ->where('term', $grade['term'])
                              ->where('column_number', $grade['sort'])
                              ->update([
                                    'score' => $grade['score'],
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        DB::table('college_grading_scores')
                              ->insert([
                                    'schedid'=> $grade['schedid'],
                                    'studid'=> $grade['studid'],
                                    'componentid'=> $grade['component_id'],
                                    'subcomponent_id' => $grade['subid'],
                                    'score' => $grade['score'],
                                    'term' => $grade['term'],
                                    'column_number' => $grade['sort'],
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  } 
                  
            }

            foreach($term_averages as $average){
                  $ave_equivalence = null;
                  foreach ($equivalence as $eq) {
                        // Remove '%' and extract min/max values as floats
                        $percentRange = array_map('trim', explode('-', str_replace('%', '', $eq->percent_equivalence)));
                
                        $minPercent = isset($percentRange[0]) ? floatval($percentRange[0]) : null;
                        $maxPercent = isset($percentRange[1]) ? floatval($percentRange[1]) : null;
                
                        if ($average['term_average'] !== 'INC' && $average['term_average'] !== null) {
                            $termAverage = round(floatval($average['term_average'])); // Round off to whole number
                            if (!is_null($minPercent) && !is_null($maxPercent)) {
                                if ($termAverage >= $minPercent && $termAverage <= $maxPercent) {
                                    $ave_equivalence = $eq->grade_point;
                                    break; 
                                }
                            }
                        }else if($average['term_average'] === 'INC'){
                            $ave_equivalence = 'INC';
                        }
                    }

                  $exist = DB::table('college_stud_term_grades')
                  ->where('schedid', $average['schedid'])
                  ->where('studid', $average['studid'])
                  ->first();


            
                  if($exist){
                        if($average['term'] == 1){
                              DB::table('college_stud_term_grades')
                                    ->where('schedid', $average['schedid'])
                                    ->where('studid', $average['studid'])
                                    ->update([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'prelim_grade' => $average['term_average'],
                                          'prelim_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 2){
                              DB::table('college_stud_term_grades')
                                    ->where('schedid', $average['schedid'])
                                    ->where('studid', $average['studid'])
                                    ->update([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'midterm_grade' => $average['term_average'],
                                          'midterm_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 3){
                              DB::table('college_stud_term_grades')
                                    ->where('schedid', $average['schedid'])
                                    ->where('studid', $average['studid'])
                                    ->update([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'prefinal_grade' => $average['term_average'],
                                          'prefinal_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 4){

                              DB::table('college_stud_term_grades')
                                    ->where('schedid', $average['schedid'])
                                    ->where('studid', $average['studid'])
                                    ->update([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'final_grade' => $average['term_average'],
                                          'final_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                              
                        }
                        
                  }else{
                        if($average['term'] == 1){
                              DB::table('college_stud_term_grades')
                                    ->insert([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'prelim_grade' => $average['term_average'],
                                          'prelim_transmuted' => $ave_equivalence,
                                          'prelim_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 2){
                              DB::table('college_stud_term_grades')
                                    ->insert([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'midterm_grade' => $average['term_average'],
                                          'midterm_transmuted' => $ave_equivalence,
                                          'midterm_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 3){
                              DB::table('college_stud_term_grades')
                                    ->insert([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'prefinal_grade' => $average['term_average'],
                                          'prefinal_transmuted' => $ave_equivalence,
                                          'prefinal_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 4){
                              DB::table('college_stud_term_grades')
                                    ->insert([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'final_grade' => $average['term_average'],
                                          'final_transmuted' => $ave_equivalence,
                                          'final_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }
            }
      }

      public function display_term_grades(Request $request){
            $term = request()->get('term');
            $schedid = request()->get('schedid');
            $status = request()->get('status');

            $highest_scores = DB::table('college_highest_score')
                                    ->where('schedid', $schedid)
                                    ->where('term', $term)
                                    ->select('component_id', 'subcomponent_id', 'score', 'column_number','date')
                                    ->get();

            $grade_scores = DB::table('college_grading_scores')
                                    ->where('schedid', $schedid)
                                    ->where('term', $term)
                                    // ->when($status, function ($query, $status) {
                                    //       return $query->where('status_flag', $status);
                                    // })
                                    ->select('componentid', 'subcomponent_id', 'score', 'column_number','studid')
                                    ->get();
            
            $grade_status = DB::table('college_grading_scores')
                                    ->where('schedid', $schedid)
                                    ->where('term', $term)
                                    ->select('studid', 'status_flag')
                                    ->groupBy('studid')
                                    ->get();
                                    
            
            
            return [
                  'highest_scores' => $highest_scores,
                  'grade_scores' => $grade_scores,
                  'grade_status' => $grade_status
            ];

      }

      public function submit_grades(Request $request){
            $grades = request()->get('grades');
            $students = request()->get('students');

            $data = DB::table("college_grade_point_scale")
                        ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
                        ->where('college_grade_point_equivalence.isactive', 1)
                        ->select(
                              'college_grade_point_scale.grade_point',
                              'college_grade_point_scale.letter_equivalence',
                              'college_grade_point_scale.percent_equivalence',
                              'college_grade_point_scale.grade_remarks'
                              )
                        ->get();

            $terms = DB::table('college_ecr_term')
                  ->join('college_termgrading', 'college_ecr_term.termID', '=', 'college_termgrading.id')
                  ->join('college_ecr', 'college_ecr_term.ecrID', '=', 'college_ecr.id')
                  ->join('college_classsched', 'college_ecr_term.ecrID', '=', 'college_ecr.id')
                  ->where('college_classsched.id', $grades[0]['schedid'])
                  ->select('college_termgrading.description', 'college_termgrading.quarter')
                  ->get();


            // return $students;
            foreach($grades as $grade){
                  DB::table('college_grading_scores')
                        ->where('studid', $grade['studid'])
                        ->where('componentid', $grade['component_id'])
                        ->where('subcomponent_id', $grade['subid'])
                        ->where('term', $grade['term'])
                        ->where('column_number', $grade['sort'])
                        ->update([
                              'status_flag' => 1,
                        ]);
            
            };
            foreach($students as $student){
                  
                  if($student['term'] == 1){
                        if($student['term_average'] == 'INC'){
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'prelim_status' => 7,
                                    ]);   
                        }else{
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'prelim_status' => 1,
                                    ]);   
                        }
                  }else if($student['term'] == 2){
                        if($student['term_average'] == 'INC'){
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'midterm_status' => 7,
                                    ]);
                        }else{
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'midterm_status' => 1,
                                    ]);
                        }
                  }else if($student['term'] == 3){
                        if($student['term_average'] == 'INC'){
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'prefinal_status' => 7,
                                    ]);
                        }else{
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'prefinal_status' => 1,
                                    ]);
                        }
                        
                  }else if($student['term'] == 4){
                        if($student['term_average'] == 'INC'){
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'final_status' => 7,
                                    ]);  
                        }else{
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'final_status' => 1,
                                    ]);     
                        }

                        $final_average = DB::table('college_stud_term_grades')
                                          ->where('schedid', $student['schedid'])
                                          ->where('studid', $student['studid'])
                                          ->first();

                  
                        
                        $selected_quarters = $terms->pluck('quarter')->toArray();

                        // Initialize an empty grades array
                        $grades = [];
                        
                        // Dynamically select grades based on the extracted quarters
                        if (in_array(1, $selected_quarters)) {
                              $grades[] = $final_average->prelim_grade;
                        }
                        if (in_array(2, $selected_quarters)) {
                              $grades[] = $final_average->midterm_grade;
                        }
                        if (in_array(3, $selected_quarters)) {
                              $grades[] = $final_average->prefinal_grade;
                        }
                        if (in_array(4, $selected_quarters)) {
                              $grades[] = $final_average->final_grade;
                        }
                        
                        // Check if any grade is 'INC'
                        if (!in_array('INC', $grades, true) && count($grades) > 0) {
                              // Compute the average of only the selected terms
                              $average = number_format(array_sum($grades) / count($grades), 2, '.', ',');
                        
                              // Determine grade equivalence
                              foreach ($data as $eq) {
                                    $percentRange = array_map('trim', explode('-', str_replace('%', '', $eq->percent_equivalence)));
                                    $minPercent = isset($percentRange[0]) ? floatval($percentRange[0]) : null;
                                    $maxPercent = isset($percentRange[1]) ? floatval($percentRange[1]) : null;
                        
                                    $termAverage = round(floatval($average)); // Round to whole number
                        
                                    if (!is_null($minPercent) && !is_null($maxPercent) && $termAverage >= $minPercent && $termAverage <= $maxPercent) {
                                    $ave_equivalence = $eq->grade_point;
                                    $ave_remarks = $eq->grade_remarks;
                                    break;
                                    }
                              }
                        
                              // Update the database with the calculated values
                              DB::table('college_stud_term_grades')
                                    ->where('schedid', $student['schedid'])
                                    ->where('studid', $student['studid'])
                                    ->update([
                                    'final_grade_average' => $average,
                                    'final_grade_transmuted' => $ave_equivalence,
                                    'final_remarks' => $ave_remarks
                                    ]);
                        
                        } else {
                              // If any grade is INC, update all values to 'INC'
                              DB::table('college_stud_term_grades')
                                    ->where('schedid', $student['schedid'])
                                    ->where('studid', $student['studid'])
                                    ->update([
                                    'final_grade_average' => 'INC',
                                    'final_grade_transmuted' => 'INC',
                                    'final_remarks' => 'INC'
                                    ]);
                        }
      
                  }
            };


      }

      public function update_grade_status(){
            $grades = request()->get('grades');
            $students = request()->get('students');

            $data = DB::table('college_grade_point_scale')
            ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
            ->where('college_grade_point_equivalence.isactive', 1)
            ->select(
                  'college_grade_point_scale.grade_point',
                  'college_grade_point_scale.letter_equivalence',
                  'college_grade_point_scale.percent_equivalence',
                  )
            ->get();

            foreach($grades as $grade){
                  if($grade['term'] == 'Prelim'){
                        $grade['term'] = 1;
                  }else if($grade['term'] == 'Midterm'){
                        $grade['term'] = 2;
                  }else if($grade['term'] == 'Pre-Final'){
                        $grade['term'] = 3;
                  }else if($grade['term'] == 'Final'){
                        $grade['term'] = 4;
                  }     
                  DB::table('college_grading_scores')
                        ->where('studid', $grade['studid'])
                        ->where('componentid', $grade['component_id'])
                        ->where('subcomponent_id', $grade['subid'])
                        ->where('term', $grade['term'])
                        ->where('column_number', $grade['sort'])
                        ->update([
                              'status_flag' => $grade['status_id'],
                        ]);
            
            };

            foreach($students as $student){
                  if($student['term'] == 'Prelim'){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $student['studid'])
                              ->where('schedid', $student['schedid'])
                              ->update([
                                    'prelim_status' => $student['status_id'],
                              ]);
                  }else if($student['term'] == 'Midterm'){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $student['studid'])
                              ->where('schedid', $student['schedid'])
                              ->update([
                                    'midterm_status' => $student['status_id'],
                              ]);
                  }else if($student['term'] == 'Pre-Final'){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $student['studid'])
                              ->where('schedid', $student['schedid'])
                              ->update([
                                    'pre-final_status' => $student['status_id'],
                              ]);
                  }else if($student['term'] == 'Final'){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $student['studid'])
                              ->where('schedid', $student['schedid'])
                              ->update([
                                    'final_status' => $student['status_id'],
                              ]);
                  }
            };


      }

      public function get_active_equivalency(){
            $data = DB::table('college_grade_point_scale')
            ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
            ->where('college_grade_point_equivalence.isactive', 1)
            ->select(
                  'college_grade_point_scale.grade_point',
                  'college_grade_point_scale.letter_equivalence',
                  'college_grade_point_scale.percent_equivalence',
                  'college_grade_point_scale.grade_remarks',
                  'college_grade_point_scale.is_failed'
                  )
            ->get();

            return $data;

      }

      public function display_submitted_grades(Request $request){
            $term = request()->get('term');
            $schedid = request()->get('schedid');
            $status = request()->get('status');

            $highest_scores = DB::table('college_highest_score')
                                    ->where('schedid', $schedid)
                                    ->where('term', $term)
                                    ->select('component_id', 'subcomponent_id', 'score', 'column_number','date')
                                    ->get();

            $grade_scores = DB::table('college_grading_scores')
                                    ->where('schedid', $schedid)
                                    ->where('term', $term)
                                    // ->when($status, function ($query, $status) {
                                    //       return $query->where('status_flag', $status);
                                    // })
                                    ->where('status_flag', '!=', 0)
                                    ->select('componentid', 'subcomponent_id', 'score', 'column_number','studid')
                                    ->get();
            
            $grade_status = DB::table('college_grading_scores')
                                    ->where('schedid', $schedid)
                                    ->where('term', $term)
                                    ->select('studid', 'status_flag')
                                    ->groupBy('studid')
                                    ->get();                       
            
            return [
                  'highest_scores' => $highest_scores,
                  'grade_scores' => $grade_scores,
                  'grade_status' => $grade_status
            ];

      }

      public function get_status_sections(Request $request){
            $term = request()->get('term');
            $status = request()->get('status');
            $syid = request()->get('syid');
            $semid = request()->get('semid');
            $gradelevel = request()->get('gradelevel');
            $courseid = request()->get('courseid');
            

            $sections = Db::table('college_stud_term_grades')
                        ->join('studinfo',function($join){
                              $join->on('studinfo.id','=','college_stud_term_grades.studid');
                              $join->where('studinfo.deleted',0);
                        })
                        ->join('college_loadsubject',function($join) use($syid,$semid){
                              $join->on('college_loadsubject.studid','=','studinfo.id');
                              // $join->where('college_loadsubject.deleted',0);
                        })
                        ->join('college_classsched',function($join) use($syid,$semid){
                              $join->on('college_classsched.id','=','college_stud_term_grades.schedid');
                              $join->where('college_classsched.deleted',0);
                              $join->where('college_classsched.syid',$syid);
                              $join->where('college_classsched.semesterID',$semid);
                        })
                        ->join('college_sections',function($join) use($syid,$semid,$courseid,$gradelevel){
                              $join->on('college_classsched.sectionID','=','college_sections.id');
                              $join->when($courseid != '', function ($query) use ($courseid) {
                                    $query->where('college_sections.courseID', '=', $courseid);
                              });
                              $join->when($gradelevel != '', function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', '=', $gradelevel);
                              });
                              $join->where('college_sections.deleted',0);
                              $join->where('college_sections.syID',$syid);
                              $join->where('college_sections.semesterID',$semid);
                        })
                        ->join('college_prospectus',function($join) use($syid,$semid){
                              $join->on('college_classsched.subjectID','=','college_prospectus.id');
                              $join->where('college_prospectus.deleted',0);
                        })
                        ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                        ->join('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                        ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->where('college_stud_term_grades.deleted',0)
                        ->when($term == 1, function ($query) use($status){
                              $query->where('college_stud_term_grades.prelim_status', '=', $status);
                        })
                        ->when($term == 2, function ($query) use($status){
                              $query->where('college_stud_term_grades.midterm_status', '=', $status);
                        })
                        ->when($term == 3, function ($query) use($status){
                              $query->where('college_stud_term_grades.prefinal_status', '=', $status);
                        })
                        ->when($term == 4, function ($query) use($status){
                              $query->where('college_stud_term_grades.final_status', '=', $status);
                        })
                        ->groupBy('college_classsched.id')
                        ->select(
                              'gradelevel.levelname',
                              'college_sections.sectionDesc',
                              DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),
                              'teacher.tid',
                              'college_prospectus.subjDesc',
                              'college_prospectus.subjCode',
                              'college_classsched.id as schedid'
                        )
                        ->get();
            if(count($sections) == 0 && $status == 0){
                  $sections = DB::table('college_sections')
                              ->join('college_classsched', 'college_sections.id', '=', 'college_classsched.sectionID')
                              ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                              ->join('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                              ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                              ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                              ->leftJoin('college_stud_term_grades', function($join) {
                                    $join->on('college_classsched.id', '=', 'college_stud_term_grades.schedid')
                                          ->on('college_stud_term_grades.deleted', '=', DB::raw(0));
                              })
                              ->where(function($query) use ($term) {
                                    $query->whereNull('college_stud_term_grades.studid') // If no record exists, include it
                                          ->orWhere(function($subQuery) use ($term) { 
                                          if ($term == 1) {
                                                $subQuery->whereNull('college_stud_term_grades.prelim_status');
                                          } elseif ($term == 2) {
                                                $subQuery->whereNull('college_stud_term_grades.midterm_status');
                                          } elseif ($term == 3) {
                                                $subQuery->whereNull('college_stud_term_grades.prefinal_status');
                                          } elseif ($term == 4) {
                                                $subQuery->whereNull('college_stud_term_grades.final_status');
                                          }
                                          });
                              })
                              ->when($gradelevel, function ($query) use($gradelevel) {
                                    $query->where('college_sections.yearID', '=', $gradelevel);
                              })
                              ->when($courseid, function ($query) use($courseid) {
                                    $query->where('college_sections.courseID', '=', $courseid);
                              })
                              ->where('college_sections.syID', $syid)
                              ->where('college_sections.semesterID', $semid)
                              ->where('college_sections.deleted', 0)
                              ->where('college_classsched.deleted', 0)
                              ->select(
                                    'gradelevel.levelname',
                                    'college_sections.sectionDesc',
                                    DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),
                                    'teacher.tid',
                                    'college_prospectus.subjDesc',
                                    'college_prospectus.subjCode',
                                    'college_classsched.id as schedid'
                              )
                              ->groupBy('college_classsched.id')
                              ->get();

            }
           


            return $sections;
      }

      public function get_status_students(Request $request){
            $term = request()->get('term');
            $status = request()->get('status');
            $syid = request()->get('syid');
            $semid = request()->get('semid');
            $gradelevel = request()->get('gradelevel');
            $courseid = request()->get('courseid');

            $term = (int) $term;
            $status = (int) $status;

            

            $students = Db::table('college_stud_term_grades')
                        ->join('studinfo',function($join){
                              $join->on('studinfo.id','=','college_stud_term_grades.studid');
                              $join->where('studinfo.deleted',0);
                        })
                        ->join('college_loadsubject',function($join) use($syid,$semid){
                              $join->on('college_loadsubject.studid','=','studinfo.id');
                              // $join->where('college_loadsubject.deleted',0);
                        })
                        ->join('college_classsched',function($join) use($syid,$semid){
                              $join->on('college_classsched.id','=','college_stud_term_grades.schedid');
                              $join->where('college_classsched.deleted',0);
                              $join->where('college_classsched.syid',$syid);
                              $join->where('college_classsched.semesterID',$semid);
                        })
                        ->join('college_sections',function($join) use($syid,$semid,$courseid,$gradelevel){
                              $join->on('college_classsched.sectionID','=','college_sections.id');
                              $join->when($courseid != '', function ($query) use ($courseid) {
                                    $query->where('college_sections.courseID', '=', $courseid);
                              });
                              $join->when($gradelevel != '', function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', '=', $gradelevel);
                              });
                              $join->where('college_sections.deleted',0);
                              $join->where('college_sections.syID',$syid);
                              $join->where('college_sections.semesterID',$semid);
                        })
                        ->join('college_prospectus',function($join) use($syid,$semid){
                              $join->on('college_classsched.subjectID','=','college_prospectus.id');
                              $join->where('college_prospectus.deleted',0);
                        })
                        ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->where('college_stud_term_grades.deleted',0)
                        ->when($term == 1, function ($query) use($status){
                              $query->where('college_stud_term_grades.prelim_status', '=', $status);
                        })
                        ->when($term == 2, function ($query) use($status){
                              $query->where('college_stud_term_grades.midterm_status', '=', $status);
                        })
                        ->when($term == 3, function ($query) use($status){
                              $query->where('college_stud_term_grades.prefinal_status', '=', $status);
                        })
                        ->when($term == 4, function ($query) use($status){
                              $query->where('college_stud_term_grades.final_status', '=', $status);
                        })
                        ->groupBy('college_stud_term_grades.studid')
                        ->select(
                              'gradelevel.levelname',
                              DB::raw("CONCAT(studinfo.lastname, ', ', IFNULL(studinfo.firstname, ''), ' ', IFNULL(studinfo.middlename, '')) AS studname"),
                              'studinfo.id as studid',
                              'studinfo.sid',
                              'college_sections.sectionDesc',
                              'college_sections.id as sectionid',
                        )
                        ->get();
            
            if(count($students) == 0 && $status == 0){
                  $students = DB::table('college_loadsubject')
                        ->join('college_enrolledstud', function($join) use($syid, $semid) {
                              $join->on('college_loadsubject.studid', '=', 'college_enrolledstud.studid')
                                    ->where('college_enrolledstud.deleted', 0)
                                    ->where('college_enrolledstud.syid', $syid)
                                    ->where('college_enrolledstud.semid', $semid)
                                    ->where('college_enrolledstud.studstatus', '!=', 0);
                        })
                        ->join('college_classsched', function($join) use($syid, $semid) {
                              $join->on('college_loadsubject.schedid', '=', 'college_classsched.id')
                                    ->where('college_classsched.deleted', 0)
                                    ->where('college_classsched.syid', $syid)
                                    ->where('college_classsched.semesterID', $semid);
                        })
                        ->join('college_sections', function($join) use($syid, $semid, $courseid, $gradelevel) {
                              $join->on('college_classsched.sectionID', '=', 'college_sections.id')
                                    ->when($courseid != '', function ($query) use ($courseid) {
                                    $query->where('college_sections.courseID', '=', $courseid);
                                    })
                                    ->when($gradelevel != '', function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', '=', $gradelevel);
                                    })
                                    ->where('college_sections.deleted', 0)
                                    ->where('college_sections.syID', $syid)
                                    ->where('college_sections.semesterID', $semid);
                        })
                        ->join('college_prospectus', function($join) {
                              $join->on('college_classsched.subjectID', '=', 'college_prospectus.id')
                                    ->where('college_prospectus.deleted', 0);
                        })
                        ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->join('studinfo', function($join) {
                              $join->on('studinfo.id', '=', 'college_loadsubject.studid')
                                    ->where('studinfo.deleted', 0);
                        })
                        ->leftJoin('college_stud_term_grades', function($join) {
                              $join->on('college_loadsubject.studid', '=', 'college_stud_term_grades.studid')
                                    ->on('college_loadsubject.schedid', '=', 'college_stud_term_grades.schedid');
                        })
                        ->where(function($query) use ($term) {
                              $query->whereNull('college_stud_term_grades.studid') // If no record exists, include student
                                    ->orWhere(function($subQuery) use ($term) { 
                                    if ($term == 1) {
                                          $subQuery->whereNull('college_stud_term_grades.prelim_status');
                                    } elseif ($term == 2) {
                                          $subQuery->whereNull('college_stud_term_grades.midterm_status');
                                    } elseif ($term == 3) {
                                          $subQuery->whereNull('college_stud_term_grades.prefinal_status');
                                    } elseif ($term == 4) {
                                          $subQuery->whereNull('college_stud_term_grades.final_status');
                                    }
                                    });
                        })
                        ->groupBy('college_loadsubject.studid')
                        ->select(
                              'gradelevel.levelname',
                              DB::raw("CONCAT(studinfo.lastname, ', ', IFNULL(studinfo.firstname, ''), ' ', IFNULL(studinfo.middlename, '')) AS studname"),
                              'studinfo.id as studid',
                              'studinfo.sid',
                              'college_sections.sectionDesc',
                              'college_sections.id as sectionid'
                        )
                        ->get();
            }

            return $students;
      }

      public function get_status_students_grades(Request $request){
            $sectionid = request()->get('sectionid');
            $term = request()->get('term');
            $status = request()->get('status');
            $studid = request()->get('studid');

            $student_grades = DB::table('college_stud_term_grades')
                              ->join('college_classsched', 'college_stud_term_grades.schedid', '=', 'college_classsched.id')
                              ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                              ->join('college_sections', function($join) use($sectionid){
                                    $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                                    $join->where('college_sections.deleted', 0);
                                    $join->where('college_sections.id', $sectionid);
                              })
                              ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                              ->where('college_stud_term_grades.studid', $studid)
                              ->when($term == 1, function ($query) use($status){
                                    $query->where('college_stud_term_grades.prelim_status', '=', $status);
                              })
                              ->when($term == 2, function ($query) use($status){
                                    $query->where('college_stud_term_grades.midterm_status', '=', $status);
                              })
                              ->when($term == 3, function ($query) use($status){
                                    $query->where('college_stud_term_grades.prefinal_status', '=', $status);
                              })
                              ->when($term == 4, function ($query) use($status){
                                    $query->where('college_stud_term_grades.final_status', '=', $status);
                              })
                              ->select(
                                    'college_prospectus.subjCode',
                                    'college_prospectus.subjDesc',
                                    'college_stud_term_grades.prelim_transmuted',
                                    'college_stud_term_grades.midterm_transmuted',
                                    'college_stud_term_grades.prefinal_transmuted',
                                    'college_stud_term_grades.final_transmuted',
                                    'college_sections.sectionDesc',
                                    'gradelevel.levelname',
                                    'college_stud_term_grades.studid',
                                    'college_stud_term_grades.schedid',
                              )
                              ->get();

            $equivalence = DB::table("college_grade_point_scale")
                                    ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
                                    ->where('college_grade_point_equivalence.isactive', 1)
                                    ->select(
                                          'college_grade_point_scale.grade_point',
                                          'college_grade_point_scale.letter_equivalence',
                                          'college_grade_point_scale.percent_equivalence',
                                          'college_grade_point_scale.grade_remarks'
                                          )
                                    ->get();
            
            foreach($student_grades as $grades){
                  if($grades->prelim_transmuted == null || $grades->prelim_transmuted == 0){
                        $grades->prelim_remarks = 0;
                  }else{
                        $grades->prelim_remarks = $equivalence->where('grade_point', $grades->prelim_transmuted)->first()->grade_remarks;
                  }

                  if($grades->midterm_transmuted == null || $grades->midterm_transmuted == 0){
                        $grades->midterm_remarks = 0;
                  }else{
                        $grades->midterm_remarks = $equivalence->where('grade_point', $grades->midterm_transmuted)->first()->grade_remarks;
                  }

                  if($grades->prefinal_transmuted == null || $grades->prefinal_transmuted == 0){
                        $grades->prefinal_remarks = 0;
                  }else{
                        $grades->prefinal_remarks = $equivalence->where('grade_point', $grades->prefinal_transmuted)->first()->grade_remarks;
                  }

                  if($grades->prefinal_transmuted == null || $grades->prefinal_transmuted == 0){
                        $grades->final_remarks = 0;
                  }else{
                        $grades->final_remarks = $equivalence->where('grade_point', $grades->final_transmuted)->first()->grade_remarks;
                  }
            }
            
            return $student_grades;
      }

      public function change_status_students_grades(Request $request){
            $schedid = request()->get('schedid');
            $studid = request()->get('studid');
            $stud_status = request()->get('stud_status');
            $term = request()->get('term_students');

            if($term == 1){
                  DB::table('college_stud_term_grades')
                        ->where('studid', $studid)
                        ->where('schedid', $schedid)
                        ->update([
                              'prelim_status' => $stud_status
                        ]);
                  
                  DB::table('college_grading_scores')
                        ->where('studid', $studid)
                        ->where('schedid', $schedid)
                        ->where('term', '=', 'Prelim')
                        ->update([
                              'status_flag' => $stud_status
                        ]);
            }else if($term == 2){
                  DB::table('college_stud_term_grades')
                        ->where('studid', $studid)
                        ->where('schedid', $schedid)
                        ->update([
                              'midterm_status' => $stud_status
                        ]);
                  
                  DB::table('college_grading_scores')
                        ->where('studid', $studid)
                        ->where('schedid', $schedid)
                        ->where('term', '=', 'Midterm')
                        ->update([
                              'status_flag' => $stud_status
                        ]);
                  
            }else if($term == 3){
                  DB::table('college_stud_term_grades')
                        ->where('studid', $studid)
                        ->where('schedid', $schedid)
                        ->update([
                              'prefinal_status' => $stud_status
                        ]);
                  
                  DB::table('college_grading_scores')
                        ->where('studid', $studid)
                        ->where('schedid', $schedid)
                        ->where('term', '=', 'Pre-Final')
                        ->update([
                              'status_flag' => $stud_status
                        ]);
                  
            }else if($term == 4){
                  DB::table('college_stud_term_grades')
                        ->where('studid', $studid)
                        ->where('schedid', $schedid)
                        ->update([
                              'final_status' => $stud_status
                        ]);
                  
                  DB::table('college_grading_scores')
                        ->where('studid', $studid)
                        ->where('schedid', $schedid)
                        ->where('term', '=', 'Final')
                        ->update([
                              'status_flag' => $stud_status
                        ]);
                  
            }
           
      }

      public function get_terms(Request $request){
            $schedid = request()->get('schedid');

            $terms = DB::table('college_ecr_term')
                        ->join('college_termgrading', 'college_ecr_term.termID', '=', 'college_termgrading.id')
                        ->join('college_ecr', 'college_ecr_term.ecrID', '=', 'college_ecr.id')
                        ->join('college_classsched', 'college_ecr_term.ecrID', '=', 'college_classsched.ecr_template')
                        ->where('college_ecr_term.deleted', 0)
                        ->where('college_classsched.id', $schedid)
                        ->select('college_termgrading.description', 'college_termgrading.quarter', 'college_classsched.id')
                        ->groupBy('college_termgrading.id')
                        ->get();

            return $terms;
      }

}
