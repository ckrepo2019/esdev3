<?php

namespace App\Models\Notification;
use DB;

use Illuminate\Database\Eloquent\Model;

class NotificationProccess extends Model
{


      public static function notification_create(
            $message = null, 
            $link = null,
            $reciever  = null,
            $createdby  = null
      ){

            try{

                  DB::table('znotification')
                        ->insert([
                              'message'=>$message,
                              'link'=>$link,
                              'reciever'=>$reciever,
                              'createdby'=>$createdby,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
                 
                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      

      
      public static function store_error($e){
            return $e;
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
