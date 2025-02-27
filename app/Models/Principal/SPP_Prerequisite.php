<?php

namespace App\Models\Principal;

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
                    ->where('sh_prerequisite.deleted','0')
                    ->select()
                    ->get();
    }
}
