<?php

namespace App\Models\Principal;
use DB;
use Session;
use App\Models\Principal\SPP_AcademicProg;
use App\Models\Principal\SPP_EnrolledStudent;
use \Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class SPP_Announcement extends Model
{
   

    public static function storeAnnouncement($request){

        $validNotification = true;
        $returnBackMessage = back();

        // if($request->get('title') == null){

        //     $validNotification = false;
        //     $returnBackMessage->with('title',(object)['title'=>'Title is empty']);

        // }
        // else{

        //     $returnBackMessage->with('validtitle',(object)['validtitle'=>$request->get('title')]);

        // }

        // if($request->get('content') == null){

        //     $validNotification = false;
        //     $returnBackMessage->with('content',(object)['content'=>'Content is empty']);

        // }
        // else{

        //     $returnBackMessage->with('validcontent',(object)['validcontent'=>$request->get('content')]);
        // }

        if(!$request->has('teachers') && !$request->has('students') && !$request->has('G') && !$request->has('parents')){
       
            $validNotification = false;
            $returnBackMessage->with('reciever',(object)['reciever'=>'Please select atleast one reciever']);

        }

        if($validNotification){

          

                $announceID = DB::table('announcements')->insertGetId([
                    'title'=>$request->get('title'),
                    'content'=>$request->get('content'),
                    'recievertype'=>'1',
                    'announcementtype'=>$request->get('announcetype'),
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>Carbon::now('Asia/Manila')
                ]);

                if($request->has('all')){

                    return "sfsdf";

                    $teachers = SPP_Teacher::getAllTeacher();

                    foreach($request->get('G') as $item){

                        $sectionstudents = SPP_EnrolledStudent::getStudent(null,null,null,null,null,null,null,$item);


                  
                        
                        foreach($sectionstudents[0]->data as $student){

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

                        if($request->get('announcetype')=='2'){

                            DB::table('smsbunker')->insert([
                                'message'=>strip_tags($request->get('content')),
                                'receiver'=>$teacher->phonenumber,
                                'smsstatus'=>'0'
                            ]);
        
                        }
                    }
                
                }

                else{

                    if($request->has('G') ){

                

                        foreach($request->get('G') as $item){


                            $sectionstudents = SPP_EnrolledStudent::getStudent(null,null,null,null,null,null,null,$item);

                           

                            if($sectionstudents[0]->count == 0){

                                $sectionstudents = SPP_EnrolledStudent::getStudent(null,null,null,null,5,null,null,$item);

                            }

                            foreach($sectionstudents[0]->data as $student){
        
                                DB::table('notifications')
                                    ->insert([
                                        'headerid'=>$announceID,
                                        'recieverid'=>$student->userid,
                                        'type'=>1,
                                        'status'=>0
                                    ]);

                                if($request->get('announcetype')=='2'){

                                    DB::table('smsbunker')->insert([
                                        'message'=>strip_tags($request->get('content')),
                                        'receiver'=>$student->contactno,
                                        'smsstatus'=>'0'
                                    ]);
                
                                }
        
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

                            if($request->get('announcetype')=='2'){

                                DB::table('smsbunker')->insert([
                                    'message'=>strip_tags($request->get('content')),
                                    'receiver'=>$teacher->phonenumber,
                                    'smsstatus'=>'0'
                                ]);
            
                            }

                        }
                    }

                    if($request->has('parents')){
                    
                        $acadprog = SPP_AcademicProg::getPrincipalAcadProg(Session::get('prinInfo')->id);

                        // return $acadprog;

                        foreach($acadprog as $item){

                            $students =  SPP_EnrolledStudent::getStudent(null,null,null,null,$item->id,null,null,null,null,null,null,'basic');

                       
                            if($students[0]->count > 0){

                                $sid =  collect($students[0]->data)->map(function($value){
                                        return 'P'.$value->sid;
                                });

                               

                                foreach($students[0]->data as $parent){

                                    $parents = DB::table('users')
                                                ->where('type','9')
                                                ->where('email', 'P'.$parent->sid)
                                                ->select('id')
                                                ->first();
                                    if(isset($parents->id)){

                                        DB::table('notifications')->insert([
                                            'headerid'=>$announceID,
                                            'type'=>'1',
                                            'recieverid'=>$parents->id,
                                            'status'=>0
                                        ]);
                                    }
        
                                    if($request->get('announcetype')=='2'){

                                        if($parent->isfathernum == 1){
                                            $contactnum = $parent->fcontactno;
                                        }
                                        elseif($parent->ismothernum == 1){
                                            $contactnum = $parent->mcontactno;
                                        }
                                        else{
                                            $contactnum = $parent->gcontactno;
                                        }
                                       

                                        DB::table('smsbunker')->insert([
                                            'message'=>strip_tags($request->get('content')),
                                            'receiver'=>$contactnum,
                                            'smsstatus'=>'0'
                                        ]);
                    
                                    }
        
                                }



                            }   
                        }

                    }



                }
                
          
      
          
        }

        else{

      
            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withInput();

        }


        toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
        return back();
    }


    public static function getNotification(
        $take=null,
        $skip=null,
        $id=null,
        $notid = null
    ){

        $query = DB::table('notifications');

        if($id!=null){

            $query->where('recieverid',$id);

        }

        $notifications = $query->distinct()->get();

        return $notifications;

    }

    public static function getAnnouncement(
        $skip=null,
        $take=null,
        $announcementID=null,
        $announcementInfo = null
    ){
        
        $query = DB::table('announcements');
        $teacherInfo = DB::table('teacher')->where('userid',auth()->user()->id)->first();

        if($announcementInfo != null){

            $query->where(function($query) use($announcementInfo){
                $query->where('announcements.title','like',$announcementInfo.'%');
            });

        }

        $query->where('createdby',$teacherInfo->userid);

        $count = $query->count();

        if($take!=null){

            $query->take($take);

        }

        if($skip!=null){

            $query->skip(($skip-1)*$take);
        }

        $data = array();

        $announcements =  $query->get();

        array_push($data,(object)['data'=>$announcements, 'count'=>$count]);

        return $data;   

    }

    public static function smstexter(
        $students = null,
        $message = null
    ){

        foreach($students as $student){

            if($student->isfathernum == 1){
                $contactnum = $student->fcontactno;
            }
            elseif($student->ismothernum == 1){
                $contactnum = $student->mcontactno;
            }
            else{
                $contactnum = $student->gcontactno;
            }
            

            DB::table('smsbunker')->insert([
                'message'=>$message,
                'receiver'=>$contactnum,
                'smsstatus'=>'0'
            ]);


        }

    }


}
