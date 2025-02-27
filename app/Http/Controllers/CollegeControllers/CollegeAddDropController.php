<?php

namespace App\Http\Controllers\CollegeControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class CollegeAddDropController extends Controller
{
    public function index()
    {
        $colleges = DB::table('college_colleges')
            ->where('deleted','0')
            ->get();

        return view('registrar.college.index')->with('colleges', $colleges);
    }
    public function selectcourse(Request $request)
    {
        $courses = DB::table('college_courses')
            ->where('deleted','0')
            ->where('collegeid',$request->get('collegeid'))
            ->get();
        return view('registrar.college.courses')
            ->with('courses', $courses)
            ->with('collegename', $request->get('collegename'));
    }
    public function viewstudents(Request $request)
    {
        // return $request->all();
        $students = DB::table('college_enrolledstud')   
            ->select(
                'studinfo.id',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.picurl',
                'college_year.yearDesc'
            )
            ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
            ->join('college_year','college_enrolledstud.yearLevel','=','college_year.levelid')
            ->join('sy','college_enrolledstud.syid','=','sy.id')
            ->where('college_enrolledstud.deleted','0')
            ->whereIn('college_enrolledstud.studstatus',[1,2,4])
            ->where('college_enrolledstud.courseid', $request->get('courseid'))
            ->where('sy.isactive','1')
            ->orderBy('lastname','asc')
            ->get();
            
        return view('registrar.college.viewstudents')
            ->with('students', $students)
            ->with('collegename', $request->get('collegename'))
            ->with('coursename', $request->get('coursename'))
            ->with('courseid', $request->get('courseid'));
    }
    public function viewschedule(Request $request)
    {
        $studinfo = DB::table('college_enrolledstud')
            ->select(
                'studinfo.id',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'college_enrolledstud.courseid',
                'college_enrolledstud.yearLevel as yearid',
                'college_enrolledstud.sectionID as sectionid',
                'college_enrolledstud.studstatus'
                )
            ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
            ->join('sy','college_enrolledstud.syID','=','sy.id')
            ->join('semester','college_enrolledstud.semID','=','semester.id')
            ->where('studid',$request->get('studid'))
            ->where('sy.isactive','1')
            ->where('semester.isactive','1')
            ->first();

        $studscheds = Db::table('college_studsched')
            ->select(
                'college_classsched.id',
                'college_classsched.sectionID',
                'college_classsched.subjectID',
                'college_classsched.teacherID',
                'college_studsched.dropped'
            )
            ->join('college_classsched','college_studsched.schedid','=','college_classsched.id')
            ->join('sy','college_classsched.syID','=','sy.id')
            ->join('semester','college_classsched.semesterID','=','semester.id')
            ->where('college_studsched.studid', $request->get('studid'))
            ->where('college_studsched.deleted','0')
            ->where('college_classsched.deleted','0')
            ->where('sy.isactive','1')
            ->where('semester.isactive','1')
            ->distinct()
            ->get();

    
            
        $schedule = array();
        if(count($studscheds) > 0)
        {
            foreach($studscheds as $studsched)
            {
                $teacher = DB::table('teacher')
                    ->select(
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix'
                        )
                    ->where('id', $studsched->teacherID)
                    ->first();

                $section = DB::table('college_sections')
                    ->select(
                        'id as sectionid',
                        'sectionDesc as sectionname'
                        )
                    ->where('id', $studsched->sectionID)
                    ->where('deleted','0')
                    ->first();

                $subject = DB::table('college_prospectus')
                    ->select(
                        'college_prospectus.subjectID as subjectid',
                        'college_prospectus.subjCode as subjectcode',
                        'college_prospectus.subjDesc as subjectname'
                        )
                    ->where('college_prospectus.id', $studsched->subjectID)
                    ->join('semester','college_prospectus.semesterID','=','semester.id')
                    ->where('college_prospectus.deleted','0')
                    // ->where('college_prospectus.yearID',$studinfo->yearid)
                    ->where('college_prospectus.courseid',$studinfo->courseid)
                    ->where('semester.isactive','1')
                    ->first();


                if(count(collect($subject)) > 0)
                {
                    $units = DB::table('college_prospectus')
                            ->select('lecunits','labunits')
                            ->join('semester','college_prospectus.semesterID','=','semester.id')
                            // ->where('college_prospectus.yearID',$studinfo->yearid)
                            ->where('college_prospectus.id',$studsched->subjectID)
                            ->where('college_prospectus.courseid',$studinfo->courseid)
                            ->where('semester.isactive','1')
                            ->first();
    
                    $scheddetail =Db::table('college_scheddetail')
                            ->select(
                                'college_scheddetail.id as scheddetailid',
                                'stime',
                                'etime',
                                'rooms.roomname',
                                'days.description as day'
                            )
                            ->leftJoin('rooms','college_scheddetail.roomID','=','rooms.id')
                            ->leftJoin('days','college_scheddetail.day','=','days.id')
                            ->where('college_scheddetail.headerID', $studsched->id)
                            ->where('college_scheddetail.deleted','0')
                            ->orderBy('stime','asc')
                            ->get();
    
                    if(count(collect($units)) == 0)
                    {
                        $units = null;
                    }else{
                        $units = $units->lecunits + $units->labunits;
                    }
                    if(count($scheddetail) > 0)
                    {
                        foreach($scheddetail as $sched)
                        {
                            $sched->stime = date('h:i A', strtotime($sched->stime));
                            $sched->etime = date('h:i A', strtotime($sched->etime));
                        }
                    }
                    array_push($schedule,(object)array(
                        'subjectinfo'   => $subject,
                        'units'         => $units,
                        'schedules'     => $scheddetail,
                        'teacher'       => $teacher,
                        'dropped'       => $studsched->dropped
                    ));
                    
                }
            }
        }

        return view('registrar.college.viewschedule')
            ->with('schedules', $schedule)
            ->with('studinfo',$studinfo);
    }
    public function dropsubject(Request $request)
    {
        // return $request->all();
        date_default_timezone_set('Asia/Manila');
        $explode = explode(' - ',$request->get('ids'));
        $subjectid = $explode[0];
        $studid = $explode[1];

        // return $studid;

        $studschedid = Db::table('college_studsched')
            ->select('college_studsched.schedid')
            ->join('college_classsched','college_studsched.schedid','=','college_classsched.id')
            ->join('college_prospectus','college_classsched.subjectID','=','college_prospectus.id')
            ->join('sy','college_classsched.syID','=','sy.id')
            ->join('semester','college_classsched.semesterID','=','semester.id')
            ->where('college_studsched.studid', $studid)
            ->where('college_prospectus.subjectID', $subjectid)
            ->where('college_studsched.deleted','0')
            ->where('college_prospectus.deleted','0')
            ->where('college_classsched.deleted','0')
            ->where('sy.isactive','1')
            ->where('semester.isactive','1')
            ->first()->schedid;

        $studsched = Db::table('college_studsched')
            ->where('college_studsched.studid', $studid)
            ->where('college_studsched.schedid', $studschedid)
            ->update([
                'dropped'   => '1',
                'deleted'   => '1',
                'droppedby' => auth()->user()->id,
                'droppeddatetime'   => date('Y-m-d H:i:s')
            ]);
            
        if($studsched)
        {
            return 1;
        }else{
            return 0;
        }
    }
    public function getsubjects(Request $request)
    {
        $syid = DB::table('sy')
            ->where('isactive','1')
            ->first()->id;

        $semester = DB::table('semester')
            ->where('isactive','1')
            ->first()->id;
            
        $studinfo = DB::table('college_enrolledstud')
            ->select(
                'courseid',
                'yearLevel as yearid',
                'sectionID as sectionid',
                'studstatus'
                )
            ->where('studid',$request->get('studid'))
            ->where('college_enrolledstud.syid',$syid)
            ->where('college_enrolledstud.semid',$semester)
            ->first();

        $prospectussubjects = DB::table('college_prospectus')
            ->select(
                'subjectID as subjectid',
                'subjCode as subjectcode',
                'subjDesc as subjectname'
            )
            ->where('courseID',$studinfo->courseid)
            ->where('semesterID',$semester)
            ->where('deleted',0)
            ->get();

        return view('registrar.college.include.viewsubjectselection')
            ->with('subjects',$prospectussubjects);
    }
    public function getavailablescheds(Request $request)
    {
        //get the section and schedule offering this subjectid
        $syid = DB::table('sy')
            ->where('isactive','1')
            ->first()->id;

        $semester = DB::table('semester')
            ->where('isactive','1')
            ->first()->id;
            
        $studinfo = DB::table('college_enrolledstud')
            ->select(
                'courseid',
                'yearLevel as yearid',
                'sectionID as sectionid',
                'studstatus'
                )
            ->where('studid',$request->get('studid'))
            ->where('college_enrolledstud.syid',$syid)
            ->where('college_enrolledstud.semid',$semester)
            ->first();
        
        $scheds = DB::table('college_classsched')
            ->select(
                'college_classsched.id',
                // 'college_scheddetail.id as scheddetailid',
                // 'college_sections.id as sectionid',
                'college_sections.sectionDesc as sectionname',
                'college_prospectus.subjectID as subjectid',
                'college_prospectus.subjCode as subjectcode',
                'college_prospectus.subjDesc as subjectname',
                // 'days.description as day',
                // 'college_scheddetail.stime',
                // 'college_scheddetail.etime',
                // 'rooms.roomname',
                'teacher.lastname',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.suffix'
            )
            ->join('college_sections','college_classsched.sectionID','=','college_sections.id')
            ->join('college_prospectus','college_classsched.subjectID','=','college_prospectus.id')
            // ->join('college_scheddetail','college_classsched.id','=','college_scheddetail.headerID')
            // ->leftJoin('days','college_scheddetail.day','=','days.id')
            // ->leftJoin('rooms','college_scheddetail.roomID','=','rooms.id')
            ->leftJoin('teacher','college_classsched.teacherID','=','teacher.id')
            ->where('college_prospectus.subjectID', $request->get('subjectid'))
            ->where('college_classsched.semesterID', $semester)
            ->where('college_classsched.syID', $syid)
            ->where('college_classsched.deleted','0')
            ->where('college_sections.deleted','0')
            // ->where('college_prospectus.yearID',$studinfo->yearid)
            ->where('college_prospectus.courseid',$studinfo->courseid)
            ->where('college_prospectus.deleted','0')
            ->distinct()
            ->get();

        // return $scheds;
            
        if(count($scheds) > 0)
        {
            foreach($scheds as $sched)
            {
                $checkschedifexists = DB::table('college_studsched')
                    ->where('studid',$request->get('studid'))
                    ->where('schedid', $sched->id)
                    ->where('deleted','0')
                    ->get();

                if(count($checkschedifexists) == 0)
                {
                    $sched->status = 0;
                }else{
                    if($checkschedifexists[0]->dropped == 0)
                    {
                        $sched->status = 1;
                    }else{
                        $sched->status = 2;
                    }
                }
                $schedules = DB::table('college_scheddetail')
                    ->select(
                        'college_scheddetail.id',
                        'days.description as day',
                        'college_scheddetail.stime',
                        'college_scheddetail.etime',
                        'rooms.roomname'
                    )
                    ->where('college_scheddetail.headerID', $sched->id)
                    ->leftJoin('days','college_scheddetail.day','=','days.id')
                    ->leftJoin('rooms','college_scheddetail.roomID','=','rooms.id')
                    ->where('college_scheddetail.deleted','0')
                    ->get();
                    
                if(count($schedules) > 0)
                {
                    foreach($schedules as $schedule)
                    {
                        
                        $schedule->stime = date('h:i A', strtotime($schedule->stime));
                        $schedule->etime = date('h:i A', strtotime($schedule->etime));
                    }
                }
                if($sched->middlename == null)
                {
                    $sched->teachername = $sched->lastname.', '.$sched->firstname.' '.$sched->suffix;
                }else{
                    $sched->teachername = $sched->lastname.', '.$sched->firstname.' '.$sched->middlename[0].'.'.$sched->suffix;
                }
                $sched->schedules = $schedules;
            }
        }

        return view('registrar.college.include.viewavailablesched')->with('scheds',$scheds);
    }
    public function addschedule(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        $addsched = DB::table('college_studsched')
            ->insert([
                'studid'        => $request->get('studid'),
                'schedid'       => $request->get('classschedid'),
                'isapprove'     => 0,
                'createdby'     => auth()->user()->id,
                'createddatetime'   => date('Y-m-d H:i:s')
            ]);

        if($addsched)
        {
            return 1;
        }else{
            return 0;
        }
        
    }
}




        // //get sections in this course -> semester - > syid
        // $studinfo = DB::table('college_enrolledstud')
        //     ->select(
        //         'courseid',
        //         'yearLevel as yearid',
        //         'sectionID as sectionid',
        //         'studstatus'
        //         )
        //     ->where('studid',$request->get('studid'))
        //     ->where('college_enrolledstud.syid',$syid)
        //     ->where('college_enrolledstud.semid',$semester)
        //     ->first();

        // $sections = DB::table('college_sections')
        //     ->select(
        //         'id',
        //         'sectionDesc as sectionname'
        //     )
        //     ->where('college_sections.syID',$syid)
        //     ->where('college_sections.semesterID',$semester)
        //     ->where('college_sections.yearID',$studinfo->yearid)
        //     ->where('college_sections.courseID',$studinfo->courseid)
        //     ->get();
        
        // $schedsexists = array();
        // $studentscheds = DB::table('college_studsched')
        //     ->select('schedid')
        //     ->where('dropped','0')
        //     ->where('deleted','0')
        //     ->where('studid',$request->get('studid'))
        //     ->get();
            
        // if(count($studentscheds)>0)
        // {
        //     foreach($studentscheds as $studentsched)
        //     {
        //         array_push($schedsexists,$studentsched->schedid);
        //     }
        // }
        // $scheds = array();
        // if(count($sections)>0)
        // {
        //     foreach($sections as $section)
        //     {
        //         $sectionsched = array();
        //         $subjects = DB::table('college_classsched')
        //             ->select(
        //                 'college_classsched.id as classschedid',
        //                 'college_classsched.subjectID',
        //                 'college_subjects.subjCode as subjectcode',
        //                 'college_subjects.subjDesc as subjectname',
        //                 'college_classsched.teacherID as teacherid'
        //             )
        //             ->join('college_subjects','college_classsched.subjectID','=','college_subjects.id')
        //             ->where('college_classsched.sectionID', $section->id)
        //             ->where('college_classsched.semesterID',$semester)
        //             ->where('college_classsched.syID',$syid)
        //             ->where('college_classsched.deleted','0')
        //             ->where('college_subjects.deleted','0')
        //             ->get();

        //         if(count($subjects)>0)
        //         {
        //             foreach($subjects as $subject)
        //             {
        //                 $teacher = DB::table('teacher')
        //                     ->select(
        //                         'lastname',
        //                         'firstname',
        //                         'middlename',
        //                         'suffix'
        //                         )
        //                     ->where('id', $subject->teacherid)
        //                     ->first();
        
        //                 $units = DB::table('college_prospectus')
        //                     ->select('lecunits','labunits')
        //                     ->join('semester','college_prospectus.semesterID','=','semester.id')
        //                     ->where('college_prospectus.subjectID',$subject->subjectID)
        //                     ->where('semester.isactive','1')
        //                     ->first();

        //                 $scheddetail = DB::table('college_scheddetail')
        //                     ->select(
        //                         'college_scheddetail.id as scheddetailid',
        //                         'stime',
        //                         'etime',
        //                         'rooms.roomname',
        //                         'days.description as day'
        //                     )
        //                     ->leftJoin('rooms','college_scheddetail.roomID','=','rooms.id')
        //                     ->leftJoin('days','college_scheddetail.day','=','days.id')
        //                     ->where('college_scheddetail.headerID', $subject->classschedid)
        //                     ->where('college_scheddetail.deleted','0')
        //                     ->orderBy('stime','asc')
        //                     ->get();
        //                 if (in_array($subject->classschedid, $schedsexists)) {
        //                     $status = 1;
        //                 }else{
        //                     $status = 0;
        //                 }
        //                 if(count(collect($units)) == 0)
        //                 {
        //                     $units = null;
        //                 }else{
        //                     $units = $units->lecunits + $units->labunits;
        //                 }
        //                 if(count($scheddetail) > 0)
        //                 {
        //                     foreach($scheddetail as $sched)
        //                     {
        //                         $sched->stime = date('h:i A', strtotime($sched->stime));
        //                         $sched->etime = date('h:i A', strtotime($sched->etime));
        //                     }
        //                     array_push($sectionsched, (object)array(
        //                         'classschedid'  => $subject->classschedid,
        //                         'subjectinfo'   => $subject,
        //                         'teacherinfo'   => $teacher,
        //                         'schedules'     => $scheddetail,
        //                         'units'         => $units,
        //                         'status'        => $status
        //                     ));
        //                 }
        //             }
        //         }
        //         if(count($sectionsched)>0)
        //         {
        //             array_push($scheds,(object)array(
        //                 'sectioninfo'   => $section,
        //                 'subjects'      => $sectionsched
        //             ));
        //         }
        //     }
        // }
        // return view('registrar.college.include.viewschedselection')
        //     ->with('scheds',$scheds);
        