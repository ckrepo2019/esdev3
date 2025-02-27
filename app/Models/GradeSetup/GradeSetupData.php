<?php

namespace App\Models\GradeSetup;
use DB;

use Illuminate\Database\Eloquent\Model;

class GradeSetupData extends Model
{


     public static function get_grade_setup($syid = null, $subjid = null, $acadprogid = null, $levelid = null){

               $grade_setup = DB::table('gradelevel');

               if($acadprogid == null){
                    $grade_setup = $grade_setup->whereIn('acadprogid',[2,3,4]);
               }else{
                    $grade_setup = $grade_setup->where('acadprogid',5);
               }

               $grade_setup = $grade_setup->where('gradelevel.deleted',0)
                              ->leftJoin('gradessetup',function($join) use($syid,$levelid,$subjid){
                                    $join->on('gradelevel.id','=','gradessetup.levelid');
                                    $join->where('gradessetup.deleted',0);
                                    $join->where('gradessetup.syid',$syid);
                                    if($levelid != null){
                                         $join->where('levelid',$levelid);
                                    }
                                    if($subjid != null){
                                         $join->where('subjid',$subjid);
                                    }
                              })
                              ->select(
                                        'sortid',
                                        'gradessetup.id',
                                        'levelname',
                                        'writtenworks',
                                        'performancetask',
                                        'qassesment',
                                        'first',
                                        'second',
                                        'third',
                                        'fourth',
                                        'gradelevel.id as levelid',
                                        'subjid'
                              )
                              ->orderBy('sortid')
                              ->get();

            return $grade_setup;
             
     }

      
}
