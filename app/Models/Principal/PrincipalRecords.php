<?php

namespace App\Models\Principal;
use DB;
use \Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class PrincipalRecords extends Model
{
    public static function get_enrolled_students($syid = null, $semid = null, $sectionid = null, $levelid = null){
        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
        }
        if($levelid == 14 || $levelid == 15){
            if($semid == null){
                $semid = DB::table('semester')->where('isactive',1)->first()->id;
            }
            $students = DB::table('sh_enrolledstud')
                            ->where('sh_enrolledstud.syid',$syid)
                            ->where('sh_enrolledstud.semid',$semid)
                            ->where('sh_enrolledstud.deleted',0);
            if($sectionid != null){
                $students =  $students->where('sh_enrolledstud.sectionid',$sectionid);
            }
            $students = $students
                            ->join('studinfo',function($join){
                                $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                $join->where('sh_enrolledstud.deleted',0);
                            })
                            ->join('gradelevel',function($join){
                                $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->join('studentstatus',function($join){
                                $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->join('sh_strand',function($join){
                                $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                $join->where('sh_strand.deleted',0);
                            })
                            ->select(
                                'lastname',
                                'firstname',
                                'levelname',
                                'strandcode',
                                'promotionstatus',
                                'description',
                                'sid',
                                'sh_enrolledstud.studstatus',
                                'studinfo.id'
                            )->get();
            return $students;
        }else{

            $students = DB::table('enrolledstud')
                            ->where('enrolledstud.syid',$syid)
                            ->where('enrolledstud.deleted',0);

            if($sectionid != null){
                $students =  $students->where('enrolledstud.sectionid',$sectionid);
            }

            $students = $students
                            ->join('studinfo',function($join){
                                $join->on('enrolledstud.studid','=','studinfo.id');
                                $join->where('enrolledstud.deleted',0);
                            })
                            ->join('gradelevel',function($join){
                                $join->on('enrolledstud.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->join('studentstatus',function($join){
                                $join->on('enrolledstud.studstatus','=','studentstatus.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->select(
                                'lastname',
                                'firstname',
                                'levelname',
                                'promotionstatus',
                                'enrolledstud.studstatus',
                                'description',
                                'sid',
                                'studinfo.id'
                            )->get();

            return $students;
        }

    }

    public static function get_schedule($syid = null, $semid = null, $sectionid = null, $levelid = null, $subjid = null){
        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
        }
        if($levelid == 14 || $levelid == 15){

            if($semid == null){
                $semid = DB::table('semester')->where('isactive',1)->first()->id;
            }
            $classshed = DB::table('sh_classsched')
                            ->where('sh_classsched.syid',$syid)
                            ->where('sh_classsched.semid',$semid)
                            ->where('sh_classsched.sectionid',$sectionid)
                            ->where('sh_classsched.deleted',0)
                            ->join('sh_classscheddetail',function($join){
                                $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                                $join->where('sh_classscheddetail.deleted',0);
                            })
                            ->join('teacher',function($join){
                                $join->on('sh_classsched.teacherid','=','teacher.id');
                                $join->where('teacher.deleted',0);
                            })
                            ->select('stime','etime','roomid','teacherid','firstname','lastname','subjid','sectionid')
                            ->get();
            return $classshed;
        }else{
        


        }

    }

    public static function get_section_subjects($syid = null, $semid = null, $sectionid = null, $levelid = null){



        if($levelid == 14 || $levelid == 15){

            $strands = DB::table('sh_sectionblockassignment')
                        ->where('sh_sectionblockassignment.deleted',0)
                        ->where('sectionid',$sectionid)
                        ->join('sh_block',function($join){
                            $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                            $join->where('sh_block.deleted',0);
                        })
                        ->join('sh_strand',function($join){
                            $join->on('sh_block.strandid','=','sh_strand.id');
                            $join->where('sh_strand.deleted',0);
                        })
                        ->select('sh_block.strandid','strandcode')
                        ->get();

            $subjects = DB::table('sh_subjects')
                        ->where('sh_subjects.levelid',$levelid)
                        ->where('sh_subjects.deleted',0)
                        ->select('id','subjtitle as subjdesc','subjcode','semid')
                        ->get();

            $temp_subjects = array();

            foreach($subjects as $key=>$item){

                $subject_strands = DB::table('sh_subjstrand')
                                    ->where('sh_subjstrand.subjid',$item->id)
                                    ->where('sh_subjstrand.deleted',0)
                                    ->select('strandid')
                                    ->get();
                $subjmatched = false;
                if(count($subject_strands) > 0){
                    foreach($subject_strands as $subject_strand){
                        foreach($strands as $strand){
                            if($strand->strandid == $subject_strand->strandid){
                                $item->strand = $strand->strandcode;
                                array_push($temp_subjects,$item);
                            }
                        }
                    }
                }else{
                    $item->strand = "";
                    array_push($temp_subjects,$item);
                }
               

            }

            return $temp_subjects;




        }else{
        


        }




    }

}
