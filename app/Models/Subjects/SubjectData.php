<?php

namespace App\Models\Subjects;
use DB;

use Illuminate\Database\Eloquent\Model;

class SubjectData extends Model
{
      public static function ps_get_subjects($subjid = null){

            $subjects = DB::table('subjects')
                              ->where('deleted',0)
                              ->where('acadprogid',2);

            if($subjid != null){
                  $subjects = $subjects->where('id',$subjid);
            }

            $subjects = $subjects->select(
                                    'id',
                                    'subjdesc',
                                    'subjcode',
                                    'inSF9',
                                    'subj_sortid',
                                    'subj_per',
                                    'inTLE',
                                    'inMAPEH',
                                    'isSP',
                                    // 'isTLECon',
                                    // 'isMAPEHCon',
                                    'isCon',
                                    'isVisible'
                              )
                              ->orderBy('inSF9')
                              ->get();

            return $subjects;

      }

      public static function gs_get_subjects($subjid = null){

            $subjects = DB::table('subjects')
                                    ->where('deleted',0)
                                    ->where('acadprogid',3);

            if($subjid != null){
                  $subjects = $subjects->where('id',$subjid);
            }

            $subjects = $subjects->select(
                                          'id',
                                          'subjdesc',
                                          'subjcode',
                                          'inSF9',
                                          'subj_sortid',
                                          'subj_per',
                                          'inTLE',
                                          'inMAPEH',
                                          'isSP',
                                          // 'isTLECon',
                                          // 'isMAPEHCon',
                                          'isCon',
                                          'isVisible'
                                    )
                                    ->orderBy('inSF9')
                                    ->get();

                        return $subjects;
      }

      public static function jh_get_subjects($subjid = null){

            $subjects = DB::table('subjects')
                        ->where('deleted',0)
                        ->where('acadprogid',4);


            if($subjid != null){
                  $subjects = $subjects->where('id',$subjid);
            }

            $subjects = $subjects->select(
                                          'id',
                                          'subjdesc',
                                          'subjcode',
                                          'inSF9',
                                          'subj_sortid',
                                          'subj_per',
                                          'inTLE',
                                          'inMAPEH',
                                          'isSP',
                                          // 'isTLECon',
                                          // 'isMAPEHCon',
                                          'isCon',
                                          'isVisible'
                                    )
                                    ->orderBy('inSF9')
                                    ->get();

            return $subjects;
      }

      public static function sh_get_subjects($subjid = null){

            $subjects = DB::table('sh_subjects')
                        ->where('deleted',0);

            if($subjid != null){
                  $subjects = $subjects->where('id',$subjid);
            }
            
            $subjects = $subjects->select(
                              'id',
                              'subjtitle as subjdesc',
                              'subjcode',
                              'inMAPEH',
                              'inSF9',
                              'sh_subj_sortid as subj_sortid',
                              'type',
                              'semid'
                        )
                        ->get();

            foreach($subjects as $item){
                  $item->inTLE = "";
            }

            return $subjects;

      }

      public static function subject_strand($subjid = null){

            $subject_strand = DB::table('sh_subjstrand')
                  ->where('sh_subjstrand.deleted',0)
                  ->where('subjid',$subjid)
                  ->join('sh_strand',function($join){
                        $join->on('sh_subjstrand.strandid','=','sh_strand.id');
                        $join->where('sh_strand.deleted',0);
                  })
                  ->select(
                        'sh_subjstrand.id',
                        'strandname',
                        'strandcode',
                        'strandid'
                  )
                  ->get();

            return $subject_strand;


      }

      public static function subject_component_list($subjid = null){

            $subjcom = DB::table('subjects')
                  ->where('subjects.deleted',0)
                  ->where('subjCom',$subjid)
                  ->where('inSF9',1)
                  ->select(
                        'id',
                        'subjdesc',
                        'subjdesc as text'
                  )
                  ->get();

            return $subjcom;

      }

      public static function subject_component_list_na($acadprogid){

            $subjects_na = DB::table('subjects')
                  ->where('subjects.deleted',0)
                  ->where('subjCom',null)
                  ->where('inSF9',1)
                  ->where('acadprogid',$acadprogid)
                  ->select(
                        'id',
                        'subjdesc',
                        'subjdesc as text'
                  )
                  ->get();

            return $subjects_na;

      }

      
}
