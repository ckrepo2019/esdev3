<?php

namespace App\Http\Controllers\CollegeControllers;

use App\Http\Controllers\CTController\CTController;
use Illuminate\Http\Request;
use DB;
use PDF;


class CollegeStudentListController extends \App\Http\Controllers\Controller
{
    public static function studentListForAll($syid = '', $semid = '', $schedid = '')
    {
        // Fetch the students enrolled in each subject with filtering
        $students = DB::table('studinfo')
            ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
            ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
            ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            ->join('college_sections', 'college_loadsubject.sectionID', '=', 'college_sections.id')
            ->join('college_classsched', 'college_loadsubject.subjectID', '=', 'college_classsched.subjectID')
            ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
            // ->join('college_year', 'college_enrolledstud.yearLevel', '=', 'college_year.levelid')
            ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
            // ->join('epermitdetails', 'college_enrolledstud.studid', '=', 'epermitdetails.studid')
            ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
            ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
            ->where('college_loadsubject.syid', $syid)
            ->where('college_loadsubject.semid', $semid)
            ->where('college_loadsubject.schedid', $schedid)
            ->where('college_loadsubject.deleted', 0)
            ->where('college_enrolledstud.deleted', 0)
            ->where('college_enrolledstud.studstatus','!=', 0)
            ->select(
                DB::raw("CONCAT(studinfo.lastname, ', ', studinfo.firstname, ' ', IFNULL(studinfo.middlename, ''), ' ', IFNULL(studinfo.suffix, '')) AS student_name"),
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
                'college_prospectus.subjDesc',
                'college_prospectus.subjectID',
                'studinfo.id AS studid'
                // 'epermitdetails.examstatus AS examstatus',
            )
            ->groupBy(
                'college_loadsubject.studid'
            )
            ->get();

        // Count the number of students
            $studentCount = $students->count();

            // Return the students and the total count
            return response()->json([
                'students' => $students,
                'studentCount' => $studentCount
            ]);
    }
    

    public static function student_list_pdf(
        $syid = null,
        $semid = null,
        $schedid = null,
        Request $request
    ) {
        // Default values from request if not provided
        if ($syid == null) {
            $syid = $request->get('syid');
        }
    
        if ($semid == null) {
            $semid = $request->get('semid');
        }
    
        if ($schedid == null) {
            $schedid = $request->get('schedid');
        }
    
        // Fetch students using CollegeStudentListController
        $students = self::studentListForAll($syid, $semid, $schedid);
        $students = $students->getData()->students; // Extract students from JSON response

        // Rest of the code
        $semester = DB::table('semester')
            ->where('id', $semid)
            ->first()
            ->semester;

        $sydesc = DB::table('sy')
            ->where('id', $syid)
            ->first()
            ->sydesc;

        $teacher = DB::table('teacher')
        ->where('userid', auth()->user()->id)
        ->first();

        $courses = DB::table('teacherdean')
            ->where('teacherdean.deleted', 0)
            ->where('college_colleges.deleted', 0)
            ->where('college_courses.deleted', 0)
            ->where('teacherid', $teacher->id)
            ->join('college_colleges', function ($join) {
                $join->on('teacherdean.collegeid', '=', 'college_colleges.id')->where('college_colleges.deleted', 0);
            })
            ->join('college_courses', function ($join) {
                $join->on('college_colleges.id', '=', 'college_courses.collegeid')->where('college_courses.deleted', 0);
            })
            ->select('college_courses.*')
            ->get();

        $dean = DB::table('college_colleges')
            ->join('teacherdean', 'college_colleges.id', '=', 'teacherdean.id')
            ->join('users', 'teacherdean.teacherid', '=', 'users.id')
            ->where('users.deleted', 0)
            ->where('college_colleges.deleted', 0)
            ->where('teacherdean.deleted', 0)
            ->select(
                'college_colleges.dean',
                'college_colleges.collegeabrv',
                'college_colleges.collegeDesc',
                'users.name'
            )
            ->first();

        // dd($teacher);
        $schedinfo = DB::table('college_classsched')
        ->leftJoin('college_subjects', 'college_classsched.subjectID', '=', 'college_subjects.id')
        ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
        ->join('college_colleges', 'college_sections.courseID', '=', 'college_colleges.id')
        ->join('college_courses', 'college_sections.courseID', '=', 'college_courses.id')
        ->where('college_classsched.syid', $syid)
        ->where('college_classsched.deleted', 0)

        ->select(
            'college_colleges.collegeDesc',
            'college_colleges.dean as college_dean',
            'college_colleges.collegeabrv',
            'college_sections.sectionDesc',
            'college_subjects.subjCode',
            'college_subjects.subjDesc'
        )
        ->first();


        $schedule = Db::table('college_scheddetail')
    ->join('college_classsched', 'college_scheddetail.headerID', '=', 'college_classsched.id')
    ->leftJoin('rooms', function ($join) {
        $join->on('college_scheddetail.roomid', '=', 'rooms.id');
        $join->where('rooms.deleted', 0);
    })
    ->leftJoin('days', function ($join) {
        $join->on('college_scheddetail.day', '=', 'days.id');
    })
    ->where('college_classsched.id', $schedid)
    ->where('college_scheddetail.deleted', 0)
    ->select(
        'college_scheddetail.stime',
        'college_scheddetail.etime',
        'college_scheddetail.day',
        'days.description',
        'rooms.roomname',
        'schedotherclass'
    )
    ->groupBy('college_scheddetail.day', 'college_scheddetail.etime', 'college_scheddetail.stime')
    ->get();

foreach ($schedule as $sched_item) {
    $start = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A');
    $end = \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
    $time = $start . ' - ' . $end;
    $sched_item->time = $time;
    $sched_item->start = $start;
    $sched_item->end = $end;
}

// Group by time
$schedule = collect($schedule)->groupBy('time')->values();

$sched_list = [];
foreach ($schedule as $sched_item_group) {
    $dayString = '';
    $days = [];

    // Get the common time, start, end, and other attributes
    $time = $sched_item_group[0]->time;
    $start = $sched_item_group[0]->start;
    $end = $sched_item_group[0]->end;
    $schedotherclass = $sched_item_group[0]->schedotherclass;

    // Collect all days for this time range
    foreach ($sched_item_group as $sched_item) {
        $dayString .= substr($sched_item->description, 0, 3) . ' / ';
        $days[] = $sched_item->day;
    }

    // Remove trailing slash and space from dayString
    $dayString = rtrim($dayString, ' / ');

    // Add to final list
    $sched_list[] = (object)[
        'day' => $dayString,
        'start' => $start,
        'end' => $end,
        'time' => $time,
        'days' => $days
    ];
}
        // dd($students);

        // Generate the PDF with the fetched data
        $pdf = PDF::loadView('ctportal.pages.studentinformation_pdf', compact('students', 'sydesc', 'semester', 'sched_list', 'schedinfo', 'dean', 'teacher', 'schedule'))->setPaper('legal');
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);

        return $pdf->stream();

        // return view('ctportal.pages.studentinformation_pdf', compact('instructor', 'students', 'sydesc', 'semester', 'sched_list', 'schedinfo', 'dean'));

    }
    
    public function print_permit(
        $syid = null,
        $semid = null,
        $schedid = null,
        Request $request
    ) {
        // Default values from request if not provided
        $syid = $syid ?? $request->get('syid');
        $semid = $semid ?? $request->get('semid');
        $schedid = $schedid ?? $request->get('schedid');
        $studid = $request->get('studid');
        
        // Fetch students using CollegeStudentListController
        $students = self::studentListForAll($syid, $semid, $schedid);
        $students = $students->getData()->students ?? [];
    
        // Fetch semester and sy description
        $semester = DB::table('semester')->where('id', $semid)->first()->semester ?? 'N/A';
        $sydesc = DB::table('sy')->where('id', $syid)->first()->sydesc ?? 'N/A';

        $teacher = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        $courses = DB::table('teacherdean')
            ->where('teacherdean.deleted', 0)
            ->where('college_colleges.deleted', 0)
            ->where('college_courses.deleted', 0)
            ->where('teacherid', $teacher->id)
            ->join('college_colleges', function ($join) {
                $join->on('teacherdean.collegeid', '=', 'college_colleges.id')->where('college_colleges.deleted', 0);
            })
            ->join('college_courses', function ($join) {
                $join->on('college_colleges.id', '=', 'college_courses.collegeid')->where('college_courses.deleted', 0);
            })
            ->select('college_courses.*')
            ->get();

        $dean = DB::table('college_colleges')
            ->join('teacherdean', 'college_colleges.id', '=', 'teacherdean.id')
            ->join('users', 'teacherdean.teacherid', '=', 'users.id')
            ->where('users.deleted', 0)
            ->where('college_colleges.deleted', 0)
            ->where('teacherdean.deleted', 0)
            ->select(
                'college_colleges.dean',
                'college_colleges.collegeabrv',
                'college_colleges.collegeDesc',
                'users.name'
            )
            ->first();
        // dd($teacher);

        // Fetch schedule info and instructor details
        $schedinfo = DB::table('college_classsched')
            ->leftJoin('college_subjects', 'college_classsched.subjectID', '=', 'college_subjects.id')
            ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
            ->join('college_colleges', 'college_sections.courseID', '=', 'college_colleges.id')
            ->join('college_courses', 'college_sections.courseID', '=', 'college_courses.id')
            ->where('college_classsched.syid', $syid)
            // ->where('college_classsched.id', $schedid)
            ->where('college_classsched.deleted', 0)

            ->select(
                'college_colleges.collegeDesc',
                'college_colleges.dean as college_dean',
                'college_colleges.collegeabrv',
                'college_sections.sectionDesc',
                'college_subjects.subjCode',
                'college_subjects.subjDesc'
            )
            ->first();
            
        // Fetch schedule details
            $schedule = DB::table('college_scheddetail')
        ->join('college_classsched', 'college_scheddetail.headerID', '=', 'college_classsched.id')
        ->leftJoin('rooms', function ($join) {
            $join->on('college_scheddetail.roomid', '=', 'rooms.id');
            $join->where('rooms.deleted', 0);
        })
        ->leftJoin('days', function ($join) {
            $join->on('college_scheddetail.day', '=', 'days.id');
        })
        ->where('college_classsched.id', $schedid)
        ->where('college_scheddetail.deleted', 0)
        ->select(
            'college_scheddetail.stime',
            'college_scheddetail.etime',
            'college_scheddetail.day',
            'days.description',
            'rooms.roomname',
            'schedotherclass'
        )
        ->groupBy('college_scheddetail.day', 'college_scheddetail.etime', 'college_scheddetail.stime')
        ->get();

    foreach ($schedule as $sched_item) {
        $start = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A');
        $end = \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
        $time = $start . ' - ' . $end;
        $sched_item->time = $time;
        $sched_item->start = $start;
        $sched_item->end = $end;
    }

    // Group by time
    $schedule = collect($schedule)->groupBy('time')->values();

    $sched_list = [];
    foreach ($schedule as $sched_item_group) {
        $dayString = '';
        $days = [];

        $time = $sched_item_group[0]->time;
        $start = $sched_item_group[0]->start;
        $end = $sched_item_group[0]->end;
        $schedotherclass = $sched_item_group[0]->schedotherclass;

        foreach ($sched_item_group as $sched_item) {
            $dayString .= substr($sched_item->description, 0, 3) . ' / ';
            $days[] = $sched_item->day;
        }

        $dayString = rtrim($dayString, ' / ');

        $sched_list[] = (object)[
            'day' => $dayString,
            'start' => $start,
            'end' => $end,
            'time' => $time,
            'days' => $days,
            'roomname' => $sched_item_group[0]->roomname,
            'schedotherclass' => $schedotherclass
        ];
    }

    foreach ($students as &$student) {
        $permitDetails = DB::table('epermitdetails')
            ->where('studid', $student->studid) // Match the student's ID
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->first();
    
        $student->examstatus = $permitDetails->examstatus ?? 'Not Permitted';
    }
    unset($student); 

    // dd($sched_list, $students);

    $pdf = PDF::loadView('ctportal.pages.studentinformation_ePermitpdf', compact(
        'students', 'sydesc', 'semester', 'schedinfo', 'teacher', 'dean', 'sched_list', 'permitDetails'
    ))->setPaper('legal');
    $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);

    return $pdf->stream();
    }
    
    // Function to format name with middlename initials, suffix, and title
    private function formatName($firstname, $middlename, $lastname, $suffix, $title)
    {
        $middlename = !empty($middlename) ? implode('', array_map(function ($m) {
            return $m[0] . '. ';
        }, explode(' ', $middlename))) : '';
    
        $suffix = !empty($suffix) ? ' ' . $suffix : '';
        $title = !empty($title) ? $title . ' ' : '';
    
        return $title . $firstname . ' ' . $middlename . $lastname . $suffix;
    }
    
    // Function to format the schedule by time and group by time slot
    private function formatSchedule($schedule)
    {
        foreach ($schedule as $sched_item) {
            $start = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A');
            $end = \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
            $sched_item->time = $start . ' - ' . $end;
        }
    
        return collect($schedule)->groupBy('time')->values()->map(function ($groupedSchedule) {
            $dayString = collect($groupedSchedule)->map(function ($sched) {
                return substr($sched->description, 0, 3);
            })->implode(' / ');
    
            $firstItem = $groupedSchedule->first();
    
            return (object)[
                'day' => $dayString,
                'start' => $firstItem->stime,
                'end' => $firstItem->etime,
                'time' => $firstItem->time
            ];
        });
    }
    
}