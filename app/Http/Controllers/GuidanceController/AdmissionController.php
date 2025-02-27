<?php

namespace App\Http\Controllers\GuidanceController;

use App\Http\Controllers\SuperAdminController\Setup\SubjectSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use File;
use PDF;

class AdmissionController extends \App\Http\Controllers\Controller
{

    public function entranceexam_setup()
    {
        $data = DB::table('guidance_test_types')->where('deleted', 0)->get();

        return view('guidanceV2.pages.admission.entranceexam_setup')->with('types', $data);
    }
    public function admission_view(Request $request)
    {
        $page_desc = '';
        $jsonCategories = [];
        $jsonDiagnosticTest = [];
        $jsonExamDates = [];
        if ($request->page == 1) {
            $page_desc = 'Percentage Setup';
        } elseif ($request->page == 2) {
            $page_desc = 'Diagnostic Setup';
        } else {
            $page_desc = 'Entrance Examination Dates';
        }
        $jsonCategories = DB::table('guidance_passing_rate')
            ->where('guidance_passing_rate.deleted', 0)
            ->join('academicprogram', 'guidance_passing_rate.acadprog_id', '=', 'academicprogram.id')
            ->select(
                'guidance_passing_rate.*',
                'academicprogram.progname',
                DB::raw('DATE_FORMAT(guidance_passing_rate.created_at, "%M %e, %Y") as formatted_created_at')
            )
            ->get();
        $jsonDiagnosticTest = DB::table('guidance_entrance_exam')
            ->where('guidance_entrance_exam.deleted', 0)
            ->join('guidance_test_category', 'guidance_entrance_exam.categoryid', '=', 'guidance_test_category.id')
            ->select(
                'guidance_entrance_exam.*',
                'guidance_test_category.category_name',
                DB::raw('DATE_FORMAT(guidance_entrance_exam.created_at, "%M %e, %Y") as formatted_created_at')
            )
            ->get();

        $jsonExamDates = DB::table('guidance_examdate')
            ->where('guidance_examdate.deleted', 0)
            ->join('sy', 'guidance_examdate.schoolyear', '=', 'sy.id')
            ->join('guidance_passing_rate', 'guidance_examdate.examid', '=', 'guidance_passing_rate.id')
            ->select(
                'guidance_examdate.*',
                'guidance_passing_rate.description',
                DB::raw('DATE_FORMAT(guidance_examdate.examinationdate, "%M %e, %Y %h:%i %p") as formatted_examdate'),
                'sy.sydesc'
            )->get();

        foreach ($jsonExamDates as $value) {
            $students = DB::table('admission_student_information')->where('examdate_id', $value->id)->where('deleted', 0)->count();
            $value->available = $value->takers - $students;
            $value->reserved = $students;
        }

        foreach ($jsonCategories as $key => $value) {
            $value->index = $key + 1;
        }

        return view(
            'guidanceV2.pages.admission.admission',
            [
                'current_page' => $request->page,
                'page_desc' => $page_desc,
                'jsonCategories' => $jsonCategories,
                'jsonDiagnosticTest' => $jsonDiagnosticTest,
                'jsonExamDates' => $jsonExamDates
            ]
        );
    }

    public function getAllAdmissionSetup()
    {
        $jsonCategories = [];
        $jsonDiagnosticTest = [];
        $jsonExamDates = [];

        $jsonCategories = DB::table('guidance_passing_rate')
            ->where('guidance_passing_rate.deleted', 0)
            ->join('academicprogram', 'guidance_passing_rate.acadprog_id', '=', 'academicprogram.id')
            ->select(
                'guidance_passing_rate.*',
                'academicprogram.progname',
                DB::raw('DATE_FORMAT(guidance_passing_rate.created_at, "%M %e, %Y") as formatted_created_at')
            )
            ->get();
        $jsonDiagnosticTest = DB::table('guidance_entrance_exam')
            ->where('guidance_entrance_exam.deleted', 0)
            ->join('guidance_test_category', 'guidance_entrance_exam.categoryid', '=', 'guidance_test_category.id')
            ->select(
                'guidance_entrance_exam.*',
                'guidance_test_category.category_name',
                DB::raw('DATE_FORMAT(guidance_entrance_exam.created_at, "%M %e, %Y") as formatted_created_at')
            )
            ->get();

        $jsonExamDates = DB::table('guidance_examdate')
            ->where('guidance_examdate.deleted', 0)
            ->join('sy', 'guidance_examdate.schoolyear', '=', 'sy.id')
            ->join('guidance_passing_rate', 'guidance_examdate.examid', '=', 'guidance_passing_rate.id')
            ->select(
                'guidance_examdate.*',
                'guidance_passing_rate.description',
                DB::raw('DATE_FORMAT(guidance_examdate.examinationdate, "%M %e, %Y %h:%i %p") as formatted_examdate'),
                'sy.sydesc'
            )->get();

        foreach ($jsonExamDates as $value) {
            $students = DB::table('admission_student_information')->where('examdate_id', $value->id)->where('deleted', 0)->count();
            $value->available = $value->takers - $students;
            $value->reserved = $students;
        }

        foreach ($jsonCategories as $key => $value) {
            $value->index = $key + 1;
        }

        return response()->json([
            'jsonCategories' => $jsonCategories,
            'jsonDiagnosticTest' => $jsonDiagnosticTest,
            'jsonExamDates' => $jsonExamDates
        ]);
    }

    public function endExam(Request $request)
    {
        DB::table('guidance_examdate')->where('id', $request->id)->update(['status' => 1]);

        return response()->json(['status' => 'success', 'message' => 'Exam has been ended']);
    }
    public function startExam(Request $request)
    {
        DB::table('guidance_examdate')->where('id', $request->id)->update(['status' => 0]);

        return response()->json(['status' => 'success', 'message' => 'Exam Restarted']);
    }

    public function applicant_view(Request $request)
    {
        $page_desc = '';
        // Usage
        $jsonPreregistered = $this->getStudentInformationByStatus(0, 0, 0, 0);
        $jsonVerified = $this->getStudentInformationByStatus(1, 0, 0, 0);
        $jsonAccepted = $this->getStudentInformationByStatus(2, 0, 0, 0);

        if ($request->page == 1) {
            $page_desc = 'Pre-Registered Applicants';
        } else if ($request->page == 2) {
            $page_desc = 'Verified Applicants';
        } else {
            $page_desc = 'Accepted Applicants';
        }

        $examdates = DB::table('guidance_examdate')
            ->where('guidance_examdate.deleted', 0)
            ->join('sy', 'guidance_examdate.schoolyear', '=', 'sy.id')
            ->join('guidance_passing_rate', 'guidance_examdate.examid', '=', 'guidance_passing_rate.id')
            ->join('academicprogram', 'guidance_examdate.acadprog', '=', 'academicprogram.id')
            ->select(
                'guidance_examdate.*',
                'guidance_passing_rate.description',
                DB::raw('DATE_FORMAT(guidance_examdate.examinationdate, "%M %e, %Y") as formatted_examdate'),
                'examinationdate AS start',
                'examinationdate AS end',
                'sy.sydesc',
                'academicprogram.progname'
            )->get();

        foreach ($examdates as $item) {

            $applicants = DB::table('admission_student_information')
                ->where('deleted', 0)
                ->where('examdate_id', $item->id)
                ->count();

            $item->slot_available = $item->takers - $applicants;

            $expiry = $item->examinationdate;
            $currDate = date('Y-m-d');

            if ($currDate > $expiry) {
                $item->expired = true;
                $item->color = "red"; // Set color to red for expired items
                $item->title = "Expired"; // Optional: Update title for expired items
            } else {
                $item->expired = false;
                // Color assignment based on availability if not expired
                if ($item->slot_available == 0) {
                    $item->color = "red";
                    $item->title = "Fully Booked";
                } else {
                    $item->color = "green";
                    $item->title = "Available";
                }
            }

        }


        return view(
            'admission.pages.applicant',
            [
                'current_page' => $request->page,
                'examdates' => $examdates,
                'page_desc' => $page_desc,
                'jsonPreregistered' => $jsonPreregistered,
                'jsonVerified' => $jsonVerified,
                'jsonAccepted' => $jsonAccepted
            ]
        );
    }

    public function save_answer(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'id' => 'required|integer', // question ID
            'studid' => 'required|integer', // student ID
            'answer' => 'required|string' // student's answer
        ]);

        // Check if the record exists and update it if it does, or insert a new one if it doesn't
        $updated = DB::table('admission_answer_history')
            ->where('deleted', 0)
            ->updateOrInsert(
                [
                    'questionid' => $request->id,
                    'studid' => $request->studid
                ],
                [
                    'answer' => $request->answer,
                    'status' => $request->status,
                    'points' => $request->points,
                    'updated_at' => now() // Ensure the updated_at timestamp is set
                ]
            );

        // Check if the update/insert was successful
        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Answer saved successfully.'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save the answer. Please try again.'
            ], 500);
        }
    }


    public function diagnostic_view(Request $request)
    {
        $request->validate([
            'poolingnumber' => 'required|string'
        ]);


        // Fetch student information
        $result = DB::table('admission_student_information')
            ->where('poolingnumber', $request->poolingnumber)
            ->where('admission_student_information.deleted', 0)
            ->join('gradelevel', 'admission_student_information.gradelevel_id', '=', 'gradelevel.id')
            ->select(
                'admission_student_information.*',
                'gradelevel.levelname'
            )
            ->first();

        if ($result->acadprog_id == 6) {
            $result->courseDesc = DB::table('college_courses')
                ->where('id', $result->course_id)
                ->value('courseDesc');
        } elseif ($result->acadprog_id == 5) {
            $result->courseDesc = DB::table('sh_strand')
                ->where('id', $result->strand_id)
                ->value('strandname');
        } else {
            $result->courseDesc = 'Not Applicable';
        }

        // Check if result is found
        if (!$result) {
            return redirect()->back()->with('error', 'Student not found.');
        }



        // Fetch the exam date from the database
        $examdate = DB::table('guidance_examdate')->where('id', $result->examdate_id)->first();

        // Parse the exam date using Carbon with Asia/Manila timezone
        $carbonExamDate = Carbon::parse($examdate->examinationdate, 'Asia/Manila');

        // Set the timezone for the current date and time comparison
        $now = Carbon::now('Asia/Manila');

        // Calculate the difference in hours
        $hoursDifference = $now->diffInHours($carbonExamDate, false);

        // Format the exam date with AM/PM notation
        $result->examdate = $carbonExamDate->format('F j, Y h:i:s A');

        // Determine the exam date status
        if ($examdate->status == 1) {
            $result->examdate_stat = 'expired';
            $result->examdateStat = 'Exam date has closed (' . $carbonExamDate->diffForHumans($now) . ').';
        } elseif ($carbonExamDate->isFuture()) {
            $result->examdate_stat = 'soon';
            $result->examdateStat = 'Exam date has not yet started (' . $carbonExamDate->diffForHumans($now) . ').';
        } else {
            $result->examdate_stat = 'ongoing';
            $result->examdateStat = 'Exam date is ongoing (Today at ' . $carbonExamDate->format('h:i A') . ').';
        }

        // Ensure your $result object is properly defined



        // Fetch subjects based on the exam setup ID
        $subjects = DB::table('guidance_test_category')
            ->where('guidance_test_category.category_deleted', 0)
            ->where('category_deleted', 0)
            ->where('guidance_entrance_exam.deleted', 0)
            ->where('passing_rate_setup_id', $result->exam_setup_id)
            ->join('guidance_entrance_exam', 'guidance_test_category.id', '=', 'guidance_entrance_exam.categoryid')
            ->select(
                'guidance_test_category.*',
                'guidance_entrance_exam.id AS examtitleId',
                'guidance_entrance_exam.examtitle AS examTitle'
            )
            // ->groupBy('guidance_test_category.id')
            ->get();


        // dd($subjects);

        // Process each subject to determine if the test is finished and get the current time and day
        if ($subjects->count()) {
            foreach ($subjects as $sub) {
                $sub->isfinished = false;
                $sub->elapsedMinutes = 0;
                $sub->elapsedSeconds = 0;

                $isdonetest = DB::table('admission_test_result')
                    ->where('deleted', 0)
                    ->where('examid', $sub->examtitleId)
                    ->where('studid', $result->id)
                    ->first();

                $totalMinutes = ($sub->category_timelimit_hrs ?? 0) * 60 + ($sub->category_timelimit_min ?? 0);
                $totalSeconds = $totalMinutes * 60;

                if ($isdonetest) {
                    // Ensure the startTime is parsed correctly
                    $startTime = Carbon::parse($isdonetest->starttime, 'Asia/Manila');
                    $currentTime = Carbon::now('Asia/Manila');

                    // Calculate the elapsed seconds
                    $elapsedSeconds = $startTime->diffInSeconds($currentTime);

                    if ($isdonetest->finishtime) {
                        $sub->isfinished = true;
                    }
                    if ($elapsedSeconds > $totalSeconds) {
                        $sub->isfinished = true;
                    }
                    $sub->elapsedMinutes = floor($elapsedSeconds / 60);
                    $sub->elapsedSeconds = $elapsedSeconds % 60;
                }

                // Get the current time and day in 'Asia/Manila' timezone
                $sub->now = Carbon::now('Asia/Manila')->toDateTimeString();
            }
        }

        // dd($result);

        return view(
            'admission.pages.admissiontest',
            [
                'subjects' => $subjects,
                'studinfo' => $result,
                'poolingnumber' => $request->poolingnumber
            ]
        );
    }

    public function getHighPassers()
    {
        $courses = DB::table('college_courses')->where('deleted', 0)->get()->toArray();
        $students = $this->processStudentTestResults(0, null);
        // return $students;
        // Sort the array of objects based on the 'totalScore' property in descending order
        usort($students, function ($a, $b) {
            return $b->totalScore <=> $a->totalScore;
        });

        foreach ($courses as $course) {
            $course->students = [];
            $course->average = 0;
            foreach ($students as $stud) {
                if ($stud->final_assign_course == $course->id && $stud->passedOverall) {
                    $course->students[] = $stud;
                    $course->average += $stud->totalScore;
                }
            }
        }
        usort($courses, function ($a, $b) {
            return $b->average <=> $a->average;
        });

        // dd($courses);

        // Get the top 4 objects
        $topScores = array_slice($courses, 0, 4);

        // dd($topScores);
        return response()->json($topScores);
    }

    public function generatePdf()
    {
        // Fetch data you want to display in the PDF
        $courses = DB::table('college_courses')->where('deleted', 0)->get(); // Add your data fetching logic here
        $gradelevels = DB::table('gradelevel')->where('deleted', 0)->get();
        $request = new Request(['admstatus' => 1]);
        $students = $this->processStudentTestResults(0, $request);

        // Load a view and pass the data
        $pdf = PDF::loadView('admission/pages/waitlist_report', compact('students'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        // Download the PDF file
        return $pdf->stream('admission_result.pdf');
    }



    public function admission_result(Request $request)
    {
        $students = $this->processStudentTestResults(0, null);
        // dd($students);

        $courses = DB::table('college_courses')->where('deleted', 0)->get();
        $gradelevels = DB::table('gradelevel')->where('deleted', 0)->get();

        return view('admission.pages.admissionresult', ['result' => $students, 'courses' => $courses, 'gradelevels' => $gradelevels]);
    }

    public function filterCourseByDepartment(Request $request)
    {
        $data = [];
        $courses = DB::table('college_courses')->where('deleted', 0)->where('collegeid', $request->department)->get();
        foreach ($courses as $course) {
            $data[] = [
                'id' => $course->id,
                'text' => $course->courseabrv
            ];
        }

        return response()->json($data);
    }

    public function allTeachers()
    {
        return DB::table('teacher')
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.deleted', 0)
            ->select('teacher.*', 'usertype.utype')
            ->get();

        // return DB::table('teacher')->where('deleted', 0)->get();
    }

    // public function processStudentTestResults()
    // {
    //     $student_results = [];

    //     $students = DB::table('admission_student_information')
    //         ->where('admission_student_information.deleted', 0)
    //         ->where('admission_student_information.status', 1)
    //         ->where('admission_student_information.exam_setup_id', '>', 0)
    //         // ->join('college_courses', 'admission_student_information.course_id', '=', 'college_courses.id')
    //         ->select(
    //             'admission_student_information.*',
    //             // 'college_courses.courseabrv',
    //             // 'college_courses.courseDesc',
    //             DB::raw('CONCAT(admission_student_information.lname, " ", admission_student_information.fname) AS studname'),
    //             DB::raw('DATE_FORMAT(admission_student_information.examdate, "%M %e, %Y") as formatted_examdate')
    //         )
    //         ->get();

    //     foreach ($students as $student) {

    //         $test_results = DB::table('admission_test_result')
    //             ->where('studid', $student->id)
    //             ->where('admission_test_result.deleted', 0)
    //             ->join('guidance_entrance_exam', 'admission_test_result.examid', '=', 'guidance_entrance_exam.id')
    //             ->join('guidance_test_category', 'guidance_entrance_exam.categoryid', '=', 'guidance_test_category.id')
    //             // ->join('guidance_course_percentage', 'guidance_test_category.category_name', '=', 'guidance_course_percentage.category_name')
    //             ->select(
    //                 'admission_test_result.*',
    //                 'guidance_entrance_exam.categoryid',
    //                 'guidance_test_category.category_name',
    //                 'guidance_test_category.category_percent',
    //                 'guidance_test_category.passing_rate_setup_id'
    //             )
    //             ->get();

    //         if (count($test_results) > 0) {
    //             if ($student->acadprog_id == 6) {
    //                 $weighted_average_score = 0;
    //                 foreach ($test_results as $item) {
    //                     // $course_percentage = DB::table('guidance_course_percentage')
    //                     //     ->where('courseid', $student->course_id)
    //                     //     ->where('passing_rate_setup_id', $item->passing_rate_setup_id)
    //                     //     ->select(
    //                     //         'general_percentage',
    //                     //         'isprovision'
    //                     //     )
    //                     //     ->first();

    //                     // $general_course_percentage = 0;
    //                     $weighted_average_formula = 0;

    //                     // if ($course_percentage) {
    //                     //     $general_course_percentage = $course_percentage->general_percentage;
    //                     //     $item->general_course_percentage = $general_course_percentage;
    //                     //     $item->isprovision = $course_percentage->isprovision;
    //                     //     // $student->overall = $course_percentage->general_percentage;
    //                     // }

    //                     $totalquestions = DB::table('guidance_test_questions')
    //                         ->where('examid', $item->examid)
    //                         ->where('deleted', 0)
    //                         ->get();

    //                     // total pts.
    //                     $totalpoints_ofques = $totalquestions->sum('points');

    //                     // passing score base on course percentage ( totalitem * general course % )
    //                     $passingscore = $totalpoints_ofques * ($item->category_percent / 100);
    //                     // score equivalent into decimal or pecent (score / totalitems)
    //                     $percent_equiv_toscore = ($item->score / $totalpoints_ofques);
    //                     // compare the score to the passing score
    //                     if ($item->score >= $passingscore) {
    //                         $item->passed = true;
    //                     } else {
    //                         $item->passed = false;
    //                     }
    //                     // get individual average score per category
    //                     // $weighted_average_formula = (($item->category_percent / 100) * $percent_equiv_toscore);
    //                     // total all score per category
    //                     // $weighted_average_score += $weighted_average_formula;


    //                     // $item->general_course_percentage = $general_course_percentage;
    //                     // $item->gwa_category = number_format(($percent_equiv_toscore * ($item->category_percent / 100)) * 100, 2);
    //                     $item->totalquestion = $totalpoints_ofques;
    //                     $item->passingscore = $passingscore;
    //                     $item->percent_equiv_toscore = $percent_equiv_toscore;


    //                     $student->passing_rate_setup_id = $item->passing_rate_setup_id;
    //                 }

    //                 $student->test_result = $test_results;
    //                 // $student->general_weighted_avg = number_format($weighted_average_score * 100, 2);
    //                 // if ($weighted_average_score >= ($general_course_percentage / 100)) {
    //                 //     $student->passed = true;
    //                 // } else {
    //                 //     $student->passed = false;
    //                 // }

    //                 // Recommended Courses
    //                 // $fitted_course = DB::table('guidance_course_percentage')
    //                 //     ->where('general_percentage', '<=', $student->general_weighted_avg)
    //                 //     ->where('passing_rate_setup_id', $student->passing_rate_setup_id)
    //                 //     ->join('college_courses', 'guidance_course_percentage.courseid', '=', 'college_courses.id')
    //                 //     ->get();

    //                 // if (count($fitted_course) > 0) {
    //                 //     $listcourse = [];
    //                 //     foreach ($fitted_course as $elem) {

    //                 //         $listcourse[] = $elem;
    //                 //     }
    //                 //     // dd($listcourse);
    //                 //     $student->fitted_course = $listcourse;
    //                 // }

    //                 $student_results[] = $student;
    //             }

    //         } else {
    //             $student->overall = 0;
    //         }

    //     }

    //     return $student_results;
    // }

    public function processStudentTestResults($id, $request)
    {
        $student_results = [];

        // Fetch student information
        $students = DB::table('admission_student_information')
            ->where('admission_student_information.deleted', 0)
            ->when(isset($request) && !is_null($request->acadprog), function ($query) use ($request) {
                return $query->where('admission_student_information.acadprog_id', $request->acadprog);
            })
            ->when(isset($request) && !is_null($request->gradelevel), function ($query) use ($request) {
                return $query->where('admission_student_information.gradelevel_id', $request->gradelevel);
            })
            ->when(isset($request) && !is_null($request->course), function ($query) use ($request) {
                return $query->where('admission_student_information.course_id', $request->course);
            })
            ->when(isset($request) && !is_null($request->strand), function ($query) use ($request) {
                return $query->where('admission_student_information.strand_id', $request->strand);
            })
            ->when(isset($request) && !is_null($request->admstatus), function ($query) use ($request) {
                return $query->where('admission_student_information.status', $request->admstatus);
            })
            ->when(isset($request) && is_null($request->admstatus), function ($query) {
                return $query->whereIn('admission_student_information.status', [1, 2, 3]);
            })
            ->when(isset($id) && $id > 0, function ($query) use ($id) {
                return $query->where('admission_student_information.id', $id);
            })
            ->where('admission_student_information.exam_setup_id', '>', 0)
            ->join('guidance_examdate', 'admission_student_information.examdate_id', '=', 'guidance_examdate.id')
            ->leftJoin('college_courses', function ($join) {
                $join->on('admission_student_information.course_id', '=', 'college_courses.id')
                    ->whereIn('acadprog_id', [6,8]);
            })
            ->leftJoin('sh_strand', function ($join) {
                $join->on('admission_student_information.strand_id', '=', 'sh_strand.id')
                    ->where('acadprog_id', 5);
            })
            ->leftJoin('college_courses AS final_courseabrv', function ($join) {
                $join->on('admission_student_information.final_assign_course', '=', 'final_courseabrv.id')->whereIn('acadprog_id', [6,8]);

            })
            ->leftJoin('sh_strand AS final_strand', function ($join) {
                $join->on('admission_student_information.final_assign_course', '=', 'final_strand.id')->where('acadprog_id', 5);

            })
            ->select(
                'admission_student_information.*',
                DB::raw('CONCAT(lname, " ", fname) AS studname'),
                DB::raw('DATE_FORMAT(guidance_examdate.examinationdate, "%M %e, %Y %h:%i %p") as formatted_examdate'),
                'college_courses.courseabrv',
                'college_courses.courseDesc',
                'final_courseabrv.courseabrv as final_courseabrv',
                'final_courseabrv.courseDesc as final_courseDesc',
                'final_strand.strandname as final_strandname',
                'final_strand.strandcode as final_strandcode',
                'sh_strand.strandname',
                'sh_strand.strandcode'
            )
            ->orderBy('admission_student_information.status', 'asc')
            ->get();

        // return $students;

        foreach ($students as $student) {
            $student->passallrequired = true;
            $student->hasRequiredSubj = false;
            $student->hasTest = false;
            $student->recommendedcourse = [];
            $totalScore = 0;
            $mySetup = DB::table('guidance_passing_rate')->where('id', $student->exam_setup_id)->first();
            $student->examSetup = $mySetup->description;
            $alternateCourseArr = $mySetup && $mySetup->alternate_course ? explode(',', $mySetup->alternate_course) : [];

            $subjects = DB::table('guidance_test_category')
                ->where('guidance_test_category.category_deleted', 0)
                ->where('guidance_test_category.passing_rate_setup_id', $student->exam_setup_id)
                ->leftJoin('guidance_entrance_exam', 'guidance_test_category.id', '=', 'guidance_entrance_exam.categoryid')
                ->select(
                    'guidance_test_category.*',
                    'guidance_entrance_exam.examtitle',
                    'guidance_entrance_exam.checkingoption',
                    'guidance_entrance_exam.id AS examid'
                )
                ->get();

            if (count($subjects) > 0) {

                foreach ($subjects as $key => $subject) {
                    $subject->index = $key + 1;

                    if ($subject->required) {
                        $student->hasRequiredSubj = true;
                    }

                    $test_result = DB::table('admission_test_result')
                        ->where('studid', $student->id)
                        ->where('examid', $subject->examid)
                        ->where('deleted', 0)
                        ->get();

                    if (count($test_result) > 0) {
                        $student->hasTest = true;
                        foreach ($test_result as $value) {
                            $total_questions = DB::table('guidance_test_questions')
                                ->where('examid', $value->examid)
                                ->where('deleted', 0)
                                ->get();

                            // $passingInDecimal = $subject->category_percent / 100;
                            $total_points_of_questions = $total_questions->sum('points');
                            if (!$total_points_of_questions || $total_points_of_questions == 0) {
                                $student->hasTest = false;
                                return;
                            }

                            if ($value->score > $total_points_of_questions) {
                                $value->score = $total_points_of_questions;
                            }
                            $passing_score = ($total_points_of_questions * ($subject->category_percent / 100)); // Calculate passing score
                            $scoreInDecimal = $value->score / $total_points_of_questions;
                            $scoreInPercent = $scoreInDecimal * 100;
                            $subject->subscore = $value->score;
                            $subject->totalquestion = $total_points_of_questions;
                            $subject->passingscore = $passing_score; // Assign rounded passing score to the subject
                            $subject->scoreInDecimal = $scoreInDecimal;
                            $subject->scoreInPercent = number_format($scoreInPercent, 2);
                            $subject->passingInDecimal = floatval($subject->category_percent);
                            // $subject->passed = $scoreInDecimal >= $passingInDecimal;
                            $subject->passed = $scoreInPercent >= floatval($subject->category_percent);
                            // $subject->passingInDecimal = $passingInDecimal;

                            $totalScore += $scoreInPercent;

                            if (!$subject->passed && $subject->required) {
                                $student->passallrequired = false;
                            }
                        }
                    } else {
                        $student->passallrequired = false;
                    }

                    $subject->test_result = $test_result;
                }

                $avg = $totalScore / $subjects->count();
                $student->totalScore = number_format($avg, 2);
                $student->subjects = $subjects;
                $student->setupAverage = $mySetup->average;

                $student->fitted_course_id = '';
                $student->fitted_courseAbrv = '';
                $student->fitted_courseDesc = '';

                if (($student->hasRequiredSubj && $student->passallrequired)) {
                    $student->passedOverall = true;

                    if ($student->acadprog_id == 5) {
                        $fitted_course = DB::table('sh_strand')->where('id', $student->strand_id)->first();
                        if ($fitted_course) {
                            $student->fitted_course_id = $fitted_course->id;
                            $student->fitted_courseAbrv = $fitted_course->strandcode;
                            $student->fitted_courseDesc = $fitted_course->strandname;
                        }
                        $student->recommendedcourse[] = DB::table('sh_strand')->where('id', $student->strand_id)->first();
                    } else if ($student->acadprog_id == 6 || $student->acadprog_id == 8) {
                        $fitted_course = DB::table('college_courses')->where('id', $student->course_id)->first();
                        if ($fitted_course) {
                            $student->fitted_course_id = $fitted_course->id;
                            $student->fitted_courseAbrv = $fitted_course->courseabrv;
                            $student->fitted_courseDesc = $fitted_course->courseDesc;
                        }
                        $setupArr = DB::table('guidance_passing_rate')->where('id', $student->exam_setup_id)->where('deleted', 0)->get();
                        if (count($setupArr) > 0) {
                            foreach ($setupArr as $set) {

                                $subjects = DB::table('guidance_test_category')
                                    ->where('guidance_test_category.category_deleted', 0)
                                    ->where('guidance_test_category.passing_rate_setup_id', $set->id)
                                    // ->where('required', 1)
                                    ->count();

                                $crsDataArr = explode(',', $set->courses);
                                foreach ($crsDataArr as $courseId) {
                                    $data = DB::table('college_courses')->where('id', $courseId)->first();
                                    $student->recommendedcourse[] = $data;
                                }
                            }
                        }
                    }

                } else if (!$student->hasRequiredSubj && floatval($avg) >= floatval($mySetup->average)) {
                    $student->passedOverall = true;

                    if ($student->acadprog_id == 5) {
                        $fitted_course = DB::table('sh_strand')->where('id', $student->strand_id)->first();
                        if ($fitted_course) {
                            $student->fitted_course_id = $fitted_course->id;
                            $student->fitted_courseAbrv = $fitted_course->strandcode;
                            $student->fitted_courseDesc = $fitted_course->strandname;
                        }

                        $student->recommendedcourse[] = DB::table('sh_strand')->where('id', $student->strand_id)->first();

                    } else if ($student->acadprog_id == 6 || $student->acadprog_id == 8) {
                        $fitted_course = DB::table('college_courses')->where('id', $student->course_id)->first();

                        if ($fitted_course) {
                            $student->fitted_course_id = $fitted_course->id;
                            $student->fitted_courseAbrv = $fitted_course->courseabrv;
                            $student->fitted_courseDesc = $fitted_course->courseDesc;
                        }

                        $setupArr = DB::table('guidance_passing_rate')->where('id', $student->exam_setup_id)->where('deleted', 0)->get();
                        if (count($setupArr) > 0) {
                            foreach ($setupArr as $set) {

                                $subjects = DB::table('guidance_test_category')
                                    ->where('guidance_test_category.category_deleted', 0)
                                    ->where('guidance_test_category.passing_rate_setup_id', $set->id)
                                    // ->where('required', 1)
                                    ->count();

                                $crsDataArr = explode(',', $set->courses);
                                foreach ($crsDataArr as $courseId) {
                                    $data = DB::table('college_courses')->where('id', $courseId)->first();
                                    $student->recommendedcourse[] = $data;
                                }
                            }
                        }
                    }

                } else {
                    $student->passedOverall = false;
                    if ($student->fitted_course_id) {
                        if ($student->acadprog_id == 5) {
                            $mycourse = DB::table('sh_strand')->where('id', $student->fitted_course_id)->first();
                            if ($mycourse) {
                                $student->fitted_course_id = $mycourse->id;
                                $student->fitted_courseAbrv = $mycourse->strandcode;
                                $student->fitted_courseDesc = $mycourse->strandname;
                            }
                        } else if ($student->acadprog_id == 6 || $student->acadprog_id == 8) {
                            $mycourse = DB::table('college_courses')->where('id', $student->fitted_course_id)->first();
                            if ($mycourse) {
                                $student->fitted_course_id = $mycourse->id;
                                $student->fitted_courseAbrv = $mycourse->courseabrv;
                                $student->fitted_courseDesc = $mycourse->courseDesc;
                            }

                            $setupArr = DB::table('guidance_passing_rate')->where('id', '!=', $student->exam_setup_id)->where('deleted', 0)->get();
                            if (count($setupArr) > 0) {
                                foreach ($setupArr as $set) {

                                    $subjects = DB::table('guidance_test_category')
                                        ->where('guidance_test_category.category_deleted', 0)
                                        ->where('guidance_test_category.passing_rate_setup_id', $set->id)
                                        ->where('required', 1)
                                        ->count();

                                    if ($subjects == 0) {
                                        $crsDataArr = explode(',', $set->courses);
                                        foreach ($crsDataArr as $courseId) {
                                            $data = DB::table('college_courses')->where('id', $courseId)->first();
                                            $student->recommendedcourse[] = $data;
                                        }
                                    }


                                }
                            }
                        }
                    } else {
                        $student->fitted_course_id = '';
                        $student->fitted_courseAbrv = '';
                        $student->fitted_courseDesc = '';
                    }
                }

                $student->alternateCourse = [];
                if (count($alternateCourseArr) > 0 && ($student->acadprog_id == 6 || $student->acadprog_id == 8)) {

                    foreach ($alternateCourseArr as $item) {
                        $data = DB::table('college_courses')->where('id', $item)->first();
                        $student->alternateCourse[] = $data;
                    }
                } else if ((count($alternateCourseArr) > 0 && $student->acadprog_id == 5) || (count($alternateCourseArr) == 0 && $student->acadprog_id == 5)) {
                    $data = DB::table('sh_strand')->where('deleted', 0)->get();
                    foreach ($data as $item) {
                        $student->alternateCourse[] = $item;
                    }
                }
            }

            if ($student->hasTest) {
                $student_results[] = $student;
            }



        }

        // dd($student_results);

        return $student_results;
    }


    public function admission_getall_result(Request $request)
    {
        $students = $this->processStudentTestResults(0, $request);

        return response()->json(['result' => $students]);
    }

    public function submit_test(Request $request)
    {
        // Validate the request data
        $request->validate([
            'studid' => 'required|integer',
            'examid' => 'required|integer',
            'score' => 'required|numeric',
        ]);

        $score = 0;

        $history = DB::table('admission_answer_history')
            ->where('admission_answer_history.studid', $request->studid)
            ->where('admission_answer_history.deleted', 0)
            ->where('guidance_test_questions.examid', $request->examid)
            ->join('guidance_test_questions', 'admission_answer_history.questionid', '=', 'guidance_test_questions.id')
            ->select(
                'admission_answer_history.*',
            )
            ->get();

        if (count($history) > 0) {
            foreach ($history as $item) {
                if ($item->status == 'correct') {
                    $score += $item->points;
                }
            }
        }

        // Perform the update
        try {
            DB::table('admission_test_result')
                ->where('deleted', 0)
                ->where([
                    ['studid', '=', $request->studid],
                    ['examid', '=', $request->examid],
                ])
                ->update([
                    'score' => $score,
                    'finishtime' => now('Asia/Manila'),
                    'updated_at' => now('Asia/Manila'),
                ]);

            return response()->json(['status' => 'success', 'message' => 'Submitted Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Submission failed! Please try again.'], 500);
        }
    }


    public function checkifdonetest(Request $request)
    {

        $studinfo = DB::table('admission_student_information')
            ->where('poolingnumber', $request->poolingnumber)
            ->where('deleted', 0)
            ->first();

        $subjects = DB::table('guidance_test_category')
            ->where('guidance_entrance_exam.id', $request->id)
            ->where('category_deleted', 0)
            ->join('guidance_entrance_exam', 'guidance_test_category.id', '=', 'guidance_entrance_exam.categoryid')
            ->select(
                'guidance_test_category.*'
            )
            ->first();

        $isdonetest = DB::table('admission_test_result')
            ->where('studid', $studinfo->id)
            ->where('examid', $request->id)
            ->where('deleted', 0)
            ->first();

        $totalMinutes = 0;
        $Hrs = $subjects->category_timelimit_hrs ?? 0;
        $Min = $subjects->category_timelimit_min ?? 0;
        $totalMinutes = ($Hrs * 60) + $Min;

        if ($isdonetest) {
            // Get the start time and current time
            $startTime = Carbon::parse($isdonetest->starttime, 'Asia/Manila');
            $currentTime = Carbon::now('Asia/Manila');

            // Calculate the difference in minutes
            $elapsedMinutes = $startTime->diffInMinutes($currentTime);

            // Check if time is over or still remaining
            if ($elapsedMinutes > $totalMinutes) {
                $timeStatus = 'Time is over';
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have already taken this test!',
                    'elapsedMinutes' => $elapsedMinutes,
                    'totalMinutes' => $totalMinutes,
                ]);

            } elseif ($totalMinutes - $elapsedMinutes >= 1) {
                $timeStatus = 'You have ' . ($totalMinutes - $elapsedMinutes) . ' minutes left';
                return response()->json([
                    'status' => 'success',
                    'message' => $timeStatus,
                    'remainingTime' => $totalMinutes - $elapsedMinutes,
                    'elapsedMinutes' => $elapsedMinutes,
                    'totalMinutes' => $totalMinutes,
                ]);
            } else {
                $timeStatus = 'Less than 1 minute left';
                return response()->json([
                    'status' => 'success',
                    'message' => $timeStatus,
                    'remainingTime' => 1,
                    'elapsedMinutes' => $elapsedMinutes,
                    'totalMinutes' => $totalMinutes,
                ]);
            }
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Redirecting!',
            ]);
        }

    }
    public function gototest(Request $request)
    {
        $directions = [];
        $questions = [];
        $result = DB::table('admission_student_information')
            ->where('poolingnumber', $request->poolingnumber)
            ->where('admission_student_information.deleted', 0)
            // ->join('college_courses', 'admission_student_information.course_id', '=', 'college_courses.id')
            ->join('gradelevel', 'admission_student_information.gradelevel_id', '=', 'gradelevel.id')
            ->select(
                'admission_student_information.*',
                // 'college_courses.courseDesc',
                // 'college_courses.courseabrv',
                'gradelevel.levelname',
            )
            ->first();


        if ($result->acadprog_id == 6) {
            $result->courseDesc = DB::table('college_courses')
                ->where('id', $result->course_id)
                ->value('courseDesc');
        } elseif ($result->acadprog_id == 5) {
            $result->courseDesc = DB::table('sh_strand')
                ->where('id', $result->strand_id)
                ->value('strandname');
        }

        $subjects = DB::table('guidance_test_category')
            ->where('category_deleted', 0)
            ->where('guidance_entrance_exam.deleted', 0)
            ->where('guidance_entrance_exam.id', $request->id)
            ->where('passing_rate_setup_id', $result->exam_setup_id)
            ->join('guidance_entrance_exam', 'guidance_test_category.id', '=', 'guidance_entrance_exam.categoryid')
            ->select(
                'guidance_test_category.*',
                'guidance_entrance_exam.id AS examtitleId',
                'guidance_entrance_exam.examtitle AS examTitle'
            )
            ->first();

        if ($subjects) {
            $directions = DB::table('guidance_test_direction')
                ->where('examid', $subjects->examtitleId)
                ->where('deleted', 0)
                ->select('textdirection')
                ->get();

            $questions = DB::table('guidance_test_questions')
                ->where('examid', $subjects->examtitleId)
                ->where('deleted', 0)
                ->select(
                    'guidance_test_questions.*',
                    DB::raw('TO_BASE64(guidance_test_questions.testanswer) AS encoded_answer')
                )
                ->orderBy('id')
                ->get()
                ->chunk(10);

            if (count($questions) > 0) {
                foreach ($questions as $quest) {
                    foreach ($quest as $item) {
                        $history = DB::table('admission_answer_history')
                            ->where('questionid', $item->id)
                            ->where('studid', $result->id)
                            ->where('deleted', 0)
                            ->first();

                        if ($history) {
                            $item->history = $history->answer;
                        }
                    }
                }
            }
        }

        // dd($questions);

        $isdonetest = DB::table('admission_test_result')
            ->where('studid', $result->id)
            ->where('examid', $request->id)
            ->where('deleted', 0)
            ->first();

        $totalSeconds = 0;
        $Hrs = $subjects->category_timelimit_hrs ?? 0;
        $Min = $subjects->category_timelimit_min ?? 0;
        $totalSeconds = ($Hrs * 3600) + ($Min * 60);
        $remainingTime = 0;
        $timeStatus = '';

        if ($isdonetest) {

            if ($isdonetest->finishtime) {
                return redirect()->back()->with(['status' => 'error', 'message' => 'You have already taken this test!']);
            }
            // Get the start time and current time
            $startTime = Carbon::parse($isdonetest->starttime, 'Asia/Manila');
            $currentTime = Carbon::now('Asia/Manila');

            // Calculate the difference in seconds
            $elapsedSeconds = $startTime->diffInSeconds($currentTime);

            // Check if time is over or still remaining
            if ($elapsedSeconds > $totalSeconds) {
                $timeStatus = 'Time is over';
                return redirect()->back()->with(['status' => 'error', 'message' => 'You have already taken this test!']);
            } else {
                $remainingTime = $totalSeconds - $elapsedSeconds;
                $timeStatus = 'You have ' . floor($remainingTime / 60) . ' minutes and ' . ($remainingTime % 60) . ' seconds left';
            }

            return view(
                'admission.pages.testpaper',
                [
                    'status' => 'success',
                    'message' => $timeStatus,
                    'studinfo' => $result,
                    'detail' => $subjects,
                    'directions' => $directions,
                    'questions' => $questions,
                    'count_question' => count($questions),
                    'poolingnumber' => $request->poolingnumber,
                    'totalMinutes' => $remainingTime
                ]
            );
        } else {
            DB::table('admission_test_result')
                ->insertGetId([
                    'studid' => $result->id,
                    'examid' => $request->id,
                    'starttime' => now('Asia/Manila'),
                    'created_at' => now('Asia/Manila'),
                    'updated_at' => now('Asia/Manila'),
                ]);

            return view(
                'admission.pages.testpaper',
                [
                    'status' => 'success',
                    'message' => $timeStatus,
                    'studinfo' => $result,
                    'detail' => $subjects,
                    'directions' => $directions,
                    'questions' => $questions,
                    'count_question' => count($questions),
                    'poolingnumber' => $request->poolingnumber,
                    'totalMinutes' => $totalSeconds
                ]
            );
        }
    }


    public function getStudentInformationByStatus($status, $sy, $level, $course)
    {
        return DB::table('admission_student_information')
            ->where('admission_student_information.deleted', 0)
            ->where('admission_student_information.status', $status)
            ->when(isset($sy) && $sy > 0, function ($query) use ($sy) {
                return $query->where('sy', $sy);
            })
            ->when(isset($level) && $level > 0, function ($query) use ($level) {
                return $query->where('gradelevel_id', $level);
            })
            ->when(isset($course) && $course > 0, function ($query) use ($course) {
                return $query->where('admission_student_information.course_id', $course);
            })
            ->leftJoin('college_courses as fitted_courses', 'admission_student_information.fitted_course_id', '=', 'fitted_courses.id')
            ->leftJoin('college_courses', 'admission_student_information.course_id', '=', 'college_courses.id')
            ->join('gradelevel', 'admission_student_information.gradelevel_id', '=', 'gradelevel.id')
            ->join('guidance_examdate', 'admission_student_information.examdate_id', '=', 'guidance_examdate.id')
            ->leftJoin('college_courses as final_course', 'admission_student_information.final_assign_course', '=', 'final_course.id')
            ->leftJoin('sh_strand', 'admission_student_information.strand_id', '=', 'sh_strand.id')
            ->select(
                'sh_strand.strandname',
                'sh_strand.strandcode',
                'final_course.courseabrv AS final_courseabrv',
                'final_course.courseDesc AS final_courseDesc',
                'admission_student_information.*',
                'college_courses.courseDesc',
                'college_courses.courseabrv',
                'fitted_courses.courseabrv AS fitted_course',
                'gradelevel.levelname',
                DB::raw('CONCAT(admission_student_information.lname, " ", admission_student_information.fname) AS studname'),
                DB::raw('DATE_FORMAT(admission_student_information.created_at, "%M %e, %Y") as formatted_created_at'),
                // DB::raw('DATE_FORMAT(admission_student_information.examdate, "%M %e, %Y") as formatted_examdate'),
                DB::raw('DATE_FORMAT(guidance_examdate.examinationdate, "%M %e, %Y %h:%i %p") as formatted_examdate'),
            )
            ->get();
    }

    public function get_applicants(Request $request)
    {
        $jsonPreregistered = $this->getStudentInformationByStatus(0, $request->sy, $request->level, $request->course);
        $jsonVerified = $this->getStudentInformationByStatus(1, $request->sy, $request->level, $request->course);
        $jsonAccepted = $this->getStudentInformationByStatus(2, $request->sy, $request->level, $request->course);

        return response()->json([
            'jsonPreregistered' => $jsonPreregistered,
            'jsonVerified' => $jsonVerified,
            'jsonAccepted' => $jsonAccepted
        ]);
    }

    public function edit_applicant(Request $request)
    {
        $student = DB::table('admission_student_information')->where('id', $request->id)->first();

        return view('admission.pages.editapplicant', ['student' => $student]);
    }

    public function store_test_direction(Request $request)
    {
        $directionId = DB::table('guidance_test_direction')
            ->insertGetId([
                'examid' => $request->examid,
                'textdirection' => $request->textdirection,
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

        if ($directionId) {
            return response()->json(['status' => 'success', 'message' => 'Direction Added!', 'directionid' => $directionId], 200);
        }
    }
    public function store_test_question(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'examid' => 'required|integer', // Example validation rules, adjust as needed
            'points' => 'required|numeric',
            'testtype' => 'required|string',
            'testquestion' => 'required|string',
            'options' => 'required|string',
            'answer' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Accept image file with maximum size of 2 MB
        ]);

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = $imageFile->getClientOriginalName();
            $destinationPath = public_path('images/test_questions');

            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $imageFile->move($destinationPath, $imageName);

        } else {
            $imageName = null;
        }

        $examtitle = DB::table('guidance_entrance_exam')->where('categoryid', $validatedData['examid'])->first();

        // Proceed with storing the question if validation passes
        $resultId = DB::table('guidance_test_questions')
            ->insertGetId([
                'examid' => $examtitle->id,
                'points' => $validatedData['points'],
                'testtype' => $validatedData['testtype'],
                'testquestion' => $validatedData['testquestion'],
                'testoptions' => $validatedData['options'],
                'testanswer' => $validatedData['answer'],
                'image' => 'test_questions/' . $imageName,
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);


        if ($resultId) {

            $allquestion = $this->getallquestion($examtitle->id);

            return response()->json(['status' => 'success', 'message' => 'Question Added successfully', 'questions' => $allquestion], 200);

        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to add question'], 500);
        }
    }

    public function update_test_question(Request $request)
    {
        DB::table('guidance_test_questions')
            ->where('id', $request->serverid)
            ->update([
                // 'examid' => $request->examid,
                'testtype' => $request->testtype,
                'testquestion' => $request->testquestion,
                'testoptions' => $request->testoptions,
                'testanswer' => $request->testanswer,
                // 'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

        return response()->json(['status' => 'success', 'message' => 'Successfully Saved!'], 200);
    }
    public function delete_test_question(Request $request)
    {
        DB::table('guidance_test_questions')
            ->where('id', $request->id)
            ->update([
                'deleted' => 1
            ]);

        return response()->json(['status' => 'success', 'message' => 'Deleted Question!'], 200);

    }

    public function store_entrance_exam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoryid' => 'required',
            'examtitle' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $examid = DB::table('guidance_entrance_exam')
            ->insertGetId([
                'categoryid' => $request->categoryid,
                'examtitle' => $request->examtitle,
                'checkingoption' => $request->checkingoption,
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

        if ($examid) {
            $result = DB::table('guidance_entrance_exam')
                ->where('guidance_entrance_exam.id', $examid)
                ->join('guidance_test_category', 'guidance_entrance_exam.categoryid', '=', 'guidance_test_category.id')
                ->select(
                    'guidance_entrance_exam.*',
                    'guidance_test_category.category_name',
                    // DB::raw('DATE_FORMAT(guidance_entrance_exam.created_at, "%Y-%m-%d %H:%i:%s") as formatted_created_at')
                    DB::raw('DATE_FORMAT(guidance_entrance_exam.created_at, "%M %e, %Y") as formatted_created_at')
                )
                ->first();
            return response()->json(
                [
                    'status' => 'success',
                    'result' => $result,
                    'message' => 'Created Successfully!'
                ],

                200
            );
        }
    }

    // public function compute_average($id)
    // {
    //     $total = 0;
    //     $data = DB::table('guidance_test_category')
    //         ->where('passing_rate_setup_id', $id)
    //         ->where('category_deleted', 0)
    //         ->get();

    //     foreach ($data as $key => $value) {
    //         $total = $total + $value->category_percent;
    //     }

    //     $average = $total / count($data);

    //     // dd($data, $average);

    //     DB::table('guidance_passing_rate')
    //         ->where('id', $id)
    //         ->update([
    //             'average' => $average
    //         ]);

    // }

    public function compute_average($id)
    {
        $total = 0;
        $data = DB::table('guidance_test_category')
            ->where('passing_rate_setup_id', $id)
            ->where('category_deleted', 0)
            ->get();

        $count = count($data);

        if ($count > 0) {
            foreach ($data as $key => $value) {
                $total += $value->category_percent;
            }

            $average = $total / $count;
        } else {
            $average = 0; // or handle the case differently if needed
        }

        DB::table('guidance_passing_rate')
            ->where('id', $id)
            ->update([
                'average' => $average
            ]);
    }


    public function update_instruction(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'instruction' => 'required|string',
            'id' => 'required|integer', // Assuming 'id' is the ID of the record to update
        ]);

        // Update the record in the database
        DB::table('guidance_test_direction')
            ->where('id', $validatedData['id'])
            ->update(['textdirection' => $validatedData['instruction']]);

        // Optionally, you can return a response indicating success
        return response()->json(['status' => 'success', 'message' => 'Instruction updated successfully'], 200);
    }

    public function getallquestion($id)
    {
        // Fetch questions from the database
        $questions = DB::table('guidance_test_questions')
            ->where('examid', $id)
            ->where('deleted', 0)
            ->get();

        // Process each question
        foreach ($questions as $key => $question) {
            // Split options string into an array
            $options = explode('*^*', $question->testoptions);

            // Add index to the question
            $question->index = $key + 1;

            // Initialize testoptions as an empty array
            $question->testoptions = [];

            // Generate array of letters A-Z
            $optionLetters = range('A', 'Z');

            // Iterate over options and assign letters
            foreach ($options as $index => $option) {
                $question->testoptions[] = [
                    'option' => $option,
                    'letter' => $optionLetters[$index] ?? '' // Handle case where options exceed letters
                ];
            }
        }

        // Return the processed questions as JSON
        return $questions;
    }

    public function update_question(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'question' => 'required|string',
            'options' => 'required|string',
            'points' => 'required|integer',
            'answer' => 'required|string',
            'id' => 'required|integer', // Assuming 'id' is the ID of the record to update
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = $imageFile->getClientOriginalName();
            $destinationPath = public_path('images/test_questions');

            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $imageFile->move($destinationPath, $imageName);

            $existingImage = DB::table('guidance_test_questions')
                ->where('id', $validatedData['id'])
                ->value('image');

            if ($existingImage && File::exists(public_path('images/' . $existingImage))) {
                File::delete(public_path('images/' . $existingImage));
            }

        } else {
            $imageName = null;
        }


        // Update the record in the database
        DB::table('guidance_test_questions')
            ->where('id', $validatedData['id'])
            ->update([
                'testquestion' => $validatedData['question'],
                'testanswer' => $validatedData['answer'],
                'testoptions' => $validatedData['options'],
                'points' => $validatedData['points'],
                'image' => 'test_questions/' . $imageName,
            ]);


        $examid = DB::table('guidance_test_questions')->where('deleted', 0)->where('id', $validatedData['id'])->value('examid');

        $allquestion = $this->getallquestion($examid);

        // Optionally, you can return a response indicating success
        return response()->json(['status' => 'success', 'message' => 'Question updated successfully', 'questions' => $allquestion], 200);
    }


    public function edit_question(Request $request)
    {
        $data = DB::table('guidance_test_questions')->where('id', $request->id)->first();
        $options = explode('*^*', $data->testoptions);
        $data->testoptions = [];
        $data->image = $data->image != 'test_questions/' ? asset('images/' . $data->image) : $data->image;

        $optionLetters = range('A', 'Z'); // Generate array of letters A-Z

        foreach ($options as $index => $option) {
            $data->testoptions[] = [
                'option' => $option,
                'letter' => $optionLetters[$index]
            ];
        }


        return response()->json($data);
    }

    public function delete_question(Request $request)
    {
        $examid = DB::table('guidance_test_questions')->where('id', $request->id)->value('examid');

        DB::table('guidance_test_questions')
            ->where('id', $request->id)
            ->update([
                'deleted' => 1,
            ]);


        $allquestion = $this->getallquestion($examid);

        return response()->json(['status' => 'success', 'message' => 'Question Deleted successfully', 'questions' => $allquestion], 200);
    }

    public function get_category_questions(Request $request)
    {
        $examtitle = DB::table('guidance_entrance_exam')->where('categoryid', $request->id)->first();

        if ($examtitle) {
            $direction = DB::table('guidance_test_direction')->where('deleted', 0)->where('examid', $examtitle->id)->first();
            $questions = DB::table('guidance_test_questions')->where('deleted', 0)->where('examid', $examtitle->id)->get();

            foreach ($questions as $key => $question) {
                $options = explode('*^*', $question->testoptions);
                $question->index = $key + 1;
                $question->testoptions = [];

                $optionLetters = range('A', 'Z'); // Generate array of letters A-Z

                foreach ($options as $index => $option) {
                    $question->testoptions[] = [
                        'option' => $option,
                        'letter' => $optionLetters[$index]
                    ];
                }
            }

            return response()->json([
                'status' => 'success',
                'title' => $examtitle,
                'direction' => $direction,
                'questions' => $questions
            ]);
        } else {
            return response()->json([
                'title' => '',
                'status' => 'warning',
                'message' => 'No Questions Found!',
            ]);
        }
    }

    public function store_titledirection(Request $request)
    {
        $insertData = [
            'categoryid' => $request->input('id'),
            'examtitle' => $request->input('examtitle'),
            'checkingoption' => $request->input('checkingoption'),
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        $isDuplicate = DB::table('guidance_entrance_exam')
            ->where(DB::raw('LOWER(examtitle)'), strtolower($request->input('examtitle')))
            ->exists();

        if ($isDuplicate) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Title Already Exists!',
            ]);
        }

        $testtitle = DB::table('guidance_entrance_exam')->insertGetId($insertData);

        if ($testtitle) {
            DB::table('guidance_test_direction')->insert([
                'examid' => $testtitle,
                'textdirection' => $request->input('direction')
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Title Save Successfully!',
        ]);

    }


    public function store_category(Request $request)
    {
        $insertData = [
            'passing_rate_setup_id' => $request->input('exam_setup_id'),
            'required' => $request->input('required'),
            'category_name' => $request->input('category_name'),
            'category_percent' => $request->input('category_percent'),
            'category_timelimit' => $request->input('category_timelimit'),
            'category_timelimit_hrs' => $request->input('category_timelimit_hrs'),
            'category_timelimit_min' => $request->input('category_timelimit_min'),
            'category_totalitems' => $request->input('category_totalitems'),
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        $isDuplicate = DB::table('guidance_test_category')
            ->where('category_deleted', 0)
            ->where('passing_rate_setup_id', $request->input('exam_setup_id'))
            ->where(DB::raw('LOWER(category_name)'), strtolower($request->input('category_name')))
            ->exists();

        if ($isDuplicate) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Name Already Exists!',
            ]);
        }

        DB::table('guidance_test_category')->insert($insertData);

        $this->compute_average($request->input('exam_setup_id'));

        return response()->json(['status' => 'success', 'message' => 'Created Successfully!'], 200);
    }

    public function delete_category(Request $request)
    {
        $data = DB::table('guidance_test_category')
            ->where('id', $request->id)->first();

        $setup_id = $data->passing_rate_setup_id;

        DB::table('guidance_test_category')
            ->where('id', $request->id)
            ->update(['category_deleted' => 1]);

        $this->compute_average($setup_id);

        return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);

    }
    public function allCategories(Request $request)
    {

        $average = DB::table('guidance_passing_rate')
            ->where('id', $request->exam_setup_id)
            ->first();

        $data = DB::table('guidance_test_category')
            ->where('category_deleted', 0)
            ->where('passing_rate_setup_id', $request->exam_setup_id)
            ->get();

        foreach ($data as $key => $value) {
            $value->index = $key + 1;
        }

        return response()->json([
            'data' => $data,
            'average' => $average->average
        ]);

    }

    public function get_category(Request $request)
    {
        $result = DB::table('guidance_test_category')
            ->where('category_deleted', 0)
            ->where('id', $request->id)
            ->first();

        if ($result) {
            return response()->json([
                'status' => 'success',
                'result' => $result
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'result' => null
            ]);
        }

    }
    public function update_category(Request $request)
    {
        $nameExist = DB::table('guidance_test_category')
            ->where('category_deleted', 0)
            ->where('category_name', $request->category_name)
            ->where('id', '!=', $request->id)
            ->where('passing_rate_setup_id', $request->exam_setup_id)
            ->first();

        if ($nameExist) {
            return response()->json(['status' => 'error', 'message' => 'Name already exist!']);
        }

        DB::table('guidance_test_category')
            ->where('id', $request->id)
            ->update([
                'required' => $request->required,
                'category_name' => $request->category_name,
                'category_percent' => $request->category_percent,
                'category_totalitems' => $request->category_totalitems,
                'category_timelimit_hrs' => $request->category_timelimit_hrs,
                'category_timelimit_min' => $request->category_timelimit_min
            ]);

        $this->compute_average($request->exam_setup_id);

        return response()->json(['status' => 'success', 'message' => 'Updated Successfully!']);

    }

    public function edit_passingrate(Request $request)
    {
        // Fetch passing rate details
        $details = DB::table('guidance_passing_rate')->where('id', $request->id)->first();

        if (!$details) {
            // Handle case where details are not found (e.g., show an error message or redirect)
            return redirect()->route('error')->with('error', 'Passing rate details not found.');
        }

        // Fetch criterias
        $criterias = DB::table('guidance_test_category')
            ->where('passing_rate_setup_id', $details->id)
            ->where('category_deleted', 0)
            ->get();

        foreach ($criterias as $key => $value) {
            $value->index = $key + 1;
        }

        // Fetch courses percentages with course details
        // $courses_percentage = DB::table('guidance_course_percentage')
        //     ->where('passing_rate_setup_id', $details->id)
        //     ->where('guidance_course_percentage.deleted', 0)
        //     ->join('college_courses', 'guidance_course_percentage.courseid', '=', 'college_courses.id')
        //     ->select(
        //         'guidance_course_percentage.*',
        //         'college_courses.courseabrv',
        //         'college_courses.courseDesc',
        //         'college_courses.id AS courseId'
        //     )
        //     ->get();

        // Attach criterias and courses percentages to details
        $details->criterias = $criterias;
        // $details->courses_percentage = $courses_percentage;

        // For debugging purposes, you can use dd($details) here to check the data before passing it to the view
        // dd($details);

        // Pass data to the view
        return view('guidanceV2.pages.admission.editpassingrate', ['details' => $details]);
    }

    public function activate_passingrate(Request $request)
    {

        // Update the specified record's isactive status
        DB::table('guidance_passing_rate')
            ->where('id', $request->id)
            ->update(['isactive' => $request->isactive]);

        // if ($request->isactive == 1) {
        //     DB::table('guidance_passing_rate')
        //         ->where('id', '!=', $request->id)
        //         ->update(['isactive' => 0]);
        // }

        // Optionally, return a response indicating success
        return response()->json(['status' => 'success', 'message' => 'Activation updated successfully']);
    }

    public function delete_passingrate(Request $request)
    {
        DB::table('guidance_passing_rate')
            ->where('id', $request->id)
            ->update([
                'deleted' => 1
            ]);

        return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    public function update_passingrate(Request $request)
    {
        $isExist = DB::table('guidance_passing_rate')
            ->where('deleted', 0)
            ->where('description', $request->description)
            ->where('id', '!=', $request->exam_setup_id)
            ->first();

        if ($isExist) {
            return response()->json(['status' => 'error', 'message' => 'Setup Name already exist!']);
        }

        DB::table('guidance_passing_rate')
            ->where('id', $request->exam_setup_id)
            ->update([
                'description' => $request->description,
                'gradelevel' => $request->gradelevel,
                'courses' => $request->courses,
                'alternate_course' => $request->alternateCourse,
                // 'graders_percent_passing' => $request->graders_percent_passing,
                // 'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
                'acadprog_id' => $request->acadprog_id,
                'strand' => $request->strand,
            ]);

        return response()->json(['status' => 'success', 'message' => 'Updated Successfully']);
    }

    public function store_passingrate(Request $request)
    {

        $isExist = DB::table('guidance_passing_rate')
            ->where('deleted', 0)
            ->where('description', $request->description)
            ->first();

        if ($isExist) {
            return response()->json(['status' => 'error', 'message' => 'Setup Name already exist!']);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'gradelevel' => 'required',
            // 'courses' => 'required',
        ]);

        // If validation fails, return JSON response with validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $passingRateId = DB::table('guidance_passing_rate')->insertGetId([
            'description' => $request->description,
            'gradelevel' => $request->gradelevel,
            'courses' => $request->courses,
            'average' => $request->average,
            // 'graders_percent_passing' => $request->graders_percent_passing,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
            'acadprog_id' => $request->acadprog_id,
            'strand' => $request->strand,

        ]);

        // Insert categories
        if ($passingRateId) {
            $categoriesJson = $request->input('categories');
            $categories = json_decode($categoriesJson, true);
            if (!empty($categories)) {
                $insertData = [];
                foreach ($categories as $category) {
                    $insertData[] = [
                        'passing_rate_setup_id' => $passingRateId,
                        'category_name' => $category['category'],
                        'category_percent' => $category['percentage'],
                        'category_timelimit' => $category['timelimit'],
                        'category_timelimit_hrs' => $category['timelimit_hrs'],
                        'category_timelimit_min' => $category['timelimit_min'],
                        'category_totalitems' => $category['total_items'],
                        'required' => $category['required'],
                        'created_at' => now('Asia/Manila'),
                        'updated_at' => now('Asia/Manila'),
                    ];
                }

                $isDuplicate = DB::table('guidance_test_category')
                    ->where('passing_rate_setup_id', $passingRateId)
                    ->where(DB::raw('LOWER(category_name)'), strtolower($category['category']))
                    ->exists();

                if (!$isDuplicate) {
                    DB::table('guidance_test_category')->insert($insertData);
                }


            }
        }
        // $coursepercentageJson = $request->input('coursepercentage');
        // $coursepercentage = json_decode($coursepercentageJson, true);
        // if (!empty($coursepercentage)) {
        //     $insertData = [];
        //     foreach ($coursepercentage as $item) {
        //         $insertData[] = [
        //             'passing_rate_setup_id' => $passingRateId,
        //             'courseid' => $item['courseid'],
        //             // 'category_name' => $item['categoryname'],
        //             // 'percentage' => $item['percentage'],
        //             'general_percentage' => $item['general_percentage'],
        //             'isprovision' => $item['isprovision'],
        //             'created_at' => now('Asia/Manila'),
        //             'updated_at' => now('Asia/Manila'),
        //         ];
        //     }
        //     DB::table('guidance_course_percentage')->insert($insertData);
        // }
        return response()->json(['status' => 'success', 'message' => 'Successfully Added!', 'resultId' => $passingRateId], 200);



    }

    public function getall_examdate()
    {
        $jsonExamDates = DB::table('guidance_examdate')
            ->where('guidance_examdate.deleted', 0)
            ->join('sy', 'guidance_examdate.schoolyear', '=', 'sy.id')
            ->join('guidance_passing_rate', 'guidance_examdate.examid', '=', 'guidance_passing_rate.id')
            ->select(
                'guidance_examdate.*',
                'guidance_passing_rate.description',
                DB::raw('DATE_FORMAT(guidance_examdate.examinationdate, "%M %e, %Y %h:%i %p") as formatted_examdate'),
                'sy.sydesc'
            )->get();

        foreach ($jsonExamDates as $value) {
            $students = DB::table('admission_student_information')->where('examdate_id', $value->id)->where('deleted', 0)->count();
            $value->available = $value->takers - $students;
            $value->reserved = $students;
        }

        return $jsonExamDates;


    }

    public function store_examdate(Request $request)
    {
        $returnId = DB::table('guidance_examdate')->insertGetId([
            'schoolyear' => $request->schoolyear,
            'examinationdate' => $request->examinationdate,
            'takers' => $request->takers,
            'venue' => $request->venue,
            'building' => $request->building,
            'room' => $request->room,
            'acadprog' => $request->acadprog,
            'examid' => $request->examid,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);

        if ($returnId) {
            return response()->json(['status' => 'success', 'message' => 'Successfully Added!', 'examdates' => $this->getall_examdate()], 200);
        }
    }

    public function update_examdate(Request $request)
    {
        DB::table('guidance_examdate')
            ->where('id', $request->id)
            ->update([
                'schoolyear' => $request->schoolyear,
                'examinationdate' => $request->examinationdate,
                'takers' => $request->takers,
                'venue' => $request->venue,
                'building' => $request->building,
                'room' => $request->room,
                'acadprog' => $request->acadprog,
                'examid' => $request->examid,
                'updated_at' => now('Asia/Manila'),
            ]);

        return response()->json(['status' => 'success', 'message' => 'Successfully Updated!', 'examdates' => $this->getall_examdate()], 200);

    }

    public function delete_examdate(Request $request)
    {
        DB::table('guidance_examdate')
            ->where('id', $request->id)
            ->update([
                'deleted' => 1,
            ]);

        return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!', 'examdates' => $this->getall_examdate()], 200);
    }

    public function edit_examdate(Request $request)
    {
        $jsonExamDates = DB::table('guidance_examdate')
            ->where('guidance_examdate.id', $request->id)
            ->join('sy', 'guidance_examdate.schoolyear', '=', 'sy.id')
            ->join('guidance_passing_rate', 'guidance_examdate.examid', '=', 'guidance_passing_rate.id')
            ->select(
                'guidance_examdate.*',
                'guidance_passing_rate.description',
                DB::raw('DATE_FORMAT(guidance_examdate.examinationdate, "%M %e, %Y %h:%i %p") as formatted_examdate'),
                'sy.sydesc'
            )->first();

        $students = DB::table('admission_student_information')->where('examdate_id', $jsonExamDates->id)->where('deleted', 0)->count();
        $jsonExamDates->available = $jsonExamDates->takers - $students;
        $jsonExamDates->reserved = $students;

        return response()->json($jsonExamDates);
    }

    public function filter_acadprog(Request $request)
    {

        $gradeLevels = DB::table('gradelevel')
            ->where('acadprogid', $request->id)
            ->where('deleted', 0)
            ->get();

        $data = [];

        foreach ($gradeLevels as $gradeLevel) {
            $data[] = [
                'id' => $gradeLevel->id, // Assuming 'id' is the primary key of your gradelevel table
                'text' => $gradeLevel->levelname, // Assuming 'name' is the field containing the display text
            ];
        }

        return response()->json($data);
    }
    public function filter_examsetup(Request $request)
    {

        $exams = DB::table('guidance_passing_rate')
            ->where('acadprog_id', $request->id)
            ->where('deleted', 0)
            ->when($request->filled('courseid'), function ($query) use ($request) {
                return $query->whereRaw('FIND_IN_SET(?, guidance_passing_rate.courses)', [$request->courseid]);
            })
            ->when($request->filled('levelid'), function ($query) use ($request) {
                return $query->whereRaw('FIND_IN_SET(?, guidance_passing_rate.gradelevel)', [$request->levelid]);
            })
            ->get();

        $data = [];

        foreach ($exams as $items) {
            $data[] = [
                'id' => $items->id, // Assuming 'id' is the primary key of your items table
                'text' => $items->description, // Assuming 'name' is the field containing the display text
            ];
        }

        return response()->json($data);
    }
    public function filter_examdates(Request $request)
    {

        $exams = DB::table('guidance_examdate')
            ->where('acadprog', $request->id)
            ->where('deleted', 0)
            ->whereIn('status', [0, null])
            // ->where('examinationdate', '>=', Carbon::now()->format('Y-m-d H:i:s'))
            ->when($request->filled('examid'), function ($query) use ($request) {
                return $query->where('guidance_examdate.examid', $request->examid);
            })
            ->select(
                'guidance_examdate.*',
                DB::raw('DATE_FORMAT(guidance_examdate.examinationdate, "%M %e, %Y %h:%i %p") as formatted_examdate'),
            )
            ->get();

        $data = [];

        if (count($exams) > 0) {
            foreach ($exams as $items) {
                $data[] = [
                    'id' => $items->id, // Assuming 'id' is the primary key of your items table
                    'text' => $items->formatted_examdate, // Assuming 'name' is the field containing the display text
                ];
            }
        }


        return response()->json($data);
    }

    public function find_examdate(Request $request)
    {
        $data = DB::table('guidance_examdate')
            ->where('id', $request->id)
            ->select(
                'guidance_examdate.*',
                DB::raw('DATE_FORMAT(guidance_examdate.examinationdate, "%M %e, %Y %h:%i %p") as formatted_examdate'),
            )
            ->first();


        $students = DB::table('admission_student_information')->where('examdate_id', $data->id)->where('deleted', 0)->count();
        $data->available = $data->takers - $students;
        $data->reserved = $students;

        return response()->json($data);
    }

    public function student_info_save(Request $request)
    {


        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'lname' => 'required|string',
            'suffix' => 'nullable|string',
            'dob' => 'required|date',
            // 'pob' => 'required|string',
            'gender' => 'required|in:male,female',
            'age' => 'required|integer',
            'acadprog_id' => 'required',
            // 'nationality_id' => 'required|exists:nationality,id',
            // 'examdate' => 'required',
            'contact_number' => 'required',
            'gradelevel_id' => 'required|integer',
            // 'course_id' => 'required|integer',
            'exam_setup_id' => 'required|integer',
            'examdate_id' => 'required|integer',
            // Add validation rules for other fields as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $student = DB::table('admission_student_information')
            ->where('fname', $request->fname)
            ->where('lname', $request->lname)
            ->where('deleted', 0)
            ->first();

        if ($student) {
            return response()->json(['status' => 'error', 'message' => 'Student already exists!. Contact the Guidance.']);
        }


        // Generate pooling number
        $poolingnumber = $this->codegenerator();

        // Check if the pooling number already exists in the database
        $existingRecord = DB::table('admission_student_information')->where('poolingnumber', $poolingnumber)->first();

        while ($existingRecord) {
            // If the pooling number already exists, generate a new one and check again
            $poolingnumber = $this->codegenerator();
            $existingRecord = DB::table('admission_student_information')->where('poolingnumber', $poolingnumber)->first();
        }

        $studentId = DB::table('admission_student_information')->insertGetId($request->except(['_token']) + [
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
            'poolingnumber' => $poolingnumber,
            // 'examdate_id' => $request->examdate_id,
            'sy' => DB::table('sy')->where('isactive', 1)->value('id'),
        ]);

        if ($studentId) {
            return response()->json(['status' => 'success', 'message' => 'Successfully Registered!', 'poolingnumber' => $poolingnumber], 200);
        }
    }

    public function student_info_update(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'lname' => 'required|string',
            'suffix' => 'nullable|string',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'age' => 'required|integer',
            'acadprog_id' => 'required',
            'contact_number' => 'required',
            'gradelevel_id' => 'required|integer',
            'exam_setup_id' => 'required|integer',
            'examdate_id' => 'required|integer',
            // Add validation rules for other fields as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the student exists
        $student = DB::table('admission_student_information')
            ->where('deleted', 0)
            ->where('id', $request->id)->first();

        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Student not found'], 404);
        }

        // Update student information using query builder
        DB::table('admission_student_information')
            ->where('id', $request->id)
            ->update($request->except(['_token', 'id']));

        return response()->json(['status' => 'success', 'message' => 'Successfully Updated!'], 200);
    }

    public function submit_pooling(Request $request)
    {
        $haspooling = DB::table('admission_student_information')->where('poolingnumber', $request->poolingnumber)->first();


        if ($haspooling) {
            if ($haspooling->status == 1) {
                return response()->json(['stud_info' => $haspooling, 'status' => 'success', 'message' => 'Success!']);
            } else if (!$haspooling->exam_setup_id) {
                return response()->json(['status' => 'error', 'message' => 'No Setup Yet!']);
            } else if ($haspooling->status == 2) {
                return response()->json(['status' => 'error', 'message' => 'You already finished taking the test!']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'You\'re not yet verified!']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Pooling does\'nt exist!']);
        }
    }

    function codegenerator()
    {
        $length = 6;
        return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
    }

    public function view_result(Request $request)
    {
        $student = $this->processStudentTestResults($request->id, null);

        return response()->json(['result' => $student]);
    }

    // public function view_result(Request $request)
    // {
    //     $student_results = [];

    //     $students = DB::table('admission_student_information')
    //         ->where('admission_student_information.id', $request->id)
    //         ->where('admission_student_information.deleted', 0)
    //         ->join('college_courses', 'admission_student_information.course_id', '=', 'college_courses.id')
    //         ->select(
    //             'admission_student_information.*',
    //             'college_courses.courseabrv',
    //             'college_courses.courseDesc',
    //             DB::raw('CONCAT(admission_student_information.lname, " ", admission_student_information.fname) AS studname'),
    //             DB::raw('DATE_FORMAT(admission_student_information.examdate, "%M %e, %Y") as formatted_examdate')
    //         )
    //         ->get();

    //     // $active_setup = DB::table('guidance_passing_rate')->where('isactive', 1)->first();

    //     foreach ($students as $student) {

    //         $test_results = DB::table('admission_test_result')
    //             ->where('studid', $student->id)
    //             // ->where('guidance_test_category.passing_rate_setup_id', $active_setup->id)
    //             // ->where('guidance_course_percentage.courseid', $value->course_id)
    //             // ->where('guidance_course_percentage.passing_rate_setup_id', $active_setup->id)
    //             ->join('guidance_entrance_exam', 'admission_test_result.examid', '=', 'guidance_entrance_exam.id')
    //             ->join('guidance_test_category', 'guidance_entrance_exam.categoryid', '=', 'guidance_test_category.id')
    //             // ->join('guidance_course_percentage', 'guidance_test_category.category_name', '=', 'guidance_course_percentage.category_name')
    //             ->select(
    //                 'admission_test_result.*',
    //                 'guidance_entrance_exam.categoryid',
    //                 'guidance_test_category.category_name',
    //                 'guidance_test_category.category_percent',
    //                 'guidance_test_category.passing_rate_setup_id'
    //             )
    //             ->get();

    //         if (count($test_results) > 0) {

    //             $weighted_average_score = 0;
    //             foreach ($test_results as $item) {
    //                 $course_percentage = DB::table('guidance_course_percentage')
    //                     ->where('courseid', $student->course_id)
    //                     ->where('passing_rate_setup_id', $item->passing_rate_setup_id)
    //                     ->select(
    //                         'general_percentage',
    //                         'isprovision'
    //                     )
    //                     ->first();

    //                 $general_percentage = 0;
    //                 $category_percent = 0;
    //                 $weighted_average_formula = 0;

    //                 if ($course_percentage) {
    //                     $general_percentage = $course_percentage->general_percentage;
    //                     $item->general_percentage = $course_percentage->general_percentage;
    //                     $item->isprovision = $course_percentage->isprovision;
    //                     // $student->overall = $course_percentage->general_percentage;
    //                 }
    //                 $totalquestions = DB::table('guidance_test_questions')
    //                     ->where('examid', $item->examid)
    //                     ->get();

    //                 $totalcount_ofques = $totalquestions->count();
    //                 $totalpoints_ofques = $totalquestions->sum('points');

    //                 // dd($totalcount_ofques, $totalpoints_ofques);

    //                 $passingscore = $totalpoints_ofques * ($general_percentage / 100);
    //                 $percent_equiv_toscore = ($item->score / $totalpoints_ofques);

    //                 $category_percent = ($item->category_percent / 100);
    //                 $weighted_average_formula = ($category_percent * $percent_equiv_toscore);

    //                 if ($item->score >= $passingscore) {
    //                     $item->passed = true;
    //                 } else {
    //                     $item->passed = false;
    //                 }

    //                 $item->general_percentage = $general_percentage;
    //                 $item->gwa_category = number_format(($percent_equiv_toscore * $category_percent) * 100, 2);
    //                 $item->totalquestion = $totalpoints_ofques;
    //                 $item->passingscore = $passingscore;
    //                 $item->percent_equiv_toscore = $percent_equiv_toscore;

    //                 $weighted_average_score += $weighted_average_formula;

    //                 $student->passing_rate_setup_id = $item->passing_rate_setup_id;
    //             }

    //             $student->test_result = $test_results;
    //             $student->general_weighted_avg = number_format($weighted_average_score * 100, 2);
    //             if ($weighted_average_score >= ($general_percentage / 100)) {
    //                 $student->passed = true;
    //             } else {
    //                 $student->passed = false;
    //             }

    //             // Recommended Courses
    //             $fitted_course = DB::table('guidance_course_percentage')
    //                 ->where('general_percentage', '<=', $student->general_weighted_avg)
    //                 ->where('passing_rate_setup_id', $student->passing_rate_setup_id)
    //                 ->join('college_courses', 'guidance_course_percentage.courseid', '=', 'college_courses.id')
    //                 ->get();

    //             if (count($fitted_course) > 0) {
    //                 $listcourse = [];
    //                 foreach ($fitted_course as $elem) {
    //                     $listcourse[] = $elem->courseDesc;
    //                 }
    //                 // dd($listcourse);
    //                 $student->fitted_course = $listcourse;
    //             }

    //         } else {
    //             $student->overall = 0;
    //         }

    //         if (count($test_results) > 0) {
    //             $student_results[] = $student;
    //         }

    //     }

    //     // dd($student_results);

    //     return response()->json(['result' => $student_results]);

    //     // return view('admission.pages.admissionresult', ['result' => $student_results]);

    // }

    public function retake_test(Request $request)
    {
        DB::table('admission_test_result')
            ->where('studid', $request->id)
            ->update(['deleted' => 1]);

        DB::table('admission_answer_history')
            ->where('studid', $request->id)
            ->update(['deleted' => 1]);

        DB::table('admission_student_information')
            ->where('id', $request->id)
            ->where('deleted', 0)
            ->update([
                'status' => 1,
                'fitted_course_id' => null,
                'final_assign_course' => null
            ]);

        return response()->json(['status' => 'success', 'message' => "Result Resetted Successfully!"]);
    }

    public function accept_student(Request $request)
    {
        $stud = DB::table('admission_student_information')
            ->where('id', $request->id)
            ->where('status', 2)
            ->first();

        if ($stud) {
            DB::table('admission_student_information')
                ->where('id', $request->id)
                ->update([
                    'status' => 1
                ]);

            return response()->json(['status' => "success", "message" => "Unassigned Successfully!"]);
        }

        $result = DB::table('admission_student_information')
            ->where('id', $request->id)
            ->update([
                'final_assign_course' => $request->courseid,
                'status' => 2
            ]);

        if (!$result) {
            return response()->json(['status' => "error", "message" => "Something went wrong!"]);
        }

        $stud = DB::table('admission_student_information')
            ->where('id', $request->id)
            ->where('status', 2)
            ->first();

        $assignCourse = '';

        if ($stud->acadprog_id == 6) {
            $data = DB::table('college_courses')->where('id', $stud->final_assign_course)->where('deleted', 0)->first();
            $assignCourse = $data->courseabrv;
        } else if ($stud->acadprog_id == 5) {
            $data = DB::table('sh_strand')->where('id', $stud->final_assign_course)->where('deleted', 0)->first();
            $assignCourse = $data->strandcode;
        } else if ($stud->acadprog_id <= 4 && $stud->acadprog_id >= 2) {
            $data = DB::table('gradelevel')->where('id', $stud->gradelevel_id)->where('deleted', 0)->first();
            $assignCourse = $data->levelname;
        } else if ($stud->acadprog_id == 7) {
            $assignCourse = 'TECH-VOC';
        }

        $abbrv = DB::table('schoolinfo')->first()->abbreviation;

        $message = "Congrats! " . ucfirst($stud->lname) . ", " . ucfirst(substr($stud->fname, 0, 1)) . ". " . ucfirst(substr($stud->mname, 0, 1)) . "." . " You are now eligible to Enroll for " . $assignCourse . ". Use this Pooling number " . $stud->poolingnumber . " for Preregistration. From " . $abbrv . ".";

        $phone = preg_replace('/^0/', '', $stud->contact_number);
        $phone = '+63' . str_replace('-', '', $phone);



        $inserted = DB::table('tapbunker')->insert([
            'smsstatus' => 1,
            'message' => $message,
            'receiver' => $phone,
            'createddatetime' => now('Asia/Manila')
        ]);

        if ($inserted) {
            return response()->json([
                'status' => "success",
                "message" => "Student Accepted Successfully!",
                'studmsg' => $message
            ]);
        } else {
            return response()->json([
                'status' => "error",
                "message" => "Something went wrong. Pls try again!",
            ]);
        }


    }

    public function decline_student(Request $request)
    {

        DB::table('admission_student_information')
            ->where('id', $request->id)
            ->update([
                'status' => 3,
                // 'deleted' => 1,
            ]);

        return response()->json(['status' => "success", "message" => "Student Declined Successfully!"]);
    }

    public function verify_student(Request $request)
    {
        DB::table('admission_student_information')
            ->where('id', $request->id)
            ->update([
                'status' => 1,
                // 'acadprog_id' => $request->acadprog_id,
                // 'exam_setup_id' => $request->exam_setup_id,
                // 'examdate' => $request->examdate,
                // 'examdate_id' => $request->examdate_id,
            ]);

        return response()->json(['status' => "success", "message" => "Student Verified Successfully!"]);
    }

    public function delete_applicant(Request $request)
    {
        DB::table('admission_student_information')
            ->where('id', $request->id)
            ->update(['deleted' => 1]);

        return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    public function store_criteria(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);
        $totalPercentage = 0;

        if ($request->id) {
            $existingPercentage = DB::table('guidance_criteria')
                ->where('deleted', 0)
                ->where('id', $request['id'])
                ->value('criteria_percentage');

            // Calculate total excluding the existing percentage
            $totalPercentage = DB::table('guidance_criteria')
                ->where('deleted', 0)
                ->where('id', '!=', $request['id'])
                ->sum('criteria_percentage') + $request['percentage'];


        } else {
            // Calculate total when inserting a new record
            $totalPercentage = DB::table('guidance_criteria')
                ->where('deleted', 0)
                ->sum('criteria_percentage') + $request['percentage'];
        }

        if ($totalPercentage > 100) {
            return response()->json(['status' => 'error', 'message' => 'Total percentage cannot exceed 100!']);
        }

        if ($totalPercentage <= 100) {
            if ($request->id) {
                DB::table('guidance_criteria')
                    ->where('id', $request['id'])
                    ->update([
                        'primary' => $request->primary,
                        'with_subcriteria' => $request->with_subcriteria,
                        'criteria_name' => $request['name'],
                        'criteria_percentage' => $request['percentage'],
                        'updated_at' => now('Asia/Manila'),
                    ]);

                $this->store_subcriteria(json_decode($request->subcriteria, true), $request->id);

                return response()->json(['status' => 'success', 'message' => 'Updated Successfully!']);
            } else {
                $returnId = DB::table('guidance_criteria')->insertGetId([
                    'criteria_name' => $request->name,
                    'primary' => $request->primary,
                    'with_subcriteria' => $request->with_subcriteria,
                    'criteria_percentage' => $request->percentage,
                    'created_at' => now('Asia/Manila'),
                    'updated_at' => now('Asia/Manila'),
                ]);

                $this->store_subcriteria(json_decode($request->subcriteria, true), $returnId);

                return response()->json(['status' => 'success', 'message' => 'Added Successfully!']);
            }
        }
    }

    public function store_subcriteria($data, $criteriaId)
    {
        $dataArr = [];

        DB::table('guidance_subcriteria')->where('criteria_id', $criteriaId)->delete();

        foreach ($data as $value) {
            $dataArr[] = [
                'criteria_id' => $criteriaId,
                'name' => $value['name'],
                'percentage' => $value['percentage'],
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ];

        }

        DB::table('guidance_subcriteria')->insert($dataArr);

    }

    public function show_subcriteria(Request $request)
    {
        return DB::table('guidance_subcriteria')->where('criteria_id', $request->id)->get();

    }


    public function all_criteria(Request $request)
    {
        $criteriaArr = DB::table('guidance_criteria')->where('deleted', 0)
            ->select(
                'guidance_criteria.*',
                DB::raw('DATE_FORMAT(guidance_criteria.created_at, "%M %e, %Y %h:%i %p") as created_at')
            )
            ->get()
            ->map(function ($criteria) use ($request) {
                $criteria->studcriteria = DB::table('guidance_student_criteria')
                    ->select('id', 'value')
                    ->where([
                        'studid' => $request->studid,
                        'criteria_id' => $criteria->id,
                        'subcriteria_id' => null
                    ])
                    ->first();

                $criteria->subcriteria = DB::table('guidance_subcriteria')->where('criteria_id', $criteria->id)->get()
                    ->map(function ($sub) use ($request) {
                        $sub->studsubcriteria = DB::table('guidance_student_criteria')
                            ->select('id', 'value')
                            ->where([
                                'studid' => $request->studid,
                                'criteria_id' => $sub->criteria_id,
                                'subcriteria_id' => $sub->id
                            ])
                            ->first();

                        return $sub;
                    });

                return $criteria;
            });

        return response()->json($criteriaArr);

    }

    public function delete_criteria(Request $request)
    {
        DB::table('guidance_criteria')->where('id', $request->id)->update(['deleted' => 1]);

        return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    public function getAnswerHistory(Request $request)
    {
        $answers = DB::table('admission_answer_history')
            ->where('admission_answer_history.deleted', 0)
            ->where('studid', $request->studid)
            ->when($request->examid, function ($query) use ($request) {
                $query->where('guidance_test_questions.examid', $request->examid);
            })
            ->join('guidance_test_questions', 'admission_answer_history.questionid', '=', 'guidance_test_questions.id')
            ->select(
                'guidance_test_questions.*',
                'admission_answer_history.*'
            )
            ->get();

        $score = $answers->where('status', 'correct')->count();

        return response()->json([
            'answers' => $answers,
            'total' => $answers->sum('points'),
            'score' => $score
        ]);
    }

    public function verify_pooling(Request $request)
    {
        $data = DB::table('admission_student_information')
            ->where('deleted', 0)
            ->where('poolingnumber', $request->code)->first();
        if (!$data) {
            return response()->json([
                'message' => 'Invalid Code',
                'status' => 'error',
            ]);
        } else {
            return response()->json([
                'message' => 'Success Code Matched!',
                'status' => 'success',
                'data' => $data
            ]);
        }
    }

    public function getAllSubject(Request $request)
    {
        return DB::table('guidance_test_category')->where('passing_rate_setup_id', $request->id)
            ->where('category_deleted', 0)
            ->join('guidance_entrance_exam', 'guidance_test_category.id', '=', 'guidance_entrance_exam.categoryid')
            ->select('guidance_entrance_exam.id', 'examtitle as text')
            ->get();
    }


    public function storeStudentCriteria(Request $request)
    {
        $criteriaArr = json_decode($request->criteria_array, true);
        $dataArr = [];

        foreach ($criteriaArr as $item) {

            if (isset($item['serverId'])) {
                DB::table('guidance_student_criteria')->where('id', $item['serverId'])->update([
                    'studid' => $request->studid,
                    'criteria_id' => $item['criteria_id'],
                    'subcriteria_id' => isset($item['sub_criteria_id']) ? $item['sub_criteria_id'] : null,
                    'value' => $item['value']
                ]);
            } else {
                $dataArr[] = [
                    'studid' => $request->studid,
                    'criteria_id' => $item['criteria_id'],
                    'subcriteria_id' => isset($item['sub_criteria_id']) ? $item['sub_criteria_id'] : null,
                    'value' => $item['value']
                ];
            }
        }

        DB::table('guidance_student_criteria')->insert($dataArr);

        // DB::table('admission_student_information')->where('id', $request->studid)->update(['final_assign_course' => $request->course_id]);

        return response()->json(['status' => 'success', 'message' => 'Saved Criteria Successfully!']);
    }


}