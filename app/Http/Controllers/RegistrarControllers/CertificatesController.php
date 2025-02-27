<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;


class CertificatesController extends Controller
{
    public static function get_students($syid, $semid)
    {
        $students = collect();
        $students_1 = DB::table('enrolledstud')
            ->select(
                'studinfo.id',
                'sid',
                'lrn',
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'gradelevel.id as levelid',
                'gender',
                'gradelevel.acadprogid',
                'enrolledstud.sectionid',
                'levelname',
                'gradelevel.acadprogid',
                'studentstatus.description as studentstatus'
            )
            ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            ->join('studentstatus', 'enrolledstud.studstatus', '=', 'studentstatus.id')
            ->where('studinfo.deleted', '0')
            ->where('gradelevel.deleted', '0')
            ->where('enrolledstud.deleted', '0')
            ->where('enrolledstud.syid', $syid)
            ->orderBy('lastname', 'asc')
            ->get();

        $students_2 = DB::table('sh_enrolledstud')
            ->select(
                'studinfo.id',
                'sid',
                'lrn',
                'lastname',
                'gender',
                'strandcode',
                'strandname',
                'firstname',
                'gradelevel.acadprogid',
                'sh_enrolledstud.sectionid',
                'middlename',
                'suffix',
                'gradelevel.id as levelid',
                'levelname',
                'gradelevel.acadprogid',
                'studentstatus.description as studentstatus',
                'sh_enrolledstud.strandid'
            )
            ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
            ->join('sh_strand', 'sh_enrolledstud.strandid', '=', 'sh_strand.id')
            ->join('studentstatus', 'sh_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->where('studinfo.deleted', '0')
            ->where('gradelevel.deleted', '0')
            ->where('sh_enrolledstud.deleted', '0')
            ->where('sh_enrolledstud.syid', $syid)
            ->where('sh_enrolledstud.semid', $semid)
            ->orderBy('lastname', 'asc')
            ->get();

        $students_3 = DB::table('college_enrolledstud')
            ->select(
                'studinfo.id',
                'sid',
                'lrn',
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'gradelevel.id as levelid',
                'courseDesc as coursename',
                'collegeDesc as collegename',
                'gender',
                'gradelevel.acadprogid',
                'levelname',
                'gradelevel.acadprogid',
                'studentstatus.description as studentstatus'
            )
            ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
            ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
            ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            ->join('studentstatus', 'college_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->where('studinfo.deleted', '0')
            ->where('gradelevel.deleted', '0')
            ->where('college_enrolledstud.deleted', '0')
            ->where('college_enrolledstud.syid', $syid)
            ->where('college_enrolledstud.semid', $semid)
            // ->whereIn('studstatus',[1,2,4])
            ->orderBy('lastname', 'asc')
            ->get();

        $students = $students->merge($students_1);
        $students = $students->merge($students_2);
        $students = $students->merge($students_3);
        $students = $students->unique('id');
        $students = $students->sortBy('firstname')->sortBy('lastname')->values()->all();

        return $students;
    }


    public function download_template(Request $request)
    {
        $templateinfo = DB::table('printables')
            ->where('id', $request->get('templateid'))
            ->first();

        $PHPWord = new \PhpOffice\PhpWord\PhpWord();
        $document = $PHPWord->loadTemplate(public_path() . '/' . $templateinfo->filepath);

        $file_url = $templateinfo->name . '.' . $templateinfo->extension;

        $document->saveAs($file_url);

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: utf-8");
        header("Content-disposition: attachment; filename=" . $file_url);
        readfile($file_url);
        unlink($file_url);

        exit();
    }


    public function printable_certifications(Request $request)
    {

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $certtype = $request->get('certtype');

        if ($request->get('action') == 'filter') {
            $templates = DB::table('printables')
                ->where('cert_type', $certtype)
                ->where('deleted', '0')
                ->get();

            $students = self::get_students($syid, $semid);

            return view('registrar.otherprintables.certifications.results')
                ->with('students', $students)
                ->with('templates', $templates);

        } elseif ($request->get('action') == 'export') {
            $students = self::get_students($syid, $semid);
            $schoolyear = DB::table('sy')->where('id', $request->get('syid'))->first()->sydesc;
            $semester = DB::table('semester')->where('id', $request->get('semid'))->first()->semester;
            // return $request->all();
            $studentinfo = collect($students)->where('id', $request->get('studid'))->first();
            $PHPWord = new \PhpOffice\PhpWord\PhpWord();
            $studid = $request->get('studid');
            //return collect($studentinfo);

            $templateinfo = DB::table('printables')
                ->where('id', $request->get('templateid'))
                ->first();

            $template = new \PhpOffice\PhpWord\TemplateProcessor($templateinfo->filepath);
            $document = $template;



            $document->setValue('sid', $studentinfo->sid);
            $document->setValue('lrn', $studentinfo->lrn);

            $document->setValue('fullname_first', $studentinfo->firstname . ' ' . $studentinfo->middlename . ' ' . $studentinfo->lastname . ' ' . $studentinfo->suffix);
            $document->setValue('fullname_last', $studentinfo->lastname . ', ' . $studentinfo->firstname . ' ' . $studentinfo->suffix . ' ' . $studentinfo->middlename);

            $document->setValue('firstname', $studentinfo->firstname);
            $document->setValue('middlename', $studentinfo->middlename);
            $document->setValue('lastname', $studentinfo->lastname);
            $document->setValue('suffix', $studentinfo->suffix);
            $document->setValue('schoolyear', $schoolyear);
            $document->setValue('semester', $semester);

            $middleinitial = '';
            if (isset($studentinfo->middlename)) {
                $middleinitial = $studentinfo->middlename[0] . '.';
            }

            $yearlevel = '';
            if ($studentinfo->levelid == 17) {
                $yearlevel = 'First Year';
            } else if ($studentinfo->levelid == 18) {
                $yearlevel = 'Second Year';
            } else if ($studentinfo->levelid == 19) {
                $yearlevel = 'Third Year';
            } else if ($studentinfo->levelid == 20) {
                $yearlevel = 'Fourth Year';
            } else if ($studentinfo->levelid == 21) {
                $yearlevel = 'Fifth Year';
            } else {
                $yearlevel = $studentinfo->levelname;
            }

            $document->setValue('gradelevel', $yearlevel);

            $objectpronoun = 'him';
            $subjectpronoun = 'He';

            if ($studentinfo->gender == 'FEMALE') {
                $objectpronoun = 'her';
                $subjectpronoun = 'She';
            }

            $document->setValue('objectpronoun', $objectpronoun);
            $document->setValue('section', $studentinfo->strandname ?? '');
            $document->setValue('so_num', $request->get('so_num'));


            $dateofgraduation = $request->get('dateofgraduation');

            $document->setValue('dateofgraduation', \Carbon\Carbon::create($dateofgraduation)->isoFormat('dateofgraduation'));
            $document->setValue('registar', $request->get('registrar') ?? '');
            $document->setValue('section', $studentinfo->sectionname ?? '');
            $document->setValue('track', $studentinfo->trackname ?? '');
            $document->setValue('strand', $studentinfo->strandname ?? '');
            $document->setValue('college', $studentinfo->collegename ?? '');
            $document->setValue('course', $studentinfo->coursename ?? '');

            $document->setValue('dateissued', date('jS', strtotime($request->get('dateissued'))) . ' day of ' . date('F Y', strtotime($request->get('dateissued'))));
            $document->setValue('purpose', $request->get('purpose'));

            $document->setValue('so_num', $request->get('so_num'));
            $document->setValue('so_series', $request->get('so_series'));
            $document->setValue('so_date', $request->get('so_date'));

            $datemonthyear = date('F, Y', strtotime($request->get('dateissued')));
            $date1 = date('j', strtotime($request->get('dateissued')));
            $date2 = date('S', strtotime($request->get('dateissued')));

            $document->setValue('datemonthyear', $datemonthyear);
            $document->setValue('date1', $date1);
            $document->setValue('date2', $date2);

            if ($studentinfo->acadprogid == 6) {
                $records = collect(\App\Models\College\TOR::getrecords($request->get('studid'), DB::table('sy')->select('sy.*', 'id as syid')->where('id', $request->get('syid'))->get()))->where('semid', $request->get('semid'));
                $subjects = collect($records)->count() == 0 ? array() : collect($records)->first()->subjdata;


                if (count($subjects) > 0) {
                    foreach ($subjects as $subject) {
                        $subject->subjectcode = $subject->subjcode;
                        $subject->subjectdesc = $subject->subjdesc;
                        $subject->subjectgrade = $subject->subjgrade;
                        $subject->subjectunits = $subject->subjunit;
                        $subject->subjectcredits = $subject->subjcredit;

                        $subject->subjremarks = $subject->subjgrade > 0 ? ($subject->subjgrade > 74 ? 'PASSED' : 'FAILED') : '';
                        $subject->subjectremarks = $subject->subjremarks;
                    }
                }

                try {
                    $document->cloneRowAndSetValues('subjectcode', $subjects);
                } catch (\Exception $e) {
                }
            } else {

                if ($studentinfo->levelid == 14 || $studentinfo->levelid == 15) {
                    $strand = $studentinfo->strandid;
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($studentinfo->levelid, $studid, $syid, $strand, null, $studentinfo->sectionid, true);

                } else {

                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($studentinfo->levelid, $studid, $syid, null, null, $studentinfo->sectionid, true);

                }

                $quarter = $request->get('quarter');
                $grade = collect($studgrades)->where('subjid', $request->get('subjid'))->values();

                if (count($grade) > 0) {
                    if ($quarter == 1) {
                        $document->setValue($quarter, '1st Quarter');
                        $document->setValue($grade, $grade[0]->q1);
                    } else if ($quarter == 2) {
                        $document->setValue($quarter, '2nd Quarter');
                        $document->setValue($grade, $grade[0]->q2);
                    } else if ($quarter == 3) {
                        $document->setValue($quarter, '3rd Quarter');
                        $document->setValue($grade, $grade[0]->q3);
                    } else if ($quarter == 4) {
                        $document->setValue($quarter, '4th Quarter');
                        $document->setValue($grade, $grade[0]->q4);
                    }
                }


            }
            $file_url = strtoupper($templateinfo->cert_type) . '_' . $studentinfo->lastname . '_' . $studentinfo->firstname . '.' . $templateinfo->extension;

            $document->saveAs($file_url);

            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: utf-8");
            header("Content-disposition: attachment; filename=" . $file_url);
            readfile($file_url);
            unlink($file_url);

            exit();
        } elseif ($request->get('action') == 'upload') {
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $cert_type = $request->get('certtype');

            $localfolder = 'certifications';
            // return $localfolder;
            $file = $request->file('file');

            $filename = $file->getClientOriginalName();

            $extension = $file->getClientOriginalExtension();

            if (!File::exists(public_path() . $localfolder)) {
                $path = public_path($localfolder);
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }
            }
            if (strpos($request->root(), 'http://') !== false) {
                $urlFolder = str_replace('http://', '', $request->root());
            } else {
                $urlFolder = str_replace('https://', '', $request->root());
            }

            if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder)) {
                $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;
                if (!File::isDirectory($cloudpath)) {
                    File::makeDirectory($cloudpath, 0777, true, true);
                }
            }

            $id = DB::table('printables')
                ->insertGetId([
                    'name' => pathinfo($filename, PATHINFO_FILENAME),
                    'extension' => $extension,
                    'cert_type' => $cert_type,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);
            $destinationPath = public_path($localfolder . '/');
            $newname = pathinfo($filename, PATHINFO_FILENAME) . '-' . $id . '.' . $extension;
            try {
                $file->move($destinationPath, $newname);
            } catch (\Exception $e) {
                return $e;
            }


            DB::table('printables')
                ->where('id', $id)
                ->update([
                    'filepath' => $localfolder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '-' . $id . '.' . $extension
                ]);

            $templateinfo = DB::table('printables')->where('id', $id)->first();
            return collect($templateinfo);

        } elseif ($request->get('action') == 'template_delete') {
            DB::table('printables')
                ->where('id', $request->get('tempid'))
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => date('Y-m-d H:i:s')
                ]);
            return 1;
        } else {
            return view('registrar.otherprintables.certifications.index');
        }
    }

}
