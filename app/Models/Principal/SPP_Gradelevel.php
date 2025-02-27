<?php

namespace App\Models\Principal;
use DB;
use Session;
use Crypt;

use Illuminate\Database\Eloquent\Model;

class SPP_Gradelevel extends Model
{
    public static function loadAllGradeLevel(){

        return  DB::table('academicprogram')
                        ->where('academicprogram.id',Session::get('principalInfo')[0]->prinId)
                        ->join('gradelevel',function($join){
                            $join->on('academicprogram.id','=','gradelevel.acadprogid');
                            $join->where('gradelevel.deleted','0');
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
                    $join->where('gradelevel.deleted','0');
                })
                ->select('gradelevel.*')
                ->orderBy('gradelevel.sortid')
                ->get();


    }

    public static function getGradeLevelAcadProg($gradelevel){

        return DB::table('gradelevel')->where('id',$gradelevel)->first();

    }

    public static function getGradeLevel(
        $skip = null,
        $take = null,
        $levelid = null,
        $levelinfo = null,
        $acadprogid = null
    ){

        $query = DB::table('gradelevel');

        if($levelinfo != null){

            $query->where(function($query) use($levelinfo){

                $query->where('gradelevel.levelname','like',$levelinfo.'%');
                
            });

        }
       

        if($levelid != null){

            $query->where('id',$levelid);

        }

        if($acadprogid != null){

            $query->where('gradelevel.acadprogid',Crypt::decrypt($acadprogid));

        }
        
        $query->where('gradelevel.deleted','0');
        $query->orderBy('sortId');

        $count = $query->count();

        if($take!=null){

            $query->take($take);

        }

        if($skip!=null){

            $query->skip(($skip-1)*$take);
        }

        $gradelevel = $query->get();

        $data = array();

        array_push($data,(object)['data'=>$gradelevel,'count'=>$count]);

        return $data;    

    }


   
}
