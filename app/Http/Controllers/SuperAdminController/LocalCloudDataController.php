<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class LocalCloudDataController extends \App\Http\Controllers\Controller
{
    
      public static function local_tables(){

            $local_tables = DB::table('table_local')
                              ->where('deleted',0)
                              ->get();

            return $local_tables;

      }

      public static function cloud_tables(){

            $local_tables = DB::table('table_cloud')
                              ->where('deleted',0)
                              ->get();

            return $local_tables;

      }

      public static function get_updated(Request $request){

            $tablename = $request->get('tablename');
            $date = $request->get('date');

            $dateto =  \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');
            $datefrom = \Carbon\Carbon::create($date)->isoFormat('YYYY-MM-DD HH:mm:ss');

            $table_date = DB::table($tablename)
                        ->whereBetween('updateddatetime', [$datefrom, $dateto])
                        ->select('updateddatetime','id')
                        ->get();

            return  $table_date;

      }

      public static function get_deleted(Request $request){

            $tablename = $request->get('tablename');
            $date = $request->get('date');

            $dateto =  \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');
            $datefrom = \Carbon\Carbon::create($date)->isoFormat('YYYY-MM-DD HH:mm:ss');

            $table_date = DB::table($tablename)
                        ->whereBetween('deleteddatetime', [$datefrom, $dateto])
                        ->select('deleteddatetime','id')
                        ->get();

            return  $table_date;

      }

}
