<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SPP_GradeLogs extends Model
{
    public static function SeniorHighGradeLogs($sectionid, $subjectid){
        
        return DB::table('gradeslogs')
                    ->join('grades',function($join) use($sectionid,$subjectid){
                        $join->on('gradeslogs.gradeid','=','grades.id');
                        $join->where('grades.subjid',$subjectid);
                        $join->where('grades.sectionid',$sectionid);
                    })
                    ->leftJoin('sy',function($join){
                        $join->on('sy.id','=','grades.syid');
                        $join->where('sy.isactive','1');
                    })
                    ->get();

    }

    public static function JuniorHighGradeLogs($subjectid,$sectionid){

        return DB::table('gradelogs')
                    ->join('grades',function($join) use($sectionid,$subjectid){
                        $join->on('gradelogs.gradeid','=','grades.id');
                        $join->where('grades.subjid',$subjectid);
                        $join->where('grades.sectionid',$sectionid);
                    })
                    ->leftJoin('sy',function($join){
                        $join->on('sy.id','=','grades.syid');
                        $join->where('sy.isactive','1');
                    })
                    ->join('teacher','teacher.id','=','gradelogs.user_id')
                    ->join('gradelevel','gradelevel.id','=','grades.levelid')
                    ->join('sections','sections.id','=','grades.sectionid')
                    ->join('subjects','subjects.id','=','grades.subjid')
                    ->select(
                        'gradelevel.levelname',
                        'sections.sectionname',
                        'gradelogs.action',
                        'subjects.subjcode',
                        'grades.quarter',
                        'teacher.firstname as name',
                        'gradelogs.id',
                        'gradelogs.date')
                    ->get();

    }

}
