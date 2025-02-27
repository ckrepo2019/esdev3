<?php

namespace App;
use DB;
use Session;

use Illuminate\Database\Eloquent\Model;

class SPP_Announcement extends Model
{
   

    public static function storeAnnouncement($request){

        $validNotification = true;
        $returnBackMessage = back();

        if($request->get('title') == null){

            $validNotification = false;
            $returnBackMessage->with('title',(object)['title'=>'Title is empty']);

        }
        else{

            $returnBackMessage->with('validtitle',(object)['validtitle'=>$request->get('title')]);

        }

        if($request->get('content') == null){

            $validNotification = false;
            $returnBackMessage->with('content',(object)['content'=>'Content is empty']);

        }
        else{

            $returnBackMessage->with('validcontent',(object)['validcontent'=>$request->get('content')]);
        }

        if(!$request->has('teachers') && !$request->has('students') && !$request->has('G')){
       
            $validNotification = false;
            $returnBackMessage->with('reciever',(object)['reciever'=>'Please select atleast one reciever']);

        }

        if($validNotification){

            $announceID = DB::table('announcements')->insertGetId([
                'title'=>$request->get('title'),
                'content'=>$request->get('content'),
                'recievertype'=>'1',
                'announcementtype'=>$request->get('announcetype'),
                'createdby'=>auth()->user()->id
            ]);
        

            if($request->has('all')){

                $teachers = SPP_Teacher::getAllTeacher();

                foreach($request->get('G') as $item){

                    $sectionstudents = SPP_Student::getAllEnrolledStudentsByGradeLevel($item);

                    foreach($sectionstudents as $student){

                        DB::table('notifications')
                            ->insert([
                                'headerid'=>$announceID,
                                'recieverid'=>$student->userid,
                                'type'=>1,
                                'status'=>0
                            ]);

                    }
                }


                foreach($teachers as $teacher){

                    DB::table('notifications')->insert([
                        'headerid'=>$announceID,
                        'type'=>'1',
                        'recieverid'=>$teacher->userid,
                        'status'=>0
                    ]);
                }
              
            }

            else{

                if($request->has('G') ){


                    foreach($request->get('G') as $item){

                        $sectionstudents = SPP_Student::getAllEnrolledStudentsByGradeLevel($item);
    
                        foreach($sectionstudents as $student){
    
                            DB::table('notifications')
                                ->insert([
                                    'headerid'=>$announceID,
                                    'recieverid'=>$student->userid,
                                    'type'=>1,
                                    'status'=>0
                                ]);
    
                        }
                    }
                }
            
                if($request->has('teachers')){
                
                    $teachers = SPP_Teacher::getAllTeacher();

                    foreach($teachers as $teacher){

                        DB::table('notifications')->insert([
                            'headerid'=>$announceID,
                            'type'=>'1',
                            'recieverid'=>$teacher->userid,
                            'status'=>0
                        ]);

                    }
                }
            }
        }

        else{
            return $returnBackMessage;
        }

        return back();
    }

    public static function getAllAnnouncement($userid,$skip=1,$take=10,$string=''){

        return DB::table('announcements')
                    ->where('createdby',$userid)
                    ->where('announcements.title','like',$string.'%')
                    ->orWhere('announcements.content','like',$string.'%')
                    ->orderBy('created_at','desc')
                    ->skip(($skip-1)*10)
                    ->take($take)
                    ->get();

    }

    public static function getAllAnnouncementCount($userid){

        return DB::table('announcements')
                        ->where('createdby',$userid)
                        ->orderBy('created_at','desc')
                        ->count();

    }

    public static function getAnnouncementCountPageNumber($userid){

        $announcementCount = self::getAllAnnouncementCount($userid);

        $announcementPageCount = $announcementCount/10;

        if(round($announcementPageCount) < $announcementCount){

            $announcementPageCount = round($announcementPageCount)+1;

        }
        else{

            $announcementPageCount = round($announcementPageCount);
        }

        return $announcementPageCount;

    }

    public static function annoucementDatatable($userid, $pagenum, $searchString = ''){
        
        $dataString = '';

        $announcements =  SPP_Announcement::getAllAnnouncement($userid,$pagenum,10,$searchString);

        $announcementCount =  SPP_Announcement::getAnnouncementCountPageNumber($userid);

        $dataString .= '<table class="table border-bottom">
                <tr>
                    <td>Title</td>
                    <td>Content</td>
                    <td width="15%">Date Posted</td>
                </tr>';
            foreach($announcements as $item){
                $dataString .= 
                '<tr>
                    <td>'.$item->title.'</td>
                    <td>'.$item->content.'</td>
                    <td>'.\Carbon\Carbon::create($item->created_at)->isoFormat('MMM DD, YYYY').'</td>
                </tr>';
            }
        
        $dataString .= '</table>';
        
        $pagination = SPP_String::paginationString($announcementCount,'announcement',$pagenum);

        $dataString.=$pagination;

        return $dataString;

    }

}
