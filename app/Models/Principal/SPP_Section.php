<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;

class SPP_Section extends Model
{

    protected $table = 'sections';

    public function rooms()
    {
        return $this->belongsTo('App\Models\Principal\SPP_Rooms', 'roomid')
                    ->where('rooms.deleted',0);
    }


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
                        'teacher.id as tid',
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

        $sy = DB::table('sy')->where('isactive','1')->first();

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        if(isset($blockid)){

            foreach($blockid as $item){
                
                DB::table('sh_sectionblockassignment')
                    ->updateOrInsert (
                        [
                            'sectionid'=>$sectionid,
                            'blockid'=>$item, 
                            'syid'=> $sy->id
                        ],
                        [
                            'deleted'=>'0',
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>$date
                        ]
                        );
            }

            // DB::table('sh_sectionblockassignment')
            //     ->where('sectionid',$sectionid)
            //     ->whereNotIn('blockid',$blockid)
            //     ->update([
            //         'deleted'=>1
            //     ]);

        }
        else{
            
            DB::table('sh_sectionblockassignment')
                ->where('sectionid',$sectionid)
                ->join('sy',function($join){
                    $join->on('sh_sectionblockassignment.syid','=','sy.id');
                    $join->where('sh_sectionblockassignment.deleted','0');
                })
                ->update([
                    'deleted'=>1
                ]);

        }
        

    }

    public static function sectionUsage($id){
        
        $sectionUsage = false;

        $studentusage = DB::table('enrolledstud')->where('deleted','0')->where('sectionid',$id)->count();

        if($studentusage > 0){

            $sectionUsage = true;

        }

        $studentusage = DB::table('sh_enrolledstud')->where('deleted','0')->where('sectionid',$id)->count();

        if($studentusage > 0){

            $sectionUsage = true;

        }

        $schedUsage = DB::table('assignsubj')->where('deleted','0')->where('sectionid',$id)->count();
        
        if($schedUsage > 0){

            $sectionUsage = true;

        }

        $schedUsage = DB::table('classsched')->where('deleted','0')->where('sectionid',$id)->count();

        if($schedUsage > 0){

            $sectionUsage = true;

        }

    }

}
