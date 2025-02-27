<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;
// use App\Section;
// use App\EnrolledStudent;
// use App\AssignSubject;
// use App\GradeLevel;
// use App\Teacher;
class TeacherPortalController extends \App\Http\Controllers\Controller
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
        $section = DB::table('sections')
            ->where('teacherid',$teacher_id[0]->id)
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
                            $get_level_name = DB::table('gradelevel')
                                        ->select('levelname')
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
                return $notifications;
        return view('teacher.home')
                // ->with('teacher_info',$teacher_info)
                ->with('sections',$countSection)
                ->with('sectionname',$unique)
                ->with('sched',$datas)
                ->with('notifications',$notifications);
    }
    public function updateNotifStatus($id)
    {
        
        $notif = DB::table('notifications')
                ->select('status')
                ->where('id', $id)
                ->get();
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
    public function announcements()
    {
        // return 'dsfsdf';

        $teacher_info = Db::table('teacher')
            ->where('teacher.userid',auth()->user()->id)
            ->get();
        $assignedSched = DB::table('assignsubjdetail')
            ->select('gradelevel.id as glevelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
            ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.id')
            ->join('gradelevel','assignsubj.glevelid','=','gradelevel.id')
            ->join('sections','assignsubj.sectionid','=','sections.id')
            ->where('assignsubjdetail.teacherid',$teacher_info[0]->id)
            ->where('assignsubjdetail.deleted','0')
            ->where('assignsubj.deleted','0')
            ->distinct()
            ->get();
        $assignedSchedSenior = DB::table('sh_classsched')
            ->select('gradelevel.id as glevelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
            // ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.id')
            ->join('gradelevel','sh_classsched.glevelid','=','gradelevel.id')
            ->join('sections','sh_classsched.sectionid','=','sections.id')
            ->where('sh_classsched.teacherid',$teacher_info[0]->id)
            ->where('sh_classsched.deleted','0')
            ->distinct()
            ->get();
            // return $assignedSchedSenior;
        $assignedSchedBlock = DB::table('sh_blocksched')
            ->select('gradelevel.id as glevelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
            // ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.id')
            ->join('gradelevel','sh_blocksched.levelid','=','gradelevel.id')
            ->join('sections','sh_blocksched.id','=','sections.blockid')
            ->where('sh_blocksched.teacherid',$teacher_info[0]->id)
            ->where('sh_blocksched.deleted','0')
            ->distinct()
            ->get();
        if(count($assignedSched)!=0 && count($assignedSchedSenior)!=0){
            foreach($assignedSchedSenior as $senior){
                $assignedSched->push($senior);
            }
        }
        if(count($assignedSched)!=0 && count($assignedSchedSenior)!=0 && count($assignedSchedBlock)!=0 ){
            foreach($assignedSchedBlock as $block){
                $assignedSched->push($block);
            }
        }
        $logs = DB::table('announcements')
            ->select('announcements.id','announcements.title','announcements.content','announcements.created_at','sections.sectionname')
            ->join('notifications','announcements.id','=','notifications.headerid')
            ->join('enrolledstud','notifications.recieverid','=','enrolledstud.studid')
            ->join('sections','enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
            ->where('announcements.createdby',$teacher_info[0]->id)
            ->where('notifications.type','1')
            ->groupBy('headerid')
            ->get();
            // return $logs;
        $logsSenior = DB::table('announcements')
            ->select('announcements.id','announcements.title','announcements.content','announcements.created_at','sections.sectionname')
            ->join('notifications','announcements.id','=','notifications.headerid')
            ->join('sh_enrolledstud','notifications.recieverid','=','sh_enrolledstud.studid')
            ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
            ->where('announcements.createdby',$teacher_info[0]->id)
            ->where('notifications.type','1')
            ->groupBy('headerid')
            ->get();
        if(count($logs)==0 && count($logsSenior)!=0){
            $logs = $logsSenior;
        }
        if(count($logs)!=0 && count($logsSenior)!=0){
            $logs->push($logsSenior[0]);
        }
        // return $logs;
        foreach($logs as $log){
            foreach($log as $key => $item){
                $sections = DB::table('announcements')
                    ->join('notifications','announcements.id','=','notifications.headerid')
                    ->join('enrolledstud','notifications.recieverid','=','enrolledstud.studid')
                    ->join('sections','enrolledstud.sectionid','=','sections.id')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->where('announcements.createdby',$teacher_info[0]->id)
                    ->where('announcements.id',$log->id)
                    ->groupBy('sectionname')
                    ->get();
                // if(count($sections)==0){
                $sectionsSenior = DB::table('announcements')
                    ->join('notifications','announcements.id','=','notifications.headerid')
                    ->join('sh_enrolledstud','notifications.recieverid','=','sh_enrolledstud.studid')
                    ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->where('announcements.createdby',$teacher_info[0]->id)
                    ->where('announcements.id',$log->id)
                    ->groupBy('sectionname')
                    ->get();
                    // return $sectionsSenior;
                if(count($sectionsSenior)!=0){
                    foreach($sectionsSenior as $sectionsenior){
                        $sections->push($sectionsenior);
                    }
                }
                $sectionsArray = array();
                foreach($sections as $section){
                    array_push($sectionsArray,$section->levelname.' - '.$section->sectionname);
                }
                $log->sections = $sectionsArray;
            }
        }
        // return $logs;
        if(count($assignedSched) == 0){
            $assignedSched = DB::table('sections')
                ->select('sections.id as sectionid','gradelevel.levelname','sections.sectionname')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->where('sections.teacherid',$teacher_info[0]->id)
                ->where('sections.deleted','0')
                ->get();
        }
        // return $logs;
        return view('teacher.announcements')
            ->with('teacher_info',$teacher_info)
            ->with('assignedSched',$assignedSched)
            ->with('logs',$logs);

    }
    public function publish_announcements(Request $request)
    {
        // return $request->all();
        $teacher_id = $request->get('teacherid');
        $title = $request->get('title');
        $content = $request->get('announcement_content');
        $checkIfExists = Db::table('announcements')
            ->where('title', 'like', '%'.$title)
            ->get();
        // return count($checkIfExists);
        if(count($checkIfExists)==0){
            $headerid = DB::table('announcements')
            ->insertGetId([
                'title' => $title,
                'content' => $content,
                'recievertype' => '4',
                'announcementtype' => '1',
                'createdby' => $teacher_id
            ]);
            // return $headerid;
            foreach($request->get('recipients') as $rec){
                $explodeRecipient = explode(" - ",$rec);
                $sectionname = $explodeRecipient[0];
                $sectionid = $explodeRecipient[1];
                // return $sectionid;
                    $sections = DB::table('sections')
                        ->select('enrolledstud.studid')
                        ->join('enrolledstud','sections.id','=','enrolledstud.sectionid')
                        ->join('sy','enrolledstud.syid','=','sy.id')
                        ->where('sections.deleted','0')
                        ->where('sections.id',$sectionid)
                        ->where('sections.sectionname',$sectionname)
                        ->where('sy.isactive','1')
                        ->distinct()
                        ->get();
                    $sectionsenior = DB::table('sections')
                        ->select('sh_enrolledstud.studid')
                        ->join('sh_enrolledstud','sections.id','=','sh_enrolledstud.sectionid')
                        ->join('sy','sh_enrolledstud.syid','=','sy.id')
                        ->where('sections.deleted','0')
                        ->where('sections.id',$sectionid)
                        ->where('sections.sectionname',$sectionname)
                        ->where('sy.isactive','1')
                        ->distinct()
                        ->get();
                        // return $sectionsenior;
                    // return $sections->push()
                    if(count($sections)==0 && count($sectionsenior)!=0){
                        $sections = $sectionsenior;
                    }
                    if(count($sectionsenior)!=0 && count($sectionsenior)!=0){
                        $sections->push($sectionsenior[0]);
                    }
                    // return $sections;
                    foreach($sections as $stud){
                        // return $stud->studid;
                        $asdfsdf = DB::table('notifications')
                        ->insert([
                            'headerid' => $headerid,
                            'recieverid' => $stud->studid,
                            'type' => 1,
                            'status' => 1
                        ]);
                        // if($asdfsdf){
                        //     return "inserted";
                        // }
                        // else{
                        //     return "not inserted";
                        // }
                        // return $asdfsdf;
                    }
            }
            return redirect('/announcements')->with(['messageSuccess' => 'Announcement published successfully!']);
        }
        else{
            return redirect('/announcements')->with(['messageWarning' => 'Announcement already exists!']);
        }
    }
}


     