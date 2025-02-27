<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class VaccineSetupController extends \App\Http\Controllers\Controller
{
    public static function vaccine_list(){

        $schedule_classifiation = DB::table('vaccine_type')
                                    ->where('deleted',0)
                                    ->select(
                                        'id',
                                        'vaccinename',
                                        'vaccinename as text'
                                    )
                                    ->orderBy('vaccinename')
                                    ->get();

        return $schedule_classifiation;

    }

    public static function vaccine_create(Request $request){

        try{

            $vaccine_name = $request->get('vaccine_name');

            $check = DB::table('vaccine_type')
                        ->where('vaccinename',$vaccine_name)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('vaccine_type')
                    ->insert([
                        'vaccinename'=>$vaccine_name,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'createdby'=>auth()->user()->id,
                        'deleted'=>0
                    ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Vaccine Type Created!',
                        'data'=>self::vaccine_list()
                    ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function vaccine_update(Request $request){
        try{

            $vaccine_name = $request->get('vaccine_name');
            $vaccine_id = $request->get('id');

            $check = DB::table('vaccine_type')
                        ->where('vaccinename',$vaccine_name)
                        ->where('id','!=',$vaccine_id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            $check = DB::table('apmc_midinfo')
                        ->where(function($query) use($vaccine_id){
                            $query->where('vacc_type_id',$vaccine_id);
                            $query->orWhere('vacc_type_2nd_id',$vaccine_id);
                            $query->orWhere('booster_type_id',$vaccine_id);
                        })
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }

            DB::table('vaccine_type')
                    ->take(1)
                    ->where('id',$vaccine_id)
                    ->update([
                        'vaccinename'=>$vaccine_name,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=>auth()->user()->id,
                        'deleted'=>0
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Vaccine Type Updated!',
                'data'=>self::vaccine_list()
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function vaccine_delete(Request $request){
        try{

            $vaccine_id = $request->get('id');

            $check = DB::table('apmc_midinfo')
                        ->where(function($query) use($vaccine_id){
                            $query->where('vacc_type_id',$vaccine_id);
                            $query->orWhere('vacc_type_2nd_id',$vaccine_id);
                            $query->orWhere('booster_type_id',$vaccine_id);
                        })
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }

            DB::table('vaccine_type')
                    ->take(1)
                    ->where('id',$vaccine_id)
                    ->update([
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'deletedby'=>auth()->user()->id,
                        'deleted'=>1
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Vaccine Type Deleted!',
                'data'=>self::vaccine_list()
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
