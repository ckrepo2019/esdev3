<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class acdprgAcadProgController extends \App\Http\Controllers\Controller
{
      public static function acdprgList(Request $request){

            $academiprogram = DB::table('academicprogram')
                                    ->get();

            return $academiprogram;

      }   
      
      public static function acdprgListDatatable(Request $request){

            $search = $request->get('search');
            $search = $search['value'];

            $academiprogram = DB::table('academicprogram')
                                    ->where(function($query) use($search){
                                          $query->orWhere('acadprogcode','like','%'.$search.'%');
                                          $query->orWhere('progname','like','%'.$search.'%');
                                    })
                                    ->take($request->get('length'))
                                    ->skip($request->get('start'))
                                    ->get();

            $academiprogramCount = DB::table('academicprogram')
                                    ->where(function($query) use($search){
                                          $query->orWhere('acadprogcode','like','%'.$search.'%');
                                          $query->orWhere('progname','like','%'.$search.'%');
                                    })
                                    ->count();

            return @json_encode((object)[
                  'data'=>$academiprogram,
                  'recordsTotal'=>$academiprogramCount,
                  'recordsFiltered'=>$academiprogramCount
            ]);

      }   

      public static function acdprgUpdate(Request $request){

            $acdprgID = $request->get('acdprgID');
            $acdprgNoDP =  $request->get('acdprgNoDP');
           

            try{
                  DB::table('academicprogram')
                        ->take(1)
                        ->where('id',$acdprgID)
                        ->update([
                              'nodp'=>$acdprgNoDP
                        ]);

             
                  DB::table('gradelevel')
                        ->where('deleted',0)
                        ->where('acadprogid',$acdprgID)
                        ->update([
                              'nodp'=>$acdprgNoDP
                        ]);

                  return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Academic Program Updated!',
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
                  'message'=>'Something went wrong!',
            ]);
      }

}
