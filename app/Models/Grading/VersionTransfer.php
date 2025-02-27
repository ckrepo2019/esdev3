<?php

namespace App\Models\Grading;
use DB;
use Illuminate\Database\Eloquent\Model;

class VersionTransfer extends Model
{

      public static function acadprog(){

          
      }

      public static function v1gradesgs(){

            $grades = DB::table('grades')
                        ->where('grades.deleted',0)
                        ->join('gradelevel',function($join){
                              $join->on('grades.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('acadprogid',3);
                        })
                        ->join('sections',function($join){
                              $join->on('grades.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->join('subjects',function($join){
                              $join->on('grades.subjid','=','subjects.id');
                              $join->where('subjects.deleted',0);
                        })
                        
                        ->select(
                              'grades.levelid',
                              'grades.sectionid',
                              'grades.subjid',
                              'grades.quarter',
                              'grades.syid',
                              'sectionname',
                              'grades.transfered',
                              'grades.datetransfered',
                              'subjdesc',
                              'gradelevel.levelname',
                              'grades.id',
                              'grades.createdby'
                        )
                        ->get();

            return $grades;

      }

      public static function v1gradesjs(){

            $grades = DB::table('grades')
                        ->where('grades.deleted',0)
                        ->join('gradelevel',function($join){
                              $join->on('grades.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('acadprogid',4);
                        })
                        ->join('sections',function($join){
                              $join->on('grades.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->join('subjects',function($join){
                              $join->on('grades.subjid','=','subjects.id');
                              $join->where('subjects.deleted',0);
                        })
                        ->select(
                              'grades.levelid',
                              'grades.sectionid',
                              'grades.subjid',
                              'grades.quarter',
                              'grades.transfered',
                              'grades.datetransfered',
                              'gradelevel.levelname',
                              'grades.syid',
                              'sectionname',
                              'subjdesc',
                              'grades.id',
                              'grades.createdby'
                        )
                        ->get();

            return $grades;

      }

      public static function v1gradessh(){

            $grades = DB::table('grades')
                        ->where('grades.deleted',0)
                        // ->where('transfered',0)
                        ->join('gradelevel',function($join){
                              $join->on('grades.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('acadprogid',5);
                        })
                        ->join('sections',function($join){
                              $join->on('grades.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->join('sh_subjects',function($join){
                              $join->on('grades.subjid','=','sh_subjects.id');
                              $join->where('sh_subjects.deleted',0);
                        })
                        ->select(
                              'grades.levelid',
                              'grades.sectionid',
                              'grades.subjid',
                              'grades.quarter',
                              'grades.transfered',
                              'grades.datetransfered',
                              'grades.id',
                              'grades.syid',
                              'sectionname',
                              'gradelevel.levelname',
                              'subjtitle as subjdesc',
                              'grades.createdby'
                        )
                        ->get();

            return $grades;

      }
     

}
