<?php

namespace App\Http\Controllers\ScholarshipCoor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\College\Students;
use PDF;
use Crypt;

class ScholarshipCoorController extends Controller
{
    public function index()
    {

        return view('scholarshipcoor.pages.corprinting.corprintingblade');

    }

    public function studentforprintingtable(Request $request)
    {

        $students = Students::enrolled(
            $request->get('take'),
            $request->get('skip'),
            $request->get('search')
        );

        return view('scholarshipcoor.pages.corprinting.studenttable')->with('students', $students);

    }

    public function printcor($id, Request $request)
    {


        if (
            auth()->user()->type == 7 ||
            auth()->user()->type == 9
        ) {
            try {

                $id = Crypt::decrypt($id);

            } catch (\Exception $e) {

                return back();

            }
        }

        $registrar = DB::table('teacher')
            ->where('tid', auth()->user()->email)
            ->first();


        //$title = $registrar->title != null ? $registrar->title.' ' : '';
        //$middlename = strlen($registrar->middlename) > 0 ? $registrar->middlename[0].'. ' : '';

        $registrar_sig = '';

        if (isset($registrar)) {
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            if (isset($registrar->middlename)) {
                $temp_middle = ' ' . $registrar->middlename[0] . '.';
            }
            if (isset($registrar->title)) {
                $temp_title = $registrar->title;
            }
            if (isset($registrar->suffix)) {
                $temp_suffix = ', ' . $registrar->suffix;
            }
            $registrar_sig = $registrar->firstname . $temp_middle . ' ' . $registrar->lastname;
        }

        if ($request->get('syid') != null) {
            $activeSy = DB::table('sy')->where('id', $request->get('syid'))->select('id', 'sydesc')->first();
        } else {
            $activeSy = DB::table('sy')->where('isactive', 1)->select('id', 'sydesc')->first();
        }

        if ($request->get('semid') != null) {
            $activeSem = DB::table('semester')->where('id', $request->get('semid'))->select('id', 'semester')->first();
        } else {
            $activeSem = DB::table('semester')->where('isactive', 1)->select('id', 'semester')->first();
        }

        $current = DB::table('college_enrolledstud')
            ->where('studid', $id)
            ->where('semid', $activeSem->id)
            ->where('syid', $activeSy->id)
            ->where('deleted', 0)
            ->orderBy('id', 'desc')
            ->first();

        // $schedules =  \App\Models\SuperAdmin\SuperAdminData::subject_enrollment_records($activeSy->id, $activeSem->id,  $id);

        $schedules = DB::table('college_loadsubject')
            ->where('college_loadsubject.studid', $id)
            ->where('college_loadsubject.deleted', 0)
            ->join('college_enrolledstud', 'college_loadsubject.studid', 'college_enrolledstud.studid')
            ->where('college_enrolledstud.studstatus', '!=', 'DROPPED')
            ->join('college_classsched', function ($join) use ($activeSy, $activeSem) {
                $join->on('college_loadsubject.schedid', '=', 'college_classsched.id');
                $join->where('college_classsched.syid', $activeSy->id);
                $join->where('college_classsched.deleted', 0);
                $join->where('college_classsched.semesterID', $activeSem->id);
            })
            ->join('college_prospectus', function ($join) {
                $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                $join->where('college_prospectus.deleted', 0);
            })
            ->join('college_sections', function ($join) {
                $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                $join->where('college_sections.deleted', 0);
            })
            // ->join('college_subjects',function($join){
            //     $join->on('college_classsched.subjectID','=','college_subjects.id');
            //     $join->where('college_subjects.deleted',0);
            // })
            ->leftJoin('college_scheddetail', function ($join) {
                $join->on('college_classsched.id', '=', 'college_scheddetail.headerid');
                $join->where('college_scheddetail.deleted', 0);
            })
            ->leftJoin('days', function ($join) {
                $join->on('college_scheddetail.day', '=', 'days.id');
            })
            ->leftJoin('college_instructor', function ($join) {
                $join->where('college_instructor.deleted', 0);
                $join->on('college_classsched.id', '=', 'college_instructor.classschedid');
            })
            ->leftJoin('teacher', function ($join) {
                $join->on('college_instructor.teacherID', '=', 'teacher.id');
            })
            ->leftJoin('rooms', function ($join) {
                $join->on('college_scheddetail.roomid', '=', 'rooms.id');
                $join->where('rooms.deleted', 0);
            })
            ->select(
                'days.id as daysort',
                'days.description',
                'college_classsched.id',
                'college_scheddetail.id as schedid',
                'college_scheddetail.etime',
                'college_scheddetail.stime',
                'college_classsched.subjectUnit',
                'college_prospectus.subjDesc',
                'college_prospectus.subjCode',
                'college_prospectus.lecunits',
                'college_prospectus.labunits',
                'rooms.roomname',
                'college_scheddetail.scheddetialclass',
                'college_scheddetail.schedotherclass',
                'college_prospectus.id as subjID',
                'lastname',
                'firstname',
                'sectionDesc',
                DB::raw('GROUP_CONCAT( DISTINCT days.id ORDER BY days.id SEPARATOR ", ") as day')

            )
            ->groupBy('college_classsched.id')
            ->get();
        $schedules = collect($schedules)->sortBy('subjCode')->values();


        if (count($schedules) > 0) {

            $withSched = true;

            $bySubject = collect($schedules)->groupBy('subjID');

            $data = array();

            foreach ($bySubject as $subjitem) {

                $byClass = collect($subjitem)->groupBy('schedotherclass');

                foreach ($byClass as $item) {
                    $day = '';


                    foreach (collect($item)->groupBy('etime') as $secondItem) {

                        $teacher = '';

                        foreach (collect($secondItem)->sortBy('daysort')->values() as $thirdItem) {

                            $item->put('id', '1');

                            if ($thirdItem->lastname != null) {
                                $teacher = $thirdItem->lastname . ', ' . $thirdItem->firstname;
                            } else {
                                $teacher = '';
                            }


                            $details = $thirdItem;
                            $days = explode(',', $thirdItem->day);
                            $thirdItem->day = $days;

                            // if($thirdItem->description == 'Thursday'){
                            //     $day .= substr($thirdItem->description, 0 , 1).'h';
                            // }
                            // else if($thirdItem->description == 'Saturday'){
                            //     $day .= 'Sat';
                            // }
                            // else{
                            //     $day .= substr($thirdItem->description, -1 , 1);
                            // }
                            foreach ($days as $d) {

                                $day .= $d == 1 ? 'M' : '';
                                $day .= $d == 2 ? 'T' : '';
                                $day .= $d == 3 ? 'W' : '';
                                $day .= $d == 4 ? 'Th' : '';
                                $day .= $d == 5 ? 'F' : '';
                                $day .= $d == 6 ? 'Sat' : '';
                                $day .= $d == 7 ? 'Sun' : '';


                            }
                        }

                        // return $dayl;


                        if (!isset($details->id)) {
                            $details->id = null;
                            $details->scheddetialclass = null;
                            $details->stime = null;
                            $details->etime = null;
                            $details->lastname = $secondItem[0]->lastname;
                            $details->firstname = $secondItem[0]->firstname;
                            $details->lecunits = $secondItem[0]->lecunits;
                            $details->labunits = $secondItem[0]->labunits;
                            $details->roomname = null;
                            $details->sectionDesc = null;
                        }


                        // $day = substr($day, 0, -1);

                        $details->description = $day;
                        $details->teacher = $teacher;

                        array_push($data, $details);

                    }
                    ;


                }

            }

            $schedules = collect($data)->groupBy('subjID');
        }


        $studentInfo = DB::table('studinfo')
            ->where('studinfo.id', $id)
            ->select(
                'firstname',
                'lastname',
                'middlename',
                'sid',
                'studinfo.id',
                'courseabrv',
                'studinfo.courseid',
                'levelname',
                'date_enrolled',
                'sectionname',
                'studinfo.levelid',
                'studtype',
                'dob',
                'semail',
                'street',
                'barangay',
                'city',
                'province',
                'contactno',
                'college_courses.courseDesc',
                'college_sections.sectionDesc'
            )
            ->leftJoin('college_courses', function ($join) {
                $join->on('studinfo.courseid', '=', 'college_courses.id');
                $join->where('college_courses.deleted', 0);
            })
            ->leftJoin('college_sections', function ($join) {
                $join->on('studinfo.sectionid', '=', 'college_sections.id');
                $join->where('college_sections.deleted', 0);
            })
            ->join('gradelevel', function ($join) {
                $join->on('studinfo.levelid', '=', 'gradelevel.id');
                $join->where('gradelevel.deleted', 0);
            })
            ->join('college_enrolledstud', function ($join) use ($activeSem, $activeSy) {
                $join->on('studinfo.id', '=', 'college_enrolledstud.studid');
                $join->where('college_enrolledstud.deleted', 0);
                $join->where('college_enrolledstud.syid', $activeSy->id);
                $join->where('college_enrolledstud.semid', $activeSem->id);
            })
            ->first();


        $studentInfo = DB::table('college_enrolledstud')
            ->where('college_enrolledstud.studid', $id)
            ->select(
                'firstname',
                'lastname',
                'middlename',
                'sid',
                'studinfo.id',
                'courseabrv',
                'studinfo.courseid',
                'levelname',
                'date_enrolled',
                //'college_sections.sectionDesc',
                'college_enrolledstud.sectionID',
                'college_enrolledstud.yearLevel as levelid',
                'studtype',
                'dob',
                'semail',
                'street',
                'barangay',
                'city',
                'province',
                'contactno',
                'college_courses.courseDesc',
                'gender'
            )
            ->join('studinfo', function ($join) {
                $join->on('college_enrolledstud.studid', '=', 'studinfo.id');
                $join->where('studinfo.deleted', 0);
            })
            ->join('college_courses', function ($join) {
                $join->on('college_enrolledstud.courseid', '=', 'college_courses.id');
                $join->where('college_courses.deleted', 0);
            })
            //->join('college_sections',function($join){
            //$join->on('college_enrolledstud.sectionid','=','college_sections.id');
            //$join->where('college_sections.deleted',0);
            //})
            ->join('gradelevel', function ($join) {
                $join->on('college_enrolledstud.yearLevel', '=', 'gradelevel.id');
                $join->where('gradelevel.deleted', 0);
            })

            ->where('college_enrolledstud.syid', $activeSy->id)
            ->where('college_enrolledstud.semid', $activeSem->id)
            ->where('college_enrolledstud.deleted', 0)
            ->first();


        $registrar = DB::table('college_enrolledstud')
            ->where('studid', $studentInfo->id)
            ->join('users', function ($join) {
                $join->on('college_enrolledstud.createdby', '=', 'users.id');
                $join->where('users.deleted', 0);
            })
            ->join('teacher', function ($join) {
                $join->on('users.id', '=', 'teacher.userid');
                $join->where('users.deleted', 0);
            })
            ->select(
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'title',
                'acadtitle'
            )
            ->first();

        $regname = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->get();

        $tempreg = '';

        foreach ($regname as $item) {
            $temp_middle = '';
            $temp_suffix = '';
            $temp_acadtitle = '';
            if (isset($item->middlename)) {
                $temp_middle = $item->middlename[0] . '.';
            }
            if (isset($item->acadtitle)) {
                $temp_acadtitle = ', ' . $item->acadtitle;
            }
            if (isset($item->suffix)) {
                $temp_suffix = ', ' . $item->suffix;
            }
            $tempreg = $item->firstname . ' ' . $temp_middle . ' ' . $item->lastname . $temp_suffix . $temp_acadtitle;
        }

        $regname = $tempreg;

        $schoolInfo = DB::table('schoolinfo')->select('schoolname', 'address', 'picurl', 'abbreviation')->first();

        $dean = DB::table('college_courses')
            ->join('college_colleges', function ($join) {
                $join->on('college_courses.collegeid', '=', 'college_colleges.id');
                $join->where('college_colleges.deleted', 0);
            })
            ->join('teacher', function ($join) {
                $join->on('college_colleges.dean', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->where('college_courses.id', $studentInfo->courseid)
            ->select(
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'title'
            )
            ->get();



        $dean_text = '';

        foreach ($dean as $item) {
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            if (isset($item->middlename)) {
                $temp_middle = $item->middlename[0] . '.';
            }
            if (isset($item->title)) {
                $temp_title = $item->title;
            }
            if (isset($item->suffix)) {
                $temp_suffix = ', ' . $item->suffix;
            }
            $dean_text = $item->firstname . ' ' . $temp_middle . ' ' . $item->lastname . $temp_suffix . ', ' . $temp_title;
        }

        if (isset($studentInfo)) {
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            if (isset($studentInfo->middlename)) {
                $temp_middle = ' ' . $studentInfo->middlename;
            }
            if (isset($studentInfo->title)) {
                $temp_title = $studentInfo->title;
            }
            if (isset($studentInfo->suffix)) {
                $temp_suffix = ' ' . $studentInfo->suffix;
            }
            $studentInfo->student = $studentInfo->lastname . ', ' . $studentInfo->firstname . $temp_suffix . $temp_middle;
        }

        $dean = $dean_text;

        if (
            auth()->user()->type == 7 ||
            auth()->user()->type == 9

        ) {

            $ledger = DB::table('studledger')
                ->where('studid', $id)
                ->where('amount', '>', 0)
                ->where('studledger.deleted', '0')
                ->where('void', '0')
                ->where('studledger.syid', $activeSy->id)
                ->where('studledger.semid', $activeSem->id)
                ->get();


        } else {

            $ledger = DB::table('studledger')
                ->where('studid', $id)
                ->where('amount', '>', 0)
                ->where('studledger.deleted', '0')
                ->where('void', '0')
                //->leftJoin('balforwardsetup',function($join){
                //$join->on('studledger.classid','=','balforwardsetup.classid');
                //})
                ->where('studledger.syid', $activeSy->id)
                ->where('studledger.semid', $activeSem->id)
                //->where('balforwardsetup.id',null)
                ->select(

                    'studledger.*',
                    DB::raw('SUM(amount) as amount')
                )
                ->groupBy('classid')
                ->get();

            foreach ($ledger as $item) {
                if (str_contains($item->particulars, 'LABORATORY')) {
                    $item->particulars = 'LABORATORY FEE';
                } else if (str_contains($item->particulars, 'OLD ACCOUNTS')) {
                    $item->particulars = 'OLD ACCOUNTS';
                } else if (str_contains($item->particulars, 'BOOKS')) {
                    $item->particulars = 'BOOKS';
                }

            }

        }


        //get section
        $schedid = DB::table('college_classsched')
            ->where('sectionID', $studentInfo->sectionID)
            ->where('deleted', 0)
            ->select('id')
            ->first();

        $sectionDesc = '';

        if (isset($schedid->id)) {

            $collegesection = DB::table('college_classsched')
                ->where('college_classsched.deleted', 0)
                ->where('college_classsched.id', $schedid->id)
                ->join('college_sections', function ($join) use ($studentInfo) {
                    $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                    $join->where('college_sections.deleted', 0);
                    $join->where('college_sections.courseID', $studentInfo->courseid);
                })
                ->leftJoin('college_courses', function ($join) {
                    $join->on('college_sections.courseID', '=', 'college_courses.id');
                    $join->where('college_courses.deleted', 0);
                })
                ->leftJoin('gradelevel', function ($join) {
                    $join->on('college_sections.yearID', '=', 'gradelevel.id');
                    $join->where('gradelevel.deleted', 0);
                })
                ->leftJoin('college_colleges', function ($join) {
                    $join->on('college_sections.collegeID', '=', 'college_colleges.id');
                    $join->where('college_colleges.deleted', 0);
                })
                ->select(
                    'college_sections.courseID',
                    'college_sections.yearID',
                    'college_sections.collegeID',
                    'college_sections.sectionDesc',
                    'college_courses.courseDesc',
                    'college_colleges.collegeDesc',
                    'gradelevel.levelname',
                    'college_courses.courseabrv',
                    'college_colleges.collegeabrv',
                    'college_classsched.id',
                    'college_courses.id as courseid',
                    // 'college_classsched.schedgroupdesc',
                    // 'schedgroupdesc as text',
                    // 'schedid'
                )
                ->first();


            if (isset($collegesection->id)) {
                $text = '';
                // if ($collegesection->courseid != null) {
                //     $text = $collegesection->courseabrv;
                // } else {
                //     $text = $collegesection->collegeabrv;
                // }
                // 		$text .= '-'.$collegesection->levelname[0] . ' '.$collegesection->schedgroupdesc;

                $text = $collegesection->sectionDesc;
                $sectionDesc = $text;
            }



        }

        if ($sectionDesc != '') {
            $studentInfo->sectionDesc = $sectionDesc;
        } else {
            $studentInfo->sectionDesc = null;
        }


        //return $sectionDesc;

        //return collect($registrar);


        if ($request->get('format') == 1) {
            $abbrv = DB::table('schoolinfo')->value('abbreviation');

            $tempregistrar = '';
            if ($abbrv && strtolower($abbrv) == 'bcc') {
                if ($registrar) {
                    $temp_middle = '';
                    $temp_suffix = '';
                    $temp_acadtitle = '';
                    if (isset($registrar->middlename)) {
                        $temp_middle = $registrar->middlename[0] . '.';
                    }
                    if (isset($registrar->acadtitle)) {
                        $temp_acadtitle = ', ' . $registrar->acadtitle;
                    }
                    if (isset($registrar->suffix)) {
                        $temp_suffix = ', ' . $registrar->suffix;
                    }
                    $tempregistrar = $registrar->firstname . ' ' . $temp_middle . ' ' . $registrar->lastname . $temp_suffix . $temp_acadtitle;

                    // return $tempregistrar;
                }

                $pdf = PDF::loadView('scholarshipcoor.pages.corprinting.corpdf_bcc', compact('regname', 'registrar_sig', 'schedules', 'schoolInfo', 'studentInfo', 'activeSy', 'activeSem', 'ledger', 'registrar', 'tempregistrar', 'dean'))->setPaper('legal');
            } else {
                $pdf = PDF::loadView('scholarshipcoor.pages.corprinting.corpdf', compact('regname', 'registrar_sig', 'schedules', 'schoolInfo', 'studentInfo', 'activeSy', 'activeSem', 'ledger', 'registrar', 'dean'))->setPaper('legal');
            }

        } else if ($request->get('format') == 2) {
            $pdf = PDF::loadView('scholarshipcoor.pages.corprinting.corpdf_2', compact('regname', 'registrar_sig', 'schedules', 'schoolInfo', 'studentInfo', 'activeSy', 'activeSem', 'ledger', 'registrar', 'dean'))->setPaper('legal');

        } else {
            $pdf = PDF::loadView('scholarshipcoor.pages.corprinting.corhccsi', compact('registrar_sig', 'schedules', 'schoolInfo', 'studentInfo', 'activeSy', 'activeSem', 'ledger', 'registrar', 'dean'))->setPaper('legal');
        }

        return $pdf->stream($studentInfo->lastname . ', ' . $studentInfo->firstname . '.pdf');


    }


    public function college_student_masterlist(Request $request)
    {

        if ($request->has('blade') && $request->get('blade') == 'blade') {

            return view('scholarshipcoor.pages.students.students');

        }
        if ($request->has('info') && $request->get('info') == 'info') {

            $studentInfo = DB::table('studinfo')->where('id', $request->get('studid'))->first();

            return view('scholarshipcoor.pages.students.studentInformation')->with('student', $studentInfo);

        } elseif ($request->has('table') && $request->get('table') == 'table') {

            $students = Students::studentMasterList(
                $request->get('take'),
                $request->get('skip'),
                $request->get('search'),
                [],
                [],
                [
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'gradelevel.levelname',
                    'studinfo.sid',
                    'studinfo.id',
                    'college_courses.courseabrv'
                ]
            );

            return view('scholarshipcoor.pages.students.studenttable')
                ->with('students', $students);

        }

    }

    public function promotional_report()
    {

        return view('scholarshipcoor.pages.promotional_report');

    }

    public function generate_promotional_report(Request $request)
    {

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        return \App\Models\Forms\PromotionalReport::promotional_report($syid, $semid, $courseid, $sectionid);

    }


    public function generate_promotional_excel(Request $request)
    {

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        return \App\Models\Forms\PromotionalReport::promotional_report_excel($syid, $semid, $courseid, $sectionid);

    }

    public function generate_beginning_excel(Request $request)
    {

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        return \App\Models\Forms\BeginningReport::beginning_report_excel($syid, $semid, $courseid, $sectionid);

    }






}
