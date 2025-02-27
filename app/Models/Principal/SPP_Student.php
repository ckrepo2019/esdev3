<?php

namespace App\Models\Principal;
use DB;
use Session;
use Carbon\Carbon;
use App\Models\Principal\SPP_Queries;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_Subjects;
use DateTime;

use Illuminate\Database\Eloquent\Model;

class SPP_Student extends Model
{

    public static function loadStudentInfo($id){
        
      return self::studInfoQuery()
                        ->select('studinfo.*', 
                        'gradelevel.acadprogid',
                        'gradelevel.levelname',
                        'sections.sectionname'
                        )
                        ->where('studinfo.id',$id)
                        ->first();

    }


    public static function studInfoQuery(){

        return DB::table('studinfo')
                    ->join('gradelevel',function($join){
                        $join->on('studinfo.levelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted','0');
                    })
                    ->join('sections',function($join){
                        $join->on('studinfo.sectionid','=','sections.id');
                        $join->where('sections.deleted','0');
                    });
                   
        }

    
    public static function getStudentAssignedSubjectSHS($studentid,$sectionid,$blockid){

        $classScheds =   SPP_Queries::getStudentAssignedSubjectsSHSQuery()
                        ->select(
                            'sh_classsched.sectionid',
                            'sh_classsched.id as id',
                            'sh_subjects.subjtitle as subjdesc',
                            'sh_subjects.id as subjid',
                            'sh_subjects.subjcode',
                            'sh_subjects.type'
                            )
                        ->where('sectionid',$sectionid)
                        ->where('sh_classsched.deleted','0')
                        ->get();

        $blockScheds = SPP_Queries::getStudentBlockSubjectsSHSQuery()
                            ->select(
                                'sh_blocksched.blockid',
                                'sh_blocksched.subjid',
                                'sh_subjects.subjtitle as subjdesc',
                                'sh_subjects.subjcode as subjcode',
                                'teacher.firstname',
                                'teacher.lastname',
                                'sh_blocksched.id as id',
                                'sh_subjects.type'
                                )
                            ->where('blockid',$blockid)
                            ->where('sh_blocksched.deleted','0')
                            ->get();

        foreach($blockScheds as $blockSched){

            $classScheds->push($blockSched);

        } 
        
        return $classScheds;
        
    }

    public static function getAllStudentClassScheduleSHS($studentid,$sectionid,$blockid){
        
        $classScheds =  SPP_Queries::getStudentAssignedSubjectsSHSQuery()
                        ->Leftjoin('sh_classscheddetail',function($join){
                            $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                            $join->where('sh_classscheddetail.deleted','0');
                        })
                        ->leftJoin('rooms',function($join){
                            $join->on('sh_classscheddetail.roomid','=','rooms.id');
                            $join->where('rooms.deleted','0');
                        })
                        ->join('days','sh_classscheddetail.day','=','days.id')
                        ->select(
                            'sh_subjects.subjtitle as subjdesc',
                            'days.description',
                            'sh_classsched.subjid',
                            'sh_classscheddetail.day',
                            'sh_classscheddetail.stime',
                            'sh_classscheddetail.etime',
                            'teacher.firstname',
                            'teacher.lastname',
                            'rooms.roomname',
                            'sh_classsched.teacherid',
                            'sh_classscheddetail.roomid',
                            'sh_subjects.type'
                            )
                        
                        ->where('sh_classsched.deleted','0')
                        ->where('sectionid',$sectionid)
                        ->get();

        $blockScheds =  SPP_Queries::getStudentBlockSubjectsSHSQuery()
                            ->Leftjoin('sh_blockscheddetail',function($join){
                                $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                                $join->where('sh_blockscheddetail.deleted','0');
                            })
                       
                            ->leftJoin('days','sh_blockscheddetail.day','=','days.id')
                            ->leftJoin('rooms',function($join){
                                $join->on('sh_blockscheddetail.roomid','=','rooms.id');
                                $join->where('rooms.deleted','0');
                            })
                            ->select(
                                'sh_blocksched.subjid',
                                'sh_subjects.subjtitle as subjdesc',
                                'days.description',
                                'sh_blockscheddetail.day',
                                'sh_blockscheddetail.stime',
                                'sh_blockscheddetail.etime',
                                'teacher.firstname',
                                'teacher.lastname',
                                'sh_blocksched.teacherid',
                                'rooms.roomname',
                                'sh_subjects.type',
                                'sh_blockscheddetail.roomid'
                                )
                            ->where('blockid',$blockid)
                            ->where('sh_blocksched.deleted','0')
                            ->get();

        foreach($blockScheds as  $blockSched){

            $classScheds->push($blockSched);

        }

        return $classScheds;


    }

    public static function getTodayStudentClassScheduleSHS($studentid,$sectionid,$blockid){
        
        $classScheds =  SPP_Queries::getStudentAssignedSubjectsSHSQuery()
                        ->Leftjoin('sh_classscheddetail',function($join){
                            $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                            $join->where('sh_classscheddetail.deleted','0');
                            $join->where('sh_classscheddetail.day',Carbon::now('Asia/Manila')->isoFormat('D'));
                        })
                        ->leftJoin('rooms',function($join){
                            $join->on('sh_classscheddetail.roomid','=','rooms.id');
                            $join->where('rooms.deleted','0');
                        })
                        ->where('sectionid',$sectionid)
                        ->where('sh_classsched.deleted','0')
                        ->get();

        $blockScheds =  SPP_Queries::getStudentBlockSubjectsSHSQuery()
                        ->Leftjoin('sh_blockscheddetail',function($join){
                            $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                            $join->where('sh_blockscheddetail.deleted','0');
                            $join->where('sh_blockscheddetail.day',Carbon::now('Asia/Manila')->isoFormat('D'));
                        })
                        ->leftJoin('rooms',function($join){
                            $join->on('sh_blockscheddetail.roomid','=','rooms.id');
                            $join->where('rooms.deleted','0');
                        })
                        ->where('blockid',$blockid)
                        ->where('sh_blocksched.deleted','0')
                        ->get();

        foreach($blockScheds as  $blockSched){

            $classScheds->push($blockSched);

        }

        return $classScheds;

    }

    public static function getALLStudentClassScheduleJHS($section){

        return SPP_Queries::getStudentAssignedSubjectsJHSQuery()
                    ->leftJoin('classsched',function($join) use($section){
                        $join->on('classsched.subjid','=','assignsubjdetail.subjid');
                        $join->where('classsched.sectionid',$section);
                        $join->where('classsched.deleted','0');
                        $join->whereIn('classsched.syid',function($query){
                                $query->select('id')->from('sy')->where('sy.isactive','1');
                            });
                    })
                    ->leftJoin('classscheddetail',function($join){
                            $join->on('classsched.id','=','classscheddetail.headerid');
                            $join->where('classscheddetail.deleted','0');
                    })
                    ->leftJoin('days','classscheddetail.days','=','days.id')
                    ->leftJoin('rooms','classscheddetail.roomid','=','rooms.id')
                    ->where('assignsubj.sectionid', $section)
                    ->where('classsched.sectionid','!=',null)
                    ->get();

    }

    public static function getTodayStudentClassScheduleJHS($section){

        return SPP_Queries::getStudentAssignedSubjectsJHSQuery()
                    ->leftJoin('classsched',function($join) use($section){
                        $join->on('classsched.subjid','=','assignsubjdetail.subjid');
                        $join->where('classsched.sectionid',$section);
                        $join->where('classsched.deleted','0');
                        $join->whereIn('classsched.syid',function($query){
                                $query->select('id')->from('sy')->where('sy.isactive','1');
                            });
                    })
                    ->leftJoin('classscheddetail',function($join){
                            $join->on('classsched.id','=','classscheddetail.headerid');
                            $join->where('classscheddetail.deleted','0');
                            $join->where('classscheddetail.days',Carbon::now('Asia/Manila')->isoFormat('D'));
                    })
                    ->leftJoin('days','classscheddetail.days','=','days.id')
                    ->leftJoin('rooms','classscheddetail.roomid','=','rooms.id')
                    ->where('assignsubj.sectionid', $section)
                    ->get();

    }

    public static function getStudentAssignedSubjectJNS($section){

        return SPP_Queries::getStudentAssignedSubjectsJHSQuery()
                    ->where('assignsubj.sectionid', $section)
                    ->get();

    }

    public static function getTodaySubjectAttendance(
        $student = null,
        $section = null,
        $schedule = null
    ){

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $currentTime = new DateTime($date);

        foreach($schedule  as $item){

            $time = new DateTime(date($item->stime));
            $etime = new DateTime(date($item->etime));

            if($currentTime->format('H:i') < $time->format('H:i')){
                
                $item->classstatus = $currentTime->diff($time)->format('%hh %im');

            }
            else if( $currentTime->format('H:i') > $time->format('H:i')){

                if($currentTime->format('H:i') < $etime->format('H:i')){

                    $item->classstatus = 1;

                }

                else{

                    $item->classstatus = 0;

                }
            }

        }

        if($student != null){


            foreach($schedule as $item){

                $subjattstatus = DB::table('studentsubjectattendance')
                                    ->where('subject_id',$item->subjid)
                                    ->where('date',$currentTime->format('Y-m-d'))
                                    ->where('student_id',$student)->get();

                if($section != null){

                    $subjattstatus->where('section_id',$section);

                }

                $subjattstatus = $subjattstatus->first();

                if(isset($subjattstatus)){
                   
                    $item->subjectattendance = $subjattstatus->status;

                }
                else {

                    $item->subjectattendance = NULL;
                }

            }
        }

        return $schedule;
       
        
    }

}
