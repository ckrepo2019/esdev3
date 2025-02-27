<?php

namespace App\Http\Controllers\TesdaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TesdaCertificationController extends Controller
{
    public function exportNationalCertToPdf(Request $request)
    {

        $schoolinfo = DB::table('schoolinfo')->first();
        $students = DB::table('studinfo')
            ->where('studinfo.deleted', 0)
            ->where(function ($query) use ($request) {
                if (isset($request->id)) {
                    $query->where('studinfo.id', $request->id);
                }
            })
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            // ->join('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            // ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
            ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
            // ->join('tesda_course_competency', 'tesda_course_series.id', '=', 'tesda_course_competency.course_series_id')
            ->where('tesda_course_series.active', 1)
            // ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
            // ->join('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
            // ->where('studinfo.id', '54654654677')
            // ->where('tesda_schedule_details.deleted', 0)
            ->select(
                'studinfo.id as student_id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.gender',
                'studinfo.suffix',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                // 'tesda_course_competency.competency_desc',
                // 'tesda_course_competency.hours',
                // 'tesda_course_series.description as series_desc',
                'tesda_course_series.id as series_id',
                'tesda_courses.id as course_id',
                // 'tesda_batches.batch_desc',
                // 'rooms.roomname',
                // 'tesda_schedule_details.stime',
                // 'tesda_schedule_details.etime',
                // 'tesda_schedule_details.date_from',
                // 'tesda_schedule_details.date_to'
            )
            // ->take(2)
            // ->groupBy('studinfo.id')
            // // ->groupBy('tesda_course_competency.id')

            ->get();

        // dd($students);

        foreach ($students as $student) {
            $compArr = [];

            // Get competencies for the current student's series_id
            $competencies = DB::table('tesda_course_competency')
                ->where('deleted', 0)
                ->where('course_series_id', $student->series_id)
                ->get()
                ->groupBy('competency_type');

            // Convert Collection to an array
            foreach ($competencies as $desc => $items) {
                $compArr[$desc] = $items;
            }

            // Store grouped competencies in the student object
            $student->competencies = array_chunk($compArr, 2);
        }



        // dd($students);

         // Generate the QR Code as a base64 string
        $qrCode = QrCode::format('svg')->size(200)->generate('https://example.com');
        $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCode);


        $pdf = PDF::loadview('tesda/pages/pdf/national_certificate', compact('students', 'schoolinfo', 'qrCodeBase64'))->setPaper('legal', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('National Certificate.pdf');
    }

    public function exportHonorableDismissalCertToPdf($id = null)
    {
        $schoolinfo = DB::table('schoolinfo')->first();
        $students = DB::table('studinfo')
            ->where('studinfo.deleted', 0)
            ->where(function ($query) use ($id) {
                if (isset($id)) {
                    $query->where('studinfo.id', $id);
                }
            })
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            // ->join('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            // ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
            ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
            // ->join('tesda_course_competency', 'tesda_course_series.id', '=', 'tesda_course_competency.course_series_id')
            ->where('tesda_course_series.active', 1)
            // ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
            // ->join('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
            // ->where('studinfo.id', '54654654677')
            // ->where('tesda_schedule_details.deleted', 0)
            ->select(
                'studinfo.id as student_id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.gender',
                'studinfo.suffix',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                // 'tesda_course_competency.competency_desc',
                // 'tesda_course_competency.hours',
                'tesda_course_series.description as series_desc',
                'tesda_course_series.id as series_id',
                'tesda_courses.id as course_id',
                // 'tesda_batches.batch_desc',
                // 'rooms.roomname',
                // 'tesda_schedule_details.stime',
                // 'tesda_schedule_details.etime',
                // 'tesda_schedule_details.date_from',
                // 'tesda_schedule_details.date_to'
            )
            // ->take(2)
            // ->groupBy('studinfo.id')
            // // ->groupBy('tesda_course_competency.id')

            ->get();

        // dd($students);

        // foreach ($students as $student) {
        //     $compArr = [];

        //     // Get competencies for the current student's series_id
        //     $competencies = DB::table('tesda_course_competency')
        //         ->where('deleted', 0)
        //         ->where('course_series_id', $student->series_id)
        //         ->get()
        //         ->groupBy('competency_type');

        //     // Convert Collection to an array
        //     foreach ($competencies as $desc => $items) {
        //         $compArr[$desc] = $items;
        //     }

        //     // Store grouped competencies in the student object
        //     $student->competencies = array_chunk($compArr, 2);
        // }



        // dd($students);


        $pdf = PDF::loadview(
            'tesda/pages/pdf/honorable_dismissal',
            compact('students', 'schoolinfo')
        )
            ->setPaper('legal', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('Honorable Dismissal.pdf');
    }
}