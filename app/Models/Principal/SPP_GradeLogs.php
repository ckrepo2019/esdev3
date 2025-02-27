<?php

namespace App\Models\Principal;

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

    public static function getGradeLogs(
        $skip = null,
        $take = null,
        $gradeLogId = null,
        $gradeInfo = null,
        $user_id = null
    ){

        $query = DB::table('gradelogs')
                    ->where('user_id',$user_id)
                    ->join('grades',function($join){
                        $join->on('gradelogs.gradeid','=','grades.id');
                        $join->where('grades.deleted','0');
                    })
                    ->join('sections',function($join){
                        $join->on('grades.sectionid','=','sections.id');
                        $join->where('sections.deleted','0');
                    })
                    ->join('gradelevel',function($join){
                        $join->on('sections.levelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted','0');
                    })
                    ->leftJoin('subjects',function($join){
                        $join->on('grades.subjid','=','subjects.id');
                        $join->where('gradelevel.acadprogid','!=','5');
                    })
                    ->leftJoin('sh_subjects',function($join){
                        $join->on('grades.subjid','=','sh_subjects.id');
                        $join->where('gradelevel.acadprogid','==','5');
                    })
                    ->join('teacher',function($join){
                        $join->on('gradelogs.user_id','=','teacher.userid');
                        $join->where('teacher.deleted','0');
                   });

       $query->addSelect(
                    'gradelogs.*',
                    'teacher.firstname',
                    'teacher.lastname',
                    'sections.sectionname',
                    'gradelevel.levelname',
                    'gradelevel.acadprogid',
                    'grades.quarter',
                    'grades.status',
                    'subjects.subjdesc',
                    'subjects.subjcode',
                    'sh_subjects.subjtitle',
                    'grades.subjid'
                );

        $count = $query->count();

        $data = array();

        if($take!=null){

            $query->take($take);

        }

        if($skip!=null){

            $query->skip(($skip-1)*$take);
        }


        $gradelogs = $query->get();
    
        
        array_push($data,(object)['data'=>$gradelogs,'count'=>$count]);

        return $data;                                            
            
            
    }

}
