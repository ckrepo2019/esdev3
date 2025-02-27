<?php

namespace App\Models\Principal;
use DB;
use Session;

use Illuminate\Database\Eloquent\Model;

class LoadData extends Model
{
    public static function loadGrades(){
        
        return DB::table('grades')
                    ->join('sy',function($query){
                        $query->on('grades.syid','=','sy.id');
                        $query->where('sy.isactive','1');
                    })
                    ->join('gradelevel',function($join){
                        $join->on('grades.levelid','=','gradelevel.id');
                    })
                    ->join('subjects',function($join){
                        $join->on('grades.subjid','=','subjects.id');
                        $join->where('subjects.deleted','0');
                        $join->where('subjects.isactive','1');
                    });
    }

   
    public static function loadStudents(){

        $students = DB::table('academicprogram')
                        ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
                        ->join('gradelevel',function($join){
                            $join->on('academicprogram.id','=','gradelevel.acadprogid');
                            $join->where('gradelevel.deleted','0');
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

    public static function parentLoadStudentInfo(){
        
        return DB::table('users')
                ->where('email',"S".(str_replace("P", "", auth()->user()->email)))
                ->join('studinfo',function($join){
                    $join->on('users.id','=','studinfo.userid');
                })
                ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                ->join('sy',function($join){
                    $join->on('enrolledstud.syid','=','sy.id');
                    $join->where('isactive','1');
                })
                ->select(
                    'enrolledstud.sectionid as sectionid',
                    'enrolledstud.id as enrollid',
                    'enrolledstud.studid as studid',
                    'studinfo.userid as userid')
                ->get();    
    }

    public static function studentLoadStudentInfo(){

        return DB::table('studinfo')
                    ->where('studinfo.userid',auth()->user()->id)
                    ->join('enrolledstud',function($join){
                        $join->on('studinfo.id','=','enrolledstud.studid');
                        $join->whereIn('enrolledstud.studstatus',['1','2','4']);
                    })
                    ->join('sy',function($join){
                        $join->on('enrolledstud.syid','=','sy.id');
                        $join->where('isactive','1');
                    })
                    ->select(
                        'enrolledstud.sectionid as sectionid',
                        'enrolledstud.id as enrollid',
                        'enrolledstud.studid as studid',
                        'studinfo.userid as userid')
                    ->take(1)
                    ->get();
    }
    
    public static function loadGradeLevelByDepartment(){

        $principalId = DB::table('teacher')->where('userid',auth()->user()->id)->first();

        
        if(auth()->user()->type == 2){

            return DB::table('academicprogram')
                        ->where('academicprogram.principalid',$principalId->id )
                        ->join('gradelevel',function($join){
                            $join->on('academicprogram.id','=','gradelevel.acadprogid');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->orderby('sortid')
                        ->get();

        }
        else{

            $activeSy = DB::table('sy')->where('isactive',1)->first();

            $teacherAcadProg = DB::table('teacheracadprog')
                                    ->where('teacherid',Session::get('prinInfo')->id)
                                    ->where('syid', $activeSy->id)
                                    ->where('deleted',0)
                                    ->select('acadprogid')
                                    ->get();

            $teacherAcadProgArray = collect($teacherAcadProg)->map(function($TAP){
                return $TAP->acadprogid;
            });

            
            return DB::table('academicprogram')
                        ->whereIn('academicprogram.id',$teacherAcadProgArray  )
                        ->join('gradelevel',function($join){
                            $join->on('academicprogram.id','=','gradelevel.acadprogid');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->orderby('sortid')
                        ->get();

        }

      
    }


    public static function loadSubjectsByDepartment(){

        $principalId = DB::table('teacher')->where('userid',auth()->user()->id)->first();

        return DB::table('academicprogram')
                ->where('academicprogram.principalid',$principalId->id )
                ->join('subjects',function($join){
                    $join->on('academicprogram.id','=','subjects.acadprogid');
                    $join->where('subjects.deleted','0');
                    $join->where('isactive','1');
                })->get();
    }

    

}
