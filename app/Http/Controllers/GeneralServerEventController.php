<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DB;
use Session;
use \Carbon\Carbon;
use App\Models\Principal\SPP_Notification;
use Illuminate\Support\Str;

class GeneralServerEventController extends Controller
{
    public function serverEventGetNotifications(){

  

        $postnotification = array();

        $dataString = '';

        $notifications = SPP_Notification::viewNotifications(null,5,auth()->user()->id,'0',null);
        
        // return $notifications;

        $gradeLogs = '#';
        $annoucement = '#';
        $grades = '#';
        $viewAll = '#';

        if(auth()->user()->type == '2'){
            $gradelogs = '/principalReadAnnouncement/';
            $gradeLogs = null;
            $annoucement = null;
            $grades = null;
        }

        else if(auth()->user()->type == '7'){
            $gradeLogs = null;
            $annoucement = '/viewAnnouncement/';
            $grades = '/gradeannouncement/';
            $viewAll = '/viewAllAnnouncement/';
        }

        else if(auth()->user()->type == '9'){
            $gradeLogs = null;
            $annoucement = '/parentviewAnnouncement/';
            $grades = '/parentsgradeannouncement/';
            $viewAll = '/parentviewAllAnnouncement/';
        }

        foreach($notifications[0]->data as $key=>$notification){
            
            $dataString.='<div class="dropdown-divider"></div>';

            if($notification->type == '1'){

                $dataString .= '<a style="font-size:12px;" href="'.$annoucement.$notification->headerid.'" class="dropdown-item">
                                    <div class="media">
                                    <div class="media-body">
                                    <h3 class="dropdown-item-title">';

                $dataString.= $notification->announcementTeacherFirstname.' '.$notification->announcementTeacherLastName;

                if($notification->status==0){

                    $dataString.= '<span class="float-right text-sm text-info"><i class="text-info fas fa-bookmark"></i></span>';
                }

                else{
                    $dataString.= '<span class="float-right text-sm text-muted"><i class="text-muted fas fa-bookmark"></i></span>';
                }
                
                $dataString.='</h3>Posted an announcement<br>
                                </p><span class="text-muted"> "'.Str::limit($notification->announcementtitle, $limit = 30, $end = '...').'"</span>
                                    <span class="text-muted float-right" style="font-size:12px;">
                                    <i class="far fa-clock mr-1">
                                    </i>'.Carbon::parse($notification->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW).'</span>
                                    </div>
                                    </div></a>';
                
                                
            }
            if($notification->type == '2'){


                if($notification->acadprogid == 5){
                    $subject = $notification->studsubjdesc;
                }
                else{
                    $subject = $notification->shstudsubjdesc;
                }

                $dataString .= '<a style="font-size:12px;" href="'.$grades.$notification->headerid.'" class="dropdown-item">
                                    <div class="media">
                                    <div class="media-body">
                                    <p class="dropdown-item-title"><small>';

                $dataString.= Str::limit($notification->gradelogsubject, $limit = 30, $end = '...').'</small>';

                if($notification->status==0){

                    $dataString.= '<span class="float-right text-sm text-info"><i class=" text-dark fas fa-bookmark"></i></span>';
                }

                else{
                    $dataString.= '<span class="float-right text-sm text-muted"><i class=" text-dark fas fa-bookmark"></i></span> ';
                }
                
                $dataString.='</p><p>Quarter '.$notification->gradequarter.' grades </p>was posted<span             class="text-muted float-right" style="font-size:12px;">
                                    <i class="far fa-clock mr-1">
                                    </i>'.Carbon::parse($notification->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW).'</span>
                                </p><span>
                                </span>
                                </div>
                                </div></a>';
                                
                                
            }
            else if($notification->type == '3'){

                $dataString .= '<a style="font-size:12px;" href="'.$gradeLogs.$notification->gradeid.'/'.$notification->headerid.'" class="dropdown-item">
                                    <div class="media">
                                    <div class="media-body">
                                    <h3  class="dropdown-item-title">';
                                    
                $dataString.= $notification->gradeLogTeacherFirstName.' '.$notification->gradeLogTeacherLastName;

                if($notification->status==0){
                    $dataString.= '<span class="float-right text-sm text-info"><i class="text-dark fas fa-bookmark"></i></span>';
                }
                else{
                    $dataString.= '<span class="float-right text-sm text-muted"><i class="text-dark fas fa-bookmark"></i></span>';
                }

                $dataString.='</h3>posted '.$subject.' Quarter '.$notification->quarter
                                .' grades <p><span>for '.$notification->levelname.' - '.$notification->sectionname.'</span>
                                <span class="text-muted float-right" style="font-size:12px;"><i class="far fa-clock mr-1">
                                </i>'.Carbon::parse($notification->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW).'</span>
                                </p></div>
                                    </div></a>';
                }

            }
        
         $dataString.=' <div class="dropdown-divider"></div><a href="'.$viewAll.'" class="dropdown-item dropdown-footer">See All Messages</a>';

        $data = array();

        $countString = '<i class="far fa-comments"></i>
                            <span class="badge badge-danger navbar-badge">'.$notifications[0]->count.'</span>';
    

        array_push($data, (object)['notifcations'=>$dataString,'count'=>$countString ]);

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
