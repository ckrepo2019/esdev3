<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class LoadSections extends Model
{
    public static function sections(){
  
        $sections = DB::table('academicprogram')
            ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
            ->join('gradelevel',function($join){
                $join->on('academicprogram.id','=','gradelevel.acadprogid');
                $join->where('deleted','0');
            })
            ->join('sections','gradelevel.id','=','sections.levelid')
            ->select('sections.id','sections.sectionname')
            ->orderBy('gradelevel.sortid')
            ->get();

        return $sections;
    }
    public static function gradelevel(){
        $gradelevels = DB::table('academicprogram')
            ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
            ->join('gradelevel',function($join){
                $join->on('academicprogram.id','=','gradelevel.acadprogid');
                $join->where('deleted','0');
            })
            ->select('gradelevel.id','gradelevel.levelname')
            ->orderBy('gradelevel.sortid')
            ->get();

        return $gradelevels;

    }
}
