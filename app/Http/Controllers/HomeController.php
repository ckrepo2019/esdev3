<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DB;
use \Carbon\Carbon;
use \Carbon\CarbonTimeZone;
use Auth;
use DateTime;
use Session;
use \Carbon\CarbonPeriod;
use Hash;

use App\Models\Teacher\VirtualClassroomCodeGenerator;
use App\Models\Teacher\VirtualClassroomGetSections;
use App\Models\Principal\AttendanceReport;
use App\Models\Principal\Billing;
use App\Models\Principal\LoadData;
use App\Models\Principal\GenerateGrade;
use App\Models\Principal\SPP_Student;
use App\Models\Principal\SPP_Teacher;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_Subject;
use App\Models\Principal\SPP_Gradelevel;
use App\Models\College\Schedule;
use App\Models\College\VCSetup;
use App\Models\ExamPermit\StudentExamPermit;
use App\Models\Attendance\AttendanceInfo;
use App\Models\HR\HREmployeeAttendance;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

        if (!Auth::check()) {
            Auth::logout();
            Session::flush();
            return redirect('/login');
        }

        if (Session::get('currentPortal') == 7) {
            return view('studentPortal.pages.home');
        } else if (Session::get('currentPortal') == 9) {
            return view('studentPortal.pages.home');
        }
        //Principal
        else if (Session::get('currentPortal') == 2) {



            $teachers = SPP_Teacher::principalGetTeachers();

            $now = Carbon::now();
            $comparedDate = $now->toDateString();

            $todate = Carbon::now();
            $countPresentTeachers = 0;
            $countAbsentTeachers = 0;
            $countLateTeacher = 0;
            $countOnTimeTeachers = 0;
            $data = array();

            $activesy = DB::table('sy')
                ->where('isactive', 1)
                ->first();

            $teacherid = DB::table('teacher')
                ->where('tid', auth()->user()->email)
                ->first()
                ->id;


            $teachersAttendances = AttendanceInfo::today(null);

            $prinacadprog = DB::table('teacheracadprog')
                ->where('teacherid', $teacherid)
                ->where('deleted', 0)
                ->where('syid', $activesy->id)
                ->where('acadprogutype', 2)
                ->select(
                    'acadprogid'
                )
                ->get();

            $all_teachersid = DB::table('teacheracadprog')
                ->where('teacheracadprog.deleted', 0)
                ->where('teacheracadprog.syid', $activesy->id)
                ->where('teacheracadprog.acadprogutype', 1)
                ->whereIn('acadprogid', collect($prinacadprog)->pluck('acadprogid'))
                ->join('teacher', function ($join) {
                    $join->on('teacheracadprog.teacherid', 'teacher.id');
                    $join->where('teacher.deleted', 0);
                    $join->where('teacher.isactive', 1);
                })
                ->select(
                    'teacherid'
                )
                ->get();

            $teachersAttendances = collect($teachersAttendances)
                ->whereIn('id', collect($all_teachersid)->pluck('teacherid'))
                ->values();

            if (count($teachersAttendances) > 0) {
                foreach ($teachersAttendances as $attteacher) {
                    $attteacher->lastactivity = HREmployeeAttendance::getattendance(date('Y-m-d'), $attteacher)->lastactivity;
                    $attteacher->customamin = HREmployeeAttendance::getattendance(date('Y-m-d'), $attteacher)->customamin;
                    $attteacher->time = $attteacher->timein;
                    if (date('h:i A', strtotime($attteacher->timein)) != '12:00 AM' && $attteacher->timein != null) {
                        if (Carbon::createFromTimeString($attteacher->time, 'Asia/Manila')->isoFormat('HH:mm') <= Carbon::createFromTimeString('7:30:00', 'Asia/Manila')->isoFormat('HH:mm')) {
                            $countOnTimeTeachers += 1;
                            array_push(
                                $data,
                                (object) array(
                                    "teacher" => strtoupper($attteacher->name),
                                    "attendance" => null,
                                    "time" => $attteacher->time,
                                    "customamin" => $attteacher->customamin
                                )
                            );
                        } else {
                            array_push(
                                $data,
                                (object) array(
                                    "teacher" => strtoupper($attteacher->name),
                                    "attendance" => null,
                                    "time" => $attteacher->time,
                                    "customamin" => $attteacher->customamin
                                )
                            );
                            $countLateTeacher += 1;
                        }
                        $countPresentTeachers += 1;
                    } else {
                        array_push(
                            $data,
                            (object) array(
                                "teacher" => strtoupper($attteacher->name),
                                "attendance" => null,
                                "time" => '00:00',
                                "customamin" => $attteacher->customamin
                            )
                        );
                        $countAbsentTeachers += 1;
                    }

                }
            }

            if ($countPresentTeachers == 0) {
                $countPresentTeachers = 0;
            } else {
                $presentPercentage = round(($countPresentTeachers / count($teachers)) * 100);
            }

            if ($countAbsentTeachers == 0) {
                $absentPercentage = 0;
            } else {
                $absentPercentage = round(($countAbsentTeachers / count($teachers)) * 100);
            }


            $schoolcalendar = DB::table('schoolcal')
                ->where('deleted', '0')
                ->get();



            return view('principalsportal.pages.home')
                ->with('teacheratt', $data)
                ->with('present', $countPresentTeachers)
                ->with('absent', $countAbsentTeachers)
                ->with('schoolcalendar', $schoolcalendar)
                ->with('late', $countLateTeacher)
                ->with('ontime', $countOnTimeTeachers);
        } else if (Session::get('currentPortal') == 6) {

            $infoCount = DB::table('schoolinfo')->first();

            $teachersinfo = SPP_Teacher::filterTeacherFaculty(null, null, null, null, null);
            $glevel = SPP_Gradelevel::getGradeLevel(null, null, null, null, null)[0]->data;

            $sydetails = DB::table('sy')
                ->where('isactive', 1)
                ->get();

            $roomsinfodetails = DB::table('rooms')
                ->get();

            $scaldetails = DB::table('schoolcal')
                ->join('schoolcaltype', 'schoolcal.type', '=', 'schoolcaltype.id')
                ->get();

            $adsdetails = DB::table('adimages')
                ->get();

            $roomscount = count($roomsinfodetails);
            $scaldetailscount = count($scaldetails);
            $adsdetailscount = count($adsdetails);
            $fstafcount = count($teachersinfo[0]->data);

            foreach ($glevel as $item) {

                $gsStudents = SPP_EnrolledStudent::getStudent(null, null, null, null, null, null, null, $item->id)[0]->count;

                $item->studCount = $gsStudents;
            }

            if ($infoCount->schoolname == null) {

                return view('adminPortal.pages.schoolinfo');

            } else {

                return view('adminPortal.pages.home')
                    ->with('fsinfo', $teachersinfo)
                    ->with('roomscount', $roomscount)
                    ->with('scaldetailscount', $scaldetailscount)
                    ->with('adsdetailscount', $adsdetailscount)
                    ->with('fstafcount', $fstafcount)
                    ->with('sydetails', $sydetails)
                    ->with('schoolcalendardetails', $scaldetails)
                    ->with('glevel', $glevel);

            }

        } else if (Session::get('currentPortal') == 12) {

            return view('adminITPortal.pages.main');

        }

        //Teacher
        else if (Session::get('currentPortal') == 1) {

            $schoolcalendar = DB::table('schoolcal')
                ->select('schoolcal.id', 'schoolcal.description', 'schoolcal.datefrom', 'schoolcal.dateto', 'schoolcaltype.typename', 'schoolcal.noclass', 'schoolcal.annual')
                ->join('schoolcaltype', 'schoolcal.type', '=', 'schoolcaltype.id')
                ->where('schoolcal.deleted', '0')
                ->get();

            return view('teacher.home')
                ->with('schoolcalendar', $schoolcalendar);

        } else if (Session::get('currentPortal') == 8) {
            return view('admission.pages.home');

        }

        //Registrar
        else if (Session::get('currentPortal') == 3) {

            $sy = DB::table('sy')
                ->where('isactive', '1')
                ->first();
            // ----------------------------------------------------------------------------------------
            $current_schoolyear = Db::table('sy')
                ->where('sy.isactive', '1')
                ->get();
            $newElem = 0;
            $oldElem = 0;
            $newJ = 0;
            $oldJ = 0;
            $newS = 0;
            $oldS = 0;
            $studentsLowerQuery = Db::table('enrolledstud')
                ->select('enrolledstud.studid', 'enrolledstud.levelid', 'enrolledstud.syid')
                ->whereIn('enrolledstud.studstatus', ['1', '4', '2'])
                ->where('enrolledstud.syid', $sy->id)
                ->where('deleted', 0)
                ->get();
            $filter = DB::table('gradelevel')
                ->get();
            foreach ($studentsLowerQuery as $studentFilter) {
                $filterQuery = DB::table('enrolledstud')
                    ->select('enrolledstud.studid', 'enrolledstud.levelid', 'enrolledstud.syid', 'gradelevel.levelname', 'gradelevel.sortid', 'studinfo.studtype', 'studinfo.createddatetime')
                    ->join('studinfo', 'enrolledstud.studid', 'studinfo.id')
                    ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                    ->whereIn('enrolledstud.studstatus', ['1', '4', '2'])
                    ->where('enrolledstud.studid', $studentFilter->studid)
                    ->where('enrolledstud.syid', $sy->id)
                    ->where('gradelevel.deleted', '0')
                    ->where('enrolledstud.deleted', 0)
                    ->where('studinfo.deleted', 0)
                    ->get();

                if (count($filterQuery) > 0) {
                    $elem = collect($filterQuery->where('sortid', '<=', '9'))->count();
                    // return $elem;
                    $junior = collect($filterQuery->whereBetween('sortid', ['10', '13']))->count();

                    if ($elem == 1 && $filterQuery[0]->syid == $sy->id || $filterQuery[0]->studtype == 'new' || $filterQuery[0]->studtype == 'transferee') {
                        $newElem += 1;
                    } elseif ($elem == 1 && $filterQuery[0]->syid != $sy->id || $filterQuery[0]->createddatetime == null && $filterQuery[0]->studtype == null || $filterQuery[0]->studtype == 'old') {
                        $oldElem += 1;
                    }
                    if ($elem > 1) {
                        $oldElem += 1;
                    }
                    if ($junior == 1 && $filterQuery[0]->syid == $sy->id || $filterQuery[0]->studtype == 'new') {
                        $newJ += 1;
                    } elseif ($junior == 1 && $filterQuery[0]->syid != $sy->id || $filterQuery[0]->createddatetime == null && $filterQuery[0]->studtype == null || $filterQuery[0]->studtype == 'old') {
                        $oldJ += 1;
                    }
                    if ($junior > 1) {
                        $oldJ += 1;
                    }
                }
            }
            // return $oldJ;
            $studentsHigherQuery = Db::table('sh_enrolledstud')
                ->select('sh_enrolledstud.studid', 'sh_enrolledstud.levelid')
                ->whereIn('sh_enrolledstud.studstatus', ['1', '4', '2'])
                ->where('sh_enrolledstud.syid', $sy->id)
                ->get();
            // return $studentsHigherQuery;
            foreach ($studentsHigherQuery as $studentSeniorFilter) {
                $filterSeniorQuery = DB::table('sh_enrolledstud')
                    ->select('sh_enrolledstud.studid', 'sh_enrolledstud.levelid', 'sh_enrolledstud.syid', 'gradelevel.levelname', 'gradelevel.sortid', 'studinfo.studtype', 'studinfo.createddatetime')
                    ->join('studinfo', 'sh_enrolledstud.studid', 'studinfo.id')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->whereIn('sh_enrolledstud.studstatus', ['1', '4', '2'])
                    ->where('sh_enrolledstud.studid', $studentSeniorFilter->studid)
                    ->where('sh_enrolledstud.syid', $sy->id)
                    ->where('gradelevel.deleted', '0')
                    ->where('sh_enrolledstud.deleted', 0)
                    ->where('studinfo.deleted', 0)
                    ->get();
                if (count($filterSeniorQuery) > 0) {
                    $senior = collect($filterSeniorQuery->whereBetween('sortid', ['14', '15']))->count();
                    if ($senior == 1 && $filterSeniorQuery[0]->syid == $sy->id || $filterSeniorQuery[0]->studtype == 'new' || $filterSeniorQuery[0]->studtype == 'transferee') {
                        $newS += 1;
                    } elseif ($senior == 1 && $filterSeniorQuery[0]->syid != $sy->id || $filterSeniorQuery[0]->studtype == 'old' || $filterSeniorQuery[0]->studtype == null && $filterSeniorQuery[0]->createddatetime == null) {
                        $oldS += 1;
                    }
                    if ($senior > 1) {
                        $oldS += 1;
                    }
                }
            }
            // return $oldS;
            $teachersQuery = Db::table('usertype')
                ->select('teacher.id', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix')
                ->join('teacher', 'usertype.id', '=', 'teacher.usertypeid')
                ->where('usertype.utype', 'TEACHER')
                ->where('teacher.isactive', '1')
                ->where('teacher.deleted', '0')
                // ->where('teacher.syid',$current_schoolyear[0]->id)
                ->get();


            $activeTeachers = count($teachersQuery);

            $notificationsRegistrar = DB::table('teacher')
                ->join('notifications', 'teacher.userid', '=', 'notifications.recieverid')
                ->join('announcements', 'notifications.headerid', '=', 'announcements.id')
                ->where('teacher.userid', auth()->user()->id)
                ->where('announcements.recievertype', '5')
                ->get();
            // ----------------------------------------------------------------------------------------
            $getTeachersLower = DB::table('enrolledstud')
                ->select('teacher.id', 'academicprogram.acadprogcode')
                ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                ->join('gradelevel', 'sections.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->join('teacher', 'sections.teacherid', '=', 'teacher.id')
                ->where('enrolledstud.syid', $current_schoolyear[0]->id)
                ->where('enrolledstud.studstatus', '1')
                ->where('teacher.deleted', '0')
                ->where('teacher.isactive', '1')
                ->distinct()
                ->get();
            $preschoolTeachers = count($getTeachersLower->where('acadprogcode', 'PRE-SCHOOL'));
            $elemTeachers = count($getTeachersLower->where('acadprogcode', 'ELEM'));
            $juniorHighTeachers = count($getTeachersLower->where('acadprogcode', 'HS'));

            $getTeachersHigher = DB::table('sh_enrolledstud')
                ->select('teacher.id', 'academicprogram.acadprogcode')
                ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                ->join('gradelevel', 'sections.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->join('teacher', 'sections.teacherid', '=', 'teacher.id')
                ->where('sh_enrolledstud.syid', $current_schoolyear[0]->id)
                ->where('sh_enrolledstud.studstatus', '!=', '')
                ->distinct()
                ->get();
            $seniorHighTeachers = count($getTeachersHigher->where('acadprogcode', 'SHS'));
            // ----------------------------------------------------------------------------------------
            $getStudentsLower = DB::table('enrolledstud')
                ->select('studinfo.id', 'academicprogram.acadprogcode')
                ->join('studinfo', 'enrolledstud.id', '=', 'studinfo.id')
                // ->join('sections','enrolledstud.sectionid','=','sections.id')
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->where('enrolledstud.syid', $current_schoolyear[0]->id)
                ->whereIn('enrolledstud.studstatus', ['1', '4', '2'])
                ->distinct()
                ->get();
            $preschoolStudents = count($getStudentsLower->where('acadprogcode', 'PRE-SCHOOL'));
            $elemStudents = count($getStudentsLower->where('acadprogcode', 'ELEM'));
            $juniorHighStudents = count($getStudentsLower->where('acadprogcode', 'HS'));
            // return $elemStudents;
            $getStudentsHigher = DB::table('sh_enrolledstud')
                ->select('studinfo.id', 'academicprogram.acadprogcode')
                ->join('studinfo', 'sh_enrolledstud.id', '=', 'studinfo.id')
                // ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->where('sh_enrolledstud.syid', $current_schoolyear[0]->id)
                ->whereIn('sh_enrolledstud.studstatus', ['1', '4', '2'])
                ->distinct()
                ->get();
            $seniorHighStudents = count($getStudentsHigher->where('acadprogcode', 'SHS'));
            // return 'asd';
            // return $seniorHighStudents;
            $schoolcalendar = DB::table('schoolcal')
                ->select('schoolcal.id', 'schoolcal.description', 'schoolcal.datefrom', 'schoolcal.dateto', 'schoolcaltype.typename', 'schoolcal.noclass', 'schoolcal.annual')
                ->join('schoolcaltype', 'schoolcal.type', '=', 'schoolcaltype.id')
                ->where('schoolcal.deleted', '0')
                ->get();

            if (count($notificationsRegistrar) == 0) {
                return view('registrar.home')
                    ->with('newElemStudents', $newElem)
                    ->with('oldElemStudents', $oldElem)
                    ->with('newJuniorStudents', $newJ)
                    ->with('oldJuniorStudents', $oldJ)
                    ->with('newSeniorStudents', $newS)
                    ->with('oldSeniorStudents', $oldS)
                    ->with('messageWarning', 'No notifications available!')
                    ->with('activeTeachers', $activeTeachers)
                    ->with('preschoolTeachers', $preschoolTeachers)
                    ->with('elemTeachers', $elemTeachers)
                    ->with('juniorHighTeachers', $juniorHighTeachers)
                    ->with('seniorHighTeachers', $seniorHighTeachers)
                    ->with('preschoolStudents', $preschoolStudents)
                    ->with('elemStudents', $elemStudents)
                    ->with('juniorHighStudents', $juniorHighStudents)
                    ->with('seniorHighStudents', $seniorHighStudents)
                    ->with('schoolcalendar', $schoolcalendar);
            } else {
                return view('registrar.home')
                    ->with('newElemStudents', $newElem)
                    ->with('oldElemStudents', $oldElem)
                    ->with('newJuniorStudents', $newJ)
                    ->with('oldJuniorStudents', $oldJ)
                    ->with('newSeniorStudents', $newS)
                    ->with('oldSeniorStudents', $oldS)
                    ->with('notifications', $notificationsRegistrar)
                    ->with('activeTeachers', $activeTeachers)
                    ->with('preschoolTeachers', $preschoolTeachers)
                    ->with('elemTeachers', $elemTeachers)
                    ->with('juniorHighTeachers', $juniorHighTeachers)
                    ->with('seniorHighTeachers', $seniorHighTeachers)
                    ->with('preschoolStudents', $preschoolStudents)
                    ->with('elemStudents', $elemStudents)
                    ->with('juniorHighStudents', $juniorHighStudents)
                    ->with('seniorHighStudents', $seniorHighStudents)
                    ->with('schoolcalendar', $schoolcalendar);
            }
        }

        //Finance
        else if (Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15) {

            $schoolinfo = db::table('schoolinfo')
                ->first();


            // return 'kjpoijpju';
            $glevelindex = DB::table('gradelevel')
                ->orderBy('sortid', 'asc')
                ->get();

            // return $glevelindex ;

            if (count($glevelindex) > 0) {
                foreach ($glevelindex as $glevel) {
                    $tuitionheader = DB::table('tuitionheader')
                        ->where('levelid', $glevel->id)
                        ->where('deleted', 0)
                        ->get();
                    if (count($tuitionheader) == 1) {
                        $glevel->status = true;
                    } else {
                        $glevel->status = false;
                    }

                }
            }

            $usertype = Session::get('currentPortal');


            // return $rateelevation;

            return view('finance.index')
                ->with('glevelindex', $glevelindex);
        } else if (Session::get('currentPortal') == 10 || $refid == 26) {
            // if($refid == 26)
            // {
            //     Session::put('currentPortal','10', )
            // }
            date_default_timezone_set('Asia/Manila');

            // $numberofemployees = DB::table('teacher')
            //     ->select('id')
            //     ->where('isactive','1')
            //     ->count();
            $departments = Db::table('hr_school_department')
                ->where('deleted', 0)
                ->get();
            // $designations = Db::table('hr_designation')
            //     ->where('deleted',0)
            //     ->get();
            $designations = Db::table('usertype')
                ->select(
                    'id',
                    'utype as designation',
                    'departmentid'
                )
                ->where('deleted', '0')
                ->get();

            $holidays = Db::table('schoolcal')
                ->select(
                    'schoolcal.description',
                    'schoolcal.datefrom',
                    'schoolcal.dateto',
                    'schoolcaltype.typename',
                    'schoolcal.noclass'
                )
                ->join('schoolcaltype', 'schoolcal.type', '=', 'schoolcaltype.id')
                ->where('schoolcal.deleted', '0')
                ->get();
            foreach ($holidays as $holiday) {
                foreach ($holiday as $key => $value) {
                    if ($key == 'datefrom') {
                        $holiday->datefrom = date('M d, Y', strtotime($value));
                    }
                    if ($key == 'dateto') {
                        $holiday->dateto = date('M d, Y', strtotime($value));
                    }
                }
            }
            // $employees = Db::table('teacher')
            //     ->select(
            //         'teacher.id',
            //         'teacher.firstname',
            //         'teacher.middlename',
            //         'teacher.lastname',
            //         'teacher.suffix',
            //         'employee_personalinfo.contactnum',
            //         'hr_school_department.department',
            //         'usertype.utype as designation'
            //         )
            //     ->join('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            //     ->join('hr_school_department','employee_personalinfo.departmentid','=','hr_school_department.id')
            //     ->join('usertype','employee_personalinfo.designationid','=','usertype.id')
            //     ->where('teacher.isactive','1')
            //     ->get();
            $employees = Db::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.userid',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix',
                    'teacher.phonenumber'
                )
                ->where('teacher.isactive', '1')
                ->get();
            foreach ($employees as $employeegetinfo) {
                foreach ($employeegetinfo as $key => $value) {
                    if ($key == 'id') {
                        $getinfo = DB::table('employee_personalinfo')
                            ->join('hr_school_department', 'employee_personalinfo.departmentid', '=', 'hr_school_department.id')
                            ->join('usertype', 'employee_personalinfo.designationid', '=', 'usertype.id')
                            ->where('employee_personalinfo.employeeid', $value)
                            ->where('usertype.utype', '!=', 'PARENT')
                            ->where('usertype.utype', '!=', 'STUDENT')
                            ->first();
                        try {
                            $employeegetinfo->department = $getinfo->department;
                            $employeegetinfo->designation = $getinfo->utype;
                        } catch (\Exception $e) {
                            $employeegetinfo->department = "";
                            $employeegetinfo->designation = "";
                        }
                    }
                    if ($key == 'phonenumber') {
                        $employeegetinfo->contactnum = $value;
                    }
                }
            }
            // return $employees;
            $schoolcalendar = DB::table('schoolcal')
                ->select('schoolcal.id', 'schoolcal.description', 'schoolcal.datefrom', 'schoolcal.dateto', 'schoolcaltype.typename', 'schoolcal.noclass', 'schoolcal.annual')
                ->join('schoolcaltype', 'schoolcal.type', '=', 'schoolcaltype.id')
                ->where('schoolcal.deleted', '0')
                ->get();
            // return $holidays;
            // $todaysleave = Db::table('employee_leaves')

            // -----------------------------------------------------------------------------------
            $schoolyears = DB::table('sy')
                ->get();
            // ---------------------------- TODAY'S ATTENDANCE
            $todaysattendance = AttendanceInfo::today(null);

            // ---------------------------- Current payroll leave applications
            $leaveapplications = array();
            $activepayrolldate = Db::table('payroll')
                ->where('status', '1')
                ->get();

            if (count($activepayrolldate) > 0) {
                $employeesleaveapplication = DB::table('employee_leaves')
                    ->select(
                        'teacher.lastname',
                        'teacher.middlename',
                        'teacher.firstname',
                        'teacher.suffix',
                        'hr_leaves.leave_type',
                        'employee_leaves.datefrom',
                        'employee_leaves.dateto',
                        'employee_leaves.status'
                    )
                    ->join('teacher', 'employee_leaves.employeeid', '=', 'teacher.id')
                    ->join('hr_leaves', 'employee_leaves.leaveid', '=', 'hr_leaves.id')
                    ->where('employee_leaves.payrollid', $activepayrolldate[0]->id)
                    ->get();
                if (count($employeesleaveapplication) > 0) {
                    foreach ($employeesleaveapplication as $employeeleaveapplication) {
                        array_push($leaveapplications, $employeeleaveapplication);
                    }
                }
            }
            // return $leaveapplications;
            return view('hr.home')
                // ->with('numberofemployees',$numberofemployees)
                ->with('departments', $departments)
                ->with('designations', $designations)
                ->with('holidays', $holidays)
                ->with('employees', $employees)
                ->with('todaysattendance', $todaysattendance)
                ->with('leaveapplications', $leaveapplications)
                ->with('schoolcalendar', $schoolcalendar);
        } else if (auth()->user()->type == 13) {

            return view('collegeportal.pages.home');

        } else if (Session::get('currentPortal') == 14) {

            return view('deanportal.pages.home');

        } else if (Session::get('currentPortal') == 16) {

            $courseDesc = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->join('college_courses', function ($join) {
                    $join->on('teacher.id', '=', 'college_courses.courseChairman');
                    $join->where('college_courses.deleted', '0');
                })
                ->select('college_courses.courseDesc')
                ->first();



            return view('chairpersonportal.pages.home')->with('courseDesc', $courseDesc);

        } else if (auth()->user()->type == 17) {

            return view('superadmin.pages.home');

        } else if (auth()->user()->type == 18 || Session::get('currentPortal') == 18) {

            return view('ctportal.pages.dashboard');

        } else {

            $check_refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->select('refid', 'resourcepath')->first();

            if (isset($check_refid->refid) && $check_refid->refid != 34) {
                try {
                    return view($check_refid->resourcepath);
                } catch (\Exception $e) {

                }
            }

            if (isset($check_refid->refid)) {

                if ($refid == 19) {
                    $schoolinfo = db::table('schoolinfo')
                        ->first();

                    $glevelindex = DB::table('gradelevel')
                        ->orderBy('sortid', 'asc')
                        ->get();

                    if (count($glevelindex) > 0) {
                        foreach ($glevelindex as $glevel) {
                            $tuitionheader = DB::table('tuitionheader')
                                ->where('levelid', $glevel->id)
                                ->where('deleted', 0)
                                ->get();
                            if (count($tuitionheader) == 1) {
                                $glevel->status = true;
                            } else {
                                $glevel->status = false;
                            }

                        }
                    }

                    $usertype = Session::get('currentPortal');

                    return view('finance.index')
                        ->with('glevelindex', $glevelindex);
                } elseif ($refid == 31) {
                    return view('guidanceV2.pages.home');
                } elseif ($refid == 32) {
                    $schoolinfo = db::table('schoolinfo')
                        ->first();

                    $glevelindex = DB::table('gradelevel')
                        ->orderBy('sortid', 'asc')
                        ->get();

                    if (count($glevelindex) > 0) {
                        foreach ($glevelindex as $glevel) {
                            $tuitionheader = DB::table('tuitionheader')
                                ->where('levelid', $glevel->id)
                                ->where('deleted', 0)
                                ->get();
                            if (count($tuitionheader) == 1) {
                                $glevel->status = true;
                            } else {
                                $glevel->status = false;
                            }

                        }
                    }

                    $usertype = Session::get('currentPortal');

                    return view('finance.sa.index')
                        ->with('glevelindex', $glevelindex);
                } else if ($refid == 34) {

                    $page = $check_refid->resourcepath ?? 'library.pages.home';

                    $labels = [];
                    $borrowed = [];
                    $returned = [];

                    $genres = DB::table('library_genres')->get();
                    foreach ($genres as $genre) {
                        $labels[] = $genre->genre_name;
                        $borrowed[] = DB::table('library_circulation')
                            ->where('circulation_status', 2)
                            ->where('library_books.book_genre', $genre->id)
                            ->join('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                            ->count();
                        $returned[] = DB::table('library_circulation')
                            ->where('circulation_status', 3)
                            ->where('library_books.book_genre', $genre->id)
                            ->join('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                            ->count();
                    }

                    $datasets = [
                        'labels' => $labels,
                        'datasets' => [
                            [
                                'label' => 'Borrowed',
                                'data' => $borrowed
                            ],
                            [
                                'label' => 'Returned',
                                'data' => $returned
                            ],
                        ]
                    ];

                    $jsonData = DB::table('library_circulation')
                        ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                        ->leftJoin('employee_personalinfo', 'library_circulation.circulation_members_id', '=', 'employee_personalinfo.employeeid')
                        ->join('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
                        ->join('libraries', 'library_books.library_branch', '=', 'libraries.id')
                        ->where('library_circulation.circulation_deleted', 0)
                        ->where('library_circulation.circulation_status', '!=', 3)
                        ->whereNotNull('library_circulation.circulation_due_date') // Ensure there is a due date
                        ->whereDate('library_circulation.circulation_due_date', '<', now()) // Filter overdue items
                        ->select(
                            'employee_personalinfo.contactnum',
                            'library_circulation.*',
                            'library_books.book_title',
                            'library_books.book_author',
                            'libraries.library_name',
                            'library_status.status_name'
                        )
                        ->get();

                    foreach ($jsonData as $item) {
                        $item->circulation_due_date = new \DateTime($item->circulation_due_date);
                        $item->circulation_due_date = $item->circulation_due_date->format('F d, Y');
                    }

                    $totalBorrowed = DB::table('library_circulation')->where('circulation_deleted', 0)->where('circulation_status', 2)->count();
                    $totalReturned = DB::table('library_circulation')->where('circulation_deleted', 0)->where('circulation_status', 3)->count();
                    $totalIssued = DB::table('library_circulation')->where('circulation_deleted', 0)->where('circulation_status', 1)->count();
                    $totalLost = DB::table('library_circulation')->where('circulation_deleted', 0)->where('circulation_status', 4)->count();

                    return view($page, [
                        'jsonData' => $jsonData,
                        'totalBorrowed' => $totalBorrowed,
                        'totalReturned' => $totalReturned,
                        'totalIssued' => $totalIssued,
                        'totalLost' => $totalLost,
                        'transactions' => $datasets,
                    ]);

                } else if($refid == 35) {
                    return view('tesda.pages.home');
                }
                else {

                    $schoolcalendar = DB::table('schoolcal')
                        ->select('schoolcal.id', 'schoolcal.description', 'schoolcal.datefrom', 'schoolcal.dateto', 'schoolcaltype.typename', 'schoolcal.noclass', 'schoolcal.annual')
                        ->join('schoolcaltype', 'schoolcal.type', '=', 'schoolcaltype.id')
                        ->where('schoolcal.deleted', '0')
                        ->get();

                    return view('general.defaultportal.pages.index')->with('schoolcalendar', $schoolcalendar);
                }

            }

            $schoolcalendar = DB::table('schoolcal')
                ->select('schoolcal.id', 'schoolcal.description', 'schoolcal.datefrom', 'schoolcal.dateto', 'schoolcaltype.typename', 'schoolcal.noclass', 'schoolcal.annual')
                ->join('schoolcaltype', 'schoolcal.type', '=', 'schoolcaltype.id')
                ->where('schoolcal.deleted', '0')
                ->get();

            return view('general.defaultportal.pages.index')->with('schoolcalendar', $schoolcalendar);



        }

    }
}
