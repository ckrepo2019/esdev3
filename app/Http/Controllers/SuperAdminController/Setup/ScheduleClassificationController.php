<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class ScheduleClassificationController extends \App\Http\Controllers\Controller
{
    public static function schedclassification_list(){

        $schedule_classifiation = DB::table('schedclassification')
                                    ->where('deleted',0)
                                    ->select(
                                        'id',
                                        'description',
                                        'description as text'
                                    )
                                    ->get();

        return $schedule_classifiation;

    }

    public static function schedclassification_create(Request $request){

        try{

            $schedclass_desc = $request->get('schedclass_desc');

            $check = DB::table('schedclassification')
                        ->where('description',$schedclass_desc)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('schedclassification')
                    ->insert([
                        'description'=>$schedclass_desc,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'createdby'=>auth()->user()->id,
                        'deleted'=>0
                    ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Schedule Classification Created!',
                        'data'=>self::schedclassification_list()
                    ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }

       

       


    }

    public static function schedclassification_update(Request $request){
        try{

            $schedclass_desc = $request->get('schedclass_desc');
            $schedclass_id = $request->get('id');

            $check = DB::table('schedclassification')
                        ->where('description',$schedclass_desc)
                        ->where('id','!=',$schedclass_id)
                        ->where('deleted',0)
                        ->count();

          
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            $check = DB::table('sh_classscheddetail')
                        ->where('classification',$schedclass_id)
                        ->where('deleted',0)
                        ->count();

            
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                    'data'=>self::schedclassification_list()
                ]);
            }


            $check = DB::table('classscheddetail')
                        ->where('classification',$schedclass_id)
                        ->where('deleted',0)
                        ->count();

            
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }

            DB::table('schedclassification')
                ->take(1)
                ->where('id',$schedclass_id)
                ->update([
                    'description'=>$schedclass_desc,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'updatedby'=>auth()->user()->id,
                ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Schedule Classification Updated!',
                        'data'=>self::schedclassification_list()
                    ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function schedclassification_delete(Request $request){
        try{

            $schedclass_id = $request->get('id');

            $check = DB::table('sh_classscheddetail')
                        ->where('classification',$schedclass_id)
                        ->where('deleted',0)
                        ->count();

            
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                    'data'=>self::schedclassification_list()
                ]);
            }


            $check = DB::table('classscheddetail')
                        ->where('classification',$schedclass_id)
                        ->where('deleted',0)
                        ->count();

            
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Used!',
                ]);
            }

            DB::table('schedclassification')
                ->take(1)
                ->where('id',$schedclass_id)
                ->update([
                    'deleted'=>1,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'deletedby'=>auth()->user()->id,
                ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Schedule Classification Deleted!',
                        'data'=>self::schedclassification_list()
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
