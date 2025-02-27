<?php

namespace App\Models\Teacher;
use DB;

use Illuminate\Database\Eloquent\Model;

class TeacherSubjectAssignment extends Model
{

      // public static function activeSy(){

      //       return DB::Table('sy')->where('isactive',1)->select('id')->first()->id;
      
      // }

      // public static function activeSem(){

      //       return DB::Table('semester')->where('isactive',1)->select('id')->first()->id;
      
      // }
   
      // public static function pre_school($teacherid){



      // }


      // public static function grade_school($teacherid = null){

      //       return DB::table('assignsubj')
      //                   ->join('gradelevel',function($join){
      //                         $join->on('assignsubj.levleid','=','gradelevel.levelid');
      //                         $join->where('gradlevel.deleted',0);
      //                         $join->where('gradlevel.acadprogid',3);
      //                   })    
      //                   ->join('assignsubjdetail',function($join) use($teacherid){
      //                         $join->on('assignsubj.id','=','assignsubjdetail.headerid');
      //                         $join->where('assignsubjdetail.deleted',0);
      //                         $join->where('assignsubjdetail.teacherid',$teacherid);
      //                   })
      //                   ->where('syid',self::activeSy)
      //                   ->get();
            

      // }

      // public static function high_school($teacherid){

      //       return DB::table('assignsubj')
      //                         ->join('gradelevel',function($join){
      //                               $join->on('assignsubj.levleid','=','gradelevel.levelid');
      //                               $join->where('gradlevel.deleted',0);
      //                               $join->where('gradlevel.acadprogid',4);
      //                         })    
      //                         ->join('assignsubjdetail',function($join) use($teacherid){
      //                               $join->on('assignsubj.id','=','assignsubjdetail.headerid');
      //                               $join->where('assignsubjdetail.deleted',0);
      //                               $join->where('assignsubjdetail.teacherid',$teacherid);
      //                         })
      //                         ->join('subjects',function($join){
      //                               $join->on('assignsubj.subjects')
      //                         })
      //                         ->where('syid',self::activeSy)
      //                         ->get();

      // }

      // public static function block_school($teacherid){

            

      // }

}
