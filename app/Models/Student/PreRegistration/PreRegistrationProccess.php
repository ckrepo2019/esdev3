<?php

namespace App\Models\Student\PreRegistration;
use DB;

use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\Model;

class PreRegistrationProccess extends Model
{

      public static function submit_preregistration($studid = null, $syid = null, $semid = null, $levelid = null, $admission_type = null){
            
            try{

                  $check = DB::table('student_pregistration')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->where('deleted',0)
                              ->select('id')
                              ->first();

                  if(isset($check->id)){
                        
                        DB::table('student_pregistration')
                                    ->where('id',$check->id)
                                    ->take(1)
                                    ->update([
                                          'admission_type'=>$admission_type,
                                          'gradelvl_to_enroll'=>$levelid,
                                          'status'=>'SUBMITTED',
                                          'updatedby'=> isset(auth()->user()->id) ? auth()->user()->id : null,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                  }else{

                        $userid = 0;

                        if(isset(auth()->user()->id)){
                              $userid = auth()->user()->id;
                        }
                       
                        DB::table('student_pregistration')
                              ->insert([
									'transtype'=>'ONLINE',
                                    'admission_type'=>$admission_type,
                                    'studid'=>$studid,
                                    'gradelvl_to_enroll'=>$levelid,
                                    'syid'=>$syid,
                                    'semid'=>$semid,
                                    'createdby'=>$userid,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                 

                  return array((object)[
                        'status'=>1,
                        'data'=>'Submitted Successfully'
                  ]);
                         

            }catch(\Exception $e){
                  return self::insert_errorlogs($e);
            }

      }

      public static function insert_errorlogs($e){
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
