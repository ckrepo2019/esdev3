<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SPP_Section extends Model
{
    public static function getSeniorHighSectionInfo($id){

        return DB::table('sections')
                    ->leftJoin('teacher',function($join){
                        $join->on('sections.teacherid','=','teacher.id');
                        $join->where('teacher.deleted','0');
                        $join->where('teacher.isactive','1');
                    })
                    ->leftJoin('rooms',function($join){
                        $join->on('sections.roomid','=','rooms.id');
                        $join->where('rooms.deleted','0');
                    })
                    ->leftJoin('gradelevel',function($join){
                        $join->on('sections.levelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted','0');
                    })
                    ->leftJoin('sh_block',function($join){
                        $join->on('sections.blockid','=','sh_block.id');
                        $join->where('sh_block.deleted','0');
                    })
                    ->leftJoin('users as cb','sh_block.createdby','=','cb.id')
                    ->leftJoin('users as ub','sh_block.updatedby','=','ub.id')
                    ->select(
                        'sections.*',
                        'firstname as fn',
                        'lastname as ln',
                        'roomname as rn',
                        'levelname as lvn', 
                        'sectionname as sn',
                        'gradelevel.acadprogid',
                        'sh_block.blockname',
                        'cb.name as cbname', 
                        'ub.name as ubname'
                    )
                    ->where('sections.id',$id)
                    ->where('sections.deleted','0')
                    ->first();
    }

    public static function addBlockToSHSection($sectionid,$blockid){

        toast('Section block successfully update','success')->autoClose(3000)->toToast($position = 'top-right');

        DB::table('sections')->where('id',$sectionid)->update(['blockid'=>$blockid]);

    }

}
