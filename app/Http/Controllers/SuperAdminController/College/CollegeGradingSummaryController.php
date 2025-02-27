<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use Session;
use PDF;

class CollegeGradingSummaryController extends \App\Http\Controllers\Controller
{
      public static function print_grades(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $studid = $request->get('studid');
            $subjid = $request->get('subjid');

            if ($subjid == null || $syid == null || $semid == null) {
                  return "Invalid selection";
            }


            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load("College Grading Sheet.xlsx");

            $sheet = $spreadsheet->setActiveSheetIndex(0);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            $student_grades = self::subject_grade($request);

            $prospectusid = DB::table('college_prospectus')
                  ->where('subjectID', $subjid)
                  ->select(
                        'id',
                        'subjDesc',
                        'subjCode'
                  )
                  ->get();

            $p_id = array();




            foreach ($prospectusid as $item) {
                  array_push($p_id, $item->id);
            }


            $teacher_info = DB::table('college_classsched')
                  ->join('teacher', function ($join) {
                        $join->on('college_classsched.teacherid', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                  })
                  ->whereIn('college_classsched.subjectID', $p_id)
                  ->where('college_classsched.deleted', 0)
                  ->where('college_classsched.syid', $syid)
                  ->whereNotNull('college_classsched.teacherID')
                  ->where('college_classsched.semesterID', $semid)
                  ->select(
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'teacherid',
                        'title',
                        'college_classsched.id'
                  )
                  ->first();


            $sched_info = DB::table('college_scheddetail')
                  ->where('headerid', $teacher_info->id)
                  ->first();

            // $teacher

            if (isset($sched_info->id)) {
                  $sheet->setCellValue('B11', $sched_info->schedotherclass);
                  $sheet->setCellValue('H10', \Carbon\Carbon::create($sched_info->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::create($sched_info->etime)->isoFormat('hh:mm A'));
            }

            $teacher = null;

            if (isset($teacher_info->lastname)) {
                  $temp_middle = '';
                  $temp_suffix = '';
                  $temp_title = '';
                  if (isset($teacher_info->middlename)) {
                        $temp_middle = $teacher_info->middlename[0] . '.';
                  }
                  if (isset($teacher_info->title)) {
                        $temp_title = $teacher_info->title . '. ';
                  }
                  if (isset($teacher_info->suffix)) {
                        $temp_suffix = ', ' . $teacher_info->suffix;
                  }
                  $teacher = $temp_title . $teacher_info->firstname . ' ' . $temp_middle . ' ' . $teacher_info->lastname . $temp_suffix;

            }




            $prospectusid = DB::table('college_prospectus')
                  ->where('subjectID', $subjid)
                  ->select(
                        'id',
                        'subjDesc',
                        'subjCode',
                        'lecunits'
                  )
                  ->first();

            $sheet->setCellValue('C9', $prospectusid->subjCode);
            $sheet->setCellValue('D10', $prospectusid->subjCode);

            $sheet->setCellValue('j9', $prospectusid->lecunits);
            $sheet->setCellValue('i11', $teacher);

            $row = 14;
            $student_count = 1;

            foreach ($student_grades as $item) {
                  $sheet->setCellValue('A' . $row, $student_count);

                  $sheet->mergeCells('B' . $row . ':E' . $row);
                  $sheet->setCellValue('B' . $row, $item->full_name);

                  $sheet->mergeCells('F' . $row . ':H' . $row);
                  $sheet->setCellValue('F' . $row, $item->courseabrv);

                  $sheet->mergeCells('I' . $row . ':K' . $row);
                  $sheet->setCellValue('I' . $row, $item->yearLevel);

                  $sheet->setCellValue('L' . $row, $item->midtermgrade);
                  $sheet->setCellValue('N' . $row, $item->prefigrade);
                  $sheet->setCellValue('P' . $row, '=AVERAGE(L' . $row . ',N' . $row . ')');

                  $sheet->getStyle('L' . $row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $sheet->getStyle('N' . $row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $sheet->getStyle('P' . $row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                  $student_count += 1;

                  if (count($student_grades) != $student_count - 1) {
                        $row += 1;
                        $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);
                  }


            }


            if ($syid == 3) {
                  $registrar = 'IRENEO SARONITMAN JR.';
                  $dean = 'KATHERINE ROSALES';
            }

            $sheet->setCellValue('A' . ($row + 2), $teacher);
            $sheet->setCellValue('F' . ($row + 2), $dean);
            $sheet->setCellValue('M' . ($row + 2), $registrar);

            $sheet->getStyle('L' . $row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('N' . $row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('P' . $row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="GradingSheet.xlsx"');
            $writer->save("php://output");
            exit();

      }


      public static function subject_grade(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $studid = $request->get('studid');
            $subjid = $request->get('subjid');

            $prospectusid = DB::table('college_prospectus')
                  ->where('subjectID', $subjid)
                  ->select(
                        'id'
                  )
                  ->get();

            $p_array = array();

            foreach ($prospectusid as $item) {
                  array_push($p_array, $item->id);
            }

            $grades = DB::table('college_studentprospectus')
                  ->join('studinfo', function ($join) {
                        $join->on('college_studentprospectus.studid', '=', 'studinfo.id');
                        $join->where('studinfo.deleted', 0);
                  })
                  ->join('college_enrolledstud', function ($join) use ($syid, $semid) {
                        $join->on('college_studentprospectus.studid', '=', 'college_enrolledstud.studid');
                        $join->where('college_enrolledstud.deleted', 0);
                        $join->where('college_enrolledstud.syid', $syid);
                        $join->where('college_enrolledstud.semid', $semid);
                        $join->whereIn('college_enrolledstud.studstatus', [1, 2, 4]);
                  })
                  ->leftJoin('college_courses', function ($join) {
                        $join->on('college_enrolledstud.courseID', '=', 'college_courses.id');
                        $join->where('college_courses.deleted', 0);
                  })
                  ->whereIn('prospectusId', $p_array)
                  ->where('college_studentprospectus.deleted', 0)
                  ->where('college_studentprospectus.syid', $syid)
                  ->where('college_studentprospectus.semid', $semid)
                  ->select(
                        'college_studentprospectus.*',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'courseabrv',
                        'college_enrolledstud.yearLevel as levelid'
                  )
                  ->get();

            foreach ($grades as $item) {
                  $temp_middle = '';
                  $temp_suffix = '';
                  if (isset($item->middlename)) {
                        if (strlen($item->middlename) > 0) {
                              $temp_middle = ' ' . $item->middlename[0] . '.';
                        }
                  }
                  if (isset($item->suffix)) {
                        $temp_suffix = ' ' . $item->suffix;
                  }
                  $item->full_name = $item->lastname . ', ' . $item->firstname . $temp_middle . $temp_suffix;

                  $levelid = null;

                  if ($item->levelid == 17) {
                        $levelid = 1;
                  } else if ($item->levelid == 17) {
                        $levelid = 2;
                  } else if ($item->levelid == 19) {
                        $levelid = 3;
                  } else if ($item->levelid == 20) {
                        $levelid = 4;
                  }

                  $item->yearLevel = $levelid;
                  $item->levelcourse = $item->courseabrv . ' - ' . $levelid;
            }


            return $grades;


      }

      public static function student_grades(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $studid = $request->get('studid');

            $grades = DB::table('college_studentprospectus')
                  ->where('deleted', 0)
                  ->where('studid', $studid)
                  ->where('syid', $syid)
                  ->where('semid', $semid)
                  ->get();


            $schedule = \App\Http\Controllers\SuperAdminController\StudentLoading::collegestudentsched_plot($studid, $syid, $semid);


            $temp_grades = array();

            foreach ($schedule[0]->info as $item) {
                  $check = collect($grades)->where('prospectusID', $item->subjectID)->first();
                  if (isset($check->id)) {

                        if ($check->prelemstatus == null) {
                              $check->prelemgrade = "";
                        }
                        if ($check->midtermstatus == null) {
                              $check->midtermgrade = "";
                              ;
                        }
                        if ($check->prefistatus == null) {
                              $check->prefigrade = "";
                              ;
                        }
                        if ($check->finalstatus == null) {
                              $check->finalgrade = "";
                              ;
                        }

                        $check->units = $item->lecunits;
                        $check->subjDesc = $item->subjDesc;
                        $check->subjCode = $item->subjCode;
                        $check->teacher = $item->teacher;
                        array_push($temp_grades, $check);
                  } else {
                        $check = collect(array());
                        $check->prelemgrade = null;
                        $check->midtermgrade = null;
                        $check->prefigrade = null;
                        $check->finalgrade = null;
                        $check->units = $item->lecunits;
                        $check->subjDesc = $item->subjDesc;
                        $check->subjCode = $item->subjCode;
                        array_push($temp_grades, (object) [
                              'prelemgrade' => null,
                              'midtermgrade' => null,
                              'prefigrade' => null,
                              'finalgrade' => null,
                              'fg' => null,
                              'units' => $item->lecunits,
                              'subjDesc' => $item->subjDesc,
                              'subjCode' => $item->subjCode,
                              'teacher' => $item->teacher,
                        ]);
                  }
            }

            return $temp_grades;

      }

      public static function students_enrolled(Request $request)
      {



            $enrolled_students = DB::table('college_enrolledstud')
                  ->join('studinfo', function ($join) {
                        $join->on('college_enrolledstud.studid', '=', 'studinfo.id');
                        $join->where('studinfo.deleted', 0);
                  })
                  ->where('college_enrolledstud.deleted', 0)
                  ->where('college_enrolledstud.syid', $request->get('syid'))
                  ->where('college_enrolledstud.semid', $request->get('semid'))
                  ->distinct('studid')
                  ->select(
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'studid',
                        'sid',
                        'studid as id'
                  )
                  ->get();

            foreach ($enrolled_students as $item) {
                  $temp_middle = '';
                  $temp_suffix = '';
                  if (isset($item->middlename)) {
                        if (strlen($item->middlename) > 0) {
                              $temp_middle = ' ' . $item->middlename[0] . '.';
                        }
                  }
                  if (isset($item->suffix)) {
                        $temp_suffix = ' ' . $item->suffix;
                  }
                  $item->full_name = $item->lastname . ', ' . $item->firstname . $temp_middle . $temp_suffix;
                  $item->text = $item->sid . ' - ' . $item->full_name;
            }

            return $enrolled_students;

      }

      public static function college_subjects()
      {

            $subjects = Db::table('college_subjects')
                  ->where('deleted', 0)
                  ->select(
                        'id',
                        'subjCode',
                        'subjDesc',
                        DB::raw("CONCAT(college_subjects.subjCode,' - ',college_subjects.subjDesc) as text")
                  )
                  ->get();


            return $subjects;


      }

      public static function generate_grade_pdf(Request $request)
      {

            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');


            $studinfo = DB::table('studinfo')
                  ->where('id', $studid)
                  ->first();


            $temp_middle = '';
            $temp_suffix = '';
            $studinfo->mi = null;
            if (isset($studinfo->middlename)) {
                  if (strlen($studinfo->middlename) > 0) {
                        $temp_middle = ' ' . $studinfo->middlename[0] . '.';
                        $studinfo->mi = $temp_middle;
                  } else {
                        $studinfo->mi = null;
                  }
            }
            if (isset($studinfo->suffix)) {
                  $temp_suffix = ' ' . $studinfo->suffix;
            }
            $studinfo->student = $studinfo->lastname . ', ' . $studinfo->firstname . $temp_suffix . $temp_middle;

            $enrollment = DB::table('college_enrolledstud')
                  ->join('college_courses', function ($join) {
                        $join->on('college_enrolledstud.courseid', '=', 'college_courses.id');
                  })
                  ->where('studid', $studid)
                  ->where('syid', $syid)
                  ->where('semid', $semid)
                  ->select(
                        'yearLevel',
                        'courseabrv'
                  )
                  ->first();



            if ($enrollment->yearLevel == 17) {
                  $enrollment->gradelevel = 1;
                  $enrollment->leveltext = '1st';
            } elseif ($enrollment->yearLevel == 18) {
                  $enrollment->gradelevel = 2;
                  $enrollment->leveltext = '2nd';
            } elseif ($enrollment->yearLevel == 19) {
                  $enrollment->gradelevel = 3;
                  $enrollment->leveltext = '3rd';
            } elseif ($enrollment->yearLevel == 20) {
                  $enrollment->leveltext = '4th';
                  $enrollment->gradelevel = 4;
            }

            $grades = self::student_grades($request);
            //return $grades;

            $sydesc = DB::table('sy')
                  ->where('id', $syid)
                  ->first();

            $semdesc = DB::table('semester')
                  ->where('id', $semid)
                  ->first();

            $registrar = DB::table('teacher')
                  ->where('id', $request->get('registrar'))
                  ->first();

            $registrar_sig = '';

            if (isset($registrar)) {
                  $temp_middle = '';
                  $temp_suffix = '';
                  $temp_title = '';
                  if (isset($registrar->middlename)) {
                        $temp_middle = $registrar->middlename[0] . '.';
                  }
                  if (isset($registrar->acadtitle)) {
                        $temp_title = ', ' . $registrar->acadtitle;
                  }
                  if (isset($item->suffix)) {
                        $temp_suffix = ', ' . $registrar->suffix;
                  }
                  $registrar_sig = $registrar->firstname . ' ' . $temp_middle . ' ' . $registrar->lastname . $temp_suffix . $temp_title;
            }

            $registrar = $registrar_sig;

            $schoolinfo = DB::table('schoolinfo')->first();


            $pdf = PDF::loadView('registrar.pdf.srrcapmc', compact('registrar', 'enrollment', 'studinfo', 'grades', 'sydesc', 'semdesc', 'schoolinfo'))->setPaper('8.5x13', 'landscape');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream();
      }

}
