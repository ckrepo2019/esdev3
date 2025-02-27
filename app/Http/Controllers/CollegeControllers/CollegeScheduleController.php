<?php

namespace App\Http\Controllers\CollegeControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class CollegeScheduleController extends Controller
{
    public function new_get_sched_ajax(Request $request)
    {
    //    return $request->all();
        $teacher = auth()->user()->id;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $levelid = $request->get('gradelevel');

        // return $teacher;


        $schedule = DB::table('college_classsched')
        ->join('college_prospectus', 'college_classsched.subjectid', '=', 'college_prospectus.id')
        ->join('college_subjects','college_prospectus.subjectID','=','college_subjects.id')
        ->join('college_scheddetail', function($join){
            $join->on('college_classsched.id', '=', 'college_scheddetail.headerID');
            $join->where('college_scheddetail.deleted', 0);
        })
        ->leftjoin('days', 'college_scheddetail.day', '=', 'days.id')
        ->leftjoin('rooms', 'college_scheddetail.roomid', '=', 'rooms.id')
        ->join('college_sections', 'college_classsched.sectionid', '=', 'college_sections.id')
        ->join('college_courses', 'college_sections.courseID', '=', 'college_courses.id')
        ->join('gradelevel', function($join) use ($levelid) {
            $join->on('college_sections.yearid', '=', 'gradelevel.id');
            $join->where('gradelevel.deleted', 0);
         
        })
        ->when($levelid != 0, function ($query) use ($levelid) {
            $query->where('gradelevel.id', $levelid);
        })
        ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
        ->join('teacher', function($join) use ($teacher) {
            $join->on('college_instructor.teacherid', '=', 'teacher.id');
            $join->where('teacher.deleted', 0);
            $join->where('teacher.userid', $teacher);
        })
        ->leftjoin('college_ecr', 'college_classsched.ecr_template' , '=', 'college_ecr.id')
        ->where('college_classsched.syid', $syid)
        ->where('college_classsched.semesterid', $semid)
        ->where('college_classsched.deleted', 0)
        ->where('college_instructor.deleted', 0)
        ->groupBy('college_classsched.id')
        ->select(
            'college_classsched.id as schedid',
            'college_sections.sectionDesc',
            'college_sections.id as sectionid',
            'college_prospectus.subjDesc',
            'college_prospectus.subjCode',
            'college_prospectus.id as subjectid',
            DB::raw('CONCAT(college_subjects.subjCode ," - ", college_subjects.subjDesc) as subject'),
            'college_scheddetail.stime',
            'college_scheddetail.etime',
            DB::raw("CONCAT(DATE_FORMAT(college_scheddetail.stime, '%h:%i %p'), ' - ', DATE_FORMAT(college_scheddetail.etime, '%h:%i %p')) AS schedtime"),
            'rooms.roomname',
            'college_scheddetail.schedotherclass',
            DB::raw('GROUP_CONCAT(DISTINCT college_scheddetail.day ORDER BY college_scheddetail.id ASC) as daysid'),
            DB::raw("GROUP_CONCAT(DISTINCT SUBSTRING(days.description, 1, 3) ORDER BY college_scheddetail.day ASC SEPARATOR '/') AS days"),
            'gradelevel.levelname as yearDesc',
            'gradelevel.id as levelid',
            'college_courses.courseDesc',
            'college_ecr.ecrDesc'
            )
            ->get();


        $schedule->transform(function ($item) {
            $item->daysid = explode(',', $item->daysid); // Convert the comma-separated days string to an array
            return $item;
        });

        foreach($schedule as $item){
            $item->enrolled = DB::table('college_loadsubject')
            ->join('college_enrolledstud', 'college_loadsubject.studid', '=', 'college_enrolledstud.studid')
            ->where('college_enrolledstud.studstatus', '!=', 0)
            ->where('college_enrolledstud.sectionID', '=', $item->sectionid)
            ->where('college_loadsubject.schedid', '=', $item->schedid)
            ->where('college_loadsubject.deleted', 0)
            ->where('college_loadsubject.syid', $syid)
            ->where('college_loadsubject.semid', $semid)
            ->distinct()
            ->count();
        }                            

            
        return $schedule;
    }

    public function new_get_sched_ajax_print($syid, $semid)
    {
        $teacher = auth()->user()->id;



        $schedule = DB::table('college_classsched')
        ->join('college_prospectus', 'college_classsched.subjectid', '=', 'college_prospectus.id')
        ->join('college_subjects','college_prospectus.subjectID','=','college_subjects.id')
        ->join('college_scheddetail', function($join){
            $join->on('college_classsched.id', '=', 'college_scheddetail.headerID');
            $join->where('college_scheddetail.deleted', 0);
        })
        ->leftjoin('days', 'college_scheddetail.day', '=', 'days.id')
        ->leftjoin('rooms', 'college_scheddetail.roomid', '=', 'rooms.id')
        ->join('college_sections', 'college_classsched.sectionid', '=', 'college_sections.id')
        ->join('college_year', 'college_sections.yearid', '=', 'college_year.levelid')
        ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
        ->join('teacher', function($join) use ($teacher) {
            $join->on('college_instructor.teacherid', '=', 'teacher.id');
            $join->where('teacher.deleted', 0);
            $join->where('teacher.userid', $teacher);
        })
        ->where('college_classsched.syid', $syid)
        ->where('college_classsched.semesterid', $semid)
        ->where('college_classsched.deleted', 0)
        ->where('college_instructor.deleted', 0)
        ->select(
            'college_classsched.id as schedid',
            'college_sections.sectionDesc',
            'college_sections.id as sectionid',
            'college_subjects.subjDesc',
            'college_subjects.subjCode',
            'college_subjects.id as subjectid',
            DB::raw('CONCAT(college_subjects.subjCode ," - ", college_subjects.subjDesc) as subject'),
            'college_scheddetail.stime',
            'college_scheddetail.etime',
            DB::raw("CONCAT(DATE_FORMAT(college_scheddetail.stime, '%h:%i %p'), ' - ', DATE_FORMAT(college_scheddetail.etime, '%h:%i %p')) AS schedtime"),
            'rooms.roomname',
            'college_scheddetail.schedotherclass',
            'days.description',
            'college_year.yearDesc',

            )
            ->groupBy('college_classsched.id')
            ->get();
        
        $school_year = DB::table('sy')
        ->where('id', $syid)
        ->select('sy.sydesc')
        ->first();

        $semester = DB::table('semester')
        ->where('id', $semid)
        ->select('semester.semester')
        ->first();
        // return $schedule;
        // $schedule->transform(function ($item) {
        //     $item->daysid = explode(',', $item->daysid); // Convert the comma-separated days string to an array
        //     return $item;
        // });


        return view('ctportal.pages.schedule.schedulepdf', compact('schedule', 'school_year', 'semester'));
    }

    public function add_to_sched(Request $request){
        $schedid = $request->get('schedid');
        $teacher = auth()->user()->id;

        $teacherid = DB::table('teacher')
        ->where('userid', $teacher)
        ->select('teacher.id')
        ->get();

        $teacher = $teacherid[0]->id;
        
        DB::table('college_instructor')
        ->insert([
            'classschedid' => $schedid,
            'teacherid' => $teacher,
            'term' => 1,
            'createdby' => auth()->user()->id,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);
    }

    public function delete_from_sched(Request $request){
        $schedid = $request->get('schedid');
        $teacher = auth()->user()->id;

        $teacherid = DB::table('teacher')
        ->where('userid', $teacher)
        ->select('teacher.id')
        ->get();

        $teacher = $teacherid[0]->id;
        
        DB::table('college_instructor')
        ->where('classschedid', $schedid)
        ->where('teacherid', $teacher)
        ->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);
    }
}
