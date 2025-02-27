<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SPP_Prerequisite extends Model
{
    public static function loadSHSubjectPrerequisiteBySubject($subjid){

        return DB::table('sh_prerequisite')
                    ->leftJoin('sh_subjects',function($join){
                        $join->on('sh_prerequisite.prereqsubjid','=','sh_subjects.id');
                    })
                    ->where('sh_prerequisite.subjid',$subjid)
                    ->select()
                    ->get();
    }
}
