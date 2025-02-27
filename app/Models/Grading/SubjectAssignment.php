<?php

namespace App\Models\Grading;
use DB;

use Illuminate\Database\Eloquent\Model;

class SubjectAssignment extends Model
{

      public static function subjectAssignment(
            $subjectid = null,
            $acadprogid = null
      ){

            return DB::table('grading_system_subjassignment')
                        ->join('grading_system',function($join) use($acadprogid){
                              $join->on('grading_system_subjassignment.gsid','=','grading_system.id');
                              $join->where('grading_system.deleted',0);
                              $join->where('acadprogid',$acadprogid);
                        })
                        ->join('grading_system_detail',function($join){
                              $join->on('grading_system.id','=','grading_system_detail.headerid');
                              $join->where('grading_system_detail.deleted',0);
                        })
                        ->where('subjid',$subjectid)
                        ->where('syid',self::activeSy()->id)
                        ->where('grading_system_subjassignment.deleted',0)
                        ->select(
                              'grading_system_detail.description',
                              'grading_system_detail.value',
                              'grading_system.description as gsdesc'
                        )
                        ->orderBy('grading_system.description')
                        ->get();

      }


      public static function activeSy(){

            return DB::table('sy')->where('isactive',1)->select('id','sydesc')->first();
            
      }


      
}
