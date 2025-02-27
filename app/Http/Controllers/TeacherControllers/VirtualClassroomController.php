<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use Crypt;
use File;
use App\Models\Teacher\VirtualClassroomCodeGenerator;
use App\Models\Teacher\VirtualClassroomGetSections;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use App\Models\College\VCSetup;
use Session;


class VirtualClassroomController extends Controller
{
    public static function getpassword(Request $request)
    {
        return VirtualClassroomCodeGenerator::passwordgeneration();
    }
    public static function index(Request $request)
    {
        
    //  return auth()->user()->id;
        date_default_timezone_set('Asia/Manila');
        
        if(auth()->user()->type != '7' && auth()->user()->type != '9' && auth()->user()->type != '18' && Session::get('currentPortal') != 18)
        {
            $teacherid = Db::table('teacher')
                ->where('userid', auth()->user()->id)
                ->get();

            $sectionclassrooms = VirtualClassroomGetSections::sectionsunder($teacherid[0]->id);

            if(count($sectionclassrooms)>0)
            {
                foreach($sectionclassrooms as $sectionclassroom)
                {
                    $checkifexists = DB::table('virtualclassrooms')
                        ->where('sectionid', $sectionclassroom->sectionid)
                        ->where('subjectid', $sectionclassroom->subjectid)
                        ->where('userid', auth()->user()->id)
                        ->where('deleted','0')
                        ->get();
                        
                    if(count($checkifexists)==0)
                    {
                        $levelname = DB::table('sections')
                            ->select('gradelevel.levelname')
                            ->leftJoin('gradelevel', 'sections.levelid','=','gradelevel.id')
                            ->where('sections.id', $sectionclassroom->sectionid)
                            ->first()
                            ->levelname;
                        // return VirtualClassroomCodeGenerator::codegeneration();
                        Db::table('virtualclassrooms')
                            ->insert([
                                'userid'            => auth()->user()->id,
                                'classroomname'     => $levelname.' - '.$sectionclassroom->sectionname.' - '.$sectionclassroom->subjcode,
                                'sectionid'         => $sectionclassroom->sectionid,
                                'subjectid'         => $sectionclassroom->subjectid,
                                'code'              => VirtualClassroomCodeGenerator::codegeneration(),
                                'password'          => VirtualClassroomCodeGenerator::passwordgeneration(),
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                        
                    }
                }
            }
            // // return $schoolid;
            // ========================= add the sections above to classrooms
            $classrooms = DB::table('virtualclassrooms')
                ->where('userid',auth()->user()->id)
                ->where('deleted','0')
                ->get();
                
            if(count($classrooms)>0)
            {
                foreach($classrooms as $classroom)
                {
                    $classroom->createddatetime = date('F d, Y h:i:s A', strtotime($classroom->createddatetime));
                }
            }

            return view('teacher.virtualclassrooms.virtualclassroomindex')
                ->with('classrooms', $classrooms);
                
        }elseif(auth()->user()->type == '7'){
            
            $getmolinfo = DB::table('studinfo')
                ->where('userid', auth()->user()->id)
                ->first();

            if($getmolinfo->mol == '1')
            {
                $getclassrooms = Db::table('virtualclassroomstud')
                    ->select(
                        'virtualclassrooms.id',
                        'virtualclassrooms.classroomname',
                        'virtualclassrooms.code'
                    )
                    ->join('virtualclassrooms','virtualclassroomstud.classroomid','=','virtualclassrooms.id')
                    ->where('studid', auth()->user()->id)
                    ->where('virtualclassroomstud.deleted','0')
                    ->where('virtualclassrooms.deleted','0')
                    ->get();
            }else{
                DB::table('virtualclassroomstud')
                    ->where('studid', auth()->user()->id)
                    ->update([
                        'deleted' => 1
                    ]);
                $getclassrooms = array();
            }

            // return $getclassrooms;
            return view('studentPortal.pages.studentvcindex')
                ->with('classrooms', $getclassrooms);

        }elseif(auth()->user()->type == '18' || Session::get('currentPortal') == 18){
            VCSetup::sectionsunder();
            $classrooms = DB::table('virtualclassrooms')
                ->where('userid',auth()->user()->id)
                ->where('deleted','0')
                ->where('acadprogid','6')
                ->get();
            if(count($classrooms)>0)
            {
                foreach($classrooms as $classroom)
                {
                    $countstudents = DB::table('virtualclassroomstud')
                        ->where('classroomid', $classroom->id)
                        ->where('studid', '!=',null)
                        ->where('deleted','0')
                        ->count();
                        
                    $classroom->countstud = $countstudents;
                }
            }
            
            return view('ctportal.pages.vc.vcindex')
                ->with('classrooms', $classrooms);
        }
        // else{
        //     $classrooms = DB::table('virtualclassrooms')
        //         ->where('userid',auth()->user()->id)
        //         ->where('deleted','0')
        //         ->get();
                
        //     if(count($classrooms)>0)
        //     {
        //         foreach($classrooms as $classroom)
        //         {
        //             $classroom->createddatetime = date('F d, Y h:i:s A', strtotime($classroom->createddatetime));
        //         }
        //     }

        //     return view('teacher.virtualclassrooms.virtualclassroomindex')
        //         ->with('classrooms', $classrooms);
        // }
    }
    public static function checkname(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $checkclassifexists = DB::table('virtualclassrooms')
            ->where('classroomname','like','%'.$request->get('classroomname'))
            ->where('userid', auth()->user()->id)
            ->where('deleted', 0)
            ->get();
        
        if(count($checkclassifexists) > 0)
        {
            return '1';
        }else{
            $code = VirtualClassroomCodeGenerator::codegeneration();
            $getid = Db::table('virtualclassrooms')
                    ->insertGetId([
                        'userid'            => auth()->user()->id,
                        'classroomname'     => $request->get('classroomname'),
                        'code'              => $code,
                        'password'          => $code,
                        'type'              => '0',
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);

            $getcode = Db::table('virtualclassrooms')
                    ->where('id', $getid)
                    ->first();

            return collect($getcode);
            // return $request->get('classroomcode');
        }
    }
    public function visit(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $classroom = DB::table('virtualclassrooms')
            ->where('id', Crypt::decrypt($request->get('classroomid')))
            ->first();

        $assignments = DB::table('virtualclassroomattach')
            ->select(
                'id',
                'title',
                'instructions',
                'perfectscore',
                'filename',
                'filepath',
                'extension',
                'duefrom',
                'dueto',
                'createddatetime'
            )
            ->where('virtualclassroomattach.classroomid', Crypt::decrypt($request->get('classroomid')))
            ->where('virtualclassroomattach.deleted','0')
            ->where('virtualclassroomattach.type','1')
            ->get();
        
        if(auth()->user()->type != '7' && auth()->user()->type != '9')
        {
            if(count($assignments)>0)
            {
                foreach($assignments as $assignment)
                {
                    $turnedinassignments = DB::table('virtualclassroomass')
                        ->select(
                            'studinfo.userid',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.gender',
                            'studinfo.picurl',
                            'virtualclassroomass.id as turnedinid',
                            'virtualclassroomass.filepath',
                            'virtualclassroomass.extension',
                            'virtualclassroomass.score',
                            'virtualclassroomass.createddatetime'
                        )
                        ->join('studinfo', 'virtualclassroomass.createdby','=','studinfo.userid')
                        ->where('studinfo.mol','1')
                        ->where('virtualclassroomass.assignmentid', $assignment->id)
                        ->where('virtualclassroomass.deleted','0')
                        ->get();
                    
                    $assignment->createddatetime = date('F d, Y', strtotime($assignment->createddatetime));
                    $assignment->turnedin = $turnedinassignments;
                    $assignment->duefrom = date('m/d/Y h:i A', strtotime($assignment->duefrom));
                    $assignment->dueto = date('m/d/Y h:i A', strtotime($assignment->dueto));

                    if(count($turnedinassignments)>0)
                    {
                        foreach($turnedinassignments as $turnedinassignment)
                        {
                            $turnedinassignment->createddatetime = date('F d, Y h:i:s A', strtotime($turnedinassignment->createddatetime));
                        }
                    }
                    // $assignment->scores = DB::table('')

                    
                }
            }
            $teacherid = Db::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first()
                ->id;
    
            $students  = array();
            $studentstored = DB::table('virtualclassroomstud')
                    ->select(
                        'virtualclassroomstud.id',
                        'studinfo.id as studid',
                        'studinfo.userid',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'gender',
                        'sectionid',
                        'crossover',
                        'picurl'
                    )
                ->join('studinfo', 'virtualclassroomstud.studid','=','studinfo.userid')
                ->where('studinfo.mol','1')
                ->where('virtualclassroomstud.classroomid', $classroom->id)
                ->where('virtualclassroomstud.deleted','0')
                ->get();
            
            if(count($studentstored) > 0)
            {
                foreach($studentstored as $stud)
                {
                    array_push($students, $stud);
                }
            }  

            if(auth()->user()->type != '18')
            {
                $sectionsunder = VirtualClassroomGetSections::sectionsunder($teacherid);
                // return $sectionsunder;
                $sectionstudents = array();
                
                if(count($sectionsunder) == 0)
                {
                    $checkifexists = DB::table('virtualclassroomstud')
                        // ->where('studid', $studentunder->id)
                        ->where('deleted','0')
                        ->get();
                }
                else
                {
                    foreach($sectionsunder as $section)
                    {
                        $studentsunder = Db::table('studinfo')
                            ->select(
                                'id as studid',
                                'userid',
                                'lastname',
                                'firstname',
                                'middlename',
                                'suffix',
                                'gender',
                                'sectionid',
                                'picurl'
                            )
                            ->where('sectionid', $section->sectionid)
                            ->where('studinfo.mol','1')
                            ->get();
                        
                        if(count($studentsunder)>0)
                        {
                            
                            foreach($studentsunder as $studentunder)
                            {
                                $checkifexists = DB::table('virtualclassroomstud')
                                    ->where('virtualclassroomstud.classroomid', $classroom->id)
                                    ->where('virtualclassroomstud.studid', $studentunder->userid)
                                    ->where('virtualclassroomstud.deleted','0')
                                    ->get();
                                    
                                if($studentunder->sectionid == $classroom->sectionid)
                                {
                                    if(count($checkifexists)==0)
                                    {
                                        $getid = DB::table('virtualclassroomstud')
                                            ->insert([
                                                'classroomid'       =>  $classroom->id,
                                                'studid'            =>  $studentunder->userid,
                                                'createddatetime'   =>  date('Y-m-d H:i:s')
                                            ]);
                                        $studentunder->id = $getid;
                                    }else{
                                        $studentunder->id = $checkifexists[0]->id;
                                    }
                                    $studentunder->crossover = 0;
                                    array_push($students, $studentunder);
                                }else{
                                    array_push($sectionstudents, $studentunder);
                                }
                            }
                        }
                    }
                }
            
                $advisorystudents = Db::table('sectiondetail')
                    ->where('teacherid', $teacherid)
                    ->get();
    
                if(count($advisorystudents)>0)
                {
                    foreach($advisorystudents as $advisorystudent)
                    {
                        $studentsadvisory = DB::table('studinfo')
                            ->select(
                                'id as studid',
                                'userid',
                                'lastname',
                                'firstname',
                                'middlename',
                                'suffix',
                                'gender',
                                'sectionid',
                                'picurl'
                            )
                            ->where('sectionid', $advisorystudent->sectionid)
                            ->where('studinfo.mol','1')
                            ->where('studstatus','!=','0')
                            ->get();
                            
                        if(count($studentsadvisory) > 0)
                        {
                            foreach($studentsadvisory as $studentadvisory)
                            {
                                // $studentadvisory->crossover = 0;
                                array_push($sectionstudents, $studentadvisory);
                                // $students = collect($students)->push($studentadvisory)->all();
    
                            }
                        }
                    }
                }

            }
    
            
            if(count($students)>0)
            {
                foreach($students as $student)
                {
                    if($student->middlename != null)
                    {
                        $student->middlename = $student->middlename[0].'.';
                    }
                }
            }
            $students = collect($students)->unique();
            $studentattachments = DB::table('virtualclassroomattach')
                ->select(
                    'studinfo.id',
                    'studinfo.userid',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'studinfo.picurl'
                    // 'virtualclassroomattach.id as attachmentid',
                    // 'virtualclassroomattach.filename',
                    // 'virtualclassroomattach.filepath',
                    // 'virtualclassroomattach.extension',
                    // 'virtualclassroomattach.createddatetime'
                )
                ->join('studinfo','virtualclassroomattach.userid', 'studinfo.userid')
                ->where('studinfo.mol','1')
                ->where('virtualclassroomattach.deleted','0')
                ->where('classroomid',Crypt::decrypt($request->get('classroomid')))
                ->where('virtualclassroomattach.type','0')
                ->groupBy('userid')
                ->get();
            
    
            if(count($studentattachments)>0)
            {
                foreach($studentattachments as $studentattachment)
                {
    
                    if($studentattachment->middlename != null)
                    {
                        $studentattachment->middlename = $studentattachment->middlename[0].'.';
                    }
                    $attachments = DB::table('virtualclassroomattach')
                        ->where('deleted','0')
                        ->where('userid',$studentattachment->userid)
                        ->get();
    
                    if(count($attachments)>0)
                    {
                        foreach($attachments as $attachment)
                        {
                            $attachment->createddatetime = date('F d, Y h:i:s A', strtotime($attachment->createddatetime));
                        }
                    }
    
                    $studentattachment->attachments = $attachments;
                }
            }
            $classattachments = Db::table('virtualclassroomattach')
                ->where('classroomid', Crypt::decrypt($request->get('classroomid')))
                ->where('userid', auth()->user()->id)
                ->where('virtualclassroomattach.deleted','0')
                ->where('virtualclassroomattach.type','0')
                ->get();
            if(auth()->user()->type == '18')
            {
                return view('ctportal.pages.vc.vcview')
                    ->with('classassignments',$assignments)
                    ->with('classroom',$classroom)
                    ->with('students',collect($students)->unique())
                    ->with('classattachments',$classattachments)
                    ->with('studentattachments',$studentattachments);
            }else{
                return view('teacher.virtualclassrooms.virtualclassroomview')
                    ->with('classassignments',$assignments)
                    ->with('classroom',$classroom)
                    ->with('students',collect($students)->unique())
                    ->with('sectionstudents',collect($sectionstudents)->unique())
                    ->with('classattachments',$classattachments)
                    ->with('studentattachments',$studentattachments);
            }
        }else{
            // return $assignments;
            $duestartedassignments = array();
            if(count($assignments)>0)
            {
                foreach($assignments as $assignment)
                {
                    $submittedassignment = DB::table('virtualclassroomass')
                        ->where('virtualclassroomass.assignmentid', $assignment->id)
                        ->where('virtualclassroomass.createdby', auth()->user()->id)
                        ->where('virtualclassroomass.deleted','0')
                        ->get();
                    if(count($submittedassignment)>0)
                    {
                        if($submittedassignment[0]->score == null){
                            $show = 1; 
                        }else{
                            $show = 0;
                        }
                        $submittedassignment[0]->show = $show;
                    }
                    if($assignment->duefrom<=date('Y-m-d H:i:s'))
                    {
                        array_push($duestartedassignments, $assignment);
                    }

                    if($assignment->dueto<=date('Y-m-d H:i:s'))
                    {
                        $assignment->status = 1;
                    }else{
                        $assignment->status = 0;
                    }
                    $assignment->createddatetime = date('F d, Y', strtotime($assignment->createddatetime));
                    $assignment->turnedin = $submittedassignment;
                    $assignment->duefrom = date('m/d/Y h:i A', strtotime($assignment->duefrom));
                    $assignment->dueto = date('m/d/Y h:i A', strtotime($assignment->dueto));
                }
            }
            
            $classattachments = Db::table('virtualclassroomattach')
                ->where('classroomid', Crypt::decrypt($request->get('classroomid')))
                ->where('virtualclassroomattach.deleted','0')
                ->where('virtualclassroomattach.type','0')
                ->where('virtualclassroomattach.createtype','0')
                ->get();
                
            $studentattachments = DB::table('virtualclassroomattach')
                ->select(
                    'studinfo.id',
                    'studinfo.userid',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'studinfo.picurl',
                    'virtualclassroomattach.id as attachmentid',
                    'virtualclassroomattach.filename',
                    'virtualclassroomattach.filepath',
                    'virtualclassroomattach.extension',
                    'virtualclassroomattach.createddatetime'
                )
                ->join('studinfo','virtualclassroomattach.userid', 'studinfo.userid')
                ->where('studinfo.mol','1')
                ->where('virtualclassroomattach.deleted','0')
                ->where('virtualclassroomattach.userid',auth()->user()->id)
                ->where('classroomid',Crypt::decrypt($request->get('classroomid')))
                ->where('virtualclassroomattach.type','0')
                // ->groupBy('userid')
                ->get();
                
            if(count($studentattachments)>0)
            {
                foreach($studentattachments as $studentattachment)
                {

                    if($studentattachment->middlename != null)
                    {
                        $studentattachment->middlename = $studentattachment->middlename[0].'.';
                    }
                    $studentattachment->createddatetime = date('F d, Y h:i:s A', strtotime($studentattachment->createddatetime));

                }
            }
            return view('studentPortal.pages.studentvcview')
                ->with('classroom',$classroom)
                ->with('classassignments',$duestartedassignments)
                ->with('classattachments',$classattachments)
                ->with('myattachments',$studentattachments);
        }
        
    }
    public function addfiles($id, Request $request)
    {   
        date_default_timezone_set('Asia/Manila');

        $classroom = DB::table('virtualclassrooms')
            ->where('id', $request->get('classroomid'))
            ->first();
            
        if(auth()->user()->type == '1')
        {
            $localfolder = 'Classrooms/classroom'.$request->get('classroomid').'/'.'Attachments';

            $createtype = 0;

        }else{

            $localfolder = 'Classrooms/classroom'.$request->get('classroomid').'/'.'Student Attachments'.'/'.auth()->user()->email;
            $createtype = 1;

        }

        if($request->has('files')){
            $countfiles = count(array_filter($request->file('files')));
            // return count(array_filter($request->file('files')));
            if($countfiles > 0){
    
                foreach($request->file('files') as $file){
    
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();

                    if (! File::exists(public_path().$localfolder)) {
    
                        $path = public_path($localfolder);
            
                        if(!File::isDirectory($path)){
                            
                            File::makeDirectory($path, 0777, true, true);
            
                        }
                        
                    }
                    
                    $urlFolder = str_replace('http://','',$request->root());
                        
                    if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder)) {

                        $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
                        
                        if(!File::isDirectory($cloudpath)){

                            File::makeDirectory($cloudpath, 0777, true, true);
                            
                        }
                        
                    }
                    

                    $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
                    
                    // try{

                    //     $file->move($clouddestinationPath, $file->getClientOriginalName());
                        
                    // }
                    // catch(\Exception $e){
                    
                
                    // }
                                // return basename($request->get('content'));
                    // $file = $request->file($file);
                    // return $extension;
                    // $filename = 
                    // $clouddestinationPath = dirname(base_path(), 1).$localfolder.'/';
            
            
                    try{
            
                        $file->move($clouddestinationPath, $file->getClientOriginalName());
                        $renamedfile = rename($clouddestinationPath.'/'.$file->getClientOriginalName(), $clouddestinationPath.'/'.auth()->user()->email.'-'.$file->getClientOriginalName());
            
                    }
                    catch(\Exception $e){
                       
                
                    }
                        // return basename($request->get('content'));
                    $destinationPath = public_path($localfolder.'/');
                        // return $filename;
                    
                    try{
            
                        $file->move($destinationPath,$file->getClientOriginalName());
                        $renamedfile = rename($destinationPath.$file->getClientOriginalName(), $destinationPath.auth()->user()->email.'-'.$file->getClientOriginalName());

                        // return collect($destinationPath.auth()->user()->email.'-'.$file->getClientOriginalName());
            
                    }
                    catch(\Exception $e){
                       
                
                    }
                    DB::table('virtualclassroomattach')
                        ->insert([
                            'classroomid'       => $request->get('classroomid'),
                            'userid'            => auth()->user()->id,
                            'filename'          => auth()->user()->email.'-'.$filename,
                            'filepath'          => $localfolder.'/'.auth()->user()->email.'-'.$file->getClientOriginalName(),
                            'createddatetime'   => date('Y-m-d H:i:s'),
                            'extension'         => $extension,
                            'createtype'        => $createtype
                        ]);
                }
    
            }
        }
        return back();
    }
    public function deleteattachment(Request $request)
    {   
        DB::table('virtualclassroomattach')
            ->where('id', Crypt::decrypt($request->get('attachmentid')))
            ->update([
                'deleted' => '1'
            ]);

        return 'success';
    }
    public function createassignment(Request $request)
    {   
        date_default_timezone_set('Asia/Manila');

        $datedue = explode(' - ', $request->get('duedatetime'));
        $duedatetimefrom = $datedue[0];
        $duedatetimeto = $datedue[1];
        $localfolder = 'Classrooms/classroom'.$request->get('classroomid').'/'.'Assignments';
        $createtype = 0;
        if($request->has('assignmentfile')){
            $file = $request->file('assignmentfile');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            if (! File::exists(public_path().$localfolder)) {
                $path = public_path($localfolder);
                if(!File::isDirectory($path)){
                    File::makeDirectory($path, 0777, true, true);
                }
            }
            
            $urlFolder = str_replace('http://','',$request->root());
                
            if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder)) {
                $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
                if(!File::isDirectory($cloudpath)){
                    File::makeDirectory($cloudpath, 0777, true, true);
                }
            }

            $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;

            try{
                $file->move($clouddestinationPath, $file->getClientOriginalName());
                $renamedfile = rename($clouddestinationPath.'/'.$file->getClientOriginalName(), $clouddestinationPath.'/'.auth()->user()->email.'-'.$file->getClientOriginalName());
            }
            catch(\Exception $e){
        
            }
            
            $destinationPath = public_path($localfolder.'/');
            
            try{
                $file->move($destinationPath,$file->getClientOriginalName());
                $renamedfile = rename($destinationPath.$file->getClientOriginalName(), $destinationPath.auth()->user()->email.'-'.$file->getClientOriginalName());
            }
            catch(\Exception $e){
        
            }

            DB::table('virtualclassroomattach')
                ->insert([
                    'classroomid'       => $request->get('classroomid'),
                    'title'             => $request->get('assignmenttitle'),
                    'instructions'      => $request->get('assignmentinstruction'),
                    'perfectscore'      =>$request->get('perfectscore'),
                    'userid'            => auth()->user()->id,
                    'filename'          => auth()->user()->email.'-'.$filename,
                    'filepath'          => $localfolder.'/'.auth()->user()->email.'-'.$file->getClientOriginalName(),
                    'createddatetime'   => date('Y-m-d H:i:s'),
                    'extension'         => $extension,
                    'duefrom'           => date('Y-m-d H:i:s', strtotime($duedatetimefrom)),
                    'dueto'             => date('Y-m-d H:i:s', strtotime($duedatetimeto)),
                    'type'              => '1',
                    'createtype'        => $createtype
                ]);
        }
        else{
            DB::table('virtualclassroomattach')
                ->insert([
                    'classroomid'       => $request->get('classroomid'),
                    'title'             => $request->get('assignmenttitle'),
                    'instructions'      => $request->get('assignmentinstruction'),
                    'perfectscore'      =>$request->get('perfectscore'),
                    'userid'            => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s'),
                    'duefrom'           => date('Y-m-d H:i:s', strtotime($duedatetimefrom)),
                    'dueto'             => date('Y-m-d H:i:s', strtotime($duedatetimeto)),
                    'type'              => '1',
                    'createtype'        => $createtype
                ]);

        }
        return back();
    }
    public function editclassassignment(Request $request)
    {  
        // return $request->all();
        $datedue = explode(' - ', $request->get('duedatetime'));
        $duedatetimefrom = $datedue[0];
        $duedatetimeto = $datedue[1];
        DB::table('virtualclassroomattach')
            ->where('id', $request->get('assignmentid'))
            ->update([
                'title'         =>  $request->get('assignmenttitle'),
                'instructions'  =>  $request->get('assignmentinstruction'),
                'perfectscore'  =>  $request->get('perfectscore'),
                'duefrom'       => date('Y-m-d H:i:s', strtotime($duedatetimefrom)),
                'dueto'         => date('Y-m-d H:i:s', strtotime($duedatetimeto)),
            ]);

        return back();
        
    }
    public function deleteassignment(Request $request)
    {  
        // return $request->all();
        DB::table('virtualclassroomattach')
            ->where('id', $request->get('assignmentid'))
            ->update([
                'deleted'         =>  '1'
            ]);

        return back();
    }
    public function addstudent(Request $request)
    {
        // return $request->all();
        date_default_timezone_set('Asia/Manila');
        foreach($request->get('studids') as $userid)
        {
            $checkifexists = DB::table('virtualclassroomstud')
                ->where('classroomid', $request->get('classroomid'))
                ->where('studid', $userid)
                ->where('deleted','0')
                ->get();

            if(count($checkifexists)==0)
            {
                Db::table('virtualclassroomstud')
                    ->insert([
                        'classroomid'       => $request->get('classroomid'),
                        'studid'            => $userid,
                        'crossover'         => 1,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }

        return 'success';
    }
    public function deletestudent(Request $request)
    {   
        DB::table('virtualclassroomstud')
            ->where('id', Crypt::decrypt($request->get('studid')))
            ->update([
                'deleted' => '1'
            ]);

        return 'success';
    }
    public function submitassignment(Request $request)
    {   
        // return $request->all();



        $localfolder = 'Classrooms/classroom'.$request->get('classroomid').'/'.'Assignments'.'/'.'Turned In'.'/'.auth()->user()->email;

        $createtype = 1;

        $file = $request->file('submitfile');

        $filename = $file->getClientOriginalName();

        $extension = $file->getClientOriginalExtension();

        if (! File::exists(public_path().$localfolder)) {

            $path = public_path($localfolder);

            if(!File::isDirectory($path)){
                
                File::makeDirectory($path, 0777, true, true);

            }
            
        }
        
        $urlFolder = str_replace('http://','',$request->root());
            
        if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder)) {

            $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
            
            if(!File::isDirectory($cloudpath)){

                File::makeDirectory($cloudpath, 0777, true, true);
                
            }
            
        }

        $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;

        try{

            $file->move($clouddestinationPath, $file->getClientOriginalName());

            $renamedfile = rename($clouddestinationPath.'/'.$file->getClientOriginalName(), $clouddestinationPath.'/'.auth()->user()->email.'-'.$file->getClientOriginalName());

        }
        catch(\Exception $e){

        }
        
        $destinationPath = public_path($localfolder.'/');
        
        try{

            $file->move($destinationPath,$file->getClientOriginalName());

            $renamedfile = rename($destinationPath.$file->getClientOriginalName(), $destinationPath.auth()->user()->email.'-'.$file->getClientOriginalName());

        }
        catch(\Exception $e){

        }

        DB::table('virtualclassroomass')
            ->insert([
                'assignmentid'      => $request->get('assignmentid'),
                'filepath'          => $localfolder.'/'.auth()->user()->email.'-'.$file->getClientOriginalName(),
                'createddatetime'   => date('Y-m-d H:i:s'),
                'extension'         => $extension,
                'createdby'         => auth()->user()->id,
                'createtype'        => $createtype
            ]);

        return back();
    }
    public function deleteturnedin(Request $request)
    {
        // return $request->all();
        DB::table('virtualclassroomass')
            ->where('id', $request->get('turnedinid'))
            ->update([
                'deleted' => '1'
            ]);
        
        return back();
    }
    public function score($action,Request $request)
    {
        // return $request->all();
        DB::table('virtualclassroomass')
            ->where('id', $request->get('turnedinid'))
            ->update([
                'score' => $request->get('score')
            ]);
        
        return back();
    }
    public function view($id){
        
        $classroom = DB::table('virtualclassrooms')
            ->where('id', $id)
            ->first();
           
        $checkifexists  =  Bigbluebutton::getMeetingInfo([
            'meetingID' => $classroom->code,
            'moderatorPW' => 'essentielmoderator'.$classroom->code //moderator password set here
        ]); 
        // return $checkifexists;
        // return \Bigbluebutton::all();
        if(count($checkifexists) == 0)
        {
            \Bigbluebutton::create([
                'meetingID' => $classroom->code,
                'meetingName' => $classroom->classroomname,
                'attendeePW' => $classroom->password,
                'moderatorPW' => 'essentielmoderator'.$classroom->code,
                'defaultWelcomeMessage'                     => 'Good day!',
                'defaultWelcomeMessageFooter'       => 'CK Children\'s Publishing',
                'logoutUrl' => explode('/',url()->full())[2].'/virtualclassrooms/closevirtualclassroom'
            ]); 
        }
        // return $checkifexists;
        if(auth()->user()->type != '7' && auth()->user()->type != '9')
        {
            $url = \Bigbluebutton::start([
                'meetingID' => $classroom->code,
                'moderatorPW' => 'essentielmoderator'.$classroom->code, //moderator password set here
                'attendeePW' => $classroom->password, //attendee password here
                'userName' => auth()->user()->name,//for join meeting 
                'defaultWelcomeMessage'                     => 'Good day!',
                'defaultWelcomeMessageFooter'       => 'CK Children\'s Publishing'
                //'redirect' => false // only want to create and meeting and get join url then use this parameter 
            ]);
            
            return redirect()->to($url);

        }elseif(auth()->user()->type == '7'){
            
            $checkifonline = Bigbluebutton::isMeetingRunning([
                'meetingID' => $classroom->code,
            ]);
            
            if($checkifonline == true)
            {
                $url = Bigbluebutton::join([
                    'meetingID' => $classroom->code,
                    'userName' => auth()->user()->name,
                    'password' => $classroom->password, //which user role want to join set password here
                    'defaultWelcomeMessage'                     => 'Good day!',
                    'defaultWelcomeMessageFooter'       => 'CK Children\'s Publishing'
                ]);
                    // return $url;
                return redirect()->to($url);
                
            }else{

                return view('teacher.virtualclassrooms.virtualclassroominactive');

            }
        }
        
        // return Redirect::to('https://meet.jit.si/'.$classroom->code);
        // return view('jitsimeet')
        //     ->with('classroom',$classroom);
    }
    public function call(Request $request){
        // return $request->all();
        // return view('jitsimeet')
        //     ->with('classroom',$classroom);
    }
    public function getstudents(Request $request)
    {
        
        $students = DB::table('virtualclassroomstud')
            ->select(
                'studinfo.id',
                'studinfo.userid',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.picurl'
            )
            ->join('studinfo','virtualclassroomstud.studid','=','studinfo.userid')
            ->where('classroomid', $request->get('classroomid'))
            ->where('virtualclassroomstud.deleted','0')
            ->where('studinfo.deleted','0')
            ->where('studinfo.mol','1')
            ->get();

        if(count($students)>0)
        {
            foreach($students as $student)
            {
                if(strtolower($student->gender) == 'male')
                {
                    $student->alt = 'avatar/S(M) 3.png';
                }
                elseif(strtolower($student->gender) == 'female')
                {
                    $student->alt = 'avatar/S(F) 1.png';
                }else{
                    $student->alt = 'assets/images/avatars/unknown.png';
                }
            }
        }
        return view('ctportal.pages.vc.ajaxblades.getstudents')
            ->with('students',$students);
    }
    public function getassignments(Request $request)
    {
        $assignments = DB::table('virtualclassroomattach')
            ->select(
                'id',
                'title',
                'instructions',
                'perfectscore',
                'filename',
                'filepath',
                'extension',
                'duefrom',
                'dueto',
                'createddatetime'
            )
            ->where('virtualclassroomattach.classroomid', $request->get('classroomid'))
            ->where('virtualclassroomattach.deleted','0')
            ->where('virtualclassroomattach.type','1')
            ->get();

        if(count($assignments)>0)
        {
            foreach($assignments as $assignment)
            {
                $turnedinassignments = DB::table('virtualclassroomass')
                    ->select(
                        'studinfo.userid',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.picurl',
                        'virtualclassroomass.id as turnedinid',
                        'virtualclassroomass.filepath',
                        'virtualclassroomass.extension',
                        'virtualclassroomass.score',
                        'virtualclassroomass.createddatetime'
                    )
                    ->join('studinfo', 'virtualclassroomass.createdby','=','studinfo.userid')
                    ->where('studinfo.mol','1')
                    ->where('virtualclassroomass.assignmentid', $assignment->id)
                    ->where('virtualclassroomass.deleted','0')
                    ->get();
                
                $assignment->createddatetime = date('F d, Y', strtotime($assignment->createddatetime));
                $assignment->turnedin = $turnedinassignments;
                $assignment->duefrom = date('m/d/Y h:i A', strtotime($assignment->duefrom));
                $assignment->dueto = date('m/d/Y h:i A', strtotime($assignment->dueto));

                if(count($turnedinassignments)>0)
                {
                    foreach($turnedinassignments as $turnedinassignment)
                    {
                        $turnedinassignment->createddatetime = date('F d, Y h:i:s A', strtotime($turnedinassignment->createddatetime));
                    }
                }
                // $assignment->scores = DB::table('')

                
            }
        }
        
        return view('ctportal.pages.vc.ajaxblades.getassignments')
            ->with('assignments', $assignments)
            ->with('classroomid', $request->get('classroomid'));
    }
    public function getassignmentinfo(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $assignmentinfo = DB::table('virtualclassroomattach')
            ->where('id', $request->get('assignmentid'))
            ->first();

        $assignmentinfo->duefrom = date('m/d/Y h:m:s A',strtotime($assignmentinfo->duefrom));
        $assignmentinfo->dueto = date('m/d/Y h:m:s A',strtotime($assignmentinfo->dueto));
        return collect($assignmentinfo);
    }
    
}
