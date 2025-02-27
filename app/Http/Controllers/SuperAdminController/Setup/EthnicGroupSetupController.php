<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class EthnicGroupSetupController extends \App\Http\Controllers\Controller
{
    public static function ethnicgroup_list(){

        $ethnicgroup = DB::table('ethnic')
                                    ->where('deleted',0)
                                    ->select(
                                        'id',
                                        'egname',
                                        'egname as text'
                                    )
                                    ->orderBy('egname')
                                    ->get();

        return $ethnicgroup;

    }

    public static function ethnicgroup_create(Request $request){

        try{

            $ethnicgroup_name = $request->get('ethnicgroup_name');

            $check = DB::table('ethnic')
                        ->where('egname',$ethnicgroup_name)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('ethnic')
                    ->insert([
                        'egname'=>$ethnicgroup_name,
                        'deleted'=>0
                    ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Ethic Group Created!',
                        'data'=>self::ethnicgroup_list()
                    ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function ethnicgroup_update(Request $request){
        try{

            $ethnicgroup_name = $request->get('ethnicgroup_name');
            $ethnicgroup_id = $request->get('id');

            $check = DB::table('ethnic')
                        ->where('egname',$ethnicgroup_name)
                        ->where('id','!=',$ethnicgroup_id)
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
                        ->where('egid',$ethnicgroup_id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }

            DB::table('ethnic')
                    ->take(1)
                    ->where('id',$ethnicgroup_id)
                    ->update([
                        'egname'=>$ethnicgroup_name,
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Ethic Group Updated!',
                'data'=>self::ethnicgroup_list()
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function ethnicgroup_delete(Request $request){
        try{

            $ethnicgroup_id = $request->get('id');

            $check = DB::table('studinfo')
                        ->where('egid',$ethnicgroup_id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }


            DB::table('ethnic')
                    ->take(1)
                    ->where('id',$ethnicgroup_id)
                    ->update([
                        'deleted'=>1
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Ethnic Group Deleted!',
                'data'=>self::ethnicgroup_list()
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
