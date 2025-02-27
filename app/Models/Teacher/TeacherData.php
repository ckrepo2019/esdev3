<?php

namespace App\Models\Teacher;
use DB;

use Illuminate\Database\Eloquent\Model;

class TeacherData extends Model
{

      public static function get_all_sections($teacherid = null, $syid = null, $semid = nul){

            $sh_sections = self::get_sections_sh($teacherid, $syid, $semid);
            $hs_sections = self::section_count_hs($teacherid, $syid);
            $gs_sections = self::section_count_gs($teacherid, $syid);
            $ps_sections = self::section_count_ps($teacherid, $syid);

            $all_subjects = array();

            foreach($sh_sections as $item){
                  array_push($all_subjects, $item);
            }

            foreach($hs_sections as $item){
                  array_push($all_subjects, $item);
            }

            foreach($gs_sections as $item){
                  array_push($all_subjects, $item);
            }

            foreach($ps_sections as $item){
                  array_push($all_subjects, $item);
            }

            return $all_subjects;
            
      }


      public static function get_sections_sh($teacherid = null, $syid = null, $semid = null){

            $teacherid = $teacherid;

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }

            // if($semid == null){
            //       $semid = DB::table('semester')->where('isactive',1)->first()->id;
            // }

            $subjects = DB::table('sh_classsched')
                              ->where('sh_classsched.teacherid',$teacherid)
                              ->where('sh_classsched.deleted',0)
                              ->join('sh_subjects',function($join){
                                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                                    $join->where('sh_subjects.deleted',0);
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_classsched.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('sh_classsched.syid',$syid)
                              // ->where('sh_classsched.semid',$semid)
                              ->select(
                                    'sectionname',
                                    'subjcode',
                                    'sectionid as id',
                                    'subjid',
                                    'sections.levelid as levelid',
                                    'levelname',
                                    'sections.id as sectionid',
                                    'subjtitle as subjdesc',
                                    'gradelevel.acadprogid',
                                    'sh_classsched.semid'
                              )
                              ->get();

            $blocksched = DB::table('sh_blocksched')
                              ->where('sh_blocksched.teacherid',$teacherid)
                              ->where('sh_blocksched.deleted',0)
                              ->join('sh_subjects',function($join){
                                    $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                                    $join->where('sh_subjects.deleted',0);
                              })
                              ->join('sh_sectionblockassignment',function($join){
                                    $join->on('sh_blocksched.blockid','=','sh_sectionblockassignment.blockid');
                                    $join->where('sh_sectionblockassignment.deleted',0);
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_sectionblockassignment.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('sh_blocksched.syid',$syid)
                              // ->where('sh_blocksched.semid',$semid)
                              ->select(
                                    'sectionname',
                                    'subjcode',
                                    'sectionid as id',
                                    'subjid','sections.levelid as levelid',
                                    'levelname',
                                    'sections.id as sectionid',
                                    'subjtitle as subjdesc',
                                    'gradelevel.acadprogid',
                                    'sh_blocksched.semid'
                              )
                              ->get();

            $allsubjects = array();

            foreach($blocksched as $item){
                  
                  array_push($allsubjects,$item);
                  
            }  
            
             foreach($subjects as $item){
                  
                  array_push($allsubjects,$item);
                  
            }  

            return $allsubjects;

      }


      public static function section_count_hs($teacherid = null, $syid = null){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }

           
            $subjects = DB::table('assignsubj')
                        ->where('syid', $syid)
                        ->where('assignsubj.deleted',0)
                        ->join('assignsubjdetail',function($join) use($teacherid){
                              $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                              $join->where('assignsubjdetail.deleted',0);
                              $join->where('assignsubjdetail.teacherid',$teacherid);
                        })
                        ->join('sections',function($join){
                              $join->on('assignsubj.sectionid','=','sections.id');
                              $join->where('assignsubjdetail.deleted',0);
                        })
                        ->join('gradelevel',function($join){
                              $join->on('assignsubj.glevelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('gradelevel.acadprogid',4);
                        })
                        ->join('subjects',function($join){
                              $join->on('assignsubjdetail.subjid','=','subjects.id');
                              $join->where('subjects.deleted',0);
                        })
                        ->select(
                              'sectionname',
                              'subjcode',
                              'sectionid as id',
                              'subjid','sections.levelid as levelid',
                              'levelname',
                              'sections.id as sectionid',
                              'subjdesc',
                              'gradelevel.acadprogid'
                        )
                        ->get();

            return $subjects;

      }

      public static function section_count_gs($teacherid = null, $syid = null){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }

           
            $subjects = DB::table('assignsubj')
                        ->where('syid', $syid)
                        ->where('assignsubj.deleted',0)
                        ->join('assignsubjdetail',function($join) use($teacherid){
                              $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                              $join->where('assignsubjdetail.deleted',0);
                              $join->where('assignsubjdetail.teacherid',$teacherid);
                        })
                        ->join('sections',function($join){
                              $join->on('assignsubj.sectionid','=','sections.id');
                              $join->where('assignsubjdetail.deleted',0);
                        })
                        ->join('gradelevel',function($join){
                              $join->on('assignsubj.glevelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('gradelevel.acadprogid',3);
                        })
                        ->join('subjects',function($join){
                              $join->on('assignsubjdetail.subjid','=','subjects.id');
                              $join->where('subjects.deleted',0);
                        })
                        ->select(
                              'sectionname',
                              'subjcode',
                              'sectionid as id',
                              'subjid','sections.levelid as levelid',
                              'levelname',
                              'sections.id as sectionid',
                              'subjdesc',
                              'gradelevel.acadprogid'
                        )
                        ->get();

            return $subjects;

      }

      public static function section_count_ps($teacherid = null, $syid = null){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }

           
            $subjects = DB::table('assignsubj')
                        ->where('syid', $syid)
                        ->where('assignsubj.deleted',0)
                        ->join('assignsubjdetail',function($join) use($teacherid){
                              $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                              $join->where('assignsubjdetail.deleted',0);
                              $join->where('assignsubjdetail.teacherid',$teacherid);
                        })
                        ->join('sections',function($join){
                              $join->on('assignsubj.sectionid','=','sections.id');
                              $join->where('assignsubjdetail.deleted',0);
                        })
                        ->join('gradelevel',function($join){
                              $join->on('assignsubj.glevelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('gradelevel.acadprogid',2);
                        })
                        ->join('subjects',function($join){
                              $join->on('assignsubjdetail.subjid','=','subjects.id');
                              $join->where('subjects.deleted',0);
                        })
                        ->select(
                              'sectionname',
                              'subjcode',
                              'sectionid as id',
                              'subjid','sections.levelid as levelid',
                              'levelname',
                              'sections.id as sectionid',
                              'subjdesc',
                              'gradelevel.acadprogid'
                        )
                        ->get();

            return $subjects;

      }

}
