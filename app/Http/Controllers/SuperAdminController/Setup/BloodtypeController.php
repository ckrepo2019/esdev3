<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class BloodtypeController extends \App\Http\Controllers\Controller
{
    public static function bloodtype_list(){

        $bloodtype = DB::table('bloodtype')
                                    ->where('deleted',0)
                                    ->select(
                                        'id as bloodtypeid',
                                        'bloodtypename as id',
                                        'bloodtypename as text'
                                    )
                                    ->orderBy('bloodtypename')
                                    ->get();

        return $bloodtype;

    }

    public static function bloodtype_create(Request $request){

        try{

            $bloodtype_name = $request->get('bloodtype_name');

            $check = DB::table('bloodtype')
                        ->where('bloodtypename',$bloodtype_name)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('bloodtype')
                    ->insert([
                        'bloodtypename'=>$bloodtype_name,
                        'deleted'=>0
                    ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'bloodtype Created!',
                        'data'=>self::bloodtype_list()
                    ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function bloodtype_update(Request $request){
        try{

            $bloodtype_name = $request->get('bloodtype_name');
            $bloodtype_id = $request->get('id');

      

            $check = DB::table('bloodtype')
                        ->where('bloodtypename',$bloodtype_name)
                        ->where('id','!=',$bloodtype_id)
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
                        ->where('bloodtype',$bloodtype_name)
                        ->where('deleted',0)
                        ->get();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }

            DB::table('bloodtype')
                    ->take(1)
                    ->where('id',$bloodtype_id)
                    ->update([
                        'bloodtypename'=>$bloodtype_name,
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'bloodtype Updated!',
                'data'=>self::bloodtype_list()
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function bloodtype_delete(Request $request){
        try{

            $bloodtype_id = $request->get('id');

            $bloodtypeinfo = DB::table('bloodtype')
                        ->where('id',$bloodtype_id)
                        ->where('deleted',0)
                        ->first();

            $check = DB::table('studinfo')
                        ->where('bloodtype',$bloodtypeinfo->bloodtypename)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }


            DB::table('bloodtype')
                    ->take(1)
                    ->where('id',$bloodtype_id)
                    ->update([
                        'deleted'=>1
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'bloodtype Deleted!',
                'data'=>self::bloodtype_list()
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
