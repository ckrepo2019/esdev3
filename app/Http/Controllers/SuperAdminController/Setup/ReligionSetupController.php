<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class ReligionSetupController extends \App\Http\Controllers\Controller
{
    public static function religion_list(){

        $religion = DB::table('religion')
                                    ->where('deleted',0)
                                    ->select(
                                        'id',
                                        'religionname',
                                        'religionname as text'
                                    )
                                    ->orderBy('religionname')
                                    ->get();

        return $religion;

    }

    public static function religion_create(Request $request){

        try{

            $religion_name = $request->get('religion_name');

            $check = DB::table('religion')
                        ->where('religionname',$religion_name)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('religion')
                    ->insert([
                        'religionname'=>$religion_name,
                        'deleted'=>0
                    ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Religion Created!',
                        'data'=>self::religion_list()
                    ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function religion_update(Request $request){
        try{

            $religion_name = $request->get('religion_name');
            $religion_id = $request->get('id');

            $check = DB::table('religion')
                        ->where('religionname',$religion_name)
                        ->where('id','!=',$religion_id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            $check = DB::table('studinfo')
                        ->where('religionid',$religion_id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }

            DB::table('religion')
                    ->take(1)
                    ->where('id',$religion_id)
                    ->update([
                        'religionname'=>$religion_name,
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Religion Updated!',
                'data'=>self::religion_list()
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function religion_delete(Request $request){
        try{

            $religion_id = $request->get('id');

            $check = DB::table('studinfo')
                        ->where('religionid',$religion_id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }


            DB::table('religion')
                    ->take(1)
                    ->where('id',$religion_id)
                    ->update([
                        'deleted'=>1
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Religion Deleted!',
                'data'=>self::religion_list()
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
