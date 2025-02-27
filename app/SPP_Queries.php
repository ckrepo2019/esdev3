<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SPP_Queries extends Model
{
    public static function getStudentBlockSubjectsSHSQuery(){

        return DB::table('sh_blocksched')
                    ->join('sy',function($join){
                        $join->on('sy.id','=','sh_blocksched.syid');
                        $join->where('sy.isactive','1');
                    })
                    ->join('semester',function($join){
                        $join->on('semester.id','=','sh_blocksched.semid');
                        $join->where('semester.isactive','1');
                    })
                    ->join('sh_subjects',function($join){
                        $join->on('sh_blocksched.subjid','=','sh_subjects.id')
                        ->where('sh_subjects.isactive','1')
                        ->where('sh_subjects.deleted','0');
                    })
                    ->join('teacher',function($join){
                        $join->on('sh_blocksched.teacherid','=','teacher.id')
                        ->where('teacher.deleted','0')
                        ->where('teacher.isactive','1');
                    });

    }

    public static function getStudentAssignedSubjectsSHSQuery(){

        return  DB::table('sh_classsched')
                    ->leftJoin('sy',function($join){
                        $join->on('sy.id','=','sh_classsched.syid');
                        $join->where('sy.isactive','1');
                    })
                    ->leftJoin('semester',function($join){
                        $join->on('semester.id','=','sh_classsched.semid');
                        $join->where('sy.isactive','1');
                    })
                    ->leftJoin('sh_subjects',function($join){
                        $join->on('sh_classsched.subjid','=','sh_subjects.id');
                        $join->where('sh_subjects.deleted','0');
                        $join->where('sh_subjects.isactive','1');
                    })
                    ->join('teacher',function($join){
                        $join->on('sh_classsched.teacherid','=','teacher.id')
                        ->where('teacher.deleted','0')
                        ->where('teacher.isactive','1');
                    });

    }

    public static function getStudentAssignedSubjectsJHSQuery(){

        return   self::assignsubjQuery()
                    ->leftJoin('assignsubjdetail',function($join){
                        $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                        $join->where('assignsubjdetail.deleted','0');
                    })
                    ->leftJoin('subjects',function($join){
                        $join->on('assignsubjdetail.subjid','=','subjects.id');
                        $join->where('subjects.deleted','0');
                        $join->where('subjects.isactive','1');
                    })
                    ->join('teacher',function($join){
                        $join->on('assignsubjdetail.teacherid','=','teacher.id')
                        ->where('teacher.deleted','0')
                        ->where('teacher.isactive','1');
                    });

    }

    public static function assignsubjQuery(){

        return   DB::table('assignsubj')
                    ->join('sy',function($join){
                        $join->on('sy.id','=','assignsubj.syid');
                        $join->where('sy.isactive','1');
                    });

    }

    public static function classschedQuery(){

        return DB::table('classsched')
                    ->join('sy',function($join){
                        $join->on('sy.id','=','classsched.syid');
                        $join->where('sy.isactive','1');
                    });

    }
    
}
