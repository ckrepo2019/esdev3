<?php

namespace App\Http\Controllers\PrincipalControllers;

use Illuminate\Http\Request;
use DB;
use Crypt;
use Hash;
use Illuminate\Support\Facades\Validator;
use Session;
use PhpOffice\PhpWord\TemplateProcessor;

class PrincipalController extends \App\Http\Controllers\Controller
{

    public static function grades_status(Request $request)
    {

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('section');
        $acadprogid = $request->get('acadprogid');
        $levelid = $request->get('levelid');
        $strandid = $request->get('strandid');

        $sectioninfo = DB::table('sectiondetail')
            ->where('syid', $syid)
            ->where('sectionid', $sectionid)
            ->where('deleted', 0)
            ->first();

        $issp = false;

        if ($sectioninfo->sd_issp == 1) {
            $issp = true;
        }

        $subjects = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null, null, $levelid, null, $syid, $semid, $strandid, array(), $issp);
        $grading_version = DB::table('zversion_control')->where('module', 1)->where('isactive', 1)->first();

        foreach ($subjects as $item) {
            if ($grading_version->version == 'v2') {
                $checkStatus = DB::table('grading_sytem_gradestatus')
                    ->where('sectionid', $sectionid)
                    ->where('levelid', $levelid)
                    ->where('subjid', $item->id)
                    ->where('syid', self::activeSy()->id);
                if ($acadprogid == 5) {
                    $checkStatus = $checkStatus->where('semid', self::activeSem()->id);
                }
                $checkStatus = $checkStatus->select('q1status', 'q2status', 'q3status', 'q4status')
                    ->first();

                $temp_gradesstatus = array();
                $quarter = 1;
                for ($x = 1; $x <= 4; $x++) {
                    $string = 'q' . $x . 'status';
                    $status = 0;
                    if ($checkStatus->$string == 2) {
                        $status = 2;
                    } else if ($checkStatus->$string == 4) {
                        $status = 3;
                    } else if ($checkStatus->$string == 1) {
                        $status = 1;
                    } else if ($checkStatus->$string == 3) {
                        $status = 4;
                    }
                    array_push($temp_gradesstatus, (object) [
                        'quarter' => $x,
                        'status' => $status,
                        'gradeid' => '#',
                        'submitted' => 1
                    ]);
                }

                $item->gradestatus = $temp_gradesstatus;

            } else {
                if (isset($item->id)) {
                    $gradestatus = DB::table('grades')
                        ->where('grades.deleted', '0')
                        ->where('sectionid', $sectionid)
                        ->where('subjid', $item->subjid)
                        ->where('syid', $syid);
                    if ($acadprogid == 5) {
                        $gradestatus->where('semid', $semid);
                    }
                    $gradestatus->select(
                        'grades.quarter',
                        'grades.status',
                        'grades.id as gradeid',
                        'grades.submitted'
                    );
                    $item->gradestatus = $gradestatus->get();
                }
            }

        }
        return view('principalsportal.pages.section.gradestatustable')->with('classassignsubj', $subjects);

    }

    public function loadSectionProfile($id)
    {

        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
        }

        $syid = DB::table('sy')
            ->where('isactive', 1)
            ->first()
            ->id;

        $sectioninfo = DB::table('sections')
            ->join('gradelevel', function ($join) {
                $join->on('sections.levelid', '=', 'gradelevel.id');
            })
            ->leftJoin('sectiondetail', function ($join) use ($syid) {
                $join->on('sections.id', '=', 'sectiondetail.sectionid');
                $join->where('sectiondetail.deleted', '0');
                $join->where('sectiondetail.syid', $syid);
            })
            ->leftJoin('rooms', function ($join) {
                $join->on('sections.roomid', '=', 'rooms.id');
                $join->where('rooms.deleted', '0');
            })
            ->where('sections.id', $id)
            ->select(
                'sections.*',
                'gradelevel.levelname',
                'acadprogid',
                'sectiondetail.teacherid',
                'roomname',
                'sectionname as sn',
                'sd_issp',
                'sd_ismwsp',
                'sd_session as session'
            )
            ->first();

        return view('principalsportal.pages.section.sectioninfo')
            ->with('sectionInfo', $sectioninfo);

    }

    public static function section_adviser(Request $request)
    {
        $syid = $request->get('syid');
        $sectionid = $request->get('section');

        $teacher = DB::table('sectiondetail')
            ->join('teacher', function ($join) {
                $join->on('sectiondetail.teacherid', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->where('sectiondetail.deleted', 0)
            ->where('syid', $syid)
            ->where('sectionid', $sectionid)
            ->select(
                'lastname',
                'firstname',
                'middlename',
                'tid'
            )
            ->get();

        return $teacher;

    }

    public static function block_assignment(Request $request)
    {

        $sectionid = $request->get('section');
        $syid = $request->get('syid');

        $blockassignment = DB::table('sh_sectionblockassignment')
            ->join('sh_block', function ($join) {
                $join->on('sh_sectionblockassignment.blockid', '=', 'sh_block.id');
                $join->where('sh_block.deleted', '0');
            })
            ->leftJoin('sh_strand', function ($join) {
                $join->on('sh_block.strandid', '=', 'sh_strand.id');
                $join->where('sh_strand.deleted', 0);
            })
            ->where('sh_sectionblockassignment.sectionid', $sectionid)
            ->where('sh_sectionblockassignment.deleted', '0')
            ->where('sh_sectionblockassignment.syid', $syid)
            ->select(
                'strandname',
                'strandcode',
                'sh_block.*',
                'sh_sectionblockassignment.blockid'
            )
            ->get();

        return $blockassignment;

    }

    public static function enrolled_students(Request $request)
    {
        
        $strandid = $request->get('strand');
        $sectionid = $request->get('section');
        $acadprog = $request->get('acad');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $studid = $request->get('studid');
        $gradelevel = $request->get('gradelevel');

        $enrolled = [];

        if ($acadprog == 4 || $acadprog == 3 || $acadprog == 2) {
           
            $enrolled = DB::table('enrolledstud')
                ->where('enrolledstud.deleted', 0)
                ->join('studinfo', function ($join) {
                    $join->on('enrolledstud.studid', '=', 'studinfo.id');
                    $join->where('studinfo.deleted', 0);
                })
                ->join('studentstatus', function ($join) {
                    $join->on('enrolledstud.studstatus', '=', 'studentstatus.id');
                })
                ->join('sections', function ($join) {
                    $join->on('enrolledstud.sectionid', '=', 'sections.id');
                    $join->where('sections.deleted', 0);
                })
                ->join('gradelevel', function ($join) {
                    $join->on('enrolledstud.levelid', '=', 'gradelevel.id');
                    $join->where('gradelevel.deleted', 0);
                });

            if ($syid != null) {
                $enrolled = $enrolled->where('enrolledstud.syid', $syid);
            }
            if ($gradelevel != null) {
                $enrolled = $enrolled->where('enrolledstud.levelid', $gradelevel);
            }
            if ($sectionid != null) {
                $enrolled = $enrolled->where('enrolledstud.sectionid', $sectionid);
            }
            
            if ($studid != null) {
                $enrolled = $enrolled->where('enrolledstud.studid', $studid);
            }
            
            $enrolled = $enrolled
                ->orderBy('studentname', 'asc')
                ->select(
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    'gradelevel.levelname',
                    'sid',
                    'enrolledstud.id',
                    'studid',
                    'enrolledstud.levelid',
                    'enrolledstud.sectionid',
                    'enrolledstud.studstatus',
                    'description',
                    'sections.sectionname',
                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                )
                ->distinct()
                ->get();
                

        } else if ($acadprog == 5) {

            $enrolled = DB::table('sh_enrolledstud')
                ->where('sh_enrolledstud.deleted', 0)
                ->join('studinfo', function ($join) {
                    $join->on('sh_enrolledstud.studid', '=', 'studinfo.id');
                    $join->where('studinfo.deleted', 0);
                })
                ->join('gradelevel', function ($join) {
                    $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id');
                    $join->where('gradelevel.deleted', 0);
                })
                ->join('studentstatus', function ($join) {
                    $join->on('sh_enrolledstud.studstatus', '=', 'studentstatus.id');
                })
                ->join('sections', function ($join) {
                    $join->on('sh_enrolledstud.sectionid', '=', 'sections.id');
                    $join->where('sections.deleted', 0);
                })
                ->join('sh_strand', function ($join) {
                    $join->on('sh_enrolledstud.strandid', '=', 'sh_strand.id');
                    $join->where('sh_strand.deleted', 0);
                });

            if ($syid != null) {
                $enrolled = $enrolled->where('sh_enrolledstud.syid', $syid);
            }
            if ($semid != null) {
                $enrolled = $enrolled->where('sh_enrolledstud.semid', $semid);
            }
            if ($studid != null) {
                $enrolled = $enrolled->where('sh_enrolledstud.studid', $studid);
            }
            if ($sectionid != null) {
                $enrolled = $enrolled->where('sh_enrolledstud.sectionid', $sectionid);
            }
            if ($strandid != null) {
                $enrolled = $enrolled->where('sh_enrolledstud.strandid', $strandid);
            }
            if ($gradelevel != null) {
                $enrolled = $enrolled->where('sh_enrolledstud.levelid', $gradelevel);
            }

            $enrolled = $enrolled
                ->orderBy('studentname', 'asc')
                ->select(
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    'gradelevel.levelname',
                    'sid',
                    'sh_enrolledstud.id',
                    'studid',
                    'sh_enrolledstud.levelid',
                    'sh_enrolledstud.sectionid',
                    'sh_enrolledstud.strandid',
                    'strandname',
                    'strandcode',
                    'sections.sectionname',
                    'sh_enrolledstud.studstatus',
                    'description',
                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                )
                ->distinct()
                ->get();

        }



        foreach ($enrolled as $item) {
            $item->actiontaken = null;
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
            $item->checked = 0;
        }

        return $enrolled;


    }

    public static function store_error($e)
    {
        DB::table('zerrorlogs')
            ->insert([
                'error' => $e,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
        return array(
            (object) [
                'status' => 0,
                'data' => 'Something went wrong!'
            ]
        );
    }

    public static function prinsf9getstudent(Request $request)
    {
        $refid = DB::table('usertype')->where('id', auth()->user()->type)->where('deleted', 0)->select('refid')->first();
        $teacherid = DB::table('teacher')->where('userid', auth()->user()->id)->select('id')->first()->id;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $acad = array();
        if (auth()->user()->type == 2) {
            $academicprogram = DB::table('academicprogram')
                ->where('principalid', $teacherid)
                ->select('id')
                ->get();
        } else {
            $academicprogram = DB::table('teacheracadprog')
                ->where('teacherid', $teacherid)
                ->where('syid', $syid)
                ->select('acadprogid as id')
                ->where('deleted', 0)
                ->get();
        }
        // $students = array();
        $students = []; // Initialize the students array

        foreach ($academicprogram as $item) {
            // Clone the request to avoid modifying the global object
            $clonedRequest = clone $request;
            $clonedRequest->request->add(['acad' => $item->id]);
            
            $student = self::enrolled_students($clonedRequest);
            
            foreach ($student as $stud_item) {
                // Ensure the necessary fields are present and not null
                $stud_item->id = $stud_item->studid ?? null;
                $stud_item->search = ($stud_item->student ?? '') . ' ' . ($stud_item->levelname ?? '') . ' ' . ($stud_item->sectionname ?? '') . ' ' . ($stud_item->sid ?? '');
                
                $isDuplicate = false;
                foreach ($students as $existingStudent) {
                    if ($existingStudent->id === $stud_item->id) {
                        $isDuplicate = true;
                        break;
                    }
                }
        
                // Add the student only if they are not already in the array
                if (!$isDuplicate) {
                    array_push($students, $stud_item);
                }
            }
        }

        return $students;
    }

    public static function principalAwardsAndRecognitions()
    {
        $data = array((object) ['data' => null, 'count' => -1]);
        return view('principalsportal.pages.awards.academicexcellenceaward')->with('data', $data);
    }

    public static function searchStudentWithHonors(Request $request)
    {
        $semid = $request->get('semid');
        $syid = $request->get('sy');
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $strandid = $request->get('strand');
        $request->request->add(['inSF9' => true]);
        $quarter = $request->get('quarter');

        $sections = DB::table('sections')
            ->where('levelid', $gradelevel)
            ->where('deleted', 0)
            ->where(function ($query) use ($section) {
                if ($section != null && $section != "") {
                    $query->where('id', $section);
                }
            })
            ->select(
                'id',
                'sectionname'
            )
            ->get();

        $gradelevel_students = array();
        foreach ($sections as $section_item) {
            $request->request->add(['section' => $section_item->id]);
            $temp_students = \App\Http\Controllers\TeacherControllers\TeacherGradingV4::get_student_data($request);

            foreach ($temp_students as $student) {
                if ($student->student != "SUBJECTS") {
                    if ($gradelevel == 14 || $gradelevel == 15) {
                        if (isset($student->strandcode)) {
                            $student->sectionname = $section_item->sectionname . ' / ' . $student->strandcode;
                        }
                        if (empty($strandid) || $strandid == $student->strand) {
                            array_push($gradelevel_students, $student);
                        }
                    } else {
                        array_push($gradelevel_students, $student);
                    }
                }
            }
        }
        $students = $gradelevel_students;

        $version = "V5";

        foreach ($students as $item) {
            if ($gradelevel == 14 || $gradelevel == 15) {
                $temp_data = ($quarter == 6)
                    ? collect($item->grades)->where('sortid', 'ZZGENAVE')->values()
                    : collect($item->grades)->where('subjid', 'G1')->where('semid', $semid)->values();
            } else {
                $temp_data = collect($item->grades)->where('subjid', 'G1')->values();
            }

            if (count($temp_data) > 0) {                             
                $quarter_grade = 'q' . $quarter;
                $quarter_award = 'q' . $quarter . 'award';
                $quarter_comp = 'q' . $quarter . 'comp';
                $quarter_lowest = 'lq' . $quarter;

                if ($quarter >= 1 && $quarter <= 4) {
                    $gen_ave = $temp_data[0]->$quarter_grade;
                    $composite = $temp_data[0]->$quarter_comp;
                    $award = $temp_data[0]->$quarter_award;
                    $lowest = $temp_data[0]->$quarter_lowest;
                } else if ($quarter == 5 || $quarter == 6) {
                    $gen_ave = $temp_data[0]->finalrating;
                    $composite = $temp_data[0]->fcomp;
                    $award = $temp_data[0]->fraward;
                    $lowest = null;
                }

                $item->quarter_award = (object) [
                    'name' => $item->student,
                    'strand' => $item->strand,
                    'genave' => $gen_ave,
                    'temp_comp' => $composite,
                    'award' => $award,
                    'lowest' => $lowest,
                    'studid' => $item->studid,
                    'quarter' => $quarter,
                    'rank' => null
                ];
            }
        }

        $rank = 1;

        foreach (collect($students)->sortByDesc('quarter_award.temp_comp')->values() as $item) {
            $item->rank = $rank;
            $rank += 1;
        }

        return collect($students)->sortBy('rank')->values();
    }

    public static function getNumberSuffix($number)
    {
        return ($number >= 11 && $number <= 13) ? 'th' : (($number % 10 == 1) ? 'st' : (($number % 10 == 2) ? 'nd' : (($number % 10 == 3) ? 'rd' : 'th')));
    }

    public static function print_award(Request $request)
    {
        // return $request->all();
        $studid = $request->get('studid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $quarter = $request->get('quarter');
        $sectionid = $request->get('sectionid');
        $levelid = $request->get('gradelevel');
        $gradelevel = $request->get('gradelevel');
        $date = $request->get('date');
        $rank = $request->get('rank');
        // $date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MM/DD/YYYY');

        $datenum = date('j', strtotime($date));
        $datesup = date('S', strtotime($date));

        $schoolinfo = DB::table('schoolinfo')
            ->first();

        $sydesc = DB::table('sy')
            ->where('id', $syid)
            ->first()->sydesc;

        $semester = DB::table('semester')
            ->where('id', $semid)
            ->first()->semester;

        $strandname = ' ';

        $PHPWord = new \PhpOffice\PhpWord\PhpWord();

        // if ($schoolinfo->abbreviation == 'SJAES') {
        //     $document = $PHPWord->loadTemplate(public_path() . '/Certificate_of_ranking _SJAES.docx');
        // } else {
        //     $document = $PHPWord->loadTemplate(public_path() . '/Certificate_of_ranking _Default.docx');
        // }

        $templatePath = $schoolinfo->abbreviation == 'SJAES' ?
            public_path() . '/Certificate_of_ranking _SJAES.docx' :
            public_path() . '/Certificate_of_ranking _Default.docx';

        $document = new TemplateProcessor($templatePath);

        // return base_path().'/'.$schoolinfo->picurl;

        $document->setImageValue('noImage.png', base_path() . '/' . $schoolinfo->picurl);

        if ($levelid == 14 || $levelid == 15) {
            $studcount = DB::table('sh_enrolledstud')
                ->where('levelid', $levelid)
                ->where('sectionid', $sectionid)
                ->where('semid', $semid)
                ->where('syid', $syid)
                ->where('deleted', 0)
                ->whereIN('studstatus', [1, 2, 4])
                ->distinct()
                ->count();

            $strandinfo = Db::table('sh_enrolledstud')
                ->join('sh_strand', function ($join) {
                    $join->on('sh_enrolledstud.strandid', '=', 'sh_strand.id');
                })
                ->where('levelid', $levelid)
                ->where('sectionid', $sectionid)
                ->where('semid', $semid)
                ->where('syid', $syid)
                ->where('sh_enrolledstud.deleted', 0)
                ->select(
                    'strandcode',
                    'strandid'
                )
                ->first();

            if (isset($strandinfo->strandcode)) {
                $strandname = $strandinfo->strandcode;
                $strandname = ' ' . $strandname . ' ';
                $strand = $strandinfo->strandid;
            }

        } else {
            $studcount = DB::table('enrolledstud')
                ->where('levelid', $levelid)
                ->where('sectionid', $sectionid)
                ->where('syid', $syid)
                ->where('deleted', 0)
                ->whereIN('studstatus', [1, 2, 4])
                ->distinct()
                ->count();
        }



        if ($levelid == 14 || $levelid == 15) {



            $student = DB::table('sh_enrolledstud')
                ->where('sh_enrolledstud.studid', $studid)
                ->where('sh_enrolledstud.deleted', 0)
                ->where('sh_enrolledstud.syid', $syid)
                ->join('studinfo', function ($join) {
                    $join->on('sh_enrolledstud.studid', '=', 'studinfo.id');
                    $join->where('studinfo.deleted', 0);
                })
                ->select(
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    'gender'
                )
                ->first();

            $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $studid, $syid, $strand, null, $sectionid, true);
            $qg = collect($studgrades)->where('id', 'G1')->where('semid', $semid)->first();

        } else {

            $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $studid, $syid, null, null, $sectionid, true);
            $qg = collect($studgrades)->where('id', 'G1')->first();
            $student = DB::table('enrolledstud')
                ->where('enrolledstud.studid', $studid)
                ->where('enrolledstud.deleted', 0)
                ->where('enrolledstud.syid', $syid)
                ->join('studinfo', function ($join) {
                    $join->on('studinfo.id', '=', 'enrolledstud.studid');
                    $join->where('studinfo.deleted', 0);
                })
                ->select(
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    'gender'
                )
                ->first();

        }

        $acadinfo = Db::table('gradelevel')
            ->where('id', $levelid)
            ->select('acadprogid')
            ->first()
            ->acadprogid;

        $middlename = '';
        if (isset($student->middlename)) {
            $middlename = explode(" ", $student->middlename);

            if (count($middlename) > 0) {
                $middlename = ' ' . $middlename[0][0] . '.';
            } else {
                $middlename = '';
            }

        }


        $suffix = '';
        if (isset($student->suffix)) {
            $suffix .= ' ' . $student->suffix;
        }

        $studname = $student->lastname . ', ' . $student->firstname . $suffix . $middlename;


        if ($acadinfo == 2) {
            $acad = 'Pre-school';
        } else if ($acadinfo == 3) {
            $acad = 'Grade School';
        } else if ($acadinfo == 4) {
            $acad = 'High School';
        } else if ($acadinfo == 5) {
            $acad = 'Senior High School';
        }

        if ($quarter == 1) {
            $grade = $qg->q1comp;
            $qsup = 'st';
        } else if ($quarter == 2) {
            $grade = $qg->q2comp;
            $qsup = 'nd';
        } else if ($quarter == 3) {
            $grade = $qg->q3comp;
            $qsup = 'rd';
        } else if ($quarter == 4) {
            $grade = $qg->q4comp;
            $qsup = 'th';
        }

        $principal_info = array(
            (object) [
                'name' => null,
                'title' => null
            ]
        );

        $signatory = DB::table('signatory')
            ->where('form', 'report_card')
            ->where('syid', $syid)
            ->where('acadprogid', $acadinfo)
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

        $ranksup = self::getNumberSuffix($rank);

        $document->setValue('studname', $studname);
        $document->setValue('acad', $acad);
        $document->setValue('sydesc', $sydesc);
        $document->setValue('rank', $rank);
        $document->setValue('ranksup', $ranksup);
        $document->setValue('grade', $grade);
        $document->setValue('principal', $principal_info[0]->name);

        if ($student->gender == 'MALE') {
            $document->setValue('gender', 'he');
        } else {
            $document->setValue('gender', 'she');
        }


        $document->setValue('schoolname', $schoolinfo->schoolname);
        $document->setValue('school_address', $schoolinfo->address);

        $document->setValue('datesup', $datesup);
        $document->setValue('datenum', $datenum);
        $document->setValue('month', \Carbon\Carbon::create($date)->isoFormat('MMMM'));
        $document->setValue('year', \Carbon\Carbon::create($date)->isoFormat('YYYY'));

        $document->setValue('studcount', $studcount);
        $document->setValue('strand', $strandname);

        $document->saveAs('Cert_award_' . $student->lastname . '_' . $student->firstname . '.docx');
        $file_url = 'Cert_award_' . $student->lastname . '_' . $student->firstname . '.docx';
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: utf-8");
        header("Content-disposition: attachment; filename=" . $file_url);
        readfile($file_url);
        unlink($file_url);

        exit();
    }

    //sf9 signatory
    public static function list_sf9_signatory(Request $request)
    {

        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $signatory = DB::table('signatory')
            ->where('form', 'report_card')
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->select(
                'id',
                'name',
                'title',
                'acadprogid'
            )
            ->get();

        return $signatory;
    }

    public static function create_sf9_signatory(Request $request)
    {

        try {

            $syid = $request->get('syid');
            $name = $request->get('name');
            $title = $request->get('title');
            $acadprogid = $request->get('acadprogid');

            $check = DB::table('signatory')
                ->where('form', 'report_card')
                ->where('acadprogid', $acadprogid)
                ->where('deleted', 0)
                ->where('syid', $syid)
                ->count();

            if ($check > 0) {
                return array(
                    (object) [
                        'status' => 0,
                        'message' => 'Already Exists!'
                    ]
                );
            }

            DB::table('signatory')
                ->insert([
                    'syid' => $syid,
                    'name' => $name,
                    'title' => $title,
                    'acadprogid' => $acadprogid,
                    'form' => 'report_card',
                    'deleted' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

            return array(
                (object) [
                    'status' => 1,
                    'message' => 'Signatory Created!'
                ]
            );

        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public static function update_sf9_signatory(Request $request)
    {
        try {

            $name = $request->get('name');
            $title = $request->get('title');
            $acadprogid = $request->get('acadprogid');
            $id = $request->get('id');
            $syid = $request->get('syid');

            DB::table('signatory')
                ->where('id', $id)
                ->update([
                    'syid' => $syid,
                    'name' => $name,
                    'title' => $title,
                    'acadprogid' => $acadprogid,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

            return array(
                (object) [
                    'status' => 1,
                    'message' => 'Signatory Updated!'
                ]
            );

        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public static function delete_sf9_signatory(Request $request)
    {
        try {

            DB::table('signatory')
                ->where('id', $id)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

            return array(
                (object) [
                    'status' => 1,
                    'message' => 'Track Deleted!'
                ]
            );

        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }
    //sf9 signatory

    //ps grade status
    public function ps_gradestatus_list_list(Request $request)
    {
        $studid = $request->get('studid');
        $sectionid = $request->get('sectionid');
        $syid = $request->get('syid');

        $students = DB::table('enrolledstud')->where('sectionid', $sectionid)->where('deleted', 0)->select('studid')->get();

        foreach ($students as $item) {
            \App\Models\PreSchool\PSGradeStatus\PSGradeStatusProcess::ps_grade_status_create($item->studid, $sectionid, $syid);
        }

        return \App\Models\PreSchool\PSGradeStatus\PSGradeStatusData::ps_grade_status_list($studid, $sectionid, $syid);
    }

    public function ps_gradestatus_list_create(Request $request)
    {
        $gsid = $request->get('gsid');
        return \App\Models\GradeSetup\GradeSetupProccess::delete_grade_setup($gsid);
    }

    public function ps_gradestatus_update(Request $request)
    {
        $studid = $request->get('studid');
        $psgradestatusid = $request->get('psgradestatusid');
        $status = $request->get('status');
        $quarter = $request->get('quarter');
        return \App\Models\PreSchool\PSGradeStatus\PSGradeStatusProcess::ps_gradestatus_update($studid, $psgradestatusid, $quarter, $status);
    }

    public function ps_gradestatus_delete(Request $request)
    {
        $gsid = $request->get('gsid');
        return \App\Models\GradeSetup\GradeSetupProccess::delete_grade_setup($gsid);
    }





}
