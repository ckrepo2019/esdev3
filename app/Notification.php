<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Notification extends Model
{
    public static function postNotification($message,$id){

        $recievers = DB::table('grades')
                        ->join('gradesdetail','grades.id','=','gradesdetail.headerid')
                        ->join('studinfo','gradesdetail.studid','=','studinfo.id')
                        ->where('grades.id',$id)
                        ->select('studinfo.userid')
                        ->get();

        foreach($recievers as $reciever){
            DB::table('notifications')
                ->insert([
                    'content'=>$message,
                    'reciever'=>$reciever->userid,
                    'status'=>0
                    ]);
        }

    }
    public static function gradeNotifiction($id){

        $gradeinfo = DB::table('grades')
                    ->join('subjects','grades.subjid','=','subjects.id')
                    ->where('grades.id',$id)
                    ->select('subjects.subjdesc','grades.quarter')
                    ->get()[0];

        if($gradeinfo->quarter == 1){
            $gradeinfo->quarter = "1st";
        }
        $message = $gradeinfo->quarter." Quarter grades for ".$gradeinfo->subjdesc." subject has been posted";

        return self::postNotification($message,$id);
    }
    public static function postAnnouncementNotification($request){
        return "HLELO";
    }
}
