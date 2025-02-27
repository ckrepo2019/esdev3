<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;
use App\Section;
use App\EnrolledStudent;
use App\AssignSubject;
use App\GradeLevel;
use App\Teacher;
class TeacherDashboardController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $teacher_id = DB::table('teacher')
                    ->select('id')
                    ->where('userid',auth()->user()->id)
                    ->get();
        $headerIds = DB::table('assignsubjdetail')->select('headerid','subjid')
                    ->where('headerid','!=',null)
                    ->where('teacherid',$teacher_id[0]->id)
                    ->get();
        $collectHeaders = collect($headerIds)->unique();
        $headerIDArray  = array();
        foreach($collectHeaders as $header_id){
            $get_header_id = DB::table('assignsubj')
                    ->select('assignsubj.glevelid','assignsubj.sectionid')
                    ->where('assignsubj.ID',$header_id->headerid)
                    ->join('assignsubjdetail','assignsubjdetail.headerid','=','assignsubj.ID')
                    ->get();
                    array_push($headerIDArray,$get_header_id);
        }
        foreach($headerIDArray as $glevelid){
            $get_level_name = DB::table('gradelevel')
                    ->select('levelname')
                    ->where('id',$glevelid[0]->glevelid)
                    ->get();
                    array_push($headerIDArray,$get_level_name);
        }
        $section = Section::where('teacherid',$teacher_id[0]->id)
                    ->get();
        $countSection = count($section);
        $headerIds = DB::table('assignsubjdetail')->select('headerid')
                    ->where('headerid','!=',null)
                    ->where('teacherid',$teacher_id[0]->id)->get();

        $headerIDArray  = array();

        foreach ($headerIds as $headerId){
            array_push($headerIDArray,$headerId->headerid);
        }

        $datas = array();

        foreach(array_unique($headerIDArray) as $uniqueHeader){
            $assignsubjID = DB::table('assignsubj')
                    ->select('sectionid')
                    ->where('id',$uniqueHeader)
                    ->get();

            $headerIds = DB::table('assignsubjdetail')->select('subjid')
                    ->where('headerid', $uniqueHeader)
                    ->where('teacherid',$teacher_id[0]->id)->get();

            foreach($headerIds as $headerId){

                if(count($assignsubjID)!=0){

                    $classschedHeaderID = DB::table('classsched')->select('id','glevelid')
                                ->where('sectionid',$assignsubjID[0]->sectionid)
                                ->where('subjid',$headerId->subjid)->get();

                    if(count($classschedHeaderID)!=0){
                        $classschedDetails = DB::table('classscheddetail')
                                ->select('days','stime','etime','roomid')
                                ->where('headerid',$classschedHeaderID[0]->id)
                                ->orderBy('days','asc')
                                ->get();
                        foreach($classschedDetails as $classschedDetail){
                            $get_level_name = GradeLevel::select('levelname')
                                        ->where('id',$classschedHeaderID[0]->glevelid)
                                        ->get();

                            $get_section_name = Section::select('sectionname')
                                        ->where('id',$assignsubjID[0]->sectionid)
                                        ->get();
                        
                            $get_subject_desc = DB::table('subjects')
                                        ->where('id',$headerId->subjid)
                                        ->get();
                            $room = DB::table('rooms')
                                        ->where('id',$classschedDetail->roomid)
                                        ->select('roomname')
                                        ->get();
                            // return $get_level_name;
                            array_push($datas,array(
                                "gradelevel"=>$get_level_name[0]->levelname,
                                "section"=>$get_section_name[0]->sectionname,
                                "subject"=>$get_subject_desc[0]->subjdesc,
                                "days"=>$classschedDetail->days,
                                "stime"=>$classschedDetail->stime,
                                "etime"=>$classschedDetail->etime,
                                "room"=>$room[0]->roomname
                            )); 
                        }
                    }
                }
            }
        }
        $collection = collect($datas);
        $unique = $collection->unique('section')->sortBy('days');
        $countSection = count($unique);
        // return $unique[0];
        // return $datas[0]['section'];
        // return $datas[0]->orderBy('days');
        $notifications = DB::table('teacher')
                ->join('notifications','teacher.userid','=','notifications.recieverid')
                ->where('teacher.userid',auth()->user()->id)
                ->get();
                // return $notifications;
        return view('teacher.home')
                // ->with('teacher_info',$teacher_info)
                ->with('sections',$countSection)
                ->with('sectionname',$unique)
                ->with('sched',$datas)
                ->with('notifications',$notifications);
    }

    public function updateNotifStatus($id)
    {
        // return $id;
        
        $notif = DB::table('notifications')
                ->select('status')
                ->where('id', $id)
                ->get();
        // $notifications = DB::table('users')
        //         ->select('announcements.content','announcements.id','announcements.created_at','announcements.status','teacher.id as teacher_id')
        //         ->join('teacher','users.id','=','teacher.userid')
        //         ->join('announcements','teacher.id','=','announcements.to')
        //         ->where('users.id',auth()->user()->id)
        //         ->get();
        $notifications = DB::table('teacher')
                ->join('notifications','teacher.userid','=','notifications.recieverid')
                ->where('teacher.userid',auth()->user()->id)
                ->get();
        $numOfNotifs = 0;

        $details = "";
        if($notif[0]->status == 0){
            DB::update('update notifications set status = ? where id = ? and `recieverid` = ?',[1,$id,$notifications[0]->userid]);
            $notification = DB::table('teacher')
                ->join('notifications','teacher.userid','=','notifications.recieverid')
                ->where('teacher.userid',auth()->user()->id)
                ->get();
            $numOfNotifs += count($notification->where('status',0));
            $details = $notification;
        }
        else{
            $notification = DB::table('teacher')
                ->join('notifications','teacher.userid','=','notifications.recieverid')
                ->where('teacher.userid',auth()->user()->id)
                ->get();
            $numOfNotifs += count($notification->where('status',0));
            $details = $notification;
        }
        $notifArray = array();
        array_push($notifArray,$numOfNotifs);
        array_push($notifArray,$details);
        array_push($notifArray,$id);
        return $notifArray;
    }
}


     