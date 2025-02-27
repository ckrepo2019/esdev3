<?php

namespace App\Models\SuperAdmin\Setup\PreregRequirements;

use Illuminate\Database\Eloquent\Model;
use DB;

class PreregRequirementsData extends Model
{
    
    public static function preregrequirements_list($id = null , $isactive = null){

        $preregrequirements_list = DB::table('preregistrationreqlist')
                                    ->leftJoin('academicprogram',function($join){
                                        $join->on('preregistrationreqlist.acadprogid','=','academicprogram.id');
                                    });

        if($id != null){
            $preregrequirements_list = $preregrequirements_list->where('preregistrationreqlist.id',$id);
        }

        if($isactive != null){
            $preregrequirements_list = $preregrequirements_list->where('preregistrationreqlist.isActive',$isActive);
        }

        $preregrequirements_list = $preregrequirements_list->where('preregistrationreqlist.deleted',0)
                                    ->select('preregistrationreqlist.*','acadprogcode','progname')
                                    ->orderBy('preregistrationreqlist.id')
                                    ->get();

        return $preregrequirements_list;

    }

}
