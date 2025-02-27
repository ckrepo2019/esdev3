<?php

namespace App;
use DB;
use Session;
use Carbon\Carbon;
use App\SPP_Queries;
use App\SPP_EnrolledStudent;

use Illuminate\Database\Eloquent\Model;

class SPP_Student extends Model
{
    public static function principalstudentQuery(){

        $students = DB::table('academicprogram')
                        ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
                        ->join('gradelevel',function($join){
                            $join->on('academicprogram.id','=','gradelevel.acadprogid');
                            $join->where('deleted','0');
                        })
                        ->join('enrolledstud',function($join){
                            $join->on('gradelevel.id','=','enrolledstud.levelid');
                            $join->whereIn('enrolledstud.studstatus',['1','2','4']);
                        })
                        ->join('sy',function($join){
                            $join->on('enrolledstud.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('sections',function($join){
                            $join->on('enrolledstud.sectionid','=','sections.id');
                            $join->where('sections.deleted','0');
                            })
                        ->join('studinfo',function($join){
                            $join->on('enrolledstud.studid','=','studinfo.id');
                        });
                        
        return $students;

    }

    public static function loadStudent(){

        return self::principalstudentQuery()->get();
        
    }

    public static function loadStudentsByGradeLevel($gradelevel){

        return self::principalstudentQuery()->where('studinfo.levelid',$gradelevel)->get();

    }

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

    public static function loadLoggedInStudentInfo(){

        return  self::studInfoQuery()
                    ->select(
                        'studinfo.*',
                        'gradelevel.acadprogid',
                        'gradelevel.levelname',
                        'sections.sectionname'
                        )
                    ->where('userid',auth()->user()->id)
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

    public static function getAllEnrolledStudentsByGradeLevel($levelid){

        return SPP_EnrolledStudent::getallenrolledstudbyGradeLevel($levelid);
        
    }

    

}
