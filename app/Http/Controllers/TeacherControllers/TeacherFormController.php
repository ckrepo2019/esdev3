<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use TCPDF;
use \Carbon\Carbon;
use DateTime;
use Carbon\CarbonPeriod;
use App\AttendanceReport;
// use App\GenerateGrade;
use App\Models\Principal\SPP_Attendance;
use App\Models\Teacher\SchoolForm2Model;
use App\Models\Teacher\FormExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

use App\Models\Principal\Section;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_Gradelevel;
use App\Models\Principal\SPP_Subject;
use App\Models\Grading\Preschool;
use App\Models\Grading\GradeSchool;
use App\Models\Grading\HighSchool;
use App\Models\Grading\CoreValue;
use App\Models\Grading\SeniorHigh;
use App\Models\Grading\GradeStatus;
use App\Models\Subjects\Subjects;
use App\Models\Grading\PreSchoolPer;
use Crypt;
use Session;
use App\Models\Principal\GenerateGrade;

class TeacherFormController extends Controller
{
    public function index(Request $request, $formtype)
    {
        date_default_timezone_set('Asia/Manila');


        $sem = DB::table('semester')
            ->where('isactive', '1')
            ->get();
        if ($request->has('action')) {
            if ($request->has('semid')) {
                $sem = DB::table('semester')
                    ->where('id', $request->get('semid'))
                    ->get();
            }
            if ($request->get('syid') == null) {
                $syid = DB::table('sy')
                    ->where('isactive', '1')
                    ->first();
            } else {
                $syid = DB::table('sy')
                    ->where('id', $request->get('syid'))
                    ->first();
            }
        } else {
            $syid = DB::table('sy')
                ->where('isactive', '1')
                ->first();
        }

        $collectsections = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.userid',
                'sections.levelid',
                'gradelevel.levelname',
                'gradelevel.sortid',
                'sections.id as sectionid',
                'sections.sectionname',
                'academicprogram.progname',
                'academicprogram.id as acadprogid',
                'academicprogram.acadprogcode'
            )
            ->join('sectiondetail', 'teacher.id', '=', 'sectiondetail.teacherid')
            ->join('sections', 'sectiondetail.sectionid', '=', 'sections.id')
            ->join('gradelevel', 'sections.levelid', '=', 'gradelevel.id')
            ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->where('teacher.userid', auth()->user()->id)
            ->where('sectiondetail.syid', $syid->id)
            ->where('sectiondetail.deleted', '0')
            ->where('gradelevel.deleted', '0')
            ->orderBy('sortid', 'asc')
            ->get();

        if (Session::get('currentPortal') == 1) {
            $collectsections = collect($collectsections)->where('userid', auth()->user()->id)->values();
        }

        // if($request->has('acadprogid'))
        // {
        //     if($request->get('acadprogid') == 0)
        //     {
        //         $collectsections = collect($collectsections)->where('acadprogid', '!=','5')->values();
        //     }elseif($request->get('acadprogid') == 5)
        //     {
        //         $collectsections = collect($collectsections)->where('acadprogid', 5)->values();
        //     }elseif($request->get('acadprogid') == null){
        //         return 'asdasdad';
        //     }
        // } 

        $sections = array();
        if (count($collectsections) > 0) {
            foreach ($collectsections as $eachsection) {
                // return collect($eachsection);
                if (strtolower($eachsection->acadprogcode) == 'shs') {
                    // if($sem[0]->id == 1)
                    // {
                    //     $eachsection->semester = 1;
                    //     array_push($sections, $eachsection);
                    // }
                    // elseif($sem[0]->id == 2)
                    // {                    
                    // foreach(collect(DB::table('semester')->where('deleted','0')->get())->whereIn('id',[1,2])->values() as $eachsem)
                    // {
                    $pushsection = (object) [
                        'id' => $eachsection->id,
                        'userid' => $eachsection->userid,
                        'levelid' => $eachsection->levelid,
                        'sortid' => $eachsection->sortid,
                        'levelname' => $eachsection->levelname,
                        'sectionid' => $eachsection->sectionid,
                        'sectionname' => $eachsection->sectionname,
                        'progname' => $eachsection->progname,
                        'acadprogid' => $eachsection->acadprogid,
                        'acadprogcode' => $eachsection->acadprogcode,
                        'semester' => $request->get('semid')
                    ];
                    array_push($sections, $pushsection);
                    // }
                    // }

                } else {
                    $eachsection->semester = 0;
                    array_push($sections, $eachsection);
                }
            }
        }
        // return $sections;
        if (count($sections) > 0) {
            foreach ($sections as $section) {
                if (strtolower($section->acadprogcode) == 'shs') {
                    $numberofstudents = Db::table('studinfo')
                        ->select('studinfo.*', 'sh_enrolledstud.studstatus as enrolledstudstatus', 'sh_enrolledstud.strandid', 'sh_strand.strandcode', 'sh_strand.strandname')
                        // ->select('gender')
                        ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                        ->join('sh_strand', 'sh_enrolledstud.strandid', '=', 'sh_strand.id')
                        ->where('sh_enrolledstud.sectionid', $section->sectionid)
                        // ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                        ->whereIn('sh_enrolledstud.levelid', [14, 15])
                        ->where('sh_enrolledstud.studstatus', '!=', '0')
                        ->where('studinfo.deleted', '0')
                        ->where('sh_enrolledstud.deleted', '0')
                        ->where('sh_enrolledstud.studstatus', '!=', '6')
                        ->where('sh_enrolledstud.syid', $syid->id)
                        ->where('sh_enrolledstud.semid', $section->semester)
                        ->orderBy('lastname', 'asc')
                        ->get();
                } else {
                    $numberofstudents = Db::table('studinfo')
                        ->select('studinfo.*', 'enrolledstud.studstatus as enrolledstudstatus')
                        // ->select('gender')
                        ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                        ->where('enrolledstud.sectionid', $section->sectionid)
                        // ->whereIn('enrolledstud.studstatus', [1,2,4])
                        ->where('enrolledstud.studstatus', '!=', '0')
                        ->where('enrolledstud.studstatus', '!=', '6')
                        ->where('studinfo.deleted', '0')
                        ->where('enrolledstud.deleted', '0')
                        ->where('enrolledstud.syid', $syid->id)
                        ->orderBy('lastname', 'asc')
                        ->get();
                }
                // $numberofstudents = collect($numberofstudents)->unique('sid');

                $section->numberofenrolled = count(collect($numberofstudents)->where('enrolledstudstatus', '1'));
                $section->numberoflateenrolled = count(collect($numberofstudents)->where('enrolledstudstatus', '2'));
                $section->numberoftransferredin = count(collect($numberofstudents)->where('enrolledstudstatus', '4'));
                $section->numberoftransferredout = count(collect($numberofstudents)->where('enrolledstudstatus', '5'));
                $section->numberofdroppedout = count(collect($numberofstudents)->where('enrolledstudstatus', '3'));
                $section->numberofwithdraw = count(collect($numberofstudents)->where('enrolledstudstatus', '7'));
                // $numberofstudents = Db::table('studinfo')
                //     ->where('sectionid', $section->sectionid)
                //     ->get();
                $section->numberofstudents = collect($numberofstudents)->whereIn('enrolledstudstatus', [1, 2, 4])->count();
                $section->students = $numberofstudents;
            }
        }
        if (!$request->has('action')) {
            // return view('teacher.forms.index')
            return view('teacher.forms.indexv2')
                ->with('acadprogid', $request->get('acadprogid'))
                ->with('sections', $sections)
                ->with('formtype', $formtype);
        } else {
            if ($request->get('action') == 'getsections') {
                $sections = collect($sections)->sortBy('sectionname')->sortBy('sortid')->all();
                if ($formtype == 'form5a' || $formtype == 'form5b') {

                    return collect($sections)->where('acadprogid', 5)->values();
                    // if($request->get('acadprogid') == 5)
                    // {
                    //     return collect($sections)->where('semester', $sem[0]->id)->values();
                    // }else{
                    //     return $sections;
                    // }
                } else {

                    return $sections;
                }
            } elseif ($request->get('action') == 'getstrands') {
                // return $request->all();

                // return $sections;
                $strands = DB::table('sh_enrolledstud')
                    ->select('sh_strand.*')
                    ->join('sh_strand', 'sh_enrolledstud.strandid', '=', 'sh_strand.id')
                    ->where('syid', $request->get('syid'))
                    ->where('semid', $request->get('semid'))
                    ->where('levelid', $request->get('levelid'))
                    ->where('sectionid', $request->get('sectionid'))
                    ->where('sh_enrolledstud.deleted', '0')
                    ->where('sh_strand.deleted', '0')
                    ->whereIn('studstatus', [1, 2, 4])
                    ->distinct()
                    ->get();
                // return $strands;
                return collect($strands)->unique('id')->all();
                // $strands = DB::table('sh_enrolledstud')


            } else {
                if ($request->get('formtype') == 'form1') {
                    // return $request->get('levelid');
                    // $acadprogid = DB::table('gradelevel')
                    //     ->where('id',$request->get('levelid'))
                    //     ->first()->acadprogid;

                    // return $sections;
                    return view('teacher.forms.form1.filtersections')
                        ->with('sections', $sections)
                        ->with('formtype', $formtype)
                        ->with('syid', $syid->id);

                } elseif ($request->get('formtype') == 'form2') {
                    // return $sections;
                    return view('teacher.forms.form2.filtersections')
                        ->with('sections', $sections)
                        ->with('formtype', $formtype)
                        ->with('syid', $syid->id);
                } elseif ($request->get('formtype') == 'form5') {
                    // return $request->all();
                    return view('teacher.forms.form5.filtersections')
                        ->with('sections', $sections)
                        ->with('formtype', $formtype)
                        ->with('syid', $syid->id);

                } elseif ($request->get('formtype') == 'form5a') {
                    return view('teacher.forms.form5a.filtersections')
                        ->with('sections', $sections)
                        ->with('acadprogid', 5)
                        ->with('formtype', $formtype)
                        ->with('syid', $syid->id);
                } elseif ($request->get('formtype') == 'form5b') {
                    return view('teacher.forms.form5b.filtersections')
                        ->with('sections', $sections)
                        ->with('acadprogid', 5)
                        ->with('formtype', $formtype)
                        ->with('syid', $syid->id);

                } elseif ($request->get('formtype') == 'form9') {
                    // return $sections;
                    return view('teacher.forms.form9.filtersections')
                        ->with('sections', $sections)
                        ->with('formtype', $formtype)
                        ->with('syid', $syid->id);
                }
            }
        }
    }
    public function form1(Request $request)
    {
        $sectionid = $request->get('sectionid');
        $levelid = $request->get('levelid');
        if ($request->has('syid')) {
            $syid = DB::table('sy')
                ->where('id', $request->get('syid'))
                ->first();
        } elseif ($request->has('schoolyear')) {
            $syid = DB::table('sy')
                ->where('id', $request->get('schoolyear'))
                ->first();
        } else {
            $syid = DB::table('sy')
                ->where('isactive', '1')
                ->first();
        }
        $teachername = ' ';
        $getteachername = DB::table('sectiondetail')
            ->join('teacher', 'sectiondetail.teacherid', '=', 'teacher.id')
            ->where('sectiondetail.sectionid', $sectionid)
            ->where('sectiondetail.syid', $syid->id)
            ->where('sectiondetail.deleted', '0')
            ->where('teacher.deleted', '0')
            ->first();

        if ($getteachername) {
            $teachername .= $getteachername->firstname . ' ';
            if ($getteachername->middlename != null) {
                $teachername .= $getteachername->middlename[0] . '. ';
            }
            $teachername .= $getteachername->lastname . ' ';
            $teachername .= $getteachername->suffix . ' ';
        }


        $acadprog = Db::table('gradelevel')
            ->select(
                'academicprogram.id',
                'academicprogram.acadprogcode',
                'gradelevel.levelname'
            )
            ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->where('gradelevel.id', $levelid)
            ->where('gradelevel.deleted', '0')
            ->first();

        $section = Db::table('sections')
            ->where('id', $sectionid)
            ->first();

        $forms = array();

        if (strtolower($acadprog->acadprogcode) == 'shs') {

            $students = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'studinfo.sid',
                    'studinfo.lrn',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.dob',
                    'studinfo.gender',
                    DB::raw('LOWER(`gender`) as lowergender'),
                    'studinfo.contactno',
                    'studinfo.mothername',
                    'studinfo.mcontactno',
                    'studinfo.fathername',
                    'studinfo.fcontactno',
                    'studinfo.guardianname',
                    'studinfo.gcontactno',
                    'studinfo.street',
                    'studinfo.barangay',
                    'studinfo.city',
                    'studinfo.province',
                    'studinfo.guardianrelation',
                    'mothertongue.mtname',
                    'ethnic.egname',
                    'religion.religionname',
                    'studinfo.mol',
                    'modeoflearning.description as modeoflearning',
                    'studinfo.studtype',
                    //'studinfo.studstatdate',
                    'sh_enrolledstud.dateenrolled',
                    'sh_enrolledstud.studstatdate',
                    'studinfo.ismothernum',
                    'studinfo.isfathernum',
                    'studinfo.isguardannum',
                    'sh_enrolledstud.strandid',
                    'sh_enrolledstud.studstatus'
                )
                ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                ->leftJoin('mothertongue', 'studinfo.mtid', '=', 'mothertongue.id')
                ->leftJoin('ethnic', 'studinfo.egid', '=', 'ethnic.id')
                ->leftJoin('religion', 'studinfo.religionid', '=', 'religion.id')
                ->leftJoin('modeoflearning', 'studinfo.mol', '=', 'modeoflearning.id')
                ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->where('sh_enrolledstud.sectionid', $sectionid)
                ->where('sh_enrolledstud.levelid', $levelid)
                ->where('sh_enrolledstud.syid', $syid->id)
                ->where('sh_enrolledstud.semid', $request->get('semid'))
                ->where('sh_enrolledstud.studstatus', '!=', '0')
                ->where('sh_enrolledstud.studstatus', '<=', '5')
                // ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                ->where('studinfo.studstatus', '<=', '5')
                ->where('studinfo.deleted', '0')
                ->where('sh_enrolledstud.deleted', '0')
                ->whereIn('sh_enrolledstud.studstatus', [1, 2, 3, 4, 5])
                ->orderBy('lastname', 'asc')
                ->get();

            if ($request->has('strandid')) {
                if ($request->get('strandid') > 0) {
                    $students = collect($students)->where('strandid', $request->get('strandid'))->all();
                }
            }
        } else {


            $students = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'studinfo.sid',
                    'studinfo.lrn',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.dob',
                    'studinfo.gender',
                    DB::raw('LOWER(`gender`) as lowergender'),
                    'studinfo.contactno',
                    'studinfo.mothername',
                    'studinfo.mcontactno',
                    'studinfo.fathername',
                    'studinfo.fcontactno',
                    'studinfo.guardianname',
                    'studinfo.gcontactno',
                    'studinfo.street',
                    'studinfo.barangay',
                    'studinfo.city',
                    'studinfo.province',
                    'studinfo.guardianrelation',
                    'mothertongue.mtname',
                    'ethnic.egname',
                    'religion.religionname',
                    'studinfo.mol',
                    'modeoflearning.description as modeoflearning',
                    'studinfo.studtype',
                    //'studinfo.studstatdate',
                    'enrolledstud.dateenrolled',
                    'enrolledstud.studstatdate',
                    'studinfo.ismothernum',
                    'studinfo.isfathernum',
                    'studinfo.isguardannum',
                    'enrolledstud.studstatus'
                )
                ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                ->leftJoin('mothertongue', 'studinfo.mtid', '=', 'mothertongue.id')
                ->leftJoin('ethnic', 'studinfo.egid', '=', 'ethnic.id')
                ->leftJoin('religion', 'studinfo.religionid', '=', 'religion.id')
                ->leftJoin('modeoflearning', 'studinfo.mol', '=', 'modeoflearning.id')
                ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->where('enrolledstud.sectionid', $sectionid)
                ->where('enrolledstud.levelid', $levelid)
                ->where('enrolledstud.syid', $syid->id)
                ->where('enrolledstud.studstatus', '!=', '0')
                ->where('enrolledstud.studstatus', '<=', '5')
                // ->where('studinfo.studstatus','!=','6')
                // ->whereIn('enrolledstud.studstatus',[1,2,4])
                ->where('studinfo.deleted', '0')
                ->where('enrolledstud.deleted', '0')
                ->whereIn('enrolledstud.studstatus', [1, 2, 3, 4, 5])
                ->orderBy('lastname', 'asc')
                ->get();

        }
        $bosy_male = 0;
        $bosy_female = 0;
        $eosy_male = 0;
        $eosy_female = 0;
        if (count($students) > 0) {

            foreach ($students as $student) {

                if ($student->studstatdate == null) {
                    $student->studstatdate = $student->dateenrolled;
                }
                $student->sortname = $student->lastname . ' ' . $student->firstname;
                if (strtolower($student->gender) == 'male') {
                    $bosy_male += 1;
                }
                if (strtolower($student->gender) == 'female') {
                    $bosy_female += 1;
                }
                if (strtolower($student->gender) == 'male' && ($student->studstatus == 1 || $student->studstatus == 2 || $student->studstatus == 4)) {
                    $eosy_male += 1;
                }
                if (strtolower($student->gender) == 'female' && ($student->studstatus == 1 || $student->studstatus == 2 || $student->studstatus == 4)) {
                    $eosy_female += 1;
                }
                $student->lastname = ucwords(mb_convert_case($student->lastname, MB_CASE_LOWER, "UTF-8"));
                $student->middlename = ucwords(mb_convert_case($student->middlename, MB_CASE_LOWER, "UTF-8"));

                $student->dob = date('m/d/Y', strtotime($student->dob));

                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct') {
                    $today = '2021-10-31';
                } else {
                    $today = date("Y-m-d");
                }

                try {
                    $diff = date_diff(date_create($student->dob), date_create($today));

                    $student->age = $diff->format('%y');

                    $firstcomparison = ['01', '02', '03', '04', '05'];

                    if (in_array(date('m', strtotime($student->dob)), $firstcomparison)) {

                        $student->age = ((int) $student->age - 1);

                    }
                } catch (\Exception $error) {
                    $student->age = null;
                }

            }

        }
        $students = collect($students)->sortBy('sortname');

        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'schoolinfo.picurl',
                'refcitymun.citymunDesc',
                'schoolinfo.district',
                'schoolinfo.address',
                'refregion.regDesc',
                'regiontext',
                'divisiontext',
                'districttext'
            )
            ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
            ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
            ->first();

        $preparedby = Db::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        $strandinfo = DB::table('sh_strand')->where('id', $request->get('strandid'))->get() ?? array();
        $trackinfo = count($strandinfo) > 0 ? DB::table('sh_track')->where('id', $strandinfo[0]->trackid)->get() : array();
        $tvlcourse = '';
        if (count($strandinfo) > 0) {
            if (strpos($strandinfo[0]->strandcode, 'TVL') !== false) {
                $tvlcourse = $strandinfo[0]->strandname;
            }
        }
        array_push($forms, (object) array(
            'schoolinfo' => $schoolinfo,
            'schoolyear' => $syid->sydesc,
            'semester' => DB::table('semester')->where('id', $request->get('semid'))->first()->semester ?? '',
            'strandname' => $strandinfo[0]->strandname ?? '',
            'strandcode' => $strandinfo[0]->strandcode ?? '',
            'trackname' => $trackinfo[0]->trackname ?? '',
            'tvlcourse' => $tvlcourse,
            'syid' => $syid->id,
            'acadprogid' => $acadprog->id,
            'levelid' => $levelid,
            'gradelevel' => $acadprog->levelname,
            'section' => $section->sectionname,
            'preparedby' => $preparedby,
            'students' => $students,
            'teachername' => $teachername,
            'bosy_male' => $bosy_male,
            'bosy_female' => $bosy_female,
            'eosy_male' => $eosy_male,
            'eosy_female' => $eosy_female
        )
        );
        if ($request->get('action') == 'getsf1') {
            $students = collect($students)->values();
            // return $students;
            return view('teacher.forms.form1.table_sf1')
                ->with('students', $students);
            // return $request->all();
        } else {
            if ($request->get('exporttype') == 'pdf') {	//retuurn 'shabudoy';
                // $pdf = PDF::loadview('teacher/pdf/pdf_form1',compact('forms')); ;
                $pdf = PDF::loadview('teacher/pdf/pdf_form1_withlmod', compact('forms'));
                ;

                return $pdf->stream('School Form 1.pdf');
            } elseif ($request->get('exporttype') == 'excel') {
                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs cp' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                // {

                // }else
                if ($acadprog->id == 5) {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path() . '/public/excelformats/apmc/sf1_shs.xlsx');
                    $sheet = $spreadsheet->getActiveSheet();
                    $borderstyle = [
                        // 'alignment' => [
                        //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        // ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ]
                    ];
                    //$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    //$drawing->setName('Logo');
                    //$drawing->setDescription('Logo');
                    //$drawing->setPath(base_path().'/public/assets/images/deped_logo.png');
                    //$drawing->setHeight(50);
                    //$drawing->setWorksheet($sheet);
                    //$drawing->setCoordinates('BB1');
                    // $drawing->setOffsetX(20);
                    //$drawing->setOffsetY(40);



                    $sheet->setCellValue('I5', $forms[0]->schoolinfo->schoolname);
                    $sheet->setCellValue('S5', $forms[0]->schoolinfo->schoolid);
                    $sheet->setCellValue('AA5', isset($schoolinfo->districttext) ? $schoolinfo->districttext : $forms[0]->schoolinfo->district);
                    $sheet->setCellValue('AO6', isset($schoolinfo->divisiontext) ? $schoolinfo->divisiontext : $forms[0]->schoolinfo->citymunDesc);
                    $sheet->setCellValue('AW6', isset($schoolinfo->regiontext) ? $schoolinfo->regiontext : $forms[0]->schoolinfo->regDesc);


                    $sheet->setCellValue('I9', $forms[0]->semester);
                    $sheet->setCellValue('S9', $forms[0]->schoolyear);
                    $sheet->setCellValue('AD9', $forms[0]->gradelevel);
                    $sheet->setCellValue('AQ11', $forms[0]->trackname . ' - ' . $forms[0]->strandcode);

                    $sheet->setCellValue('I16', $forms[0]->section);
                    $sheet->setCellValue('T16', $forms[0]->tvlcourse);

                    // $sheet->setCellValue('S9',$forms[0]->schoolyear);
                    // $sheet->setCellValue('AD9',$forms[0]->gradelevel);
                    // $sheet->setCellValue('I9',DB::table('semester')->where('id',$request->get('semid'))->first()->semester);
                    // $sheet->setCellValue('I16',$forms[0]->section);

                    $trackandstrand = DB::table('sh_strand')
                        ->join('sh_track', 'sh_strand.trackid', '=', 'sh_track.id')
                        ->select('sh_strand.*', 'sh_track.trackname')
                        ->first();

                    $sheet->setCellValue('AQ11', $trackandstrand->trackname . ' - ' . $trackandstrand->strandcode);


                    $sheet->setCellValue('A21', collect($forms[0]->students)->where('lowergender', 'male')->count());
                    $sheet->setCellValue('A23', collect($forms[0]->students)->where('lowergender', 'female')->count());
                    $sheet->setCellValue('A24', collect($forms[0]->students)->count());
                    $sheet->setCellValue('AC28', $bosy_male);
                    $sheet->setCellValue('AF28', $eosy_male);
                    $sheet->setCellValue('AC31', $bosy_female);
                    $sheet->setCellValue('AC34', $bosy_male + $bosy_female);
                    $sheet->setCellValue('AF31', $eosy_female);
                    $sheet->setCellValue('AF34', $eosy_male + $eosy_female);
                    $sheet->setCellValue('AM27', $teachername);
                    // $sheet->setCellValue('AJ14',DB::table('schoolinfo')->first()->authorized);
                    $sheet->setCellValue('A37', 'Generated on: ' . date('l, F d, Y'));


                    // ================================  TABLE HEADER     
                    $malecount = 0;
                    $femalecount = 0;
                    $cellcount = 20;
                    $contactno = null;

                    if (count($forms[0]->students) > 0) {
                        foreach (collect($forms[0]->students)->where('lowergender', 'male')->values() as $malekey => $male) {
                            if ($malekey == 0) {
                                $sheet->insertNewRowBefore(($cellcount + 1), 1);
                            }
                            if ($male->ismothernum == 1) {
                                $contactno = $male->mcontactno;
                            }
                            if ($male->isfathernum == 1) {
                                $contactno = $male->fcontactno;
                            }
                            if ($male->isguardannum == 1) {
                                $contactno = $male->gcontactno;
                            }
                            $sheet->mergeCells('A' . $cellcount . ':B' . $cellcount); //lrn
                            $sheet->mergeCells('C' . $cellcount . ':J' . $cellcount); //name
                            $sheet->mergeCells('L' . $cellcount . ':N' . $cellcount); //bdate
                            $sheet->mergeCells('O' . $cellcount . ':P' . $cellcount); //age
                            $sheet->mergeCells('Q' . $cellcount . ':T' . $cellcount); //religion
                            $sheet->mergeCells('U' . $cellcount . ':Y' . $cellcount); //street
                            $sheet->mergeCells('Z' . $cellcount . ':AD' . $cellcount); //barangay
                            $sheet->mergeCells('AE' . $cellcount . ':AF' . $cellcount); //city
                            $sheet->mergeCells('AG' . $cellcount . ':AJ' . $cellcount); //province
                            $sheet->mergeCells('AK' . $cellcount . ':AO' . $cellcount); //father's name
                            $sheet->mergeCells('AP' . $cellcount . ':AQ' . $cellcount); //mother's name
                            $sheet->mergeCells('AR' . $cellcount . ':AU' . $cellcount); //guardianname
                            $sheet->mergeCells('AV' . $cellcount . ':AW' . $cellcount); //guardian relation
                            $sheet->mergeCells('AY' . $cellcount . ':AZ' . $cellcount); //guardian contactnum
                            $sheet->mergeCells('BA' . $cellcount . ':BB' . $cellcount); //learning modality
                            $sheet->mergeCells('BC' . $cellcount . ':BJ' . $cellcount); //remarks

                            // $sheet->setCellValue('A'.$cellcount, $malecount);
                            $sheet->setCellValue('A' . $cellcount, $male->lrn . ' ');
                            $sheet->setCellValue('C' . $cellcount, $male->lastname . ', ' . $male->firstname . ' ' . $male->suffix . ' ' . $male->middlename);
                            $sheet->setCellValue('K' . $cellcount, $male->gender[0]);
                            $sheet->setCellValue('L' . $cellcount, $male->dob);
                            $sheet->setCellValue('O' . $cellcount, $male->age);
                            $sheet->setCellValue('Q' . $cellcount, $male->religionname);
                            $sheet->setCellValue('U' . $cellcount, $male->street);
                            $sheet->setCellValue('Z' . $cellcount, $male->barangay);
                            $sheet->setCellValue('AE' . $cellcount, $male->city);
                            $sheet->setCellValue('AG' . $cellcount, $male->province);
                            $sheet->setCellValue('AK' . $cellcount, strtoupper($male->fathername));
                            $sheet->setCellValue('AP' . $cellcount, strtoupper($male->mothername));
                            $sheet->setCellValue('AR' . $cellcount, $male->guardianname);
                            $sheet->setCellValue('AV' . $cellcount, $male->guardianrelation);
                            $sheet->setCellValue('AY' . $cellcount, $contactno);
                            $sheet->setCellValue('BA' . $cellcount, $male->modeoflearning);

                            if ($male->studstatus == 3 || $male->studstatus == 5) {
                                $sheet->setCellValue('BC' . $cellcount, DB::table('studentstatus')->where('id', $male->studstatus)->first()->description);
                            }

                            $sheet->getStyle('A' . $cellcount . ':BC' . $cellcount)->applyFromArray($borderstyle);
                            if (isset(collect($forms[0]->students)->where('lowergender', 'male')->values()[$malekey + 2])) {
                                $sheet->insertNewRowBefore(($cellcount + 1), 1);
                            } else {
                                // return collect($forms[0]->students)->where('lowergender','male')->count();
                                // return $cellcount;
                            }
                            $malecount += 1;
                            $cellcount += 1;
                        }
                        $cellcount += 1;
                        foreach (collect($forms[0]->students)->where('lowergender', 'female')->values() as $femalekey => $female) {
                            if ($femalekey == 0) {

                                $sheet->insertNewRowBefore(($cellcount + 1), 1);
                            }
                            if ($female->ismothernum == 1) {
                                $contactno = $female->mcontactno;
                            }
                            if ($female->isfathernum == 1) {
                                $contactno = $female->fcontactno;
                            }
                            if ($female->isguardannum == 1) {
                                $contactno = $female->gcontactno;
                            }
                            $sheet->mergeCells('A' . $cellcount . ':B' . $cellcount); //lrn
                            $sheet->mergeCells('C' . $cellcount . ':J' . $cellcount); //name
                            $sheet->mergeCells('L' . $cellcount . ':N' . $cellcount); //bdate
                            $sheet->mergeCells('O' . $cellcount . ':P' . $cellcount); //age
                            $sheet->mergeCells('Q' . $cellcount . ':T' . $cellcount); //religion
                            $sheet->mergeCells('U' . $cellcount . ':Y' . $cellcount); //street
                            $sheet->mergeCells('Z' . $cellcount . ':AD' . $cellcount); //barangay
                            $sheet->mergeCells('AE' . $cellcount . ':AF' . $cellcount); //city
                            $sheet->mergeCells('AG' . $cellcount . ':AJ' . $cellcount); //province
                            $sheet->mergeCells('AK' . $cellcount . ':AO' . $cellcount); //father's name
                            $sheet->mergeCells('AP' . $cellcount . ':AQ' . $cellcount); //mother's name
                            $sheet->mergeCells('AR' . $cellcount . ':AU' . $cellcount); //guardianname
                            $sheet->mergeCells('AV' . $cellcount . ':AW' . $cellcount); //guardian relation
                            $sheet->mergeCells('AY' . $cellcount . ':AZ' . $cellcount); //guardian contactnum
                            $sheet->mergeCells('BA' . $cellcount . ':BB' . $cellcount); //learning modality
                            $sheet->mergeCells('BC' . $cellcount . ':BJ' . $cellcount); //remarks

                            // $sheet->setCellValue('A'.$cellcount, $malecount);
                            $sheet->setCellValue('A' . $cellcount, $female->lrn . ' ');
                            $sheet->setCellValue('C' . $cellcount, $female->lastname . ', ' . $female->firstname . ' ' . $female->suffix . ' ' . $female->middlename);
                            $sheet->setCellValue('K' . $cellcount, $female->gender[0]);
                            $sheet->setCellValue('L' . $cellcount, $female->dob);
                            $sheet->setCellValue('O' . $cellcount, $female->age);
                            $sheet->setCellValue('Q' . $cellcount, $female->religionname);
                            $sheet->setCellValue('U' . $cellcount, $female->street);
                            $sheet->setCellValue('Z' . $cellcount, $female->barangay);
                            $sheet->setCellValue('AE' . $cellcount, $female->city);
                            $sheet->setCellValue('AG' . $cellcount, $female->province);
                            $sheet->setCellValue('AK' . $cellcount, strtoupper($female->fathername));
                            $sheet->setCellValue('AP' . $cellcount, strtoupper($female->mothername));
                            $sheet->setCellValue('AR' . $cellcount, $female->guardianname);
                            $sheet->setCellValue('AV' . $cellcount, $female->guardianrelation);
                            $sheet->setCellValue('AY' . $cellcount, $contactno);
                            $sheet->setCellValue('BA' . $cellcount, $female->modeoflearning);

                            if ($female->studstatus == 3 || $female->studstatus == 5) {
                                $sheet->setCellValue('BC' . $cellcount, DB::table('studentstatus')->where('id', $female->studstatus)->first()->description);
                            }

                            $sheet->getStyle('A' . $cellcount . ':BC' . $cellcount)->applyFromArray($borderstyle);
                            if (isset(collect($forms[0]->students)->where('lowergender', 'female')->values()[$femalekey + 2])) {
                                $sheet->insertNewRowBefore(($cellcount + 1), 1);
                            }
                            $femalecount += 1;
                            $cellcount += 1;
                        }

                    }


                    $sheet->getpageSetup()->setFitToPage(true);
                    $sheet->getpageSetup()->setFitToWidth(1);
                    $sheet->getpageSetup()->setFitToHeight(0);
                    $sheet->getSheetView()->setZoomScale(85);
                    $sheet->getpageSetup()->setPrintArea('A1:BJ' . (count($forms[0]->students) + 23));
                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="School Form 1 ' . $forms[0]->gradelevel . ' - ' . $forms[0]->section . '.xlsx"');
                    $writer->save("php://output");
                    exit();
                } else {
                    if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs cp' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs') {
                        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path() . '/public/excelformats/schoolform1_withlmod.xlsx');
                        $sheet = $spreadsheet->getActiveSheet();
                        $borderstyle = [
                            // 'alignment' => [
                            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                            // ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                ],
                            ]
                        ];

                        $sheet->setCellValue('F4', $forms[0]->schoolinfo->schoolid);
                        $sheet->setCellValue('H4', $forms[0]->schoolinfo->regDesc);
                        $sheet->setCellValue('N4', $forms[0]->schoolinfo->citymunDesc);
                        $sheet->setCellValue('U4', $forms[0]->schoolinfo->district);
                        $sheet->setCellValue('F6', $forms[0]->schoolinfo->schoolname);
                        $sheet->setCellValue('P6', $forms[0]->schoolyear);
                        $sheet->setCellValue('U6', $forms[0]->gradelevel);
                        $sheet->setCellValue('X6', $forms[0]->section);

                        // ================================  TABLE HEADER     
                        $malecount = 1;
                        $femalecount = 1;
                        $cellcount = 11;
                        $contactno = null;
                        if (count($forms[0]->students) > 0) {
                            // ================================  MALE STUDENTS     
                            // $malestudents = collect($forms[0]->students->where('gender','MALE'));
                            foreach ($forms[0]->students as $male) {
                                if (strtolower($male->gender) == 'male') {
                                    $sheet->insertNewRowBefore($cellcount, 1);
                                    if ($male->ismothernum == 1) {
                                        $contactno = $male->mcontactno;
                                    }
                                    if ($male->isfathernum == 1) {
                                        $contactno = $male->fcontactno;
                                    }
                                    if ($male->isguardannum == 1) {
                                        $contactno = $male->gcontactno;
                                    }
                                    $sheet->setCellValue('A' . $cellcount, $malecount);
                                    $sheet->setCellValue('B' . $cellcount, $male->lrn . ' ');
                                    $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                                    $sheet->setCellValue('C' . $cellcount, $male->lastname . ', ' . $male->firstname . ' ' . $male->suffix . ' ' . $male->middlename);
                                    $sheet->setCellValue('G' . $cellcount, $male->gender[0]);
                                    $sheet->setCellValue('H' . $cellcount, $male->dob);
                                    $sheet->setCellValue('I' . $cellcount, $male->age);
                                    $sheet->setCellValue('L' . $cellcount, $male->mtname);
                                    $sheet->setCellValue('M' . $cellcount, $male->egname);
                                    $sheet->setCellValue('N' . $cellcount, $male->religionname);
                                    $sheet->setCellValue('O' . $cellcount, $male->street);
                                    $sheet->setCellValue('P' . $cellcount, $male->barangay);
                                    $sheet->setCellValue('Q' . $cellcount, $male->city);
                                    $sheet->mergeCells('R' . $cellcount . ':S' . $cellcount);
                                    $sheet->setCellValue('S' . $cellcount, $male->province);
                                    $sheet->mergeCells('T' . $cellcount . ':U' . $cellcount);
                                    $sheet->setCellValue('T' . $cellcount, strtoupper($male->fathername));
                                    $sheet->mergeCells('V' . $cellcount . ':W' . $cellcount);
                                    $sheet->setCellValue('V' . $cellcount, strtoupper($male->mothername));
                                    $sheet->setCellValue('X' . $cellcount, $male->guardianname);
                                    $sheet->setCellValue('Y' . $cellcount, $male->guardianrelation);
                                    $sheet->setCellValue('Z' . $cellcount, $contactno);

                                    if (count(DB::table('modeoflearning')->where('id', $male->mol)->get()) > 0) {
                                        $sheet->setCellValue('AA' . $cellcount, DB::table('modeoflearning')->where('id', $male->mol)->first()->description);
                                    }

                                    $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                                    $malecount += 1;
                                    $cellcount += 1;
                                }
                            }

                            $sheet->insertNewRowBefore($cellcount, 1);
                            $sheet->setCellValue('A' . $cellcount, '');
                            $sheet->setCellValue('B' . $cellcount, ($malecount - 1));
                            $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                            $sheet->setCellValue('C' . $cellcount, ' == TOTAL MALE');
                            $sheet->setCellValue('G' . $cellcount, '');
                            $sheet->setCellValue('H' . $cellcount, '');
                            $sheet->setCellValue('I' . $cellcount, '');
                            $sheet->setCellValue('L' . $cellcount, '');
                            $sheet->setCellValue('M' . $cellcount, '');
                            $sheet->setCellValue('N' . $cellcount, '');
                            $sheet->setCellValue('O' . $cellcount, '');
                            $sheet->setCellValue('P' . $cellcount, '');
                            $sheet->setCellValue('Q' . $cellcount, '');
                            $sheet->mergeCells('R' . $cellcount . ':S' . $cellcount);
                            $sheet->setCellValue('S' . $cellcount, '');
                            $sheet->mergeCells('T' . $cellcount . ':U' . $cellcount);
                            $sheet->setCellValue('T' . $cellcount, '');
                            $sheet->mergeCells('V' . $cellcount . ':W' . $cellcount);
                            $sheet->setCellValue('V' . $cellcount, '');
                            $sheet->setCellValue('X' . $cellcount, '');
                            $sheet->setCellValue('Y' . $cellcount, '');
                            $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                            $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                            $cellcount += 1;

                            // ================================  FEMALE STUDENTS     

                            foreach ($forms[0]->students as $female) {
                                if (strtolower($female->gender) == 'female') {
                                    $sheet->insertNewRowBefore($cellcount, 1);
                                    if ($female->ismothernum == 1) {
                                        $contactno = $female->mcontactno;
                                    }
                                    if ($female->isfathernum == 1) {
                                        $contactno = $female->fcontactno;
                                    }
                                    if ($female->isguardannum == 1) {
                                        $contactno = $female->gcontactno;
                                    }
                                    $sheet->setCellValue('A' . $cellcount, $femalecount);
                                    $sheet->setCellValue('B' . $cellcount, $female->lrn . ' ');
                                    $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                                    $sheet->setCellValue('C' . $cellcount, $female->lastname . ', ' . $female->firstname . ' ' . $female->suffix . ' ' . $female->middlename);
                                    $sheet->setCellValue('G' . $cellcount, $female->gender[0]);
                                    $sheet->setCellValue('H' . $cellcount, $female->dob);
                                    $sheet->setCellValue('I' . $cellcount, $female->age);
                                    $sheet->setCellValue('L' . $cellcount, $female->mtname);
                                    $sheet->setCellValue('M' . $cellcount, $female->egname);
                                    $sheet->setCellValue('N' . $cellcount, $female->religionname);
                                    $sheet->setCellValue('O' . $cellcount, $female->street);
                                    $sheet->setCellValue('P' . $cellcount, $female->barangay);
                                    $sheet->setCellValue('Q' . $cellcount, $female->city);
                                    $sheet->mergeCells('R' . $cellcount . ':S' . $cellcount);
                                    $sheet->setCellValue('S' . $cellcount, $female->province);
                                    $sheet->mergeCells('T' . $cellcount . ':U' . $cellcount);
                                    $sheet->setCellValue('T' . $cellcount, strtoupper($female->fathername));
                                    $sheet->mergeCells('V' . $cellcount . ':W' . $cellcount);
                                    $sheet->setCellValue('V' . $cellcount, strtoupper($female->mothername));
                                    $sheet->setCellValue('X' . $cellcount, $female->guardianname);
                                    $sheet->setCellValue('Y' . $cellcount, $female->guardianrelation);
                                    $sheet->setCellValue('Z' . $cellcount, $contactno);

                                    if (count(DB::table('modeoflearning')->where('id', $male->mol)->get()) > 0) {
                                        $sheet->setCellValue('AA' . $cellcount, DB::table('modeoflearning')->where('id', $male->mol)->first()->description);
                                    }

                                    $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                                    $femalecount += 1;
                                    $cellcount += 1;
                                }
                            }
                            $sheet->insertNewRowBefore($cellcount, 1);
                            $sheet->setCellValue('A' . $cellcount, '');
                            $sheet->setCellValue('B' . $cellcount, ($femalecount - 1));
                            $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                            $sheet->setCellValue('C' . $cellcount, ' == TOTAL FEMALE');
                            $sheet->setCellValue('G' . $cellcount, '');
                            $sheet->setCellValue('H' . $cellcount, '');
                            $sheet->setCellValue('I' . $cellcount, '');
                            $sheet->setCellValue('L' . $cellcount, '');
                            $sheet->setCellValue('M' . $cellcount, '');
                            $sheet->setCellValue('N' . $cellcount, '');
                            $sheet->setCellValue('O' . $cellcount, '');
                            $sheet->setCellValue('P' . $cellcount, '');
                            $sheet->setCellValue('Q' . $cellcount, '');
                            $sheet->mergeCells('R' . $cellcount . ':S' . $cellcount);
                            $sheet->setCellValue('S' . $cellcount, '');
                            $sheet->mergeCells('T' . $cellcount . ':U' . $cellcount);
                            $sheet->setCellValue('T' . $cellcount, '');
                            $sheet->mergeCells('V' . $cellcount . ':W' . $cellcount);
                            $sheet->setCellValue('V' . $cellcount, '');
                            $sheet->setCellValue('X' . $cellcount, '');
                            $sheet->setCellValue('Y' . $cellcount, '');
                            $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                            $cellcount += 1;
                        }
                        $sheet->insertNewRowBefore($cellcount, 1);
                        $sheet->setCellValue('A' . $cellcount, '');
                        $sheet->setCellValue('B' . $cellcount, (($femalecount - 1) + ($malecount - 1)));
                        $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                        $sheet->setCellValue('C' . $cellcount, ' == COMBINED');
                        $sheet->setCellValue('G' . $cellcount, '');
                        $sheet->setCellValue('H' . $cellcount, '');
                        $sheet->setCellValue('I' . $cellcount, '');
                        $sheet->setCellValue('L' . $cellcount, '');
                        $sheet->setCellValue('M' . $cellcount, '');
                        $sheet->setCellValue('N' . $cellcount, '');
                        $sheet->setCellValue('O' . $cellcount, '');
                        $sheet->setCellValue('P' . $cellcount, '');
                        $sheet->setCellValue('Q' . $cellcount, '');
                        $sheet->mergeCells('R' . $cellcount . ':S' . $cellcount);
                        $sheet->setCellValue('S' . $cellcount, '');
                        $sheet->mergeCells('T' . $cellcount . ':U' . $cellcount);
                        $sheet->setCellValue('T' . $cellcount, '');
                        $sheet->mergeCells('V' . $cellcount . ':W' . $cellcount);
                        $sheet->setCellValue('V' . $cellcount, '');
                        $sheet->setCellValue('X' . $cellcount, '');
                        $sheet->setCellValue('Y' . $cellcount, '');
                        $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);

                    } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {
                        if ($acadprog->id == 5) {
                            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path() . '/public/excelformats/apmc/sf1_shs.xls');
                            $sheet = $spreadsheet->getActiveSheet();
                            $borderstyle = [
                                // 'alignment' => [
                                //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                                // ],
                                'borders' => [
                                    'top' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                ]
                            ];

                            $sheet->setCellValue('S9', $forms[0]->schoolyear);
                            $sheet->setCellValue('AD9', $forms[0]->gradelevel);
                            $sheet->setCellValue('I9', DB::table('semester')->where('id', $request->get('semid'))->first()->semester);
                            $sheet->setCellValue('I16', $forms[0]->section);

                            $trackandstrand = DB::table('sh_strand')
                                ->join('sh_track', 'sh_strand.trackid', '=', 'sh_track.id')
                                ->select('sh_strand.*', 'sh_track.trackname')
                                ->first();

                            $sheet->setCellValue('AQ11', $trackandstrand->trackname . ' - ' . $trackandstrand->strandcode);


                            $sheet->setCellValue('A21', collect($forms[0]->students)->where('lowergender', 'male')->count());
                            $sheet->setCellValue('A23', collect($forms[0]->students)->where('lowergender', 'female')->count());
                            $sheet->setCellValue('A24', collect($forms[0]->students)->count());
                            $sheet->setCellValue('AC28', $bosy_male);
                            $sheet->setCellValue('AF28', $eosy_male);
                            $sheet->setCellValue('AC31', $bosy_female);
                            $sheet->setCellValue('AC34', $bosy_male + $bosy_female);
                            $sheet->setCellValue('AF31', $eosy_female);
                            $sheet->setCellValue('AF34', $eosy_male + $eosy_female);
                            $sheet->setCellValue('AM27', $teachername);
                            // $sheet->setCellValue('AJ14',DB::table('schoolinfo')->first()->authorized);
                            $sheet->setCellValue('A37', 'Generated on: ' . date('l, F d, Y'));


                            // ================================  TABLE HEADER     
                            $malecount = 0;
                            $femalecount = 0;
                            $cellcount = 20;
                            $contactno = null;

                            if (count($forms[0]->students) > 0) {
                                foreach (collect($forms[0]->students)->where('lowergender', 'male')->values() as $malekey => $male) {
                                    if ($malekey == 0) {
                                        $sheet->insertNewRowBefore(($cellcount + 1), 1);
                                    }
                                    if ($male->ismothernum == 1) {
                                        $contactno = $male->mcontactno;
                                    }
                                    if ($male->isfathernum == 1) {
                                        $contactno = $male->fcontactno;
                                    }
                                    if ($male->isguardannum == 1) {
                                        $contactno = $male->gcontactno;
                                    }
                                    $sheet->mergeCells('A' . $cellcount . ':B' . $cellcount); //lrn
                                    $sheet->mergeCells('C' . $cellcount . ':J' . $cellcount); //name
                                    $sheet->mergeCells('L' . $cellcount . ':N' . $cellcount); //bdate
                                    $sheet->mergeCells('O' . $cellcount . ':P' . $cellcount); //age
                                    $sheet->mergeCells('Q' . $cellcount . ':T' . $cellcount); //religion
                                    $sheet->mergeCells('U' . $cellcount . ':Y' . $cellcount); //street
                                    $sheet->mergeCells('Z' . $cellcount . ':AD' . $cellcount); //barangay
                                    $sheet->mergeCells('AE' . $cellcount . ':AF' . $cellcount); //city
                                    $sheet->mergeCells('AG' . $cellcount . ':AJ' . $cellcount); //province
                                    $sheet->mergeCells('AK' . $cellcount . ':AO' . $cellcount); //father's name
                                    $sheet->mergeCells('AP' . $cellcount . ':AQ' . $cellcount); //mother's name
                                    $sheet->mergeCells('AR' . $cellcount . ':AU' . $cellcount); //guardianname
                                    $sheet->mergeCells('AV' . $cellcount . ':AW' . $cellcount); //guardian relation
                                    $sheet->mergeCells('AY' . $cellcount . ':AZ' . $cellcount); //guardian contactnum
                                    $sheet->mergeCells('BA' . $cellcount . ':BB' . $cellcount); //learning modality
                                    $sheet->mergeCells('BC' . $cellcount . ':BJ' . $cellcount); //remarks

                                    // $sheet->setCellValue('A'.$cellcount, $malecount);
                                    $sheet->setCellValue('A' . $cellcount, $male->lrn . ' ');
                                    $sheet->setCellValue('C' . $cellcount, $male->lastname . ', ' . $male->firstname . ' ' . $male->suffix . ' ' . $male->middlename);
                                    $sheet->setCellValue('K' . $cellcount, $male->gender[0]);
                                    $sheet->setCellValue('L' . $cellcount, $male->dob);
                                    $sheet->setCellValue('O' . $cellcount, $male->age);
                                    $sheet->setCellValue('Q' . $cellcount, $male->religionname);
                                    $sheet->setCellValue('U' . $cellcount, $male->street);
                                    $sheet->setCellValue('Z' . $cellcount, $male->barangay);
                                    $sheet->setCellValue('AE' . $cellcount, $male->city);
                                    $sheet->setCellValue('AG' . $cellcount, $male->province);
                                    $sheet->setCellValue('AK' . $cellcount, strtoupper($male->fathername));
                                    $sheet->setCellValue('AP' . $cellcount, strtoupper($male->mothername));
                                    $sheet->setCellValue('AR' . $cellcount, $male->guardianname);
                                    $sheet->setCellValue('AV' . $cellcount, $male->guardianrelation);
                                    $sheet->setCellValue('AY' . $cellcount, $contactno);
                                    $sheet->setCellValue('BA' . $cellcount, $male->modeoflearning);

                                    if ($male->studstatus == 3 || $male->studstatus == 5) {
                                        $sheet->setCellValue('BC' . $cellcount, DB::table('studentstatus')->where('id', $male->studstatus)->first()->description);
                                    }

                                    $sheet->getStyle('A' . $cellcount . ':BC' . $cellcount)->applyFromArray($borderstyle);
                                    if (isset(collect($forms[0]->students)->where('lowergender', 'male')->values()[$malekey + 2])) {
                                        $sheet->insertNewRowBefore(($cellcount + 1), 1);
                                    } else {
                                        // return collect($forms[0]->students)->where('lowergender','male')->count();
                                        // return $cellcount;
                                    }
                                    $malecount += 1;
                                    $cellcount += 1;
                                }
                                $cellcount += 1;
                                foreach (collect($forms[0]->students)->where('lowergender', 'female')->values() as $femalekey => $female) {
                                    if ($femalekey == 0) {

                                        $sheet->insertNewRowBefore(($cellcount + 1), 1);
                                    }
                                    if ($female->ismothernum == 1) {
                                        $contactno = $female->mcontactno;
                                    }
                                    if ($female->isfathernum == 1) {
                                        $contactno = $female->fcontactno;
                                    }
                                    if ($female->isguardannum == 1) {
                                        $contactno = $female->gcontactno;
                                    }
                                    $sheet->mergeCells('A' . $cellcount . ':B' . $cellcount); //lrn
                                    $sheet->mergeCells('C' . $cellcount . ':J' . $cellcount); //name
                                    $sheet->mergeCells('L' . $cellcount . ':N' . $cellcount); //bdate
                                    $sheet->mergeCells('O' . $cellcount . ':P' . $cellcount); //age
                                    $sheet->mergeCells('Q' . $cellcount . ':T' . $cellcount); //religion
                                    $sheet->mergeCells('U' . $cellcount . ':Y' . $cellcount); //street
                                    $sheet->mergeCells('Z' . $cellcount . ':AD' . $cellcount); //barangay
                                    $sheet->mergeCells('AE' . $cellcount . ':AF' . $cellcount); //city
                                    $sheet->mergeCells('AG' . $cellcount . ':AJ' . $cellcount); //province
                                    $sheet->mergeCells('AK' . $cellcount . ':AO' . $cellcount); //father's name
                                    $sheet->mergeCells('AP' . $cellcount . ':AQ' . $cellcount); //mother's name
                                    $sheet->mergeCells('AR' . $cellcount . ':AU' . $cellcount); //guardianname
                                    $sheet->mergeCells('AV' . $cellcount . ':AW' . $cellcount); //guardian relation
                                    $sheet->mergeCells('AY' . $cellcount . ':AZ' . $cellcount); //guardian contactnum
                                    $sheet->mergeCells('BA' . $cellcount . ':BB' . $cellcount); //learning modality
                                    $sheet->mergeCells('BC' . $cellcount . ':BJ' . $cellcount); //remarks

                                    // $sheet->setCellValue('A'.$cellcount, $malecount);
                                    $sheet->setCellValue('A' . $cellcount, $female->lrn . ' ');
                                    $sheet->setCellValue('C' . $cellcount, $female->lastname . ', ' . $female->firstname . ' ' . $female->suffix . ' ' . $female->middlename);
                                    $sheet->setCellValue('K' . $cellcount, $female->gender[0]);
                                    $sheet->setCellValue('L' . $cellcount, $female->dob);
                                    $sheet->setCellValue('O' . $cellcount, $female->age);
                                    $sheet->setCellValue('Q' . $cellcount, $female->religionname);
                                    $sheet->setCellValue('U' . $cellcount, $female->street);
                                    $sheet->setCellValue('Z' . $cellcount, $female->barangay);
                                    $sheet->setCellValue('AE' . $cellcount, $female->city);
                                    $sheet->setCellValue('AG' . $cellcount, $female->province);
                                    $sheet->setCellValue('AK' . $cellcount, strtoupper($female->fathername));
                                    $sheet->setCellValue('AP' . $cellcount, strtoupper($female->mothername));
                                    $sheet->setCellValue('AR' . $cellcount, $female->guardianname);
                                    $sheet->setCellValue('AV' . $cellcount, $female->guardianrelation);
                                    $sheet->setCellValue('AY' . $cellcount, $contactno);
                                    $sheet->setCellValue('BA' . $cellcount, $female->modeoflearning);

                                    if ($female->studstatus == 3 || $female->studstatus == 5) {
                                        $sheet->setCellValue('BC' . $cellcount, DB::table('studentstatus')->where('id', $female->studstatus)->first()->description);
                                    }

                                    $sheet->getStyle('A' . $cellcount . ':BC' . $cellcount)->applyFromArray($borderstyle);
                                    if (isset(collect($forms[0]->students)->where('lowergender', 'female')->values()[$femalekey + 2])) {
                                        $sheet->insertNewRowBefore(($cellcount + 1), 1);
                                    }
                                    $femalecount += 1;
                                    $cellcount += 1;
                                }

                            }


                            $sheet->getpageSetup()->setFitToPage(true);
                            $sheet->getpageSetup()->setFitToWidth(1);
                            $sheet->getpageSetup()->setFitToHeight(0);
                            $sheet->getSheetView()->setZoomScale(85);
                            $sheet->getpageSetup()->setPrintArea('A1:BJ' . (count($forms[0]->students) + 23));
                            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                            header('Content-Type: application/vnd.ms-excel');
                            header('Content-Disposition: attachment; filename="School Form 1 ' . $forms[0]->gradelevel . ' - ' . $forms[0]->section . '.xls"');
                            $writer->save("php://output");
                            exit;
                        } else {
                            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path() . '/public/excelformats/apmc/sf1_jhs.xls');
                            $sheet = $spreadsheet->getActiveSheet();
                            $borderstyle = [
                                // 'alignment' => [
                                //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                                // ],
                                'borders' => [
                                    'top' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                ]
                            ];

                            $sheet->setCellValue('S4', $forms[0]->schoolyear);
                            $sheet->setCellValue('AB4', $forms[0]->gradelevel);
                            $sheet->setCellValue('AH4', $forms[0]->section);


                            $sheet->setCellValue('Y14', $bosy_male);
                            $sheet->setCellValue('Z14', $eosy_male);
                            $sheet->setCellValue('Y17', $bosy_female);
                            $sheet->setCellValue('Z17', $eosy_female);
                            $sheet->setCellValue('AD14', $teachername);
                            $sheet->setCellValue('AJ14', DB::table('schoolinfo')->first()->authorized);
                            $sheet->setCellValue('A23', 'Generated on: ' . date('l, F d, Y'));


                            // ================================  TABLE HEADER     
                            $malecount = 0;
                            $femalecount = 0;
                            $cellcount = 9;
                            $contactno = null;
                            if (count($forms[0]->students) > 0) {
                                foreach (collect($forms[0]->students)->where('lowergender', 'female')->values() as $femalekey => $female) {
                                    if ($femalekey == 0) {

                                        $sheet->insertNewRowBefore(($cellcount + 1), 1);
                                    }
                                    if ($female->ismothernum == 1) {
                                        $contactno = $female->mcontactno;
                                    }
                                    if ($female->isfathernum == 1) {
                                        $contactno = $female->fcontactno;
                                    }
                                    if ($female->isguardannum == 1) {
                                        $contactno = $female->gcontactno;
                                    }
                                    $sheet->mergeCells('A' . $cellcount . ':B' . $cellcount);
                                    $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                                    $sheet->mergeCells('H' . $cellcount . ':I' . $cellcount);
                                    $sheet->mergeCells('J' . $cellcount . ':K' . $cellcount);
                                    $sheet->mergeCells('L' . $cellcount . ':M' . $cellcount);
                                    $sheet->mergeCells('Q' . $cellcount . ':T' . $cellcount);
                                    $sheet->mergeCells('U' . $cellcount . ':V' . $cellcount);
                                    $sheet->mergeCells('X' . $cellcount . ':Z' . $cellcount);
                                    $sheet->mergeCells('AB' . $cellcount . ':AD' . $cellcount);
                                    $sheet->mergeCells('AF' . $cellcount . ':AG' . $cellcount);
                                    $sheet->mergeCells('AH' . $cellcount . ':AJ' . $cellcount);
                                    $sheet->mergeCells('AL' . $cellcount . ':AM' . $cellcount);

                                    // $sheet->setCellValue('A'.$cellcount, $malecount);
                                    $sheet->setCellValue('A' . $cellcount, $female->lrn . ' ');
                                    $sheet->setCellValue('C' . $cellcount, $female->lastname . ', ' . $female->firstname . ' ' . $female->suffix . ' ' . $female->middlename);
                                    $sheet->setCellValue('G' . $cellcount, $female->gender[0]);
                                    $sheet->setCellValue('H' . $cellcount, $female->dob);
                                    $sheet->setCellValue('J' . $cellcount, $female->age);
                                    $sheet->setCellValue('L' . $cellcount, $female->mtname);
                                    $sheet->setCellValue('N' . $cellcount, $female->egname);
                                    $sheet->setCellValue('O' . $cellcount, $female->religionname);
                                    $sheet->setCellValue('P' . $cellcount, $female->street);
                                    $sheet->setCellValue('Q' . $cellcount, $female->barangay);
                                    $sheet->setCellValue('U' . $cellcount, $female->city);
                                    $sheet->setCellValue('X' . $cellcount, $female->province);
                                    $sheet->setCellValue('AB' . $cellcount, strtoupper($female->fathername));
                                    $sheet->setCellValue('AF' . $cellcount, strtoupper($female->mothername));
                                    $sheet->setCellValue('AH' . $cellcount, $female->guardianname);
                                    $sheet->setCellValue('AK' . $cellcount, $female->guardianrelation);
                                    $sheet->setCellValue('AL' . $cellcount, $contactno);

                                    if ($female->studstatus == 3 || $female->studstatus == 5) {
                                        $sheet->setCellValue('AN' . $cellcount, DB::table('studentstatus')->where('id', $female->studstatus)->first()->description);
                                    }

                                    $sheet->getStyle('A' . $cellcount . ':AN' . $cellcount)->applyFromArray($borderstyle);
                                    if (isset(collect($forms[0]->students)->where('lowergender', 'female')->values()[$femalekey + 2])) {
                                        $sheet->insertNewRowBefore(($cellcount + 1), 1);
                                    }
                                    $femalecount += 1;
                                    $cellcount += 1;
                                }
                                $sheet->setCellValue('A' . $cellcount, $femalecount);
                                $cellcount += 1;
                                $sheet->setCellValue('A' . $cellcount, count($forms[0]->students));
                                $cellcount = 7;
                                foreach (collect($forms[0]->students)->where('lowergender', 'male')->values() as $malekey => $male) {
                                    if ($malekey == 0) {
                                        $sheet->insertNewRowBefore(($cellcount + 1), 1);
                                    }
                                    if ($male->ismothernum == 1) {
                                        $contactno = $male->mcontactno;
                                    }
                                    if ($male->isfathernum == 1) {
                                        $contactno = $male->fcontactno;
                                    }
                                    if ($male->isguardannum == 1) {
                                        $contactno = $male->gcontactno;
                                    }
                                    $sheet->mergeCells('A' . $cellcount . ':B' . $cellcount);
                                    $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                                    $sheet->mergeCells('H' . $cellcount . ':I' . $cellcount);
                                    $sheet->mergeCells('J' . $cellcount . ':K' . $cellcount);
                                    $sheet->mergeCells('L' . $cellcount . ':M' . $cellcount);
                                    $sheet->mergeCells('Q' . $cellcount . ':T' . $cellcount);
                                    $sheet->mergeCells('U' . $cellcount . ':V' . $cellcount);
                                    $sheet->mergeCells('X' . $cellcount . ':Z' . $cellcount);
                                    $sheet->mergeCells('AB' . $cellcount . ':AD' . $cellcount);
                                    $sheet->mergeCells('AF' . $cellcount . ':AG' . $cellcount);
                                    $sheet->mergeCells('AH' . $cellcount . ':AJ' . $cellcount);
                                    $sheet->mergeCells('AL' . $cellcount . ':AM' . $cellcount);

                                    // $sheet->setCellValue('A'.$cellcount, $malecount);
                                    $sheet->setCellValue('A' . $cellcount, $male->lrn . ' ');
                                    $sheet->setCellValue('C' . $cellcount, $male->lastname . ', ' . $male->firstname . ' ' . $male->suffix . ' ' . $male->middlename);
                                    $sheet->setCellValue('G' . $cellcount, $male->gender[0]);
                                    $sheet->setCellValue('H' . $cellcount, $male->dob);
                                    $sheet->setCellValue('J' . $cellcount, $male->age);
                                    $sheet->setCellValue('L' . $cellcount, $male->mtname);
                                    $sheet->setCellValue('N' . $cellcount, $male->egname);
                                    $sheet->setCellValue('O' . $cellcount, $male->religionname);
                                    $sheet->setCellValue('P' . $cellcount, $male->street);
                                    $sheet->setCellValue('Q' . $cellcount, $male->barangay);
                                    $sheet->setCellValue('U' . $cellcount, $male->city);
                                    $sheet->setCellValue('X' . $cellcount, $male->province);
                                    $sheet->setCellValue('AB' . $cellcount, strtoupper($male->fathername));
                                    $sheet->setCellValue('AF' . $cellcount, strtoupper($male->mothername));
                                    $sheet->setCellValue('AH' . $cellcount, $male->guardianname);
                                    $sheet->setCellValue('AK' . $cellcount, $male->guardianrelation);
                                    $sheet->setCellValue('AL' . $cellcount, $contactno);

                                    if ($male->studstatus == 3 || $male->studstatus == 5) {
                                        $sheet->setCellValue('AN' . $cellcount, DB::table('studentstatus')->where('id', $male->studstatus)->first()->description);
                                    }

                                    $sheet->getStyle('A' . $cellcount . ':AN' . $cellcount)->applyFromArray($borderstyle);
                                    if (isset(collect($forms[0]->students)->where('lowergender', 'male')->values()[$malekey + 2])) {
                                        $sheet->insertNewRowBefore(($cellcount + 1), 1);
                                    } else {
                                        // return collect($forms[0]->students)->where('lowergender','male')->count();
                                        // return $cellcount;
                                    }
                                    $malecount += 1;
                                    $cellcount += 1;
                                }
                                $sheet->setCellValue('A' . $cellcount, $malecount);

                            }
                            $sheet->setCellValue('A' . $cellcount, $malecount);


                            $sheet->getpageSetup()->setFitToPage(true);
                            $sheet->getpageSetup()->setFitToWidth(1);
                            $sheet->getpageSetup()->setFitToHeight(0);
                            $sheet->getSheetView()->setZoomScale(85);
                            $sheet->getpageSetup()->setPrintArea('A1:AN' . (count($forms[0]->students) + 23));
                            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                            header('Content-Type: application/vnd.ms-excel');
                            header('Content-Disposition: attachment; filename="School Form 1 ' . $forms[0]->gradelevel . ' - ' . $forms[0]->section . '.xls"');
                            $writer->save("php://output");
                            exit;
                        }
                    } else {

                        $signatories = DB::table('signatory')
                            ->where('form', 'form1')
                            ->where('syid', $forms[0]->syid)
                            ->where('deleted', '0')
                            ->whereIn('acadprogid', [$forms[0]->acadprogid, 0])
                            ->get();

                        $signatory_name = '';
                        if (count($signatories) == 0) {
                            $signatory_name = DB::table('schoolinfo')->first()->authorized;
                        } else {

                            $signatory_name = $signatories[0]->name;
                        }

                        $signatoriesv2 = DB::table('signatory')
                            ->where('form', 'form1')
                            ->where('syid', $forms[0]->syid)
                            ->where('deleted', '0')
                            ->where('acadprogid', $forms[0]->acadprogid)
                            ->get();

                        if (count($signatoriesv2) == 0) {
                            $signatoriesv2 = DB::table('signatory')
                                ->where('form', 'form1')
                                ->where('syid', $forms[0]->syid)
                                ->where('deleted', '0')
                                ->where('acadprogid', 0)
                                ->get();

                            if (count($signatoriesv2) > 0) {
                                if (collect($signatoriesv2)->where('levelid', $forms[0]->levelid)->count() == 0) {
                                    $signatoriesv2 = collect($signatoriesv2)->where('levelid', 0)->values();
                                } else {
                                    $signatoriesv2 = collect($signatoriesv2)->where('levelid', $forms[0]->levelid)->values();
                                }
                            }

                        } else {
                            if (collect($signatoriesv2)->where('levelid', $forms[0]->levelid)->count() == 0) {
                                $signatoriesv2 = collect($signatoriesv2)->where('levelid', 0)->values();
                            } else {
                                $signatoriesv2 = collect($signatoriesv2)->where('levelid', $forms[0]->levelid)->values();
                            }
                        }

                        $first = collect($signatoriesv2)->first();

                        if (count($signatoriesv2) > 0) {
                            foreach ($signatoriesv2 as $signatory) {
                                $signatory->display = 0;
                            }
                        }
                        $odd = array();
                        $even = array();
                        foreach (collect($signatoriesv2)->toArray() as $k => $v) {
                            if ($k > 0) {
                                if ($k % 2 == 0) {
                                    $even[] = $v;
                                } else {
                                    $odd[] = $v;
                                }
                            }
                        }
                        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path() . '/public/excelformats/schoolform1_withlmod.xlsx');
                        $sheet = $spreadsheet->getActiveSheet();
                        $borderstyle = [
                            // 'alignment' => [
                            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                            // ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                ],
                            ]
                        ];

                        $sheet->getColumnDimension('A')->setWidth(10);
                        $sheet->setCellValue('F4', $forms[0]->schoolinfo->schoolid);
                        $sheet->setCellValue('I4', $forms[0]->schoolinfo->regDesc ?? DB::table('schoolinfo')->first()->regiontext);
                        $sheet->setCellValue('N4', $forms[0]->schoolinfo->citymunDesc ?? DB::table('schoolinfo')->first()->divisiontext);
                        $sheet->setCellValue('U4', $forms[0]->schoolinfo->district ?? DB::table('schoolinfo')->first()->districttext);
                        $sheet->setCellValue('F6', $forms[0]->schoolinfo->schoolname);
                        $sheet->setCellValue('P6', $forms[0]->schoolyear);
                        $sheet->setCellValue('U6', $forms[0]->gradelevel);
                        $sheet->setCellValue('X6', $forms[0]->section);


                        $sheet->setCellValue('T15', collect($forms[0]->students)->where('lowergender', 'male')->count());
                        $sheet->setCellValue('U15', collect($forms[0]->students)->where('lowergender', 'male')->count());
                        $sheet->setCellValue('T16', collect($forms[0]->students)->where('lowergender', 'female')->count());
                        $sheet->setCellValue('U16', collect($forms[0]->students)->where('lowergender', 'female')->count());
                        $sheet->setCellValue('T17', collect($forms[0]->students)->count());
                        $sheet->setCellValue('U17', collect($forms[0]->students)->count());

                        $sheet->setCellValue('W15', $forms[0]->teachername);
                        $sheet->setCellValue('W18', 'BoSY Date: ' . date('F d, Y', strtotime(DB::table('sy')->where('id', $forms[0]->syid)->first()->sdate)) . '   EoSY Date: ' . date('F d, Y', strtotime(DB::table('sy')->where('id', $forms[0]->syid)->first()->edate)));
                        $sheet->setCellValue('Z18', 'BoSY Date: ' . date('F d, Y', strtotime(DB::table('sy')->where('id', $forms[0]->syid)->first()->sdate)) . '   EoSY Date: ' . date('F d, Y', strtotime(DB::table('sy')->where('id', $forms[0]->syid)->first()->edate)));


                        $sheet->setCellValue('Z15', $signatory_name);

                        // ================================  TABLE HEADER     
                        $malecount = 1;
                        $femalecount = 1;
                        $cellcount = 10;
                        $contactno = null;
                        if (count($forms[0]->students) > 0) {
                            // ================================  MALE STUDENTS     
                            // $malestudents = collect($forms[0]->students->where('gender','MALE'));
                            foreach (collect($forms[0]->students)->where('lowergender', 'male')->values() as $male) {
                                if ($male->ismothernum == 1) {
                                    $contactno = $male->mcontactno;
                                }
                                if ($male->isfathernum == 1) {
                                    $contactno = $male->fcontactno;
                                }
                                if ($male->isguardannum == 1) {
                                    $contactno = $male->gcontactno;
                                }
                                $sheet->getStyle('A' . $cellcount . ':B' . $cellcount)->getFont()->setSize(20);
                                $sheet->setCellValue('A' . $cellcount, $malecount);
                                $sheet->setCellValue('B' . $cellcount, $male->lrn . ' ');
                                $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                                $sheet->setCellValue('C' . $cellcount, $male->lastname . ', ' . $male->firstname . ' ' . $male->suffix . ' ' . $male->middlename);
                                $sheet->setCellValue('G' . $cellcount, $male->gender[0]);
                                $sheet->setCellValue('H' . $cellcount, $male->dob);
                                $sheet->setCellValue('I' . $cellcount, $male->age);
                                $sheet->setCellValue('L' . $cellcount, $male->mtname);
                                $sheet->setCellValue('M' . $cellcount, $male->egname);
                                $sheet->setCellValue('N' . $cellcount, $male->religionname);
                                $sheet->setCellValue('O' . $cellcount, $male->street);
                                $sheet->setCellValue('P' . $cellcount, $male->barangay);
                                $sheet->setCellValue('Q' . $cellcount, $male->city);
                                $sheet->mergeCells('R' . $cellcount . ':S' . $cellcount);
                                $sheet->setCellValue('R' . $cellcount, $male->province);
                                $sheet->mergeCells('T' . $cellcount . ':U' . $cellcount);
                                $sheet->setCellValue('T' . $cellcount, strtoupper($male->fathername));
                                $sheet->mergeCells('V' . $cellcount . ':W' . $cellcount);
                                $sheet->setCellValue('V' . $cellcount, strtoupper($male->mothername));
                                $sheet->setCellValue('X' . $cellcount, $male->guardianname);
                                $sheet->setCellValue('Y' . $cellcount, $male->guardianrelation);
                                $sheet->setCellValue('Z' . $cellcount, $contactno);

                                if (count(DB::table('modeoflearning')->where('id', $male->mol)->get()) > 0) {
                                    $sheet->setCellValue('AA' . $cellcount, DB::table('modeoflearning')->where('id', $male->mol)->first()->description);
                                }

                                $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                                $malecount += 1;
                                $cellcount += 1;
                                $sheet->insertNewRowBefore($cellcount, 1);
                            }

                            $sheet->insertNewRowBefore($cellcount, 1);
                            $sheet->setCellValue('A' . $cellcount, '');
                            $sheet->setCellValue('B' . $cellcount, ($malecount - 1));
                            $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                            $sheet->setCellValue('C' . $cellcount, ' == TOTAL MALE');
                            $sheet->setCellValue('G' . $cellcount, '');
                            $sheet->setCellValue('H' . $cellcount, '');
                            $sheet->setCellValue('I' . $cellcount, '');
                            $sheet->setCellValue('L' . $cellcount, '');
                            $sheet->setCellValue('M' . $cellcount, '');
                            $sheet->setCellValue('N' . $cellcount, '');
                            $sheet->setCellValue('O' . $cellcount, '');
                            $sheet->setCellValue('P' . $cellcount, '');
                            $sheet->setCellValue('Q' . $cellcount, '');
                            $sheet->mergeCells('R' . $cellcount . ':S' . $cellcount);
                            $sheet->setCellValue('R' . $cellcount, '');
                            $sheet->mergeCells('T' . $cellcount . ':U' . $cellcount);
                            $sheet->setCellValue('T' . $cellcount, '');
                            $sheet->mergeCells('V' . $cellcount . ':W' . $cellcount);
                            $sheet->setCellValue('V' . $cellcount, '');
                            $sheet->setCellValue('X' . $cellcount, '');
                            $sheet->setCellValue('Y' . $cellcount, '');
                            $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                            $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                            $cellcount += 1;

                            // ================================  FEMALE STUDENTS     

                            foreach (collect($forms[0]->students)->where('lowergender', 'female')->values() as $female) {
                                if ($female->ismothernum == 1) {
                                    $contactno = $female->mcontactno;
                                }
                                if ($female->isfathernum == 1) {
                                    $contactno = $female->fcontactno;
                                }
                                if ($female->isguardannum == 1) {
                                    $contactno = $female->gcontactno;
                                }
                                $sheet->setCellValue('A' . $cellcount, $femalecount);
                                $sheet->setCellValue('B' . $cellcount, $female->lrn . ' ');
                                $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                                $sheet->setCellValue('C' . $cellcount, $female->lastname . ', ' . $female->firstname . ' ' . $female->suffix . ' ' . $female->middlename);
                                $sheet->setCellValue('G' . $cellcount, $female->gender[0]);
                                $sheet->setCellValue('H' . $cellcount, $female->dob);
                                $sheet->setCellValue('I' . $cellcount, $female->age);
                                $sheet->setCellValue('L' . $cellcount, $female->mtname);
                                $sheet->setCellValue('M' . $cellcount, $female->egname);
                                $sheet->setCellValue('N' . $cellcount, $female->religionname);
                                $sheet->setCellValue('O' . $cellcount, $female->street);
                                $sheet->setCellValue('P' . $cellcount, $female->barangay);
                                $sheet->setCellValue('Q' . $cellcount, $female->city);
                                $sheet->mergeCells('R' . $cellcount . ':S' . $cellcount);
                                $sheet->setCellValue('R' . $cellcount, $female->province);
                                $sheet->mergeCells('T' . $cellcount . ':U' . $cellcount);
                                $sheet->setCellValue('T' . $cellcount, strtoupper($female->fathername));
                                $sheet->mergeCells('V' . $cellcount . ':W' . $cellcount);
                                $sheet->setCellValue('V' . $cellcount, strtoupper($female->mothername));
                                $sheet->setCellValue('X' . $cellcount, $female->guardianname);
                                $sheet->setCellValue('Y' . $cellcount, $female->guardianrelation);
                                $sheet->setCellValue('Z' . $cellcount, $contactno);

                                if (count(DB::table('modeoflearning')->where('id', $female->mol)->get()) > 0) {
                                    $sheet->setCellValue('AA' . $cellcount, DB::table('modeoflearning')->where('id', $female->mol)->first()->description);
                                }

                                $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                                $femalecount += 1;
                                $cellcount += 1;
                                $sheet->insertNewRowBefore($cellcount, 1);
                            }
                            $sheet->insertNewRowBefore($cellcount, 1);
                            $sheet->setCellValue('A' . $cellcount, '');
                            $sheet->setCellValue('B' . $cellcount, ($femalecount - 1));
                            $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                            $sheet->setCellValue('C' . $cellcount, ' == TOTAL FEMALE');
                            $sheet->setCellValue('G' . $cellcount, '');
                            $sheet->setCellValue('H' . $cellcount, '');
                            $sheet->setCellValue('I' . $cellcount, '');
                            $sheet->setCellValue('L' . $cellcount, '');
                            $sheet->setCellValue('M' . $cellcount, '');
                            $sheet->setCellValue('N' . $cellcount, '');
                            $sheet->setCellValue('O' . $cellcount, '');
                            $sheet->setCellValue('P' . $cellcount, '');
                            $sheet->setCellValue('Q' . $cellcount, '');
                            $sheet->mergeCells('R' . $cellcount . ':S' . $cellcount);
                            $sheet->setCellValue('R' . $cellcount, '');
                            $sheet->mergeCells('T' . $cellcount . ':U' . $cellcount);
                            $sheet->setCellValue('T' . $cellcount, '');
                            $sheet->mergeCells('V' . $cellcount . ':W' . $cellcount);
                            $sheet->setCellValue('V' . $cellcount, '');
                            $sheet->setCellValue('X' . $cellcount, '');
                            $sheet->setCellValue('Y' . $cellcount, '');
                            $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                            $cellcount += 1;
                        }
                        $sheet->insertNewRowBefore($cellcount, 1);
                        $sheet->setCellValue('A' . $cellcount, '');
                        $sheet->setCellValue('B' . $cellcount, (($femalecount - 1) + ($malecount - 1)));
                        $sheet->mergeCells('C' . $cellcount . ':F' . $cellcount);
                        $sheet->setCellValue('C' . $cellcount, ' == COMBINED');
                        $sheet->setCellValue('G' . $cellcount, '');
                        $sheet->setCellValue('H' . $cellcount, '');
                        $sheet->setCellValue('I' . $cellcount, '');
                        $sheet->setCellValue('L' . $cellcount, '');
                        $sheet->setCellValue('M' . $cellcount, '');
                        $sheet->setCellValue('N' . $cellcount, '');
                        $sheet->setCellValue('O' . $cellcount, '');
                        $sheet->setCellValue('P' . $cellcount, '');
                        $sheet->setCellValue('Q' . $cellcount, '');
                        $sheet->mergeCells('R' . $cellcount . ':S' . $cellcount);
                        $sheet->setCellValue('R' . $cellcount, '');
                        $sheet->mergeCells('T' . $cellcount . ':U' . $cellcount);
                        $sheet->setCellValue('T' . $cellcount, '');
                        $sheet->mergeCells('V' . $cellcount . ':W' . $cellcount);
                        $sheet->setCellValue('V' . $cellcount, '');
                        $sheet->setCellValue('X' . $cellcount, '');
                        $sheet->setCellValue('Y' . $cellcount, '');
                        $sheet->getStyle('A' . $cellcount . ':AB' . $cellcount)->applyFromArray($borderstyle);
                    }
                    // $cellcount+=1;


                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="School Form 1 ' . $forms[0]->gradelevel . ' - ' . $forms[0]->section . '.xlsx"');
                    $writer->save("php://output");
                }

            }
        }
    }
    public function form2(Request $request)
    {
        set_time_limit(0);
        date_default_timezone_set('Asia/Manila');
        $teacherid = Db::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first()
            ->id;

        if ($request->has('selectedyear')) {
            $currentyearnum = $request->get('selectedyear');

        } else {
            $currentyearnum = date('Y');
        }
        $sectionid = $request->get('sectionid');
        $strandid = $request->get('strandid');
        $semid = $request->get('semid');
        $levelid = $request->get('levelid');
        $selectedmonth = $request->get('selectedmonth');

        if ($request->has('syid')) {
            $syid = DB::table('sy')
                ->where('id', $request->get('syid'))
                ->first()->id;
        } else {
            $syid = DB::table('sy')
                ->where('isactive', '1')
                ->first()->id;
        }

        $setup = DB::table('sf2_setup')
            ->select('sf2_setup.*', 'sh_strand.strandname', 'sh_strand.strandcode')
            ->leftJoin('sh_strand', 'sf2_setup.strandid', '=', 'sh_strand.id')
            ->where('sf2_setup.deleted', '0')
            ->where('sf2_setup.syid', $syid)
            ->where('sf2_setup.sectionid', $sectionid)
            ->where('sf2_setup.month', $selectedmonth)
            ->where('sf2_setup.year', $currentyearnum)
            ->get();


        //school days setup
        $schooldayssetup = \App\Http\Controllers\SuperAdminController\SchoolDaysController::attendance_setup_list($syid, $levelid, 0, $selectedmonth);

        if (count($setup) == 0) {
            $setupid = DB::table('sf2_setup')
                ->insertGetId([
                    'teacherid' => $teacherid,
                    'month' => $request->get('selectedmonth'),
                    'syid' => $request->get('syid'),
                    'sectionid' => $request->get('sectionid'),
                    'year' => $request->get('selectedyear'),
                    'strandid' => $request->get('strandid'),
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s'),
                    'course' => $request->get('tvlcourse')
                ]);

            $setup = DB::table('sf2_setup')
                ->select('sf2_setup.*', 'sh_strand.strandname', 'sh_strand.strandcode')
                ->leftJoin('sh_strand', 'sf2_setup.strandid', '=', 'sh_strand.id')
                ->where('sf2_setup.deleted', '0')
                ->where('sf2_setup.syid', $syid)
                ->where('sf2_setup.sectionid', $sectionid)
                ->where('sf2_setup.month', $selectedmonth)
                ->where('sf2_setup.year', $currentyearnum)
                ->get();
        } else {
            $setupid = $setup[0]->id;
        }

        //return $schooldayssetup;

        $setup_dates = DB::table('sf2_setupdates')
            ->where('setupid', $setupid)
            ->get();

        if (count($schooldayssetup) > 0) {

            foreach ($schooldayssetup[0]->dates as $item) {
                DB::table('sf2_setupdates')
                    ->updateOrInsert(
                        ['setupid' => $setupid, 'dates' => $item],
                        [
                            'setupid' => $setupid,
                            'dates' => $item,
                            'deleted' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]
                    );
            }

            //remove other dates
            DB::table('sf2_setupdates')
                ->where('setupid', $setupid)
                ->whereNotIn('dates', $schooldayssetup[0]->dates)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);
            //school days setup
        } else {
            $setup = array();
        }

        if ($request->get('levelid') == 14 || $request->get('levelid') == 15) {
            if (count(collect($setup)->where('strandid', $strandid)) > 0) {
                $setup = collect($setup)->where('strandid', $strandid)->values();
            }
        }
        if (Session::get('currentPortal') == 1) {
            $setup = collect($setup)->where('teacherid', $teacherid)->values();
        } else {
            $teacherid = $setup[0]->teacherid;
        }

        if ($selectedmonth[0] == 0) {
            $setup_numdaymonth = $selectedmonth[1];
        } else {
            $setup_numdaymonth = $selectedmonth;
        }


        $setup_numdays = DB::table('studattendance_setup')
            ->where('syid', $syid)
            ->where('month', $setup_numdaymonth)
            ->where('year', $currentyearnum)
            ->where('semid', $semid)
            ->where('levelid', $levelid)
            ->where('deleted', 0)
            ->first();


        if (count($setup) > 0) {
            foreach ($setup as $sf2setup) {
                $sf2setup->dates = DB::table('sf2_setupdates')
                    ->where('setupid', $sf2setup->id)
                    ->where('deleted', '0')
                    ->orderBy('dates', 'asc')
                    ->get();
            }
        } else {
            $setup = [];
        }

        $locksf2 = 0;
        if (count($setup) > 0) {
            if (DB::getSchemaBuilder()->hasTable('sf2_setuplock')) {
                // return $setup;
                $lockstatus = DB::table('sf2_setuplock')
                    ->where('setupid', $setup[0]->id)
                    ->where('deleted', '0')
                    ->first();

                // return collect($lockstatus);
                if ($lockstatus) {

                    $locksf2 = $lockstatus->lockstatus;
                }
            }

        }
        $strands = array();

        if ($request->get('action') == 'index') {
            $acadprogcode = Db::table('gradelevel')
                ->select('academicprogram.acadprogcode', 'gradelevel.levelname')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->where('gradelevel.id', $request->get('levelid'))
                ->first();
            // return $setup;
            return view('teacher.forms.form2.index')
                ->with('syid', $syid)
                ->with('locksf2', $locksf2)
                ->with('semid', $semid)
                ->with('setup_numdays', $setup_numdays)
                ->with('selectedmonth', $selectedmonth)
                ->with('levelid', $request->get('levelid'))
                ->with('sectionid', $request->get('sectionid'))
                ->with('strandid', $request->get('strandid'))
                ->with('acadprogcode', $acadprogcode->acadprogcode)
                ->with('setup', $setup);

        } elseif ($request->get('action') == 'updateequivalence') {
            $checkifexists = DB::table('sf2_lact')
                ->where('teacherid', $teacherid)
                ->where('year', $request->get('selectedyear'))
                ->where('month', $request->get('selectedmonth'))
                ->where('sectionid', $request->get('sectionid'))
                ->where('strandid', $request->get('strandid'))
                ->where('lact', $request->get('selectedlact'))
                ->where('deleted', '0')
                ->get();

            if (count($checkifexists) == 0) {
                DB::table('sf2_lact')
                    ->insert([
                        'teacherid' => $teacherid,
                        'year' => $request->get('selectedyear'),
                        'month' => $request->get('selectedmonth'),
                        'sectionid' => $request->get('sectionid'),
                        'strandid' => $request->get('strandid'),
                        'equivalence' => $request->get('equivalence'),
                        'lact' => $request->get('selectedlact'),
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                DB::table('sf2_lact')
                    ->where('id', $checkifexists[0]->id)
                    ->update([
                        'equivalence' => $request->get('equivalence'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);

            }
        } elseif ($request->get('action') == 'updatestudlact3') {

            $equivalence = DB::table('sf2_lact')
                ->where('id', $request->get('headerid'))
                ->first()->equivalence;

            $checkifexists = DB::table('sf2_lact3detail')
                ->where('headerid', $request->get('headerid'))
                ->where('studid', $request->get('studid'))
                ->where('deleted', '0')
                ->first();

            $dayspresent = ($request->get('submitted') / $request->get('required')) * $equivalence;
            $daysabsent = $equivalence - $dayspresent;

            if (!$checkifexists) {
                DB::table('sf2_lact3detail')
                    ->insert([
                        'headerid' => $request->get('headerid'),
                        'studid' => $request->get('studid'),
                        'submitted' => $request->get('submitted'),
                        'required' => $request->get('required'),
                        'dayspresent' => $dayspresent,
                        'daysabsent' => $daysabsent,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                DB::table('sf2_lact3detail')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'submitted' => $request->get('submitted'),
                        'required' => $request->get('required'),
                        'dayspresent' => $dayspresent,
                        'daysabsent' => $daysabsent,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);

            }
        } elseif ($request->get('action') == 'getselecteddates') {
            $dates = DB::table('sf2_setup')
                ->select('dates')
                ->join('sf2_setupdates', 'sf2_setup.id', '=', 'sf2_setupdates.setupid')
                ->where('sf2_setup.sectionid', $request->get('sectionid'))
                ->where('sf2_setup.month', $request->get('selectedmonth'))
                ->where('sf2_setup.strandid', $request->get('strandid'))
                ->where('sf2_setup.deleted', '0')
                ->where('sf2_setupdates.deleted', '0')
                ->where('sf2_setup.createdby', auth()->user()->id)
                ->distinct('dates')
                ->get();

            $datesarray = collect(collect($dates)->pluck('dates'))->toArray();

            function draw_calendar($year, $month, $dateslist)
            {

                // return $dates;
                /* draw table */
                $calendar = '<table class="table-bordered" style="width: 100%;">';

                /* table headings */
                $headings = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                $calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings) . '</td></tr>';

                /* days and weeks vars now ... */
                $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
                $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
                $days_in_this_week = 1;
                $day_counter = 0;
                $dates_array = array();

                /* row for week one */
                $calendar .= '<tr class="calendar-row">';

                /* print "blank" days until the first of the current week */
                for ($x = 0; $x < $running_day; $x++):
                    $calendar .= '<td class="calendar-day-np"> </td>';
                    $days_in_this_week++;
                endfor;
                /* keep going with days.... */
                for ($list_day = 1; $list_day <= $days_in_month; $list_day++):
                    // return count($list_day);

                    $calendar .= '<td class="calendar-day">';
                    /* add in the day number */
                    $listnum = $list_day;
                    if (strlen($list_day) == 1) {
                        $listnum = '0' . $list_day;
                    }
                    // return $dates;
                    if (in_array($year . '-' . $month . '-' . $listnum, $dateslist)) {
                        // return 'ada';
                        $calendar .= '<div class="day-number"><a class="btn btn-block each-date btn-success" data-select="1"  data-id="' . $year . '-' . $month . '-' . $listnum . '">' . $list_day . '</a></div>';

                    } else {
                        $calendar .= '<div class="day-number"><a class="btn btn-block each-date" data-select="0" data-id="' . $year . '-' . $month . '-' . $listnum . '">' . $list_day . '</a></div>';
                    }
                    /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
                    $calendar .= str_repeat('<p> </p>', 2);

                    $calendar .= '</td>';
                    if ($running_day == 6):
                        $calendar .= '</tr>';
                        if (($day_counter + 1) != $days_in_month):
                            $calendar .= '<tr class="calendar-row">';
                        endif;
                        $running_day = -1;
                        $days_in_this_week = 0;
                    endif;
                    $days_in_this_week++;
                    $running_day++;
                    $day_counter++;
                endfor;

                /* finish the rest of the days in the week */
                if ($days_in_this_week < 8):
                    for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
                        $calendar .= '<td class="calendar-day-np"> </td>';
                    endfor;
                endif;

                /* final row */
                $calendar .= '</tr>';

                /* end the table */
                $calendar .= '</table>';

                /* all done, return result */
                return $calendar;
            }

            /* sample usages */
            echo '<h2><center>' . date('F Y', strtotime($request->get('selectedyear') . '-' . $request->get('selectedmonth'))) . '</center></h2>';

            return draw_calendar($request->get('selectedyear'), $request->get('selectedmonth'), $datesarray);
        } elseif ($request->get('action') == 'updatesetupdates') {
            if (!$request->has('dates')) {
                DB::table('sf2_setup')
                    ->where('id', $request->get('setupid'))
                    ->update([
                        'course' => $request->get('newcourse'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                $dates = DB::table('sf2_setup')
                    ->select('sf2_setup.id as setupid', 'sf2_setupdates.id', 'dates')
                    ->join('sf2_setupdates', 'sf2_setup.id', '=', 'sf2_setupdates.setupid')
                    ->where('sf2_setup.sectionid', $request->get('sectionid'))
                    ->where('sf2_setup.month', $request->get('selectedmonth'))
                    ->where('sf2_setup.strandid', $request->get('strandid'))
                    ->where('sf2_setup.deleted', '0')
                    ->where('sf2_setupdates.deleted', '0')
                    ->where('sf2_setup.createdby', auth()->user()->id)
                    ->distinct('dates')
                    ->get();
                $selecteddates = collect(collect(json_decode($request->get('dates')))->pluck('tdate'))->toArray();
                if (count($selecteddates) == 0) {
                    foreach ($dates as $date) {
                        DB::table('sf2_setup')
                            ->where('sf2_setup.id', $date->setupid)
                            ->where('sf2_setup.createdby', auth()->user()->id)
                            ->update([
                                'deleted' => 1,
                                'deletedby' => auth()->user()->id,
                                'deleteddatetime' => date('Y-m-d H:i:s')
                            ])
                            ->get();
                        DB::table('sf2_setupdates')
                            ->where('sf2_setupdates.setupid', $date->setupid)
                            ->update([
                                'deleted' => 1,
                                'deletedby' => auth()->user()->id,
                                'deleteddatetime' => date('Y-m-d H:i:s')
                            ])
                            ->get();
                    }

                } else {
                    foreach ($selecteddates as $selecteddate) {
                        if (collect($dates)->where('dates', $selecteddate)->count() == 0) {
                            DB::table('sf2_setupdates')
                                ->insert([
                                    'setupid' => $dates[0]->setupid,
                                    'dates' => $selecteddate,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                    foreach ($dates as $date) {
                        if (!in_array($date->dates, $selecteddates)) {
                            DB::table('sf2_setupdates')
                                ->where('id', $date->id)
                                ->update([
                                    'deleted' => 1,
                                    'deletedby' => auth()->user()->id,
                                    'deleteddatetime' => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }
            }

        } elseif ($request->get('action') == 'getattendance') {

            $studentids = json_decode($request->get('studentids'));
            // return 
            $month = $request->get('selectedmonth');
            $year = $request->get('selectedyear');
            $collecteddates = json_decode($request->get('dates'));
            $lists = array();
            for ($d = 1; $d <= 31; $d++) {
                $time = mktime(12, 0, 0, $month, $d, $year);
                if (date('m', $time) == $month)
                    $lists[] = date('Y-m-d', $time);
            }
            $dates = array();
            foreach ($collecteddates as $collecteddate) {

                array_push($dates, (object) array(
                    'date' => $collecteddate,
                    'datestr' => date('M d', strtotime($collecteddate)),
                    'day' => date('D', strtotime($collecteddate))
                )
                );
            }
            // foreach($lists as $list)
            // {

            //     array_push($dates, (object)array(
            //         'date'  => $list,
            //         'datestr'  => date('M d',strtotime($list)),
            //         'day'  => date('D',strtotime($list))
            //     ));
            // }
            $studids = array();
            $studentids = str_replace(array('[', ']'), '', $studentids);
            if (strpos($studentids, ',') !== false) {
                $studids = explode(',', $studentids);
            } else {
                array_push($studids, $studentids);
            }


            $attendance = DB::table('studattendance')
                // ->where('syid', $syid->id)
                ->where('deleted', '0')
                ->whereBetween('tdate', [collect($dates)->first()->date, collect($dates)->last()->date])
                ->whereIn('studid', $studids)
                ->get();


            $students = DB::table('studinfo')
                ->whereIn('id', $studids)
                ->orderBy('lastname', 'asc')
                ->get();

            if (count($students) > 0) {
                foreach ($students as $student) {
                    $att = array();

                    foreach ($dates as $date) {
                        $attstatus = collect($attendance)->where('studid', $student->id)->where('tdate', $date->date)->values();
                        // return collect($attstatus);

                        $status = "";

                        if (count($attstatus) > 0) {
                            if ($attstatus[0]->present == 1) {
                                $status = 'PRESENT';
                            }
                            if ($attstatus[0]->absent == 1) {
                                $status = 'ABSENT';
                            }
                            if ($attstatus[0]->tardy == 1) {
                                $status = 'LATE';
                            }
                            if ($attstatus[0]->cc == 1) {
                                $status = 'CC';
                            }
                        }

                        array_push($att, (object) array(
                            'tdate' => $date->date,
                            'status' => $status
                        )
                        );
                    }

                    $student->attendance = $att;
                }
            }
            $acadprogcode = Db::table('gradelevel')
                ->select('academicprogram.acadprogcode', 'gradelevel.levelname')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->where('gradelevel.id', $request->get('levelid'))
                ->first();
            return view('teacher.forms.form2.attendanceindex')
                ->with('dates', $dates)
                ->with('students', $students)
                ->with('levelid', $request->get('levelid'))
                ->with('sectionid', $request->get('sectionid'))
                ->with('syid', $request->get('syid'))
                ->with('acadprogcode', $acadprogcode->acadprogcode);
        } elseif ($request->get('action') == 'filter' || $request->get('action') == 'export') {
            // return $setup;

            $tvlcourse = "";
            $acadprogcode = Db::table('gradelevel')
                ->select('academicprogram.id', 'academicprogram.acadprogcode', 'gradelevel.levelname')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->where('gradelevel.id', $request->get('levelid'))
                ->first();

            if (strtolower($acadprogcode->acadprogcode) == 'shs') {
                if (count($setup) > 0) {
                    if (strpos($setup[0]->strandcode, 'TVL') !== false) {
                        $tvlcourse = $setup[0]->strandname;
                    }
                }
                if ($request->has('strandid')) {
                    if ($request->has('semid')) {
                        $semid = $request->get('semid');
                    } else {
                        $semid = $request->get('semester');
                    }
                    $students = Db::table('studinfo')
                        ->select('studinfo.id', 'studinfo.lrn', 'studinfo.lastname', DB::raw("CONCAT(lastname, ' ',firstname) as name"), 'studinfo.middlename', 'studinfo.firstname', 'studinfo.suffix', 'studinfo.gender', 'sh_enrolledstud.studstatus', 'sh_enrolledstud.dateenrolled', 'sh_enrolledstud.studstatdate', 'sh_enrolledstud.strandid', 'sh_enrolledstud.studstatus as enrolledstudstatus')
                        ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                        ->where('studinfo.deleted', '0')
                        ->where('sh_enrolledstud.deleted', '0')
                        // // ->where('sh_enrolledstud.semid',DB::table('semester')->where('isactive','1')->first()->id)
                        ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                        ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                        ->where('sh_enrolledstud.strandid', $request->get('strandid'))
                        // // // ->where('studinfo.studstatus','!=','0')
                        ->where('sh_enrolledstud.studstatus', '!=', '0')
                        ->where('sh_enrolledstud.studstatus', '<=', '5')
                        // // // ->where('studinfo.studstatus','!=','0')
                        ->where('studinfo.studstatus', '<=', '5')
                        ->where('sh_enrolledstud.semid', $semid)
                        ->where('sh_enrolledstud.syid', $syid)
                        ->orderBy('lastname', 'asc')
                        ->get();




                } else {
                    $students = Db::table('studinfo')
                        ->select('studinfo.id', 'studinfo.lrn', 'studinfo.lastname', DB::raw("CONCAT(lastname, ' ',firstname) as name"), 'studinfo.middlename', 'studinfo.firstname', 'studinfo.suffix', 'studinfo.gender', 'sh_enrolledstud.studstatus', 'sh_enrolledstud.dateenrolled', 'sh_enrolledstud.studstatdate', 'sh_enrolledstud.strandid', 'sh_enrolledstud.studstatus as enrolledstudstatus')
                        ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                        ->where('studinfo.deleted', '0')
                        ->where('sh_enrolledstud.deleted', '0')
                        ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                        ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                        // ->where('studinfo.studstatus','!=','0')
                        ->where('sh_enrolledstud.studstatus', '!=', '0')
                        ->where('sh_enrolledstud.studstatus', '<=', '5')
                        // ->where('studinfo.studstatus','!=','0')
                        ->where('studinfo.studstatus', '<=', '5')
                        ->where('sh_enrolledstud.semid', $request->get('semid'))
                        ->where('sh_enrolledstud.syid', $syid)
                        ->orderBy('lastname', 'asc')
                        ->get();
                }
                if (count($students) > 0) {
                    foreach ($students as $student) {
                        if ($student->studstatdate == null) {
                            $student->studstatdate = $student->dateenrolled;
                        }
                        array_push($strands, $student->strandid);
                    }
                }
            } else {
                $students = Db::table('studinfo')
                    ->select('studinfo.id', 'studinfo.lrn', 'studinfo.lastname', DB::raw("CONCAT(lastname, ' ',firstname) as name"), 'studinfo.middlename', 'studinfo.firstname', 'studinfo.suffix', 'studinfo.gender', 'studinfo.studstatus', 'enrolledstud.dateenrolled', 'enrolledstud.studstatdate', 'enrolledstud.studstatus as enrolledstudstatus')
                    ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                    ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                    ->where('studinfo.deleted', '0')
                    ->where('enrolledstud.deleted', '0')
                    ->where('enrolledstud.studstatus', '!=', '0')
                    ->where('enrolledstud.studstatus', '<=', '5')
                    ->where('enrolledstud.sectionid', $request->get('sectionid'))
                    ->where('enrolledstud.levelid', $request->get('levelid'))
                    // ->where('studinfo.studstatus','!=','0')
                    ->where('studinfo.studstatus', '<=', '5')
                    ->where('enrolledstud.syid', $syid)
                    ->orderBy('lastname', 'asc')
                    ->get();

                if (count($students) > 0) {
                    foreach ($students as $student) {
                        if ($student->studstatdate == null) {
                            $student->studstatdate = $student->dateenrolled;
                        }
                    }
                }
            }
            // return $students;

            // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjhs')
            // {
            //     $students = collect($students)->whereIn('studstatus',[1,2,4])->values()->all();
            //     // $students = collect($students)->where('studstatdate','<=',date($currentyearnum.'-'.(sprintf("%02d", $selectedmonth)).'-t'));
            // }
            $students = collect($students)->sortBy('name')->all();
            $students = collect($students)->unique('id')->all();
            // return $students;
            // return $students;
            if (count($setup) == 0) {
                $currentdays = array();
            } else {
                $currentdays = $setup[0]->dates;
            }
            if (count($students) > 0) {
                foreach ($students as $student) {
                    $student->gender = strtolower($student->gender);
                    $student->lastname = ucwords(mb_convert_case($student->lastname, MB_CASE_LOWER, "UTF-8"));
                    // $student->firstname = $student->firstname;
                    // $student->middlename = ucwords(mb_convert_case($student->middlename, MB_CASE_LOWER, "UTF-8"));

                    $student->display = 1;
                    if ($student->studstatdate != null) {
                        if ($student->studstatdate < $student->dateenrolled) {
                            $student->studstatdate = date('Y-m-d', strtotime($student->dateenrolled));

                        } else {
                            $student->studstatdate = date('Y-m-d', strtotime($student->studstatdate));
                        }
                    } else {
                        $student->studstatdate = date('Y-m-d', strtotime($student->dateenrolled));
                    }
                    if (count($currentdays) > 0) {
                        if ($student->studstatus == 3 || $student->studstatus == 5)
                            // return collect($currentdays)->last()->dates;
                            if ($student->studstatdate < collect($currentdays)->last()->dates) {
                                $student->display = 0;
                            }
                    }
                }
            }



            $studentsarray = array();
            $month = $request->get('selectedmonth');
            $year = $request->get('selectedyear');
            $date = Carbon::createFromDate($year, $month)->endOfMonth();

            $tempstudents = array();
            foreach ($students as $student) {
                if ($student->studstatus == 4 || $student->studstatus == 2) {
                    $dateenrolled = Carbon::create($student->dateenrolled)->endOfMonth();
                    if (!$dateenrolled->gte($date)) {
                        array_push($tempstudents, $student);
                    }
                } else {
                    array_push($tempstudents, $student);
                }
            }


            if (count($currentdays) > 0) {
                foreach ($currentdays as $currentday) {
                    $currentday->daydate = $currentday->dates;
                    $currentday->daynum = date('d', strtotime($currentday->dates));
                    $currentday->daystr = date('D', strtotime($currentday->dates));
                }

                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sma') {
                    if (count($students) > 0) {
                        foreach ($students as $student) {

                            if ($student->studstatus == '3' || $student->studstatus == '5') {
                                // return date('Y-m',strtotime(collect($currentdays)->last()->daydate));
                                if (count($currentdays) > 0) {
                                    if (date('Y-m', strtotime($student->studstatdate)) <= date('Y-m', strtotime(collect($currentdays)->last()->daydate))) {
                                        $student->display = 0;
                                    } else {
                                        $student->display = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }



            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi') {
                $attendance = SchoolForm2Model::attendance_hccsi($students, $currentdays, $request->get('levelid'), $request->get('sectionid'), $selectedmonth, $currentyearnum, $teacherid, $request->get('selectedlact'), $setup, $syid, $semid, $strandid);
                // return collect($attendance[0])->where('id', 2736);
                // return collect($attendance);
            }
            // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjchssi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
            // {
            //     $attendance = SchoolForm2Model::attendance_sjchssi($students,$currentdays,$request->get('levelid'),$request->get('sectionid'),$selectedmonth,$currentyearnum,$teacherid,$request->get('selectedlact'),$setup, $syid, $semid, $strandid);
            //     // return collect($attendance);
            //     // return collect($attendance);
            // }
            else {
                // $attendance = SchoolForm2Model::attendance($students,$currentdays,$request->get('levelid'),$request->get('sectionid'),$selectedmonth,$currentyearnum,$teacherid,$request->get('selectedlact'),$setup, $syid, $semid, $strandid);

                $attendance = SchoolForm2Model::attendance_sjchssi($students, $currentdays, $request->get('levelid'), $request->get('sectionid'), $selectedmonth, $currentyearnum, $teacherid, $request->get('selectedlact'), $setup, $syid, $semid, $strandid);
            }
            // return collect($attendance);
            // return collect($attendance[0])->where('id', 1018)->values();
            // return collect($attendance);
            // return collect($attendance);
            // return collect($attendance[0])->where('id','260');
            // $studentstotalperday = SchoolForm2Model::studentstotalperday($students,$currentdays,$request->get('levelid'),$request->get('sectionid'),$selectedmonth,$currentyearnum,$teacherid,$request->get('selectedlact'),$setup, $syid, $semid, $strandid);
            // return $attendance;
            if (count($attendance[0]) == 0) {
                $studentstotalperday = array();
            } else {
                // $students = collect($students)->where('studstatdate','<=',date($currentyearnum.'-'.(sprintf("%02d", $selectedmonth)).'-t'));
                $studentstotalperdaysummary = array();
                foreach ($attendance[0] as $eachstudatt) {
                    if (count($eachstudatt->attendance) > 0) {
                        foreach ($eachstudatt->attendance as $eachatt) {
                            array_push($studentstotalperdaysummary, (object) array(
                                'studid' => $eachstudatt->id,
                                'gender' => strtolower($eachstudatt->gender),
                                'display' => $eachstudatt->display,
                                'value' => $eachatt->value ?? null,
                                'dayint' => $eachatt->day,
                                'keystatus' => $eachatt->keystatus ?? null,
                                'status' => $eachatt->status,
                            )
                            );
                        }
                    }
                }
                $studentstotalperday = array();
                if (count($studentstotalperdaysummary) > 0) {
                    foreach ($currentdays as $eachday) {
                        // return collect($studentstotalperdaysummary)->where('gender','female')->where('dayint', $eachday->daynum)->where('display','1')->whereIn('status',[2,10,30,3,4])->sum('value');
                        // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjchssi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                        // {
                        // if($eachday->daynum == '08')
                        // {
                        //     return collect($studentstotalperdaysummary)->where('gender','female')->where('dayint', $eachday->daynum)->values();
                        // }
                        $withrecordsmale0 = collect($studentstotalperdaysummary)->where('gender', 'male')->where('dayint', $eachday->daynum)->where('display', '1')->where('status', '!=', '1')->where('status', '!=', null)->sum('value');
                        $withrecordsmaleam0 = collect($studentstotalperdaysummary)->where('gender', 'male')->where('dayint', $eachday->daynum)->where('display', '1')->whereIn('status', [2, 11, 31])->sum('value');
                        $withrecordsmalepm0 = collect($studentstotalperdaysummary)->where('gender', 'male')->where('dayint', $eachday->daynum)->where('display', '1')->whereIn('status', [2, 10, 30])->sum('value');
                        $withrecordsfemale0 = collect($studentstotalperdaysummary)->where('gender', 'female')->where('dayint', $eachday->daynum)->where('display', '1')->where('status', '!=', '1')->where('status', '!=', null)->sum('value');
                        $withrecordsfemaleam0 = collect($studentstotalperdaysummary)->where('gender', 'female')->where('dayint', $eachday->daynum)->where('display', '1')->whereIn('status', [2, 11, 31])->sum('value');
                        $withrecordsfemalepm0 = collect($studentstotalperdaysummary)->where('gender', 'female')->where('dayint', $eachday->daynum)->where('display', '1')->whereIn('status', [2, 10, 30])->sum('value');
                        $total0 = collect($studentstotalperdaysummary)->where('dayint', $eachday->daynum)->where('display', '0')->where('status', '!=', '1')->where('status', '!=', null)->sum('value');

                        $withrecordsmale1 = collect($studentstotalperdaysummary)->where('gender', 'male')->where('dayint', $eachday->daynum)->where('display', '1')->where('status', '!=', '1')->where('status', '!=', null)->sum('value');
                        $withrecordsmaleam1 = collect($studentstotalperdaysummary)->where('gender', 'male')->where('dayint', $eachday->daynum)->where('display', '1')->whereIn('status', [2, 11, 31])->sum('value');
                        $withrecordsmalepm1 = collect($studentstotalperdaysummary)->where('gender', 'male')->where('dayint', $eachday->daynum)->where('display', '1')->whereIn('status', [2, 10, 30])->sum('value');
                        $withrecordsfemale1 = collect($studentstotalperdaysummary)->where('gender', 'female')->where('dayint', $eachday->daynum)->where('display', '1')->where('status', '!=', '1')->where('status', '!=', null)->sum('value');
                        $withrecordsfemaleam1 = collect($studentstotalperdaysummary)->where('gender', 'female')->where('dayint', $eachday->daynum)->where('display', '1')->whereIn('status', [2, 11, 31])->sum('value');
                        $withrecordsfemalepm1 = collect($studentstotalperdaysummary)->where('gender', 'female')->where('dayint', $eachday->daynum)->where('display', '1')->whereIn('status', [2, 10, 30])->sum('value');
                        $total1 = collect($studentstotalperdaysummary)->where('dayint', $eachday->daynum)->where('display', '1')->where('status', '!=', '1')->where('status', '!=', null)->sum('value');

                        array_push($studentstotalperday, (object) array(
                            'day' => $eachday->daynum,
                            'withrecordsmale' => $withrecordsmale1 + $withrecordsmale0,
                            'withrecordsmaleam' => $withrecordsmaleam1 + $withrecordsmaleam0,
                            'withrecordsmalepm' => $withrecordsmalepm1 + $withrecordsmalepm0,
                            'withrecordsfemale' => $withrecordsfemale1 + $withrecordsfemale0,
                            'withrecordsfemaleam' => $withrecordsfemaleam1 + $withrecordsfemaleam0,
                            'withrecordsfemalepm' => $withrecordsfemalepm1 + $withrecordsfemalepm0,
                            'total' => $total1 + $total0
                        )
                        );

                        // }else{
                        //     array_push($studentstotalperday,(object)array(
                        //         'day'   => $eachday->daynum,
                        //         'withrecordsmale' => collect($studentstotalperdaysummary)->where('gender','male')->where('dayint', $eachday->daynum)->where('status','!=','1')->where('status','!=',null)->count(),
                        //         'withrecordsmaleam' => collect($studentstotalperdaysummary)->where('gender','male')->where('dayint', $eachday->daynum)->whereIn('status',[2,11,31])->sum('value'),
                        //         'withrecordsmalepm' => collect($studentstotalperdaysummary)->where('gender','male')->where('dayint', $eachday->daynum)->whereIn('status',[2,10,30])->sum('value'),
                        //         'withrecordsfemale' =>collect($studentstotalperdaysummary)->where('gender','female')->where('dayint', $eachday->daynum)->where('status','!=','1')->where('status','!=',null)->count(),
                        //         'withrecordsfemaleam' => collect($studentstotalperdaysummary)->where('gender','female')->where('dayint', $eachday->daynum)->whereIn('status',[2,11,31])->sum('value'),
                        //         'withrecordsfemalepm' => collect($studentstotalperdaysummary)->where('gender','female')->where('dayint', $eachday->daynum)->whereIn('status',[2,10,30])->sum('value'),
                        //         'total' =>collect($studentstotalperdaysummary)->where('dayint', $eachday->daynum)->where('status','!=','1')->where('status','!=',null)->sum('value')
                        //     ));
                        // }
                    }

                }
                // return $studentstotalperday;

            }

            $enrollmonth = DB::table('teachersf2')
                ->where('teacherid', $teacherid)
                ->where('deleted', '0')
                ->first();

            if (count(collect($enrollmonth)) == 0) {
                $enrollmonth = 06;
                $enrollmonthstr = 'June';
            } else {
                $enrollmonth = $enrollmonth->enrollmonth;
                $enrollmonthstr = date("F", mktime(0, 0, 0, $enrollmonth, 10));
                ;
            }

            $droppedout_male = collect($students)->where('enrolledstudstatus', '3')->where('studstatdate', '<=', date($currentyearnum . '-' . (sprintf("%02d", $selectedmonth)) . '-t'))->where('gender', 'male')->count();

            $droppedout_female = collect($students)->where('enrolledstudstatus', '3')->where('studstatdate', '<=', date($currentyearnum . '-' . (sprintf("%02d", $selectedmonth)) . '-t'))->where('gender', 'female')->count();

            $droppedout_total = collect($students)->where('enrolledstudstatus', '3')->where('studstatdate', '<=', date($currentyearnum . '-' . (sprintf("%02d", $selectedmonth)) . '-t'))->count();


            $transferredout_male = collect($students)->where('enrolledstudstatus', '5')->where('studstatdate', '<=', date($currentyearnum . '-' . (sprintf("%02d", $selectedmonth)) . '-t'))->where('gender', 'male')->count();

            $transferredout_female = collect($students)->where('enrolledstudstatus', '5')->where('studstatdate', '<=', date($currentyearnum . '-' . (sprintf("%02d", $selectedmonth)) . '-t'))->where('gender', 'female')->count();

            $transferredout_total = collect($students)->where('enrolledstudstatus', '5')->where('studstatdate', '<=', date($currentyearnum . '-' . (sprintf("%02d", $selectedmonth)) . '-t'))->count();

            $transferredin_male = collect($students)->where('enrolledstudstatus', '4')->where('gender', 'male')->count();
            $transferredin_female = collect($students)->where('enrolledstudstatus', '4')->where('gender', 'female')->count();
            $transferredin_total = collect($students)->where('enrolledstudstatus', '4')->count();


            $firstfriday = date('Y-m-d', strtotime('first fri ' . $currentyearnum . '-' . $enrollmonth));

            $enrollmentasof_male = collect($students)->filter(function ($value, $key) use ($firstfriday, $currentdays) {
                if (strtolower($value->gender) == 'male') {
                    return $value;
                    // if($value->display == 1)
                    // {
                    //     return $value;
                    // }elseif($value->display == 0){
                    //     if(count($currentdays)>0)
                    //     {
                    //         if(date('Y-m', strtotime($value->studstatdate)) ==  date('Y-m',strtotime(collect($currentdays)->last()->daydate))) 
                    //         {
                    //            $value->datecondfirst = date('Y-m', strtotime($value->studstatdate));
                    //            $value->datecondsecond = date('Y-m',strtotime(collect($currentdays)->last()->daydate));
                    //             return $value;
                    //         }
                    //     }
                    // }
                }
            })->values()->count();

            $enrollmentasof_female = collect($students)->filter(function ($value, $key) use ($firstfriday, $currentdays) {
                if (strtolower($value->gender) == 'female') {
                    return $value;
                    // if($value->display == 1)
                    // {
                    //     return $value;
                    // }elseif($value->display == 0){
                    //     if(count($currentdays)>0)
                    //     {
                    //         if(date('Y-m', strtotime($value->studstatdate)) ==  date('Y-m',strtotime(collect($currentdays)->last()->daydate))) 
                    //         {
                    //            $value->datecondfirst = date('Y-m', strtotime($value->studstatdate));
                    //            $value->datecondsecond = date('Y-m',strtotime(collect($currentdays)->last()->daydate));
                    //             return $value;
                    //         }
                    //     }
                    // }
                }
            })->values()->count();

            $lateenrolled_male = collect($students)->where('enrolledstudstatus', '2')->whereBetween('studstatdate', [$currentyearnum . '-' . (sprintf("%02d", $selectedmonth - 1)) . '-1', $currentyearnum . '-' . (sprintf("%02d", $selectedmonth)) . '-' . date('t', strtotime($currentyearnum . '-' . (sprintf("%02d", $selectedmonth))))])->filter(function ($value, $key) use ($selectedmonth, $firstfriday) {

                if (date('m', strtotime($value->dateenrolled)) == $selectedmonth && strtolower($value->gender) == 'male') {
                    return $value;
                }
            })->count();

            $lateenrolled_female = collect($students)->where('enrolledstudstatus', '2')->whereBetween('studstatdate', [$currentyearnum . '-' . (sprintf("%02d", $selectedmonth - 1)) . '-1', $currentyearnum . '-' . (sprintf("%02d", $selectedmonth)) . '-' . date('t', strtotime($currentyearnum . '-' . (sprintf("%02d", $selectedmonth))))])->filter(function ($value, $key) use ($selectedmonth, $firstfriday) {

                if (date('m', strtotime($value->dateenrolled)) == $selectedmonth && strtolower($value->gender) == 'female') {
                    return $value;
                }
            })->count();


            // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma')
            // {
            $registered_male = collect($students)->filter(function ($value, $key) use ($selectedmonth, $firstfriday, $currentdays) {

                // if($value->dateenrolled >= $firstfriday)
                // {
                if (strtolower($value->gender) == 'male') {
                    if ($value->display == 1) {
                        return $value;

                    }

                    // if($value->enrolledstudstatus != 0 && $value->enrolledstudstatus != 3 && $value->enrolledstudstatus != 5)
                    // {
                    //     return $value;
                    // }else{
                    //     if(count($currentdays)>0)
                    //     {
                    //         if(date('Y-m', strtotime($value->studstatdate)) >  date('Y-m',strtotime(collect($currentdays)->last()->daydate))) 
                    //         {
                    //             return $value;
                    //         }
                    //     }
                    // }
                }
            })->count();

            $registered_female = collect($students)->filter(function ($value, $key) use ($selectedmonth, $firstfriday, $currentdays) {

                // if($value->dateenrolled >= $firstfriday)
                // {
                if (strtolower($value->gender) == 'female') {
                    if ($value->display == 1) {
                        return $value;

                    }

                    // if($value->enrolledstudstatus != 0 && $value->enrolledstudstatus != 3 && $value->enrolledstudstatus != 5)
                    // {
                    //     return $value;
                    // }else{
                    //     if(count($currentdays)>0)
                    //     {
                    //         if(date('Y-m', strtotime($value->studstatdate)) >  date('Y-m',strtotime(collect($currentdays)->last()->daydate))) 
                    //         {
                    //                    $value->datecondfirst = date('Y-m', strtotime($value->studstatdate));
                    //                    $value->datecondsecond = date('Y-m',strtotime(collect($currentdays)->last()->daydate));
                    //             return $value;
                    //         }
                    //     }
                    // }
                }
                // }
            })->count();

            $registered_total = $registered_male + $registered_female;

            if ($registered_male == 0 || $enrollmentasof_male == 0) {
                $enrollmentpercentage_male = 0;
            } else {
                $enrollmentpercentage_male = round(($registered_male / $enrollmentasof_male) * 100);

            }

            if ($registered_female == 0 || $enrollmentasof_female == 0) {
                $enrollmentpercentage_female = 0;
            } else {
                $enrollmentpercentage_female = round(($registered_female / $enrollmentasof_female) * 100);

            }

            if ($enrollmentasof_male + $enrollmentasof_female == 0) {
                $enrollmentpercentage_total = 0;
            } else {
                // $enrollmentpercentage_total = round((($registered_male+$registered_female)/($enrollmentasof_male+$enrollmentasof_female))*100);                
                $enrollmentpercentage_total = round(($enrollmentpercentage_male + $enrollmentpercentage_female) / 2, 2);
            }

            if (count($attendance) == 0) {
                $countconsecutive_male = 0;
                $countconsecutive_female = 0;
                $nlpamale = 0;
                $nlpafemale = 0;
            } else {
                if (count($attendance[0]) > 0 && count($setup) > 0) {
                    foreach ($attendance[0] as $student) {
                        // return collect($setup);
                        $remarks = DB::table('sf2_setupremarks')
                            ->where('setupid', $setup[0]->id)
                            ->where('studentid', $student->id)
                            ->where('deleted', 0)
                            ->first();

                        if ($remarks) {
                            // return collect($remarks);
                            $remark = $remarks->remarks;
                        } else {
                            $remark = null;
                        }

                        $student->remarks = $remark;
                    }
                }
                $countconsecutive_male = $attendance[1][0]->countconsecutive_male;
                $countconsecutive_female = $attendance[1][0]->countconsecutive_female;
                $nlpamale = $attendance[1][0]->nlpamale;
                $nlpafemale = $attendance[1][0]->nlpafemale;
            }
            // return (($registered_male+$registered_female)/($enrollmentasof_male+$enrollmentasof_female));

            $summarydetails = (object) array(
                'droppedout_male' => $droppedout_male,
                'droppedout_female' => $droppedout_female,
                'droppedout_total' => $droppedout_total,
                'transferredin_male' => $transferredin_male,
                'transferredin_female' => $transferredin_female,
                'transferredin_total' => $transferredin_total,
                'transferredout_male' => $transferredout_male,
                'transferredout_female' => $transferredout_female,
                'transferredout_total' => $transferredout_total,
                'enrollmentasof_male' => $enrollmentasof_male,
                'enrollmentasof_female' => $enrollmentasof_female,
                'lateenrolled_male' => $lateenrolled_male,
                'lateenrolled_female' => $lateenrolled_female,
                'registered_male' => $registered_male,
                'registered_female' => $registered_female,
                'registered_total' => $registered_total,
                'enrollmentpercentage_male' => $enrollmentpercentage_male,
                'enrollmentpercentage_female' => $enrollmentpercentage_female,
                'enrollmentpercentage_total' => $enrollmentpercentage_total,
                'countconsecutive_male' => $countconsecutive_male,
                'countconsecutive_female' => $countconsecutive_female,
                'nlpamale' => $nlpamale,
                'nlpafemale' => $nlpafemale
            );

            if ($request->get('selectedlact') == 3) {
                // return $request->all();
                if (count($setup) > 0) {
                    $equivalence = DB::table('sf2_lact')
                        ->where('teacherid', $teacherid)
                        ->where('year', $currentyearnum)
                        ->where('month', $selectedmonth)
                        ->where('sectionid', $sectionid)
                        ->where('strandid', $request->get('strandid'))
                        ->where('lact', 3)
                        ->where('deleted', '0')
                        ->get();
                } else {
                    $equivalence = array();
                    DB::table('sf2_lact')
                        ->where('teacherid', $teacherid)
                        ->where('year', $currentyearnum)
                        ->where('month', $selectedmonth)
                        ->where('sectionid', $sectionid)
                        ->where('strandid', $request->get('strandid'))
                        ->where('lact', 3)
                        ->where('deleted', '0')
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => date('Y-m-d H:i:s')
                        ]);
                }
                if (count($students) > 0) {
                    foreach ($students as $student) {
                        $student->submitted = null;
                        $student->required = null;
                        $student->dayspresent = null;
                        $student->daysabsent = null;
                        $student->remarks = '';

                        if (count($setup) > 0) {
                            $remark = DB::table('sf2_setupremarks')
                                ->where('setupid', $setup[0]->id)
                                ->where('studentid', $student->id)
                                ->where('deleted', '0')
                                ->first();

                            if ($remark) {
                                $student->remarks = $remark->remarks;
                            }
                        }

                        if (count($equivalence) > 0) {
                            $checkifexists = DB::table('sf2_lact3detail')
                                ->where('headerid', $equivalence[0]->id)
                                ->where('studid', $student->id)
                                ->where('deleted', '0')
                                ->first();

                            if ($checkifexists) {
                                if ($checkifexists->dayspresent > count($currentdays)) {
                                    $checkifexists->dayspresent = count($currentdays);
                                }
                                $student->submitted = $checkifexists->submitted;
                                $student->required = $checkifexists->required;
                                $student->dayspresent = $checkifexists->dayspresent;
                                $student->daysabsent = $checkifexists->daysabsent;
                            }
                        }
                        if (count($student->attendance) > 0) {
                            $presentdays = 0;
                            foreach ($student->attendance as $studatt) {
                                if ($student->dayspresent > $presentdays) {
                                    $studatt->status = 2;
                                    $presentdays += 1;
                                }
                            }
                        }
                    }
                }
            }
            if (count($currentdays) > 0) {
                foreach ($currentdays as $totalatt) {
                    $presentmale = 0;
                    $absentmale = 0;
                    $tardymale = 0;

                    $presentfemale = 0;
                    $absentfemale = 0;
                    $tardyfemale = 0;

                    $present = 0;
                    $absent = 0;
                    $tardy = 0;


                    foreach ($attendance[0] as $att) {

                        if (strtolower($att->gender) == 'male') {
                            $todayatt = collect($att->attendance)->where('day', $totalatt->daynum)->first();
                            if ($todayatt) {
                                if ($todayatt->combinedstatus === 1) {
                                    $presentmale += 1;
                                    $present += 1;
                                } elseif ($todayatt->combinedstatus === 'presentam') {
                                    // if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                                    // {
                                    $presentmale += 0.5;
                                    $present += 0.5;
                                    $absentmale += 0.5;
                                    $absent += 0.5;
                                    // }else{
                                    // $presentmale+=1;
                                    // $present+=1;
                                    // }
                                } elseif ($todayatt->combinedstatus === 'presentpm') {
                                    // if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                                    // {
                                    $presentmale += 0.5;
                                    $present += 0.5;
                                    $absentmale += 0.5;
                                    $absent += 0.5;
                                    // }else{
                                    // $presentmale+=1;
                                    // $present+=1;
                                    // }
                                } elseif ($todayatt->combinedstatus === 0) {
                                    $absentmale += 1;
                                    $absent += 1;
                                } elseif ($todayatt->combinedstatus === 'absentam') {
                                    $absentmale += 0.5;
                                    $presentmale += 0.5;
                                    $present += 0.5;
                                    $absent += 0.5;
                                } elseif ($todayatt->combinedstatus === 'absentpm') {
                                    $absentmale += 0.5;
                                    $presentmale += 0.5;
                                    $present += 0.5;
                                    $absent += 0.5;
                                } elseif ($todayatt->combinedstatus === 2 || $todayatt->combinedstatus === 3) {
                                    $tardymale += 1;
                                    $tardy += 1;
                                    $presentmale += 1;
                                    $present += 1;
                                } elseif ($todayatt->combinedstatus === 'lateam' || $todayatt->combinedstatus === 'latepm' || $todayatt->combinedstatus === 'ccam' || $todayatt->combinedstatus === 'ccpm') {
                                    $tardymale += 1;
                                    $tardy += 1;
                                    $presentmale += 1;
                                    $present += 1;
                                }
                            }
                        }
                        if (strtolower($att->gender) == 'female') {
                            $todayatt = collect($att->attendance)->where('day', $totalatt->daynum)->first();
                            if ($todayatt) {
                                if ($todayatt->combinedstatus === 1) {
                                    $presentfemale += 1;
                                    $present += 1;
                                } elseif ($todayatt->combinedstatus === 'presentam') {
                                    // if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                                    // {
                                    $presentfemale += 0.5;
                                    $present += 0.5;
                                    $absentfemale += 0.5;
                                    $absent += 0.5;
                                    // }else{
                                    // $presentfemale+=1;
                                    // $present+=1;
                                    // }
                                } elseif ($todayatt->combinedstatus === 'presentpm') {
                                    // if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                                    // {
                                    $presentfemale += 0.5;
                                    $present += 0.5;
                                    $absentfemale += 0.5;
                                    $absent += 0.5;
                                    // }else{
                                    // $presentfemale+=1;
                                    // $present+=1;
                                    // }
                                } elseif ($todayatt->combinedstatus === 0) {
                                    $absentfemale += 1;
                                    $absent += 1;
                                } elseif ($todayatt->combinedstatus === 'absentam') {
                                    $absentfemale += 0.5;
                                    $absent += 0.5;
                                    $presentfemale += 0.5;
                                    $present += 0.5;
                                } elseif ($todayatt->combinedstatus === 'absentpm') {
                                    $absentfemale += 0.5;
                                    $absent += 0.5;
                                    $presentfemale += 0.5;
                                    $present += 0.5;
                                } elseif ($todayatt->combinedstatus === 2 || $todayatt->combinedstatus === 3) {
                                    $tardyfemale += 1;
                                    $tardy += 1;
                                    $presentfemale += 1;
                                    $present += 1;
                                } elseif ($todayatt->combinedstatus === 'lateam' || $todayatt->combinedstatus === 'latepm' || $todayatt->combinedstatus === 'ccam' || $todayatt->combinedstatus === 'ccpm') {
                                    $tardyfemale += 1;
                                    $tardy += 1;
                                    $presentfemale += 1;
                                    $present += 1;
                                }
                            }
                        }
                    }
                    $totalatt->presentmale = $presentmale;
                    $totalatt->absentmale = $absentmale;
                    $totalatt->tardymale = $tardymale;
                    $totalatt->presentfemale = $presentfemale;
                    $totalatt->absentfemale = $absentfemale;
                    $totalatt->tardyfemale = $tardyfemale;
                    $totalatt->present = $present;
                    $totalatt->absent = $absent;
                    $totalatt->tardy = $tardy;
                }
            }
            // return $currentdays;
            if ($request->get('action') == 'filter') {
                $studentids = collect($attendance[0])->pluck('id');
                if ($request->get('selectedlact') == 3) {
                    return view('teacher.forms.form2.showsf2_lact3')
                        ->with('equivalence', $equivalence)
                        ->with('setup', $setup)
                        ->with('tvlcourse', $tvlcourse)
                        ->with('setup_numdays', $setup_numdays)
                        ->with('students', $students)
                        ->with('syid', $request->get('syid'))
                        ->with('studentids', $studentids);


                } else {
                    if (Session::get('currentPortal') == 1) {
                        // return $currentdays;
                        // return $registered_male+$registered_female;
                        // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjchssi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                        // {
                        return view('teacher.forms.form2.showschoolform2_hccsi')
                            ->with('setup', $setup)
                            ->with('locksf2', $locksf2)
                            ->with('tvlcourse', $tvlcourse)
                            ->with('setup_numdays', $setup_numdays)
                            ->with('strandid', $request->get('strandid'))
                            ->with('attendance', $attendance)
                            // ->with('maletotalperday', $maletotalperday)
                            // ->with('femaletotalperday', $femaletotalperday)
                            ->with('studentstotalperday', $studentstotalperday)
                            ->with('activedays', $currentdays)
                            ->with('summarydetails', $summarydetails)
                            ->with('registered_male', $registered_male)
                            ->with('registered_female', $registered_female)
                            ->with('registered_total', $registered_male + $registered_female)
                            ->with('enrollmonth', $enrollmonth)
                            ->with('enrollmonthstr', $enrollmonthstr)
                            ->with('syid', $request->get('syid'))
                            ->with('semid', $semid)
                            ->with('levelid', $request->get('levelid'))
                            ->with('sectionid', $request->get('sectionid'))
                            ->with('studentids', $studentids);
                        // }else{

                        //     return view('teacher.forms.form2.showschoolform2')
                        //         ->with('setup', $setup)
                        //         ->with('locksf2', $locksf2)
                        //         ->with('tvlcourse', $tvlcourse)
                        //         ->with('setup_numdays',$setup_numdays)
                        //         ->with('strandid', $request->get('strandid'))
                        //         ->with('attendance', $attendance)
                        //         ->with('studentstotalperday', $studentstotalperday)
                        //         ->with('activedays', $currentdays)
                        //         ->with('summarydetails', $summarydetails)
                        //         ->with('enrollmonth', $enrollmonth)
                        //         ->with('enrollmonthstr', $enrollmonthstr)
                        //         ->with('syid', $request->get('syid'))
                        //         ->with('levelid', $request->get('levelid'))
                        //         ->with('sectionid', $request->get('sectionid'))
                        //         ->with('studentids', $studentids);
                        // }
                    } else {
                        // return $registered_male;
                        return view('registrar.forms.form2.form2')
                            ->with('setup', $setup)
                            ->with('tvlcourse', $tvlcourse)
                            ->with('setup_numdays', $setup_numdays)
                            ->with('strandid', $request->get('strandid'))
                            ->with('attendance', $attendance)
                            // ->with('maletotalperday', $maletotalperday)
                            // ->with('femaletotalperday', $femaletotalperday)
                            ->with('studentstotalperday', $studentstotalperday)
                            ->with('activedays', $currentdays)
                            ->with('summarydetails', $summarydetails)
                            ->with('enrollmonth', $enrollmonth)
                            ->with('enrollmonthstr', $enrollmonthstr)
                            ->with('enrollmentmonth', $enrollmonthstr)
                            ->with('enrollmentasof_male', $enrollmentasof_male)
                            ->with('enrollmentasof_female', $enrollmentasof_female)
                            ->with('enrollmentasof_total', $enrollmentasof_male + $enrollmentasof_female)
                            ->with('lateenrolled_male', $lateenrolled_male)
                            ->with('lateenrolled_female', $lateenrolled_female)
                            ->with('lateenrolled_total', $lateenrolled_male + $lateenrolled_female)
                            ->with('registered_male', $registered_male)
                            ->with('registered_female', $registered_female)
                            ->with('registered_total', $registered_male + $registered_female)
                            ->with('enrollmentpercentage_male', $enrollmentpercentage_male)
                            ->with('enrollmentpercentage_female', $enrollmentpercentage_female)
                            ->with('enrollmentpercentage_total', $enrollmentpercentage_male + $enrollmentpercentage_female)
                            ->with('droppedout_male', $droppedout_male)
                            ->with('droppedout_female', $droppedout_female)
                            ->with('droppedout_total', $droppedout_male + $droppedout_female)
                            ->with('transferredin_male', $transferredin_male)
                            ->with('transferredin_female', $transferredin_female)
                            ->with('transferredin_total', $transferredin_male + $transferredin_female)
                            ->with('transferredout_male', $transferredout_male)
                            ->with('transferredout_female', $transferredout_female)
                            ->with('transferredout_total', $transferredout_male + $transferredout_female)
                            ->with('currentdays', $currentdays)
                            ->with('syid', $request->get('syid'))
                            ->with('levelid', $request->get('levelid'))
                            ->with('sectionid', $request->get('sectionid'))
                            ->with('studentids', $studentids);
                    }
                }
            } else {

                $schoolinfo = DB::table('schoolinfo')
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


                $levelname = $acadprogcode->levelname;
                $sectionname = DB::table('sections')
                    ->where('id', $request->get('sectionid'))
                    ->first()
                    ->sectionname;
                $sydesc = DB::table('sy')
                    ->where('id', $syid)
                    ->first()
                    ->sydesc;

                $teachername = DB::table('teacher')
                    ->select('lastname', 'firstname', 'middlename', 'suffix', 'title')
                    ->where('id', $teacherid)
                    ->first();


                $countconsecutive_total = $attendance[1][0]->countconsecutive_male + $attendance[1][0]->countconsecutive_female;
                $nlpatotal = $attendance[1][0]->nlpamale + $attendance[1][0]->nlpafemale;

                if ($request->get('exporttype') == 'pdf') {

                    $data = [
                        'tvlcourse' => $tvlcourse,
                        'selectedlact' => $request->get('selectedlact'),
                        'selectedmonth' => date('F', strtotime($currentyearnum . '-' . $selectedmonth)),
                        'teachername' => $teachername,
                        'levelid' => $request->get('levelid'),
                        'levelname' => $levelname,
                        'sectionname' => $sectionname,
                        'syid' => $syid,
                        'acadprogid' => $acadprogcode->id,
                        'sydesc' => $sydesc,
                        'schoolinfo' => $schoolinfo,
                        'students' => $students,
                        'attendance' => $attendance[0],
                        // 'maletotalperday'           =>  $maletotalperday,
                        // 'femaletotalperday'         =>  $femaletotalperday,
                        'studentstotalperday' => $studentstotalperday,
                        'schoolinfo' => $schoolinfo,
                        'currentdays' => $currentdays,
                        'enrollmentmonth' => $enrollmonthstr,
                        'enrollmentasof_male' => $enrollmentasof_male,
                        'enrollmentasof_female' => $enrollmentasof_female,
                        'enrollmentasof_total' => $enrollmentasof_male + $enrollmentasof_female,
                        'lateenrolled_male' => $lateenrolled_male,
                        'lateenrolled_female' => $lateenrolled_female,
                        'lateenrolled_total' => $lateenrolled_male + $lateenrolled_female,
                        'registered_male' => $registered_male,
                        'registered_female' => $registered_female,
                        'registered_total' => $registered_total,
                        'enrollmentpercentage_male' => $enrollmentpercentage_male,
                        'enrollmentpercentage_female' => $enrollmentpercentage_female,
                        'enrollmentpercentage_total' => $enrollmentpercentage_total,
                        'pam_male' => $request->get('pam_male'),
                        'pam_female' => $request->get('pam_female'),
                        'pam_total' => $request->get('pam_total'),
                        'countconsecutive_male' => $attendance[1][0]->countconsecutive_male,
                        'countconsecutive_female' => $attendance[1][0]->countconsecutive_female,
                        'countconsecutive_total' => $countconsecutive_total,
                        'nlpamale' => $attendance[1][0]->nlpamale,
                        'nlpafemale' => $attendance[1][0]->nlpafemale,
                        'nlpatotal' => $nlpatotal,
                        'droppedout_male' => $droppedout_male,
                        'droppedout_female' => $droppedout_female,
                        'droppedout_total' => $droppedout_total,
                        'transferredin_male' => $transferredin_male,
                        'transferredin_female' => $transferredin_female,
                        'transferredin_total' => $transferredin_total,
                        'transferredout_male' => $transferredout_male,
                        'transferredout_female' => $transferredout_female,
                        'transferredout_total' => $transferredout_total
                    ];


                    $monthname = date('F', strtotime($currentyearnum . '-' . $selectedmonth));
                    if (strtolower($acadprogcode->acadprogcode) == 'shs') {
                        $strandinfo = DB::table('sh_strand')
                            ->select('sh_strand.strandcode', 'sh_strand.strandname', 'sh_track.trackname')
                            ->where('sh_strand.id', $request->get('strandid'))
                            ->leftJoin('sh_track', 'sh_strand.trackid', '=', 'sh_track.id')
                            ->first();
                        $semester = $semid;

                        // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjchssi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                        // {
                        //     $pdf = PDF::loadview('teacher/pdf/pdf_sf2_shs_hccsi',$data, compact('strandinfo','semester'));
                        // }else
                        // return $data;
                        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {
                            $pdf = PDF::loadview('teacher/pdf/pdf_sf2_shs_apmc', $data, compact('strandinfo', 'semester'));
                        } else {
                            $pdf = PDF::loadview('teacher/pdf/pdf_sf2_shs_hccsi', $data, compact('strandinfo', 'semester'));
                            // return $studentstotalperday;
                            // $pdf = PDF::loadview('teacher/pdf/pdf_sf2_shs_ndm',$data, compact('strandinfo','semester'));
                        }
                        return $pdf->stream('School Form 2 ' . $acadprogcode->levelname . ' - ' . $sectionname . ' - ' . $sydesc . '_' . $monthname . '.pdf');
                    } else {
                        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma') {
                            $pdf = PDF::loadview('teacher/pdf/schoolform2preview', $data);
                        } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {
                            $pdf = PDF::loadview('teacher/pdf/pdf_sf2_jhs_apmc', $data);
                        } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjhs') {
                            $pdf = PDF::loadview('teacher/pdf/pdf_sf2_jhs_sjhst', $data);
                        } else {
                            $pdf = PDF::loadview('teacher/pdf/pdf_sf2_jhs_hccsi', $data);
                        }

                        return $pdf->stream('School Form 2 ' . $acadprogcode->levelname . ' - ' . $sectionname . ' - ' . $sydesc . '_' . $monthname . '.pdf');

                        // }else{
                        //     $pdf = PDF::loadview('teacher/pdf/schoolform2preview',$data)->setPaper('legal','landscape');

                        //     return $pdf->stream('School Form 2.pdf');
                        // }
                    }
                } elseif ($request->get('exporttype') == 'excel') {
                    // return $request->all();
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path() . '/public/excelformats/schoolform2.xlsx');
                    $sheet = $spreadsheet->getActiveSheet();
                    $borderstyle = [
                        // 'alignment' => [
                        //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        // ],
                        'borders' => [
                            'outline' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => array('argb' => '00000000'),
                            ),
                        ]
                    ];

                    // foreach(range('A','AJ') as $columnID) {
                    $sheet->getColumnDimension('AH')->setAutoSize(true);
                    // }
                    //header
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Logo');
                    $drawing->setDescription('Logo');
                    $drawing->setPath(base_path() . '/public/' . $schoolinfo->picurl);
                    $drawing->setHeight(100);
                    $drawing->setWorksheet($sheet);
                    $drawing->setCoordinates('A1');
                    $drawing->setOffsetX(20);
                    $drawing->setOffsetY(20);

                    $drawing->getShadow()->setVisible(true);
                    $drawing->getShadow()->setDirection(45);
                    $sheet->setCellValue('C6', $schoolinfo->schoolid);
                    $sheet->setCellValue('K6', $sydesc);
                    $sheet->setCellValue('X6', date('F', strtotime($selectedmonth)));
                    $sheet->mergeCells('C8:O8');
                    $sheet->setCellValue('C8', $schoolinfo->schoolname);
                    $sheet->mergeCells('X8:Y8');
                    $sheet->setCellValue('X8', filter_var($levelname, FILTER_SANITIZE_NUMBER_INT));
                    $sheet->setCellValue('AC8', $sectionname);
                    //--header

                    //footer
                    $sheet->mergeCells('AC21:AD21');
                    $sheet->setCellValue('AC21', date('F', strtotime($selectedmonth)));
                    $sheet->mergeCells('AG21:AG22');
                    $sheet->setCellValue('AG21', count($currentdays));

                    $sheet->mergeCells('AD46:AI46');
                    if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma') {
                        $sheet->setCellValue('AD46', $teachername->firstname . ' ' . $teachername->middlename[0] . '. ' . $teachername->lastname . ' ' . $teachername->suffix);
                    } else {
                        $sheet->setCellValue('AD46', $teachername->firstname . ' ' . $teachername->middlename[0] . '. ' . $teachername->lastname . ' ' . $teachername->suffix . ', LPT');
                    }
                    $sheet->mergeCells('AD50:AI50');
                    $sheet->setCellValue('AD50', $schoolinfo->authorized);
                    $sheet->getStyle('AD46')->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('AD50')->getAlignment()->setHorizontal('center');

                    $sheet->setCellValue('AH23', $enrollmentasof_male);
                    $sheet->setCellValue('AI23', $enrollmentasof_female);
                    $sheet->setCellValue('AJ23', '=SUM(AH23,AI23)');

                    $sheet->setCellValue('AH25', $lateenrolled_male);
                    $sheet->setCellValue('AI25', $lateenrolled_female);
                    $sheet->setCellValue('AJ25', '=SUM(AH25,AI25)');

                    $sheet->setCellValue('AH27', $registered_male);
                    $sheet->setCellValue('AI27', $registered_female);
                    $sheet->setCellValue('AJ27', '=SUM(AH27,AI27)');

                    $sheet->setCellValue('AH29', $enrollmentpercentage_male);
                    $sheet->setCellValue('AI29', $enrollmentpercentage_female);
                    $sheet->setCellValue('AJ29', '=SUM(AH29,AI29)');

                    $averagedetailmale = collect($studentstotalperday)->sum('withrecordsmale') / count($currentdays);
                    $averagedetailfemale = collect($studentstotalperday)->sum('withrecordsfemale') / count($currentdays);

                    $sheet->setCellValue('AH31', collect($studentstotalperday)->sum('withrecordsmale') / count($currentdays));
                    $sheet->setCellValue('AI31', collect($studentstotalperday)->sum('withrecordsfemale') / count($currentdays));
                    $sheet->setCellValue('AJ31', '=SUM(AH31,AI31)');
                    $sheet->getStyle("AH31:AJ31")->getNumberFormat()->setFormatCode('0');


                    if ($averagedetailmale > 0 || $registered_male > 0) {
                        if (number_format(($averagedetailmale / $registered_male) * 100, 1) > 100) {
                            $pammale = 100;
                        } else {
                            $pammale = number_format(($averagedetailmale / $registered_male) * 100, 1);
                        }
                    } else {
                        $pammale = 0;
                    }
                    if ($averagedetailfemale > 0 || $registered_female > 0) {
                        if (number_format(($averagedetailfemale / $registered_female) * 100, 1) > 100) {
                            $pamfemale = 100;
                        } else {
                            $pamfemale = number_format(($averagedetailfemale / $registered_female) * 100, 1);
                        }
                    } else {
                        $pamfemale = 0;
                    }
                    $sheet->setCellValue('AH33', $pammale);
                    $sheet->getStyle('AH33')->getNumberFormat()->setFormatCode('0');

                    $sheet->setCellValue('AI33', $pamfemale);
                    $sheet->setCellValue('AJ33', $pammale + $pamfemale);

                    $sheet->setCellValue('AH35', $attendance[1][0]->countconsecutive_male);
                    $sheet->setCellValue('AI35', $attendance[1][0]->countconsecutive_female);
                    $sheet->setCellValue('AJ35', '=SUM(AH35,AI35)');
                    $sheet->getStyle('AH35:AJ35')->getNumberFormat()->setFormatCode('0');

                    $sheet->setCellValue('AH37', $droppedout_male);
                    $sheet->setCellValue('AI37', $droppedout_female);
                    $sheet->setCellValue('AJ37', '=SUM(AH37,AI37)');

                    $sheet->setCellValue('AH39', $transferredout_male);
                    $sheet->setCellValue('AI39', $transferredout_female);
                    $sheet->setCellValue('AJ39', '=SUM(AH39,AI39)');

                    $sheet->setCellValue('AH41', $transferredin_male);
                    $sheet->setCellValue('AI41', $transferredin_female);
                    $sheet->setCellValue('AJ41', '=SUM(AH41,AI41)');
                    // -- footermaletotalperday

                    function getNameFromNumber($num)
                    {
                        $numeric = ($num - 1) % 26;
                        $letter = chr(65 + $numeric);
                        $num2 = intval(($num - 1) / 26);
                        if ($num2 > 0) {
                            return getNameFromNumber($num2) . $letter;
                        } else {
                            return $letter;
                        }
                    }

                    $startcolumn = 5;

                    $sheet->mergeCells('E10:' . getNameFromNumber(($startcolumn + count($currentdays)) - 1) . '10');
                    $sheet->setCellValue('E10', '(1st row for date)');
                    $sheet->getStyle('E10')->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('E10:' . getNameFromNumber(($startcolumn + count($currentdays)) - 1) . '10')->applyFromArray($borderstyle);

                    foreach ($currentdays as $day) {
                        $sheet->setCellValue(getNameFromNumber($startcolumn) . '11', $day->daynum);
                        $sheet->getStyle(getNameFromNumber($startcolumn) . '11')->applyFromArray($borderstyle);
                        if (strtolower($day->daystr) == 'thu') {
                            $sheet->setCellValue(getNameFromNumber($startcolumn) . '12', 'TH');
                        } else {
                            $sheet->setCellValue(getNameFromNumber($startcolumn) . '12', strtoupper($day->daystr[0]));
                        }
                        $sheet->getStyle(getNameFromNumber($startcolumn) . '12')->applyFromArray($borderstyle);
                        $startcolumn += 1;
                    }
                    $sheet->mergeCells(getNameFromNumber($startcolumn) . '10:' . getNameFromNumber($startcolumn + 1) . '11');
                    $sheet->setCellValue(getNameFromNumber($startcolumn) . '10', "Total for\nthe Month");
                    $sheet->getStyle(getNameFromNumber($startcolumn) . '10')->getAlignment()->setWrapText(true);
                    $sheet->getStyle(getNameFromNumber($startcolumn) . '10')->getAlignment()->setHorizontal('center');
                    $sheet->getStyle(getNameFromNumber($startcolumn) . '10:' . getNameFromNumber($startcolumn + 1) . '11')->applyFromArray($borderstyle);

                    $sheet->getColumnDimension(getNameFromNumber($startcolumn))->setWidth(12);
                    $sheet->getColumnDimension(getNameFromNumber($startcolumn + 1))->setWidth(12);

                    $sheet->setCellValue(getNameFromNumber($startcolumn) . '12', 'ABSENT');
                    $sheet->getStyle(getNameFromNumber($startcolumn) . '12')->applyFromArray($borderstyle);
                    $sheet->setCellValue(getNameFromNumber($startcolumn + 1) . '12', 'TARDY');
                    $sheet->getStyle(getNameFromNumber($startcolumn + 1) . '12')->applyFromArray($borderstyle);
                    $startcolumn += 2;

                    $sheet->mergeCells(getNameFromNumber($startcolumn) . '10:' . getNameFromNumber($startcolumn + 5) . '12');
                    $sheet->setCellValue(getNameFromNumber($startcolumn) . '10', "REMARKS (If DROPPED OUT, state reason,\nplease refer to legend number 2. If\nTRANSFERRED IN/OUT, write the name of\nSchool.)");
                    $sheet->getStyle(getNameFromNumber($startcolumn) . '10')->getAlignment()->setWrapText(true);
                    $sheet->getStyle(getNameFromNumber($startcolumn) . '10')->getAlignment()->setHorizontal('center');
                    $sheet->getStyle(getNameFromNumber($startcolumn) . '10:' . getNameFromNumber($startcolumn + 5) . '12')->applyFromArray($borderstyle);

                    $startcellno = 13;
                    $sheet->insertNewRowBefore(14, 1);
                    if (count($students) > 0) {
                        $malecount = 1;
                        $maleabsentcount = 0;
                        $maletardycount = 0;
                        foreach ($attendance[0] as $student) {
                            $malecolumncount = 5;
                            if (strtolower($student->gender) == 'male' && $student->display == 1) {
                                $sheet->setCellValue('A' . $startcellno, $malecount);
                                $sheet->setCellValue('B' . $startcellno, $student->lrn);
                                $sheet->getStyle('B' . $startcellno)->getNumberFormat()->setFormatCode('0');
                                $sheet->mergeCells('C' . $startcellno . ':D' . $startcellno);
                                $sheet->setCellValue('C' . $startcellno, ucwords(strtolower($student->lastname . ', ' . $student->firstname . ' ' . $student->suffix . ' ' .
                                    $student->middlename)));
                                $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('left');

                                $absentcount = 0;
                                $tardycount = 0;
                                foreach ($student->attendance as $att) {
                                    //1 = absent; 2 = present; 3 = late; 4 = cc;
                                    $stat = "";
                                    // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                                    // {
                                    //     if($att->status == 1)
                                    //     {
                                    //         $absentcount+=1;
                                    //         $stat = "";
                                    //     }
                                    //     elseif($att->status == 2)
                                    //     {
                                    //         $stat = "X";
                                    //     }
                                    //     elseif($att->status == 3)
                                    //     {
                                    //         $tardycount+=1;
                                    //         $stat = "L";
                                    //     }
                                    //     elseif($att->status == 4)
                                    //     {
                                    //         $tardycount+=1;
                                    //         $stat = "CC";
                                    //     }
                                    // }else{
                                    if ($att->status == 1) {
                                        $absentcount += 1;
                                        $stat = "X";
                                    } elseif ($att->status == 2) {
                                        $stat = "";
                                    } elseif ($att->status == 3) {
                                        $tardycount += 1;
                                        $stat = "L";
                                    } elseif ($att->status == 4) {
                                        $tardycount += 1;
                                        $stat = "CC";
                                    }
                                    // }
                                    $sheet->setCellValue(getNameFromNumber($malecolumncount) . $startcellno, $stat);
                                    $sheet->getStyle(getNameFromNumber($malecolumncount) . $startcellno)->applyFromArray($borderstyle);
                                    $malecolumncount += 1;
                                }
                                $sheet->setCellValue(getNameFromNumber($malecolumncount) . $startcellno, $absentcount);
                                $sheet->getStyle(getNameFromNumber($malecolumncount) . $startcellno)->applyFromArray($borderstyle);
                                $malecolumncount += 1;
                                $sheet->setCellValue(getNameFromNumber($malecolumncount) . $startcellno, $tardycount);
                                $sheet->getStyle(getNameFromNumber($malecolumncount) . $startcellno)->applyFromArray($borderstyle);
                                $malecolumncount += 1;
                                $sheet->mergeCells(getNameFromNumber($malecolumncount) . $startcellno . ':' . getNameFromNumber($malecolumncount + 5) . $startcellno);
                                $sheet->setCellValue(getNameFromNumber($malecolumncount) . $startcellno, $student->remarks);
                                $sheet->getStyle(getNameFromNumber($malecolumncount) . $startcellno . ':' . getNameFromNumber($malecolumncount + 5) . $startcellno)->applyFromArray($borderstyle);

                                $startcellno += 1;
                                $malecount += 1;
                                $sheet->insertNewRowBefore($startcellno, 1);
                                $maleabsentcount += $absentcount;
                                $maletardycount += $tardycount;

                            }
                        }
                        $sheet->removeRow($startcellno);
                        $sheet->removeRow($startcellno);
                        $startcellno += 1;
                        $maletotalcolumncount = 5;

                        foreach ($studentstotalperday as $maleperday) {

                            $sheet->setCellValue(getNameFromNumber($maletotalcolumncount) . $startcellno, $maleperday->withrecordsmale);
                            $sheet->getStyle(getNameFromNumber($maletotalcolumncount) . $startcellno)->applyFromArray($borderstyle);
                            $maletotalcolumncount += 1;
                        }
                        $sheet->setCellValue(getNameFromNumber($maletotalcolumncount) . $startcellno, '=SUM(' . getNameFromNumber($maletotalcolumncount) . '13,' . getNameFromNumber($maletotalcolumncount) . ($startcellno - 2) . ')');
                        $sheet->getStyle(getNameFromNumber($maletotalcolumncount) . $startcellno)->applyFromArray($borderstyle);
                        $maletotalcolumncount += 1;

                        $sheet->setCellValue(getNameFromNumber($maletotalcolumncount) . $startcellno, '=SUM(' . getNameFromNumber($maletotalcolumncount) . '13,' . getNameFromNumber($maletotalcolumncount) . ($startcellno - 2) . ')');
                        $sheet->getStyle(getNameFromNumber($maletotalcolumncount) . $startcellno)->applyFromArray($borderstyle);
                        $maletotalcolumncount += 1;
                        $sheet->mergeCells(getNameFromNumber($maletotalcolumncount) . $startcellno . ':' . getNameFromNumber($maletotalcolumncount + 5) . $startcellno);
                        $sheet->getStyle(getNameFromNumber($maletotalcolumncount) . $startcellno . ':' . getNameFromNumber($maletotalcolumncount + 5) . $startcellno)->applyFromArray($borderstyle);

                        $sheet->removeRow($startcellno + 1);
                        $startcellno += 1;
                        //female
                        $femalecount = 1;
                        $femaleabsentcount = 0;
                        $femaletardycount = 0;
                        foreach ($attendance[0] as $student) {
                            $femalecolumncount = 5;
                            if (strtolower($student->gender) == 'female' && $student->display == 1) {
                                $sheet->setCellValue('A' . $startcellno, $femalecount);
                                $sheet->setCellValue('B' . $startcellno, $student->lrn);
                                $sheet->getStyle('B' . $startcellno)->getNumberFormat()->setFormatCode('0');
                                $sheet->mergeCells('C' . $startcellno . ':D' . $startcellno);
                                $sheet->setCellValue('C' . $startcellno, ucwords(strtolower($student->lastname . ', ' . $student->firstname . ' ' . $student->suffix . ' ' .
                                    $student->middlename)));
                                $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('left');

                                $absentcount = 0;
                                $tardycount = 0;
                                foreach ($student->attendance as $att) { //1 = absent; 2 = present; 3 = late; 4 = cc;
                                    $stat = " ";
                                    // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                                    // {
                                    //     if($att->status == 1)
                                    //     {
                                    //         $absentcount+=1;
                                    //         $stat = "";
                                    //     }
                                    //     elseif($att->status == 2)
                                    //     {
                                    //         $stat = "X";
                                    //     }
                                    //     elseif($att->status == 3)
                                    //     {
                                    //         $tardycount+=1;
                                    //         $stat = "L";
                                    //     }
                                    //     elseif($att->status == 4)
                                    //     {
                                    //         $tardycount+=1;
                                    //         $stat = "CC";
                                    //     }
                                    // }else{
                                    if ($att->status == 1) {
                                        $absentcount += 1;
                                        $stat = "X";
                                    } elseif ($att->status == 2) {
                                        $stat = "";
                                    } elseif ($att->status == 3) {
                                        $tardycount += 1;
                                        $stat = "L";
                                    } elseif ($att->status == 4) {
                                        $tardycount += 1;
                                        $stat = "CC";
                                    }
                                    // }
                                    $sheet->setCellValue(getNameFromNumber($femalecolumncount) . $startcellno, $stat);
                                    $sheet->getStyle(getNameFromNumber($femalecolumncount) . $startcellno)->applyFromArray($borderstyle);
                                    $femalecolumncount += 1;
                                }
                                $sheet->setCellValue(getNameFromNumber($femalecolumncount) . $startcellno, $absentcount);
                                $sheet->getStyle(getNameFromNumber($femalecolumncount) . $startcellno)->applyFromArray($borderstyle);
                                $femalecolumncount += 1;
                                $sheet->setCellValue(getNameFromNumber($femalecolumncount) . $startcellno, $tardycount);
                                $sheet->getStyle(getNameFromNumber($femalecolumncount) . $startcellno)->applyFromArray($borderstyle);
                                $femalecolumncount += 1;

                                $sheet->mergeCells(getNameFromNumber($femalecolumncount) . $startcellno . ':' . getNameFromNumber($femalecolumncount + 5) . $startcellno);
                                $sheet->setCellValue(getNameFromNumber($femalecolumncount) . $startcellno, $student->remarks);
                                $sheet->getStyle(getNameFromNumber($femalecolumncount) . $startcellno . ':' . getNameFromNumber($femalecolumncount + 5) . $startcellno)->applyFromArray($borderstyle);

                                $startcellno += 1;
                                $femalecount += 1;
                                $sheet->insertNewRowBefore($startcellno, 1);

                                $femaleabsentcount += $absentcount;
                                $femaletardycount += $tardycount;
                            }
                        }
                        $startcellno += 1;
                        $femaletotalcolumncount = 5;
                        foreach ($studentstotalperday as $femaleperday) {
                            $sheet->setCellValue(getNameFromNumber($femaletotalcolumncount) . $startcellno, $femaleperday->withrecordsfemale);
                            $sheet->getStyle(getNameFromNumber($femaletotalcolumncount) . $startcellno)->applyFromArray($borderstyle);
                            $femaletotalcolumncount += 1;
                        }
                        $sheet->setCellValue(getNameFromNumber($femaletotalcolumncount) . $startcellno, $femaleabsentcount);
                        $sheet->getStyle(getNameFromNumber($femaletotalcolumncount) . $startcellno)->applyFromArray($borderstyle);
                        $femaletotalcolumncount += 1;

                        $sheet->setCellValue(getNameFromNumber($femaletotalcolumncount) . $startcellno, $femaletardycount);
                        $sheet->getStyle(getNameFromNumber($femaletotalcolumncount) . $startcellno)->applyFromArray($borderstyle);
                        $femaletotalcolumncount += 1;
                        $sheet->mergeCells(getNameFromNumber($femaletotalcolumncount) . $startcellno . ':' . getNameFromNumber($femaletotalcolumncount + 5) . $startcellno);
                        $sheet->getStyle(getNameFromNumber($femaletotalcolumncount) . $startcellno . ':' . getNameFromNumber($femaletotalcolumncount + 5) . $startcellno)->applyFromArray($borderstyle);

                        $startcellno += 1;
                        $combinedtotalcolumncount = 5;
                        foreach ($studentstotalperday as $studentstotal) {
                            $sheet->setCellValue(getNameFromNumber($combinedtotalcolumncount) . $startcellno, $studentstotal->total);
                            $sheet->getStyle(getNameFromNumber($combinedtotalcolumncount) . $startcellno)->applyFromArray($borderstyle);
                            $combinedtotalcolumncount += 1;
                        }
                        $sheet->setCellValue(getNameFromNumber($combinedtotalcolumncount) . $startcellno, $maleabsentcount + $femaleabsentcount);
                        $sheet->getStyle(getNameFromNumber($combinedtotalcolumncount) . $startcellno)->applyFromArray($borderstyle);
                        $combinedtotalcolumncount += 1;

                        $sheet->setCellValue(getNameFromNumber($combinedtotalcolumncount) . $startcellno, $maletardycount + $femaletardycount);
                        $sheet->getStyle(getNameFromNumber($combinedtotalcolumncount) . $startcellno)->applyFromArray($borderstyle);
                        $combinedtotalcolumncount += 1;
                        $sheet->mergeCells(getNameFromNumber($combinedtotalcolumncount) . $startcellno . ':' . getNameFromNumber($combinedtotalcolumncount + 5) . $startcellno);
                        $sheet->getStyle(getNameFromNumber($combinedtotalcolumncount) . $startcellno . ':' . getNameFromNumber($combinedtotalcolumncount + 5) . $startcellno)->applyFromArray($borderstyle);

                    }

                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="School Form 2 ' . $levelname . ' - ' . $sectionname . '.xlsx"');
                    $writer->save("php://output");
                }
            }

        } elseif ($request->get('action') == 'getcalendar') {
            // return Session::get('currentPortal');
            // return $setup;
            $list = array();
            $today = date("d"); // Current day
            $month = $request->get('selectedmonth');
            $year = $request->get('selectedyear');
            function draw_calendar($month, $year, $setup_numdays)
            {

                $numdays = 0;
                if ($setup_numdays) {
                    $numdays = $setup_numdays->days;
                }
                $numdays_counter = 0;
                // return $numdays;
                /* draw table */
                $calendar = '<table class="table-bordered" style="width: 100%; table-layout: fixed;">';

                /* table headings */
                $headings = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                $calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings) . '</td></tr>';

                /* days and weeks vars now ... */
                $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
                $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
                $days_in_this_week = 1;
                $day_counter = 0;
                $dates_array = array();
                // return $running_day;
                /* row for week one */
                $calendar .= '<tr class="calendar-row">';

                /* print "blank" days until the first of the current week */
                for ($x = 0; $x < $running_day; $x++):
                    $calendar .= '<td class="calendar-day-np"> </td>';
                    $days_in_this_week++;
                endfor;
                // return $numdays;
                /* keep going with days.... */
                for ($list_day = 1; $list_day <= $days_in_month; $list_day++):
                    $addthisclass = 'btn-success';
                    if (strtolower(date('l', strtotime($year . '-' . $month . '-' . $list_day))) != 'saturday' && strtolower(date('l', strtotime($year . '-' . $month . '-' . $list_day))) != 'sunday') {
                        if ($numdays > 0) {
                            if ($numdays_counter < $numdays) {
                                $addthisclass = 'btn-success';
                                $numdays_counter += 1;
                            } else {
                                $addthisclass = '';
                            }
                        }
                        $calendar .= '<td class="calendar-day day-number active-date ' . $addthisclass . '" data-id="' . $list_day . '" style="text-align: center;vertical-align: middle !important; padding: 10px;">';
                    } else {
                        $calendar .= '<td class="calendar-day day-number active-date " data-id="' . $list_day . '" style="text-align: center;vertical-align: middle !important; padding: 10px;">';
                    }
                    /* add in the day number */
                    $calendar .= $list_day;

                    /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
                    // $calendar.= str_repeat('<p> </p>',2);

                    $calendar .= '</td>';
                    if ($running_day == 6):
                        $calendar .= '</tr>';
                        if (($day_counter + 1) != $days_in_month):
                            $calendar .= '<tr class="calendar-row">';
                        endif;
                        $running_day = -1;
                        $days_in_this_week = 0;
                    endif;
                    $days_in_this_week++;
                    $running_day++;
                    $day_counter++;
                endfor;

                /* finish the rest of the days in the week */
                if ($days_in_this_week < 8):
                    for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
                        $calendar .= '<td class="calendar-day-np"> </td>';
                    endfor;
                endif;

                /* final row */
                $calendar .= '</tr>';

                /* end the table */
                $calendar .= '</table>';

                /* all done, return result */
                return $calendar;
            }

            /* sample usages */
            echo '<h2><center>' . date('F Y', strtotime($year . '-' . $month)) . '</center></h2>';

            return draw_calendar($month, $year, $setup_numdays);

        } elseif ($request->get('action') == 'createsetup') {
            $selecteddates = collect($request->get('dates'))->sort()->values();
            $setupid = DB::table('sf2_setup')
                ->insertGetId([
                    'teacherid' => $teacherid,
                    'month' => $request->get('selectedmonth'),
                    'syid' => $request->get('syid'),
                    'sectionid' => $request->get('sectionid'),
                    'year' => $request->get('selectedyear'),
                    'strandid' => $request->get('strandid'),
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s'),
                    'course' => $request->get('tvlcourse')
                ]);

            if (count($selecteddates) > 0) {
                foreach ($selecteddates as $adddate) {
                    DB::table('sf2_setupdates')
                        ->insert([
                            'setupid' => $setupid,
                            'dates' => $request->get('selectedyear') . '-' . $request->get('selectedmonth') . '-' . $adddate,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);
                }
            }
            if ($request->get('selectedlact') == 3) {
                DB::table('sf2_lact')
                    ->insert([
                        'teacherid' => $teacherid,
                        'year' => $request->get('selectedyear'),
                        'month' => $request->get('selectedmonth'),
                        'sectionid' => $request->get('sectionid'),
                        'strandid' => $request->get('strandid'),
                        'equivalence' => count($selecteddates),
                        'lact' => $request->get('selectedlact'),
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);
            }
        } elseif ($request->get('action') == 'deletesetup') {
            $setupids = DB::table('sf2_setup')
                ->where('teacherid', $teacherid)
                ->where('month', $request->get('selectedmonth'))
                ->where('year', $request->get('selectedyear'))
                ->where('syid', $request->get('syid'))
                ->where('sectionid', $request->get('sectionid'))
                ->where('deleted', '0')
                ->get();

            if (collect($setupids)->where('strandid', $request->get('strandid'))->count() > 0) {
                $setupid = collect($setupids)->where('strandid', $request->get('strandid'))->first()->id;
            } else {
                $setupid = collect($setupids)->first()->id;
            }
            // return 
            DB::table('sf2_setup')
                ->where('id', $setupid)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('sf2_setupdates')
                ->where('setupid', $setupid)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);
        } elseif ($request->get('action') == 'getremarks') {
            // return $request->all();
            $remark = DB::table('sf2_setupremarks')
                ->where('setupid', $setup[0]->id)
                ->where('studentid', $request->get('studentid'))
                ->where('deleted', '0')
                ->first();

            if ($remark) {
                return $remark->remarks;
            } else {
                return '';
            }

        } elseif ($request->get('action') == 'updateremarks') {
            // return $request->all();
            $remark = DB::table('sf2_setupremarks')
                ->where('setupid', $setup[0]->id)
                ->where('studentid', $request->get('studentid'))
                ->where('deleted', '0')
                ->get();
            // return $remark;
            if (count($remark) == 0) {
                DB::table('sf2_setupremarks')
                    ->insert([
                        'setupid' => $setup[0]->id,
                        'studentid' => $request->get('studentid'),
                        'remarks' => $request->get('remarks'),
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);


                return $request->get('remarks');

            } else {

                DB::table('sf2_setupremarks')
                    ->where('id', $remark[0]->id)
                    ->update([
                        'remarks' => $request->get('remarks'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);

                return $request->get('remarks');
            }
        }

    }

    public function form2shsindex(Request $request)
    {
        $sections = DB::table('sectiondetail')
            ->where('teacherid', DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted', '0')->first()->id)
            ->where('syid', $request->get('syid'))
            ->where('sectionid', $request->get('sectionid'))
            ->where('deleted', '0')
            ->get();

        $strandids = array();

        if (count($sections) > 0) {
            foreach ($sections as $section) {
                $getstrandids = DB::table('sh_enrolledstud')
                    ->select('strandid', 'sh_strand.strandcode', 'sh_strand.strandname', 'sh_track.id as trackid', 'sh_track.trackname')
                    ->leftJoin('sh_strand', 'sh_enrolledstud.strandid', '=', 'sh_strand.id')
                    ->leftJoin('sh_track', 'sh_strand.trackid', '=', 'sh_track.id')
                    ->where('sh_enrolledstud.syid', $request->get('syid'))
                    ->where('sh_enrolledstud.semid', $request->get('semid'))
                    ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                    ->where('sh_enrolledstud.sectionid', $section->sectionid)
                    ->where('sh_enrolledstud.deleted', '0')
                    ->where('studstatus', '!=', '0')
                    ->distinct()
                    ->get();

                if (count($getstrandids) > 0) {
                    foreach ($getstrandids as $strandid) {
                        $numofstudents = DB::table('sh_enrolledstud')
                            ->where('sh_enrolledstud.syid', $request->get('syid'))
                            ->where('sh_enrolledstud.semid', $request->get('semid'))
                            ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                            ->where('sh_enrolledstud.sectionid', $section->sectionid)
                            ->where('sh_enrolledstud.strandid', $strandid->strandid)
                            ->where('sh_enrolledstud.deleted', '0')
                            ->where('studstatus', '!=', '0')
                            ->get();

                        $strandid->numofstudents = count($numofstudents);
                        $strandid->numofenrolledstudents = collect($numofstudents)->where('studstatus', '1')->count();
                        $strandid->numoflateenrolledstudents = collect($numofstudents)->where('studstatus', '2')->count();
                        $strandid->numofdroppedoutstudents = collect($numofstudents)->where('studstatus', '3')->count();
                        $strandid->numoftransferredinstudents = collect($numofstudents)->where('studstatus', '4')->count();
                        $strandid->numoftransferredoutstudents = collect($numofstudents)->where('studstatus', '5')->count();
                        $strandid->numofwithdrawnstudents = collect($numofstudents)->where('studstatus', '6')->count();

                        array_push($strandids, $strandid);

                    }
                }
            }
        }

        $levelname = DB::table('gradelevel')
            ->where('id', $request->get('levelid'))
            ->first();
        return view('teacher.forms.shsindex')
            ->with('formtype', $request->get('formtype'))
            ->with('levelname', $levelname->levelname)
            ->with('levelid', $levelname->id)
            ->with('sectionid', $request->get('sectionid'))
            ->with('selectedmonth', $request->get('selectedmonth'))
            ->with('syid', $request->get('syid'))
            ->with('semid', $request->get('semid'))
            ->with('strands', $strandids);
    }
    public function form2summarytable(Request $request)
    {
        $acadprogcode = Db::table('gradelevel')
            ->select('academicprogram.acadprogcode')
            ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->where('gradelevel.id', $request->get('levelid'))
            ->first()
            ->acadprogcode;


        if (strtolower($acadprogcode) == 'shs') {
            $students = Db::table('studinfo')
                ->select('studinfo.id', 'studinfo.gender', 'sh_enrolledstud.dateenrolled', 'sh_enrolledstud.studstatus')
                ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                ->where('studinfo.deleted', '0')
                ->where('sh_enrolledstud.deleted', '0')
                ->where('studinfo.sectionid', $request->get('sectionid'))
                ->where('sh_enrolledstud.studstatus', '!=', '6')
                ->where('studinfo.studstatus', '!=', '0')
                ->where('studinfo.studstatus', '!=', '6')
                ->whereBetween('sh_enrolledstud.dateenrolled', [date('Y') . '-' . $request->get('enrollmentmonth') . '-' . date('01'), date('Y') . '-' . $request->get('selectedmonth') . '-' . date('t')])
                ->where('sy.isactive', '1')
                ->get();
        } else {
            $students = Db::table('studinfo')
                ->select('studinfo.id', 'studinfo.gender', 'enrolledstud.dateenrolled', 'enrolledstud.studstatus')
                ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                ->where('studinfo.deleted', '0')
                ->where('enrolledstud.deleted', '0')
                ->where('studinfo.sectionid', $request->get('sectionid'))
                ->where('studinfo.studstatus', '!=', '0')
                ->where('enrolledstud.studstatus', '!=', '6')
                ->whereBetween('enrolledstud.dateenrolled', [date('Y') . '-' . $request->get('enrollmentmonth') . '-' . date('01'), date('Y') . '-' . $request->get('selectedmonth') . '-' . date('t')])
                ->where('sy.isactive', '1')
                ->get();
        }

        $firstfriday = date('Y-m-d', strtotime('first fri ' . date('Y') . '-' . $request->get('enrollmentmonth')));
        // $enrollmentasof_male = collect($students)->where('gender', 'MALE')->count();
        // $enrollmentasof_female = collect($students)->where('gender', 'FEMALE')->count();
        $enrollmentasof_male = collect($students)->filter(function ($value, $key) use ($firstfriday) {
            if ($value->studstatus == 2 && strtolower($value->gender) == 'male') {
                return $value;
            }
        })->where('dateenrolled', '<=', $firstfriday)->count();
        $enrollmentasof_female = collect($students)->filter(function ($value, $key) use ($firstfriday) {
            if ($value->studstatus == 2 && strtolower($value->gender) == 'female') {
                return $value;
            }
        })->where('dateenrolled', '<=', $firstfriday)->count();

        $selectedmonth = $request->get('selectedmonth');

        // $lateenrolled_male = collect($students)->filter(function ($value, $key) use($selectedmonth){
        //     // if(date('m',strtotime($value->dateenrolled)) == $selectedmonth && strtolower($value->gender) == 'male')
        //     if(date('m',strtotime($value->dateenrolled)) == $selectedmonth && $value->studstatus == 2 && strtolower($value->gender) == 'male')
        //     {
        //         return $value;
        //     }
        // })->count();
        // $lateenrolled_female = collect($students)->filter(function ($value, $key) use($selectedmonth){
        //     if(date('m',strtotime($value->dateenrolled)) == $selectedmonth && $value->studstatus == 2 && strtolower($value->gender) == 'female')
        //     {
        //         return $value;
        //     }
        // })->count();

        $lateenrolled_male = collect($students)->whereBetween('studstatdate', [date('Y') . '-' . (sprintf("%02d", $selectedmonth - 1)) . '-1', date('Y') . '-' . (sprintf("%02d", $selectedmonth)) . '-' . date('t', strtotime(date('Y') . '-' . (sprintf("%02d", $selectedmonth))))])->filter(function ($value, $key) use ($selectedmonth, $firstfriday) {

            if (date('m', strtotime($value->dateenrolled)) == $selectedmonth && strtolower($value->gender) == 'male') {
                return $value;
            }
        })->count();

        $lateenrolled_female = collect($students)->whereBetween('studstatdate', [date('Y') . '-' . (sprintf("%02d", $selectedmonth - 1)) . '-1', date('Y') . '-' . (sprintf("%02d", $selectedmonth)) . '-' . date('t', strtotime(date('Y') . '-' . (sprintf("%02d", $selectedmonth))))])->filter(function ($value, $key) use ($selectedmonth, $firstfriday) {

            if (date('m', strtotime($value->dateenrolled)) == $selectedmonth && strtolower($value->gender) == 'female') {
                return $value;
            }
        })->count();

        $registered_male = collect($students)->filter(function ($value, $key) use ($selectedmonth) {
            if (date('m', strtotime($value->dateenrolled)) == $selectedmonth && $value->studstatus == 1 && strtolower($value->gender) == 'male') {
                return $value;
            }
        })->count();
        $registered_female = collect($students)->filter(function ($value, $key) use ($selectedmonth) {
            if (date('m', strtotime($value->dateenrolled)) == $selectedmonth && $value->studstatus == 1 && strtolower($value->gender) == 'female') {
                return $value;
            }
        })->count();

        $registered_total = $registered_male + $registered_female;

        if ($registered_male == 0 || $enrollmentasof_male == 0) {
            $enrollmentpercentage_male = 0;
        } else {
            $enrollmentpercentage_male = ($registered_male / $enrollmentasof_male) * 100;
        }

        if ($registered_female == 0 || $enrollmentasof_female == 0) {
            $enrollmentpercentage_female = 0;
        } else {
            $enrollmentpercentage_female = ($registered_female / $enrollmentasof_female) * 100;
        }

        if ($enrollmentasof_male + $enrollmentasof_female == 0) {
            $enrollmentpercentage_total = 0;
        } else {
            $enrollmentpercentage_total = (($registered_male + $registered_female) / ($enrollmentasof_male + $enrollmentasof_female)) * 100;
        }

        // $averagedailyattendance_male = 
        return array(
            'enrollmentasof_male' => $enrollmentasof_male,
            'enrollmentasof_female' => $enrollmentasof_female,
            'lateenrolled_male' => $lateenrolled_male,
            'lateenrolled_female' => $lateenrolled_female,
            'registered_male' => $registered_male,
            'registered_female' => $registered_female,
            'registered_total' => $registered_total,
            'enrollmentpercentage_male' => $enrollmentpercentage_male,
            'enrollmentpercentage_female' => $enrollmentpercentage_female,
            'enrollmentpercentage_total' => $enrollmentpercentage_total
        );

    }
    public function enrollmentmonth(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $teacherid = Db::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first()
            ->id;
        $checkifexists = DB::table('teachersf2')
            ->where('teacherid', $teacherid)
            ->where('deleted', '0')
            ->first();

        if (count(collect($checkifexists)) == 0) {
            DB::table('teachersf2')
                ->insert([
                    'teacherid' => $teacherid,
                    'enrollmonth' => $request->get('selectedenrollmentmonth'),
                    'createdby' => $teacherid,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);
        } else {
            DB::table('teachersf2')
                ->where('teacherid', $teacherid)
                ->where('deleted', '0')
                ->update([
                    'enrollmonth' => $request->get('selectedenrollmentmonth'),
                    'updatedby' => $teacherid,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);
        }
        return back();
    }
    public function form5(Request $request)
    {

        $curriculum = $request->get('curriculum');
        $divisionRep = $request->get('divrep');

        if ($request->has('syid')) {
            $sy = DB::table('sy')
                ->select('id', 'sydesc')
                ->where('id', $request->get('syid'))
                ->first();
        } else {
            $sy = DB::table('sy')
                ->select('id', 'sydesc')
                ->where('isactive', 1)
                ->first();
        }
        if ($request->has('semid')) {
            $sem = DB::table('semester')
                ->select('id', 'semester')
                ->where('id', $request->get('semid'))
                ->first();
        } else {
            $sem = DB::table('semester')
                ->select('id', 'semester')
                ->where('isactive', 1)
                ->first();
        }

        $acadprogid = DB::table('gradelevel')->where('id', $request->get('levelid'))->first()->acadprogid;
        if (Session::get('currentPortal') == 1) {
            $getSectionAndLevel = DB::table('teacher')
                ->select('teacher.id', 'sections.levelid', 'gradelevel.levelname', 'sections.id as sectionid', 'sections.sectionname', 'academicprogram.acadprogcode', 'academicprogram.id as acadprogid', 'semid')
                ->leftJoin('sectiondetail', 'teacher.id', '=', 'sectiondetail.teacherid')
                ->leftJoin('sections', 'sectiondetail.sectionid', '=', 'sections.id')
                ->leftJoin('gradelevel', 'sections.levelid', '=', 'gradelevel.id')
                ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->where('teacher.userid', auth()->user()->id)
                ->where('sectiondetail.syid', $sy->id)
                ->where('gradelevel.id', $request->get('levelid'))
                ->where('sections.id', $request->get('sectionid'))
                ->get();
        } else {
            $getSectionAndLevel = DB::table('teacher')
                ->select('teacher.id', 'sections.levelid', 'gradelevel.levelname', 'sections.id as sectionid', 'sections.sectionname', 'academicprogram.acadprogcode', 'academicprogram.id as acadprogid', 'semid')
                ->leftJoin('sectiondetail', 'teacher.id', '=', 'sectiondetail.teacherid')
                ->leftJoin('sections', 'sectiondetail.sectionid', '=', 'sections.id')
                ->leftJoin('gradelevel', 'sections.levelid', '=', 'gradelevel.id')
                ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->where('sectiondetail.syid', $sy->id)
                ->where('gradelevel.id', $request->get('levelid'))
                ->where('sections.id', $request->get('sectionid'))
                ->get();
        }

        $getSectionAndLevel = collect($getSectionAndLevel)->where('acadprogid', $acadprogid)->values()->all();

        if ($request->get('acadprogid') == 5) {
            $getSectionAndLevel = collect($getSectionAndLevel)->where('semid', $sem->id)->values()->all();
        }

        if (count($getSectionAndLevel) == 0) {
            $getSectionAndLevel = array();

            $gradelevelname = DB::table('gradelevel')
                ->where('id', $request->get('levelid'))
                ->first();

            $acadprogcode = DB::table('academicprogram')
                ->where('id', $gradelevelname->acadprogid)
                ->first();

            $sectionname = DB::table('sections')
                ->where('id', $request->get('sectionid'))
                ->first();

            array_push($getSectionAndLevel, (object) array(
                'teacherid' => 0,
                'levelid' => $gradelevelname->id,
                'levelname' => $gradelevelname->levelname,
                'sectionid' => $sectionname->id,
                'sectionname' => $sectionname->sectionname,
                'acadprogid' => $acadprogcode->id,
                'acadprogcode' => $acadprogcode->acadprogcode
            )
            );
        }

        $getSchoolInfo = DB::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'schoolinfo.picurl',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'refregion.regDesc as region',
                'schoolinfo.regiontext',
                'schoolinfo.divisiontext',
                'schoolinfo.districttext'
            )
            ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
            ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
            ->get();

        if (Session::get('currentPortal') == 1) {
            $getTeacherName = DB::table('users')
                ->select('teacher.firstname', 'teacher.middlename', 'teacher.lastname', 'teacher.suffix')
                ->leftJoin('teacher', 'users.id', '=', 'teacher.userid')
                ->where('users.id', auth()->user()->id)
                ->first();

        } else {
            $getTeacherName = DB::table('teacher')
                ->select('teacher.firstname', 'teacher.middlename', 'teacher.lastname', 'teacher.suffix')
                ->leftJoin('sectiondetail', 'teacher.id', '=', 'sectiondetail.teacherid')
                ->where('sectiondetail.syid', $sy->id)
                ->where('sectiondetail.sectionid', $request->get('sectionid'))
                // ->where('users.id',auth()->user()->id)
                ->first();
        }
        if (!$getTeacherName) {
            $getTeacherName = (object) array(
                'teacherid' => 0,
                'firstname' => null,
                'lastname' => null,
                'middlename' => null,
                'suffix' => null
            );
        }
        // return $getSectionAndLevel;
        // if(count($getSectionAndLevel)==0){

        //     if($request->get('action') == 'show')
        //     {
        //         return view('teacher.forms.form5.showschoolform5')
        //             ->with('gradeAndLevel',$getSectionAndLevel)
        //             ->with('school',$getSchoolInfo)
        //             ->with('sy',$sy)
        //             ->with('teachername',$getTeacherName);
        //     }

        // }
        // else{
        // return $request->get('sectionid');

        if ($acadprogid == 5) {
            $students = DB::table('sh_enrolledstud')
                ->select(
                    'studinfo.id',
                    'studinfo.lrn',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'academicprogram.id as acadprogid',
                    'gradelevel.id as levelid',
                    'sections.id as sectionid',
                    'sections.blockid',
                    'sh_enrolledstud.sectionid as ensectid',
                    'sh_enrolledstud.levelid as enlevelid',
                    'sh_enrolledstud.promotionstatus',
                    'sh_enrolledstud.strandid',
                    'sh_enrolledstud.semid'
                )
                ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
                ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                ->where('sh_enrolledstud.syid', $sy->id)
                ->where('sh_enrolledstud.semid', $sem->id)
                ->where('sh_enrolledstud.deleted', 0)
                ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                ->where('studinfo.deleted', 0)
                ->whereIn('sh_enrolledstud.studstatus', [1, 2, 4])
                ->distinct()
                ->orderBy('studinfo.lastname', 'asc')
                ->get();

        } else {
            $students = DB::table('enrolledstud')
                ->select(
                    'studinfo.id',
                    'studinfo.lrn',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'academicprogram.id as acadprogid',
                    'gradelevel.id as levelid',
                    'sections.id as sectionid',
                    'sections.blockid',
                    'enrolledstud.sectionid as ensectid',
                    'enrolledstud.levelid as enlevelid',
                    'enrolledstud.promotionstatus'
                )
                ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
                ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->where('enrolledstud.sectionid', $request->get('sectionid'))
                ->where('enrolledstud.syid', $sy->id)
                ->where('enrolledstud.deleted', 0)
                ->where('enrolledstud.levelid', $request->get('levelid'))
                ->where('studinfo.deleted', 0)
                ->whereIn('enrolledstud.studstatus', [1, 2, 4])
                ->distinct()
                ->orderBy('studinfo.lastname', 'asc')
                ->get();
        }
        foreach ($students as $student) {
            // $student->lastname = ucwords(mb_convert_case($student->lastname, MB_CASE_LOWER, "UTF-8"));
            // $student->firstname = ucwords(mb_convert_case($student->firstname, MB_CASE_LOWER, "UTF-8"));
            $student->promotionstat = '';
            $student->gender = strtolower($student->gender);

            $student->sortthis = $student->lastname . ', ' . $student->firstname . ' ' . $student->suffix . ' ' . $student->middlename;
            if ($student->levelid == 14 || $student->levelid == 15) {
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($student->levelid, $student->id, $sy->id, $student->strandid, null, $student->ensectid);
                $studgrades = collect($studgrades)->where('semid', $sem->id)->values();
                $temp_grades = array();
                $generalaverage = array();
                foreach ($studgrades as $item) {
                    if ($item->id == 'G1') {
                        array_push($generalaverage, $item);
                    } else {
                        if ($item->strandid == $student->strandid) {
                            array_push($temp_grades, $item);
                        }
                        if ($item->strandid == null) {
                            array_push($temp_grades, $item);
                        }
                    }
                }
                $grades = $temp_grades;

            } else {
                $schoolyear = DB::table('sy')->where('id', $sy->id)->first();
                Session::put('schoolYear', $schoolyear);
                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct') {
                    $student->acadprogid = 4;
                    $checkGradingVersion = DB::table('zversion_control')->where('module', 1)->where('isactive', 1)->first();

                    if ($checkGradingVersion->version == 'v1') {
                        $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV4($student, true, 'sf9', $schoolyear->id);

                    }
                    if ($checkGradingVersion->version == 'v2') {
                        $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($student, true, 'sf9', $schoolyear->id);

                    }
                    $grades = $gradesv4;

                    $grades = collect($grades)->unique('subjectcode');
                    $generalaverage = array();
                } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb') {
                    $schoolyear = DB::table('sy')->where('id', $sy->id)->first();
                    Session::put('schoolYear', $schoolyear);
                    $grades = GenerateGrade::reportCardV4($student, true, 'sf9');

                    $generalaverage = \App\Models\Grades\GradesData::general_average($grades);
                    $grades = \App\Models\Grades\GradesData::get_finalrating($grades, $student->acadprogid);
                    $generalaverage = \App\Models\Grades\GradesData::get_finalrating($generalaverage, $student->acadprogid);
                } else {

                    // return $student->id;
                    // $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,$sy->strandid,null,$sy->ensectid,true);
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($student->levelid, $student->id, $schoolyear->id, null, null, $student->ensectid, true);
                    // return $studgrades;
                    $temp_grades = array();
                    $generalaverage = array();
                    foreach ($studgrades as $item) {
                        if ($item->id == 'G1') {
                            if ($item->finalrating == null) {
                                $item->finalrating = $item->lfr;
                            }
                            array_push($generalaverage, $item);
                            // array_push($temp_grades,$item);
                        } else {
                            array_push($temp_grades, $item);
                        }
                    }

                    $studgrades = $temp_grades;
                    $grades = collect($studgrades)->sortBy('sortid')->values();
                }
            }
            // return $generalaverage;
            if (count($generalaverage) > 0) {
                $student->fcomp = $generalaverage[0]->fcomp;
                $student->fraward = $generalaverage[0]->fraward;
            } else {
                $student->fcomp = null;
                $student->fraward = null;
            }
            // $attendance_setup = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($sy->id);
            // $grades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $student->levelid,$student->id,$sy->id);
            // $grades = collect($grades)->sortBy('sortid')->values();
            // $generalaverage = collect($grades)->where('id','G1')->values();
            // unset($grades[count($grades)-1]);
            // return $grades;
            if (count($grades) > 0) {
                $failedsubjects = 0;
                if (collect($grades)->contains('quarter1')) {
                    $qtype = 'quarter';
                    $countsubjwithgrades = collect($grades)->where('quarter1', '!=', null)->where('quarter2', '!=', null)->where('quarter3', '!=', null)->where('quarter4', '!=', null)->count();
                } else {
                    $qtype = 'q';
                    $countsubjwithgrades = collect($grades)->where('q1', '!=', null)->where('q2', '!=', null)->where('q3', '!=', null)->where('q4', '!=', null)->count();
                }

                if ($countsubjwithgrades == count($grades)) {

                    foreach ($grades as $grade) {
                        if ($qtype == 'q') {
                            $grade->quarter1 = $grade->q1;
                            $grade->quarter2 = $grade->q2;
                            $grade->quarter3 = $grade->q3;
                            $grade->quarter4 = $grade->q4;
                        }

                        // $finalrating = number_format(($grade->quarter1+$grade->quarter2+$grade->quarter3+$grade->quarter4)/4);
                        // $grade->finalrating = $grade->finalrating;
                        $grade->failed = 0;

                        if (collect($grade)->has('finalrating')) {
                            if ($grade->finalrating < 75 && $grade->inMAPEH == 0) {
                                $failedsubjects += 1;
                                $grade->failed = 1;
                            }
                        } else {
                            $grade->failed = 0;
                        }
                        // $generalaverage += $finalrating;
                    }

                    if ($student->promotionstatus == 1) {
                        if ($failedsubjects > 2) {
                            $student->promotionstat = 'PROMOTED';
                        }
                    } else {
                        if ($failedsubjects > 2) {
                            $student->promotionstat = 'RETAINED';
                        } elseif ($failedsubjects == 2 || $failedsubjects == 1) {
                            $student->promotionstat = 'CONDITIONAL';
                        } else {
                            $student->promotionstat = 'PROMOTED';
                        }
                    }

                } else {

                    foreach ($grades as $grade) {
                        if ($qtype == 'q') {
                            if (collect($grade)->has('q1')) {
                                $grade->quarter1 = $grade->q1;
                                $grade->quarter2 = $grade->q2;
                                $grade->quarter3 = $grade->q3;
                                $grade->quarter4 = $grade->q4;
                            }
                        }
                        if (collect($grade)->has('finalrating')) {
                            $finalrating = $grade->finalrating;
                            if ($finalrating < 75) {
                                $failedsubjects += 1;
                                $grade->failed = 1;
                            }
                        } else {
                            $grade->failed = 0;
                        }
                    }
                    if ($student->promotionstatus == 1) {
                        $student->promotionstat = 'PROMOTED';
                    } else {
                        $student->promotionstat = '';
                    }
                }

            }
            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm') {
                $student->generalaverage = number_format($generalaverage[0]->finalrating);
            } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs') {
                $student->generalaverage = number_format($generalaverage[0]->fcomp, 2);
            } else {

                if (count($generalaverage) == 0) {

                    $student->generalaverage = null;

                } else {
                    $student->generalaverage = number_format($generalaverage[0]->finalrating);
                }
            }
            $student->grades = $grades;

            $checkifexists = DB::table('sf5')
                ->where('studid', $student->id)
                ->where('sectionid', $request->get('sectionid'))
                ->where('syid', $sy->id)
                ->where('levelid', $request->get('levelid'))
                ->where('deleted', 0)
                ->first();
            if ($checkifexists) {
                $actiontaken = $checkifexists->actiontaken;
                if ($actiontaken == 1) {
                    $student->promotionstat = 'PROMOTED';
                } elseif ($actiontaken == 2) {
                    $student->promotionstat = 'CONDITIONAL';
                } elseif ($actiontaken == 3) {
                    $student->promotionstat = 'RETAINED';
                }
            } else {
                if ($student->generalaverage == null || $student->generalaverage == 0) {
                    $actiontaken = 0;
                } else {
                    if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {
                        $actiontaken = 1;
                        $student->promotionstat = 'PROMOTED';

                    } else {
                        if ($student->generalaverage <= 74) {
                            $actiontaken = 3;
                            $student->promotionstat = 'RETAINED';
                        } elseif ($student->generalaverage == 75) {
                            $student->promotionstat = 'CONDITIONAL';
                            $actiontaken = 2;
                        } else {
                            $actiontaken = 1;
                            $student->promotionstat = 'PROMOTED';
                        }
                    }
                }
            }
            $student->actiontaken = $actiontaken;
        }

        if (count($getSectionAndLevel) == 0) {
            $getPrincipal = (object) array(
                'firstname' => null,
                'lastname' => null,
                'middlename' => null,
                'suffix' => null
            );
            $getTeacherName = (object) array(
                'firstname' => null,
                'lastname' => null,
                'middlename' => null,
                'suffix' => null
            );
        } else {

            $getPrincipal = DB::table('gradelevel')
                ->select('teacher.firstname', 'teacher.middlename', 'teacher.lastname', 'teacher.suffix')
                ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->leftJoin('teacher', 'academicprogram.principalid', '=', 'teacher.id')
                ->where('gradelevel.id', $getSectionAndLevel[0]->levelid)
                ->first();

        }
        $students = collect($students)->sortBy('sortthis')->values();
        // return $students;
        if ($request->get('action') == 'show') {
            if (Session::get('currentPortal') == 1) {
                // return $students;
                // return collect($sem);
                // return view('teacher.forms.form5.showschoolform5')
                return view('teacher.forms.form5.form5')
                    ->with('school', $getSchoolInfo)
                    ->with('sy', $sy)
                    ->with('sem', $sem)
                    ->with('acadprogid', $request->get('acadprogid'))
                    ->with('gradeAndLevel', $getSectionAndLevel)
                    ->with('students', $students)
                    ->with('teachername', $getTeacherName)
                    ->with('principalname', $getPrincipal);
            } else {
                return view('registrar.forms.form5.form5')
                    ->with('school', $getSchoolInfo)
                    ->with('sy', $sy)
                    ->with('sem', $sem)
                    ->with('acadprogid', $request->get('acadprogid'))
                    ->with('gradeAndLevel', $getSectionAndLevel)
                    ->with('students', $students)
                    ->with('teachername', $getTeacherName)
                    ->with('principalname', $getPrincipal);
            }
        } elseif ($request->get('action') == 'updateactiontaken') {
            $actiontakens = json_decode($request->get('actiontakens'));
            if (count($actiontakens) > 0) {
                foreach ($actiontakens as $actiontaken) {
                    $checkifexists = DB::table('sf5')
                        ->where('studid', $actiontaken->studid)
                        ->where('levelid', $request->get('levelid'))
                        ->where('syid', $request->get('syid'))
                        ->where('sectionid', $request->get('sectionid'))
                        ->where('deleted', '0')
                        ->first();

                    if ($checkifexists) {
                        DB::table('sf5')
                            ->where('id', $checkifexists->id)
                            ->update([
                                'actiontaken' => $actiontaken->actiontaken,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => date('Y-m-d H:i:s')
                            ]);
                    } else {
                        DB::table('sf5')
                            ->insert([
                                'studid' => $actiontaken->studid,
                                'syid' => $request->get('syid'),
                                'levelid' => $request->get('levelid'),
                                'sectionid' => $request->get('sectionid'),
                                'actiontaken' => $actiontaken->actiontaken,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }
        } elseif ($request->get('action') == 'export') {
            // return $getSectionAndLevel;

            if ($request->get('exporttype') == 'pdf') {
                $layout = $request->get('layout');
                if ($request->get('template') == 1) {

                    $pdf = PDF::loadview('schoolforms/form5/pdf_sf5_temp1_dompdf', compact('getSchoolInfo', 'sy', 'getSectionAndLevel', 'curriculum', 'divisionRep', 'students', 'getTeacherName', 'getPrincipal', 'layout'));
                    $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
                    return $pdf->stream('School Form 5 - Report on Promotion & Level of Proficiency.pdf');
                } elseif ($request->get('template') == 2) {
                    $pdf = PDF::loadview('schoolforms/form5/pdf_sf5_temp2_dompdf', compact('getSchoolInfo', 'sy', 'getSectionAndLevel', 'curriculum', 'divisionRep', 'students', 'getTeacherName', 'getPrincipal', 'layout'));
                    $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
                    return $pdf->stream('School Form 5 - Report on Promotion & Level of Proficiency.pdf');
                } elseif ($request->get('template') == 3) {
                    // $pdf = PDF::loadview('teacher/pdf/pdf_sf5_default',compact('getSchoolInfo','sy','getSectionAndLevel','curriculum','divisionRep','students','getTeacherName','getPrincipal','getValues'));

                    // return $pdf->stream('School Form 5 '.$getSectionAndLevel[0]->levelname.' - '.$getSectionAndLevel[0]->sectionname.'.pdf');
                    // return $students;
                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    // set document information
                    $pdf->SetCreator('CK');
                    $pdf->SetAuthor('CK Children\'s Publishing');
                    // $pdf->SetTitle($schoolinfo->schoolname.' - Number of Enrollees');
                    $pdf->SetSubject('Number of Enrollees');

                    // set header and footer fonts
                    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

                    // set default monospaced font
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                    // set margins
                    $pdf->SetMargins(5, 9, 5);
                    // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                    // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);

                    // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
                    // set auto page breaks
                    $pdf->SetAutoPageBreak(TRUE, 9);

                    // set image scale factor
                    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                    // set some language-dependent strings (optional)
                    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                        require_once (dirname(__FILE__) . '/lang/eng.php');
                        $pdf->setLanguageArray($l);
                    }

                    // ---------------------------------------------------------

                    // set font
                    // $pdf->SetFont('dejavusans', '', 10);


                    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                    // Print a table

                    // add a page

                    if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {
                        $pdf->AddPage('L', 'GOVERNMENTLEGAL');
                        $view = \View::make('teacher/pdf/pdf_sf5_default', compact('getSchoolInfo', 'sy', 'getSectionAndLevel', 'curriculum', 'divisionRep', 'students', 'getTeacherName', 'getPrincipal', 'getValues'));

                        $html = $view->render();

                        // $pdf->Image(base_path().'/public/'.substr($getSchoolInfo[0]->picurl, 0, strpos($getSchoolInfo[0]->picurl, "?")), 8, 15, 15, 15, '', '', '', false, 400, '', false, false, 0);

                        $pdf->Image(base_path() . '/public/assets/images/department_of_Education.png', 8, 10, 25, 25, '', '', '', false, 300, '', false, false, 0);
                    } else {
                        // return $students;
                        $pdf->AddPage('P', 'GOVERNMENTLEGAL');
                        $view = \View::make('teacher/pdf/schoolform5preview', compact('getSchoolInfo', 'sy', 'getSectionAndLevel', 'curriculum', 'divisionRep', 'students', 'getTeacherName', 'getPrincipal'));

                        $html = $view->render();

                        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm') {
                            $pdf->Image(base_path() . '/public/' . substr($getSchoolInfo[0]->picurl, 0, strpos($getSchoolInfo[0]->picurl, "?")), 6, 12, 22, 22, '', '', '', false, 300, '', false, false, 0);
                        } else {
                            $pdf->Image(base_path() . '/public/' . substr($getSchoolInfo[0]->picurl, 0, strpos($getSchoolInfo[0]->picurl, "?")), 8, 15, 15, 15, '', '', '', false, 300, '', false, false, 0);
                        }
                        $pdf->Image(base_path() . '/public/assets/images/department_of_Education.png', 190, 15, 15, 15, '', '', '', false, 300, '', false, false, 0);
                    }


                    $pdf->writeHTML($html, true, false, false, false, '');

                    $pdf->lastPage();

                    // ---------------------------------------------------------
                    //Close and output PDF document
                    if (count($getSectionAndLevel) == 0) {
                        $pdf->Output('School Form 5.pdf', 'I');
                    } else {
                        $pdf->Output('School Form 5 ' . $getSectionAndLevel[0]->levelname . ' - ' . $getSectionAndLevel[0]->sectionname . '.pdf', 'I');
                    }
                }


            } elseif ($request->get('exporttype') == 'excel') {

                // return $getSchoolInfo;
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path() . '/public/excelformats/schoolform5.xlsx');
                $sheet = $spreadsheet->getActiveSheet();
                $borderstyle = [
                    // 'alignment' => [
                    //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    // ],
                    'borders' => [
                        'outline' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => array('argb' => '00000000'),
                        ),
                    ]
                ];
                $signatories = DB::table('signatory')
                    ->where('form', 'form5')
                    ->where('syid', $sy->id)
                    ->where('deleted', '0')
                    ->get();

                if (count($signatories) > 0) {
                    if (collect($signatories)->where('levelid', $getSectionAndLevel[0]->levelid)->count() == 0) {
                        $signatories = collect($signatories)->where('levelid', 0)->values();
                    } else {
                        $signatories = collect($signatories)->where('levelid', $getSectionAndLevel[0]->levelid)->values();
                    }


                }

                $picurl = explode('?', $getSchoolInfo[0]->picurl);

                if (count($picurl) > 0) {
                    $picurl = $picurl[0];

                } else {
                    $picurl = $getSchoolInfo[0]->picurl;
                }

                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(base_path() . '/public/' . $picurl);
                $drawing->setHeight(100);
                $drawing->setWorksheet($sheet);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(30);
                $drawing->setOffsetY(25);
                // $drawing->setRotation(25);
                $drawing->getShadow()->setVisible(true);
                $drawing->getShadow()->setDirection(50);

                $sheet->setCellValue('C3', str_replace('REGION ', '', $getSchoolInfo[0]->regiontext));
                $sheet->setCellValue('E3', str_replace('CITY', '', $getSchoolInfo[0]->divisiontext));
                $sheet->setCellValue('J3', $getSchoolInfo[0]->districttext);

                $sheet->mergeCells('C5:D5');
                $sheet->setCellValue('C5', $getSchoolInfo[0]->schoolid);
                $sheet->setCellValue('G5', $sy->sydesc);
                $sheet->setCellValue('J5', $curriculum);

                $sheet->setCellValue('C7', $getSchoolInfo[0]->schoolname);
                $sheet->setCellValue('J7', filter_var($getSectionAndLevel[0]->levelname, FILTER_SANITIZE_NUMBER_INT));
                $sheet->setCellValue('L7', $getSectionAndLevel[0]->sectionname);


                $sheet->setCellValue('B35', collect($students)->where('gender', 'male')->count());
                $sheet->setCellValue('B64', collect($students)->where('gender', 'female')->count());
                $sheet->setCellValue('B65', collect($students)->count());
                $sheet->setCellValue('N15', collect($students)->where('promotionstat', 'PROMOTED')->where('gender', 'male')->count());
                $sheet->setCellValue('O15', collect($students)->where('promotionstat', 'PROMOTED')->where('gender', 'female')->count());
                $sheet->setCellValue('P15', collect($students)->where('promotionstat', 'PROMOTED')->count());

                $sheet->setCellValue('N17', collect($students)->where('promotionstat', 'CONDITIONAL')->where('gender', 'male')->count());
                $sheet->setCellValue('O17', collect($students)->where('promotionstat', 'CONDITIONAL')->where('gender', 'female')->count());
                $sheet->setCellValue('P17', collect($students)->where('promotionstat', 'CONDITIONAL')->count());

                $sheet->setCellValue('N19', collect($students)->where('promotionstat', 'RETAINED')->where('gender', 'male')->count());
                $sheet->setCellValue('O19', collect($students)->where('promotionstat', 'RETAINED')->where('gender', 'female')->count());
                $sheet->setCellValue('P19', collect($students)->where('promotionstat', 'RETAINED')->count());

                $sheet->setCellValue('N24', collect($students)->where('generalaverage', '<', 75)->where('gender', 'male')->count());
                $sheet->setCellValue('O24', collect($students)->where('generalaverage', '<', 75)->where('gender', 'female')->count());
                $sheet->setCellValue('P24', collect($students)->where('generalaverage', '<', 75)->count());

                $sheet->setCellValue('N26', collect($students)->where('generalaverage', '>', 74)->where('generalaverage', '<', 80)->where('gender', 'male')->count());
                $sheet->setCellValue('O26', collect($students)->where('generalaverage', '>', 74)->where('generalaverage', '<', 80)->where('gender', 'female')->count());
                $sheet->setCellValue('P26', collect($students)->where('generalaverage', '>', 74)->where('generalaverage', '<', 80)->count());

                $sheet->setCellValue('N28', collect($students)->where('generalaverage', '>', 79)->where('generalaverage', '<', 85)->where('gender', 'male')->count());
                $sheet->setCellValue('O28', collect($students)->where('generalaverage', '>', 79)->where('generalaverage', '<', 85)->where('gender', 'female')->count());
                $sheet->setCellValue('P28', collect($students)->where('generalaverage', '>', 79)->where('generalaverage', '<', 85)->count());

                $sheet->setCellValue('N30', collect($students)->where('generalaverage', '>', 84)->where('generalaverage', '<', 90)->where('gender', 'male')->count());
                $sheet->setCellValue('O30', collect($students)->where('generalaverage', '>', 84)->where('generalaverage', '<', 90)->where('gender', 'female')->count());
                $sheet->setCellValue('P30', collect($students)->where('generalaverage', '>', 84)->where('generalaverage', '<', 90)->count());

                $sheet->setCellValue('N32', collect($students)->where('generalaverage', '>', 89)->where('gender', 'male')->count());
                $sheet->setCellValue('O32', collect($students)->where('generalaverage', '>', 89)->where('gender', 'female')->count());
                $sheet->setCellValue('P32', collect($students)->where('generalaverage', '>', 89)->count());


                $middlename = $getTeacherName->middlename != null ? $getTeacherName->middlename[0] . '. ' : '';

                $sheet->setCellValue('M38', $getTeacherName->firstname . ' ' . $middlename . $getTeacherName->lastname);

                if (count($signatories) > 0) {
                    $sheet->setCellValue('M42', $signatories[0]->title);
                    $sheet->setCellValue('M43', $signatories[0]->name);
                    $sheet->setCellValue('M44', $signatories[0]->description);

                    if (isset($signatories[1])) {
                        $sheet->setCellValue('M47', $signatories[1]->title);
                        $sheet->setCellValue('M48', $signatories[1]->name);
                        $sheet->setCellValue('M50', $signatories[1]->description);
                    }
                } else {
                    $sheet->setCellValue('M43', DB::table('schoolinfo')->first()->authorized);
                }

                $startcellno = 13;
                $malecellcounts = 0;
                $femalecellcounts = 0;

                if (count($students) > 0) {
                    foreach ($students as $student) {
                        if (strtolower($student->gender) == 'male') {
                            if ($malecellcounts == 21) {
                                $sheet->insertNewRowBefore($startcellno, 1);
                                $sheet->mergeCells('B' . $startcellno . ':E' . $startcellno);
                                $sheet->mergeCells('H' . $startcellno . ':I' . $startcellno);
                                $sheet->mergeCells('J' . $startcellno . ':K' . $startcellno);
                            } else {
                                $malecellcounts += 1;
                            }
                            $sheet->setCellValue('A' . $startcellno, ' ' . $student->lrn);
                            $sheet->setCellValue('B' . $startcellno, ucwords(strtolower($student->lastname . ', ' . $student->firstname)) . ' ' . $student->suffix . ' ' . ucwords(strtolower($student->middlename)));
                            $sheet->getStyle('B' . $startcellno)->getAlignment()->setHorizontal('left');
                            if ($student->generalaverage != 0) {
                                $sheet->setCellValue('F' . $startcellno, $student->generalaverage);
                            }
                            $sheet->setCellValue('G' . $startcellno, $student->promotionstat);
                            $startcellno += 1;
                        }
                    }


                    if ($malecellcounts == 21) {
                        $startcellno += 3;
                    } elseif ($malecellcounts < 21) {
                        $startcellno += (2 + (21 - $malecellcounts));
                    } else {
                        $startcellno += 3;
                    }
                    foreach ($students as $student) {
                        if (strtolower($student->gender) == 'female') {
                            if ($femalecellcounts == 28) {
                                $sheet->insertNewRowBefore($startcellno, 1);
                                $sheet->mergeCells('B' . $startcellno . ':E' . $startcellno);
                                $sheet->mergeCells('H' . $startcellno . ':I' . $startcellno);
                                $sheet->mergeCells('J' . $startcellno . ':K' . $startcellno);
                            } else {
                                $femalecellcounts += 1;
                            }
                            $sheet->setCellValue('A' . $startcellno, ' ' . $student->lrn);
                            $sheet->setCellValue('B' . $startcellno, ucwords(strtolower($student->lastname . ', ' . $student->firstname)) . ' ' . $student->suffix . ' ' . ucwords(strtolower($student->middlename)));
                            $sheet->getStyle('B' . $startcellno)->getAlignment()->setHorizontal('left');
                            if ($student->generalaverage != 0) {
                                $sheet->setCellValue('F' . $startcellno, $student->generalaverage);
                            }
                            $sheet->setCellValue('G' . $startcellno, $student->promotionstat);
                            $startcellno += 1;
                        }
                    }
                }
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="School Form 5 ' . $getSectionAndLevel[0]->levelname . ' - ' . $getSectionAndLevel[0]->sectionname . '.xlsx"');
                $writer->save("php://output");
            }
        }

    }
    public function form9(Request $request)
    {
        // return $request->all();

        $sem = DB::table('semester')
            ->where('isactive', '1')
            ->first();

        $semid = $request->get('semid');

        if ($request->has('syid')) {
            $sy = DB::table('sy')
                ->select('id', 'sydesc')
                ->where('id', $request->get('syid'))
                ->first();
        } else {
            $sy = DB::table('sy')
                ->select('id', 'sydesc')
                ->where('isactive', 1)
                ->first();
        }

        $getId = DB::table('teacher')
            ->select(
                'teacher.id',
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid',
                'gradelevel.levelname',
                'academicprogram.acadprogcode'
            )
            ->join('sectiondetail', 'teacher.id', '=', 'sectiondetail.teacherid')
            ->join('sections', 'sectiondetail.sectionid', '=', 'sections.id')
            ->join('gradelevel', 'sections.levelid', '=', 'gradelevel.id')
            ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->where('sectiondetail.syid', $sy->id)
            ->where('sections.id', $request->get('sectionid'))
            ->where('teacher.userid', auth()->user()->id)
            ->get();

        if ($request->get('action') == 'show') {
            if (count($getId) == 0) {

                return view('teacher.forms.form9.reportcard');

            } else {
                if (strtolower($getId[0]->acadprogcode) == "shs") {
                    $students = DB::table('sh_enrolledstud')
                        ->select(
                            'studinfo.*'
                        )
                        ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
                        ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                        ->where('sections.teacherid', $getId[0]->id)
                        ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                        ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                        ->where('sh_enrolledstud.syid', $sy->id)
                        ->where('sh_enrolledstud.semid', $semid)
                        ->where('sh_enrolledstud.studstatus', '!=', '0')
                        ->where('sh_enrolledstud.studstatus', '!=', '6')
                        ->where('studinfo.studstatus', '!=', '6')
                        ->where('studinfo.deleted', '0')
                        ->where('sh_enrolledstud.deleted', '0')
                        ->orderBy('studinfo.lastname', 'asc')
                        ->distinct()
                        ->get();

                } else {
                    $students = DB::table('enrolledstud')
                        ->select(
                            'studinfo.*'
                        )
                        ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
                        // ->where('enrolledstud.teacherid',$getId[0]->id)
                        ->where('enrolledstud.sectionid', $request->get('sectionid'))
                        ->where('enrolledstud.syid', $sy->id)
                        ->where('enrolledstud.studstatus', '!=', '0')
                        ->where('enrolledstud.studstatus', '!=', '6')
                        ->where('studinfo.studstatus', '!=', '6')
                        ->where('studinfo.deleted', '0')
                        ->where('enrolledstud.deleted', '0')
                        ->orderBy('studinfo.lastname', 'asc')
                        ->distinct()
                        ->get();
                }

                return view('teacher.forms.form9.reportcard')
                    ->with('students', $students)
                    ->with('syid', $sy->id)
                    ->with('info', $getId);

            }
        } elseif ($request->get('action') == 'preview') {

            $studid = $request->get('studentid');
            $id = $request->get('studentid');
            $syid = $request->get('syid');

            if ($syid == null) {
                $syid = DB::table('sy')->where('isactive', 1)->first()->id;
            }

            $teacherid = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first();

            $strand = null;
            $studid = $id;

            $schoolyear = DB::table('sy')->where('id', $syid)->first();

            $student = DB::table('enrolledstud')
                ->where('enrolledstud.studid', $studid)
                ->where('enrolledstud.deleted', 0)
                ->where('enrolledstud.syid', $syid)
                ->join('studinfo', function ($join) {
                    $join->on('studinfo.id', '=', 'enrolledstud.studid');
                    $join->where('studinfo.deleted', 0);
                })
                ->join('sections', function ($join) {
                    $join->on('enrolledstud.sectionid', '=', 'sections.id');
                    $join->where('sections.deleted', 0);
                })
                ->join('gradelevel', function ($join) {
                    $join->on('enrolledstud.levelid', '=', 'gradelevel.id');
                    $join->where('gradelevel.deleted', 0);
                })
                ->select(
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    'acadprogid',
                    'enrolledstud.levelid',
                    'enrolledstud.sectionid',
                    'dob',
                    'gender',
                    'levelname',
                    'sections.sectionname',
                    'lrn'
                )
                ->first();

            if (!isset($student->levelid)) {

                $student = DB::table('sh_enrolledstud')
                    ->where('sh_enrolledstud.studid', $studid)
                    ->where('sh_enrolledstud.deleted', 0)
                    ->where('sh_enrolledstud.syid', $syid)
                    ->join('studinfo', function ($join) {
                        $join->on('sh_enrolledstud.studid', '=', 'studinfo.id');
                        $join->where('studinfo.deleted', 0);
                    })
                    ->join('sections', function ($join) {
                        $join->on('sh_enrolledstud.sectionid', '=', 'sections.id');
                        $join->where('sections.deleted', 0);
                    })
                    ->join('gradelevel', function ($join) {
                        $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id');
                        $join->where('gradelevel.deleted', 0);
                    })
                    ->select(
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'acadprogid',
                        'sh_enrolledstud.strandid',
                        'sh_enrolledstud.levelid',
                        'sh_enrolledstud.sectionid',
                        'dob',
                        'gender',
                        'levelname',
                        'sections.sectionname',
                        'lrn'
                    )
                    ->first();

                $strand = $student->strandid;

            }



            if (!isset($student->levelid)) {
                return "Student not Found!";
            }

            $schoolinfo = DB::table('schoolinfo')->first();
            if (strtoupper($schoolinfo->abbreviation) == 'SPCT') {
                if ($student->levelid == 2) {
                    return redirect('/grade/prekinder/pdf?studid=' . $studid . '&syid=' . $syid);
                } else if ($student->levelid == 3) {
                    return redirect('/grade/preschool/pdf?studid=' . $studid . '&syid=' . $syid);
                }
            }


            $acad = $student->acadprogid;
            $gradelevel = $student->levelid;
            $sectionid = $student->sectionid;


            $birthDate = $student->dob; // Your birthdate
            $currentYear = explode("-", $schoolyear->sydesc)[0]; // Current Year
            $birthYear = date('Y', strtotime($birthDate)); // Extracted Birth Year using strtotime and date() function
            $age = $currentYear - $birthYear; // Current year minus birthyear
            $student->age = $age;

            $middlename = explode(" ", $student->middlename);
            $temp_middle = '';
            if ($middlename != null) {
                foreach ($middlename as $middlename_item) {
                    if (strlen($middlename_item) > 0) {
                        $temp_middle .= $middlename_item[0] . '.';
                    }
                }
            }

            $student->student = $student->lastname . ', ' . $student->firstname . ' ' . $student->suffix . ' ' . $temp_middle;

            $sectioninfo = DB::table('sectiondetail')
                ->where('sectionid', $sectionid)
                ->join('teacher', function ($join) {
                    $join->on('sectiondetail.teacherid', '=', 'teacher.id');
                    $join->where('teacher.deleted', 0);
                })
                ->select(
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix'
                )
                ->get();

            $adviser = '';

            foreach ($sectioninfo as $item) {
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
                $adviser = $item->firstname . ' ' . $temp_middle . ' ' . $item->lastname . ' ' . $item->suffix;

                $item->checked = 0;

            }

            //Attendance
            $attendance_setup = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($syid, $student->levelid);
            $temp_schoolinfo = DB::table('schoolinfo')->first();

            if (
                $syid == 2 && (
                    strtoupper($temp_schoolinfo->abbreviation) == 'ZPS'
                )
            ) {
                $attendance_setup = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($syid);
                foreach ($attendance_setup as $item) {
                    $month_count = \App\Models\Attendance\AttendanceData::monthly_attendance_count($syid, $item->month, $studid, $item->year);
                    $item->present = collect($month_count)->where('present', 1)->count() + collect($month_count)->where('tardy', 1)->count() + collect($month_count)->where('cc', 1)->count();
                    $item->absent = $item->days - $item->present;
                    if ($item->present >= $item->days) {
                        $item->present = $item->days;
                        $item->absent = 0;
                    }
                }
            } else {

                foreach ($attendance_setup as $item) {

                    $sf2_setup = DB::table('sf2_setup')
                        ->where('month', $item->month)
                        ->where('year', $item->year)
                        ->where('sectionid', $sectionid)
                        ->where('sf2_setup.deleted', 0)
                        ->join('sf2_setupdates', function ($join) {
                            $join->on('sf2_setup.id', '=', 'sf2_setupdates.setupid');
                            $join->where('sf2_setupdates.deleted', 0);
                        })
                        ->groupBy('dates')
                        ->select('dates')
                        ->get();

                    $temp_days = array();


                    foreach ($sf2_setup as $sf2_setup_item) {
                        array_push($temp_days, $sf2_setup_item->dates);
                    }



                    $student_attendance = DB::table('studattendance')
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->whereIn('tdate', $temp_days)
                        // ->where('syid',$syid)
                        ->distinct('tdate')
                        ->distinct()
                        ->select([
                            'present',
                            'absent',
                            'tardy',
                            'cc',
                            'tdate'
                        ])
                        ->get();

                    $student_attendance = collect($student_attendance)->unique('tdate')->values();

                    $item->present = collect($student_attendance)->where('present', 1)->count() + collect($student_attendance)->where('tardy', 1)->count() + collect($student_attendance)->where('cc', 1)->count();
                    $item->absent = collect($student_attendance)->where('absent', 1)->count();

                }

            }

            $grading_version = DB::table('zversion_control')->where('module', 1)->where('isactive', 1)->first();

            if ($student->levelid == 14 || $student->levelid == 15) {
                if ($grading_version->version == 'v2' && $syid == 2) {
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2($gradelevel, $studid, $syidd, $strand, null, $sectionid);
                } else {
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $studid, $syid, $strand, null, $sectionid);
                }
                $temp_grades = array();
                $finalgrade = array();
                foreach ($studgrades as $item) {
                    if ($item->id == 'G1') {
                        array_push($finalgrade, $item);
                    } else {
                        if ($item->strandid == $strand) {
                            array_push($temp_grades, $item);
                        }
                        if ($item->strandid == null) {
                            array_push($temp_grades, $item);
                        }
                    }
                }

                $studgrades = $temp_grades;
                $studgrades = collect($studgrades)->where('isVisible', '1')->values();
                $studgrades = collect($studgrades)->sortBy('sortid')->values();
            } else {
                if ($grading_version->version == 'v2' && $syid == 2) {
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2($gradelevel, studid, $syid, null, null, $sectionid);
                } else {
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $studid, $syid, null, null, $sectionid);
                }
                $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel);
                $grades = $studgrades;
                $grades = collect($grades)->sortBy('sortid')->values();
                $finalgrade = collect($grades)->where('id', 'G1')->values();
                unset($grades[count($grades) - 1]);
                $studgrades = collect($grades)->where('isVisible', '1')->values();
            }


            $progname = DB::table('academicprogram')
                ->where('id', $acad)
                ->get();

            return view('teacher.pdf.reportcardpreview')
                ->with('studentid', $studid)
                ->with('syid', $syid)
                ->with('progname', $progname[0]->progname)
                ->with('finalgrade', $finalgrade)
                ->with('student', $student)
                ->with('sectioninfo', $sectionid)
                ->with('grades', $studgrades)
                ->with('attendance_setup', $attendance_setup);

        }
    }
}






// $tempDate = Carbon::createFromDate($year, $month, 1);
// // return $list;
// // $date = Carbon::createFromFormat('Y-m-d',$year.'-'.$month.'-1');

// $html = '<h1 class="w3-text-teal"><center>' . $tempDate->format('F Y') . '</center></h1>';



// // return date("t", mktime (0,0,0,$today->month,1,$today->year));

// $html .= '<table border="1" class = "table table-bordered">
//        <thead><tr class="w3-theme">
//        <th>Sun</th>
//        <th>Mon</th>
//        <th>Tue</th>
//        <th>Wed</th>
//        <th>Thu</th>
//        <th>Fri</th>
//        <th>Sat</th>
//        </tr></thead>';

// $skip = $tempDate->dayOfWeek;


// for($i = 0; $i < $skip; $i++)
// {
//     $tempDate->subDay();
// }


// //loops through month
// do
// {
//     $html .= '<tr>';
//     //loops through each week
//     for($i=0; $i < 7; $i++)
//     {
//         $html .= '<td><span class="date">';

//         $html .= $tempDate->day;

//         $html .= '</span></td>';

//         $tempDate->addDay();
//     }
//     $html .= '</tr>';

// }while($tempDate->month == $month);

// return $html .= '</table>';
// }