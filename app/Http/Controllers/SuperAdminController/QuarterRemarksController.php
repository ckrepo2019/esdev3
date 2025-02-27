<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session; 

class QuarterRemarksController extends \App\Http\Controllers\Controller
{
      // public static function get_gradelevel(Request $request){

      //       $syid = $request->get('syid');

      //       $acad = self::get_acad($syid);

      //       if(Session::get('currentPortal') == 2){
                  
      //             $teacherid = DB::table('teacher')
      //                               ->where('deleted',0)
      //                               ->where('tid',auth()->user()->email)
      //                               ->first();
      
      //             $gradelevel = DB::table('gradelevel')
      //                         ->where('deleted',0)
      //                         ->whereIn('acadprogid',$acad)
      //                         ->where('gradelevel.acadprogid','!=',6)
      //                         ->orderBy('sortid')
      //                         ->select(
      //                               'gradelevel.levelname as text',
      //                               'gradelevel.id',
      //                               'acadprogid'
      //                         )
      //                         ->get(); 
      
      //       }else{
      
      //             $teacherid = DB::table('teacher')
      //                               ->where('tid',auth()->user()->email)
      //                               ->select('id')
      //                               ->first()
      //                               ->id;
      
      //             $gradelevel = DB::table('gradelevel')
      //                         ->where('deleted',0)
      //                         ->where('gradelevel.acadprogid','!=',6)
      //                         ->whereIn('gradelevel.acadprogid',$acad)
      //                         ->orderBy('sortid')
      //                         ->select(
      //                               'gradelevel.levelname as text',
      //                               'gradelevel.id',
      //                               'acadprogid'
      //                         )
      //                         ->get(); 
      //       }


      //       return $gradelevel;

      // }


      // public static function get_acad($syid = null){

      //       if(auth()->user()->type == 17){
      //             $acadprog = DB::table('academicprogram')
      //                                     ->select('id')
      //                                     ->get();
      //       }
      //       else{

      //             $teacherid = DB::table('teacher')
      //                               ->where('tid',auth()->user()->email)
      //                               ->select('id')
      //                               ->first()
      //                               ->id;

      //             if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){

      //                   $acadprog = DB::table('academicprogram')
      //                                     ->where('principalid',$teacherid)
      //                                     ->get();

      //             }else{

      //                   $acadprog = DB::table('teacheracadprog')
      //                               ->where('teacherid',$teacherid)
      //                               ->where('acadprogutype',Session::get('currentPortal'))
      //                               ->where('deleted',0)
      //                               ->where('syid',$syid)
      //                               ->select('acadprogid as id')
      //                               ->distinct('acadprogid')
      //                               ->get();
      //             }
      //       }


      //       $acadprog_list = array();
      //       foreach($acadprog as $item){
      //             array_push($acadprog_list,$item->id);
      //       }

      //       return $acadprog_list;

      // }

      // public static function get_student_grades_ajax(Request $request){

      //       $studid = $request->get('studid');
      //       $syid = $request->get('syid');

      //       return self::get_student_grades($syid,$studid);

      // }

      public static function get_student_grades(Request $request){

            $studid = $request->get('studid');
            $syid = $request->get('syid');

            $student_grade = DB::table('quarterremarks')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->get();

            return $student_grade;

      }


      public static function store_grades(Request $request){

            $syid = $request->get('syid');
            $studid = $request->get('studid');
            $quarter = $request->get('quarter');
            $value = $request->get('value');


            try{
                  $quarter_val = 'q'.$quarter.'remarks';
                  $check_if_exist = DB::table('quarterremarks')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->first();
                              
                  if(isset($check_if_exist->id)){
                        DB::table('quarterremarks')
                                    ->where('id',$check_if_exist->id)
                                    ->update([
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          $quarter_val=>$value
                                    ]);
                        
                  }else{
                        DB::table('quarterremarks')
                                    ->insert([
                                          'syid'=>$syid,
                                          'studid'=>$studid,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          $quarter_val=>$value
                                    ]);
                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!',
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
            
      }

      public static function teacher_class($syid = null,Request $request){

            if($syid == null){
                  if($request->get('syid') != null){
                        $syid = $request->get('syid');
                  }
                  else{
                        $syid = DB::table('sy')
                                    ->where('isactive',1)
                                    ->first()
                                    ->id;
                  }
            }
            
            if(Session::get('currentPortal')== 17){
                  $teacher = DB::table('teacher')
                                    ->where('deleted',0)
                                    ->select('id')
                                    ->get();
            }else{
                  $teacher = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->where('deleted',0)
                        ->select('id')
                        ->get();
            }

            $sections = DB::table('sectiondetail')
                        ->join('sections',function($join){
                              $join->on('sectiondetail.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->where('sectiondetail.syid',$syid)
                        ->where('sectiondetail.deleted',0)
                        ->whereIn('sectiondetail.teacherid', collect($teacher)->pluck('id'))
                        ->select(
                              'levelid',
                              'sectionname',
                              'sectionname as text',
                              'sections.id'
                        )
                        ->get();

            $all_info = array();

            foreach($sections as $item){

                  if($item->levelid == 14 || $item->levelid == 15){
                        $students = DB::table('sh_enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->where('sh_enrolledstud.sectionid',$item->id)
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->distinct('studid')
                              ->select(
                                    'sid',
                                    'studid as id',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                              )
                              ->orderBy('studentname','asc')
                              ->get();
                  }else{

                        $students = DB::table('enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->where('enrolledstud.sectionid',$item->id)
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.deleted',0)
                              ->select(
                                    'sid',
                                    'studid as id',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                              )
                              ->orderBy('studentname','asc')
                              ->get();
                              
                  }

                  

                  foreach($students as $student_item){
                        $middlename = explode(" ",$student_item->middlename);
                        $temp_middle = '';
                        if($middlename != null){
                              foreach ($middlename as $middlename_item) {
                                    if(strlen($middlename_item) > 0){
                                    $temp_middle .= $middlename_item[0].'.';
                                    } 
                              }
                        }
                        $student_item->student=$student_item->lastname.', '.$student_item->firstname.' '.$student_item->suffix.' '.$temp_middle;
                        $student_item->text = $student_item->sid.' - '.$student_item->student;
                  }

                  array_push($all_info,(object)[
                        'levelid'=>$item->levelid,
                        'id'=>$item->id,
                        'sectionname'=>$item->sectionname,
                        'text'=>$item->sectionname,
                        'students'=>$students
                  ]);

            }

            return $all_info;

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
                  'module'=>1,
                  'message'=>$message,
                  'createdby'=>auth()->user()->id,
                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
      }
      

      

}
