<?php

namespace App\Http\Controllers\TesdaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class ReportController extends Controller
{
    public function tesda_certifications_enrollment_get(Request $request)
    {
        $course = $request->get('course_id');
        $type = $request->get('course_type');
        $batch = $request->get('batch_id');
        $status = $request->get('status');

        $students = DB::table('studinfo')
            ->where('studinfo.deleted', 0)
            ->when($course, function ($query, $course) {
                return $query->where('studinfo.courseid', $course);
            })
            ->when($type, function ($query, $type) {
                return $query->where('tesda_initialcourse.course_type', $type);
            })
            ->when($status, function ($query, $status) {
                return $query->where('studinfo.studstatus', $status);
            })
            ->when($batch, function ($query, $batch) {
                return $query->where('tesda_enrolledstud.batchid', $batch);
            })
            ->leftJoin('tesda_enrolledstud', function ($join) {
                $join->on('studinfo.id', '=', 'tesda_enrolledstud.studid')
                    ->where('tesda_enrolledstud.deleted', 0);
            })
            ->leftJoin('studentstatus', 'tesda_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->leftJoin('tesda_courses', function ($join) {
                $join->on('tesda_enrolledstud.courseid', '=', 'tesda_courses.id')
                    ->where('tesda_courses.deleted', 0);
            })
            ->leftJoin('tesda_courses as tesda_initialcourse', function ($join) {
                $join->on('studinfo.courseid', '=', 'tesda_initialcourse.id')
                    ->where('tesda_initialcourse.deleted', 0);
            })
            ->leftJoin('tesda_batches', function ($join) {
                $join->on('tesda_enrolledstud.batchid', '=', 'tesda_batches.id')
                    ->where('tesda_batches.deleted', 0);
            })
            ->select(
                'studinfo.*',
                'tesda_enrolledstud.*',
                'tesda_enrolledstud.dateenrolled',
                'tesda_courses.course_name',
                'tesda_courses.course_code',
                'studentstatus.description as studentstatus',
                'studentstatus.id as studentstatusid',
                'studinfo.courseid as initialcourseid',
                'tesda_initialcourse.course_name as initialcoursename',
                'tesda_batches.batch_desc',
                'studinfo.id',
            )
            ->get();

        return response()->json($students);
    }

    public function tesda_cor()
    {
        return view('tesda.pages.report.certificate_of_registration');
    }
    public function tesda_tor()
    {
        return view('tesda.pages.report.transcript_of_records');
    }
    public function tesda_attendanceSummary()
    {
        return view('tesda.pages.report.attendance_summary');
    }
    public function tesda_enrollmentSummary()
    {
        return view('tesda.pages.report.enrollment_summary');
    }
    public function tesda_applicationForGraduation()
    {
        return view('tesda.pages.report.application_for_graduation');
    }
    public function tesda_certifications()
    {
        return view('tesda.pages.report.certifications');
    }

    public function tesdaPrintCertCompletion($studentId)
    {
        $schoolInfo = DB::table('schoolinfo')->select('schoolinfo.*')->first();

        $student = DB::table('studinfo')
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            ->join('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
            ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
            ->where('tesda_course_series.active', 1)
            ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
            ->join('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
            ->where('studinfo.id', $studentId)
            ->where('tesda_schedule_details.deleted', 0)
            ->select(
                'studinfo.id as student_id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.gender',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                // 'tesda_course_competency.competency_desc',
                // 'tesda_course_competency.hours',
                'tesda_course_series.description as series_desc',
                'tesda_course_series.id as series_id',
                'tesda_courses.id as course_id',
                'tesda_batches.batch_desc',
                'rooms.roomname',
                'tesda_schedule_details.stime',
                'tesda_schedule_details.etime',
                'tesda_schedule_details.date_from',
                'tesda_schedule_details.date_to'
            )

            ->groupBy('course_id')
            // ->groupBy('tesda_course_competency.id')

            ->first();

        // dd($studentData);


        $schedules = DB::table('tesda_course_competency')
            ->where('tesda_course_competency.deleted', 0)
            ->where('tesda_course_competency.course_series_id', $student->series_id)
            ->join('tesda_batch_schedule', function ($query) {
                $query->on('tesda_course_competency.id', '=', 'tesda_batch_schedule.batch_id')
                    ->where('tesda_batch_schedule.deleted', 0);
            })
            ->join('tesda_schedule_details', function ($query) {
                $query->on('tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id');
            })
            ->join('rooms', function ($query) {
                $query->on('tesda_schedule_details.roomID', '=', 'rooms.id');
            })
            ->select(
                'tesda_course_competency.competency_desc',
                'tesda_course_competency.hours',
                'tesda_schedule_details.date_from',
                'tesda_schedule_details.date_to',
                'tesda_schedule_details.stime',
                'tesda_schedule_details.etime',
                'rooms.roomname'
            )
            ->get()
            ->groupBy('competency_desc');


        // return response()->json(['success' => true, 'student' => $student, 'schedules' => $schedules]);

        $pdf = PDF::loadView('tesda.pages.report.tesdaPrintCompletionCert', compact('student', 'schoolInfo', 'schedules'))->setPaper('legal');

        return $pdf->stream('Certificate_of_Completion.pdf');
    }

    public function tesdaPrintCertCompletionAll(Request $request)
    {
        $studid = $request->get('id');
        $schoolInfo = DB::table('schoolinfo')->first();

        $students = DB::table('studinfo')
            ->where('studinfo.deleted', 0)
            ->where(function ($query) use ($studid) {
                if(isset($studid)){
                    $query->where('studinfo.id', $studid);
                }
            })
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            ->join('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
            // ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
            // ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
            // ->join('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
            // ->where('tesda_course_series.active', 1)
            // ->where('tesda_schedule_details.deleted', 0)
            ->select(
                'studinfo.id as student_id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.gender',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                // 'tesda_course_series.description as series_desc',
                // 'tesda_course_series.id as series_id',
                'tesda_courses.id as course_id',
                'tesda_batches.batch_desc',
                // 'rooms.roomname',
                // 'tesda_schedule_details.stime',
                // 'tesda_schedule_details.etime',
                // 'tesda_schedule_details.date_from',
                // 'tesda_schedule_details.date_to'
            )
            ->groupBy('studinfo.id') // Ensure it groups by student
            ->get();

        // dd($students);

        if ($students->isEmpty()) {
            return abort(404, 'No students found');
        }

        // $schedules = DB::table('tesda_course_competency')
        //     ->where('tesda_course_competency.deleted', 0)
        //     ->join('tesda_batch_schedule', 'tesda_course_competency.id', '=', 'tesda_batch_schedule.batch_id')
        //     ->where('tesda_batch_schedule.deleted', 0)
        //     ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
        //     ->join('rooms', 'tesda_schedule_details.roomID', '=', 'rooms.id')
        //     ->select(
        //         'tesda_course_competency.competency_desc',
        //         'tesda_course_competency.hours',
        //         'tesda_schedule_details.date_from',
        //         'tesda_schedule_details.date_to',
        //         'tesda_schedule_details.stime',
        //         'tesda_schedule_details.etime',
        //         'rooms.roomname',
        //         'tesda_course_competency.course_series_id'
        //     )
        //     ->get()
        //     ->groupBy('course_series_id');

        $pdf = PDF::loadView('tesda.pages.report.tesdaPrintAllCompletionCert', compact('students', 'schoolInfo'))
            ->setPaper('legal', 'landscape');

        return $pdf->stream('All_Certificates_of_Completion.pdf');
    }

    public function printApplicationForGraduation(Request $request)
    {
        $courseId = $request->query('course_id');
        $schoolInfo = DB::table('schoolinfo')->first();

        try {
            $students = DB::table('studinfo')
                ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
                ->join('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
                ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
                ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
                ->leftJoin('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
                ->leftJoin('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
                ->where('tesda_course_series.active', 1)
                ->where('tesda_schedule_details.deleted', 0);

            if (!empty($courseId)) {
                $students->where('tesda_courses.id', $courseId);
            }
            $students = $students->select(
                'studinfo.id as student_id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.gender',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                'tesda_course_series.description as series_desc',
                'tesda_course_series.id as series_id',
                'tesda_courses.id as course_id',
                'tesda_batches.batch_desc',
                'rooms.roomname',
                'tesda_schedule_details.stime',
                'tesda_schedule_details.etime',
                'tesda_batches.date_from',
                'tesda_batches.date_to'
            )
                ->groupBy('studinfo.id')
                ->get();

            // Check if students exist
            if ($students->isEmpty()) {
                return abort(404, 'No students found');
            }
            // Generate PDF
            $pdf = PDF::loadView('tesda.pages.report.tesdaApplicationForGraduation', compact('students', 'schoolInfo'))
                ->setPaper('legal');

            return $pdf->stream('All_Certificates_of_Completion.pdf');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', 'The selected course series is not active.');

        }
    }

    public function printSpecialOrderRequest(Request $request)
    {
        $courseId = $request->query('course_id');
        $schoolInfo = DB::table('schoolinfo')->first();

        try {
            $students = DB::table('studinfo')
                ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
                ->join('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
                ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
                ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
                ->leftJoin('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
                ->leftJoin('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
                ->where('tesda_course_series.active', 1)
                ->where('tesda_schedule_details.deleted', 0);

            if (!empty($courseId)) {
                $students->where('tesda_courses.id', $courseId);
            }
            $students = $students->select(
                'studinfo.id as student_id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.gender',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                'tesda_course_series.description as series_desc',
                'tesda_course_series.id as series_id',
                'tesda_courses.id as course_id',
                'tesda_batches.batch_desc',
                'rooms.roomname',
                'tesda_schedule_details.stime',
                'tesda_schedule_details.etime',
                'tesda_batches.date_from',
                'tesda_batches.date_to'
            )
                ->groupBy('studinfo.id')
                ->get();
            // return $students;
            // Check if students exist
            if ($students->isEmpty()) {
                return abort(404, 'No students found');
            }
            // Generate PDF
            $pdf = PDF::loadView('tesda.pages.report.tesdaPrintSpecialOrderReports', compact('students', 'schoolInfo'))
                ->setPaper('legal');

            return $pdf->stream('All_Certificates_of_Completion.pdf');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', 'The selected course series is not active.');

        }
    }
}