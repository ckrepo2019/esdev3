<?php

namespace App\Http\Controllers\PrincipalControllers;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class ScheduleTimeTempController extends \App\Http\Controllers\Controller
{

    public static function timetempdetails_list(Request $request){

        $headerid = $request->get('headerid');

        $timetemp_details = DB::table('schedtimetemplate_detail')
                ->where('headerid',$headerid)
                ->where('deleted',0)
                ->select(
                    'id',
                    'stime',
                    'etime'
                )
                ->get();

        foreach($timetemp_details as $timetemp_detail){
            $timetemp_detail->time = \Carbon\Carbon::createFromTimeString($timetemp_detail->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::createFromTimeString($timetemp_detail->etime)->isoFormat('hh:mm A');
            $timetemp_detail->stime_display = \Carbon\Carbon::create($timetemp_detail->stime)->isoFormat('hh:mm A');
            $timetemp_detail->etime_display = \Carbon\Carbon::create($timetemp_detail->etime)->isoFormat('hh:mm A');
        }

        return $timetemp_details;

    }

    public static function timetempdetails_delete(Request $request){
        try{

            $schedtemp_id = $request->get('id');
            $schedtemp_headerid = $request->get('headerid');
        
            DB::table('schedtimetemplate_detail')
                ->take(1)
                ->where('id',$schedtemp_id)
                ->where('deleted',0)
                ->update([
                    'deleted'=>1,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'deletedby'=>auth()->user()->id,
                ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Time Template Deleted!',
                        'data'=>self::timetempdetails_list($request)
                    ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function timetempdetails_create(Request $request){
        try{
            $timetempdetail = $request->get('timetempdetail');
            $headerid = $request->get('headerid');
            $time = explode(" - ", $timetempdetail);
            $stime = \Carbon\Carbon::create($time[0])->isoFormat('HH:mm:ss');
            $etime = \Carbon\Carbon::create($time[1])->isoFormat('HH:mm:ss');

            $temp_details = self::timetempdetails_list($request);

            foreach($temp_details as $temp_detail){
                $sched_stime = $temp_detail->stime;
                $sched_etime = $temp_detail->etime;
                if($stime >= $sched_stime && $stime <= $sched_etime ){
                    if( $stime != $sched_etime){
                        return array((object)[
                            'status'=>0,
                            'icon'=>'error',
                            'message'=>'Time Template Detail Conflict!',
                        ]);
                    }
                }else if( $etime >= $sched_stime && $etime <= $sched_etime ){
                    if( $etime != $sched_stime){
                        return array((object)[
                            'status'=>1,
                            'icon'=>'error',
                            'message'=>'Time Template Detail Conflict!',
                        ]);
                    }
                }else if( $sched_stime >= $stime && $sched_etime <= $etime ){
                    return array((object)[
                        'status'=>0,
                        'icon'=>'error',
                        'message'=>'Time Template Detail Conflict!',
                    ]);
                }
            }


            DB::table('schedtimetemplate_detail')
                    ->insert([
                        'stime'=>$stime,
                        'etime'=>$etime,
                        'headerid'=>$headerid,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'createdby'=>auth()->user()->id,
                        'deleted'=>0
                    ]);
            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Time Template Detail Created!',
                        'data'=>self::timetempdetails_list($request)
                    ]);
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function timetempdetails_update(Request $request){
        try{
            $timetempdetail = $request->get('timetempdetail');
            $headerid = $request->get('headerid');
            $id = $request->get('id');
            $time = explode(" - ", $timetempdetail);
            $stime = \Carbon\Carbon::create($time[0])->isoFormat('HH:mm:ss');
            $etime = \Carbon\Carbon::create($time[1])->isoFormat('HH:mm:ss');

            DB::table('schedtimetemplate_detail')
                    ->where('id',$id)
                    ->take(1)
                    ->update([
                        'stime'=>$stime,
                        'etime'=>$etime,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=>auth()->user()->id
                    ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Time Template Detail Updated!',
                        'data'=>self::timetempdetails_list($request)
                    ]);
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }


    //time template header
    public static function scheduletimetemp_list(){

        $scheduletimetemp_list = DB::table('schedtimetemplate')
                                    ->where('deleted',0)
                                    ->select(
                                        'id',
                                        'description',
                                        'description as text'
                                    )
                                    ->get();

        return $scheduletimetemp_list;

    }

    public static function scheduletimetemp_create(Request $request){

        try{
            $timetemp_desc = $request->get('timetemp_desc');

            $check = DB::table('schedtimetemplate')
                        ->where('description',$timetemp_desc)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('schedtimetemplate')
                    ->insert([
                        'description'=>$timetemp_desc,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'createdby'=>auth()->user()->id,
                        'deleted'=>0
                    ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Time Template Created!',
                        'data'=>self::scheduletimetemp_list()
                    ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function scheduletimetemp_update(Request $request){
        try{

            $timetemp_desc = $request->get('timetemp_desc');
            $schedtemp_id = $request->get('id');

            $check = DB::table('schedtimetemplate')
                        ->where('description',$timetemp_desc)
                        ->where('id','!=',$schedtemp_id)
                        ->where('deleted',0)
                        ->count();

          
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('schedtimetemplate')
                ->take(1)
                ->where('id',$schedtemp_id)
                ->update([
                    'description'=>$timetemp_desc,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'updatedby'=>auth()->user()->id,
                ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Time Template Updated!',
                        'data'=>self::scheduletimetemp_list()
                    ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function scheduletimetemp_delete(Request $request){
        try{

            $schedtemp_id = $request->get('id');
        
            DB::table('schedtimetemplate')
                ->take(1)
                ->where('id',$schedtemp_id)
                ->where('deleted',0)
                ->update([
                    'deleted'=>1,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'deletedby'=>auth()->user()->id,
                ]);

            DB::table('schedtimetemplate_detail')
                ->where('headerid',$schedtemp_id)
                ->where('deleted',0)
                ->update([
                    'deleted'=>1,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'deletedby'=>auth()->user()->id,
                ]);


            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Time Template Deleted!',
                        'data'=>self::scheduletimetemp_list()
                    ]);

        }catch(\Exception $e){
            return self::store_error($e);
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
              'icon'=>'error',
              'message'=>'Something went wrong!'
        ]);
    }
      
}
