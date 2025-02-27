<?php

namespace App\Models\VersionControl;
use DB;

use Illuminate\Database\Eloquent\Model;

class VersionControl extends Model
{

      public static function get_all_versions(){

            return DB::table('zversion_control')
                              ->join('zversion_control_module',function($join){
                                    $join->on('zversion_control.module','=','zversion_control_module.moduleid');
                              })
                              ->select(
                                    'moduleid',
                                    'description',
                                    'version',
                                    'moduleid',
                                    'isactive',
                                    'zversion_control.id'
                                    
                                    )
                              ->get();


      }
      
      public static function get_version_module(){

            return DB::table('zversion_control_module')
                        ->select('moduleid','description')
                        ->get();

      }

      public static function module_id(){

            return DB::table('zversion_control_module')
                        ->select('moduleid','description')
                        ->get();

      }
      
      
}
