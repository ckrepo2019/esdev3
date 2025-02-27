<?php

namespace App\Models\Student\PreRegistration;
use DB;

use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\Model;

class OnlinePaymentProccess extends Model
{

      public static function submit_preregistration($studid = null, $syid = null, $semid = null){
            
            try{
                  DB::table('student_pregistration')
                        ->insert([
                              'studid'=>$studid,
                              'syid'=>$syid,
                              'semid'=>$semid,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Submitted Successfully'
                  ]);
                         

            }catch(\Exception $e){
                  return self::insert_errorlogs($e);
            }

      }

      public static function insert_errorlogs($e){
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
