<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class Student extends Model
{
    protected $table = "studinfo";

    public static function getallStudentsByDepartement(){

        return DB::table('academicprogram')
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
                        })
                        ->select('studinfo.*')
                        ->get();

    }

    public static function studentQuery(){

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
}
