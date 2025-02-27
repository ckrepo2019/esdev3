<?php

namespace App\Models\Grading;
use DB;
use App\Models\Grading\GradingSystem;

use Illuminate\Database\Eloquent\Model;

class PendingGrades extends Model
{

      public static function active_sy(){
            return DB::table('sy')->where('isactive',1)->first()->id;
      }

      public static function active_sem(){
            return DB::table('semester')->where('isactive',1)->first()->id;
      }
      public static function teacher_id(){
            return DB::table('teacher')->where('userid',auth()->user()->id)->where('deleted',0)->first()->id;
      }

      public static function  pending_grade_count(){
            
            $pending_count = DB::table('grading_system_pending_grade')
                        ->where('teacherid',self::teacher_id())
                        ->where('isactive',1)
                        ->where('deleted',0)
                        ->count();

            return $pending_count;

      }

      public static function get_teacher_grade($teacherid = null){

            if($teacherid == null){

                  $teacherid = self::teacher_id();

            }

            $pending_grades = array();
            
            $gs_js_pending_grades = DB::table('grading_system_pending_grade')
                        ->where('teacherid',$teacherid)
                        ->join('studinfo',function($join){
                              $join->on('grading_system_pending_grade.studid','=','studinfo.id');
                        })
                        ->join('gradelevel',function($join){
                              $join->on('grading_system_pending_grade.levelid','=','gradelevel.id');
                        })
                        ->join('sy',function($join){
                              $join->on('grading_system_pending_grade.syid','=','sy.id');
                        })
                        ->join('semester',function($join){
                              $join->on('grading_system_pending_grade.semid','=','semester.id');
                        })
                        ->join('subjects',function($join){
                              $join->on('grading_system_pending_grade.subjid','=','subjects.id');
                        })
                        ->whereNotIn('grading_system_pending_grade.levelid',[14,15])
                        ->where('grading_system_pending_grade.isactive',1)
                        ->where('grading_system_pending_grade.deleted',0)
                        ->select(
                              'grading_system_pending_grade.*',
                              'levelname',
                              'sectionname',
                              'lastname',
                              'firstname',
                              'subjdesc',
                              'sid',
                              'studid',
                              'quarter'
                              )
                        ->get();

            $sh_pending_grades = DB::table('grading_system_pending_grade')
                        ->where('teacherid',$teacherid)
                        ->join('studinfo',function($join){
                              $join->on('grading_system_pending_grade.studid','=','studinfo.id');
                        })
                        ->join('gradelevel',function($join){
                              $join->on('grading_system_pending_grade.levelid','=','gradelevel.id');
                        })
                        ->join('sy',function($join){
                              $join->on('grading_system_pending_grade.syid','=','sy.id');
                        })
                        ->join('semester',function($join){
                              $join->on('grading_system_pending_grade.semid','=','semester.id');
                        })
                        ->join('sh_subjects',function($join){
                              $join->on('grading_system_pending_grade.subjid','=','sh_subjects.id');
                        })
                        ->whereIn('grading_system_pending_grade.levelid',[14,15])
                        ->where('grading_system_pending_grade.isactive',1)
                        ->where('grading_system_pending_grade.deleted',0)
                        ->select(
                              'grading_system_pending_grade.*',
                              'levelname',
                              'sectionname',
                              'lastname',
                              'firstname',
                              'subjtitle as subjdesc',
                              'sid',
                              'studid',
                              'quarter'
                              )
                        ->get();


            foreach($gs_js_pending_grades as $item){

                  array_push($pending_grades, $item);

            }

            foreach($sh_pending_grades as $item){

                  array_push($pending_grades, $item);

            }

            return $pending_grades;

      }
    

   


}
