<?php

namespace App\Http\Controllers\DeanControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Controllers\CollegeSchedConflicts\SchedConflict;

class DeanGradeSummaryController extends Controller
{


    public static function college_studentListGradeSummary($syid = '', $semid = '', $course = '', $academic = '', $section = '')
    {

            $students = DB::table('studinfo')
            ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
            ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
            ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            ->join('college_sections', 'college_loadsubject.sectionID', '=', 'college_sections.id')
            ->join('college_classsched', 'college_loadsubject.subjectID', '=', 'college_classsched.subjectID')
            ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
            ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
            // ->join('college_year', 'college_enrolledstud.yearLevel', '=', 'college_year.levelid')
            ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
            // ->join('epermitdetails', 'college_enrolledstud.studid', '=', 'epermitdetails.studid')
            ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
            ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
            ->where('college_loadsubject.syid', $syid)
            ->where('college_loadsubject.semid', $semid)
            ->where('college_loadsubject.sectionID', $section)
            ->where('college_courses.id', $course)
            ->where('college_enrolledstud.yearLevel', $academic)
            // ->where('college_loadsubject.schedid', $schedid)
            ->where('college_prospectus.deleted', 0)
            ->where('college_loadsubject.deleted', 0)
            ->where('college_enrolledstud.deleted', 0)
            ->select(
                DB::raw("CONCAT(studinfo.lastname, ', ', studinfo.firstname, ' ', IFNULL(studinfo.middlename, ''), ' ', IFNULL(studinfo.suffix, '')) AS student_name"),
                'studinfo.id',
                'studinfo.firstname',
                'studinfo.gender',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.street',
                'studinfo.barangay',
                'studinfo.city',
                'studinfo.province',
                'studinfo.contactno',
                'studinfo.sid',
                'studinfo.semail',
                'college_sections.id AS sectionId',
                'college_courses.courseabrv',
                'college_courses.courseDesc',
                'college_courses.id AS courseid',
                'gradelevel.levelname',
                'studentstatus.description AS student_status',
                'college_enrolledstud.studstatus',
                'college_enrolledstud.yearLevel AS levelid',
                'college_subjects.subjCode',
                'college_subjects.subjDesc',
                'college_prospectus.subjectID',
            )
            ->groupBy(
                'college_loadsubject.studid'
            )
            ->get();
            $groupedSubjects = DB::table('studinfo')
                ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
                ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
                ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
                ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                ->join('college_sections', 'college_loadsubject.sectionID', '=', 'college_sections.id')
                ->join('college_classsched', 'college_loadsubject.subjectID', '=', 'college_classsched.subjectID')
                ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
                // ->join('college_studentprospectus', 'studinfo.sid', '=', 'college_studentprospectus.studid')
                ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
                ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
                ->where('college_loadsubject.syid', $syid)
                ->where('college_loadsubject.semid', $semid)
                ->where('college_loadsubject.sectionID', $section)
                ->where('college_courses.id', $course)
                ->where('college_enrolledstud.yearLevel', $academic)
                // ->where('college_prospectus.deleted', 0)
                ->where('college_loadsubject.deleted', 0)
                ->where('college_enrolledstud.deleted', 0)
                ->select(               
                    // 'studinfo.id',             
                    'college_subjects.subjCode',
                    'college_subjects.subjDesc',
                    'college_prospectus.id as subjectID',  
                
                )
                ->groupBy(
                    'college_loadsubject.subjectID'
                )
                ->get();

            $studentsgrades = DB::table('college_studentprospectus')
            ->join('studinfo', 'college_studentprospectus.studid', '=', 'studinfo.sid')
            ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
            ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
            ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
            ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
            // ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
            ->where('college_loadsubject.syid', $syid)
            ->where('college_loadsubject.semid', $semid)
            ->where('college_loadsubject.sectionID', $section)
            ->where('college_courses.id', $course)
            ->where('college_enrolledstud.yearLevel', $academic)
            // ->where('college_prospectus.deleted', 0)
            ->where('college_loadsubject.deleted', 0)
            ->where('college_enrolledstud.deleted', 0)
            ->select(               
                'studinfo.id',   
                'college_studentprospectus.finalstatus', 
                'college_studentprospectus.fg',
                'college_studentprospectus.prospectusID',  
                'college_subjects.subjCode',
                'college_subjects.subjDesc',                          
            )         
            ->get();

        // Count the number of students
        $studentCount = $students->count();

        return response()->json([
            'students' => $students,
            'groupedSubjects' => $groupedSubjects,
            'studentCount' => $studentCount,
            'studentsgrades' => $studentsgrades
        ]);
    }

    // public static function college_studentListGradeSummary($syid = '', $semid = '', $course = '', $academic = '', $section = '')
    // {

    //         $students = DB::table('studinfo')
    //         ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
    //         ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
    //         ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
    //         ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
    //         ->join('college_sections', 'college_loadsubject.sectionID', '=', 'college_sections.id')
    //         ->join('college_classsched', 'college_loadsubject.subjectID', '=', 'college_classsched.subjectID')
    //         ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
    //         ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
    //         // ->join('college_year', 'college_enrolledstud.yearLevel', '=', 'college_year.levelid')
    //         ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
    //         // ->join('epermitdetails', 'college_enrolledstud.studid', '=', 'epermitdetails.studid')
    //         ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
    //         ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
    //         ->where('college_loadsubject.syid', $syid)
    //         ->where('college_loadsubject.semid', $semid)
    //         ->where('college_loadsubject.sectionID', $section)
    //         ->where('college_courses.id', $course)
    //         ->where('college_enrolledstud.yearLevel', $academic)
    //         // ->where('college_loadsubject.schedid', $schedid)
    //         ->where('college_prospectus.deleted', 0)
    //         ->where('college_loadsubject.deleted', 0)
    //         ->where('college_enrolledstud.deleted', 0)
    //         ->select(
    //             DB::raw("CONCAT(studinfo.lastname, ', ', studinfo.firstname, ' ', IFNULL(studinfo.middlename, ''), ' ', IFNULL(studinfo.suffix, '')) AS student_name"),
    //             'studinfo.id',
    //             'studinfo.firstname',
    //             'studinfo.gender',
    //             'studinfo.lastname',
    //             'studinfo.middlename',
    //             'studinfo.suffix',
    //             'studinfo.street',
    //             'studinfo.barangay',
    //             'studinfo.city',
    //             'studinfo.province',
    //             'studinfo.contactno',
    //             'studinfo.sid',
    //             'studinfo.semail',
    //             'college_sections.id AS sectionId',
    //             'college_courses.courseabrv',
    //             'college_courses.courseDesc',
    //             'college_courses.id AS courseid',
    //             'gradelevel.levelname',
    //             'studentstatus.description AS student_status',
    //             'college_enrolledstud.studstatus',
    //             'college_enrolledstud.yearLevel AS levelid',
    //             'college_subjects.subjCode',
    //             'college_subjects.subjDesc',
    //             'college_prospectus.subjectID',
    //         )
    //         ->groupBy(
    //             'college_loadsubject.studid'
    //         )
    //         ->get();
    //         $groupedSubjects = DB::table('studinfo')
    //             ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
    //             ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
    //             ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
    //             ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
    //             ->join('college_sections', 'college_loadsubject.sectionID', '=', 'college_sections.id')
    //             ->join('college_classsched', 'college_loadsubject.subjectID', '=', 'college_classsched.subjectID')
    //             ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
    //             // ->join('college_studentprospectus', 'studinfo.sid', '=', 'college_studentprospectus.studid')
    //             ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
    //             ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
    //             ->where('college_loadsubject.syid', $syid)
    //             ->where('college_loadsubject.semid', $semid)
    //             ->where('college_loadsubject.sectionID', $section)
    //             ->where('college_courses.id', $course)
    //             ->where('college_enrolledstud.yearLevel', $academic)
    //             // ->where('college_prospectus.deleted', 0)
    //             ->where('college_loadsubject.deleted', 0)
    //             ->where('college_enrolledstud.deleted', 0)
    //             ->select(               
    //                 // 'studinfo.id',             
    //                 'college_subjects.subjCode',
    //                 'college_subjects.subjDesc',
    //                 'college_prospectus.subjectID',  
                
    //             )
    //             ->groupBy(
    //                 'college_loadsubject.subjectID'
    //             )
    //             ->get();

    //         $studentsgrades = DB::table('college_studentprospectus')

    //         ->join('studinfo', 'college_studentprospectus.studid', '=', 'studinfo.sid')
    //         ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
    //         ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
    //         ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
    //         ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
    //         ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
    //         ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
    //         // ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
    //         ->where('college_loadsubject.syid', $syid)
    //         ->where('college_loadsubject.semid', $semid)
    //         ->where('college_loadsubject.sectionID', $section)
    //         ->where('college_courses.id', $course)
    //         ->where('college_enrolledstud.yearLevel', $academic)
    //         // ->where('college_prospectus.deleted', 0)
    //         ->where('college_loadsubject.deleted', 0)
    //         ->where('college_enrolledstud.deleted', 0)
    //         ->select(               
    //             'studinfo.id',   
    //             'college_studentprospectus.finalstatus', 
    //             'college_studentprospectus.fg',
    //             'college_studentprospectus.prospectusID',  
    //             'college_subjects.subjCode',
    //             'college_subjects.subjDesc',  

                        
    //         )         
    //         ->get();

    //     // Count the number of students
    //     $studentCount = $students->count();

    //     return response()->json([
    //         'students' => $students,
    //         'groupedSubjects' => $groupedSubjects,
    //         'studentCount' => $studentCount,
    //         'studentsgrades' => $studentsgrades
    //     ]);
    // }
   
    public function get_acadprogid(Request $request){
        $course = $request->course;

        return response()->json(DB::table('college_colleges')
            ->join('college_courses', function($join) use($course){
                $join->on('college_colleges.id','=', 'college_courses.collegeid');
                $join->where('college_courses.id',$course);
            })
            ->where('college_colleges.deleted', 0)
            ->select('college_colleges.acadprogid')
            ->first());
    }
    public function get_yearlevel(Request $request){
        $usertype = auth()->user()->type;
        $userid = auth()->user()->id;


        if($usertype == 3){
            return DB::table('gradelevel')
            ->where('deleted', 0)
            ->where('id', '>=', 17)
            ->select('id','levelname')
            ->get();
        }else{
            $acadprog = DB::table('teacher')
            ->where('teacher.userid', $userid)
            ->where('teacher.deleted', 0)
            ->join('teacheracadprog', 'teacher.id', '=', 'teacheracadprog.teacherid')
            ->where('teacheracadprog.deleted', 0)
            ->select('teacheracadprog.acadprogid')
            ->get();

            $acads = [];
            foreach($acadprog as $acad)
            {
                array_push($acads, $acad->acadprogid);
            }

            return DB::table('gradelevel')
            ->where('deleted', 0)
            ->whereIn('acadprogid', $acads)
            ->select('id','levelname')
            ->get();
        }
    }


    public function get_sections(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semester');
        $course = $request->get('course');
        $academic = $request->get('academic');

        $user = auth()->user()->id;
        $usertype = auth()->user()->type;
        if(Session::get('currentPortal') == 14 || Session::get('currentPortal') == 3){
            $collegeid = DB::table('teacher')
                ->join('teacherdean', 'teacherdean.teacherid', '=', 'teacher.id')
                ->where('teacher.userid', $user)
                ->where('teacher.deleted', 0)
                ->where('teacherdean.deleted', 0)
                ->pluck('teacherdean.collegeid')
                ->toArray();
        }else if(Session::get('currentPortal') == 16){
            $collegeid = DB::table('teacher')
                ->join('teacherprogramhead', 'teacherprogramhead.teacherid', '=', 'teacher.id')
                ->join('college_courses', 'teacherprogramhead.courseid', '=', 'college_courses.id')
                // ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
                ->where('teacher.userid', $user)
                ->where('teacher.deleted', 0)
                ->where('teacherprogramhead.deleted', 0)
                ->pluck('college_courses.collegeid')
                ->toArray();
        }

        
        
        $sections = DB::table('college_sections')
            ->where('college_sections.syID', $syid)
            // ->where('college_sections.semesterID', $semid)
            ->when($usertype != 3, function ($query) use ($collegeid) {
               return $query->whereIn('college_sections.collegeID',$collegeid);
            })
            ->when($academic, function ($query) use ($academic) {
                return $query->where('college_sections.yearID', $academic);
            })
            ->when ($course, function ($query) use ($course) {
                return $query->where('college_sections.courseID', $course);
            })
            ->when($semid, function ($query) use ($semid){
                return $query->where('college_sections.semesterID', $semid);
            })
            ->join('college_courses', 'college_sections.courseID', 'college_courses.id')
            ->join('gradelevel', 'college_sections.yearID', 'gradelevel.id')
            ->join('college_colleges', 'college_sections.collegeID', 'college_colleges.id')
            // ->where('college_sections.courseID', $course)
            ->where('college_sections.deleted', 0)
            ->orderBy('college_sections.id', 'desc')
            ->select(
                'college_sections.id',
                'college_sections.sectionDesc',
                'college_sections.courseID',
                'college_courses.courseDesc',
                'college_courses.courseabrv',
                'gradelevel.id as yearID',
                'gradelevel.levelname as yearDesc',
                'college_colleges.acadprogid'
            )
            ->get();
        return $sections;
    }

    
}
