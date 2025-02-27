<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Hash;

class ProjectSetupController extends \App\Http\Controllers\Controller
{

      public static function hybrid2_online(){
           return [7,9];
      }

      public static function hybrid1_online(){
            return [6,7,9,1,2,18,14,16];
      }

      public static function update_essentiellink(Request $request){

            try{
                  $essentiellink = $request->get('essentiellink');

                  DB::table('schoolinfo')
                        ->update([
                              'essentiellink'=>$essentiellink
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Essentiel Link Updated!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function update_projectsetup(Request $request){

            try{
                  $projectsetup = $request->get('projectsetup');

                  DB::table('schoolinfo')
                        ->update([
                              'projectsetup'=>$projectsetup
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Project Setup Updated!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function update_processsetup(Request $request){

            $processsetup = $request->get('processsetup');
            $schoolinfo = DB::table('schoolinfo')->first();
            try{
                  if($processsetup == 'all'){
                        $setup = 0;
                        $studinof_crud = 'offline';
                  }else if($processsetup == 'hybrid1' || $processsetup == 'hybrid2'){
                        $setup = 1;
                        $studinof_crud = 'online';
                  }else{
                        $setup = 1;
                        $studinof_crud = 'offline';
                  }



                  DB::table('schoolinfo')
                        ->update([
                              'setup'=>$setup,
                              'studinfo_crud'=>$studinof_crud,
                              'processsetup'=>$processsetup
                        ]);


                  DB::table('usertype')
                        ->where('deleted',0)
                        ->update([
                              'type_active'=>0,
                              'updated_on'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  if($processsetup == 'all'){
                        DB::table('usertype')
                              ->where('deleted',0)
                              ->update([
                                    'type_active'=>1,
                                    'updated_on'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else if($processsetup == 'hybrid1'){

                        $hybrid1_online = self::hybrid1_online();

                        if($schoolinfo->projectsetup == 'online'){
                              DB::table('usertype')
                                    ->where('deleted',0)
                                    ->whereIn('id',$hybrid1_online)
                                    ->update([
                                          'type_active'=>1,
                                          'updated_on'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }else{
                              DB::table('usertype')
                                    ->where('deleted',0)
                                    ->whereNotIn('id',$hybrid1_online)
                                    ->update([
                                          'type_active'=>1,
                                          'updated_on'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              DB::table('usertype')
                                    ->where('deleted',0)
                                    ->whereIn('id',[16,14,6])
                                    ->update([
                                          'type_active'=>1,
                                          'updated_on'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                       
                  }else{

                        $hybrid2_online = self::hybrid2_online();

                        if($schoolinfo->projectsetup == 'online'){
                              DB::table('usertype')
                                    ->where('deleted',0)
                                    ->whereIn('id',$hybrid2_online)
                                    ->update([
                                          'type_active'=>1,
                                          'updated_on'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }else{
                              DB::table('usertype')
                                    ->where('deleted',0)
                                    ->whereNotIn('id',$hybrid2_online)
                                    ->update([
                                          'type_active'=>1,
                                          'updated_on'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }
      
                  DB::table('usertype')
                        ->where('deleted',0)
                        ->where('id',17)
                        ->update([
                              'type_active'=>1,
                              'updated_on'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Process Setup Updated!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function update_olinelink(Request $request){

            $onlinelink = $request->get('onlinelink');

            try{

                  DB::table('schoolinfo')
                        ->update([
                              'es_cloudurl'=>$onlinelink,
                              'websitelink'=>$onlinelink
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Process Setup Updated!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }
      

      public static function usertypes(){

            try{

                  $usertypes = DB::table('usertype')
                                          ->where('id','!=',17)
                                          ->where('deleted',0)
                                          ->get();

                  return $usertypes;

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
                  'data'=>'Something went wrong!'
            ]);
      }
}
