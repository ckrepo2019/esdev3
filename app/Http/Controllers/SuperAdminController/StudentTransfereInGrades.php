<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;

class StudentTransfereInGrades extends \App\Http\Controllers\Controller
{

      // public static function subjects(Request $request){

      //       $levelid = $request->get('levelid');
      //       // $strandid = $request->get('strandid');
      //       $syid = $request->get('syid');
      //       $semid = $request->get('semid');

      //       if($levelid == 14 || $levelid == 15){

      //             $subjects = DB::table('subject_plot')
      //                               ->join('sh_subjects',function($join){
      //                                     $join->on('subject_plot.subjid','=','sh_subjects.id');
      //                                     $join->where('sh_subjects.deleted',0);
      //                               })
      //                               ->where('subject_plot.syid',$syid)
      //                               ->where('subject_plot.semid',$semid)
      //                               // ->where('subject_plot.syid',$strandid)
      //                               ->where('subject_plot.deleted',0)
      //                               ->where('subject_plot.levelid',$levelid)
      //                               ->select(
      //                                     'subject_plot.subjid as id',
      //                                     'subjtitle as subjdesc',
      //                                     'subjcode',
      //                                     DB::raw("CONCAT(sh_subjects.subjcode,' - ',sh_subjects.subjtitle) as text")
      //                               )
      //                               ->distinct('subjid')
      //                               ->get();

      //       }else{

      //             $subjects = DB::table('subject_plot')
      //                               ->join('subjects',function($join){
      //                                     $join->on('subject_plot.subjid','=','subjects.id');
      //                                     $join->where('subjects.deleted',0);
      //                                     $join->where('subjects.isCon',0);
      //                               })
      //                               ->where('subject_plot.syid',$syid)
      //                               ->where('subject_plot.deleted',0)
      //                               ->where('subject_plot.levelid',$levelid)
                                    
      //                               ->select(
      //                                     'subject_plot.subjid as id',
      //                                     'subjdesc',
      //                                     'subjcode',
      //                                     DB::raw("CONCAT(subjects.subjcode,' - ',subjects.subjdesc) as text")
      //                               )
      //                               ->get();


      //       }

      //       return $subjects;
            
      // }

      public static function gradelevel(Request $request){

            $gradelevel = DB::table('gradelevel')
                              ->where('gradelevel.deleted',0)
                              ->where('acadprogid','!=',6)
                              ->orderBy('sortid')
                              ->select(
                                    'id',
                                    'levelname as text'
                              )
                              ->get();

            return $gradelevel;
            
      }

      public static function sections(Request $request){

            $levelid = $request->get('levelid');

            $sections = DB::table('sections')
                              ->where('sections.deleted',0)
                              ->where('levelid',$levelid)
                              ->select(
                                    'id',
                                    'sectionname as text'
                              )
                              ->get();

            return $sections;
            
      }

      public static function student_trasferedin_grade_delete(Request $request){

            $studid = $request->get('studid');
            $dataid = $request->get('dataid');

            try{

                  DB::table('grades_tranf')
                        ->where('id',$dataid)
                        ->where('studid',$studid)
                        ->update([
                              'deleted'=>1,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Grades Deleted!',
                  ]); 

            }catch(\Exception $e){

                  return self::store_error($e);

            }
      }


      public static function student_trasferedin_grade_update(Request $request){
            $studid = $request->get('studid');
            $id = $request->get('id');
            $grade = $request->get('grade');
            try{

                  DB::table('grades_tranf')
                        ->where('id',$id)
                        ->where('studid',$studid)
                        ->update([
                              'qg'=>$grade,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Grade Update!',
                  ]); 

            }catch(\Exception $e){

            }
      }

      public static function student_trasferedin_grade_create(Request $request){

            $studid = $request->get('studid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $quarter = $request->get('quarter');
            $grade = $request->get('grade');

            try{

                  // $checking = DB::table('grades_tranf')
                  //                   ->where('studid',$studid)
                  //                   ->where('syid',$syid)
                  //                   ->where('subjid',$subjid)
                  //                   ->where('quarter',$quarter)
                  //                   ->where('deleted',0)
                  //                   ->count();

                  // if( $checking > 0){
                  //       return array((object)[
                  //             'status'=>0,
                  //             'message'=>'Already Exist!',
                  //       ]); 
                  // }

//                   if($levelid == 14 || $levelid == 15){

//                         $enrollment_record = DB::table('sh_enrolledstud')
//                                                 ->where('syid',$syid)
//                                                 ->where('semid',$semid)
//                                                 ->where('levelid',$levelid)
//                                                 ->where('studid',$studid)
//                                                 ->where('sectionid',$sectionid)
//                                                 ->where('deleted',0)
//                                                 ->select(
//                                                       'levelid',
//                                                       'sectionid'
//                                                 )->first();
// }
//                   else{
//                         $enrollment_record = DB::table('enrolledstud')
//                                                             ->where('syid',$syid)
//                                                             ->where('levelid',$levelid)
//                                                             ->where('studid',$studid)
//                                                             ->where('sectionid',$sectionid)
//                                                             ->where('deleted',0)
//                                                             ->select(
//                                                                   'levelid',
//                                                                   'sectionid'
//                                                             )->first();
//                   }


                  if($levelid == 14 || $levelid == 15){}
                  else{ $semid = 1; }

                  $checking = DB::table('grades_tranf')
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('subjid',$subjid)
                                    ->where('quarter',$quarter)
                                    ->where('deleted',0)
                                    ->get();

                  if( count($checking) > 0){

                        if($grade == null){
                              DB::table('grades_tranf')
                                    ->where('id',$checking[0]->id)
                                    ->take(1)
                                    ->update([
                                          'deleted'=>1,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }else{
                              DB::table('grades_tranf')
                                    ->where('id',$checking[0]->id)
                                    ->take(1)
                                    ->update([
                                          'qg'=>$grade,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        
                  }else{
                        DB::table('grades_tranf')
                              ->insert([
                                    'studid'=>$studid,
                                    'levelid'=>$levelid,
                                    'sectionid'=>$sectionid,
                                    'subjid'=>$subjid,
                                    'semid'=>$semid,
                                    'quarter'=>$quarter,
                                    'qg'=>$grade,
                                    'syid'=>$syid,
                                    'gdstatus'=>4,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Grade Added!',
                  ]); 

            }catch(\Exception $e){

                  return self::store_error($e);

            }
      }
      
      public static function student_transferein_grade_list(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $studid = $request->get('studid');

            $all_students = array();

            $students = DB::table('grades_tranf')
                        ->join('studinfo',function($join){
                              $join->on('grades_tranf.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })  
                        ->join('enrolledstud',function($join) use($syid){
                              $join->on('grades_tranf.studid','=','enrolledstud.studid');
                              $join->where('enrolledstud.deleted',0);
                              $join->where('enrolledstud.syid',$syid);
                              $join->whereIn('enrolledstud.studstatus',[1,2,4]);
                        })  
                        ->join('sections',function($join) use($syid){
                              $join->on('grades_tranf.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        }) 
                        ->join('gradelevel',function($join) use($syid){
                              $join->on('grades_tranf.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('gradelevel.acadprogid','!=',5);
                        }) 
                        ->join('subjects',function($join) use($syid){
                              $join->on('grades_tranf.subjid','=','subjects.id');
                              $join->where('subjects.deleted',0);
                        })  
                        ->where('grades_tranf.deleted',0)
                        ->where('grades_tranf.syid',$syid)
                        ->where('grades_tranf.studid',$studid)
                        ->where('grades_tranf.semid',$semid)
                        ->select(
                              'subjcode',
                              'subjdesc',
                              'levelname',
                              'sections.sectionname',
                              'sid',
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'enrolledstud.studid',
                              'grades_tranf.id',
                              DB::raw("CONCAT(subjects.subjdesc,' - ',subjects.subjcode) as subjtext"),
                              'quarter',
                              'qg',
                              'gdstatus',
                              'subjid'
                        )
                        ->get();


            foreach($students as $item){
                  array_push( $all_students , $item);
            }
			
			return $all_students;

            $students = DB::table('grades_tranf')
                        ->join('studinfo',function($join){
                              $join->on('grades_tranf.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })  
                        ->join('sh_enrolledstud',function($join) use($syid,$semid){
                              $join->on('grades_tranf.studid','=','sh_enrolledstud.studid');
                              $join->where('sh_enrolledstud.deleted',0);
                              $join->where('sh_enrolledstud.syid',$syid);
                              // $join->where('sh_enrolledstud.semid',$semid);
                              $join->whereIn('sh_enrolledstud.studstatus',[1,2,4]);
                        })  
                        ->join('sections',function($join) use($syid){
                              $join->on('grades_tranf.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        }) 
                        ->join('gradelevel',function($join) use($syid){
                              $join->on('grades_tranf.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('gradelevel.acadprogid',5);
                        }) 
                        ->join('sh_subjects',function($join) use($syid){
                              $join->on('grades_tranf.subjid','=','sh_subjects.id');
                              $join->where('sh_subjects.deleted',0);
                        })  
                        ->where('grades_tranf.syid',$syid)
                        ->where('grades_tranf.semid',$semid)
                        ->where('grades_tranf.studid',$studid)
                        ->where('grades_tranf.deleted',0)
                        ->select(
                              'subjcode',
                              'subjtitle as subjdesc',
                              'levelname',
                              'sections.sectionname',
                              'sid',
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'sh_enrolledstud.studid',
                              DB::raw("CONCAT(sh_subjects.subjtitle,' - ',sh_subjects.subjcode) as subjtext"),
                              'grades_tranf.id',
                              'quarter',
                              'qg',
                              'gdstatus',
                              'subjid'
                        )
                        ->get();

            foreach($students as $item){
                  array_push( $all_students , $item);
            }

            foreach($all_students as $item){
                  $temp_middle = '';
                  $temp_suffix = '';
                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix;
                  }
                  $item->full_name = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;
                  $item->text =  $item->sid.' - '.$item->full_name;
            }

            return $all_students;

      }

      public static function students(Request $request){

            $semid = $request->get('semid');
            $syid = $request->get('syid');

            $all_students = array();

            if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){
                  $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
                  $acadprogid = DB::table('academicprogram')
                                  ->where('principalid',$teacherid)
                                  ->select('id as acadprogid')
                                  ->get();

                  $all_acad = array();

                  foreach( $acadprogid as $item){
                        array_push($all_acad,$item->acadprogid);
                  }

            }else if (auth()->user()->type == 17 || Session::get('currentPortal') == 17){
                  $acadprogid = DB::table('academicprogram')
                                  ->select('id as acadprogid')
                                  ->get();

                  $all_acad = array();
                  foreach( $acadprogid as $item){
                        array_push($all_acad,$item->acadprogid);
                  }
            }else{
                  $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
                  $acadprogid = DB::table('teacheracadprog')
                                          ->where('teacherid',$teacherid)
                                          //->where('syid',$syid)
                                          ->select('acadprogid')
                                          ->where('deleted',0)
                                          ->distinct('acadprogid')
                                          ->get();

                  $all_acad = array();
                  foreach( $acadprogid as $item){
                        array_push($all_acad,$item->acadprogid);
                  }
            }

            $students = DB::table('enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($all_acad){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              })
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.deleted',0)
                              // ->whereIn('enrolledstud.studstatus',[4])
                              ->select(
                                    'enrolledstud.levelid',
                                    'enrolledstud.sectionid',
                                    'sid',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'enrolledstud.studid',
                                    'studinfo.id'
                              )
                              ->get();

            foreach($students as $item){
                  array_push( $all_students , $item);
            }

            $students = DB::table('sh_enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($all_acad){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              })
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.semid',$semid)
                              ->where('sh_enrolledstud.deleted',0)
                              // ->whereIn('sh_enrolledstud.studstatus',[4])
                              ->select(
                                    'sh_enrolledstud.strandid',
                                    'sh_enrolledstud.levelid',
                                    'sh_enrolledstud.sectionid',
                                    'sid',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'sh_enrolledstud.studid',
                                    'studinfo.id'
                              )
                              ->get();

            foreach($students as $item){
                  array_push( $all_students , $item);
            }

            foreach($all_students as $item){
                  $temp_middle = '';
                  $temp_suffix = '';
                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix;
                  }
                  $item->full_name = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;
                  $item->text =  $item->sid.' - '.$item->full_name;
            }

            return $all_students;

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
                  'message'=>'Something went wrong!'
            ]);
      }

      public static function subjects(Request $request){

            $levelid = $request->get('levelid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $strandid = $request->get('strandid');

            if($levelid == 14 || $levelid == 15){
                  $subjects = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null, null, $levelid, null, $syid, $semid, $strandid);
            }else{
                  $subjects = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null, null, $levelid, null, $syid);
            }

            return $subjects;

      }

}
