<?php

namespace App\Models\AttendanceSetup;
use DB;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetupProccess extends Model
{


      public static function attendance_setup_create(
            $month = null, 
            $days = null,
            $syid  = null,
            $sort = null,
            $year = null
      ){

            try{

                  $attendance_setup_id = DB::table('studattendance_setup')
                        ->insertGetId([
                              'syid'=>$syid,
                              'month'=>$month,
                              'days'=>$days,
                              'sort'=>$sort,
                              'year'=>$year,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        'id'=> $attendance_setup_id
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function attendance_setup_update(
            $attsetupid = null,
            $month = null, 
            $days = null,
            $syid  = null,
            $sort = null,
            $year = null
      ){

            try{

                  DB::table('studattendance_setup')
                        ->take(1)
                        ->where('id',$attsetupid)
                        ->where('deleted',0)
                        ->update([
                              'syid'=>$syid,
                              'month'=>$month,
                              'days'=>$days,
                              'sort'=>$sort,
                              'sort'=>$sort,
                              'year'=>$year,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  
                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      
      public static function attendance_setup_delete(
            $attsetupid = null
      ){

            try{

                  DB::table('studattendance_setup')
                        ->take(1)
                        ->where('id',$attsetupid)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  
                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!'
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
                  'data'=>'Something went wrong!'
            ]);

      }




      


      
}
