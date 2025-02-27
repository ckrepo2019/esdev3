<?php

namespace App\Http\Controllers\AdministratorControllers;

use Illuminate\Http\Request;
use DB;
use Session;

class RoomsController extends \App\Http\Controllers\Controller
{

    public static function getsubjects(Request $request){

        try{
            
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $sections = $request->get('sections');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            if($levelid == 14 || $levelid == 15){

                $sectionblockass = DB::table('sh_sectionblockassignment')
                                              ->join('sh_block',function($join){
                                                  $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                                  $join->where('sh_block.deleted',0);
                                              })
                                              ->where('sh_sectionblockassignment.syid',$syid)
                                              ->where('sh_sectionblockassignment.deleted',0)
                                              ->where('sh_sectionblockassignment.sectionid',$sectionid)
                                              ->select('strandid')
                                              ->get();
      
                $subjects = DB::table('subject_plot')
                                ->where('subject_plot.deleted',0)
                                ->where('subject_plot.syid',$syid)
                                ->where('subject_plot.levelid',$levelid)
                                ->where('subject_plot.strandid',collect($sectionblockass)->pluck('strandid'))
                                ->where('subject_plot.semid',$semid)
                                ->join('sh_subjects',function($join){
                                    $join->on('subject_plot.subjid','=','sh_subjects.id');
                                    $join->where('sh_subjects.deleted',0);
                                })
                                ->distinct('subjid')
                                ->select(
                                    'subjtitle as text',
                                    'subjid as id'
                                )
                                ->get();
                foreach($subjects as $item){
                    $item->subjCom = null;
                }

            }else{

                $subjects = DB::table('subject_plot')
                                ->where('subject_plot.deleted',0)
                                ->where('subject_plot.syid',$syid)
                                ->where('subject_plot.levelid',$levelid)
                                ->join('subjects',function($join){
                                    $join->on('subject_plot.subjid','=','subjects.id');
                                    $join->where('subjects.deleted',0);
                                    $join->where('subjects.isCon',0);
                                })
                                ->select(
                                    'subjdesc as text',
                                    'subjid as id',
                                    'subjCom'
                                )
                                ->get();
            }

          

            return array((object)[
                'status'=>1,
                'data'=>$subjects
            ]);
        
        }catch(\Exception $e){

            return self::store_error($e);


        }

    }


    public static function getsections(Request $request){

        try{
            
            $sections = DB::table('sectiondetail')
                            ->where('sectiondetail.deleted',0)
                            ->where('syid',$request->get('syid'))
                            ->join('sections',function($join){
                                $join->on('sectiondetail.sectionid','=','sections.id');
                                $join->where('sections.deleted',0);
                            })
                            ->join('gradelevel',function($join){
                                $join->on('sections.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->select(
                                'sectiondetail.sd_roomid as roomid',
                                'sectiondetail.teacherid',
                                'sectionname as text',
                                'sectionid as id',
                                'levelid',
                                'acadprogid'
                            )
                            ->get();

            return array((object)[
                'status'=>1,
                'data'=>$sections
            ]);
        
        }catch(\Exception $e){

            return self::store_error($e);


        }

    }

    public static function rooms(Request $request){

        $search = $request->get('search');
        $search = $search['value'];

        $rooms = DB::table('rooms')
                    ->leftJoin('building',function($join){
                        $join->on('rooms.buildingid','=','building.id');
                        $join->where('building.deleted',0);
                    })  
                    ->where(function($query) use($search){
                        if($search != null){
                            $query->orWhere('roomname','like','%'.$search.'%');
                            $query->orWhere('description','like','%'.$search.'%');
                        }
                    })
                    ->take($request->get('length'))
                    ->skip($request->get('start'))
                    ->where('rooms.deleted',0)
                    ->select(
                        'rooms.id',
                        'rooms.roomname',
                        'rooms.capacity',
                        'buildingid',
                        'description'
                    )
                    ->get();


        $room_count = DB::table('rooms')
                    ->leftJoin('building',function($join){
                        $join->on('rooms.buildingid','=','building.id');
                        $join->where('building.deleted',0);
                    })  
                    ->where(function($query) use($search){
                        if($search != null){
                            $query->orWhere('roomname','like','%'.$search.'%');
                            $query->orWhere('description','like','%'.$search.'%');
                        }
                    })
                    ->where('rooms.deleted',0)
                    ->select(
                        'rooms.id',
                        'rooms.roomname',
                        'rooms.capacity',
                        'buildingid',
                        'description'
                    )
                    ->count();

                
        // return collect($rooms)->sortBy('roomname')->values();
        return @json_encode((object)[
            'data'=>$rooms,
            'recordsTotal'=>$room_count,
            'recordsFiltered'=>$room_count
        ]);

        return $rooms;

    }

    public static function create_room(Request $request){

        $roomname = $request->get('roomname');
        $capacity = $request->get('capacity');
        $building = $request->get('building');

        try{

            $check = DB::table('rooms')
                        ->where('roomname',$roomname)
                        ->where('deleted',0)
                        ->count();

            if($check == 0){
                DB::table('rooms')
                    ->insert([
                        'deleted'=>0,
                        'roomname'=>$roomname,
                        'buildingid'=>$building,
                        'capacity'=>$capacity,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                return  array((object)[
                    'status'=>1,
                    'message'=>'Room Created'
                ]);
            }else{
                return  array((object)[
                    'status'=>0,
                    'message'=>'Already Exist'
                ]);
            }

        }catch(\Exception $e){
            return $e;
            return  array((object)[
                'status'=>0,
                'message'=>'Something went wrong!'
            ]);

        }

    }

    public static function udpate_room(Request $request){

        $roomname = $request->get('roomname');
        $capacity = $request->get('capacity');
        $building = $request->get('building');
        $id = $request->get('id');

        try{

            $check = DB::table('rooms')
                        ->where('roomname',$roomname)
                        ->where('id','!=',$id)
                        ->where('deleted',0)
                        ->count();

            if($check == 0){

                DB::table('rooms')
                    ->where('id',$id)
                    ->take(1)
                    ->update([
                        'roomname'=>$roomname,
                        'buildingid'=>$building,
                        'capacity'=>$capacity,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                return  array((object)[
                    'status'=>1,
                    'message'=>'Room Updated'
                ]);
            }else{
                return  array((object)[
                    'status'=>0,
                    'message'=>'Already Exist'
                ]);
            }

        }catch(\Exception $e){
            return  array((object)[
                'status'=>0,
                'message'=>'Something went wrong!'
            ]);

        }

    }

    public static function delete_room(Request $request){

        $id = $request->get('id');

        try{

            $check_usage = DB::table('classscheddetail')
                            ->where('roomid',$id)
                            ->where('deleted',0)
                            ->count();

            if($check_usage > 0){
                return  array((object)[
                    'status'=>2,
                    'message'=>'Room is already used!'
                ]);
            }

            $check_usage = DB::table('sh_classscheddetail')
                        ->where('roomid',$id)
                        ->where('deleted',0)
                        ->count();

            if($check_usage > 0){
                return  array((object)[
                    'status'=>2,
                    'message'=>'Room is already used!'
                ]);
            }

            $check_usage = DB::table('scheddetail')
                        ->where('roomid',$id)
                        ->where('deleted',0)
                        ->count();

            if($check_usage > 0){
                return  array((object)[
                    'status'=>2,
                    'message'=>'Room is already used!'
                ]);
            }

            DB::table('rooms')
                ->where('id',$id)
                ->update([
                    'deleted'=>1,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
            
            return  array((object)[
                'status'=>1,
                'message'=>'Room Deleted!'
            ]);
    
        }catch(\Exception $e){
            return $e;
            return  array((object)[
                'status'=>0,
                'message'=>'Something went wrong!'
            ]);

        }

    }

    public static function store_error($e){
        DB::table('zerrorlogs')
        ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
        return array((object)[
              'status'=>0,
              'message'=>'Something went wrong!'
        ]);
    }
   


}
