<?php

namespace App\Http\Controllers\ParentControllers;


use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DB;
use \Carbon\Carbon;
use App\Models\Principal\LoadData;
use App\Models\Principal\AttendanceReport;
use Session;
use App\Models\Principal\SPP_Student;
use App\Models\Principal\SPP_Subject;


class ParentServerEventController extends \App\Http\Controllers\Controller
{

    
    public function homeevent(){

        $studendinfo = Session::get('studentInfo');

        $schedule  = SPP_Subject::getSchedule(date('w'),null,$studendinfo->ensectid,$studendinfo->blockid);

        $todaySubjectAttendance = SPP_Student::getTodaySubjectAttendance($studendinfo->id,$studendinfo->ensectid,$schedule);

        $datastring = '';

        $todaySubjectAttendance = collect($todaySubjectAttendance)->sortBy('stime');

        if(count($todaySubjectAttendance)>0){
            foreach($todaySubjectAttendance as $item){
                $datastring.= '<tr>
                    <td  width=20%>'.$item->subjcode.'</td>';
                
                    if($item->classstatus=="0"){
                        $datastring.='<td class="text-center" width=10%>
                            <span class="badge bg-secondary  d-block p-2">Class over</span>
                        </td>';
                    }
                    elseif($item->classstatus=="1"){
                        $datastring.='<td class="text-center"  width=10%>
                            <span class="badge bg-success  d-block p-2">Current class</span>
                        </td>';
                    }
                    else{
                        $datastring.='<td colspan="2" class="text-center"  width=10%>
                            <span class="badge bg-info  d-block p-2">Starts in '.$item->classstatus.' </span>
                        </td>';
                    }
              

                    if($item->subjectattendance=='absent'){
                        $datastring.='<td class="text-center" width=10%>
                            <span class="badge bg-danger p-2">Absent</span>
                        </td>';
                    }
                    elseif($item->subjectattendance=='present'){
                        $datastring.='<td class="text-center" width=10%>
                            <span class="badge bg-success p-2">Present</span>
                        </td>';
                    }
                    elseif($item->subjectattendance=='late' ){
                        $datastring.='<td class="text-center" width=10%>
                            <span class="badge bg-warning p-2">Late</span>
                        </td>';
                    }
                    elseif($item->classstatus=="0" || $item->classstatus=="1"){
                        $datastring.='<td class="text-center" width=10%>
                            <span class="badge bg-warning p-2">Not Checked</span>
                        </td>';
                    }
                
                $datastring.= '<tr>';
            }

        }
        else{
            $datastring.= '<tr><td>Your student doesn'.'\''.'t have a schedule for this day.<td><tr>';
        }

        $data = $datastring;

        $response = new StreamedResponse();
        $response->setCallback(function () use ($data){
             echo 'data: ' . json_encode($data) . "\n\n";
             ob_flush();
             flush();
             usleep(200000);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->send();

    }

    public function tapstate(){

        $studendinfo = Session::get('studentInfo');

        $todaySchoolAttendance = AttendanceReport::todaySchoolAttendance($studendinfo->id);

        $datastring = '';

        $datastring .=' <li class="list-group-item">
                            <b>Date</b> <a class="float-right  mb-0 h6 text-info">'.\Carbon\Carbon::now()->isoFormat('MMM DD, YYYY').'</a>
                        </li>';

        if(count($todaySchoolAttendance)==0){
            $datastring .='<li class="list-group-item">
                <b>Time In</b> <a class="float-right">
                <a class="float-right text-danger h6 mb-0">00:00</a></a>
            </li>
            <li class="list-group-item">
                <b>Time Out</b>
                <a class="float-right text-danger h6 mb-0">00:00</a></a>
            </li>
            <li class="list-group-item">
                <b>Status</b>
                <a class="float-right text-danger h6 mb-0">Outside Campus</a>
            </li>';
        }
        else{
            $datastring .='<li class="list-group-item">
                <b>Time In</b> <a class="float-right">';
                if($todaySchoolAttendance[0]->intimepm != NULL && $todaySchoolAttendance[0]->intimepm != '00:00:00' && $todaySchoolAttendance[0]->intimeam == '00:00:00'){
                    $datastring .='<a class="float-right text-success h6 mb-0">'.\Carbon\Carbon::create($todaySchoolAttendance[0]->intimepm)->isoFormat('hh : mm a').'</a>';
                }
                elseif($todaySchoolAttendance[0]->intimeam != NULL && $todaySchoolAttendance[0]->intimeam != '00:00:00' ){
                    $datastring .='<a class="float-right text-success h6 mb-0">'.\Carbon\Carbon::create($todaySchoolAttendance[0]->intimeam)->isoFormat('hh : mm a').'</a> ';
                }
                else{
                    $datastring .='<a class="float-right text-danger h6 mb-0">00 : 00</a> ';
                }
            $datastring .='</li>
                <li class="list-group-item">
                <b>Time Out</b>';
            
                if($todaySchoolAttendance[0]->outtimepm != NULL && $todaySchoolAttendance[0]->outtimepm != '00:00:00'){
                    $datastring .='<a class="float-right text-success h6 mb-0">'.\Carbon\Carbon::create($todaySchoolAttendance[0]->outtimepm)->isoFormat('hh : mm a').'</a>';
                }
                elseif($todaySchoolAttendance[0]->outtimeam != NULL && $todaySchoolAttendance[0]->outtimeam != '00:00:00' ){
                    $datastring .='<a class="float-right text-success h6 mb-0">'.\Carbon\Carbon::create($todaySchoolAttendance[0]->outtimeam)->isoFormat('hh : mm a').'</a>';
                }
                else{
                    $datastring .='<a class="float-right text-danger h6 mb-0">00 : 00</a>';
                }
            $datastring .='</li>
                <li class="list-group-item">
                <b>Status</b>';
                if($todaySchoolAttendance[0]->tapstate == 'IN' ){
                    $datastring .='<a class="float-right text-success h6 mb-0">Inside Campus
                    <br><span class="h6">'.\Carbon\Carbon::create($todaySchoolAttendance[0]->updateddatetime)->isoFormat('hh : mm a').'</span></a>';
                }
                else{
                    $datastring .='<a class="float-right text-danger h6 mb-0">Outside Campus
                    <br><span class="h6 float-right text-info mb-0">'.\Carbon\Carbon::create($todaySchoolAttendance[0]->updateddatetime)->isoFormat('hh : mm a').'</span></a>';
                }
            $datastring .='</li>';
        }


        $data = $datastring;

        $response = new StreamedResponse();
        $response->setCallback(function () use ($data){
             echo 'data: ' . json_encode($data) . "\n\n";
             ob_flush();
             flush();
             usleep(200000);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->send();
    }

    public function loadNotifications(){

        $studendinfo = Session::get('studentInfo');

        $postnotification = array();

        $notifications = DB::table('notifications')
                    ->where('recieverid',$studendinfo->id)
                    ->orderBy('status')
                    ->orderBy('created_at')
                    ->take(3)
                    ->get();

        $dataString = '';

        foreach($notifications as $key=>$notification){
            
            $dataString.='<div class="dropdown-divider"></div>';
                                  


            if($notification->type == '1'){

                $user = DB::table('announcements')
                        ->join('teacher','announcements.createdby','=','teacher.id')
                        ->select('announcements.id','teacher.firstname','teacher.lastname','announcements.created_at')
                        ->where('announcements.id',$notification->headerid)->first();

                if(isset($user)){

                    $dataString .= '<a href="/parentviewAnnouncement/'.$notification->id.'" class="dropdown-item">
                                        <div class="media">
                                        <div class="media-body">
                                        <h3 class="dropdown-item-title">';
                        
                    $dataString.= $user->firstname.' '.$user->lastname;

                    if($notification->status==0){
                        $dataString.= '<span class="float-right text-sm text-info"><i class="fas fa-bookmark"></i></span>';
                    }
                    else{
                        $dataString.= '<span class="float-right text-sm text-muted"><i class="fas fa-bookmark"></i></span>';
                    }
                    
                    
                    $dataString.='</h3><p class="text-sm">Posted an announcement</p>
                                        <p class="text-sm text-muted"><i class="far fa-clock mr-1">
                                        </i>'.Carbon::parse($notification->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW).'</p>
                                        </div>
                                        </div></a>';
                }
            
            }

            else if($notification->type == '2'){

                $gradesDetail = LoadData::loadGrades()
                                    ->where('grades.id',$notification->headerid)
                                    ->select('quarter','subjects.subjdesc')
                                    ->first();

                if(count($gradesDetail)>0){

                    $dataString .= '<a href="/parentsgradeannouncement/'.$notification->id.'" class="dropdown-item">
                                        <div class="media">
                                        <div class="media-body">
                                        <h3 class="dropdown-item-title">';

                    if($notification->status==0){
                        $dataString.= '<span class="float-right text-sm text-info"><i class="fas fa-bookmark"></i></span>';
                    }
                    else{
                        $dataString.= '<span class="float-right text-sm text-muted"><i class="fas fa-bookmark"></i></span>';
                    }

                    $dataString.='</h3>'.$gradesDetail[0]->subjdesc.' Quarter '.$gradesDetail[0]->quarter
                                    .' grades <p class="text-sm">Was Posted</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1">
                                    </i>'.Carbon::parse($notification->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW).'</p>
                                    </div>
                                    </div></a>';
                }
            }
        }

        $dataString.=' <div class="dropdown-divider"></div><a href="/parentviewAllAnnouncement" class="dropdown-item dropdown-footer">See All Messages</a>';
        $data = $dataString;
    

        $response = new StreamedResponse();
        $response->setCallback(function () use ($data){
             echo 'data: ' . json_encode($data) . "\n\n";
             ob_flush();
             flush();
             usleep(200000);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->send();
    }

    public function countUnreadNotifictions(){

        $studendinfo = Session::get('studentInfo');

        $notifications = DB::table('notifications')
                ->where('recieverid',$studendinfo->id)
                ->where('status','0')
                ->count();

        $dataString = '<i class="far fa-comments"></i>
                             <span class="badge badge-danger navbar-badge">'.$notifications.'</span>';

        $data = $dataString;

        $response = new StreamedResponse();
        $response->setCallback(function () use ($data){
             echo 'data: ' . json_encode($data) . "\n\n";
             ob_flush();
             flush();
             usleep(200000);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->send();
    }

    public function loadAllNotification(){

        $studendinfo = Session::get('studentInfo');

        $postnotification = array();

        $notifications = DB::table('notifications')
                    ->where('recieverid',$studendinfo->id)
                    ->orderBy('created_at','desc')
                    ->get();

        foreach($notifications as $key=>$notification){
            if($notification->type == '1'){

                $announcements =  DB::table('announcements')
                                ->join('users','announcements.createdby','=','users.id')
                                ->select('announcements.id','users.name','announcements.created_at')
                                ->where('announcements.id',$notification->headerid)->get();
                if(count($announcements)>0){
                    array_push($postnotification, (object) array(
                        'notificationInfo'=>$notification,
                        'notificationContent'=>$announcements[0]
                    ));
                }
               
            
            }
            else if($notification->type == '2'){
                $grades = LoadData::loadGrades()
                            ->where('grades.id',$notification->headerid)
                            ->select('quarter','subjects.subjdesc')->get();
                if(count($grades)>0){

                    array_push($postnotification,(object) array(
                        'notificationInfo'=>$notification,
                        'notificationContent'=>$grades[0]
                    ));

                }

               

            }
        }

        $dataString = '';

        foreach($postnotification as $item){
            $dataString.='<tr><td>';
            if($item->notificationInfo->type=='1'){
                if($item->notificationInfo->status=='0'){
                    $dataString.='<i class="fas fa-bookmark text-info"></i>';
                }
                else{
                    $dataString.='<i class="fas fa-bookmark text-muted"></i>';
                }
                $dataString.='  <a href="/parentviewAnnouncement/'.$item->notificationInfo->id.'">'.$item->notificationContent->name.'posted an announcement</a>
                <span class="float-right">'.Carbon::parse($item->notificationInfo->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW).'</span>';
            }
            else if($item->notificationInfo->type=='2'){
                if($item->notificationInfo->status=='0'){
                    $dataString.='<i class="fas fa-bookmark text-info"></i>';
                }
                else{
                    $dataString.='<i class="fas fa-bookmark text-muted"></i>';
                }

                $dataString.='  <a href="/parentgrades">'.$item->notificationContent->subjdesc.' Quarter '.$item->notificationContent->quarter.' Grades was posted</a>
                <span class="float-right">'. Carbon::parse($item->notificationInfo->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW).'</span>';
            }

            $dataString .= '</td></tr>';
        }


        $data = $dataString;

        $response = new StreamedResponse();
        $response->setCallback(function () use ($data){
             echo 'data: ' . json_encode($data) . "\n\n";
             ob_flush();
             flush();
             usleep(200000);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->send();
    }
}
