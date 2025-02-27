<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;
use \Carbon\CarbonTimeZone;
use Auth;
use DateTime;
use Session;
use \Carbon\CarbonPeriod;
class AttendanceInfo extends Model
{
    public static function today($type)
    {

        date_default_timezone_set('Asia/Manila');
        // 7 = student;
        if($type == 7)
        {   
            $users = DB::table('studinfo')
                ->select('id','userid','lastname','firstname','middlename','suffix')
                ->where('userid','!=',null)
                ->where('deleted','0')
                ->whereIn('studstatus',[1,2,4])
                ->orderBY('lastname','asc')
                ->get();

            $taphistories = DB::table('taphistory')
                ->where('tdate', date('Y-m-d'))
                ->whereIn('studid',collect($users)->pluck('id'))
                ->where('utype', 7)
                ->where('deleted','0')
                ->get();
        }else{
            $users = DB::table('teacher')
                ->select('id','userid','lastname','firstname','middlename','suffix')
                ->where('userid','!=',null)
                ->where('deleted','0')
                ->orderBY('lastname','asc')
                ->get();
               
            $taphistories = collect();
            $tapping = DB::table('taphistory')
                ->where('tdate', date('Y-m-d'))
                ->whereIn('studid',collect($users)->pluck('id'))
                ->where('utype','!=', 7)
                ->where('deleted','0')
                ->get();
            $hrattendance = DB::table('hr_attendance')
                ->where('tdate', date('Y-m-d'))
                ->whereIn('studid',collect($users)->pluck('id'))
                ->where('utype','!=', 7)
                ->where('deleted','0')
                ->get();

            $taphistories = $taphistories->merge($tapping);
            $taphistories = $taphistories->merge($hrattendance);
            $taphistories = $taphistories->values()->all();
        }


        if(count($taphistories) == 0)
        {
            foreach($users as $user)
            {
                $user->timein = null;
            }
        }else{
            foreach($users as $user)
            {
                try{
                    $timein = collect($taphistories)->where('studid', $user->id)->sortBy('ttime')->first()->ttime;
                    $user->timein = $timein;
                }catch(\Exception $error)
                {
                    $user->timein = null;
                }
                $user->taps = $timein = collect($taphistories)->where('studid', $user->id)->sortBy('ttime');
            }
        }
        foreach($users as $user)
        {
            if($user->timein == null) ///HR
            {///HR
                $status = 0;///HR
            }else{///HR
                $status = 1;///HR
            }///HR
            if($user->middlename == null){

                $user->name = $user->lastname.', '.$user->firstname.' '.$user->suffix;
                $user->status =  $status;
 
             }else{
 
                 $user->name = $user->lastname.', '.$user->firstname.' '.$user->middlename[0].' '.$user->suffix;
                 $user->status =  $status;
 
             }


            $user->teacher = $user->name;
            
        }
        return $users;
    }
}
