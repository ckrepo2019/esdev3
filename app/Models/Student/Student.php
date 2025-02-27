<?php

namespace App\Models\Student;
use DB;


use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

      public static function activeSy(){

            return DB::Table('sy')->where('isactive',1)->select('id')->first()->id;
      
      }

      public static function activeSem(){
            $activeQuarter = DB::table('grading_system_student_evlstp')->where('status',1)->first();
            if(isset($activeQuarter->id)){
                return $activeQuarter->id;
            }else{
                return 0;
            }
      
      }

      public static function check_if_subject_exist($subject = null, $levelid = null, $sectionid = null, $teacherid = null, $blokid = null){

            $acadprog = self::check_student_academic_program($levelid);

            if(!isset($acadprog->acadprogid)){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"Unable to detect student academic program."
                  ]);

                  return $data;

            }

            if($acadprog->acadprogid == 5){

                  if($sectionid != null){

                         $count = DB::table('sh_classsched')
                                    ->where('glevelid',$levelid)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subject)
                                    ->where('syid',self::activeSy())
                                    ->where('semid',self::activeSem())
                                    ->where('deleted',0)
                                    ->where('teacherid',$teacherid)  
                                    ->count();

                        if($count > 0){

                              $data =  array((object)[
                                    'status'=>1,
                                    'data'=>$count
                              ]);
            
                              return $data;

                        }
                                    
                  }
                  
                  if($blokid != null){

                        $count = DB::table('sh_blocksched')
                                   ->where('blockid',$blokid)
                                   ->where('subjid',$subject)
                                   ->where('teacherid',$teacherid)
                                   ->where('syid',self::activeSy())
                                   ->where('semid',self::activeSem())
                                   ->where('deleted',0)
                                    
                                   ->count();

                       if($count > 0){

                             $data =  array((object)[
                                   'status'=>1,
                                   'data'=>$count
                             ]);
           
                             return $data;

                       }
                                   
                 }
            }
            else{

                  $count = Db::table('assignsubj')
                              ->join('assignsubjdetail',function($join) use($subject, $teacherid){
                                    $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                                    $join->where('subjid',$subject);
                                    $join->where('teacherid',$teacherid);
                                    $join->where('assignsubjdetail.deleted',0);
                              })
                              ->where('sectionid',$sectionid)
                              ->where('glevelid',$levelid)
                              ->where('syid',self::activeSy())
                              ->where('assignsubj.deleted',0)
                              ->count();

                  if($count > 0){

                        $data =  array((object)[
                              'status'=>1,
                              'data'=>$count
                        ]);

                        return $data;

                  }

            }

            $data =  array((object)[
                  'status'=>0,
                  'data'=>'No Results Found'
            ]);

            return $data;
        


      }



      public static function generate_grading_header(
            $studid = null, 
            $levelid = null,
            $subjid = null, 
            $sectionid = null, 
            $teacherid = null
      ){

            try{

                  $headerid = DB::table('grading_system_student_header')
                                          ->insertGetId([
                                                'studid'=>$studid,
                                                'levelid'=>$levelid,
                                                'subjid'=>$subjid,
                                                'sectionid'=>$sectionid,
                                                'teacherid'=>$teacherid,
                                                'syid'=>self::activeSy(),
                                                'semid'=>1
                                          ]);

                  $data =  array((object)[
                              'status'=>1,
                              'data'=>$headerid
                        ]);


                  return $data;


                  

            }catch(Exception $e){

                  DB::table('zerrorlogs')
                                    ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"Something went wrong."
                  ]);

                  return $data;



            }


      }

      public static function check_student_academic_program($levelid = null){

            return DB::table('gradelevel')->where('id',$levelid)->where('deleted',0)->select('acadprogid')->first();

      }


      public static function get_grading_header(
            $studid = null, 
            $sectionid = null, 
            $subjid = null, 
            $teacherid = null,
            $levelid = null
      ){

            $acadprog = self::check_student_academic_program($levelid);

            if(!isset($acadprog->acadprogid)){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"Unable to detect student academic program."
                  ]);

                  return $data;

                              
            }

            try{

                  $grading_header = DB::table('grading_system_student_header')
                                          ->where('studid',$studid)
                                          ->where('sectionid',$sectionid)
                                          ->where('subjid',$subjid)
                                          ->where('teacherid',$teacherid)
                                          ->where('levelid',$levelid)
                                          ->where('deleted',0)
                                          ->where('syid',self::activeSy())
                                          ->where('semid',1)
                                          ->get();

                  $data =  array((object)[
                              'status'=>1,
                              'data'=>$grading_header
                        ]);


                  return $data;


                  

            }catch(Exception $e){

                  DB::table('zerrorlogs')
                                    ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"Something went wrong."
                  ]);

                  return $data;



            }

      }

      public static function check_evaluation($headerid = null){

            $checkForEvalution = DB::table('grading_system_student_evaluation')
                                          ->where('studheader',$headerid)
                                          ->where('deleted',0)
                                          ->select('q1val','q2val','q3val','q4val','gsid')
                                          ->get();

            return  $checkForEvalution ;

      }
      
      public static function student_profile($studid = null){

            return DB::table('studinfo')
                        ->leftJoin('gradelevel',function($join){
                              $join->on('studinfo.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                        }) 
                        ->join('studentstatus',function($join){
                              $join->on('studinfo.studstatus','=','studentstatus.id');
                        })   
                        ->leftJoin('sections',function($join){
                              $join->on('studinfo.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->leftJoin('sh_strand',function($join){
                              $join->on('studinfo.strandid','=','sh_strand.id');
                              $join->where('sh_strand.deleted',0);
                        })  
                        ->leftJoin('sh_block',function($join){
                              $join->on('studinfo.blockid','=','sh_block.id');
                              $join->where('sh_block.deleted',0);
                        }) 
                        ->leftJoin('college_courses',function($join){
                              $join->on('studinfo.courseid','=','college_courses.id');
                              $join->where('college_courses.deleted',0);
                        }) 
                        ->leftJoin('nationality',function($join){
                              $join->on('studinfo.nationality','=','nationality.id');
                              $join->where('nationality.deleted',0);
                        }) 
                        ->leftJoin('religion',function($join){
                              $join->on('studinfo.religionid','=','religion.id');
                              $join->where('religion.deleted',0);
                        }) 
                        ->where('studinfo.id',$studid)
                        ->where('studinfo.deleted',0)
                        ->select(
                              'studinfo.*',
                              'studentstatus.description as studstatus',
                              'gradelevel.levelname',
                              'sh_strand.strandname',
                              'sh_block.blockname',
                              'courseDesc',
                              'nationality.nationality as student_nationality',
                              'religion.religionname as student_religion',
                              'sections.sectionname as sectname',
                              'ismothernum',
                              'isguardannum',
                              'isfathernum',
                              'street',
                              'barangay',
                              'city',
                              'province',
                              'gender',
                              'picurl'
                              )
                        ->first();

      }


      public static function student_enrollment($studid = null){

            $enrollment = array();

            $pgjenrollment = DB::table('enrolledstud')
                              ->join('gradelevel',function($join){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })  
                              ->join('sy',function($join){
                                    $join->on('enrolledstud.syid','=','sy.id');
                              })  
                              ->join('studentstatus',function($join){
                                    $join->on('enrolledstud.studstatus','=','studentstatus.id');
                              })  
                              ->where('enrolledstud.deleted',0)
                              ->where('enrolledstud.studid',$studid)
                              ->select(
                                    'sydesc',
                                    'levelname',
                                    'enrolledstud.syid',
                                    'enrolledstud.sectionid',
                                    'studentstatus.description',
                                    'promotionstatus'
                              )
                              ->get();

            $shenrollment = DB::table('sh_enrolledstud')
                              ->join('gradelevel',function($join){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })  
                              ->join('sy',function($join){
                                    $join->on('sh_enrolledstud.syid','=','sy.id');
                              })  
                              ->join('studentstatus',function($join){
                                    $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                              })  
                              ->join('semester',function($join){
                                    $join->on('sh_enrolledstud.semid','=','semester.id');
                              })
                              ->where('sh_enrolledstud.deleted',0)
                              ->where('sh_enrolledstud.studid',$studid)
                              ->select(
                                    'sydesc',
                                    'levelname',
                                    'sh_enrolledstud.sectionid',
                                    'sh_enrolledstud.syid',
                                    'studentstatus.description',
                                    'promotionstatus'
                              )
                              ->get();


            foreach($pgjenrollment as $item){

                  $sectionname = DB::table('sectiondetail')
                                    ->where('sectionid',$item->sectionid)
                                    ->where('syid',$item->syid)
                                    ->select('sectname')
                                    ->first();

                  $item->sectionname =   $sectionname->sectname;

                  array_push($enrollment, $item);
                  
            }

            foreach($shenrollment as $item){

                  $sectionname = DB::table('sectiondetail')
                                    ->where('sectionid',$item->sectionid)
                                    ->where('syid',$item->syid)
                                    ->select('sectname')
                                    ->first();

                  $item->sectionname =   $sectionname->sectname;

                  array_push($enrollment, $item);
                  
            }

            return $enrollment;

      }


      public static function student_final_grade($studid = null){

            $studendinfo = DB::table('studinfo')
                              ->where('id',$studid)
                              ->select('levelid')
                              ->first();

            if($studendinfo->levelid == 14 && $studendinfo->levelid == 15){

                  $student_grade = DB::table('tempgradesum')
                                          ->where('studid',$studid)
                                          ->join('sh_subjects',function($join){
                                                $join->on('tempgradesum.subjid','=','sh_subjects.id');
                                                $join->where('sh_subjects.deleted',0);
                                          })
                                          ->where('syid',self::activeSy())
                                          ->where('semid',self::activeSem())
                                          ->select(
                                                'subjtitle as subjdesc',
                                                'q1',
                                                'q2',
                                                'q3',
                                                'q4'
                                          )
                                          ->get();


            }else{

                  $student_grade = DB::table('tempgradesum')
                                          ->where('studid',$studid)
                                          ->join('subjects',function($join){
                                                $join->on('tempgradesum.subjid','=','subjects.id');
                                                $join->where('subjects.deleted',0);
                                          })
                                          ->select(
                                                'subjdesc',
                                                'q1',
                                                'q2',
                                                'q3',
                                                'q4'
                                          )
                                          ->where('syid',self::activeSy())
                                          ->get();

            }

            return  $student_grade;
            


      }

      public static function get_evalution_comment($headerid = null){

            $evaluation_comment = DB::table('grading_system_student_evalcom')
                                    ->where('evalcom_studheader',$headerid)
                                    ->where('deleted',0)
                                    ->get();

              
            if(count($evaluation_comment) == 0){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"No comments and suggestions found!"
                  ]);

                  return $data;

            }
            else {
                  
                  $data = array((object)[
                        'status'=>1,
                        'data'=>$evaluation_comment
                  ]);

                  return $data;

            }

      }


      
}
