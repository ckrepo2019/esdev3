<?php

namespace App\Http\Controllers\DeanControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use DB;
use PDF;
use App\Http\Controllers\NotificationController\NotificationController;

class CollegeStudentLoadingController extends Controller
{
    public static function students(Request $request)
        {
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $gradelevel = $request->get('gradelevel');
            $enrolledstatus = $request->get('enrolled_status');
            $acadprog = [];
            // Check the current portal to apply specific filters for the teacher
            if (Session::get('currentPortal') == 16) {
                $teacher = DB::table('teacher')
                    ->where('userid', auth()->user()->id)
                    ->first();
                $acadprog = DB::table('teacherprogramhead')
                    ->where('teacherprogramhead.deleted', 0)
                    ->where('teacherprogramhead.syid', $syid)
                    ->where('teacherid', $teacher->id)
                    ->join('college_courses', function ($join) {
                        $join->on('teacherprogramhead.courseid', '=', 'college_courses.id')
                            ->where('college_courses.deleted', 0);
                    })
                    ->join('college_colleges', function ($join) {
                        $join->on('college_courses.collegeid', '=', 'college_colleges.id')
                            ->where('college_colleges.deleted', 0);
                    })
                    ->select('college_colleges.acadprogid')
                    ->distinct('college_colleges.acadprogid')
                    ->get();
                    
                $courses = DB::table('teacherprogramhead')
                    ->where('teacherprogramhead.deleted', 0)
                    ->where('teacherprogramhead.syid', $syid)
                    ->where('teacherid', $teacher->id)
                    ->join('college_courses', function ($join) {
                        $join->on('teacherprogramhead.courseid', '=', 'college_courses.id')
                            ->where('college_courses.deleted', 0);
                    })
                    ->select('college_courses.id')
                    ->get();

                $temp_course = $courses->pluck('id')->toArray();

                if (empty($temp_course)) {
                    return [];
                }
            } else if (Session::get('currentPortal') == 14) {
                $teacher = DB::table('teacher')
                    ->where('userid', auth()->user()->id)
                    ->first();
                
                $acadprog = DB::table('teacherdean')
                    ->where('teacherdean.deleted', 0)
                    ->where('teacherdean.syid', $syid)
                    ->where('teacherid', $teacher->id)
                    ->join('college_colleges', function ($join) {
                        $join->on('teacherdean.collegeid', '=', 'college_colleges.id')
                            ->where('college_colleges.deleted', 0);
                    })
                    ->select('college_colleges.acadprogid')
                    ->distinct('college_colleges.acadprogid')
                    ->get();


                $courses = DB::table('teacherdean')
                    ->where('teacherdean.deleted', 0)
                    ->where('teacherdean.syid', $syid)
                    ->where('teacherid', $teacher->id)
                    ->join('college_colleges', function ($join) {
                        $join->on('teacherdean.collegeid', '=', 'college_colleges.id')
                            ->where('college_colleges.deleted', 0);
                    })
                    ->join('college_courses', function ($join) {
                        $join->on('college_colleges.id', '=', 'college_courses.collegeid')
                            ->where('college_courses.deleted', 0);
                    })
                    ->select('college_courses.id')
                    ->get();

                $temp_course = $courses->pluck('id')->toArray();

                if (empty($temp_course)) {
                    return [];
                }
            } else {
                $temp_course = [];
            }

            // Main query to retrieve enrolled students
            $students = DB::table('studinfo')
            ->where('studinfo.studisactive', 1)
            ->where('studinfo.deleted', 0)
            ->leftJoin('college_enrolledstud', function ($join) use ($syid, $semid, $enrolledstatus) {
                $join->on('studinfo.id', '=', 'college_enrolledstud.studid')
                    ->where('studinfo.deleted', 0)
                    ->where('college_enrolledstud.deleted', 0)
                    ->where('college_enrolledstud.syid', $syid)
                    ->where('college_enrolledstud.semid', $semid);
                })
                ->join('gradelevel', function ($join) use($acadprog) {
                    $join->on('studinfo.levelid', '=', 'gradelevel.id')
                        ->where('gradelevel.deleted', 0)
                        ->when(Session::get('currentPortal') == 3, function ($query) {
                            $query->whereIn('gradelevel.id', [17, 18, 19, 20, 21, 22, 23 ,24 ,25]);
                        })
                        ->when(Session::get('currentPortal') == 14, function ($query) use($acadprog) {
                            $query->whereIn('gradelevel.acadprogid', $acadprog->pluck('acadprogid')->toArray());
                        })
                        ->when(Session::get('currentPortal') == 16, function ($query) use($acadprog) {
                            $query->whereIn('gradelevel.acadprogid', $acadprog->pluck('acadprogid')->toArray());
                        });
                })
                ->leftJoin('college_courses', function ($join) {
                    $join->on('studinfo.courseid', '=', 'college_courses.id')
                        ->where('college_courses.deleted', 0);
                })
                ->leftJoin('college_colleges', function ($join) {
                    $join->on('college_courses.collegeid', '=', 'college_colleges.id')
                        ->where('college_colleges.deleted', 0);
                })
                ->leftJoin('studentstatus', function ($join) {
                    $join->on('studinfo.studstatus', '=', 'studentstatus.id');
                })
                ->leftJoin('college_sections', function ($join) {
                    $join->on('studinfo.sectionid', '=', 'college_sections.id');
                })
                ->leftJoin('studinfo_more', function ($join) {
                    $join->on('studinfo.id', '=', 'studinfo_more.id');
                })
                ->when($request->input('course'), function ($query, $course) {
                    $query->where('studinfo.courseid', $course);
                })
                ->when($enrolledstatus != 'all', function ($query, $course) use($enrolledstatus) {
                    $query->where('studinfo.studstatus', $enrolledstatus);
                })
                ->when($request->input('gradelevel'), function ($query, $gradelevel) {
                    $query->where('studinfo.levelid', $gradelevel);
                })
                ->when(Session::get('currentPortal') == 16 || Session::get('currentPortal') == 14, function ($query) use ($temp_course) {
                    $query->where(function ($q) use ($temp_course) {
                        $q->whereIn('studinfo.courseid', $temp_course)
                            ->orWhereNull('studinfo.courseid')
                            ->orWhere('studinfo.courseid', 0);
                    });
                })
                ->select(
                    'studinfo.picurl',
                    'college_enrolledstud.date_enrolled',
                    'college_enrolledstud.updateddatetime',
                    'studinfo.sectionid',
                    'college_sections.sectionDesc',
                    'studinfo.levelid',
                    'studinfo.id',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.contactno',
                    'studinfo.sid',
                    'studinfo.suffix',
                    'college_courses.courseDesc',
                    'college_courses.courseabrv',
                    'college_colleges.collegeDesc',
                    'gradelevel.levelname',
                    'gradelevel.acadprogid',
                    'studentstatus.description as studstatus',
                    'studinfo.gender',
                    'studinfo.ismothernum',
                    'studinfo.isfathernum',
                    'studinfo.isguardannum',
                    'studinfo.fcontactno',
                    'studinfo.mcontactno',
                    'studinfo.gcontactno',
                    'studinfo.courseid as courseIdNotYetEnrolled',
                    'studinfo.sectionid as notyetenerolledId',
                    DB::raw("(SELECT sectionDesc FROM college_sections WHERE id = studinfo.sectionid) as sectionDescNotYetEnrolled"),
                    DB::raw("(SELECT courseabrv FROM college_courses WHERE id = studinfo.courseid) as courseDescNotYetEnrolled")
                )
                ->groupBy('studinfo.id') // Group by student id to avoid duplication
                ->get();

            // Formatting middle name and full student name
            foreach ($students as $item) {
                $middlename = explode(" ", $item->middlename);
                $temp_middle = '';
                if ($middlename != null) {
                    foreach ($middlename as $middlename_item) {
                        if (strlen($middlename_item) > 0) {
                            $temp_middle .= $middlename_item[0] . '.';
                        }
                    }
                }
                $item->student = $item->lastname . ', ' . $item->firstname . ' ' . $item->suffix . ' ' . $temp_middle;
                $item->text = $item->sid . ' - ' . $item->student;
            }

            return $students;
        }


    public function getStudents(Request $request)
    {
        $students = self::students($request);
        return response()->json($students);
    }


    public function getStudentInformation(Request $request)
    {

        $student = DB::table('studinfo')
        ->leftjoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
        ->leftjoin('college_sections', 'studinfo.sectionid', '=', 'college_sections.id')
        ->leftjoin('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
        ->leftjoin('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
        ->leftJoin('studinfo_more', 'studinfo.id', '=', 'studinfo_more.studid')
        ->leftjoin('nationality', 'studinfo.nationality', '=', 'nationality.id')
        ->where('studinfo.id', $request->studentId)
        ->select('studinfo.*', 'studinfo_more.*', 'nationality.nationality', 'college_sections.sectionDesc', 'gradelevel.levelname', 'college_courses.courseDesc', 'studentstatus.description as studstatus')
        ->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $student->contact_info = [
            'personal' => $student->contactno ?? null,
            'mother' => $student->ismothernum ? $student->mcontactno : null,
            'father' => $student->isfathernum ? $student->fcontactno : null,
            'guardian' => $student->isguardannum ? $student->gcontactno : null,
        ];

        return response()->json($student);
    }


    public function getSectionSchedule(Request $request)
    {
        $sectionId = $request->input('section_id');
        $syid = $request->input('syid');
        $semid = $request->input('semid');

        $query = DB::table('college_classsched')
            ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
            ->leftJoin('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
            ->leftJoin('college_subjprereq', 'college_prospectus.id', '=', 'college_subjprereq.subjID')
            ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
            ->leftJoin('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
            ->leftJoin('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
            ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
            ->leftJoin('days', 'college_scheddetail.day', '=', 'days.id')
            ->where('college_classsched.syid', $syid)
            ->where('college_classsched.semesterID', $semid)
            ->where('college_classsched.deleted', 0)
            ->where(function ($query) {
                $query->whereNull('college_scheddetail.deleted')
                    ->orWhere('college_scheddetail.deleted', 0);
            })
            ->when($sectionId !== 'all', function ($q) use ($sectionId) {
                $q->where('college_sections.id', $sectionId);
            })
            ->select(
                'college_prospectus.id as subjectId',
                'college_subjprereq.prereqsubjID as prereqID',
                DB::raw("(SELECT subjDesc FROM college_subjects WHERE id = college_subjprereq.prereqsubjID) as prereqDESC"),
                'college_classsched.id as schedid',
                'college_sections.sectionDesc as section',
                'college_sections.id as sectionId',
                'college_prospectus.subjCode',
                'college_prospectus.subjDesc',
                'college_prospectus.lecunits',
                'college_prospectus.labunits',
                'days.id as dayId',
                'college_scheddetail.schedotherclass',
                DB::raw('college_prospectus.lecunits + college_prospectus.labunits as cr_unit'),
                DB::raw("COALESCE(DATE_FORMAT(college_scheddetail.stime, '%h:%i %p'), 'No schedule') as start_time"),
                DB::raw("COALESCE(DATE_FORMAT(college_scheddetail.etime, '%h:%i %p'), 'No schedule') as end_time"),
                DB::raw("CONCAT(teacher.firstname, ' ', teacher.lastname) as instructor"),
                'rooms.roomname as room',
                DB::raw("(SELECT COUNT(*) FROM college_studsched WHERE college_studsched.schedid = college_classsched.id AND college_classsched.syID = {$syid} AND college_classsched.semesterID = {$semid}) as enrolled"),
                'days.description as day',
                'college_scheddetail.stime',
                'college_scheddetail.etime'
            )
            ->groupBy('schedid');

        $schedules = $query->get()->groupBy('schedid')->map(function ($scheduleItems) {
            $firstItem = $scheduleItems->first();
            $groupedSubjects = $scheduleItems->groupBy('day');

            $stime = $firstItem->stime ? date("h:i A", strtotime($firstItem->stime)) : null;
            $etime = $firstItem->etime ? date("h:i A", strtotime($firstItem->etime)) : null;

            $timeDaySchedule = $stime && $etime ? "{$stime} - {$etime}" : "No schedule available";

            return [
                'schedid' => $firstItem->schedid,
                'sectionId' => $firstItem->sectionId,
                'prereqID' => $firstItem->prereqID,
                'prereqDESC' => $firstItem->prereqDESC,
                'section' => $firstItem->section,
                'schedotherclass' => $firstItem->schedotherclass,
                'time_day_schedule' => $timeDaySchedule,
                'days' => $firstItem->day,
                'room' => $firstItem->room,
                'stime' => $stime ?: 'No schedule',
                'etime' => $etime ?: 'No schedule',
                'dayId' => $firstItem->dayId,
                'time_day' => "$timeDaySchedule {$firstItem->day}",
                'subjCode' => $firstItem->subjCode,
                'subjDesc' => $firstItem->subjDesc,
                'lecunits' => $firstItem->lecunits,
                'labunits' => $firstItem->labunits,
                'cr_unit' => $firstItem->lecunits + $firstItem->labunits,
                'instructor' => $firstItem->instructor,
                'enrolled' => $firstItem->enrolled,
                'subjectId' => $firstItem->subjectId,
            ];
        })->values();

        // Calculate total units
        $totalLecUnits = $schedules->sum('lecunits');
        $totalLabUnits = $schedules->sum('labunits');
        $totalUnits = $totalLecUnits + $totalLabUnits;

        return response()->json([
            'data' => $schedules,
            'totalLecUnits' => $totalLecUnits,
            'totalLabUnits' => $totalLabUnits,
            'totalUnits' => $totalUnits
        ]);
    }

    public function getStudentLoading($sectionId)
        {
            $prospectus = DB::table('college_prospectus')
                ->join('college_classsched', 'college_prospectus.id', '=', 'college_classsched.subjectID')
                // ->join('college_studsched', 'college_classsched.sectionID', '=', 'college_studsched.schedid')
                ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
                ->join('days', 'college_scheddetail.day', '=', 'days.id')
                ->leftjoin('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                ->leftJoin('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                // Ensure we are only selecting subjects that are not deleted
                ->where('college_classsched.deleted', 0)
                ->where('college_sections.id', $sectionId)
                ->select(
                    'college_prospectus.id',
                    'college_prospectus.curriculumID',
                    'college_sections.id as sectionID',
                    'college_prospectus.subjCode',
                    'college_prospectus.subjDesc',
                    'college_prospectus.lecunits',
                    'college_prospectus.labunits',
                    'college_prospectus.subjClass',
                    'college_sections.sectionDesc',
                    'college_sections.yearID',
                    'college_scheddetail.schedotherclass',
                    'rooms.roomname',
                    'days.description as day',
                    'college_scheddetail.stime',
                    'college_scheddetail.etime',
                    DB::raw("CONCAT(teacher.firstname, ' ', COALESCE(teacher.middlename, ''), ' ', teacher.lastname) as instructor")
                )
                ->groupBy('college_prospectus.id', 'day', 'college_scheddetail.stime','college_scheddetail.etime')
                ->get();

            return response()->json(['subjects' => $prospectus]);
        }

    public function saveLoadedSubjects(Request $request)
    {
        $validated = $request->validate([
            'studentId' => 'required|integer',
            'subjects' => 'required|array',
            'subjects.*.studid' => 'required|integer',
            'subjects.*.subjid' => 'required|integer',
            'subjects.*.sectionid' => 'required|integer',
            'subjects.*.schedid' => 'required|integer',
            'subjects.*.syid' => 'required|integer',
            'subjects.*.semid' => 'required|integer',
        ]);

        $subjects = $validated['subjects'];
        $now = \Carbon\Carbon::now('Asia/Manila');
        $createdBy = auth()->user()->id;

        foreach ($subjects as $subject) {
            // Check if the subject exists (same student, subject, and semester/year)
            $existingSubject = DB::table('college_loadsubject')
                ->where('studid', $subject['studid'])
                ->where('subjectID', $subject['subjid'])
                ->where('syid', $subject['syid'])
                ->where('semid', $subject['semid'])
                ->first();

            if ($existingSubject) {
                // If the subject was deleted and in the same section, restore it
                if ($existingSubject->deleted == 1 && $existingSubject->sectionID == $subject['sectionid']) {
                    DB::table('college_loadsubject')
                        ->where('id', $existingSubject->id)
                        ->update([
                            'deleted' => 0,
                            'isDropped' => 0,
                            'updateddatetime' => $now,
                            'updatedby' => $createdBy,
                        ]);
                } elseif ($existingSubject->sectionID != $subject['sectionid']) {
                    // Allow adding the same subject in a different section
                    DB::table('college_loadsubject')->insert([
                        'schedid' => $subject['schedid'],
                        'studid' => $subject['studid'],
                        'subjectID' => $subject['subjid'],
                        'sectionID' => $subject['sectionid'],
                        'syid' => $subject['syid'],
                        'semid' => $subject['semid'],
                        'createdby' => $createdBy,
                        'createddatetime' => $now,
                        'updateddatetime' => $now,
                    ]);
                }
            } else {
                // Insert the subject as a new record
                DB::table('college_loadsubject')->insert([
                    'schedid' => $subject['schedid'],
                    'studid' => $subject['studid'],
                    'subjectID' => $subject['subjid'],
                    'sectionID' => $subject['sectionid'],
                    'syid' => $subject['syid'],
                    'semid' => $subject['semid'],
                    'createdby' => $createdBy,
                    'createddatetime' => $now,
                    'updateddatetime' => $now,
                ]);
            }
        }

        return response()->json(['message' => 'Subjects loaded successfully'], 200);
    }
        
    public static function getAddedStudentLoading($studentId, $sectionId, $syid, $semid, $subjectID, $finance = null)
    {
        $studentLoading = DB::table('college_classsched')
            ->join('college_loadsubject', 'college_classsched.id', '=', 'college_loadsubject.schedid')
            ->leftJoin('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
            ->leftJoin('studinfo', 'college_loadsubject.studid', '=', 'studinfo.id')
            ->join('college_year', 'college_loadsubject.syid', '=', 'college_year.id')
            ->leftJoin('college_semester', 'college_loadsubject.semid', '=', 'college_semester.id')
            ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
            ->leftJoin('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
            ->leftJoin('college_sections', 'college_loadsubject.sectionID', '=', 'college_sections.id')
            ->leftJoin('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
            ->leftJoin('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
            ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
            ->leftJoin('days', 'college_scheddetail.day', '=', 'days.id')
            ->leftJoin('users', 'college_loadsubject.deletedby', '=', 'users.id')
            ->where('college_loadsubject.studid', $studentId)
            ->when($sectionId != 'all', function ($query) use ($sectionId) {
                $query->where('college_classsched.sectionID', $sectionId);
            })
            ->where('college_loadsubject.syid', $syid)
            ->where('college_loadsubject.semid', $semid)
            ->where(function($query) use($finance) {
                $query->where('college_loadsubject.deleted', 0);
                $query->when($finance != 1, function ($query) {
                    $query->orWhere('college_loadsubject.isDropped', 1);
                });
            })
            ->where('college_classsched.deleted', 0)
            ->orderBy('days.id', 'asc')
            ->select(
                'college_loadsubject.subjectID as subjectID',
                'college_loadsubject.studid',
                'college_loadsubject.subjectID',
                'college_loadsubject.sectionid as sectionID',
                'college_loadsubject.isDropped as loadStatus',
                'college_loadsubject.schedid',
                'college_sections.sectionDesc',
                'college_prospectus.subjCode',
                'college_prospectus.subjDesc',
                'college_prospectus.lecunits',
                'college_prospectus.labunits',
                'college_classsched.id as schedid',
                'college_instructor.teacherid', 
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'college_scheddetail.schedotherclass',
                'rooms.roomname',
                'days.description as day',
                'college_scheddetail.stime',
                'college_scheddetail.etime',
                'users.name'
            )
            ->get();

        // return $studentLoading;

        foreach ($studentLoading as $subject) {
            $prerequisites = DB::table('college_subjprereq')
                ->join('college_prospectus', 'college_subjprereq.prereqsubjID', '=', 'college_prospectus.id')
                ->where('college_subjprereq.subjID', $subject->subjectID)
                ->select('college_prospectus.subjCode', 'college_prospectus.subjDesc')
                ->get();

            $subject->prerequisites = $prerequisites;
        }


        // Group by both subjectID and sectionID to ensure unique combinations are handled
        $studentLoading = $studentLoading->groupBy(function ($item) {
            return "{$item->subjectID}-{$item->sectionID}";
        })->map(function ($subjects) {
            $firstSubject = $subjects->first();
        
            $scheduleDetails = $subjects->groupBy(function ($subject) {
                return "{$subject->stime}-{$subject->etime}";
            })->map(function ($groupedSubjects) {
                $stime = isset($groupedSubjects->first()->stime) && !empty($groupedSubjects->first()->stime) 
                    ? date("h:i A", strtotime($groupedSubjects->first()->stime)) 
                    : "";
            
                $etime = isset($groupedSubjects->first()->etime) && !empty($groupedSubjects->first()->etime) 
                    ? date("h:i A", strtotime($groupedSubjects->first()->etime)) 
                    : "";
            
                $days = $groupedSubjects->pluck('day')->unique()->filter()->implode('/') ?? '';
            
                // If both time and days are empty, return an empty string
                if (empty($stime) && empty($etime) && empty($days)) {
                    return '';
                }
            
                // Construct schedule string
                $schedule = trim("{$stime} - {$etime}");
                if (!empty($days)) {
                    $schedule .= " ({$days})";
                }
            
                return $schedule;
            })->filter()->implode(', ');
            
        
            $instructor = trim("{$firstSubject->firstname} " . ($firstSubject->middlename ? "{$firstSubject->middlename} " : '') . "{$firstSubject->lastname}");
            $instructor = $instructor ?: 'Not Assigned';
        
            return [
                'id' => $firstSubject->subjectID,
                'studid' => $firstSubject->studid,
                'subjectID' => $firstSubject->subjectID,
                'sectionID' => $firstSubject->sectionID,
                'sectionDesc' => $firstSubject->sectionDesc ?? 'N/A',
                'subjCode' => $firstSubject->subjCode ?? 'N/A',
                'subjDesc' => $firstSubject->subjDesc ?? 'N/A',
                'lecunits' => $firstSubject->lecunits ?? 0,
                'labunits' => $firstSubject->labunits ?? 0,
                'totalUnits' => ($firstSubject->lecunits ?? 0) + ($firstSubject->labunits ?? 0),
                'schedotherclass' => $subjects->pluck('schedotherclass')->filter()->unique()->implode(', ') ?? 'N/A',
                'schedule' => !empty($scheduleDetails) ? $scheduleDetails : '',
                'instructor' => $instructor,
                'roomname' => $firstSubject->roomname ?? 'Not Assigned',
                'loadStatus' => $firstSubject->loadStatus,
                'deletedByName' => $firstSubject->name ?? 'Unknown',
                'schedid' => $firstSubject->schedid,
                'prerequisites' => isset($firstSubject->prerequisites) && count($firstSubject->prerequisites) > 0 
                    ? collect($firstSubject->prerequisites)->pluck('subjCode')->implode(', ')
                    : ''
            ];
        });
        

        $gensections = [];
        foreach ($studentLoading as $_load) {
            $exists = array_filter($gensections, function ($section) use ($_load) {
                return $section->sectionid == $_load['sectionID'];
            });



            if (count($exists) == 0) {
                array_push($gensections, (object)[
                    'sectionid' => $_load['sectionID'],
                    'sectiondesc' => $_load['sectionDesc']
                ]);
            }
        }

        $selectedsection = 0;
        $getstudinfo = DB::table('studinfo')
            ->select('sectionid')
            ->where('id', $studentId)
            ->first();

        $loadedSubjects = DB::table('college_loadsubject')
            ->where('college_loadsubject.studid', $studentId)
            ->where('college_loadsubject.syid', $syid)
            ->where('college_loadsubject.semid', $semid)
            ->where('college_loadsubject.deleted', 0)
            ->select(
                'college_loadsubject.schedid',
            )
            ->get();

        
            $studStatus = DB::table('college_enrolledstud')
            ->where('college_enrolledstud.studid', $studentId)
            ->where('college_enrolledstud.syid', $syid)
            ->where('college_enrolledstud.semid', $semid)
            ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
            ->exists();
        
        $enrolled = $studStatus ? 1 : 0;
        
        $RemoveSubject = DB::table('college_loadsubject')
            ->where('studid', $studentId)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('deleted', 1)
            ->select(
                'subjectID',
                'sectionID'
            )
            ->get();
        
        // Exclude removed subjects if the student is not enrolled
        if (!$studStatus && count($RemoveSubject) > 0) {
            $RemoveSubject = collect(); // Return an empty collection
        }
        
        if ($getstudinfo) {
            $selectedsection = $getstudinfo->sectionid;
        }
        
        // Return response
        return response()->json([
            'studentLoading' => $studentLoading->values(),
            'gensections' => $gensections,
            'selectedsection' => $selectedsection,
            'enrolled' => $enrolled,
            'loadedSubjects' => $loadedSubjects
        ], 200);
    }

    public function deleteLoadedSubject($subjectId, Request $request)
    {
        $studentId = $request->input('studentId');
        $syid = $request->input('syid');
        $semid = $request->input('semid');
        $schedid = $request->input('schedid');
    
        // Query to get the subject and other details
        $subject = DB::table('college_loadsubject')
            ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
            ->where('studid', $studentId)
            ->where('college_loadsubject.subjectID', $subjectId)
            ->where('college_loadsubject.syid', $syid)
            ->where('college_loadsubject.semid', $semid)
            ->where('college_loadsubject.deleted', 0)
            ->first();
    
        if (!$subject) {
            return response()->json(['message' => 'Subject not found or already deleted'], 404);
        }
    
        // Check for any existing transactions
        $transaction = DB::table('college_studentprospectus')
            ->where('studid', $studentId)
            ->where('prospectusID', $subjectId)
            ->where('prospectusID', $subject->subjectID)
            ->where('deleted', 0)
            ->count();
    
        if ($transaction > 0) {
            return response()->json(['message' => 'Subject cannot be deleted due to associated transactions'], 403);
        }
    
        // Delete the subject
        DB::table('college_loadsubject')
            ->where('studid', $studentId)
            ->where('subjectID', $subjectId)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->update([
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                'deletedby' => auth()->user()->id,
                'deleted' => 1,
                'isDropped' => 1,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            ]);

        DB::table('college_stud_term_grades')
            ->where('studid', $studentId)
            ->where('schedid', $schedid )
            ->update([
                'prelim_status' => 8,
                'midterm_status' => 8,
                'prefinal_status' => 8,
                'final_status' => 8

            ]);

        DB::table('college_grading_scores')
            ->where('studid', $studentId)
            ->where('schedid', $schedid )
            ->update([
                'status_flag' => 8,
            ]);
    
        // Get student name by concatenating first, middle, last, and suffix (if available)
        $studentInfo = DB::table('studinfo')
            ->where('id', $studentId)
            ->first();
    
        $studentFullName = $studentInfo->firstname . ' ' .
            ($studentInfo->middlename ? $studentInfo->middlename . ' ' : '') .
            $studentInfo->lastname .
            ($studentInfo->suffix ? ' ' . $studentInfo->suffix : '');
    
        // Get the sectionId for sending notifications
        $sectionId = DB::table('college_enrolledstud')
            ->where('studid', $studentId)
            ->value('sectionID');
    
        // Send notifications to teachers
        $teachers = DB::table('teacher')->whereIn('usertypeid', [4,15])->select('id','userid', 'usertypeid')->get();
        foreach ($teachers as $teacher) {
            NotificationController::sendNotification(
                'Subject Deletion',
                'The subject ' . $subject->subjDesc . ' from student ledger has been dropped from this student: ' . $studentFullName,
                $teacher->userid, 
                'notification',
                'pending',
                '/finance/studledger',
                auth()->user()->id,
                $teacher->usertypeid
            );
        }
    
        return response()->json(['message' => 'Subject and associated schedule deleted successfully'], 200);
    }
    

    public function collegeLoadSubject(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'section' => 'required',
            'subjects' => 'required|array',
            'subjects.*.subject' => 'required',
        ]);

        $student = Student::find($validatedData['student_id']);

        foreach ($validatedData['subjects'] as $subjectData) {
            $student->subjects()->create([
                'section' => $subjectData['section'],
                'subject' => $subjectData['subject'],
                'lec' => $subjectData['lec'],
                'lab' => $subjectData['lab'],
                'credit_unit' => $subjectData['creditUnit'],
                'class' => $subjectData['class'],
                'schedule' => $subjectData['schedule'],
                'instructor' => $subjectData['instructor'],
                'room' => $subjectData['room'],
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function updateStudentSection(Request $request)
    {
        DB::table('studinfo')
            ->where('id', $request->studentId)
            ->update(['sectionid' => $request->sectionId]);

        return response()->json(['message' => 'Student section updated successfully']);
    }

    public function getStudentSections(Request $request)
    {
        $studentSections = DB::table('college_sections')
            ->join('college_loadsubject', 'college_sections.id', '=', 'college_loadsubject.sectionID')
            ->where('college_loadsubject.studid', $request->studentId)
            ->where('college_loadsubject.deleted', 0)
            ->select('college_sections.id', 'college_sections.sectionDesc')
            ->groupBy('college_sections.id', 'college_sections.sectionDesc')
            ->get();

        return response()->json($studentSections);
    }

    public function getStudentAllSections(Request $request)
    {
        $studentSubjects = DB::table('college_sections')
            ->join('college_loadsubject', 'college_sections.id', '=', 'college_loadsubject.sectionID')
            ->where('college_classsched.sectionID', $request->sectionId)
            ->where('college_subjects.deleted', 0)
            ->where('college_classsched.deleted', 0)
            ->select('college_subjects.id', 'college_subjects.subjCode', 'college_subjects.subjDesc')
            ->groupBy('college_subjects.id', 'college_subjects.subjCode', 'college_subjects.subjDesc')
            ->get();

        return response()->json($studentSubjects);
    }

    public function getFilteredStudents(Request $request)
    {
        // Start query builder
        $query = DB::table('studinfo')
            ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
            ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
            ->join('college_sections', 'studinfo.sectionID', '=', 'college_sections.id')
            ->select(
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'studinfo.id',
                'studinfo.sid',
                'college_enrolledstud.syid',
                'college_enrolledstud.semid',
                'college_enrolledstud.yearLevel',
                'college_courses.courseDesc',
                'college_courses.courseabrv',
                'college_sections.sectionDesc',
                'gradelevel.levelname',
                'studentstatus.description as studstatus',
                'college_enrolledstud.date_enrolled',
                'college_enrolledstud.updateddatetime'

            );
    
        // Apply Filters BEFORE calling get()
        if ($request->has('syid') && !empty($request->syid)) {
            $query->where('college_enrolledstud.syid', $request->syid);
        }
        if ($request->has('semid') && !empty($request->semid)) {
            $query->where('college_enrolledstud.semid', $request->semid);
        }
        if ($request->has('course') && !empty($request->course)) {
            $query->where('studinfo.courseid', $request->course);
        }
        if ($request->has('gradelevel') && !empty($request->gradelevel)) {
            $query->where('studinfo.levelid', $request->gradelevel);
        }
        if ($request->has('studentstatus') && !empty($request->studentstatus)) {
            $query->where('college_enrolledstud.studstatus', $request->studentstatus);
        }
    
        // Execute query AFTER all filters
        $filteredStudents = $query->get();
    
        // Process middle name
        foreach ($filteredStudents as $item) {
            $middlename = explode(" ", $item->middlename);
            $temp_middle = '';
            if ($middlename != null) {
                foreach ($middlename as $middlename_item) {
                    if (strlen($middlename_item) > 0) {
                        $temp_middle .= $middlename_item[0] . '.';
                    }
                }
            }
            $item->student = trim($item->lastname . ', ' . $item->firstname . ' ' . $item->suffix . ' ' . $temp_middle);
            $item->text = $item->sid . ' - ' . $item->student;
        }
    
        return response()->json($filteredStudents);
    }
    


   public function updateCourseStudent(Request $request) {
    DB::table('studinfo')
        ->where('id', $request->studentId)
        ->where('deleted', 0)
        ->update(['courseid' => $request->courseid]);

    return response()->json(['success' => true, 'message' => 'Course updated successfully']);
    }
    // public function updateCourseStudentEnrolled(Request $request) {
    //     DB::table('college_enrolledstud')
    //         ->where('studid', $request->studentId)
    //         ->where('deleted', 0)
    //         ->update(['courseid' => $request->courseid]);

    //     return response()->json(['success' => true, 'message' => 'Course updated successfully']);
    //     }

    public function getSections(Request $request)
    {
        $filter = $request->input('filter');
        $syid = $request->input('syid');
        $semid = $request->input('semid');

        $query = DB::table('college_classsched')
            ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
            ->where('college_classsched.syid', $syid)
            ->where('college_classsched.semesterid', $semid)
            ->where('college_classsched.deleted', 0);

        if ($filter && $filter !== 'all') {
            $query->where('college_classsched.sectionID', $filter);
        }

        $sections = $query
            ->select('college_sections.id', 'college_sections.sectionDesc')
            ->distinct()
            ->get();

        return response()->json($sections);
    }

    // public function prereqSubj(Request $request)
    // {
    //     $prereqSubj = DB::table('college_prospectus')
    //         ->join('college_subjprereq', 'college_prospectus.subjectID', '=', 'college_subjprereq.subjID')
    //         ->where('college_subjprereq.subjID', $request->subjID)
    //         ->select('college_prospectus.subjCode', 'college_prospectus.subjDesc')
    //         ->get();

    //     return response()->json($prereqSubj);
    // }

    public function deleteAddedSubj(Request $request)
    {
        $deleteSubj = DB::table('college_loadsubject')
            ->where('studid', $request->studentId)
            ->where('subjectID', $request->subjectID)
            ->where('college_loadsubject.subjectID', 0)
            ->where('college_loadsubject.sectionID', 0)
            ->update([
                'deleted' => 1,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }

//     public function updateNotEnrollStudentCourse(Request $request)
// {
//     $request->validate([
//         'studentId' => 'required|integer|exists:studinfo,id',
//         'courseId' => 'nullable|integer|exists:college_courses,id',
//     ]);

//     $studentId = $request->input('studentId');
//     $sectionId = $request->input('sectionId');
//     $courseId = $request->input('courseId');

//     // Update student section and course ID
//     DB::table('studinfo')
//         ->where('id', $studentId)
//         ->where('deleted', 0)
//         ->update([
//             'courseid' => $courseId,
//         ]);

//     return response()->json(['success' => true, 'message' => 'Student updated successfully.']);
// }

    // public function updateNotEnrollStudentSection(Request $request)
    // {
    //     $request->validate([
    //         'studentId' => 'required|integer|exists:studinfo,id',
    //         'sectionId' => 'nullable|integer|exists:college_sections,id',
    //     ]);

    //     $studentId = $request->input('studentId');
    //     $sectionId = $request->input('sectionId');

    //     // Update student section and course ID
    //     DB::table('studinfo')
    //         ->where('id', $studentId)
    //         ->where('deleted', 0)
    //         ->update([
    //             'sectionid' => $sectionId,
    //         ]);
    //     }

    public static function printStudentLoading($studentId, $syid, $semid, Request $request)
    {
        $student = DB::table('studinfo')
            ->leftjoin('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
            ->leftjoin('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
            ->leftjoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->leftjoin('college_sections', 'studinfo.sectionid', '=', 'college_sections.id')
            ->leftjoin('semester', 'college_enrolledstud.semid', '=', 'semester.id')
            ->leftjoin('sy', 'college_enrolledstud.syid', '=', 'sy.id')
            ->where('college_enrolledstud.studid', $studentId)
            ->where('college_enrolledstud.syid', $syid)
            ->where('college_enrolledstud.semid', $semid)
            ->where('college_enrolledstud.deleted', 0)
            ->select(
                'college_enrolledstud.studid',
                'studinfo.sid',
                'studinfo.levelid',
                'studinfo.dob',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.courseid',
                'studinfo.street',
                'studinfo.barangay',
                'studinfo.city',
                'studinfo.province',
                'studinfo.contactno',
                'studinfo.studtype',
                'college_sections.sectionDesc',
                'gradelevel.levelname',
                'college_courses.courseDesc',
                'semester.semester',
                'sy.sydesc'
            )
            ->first();

        $studentLoading = DB::table('college_classsched')
        ->join('college_loadsubject', 'college_classsched.id', '=', 'college_loadsubject.schedid')
        ->leftjoin('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
        ->leftjoin('studinfo', 'college_loadsubject.studid', '=', 'studinfo.id')
        ->join('college_year', 'college_loadsubject.syid', '=', 'college_year.id')
        ->leftjoin('college_semester', 'college_loadsubject.semid', '=', 'college_semester.id')
        ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
        ->leftJoin('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
        ->leftjoin('college_subjprereq', 'college_prospectus.id', '=', 'college_subjprereq.subjID')
        ->leftjoin('college_sections', 'college_loadsubject.sectionID', '=', 'college_sections.id')
        ->leftjoin('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
        ->leftjoin('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
        ->leftjoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
        ->leftjoin('days', 'college_scheddetail.day', '=', 'days.id')
            ->where('college_loadsubject.studid', $studentId)
            ->where('college_loadsubject.syid', $syid)
            ->where('college_loadsubject.semid', $semid)
            ->where('college_loadsubject.deleted', 0)
            ->where('college_classsched.deleted', 0)
            ->select(
                'college_prospectus.subjDesc',
                'college_prospectus.subjCode',
                'college_prospectus.lecunits',
                'college_prospectus.labunits',
                DB::raw("college_prospectus.lecunits + college_prospectus.labunits as credunits"),
                'college_scheddetail.schedotherclass',
                'days.description',
                'college_scheddetail.stime',
                'college_scheddetail.etime',
                'rooms.roomname',
                'teacher.firstname',
                'teacher.lastname'
            )
            ->groupBy('college_loadsubject.subjectID')
            ->get();

        $billingAssessment = DB::table('studledger')
                ->where('studid', $studentId)
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('deleted', 0)
                ->where('void', '0')
                ->select(
                    'particulars',
                    'amount'
                )
                ->get();

                
        if (
            auth()->user()->type == 7 ||
            auth()->user()->type == 9
        ) {
            try {

                $id = Crypt::decrypt($id);

            } catch (\Exception $e) {

                return back();

            }
        }

        $registrar = DB::table('teacher')
            ->where('tid', auth()->user()->email)
            ->first();


        //$title = $registrar->title != null ? $registrar->title.' ' : '';
        //$middlename = strlen($registrar->middlename) > 0 ? $registrar->middlename[0].'. ' : '';

        $registrar_sig = '';

        if (isset($registrar)) {
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            if (isset($registrar->middlename)) {
                $temp_middle = ' ' . $registrar->middlename[0] . '.';
            }
            if (isset($registrar->title)) {
                $temp_title = $registrar->title;
            }
            if (isset($registrar->suffix)) {
                $temp_suffix = ', ' . $registrar->suffix;
            }
            $registrar_sig = $registrar->firstname . $temp_middle . ' ' . $registrar->lastname;
        }

        // Fetch school info
        $scinfo = DB::table('schoolinfo')
            ->first();

        $pdf = PDF::loadView('superadmin.pages.student.studentloading_print', compact('student', 'studentLoading', 'scinfo', 'billingAssessment'))
            ->setPaper('legal');
        return $pdf->stream();
    }


    public static function conflictDetection(Request $request)
{
    // Get the authenticated dean ID
    $deanId = auth()->user()->id;

    // Get the schedules, school year ID (syid), and semester ID (semid) from the request
    $schedules = $request->get('schedules');
    $schedid = $request->get('schedid');
    $syid = $request->get('syid');
    $semid = $request->get('semid');

    // Initialize the base query for conflict detection
    $conflictQuery = DB::table('college_classsched')
        ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
        ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
        ->join('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
        ->join('days', 'college_scheddetail.day', '=', 'days.id')
        ->leftJoin('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
        ->leftJoin('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
        ->select(
            'college_classsched.id as schedid',
            'college_prospectus.subjDesc',
            'college_prospectus.subjCode',
            'college_scheddetail.day',
            'rooms.roomname',
            'college_scheddetail.stime',
            'college_scheddetail.etime',
            'days.description as dayname',
            DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),
            'college_classsched.sectionID'
        )
        ->where('college_classsched.syid', $syid)
        ->where('college_classsched.semesterid', $semid)
        // ->where('college_scheddetail.headerid', $schedid)
        ->where('college_classsched.deleted', 0)
        ->where('college_instructor.deleted', 0);

    // Loop through the provided schedules and add conditions for conflict detection
    foreach ($schedules as $schedule) {
        // Skip invalid schedules
        if (!isset($schedule['room'], $schedule['teacher'], $schedule['day'], $schedule['stime'], $schedule['etime'], $schedule['section_id'])) {
            continue;
        }

        // Add conditions for checking conflicts with other sections or subjects
        $conflictQuery->orWhere(function ($query) use ($schedule) {
            $query->where('college_scheddetail.roomID', $schedule['room'])
                ->where('college_scheddetail.day', $schedule['day'])
                ->where(function ($subQuery) use ($schedule) {
                    // Check if the schedule time overlaps with another class
                    $subQuery->whereBetween('college_scheddetail.stime', [$schedule['stime'], $schedule['etime']])
                        ->orWhereBetween('college_scheddetail.etime', [$schedule['stime'], $schedule['etime']])
                        ->orWhere(function ($q) use ($schedule) {
                            $q->where('college_scheddetail.stime', '<=', $schedule['stime'])
                                ->where('college_scheddetail.etime', '>=', $schedule['etime']);
                        });
                })
                // Check for conflicts in the same room and day with the same teacher or different teacher
                ->where(function ($q) use ($schedule) {
                    $q->where('teacher.id', $schedule['teacher'])
                        ->orWhereNull('teacher.id'); // Check if there is no teacher assigned (optional check)
                })
                // Ensure we are checking within the same or different section
                ->where('college_classsched.sectionID', '!=', $schedule['section_id']);
        });
    }

    // Execute the query to fetch potential conflicts
    $conflictSchedules = $conflictQuery->get();

    // Format the conflicts by groupings (schedid) and include details like room, teacher, and conflicting days
    $formattedConflicts = $conflictSchedules->groupBy('schedid')->map(function ($conflicts) {
        return [
            'schedid' => $conflicts->first()->schedid,
            'subjDesc' => $conflicts->first()->subjDesc,
            'subjCode' => $conflicts->first()->subjCode,
            'roomname' => $conflicts->first()->roomname,
            'teachername' => $conflicts->first()->teachername,
            'conflicting_days' => $conflicts->pluck('dayname')->unique()->implode(', '),
            'time_range' => $conflicts->first()->stime . ' - ' . $conflicts->first()->etime,
            'conflicting_section_ids' => $conflicts->pluck('sectionID')->unique()->implode(', ') // List of conflicting section IDs
        ];
    });

    // Return the conflicts in a structured JSON response
    return response()->json($formattedConflicts->values());
}

    
    public static function checkStudentStatus(Request $request)
    {
        $studentId = $request->input('studentId');
        $syid = $request->input('syid');
        $semid = $request->input('semid');

        $studStatus = DB::table('college_enrolledstud')
            ->where('college_enrolledstud.studid', $studentId)
            ->where('college_enrolledstud.syid', $syid)
            ->where('college_enrolledstud.semid', $semid)
            ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
            ->exists();

        return response()->json([
            'isEnrolled' => $studStatus,
            'status' => $studStatus ? 'ENROLLED' : 'NOT ENROLLED'
        ]);
    }


    public static function enrollmentDetails(Request $request)
    {
        $studentId = $request->input('studentId');

        $studInfoMore = DB::table('studinfo_more')
        ->join('studinfo', 'studinfo_more.studid', '=', 'studinfo.id')
        ->leftjoin('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
        ->leftjoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
        ->leftjoin('college_sections', 'college_enrolledstud.sectionID', '=', 'college_sections.id')
        ->leftjoin('sy', 'college_enrolledstud.syid', '=', 'sy.id')
        ->leftjoin('semester', 'college_enrolledstud.semid', '=', 'semester.id')
        ->where('studinfo.deleted', 0)
            ->where('studinfo_more.studid', $studentId)
            ->select('studinfo_more.psschoolname',
            'studinfo_more.pssy',
            'studinfo_more.gsschoolname',
            'studinfo_more.gssy',
            'studinfo_more.jhsschoolname',
            'studinfo_more.jhssy', 
            'studinfo_more.shsschoolname',
            'studinfo_more.shssy',
            'studinfo_more.shsstrand',
            'studinfo_more.collegeschoolname',
            'studinfo_more.collegecourse',
            'studinfo_more.collegesy',
            'college_sections.sectionDesc', 
            'gradelevel.levelname', 
            'sy.sydesc', 
            'semester.semester')
            ->first();
            return response()->json(['studinfo_more' => $studInfoMore]);
    }

public static function checkScheduleConflicts(Request $request)
    {
        // Get request inputs
        $studentId = $request->input('studid');
        $schedid = $request->get('schedid');
        $room = $request->get('room');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $days = $request->get('day');  // Decode the days array
        $stime = $request->get('stime');  // Decode the stime array
        $etime = $request->get('etime');  // Decode the etime array

        // Query to find conflicting schedules
        $conflictingSubjects = DB::table('college_loadsubject')
            ->join('college_classsched', 'college_loadsubject.schedid', '=', 'college_classsched.id')
            ->where('college_loadsubject.studid', $studentId)
            ->where('college_loadsubject.syid', $syid)
            ->where('college_loadsubject.semid', $semid)
            ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
            ->where('college_loadsubject.deleted', 0)
            ->get();

        foreach($conflictingSubjects as $item){
            $item->schedule = DB::table('college_scheddetail')
                ->where('deleted', 0)
                ->where('headerID', $item->schedid)
                ->get();
        }

        foreach($conflictingSubjects as $item){
            $item->withconflict = 0;
            foreach($item->schedule as $sched){
                $starttime = $sched->stime;
                $endtime = $sched->etime;

                $loadedSubjects = collect($conflictingSubjects)->where('schedid', '!=', $item->schedid);
        
                $conflict = DB::table('college_scheddetail')
                ->where('college_scheddetail.deleted', 0)
                ->join('college_classsched', 'college_scheddetail.headerID', '=', 'college_classsched.id')
                ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                ->join('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                ->wherein('headerID', $loadedSubjects->pluck('schedid')->toArray())
                ->where('day', $sched->day)
                ->where(function ($query) use ($starttime, $endtime) {
                    $query->whereBetween('stime', [$starttime, $endtime])
                          ->orWhereBetween('etime', [$starttime, $endtime]);
                })
                ->get();

                if(count($conflict) > 0){
                    $item->withconflict = 1;
                    $item->conflict = $conflict;
                }else{

                }
            }
        }

        $conflictingSubjects = $conflictingSubjects->where('withconflict', 1)->first();

        return response()->json($conflictingSubjects);

        // return response()->json($conflictingSubjects);
        // if (count($conflictingSubjects) > 0) {
        //     return response()->json(['conflict' => true, 'conflictingSubjects' => $conflictingSubjects], 422);
        // }
        // // No conflicts, return a success response
        // return response()->json(['conflict' => false], 200);
    }

    function getStudentCurriculum(Request $request){    
        $studentId = $request->input('studentId');

        $curriculum = DB::table('college_studentcurriculum')
                    ->where('studid', $studentId)
                    ->where('deleted',0)
                    ->get();
        return $curriculum;
    }

    function addStudentCurriculum(Request $request){
        $studentId = $request->input('studid');
        $curriculumID = $request->input('currid');

        DB::table('college_studentcurriculum')
            ->insert([
                'studid' => $studentId,
                'curriculumid' => $curriculumID,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            ]);
    }

    function updateStudentCurriculum(Request $request){
        $studentId = $request->input('studid');
        $curriculumID = $request->input('currid');

        DB::table('college_studentcurriculum')
            ->where('studid', $studentId)
            ->update([
                'curriculumid' => $curriculumID,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            ]);
    }
    
    function student_curriculum(Request $request){
        $studentId = $request->input('studid');
        $curriculum = DB::table('college_studentcurriculum')
                    ->join('college_curriculum', 'college_studentcurriculum.curriculumid', '=', 'college_curriculum.id')
                    ->where('college_studentcurriculum.studid', $studentId)
                    ->where('college_studentcurriculum.deleted',0)
                    ->select(
                        'college_curriculum.curriculumname',
                        'college_studentcurriculum.id'
                        )
                    ->first();

        return response()->json($curriculum);
    }

    public function print_all_student_information(Request $request)
{
    $syid = $request->input('sy');
    $semid = $request->input('sem');
    $course = $request->input('c');
    $gradelevel = $request->input('gradelevel');

    $schoolInfo = DB::table('schoolinfo')->select('schoolinfo.*')->first();

    $students = DB::table('studinfo')
        ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
        ->leftJoin('college_sections', 'studinfo.sectionid', '=', 'college_sections.id')
        ->leftJoin('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
        ->leftJoin('semester', 'college_sections.semesterID', '=', 'semester.id')
        ->leftJoin('sy', 'college_sections.syID', '=', 'sy.id')
        ->where('studinfo.deleted', 0)
        ->whereIn('gradelevel.id', [17, 18, 19, 20, 21, 22, 23, 24, 25]);

    // Apply filters if they exist
    if (!empty($syid)) {
        $students->where('college_sections.syID', $syid);
    }
    if (!empty($semid)) {
        $students->where('college_sections.semesterID', $semid);
    }
    if (!empty($course)) {
        $students->where('studinfo.courseid', $course);
    }
    if (!empty($gradelevel)) {
        $students->where('studinfo.levelid', $gradelevel);
    }

    $students = $students->select(
        'studinfo.sid',
        'studinfo.firstName',
        'studinfo.middleName',
        'studinfo.lastName',
        'studinfo.suffix',
        'studinfo.gender',
        'studinfo.contactno',
        'studinfo.semail',
        'studinfo.dob',
        'studinfo.mothername',
        'studinfo.fathername',
        'studinfo.guardianname',
        DB::raw("
            IF(studinfo.barangay != '' AND studinfo.city != '' AND studinfo.province != '', 
            CONCAT(studinfo.barangay, ', ', studinfo.city, ', ', studinfo.province), '') 
            AS fulladdress
        "),
        DB::raw("
            CASE 
                WHEN studinfo.ismothernum = 1 THEN CONCAT('Mother: ', studinfo.mothername, ': ', studinfo.mcontactno)
                WHEN studinfo.isfathernum = 1 THEN CONCAT('Father: ', studinfo.fathername, ': ', studinfo.fcontactno)
                WHEN studinfo.isguardannum = 1 THEN CONCAT('Guardian: ', studinfo.guardianname, ': ', studinfo.gcontactno)
                ELSE 'No emergency contact available'
            END AS emergency_contact
        "),
        'gradelevel.levelname',
        'college_courses.courseDesc',
        'college_sections.sectionDesc',
        'sy.sydesc',
        'semester.semester'
    )
    ->get();
    

    // Load the Blade view into PDF
    $pdf = PDF::loadView('superadmin.pages.student.printAllStudentInformation', compact('students', 'schoolInfo'))
        ->setPaper('legal', 'landscape');

    return $pdf->stream('Student_Loading_Report.pdf');
}


}
