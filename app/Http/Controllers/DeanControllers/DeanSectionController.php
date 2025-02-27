<?php

namespace App\Http\Controllers\DeanControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Controllers\CollegeSchedConflicts\SchedConflict;

class DeanSectionController extends Controller
{
    public  function add_section(Request $request)
    {

        $request->validate([
            'section_name' => 'required',
            'course' => 'required',
            'academic' => 'required',
        ],
        [
            'section_name.required' => 'Section name is required.',
            'course.required' => 'Course is required.',
            'academic.required' => 'Academic Level is required.',
        ]);
    
        

        

        $id = $request->id;
        $syid = $request->syid;
        $semid = $request->semester;
        $academic = $request->academic;
        $course = $request->course;
        $sectionDesc = $request->section_name;
        $college = DB::table('college_courses')->where('id', $course)->select('collegeid')->first();
        $exist = DB::table('college_sections')
            ->where('syID', $syid)
            ->where('sectionDesc', $sectionDesc)
            ->where('deleted', '0')
            ->first();
        
            if ($id == 0) {
                if ($exist == null) {
                DB::table('college_sections')->insert([
                    'syID' => $syid,
                    'semesterID' => $semid,
                    'collegeID' => $college->collegeid,
                    'yearID' => $academic,
                    'courseID' => $course,
                    'sectionDesc' => $sectionDesc,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                ]);
                }else{
                    return 'Section Already Exists';
                }
            } else {

                $collegeID  = DB::table('college_courses')
                    ->where('id', $course)
                    ->value('collegeid');
        

                DB::table('college_sections')
                    ->where('id', $id)
                    ->update([
                        'courseID' => $course,
                        'sectionDesc' => $sectionDesc,
                        'yearID' => $academic,
                        'collegeID' => $collegeID,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    ]);
            }
        
        

    }
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

    public function get_section(Request $request)
    {
        $sections = DB::table('college_sections')
            ->join('college_courses', 'college_sections.courseID', 'college_courses.id')
            ->join('gradelevel', 'college_sections.yearID', 'gradelevel.id')
            ->where('college_sections.deleted', 0)
            ->where('college_sections.id', $request->id)
            ->select(
                'college_sections.id',
                'college_sections.sectionDesc',
                'college_courses.courseDesc',
                'college_sections.courseID',
                'gradelevel.levelname as yearDesc',
                'gradelevel.id as yearID',
            )
            ->first();
        return response()->json($sections);
    }

    public function get_section_list(Request $request){
        $user = auth()->user()->id;
        $usertype = auth()->user()->type;
        
        $collegeid = DB::table('teacher')
        ->join('teacherdean', 'teacherdean.teacherid', '=', 'teacher.id')
        ->where('teacher.userid', $user)
        ->where('teacher.deleted', 0)
        ->where('teacherdean.deleted', 0)
        ->pluck('teacherdean.collegeid')
        ->toArray();

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sections = DB::table('college_sections')
        ->join('college_courses', 'college_sections.courseID', 'college_courses.id')
        ->join('college_year', 'college_sections.yearID', 'college_year.levelid')
        ->when($usertype != 3, function ($query) use ($collegeid) {
            return $query->whereIn('college_sections.collegeID',$collegeid);
         })
        ->where('college_sections.syID', $syid)
        ->where('college_sections.semesterID', $semid)
        ->where('college_sections.deleted', 0)
        ->select(
            'college_sections.id',
            'college_sections.sectionDesc',
            'college_courses.id as courseID',
            'college_courses.courseDesc',
            'college_sections.courseID',
            'college_year.levelid as yearID',
            'college_year.yearDesc',
        )
        ->get();
        return $sections;
    }

    public function copy_section(Request $request){
        $syid = $request->get('syid');
        $semester = $request->get('semester');
        $user = auth()->user()->id;

        $section_list = $request->get('section_list');
        foreach($section_list as $section){

            $coursecollegeid = DB::table('college_courses')
            ->where('id', $section['course'])
            ->value('collegeid');

            $sections = DB::table('college_sections')
            ->where('college_sections.sectionDesc', $section['sectiondesc'])
            ->where('college_sections.syID', $syid)
            ->where('college_sections.semesterID', $semester)
            ->where('college_sections.deleted', 0)
            ->get();
            if(count($sections) == 0){
                DB::table('college_sections')->insert([
                    'syID' => $syid,
                    'sectionDesc' => $section['sectiondesc'],
                    'semesterID' => $semester,
                    'yearID'=> $section['yearID'],
                    'courseID' => $section['course'],
                    'collegeID' => $coursecollegeid,
                    'createdby' => $user,
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                ]);
            }
            
        }

       
    }
    public function delete_section(Request $request)
    {

        $checkenrolled = DB::table('college_sections')
        ->join('college_enrolledstud', 'college_sections.id', '=', 'college_enrolledstud.sectionID')
        ->where('college_enrolledstud.sectionID', $request->input('sectionid'))
        ->where('college_enrolledstud.deleted', 0)
        ->where('college_enrolledstud.studstatus', 1)
        ->where('college_enrolledstud.syid', $request->input('sy'))
        ->count();
        
        if($checkenrolled > 0){
            return response()->json(['error' => 'Section has students enrolled.'],422);
        }

        DB::table('college_sections')
            ->where('id', $request->input('sectionid'))
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);
        DB::table('college_classsched')
            ->where('sectionID', $request->input('sectionid'))
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);

        $classschedId = DB::table('college_classsched')
        ->where('sectionID', $request->input('sectionid'))
        ->pluck('id');

        DB::table('college_instructor')
            ->whereIn('classschedid', $classschedId)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);

        DB::table('college_scheddetail')
        ->whereIn('headerid', $classschedId)
        ->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);
        
    }

    public function update_section(Request $request)
    {

        $course  = DB::table('college_courses')
        ->where('id', $request->get('course'))
        ->select('collegeid')
        ->first();

        return $course;

        DB::table('college_sections')
            ->where('id', $request->get('sectionid'))
            ->update([
                'sectionDesc' => $request->get('section_name'),
                'courseID' => $course,
                'yearID' => $request->get('academic'),
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            ]);
    }


    public function get_curriculum_option(Request $request){
        $course = $request->get('course');

        return DB::table('college_curriculum')
        ->where('courseID', $course)
        ->where('deleted', 0)
        ->orderBy('id', 'desc')
        ->get();

   
    }

    public function get_subjects(Request $request){
        $course = $request->get('course');
        $curriculum = $request->get('curriculum');

        return DB::table('college_prospectus')
        ->where('courseID', $course)
        ->where('curriculumID', $curriculum)
        ->where('deleted', 0)
        ->select('id', 'subjDesc','subjCode')   
        ->get();

    }

    public function get_subject_units(Request $request)
    {
        $subjects = DB::table('college_prospectus')
        ->where('id', $request->id)
        ->where('deleted', 0)
        ->select('labunits','lecunits','credunits','subjCode')
        ->first();
      
        return response()->json($subjects);
    }


    public function get_specific_subject(Request $request)
    {

        $subjects = DB::table('college_prospectus')
        ->where('id', $request->id)
        ->where('deleted', 0)
        ->select('id','subjDesc','subjCode')
        ->first();


        return response()->json($subjects);
    }

    public function sched_conflict(Request $request, $teacherconflict = 0)
    {

        $request->validate([
            'subject' => 'required',
            'stime' => 'required',
            'etime' => 'required',
            'day' => 'required',
            // 'room' => 'required',
        ],
        [
            'subject.required' => 'Subject is required.',
            'stime.required' => 'Time is required.',
            'etime.required' => 'Time is required.',
            'day.required' => 'Day is required.',
            // 'room.required' => 'Room is required.',
        ]);
    
        $schedid = $request->get('schedid');
        $syid = $request->get('syid');
        $semid = $request->get('semester');
        $sectionid = $request->get('sectionid');
        $subject = $request->get('subject');
        $stime = $request->get('stime');
        $etime = $request->get('etime');
        $days = $request->get('day');
        $roomid = $request->get('room');
        $terms = $request->get('terms');
        $leclab = $request->get('leclab');
        $teachterm = $request->get('teachterm');
        $conflict = $request->get('conflict');
        $instructors = $request->get('instructor');
        $grade_template = $request->get('grade_template');

        

        if($conflict == 1){ //IF conflict modal pops up, proceed creating schedule

            return self::add_sched($request->all());
        } else {

        
            if($teachterm != 0  && $instructors != 0){
                if($teacherconflict == 0 && $conflict == 0){ //Checkpoimt to another controller to check if there is conflict in teacher schedule
                    $teacher_conflict = new SchedConflict();
                    return $teacher_conflict->teacher_conflict($request->all());
                }
            }
            
            

            if ($schedid == 0) { //if creating a new schedule, check if conflict
                $section = DB::table('college_classsched')
                    
                  ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                  ->join('college_prospectus', 'college_classsched.subjectid', '=', 'college_prospectus.id')
                    ->join('college_subjects','college_prospectus.subjectID','=','college_subjects.id')
                    ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
                    ->where('college_scheddetail.deleted', 0)
                    ->join('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                    ->join('days', 'college_scheddetail.day', '=', 'days.id')
                    ->leftJoin('college_instructor', function ($join) {
                        $join->on('college_classsched.id', '=', 'college_instructor.classschedid')
                            ->where('college_instructor.deleted', 0);
                    })
                    ->leftJoin('teacher', 'college_instructor.teacherID', '=', 'teacher.id')
                    ->where('college_classsched.deleted', 0)
                    ->where('college_classsched.semesterID', $semid)
                    ->where('college_classsched.syID', $syid)
                    ->select(
                        'college_sections.sectionDesc',
                        'college_subjects.subjDesc',
                        DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),
                        'college_classsched.id as schedid',
                        'college_scheddetail.id as scheddetailid',
                        'college_scheddetail.headerID',
                        'college_scheddetail.stime',
                        'college_scheddetail.etime',
                        'college_scheddetail.day',
                        'college_scheddetail.roomID',
                        'rooms.roomname',
                        'days.description',
                        'days.id as dayid',
                        'college_subjects.subjCode',
                        'college_subjects.subjDesc',
                    )
                    ->get();

                    
            } else { //if updating a schedule, check if conflict
                $section = DB::table('college_classsched')
                    
                  ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                  ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                    ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
                    ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
                    ->where('college_scheddetail.deleted', 0)
                    ->join('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                    ->join('days', 'college_scheddetail.day', '=', 'days.id')
                    ->leftJoin('college_instructor', function ($join) {
                        $join->on('college_classsched.id', '=', 'college_instructor.classschedid')
                            ->where('college_instructor.deleted', 0);
                    })
                    ->leftJoin('teacher', 'college_instructor.teacherID', '=', 'teacher.id')
                    ->where('college_classsched.id', '<>', $schedid)
                    ->where('college_classsched.deleted', 0)
                    ->where('college_classsched.semesterID', $semid)
                    ->where('college_classsched.syID', $syid)
                    ->select(
                        'college_sections.sectionDesc',
                        'college_subjects.subjDesc',
                        DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),
                        'college_classsched.id as schedid',
                        'college_scheddetail.id as scheddetailid',
                        'college_scheddetail.headerID',
                        'college_scheddetail.stime',
                        'college_scheddetail.etime',
                        'college_scheddetail.day',
                        'college_scheddetail.roomID',
                        'rooms.roomname',
                        'days.description',
                        'days.id as dayid',
                        'college_subjects.subjCode',
                        'college_subjects.subjDesc',
                    )
                    ->get();
            }
            $conflict_list = array();

            foreach ($section as $item) {
                foreach ($days as $day) {
                        if ($item->day == $day) {
                            if ($item->roomID == $roomid) {
                                if ($stime >= $item->stime && $stime <= $item->etime) {
                                    if ($stime != $item->etime) {
                                          array_push($conflict_list, $item);
                                    }
                              } else if ($etime >= $item->stime && $etime <= $item->etime) {
                                    if ($etime != $item->stime) {
                                          array_push($conflict_list, $item);
                                    }
                              } else if ($item->stime >= $stime && $item->etime <= $etime) {
                                    array_push($conflict_list, $item);
                              }
                            }
                    }
                }
            }






            if (!empty($conflict_list)) { // if conflict exists
                foreach ($conflict_list as $item) {
                    $item->stime = \Carbon\Carbon::createFromFormat('H:i:s', $item->stime)->format('g:i A');
                    $item->etime = \Carbon\Carbon::createFromFormat('H:i:s', $item->etime)->format('g:i A');
                }
                $combined = collect($conflict_list)
                    ->groupBy(function ($item) {
                        // Group by fields other than day and description
                        return $item->schedid . '-' .
                            $item->headerID . '-' .
                            $item->subjDesc . '-' .
                            $item->stime . '-' .
                            $item->etime . '-' .
                            $item->roomID . '-' .
                            $item->roomname . '-' .
                            $item->subjCode;
                    })
                    ->map(function ($group) {
                        // Combine descriptions (days)
                        $days = $group->sortBy('dayid')->pluck('description')->map(function ($item) {
                            return substr($item, 0, 3);
                        })->unique()->join('/');

                        // Take the first item and update its description field
                        $item = $group->first();
                        $item->description = $days;
                        return $item;
                    })
                    ->values()
                    ->toArray();

                return $combined;
            } else { //if no conflict exists
                return self::add_sched($request->all());
            }
        }
    }



    public function add_sched($request)
    {
        $syid = $request['syid'];
        $semid = $request['semester'];
        $sectionid = $request['sectionid'];
        $subject = $request['subject'];
        $schedid = $request['schedid'];
        $stime = $request['stime'];
        $etime = $request['etime'];
        $days = $request['day'];
        $roomid = $request['room'];
        $leclab = $request['leclab'];
        $teachterm = $request['teachterm'] ?? 0;
        $capacity = $request['capacity'];
        $grade_template = $request['grade_template'];

        
    
        // $prospectusid = DB::table('college_prospectus') 
        // ->join('college_subjects', function($join) use($subject) {
        //     $join->on('college_prospectus.subjectID', '=', 'college_subjects.id');
        //     $join->where('college_subjects.id', $subject);
        // })
        // ->whereRaw('college_prospectus.curriculumID = (SELECT MAX(curriculumID) FROM college_prospectus WHERE subjectID = college_subjects.id)')
        // ->select('college_prospectus.id')
        // ->first();

        // $prospectusid = $prospectusid->id;

        if ($schedid == 0) {
            $schedid = DB::table('college_classsched')->insertGetId([
                'syID' => $syid,
                'semesterID' => $semid,
                'sectionID' => $sectionid,
                'subjectID' => $subject,
                'capacity' => $capacity,
                'ecr_template' => $grade_template,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            ]);

            foreach ($days as $day) {
                DB::table('college_scheddetail')->insert([
                    'headerID' => $schedid,
                    'stime' => $stime,
                    'etime' => $etime,
                    'roomID' => $roomid,
                    'day' => $day,
                    'schedotherclass' => $leclab,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                ]);
            }

            if($teachterm != 0) {
                foreach($teachterm as $term) {
                    foreach($term[1] as $te) {
                        DB::table('college_instructor')->insert([
                            'classschedID' => $schedid,
                            'teacherID' => $term[0][0],
                            'term' => $te,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        ]);
                    }
                }
            }
           
        }
        // Update
        else {



            DB::table('college_classsched')
                ->where('id', $schedid)
                ->update([
                    'subjectID' => $subject,
                    'capacity' => $capacity,
                    'ecr_template' => $grade_template,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                ]);

            $daytime = DB::table('college_scheddetail')
                ->where('headerID', $schedid)
                ->where('deleted', 0)
                ->pluck('day')
                ->toArray();


            $days_to_delete = array_diff($daytime, $days);
            $days_to_delete = array_values($days_to_delete);
            if (!empty($days_to_delete)) {
                foreach ($days_to_delete as $deleting_days) {
                    DB::table('college_scheddetail')
                        ->where('headerID', $schedid)
                        ->where('day', $deleting_days)
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        ]);
                }
            }

            foreach ($days as $day) {
                if (!in_array($day, $daytime)) {

                    $deletedday = DB::table('college_scheddetail')
                        ->where('headerID', $schedid)
                        ->where('deleted', 1)
                        ->where('day', $day)
                        ->pluck('day')
                        ->toArray();



                    if (!empty($deletedday)) {
                        foreach ($deletedday as $del) {
                            DB::table('college_scheddetail')
                                ->where('headerID', $schedid)
                                ->where('day', $del)
                                ->update([
                                    'stime' => $stime,
                                    'etime' => $etime,
                                    'roomID' => $roomid,
                                    'deleted' => 0,
                                    'deletedby' => null,
                                    'deleteddatetime' => null,
                                ]);
                        }
                    } else {
                        DB::table('college_scheddetail')->insert([
                            'headerID' => $schedid,
                            'stime' => $stime,
                            'etime' => $etime,
                            'roomID' => $roomid,
                            'day' => $day,
                            'schedotherclass' => $leclab,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        ]);
                    }
                } else {
                    DB::table('college_scheddetail')
                        ->where('day', $day)
                        ->where('headerID', $schedid)
                        ->update([
                            'stime' => $stime,
                            'etime' => $etime,
                            'roomID' => $roomid,
                            'schedotherclass' => $leclab,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        ]);
                }
            }
            $teachers = [];

            if($teachterm != 0){
                foreach ($teachterm as $term) {
                    $teachers[] = $term[0][0];
                }
            }

            

            $deleteinstructor = DB::table('college_instructor')
                ->where('classschedID', $schedid)
                ->where('deleted', 0)
                ->pluck('teacherID')
                ->toArray();

            $instructor_to_delete = array_diff($deleteinstructor, $teachers);
            $instructor_to_delete = array_values($instructor_to_delete);
            if (!empty($instructor_to_delete)) {
                foreach ($instructor_to_delete as $deleting_instructor) {
                    DB::table('college_instructor')
                        ->where('classschedID', $schedid)
                        ->where('teacherID', $deleting_instructor)
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        ]);
                }
            }

            if($teachterm != 0){
                foreach ($teachterm as $term) {


                    $instructortime = DB::table('college_instructor')
                        ->where('classschedID', $schedid)
                        ->where('teacherID', $term[0][0])
                        ->where('deleted', 0)
                        ->pluck('term')
                        ->toArray();
    
                    $terms_to_delete = array_diff($instructortime, $term[1]);
                    $terms_to_delete = array_values($terms_to_delete);
                    if (!empty($terms_to_delete)) {
                        foreach ($terms_to_delete as $deleting_terms) {
                            DB::table('college_instructor')
                                ->where('classschedID', $schedid)
                                ->where('teacherID', $term[0][0])
                                ->where('term', $deleting_terms)
                                ->update([
                                    'deleted' => 1,
                                    'deletedby' => auth()->user()->id,
                                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                ]);
                        }
                    }
                    foreach ($term[1] as $te) {
                        if (!in_array($te, $instructortime)) {
                            $deletedterm = DB::table('college_instructor')
                                ->where('classschedID', $schedid)
                                ->where('teacherID', $term[0])
                                ->where('term', $te)
                                ->pluck('term')
                                ->toArray();
    
                            if (!empty($deletedterm)) {
    
                                DB::table('college_instructor')
                                    ->where('classschedID', $schedid)
                                    ->where('teacherID', $term[0][0])
                                    ->where('term', $te)
                                    ->update([
                                        'deleted' => 0,
                                        'deletedby' => null,
                                        'deleteddatetime' => null
                                    ]);
                            } else {
    
                                DB::table('college_instructor')->insert([
                                    'classschedID' => $schedid,
                                    'teacherID' => $term[0][0],
                                    'term' => $te,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                ]);
                            }
                        }
                    }
                }
            }
            
        }



        // foreach($instructors as $instructor){
        //     foreach($terms as $term){
        //         DB::table('college_instructor')->insert([
        //             'classschedID' => $schedid,
        //             'instructorID' => $instructor,
        //             'prelim' => $term[0],
        //             'midterm' => $term[1],
        //             'semifinal' => $term[2],
        //             'final' => $term[3],
        //             'createdby' => auth()->user()->id,
        //             'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        //         ]);
        //     }
        // }
    }

    public function display_sched(Request $request)
    {

        $id = $request->id;
        $currentuser = auth()->user()->id;

         $scheds = DB::table('college_classsched')
         ->where('college_classsched.sectionID',$id)
         ->where('college_classsched.deleted',0)
         ->join('college_prospectus', 'college_classsched.subjectid', '=', 'college_prospectus.id')
         ->join('college_subjects','college_prospectus.subjectID','=','college_subjects.id')
        ->leftJoin('college_instructor', function($join) {
            $join->on('college_classsched.id', '=', 'college_instructor.classschedid')
                  ->where('college_instructor.deleted', 0);
        })
        ->leftjoin('teacher', function($join){
                $join->on('college_instructor.teacherid', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);;
            })
         ->join('college_scheddetail','college_classsched.id','=','college_scheddetail.headerID')
         ->where('college_scheddetail.deleted',0)
         ->leftjoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
         ->leftjoin('building','rooms.buildingid','=','building.id')
         ->leftjoin('days','college_scheddetail.day','=','days.id')
         ->select(
                 'college_prospectus.id as subjectid',
                 'college_prospectus.subjCode',
                 'college_prospectus.subjDesc',
                 DB::raw("CONCAT(DATE_FORMAT(college_scheddetail.stime, '%h:%i %p'), ' - ', DATE_FORMAT(college_scheddetail.etime, '%h:%i %p')) AS schedtime"),
                 'college_scheddetail.schedotherclass',
                 'college_prospectus.lecunits',
                 'college_prospectus.labunits',
                 'college_prospectus.credunits',
                 'rooms.roomname',
                 'building.description as buildingname',
                 DB::raw("GROUP_CONCAT(DISTINCT CONCAT(' ', teacher.firstname, ' ', IFNULL(teacher.middlename, ' '), teacher.lastname) ORDER BY teacher.lastname ASC SEPARATOR ', ') AS instructorname"),
                 'college_classsched.id as schedid',
                 'college_classsched.capacity',
                 'teacher.userid',
                 DB::raw("GROUP_CONCAT(DISTINCT SUBSTRING(days.description, 1, 3) ORDER BY college_scheddetail.day ASC SEPARATOR '/') AS days")
                 )
         ->groupBy('college_classsched.id')
         ->get();
        
        $scheds->map(function($item) use ($currentuser) {
            $item->currentuser = $currentuser;
            return $item;
        });
        return $scheds;
    }

    public function display_sched_edit(Request $request)
    {

        $id = $request->id;


        $scheds = DB::table('college_classsched')
         ->where('college_classsched.id',$id)
         ->where('college_classsched.deleted',0)
         ->join('college_prospectus', 'college_classsched.subjectid', '=', 'college_prospectus.id')
         ->join('college_subjects','college_prospectus.subjectID','=','college_subjects.id')
         ->leftJoin('college_instructor', function($join) {
            $join->on('college_classsched.id', '=', 'college_instructor.classschedid')
                  ->where('college_instructor.deleted', 0);
        })
        ->leftjoin('teacher', function($join){
                $join->on('college_instructor.teacherid', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);;
            })
         ->join('college_scheddetail','college_classsched.id','=','college_scheddetail.headerID')
         ->where('college_scheddetail.deleted',0)
         ->leftjoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
         ->leftjoin('building','rooms.buildingid','=','building.id')
         ->leftjoin('days','college_scheddetail.day','=','days.id')
         ->select(
                 'college_prospectus.id as subjectid',
                 'college_prospectus.subjCode',
                 'college_prospectus.subjDesc',
                 'college_scheddetail.schedotherclass',
                 'college_prospectus.lecunits',
                 'college_prospectus.labunits',
                 'college_prospectus.credunits',
                 'building.id as buildingid',
                 'building.description as buildingname',
                 'rooms.roomname',
                 'college_scheddetail.roomID as roomid',
                 'college_scheddetail.stime as stime',
                 'college_scheddetail.etime as etime',
                 'college_classsched.id as schedid',
                 DB::raw("GROUP_CONCAT(DISTINCT SUBSTRING(days.description, 1, 3) ORDER BY college_scheddetail.day ASC SEPARATOR '/') AS days"),
                 'college_instructor.teacherID',
                 'college_instructor.id as instid',
                 'college_classsched.capacity',
                 'college_classsched.ecr_template',
                 DB::raw("GROUP_CONCAT(DISTINCT college_instructor.term ORDER BY college_instructor.term ASC SEPARATOR ',') as terms"),
                 DB::raw("GROUP_CONCAT(DISTINCT CONCAT(teacher.firstname, ', ', teacher.lastname) ORDER BY college_instructor.teacherID ASC SEPARATOR ',') as teachername"),
                 
                 )
         ->groupBy('college_classsched.id', 'college_instructor.teacherID')
         ->get();
        
        $schedules = [];

        if($scheds->count() == 0){
            return 'wow';
        }
        foreach ($scheds as $sched) {
            $schedid = $sched->schedid;

            if (!isset($schedules[$schedid])) {
                $schedules[$schedid] = [
                    'subjectid' => $sched->subjectid,
                    'subjCode' => $sched->subjCode,
                    'subjDesc' => $sched->subjDesc,
                    'schedotherclass' => $sched->schedotherclass,
                    'lecunits' => $sched->lecunits,
                    'labunits' => $sched->labunits,
                    'roomname' => $sched->roomname,
                    'roomid' => $sched->roomid,
                    'days' => $sched->days,
                    'stime' => $sched->stime,
                    'etime' => $sched->etime,
                    'capacity' => $sched->capacity,
                    'ecr_template' => $sched->ecr_template,
                    'buildingid' => $sched->buildingid,
                    'teachers' => [],
                ];
            }

            $schedules[$schedid]['teachers'][] = [
                'teachername' => $sched->teachername,
                'instid' => $sched->instid,
                'teacherID' => $sched->teacherID,
                'terms' => explode(',', $sched->terms)
            ];
        }
        $schedules = array_values($schedules);
        $schedules = $schedules[0];
        return response()->json($schedules);
    }

    public function delete_sched(Request $request)
    {
        
        $id = $request->get('schedid');
        $sy = $request->get('sy');

        $checkenrolled = DB::table('college_classsched')
        ->where('college_classsched.id', $request->get('schedid'))
        ->join('college_sections','college_classsched.sectionID', '=', 'college_sections.id')
        ->join('college_enrolledstud','college_sections.id', '=', 'college_enrolledstud.sectionID')
        ->join('college_loadsubject','college_classsched.id', '=', 'college_loadsubject.schedid')
        ->where('college_loadsubject.syid', $sy)
        ->where('college_classsched.deleted', 0)
        ->count();

        if($checkenrolled > 0){
            return response()->json(['error' => 'Schedule has students enrolled.'], 422);
        }

        DB::table('college_classsched')
        ->where('college_classsched.id',$id)
        ->leftjoin('college_instructor','college_classsched.id' , '=', 'college_instructor.classschedID')
        ->join('college_scheddetail','college_classsched.id','=','college_scheddetail.headerID')
        ->update([
            'college_classsched.deleted' => 1,
            'college_classsched.deletedby' => auth()->user()->id,
            'college_classsched.deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            'college_instructor.deleted' => 1,
            'college_instructor.deletedby' => auth()->user()->id,
            'college_instructor.deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            'college_scheddetail.deleted' => 1,
            'college_scheddetail.deletedby' => auth()->user()->id,
            'college_scheddetail.deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
          
        ]);
   }

    public function load_prospectus_subject(Request $request){
        $syid = $request->get('syid');
        $semester = $request->get('semester');
        $load_subj = $request->get('load_subj');
        $sectionid = $request->get('section_id');
        $current_curr =  $request->get('current_curr');
        foreach($load_subj as $item){
            // $prospectus = DB::table('college_subjects')
            // ->join('college_prospectus', function($join) use ($item, $current_curr) {
            //     $join->on('college_prospectus.subjectID', '=', 'college_subjects.id');
            //     $join->where('college_subjects.id', $item['subjectID']);
            //     $join->where('college_subjects.deleted', 0);
            //     $join->where('college_prospectus.curriculumID', $current_curr);
            // })
            // ->select('college_prospectus.id')
            // ->get();
            $schedid = DB::table('college_classsched')
            ->insertGetId([
                'syID' => $syid,
                'semesterID' => $semester,
                'sectionID' => $sectionid,
                'subjectID' => $item,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            ]);

            DB::table('college_scheddetail')
            ->insert([
                'headerID' => $schedid,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            ]);
        }
    }

    public function  get_curr(Request $request){
        $current_curr = DB::table('college_curriculum')
            ->where('courseID', $request->get('course_id'))
            ->where('deleted', 0)
            ->orderBy('id', 'desc')
            ->select('id', 'curriculumname')
            ->first();
        return response()->json($current_curr);
    }

    public function add_building(Request $request){
        $building = $request->get('building');
        $capacity = $request->get('capacity');

        $id = DB::table('building')->insertGetId([
            'description' => $building,
            'capacity' => $capacity,
            'createdby' => auth()->user()->id,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);

        $building = DB::table('building')
            ->where('id', $id)
            ->where('deleted', 0)
            ->orderBy('id', 'desc')
            ->select('id', 'description', 'capacity')
            ->first();
        
        return response()->json($building);
    }


    public function add_room(Request $request){
        $room = $request->get('room');
        $capacity = $request->get('capacity');

        $id = DB::table('rooms')->insertGetId([
            'roomname' => $room,
            'capacity' => $capacity,
            'buildingid' => $request->get('buildingid'),
            'createdby' => auth()->user()->id,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);

        $room = DB::table('rooms')
            ->where('id', $id)
            ->orderBy('id', 'desc')
            ->select('id', 'roomname', 'capacity')
            ->first();
        
        return response()->json($room);
    }

    public function get_rooms(Request $request){
        $buildingid = $request->get('buildingid');

        $rooms = DB::table('rooms')
            ->where('buildingid', $buildingid)
            ->where('deleted', 0)
            ->orderBy('id', 'desc')
            ->select('id', 'roomname', 'capacity')
            ->get();
            
        return $rooms;
    }

    public function get_conflict_all(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $schedid = $request->get('schedid');
        $sectionid = $request->get('sectionid');
        $schedule = DB::table('college_classsched')
                ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
                ->where('college_scheddetail.deleted', 0)
                ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                ->leftJoin('days', 'college_scheddetail.day', '=', 'days.id')
                ->where('college_classsched.id', $schedid)
                ->groupBy('college_classsched.id')
                ->select(
                    'college_classsched.id',
                    'college_scheddetail.stime',
                    'college_scheddetail.etime',
                    'rooms.id as room_id'
                )
                ->get();
        
        
        $schedule = $schedule->map(function ($item) use($schedid) {
            $item->days =DB::table('college_classsched')
                            ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
                            ->where('college_scheddetail.deleted', 0)
                            ->leftJoin('days', 'college_scheddetail.day', '=', 'days.id')
                            ->where('college_classsched.id', $schedid)
                            ->pluck('college_scheddetail.day')
                            ->toArray();
            return $item;
        });
        
        
        $section = DB::table('college_classsched')
                ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
                ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
                ->where('college_scheddetail.deleted', 0)
                ->leftjoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                ->leftjoin('days', 'college_scheddetail.day', '=', 'days.id')
                ->leftJoin('college_instructor', function ($join) {
                    $join->on('college_classsched.id', '=', 'college_instructor.classschedid')
                        ->where('college_instructor.deleted', 0);
                })
                ->leftJoin('teacher', 'college_instructor.teacherID', '=', 'teacher.id')
                ->where('college_classsched.id', '<>', $schedid)
                ->where('college_classsched.deleted', 0)
                ->where('college_classsched.semesterID', $semid)
                ->where('college_classsched.syID', $syid)
                ->select(
                    'college_sections.sectionDesc',
                    'college_subjects.subjDesc',
                    DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),
                    'college_classsched.id as schedid',
                    'college_scheddetail.id as scheddetailid',
                    'college_scheddetail.headerID',
                    'college_scheddetail.stime',
                    'college_scheddetail.etime',
                    'college_scheddetail.day',
                    'college_scheddetail.roomID',
                    'rooms.roomname',
                    'days.description',
                    'days.id as dayid',
                    'college_subjects.subjCode',
                    'college_subjects.subjDesc',
                )
                ->get();

                $conflict_list = array();
                // return $section;
                
                foreach ($section as $item) {
                    foreach ($schedule[0]->days as $day) {
                            if ($item->day == $day) {
                                if ($item->roomID == $schedule[0]->room_id) {
                                    if ($schedule[0]->stime >= $item->stime && $schedule[0]->stime <= $item->etime) {
                                        if ($schedule[0]->stime != $item->etime) {
                                            array_push($conflict_list, $item);
                                        }
                                } else if ($schedule[0]->etime >= $item->stime && $schedule[0]->etime <= $item->etime) {
                                        if ($schedule[0]->etime != $item->stime) {
                                            array_push($conflict_list, $item);
                                        }
                                } else if ($item->stime >= $schedule[0]->stime && $item->etime <= $schedule[0]->etime) {
                                        array_push($conflict_list, $item);
                                }
                            }
                        }
                    }
                }

                if (!empty($conflict_list)) { // if conflict exists
                    foreach ($conflict_list as $item) {
                        $item->stime = \Carbon\Carbon::createFromFormat('H:i:s', $item->stime)->format('g:i A');
                        $item->etime = \Carbon\Carbon::createFromFormat('H:i:s', $item->etime)->format('g:i A');
                    }
                    $combined = collect($conflict_list)
                        ->groupBy(function ($item) {
                            // Group by fields other than day and description
                            return $item->schedid . '-' .
                                $item->headerID . '-' .
                                $item->subjDesc . '-' .
                                $item->stime . '-' .
                                $item->etime . '-' .
                                $item->roomID . '-' .
                                $item->roomname . '-' .
                                $item->subjCode;
                        })
                        ->map(function ($group) {
                            // Combine descriptions (days)
                            $days = $group->sortBy('dayid')->pluck('description')->map(function ($item) {
                                return substr($item, 0, 3);
                            })->unique()->join('/');
    
                            // Take the first item and update its description field
                            $item = $group->first();
                            $item->description = $days;
                            return $item;
                        })
                        ->values()
                        ->toArray();
    
                    return $combined;
                } else {
                    return 0;
                }
    }
}
