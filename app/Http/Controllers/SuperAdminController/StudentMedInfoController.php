<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class StudentMedInfoController extends \App\Http\Controllers\Controller
{
      
      public static function list(Request $request){
            
            $studid = $request->get('studid');
            $syid = $request->get('syid');
         
            $med_info = DB::table('apmc_midinfo')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->get();

            return $med_info;

      }

      public static function create(Request $request){
            try{

                  $check = DB::table('apmc_midinfo')
                              ->where('studid',$request->get('studid'))
                              ->where('syid',$request->get('syid'))
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>1,
                              'message'=>'Already Exist!'
                        ]);
                  }

                  $user = null;

                  if(isset(auth()->user()->id)){
                        $user = auth()->user()->id;
                  }

                  DB::table('apmc_midinfo')
                        ->insert([
                              'studid'=>$request->get('studid'),
                              'syid'=>$request->get('syid'),
                              'vacc'=>$request->get('vacc'),
                              'vacc_type'=>$request->get('vacc_type'),
                              'vacc_card_id'=>$request->get('vacc_card_id'),
                              'dose_date_1st'=>$request->get('dose_date_1st'),
                              'dose_date_2nd'=>$request->get('dose_date_2nd'),
                              'philhealth'=>$request->get('philhealth'),
                              'bloodtype'=>$request->get('bloodtype'),
                              'allergy'=>$request->get('allergy'),
                              'other_med_info'=>$request->get('other_med_info'),
                              'createdby'=>$user,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Updated!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function update(Request $request){
            try{

                  DB::table('apmc_midinfo')
                        ->where('id',$request->get('id'))
                        ->take(1)
                        ->update([
                              'vacc'=>$request->get('vacc'),
                              'vacc_type'=>$request->get('vacc_type'),
                              'vacc_card_id'=>$request->get('vacc_card_id'),
                              'dose_date_1st'=>$request->get('dose_date_1st'),
                              'dose_date_2nd'=>$request->get('dose_date_2nd'),
                              'philhealth'=>$request->get('philhealth'),
                              'bloodtype'=>$request->get('bloodtype'),
                              'allergy'=>$request->get('allergy'),
                              'other_med_info'=>$request->get('other_med_info'),
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Updated!'
                  ]);

            }catch(\Exception $e){

                  return self::store_error($e);
            }
      }

      public static function delete(Request $request){
            try{

                  return array((object)[
                        'status'=>1,
                        'message'=>'College Deleted!'
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
                  'message'=>'Something went wrong!'
            ]);
      }

}
