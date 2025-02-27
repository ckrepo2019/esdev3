<?php

namespace App\Models\AttendanceSetup;
use DB;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetupData extends Model
{
      public static function attendance_setup_list($syid = null, $levelid = null){

            $attendance_setup = DB::table('studattendance_setup')
                              ->where('deleted',0);

            if($syid != null){
                  $attendance_setup = $attendance_setup->where('syid',$syid);
            }
            if($levelid != null){
                  $attendance_setup = $attendance_setup->where('levelid',$levelid);
            }else{
                  $attendance_setup = $attendance_setup->whereNull('levelid');
            }

            $attendance_setup = $attendance_setup
                                    ->join('sy',function($join){
                                          $join->on('studattendance_setup.syid','=','sy.id');
                                    })
                                    ->select(
                                          'studattendance_setup.id',
                                          'studattendance_setup.syid',
                                          'studattendance_setup.month',
                                          'studattendance_setup.days',
                                          'studattendance_setup.year',
                                          'sydesc',
                                          'sort'
                                    )
                                    ->get();

            foreach( $attendance_setup as $item){
                  $item->monthdesc = \Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM');
            }

            return $attendance_setup;

      }

     
      
}
