<?php

namespace App\Models\Attendance;
use DB;

use Illuminate\Database\Eloquent\Model;

class AttendanceData extends Model
{
      public static function monthly_attendance_count($syid = null, $month = null, $studid = null, $year = null){

            $attendance_setup = DB::table('studattendance')
                                    ->where('deleted',0);
            
            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }
            else if($syid != null){
                  $attendance_setup = $attendance_setup->where('syid',$syid);
            }

            $attendance_setup = $attendance_setup->where('studid',$studid)
                              ->whereMonth('tdate','=',$month)
                              ->whereYear('tdate','=',$year)
                              ->get();
                             

            return collect($attendance_setup)->unique('attdate')->values();;

      }

      public static function daily_attendance_count($syid = null, $month = null, $studid = null){

            $attendance_setup = DB::table('studattendance')
                                    ->where('deleted',0);
            
            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }

            if($syid != null){
                  $attendance_setup = $attendance_setup->where('syid',$syid);
            }

            $attendance_setup = $attendance_setup->where('studid',$studid)
                              ->whereMonth('tdate','=',$month)
                              ->get();
                             

            return $attendance_setup;

      }


     
      
}
