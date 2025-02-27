<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;
use App\AttendanceBySubject;
use \Carbon\Carbon;
class BeadleAttendanceController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $sem = DB::table('semester')
            ->where('isactive','1')
            ->get();

        $mutable = date('Y-m-d');
        $tdate =date('Y-m-d');
        $headerIDArray  = array();
        $sy = DB::table('sy')
            ->where('isactive','1')
            ->get();
            
        $headerIdsLower = DB::table('assignsubj')
            ->select('assignsubj.id')
            ->join('assignsubjdetail','assignsubj.id','=','assignsubjdetail.headerid')
            ->join('teacher','assignsubjdetail.teacherid','=','teacher.id')
            ->where('teacher.userid',auth()->user()->id)
            ->where('assignsubj.syid',$sy[0]->id)
            ->where('assignsubj.deleted','0')
            ->where('assignsubjdetail.deleted','0')
            ->distinct()
            ->get();
            
        foreach ($headerIdsLower as $headerId){
            array_push($headerIDArray, (object)array(
                'progname' => 'lower',
                'id' => $headerId->id
            ));
        }
        
        $headerIdsHigher = DB::table('sh_classsched')
            ->select('sh_classsched.id')
            ->join('sh_classscheddetail','sh_classsched.id','=','sh_classscheddetail.headerid')
            ->join('teacher','sh_classsched.teacherid','=','teacher.id')
            ->where('teacher.userid',auth()->user()->id)
            ->where('sh_classsched.syid',$sy[0]->id)
            ->where('sh_classsched.semid',$sem[0]->id)
            ->distinct()
            ->get();
        
        if(count($headerIdsHigher)>0){
            foreach ($headerIdsHigher as $headerId){
                array_push($headerIDArray,(object)array(
                    'progname' => 'higher',
                    'id' => $headerId->id
                ));
            }
        }
        $teacherid = DB::table('teacher')->where('userid', auth()->user()->id)->first()->id;
        $block = DB::table('sh_blocksched')
            // ->select('sh_blocksched.*')
            ->select('gradelevel.id as glevelid','gradelevel.levelname')
            ->join('sh_blockscheddetail','sh_blocksched.id','=','sh_blockscheddetail.headerid')
            ->join('sh_subjects','sh_blocksched.subjid','=','sh_subjects.id')
            ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
            ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
            ->leftJoin('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('days','sh_blockscheddetail.day','=','days.id')
            ->join('rooms','sh_blockscheddetail.roomid','=','rooms.id')
            ->join('sy','sh_blocksched.syid','=','sy.id')
            ->where('sh_blocksched.teacherid',$teacherid)
            ->where('sh_blocksched.deleted','0')
            ->where('sh_blockscheddetail.deleted','0')
            ->where('sh_sectionblockassignment.deleted','0')
            // ->where('gradelevel.deleted','0')
            ->where('sections.deleted','0')
            ->where('sy.isactive','1')
            ->distinct()
            ->get();
            
        $grade_level_id_array = array();
        foreach($headerIDArray as $header){
            if($header->progname == 'lower'){
                $get_grade_level_id_lower = DB::table('assignsubj')
                    ->select('glevelid')
                    ->where('id',$header->id)
                    ->get();
                if(count($get_grade_level_id_lower)>0){
                    array_push($grade_level_id_array,$get_grade_level_id_lower[0]);
                }
            }
            if($header->progname == 'higher'){
                $get_grade_level_id_higher = DB::table('sh_classsched')
                    ->select('glevelid')
                    ->where('id',$header->id)
                    ->get();
                if(count($get_grade_level_id_higher)>0){
                    foreach($get_grade_level_id_higher as $higher)
                    {
                        array_push($grade_level_id_array,$higher);
                    }
                }
            }
        }
        // return $grade_level_id_array;
        if(count($block)>0){
            foreach($block as $blockeach)
            {
                array_push($grade_level_id_array,$blockeach);
            }
        }
        $grade_level_ids = collect($grade_level_id_array);
        $levels = $grade_level_ids->unique();
        $final_grade_level_data = array();
        foreach($levels as $grade_level_id){
            $get_grade_level = DB::table('gradelevel')
                ->select('id','levelname','sortid')
                ->where('id',$grade_level_id->glevelid)
                ->orderBy('sortid','asc')
                ->get();
            if(count($get_grade_level)==0){
                array_push($final_grade_level_data,0);
            }
            else{
                array_push($final_grade_level_data,$get_grade_level[0]);
            }
        }

        if(!$request->has('version'))
        {
            // return count($final_grade_level_data);
            if(count($final_grade_level_data)=='0' || $final_grade_level_data[0]=='0'){
                // return 'sdfds';
                return view('teacher.attendance')
                ->with('date',$tdate)
                ->with('schoolyear',$sy)
                ->with('message','Attendance is not available!');
            }
            else{
                // return $final_grade_level_data;
                return view('teacher.attendance')
                    ->with('gradelevel',collect($final_grade_level_data)->unique()->sortBy('sortid')->values())
                    ->with('schoolyear',$sy)
                    ->with('date',$tdate);
            }
        }else{

            $schoolyears = DB::table('sy')
                ->get();
            $semesters = DB::table('semester')
                ->get();

            return view('teacher.classattendance.bysubject.index_v3')
                ->with('gradelevel',collect($final_grade_level_data)->unique()->sortBy('sortid')->values())
                ->with('schoolyear',$sy)
                ->with('date',$tdate)
                ->with('schoolyears',$schoolyears)
                ->with('semesters',$semesters);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        if(!$request->has('version'))
        {
            date_default_timezone_set('Asia/Manila');
            $attendance = array_keys($request->except('gradelevel'));
            
    
            $attendanceIndexStudId = 5;
            $attendanceIndexDate = 4;
            $attendanceIndexRemarks = 6;
    
            foreach($attendance as $data){
                if($attendanceIndexStudId<count($attendance)){
                    if($attendanceIndexStudId>=3){
                        $student_id = $attendance[$attendanceIndexStudId];
    
                        
                        $section_id = $request->get('section');
                        $subject_id = $request->get('subject');
                        $sy = $request->get('sy');
                        $date = $request->get('currentDate');
                        $day = substr($date, -2);
                        $status = $request->get($student_id);
                        // return $status;
                        $remarks = $request->get($attendance[$attendanceIndexRemarks]);
                        // return $remarks;
                        $checkifexists = Db::table('studentsubjectattendance')
                            ->where('date',$date)
                            ->where('student_id',$student_id)
                            ->where('section_id',$section_id)
                            ->where('subject_id',$subject_id)
                            ->get();
                            
                        if(count($checkifexists) == 0){
                            Db::table('studentsubjectattendance')
                                ->insert([
                                    'student_id'        => $student_id,
                                    'section_id'        => $section_id,
                                    'subject_id'        => $subject_id,
                                    'status'            => $status,
                                    'remarks'           => $remarks,
                                    'date'              => $date,
                                    'createddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }else{
                            
                            $hey = DB::update('update studentsubjectattendance set status = ?, remarks = ?, updateddatetime = ? where student_id = ? and section_id = ? and subject_id = ? and date = ?',[$status, $remarks,date('Y-m-d H:i:s'),$student_id,$section_id,$subject_id,$date]);
                        }
                        // if($hey){
                        //     return 'asd';
                        // }else{
                        //     return 'asdsad';
                        // }
                    }
                }
                $attendanceIndexRemarks+=2;
                $attendanceIndexStudId +=2;
            }
            return back()->withInput();
        }else{
            if($request->get('version') == 3)
            {
                $subjectid = $request->get('selectedsubject');
                if($request->get('selectedsubject') == 0)
                {
                    $isCon = 'mapeh';
                }else{
                    $isCon = null;
                }
        
        
                if($request->has('datavalues'))
                {
                    foreach($request->get('datavalues') as $dataval)
                    {
                        // return $dataval['studid'];
                        $checkifexists = DB::table('studentsubjectattendance')
                            ->where('student_id', $dataval['studid'])
                            ->where('section_id', $request->get('selectedsection'))
                            ->where('subject_id', $request->get('selectedsubject'))
                            ->where('date', $dataval['tdate'])
                            ->where('deleted','0');
                            if($subjectid == 0)
                            {
                                $checkifexists = $checkifexists->where('isCon','mapeh')->first();
                            }else{
                                $checkifexists = $checkifexists->first();
                            }

                        if($checkifexists)
                        {
                            if(strtolower($dataval['newstatus']) == 'none')
                            {
                                DB::table('studentsubjectattendance')
                                    ->where('id', $checkifexists->id)
                                    ->update([
                                        'deleted'    => 1,
                                        'deletedby'         => auth()->user()->id,
                                        'deleteddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }else{
                                DB::table('studentsubjectattendance')
                                    ->where('id', $checkifexists->id)
                                    ->update([
                                        'isCon'            => $isCon,
                                        'status'    => strtolower($dataval['newstatus']),
                                        'levelid'           =>$request->get('selectedgradelevel'),
                                        'updatedby'         => auth()->user()->id,
                                        'updateddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }else{
                            if(strtolower($dataval['newstatus']) != 'none')
                            {
                                DB::table('studentsubjectattendance')
                                    ->insert([
                                        'student_id'        =>$dataval['studid'],
                                        'levelid'           =>$request->get('selectedgradelevel'),
                                        'section_id'        =>$request->get('selectedsection'),
                                        'subject_id'        =>$request->get('selectedsubject'),
                                        'date'              =>$dataval['tdate'],
                                        'status'            => strtolower($dataval['newstatus']),
                                        'isCon'            => $isCon,
                                        'semid'             => $request->get('selectedsemester'),
                                        'createdby'   => auth()->user()->id,
                                        'createddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                }
            }
        }
    }
    public function getsections(Request $request)
    {
        if($request->ajax())
        {
            date_default_timezone_set('Asia/Manila');
            
            $syid = DB::table('sy')
                ->where('isactive','1')
                ->first()->id;
            $semid = $request->get('semid');
            $acadprogcode = DB::table('gradelevel')
                ->select('academicprogram.acadprogcode')
                ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
                ->where('gradelevel.id',$request->get('gradelevelid'))
                ->first()->acadprogcode;
            
            $sectionsarray = array();
    
            if(strtolower($acadprogcode) == "shs"){
                // return 'sadas';
                $sectionsreg = DB::table('teacher')
                    ->select('sections.id','sections.sectionname')
                    ->join('sh_classsched','teacher.id','=','sh_classsched.teacherid')
                    ->join('sections','sh_classsched.sectionid','=','sections.id')
                    ->where('sh_classsched.glevelid',$request->get('gradelevelid'))
                    ->where('sh_classsched.syid',$syid)
                    ->where('teacher.userid',auth()->user()->id)
                    ->where('sh_classsched.deleted','0')
                    ->distinct()
                    ->get();
                    
                if(count($sectionsreg)> 0)
                {
                    foreach($sectionsreg as $sectionreg)
                    {
                        array_push($sectionsarray, (object)array(
                            'id'            => $sectionreg->id,
                            'sectionname'   => $sectionreg->sectionname
                        ));
                    }
                }
                $sectionsblock = DB::table('teacher')
                    ->select('sections.id','sections.sectionname','teacher.id as teacherid')
                    ->join('sh_blocksched','teacher.id','=','sh_blocksched.teacherid')
                    ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
                    ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
                    ->where('sh_blocksched.syid',$syid)
                    ->where('sections.levelid',$request->get('gradelevelid'))
                    ->where('teacher.userid',auth()->user()->id)
                    ->where('sh_blocksched.deleted','0')
                    ->where('sh_sectionblockassignment.deleted','0')
                    ->distinct()
                    ->get();
                    
    
                if(count($sectionsblock)> 0)
                {
                    foreach($sectionsblock as $sectionblock)
                    {
                        array_push($sectionsarray, (object)array(
                            'id'            => $sectionblock->id,
                            'sectionname'   => $sectionblock->sectionname
                        ));
                    }
                }
            }
            else{
                $sectionsreg = DB::table('teacher')
                    ->select('sections.id','sections.sectionname')
                    ->join('assignsubjdetail','teacher.id','=','assignsubjdetail.teacherid')
                    ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.ID')
                    ->join('sections','assignsubj.sectionid','=','sections.id')
                    ->where('assignsubj.syid',$syid)
                    ->where('assignsubj.glevelid',$request->get('gradelevelid'))
                    ->where('assignsubj.deleted','0')
                    ->where('sections.deleted','0')
                    ->where('assignsubjdetail.deleted','0')
                    ->where('teacher.userid',auth()->user()->id)
                    ->distinct()
                    ->get();
    
                if(count($sectionsreg)> 0)
                {
                    foreach($sectionsreg as $sectionreg)
                    {
                        array_push($sectionsarray, (object)array(
                            'id'            => $sectionreg->id,
                            'sectionname'   => $sectionreg->sectionname
                        ));
                    }
                }
                    // return $sections;
            }
            return $sectionsarray;
        }
    }
    public function getstrands(Request $request)
    {
        // if($request->ajax())
        // {
            date_default_timezone_set('Asia/Manila');
            
            $syid = DB::table('sy')
                ->where('id',$request->get('syid'))
                ->first()->id;
                
            $acadprogcode = DB::table('gradelevel')
                ->select('acadprogcode','progname')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('gradelevel.id',$request->get('levelid'))
                ->first()->acadprogcode;
            
            if(strtolower($acadprogcode) == "shs"){
                // return $headerIDArray;
                $strandid = $request->get('strandid');
            }
            else{
                $strandid = null;
            }
            $strands = DB::table('sh_sectionblockassignment')
                ->select('sh_strand.*')
                ->join('sh_block','sh_sectionblockassignment.blockid','=','sh_block.id')
                ->join('sh_strand','sh_block.strandid','=','sh_strand.id')
                ->where('sh_sectionblockassignment.sectionid',$request->get('sectionid'))
                ->where('sh_sectionblockassignment.deleted','0')
                ->get();


            return $strands;
        // }
    }
    public function getsubjects(Request $request)
    {
            // return $request->all();
        date_default_timezone_set('Asia/Manila');
        
        $syid = DB::table('sy')
            ->where('id',$request->get('syid'))
            ->first()->id;
        $acadprogcode = DB::table('gradelevel')
            ->select('acadprogcode','progname')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id',$request->get('levelid'))
            ->first()->acadprogcode;
        

        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
        {
            if(strtolower($acadprogcode) == "shs"){
                

                $strandid = null;
                $semid = $request->get('semid');
            }
            else{
                $strandid = null;
                $semid = null;
            }
        }else{
            if(strtolower($acadprogcode) == "shs"){
                

                $strandid = $request->get('strandid');
                $semid = $request->get('semid');
            }
            else{
                $strandid = null;
                $semid = null;
            }
        }
        $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($request->get('levelid'), $syid, $request->get('sectionid'), $semid, $strandid);
        
        $subjectarray = array();
        
        if(count($subjects)>0)
        {
            foreach($subjects as $subject)
            {
                if(collect($subject->schedule)->where('teacherid', DB::table('teacher')->where('userid', auth()->user()->id)->first()->id)->count() > 0)
                {
                    array_push($subjectarray, $subject);
                }
            }
        }      
        $mapehcomps = array('music','arts','p.e','health');
        $isMapeh = 0;
        $components = array();
        if(count($subjectarray)>0)
        {
            foreach($subjectarray as $eachsubject)
            {
                $eachsubject->display = 1;
                $eachsubject->inMapeh = 0;
                foreach($mapehcomps as $eachcomp)
                {
                    // strpos($mystring, $findme)
                    if (str_contains(strtolower($eachsubject->subjdesc), $eachcomp)) { 
                        $isMapeh = 1;
                        $eachsubject->display = 0;
                        $eachsubject->inMapeh = 1;
                        array_push($components, $eachsubject);                                
                    }
                }

            }
        }
        if($isMapeh == 1)
        {
            $subjectarray = collect($subjectarray)->push((object)[
                'id'       => 'mapeh',
                'subjid'       => 'mapeh',
                'subjdesc'       => 'MAPEH',
                'mapeh'       => 1,
                'isCon'       => '1',
                'display'       => '1'
            ]);
        }
        if($request->has('action'))
        {
            return view('teacher.classattendance.bysubject.withcomponents')
                ->with('dates', $request->get('dates'))
                ->with('year', $request->get('selectedyear'))
                ->with('month', $request->get('selectedmonth'))
                ->with('components', $components);
        }else{
            if($request->ajax())
            {
                return collect($subjectarray)->where('display', 1)->unique('subjid')->values();
            }
        }
    }
    public function getstudents(Request $request)
    {
        if(!$request->has('version'))
        {
            if($request->ajax())
            {
                
                if($request->get('date') == true){
                    $date = $request->get('date');
                }else{
                    $date = date('Y-m-d');
                }
        
                $sectionid = $request->get('sectionid');
                $subjectid = $request->get('subjectid');
                $syid = $request->get('syid');
                $semid = DB::table('semester')
                    ->where('isactive','1')
                    ->first()->id;
    
                $teacherid = DB::table('teacher')->where('userid', auth()->user()->id)->first()->id;
    
                $studentstoview = array();
        
                $students_1 = DB::table('studinfo')
                    ->select('enrolledstud.studid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','enrolledstud.promotionstatus')
                    ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                    ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                    ->where('enrolledstud.sectionid', $sectionid)
                    ->where('enrolledstud.syid',$syid)
                    ->whereIn('enrolledstud.studstatus', [1,2,4])
                    // ->whereIn('studinfo.studstatus', [1,2,4])
                    ->where('enrolledstud.studstatus','!=','0')
                    ->orderBy('studinfo.lastname','asc')
                    // ->where('studinfo.studstatus','!=','0')
                    // ->groupBy('enrolledstud.studid')
                    ->distinct()
                    ->get();
                    
                if(count($students_1)==0){
                    $students_1 = DB::table('studinfo')
                        ->select('sh_enrolledstud.studid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','sh_enrolledstud.promotionstatus')
                        ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                        ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                        ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                        // ->whereIn('studinfo.studstatus', [1,2,4])
                        ->where('sh_enrolledstud.sectionid', $sectionid)
                        ->where('sh_enrolledstud.studstatus','!=','0')
                        ->where('sh_enrolledstud.syid',$syid)
                        ->orderBy('studinfo.lastname','asc')
                        ->distinct()
                        ->get();
                }
    
                $students = collect();
                $students = $students->merge($students_1);
                if(count($students)>0)
                {
    
                    foreach($students as $student)
                    {
                        $student->added = 0;
                    }
    
                }
    
                $customscheds = DB::table('sh_classsched')
                    ->select('studsched.studid')
                    ->join('studsched','sh_classsched.id','=','studsched.schedid')
                    ->where('sh_classsched.sectionid', $sectionid)
                    ->where('sh_classsched.teacherid', $teacherid)
                    ->where('sh_classsched.subjid',$subjectid)
                    ->where('sh_classsched.syid', $syid)
                    ->where('sh_classsched.semid', $semid)
                    ->where('sh_classsched.deleted','0')
                    ->where('studsched.deleted','0')
                    ->where('studsched.isapprove','1')
                    ->distinct()
                    ->get();
                
                $customschedstudents = array();
                if(count($customscheds)>0)
                {
    
                    foreach($customscheds as $customsched)
                    {
                        $customschedstud_info = DB::table('studinfo')
                            ->select('studinfo.id as studid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus',
                            'studentstatus.description','sh_enrolledstud.promotionstatus'
                            )
                            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                            ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                            // ->where('sh_enrolledstud.sectionid', $sectionid)
                            ->where('sh_enrolledstud.studstatus','!=','0')
                            ->where('sh_enrolledstud.syid',$syid)
                            ->orderBy('studinfo.lastname','asc')
                            ->distinct()
                            ->where('studinfo.id',$customsched->studid)
                            ->first();
    
                        if($customschedstud_info)
                        {
                            $customschedstud_info->added = 1;
                            array_push($customschedstudents, $customschedstud_info);
                        }
                    }
                }
    
                $students = $students->merge($customschedstudents);
    
                $students = $students->sortBy('lastname')->values();
                
                $checkdata = 0;
                
                foreach($students as $student ){
                    $studentAtt = DB::table('studattendance')
                        ->select('present','absent','tardy','cc')
                        ->where('tdate', '2020-11-20')
                        ->where('studid',$student->studid)
                        // ->where('section_id',$section_id)
                        ->get();
                        // return $studentAtt;
                    if(count($studentAtt)>=0){
                        $checkdata+=1;
                    }
                }
        
                $checkbeadleattendance = 0;
                
                if($checkdata>0){
                    foreach($students as $student){
                        $studentAtts = DB::table('studattendance')
                            ->select('present','absent','tardy','cc')
                            ->where('tdate', $date)
                            ->where('studid',$student->studid)
                            ->distinct()
                            ->get();
        
                        $attBySubj = DB::table('studentsubjectattendance')
                            ->where('student_id',$student->studid)
                            ->where('section_id',$sectionid)
                            ->where('date',$date)
                            ->where('subject_id',$subjectid)
                            ->get();
                        // return $attBySubj;;
                        if(count($attBySubj)==0){
                            if(count($studentAtts)==0){
                                array_push($studentstoview,(object)array(
                                    'status' => 'present',
                                    'remarks' => null,
                                    'lastname' => $student->lastname,
                                    'firstname' => $student->firstname,
                                    'middlename' => $student->middlename,
                                    'gender' => strtolower($student->gender),
                                    'id' => $student->studid,
                                    'description' => $student->description,
                                    'studstatus' => $student->studstatus,
                                    'added' => $student->added,
                                    'promotionstatus' => $student->promotionstatus
                                ));
                            }
                            else{
                                
                                if($studentAtts[0]->absent == 1){
                                    $status ='absent';
                                }else{
                                    $status = 'present';
                                }
                                array_push($studentstoview,(object)array(
                                    'status' =>  $status,
                                    'remarks' => null,
                                    'lastname' => $student->lastname,
                                    'firstname' => $student->firstname,
                                    'middlename' => $student->middlename,
                                    'gender' => strtolower($student->gender),
                                    'id' => $student->studid,
                                    'description' => $student->description,
                                    'studstatus' => $student->studstatus,
                                    'added' => $student->added,
                                    'promotionstatus' => $student->promotionstatus
                                ));
                            }
                        }
                        if(count($attBySubj)>0){
                            $checkbeadleattendance+=1;
                            $attInfoStud = DB::table('studentsubjectattendance')
                            ->select(
                                'studentsubjectattendance.status',
                                'studentsubjectattendance.remarks',
                                'studinfo.lastname','studinfo.firstname',
                                'studinfo.middlename','studinfo.gender','studinfo.id','studinfo.studstatus'
                            )
                            ->join('studinfo','studinfo.id','=','studentsubjectattendance.student_id')
                            ->where('section_id',$sectionid)
                            ->where('date',$date)
                            ->where('subject_id',$subjectid)
                            ->where('studinfo.id',$student->studid)
                            ->get();
                            $attInfoStud[0]->promotionstatus = $student->promotionstatus;
                            $attInfoStud[0]->gender = strtolower($attInfoStud[0]->gender);
                            $attInfoStud[0]->added = $student->added;
                            $attInfoStud[0]->description = $student->description;
                            array_push($studentstoview,$attInfoStud[0]);
                        }
                    }
                }else{
                    // return 'No records from this day!';
                    // return 'asdasd';
                    foreach($students as $student ){
                        $studentAtt = DB::table('studattendance')
                            ->select('present','absent','tardy','cc')
                            ->where('tdate', $date)
                            ->where('studid',$student->studid)
                            ->distinct()
                            ->get();
                        $attBySubj = DB::table('studentsubjectattendance')
                            ->where('student_id',$student->studid)
                            ->where('section_id',$sectionid)
                            ->where('date',$date)
                            ->where('subject_id',$subjectid)
                            ->get();
                            
                        if(count($attBySubj)==0){
                            if(count($studentAtt)==0){
                                array_push($studentstoview,(object)array(
                                    'status' => 'present',
                                    'remarks' => null,
                                    'lastname' => $student->lastname,
                                    'firstname' => $student->firstname,
                                    'middlename' => $student->middlename,
                                    'gender' => strtolower($student->gender),
                                    'id' => $student->studid,
                                    'description' => $student->description,
                                    'studstatus' => $student->studstatus,
                                    'added' => $student->added,
                                    'promotionstatus' => $student->promotionstatus
                                ));
                            }
                            else{
                                if( $studentAtt[0]->absent == 1){
                                    $status ='absent';
                                }else{
                                    $status = 'present';
                                }
                                array_push($studentstoview,(object)array(
                                    'status' => $status,
                                    'remarks' => null,
                                    'lastname' => $student->lastname,
                                    'firstname' => $student->firstname,
                                    'middlename' => $student->middlename,
                                    'gender' => strtolower($student->gender),
                                    'id' => $student->studid,
                                    'description' => $student->description,
                                    'studstatus' => $student->studstatus,
                                    'added' => $student->added,
                                    'promotionstatus' => $student->promotionstatus
                                ));
                            }
                        }
                        elseif(count($attBySubj)>=0){
                            $checkbeadleattendance+=1;
                            $attInfoStud = DB::table('studentsubjectattendance')
                            ->select(
                                'studentsubjectattendance.status',
                                'studentsubjectattendance.remarks',
                                'studinfo.lastname','studinfo.firstname',
                                'studinfo.middlename','studinfo.gender','studinfo.id','studinfo.studstatus'
                            )
                            ->join('studinfo','studinfo.id','=','studentsubjectattendance.student_id')
                            ->where('section_id',$sectionid)
                            ->where('date',$date)
                            ->where('subject_id',$subjectid)
                            ->where('studinfo.id',$student->studid)
                            ->get();
                            $attInfoStud[0]->promotionstatus = $student->promotionstatus;
                            $attInfoStud[0]->gender = strtolower($attInfoStud[0]->gender);
                            $attInfoStud[0]->added = $student->added;
                            $attInfoStud[0]->description = $student->description;
                            array_push($studentstoview,$attInfoStud);
                        }
                    }
                }
                $attendancearray = array();
                if($checkbeadleattendance == 0){
                    $exists = 0;
                }elseif($checkbeadleattendance>0){
                    $exists = 1;
                }
                array_push($attendancearray,$studentstoview);
                array_push($attendancearray,$exists);
                return $attendancearray;
            }
        }else{
            if($request->get('mapehattendance') == 1)
            {
                $year = $request->get('selectedyear');
                $month = $request->get('selectedmonth');
                $syid = $request->get('selectedschoolyear');
                $strandid = $request->get('selectedstrand');
                $semid = $request->get('selectedsemester');
                $levelid = $request->get('selectedgradelevel');
                $sectionid = $request->get('selectedsection');
                $subjects = json_decode($request->get('subjects'));
                $selecteddates = $request->get('dates');
                preg_match_all('!\d+!', $selecteddates, $matches);
                $selecteddates = $matches[0];
                $students = array();
                $gradelevelinfo = DB::table('gradelevel')
                ->select('gradelevel.*')
                ->where('gradelevel.id', $levelid)
                ->first();

                $acadprogcode = DB::table('gradelevel')
                    ->select('acadprogcode','progname')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('gradelevel.id',$levelid)
                    ->first()->acadprogcode;
                
    
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
                {
                    if(strtolower($acadprogcode) == "shs"){
                        
        
                        $strandid = null;
                        $semid = $semid;
                    }
                    else{
                        $strandid = null;
                        $semid = null;
                    }
                }else{
                    if(strtolower($acadprogcode) == "shs"){
                        
        
                        $strandid = $strandid;
                        $semid = $semid;
                    }
                    else{
                        $strandid = null;
                        $semid = null;
                    }
                }
                $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid, $syid, $sectionid, $semid, $strandid);
                
                $subjectarray = array();
                
                if(count($subjects)>0)
                {
                    foreach($subjects as $subject)
                    {
                        if(collect($subject->schedule)->where('teacherid', DB::table('teacher')->where('userid', auth()->user()->id)->first()->id)->count() > 0)
                        {
                            array_push($subjectarray, $subject);
                        }
                    }
                }      
                $mapehcomps = array('music','arts','p.e','health');
                // $isMapeh = 0;
                $subjidswithmapeh = array(0);
                $components = array();
                if(count($subjectarray)>0)
                {
                    foreach($subjectarray as $eachsubject)
                    {
                        $eachsubject->display = 1;
                        $eachsubject->inMapeh = 0;
                        foreach($mapehcomps as $eachcomp)
                        {
                            // strpos($mystring, $findme)
                            if (str_contains(strtolower($eachsubject->subjdesc), $eachcomp)) { 
                                $isMapeh = 1;
                                $eachsubject->display = 0;
                                $eachsubject->inMapeh = 1;
                                array_push($components, $eachsubject);                                
                                array_push($subjidswithmapeh, $eachsubject->subjid);                                
                            }
                        }
        
                    }
                }
                if($request->has('subjectid'))
                {
                    $components = collect($components)->where('subjid', $request->get('subjectid'))->values();
                }
                // if($isMapeh == 1)
                // {
                //     $subjectarray = collect($subjectarray)->push((object)[
                //         'id'       => 'mapeh',
                //         'subjid'       => 'mapeh',
                //         'subjdesc'       => 'MAPEH',
                //         'mapeh'       => 1,
                //         'isCon'       => '1',
                //         'display'       => '1'
                //     ]);
                // }
                if($gradelevelinfo->acadprogid == 5)
                {                            
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
                    {
                        $students = DB::table('studinfo')
                                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','sh_enrolledstud.studstatus','studentstatus.description','sh_enrolledstud.promotionstatus')
                                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                                    ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                                    ->where('sh_enrolledstud.sectionid', $sectionid)
                                    ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                                    // ->whereIn('studinfo.studstatus', [1,2,4])
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->where('sh_enrolledstud.levelid',$levelid)
                                    ->where('studinfo.deleted','0')
                                    ->where('sh_enrolledstud.studstatus','!=',0)
                                    ->where('sh_enrolledstud.studstatus','!=',6)
                                    ->where('studinfo.studstatus','!=',6)
                                    ->where('sh_enrolledstud.syid',$syid)
                                    ->where('sh_enrolledstud.semid',$semid)
                                    ->orderBy('lastname','asc')
                                    ->distinct()
                                    ->get();
                    }else{
                        $students = DB::table('studinfo')
                                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','sh_enrolledstud.studstatus','studentstatus.description','sh_enrolledstud.promotionstatus')
                                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                                    ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                                    ->where('sh_enrolledstud.sectionid', $sectionid)
                                    ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                                    // ->whereIn('studinfo.studstatus', [1,2,4])
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->where('sh_enrolledstud.levelid',$levelid)
                                    ->where('sh_enrolledstud.strandid',$strandid)
                                    ->where('studinfo.deleted','0')
                                    ->where('sh_enrolledstud.studstatus','!=',0)
                                    ->where('sh_enrolledstud.studstatus','!=',6)
                                    ->where('studinfo.studstatus','!=',6)
                                    ->where('sh_enrolledstud.syid',$syid)
                                    ->where('sh_enrolledstud.semid',$semid)
                                    ->orderBy('lastname','asc')
                                    ->distinct()
                                    ->get();
                    }

                    // $subjectname = DB::table('sh_subjects')
                    //     ->where('id', $subjectid)->first()->subjtitle;     

                    // $subjectcode = DB::table('sh_subjects')
                    //     ->where('id', $subjectid)->first()->subjcode;  
                }else{
                    $students = DB::table('enrolledstud')
                                ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','enrolledstud.studstatus','studentstatus.description','enrolledstud.promotionstatus')
                                ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                                ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                                ->where('enrolledstud.sectionid', $sectionid)
                                ->whereIn('enrolledstud.studstatus', [1,2,4])
                                // ->whereIn('studinfo.studstatus', [1,2,4])
                                ->where('enrolledstud.deleted',0)
                                ->where('enrolledstud.levelid',$levelid)
                                ->where('studinfo.deleted','0')
                                ->where('enrolledstud.studstatus','!=',0)
                                ->where('enrolledstud.studstatus','!=',6)
                                ->where('studinfo.studstatus','!=',6)
                                ->where('enrolledstud.syid',$syid)
                                ->distinct()
                                ->orderBy('lastname','asc')
                                ->get();   
                    // $subjectname = DB::table('subjects')
                    //     ->where('id', $subjectid)->first()->subjdesc;     
        
                    // $subjectcode = DB::table('subjects')
                    //     ->where('id', $subjectid)->first()->subjcode;  
                     
                    $temp_students = array();
                    $subjects = $subjectarray;
                    // return $subjects;
                    foreach($components as $subjectid)
                    {
                        // $temp_students = array();
                        $check_subj = DB::table('subjects')
                            ->where('id',$subjectid->subjid)
                            ->select('isSP')
                            ->first();
                               
                        try
                        {
                            if($check_subj->isSP == 1){
                                $student_spec = DB::table('subjects_studspec')
                                                    ->where('syid',$syid)
                                                    ->where('deleted',0)
                                                    ->where('subjid',$subjectid->subjid)
                                                    ->select('studid')
                                                    ->get();
                                                              
                                foreach($students as $item){
                                    $check = collect($student_spec)->where('studid',$item->id)->count();
                                    if($check != 0){
                                    array_push($temp_students,$item);
                                    }
                                    
                                }    
                                $students =       $temp_students;                  
                            }
                        }catch(\Exception $error)
                        {}
                    }
                }
                // return $components;
                // $allsubjects = array();
                // foreach($components as $subjectid)
                // {
                //     if($gradelevelinfo->acadprogid == 5)
                //     {   
                //         $subjectname = DB::table('sh_subjects')
                //             ->where('id', $subjectid->subjid)->first()->subjtitle;     

                //         $subjectcode = DB::table('sh_subjects')
                //             ->where('id', $subjectid->subjid)->first()->subjcode;  
                //     }else{
                //         $subjectname = DB::table('subjects')
                //             ->where('id', $subjectid->subjid)->first()->subjdesc;     
            
                //         $subjectcode = DB::table('subjects')
                //             ->where('id', $subjectid->subjid)->first()->subjcode;  
                //     }
                //     array_push($allsubjects, (object)array(
                //         'id'            => $subjectid->subjid,
                //         'subjectname'   => $subjectname,
                //         'subjectcode'   => $subjectcode
                //     ));

                // }
                
                $students = collect($students)->unique('id');
                $dates = array();
                if (is_array($selecteddates) || is_object($selecteddates))
                {
                    foreach($selecteddates as $date)
                    {
                        array_push($dates, (object)array(
                            'date'  => date('Y-m-d',strtotime($year.'-'.$month.'-'.$date)),
                            'datestr'  => date('M d',strtotime($year.'-'.$month.'-'.$date)),
                            'day'  => date('D',strtotime($year.'-'.$month.'-'.$date))
                        ));
                    }
                }

                
                $studids = collect($students)->pluck('id');
                
                $attendance = DB::table('studentsubjectattendance')
                    ->where('section_id', $sectionid)
                    ->whereIn('subject_id', $subjidswithmapeh)
                    ->whereIn('date',collect($dates)->pluck('date'))
                    ->whereIn('student_id',$studids)
                    ->where('deleted','0')
                    ->get();
                    
                    // return $subjidswithmapeh;
                if(count($students)>0)
                {
                    foreach($students as $student)
                    {
                        $att = array();
                        foreach($dates as $date)
                        {
                            foreach($components as $eachsubj)
                            {
                                $attstatus = collect($attendance)->where('student_id', $student->id)
                                ->where('subject_id', $eachsubj->subjid)
                                ->where('date', $date->date)
                                ->values();

                                $status = "";
        
                                if(count($attstatus)>0)
                                {
                                    $status = strtolower($attstatus[0]->status);
                                }
                                array_push($att, (object)array(
                                    'subjid'     =>    $eachsubj->subjid,
                                    'subjdesc'     =>    $eachsubj->subjectname ?? $eachsubj->subjtitle ?? $eachsubj->subjdesc ,
                                    'subjcode'     =>    $eachsubj->subjectcode ?? $eachsubj->subjcode,
                                    'tdate'     =>    $date->date,
                                    'status'    => $status
                                ));

                            }
                            $conattstatus = collect($attendance)->where('student_id', $student->id)->where('subject_id', 0)->where('isCon', 'mapeh')->where('date',$date->date)->values();
                            // return $conattstatus;
                            $constatus = "";
    
                            if(count($conattstatus)>0)
                            {
                                
                                $constatus = strtolower($conattstatus[0]->status);
                            }
                            array_push($att, (object)array(
                                'subjid'     =>    '0',
                                'subjdesc'     =>    'MAPEH',
                                'subjcode'     =>    'MAPEH',
                                'tdate'     =>    $date->date,
                                'status'    => $constatus
                            ));
                        }
    
                        $student->attendance = $att;
                    }
                }
                $subjectid = 0;
                if($request->has('subjectid'))
                {
                    $subjectid = $request->get('subjectid');
                }
                return view('teacher.classattendance.bysubject.withcomponentsstudents')
                        ->with('dates', $dates)
                        ->with('subjects', $components)
                        ->with('subjectid', $subjectid)
                        ->with('students', $students);
                
            }else{
                if($request->get('version') == 3)
                {
                    $year = $request->get('selectedyear');
                    $month = $request->get('selectedmonth');
                    $syid = $request->get('selectedschoolyear');
                    $strandid = $request->get('selectedstrand');
                    $semid = $request->get('selectedsemester');
                    $levelid = $request->get('selectedgradelevel');
                    $sectionid = $request->get('selectedsection');
                    $subjectid = $request->get('selectedsubject');
    
                    
                    $gradelevelinfo = DB::table('gradelevel')
                        ->select('gradelevel.*','academicprogram.acadprogcode')
                        ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                        ->where('gradelevel.id', $levelid)
                        ->first();
    
    
                    $levelname = DB::table('gradelevel')
                        ->where('id', $levelid)->first()->levelname;
            
                    $sectionname = DB::table('sections')
                        ->where('id', $sectionid)->first()->sectionname;
    
                    if($gradelevelinfo)
                    {
                        if(strtolower($gradelevelinfo->acadprogcode) != 'shs'){
                            $students = DB::table('enrolledstud')
                                        ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','enrolledstud.studstatus','studentstatus.description','enrolledstud.promotionstatus')
                                        ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                                        ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                                        ->where('enrolledstud.sectionid', $sectionid)
                                        ->whereIn('enrolledstud.studstatus', [1,2,4])
                                        // ->whereIn('studinfo.studstatus', [1,2,4])
                                        ->where('enrolledstud.deleted',0)
                                        ->where('enrolledstud.levelid',$levelid)
                                        ->where('studinfo.deleted','0')
                                        ->where('enrolledstud.studstatus','!=',0)
                                        ->where('enrolledstud.studstatus','!=',6)
                                        ->where('studinfo.studstatus','!=',6)
                                        ->where('enrolledstud.syid',$syid)
                                        ->distinct()
                                        ->orderBy('lastname','asc')
                                        ->get();   
    
                            $subjectname = DB::table('subjects')
                                ->where('id', $subjectid)->first()->subjdesc ?? 'MAPEH';     
                
                            $subjectcode = DB::table('subjects')
                                ->where('id', $subjectid)->first()->subjcode ?? 'MAPEH';  
                        }else{
                            
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
                            {
                                $students = DB::table('studinfo')
                                            ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','sh_enrolledstud.studstatus','studentstatus.description','sh_enrolledstud.promotionstatus')
                                            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                                            ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                                            ->where('sh_enrolledstud.sectionid', $sectionid)
                                            ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                                            // ->whereIn('studinfo.studstatus', [1,2,4])
                                            ->where('sh_enrolledstud.deleted',0)
                                            ->where('sh_enrolledstud.levelid',$levelid)
                                            ->where('studinfo.deleted','0')
                                            ->where('sh_enrolledstud.studstatus','!=',0)
                                            ->where('sh_enrolledstud.studstatus','!=',6)
                                            ->where('studinfo.studstatus','!=',6)
                                            ->where('sh_enrolledstud.syid',$syid)
                                            ->where('sh_enrolledstud.semid',$semid)
                                            ->orderBy('lastname','asc')
                                            ->distinct()
                                            ->get();
                            }else{
                                $students = DB::table('studinfo')
                                            ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','sh_enrolledstud.studstatus','studentstatus.description','sh_enrolledstud.promotionstatus')
                                            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                                            ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                                            ->where('sh_enrolledstud.sectionid', $sectionid)
                                            ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                                            // ->whereIn('studinfo.studstatus', [1,2,4])
                                            ->where('sh_enrolledstud.deleted',0)
                                            ->where('sh_enrolledstud.levelid',$levelid)
                                            ->where('sh_enrolledstud.strandid',$strandid)
                                            ->where('studinfo.deleted','0')
                                            ->where('sh_enrolledstud.studstatus','!=',0)
                                            ->where('sh_enrolledstud.studstatus','!=',6)
                                            ->where('studinfo.studstatus','!=',6)
                                            ->where('sh_enrolledstud.syid',$syid)
                                            ->where('sh_enrolledstud.semid',$semid)
                                            ->orderBy('lastname','asc')
                                            ->distinct()
                                            ->get();
                            }
    
                            $subjectname = DB::table('sh_subjects')
                                ->where('id', $subjectid)->first()->subjtitle ?? 'MAPEH';     
    
                            $subjectcode = DB::table('sh_subjects')
                                ->where('id', $subjectid)->first()->subjcode ?? 'MAPEH';  
                        }
                    }else{
                        $students = array();
                    }
                    if(strtolower($gradelevelinfo->acadprogcode) != 'shs'){
                         
                        $check_subj = DB::table('subjects')
                                       ->where('id',$subjectid)
                                       ->select('isSP')
                                       ->first();
                                      
                        if($check_subj)
                        {
                            if($check_subj->isSP == 1){
                                 $temp_students = array();
                                 $student_spec = DB::table('subjects_studspec')
                                                    ->where('syid',$syid)
                                                    ->where('deleted',0)
                                                    ->where('subjid',$subjectid)
                                                    ->select('studid')
                                                    ->get();
                                                    
                                foreach($students as $item){
                                    $check = collect($student_spec)->where('studid',$item->id)->count();
                                    if($check != 0){
                                      array_push($temp_students,$item);
                                    }
                                    
                                }
                                    
                                $students = $temp_students;
                            }
                        }
                       
                   }
                    $dates = array();
    
                    foreach($request->get('dates') as $date)
                    {
                        array_push($dates, (object)array(
                            'date'  => date('Y-m-d',strtotime($year.'-'.$month.'-'.$date)),
                            'datestr'  => date('M d',strtotime($year.'-'.$month.'-'.$date)),
                            'day'  => date('D',strtotime($year.'-'.$month.'-'.$date))
                        ));
                    }
    
                    
                    $studids = collect($students)->pluck('id');
                    
                    $attendance = DB::table('studentsubjectattendance')
                        ->where('section_id', $sectionid)
                        ->where('subject_id', $subjectid)
                        ->where('deleted','0')
                        ->whereBetween('date',[collect($dates)->first()->date,collect($dates)->last()->date])
                        ->whereIn('student_id',$studids)
                        ->where('deleted','0')
                        ->get();
                    // return $attendance;
                    if(count($students)>0)
                    {
                        foreach($students as $student)
                        {
                            $att = array();
        
                            foreach($dates as $date)
                            {
                                $attstatus = collect($attendance)->where('student_id', $student->id)->where('date', $date->date)->values();
                                
                                $status = "";
        
                                if(count($attstatus)>0)
                                {
                                    
                                    $status = strtolower($attstatus[0]->status);
                                }
        
                                array_push($att, (object)array(
                                    'tdate'     =>    $date->date,
                                    'status'    => $status
                                ));
                            }
        
                            $student->attendance = $att;
                        }
                    }
                    
                    if(!$request->has('action'))
                    {
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                        {
                            return view('teacher.classattendance.bysubject.subjattendancetable_hccsi')
                                    ->with('dates', $dates)
                                    ->with('students', $students);
                        }else{
                            return view('teacher.classattendance.bysubject.subjattendancetable_v3')
                                    ->with('dates', $dates)
                                    ->with('students', $students);
                        }
    
                    }else{
                        $schoolinfo = DB::table('schoolinfo')
                            ->first();
                        // return $students;
                        
                        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
                        $sheet = $spreadsheet->getActiveSheet();
                        $borderstyle    = [
                                            'borders' => [
                                                'top' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                ],
                                                'bottom' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                ],
                                                'left' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                ],
                                                'right' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                ],
                                            ]
                                        ];
                        $font_bold = [
                                'font' => [
                                    'bold' => true,
                                ]
                            ];
                        foreach(range('B','Z') as $columnID) {
                            $sheet->getColumnDimension($columnID)->setAutoSize(true);
                        }
    
                        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $drawing->setName('Logo');
                        $drawing->setDescription('Logo');
                        
                        if(strpos(DB::table('schoolinfo')->first()->picurl, '?') !== false){
                            $picurl = substr(DB::table('schoolinfo')->first()->picurl, 0, strpos(DB::table('schoolinfo')->first()->picurl, "?"));
                        }else{
                            $picurl = DB::table('schoolinfo')->first()->picurl;
                        }
                        $drawing->setPath(base_path().'/public/'.$picurl);
                        $drawing->setHeight(80);
                        $drawing->setWorksheet($sheet);
                        $drawing->setCoordinates('A1');
                        $drawing->setOffsetX(20);
                        $drawing->setOffsetY(20);
                        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    
                        $drawing->getShadow()->setVisible(true);
                        $drawing->getShadow()->setDirection(45);
                        $startcellno = 5;
    
                                // return $dates;;
                        // $sheet->mergeCells('C2:J2');
                        $sheet->setCellValue('B2', $schoolinfo->schoolname);
                        // $sheet->getStyle('C2:J2')->getAlignment()->setHorizontal('center');
    
                        // $sheet->mergeCells('C3:J3');
                        $sheet->setCellValue('B3', $schoolinfo->address);
                        // $sheet->getStyle('C3:J3')->getAlignment()->setHorizontal('center');
                        $sheet->setCellValue('B'.$startcellno, 'GRADE LEVEL & SECTION : '.$levelname.' - '.$sectionname);
                        $startcellno+=1;
    
                        $sheet->setCellValue('B'.$startcellno, 'SUBJECT : '.$subjectcode.' - '.$subjectname);
                        $startcellno+=1;
    
                        if(count($dates) == 1)
                        {
                            $sheet->setCellValue('B'.$startcellno, 'AS OF : '.strtoupper($dates[0]->datestr));
                        }else{
                            $sheet->setCellValue('B'.$startcellno, 'AS OF : '.strtoupper(collect($dates)->first()->datestr).' - '.strtoupper(collect($dates)->last()->datestr));
                        }
                        
                        $startcellno+=2;
    
                        $columnno = 2;
                        
                        $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->getStyle('A'.(int)($startcellno+1).':B'.(int)($startcellno+1))->applyFromArray($borderstyle);
                        $sheet->mergeCells('A'.$startcellno.':B'.(int)($startcellno+1));
                        $sheet->setCellValue('A'.$startcellno, 'STUDENTS');
                        $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                        function getNameFromNumber($num) {
                            $numeric = ($num - 1) % 26;
                            $letter = chr(65 + $numeric);
                            $num2 = intval(($num - 1) / 26);
                            if ($num2 > 0) {
                                return getNameFromNumber($num2) . $letter;
                            } else {
                                return $letter;
                            }
                        }
    
                        if(count($dates)>0)
                        {
                            $columndate = 3;
                            foreach($dates as $date)
                            {
                                // return getNameFromNumber($columnno+count($dates)).$startcellno;
                                $sheet->getStyle(getNameFromNumber($columndate).$startcellno)->applyFromArray($borderstyle);
                                $sheet->setCellValue(getNameFromNumber($columndate).$startcellno, date('M d',strtotime($date->date)));
                                $sheet->getStyle(getNameFromNumber($columndate).(int)($startcellno+1))->applyFromArray($borderstyle);
                                $sheet->setCellValue(getNameFromNumber($columndate).(int)($startcellno+1), $date->day);
                                $sheet->getStyle(getNameFromNumber($columndate).$startcellno)->getFont()->setBold(true);
                                $columndate+=1;
                            }
                        }
                        $sheet->getStyle('A'.$startcellno)->applyFromArray($borderstyle);
                        $startcellno+=2;
    
                        $malecount = 1;
                        $femalecount = 1;
    
                        // return $dates;
    
                        $sheet->mergeCells('A'.$startcellno.':'.sprintf(getNameFromNumber(1+count($dates))).$startcellno);
                        $sheet->setCellValue('A'.$startcellno, 'MALE');
                        $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('A'.(int)($startcellno).':B'.(int)($startcellno+1))->applyFromArray($borderstyle);
                        $startcellno+=1;
                        foreach($students as $student)
                        {
                            if(strtolower($student->gender) == 'male')
                            {
                                $sheet->getStyle('A'.($startcellno).':B'.(int)($startcellno+1))->applyFromArray($borderstyle);
                                $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                try{
                                    $sheet->setCellValue('A'.$startcellno, $malecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'. '.$student->suffix);
                                }catch(\Exception $e)
                                {
                                    $sheet->setCellValue('A'.$startcellno, $malecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->suffix);
                                }
                                $attendancecolumn = 3;
                                if(count($student->attendance)>0)
                                {
                                    foreach($student->attendance as $att)
                                    {
                                        $sheet->getStyle(sprintf(getNameFromNumber($attendancecolumn)).$startcellno)->applyFromArray($borderstyle);
                                        $sheet->setCellValue(sprintf(getNameFromNumber($attendancecolumn)).$startcellno, strtoupper($att->status));
                                        $attendancecolumn+=1;
                                    }
                                }
                                $startcellno+=1;
                                $malecount+=1;
                            }
                        }
    
                        $sheet->mergeCells('A'.$startcellno.':'.sprintf(getNameFromNumber(1+count($dates))).$startcellno);
                        $sheet->setCellValue('A'.$startcellno, 'FEMALE');
                        $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                        $startcellno+=1;
                        foreach($students as $student)
                        {
                            if(strtolower($student->gender) == 'female')
                            {
                                $sheet->getStyle('A'.($startcellno).':B'.($startcellno))->applyFromArray($borderstyle);
                                $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                try{
                                    $sheet->setCellValue('A'.$startcellno, $femalecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'. '.$student->suffix);
                                }catch(\Exception $e)
                                {
                                    $sheet->setCellValue('A'.$startcellno, $femalecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->suffix);
                                }
                                $attendancecolumn = 3;
                                if(count($student->attendance)>0)
                                {
                                    foreach($student->attendance as $att)
                                    {
                                        $sheet->getStyle(sprintf(getNameFromNumber($attendancecolumn)).$startcellno)->applyFromArray($borderstyle);
                                        $sheet->setCellValue(sprintf(getNameFromNumber($attendancecolumn)).$startcellno, strtoupper($att->status));
                                        $attendancecolumn+=1;
                                    }
                                }
                                $startcellno+=1;
                                $femalecount+=1;
                            }
                        }
    
    
                                
                        // $sheet->setCellValue('B'.$startcellno, 'SCHOOL YEAR');
                        // $sheet->setCellValue('C'.$startcellno, ': '.$sy->sydesc);
                        // $sheet->setCellValue('E'.$startcellno, 'COLLEGE/TRACK');
                        // $sheet->setCellValue('F'.$startcellno, ': '.$trackname);
                        // $sheet->setCellValue('H'.$startcellno, 'GENDER');
                        // $sheet->setCellValue('I'.$startcellno, ': '.$selectedgender);
                        // $startcellno+=1;
                        
    
                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment; filename="Attendance - '.$levelname.' '.$sectionname.' ('.$subjectcode.').xlsx"');
                        $writer->save("php://output");
                    }
                }
            }
        }
    }
    public function getcalendar(Request $request)
    {
        if($request->get('strandid') == 0)
        {
            $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($request->get('levelid'), $request->get('syid'), $request->get('sectionid'), null, null);
                  
        }else{
            $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($request->get('levelid'), $request->get('syid'), $request->get('sectionid'), $request->get('semid'), $request->get('strandid'));
                  
        }  
        // return $subjects;
        $schedules = array();
        if(count($subjects)>0)
        {
            foreach($subjects as $subject)
            {
                if(count($subject->schedule)> 0)
                {
                    
                    if(count($subject->schedule[0]->days)>0)
                    {
                        foreach($subject->schedule[0]->days as $eachday)
                        {
                            array_push($schedules, (object)array(
                                'id'        => $subject->subjid,
                                'teacherid'        =>$subject->schedule[0]->teacherid,
                                'subjectname'     => $subject->subjcode,
                                'subjdesc'        => $subject->subjdesc,
                                'day'             => $eachday == 1 ? 'Mon' : ($eachday == 2 ? 'Tue' : ($eachday == 3 ? 'Wed' : ($eachday == 4 ? 'Thu' : ($eachday == 5 ? 'Fri' : ($eachday == 6 ? 'Sat' : ( $eachday == 7 ? 'Sun' : null)))))),
                                'starttime'       => $subject->schedule[0]->start,
                                'shift'           => date('A', strtotime($subject->schedule[0]->start)),
                            )); 
                        }
                    }
                    // array_push($schedules, (object)array(
                    //     'id'        => $subject->subjid,
                    //     'teacherid'        =>$subject->schedule[0]->teacherid,
                    //     'subjectname'     => $subject->subjcode,
                    //     'subjdesc'        => $subject->subjdesc,
                    //     'day'             => str_replace(' ', '', $subject->schedule[0]->day),
                    //     'starttime'       => $subject->schedule[0]->start,
                    //     'shift'           => date('A', strtotime($subject->schedule[0]->start)),
                    // ));
                }
            }
        }
        $schedules = collect($schedules)->where('teacherid', DB::table('teacher')->where('userid', auth()->user()->id)->first()->id)->where('id', $request->get('subjectid'))->values();
        
        // return $request->all();
        $list=array();
        $today = date("d"); // Current day
        $month = $request->get('selectedmonth');
        $year =  $request->get('selectedyear');
        function draw_calendar($month,$year,$schedules){

            /* draw table */
            $calendar = '<table class="table-bordered" style="width: 100%;">';
        
            /* table headings */
            $headings = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
            $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
        
            /* days and weeks vars now ... */
            $running_day = date('w',mktime(0,0,0,$month,1,$year));
            $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
            $days_in_this_week = 1;
            $day_counter = 0;
            $dates_array = array();
        
            /* row for week one */
            $calendar.= '<tr class="calendar-row" style="text-transform: none;">';
        
            /* print "blank" days until the first of the current week */
            for($x = 0; $x < $running_day; $x++):
                $calendar.= '<td class="calendar-day-np"> </td>';
                $days_in_this_week++;
            endfor;
        
            /* keep going with days.... */
            for($list_day = 1; $list_day <= $days_in_month; $list_day++):
                $calendar.= '<td class="calendar-day">';
                    /* add in the day number */
                    if(collect($schedules)->where('day',date('D', strtotime($year.'-'.$month.'-'.$list_day)))->count() == 0)
                    {
                        $calendar.= '<div class="day-number"><a class="btn btn-block btn-second active-date"  data-id="'.$list_day.'">'.$list_day.'</a></div>';
                    }else{
                        $calendar.= '<div class="day-number"><a class="btn btn-block active-date selecteddate"style="background-color: #ddd;"  data-id="'.$list_day.'">'.$list_day.'</a></div>';
                    }
        
                    /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
                    $calendar.= str_repeat('<p> </p>',2);
                    
                $calendar.= '</td>';
                if($running_day == 6):
                    $calendar.= '</tr>';
                    if(($day_counter+1) != $days_in_month):
                        $calendar.= '<tr class="calendar-row">';
                    endif;
                    $running_day = -1;
                    $days_in_this_week = 0;
                endif;
                $days_in_this_week++; $running_day++; $day_counter++;
            endfor;
        
            /* finish the rest of the days in the week */
            if($days_in_this_week < 8):
                for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                    $calendar.= '<td class="calendar-day-np"> </td>';
                endfor;
            endif;
        
            /* final row */
            $calendar.= '</tr>';
        
            /* end the table */
            $calendar.= '</table>';
            
            /* all done, return result */
            return $calendar;
        }
        
        /* sample usages */
        echo '<h2><center>' . date('F Y', strtotime($year.'-'.$month)) . '</center></h2>';
        return draw_calendar($month,$year,$schedules);
    }
    public function updatecolumn(Request $request)
    {
        $tdate          = $request->get('tdate');
        $studids        = json_decode($request->get('studids'));
        $levelid        = $request->get('levelid');
        $sectionid        = $request->get('sectionid');
        $subjectid        = $request->get('subjectid');

        if($subjectid == 0)
        {
            $isCon = 'mapeh';
        }else{
            $isCon = null;
        }
        if($request->get('action') == 'present')
        {
            
            foreach($studids as $studid)
            {
                $checkifexists = DB::table('studentsubjectattendance')
                    ->where('levelid', $levelid)
                    ->where('section_id', $sectionid)
                    ->where('subject_id', $subjectid)
                    ->where('student_id', $studid)
                    ->where('date', $tdate)
                    ->where('deleted','0');

                    if($subjectid == 0)
                    {
                        $checkifexists = $checkifexists->where('isCon','mapeh')->first();
                    }else{
                        $checkifexists = $checkifexists->first();
                    }

                if($checkifexists)
                {
                    DB::table('studentsubjectattendance')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'isCon'            => $isCon,
                            'status'           => 'present',
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studentsubjectattendance')
                        ->insert([
                            'status'           => 'present',
                            'student_id'            => $studid,
                            'levelid'              => $levelid,
                            'section_id'             => $sectionid,
                            'subject_id'           => $subjectid,
                            'date'            => $tdate,
                            'isCon'            => $isCon,
                            'deleted'           => 0,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        if($request->get('action') == 'late')
        {
            
            foreach($studids as $studid)
            {
                $checkifexists = DB::table('studentsubjectattendance')
                    ->where('levelid', $levelid)
                    ->where('section_id', $sectionid)
                    ->where('subject_id', $subjectid)
                    ->where('student_id', $studid)
                    ->where('date', $tdate)
                    ->where('deleted','0');

                    if($subjectid == 0)
                    {
                        $checkifexists = $checkifexists->where('isCon','mapeh')->first();
                    }else{
                        $checkifexists = $checkifexists->first();
                    }


                if($checkifexists)
                {
                    DB::table('studentsubjectattendance')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'isCon'            => $isCon,
                            'status'           => 'late',
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studentsubjectattendance')
                        ->insert([
                            'status'           => 'late',
                            'student_id'            => $studid,
                            'levelid'              => $levelid,
                            'section_id'             => $sectionid,
                            'subject_id'           => $subjectid,
                            'date'            => $tdate,
                            'isCon'            => $isCon,
                            'deleted'           => 0,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        if($request->get('action') == 'absent')
        {
            
            foreach($studids as $studid)
            {
                $checkifexists = DB::table('studentsubjectattendance')
                    ->where('levelid', $levelid)
                    ->where('section_id', $sectionid)
                    ->where('subject_id', $subjectid)
                    ->where('student_id', $studid)
                    ->where('date', $tdate)
                    ->where('deleted','0');

                if($subjectid == 0)
                {
                    $checkifexists = $checkifexists->where('isCon','mapeh')->first();
                }else{
                    $checkifexists = $checkifexists->first();
                }
                    // ->first();

                if($checkifexists)
                {
                    DB::table('studentsubjectattendance')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'isCon'            => $isCon,
                            'status'           => 'absent',
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studentsubjectattendance')
                        ->insert([
                            'status'           => 'absent',
                            'student_id'            => $studid,
                            'levelid'              => $levelid,
                            'section_id'             => $sectionid,
                            'subject_id'           => $subjectid,
                            'date'            => $tdate,
                            'isCon'            => $isCon,
                            'deleted'           => 0,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        if($request->get('action') == 'delete')
        {
            
            foreach($studids as $studid)
            {
                $checkifexists = DB::table('studentsubjectattendance')
                    ->where('levelid', $levelid)
                    ->where('section_id', $sectionid)
                    ->where('subject_id', $subjectid)
                    ->where('student_id', $studid)
                    ->where('date', $tdate)
                    ->where('deleted','0');
                if($subjectid == 0)
                {
                    $checkifexists = $checkifexists->where('isCon','mapeh')->first();
                }else{
                    $checkifexists = $checkifexists->first();
                }
                if($checkifexists)
                {
                    DB::table('studentsubjectattendance')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'deleted'           => '1',
                            'deletedby'           => auth()->user()->id,
                            'deleted'           => date('Y-m-d H:i:s'),
                        ]);
                }
            }
        }
    }
    public function updaterow(Request $request)
    {
        
        $studid          = $request->get('studid');
        $dates           = json_decode($request->get('dates'));
        $levelid         = $request->get('levelid');
        $sectionid        = $request->get('sectionid');
        $subjectid        = $request->get('subjectid');
        
        if($subjectid == 0)
        {
            $isCon = 'mapeh';
        }else{
            $isCon = null;
        }
        if($request->get('action') == 'present')
        {
            foreach($dates as $date)
            {
                $checkifexists = DB::table('studentsubjectattendance')
                    ->where('date',$date)
                    ->where('levelid', $levelid)
                    ->where('section_id', $sectionid)
                    ->where('subject_id', $subjectid)
                    ->where('student_id',$studid)
                    ->where('deleted','0');

                if($subjectid == 0)
                {
                    $checkifexists = $checkifexists->where('isCon','mapeh')->first();
                }else{
                    $checkifexists = $checkifexists->first();
                }
    
                if($checkifexists)
                {
                    DB::table('studentsubjectattendance')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'isCon'            => $isCon,
                            'status'           => 'present',
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studentsubjectattendance')
                        ->insert([
                            'status'           => 'present',
                            'student_id'            => $studid,
                            'levelid'              => $levelid,
                            'section_id'             => $sectionid,
                            'subject_id'           => $subjectid,
                            'date'            => $date,
                            'isCon'            => $isCon,
                            'deleted'           => 0,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        if($request->get('action') == 'late')
        {
            foreach($dates as $date)
            {
                $checkifexists = DB::table('studentsubjectattendance')
                    ->where('date',$date)
                    ->where('levelid', $levelid)
                    ->where('section_id', $sectionid)
                    ->where('subject_id', $subjectid)
                    ->where('student_id',$studid)
                    ->where('deleted','0');

                    if($subjectid == 0)
                    {
                        $checkifexists = $checkifexists->where('isCon','mapeh')->first();
                    }else{
                        $checkifexists = $checkifexists->first();
                    }
    
                if($checkifexists)
                {
                    DB::table('studentsubjectattendance')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'isCon'            => $isCon,
                            'status'           => 'late',
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studentsubjectattendance')
                        ->insert([
                            'status'           => 'late',
                            'student_id'            => $studid,
                            'levelid'              => $levelid,
                            'section_id'             => $sectionid,
                            'subject_id'           => $subjectid,
                            'date'            => $date,
                            'isCon'            => $isCon,
                            'deleted'           => 0,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        if($request->get('action') == 'absent')
        {
            foreach($dates as $date)
            {
                $checkifexists = DB::table('studentsubjectattendance')
                    ->where('date',$date)
                    ->where('levelid', $levelid)
                    ->where('section_id', $sectionid)
                    ->where('subject_id', $subjectid)
                    ->where('student_id',$studid)
                    ->where('deleted','0');

                    if($subjectid == 0)
                    {
                        $checkifexists = $checkifexists->where('isCon','mapeh')->first();
                    }else{
                        $checkifexists = $checkifexists->first();
                    }
    
                if($checkifexists)
                {
                    DB::table('studentsubjectattendance')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'isCon'            => $isCon,
                            'status'           => 'absent',
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studentsubjectattendance')
                        ->insert([
                            'status'           => 'absent',
                            'student_id'            => $studid,
                            'levelid'              => $levelid,
                            'section_id'             => $sectionid,
                            'subject_id'           => $subjectid,
                            'date'            => $date,
                            'isCon'            => $isCon,
                            'deleted'           => 0,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        if($request->get('action') == 'delete')
        {
            foreach($dates as $date)
            {
                $checkifexists = DB::table('studentsubjectattendance')
                    ->where('date',$date)
                    ->where('levelid', $levelid)
                    ->where('section_id', $sectionid)
                    ->where('subject_id', $subjectid)
                    ->where('student_id',$studid)
                    ->where('deleted','0');

                    if($subjectid == 0)
                    {
                        $checkifexists = $checkifexists->where('isCon','mapeh')->first();
                    }else{
                        $checkifexists = $checkifexists->first();
                    }
    
                if($checkifexists)
                {
                    DB::table('studentsubjectattendance')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'deleted'           => '1'
                        ]);
                }
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}