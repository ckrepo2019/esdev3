<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use App\Models\Grading\IndividualGrading;
use App\Models\Grading\GradingSystem;
use App\Models\Grading\GradeSchool;
use App\Models\Principal\SPP_Subject;
use App\Models\Grading\GradeStatus;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class MasterSheetController extends Controller
{


    public static function mastersheet_2($students, $schoolinfo, $syinfo, $semester)
    {



    }

    public static function remove_duplicate()
    {
        $syid = 1;
        $semid = 2;

        $enrolled = DB::table('sh_enrolledstud')
            ->where('deleted', 0)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->select(
                'id',
                'studid'
            )
            ->get();

        foreach (collect($enrolled)->unique()->values() as $item) {

            $check = collect($enrolled)->where('studid', $item->studid)->values();

            if (count($check) > 1) {
                DB::table('sh_enrolledstud')
                    ->where('id', $check[1]->id)
                    ->take(1)
                    ->update([
                        'deleted' => 1,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            }


        }

        return "done";
    }

    public static function consolidated_pdf(Request $request)
    {

        $activesem = $request->get('semid');
        $activesy = $request->get('sy');
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $quarter = $request->get('quarter');
        $strandid = $request->get('strand');
        $subjid = $request->get('subjid');

        if ($gradelevel == 14 || $gradelevel == 15) {
            $activesem = $request->get('semid');
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects_sh($gradelevel, $strandid, $activesem, $activesy);

        } else {
            $isforsp = false;

            $sectioninfo = DB::table('sectiondetail')
                ->where('syid', $activesy)
                ->where('sectionid', $section)
                ->where('deleted', 0)
                ->first();

            if ($sectioninfo->sd_issp == 1) {
                $isforsp = true;
            }

            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel, $activesy, $isforsp);
        }

        $students = \App\Http\Controllers\TeacherControllers\TeacherGradingV4::get_student_data($request);
        $subjects = collect($subjects)->values();

        // return $students;

        $version = "V5";
        unset($students[0]);
        if ($gradelevel == 14 || $gradelevel == 15) {
            $students = collect($students)->where('strand', $strandid)->values();
        }

        $section_detail = DB::table('sections')
            ->leftJoin('teacher', function ($join) {
                $join->on('sections.teacherid', '=', 'teacher.id');
            })
            ->join('gradelevel', function ($join) {
                $join->on('sections.levelid', '=', 'gradelevel.id');
            })
            ->where('sections.id', $section)
            ->where('sections.deleted', 0)
            ->select('lastname', 'firstname', 'sectionname', 'levelname', 'levelid')
            ->first();

        $schoolyear_detail = DB::table('sy')->where('id', $activesy)->first();

        $schoolinfo = DB::table('schoolinfo')
            ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
            ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
            ->select('schoolinfo.*', 'refregion.regDesc', 'refcitymun.citymunDesc')
            ->get();

        // return $students;


        return $subjects;

        $pdf = PDF::loadView('teacher.grading.grade_summary.grade_summary_printable.grade_summary_con', compact('subjid', 'schoolinfo', 'students', 'subjects', 'quarter', 'section_detail', 'schoolyear_detail', 'activesem'))->setPaper('legal');
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);

        return $pdf->stream('MSQ' . $quarter . str_replace("GRADE ", "_G", $section_detail->levelname) . str_replace(" ", "_", $section_detail->sectionname) . '_SY' . $schoolyear_detail->sydesc . '_' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYYMMDDThhMMSS') . $version . '.pdf');


    }


    public static function excel_composite(Request $request)
    {

        $semid = $request->get('semid');
        $activesy = $request->get('sy');
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $quarter = $request->get('quarter');
        $strandid = $request->get('strand');


        $section_detail = Db::table('sectiondetail')
            ->join('sections', function ($join) {
                $join->on('sectiondetail.sectionid', '=', 'sections.id');
            })
            ->join('gradelevel', function ($join) {
                $join->on('sections.levelid', '=', 'gradelevel.id');
            })
            ->leftJoin('teacher', function ($join) {
                $join->on('sectiondetail.teacherid', '=', 'teacher.id');
            })
            ->where('sectiondetail.deleted', 0)
            ->where('sectionid', $section)
            ->where('syid', $activesy)
            ->select(
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'acadtitle',
                'title',
                'sectionname',
                'levelname',
                'levelid',
                'sd_issp'
            )
            ->first();


        if ($gradelevel == 14 || $gradelevel == 15) {
            $semid = $request->get('semid');
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects_sh($gradelevel, $strandid, $semid);
        } else {
            $isforsp = $section_detail->sd_issp == 1 ? true : false;
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel, $activesy, $isforsp);
        }

        $students = \App\Http\Controllers\TeacherControllers\TeacherGradingV4::get_student_data($request);

        $subjects = collect($subjects)->where('isVisible', 1)->values();
        foreach ($students as $item) {
            $item->grades = collect($item->grades)->where('isVisible', 1)->values();
        }

        unset($students[0]);
        if ($gradelevel == 14 || $gradelevel == 15) {
            $students = collect($students)->where('strand', $strandid)->values();
        }

        $schoolyear_detail = DB::table('sy')->where('id', $activesy)->first();

        $schoolinfo = DB::table('schoolinfo')
            ->select(
                'regiontext',
                'districttext',
                'divisiontext',
                'schoolname',
                'address'
            )
            ->first();


        $teachername = '';
        if (isset($section_detail)) {
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            $temp_acadtitle = '';
            if (isset($section_detail->middlename)) {
                $temp_middle = $section_detail->middlename[0] . '.';
            }
            if (isset($section_detail->title)) {
                $temp_title = $section_detail->title . '. ';
            }
            if (isset($section_detail->suffix)) {
                $temp_suffix = ', ' . $section_detail->suffix;
            }
            if (isset($teachsection_detailerinfo->acadtitle)) {
                $temp_acadtitle = ', ' . $section_detail->acadtitle;
            }
            $teachername = $temp_title . $section_detail->firstname . ' ' . $temp_middle . ' ' . $section_detail->lastname . $temp_suffix . $temp_acadtitle;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        ;

        $sheet = $spreadsheet->getActiveSheet();

        $borderstyle = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];

        $border_1 = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $font_bold = [
            'font' => [
                'bold' => true,
            ]
        ];

        $component = [
            'font' => [
                'bold' => false,
            ],
        ];

        $letterarray = array();
        for ($letters = 'C'; $letters != 'DZ'; $letters++) {
            $sheet->getColumnDimension($letters)->setWidth(6);
            array_push($letterarray, $letters);
        }

        $cell_number = 10;
        $sheet->getStyle('C' . $cell_number . ':' . 'BZ' . $cell_number)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C' . ($cell_number + 1) . ':' . 'BZ' . ($cell_number + 1))->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C' . ($cell_number + 2) . ':' . 'BZ' . ($cell_number + 2))->getAlignment()->setHorizontal('center');




        if (count($subjects) > 0) {
            $colcount = 0;
            foreach (collect($subjects)->sortBy('sortid')->values() as $subject) {

                if ($subject->isVisible == 1) {
                    if ($gradelevel == 14 || $gradelevel == 15) {

                        $sheet->mergeCells($letterarray[$colcount] . $cell_number . ':' . $letterarray[$colcount + 2] . $cell_number);
                        $sheet->setCellValue($letterarray[$colcount] . $cell_number, $subject->subjcode);

                        $sheet->mergeCells($letterarray[$colcount] . ($cell_number + 1) . ':' . $letterarray[$colcount + 1] . ($cell_number + 1));
                        $sheet->setCellValue($letterarray[$colcount] . ($cell_number + 1), 'Quarter');
                        $sheet->getStyle($letterarray[$colcount] . ($cell_number + 1) . ':' . $letterarray[$colcount + 1] . ($cell_number + 1))->getAlignment()->setHorizontal('center');

                        $sheet->getStyle($letterarray[$colcount] . ($cell_number + 1) . ':' . $letterarray[$colcount + 1] . ($cell_number + 1))->applyFromArray($borderstyle);
                        $sheet->getStyle($letterarray[$colcount] . $cell_number . ':' . $letterarray[$colcount + 1] . $cell_number)->applyFromArray($borderstyle);


                        if ($semid == 1) {
                            $sheet->setCellValue($letterarray[$colcount] . ($cell_number + 2), '1');
                            $sheet->setCellValue($letterarray[$colcount + 1] . ($cell_number + 2), '2');
                        } else if ($semid == 2) {
                            $sheet->setCellValue($letterarray[$colcount] . ($cell_number + 2), '3');
                            $sheet->setCellValue($letterarray[$colcount + 1] . ($cell_number + 2), '4');
                        }

                        $sheet->getStyle($letterarray[$colcount] . ($cell_number + 2))->applyFromArray($borderstyle);
                        $sheet->getStyle($letterarray[$colcount + 1] . ($cell_number + 2))->applyFromArray($borderstyle);


                        $sheet->setCellValue($letterarray[$colcount + 2] . ($cell_number + 2), 'Grade');
                        $sheet->setCellValue($letterarray[$colcount + 2] . ($cell_number + 1), 'Final');
                        $sheet->getColumnDimension($letterarray[$colcount + 2])->setWidth(6);
                        $sheet->getStyle($letterarray[$colcount + 2] . ($cell_number + 1) . ':' . $letterarray[$colcount + 2] . ($cell_number + 2))->applyFromArray($borderstyle);

                        $sheet->getColumnDimension($letterarray[$colcount])->setWidth(4);
                        $sheet->getColumnDimension($letterarray[$colcount + 1])->setWidth(4);

                        $sheet->getStyle($letterarray[$colcount] . ($cell_number + 2))->applyFromArray($borderstyle);
                        $sheet->getStyle($letterarray[$colcount + 1] . ($cell_number + 2))->applyFromArray($borderstyle);

                        $colcount += 3;

                    } else {

                        $sheet->mergeCells($letterarray[$colcount] . $cell_number . ':' . $letterarray[$colcount + 4] . $cell_number);
                        $sheet->setCellValue($letterarray[$colcount] . $cell_number, $subject->subjcode);

                        $sheet->mergeCells($letterarray[$colcount] . ($cell_number + 1) . ':' . $letterarray[$colcount + 3] . ($cell_number + 1));
                        $sheet->setCellValue($letterarray[$colcount] . ($cell_number + 1), 'Quarter');
                        $sheet->getStyle($letterarray[$colcount] . ($cell_number + 1) . ':' . $letterarray[$colcount + 3] . ($cell_number + 1))->getAlignment()->setHorizontal('center');

                        $sheet->getStyle($letterarray[$colcount] . ($cell_number + 1) . ':' . $letterarray[$colcount + 3] . ($cell_number + 1))->applyFromArray($borderstyle);
                        $sheet->getStyle($letterarray[$colcount] . $cell_number . ':' . $letterarray[$colcount + 4] . $cell_number)->applyFromArray($borderstyle);

                        $sheet->setCellValue($letterarray[$colcount + 4] . ($cell_number + 1), 'Final');
                        $sheet->setCellValue($letterarray[$colcount + 4] . ($cell_number + 2), 'Grade');
                        $sheet->getColumnDimension($letterarray[$colcount + 4])->setWidth(6);

                        $sheet->getStyle($letterarray[$colcount + 4] . ($cell_number + 1) . ':' . $letterarray[$colcount + 4] . ($cell_number + 2))->applyFromArray($borderstyle);


                        for ($x = 0; $x <= 3; $x++) {
                            $sheet->setCellValue($letterarray[$colcount + $x] . ($cell_number + 2), $x + 1);
                            $sheet->getColumnDimension($letterarray[$colcount + $x])->setWidth(4);
                            $sheet->getStyle($letterarray[$colcount + $x] . ($cell_number + 2))->applyFromArray($borderstyle);
                        }

                        $colcount += 5;

                    }
                }
            }
        }

        $last_column = $letterarray[$colcount - 1];



        $cell_number += 3;
        $male = 0;
        $female = 0;

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setAutoSize(true);

        foreach (collect($students)->where('student', '!=', 'SUBJECTS')->values() as $student) {
            if (($male == 0 && strtoupper($student->gender) == 'MALE') || ($female == 0 && strtoupper($student->gender) == 'FEMALE')) {

                $sheet->getStyle('A' . $cell_number)->applyFromArray($borderstyle);
                $sheet->getStyle('B' . $cell_number . ':' . $last_column . $cell_number)->applyFromArray($borderstyle);
                $sheet->mergeCells('B' . $cell_number . ':' . $last_column . $cell_number);
                $sheet->setCellValue('A' . $cell_number, '#');
                $sheet->getStyle('A' . $cell_number)->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('B' . $cell_number, strtoupper($student->gender));
                $cell_number += 1;
                $male = strtoupper($student->gender) == 'MALE' ? 1 : 0;
                $female = strtoupper($student->gender) == 'FEMALE' ? 1 : 0;
                $count = 1;

            }

            $sheet->setCellValue('A' . $cell_number, $count);
            $sheet->setCellValue('B' . $cell_number, $student->student);
            $sheet->getStyle('A' . $cell_number)->applyFromArray($border_1);
            $sheet->getStyle('B' . $cell_number)->applyFromArray($border_1);

            $colcount = 0;

            foreach (collect($student->grades)->where('id', '!=', 'G1')->sortBy('sortid')->values() as $stud_grades) {

                $sheet->getStyle('C' . $cell_number . ':' . $last_column . $cell_number)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $cell_number . ':' . $last_column . $cell_number)->applyFromArray($border_1);
                $sheet->getStyle('A' . $cell_number)->getAlignment()->setHorizontal('center');

                if ($stud_grades->subjid != null) {

                    if ($gradelevel == 14 || $gradelevel == 15) {
                        if ($semid == 1) {
                            $sheet->setCellValue($letterarray[$colcount] . $cell_number, $stud_grades->q1);
                            $sheet->setCellValue($letterarray[$colcount + 1] . $cell_number, $stud_grades->q2);
                        } else if ($semid == 2) {
                            $sheet->setCellValue($letterarray[$colcount] . $cell_number, $stud_grades->q3);
                            $sheet->setCellValue($letterarray[$colcount + 1] . $cell_number, $stud_grades->q4);
                        }

                        $sheet->getColumnDimension($letterarray[$colcount + 2])->setWidth(6);
                        $sheet->setCellValue($letterarray[$colcount + 2] . ($cell_number), $stud_grades->finalrating);

                        $colcount += 3;

                    } else {

                        $sheet->setCellValue($letterarray[$colcount] . $cell_number, $stud_grades->q1);
                        $sheet->setCellValue($letterarray[$colcount + 1] . $cell_number, $stud_grades->q2);
                        $sheet->setCellValue($letterarray[$colcount + 2] . $cell_number, $stud_grades->q3);
                        $sheet->setCellValue($letterarray[$colcount + 3] . $cell_number, $stud_grades->q4);

                        $sheet->getColumnDimension($letterarray[$colcount + 4])->setWidth(6);
                        $sheet->setCellValue($letterarray[$colcount + 4] . ($cell_number), $stud_grades->finalrating);

                        $colcount += 5;

                    }
                }

            }

            $cell_number += 1;
            $count += 1;
        }

        $sheet->mergeCells('A1:' . $letterarray[$colcount - 1] . '1');
        $sheet->setCellValue('A1', $schoolinfo->schoolname);
        $sheet->getStyle('A1')->applyFromArray($font_bold);

        $sheet->mergeCells('A2:' . $letterarray[$colcount - 1] . '2');
        $sheet->setCellValue('A2', $schoolinfo->address);

        $sheet->mergeCells('A4:' . $letterarray[$colcount - 1] . '4');
        $sheet->setCellValue('A4', 'M A S T E R   S H E E T   C O N S O L I D A T E D');
        $sheet->getStyle('A4')->applyFromArray($font_bold);

        $sheet->mergeCells('A5:' . $letterarray[$colcount - 1] . '5');
        $sheet->setCellValue('A5', strtoupper($section_detail->levelname) . ' - ' . strtoupper($section_detail->sectionname));
        $sheet->getStyle('A5')->applyFromArray($font_bold);

        $sheet->mergeCells('A6:' . $letterarray[$colcount - 1] . '6');
        $sheet->setCellValue('A6', strtoupper('SCHOOL YEAR') . ' - ' . $schoolyear_detail->sydesc);

        $sheet->getStyle('A1:A6')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A8:H8');
        $sheet->setCellValue('A8', 'ADVISER: ' . $teachername);
        $sheet->mergeCells('A8:H8');
        // $sheet->getStyle('A8')->applyFromArray($borderstyle);
        // $sheet->getStyle('B8')->applyFromArray($borderstyle);

        $version = "V5";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="MSQ"' . $quarter . str_replace("GRADE ", "_G", $section_detail->levelname) . str_replace(" ", "_", $section_detail->sectionname) . '_SY' . $schoolyear_detail->sydesc . '_' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYYMMDDThhMMSS') . $version . '.xlsx"');
        $writer->save("php://output");
        exit();

    }

    public static function excel_mastersheet(Request $request)
    {

        $activesem = $request->get('semid');
        $activesy = $request->get('sy');
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $quarter = $request->get('quarter');
        $strandid = $request->get('strand');
        $strandcode = '';

        if ($gradelevel == 14 || $gradelevel == 15) {
            $activesem = $request->get('semid');
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects_sh($gradelevel, $strandid, $activesem, $activesy);

            $strandinfo = DB::table('sh_strand')
                ->where('id', $strandid)
                ->select('strandcode')
                ->first();

            if (isset($strandinfo->strandcode)) {
                $strandcode = ' ( ' . $strandinfo->strandcode . ' )';
            }

        } else {
            $activesem = 1;
            $isforsp = false;

            $sectioninfo = DB::table('sectiondetail')
                ->where('syid', $activesy)
                ->where('sectionid', $section)
                ->where('deleted', 0)
                ->first();

            if ($sectioninfo->sd_issp == 1) {
                $isforsp = true;
            }

            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel, $activesy, $isforsp);
        }
        $students = \App\Http\Controllers\TeacherControllers\TeacherGradingV4::get_student_data($request);


        unset($students[0]);
        if ($gradelevel == 14 || $gradelevel == 15) {
            $students = collect($students)->where('strand', $strandid)->values();
        }

        $section_detail = DB::table('sections')
            ->leftJoin('teacher', function ($join) {
                $join->on('sections.teacherid', '=', 'teacher.id');
            })
            ->join('gradelevel', function ($join) {
                $join->on('sections.levelid', '=', 'gradelevel.id');
            })
            ->where('sections.id', $section)
            ->where('sections.deleted', 0)
            ->select('lastname', 'firstname', 'sectionname', 'levelname', 'levelid')
            ->first();

        $schoolyear_detail = DB::table('sy')->where('id', $activesy)->first();

        $schoolinfo = DB::table('schoolinfo')
            ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
            ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
            ->select('schoolinfo.*', 'refregion.regDesc', 'refcitymun.citymunDesc')
            ->first();

        $teacherinfo = Db::table('sectiondetail')
            ->select('teacher.*')
            ->leftJoin('teacher', 'sectiondetail.teacherid', '=', 'teacher.id')
            ->where('sectiondetail.deleted', 0)
            ->where('sectionid', $section)
            ->where('syid', $activesy)
            ->first();

        if (isset($teacherinfo)) {


            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            $temp_acadtitle = '';
            if (isset($teacherinfo->middlename)) {
                $temp_middle = ' ' . $teacherinfo->middlename[0] . '.';
            }
            if (isset($teacherinfo->title)) {
                $temp_title = $teacherinfo->title . '. ';
            }
            if (isset($teacherinfo->suffix)) {
                $temp_suffix = ', ' . $teacherinfo->suffix;
            }
            if (isset($teacherinfo->acadtitle)) {
                $temp_acadtitle = ', ' . $teacherinfo->acadtitle;
            }
            $teachername = $temp_title . $teacherinfo->firstname . $temp_middle . ' ' . $teacherinfo->lastname . $temp_suffix . $temp_acadtitle;

        } else {
            $teachername = "";
        }

        if ($request->get('quarter') == 1) {
            $quartername = '1st Quarter';
        } elseif ($request->get('quarter') == 2) {
            $quartername = '2nd Quarter';
        } elseif ($request->get('quarter') == 3) {
            $quartername = '3rd Quarter';
        } elseif ($request->get('quarter') == 4) {
            $quartername = '4th Quarter';
        } elseif ($request->get('quarter') == 5) {
            $quartername = 'Final Grade';
        }

        $gradelevelinfo = Db::table('gradelevel')
            ->select('levelname', 'acadprogcode')
            ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->where('gradelevel.id', $gradelevel)
            ->first();

        if ($gradelevelinfo) {
            if (strtolower($gradelevelinfo->acadprogcode) == 'college') {
                $sectioninfo = Db::table('college_sections')
                    ->select('sectionDesc as sectionname')
                    ->where('id', $section)
                    ->first();

            } else {
                $sectioninfo = Db::table('sections')
                    ->select('sectionname')
                    ->where('id', $section)
                    ->first();
            }
        } else {
            $sectioninfo = (object) array();
        }

        $schoolyearinfo = DB::table('sy')
            ->where('id', $activesy)
            ->first();

        $format = $request->get('format');

        if ($format == 2) {

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load("GRADE MASTER SHEET/GRADE_MASTER_SHEET_HCHS.xlsx");

            $sheet = $spreadsheet->setActiveSheetIndex(0);

            $first_char = 72;
            foreach (collect($subjects)->sortBy('sortid')->values() as $subject) {
                $sheet->setCellValue(chr($first_char) . '9', $subject->subjdesc);
                $sheet->setCellValue(chr($first_char) . '59', $subject->subjdesc);
                $first_char += 1;
            }

            $quarterlabel = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $quarterlabel->createText('Quarter: ');
            $quartertext = $quarterlabel->createTextRun($quartername);
            $quartertext->getFont()->setBold(true);
            $quartertext->getFont()->setUnderline(true);
            $quartertext->getFont()->setName('Times New Roman');


            // return collect($schoolinfo);

            $sheet->setCellValue('F6', $quarterlabel);
            $sheet->setCellValue('F56', $quarterlabel);

            $sectionlabel = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $sectionlabel->createText('Grade & Section: ');
            $sectiontext = $sectionlabel->createTextRun($section_detail->levelname . ' - ' . $section_detail->sectionname);
            $sectiontext->getFont()->setBold(true);
            $sectiontext->getFont()->setUnderline(true);
            $sectiontext->getFont()->setName('Times New Roman');

            $sheet->setCellValue('L6', $sectionlabel);
            $sheet->setCellValue('L56', $sectionlabel);


            $sectionlabel = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $sectionlabel->createText('School Name: ');
            $sectiontext = $sectionlabel->createTextRun($schoolinfo->schoolname);
            $sectiontext->getFont()->setBold(true);
            $sectiontext->getFont()->setUnderline(true);
            $sectiontext->getFont()->setName('Times New Roman');

            $sheet->setCellValue('F5', $sectionlabel);
            $sheet->setCellValue('F55', $sectionlabel);



            $sectionlabel = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $sectionlabel->createText('Division: ');
            $sectiontext = $sectionlabel->createTextRun($schoolinfo->divisiontext);
            $sectiontext->getFont()->setBold(true);
            $sectiontext->getFont()->setUnderline(true);
            $sectiontext->getFont()->setName('Times New Roman');

            $sheet->setCellValue('L4', $sectionlabel);
            $sheet->setCellValue('L54', $sectionlabel);

            $sectionlabel = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $sectionlabel->createText('Shool ID: ');
            $sectiontext = $sectionlabel->createTextRun($schoolinfo->schoolid);
            $sectiontext->getFont()->setBold(true);
            $sectiontext->getFont()->setUnderline(true);
            $sectiontext->getFont()->setName('Times New Roman');

            $sheet->setCellValue('L5', $sectionlabel);
            $sheet->setCellValue('L55', $sectionlabel);

            $sectionlabel = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $sectionlabel->createText('District: ');
            $sectiontext = $sectionlabel->createTextRun($schoolinfo->districttext);
            $sectiontext->getFont()->setBold(true);
            $sectiontext->getFont()->setUnderline(true);
            $sectiontext->getFont()->setName('Times New Roman');

            $sheet->setCellValue('Q4', $sectionlabel);
            $sheet->setCellValue('Q54', $sectionlabel);



            $teachernamelabel = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $teachernamelabel->createText('Adviser: ');
            $teachernametext = $teachernamelabel->createTextRun($teachername);
            $teachernametext->getFont()->setBold(true);
            $teachernametext->getFont()->setUnderline(true);
            $teachernametext->getFont()->setName('Times New Roman');

            $sheet->setCellValue('Q6', $teachernamelabel);
            $sheet->setCellValue('Q56', $teachernamelabel);

            $sydesclabel = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $sydesclabel->createText('School Year: ');
            $sydesctext = $sydesclabel->createTextRun($schoolyearinfo->sydesc);
            $sydesctext->getFont()->setBold(true);
            $sydesctext->getFont()->setUnderline(true);
            $sydesctext->getFont()->setName('Times New Roman');

            $sheet->setCellValue('Q5', $sydesclabel);
            $sheet->setCellValue('Q55', $sydesclabel);

            $male_row = 9;
            $female_row = 59;

            foreach (collect($students)->where('student', '!=', 'SUBJECTS')->values() as $student) {
                $row = 0;
                if ($student->gender == 'MALE') {
                    $male_row += 1;
                    $row = $male_row;
                } else {
                    $female_row += 1;
                    $row = $female_row;
                }

                $studgrades = $student->grades;
                $temp_qg = 'q' . $quarter;
                $first_char = 72;
                $formula = '= (';


                foreach ($subjects as $subjitem) {

                    $subjgrades = collect($studgrades)->where('subjid', $subjitem->subjid)->first();

                    if (isset($subjgrades)) {
                        $sheet->setCellValue(chr($first_char) . $row, $subjgrades->$temp_qg);
                    }



                    if ($subjitem->inSF9 == 1 && $subjitem->subjCom == null) {
                        $formula .= chr($first_char) . $row . ' + ';
                    }

                    $first_char += 1;

                }

                $formula = substr($formula, 0, -2) . ')/' . collect($subjects)->where('inSF9', 1)->where('subjCom', null)->count();

                $sheet->setCellValue('C' . $row, $student->student);
                $sheet->setCellValue('U' . $row, $formula);

            }

            $version = "V5";
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="MSQ"' . $quarter . str_replace("GRADE ", "_G", $section_detail->levelname) . str_replace(" ", "_", $section_detail->sectionname) . '_SY' . $schoolyear_detail->sydesc . '_' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYYMMDDThhMMSS') . $version . '.xlsx"');
            $writer->save("php://output");
            exit();
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        ;

        $sheet = $spreadsheet->getActiveSheet();
        $borderstyle = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $font_bold = [
            'font' => [
                'bold' => true,
            ]
        ];

        $letterval = 65; // A
        $contanstantcellcount = 6;

        $numofsubjects = 0;

        foreach (range('A', chr($letterval + $contanstantcellcount)) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $last_column = chr(67 + count($subjects) + 2);
        if (count($subjects) > 0) {
            $quarter_holder = chr((67 + count($subjects) + 2) - 5);
        } else {
            $quarter_holder = 'C';
        }


        $sheet->mergeCells('A1:' . $last_column . '1');
        $sheet->setCellValue('A1', $schoolinfo->schoolname);
        $sheet->getStyle('A1')->applyFromArray($font_bold);

        $sheet->mergeCells('A2:' . $last_column . '2');
        $sheet->setCellValue('A2', $schoolinfo->address);

        $sheet->mergeCells('A4:' . $last_column . '4');
        $sheet->setCellValue('A4', 'M A S T E R   S H E E T S');
        $sheet->getStyle('A4')->applyFromArray($font_bold);

        $sheet->mergeCells('A5:' . $last_column . '5');
        $sheet->setCellValue('A5', strtoupper($gradelevelinfo->levelname) . ' - ' . strtoupper($sectioninfo->sectionname) . $strandcode);
        $sheet->getStyle('A5')->applyFromArray($font_bold);

        $sheet->mergeCells('A6:' . $last_column . '6');
        $sheet->setCellValue('A6', strtoupper('SCHOOL YEAR') . ' - ' . $schoolyearinfo->sydesc);

        $sheet->getStyle('A1:A6')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A8:B8');
        $sheet->setCellValue('A8', 'TEACHER: ' . $teachername);

        $sheet->mergeCells($quarter_holder . '8:' . $last_column . '8');

        $sheet->setCellValue($quarter_holder . '8', 'GRADING PERIOD: ' . $quartername);

        $countstudmale = 1;
        $startcellno = 10;
        $numofsubjectswithgrades = 0;

        if (count($subjects) > 0) {
            $subjectletterval = 67; // F
            $sheet->getStyle('A9')->applyFromArray($borderstyle);
            $sheet->getStyle('B9')->applyFromArray($borderstyle);

            foreach (collect($subjects)->sortBy('sortid')->values() as $subject) {

                //if($subject->isVisible == 1){
                if ($gradelevel == 14 || $gradelevel == 15) {
                    $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($font_bold);
                    $sheet->getStyle(chr($subjectletterval) . '9')->getAlignment()->setTextRotation(90);
                    $sheet->setCellValue(chr($subjectletterval) . '9', $subject->subjdesc);
                    $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($borderstyle);
                    $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
                    $sheet->getColumnDimension(chr($subjectletterval))->setWidth(8);
                    $sheet->getStyle(chr($subjectletterval) . '9')->getAlignment()->setWrapText(true);
                    $sheet->getStyle(chr($subjectletterval) . '9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getRowDimension(9)->setRowHeight(200);
                    $subjectletterval += 1;
                } else {
                    if ($request->get('quarter') == 5) {
                        if ($subject->subjCom == null) {
                            $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($font_bold);
                            $sheet->getStyle(chr($subjectletterval) . '9')->getAlignment()->setTextRotation(90);
                            $sheet->setCellValue(chr($subjectletterval) . '9', $subject->subjdesc);
                            $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($borderstyle);
                            $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
                            $sheet->getColumnDimension(chr($subjectletterval))->setWidth(5);
                            $sheet->getColumnDimension('E')->setWidth(5);
                            $subjectletterval += 1;
                        }
                    } else {
                        if ($subject->subjCom == null) {
                            $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($font_bold);
                            $sheet->setCellValue(chr($subjectletterval) . '9', $subject->subjdesc);
                        } else {
                            $sheet->setCellValue(chr($subjectletterval) . '9', '     ' . $subject->subjdesc);
                        }
                        $sheet->getStyle(chr($subjectletterval) . '9')->getAlignment()->setTextRotation(90);

                        $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($borderstyle);
                        $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
                        $sheet->getColumnDimension(chr($subjectletterval))->setWidth(5);
                        $sheet->getColumnDimension('E')->setWidth(5);
                        $subjectletterval += 1;
                    }
                }

                //}

            }

            if ($quarter != 5) {
                $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($borderstyle);
                $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
                $sheet->getColumnDimension(chr($subjectletterval))->setWidth(5);
                $subjectletterval += 1;
            } else {
                foreach (collect($subjects)->where('subjCom', '!=', null)->values() as $item) {
                    $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($borderstyle);
                    $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
                    $sheet->getColumnDimension(chr($subjectletterval))->setWidth(5);
                    $subjectletterval += 1;
                }
                $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($borderstyle);
                $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
                $sheet->getColumnDimension(chr($subjectletterval))->setWidth(5);
                $subjectletterval += 1;
            }

            $sheet->setCellValue(chr($subjectletterval) . '9', 'AVERAGE');
            $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($font_bold);
            $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($borderstyle);
            $sheet->getStyle(chr($subjectletterval) . '9')->getAlignment()->setTextRotation(90);
            $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
            $sheet->getColumnDimension(chr($subjectletterval))->setWidth(5);
            $subjectletterval += 1;
            $sheet->setCellValue(chr($subjectletterval) . '9', 'COMPOSITE');
            $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($font_bold);
            $sheet->getStyle(chr($subjectletterval) . '9')->applyFromArray($borderstyle);
            $sheet->getStyle(chr($subjectletterval) . '9')->getAlignment()->setTextRotation(90);
            $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
            $sheet->getColumnDimension(chr($subjectletterval))->setWidth(5);
        }

        $cell_number = 10;
        $male = 0;
        $female = 0;

        $count = 1;
        foreach (collect($students)->where('student', '!=', 'SUBJECTS')->values() as $student) {
            if ($male == 0 && strtoupper($student->gender) == 'MALE') {
                $sheet->getStyle('A' . $cell_number)->applyFromArray($borderstyle);
                $sheet->getStyle('B' . $cell_number)->applyFromArray($borderstyle);
                $sheet->getStyle('C' . $cell_number . ':' . $last_column . $cell_number)->applyFromArray($borderstyle);
                $sheet->setCellValue('A' . $cell_number, '#');
                $sheet->getStyle('A' . $cell_number)->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('B' . $cell_number, 'MALE');
                $cell_number += 1;
                $male = 1;
            }

            if ($female == 0 && strtoupper($student->gender) == 'FEMALE') {
                $sheet->getStyle('A' . $cell_number)->applyFromArray($borderstyle);
                $sheet->getStyle('B' . $cell_number)->applyFromArray($borderstyle);
                $sheet->getStyle('C' . $cell_number . ':' . $last_column . $cell_number)->applyFromArray($borderstyle);
                $sheet->getStyle('A' . $cell_number)->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A' . $cell_number, '#');
                $sheet->setCellValue('B' . $cell_number, 'FEMALE');
                $cell_number += 1;
                $female = 1;
                $count = 1;
            }

            $sheet->setCellValue('A' . $cell_number, $count);
            $sheet->setCellValue('B' . $cell_number, $student->student);
            $sheet->getStyle('A' . $cell_number)->applyFromArray($borderstyle);
            $sheet->getStyle('B' . $cell_number)->applyFromArray($borderstyle);
            $subjectletterval = 67; // F
            foreach (collect($student->grades)->where('id', '!=', 'G1')->sortBy('sortid')->values() as $stud_grades) {
                $sheet->getStyle(chr($subjectletterval) . $cell_number)->applyFromArray($borderstyle);
                if ($stud_grades->subjid != null) {
                    if ($gradelevel == 14 || $gradelevel == 15) {
                        if (isset($stud_grades->qg)) {
                            $sheet->setCellValue(chr($subjectletterval) . $cell_number, $stud_grades->qg);
                        }
                        $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
                        $sheet->getStyle(chr($subjectletterval) . $cell_number)->getAlignment()->setHorizontal('center');
                        // $sheet->getColumnDimension('E')->setWidth(5);
                        $subjectletterval += 1;
                    } else {
                        if ($request->get('quarter') == 5) {
                            if ($stud_grades->subjCom == null) {
                                if (isset($stud_grades->qg)) {
                                    $sheet->setCellValue(chr($subjectletterval) . $cell_number, $stud_grades->qg);
                                }
                                $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
                                $sheet->getStyle(chr($subjectletterval) . $cell_number)->getAlignment()->setHorizontal('center');
                                $sheet->getColumnDimension('E')->setWidth(5);
                                $subjectletterval += 1;
                            }
                        } else {
                            if (isset($stud_grades->qg)) {
                                $sheet->setCellValue(chr($subjectletterval) . $cell_number, $stud_grades->qg);
                            }
                            $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
                            $sheet->getStyle(chr($subjectletterval) . $cell_number)->getAlignment()->setHorizontal('center');
                            $sheet->getColumnDimension('E')->setWidth(5);
                            $subjectletterval += 1;
                        }
                    }


                }
            }

            $sheet->getStyle(chr($subjectletterval) . $cell_number)->applyFromArray($borderstyle);

            if ($quarter != 5) {
                $subjectletterval += 1;
            } else {
                foreach (collect($subjects)->where('subjCom', '!=', null)->values() as $item) {
                    $sheet->getStyle(chr($subjectletterval) . $cell_number)->applyFromArray($borderstyle);
                    $subjectletterval += 1;
                }
                $sheet->getStyle(chr($subjectletterval) . $cell_number)->applyFromArray($borderstyle);
                $subjectletterval += 1;
            }

            if ($quarter != 5) {
                $temp_qg = 'q' . $quarter;
                $sheet->setCellValue(chr($subjectletterval) . $cell_number, collect($student->grades)->where('id', 'G1')->first()->$temp_qg);
            } else {
                $sheet->setCellValue(chr($subjectletterval - 1) . $cell_number, collect($student->grades)->where('id', 'G1')->first()->finalrating);
                $sheet->setCellValue(chr($subjectletterval) . $cell_number, collect($student->grades)->where('id', 'G1')->first()->fcomp);
            }

            $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
            $sheet->getStyle(chr($subjectletterval) . $cell_number)->applyFromArray($borderstyle);
            $sheet->getStyle(chr($subjectletterval) . $cell_number)->getAlignment()->setHorizontal('center');
            $subjectletterval += 1;

            if ($quarter != 5) {

                $temp_qgcomp = 'q' . $quarter . 'comp';
                $temp_qg = 'q' . $quarter;

                if ($gradelevel == 14 || $gradelevel == 15) {
                    $sheet->setCellValue(chr($subjectletterval - 1) . $cell_number, collect($student->grades)->where('id', 'G1')->where('semid', $activesem)->first()->$temp_qg);
                    $sheet->setCellValue(chr($subjectletterval) . $cell_number, collect($student->grades)->where('id', 'G1')->where('semid', $activesem)->first()->$temp_qgcomp);
                } else {
                    $sheet->setCellValue(chr($subjectletterval - 1) . $cell_number, collect($student->grades)->where('id', 'G1')->first()->$temp_qg);
                    $sheet->setCellValue(chr($subjectletterval) . $cell_number, collect($student->grades)->where('id', 'G1')->first()->$temp_qgcomp);
                }

            } else {
                if ($gradelevel == 14 || $gradelevel == 15) {
                    $sheet->setCellValue(chr($subjectletterval - 1) . $cell_number, collect($student->grades)->where('id', 'G1')->first()->finalrating);
                    $sheet->setCellValue(chr($subjectletterval) . $cell_number, collect($student->grades)->where('id', 'G1')->first()->fcomp);
                } else {
                    $sheet->setCellValue(chr($subjectletterval - 1) . $cell_number, collect($student->grades)->where('id', 'G1')->first()->finalrating);
                    $sheet->setCellValue(chr($subjectletterval) . $cell_number, collect($student->grades)->where('id', 'G1')->first()->fcomp);
                }

            }

            $sheet->getColumnDimension(chr($subjectletterval))->setAutoSize(false);
            $sheet->getStyle(chr($subjectletterval) . $cell_number)->applyFromArray($borderstyle);
            $sheet->getStyle(chr($subjectletterval) . $cell_number)->getAlignment()->setHorizontal('center');

            $subjectletterval += 5;

            $cell_number += 1;
            $count += 1;
        }

        $version = "V5";

        if (count($students) == 0) {
            $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea('A1:' . 'E' . $cell_number);
        } else {
            $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea('A1:' . chr($subjectletterval - 5) . $cell_number);
        }


        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0);

        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToPage(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        $spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(85);


        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="MSQ"' . $quarter . str_replace("GRADE ", "_G", $section_detail->levelname) . str_replace(" ", "_", $section_detail->sectionname) . '_SY' . $schoolyear_detail->sydesc . '_' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYYMMDDThhMMSS') . $version . '.xlsx"');
        $writer->save("php://output");

    }

    public function mastersheet(Request $request)
    {

        $activesem = $request->get('semid');
        $activesy = $request->get('sy');
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $quarter = $request->get('quarter');
        $strandid = $request->get('strand');

        if ($gradelevel == 14 || $gradelevel == 15) {
            $activesem = $request->get('semid');
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects_sh($gradelevel, $strandid, $activesem, $activesy);

        } else {
            $isforsp = false;

            $sectioninfo = DB::table('sectiondetail')
                ->where('syid', $activesy)
                ->where('sectionid', $section)
                ->where('deleted', 0)
                ->first();

            if ($sectioninfo->sd_issp == 1) {
                $isforsp = true;
            }

            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel, $activesy, $isforsp);
        }

        $students = \App\Http\Controllers\TeacherControllers\TeacherGradingV4::get_student_data($request);
        $subjects = collect($subjects)->values();

        // return $students;

        $version = "V5";
        unset($students[0]);
        if ($gradelevel == 14 || $gradelevel == 15) {
            $students = collect($students)->where('strand', $strandid)->values();
        }

        $section_detail = DB::table('sections')
            ->leftJoin('teacher', function ($join) {
                $join->on('sections.teacherid', '=', 'teacher.id');
            })
            ->join('gradelevel', function ($join) {
                $join->on('sections.levelid', '=', 'gradelevel.id');
            })
            ->where('sections.id', $section)
            ->where('sections.deleted', 0)
            ->select('lastname', 'firstname', 'sectionname', 'levelname', 'levelid')
            ->first();

        $schoolyear_detail = DB::table('sy')->where('id', $activesy)->first();

        $schoolinfo = DB::table('schoolinfo')
            ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
            ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
            ->select('schoolinfo.*', 'refregion.regDesc', 'refcitymun.citymunDesc')
            ->get();

        $pdf = PDF::loadView('teacher.grading.grade_summary.grade_summary_printable.grade_summary_sf9', compact('schoolinfo', 'students', 'subjects', 'quarter', 'section_detail', 'schoolyear_detail', 'activesem'))->setPaper('legal');
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);

        return $pdf->stream('MSQ' . $quarter . str_replace("GRADE ", "_G", $section_detail->levelname) . str_replace(" ", "_", $section_detail->sectionname) . '_SY' . $schoolyear_detail->sydesc . '_' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYYMMDDThhMMSS') . $version . '.pdf');

    }
    public function bysubject(Request $request)
    {

        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');

        if ($request->has('sy')) {
            $syid = $request->get('sy');
        } else {
            $syid = $request->get('syid');
        }

        $semid = $request->get('semid');
        $subjid = $request->get('subjid');
        $status = $request->get('status');
        $strandid = $request->get('strand');

        $subjid = $request->get('subjid');
        $request['quarter'] = null;

        if ($gradelevel == 14 || $gradelevel == 15) {
            $activesem = $request->get('semid');
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects_sh($gradelevel, $strandid, $activesem);
        } else {
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel);
        }

        $students = \App\Http\Controllers\TeacherControllers\TeacherGradingV4::get_student_data($request);
        $version = "V5";
        unset($students[0]);


        if ($gradelevel == 14 || $gradelevel == 15) {
            $students = collect($students)->where('strand', $strandid)->values();
        }

        $data = $students;


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        ;
        $sheet = $spreadsheet->getActiveSheet();
        $borderstyle = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $font_bold = [
            'font' => [
                'bold' => true,
            ]
        ];

        $schoolinfo = Db::table('schoolinfo')->first();

        $gradelevelinfo = DB::table('gradelevel')
            ->select('gradelevel.*', 'academicprogram.acadprogcode')
            ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->where('gradelevel.id', $gradelevel)
            ->first();

        if (strtolower($gradelevelinfo->acadprogcode) == 'college') {

        } else {
            $sectioninfo = Db::table('sections')
                ->where('id', $section)
                ->first();
        }

        $schoolyearinfo = DB::table('sy')
            ->where('id', $syid)
            ->first();

        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->getColumnDimension('B')->setAutoSize(false);
        $sheet->getColumnDimension('B')->setWidth(15);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(base_path() . '/public/assets/images/department_of_Education.png');
        // $drawing->setPath(base_path().'/public/'.$schoolinfo->picurl);
        $drawing->setHeight(80);
        $drawing->setWorksheet($sheet);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(20);

        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);


        $sheet->mergeCells('D1:G2');
        $sheet->setCellValue('D1', 'Summary of Quarterly Grades');
        $sheet->getStyle('D1')->applyFromArray($font_bold);
        $sheet->getStyle('D1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('C4', 'REGION');
        $sheet->setCellValue('C5', 'SCHOOL NAME');

        $sheet->getStyle('C4:C5')->getAlignment()->setHorizontal('right');
        $sheet->setCellValue('D4', $schoolinfo->regiontext);
        $sheet->getStyle('D4')->applyFromArray($borderstyle);
        $sheet->getStyle('D4')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('E4', 'DIVISION');
        $sheet->getStyle('E4')->getAlignment()->setHorizontal('right');
        $sheet->setCellValue('F4', $schoolinfo->divisiontext);
        $sheet->getStyle('F4')->applyFromArray($borderstyle);
        $sheet->getStyle('F4')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('H1:I1');
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(base_path() . '/public/assets/images/deped_logo.png');
        $drawing->setWidth(110);
        $drawing->setWorksheet($sheet);
        $drawing->setCoordinates('H1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(15);

        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);



        $sheet->mergeCells('D5:F5');
        $sheet->setCellValue('D5', $schoolinfo->schoolname);
        $sheet->getStyle('D5:F5')->applyFromArray($borderstyle);
        $sheet->getStyle('D5')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('G5', 'SCHOOL ID');
        $sheet->getStyle('G5')->getAlignment()->setHorizontal('right');
        $sheet->mergeCells('H5:I5');
        $sheet->setCellValue('H5', $schoolinfo->schoolid);
        $sheet->getStyle('H5:I5')->applyFromArray($borderstyle);
        $sheet->getStyle('H5')->getAlignment()->setHorizontal('center');


        $sheet->mergeCells('A7:A9');
        $sheet->getStyle('A7:A9')->applyFromArray($borderstyle);


        $sheet->mergeCells('B7:C9');
        $sheet->getStyle('B7:C9')->applyFromArray($borderstyle);
        $sheet->getStyle('B7:C9')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B7:C9')->getAlignment()->setVertical('center');
        $sheet->setCellValue('B7', 'LEARNERS\' NAMES');

        $studentgrades = array();

        if ($gradelevel == 14 || $gradelevel == 15) {

            $teachername = '';

        } else {

            $teachername = '';

            $teacher = DB::table('assignsubj')
                ->where('sectionid', $section)
                ->where('syid', $syid)
                ->where('glevelid', $gradelevel)
                ->join('assignsubjdetail', function ($join) use ($subjid) {
                    $join->on('assignsubj.id', '=', 'assignsubjdetail.headerid');
                    $join->where('assignsubjdetail.deleted', 0);
                    $join->where('assignsubjdetail.subjid', $subjid);
                })
                ->join('teacher', function ($join) {
                    $join->on('assignsubjdetail.teacherid', '=', 'teacher.id');
                    $join->where('teacher.deleted', 0);
                })
                ->select('firstname', 'lastname', 'middlename', 'suffix')
                ->where('assignsubj.deleted', 0)
                ->first();



            if (isset($teacher->firstname)) {
                if ($teacher->middlename == null) {
                    $teachername = $teacher->lastname . ', ' . $teacher->firstname . ' ' . $teacher->suffix;
                } else {
                    $teachername = $teacher->lastname . ', ' . $teacher->firstname . ' ' . $teacher->middlename[0] . '. ' . $teacher->suffix;
                }
            }
        }


        if (count($data) > 0) {
            foreach ($data as $eachdata) {
                if (strtolower($eachdata->student) != 'subjects') {
                    $gender = Db::table('studinfo')
                        ->where('id', $eachdata->studid)
                        ->first()->gender;
                    $eachdata->gender = $gender;
                    $filteredsubject = collect($eachdata->grades)->filter(function ($value, $key) use ($subjid) {
                        if ($value->subjid == $subjid) {
                            return $value;
                        }
                    })->values()->all();
                    $eachdata->subjectgrades = $filteredsubject;
                    $eachdata->subjectgrades[0]->teachername = $teachername;
                    $eachdata->subjectgrades = $filteredsubject;
                    array_push($studentgrades, $eachdata);
                }
            }
        }
        // return $studentgrades;
        $sheet->mergeCells('D7:F7');
        $sheet->getStyle('D7:F7')->applyFromArray($borderstyle);
        $sheet->setCellValue('D7', 'GRADE & SECTION: ' . $gradelevelinfo->levelname . ' - ' . $sectioninfo->sectionname);

        $sheet->mergeCells('G7:I7');
        $sheet->getStyle('G7:I7')->applyFromArray($borderstyle);
        $sheet->setCellValue('G7', 'SCHOOL YEAR: ' . $schoolyearinfo->sydesc);

        $sheet->mergeCells('D8:F8');
        $sheet->getStyle('D8:F8')->applyFromArray($borderstyle);

        if (count($studentgrades) > 0) {
            if (count($studentgrades[0]->subjectgrades) > 0) {
                $sheet->setCellValue('D8', 'TEACHER: ' . $studentgrades[0]->subjectgrades[0]->teachername);
            } else {
                $sheet->setCellValue('D8', 'TEACHER:');
            }

        } else {
            $sheet->setCellValue('D8', 'TEACHER:');
        }
        $sheet->mergeCells('G8:I8');
        $sheet->getStyle('G8:I8')->applyFromArray($borderstyle);
        if (count($studentgrades) > 0) {
            if (count($studentgrades[0]->subjectgrades) > 0) {
                $subjectname = $studentgrades[0]->subjectgrades[0]->subjdesc;
                $sheet->setCellValue('G8', 'SUBJECT: ' . $studentgrades[0]->subjectgrades[0]->subjdesc);

            } else {
                $subjectname = "";
                $sheet->setCellValue('G8', 'SUBJECT: ');

            }
        } else {
            $subjectname = "";
            $sheet->setCellValue('G8', 'SUBJECT: ');
        }

        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {

            if ($semid == 1) {
                $sheet->mergeCells('D9:E9');
                $sheet->setCellValue('D9', '1st Quarter');
                $sheet->getStyle('D9')->applyFromArray($borderstyle);
                $sheet->mergeCells('F9:G9');
                $sheet->setCellValue('F9', '2nd Quarter');
                $sheet->getStyle('F9')->applyFromArray($borderstyle);
            } else if ($semid == 2) {
                $sheet->mergeCells('D9:E9');
                $sheet->setCellValue('D9', '3rd Quarter');
                $sheet->getStyle('D9')->applyFromArray($borderstyle);
                $sheet->mergeCells('F9:G9');
                $sheet->setCellValue('F9', '4th Quarter');
                $sheet->getStyle('F9')->applyFromArray($borderstyle);
            }


        } else {
            $sheet->setCellValue('D9', '1st Quarter');
            $sheet->getStyle('D9')->applyFromArray($borderstyle);

            $sheet->setCellValue('E9', '2nd Quarter');
            $sheet->getStyle('E9')->applyFromArray($borderstyle);

            $sheet->setCellValue('F9', '3rd Quarter');
            $sheet->getStyle('F9')->applyFromArray($borderstyle);

            $sheet->setCellValue('G9', '4th Quarter');
            $sheet->getStyle('G9')->applyFromArray($borderstyle);
        }

        // $sheet->setCellValue('H9','FINAL GRADE');
        // $sheet->getStyle('H9')->applyFromArray($borderstyle);

        // $sheet->setCellValue('I9','REMARKS');
        // $sheet->getStyle('I9')->applyFromArray($borderstyle);




        $sheet->setCellValue('H9', 'TENTATIVE');
        $sheet->getStyle('H9')->applyFromArray($borderstyle);

        $sheet->setCellValue('I9', 'FINAL GRADE');
        $sheet->getStyle('I9')->applyFromArray($borderstyle);

        $sheet->setCellValue('J9', 'REMARKS');
        $sheet->getStyle('J9')->applyFromArray($borderstyle);

        $sheet->getStyle('D9:J9')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D7:J9')->getAlignment()->setVertical('center');


        $sheet->getStyle('A10:J0')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('DDDDDD');

        $studentgrades = collect($studentgrades)->sortBy('student')->values()->all();
        // $sheet->setReadDataOnly(false);
        $startcellno = 11;
        $malecount = 1;
        $femalecount = 1;



        $sheet->mergeCells('B10:C10');
        $sheet->getStyle('B10:C10')->applyFromArray($borderstyle);
        $sheet->setCellValue('B10', 'MALE');

        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
            $sheet->mergeCells('D10:E10');
            $sheet->mergeCells('F10:G10');
        }

        foreach (range('D', 'J') as $columnID) {
            $sheet->getStyle($columnID . '10')->applyFromArray($borderstyle);
        }

        if (count($studentgrades) > 0) {
            foreach ($studentgrades as $studentgrade) {
                if (strtolower($studentgrade->gender) == 'male') {
                    $sheet->mergeCells('B' . $startcellno . ':C' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, $malecount);
                    $sheet->getStyle('A' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('B' . $startcellno, $studentgrade->student);
                    $sheet->getStyle('B' . $startcellno . ':C' . $startcellno)->applyFromArray($borderstyle);

                    foreach (range('D', 'J') as $columnID) {
                        $sheet->getStyle($columnID . $startcellno)->applyFromArray($borderstyle);
                    }
                    $complete = 0;
                    $totalgrade = 0;
                    if (count($studentgrade->subjectgrades) > 0) {
                        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
                            if ($request->get('semid') == 1) {
                                $sheet->mergeCells('D' . $startcellno . ':E' . $startcellno);
                                $sheet->mergeCells('F' . $startcellno . ':G' . $startcellno);
                                $sheet->setCellValue('D' . $startcellno, $studentgrade->subjectgrades[0]->q1);
                                $sheet->setCellValue('F' . $startcellno, $studentgrade->subjectgrades[0]->q2);
                            } else {
                                $sheet->mergeCells('D' . $startcellno . ':E' . $startcellno);
                                $sheet->mergeCells('F' . $startcellno . ':G' . $startcellno);
                                $sheet->setCellValue('D' . $startcellno, $studentgrade->subjectgrades[0]->q3);
                                $sheet->setCellValue('F' . $startcellno, $studentgrade->subjectgrades[0]->q4);
                            }
                        } else {
                            $sheet->setCellValue('D' . $startcellno, $studentgrade->subjectgrades[0]->q1);
                            $sheet->setCellValue('E' . $startcellno, $studentgrade->subjectgrades[0]->q2);
                            $sheet->setCellValue('F' . $startcellno, $studentgrade->subjectgrades[0]->q3);
                            $sheet->setCellValue('G' . $startcellno, $studentgrade->subjectgrades[0]->q4);
                        }
                    }
                    $sheet->setCellValue('I' . $startcellno, $studentgrade->subjectgrades[0]->finalrating);
                    $sheet->setCellValue('J' . $startcellno, $studentgrade->subjectgrades[0]->actiontaken);

                    if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
                        $sheet->setCellValue('H' . $startcellno, '=IF(OR(D' . $startcellno . '="",F' . $startcellno . '=""),"",IF(ISERROR(ROUND(AVERAGE(D' . $startcellno . ',F' . $startcellno . '),2)),"",ROUND(AVERAGE(D' . $startcellno . ',F' . $startcellno . '),2)))');
                    } else {
                        $sheet->setCellValue('H' . $startcellno, '=IF(OR(D' . $startcellno . '="",E' . $startcellno . '="",F' . $startcellno . '="",G' . $startcellno . '=""),"",IF(ISERROR(ROUND(AVERAGE(D' . $startcellno . ',E' . $startcellno . ',F' . $startcellno . ',G' . $startcellno . '),2)),"",ROUND(AVERAGE(D' . $startcellno . ',E' . $startcellno . ',F' . $startcellno . ',G' . $startcellno . '),2)))');
                    }


                    $sheet->getStyle('D' . $startcellno . ':J' . $startcellno)->getAlignment()->setHorizontal('center');
                    $startcellno += 1;
                    $malecount += 1;
                }
            }
        }
        $sheet->mergeCells('B' . $startcellno . ':C' . $startcellno);
        $sheet->getStyle('B' . $startcellno . ':C' . $startcellno)->applyFromArray($borderstyle);
        $sheet->setCellValue('B' . $startcellno, 'FEMALE');
        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
            $sheet->mergeCells('D' . $startcellno . ':E' . $startcellno);
            $sheet->mergeCells('F' . $startcellno . ':G' . $startcellno);
        }


        $sheet->getStyle('A' . $startcellno . ':I' . $startcellno)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('DDDDDD');
        foreach (range('D', 'J') as $columnID) {
            $sheet->getStyle($columnID . $startcellno)->applyFromArray($borderstyle);
        }

        $startcellno += 1;
        if (count($studentgrades) > 0) {
            foreach ($studentgrades as $studentgrade) {
                if (strtolower($studentgrade->gender) == 'female') {
                    $sheet->mergeCells('B' . $startcellno . ':C' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, $femalecount);
                    $sheet->getStyle('A' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('B' . $startcellno, $studentgrade->student);
                    $sheet->getStyle('B' . $startcellno . ':C' . $startcellno)->applyFromArray($borderstyle);

                    foreach (range('D', 'J') as $columnID) {
                        $sheet->getStyle($columnID . $startcellno)->applyFromArray($borderstyle);
                    }

                    $complete = 0;
                    $totalgrade = 0;

                    if (count($studentgrade->subjectgrades) > 0) {
                        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
                            if ($request->get('semid') == 1) {
                                $sheet->mergeCells('D' . $startcellno . ':E' . $startcellno);
                                $sheet->mergeCells('F' . $startcellno . ':G' . $startcellno);
                                $sheet->setCellValue('D' . $startcellno, $studentgrade->subjectgrades[0]->q1);
                                $sheet->setCellValue('F' . $startcellno, $studentgrade->subjectgrades[0]->q2);
                            } else {
                                $sheet->mergeCells('D' . $startcellno . ':E' . $startcellno);
                                $sheet->mergeCells('F' . $startcellno . ':G' . $startcellno);
                                $sheet->setCellValue('D' . $startcellno, $studentgrade->subjectgrades[0]->q3);
                                $sheet->setCellValue('F' . $startcellno, $studentgrade->subjectgrades[0]->q4);
                            }
                        } else {
                            $sheet->setCellValue('D' . $startcellno, $studentgrade->subjectgrades[0]->q1);
                            $sheet->setCellValue('E' . $startcellno, $studentgrade->subjectgrades[0]->q2);
                            $sheet->setCellValue('F' . $startcellno, $studentgrade->subjectgrades[0]->q3);
                            $sheet->setCellValue('G' . $startcellno, $studentgrade->subjectgrades[0]->q4);
                        }
                    }
                    $sheet->setCellValue('I' . $startcellno, $studentgrade->subjectgrades[0]->finalrating);
                    $sheet->setCellValue('J' . $startcellno, $studentgrade->subjectgrades[0]->actiontaken);

                    if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
                        $sheet->setCellValue('H' . $startcellno, '=IF(OR(D' . $startcellno . '="",F' . $startcellno . '=""),"",IF(ISERROR(ROUND(AVERAGE(D' . $startcellno . ',F' . $startcellno . '),2)),"",ROUND(AVERAGE(D' . $startcellno . ',F' . $startcellno . '),2)))');
                    } else {
                        $sheet->setCellValue('H' . $startcellno, '=IF(OR(D' . $startcellno . '="",E' . $startcellno . '="",F' . $startcellno . '="",G' . $startcellno . '=""),"",IF(ISERROR(ROUND(AVERAGE(D' . $startcellno . ',E' . $startcellno . ',F' . $startcellno . ',G' . $startcellno . '),2)),"",ROUND(AVERAGE(D' . $startcellno . ',E' . $startcellno . ',F' . $startcellno . ',G' . $startcellno . '),2)))');
                    }



                    $sheet->getStyle('D' . $startcellno . ':J' . $startcellno)->getAlignment()->setHorizontal('center');
                    $startcellno += 1;
                    $femalecount += 1;
                }
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $schoolyear_detail = DB::table('sy')->where('id', $syid)->first();
        header('Content-Disposition: attachment; filename="GS_' . $subjectname . '_' . str_replace("GRADE ", "_G", $gradelevelinfo->levelname) . str_replace(" ", "_", $sectioninfo->sectionname) . '_SY' . $schoolyear_detail->sydesc . '_' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYYMMDDThhMMSS') . $version . '.xlsx"');
        $writer->save("php://output");
    }


    public function grade_status(Request $request)
    {
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $status = $request->get('status');

        if ($request->has('sy')) {
            $syid = $request->get('sy');
        } else {
            $syid = $request->get('syid');
        }

        $semid = $request->get('semid');


        $data = \App\Models\Grading\GradingReport::grade_report_gradelevel($syid, $semid, $gradelevel);

        $temp_data = array();

        foreach ($data as $item) {

            $q1_grade = 0;
            $q2_grade = 0;
            $q3_grade = 0;
            $q4_grade = 0;
            $final = 60;
            $remark = 0;

            foreach ($item->grades as $grade_item) {


                $q1_grade += $grade_item->q1;
                $q2_grade += $grade_item->q2;

                if ($gradelevel != 14 && $gradelevel != 15) {
                    $q3_grade += $grade_item->q3;
                    $q4_grade += $grade_item->q4;
                }



            }

            $q1_grade = $q1_grade / count($item->grades);
            $q2_grade = $q2_grade / count($item->grades);

            if ($gradelevel != 14 && $gradelevel != 15) {
                $final = (number_format($q1_grade) + number_format($q2_grade) + number_format($q3_grade) + number_format($q4_grade)) / 4;
                if (number_format($final) >= 75) {
                    $remark = 1;
                }
            } else {
                $final = (number_format($q1_grade) + number_format($q2_grade)) / 2;
                if (number_format($final) >= 75) {
                    $remark = 1;
                }
            }


            if ($status == 3 && $remark == 0) {

                array_push($temp_data, (object) [
                    'student' => $item->student,
                    'studid' => $item->studid,
                    'q1' => number_format($q1_grade),
                    'q2' => number_format($q2_grade),
                    'q3' => number_format($q3_grade),
                    'q4' => number_format($q4_grade),
                    'final' => number_format($final),
                    'remark' => $remark
                ]);
            } else if ($status == 2 && $remark == 1) {
                array_push($temp_data, (object) [
                    'student' => $item->student,
                    'studid' => $item->studid,
                    'q1' => number_format($q1_grade),
                    'q2' => number_format($q2_grade),
                    'q3' => number_format($q3_grade),
                    'q4' => number_format($q4_grade),
                    'final' => number_format($final),
                    'remark' => $remark
                ]);
            } else if ($status == 2) {
                array_push($temp_data, (object) [
                    'student' => $item->student,
                    'studid' => $item->studid,
                    'q1' => number_format($q1_grade),
                    'q2' => number_format($q2_grade),
                    'q3' => number_format($q3_grade),
                    'q4' => number_format($q4_grade),
                    'final' => number_format($final),
                    'remark' => $remark
                ]);
            }

        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        ;
        $sheet = $spreadsheet->getActiveSheet();
        $borderstyle = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $font_bold = [
            'font' => [
                'bold' => true,
            ]
        ];

        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'schoolinfo.picurl',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
            ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
            ->first();

        $gradelevelinfo = DB::table('gradelevel')
            ->select('gradelevel.*', 'academicprogram.acadprogcode')
            ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->where('gradelevel.id', $gradelevel)
            ->first();

        if (strtolower($gradelevelinfo->acadprogcode) == 'college') {

        } else {
            $sectioninfo = Db::table('sections')
                ->where('id', $section)
                ->first();
        }

        $schoolyearinfo = DB::table('sy')
            ->where('id', $syid)
            ->first();

        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->getColumnDimension('B')->setAutoSize(false);
        $sheet->getColumnDimension('B')->setWidth(15);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(base_path() . '/public/assets/images/department_of_Education.png');
        // $drawing->setPath(base_path().'/public/'.$schoolinfo->picurl);
        $drawing->setHeight(80);
        $drawing->setWorksheet($sheet);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(20);

        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);


        $sheet->mergeCells('D1:G2');
        $sheet->setCellValue('D1', 'Summary of Quarterly Grades');
        $sheet->getStyle('D1')->applyFromArray($font_bold);
        $sheet->getStyle('D1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('C4', 'REGION');
        $sheet->setCellValue('C5', 'SCHOOL NAME');

        $sheet->getStyle('C4:C5')->getAlignment()->setHorizontal('right');
        $sheet->setCellValue('D4', $schoolinfo->region);
        $sheet->getStyle('D4')->applyFromArray($borderstyle);
        $sheet->getStyle('D4')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('E4', 'DIVISION');
        $sheet->getStyle('E4')->getAlignment()->setHorizontal('right');
        $sheet->setCellValue('F4', $schoolinfo->division);
        $sheet->getStyle('F4')->applyFromArray($borderstyle);
        $sheet->getStyle('F4')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('H1:I1');
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(base_path() . '/public/assets/images/deped_logo.png');
        // $drawing->setPath(base_path().'/public/'.$schoolinfo->picurl);
        // $drawing->setHeight(50);
        $drawing->setWidth(110);
        $drawing->setWorksheet($sheet);
        $drawing->setCoordinates('H1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(15);

        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);



        $sheet->mergeCells('D5:F5');
        $sheet->setCellValue('D5', $schoolinfo->schoolname);
        $sheet->getStyle('D5:F5')->applyFromArray($borderstyle);
        $sheet->getStyle('D5')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('G5', 'SCHOOL ID');
        $sheet->getStyle('G5')->getAlignment()->setHorizontal('right');
        $sheet->mergeCells('H5:I5');
        $sheet->setCellValue('H5', $schoolinfo->schoolid);
        $sheet->getStyle('H5:I5')->applyFromArray($borderstyle);
        $sheet->getStyle('H5')->getAlignment()->setHorizontal('center');


        $sheet->mergeCells('A7:A9');
        $sheet->getStyle('A7:A9')->applyFromArray($borderstyle);


        $sheet->mergeCells('B7:C9');
        $sheet->getStyle('B7:C9')->applyFromArray($borderstyle);
        $sheet->getStyle('B7:C9')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B7:C9')->getAlignment()->setVertical('center');
        $sheet->setCellValue('B7', 'LEARNERS\' NAMES');



        $studentgrades = array();

        // if(count($data)>0)
        // {
        //     foreach($data as $eachdata)
        //     {
        //         if(strtolower($eachdata->student) != 'subjects')
        //         {
        //             $gender = Db::table('studinfo')
        //                 ->where('id', $eachdata->studid)
        //                 ->first()->gender;

        //             $eachdata->gender = $gender;
        //             // return $eachdata->grades;
        //             $filteredsubject = collect($eachdata->grades)->filter(function ($value, $key) use($subjid) {
        //                 if($value->subjid == $subjid)
        //                 {
        //                     return $value;
        //                 }
        //             })->values()->all();

        //             $eachdata->subjectgrades = $filteredsubject;

        //             if(count($eachdata->subjectgrades)>0)
        //             {
        //                 if($eachdata->subjectgrades[0]->teacherid != "")
        //                 {
        //                     $teachername = Db::table('teacher')
        //                         ->select('teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix')
        //                         ->where('id', $eachdata->subjectgrades[0]->teacherid)
        //                         ->first();

        //                     if($teachername->middlename == null)
        //                     {
        //                         $teachername = $teachername->lastname.', '.$teachername->firstname.' '.$teachername->suffix;
        //                     }else{
        //                         $teachername = $teachername->lastname.', '.$teachername->firstname.' '.$teachername->middlename[0].'. '.$teachername->suffix ;
        //                     }
        //                 }else{
        //                     $teachername = null;
        //                 }
        //                     $eachdata->subjectgrades[0]->teachername = $teachername;
        //             }

        //             $eachdata->subjectgrades = $filteredsubject;

        //             array_push($studentgrades, $eachdata);
        //         }
        //     }
        // }
        // return $studentgrades;

        foreach ($temp_data as $eachdata) {
            $gender = Db::table('studinfo')
                ->where('id', $eachdata->studid)
                ->select('gender')
                ->first()->gender;

            $eachdata->gender = $gender;

        }

        $sheet->mergeCells('D7:F7');
        $sheet->getStyle('D7:F7')->applyFromArray($borderstyle);
        $sheet->setCellValue('D7', 'GRADE LEVEL: ' . $gradelevelinfo->levelname);

        $sheet->mergeCells('G7:I7');
        $sheet->getStyle('G7:I7')->applyFromArray($borderstyle);
        $sheet->setCellValue('G7', 'SCHOOL YEAR: ' . $schoolyearinfo->sydesc);

        $sheet->mergeCells('D8:F8');
        $sheet->getStyle('D8:F8')->applyFromArray($borderstyle);

        if (count($studentgrades) > 0) {
            if (count($studentgrades[0]->subjectgrades) > 0) {
                $sheet->setCellValue('D8', '');
            } else {
                $sheet->setCellValue('D8', '');
            }

        } else {
            $sheet->setCellValue('D8', '');
        }
        $sheet->mergeCells('G8:I8');
        $sheet->getStyle('G8:I8')->applyFromArray($borderstyle);

        if (count($studentgrades) > 0) {
            if (count($studentgrades[0]->subjectgrades) > 0) {
                $subjectname = $studentgrades[0]->subjectgrades[0]->subjdesc;
                $sheet->setCellValue('G8', '');

            } else {
                $subjectname = "";
                $sheet->setCellValue('G8', '');

            }
        } else {
            $subjectname = "";
            $sheet->setCellValue('G8', '');
        }

        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
            $sheet->mergeCells('D9:E9');
            $sheet->setCellValue('D9', '1st Quarter');
            $sheet->getStyle('D9')->applyFromArray($borderstyle);


            $sheet->mergeCells('F9:G9');
            $sheet->setCellValue('F9', '2nd Quarter');
            $sheet->getStyle('F9')->applyFromArray($borderstyle);

        } else {
            $sheet->setCellValue('D9', '1st Quarter');
            $sheet->getStyle('D9')->applyFromArray($borderstyle);

            $sheet->setCellValue('E9', '2nd Quarter');
            $sheet->getStyle('E9')->applyFromArray($borderstyle);

            $sheet->setCellValue('F9', '3rd Quarter');
            $sheet->getStyle('F9')->applyFromArray($borderstyle);

            $sheet->setCellValue('G9', '4th Quarter');
            $sheet->getStyle('G9')->applyFromArray($borderstyle);
        }

        $sheet->setCellValue('H9', 'FINAL GRADE');
        $sheet->getStyle('H9')->applyFromArray($borderstyle);

        $sheet->setCellValue('I9', 'REMARKS');
        $sheet->getStyle('I9')->applyFromArray($borderstyle);

        $sheet->getStyle('D9:I9')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D7:I9')->getAlignment()->setVertical('center');


        $sheet->getStyle('A10:I10')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('DDDDDD');

        $studentgrades = collect($temp_data)->sortBy('student')->values()->all();
        // $sheet->setReadDataOnly(false);


        $startcellno = 11;
        $malecount = 1;
        $femalecount = 1;



        $sheet->mergeCells('B10:C10');
        $sheet->getStyle('B10:C10')->applyFromArray($borderstyle);
        $sheet->setCellValue('B10', 'MALE');

        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
            $sheet->mergeCells('D10:E10');
            $sheet->mergeCells('F10:G10');
        }

        foreach (range('D', 'I') as $columnID) {
            $sheet->getStyle($columnID . '10')->applyFromArray($borderstyle);
        }

        if (count($studentgrades) > 0) {
            foreach ($temp_data as $studentgrade) {
                if (strtolower($studentgrade->gender) == 'male') {
                    $sheet->mergeCells('B' . $startcellno . ':C' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, $malecount);
                    $sheet->getStyle('A' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('B' . $startcellno, $studentgrade->student);
                    $sheet->getStyle('B' . $startcellno . ':C' . $startcellno)->applyFromArray($borderstyle);

                    foreach (range('D', 'I') as $columnID) {
                        $sheet->getStyle($columnID . $startcellno)->applyFromArray($borderstyle);
                    }
                    $complete = 0;
                    $totalgrade = 0;
                    if (isset($studentgrade->q1)) {

                        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {

                            $sheet->mergeCells('D' . $startcellno . ':E' . $startcellno);
                            if (isset($studentgrade->q1)) {
                                $sheet->setCellValue('D' . $startcellno, $studentgrade->q1);
                            }
                            $sheet->mergeCells('F' . $startcellno . ':G' . $startcellno);
                            if (isset($studentgrade->q1)) {
                                $sheet->setCellValue('F' . $startcellno, $studentgrade->q2);
                            }
                        } else {
                            $sheet->setCellValue('D' . $startcellno, $studentgrade->q1);
                            $sheet->setCellValue('E' . $startcellno, $studentgrade->q2);
                            $sheet->setCellValue('F' . $startcellno, $studentgrade->q3);
                            $sheet->setCellValue('G' . $startcellno, $studentgrade->q4);
                        }

                        if (isset($studentgrade->q1)) {
                            if ($studentgrade->q1 != null) {
                                $totalgrade += $studentgrade->q1;
                                $complete += 1;
                            }
                        }
                        if (isset($studentgrade->q2)) {
                            if ($studentgrade->q2 != null) {
                                $totalgrade += $studentgrade->q2;
                                $complete += 1;
                            }
                        }
                        if (isset($studentgrade->q3)) {
                            if ($studentgrade->q3 != null) {
                                $totalgrade += $studentgrade->q3;
                                $complete += 1;
                            }
                        }
                        if (isset($studentgrade->q4)) {
                            if ($studentgrade->q4 != null) {
                                $totalgrade += $studentgrade->q4;
                                $complete += 1;
                            }
                        }
                    }

                    if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
                        // if($complete == 2)
                        // {
                        $sheet->setCellValue('H' . $startcellno, number_format($totalgrade / 2));
                        if (number_format($totalgrade / 2, 2) < 75) {
                            $remarks = 'FAILED';
                        } else {
                            $remarks = 'PASSED';
                        }
                        $sheet->setCellValue('I' . $startcellno, $remarks);
                        // }
                    } else {
                        // if($complete == 4)
                        // {
                        $sheet->setCellValue('H' . $startcellno, number_format($totalgrade / 4));
                        if (number_format($totalgrade / 4, 2) < 75) {
                            $remarks = 'FAILED';
                        } else {
                            $remarks = 'PASSED';
                        }
                        $sheet->setCellValue('I' . $startcellno, $remarks);
                        // }
                    }
                    $sheet->getStyle('D' . $startcellno . ':I' . $startcellno)->getAlignment()->setHorizontal('center');
                    $startcellno += 1;
                    $malecount += 1;
                }
            }
        }
        $sheet->mergeCells('B' . $startcellno . ':C' . $startcellno);
        $sheet->getStyle('B' . $startcellno . ':C' . $startcellno)->applyFromArray($borderstyle);
        $sheet->setCellValue('B' . $startcellno, 'FEMALE');
        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
            $sheet->mergeCells('D' . $startcellno . ':E' . $startcellno);
            $sheet->mergeCells('F' . $startcellno . ':G' . $startcellno);
        }


        $sheet->getStyle('A' . $startcellno . ':I' . $startcellno)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('DDDDDD');
        foreach (range('D', 'I') as $columnID) {
            $sheet->getStyle($columnID . $startcellno)->applyFromArray($borderstyle);
        }

        $startcellno += 1;
        if (count($studentgrades) > 0) {
            foreach ($studentgrades as $studentgrade) {
                if (strtolower($studentgrade->gender) == 'female') {
                    $sheet->mergeCells('B' . $startcellno . ':C' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, $femalecount);
                    $sheet->getStyle('A' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('B' . $startcellno, $studentgrade->student);
                    $sheet->getStyle('B' . $startcellno . ':C' . $startcellno)->applyFromArray($borderstyle);

                    foreach (range('D', 'I') as $columnID) {
                        $sheet->getStyle($columnID . $startcellno)->applyFromArray($borderstyle);
                    }

                    $complete = 0;
                    $totalgrade = 0;

                    if (isset($studentgrade->q1)) {

                        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
                            $sheet->mergeCells('D' . $startcellno . ':E' . $startcellno);
                            if (isset($studentgrade->q1)) {
                                $sheet->setCellValue('D' . $startcellno, $studentgrade->q1);
                            }
                            $sheet->mergeCells('F' . $startcellno . ':G' . $startcellno);
                            if (isset($studentgrade->q1)) {
                                $sheet->setCellValue('F' . $startcellno, $studentgrade->q2);
                            }
                        } else {
                            $sheet->setCellValue('D' . $startcellno, $studentgrade->q1);
                            $sheet->setCellValue('E' . $startcellno, $studentgrade->q2);
                            $sheet->setCellValue('F' . $startcellno, $studentgrade->q3);
                            $sheet->setCellValue('G' . $startcellno, $studentgrade->q4);
                        }
                        if (isset($studentgrade->q1)) {
                            if ($studentgrade->q1 != null) {
                                $totalgrade += $studentgrade->q1;
                                $complete += 1;
                            }
                        }
                        if (isset($studentgrade->q2)) {
                            if ($studentgrade->q2 != null) {
                                $totalgrade += $studentgrade->q2;
                                $complete += 1;
                            }
                        }
                        if (isset($studentgrade->q3)) {
                            if ($studentgrade->q3 != null) {
                                $totalgrade += $studentgrade->q3;
                                $complete += 1;
                            }
                        }
                        if (isset($studentgrade->q4)) {
                            if ($studentgrade->q4 != null) {
                                $totalgrade += $studentgrade->q4;
                                $complete += 1;
                            }
                        }
                    }

                    if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
                        // if($complete == 2)
                        // {
                        $sheet->setCellValue('H' . $startcellno, number_format($totalgrade / 2));
                        if (number_format($totalgrade / 2, 2) < 75) {
                            $remarks = 'FAILED';
                        } else {
                            $remarks = 'PASSED';
                        }
                        $sheet->setCellValue('I' . $startcellno, $remarks);
                        // }
                    } else {
                        // if($complete == 4)
                        // {
                        $sheet->setCellValue('H' . $startcellno, number_format($totalgrade / 4));
                        if (number_format($totalgrade / 4, 2) < 75) {
                            $remarks = 'FAILED';
                        } else {
                            $remarks = 'PASSED';
                        }
                        $sheet->setCellValue('I' . $startcellno, $remarks);
                        // }
                    }
                    $sheet->getStyle('D' . $startcellno . ':I' . $startcellno)->getAlignment()->setHorizontal('center');
                    $startcellno += 1;
                    $femalecount += 1;
                }
            }
        }

        $excel_status = 'ALL';

        if ($status == 2) {
            $excel_status = 'PASSED';
        } else if ($status == 3) {
            $excel_status = 'FAILED';
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . 'GRADE STATUS - ' . $gradelevelinfo->levelname . ' - ' . $excel_status . '.xlsx"');
        $writer->save("php://output");
    }


    public static function studentawardscertificate(Request $request)
    {

        $PHPWord = new \PhpOffice\PhpWord\PhpWord();

        $document = $PHPWord->loadTemplate('2ND-QUARTER-CERTIFICATE-1.docx');


        $document->cloneBlock('blockname1', 3, true, false);

        $file = 'testFile.docx';

        $document->saveAs("Result.docx");

        $file_url = 'Result.docx';
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: utf-8");
        header("Content-disposition: attachment; filename=Result.docx");
        readfile($file_url);

        exit();

        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $syid = $request->get('sy');
        $semid = $request->get('semid');
        $strandid = $request->get('strand');
        $quarter = $request->get('quarter');

        $request->request->remove('semid');

        $quartertext = '';
        $awardtext = '';

        if ($quarter == 1) {
            $quartertext = '1st Quarter';
            $q1award = 'q1award';
        } else if ($quarter == 2) {
            $quartertext = '2nd Quarter';
            $q1award = 'q1award';
        } else if ($quarter == 3) {
            $quartertext = '3rd Quarter';
            $q1award = 'q1award';
        } else if ($quarter == 4) {
            $quartertext = '4th Quarter';
            $q1award = 'q1award';
        }

        if ($section == null) {
            $sections = DB::table('sections')
                ->where('sections.id', $section)
                ->where('sections.deleted', 0)
                ->join('gradelevel', function ($join) {
                    $join->on('sections.levelid', '=', 'gradelevel.id');
                    $join->where('gradelevel.deleted', 0);
                })
                ->select(
                    'sections.id',
                    'sectionname',
                    'levelname',
                    'acadprogid'
                )
                ->first();

            $gradelevel_students = array();
            foreach ($sections as $section_item) {
                $request->request->add(['section' => $section_item->id]);
                $temp_students = \App\Http\Controllers\TeacherControllers\TeacherGradingV4::get_student_data($request);
                foreach ($temp_students as $student) {
                    $student->sectionname = $section_item->sectionname;
                    array_push($gradelevel_students, $student);
                }
            }
            $students = collect($gradelevel_students);
        } else {
            $sections = DB::table('sections')
                ->where('sections.id', $section)
                ->where('sections.deleted', 0)
                ->join('gradelevel', function ($join) {
                    $join->on('sections.levelid', '=', 'gradelevel.id');
                    $join->where('gradelevel.deleted', 0);
                })
                ->select(
                    'sections.id',
                    'sectionname',
                    'levelname',
                    'acadprogid'
                )
                ->first();

            $students = \App\Http\Controllers\TeacherControllers\TeacherGradingV4::get_student_data($request);
            if ($strandid != null) {
                $gradelevel_students = array();
                $temp_students = array();
                foreach ($students as $student) {
                    if (isset($student->strand)) {
                        if ($strandid == $student->strand) {
                            $student->sectionname = $sections->sectionname;
                            array_push($gradelevel_students, $student);
                            array_push($temp_students, $student);
                        }
                    }
                }
                $students = $temp_students;
            } else {
                foreach ($students as $student) {
                    $student->sectionname = $sections->sectionname;
                }
            }
        }

        $sectioninfo = DB::table('sectiondetail')
            ->where('sectionid', $section)
            ->where('syid', $syid)
            ->join('teacher', function ($join) {
                $join->on('sectiondetail.teacherid', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->select(
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'teacherid',
                'title',
                'acadtitle'
            )
            ->get();

        $adviser = '';
        $teacherid = null;

        foreach ($sectioninfo as $item) {
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            $temp_acadtitle = '';
            if (isset($item->middlename)) {
                $temp_middle = $item->middlename[0] . '.';
            }
            if (isset($item->title)) {
                $temp_title = $item->title . '. ';
            }
            if (isset($item->suffix)) {
                $temp_suffix = ', ' . $item->suffix;
            }
            if (isset($item->acadtitle)) {
                $temp_acadtitle = ', ' . $item->acadtitle;
            }
            $adviser = $temp_title . $item->firstname . ' ' . $temp_middle . ' ' . $item->lastname . $temp_suffix . $temp_acadtitle;
            $teacherid = $item->teacherid;
            $item->checked = 0;

        }

        $acad = $sections->acadprogid;
        $principal_info = array(
            (object) [
                'name' => null,
                'title' => null
            ]
        );

        $signatory = DB::table('signatory')
            ->where('form', 'report_card')
            ->where('syid', $syid)
            ->where('acadprogid', $acad)
            ->where('deleted', 0)
            ->select(
                'name',
                'title'
            )
            ->first();

        if (isset($signatory->name) || isset($signatory->title)) {
            $principal_info[0]->name = $signatory->name;
            $principal_info[0]->title = $signatory->title;
        }

        $students = collect($students)->where('student', '!=', 'SUBJECTS')->values();
        $sy = DB::table('sy')->where('id', $syid)->first();

        $pdf = PDF::loadView('principalsportal.pages.awards.cert2nd', compact('q1award', 'principal_info', 'sy', 'quartertext', 'students', 'sections', 'adviser'))->setPaper('8.5X11');
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
        return $pdf->stream();

        return $students;

        return "sdfsf";

    }

    function studentawards(Request $request)
    {

        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $syid = $request->get('sy');
        $semid = $request->get('semid');
        $strandid = $request->get('strand');
        $quarter = $request->get('quarter');
        $exclude = explode(",", $request->get('exclude'));

        $students = \App\Http\Controllers\PrincipalControllers\PrincipalController::searchStudentWithHonors($request);

        if ($section == null) {
            $sections = (object) [
                'sectionname' => null
            ];
        } else {
            $sections = DB::table('sections')
                ->where('id', $section)
                ->where('deleted', 0)
                ->select(
                    'id',
                    'sectionname'
                )
                ->first();
        }

        $schoolinfo = Db::table('schoolinfo')->first();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("Student_Ranking.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $gradelevelinfo = DB::table('gradelevel')
            ->where('gradelevel.id', $gradelevel)
            ->first();

        $schoolyearinfo = DB::table('sy')
            ->where('id', $syid)
            ->first();

        $sheet->setCellValue('A1', $schoolinfo->schoolname);
        $sheet->setCellValue('A3', $schoolinfo->address);
        $sheet->setCellValue('A4', $schoolyearinfo->sydesc);
        $sheet->setCellValue('A5', $gradelevelinfo->levelname . ' - ' . $sections->sectionname);
        $malecount = 1;
        $startcellno = 10;

        if (count($students) > 0) {

            if ($gradelevel == 14 || $gradelevel == 15) {
                $students = collect($students)->where('student', '!=', 'SUBJECTS')->where('semid', $semid)->values();
            } else {
                $students = collect($students)->where('student', '!=', 'SUBJECTS')->values();
            }

            foreach (collect($students)->sortBy('rank')->values() as $studentgrade) {
                $sheet->setCellValue('A' . $startcellno, $malecount);
                $sheet->setCellValue('B' . $startcellno, $studentgrade->student);
                $sheet->setCellValue('D' . $startcellno, $studentgrade->sectionname);
                $sheet->setCellValue('E' . $startcellno, $studentgrade->quarter_award->genave);
                $sheet->setCellValue('F' . $startcellno, $studentgrade->quarter_award->temp_comp);
                $sheet->setCellValue('G' . $startcellno, $studentgrade->quarter_award->award);
                $sheet->setCellValue('I' . $startcellno, $studentgrade->rank);
                $sheet->setCellValue('J' . $startcellno, $studentgrade->quarter_award->lowest);
                $startcellno += 1;
                $malecount += 1;
            }
        }

        $version = "V5";

        try {
            ob_end_clean();
        } catch (\Exception $e) {
        }


        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . 'SR ' . str_replace("GRADE ", "_G", $gradelevelinfo->levelname) . str_replace(" ", "_", $sections->sectionname) . '_SY' . $schoolyearinfo->sydesc . '_' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYYMMDDThhMMSS') . $version . '.xlsx');
        $writer->save("php://output");
    }

    public function finalcomposite(Request $request)
    {

        // return "This page is not yet available";

        $semid = $request->get('semid');
        $syid = $request->get('sy');
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $quarter = $request->get('quarter');
        $strandid = $request->get('strand');


        $students = DB::table('sh_enrolledstud')
            ->where('sh_enrolledstud.levelid', $gradelevel)
            ->where('sh_enrolledstud.sectionid', $section)
            ->where('sh_enrolledstud.syid', $syid)
            ->where('sh_enrolledstud.deleted', 0)
            ->join('studinfo', function ($join) {
                $join->on('sh_enrolledstud.studid', '=', 'studinfo.id');
                $join->where('studinfo.deleted', 0);
            })
            ->orderBy('gender', 'desc')
            ->orderBy('lastname')
            ->orderBy('studentname', 'asc')
            ->select(
                'sh_enrolledstud.sectionid as ensectid',
                'sh_enrolledstud.studid as id',
                'sh_enrolledstud.strandid',
                'sh_enrolledstud.semid',
                'lastname',
                'firstname',
                'middlename',
                'lrn',
                'dob',
                'gender',
                'studid',
                'sh_enrolledstud.levelid',
                'sh_enrolledstud.syid',
                'sh_enrolledstud.strandid',
                'sh_enrolledstud.sectionid',
                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as student"),
                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
            )
            ->get();

        $students = collect($students)->unique('studid')->values();


        foreach ($students as $item) {

            $enrollment = DB::table('sh_enrolledstud')

                ->join('gradelevel', function ($join) {
                    $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id');
                    $join->where('gradelevel.deleted', 0);
                })
                ->join('sections', function ($join) {
                    $join->on('sh_enrolledstud.sectionid', '=', 'sections.id');
                    $join->where('sections.deleted', 0);
                })
                ->join('studinfo', function ($join) {
                    $join->on('sh_enrolledstud.studid', '=', 'studinfo.id');
                    $join->where('gradelevel.deleted', 0);
                })
                ->where('sh_enrolledstud.studid', $item->studid)
                ->where('sh_enrolledstud.deleted', 0)
                ->select(
                    'sh_enrolledstud.sectionid as ensectid',
                    'acadprogid',
                    'sh_enrolledstud.studid as id',
                    'sh_enrolledstud.strandid',
                    'sh_enrolledstud.semid',
                    'lastname',
                    'firstname',
                    'middlename',
                    'lrn',
                    'dob',
                    'gender',
                    'levelname',
                    'sh_enrolledstud.levelid',
                    'sh_enrolledstud.syid',
                    'sh_enrolledstud.strandid',
                    'sh_enrolledstud.sectionid',
                    'sections.sectionname as ensectname'
                )
                // ->distinct('sh_enrolledstud.syid')
                ->get();

            $enrollment = collect($enrollment)->unique('syid')->values();

            foreach ($enrollment as $enitem) {


                $enlevel = $enitem->levelid;
                $studid = $item->studid;
                $ensyid = $enitem->syid;
                $enstrand = $enitem->strandid;
                $ensection = $enitem->sectionid;

                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($enlevel, $studid, $ensyid, $enstrand, null, $ensection);
                $enitem->grades = collect($studgrades)->where('subjid', 'G1')->values();

            }

            $item->enrollment = $enrollment;


        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        ;
        $sheet = $spreadsheet->getActiveSheet();
        $borderstyle = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $font_bold = [
            'font' => [
                'bold' => true,
            ]
        ];

        $schoolinfo = Db::table('schoolinfo')->first();

        $gradelevelinfo = DB::table('gradelevel')
            ->select('gradelevel.*', 'academicprogram.acadprogcode')
            ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->where('gradelevel.id', $gradelevel)
            ->first();

        if (strtolower($gradelevelinfo->acadprogcode) == 'college') {

        } else {
            $sectioninfo = Db::table('sections')
                ->where('id', $section)
                ->first();
        }

        $schoolyearinfo = DB::table('sy')
            ->where('id', $syid)
            ->first();

        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }


        foreach (range('D', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(false);
            $sheet->getColumnDimension($columnID)->setWidth(18);
        }

        $sheet->getColumnDimension('B')->setAutoSize(false);
        $sheet->getColumnDimension('B')->setWidth(15);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(base_path() . '/public/assets/images/department_of_Education.png');
        // $drawing->setPath(base_path().'/public/'.$schoolinfo->picurl);
        $drawing->setHeight(80);
        $drawing->setWorksheet($sheet);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(20);

        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);


        $sheet->mergeCells('D1:G2');
        $sheet->setCellValue('D1', 'Grading Sheet');
        $sheet->getStyle('D1')->applyFromArray($font_bold);
        $sheet->getStyle('D1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('C4', 'REGION');
        $sheet->setCellValue('C5', 'SCHOOL NAME');

        $sheet->getStyle('C4:C5')->getAlignment()->setHorizontal('right');
        $sheet->setCellValue('D4', $schoolinfo->regiontext);
        $sheet->getStyle('D4')->applyFromArray($borderstyle);
        $sheet->getStyle('D4')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('E4', 'DIVISION');
        $sheet->getStyle('E4')->getAlignment()->setHorizontal('right');
        $sheet->setCellValue('F4', $schoolinfo->divisiontext);
        $sheet->getStyle('F4')->applyFromArray($borderstyle);
        $sheet->getStyle('F4')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('H1:I1');
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(base_path() . '/public/assets/images/deped_logo.png');

        $drawing->setWidth(110);
        $drawing->setWorksheet($sheet);
        $drawing->setCoordinates('H1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(15);

        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);

        $sheet->mergeCells('D5:F5');
        $sheet->setCellValue('D5', $schoolinfo->schoolname);
        $sheet->getStyle('D5:F5')->applyFromArray($borderstyle);
        $sheet->getStyle('D5')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('G5', 'SCHOOL ID');
        $sheet->getStyle('G5')->getAlignment()->setHorizontal('right');
        $sheet->mergeCells('H5:I5');
        $sheet->setCellValue('H5', $schoolinfo->schoolid);
        $sheet->getStyle('H5:I5')->applyFromArray($borderstyle);
        $sheet->getStyle('H5')->getAlignment()->setHorizontal('center');


        $sheet->mergeCells('A7:A10');
        $sheet->getStyle('A7:A10')->applyFromArray($borderstyle);


        $sheet->mergeCells('B7:C10');
        $sheet->getStyle('B7:C10')->applyFromArray($borderstyle);
        $sheet->getStyle('B7:C10')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B7:C10')->getAlignment()->setVertical('center');
        $sheet->setCellValue('B7', 'LEARNERS\' NAMES');

        $sheet->mergeCells('D7:F7');
        $sheet->getStyle('D7:F7')->applyFromArray($borderstyle);
        $sheet->setCellValue('D7', 'GRADE & SECTION: ' . $gradelevelinfo->levelname . ' - ' . $sectioninfo->sectionname);

        $sheet->mergeCells('G7:I7');
        $sheet->getStyle('G7:I7')->applyFromArray($borderstyle);
        $sheet->setCellValue('G7', 'SCHOOL YEAR: ' . $schoolyearinfo->sydesc);

        $sheet->mergeCells('D8:F8');
        $sheet->getStyle('D8:F8')->applyFromArray($borderstyle);

        $studentgrades = array();


        $adviserdetail = DB::table('sectiondetail')
            ->where('sectionid', $section)
            ->where('syid', $syid)
            ->join('teacher', function ($join) {
                $join->on('sectiondetail.teacherid', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->select(
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'teacherid',
                'title',
                'acadtitle'
            )
            ->get();

        $adviser = '';
        $teacherid = null;

        foreach ($adviserdetail as $item) {
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            $temp_acadtitle = '';
            if (isset($item->middlename)) {
                $temp_middle = $item->middlename[0] . '.';
            }
            if (isset($item->title)) {
                $temp_title = $item->title . '. ';
            }
            if (isset($item->suffix)) {
                $temp_suffix = ', ' . $item->suffix;
            }
            if (isset($item->acadtitle)) {
                $temp_acadtitle = ', ' . $item->acadtitle;
            }
            $adviser = $temp_title . $item->firstname . ' ' . $temp_middle . ' ' . $item->lastname . $temp_suffix . $temp_acadtitle;

        }

        $sheet->setCellValue('D8', 'Adviser: ' . $adviser);

        $sheet->mergeCells('G8:I8');
        $sheet->getStyle('G8:I8')->applyFromArray($borderstyle);

        $startcellno = 9;

        $sheet->mergeCells('D' . $startcellno . ':F' . $startcellno);
        $sheet->mergeCells('G' . $startcellno . ':I' . $startcellno);

        $sheet->getStyle('D' . $startcellno . ':I' . $startcellno)->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('D' . $startcellno, 'Grade 11');
        $sheet->setCellValue('G' . $startcellno, 'Grade 12');

        $startcellno = 10;

        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
            $sheet->setCellValue('D' . $startcellno, 'First Semester');
            $sheet->getStyle('D' . $startcellno)->applyFromArray($borderstyle);

            $sheet->setCellValue('E' . $startcellno, 'Second Semester');
            $sheet->getStyle('E' . $startcellno)->applyFromArray($borderstyle);

            $sheet->setCellValue('F' . $startcellno, 'First Semester');
            $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);

            $sheet->setCellValue('G' . $startcellno, 'Second Semester');
            $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);

        } else {
            $sheet->setCellValue('D' . $startcellno, '1st Quarter');
            $sheet->getStyle('D' . $startcellno)->applyFromArray($borderstyle);

            $sheet->setCellValue('E' . $startcellno, '2nd Quarter');
            $sheet->getStyle('E' . $startcellno)->applyFromArray($borderstyle);

            $sheet->setCellValue('F' . $startcellno, '3rd Quarter');
            $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);

            $sheet->setCellValue('G' . $startcellno, '4th Quarter');
            $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
        }

        $sheet->setCellValue('H' . $startcellno, 'FINAL GRADE');
        $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);

        $sheet->setCellValue('I' . $startcellno, 'COMPOSITE');
        $sheet->getStyle('I' . $startcellno)->applyFromArray($borderstyle);

        $sheet->getStyle('D' . $startcellno . ':I' . $startcellno)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D' . $startcellno . ':I' . $startcellno)->getAlignment()->setVertical('center');





        $startcellno += 1;
        $malecount = 1;
        $femalecount = 1;

        $sheet->getStyle('A' . $startcellno . ':I' . $startcellno)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('DDDDDD');


        $sheet->mergeCells('B' . $startcellno . ':C' . $startcellno);
        $sheet->getStyle('B' . $startcellno . ':C' . $startcellno)->applyFromArray($borderstyle);
        $sheet->setCellValue('B' . $startcellno, 'MALE');

        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
            $sheet->mergeCells('D' . $startcellno . ':E' . $startcellno);
            $sheet->mergeCells('F' . $startcellno . ':G' . $startcellno);
        }

        foreach (range('D', 'I') as $columnID) {
            $sheet->getStyle($columnID . $startcellno)->applyFromArray($borderstyle);
        }


        $studentgrades = collect($studentgrades)->sortBy('student')->values()->all();
        $students = collect($students)->where('student', '!=', 'SUBJECTS')->values();
        $startcellno += 1;

        if (count($students) > 0) {
            foreach ($students as $studentgrade) {

                if (strtolower($studentgrade->gender) == 'male') {


                    $sheet->mergeCells('B' . $startcellno . ':C' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, $malecount);
                    $sheet->getStyle('A' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('B' . $startcellno, $studentgrade->student);
                    $sheet->getStyle('B' . $startcellno . ':C' . $startcellno)->applyFromArray($borderstyle);

                    foreach (range('D', 'I') as $columnID) {
                        $sheet->getStyle($columnID . $startcellno)->applyFromArray($borderstyle);
                    }

                    $complete = 0;
                    $totalgrade = 0;


                    $student_grade = DB::table('sf10')
                        ->join('sf10grades_senior', function ($join) {
                            $join->on('sf10.id', '=', 'sf10grades_senior.headerid');
                            $join->where('sf10grades_senior.deleted', 0);
                        })
                        ->where('sf10.studid', $studentgrade->studid)
                        ->where('sf10grades_senior.deleted', 0)
                        ->select('semid', 'q1', 'q2', 'q3', 'q4')
                        ->get();



                    $q1 = collect($students)->where('studid', $studentgrade->studid)->values();
                    $q2 = collect($students)->where('studid', $studentgrade->studid)->values();

                    $compq1 = 0;
                    $compq2 = 0;
                    $compq3 = 0;
                    $compq4 = 0;

                    $fincomp_grade = 0;
                    foreach ($studentgrade->enrollment as $enitem) {
                        foreach ($enitem->grades as $entimgrade) {
                            $fincomp_grade += $entimgrade->finalrating;
                        }
                    }

                    //grade 11 enrollment
                    $g11_enrollment = collect($studentgrade->enrollment)->where('levelid', 14)->values();

                    foreach ($g11_enrollment as $en_item) {
                        foreach ($en_item->grades as $grade_item) {

                            if ($grade_item->semid == 1) {
                                $sheet->setCellValue('D' . $startcellno, $grade_item->finalrating);
                            } else {
                                $sheet->setCellValue('E' . $startcellno, $grade_item->finalrating);
                            }
                        }
                    }

                    //grade 12 enrollment
                    $g12_enrollment = collect($studentgrade->enrollment)->where('levelid', 15)->values();

                    foreach ($g12_enrollment as $en_item) {
                        foreach ($en_item->grades as $grade_item) {

                            if ($grade_item->semid == 1) {
                                $sheet->setCellValue('F' . $startcellno, $grade_item->finalrating);
                            } else {
                                $sheet->setCellValue('G' . $startcellno, $grade_item->finalrating);
                            }
                        }
                    }

                    $sheet->setCellValue('H' . $startcellno, number_format(($fincomp_grade) / 4));
                    $sheet->setCellValue('I' . $startcellno, number_format((($fincomp_grade) / 4), 3));

                    $sheet->getStyle('D' . $startcellno . ':I' . $startcellno)->getAlignment()->setHorizontal('center');
                    $startcellno += 1;
                    $malecount += 1;

                }
            }
        }

        $sheet->mergeCells('B' . $startcellno . ':C' . $startcellno);
        $sheet->getStyle('B' . $startcellno . ':C' . $startcellno)->applyFromArray($borderstyle);
        $sheet->setCellValue('B' . $startcellno, 'FEMALE');

        if (strtolower($gradelevelinfo->acadprogcode) == 'shs') {
            $sheet->mergeCells('D' . $startcellno . ':E' . $startcellno);
            $sheet->mergeCells('F' . $startcellno . ':G' . $startcellno);
        }

        $sheet->getStyle('A' . $startcellno . ':I' . $startcellno)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('DDDDDD');
        foreach (range('D', 'I') as $columnID) {
            $sheet->getStyle($columnID . $startcellno)->applyFromArray($borderstyle);
        }

        $startcellno += 1;

        $students = collect($students)->where('student', '!=', 'SUBJECTS')->values();

        if (count($students) > 0) {
            foreach ($students as $studentgrade) {


                if (strtolower($studentgrade->gender) == 'female') {

                    $sheet->mergeCells('B' . $startcellno . ':C' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, $malecount);
                    $sheet->getStyle('A' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('B' . $startcellno, $studentgrade->student);
                    $sheet->getStyle('B' . $startcellno . ':C' . $startcellno)->applyFromArray($borderstyle);

                    foreach (range('D', 'I') as $columnID) {
                        $sheet->getStyle($columnID . $startcellno)->applyFromArray($borderstyle);
                    }

                    $complete = 0;
                    $totalgrade = 0;

                    $student_grade = DB::table('sf10')
                        ->join('sf10grades_senior', function ($join) {
                            $join->on('sf10.id', '=', 'sf10grades_senior.headerid');
                            $join->where('sf10grades_senior.deleted', 0);
                        })
                        ->where('sf10.studid', $studentgrade->studid)
                        ->where('sf10grades_senior.deleted', 0)
                        ->select('semid', 'q1', 'q2', 'q3', 'q4')
                        ->get();



                    $q1 = collect($students)->where('studid', $studentgrade->studid)->values();
                    $q2 = collect($students)->where('studid', $studentgrade->studid)->values();

                    $compq1 = 0;
                    $compq2 = 0;
                    $compq3 = 0;
                    $compq4 = 0;

                    $fincomp_grade = 0;
                    foreach ($studentgrade->enrollment as $enitem) {
                        foreach ($enitem->grades as $entimgrade) {
                            $fincomp_grade += $entimgrade->finalrating;
                        }
                    }

                    //grade 11 enrollment
                    $g11_enrollment = collect($studentgrade->enrollment)->where('levelid', 14)->values();

                    foreach ($g11_enrollment as $en_item) {
                        foreach ($en_item->grades as $grade_item) {

                            if ($grade_item->semid == 1) {
                                $sheet->setCellValue('D' . $startcellno, $grade_item->finalrating);
                            } else {
                                $sheet->setCellValue('E' . $startcellno, $grade_item->finalrating);
                            }
                        }
                    }

                    //grade 12 enrollment
                    $g12_enrollment = collect($studentgrade->enrollment)->where('levelid', 15)->values();

                    foreach ($g12_enrollment as $en_item) {
                        foreach ($en_item->grades as $grade_item) {

                            if ($grade_item->semid == 1) {
                                $sheet->setCellValue('F' . $startcellno, $grade_item->finalrating);
                            } else {
                                $sheet->setCellValue('G' . $startcellno, $grade_item->finalrating);
                            }
                        }
                    }

                    $sheet->setCellValue('H' . $startcellno, number_format(($fincomp_grade) / 4));
                    $sheet->setCellValue('I' . $startcellno, number_format((($fincomp_grade) / 4), 3));

                    $sheet->getStyle('D' . $startcellno . ':I' . $startcellno)->getAlignment()->setHorizontal('center');
                    $startcellno += 1;
                    $malecount += 1;
                }
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="FG_' . $schoolyearinfo->sydesc . '_' . $gradelevelinfo->levelname . ' ' . $sectioninfo->sectionname . '.xlsx"');
        $writer->save("php://output");
    }

    public static function generateGSA(Request $request)
    {

        $activesem = $request->get('semid');
        $activesy = $request->get('sy');
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $quarter = $request->get('quarter');
        $strandid = $request->get('strand');

        $levelname = DB::table('gradelevel')
            ->where('id', $gradelevel)
            ->first();

        $quarterdesc = '';

        $principal_info = array(
            (object) [
                'name' => null,
                'title' => null
            ]
        );

        $signatory = DB::table('signatory')
            ->where('form', 'report_card')
            ->where('syid', $activesy)
            ->where('acadprogid', $levelname->acadprogid)
            ->where('deleted', 0)
            ->select(
                'name',
                'title'
            )
            ->first();

        if (isset($signatory->name) || isset($signatory->title)) {
            $principal_info[0]->name = $signatory->name;
            $principal_info[0]->title = $signatory->title;
        }


        $sectioninfo = DB::table('sectiondetail')
            ->where('sectionid', $section)
            ->where('syid', $activesy)
            ->where('sectiondetail.deleted', 0)
            ->join('teacher', function ($join) {
                $join->on('sectiondetail.teacherid', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->select(
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'teacherid',
                'title',
                'acadtitle'
            )
            ->get();

        $adviser = '';

        foreach ($sectioninfo as $item) {
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            $temp_acadtitle = '';
            if (isset($item->middlename)) {
                $temp_middle = $item->middlename[0] . '.';
            }
            if (isset($item->title)) {
                $temp_title = $item->title . '. ';
            }
            if (isset($item->suffix)) {
                $temp_suffix = ', ' . $item->suffix;
            }
            if (isset($item->acadtitle)) {
                $temp_acadtitle = ', ' . $item->acadtitle;
            }
            $adviser = $temp_title . $item->firstname . ' ' . $temp_middle . ' ' . $item->lastname . $temp_suffix . $temp_acadtitle;
        }

        if ($quarter == 1) {
            $quarterdesc = '1st Quarter';
        } else if ($quarter == 2) {
            $quarterdesc = '2nd Quarter';
        } else if ($quarter == 3) {
            $quarterdesc = '3rd Quarter';
        } else if ($quarter == 4) {
            $quarterdesc = '4th Quarter';
        }

        $schoolinfo = DB::table('schoolinfo')->first();

        $sy = DB::table('sy')
            ->where('id', $activesy)
            ->first();

        if ($gradelevel == 14 || $gradelevel == 15) {

            $students = DB::table('sh_enrolledstud')
                ->join('studinfo', function ($join) {
                    $join->on('sh_enrolledstud.studid', '=', 'studinfo.id');
                    $join->where('studinfo.deleted', 0);
                })->select(
                    'sh_enrolledstud.syid',
                    'sh_enrolledstud.syid',
                    'sh_enrolledstud.levelid',
                    'sh_enrolledstud.sectionid',
                    'firstname',
                    'lastname',
                    'gender',
                    'middlename',
                    'studid',
                    'gender',
                    'suffix',
                    'sid',
                    'sh_enrolledstud.strandid',
                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                )
                ->whereIn('sh_enrolledstud.studstatus', [1, 2, 4])
                ->where('sh_enrolledstud.deleted', 0)
                ->where('sh_enrolledstud.semid', $activesem)
                ->where('sh_enrolledstud.syid', $activesy)
                ->where('sh_enrolledstud.levelid', $gradelevel)
                ->orderBy('gender', 'desc')
                ->orderBy('lastname')
                // ->take(10)
                ->get();


            $students = collect($students)->unique('studid')->values();

            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects_sh($gradelevel, null, $activesem, $activesy);


            foreach ($subjects as $item) {
                $item->grades = array();
            }

            $subjects = collect($subjects)->unique('subjid')->values();

            foreach ($students as $item) {

                $grades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $item->studid, $activesy, $item->strandid, $activesem, $item->sectionid, true);

                foreach ($subjects as $subjitem) {

                    $tempgrades = $subjitem->grades;
                    $filteredstudentgrades = collect($grades)->where('subjid', $subjitem->subjid)->values();

                    foreach ($filteredstudentgrades as $filtereditem) {
                        $filtereditem->student = $item->studentname;
                        array_push($tempgrades, $filtereditem);
                    }
                    $subjitem->grades = $tempgrades;
                }


            }



            $sheetCount = 0;


            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A6', $levelname->levelname . ' - ' . $quarterdesc);

            foreach ($subjects as $item) {

                $clonedWorksheet = clone $spreadsheet->getSheet(0);
                $clonedWorksheet->setTitle($item->subjcode);
                $spreadsheet->addSheet($clonedWorksheet);

                $sheet = $spreadsheet->getSheetByName($item->subjcode);

                $sheet->setCellValue('A6', $levelname->levelname . ' - ' . $quarterdesc);


                $sheet->setCellValue('A2', $schoolinfo->address);
                $sheet->setCellValue('A1', $schoolinfo->schoolname);
                $sheet->setCellValue('A5', 'SCHOOL YEAR - ' . $sy->sydesc);



                $sheet->setCellValue('C8', $item->subjdesc);



                $formula = '=SUM(C10:C' . (count($item->grades) + 9) . ')/' . count($item->grades);
                $sheet->setCellValue('C9', $formula);
                $studentcol = 10;

                foreach ($item->grades as $gradeitem) {

                    $sheet->setCellValue('A' . $studentcol, $studentcol - 9);
                    $sheet->setCellValue('B' . $studentcol, $gradeitem->student);
                    $temp_qg = 'q' . $quarter;
                    $sheet->setCellValue('C' . $studentcol, $gradeitem->$temp_qg);
                    $studentcol += 1;

                }

                $sheet->removeRow($studentcol, 500 - $studentcol);

            }


            $sheet->getStyle('B' . ($studentcol + 4))->getAlignment()->setHorizontal('center');
            $sheet->getStyle('B' . ($studentcol + 5))->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('B' . ($studentcol + 4), $adviser);
            $sheet->setCellValue('B' . ($studentcol + 5), "Class Adviser");

            // $sheetIndex = $spreadsheet->getIndex(
            //     $spreadsheet->getSheetByName('Sheet1') ?? null
            // );

            // $spreadsheet->removeSheetByIndex($sheetIndex);
            // $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->setActiveSheetIndex(0);


            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $levelname->levelname . ' - ' . $quarterdesc . '.xlsx"');
            $writer->save("php://output");
            exit();


        }

        $students = DB::table('enrolledstud')
            ->join('studinfo', function ($join) {
                $join->on('enrolledstud.studid', '=', 'studinfo.id');
                $join->where('studinfo.deleted', 0);
            })->select(
                'enrolledstud.syid',
                'enrolledstud.syid',
                'enrolledstud.levelid',
                'enrolledstud.sectionid',
                'firstname',
                'lastname',
                'gender',
                'middlename',
                'studid',
                'gender',
                'suffix',
                'sid',
                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
            )
            ->whereIn('enrolledstud.studstatus', [1, 2, 4])
            ->where('enrolledstud.deleted', 0)
            ->where('enrolledstud.syid', $activesy)
            ->whereIn('enrolledstud.levelid', [10, 11, 12, 13])
            ->orderBy('gender', 'desc')
            ->orderBy('lastname')
            ->get();

        foreach ($students as $item) {
            $grades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $item->studid, $activesy, null, $activesem, $item->sectionid);

            $item->grades = $grades;
        }





        $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel, $activesy);

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();


        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A6', $levelname->levelname . ' - ' . $quarterdesc);

        $sheet->setCellValue('A2', $schoolinfo->address);
        $sheet->setCellValue('A1', $schoolinfo->schoolname);
        $sheet->setCellValue('A5', 'SCHOOL YEAR - ' . $sy->sydesc);

        $sheet->setTitle($levelname->levelname);



        $letterval = 67;
        foreach ($subjects as $item) {
            $sheet->setCellValue(chr($letterval) . '8', $item->subjdesc);
            $formula = '=AVERAGE(' . chr($letterval) . '10:' . chr($letterval) . (count($students) + 9) . ')';
            $formula = '=SUM(' . chr($letterval) . '10:' . chr($letterval) . (count($students) + 9) . ')/' . count($students);
            $sheet->setCellValue(chr($letterval) . '9', $formula);
            $letterval += 1;
        }

        $studentcol = 10;
        foreach ($students as $item) {
            $sheet->setCellValue('A' . $studentcol, $studentcol - 9);
            $sheet->setCellValue('B' . $studentcol, $item->studentname);

            $letterval = 67;
            foreach ($item->grades as $item) {
                if ($item->id != 'G1') {
                    $temp_qg = 'q' . $quarter;

                    $sheet->setCellValue(chr($letterval) . $studentcol, $item->$temp_qg);
                    $letterval += 1;
                }
            }

            $studentcol += 1;

        }



        $sheet->removeRow($studentcol, 500 - $studentcol);


        $sheet->getStyle('B' . ($studentcol + 4))->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B' . ($studentcol + 5))->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('B' . ($studentcol + 4), $adviser);
        $sheet->setCellValue('B' . ($studentcol + 5), "Class Adviser");


        $sheet->getStyle('F' . ($studentcol + 7))->getAlignment()->setHorizontal('center');
        $sheet->getStyle('F' . ($studentcol + 8))->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('F' . ($studentcol + 7), $principal_info[0]->name);
        $sheet->setCellValue('F' . ($studentcol + 8), $principal_info[0]->title);




        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $levelname->levelname . ' - ' . $quarterdesc . '.xlsx"');
        $writer->save("php://output");


    }

    public static function generateLP(Request $request)
    {

        $activesem = $request->get('semid');
        $activesy = $request->get('sy');
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $quarter = $request->get('quarter');
        $strandid = $request->get('strand');
        $subjid = $request->get('subjid');

        // $subjid = 3;


        // return $subjid;


        $levelname = DB::table('gradelevel')
            ->where('id', $gradelevel)
            ->first();

        $acadprog = $levelname->acadprogid;

        $gradelevel = DB::table('gradelevel')
            ->where('acadprogid', $acadprog)
            ->select(
                'id'
            )
            ->get();

        $subject = DB::table('subjects')
            ->where('id', $subjid)
            ->first();

        $sections = DB::table('sectiondetail')
            ->join('sections', function ($join) use ($gradelevel) {
                $join->on('sectiondetail.sectionid', '=', 'sections.id');
                $join->where('sections.deleted', 0);
                $join->whereIn('sections.levelid', collect($gradelevel)->pluck('id'));
            })
            ->join('gradelevel', function ($join) {
                $join->on('sections.levelid', '=', 'gradelevel.id');
                $join->where('sections.deleted', 0);
            })

            ->where('sectiondetail.deleted', 0)
            ->where('syid', $activesy)
            ->select(
                'levelname',
                'sectionname',
                'levelid',
                'sectionid',
                'gradelevel.acadprogid'
                //   'lastname',
                //     'firstname',
                //     'middlename',
                //     'suffix',
                //     'title',
                //     'acadtitle'
            )
            ->get();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("GSA-PL.xlsx");

        $sheetcount = 1;

        foreach ($sections as $item) {

            $subjteacher = DB::table('assignsubj')
                ->join('assignsubjdetail', function ($join) use ($subjid) {
                    $join->on('assignsubj.id', '=', 'assignsubjdetail.headerid');
                    $join->where('assignsubjdetail.deleted', 0);
                    $join->where('subjid', $subjid);
                })
                ->join('teacher', function ($join) {
                    $join->on('assignsubjdetail.teacherid', '=', 'teacher.id');
                    $join->where('teacher.deleted', 0);
                })
                ->select(
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    'teacherid',
                    'title',
                    'acadtitle'
                )
                ->where('syid', $activesy)
                ->where('sectionid', $item->sectionid)
                ->where('assignsubj.deleted', 0)
                ->first();



            $adviser = '';
            if (isset($subjteacher)) {


                $temp_middle = '';
                $temp_suffix = '';
                $temp_title = '';
                $temp_acadtitle = '';
                if (isset($subjteacher->middlename)) {
                    $temp_middle = $subjteacher->middlename[0] . '.';
                }
                if (isset($subjteacher->title)) {
                    $temp_title = $subjteacher->title . '. ';
                }
                if (isset($subjteacher->suffix)) {
                    $temp_suffix = ', ' . $subjteacher->suffix;
                }
                if (isset($subjteacher->acadtitle)) {
                    $temp_acadtitle = ', ' . $subjteacher->acadtitle;
                }
                $adviser = $temp_title . $subjteacher->firstname . ' ' . $temp_middle . ' ' . $subjteacher->lastname . $temp_suffix . $temp_acadtitle;
            }


            $enrolledstud = DB::table('enrolledstud')
                ->join('studinfo', function ($join) {
                    $join->on('enrolledstud.studid', '=', 'studinfo.id');
                    $join->where('studinfo.deleted', 0);
                })
                ->where('enrolledstud.sectionid', $item->sectionid)
                ->where('enrolledstud.levelid', $item->levelid)
                ->where('enrolledstud.syid', $activesy)
                ->where('enrolledstud.deleted', 0)
                ->whereIn('enrolledstud.studstatus', [1, 2, 4])
                ->distinct('studid')
                ->select(
                    'studid',
                    'firstname',
                    'lastname',
                    'middlename',
                    'suffix',
                    'mol',
                    'gender',
                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                )
                ->orderBy('gender', 'desc')
                ->orderBy('studentname', 'asc')
                ->get();

            $hps = DB::table('grades')
                ->where('levelid', $item->levelid)
                ->where('syid', $activesy)
                ->where('subjid', $subjid)
                ->where('sectionid', $item->sectionid)
                ->where('quarter', $quarter)
                ->where('deleted', 0)
                ->select(
                    'qahr1',
                    'id'
                )
                ->first();






            $item->male = collect($enrolledstud)->whereIn('gender', ['MALE', 'male'])->count();
            $item->female = collect($enrolledstud)->whereIn('gender', ['FEMALE', 'female'])->count();
            $item->totalclasspopulation = collect($enrolledstud)->count();

            if (isset($hps)) {

                $item->hps = $hps->qahr1;

                $gradesdetail = DB::table('gradesdetail')
                    ->where('headerid', $hps->id)
                    ->whereIn('studid', collect($enrolledstud)->pluck('studid'))
                    ->join('studinfo', function ($join) {
                        $join->on('gradesdetail.studid', '=', 'studinfo.id');
                        $join->where('studinfo.deleted', 0);
                    })
                    ->select(
                        'qg',
                        'qa1',
                        'studid',
                        'firstname',
                        'lastname',
                        'middlename',
                        'suffix',
                        'mol',
                        'gender',
                        DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                    )
                    ->get();

                $item->totalmalescore = collect($gradesdetail)->whereIn('gender', ['MALE', 'male'])->sum('qa1');
                $item->totalfemalescore = collect($gradesdetail)->whereIn('gender', ['FEMALE', 'female'])->sum('qa1');
                $item->totalscore = collect($gradesdetail)->sum('qa1');

                $item->totalmalemstook = collect($gradesdetail)->whereIn('gender', ['MALE', 'male'])->count();
                $item->totalfemalemstook = collect($gradesdetail)->whereIn('gender', ['FEMALE', 'female'])->count();
                $item->totalmstook = collect($gradesdetail)->count();


                $item->totalqgmalescore = collect($gradesdetail)->whereIn('gender', ['MALE', 'male'])->sum('qg');
                $item->totalqgfemalescore = collect($gradesdetail)->whereIn('gender', ['FEMALE', 'female'])->sum('qg');
                $item->totalqgscore = collect($gradesdetail)->sum('qg');


                $item->totalbelowqgmale = collect($gradesdetail)->whereIn('gender', ['MALE', 'male'])->where('qg', '<', '75')->count();
                $item->totalbelowqgfemale = collect($gradesdetail)->whereIn('gender', ['FEMALE', 'female'])->where('qg', '<', '75')->count();
                $item->totalbelowqg = collect($gradesdetail)->where('qg', '<', '75')->count();


                $item->totalaboveqgmale = collect($gradesdetail)->whereIn('gender', ['MALE', 'male'])->where('qg', '>=', '75')->count();
                $item->totalaboveqgfemale = collect($gradesdetail)->whereIn('gender', ['FEMALE', 'female'])->where('qg', '>=', '75')->count();
                $item->totalabovewqg = collect($gradesdetail)->where('qg', '>=', '75')->count();


                $hps75percent = $item->hps * .75;

                $item->malewithabove75grades = collect($gradesdetail)->whereIn('gender', ['MALE', 'male'])->where('qa1', '>=', '75')->count();
                $item->femalewithabove75grades = collect($gradesdetail)->whereIn('gender', ['FEMALE', 'female'])->where('qa1', '>=', '75')->count();



                $item->malewithless75grades = collect($gradesdetail)->whereIn('gender', ['MALE', 'male'])->where('qa1', '<', '75')->values();
                $item->femalewithless75grades = collect($gradesdetail)->whereIn('gender', ['FEMALE', 'female'])->where('qa1', '<', '75')->count();



            } else {
                $item->hps = 0;
                $item->totalmalescore = 0;
                $item->totalfemalescore = 0;
                $item->totalscore = 0;

                $item->totalmalemstook = 0;
                $item->totalfemalemstook = 0;
                $item->totalmstook = 0;

                $item->totalqgmalescore = 0;
                $item->totalqgfemalescore = 0;
                $item->totalqgscore = 0;


                $item->totalbelowqgmale = 0;
                $item->totalbelowqgfemale = 0;
                $item->totalbelowqg = 0;


                $item->totalaboveqgmale = 0;
                $item->totalaboveqgfemale = 0;
                $item->totalabovewqg = 0;

                $item->malewithabove75grades = 0;
                $item->femalewithabove75grades = 0;

                $item->femalewithless75grades = 0;
                $item->femalewithless75grades = 0;

                $hps75percent = 0;
            }


            $sheet = $spreadsheet->setActiveSheetIndexByName('S' . $sheetcount);
            $sheet->setTitle($item->levelname . '-' . $item->sectionname);


            $sheet->setCellValue('B14', $quarter);

            $sheet->setCellValue('B15', 'FILIPINO');
            $sheet->setCellValue('B16', $item->levelname . '-' . $item->sectionname);

            $sheet->setCellValue('B17', $item->hps);

            $sheet->setCellValue('B19', $item->male);
            $sheet->setCellValue('B20', $item->totalmalemstook);

            $sheet->setCellValue('B23', $item->female);
            $sheet->setCellValue('B24', $item->totalfemalemstook);

            $sheet->setCellValue('B27', $item->totalclasspopulation);
            $sheet->setCellValue('B28', $item->totalmstook);


            $sheet->setCellValue('E15', $item->totalmalescore);
            $sheet->setCellValue('E16', $item->totalmalemstook);

            $sheet->setCellValue('E20', $item->totalfemalescore);
            $sheet->setCellValue('E21', $item->totalfemalemstook);


            $sheet->setCellValue('E26', $item->totalscore);
            $sheet->setCellValue('E25', $item->totalmstook);

            $sheet->setCellValue('D34', 'HPS = ' . $item->hps);

            $sheet->setCellValue('D35', '# of Male Students with scores ' . $hps75percent . ' and above: ');
            $sheet->setCellValue('D36', '# of FeMale Students with scores ' . $hps75percent . ' and above: ');


            $sheet->setCellValue('E35', $item->malewithabove75grades);
            $sheet->setCellValue('E36', $item->femalewithabove75grades);


            $sheet->setCellValue('J15', $item->totalqgmalescore);
            $sheet->setCellValue('J16', $item->totalmalemstook);
            $sheet->setCellValue('J17', $item->totalbelowqgmale);
            $sheet->setCellValue('J21', $item->totalaboveqgmale);


            $sheet->setCellValue('J25', $item->totalqgfemalescore);
            $sheet->setCellValue('J26', $item->totalfemalemstook);
            $sheet->setCellValue('J27', $item->totalbelowqgfemale);
            $sheet->setCellValue('J31', $item->totalaboveqgfemale);


            $sheet->setCellValue('J36', $item->totalqgscore);


            $sheet->setCellValue('C40', '="The PL is "&TEXT(E28*100,"0.00")&"% and the percentage of the learners with 75% and above is "&TEXT(F37*100,"0.00")&"%"');

            $sheet->setCellValue('I46', '="or SIMPLY the PL is  "&TEXT(J41,"0.00")&"% and "&TEXT(J43*100,"0.00")&"% of the entire class"');
            $sheet->setCellValue('I47', 'has a grade of at least 75% and above in ' . $item->levelname . '-' . $item->sectionname . ' ' . $subject->subjdesc);

            $sheet->setCellValue('A66', strtoupper('Sr. Editha D. Dismas, TDM'));
            $sheet->setCellValue('A60', '');
            $sheet->setCellValue('A54', $adviser);

            $sheet = $spreadsheet->setActiveSheetIndexByName('SUMMARY');

            $sheet->setCellValue('A' . ($sheetcount + 18), $item->sectionname);

            $sheet->setCellValue('A' . ($sheetcount + 43), $item->sectionname);

            $sheet->setCellValue('B' . ($sheetcount + 18), $item->male);
            $sheet->setCellValue('C' . ($sheetcount + 18), $item->female);


            $sheet->setCellValue('F' . ($sheetcount + 18), $item->totalmalemstook);
            $sheet->setCellValue('G' . ($sheetcount + 18), $item->totalfemalemstook);


            $sheet->setCellValue('B' . ($sheetcount + 43), $item->malewithabove75grades);
            $sheet->setCellValue('C' . ($sheetcount + 43), $item->femalewithabove75grades);

            $sheetcount += 1;






        }



        $quarterdesc = '';

        if ($quarter == 1) {
            $quarterdesc = '1st Quarter';
        } else if ($quarter == 2) {
            $quarterdesc = '2nd Quarter';
        } else if ($quarter == 3) {
            $quarterdesc = '3rd Quarter';
        } else if ($quarter == 4) {
            $quarterdesc = '4th Quarter';
        }





        // $sheet = $spreadsheet->getActiveSheet();

        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $item->levelname . '-' . $item->sectionname . ' ' . $subject->subjdesc . '.xlsx"');
        $writer->save("php://output");


    }

}
