<?php

namespace App\Models\Subjects;
use DB;

use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{

      //grade_school_subjects           - get all grade school subjects                    
      //grade_school_grading_assignment - get all grade shool subject grading assignment



      public static function grade_school_subjects(){

            return DB::table('subjects')
                        ->where('acadprogid',3)
                        ->select('subjdesc','id','subjcode')
                        ->get();
                        

      }


      public static function grade_school_subject_assignment($gsid = null){

            $activeSy = DB::table('sy')->where('isactive',1)->first();

            $subjects = DB::table('subjects')
                              ->where('subjects.deleted',0)
                              ->where('subjects.acadprogid',3)
                              ->select('subjdesc','subjects.id as subjid','subjcode')
                              ->get();

            foreach($subjects as $item){

                  $gs = DB::table('grading_system_subjassignment')
                              ->join('grading_system',function($join){
                                    $join->on('grading_system_subjassignment.gsid','=','grading_system.id');
                                    $join->where('grading_system.deleted',0);
                                    $join->where('grading_system.acadprogid',3);
                              })
                              ->where('grading_system_subjassignment.deleted',0)
                              ->where('grading_system_subjassignment.syid',$activeSy->id)
                              ->select(
                                    'grading_system.description',
                                    'gsid',
                                    'grading_system_subjassignment.createddatetime',
                                    'grading_system_subjassignment.id as gssid',
                              )
                              ->where('gsid',$gsid)
                              ->where('subjid',$item->subjid)
                              ->first();

                  if(isset($gs->gsid)){

                        $item->description = $gs->description;
                        $item->gsid = $gs->gsid;
                        $item->gssid = $gs->gssid;
                        $item->createddatetime = $gs->createddatetime;

                  }else{

                        $item->description = null;
                        $item->gsid = null;
                        $item->gssid = null;
                        $item->createddatetime = null;

                  }

            }

            return $subjects;
                        
      }

      public static function high_school_subject_assignment($gsid = null){

            $activeSy = DB::table('sy')->where('isactive',1)->first();

            $subjects = DB::table('subjects')
                              ->where('subjects.deleted',0)
                              ->where('subjects.acadprogid',4)
                              ->select('subjdesc','subjects.id as subjid','subjcode')
                              ->get();

            foreach($subjects as $item){

                  $gs = DB::table('grading_system_subjassignment')
                              ->join('grading_system',function($join){
                                    $join->on('grading_system_subjassignment.gsid','=','grading_system.id');
                                    $join->where('grading_system.deleted',0);
                                    $join->where('grading_system.acadprogid',4);
                              })
                              ->where('grading_system_subjassignment.deleted',0)
                              ->where('grading_system_subjassignment.syid',$activeSy->id)
                              ->select(
                                    'grading_system.description',
                                    'gsid',
                                    'grading_system_subjassignment.createddatetime',
                                    'grading_system_subjassignment.id as gssid',
                              )
                              ->where('subjid',$item->subjid)
                              ->where('gsid',$gsid)
                              ->first();

                  if(isset($gs->gsid)){

                        $item->description = $gs->description;
                        $item->gsid = $gs->gsid;
                        $item->gssid = $gs->gssid;
                        $item->createddatetime = $gs->createddatetime;

                  }else{

                        $item->description = null;
                        $item->gsid = null;
                        $item->gssid = null;
                        $item->createddatetime = null;

                  }

            }

            return $subjects;
        
      }

      public static function senior_high_subject_assignment($gsid = null){

            $activeSy = DB::table('sy')->where('isactive',1)->first();

            $sh_subjects = DB::table('sh_subjects')
                              ->where('sh_subjects.deleted',0)
                              ->select('subjtitle as subjdesc','sh_subjects.id as subjid','subjcode','type')
                              ->get();

            foreach($sh_subjects as $item){

                  $gs = DB::table('grading_system_subjassignment')
                              ->join('grading_system',function($join){
                                    $join->on('grading_system_subjassignment.gsid','=','grading_system.id');
                                    $join->where('grading_system.deleted',0);
                                    $join->where('grading_system.acadprogid',5);
                              })
                              ->where('grading_system_subjassignment.deleted',0)
                              ->where('grading_system_subjassignment.syid',$activeSy->id)
                              ->select(
                                    'grading_system.description',
                                    'gsid',
                                    'grading_system_subjassignment.id as gssid',
                                    'grading_system_subjassignment.createddatetime'
                              )
                              ->where('subjid',$item->subjid)
                              ->where('gsid',$gsid)
                              ->first();

                  if(isset($gs->gsid)){

                        $item->description = $gs->description;
                        $item->gsid = $gs->gsid;
                        $item->gssid = $gs->gssid;
                        $item->createddatetime = $gs->createddatetime;

                  }else{

                        $item->description = null;
                        $item->gsid = null;
                        $item->gssid = null;
                        $item->createddatetime = null;

                  }

            }

            return $sh_subjects;

      }

     
      public static function  get_sh_subject($subjectid = null){

            return DB::table('sh_subjects')->where('id',$subjectid)->where('deleted',0)->get();


      }

    


      
}
