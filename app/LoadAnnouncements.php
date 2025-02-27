<?php

namespace App;
use DB;
use Illuminate\Http\Request;
use Session;

use Illuminate\Database\Eloquent\Model;

class LoadAnnouncements extends Model
{
    public function announcements($id, $request){

        $content = DB::table('announcements')
        ->join('users','users.id','=','announcements.createdby')
        ->where('announcements.id',$id)
        ->select('announcements.content','announcements.title','announcements.created_at',
                'users.name'
                )
        ->get();

        DB::table('announcements')
            ->where('id',$id)
            ->update(['status'=>'1']);

        $this->updateSession();

        return $content;
    }

    public static function  allannouncements(Request $request){

        $content = DB::table('announcements')
            ->join('users','users.id','=','announcements.createdby')
            ->join('studinfo',function($join){
                $join->on('studinfo.id','=','announcements.to');
                $join->where('studinfo.userid',auth()->user()->id);
            })
            ->select('announcements.title','announcements.created_at','announcements.status',
                    'announcements.id',
                    'users.name'
                    )
        ->get();
        
        self::updateSession();
  

        return $content;
    }

    public static function updateSession(){

        $announcements = DB::table('announcements')
            ->select('users.name','announcements.created_at','status','announcements.id')
            ->join('users','users.id','=','announcements.createdby')
            ->join('studinfo',function($join){
                $join->on('studinfo.id','=','announcements.to');
                $join->where('studinfo.userid',auth()->user()->id);
            })
            ->latest()
            ->take(5)
            ->get();

        $unread = DB::table('announcements')
            ->join('studinfo',function($join){
                $join->on('studinfo.id','=','announcements.to');
                $join->where('studinfo.userid',auth()->user()->id);
            })
            ->where('status','0')
            ->count();

        Session::put('announcement', $announcements);
        Session::put('unread', $unread);
        
    }
}
