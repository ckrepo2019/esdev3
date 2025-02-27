<?php

namespace App\Http\Controllers\PrincipalControllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DB;
use \Carbon\Carbon;
use App\Models\Principal\LoadData;
use App\Models\Principal\SPP_Notification;
use Session;
use Crypt;

class PrincipalServerEventController extends \App\Http\Controllers\Controller
{
    public function loadNotifications(){

        $postnotification = array();

        $dataString = '';

        $notifications = SPP_Notification::viewNotifications(null,5,auth()->user()->id,'0',null);

    //    return    $notifications ;

        foreach($notifications[0]->data as $key=>$notification){
            
            $dataString.='<div class="dropdown-divider"></div>';

            if($notification->type == '1'){

                $url = null;

                if(Session::has('prinInfo')){
                    $url = '/principalReadAnnouncement/';
                }

                $dataString .= '<a style="font-size:12px;" href="'.$url.$notification->headerid.'" class="dropdown-item">
                                    <div class="media">
                                    <div class="media-body">
                                    <h3 class="dropdown-item-title">';

                $dataString.= $notification->announcementTeacherFirstname.' '.$notification->announcementTeacherLastName;

                if($notification->status==0){
                    $dataString.= '<span class="float-right text-sm text-info"><i class="fas fa-bookmark"></i></span>';
                }
                else{
                    $dataString.= '<span class="float-right text-sm text-muted"><i class="fas fa-bookmark"></i></span>';
                }
                
                $dataString.='</h3>Posted an announcement<br>
                                 </p><span class="text-dark"> "'.$notification->announcementtitle.'"</span>
                                    <span class="text-muted float-right" style="font-size:12px;">
                                    <i class="far fa-clock mr-1">
                                    </i>'.Carbon::parse($notification->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW).'</span>
                                    </div>
                                    </div></a>';
                
                                   
            }
            else if($notification->type == '3'){
                $dataString .= '<style>
                                .media-body {
                                    transition: .3s;
                                    padding: .5em;
                                    
                                }
                                h3 {transition: .3s;}
                                .fa-bookmark {
                                    transition: .4s;
                                    color: #ffc107;
                                    
                                }
                                .media-body:hover{
                                    background: skyblue;
                                    // padding-bottom: 1em;
                                }
                                .media-body:hover .fa-bookmark {
                                    color: #ffc107;
                                    text-shadow: 1px 1px 2px #fff;
                                    transition: .3s;
                                }
                                .media-body:hover h3 {
                                    color: green;
                                    text-shadow: 1px 1px 1px #fff;
                                    font-size: 17px;
                                }

                                </style>
                            
                                <a style="font-size:12px;" href="/principalgradeannouncement/'.Crypt::encrypt($notification->gradeid).'/'.Crypt::encrypt($notification->headerid).'" class="dropdown-item">
                                    <div class="media">
                                    <div class="media-body">
                                    <h3  class="dropdown-item-title">';
                                    
                $dataString.= $notification->gradeLogTeacherFirstName.' '.$notification->gradeLogTeacherLastName;

                if($notification->status==0){
                    $dataString.= '<span class="float-right text-sm text-info" style="padding: 1em"><i class="fas fa-bookmark"></i></span>';
                }
                else{
                    $dataString.= '<span class="float-right text-sm text-muted" style="padding: 1em"><i class="fas fa-bookmark"></i></span>';
                }
                if($notification->acadprogid == 5){

                    $dataString.='</h3><div style="width:60%;text-overflow:ellipsis;white-space:nowrap;overflow:hidden;">submitted '.$notification->shgradelogsubject.'</div> Quarter '.$notification->quarter
                                .' grades <p><span class="text-dark">for '.$notification->levelname.' - '.$notification->sectionname.'</span>
                                <span class="text-muted " style="font-size:12px;"><i class="far fa-clock mr-1">
                                </i>'.Carbon::parse($notification->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW).'</span>
                                </p></div>
                                </div></a>';
                }
                else{

                    $dataString.='</h3><div style="width:60%;text-overflow:ellipsis;white-space:nowrap;overflow:hidden;">submitted '.$notification->gradelogsubject.'</div> Quarter '.$notification->quarter
                                .' grades <p><span class="text-dark">for '.$notification->levelname.' - '.$notification->sectionname.'</span>
                                <span class="text-primary" style="font-size:12px;"><i class="far fa-clock mr-1">
                                </i>'.Carbon::parse($notification->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW).'</span>
                                </p></div>
                                </div></a>';
                }
            }

            
        }

        $dataString.=' <div class="dropdown-divider"></div><a href="/principalviewAllNotifications" class="dropdown-item dropdown-footer">See All Messages</a>';

        $data = array();

        $countString = '<i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge">'.$notifications[0]->count.'</span>';
    

        array_push($data, (object)['notifcations'=>$dataString,'count'=>$countString ]);

        $response = new StreamedResponse();

        $response->setCallback(function () use ($data){
             echo 'data: ' . json_encode($data) . "\n\n";
             ob_flush();
             flush();
             usleep(60000000);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->send();

        
        
    }

   


}
