<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Principal\SPP_Gradelevel;
use Session;

class SPP_AcademicProg extends Model
{
    public static function getAllAcadProg(){

        return DB::table('academicprogram')->get();

    }

    public static function getPrincipalAcadProg($teacherid){

        return DB::table('academicprogram')->where('principalid',$teacherid)->get();

    }


    public static function getAllEnrolledStudentBaseOnAcademicProgram(){

        

        if($syid != null){

            $students->join('sy',function($join) use ($syid){
                $join->on('sy.id','=','enrolledstud.syid');
                $join->where('sy.id',$syid);
            });

           

        }
        else{

            $students->join('sy',function($join){
                $join->on('sy.id','=','enrolledstud.syid');
                $join->where('sy.isactive','1');
            });

        }
     

        return DB::table('academicprogram')
                        ->join('gradelevel',function($join){
                            $join->on('gradelevel.acadprogid','=','academicprogram.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->join('enrolledstud',function($join){
                            $join->on('gradelevel.id','=','enrolledstud.levelid');
                            $join->where('enrolledstud.deleted','0');
                            $join->whereIn('studstatus',['1','2','4']);
                            $join->select('dateenrolled');
                            $join->distinct();
                        })
                        ->leftJoin('sectiondetail',function($join){
                            $join->on('sectiondetail.sectionid','=','enrolledstud.sectionid');

                            if(Session::has('schoolYear')){

                                $join->whereIn('sectiondetail.syid',function($query){
                                    $query->select('id')
                                    ->from('sy')
                                    ->where('sy.id',Session::get('schoolYear')->id);
                                });

                            }

                            else{

                                $join->whereIn('sectiondetail.syid',function($query){
                                    $query->select('id')->from('sy')->where('sy.isactive','1');
                                });


                            }


                          

                           
                            $join->where('sectiondetail.deleted','0');
                        })
                        ->leftJoin('sections',function($join){
                            $join->on('sections.id','=','sectiondetail.sectionid');
                            $join->where('sections.deleted','0');
                        })
                        ->leftJoin('teacher',function($join){
                            $join->on('sectiondetail.teacherid','=','teacher.id');
                            $join->where('teacher.deleted','0');
                        })
                        ->join('studinfo',function($join){
                            $join->on('enrolledstud.studid','=','studinfo.id');
                        });
                

    }

    public static function searchAllEnrolledStudentBaseOnAcademicProgram($acadid,$studentinfo){

        return DB::table('academicprogram')
                ->join('gradelevel',function($join){
                    $join->on('gradelevel.acadprogid','=','academicprogram.id');
                    $join->where('gradelevel.deleted','0');
                })
                ->join('enrolledstud',function($join){
                    $join->on('gradelevel.id','=','enrolledstud.levelid');
                    $join->whereIn('enrolledstud.syid',function($query){
                        $query->select('id')->from('sy')->where('sy.isactive','1');
                    });
                    $join->where('enrolledstud.deleted','0');
                    $join->whereIn('studstatus',['1','2','4']);
                })
                ->join('studinfo',function($join){
                    $join->on('enrolledstud.studid','=','studinfo.id');
                })
                ->where('academicprogram.id',$acadid)
                ->where(function($query) use($studentinfo){
                    $query->where('studinfo.firstname','like',$studentinfo.'%');
                    $query ->orWhere('studinfo.lastname','like',$studentinfo.'%');
                    $query ->orWhere('studinfo.middlename','like',$studentinfo.'%');
                    $query ->orWhere('studinfo.sid','like',$studentinfo.'%');
                })
                ->select(
                    'studinfo.*',
                    'gradelevel.levelname')
                ->get();

    }

    public static function getAllGradeLevelByAcadprog($acadid){
        
        return DB::table('academicprogram')
                ->join('gradelevel',function($join){
                    $join->on('gradelevel.acadprogid','=','academicprogram.id');
                    $join->where('gradelevel.deleted','0');
                })
                ->orderBy('sortid')
                ->where('academicprogram.id',$acadid)
                ->get();

    }

    // public static function getAllEnrolledStudentsByGradeLevel($levelid){

    //     return DB::table('academicprogram')
    //             ->join('gradelevel',function($join) use($levelid){
    //                 $join->on('gradelevel.acadprogid','=','academicprogram.id');
    //                 $join->where('gradelevel.deleted','0');
    //                 $join->where('gradelevel.id',$levelid);
    //             })
    //             ->join('enrolledstud',function($join){
    //                 $join->on('gradelevel.id','=','enrolledstud.levelid');
    //                 $join->whereIn('enrolledstud.syid',function($query){
    //                     $query->select('id')->from('sy')->where('sy.isactive','1');
    //                 });
    //                 $join->where('enrolledstud.deleted','0');
    //                 $join->whereIn('studstatus',['1','2','4']);
    //             })
    //             ->join('studinfo',function($join){
    //                 $join->on('enrolledstud.studid','=','studinfo.id');
    //             })
    //             ->select('studinfo.*')
    //             ->get();

    // }



}
