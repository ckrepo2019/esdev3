<?php

namespace App\Http\Controllers\DeanControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use DB;
use PDF;

class DeanHomepage extends Controller
{
    public static function students(Request $request)
        {
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $gradelevel = $request->get('gradelevel');
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
            ->leftJoin('college_enrolledstud', function ($join) use ($syid, $semid) {
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
        
        public function getEnrolledSummary(Request $request)
        {   
            // Retrieve the parameters from the request
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $college = $request->get('college'); 
            $dean = $request->get('dean'); 
        
            // Get the list of colleges
            $colleges = DB::table('teacherdean')
                ->join('college_colleges', 'teacherdean.teacherid', '=', 'college_colleges.dean')
                ->where('college_colleges.deleted', 0)
                ->select('college_colleges.id as college_id', 'college_colleges.collegeDesc', 'college_colleges.acadprogid', 'teacherdean.teacherid')
                ->get();
        
            // Get the list of grade levels (academic levels)
            $gradeLevels = DB::table('gradelevel')
                ->where('deleted', 0)
                ->whereBetween('id', [17, 21])
                ->select('id', 'levelname')
                ->get();
        
            // Get the list of enrolled students (including colleges with zero enrollment)
            $enrolled = [];
        
            foreach ($gradeLevels as $gradeLevel) {
                foreach ($colleges as $college) {
                    $temp_enrolled = DB::table('college_colleges')
                        ->leftJoin('college_courses', 'college_colleges.id', '=', 'college_courses.collegeid')
                        ->leftJoin('studinfo', 'college_courses.id', '=', 'studinfo.courseid')
                        ->leftJoin('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
                        ->where('college_colleges.id', $college->college_id)
                        ->where('college_enrolledstud.deleted', 0)
                        ->where('college_enrolledstud.syid', $syid)
                        ->where('college_enrolledstud.semid', $semid)
                        ->where('college_enrolledstud.yearLevel', $gradeLevel->id)
                        ->select(
                            'college_colleges.collegeDesc',
                            'college_colleges.id as college_id',
                            DB::raw('COUNT(college_enrolledstud.studid) as enrolled_count')
                        )
                        ->groupBy('college_colleges.collegeDesc', 'college_colleges.id')
                        ->first();
        
                    // If no students are enrolled, still add the college with zero count
                    if (!$temp_enrolled) {
                        $temp_enrolled = (object)[
                            'collegeDesc' => $college->collegeDesc,
                            'college_id' => $college->college_id,
                            'enrolled_count' => 0,
                            'gradeLevel' => $gradeLevel->levelname
                        ];
                    } else {
                        $temp_enrolled->gradeLevel = $gradeLevel->levelname;
                    }
        
                    array_push($enrolled, $temp_enrolled);
                }
            }
        
            // Return all colleges and enrollment data
            return response()->json([
                'colleges' => $colleges,
                'gradeLevels' => $gradeLevels,
                'enrolled' => $enrolled
            ]);
        }
        

public function getDeanCourses(Request $request)
{
    // Get the dean's ID from the request
    $dean = $request->get('dean');

    // Retrieve colleges based on the dean's ID
    $colleges = DB::table('teacherdean')
        ->join('college_colleges', 'teacherdean.teacherid', '=', 'college_colleges.dean')
        ->where('college_colleges.deleted', 0)
        // ->where('college_colleges.dean', $dean)
        ->select('college_colleges.id as college_id', 'college_colleges.collegeDesc', 'college_colleges.dean as deanId')
        ->get();
        
    return response()->json($colleges);
}

    public function getGradeStatus(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $statusFilter = $request->get('statusFilter');
        $collegeid = $request->get('collegeid');
    
        $teacher = DB::table('users')
            ->join('teacher', 'users.id', '=', 'teacher.userid')
            ->where('teacher.userid', auth()->user()->id)
            ->first();
    
        $dean = DB::table('teacherdean')
            ->join('college_colleges', 'teacherdean.teacherid', '=', 'college_colleges.dean')
            ->where('teacherdean.teacherid', $teacher->id)
            ->where('college_colleges.deleted', 0)
            ->select('teacherdean.teacherid as deanid')
            ->first();
    
        $studentGrades = DB::table('college_studentprospectus')
            ->join('studinfo', 'college_studentprospectus.studid', '=', 'studinfo.sid')
            ->join('college_courses', 'college_studentprospectus.courseID', '=', 'college_courses.id')
            ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('college_colleges.id', $collegeid)
            ->whereBetween('gradelevel.id', [17, 25])
            ->where('college_colleges.dean', $teacher->id)
            ->select(
                'college_studentprospectus.prelemstatus',
                'college_studentprospectus.midtermstatus',
                'college_studentprospectus.finalstatus',
                'college_studentprospectus.prefistatus',
                'college_colleges.collegeDesc'
            )
            ->get();
            
        $statusCounts = [
            'Not Submitted' => 0,
            'Submitted' => 0,
            'Pending' => 0,
            'Approved' => 0,
            'Posted' => 0
        ];

        foreach ($studentGrades as $grade) {
            $include = false;

            switch ($statusFilter) {
                case 'drop':
                    if ($grade->midtermstatus == 9 || $grade->prefistatus == 9 || $grade->finalstatus == 9) {
                        $include = true;
                    }
                    break;
                case 'sub':
                    if ($grade->midtermstatus == 1 || $grade->prefistatus == 1 || $grade->finalstatus == 1) {
                        $include = true;
                    }
                    break;
                case 'posted':
                    if ($grade->midtermstatus == 4 || $grade->prefistatus == 4 || $grade->finalstatus == 4) {
                        $include = true;
                    }
                    break;
                case 'deanapp':
                    if ($grade->midtermstatus == 2 || $grade->prefistatus == 2 || $grade->finalstatus == 2) {
                        $include = true;
                    }
                    break;
                case 'pen':
                    if ($grade->midtermstatus == 3 || $grade->prefistatus == 3 || $grade->finalstatus == 3) {
                        $include = true;
                    }
                    break;
                case 'app':
                    if ($grade->midtermstatus == 7 || $grade->prefistatus == 7 || $grade->finalstatus == 7) {
                        $include = true;
                    }
                    break;
                case 'uns':
                    if ($grade->midtermstatus == null || $grade->prefistatus == null || $grade->finalstatus == null) {
                        $include = true;
                    }
                    break;
                case 'inc':
                    if ($grade->midtermstatus == 8 || $grade->prefistatus == 8 || $grade->finalstatus == 8) {
                        $include = true;
                    }
                    break;
                default:
                    $include = true;
                    break;
            }

            if ($include) {
                if ($grade->midtermstatus == 1 || $grade->prefistatus == 1 || $grade->finalstatus == 1) {
                    $statusCounts['Submitted']++;
                }
                elseif ($grade->midtermstatus == 3 || $grade->prefistatus == 3 || $grade->finalstatus == 3) {
                    $statusCounts['Pending']++;
                }
                elseif ($grade->midtermstatus == 2 || $grade->prefistatus == 2 || $grade->finalstatus == 2) {
                    $statusCounts['Approved']++;
                }
                elseif ($grade->midtermstatus == 4 || $grade->prefistatus == 4 || $grade->finalstatus == 4) {
                    $statusCounts['Posted']++;
                }
                else {
                    $statusCounts['Not Submitted']++;
                }
            }
        }
        return response()->json(['studentGrades'=> $statusCounts, 'dean' => $dean]);
    }

    public function getEnrolledStudentsByCourse(Request $request)
    {
        $syid = $request->input('syid');
        $semid = $request->input('semid');
        $collegeId = $request->input('college_id');

        $teacher = DB::table('users')
            ->join('teacher', 'users.id', '=', 'teacher.userid')
            ->where('teacher.userid', auth()->user()->id)
            ->first();

        $dean = DB::table('teacherdean')
            ->join('college_colleges', 'teacherdean.teacherid', '=', 'college_colleges.dean')
            ->where('teacherdean.teacherid', $teacher->id)
            ->where('college_colleges.deleted', 0)
            ->select('teacherdean.teacherid as deanid')
            ->first();

        $enrollments = DB::table('college_colleges')
            ->leftJoin('college_courses', 'college_colleges.id', '=', 'college_courses.collegeid')
            ->leftJoin('studinfo', 'college_courses.id', '=', 'studinfo.courseid')
            ->leftJoin('college_enrolledstud', function ($join) use ($syid, $semid) {
                $join->on('studinfo.id', '=', 'college_enrolledstud.studid')
                    ->where('college_enrolledstud.syid', $syid)
                    ->where('college_enrolledstud.semid', $semid);
            })
            ->where('college_colleges.dean', $dean->deanid)
            ->when($collegeId, function ($query) use ($collegeId) { 
                $query->where('college_colleges.id', $collegeId);
            })
            ->select(
                'college_colleges.id as college_id',
                'college_colleges.collegeDesc as college_name',
                'college_courses.id as course_id',
                'college_courses.courseabrv',
                DB::raw('COUNT(college_enrolledstud.studid) as total_enrollees')
            )
            ->groupBy(
                'college_colleges.id',
                'college_colleges.collegeDesc',
                'college_courses.id',
                'college_courses.courseabrv'
            )
            ->get();

        $data = [];
        foreach ($enrollments as $enrollment) {
            $collegeName = $enrollment->college_name;
            $courseAbvr = $enrollment->courseabrv;

            if (!isset($data[$collegeName][$courseAbvr])) {
                $data[$collegeName][$courseAbvr] = 0;
            }
            $data[$collegeName][$courseAbvr] += $enrollment->total_enrollees;
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
