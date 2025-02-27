<?php

namespace App\Models\FinalGrading;

use Illuminate\Database\Eloquent\Model;
use DB;

class FinalGradingProcess extends Model
{
      public static function final_grading_store_grade($id = null, $grade = null , $acadprogid  = null, $subjid = null, $quarter = null){

                  try{
                        $field = 'qgq' . $quarter;

                        if($acadprogid == 3){
                              DB::table('grading_system_gsgrades')
                                    ->where('id',$id)
                                    ->where('subjid',$subjid)
                                    ->where('deleted',0)
                                    ->take(1)
                                    ->update([
                                          $field => $grade
                                    ]);
                        }
                        else if($acadprogid == 4){
                              DB::table('grading_system_grades_hs')
                                    ->where('id',$id)
                                    ->where('subjid',$subjid)
                                    ->where('deleted',0)
                                    ->take(1)
                                    ->update([
                                          $field => $grade
                                    ]);
                        }

                        return array((object)[
                              'status'=>1,
                              'message'=>'Update Successfully!'
                        ]);

                  }catch(\Exception $e){
                        return $e;
                  }
            

      }

      public static function final_grading_store_grade_type1($gdid = null,  $studid = null,  $fg = null){

            DB::table('gradesdetail')
                  ->where('id',$gdid)
                  ->where('studid',$studid)
                  ->take(1)
                  ->update([
                        'qg'=>$fg,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);
      

      }

      public static function final_grading_grade_submit_type1($id = null, $levelid = null){

            try{
                  DB::table('grades')
                          ->take(1)
                          ->where('id',$id)
                          ->where('levelid',$levelid)
                          ->update([
                              'submitted'=>1,
                              'status'=>0,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'date_submitted'=>\Carbon\Carbon::now('Asia/Manila'),
                          ]);
      
                  DB::table('gradesdetail')
                              ->where('headerid',$id)
                              ->update([
                                      'gdstatus'=>1,
                                      'updatedby'=>auth()->user()->id,
                                      'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                      'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              ]);
                          
                  DB::table('gradelogs')
                      ->insert([
                          'action'=>1,
                          'user_id'=>auth()->user()->id,
                          'gradeid'=>$id,
                          'createdby'=>auth()->user()->id,
                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                      ]);
      
                  $getPrincipalId = DB::table('gradelevel')
                                      ->select('teacher.userid')
                                      ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                                      ->join('teacher','academicprogram.principalid','=','teacher.id')
                                      ->where('gradelevel.id',$levelid)
                                      ->first();
      
                  if(isset($getPrincipalId->id)){
                      DB::table('notifications')
                                  ->insert([
                                      'headerid' => $id,
                                      'type' => '3',
                                      'status' => '0',
                                      'recieverid' => $getPrincipalId->userid
                                  ]);
                  }
      
                  return array((object)[
                          'status'=>1,
                          'data'=>'Submitted Successfully'
                  ]);
      
              }catch(\Exception $e){
                  try{
      
                      DB::table('grades')
                              ->take(1)
                              ->where('id',$id)
                              ->where('levelid',$levelid)
                              ->update([
                                  'submitted'=>1,
                                  'updatedby'=>auth()->user()->id,
                                  'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                  'date_submitted'=>\Carbon\Carbon::now('Asia/Manila'),
                              ]);
      
                      DB::table('gradesdetail')
                                  ->where('headerid',$id)
                                  ->update([
                                      'updatedby'=>auth()->user()->id,
                                      'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                  ]);
      
                      DB::table('gradelogs')
                          ->insert([
                              'action'=>1,
                              'user_id'=>auth()->user()->id,
                              'gradeid'=>$id,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                          ]);
      
      
                      $getPrincipalId = DB::table('gradelevel')
                                      ->select('teacher.userid')
                                      ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                                      ->join('teacher','academicprogram.principalid','=','teacher.id')
                                      ->where('gradelevel.id',$levelid)
                                      ->first();
      
                      if(isset($getPrincipalId->id)){
                          DB::table('notifications')
                                      ->insert([
                                          'headerid' => $id,
                                          'type' => '3',
                                          'status' => '0',
                                          'recieverid' => $getPrincipalId->userid
                                      ]);
                      }
      
                      return array((object)[
                              'status'=>1,
                              'data'=>'Submitted Successfully'
                      ]);
      
      
      
                  }catch(\Exception $e){
      
      
                  }
      
              }

      }

      public static function generate_grade_deatail_type1($syid = null, $semid = null, $gradelevel = null, $sectionid = null, $subjid = null, $quarter = null){
    
            if($gradelevel == 14 || $gradelevel == 15){
    
                $enrolled_students = DB::table('sh_enrolledstud')
                                        ->join('studinfo',function($join){
                                            $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                        })
                                        ->where('sh_enrolledstud.deleted',0)
                                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                        ->where('sh_enrolledstud.levelid',$gradelevel)
                                        ->where('sh_enrolledstud.sectionid',$sectionid)
                                        ->where('sh_enrolledstud.syid',$syid)
                                        ->where('sh_enrolledstud.semid',$semid)
                                        ->select('studid','gender','firstname','lastname')
                                        ->select('studid','gender','firstname','lastname')
                                        ->orderby('gender','desc')
                                        ->orderby('lastname')
                                        ->get();
    
    
            }else{
    
                $enrolled_students = DB::table('enrolledstud')
                                        ->join('studinfo',function($join){
                                            $join->on('enrolledstud.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                        })
                                        ->where('enrolledstud.deleted',0)
                                        ->whereIn('enrolledstud.studstatus',[1,2,4])
                                        ->where('enrolledstud.levelid',$gradelevel)
                                        ->where('enrolledstud.sectionid',$sectionid)
                                        ->where('enrolledstud.syid',$syid)
                                        ->select('studid','gender','firstname','lastname')
                                        ->orderby('gender','desc')
                                        ->orderby('lastname')
                                        ->get();
    
            }
    
            $check_if_header_exist = DB::table('grades')
                                ->where('subjid',$subjid)
                                ->where('syid',$syid)
                                ->where('semid',$semid)
                                ->where('levelid',$gradelevel)
                                ->where('sectionid',$sectionid)
                                ->where('quarter',$quarter)
                                ->where('deleted',0)
                                ->select('status','id','submitted')
                                ->first();
    
            if(!isset($check_if_header_exist->status)){
    
                if($gradelevel == 14 && $gradelevel == 15){
                    $hps_sem = $semid;
                }else{
                    $hps_sem = 1;
                }
    
                $gradeId = DB::table('grades')->insertGetId([
                    'syid'=>$syid,
                    'levelid'=>$gradelevel,
                    'quarter'=>$quarter,
                    'sectionid'=>$sectionid,
                    'subjid'=>$subjid,
                    'deleted'=>0,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'submitted'=>0,
                    'status'=>0,
                    'wwhr1'=>0,
                    'wwhr2'=>0,
                    'wwhr3'=>0,
                    'wwhr4'=>0,
                    'wwhr5'=>0,
                    'wwhr6'=>0,
                    'wwhr7'=>0,
                    'wwhr8'=>0,
                    'wwhr9'=>0,
                    'wwhr0'=>0,
                    'pthr1'=>0,
                    'pthr2'=>0,
                    'pthr3'=>0,
                    'pthr4'=>0,
                    'pthr5'=>0,
                    'pthr6'=>0,
                    'pthr7'=>0,
                    'pthr8'=>0,
                    'pthr9'=>0,
                    'pthr0'=>0,
                    'qahr1'=>0,
                    'semid'=>$semid,
                ]);
    
                $check_if_header_exist = DB::table('grades')
                    ->where('subjid',$subjid)
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->where('levelid',$gradelevel)
                    ->where('sectionid',$sectionid)
                    ->where('quarter',$quarter)
                    ->where('deleted',0)
                    ->select('status','id','submitted')
                    ->first();
    
            }
    
            if(isset($check_if_header_exist->status)){
    
                foreach($enrolled_students as $item){
    
                    $check_student_grades_exist = DB::table('gradesdetail')
                                                    ->where('headerid',$check_if_header_exist->id)
                                                    ->where('studid',$item->studid)
                                                    ->select('id','qg')
                                                    ->first();
    
                    if(isset($check_student_grades_exist->qg)){
              
                        $item->qg = $check_student_grades_exist->qg;
                        $item->id = $check_student_grades_exist->id;
    
                    }else{
    
                        $id = DB::table('gradesdetail')
                            ->insertGetId([
                                'headerid'=>$check_if_header_exist->id,
                                'studid'=>$item->studid,
                                'studname'=>$item->lastname.', '.$item->firstname,
                                'wwws'=>0,
                                'ptws'=>0,
                                'qaws'=>0,
                                'wwps'=>0,
                                'ptps'=>0,
                                'qaps'=>0,
                                'wwtotal'=>0,
                                'pttotal'=>0,
                                'qatotal'=>0,
                                'ig'=>0,
                                'qg'=>0,
                                'ww1'=>0,
                                'ww2'=>0,
                                'ww3'=>0,
                                'ww4'=>0,
                                'ww5'=>0,
                                'ww6'=>0,
                                'ww7'=>0,
                                'ww8'=>0,
                                'ww9'=>0,
                                'ww0'=>0,
                                'pt1'=>0,
                                'pt2'=>0,
                                'pt3'=>0,
                                'pt4'=>0,
                                'pt5'=>0,
                                'pt6'=>0,
                                'pt7'=>0,
                                'pt8'=>0,
                                'pt9'=>0,
                                'pt0'=>0,
                                'qa1'=>0,
                                'remarks'=>0,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);

                        $item->id = $id;
                        $item->qg = null;
                    }
    
                }
    
                return $enrolled_students;
    
            }
    
          }
}
