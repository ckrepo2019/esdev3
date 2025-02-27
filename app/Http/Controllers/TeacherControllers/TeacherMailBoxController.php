<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use \Carbon\Carbon;
use Crypt;

class TeacherMailBoxController extends Controller
{
    public function inbox($id)
    {

        $id = Crypt::decrypt($id);

        $announcements = DB::table('announcements')
            ->select('announcements.id','announcements.title','announcements.createdby','announcements.created_at','notifications.id as notificationid','notifications.status','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
            ->join('notifications','announcements.id','=','notifications.headerid')
            ->join('teacher','announcements.createdby','=','teacher.userid')
            ->whereIn('announcements.recievertype',['1','2'])
            ->where('notifications.recieverid',$id)
            ->where('notifications.deleted',0)
            ->get();
            
        foreach($announcements as $message){

            foreach($message as $date => $value){

                if($date == "created_at"){
                    
                    $message->created_at = Carbon::create($value)->isoFormat('dddd - MMM DD, YYYY h:m:s A');
                    
                }

            }

        }

        return view('teacher.mailbox_inbox')
            ->with('announcements',$announcements);

    }
    public function read($id, Request $request)
    {

        if($id == 'deletesent'){
            
            $getheader = DB::table('announcements')
                ->where('id',$request->get('messageid'))
                ->where('createdby',$request->get('teacheruserid'))
                ->get();
                
            DB::update('update notifications set deleted = ? where headerid = ? and recieverid = ?',[1,$getheader->id,auth()->user()->id]);
            return redirect()->action(
                'TeacherControllers\TeacherMailBoxController@inbox', ['id' => Crypt::encrypt(auth()->user()->id)]
            )->with("deleted", 'Selected messages has been deleted!');

        }else{
            
            $id = Crypt::decrypt($id);
            
            $message_id = $request->get('message_id');
            
            DB::update('update notifications set status = ? where id = ?',['1', $request->get('message_notificationid')]);
           
            $getMessage = DB::table('announcements')
                ->select('announcements.id','announcements.title','announcements.content','announcements.created_at','teacher.userid as teacheruserid','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                ->join('teacher','announcements.createdby','=','teacher.userid')
                ->where('announcements.id',$message_id)
                ->where('announcements.deleted','0')
                ->get();
                
            foreach($getMessage as $message){

                foreach($message as $date => $value){

                    if($date == "created_at"){

                        $message->created_at = Carbon::create($value)->isoFormat('D MMMM YYYY - hh:mm:ss A');
                        
                    }

                }

            }
            
            return view('teacher.mailbox_read')
                ->with('message', $getMessage[0])
                ->with('messageidentifier',$request->get('message'));

        }
        
    }
    public function compose($id, Request $request)
    {
        // return $id;
        if($id == "getreceiver"){
            
            $syid = DB::table('sy')
                ->where('isactive','1')
                ->get();

            $getTeacher = DB::table('teacher')
                ->where('id',$request->get('userid'))
                ->get();

            if($request->get('receivertype') == "Principal"){

                $getPrincipal = DB::table('teacher')
                    ->select('teacher.id','teacher.userid','teacher.firstname','teacher.middlename','teacher.lastname','usertype.utype')
                    ->join('usertype','teacher.usertypeid','=','usertype.id')
                    ->where('usertype.utype','PRINCIPAL')
                    ->where('teacher.deleted','0')
                    ->where('teacher.isactive','1')
                    ->distinct()
                    ->get();

                $principalArray = array();

                array_push($principalArray, 'Principal');

                array_push($principalArray, $getPrincipal);

                return $principalArray;

            }
            elseif($request->get('receivertype') == "Teacher"){

                $getTeacher = DB::table('teacher')
                    ->select('teacher.id','teacher.userid','teacher.firstname','teacher.middlename','teacher.lastname','usertype.utype')
                    ->join('usertype','teacher.usertypeid','=','usertype.id')
                    ->where('usertype.utype','TEACHER')
                    ->where('teacher.deleted','0')
                    ->where('teacher.isactive','1')
                    ->distinct()
                    ->get();
                    
                $teacherArray = array();

                array_push($teacherArray, 'Teacher');

                array_push($teacherArray, $getTeacher);

                return $teacherArray;

            } 
            elseif($request->get('receivertype') == "Section"){

                $teacher_info = Db::table('teacher')
                    ->where('teacher.userid',$request->get('userid'))
                    ->get();
                    
                $assignedSched = DB::table('assignsubjdetail')
                    ->select('gradelevel.id as glevelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
                    ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.id')
                    ->join('gradelevel','assignsubj.glevelid','=','gradelevel.id')
                    ->join('sections','assignsubj.sectionid','=','sections.id')
                    ->where('assignsubjdetail.teacherid',$teacher_info[0]->id)
                    ->where('assignsubjdetail.deleted','0')
                    ->where('assignsubj.deleted','0')
                    ->where('assignsubj.syid', $syid[0]->id)
                    ->distinct()
                    ->get();
                    
                $assignedSchedSenior = DB::table('sh_classsched')
                    ->select('gradelevel.id as glevelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
                    // ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.id')
                    ->join('gradelevel','sh_classsched.glevelid','=','gradelevel.id')
                    ->join('sections','sh_classsched.sectionid','=','sections.id')
                    ->where('sh_classsched.teacherid',$teacher_info[0]->id)
                    ->where('sh_classsched.deleted','0')
                    ->where('sh_classsched.syid', $syid[0]->id)
                    ->distinct()
                    ->get();
                    
                $assignedSchedBlock = DB::table('sh_blocksched')
                    ->select('gradelevel.id as glevelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
                    // ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.id')
                    ->join('gradelevel','sh_blocksched.levelid','=','gradelevel.id')
                    ->join('sections','sh_blocksched.id','=','sections.blockid')
                    ->where('sh_blocksched.teacherid',$teacher_info[0]->id)
                    ->where('sh_blocksched.deleted','0')
                    ->where('sh_blocksched.syid', $syid[0]->id)
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
                
                if(count($assignedSched) == 0){
                    
                    $assignedSched = DB::table('sections')
                        ->select('sections.id as sectionid','gradelevel.levelname','sections.sectionname')
                        ->join('gradelevel','sections.levelid','=','gradelevel.id')
                        ->where('sections.teacherid',$teacher_info[0]->id)
                        ->where('sections.deleted','0')
                        ->get();

                }
                
                $sectionArray = array();

                array_push($sectionArray, 'Section');

                array_push($sectionArray, $assignedSched);

                return $sectionArray;

            }
            elseif($request->get('receivertype') == "Student"){

                $teacher_info = Db::table('teacher')
                    ->where('teacher.userid',$request->get('userid'))
                    ->get();
                    
                $assignedSched = DB::table('assignsubjdetail')
                    ->select('gradelevel.id as glevelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
                    ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.id')
                    ->join('gradelevel','assignsubj.glevelid','=','gradelevel.id')
                    ->join('sections','assignsubj.sectionid','=','sections.id')
                    ->where('assignsubjdetail.teacherid',$teacher_info[0]->id)
                    ->where('assignsubjdetail.deleted','0')
                    ->where('assignsubj.deleted','0')
                    ->where('assignsubj.syid', $syid[0]->id)
                    ->distinct()
                    ->get();

                $assignedSchedSenior = DB::table('sh_classsched')
                    ->select('gradelevel.id as glevelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
                    // ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.id')
                    ->join('gradelevel','sh_classsched.glevelid','=','gradelevel.id')
                    ->join('sections','sh_classsched.sectionid','=','sections.id')
                    ->where('sh_classsched.teacherid',$teacher_info[0]->id)
                    ->where('sh_classsched.deleted','0')
                    ->where('sh_classsched.syid', $syid[0]->id)
                    ->distinct()
                    ->get();
                    
                $assignedSchedBlock = DB::table('sh_blocksched')
                    ->select('gradelevel.id as glevelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
                    // ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.id')
                    ->join('gradelevel','sh_blocksched.levelid','=','gradelevel.id')
                    ->join('sections','sh_blocksched.id','=','sections.blockid')
                    ->where('sh_blocksched.teacherid',$teacher_info[0]->id)
                    ->where('sh_blocksched.deleted','0')
                    ->where('sh_blocksched.syid', $syid[0]->id)
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
                if(count($assignedSched) == 0){

                    $assignedSched = DB::table('sections')
                        ->select('sections.id as sectionid','gradelevel.levelname','sections.sectionname','gradelevel.id as glevelid')
                        ->join('gradelevel','sections.levelid','=','gradelevel.id')
                        ->where('sections.teacherid',$teacher_info[0]->id)
                        ->where('sections.deleted','0')
                        ->get();

                }
                
                $studentArray = array();

                array_push($studentArray, 'Student');

                foreach($assignedSched as $student){

                    $acadprogname = DB::table('gradelevel')
                        ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                        ->where('gradelevel.id',$student->glevelid)
                        ->get();

                    if($acadprogname[0]->progname == "SENIOR HIGH SCHOOL"){

                        $studentinfo = DB::table('sh_enrolledstud')
                            ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                            ->where('sh_enrolledstud.sectionid',$student->sectionid)
                            ->where('sh_enrolledstud.syid', $syid[0]->id)
                            ->get();

                    }
                    else{

                        $studentinfo = DB::table('enrolledstud')
                            ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                            ->where('enrolledstud.sectionid',$student->sectionid)
                            ->where('enrolledstud.syid', $syid[0]->id)
                            ->get();

                    }

                    array_push($studentArray, $studentinfo);

                }

                return $studentArray;

            }

        }
        if($id == "send"){
            
            date_default_timezone_set('Asia/Manila');
            $sy = DB::table('sy')
                ->where('isactive','1')
                ->first();

            $title = $request->get('title');

            $content = $request->get('content');
            // return $request->get('receivertype');
            if($request->get('receivertype')=='Principal'){

                $checkIfExists = Db::table('announcements')
                    ->where('title', 'like', '%'.$title)
                    ->where('createdby', auth()->user()->id)
                    ->get();
                    
                if(count($checkIfExists)==0){

                    $headerid = DB::table('announcements')
                    ->insertGetId([
                        'title' => $title,
                        'content' => $content,
                        'recievertype' => '6',
                        'announcementtype' => '2',
                        'createdby' => auth()->user()->id,
                        'created_at'    => date('Y-m-d H:i:s'),
                        'createddatetime'    => date('Y-m-d H:i:s')
                    ]);

                    foreach($request->get('recipients') as $rec){
                        
                        $asdfsdf = DB::table('notifications')
                        ->insert([
                            'headerid' => $headerid,
                            'recieverid' => $rec,
                            'type' => 1,
                            'syid' => $sy->id,
                            'status' => 0
                        ]);
                                
                    }

                    return redirect('/mailbox/compose/'.auth()->user()->id.'')->with(['messageSuccess' => 'Announcement published successfully!']);

                }
                else{

                    return redirect('/mailbox/compose/'.auth()->user()->id.'')->with(['messageWarning' => 'Announcement already exists!']);

                }

            }
            elseif($request->get('receivertype')=='Teacher'){
                
                $checkIfExists = Db::table('announcements')
                    ->where('title', 'like', '%'.$title)
                    ->where('createdby', auth()->user()->id)
                    ->get();

                if(count($checkIfExists)==0){

                    $headerid = DB::table('announcements')
                    ->insertGetId([
                        'title' => $title,
                        'content' => $content,
                        'recievertype' => '2',
                        'announcementtype' => '2',
                        'createdby' => auth()->user()->id
                    ]);

                    foreach($request->get('recipients') as $rec){

                        $asdfsdf = DB::table('notifications')
                        ->insert([
                            'headerid' => $headerid,
                            'recieverid' => $rec,
                            'type' => 1,
                            'syid' => $sy->id,
                            'status' => 0
                        ]);

                    }

                    return redirect('/mailbox/compose/'.auth()->user()->id.'')->with(['messageSuccess' => 'Announcement published successfully!']);

                }
                else{

                    return redirect('/mailbox/compose/'.auth()->user()->id.'')->with(['messageWarning' => 'Announcement already exists!']);

                }

            }
            elseif($request->get('receivertype')=='Section'){
                
                $checkIfExists = Db::table('announcements')
                    ->where('title', 'like', '%'.$title)
                    ->where('createdby', auth()->user()->id)
                    ->get();

                if(count($checkIfExists)==0){

                    $headerid = DB::table('announcements')
                        ->insertGetId([
                            'title' => $title,
                            'content' => $content,
                            'recievertype' => '4',
                            'announcementtype' => '2',
                            'createdby' => auth()->user()->id
                        ]);

                    foreach($request->get('recipients') as $rec){
                        // return $request->get('recipients');
                        $sections = DB::table('sections')
                            ->join('gradelevel','sections.levelid','=','gradelevel.id')
                            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                            ->where('sections.deleted','0')
                            ->where('sections.id',$rec)
                            // ->where('sy.isactive','1')
                            ->distinct()
                            ->get();

                        if($sections[0]->progname == 'SENIOR HIGH SCHOOL'){

                            $students = DB::table('sh_enrolledstud')
                                ->select('studinfo.userid')
                                ->join('studinfo','sh_enrolledstud.studid','studinfo.id')
                                ->join('sy','sh_enrolledstud.syid','syid')
                                ->where('sh_enrolledstud.sectionid',$rec)
                                // ->where('sh_enrolledstud.levelid',$sections[0]->levelid)
                                ->where('sy.isactive','1')
                                ->where('sh_enrolledstud.deleted','0')
                                ->where('sh_enrolledstud.studstatus','!=','0')
                                ->distinct()
                                ->get();

                        }
                        else{
                            
                            $students = DB::table('enrolledstud')
                                ->select('studinfo.userid')
                                ->join('studinfo','enrolledstud.studid','studinfo.id')
                                ->join('sy','enrolledstud.syid','syid')
                                ->where('enrolledstud.sectionid',$rec)
                                // ->where('enrolledstud.levelid',$sections[0]->levelid)
                                ->where('sy.isactive','1')
                                ->where('enrolledstud.deleted','0')
                                ->where('enrolledstud.studstatus','!=','0')
                                ->distinct()
                                ->get();

                        }
                        // return $students;
                        foreach($students as $student){

                            $asdfsdf = DB::table('notifications')
                                ->insert([
                                    'headerid' => $headerid,
                                    'recieverid' => $student->userid,
                                    'type' => 1,
                                    'syid' => $sy->id,
                                    'status' => 0
                                ]);
                            // if($asdfsdf){
                            //     return 'yes';
                            // }else{
                            //     return 'no';
                            // }
                            
                        }
                                
                    }

                    return redirect('/mailbox/compose/'.auth()->user()->id.'')->with(['messageSuccess' => 'Announcement published successfully!']);

                }
                else{

                    return redirect('/mailbox/compose/'.auth()->user()->id.'')->with(['messageWarning' => 'Announcement already exists!']);

                }

            }
            elseif($request->get('receivertype')=='Student'){
                
                $checkIfExists = Db::table('announcements')
                    ->where('title', 'like', '%'.$title)
                    ->where('createdby', auth()->user()->id)
                    ->get();
                    
                if(count($checkIfExists)==0){

                    $headerid = DB::table('announcements')
                    ->insertGetId([
                        'title' => $title,
                        'content' => $content,
                        'recievertype' => '7',
                        'announcementtype' => '2',
                        'createdby' => auth()->user()->id
                    ]);
                    
                    foreach($request->get('recipients') as $rec){
                        
                        $asdfsdf = DB::table('notifications')
                        ->insert([
                            'headerid' => $headerid,
                            'recieverid' => $rec,
                            'type' => 1,
                            'syid' => $sy->id,
                            'status' => 0
                        ]);   
                        
                    }

                    return redirect('/mailbox/compose/'.auth()->user()->id.'')->with(['messageSuccess' => 'Announcement published successfully!']);

                }
                else{

                    return redirect('/mailbox/compose/'.auth()->user()->id.'')->with(['messageWarning' => 'Announcement already exists!']);

                }

            }

        }

        return view('teacher.mailbox_compose');

    }
    public function sent($id, Request $request)
    {
        
        $id = Crypt::decrypt($id);

        $teacher_info = Db::table('teacher')
            ->where('teacher.userid',$id)
            ->get();
            
        $announcements = DB::table('announcements')
            ->select('announcements.id','announcements.title','announcements.content','announcements.created_at','notifications.id as notificationid','notifications.status','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix','announcements.recievertype')
            ->join('notifications','announcements.id','=','notifications.headerid')
            ->join('teacher','notifications.recieverid','=','teacher.userid')
            ->where('announcements.createdby',$id)
            ->where('announcements.deleted',0)
            // ->where('announcements.recievertype','2')
            ->get();
            
        $students = DB::table('announcements')
                ->select('announcements.id','announcements.title','announcements.content','announcements.created_at','notifications.id as notificationid','notifications.status','studinfo.id as studentid','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix')
                ->join('notifications','announcements.id','=','notifications.headerid')
                ->join('studinfo','notifications.recieverid','=','studinfo.userid')
                ->where('announcements.createdby',$id)
                ->where('announcements.recievertype','7')
                ->where('announcements.deleted',0)
                ->distinct()
                // ->groupBy('announcements.id')
                ->get();
                
        $sections = DB::table('announcements')
                ->select('announcements.id','announcements.title','announcements.content','announcements.created_at','notifications.id as notificationid','notifications.status','sections.sectionname','gradelevel.levelname')
                ->join('notifications','announcements.id','=','notifications.headerid')
                ->join('studinfo','notifications.recieverid','=','studinfo.userid')
                ->join('sections','studinfo.sectionid','=','sections.id')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->where('announcements.createdby',$id)
                ->where('announcements.recievertype','4')
                ->where('announcements.deleted',0)
                ->distinct()
                ->groupBy('announcements.id','sections.id')
                ->orderBy('created_at','desc')
                ->get();
                
        $sentTitleArray = array();

        $recipientsArray = array();

        foreach($announcements as $sent){

            if($sent->recievertype == '2'){

                array_push($sentTitleArray, (object) array(
                    'id' => $sent->id,
                    'title' => $sent->title,
                    'content' => $sent->content,
                    'created_at' => Carbon::create($sent->created_at)->isoFormat('dddd - MMM DD, YYYY h:m:s A')
                ));

                array_push($recipientsArray, array(
                    'id' => $sent->id,
                    'role' => 'Teacher - ',
                    'firstname' => $sent->firstname,
                    'middlename' => $sent->middlename,
                    'lastname' => $sent->lastname,
                    'suffix' => $sent->suffix,
                    'notificationid' => $sent->notificationid
                ));

            }
            elseif($sent->recievertype == '6'){

                array_push($sentTitleArray, (object) array(
                    'id' => $sent->id,
                    'title' => $sent->title,
                    'content' => $sent->content,
                    'created_at' => Carbon::create($sent->created_at)->isoFormat('dddd - MMM DD, YYYY h:m:s A')
                ));

                array_push($recipientsArray, array(
                    'id' => $sent->id,
                    'role' => 'Principal - ',
                    'firstname' => $sent->firstname,
                    'middlename' => $sent->middlename,
                    'lastname' => $sent->lastname,
                    'suffix' => $sent->suffix,
                    'notificationid' => $sent->notificationid
                ));

            }

        }
        foreach($students as $sent){

            array_push($sentTitleArray, (object) array(
                'id' => $sent->id,
                'title' => $sent->title,
                'content' => $sent->content,
                'created_at' => Carbon::create($sent->created_at)->isoFormat('dddd - MMM DD, YYYY h:m:s A')
            ));

            array_push($recipientsArray, array(
                'id' => $sent->id,
                'role' => 'Student - ',
                'firstname' => $sent->firstname,
                'middlename' => $sent->middlename,
                'lastname' => $sent->lastname,
                'suffix' => $sent->suffix,
                'notificationid' => $sent->notificationid
            ));

        }
        foreach($sections as $sent){

            array_push($sentTitleArray, (object) array(
                'id' => $sent->id,
                'title' => $sent->title,
                'content' => $sent->content,
                'created_at' => Carbon::create($sent->created_at)->isoFormat('dddd - MMM DD, YYYY h:m:s A')
            ));

            array_push($recipientsArray, array(
                'id' => $sent->id,
                'role' => 'Section - ',
                'firstname' => $sent->levelname,
                'middlename' => ' - ',
                'lastname' => $sent->sectionname,
                'suffix' => ' ',
                'notificationid' => $sent->notificationid
            ));

        }

        $collectedTitle = collect($sentTitleArray)->flatten()->unique('id');
        
        $allData = array();

        foreach($collectedTitle as $title){

            $collectAll = array();

            foreach($recipientsArray as $recipient){

                if($recipient['id'] == $title->id){

                    array_push($collectAll,$recipient);

                }

            }
            
            array_push($allData, array(
                'message' => $title,
                'recipients' => $collectAll
            ));

        }
        
        return view('teacher.mailbox_sent')
            ->with('messages', $allData);
    }
    public function delete($id, Request $request)
    {
        
        if($id == 'read'){

            if($request->get('message')=='deleteinbox'){

                $getheader = DB::table('announcements')
                    ->where('id',$request->get('messageid'))
                    ->where('createdby',$request->get('teacheruserid'))
                    ->first();
                    
                DB::update('update notifications set deleted = ? where headerid = ? and recieverid = ?',[1,$getheader->id,auth()->user()->id]);
                return redirect()->action(
                    'TeacherControllers\TeacherMailBoxController@inbox', ['id' => Crypt::encrypt(auth()->user()->id)]
                )->with("deleted", 'Selected messages has been deleted!');

            }
            elseif($request->get('message')=='deletesent'){
                
                $getheader = DB::table('announcements')
                    ->where('id',$request->get('messageid'))
                    ->where('createdby',$request->get('teacheruserid'))
                    ->get();

                DB::update('update announcements set deleted = ? where id = ? and createdby = ?',[1,$request->get('messageid'),auth()->user()->id]);
                return redirect()->action(
                    'TeacherControllers\TeacherMailBoxController@sent', ['id' => Crypt::encrypt(auth()->user()->id)]
                )->with("deleted", 'Selected messages has been deleted!');

            }

        }
        elseif($id == 'sent'){

            foreach($request->get('messageids') as $messageid){

                DB::update('update announcements set deleted = ? where id = ? and createdby = ?',[1,$messageid,auth()->user()->id]);

            }

            return redirect()->back()->with("deleted", 'Selected messages has been deleted!');

        }

    }
    public function trash($id,Request $request)
    {
        
        if($id == 'recycle'){
            
            foreach($request->get('messageids') as $messageid){

                DB::update('update announcements set deleted = ? where id = ? and createdby = ?',[0,$messageid,auth()->user()->id]);

            }

            return redirect()->back()->with("recycled", 'Selected messages has been recycled!');
            
        }else{

            $id = Crypt::decrypt($id);

            $teacher_info = Db::table('teacher')
                ->where('teacher.userid',$id)
                ->get();
                
            $announcements = DB::table('announcements')
                ->select('announcements.id','announcements.title','announcements.content','announcements.created_at','notifications.id as notificationid','notifications.status','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix','announcements.recievertype')
                ->join('notifications','announcements.id','=','notifications.headerid')
                ->join('teacher','notifications.recieverid','=','teacher.userid')
                ->where('announcements.createdby',$id)
                ->where('announcements.deleted',1)
                // ->where('announcements.recievertype','2')
                ->get();
                
            $students = DB::table('announcements')
                    ->select('announcements.id','announcements.title','announcements.content','announcements.created_at','notifications.id as notificationid','notifications.status','studinfo.id as studentid','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix')
                    ->join('notifications','announcements.id','=','notifications.headerid')
                    ->join('studinfo','notifications.recieverid','=','studinfo.userid')
                    ->where('announcements.createdby',$id)
                    ->where('announcements.recievertype','7')
                    ->where('announcements.deleted',1)
                    ->distinct()
                    // ->groupBy('announcements.id')
                    ->get();
                    
            $sections = DB::table('announcements')
                    ->select('announcements.id','announcements.title','announcements.content','announcements.created_at','notifications.id as notificationid','notifications.status','sections.sectionname','gradelevel.levelname')
                    ->join('notifications','announcements.id','=','notifications.headerid')
                    ->join('studinfo','notifications.recieverid','=','studinfo.userid')
                    ->join('sections','studinfo.sectionid','=','sections.id')
                    ->join('gradelevel','sections.levelid','=','gradelevel.id')
                    ->where('announcements.createdby',$id)
                    ->where('announcements.recievertype','4')
                    ->where('announcements.deleted',1)
                    ->distinct()
                    ->groupBy('announcements.id','sections.id')
                    ->orderBy('created_at','desc')
                    ->get();
                    
            $sentTitleArray = array();

            $recipientsArray = array();

            foreach($announcements as $sent){

                if($sent->recievertype == '2'){

                    array_push($sentTitleArray, (object) array(
                        'id' => $sent->id,
                        'title' => $sent->title,
                        'content' => $sent->content,
                        'created_at' => Carbon::create($sent->created_at)->isoFormat('dddd - MMM DD, YYYY h:m:s A')
                    ));

                    array_push($recipientsArray, array(
                        'id' => $sent->id,
                        'role' => 'Teacher - ',
                        'firstname' => $sent->firstname,
                        'middlename' => $sent->middlename,
                        'lastname' => $sent->lastname,
                        'suffix' => $sent->suffix
                    ));

                }
                elseif($sent->recievertype == '6'){

                    array_push($sentTitleArray, (object) array(
                        'id' => $sent->id,
                        'title' => $sent->title,
                        'content' => $sent->content,
                        'created_at' => Carbon::create($sent->created_at)->isoFormat('dddd - MMM DD, YYYY h:m:s A')
                    ));

                    array_push($recipientsArray, array(
                        'id' => $sent->id,
                        'role' => 'Principal - ',
                        'firstname' => $sent->firstname,
                        'middlename' => $sent->middlename,
                        'lastname' => $sent->lastname,
                        'suffix' => $sent->suffix
                    ));

                }

            }

            foreach($students as $sent){

                array_push($sentTitleArray, (object) array(
                    'id' => $sent->id,
                    'title' => $sent->title,
                    'content' => $sent->content,
                    'created_at' => Carbon::create($sent->created_at)->isoFormat('dddd - MMM DD, YYYY h:m:s A')
                ));

                array_push($recipientsArray, array(
                    'id' => $sent->id,
                    'role' => 'Student - ',
                    'firstname' => $sent->firstname,
                    'middlename' => $sent->middlename,
                    'lastname' => $sent->lastname,
                    'suffix' => $sent->suffix
                ));

            }

            foreach($sections as $sent){

                array_push($sentTitleArray, (object) array(
                    'id' => $sent->id,
                    'title' => $sent->title,
                    'content' => $sent->content,
                    'created_at' => Carbon::create($sent->created_at)->isoFormat('dddd - MMM DD, YYYY h:m:s A')
                ));

                array_push($recipientsArray, array(
                    'id' => $sent->id,
                    'role' => 'Section - ',
                    'firstname' => $sent->levelname,
                    'middlename' => ' - ',
                    'lastname' => $sent->sectionname,
                    'suffix' => ' '
                ));

            }
            
            $collectedTitle = collect($sentTitleArray)->unique();

            $allData = array();

            foreach($collectedTitle as $title){

                $collectAll = array();

                foreach($recipientsArray as $recipient){

                    if($recipient['id'] == $title->id){

                        array_push($collectAll,$recipient);

                    }

                }
                
                array_push($allData, array(
                    'message' => $title,
                    'recipients' => $collectAll
                ));

            }
            
            return view('teacher.mailbox_trash')
                ->with('messages', $allData);

        }

    }
    public function print(Request $request)
    {
        
        $message = DB::table('announcements')
            ->select('announcements.id','announcements.title','announcements.content','announcements.created_at','teacher.userid as teacheruserid','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
            ->join('teacher','announcements.createdby','=','teacher.userid')
            ->where('announcements.id',Crypt::decrypt($request->get('messageid')))
            ->where('announcements.deleted','0')
            ->get();
            
        foreach($message as $msg){

            foreach($msg as $date => $value){

                if($date == "created_at"){

                    $msg->created_at = Carbon::create($value)->isoFormat('D MMMM YYYY - hh:mm:ss A');
                    
                }

                if($date == 'firstname'){

                    if($value == null){

                        $msg->firstname = "";

                    }

                }
                if($date == 'middlename'){

                    if($value == null){

                        $msg->middlename = "";

                    }

                }
                if($date == 'lastname'){

                    if($value == null){

                        $msg->lastname = "";

                    }

                }
                if($date == 'suffix'){

                    if($value == null){

                        $msg->suffix = "";

                    }

                }

            }

        }
        
        $pdf = PDF::loadview('teacher/pdf/mail',compact('message'))->setPaper('8.5x11','portrait');

        return $pdf->stream('Mail.pdf');
        
    }

}
