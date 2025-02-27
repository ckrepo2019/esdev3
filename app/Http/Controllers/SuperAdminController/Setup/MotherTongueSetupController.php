<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class MotherTongueSetupController extends \App\Http\Controllers\Controller
{
    public static function mothertongue_list(){

        $mothertongue = DB::table('mothertongue')
                                    ->where('deleted',0)
                                    ->select(
                                        'id',
                                        'mtname',
                                        'mtname as text'
                                    )
                                    ->orderBy('mtname')
                                    ->get();

        return $mothertongue;

    }

    public static function mothertongue_create(Request $request){

        try{

            $mothertongue_name = $request->get('mothertongue');

            $check = DB::table('mothertongue')
                        ->where('mtname',$mothertongue_name)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('mothertongue')
                    ->insert([
                        'mtname'=>$mothertongue_name,
                        'deleted'=>0
                    ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Mother Tongure Created!',
                        'data'=>self::mothertongue_list()
                    ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function mothertongue_update(Request $request){
        try{

            $mothertongue_name = $request->get('mothertongue');
            $mothertongue_id = $request->get('id');

            $check = DB::table('mothertongue')
                        ->where('mtname',$mothertongue_name)
                        ->where('id','!=',$mothertongue_id)
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
                        ->where('mtid',$mothertongue_id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }

            DB::table('mothertongue')
                    ->take(1)
                    ->where('id',$mothertongue_id)
                    ->update([
                        'mtname'=>$mothertongue_name,
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Mother Tongue Updated!',
                'data'=>self::mothertongue_list()
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function mothertongue_delete(Request $request){
        try{

            $mothertongue_id = $request->get('id');

            $check = DB::table('studinfo')
                        ->where('mtid',$mothertongue_id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }

            DB::table('mothertongue')
                    ->take(1)
                    ->where('id',$mothertongue_id)
                    ->update([
                        'deleted'=>1
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Mother Tongue Deleted!',
                'data'=>self::mothertongue_list()
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
