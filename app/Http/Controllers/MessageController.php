<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Teacher\VirtualClassroomGetSections;
class MessageController extends Controller
{
    public function index()
    {
        
        $recipients = array();

        if(auth()->user()->type == 1){

// TEACHER   

            $extends = 'teacher.layouts.app';
            
            $createdby = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first();

            $sectionclassrooms = VirtualClassroomGetSections::sectionsunder($createdby->id);

            foreach($sectionclassrooms as $section)
            {
                $studentsunder = Db::table('studinfo')
                    ->select(
                        'id',
                        'userid',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'gender',
                        'picurl'
                    )
                    ->where('sectionid', $section->sectionid)
                    ->get();

                if(count($studentsunder)>0)
                {
                    foreach($studentsunder as $student)
                    {
                        if(strtolower($student->gender) == 'female')
                        {
    
                            $student->avatar = 'avatar/S(F) 1.png';
    
                        }else{
                            
                            $student->avatar = 'avatar/S(M) 3.png';
                        }
    
                        if($student->picurl == null){
                            $student->picurl = $student->avatar;
                        }
    
                        $student->usertypeid = '7';
    
                        array_push($recipients, $student);
                    }
                }
            }

        }elseif(auth()->user()->type == 7){

// STUDENT

            $extends = 'student.layouts.app';

            $createdby = DB::table('students')
                ->where('userid', auth()->user()->id)
                ->first();

            $section = Db::table('assignsubj')
                ->join('assignsubjdetail','assignsubj.id','=','assignsubjdetail.headerid')
                ->join('sy','assignsubj.syid','=','sy.id')
                ->where('sy.isactive','1')
                ->get();
            // return $section;
            $teachers = DB::table('classrooms')
                ->select(
                    'teachers.id',
                    'teachers.firstname',
                    'teachers.middlename',
                    'teachers.lastname',
                    'teachers.suffix',
                    'teachers.gender',
                    'teachers.gender',
                    'teachers.picurl'
                )
                ->join('classroomstudents','classrooms.id','=','classroomstudents.classroomid')
                ->join('teachers','classrooms.createdby','=','teachers.id')
                ->where('classroomstudents.studentid',$studentid)
                ->where('classroomstudents.deleted','0')
                ->where('classrooms.deleted','0')
                ->distinct()
                ->get();
                
            if(count($teachers)>0)
            {
                foreach($teachers as $teacher)
                {
                    if(strtolower($teacher->gender) == 'female')
                    {

                        $teacher->avatar = 'avatar/T(F) 3.png';

                    }else{
                        
                        $teacher->avatar = 'avatar/T(M) 2.png';
                    }

                    if($teacher->picurl == null){
                        $teacher->picurl = $teacher->avatar;
                    }

                    $teacher->usertypeid = '2';

                    array_push($recipients, $teacher);
                }
            }
            $classrooms = Db::table('classrooms')
                ->select(
                    'classrooms.id',
                    'classrooms.classroomname',
                    'classrooms.createddatetime',
                    'teachers.firstname',
                    'teachers.middlename',
                    'teachers.lastname',
                    'teachers.suffix'
                )
                ->join('teachers','classrooms.createdby','=','teachers.id')
                ->where('classrooms.deleted','0')
                ->distinct()
                ->get();

            if(count($classrooms) > 0){
                foreach($classrooms as $classroom){
                    $classroom->createddatetime = date('F d, Y h:i:s A', strtotime($classroom->createddatetime));
                    $classroom->students = Db::table('classroomstudents')
                        ->where('classroomid', $classroom->id)
                        ->where('deleted','0')
                        ->count();

                    $joined = Db::table('classroomstudents')
                        ->where('classroomid', $classroom->id)
                        ->where('classroomstudents.studentid',$studentid)
                        ->where('deleted','0')
                        ->get();
                    
                    if(count($joined) == 0){
                        $classroom->joined = 0;
                    }else{
                        $classroom->joined = 1;
                        $classroom->datejoined = date('F d, Y', strtotime($joined[0]->createddatetime));
                    }
                    $classroom->books = Db::table('classroombooks')
                        ->where('classroomid', $classroom->id)
                        ->where('deleted','0')
                        ->count();
                }
            }
        }

        
        $messagesrecipients = DB::table('messages')
                ->where('createdby', '=' , auth()->user()->id)
                    ->orWhere('recipientid', '=', auth()->user()->id)
            ->orderBy('createddatetime','asc')
            ->get();
        // return
        // );
        // return $messagesrecipients;
        // if(count($messagesrecipients)>0){


        //     foreach($messagesrecipients as $messagesrecipient)
        //     {
        //         if($messagesrecipient->usertypeid == '2'){
        //             if(auth()->user()->type == '2')
        //             {
        //                 $recipientname = Db::table('teachers')
        //                     ->where('id', $messagesrecipient->createdby)
        //                     ->first();

        //                 if(strtolower($recipientname->gender) == 'female')
        //                 {
        
        //                     $avatar = 'avatar/teacher-female.png';
        
        //                 }else{
                            
        //                     $avatar = 'avatar/teacher-male.png';
        //                 }
        
        //                 $messagesrecipient->accountid = $recipientname->id;
        //                 $messagesrecipient->firstname = $recipientname->firstname;
        //                 $messagesrecipient->middlename = $recipientname->middlename;
        //                 $messagesrecipient->lastname = $recipientname->lastname;
        //                 $messagesrecipient->suffix = $recipientname->suffix;
        //                 $messagesrecipient->gender = $recipientname->gender;
        //                 $messagesrecipient->picurl = $recipientname->picurl;
        //                 $messagesrecipient->avatar = $avatar;

        //                 if($recipientname->picurl == null){
        //                     $recipientname->picurl = $avatar;
        //                     $messagesrecipient->picurl = $avatar;
        //                 }

        //                 if($messagesrecipient->createdby == $createdby->id){
        //                     // array_push($recipients, (object)array(
        //                     //     'id'            => $recipientname->id,
        //                     //     'firstname'     => $recipientname->firstname,
        //                     //     'middlename'    => $recipientname->middlename,
        //                     //     'lastname'      => $recipientname->lastname,
        //                     //     'suffix'        => $recipientname->suffix,
        //                     //     'gender'        => $recipientname->gender,
        //                     //     'picurl'        => $recipientname->picurl,
        //                     //     'avatar'        => $avatar,
        //                     //     'usertypeid'    => 2,
        //                     //     'mine'          => 1
        //                     // ));
                            
        //                     $messagesrecipient->mine = '1';

        //                 }else{
        //                     array_push($recipients, (object)array(
        //                         'id'            => $recipientname->id,
        //                         'firstname'     => $recipientname->firstname,
        //                         'middlename'    => $recipientname->middlename,
        //                         'lastname'      => $recipientname->lastname,
        //                         'suffix'        => $recipientname->suffix,
        //                         'gender'        => $recipientname->gender,
        //                         'picurl'        => $recipientname->picurl,
        //                         'avatar'        => $avatar,
        //                         'usertypeid'    => 2
        //                     ));
        //                     $messagesrecipient->mine = '0';
        //                 }

        //             }else{
        //                 $recipientname = Db::table('teachers')
        //                     ->where('id', $messagesrecipient->createdby)
        //                     ->first();

        //                     if(strtolower($recipientname->gender) == 'female')
        //                     {
            
        //                         $avatar = 'avatar/teacher-female.png';
            
        //                     }else{
                                
        //                         $avatar = 'avatar/teacher-male.png';
        //                     }
            
        
        //                     $messagesrecipient->accountid = $recipientname->id;
        //                     $messagesrecipient->firstname = $recipientname->firstname;
        //                     $messagesrecipient->middlename = $recipientname->middlename;
        //                     $messagesrecipient->lastname = $recipientname->lastname;
        //                     $messagesrecipient->suffix = $recipientname->suffix;
        //                     $messagesrecipient->gender = $recipientname->gender;
        //                     $messagesrecipient->picurl = $recipientname->picurl;
        //                     $messagesrecipient->avatar = $avatar;
        //                     if($recipientname->picurl == null){
        //                         $recipientname->picurl = $avatar;
        //                         $messagesrecipient->picurl = $avatar;
        //                     }
        //                 array_push($recipients, (object)array(
        //                     'id'            => $recipientname->id,
        //                     'firstname'     => $recipientname->firstname,
        //                     'middlename'    => $recipientname->middlename,
        //                     'lastname'      => $recipientname->lastname,
        //                     'suffix'        => $recipientname->suffix,
        //                     'gender'        => $recipientname->gender,
        //                     'picurl'        => $recipientname->picurl,
        //                     'avatar'        => $avatar,
        //                     'usertypeid'    => 2
        //                 ));
        //                 $messagesrecipient->mine = '0';
        //             }

        //         }elseif($messagesrecipient->usertypeid == '3'){
        //             if(auth()->user()->type == '2')
        //             {
        //                 $recipientname = Db::table('students')
        //                     ->where('id', $messagesrecipient->createdby)
        //                     ->first();

        //                 if(strtolower($recipientname->gender) == 'female')
        //                 {
        
        //                     $avatar = 'avatar/S(F) 1.png';
        
        //                 }else{
                            
        //                     $avatar = 'avatar/S(M) 1.png';
        //                 }
        
        //                 $messagesrecipient->accountid = $recipientname->id;
        //                 $messagesrecipient->firstname = $recipientname->firstname;
        //                 $messagesrecipient->middlename = $recipientname->middlename;
        //                 $messagesrecipient->lastname = $recipientname->lastname;
        //                 $messagesrecipient->suffix = $recipientname->suffix;
        //                 $messagesrecipient->gender = $recipientname->gender;
        //                 $messagesrecipient->picurl = $recipientname->picurl;
        //                 $messagesrecipient->avatar = $avatar;
        //                 if($recipientname->picurl == null){
        //                     $recipientname->picurl = $avatar;
        //                     $messagesrecipient->picurl = $avatar;
        //                 }
        //                 array_push($recipients, (object)array(
        //                     'id'            => $recipientname->id,
        //                     'firstname'     => $recipientname->firstname,
        //                     'middlename'    => $recipientname->middlename,
        //                     'lastname'      => $recipientname->lastname,
        //                     'suffix'        => $recipientname->suffix,
        //                     'gender'        => $recipientname->gender,
        //                     'picurl'        => $recipientname->picurl,
        //                     'avatar'        => $avatar,
        //                     'usertypeid'    => 3
        //                 ));
        //                 $messagesrecipient->mine = '0';

        //             }else{
        //                 $recipientname = Db::table('students')
        //                     ->where('id', $messagesrecipient->createdby)
        //                     ->first();

        //                     if(strtolower($recipientname->gender) == 'female')
        //                     {
            
        //                         $avatar = 'avatar/S(F) 1.png';
            
        //                     }else{
                                
        //                         $avatar = 'avatar/S(M) 1.png';
        //                     }
            
        
        //                     $messagesrecipient->accountid = $recipientname->id;
        //                     $messagesrecipient->firstname = $recipientname->firstname;
        //                     $messagesrecipient->middlename = $recipientname->middlename;
        //                     $messagesrecipient->lastname = $recipientname->lastname;
        //                     $messagesrecipient->suffix = $recipientname->suffix;
        //                     $messagesrecipient->gender = $recipientname->gender;
        //                     $messagesrecipient->picurl = $recipientname->picurl;
        //                     $messagesrecipient->avatar = $avatar;
        //                     if($recipientname->picurl == null){
        //                         $recipientname->picurl = $avatar;
        //                         $messagesrecipient->picurl = $avatar;
        //                     }
        //                 if($messagesrecipient->createdby == $createdby->id){
        //                     // array_push($recipients, (object)array(
        //                     //     'id'            => $recipientname->id,
        //                     //     'firstname'     => $recipientname->firstname,
        //                     //     'middlename'    => $recipientname->middlename,
        //                     //     'lastname'      => $recipientname->lastname,
        //                     //     'suffix'        => $recipientname->suffix,
        //                     //     'gender'        => $recipientname->gender,
        //                     //     'picurl'        => $recipientname->picurl,
        //                     //     'avatar'        => $avatar,
        //                     //     'usertypeid'    => 2,
        //                     //     'mine'          => 1
        //                     // ));
        //                     $messagesrecipient->mine = '1';
        //                 }else{
        //                     array_push($recipients, (object)array(
        //                         'id'            => $recipientname->id,
        //                         'firstname'     => $recipientname->firstname,
        //                         'middlename'    => $recipientname->middlename,
        //                         'lastname'      => $recipientname->lastname,
        //                         'suffix'        => $recipientname->suffix,
        //                         'gender'        => $recipientname->gender,
        //                         'picurl'        => $recipientname->picurl,
        //                         'avatar'        => $avatar,
        //                         'usertypeid'    => 2
        //                     ));
        //                     $messagesrecipient->mine = '0';
        //                 }
        //             }
        //         }
        //     }
        // }
        // return $recipients;
        
        // if(auth()->user()->type == 2){
            return view('messages.messages')
                ->with('createdby',$createdby)
                ->with('extends', $extends)
                ->with('messages', collect($messagesrecipients)->unique())
                ->with('recipients', collect($recipients)->unique());
        // }elseif(auth()->user()->type == 3){
        //     return view('global.messages')
        //         ->with('createdby',$createdby)
        //         ->with('classrooms', $classrooms)
        //         ->with('extends', $extends)
        //         ->with('messages', collect($messagesrecipients)->unique())
        //         ->with('recipients', collect($recipients)->unique());
        // }
    }
    public function sendmessage(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');
        
        // if(auth()->user()->type == '2'){
        //     $createdby = DB::table('teachers')
        //         ->where('userid', auth()->user()->id)
        //         ->first()
        //         ->id;
        // }elseif(auth()->user()->type == '3'){
        //     $createdby = DB::table('students')
        //         ->where('userid', auth()->user()->id)
        //         ->first()
        //         ->id;
        // }

        $messageid = DB::table('messages')
            ->insertGetId([
                'content'           => $request->get('content'),
                'recipientid'       => $request->get('recipientid'),
                'createdby'         => auth()->user()->id,
                'createddatetime'   => date('Y-m-d H:i:s')
            ]);

        
        // recipientid
        // usertypeid
        // content

    }
    public function loadmessages(Request $request){

        // return $request->all();

        // if(auth()->user()->type == 1){
            
        //     $createdby = DB::table('teacher')
        //         ->where('userid', auth()->user()->id)
        //         ->first();

        // }else if(auth()->user()->type == 7){

        //     $createdby = DB::table('studinfo')
        //         ->where('userid', auth()->user()->id)
        //         ->first();
        // }

    
        $messagesrecipients = DB::table('messages')
                    ->where(function ($query) use ($request) {
                        $query->where('createdby', '=' ,auth()->user()->id)
                              ->where('recipientid', '=', $request->get('userid'));
                    })
                    ->orWhere(function ($query) use ($request) {
                        $query->where('createdby', '=' , $request->get('userid'))
                              ->where('recipientid', '=', auth()->user()->id);
                    })
                    ->orderBy('createddatetime','asc')
                    ->get();

        // return $messagesrecipients;

        if(count($messagesrecipients)>0){


            foreach($messagesrecipients as $messagesrecipient)
            {
                // if($messagesrecipient->usertypeid == '1'){
                    if(auth()->user()->type == '1')
                    {
                        $recipientname = Db::table('teacher')
                            ->join('employee_personalinfo','teacher.id','employee_personalinfo.employeeid')
                            ->where('userid', $messagesrecipient->createdby)
                            ->first();

                        if(strtolower($recipientname->gender) == 'female')
                        {
        
                            $avatar = 'avatar/T(F) 3.png';
        
                        }else{
                            
                            $avatar = 'avatar/T(M) 2.png';
                        }
        
                        $messagesrecipient->accountid = $recipientname->id;
                        $messagesrecipient->firstname = $recipientname->firstname;
                        $messagesrecipient->middlename = $recipientname->middlename;
                        $messagesrecipient->lastname = $recipientname->lastname;
                        $messagesrecipient->suffix = $recipientname->suffix;
                        $messagesrecipient->gender = $recipientname->gender;
                        $messagesrecipient->picurl = $recipientname->picurl;
                        $messagesrecipient->avatar = $avatar;

                        if($recipientname->picurl == null){
                            $recipientname->picurl = $avatar;
                            $messagesrecipient->picurl = $avatar;
                        }

                        if($messagesrecipient->createdby == auth()->user()->id){
                            // array_push($recipients, (object)array(
                            //     'id'            => $recipientname->id,
                            //     'firstname'     => $recipientname->firstname,
                            //     'middlename'    => $recipientname->middlename,
                            //     'lastname'      => $recipientname->lastname,
                            //     'suffix'        => $recipientname->suffix,
                            //     'gender'        => $recipientname->gender,
                            //     'picurl'        => $recipientname->picurl,
                            //     'avatar'        => $avatar,
                            //     'usertypeid'    => 2,
                            //     'mine'          => 1
                            // ));
                            
                            $messagesrecipient->mine = '1';

                        }else{
                       
                            $messagesrecipient->mine = '0';
                        }

                    }else{
                        $recipientname = Db::table('teacher')
                            ->where('id', $messagesrecipient->createdby)
                            ->first();

                            if(strtolower($recipientname->gender) == 'female')
                            {
            
                                $avatar = 'avatar/T(F) 3.png';
            
                            }else{
                                
                                $avatar = 'avatar/T(M) 2.png';
                            }
            
        
                            $messagesrecipient->accountid = $recipientname->id;
                            $messagesrecipient->firstname = $recipientname->firstname;
                            $messagesrecipient->middlename = $recipientname->middlename;
                            $messagesrecipient->lastname = $recipientname->lastname;
                            $messagesrecipient->suffix = $recipientname->suffix;
                            $messagesrecipient->gender = $recipientname->gender;
                            $messagesrecipient->picurl = $recipientname->picurl;
                            $messagesrecipient->avatar = $avatar;
                            if($recipientname->picurl == null){
                                $recipientname->picurl = $avatar;
                                $messagesrecipient->picurl = $avatar;
                            }
                      
                        $messagesrecipient->mine = '0';
                    }

                // }elseif($messagesrecipient->usertypeid == '7'){
                //     if(auth()->user()->type == '1')
                //     {
                //         $recipientname = Db::table('students')
                //             ->where('id', $messagesrecipient->createdby)
                //             ->first();

                //         if(strtolower($recipientname->gender) == 'female')
                //         {
        
                //             $avatar = 'avatar/S(F) 1.png';
        
                //         }else{
                            
                //             $avatar = 'avatar/S(M) 1.png';
                //         }
        
                //         $messagesrecipient->accountid = $recipientname->id;
                //         $messagesrecipient->firstname = $recipientname->firstname;
                //         $messagesrecipient->middlename = $recipientname->middlename;
                //         $messagesrecipient->lastname = $recipientname->lastname;
                //         $messagesrecipient->suffix = $recipientname->suffix;
                //         $messagesrecipient->gender = $recipientname->gender;
                //         $messagesrecipient->picurl = $recipientname->picurl;
                //         $messagesrecipient->avatar = $avatar;
                //         if($recipientname->picurl == null){
                //             $recipientname->picurl = $avatar;
                //             $messagesrecipient->picurl = $avatar;
                //         }
                       
                //         $messagesrecipient->mine = '0';

                //     }else{
                //         $recipientname = Db::table('students')
                //             ->where('id', $messagesrecipient->createdby)
                //             ->first();

                //             if(strtolower($recipientname->gender) == 'female')
                //             {
            
                //                 $avatar = 'avatar/S(F) 1.png';
            
                //             }else{
                                
                //                 $avatar = 'avatar/S(M) 1.png';
                //             }
            
        
                //             $messagesrecipient->accountid = $recipientname->id;
                //             $messagesrecipient->firstname = $recipientname->firstname;
                //             $messagesrecipient->middlename = $recipientname->middlename;
                //             $messagesrecipient->lastname = $recipientname->lastname;
                //             $messagesrecipient->suffix = $recipientname->suffix;
                //             $messagesrecipient->gender = $recipientname->gender;
                //             $messagesrecipient->picurl = $recipientname->picurl;
                //             $messagesrecipient->avatar = $avatar;
                //             if($recipientname->picurl == null){
                //                 $recipientname->picurl = $avatar;
                //                 $messagesrecipient->picurl = $avatar;
                //             }
                //         if($messagesrecipient->createdby == auth()->user()->id){
                //             // array_push($recipients, (object)array(
                //             //     'id'            => $recipientname->id,
                //             //     'firstname'     => $recipientname->firstname,
                //             //     'middlename'    => $recipientname->middlename,
                //             //     'lastname'      => $recipientname->lastname,
                //             //     'suffix'        => $recipientname->suffix,
                //             //     'gender'        => $recipientname->gender,
                //             //     'picurl'        => $recipientname->picurl,
                //             //     'avatar'        => $avatar,
                //             //     'usertypeid'    => 2,
                //             //     'mine'          => 1
                //             // ));
                //             $messagesrecipient->mine = '1';
                //         }else{
                           
                //             $messagesrecipient->mine = '0';
                //         }
                //     }
                // }
            }
        }

        // return collect($messagesrecipients)->unique();
        // resources\views\global\messagecontainer.blade.php

        return view('messages.messagecontainer')->with('messages', collect($messagesrecipients)->unique());

    }
}
