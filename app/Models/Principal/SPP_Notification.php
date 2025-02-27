<?php

namespace App\Models\Principal;
use DB;

use Illuminate\Database\Eloquent\Model;

class SPP_Notification extends Model
{
    public static function viewNotifications(
        $skip = null, 
        $take = null, 
        $recieverid = null,
        $status = null, 
        $type = null,
        $searchSring = null
        ){

        $data = array();

        $notifications = DB::table('notifications');

        
      


        $notifications->when('notifications.type == 3 ',function($query){  

            $query->leftJoin('gradelogs',function($join){

                $join->on('notifications.headerid','=','gradelogs.id');

                // $join->where('notifications.type','3');

                $join->join('grades as gradeLogGrade',function($gradesjoin) {

                    $gradesjoin->on('gradelogs.gradeid','=','gradeLogGrade.id');
                    
                });

                $join->join('gradelevel as gradeLogLevel',function($join){

                    $join->on('gradeLogGrade.levelid','=','gradeLogLevel.id');

                });

                
                $join->when('gradeLogLevel.acadprogid == 5',function($join){

                        $join->leftjoin('sh_subjects as shgradeLogSubject',function($join){
                            $join->on('gradeLogGrade.subjid','=','shgradeLogSubject.id');
                            $join->where('shgradeLogSubject.deleted','0');
                        });
                    }
                );

                $join->when('gradeLogLevel.acadprogid != 5',function($join){

                    $join->leftjoin('subjects as gradeLogSubject',function($join){

                        $join->on('gradeLogGrade.subjid','=','gradeLogSubject.id');
                        $join->where('gradeLogSubject.deleted','0');

                    });

                });

                

                $join->leftJoin('teacher as gradeLogTeacher',function($tjoin){

                    $tjoin->on('gradelogs.user_id','=','gradeLogTeacher.userid');

                });

                $join->join('sections as gradeLogSection' , function($sectjoin){

                    $sectjoin->on('gradeLogGrade.sectionid','=','gradeLogSection.id');

                });

            });

            $query->addSelect(
                'gradeLogTeacher.firstname as gradeLogTeacherFirstName',
                'gradeLogTeacher.lastname as gradeLogTeacherLastName',
                'gradeLogGrade.quarter as quarter',
                'gradeLogSection.sectionname',
                'notifications.type',
                'gradelogs.gradeid',
                'notifications.headerid',
                'gradeLogLevel.acadprogid',
                'gradeLogLevel.levelname as levelname',
                'notifications.status',
                'notifications.created_at',
                'shgradeLogSubject.subjtitle as shgradelogsubject',
                'gradeLogSubject.subjdesc as gradelogsubject'
               
            );


        });

    
       

        $notifications->when('notifications.type == 2',function($query){  

            $query->leftJoin('grades as studgrades', function($join){
                $join->on('notifications.headerid','=','studgrades.id');
            })
            ->leftJoin('gradelevel as studgradelevel',function($join){

                $join->on('studgrades.levelid','=','studgradelevel.id');
                $join->where('studgradelevel.deleted','0');
            })
            ->when('studgradelevel.acadprogid == 5',function($join){

                $join->leftJoin('sh_subjects as shgradeSubject',function($join){

                    $join->on('studgrades.subjid','=','shgradeSubject.id');
                    $join->where('shgradeSubject.deleted','0');

                });

            })
            ->when('studgradelevel.acadprogid != 5',function($join){
                $join->leftJoin('subjects as gradeSubject',function($join){
                    $join->on('studgrades.subjid','=','gradeSubject.id');
                    $join->where('gradeSubject.deleted','0');

                });
            });
               
           

            $query->addSelect(

                'studgrades.quarter as gradequarter',
                'studgrades.id as gradegradeid',

                'notifications.headerid as gradeheaderid',
                'studgradelevel.acadprogid as gradeacadprogid',

                'studgradelevel.levelname as levelname',

                'notifications.type as gradetype',
                'notifications.status as gradestatus',
                'notifications.created_at as gradecreated_at'
            );

            $query->when('studgradelevel.acadprogid == 5',function($query){

                $query->addSelect('shgradeSubject.subjtitle as shstudsubjdesc');

            });

            $query->when('studgradelevel.acadprogid != 5',function($query){

                $query->addSelect('gradeSubject.subjdesc as studsubjdesc');

            });

           
        });


        $notifications->when('notifications.type == 1',function($query){ 

            $query->leftJoin('announcements',function($join){
                    $join->on('notifications.headerid','=','announcements.id');
                    $join->where('notifications.type','1');
                    $join->join('teacher as announcementTeacher',function($join){
                        $join->on('announcements.createdby','=','announcementTeacher.userid');
                    });   
                });

            $query->addSelect(
                'announcementTeacher.firstname as announcementTeacherFirstname',
                'announcementTeacher.lastname as announcementTeacherLastName',
                'notifications.headerid as announcementheaderid',
                'announcements.title as announcementtitle'

            );

        });

       

        if($recieverid!=null){

            $notifications->where('notifications.recieverid',$recieverid);

        }

   

        if($searchSring!=null){

            $notifications->where(function($query) use($searchSring){

                $query->where(function($lv2query) use($searchSring){

                    $lv2query->where('notifications.type','3');
                    $lv2query->where(function($lv3query) use($searchSring){
                        $lv3query->where('gradeLogTeacher.firstname','like',$searchSring.'%');
                        $lv3query->orWhere('gradeLogTeacher.lastname','like',$searchSring.'%');
                    });

                });

                $query->orWhere(function($lv2query) use($searchSring){
                    $lv2query->where('notifications.type','1');
                    $lv2query->where(function($lv3query) use($searchSring){
                        $lv3query->where('announcementTeacher.firstname','like',$searchSring.'%');
                        $lv3query->orWhere('announcementTeacher.lastname','like',$searchSring.'%');
                    }); 
                });
              
            });

            

        }

        

        if($status!=null){
            
            $notifications->where('notifications.status',$status);
            
        }

        $count = $notifications->distinct()->get()->count();

        $notifications->orderBy('notifications.status','asc');  
        $notifications->orderBy('notifications.created_at','desc');  
        

        

        if($take!=null){
            $notifications->take($take);
        }

        if($skip!=null){
            $notifications->skip(($skip-1)*$take);
        }

        array_push($data,(object)[
            'data'=>$notifications->get(),'count'=>$count]);

        return $data;    

    }

    public static function insertNotifications(
        $headerid = null,
        $recieverid = null,
        $type = null
    ){
        
        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        DB::table('notifications')->insert([
            'headerid'=>$headerid,
            'recieverid'=>$recieverid,
            'type'=>$type,
            'created_at'=>$date
        ]);
    
    }

    // public static function tableString($notifications){

    //     $dataString = '';
    //     $announcementUrl = '#';
    //     $gradeLogUrl = '#';
    //     $gradeInfoUrl = '#';

    //     if( auth()->user()->type == 9 ){

    //         $announcementUrl ='viewAnnouncement';
    //     }
    //     else if(auth()->user()->type == 7){
    //         $gradeInfoUrl = "studentgrades/";

    //     }
    //     else if(auth()->user()->type == 2){
    //         $announcementUrl = 'principalgradeannouncement';
    //     }

    //     foreach($notifications as $item){
    //         $dataString .= '<tr>';
    //             if($item->type=='3'){
    //                 $dataString .= '<td><a href="principalgradeannouncement/'.$item->gradeid.'/'.$item->headerid.'">'.$item->gradeLogTeacherFirstName.' '.$item->gradeLogTeacherLastName.'</a> submitted '.$item->subjdesc.'  Quarter '.$item->quarter.' Grades for '.$item->levelname.' - '.$item->sectionname.'  </td>';
    //             }
    //             else if($item->type=='2'){
    //                 $dataString .= ' <td><a href="'.$gradeInfoUrl.'/">'.$item->studsubject.'</a> Quarter '.$item->gradeQuarter.' grades was posted</td>';
    //             }
    //             else if($item->type=='1'){

    //                 $dataString .=   '<td><a href="'. $announcementUrl.'/'.$item->headerid.'">'.$item->announcementTeacherFirstname.' '.$item->announcementTeacherLastName.'</a> posted an announcement "'.$item->title.'"</td>';
    //             }

    //             if($item->status == 0){
    //                 $dataString .= '<td>Unread</td>';
                  
    //             }
    //             else{
    //                 $dataString .= '<td>View</td>';
    //             }
    //         $dataString .= '</tr>';
    //     }

    //     return $dataString;

    // }


}
