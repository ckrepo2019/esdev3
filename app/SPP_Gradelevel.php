<?php

namespace App;
use DB;
use Session;

use Illuminate\Database\Eloquent\Model;

class SPP_Gradelevel extends Model
{
    public static function loadAllGradeLevel(){

        return  DB::table('academicprogram')
                        ->where('academicprogram.id',Session::get('principalInfo')[0]->prinid)
                        ->join('gradelevel',function($join){
                            $join->on('academicprogram.id','=','gradelevel.acadprogid');
                            $join->where('deleted','0');
                        })
                        ->select('gradelevel.*')
                        ->orderBy('gradelevel.sortid')
                        ->get();
    }
    public static function getgradelevelwithoutgradesetup($levelid){
        
        return  DB::table('academicprogram')
                    ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
                    ->leftJoin('subjects',function($join){
                        $join->on('academicprogram.id','=','subjects.acadprogid');
                        $join->where('subjects.deleted','0');
                    })
                    ->leftJoin('gradessetup',function($join) use($levelid){
                        $join->on('subjects.id','=','gradessetup.subjid');
                        $join->where('gradessetup.levelid',$levelid);
                        $join->where('gradessetup.deleted','0');
                    })
                    ->select('subjects.*')
                    ->where('gradessetup.subjid',NULL)
                    ->get();
    }

    public static function laodSHGradeLevel(){

        return DB::table('gradelevel')->where('acadprogid','5')->get();
        
    }


    public static function showgradelevelwithoutgradesetup($levelid){

        $subjects = self::getgradelevelwithoutgradesetup($levelid);

        $dataString = '';

        $dataString .='<option value="" selected>Select Subject</option>';

        foreach($subjects as $item){

            $dataString .='<option value="'.$item->id.'">'.$item->subjdesc.'</option>';

        }

        return $dataString;
    }

    public static function getPrincipalGradeLevel(){

        return DB::table('academicprogram')
                ->where('principalid',Session::get('prinInfo')->id)
                ->join('gradelevel',function($join){
                    $join->on('academicprogram.id','=','gradelevel.acadprogid');
                    $join->where('deleted','0');
                })
                ->select('gradelevel.*')
                ->orderBy('gradelevel.sortid')
                ->get();


    }

    public static function getGradeLevelAcadProg($gradelevel){

        return DB::table('gradelevel')->where('id',$gradelevel)->first();

    }


   
}
