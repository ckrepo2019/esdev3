<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class RegistrarDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return 'asd';
        return view("portal.registrar.home");
        
    }

    public function updateNotifStatus($id)
    {
        $notif = DB::table('announcements')
                ->select('status')
                ->where('id', $id)
                ->get();
        $notifications = DB::table('users')
                ->select('announcements.content','announcements.id','announcements.created_at','announcements.status','teacher.id as teacher_id')
                ->join('teacher','users.id','=','teacher.userid')
                ->join('announcements','teacher.id','=','announcements.to')
                ->where('users.id',auth()->user()->id)
                ->get();
        $numOfNotifs = 0;
        $details = "";
        if($notif[0]->status == 0){
            DB::update('update announcements set status = ? where id = ? and `to` = ?',[1,$id,$notifications[0]->teacher_id]);
            $notification = DB::table('users')
                ->select('announcements.content','announcements.id','announcements.created_at','announcements.status','teacher.id as teacher_id')
                ->join('teacher','users.id','=','teacher.userid')
                ->join('announcements','teacher.id','=','announcements.to')
                ->where('users.id',auth()->user()->id)
                ->get();
            $numOfNotifs += count($notification->where('status',0));
            $details = $notification;
        }
        else{
            $notification = DB::table('users')
            ->select('announcements.content','announcements.id','announcements.created_at','announcements.status','teacher.id as teacher_id')
            ->join('teacher','users.id','=','teacher.userid')
            ->join('announcements','teacher.id','=','announcements.to')
            ->where('users.id',auth()->user()->id)
            ->get();
            $numOfNotifs += count($notification->where('status',0));
            $details = $notification;
        }
        $notifArray = array();
        array_push($notifArray,$numOfNotifs);
        array_push($notifArray,$details);
        return $notifArray;
    }
}