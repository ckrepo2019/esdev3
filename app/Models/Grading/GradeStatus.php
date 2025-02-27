<?php

namespace App\Models\Grading;
use DB;
use \Carbon\Carbon;
use App\Models\Grading\GradeSchool;
use App\Models\Grading\HighSchool;
use App\Models\Grading\SeniorHigh;
use App\Models\Grading\PreSchoolPer;

use Illuminate\Database\Eloquent\Model;

class GradeStatus extends Model
{
   

      public static function activeSy(){
            return DB::table('sy')->where('isactive',1)->first();
      }
      public static function activeSem(){
            return DB::table('semester')->where('isactive',1)->first();
      }

      public static function get_grade_status($teacherid = null, $syid = null, $semid = null){

            // $activesy = DB::table('sy')->where('isactive',1)->first();
            // $activeSem = DB::table('semester')->where('isactive',1)->first();

            $allsubjects = array();

            $gssubjects = GradeSchool::get_sections(null, $syid);
            $hssubjects = HighSchool::get_sections(null, $syid);
            $shsubjects = SeniorHigh::get_sections(null,$syid,$semid);
            $pssubjects = PreSchoolPer::get_sections(null,$syid);

            foreach($gssubjects as $item){

                  array_push($allsubjects, $item);

            }
            
            foreach($hssubjects as $item){

                  array_push($allsubjects, $item);

            }

            foreach($shsubjects as $item){

                  array_push($allsubjects, $item);

            }

            foreach($pssubjects as $item){

                  array_push($allsubjects, $item);

            }

         

            $notgeneratedCount = 0;
            $gstatus = array();

            foreach($allsubjects as $item){

                  $check = DB::table('grading_sytem_gradestatus')
                              ->where('sectionid',$item->id)
                              ->where('subjid',$item->subjid)
                              ->where('grading_sytem_gradestatus.deleted',0)
                              ->where('syid',$syid)
                              ->get();

                  if(count($check) == 0){

                        $notgeneratedCount += 1;
                        $item->genstatus = 0;
                        $item->gstatus = $gstatus;

                  }else{
                        
                        $item->genstatus = 1;
                        $item->gstatus = $check;

                  }
            }

            return view('superadmin.pages.gradingsystem.grade_status_table')
                        ->with('allsubjects',$allsubjects)
                        ->with('notgeneratedCount',$notgeneratedCount);
         

      }

      //grading v5
      public static function get_grade_status_preschool($teacherid = null, $syid = null, $semid = null){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;
            }

            // $allsubjects = array();

            // $pssubjects = PreSchoolPer::get_sections($teacherid);

            // foreach($pssubjects as $item){

            //       array_push($allsubjects, $item);

            // }
            $allsubjects = DB::table('sections')
                              ->where('teacherid',$teacherid)
                              ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('sections.deleted',0);
                                    $join->where('gradelevel.acadprogid',2);
                              })
                              ->select('sections.id','levelid','sectionname')
                              ->get();

            foreach($allsubjects as $item){
                  $item->q1status = null;
                  $item->q2status = null;
                  $item->q3status = null;
                  $item->q4status = null;
                  $item->subjid = 0;
                  $item->subjcode = 'Pre-school';
            }

           

            $notgeneratedCount = 0;
            $gstatus = array();

            foreach($allsubjects as $item){

                  $check = DB::table('grading_sytem_gradestatus')
                              ->where('sectionid',$item->id)
                              ->where('subjid',$item->subjid)
                              ->where('syid',$syid)
                              ->where('grading_sytem_gradestatus.deleted',0)
                              ->get();

                 

                  if(count($check) == 0){

                        $notgeneratedCount += 1;
                        $item->genstatus = 0;
                        $item->gstatus = $gstatus;

                  }else{
                        
                        $item->genstatus = 1;
                        $item->gstatus = $check;

                  }
            }

            return view('superadmin.pages.gradingsystem.grade_status_table')
                        ->with('allsubjects',$allsubjects)
                        ->with('notgeneratedCount',$notgeneratedCount);
         

      }

      //grading v5

      public static function generate_grade_status( $section = null, $subject = null, $gradelevel = null, $syid = null, $semid = null){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }
            if($semid == null){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;

            }

            $check = 0;

            try{

                  $check = DB::table('grading_sytem_gradestatus')
                                          ->where('subjid',$subject)
                                          ->where('sectionid',$section)
                                          ->where('levelid',$gradelevel)
                                          ->where('grading_sytem_gradestatus.deleted',0)
                                          ->count();

            }
            catch(\Exception $e){

                  DB::table('zerrorlogs')
                        ->insert([
                        'error'=>$e,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
                  return 0;

            }

            if($check == 0){

                  try{

                        DB::table('grading_sytem_gradestatus')
                                          ->insert([
                                                'subjid'=>$subject,
                                                'sectionid'=>$section,
                                                'levelid'=>$gradelevel,
                                                'syid'=>$syid,
                                                'semid'=>$semid,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                        return 1;

                  }
                  catch(\Exception $e){

                        DB::table('zerrorlogs')
                                    ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                        return 0;

                  }

            }
            else{
                  
                  return 0;
            
            }
          



      }

      public static function submit_grades($quarter, $did){

            try{

                  DB::table('grading_sytem_gradestatus')
                                    ->where('id',$did)
                                    ->where('createdby',auth()->user()->id)
                                    ->where('grading_sytem_gradestatus.deleted',0)
                                    ->update([
                                          'q'.$quarter.'status'=>1,
                                          'q'.$quarter.'datesubmitted'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id
                                    ]);

                  DB::table('grading_system_gradestatus_logs')
                        ->insert([
                              'headerid'=>$did,
                              'status'=>1,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  $gradinfo =  DB::table('grading_sytem_gradestatus')
                        ->where('id',$did)
                        ->where('grading_sytem_gradestatus.deleted',0)
                        ->select('sectionid','subjid','levelid','createdby')  
                        ->first();

                  
                  $userid = DB::table('gradelevel')
                                    ->where('gradelevel.id',$gradinfo->levelid)
                                    ->where('gradelevel.deleted',0)
                                    ->join('academicprogram',function($join){
                                          $join->on('gradelevel.acadprogid','=','academicprogram.id');
                                    })
                                    ->join('teacher',function($join){
                                          $join->on('academicprogram.principalid','=','teacher.id');
                                          $join->where('teacher.deleted',0);
                                    })
                                    ->select('userid')
                                    ->first();
                              
                  if( $gradinfo->levelid == 14 || $gradinfo->levelid == 15){

                        $subjects = DB::table('sh_subjects')
                              ->where('id', $gradinfo->subjid)
                              ->select('subjtitle as subjdesc')
                              ->first();

                  }
                  elseif( $gradinfo->levelid == 2 || $gradinfo->levelid == 3 ||  $gradinfo->levelid == 4){
                        $subjects = (object)[
                              'subjdesc'=>'Pre-school'
                        ];
                  }
                  else{

                        $subjects = DB::table('subjects')
                              ->where('id', $gradinfo->subjid)
                              ->select('subjdesc')
                              ->first();

                  }

                  $section = DB::table('sections')
                              ->where('id', $gradinfo->sectionid)
                              ->select('sectionname')
                              ->first();



                  DB::table('znotification')
                              ->insert([
                                    'link'=>'#',
                                    'reciever'=> $userid->userid,
                                    'message'=> 'Submitted Grade : '.$section->sectionname.' - '.$subjects->subjdesc.' grades was submitted',
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  return 1;

            }
            catch(\Exception $e){

                  DB::table('zerrorlogs')
                              ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  return 0;

            }
            
      }

      public static function approve_grade($quarter, $gsid){

            try{

                  $gradinfo =  DB::table('grading_sytem_gradestatus')
                                    ->where('id',$gsid)
                                    ->select('sectionid','subjid','levelid','createdby')  
                                    ->where('grading_sytem_gradestatus.deleted',0)
                                    ->first();

                  if( $gradinfo->levelid == 14 || $gradinfo->levelid == 15){
                        if($quarter == 3){
                              $quarter = 1;
                        }
                        else if($quarter == 4){
                              $quarter = 2;
                        }
                  }


                  DB::table('grading_sytem_gradestatus')
                                    ->where('id',$gsid)
                                    ->where('q'.$quarter.'status',1)
                                    ->where('grading_sytem_gradestatus.deleted',0)
                                    ->update([
                                          'q'.$quarter.'status'=>2,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id
                                    ]);

                  DB::table('grading_system_gradestatus_logs')
                        ->insert([
                              'headerid'=>$gsid,
                              'status'=>2,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  DB::table('grading_system_gradestatus_logs')
                        ->insert([
                              'headerid'=>$gsid,
                              'status'=>2,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  $gradinfo =  DB::table('grading_sytem_gradestatus')
                        ->where('id',$gsid)
                        ->where('grading_sytem_gradestatus.deleted',0)
                        ->select('sectionid','subjid','levelid','createdby')  
                        ->first();

                              
                  if( $gradinfo->levelid == 14 || $gradinfo->levelid == 15){

                        $subjects = DB::table('sh_subjects')
                              ->where('id', $gradinfo->subjid)
                              ->select('subjtitle as subjdesc')
                              ->first();

                  }else{

                        $subjects = DB::table('subjects')
                              ->where('id', $gradinfo->subjid)
                              ->select('subjdesc')
                              ->first();

                  }

                  $section = DB::table('sections')
                              ->where('id', $gradinfo->sectionid)
                              ->select('sectionname')
                              ->first();

                  DB::table('znotification')
                              ->insert([
                                    'link'=>'#',
                                    'reciever'=> $gradinfo->createdby,
                                    'message'=> 'Approved Grade : '.$section->sectionname.' - '.$subjects->subjdesc.' grades was approved',
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);


                  return 1;

            }
            catch(\Exception $e){

                  DB::table('zerrorlogs')
                              ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  return 0;

            }
            
      }

      public static function post_grade($quarter, $gsid){

            try{

                  $gradinfo =  DB::table('grading_sytem_gradestatus')
                                    ->join('gradelevel',function($join){
                                          $join->on('grading_sytem_gradestatus.levelid','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->where('grading_sytem_gradestatus.id',$gsid)
                                    ->where('grading_sytem_gradestatus.deleted',0)
                                    ->select('sectionid','subjid','levelid','createdby','acadprogid')  
                                    ->first();


                  if( $gradinfo->acadprogid == 5){

                        $subjects = DB::table('sh_subjects')
                              ->where('id', $gradinfo->subjid)
                              ->select('subjtitle as subjdesc')
                              ->first();

                        $field = 'qgq'.$quarter;


                        $studentGrades = DB::table('grading_system_grades_sh')
                                          ->where('levelid',$gradinfo->levelid)
                                          ->where('subjid',$gradinfo->subjid)
                                          ->where('sectionid',$gradinfo->sectionid)
                                          ->where('syid',self::activeSy()->id)
                                          ->where('semid',self::activeSem()->id)
                                          ->where('studid','!=',0)
                                          ->where('deleted',0)
                                          ->select('studid',$field)
                                          ->get();

                        foreach($studentGrades as $item){

                              
                              DB::table('tempgradesum')
                                    ->updateOrInsert(
                                          [
                                                'studid'=>$item->studid,
                                                'subjid'=>$gradinfo->subjid,
                                                'syid'=>self::activeSy()->id,
                                                'semid'=>self::activeSem()->id,
                                          ],
                                          [
                                          
                                          'q'.$quarter=>$item->$field,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              self::remove_pending_status( $item->studid , $gradinfo->sectionid , $gradinfo->subjid , $gradinfo->levelid, $temp_quarter);

                        }

                       
                                             

                  }
                  elseif( $gradinfo->acadprogid == 4){

                        $subjects = DB::table('subjects')
                              ->where('id', $gradinfo->subjid)
                              ->select('subjdesc')
                              ->first();

                        $field = 'qgq'.$quarter;

                        $studentGrades = DB::table('grading_system_grades_hs')
                                          ->where('levelid',$gradinfo->levelid)
                                          ->where('subjid',$gradinfo->subjid)
                                          ->where('sectionid',$gradinfo->sectionid)
                                          ->where('syid',self::activeSy()->id)
                                          ->where('studid','!=',0)
                                          ->where('deleted',0)
                                          ->select('studid',$field)
                                          ->get();

                        foreach($studentGrades as $item){

                              DB::table('tempgradesum')
                                    ->updateOrInsert(
                                          [
                                                'studid'=>$item->studid,
                                                'subjid'=>$gradinfo->subjid,
                                                'syid'=>self::activeSy()->id
                                            ],
                                          [
                                          'semid'=>1,
                                          'q'.$quarter=>$item->$field,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              
                        }


                  }
                  else{

                        $subjects = DB::table('subjects')
                              ->where('id', $gradinfo->subjid)
                              ->select('subjdesc')
                              ->first();

                        $field = 'qgq'.$quarter;

                        $studentGrades = DB::table('grading_system_gsgrades')
                                          ->where('levelid',$gradinfo->levelid)
                                          ->where('subjid',$gradinfo->subjid)
                                          ->where('sectionid',$gradinfo->sectionid)
                                          ->where('syid',self::activeSy()->id)
                                          ->where('studid','!=',0)
                                          ->where('deleted',0)
                                          ->select('studid',$field)
                                          ->get();

                        foreach($studentGrades as $item){


                              DB::table('tempgradesum')
                                    ->updateOrInsert(
                                          [
                                                'studid'=>$item->studid,
                                                'subjid'=>$gradinfo->subjid,
                                                'syid'=>self::activeSy()->id
                                          ],
                                          [
                                          'semid'=>1,
                                          'q'.$quarter=>$item->$field,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                        }

                  }

                  DB::table('grading_sytem_gradestatus')
                                    ->where('id',$gsid)
                                    ->where('q'.$quarter.'status',2)
                                    ->where('grading_sytem_gradestatus.deleted',0)
                                    ->update([
                                          'q'.$quarter.'status'=>3,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'q'.$quarter.'dateposted'=>\Carbon\Carbon::now('Asia/Manila'),
                                    ]);

                  $section = DB::table('sections')
                              ->where('id', $gradinfo->sectionid)
                              ->select('sectionname')
                              ->first();

                  DB::table('znotification')
                              ->insert([
                                    'link'=>'#',
                                    'reciever'=> $gradinfo->createdby,
                                    'message'=> 'Posted Grade : '.$section->sectionname.' - '.$subjects->subjdesc.' grades was added posted',
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  DB::table('grading_system_gradestatus_logs')
                              ->insert([
                                    'headerid'=>$gsid,
                                    'status'=>3,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  return 1;

            }
            catch(\Exception $e){

                  DB::table('zerrorlogs')
                              ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  return 0;

            }
            
      }

      

      public static function check_grade_status($sectionid = null, $subjectid = null, $quarter = null, $syid = null){

            if($syid == null){
                  $activesy = DB::table('sy')->where('isactive',1)->first();
            }else{
                  $activesy = DB::table('sy')->where('id',$syid)->first();
            }

            $data = array();

            $checkStatus = DB::table('grading_sytem_gradestatus')
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjectid)
                              ->where('grading_sytem_gradestatus.deleted',0)
                              ->where('syid',$activesy->id)
                              ->where('deleted',0)
                              ->first();

            if(isset($checkStatus->id) == 0){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Please generate grade status"
                  ]);

                  return $data;

            }
            else if($quarter != null){

                  if($quarter == 2){

                        if($checkStatus->q1status != 3){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"1st Quarter grades are not yet posted"
                              ]);
            
                              return $data;

                        }

                        
                  }
                  if($quarter == 3){

                        if($checkStatus->q2status != 3){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"2nd Quarter grades are not yet posted"
                              ]);

                              return $data;
                              
                        }
                        
                  }
                  if($quarter == 4){

                         if($checkStatus->q3status != 3){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"3rd Quarter grades are not yet posted"
                              ]);
            
                              return $data;
                              
                        }
                  }

            }

            $data = array((object)[
                  'status'=>1,
                  'data'=>$checkStatus
            ]);

            return $data;

      }


      public static function check_grade_status_sh($sectionid = null, $subjectid = null, $quarter = null, $syid = null, $semid = null ){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }
            if($semid == null){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;
            }

            $checkStatus = DB::table('grading_sytem_gradestatus')
                              ->where('grading_sytem_gradestatus.deleted',0)
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjectid)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->where('deleted',0)
                              ->first();

            if(isset($checkStatus->id) == 0){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Please generate grade status"
                  ]);

                  return $data;

            }
            else{

                
                  if($quarter == 2){

                        if($checkStatus->q1status != 3 && $checkStatus->q1status != 2){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"1st Quarter grades are not yet posted"
                              ]);
            
                              return $data;

                        }
                        
                  }
                 
                  if($quarter == 4){

                         if($checkStatus->q3status != 3 && $checkStatus->q1status != 2){

                              $data = array((object)[
                                    'status'=>0,
                                    'data'=>"3rd Quarter grades are not yet posted"
                              ]);
            
                              return $data;
                              
                        }
                  }

            }

            $data = array((object)[
                  'status'=>1,
                  'data'=>$checkStatus
            ]);

            return $data;

      }

      public static function pending_grade($quarter, $gsid){

            try{

                  $gradinfo =  DB::table('grading_sytem_gradestatus')
                                    ->where('id',$gsid)
                                    ->where('grading_sytem_gradestatus.deleted',0)
                                    ->select('sectionid','subjid','levelid','createdby')  
                                    ->first();

                  if( $gradinfo->levelid == 14 || $gradinfo->levelid == 15){
                        if($quarter == 3){
                              $quarter = 1;
                        }
                        else if($quarter == 4){
                              $quarter = 2;
                        }
                  }

                  DB::table('grading_sytem_gradestatus')
                                    ->where('id',$gsid)
                                    ->where('grading_sytem_gradestatus.deleted',0)
                                    ->where('q'.$quarter.'status','!=',3)
                                    ->update([
                                          'q'.$quarter.'status'=>4,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                    ]);

                  
                  $gradinfo =  DB::table('grading_sytem_gradestatus')
                                    ->where('id',$gsid)
                                     ->where('grading_sytem_gradestatus.deleted',0)
                                    ->select('sectionid','subjid','levelid','createdby')  
                                    ->first();
                                          
                  if( $gradinfo->levelid == 14 || $gradinfo->levelid == 15){

                        $subjects = DB::table('sh_subjects')
                              ->where('id', $gradinfo->subjid)
                              ->select('subjtitle as subjdesc')
                              ->first();

                  }else{

                        $subjects = DB::table('subjects')
                              ->where('id', $gradinfo->subjid)
                              ->select('subjdesc')
                              ->first();

                  }

                  $section = DB::table('sections')
                              ->where('id', $gradinfo->sectionid)
                              ->select('sectionname')
                              ->first();
                              


                  DB::table('znotification')
                              ->insert([
                                    'link'=>'#',
                                    'reciever'=> $gradinfo->createdby,
                                    'message'=> 'Pending Grades : '.$section->sectionname.' - '.$subjects->subjdesc.' grades was added to pending',
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);


                  DB::table('grading_system_gradestatus_logs')
                        ->insert([
                              'headerid'=>$gsid,
                              'status'=>4,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return 1;

            }
            catch(\Exception $e){

                  DB::table('zerrorlogs')
                              ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  return 0;

            }
            
      }
    
      public static function get_advisory_sections($teacherid = null, $syid = null, $semid = null){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }

            return DB::table('sectiondetail')
                        ->where('sectiondetail.deleted',0)
                        ->where('sectiondetail.teacherid',$teacherid)
                        ->where('syid',$syid)
                        ->orderBy('sortid')
                        ->join('sections',function($join){
                              $join->on('sectiondetail.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->join('gradelevel',function($join){
                              $join->on('sections.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                        })
                        ->select('levelid','sectionid','sectionname','levelname')
                        ->get();


      }

      public static function filtered_grades_status($sectionid = null, $subjid = null, $syid = null, $semid = null){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }
            if($semid == null){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;
            }

            return array(DB::table('grading_sytem_gradestatus')   
                        ->where('sectionid',$sectionid)
                         ->where('grading_sytem_gradestatus.deleted',0)
                        // ->where('semid',$semid)
                        // ->where('syid',$syid)
                        ->where('subjid',$subjid)
                        ->first());


      }

      public static function remove_pending_status( $studid = null, $sectionid = null, $subjid = null, $levelid = null, $quarter = null ){

            $syid = DB::table('sy')->where('isactive',1)->first()->id;
            $semid = 1;

            if($levelid == 14 || $levelid == 15){
                  $semid = DB::table('semester')->where('isactive',1)->first()->id;
            }     

            DB::table('grading_system_pending_grade')
                  ->where('deleted',0)
                  ->where('studid', $studid)
                  ->where('syid', $syid)
                  ->where('semid', $semid)
                  ->where('subjid', $subjid)
                  ->where('levelid', $levelid)
                  ->where('quarter', $quarter)
                  ->where('sectionid', $sectionid)
                  ->update([
                        'deleted'=>1,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                  ]);

            return array((object)[
                  'status'=>0,
                  'data'=>'Pending status is removed!'
            ]);

      }

}
