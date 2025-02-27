<?php

namespace App\Models\EnrollmentSetup;
use DB;

use Illuminate\Database\Eloquent\Model;

class EnrollmentSetupProcess extends Model
{

     public static function enrollmentsetup_create(
           $syid = null,
           $semid = null,
           $acadprogid = null,
           $enrollmentstart = null,
           $enrollmentend = null,
           $type = null){

            try{

                  $id = DB::table('early_enrollment_setup')
                        ->insertGetId([
                              'syid'=>$syid,
                              'semid'=>$semid,
                              'acadprogid'=>$acadprogid,
                              'enrollmentstart'=>$enrollmentstart,
                              'enrollmentend'=>$enrollmentend,
                              'type'=>$type,
                              'deleted'=>0,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'id'=>$id,
                        'enrollmentstart'=>\Carbon\Carbon::create($enrollmentstart)->isoFormat('MMM DD, YYYY'),
                        'enrollmentend'=>\Carbon\Carbon::create($enrollmentend)->isoFormat('MMM DD, YYYY'),
                        'enrollmentstart_format1'=>\Carbon\Carbon::create($enrollmentstart)->isoFormat('YYYY-MM-DD'),
                        'enrollmentend_format2'=>\Carbon\Carbon::create($enrollmentend)->isoFormat('YYYY-MM-DD'),
                        'status'=>1,
                        'data'=>'Created Successfully!'
                  ]);

            }catch(\Exception $e){
                  return $e;
                  return self::store_error($e);
            }

     }

      public static function enrollmentsetup_update(
            $syid = null,
            $semid = null,
            $acadprogid = null,
            $enrollmentstart = null,
            $enrollmentend = null,
            $type = null,
            $id = null
            ){

            try{

                  DB::table('early_enrollment_setup')
                        ->take(1)
                        ->where('id',$id)
                        ->update([
                              'syid'=>$syid,
                              'semid'=>$semid,
                              'acadprogid'=>$acadprogid,
                              'enrollmentstart'=>$enrollmentstart,
                              'enrollmentend'=>$enrollmentend,
                              'type'=>$type,
                              'deleted'=>0,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'id'=>$id,
                        'status'=>1,
                        'data'=>'Updated Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function enrollmentsetup_delete(
            $id = null
      ){

            try{

                  DB::table('early_enrollment_setup')
                        ->take(1)
                        ->where('id',$id)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'id'=>$id,
                        'status'=>1,
                        'data'=>'Deleted Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }


      public static function enrollmentsetup_update_active(
            $id = null
      ){

            try{
                  $setup =   DB::table('early_enrollment_setup')
                                    ->where('id',$id)
                                    ->first();

                  $all_setup =   DB::table('early_enrollment_setup')
                                    ->where('acadprogid',$setup->acadprogid)
                                    ->where('deleted',0)
                                    ->get();

                  foreach($all_setup as $item){

                        DB::table('early_enrollment_setup')
                                    ->take(1)
                                    ->where('id',$item->id)
                                    ->update([
                                          'isactive'=>0,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                  }

                  $active = 0;
                  if($setup->isactive == 0){
                        $active = 1;
                  }
            
                  DB::table('early_enrollment_setup')
                        ->take(1)
                        ->where('id',$id)
                        ->update([
                              'isactive'=>$active,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'id'=>$id,
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
