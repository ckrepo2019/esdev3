<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use Session;
class Form10Controller extends Controller
{
    public function index(Request $request)
    {


        $extends = 'registrar';
        if(Session::get('currentPortal') == 1)
        {
            $syid = DB::table('sy')->where('isactive','1')->first()->id;
            $collectstudents = collect();
            $extends = 'teacher';
                        
            $getProgname = DB::table('teacher')
            ->select(
                // 'teacher.id',
                'sections.levelid',
                // 'gradelevel.levelname',
                'sections.id as sectionid',
                'sectiondetail.syid',
                'academicprogram.id as acadprogid',
                'academicprogram.progname'
                )
            ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('teacher.userid',auth()->user()->id)
            ->where('sectiondetail.syid',$syid)
            ->where('sections.deleted','0')
            ->where('sectiondetail.deleted','0')
            ->where('gradelevel.deleted','0')
            ->distinct('progname')
            ->get();

            if(count($getProgname)>0)
            {
                foreach($getProgname as $each)
                {
                    if($each->acadprogid == 5)
                    {
                        $students = DB::table('sh_enrolledstud')
                            ->select('studinfo.id','sid','lastname','firstname','middlename','suffix','gradelevel.id as levelid','levelname','gradelevel.acadprogid','studentstatus.description as studentstatus')
                            ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                            ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                            ->leftJoin('studentstatus','studinfo.studstatus','=','studentstatus.id')
                            ->where('sh_enrolledstud.syid', $each->syid)
                            ->where('sh_enrolledstud.levelid', $each->levelid)
                            ->where('sh_enrolledstud.sectionid', $each->sectionid)
                            ->where('sh_enrolledstud.deleted','0')
                            ->where('studinfo.deleted','0')
                            ->where('gradelevel.deleted','0')
                            ->orderBy('lastname','asc')
                            ->get();

                    }else{
                        $students = DB::table('enrolledstud')
                            ->select('studinfo.id','sid','lastname','firstname','middlename','suffix','gradelevel.id as levelid','levelname','gradelevel.acadprogid','studentstatus.description as studentstatus')
                            ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                            ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                            ->leftJoin('studentstatus','studinfo.studstatus','=','studentstatus.id')
                            ->where('enrolledstud.syid', $each->syid)
                            ->where('enrolledstud.levelid', $each->levelid)
                            ->where('enrolledstud.sectionid', $each->sectionid)
                            ->where('enrolledstud.deleted','0')
                            ->where('studinfo.deleted','0')
                            ->where('gradelevel.deleted','0')
                            ->orderBy('lastname','asc')
                            ->get();
                    }
                    $collectstudents = $collectstudents->merge($students);
                }
            }
            $students = $collectstudents;
            
        }else{

            $students = collect();
            $students_1 = DB::table('enrolledstud')
            ->select('studinfo.id','sid','lastname','firstname','middlename','suffix','gradelevel.id as levelid','levelname','gradelevel.acadprogid','studentstatus.description as studentstatus')
                ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                ->join('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                ->where('studinfo.deleted','0')
                ->where('gradelevel.deleted','0')
                ->where('enrolledstud.deleted','0')
                // ->whereIn('studstatus',[1,2,4])
                ->orderBy('lastname','asc')
                ->get();
    
            $students_2 = DB::table('sh_enrolledstud')
                ->select('studinfo.id','sid','lastname','firstname','middlename','suffix','gradelevel.id as levelid','levelname','gradelevel.acadprogid','studentstatus.description as studentstatus')
                ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                ->join('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                ->where('studinfo.deleted','0')
                ->where('gradelevel.deleted','0')
                ->where('sh_enrolledstud.deleted','0')
                // ->whereIn('studstatus',[1,2,4])
                ->orderBy('lastname','asc')
                ->get();
    
            $students_3 = DB::table('college_enrolledstud')
            ->select('studinfo.id','sid','lastname','firstname','middlename','suffix','gradelevel.id as levelid','levelname','gradelevel.acadprogid','studentstatus.description as studentstatus')
                ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                ->join('studentstatus','college_enrolledstud.studstatus','=','studentstatus.id')
                ->where('studinfo.deleted','0')
                ->where('gradelevel.deleted','0')
                ->where('college_enrolledstud.deleted','0')
                // ->whereIn('studstatus',[1,2,4])
                ->orderBy('lastname','asc')
                ->get();
                
            $students = $students->merge($students_1);
            $students = $students->merge($students_2);
            $students = $students->merge($students_3);
            $students = $students->sortBy('firstname')->sortBy('lastname')->values()->all();
            $students = collect($students)->sortBy('firstname')->sortBy('lastname')->unique('id')->values()->all();
        }
        $students = collect($students)->unique('id');
        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
        {
            return view('registrar.forms.form10v2.index')
                ->with('students', $students)
                ->with('extends', $extends);
        }else{
            return view('registrar.forms.form10.v3.index')
                ->with('students', $students)
                ->with('extends', $extends);
        }
    }
    public function getrecords(Request $request)
    {
        function numberToRomanRepresentation($number) {
            $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
            $returnValue = '';
            while ($number > 0) {
                foreach ($map as $roman => $int) {
                    if($number >= $int) {
                        $number -= $int;
                        $returnValue .= $roman;
                        break;
                    }
                }
            }
            return $returnValue;
        }
        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.divisiontext',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();

        $studentid = $request->get('studentid');
        $acadprogid = $request->get('acadprogid');

        if($acadprogid == 5)
        {
            $gradelevels = DB::table('gradelevel')
                ->select('id','levelname','sortid as levelsortid')
                ->where('acadprogid', $acadprogid)
                ->where('deleted','0')
                ->orderBy('sortid','asc')
                ->get();

            $shsgradelevels = array();
            foreach($gradelevels as $gradelevel)
            {
                $romannumeral = numberToRomanRepresentation(preg_replace('/\D+/', '', $gradelevel->levelname));
                for($x = 1; $x <= 2; $x++)
                {
                    // $gradelevel->semid = $x;
                    // array_push($shsgradelevels, $gradelevel);
                    array_push($shsgradelevels, (object)array(
                        'id'            => $gradelevel->id,
                        'levelname'            => $gradelevel->levelname,
                        'levelsortid'            => $gradelevel->levelsortid,
                        'semid'            => $x,
                        'romannumeral'            => $romannumeral
                    ));
                }
            }
            $gradelevels = $shsgradelevels;
    
        }else{
            $gradelevels = DB::table('gradelevel')
                ->select('id','levelname','sortid as levelsortid')
                ->where('acadprogid', $acadprogid)
                ->where('deleted','0')
                ->orderBy('sortid','asc')
                ->get();
    
        }
          
        $displayaccomplished = null;
        $collectgradelevels = array();
        foreach($gradelevels as $gradelevel)
        {
            $gradelevel->recordinputype = 0; //0 = auto; 1 = manual; 2 = upload;
            $gradelevel->autoexists = 0;
            $gradelevel->manualexists = 0;
            $gradelevel->recordid = 0;
            $gradelevel->inschool = 0;
            $gradelevel->syid = 0;
            $gradelevel->sydesc = null;
            $gradelevel->sectionid = 0;
            if($acadprogid == 5)
            {
                $enrollmentadetails = DB::table('sh_enrolledstud')
                    ->select('sh_enrolledstud.*','sy.sydesc','sections.sectionname','sh_strand.strandname','sh_strand.strandcode','sh_track.trackname')
                    ->join('sy','sh_enrolledstud.syid','=','sy.id')
                    ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                    ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                    ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                    ->where('sh_enrolledstud.studid', $studentid)
                    ->where('sh_enrolledstud.levelid', $gradelevel->id)
                    ->where('sh_enrolledstud.semid', $gradelevel->semid)
                    ->where('sh_enrolledstud.deleted','0')
                    ->get();


            }else{
                $enrollmentadetails = DB::table('enrolledstud')
                    ->select('enrolledstud.*','sy.sydesc','sections.sectionname')
                    ->join('sy','enrolledstud.syid','=','sy.id')
                    ->join('sections','enrolledstud.sectionid','=','sections.id')
                    ->where('enrolledstud.studid', $studentid)
                    ->where('enrolledstud.levelid', $gradelevel->id)
                    ->where('enrolledstud.deleted','0')
                    ->get();
            }
            
            
            if(count($enrollmentadetails) > 0)
            {
                foreach($enrollmentadetails as $eachrecord)
                {
                    $teachername = '';
                    $autoinfo = array();
                    $displayaccomplished = $eachrecord->strandname ?? '';
                    $eachrecord->autoexists = 1;
                    $eachrecord->inschool = 1;
                    // $gradelevel->sydesc = $eachrecord->sydesc;
                    // $gradelevel->syid =  $eachrecord->syid;
                    // $gradelevel->sectionid = $eachrecord->sectionid;
                    // $gradelevel->strandid =  $eachrecord->strandid;
                    // $gradelevel->promotionstatus =  $eachrecord->promotionstatus;
                    
                    $getTeacher = Db::table('sectiondetail')
                        ->select(
                            'teacher.title',
                            'teacher.firstname',
                            'teacher.middlename',
                            'teacher.lastname',
                            'teacher.suffix'
                            )
                        ->join('teacher','sectiondetail.teacherid','teacher.id')
                        ->where('sectiondetail.sectionid',$eachrecord->sectionid)
                        ->where('sectiondetail.syid',$eachrecord->syid)
                        ->where('sectiondetail.deleted','0')
                        ->first();
        
                    if($getTeacher)
                    {
                        if($getTeacher->title!=null)
                        {
                            $teachername.=$getTeacher->title.' ';
                        }
                        if($getTeacher->firstname!=null)
                        {
                            $teachername.=$getTeacher->firstname.' ';
                        }
                        if($getTeacher->middlename!=null)
                        {
                            $teachername.=$getTeacher->middlename[0].'. ';
                        }
                        if($getTeacher->middlename!=null)
                        {
                            $teachername.=$getTeacher->lastname.' ';
                        }
                        if($getTeacher->lastname!=null)
                        {
                            $teachername.=$getTeacher->suffix.' ';
                        }
                    }
                    $recordincharge = null;
                    $datechecked = null;
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                    {
                        $recordincharge = 'MERLIE S. SABUELO, REGISTRAR';
                        $datechecked = date('m/d/Y');
                    }
                    $autoinfo = array((object)array(
                        'syid'          => $eachrecord->syid,
                        'sydesc'          => $eachrecord->sydesc,
                        'semid'          => $eachrecord->semid ?? null,
                        'strandid'          => $eachrecord->strandid ?? null,
                        'strandcode'          => $eachrecord->strandcode ?? null,
                        'strandname'          => $eachrecord->strandname ?? null,
                        'trackname'          => $eachrecord->trackname ?? null,
                        'sectionname'          => $eachrecord->sectionname,
                        'teachername'          => $teachername,
                        'schoolid'          => $schoolinfo->schoolid,
                        'schoolname'          => $schoolinfo->schoolname,
                        'schooladdress'          => $schoolinfo->address,
                        'schooldistrict'          => $schoolinfo->district,
                        'schooldivision'          => $schoolinfo->divisiontext,
                        'schoolregion'          => $schoolinfo->region,
                        'remarks'          => null,
                        'recordincharge'          => $recordincharge,
                        'datechecked'          => $datechecked,
                        'credit_advance'          => null,
                        'credit_lack'          => null,
                        'noofyears'          =>  null
                    ));
                    // return $enrollmentadetails;
                    if($eachrecord->syid == 0)
                    {
                        $studgrades =array();
                    }else{
                        if($acadprogid == 5)
                        {
                            $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $eachrecord->levelid,$studentid,$eachrecord->syid,$eachrecord->strandid,null,$eachrecord->sectionid);
        
                        }else{
                            $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $eachrecord->levelid,$studentid,$eachrecord->syid,null,null,$eachrecord->sectionid);
                        }
                    }
                    
                    $temp_grades = array();
                    $generalaverage = array();
                    foreach($studgrades as $item){
                        if($item->id == 'G1'){
                            array_push($generalaverage,$item);
                        }else{
                            array_push($temp_grades,$item);
                        }
                    }
                    $studgrades = $temp_grades;
                    if($acadprogid == 5)
                    {
                        $generalaverage = collect($generalaverage)->where('semid',$eachrecord->semid)->values();
                        $studgrades = collect($studgrades)->where('semid', $eachrecord->semid)->values()->all();
                    }
                    $autogrades = collect($studgrades)->sortBy('sortid')->values()->all();
                   
                    $gradesadd = 0;
                    if(count($autogrades)>0)
                    {
                        // return collect($gradelevel);
                        // return collect($autogrades);
                        // return $gradelevel->syid;
                        foreach($autogrades as $grade)
                        {
                            if(!collect($grade)->has('inMAPEH'))
                            {
                                $grade->inMAPEH = 0;
                            }
                            if(!collect($grade)->has('inTLE'))
                            {
                                $grade->inTLE = 0;
                            }
                            if(!collect($grade)->has('subjdesc'))
                            {
                                if(collect($grade)->has('subjectcode'))
                                {
                                    $grade->subjdesc = $grade->subjectcode;
                                }
                                $grade->q1 = $grade->quarter1;
                                $grade->q2 = $grade->quarter2;
                                $grade->q3 = $grade->quarter3;
                                $grade->q4 = $grade->quarter4;
                            }else{
                                // $grade->subjdesc = ucwords(strtolower($grade->subjdesc));
                            }
                            if($acadprogid == 5)
                            {
                                if($grade->semid == 2)
                                {
                                    $grade->q1 = $grade->q3;
                                    $grade->q2 = $grade->q4;
                                }
                            }
                            // 0 = noteditable ; 1 = for adding (first time) ; 2 = editable;
                            $grade->q1stat = 0;
                            $grade->q2stat = 0;
                            $grade->q3stat = 0;
                            $grade->q4stat = 0;
                            
        
                            $complete = 0;
                            $chekifaddinautoexist = DB::table('sf10grades_addinauto')
                                    ->where('studid',$studentid)
                                    ->where('subjid',$grade->subjid)
                                    ->where('levelid',$eachrecord->levelid)
                                    ->where('deleted',0)
                                    ->get();
        
                            if(count($chekifaddinautoexist)>0)
                            {
                                $gradesadd += 1;
                            }
                            if(collect($chekifaddinautoexist)->where('quarter',1)->count() > 0)
                            {
                                $grade->q1stat = 2;
                                $grade->q1    = collect($chekifaddinautoexist)->where('quarter',1)->first()->grade;
                                $complete+=1;;
                            }
                            if(collect($chekifaddinautoexist)->where('quarter',2)->count() > 0)
                            {
                                $grade->q2stat = 2;
                                $grade->q2    = collect($chekifaddinautoexist)->where('quarter',2)->first()->grade;
                                $complete+=1;;
                            }
                            if(collect($chekifaddinautoexist)->where('quarter',3)->count() > 0)
                            {
                                $grade->q3stat = 2;
                                $grade->q3    = collect($chekifaddinautoexist)->where('quarter',3)->first()->grade;
                                $complete+=1;;
                            }
                            if(collect($chekifaddinautoexist)->where('quarter',4)->count() > 0)
                            {
                                $grade->q4stat = 2;
                                $grade->q4    = collect($chekifaddinautoexist)->where('quarter',4)->first()->grade;
                                $complete+=1;;
                            }
        
                            if($grade->q1 == 0)
                            {
                                $grade->q1 = null;
                                $grade->q1stat = 1;
                            }else{
                                $complete+=1;;
                            }
                            if($grade->q2 == 0)
                            {
                                $grade->q2 = null;
                                $grade->q2stat = 1;
                            }else{
                                $complete+=1;;
                            }
                            if($grade->q3 == 0)
                            {
                                $grade->q3 = null;
                                $grade->q3stat = 1;
                            }else{
                                $complete+=1;;
                            }
                            if($grade->q4 == 0)
                            {
                                $grade->q4 = null;
                                $grade->q4stat = 1;
                            }else{
                                $complete+=1;;
                            }
                            if($grade->q1 == null)
                            {
                                $grade->q1stat = 1;
                            }
                            if($grade->q2 == null)
                            {
                                $grade->q2stat = 1;
                            }
                            if($grade->q3 == null)
                            {
                                $grade->q3stat = 1;
                            }
                            if($grade->q4 == null)
                            {
                                $grade->q4stat = 1;
                            }
        
                            if($acadprogid == 5)
                            {
                                $quarterlimit = 2;
                            }else{
                                $quarterlimit = 4;
                            }
                            if($complete < $quarterlimit)
                            {
                                $qg = null;
                                $remarks = ($eachrecord->studstatus != 3 && $eachrecord->studstatus != 5) ? 'TAKING' : '';
                            }else{
                                if($acadprogid == 5)
                                {
                                    $qg = ($grade->q1 + $grade->q2) / 2;
                                }else{
                                    $qg = ($grade->q1 + $grade->q2 + $grade->q3 + $grade->q4) / 4;
                                }
                                if($qg>=75){
                
                                    $remarks = "PASSED";
                
                                }elseif($qg == null){
                
                                    $remarks = null;
                
                                }else{
                                    $remarks = "FAILED";
                                }
                                
                                if($qg == 0)
                                {
                                    $qg = null;
                                    $remarks = null;
                                }
                            }
                            
                            if($acadprogid != 5)
                            {
                                $grade->subjcode = null;
                            }
                            $sortsubjcode = 0;
                            if($acadprogid == 5)
                            {
                                $subjcode = DB::table('sh_subjects')
                                    ->where('id', $grade->subjid)
                                    ->first();
            
                                if($subjcode)
                                {
                                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                                    {
                                        // return collect($subject)
                                        if(in_array($subject->subjid, $subjidarray))
                                        {
                                                $subjcode = 'Other Subject';
                                        }else{
                                            if($subjcode->type == 1)
                                            {
                                                $subjcode = 'CORE';
                                            }
                                            elseif($subjcode->type == 3)
                                            {
                                                $subjcode = 'APPLIED';
                                            }
                                            elseif($subjcode->type == 2)
                                            {
                                                $subjcode = 'SPECIALIZED';
                                            }else{
                                                $subjcode = 'Other Subject';
                                            }
                                        }
                                    }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                    {
                                        if($subjcode->type == 1)
                                        {
                                            $subjcode = 'CORE';
                                        }
                                        elseif($subjcode->type == 2)
                                        {
                                            $sortsubjcode = 2;
                                            $subjcode = 'SPECIALIZED';
                                        }
                                        elseif($subjcode->type == 3)
                                        {
                                            $sortsubjcode = 1;
                                            $subjcode = 'APPLIED';
                                        }else{
                                            $sortsubjcode = 3;
                                            $subjcode = 'Other Subject';
                                        }
                                    }else{
                                        if($subjcode->type == 1)
                                        {
                                            $subjcode = 'CORE';
                                        }
                                        elseif($subjcode->type == 2)
                                        {
                                            $subjcode = 'APPLIED';
                                        }
                                        elseif($subjcode->type == 3)
                                        {
                                            $subjcode = 'SPECIALIZED';
                                        }else{
                                            $subjcode = 'Other Subject';
                                        }
                                    }
                                }else{
                                    $subjcode = null;
                                }
                                $grade->subjcode = $subjcode;
                            }
                            $grade->sortsubjcode = $sortsubjcode;
                            $grade->subjtitle = $grade->subjdesc;
                            $grade->quarter1 = (number_format($grade->q1) > 0 ? number_format($grade->q1) : null);
                            $grade->quarter2 = (number_format($grade->q2) > 0 ? number_format($grade->q2) : null);
                            $grade->quarter3 = (number_format($grade->q3) > 0 ? number_format($grade->q3) : null);
                            $grade->quarter4 = (number_format($grade->q4) > 0 ? number_format($grade->q4) : null);
                            $grade->finalrating = (number_format($qg) > 0 ? number_format($qg) : null);
                            $grade->remarks = $remarks;
                            $grade->sort = $grade->sortsubjcode.' '.(isset($grade->sortid) ? $grade->sortid : '');
                        }
                        
                    }
                    
                    // $autogrades = collect($autogrades)->map(function ($user) {
                    //     return (object)collect($user)
                    //         ->only(['subjdesc', 'subjcode','inSF9','id','plotsort','sortid','subjid','semid','strandid','gradessetup','search','mapeh','inTLE','inMAPEH','q1','q2','q3','q4','quarter1','quarter2','quarter3','quarter4','finalrating','actiontaken','ver','q1stat','q2stat','q3stat','q4stat','subjtitle','remarks','sortsubjcode','sort'])
                    //         ->all();
                    // })->values()->all();
                    // $autogrades = collect($autogrades)->sortBy('sort')->values();
                    
                    $subjaddedforauto     = DB::table('sf10grades_subjauto')
                                            ->where('studid',$studentid)
                                            ->where('syid',$eachrecord->syid)
                                            ->where('levelid',$eachrecord->levelid)
                                            ->where('deleted','0')
                                            ->get();
                        
                    
                    
                    $attendancesummary = array();
                    if($eachrecord->syid > 0)
                    {
                        $attendancesummary = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($eachrecord->syid);
                        foreach( $attendancesummary as $item){
                            $month_count = \App\Models\Attendance\AttendanceData::monthly_attendance_count($eachrecord->syid,$item->month,$studentid);
                            $item->present = collect($month_count)->where('present',1)->count() + collect($month_count)->where('tardy',1)->count() + collect($month_count)->where('cc',1)->count();
                            $item->absent = collect($month_count)->where('absent',1)->count();
                        }
                        
                        $attendancesummary = collect($attendancesummary)->sortBy('sort');
                    }
                    $autoattendance = $attendancesummary;
                    
                    array_push($collectgradelevels,(object)[
                        'id'       => $eachrecord->levelid,
                        'levelname'       => $gradelevel->levelname,
                        'romannumeral'       => $gradelevel->romannumeral ?? '',
                        'sortthis'       => $gradelevel->levelsortid.' '.$eachrecord->sydesc.' '.($eachrecord->semid ?? ''),
                        'levelsortid'       => $gradelevel->levelsortid,
                        'recordinputype'       => $gradelevel->recordinputype,
                        'autoexists'       =>1,
                        'manualexists'       => 0,
                        'recordid'       => 0,
                        'inschool'       => 1,
                        'syid'       => $eachrecord->syid,
                        'sydesc'       => $eachrecord->sydesc,
                        'semid'       =>$eachrecord->semid ?? null,
                        'sectionid'       => $eachrecord->sectionid,
                        'strandid'       => $eachrecord->strandid ?? null,
                        'strandcode'       => $eachrecord->strandcode ?? null,
                        'strandname'       => $eachrecord->strandname ?? null,
                        'trackname'       => $eachrecord->trackname ?? null,
                        'headerinfo'       => $autoinfo,
                        'grades'       => $autogrades,
                        'studstatus'       => $eachrecord->studstatus,
                        'promotionstatus'       => $eachrecord->promotionstatus,
                        'generalaverage'       => $generalaverage,
                        'subjaddedforauto'       => $subjaddedforauto,
                        'attendance'       => $autoattendance,
                        'remedialclasses'       => array(),
                        'withgrades'       => count($autogrades)>0 ? 1: 0
                    ]);
                }
            }
            if($acadprogid == 5)
            {
                $manualrecords = DB::table('sf10')
                    ->select('sf10.id','sf10.id as recordid','sf10.syid','sf10.sydesc','sf10.levelid','gradelevel.levelname','sf10.sectionid','sf10.sectionname','sf10.teachername','sf10.schoolid','sf10.schoolname','sf10.schooladdress','sf10.schooldistrict','sf10.schooldivision','sf10.schoolregion','sf10.remarks','sf10.recordincharge','sf10.datechecked','sf10.credit_advance','sf10.credit_lack','sf10.noofyears','recordinputtype','isactive','trackname','strandname','sf10.semid')
                    ->join('gradelevel','sf10.levelid','=','gradelevel.id')
                    ->where('sf10.studid', $studentid)
                    ->where('sf10.acadprogid', $acadprogid)
                    ->where('sf10.levelid', $gradelevel->id)
                    ->where('sf10.deleted','0')
                    // ->where('sf10.isactive','1')
                    ->get();
                $manualrecords = collect($manualrecords)->where('semid',$gradelevel->semid)->values();
            }else{
                
                $manualrecords = DB::table('sf10')
                ->select('sf10.id','sf10.id as recordid','sf10.syid','sf10.sydesc','sf10.levelid','gradelevel.levelname','sf10.sectionid','sf10.sectionname','sf10.teachername','sf10.schoolid','sf10.schoolname','sf10.schooladdress','sf10.schooldistrict','sf10.schooldivision','sf10.schoolregion','sf10.remarks','sf10.recordincharge','sf10.datechecked','sf10.credit_advance','sf10.credit_lack','sf10.noofyears','recordinputtype','isactive','trackname','strandname','sf10.semid')
                ->join('gradelevel','sf10.levelid','=','gradelevel.id')
                ->where('sf10.studid', $studentid)
                ->where('sf10.acadprogid', $acadprogid)
                ->where('sf10.levelid', $gradelevel->id)
                ->where('sf10.deleted','0')
                // ->where('sf10.isactive','1')
                ->get();
            }
            // if($gradelevel->id == 15 && $gradelevel->semid == 2)
            // {
            //     return $manualrecords;
            // }
            
            $manualinfo = array();
            $manualgrades = array();
            $manualattendance = array();
            $manualremedialclasses = array();
            
            // if(count($enrollmentadetails) == 0)
            // {
                if(count($manualrecords)>0)
                {
                    $gradelevel->manualexists = 1;
                    // return $manualrecords;
                    $manualinfo = $manualrecords;
                    foreach($manualrecords as $manualrecord)
                    {
                        $manualrecord->type = 2;
    
                        if($acadprogid == 4)
                        {
                            $grades = DB::table('sf10grades_junior')
                                    ->where('headerid', $manualrecord->id)
                                    ->where('deleted','0')
                                    ->get();
                            if(count($grades)>0)
                            {
                                foreach($grades as $grade)
                                {                    
                                    $grade->q1stat = 0;
                                    $grade->q2stat = 0;
                                    $grade->q3stat = 0;
                                    $grade->q4stat = 0;
                                    
                                    if($grade->q1 == 0)
                                    {
                                        $grade->q1 = null;
                                    }
                                    if($grade->q2 == 0)
                                    {
                                        $grade->q2 = null;
                                    }
                                    if($grade->q3 == 0)
                                    {
                                        $grade->q3 = null;
                                    }
                                    if($grade->q4 == 0)
                                    {
                                        $grade->q4 = null;
                                    }
                                    $grade->subjcode = null;
                                    $grade->subjtitle = $grade->subjectname;
                                    $grade->subjdesc = $grade->subjectname;
                                    $grade->quarter1 = $grade->q1;
                                    $grade->quarter2 = $grade->q2;
                                    $grade->quarter3 = $grade->q3;
                                    $grade->quarter4 = $grade->q4;
        
                                    array_push($manualgrades,(object)array(
                                        'id'            => $grade->id,
                                        'headerid'            => $grade->headerid,
                                        'subjectid'            => $grade->subjectid,
                                        'subjcode'            => $grade->subjcode,
                                        'subjtitle'            => $grade->subjtitle,
                                        'subjdesc'            => $grade->subjdesc,
                                        'q1'            => $grade->q1,
                                        'q2'            => $grade->q2,
                                        'q3'            => $grade->q3,
                                        'q4'            => $grade->q4,
                                        'quarter1'            => $grade->q1,
                                        'quarter2'            => $grade->q2,
                                        'quarter3'            => $grade->q3,
                                        'quarter4'            => $grade->q4,
                                        'finalrating'            => $grade->finalrating,
                                        'credits'            => $grade->credits,
                                        'remarks'            => $grade->remarks,
                                        'inMAPEH'            => $grade->inMAPEH,
                                        'inTLE'            => $grade->inTLE,
                                        'fromsystem'            => $grade->fromsystem,
                                        'isgenave'            => $grade->isgenave,
                                        'editablegrades'            => $grade->editablegrades,
                                        'inputtype'            => $grade->inputtype,
                                    ));
                                }
                                
                            }
                        }
                        elseif($acadprogid == 5)
                        {
                            $grades = DB::table('sf10grades_senior')
                                    ->where('headerid', $manualrecord->id)
                                    ->where('deleted','0')
                                    ->get();
                                    
                            if(count($grades)>0)
                            {
                                foreach($grades as $grade)
                                {
                                    $grade->q1stat = 0;
                                    $grade->q2stat = 0;
                                    
                                    if($grade->q1 == 0)
                                    {
                                        $grade->q1 = null;
                                    }
                                    if($grade->q2 == 0)
                                    {
                                        $grade->q2 = null;
                                    }
                                    $grade->semid = $manualrecord->semid;
                                     $grade->semid = $manualrecord->semid;
                                }
                                
                                 $grades[0]->semid = $manualrecord->semid;
                            }
                            if(count($grades)>0)
                            {
                                foreach($grades as $grade)
                                {         
                                    $sortsubjcode = 0;           
                                    $grade->q1stat = 0;
                                    $grade->q2stat = 0;
                                    
                                    if($grade->q1 == 0)
                                    {
                                        $grade->q1 = null;
                                    }
                                    if($grade->q2 == 0)
                                    {
                                        $grade->q2 = null;
                                    }
                                    $grade->subjcode = $grade->subjcode;
                                    $grade->subjtitle = $grade->subjdesc;
                                    $grade->subjdesc = $grade->subjdesc;
                                    $grade->quarter1 = $grade->q1;
                                    $grade->quarter2 = $grade->q2;
                                    if(strtolower($grade->subjcode) == 'core')
                                    {
                                        $sortsubjcode = '0-'.$grade->id;
                                    }
                                    elseif(strtolower($grade->subjcode) == 'specialized')
                                    {
                                        $sortsubjcode = '2-'.$grade->id;
                                        $subjcode = 'SPECIALIZED';
                                    }
                                    elseif(strtolower($grade->subjcode) == 'applied')
                                    {
                                        $sortsubjcode = '1-'.$grade->id;
                                        $subjcode = 'APPLIED';
                                    }else{
                                        $sortsubjcode = '3-'.$grade->id;
                                    }
                                    
                                    array_push($manualgrades,(object)array(
                                        'id'            => $grade->id,
                                        'headerid'            => $grade->headerid,
                                        'subjectid'            => $grade->subjid,
                                        'subjcode'            => $grade->subjcode,
                                        'subjtitle'            => $grade->subjdesc,
                                        'subjdesc'            => $grade->subjdesc,
                                        'q1'            => $grade->q1,
                                        'q2'            => $grade->q2,
                                        'quarter1'            => $grade->q1,
                                        'quarter2'            => $grade->q2,
                                        'finalrating'            => $grade->finalrating,
                                        'remarks'            => $grade->remarks,
                                        'fromsystem'            => $grade->fromsystem,
                                        'editablegrades'            => $grade->editablegrades,
                                        'inputtype'            => $grade->inputtype,
                                        'isgenave'            => $grade->isgenave,
                                        'sortsubjcode'            => $sortsubjcode
                                    ));
                                }
                                $grades = collect($manualgrades)->sortBy('sortsubjcode')->all();
                            }
                        }
                        // if($gradelevel->id == 15 && $gradelevel->semid == 2)
                        // {
                        //     return $grades;
                        // }
                        $remedialclasses = DB::table('sf10remedial_senior')
                                ->where('headerid', $manualrecord->id)
                                ->where('deleted','0')
                                ->get();
        
                    
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                        {
                            $attendance = DB::table('sf10attendance')
                            ->where('sf10attendance.studentid',$studentid)
                                ->where('acadprogid','5')
                                ->where('sydesc',$manualrecord->sydesc)
                                ->where('deleted','0')
                                ->get();
        
                        }else{
                            $attendance = array();
                        }
                        $manualrecord->studstatus = 1;
                        $manualrecord->promotionstatus = null;
                        $manualattendance = $attendance;
                        $manualrecord->attendance = $attendance;
                        $manualremedialclasses = $remedialclasses;
                        $manualrecord->remedialclasses = $remedialclasses;
                        $manualrecord->grades               = $grades;
                        $manualrecord->generalaverage       = collect($grades)->where('isgenave',1)->values();
                        // $manualrecord->subjaddedforauto     = array();
                        // $manualrecord->attendance           = $attendance;
                        // $manualrecord->remedials            = $remedialclasses;
                    }
                }
                if(collect($manualrecords)->count()>0)
                {
                    // foreach(collect($manualrecords)->where('isactive','1')->values() as $eachmanualrecord)
                    foreach($manualrecords as $eachmanualrecord)
                    {
                        // return $eachmanualrecord;
                        // return collect($manualrecords[1]);
                        // $addcollect_sydesc = isset($eachmanualrecord->sydesc) ? $eachmanualrecord->sydesc : null;
                        // $addcollect_recordinputype = isset($eachmanualrecord->recordinputtype) ? $eachmanualrecord->recordinputtype : 1;
                        $addcollect_headerinfo = $eachmanualrecord;
                        // $addcollect_grades = collect($eachmanualrecord->grades)->where('isgenave','0')->values()->all();
                        // $addcollect_generalaverage = collect($eachmanualrecord->grades)->where('isgenave','1')->values()->all();
                        $addcollect_subjaddedforauto = array();
                        // $addcollect_attendance = $eachmanualrecord->attendance;
                        // $addcollect_strandname = $eachmanualrecord->strandname;
                        // $addcollect_trackname = $eachmanualrecord->trackname;
                        $addcollect_remedialclasses = $eachmanualrecord->remedialclasses;
                        if(count(collect($eachmanualrecord->grades)->where('isgenave','0')->values()->all()) == 0)
                        {
                        $addcollect_withgrades = 0;
                        }else{
                        $addcollect_withgrades = 1;
                        }
    
                        // array_push($collectgradelevels,(object)[
                            array_push($collectgradelevels,(object)[
                            'id'       => $eachmanualrecord->levelid,
                            'levelname'       => $gradelevel->levelname,
                            'romannumeral'       => $gradelevel->romannumeral ?? '',
                            'sortthis'       => $gradelevel->levelsortid.' '.$eachmanualrecord->sydesc.' '.($eachmanualrecord->semid ?? ''),
                            'levelsortid'       => $gradelevel->levelsortid,
                            'recordinputype'       => isset($eachmanualrecord->recordinputtype) ? $eachmanualrecord->recordinputtype : 1,
                            'autoexists'       => null,
                            'manualexists'       => 1,
                            'recordid'       => $eachmanualrecord->recordid,
                            'inschool'       => 0,
                            'syid'       => $eachmanualrecord->syid,
                            'sydesc'       => $eachmanualrecord->sydesc,
                            'semid'       => isset($eachmanualrecord->semid) ? $eachmanualrecord->semid : null,
                            'sectionid'       => $eachmanualrecord->sectionid,
                            'strandid'       => $eachmanualrecord->strandid ?? null,
                            'strandcode'       => $eachmanualrecord->strandcode ?? null,
                            'strandname'       => $eachmanualrecord->strandname ?? '',
                            'trackname'       => $eachmanualrecord->trackname ?? '',
                            'headerinfo'       => array($addcollect_headerinfo),
                            'grades'       =>  collect($eachmanualrecord->grades)->where('isgenave','0')->values()->all(),
                            'studstatus'       => studstatus,
                            'promotionstatus'       => null,
                            'generalaverage'       => collect($eachmanualrecord->grades)->where('isgenave','1')->values()->all(),
                            'subjaddedforauto'       => $addcollect_subjaddedforauto,
                            'attendance'       => $eachmanualrecord->attendance,
                            'remedialclasses'       => $addcollect_remedialclasses,
                            'withgrades'       => $addcollect_withgrades,
                        ]);
                        // if($gradelevel->id == 15 && $gradelevel->semid == 2)
                        // {
                        //     // return $manualrecords;collectgradelevels
                        // return $collectgradelevels;
                        // }
                        // return $gradelevels;
                    }
                }
                // else{
                //     $gradelevel->headerinfo = $autoinfo;
                //     $gradelevel->grades = $autogrades;
                //     $gradelevel->generalaverage = $generalaverage;
                //     $gradelevel->subjaddedforauto = $subjaddedforauto;
                //     $gradelevel->attendance = $autoattendance;
                //     $gradelevel->remedialclasses = array();
                //     if(count($autogrades) == 0)
                //     {
                //     $gradelevel->withgrades = 0;
                //     }else{
                //     $gradelevel->withgrades = 1;
                //     }
                // }
            // }else{
            //     if(count($manualrecords)>0)
            //     {
            //         $gradelevel->manualexists = 1;
            //     }
            //     $gradelevel->headerinfo = $autoinfo;
            //     $gradelevel->grades = $autogrades;
            //     $gradelevel->generalaverage = $generalaverage;
            //     $gradelevel->subjaddedforauto = $subjaddedforauto;
            //     $gradelevel->attendance = $autoattendance;
            //     $gradelevel->remedialclasses = array();
            //     if(count($autogrades) == 0)
            //     {
            //     $gradelevel->withgrades = 0;
            //     }else{
            //     $gradelevel->withgrades = 1;
            //     }
            // }
            // if($gradelevel->syid == DB::table('sy')->where('isactive','1')->first()->id)
            // {
            //     $gradelevel->headerinfo = $autoinfo;
            //     $gradelevel->grades = $autogrades;
            //     $gradelevel->generalaverage = $generalaverage;
            //     $gradelevel->subjaddedforauto = $subjaddedforauto;
            //     $gradelevel->attendance = $autoattendance;
            //     $gradelevel->remedialclasses = array();
            // }else{
            //     $gradelevel->sydesc = isset($manualinfo[0]->sydesc) ? $manualinfo[0]->sydesc : null;
            //     $gradelevel->recordinputype = isset($manualinfo[0]->recordinputtype) ? $manualinfo[0]->recordinputtype : 3;
            //     $gradelevel->headerinfo = $manualinfo;
            //     $gradelevel->grades = $manualgrades;
            //     $gradelevel->generalaverage = array();
            //     $gradelevel->subjaddedforauto = array();
            //     $gradelevel->attendance = $manualattendance;
            //     $gradelevel->remedialclasses = $manualremedialclasses;
            // }
            // $gradelevel->autoinfo = $autoinfo;
            // $gradelevel->autogrades = $autogrades;
            // $gradelevel->autogrades = $autogrades;
            // $gradelevel->subjaddedforauto = $subjaddedforauto;
            // $gradelevel->autoattendance = $autoattendance;
            // $gradelevel->manualinfo = $manualinfo;
            // $gradelevel->manualgrades = $manualgrades;
            // $gradelevel->manualattendance = $manualattendance;
            // $gradelevel->manualremedialclasses = $manualremedialclasses;
            if(count($enrollmentadetails) == 0 && count($manualrecords)==0)
            {
                array_push($collectgradelevels,(object)[
                    'id'       => $gradelevel->id,
                    'levelname'       => $gradelevel->levelname,
                    'romannumeral'       => $gradelevel->romannumeral ?? '',
                    'sortthis'       => $gradelevel->levelsortid,
                    'levelsortid'       => $gradelevel->levelsortid,
                    'recordinputype'       => 3,
                    'autoexists'       => 0,
                    'manualexists'       => 0,
                    'recordid'       => 0,
                    'inschool'       => 0,
                    'syid'       => null,
                    'sydesc'       => null,
                    'semid'       => $gradelevel->semid ?? null,
                    'sectionid'       => null,
                    'strandid'       => null,
                    'strandcode'       => null,
                    'strandname'       => null,
                    'trackname'       => null,
                    'headerinfo'       =>array(),
                    'grades'       => array(),
                    'studstatus'       => 0,
                    'promotionstatus'       => 0,
                    'generalaverage'       => array(),
                    'subjaddedforauto'       => array(),
                    'attendance'       => array(),
                    'remedialclasses'       => array(),
                    'withgrades'       => 0
                ]);

            }
        }
        if($acadprogid == 5)
        {
        $gradelevels = collect($collectgradelevels)->sortBy('sortthis')->values()->all();
        }else{
        $gradelevels = collect($collectgradelevels)->sortBy('sydesc')->sortBy('levelsortid')->values()->all();
        }
        $finallevels = array();
        foreach($gradelevels as $eachgradelevel)
        {
            if(isset($eachgradelevel->headerinfo))
            {
                array_push($finallevels, $eachgradelevel);
            }
        }
        // return count($gradelevels);
        // $gradelevels = $collectgradelevels;
        $gradelevels = $finallevels;
        // return $gradelevels;
        // return count($gradelevels);
        if($acadprogid == 5)
        {
            $subjects = DB::table('subject_plot')
                ->select('sh_subjects.id','subjcode','sh_subjects.subjtitle as subjdesc','subject_plot.strandid','subject_plot.plotsort','subject_plot.semid','subject_plot.syid','subject_plot.levelid','subject_plot.strandid')
                ->join('sh_subjects','subject_plot.subjid','=','sh_subjects.id')
                ->where('sh_subjects.inSF9', 1)
                ->where('sh_subjects.deleted', 0)
                ->where('subject_plot.deleted', 0)
                ->orderBy('subject_plot.plotsort','asc')
                ->get();  
    
            $admissiondate = DB::table('admissiondate')
                  ->select('admissiondate.*','gradelevel.levelname','sy.sydesc')
                  ->join('sy','admissiondate.syid','=','sy.id')
                  ->join('gradelevel','admissiondate.levelid','=','gradelevel.id')
                  ->where('gradelevel.deleted','0')
                  ->where('admissiondate.deleted','0')
                  ->where('sydesc',collect($gradelevels)->where('sydesc','!=', null)->first()->sydesc ?? null)
                  ->where('levelname',collect($gradelevels)->where('levelname','!=', null)->first()->levelname ?? null)
                  ->first()->admissiondate ?? null;

            $eligibility = DB::table('sf10eligibility_senior')
                ->where('studid', $studentid)
                ->where('deleted','0')
                ->first();
                
            if(!$eligibility)
            {
                $eligibility = (object)array(
                    'completerhs'       =>  0,
                    'genavehs'          =>  null,
                    'completerjh'       =>  0,
                    'genavejh'          =>  null,
                    'graduationdate'    =>  null,
                    'schoolname'        =>  null,
                    'schooladdress'     =>  null,
                    'peptpasser'        =>  0,
                    'peptrating'        =>  null,
                    'alspasser'         =>  0,
                    'alsrating'         =>  null,
                    'examdate'          =>  null,
                    'centername'        =>  null,
                    'shsadmissiondate'        =>  $admissiondate,
                    'strandaccomplished'        =>  null,
                    'others'            =>  null
                );
            }else{
                if($eligibility->shsadmissiondate == null)
                {
                    $eligibility->shsadmissiondate = $admissiondate;
                }
            }
            $footer = DB::table('sf10_footer_senior')
                ->where('studid', $studentid)
                ->where('deleted','0')
                ->first();
                
            if(!$footer)
            {
                $footer = (object)array(
                    'strandaccomplished'        =>  $displayaccomplished,
                    'shsgenave'                 =>  null,
                    'honorsreceived'            =>  null,
                    'shsgraduationdate'         =>  null,
                    'shsgraduationdateshow'     =>  null,
                    'datecertified'             =>  null,
                    'certifiedby'             =>  null,
                    'datecertifiedshow'         =>  null,
                    'copyforupper'              =>  null,
                    'copyforlower'              =>  null
                );
            }else{
                if($footer->strandaccomplished == null)
                {
                    $footer->strandaccomplished = $displayaccomplished;
                }
                if($footer->shsgraduationdate != null)
                {
                    $footer->shsgraduationdate = date('m/d/Y', strtotime($footer->shsgraduationdate));
                    $footer->shsgraduationdateshow = date('Y-m-d', strtotime($footer->shsgraduationdate));
                }else{
                    $footer->shsgraduationdateshow = null;
                }
                if($footer->datecertified != null)
                {
                    $footer->datecertified = date('m/d/Y', strtotime($footer->datecertified));
                    $footer->datecertifiedshow = date('Y-m-d', strtotime($footer->datecertified));
                }else{
                    $footer->datecertifiedshow = null;
                }
            }

        }elseif($acadprogid == 4)
        {
            $subjects = DB::table('subject_plot')
                ->select('subjects.id','subjcode','subjects.subjdesc','subject_plot.strandid','subject_plot.plotsort','subject_plot.semid','subject_plot.syid','subject_plot.levelid','subject_plot.strandid')
                ->join('subjects','subject_plot.subjid','=','subjects.id')
                ->where('subjects.inSF9', 1)
                ->where('subjects.deleted', 0)
                ->where('subject_plot.levelid', '!=','14')
                ->where('subject_plot.levelid', '!=','15')
                ->where('subject_plot.deleted', 0)
                ->orderBy('subject_plot.plotsort','asc')
                ->get();  
                
            $subjects = collect($subjects)->unique('subjdesc')->values();
            $eligibility = DB::table('sf10eligibility_junior')
                ->where('studid', $studentid)
                ->where('deleted','0')
                ->first();
    
            if(!$eligibility)
            {
                $eligibility = (object)array(
                    'completer'  =>  0,
                    'genave'     =>  0,
                    'citation'          =>  null,
                    'schoolid'          =>  null,
                    'schoolname'        =>  null,
                    'schooladdress'     =>  null,
                    'peptpasser'        =>  0,
                    'peptrating'        =>  null,
                    'alspasser'         =>  0,
                    'alsrating'         =>  null,
                    'examdate'          =>  null,
                    'centername'        =>  null,
                    'centeraddress'     =>  null,
                    'remarks'           =>  null,
                    'specifyothers'     =>  null,
                    'guardianaddress'     =>  null,
                    'sygraduated'     =>  null,
                    'totalnoofyears'     =>  null
                );
            } 
            $footer = DB::table('sf10_footer_junior')
                ->where('studid', $studentid)
                ->where('deleted','0')
                ->first();
                
    
            if(!$footer)
            {
                $footer = (object)array(
                    'copyforupper'        =>  null,
                    'purpose'        =>  null,
                    'classadviser'                 =>  null,
                    'recordsincharge'            =>  null,
                    'copysentto'            =>  null,
                    'address'            =>  null
                );
            }
        }
        // return $gradelevels;
        // return $gradelevels;
        $eachsemsignatories = DB::table('sf10bylevelsign')
            ->where('studid',$studentid)
            ->where('semid','!=',null)            
            ->where('deleted','0')
            ->get();
        if(!$request->has('export'))
        {
            if($acadprogid == 5)
            {
                $footer = DB::table('sf10_footer_senior')
                    ->where('studid', $studentid)
                    ->where('deleted','0')
                    ->first();
                    
                if(!$footer)
                {
                    $footer = (object)array(
                        'strandaccomplished'        =>  $displayaccomplished,
                        'shsgenave'                 =>  null,
                        'honorsreceived'            =>  null,
                        'shsgraduationdate'         =>  null,
                        'shsgraduationdateshow'     =>  null,
                        'certifiedby'             =>  null,
                        'datecertified'             =>  null,
                        'datecertifiedshow'         =>  null,
                        'copyforupper'              =>  null,
                        'copyforlower'              =>  null
                    );
                }else{
                    if($footer->strandaccomplished == null)
                    {
                        $footer->strandaccomplished = $displayaccomplished;
                    }
                    if($footer->shsgraduationdate != null)
                    {
                        $footer->shsgraduationdate = date('m/d/Y', strtotime($footer->shsgraduationdate));
                        $footer->shsgraduationdateshow = date('Y-m-d', strtotime($footer->shsgraduationdate));
                    }else{
                        $footer->shsgraduationdateshow = null;
                    }
                    if($footer->datecertified != null)
                    {
                        $footer->datecertified = date('m/d/Y', strtotime($footer->datecertified));
                        $footer->datecertifiedshow = date('Y-m-d', strtotime($footer->datecertified));
                    }else{
                        $footer->datecertifiedshow = null;
                    }
                }
                // return collect($eligibility);
                // return $subjects;
                    //  return $gradelevels;
                    return view('registrar.forms.form10v2.records_shs')
                        ->with('eligibility', $eligibility)
                        ->with('eachsemsignatories', $eachsemsignatories)
                        ->with('studentid', $studentid)
                        ->with('acadprogid', $acadprogid)
                        ->with('gradelevels', $gradelevels)
                        ->with('footer', $footer)
                        ->with('subjects', $subjects);
                    //  return $gradelevels;
    
            }
            elseif($acadprogid == 4)
            {
                // return $gradelevels;
                    return view('registrar.forms.form10v2.records_jhs')
                        ->with('eligibility', $eligibility)
                        ->with('studentid', $studentid)
                        ->with('acadprogid', $acadprogid)
                        ->with('gradelevels', $gradelevels)
                        ->with('footer', $footer)
                        ->with('schoolinfo', $schoolinfo)
                        ->with('subjects', $subjects);
                    //  return $gradelevels;
    
            }
        }else{
            $studinfo = Db::table('studinfo')
                ->select(
                    'studinfo.id',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.lastname',
                    'studinfo.suffix',
                    'studinfo.lrn',
                    'studinfo.dob',
                    'studinfo.gender',
                    'studinfo.levelid',
                    'studinfo.street',
                    'studinfo.barangay',
                    'studinfo.city',
                    'studinfo.province',
                    'studinfo.mothername',
                    'studinfo.moccupation',
                    'studinfo.fathername',
                    'studinfo.foccupation',
                    'studinfo.guardianname',
                    'studinfo.ismothernum',
                    'studinfo.isfathernum',
                    'studinfo.isguardannum as isguardiannum',
                    'gradelevel.levelname',
                    'studinfo.sectionid as ensectid',
                    'gradelevel.id as enlevelid'
                    )
                ->leftJoin('gradelevel','studinfo.levelid','gradelevel.id')
                ->where('studinfo.id',$studentid)
                ->first();
            if($request->get('exporttype') == 'pdf')
            {
                $allgradelevels = DB::table('gradelevel')
                ->where('deleted','0')
                ->select('id','levelname','sortid')
                ->orderBy('sortid')
                ->get();
                $format = $request->get('format');
                if($request->get('acadprogid') == 5)
                {
                    $template = 'registrar/pdf/pdf_schoolform10_senior';
                    // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                    // {
                    //     $template = 'registrar/pdf/pdf_schoolform10_seniorlhs';
                    // }
                    // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                    // {
                    //     $template = 'registrar/pdf/pdf_schoolform10_seniorbct';
                    // }
                    // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                    // {
                    //     $template = 'registrar/pdf/pdf_schoolform10_seniorsjaes';
                    // }
                    // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                    // {
                    //     $template = 'registrar/pdf/pdf_schoolform10_seniormcs';
                    // }
                    // return $records;
                    
                    // return $records[1][1]->grades;
                    // return $records[1][1]->attendance;;
                    // return collect($studinfo);
                    // return $gradelevels;
                    // return $gradelevels;
                        $pdf = PDF::loadview('registrar/forms/form10v2/pdf/pdf_senior',compact('eligibility','studinfo','gradelevels','footer','subjects','format','eachsemsignatories')); 
                        return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                }
                elseif($request->get('acadprogid') == 4)
                {
                    
                    // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs')
                    // {
                    //     $pdf = PDF::loadview('registrar/forms/form10v2/pdf/pdf_schoolform10_juniorlhs',compact('eligibility','studinfo','gradelevels','maxgradecount','footer','subjects','format')); 
                    //     return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                    // }
                    // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                    // {
                    //     // return $records
                    //     $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_juniorsjaes',compact('eligibility','studinfo','records','maxgradecount','footer','format','acadprogid')); 
                    //     return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                    // }
                    // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
                    // {
                    //     // return collect($footer);
                    //     $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_juniorxai',compact('eligibility','studinfo','records','maxgradecount','footer','format','acadprogid')); 
                    //     return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
        
                    // }else{
                        // return $gradelevels;
                        $pdf = PDF::loadview('registrar/forms/form10v2/pdf/pdf_junior',compact('eligibility','studinfo','gradelevels','footer','subjects','format','schoolinfo','allgradelevels')); 
                        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
                        return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                    // }
                }
    
            }
        }

    }
    public function addnewrecord(Request $request)
    {
        // return $request->all();
        $studentid = $request->get('studentid');
        // return $studentid;
        // if($request->ajax()){
        $gradelevels = DB::table('gradelevel')
            ->select(
                'gradelevel.id',
                'gradelevel.levelname',
                'gradelevel.sortid'
            )
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('academicprogram.id',$request->get('acadprogid'))
            ->where('gradelevel.deleted','0')
            ->get();

        if($request->get('acadprogid') == 3)
        {
            $sectionid = 0;
            $adviserid = 0;

            if($request->has('sectionid'))
            {
                $sectionid = $request->get('sectionid');
            }
            if(Session::get('currentPortal') == '1')
            {
                $adviserid = DB::table('teacher')->where('userid', auth()->user()->id)->first()->id;
            }

            $syid = DB::table('sy')
                ->where('sydesc',$request->get('schoolyear'))
                ->first();

            if($syid)
            {
                $syid = $syid->id;
            }else{
                $syid =0;
            }
            $sydesc = $request->get('schoolyear');

            // $subjects = DB::table('classsched')
            //     ->select('subjects.id','subjects.subjdesc','inSF9','inMAPEH','inTLE','subj_sortid')
            //     ->join('subjects','classsched.subjid','=','subjects.id')
            //     ->where('classsched.glevelid', $request->get('levelid'))
            //     // ->where('classsched.sectionid', $sectionid)
            //     ->where('classsched.syid', $syid)
            //     ->where('classsched.deleted', 0)
            //     ->orderBy('subj_sortid','asc')
            //     ->get();
            $subjects = DB::table('subjects')
                ->select('subjects.id','subjects.subjdesc','inSF9','inMAPEH','inTLE','subj_sortid')
                // ->where('acadprogid', 3)
                ->where('inSF9', 1)
                // ->where('acadprogid', $request->get('levelid'))
                // ->where('classsched.sectionid', $sectionid)
                // ->where('classsched.syid', $syid)
                ->where('deleted', 0)
                ->orderBy('subj_sortid','asc')
                ->get();  
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    $subject->editable = '0';
                    // if($subject->q1 != null && $subject->q2 != null && $subject->q3 != null && $subject->q4 != null)
                    // {
                    //     $subject->final = number_format((($subject->q1+$subject->q2)/2));
                    //     if($subject->final>=75)
                    //     {
                    //         $subject->remarks = 'PASSED';
                    //     }else{
                    //         $subject->remarks = '';
                    //     }
                    //     $subject->editable = '0';
                    // }else{
                    //     $subject->final = null;
                    //     $subject->remarks = '';
                    //     $subject->editable = '1';
                    // }
                }
            }
            
            return view('registrar.forms.form10.addnewelem')
                ->with('schoolyear',$sydesc)
                ->with('levelid',$request->get('levelid'))
                ->with('gradelevels', $gradelevels)
                ->with('subjects',$subjects);
        }
        elseif($request->get('acadprogid') == 4)
        {
            
            $sectionid = 0;
            $adviserid = 0;
            if($request->has('sectionid'))
            {
                $sectionid = $request->get('sectionid');
            }
            if(Session::get('currentPortal') == '1')
            {
                $adviserid = DB::table('teacher')->where('userid', auth()->user()->id)->first()->id;
            }

            $syid = DB::table('sy')
                ->where('sydesc',$request->get('schoolyear'))
                ->first();

            if($syid)
            {
                $syid = $syid->id;
            }else{
                $syid =0;
            }
            $sydesc = $request->get('schoolyear');
            $subjects = DB::table('subjects')
                ->select('subjects.id','subjects.subjdesc','inSF9','inMAPEH','inTLE','subj_sortid')
                // ->where('acadprogid', 4)
                ->where('inSF9', 1)
                ->where('deleted', 0)
                // ->orderBy('subj_sortid','asc')
                ->get();  
                //   return $subjects;  
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    $subject->q1 = null;
                    $subject->q2 = null;
                    $subject->q3 = null;
                    $subject->q4 = null;
                    if($subject->q1 != null && $subject->q2 != null && $subject->q3 != null && $subject->q4 != null)
                    {
                        $subject->final = number_format((($subject->q1+$subject->q2)/2));
                        if($subject->final>=75)
                        {
                            $subject->remarks = 'PASSED';
                        }else{
                            $subject->remarks = '';
                        }
                        $subject->editable = '0';
                    }else{
                        $subject->final = null;
                        $subject->remarks = '';
                        $subject->editable = '1';
                    }
                }
            }
            $levelinfo = DB::table('gradelevel')
                ->where('id', $request->get('levelid'))
                ->first();
            return view('registrar.forms.form10v2.record_new')
                ->with('levelinfo',$levelinfo)
                ->with('schoolyear',$sydesc)
                ->with('levelid',$request->get('levelid'))
                ->with('gradelevels', $gradelevels)
                ->with('subjects',$subjects);
        }
        elseif($request->get('acadprogid') == 5)
        {
            $sectionid = 0;
            $adviserid = 0;
            if($request->has('sectionid'))
            {
                $sectionid = $request->get('sectionid');
            }
            if(Session::get('currentPortal') == '1')
            {
                $adviserid = DB::table('teacher')->where('userid', auth()->user()->id)->first()->id;
            }

            $syid = DB::table('sy')
                ->where('sydesc',$request->get('schoolyear'))
                ->first();

            if($syid)
            {
                $syid = $syid->id;
            }else{
                $syid =0;
            }
            $semid = $request->get('semid');
            $sydesc = $request->get('schoolyear');
            
            
            $studinfo = DB::table('studinfo')->where('id',$studentid)->first();

            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
            {
                // return collect($studinfo);

                if($sectionid == 19 && $semid == 1)
                {
                    $specific_subj = DB::table('sh_subjects')
                                        ->leftJoin('tempgradesum',function($join) use($studentid){
                                            $join->on('sh_subjects.id','=','tempgradesum.subjid');
                                            $join->where('tempgradesum.studid',$studentid);
                                        }) 
                                        ->where('sh_subjects.id',29)
                                        ->where('sh_subjects.inSF9',1)
                                        ->where('sh_subjects.semid',$semid)
                                        
                                        ->select(
                                            'sh_subjects.id',
                                            'sh_subjects.subjtitle as subjdesc',
                                            'sh_subjects.type',
                                            'sh_subjects.subjcode',
                                            'q1',
                                            'q2',
                                            'q3',
                                            'q4',
                                            'inSF9',
                                            'inMAPEH',
                                            'sh_subj_sortid as subj_sortid'
                                            )
                                        
                                        ->get();
                }elseif($sectionid == 19 && $semid == 2){
                    $specific_subj = DB::table('sh_subjects')
                                        ->leftJoin('tempgradesum',function($join) use($studentid){
                                            $join->on('sh_subjects.id','=','tempgradesum.subjid');
                                            $join->where('tempgradesum.studid',$studentid);
                                        }) 
                                        ->where('sh_subjects.id',36)
                                        ->where('sh_subjects.inSF9',1)
                                        ->where('sh_subjects.semid',$semid)
                                        
                                        ->select(
                                            'sh_subjects.id',
                                            'sh_subjects.subjtitle as subjdesc',
                                            'sh_subjects.type',
                                            'sh_subjects.subjcode',
                                            'q1',
                                            'q2',
                                            'q3',
                                            'q4',
                                            'inSF9',
                                            'inMAPEH',
                                            'sh_subj_sortid as subj_sortid'
                                            )
                                        
                                        ->get();
                }
                else{
                    $specific_subj = array();
                }
                $sh_subjects = DB::table('sh_subjects')
                                    ->join('sh_subjstrand',function($join) use($studinfo){
                                        $join->on('sh_subjects.id','=','sh_subjstrand.subjid');
                                        $join->where('sh_subjstrand.deleted',0);
                                        $join->where('sh_subjstrand.strandid',$studinfo->strandid);
                                    })
                                    ->leftJoin('tempgradesum',function($join) use($studentid){
                                        $join->on('sh_subjects.id','=','tempgradesum.subjid');
                                        $join->where('tempgradesum.studid',$studentid);
                                    }) 
                                    ->join('gradessetup',function($join) use($request){
                                        $join->on('sh_subjects.id','=','gradessetup.subjid');
                                        $join->where('gradessetup.deleted',0);
                                        $join ->where('gradessetup.levelid',$request->get('levelid'));
                                    })
                                    ->where('sh_subjects.inSF9',1)
                                    ->where('sh_subjects.semid',$semid)
                                    
                                    ->select(
                                        'sh_subjects.id',
                                        'sh_subjects.subjtitle as subjdesc',
                                        'sh_subjects.type',
                                        'sh_subjects.subjcode',
                                        'q1',
                                        'q2',
                                        'q3',
                                        'q4',
                                        'inSF9',
                                        'inMAPEH',
                                        'sh_subj_sortid as subj_sortid'
                                        )
                                    
                                    ->get();


                $core_subj = DB::table('sh_subjects')
                                            ->leftJoin('tempgradesum',function($join) use($studentid){
                                                $join->on('sh_subjects.id','=','tempgradesum.subjid');
                                                $join->where('tempgradesum.studid',$studentid);
                                            }) 
                                            ->join('gradessetup',function($join) use($request){
                                                $join->on('sh_subjects.id','=','gradessetup.subjid');
                                                $join->where('gradessetup.deleted',0);
                                                $join ->where('gradessetup.levelid',$request->get('levelid'));
                                            })
                                            ->where('sh_subjects.inSF9',1)
                                            ->where('sh_subjects.semid',$semid)
                                            ->where('type',1)
                                            ->select(
                                                'sh_subjects.id',
                                                'sh_subjects.subjtitle as subjdesc',
                                                'sh_subjects.type',
                                                'sh_subjects.subjcode',
                                                'q1',
                                                'q2',
                                                'q3',
                                                'q4',
                                                'inSF9',
                                                'inMAPEH',
                                                'sh_subj_sortid as subj_sortid'
                                                )
                                        
                                            ->get();
                $subjects = collect();
                $subjects = $subjects->merge($specific_subj);
                $subjects = $subjects->merge($sh_subjects);
                $subjects = $subjects->merge($core_subj);
                $subjects = $subjects->sortBy('subj_sortid');
                // $subjects = $subjects->merge($sh_blocksched);
                if(count($subjects)>0)
                {
                    foreach($subjects as $subject)
                    {
                        $subject->inTLE = 0;
                        if($subject->q1 != null && $subject->q2 != null)
                        {
                            $subject->final = number_format((($subject->q1+$subject->q2)/2));
                            if($subject->final>=75)
                            {
                                $subject->remarks = 'PASSED';
                            }else{
                                $subject->remarks = '';
                            }
                            $subject->editable = '0';
                        }else{
                            $subject->final = null;
                            $subject->remarks = '';
                            $subject->editable = '1';
                        }
                    }
                }

            }else{
                $subjects = array();
            }
            
            return view('registrar.forms.form10.addnewsenior')
                ->with('schoolyear',$sydesc)
                ->with('semid',$request->get('semid'))
                ->with('levelid',$request->get('levelid'))
                ->with('gradelevels', $gradelevels)
                ->with('subjects',$subjects);
        }
    }
    public function addnewheader(Request $request)
    {
        // return $request->all();
        $studentid              = $request->get('studentid');
        $acadprogid             = $request->get('acadprogid');

        $recordid               = $request->get('recordid');
        $schoolname             = $request->get('schoolname');
        $schoolid               = $request->get('schoolid');
        $schooldistrict         = $request->get('district');
        $schooldivision         = $request->get('division');
        $schoolregion           = $request->get('region');
        $levelid                = $request->get('levelid');
        $levelname              = $request->get('levelname');
        $sectionname            = $request->get('sectionname');
        $sydesc                 = $request->get('sydesc');
        $teachername            = $request->get('teachername');

        $remarks                = $request->get('remarks');
        $recordsincharge        = $request->get('recordsincharge');
        $datechecked            = $request->get('datechecked');
        $credit_advance         = $request->get('credit_advance');
        $credit_lacks           = $request->get('credit_lacks');
        $noofyears              = $request->get('noofyears');
        $generalaverageval      = $request->get('generalaverageval');
        
        if($acadprogid == 3 || $acadprogid == 4)
        {
            $checkifexists          = DB::table('sf10')
                                        ->where('studid',$studentid)
                                        ->where('sydesc',$sydesc)
                                        ->where('levelid',$levelid)
                                        ->where('deleted','0')
                                        ->first();

                                    // return collect($checkifexists);
            if($checkifexists)
            {
                $gethederid = $checkifexists->id;
                DB::table('sf10')
                ->where('id',$gethederid)
                ->update([
                    'syid'              =>  null,
                    'sydesc'            =>  $sydesc,
                    'yearfrom'          =>  null,
                    'yearto'            =>  null,
                    'levelid'           =>  $levelid,
                    'levelname'         =>  $levelname,
                    'sectionid'         =>  null,
                    'sectionname'       =>  $sectionname,
                    'teachername'       =>  $teachername,
                    'principalname'     =>  null,
                    'acadprogid'        =>  $acadprogid,
                    'schoolid'          =>  $schoolid,
                    'schoolname'        =>  $schoolname,
                    'schooladdress'     =>  null,
                    'schooldistrict'    =>  $schooldistrict,
                    'schooldivision'    =>  $schooldivision,
                    'schoolregion'      =>  $schoolregion,
                    'unitsearned'       =>  null,
                    'noofyears'         =>  null,
                    'remarks'           =>  $request->get('remarks'),
                    'recordincharge'    =>  $request->get('recordsincharge'),
                    'datechecked'       =>  $request->get('datechecked'),
                    'credit_advance'    =>  $credit_advance,
                    'credit_lack'       =>  $credit_lacks,
                    'noofyears'         =>  $noofyears,
                    'recordinputtype'   =>  1,
                    'createdby'         =>  auth()->user()->id,
                    'createddatetime'   =>  date('Y-m-d H:i:s')
                ]);
            }else{
                 DB::table('sf10')
                ->insert([
                    'studid'            =>  $studentid,
                    'syid'              =>  null,
                    'sydesc'            =>  $sydesc,
                    'yearfrom'          =>  null,
                    'yearto'            =>  null,
                    'levelid'           =>  $levelid,
                    'levelname'         =>  $levelname,
                    'sectionid'         =>  null,
                    'sectionname'       =>  $sectionname,
                    'teachername'       =>  $teachername,
                    'principalname'     =>  null,
                    'acadprogid'        =>  $acadprogid,
                    'schoolid'          =>  $schoolid,
                    'schoolname'        =>  $schoolname,
                    'schooladdress'     =>  null,
                    'schooldistrict'    =>  $schooldistrict,
                    'schooldivision'    =>  $schooldivision,
                    'schoolregion'      =>  $schoolregion,
                    'unitsearned'       =>  null,
                    'noofyears'         =>  null,
                    'remarks'           =>  $request->get('remarks'),
                    'recordincharge'    =>  $request->get('recordsincharge'),
                    'datechecked'       =>  $request->get('datechecked'),
                    'credit_advance'    =>  $credit_advance,
                    'credit_lack'       =>  $credit_lacks,
                    'noofyears'         =>  $noofyears,
                    'recordinputtype'   => 1,
                    'createdby'         =>  auth()->user()->id,
                    'createddatetime'   =>  date('Y-m-d H:i:s')
                ]);
            }
            return 1;

            
        }elseif($acadprogid == 5)
        {
            $schoolname             = $request->get('schoolname');
            $schoolid               = $request->get('schoolid');
            $gradelevelid           = $request->get('levelid');
            $trackname              = $request->get('trackname');
            $strandname             = $request->get('strandname');
            $sectionname            = $request->get('sectionname');
            $schoolyear             = $request->get('sydesc');
            $semester               = $request->get('semid');
            $teachername            = $request->get('preparedby');
            $recordsincharge        = $request->get('certifiedby');

            $generalaverageval      = $request->get('generalaverageval');
            $generalaveragerem      = $request->get('generalaveragerem');
            $semesterremarks        = $request->get('semremarks');
            $datechecked            = $request->get('datechecked');

            $strandname            = $request->get('strandname');
            $trackname            = $request->get('trackname');
            
            $checkifexists          = DB::table('sf10')
                                        ->where('studid',$studentid)
                                        // ->where('sydesc',$schoolyear)
                                        ->where('levelid',$gradelevelid)
                                        ->where('semid',$semester)
                                        ->where('deleted','0')
                                        ->first();
            // return collect($checkifexists);
            if($checkifexists)
            {
                $gethederid = $checkifexists->id;
                DB::table('sf10')
                ->where('id',$gethederid)
                ->update([
                    'syid'              =>  null,
                    'sydesc'            =>  $schoolyear,
                    'yearfrom'          =>  null,
                    'yearto'            =>  null,
                    'semid'             =>  $semester,
                    'levelid'           =>  $gradelevelid,
                    'levelname'         =>  null,
                    'sectionid'         =>  null,
                    'sectionname'       =>  $sectionname,
                    'trackid'           =>  null,
                    'trackname'         =>  $trackname,
                    'strandid'          =>  null,
                    'strandname'        =>  $strandname,
                    'teachername'       =>  $teachername,
                    'principalname'     =>  null,
                    'acadprogid'        =>  $acadprogid,
                    'schoolid'          =>  $schoolid,
                    'schoolname'        =>  $schoolname,
                    'schooladdress'     =>  null,
                    'unitsearned'       =>  null,
                    'noofyears'         =>  null,
                    'remarks'           =>  $semesterremarks,
                    'recordincharge'    =>  $recordsincharge,
                    'datechecked'       =>  $datechecked,
                    'recordinputtype'   =>  1,
                    'createdby'         =>  auth()->user()->id,
                    'createddatetime'   =>  date('Y-m-d H:i:s')
                ]);
            }else{
                $gethederid             = DB::table('sf10')
                                            ->insertGetId([
                                                'studid'            =>  $studentid,
                                                'syid'              =>  null,
                                                'sydesc'            =>  $schoolyear,
                                                'yearfrom'          =>  null,
                                                'yearto'            =>  null,
                                                'semid'             =>  $semester,
                                                'levelid'           =>  $gradelevelid,
                                                'levelname'         =>  null,
                                                'sectionid'         =>  null,
                                                'sectionname'       =>  $sectionname,
                                                'trackid'           =>  null,
                                                'trackname'         =>  $trackname,
                                                'strandid'          =>  null,
                                                'strandname'        =>  $strandname,
                                                'teachername'       =>  $teachername,
                                                'principalname'     =>  null,
                                                'acadprogid'        =>  $acadprogid,
                                                'schoolid'          =>  $schoolid,
                                                'schoolname'        =>  $schoolname,
                                                'schooladdress'     =>  null,
                                                'unitsearned'       =>  null,
                                                'noofyears'         =>  null,
                                                'remarks'           =>  $semesterremarks,
                                                'recordincharge'    =>  $recordsincharge,
                                                'datechecked'       =>  $datechecked,
                                                'recordinputtype'   =>  1,
                                                'createdby'         =>  auth()->user()->id,
                                                'createddatetime'   =>  date('Y-m-d H:i:s')
                                            ]);
            }
            return 1;
        }
    }
    public function updategrades(Request $request)
    {
        // return $request->all();
        $studentid              = $request->get('studentid');
        $acadprogid             = $request->get('acadprogid');

        $recordid               = $request->get('id');
        $subjects               = json_decode($request->get('subjects'));
        
        if($acadprogid == 3)
        {
            $tablename = 'sf10grades_elem';
        }
        elseif($acadprogid == 4)
        {
            $tablename = 'sf10grades_junior';
        }
        elseif($acadprogid == 5)
        {
            $tablename = 'sf10grades_senior';
        }
        if($acadprogid == 3 || $acadprogid == 4)
        {

            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                        if(!$request->has('intle'))
                        {
                            $subject->intle = 0;
                        }
                        if(!$request->has('inmapeh'))
                        {
                            $subject->inmapeh = 0;
                        }
                        
                        DB::table($tablename)
                            ->insert([
                                'headerid'          =>  $recordid,
                                'subjectid'         =>  null,
                                'subjectname'       =>  $subject->subjdesc,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'q3'                =>  $subject->q3,
                                'q4'                =>  $subject->q4,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                'credits'           =>  $subject->credits,
                                'fromsystem'        =>  $subject->fromsystem,
                                'editablegrades'    =>  $subject->editablegrades,
                                'inTLE'             =>  $subject->intle,
                                'inMAPEH'           =>  $subject->indentsubj,
                                'createdby'         =>  auth()->user()->id,
                                'createddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                }
                return 1;
            }else{                
                
                return 0;
            }
        }else{
            
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    $genave = 0;
                    if(strpos(strtolower($subject->subjdesc),'general ave') !== false)
                    // if (strtolower($cell->getValue()) == strtolower($searchValue)) 
                    {
                        $subject->subjdesc = 'General Average';
                        $genave = 1;
                    }
                        DB::table($tablename)
                            ->insert([
                                'headerid'          =>  $recordid,
                                'subjid'         =>  null,
                                'subjdesc'       =>  $subject->subjdesc,
                                'subjcode'       =>  $subject->subjcode,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                // 'credits'           =>  $subject->credits,
                                'isgenave'           =>  $genave,
                                'fromsystem'        =>  $subject->fromsystem,
                                'editablegrades'    =>  $subject->editablegrades,
                                'createdby'         =>  auth()->user()->id,
                                'createddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                }
                return 1;
            }else{                
                
                return 0;
            }
        }
    }
    public function updatesubject(Request $request)
    {
        $studentid              = $request->get('studentid');
        $acadprogid             = $request->get('acadprogid');
        if($acadprogid == 3)
        {
            $tablename = 'sf10grades_elem';
        }
        elseif($acadprogid == 4)
        {
            $tablename = 'sf10grades_junior';
        }
        elseif($acadprogid == 5)
        {
            $tablename = 'sf10grades_senior';
        }
        if($acadprogid == 5)
        {
            DB::table($tablename)
                ->where('id', $request->get('id'))
                ->update([
                    'subjcode'       =>  $request->get('subjcode'),
                    'subjdesc'       =>  $request->get('subjdesc'),
                    'q1'                =>  $request->get('q1'),
                    'q2'                =>  $request->get('q2'),
                    'finalrating'       =>  $request->get('final'),
                    'remarks'           =>  $request->get('remarks'),
                    // 'credits'           =>  $request->get('credits'),
                    'fromsystem'        =>  $request->get('fromsystem'),
                    'editablegrades'    =>  $request->get('editablegrades'),
                    // 'inTLE'             =>  $request->get('intle'),
                    'inMAPEH'           =>  $request->get('indentsubj'),
                    'updatedby'         =>  auth()->user()->id,
                    'updateddatetime'   =>  date('Y-m-d H:i:s')
                ]);
        }else{
            DB::table($tablename)
                ->where('id', $request->get('id'))
                ->update([
                    'subjectname'       =>  $request->get('subjdesc'),
                    'q1'                =>  $request->get('q1'),
                    'q2'                =>  $request->get('q2'),
                    'q3'                =>  $request->get('q3'),
                    'q4'                =>  $request->get('q4'),
                    'finalrating'       =>  $request->get('final'),
                    'remarks'           =>  $request->get('remarks'),
                    'credits'           =>  $request->get('credits'),
                    'fromsystem'        =>  $request->get('fromsystem'),
                    'editablegrades'    =>  $request->get('editablegrades'),
                    'inTLE'             =>  $request->get('intle'),
                    'inMAPEH'           =>  $request->get('indentsubj'),
                    'updatedby'         =>  auth()->user()->id,
                    'updateddatetime'   =>  date('Y-m-d H:i:s')
                ]);
        }

        return 1;
    }
    public function deletesubject(Request $request)
    {
        $studentid              = $request->get('studentid');
        $acadprogid             = $request->get('acadprogid');
        if($acadprogid == 3)
        {
            $tablename = 'sf10grades_elem';
        }
        elseif($acadprogid == 4)
        {
            $tablename = 'sf10grades_junior';
        }
        elseif($acadprogid == 5)
        {
            $tablename = 'sf10grades_senior';
        }
        DB::table($tablename)
            ->where('id', $request->get('id'))
            ->update([
                'deleted'           => 1,
                'deletedby'         =>  auth()->user()->id,
                'deleteddatetime'   =>  date('Y-m-d H:i:s')
            ]);

        return 1;
    }
    public function gettemplate(Request $request)
    {
        $collectgradelevels = DB::table('gradelevel')
            ->where('acadprogid', $request->get('acadprogid'))
            ->where('deleted','0')
            ->get();

        $gradelevels = array();
        if($request->get('acadprogid') == 5)
        {
            if(count($collectgradelevels)>0)
            {
                foreach($collectgradelevels as $gradelevel)
                {
                    for($x = 1; $x <=2; $x++)
                    {
                        $subjects = DB::table('sf10_templatesetup')
                            ->where('acadprogid', $request->get('acadprogid'))
                            ->where('levelid', $gradelevel->id)
                            ->where('semid',  $x)
                            ->where('isSubject','1')
                            ->where('deleted','0')
                            ->get();

                        $gradelevel->semid = $x;
                            array_push($gradelevels, (object)array(
                                'id'            => $gradelevel->id,
                                'levelname'            => $gradelevel->levelname,
                                'sortid'            => $gradelevel->sortid,
                                'semid'            => $x,
                                'subjects'            => $subjects,
                                'acadprogid'            => $gradelevel->acadprogid,
                                'deleted'            => $gradelevel->deleted
                            )); 
                    }
                }
            }
            return view('registrar.forms.form10v2.templates_shs')
                ->with('gradelevels', $gradelevels); 
        }else{
            
            if(count($collectgradelevels)>0)
            {
                foreach($collectgradelevels as $gradelevel)
                {
                    $gradelevel->subjects = DB::table('sf10_templatesetup')
                        ->where('acadprogid', $request->get('acadprogid'))
                        ->where('levelid', $gradelevel->id)
                        ->where('isSubject','1')
                        ->where('deleted','0')
                        ->get();
    
                    $gradelevel->headers = DB::table('sf10_templatesetup')
                        ->where('acadprogid', $request->get('acadprogid'))
                        ->where('levelid', $gradelevel->id)
                        ->where('isSubject','0')
                        ->where('deleted','0')
                        ->get();
                }
            }
            return view('registrar.forms.form10v2.templates_jhs')
                ->with('gradelevels', $collectgradelevels); 
        }
    }
    public function template(Request $request)
    {
        // return $request->all();
        if($request->get('action') == 'update')
        {
            $templateid = $request->get('templateid');
            $acadprogid = $request->get('acadprogid');
            $levelid = $request->get('levelid');
            $semid = $request->get('semid');
            $rowstart = $request->get('rowstart');
            $rowend = $request->get('rowend');
            $cols = json_decode($request->get('cols'));
            $sheetnum = $request->get('sheetnum');

            $headerrowstart = $request->get('headerrowstart');
            $headerrowend = $request->get('headerrowend');
            $headercolumn = $request->get('headercolumn');

            // $col1   = null;
            // $col2   = null;
            // $col3   = null;
            // $col4   = null;
            // $col5   = null;
            // $col6   = null;
            // $col7   = null;
            // $col8   = null;
            // $col9   = null;
            // $col10  = null;
            // $col11  = null;
            // $col12  = null;
            // $col13  = null;
            // $col14  = null;
            // $col15  = null;
            
            $checkifexistsheader = DB::table('sf10_templatesetup')
                ->where('acadprogid', $acadprogid)
                ->where('levelid', $levelid)
                ->where('deleted','0')
                ->where('semid',$semid)
                ->where('isSubject','0')
                ->first();

            if($checkifexistsheader)
            {
                DB::table('sf10_templatesetup')
                    ->where('id', $checkifexistsheader->id)
                    ->update([
                        'rowstart'                => $headerrowstart,
                        'rowend'                => $headerrowend,
                        'col1'                => $headercolumn,
                        'sheetnum'                => $sheetnum,
                        'isSubject'                => 0,
                        'semid'                => $semid,
                        'updatedby'                => auth()->user()->id,
                        'updateddatetime'                => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10_templatesetup')
                    ->insert([
                        'templateid'                => $templateid,
                        'acadprogid'                => $acadprogid,
                        'levelid'                => $levelid,
                        'rowstart'                => $headerrowstart,
                        'rowend'                => $headerrowend,
                        'col1'                => $headercolumn,
                        'sheetnum'                => $sheetnum,
                        'semid'                => $semid,
                        'isSubject'                => 0,
                        'createdby'                => auth()->user()->id,
                        'createddatetime'                => date('Y-m-d H:i:s')
                    ]);
            }




            $checkifexists = DB::table('sf10_templatesetup')
                ->where('acadprogid', $acadprogid)
                ->where('levelid', $levelid)
                ->where('semid',$semid)
                ->where('deleted','0')
                ->where('isSubject','1')
                ->first();

            if($checkifexists)
            {
                DB::table('sf10_templatesetup')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'rowstart'                => $rowstart,
                        'rowend'                => $rowend,
                        'col1'                => isset($cols[0]) ? $cols[0] : null,
                        'col2'                => isset($cols[1]) ? $cols[1] : null,
                        'col3'                => isset($cols[2]) ? $cols[2] : null,
                        'col4'                => isset($cols[3]) ? $cols[3] : null,
                        'col5'                => isset($cols[4]) ? $cols[4] : null,
                        'col6'                => isset($cols[5]) ? $cols[5] : null,
                        'col7'                => isset($cols[6]) ? $cols[6] : null,
                        'col8'                => isset($cols[7]) ? $cols[7] : null,
                        'col9'                => isset($cols[8]) ? $cols[8] : null,
                        'col10'                => isset($cols[9]) ? $cols[9] : null,
                        'col11'                => isset($cols[10]) ? $cols[10] : null,
                        'col12'                => isset($cols[11]) ? $cols[11] : null,
                        'col13'                => isset($cols[12]) ? $cols[12] : null,
                        'col14'                => isset($cols[13]) ? $cols[13] : null,
                        'col15'                => isset($cols[14]) ? $cols[14] : null,
                        'sheetnum'                => $sheetnum,
                        'semid'                => $semid,
                        'isSubject'                => 1,
                        'updatedby'                => auth()->user()->id,
                        'updateddatetime'                => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10_templatesetup')
                    ->insert([
                        'templateid'                => $templateid,
                        'acadprogid'                => $acadprogid,
                        'levelid'                => $levelid,
                        'rowstart'                => $rowstart,
                        'rowend'                => $rowend,
                        'col1'                => isset($cols[0]) ? $cols[0] : null,
                        'col2'                => isset($cols[1]) ? $cols[1] : null,
                        'col3'                => isset($cols[2]) ? $cols[2] : null,
                        'col4'                => isset($cols[3]) ? $cols[3] : null,
                        'col5'                => isset($cols[4]) ? $cols[4] : null,
                        'col6'                => isset($cols[5]) ? $cols[5] : null,
                        'col7'                => isset($cols[6]) ? $cols[6] : null,
                        'col8'                => isset($cols[7]) ? $cols[7] : null,
                        'col9'                => isset($cols[8]) ? $cols[8] : null,
                        'col10'                => isset($cols[9]) ? $cols[9] : null,
                        'col11'                => isset($cols[10]) ? $cols[10] : null,
                        'col12'                => isset($cols[11]) ? $cols[11] : null,
                        'col13'                => isset($cols[12]) ? $cols[12] : null,
                        'col14'                => isset($cols[13]) ? $cols[13] : null,
                        'col15'                => isset($cols[14]) ? $cols[14] : null,
                        'sheetnum'                => $sheetnum,
                        'semid'                => $semid,
                        'isSubject'                => 1,
                        'createdby'                => auth()->user()->id,
                        'createddatetime'                => date('Y-m-d H:i:s')
                    ]);
            }
            return 1;
                
        }
        // return $request->all();
    }
    public function fileupload(Request $request)
    {
        // return $request->all();
        $path = $request->file('excelfile')->getRealPath();
            
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($path);


        function get_string_between($string, $start, $end){
            $string = ' ' . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return '';
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }
        if($request->get('acadprogid') == '5')
        {
            $fronttablecount = 0;
            $foundInCells = array();
            $searchValue = array('school:','school: ');
            $searchValuegenave = 'general ave';
            $generalaverages = array();
            $remarksarray = array();
            
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $ws = $worksheet->getTitle();
                
                foreach ($worksheet->getRowIterator() as $row) {
                    try{
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(true);
                    foreach ($cellIterator as $cell) {
                        if(strlen($cell->getCoordinate()) <= 3)
                        {
                            if(in_array(strtolower($cell->getValue()),$searchValue))
                            // if (strtolower($cell->getValue()) == strtolower($searchValue)) 
                            {
                                $foundInCells[] = $ws . '!' . $cell->getCoordinate();
                            }
                        }
                        if(strpos(strtolower($cell->getValue()),$searchValuegenave) !== false)
                        // if (strtolower($cell->getValue()) == strtolower($searchValue)) 
                        {
                            $generalaverages[] =  $cell->getCoordinate();
                        }
                        if(strpos(strtolower($cell->getValue()),'remarks') !== false)
                        // if (strtolower($cell->getValue()) == strtolower($searchValue)) 
                        {
                            $remarksarray[] =  $cell->getCoordinate();
                        }
                    }
                    }catch(\Exception $error)
                    {
                        
                    }
                }
            }
            
            $startandends = array();
            
            if(count($foundInCells)>0)
            {
                foreach($foundInCells as $cellkey => $foundInCell)
                {

                    if(isset($foundInCells[$cellkey+1]))
                    {
                        $startrowcount = (int) filter_var($foundInCell, FILTER_SANITIZE_NUMBER_INT)-1;
                        $endrowcount = (int) filter_var($foundInCells[$cellkey+1], FILTER_SANITIZE_NUMBER_INT)-1;
                        // return substr($foundInCell, strpos($foundInCell, "!") + 1);
                        $endcell =  substr(preg_replace('/[0-9]+/', $endrowcount, $foundInCells[$cellkey+1]),  strpos(preg_replace('/[0-9]+/', $endrowcount, $foundInCells[$cellkey+1]), "!") + 1);
                        // if()

                        if($endrowcount<$startrowcount)
                        {
                            $endrowcount = $startrowcount+20;
                        }
                        array_push($startandends, (object)array(
                            'sheet'     => strtok($foundInCell, '!'),
                            'start'     => substr($foundInCell, strpos($foundInCell, "!") + 1),
                            'end'       =>  substr(preg_replace('/[0-9]+/', $endrowcount, $foundInCells[$cellkey+1]),  strpos(preg_replace('/[0-9]+/', $endrowcount, $foundInCells[$cellkey+1]), "!") + 1)
                        ));
                    }else{
                        array_push($startandends, (object)array(
                            'sheet'     => strtok($foundInCell, '!'),
                            'start'     => substr($foundInCell, strpos($foundInCell, "!") + 1),
                            'end'       => preg_replace('/[^a-z]/i', '', substr($foundInCell, strpos($foundInCell, "!") + 1)).(preg_replace('/[^0-9]/', "", substr($foundInCell, strpos($foundInCell, "!") + 1))+20)
                        ));
                    }
                }
            }
            // $headers = array();
            $startandends = collect($startandends)->groupBy('sheet');
            $records = array();
            // return $startandends;
            foreach($startandends as $eachkey => $startandend)
            {
                $worksheet = $spreadsheet->getSheetByName($eachkey);
                $highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'
                // return $highestColumn;
                $data = $worksheet->toArray();
                $eachsetupsubjects = array();
                foreach($startandend as $key=>$eachrow)
                {
                    if(strtolower($eachkey) == 'front')
                    {
                        $levelid   = 14;
                        $levelname = 'Grade 11';
                    }
                    if(strtolower($eachkey) == 'back')
                    {
                        $levelid   = 15;
                        $levelname = 'Grade 12';
                    }
                    if($key == 0)
                    {
                        if(strtolower($eachkey) == 'front')
                        {
                            $allkeycount = 0;

                        }else{
                            $allkeycount = 2;

                        }
                        $semid   = 1;
                    }
                    elseif($key == 1)
                    {
                        if(strtolower($eachkey) == 'front')
                        {
                            $allkeycount = 1;

                        }else{
                            $allkeycount = 3;

                        }
                        $semid   = 2;
                    }
                    $subjects = array();
                    $header1 = '';
                    $header2 = '';
                    $school = null;
                    $schoolid = null;
                    $gradelevel = null;
                    $sy = null;
                    $sem = null;
                    $trackandstrand = null;
                    $section = null;

                    $rangeheader1 = 'A'.(int) filter_var($eachrow->start, FILTER_SANITIZE_NUMBER_INT).':BO'.(int) filter_var($eachrow->start, FILTER_SANITIZE_NUMBER_INT);
                    
                    $dateheader1 = $worksheet->rangeToArray( $rangeheader1, 
                                                            NULL, 
                                                            TRUE,
                                                            TRUE,
                                                            TRUE);
                                                            
                    foreach(collect($dateheader1)->flatten() as $eachrowdata)
                    {
                        if(!is_null($eachrowdata))
                        {
                            $header1.=$eachrowdata.' ';
                        }
                    }

                    $school = get_string_between($header1, 'SCHOOL:', 'SCHOOL ID');
                    $schoolid = get_string_between($header1, 'SCHOOL ID:', 'GRADE LEVEL');
                    $gradelevel = get_string_between($header1, 'GRADE LEVEL:', 'SY');
                    $sy = get_string_between($header1, 'SY:', 'SEM');
                    $sem = substr($header1, strpos($header1, "SEM:") + 4);

                    if($levelid == 14)
                    {
                        $rownumber = (int) filter_var($eachrow->start, FILTER_SANITIZE_NUMBER_INT) + 2;
                    }else{
                        if($semid == 1)
                        {
                            $rownumber = (int) filter_var($eachrow->start, FILTER_SANITIZE_NUMBER_INT) + 1;
                        }else{
                            $rownumber = (int) filter_var($eachrow->start, FILTER_SANITIZE_NUMBER_INT) + 2;
                        }
                    }

                    $rangeheader2 = 'A'.$rownumber.':BO'.$rownumber;
                    
                    $dateheader2 = $worksheet->rangeToArray( $rangeheader2, 
                                                            NULL, 
                                                            TRUE,
                                                            TRUE,
                                                            TRUE);
                                                         
                                                            
                    foreach(collect($dateheader2)->flatten() as $eachrowdata)
                    {
                        if(!is_null($eachrowdata))
                        {
                            $header2.=$eachrowdata.' ';
                        }
                    }
                    $trackandstrand = get_string_between($header2, 'TRACK/STRAND:', 'SECTION');

                    $section = substr($header2, strpos($header2, "SECTION:") + 8);
                    // return $header2;
                    $getsemid = 0;
                    if(strpos(strtolower($sem), strtolower('1st')) !== false || strpos(strtolower($sem), 'first') !== false){
                        $getsemid = 1;
                    }
                    if(strpos(strtolower($sem), '2nd') !== false || strpos(strtolower($sem), 'second') !== false){
                        $getsemid = 2;
                    }
                    $getsubjects = 'A'.(((int) filter_var($eachrow->start, FILTER_SANITIZE_NUMBER_INT))+8).':BO'.(((int) filter_var($eachrow->end, FILTER_SANITIZE_NUMBER_INT)));
                    $collectsubjects = $worksheet->rangeToArray( $getsubjects, 
                                                            NULL, 
                                                            TRUE,
                                                            TRUE,
                                                            TRUE);


                                    
                    $eachsubjectrow = collect();   
                    $keycount = 1;
                    foreach(collect($collectsubjects)->flatten() as $keysubj=> $eachrowsubject)
                    {          
                        if(!is_null($eachrowsubject))
                        {
                            $eachsubjectrow = $eachsubjectrow->merge($eachrowsubject);
                            $keycount+=1;
                        }
                        if($keycount == 7)
                        {     
                            array_push($subjects, $eachsubjectrow);
                            $eachsubjectrow = collect();
                            $keycount = 1;
                        }
                    }
                    $getgeneralaverage = 'A'.(((int) filter_var($generalaverages[$allkeycount], FILTER_SANITIZE_NUMBER_INT))).':BO'.(((int) filter_var($generalaverages[$allkeycount], FILTER_SANITIZE_NUMBER_INT)));
                    $genave = collect();
                    $genavedetails = $worksheet->rangeToArray( $getgeneralaverage, 
                                                            NULL, 
                                                            TRUE,
                                                            TRUE,
                                                            TRUE);
                    foreach(collect($genavedetails)->flatten() as $keygenave=> $eachcellforave)
                    {          
                        if(!is_null($eachcellforave))
                        {
                            $genave = $genave->merge($eachcellforave);
                        }
                    }
                    // return 
                    $getremarksvaluse = 'A'.(((int) filter_var($remarksarray[$allkeycount], FILTER_SANITIZE_NUMBER_INT))).':BO'.(((int) filter_var($remarksarray[$allkeycount], FILTER_SANITIZE_NUMBER_INT)));

                    $eachremarks = '';
                    $eachremarksdetail = $worksheet->rangeToArray( $getremarksvaluse, 
                                                            NULL, 
                                                            TRUE,
                                                            TRUE,
                                                            TRUE);
                    foreach(collect($eachremarksdetail)->flatten() as $keyremarks=> $eachcellforremarks)
                    {          
                        if(!is_null($eachcellforremarks))
                        {
                            $eachremarks .= $eachcellforremarks;
                        }
                    }
                    array_push($records, (object)array(
                        'start'              => $eachrow->start,
                        'end'              => $eachrow->end,
                        'levelid'              => $levelid,
                        'levelname'              => $levelname,
                        'semid'              => $semid,
                        'school'              => $school,
                        'schoolid'              => $schoolid,
                        'gradelevel'              => $gradelevel,
                        'sy'              => $sy,
                        'sem'              => $sem,
                        'getsemid'              => $getsemid,
                        'trackandstrand'              => $trackandstrand,
                        'section'              => $section,
                        'subjects'      => $subjects,
                        'genave'      => $genave,
                        'remarks'      => str_replace('remarks', '', strtolower($eachremarks))
                    ));
                }
            }
            return view('registrar.forms.form10v2.viewuploaded_shs')
                ->with('records', $records);            
            
        }else{

            $headersetups = DB::table('sf10_templatesetup')
                ->where('acadprogid','4')
                ->where('deleted','0')
                ->where('isSubject','0')
                ->get();
    
            
            $headers = array();
    
            if(count($headersetups)>0)
            {
                foreach($headersetups as $headersetup)
                {
                    $worksheet = $spreadsheet->setActiveSheetIndex(($headersetup->sheetnum-1));
                    $data = $worksheet->toArray();
                    $levelid = $headersetup->levelid;
    
    
                    $column1 = null;
                    $column2 = null;
                    $column3 = null;
                    $column4 = null;
                    $column5 = null;
                    $column6 = null;
                    $column7 = null;
                    $column8 = null;
                    $column9 = null;
                    $column10 = null;
    
                    for($x = ($headersetup->rowstart-1); $x<$headersetup->rowend; $x++)
                    {
                        $datarow = $data[$x];
    
                        // return $datarow;
                        $col1num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($headersetup->col1));
                        $col1    = $datarow[$col1num-1];
                        
                        try{
                            $col8num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($headersetup->col8));
                            $col8    = $datarow[$col8num-1];
                        }catch(\Exception $error){}
                        try{
                            $col9num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($headersetup->col9));
                            $col9    = $datarow[$col9num-1];
                        }catch(\Exception $error){}
                        try{
                            $col10num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($headersetup->col10));
                            $col10    = $datarow[$col10num-1];
                        }catch(\Exception $error){}      
                        
                        if($x == ($headersetup->rowstart-1))
                        {
                            $column1 = get_string_between($col1, 'School:', 'School ID');
                            $column2 = get_string_between($col1, 'School ID:', 'District');
                            $column3 = get_string_between($col1, 'District:', 'Division');
                            $column4 = get_string_between($col1, 'Division:', 'Region');
                            $regiontext = 'Region:';
                            $index = strpos($col1, $regiontext) + strlen($regiontext);
                            $result = substr($col1, $index);
                            $column5 = $result;
                        }else{
                            $column6 = get_string_between($col1, 'Section:', 'School Year');
                            $column7 = get_string_between($col1, 'School Year:', 'Name of Adviser/Teacher');
                            $column8 = get_string_between($col1, 'Name of Adviser/Teacher:', 'Signature');
                        }
                        
                    }
                    array_push($headers, (object)array(
                        'levelid'  => $levelid,
                        'display'  => 0,
                        'col1'  => $column1,
                        'col2'  => $column2,
                        'col3'  => $column3,
                        'col4'  => $column4,
                        'col5'  => $column5,
                        'col6'  => $column6,
                        'col7'  => $column7,
                        'col8'  => $column8,
                        'col9'  => $column9,
                        'col10'  => $column10
                    ));
                }
            }
            $subjectsetups = DB::table('sf10_templatesetup')
                ->where('acadprogid','4')
                ->where('deleted','0')
                ->where('isSubject','1')
                ->get();
    
            if(count($subjectsetups)>0)
            {
                foreach($subjectsetups as $setup)
                {
                    $worksheet = $spreadsheet->setActiveSheetIndex(($setup->sheetnum-1));
                    $data = $worksheet->toArray();
                    $levelid = $setup->levelid;
    
                    // if($setup->levelid == 12)
                    // {
                    //     return $data;
                    // }
                    $subjects = array();
    
                    for($x = ($setup->rowstart-1); $x<$setup->rowend; $x++)
                    {
                        $datarow = $data[$x];
                        $col1 = null;
                        $col2 = null;
                        $col3 = null;
                        $col4 = null;
                        $col5 = null;
                        $col6 = null;
                        $col7 = null;
                        $col8 = null;
                        $col9 = null;
                        $col10 = null;
    
                        // if($setup->levelid == 12)
                        // {
                        //     return $datarow;
                        // }
                        $col1num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($setup->col1));
                        $col1    = $datarow[$col1num-1];
                        $col2num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($setup->col2));
                        $col2    = $datarow[$col2num-1];
                        $col3num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($setup->col3));
                        $col3    = $datarow[$col3num-1];
                        $col4num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($setup->col4));
                        $col4    = $datarow[$col4num-1];
                        $col5num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($setup->col5));
                        $col5    = $datarow[$col5num-1];
                        $col6num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($setup->col6));
                        $col6    = $datarow[$col6num-1];
    
                        if($col6 > 0)
                        {
                            if($col2>0 && $col3>0 && $col4>0 && $col5>0)
                            {
                                $col6    = number_format(($col2+$col3+$col4+$col5)/4);
                            }
                        }
                        $col7num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($setup->col7));
                        $col7    = $datarow[$col7num-1];
                        
                        try{
                            $col8num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($setup->col8));
                            $col8    = $datarow[$col8num-1];
                        }catch(\Exception $error){}
                        try{
                            $col9num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($setup->col9));
                            $col9    = $datarow[$col9num-1];
                        }catch(\Exception $error){}
                        try{
                            $col10num = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(($setup->col10));
                            $col10    = $datarow[$col10num-1];
                        }catch(\Exception $error){}      
                        
                        array_push($subjects, (object)array(
                            'col1'  => $col1,
                            'col2'  => $col2,
                            'col3'  => $col3,
                            'col4'  => $col4,
                            'col5'  => $col5,
                            'col6'  => $col6,
                            'col7'  => $col7,
                            'col8'  => $col8,
                            'col9'  => $col9,
                            'col10'  => $col10
                        ));
                    }
    
                    $setup->subjects = $subjects;
                }
            }
            return view('registrar.forms.form10v2.viewuploaded_jhs')
                ->with('headers',$headers)
                ->with('setups',$subjectsetups);
        }
        // return $headers;
        // return $data[0];
    }

    public function uploadrecord(Request $request)
    {
        // return $request->all();
        $studentid = $request->get('studentid');
        $semid = $request->get('semid');
        $acadprogid = $request->get('acadprogid');
        $levelid = $request->get('levelid');
        $schoolname = $request->get('schoolname');
        $schoolid = $request->get('schoolid');
        $schooldistrict = $request->get('district');
        $schooldivision = $request->get('division');
        $schoolregion = $request->get('region');
        $sectionname = $request->get('section');
        $schoolyear = $request->get('sydesc');
        $teachername = $request->get('teachername');
        $trackandstrand = $request->get('trackandstrand');

        $subjects = json_decode($request->get('subjects'));

        if($acadprogid == 3)
        {
            $tablename = 'sf10grades_elem';
        }
        elseif($acadprogid == 4)
        {
            $tablename = 'sf10grades_junior';
        }
        elseif($acadprogid == 5)
        {
            $tablename = 'sf10grades_senior';
        }

        if($acadprogid == 5)
        {
            $syid = 0;
            $getsyid = DB::table('sy')
                ->where('sydesc',$schoolyear)
                ->first();
            if($getsyid)
            {
                $syid = $getsyid->id;
            }

            $trackandstrand = explode('/', $trackandstrand);
            $trackname = $trackandstrand[0];
            $strandname = $trackandstrand[1] ?? '';
            $checkifexists          = DB::table('sf10')
                                        ->where('studid',$studentid)
                                        ->where('sydesc',$schoolyear)
                                        ->where('levelid',$levelid)
                                        ->where('semid',$semid)
                                        ->where('deleted','0')
                                        ->where('recordinputtype','2')
                                        ->first();

            
            if($checkifexists)
            {
                $gethederid = $checkifexists->id;
            }else{
                $gethederid             = DB::table('sf10')
                                            ->insertGetId([
                                                'studid'            =>  $studentid,
                                                'syid'              =>  $syid,
                                                'sydesc'            =>  $schoolyear,
                                                'semid'            =>  $semid,
                                                'yearfrom'          =>  null,
                                                'yearto'            =>  null,
                                                'levelid'           =>  $levelid,
                                                'levelname'         =>  null,
                                                'sectionid'         =>  null,
                                                'sectionname'       =>  $sectionname,
                                                'teachername'       =>  $teachername,
                                                'trackname'       =>  $trackname,
                                                'strandname'       =>  $strandname,
                                                'principalname'     =>  null,
                                                'acadprogid'        =>  $acadprogid,
                                                'schoolid'          =>  $schoolid,
                                                'schoolname'        =>  $schoolname,
                                                'schooladdress'     =>  null,
                                                'schooldistrict'    =>  $schooldistrict,
                                                'schooldivision'    =>  $schooldivision,
                                                'schoolregion'      =>  $schoolregion,
                                                'unitsearned'       =>  null,
                                                'noofyears'         =>  null,
                                                'remarks'           =>  $request->get('remarks'),
                                                'recordincharge'    =>  $request->get('recordsincharge'),
                                                'datechecked'       =>  $request->get('datechecked'),
                                                'createdby'         =>  auth()->user()->id,
                                                'createddatetime'   =>  date('Y-m-d H:i:s'),
                                                'recordinputtype'   => 2,
                                                'isactive'          => 1
                                            ]);
                if(count($subjects)>0)
                {
                    foreach($subjects as $subject)
                    {
                        $genave = 0;
                        if(strpos(strtolower($subject->subjectname),'general ave') !== false)
                        // if (strtolower($cell->getValue()) == strtolower($searchValue)) 
                        {
                            $subject->subjectname = 'General Average';
                            $genave = 1;
                        }
                            DB::table($tablename)
                                ->insert([
                                    'headerid'          =>  $gethederid,
                                    'subjcode'       =>  $subject->indicate,
                                    'subjdesc'       =>  $subject->subjectname,
                                    'q1'                =>  $subject->q1,
                                    'q2'                =>  $subject->q2,
                                    // 'q3'                =>  $subject->q3,
                                    // 'q4'                =>  $subject->q4,
                                    'finalrating'       =>  $subject->final,
                                    'remarks'           =>  $subject->remarks,
                                    // 'credits'           =>  $subject->credits,
                                    'editablegrades'    =>  1,
                                    'isgenave'    =>  $genave,
                                    'inputtype'           =>  1,
                                    'createdby'         =>  auth()->user()->id,
                                    'createddatetime'   =>  date('Y-m-d H:i:s')
                                ]);
                    }
                    return 1;
                }else{                
                    
                    return 0;
                }
            }
        }elseif($acadprogid == 3 || $acadprogid == 4){


            $credit_advance            = $request->get('credit_advance');
            $credit_lacks            = $request->get('credit_lacks');
            $noofyears            = $request->get('noofyears');
            
            $generalaverageval      = $request->get('generalaverageval');

            $checkifexists          = DB::table('sf10')
                                        ->where('studid',$studentid)
                                        ->where('sydesc',$schoolyear)
                                        ->where('levelid',$levelid)
                                        ->where('deleted','0')
                                        ->where('recordinputtype','2')
                                        ->first();
                                        
            if($checkifexists)
            {
                $gethederid = $checkifexists->id;
            }else{
                $gethederid             = DB::table('sf10')
                                            ->insertGetId([
                                                'studid'            =>  $studentid,
                                                'syid'              =>  null,
                                                'sydesc'            =>  $schoolyear,
                                                'yearfrom'          =>  null,
                                                'yearto'            =>  null,
                                                'levelid'           =>  $levelid,
                                                'levelname'         =>  null,
                                                'sectionid'         =>  null,
                                                'sectionname'       =>  $sectionname,
                                                'teachername'       =>  $teachername,
                                                'principalname'     =>  null,
                                                'acadprogid'        =>  $acadprogid,
                                                'schoolid'          =>  $schoolid,
                                                'schoolname'        =>  $schoolname,
                                                'schooladdress'     =>  null,
                                                'schooldistrict'    =>  $schooldistrict,
                                                'schooldivision'    =>  $schooldivision,
                                                'schoolregion'      =>  $schoolregion,
                                                'unitsearned'       =>  null,
                                                'noofyears'         =>  null,
                                                'remarks'           =>  $request->get('remarks'),
                                                'recordincharge'    =>  $request->get('recordsincharge'),
                                                'datechecked'       =>  $request->get('datechecked'),
                                                'credit_advance'    =>  $credit_advance,
                                                'credit_lack'       =>  $credit_lacks,
                                                'noofyears'         =>  $noofyears,
                                                'createdby'         =>  auth()->user()->id,
                                                'createddatetime'   =>  date('Y-m-d H:i:s'),
                                                'recordinputtype'   => 2,
                                                'isactive'          => 1
                                            ]);
            }
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    $checkifexists = DB::table($tablename)
                        ->where('headerid',$gethederid)
                        ->where('subjectname',$subject->subjectname)
                        ->where('deleted','0')
                        ->first();

                    if($checkifexists)
                    {
                        DB::table($tablename)
                            ->where('id', $checkifexists->id)
                            ->update([
                                'subjectid'         =>  null,
                                'subjectname'       =>  $subject->subjectname,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'q3'                =>  $subject->q3,
                                'q4'                =>  $subject->q4,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                // 'credits'           =>  $subject->credits,
                                'editablegrades'    =>  1,
                                'inMAPEH'           =>  $subject->indent,
                                'inputtype'           =>  1,
                                'updatedby'         =>  auth()->user()->id,
                                'updateddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                    }else{
                        DB::table($tablename)
                            ->insert([
                                'headerid'          =>  $gethederid,
                                'subjectid'         =>  null,
                                'subjectname'       =>  $subject->subjectname,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'q3'                =>  $subject->q3,
                                'q4'                =>  $subject->q4,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                // 'credits'           =>  $subject->credits,
                                'editablegrades'    =>  1,
                                'inMAPEH'           =>  $subject->indent,
                                'inputtype'           =>  1,
                                'createdby'         =>  auth()->user()->id,
                                'createddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                    }
                }
                return 1;
            }else{                
                
                return 0;
            }

            
        }
    }
    



    //v3
    
    public function eligibility_update(Request $request)
    {
        $studentid              = $request->get('studentid');
        $acadprogid             = $request->get('acadprogid');

        if($acadprogid == 3)
        {
            $kinderprogressreport   = $request->get('kinderprogressreport');
            $eccdchecklist          = $request->get('eccdchecklist');
            $kindergartencert       = $request->get('kindergartencert');
            $peptpasser             = $request->get('peptpasser');
            $schoolname             = $request->get('schoolname');
            $schoolid               = $request->get('schoolid');
            $schooladdress          = $request->get('schooladdress');
            $peptrating             = $request->get('peptrating');
            $examdate               = $request->get('examdate');
            $specify                = $request->get('specify');
            $centername             = $request->get('centername');
            $remarks                = $request->get('remarks');
            $checkifexists          = DB::table('sf10eligibility_elem')
                                        ->where('studid', $studentid)
                                        ->where('deleted','0')
                                        ->first();
    
            if($checkifexists)
            {
                DB::table('sf10eligibility_elem')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'kinderprogreport'          => $kinderprogressreport,
                        'eccdchecklist'             => $eccdchecklist,
                        'kindergartencert'          => $kindergartencert,
                        'schoolid'                  => $schoolid,
                        'schoolname'                => $schoolname,
                        'schooladdress'             => $schooladdress,
                        'pept'                      => $peptpasser,
                        'peptrating'                => $peptrating,
                        'examdate'                  => $examdate,
                        'centername'                => $centername,
                        // 'centeraddress'          => ,
                        'remarks'                   => $remarks,
                        'specifyothers'             => $specify,
                        'updatedby'                 => auth()->user()->id,
                        'updateddatetime'           => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10eligibility_elem')
                    ->insert([
                        'studid'                    => $studentid,
                        'kinderprogreport'          => $kinderprogressreport,
                        'eccdchecklist'             => $eccdchecklist,
                        'kindergartencert'          => $kindergartencert,
                        'schoolid'                  => $schoolid,
                        'schoolname'                => $schoolname,
                        'schooladdress'             => $schooladdress,
                        'pept'                      => $peptpasser,
                        'peptrating'                => $peptrating,
                        'examdate'                  => $examdate,
                        'centername'                => $centername,
                        // 'centeraddress'          => ,
                        'remarks'                   => $remarks,
                        'specifyothers'             => $specify,
                        'createdby'                 => auth()->user()->id,
                        'createddatetime'           => date('Y-m-d H:i:s')
                    ]);
            }
        }
        elseif($acadprogid == 4)
        {

            $courseschool         = $request->get('courseschool');
            $courseyear           = $request->get('courseyear');
            $coursegenave           = $request->get('coursegenave');

            $completer          = $request->get('completer');
            $generalaverage     = $request->get('generalaverage');
            $citation           = $request->get('citation');
            $peptpasser         = $request->get('peptpasser');
            $alspasser          = $request->get('alspasser');
            $alsrating          = $request->get('alsrating');
            $peptrating         = $request->get('peptrating');
            $schoolname         = $request->get('schoolname');
            $schoolid           = $request->get('schoolid');
            $schooladdress      = $request->get('schooladdress');
            $examdate           = $request->get('examdate');
            $specify            = $request->get('specify');
            $centername         = $request->get('centername');
            $guardianaddress    = $request->get('guardianaddress');
            $sygraduated        = $request->get('sygraduated');
            $totalnoofyears     = $request->get('totalnoofyears');
            $checkifexists      = DB::table('sf10eligibility_junior')
                                        ->where('studid', $studentid)
                                        ->where('deleted','0')
                                        ->first();
    
            if($checkifexists)
            {
                DB::table('sf10eligibility_junior')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'completer'         => $completer,
                        'genave'            => $generalaverage,
                        'citation'          => $citation,
                        'schoolid'          => $schoolid,
                        'schoolname'        => $schoolname,
                        'schooladdress'     => $schooladdress,
                        'peptpasser'        => $peptpasser,
                        'peptrating'        => $peptrating,
                        'alspasser'         => $alspasser,
                        'alsrating'         => $alsrating,
                        'examdate'          => $examdate,
                        'centername'        => $centername,
                        'specifyothers'     => $specify,
                        'guardianaddress'   => $guardianaddress,
                        'sygraduated'       => $sygraduated,
                        'totalnoofyears'    => $totalnoofyears,
                        'courseschool'  => $courseschool,
                        'courseyear'  => $courseyear,
                        'coursegenave'  => $coursegenave,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10eligibility_junior')
                    ->insert([
                        'studid'            => $studentid,
                        'completer'         => $completer,
                        'genave'            => $generalaverage,
                        'citation'          => $citation,
                        'schoolid'          => $schoolid,
                        'schoolname'        => $schoolname,
                        'schooladdress'     => $schooladdress,
                        'peptpasser'        => $peptpasser,
                        'peptrating'        => $peptrating,
                        'alspasser'         => $alspasser,
                        'alsrating'         => $alsrating,
                        'examdate'          => $examdate,
                        'centername'        => $centername,
                        'specifyothers'     => $specify,
                        'guardianaddress'   => $guardianaddress,
                        'sygraduated'       => $sygraduated,
                        'totalnoofyears'    => $totalnoofyears,
                        'courseschool'  => $courseschool,
                        'courseyear'  => $courseyear,
                        'coursegenave'  => $coursegenave,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }
        elseif($acadprogid == 5)
        {

            //             courseschool
            // courseyear
            // coursegenave
            
            $completerhs          = $request->get('completerhs');
            $completerjh          = $request->get('completerjh');
            $generalaveragehs     = $request->get('generalaveragehs');
            $generalaveragejh     = $request->get('generalaveragejh');
            $graduationdate       = $request->get('graduationdate');
            $peptpasser           = $request->get('peptpasser');
            $alspasser            = $request->get('alspasser');
            $alsrating            = $request->get('alsrating');
            $peptrating           = $request->get('peptrating');
            $schoolname           = $request->get('schoolname');
            $schooladdress        = $request->get('schooladdress');
            $examdate             = $request->get('examdate');
            $others               = $request->get('others');
            $centername         = $request->get('centername');

            $courseschool         = $request->get('courseschool');
            $courseyear           = $request->get('courseyear');
            $coursegenave           = $request->get('coursegenave');

            $dateshsadmission      = $request->get('dateshsadmission');
            $checkifexists        = DB::table('sf10eligibility_senior')
                                        ->where('studid', $studentid)
                                        ->where('deleted','0')
                                        ->first();
    
            if($checkifexists)
            {
                DB::table('sf10eligibility_senior')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'completerhs'       => $completerhs,
                        'completerjh'       => $completerjh,
                        'genavehs'          => $generalaveragehs,
                        'genavejh'          => $generalaveragejh,
                        'graduationdate'    => $graduationdate,
                        'schoolname'        => $schoolname,
                        'schooladdress'     => $schooladdress,
                        'peptpasser'        => $peptpasser,
                        'peptrating'        => $peptrating,
                        'alspasser'         => $alspasser,
                        'alsrating'         => $alsrating,
                        'examdate'          => $examdate,
                        'centername'        => $centername,
                        'others'            => $others,
                        'shsadmissiondate'  => $dateshsadmission,
                        'courseschool'  => $courseschool,
                        'courseyear'  => $courseyear,
                        'coursegenave'  => $coursegenave,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10eligibility_senior')
                    ->insert([
                        'studid'            => $studentid,
                        'completerhs'       => $completerhs,
                        'completerjh'       => $completerjh,
                        'genavehs'          => $generalaveragehs,
                        'genavejh'          => $generalaveragejh,
                        'graduationdate'    => $graduationdate,
                        'schoolname'        => $schoolname,
                        'schooladdress'     => $schooladdress,
                        'peptpasser'        => $peptpasser,
                        'peptrating'        => $peptrating,
                        'alspasser'         => $alspasser,
                        'alsrating'         => $alsrating,
                        'examdate'          => $examdate,
                        'centername'        => $centername,
                        'others'            => $others,
                        'shsadmissiondate'  => $dateshsadmission,
                        'courseschool'  => $courseschool,
                        'courseyear'  => $courseyear,
                        'coursegenave'  => $coursegenave,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }
    }
    public function getrecords_v3(Request $request)
    {
        $currentschoolyear = Db::table('sy')
            ->where('isactive','1')
            ->first();

        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.divisiontext',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();

        $studentid = $request->get('studentid');
        $acadprogid = $request->get('acadprogid');

        if($acadprogid == 5)
        {
            $gradelevels = DB::table('gradelevel')
                ->select('id','levelname','sortid as levelsortid')
                ->where('acadprogid', $acadprogid)
                ->where('deleted','0')
                ->orderBy('sortid','asc')
                ->get();

            $shsgradelevels = array();
            foreach($gradelevels as $gradelevel)
            {
                $gradelevel->subjects = DB::table('subject_plot')
                    ->select('sh_subjects.*','subject_plot.syid','subject_plot.semid','subject_plot.levelid','sy.sydesc')
                    ->join('sh_subjects','subject_plot.subjid','=','sh_subjects.id')
                    ->join('sy','subject_plot.syid','=','sy.id')
                    ->where('subject_plot.deleted','0')
                    ->where('sh_subjects.deleted','0')
                    ->where('sh_subjects.inSF9','1')
                    ->orderBy('sh_subj_sortid','asc')
                    // ->where('subject_plot.syid', $sy->syid)
                    ->where('subject_plot.levelid', $gradelevel->id)
                    ->get();
                    
                $gradelevel->subjects = collect($gradelevel->subjects)->unique();
                $romannumeral = numberToRomanRepresentation(preg_replace('/\D+/', '', $gradelevel->levelname));
                for($x = 1; $x <= 2; $x++)
                {
                    // $gradelevel->semid = $x;
                    // array_push($shsgradelevels, $gradelevel);
                    array_push($shsgradelevels, (object)array(
                        'id'            => $gradelevel->id,
                        'levelname'            => $gradelevel->levelname,
                        'subjects'            => $gradelevel->subjects,
                        'levelsortid'            => $gradelevel->levelsortid,
                        'semid'            => $x,
                        'romannumeral'            => $romannumeral
                    ));
                }
            }
            $gradelevels = $shsgradelevels;
    
        }else{
            $gradelevels = DB::table('gradelevel')
                ->select('id','levelname','sortid as levelsortid')
                ->where('acadprogid', $acadprogid)
                ->where('deleted','0')
                ->orderBy('sortid','asc')
                ->get();

            foreach($gradelevels as $eachlevel)
            {
                $eachlevel->subjects = DB::table('subject_plot')
                    ->select('subjects.*','subject_plot.syid','subject_plot.levelid','sy.sydesc')
                    ->join('subjects','subject_plot.subjid','=','subjects.id')
                    ->join('sy','subject_plot.syid','=','sy.id')
                    ->where('subject_plot.deleted','0')
                    ->where('subjects.deleted','0')
                    ->where('subjects.inSF9','1')
                    ->orderBy('subject_plot.plotsort','asc')
                    ->where('subject_plot.syid', $currentschoolyear->id)
                    ->where('subject_plot.levelid', $eachlevel->id)
                    ->get();
                    $eachlevel->subjects = collect($eachlevel->subjects)->unique('subjdesc');
            }

            $displayaccomplished = null;
            $collectgradelevels = array();
            foreach($gradelevels as $gradelevel)
            {
                $gradelevel->recordinputype = 0; //0 = auto; 1 = manual; 2 = upload;
                $gradelevel->autoexists = 0;
                $gradelevel->manualexists = 0;
                $gradelevel->recordid = 0;
                $gradelevel->inschool = 0;
                $gradelevel->syid = 0;
                $gradelevel->sydesc = null;
                $gradelevel->sectionid = 0;
                if($acadprogid == 5)
                {
                    $enrollmentadetails = DB::table('sh_enrolledstud')
                        ->select('sh_enrolledstud.*','sy.sydesc','sections.sectionname','sh_strand.strandname','sh_strand.strandcode','sh_track.trackname')
                        ->join('sy','sh_enrolledstud.syid','=','sy.id')
                        ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                        ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                        ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                        ->where('sh_enrolledstud.studid', $studentid)
                        ->where('sh_enrolledstud.levelid', $gradelevel->id)
                        ->where('sh_enrolledstud.semid', $gradelevel->semid)
                        ->where('sh_enrolledstud.deleted','0')
                        ->get();
    
    
                }else{
                    $enrollmentadetails = DB::table('enrolledstud')
                        ->select('enrolledstud.*','sy.sydesc','sections.sectionname')
                        ->join('sy','enrolledstud.syid','=','sy.id')
                        ->join('sections','enrolledstud.sectionid','=','sections.id')
                        ->where('enrolledstud.studid', $studentid)
                        ->where('enrolledstud.levelid', $gradelevel->id)
                        ->where('enrolledstud.deleted','0')
                        ->get();
                }
                
                $teachername = '';
                
                $autoinfo = array();
                if(count($enrollmentadetails) > 0)
                {
                    $displayaccomplished = isset(collect($enrollmentadetails)->first()->strandname) ? collect($enrollmentadetails)->first()->strandname : null;
                    $gradelevel->autoexists = 1;
                    $gradelevel->inschool = 1;
                    $gradelevel->sydesc = collect($enrollmentadetails)->first()->sydesc;
                    $gradelevel->syid = collect($enrollmentadetails)->first()->syid;
                    $gradelevel->sectionid = collect($enrollmentadetails)->first()->sectionid;
                    $gradelevel->strandid = isset(collect($enrollmentadetails)->first()->strandid) ? collect($enrollmentadetails)->first()->strandid : null;
                    $gradelevel->promotionstatus = collect($enrollmentadetails)->first()->promotionstatus ?? null;
                    
                    $getTeacher = Db::table('sectiondetail')
                        ->select(
                            'teacher.title',
                            'teacher.firstname',
                            'teacher.middlename',
                            'teacher.lastname',
                            'teacher.suffix'
                            )
                        ->join('teacher','sectiondetail.teacherid','teacher.id')
                        ->where('sectiondetail.sectionid',$gradelevel->sectionid)
                        ->where('sectiondetail.syid',$gradelevel->syid)
                        ->where('sectiondetail.deleted','0')
                        ->first();
        
                    if($getTeacher)
                    {
                        if($getTeacher->title!=null)
                        {
                            $teachername.=$getTeacher->title.' ';
                        }
                        if($getTeacher->firstname!=null)
                        {
                            $teachername.=$getTeacher->firstname.' ';
                        }
                        if($getTeacher->middlename!=null)
                        {
                            $teachername.=$getTeacher->middlename[0].'. ';
                        }
                        if($getTeacher->middlename!=null)
                        {
                            $teachername.=$getTeacher->lastname.' ';
                        }
                        if($getTeacher->lastname!=null)
                        {
                            $teachername.=$getTeacher->suffix.' ';
                        }
                    }
                    $recordincharge = null;
                    $datechecked = null;
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                    {
                        $recordincharge = 'MERLIE S. SABUELO, REGISTRAR';
                        $datechecked = date('m/d/Y');
                    }
                    $autoinfo = array((object)array(
                        'syid'          => collect($enrollmentadetails)->first()->syid,
                        'sydesc'          => collect($enrollmentadetails)->first()->sydesc,
                        'semid'          => isset($gradelevel->semid) ? $gradelevel->semid : 0,
                        'strandid'          => isset(collect($enrollmentadetails)->first()->strandid) ? collect($enrollmentadetails)->first()->strandid : null,
                        'strandcode'          => isset(collect($enrollmentadetails)->first()->strandcode) ? collect($enrollmentadetails)->first()->strandcode : null,
                        'strandname'          => isset(collect($enrollmentadetails)->first()->strandname) ? collect($enrollmentadetails)->first()->strandname : null,
                        'trackname'          => isset(collect($enrollmentadetails)->first()->trackname) ? collect($enrollmentadetails)->first()->trackname : null,
                        'semid'          => isset($gradelevel->semid) ? $gradelevel->semid : 0,
                        'sectionname'          => collect($enrollmentadetails)->first()->sectionname,
                        'teachername'          => $teachername,
                        'schoolid'          => $schoolinfo->schoolid,
                        'schoolname'          => $schoolinfo->schoolname,
                        'schooladdress'          => $schoolinfo->address,
                        'schooldistrict'          => $schoolinfo->district,
                        'schooldivision'          => $schoolinfo->divisiontext,
                        'schoolregion'          => $schoolinfo->region,
                        'remarks'          => null,
                        'recordincharge'          => $recordincharge,
                        'datechecked'          => $datechecked,
                        'credit_advance'          => null,
                        'credit_lack'          => null,
                        'noofyears'          =>  null
                    ));
                }else{
                    // $gradelevel->semid = 0;
                    $gradelevel->recordinputype = 3;
                    $gradelevel->strandid = null;
                    $gradelevel->strandcode = null;
                    $gradelevel->strandname = null;
                    $gradelevel->trackname = null;
                    $gradelevel->promotionstatus = null;
                }
                if($gradelevel->syid == 0)
                {
                    $studgrades =array();
                }else{
                    if($acadprogid == 5)
                    {
                        $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $gradelevel->id,$studentid,$gradelevel->syid,$gradelevel->strandid,null,$gradelevel->sectionid);
    
                    }else{
                        $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $gradelevel->id,$studentid,$gradelevel->syid,null,null,$gradelevel->sectionid);
                    }
                }
                
                $temp_grades = array();
                $generalaverage = array();
                foreach($studgrades as $item){
                    if($item->id == 'G1'){
                        array_push($generalaverage,$item);
                    }else{
                        array_push($temp_grades,$item);
                    }
                }
                $studgrades = $temp_grades;
                if($acadprogid == 5)
                {
                    $generalaverage = collect($generalaverage)->where('semid',$gradelevel->semid)->values();
                    $studgrades = collect($studgrades)->where('semid', $gradelevel->semid)->values()->all();
                }
                $autogrades = collect($studgrades)->sortBy('sortid')->values()->all();
               
                $gradesadd = 0;
                if(count($autogrades)>0)
                {
                    // return collect($gradelevel);
                    // return collect($autogrades);
                    // return $gradelevel->syid;
                    foreach($autogrades as $grade)
                    {
                        if(!collect($grade)->has('inMAPEH'))
                        {
                            $grade->inMAPEH = 0;
                        }
                        if(!collect($grade)->has('inTLE'))
                        {
                            $grade->inTLE = 0;
                        }
                        if(!collect($grade)->has('subjdesc'))
                        {
                            if(collect($grade)->has('subjectcode'))
                            {
                                $grade->subjdesc = $grade->subjectcode;
                            }
                            $grade->q1 = $grade->quarter1;
                            $grade->q2 = $grade->quarter2;
                            $grade->q3 = $grade->quarter3;
                            $grade->q4 = $grade->quarter4;
                        }else{
                            // $grade->subjdesc = ucwords(strtolower($grade->subjdesc));
                        }
                        if($acadprogid == 5)
                        {
                            if($grade->semid == 2)
                            {
                                $grade->q1 = $grade->q3;
                                $grade->q2 = $grade->q4;
                            }
                        }
                        // 0 = noteditable ; 1 = for adding (first time) ; 2 = editable;
                        $grade->q1stat = 0;
                        $grade->q2stat = 0;
                        $grade->q3stat = 0;
                        $grade->q4stat = 0;
                        
    
                        $complete = 0;
                        $chekifaddinautoexist = DB::table('sf10grades_addinauto')
                                ->where('studid',$studentid)
                                ->where('subjid',$grade->subjid)
                                ->where('levelid',$gradelevel->id)
                                ->where('deleted',0)
                                ->get();
    
                        if(count($chekifaddinautoexist)>0)
                        {
                            $gradesadd += 1;
                        }
                        if(collect($chekifaddinautoexist)->where('quarter',1)->count() > 0)
                        {
                            $grade->q1stat = 2;
                            $grade->q1    = collect($chekifaddinautoexist)->where('quarter',1)->first()->grade;
                            $complete+=1;;
                        }
                        if(collect($chekifaddinautoexist)->where('quarter',2)->count() > 0)
                        {
                            $grade->q2stat = 2;
                            $grade->q2    = collect($chekifaddinautoexist)->where('quarter',2)->first()->grade;
                            $complete+=1;;
                        }
                        if(collect($chekifaddinautoexist)->where('quarter',3)->count() > 0)
                        {
                            $grade->q3stat = 2;
                            $grade->q3    = collect($chekifaddinautoexist)->where('quarter',3)->first()->grade;
                            $complete+=1;;
                        }
                        if(collect($chekifaddinautoexist)->where('quarter',4)->count() > 0)
                        {
                            $grade->q4stat = 2;
                            $grade->q4    = collect($chekifaddinautoexist)->where('quarter',4)->first()->grade;
                            $complete+=1;;
                        }
    
                        if($grade->q1 == 0)
                        {
                            $grade->q1 = null;
                            $grade->q1stat = 1;
                        }else{
                            $complete+=1;;
                        }
                        if($grade->q2 == 0)
                        {
                            $grade->q2 = null;
                            $grade->q2stat = 1;
                        }else{
                            $complete+=1;;
                        }
                        if($grade->q3 == 0)
                        {
                            $grade->q3 = null;
                            $grade->q3stat = 1;
                        }else{
                            $complete+=1;;
                        }
                        if($grade->q4 == 0)
                        {
                            $grade->q4 = null;
                            $grade->q4stat = 1;
                        }else{
                            $complete+=1;;
                        }
                        if($grade->q1 == null)
                        {
                            $grade->q1stat = 1;
                        }
                        if($grade->q2 == null)
                        {
                            $grade->q2stat = 1;
                        }
                        if($grade->q3 == null)
                        {
                            $grade->q3stat = 1;
                        }
                        if($grade->q4 == null)
                        {
                            $grade->q4stat = 1;
                        }
    
                        if($acadprogid == 5)
                        {
                            $quarterlimit = 2;
                        }else{
                            $quarterlimit = 4;
                        }
                        if($complete < $quarterlimit)
                        {
                            $qg = null;
                            $remarks = ($eachrecord->studstatus != 3 && $eachrecord->studstatus != 5) ? 'TAKING' : '';
                        }else{
                            if($acadprogid == 5)
                            {
                                $qg = ($grade->q1 + $grade->q2) / 2;
                            }else{
                                $qg = ($grade->q1 + $grade->q2 + $grade->q3 + $grade->q4) / 4;
                            }
                            if($qg>=75){
            
                                $remarks = "PASSED";
            
                            }elseif($qg == null){
            
                                $remarks = null;
            
                            }else{
                                $remarks = "FAILED";
                            }
                            
                            if($qg == 0)
                            {
                                $qg = null;
                                $remarks = null;
                            }
                        }
                        
                        if($acadprogid != 5)
                        {
                            $grade->subjcode = null;
                        }
                        if($acadprogid == 5)
                        {
                            $subjcode = DB::table('sh_subjects')
                                ->where('id', $grade->subjid)
                                ->first();
        
                            if($subjcode)
                            {
                                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                                {
                                    // return collect($subject)
                                    if(in_array($subject->subjid, $subjidarray))
                                    {
                                            $subjcode = 'Other Subject';
                                    }else{
                                        if($subjcode->type == 1)
                                        {
                                            $subjcode = 'CORE';
                                        }
                                        elseif($subjcode->type == 3)
                                        {
                                            $subjcode = 'APPLIED';
                                        }
                                        elseif($subjcode->type == 2)
                                        {
                                            $subjcode = 'SPECIALIZED';
                                        }else{
                                            $subjcode = 'Other Subject';
                                        }
                                    }
                                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                {
                                    if($subjcode->type == 1)
                                    {
                                        $subjcode = 'CORE';
                                    }
                                    elseif($subjcode->type == 2)
                                    {
                                        $subjcode = 'SPECIALIZED';
                                    }
                                    elseif($subjcode->type == 3)
                                    {
                                        $subjcode = 'APPLIED';
                                    }else{
                                        $subjcode = 'Other Subject';
                                    }
                                }else{
                                    if($subjcode->type == 1)
                                    {
                                        $subjcode = 'CORE';
                                    }
                                    elseif($subjcode->type == 2)
                                    {
                                        $subjcode = 'APPLIED';
                                    }
                                    elseif($subjcode->type == 3)
                                    {
                                        $subjcode = 'SPECIALIZED';
                                    }else{
                                        $subjcode = 'Other Subject';
                                    }
                                }
                            }else{
                                $subjcode = null;
                            }
                            $grade->subjcode = $subjcode;
                        }
                        $grade->subjtitle = $grade->subjdesc;
                        $grade->quarter1 = (number_format($grade->q1) > 0 ? number_format($grade->q1) : null);
                        $grade->quarter2 = (number_format($grade->q2) > 0 ? number_format($grade->q2) : null);
                        $grade->quarter3 = (number_format($grade->q3) > 0 ? number_format($grade->q3) : null);
                        $grade->quarter4 = (number_format($grade->q4) > 0 ? number_format($grade->q4) : null);
                        $grade->finalrating = (number_format($qg) > 0 ? number_format($qg) : null);
                        $grade->remarks = $remarks;
                    }
                    
                }
                
                $autogrades = collect($autogrades)->map(function ($user) {
                    return (object)collect($user)
                        ->only(['subjdesc', 'subjcode','inSF9','id','plotsort','sortid','subjid','semid','strandid','gradessetup','search','mapeh','inTLE','inMAPEH','q1','q2','q3','q4','quarter1','quarter2','quarter3','quarter4','finalrating','actiontaken','ver','q1stat','q2stat','q3stat','q4stat','subjtitle','remarks'])
                        ->all();
                })->values()->all();
                $autogrades = collect($autogrades)->sortBy('sortid')->values();
                
                $subjaddedforauto     = DB::table('sf10grades_subjauto')
                                        ->where('studid',$studentid)
                                        ->where('syid',$gradelevel->syid)
                                        ->where('levelid',$gradelevel->id)
                                        ->where('deleted','0')
                                        ->get();
                    
                
                
                $attendancesummary = array();
                if($gradelevel->syid > 0)
                {
                    $attendancesummary = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($gradelevel->syid);
                    foreach( $attendancesummary as $item){
                        $month_count = \App\Models\Attendance\AttendanceData::monthly_attendance_count($gradelevel->syid,$item->month,$studentid);
                        $item->present = collect($month_count)->where('present',1)->count() + collect($month_count)->where('tardy',1)->count() + collect($month_count)->where('cc',1)->count();
                        $item->absent = collect($month_count)->where('absent',1)->count();
                    }
                    
                    $attendancesummary = collect($attendancesummary)->sortBy('sort')->values()->all();
                }
                $autoattendance = $attendancesummary;
    
                    
                if($acadprogid == 5)
                {
                    $manualrecords = DB::table('sf10')
                        ->select('sf10.id','sf10.id as recordid','sf10.syid','sf10.sydesc','sf10.levelid','gradelevel.levelname','sf10.sectionid','sf10.sectionname','sf10.teachername','sf10.schoolid','sf10.schoolname','sf10.schooladdress','sf10.schooldistrict','sf10.schooldivision','sf10.schoolregion','sf10.remarks','sf10.recordincharge','sf10.datechecked','sf10.credit_advance','sf10.credit_lack','sf10.noofyears','recordinputtype','isactive','trackname','strandname','sf10.semid')
                        ->join('gradelevel','sf10.levelid','=','gradelevel.id')
                        ->where('sf10.studid', $studentid)
                        ->where('sf10.acadprogid', $acadprogid)
                        ->where('sf10.levelid', $gradelevel->id)
                        ->where('sf10.deleted','0')
                        // ->where('sf10.isactive','1')
                        ->get();
                    $manualrecords = collect($manualrecords)->where('semid',$gradelevel->semid)->values();
                }else{
                    
                    $manualrecords = DB::table('sf10')
                    ->select('sf10.id','sf10.id as recordid','sf10.syid','sf10.sydesc','sf10.levelid','gradelevel.levelname','sf10.sectionid','sf10.sectionname','sf10.teachername','sf10.schoolid','sf10.schoolname','sf10.schooladdress','sf10.schooldistrict','sf10.schooldivision','sf10.schoolregion','sf10.remarks','sf10.recordincharge','sf10.datechecked','sf10.credit_advance','sf10.credit_lack','sf10.noofyears','recordinputtype','isactive','trackname','strandname','sf10.semid')
                    ->join('gradelevel','sf10.levelid','=','gradelevel.id')
                    ->where('sf10.studid', $studentid)
                    ->where('sf10.acadprogid', $acadprogid)
                    ->where('sf10.levelid', $gradelevel->id)
                    ->where('sf10.deleted','0')
                    // ->where('sf10.isactive','1')
                    ->get();
                }
                // return $manualrecords;
                $manualinfo = array();
                $manualgrades = array();
                $manualattendance = array();
                $manualremedialclasses = array();
                
                if(count($enrollmentadetails) == 0)
                {
                    if(count($manualrecords)>0)
                    {
                        $gradelevel->manualexists = 1;
                        // return $manualrecords;
                        $manualinfo = $manualrecords;
                        foreach($manualrecords as $manualrecord)
                        {
                            $manualrecord->type = 2;
        
                            if($acadprogid == 4 || $acadprogid == 3)
                            {
                                if($acadprogid == 3)
                                {
                                    $table_acad = 'elem';
                                }else{
                                    $table_acad = 'junior';
                                }
                                $grades = DB::table('sf10grades_'.$table_acad)
                                        ->where('headerid', $manualrecord->id)
                                        ->where('deleted','0')
                                        ->get();
                                if(count($grades)>0)
                                {
                                    foreach($grades as $grade)
                                    {                    
                                        $grade->q1stat = 0;
                                        $grade->q2stat = 0;
                                        $grade->q3stat = 0;
                                        $grade->q4stat = 0;
                                        
                                        if($grade->q1 == 0)
                                        {
                                            $grade->q1 = null;
                                        }
                                        if($grade->q2 == 0)
                                        {
                                            $grade->q2 = null;
                                        }
                                        if($grade->q3 == 0)
                                        {
                                            $grade->q3 = null;
                                        }
                                        if($grade->q4 == 0)
                                        {
                                            $grade->q4 = null;
                                        }
                                        $grade->subjcode = null;
                                        $grade->subjtitle = $grade->subjectname;
                                        $grade->subjdesc = $grade->subjectname;
                                        $grade->quarter1 = $grade->q1;
                                        $grade->quarter2 = $grade->q2;
                                        $grade->quarter3 = $grade->q3;
                                        $grade->quarter4 = $grade->q4;
            
                                        array_push($manualgrades,(object)array(
                                            'id'            => $grade->id,
                                            'headerid'            => $grade->headerid,
                                            'subjectid'            => $grade->subjectid,
                                            'subjcode'            => $grade->subjcode,
                                            'subjtitle'            => $grade->subjtitle,
                                            'subjdesc'            => $grade->subjdesc,
                                            'q1'            => $grade->q1,
                                            'q2'            => $grade->q2,
                                            'q3'            => $grade->q3,
                                            'q4'            => $grade->q4,
                                            'quarter1'            => $grade->q1,
                                            'quarter2'            => $grade->q2,
                                            'quarter3'            => $grade->q3,
                                            'quarter4'            => $grade->q4,
                                            'finalrating'            => $grade->finalrating,
                                            'credits'            => $grade->credits,
                                            'remarks'            => $grade->remarks,
                                            'inMAPEH'            => $grade->inMAPEH,
                                            'inTLE'            => $grade->inTLE,
                                            'fromsystem'            => $grade->fromsystem,
                                            'isgenave'            => $grade->isgenave,
                                            'editablegrades'            => $grade->editablegrades,
                                            'inputtype'            => $grade->inputtype,
                                        ));
                                    }
                                    
                                }
                            }
                            elseif($acadprogid == 5)
                            {
                                $grades = DB::table('sf10grades_senior')
                                        ->where('headerid', $manualrecord->id)
                                        ->where('deleted','0')
                                        ->get();
                                        
                                if(count($grades)>0)
                                {
                                    foreach($grades as $grade)
                                    {
                                        $grade->q1stat = 0;
                                        $grade->q2stat = 0;
                                        
                                        if($grade->q1 == 0)
                                        {
                                            $grade->q1 = null;
                                        }
                                        if($grade->q2 == 0)
                                        {
                                            $grade->q2 = null;
                                        }
                                        $grade->semid = $manualrecord->semid;
                                         $grade->semid = $manualrecord->semid;
                                    }
                                    
                                     $grades[0]->semid = $manualrecord->semid;
                                }
                                if(count($grades)>0)
                                {
                                    foreach($grades as $grade)
                                    {                    
                                        $grade->q1stat = 0;
                                        $grade->q2stat = 0;
                                        
                                        if($grade->q1 == 0)
                                        {
                                            $grade->q1 = null;
                                        }
                                        if($grade->q2 == 0)
                                        {
                                            $grade->q2 = null;
                                        }
                                        $grade->subjcode = $grade->subjcode;
                                        $grade->subjtitle = $grade->subjdesc;
                                        $grade->subjdesc = $grade->subjdesc;
                                        $grade->quarter1 = $grade->q1;
                                        $grade->quarter2 = $grade->q2;
            
                                        array_push($manualgrades,(object)array(
                                            'id'            => $grade->id,
                                            'headerid'            => $grade->headerid,
                                            'subjectid'            => $grade->subjid,
                                            'subjcode'            => $grade->subjcode,
                                            'subjtitle'            => $grade->subjdesc,
                                            'subjdesc'            => $grade->subjdesc,
                                            'q1'            => $grade->q1,
                                            'q2'            => $grade->q2,
                                            'quarter1'            => $grade->q1,
                                            'quarter2'            => $grade->q2,
                                            'finalrating'            => $grade->finalrating,
                                            'remarks'            => $grade->remarks,
                                            'fromsystem'            => $grade->fromsystem,
                                            'editablegrades'            => $grade->editablegrades,
                                            'inputtype'            => $grade->inputtype,
                                            'isgenave'            => $grade->isgenave
                                        ));
                                    }
                                }
                            }
                            $remedialclasses = DB::table('sf10remedial_senior')
                                    ->where('headerid', $manualrecord->id)
                                    ->where('deleted','0')
                                    ->get();
            
                        
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                            {
                                $attendance = DB::table('sf10attendance')
                                ->where('sf10attendance.studentid',$studentid)
                                    ->where('acadprogid','5')
                                    ->where('sydesc',$manualrecord->sydesc)
                                    ->where('deleted','0')
                                    ->get();
            
                            }else{
                                $attendance = array();
                            }
                            $manualrecord->promotionstatus = null;
                            $manualattendance = $attendance;
                            $manualrecord->attendance = $attendance;
                            $manualremedialclasses = $remedialclasses;
                            $manualrecord->remedialclasses = $remedialclasses;
                            $manualrecord->grades               = $grades;
                            $manualrecord->generalaverage       = collect($grades)->where('isgenave',1)->values();
                            // $manualrecord->subjaddedforauto     = array();
                            // $manualrecord->attendance           = $attendance;
                            // $manualrecord->remedials            = $remedialclasses;
                        }
                    }
                    if(collect($manualrecords)->where('isactive','1')->count()>0)
                    {
                        foreach(collect($manualrecords)->where('isactive','1')->values() as $eachmanualrecord)
                        {
                            // return $eachmanualrecord;
                            // return collect($manualrecords[1]);
                            $addcollect_sydesc = isset($eachmanualrecord->sydesc) ? $eachmanualrecord->sydesc : null;
                            $addcollect_recordinputype = isset($eachmanualrecord->recordinputtype) ? $eachmanualrecord->recordinputtype : 1;
                            $addcollect_headerinfo = $eachmanualrecord;
                            $addcollect_grades = collect($eachmanualrecord->grades)->where('isgenave','0')->values()->all();
                            $addcollect_generalaverage = collect($eachmanualrecord->grades)->where('isgenave','1')->values()->all();
                            $addcollect_subjaddedforauto = array();
                            $addcollect_attendance = $eachmanualrecord->attendance;
                            $addcollect_strandname = $eachmanualrecord->strandname;
                            $addcollect_trackname = $eachmanualrecord->trackname;
                            $addcollect_remedialclasses = $eachmanualrecord->remedialclasses;
                            if(count(collect($eachmanualrecord->grades)->where('isgenave','0')->values()->all()) == 0)
                            {
                            $addcollect_withgrades = 0;
                            }else{
                            $addcollect_withgrades = 1;
                            }
        
                            $gradelevels = collect($gradelevels)->push((object)[
                                'id'       => $gradelevel->id,
                                'levelname'       => $gradelevel->levelname,
                                'romannumeral'       => $gradelevel->romannumeral ?? '',
                                'levelsortid'       => $gradelevel->levelsortid,
                                'recordinputype'       => $gradelevel->recordinputype,
                                'autoexists'       => $gradelevel->autoexists,
                                'manualexists'       => $gradelevel->manualexists,
                                'recordid'       => $gradelevel->recordid,
                                'inschool'       => $gradelevel->inschool,
                                'syid'       => $gradelevel->syid,
                                'sydesc'       => $eachmanualrecord->sydesc,
                                'semid'       => isset($eachmanualrecord->semid) ? $eachmanualrecord->semid : null,
                                'sectionid'       => $gradelevel->sectionid,
                                'strandid'       => $gradelevel->strandid,
                                'strandcode'       => $gradelevel->strandcode ?? null,
                                'strandname'       => $addcollect_strandname,
                                'trackname'       => $addcollect_trackname,
                                'headerinfo'       => array($addcollect_headerinfo),
                                'grades'       => $addcollect_grades,
                                'promotionstatus'       => null,
                                'generalaverage'       => $addcollect_generalaverage,
                                'subjaddedforauto'       => $addcollect_subjaddedforauto,
                                'attendance'       => $addcollect_attendance,
                                'remedialclasses'       => $addcollect_remedialclasses,
                                'withgrades'       => $addcollect_withgrades,
                            ]);
                            // return $gradelevels;
                        }
                    }else{
                        $gradelevel->headerinfo = $autoinfo;
                        $gradelevel->grades = $autogrades;
                        $gradelevel->generalaverage = $generalaverage;
                        $gradelevel->subjaddedforauto = $subjaddedforauto;
                        $gradelevel->attendance = $autoattendance;
                        $gradelevel->remedialclasses = array();
                        if(count($autogrades) == 0)
                        {
                        $gradelevel->withgrades = 0;
                        }else{
                        $gradelevel->withgrades = 1;
                        }
                    }
                }else{
                    $gradelevel->headerinfo = $autoinfo;
                    $gradelevel->grades = $autogrades;
                    $gradelevel->generalaverage = $generalaverage;
                    $gradelevel->subjaddedforauto = $subjaddedforauto;
                    $gradelevel->attendance = $autoattendance;
                    $gradelevel->remedialclasses = array();
                    if(count($autogrades) == 0)
                    {
                    $gradelevel->withgrades = 0;
                    }else{
                    $gradelevel->withgrades = 1;
                    }
                }
            }
               
            if($acadprogid == 5)
            {
            $gradelevels = collect($gradelevels)->sortBy('semid')->sortBy('levelsortid')->values()->all();
            }else{
            $gradelevels = collect($gradelevels)->sortBy('sydesc')->sortBy('levelsortid')->values()->all();
            }
            // return $gradelevels;
            $finallevels = array();
            foreach($gradelevels as $eachgradelevel)
            {
                if(isset($eachgradelevel->headerinfo))
                {
                    array_push($finallevels, $eachgradelevel);
                }
            }
            $gradelevels = $finallevels;
            if($acadprogid == 5)
            {
                $subjects = DB::table('subject_plot')
                    ->select('sh_subjects.id','subjcode','sh_subjects.subjtitle as subjdesc','subject_plot.strandid','subject_plot.plotsort','subject_plot.semid','subject_plot.syid','subject_plot.levelid','subject_plot.strandid')
                    ->join('sh_subjects','subject_plot.subjid','=','sh_subjects.id')
                    ->where('sh_subjects.inSF9', 1)
                    ->where('sh_subjects.deleted', 0)
                    ->where('subject_plot.deleted', 0)
                    ->orderBy('subject_plot.plotsort','asc')
                    ->get();  
        
                $eligibility = DB::table('sf10eligibility_senior')
                    ->where('studid', $studentid)
                    ->where('deleted','0')
                    ->first();
    
                if(!$eligibility)
                {
                    $eligibility = (object)array(
                        'completerhs'       =>  0,
                        'genavehs'          =>  null,
                        'completerjh'       =>  0,
                        'genavejh'          =>  null,
                        'graduationdate'    =>  null,
                        'schoolname'        =>  null,
                        'schooladdress'     =>  null,
                        'peptpasser'        =>  0,
                        'peptrating'        =>  null,
                        'alspasser'         =>  0,
                        'alsrating'         =>  null,
                        'examdate'          =>  null,
                        'centername'        =>  null,
                        'shsadmissiondate'        =>  null,
                        'strandaccomplished'        =>  null,
                        'others'            =>  null
                    );
                }
                $footer = DB::table('sf10_footer_senior')
                    ->where('studid', $studentid)
                    ->where('deleted','0')
                    ->first();
                    
                if(!$footer)
                {
                    $footer = (object)array(
                        'strandaccomplished'        =>  $displayaccomplished,
                        'shsgenave'                 =>  null,
                        'honorsreceived'            =>  null,
                        'shsgraduationdate'         =>  null,
                        'shsgraduationdateshow'     =>  null,
                        'datecertified'             =>  null,
                        'certifiedby'             =>  null,
                        'datecertifiedshow'         =>  null,
                        'copyforupper'              =>  null,
                        'copyforlower'              =>  null
                    );
                }else{
                    if($footer->strandaccomplished == null)
                    {
                        $footer->strandaccomplished = $displayaccomplished;
                    }
                    if($footer->shsgraduationdate != null)
                    {
                        $footer->shsgraduationdate = date('m/d/Y', strtotime($footer->shsgraduationdate));
                        $footer->shsgraduationdateshow = date('Y-m-d', strtotime($footer->shsgraduationdate));
                    }else{
                        $footer->shsgraduationdateshow = null;
                    }
                    if($footer->datecertified != null)
                    {
                        $footer->datecertified = date('m/d/Y', strtotime($footer->datecertified));
                        $footer->datecertifiedshow = date('Y-m-d', strtotime($footer->datecertified));
                    }else{
                        $footer->datecertifiedshow = null;
                    }
                }
            }elseif($acadprogid == 4)
            {
                $subjects = DB::table('subject_plot')
                    ->select('subjects.id','subjcode','subjects.subjdesc','subject_plot.strandid','subject_plot.plotsort','subject_plot.semid','subject_plot.syid','subject_plot.levelid','subject_plot.strandid')
                    ->join('subjects','subject_plot.subjid','=','subjects.id')
                    ->where('subjects.inSF9', 1)
                    ->where('subjects.deleted', 0)
                    ->where('subject_plot.levelid', '!=','14')
                    ->where('subject_plot.levelid', '!=','15')
                    ->where('subject_plot.deleted', 0)
                    ->orderBy('subject_plot.plotsort','asc')
                    ->get();  
                    
                $subjects = collect($subjects)->unique('subjdesc')->values();
                $eligibility = DB::table('sf10eligibility_junior')
                    ->where('studid', $studentid)
                    ->where('deleted','0')
                    ->first();
        
                if(!$eligibility)
                {
                    $eligibility = (object)array(
                        'completer'  =>  0,
                        'genave'     =>  0,
                        'citation'          =>  null,
                        'schoolid'          =>  null,
                        'schoolname'        =>  null,
                        'schooladdress'     =>  null,
                        'peptpasser'        =>  0,
                        'peptrating'        =>  null,
                        'alspasser'         =>  0,
                        'alsrating'         =>  null,
                        'examdate'          =>  null,
                        'centername'        =>  null,
                        'centeraddress'     =>  null,
                        'remarks'           =>  null,
                        'specifyothers'     =>  null,
                        'guardianaddress'     =>  null,
                        'sygraduated'     =>  null,
                        'totalnoofyears'     =>  null
                    );
                } 
                $footer = DB::table('sf10_footer_junior')
                    ->where('studid', $studentid)
                    ->where('deleted','0')
                    ->first();
                    
        
                if(!$footer)
                {
                    $footer = (object)array(
                        'copyforupper'        =>  null,
                        'purpose'        =>  null,
                        'classadviser'                 =>  null,
                        'recordsincharge'            =>  null,
                        'copysentto'            =>  null,
                        'address'            =>  null
                    );
                }
            }if(!$request->has('export'))
            {
                if($acadprogid == 5)
                {
                    $footer = DB::table('sf10_footer_senior')
                        ->where('studid', $studentid)
                        ->where('deleted','0')
                        ->first();
                        
                    if(!$footer)
                    {
                        $footer = (object)array(
                            'strandaccomplished'        =>  $displayaccomplished,
                            'shsgenave'                 =>  null,
                            'honorsreceived'            =>  null,
                            'shsgraduationdate'         =>  null,
                            'shsgraduationdateshow'     =>  null,
                            'certifiedby'             =>  null,
                            'datecertified'             =>  null,
                            'datecertifiedshow'         =>  null,
                            'copyforupper'              =>  null,
                            'copyforlower'              =>  null
                        );
                    }else{
                        if($footer->strandaccomplished == null)
                        {
                            $footer->strandaccomplished = $displayaccomplished;
                        }
                        if($footer->shsgraduationdate != null)
                        {
                            $footer->shsgraduationdate = date('m/d/Y', strtotime($footer->shsgraduationdate));
                            $footer->shsgraduationdateshow = date('Y-m-d', strtotime($footer->shsgraduationdate));
                        }else{
                            $footer->shsgraduationdateshow = null;
                        }
                        if($footer->datecertified != null)
                        {
                            $footer->datecertified = date('m/d/Y', strtotime($footer->datecertified));
                            $footer->datecertifiedshow = date('Y-m-d', strtotime($footer->datecertified));
                        }else{
                            $footer->datecertifiedshow = null;
                        }
                    }
                    // return $subjects;
                        //  return $gradelevels;
                        return view('registrar.forms.form10v2.records_shs')
                            ->with('eligibility', $eligibility)
                            ->with('studentid', $studentid)
                            ->with('acadprogid', $acadprogid)
                            ->with('gradelevels', $gradelevels)
                            ->with('footer', $footer)
                            ->with('subjects', $subjects);
                        //  return $gradelevels;
        
                }
                elseif($acadprogid == 4)
                {
                    // return $gradelevels;
                        return view('registrar.forms.form10v2.records_jhs')
                            ->with('eligibility', $eligibility)
                            ->with('studentid', $studentid)
                            ->with('acadprogid', $acadprogid)
                            ->with('gradelevels', $gradelevels)
                            ->with('footer', $footer)
                            ->with('subjects', $subjects);
                        //  return $gradelevels;
        
                }
            }
            // return $gradelevels;
        }
    }
    public function getrecords_elem(Request $request)
    {
        $acadprogid = $request->get('acadprogid');
        $studentid = $request->get('studentid');
        
        $currentschoolyear = Db::table('sy')
            ->where('isactive','1')
            ->first();
        $gradelevels = DB::table('gradelevel')
            ->select(
                'gradelevel.id',
                'gradelevel.levelname',
                'gradelevel.sortid'
            )
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('academicprogram.id',$request->get('acadprogid'))
            ->where('gradelevel.deleted','0')
            ->get();

        foreach($gradelevels as $gradelevel)
        {

            $gradelevel->subjects = DB::table('subject_plot')
                ->select('subjects.*','subject_plot.syid','subject_plot.levelid','sy.sydesc')
                ->join('subjects','subject_plot.subjid','=','subjects.id')
                ->join('sy','subject_plot.syid','=','sy.id')
                ->where('subject_plot.deleted','0')
                ->where('subjects.deleted','0')
                ->where('subjects.inSF9','1')
                ->orderBy('subject_plot.plotsort','asc')
                ->where('subject_plot.syid', $currentschoolyear->id)
                ->where('subject_plot.levelid', $gradelevel->id)
                ->get();
                $gradelevel->subjects = collect($gradelevel->subjects)->unique('subjdesc');

        }
        

        $school = DB::table('schoolinfo')
            ->first();
         
            $schoolinfo = Db::table('schoolinfo')
                ->select(
                    'schoolinfo.schoolid',
                    'schoolinfo.schoolname',
                    'schoolinfo.abbreviation',
                    'schoolinfo.authorized',
                    'refcitymun.citymunDesc as division',
                    'schoolinfo.district',
                    'schoolinfo.districttext',
                    'schoolinfo.divisiontext',
                    'schoolinfo.regiontext',
                    'schoolinfo.address',
                    'schoolinfo.picurl',
                    'refregion.regDesc as region'
                )
                ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
                ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
                ->first();
   

        $studinfo = Db::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'studinfo.lrn',
                'studinfo.dob',
                'studinfo.gender',
                'studinfo.levelid',
                'studinfo.street',
                'studinfo.barangay',
                'studinfo.city',
                'studinfo.province',
                'studinfo.mothername',
                'studinfo.moccupation',
                'studinfo.fathername',
                'studinfo.foccupation',
                'studinfo.guardianname',
                'gradelevel.levelname',
                'sectionid as ensectid',
                'gradelevel.acadprogid',
                 'strandid'
                )
            ->leftJoin('gradelevel','studinfo.levelid','gradelevel.id')
            ->where('studinfo.id',$studentid)
            ->first();
            
        $studaddress = '';

        if($studinfo->street!=null)
        {
            $studaddress.=$studinfo->street.', ';
        }
        if($studinfo->barangay!=null)
        {
            $studaddress.=$studinfo->barangay.', ';
        }
        if($studinfo->city!=null)
        {
            $studaddress.=$studinfo->city.', ';
        }
        if($studinfo->province!=null)
        {
            $studaddress.=$studinfo->province.', ';
        }

        $studinfo->address = substr($studaddress,0,-2);

    
        $schoolyears = DB::table('enrolledstud')
            ->select(
                'enrolledstud.id',
                'enrolledstud.syid',
                'sy.sydesc',
                'academicprogram.id as acadprogid',
                'enrolledstud.levelid',
                'gradelevel.levelname',
                'enrolledstud.sectionid',
                'sections.sectionname as section'
                )
            ->join('gradelevel','enrolledstud.levelid','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
            ->join('sy','enrolledstud.syid','sy.id')
            ->join('sections','enrolledstud.sectionid','sections.id')
            ->where('enrolledstud.deleted','0')
            ->where('academicprogram.id',$acadprogid)
            ->where('enrolledstud.studid',$studentid)
            ->where('enrolledstud.studstatus','!=','0')
            ->distinct()
            ->orderByDesc('enrolledstud.levelid')
            ->get();

        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
        {
            $schoolyears = collect($schoolyears)->where('levelid', $request->get('selectedgradelevel'))->values();
        }
            
        if(count($schoolyears) != 0){
            
            $currentlevelid = (object)array(
                'syid'      => $schoolyears[0]->syid,
                'levelid'   => $schoolyears[0]->levelid,
                'levelname' => $schoolyears[0]->levelname
            );

        }

        else{

            $currentlevelid = (object)array(
                'syid' => $currentschoolyear->id,
                'levelid' => $studinfo->levelid,
                'levelname' => $studinfo->levelname
            );

        }

        $failingsubjectsArray = array();

        $gradelevelsenrolled = array();

        $autorecords = array();
        
        foreach($schoolyears as $sy){

            array_push($gradelevelsenrolled,(object)array(
                'levelid' => $sy->levelid,
                'levelname' => $sy->levelname
            ));

            $generalaverage = array();

            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
            {
                $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                if($grading_version->version == 'v2'){
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $sy->levelid,$studinfo->id,$sy->syid,null,null,$sy->sectionid);
                }else{
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,null,null,$sy->sectionid);
                }
                $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($sy->levelid);
                $grades = $studgrades;
                $grades = collect($grades)->sortBy('sortid')->values();
                $generalaverage = collect($grades)->where('id','G1')->values();
                unset($grades[count($grades)-1]);
                $grades = collect($grades)->where('isVisible','1')->values();
                // return $generalaverage;
            }
            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
            {
                // $grades = \App\Models\Principal\GenerateGrade::reportCardV5($studinfo, true, 'sf9');  
                // $gradesinMapeh = collect($grades)->where('inMAPEH','1')->sortBy('sortid');
                // $grades = collect($grades)->where('inMAPEH','0')->sortBy('sortid');
                // $grades = $grades->merge($gradesinMapeh);
                // $grades = collect($grades)->unique('subjectcode')->values();
                if($sy->syid == 2){
                    $currentSchoolYear = DB::table('sy')->where('id',$sy->syid)->first();
                    Session::put('schoolYear',$currentSchoolYear);
                    $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$studentid,null);
                    
                    
                    if($request->has('action'))
                    {
                        $studentInfo[0]->data = DB::table('studinfo')
                                            ->select('studinfo.*','studinfo.sectionid as ensectid','studinfo.levelid as enlevelid','gradelevel.levelname','acadprogid')
                                            ->where('studinfo.id',$studentid)
                        
                                            ->join('gradelevel','studinfo.levelid','=','gradelevel.id')->get();
                        $studentInfo[0]->count = 1;
                        $studentInfo[0]->data[0]->teacherfirstname = "";
                        $studentInfo[0]->data[0]->teachermiddlename = " ";
                        $studentInfo[0]->data[0]->teacherlastname = "";
                    }
            
                    if($studentInfo[0]->count == 0){
            
                        $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$studentid,null,5);
                        
                        $studentInfo = DB::table('enrolledstud')
                                            ->where('studid',$studentid)
                                            ->where('enrolledstud.deleted',0)
                                            ->select(
                                                'enrolledstud.sectionid as ensectid',
                                                'acadprogid',
                                                'enrolledstud.studid as id',
                                                'lastname',
                                                'firstname',
                                                'middlename',
                                                'lrn',
                                                'dob',
                                                'gender',
                                                'levelname',
                                                'sections.sectionname as ensectname'
                                                )
                                            ->join('gradelevel',function($join){
                                                $join->on('enrolledstud.levelid','=','gradelevel.id');
                                                $join->where('gradelevel.deleted',0);
                                            })
                                            ->join('sections',function($join){
                                                $join->on('enrolledstud.sectionid','=','sections.id');
                                                $join->where('sections.deleted',0);
                                            })
                                             ->join('studinfo',function($join){
                                                $join->on('enrolledstud.studid','=','studinfo.id');
                                                $join->where('gradelevel.deleted',0);
                                            })
                                            ->get();
                                            
                        $studentInfo = array((object)[
                                'data'=>   $studentInfo                             
                            ]);
                                            
                                            
                    }
                    $acad = $studentInfo[0]->data[0]->acadprogid;
                    $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($studentInfo[0]->data[0], true, 'sf9',2);    
                           
                    $grades = $gradesv4;
                
                    $grades = collect($grades)->sortBy('sortid')->values();
                  
                    $grades = collect($grades)->unique('subjectcode');
                    $grades = collect($grades)->unique('subjid');
                    // return $grades/;
                    
                }else{
                        $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,null,null,$sy->sectionid);
                   
                        $temp_grades = array();
                        $finalgrade = array();
                        foreach($studgrades as $item){
                            if($item->id == 'G1'){
                                array_push($finalgrade,$item);
                            }else{
                                if($item->strandid == $studinfo->strandid){
                                    array_push($temp_grades,$item);
                                }
                                if($item->strandid == null){
                                    array_push($temp_grades,$item);
                                }
                            }
                        }
                       
                        $studgrades = $temp_grades;
                        $grades = collect($studgrades)->sortBy('sortid')->values();
                        $grades = collect($grades)->unique('subjid');
                }
            }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
            {
                
                
                $strand = 0;
                $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
                Session::put('schoolYear', $schoolyear);
                $subjects = \App\Models\Principal\SPP_Subject::getSubject(null,null,null,$sy->sectionid,null,null,null,null,'sf9',$schoolyear->id)[0]->data;
                // if(count($subjects)>0)
                // {
                //     return $subjects;
                // }
                $temp_subject = array();
        
                foreach($subjects as $item){
                    array_push($temp_subject,$item);
                }
                
                if($sy->acadprogid != 5){
                    array_push($temp_subject, (object)[
                        'id'=>'MAPEH1',
                        'subjdesc'=>'MAPEH',
                        "inMAPEH"=> 0,
                        "teacherid"=> 14,
                        "inSF9"=> 1,
                        "inTLE"=> 0,
                        "subj_per"=> 0,
                        "subj_sortid"=> "2M0"
                    ]);
                }
                
                
                $subjects = $temp_subject;
                $studgrades = \App\Models\Grades\GradesData::student_grades_detail($sy->syid,null,$sy->sectionid,null,$studinfo->id, $sy->levelid,$strand,null,$subjects);
                // return $studgrades;
                // if($id == 682){
                //     return $studgrades;
                // }
                $studgrades =  \App\Models\Grades\GradesData::get_finalrating($studgrades,$sy->acadprogid);;
                $finalgrade =  \App\Models\Grades\GradesData::general_average($studgrades);
                $generalaverage =  \App\Models\Grades\GradesData::get_finalrating($finalgrade,$sy->acadprogid);
                
                $grades = $studgrades;
            }elseif(/*strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs' ||*/ strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm'){
                $subjects = \App\Models\Principal\SPP_Subject::getSubject(null,null,null,$studinfo->ensectid,null,null,null,null,'sf9',$sy->syid)[0]->data;
     
                //mcs
                if($sy->acadprogid != 5){
                    
                    $temp_subject = array();
        
                    foreach($subjects as $item){
                        array_push($temp_subject,$item);
                    }
        
                    array_push($temp_subject, (object)[
                        'id'=>'M1',
                        'subjdesc'=>'MAPEH',
                        "inMAPEH"=> 0,
                        "teacherid"=> 14,
                        "inSF9"=> 1,
                        "mapeh"=>0,
                        "inTLE"=>0,
                        "semid"=>1,
                        "subj_per"=> 0,
                        "subj_sortid"=> "3M0"
                    ]);
        
                    $subjects = $temp_subject;
        
                }
                $strand = 0;
                $studgrades = \App\Models\Grades\GradesData::student_grades_detail($sy->syid,null,$studinfo->ensectid,null,$studinfo->id, $studinfo->levelid,$strand,null,$subjects);
                $studgrades =  \App\Models\Grades\GradesData::get_finalrating($studgrades,$sy->acadprogid);
                $finalgrade =  \App\Models\Grades\GradesData::general_average($studgrades);
                $finalgrade =  \App\Models\Grades\GradesData::get_finalrating($finalgrade,$sy->acadprogid);
                $grades     =   $studgrades;

            }else{
                if(DB::table('schoolinfo')->first()->schoolid == '405308') //fmcma
                {
                    $attendance_setup = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($sy->syid);
                }
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,null,null,$sy->sectionid);
     
                $temp_grades = array();
                $generalaverage = array();
                foreach($studgrades as $item){
                    if($item->id == 'G1'){
                        array_push($generalaverage,$item);
                    }else{
                        if($item->strandid == $studinfo->strandid){
                            array_push($temp_grades,$item);
                        }
                        if($item->strandid == null){
                            array_push($temp_grades,$item);
                        }
                    }
                }
               
                if(DB::table('schoolinfo')->first()->schoolid == '405308') //fmcma
                {
                    if(count($generalaverage)>0)
                    {
                        $generalaverage[0]->actiontaken = strtolower($generalaverage[0]->actiontaken) == 'passed'? 'PROMOTED' : $generalaverage[0]->actiontaken;
                    }
                }
                $studgrades = $temp_grades;
                $grades = collect($studgrades)->unique('subjid');
                
                $grades = collect($grades)->sortBy('sortid')->values();
            }
            
            $attendancesummary = DB::table('sf10attendance')
                ->where('sf10attendance.studentid',$studentid)
                ->where('acadprogid','3')
                ->where('sydesc',$sy->sydesc)
                ->where('deleted','0')
                ->get();
                
            if(count($attendancesummary) == 0)
            {
                $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
                Session::put('schoolYear', $schoolyear);
                $attendancesummary = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($schoolyear->id, $sy->levelid);
                // return $schoolyear->id;
                foreach( $attendancesummary as $item){
                    $item->type = 1;
                    $item->numdays = $item->days;
                    
                    $sf2_setup = DB::table('sf2_setup')
                        ->where('month',$item->month)
                        ->where('year',$item->year)
                        ->where('sectionid',$sy->sectionid)
                        ->where('sf2_setup.deleted',0)
                        ->join('sf2_setupdates',function($join){
                            $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                            $join->where('sf2_setupdates.deleted',0);
                        })
                        ->select('dates')
                        ->get();

                    if(count($sf2_setup) == 0){

                        $sf2_setup = DB::table('sf2_setup')
                                    ->where('month',$item->month)
                                    ->where('year',$item->year)
                                    ->where('sectionid',$sy->sectionid)
                                    ->where('sf2_setup.deleted',0)
                                    ->join('sf2_setupdates',function($join){
                                        $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                        $join->where('sf2_setupdates.deleted',0);
                                    })
                                    ->select('dates')
                                    ->get();

                    }

                    $temp_days = array();

                    foreach($sf2_setup as $sf2_setup_item){
                    array_push($temp_days,$sf2_setup_item->dates);
                    }

                    $student_attendance = DB::table('studattendance')
                                        ->where('studid',$studinfo->id)
                                        ->where('deleted',0)
                                        ->whereIn('tdate',$temp_days)
                                        // ->where('syid',$syid)
                                        ->distinct('tdate')
                                        ->distinct()
                                        // ->select([
                                        //     'present',
                                        //     'absent',
                                        //     'tardy',
                                        //     'cc',
                                        //     'tdate'
                                        // ])
                                        ->get();

                    $student_attendance = collect($student_attendance)->unique('tdate')->values();

                    $item->present = collect($student_attendance)->where('present',1)->count() + collect($student_attendance)->where('tardy',1)->count() + collect($student_attendance)->where('cc',1)->count() + (collect($student_attendance)->where('presentam',1)->count() * 0.5) + (collect($student_attendance)->where('presentpm',1)->count() * 0.5) + (collect($student_attendance)->where('absentam',1)->count() * 0.5) + (collect($student_attendance)->where('absentpm',1)->count() * 0.5) + collect($student_attendance)->where('lateam',1)->count()  + collect($student_attendance)->where('latepm',1)->count() + collect($student_attendance)->where('ccam',1)->count() + collect($student_attendance)->where('ccpm',1)->count()  ;
                    $item->present = $item->present > $item->numdays ? $item->numdays : $item->present;
                    $item->absent = collect($student_attendance)->where('absent',1)->count() + (collect($student_attendance)->where('absentam',1)->count() * 0.5) + (collect($student_attendance)->where('absentpm',1)->count() * 0.5);
                    $item->numdayspresent = $item->present;
                    $item->numdaysabsent = $item->absent;
                    $item->monthstr = substr($item->monthdesc, 0, 3);
                }
                $attendancesummary = collect($attendancesummary)->sortBy('sort')->values()->all();
            }
            // $grades = collect($grades)->unique('id');
            if(count($grades)>0)
            {
                foreach($grades as $grade)
                {
                    if(!isset($grade->id))
                    {
                        $grade->id = $grade->subjid;
                    }
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                    {
                        // return collect($grade);
                        $grade->inMAPEH = $grade->mapeh;
                        $grade->inTLE = $grade->mapeh;
                        $grade->q1 = $grade->quarter1;
                        $grade->q2 = $grade->quarter2;
                        $grade->q3 = $grade->quarter3;
                        $grade->q4 = $grade->quarter4;
                        if(isset($grade->subjectcode))
                        {
                            $grade->subjdesc = $grade->subjectcode;
                        }
                    }

                    $complete = 0;
                    if($grade->q1 == 0)
                    {
                        $grade->q1 = null;
                    }else{
                        $complete+=1;;
                    }
                    if($grade->q2 == 0)
                    {
                        $grade->q2 = null;
                    }else{
                        $complete+=1;;
                    }
                    if($grade->q3 == 0)
                    {
                        $grade->q3 = null;
                    }else{
                        $complete+=1;;
                    }
                    if($grade->q4 == 0)
                    {
                        $grade->q4 = null;
                    }else{
                        $complete+=1;;
                    }
    
                    if($complete < 4)
                    {
                        $qg = null;
                        $remarks = null;
                    }else{
                        $qg = ($grade->q1 + $grade->q2 + $grade->q3 + $grade->q4) / 4;
                        if($qg>=75){
        
                            $remarks = "PASSED";
        
                        }elseif($qg == null){
        
                            $remarks = null;
        
                        }else{
                            $remarks = "FAILED";
                        }
                        
                        if($qg == 0)
                        {
                            $qg = null;
                            $remarks = null;
                        }
                    }
    
                    $grade->subjcode = null;

                    try{
                        $grade->subjtitle = $grade->subjdesc;
                    }catch(\Exception $error)
                    {
                        $grade->subjtitle = "";
                    }
                    $grade->quarter1 = $grade->q1;
                    $grade->quarter2 = $grade->q2;
                    $grade->quarter3 = $grade->q3;
                    $grade->quarter4 = $grade->q4;
                    $grade->finalrating = number_format($qg);
                    $grade->remarks = $remarks;
                }
            }
            $grades = collect($grades)->unique('id');
            
            
            $teachername = '';

            $getTeacher = Db::table('sectiondetail')
                ->select(
                    'teacher.title',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix'
                    )
                ->join('teacher','sectiondetail.teacherid','teacher.id')
                ->where('sectiondetail.sectionid',$sy->sectionid)
                ->where('sectiondetail.syid',$sy->syid)
                ->where('sectiondetail.deleted','0')
                ->first();

            if($getTeacher)
            {
                if($getTeacher->title!=null)
                {
                    $teachername.=$getTeacher->title.' ';
                }
                if($getTeacher->firstname!=null)
                {
                    $teachername.=$getTeacher->firstname.' ';
                }
                if($getTeacher->middlename!=null)
                {
                    $teachername.=$getTeacher->middlename[0].'. ';
                }
                if($getTeacher->lastname!=null)
                {
                    $teachername.=$getTeacher->lastname.' ';
                }
                if($getTeacher->suffix!=null)
                {
                    $teachername.=$getTeacher->suffix.' ';
                }        
            }

            $subjaddedforauto     = DB::table('sf10grades_subjauto')
                                    ->where('studid',$studentid)
                                    ->where('syid',$sy->syid)
                                    ->where('levelid',$sy->levelid)
                                    ->where('deleted','0')
                                    ->get();
            
            if(count($grades)>0)
            {
                array_push($autorecords, (object) array(
                        'id'                => null,
                        'syid'              => $sy->syid,
                        'sydesc'            => $sy->sydesc,
                        'levelid'           => $sy->levelid,
                        'levelname'         => $sy->levelname,
                        'sectionid'         => $sy->sectionid,
                        'sectionname'       => $sy->section,
                        'teachername'       => $teachername,
                        'schoolid'          => $schoolinfo->schoolid,
                        'schoolname'        => $schoolinfo->schoolname,
                        'schooladdress'     => $schoolinfo->address,
                        'schooldistrict'    => $schoolinfo->districttext != null ? $schoolinfo->districttext : $schoolinfo->district,
                        'schooldivision'    => $schoolinfo->divisiontext != null ? $schoolinfo->divisiontext : $schoolinfo->division,
                        'schoolregion'      => $schoolinfo->regiontext != null ? $schoolinfo->regiontext : $schoolinfo->region,
                        'type'              => 1,
                        'grades'            => $grades,
                        'generalaverage'    => $generalaverage,
                        'subjaddedforauto'  => $subjaddedforauto,
                        'attendance'        => $attendancesummary,
                        'credit_advance'   => null,
                        'credit_lack'      => null,
                        'noofyears'         => null,
                        'remedials'         => array(),
                        'remarks'         => array()
                ));
            }

        }

        
        if(count(collect($gradelevelsenrolled)->unique()) == 2){

            $completed = 1;

        }

        elseif(count(collect($gradelevelsenrolled)->unique()) < 2){

            $completed = 0;

        }


        $manualrecords = DB::table('sf10')
            ->select('sf10.id','sf10.syid','sf10.sydesc','sf10.levelid','gradelevel.levelname','sf10.sectionid','sf10.sectionname','sf10.teachername','sf10.schoolid','sf10.schoolname','sf10.schooladdress','sf10.schooldistrict','sf10.schooldivision','sf10.schoolregion','sf10.remarks','sf10.recordincharge','sf10.datechecked','sf10.credit_advance','sf10.credit_lack','sf10.noofyears')
            ->join('gradelevel','sf10.levelid','=','gradelevel.id')
            ->where('sf10.studid', $studentid)
            ->where('sf10.acadprogid', $acadprogid)
            ->where('sf10.deleted','0')
            ->get();

        if(count($manualrecords)>0)
        {
            foreach($manualrecords as $manualrecord)
            {
                $generalaverage = array();
                $manualrecord->type = 2;

                $grades = DB::table('sf10grades_elem')
                        ->where('headerid', $manualrecord->id)
                        ->where('deleted','0')
                        ->get();

                if(count($grades)>0)
                {
                    foreach($grades as $grade)
                    {
                        if(strtolower($grade->subjectname) == 'general average')
                        {
                            array_push($generalaverage, $grade);
                        }
                        
                        if($grade->q1 == 0)
                        {
                            $grade->q1 = null;
                        }
                        if($grade->q2 == 0)
                        {
                            $grade->q2 = null;
                        }
                        if($grade->q3 == 0)
                        {
                            $grade->q3 = null;
                        }
                        if($grade->q4 == 0)
                        {
                            $grade->q4 = null;
                        }
                        $grade->subjcode = null;
                        $grade->subjtitle = $grade->subjectname;
                        $grade->subjdesc = $grade->subjectname;
                        $grade->quarter1 = $grade->q1;
                        $grade->quarter2 = $grade->q2;
                        $grade->quarter3 = $grade->q3;
                        $grade->quarter4 = $grade->q4;
                    }
                }
                $remedialclasses = DB::table('sf10remedial_elem')
                        ->where('studid', $studentid)
                        ->where('levelid', $manualrecord->levelid)
                        ->where('sydesc', $manualrecord->sydesc)
                        ->where('deleted','0')
                        ->get();
                
                $attendance = DB::table('sf10attendance')
                    ->where('headerid',$manualrecord->id)
                    ->where('acadprogid','3')
                    ->where('deleted','0')
                    ->get();
                    
                $manualrecord->grades           = $grades;
                $manualrecord->generalaverage           = $generalaverage;
                $manualrecord->subjaddedforauto = array();
                $manualrecord->attendance       = $attendance;
                $manualrecord->remedials        = $remedialclasses;
            }
        }
        // return $manualrecords;
        $records = collect();
        $records = $records->merge($autorecords);
        $records = $records->merge($manualrecords);
        $footer = DB::table('sf10_footer_elem')
            ->where('studid', $studentid)
            ->where('deleted','0')
            ->first();
            
        if(!$footer)
        {
                $footer = (object)array(
                    'purpose'        =>  null,
                    'classadviser'                 =>  null,
                    'recordsincharge'            =>  null,
                    'lastsy'            =>  null,
                    'admissiontograde'            =>  null,
                    'copysentto'        =>  null,
                    'address'           =>  null
                );
        }
        $eligibility = DB::table('sf10eligibility_elem')
            ->where('studid', $studentid)
            ->where('deleted','0')
            ->first();

        if(!$eligibility)
        {
            $eligibility = (object)array(
                'kinderprogreport'  =>  0,
                'eccdchecklist'     =>  0,
                'kindergartencert'  =>  0,
                'schoolid'          =>  null,
                'schoolname'        =>  null,
                'schooladdress'     =>  null,
                'pept'              =>  0,
                'peptrating'        =>  null,
                'examdate'          =>  null,
                'centername'        =>  null,
                'centeraddress'     =>  null,
                'remarks'           =>  null,
                'specifyothers'     =>  null
            );
        }
        $eachlevelsignatories = DB::table('sf10bylevelsign')
            ->where('studid',$studentid)
            ->where('deleted','0')
            ->get();
        if($request->has('export'))
        {
            if(count($records)>0)
            {
                foreach($records as $eachkey=>$record)
                {
                    $record->withdata = 1;

                    if($acadprogid == 3)
                    {
                        $record->sortid = 0;
                        if(preg_replace('/\D+/', '', $record->levelname) == 1)
                        {
                            $record->sortid = 1;
                        }
                        elseif(preg_replace('/\D+/', '', $record->levelname) == 2)
                        {
                            $record->sortid = 2;
                        }
                        elseif(preg_replace('/\D+/', '', $record->levelname) == 3)
                        {
                            $record->sortid = 3;
                        }
                        elseif(preg_replace('/\D+/', '', $record->levelname) == 4)
                        {
                            $record->sortid = 4;
                        }
                        elseif(preg_replace('/\D+/', '', $record->levelname) == 5)
                        {
                            $record->sortid = 5;
                        }
                        elseif(preg_replace('/\D+/', '', $record->levelname) == 6)
                        {
                            $record->sortid = 6;
                        }
                    }else{
                        $record->sortid = $eachkey+1;
                    }
                    
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                    {
                        $record->noofgrades = count(collect($record->grades)->where('subjdesc','!=','General Average')) + count($record->subjaddedforauto);
                    }else{
                        $record->noofgrades = count(collect($record->grades)->where('subjdesc','!=','General Average'));
                    }
                }
            }
            // return $records;
            $withnodata = array();
            if($acadprogid == 3)
            {
                for($x = 1; $x <= 8; $x++)
                {
                    if(collect($records)->where('sortid',$x)->count() == 0)
                    {
                        if($x == 1)
                        {
                            $recordsortid = 1;
                            $recordlevelid = 1;
                            $recordlevelname = 'GRADE 1';
                        }
                        elseif($x == 2)
                        {
                            $recordsortid = 2;
                            $recordlevelid = 5;
                            $recordlevelname =  'GRADE 2';
                        }
                        elseif($x == 3)
                        {
                            $recordsortid = 3;
                            $recordlevelid = 6;
                            $recordlevelname =  'GRADE 3';
                        }
                        elseif($x == 4)
                        {
                            $recordsortid = 4;
                            $recordlevelid = 7;
                            $recordlevelname =  'GRADE 4';
                        }
                        elseif($x == 5)
                        {
                            $recordsortid = 5;
                            $recordlevelid = 16;
                            $recordlevelname =  'GRADE 5';
                        }
                        elseif($x == 6)
                        {
                            $recordsortid = 6;
                            $recordlevelid = 9;
                            $recordlevelname =  'GRADE 6';
                        }
                        elseif($x == 7)
                        {
                            $recordsortid = 7;
                            $recordlevelid = 0;
                            $recordlevelname =  '';
                        }
                        elseif($x == 8)
                        {
                            $recordsortid = 8;
                            $recordlevelid = 0;
                            $recordlevelname =  '';
                        }
                        array_push($withnodata, (object)array(
                            'id'                => null,
                            'syid'              => null,
                            'sydesc'            => null,
                            'levelid'           => $recordlevelid,
                            'levelname'         => $recordlevelname,
                            'sectionid'         => null,
                            'sectionname'       => null,
                            'teachername'       => null,
                            'schoolid'          => null,
                            'schoolname'        => null,
                            'schooladdress'     => null,
                            'schooldistrict'    => null,
                            'schooldivision'    => null,
                            'schoolregion'      => null,
                            'credit_advance'   => null,
                            'credit_lack'      => null,
                            'noofyears'         => null,
                            'type'              => 2,
                            'grades'            => array(),
                            'subjaddedforauto'  => array(),
                            'generalaverage'  => array(),
                            'attendance'        => array(),
                            'noofgrades'        => 0,
                            'remedials'         => array(),
                            'sortid'            => $x,
                            'withdata'          => 0,
                        ));
                    }
                }
            }else{
                for($x = 1; $x <= 4; $x++)
                {
                    if(collect($records)->where('sortid',$x)->count() == 0)
                    {
                        if($x == 1)
                        {
                            $recordsortid = 1;
                        }
                        elseif($x == 2)
                        {
                            $recordsortid = 2;
                        }
                        elseif($x == 3)
                        {
                            $recordsortid = 3;
                        }else{
                            $recordsortid = 4;
                        }
                        $recordlevelid = $gradelevels[($x-1)]->id ?? '';
                        $recordlevelname = $gradelevels[($x-1)]->levelname ?? '';
                        array_push($withnodata, (object)array(
                            // 'sydesc'=>$schoolyears[0]->syid
                            'id'                => null,
                            'syid'              => null,
                            'sydesc'            => null,
                            'levelid'           => $recordlevelid,
                            'levelname'         => $recordlevelname,
                            'sectionid'         => null,
                            'sectionname'       => null,
                            'teachername'       => null,
                            'schoolid'          => null,
                            'schoolname'        => null,
                            'schooladdress'     => null,
                            'schooldistrict'    => null,
                            'schooldivision'    => null,
                            'schoolregion'      => null,
                            'credit_advance'   => null,
                            'credit_lack'      => null,
                            'noofyears'         => null,
                            'type'              => 2,
                            'grades'            => array(),
                            'subjaddedforauto'  => array(),
                            'generalaverage'  => array(),
                            'attendance'        => array(),
                            'noofgrades'        => 0,
                            'remedials'         => array(),
                            'sortid'            => $x,
                            'withdata'          => 0,
                        ));
                    }
                }
            }
            
            $records = $records->merge($withnodata);
            $maxgradecount = collect($records)->pluck('noofgrades')->max();
            
            if($maxgradecount == 0)
            {
                $maxgradecount = 12;
            }
            $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
            // return $records;
            $records = array_chunk($records, 2);
            
            $schoolinfo = Db::table('schoolinfo')
                ->select(
                    'schoolinfo.schoolid',
                    'schoolinfo.abbreviation',
                    'schoolinfo.schoolname',
                    'schoolinfo.authorized',
                    'refcitymun.citymunDesc as division',
                    'schoolinfo.district',
                    'schoolinfo.districttext',
                    'schoolinfo.divisiontext',
                    'schoolinfo.regiontext',
                    'schoolinfo.address',
                    'schoolinfo.picurl',
                    'refregion.regDesc as region'
                )
                ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
                ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
                ->first();
            if($request->get('exporttype') == 'pdf')
            {
                $subjects = DB::table('subject_plot')
                    ->select('subjects.id','subjcode','subjects.subjdesc','subject_plot.strandid','subject_plot.plotsort','subject_plot.semid','subject_plot.syid','subject_plot.levelid','subject_plot.strandid','inMAPEH')
                    ->join('subjects','subject_plot.subjid','=','subjects.id')
                    ->where('subjects.inSF9', 1)
                    ->where('subjects.deleted', 0)
                    ->where('subject_plot.levelid', '!=','14')
                    ->where('subject_plot.levelid', '!=','15')
                    ->where('subject_plot.deleted', 0)
                    ->orderBy('subject_plot.plotsort','asc')
                    ->get();  
                    
                $format = $request->get('format');
                $template = 'registrar/forms/deped/form10_elem';
                if($request->has('format'))
                {
                    if($format == 'deped')
                    {
                        $template = 'registrar/forms/deped/form10_elem';
                    }elseif($format == 'deped-2')//old
                    {
                        // return 'hello';
                        $template = 'registrar/pdf/pdf_schoolform10_elem';
                    }elseif($format == 'school'){
                        $template = 'registrar/pdf/pdf_schoolform10_junior';
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                        {
                            $template = 'registrar/pdf/pdf_schoolform10_juniorsjaes';
                        }
                        elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                        {
                            $template = 'registrar/pdf/pdf_schoolform10_elemdcc';
                        }else{
                            
                            $template = 'registrar/pdf/pdf_schoolform10_elem';
                        }
                    }
                }
                if($request->has('papersize'))
                {
                    $papersize = $request->get('papersize');
                }else{
                    $papersize = null;
                }
                
                $eachgradesignatories = DB::table('sf10bylevelsign')
                    ->where('studid',$studentid)
                    ->where('semid', 0)            
                    ->where('deleted','0')
                    ->get();
                
                $pdf = PDF::loadview($template,compact('eligibility','studinfo','records','maxgradecount','footer','format','acadprogid','schoolinfo','subjects','gradelevels','papersize','eachgradesignatories')); 
                return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                //     // return $subjects;
                // // $subjects = collect($subjects)->unique('subjdesc')->values();
                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                // {
                //     $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_juniorsjaes',compact('eligibility','studinfo','records','maxgradecount','footer','format','acadprogid')); 
                //     return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                // }
                // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                // {
                //     // return $records;
                //     $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_elemdcc',compact('eligibility','studinfo','records','maxgradecount','footer','format','acadprogid','schoolinfo','subjects','gradelevels')); 
                //     return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                // }
                // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak')
                // {
                //     // return $records;
                //     $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_elem',compact('eligibility','studinfo','records','maxgradecount','footer','format','acadprogid','schoolinfo','subjects','gradelevels')); 
                //     return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                // }else{
                //     // return $records;
                //     $pdf = PDF::loadview('registrar/forms/deped/form10_elem',compact('eligibility','studinfo','records','maxgradecount','footer','schoolinfo','gradelevels'));; 
                //     // $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_elem',compact('eligibility','studinfo','records','maxgradecount','footer','schoolinfo'));; 
                //     return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                // }

                // // if(strtolower($schoolinfo->abbreviation) == 'sihs')
                // // {
                // //     $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_elemlhs',compact('eligibility','studinfo','records','maxgradecount','footer','schoolinfo'))->setPaper('legal','portrait');; 
                // //     return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                // // }else{
                //     $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_elem',compact('eligibility','studinfo','records','maxgradecount','footer','schoolinfo'))->setPaper('legal','portrait');; 
                //     return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                // // }
            }else{
                $inputFileType = 'Xlsx';
                if(strtolower($schoolinfo->abbreviation) == 'hcb')
                {
                    $inputFileName = base_path().'/public/excelformats/hcb/sf10_es.xlsx';
                }else{
                    if(DB::table('schoolinfo')->first()->schoolid == '405308')
                    {
                        $inputFileName = base_path().'/public/excelformats/fmcma/sf10_es.xlsx';
                    }else{
                        $inputFileName = base_path().'/public/excelformats/sf10_es.xlsx';
                    }
                }
                // $sheetname = 'Front';

                /**  Create a new Reader of the type defined in $inputFileType  **/
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                /**  Advise the Reader of which WorkSheets we want to load  **/
                $reader->setLoadAllSheets();
                /**  Load $inputFileName to a Spreadsheet Object  **/
                $spreadsheet = $reader->load($inputFileName);
                
                $sheet = $spreadsheet->getSheet(0);

                if(strtolower($schoolinfo->abbreviation) == 'hcb')
                {
                    $sheet->setCellValue('D8', $studinfo->lastname);
                    $sheet->setCellValue('L8', $studinfo->firstname);
                    $sheet->setCellValue('V8', $studinfo->suffix);
                    $sheet->setCellValue('AB8', $studinfo->middlename);

                    
                    $sheet->setCellValue('H9', $studinfo->lrn);
                    $sheet->getStyle('H9')->getNumberFormat()->setFormatCode('0');
                    $sheet->setCellValue('U9', date('m/d/Y', strtotime($studinfo->dob)));
                    $sheet->setCellValue('AB9', $studinfo->gender);
                    if($eligibility->kinderprogreport == 1)
                    {
                        $sheet->setCellValue('I13', '/');
                    }
                    if($eligibility->eccdchecklist == 1)
                    {
                        $sheet->setCellValue('Q13', '/');
                    }
                    if($eligibility->kindergartencert == 1)
                    {
                        $sheet->setCellValue('W13', '/');
                    }

                    $sheet->setCellValue('E14', $eligibility->schoolname);
                    $sheet->setCellValue('Q14', $eligibility->schoolid);
                    $sheet->setCellValue('Y14', $eligibility->schooladdress);

                    if($eligibility->pept == 1)
                    {
                        $sheet->setCellValue('B17', '/');
                    }
                    if($eligibility->peptrating == 1)
                    {
                        $sheet->setCellValue('H17', '/');
                    }
                    $sheet->setCellValue('T17', $eligibility->examdate);
                    $sheet->setCellValue('AC17', $eligibility->specifyothers);

                    $sheet->setCellValue('J18', $eligibility->centername);
                    $sheet->setCellValue('W18', $eligibility->remarks);

                    $startcellno = 22;

                    // F I R S T

                    $records_firstrow = $records[0];
                    
                    $sheet->setCellValue('C'.$startcellno, $records_firstrow[0]->schoolname);
                    $sheet->setCellValue('M'.$startcellno, $records_firstrow[0]->schoolid);
                    $sheet->setCellValue('S'.$startcellno, $records_firstrow[1]->schoolname);
                    $sheet->setCellValue('AB'.$startcellno, $records_firstrow[1]->schoolid);

                    $startcellno += 1;
                    
                    $sheet->setCellValue('C'.$startcellno, $records_firstrow[0]->schooldistrict);
                    $sheet->setCellValue('H'.$startcellno, $records_firstrow[0]->schooldivision);
                    $sheet->setCellValue('N'.$startcellno, $records_firstrow[0]->schoolregion);
                    $sheet->setCellValue('S'.$startcellno, $records_firstrow[1]->schooldistrict);
                    $sheet->setCellValue('X'.$startcellno, $records_firstrow[1]->schooldivision);
                    $sheet->setCellValue('AD'.$startcellno, $records_firstrow[1]->schoolregion);

                    $startcellno += 1;

                    $sheet->setCellValue('E'.$startcellno, preg_replace('/\D+/', '', $records_firstrow[0]->levelname));
                    $sheet->setCellValue('I'.$startcellno,  $records_firstrow[0]->sectionname);
                    $sheet->setCellValue('N'.$startcellno,  $records_firstrow[0]->sydesc);
                    $sheet->setCellValue('U'.$startcellno, preg_replace('/\D+/', '', $records_firstrow[1]->levelname));
                    $sheet->setCellValue('Y'.$startcellno,  $records_firstrow[1]->sectionname);
                    $sheet->setCellValue('AD'.$startcellno,  $records_firstrow[1]->sydesc);

                    $startcellno += 1;

                    $sheet->setCellValue('D'.$startcellno, $records_firstrow[0]->teachername);
                    $sheet->setCellValue('T'.$startcellno, $records_firstrow[1]->teachername);
                    
                    $startcellno += 4;
                    
                    $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                    
                    if(count($records_firstrow[0]->grades) == 0)
                    {
                        $firsttable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $firsttable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('A'.$x.':C'.$x);
                            $sheet->mergeCells('D'.$x.':E'.$x);
                            $sheet->mergeCells('F'.$x.':G'.$x);
                            $sheet->mergeCells('H'.$x.':I'.$x);
                            $sheet->mergeCells('J'.$x.':K'.$x);
                            $sheet->mergeCells('L'.$x.':M'.$x);
                            $sheet->mergeCells('N'.$x.':O'.$x);
                        }
                    }else{
                        $firsttable_cellno = $startcellno;
                        foreach($records_firstrow[0]->grades as $firstgrades)
                        {
                            $inmapeh = '';
                            if($firstgrades->inMAPEH == 1)
                            {
                                $inmapeh = '     ';
                            }
                            $sheet->mergeCells('A'.$firsttable_cellno.':C'.$firsttable_cellno);
                            $sheet->setCellValue('A'.$firsttable_cellno, $inmapeh.$firstgrades->subjdesc);
                            $sheet->mergeCells('D'.$firsttable_cellno.':E'.$firsttable_cellno);
                            $sheet->setCellValue('D'.$firsttable_cellno, $firstgrades->q1);
                            $sheet->mergeCells('F'.$firsttable_cellno.':G'.$firsttable_cellno);
                            $sheet->setCellValue('F'.$firsttable_cellno, $firstgrades->q2);
                            $sheet->mergeCells('H'.$firsttable_cellno.':I'.$firsttable_cellno);
                            $sheet->setCellValue('H'.$firsttable_cellno, $firstgrades->q3);
                            $sheet->mergeCells('J'.$firsttable_cellno.':K'.$firsttable_cellno);
                            $sheet->setCellValue('J'.$firsttable_cellno, $firstgrades->q4);
                            $sheet->mergeCells('L'.$firsttable_cellno.':M'.$firsttable_cellno);
                            $sheet->setCellValue('L'.$firsttable_cellno, $firstgrades->finalrating);
                            $sheet->mergeCells('N'.$firsttable_cellno.':O'.$firsttable_cellno);
                            $sheet->setCellValue('N'.$firsttable_cellno, $firstgrades->remarks);
                            $firsttable_cellno+=1;
                        }
                        
                        $genave = number_format(collect($records_firstrow[0]->grades)->where('inMAPEH','0')->avg('finalrating'));
                        $sheet->setCellValue('L'.$firsttable_cellno, $genave);

                        if($genave>=75)
                        {
                            $sheet->setCellValue('N'.$firsttable_cellno, 'PASSED');
                        }elseif($genave<75 && $genave!= 0){
                            $sheet->setCellValue('N'.$firsttable_cellno, 'FAILED');
                        }
                    }
                    
                    if(count($records_firstrow[1]->grades) == 0)
                    {
                        $secondtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        
                        for($x = $secondtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('Q'.$x.':S'.$x);
                            $sheet->mergeCells('T'.$x.':U'.$x);
                            $sheet->mergeCells('V'.$x.':W'.$x);
                            $sheet->mergeCells('X'.$x.':Y'.$x);
                            $sheet->mergeCells('Z'.$x.':AA'.$x);
                            $sheet->mergeCells('AB'.$x.':AC'.$x);
                            $sheet->mergeCells('AD'.$x.':AE'.$x);
                        }
                    }else{
                        $secondtable_cellno = $startcellno;
                        foreach($records_firstrow[1]->grades as $secondgrades)
                        {
                            $inmapeh = '';
                            if($secondgrades->inMAPEH == 1)
                            {
                                $inmapeh = '     ';
                            }
                            $sheet->mergeCells('Q'.$secondtable_cellno.':S'.$secondtable_cellno);
                            $sheet->setCellValue('Q'.$secondtable_cellno, $inmapeh.$secondgrades->subjdesc);
                            $sheet->mergeCells('T'.$secondtable_cellno.':U'.$secondtable_cellno);
                            $sheet->setCellValue('T'.$secondtable_cellno, $secondgrades->q1);
                            $sheet->mergeCells('V'.$secondtable_cellno.':W'.$secondtable_cellno);
                            $sheet->setCellValue('V'.$secondtable_cellno, $secondgrades->q2);
                            $sheet->mergeCells('X'.$secondtable_cellno.':Y'.$secondtable_cellno);
                            $sheet->setCellValue('X'.$secondtable_cellno, $secondgrades->q3);
                            $sheet->mergeCells('Z'.$secondtable_cellno.':AA'.$secondtable_cellno);
                            $sheet->setCellValue('Z'.$secondtable_cellno, $secondgrades->q4);
                            $sheet->mergeCells('AB'.$secondtable_cellno.':AC'.$secondtable_cellno);
                            $sheet->setCellValue('AB'.$secondtable_cellno, $secondgrades->finalrating);
                            $sheet->mergeCells('AD'.$secondtable_cellno.':AE'.$secondtable_cellno);
                            $sheet->setCellValue('AD'.$secondtable_cellno, $secondgrades->remarks);
                            $secondtable_cellno+=1;
                        }
                        $genave = number_format(collect($records_firstrow[1]->grades)->where('inMAPEH','0')->avg('finalrating'));
                        $sheet->setCellValue('AB'.$secondtable_cellno, $genave);

                        if($genave>=75)
                        {
                            $sheet->setCellValue('AD'.$secondtable_cellno, 'PASSED');
                        }elseif($genave<75 && $genave!= 0){
                            $sheet->setCellValue('AD'.$secondtable_cellno, 'FAILED');
                        }
                    }

                    $startcellno += $maxgradecount; // general average

                    $startcellno += 2; // attendance

                    if(count($records_firstrow[0]->attendance) > 0)
                    {
                        $sheet->setCellValue('D'.$startcellno, collect($records_firstrow[0]->attendance)->sum('days'));
                        $sheet->setCellValue('I'.$startcellno, collect($records_firstrow[0]->attendance)->sum('present'));
                    }
                    
                    if(count($records_firstrow[1]->attendance) > 0)
                    {
                        $sheet->setCellValue('T'.$startcellno, collect($records_firstrow[1]->attendance)->sum('days'));
                        $sheet->setCellValue('Y'.$startcellno, collect($records_firstrow[1]->attendance)->sum('present'));
                    }

                    $startcellno += 6; 

                    // S E C O N D

                    $records_secondrow = $records[1];
                    
                    $sheet->setCellValue('C'.$startcellno, $records_secondrow[0]->schoolname);
                    $sheet->setCellValue('M'.$startcellno, $records_secondrow[0]->schoolid);
                    $sheet->setCellValue('S'.$startcellno, $records_secondrow[1]->schoolname);
                    $sheet->setCellValue('AB'.$startcellno, $records_secondrow[1]->schoolid);

                    $startcellno += 1;
                    
                    $sheet->setCellValue('C'.$startcellno, $records_secondrow[0]->schooldistrict);
                    $sheet->setCellValue('H'.$startcellno, $records_secondrow[0]->schooldivision);
                    $sheet->setCellValue('N'.$startcellno, $records_secondrow[0]->schoolregion);
                    $sheet->setCellValue('S'.$startcellno, $records_secondrow[1]->schooldistrict);
                    $sheet->setCellValue('X'.$startcellno, $records_secondrow[1]->schooldivision);
                    $sheet->setCellValue('AD'.$startcellno, $records_secondrow[1]->schoolregion);

                    $startcellno += 1;

                    $sheet->setCellValue('E'.$startcellno, preg_replace('/\D+/', '', $records_secondrow[0]->levelname));
                    $sheet->setCellValue('I'.$startcellno,  $records_secondrow[0]->sectionname);
                    $sheet->setCellValue('N'.$startcellno,  $records_secondrow[0]->sydesc);
                    $sheet->setCellValue('U'.$startcellno, preg_replace('/\D+/', '', $records_secondrow[1]->levelname));
                    $sheet->setCellValue('Y'.$startcellno,  $records_secondrow[1]->sectionname);
                    $sheet->setCellValue('AD'.$startcellno,  $records_secondrow[1]->sydesc);

                    $startcellno += 1;

                    $sheet->setCellValue('D'.$startcellno, $records_secondrow[0]->teachername);
                    $sheet->setCellValue('T'.$startcellno, $records_secondrow[1]->teachername);
                    
                    $startcellno += 4;
                    
                    $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                    
                    if(count($records_secondrow[0]->grades) == 0)
                    {
                        $thirdtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $thirdtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('A'.$x.':C'.$x);
                            $sheet->mergeCells('D'.$x.':E'.$x);
                            $sheet->mergeCells('F'.$x.':G'.$x);
                            $sheet->mergeCells('H'.$x.':I'.$x);
                            $sheet->mergeCells('J'.$x.':K'.$x);
                            $sheet->mergeCells('L'.$x.':M'.$x);
                            $sheet->mergeCells('N'.$x.':O'.$x);
                        }
                    }else{
                        $thirdtable_cellno = $startcellno;
                        foreach($records_secondrow[0]->grades as $thirdgrades)
                        {
                            $inmapeh = '';
                            if($thirdgrades->inMAPEH == 1)
                            {
                                $inmapeh = '     ';
                            }
                            $sheet->mergeCells('A'.$thirdtable_cellno.':C'.$thirdtable_cellno);
                            $sheet->setCellValue('A'.$thirdtable_cellno, $inmapeh.$thirdgrades->subjdesc);
                            $sheet->mergeCells('D'.$thirdtable_cellno.':E'.$thirdtable_cellno);
                            $sheet->setCellValue('D'.$thirdtable_cellno, $thirdgrades->q1);
                            $sheet->mergeCells('F'.$thirdtable_cellno.':G'.$thirdtable_cellno);
                            $sheet->setCellValue('F'.$thirdtable_cellno, $thirdgrades->q2);
                            $sheet->mergeCells('H'.$thirdtable_cellno.':I'.$thirdtable_cellno);
                            $sheet->setCellValue('H'.$thirdtable_cellno, $thirdgrades->q3);
                            $sheet->mergeCells('J'.$thirdtable_cellno.':K'.$thirdtable_cellno);
                            $sheet->setCellValue('J'.$thirdtable_cellno, $thirdgrades->q4);
                            $sheet->mergeCells('L'.$thirdtable_cellno.':M'.$thirdtable_cellno);
                            $sheet->setCellValue('L'.$thirdtable_cellno, $thirdgrades->finalrating);
                            $sheet->mergeCells('N'.$thirdtable_cellno.':O'.$thirdtable_cellno);
                            $sheet->setCellValue('N'.$thirdtable_cellno, $thirdgrades->remarks);
                            $thirdtable_cellno+=1;
                        }
                        $genave = number_format(collect($records_secondrow[0]->grades)->where('inMAPEH','0')->avg('finalrating'));
                        $sheet->setCellValue('L'.$thirdtable_cellno, $genave);

                        if($genave>=75)
                        {
                            $sheet->setCellValue('N'.$thirdtable_cellno, 'PASSED');
                        }elseif($genave<75 && $genave!= 0){
                            $sheet->setCellValue('N'.$thirdtable_cellno, 'FAILED');
                        }
                    }
                    
                    if(count($records_secondrow[1]->grades) == 0)
                    {
                        $fourthtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        
                        for($x = $fourthtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('Q'.$x.':S'.$x);
                            $sheet->mergeCells('T'.$x.':U'.$x);
                            $sheet->mergeCells('V'.$x.':W'.$x);
                            $sheet->mergeCells('X'.$x.':Y'.$x);
                            $sheet->mergeCells('Z'.$x.':AA'.$x);
                            $sheet->mergeCells('AB'.$x.':AC'.$x);
                            $sheet->mergeCells('AD'.$x.':AE'.$x);
                        }
                    }else{
                        $fourthtable_cellno = $startcellno;
                        foreach($records_secondrow[1]->grades as $fourthgrades)
                        {
                            $inmapeh = '';
                            if($fourthgrades->inMAPEH == 1)
                            {
                                $inmapeh = '     ';
                            }
                            $sheet->mergeCells('Q'.$fourthtable_cellno.':S'.$fourthtable_cellno);
                            $sheet->setCellValue('Q'.$fourthtable_cellno, $inmapeh.$fourthgrades->subjdesc);
                            $sheet->mergeCells('T'.$fourthtable_cellno.':U'.$fourthtable_cellno);
                            $sheet->setCellValue('T'.$fourthtable_cellno, $fourthgrades->q1);
                            $sheet->mergeCells('V'.$fourthtable_cellno.':W'.$fourthtable_cellno);
                            $sheet->setCellValue('V'.$fourthtable_cellno, $fourthgrades->q2);
                            $sheet->mergeCells('X'.$fourthtable_cellno.':Y'.$fourthtable_cellno);
                            $sheet->setCellValue('X'.$fourthtable_cellno, $fourthgrades->q3);
                            $sheet->mergeCells('Z'.$fourthtable_cellno.':AA'.$fourthtable_cellno);
                            $sheet->setCellValue('Z'.$fourthtable_cellno, $fourthgrades->q4);
                            $sheet->mergeCells('AB'.$fourthtable_cellno.':AC'.$fourthtable_cellno);
                            $sheet->setCellValue('AB'.$fourthtable_cellno, $fourthgrades->finalrating);
                            $sheet->mergeCells('AD'.$fourthtable_cellno.':AE'.$fourthtable_cellno);
                            $sheet->setCellValue('AD'.$fourthtable_cellno, $fourthgrades->remarks);
                            $fourthtable_cellno+=1;
                        }
                        $genave = number_format(collect($records_secondrow[1]->grades)->where('inMAPEH','0')->avg('finalrating'));
                        $sheet->setCellValue('AB'.$fourthtable_cellno, $genave);

                        if($genave>=75)
                        {
                            $sheet->setCellValue('AD'.$fourthtable_cellno, 'PASSED');
                        }elseif($genave<75 && $genave!= 0){
                            $sheet->setCellValue('AD'.$fourthtable_cellno, 'FAILED');
                        }
                    }
                    
                    $startcellno += $maxgradecount; // general average

                    $startcellno += 2; // attendance

                    if(count($records_secondrow[0]->attendance) > 0)
                    {
                        $sheet->setCellValue('D'.$startcellno, collect($records_secondrow[0]->attendance)->sum('days'));
                        $sheet->setCellValue('I'.$startcellno, collect($records_secondrow[0]->attendance)->sum('present'));
                    }
                    
                    if(count($records_secondrow[1]->attendance) > 0)
                    {
                        $sheet->setCellValue('T'.$startcellno, collect($records_secondrow[1]->attendance)->sum('days'));
                        $sheet->setCellValue('Y'.$startcellno, collect($records_secondrow[1]->attendance)->sum('present'));
                    }

                    $startcellno += 6; 

                    // T H I R D

                    $records_thirdrow = $records[2];
                    
                    $sheet->setCellValue('C'.$startcellno, $records_thirdrow[0]->schoolname);
                    $sheet->setCellValue('M'.$startcellno, $records_thirdrow[0]->schoolid);
                    $sheet->setCellValue('S'.$startcellno, $records_thirdrow[1]->schoolname);
                    $sheet->setCellValue('AB'.$startcellno, $records_thirdrow[1]->schoolid);

                    $startcellno += 1;
                    
                    $sheet->setCellValue('C'.$startcellno, $records_thirdrow[0]->schooldistrict);
                    $sheet->setCellValue('H'.$startcellno, $records_thirdrow[0]->schooldivision);
                    $sheet->setCellValue('N'.$startcellno, $records_thirdrow[0]->schoolregion);
                    $sheet->setCellValue('S'.$startcellno, $records_thirdrow[1]->schooldistrict);
                    $sheet->setCellValue('X'.$startcellno, $records_thirdrow[1]->schooldivision);
                    $sheet->setCellValue('AD'.$startcellno, $records_thirdrow[1]->schoolregion);

                    $startcellno += 1;

                    $sheet->setCellValue('E'.$startcellno, preg_replace('/\D+/', '', $records_thirdrow[0]->levelname));
                    $sheet->setCellValue('I'.$startcellno,  $records_thirdrow[0]->sectionname);
                    $sheet->setCellValue('N'.$startcellno,  $records_thirdrow[0]->sydesc);
                    $sheet->setCellValue('U'.$startcellno, preg_replace('/\D+/', '', $records_thirdrow[1]->levelname));
                    $sheet->setCellValue('Y'.$startcellno,  $records_thirdrow[1]->sectionname);
                    $sheet->setCellValue('AD'.$startcellno,  $records_thirdrow[1]->sydesc);

                    $startcellno += 1;

                    $sheet->setCellValue('D'.$startcellno, $records_thirdrow[0]->teachername);
                    $sheet->setCellValue('T'.$startcellno, $records_thirdrow[1]->teachername);
                    
                    $startcellno += 4;
                    
                    $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                    
                    if(count($records_thirdrow[0]->grades) == 0)
                    {
                        $fifthtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $fifthtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('A'.$x.':C'.$x);
                            $sheet->mergeCells('D'.$x.':E'.$x);
                            $sheet->mergeCells('F'.$x.':G'.$x);
                            $sheet->mergeCells('H'.$x.':I'.$x);
                            $sheet->mergeCells('J'.$x.':K'.$x);
                            $sheet->mergeCells('L'.$x.':M'.$x);
                            $sheet->mergeCells('N'.$x.':O'.$x);
                        }
                    }else{
                        $fifthtable_cellno = $startcellno;
                        foreach($records_thirdrow[0]->grades as $fifthgrades)
                        {
                            $inmapeh = '';
                            if($fifthgrades->inMAPEH == 1)
                            {
                                $inmapeh = '     ';
                            }
                            $sheet->mergeCells('A'.$fifthtable_cellno.':C'.$fifthtable_cellno);
                            $sheet->setCellValue('A'.$fifthtable_cellno, $inmapeh.$fifthgrades->subjdesc);
                            $sheet->mergeCells('D'.$fifthtable_cellno.':E'.$fifthtable_cellno);
                            $sheet->setCellValue('D'.$fifthtable_cellno, $fifthgrades->q1);
                            $sheet->mergeCells('F'.$fifthtable_cellno.':G'.$fifthtable_cellno);
                            $sheet->setCellValue('F'.$fifthtable_cellno, $fifthgrades->q2);
                            $sheet->mergeCells('H'.$fifthtable_cellno.':I'.$fifthtable_cellno);
                            $sheet->setCellValue('H'.$fifthtable_cellno, $fifthgrades->q3);
                            $sheet->mergeCells('J'.$fifthtable_cellno.':K'.$fifthtable_cellno);
                            $sheet->setCellValue('J'.$fifthtable_cellno, $fifthgrades->q4);
                            $sheet->mergeCells('L'.$fifthtable_cellno.':M'.$fifthtable_cellno);
                            $sheet->setCellValue('L'.$fifthtable_cellno, $fifthgrades->finalrating);
                            $sheet->mergeCells('N'.$fifthtable_cellno.':O'.$fifthtable_cellno);
                            $sheet->setCellValue('N'.$fifthtable_cellno, $fifthgrades->remarks);
                            $fifthtable_cellno+=1;
                        }
                        $genave = number_format(collect($records_thirdrow[0]->grades)->where('inMAPEH','0')->avg('finalrating'));
                        $sheet->setCellValue('L'.$fifthtable_cellno, $genave);

                        if($genave>=75)
                        {
                            $sheet->setCellValue('N'.$fifthtable_cellno, 'PASSED');
                        }elseif($genave<75 && $genave!= 0){
                            $sheet->setCellValue('N'.$fifthtable_cellno, 'FAILED');
                        }
                    }
                    
                    if(count($records_thirdrow[1]->grades) == 0)
                    {
                        $sixthtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        
                        for($x = $sixthtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('Q'.$x.':S'.$x);
                            $sheet->mergeCells('T'.$x.':U'.$x);
                            $sheet->mergeCells('V'.$x.':W'.$x);
                            $sheet->mergeCells('X'.$x.':Y'.$x);
                            $sheet->mergeCells('Z'.$x.':AA'.$x);
                            $sheet->mergeCells('AB'.$x.':AC'.$x);
                            $sheet->mergeCells('AD'.$x.':AE'.$x);
                        }
                    }else{
                        $sixthtable_cellno = $startcellno;
                        foreach($records_thirdrow[1]->grades as $sixthgrades)
                        {
                            $inmapeh = '';
                            if($sixthgrades->inMAPEH == 1)
                            {
                                $inmapeh = '     ';
                            }
                            $sheet->mergeCells('Q'.$sixthtable_cellno.':S'.$sixthtable_cellno);
                            $sheet->setCellValue('Q'.$sixthtable_cellno, $inmapeh.$sixthgrades->subjdesc);
                            $sheet->mergeCells('T'.$sixthtable_cellno.':U'.$sixthtable_cellno);
                            $sheet->setCellValue('T'.$sixthtable_cellno, $sixthgrades->q1);
                            $sheet->mergeCells('V'.$sixthtable_cellno.':W'.$sixthtable_cellno);
                            $sheet->setCellValue('V'.$sixthtable_cellno, $sixthgrades->q2);
                            $sheet->mergeCells('X'.$sixthtable_cellno.':Y'.$sixthtable_cellno);
                            $sheet->setCellValue('X'.$sixthtable_cellno, $sixthgrades->q3);
                            $sheet->mergeCells('Z'.$sixthtable_cellno.':AA'.$sixthtable_cellno);
                            $sheet->setCellValue('Z'.$sixthtable_cellno, $sixthgrades->q4);
                            $sheet->mergeCells('AB'.$sixthtable_cellno.':AC'.$sixthtable_cellno);
                            $sheet->setCellValue('AB'.$sixthtable_cellno, $sixthgrades->finalrating);
                            $sheet->mergeCells('AD'.$sixthtable_cellno.':AE'.$sixthtable_cellno);
                            $sheet->setCellValue('AD'.$sixthtable_cellno, $sixthgrades->remarks);
                            $sixthtable_cellno+=1;
                        }
                        $genave = number_format(collect($records_thirdrow[1]->grades)->where('inMAPEH','0')->avg('finalrating'));
                        $sheet->setCellValue('AB'.$sixthtable_cellno, $genave);

                        if($genave>=75)
                        {
                            $sheet->setCellValue('AD'.$sixthtable_cellno, 'PASSED');
                        }elseif($genave<75 && $genave!= 0){
                            $sheet->setCellValue('AD'.$sixthtable_cellno, 'FAILED');
                        }
                    }
                    
                    $startcellno += $maxgradecount; // general average

                    $startcellno += 2; // attendance

                    if(count($records_thirdrow[0]->attendance) > 0)
                    {
                        $sheet->setCellValue('D'.$startcellno, collect($records_thirdrow[0]->attendance)->sum('days'));
                        $sheet->setCellValue('I'.$startcellno, collect($records_thirdrow[0]->attendance)->sum('present'));
                    }
                    
                    if(count($records_thirdrow[1]->attendance) > 0)
                    {
                        $sheet->setCellValue('T'.$startcellno, collect($records_thirdrow[1]->attendance)->sum('days'));
                        $sheet->setCellValue('Y'.$startcellno, collect($records_thirdrow[1]->attendance)->sum('present'));
                    }


                    $startcellno += 8;  // Certification

                    $sheet->setCellValue('H'.$startcellno, $studinfo->firstname.' '.$studinfo->middlename[0].'. '. $studinfo->lastname.' '.$studinfo->suffix);
                    $sheet->setCellValue('R'.$startcellno, $studinfo->lrn);
                    $sheet->getStyle('R'.$startcellno)->getNumberFormat()->setFormatCode('0');

                    $startcellno += 1; // schoolinfo

                    $startcellno += 2;

                    $sheet->setCellValue('D'.$startcellno, $footer->copysentto);

                    $startcellno += 1;

                    $sheet->setCellValue('D'.$startcellno, $footer->address);
                    $registrarname = DB::table('teacher')
                        ->where('userid', auth()->user()->id)
                        ->first();
                    $sheet->setCellValue('Y'.$startcellno, $registrarname->title.' '.$registrarname->firstname.' '.$registrarname->middlename[0].'. '.$registrarname->lastname.' '.$registrarname->suffix);

                    $startcellno += 1;

                    $sheet->setCellValue('D'.$startcellno, date('m/d/Y'));

                }else{
                    if(DB::table('schoolinfo')->first()->schoolid == '405308') // fmcma
                    {
                        $sheet->setCellValue('D8', $studinfo->lastname);
                        $sheet->setCellValue('L8', $studinfo->firstname);
                        $sheet->setCellValue('V8', $studinfo->suffix);
                        $sheet->setCellValue('AB8', $studinfo->middlename);
    
                        
                        $sheet->setCellValue('H9', $studinfo->lrn);
                        $sheet->getStyle('H9')->getNumberFormat()->setFormatCode('0');
                        $sheet->setCellValue('U9', date('m/d/Y', strtotime($studinfo->dob)));
                        $sheet->setCellValue('AB9', $studinfo->gender);
                        if($eligibility->kinderprogreport == 1)
                        {
                            $sheet->setCellValue('I13', '/');
                        }
                        if($eligibility->eccdchecklist == 1)
                        {
                            $sheet->setCellValue('Q13', '/');
                        }
                        if($eligibility->kindergartencert == 1)
                        {
                            $sheet->setCellValue('W13', '/');
                        }
    
                        $sheet->setCellValue('E14', $eligibility->schoolname);
                        $sheet->setCellValue('Q14', $eligibility->schoolid);
                        $sheet->setCellValue('Y14', $eligibility->schooladdress);
    
                        if($eligibility->pept == 1)
                        {
                            $sheet->setCellValue('B17', '/');
                        }
                        if($eligibility->peptrating == 1)
                        {
                            $sheet->setCellValue('H17', '/');
                        }
                        $sheet->setCellValue('T17', $eligibility->examdate);
                        $sheet->setCellValue('AC17', $eligibility->specifyothers);
    
                        $sheet->setCellValue('J18', $eligibility->centername);
                        $sheet->setCellValue('W18', $eligibility->remarks);
    
                        $startcellno = 22;
    
                        // F I R S T
    
                        $records_firstrow = $records[0];
                        
                        $sheet->setCellValue('C'.$startcellno, $records_firstrow[0]->schoolname);
                        $sheet->setCellValue('M'.$startcellno, $records_firstrow[0]->schoolid);
                        $sheet->setCellValue('S'.$startcellno, $records_firstrow[1]->schoolname);
                        $sheet->setCellValue('AB'.$startcellno, $records_firstrow[1]->schoolid);
    
                        $startcellno += 1;
                        
                        $sheet->setCellValue('C'.$startcellno, $records_firstrow[0]->schooldistrict);
                        $sheet->setCellValue('H'.$startcellno, $records_firstrow[0]->schooldivision);
                        $sheet->setCellValue('N'.$startcellno, $records_firstrow[0]->schoolregion);
                        $sheet->setCellValue('S'.$startcellno, $records_firstrow[1]->schooldistrict);
                        $sheet->setCellValue('X'.$startcellno, $records_firstrow[1]->schooldivision);
                        $sheet->setCellValue('AD'.$startcellno, $records_firstrow[1]->schoolregion);
    
                        $startcellno += 1;
    
                        $sheet->setCellValue('E'.$startcellno, preg_replace('/\D+/', '', $records_firstrow[0]->levelname));
                        $sheet->setCellValue('I'.$startcellno,  $records_firstrow[0]->sectionname);
                        $sheet->setCellValue('N'.$startcellno,  $records_firstrow[0]->sydesc);
                        $sheet->setCellValue('U'.$startcellno, preg_replace('/\D+/', '', $records_firstrow[1]->levelname));
                        $sheet->setCellValue('Y'.$startcellno,  $records_firstrow[1]->sectionname);
                        $sheet->setCellValue('AD'.$startcellno,  $records_firstrow[1]->sydesc);
    
                        $startcellno += 1;
    
                        $sheet->setCellValue('D'.$startcellno, $records_firstrow[0]->teachername);
                        $sheet->setCellValue('T'.$startcellno, $records_firstrow[1]->teachername);
                        
                        $startcellno += 4;
                        
                        $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                        
                        if(collect($records_firstrow[0]->grades)->where('subjdesc','!=','General Average')->count() == 0)
                        {
                            $firsttable_cellno = $startcellno;
                            $endcell = (($startcellno+$maxgradecount)-2);
                            for($x = $firsttable_cellno; $x <= $endcell; $x++)
                            {
                                $sheet->mergeCells('A'.$x.':C'.$x);
                                $sheet->mergeCells('D'.$x.':E'.$x);
                                $sheet->mergeCells('F'.$x.':G'.$x);
                                $sheet->mergeCells('H'.$x.':I'.$x);
                                $sheet->mergeCells('J'.$x.':K'.$x);
                                $sheet->mergeCells('L'.$x.':M'.$x);
                                $sheet->mergeCells('N'.$x.':O'.$x);
                            }
                        }else{
                            $firsttable_cellno = $startcellno;
                            $countsubj  = 0;
                            foreach($records_firstrow[0]->grades as $firstgrades)
                            {
                                if(strtolower($firstgrades->subjdesc) != 'general average')
                                {
                                    $countsubj+=1;
                                    $inmapeh = '';
                                    if($firstgrades->inMAPEH == 1)
                                    {
                                        $inmapeh = '     ';
                                    }
                                    $sheet->mergeCells('A'.$firsttable_cellno.':C'.$firsttable_cellno);
                                    $sheet->setCellValue('A'.$firsttable_cellno, $inmapeh.$firstgrades->subjdesc);
                                    $sheet->mergeCells('D'.$firsttable_cellno.':E'.$firsttable_cellno);
                                    $sheet->setCellValue('D'.$firsttable_cellno, $firstgrades->q1);
                                    $sheet->mergeCells('F'.$firsttable_cellno.':G'.$firsttable_cellno);
                                    $sheet->setCellValue('F'.$firsttable_cellno, $firstgrades->q2);
                                    $sheet->mergeCells('H'.$firsttable_cellno.':I'.$firsttable_cellno);
                                    $sheet->setCellValue('H'.$firsttable_cellno, $firstgrades->q3);
                                    $sheet->mergeCells('J'.$firsttable_cellno.':K'.$firsttable_cellno);
                                    $sheet->setCellValue('J'.$firsttable_cellno, $firstgrades->q4);
                                    $sheet->mergeCells('L'.$firsttable_cellno.':M'.$firsttable_cellno);
                                    
                                    $sheet->setCellValue('L'.$firsttable_cellno, $firstgrades->finalrating);
                                    $sheet->mergeCells('N'.$firsttable_cellno.':O'.$firsttable_cellno);
                                    $sheet->setCellValue('N'.$firsttable_cellno, $firstgrades->remarks);
                                    $firsttable_cellno+=1;
                                }
                            }
                            
                            for($x = $countsubj; $x < $maxgradecount; $x++)
                            {
                                $sheet->mergeCells('A'.$firsttable_cellno.':C'.$firsttable_cellno);
                                $sheet->mergeCells('D'.$firsttable_cellno.':E'.$firsttable_cellno);
                                $sheet->mergeCells('F'.$firsttable_cellno.':G'.$firsttable_cellno);
                                $sheet->mergeCells('H'.$firsttable_cellno.':I'.$firsttable_cellno);
                                $sheet->mergeCells('J'.$firsttable_cellno.':K'.$firsttable_cellno);
                                $sheet->mergeCells('L'.$firsttable_cellno.':M'.$firsttable_cellno);
                                $sheet->mergeCells('N'.$firsttable_cellno.':O'.$firsttable_cellno);
                                $firsttable_cellno+=1;
                            }
                            
                            if($records_firstrow[0]->type == 1)
                            {
                                $genave = collect($records_firstrow[0]->generalaverage)->first()->finalrating;
                            }else{
                                $genave = collect($records_firstrow[0]->grades)->where('subjdesc','General Average')->first()->finalrating;
                            }
                            $sheet->setCellValue('L'.$firsttable_cellno, $genave);
    
                            if($genave>=75)
                            {                                
                                $sheet->setCellValue('N'.$firsttable_cellno, 'PASSED');
                            }elseif($genave<75 && $genave!= 0){                                
                                $sheet->setCellValue('N'.$firsttable_cellno, 'FAILED');
                            }
                        }
                        if(collect($records_firstrow[1]->grades)->where('subjdesc','!=','General Average')->count() == 0)
                        {
                            $secondtable_cellno = $startcellno;
                            $endcell = (($startcellno+$maxgradecount)-2);
                            
                            for($x = $secondtable_cellno; $x <= $endcell; $x++)
                            {
                                $sheet->mergeCells('Q'.$x.':S'.$x);
                                $sheet->mergeCells('T'.$x.':U'.$x);
                                $sheet->mergeCells('V'.$x.':W'.$x);
                                $sheet->mergeCells('X'.$x.':Y'.$x);
                                $sheet->mergeCells('Z'.$x.':AA'.$x);
                                $sheet->mergeCells('AB'.$x.':AC'.$x);
                                $sheet->mergeCells('AD'.$x.':AE'.$x);
                            }
                        }else{
                            $secondtable_cellno = $startcellno;
                            $countsubj = 0;
                            foreach($records_firstrow[1]->grades as $secondgrades)
                            {
                                if(strtolower($secondgrades->subjdesc) != 'general average')
                                {
                                    $countsubj+=1;
                                    $inmapeh = '';
                                    if($secondgrades->inMAPEH == 1)
                                    {
                                        $inmapeh = '     ';
                                    }
                                    $sheet->mergeCells('Q'.$secondtable_cellno.':S'.$secondtable_cellno);
                                    $sheet->setCellValue('Q'.$secondtable_cellno, $inmapeh.$secondgrades->subjdesc);
                                    $sheet->mergeCells('T'.$secondtable_cellno.':U'.$secondtable_cellno);
                                    $sheet->setCellValue('T'.$secondtable_cellno, $secondgrades->q1);
                                    $sheet->mergeCells('V'.$secondtable_cellno.':W'.$secondtable_cellno);
                                    $sheet->setCellValue('V'.$secondtable_cellno, $secondgrades->q2);
                                    $sheet->mergeCells('X'.$secondtable_cellno.':Y'.$secondtable_cellno);
                                    $sheet->setCellValue('X'.$secondtable_cellno, $secondgrades->q3);
                                    $sheet->mergeCells('Z'.$secondtable_cellno.':AA'.$secondtable_cellno);
                                    $sheet->setCellValue('Z'.$secondtable_cellno, $secondgrades->q4);
                                    $sheet->mergeCells('AB'.$secondtable_cellno.':AC'.$secondtable_cellno);
                                    $sheet->setCellValue('AB'.$secondtable_cellno, $secondgrades->finalrating);
                                    $sheet->mergeCells('AD'.$secondtable_cellno.':AE'.$secondtable_cellno);
                                    $sheet->setCellValue('AD'.$secondtable_cellno, $secondgrades->remarks);
                                    $secondtable_cellno+=1;
                                }
                            }
                            for($x = $countsubj; $x < $maxgradecount; $x++)
                            {
                                $sheet->mergeCells('Q'.$secondtable_cellno.':S'.$secondtable_cellno);
                                $sheet->mergeCells('T'.$secondtable_cellno.':U'.$secondtable_cellno);
                                $sheet->mergeCells('V'.$secondtable_cellno.':W'.$secondtable_cellno);
                                $sheet->mergeCells('X'.$secondtable_cellno.':Y'.$secondtable_cellno);
                                $sheet->mergeCells('Z'.$secondtable_cellno.':AA'.$secondtable_cellno);
                                $sheet->mergeCells('AB'.$secondtable_cellno.':AC'.$secondtable_cellno);
                                $sheet->mergeCells('AD'.$secondtable_cellno.':AE'.$secondtable_cellno);
                                $secondtable_cellno+=1;
                            }
                            
                            if($records_firstrow[1]->type == 1)
                            {
                                $genave = collect($records_firstrow[1]->generalaverage)->first()->finalrating;
                            }else{
                                $genave = collect($records_firstrow[1]->grades)->where('subjdesc','General Average')->first()->finalrating;
                            }
                            $sheet->setCellValue('AB'.$secondtable_cellno, $genave);
    
                            if($genave>=75)
                            {
                                $sheet->setCellValue('AD'.$secondtable_cellno, 'PASSED');
                            }elseif($genave<75 && $genave!= 0){
                                $sheet->setCellValue('AD'.$secondtable_cellno, 'FAILED');
                            }
                        }
    
                        $startcellno += $maxgradecount; // general average
    
                        $startcellno += 2; // attendance
    
                        $startcellno += 5; 
                        // S E C O N D
    
                        $records_secondrow = $records[1];
                        
                        $sheet->setCellValue('C'.$startcellno, $records_secondrow[0]->schoolname);
                        $sheet->setCellValue('M'.$startcellno, $records_secondrow[0]->schoolid);
                        $sheet->setCellValue('S'.$startcellno, $records_secondrow[1]->schoolname);
                        $sheet->setCellValue('AB'.$startcellno, $records_secondrow[1]->schoolid);
    
                        $startcellno += 1;
                        
                        $sheet->setCellValue('C'.$startcellno, $records_secondrow[0]->schooldistrict);
                        $sheet->setCellValue('H'.$startcellno, $records_secondrow[0]->schooldivision);
                        $sheet->setCellValue('N'.$startcellno, $records_secondrow[0]->schoolregion);
                        $sheet->setCellValue('S'.$startcellno, $records_secondrow[1]->schooldistrict);
                        $sheet->setCellValue('X'.$startcellno, $records_secondrow[1]->schooldivision);
                        $sheet->setCellValue('AD'.$startcellno, $records_secondrow[1]->schoolregion);
    
                        $startcellno += 1;
    
                        $sheet->setCellValue('E'.$startcellno, preg_replace('/\D+/', '', $records_secondrow[0]->levelname));
                        $sheet->setCellValue('I'.$startcellno,  $records_secondrow[0]->sectionname);
                        $sheet->setCellValue('N'.$startcellno,  $records_secondrow[0]->sydesc);
                        $sheet->setCellValue('U'.$startcellno, preg_replace('/\D+/', '', $records_secondrow[1]->levelname));
                        $sheet->setCellValue('Y'.$startcellno,  $records_secondrow[1]->sectionname);
                        $sheet->setCellValue('AD'.$startcellno,  $records_secondrow[1]->sydesc);
    
                        $startcellno += 1;
    
                        $sheet->setCellValue('D'.$startcellno, $records_secondrow[0]->teachername);
                        $sheet->setCellValue('T'.$startcellno, $records_secondrow[1]->teachername);
                        
                        $startcellno += 4;
                        
                        $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                        
                        if(collect($records_secondrow[0]->grades)->where('subjdesc','!=','General Average')->count() == 0)
                        {
                            $thirdtable_cellno = $startcellno;
                            $endcell = (($startcellno+$maxgradecount)-2);
                            for($x = $thirdtable_cellno; $x <= $endcell; $x++)
                            {
                                $sheet->mergeCells('A'.$x.':C'.$x);
                                $sheet->mergeCells('D'.$x.':E'.$x);
                                $sheet->mergeCells('F'.$x.':G'.$x);
                                $sheet->mergeCells('H'.$x.':I'.$x);
                                $sheet->mergeCells('J'.$x.':K'.$x);
                                $sheet->mergeCells('L'.$x.':M'.$x);
                                $sheet->mergeCells('N'.$x.':O'.$x);
                            }
                        }else{
                            $thirdtable_cellno = $startcellno;
                            $countsubj = 0;
                            foreach($records_secondrow[0]->grades as $thirdgrades)
                            {
                                if(strtolower($thirdgrades->subjdesc) != 'general average')
                                {
                                    $countsubj+=1;
                                    $inmapeh = '';
                                    if($thirdgrades->inMAPEH == 1)
                                    {
                                        $inmapeh = '     ';
                                    }
                                    $sheet->mergeCells('A'.$thirdtable_cellno.':C'.$thirdtable_cellno);
                                    $sheet->setCellValue('A'.$thirdtable_cellno, $inmapeh.$thirdgrades->subjdesc);
                                    $sheet->mergeCells('D'.$thirdtable_cellno.':E'.$thirdtable_cellno);
                                    $sheet->setCellValue('D'.$thirdtable_cellno, $thirdgrades->q1);
                                    $sheet->mergeCells('F'.$thirdtable_cellno.':G'.$thirdtable_cellno);
                                    $sheet->setCellValue('F'.$thirdtable_cellno, $thirdgrades->q2);
                                    $sheet->mergeCells('H'.$thirdtable_cellno.':I'.$thirdtable_cellno);
                                    $sheet->setCellValue('H'.$thirdtable_cellno, $thirdgrades->q3);
                                    $sheet->mergeCells('J'.$thirdtable_cellno.':K'.$thirdtable_cellno);
                                    $sheet->setCellValue('J'.$thirdtable_cellno, $thirdgrades->q4);
                                    $sheet->mergeCells('L'.$thirdtable_cellno.':M'.$thirdtable_cellno);
                                    $sheet->setCellValue('L'.$thirdtable_cellno, $thirdgrades->finalrating);
                                    $sheet->mergeCells('N'.$thirdtable_cellno.':O'.$thirdtable_cellno);
                                    $sheet->setCellValue('N'.$thirdtable_cellno, $thirdgrades->remarks);
                                    $thirdtable_cellno+=1;
                                }
                            }
                            
                            for($x = $countsubj; $x < $maxgradecount; $x++)
                            {
                                $sheet->mergeCells('A'.$thirdtable_cellno.':C'.$thirdtable_cellno);
                                $sheet->mergeCells('D'.$thirdtable_cellno.':E'.$thirdtable_cellno);
                                $sheet->mergeCells('F'.$thirdtable_cellno.':G'.$thirdtable_cellno);
                                $sheet->mergeCells('H'.$thirdtable_cellno.':I'.$thirdtable_cellno);
                                $sheet->mergeCells('J'.$thirdtable_cellno.':K'.$thirdtable_cellno);
                                $sheet->mergeCells('L'.$thirdtable_cellno.':M'.$thirdtable_cellno);
                                $sheet->mergeCells('N'.$thirdtable_cellno.':O'.$thirdtable_cellno);
                                $thirdtable_cellno+=1;
                            }
                            
                            if($records_secondrow[0]->type == 1)
                            {
                                $genave = collect($records_secondrow[0]->generalaverage)->first()->finalrating;
                            }else{
                                $genave = collect($records_secondrow[0]->grades)->where('subjdesc','General Average')->first()->finalrating;
                            }
                            
                            $sheet->setCellValue('L'.$thirdtable_cellno, $genave);
    
                            if($genave>=75)
                            {
                                $sheet->setCellValue('N'.$thirdtable_cellno, 'PASSED');
                            }elseif($genave<75 && $genave!= 0){
                                $sheet->setCellValue('N'.$thirdtable_cellno, 'FAILED');
                            }
                        }
                        
                        if(collect($records_secondrow[1]->grades)->where('subjdesc','!=','General Average')->count() == 0)
                        {
                            $fourthtable_cellno = $startcellno;
                            $endcell = (($startcellno+$maxgradecount)-2);
                            
                            for($x = $fourthtable_cellno; $x <= $endcell; $x++)
                            {
                                $sheet->mergeCells('Q'.$x.':S'.$x);
                                $sheet->mergeCells('T'.$x.':U'.$x);
                                $sheet->mergeCells('V'.$x.':W'.$x);
                                $sheet->mergeCells('X'.$x.':Y'.$x);
                                $sheet->mergeCells('Z'.$x.':AA'.$x);
                                $sheet->mergeCells('AB'.$x.':AC'.$x);
                                $sheet->mergeCells('AD'.$x.':AE'.$x);
                            }
                        }else{
                            $fourthtable_cellno = $startcellno;
                            $countsubj = 0;
                            foreach($records_secondrow[1]->grades as $fourthgrades)
                            {
                                if(strtolower($fourthgrades->subjdesc) != 'general average')
                                {
                                    $countsubj+=1;
                                    $inmapeh = '';
                                    if($fourthgrades->inMAPEH == 1)
                                    {
                                        $inmapeh = '     ';
                                    }
                                    $sheet->mergeCells('Q'.$fourthtable_cellno.':S'.$fourthtable_cellno);
                                    $sheet->setCellValue('Q'.$fourthtable_cellno, $inmapeh.$fourthgrades->subjdesc);
                                    $sheet->mergeCells('T'.$fourthtable_cellno.':U'.$fourthtable_cellno);
                                    $sheet->setCellValue('T'.$fourthtable_cellno, $fourthgrades->q1);
                                    $sheet->mergeCells('V'.$fourthtable_cellno.':W'.$fourthtable_cellno);
                                    $sheet->setCellValue('V'.$fourthtable_cellno, $fourthgrades->q2);
                                    $sheet->mergeCells('X'.$fourthtable_cellno.':Y'.$fourthtable_cellno);
                                    $sheet->setCellValue('X'.$fourthtable_cellno, $fourthgrades->q3);
                                    $sheet->mergeCells('Z'.$fourthtable_cellno.':AA'.$fourthtable_cellno);
                                    $sheet->setCellValue('Z'.$fourthtable_cellno, $fourthgrades->q4);
                                    $sheet->mergeCells('AB'.$fourthtable_cellno.':AC'.$fourthtable_cellno);
                                    $sheet->setCellValue('AB'.$fourthtable_cellno, $fourthgrades->finalrating);
                                    $sheet->mergeCells('AD'.$fourthtable_cellno.':AE'.$fourthtable_cellno);
                                    $sheet->setCellValue('AD'.$fourthtable_cellno, $fourthgrades->remarks);
                                    $fourthtable_cellno+=1;
                                }
                            }
                            for($x = $countsubj; $x < $maxgradecount; $x++)
                            {
                                $sheet->mergeCells('Q'.$fourthtable_cellno.':S'.$fourthtable_cellno);
                                $sheet->mergeCells('T'.$fourthtable_cellno.':U'.$fourthtable_cellno);
                                $sheet->mergeCells('V'.$fourthtable_cellno.':W'.$fourthtable_cellno);
                                $sheet->mergeCells('X'.$fourthtable_cellno.':Y'.$fourthtable_cellno);
                                $sheet->mergeCells('Z'.$fourthtable_cellno.':AA'.$fourthtable_cellno);
                                $sheet->mergeCells('AB'.$fourthtable_cellno.':AC'.$fourthtable_cellno);
                                $sheet->mergeCells('AD'.$fourthtable_cellno.':AE'.$fourthtable_cellno);
                                $fourthtable_cellno+=1;
                            }
                            
                            if($records_secondrow[1]->type == 1)
                            {
                                $genave = collect($records_secondrow[1]->generalaverage)->first()->finalrating;
                            }else{
                                $genave = collect($records_secondrow[1]->grades)->where('subjdesc','General Average')->first()->finalrating;
                            }
                            $sheet->setCellValue('AB'.$fourthtable_cellno, $genave);
    
                            if($genave>=75)
                            {
                                $sheet->setCellValue('AD'.$fourthtable_cellno, 'PASSED');
                            }elseif($genave<75 && $genave!= 0){
                                $sheet->setCellValue('AD'.$fourthtable_cellno, 'FAILED');
                            }
                        }
                        
                        $startcellno += $maxgradecount; // general average
    
                        $startcellno += 2; // attendance
                            
                        $startcellno += 5; 
    
                        // T H I R D
    
                        $records_thirdrow = $records[2];
                        
                        $sheet->setCellValue('C'.$startcellno, $records_thirdrow[0]->schoolname);
                        $sheet->setCellValue('M'.$startcellno, $records_thirdrow[0]->schoolid);
                        $sheet->setCellValue('S'.$startcellno, $records_thirdrow[1]->schoolname);
                        $sheet->setCellValue('AB'.$startcellno, $records_thirdrow[1]->schoolid);
    
                        $startcellno += 1;
                        
                        $sheet->setCellValue('C'.$startcellno, $records_thirdrow[0]->schooldistrict);
                        $sheet->setCellValue('H'.$startcellno, $records_thirdrow[0]->schooldivision);
                        $sheet->setCellValue('N'.$startcellno, $records_thirdrow[0]->schoolregion);
                        $sheet->setCellValue('S'.$startcellno, $records_thirdrow[1]->schooldistrict);
                        $sheet->setCellValue('X'.$startcellno, $records_thirdrow[1]->schooldivision);
                        $sheet->setCellValue('AD'.$startcellno, $records_thirdrow[1]->schoolregion);
    
                        $startcellno += 1;
    
                        $sheet->setCellValue('E'.$startcellno, preg_replace('/\D+/', '', $records_thirdrow[0]->levelname));
                        $sheet->setCellValue('I'.$startcellno,  $records_thirdrow[0]->sectionname);
                        $sheet->setCellValue('N'.$startcellno,  $records_thirdrow[0]->sydesc);
                        $sheet->setCellValue('U'.$startcellno, preg_replace('/\D+/', '', $records_thirdrow[1]->levelname));
                        $sheet->setCellValue('Y'.$startcellno,  $records_thirdrow[1]->sectionname);
                        $sheet->setCellValue('AD'.$startcellno,  $records_thirdrow[1]->sydesc);
    
                        $startcellno += 1;
    
                        $sheet->setCellValue('D'.$startcellno, $records_thirdrow[0]->teachername);
                        $sheet->setCellValue('T'.$startcellno, $records_thirdrow[1]->teachername);
                        
                        $startcellno += 4;
                        
                        $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                        
                        if(collect($records_thirdrow[0]->grades)->where('subjdesc','!=','General Average')->count() == 0)
                        {
                            $fifthtable_cellno = $startcellno;
                            $endcell = (($startcellno+$maxgradecount)-2);
                            for($x = $fifthtable_cellno; $x <= $endcell; $x++)
                            {
                                $sheet->mergeCells('A'.$x.':C'.$x);
                                $sheet->mergeCells('D'.$x.':E'.$x);
                                $sheet->mergeCells('F'.$x.':G'.$x);
                                $sheet->mergeCells('H'.$x.':I'.$x);
                                $sheet->mergeCells('J'.$x.':K'.$x);
                                $sheet->mergeCells('L'.$x.':M'.$x);
                                $sheet->mergeCells('N'.$x.':O'.$x);
                            }
                        }else{
                            $countsubj = 0;
                            $fifthtable_cellno = $startcellno;
                            foreach($records_thirdrow[0]->grades as $fifthgrades)
                            {
                                if(strtolower($fifthgrades->subjdesc) != 'general average')
                                {
                                    $countsubj+=1;
                                    $inmapeh = '';
                                    if($fifthgrades->inMAPEH == 1)
                                    {
                                        $inmapeh = '     ';
                                    }
                                    $sheet->mergeCells('A'.$fifthtable_cellno.':C'.$fifthtable_cellno);
                                    $sheet->setCellValue('A'.$fifthtable_cellno, $inmapeh.$fifthgrades->subjdesc);
                                    $sheet->mergeCells('D'.$fifthtable_cellno.':E'.$fifthtable_cellno);
                                    $sheet->setCellValue('D'.$fifthtable_cellno, $fifthgrades->q1);
                                    $sheet->mergeCells('F'.$fifthtable_cellno.':G'.$fifthtable_cellno);
                                    $sheet->setCellValue('F'.$fifthtable_cellno, $fifthgrades->q2);
                                    $sheet->mergeCells('H'.$fifthtable_cellno.':I'.$fifthtable_cellno);
                                    $sheet->setCellValue('H'.$fifthtable_cellno, $fifthgrades->q3);
                                    $sheet->mergeCells('J'.$fifthtable_cellno.':K'.$fifthtable_cellno);
                                    $sheet->setCellValue('J'.$fifthtable_cellno, $fifthgrades->q4);
                                    $sheet->mergeCells('L'.$fifthtable_cellno.':M'.$fifthtable_cellno);
                                    $sheet->setCellValue('L'.$fifthtable_cellno, $fifthgrades->finalrating);
                                    $sheet->mergeCells('N'.$fifthtable_cellno.':O'.$fifthtable_cellno);
                                    $sheet->setCellValue('N'.$fifthtable_cellno, $fifthgrades->remarks);
                                    $fifthtable_cellno+=1;
                                }
                            }
                            for($x = $countsubj; $x < $maxgradecount; $x++)
                            {
                                $sheet->mergeCells('Q'.$fifthtable_cellno.':S'.$fifthtable_cellno);
                                $sheet->mergeCells('T'.$fifthtable_cellno.':U'.$fifthtable_cellno);
                                $sheet->mergeCells('V'.$fifthtable_cellno.':W'.$fifthtable_cellno);
                                $sheet->mergeCells('X'.$fifthtable_cellno.':Y'.$fifthtable_cellno);
                                $sheet->mergeCells('Z'.$fifthtable_cellno.':AA'.$fifthtable_cellno);
                                $sheet->mergeCells('AB'.$fifthtable_cellno.':AC'.$fifthtable_cellno);
                                $sheet->mergeCells('AD'.$fifthtable_cellno.':AE'.$fifthtable_cellno);
                                $fifthtable_cellno+=1;
                            }
                            if($records_thirdrow[0]->type == 1)
                            {
                                $genave = collect($records_thirdrow[0]->generalaverage)->first()->finalrating;
                            }else{
                                $genave = collect($records_thirdrow[0]->grades)->where('subjdesc','General Average')->first()->finalrating;
                            }
                            $sheet->setCellValue('L'.$fifthtable_cellno, $genave);
    
                            if($genave>=75)
                            {
                                $sheet->setCellValue('N'.$fifthtable_cellno, 'PASSED');
                            }elseif($genave<75 && $genave!= 0){
                                $sheet->setCellValue('N'.$fifthtable_cellno, 'FAILED');
                            }
                        }
                        
                        if(collect($records_thirdrow[1]->grades)->where('subjdesc','!=','General Average')->count() == 0)
                        {
                            $sixthtable_cellno = $startcellno;
                            $endcell = (($startcellno+$maxgradecount)-2);
                            
                            for($x = $sixthtable_cellno; $x <= $endcell; $x++)
                            {
                                $sheet->mergeCells('Q'.$x.':S'.$x);
                                $sheet->mergeCells('T'.$x.':U'.$x);
                                $sheet->mergeCells('V'.$x.':W'.$x);
                                $sheet->mergeCells('X'.$x.':Y'.$x);
                                $sheet->mergeCells('Z'.$x.':AA'.$x);
                                $sheet->mergeCells('AB'.$x.':AC'.$x);
                                $sheet->mergeCells('AD'.$x.':AE'.$x);
                            }
                        }else{
                            $countsubj = 0;
                            $sixthtable_cellno = $startcellno;
                            foreach($records_thirdrow[1]->grades as $sixthgrades)
                            {
                                if(strtolower($sixthgrades->subjdesc) != 'general average')
                                {
                                    $countsubj+=1;
                                    $inmapeh = '';
                                    if($sixthgrades->inMAPEH == 1)
                                    {
                                        $inmapeh = '     ';
                                    }
                                    $sheet->mergeCells('Q'.$sixthtable_cellno.':S'.$sixthtable_cellno);
                                    $sheet->setCellValue('Q'.$sixthtable_cellno, $inmapeh.$sixthgrades->subjdesc);
                                    $sheet->mergeCells('T'.$sixthtable_cellno.':U'.$sixthtable_cellno);
                                    $sheet->setCellValue('T'.$sixthtable_cellno, $sixthgrades->q1);
                                    $sheet->mergeCells('V'.$sixthtable_cellno.':W'.$sixthtable_cellno);
                                    $sheet->setCellValue('V'.$sixthtable_cellno, $sixthgrades->q2);
                                    $sheet->mergeCells('X'.$sixthtable_cellno.':Y'.$sixthtable_cellno);
                                    $sheet->setCellValue('X'.$sixthtable_cellno, $sixthgrades->q3);
                                    $sheet->mergeCells('Z'.$sixthtable_cellno.':AA'.$sixthtable_cellno);
                                    $sheet->setCellValue('Z'.$sixthtable_cellno, $sixthgrades->q4);
                                    $sheet->mergeCells('AB'.$sixthtable_cellno.':AC'.$sixthtable_cellno);
                                    $sheet->setCellValue('AB'.$sixthtable_cellno, $sixthgrades->finalrating);
                                    $sheet->mergeCells('AD'.$sixthtable_cellno.':AE'.$sixthtable_cellno);
                                    $sheet->setCellValue('AD'.$sixthtable_cellno, $sixthgrades->remarks);
                                    $sixthtable_cellno+=1;
                                }
                            }
                            for($x = $countsubj; $x < $maxgradecount; $x++)
                            {
                                $sheet->mergeCells('Q'.$sixthtable_cellno.':S'.$sixthtable_cellno);
                                $sheet->mergeCells('T'.$sixthtable_cellno.':U'.$sixthtable_cellno);
                                $sheet->mergeCells('V'.$sixthtable_cellno.':W'.$sixthtable_cellno);
                                $sheet->mergeCells('X'.$sixthtable_cellno.':Y'.$sixthtable_cellno);
                                $sheet->mergeCells('Z'.$sixthtable_cellno.':AA'.$sixthtable_cellno);
                                $sheet->mergeCells('AB'.$sixthtable_cellno.':AC'.$sixthtable_cellno);
                                $sheet->mergeCells('AD'.$sixthtable_cellno.':AE'.$sixthtable_cellno);
                                $sixthtable_cellno+=1;
                            }
                            if($records_thirdrow[1]->type == 1)
                            {
                                $genave = collect($records_thirdrow[1]->generalaverage)->first()->finalrating;
                            }else{
                                $genave = collect($records_thirdrow[1]->grades)->where('subjdesc','General Average')->first()->finalrating;
                            }
                            $sheet->setCellValue('AB'.$sixthtable_cellno, $genave);
    
                            if($genave>=75)
                            {
                                $sheet->setCellValue('AD'.$sixthtable_cellno, 'PASSED');
                            }elseif($genave<75 && $genave!= 0){
                                $sheet->setCellValue('AD'.$sixthtable_cellno, 'FAILED');
                            }
                        }
                        
                        $startcellno += $maxgradecount; // general average
    
                        $startcellno += 2; // attendance    
    
                        $startcellno += 7;  // Certification
    
                        $sheet->setCellValue('H'.$startcellno, $studinfo->firstname.' '.$studinfo->middlename[0].'. '. $studinfo->lastname.' '.$studinfo->suffix);
                        $sheet->setCellValue('R'.$startcellno, $studinfo->lrn);
                        $sheet->getStyle('R'.$startcellno)->getNumberFormat()->setFormatCode('0');
    
                        $startcellno += 1; // schoolinfo
    
                        $startcellno += 2;
    
                        $sheet->setCellValue('D'.$startcellno, $footer->copysentto);
    
                        $startcellno += 1;
    
                        $sheet->setCellValue('D'.$startcellno, $footer->address);
                        $registrarname = DB::table('teacher')
                            ->where('userid', auth()->user()->id)
                            ->first();
                        $sheet->setCellValue('Y'.$startcellno, $registrarname->title.' '.$registrarname->firstname.' '.$registrarname->middlename[0].'. '.$registrarname->lastname.' '.$registrarname->suffix);
    
                        $startcellno += 1;
    
                        $sheet->setCellValue('D'.$startcellno, date('m/d/Y'));

                    }else{
                        //// F R O N T  P A G E
                                // $sheet = $spreadsheet->getActiveSheet();
        
                                $sheet->setCellValue('D9', $studinfo->lastname);
                                $sheet->setCellValue('M9', $studinfo->firstname);
                                $sheet->setCellValue('S9', $studinfo->suffix);
                                $sheet->setCellValue('AA9', $studinfo->middlename);
        
                                $sheet->mergeCells('G10:J10');
                                $sheet->setCellValue('G10', $studinfo->lrn);
                                $sheet->getStyle('G10')->getNumberFormat()->setFormatCode('0');
                                $sheet->setCellValue('Q10', date('m/d/Y', strtotime($studinfo->dob)));
                                $sheet->setCellValue('AC10', $studinfo->gender);
                                
                                // E L I G I B I L I T Y
                                if($eligibility->kinderprogreport == 1)
                                {
                                    $sheet->setCellValue('G14', '[ / ]');
                                }else{
                                    $sheet->setCellValue('G14', '[   ]');
                                }
                                if($eligibility->eccdchecklist == 1)
                                {
                                    $sheet->setCellValue('P14', '[ / ]');
                                }else{
                                    $sheet->setCellValue('P14', '[   ]');
                                }
                                if($eligibility->kindergartencert == 1)
                                {
                                    $sheet->setCellValue('T14', '[ / ]');
                                }else{
                                    $sheet->setCellValue('T14', '[   ]');
                                }
                                if($eligibility->pept == 1)
                                {
                                    $sheet->setCellValue('B18', '    [ / ]   PEPT Passer     Rating:  '.$eligibility->peptrating);
                                }else{
                                    $sheet->setCellValue('B18', '    [   ]   PEPT Passer     Rating:  __________');
                                }
                                $sheet->setCellValue('B18', '     Date of Examination/Assessment (mm/dd/yyyy):  '.$eligibility->examdate.'  ');
                                $sheet->setCellValue('AA18', $eligibility->specifyothers);
                                $sheet->setCellValue('C19', '     Date of Examination/Assessment (mm/dd/yyyy):  '.$eligibility->examdate.'  ');
                               
        
                                $firstrecords = $records[0];
        
                                foreach($firstrecords as $firstrecord)
                                {
                                    foreach($firstrecord as $key => $value)
                                    {
                                        if($value == null)
                                        {   
                                            if($key == 'grades' || $key == 'subjaddedforauto')
                                            {
                                                $firstrecord->$key = array();
                                            }
                                            elseif($key == 'sydesc')
                                            {
                                                $firstrecord->$key = null;
                                            }
                                            elseif($key == 'schoolname')
                                            {
                                                $firstrecord->$key = null;
                                            }
                                            elseif($key == 'schoolid')
                                            {
                                                $firstrecord->$key = null;
                                            }
                                            elseif($key == 'schoolregion')
                                            {
                                                $firstrecord->$key = null;
                                            }
                                            elseif($key == 'noofgrades')
                                            {
                                                $secondrecord->$key = 0;
                                            }else{
                                                $firstrecord->$key = '_______________';
                                            }
                                            // return $key;
                                            // $frontrecord->$key;
                                        }
                                    }
                                }
                                ###########  First table
                                    $sheet->setCellValue('B23', 'School:   '.$firstrecords[0]->schoolname);
                                    $sheet->setCellValue('N23', $firstrecords[0]->schoolid);
                                    $sheet->setCellValue('B24', 'District: '.$firstrecords[0]->schooldistrict.'   Division: '.$firstrecords[0]->schooldivision);
                                    $sheet->setCellValue('O24', str_replace("REGION", "",$firstrecords[0]->schoolregion));
                                    $sheet->setCellValue('B25', 'Classified as Grade: '.preg_replace('/\D+/', '', $firstrecords[0]->levelname).'  Section:  '.$firstrecords[0]->sectionname);
                                    $sheet->setCellValue('N25', $firstrecords[0]->sydesc);
                                    $sheet->setCellValue('B26', 'Name of Adviser/Teacher: '.$firstrecords[0]->teachername);
                            
                                    $sheet->getRowDimension('29')->setRowHeight(18);
                                    $sheet->insertNewRowBefore(30, ($maxgradecount-2));
                                    $firstgradescellno = 30;
                                    // return $maxgradecount+27;
                                    for($x = 30; $x < ((29+$maxgradecount)); $x++)
                                    {
                                        $firstgradescellno+=1;
                                        $sheet->getRowDimension($x)->setRowHeight(18);
                                    }
                                    $firsttablecellno = 30;
                                    
                                    if(count($firstrecords[0]->grades)>0)
                                    {
                                        foreach(collect($firstrecords[0]->grades)->where('subjdesc','!=','General Average') as $firstrecordgrade)
                                        {
                                            $sheet->getStyle('B'.$firsttablecellno)->getAlignment()->setHorizontal('left');
                                            $sheet->mergeCells('B'.$firsttablecellno.':F'.$firsttablecellno);
                                            $sheet->setCellValue('B'.$firsttablecellno, $firstrecordgrade->subjdesc);
                                            $sheet->setCellValue('G'.$firsttablecellno, $firstrecordgrade->q1);
                                            $sheet->setCellValue('H'.$firsttablecellno, $firstrecordgrade->q2);
                                            $sheet->setCellValue('I'.$firsttablecellno, $firstrecordgrade->q3);
                                            $sheet->setCellValue('J'.$firsttablecellno, $firstrecordgrade->q4);
                                            $sheet->mergeCells('K'.$firsttablecellno.':M'.$firsttablecellno);
                                            $sheet->setCellValue('K'.$firsttablecellno, $firstrecordgrade->finalrating);
                                            $sheet->mergeCells('N'.$firsttablecellno.':O'.$firsttablecellno);
                                            $sheet->setCellValue('N'.$firsttablecellno, $firstrecordgrade->remarks);
                                            $sheet->getStyle('G'.$firsttablecellno.':N'.$firsttablecellno)->getFont()->setBold(false);
                                            $firsttablecellno+=1;
                                        }
                                    }
                                    if(count($firstrecords[0]->subjaddedforauto)>0)
                                    {
                                        foreach($firstrecords[0]->subjaddedforauto as $customsubjgrade)
                                        {
                                            $sheet->getStyle('B'.$firsttablecellno)->getAlignment()->setHorizontal('left');
                                            $sheet->mergeCells('B'.$firsttablecellno.':F'.$firsttablecellno);
                                            $sheet->setCellValue('B'.$firsttablecellno, $customsubjgrade->subjdesc);
                                            $sheet->setCellValue('G'.$firsttablecellno, $customsubjgrade->q1);
                                            $sheet->setCellValue('H'.$firsttablecellno, $customsubjgrade->q2);
                                            $sheet->setCellValue('I'.$firsttablecellno, $customsubjgrade->q3);
                                            $sheet->setCellValue('J'.$firsttablecellno, $customsubjgrade->q4);
                                            $sheet->mergeCells('K'.$firsttablecellno.':M'.$firsttablecellno);
                                            $sheet->setCellValue('K'.$firsttablecellno, $customsubjgrade->finalrating);
                                            $sheet->mergeCells('N'.$firsttablecellno.':O'.$firsttablecellno);
                                            $sheet->setCellValue('N'.$firsttablecellno, $customsubjgrade->actiontaken);
                                            $sheet->getStyle('G'.$firsttablecellno.':N'.$firsttablecellno)->getFont()->setBold(false);
                                            $firsttablecellno+=1;
                                        }
                                    }
                                    $genave = number_format(collect($firstrecords[0]->grades)->where('inMAPEH','0')->avg('finalrating'));
                                    $sheet->setCellValue('L'.$firsttablecellno, $genave);
            
                                    if($genave>=75)
                                    {
                                        $sheet->setCellValue('N'.$firsttablecellno, 'PASSED');
                                    }elseif($genave<75 && $genave!= 0){
                                        $sheet->setCellValue('N'.$firsttablecellno, 'FAILED');
                                    }
    
                                    for($x = $firstrecords[0]->noofgrades; $x < $maxgradecount; $x++ )
                                    {
                                            $firsttablecellno+=1;
                                    }
    
                                    if($firstrecords[0]->type == 1)
                                    {
                                        if(count($firstrecords[0]->grades)>0)
                                        {
                                            $sheet->setCellValue('G'.$firsttablecellno, number_format(collect($firstrecords[0]->grades)->avg('q1')));
                                            $sheet->setCellValue('H'.$firsttablecellno, number_format(collect($firstrecords[0]->grades)->avg('q2')));
                                            $sheet->setCellValue('I'.$firsttablecellno, number_format(collect($firstrecords[0]->grades)->avg('q3')));
                                            $sheet->setCellValue('J'.$firsttablecellno, number_format(collect($firstrecords[0]->grades)->avg('q4')));
                                            $sheet->setCellValue('K'.$firsttablecellno, number_format(collect($firstrecords[0]->grades)->avg('finalrating')));
                                            if(number_format(collect($firstrecords[0]->grades)->avg('finalrating')) < 75)
                                            {
                                                $sheet->setCellValue('N'.$firsttablecellno, 'RETAINED');
                                            }else{
                                                $sheet->setCellValue('N'.$firsttablecellno, 'PASSED');
                                            }
                                        }
                                    }else{
                                        if(count(collect($firstrecords[0]->grades)->where('subjtitle','General Average'))>0)
                                        {
                                            $sheet->setCellValue('K'.$firsttablecellno, collect($firstrecords[0]->grades)->where('subjtitle','General Average')->first()->finalrating);
                                            if(collect($firstrecords[0]->grades)->where('subjtitle','General Average')->first()->finalrating < 75)
                                            {
                                                $sheet->setCellValue('N'.$firsttablecellno, 'RETAINED');
                                            }else{
                                                $sheet->setCellValue('N'.$firsttablecellno, 'PASSED');
                                            }
                                        }
                                    }
                                ##########  -- F I R S T  T A B L E --  ##########
                                ###########  Second table
                                    $sheet->setCellValue('Q23', 'School:   '.$firstrecords[1]->schoolname);
                                    $sheet->setCellValue('AC23', $firstrecords[1]->schoolid);
                                    $sheet->setCellValue('Q24', 'District: '.$firstrecords[1]->schooldistrict.'   Division: '.$firstrecords[1]->schooldivision);
                                    $sheet->setCellValue('AD24', str_replace("REGION", "",$firstrecords[1]->schoolregion));
                                    $sheet->setCellValue('Q25', 'Classified as Grade: '.preg_replace('/\D+/', '', $firstrecords[1]->levelname).'  Section:  '.$firstrecords[1]->sectionname);
                                    $sheet->setCellValue('AC25', $firstrecords[1]->sydesc);
                                    $sheet->setCellValue('Q26', 'Name of Adviser/Teacher: '.$firstrecords[1]->teachername);
        
                                    $secondtablecellno = 30;
                                    if(count($firstrecords[1]->grades)>0)
                                    {
                                        foreach($firstrecords[1]->grades as $secondrecordgrade)
                                        {
                                            $sheet->getStyle('Q'.$secondtablecellno)->getAlignment()->setHorizontal('left');
                                            $sheet->mergeCells('Q'.$secondtablecellno.':W'.$secondtablecellno);
                                            $sheet->setCellValue('Q'.$secondtablecellno, $secondrecordgrade->subjdesc);
                                            $sheet->setCellValue('X'.$secondtablecellno, $secondrecordgrade->q1);
                                            $sheet->setCellValue('Z'.$secondtablecellno, $secondrecordgrade->q2);
                                            $sheet->setCellValue('AA'.$secondtablecellno, $secondrecordgrade->q3);
                                            $sheet->setCellValue('AB'.$secondtablecellno, $secondrecordgrade->q4);
                                            $sheet->setCellValue('AC'.$secondtablecellno, $secondrecordgrade->finalrating);
                                            $sheet->mergeCells('AD'.$secondtablecellno.':AE'.$secondtablecellno);
                                            $sheet->setCellValue('AD'.$secondtablecellno, $secondrecordgrade->remarks);
                                            $sheet->getStyle('X'.$secondtablecellno.':AD'.$secondtablecellno)->getFont()->setBold(false);
                                            $secondtablecellno+=1;
                                        }
                                    }
                                    if(count($firstrecords[1]->subjaddedforauto)>0)
                                    {
                                        foreach($firstrecords[1]->subjaddedforauto as $customsubjgrade)
                                        {
                                            $sheet->getStyle('Q'.$secondtablecellno)->getAlignment()->setHorizontal('left');
                                            $sheet->mergeCells('Q'.$secondtablecellno.':W'.$secondtablecellno);
                                            $sheet->setCellValue('Q'.$secondtablecellno, $customsubjgrade->subjdesc);
                                            $sheet->setCellValue('X'.$secondtablecellno, $customsubjgrade->q1);
                                            $sheet->setCellValue('Z'.$secondtablecellno, $customsubjgrade->q2);
                                            $sheet->setCellValue('AA'.$secondtablecellno, $customsubjgrade->q3);
                                            $sheet->setCellValue('AB'.$secondtablecellno, $customsubjgrade->q4);
                                            $sheet->setCellValue('AC'.$secondtablecellno, $customsubjgrade->finalrating);
                                            $sheet->mergeCells('AD'.$secondtablecellno.':AE'.$secondtablecellno);
                                            $sheet->setCellValue('AD'.$secondtablecellno, $customsubjgrade->actiontaken);
                                            $sheet->getStyle('X'.$secondtablecellno.':AD'.$secondtablecellno)->getFont()->setBold(false);
                                            $secondtablecellno+=1;
                                        }
                                    }
                                    $genave = number_format(collect($firstrecords[1]->grades)->where('inMAPEH','0')->avg('finalrating'));
                                    $sheet->setCellValue('L'.$secondtablecellno, $genave);
            
                                    if($genave>=75)
                                    {
                                        $sheet->setCellValue('N'.$secondtablecellno, 'PASSED');
                                    }elseif($genave<75 && $genave!= 0){
                                        $sheet->setCellValue('N'.$secondtablecellno, 'FAILED');
                                    }
                                    for($x = $firstrecords[1]->noofgrades; $x < $maxgradecount; $x++ )
                                    {
                                            $secondtablecellno+=1;
                                    }
                                    if($firstrecords[1]->type == 1)
                                    {
                                        if(count($firstrecords[1]->grades)>0)
                                        {
                                            $sheet->setCellValue('X'.$secondtablecellno, number_format(collect($firstrecords[1]->grades)->avg('q1')));
                                            $sheet->setCellValue('Z'.$secondtablecellno, number_format(collect($firstrecords[1]->grades)->avg('q2')));
                                            $sheet->setCellValue('AA'.$secondtablecellno, number_format(collect($firstrecords[1]->grades)->avg('q3')));
                                            $sheet->setCellValue('AB'.$secondtablecellno, number_format(collect($firstrecords[1]->grades)->avg('q4')));
                                            $sheet->setCellValue('AC'.$secondtablecellno, number_format(collect($firstrecords[1]->grades)->avg('finalrating')));
                                            if(number_format(collect($firstrecords[1]->grades)->avg('finalrating')) < 75)
                                            {
                                                $sheet->setCellValue('AD'.$secondtablecellno, 'RETAINED');
                                            }else{
                                                $sheet->setCellValue('AD'.$secondtablecellno, 'PASSED');
                                            }
                                        }
                                    }else{
                                        if(count(collect($firstrecords[1]->grades)->where('subjtitle','General Average'))>0)
                                        {
                                            $sheet->setCellValue('K'.$secondtablecellno, collect($firstrecords[1]->grades)->where('subjtitle','General Average')->first()->finalrating);
                                            if(collect($firstrecords[1]->grades)->where('subjtitle','General Average')->first()->finalrating < 75)
                                            {
                                                $sheet->setCellValue('N'.$secondtablecellno, 'RETAINED');
                                            }else{
                                                $sheet->setCellValue('N'.$secondtablecellno, 'PASSED');
                                            }
                                        }
                                    }
                                ##########  -- S E C O N D  T A B L E --  ##########
                                
                                $secondrecords = $records[1];
                                $firstgradescellno += 8;
                                $secondgradescellno = $firstgradescellno;
                                foreach($secondrecords as $secondrecord)
                                {
                                    foreach($secondrecord as $key => $value)
                                    {
                                        if($value == null)
                                        {   
                                            if($key == 'grades' || $key == 'subjaddedforauto')
                                            {
                                                $secondrecord->$key = array();
                                            }
                                            elseif($key == 'sydesc')
                                            {
                                                $secondrecord->$key = null;
                                            }
                                            // elseif($key == 'schoolname')
                                            // {
                                            //     $secondrecord->$key = null;
                                            // }
                                            elseif($key == 'schoolid')
                                            {
                                                $secondrecord->$key = null;
                                            }
                                            elseif($key == 'schoolregion')
                                            {
                                                $secondrecord->$key = null;
                                            }else{
                                                $secondrecord->$key = '_______________';
                                            }
                                            // return $key;
                                            // $frontrecord->$key;
                                        }
                                    }
                                }
                                ###########  Third table
                                    $sheet->setCellValue('B'.$secondgradescellno, 'School:   '.$secondrecords[0]->schoolname);
                                    $sheet->setCellValue('N'.$secondgradescellno, $secondrecords[0]->schoolid);
                                    $secondgradescellno += 1;
                                    $sheet->setCellValue('B'.$secondgradescellno, 'District: '.$secondrecords[0]->schooldistrict.'   Division: '.$secondrecords[0]->schooldivision);
                                    $sheet->setCellValue('O'.$secondgradescellno, str_replace("REGION", "",$secondrecords[0]->schoolregion));
                                    $secondgradescellno += 1;
                                    $sheet->setCellValue('B'.$secondgradescellno, 'Classified as Grade: '.preg_replace('/\D+/', '', $secondrecords[0]->levelname).'  Section:  '.$secondrecords[0]->sectionname);
                                    $sheet->setCellValue('N'.$secondgradescellno, $secondrecords[0]->sydesc);
                                    $secondgradescellno += 1;
                                    $sheet->setCellValue('B'.$secondgradescellno, 'Name of Adviser/Teacher: '.$secondrecords[0]->teachername);
        
                                    $secondgradescellno += 5;
        
                                    $sheet->insertNewRowBefore($secondgradescellno, ($maxgradecount-2));
        
                                    $firsttablecellno = $secondgradescellno;
                                    if(count($secondrecords[0]->grades)>0)
                                    {
                                        foreach($secondrecords[0]->grades as $firstrecordgrade)
                                        {
                                            $sheet->getStyle('B'.$firsttablecellno)->getAlignment()->setHorizontal('left');
                                            $sheet->mergeCells('B'.$firsttablecellno.':F'.$firsttablecellno);
                                            $sheet->setCellValue('B'.$firsttablecellno, $firstrecordgrade->subjdesc);
                                            $sheet->setCellValue('G'.$firsttablecellno, $firstrecordgrade->q1);
                                            $sheet->setCellValue('H'.$firsttablecellno, $firstrecordgrade->q2);
                                            $sheet->setCellValue('I'.$firsttablecellno, $firstrecordgrade->q3);
                                            $sheet->setCellValue('J'.$firsttablecellno, $firstrecordgrade->q4);
                                            $sheet->mergeCells('K'.$firsttablecellno.':M'.$firsttablecellno);
                                            $sheet->setCellValue('K'.$firsttablecellno, $firstrecordgrade->finalrating);
                                            $sheet->mergeCells('N'.$firsttablecellno.':O'.$firsttablecellno);
                                            $sheet->setCellValue('N'.$firsttablecellno, $firstrecordgrade->remarks);
                                            $sheet->getStyle('G'.$firsttablecellno.':N'.$firsttablecellno)->getFont()->setBold(false);
                                            $firsttablecellno+=1;
                                        }
                                    }
                                    if(count($secondrecords[0]->subjaddedforauto)>0)
                                    {
                                        foreach($secondrecords[0]->subjaddedforauto as $customsubjgrade)
                                        {
                                            $sheet->getStyle('B'.$firsttablecellno)->getAlignment()->setHorizontal('left');
                                            $sheet->mergeCells('B'.$firsttablecellno.':F'.$firsttablecellno);
                                            $sheet->setCellValue('B'.$firsttablecellno, $customsubjgrade->subjdesc);
                                            $sheet->setCellValue('G'.$firsttablecellno, $customsubjgrade->q1);
                                            $sheet->setCellValue('H'.$firsttablecellno, $customsubjgrade->q2);
                                            $sheet->setCellValue('I'.$firsttablecellno, $customsubjgrade->q3);
                                            $sheet->setCellValue('J'.$firsttablecellno, $customsubjgrade->q4);
                                            $sheet->mergeCells('K'.$firsttablecellno.':M'.$firsttablecellno);
                                            $sheet->setCellValue('K'.$firsttablecellno, $customsubjgrade->finalrating);
                                            $sheet->mergeCells('N'.$firsttablecellno.':O'.$firsttablecellno);
                                            $sheet->setCellValue('N'.$firsttablecellno, $customsubjgrade->actiontaken);
                                            $sheet->getStyle('G'.$firsttablecellno.':N'.$firsttablecellno)->getFont()->setBold(false);
                                            $firsttablecellno+=1;
                                        }
                                    }
                                    
                                    for($x = $secondrecords[0]->noofgrades; $x < $maxgradecount; $x++ )
                                    {
                                        $firsttablecellno+=1;
                                    }
    
                                    if($secondrecords[0]->type == 1)
                                    {
                                        if(count($secondrecords[0]->grades)>0)
                                        {
                                            $sheet->setCellValue('G'.$firsttablecellno, number_format(collect($secondrecords[0]->grades)->avg('q1')));
                                            $sheet->setCellValue('H'.$firsttablecellno, number_format(collect($secondrecords[0]->grades)->avg('q2')));
                                            $sheet->setCellValue('I'.$firsttablecellno, number_format(collect($secondrecords[0]->grades)->avg('q3')));
                                            $sheet->setCellValue('J'.$firsttablecellno, number_format(collect($secondrecords[0]->grades)->avg('q4')));
                                            $sheet->setCellValue('K'.$firsttablecellno, number_format(collect($secondrecords[0]->grades)->avg('finalrating')));
                                            if(number_format(collect($secondrecords[0]->grades)->avg('finalrating')) < 75)
                                            {
                                                $sheet->setCellValue('N'.$firsttablecellno, 'RETAINED');
                                            }else{
                                                $sheet->setCellValue('N'.$firsttablecellno, 'PASSED');
                                            }
                                        }
                                    }else{
                                        if(count(collect($secondrecords[0]->grades)->where('subjtitle','General Average'))>0)
                                        {
                                            $sheet->setCellValue('K'.$firsttablecellno, collect($secondrecords[0]->grades)->where('subjtitle','General Average')->first()->finalrating);
                                            if(collect($secondrecords[0]->grades)->where('subjtitle','General Average')->first()->finalrating < 75)
                                            {
                                                $sheet->setCellValue('N'.$firsttablecellno, 'RETAINED');
                                            }else{
                                                $sheet->setCellValue('N'.$firsttablecellno, 'PASSED');
                                            }
                                        }
                                    }
                                ##########  -- T H I R D  T A B L E --  ##########
                                ###########  Fourth table
                                    $fourthtablecellno = $firstgradescellno;
                                    // return collect($secondrecords[1]);
                                    $sheet->setCellValue('Q'.$fourthtablecellno, 'School:   '.$secondrecords[1]->schoolname);
                                    $sheet->setCellValue('AC'.$fourthtablecellno, $secondrecords[1]->schoolid);
                                    $fourthtablecellno += 1;
                                    $sheet->setCellValue('Q'.$fourthtablecellno, 'District: '.$secondrecords[1]->schooldistrict.'   Division: '.$secondrecords[1]->schooldivision);
                                    $sheet->setCellValue('AD'.$fourthtablecellno, str_replace("REGION", "",$secondrecords[1]->schoolregion));
                                    $fourthtablecellno += 1;
                                    $sheet->setCellValue('Q'.$fourthtablecellno, 'Classified as Grade: '.preg_replace('/\D+/', '', $secondrecords[1]->levelname).'  Section:  '.$secondrecords[1]->sectionname);
                                    $sheet->setCellValue('AC'.$fourthtablecellno, $secondrecords[1]->sydesc);
                                    $fourthtablecellno += 1;
                                    $sheet->setCellValue('Q'.$fourthtablecellno, 'Name of Adviser/Teacher: '.$secondrecords[1]->teachername);
    
                                    $fourthtablecellno += 5;
        
        
                                    if(count($secondrecords[1]->grades)>0)
                                    {
                                        foreach($secondrecords[1]->grades as $fourthrecordgrade)
                                        {
                                            $sheet->getStyle('Q'.$fourthtablecellno)->getAlignment()->setHorizontal('left');
                                            $sheet->mergeCells('Q'.$fourthtablecellno.':W'.$fourthtablecellno);
                                            $sheet->setCellValue('Q'.$fourthtablecellno, $fourthrecordgrade->subjdesc);
                                            $sheet->setCellValue('X'.$fourthtablecellno, $fourthrecordgrade->q1);
                                            $sheet->setCellValue('Z'.$fourthtablecellno, $fourthrecordgrade->q2);
                                            $sheet->setCellValue('AA'.$fourthtablecellno, $fourthrecordgrade->q3);
                                            $sheet->setCellValue('AB'.$fourthtablecellno, $fourthrecordgrade->q4);
                                            $sheet->setCellValue('AC'.$fourthtablecellno, $fourthrecordgrade->finalrating);
                                            $sheet->mergeCells('AD'.$fourthtablecellno.':AE'.$fourthtablecellno);
                                            $sheet->setCellValue('AD'.$fourthtablecellno, $fourthrecordgrade->remarks);
                                            $sheet->getStyle('X'.$fourthtablecellno.':AD'.$fourthtablecellno)->getFont()->setBold(false);
                                            $fourthtablecellno+=1;
                                        }
                                    }
                                    if(count($secondrecords[1]->subjaddedforauto)>0)
                                    {
                                        foreach($secondrecords[1]->subjaddedforauto as $customsubjgrade)
                                        {
                                            $sheet->getStyle('Q'.$fourthtablecellno)->getAlignment()->setHorizontal('left');
                                            $sheet->mergeCells('Q'.$fourthtablecellno.':W'.$fourthtablecellno);
                                            $sheet->setCellValue('Q'.$fourthtablecellno, $customsubjgrade->subjdesc);
                                            $sheet->setCellValue('X'.$fourthtablecellno, $customsubjgrade->q1);
                                            $sheet->setCellValue('Z'.$fourthtablecellno, $customsubjgrade->q2);
                                            $sheet->setCellValue('AA'.$fourthtablecellno, $customsubjgrade->q3);
                                            $sheet->setCellValue('AB'.$fourthtablecellno, $customsubjgrade->q4);
                                            $sheet->setCellValue('AC'.$fourthtablecellno, $customsubjgrade->finalrating);
                                            $sheet->mergeCells('AD'.$fourthtablecellno.':AE'.$fourthtablecellno);
                                            $sheet->setCellValue('AD'.$fourthtablecellno, $customsubjgrade->actiontaken);
                                            $sheet->getStyle('X'.$fourthtablecellno.':AD'.$fourthtablecellno)->getFont()->setBold(false);
                                            $fourthtablecellno+=1;
                                        }
                                    }
                                    if(count($secondrecords[1]->grades)>0)
                                    {
                                        $sheet->setCellValue('X'.$fourthtablecellno, number_format(collect($secondrecords[1]->grades)->avg('q1')));
                                        $sheet->setCellValue('Z'.$fourthtablecellno, number_format(collect($secondrecords[1]->grades)->avg('q2')));
                                        $sheet->setCellValue('AA'.$fourthtablecellno, number_format(collect($secondrecords[1]->grades)->avg('q3')));
                                        $sheet->setCellValue('AB'.$fourthtablecellno, number_format(collect($secondrecords[1]->grades)->avg('q4')));
                                        $sheet->setCellValue('AC'.$fourthtablecellno, number_format(collect($secondrecords[1]->grades)->avg('finalrating')));
                                        if(number_format(collect($secondrecords[1]->grades)->avg('finalrating')) < 75)
                                        {
                                            $sheet->setCellValue('AD'.$fourthtablecellno, 'RETAINED');
                                        }else{
                                            $sheet->setCellValue('AD'.$fourthtablecellno, 'PASSED');
                                        }
                                    }
                                ##########  -- F O U R T H  T A B L E --  ##########
                        //// ! F R O N T P A G E ! ////
        
                        //// B A C K  P A G E
                                $sheet = $spreadsheet->getSheet(1);
                                #### Footer ####    
                                    $footercellno = 36;
                                    $sheet->setCellValue('C'.$footercellno, 'I CERTIFY that this is a true record of '.$studinfo->firstname.' '.$studinfo->middlename[0].'. '.$studinfo->lastname.' '.$studinfo->suffix.' with LRN  '.$studinfo->lrn.'  and that he/she is  eligible for admission to Grade ________.');
                                    $sheet->setCellValue('C'.($footercellno+7), 'I CERTIFY that this is a true record of '.$studinfo->firstname.' '.$studinfo->middlename[0].'. '.$studinfo->lastname.' '.$studinfo->suffix.' with LRN  '.$studinfo->lrn.'  and that he/she is  eligible for admission to Grade ________.');
                                    $sheet->setCellValue('C'.($footercellno+14), 'I CERTIFY that this is a true record of '.$studinfo->firstname.' '.$studinfo->middlename[0].'. '.$studinfo->lastname.' '.$studinfo->suffix.' with LRN  '.$studinfo->lrn.'  and that he/she is  eligible for admission to Grade ________.');
                                #### Footer #### 
                                
                                
        
                                
                                $thirdrecords = $records[2];
        
                                foreach($thirdrecords as $thirdrecord)
                                {
                                    foreach($thirdrecord as $key => $value)
                                    {
                                        if($value == null)
                                        {   
                                            if($key == 'grades')
                                            {
                                                $thirdrecord->$key = array();
                                            }
                                            elseif($key == 'sydesc')
                                            {
                                                $thirdrecord->$key = null;
                                            }
                                            elseif($key == 'schoolname')
                                            {
                                                $thirdrecord->$key = null;
                                            }
                                            elseif($key == 'schoolid')
                                            {
                                                $thirdrecord->$key = null;
                                            }
                                            elseif($key == 'schoolregion')
                                            {
                                                $thirdrecord->$key = null;
                                            }else{
                                                $thirdrecord->$key = '_______________';
                                            }
                                            // return $key;
                                            // $frontrecord->$key;
                                        }
                                    }
                                }
                                ###########  Fifth table
                                    $sheet->setCellValue('B3', 'School:   '.$thirdrecords[0]->schoolname);
                                    $sheet->setCellValue('N3', $thirdrecords[0]->schoolid);
                                    $sheet->setCellValue('B4', 'District: '.$thirdrecords[0]->schooldistrict.'   Division: '.$thirdrecords[0]->schooldivision);
                                    $sheet->setCellValue('O4', str_replace("REGION", "",$thirdrecords[0]->schoolregion));
                                    $sheet->setCellValue('B5', 'Classified as Grade: '.preg_replace('/\D+/', '', $thirdrecords[0]->levelname).'  Section:  '.$thirdrecords[0]->sectionname);
                                    $sheet->setCellValue('N5', $thirdrecords[0]->sydesc);
                                    $sheet->setCellValue('B6', 'Name of Adviser/Teacher: '.$thirdrecords[0]->teachername);
                            
                                    // $sheet->getRowDimension('29')->setRowHeight(18);
                                    $sheet->insertNewRowBefore(11, ($maxgradecount-2));
                                    $thirdgradescellno = 10;
                                    // return $maxgradecount+27;
                                    for($x = 11; $x < ((9+$maxgradecount)); $x++)
                                    {
                                        $thirdgradescellno+=1;
                                        $sheet->getRowDimension($x)->setRowHeight(18);
                                    }
                                    
                                    if(count($thirdrecords[0]->grades)>0)
                                    {
                                        $firsttablecellno = 10;
                                        foreach($thirdrecords[0]->grades as $fifthrecordgrade)
                                        {
                                            $sheet->getStyle('B'.$firsttablecellno)->getAlignment()->setHorizontal('left');
                                            $sheet->mergeCells('B'.$firsttablecellno.':F'.$firsttablecellno);
                                            $sheet->setCellValue('B'.$firsttablecellno, $fifthrecordgrade->subjdesc);
                                            $sheet->setCellValue('G'.$firsttablecellno, $fifthrecordgrade->q1);
                                            $sheet->setCellValue('H'.$firsttablecellno, $fifthrecordgrade->q2);
                                            $sheet->setCellValue('I'.$firsttablecellno, $fifthrecordgrade->q3);
                                            $sheet->setCellValue('J'.$firsttablecellno, $fifthrecordgrade->q4);
                                            $sheet->mergeCells('K'.$firsttablecellno.':M'.$firsttablecellno);
                                            $sheet->setCellValue('K'.$firsttablecellno, $fifthrecordgrade->finalrating);
                                            $sheet->mergeCells('N'.$firsttablecellno.':O'.$firsttablecellno);
                                            $sheet->setCellValue('N'.$firsttablecellno, $fifthrecordgrade->remarks);
                                            $sheet->getStyle('G'.$firsttablecellno.':N'.$firsttablecellno)->getFont()->setBold(false);
                                            $firsttablecellno+=1;
                                        }
                                    }
                                ##########  -- F I F T H  T A B L E --  ##########
                                ##########  Sixth table
                                    $sheet->setCellValue('Q3', 'School:   '.$thirdrecords[1]->schoolname);
                                    $sheet->setCellValue('AC3', $thirdrecords[1]->schoolid);
                                    $sheet->setCellValue('Q4', 'District: '.$thirdrecords[1]->schooldistrict.'   Division: '.$thirdrecords[1]->schooldivision);
                                    $sheet->setCellValue('AD4', str_replace("REGION", "",$thirdrecords[1]->schoolregion));
                                    $sheet->setCellValue('Q5', 'Classified as Grade: '.preg_replace('/\D+/', '', $thirdrecords[1]->levelname).'  Section:  '.$thirdrecords[1]->sectionname);
                                    $sheet->setCellValue('AC5', $thirdrecords[1]->sydesc);
                                    $sheet->setCellValue('Q6', 'Name of Adviser/Teacher: '.$thirdrecords[1]->teachername);
                            
                                    $sixthgradescellno = 10;
                                    
                                    if(count($thirdrecords[1]->grades)>0)
                                    {
                                        $sixthtablecellno = 10;
                                        foreach($thirdrecords[1]->grades as $sixthrecordgrade)
                                        {
                                            $sheet->getStyle('Q'.$sixthtablecellno)->getAlignment()->setHorizontal('left');
                                            $sheet->mergeCells('Q'.$sixthtablecellno.':W'.$sixthtablecellno);
                                            $sheet->setCellValue('Q'.$sixthtablecellno, $sixthrecordgrade->subjdesc);
                                            $sheet->setCellValue('X'.$sixthtablecellno, $sixthrecordgrade->q1);
                                            $sheet->setCellValue('Z'.$sixthtablecellno, $sixthrecordgrade->q2);
                                            $sheet->setCellValue('AA'.$sixthtablecellno, $sixthrecordgrade->q3);
                                            $sheet->setCellValue('AB'.$sixthtablecellno, $sixthrecordgrade->q4);
                                            $sheet->mergeCells('K'.$sixthtablecellno.':M'.$sixthtablecellno);
                                            $sheet->setCellValue('AC'.$sixthtablecellno, $sixthrecordgrade->finalrating);
                                            $sheet->mergeCells('AD'.$sixthtablecellno.':AE'.$sixthtablecellno);
                                            $sheet->setCellValue('AD'.$sixthtablecellno, $sixthrecordgrade->remarks);
                                            $sheet->getStyle('X'.$sixthtablecellno.':AD'.$sixthtablecellno)->getFont()->setBold(false);
                                            $sixthtablecellno+=1;
                                        }
                                    }
                                #########  -- S I X T H  T A B L E --  ##########
                        //// ! B A C K P A G E ! ////
                    }
                }

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.xlsx"');
                $writer->save("php://output");
            }
            
        }else{
            return view('registrar.forms.form10.v3.records_elem')
                ->with('eachlevelsignatories', $eachlevelsignatories)
                ->with('acadprogid', $acadprogid)
                ->with('studinfo', $studinfo)
                ->with('eligibility', $eligibility)
            // return view('registrar.forms.form10.gradeselem')
                ->with('records', $records->sortByDesc('sydesc'))
                ->with('footer', $footer)
                ->with('gradelevels', collect($gradelevels)->sortBy('sortid'));
        }

    }    
    public function getrecords_jhs(Request $request)
    {
        $acadprogid = $request->get('acadprogid');
        $studentid = $request->get('studentid');
        
        $schoolinfo = Db::table('schoolinfo')
        ->select(
            'schoolinfo.schoolid',
            'schoolinfo.schoolname',
            'schoolinfo.authorized',
            'refcitymun.citymunDesc as division',
            'schoolinfo.district',
            'schoolinfo.districttext',
            'schoolinfo.divisiontext',
            'schoolinfo.regiontext',
            'schoolinfo.address',
            'schoolinfo.picurl',
            'refregion.regDesc as region'
        )
        ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
        ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
        ->first();
        $currentschoolyear = Db::table('sy')
            ->where('isactive','1')
            ->first();
        $gradelevels = DB::table('gradelevel')
            ->select(
                'gradelevel.id',
                'gradelevel.levelname',
                'gradelevel.sortid'
            )
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('academicprogram.id',$request->get('acadprogid'))
            ->where('gradelevel.deleted','0')
            ->get();

        foreach($gradelevels as $gradelevel)
        {

            $gradelevel->subjects = DB::table('subject_plot')
                ->select('subjects.*','subject_plot.syid','subject_plot.levelid','sy.sydesc')
                ->join('subjects','subject_plot.subjid','=','subjects.id')
                ->join('sy','subject_plot.syid','=','sy.id')
                ->where('subject_plot.deleted','0')
                ->where('subjects.deleted','0')
                ->where('subjects.inSF9','1')
                ->orderBy('subject_plot.plotsort','asc')
                ->where('subject_plot.syid', $currentschoolyear->id)
                ->where('subject_plot.levelid', $gradelevel->id)
                ->get();
                $gradelevel->subjects = collect($gradelevel->subjects)->unique('subjdesc');

        }
        // return $gradelevels;
            

        $school = DB::table('schoolinfo')
            ->first();
            

        $studinfo = Db::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'studinfo.lrn',
                'studinfo.dob',
                'studinfo.pob',
                'studinfo.contactno',
                'studinfo.gender',
                'studinfo.levelid',
                'studinfo.street',
                'studinfo.barangay',
                'studinfo.city',
                'studinfo.province',
                'studinfo.mothername',
                'studinfo.moccupation',
                'studinfo.fathername',
                'studinfo.foccupation',
                'studinfo.guardianname',
                'studinfo.ismothernum',
                'studinfo.isfathernum',
                'nationality.nationality',
                'studinfo.isguardannum as isguardiannum',
                'gradelevel.levelname',
                'studinfo.sectionid as ensectid',
                'gradelevel.id as enlevelid'
                )
            ->leftJoin('gradelevel','studinfo.levelid','gradelevel.id')
            ->leftJoin('nationality','studinfo.nationality','nationality.id')
            ->where('studinfo.id',$studentid)
            ->first();


        $studaddress = '';

        if($studinfo->street!=null)
        {
            $studaddress.=$studinfo->street.', ';
        }
        if($studinfo->barangay!=null)
        {
            $studaddress.=$studinfo->barangay.', ';
        }
        if($studinfo->city!=null)
        {
            $studaddress.=$studinfo->city.', ';
        }
        if($studinfo->province!=null)
        {
            $studaddress.=$studinfo->province.', ';
        }

        $studinfo->address = substr($studaddress,0,-2);

    
        $schoolyears = DB::table('enrolledstud')
            ->select(
                'enrolledstud.id',
                'enrolledstud.syid',
                'sy.sydesc',
                'academicprogram.id as acadprogid',
                'enrolledstud.levelid',
                'gradelevel.levelname',
                'enrolledstud.promotionstatus',
                'enrolledstud.sectionid',
                'enrolledstud.sectionid as ensectid',
                'sections.sectionname as section'
                )
            ->join('gradelevel','enrolledstud.levelid','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
            ->join('sy','enrolledstud.syid','sy.id')
            ->join('sections','enrolledstud.sectionid','sections.id')
            ->where('enrolledstud.deleted','0')
            ->where('academicprogram.id',$acadprogid)
            ->where('enrolledstud.studid',$studentid)
            ->where('enrolledstud.studstatus','!=','0')
            ->distinct()
            ->orderByDesc('enrolledstud.levelid')
            ->get();

            
        if(count($schoolyears) != 0){
            
            $currentlevelid = (object)array(
                'syid'      => $schoolyears[0]->syid,
                'levelid'   => $schoolyears[0]->levelid,
                'levelname' => $schoolyears[0]->levelname
            );

        }

        else{

            $currentlevelid = (object)array(
                'syid' => $currentschoolyear->id,
                'levelid' => $studinfo->levelid,
                'levelname' => $studinfo->levelname
            );

        }

        $failingsubjectsArray = array();

        $gradelevelsenrolled = array();

        $autorecords = array();
        
        foreach($schoolyears as $sy){

            array_push($gradelevelsenrolled,(object)array(
                'levelid' => $sy->levelid,
                'levelname' => $sy->levelname
            ));
            
            $generalaverage = array();

            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                if($grading_version->version == 'v2'){
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $sy->levelid,$studinfo->id,$sy->syid,null,null,$sy->sectionid);
                }else{
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,null,null,$sy->sectionid);
                }
                $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($sy->levelid);
                $grades = $studgrades;
                $grades = collect($grades)->sortBy('sortid')->values();
                $generalaverage = collect($grades)->where('id','G1')->values();
                unset($grades[count($grades)-1]);
                $grades = collect($grades)->where('isVisible','1')->values();

                // if($sy->levelid == 13)
                // {
                //     return collect($grades)->where('subjdesc','TLE');
                // }
                // return $grades;
            }
            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
            {
                
                $strand = 0;
                $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
                Session::put('schoolYear', $schoolyear);
                $subjects = \App\Models\Principal\SPP_Subject::getSubject(null,null,null,$sy->sectionid,null,null,null,null,'sf9',$schoolyear->id)[0]->data;
                // if(count($subjects)>0)
                // {
                //     return $subjects;
                // }
                $temp_subject = array();
        
                foreach($subjects as $item){
                    array_push($temp_subject,$item);
                }
                
                if($sy->acadprogid != 5){
                    array_push($temp_subject, (object)[
                        'id'=>'MAPEH1',
                        'subjdesc'=>'MAPEH',
                        "inMAPEH"=> 0,
                        "teacherid"=> 14,
                        "inSF9"=> 1,
                        "inTLE"=> 0,
                        "subj_per"=> 0,
                        "subj_sortid"=> "2M0"
                    ]);
                }
                
                
                $subjects = $temp_subject;
                $studgrades = \App\Models\Grades\GradesData::student_grades_detail($sy->syid,null,$sy->sectionid,null,$studinfo->id, $sy->levelid,$strand,null,$subjects);
                // return $studgrades;
                // if($id == 682){
                //     return $studgrades;
                // }
                $studgrades =  \App\Models\Grades\GradesData::get_finalrating($studgrades,$sy->acadprogid);;
                $finalgrade =  \App\Models\Grades\GradesData::general_average($studgrades);
                $generalaverage =  \App\Models\Grades\GradesData::get_finalrating($finalgrade,$sy->acadprogid);
                
                $temp_grades = array();
                $generalaverage = array();
                if(count($studgrades)>0)
                {
                    foreach($studgrades as $item){
                        if(!isset($item->isVisible))
                        {
                        $item->isVisible = 1;
                        }
                        if(isset($item->id))
                        {
                            if($item->id == 'G1'){
                                array_push($generalaverage,$item);
                            }else{
                                array_push($temp_grades,$item);
                            }
                        }else{
                                array_push($temp_grades,$item);
                        }
                       
                    }
                }
                $grades = collect($temp_grades)->sortBy('sortid')->values();
                $finalgrade[0]->finalrating =  number_format(collect($grades)->where('inMAPEH',0)->average('finalrating'));
                if($finalgrade[0]->finalrating >= 75){
                    $finalgrade[0]->actiontaken = 'PASSED';
                }else{
                    $finalgrade[0]->actiontaken = 'FAILED';
                }
                $finalgrade[0]->subjdesc = 'General Average';
                $finalgrade[0]->q1 = $finalgrade[0]->quarter1;
                $finalgrade[0]->q2 = $finalgrade[0]->quarter2;
                $finalgrade[0]->q3 = $finalgrade[0]->quarter3;
                $finalgrade[0]->q4 = $finalgrade[0]->quarter4;
                $generalaverage = $finalgrade;
            }
            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
            {
                if($sy->syid == 2){
                    $currentSchoolYear = DB::table('sy')->where('id',$sy->syid)->first();
                    Session::put('schoolYear',$currentSchoolYear);
                    $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$studentid,null);
                    
                    
                    if($request->has('action'))
                    {
                        $studentInfo[0]->data = DB::table('studinfo')
                                            ->select('studinfo.*','studinfo.sectionid as ensectid','studinfo.levelid as enlevelid','gradelevel.levelname','acadprogid')
                                            ->where('studinfo.id',$studentid)
                        
                                            ->join('gradelevel','studinfo.levelid','=','gradelevel.id')->get();
                        $studentInfo[0]->count = 1;
                        $studentInfo[0]->data[0]->teacherfirstname = "";
                        $studentInfo[0]->data[0]->teachermiddlename = " ";
                        $studentInfo[0]->data[0]->teacherlastname = "";
                    }
            
                    if($studentInfo[0]->count == 0){
            
                        $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$studentid,null,4);
                        
                        $studentInfo = DB::table('enrolledstud')
                            ->where('studid',$studentid)
                            ->where('enrolledstud.deleted',0)
                            ->select(
                                'enrolledstud.sectionid as ensectid',
                                'acadprogid',
                                'enrolledstud.studid as id',
                                'enrolledstud.strandid',
                                'enrolledstud.semid',
                                'lastname',
                                'firstname',
                                'middlename',
                                'lrn',
                                'dob',
                                'gender',
                                'levelname',
                                'sections.sectionname as ensectname'
                                )
                            ->join('gradelevel',function($join){
                                $join->on('enrolledstud.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->join('sections',function($join){
                                $join->on('enrolledstud.sectionid','=','sections.id');
                                $join->where('sections.deleted',0);
                            })
                                ->join('studinfo',function($join){
                                $join->on('enrolledstud.studid','=','studinfo.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->get();
                                            
                        $studentInfo = array((object)[
                                'data'=>   $studentInfo                             
                            ]);
                                            
                                            
                    }
                    $acad = $studentInfo[0]->data[0]->acadprogid;
                    $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($studentInfo[0]->data[0], true, 'sf9',2);    
                           
                    $grades = $gradesv4;
                    $grades = collect($grades)->unique('subjectcode');
                    
                }else{
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,null,null,$sy->sectionid);
               
                    $temp_grades = array();
                    $finalgrade = array();
                    foreach($studgrades as $item){
                        if($item->id == 'G1'){
                            array_push($finalgrade,$item);
                        }else{
                            array_push($temp_grades,$item);
                        }
                    }
                   
                    $studgrades = $temp_grades;
                    $grades = collect($studgrades)->sortBy('sortid')->values();
                }
            }
            // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
            // {
            //     $studinfo->acadprogid = $sy->acadprogid;
            //     $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
            //     Session::put('schoolYear', $schoolyear);
            //     $grades = \App\Models\Principal\GenerateGrade::reportCardV3($studinfo, true, 'sf9');
            //     $generalaverage = \App\Models\Principal\GenerateGrade::genAveV3($grades);

            // }
            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm'){
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($sy->levelid,$studinfo->id,$sy->syid,null,null,$sy->ensectid);
                
                $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($sy->levelid);
                $grades = $studgrades;
                $grades = collect($grades)->sortBy('sortid')->values();
                $generalaverage = collect($grades)->where('id','G1')->values();
                unset($grades[count($grades)-1]);

            }
            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
            {
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($studinfo->levelid,$studinfo->id,$sy->syid,null,null,$studinfo->ensectid);
                $subjects = array();
                $grades = $studgrades;
                $grades = collect($grades)->sortBy('sortid')->values();
                $generalaverage = collect($grades)->where('id','G1')->values();
                unset($grades[count($grades)-1]);
                $studgrades = collect($grades)->where('isVisible','1')->values();

            }else{
                if(DB::table('schoolinfo')->first()->schoolid == '405308') //fmcma
                {
                    $attendance_setup = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($sy->syid);
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,null,null,$sy->sectionid);
         
                    $temp_grades = array();
                    $generalaverage = array();
                    foreach($studgrades as $item){
                        if($item->id == 'G1'){
                            array_push($generalaverage,$item);
                        }else{
                                array_push($temp_grades,$item);
                        }
                    }
                   
                    $studgrades = $temp_grades;
                    $grades = collect($studgrades)->unique('subjid');
                    
                    $grades = collect($grades)->sortBy('sortid')->values();
                    if(DB::table('schoolinfo')->first()->schoolid == '405308') //fmcma
                    {
                        if(count($generalaverage)>0)
                        {
                            $generalaverage[0]->actiontaken = strtolower($generalaverage[0]->actiontaken) == 'passed'? 'PROMOTED' : $generalaverage[0]->actiontaken;
                        }
                    }
                    
                }else{
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,null,null,$sy->sectionid);
                    // return $studgrades;
                    $temp_grades = array();
                    $generalaverage = array();
                    foreach($studgrades as $item){
                        if($item->id == 'G1'){
                            array_push($generalaverage,$item);
                        }else{
                            array_push($temp_grades,$item);
                        }
                    }
                   
                    $studgrades = $temp_grades;
                    $grades = collect($studgrades)->sortBy('sortid')->values();
                }
            }
            
            
            $attendancesummary = DB::table('sf10attendance')
                ->where('sf10attendance.studentid',$studentid)
                ->where('acadprogid','4')
                ->where('sydesc',$sy->sydesc)
                ->where('deleted','0')
                ->get();
                
            if(count($attendancesummary) == 0)
            {
                $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
                Session::put('schoolYear', $schoolyear);
                $attendancesummary = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($schoolyear->id, $sy->levelid);
                foreach( $attendancesummary as $item){
                    $item->type = 1;
                    $item->numdays = $item->days;
                    
                    $sf2_setup = DB::table('sf2_setup')
                        ->where('month',$item->month)
                        ->where('year',$item->year)
                        ->where('sectionid',$sy->sectionid)
                        ->where('sf2_setup.deleted',0)
                        ->join('sf2_setupdates',function($join){
                            $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                            $join->where('sf2_setupdates.deleted',0);
                        })
                        ->select('dates')
                        ->get();

                    if(count($sf2_setup) == 0){

                        $sf2_setup = DB::table('sf2_setup')
                                    ->where('month',$item->month)
                                    ->where('year',$item->year)
                                    ->where('sectionid',$sy->sectionid)
                                    ->where('sf2_setup.deleted',0)
                                    ->join('sf2_setupdates',function($join){
                                        $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                        $join->where('sf2_setupdates.deleted',0);
                                    })
                                    ->select('dates')
                                    ->get();

                    }

                    $temp_days = array();

                    foreach($sf2_setup as $sf2_setup_item){
                    array_push($temp_days,$sf2_setup_item->dates);
                    }

                    $student_attendance = DB::table('studattendance')
                                        ->where('studid',$studinfo->id)
                                        ->where('deleted',0)
                                        ->whereIn('tdate',$temp_days)
                                        // ->where('syid',$syid)
                                        ->distinct('tdate')
                                        ->distinct()
                                        // ->select([
                                        //     'present',
                                        //     'absent',
                                        //     'tardy',
                                        //     'cc',
                                        //     'tdate'
                                        // ])
                                        ->get();

                    $student_attendance = collect($student_attendance)->unique('tdate')->values();

                    $item->present = collect($student_attendance)->where('present',1)->count() + collect($student_attendance)->where('tardy',1)->count() + collect($student_attendance)->where('cc',1)->count() + (collect($student_attendance)->where('presentam',1)->count() * 0.5) + (collect($student_attendance)->where('presentpm',1)->count() * 0.5) + (collect($student_attendance)->where('absentam',1)->count() * 0.5) + (collect($student_attendance)->where('absentpm',1)->count() * 0.5) + collect($student_attendance)->where('lateam',1)->count()  + collect($student_attendance)->where('latepm',1)->count() + collect($student_attendance)->where('ccam',1)->count() + collect($student_attendance)->where('ccpm',1)->count()  ;
                    $item->present = $item->present > $item->numdays ? $item->numdays : $item->present;
                    $item->absent = collect($student_attendance)->where('absent',1)->count() + (collect($student_attendance)->where('absentam',1)->count() * 0.5) + (collect($student_attendance)->where('absentpm',1)->count() * 0.5);
                    $item->numdayspresent = $item->present;
                    $item->numdaysabsent = $item->absent;
                    $item->monthstr = substr($item->monthdesc, 0, 3);
                }
                $attendancesummary = collect($attendancesummary)->sortBy('sort')->values()->all();
            }

            
            $filtergrades = collect($grades)->where('isVisible','1')->values()->all();
            if(count($filtergrades) == 0)
            {
                $grades = collect($grades)->where('isVisible','1')->values()->all();
            }else{
                
                $grades = $filtergrades;
            }
            // return $grades;
            $gradesadd = 0;
            if(count($grades)>0)
            {
                foreach($grades as $grade)
                {
                    if(!collect($grade)->has('inMAPEH'))
                    {
                        $grade->inMAPEH = 0;
                    }
                    if(!collect($grade)->has('inTLE'))
                    {
                        $grade->inTLE = 0;
                    }
                    if(!collect($grade)->has('subjdesc'))
                    {
                        if(collect($grade)->has('subjectcode'))
                        {
                            $grade->subjdesc = $grade->subjectcode;
                        }
                        $grade->q1 = $grade->quarter1;
                        $grade->q2 = $grade->quarter2;
                        $grade->q3 = $grade->quarter3;
                        $grade->q4 = $grade->quarter4;
                    }else{
                        // $grade->subjdesc = ucwords(strtolower($grade->subjdesc));
                    }
                    // 0 = noteditable ; 1 = for adding (first time) ; 2 = editable;
                    $grade->q1stat = 0;
                    $grade->q2stat = 0;
                    $grade->q3stat = 0;
                    $grade->q4stat = 0;
                    

                    $complete = 0;
                    $chekifaddinautoexist = DB::table('sf10grades_addinauto')
                            ->where('studid',$studinfo->id)
                            ->where('subjid',$grade->subjid ?? $grade->id)
                            ->where('levelid',$sy->levelid)
                            ->where('deleted',0)
                            ->get();

                    if(count($chekifaddinautoexist)>0)
                    {
                        $gradesadd += 1;
                    }
                    if(collect($chekifaddinautoexist)->where('quarter',1)->count() > 0)
                    {
                        $grade->q1stat = 2;
                        $grade->q1    = collect($chekifaddinautoexist)->where('quarter',1)->first()->grade;
                        $complete+=1;;
                    }
                    if(collect($chekifaddinautoexist)->where('quarter',2)->count() > 0)
                    {
                        $grade->q2stat = 2;
                        $grade->q2    = collect($chekifaddinautoexist)->where('quarter',2)->first()->grade;
                        $complete+=1;;
                    }
                    if(collect($chekifaddinautoexist)->where('quarter',3)->count() > 0)
                    {
                        $grade->q3stat = 2;
                        $grade->q3    = collect($chekifaddinautoexist)->where('quarter',3)->first()->grade;
                        $complete+=1;;
                    }
                    if(collect($chekifaddinautoexist)->where('quarter',4)->count() > 0)
                    {
                        $grade->q4stat = 2;
                        $grade->q4    = collect($chekifaddinautoexist)->where('quarter',4)->first()->grade;
                        $complete+=1;;
                    }

                    if($grade->q1 == 0)
                    {
                        $grade->q1 = null;
                        $grade->q1stat = 1;
                    }else{
                        $complete+=1;;
                    }
                    if($grade->q2 == 0)
                    {
                        $grade->q2 = null;
                        $grade->q2stat = 1;
                    }else{
                        $complete+=1;;
                    }
                    if($grade->q3 == 0)
                    {
                        $grade->q3 = null;
                        $grade->q3stat = 1;
                    }else{
                        $complete+=1;;
                    }
                    if($grade->q4 == 0)
                    {
                        $grade->q4 = null;
                        $grade->q4stat = 1;
                    }else{
                        $complete+=1;;
                    }
                    if($grade->q1 == null)
                    {
                        $grade->q1stat = 1;
                    }
                    if($grade->q2 == null)
                    {
                        $grade->q2stat = 1;
                    }
                    if($grade->q3 == null)
                    {
                        $grade->q3stat = 1;
                    }
                    if($grade->q4 == null)
                    {
                        $grade->q4stat = 1;
                    }

                    if($complete < 4)
                    {
                        $qg = null;
                        $remarks = null;
                    }else{
                        $qg = ($grade->q1 + $grade->q2 + $grade->q3 + $grade->q4) / 4;
                        if($qg>=75){
        
                            $remarks = "PASSED";
        
                        }elseif($qg == null){
        
                            $remarks = null;
        
                        }else{
                            $remarks = "FAILED";
                        }
                        
                        if($qg == 0)
                        {
                            $qg = null;
                            $remarks = null;
                        }
                    }
                    
                    $grade->subjcode = null;
                    $grade->subjtitle = $grade->subjdesc;
                    $grade->quarter1 = (number_format($grade->q1) > 0 ? number_format($grade->q1) : null);
                    $grade->quarter2 = (number_format($grade->q2) > 0 ? number_format($grade->q2) : null);
                    $grade->quarter3 = (number_format($grade->q3) > 0 ? number_format($grade->q3) : null);
                    $grade->quarter4 = (number_format($grade->q4) > 0 ? number_format($grade->q4) : null);
                    $grade->finalrating = (number_format($qg) > 0 ? number_format($qg) : null);
                    $grade->remarks = $remarks;
                }
            }
            // return $grades;
            
            

            $teachername = '';

            $getTeacher = Db::table('sectiondetail')
                ->select(
                    'teacher.title',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix'
                    )
                ->join('teacher','sectiondetail.teacherid','teacher.id')
                ->where('sectiondetail.sectionid',$sy->sectionid)
                ->where('sectiondetail.syid',$sy->syid)
                ->where('sectiondetail.deleted','0')
                ->first();

            if($getTeacher)
            {
                if($getTeacher->title!=null)
                {
                    $teachername.=$getTeacher->title.' ';
                }
                if($getTeacher->firstname!=null)
                {
                    $teachername.=$getTeacher->firstname.' ';
                }
                if($getTeacher->middlename!=null)
                {
                    $teachername.=$getTeacher->middlename[0].'. ';
                }
                if($getTeacher->middlename!=null)
                {
                    $teachername.=$getTeacher->lastname.' ';
                }
                if($getTeacher->lastname!=null)
                {
                    $teachername.=$getTeacher->suffix.' ';
                }
            }
            $subjaddedforauto     = DB::table('sf10grades_subjauto')
                                    ->where('studid',$studentid)
                                    ->where('syid',$sy->syid)
                                    ->where('levelid',$sy->levelid)
                                    ->where('deleted','0')
                                    ->get();
                
            // $attendance = AttendanceReport::schoolYearBasedAttendanceReport($sy);
            // return $grades;
            if($gradesadd > 0)
            {
                $generalaverage[0]->finalrating = number_format(collect($grades)->where('subjCom', null)->avg('finalrating'));
            }
            // if(count($generalaverage) == 0)
            // {
            //     array_push($generalaverage,(object)array(
            //         'subjdesc'      => 'General Average',
            //         'q1'            => null,
            //         'q2'            => null,
            //         'q3'            => null,
            //         'q4'            => null,
            //         'quarter1'      => null,
            //         'quarter2'      => null,
            //         'quarter3'      => null,
            //         'quarter4'      => null,
            //         'remarks'       => null,
            //         'finalrating'   => collect($grades)->avg('finalrating')
            //     ));
            // }
            if(count($grades)>0)
            {
                array_push($autorecords, (object) array(
                        'id'                => null,
                        'syid'              => $sy->syid,
                        'sydesc'            => $sy->sydesc,
                        'levelid'           => $sy->levelid,
                        'levelname'         => $sy->levelname,
                        'sectionid'         => $sy->sectionid,
                        'sectionname'       => $sy->section,
                        'teachername'       => substr($teachername,0,-2),
                        'schoolid'          => $schoolinfo->schoolid,
                        'schoolname'        => $schoolinfo->schoolname,
                        'schooladdress'     => $schoolinfo->address,
                        'schooldistrict'    => $schoolinfo->districttext != null ? $schoolinfo->districttext : $schoolinfo->district,
                        'schooldivision'    => $schoolinfo->divisiontext != null ? $schoolinfo->divisiontext : $schoolinfo->division,
                        'schoolregion'      => $schoolinfo->regiontext != null ? $schoolinfo->regiontext : $schoolinfo->region,
                        'credit_advance'        => null,
                        'credit_lack'        => null,
                        'noofyears'        => null,
                        'promotionstatus'        => $sy->promotionstatus,
                        'type'              => 1,
                        'grades'            => $grades,
                        'generalaverage'    => $generalaverage,
                        'subjaddedforauto'  => $subjaddedforauto,
                        'attendance'        => $attendancesummary,
                        'remedials'         => array()
                )); 
            }           

        }
        
        if(count(collect($gradelevelsenrolled)->unique()) == 2){

            $completed = 1;

        }

        elseif(count(collect($gradelevelsenrolled)->unique()) < 2){

            $completed = 0;

        }


        $manualrecords = DB::table('sf10')
            ->select('sf10.id','sf10.syid','sf10.sydesc','sf10.levelid','gradelevel.levelname','sf10.sectionid','sf10.sectionname','sf10.teachername','sf10.schoolid','sf10.schoolname','sf10.schooladdress','sf10.schooldistrict','sf10.schooldivision','sf10.schoolregion','sf10.remarks','sf10.recordincharge','sf10.datechecked','sf10.credit_advance','sf10.credit_lack','sf10.noofyears')
            ->join('gradelevel','sf10.levelid','=','gradelevel.id')
            ->where('sf10.studid', $studentid)
            ->where('sf10.acadprogid', $acadprogid)
            ->where('sf10.deleted','0')
            ->get();

        if(count($manualrecords)>0)
        {
            foreach($manualrecords as $manualrecord)
            {
                $manualrecord->type = 2;

                $grades = DB::table('sf10grades_junior')
                        ->where('headerid', $manualrecord->id)
                        ->where('deleted','0')
                        ->get();

                if(count($grades)>0)
                {
                    foreach($grades as $grade)
                    {
                        // $grade->subjectname = ucwords(strtolower($grade->subjectname));
                
                        $grade->q1stat = 0;
                        $grade->q2stat = 0;
                        $grade->q3stat = 0;
                        $grade->q4stat = 0;
                        
                        if($grade->q1 == 0)
                        {
                            $grade->q1 = null;
                        }
                        if($grade->q2 == 0)
                        {
                            $grade->q2 = null;
                        }
                        if($grade->q3 == 0)
                        {
                            $grade->q3 = null;
                        }
                        if($grade->q4 == 0)
                        {
                            $grade->q4 = null;
                        }
                        $grade->subjcode = null;
                        $grade->subjtitle = $grade->subjectname;
                        $grade->subjdesc = $grade->subjectname;
                        $grade->quarter1 = $grade->q1;
                        $grade->quarter2 = $grade->q2;
                        $grade->quarter3 = $grade->q3;
                        $grade->quarter4 = $grade->q4;
                    }
                }
                $remedialclasses = DB::table('sf10remedial_junior')
                    ->where('studid', $studentid)
                    ->where('levelid', $manualrecord->levelid)
                    ->where('sydesc', $manualrecord->sydesc)
                    ->where('deleted','0')
                    ->get();

                
                $attendance = DB::table('sf10attendance')
                    ->select('sf10attendance.*','numdays as days')
                    ->where('headerid',$manualrecord->id)
                    ->where('acadprogid','4')
                    ->where('deleted','0')
                    ->get();
                    // return $grades;
                 $generalaverage =   collect($grades)->filter(function ($value, $key) {
                    if(strtolower($value->subjdesc) == 'general average')
                    {
                        return $value;
                    }
                })->values();
                    $manualrecord->promotionstatus               = null;
                $manualrecord->grades               = $grades;
                $manualrecord->generalaverage       = $generalaverage;
                $manualrecord->subjaddedforauto     = array();
                $manualrecord->attendance           = $attendance;
                $manualrecord->remedials            = $remedialclasses;
            }
        }

        $records = collect();
        $records = $records->merge($autorecords);
        $records = $records->merge($manualrecords);

        $footer = DB::table('sf10_footer_junior')
            ->where('studid', $studentid)
            ->where('deleted','0')
            ->first();
            

        if(!$footer)
        {
            $footer = (object)array(
                'admissiontograde'        =>  null,
                'copyforupper'        =>  null,
                'purpose'        =>  null,
                'classadviser'                 =>  null,
                'recordsincharge'            =>  null,
                'copysentto'            =>  null,
                'address'            =>  null
            );
        }

        $eligibility = DB::table('sf10eligibility_junior')
            ->where('studid', $studentid)
            ->where('deleted','0')
            ->first();

        if(!$eligibility)
        {
            $eligibility = (object)array(
                'completer'  =>  0,
                'genave'     =>  0,
                'citation'          =>  null,
                'schoolid'          =>  null,
                'schoolname'        =>  null,
                'schooladdress'     =>  null,
                'peptpasser'        =>  0,
                'peptrating'        =>  null,
                'alspasser'         =>  0,
                'alsrating'         =>  null,
                'examdate'          =>  null,
                'centername'        =>  null,
                'centeraddress'     =>  null,
                'remarks'           =>  null,
                'specifyothers'     =>  null,
                'guardianaddress'     =>  null,
                'sygraduated'     =>  null,
                'courseschool'          =>  null,
                'courseyear'          =>  null,
                'coursegenave'          =>  null,
                'totalnoofyears'     =>  null
            );
        }
        
        $eachlevelsignatories = DB::table('sf10bylevelsign')
            ->where('studid',$studentid)
            ->where('deleted','0')
            ->get();
        if($request->has('export'))
        {            
            if(count($records)>0)
            {
                foreach($records as $record)
                {
                    $record->withdata = 1;
                    $record->sortid = 0;
    
                    if(preg_replace('/\D+/', '', $record->levelname) == 7)
                    {
                        $record->sortid = 1;
                        $record->levelid = 10;
                        $record->levelname = 'GRADE 7';
                    }
                    elseif(preg_replace('/\D+/', '', $record->levelname) == 8)
                    {
                        $record->sortid = 2;
                        $record->levelid = 11;
                        $record->levelname =  'GRADE 8';
                    }
                    elseif(preg_replace('/\D+/', '', $record->levelname) == 9)
                    {
                        $record->sortid = 3;
                        $record->levelid = 12;
                        $record->levelname =  'GRADE 9';
                    }
                    elseif(preg_replace('/\D+/', '', $record->levelname) == 10)
                    {
                        $record->sortid = 4;
                        $record->levelid = 13;
                        $record->levelname =  'GRADE 10';
                    }
                    
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                    {
                        $record->noofgrades = collect($record->grades)->where('subjdesc','!=','General Average')->count() + count($record->subjaddedforauto);
                    }else{
                        $record->noofgrades = collect($record->grades)->where('subjdesc','!=','General Average')->count();
                    }
                }
            }
            $maxgradecount = collect($records)->pluck('noofgrades')->max();
            
            if($maxgradecount == 0)
            {
                $maxgradecount = 12;
            }
            $withnodata = array();
            for($x = 1; $x <= 4; $x++)
            {
                if(collect($records)->where('sortid',$x)->count() == 0)
                {
                    if($x == 1)
                    {
                        $recordsortid = 1;
                        $recordlevelid = 10;
                        $recordlevelname = 'GRADE 7';
                    }
                    elseif($x == 2)
                    {
                        $recordsortid = 2;
                        $recordlevelid = 11;
                        $recordlevelname =  'GRADE 8';
                    }
                    elseif($x == 3)
                    {
                        $recordsortid = 3;
                        $recordlevelid = 12;
                        $recordlevelname =  'GRADE 9';
                    }
                    elseif($x == 4)
                    {
                        $recordsortid = 4;
                        $recordlevelid = 13;
                        $recordlevelname =  'GRADE 10';
                    }
                    // $records = $records->merge([
                    //     'sortid'    => $x,
                    //     'withdata'  => 0
                    // ])
                    array_push($withnodata, (object)array(
                        // 'sydesc'=>$schoolyears[0]->syid
                        'id'                => null,
                        'syid'              => null,
                        'sydesc'            => null,
                        'levelid'           => $recordlevelid,
                        'levelname'         => $recordlevelname,
                        'sectionid'         => null,
                        'promotionstatus'         => null,
                        'sectionname'       => null,
                        'teachername'       => null,
                        'schoolid'          => null,
                        'schoolname'        => null,
                        'schooladdress'     => null,
                        'schooldistrict'    => null,
                        'schooldivision'    => null,
                        'schoolregion'      => null,
                        'type'              => 1,
                        'grades'            => array(),
                        'generalaverage'    => array(),
                        'subjaddedforauto'  => array(),
                        'attendance'        => array(),
                        'noofgrades'        => 0,
                        'credit_advance'        => null,
                        'credit_lack'        => null,
                        'noofyears'        => null,
                        'remedials'         => array(),
                        'sortid'            => $x,
                        'withdata'          => 0,
                    ));
                }
            }
            $records = $records->merge($withnodata);
            
            
            if($request->get('exporttype') == 'pdf')
            {
                $subjects = DB::table('subject_plot')
                    ->select('subjects.id','subjcode','subjects.subjdesc','subject_plot.strandid','subject_plot.plotsort','subject_plot.semid','subject_plot.syid','subject_plot.levelid','subject_plot.strandid','inMAPEH')
                    ->join('subjects','subject_plot.subjid','=','subjects.id')
                    ->where('subjects.inSF9', 1)
                    ->where('subjects.deleted', 0)
                    ->where('subject_plot.levelid', '!=','14')
                    ->where('subject_plot.levelid', '!=','15')
                    ->where('subject_plot.deleted', 0)
                    ->orderBy('subject_plot.plotsort','asc')
                    ->get();  
                  
                // return $records;
                $format = $request->get('format');
                $template = 'registrar/forms/deped/form10_jhs';
                if($request->has('papersize'))
                {
                    $papersize = $request->get('papersize');
                }else{
                    $papersize = null;
                }
                if($request->has('format'))
                {
                    if($format == 'deped')
                    {
                        $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                        $records = array_chunk($records, 2);
                        $template = 'registrar/forms/deped/form10_jhs';
                    }elseif($format == 'deped-2')//old
                    {
                        $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                        $records = array_chunk($records, 2);
                        $template = 'registrar/pdf/pdf_schoolform10_junior';
                        $format = 'school';
                    }elseif($format == 'school'){
                        $template = 'registrar/pdf/pdf_schoolform10_junior';
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                        {
                            $records = collect($records->sortBy('sydesc')->sortBy('sortid')->values()->all())->toArray();
                            if($request->get('layout') == 1)
                            {
                                $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_juniorhccsi_spr2',compact('eligibility','studinfo','records','maxgradecount','footer','format','gradelevels','papersize')); 
                                return $pdf->stream('Student Permanent Record - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                            }else{
                                $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_juniorhccsi_spr',compact('eligibility','studinfo','records','maxgradecount','footer','format','gradelevels','papersize')); 
                                return $pdf->stream('Student Permanent Record - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                            }
                        }else{
                            $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'faa')
                            {
                                $records = array_chunk($records, 2);
                                $template = 'registrar/pdf/pdf_schoolform10_juniorlhs';
                            }
                            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                            {
                                $records = array_chunk($records, 2);
                                $template = 'registrar/pdf/pdf_schoolform10_juniorsjaes';
                            }
                            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
                            {
                                $records = array_chunk($records, 2);
                                $template = 'registrar/pdf/pdf_schoolform10_juniorxai';
                            }
                            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                            {
                                $template = 'registrar/pdf/pdf_schoolform10_juniordcc';
                            }
                            else
                            {
                                $records = array_chunk($records, 2);
                                $template = 'registrar/pdf/pdf_schoolform10_junior';
                            }
                        }
                    }
                }else{
                    $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                    $records = array_chunk($records, 2);
                }
                // return collect($schoolinfo);
                // return $records[0][0]->schoolname;
                $layout = $request->get('layout');
                // return $template;
                $pdf = PDF::loadview($template,compact('eligibility','studinfo','records','maxgradecount','footer','format','acadprogid','schoolinfo','subjects','gradelevels','layout','papersize')); 
                return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');  

            }elseif($request->get('exporttype') == 'excel'){
                
                $inputFileType = 'Xlsx';
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                {
                }
                elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs')
                {
                    $inputFileName = base_path().'/public/excelformats/lhs/sf10_jhs.xlsx';
                }
                // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
                // {
                //     $inputFileName = base_path().'/public/excelformats/lhs/sf10_jhs.xlsx';
                // }
                else{
                    if(DB::table('schoolinfo')->first()->schoolid == '405308')
                    {
                        $inputFileName = base_path().'/public/excelformats/fmcma/sf10_jhs.xlsx';
                    }else{
                        $inputFileName = base_path().'/public/excelformats/sf10_jhs.xlsx';
                    }
                }

                /**  Create a new Reader of the type defined in $inputFileType  **/
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                /**  Advise the Reader of which WorkSheets we want to load  **/
                $reader->setLoadAllSheets();
                /**  Load $inputFileName to a Spreadsheet Object  **/
                $spreadsheet = $reader->load($inputFileName);
                
                $sheet = $spreadsheet->getSheet(0);

                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                {
                    $inputFileName = base_path().'/public/excelformats/hcb/sf10_jhs.xlsx';
                    
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Advise the Reader of which WorkSheets we want to load  **/
                    $reader->setLoadAllSheets();
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($inputFileName);
                    
                    $sheet = $spreadsheet->getSheet(0);

                    $sheet->setCellValue('D8', $studinfo->lastname);
                    $sheet->setCellValue('L8', $studinfo->firstname);
                    $sheet->setCellValue('V8', $studinfo->suffix);
                    $sheet->setCellValue('AB8', $studinfo->middlename);    
                    
                    $sheet->setCellValue('H9', $studinfo->lrn);
                    $sheet->getStyle('H9')->getNumberFormat()->setFormatCode('0');
                    $sheet->setCellValue('U9', date('m/d/Y', strtotime($studinfo->dob)));
                    $sheet->setCellValue('AB9', $studinfo->gender);
    
                    if($eligibility->completer == 1)
                    {
                        $sheet->setCellValue('B13', '/');
                    }
                    $sheet->setCellValue('P13', $eligibility->genave);
                    $sheet->setCellValue('W13', $eligibility->citation);
    
                    $sheet->setCellValue('I14', $eligibility->schoolname);
                    $sheet->setCellValue('S14', $eligibility->schoolid);
                    $sheet->setCellValue('Z14', $eligibility->schooladdress);
                    
                    if($eligibility->peptpasser == 1)
                    {
                        $sheet->setCellValue('B17', '/');
                    }
                    $sheet->setCellValue('I17', $eligibility->peptrating);
                    if($eligibility->alspasser == 1)
                    {
                        $sheet->setCellValue('L17', '/');
                    }
                    $sheet->setCellValue('S17', $eligibility->alsrating);
                    $sheet->setCellValue('AA17', $eligibility->specifyothers);
                    
                    $sheet->setCellValue('L18', $eligibility->examdate);
                    $sheet->setCellValue('X18', $eligibility->centername);
    
                    $startcellno = 22;
    
                    // F I R S T
    
                    $records_firstrow = $records[0];
                    
                    if(count($records_firstrow[0]->generalaverage)>0)
                    {
                        $sheet->setCellValue('L31', $records_firstrow[0]->generalaverage[0]->finalrating);
                        $sheet->setCellValue('N31', $records_firstrow[0]->generalaverage[0]->actiontaken);
                    }
                    if(count($records_firstrow[1]->generalaverage)>0)
                    {
                        $sheet->setCellValue('AB31', $records_firstrow[1]->generalaverage[0]->finalrating);
                        $sheet->setCellValue('AD31', $records_firstrow[1]->generalaverage[0]->actiontaken);
                    }
                    
                    $records_secondrow = $records[1];
                    
                    if(count($records_secondrow[0]->generalaverage)>0)
                    {
                        $sheet->setCellValue('L49', $records_secondrow[0]->generalaverage[0]->finalrating);
                        $sheet->setCellValue('N49', $records_secondrow[0]->generalaverage[0]->actiontaken);
                    }
                    if(count($records_secondrow[1]->generalaverage)>0)
                    {
                        $sheet->setCellValue('AB49', $records_secondrow[1]->generalaverage[0]->finalrating);
                        $sheet->setCellValue('AD49', $records_secondrow[1]->generalaverage[0]->actiontaken);
                    }
                    
                    $sheet->setCellValue('C'.$startcellno, $records_firstrow[0]->schoolname);
                    $sheet->setCellValue('M'.$startcellno, $records_firstrow[0]->schoolid);
                    $sheet->setCellValue('S'.$startcellno, $records_firstrow[1]->schoolname);
                    $sheet->setCellValue('AC'.$startcellno, $records_firstrow[1]->schoolid);
    
                    $startcellno += 1;
                    
                    $sheet->setCellValue('C'.$startcellno, $records_firstrow[0]->schooldistrict);
                    $sheet->setCellValue('H'.$startcellno, $records_firstrow[0]->schooldivision);
                    $sheet->setCellValue('N'.$startcellno, $records_firstrow[0]->schoolregion);
                    $sheet->setCellValue('S'.$startcellno, $records_firstrow[1]->schooldistrict);
                    $sheet->setCellValue('X'.$startcellno, $records_firstrow[1]->schooldivision);
                    $sheet->setCellValue('AD'.$startcellno, $records_firstrow[1]->schoolregion);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('E'.$startcellno, preg_replace('/\D+/', '', $records_firstrow[0]->levelname));
                    $sheet->setCellValue('I'.$startcellno,  $records_firstrow[0]->sectionname);
                    $sheet->setCellValue('N'.$startcellno,  $records_firstrow[0]->sydesc);
                    $sheet->setCellValue('U'.$startcellno, preg_replace('/\D+/', '', $records_firstrow[1]->levelname));
                    $sheet->setCellValue('Y'.$startcellno,  $records_firstrow[1]->sectionname);
                    $sheet->setCellValue('AD'.$startcellno,  $records_firstrow[1]->sydesc);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('D'.$startcellno, $records_firstrow[0]->teachername);
                    $sheet->setCellValue('T'.$startcellno, $records_firstrow[1]->teachername);
                    
                    $startcellno += 4;
                    
                    $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                    
                    if(count($records_firstrow[0]->grades) == 0)
                    {
                        $firsttable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $firsttable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('A'.$x.':C'.$x);
                            $sheet->mergeCells('D'.$x.':E'.$x);
                            $sheet->mergeCells('F'.$x.':G'.$x);
                            $sheet->mergeCells('H'.$x.':I'.$x);
                            $sheet->mergeCells('J'.$x.':K'.$x);
                            $sheet->mergeCells('L'.$x.':M'.$x);
                            $sheet->mergeCells('N'.$x.':O'.$x);
                        }
                    }else{
                        $firsttable_cellno = $startcellno;
                        foreach($records_firstrow[0]->grades as $firstgrades)
                        {
                            if(strtolower($firstgrades->subjdesc) != 'general average')
                            {
                                if(mb_strlen ($firstgrades->subjdesc) <= 22 && mb_strlen ($firstgrades->subjdesc) > 13)
                                {
                                    $sheet->getRowDimension($firsttable_cellno)->setRowHeight(25,'pt');  
                                }elseif(mb_strlen ($firstgrades->subjdesc) > 22)
                                {
                                    $sheet->getRowDimension($firsttable_cellno)->setRowHeight(45,'pt'); 
                                }
                                $sheet->getStyle('A'.$firsttable_cellno.':N'.$firsttable_cellno)->getAlignment()->setVertical('center');
                                $sheet->getStyle('A'.$firsttable_cellno)->getAlignment()->setWrapText(true);
                                
                                $inmapeh = '';
                                if($firstgrades->inMAPEH == 1)
                                {
                                    $inmapeh = '     ';
                                }
                                $sheet->mergeCells('A'.$firsttable_cellno.':C'.$firsttable_cellno);
                                $sheet->setCellValue('A'.$firsttable_cellno, $inmapeh.$firstgrades->subjdesc);
                                $sheet->mergeCells('D'.$firsttable_cellno.':E'.$firsttable_cellno);
                                $sheet->setCellValue('D'.$firsttable_cellno, $firstgrades->q1);
                                $sheet->mergeCells('F'.$firsttable_cellno.':G'.$firsttable_cellno);
                                $sheet->setCellValue('F'.$firsttable_cellno, $firstgrades->q2);
                                $sheet->mergeCells('H'.$firsttable_cellno.':I'.$firsttable_cellno);
                                $sheet->setCellValue('H'.$firsttable_cellno, $firstgrades->q3);
                                $sheet->mergeCells('J'.$firsttable_cellno.':K'.$firsttable_cellno);
                                $sheet->setCellValue('J'.$firsttable_cellno, $firstgrades->q4);
                                $sheet->mergeCells('L'.$firsttable_cellno.':M'.$firsttable_cellno);
                                $sheet->setCellValue('L'.$firsttable_cellno, $firstgrades->finalrating);
                                $sheet->mergeCells('N'.$firsttable_cellno.':O'.$firsttable_cellno);
                                $sheet->setCellValue('N'.$firsttable_cellno, $firstgrades->remarks);
                                $firsttable_cellno+=1;
                            }
                        }
                    }
                    
                    
                    if(count($records_firstrow[1]->grades) == 0)
                    {
                        $secondtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        
                        for($x = $secondtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('Q'.$x.':S'.$x);
                            $sheet->mergeCells('T'.$x.':U'.$x);
                            $sheet->mergeCells('V'.$x.':W'.$x);
                            $sheet->mergeCells('X'.$x.':Y'.$x);
                            $sheet->mergeCells('Z'.$x.':AA'.$x);
                            $sheet->mergeCells('AB'.$x.':AC'.$x);
                            $sheet->mergeCells('AD'.$x.':AE'.$x);
                        }
                    }else{
                        $secondtable_cellno = $startcellno;
                        foreach($records_firstrow[1]->grades as $secondgrades)
                        {
                            if(strtolower($secondgrades->subjdesc) != 'general average')
                            {
                                if(mb_strlen ($secondgrades->subjdesc) <= 22 && mb_strlen ($secondgrades->subjdesc) > 13)
                                {
                                    $sheet->getRowDimension($secondtable_cellno)->setRowHeight(25,'pt');  
                                }elseif(mb_strlen ($secondgrades->subjdesc) > 22)
                                {
                                    $sheet->getRowDimension($secondtable_cellno)->setRowHeight(45,'pt'); 
                                }
                                $sheet->getStyle('Q'.$secondtable_cellno.':AD'.$secondtable_cellno)->getAlignment()->setVertical('center');
        
                                $sheet->getStyle('Q'.$secondtable_cellno)->getAlignment()->setWrapText(true);
                                $inmapeh = '';
                                if($secondgrades->inMAPEH == 1)
                                {
                                    $inmapeh = '     ';
                                }
                                $sheet->mergeCells('Q'.$secondtable_cellno.':S'.$secondtable_cellno);
                                $sheet->setCellValue('Q'.$secondtable_cellno, $inmapeh.$secondgrades->subjdesc);
                                $sheet->getStyle('Q'.$secondtable_cellno)->getAlignment()->setWrapText(true);
                                $sheet->mergeCells('T'.$secondtable_cellno.':U'.$secondtable_cellno);
                                $sheet->setCellValue('T'.$secondtable_cellno, $secondgrades->q1);
                                $sheet->mergeCells('V'.$secondtable_cellno.':W'.$secondtable_cellno);
                                $sheet->setCellValue('V'.$secondtable_cellno, $secondgrades->q2);
                                $sheet->mergeCells('X'.$secondtable_cellno.':Y'.$secondtable_cellno);
                                $sheet->setCellValue('X'.$secondtable_cellno, $secondgrades->q3);
                                $sheet->mergeCells('Z'.$secondtable_cellno.':AA'.$secondtable_cellno);
                                $sheet->setCellValue('Z'.$secondtable_cellno, $secondgrades->q4);
                                $sheet->mergeCells('AB'.$secondtable_cellno.':AC'.$secondtable_cellno);
                                $sheet->setCellValue('AB'.$secondtable_cellno, $secondgrades->finalrating);
                                $sheet->mergeCells('AD'.$secondtable_cellno.':AE'.$secondtable_cellno);
                                $sheet->setCellValue('AD'.$secondtable_cellno, $secondgrades->remarks);
                                $secondtable_cellno+=1;
                            }
                        }
                    }
    
                    $startcellno += $maxgradecount; // general average
    
                    $startcellno += 2; // attendance
    
                    if(count($records_firstrow[0]->attendance) > 0)
                    {
                        $sheet->setCellValue('D'.$startcellno, collect($records_firstrow[0]->attendance)->sum('days'));
                        $sheet->setCellValue('I'.$startcellno, collect($records_firstrow[0]->attendance)->sum('present'));
                    }
                    
                    if(count($records_firstrow[1]->attendance) > 0)
                    {
                        $sheet->setCellValue('T'.$startcellno, collect($records_firstrow[1]->attendance)->sum('days'));
                        $sheet->setCellValue('Y'.$startcellno, collect($records_firstrow[1]->attendance)->sum('present'));
                    }
    
                    $startcellno += 7; 
    
                    // S E C O N D
    
                    
                    
                    $sheet->setCellValue('C'.$startcellno, $records_secondrow[0]->schoolname);
                    $sheet->setCellValue('M'.$startcellno, $records_secondrow[0]->schoolid);
                    $sheet->setCellValue('S'.$startcellno, $records_secondrow[1]->schoolname);
                    $sheet->setCellValue('AC'.$startcellno, $records_secondrow[1]->schoolid);
    
                    $startcellno += 1;
                    
                    $sheet->setCellValue('C'.$startcellno, $records_secondrow[0]->schooldistrict);
                    $sheet->setCellValue('H'.$startcellno, $records_secondrow[0]->schooldivision);
                    $sheet->setCellValue('N'.$startcellno, $records_secondrow[0]->schoolregion);
                    $sheet->setCellValue('S'.$startcellno, $records_secondrow[1]->schooldistrict);
                    $sheet->setCellValue('X'.$startcellno, $records_secondrow[1]->schooldivision);
                    $sheet->setCellValue('AD'.$startcellno, $records_secondrow[1]->schoolregion);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('E'.$startcellno, preg_replace('/\D+/', '', $records_secondrow[0]->levelname));
                    $sheet->setCellValue('I'.$startcellno,  $records_secondrow[0]->sectionname);
                    $sheet->setCellValue('N'.$startcellno,  $records_secondrow[0]->sydesc);
                    $sheet->setCellValue('U'.$startcellno, preg_replace('/\D+/', '', $records_secondrow[1]->levelname));
                    $sheet->setCellValue('Y'.$startcellno,  $records_secondrow[1]->sectionname);
                    $sheet->setCellValue('AD'.$startcellno,  $records_secondrow[1]->sydesc);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('D'.$startcellno, $records_secondrow[0]->teachername);
                    $sheet->setCellValue('T'.$startcellno, $records_secondrow[1]->teachername);
                    
                    $startcellno += 4;
                    
                    $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                    
                    if(count($records_secondrow[0]->grades) == 0)
                    {
                        $thirdtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $thirdtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('A'.$x.':C'.$x);
                            $sheet->mergeCells('D'.$x.':E'.$x);
                            $sheet->mergeCells('F'.$x.':G'.$x);
                            $sheet->mergeCells('H'.$x.':I'.$x);
                            $sheet->mergeCells('J'.$x.':K'.$x);
                            $sheet->mergeCells('L'.$x.':M'.$x);
                            $sheet->mergeCells('N'.$x.':O'.$x);
                        }
                    }else{
                        $thirdtable_cellno = $startcellno;
                        foreach($records_secondrow[0]->grades as $thirdgrades)
                        {
                            if(strtolower($thirdgrades->subjdesc) != 'general average')
                            {
                                $inmapeh = '';
                                if($thirdgrades->inMAPEH == 1)
                                {
                                    $inmapeh = '     ';
                                }
                                if(mb_strlen ($thirdgrades->subjdesc) <= 22 && mb_strlen ($thirdgrades->subjdesc) > 13)
                                {
                                    $sheet->getRowDimension($thirdtable_cellno)->setRowHeight(25,'pt');  
                                }elseif(mb_strlen ($thirdgrades->subjdesc) > 22)
                                {
                                    $sheet->getRowDimension($thirdtable_cellno)->setRowHeight(45,'pt'); 
                                }
                                $sheet->getStyle('A'.$thirdtable_cellno.':N'.$thirdtable_cellno)->getAlignment()->setVertical('center');
        
                                $sheet->getStyle('A'.$thirdtable_cellno)->getAlignment()->setWrapText(true);
                                
                                $sheet->mergeCells('A'.$thirdtable_cellno.':C'.$thirdtable_cellno);
                                $sheet->setCellValue('A'.$thirdtable_cellno, $inmapeh.$thirdgrades->subjdesc);
                                $sheet->mergeCells('D'.$thirdtable_cellno.':E'.$thirdtable_cellno);
                                $sheet->setCellValue('D'.$thirdtable_cellno, $thirdgrades->q1);
                                $sheet->mergeCells('F'.$thirdtable_cellno.':G'.$thirdtable_cellno);
                                $sheet->setCellValue('F'.$thirdtable_cellno, $thirdgrades->q2);
                                $sheet->mergeCells('H'.$thirdtable_cellno.':I'.$thirdtable_cellno);
                                $sheet->setCellValue('H'.$thirdtable_cellno, $thirdgrades->q3);
                                $sheet->mergeCells('J'.$thirdtable_cellno.':K'.$thirdtable_cellno);
                                $sheet->setCellValue('J'.$thirdtable_cellno, $thirdgrades->q4);
                                $sheet->mergeCells('L'.$thirdtable_cellno.':M'.$thirdtable_cellno);
                                $sheet->setCellValue('L'.$thirdtable_cellno, $thirdgrades->finalrating);
                                $sheet->mergeCells('N'.$thirdtable_cellno.':O'.$thirdtable_cellno);
                                $sheet->setCellValue('N'.$thirdtable_cellno, $thirdgrades->remarks);
                                $thirdtable_cellno+=1;
                            }
                        }
                    }
                    
                    if(count($records_secondrow[1]->grades) == 0)
                    {
                        $fourthtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        
                        for($x = $fourthtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('Q'.$x.':S'.$x);
                            $sheet->mergeCells('T'.$x.':U'.$x);
                            $sheet->mergeCells('V'.$x.':W'.$x);
                            $sheet->mergeCells('X'.$x.':Y'.$x);
                            $sheet->mergeCells('Z'.$x.':AA'.$x);
                            $sheet->mergeCells('AB'.$x.':AC'.$x);
                            $sheet->mergeCells('AD'.$x.':AE'.$x);
                        }
                    }else{
                        $fourthtable_cellno = $startcellno;
                        foreach($records_secondrow[1]->grades as $fourthgrades)
                        {
                            if(strtolower($fourthgrades->subjdesc) != 'general average')
                            {
                                $inmapeh = '';
                                if($fourthgrades->inMAPEH == 1)
                                {
                                    $inmapeh = '     ';
                                }
                                if(mb_strlen ($fourthgrades->subjdesc) <= 22 && mb_strlen ($fourthgrades->subjdesc) > 13)
                                {
                                    $sheet->getRowDimension($fourthtable_cellno)->setRowHeight(25,'pt');  
                                }elseif(mb_strlen ($fourthgrades->subjdesc) > 22)
                                {
                                    $sheet->getRowDimension($fourthtable_cellno)->setRowHeight(45,'pt'); 
                                }
                                $sheet->getStyle('Q'.$fourthtable_cellno.':AD'.$fourthtable_cellno)->getAlignment()->setVertical('center');
        
                                $sheet->getStyle('Q'.$fourthtable_cellno)->getAlignment()->setWrapText(true);
                                
                                $sheet->mergeCells('Q'.$fourthtable_cellno.':S'.$fourthtable_cellno);
                                $sheet->setCellValue('Q'.$fourthtable_cellno, $inmapeh.$fourthgrades->subjdesc);
                                $sheet->mergeCells('T'.$fourthtable_cellno.':U'.$fourthtable_cellno);
                                $sheet->setCellValue('T'.$fourthtable_cellno, $fourthgrades->q1);
                                $sheet->mergeCells('V'.$fourthtable_cellno.':W'.$fourthtable_cellno);
                                $sheet->setCellValue('V'.$fourthtable_cellno, $fourthgrades->q2);
                                $sheet->mergeCells('X'.$fourthtable_cellno.':Y'.$fourthtable_cellno);
                                $sheet->setCellValue('X'.$fourthtable_cellno, $fourthgrades->q3);
                                $sheet->mergeCells('Z'.$fourthtable_cellno.':AA'.$fourthtable_cellno);
                                $sheet->setCellValue('Z'.$fourthtable_cellno, $fourthgrades->q4);
                                $sheet->mergeCells('AB'.$fourthtable_cellno.':AC'.$fourthtable_cellno);
                                $sheet->setCellValue('AB'.$fourthtable_cellno, $fourthgrades->finalrating);
                                $sheet->mergeCells('AD'.$fourthtable_cellno.':AE'.$fourthtable_cellno);
                                $sheet->setCellValue('AD'.$fourthtable_cellno, $fourthgrades->remarks);
                                $fourthtable_cellno+=1;
                            }
                        }
                    }
                    
                    $startcellno += $maxgradecount; // general average
    
                    $startcellno += 2; // attendance
    
                    if(count($records_secondrow[0]->attendance) > 0)
                    {
                        $sheet->setCellValue('D'.$startcellno, collect($records_secondrow[0]->attendance)->sum('days'));
                        $sheet->setCellValue('I'.$startcellno, collect($records_secondrow[0]->attendance)->sum('present'));
                    }
                    
                    if(count($records_secondrow[1]->attendance) > 0)
                    {
                        $sheet->setCellValue('T'.$startcellno, collect($records_secondrow[1]->attendance)->sum('days'));
                        $sheet->setCellValue('Y'.$startcellno, collect($records_secondrow[1]->attendance)->sum('present'));
                    }
    
                    $startcellno += 9;  // Certification
    
                    $sheet->setCellValue('H'.$startcellno, $studinfo->firstname.' '.$studinfo->middlename[0].'. '. $studinfo->lastname.' '.$studinfo->suffix);
                    $sheet->setCellValue('R'.$startcellno, $studinfo->lrn);
                    $sheet->getStyle('R'.$startcellno)->getNumberFormat()->setFormatCode('0');
    
                    $startcellno += 1; // schoolinfo
    
                    $startcellno += 3;
    
                    $registrarname = DB::table('teacher')
                        ->where('userid', auth()->user()->id)
                        ->first();
    
                    $sheet->setCellValue('W'.$startcellno, $registrarname->title.' '.$registrarname->firstname.' '.$registrarname->middlename[0].'. '.$registrarname->lastname.' '.$registrarname->suffix);
    
                    $startcellno += 4;
    
                    $sheet->setCellValue('D'.$startcellno, $footer->copysentto);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('D'.$startcellno, $footer->address);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('D'.$startcellno, date('m/d/Y'));

                }
                elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs')
                {
                    $inputFileName = base_path().'/public/excelformats/lhs/sf10_jhs.xlsx';

                    /**  Create a new Reader of the type defined in $inputFileType  **/
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Advise the Reader of which WorkSheets we want to load  **/
                    $reader->setLoadAllSheets();
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($inputFileName);
                    
                    $sheet = $spreadsheet->getSheet(0);
                    $sheet->setCellValue('G7', $studinfo->lastname);
                    $sheet->setCellValue('W7', $studinfo->firstname);
                    $sheet->setCellValue('AN7', $studinfo->suffix);
                    $sheet->setCellValue('AX7', $studinfo->middlename);

                    $sheet->setCellValue('M8', $studinfo->lrn);
                    $sheet->getStyle('M8')->getNumberFormat()->setFormatCode('0');
                    $sheet->setCellValue('AH8', date('m/d/Y', strtotime($studinfo->dob)));
                    $sheet->setCellValue('AV8', $studinfo->gender);
                    // E L I G I B I L I T Y
                    if($eligibility->completer == 1)
                    {
                        $sheet->setCellValue('C12', '/');
                    }

                    $sheet->getStyle('C12')->getAlignment()->setHorizontal('center');

                    $sheet->setCellValue('AC12', $eligibility->genave);
                    $sheet->setCellValue('AP12', $eligibility->citation);
                    $sheet->setCellValue('M13', $eligibility->schoolname);
                    $sheet->setCellValue('AH13', $eligibility->schoolid);
                    $sheet->setCellValue('AR13', $eligibility->schooladdress);

                    if($eligibility->peptpasser == 1)
                    {
                        $sheet->setCellValue('C16', '/');
                        $sheet->setCellValue('L16', $eligibility->peptrating);                        
                    }

                    $sheet->getStyle('C16')->getAlignment()->setHorizontal('center');

                    if($eligibility->alspasser == 1)
                    {
                        $sheet->setCellValue('W16', '/');
                        $sheet->setCellValue('AI16', $eligibility->alsrating);     
                    }

                    $sheet->getStyle('W16')->getAlignment()->setHorizontal('center');

                    $sheet->setCellValue('AX16',$eligibility->specifyothers);

                    if($eligibility->examdate!= null)
                    {
                        $eligibility->examdate = date('m/d/Y',strtotime($eligibility->examdate));
                    }

                    $sheet->setCellValue('T17',$eligibility->examdate);
                    $sheet->setCellValue('AO17',$eligibility->centername);

                    $startcellno = 21;

                    foreach($records[0] as $frontrecord)
                    {
                        // return $frontrecord;
                        $sheet->setCellValue('E'.$startcellno,$frontrecord->schoolname);
                        $sheet->setCellValue('U'.$startcellno,$frontrecord->schoolid);
                        $sheet->setCellValue('AD'.$startcellno,$frontrecord->schooldistrict);
                        $sheet->setCellValue('AP'.$startcellno,$frontrecord->schooldivision);
                        $sheet->setCellValue('BB'.$startcellno,str_replace('REGION', '', $frontrecord->schoolregion));
                        $startcellno+=1;
                        $sheet->setCellValue('I'.$startcellno,str_replace('GRADE', '', $frontrecord->levelname));
                        $sheet->setCellValue('N'.$startcellno,$frontrecord->sectionname);
                        $sheet->setCellValue('V'.$startcellno,$frontrecord->sydesc);
                        $sheet->setCellValue('AI'.$startcellno,$frontrecord->teachername);
                        $startcellno+=4;
                        if(count($frontrecord->grades)>0)
                        {
                            foreach($frontrecord->grades as $grade)
                            {
                                $sheet->insertNewRowBefore($startcellno, 1);
                                $sheet->mergeCells('B'.$startcellno.':T'.$startcellno);
                                $sheet->getStyle('B'.$startcellno)->getAlignment()->setHorizontal('left');
                                if(strpos($grade->subjdesc, 'MAPEH') !== false || strpos($grade->subjdesc, 'T.L.E') !== false || strpos($grade->subjdesc, 'TLE') !== false){
                                } else{
                                    $grade->subjdesc = ucwords(strtolower($grade->subjdesc));
                                }
                                if($grade->inMAPEH == 1)
                                {
                                    $sheet->setCellValue('B'.$startcellno,'     '.$grade->subjdesc);
                                    $sheet->getStyle('B'.$startcellno)->getFont()->setItalic(true);
                                    $sheet->getStyle('B'.$startcellno)->getFont()->setBold(false);
                                }else{
                                    $sheet->setCellValue('B'.$startcellno,$grade->subjdesc);
                                    $sheet->getStyle('B'.$startcellno)->getFont()->setBold(true);
                                }
                                $sheet->mergeCells('U'.$startcellno.':X'.$startcellno);
                                $sheet->setCellValue('U'.$startcellno,$grade->q1);
                                $sheet->mergeCells('Y'.$startcellno.':AB'.$startcellno);
                                $sheet->setCellValue('Y'.$startcellno,$grade->q2);
                                $sheet->mergeCells('AC'.$startcellno.':AF'.$startcellno);
                                $sheet->setCellValue('AC'.$startcellno,$grade->q3);
                                $sheet->mergeCells('AG'.$startcellno.':AI'.$startcellno);
                                $sheet->setCellValue('AG'.$startcellno,$grade->q4);
                                $sheet->mergeCells('AJ'.$startcellno.':AO'.$startcellno);
                                $sheet->setCellValue('AJ'.$startcellno,$grade->finalrating);
                                $sheet->mergeCells('AP'.$startcellno.':BC'.$startcellno);
                                $sheet->setCellValue('AP'.$startcellno,$grade->remarks);
                                $startcellno+=1;
                            }
                            // return $frontrecord->grades;
                        }     
                        
                        for($x = count($frontrecord->grades); $x < $maxgradecount; $x ++)
                        {
                            $sheet->mergeCells('B'.$startcellno.':T'.$startcellno);

                            $sheet->mergeCells('U'.$startcellno.':X'.$startcellno);

                            $sheet->mergeCells('Y'.$startcellno.':AB'.$startcellno);

                            $sheet->mergeCells('AC'.$startcellno.':AF'.$startcellno);

                            $sheet->mergeCells('AG'.$startcellno.':AI'.$startcellno);

                            $sheet->mergeCells('AJ'.$startcellno.':AO'.$startcellno);

                            $sheet->mergeCells('AP'.$startcellno.':BC'.$startcellno);
                            $sheet->insertNewRowBefore($startcellno+1, 1);
                            $startcellno+=1;
                        }   
                        $startcellno+=1;
                        //general average
                        if(count($frontrecord->generalaverage)>0)
                        {
                            $sheet->setCellValue('AJ'.$startcellno,$frontrecord->generalaverage[0]->finalrating);
                            $sheet->getStyle('AJ'.$startcellno)->getNumberFormat()->setFormatCode('0');
                            $sheet->setCellValue('AP'.$startcellno,$frontrecord->generalaverage[0]->actiontaken);
                        }
                        $startcellno+=9;
                        foreach($frontrecord->attendance as $month)
                        {
                            if($month->monthdesc == 'June')
                            {
                                $sheet->setCellValue('I'.$startcellno,$month->days);

                            }elseif($month->monthdesc == 'July')
                            {
                                $sheet->setCellValue('K'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'August')
                            {
                                $sheet->setCellValue('M'.$startcellno,$month->days); 
                            }elseif($month->monthdesc == 'September')
                            {
                                $sheet->setCellValue('P'.$startcellno,$month->days); 
                            }elseif($month->monthdesc == 'October')
                            {
                                $sheet->setCellValue('S'.$startcellno,$month->days);  
                            }elseif($month->monthdesc == 'November')
                            {
                                $sheet->setCellValue('U'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'December')
                            {
                                $sheet->setCellValue('W'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'January')
                            {
                                $sheet->setCellValue('Y'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'February')
                            {
                                $sheet->setCellValue('AA'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'March')
                            {
                                $sheet->setCellValue('AC'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'April')
                            {
                                $sheet->setCellValue('AF'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'May')
                            {
                                $sheet->setCellValue('AH'.$startcellno,$month->days);
                            }
                        }
                        $sheet->setCellValue('AI'.$startcellno,collect($frontrecord->attendance)->sum('days'));
                        $startcellno+=1;
                        foreach($frontrecord->attendance as $month)
                        {
                            if($month->monthdesc == 'June')
                            {
                                $sheet->setCellValue('I'.$startcellno,$month->present);

                            }elseif($month->monthdesc == 'July')
                            {
                                $sheet->setCellValue('K'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'August')
                            {
                                $sheet->setCellValue('M'.$startcellno,$month->present); 
                            }elseif($month->monthdesc == 'September')
                            {
                                $sheet->setCellValue('P'.$startcellno,$month->present); 
                            }elseif($month->monthdesc == 'October')
                            {
                                $sheet->setCellValue('S'.$startcellno,$month->present);  
                            }elseif($month->monthdesc == 'November')
                            {
                                $sheet->setCellValue('U'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'December')
                            {
                                $sheet->setCellValue('W'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'January')
                            {
                                $sheet->setCellValue('Y'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'February')
                            {
                                $sheet->setCellValue('AA'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'March')
                            {
                                $sheet->setCellValue('AC'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'April')
                            {
                                $sheet->setCellValue('AF'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'May')
                            {
                                $sheet->setCellValue('AH'.$startcellno,$month->days);
                            }
                        }
                        $sheet->setCellValue('AI'.$startcellno,collect($frontrecord->attendance)->sum('present'));
                        $startcellno+=3;
                    }

                    $sheet = $spreadsheet->getSheetByName('Back');
                    //backcertification
                    $mi = null;
                    if($studinfo->middlename != null)
                    {
                        $mi =  $studinfo->middlename[0].'.';
                    }
                    $sheet->setCellValue('O48',$studinfo->firstname.' '.$mi.'. '.$studinfo->lastname.' '.$studinfo->suffix);
                    $sheet->setCellValue('AF48',$studinfo->lrn);
                    $sheet->getStyle('AF48')->getNumberFormat()->setFormatCode('0');

                    $sheet->setCellValue('H49',$schoolinfo->schoolname);
                    $sheet->setCellValue('AC49',$schoolinfo->schoolid);

                    $sheet->setCellValue('B50',date('m/d/Y'));
                    $sheet->setCellValue('S52',strtoupper($schoolinfo->authorized));

                    $startcellno = 3;

                    foreach($records[1] as $backrecord)
                    {
                        // return $frontrecord;
                        $sheet->setCellValue('E'.$startcellno,$backrecord->schoolname);
                        $sheet->setCellValue('U'.$startcellno,$backrecord->schoolid);
                        $sheet->setCellValue('AD'.$startcellno,$backrecord->schooldistrict);
                        $sheet->setCellValue('AP'.$startcellno,$backrecord->schooldivision);
                        $sheet->setCellValue('BB'.$startcellno,str_replace('REGION', '', $backrecord->schoolregion));
                        $startcellno+=1;
                        $sheet->setCellValue('I'.$startcellno,str_replace('GRADE', '', $backrecord->levelname));
                        $sheet->setCellValue('N'.$startcellno,$backrecord->sectionname);
                        $sheet->setCellValue('V'.$startcellno,$backrecord->sydesc);
                        $sheet->setCellValue('AI'.$startcellno,$backrecord->teachername);
                        $startcellno+=4;

                        if(count($backrecord->grades)>0)
                        {
                            foreach($backrecord->grades as $grade)
                            {
                                $sheet->insertNewRowBefore($startcellno, 1);
                                $sheet->mergeCells('B'.$startcellno.':T'.$startcellno);
                                $sheet->getStyle('B'.$startcellno)->getAlignment()->setHorizontal('left');
                                if(strpos($grade->subjdesc, 'MAPEH') !== false || strpos($grade->subjdesc, 'T.L.E') !== false || strpos($grade->subjdesc, 'TLE') !== false){
                                } else{
                                    $grade->subjdesc = ucwords(strtolower($grade->subjdesc));
                                }
                                if($grade->inMAPEH == 1)
                                {
                                    $sheet->setCellValue('B'.$startcellno,'     '.$grade->subjdesc);
                                    $sheet->getStyle('B'.$startcellno)->getFont()->setItalic(true);
                                    $sheet->getStyle('B'.$startcellno)->getFont()->setBold(false);
                                }else{
                                    $sheet->setCellValue('B'.$startcellno,$grade->subjdesc);
                                    $sheet->getStyle('B'.$startcellno)->getFont()->setBold(true);
                                }
                                $sheet->mergeCells('U'.$startcellno.':X'.$startcellno);
                                $sheet->setCellValue('U'.$startcellno,$grade->q1);
                                $sheet->mergeCells('Y'.$startcellno.':AB'.$startcellno);
                                $sheet->setCellValue('Y'.$startcellno,$grade->q2);
                                $sheet->mergeCells('AC'.$startcellno.':AF'.$startcellno);
                                $sheet->setCellValue('AC'.$startcellno,$grade->q3);
                                $sheet->mergeCells('AG'.$startcellno.':AI'.$startcellno);
                                $sheet->setCellValue('AG'.$startcellno,$grade->q4);
                                $sheet->mergeCells('AJ'.$startcellno.':AO'.$startcellno);
                                $sheet->setCellValue('AJ'.$startcellno,$grade->finalrating);
                                $sheet->mergeCells('AP'.$startcellno.':BC'.$startcellno);
                                $sheet->setCellValue('AP'.$startcellno,$grade->remarks);
                                $startcellno+=1;
                            }
                            // return $frontrecord->grades;
                        }     
                        
                        for($x = count($backrecord->grades); $x < $maxgradecount; $x ++)
                        {
                            $sheet->mergeCells('B'.$startcellno.':T'.$startcellno);

                            $sheet->mergeCells('U'.$startcellno.':X'.$startcellno);

                            $sheet->mergeCells('Y'.$startcellno.':AB'.$startcellno);

                            $sheet->mergeCells('AC'.$startcellno.':AF'.$startcellno);

                            $sheet->mergeCells('AG'.$startcellno.':AI'.$startcellno);

                            $sheet->mergeCells('AJ'.$startcellno.':AO'.$startcellno);

                            $sheet->mergeCells('AP'.$startcellno.':BC'.$startcellno);
                            $sheet->insertNewRowBefore($startcellno+1, 1);
                            $startcellno+=1;
                        }   
                        $startcellno+=1;
                        //general average
                        if(count($backrecord->generalaverage)>0)
                        {
                            $sheet->setCellValue('AJ'.$startcellno,$backrecord->generalaverage[0]->finalrating);
                            $sheet->getStyle('AJ'.$startcellno)->getNumberFormat()->setFormatCode('0');
                            $sheet->setCellValue('AP'.$startcellno,$backrecord->generalaverage[0]->actiontaken);
                        }
                        $startcellno+=9;
                        foreach($backrecord->attendance as $month)
                        {
                            if($month->monthdesc == 'June')
                            {
                                $sheet->setCellValue('I'.$startcellno,$month->days);

                            }elseif($month->monthdesc == 'July')
                            {
                                $sheet->setCellValue('K'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'August')
                            {
                                $sheet->setCellValue('M'.$startcellno,$month->days); 
                            }elseif($month->monthdesc == 'September')
                            {
                                $sheet->setCellValue('P'.$startcellno,$month->days); 
                            }elseif($month->monthdesc == 'October')
                            {
                                $sheet->setCellValue('S'.$startcellno,$month->days);  
                            }elseif($month->monthdesc == 'November')
                            {
                                $sheet->setCellValue('U'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'December')
                            {
                                $sheet->setCellValue('W'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'January')
                            {
                                $sheet->setCellValue('Y'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'February')
                            {
                                $sheet->setCellValue('AA'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'March')
                            {
                                $sheet->setCellValue('AC'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'April')
                            {
                                $sheet->setCellValue('AF'.$startcellno,$month->days);
                            }elseif($month->monthdesc == 'May')
                            {
                                $sheet->setCellValue('AH'.$startcellno,$month->days);
                            }
                        }
                        $sheet->setCellValue('AI'.$startcellno,collect($backrecord->attendance)->sum('days'));
                        $startcellno+=1;
                        foreach($backrecord->attendance as $month)
                        {
                            if($month->monthdesc == 'June')
                            {
                                $sheet->setCellValue('I'.$startcellno,$month->present);

                            }elseif($month->monthdesc == 'July')
                            {
                                $sheet->setCellValue('K'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'August')
                            {
                                $sheet->setCellValue('M'.$startcellno,$month->present); 
                            }elseif($month->monthdesc == 'September')
                            {
                                $sheet->setCellValue('P'.$startcellno,$month->present); 
                            }elseif($month->monthdesc == 'October')
                            {
                                $sheet->setCellValue('S'.$startcellno,$month->present);  
                            }elseif($month->monthdesc == 'November')
                            {
                                $sheet->setCellValue('U'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'December')
                            {
                                $sheet->setCellValue('W'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'January')
                            {
                                $sheet->setCellValue('Y'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'February')
                            {
                                $sheet->setCellValue('AA'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'March')
                            {
                                $sheet->setCellValue('AC'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'April')
                            {
                                $sheet->setCellValue('AF'.$startcellno,$month->present);
                            }elseif($month->monthdesc == 'May')
                            {
                                $sheet->setCellValue('AH'.$startcellno,$month->days);
                            }
                        }
                        $sheet->setCellValue('AI'.$startcellno,collect($backrecord->attendance)->sum('present'));
                        $startcellno+=8;
                    }
                    // return $maxgradecount;

                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
                {
                    $inputFileName = base_path().'/public/excelformats/sf10_jhs.xlsx';

                    /**  Create a new Reader of the type defined in $inputFileType  **/
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Advise the Reader of which WorkSheets we want to load  **/
                    $reader->setLoadAllSheets();
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($inputFileName);
                    
                    $sheet = $spreadsheet->getSheet(0);
                    $sheet->setCellValue('D8', $studinfo->lastname);
                    $sheet->setCellValue('L8', $studinfo->firstname);
                    $sheet->setCellValue('V8', $studinfo->suffix);
                    $sheet->setCellValue('AB8', $studinfo->middlename);
    
                    
                    $sheet->setCellValue('H9', $studinfo->lrn);
                    $sheet->getStyle('H9')->getNumberFormat()->setFormatCode('0');
                    $sheet->setCellValue('U9', date('m/d/Y', strtotime($studinfo->dob)));
                    $sheet->setCellValue('AB9', $studinfo->gender);
    
                    if($eligibility->completer == 1)
                    {
                        $sheet->setCellValue('B13', '/');
                    }
                    $sheet->setCellValue('P13', $eligibility->genave);
                    $sheet->setCellValue('W13', $eligibility->citation);
    
                    $sheet->setCellValue('I14', $eligibility->schoolname);
                    $sheet->setCellValue('S14', $eligibility->schoolid);
                    $sheet->setCellValue('Z14', $eligibility->schooladdress);
                    
                    if($eligibility->peptpasser == 1)
                    {
                        $sheet->setCellValue('B17', '/');
                    }
                    $sheet->setCellValue('I17', $eligibility->peptrating);
                    if($eligibility->alspasser == 1)
                    {
                        $sheet->setCellValue('L17', '/');
                    }
                    $sheet->setCellValue('S17', $eligibility->alsrating);
                    $sheet->setCellValue('AA17', $eligibility->specifyothers);
                    
                    $sheet->setCellValue('L18', $eligibility->examdate);
                    $sheet->setCellValue('X18', $eligibility->centername);
    
                    $startcellno = 22;
    
                    // F I R S T
    
                    $records_firstrow = $records[0];
                    
                    $sheet->setCellValue('C'.$startcellno, $records_firstrow[0]->schoolname);
                    $sheet->setCellValue('M'.$startcellno, $records_firstrow[0]->schoolid);
                    $sheet->setCellValue('S'.$startcellno, $records_firstrow[1]->schoolname);
                    $sheet->setCellValue('AC'.$startcellno, $records_firstrow[1]->schoolid);
    
                    $startcellno += 1;
                    
                    $sheet->setCellValue('C'.$startcellno, $records_firstrow[0]->schooldistrict);
                    $sheet->setCellValue('H'.$startcellno, $records_firstrow[0]->schooldivision);
                    $sheet->setCellValue('N'.$startcellno, $records_firstrow[0]->schoolregion);
                    $sheet->setCellValue('S'.$startcellno, $records_firstrow[1]->schooldistrict);
                    $sheet->setCellValue('X'.$startcellno, $records_firstrow[1]->schooldivision);
                    $sheet->setCellValue('AD'.$startcellno, $records_firstrow[1]->schoolregion);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('E'.$startcellno, preg_replace('/\D+/', '', $records_firstrow[0]->levelname));
                    $sheet->setCellValue('I'.$startcellno,  $records_firstrow[0]->sectionname);
                    $sheet->setCellValue('N'.$startcellno,  $records_firstrow[0]->sydesc);
                    $sheet->setCellValue('U'.$startcellno, preg_replace('/\D+/', '', $records_firstrow[1]->levelname));
                    $sheet->setCellValue('Y'.$startcellno,  $records_firstrow[1]->sectionname);
                    $sheet->setCellValue('AD'.$startcellno,  $records_firstrow[1]->sydesc);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('D'.$startcellno, $records_firstrow[0]->teachername);
                    $sheet->setCellValue('T'.$startcellno, $records_firstrow[1]->teachername);
                    
                    $startcellno += 4;
                    
                    $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                    
                    if(count($records_firstrow[0]->grades) == 0)
                    {
                        $firsttable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $firsttable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('A'.$x.':C'.$x);
                            $sheet->mergeCells('D'.$x.':E'.$x);
                            $sheet->mergeCells('F'.$x.':G'.$x);
                            $sheet->mergeCells('H'.$x.':I'.$x);
                            $sheet->mergeCells('J'.$x.':K'.$x);
                            $sheet->mergeCells('L'.$x.':M'.$x);
                            $sheet->mergeCells('N'.$x.':O'.$x);
                        }
                    }else{
                        $firsttable_cellno = $startcellno;
                        foreach($records_firstrow[0]->grades as $firstgrades)
                        {
                            if(mb_strlen ($firstgrades->subjdesc) <= 22 && mb_strlen ($firstgrades->subjdesc) > 13)
                            {
                                $sheet->getRowDimension($firsttable_cellno)->setRowHeight(25,'pt');  
                            }elseif(mb_strlen ($firstgrades->subjdesc) > 22)
                            {
                                $sheet->getRowDimension($firsttable_cellno)->setRowHeight(45,'pt'); 
                            }
                            $sheet->getStyle('A'.$firsttable_cellno.':N'.$firsttable_cellno)->getAlignment()->setVertical('center');
                            $sheet->getStyle('A'.$firsttable_cellno)->getAlignment()->setWrapText(true);
                            
                            $inmapeh = '';
                            if($firstgrades->inMAPEH == 1)
                            {
                                $inmapeh = '     ';
                            }
                            $sheet->mergeCells('A'.$firsttable_cellno.':C'.$firsttable_cellno);
                            $sheet->setCellValue('A'.$firsttable_cellno, $inmapeh.$firstgrades->subjdesc);
                            $sheet->mergeCells('D'.$firsttable_cellno.':E'.$firsttable_cellno);
                            $sheet->setCellValue('D'.$firsttable_cellno, $firstgrades->q1);
                            $sheet->mergeCells('F'.$firsttable_cellno.':G'.$firsttable_cellno);
                            $sheet->setCellValue('F'.$firsttable_cellno, $firstgrades->q2);
                            $sheet->mergeCells('H'.$firsttable_cellno.':I'.$firsttable_cellno);
                            $sheet->setCellValue('H'.$firsttable_cellno, $firstgrades->q3);
                            $sheet->mergeCells('J'.$firsttable_cellno.':K'.$firsttable_cellno);
                            $sheet->setCellValue('J'.$firsttable_cellno, $firstgrades->q4);
                            $sheet->mergeCells('L'.$firsttable_cellno.':M'.$firsttable_cellno);
                            $sheet->setCellValue('L'.$firsttable_cellno, $firstgrades->finalrating);
                            $sheet->mergeCells('N'.$firsttable_cellno.':O'.$firsttable_cellno);
                            $sheet->setCellValue('N'.$firsttable_cellno, $firstgrades->remarks);
                            $firsttable_cellno+=1;
                        }
                    }
                    
                    
                    if(count($records_firstrow[1]->grades) == 0)
                    {
                        $secondtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        
                        for($x = $secondtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('Q'.$x.':S'.$x);
                            $sheet->mergeCells('T'.$x.':U'.$x);
                            $sheet->mergeCells('V'.$x.':W'.$x);
                            $sheet->mergeCells('X'.$x.':Y'.$x);
                            $sheet->mergeCells('Z'.$x.':AA'.$x);
                            $sheet->mergeCells('AB'.$x.':AC'.$x);
                            $sheet->mergeCells('AD'.$x.':AE'.$x);
                        }
                    }else{
                        $secondtable_cellno = $startcellno;
                        foreach($records_firstrow[1]->grades as $secondgrades)
                        {
                            if(mb_strlen ($secondgrades->subjdesc) <= 22 && mb_strlen ($secondgrades->subjdesc) > 13)
                            {
                                $sheet->getRowDimension($secondtable_cellno)->setRowHeight(25,'pt');  
                            }elseif(mb_strlen ($secondgrades->subjdesc) > 22)
                            {
                                $sheet->getRowDimension($secondtable_cellno)->setRowHeight(45,'pt'); 
                            }
                            $sheet->getStyle('Q'.$secondtable_cellno.':AD'.$secondtable_cellno)->getAlignment()->setVertical('center');
    
                            $sheet->getStyle('Q'.$secondtable_cellno)->getAlignment()->setWrapText(true);
                            $inmapeh = '';
                            if($secondgrades->inMAPEH == 1)
                            {
                                $inmapeh = '     ';
                            }
                            $sheet->mergeCells('Q'.$secondtable_cellno.':S'.$secondtable_cellno);
                            $sheet->setCellValue('Q'.$secondtable_cellno, $inmapeh.$secondgrades->subjdesc);
                            $sheet->getStyle('Q'.$secondtable_cellno)->getAlignment()->setWrapText(true);
                            $sheet->mergeCells('T'.$secondtable_cellno.':U'.$secondtable_cellno);
                            $sheet->setCellValue('T'.$secondtable_cellno, $secondgrades->q1);
                            $sheet->mergeCells('V'.$secondtable_cellno.':W'.$secondtable_cellno);
                            $sheet->setCellValue('V'.$secondtable_cellno, $secondgrades->q2);
                            $sheet->mergeCells('X'.$secondtable_cellno.':Y'.$secondtable_cellno);
                            $sheet->setCellValue('X'.$secondtable_cellno, $secondgrades->q3);
                            $sheet->mergeCells('Z'.$secondtable_cellno.':AA'.$secondtable_cellno);
                            $sheet->setCellValue('Z'.$secondtable_cellno, $secondgrades->q4);
                            $sheet->mergeCells('AB'.$secondtable_cellno.':AC'.$secondtable_cellno);
                            $sheet->setCellValue('AB'.$secondtable_cellno, $secondgrades->finalrating);
                            $sheet->mergeCells('AD'.$secondtable_cellno.':AE'.$secondtable_cellno);
                            $sheet->setCellValue('AD'.$secondtable_cellno, $secondgrades->remarks);
                            $secondtable_cellno+=1;
                        }
                    }
    
                    $startcellno += $maxgradecount; // general average
    
                    $startcellno += 2; // attendance
    
                    if(count($records_firstrow[0]->attendance) > 0)
                    {
                        $sheet->setCellValue('D'.$startcellno, collect($records_firstrow[0]->attendance)->sum('days'));
                        $sheet->setCellValue('I'.$startcellno, collect($records_firstrow[0]->attendance)->sum('present'));
                    }
                    
                    if(count($records_firstrow[1]->attendance) > 0)
                    {
                        $sheet->setCellValue('T'.$startcellno, collect($records_firstrow[1]->attendance)->sum('days'));
                        $sheet->setCellValue('Y'.$startcellno, collect($records_firstrow[1]->attendance)->sum('present'));
                    }
    
                    $startcellno += 7; 
    
                    // S E C O N D
    
                    $records_secondrow = $records[1];
                    
                    $sheet->setCellValue('C'.$startcellno, $records_secondrow[0]->schoolname);
                    $sheet->setCellValue('M'.$startcellno, $records_secondrow[0]->schoolid);
                    $sheet->setCellValue('S'.$startcellno, $records_secondrow[1]->schoolname);
                    $sheet->setCellValue('AC'.$startcellno, $records_secondrow[1]->schoolid);
    
                    $startcellno += 1;
                    
                    $sheet->setCellValue('C'.$startcellno, $records_secondrow[0]->schooldistrict);
                    $sheet->setCellValue('H'.$startcellno, $records_secondrow[0]->schooldivision);
                    $sheet->setCellValue('N'.$startcellno, $records_secondrow[0]->schoolregion);
                    $sheet->setCellValue('S'.$startcellno, $records_secondrow[1]->schooldistrict);
                    $sheet->setCellValue('X'.$startcellno, $records_secondrow[1]->schooldivision);
                    $sheet->setCellValue('AD'.$startcellno, $records_secondrow[1]->schoolregion);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('E'.$startcellno, preg_replace('/\D+/', '', $records_secondrow[0]->levelname));
                    $sheet->setCellValue('I'.$startcellno,  $records_secondrow[0]->sectionname);
                    $sheet->setCellValue('N'.$startcellno,  $records_secondrow[0]->sydesc);
                    $sheet->setCellValue('U'.$startcellno, preg_replace('/\D+/', '', $records_secondrow[1]->levelname));
                    $sheet->setCellValue('Y'.$startcellno,  $records_secondrow[1]->sectionname);
                    $sheet->setCellValue('AD'.$startcellno,  $records_secondrow[1]->sydesc);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('D'.$startcellno, $records_secondrow[0]->teachername);
                    $sheet->setCellValue('T'.$startcellno, $records_secondrow[1]->teachername);
                    
                    $startcellno += 4;
                    
                    $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                    
                    if(count($records_secondrow[0]->grades) == 0)
                    {
                        $thirdtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $thirdtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('A'.$x.':C'.$x);
                            $sheet->mergeCells('D'.$x.':E'.$x);
                            $sheet->mergeCells('F'.$x.':G'.$x);
                            $sheet->mergeCells('H'.$x.':I'.$x);
                            $sheet->mergeCells('J'.$x.':K'.$x);
                            $sheet->mergeCells('L'.$x.':M'.$x);
                            $sheet->mergeCells('N'.$x.':O'.$x);
                        }
                    }else{
                        $thirdtable_cellno = $startcellno;
                        foreach($records_secondrow[0]->grades as $thirdgrades)
                        {
                            $inmapeh = '';
                            if($thirdgrades->inMAPEH == 1)
                            {
                                $inmapeh = '     ';
                            }
                            if(mb_strlen ($thirdgrades->subjdesc) <= 22 && mb_strlen ($thirdgrades->subjdesc) > 13)
                            {
                                $sheet->getRowDimension($thirdtable_cellno)->setRowHeight(25,'pt');  
                            }elseif(mb_strlen ($thirdgrades->subjdesc) > 22)
                            {
                                $sheet->getRowDimension($thirdtable_cellno)->setRowHeight(45,'pt'); 
                            }
                            $sheet->getStyle('A'.$thirdtable_cellno.':N'.$thirdtable_cellno)->getAlignment()->setVertical('center');
    
                            $sheet->getStyle('A'.$thirdtable_cellno)->getAlignment()->setWrapText(true);
                            
                            $sheet->mergeCells('A'.$thirdtable_cellno.':C'.$thirdtable_cellno);
                            $sheet->setCellValue('A'.$thirdtable_cellno, $inmapeh.$thirdgrades->subjdesc);
                            $sheet->mergeCells('D'.$thirdtable_cellno.':E'.$thirdtable_cellno);
                            $sheet->setCellValue('D'.$thirdtable_cellno, $thirdgrades->q1);
                            $sheet->mergeCells('F'.$thirdtable_cellno.':G'.$thirdtable_cellno);
                            $sheet->setCellValue('F'.$thirdtable_cellno, $thirdgrades->q2);
                            $sheet->mergeCells('H'.$thirdtable_cellno.':I'.$thirdtable_cellno);
                            $sheet->setCellValue('H'.$thirdtable_cellno, $thirdgrades->q3);
                            $sheet->mergeCells('J'.$thirdtable_cellno.':K'.$thirdtable_cellno);
                            $sheet->setCellValue('J'.$thirdtable_cellno, $thirdgrades->q4);
                            $sheet->mergeCells('L'.$thirdtable_cellno.':M'.$thirdtable_cellno);
                            $sheet->setCellValue('L'.$thirdtable_cellno, $thirdgrades->finalrating);
                            $sheet->mergeCells('N'.$thirdtable_cellno.':O'.$thirdtable_cellno);
                            $sheet->setCellValue('N'.$thirdtable_cellno, $thirdgrades->remarks);
                            $thirdtable_cellno+=1;
                        }
                    }
                    
                    if(count($records_secondrow[1]->grades) == 0)
                    {
                        $fourthtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        
                        for($x = $fourthtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('Q'.$x.':S'.$x);
                            $sheet->mergeCells('T'.$x.':U'.$x);
                            $sheet->mergeCells('V'.$x.':W'.$x);
                            $sheet->mergeCells('X'.$x.':Y'.$x);
                            $sheet->mergeCells('Z'.$x.':AA'.$x);
                            $sheet->mergeCells('AB'.$x.':AC'.$x);
                            $sheet->mergeCells('AD'.$x.':AE'.$x);
                        }
                    }else{
                        $fourthtable_cellno = $startcellno;
                        foreach($records_secondrow[1]->grades as $fourthgrades)
                        {
                            $inmapeh = '';
                            if($fourthgrades->inMAPEH == 1)
                            {
                                $inmapeh = '     ';
                            }
                            if(mb_strlen ($fourthgrades->subjdesc) <= 22 && mb_strlen ($fourthgrades->subjdesc) > 13)
                            {
                                $sheet->getRowDimension($fourthtable_cellno)->setRowHeight(25,'pt');  
                            }elseif(mb_strlen ($fourthgrades->subjdesc) > 22)
                            {
                                $sheet->getRowDimension($fourthtable_cellno)->setRowHeight(45,'pt'); 
                            }
                            $sheet->getStyle('Q'.$fourthtable_cellno.':AD'.$fourthtable_cellno)->getAlignment()->setVertical('center');
    
                            $sheet->getStyle('Q'.$fourthtable_cellno)->getAlignment()->setWrapText(true);
                            
                            $sheet->mergeCells('Q'.$fourthtable_cellno.':S'.$fourthtable_cellno);
                            $sheet->setCellValue('Q'.$fourthtable_cellno, $inmapeh.$fourthgrades->subjdesc);
                            $sheet->mergeCells('T'.$fourthtable_cellno.':U'.$fourthtable_cellno);
                            $sheet->setCellValue('T'.$fourthtable_cellno, $fourthgrades->q1);
                            $sheet->mergeCells('V'.$fourthtable_cellno.':W'.$fourthtable_cellno);
                            $sheet->setCellValue('V'.$fourthtable_cellno, $fourthgrades->q2);
                            $sheet->mergeCells('X'.$fourthtable_cellno.':Y'.$fourthtable_cellno);
                            $sheet->setCellValue('X'.$fourthtable_cellno, $fourthgrades->q3);
                            $sheet->mergeCells('Z'.$fourthtable_cellno.':AA'.$fourthtable_cellno);
                            $sheet->setCellValue('Z'.$fourthtable_cellno, $fourthgrades->q4);
                            $sheet->mergeCells('AB'.$fourthtable_cellno.':AC'.$fourthtable_cellno);
                            $sheet->setCellValue('AB'.$fourthtable_cellno, $fourthgrades->finalrating);
                            $sheet->mergeCells('AD'.$fourthtable_cellno.':AE'.$fourthtable_cellno);
                            $sheet->setCellValue('AD'.$fourthtable_cellno, $fourthgrades->remarks);
                            $fourthtable_cellno+=1;
                        }
                    }
                    
                    $startcellno += $maxgradecount; // general average
    
                    $startcellno += 2; // attendance
    
                    if(count($records_secondrow[0]->attendance) > 0)
                    {
                        $sheet->setCellValue('D'.$startcellno, collect($records_secondrow[0]->attendance)->sum('days'));
                        $sheet->setCellValue('I'.$startcellno, collect($records_secondrow[0]->attendance)->sum('present'));
                    }
                    
                    if(count($records_secondrow[1]->attendance) > 0)
                    {
                        $sheet->setCellValue('T'.$startcellno, collect($records_secondrow[1]->attendance)->sum('days'));
                        $sheet->setCellValue('Y'.$startcellno, collect($records_secondrow[1]->attendance)->sum('present'));
                    }
    
                    $startcellno += 9;  // Certification
    
                    $sheet->setCellValue('H'.$startcellno, $studinfo->firstname.' '.$studinfo->middlename[0].'. '. $studinfo->lastname.' '.$studinfo->suffix);
                    $sheet->setCellValue('R'.$startcellno, $studinfo->lrn);
                    $sheet->getStyle('R'.$startcellno)->getNumberFormat()->setFormatCode('0');
    
                    $startcellno += 1; // schoolinfo
    
                    $startcellno += 3;
    
                    $registrarname = DB::table('teacher')
                        ->where('userid', auth()->user()->id)
                        ->first();
    
                    $sheet->setCellValue('W'.$startcellno, $registrarname->title.' '.$registrarname->firstname.' '.$registrarname->middlename[0].'. '.$registrarname->lastname.' '.$registrarname->suffix);
    
                    $startcellno += 4;
    
                    $sheet->setCellValue('D'.$startcellno, $footer->copysentto);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('D'.$startcellno, $footer->address);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('D'.$startcellno, date('m/d/Y'));
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                {
                    $inputFileName = base_path().'/public/excelformats/sbc/sf10_jhs_revised.xlsx';

                    /**  Create a new Reader of the type defined in $inputFileType  **/
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Advise the Reader of which WorkSheets we want to load  **/
                    $reader->setLoadAllSheets();
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($inputFileName);
                    
                    $sheet = $spreadsheet->getSheet(0);
                    $records = $records->sortBy('sydesc')->sortBy('sortid')->values();
                    // return $records;
                    // return collect($studinfo);
                    
                    $sheet->setCellValue('E71',$studinfo->firstname.' '.(isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'. ' : ' ').$studinfo->lastname.' '.$studinfo->suffix);
                    $sheet->setCellValue('J71',$studinfo->lrn);
                    $sheet->getStyle('J71')->getNumberFormat()->setFormatCode('0');

                    $sheet->setCellValue('E137',$studinfo->firstname.' '.(isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'. ' : ' ').$studinfo->lastname.' '.$studinfo->suffix);
                    $sheet->setCellValue('J137',$studinfo->lrn);
                    $sheet->getStyle('J137')->getNumberFormat()->setFormatCode('0');

                    $startcellno = 7;
                    $sheet->setCellValue('C'.$startcellno,$studinfo->firstname.' '.(isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'. ' : ' ').$studinfo->lastname.' '.$studinfo->suffix);
                    $sheet->setCellValue('K'.$startcellno,$studinfo->lrn);
                    $sheet->getStyle('K'.$startcellno)->getNumberFormat()->setFormatCode('0');
                    $startcellno+=1;
                    $sheet->setCellValue('D'.$startcellno,$studinfo->dob != null ? date('m/d/Y', strtotime($studinfo->dob)) : '');
                    $sheet->setCellValue('I'.$startcellno,$studinfo->gender);
                    $startcellno+=1;
                    $sheet->setCellValue('C'.$startcellno,$studinfo->fathername);
                    $startcellno+=1;
                    $sheet->setCellValue('C'.$startcellno,$studinfo->mothername);
                    $startcellno+=2;
                    if($eligibility->completer == 1)
                    {
                        $sheet->setCellValue('A'.$startcellno,'/');
                    }
                    $sheet->setCellValue('F'.$startcellno,$eligibility->genave);
                    $sheet->setCellValue('H'.$startcellno,$eligibility->schoolname);
                    $sheet->setCellValue('L'.$startcellno,$eligibility->schooladdress);
                    $startcellno+=1;
                    if($eligibility->peptpasser == 1)
                    {
                        $sheet->setCellValue('A'.$startcellno,'/');
                    }
                    $sheet->setCellValue('F'.$startcellno,$eligibility->peptrating);
                    $sheet->setCellValue('H'.$startcellno,$eligibility->examdate != null ? date('m/d/Y', strtotime($eligibility->examdate)) : '');
                    $sheet->setCellValue('K'.$startcellno,$eligibility->centername);
                    $startcellno+=1;
                    if($eligibility->alspasser == 1)
                    {
                        $sheet->setCellValue('A'.$startcellno,'/');
                    }
                    $sheet->setCellValue('F'.$startcellno,$eligibility->alsrating);
                    $sheet->setCellValue('H'.$startcellno,$eligibility->examdate != null ? date('m/d/Y', strtotime($eligibility->examdate)) : '');
                    $sheet->setCellValue('K'.$startcellno,$eligibility->centername);
                    $startcellno+=1;
                    $sheet->setCellValue('E'.$startcellno,$eligibility->specifyothers);
                    $startcellno+=3;
                    // return $records;
                    foreach($records as $eachkey=>$eachrecord)
                    {
                        $sheet->setCellValue('C'.$startcellno,$eachrecord->sydesc);
                        $startcellno+=1;
                        $sheet->setCellValue('G'.$startcellno,$eachrecord->teachername);
                        $startcellno+=1;
                        $sheet->setCellValue('C'.$startcellno,$eachrecord->sectionname);
                        $sheet->setCellValue('H'.$startcellno,collect($eachrecord->attendance)->sum('days'));
                        $startcellno+=3;
                        
                        $grades = collect($eachrecord->grades)->whereNotIn('id', collect($eachrecord->generalaverage)->pluck('id'))->all();
                        if(count($grades)>0)
                        {
                            foreach($grades as $eachsubject)
                            {
                                $sheet->setCellValue('A'.$startcellno,'');
                                $sheet->setCellValue('B'.$startcellno,'');
                                $sheet->setCellValue('F'.$startcellno,'');
                                $sheet->setCellValue('G'.$startcellno,'');
                                $sheet->setCellValue('H'.$startcellno,'');
                                $sheet->setCellValue('I'.$startcellno,'');
                                $sheet->setCellValue('J'.$startcellno,'');
                                $sheet->setCellValue('K'.$startcellno,'');
                                // return $eachsubject;
                                if($eachsubject->inMAPEH === 1)
                                {
                                    $sheet->setCellValue('B'.$startcellno,$eachsubject->subjdesc);
                                }else{
                                    $sheet->setCellValue('A'.$startcellno,$eachsubject->subjdesc);
                                }
                                $sheet->setCellValue('F'.$startcellno,$eachsubject->q1);
                                $sheet->setCellValue('G'.$startcellno,$eachsubject->q2);
                                $sheet->setCellValue('H'.$startcellno,$eachsubject->q3);
                                $sheet->setCellValue('I'.$startcellno,$eachsubject->q4);
                                $sheet->setCellValue('J'.$startcellno,$eachsubject->finalrating);
                                $sheet->setCellValue('K'.$startcellno,$eachsubject->remarks);
                                $startcellno+=1;
                            }
                            for($x = count($grades); $x < 15; $x++)
                            {
                                $sheet->setCellValue('A'.$startcellno,'');
                                $sheet->setCellValue('B'.$startcellno,'');
                                $sheet->setCellValue('F'.$startcellno,'');
                                $sheet->setCellValue('G'.$startcellno,'');
                                $sheet->setCellValue('H'.$startcellno,'');
                                $sheet->setCellValue('I'.$startcellno,'');
                                $sheet->setCellValue('J'.$startcellno,'');
                                $sheet->setCellValue('K'.$startcellno,'');
                                $startcellno+=1;
                            }
                        }else{
                            for($x = count($grades); $x < 15; $x++)
                            {
                                $sheet->setCellValue('A'.$startcellno,'');
                                $sheet->setCellValue('B'.$startcellno,'');
                                $sheet->setCellValue('F'.$startcellno,'');
                                $sheet->setCellValue('G'.$startcellno,'');
                                $sheet->setCellValue('H'.$startcellno,'');
                                $sheet->setCellValue('I'.$startcellno,'');
                                $sheet->setCellValue('J'.$startcellno,'');
                                $sheet->setCellValue('K'.$startcellno,'');
                                $startcellno+=1;
                            }
                        }
                        // for($defnum = 13; $defnum < count($grades); $defnum++)
                        // {
                        //     $sheet->insertNewRowBefore($startcellno, 1);
                        //     $sheet->setCellValue('A'.$startcellno,'');
                        //     $sheet->setCellValue('B'.$startcellno,'');
                        //     $sheet->setCellValue('F'.$startcellno,'');
                        //     $sheet->setCellValue('G'.$startcellno,'');
                        //     $sheet->setCellValue('H'.$startcellno,'');
                        //     $sheet->setCellValue('I'.$startcellno,'');
                        //     $sheet->setCellValue('J'.$startcellno,'');
                        //     $sheet->mergeCells('K'.$startcellno.':L'.$startcellno);
                        //     $sheet->setCellValue('K'.$startcellno,'');
                        // }
                        if(count($eachrecord->generalaverage)>0)
                        {
                            $sheet->setCellValue('F'.$startcellno,$eachrecord->generalaverage[0]->q1);
                            $sheet->setCellValue('G'.$startcellno,$eachrecord->generalaverage[0]->q2);
                            $sheet->setCellValue('H'.$startcellno,$eachrecord->generalaverage[0]->q3);
                            $sheet->setCellValue('I'.$startcellno,$eachrecord->generalaverage[0]->q4);
                            $sheet->setCellValue('J'.$startcellno,$eachrecord->generalaverage[0]->finalrating);
                            $sheet->setCellValue('K'.$startcellno,$eachrecord->generalaverage[0]->remarks ?? $eachrecord->generalaverage[0]->actiontaken ?? '');
                        }
                        $startcellno+=6;
                        if($eachkey == 1)
                        {
                            $startcellno+=13;
                        }
                        elseif($eachkey == 3)
                        {
                            $startcellno+=12;
                            // return collect($footer);
                            $sheet->setCellValue('C'.$startcellno,$footer->copysentto != null ? $footer->copysentto : $footer->purpose);
                            $startcellno+=1;
                            $sheet->setCellValue('C'.$startcellno,$footer->address);
                        }
                    }
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.xlsx"');
                    $writer->save("php://output");
                    exit;

                }
                else{
                    $inputFileName = base_path().'/public/excelformats/sf10_jhs.xlsx';

                    /**  Create a new Reader of the type defined in $inputFileType  **/
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Advise the Reader of which WorkSheets we want to load  **/
                    $reader->setLoadAllSheets();
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($inputFileName);
                    
                    $sheet = $spreadsheet->getSheet(0);

                    $sheet->setCellValue('C7', $studinfo->lastname);
                    $sheet->setCellValue('G7', $studinfo->firstname);
                    $sheet->setCellValue('K7', $studinfo->suffix);
                    $sheet->setCellValue('M7', $studinfo->middlename);

                    $sheet->setCellValue('E8', $studinfo->lrn);
                    $sheet->getStyle('E8')->getNumberFormat()->setFormatCode('0');
                    $sheet->setCellValue('I8', date('m/d/Y', strtotime($studinfo->dob)));
                    $sheet->setCellValue('M8', $studinfo->gender);
                    
                    // E L I G I B I L I T Y
                    if($eligibility->completer == 1)
                    {
                        $sheet->setCellValue('B12', '[ / ]');
                    }else{
                        $sheet->setCellValue('B12', '[   ]');
                    }

                    $sheet->getStyle('B12')->getAlignment()->setHorizontal('center');

                    $sheet->setCellValue('I12', $eligibility->genave);
                    $sheet->setCellValue('L12', $eligibility->citation);
                    $sheet->setCellValue('F13', $eligibility->schoolname);
                    $sheet->setCellValue('J13', $eligibility->schoolid);
                    $sheet->setCellValue('L13', $eligibility->schooladdress);

                    $passingdetails = '';
                    if($eligibility->peptpasser == 1)
                    {
                        $passingdetails .= '     [  /  ]        ';
                    }else{
                        $passingdetails .= '     [     ]        ';
                    }
                    $passingdetails .= ' PEPT Passer                Rating:    '.$eligibility->peptrating;
                    if($eligibility->alspasser == 1)
                    {
                        $passingdetails .= '            [  /  ]        ';
                    }else{
                        $passingdetails .= '            [     ]        ';
                    }
                    $passingdetails .= ' ALS A & E Passer                    Rating:     '.$eligibility->alsrating;
                    $passingdetails .= '                                  Others (Pls. Specify):     '.$eligibility->specifyothers;

                    $sheet->setCellValue('B15', $passingdetails);

                    if($eligibility->examdate!= null)
                    {
                        $eligibility->examdate= date('m/d/Y',strtotime($eligibility->examdate));
                    }

                    $sheet->setCellValue('B16', "      Date of Examination/Assessment (mm/dd/yyyy):     ".$eligibility->examdate."      Name and Address of Testing Center:     ".$eligibility->centername."    ");
                    

                    //////// F O O T E R //////////
                    $certificationdetails = 'I CERTIFY that this is a true record of       '.$studinfo->firstname.' '.$studinfo->middlename[0].'. '.$studinfo->lastname.' '.$studinfo->suffix.'      with LRN     '.$studinfo->lrn.'        and that he/she is  eligible for admission to Grade ____.';

                    $sheet->setCellValue('B51', $certificationdetails);

                    $sheet->setCellValue('B52', 'Name of School:  '.$schoolinfo->schoolname.'              School ID: '.$schoolinfo->schoolid.'                     Last School Year Attended: __________________');

                    $sheet->setCellValue('B54', '');
                    $sheet->setCellValue('C54', date('m/d/Y'));
                    $sheet->getStyle('B54')->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('C55')->getAlignment()->setHorizontal('center');

                    
                    $sheet->setCellValue('E54', $schoolinfo->authorized);
                    //////// ! FOOTER ! ////////

                    $frontrecords = $records[0];

                    foreach($frontrecords as $frontrecord)
                    {
                        foreach($frontrecord as $key => $value)
                        {
                            if($value == null)
                            {   
                                if($key == 'grades'||$key == 'subjaddedforauto' || $key == 'generalaverage')
                                {
                                    $frontrecord->$key = array();
                                }else{
                                    $frontrecord->$key = '_______________';
                                }
                                // return $key;
                                // $frontrecord->$key;
                            }
                        }
                    }
                    
                          
                    if(collect($frontrecords[0]->generalaverage)->count()>0)
                    {
                        $sheet->mergeCells('L27:M27');                    
                        $sheet->setCellValue('K27', number_format(collect($frontrecords[0]->generalaverage)->first()->finalrating));
                        $sheet->setCellValue('L27', collect($frontrecords[0]->generalaverage)->first()->remarks);
                    }
                        
                    if(collect($frontrecords[1]->generalaverage)->count()>0)
                    {
                        $sheet->mergeCells('L41:M41');
                        $sheet->setCellValue('K41', number_format(collect($frontrecords[1]->generalaverage)->first()->finalrating));
                        $sheet->setCellValue('L41', collect($frontrecords[1]->generalaverage)->first()->finalrating);
                    }

                    ///// FIRST GRADES TABLE
                        $firstschoolinfo = 'School: '.$frontrecords[0]->schoolname.'     School ID: '.$frontrecords[0]->schoolid.'        District: '.$frontrecords[0]->schooldistrict.'      Division: '.$frontrecords[0]->schooldivision.'      Region: '.$frontrecords[0]->schoolregion;
                        
                        $sheet->setCellValue('B20', $firstschoolinfo);

                        $firstlevelinfo = 'Classified as Grade: '.preg_replace('/\D+/', '', $frontrecords[0]->levelname).'   Section: '.$frontrecords[0]->sectionname.'  School Year: '.$frontrecords[0]->sydesc.'   Name of Adviser/Teacher: '.$frontrecords[0]->teachername.' Signature: __________';
                        $sheet->setCellValue('B21', $firstlevelinfo);

                        $sheet->insertNewRowBefore(25, ($maxgradecount-2));
                        $firstgradescellno = 25;
                        for($x = 25; $x < ((23+$maxgradecount)); $x++)
                        {
                            $firstgradescellno+=1;
                            $sheet->mergeCells('B'.$x.':F'.$x);
                            $sheet->mergeCells('L'.$x.':M'.$x);
                        }
                        $firstconstantno = 25;
                        $countsubj = 0;
                        if(count($frontrecords[0]->grades)>0)
                        {
                            foreach($frontrecords[0]->grades as $g7grade)
                            {
                                if(strtolower($g7grade->subjdesc) != 'general average')
                                {
                                    $countsubj += 1;
                                    $space = '';
                                    if($g7grade->inMAPEH == 1 || $g7grade->inTLE == 1)
                                    {
                                        $space = "           ";
                                    }
                                    $sheet->setCellValue('B'.$firstconstantno, $space.$g7grade->subjdesc);
                                    $sheet->getStyle('B'.$firstconstantno)->getAlignment()->setHorizontal('left');
                                    $sheet->setCellValue('G'.$firstconstantno, $g7grade->q1);
                                    $sheet->setCellValue('H'.$firstconstantno, $g7grade->q2);
                                    $sheet->setCellValue('I'.$firstconstantno, $g7grade->q3);
                                    $sheet->setCellValue('J'.$firstconstantno, $g7grade->q4);
                                    $sheet->setCellValue('K'.$firstconstantno, $g7grade->finalrating);
                                    $sheet->setCellValue('L'.$firstconstantno, $g7grade->remarks);
                                    $firstconstantno+=1;
                                }
                            }
                        }
                        
                        if(count($frontrecords[0]->subjaddedforauto)>0)
                        {
                            foreach($frontrecords[0]->subjaddedforauto as $customsubjgrade)
                            {
                                if(strtolower($customsubjgrade->subjdesc) != 'general average')
                                {
                                    $countsubj += 1;
                                    $sheet->setCellValue('B'.$firstconstantno, $customsubjgrade->subjdesc);
                                    $sheet->getStyle('B'.$firstconstantno)->getAlignment()->setHorizontal('left');
                                    $sheet->setCellValue('G'.$firstconstantno, $customsubjgrade->q1);
                                    $sheet->setCellValue('H'.$firstconstantno, $customsubjgrade->q2);
                                    $sheet->setCellValue('I'.$firstconstantno, $customsubjgrade->q3);
                                    $sheet->setCellValue('J'.$firstconstantno, $customsubjgrade->q4);
                                    $sheet->setCellValue('K'.$firstconstantno, $customsubjgrade->finalrating);
                                    $sheet->setCellValue('L'.$firstconstantno, $customsubjgrade->actiontaken);
                                    $firstconstantno+=1;
                                }
                            }
                        }

                        $firstgradescellno+=9;
                    ///// !FIRST GRADES TABLE! //////
                    ///// SECOND GRADES TABLE
                        $secondgradescellno = $firstgradescellno;
                        $secondschoolinfo = 'School: '.$frontrecords[1]->schoolname.'     School ID: '.$frontrecords[1]->schoolid.'        District: '.$frontrecords[1]->schooldistrict.'      Division: '.$frontrecords[1]->schooldivision.'      Region: '.$frontrecords[1]->schoolregion;
                        $sheet->setCellValue('B'.$secondgradescellno, $secondschoolinfo);
                        $secondgradescellno+=1;
                        $secondlevelinfo = 'Classified as Grade: '.preg_replace('/\D+/', '', $frontrecords[1]->levelname).'   Section: '.$frontrecords[1]->sectionname.'  School Year: '.$frontrecords[1]->sydesc.'   Name of Adviser/Teacher: '.$frontrecords[1]->teachername.' Signature: __________';
                        $sheet->setCellValue('B'.$secondgradescellno, $secondlevelinfo);
                        $secondgradescellno+=4;

                        // return $secondgradescellno;
                        $sheet->insertNewRowBefore($secondgradescellno, ($maxgradecount-2));
                        
                        for($x = $secondgradescellno; $x < (($secondgradescellno+$maxgradecount)-2); $x++)
                        {
                            $sheet->mergeCells('B'.$x.':F'.$x);
                            $sheet->mergeCells('L'.$x.':M'.$x);
                        }
                        $countsubj = 0;
                        if(count($frontrecords[1]->grades)>0)
                        {
                            foreach($frontrecords[1]->grades as $g8grade)
                            {
                                if(strtolower($g8grade->subjdesc) != 'general average')
                                {
                                    $countsubj += 1;
                                    $sheet->setCellValue('B'.$secondgradescellno, $g8grade->subjdesc);
                                    $sheet->getStyle('B'.$secondgradescellno)->getAlignment()->setHorizontal('left');
                                    $sheet->setCellValue('G'.$secondgradescellno, $g8grade->q1);
                                    $sheet->setCellValue('H'.$secondgradescellno, $g8grade->q2);
                                    $sheet->setCellValue('I'.$secondgradescellno, $g8grade->q3);
                                    $sheet->setCellValue('J'.$secondgradescellno, $g8grade->q4);
                                    $sheet->setCellValue('K'.$secondgradescellno, $g8grade->finalrating);
                                    $sheet->setCellValue('L'.$secondgradescellno, $g8grade->remarks);
                                    $secondgradescellno+=1;
                                }
                            }
                        }
                        if(count($frontrecords[1]->subjaddedforauto)>0)
                        {
                            foreach($frontrecords[1]->subjaddedforauto as $customsubjgrade)
                            {
                                if(strtolower($customsubjgrade->subjdesc) != 'general average')
                                {
                                    $countsubj += 1;
                                    $sheet->setCellValue('B'.$secondgradescellno, $customsubjgrade->subjdesc);
                                    $sheet->getStyle('B'.$secondgradescellno)->getAlignment()->setHorizontal('left');
                                    $sheet->setCellValue('G'.$secondgradescellno, $customsubjgrade->q1);
                                    $sheet->setCellValue('H'.$secondgradescellno, $customsubjgrade->q2);
                                    $sheet->setCellValue('I'.$secondgradescellno, $customsubjgrade->q3);
                                    $sheet->setCellValue('J'.$secondgradescellno, $customsubjgrade->q4);
                                    $sheet->setCellValue('K'.$secondgradescellno, $customsubjgrade->finalrating);
                                    $sheet->setCellValue('L'.$secondgradescellno, $customsubjgrade->actiontaken);
                                    $secondgradescellno+=1;
                                }
                            }
                        }
                    ///// !SECOND GRADES TABLE! //////

                    $sheet = $spreadsheet->getSheet(1);

                    $backrecords = $records[1];

                    foreach($backrecords as $backrecord)
                    {
                        foreach($backrecord as $key => $value)
                        {
                            if($value == null)
                            {   
                                if($key == 'grades' || $key == 'subjaddedforauto')
                                {
                                    $backrecord->$key = array();
                                }else{
                                    $backrecord->$key = '________';
                                }
                            }
                        }
                    }
                    //////// F O O T E R //////////
                    $backcertificationdetails = 'I CERTIFY that this is a true record of       '.$studinfo->firstname.' '.$studinfo->middlename[0].'. '.$studinfo->lastname.' '.$studinfo->suffix.'      with LRN     '.$studinfo->lrn.'        and that he/she is  eligible for admission to Grade ____.';

                    $sheet->setCellValue('A31', $backcertificationdetails);

                    $sheet->setCellValue('A32', 'Name of School:  '.$schoolinfo->schoolname.'              School ID: '.$schoolinfo->schoolid.'                     Last School Year Attended: __________________');

                    $sheet->setCellValue('A34', '');
                    $sheet->setCellValue('B34', date('m/d/Y'));
                    $sheet->getStyle('A34')->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('D34')->getAlignment()->setHorizontal('center');

                    
                    $sheet->setCellValue('D34', $schoolinfo->authorized);
                    //////// ! FOOTER ! ////////
                    ///// THIRD GRADES TABLE
                        $thirdschoolinfo = 'School: '.$backrecords[0]->schoolname.'     School ID: '.$backrecords[0]->schoolid.'        District: '.$backrecords[0]->schooldistrict.'      Division: '.$backrecords[0]->schooldivision.'      Region: '.$backrecords[0]->schoolregion;
                        
                        $sheet->setCellValue('A3', $thirdschoolinfo);

                        $thirdlevelinfo = 'Classified as Grade: '.preg_replace('/\D+/', '', $backrecords[0]->levelname).'   Section: '.$backrecords[0]->sectionname.'  School Year: '.$backrecords[0]->sydesc.'   Name of Adviser/Teacher: '.$backrecords[0]->teachername.' Signature: __________';
                        $sheet->setCellValue('A4', $thirdlevelinfo);

                        $sheet->insertNewRowBefore(8, ($maxgradecount-1));
                        $thirdgradescellno = 8;
                        
                        for($x = 8; $x <= ((6+$maxgradecount)); $x++)
                        {
                            $sheet->mergeCells('A'.$x.':E'.$x);
                            $sheet->mergeCells('K'.$x.':L'.$x);
                            $thirdgradescellno+=1;
                        }
                        
                        $thirdconstantno = 8;
                        $countsubj = 0;
                        if(count($backrecords[0]->grades)>0)
                        {
                            foreach($backrecords[0]->grades as $g9grade)
                            {
                                if(strtolower($g9grade->subjdesc) != 'general average')
                                {
                                    $countsubj += 1;
                                    $sheet->setCellValue('A'.$thirdconstantno, $g9grade->subjdesc);
                                    $sheet->getStyle('A'.$thirdconstantno)->getAlignment()->setHorizontal('left');
                                    $sheet->setCellValue('F'.$thirdconstantno, $g9grade->q1);
                                    $sheet->setCellValue('G'.$thirdconstantno, $g9grade->q2);
                                    $sheet->setCellValue('H'.$thirdconstantno, $g9grade->q3);
                                    $sheet->setCellValue('I'.$thirdconstantno, $g9grade->q4);
                                    $sheet->setCellValue('J'.$thirdconstantno, $g9grade->finalrating);
                                    $sheet->setCellValue('K'.$thirdconstantno, $g9grade->remarks);
                                    $thirdconstantno+=1;
                                }
                            }
                            // $sheet->setCellValue('J'.$thirdgradescellno, collect($backrecords[0]->grades)->where('inMAPEH',0)->avg('finalrating'));
                        }
                        if(count($backrecords[0]->subjaddedforauto)>0)
                        {
                            foreach($backrecords[0]->subjaddedforauto as $customsubjgrade)
                            {
                                if(strtolower($g9grade->subjdesc) != 'general average')
                                {
                                    $countsubj += 1;
                                    $sheet->setCellValue('A'.$thirdconstantno, $customsubjgrade->subjdesc);
                                    $sheet->getStyle('A'.$thirdconstantno)->getAlignment()->setHorizontal('left');
                                    $sheet->setCellValue('F'.$thirdconstantno, $customsubjgrade->q1);
                                    $sheet->setCellValue('G'.$thirdconstantno, $customsubjgrade->q2);
                                    $sheet->setCellValue('H'.$thirdconstantno, $customsubjgrade->q3);
                                    $sheet->setCellValue('I'.$thirdconstantno, $customsubjgrade->q4);
                                    $sheet->setCellValue('J'.$thirdconstantno, $customsubjgrade->finalrating);
                                    $sheet->setCellValue('K'.$thirdconstantno, $customsubjgrade->actiontaken);
                                    $thirdconstantno+=1;
                                }
                            }
                        }
                        if(DB::table('schoolinfo')->first()->schoolid == '405308') // fmcma
                        {
                            for($x = $countsubj; $x < $maxgradecount; $x++)
                            {
                                $sheet->mergeCells('A'.$thirdconstantno.':E'.$thirdconstantno);
                                $sheet->mergeCells('K'.$thirdconstantno.':L'.$thirdconstantno);
                                $thirdconstantno+=1;
                            }
                            
                            $sheet->setCellValue('J'.$thirdconstantno, number_format(collect($backrecords[0]->generalaverage)->first()->finalrating));
                        }else{
                            $thirdgradescellno+=1;
                            $sheet->setCellValue('J'.$thirdgradescellno, collect($backrecords[0]->grades)->where('inMAPEH',0)->avg('finalrating'));
                        }
                        
                        $thirdgradescellno+=9;
                    ///// !THIRD GRADES TABLE! //////
                    ///// FOURTH GRADES TABLE
                        $fourthgradescellno = $thirdgradescellno;
                        $fourthschoolinfo = 'School: '.$backrecords[1]->schoolname.'     School ID: '.$backrecords[1]->schoolid.'        District: '.$backrecords[1]->schooldistrict.'      Division: '.$backrecords[1]->schooldivision.'      Region: '.$backrecords[1]->schoolregion;
                        $sheet->setCellValue('A'.$fourthgradescellno, $fourthschoolinfo);
                        $fourthgradescellno+=1;
                        $fourthlevelinfo = 'Classified as Grade: '.preg_replace('/\D+/', '', $backrecords[1]->levelname).'   Section: '.$backrecords[1]->sectionname.'  School Year: '.$backrecords[1]->sydesc.'   Name of Adviser/Teacher: '.$backrecords[1]->teachername.' Signature: __________';
                        $sheet->setCellValue('A'.$fourthgradescellno, $fourthlevelinfo);
                        $fourthgradescellno+=2;

                        $sheet->insertNewRowBefore($fourthgradescellno, ($maxgradecount-1));
                        
                        for($x = $fourthgradescellno; $x < (($fourthgradescellno+$maxgradecount)-1); $x++)
                        {
                            $sheet->mergeCells('A'.$x.':E'.$x);
                            $sheet->mergeCells('K'.$x.':L'.$x);
                        }
                        $countsubj = 0;

                        if(count($backrecords[1]->grades)>0)
                        {
                            foreach($backrecords[1]->grades as $g10grade)
                            {
                                if(strtolower($g10grade->subjdesc) != 'general average')
                                {
                                    $countsubj+=1;
                                    $sheet->setCellValue('A'.$fourthgradescellno, $g10grade->subjdesc);
                                    $sheet->getStyle('A'.$fourthgradescellno)->getAlignment()->setHorizontal('left');
                                    $sheet->setCellValue('F'.$fourthgradescellno, $g10grade->q1);
                                    $sheet->setCellValue('G'.$fourthgradescellno, $g10grade->q2);
                                    $sheet->setCellValue('H'.$fourthgradescellno, $g10grade->q3);
                                    $sheet->setCellValue('I'.$fourthgradescellno, $g10grade->q4);
                                    $sheet->setCellValue('J'.$fourthgradescellno, $g10grade->finalrating);
                                    $sheet->setCellValue('K'.$fourthgradescellno, $g10grade->remarks);
                                    $fourthgradescellno+=1;
                                }
                            }
                        }
                        if(count($backrecords[1]->subjaddedforauto)>0)
                        {
                            foreach($backrecords[1]->subjaddedforauto as $customsubjgrade)
                            {
                                if(strtolower($customsubjgrade->subjdesc) != 'general average')
                                {
                                    $countsubj+=1;
                                    $sheet->setCellValue('A'.$fourthgradescellno, $customsubjgrade->subjdesc);
                                    $sheet->getStyle('A'.$fourthgradescellno)->getAlignment()->setHorizontal('left');
                                    $sheet->setCellValue('F'.$fourthgradescellno, $customsubjgrade->q1);
                                    $sheet->setCellValue('G'.$fourthgradescellno, $customsubjgrade->q2);
                                    $sheet->setCellValue('H'.$fourthgradescellno, $customsubjgrade->q3);
                                    $sheet->setCellValue('I'.$fourthgradescellno, $customsubjgrade->q4);
                                    $sheet->setCellValue('J'.$fourthgradescellno, $customsubjgrade->finalrating);
                                    $sheet->setCellValue('K'.$fourthgradescellno, $customsubjgrade->actiontaken);
                                    $fourthgradescellno+=1;
                                }
                            }
                        }
                        
                        if(DB::table('schoolinfo')->first()->schoolid == '405308') // fmcma
                        {
                            for($x = $countsubj; $x < $maxgradecount; $x++)
                            {
                                $sheet->mergeCells('A'.$fourthgradescellno.':E'.$fourthgradescellno);
                                $sheet->mergeCells('K'.$fourthgradescellno.':L'.$fourthgradescellno);
                                $fourthgradescellno+=1;
                            }
                            
                            $sheet->setCellValue('J'.$fourthgradescellno, number_format(collect($backrecords[1]->generalaverage)->first()->finalrating));
                        }else{
                            $sheet->setCellValue('J'.$fourthgradescellno, collect($backrecords[1]->grades)->where('inMAPEH',0)->avg('finalrating'));
                        }
                    ///// !FOURTH GRADES TABLE! //////
                }
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.xlsx"');
                $writer->save("php://output");
            }
        }else{
            
            return view('registrar.forms.form10.v3.records_jhs')
            ->with('acadprogid', $acadprogid)
            ->with('eachlevelsignatories', $eachlevelsignatories)
            ->with('studinfo', $studinfo)
            ->with('eligibility', $eligibility)
        // return view('registrar.forms.form10.gradeselem')
            ->with('records', $records->sortByDesc('sydesc'))
            ->with('footer', $footer)
            ->with('gradelevels', collect($gradelevels)->sortBy('sortid'));
        }

    }
    public function getrecords_shs(Request $request)
    {
        $acadprogid = $request->get('acadprogid');
        $studentid = $request->get('studentid');
        
        $gradelevels = DB::table('gradelevel')
            ->select(
                'gradelevel.id',
                'gradelevel.levelname',
                'gradelevel.sortid'
            )
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('academicprogram.id',$request->get('acadprogid'))
            ->where('gradelevel.deleted','0')
            ->get();
        foreach($gradelevels as $gradelevel)
        {
            $gradelevel->subjects = DB::table('subject_plot')
                ->select('sh_subjects.*','subject_plot.syid','subject_plot.semid','subject_plot.levelid','sy.sydesc','subject_plot.strandid','subject_plot.plotsort')
                ->join('sh_subjects','subject_plot.subjid','=','sh_subjects.id')
                ->join('sy','subject_plot.syid','=','sy.id')
                ->where('subject_plot.deleted','0')
                ->where('sh_subjects.deleted','0')
                ->where('sh_subjects.inSF9','1')
                ->orderBy('sh_subj_sortid','asc')
                // ->where('subject_plot.syid', $sy->syid)
                ->where('subject_plot.levelid', $gradelevel->id)
                ->get();
                
            $gradelevel->subjects = collect($gradelevel->subjects)->sortBy('plotsort')->unique();
        }

        $currentschoolyear = Db::table('sy')
            ->where('isactive','1')
            ->first();
            
        $school = DB::table('schoolinfo')
            ->first();            

        $studinfo = Db::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'studinfo.lrn',
                'studinfo.dob',
                'studinfo.gender',
                'studinfo.levelid',
                'studinfo.street',
                'studinfo.barangay',
                'studinfo.pob',
                'studinfo.city',
                'studinfo.province',
                'studinfo.mothername',
                'studinfo.moccupation',
                'studinfo.fathername',
                'studinfo.foccupation',
                'studinfo.guardianname',
                'studinfo.ismothernum',
                'studinfo.isfathernum',
                'studinfo.isguardannum',
                'gradelevel.levelname',
                'sectionid as ensectid',
                'gradelevel.acadprogid',
                'strandid'
                //  'sh_strand.strandname',
                //  'sh_strand.strandcode'
                )
            ->leftJoin('gradelevel','studinfo.levelid','gradelevel.id')
            // ->leftJoin('sh_strand','studinfo.strandid','sh_strand.id')
            ->where('studinfo.id',$studentid)
            ->first();
            
        $studaddress = '';

        if($studinfo->street!=null)
        {
            $studaddress.=$studinfo->street.', ';
        }
        if($studinfo->barangay!=null)
        {
            $studaddress.=$studinfo->barangay.', ';
        }
        if($studinfo->city!=null)
        {
            $studaddress.=$studinfo->city.', ';
        }
        if($studinfo->province!=null)
        {
            $studaddress.=$studinfo->province.', ';
        }

        $studinfo->address = substr($studaddress,0,-2);

    
        $schoolyears = DB::table('sh_enrolledstud')
            ->select(
                'sh_enrolledstud.id',
                'sh_enrolledstud.syid',
                'sy.sydesc',
                'sy.sdate',
                'sy.edate',
                'sh_enrolledstud.semid',
                'sh_enrolledstud.blockid',
                'academicprogram.id as acadprogid',
                'sh_enrolledstud.levelid',
                'sh_enrolledstud.strandid',
                'sh_strand.strandname',
                'sh_strand.strandcode',
                'sh_strand.trackid',
                'sh_track.trackname',
                'gradelevel.levelname',
                'sh_enrolledstud.sectionid',
                'sh_enrolledstud.sectionid as ensectid',
                'sections.sectionname as section',
                'sh_enrolledstud.levelid as enlevelid'
                )
            ->join('gradelevel','sh_enrolledstud.levelid','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
            ->join('sy','sh_enrolledstud.syid','sy.id')
            ->join('sections','sh_enrolledstud.sectionid','sections.id')
            ->leftJoin('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
            ->leftJoin('sh_track','sh_strand.trackid','=','sh_track.id')
            ->where('sh_enrolledstud.deleted','0')
            ->where('academicprogram.id',$acadprogid)
            ->where('sh_enrolledstud.studid',$studentid)
            // ->whereIn('sh_enrolledstud.studstatus',[1,2,4])

            ->where('sh_enrolledstud.studstatus','!=','0')
            ->where('sh_enrolledstud.studstatus','<=','5')
            ->distinct()
            ->orderByDesc('sh_enrolledstud.levelid')
            ->get();
            
        if(count($schoolyears) != 0){
            
            $currentlevelid = (object)array(
                'syid'      => $schoolyears[0]->syid,
                'levelid'   => $schoolyears[0]->levelid,
                'levelname' => $schoolyears[0]->levelname
            );

        }

        else{

            $currentlevelid = (object)array(
                'syid' => $currentschoolyear->id,
                'levelid' => $studinfo->levelid,
                'levelname' => $studinfo->levelname
            );

        }

        $failingsubjectsArray = array();

        $gradelevelsenrolled = array();

        $autorecords = array();
        // return GradesData::student_grades_sh()
        
        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.abbreviation',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.districttext',
                'schoolinfo.divisiontext',
                'schoolinfo.regiontext',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();

        $displayaccomplished = '';

        foreach($schoolyears as $sy){

       
             if($studinfo->ensectid == null){
                 $studinfo->ensectid = $sy->sectionid;
             }
            

            array_push($gradelevelsenrolled,(object)array(
                'levelid' => $sy->levelid,
                'levelname' => $sy->levelname
            ));
            
            $studinfo->semid = $sy->semid;
            $studinfo->levelid = $sy->levelid;
            $studinfo->enlevelid = $sy->levelid;
            $generalaverage = array();

            
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
            {
                $strand = $studinfo->strandid;
                $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                if($grading_version->version == 'v2'){
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $sy->levelid,$studinfo->id,$sy->syid,$strand,null,$sy->sectionid);
                }else{
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,$strand,null,$sy->sectionid);
                }
                $temp_grades = array();
                foreach($studgrades as $item){
                    if($item->id == 'G1'){
                        array_push($generalaverage,$item);
                    }else{
                        if($item->strandid == $strand){
                            array_push($temp_grades,$item);
                        }
                        if($item->strandid == null){
                            array_push($temp_grades,$item);
                        }
                    }
                }
            
                $studgrades = $temp_grades;
                $grades = collect($studgrades)->sortBy('sortid')->values();
            }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'svai' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lchsi')
            {
                $strand = $studinfo->strandid;
                
                $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
                Session::put('schoolYear', $schoolyear);
                $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                if($grading_version->version == 'v2'){
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $sy->levelid,$studinfo->id,$schoolyear->id,$strand,null,$sy->sectionid);
                }else{
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$schoolyear->id,$strand,null,$sy->sectionid);
                }
                $temp_grades = array();
                $generalaverage = array();
                foreach($studgrades as $item){
                    if($item->id == 'G1'){
                        array_push($generalaverage,$item);
                    }else{
                        if($item->strandid == $strand){
                            array_push($temp_grades,$item);
                        }
                        if($item->strandid == null){
                            array_push($temp_grades,$item);
                        }
                    }
                }
               
                $studgrades = $temp_grades;
                $studgrades = collect($studgrades)->sortBy('sortid')->values();
                $grades = $studgrades;
            }
            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
            {
                $studinfo->semid = $sy->semid;
                $studinfo->acadprogid = $sy->acadprogid;
                $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
                Session::put('schoolYear', $schoolyear);
                $grades = \App\Models\Principal\GenerateGrade::reportCardV3($studinfo, true, 'sf9');
                $generalaverage = \App\Models\Principal\GenerateGrade::genAveV3($grades);
                foreach($grades as $key=>$item){
    
                    $checkStrand = DB::table('sh_subjstrand')
                                        ->where('subjid',$item->subjid)
                                        ->where('strandid', $studinfo->strandid)
                                        ->where('deleted',0)
                                        ->count();
    
                    if($checkStrand == 0){
    
                        unset($grades[$key]);
    
                    }
    
    
                }

            }
            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
            {
                $strand = $studinfo->strandid;
                $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
                Session::put('schoolYear', $schoolyear);
                $subjects = \App\Models\Principal\SPP_Subject::getSubject(null,null,null,$sy->sectionid,null,null,null,null,'sf9',$schoolyear->id)[0]->data;
                
                $temp_subject = array();
        
                foreach($subjects as $item){
                    array_push($temp_subject,$item);
                }
                                
                
                $subjects = $temp_subject;
                $studgrades = \App\Models\Grades\GradesData::student_grades_detail($sy->syid,null,$sy->sectionid,null,$studinfo->id, $sy->levelid,$strand,null,$subjects);
                
                $studgrades =  \App\Models\Grades\GradesData::get_finalrating($studgrades,$sy->acadprogid);;
                $finalgrade =  \App\Models\Grades\GradesData::general_average($studgrades);
                $generalaverage =  \App\Models\Grades\GradesData::get_finalrating($finalgrade,$sy->acadprogid);
                $generalaverage = collect($generalaverage)->where('semid', $sy->semid)->values();
                
                $grades = $studgrades;

            }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'csl')
            {
                    
                $strandid = $studinfo->strandid;
                $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                        
                $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
                Session::put('schoolYear', $schoolyear);
                if($grading_version->version == 'v2'){
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $sy->levelid,$studinfo->id,$sy->syid,$strandid,null,$sy->sectionid);
                }else{
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($sy->levelid,$studinfo->id,$sy->syid,$strandid,null,$sy->sectionid);
                }
                $temp_grades = array();
                $finalgrade = array();
                foreach($studgrades as $item){
                    if($item->id == 'G1'){
                        array_push($finalgrade,$item);
                    }else{
                        if($item->strandid == $strandid){
                            array_push($temp_grades,$item);
                        }
                        if($item->strandid == null){
                            array_push($temp_grades,$item);
                        }
                    }
                }
            
                $studgrades = $temp_grades;
                $studgrades = collect($studgrades)->sortBy('sortid')->values();
                $generalaverage = $finalgrade;
                $grades = $studgrades;

            }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma')
            {
            
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,$sy->strandid,null,$sy->ensectid);
                // return $studgrades;
                $temp_grades = array();
                $generalaverage = array();
                foreach($studgrades as $item){
                    if($item->id == 'G1'){
                        array_push($generalaverage,$item);
                    }else{
                        if($item->strandid == $studinfo->strandid){
                            array_push($temp_grades,$item);
                        }
                        if($item->strandid == null){
                            array_push($temp_grades,$item);
                        }
                    }
                }
                $generalaverage = collect($generalaverage)->where('semid',$sy->semid)->values();
                $grades = collect($temp_grades)->sortBy('sortid')->values();

            }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct'){
                if($sy->syid == 2){
                    $currentSchoolYear = DB::table('sy')->where('id',$sy->syid)->first();
                    Session::put('schoolYear',$currentSchoolYear);
                    $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$studentid,null);
                    
                    
                    if($request->has('action'))
                    {
                        $studentInfo[0]->data = DB::table('studinfo')
                                            ->select('studinfo.*','studinfo.sectionid as ensectid','studinfo.levelid as enlevelid','gradelevel.levelname','acadprogid')
                                            ->where('studinfo.id',$studentid)
                        
                                            ->join('gradelevel','studinfo.levelid','=','gradelevel.id')->get();
                        $studentInfo[0]->count = 1;
                        $studentInfo[0]->data[0]->teacherfirstname = "";
                        $studentInfo[0]->data[0]->teachermiddlename = " ";
                        $studentInfo[0]->data[0]->teacherlastname = "";
                    }
            
                    if($studentInfo[0]->count == 0){
            
                        $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$studentid,null,5);
                        
                        $studentInfo = DB::table('sh_enrolledstud')
                                            ->where('studid',$studentid)
                                            ->where('sh_enrolledstud.semid',1)
                                            ->where('sh_enrolledstud.deleted',0)
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
                                                'sections.sectionname as ensectname'
                                                )
                                            ->join('gradelevel',function($join){
                                                $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                                $join->where('gradelevel.deleted',0);
                                            })
                                            ->join('sections',function($join){
                                                $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                                $join->where('sections.deleted',0);
                                            })
                                             ->join('studinfo',function($join){
                                                $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                                $join->where('gradelevel.deleted',0);
                                            })
                                            ->get();
                                            
                        $studentInfo = array((object)[
                                'data'=>   $studentInfo                             
                            ]);
                                            
                                            
                    }
                    $strand = $studentInfo[0]->data[0]->strandid;
                    $acad = $studentInfo[0]->data[0]->acadprogid;
                    $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($studentInfo[0]->data[0], true, 'sf9',2);    
                           
                    $grades = $gradesv4;
                    // return $grades;
                
                    if(  $acad == 5){
                        foreach($grades as $key=>$item){
                            $checkStrand = DB::table('sh_subjstrand')
                                                ->where('subjid',$item->subjid)
                                                ->where('deleted',0)
                                                ->get();
                            if( count($checkStrand) > 0 ){
                                $check_same_strand = collect($checkStrand)->where('strandid',$strand)->count();
                                if( $check_same_strand == 0){
                                    unset($grades[$key]); 
                                }
                            }
                        }
                    }
            
                  
                    $grades = collect($grades)->unique('subjectcode');
                    
                }else{
                        $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,$sy->strandid,null,$sy->ensectid,true);
                   
                        $temp_grades = array();
                        $generalaverage = array();
                        foreach($studgrades as $item){
                            if($item->id == 'G1'){
                                array_push($generalaverage,$item);
                                array_push($temp_grades,$item);
                            }else{
                                if($item->strandid == $studinfo->strandid){
                                    array_push($temp_grades,$item);
                                }
                                if($item->strandid == null){
                                    array_push($temp_grades,$item);
                                }
                            }
                        }
                    
                        $generalaverage = collect($generalaverage)->where('semid',$sy->semid)->values();
                       
                        $studgrades = $temp_grades;
                        $grades = collect($studgrades)->sortBy('sortid')->values();
                }
                
            }else{
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,$sy->strandid,null,$sy->sectionid);
                // $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,$sy->strandid,null,$sy->ensectid,true);
                $temp_grades = array();
                $generalaverage = array();
                foreach($studgrades as $item){
                    if($item->id == 'G1'){
                        array_push($generalaverage,$item);
                        
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'mcs')
                        {
                            if(count($generalaverage) == 0)
                            {
                                array_push($temp_grades,$item);
                            }
                        }
                    }else{
                        if($item->strandid == $sy->strandid){
                            array_push($temp_grades,$item);
                        }
                        if($item->strandid == null){
                            array_push($temp_grades,$item);
                        }
                    }
                }
               
                $generalaverage = collect($generalaverage)->where('semid',$sy->semid)->values();
                $studgrades = $temp_grades;
                $grades = collect($studgrades)->sortBy('sortid')->values();
                if(DB::table('schoolinfo')->first()->schoolid == '405308') //fmcma
                {
                    if(count($generalaverage)>0)
                    {
                        $generalaverage[0]->actiontaken = strtolower($generalaverage[0]->actiontaken) == 'passed'? 'PROMOTED' : $generalaverage[0]->actiontaken;
                    }
                }
            }   
            // if($sy->levelid == 14)
            // {
            //     return $studgrades;
            // }
            $attendancesummary = DB::table('sf10attendance')
                ->where('sf10attendance.studentid',$studentid)
                ->where('acadprogid','5')
                ->where('sydesc',$sy->sydesc)
                ->where('semid',$sy->semid)
                ->where('deleted','0')
                ->get();
                
            // return $attendancesummary;
            if(count($attendancesummary)==0)
            {
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'bct')
                {
                    $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
                    Session::put('schoolYear', $schoolyear);
                    $attendancesummary = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($schoolyear->id, $sy->levelid);
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                    {                    
                        // return $attendancesummary;
                        foreach( $attendancesummary as $item){
                            $item->type = 1;
                            $item->numdays = $item->days;
                            $sf2_setup = DB::table('sf2_setup')
                                            ->where('month',$item->month)
                                            ->where('year',$item->year)
                                            ->where('sectionid',$sy->sectionid)
                                            ->where('sf2_setup.deleted',0)
                                            ->join('sf2_setupdates',function($join){
                                                $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                                $join->where('sf2_setupdates.deleted',0);
                                            })
                                            ->select('dates')
                                            ->get();
    
                            if(count($sf2_setup) == 0){
    
                                $sf2_setup = DB::table('sf2_setup')
                                            ->where('month',$item->month)
                                            ->where('year',$item->year)
                                            ->where('sectionid',$sy->sectionid)
                                            ->where('sf2_setup.deleted',0)
                                            ->join('sf2_setupdates',function($join){
                                                $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                                $join->where('sf2_setupdates.deleted',0);
                                            })
                                            ->select('dates')
                                            ->get();
    
                            }
    
                            $temp_days = array();
    
                            foreach($sf2_setup as $sf2_setup_item){
                                array_push($temp_days,$sf2_setup_item->dates);
                            }
    
                            $student_attendance = DB::table('studattendance')
                                                    ->where('studid',$studinfo->id)
                                                    ->where('deleted',0)
                                                    ->whereIn('tdate',$temp_days)
                                                    ->select([
                                                        'present',
                                                        'absent',
                                                        'tardy',
                                                        'cc'
                                                    ])
                                                    ->get();
    
                            $student_attendance = collect($student_attendance)->unique('tdate');    
                            $item->present = collect($student_attendance)->where('present',1)->count() + collect($student_attendance)->where('tardy',1)->count() + collect($student_attendance)->where('cc',1)->count();
                            $item->absent = collect($student_attendance)->where('absent',1)->count();
                            $item->numdayspresent = collect($student_attendance)->where('present',1)->count() + collect($student_attendance)->where('tardy',1)->count() + collect($student_attendance)->where('cc',1)->count();
                        }
                    }else{
                    
                        $schoolyear = DB::table('sy')->where('id',$sy->syid)->first();
                        Session::put('schoolYear', $schoolyear);
                        $attendancesummary = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($schoolyear->id, $sy->levelid);
                        // return $schoolyear->id;
                        
                        foreach( $attendancesummary as $item){
                            $item->type = 1;
                            $item->numdays = $item->days;
                            
                            $sf2_setup = DB::table('sf2_setup')
                                ->where('month',$item->month)
                                ->where('year',$item->year)
                                ->where('sectionid',$sy->sectionid)
                                ->where('sf2_setup.deleted',0)
                                ->join('sf2_setupdates',function($join){
                                    $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                    $join->where('sf2_setupdates.deleted',0);
                                })
                                ->select('dates')
                                ->get();
        
                            if(count($sf2_setup) == 0){
        
                                $sf2_setup = DB::table('sf2_setup')
                                            ->where('month',$item->month)
                                            ->where('year',$item->year)
                                            ->where('sectionid',$sy->sectionid)
                                            ->where('sf2_setup.deleted',0)
                                            ->join('sf2_setupdates',function($join){
                                                $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                                $join->where('sf2_setupdates.deleted',0);
                                            })
                                            ->select('dates')
                                            ->get();
        
                            }
        
                            $temp_days = array();
        
                            foreach($sf2_setup as $sf2_setup_item){
                            array_push($temp_days,$sf2_setup_item->dates);
                            }
        
                            $student_attendance = DB::table('studattendance')
                                                ->where('studid',$studinfo->id)
                                                ->where('deleted',0)
                                                ->whereIn('tdate',$temp_days)
                                                // ->where('syid',$syid)
                                                ->distinct('tdate')
                                                ->distinct()
                                                // ->select([
                                                //     'present',
                                                //     'absent',
                                                //     'tardy',
                                                //     'cc',
                                                //     'tdate'
                                                // ])
                                                ->get();
        
                            $student_attendance = collect($student_attendance)->unique('tdate')->values();
        
                            $item->present = collect($student_attendance)->where('present',1)->count() + collect($student_attendance)->where('tardy',1)->count() + collect($student_attendance)->where('cc',1)->count() + (collect($student_attendance)->where('presentam',1)->count() * 0.5) + (collect($student_attendance)->where('presentpm',1)->count() * 0.5) + (collect($student_attendance)->where('absentam',1)->count() * 0.5) + (collect($student_attendance)->where('absentpm',1)->count() * 0.5) + collect($student_attendance)->where('lateam',1)->count()  + collect($student_attendance)->where('latepm',1)->count() + collect($student_attendance)->where('ccam',1)->count() + collect($student_attendance)->where('ccpm',1)->count()  ;
                            $item->present = $item->present > $item->numdays ? $item->numdays : $item->present;
                            $item->absent = collect($student_attendance)->where('absent',1)->count() + (collect($student_attendance)->where('absentam',1)->count() * 0.5) + (collect($student_attendance)->where('absentpm',1)->count() * 0.5);
                            $item->numdayspresent = $item->present;
                            $item->numdaysabsent = $item->absent;
                            $item->monthstr = substr($item->monthdesc, 0, 3);
                            $item->monthdesc = $item->monthstr;
                        }
                        // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                        // {
                            
                            $attendancesummary = collect($attendancesummary)->toArray();
                            $attendancesummary = array_chunk($attendancesummary, 6);
                            if(!$request->has('export'))
                            {
                                // $attendancesummary = collect($attendancesummary)->toArray();
                                // $attendancesummary = array_chunk($attendancesummary, 6);
                                if($sy->semid == 1)
                                {
                                    $attendancesummary = $attendancesummary[0] ?? array();
                                }else{
                                    $attendancesummary = $attendancesummary[1] ?? array();
                                }
                            }
                        // }
                    }
                }
            }else{
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                {
                    $attendancesummary = collect($attendancesummary)->toArray();
                    $attendancesummary = array_chunk($attendancesummary, 6);
                    if(!$request->has('export'))
                    {
                        // $attendancesummary = collect($attendancesummary)->toArray();
                        // $attendancesummary = array_chunk($attendancesummary, 6);
                        if($sy->semid == 1)
                        {
                            $attendancesummary = $attendancesummary[0] ?? array();
                        }else{
                            $attendancesummary = $attendancesummary[1] ?? array();
                        }
                    }
                }
            }
            
            $subjidarray = array(85,
                            86,
                            87,
                            100,
                            102,
                            90,
                            91,
                            92,
                            93,
                            101);

            $grades = collect($grades)->where('semid', $sy->semid)->values();
            if(count($grades)>0)
            {
                foreach($grades as $subject)
                {                                
                    try{
                    $subjectsjaesfinalrating = $subject->finalrating;
                    }catch(\Exception $error)
                    {
                        // return collect($sy);
                        // return collect($subject)
                        // ;
                    }
                    
                    if(!isset($subject->subjdesc))
                    {
                        $subject->subjdesc = $subject->subjectcode;
                    }
                    if(!collect($subject)->has('subjdesc'))
                    {
                        $subject->subjdesc = $subject->subjectcode;
                    }
                    $subject->q1stat = 0;
                    $subject->q2stat = 0;
                    $complete        = 0;

                    if($subject->quarter1 != null)
                    {
                        $subject->q1 = $subject->quarter1;
                    }
                    if($subject->quarter2 != null)
                    {
                        $subject->q2 = $subject->quarter2;
                    }
                    if($subject->quarter3 != null)
                    {
                        $subject->q1 = $subject->quarter3;
                    }
                    if($subject->quarter4 != null)
                    {
                        $subject->q2 = $subject->quarter4;
                    }
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                    {
                        if($sy->semid == 2 && $sy->levelid == 15)
                        {
                            $subject->q2 = null;
                        }
                    }
                    if(strtolower($schoolinfo->abbreviation) != 'bct')
                    {
                        if($sy->semid == 2)
                        {
                            $subject->q1 = $subject->quarter3;
                            $subject->q2 = $subject->quarter4;
                        }
                    }
                    $chekifaddinautoexist = DB::table('sf10grades_addinauto')
                            ->where('studid',$studinfo->id)
                            ->where('subjid',$subject->subjid)
                            ->where('levelid',$sy->levelid)
                            ->where('syid',$sy->syid)
                            ->where('semid',$sy->semid)
                            ->where('deleted',0)
                            ->get();
                            
                    if(collect($chekifaddinautoexist)->where('quarter',1)->count() > 0)
                    {
                        $subject->q1stat = 2;
                        $subject->q1    = collect($chekifaddinautoexist)->where('quarter',1)->first()->grade;
                        $complete+=1;;
                    }
                    if(collect($chekifaddinautoexist)->where('quarter',2)->count() > 0)
                    {
                        $subject->q2stat = 2;
                        $subject->q2    = collect($chekifaddinautoexist)->where('quarter',2)->first()->grade;
                        $complete+=1;;
                    }

                    try{
                        if($subject->q1 == 0)
                        {
                            $subject->q1 = null;
                            $subject->q1stat = 1;
                        }else{
                            $complete+=1;;
                        }
                        if($subject->q2 == 0)
                        {
                            $subject->q2 = null;
                            $subject->q2stat = 1;
                        }else{
                            $complete+=1;;
                        }
                        if($subject->q1 == null)
                        {
                            $subject->q1stat = 1;
                        }
                        if($subject->q2 == null)
                        {
                            $subject->q2stat = 1;
                        }

                    }catch(\Exception $error)
                    {
                        if(!isset($subject->q1))
                        {
                            $subject->q1 = null;
                            $subject->q1stat = 1;
                        }
                        if(!isset($subject->q2))
                        {
                            $subject->q2 = null;
                            $subject->q2stat = 1;
                        }
                        if(!isset($subject->q3))
                        {
                            $subject->q3 = null;
                            $subject->q1stat = 1;
                        }
                        if(!isset($subject->q4))
                        {
                            $subject->q4 = null;
                            $subject->q2stat = 1;
                        }
                        if(!isset($subject->actiontaken ))
                        {
                            $subject->actiontaken  = null;
                        }
                        if(!isset($subject->subjcode))
                        {
                            $subject->subjcode  = $subject->sc;
                        }
                        // return collect($subject);
                    }
                    if($complete < 2)
                    {
                        $qg = null;
                        $remarks = null;
                    }else{
                        $qg = ($subject->q1 + $subject->q2) / 2;
                        if($qg>75){
        
                            $remarks = "PASSED";
        
                        }elseif($qg == null){
        
                            $remarks = null;
        
                        }else{
                            $remarks = "FAILED";
                        }
                        
                        if($qg == 0)
                        {
                            $qg = null;
                            $remarks = null;
                        }
                    }
                    
                    $subjcode = DB::table('sh_subjects')
                        ->where('id', $subject->subjid)
                        ->first();

                    $sortsubjcode = 0;
                    
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                    {
                        
                        if($subject->type == 1)
                        {
                            $subjcode = 'CORE';
                        }
                        elseif($subject->type == 3)
                        {
                            $sortsubjcode = 1;
                            $subjcode = 'APPLIED';
                        }
                        elseif($subject->type == 2)
                        {
                            $sortsubjcode = 2;
                            $subjcode = 'SPECIALIZED';
                        }else{
                            $sortsubjcode = 3;
                            $subjcode = 'Other Subject';
                        }
                    }else{
                        if($subjcode)
                        {
                            // return collect($subjcode);
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                            {
                                // return collect($subject)
                                if(in_array($subject->subjid, $subjidarray))
                                {
                                    $subjcode = 'Other Subject';
                                }else{
                                    if($subjcode->type == 1)
                                    {
                                        $subjcode = 'CORE';
                                    }
                                    elseif($subjcode->type == 3)
                                    {
                                        $sortsubjcode = 1;
                                        $subjcode = 'APPLIED';
                                    }
                                    elseif($subjcode->type == 2)
                                    {
                                        $sortsubjcode = 2;
                                        $subjcode = 'SPECIALIZED';
                                    }else{
                                        $sortsubjcode = 3;
                                        $subjcode = 'Other Subject';
                                    }
                                }
                            }
                            // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'faa' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'){
                            //     if($subjcode->type == 1)
                            //     {
                            //         $subjcode = 'CORE';
                            //     }
                            //     elseif($subjcode->type == 2)
                            //     {
                            //         $subjcode = 'SPECIALIZED';
                            //     }
                            //     elseif($subjcode->type == 3)
                            //     {
                            //         $subjcode = 'APPLIED';
                            //     }else{
                            //         $subjcode = 'Other Subject';
                            //     }
                                
                            // }
                            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc'){
                                $subjcode = $subjcode->subjcode;                            
                            }else{
                                if($subjcode->type == 1)
                                {
                                    $subjcode = 'CORE';
                                }
                                elseif($subjcode->type == 2)
                                {
                                    $sortsubjcode = 2;
                                    $subjcode = 'SPECIALIZED';
                                }
                                elseif($subjcode->type == 3)
                                {
                                    $sortsubjcode = 1;
                                    $subjcode = 'APPLIED';
                                }else{
                                    $sortsubjcode = 3;
                                    $subjcode = 'Other Subject';
                                }
                                // if($subjcode->type == 1)
                                // {
                                //     $subjcode = 'CORE';
                                // }
                                // elseif($subjcode->type == 2)
                                // {
                                //     $subjcode = 'APPLIED';
                                // }
                                // elseif($subjcode->type == 3)
                                // {
                                //     $subjcode = 'SPECIALIZED';
                                // }else{
                                //     $subjcode = 'Other Subject';
                                // }
                            }
                        }else{
                            $subjcode = null;
                        }
                    }
                    
                    if($subject->q1 != null && $subject->q2 != null)
                    {
                        $subject->finalrating = number_format(($subject->q1+$subject->q2)/2);
                    }else{                        
                        $subject->finalrating = null;
                    }

                    if($subject->finalrating == null)
                    {
                        $subject->remarks = null;
                    }else{
                        if($subject->finalrating < 75)
                        {
                            $subject->remarks = 'FAILED';
                        }else{
                            $subject->remarks = 'PASSED';
                        }
                    }                    
                    
                    if(strtolower($schoolinfo->abbreviation) == 'bct')
                    {
                        if(isset($subject->sc)){
                            $subject->subjcode = $subject->sc;
                        }else{
                            if(isset($subject->subjectcode))
                            {
                                $subject->sc = $subject->subjectcode;
                                $subject->subjcode = $subject->subjectcode;
                            }
                        }
                        
                    }else{
                        if(isset($subject->sc)){
                            $subject->subjcode = $subjcode;
                        }else{
                            try{
                             $subject->sc = $subject->subjectcode;
                            }catch(\Exception $error)
                            {
                                $subject->subjcode = $subjcode;
                            }
                             $subject->subjcode = $subjcode;
                        }                        
                    }
                    $subject->sortsubjcode = $sortsubjcode;
                    $subject->semid = $sy->semid;
                          
                    try{
                        if(strpos(strtolower($subject->subjdesc),'physical edu') !== false)
                        // if (strtolower($cell->getValue()) == strtolower($searchValue)) 
                        {
                            $subjectsjaesfinalrating = ($subjectsjaesfinalrating*0.25);
                        }
                        $subject->subjectsjaesfinalrating = $subjectsjaesfinalrating;
                    }catch(\Exception $error)
                    {
                        // return collect($sy);
                        // return collect($subject)
                        // ;
                    }
                    
                }                
                $finalrating = number_format(collect($grades)->sum('finalrating')/count($grades));

                if($finalrating < 75)
                {
                    $remarks = 'FAILED';
                }else{
                    $remarks = 'PASSED';
                }

                // if(count($generalaverage) == 0)
                // {
                //     $grades = collect($grades)->add(
                //         (object)[
                        
                //             'subjdesc'      => 'General Average',
                //             'subjid'        => null,
                //             'q1'            => null,
                //             'q2'            => null,
                //             'q3'            => null,
                //             'q4'            => null,
                //             'finalrating'   => $finalrating,
                //             'remarks'       => $remarks,
                //             'subjcode'      => null,
                //             'semid'      => $sy->semid
                //         ]
                        
                //     )->all();
                // }
                
            }
            $teachername = '';
            $principalname ='';

            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hc babak')
            {
                $getTeacher = Db::table('sectiondetail')
                    ->select(
                        'teacher.title',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.lastname',
                        'teacher.suffix'
                        )
                    ->join('teacher','sectiondetail.teacherid','teacher.id')
                    ->where('sectiondetail.sectionid',$sy->sectionid)
                    ->where('sectiondetail.syid',$sy->syid)
                    ->where('sectiondetail.semid',$sy->semid)
                    ->where('sectiondetail.deleted','0')
                    ->first();
    
                if(!$getTeacher)
                {
    
                    $getTeacher = Db::table('sectiondetail')
                        ->select(
                            'teacher.title',
                            'teacher.firstname',
                            'teacher.middlename',
                            'teacher.lastname',
                            'teacher.suffix'
                            )
                        ->join('teacher','sectiondetail.teacherid','teacher.id')
                        ->where('sectiondetail.sectionid',$sy->sectionid)
                        ->where('sectiondetail.syid',$sy->syid)
                        // ->where('sectiondetail.semid',$sy->semid)
                        ->where('sectiondetail.deleted','0')
                        ->first();
    
                        
    
                }
                if($getTeacher) 
                {
                    if($getTeacher->title!=null)
                    {
                        $teachername.=$getTeacher->title.' ';
                    }
                    if($getTeacher->firstname!=null)
                    {
                        $teachername.=$getTeacher->firstname.' ';
                    }
                    if($getTeacher->middlename!=null)
                    {
                        $teachername.=$getTeacher->middlename[0].'. ';
                    }
                    if($getTeacher->lastname!=null)
                    {
                        $teachername.=$getTeacher->lastname.' ';
                    }
                    if($getTeacher->suffix!=null)
                    {
                        $teachername.=$getTeacher->suffix.' ';
                    }
                    // $teachername = substr($teachername,0,-2);
                }
    
                // return $acadprogid;
                $principal = Db::table('academicprogram')
                    ->select(
                        'teacher.title',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.lastname',
                        'teacher.suffix'
                        )
                    ->leftJoin('teacher','academicprogram.principalid','=','teacher.id')
                    ->where('academicprogram.id', $acadprogid)
                    ->first();
                    
                if($principal)
                {
                    if($principal->title!=null)
                    {
                        $principalname.=$principal->title.' ';
                    }
                    if($principal->firstname!=null)
                    {
                        $principalname.=$principal->firstname.' ';
                    }
                    if($principal->middlename!=null)
                    {
                        $principalname.=$principal->middlename[0].'. ';
                    }
                    if($principal->lastname!=null)
                    {
                        $principalname.=$principal->lastname.' ';
                    }
                    if($principal->suffix!=null)
                    {
                        $principalname.=$principal->suffix.' ';
                    }
            
                }
            }

            $subjaddedforauto     = DB::table('sf10grades_subjauto')
                                    ->where('studid',$studentid)
                                    ->where('syid',$sy->syid)
                                    ->where('semid',$sy->semid)
                                    ->where('levelid',$sy->levelid)
                                    ->where('deleted','0')
                                    ->get();
            
            $displayaccomplished = $sy->strandname;
            
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
            {
                $recordsincharge = 'MR. ROMEO A. BALASTA';
            }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
            {
                $recordsincharge = 'DOLLY JOY V. VALENZUELA, MAED - PRINCIPAL';
            }
            // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
            // {
            //     $recordsincharge = null;
            // }
            else{
                $recordsincharge = null;
                // $recordsincharge = DB::table('schoolinfo')->first()->schoolrecordsincharge ?? (DB::table('schoolinfo')->first()->schoolrecordsincharge != null ? DB::table('schoolinfo')->first()->schoolrecordsincharge : auth()->user()->name);
            }
            
            $datechecked = null;
            if($sy->levelid == 14)
            {
                if($sy->semid == 1)
                {
                    $datechecked = (DB::table('schoolinfo')->first()->sf101datechecked != null) ? date('M d, Y', strtotime(DB::table('schoolinfo')->first()->sf101datechecked)) : null;
                }
                elseif($sy->semid == 2)
                {
                    $datechecked = (DB::table('schoolinfo')->first()->sf102datechecked != null) ? date('M d, Y', strtotime(DB::table('schoolinfo')->first()->sf102datechecked)) : null;
                }
            }
            elseif($sy->levelid == 15)
            {
                if($sy->semid == 1)
                {
                    $datechecked = (DB::table('schoolinfo')->first()->sf103datechecked != null) ? date('M d, Y', strtotime(DB::table('schoolinfo')->first()->sf103datechecked)) : null;
                }
                elseif($sy->semid == 2)
                {
                    $datechecked = (DB::table('schoolinfo')->first()->sf104datechecked != null) ? date('M d, Y', strtotime(DB::table('schoolinfo')->first()->sf104datechecked)) : null;
                }
            }
            if(count($grades)>0)
            {
                
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'lhs')
                {
                    $grades = collect($grades)->sortBy('sortid')->sortBy('sortsubjcode')->values()->all();
                }else{
                    $grades = collect($grades)->sortBy('sortid')->values()->all();

                }
                array_push($autorecords, (object) array(
                    'id'                => null,
                    'syid'              => $sy->syid,
                    'sydesc'            => $sy->sydesc,
                    'sdate'             => $sy->sdate,
                    'edate'             => $sy->edate,
                    'semid'             => $sy->semid,
                    'levelid'           => $sy->levelid,
                    'levelname'         => $sy->levelname,
                    'trackid'           => $sy->trackid,
                    'trackname'         => $sy->trackname,
                    'strandid'          => $sy->strandid,
                    'strandname'        => $sy->strandname,
                    'strandcode'        => $sy->strandcode,
                    'sectionid'         => $sy->sectionid,
                    'sectionname'       => $sy->section,
                    'teachername'       => $teachername,
                    'schoolid'          => $schoolinfo->schoolid,
                    'schoolname'        => $schoolinfo->schoolname,
                    'schooladdress'     => $schoolinfo->address,
                    'schooldistrict'    => $schoolinfo->districttext != null ? $schoolinfo->districttext : $schoolinfo->district,
                    'schooldivision'    => $schoolinfo->divisiontext != null ? $schoolinfo->divisiontext : $schoolinfo->division,
                    'schoolregion'      => $schoolinfo->regiontext != null ? $schoolinfo->regiontext : $schoolinfo->region,
                    'type'              => 1,
                    'remedials'         => array(),
                    'grades'            => $grades,
                    'generalaverage'    => $generalaverage,
                    'subjaddedforauto'  => $subjaddedforauto,
                    'attendance'        => $attendancesummary,
                    'remarks'           => null,
                    'recordincharge'    => $recordsincharge,
                    'principalname'     => $principalname,
                    'datechecked'       => $datechecked
                ));
            }

        }
        // return $autorecords;
        
        if(count($schoolyears) == 0)
        {
            $studinfo->semid = DB::table('semester')
                ->where('isactive','1')
                ->first()->id;
        }
        $manualrecords = DB::table('sf10')
            ->select('sf10.*','gradelevel.levelname')
            ->join('gradelevel','sf10.levelid','=','gradelevel.id')
            ->where('sf10.studid', $studentid)
            ->where('sf10.acadprogid', $acadprogid)
            ->where('sf10.deleted','0')
            ->get();
            

        if(count($manualrecords)>0)
        {
            foreach($manualrecords as $manualrecord)
            {
                $manualrecord->type = 2;

                $grades = DB::table('sf10grades_senior')
                        ->where('headerid', $manualrecord->id)
                        ->where('deleted','0')
                        ->get();
                        
                if(count($grades)>0)
                {
                    foreach($grades as $grade)
                    {
                        $grade->q1stat = 0;
                        $grade->q2stat = 0;
                        
                        if($grade->q1 == 0)
                        {
                            $grade->q1 = null;
                        }
                        if($grade->q2 == 0)
                        {
                            $grade->q2 = null;
                        }
                        $grade->semid = $manualrecord->semid;
                         $grade->semid = $manualrecord->semid;
                    }
                    
                     $grades[0]->semid = $manualrecord->semid;
                }
                $remedialclasses = DB::table('sf10remedial_senior')
                    ->where('semid',$manualrecord->semid)
                    ->where('studid', $studentid)
                    ->where('levelid', $manualrecord->levelid)     
                    ->where('sydesc', $manualrecord->sydesc)     
                    ->where('deleted','0')
                    ->get();

                // return $remedialclasses;
                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                // {
                    $attendance = DB::table('sf10attendance')
                    ->where('sf10attendance.studentid',$studentid)
                        ->where('acadprogid','5')
                        ->where('sydesc',$manualrecord->sydesc)
                        ->where('deleted','0')
                        ->get();

                // }else{
                //     $attendance = array();
                // }
    
                $manualrecord->grades       = $grades;
                $manualrecord->generalaverage       = array();
                $manualrecord->subjaddedforauto       = array();
                $manualrecord->attendance   = $attendance;
                $manualrecord->remedials    = $remedialclasses;
                $manualrecord->principalname    = null;
            }
        }
        // return $autorecords;
        $records = collect();
        $records = $records->merge($autorecords);
        $records = $records->merge($manualrecords);
        
        if(count($records)>0)
        {
            foreach($records as $record)
            {
                $record->withdata = 1;
                $record->sortid = 0;

                if(preg_replace('/\D+/', '', $record->levelname) == 11)
                {
                    
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                    {
                        if($record->semid == 1)
                        {
                            $record->sortid = 1;
                            $record->noofgrades = count(collect($record->grades)->where('semid',1)->where('subjdesc','!=','General Average')) + count($record->subjaddedforauto);
                        }else{
                            $record->sortid = 2;
                            $record->noofgrades = count(collect($record->grades)->where('semid',2)->where('subjdesc','!=','General Average')) + count($record->subjaddedforauto);
                        }
                    }else{
                        if($record->semid == 1)
                        {
                            $record->sortid = 1;
                            $record->noofgrades = count(collect($record->grades)->where('semid',1)->where('subjdesc','!=','General Average'));
                        }else{
                            $record->sortid = 2;
                            $record->noofgrades = count(collect($record->grades)->where('semid',2)->where('subjdesc','!=','General Average'));
                        }
                    }
                }
                elseif(preg_replace('/\D+/', '', $record->levelname) == 12)
                {
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                    {
                        if($record->semid == 1)
                        {
                            $record->sortid = 3;
                            $record->noofgrades = count(collect($record->grades)->where('semid',1)->where('subjdesc','!=','General Average')) + count($record->subjaddedforauto);
                        }else{
                            $record->sortid = 4;
                            $record->noofgrades = count(collect($record->grades)->where('semid',2)->where('subjdesc','!=','General Average')) + count($record->subjaddedforauto);
                        }
                    }else{
                        if($record->semid == 1)
                        {
                            $record->sortid = 3;
                            $record->noofgrades = count(collect($record->grades)->where('semid',1)->where('subjdesc','!=','General Average'));
                        }else{
                            $record->sortid = 4;
                            $record->noofgrades = count(collect($record->grades)->where('semid',2)->where('subjdesc','!=','General Average'));
                        }
                    }
                }
            }
        }

        $withnodata = array();
        for($x = 1; $x <= 4; $x++)
        {
            if(collect($records)->where('sortid',$x)->count() == 0)
            {
                if($x == 1)
                {
                    $nolevelname = 'GRADE 11';
                    $nolevelid = 14;
                    $nosemester = 1;
                }
                if($x == 2)
                {
                    $nolevelname = 'GRADE 11';
                    $nolevelid = 14;
                    $nosemester = 2;
                }
                if($x == 3)
                {
                    $nolevelname = 'GRADE 12';
                    $nolevelid = 15;
                    $nosemester = 1;
                }
                if($x == 4)
                {
                    $nolevelname = 'GRADE 12';
                    $nolevelid = 15;
                    $nosemester = 2;
                }
                array_push($withnodata, (object)array(
                   
                    'id'                => null,
                    'syid'              => null,
                    'sydesc'            => null,
                    'semid'             => $nosemester,
                    'levelid'           => $nolevelid,
                    'levelname'         => $nolevelname,
                    'trackid'           => null,
                    'trackname'         => null,
                    'strandid'          => null,
                    'strandname'        => null,
                    'strandcode'        => null,
                    'sectionid'         => null,
                    'sectionname'       => null,
                    'teachername'       => null,
                    'schoolid'          => null,
                    'schoolname'        => null,
                    'schooladdress'     => null,
                    'schooldistrict'    => null,
                    'schooldivision'    => null,
                    'schoolregion'      => null,
                    'type'              => 1,
                    'remedials'         => array(),
                    'grades'            => array(),
                    'generalaverage'    => array(),
                    'attendance'        => array(),
                    'subjaddedforauto'  => array(),
                    'remarks'           => null,
                    'recordincharge'    => null,
                    'principalname'    => null,
                    'datechecked'       => null,
                    'sortid'            => $x,
                    'withdata'          => 0,
                ));
            }
        }
        if(count($records)>0){
            foreach($records as $eachrecord)
            {
                $eachrecord->sort = $eachrecord->levelid.' '.$eachrecord->sydesc.' '.$eachrecord->semid;
            }
        }
        $maxgradecount = collect($records)->pluck('noofgrades')->max();

        if($maxgradecount == 0)
        {
            $maxgradecount = 12;
        }
        $footer = DB::table('sf10_footer_senior')
            ->where('studid', $studentid)
            ->where('deleted','0')
            ->first();
            
        if(!$footer)
        {
            $footer = (object)array(
                'strandaccomplished'        =>  $displayaccomplished,
                'shsgenave'                 =>  null,
                'honorsreceived'            =>  null,
                'shsgraduationdate'         =>  null,
                'shsgraduationdateshow'     =>  null,
                'datecertified'             =>  null,
                'datecertifiedshow'         =>  null,
                'copyforupper'              =>  null,
                'copyforlower'              =>  null,
                'registrar'              =>  null
            );
        }else{
            if($footer->strandaccomplished == null)
            {
                $footer->strandaccomplished = $displayaccomplished;
            }
            if($footer->shsgraduationdate != null)
            {
                $footer->shsgraduationdate = date('m/d/Y', strtotime($footer->shsgraduationdate));
                $footer->shsgraduationdateshow = date('Y-m-d', strtotime($footer->shsgraduationdate));
            }else{
                $footer->shsgraduationdateshow = null;
            }
            if($footer->datecertified != null)
            {
                $footer->datecertified = date('m/d/Y', strtotime($footer->datecertified));
                $footer->datecertifiedshow = date('Y-m-d', strtotime($footer->datecertified));
            }else{
                $footer->datecertifiedshow = null;
            }
        }
        
        $admissiondate = DB::table('admissiondate')
            ->select('admissiondate.*','gradelevel.levelname','sy.sydesc')
            ->join('sy','admissiondate.syid','=','sy.id')
            ->join('gradelevel','admissiondate.levelid','=','gradelevel.id')
            ->where('gradelevel.deleted','0')
            ->where('admissiondate.deleted','0')
            ->where('sydesc',collect($records)->where('sydesc','!=', null)->first()->sydesc ?? '')
            ->where('levelname',collect($records)->where('levelname','!=', null)->first()->levelname ?? '')
            ->first()->admissiondate ?? null;

        $eligibility = DB::table('sf10eligibility_senior')
            ->where('studid', $studentid)
            ->where('deleted','0')
            ->first();

        if(!$eligibility)
        {
            $eligibility = (object)array(
                'completerhs'       =>  0,
                'genavehs'          =>  null,
                'completerjh'       =>  0,
                'genavejh'          =>  null,
                'graduationdate'    =>  null,
                'schoolname'        =>  null,
                'schooladdress'     =>  null,
                'peptpasser'        =>  0,
                'peptrating'        =>  null,
                'alspasser'         =>  0,
                'alsrating'         =>  null,
                'examdate'          =>  null,
                'courseschool'          =>  null,
                'courseyear'          =>  null,
                'coursegenave'          =>  null,
                'centername'        =>  null,
                'shsadmissiondate'        =>  $admissiondate,
                'others'            =>  null
            );
        }else{
            if($eligibility->shsadmissiondate == null)
            {
                $eligibility->shsadmissiondate = $admissiondate;
            }
        }
        // return collect($eligibility);
        $eachsemsignatories = DB::table('sf10bylevelsign')
            ->where('studid',$studentid)
            ->where('semid','!=',null)            
            ->where('deleted','0')
            ->get();

            // return $gradelevels;
        if($request->has('export'))
        {
            $records = collect($records)->merge($withnodata);
            if(count($records)>0){
                foreach($records as $eachrecord)
                {
                    $eachrecord->sort = $eachrecord->levelid.' '.$eachrecord->sydesc.' '.$eachrecord->semid;
                }
            }
            
            if($request->get('exporttype') == 'pdf')
            {
                $records = collect($records)->sortBy('sort')->values();
                if(count($gradelevels)>0)
                {
                    foreach($gradelevels as $gradelevel)
                    {
                        $gradelevel->records = collect($records)->where('levelid', $gradelevel->id)->values();
                    }
                }
                // return $records;
                $format = $request->get('format');
                $template = 'registrar/forms/deped/form10_shs';
                if($request->has('papersize'))
                {
                    $papersize = $request->get('papersize');
                }else{
                    $papersize = null;
                }
                
                if($request->has('format'))
                {
                    if($format == 'deped')
                    {
                        $records = collect($records)->sortBy('sort')->toArray();
                        $records = array_chunk($records, 2);
                        $template = 'registrar/forms/deped/form10_shs';
                    }elseif($format == 'deped-2')//old
                    {
                        $records = collect($records)->sortBy('sort')->toArray();
                        $records = array_chunk($records, 2);
                        $template = 'registrar/pdf/pdf_schoolform10_senior';
                    }elseif($format == 'depedspr')
                    {
                        $records = collect($records)->sortBy('sort')->toArray();
                        $records = array_chunk($records, 2);
                        $template = 'registrar/forms/deped/form10_shsspr';
                    }elseif($format == 'school'){
                        $template = 'registrar/pdf/pdf_schoolform10_senior';
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                        {
                            $records = collect( collect($records)->sortBy('sort')->values()->all())->toArray();
                            $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_seniorhccsi_spr',compact('eligibility','studinfo','records','maxgradecount','footer','format','gradelevels','eachsemsignatories','papersize')); 
                            return $pdf->stream('Student Permanent Record - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                        }else{
                            $records =  collect($records)->sortBy('sort')->values()->toArray();
                            // return $records;
                            // $records =  collect($records)->sortBy('sort')->all()->toArray();
                            // return $records;
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                            {
                                $records = array_chunk($records, 2);
                                $template = 'registrar/pdf/pdf_schoolform10_seniorlhs';
                            }
                            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                            {
                                $records = array_chunk($records, 2);
                                $template = 'registrar/pdf/pdf_schoolform10_seniorbct';
                            }
                            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                            {
                                $records = array_chunk($records, 2);
                                $template = 'registrar/pdf/pdf_schoolform10_seniorsjaes';
                            }
                            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                            {
                                $records = array_chunk($records, 2);
                                $template = 'registrar/pdf/pdf_schoolform10_seniormcs';
                            }
                            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak')
                            {
                                $records = array_chunk($records, 2);
                                $template = 'registrar/pdf/pdf_schoolform10_seniorhcbabak';
                            }
                            elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mhssi')
                            {
                                $records = array_chunk($records, 2);
                                $template = 'registrar/pdf/pdf_schoolform10_seniormhssi';
                            }else{
                                $records = array_chunk($records, 2);
                            }
                        }
                    }
                }else{
                    $records =  collect($records)->sortBy('sort')->toArray();
                    $records = array_chunk($records, 2);
                }
				$gradelevels[1]->records = collect($gradelevels[1]->records)->sortBy('semid')->values();
                $gradelevels[0]->records = collect($gradelevels[0]->records)->sortBy('semid')->values();
                $records[1] = collect($records[1])->sortBy('semid')->values();
                // return $records;
                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                // {
                //     $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                //     $records = array_chunk($records, 2);
                //     $template = 'registrar/pdf/pdf_schoolform10_seniorlhs';
                // }
                // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                // {
                //     $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                //     $records = array_chunk($records, 2);
                //     $template = 'registrar/pdf/pdf_schoolform10_seniorbct';
                // }
                // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                // {
                //     $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                //     $records = array_chunk($records, 2);
                //     $template = 'registrar/pdf/pdf_schoolform10_seniorsjaes';
                // }
                // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                // {
                //     $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                //     $records = array_chunk($records, 2);
                //     $template = 'registrar/pdf/pdf_schoolform10_seniormcs';
                // }
                // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak')
                // {
                //     $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                //     $records = array_chunk($records, 2);
                //     if($format == 'school')
                //     {
                //         $template = 'registrar/pdf/pdf_schoolform10_seniorhcbabak';
                //     }
                // }
                // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                // {
                //     if($request->get('format') == 'deped')
                //     {
                //         $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                //         $records = array_chunk($records, 2);
                        
                //         $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_senior',compact('eligibility','studinfo','records','maxgradecount','footer','format','gradelevels')); 
                //         return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                //     }else{
                //         $records = collect($records->sortBy('sydesc')->sortBy('sortid')->values()->all())->toArray();
                        
                //         $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_seniorhccsi_spr',compact('eligibility','studinfo','records','maxgradecount','footer','format','gradelevels')); 
                //         return $pdf->stream('Student Permanent Record - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
                //     }  
                // }
                // else{
                    // $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                    // $records = array_chunk($records, 2);
                    // if($request->has('format'))
                    // {
                    //     if($format == 'deped')
                    //     {
                    //         $template = 'registrar/forms/deped/form10_shs';
                    //     }elseif($format == 'depedspr')
                    //     {
                    //         $template = 'registrar/forms/deped/form10_shsspr';
                    //     }else{
                    //         $template = 'registrar/pdf/pdf_schoolform10_senior';
                    //     }
                    // }else{
                    //     $template = 'registrar/forms/deped/form10_shsspr';
                    // }
                // }
                
                // return $records[1][1]->grades;
                // return $records[1][1]->attendance;;
                // return collect($studinfo);
                // return $gradelevels;
                // return $gradelevels;
                // return $eachsemsignatories;
                $layout = $request->get('layout');
                $pdf = PDF::loadview($template,compact('eligibility','studinfo','records','maxgradecount','footer','format','gradelevels','eachsemsignatories','layout','papersize')); 
                $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
                return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
            }else{
                $records = $records->sortBy('sydesc')->sortBy('sortid')->toArray();
                $records = array_chunk($records, 2);
                $inputFileType = 'Xlsx';
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                {
                    $inputFileName = base_path().'/public/excelformats/hcb/sf10_shs.xlsx';
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                {
                    $inputFileName = base_path().'/public/excelformats/lhs/sf10_shs.xlsx';
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                {
                    $inputFileName = base_path().'/public/excelformats/mcs/sf10_shs.xlsx';
                }else{
                    if(DB::table('schoolinfo')->first()->schoolid == '405308')
                    {
                        $inputFileName = base_path().'/public/excelformats/fmcma/sf10_shs.xlsx';
                    }else{
                        $inputFileName = base_path().'/public/excelformats/sf10_shs.xlsx';
                    }
                }
                $sheetname = 'front';

                /**  Create a new Reader of the type defined in $inputFileType  **/
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                /**  Advise the Reader of which WorkSheets we want to load  **/
                $reader->setLoadAllSheets();
                /**  Load $inputFileName to a Spreadsheet Object  **/
                $spreadsheet = $reader->load($inputFileName);
                
                function getNameFromNumber($num) {
                    $numeric = ($num - 1) % 26;
                    $letter = chr(65 + $numeric);
                    $num2 = intval(($num - 1) / 26);
                    if ($num2 > 0) {
                        return getNameFromNumber($num2) . $letter;
                    } else {
                        return $letter;
                    }
                }
                // FIRST PAGE
                $sheet = $spreadsheet->getSheet(0);
                
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                {
                    $registrar = DB::table('teacher')   
                        ->where('userid', auth()->user()->id)
                        ->select('title','firstname','middlename','lastname','suffix')
                        ->first();
                        
                    $registrarname = '';
                    if($registrar)
                    {
                        $registrarname.=$registrar->title;
                        $registrarname.=$registrar->firstname.' ';
                        if($registrar->middlename != null)
                        {
                            $registrarname.=$registrar->middlename[0].'. ';
                        }
                        $registrarname.=$registrar->lastname.' ';
                        $registrarname.=$registrar->suffix.', Registrar';
                    }

                    $sheet->setCellValue('C8', $studinfo->lastname);
                    $sheet->setCellValue('P8', $studinfo->firstname);
                    $sheet->setCellValue('Z8', $studinfo->middlename);

                    $sheet->setCellValue('B9', $studinfo->lrn);
                    $sheet->getStyle('B9')->getNumberFormat()->setFormatCode('0');
                    $sheet->setCellValue('L9', date('m/d/Y', strtotime($studinfo->dob)));
                    $sheet->setCellValue('Q9', $studinfo->gender);

                    if($eligibility->completerhs == 1)
                    {
                        $sheet->setCellValue('A12', '/');
                    }
                    $sheet->setCellValue('I12', $eligibility->genavehs);
                    if($eligibility->completerjh == 1)
                    {
                        $sheet->setCellValue('L12', '/');
                    }
                    $sheet->setCellValue('Y12', $eligibility->genavejh);

                    if($eligibility->graduationdate != null)
                    {
                        $sheet->setCellValue('I13', date('m/d/Y', strtotime($eligibility->graduationdate)));
                    }
                    $sheet->setCellValue('P13', $eligibility->schoolname);
                    $sheet->setCellValue('Y13', $eligibility->schooladdress);

                    if($eligibility->peptpasser == 1)
                    {
                        $sheet->setCellValue('A14', '/');
                    }
                    $sheet->setCellValue('H14', $eligibility->peptrating);
                    if($eligibility->alspasser == 1)
                    {
                        $sheet->setCellValue('L14', '/');
                    }
                    $sheet->setCellValue('T14', $eligibility->alsrating);
                    $sheet->setCellValue('AB14', $eligibility->others);

                    if($eligibility->examdate != null)
                    {
                        $sheet->setCellValue('I15',  date('m/d/Y', strtotime($eligibility->examdate)));
                    }
                    $sheet->setCellValue('W15', $eligibility->centername);
                   
                    $startcellno = 21;

                    // F I R S T
                    $records_firstrow = $records[0];
                    
                    $sheet->setCellValue('B'.$startcellno, $records_firstrow[0]->schoolname);
                    $sheet->setCellValue('H'.$startcellno, $records_firstrow[0]->schoolid);
                    $sheet->setCellValue('K'.$startcellno, $records_firstrow[0]->sydesc);
                    if($records_firstrow[0]->semid == 1)
                    {
                        $sheet->setCellValue('N'.$startcellno, '1st');
                    }elseif($records_firstrow[0]->semid == 2)
                    {
                        $sheet->setCellValue('N'.$startcellno, '2nd');
                    }
                    $sheet->setCellValue('Q'.$startcellno, $records_firstrow[1]->schoolname);
                    $sheet->setCellValue('X'.$startcellno, $records_firstrow[1]->schoolid);
                    $sheet->setCellValue('AA'.$startcellno, $records_firstrow[1]->sydesc);
                    if($records_firstrow[1]->semid == 1)
                    {
                        $sheet->setCellValue('AD'.$startcellno, '1st');
                    }elseif($records_firstrow[1]->semid == 2)
                    {
                        $sheet->setCellValue('AD'.$startcellno, '2nd');
                    }
    
                    $startcellno += 1;
                    
                    $sheet->setCellValue('C'.$startcellno, $records_firstrow[0]->trackname.'/'.$records_firstrow[0]->strandname);
                    $sheet->setCellValue('I'.$startcellno, preg_replace('/\D+/', '', $records_firstrow[0]->levelname));
                    $sheet->setCellValue('M'.$startcellno, $records_firstrow[0]->sectionname);
                    $sheet->setCellValue('R'.$startcellno, $records_firstrow[1]->trackname.'/'.$records_firstrow[1]->strandname);
                    $sheet->setCellValue('X'.$startcellno, preg_replace('/\D+/', '', $records_firstrow[1]->levelname));
                    $sheet->setCellValue('AB'.$startcellno, $records_firstrow[1]->sectionname);
                    
                    $startcellno += 5;
    
                    
                    // return collect($records_firstrow[1]->grades);
                    if($records_firstrow[0]->type == 1)
                    {
                        if( collect($records_firstrow[0]->grades)->where('subjdesc','General Average')->count() > 0)
                        {
                            $sheet->setCellValue('K'.($startcellno+2),collect($records_firstrow[0]->grades)->where('subjdesc','General Average')->first()->finalrating);
                            $sheet->setCellValue('M'.($startcellno+2),collect($records_firstrow[0]->grades)->where('subjdesc','General Average')->first()->remarks);
                        }
                    }else{
                        $sheet->setCellValue('K'.($startcellno+2),collect($records_firstrow[0]->grades)->where('semid', $records_firstrow[0]->semid)->where('subjdesc','General Average')->first()->finalrating);
                    }
                    if($records_firstrow[1]->type == 1)
                    {
                        if(collect($records_firstrow[1]->grades)->where('subjdesc','General Average')->count()>0)
                        {
                            $sheet->setCellValue('AA'.($startcellno+2),collect($records_firstrow[1]->grades)->where('subjdesc','General Average')->first()->finalrating);
                            $sheet->setCellValue('AC'.($startcellno+2),collect($records_firstrow[0]->grades)->where('subjdesc','General Average')->first()->remarks);
                        }
                    }else{
                        if(collect($records_firstrow[1]->grades)->where('subjdesc','General Average')->count()>0)
                        {
                            $sheet->setCellValue('AA'.($startcellno+2),collect($records_firstrow[1]->grades)->where('semid', $records_firstrow[1]->semid)->where('subjdesc','General Average')->first()->finalrating);
                        }
                    }
                    
                    
                    $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                    
                    
                    if(count($records_firstrow[0]->grades) == 0)
                    {
                        $firsttable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $firsttable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('A'.$x.':B'.$x);
                            $sheet->mergeCells('C'.$x.':H'.$x);
                            $sheet->mergeCells('K'.$x.':L'.$x);
                            $sheet->mergeCells('M'.$x.':N'.$x);
                        }
                    }else{
                        $firsttable_cellno = $startcellno;
                        
                        foreach(collect($records_firstrow[0]->grades)->where('semid', $records_firstrow[0]->semid) as $firstgrades)
                        {
                            if(strtolower($firstgrades->subjdesc)!= 'general average')
                            {
                                if(mb_strlen ($firstgrades->subjdesc) > 32)
                                {
                                    $sheet->getRowDimension($firsttable_cellno)->setRowHeight(25,'pt');  
                                }
                                $sheet->getStyle('A'.$firsttable_cellno.':M'.$firsttable_cellno)->getAlignment()->setVertical('center');
                                $sheet->mergeCells('A'.$firsttable_cellno.':B'.$firsttable_cellno);
                                $sheet->setCellValue('A'.$firsttable_cellno, $firstgrades->subjcode);
                                $sheet->mergeCells('C'.$firsttable_cellno.':H'.$firsttable_cellno);
                                $sheet->getStyle('C'.$firsttable_cellno)->getAlignment()->setWrapText(true);
                                $sheet->setCellValue('C'.$firsttable_cellno, $firstgrades->subjdesc);
                                
                                $sheet->setCellValue('I'.$firsttable_cellno, $firstgrades->q1);
                                $sheet->setCellValue('J'.$firsttable_cellno, $firstgrades->q2);
                                $sheet->mergeCells('K'.$firsttable_cellno.':L'.$firsttable_cellno);
                                $sheet->setCellValue('K'.$firsttable_cellno, $firstgrades->finalrating);
                                $sheet->mergeCells('M'.$firsttable_cellno.':N'.$firsttable_cellno);
                                $sheet->setCellValue('M'.$firsttable_cellno, $firstgrades->remarks);
                                $firsttable_cellno+=1;
                            }
                        }
                    }
                    if(count($records_firstrow[1]->grades) == 0)
                    {
                        $secondtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $secondtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('P'.$x.':Q'.$x);
                            $sheet->mergeCells('R'.$x.':X'.$x);
                            $sheet->mergeCells('AA'.$x.':AB'.$x);
                            $sheet->mergeCells('AC'.$x.':AD'.$x);
                        }
                    }else{
                        $secondtable_cellno = $startcellno;
                        foreach(collect($records_firstrow[1]->grades)->where('semid', $records_firstrow[1]->semid) as $secondgrades)
                        {
                            if(strtolower($secondgrades->subjdesc) != 'general average')
                            {
                                // return mb_strlen ($secondgrades->subjdesc);
                                if(mb_strlen ($secondgrades->subjdesc) > 32)
                                {
                                    $sheet->getRowDimension($secondtable_cellno)->setRowHeight(25,'pt');  
                                }
                                $sheet->getStyle('P'.$secondtable_cellno.':AC'.$secondtable_cellno)->getAlignment()->setVertical('center');
                                $sheet->mergeCells('P'.$secondtable_cellno.':Q'.$secondtable_cellno);
                                $sheet->setCellValue('P'.$secondtable_cellno, $secondgrades->subjcode);
                                $sheet->mergeCells('R'.$secondtable_cellno.':X'.$secondtable_cellno);
                                $sheet->getStyle('R'.$secondtable_cellno)->getAlignment()->setWrapText(true);
                                $sheet->setCellValue('R'.$secondtable_cellno, $secondgrades->subjdesc);
                                $sheet->setCellValue('Y'.$secondtable_cellno, $secondgrades->q1);
                                $sheet->setCellValue('Z'.$secondtable_cellno, $secondgrades->q2);
                                $sheet->mergeCells('AA'.$secondtable_cellno.':AB'.$secondtable_cellno);
                                $sheet->setCellValue('AA'.$secondtable_cellno, $secondgrades->finalrating);
                                $sheet->mergeCells('AC'.$secondtable_cellno.':AD'.$secondtable_cellno);
                                $sheet->setCellValue('AC'.$secondtable_cellno, $secondgrades->remarks);
                                $secondtable_cellno+=1;
                            }
                        }
                    }
                    
                    $startcellno += $maxgradecount; // general average
    
                    $startcellno += 2;
    
                    $sheet->setCellValue('B'.$startcellno, $records_firstrow[0]->remarks);
                    $sheet->setCellValue('Q'.$startcellno, $records_firstrow[1]->remarks);
                    
                    $startcellno += 3;
                    
                    $sheet->setCellValue('A'.$startcellno, $records_firstrow[0]->teachername);
                    $sheet->setCellValue('F'.$startcellno, $registrarname);
                    
                    $sheet->setCellValue('P'.$startcellno, $records_firstrow[1]->teachername);
                    $sheet->setCellValue('U'.$startcellno, $registrarname);
    
                    $startcellno += 14;
    
                    // S E C O N D
    
                    $records_secondrow = $records[1];
                    
                    $sheet->setCellValue('B'.$startcellno, $records_secondrow[0]->schoolname);
                    $sheet->setCellValue('H'.$startcellno, $records_secondrow[0]->schoolid);
                    $sheet->setCellValue('K'.$startcellno, $records_secondrow[0]->sydesc);
                    if($records_secondrow[0]->semid == 1)
                    {
                        $sheet->setCellValue('N'.$startcellno, '1st');
                    }elseif($records_secondrow[0]->semid == 2)
                    {
                        $sheet->setCellValue('N'.$startcellno, '2nd');
                    }
                    $sheet->setCellValue('Q'.$startcellno, $records_secondrow[1]->schoolname);
                    $sheet->setCellValue('X'.$startcellno, $records_secondrow[1]->schoolid);
                    $sheet->setCellValue('AA'.$startcellno, $records_secondrow[1]->sydesc);
                    if($records_secondrow[1]->semid == 1)
                    {
                        $sheet->setCellValue('AD'.$startcellno, '1st');
                    }elseif($records_secondrow[1]->semid == 2)
                    {
                        $sheet->setCellValue('AD'.$startcellno, '2nd');
                    }
    
                    $startcellno += 1;
                    
                    $sheet->setCellValue('C'.$startcellno, $records_secondrow[0]->trackname.'/'.$records_secondrow[0]->strandname);
                    $sheet->setCellValue('I'.$startcellno, preg_replace('/\D+/', '', $records_secondrow[0]->levelname));
                    $sheet->setCellValue('M'.$startcellno, $records_secondrow[0]->sectionname);
                    $sheet->setCellValue('R'.$startcellno, $records_secondrow[1]->trackname.'/'.$records_secondrow[1]->strandname);
                    $sheet->setCellValue('X'.$startcellno, preg_replace('/\D+/', '', $records_secondrow[1]->levelname));
                    $sheet->setCellValue('AB'.$startcellno, $records_secondrow[1]->sectionname);
                    
                    $startcellno += 5;
    
                    // if($records_secondrow[0]->type == 1)
                    // {
                    //     if( collect($records_secondrow[0]->grades)->where('subjdesc','General Average')->count() > 0)
                    //     {
                    //         $sheet->setCellValue('K'.($startcellno+2),collect($records_secondrow[0]->grades)->where('subjdesc','General Average')->first()->finalrating);
                    //         $sheet->setCellValue('M'.($startcellno+2),collect($records_secondrow[0]->grades)->where('subjdesc','General Average')->first()->remarks);
                    //     }
                    // }else{
                    //     $sheet->setCellValue('K'.($startcellno+2),collect($records_secondrow[0]->grades)->where('semid', $records_secondrow[0]->semid)->where('subjdesc','General Average')->first()->finalrating);
                    // }
                    if(collect($records_firstrow[0]->generalaverage)->count()>0)
                    {
                        $sheet->setCellValue('K'.($startcellno+2),collect($records_firstrow[0]->generalaverage)->first()->finalrating);
                        $sheet->setCellValue('M'.($startcellno+2),collect($records_secondrow[0]->generalaverage)->first()->actiontaken);
                    }
                    if(collect($records_firstrow[1]->generalaverage)->count()>0)
                    {
                        $sheet->setCellValue('AA'.($startcellno+2),collect($records_firstrow[1]->generalaverage)->first()->finalrating);
                        $sheet->setCellValue('AC'.($startcellno+2),collect($records_secondrow[1]->generalaverage)->first()->actiontaken);
                    }
                    // if($records_secondrow[1]->type == 1)
                    // {
                    //     if(collect($records_secondrow[1]->grades)->where('subjdesc','General Average')->count()>0)
                    //     {
                    //         $sheet->setCellValue('AA'.($startcellno+2),collect($records_secondrow[1]->grades)->where('subjdesc','General Average')->first()->finalrating);
                    //         $sheet->setCellValue('AC'.($startcellno+2),collect($records_secondrow[0]->grades)->where('subjdesc','General Average')->first()->remarks);
                    //     }
                    // }else{
                    //     if(collect($records_secondrow[1]->grades)->where('subjdesc','General Average')->count()>0)
                    //     {
                    //         $sheet->setCellValue('AA'.($startcellno+2),collect($records_secondrow[1]->grades)->where('semid', $records_secondrow[1]->semid)->where('subjdesc','General Average')->first()->finalrating);
                    //     }
                    // }
                    
                    
                    $sheet->insertNewRowBefore(($startcellno+1), ($maxgradecount-2));
                    
                    if(count($records_secondrow[0]->grades) == 0)
                    {
                        $thirdtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $thirdtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('A'.$x.':B'.$x);
                            $sheet->mergeCells('C'.$x.':H'.$x);
                            $sheet->mergeCells('K'.$x.':L'.$x);
                            $sheet->mergeCells('M'.$x.':N'.$x);
                        }
                    }else{
                        $thirdtable_cellno = $startcellno;
                        
                        foreach(collect($records_secondrow[0]->grades)->where('semid', $records_secondrow[0]->semid) as $thirdgrades)
                        {
                            if(mb_strlen ($thirdgrades->subjdesc) > 32)
                            {
                                $sheet->getRowDimension($thirdtable_cellno)->setRowHeight(25,'pt');  
                            }
                            $sheet->getStyle('A'.$thirdtable_cellno.':M'.$thirdtable_cellno)->getAlignment()->setVertical('center');
                            $sheet->mergeCells('A'.$thirdtable_cellno.':B'.$thirdtable_cellno);
                            $sheet->setCellValue('A'.$thirdtable_cellno, $thirdgrades->subjcode);
                            $sheet->mergeCells('C'.$thirdtable_cellno.':H'.$thirdtable_cellno);
                            $sheet->getStyle('C'.$thirdtable_cellno)->getAlignment()->setWrapText(true);
                            $sheet->setCellValue('C'.$thirdtable_cellno, $thirdgrades->subjdesc);
                            $sheet->setCellValue('I'.$thirdtable_cellno, $thirdgrades->q1);
                            $sheet->setCellValue('J'.$thirdtable_cellno, $thirdgrades->q2);
                            $sheet->mergeCells('K'.$thirdtable_cellno.':L'.$thirdtable_cellno);
                            $sheet->setCellValue('K'.$thirdtable_cellno, $thirdgrades->finalrating);
                            $sheet->mergeCells('M'.$thirdtable_cellno.':N'.$thirdtable_cellno);
                            $sheet->setCellValue('M'.$thirdtable_cellno, $thirdgrades->remarks);
                            $thirdtable_cellno+=1;
                        }
                    }
                    if(count($records_secondrow[1]->grades) == 0)
                    {
                        $fourthtable_cellno = $startcellno;
                        $endcell = (($startcellno+$maxgradecount)-2);
                        for($x = $fourthtable_cellno; $x <= $endcell; $x++)
                        {
                            $sheet->mergeCells('P'.$x.':Q'.$x);
                            $sheet->mergeCells('R'.$x.':X'.$x);
                            $sheet->mergeCells('AA'.$x.':AB'.$x);
                            $sheet->mergeCells('AC'.$x.':AD'.$x);
                        }
                    }else{
                        $fourthtable_cellno = $startcellno;
                        foreach(collect($records_secondrow[1]->grades)->where('semid', $records_secondrow[1]->semid) as $fourthgrades)
                        {
                            if(mb_strlen ($fourthgrades->subjdesc) > 32)
                            {
                                $sheet->getRowDimension($fourthtable_cellno)->setRowHeight(25,'pt');  
                            }
                            $sheet->getStyle('P'.$fourthtable_cellno.':AC'.$fourthtable_cellno)->getAlignment()->setVertical('center');
                            $sheet->mergeCells('P'.$fourthtable_cellno.':Q'.$fourthtable_cellno);
                            $sheet->setCellValue('P'.$fourthtable_cellno, $fourthgrades->subjcode);
                            $sheet->mergeCells('R'.$fourthtable_cellno.':X'.$fourthtable_cellno);
                            $sheet->getStyle('R'.$fourthtable_cellno)->getAlignment()->setWrapText(true);
                            $sheet->setCellValue('R'.$fourthtable_cellno, $fourthgrades->subjdesc);
                            $sheet->setCellValue('Y'.$fourthtable_cellno, $fourthgrades->q1);
                            $sheet->setCellValue('Z'.$fourthtable_cellno, $fourthgrades->q2);
                            $sheet->mergeCells('AA'.$fourthtable_cellno.':AB'.$fourthtable_cellno);
                            $sheet->setCellValue('AA'.$fourthtable_cellno, $fourthgrades->finalrating);
                            $sheet->mergeCells('AC'.$fourthtable_cellno.':AD'.$fourthtable_cellno);
                            $sheet->setCellValue('AC'.$fourthtable_cellno, $fourthgrades->remarks);
                            $fourthtable_cellno+=1;
                        }
                    }
                    
                
                    $startcellno += $maxgradecount; // general average
                    
                    $startcellno += 2;
    
                    $sheet->setCellValue('B'.$startcellno, $records_secondrow[0]->remarks);
                    $sheet->setCellValue('Q'.$startcellno, $records_secondrow[1]->remarks);
                    
                    $startcellno += 3;
                    
                    $sheet->setCellValue('A'.$startcellno, $records_secondrow[0]->teachername);
                    $sheet->setCellValue('F'.$startcellno, $registrarname);
                    $sheet->setCellValue('P'.$startcellno, $records_secondrow[1]->teachername);
                    $sheet->setCellValue('U'.$startcellno, $registrarname);
    
                    $startcellno += 14;
                    
                    $sheet->setCellValue('D'.$startcellno, $footer->strandaccomplished);
                    $sheet->setCellValue('V'.$startcellno, $footer->shsgenave);
    
                    $startcellno += 1;
    
                    $sheet->setCellValue('D'.$startcellno, $footer->honorsreceived);
                    $sheet->setCellValue('U'.$startcellno, $footer->shsgraduationdateshow);
    
                    $startcellno += 4;
                    
                    $sheet->setCellValue('P'.$startcellno, DB::table('schoolinfo')->first()->authorized);
                    $sheet->setCellValue('Z'.$startcellno, date('m/d/Y'));
    
                    $startcellno += 2;
    
                    $sheet->setCellValue('R'.$startcellno, $footer->copyforupper);
                    
                    $startcellno += 1;
                    
                    $sheet->setCellValue('S'.$startcellno, date('m/d/Y'));
                    // Sheet2
                    $sheet = $spreadsheet->getSheet(1);

                        $startcellno = 10;
                        
                        $sheet->setCellValue('B'.$startcellno, $studinfo->lastname.', '.$studinfo->firstname.' '.$studinfo->middlename[0].'. '.$studinfo->suffix);
                        $sheet->setCellValue('H'.$startcellno, $studinfo->gender);
                        $sheet->setCellValue('L'.$startcellno, date('m/d/Y',strtotime($studinfo->dob)));
                        
                        $startcellno += 1;

                        $sheet->setCellValue('B'.$startcellno, $studinfo->street.', '.$studinfo->barangay.' '.$studinfo->city.', '.$studinfo->province);
                        
                        $startcellno += 1;
                        
                        $sheet->setCellValue('C'.$startcellno, $studinfo->fathername);
                        $sheet->setCellValue('L'.$startcellno, $studinfo->foccupation);
                        
                        $startcellno += 1;
                        
                        $sheet->setCellValue('C'.$startcellno, $studinfo->mothername);
                        $sheet->setCellValue('L'.$startcellno, $studinfo->moccupation);
                        
                        $records_grade11  = $records[0];
                        
                        $startcellno += 5;

                        if(!array_key_exists("strandcode", collect($records_grade11[0])->toArray())){
                            $records_grade11[0]->strandcode = $records_grade11[0]->strandname;
                        }
                        
                        
                        $sheet->setCellValue('B'.$startcellno, preg_replace('/\D+/', '', $records_grade11[0]->levelname));
                        $sheet->setCellValue('E'.$startcellno, $records_grade11[0]->strandcode.'/'.$records_grade11[0]->sectionname);
                        $sheet->setCellValue('H'.$startcellno, $records_grade11[0]->schoolname);
                        $sheet->setCellValue('M'.$startcellno, $records_grade11[0]->sydesc);
                        
                        $startcellno += 5;

                        $grade11_firstsem = collect($records_grade11[0]->grades)->where('semid',$records_grade11[0]->semid)->values();
                        
                        $coresubjects_firstsem = collect($grade11_firstsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'COR') === false;
                        })->values();
                        
                        if(count($coresubjects_firstsem) == 0)
                        {
                            $startcellno += 1;
                        }else{
                            foreach($coresubjects_firstsem as $coresubjfirstsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $coresubjfirstsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $coresubjfirstsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $coresubjfirstsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $coresubjfirstsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }

                        $startcellno += 1;

                        $sheet->setCellValue('B'.$startcellno, 'Applied and Specialized Subjects');
                        $sheet->getStyle('B'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('B'.$startcellno)->getFont()->setItalic(true);

                        $startcellno += 1;
                        
                        $appsubjects_firstsem = collect($grade11_firstsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'APP') === false;
                        })->values();

                        if(count($appsubjects_firstsem) > 0)
                        {
                            foreach($appsubjects_firstsem as $appsubjfirstsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $appsubjfirstsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $appsubjfirstsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $appsubjfirstsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $appsubjfirstsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }

                        $specsubjects_firstsem = collect($grade11_firstsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'SPEC') === false;
                        })->values();

                        if(count($specsubjects_firstsem) > 0)
                        {
                            foreach($specsubjects_firstsem as $specsubjfirstsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $specsubjfirstsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $specsubjfirstsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $specsubjfirstsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $specsubjfirstsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }
                        
                        if(collect($grade11_firstsem)->where('subjdesc','General Average')->count()>0)
                        {
                            $sheet->setCellValue('L36', collect($grade11_firstsem)->where('subjdesc','General Average')->first()->finalrating); // 
    
                        }
                        $startcellno = 36;
                        $startcellno += 2;
                        
                        
                        // getNameFromNumber(3);    = C
                        $attendance = $records_grade11[0]->attendance;
                        
                        if(count($attendance)>0)
                        {
                            $attendance = $records_grade11[0]->attendance[0];   
                        }
                        if(count($attendance) > 0)
                        {
                            $startcolumnno = 4;
                            foreach($attendance as $attendancefirstsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, substr($attendancefirstsem->monthdesc, 0, 3));
                                $startcolumnno+=1;
                            }
                            $startcellno+=1;

                            $startcolumnno = 4;
                            foreach($attendance as $attendancefirstsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, $attendancefirstsem->days);
                                $startcolumnno+=1;
                            }
                            $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, collect($attendance)->sum('days'));

                            $startcellno+=1;

                            $startcolumnno = 4;
                            foreach($attendance as $attendancefirstsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, $attendancefirstsem->present);
                                $startcolumnno+=1;
                            }
                            $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, collect($attendance)->sum('present'));
                            $startcellno+=3;
                        }else{
                            $startcellno += 5;
                        }
                        // $startcellno += 5;
                        // return $startcellno;
                        // return collect($records_grade11[1]);
                        if(!array_key_exists("strandcode", collect($records_grade11[1])->toArray())){
                            $records_grade11[1]->strandcode = $records_grade11[1]->strandname;
                        }
                        
                        
                        $sheet->setCellValue('B'.$startcellno, preg_replace('/\D+/', '', $records_grade11[1]->levelname));
                        $sheet->setCellValue('E'.$startcellno, $records_grade11[1]->strandcode.'/'.$records_grade11[1]->sectionname);
                        $sheet->setCellValue('H'.$startcellno, $records_grade11[1]->schoolname);
                        $sheet->setCellValue('M'.$startcellno, $records_grade11[1]->sydesc);
                        
                        $startcellno += 5;

                        $grade11_secondsem = collect($records_grade11[1]->grades)->where('semid',$records_grade11[1]->semid)->values();
                        
                        $coresubjects_secondsem = collect($grade11_secondsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'COR') === false;
                        })->values();
                        
                        if(count($coresubjects_secondsem) == 0)
                        {
                            $startcellno += 1;
                        }else{
                            foreach($coresubjects_secondsem as $coresubjsecondsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $coresubjsecondsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $coresubjsecondsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $coresubjsecondsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $coresubjsecondsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }

                        $startcellno += 1;

                        $sheet->setCellValue('B'.$startcellno, 'Applied and Specialized Subjects');
                        $sheet->getStyle('B'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('B'.$startcellno)->getFont()->setItalic(true);

                        $startcellno += 1;
                        
                        $appsubjects_secondsem = collect($grade11_secondsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'APP') === false;
                        })->values();

                        if(count($appsubjects_secondsem) > 0)
                        {
                            foreach($appsubjects_secondsem as $appsubjsecondsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $appsubjsecondsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $appsubjsecondsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $appsubjsecondsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $appsubjsecondsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }

                        $specsubjects_secondsem = collect($grade11_secondsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'SPEC') === false;
                        })->values();

                        if(count($specsubjects_secondsem) > 0)
                        {
                            foreach($specsubjects_secondsem as $specsubjsecondsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $specsubjsecondsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $specsubjsecondsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $specsubjsecondsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $specsubjsecondsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }
                        
                        if(collect($grade11_secondsem)->where('subjdesc','General Average')->count()>0)
                        {
                            $sheet->setCellValue('L61', collect($grade11_secondsem)->where('subjdesc','General Average')->first()->finalrating); // 
    
                        }
                        
                        $attendance = $records_grade11[1]->attendance;
                        
                        $startcellno = 63;
                        if(count($attendance)>0)
                        {
                            $attendance = $records_grade11[1]->attendance[1];   
                        }
                        // return $attendance;
                        if(count($attendance) > 0)
                        {
                            $startcolumnno = 4;
                            foreach($attendance as $attendancesecondsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, substr($attendancesecondsem->monthdesc, 0, 3));
                                $startcolumnno+=1;
                            }
                            $startcellno+=1;
                            $startcolumnno = 4;
                            foreach($attendance as $attendancesecondsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, $attendancesecondsem->days);
                                $startcolumnno+=1;
                            }
                            $sheet->setCellValue('I'.$startcellno, collect($attendance)->sum('days'));

                            $startcellno+=1;

                            $startcolumnno = 4;
                            foreach($attendance as $attendancesecondsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, $attendancesecondsem->present);
                                $startcolumnno+=1;
                            }
                            $sheet->setCellValue('I'.$startcellno, collect($attendance)->sum('present'));
                        }
                    // Sheet3
                    $sheet = $spreadsheet->getSheet(2);

                        $startcellno = 10;
                        
                        $sheet->setCellValue('B'.$startcellno, $studinfo->lastname.', '.$studinfo->firstname.' '.$studinfo->middlename[0].'. '.$studinfo->suffix);
                        $sheet->setCellValue('H'.$startcellno, $studinfo->gender);
                        $sheet->setCellValue('L'.$startcellno, date('m/d/Y',strtotime($studinfo->dob)));
                        
                        $startcellno += 1;

                        $sheet->setCellValue('B'.$startcellno, $studinfo->street.', '.$studinfo->barangay.' '.$studinfo->city.', '.$studinfo->province);
                        
                        $startcellno += 1;
                        
                        $sheet->setCellValue('C'.$startcellno, $studinfo->fathername);
                        $sheet->setCellValue('L'.$startcellno, $studinfo->foccupation);
                        
                        $startcellno += 1;
                        
                        $sheet->setCellValue('C'.$startcellno, $studinfo->mothername);
                        $sheet->setCellValue('L'.$startcellno, $studinfo->moccupation);
                        
                        $records_grade12  = $records[1];
                        
                        $startcellno += 5;

                        if(!array_key_exists("strandcode", collect($records_grade12[0])->toArray())){
                            $records_grade12[0]->strandcode = $records_grade12[0]->strandname;
                        }
                        
                        
                        $sheet->setCellValue('B'.$startcellno, preg_replace('/\D+/', '', $records_grade12[0]->levelname));
                        $sheet->setCellValue('E'.$startcellno, $records_grade12[0]->strandcode.'/'.$records_grade12[0]->sectionname);
                        $sheet->setCellValue('H'.$startcellno, $records_grade12[0]->schoolname);
                        $sheet->setCellValue('M'.$startcellno, $records_grade12[0]->sydesc);
                        
                        $startcellno += 5;

                        $grade12_firstsem = collect($records_grade12[0]->grades)->where('semid',$records_grade12[0]->semid)->values();
                        
                        $coresubjects_firstsem = collect($grade12_firstsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'COR') === false;
                        })->values();
                        
                        if(count($coresubjects_firstsem) == 0)
                        {
                            $startcellno += 1;
                        }else{
                            foreach($coresubjects_firstsem as $coresubjfirstsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $coresubjfirstsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $coresubjfirstsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $coresubjfirstsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $coresubjfirstsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }

                        $startcellno += 1;

                        $sheet->setCellValue('B'.$startcellno, 'Applied and Specialized Subjects');
                        $sheet->getStyle('B'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('B'.$startcellno)->getFont()->setItalic(true);

                        $startcellno += 1;
                        
                        $appsubjects_firstsem = collect($grade12_firstsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'APP') === false;
                        })->values();

                        if(count($appsubjects_firstsem) > 0)
                        {
                            foreach($appsubjects_firstsem as $appsubjfirstsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $appsubjfirstsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $appsubjfirstsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $appsubjfirstsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $appsubjfirstsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }

                        $specsubjects_firstsem = collect($grade12_firstsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'SPEC') === false;
                        })->values();

                        if(count($specsubjects_firstsem) > 0)
                        {
                            foreach($specsubjects_firstsem as $specsubjfirstsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $specsubjfirstsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $specsubjfirstsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $specsubjfirstsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $specsubjfirstsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }
                        if(collect($grade12_firstsem)->where('subjdesc','General Average')->count()>0)
                        {
                            $sheet->setCellValue('L36', collect($grade12_firstsem)->where('subjdesc','General Average')->first()->finalrating); //     
                        }
                        $startcellno = 36;
                        $startcellno +=2;
                        $attendance = $records_grade12[0]->attendance;
                        
                        if(count($attendance)>0)
                        {
                            $attendance = $records_grade12[0]->attendance[0];   
                        }
                        if(count($attendance) > 0)
                        {
                            $startcolumnno = 4;
                            foreach($attendance as $attendancefirstsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, substr($attendancefirstsem->monthdesc, 0, 3));
                                $startcolumnno+=1;
                            }
                            $startcellno+=1;

                            $startcolumnno = 4;
                            foreach($attendance as $attendancefirstsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, $attendancefirstsem->days);
                                $startcolumnno+=1;
                            }
                            $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, collect($attendance)->sum('days'));

                            $startcellno+=1;

                            $startcolumnno = 4;
                            foreach($attendance as $attendancefirstsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, $attendancefirstsem->present);
                                $startcolumnno+=1;
                            }
                            $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, collect($attendance)->sum('present'));
                            $startcellno+=3;
                        }else{
                            $startcellno += 5;
                        }
                        // return collect($records_grade11[1]);
                        if(!array_key_exists("strandcode", collect($records_grade12[1])->toArray())){
                            $records_grade12[1]->strandcode = $records_grade12[1]->strandname;
                        }
                        
                        
                        $sheet->setCellValue('B'.$startcellno, preg_replace('/\D+/', '', $records_grade12[1]->levelname));
                        $sheet->setCellValue('E'.$startcellno, $records_grade12[1]->strandcode.'/'.$records_grade12[1]->sectionname);
                        $sheet->setCellValue('H'.$startcellno, $records_grade12[1]->schoolname);
                        $sheet->setCellValue('M'.$startcellno, $records_grade12[1]->sydesc);
                        
                        $startcellno += 5;

                        $grade12_secondsem = collect($records_grade12[1]->grades)->where('semid',$records_grade12[1]->semid)->values();
                        
                        $coresubjects_firstsem = collect($grade12_secondsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'COR') === false;
                        })->values();
                        
                        if(count($coresubjects_firstsem) == 0)
                        {
                            $startcellno += 1;
                        }else{
                            foreach($coresubjects_firstsem as $coresubjfirstsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $coresubjfirstsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $coresubjfirstsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $coresubjfirstsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $coresubjfirstsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }

                        $startcellno += 1;

                        $sheet->setCellValue('B'.$startcellno, 'Applied and Specialized Subjects');
                        $sheet->getStyle('B'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('B'.$startcellno)->getFont()->setItalic(true);

                        $startcellno += 1;
                        
                        $appsubjects_firstsem = collect($grade12_secondsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'APP') === false;
                        })->values();

                        if(count($appsubjects_firstsem) > 0)
                        {
                            foreach($appsubjects_firstsem as $appsubjfirstsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $appsubjfirstsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $appsubjfirstsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $appsubjfirstsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $appsubjfirstsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }

                        $specsubjects_firstsem = collect($grade12_secondsem)->reject(function($element) {
                            return mb_strpos($element->subjcode, 'SPEC') === false;
                        })->values();

                        if(count($specsubjects_firstsem) > 0)
                        {
                            foreach($specsubjects_firstsem as $specsubjfirstsem)
                            {
                                $sheet->setCellValue('B'.$startcellno, $specsubjfirstsem->subjdesc);
                                $sheet->setCellValue('J'.$startcellno, $specsubjfirstsem->q1);
                                $sheet->setCellValue('K'.$startcellno, $specsubjfirstsem->q2);
                                $sheet->mergeCells('L'.$startcellno.':M'.$startcellno);
                                $sheet->setCellValue('L'.$startcellno, $specsubjfirstsem->finalrating);
                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setHorizontal('center');
                                $startcellno += 1;
                            }
                        }
                        
                        if(collect($grade12_secondsem)->where('subjdesc','General Average')->count()>0)
                        {
                            $sheet->setCellValue('L61', collect($grade12_secondsem)->where('subjdesc','General Average')->first()->finalrating); // 
                        }
                        $attendance = $records_grade12[1]->attendance;
                        
                        $startcellno = 63;
                        if(count($attendance)>0)
                        {
                            $attendance = $records_grade12[1]->attendance[1];   
                        }
                        // return $attendance;
                        if(count($attendance) > 0)
                        {
                            $startcolumnno = 4;
                            foreach($attendance as $attendancesecondsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, substr($attendancesecondsem->monthdesc, 0, 3));
                                $startcolumnno+=1;
                            }
                            $startcellno+=1;
                            $startcolumnno = 4;
                            foreach($attendance as $attendancesecondsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, $attendancesecondsem->days);
                                $startcolumnno+=1;
                            }
                            $sheet->setCellValue('I'.$startcellno, collect($attendance)->sum('days'));

                            $startcellno+=1;

                            $startcolumnno = 4;
                            foreach($attendance as $attendancesecondsem)
                            {
                                $sheet->setCellValue(getNameFromNumber($startcolumnno).$startcellno, $attendancesecondsem->present);
                                $startcolumnno+=1;
                            }
                            $sheet->setCellValue('I'.$startcellno, collect($attendance)->sum('present'));
                        }
                    
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == '')
                {
                    


                    //// F R O N T  P A G E

                    $sheet->setCellValue('F8', $studinfo->lastname);
                    $sheet->setCellValue('Y8', $studinfo->firstname);
                    $sheet->setCellValue('AZ8', $studinfo->middlename);

                    $sheet->setCellValue('C9', $studinfo->lrn);
                    $sheet->getStyle('C9')->getNumberFormat()->setFormatCode('0');
                    $sheet->setCellValue('AA9', date('m/d/Y', strtotime($studinfo->dob)));
                    $sheet->setCellValue('AN9', $studinfo->gender);
                    
                    // E L I G I B I L I T Y
                    // return collect($eligibility);
                    if($eligibility->completerhs == 1)
                    {
                        $sheet->setCellValue('A13', '/');
                    }
                    $sheet->setCellValue('N13', $eligibility->genavehs);
                    if($eligibility->completerjh == 1)
                    {
                        $sheet->setCellValue('S13', '/');
                    }
                    $sheet->setCellValue('AH13', $eligibility->genavejh);

                    if($eligibility->graduationdate != null)
                    {
                        $sheet->setCellValue('P14', date('m/d/Y', strtotime($eligibility->graduationdate)));
                    }
                    $sheet->setCellValue('Z14', $eligibility->schoolname);
                    $sheet->setCellValue('AV14', $eligibility->schooladdress);

                    // $sheet->setCellValue('A16', $eligibility->schoolname);
                    $sheet->getStyle('A16')->getAlignment()->setHorizontal('center');
                    if($eligibility->peptpasser == 1)
                    {
                        $sheet->setCellValue('A16', '/');
                    }
                    $sheet->setCellValue('K16', $eligibility->peptrating);
                    if($eligibility->alspasser == 1)
                    {
                        $sheet->setCellValue('S16', '/');
                    }
                    $sheet->setCellValue('AC16', $eligibility->alsrating);
                    $sheet->setCellValue('AP16', $eligibility->others);
                    
                    if($eligibility->examdate != null)
                    {
                        $sheet->setCellValue('P17',  date('m/d/Y', strtotime($eligibility->examdate)));
                    }
                    $sheet->setCellValue('AN17', $eligibility->centername);


                    $frontrecords = $records[0];
                    
                    foreach($frontrecords as $frontrecord)
                    {
                        foreach($frontrecord as $key => $value)
                        {
                            if($value == null)
                            {   
                                if($key == 'grades' || $key == 'remedials')
                                {
                                    $frontrecord->$key = array();
                                }else{
                                    // $frontrecord->$key = '_______________';
                                }
                                // return $key;
                                // $frontrecord->$key;
                            }
                        }
                    }
                    // return $frontrecords;
                    $frontstartcellno = 23;
                    ///// FIRST GRADES TABLE
                    
                        $sheet->setCellValue('E'.$frontstartcellno, $frontrecords[0]->schoolname);
                        $sheet->setCellValue('AF'.$frontstartcellno, $frontrecords[0]->schoolid);
                        $sheet->setCellValue('AS'.$frontstartcellno, preg_replace('/\D+/', '', $frontrecords[0]->levelname));
                        $sheet->setCellValue('BA'.$frontstartcellno, $frontrecords[0]->sydesc);
                        if($frontrecords[0]->semid == 1)
                        {
                            $sheet->setCellValue('BK'.$frontstartcellno, '1st');
                        }elseif($frontrecords[0]->semid == 2)
                        {
                            $sheet->setCellValue('BK'.$frontstartcellno, '2nd');
                        }
                        
                    $frontstartcellno += 2;

                        $sheet->setCellValue('G'.$frontstartcellno, $frontrecords[0]->strandname);
                        $sheet->setCellValue('AS'.$frontstartcellno, $frontrecords[0]->sectionname);

                    $frontstartcellno += 6;
                    
                        // $sheet->insertNewRowBefore(($frontstartcellno+1), ($maxgradecount-2));
                        
                        if(count(collect($frontrecords[0]->grades)->where('semid',$frontrecords[0]->semid))>0)
                        {
                            foreach(collect($frontrecords[0]->grades)->where('semid',$frontrecords[0]->semid) as $key => $g11sem1grade)
                            {
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);
                                $sheet->setCellValue('A'.$frontstartcellno, $g11sem1grade->subjcode);
                                $sheet->setCellValue('I'.$frontstartcellno, ucwords(strtolower($g11sem1grade->subjdesc)));
                                $sheet->setCellValue('AT'.$frontstartcellno, $g11sem1grade->q1);
                                if($g11sem1grade->q1<75) { $sheet->getStyle('AT'.$frontstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);}
                                $sheet->setCellValue('AY'.$frontstartcellno, $g11sem1grade->q2);
                                if($g11sem1grade->q2<75) { $sheet->getStyle('AY'.$frontstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);}
                                $sheet->setCellValue('BD'.$frontstartcellno, $g11sem1grade->finalrating);
                                $sheet->setCellValue('BI'.$frontstartcellno, $g11sem1grade->remarks);
                                $frontstartcellno+=1;
                                if($key != collect($frontrecords[0]->grades)->where('semid',$frontrecords[0]->semid)->reverse()->keys()->first())
                                {
                                    $sheet->insertNewRowBefore($frontstartcellno, 1);
                                }
                            }
                            // $sheet->setCellValue('K'.$firstgradescellno, collect($frontrecords[0]->grades)->where('inMAPEH',0)->avg('finalrating'));
                        }else{
                            for($x = 0; $x<$maxgradecount ; $x++)
                            {
                                $frontstartcellno += 1;
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);

                            }
                        }
                        
                        if(count($frontrecords[0]->subjaddedforauto) > 0)
                        {
                            foreach($frontrecords[0]->subjaddedforauto as $customsubjgrade)
                            {
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);
                                $sheet->setCellValue('A'.$frontstartcellno, $customsubjgrade->subjcode);
                                $sheet->setCellValue('I'.$frontstartcellno, ucwords(strtolower($customsubjgrade->subjdesc)));
                                $sheet->setCellValue('AT'.$frontstartcellno, $customsubjgrade->q1);
                                if($customsubjgrade->q1<75) { $sheet->getStyle('AT'.$frontstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);}
                                $sheet->setCellValue('AY'.$frontstartcellno, $customsubjgrade->q2);
                                if($customsubjgrade->q2<75) { $sheet->getStyle('AY'.$frontstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);}
                                $sheet->setCellValue('BD'.$frontstartcellno, $customsubjgrade->finalrating);
                                $sheet->setCellValue('BI'.$frontstartcellno, $customsubjgrade->actiontaken);
                                $frontstartcellno+=1;
                            }
                        }

                    $frontstartcellno+=2;

                        $sheet->setCellValue('E'.$frontstartcellno, $frontrecords[0]->remarks);

                    $frontstartcellno+=4;

                        $sheet->setCellValue('A'.$frontstartcellno, $frontrecords[0]->teachername);
                        $sheet->setCellValue('Y'.$frontstartcellno, $frontrecords[0]->recordincharge);
                        $sheet->setCellValue('AZ'.$frontstartcellno, $frontrecords[0]->datechecked);

                    $frontstartcellno+=17;
                    ///// SECOND GRADES TABLE
                    
                        $sheet->setCellValue('E'.$frontstartcellno, $frontrecords[1]->schoolname);
                        $sheet->setCellValue('AF'.$frontstartcellno, $frontrecords[1]->schoolid);
                        $sheet->setCellValue('AS'.$frontstartcellno, preg_replace('/\D+/', '', $frontrecords[1]->levelname));
                        $sheet->setCellValue('BA'.$frontstartcellno, $frontrecords[1]->sydesc);
                        if($frontrecords[1]->semid == 1)
                        {
                            $sheet->setCellValue('BK'.$frontstartcellno, '1st');
                        }elseif($frontrecords[1]->semid == 2)
                        {
                            $sheet->setCellValue('BK'.$frontstartcellno, '2nd');
                        }
                        
                    $frontstartcellno += 2;

                        $sheet->setCellValue('G'.$frontstartcellno, $frontrecords[1]->strandname);
                        $sheet->setCellValue('AS'.$frontstartcellno, $frontrecords[1]->sectionname);

                    $frontstartcellno += 6;
                    
                        // $sheet->insertNewRowBefore(($frontstartcellno+1), ($maxgradecount-2));
                        
                        if(count(collect($frontrecords[1]->grades)->where('semid',$frontrecords[1]->semid))>0)
                        {
                            foreach(collect($frontrecords[1]->grades)->where('semid',$frontrecords[1]->semid) as $key => $g11sem2grade)
                            {
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);
                                $sheet->setCellValue('A'.$frontstartcellno, $g11sem2grade->subjcode);
                                $sheet->setCellValue('I'.$frontstartcellno, ucwords(strtolower($g11sem2grade->subjdesc)));
                                $sheet->setCellValue('AT'.$frontstartcellno, $g11sem2grade->q1);
                                if($g11sem2grade->q1<75) { $sheet->getStyle('AT'.$frontstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);}
                                $sheet->setCellValue('AY'.$frontstartcellno, $g11sem2grade->q2);
                                if($g11sem2grade->q2<75) { $sheet->getStyle('AY'.$frontstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);}
                                $sheet->setCellValue('BD'.$frontstartcellno, $g11sem2grade->finalrating);
                                $sheet->setCellValue('BI'.$frontstartcellno, $g11sem2grade->remarks);
                                $frontstartcellno+=1;
                                if($key != collect($frontrecords[1]->grades)->where('semid',$frontrecords[1]->semid)->reverse()->keys()->first())
                                {
                                    $sheet->insertNewRowBefore($frontstartcellno, 1);
                                }
                            }
                            // $sheet->setCellValue('K'.$firstgradescellno, collect($frontrecords[0]->grades)->where('inMAPEH',0)->avg('finalrating'));
                        }else{
                            
                            for($x = 0; $x<$maxgradecount ; $x++)
                            {
                                $frontstartcellno += 1;
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);

                            }
                        }
                        if(count($frontrecords[1]->subjaddedforauto) > 0)
                        {
                            foreach($frontrecords[1]->subjaddedforauto as $customsubjgrade)
                            {
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);
                                $sheet->setCellValue('A'.$frontstartcellno, $customsubjgrade->subjcode);
                                $sheet->setCellValue('I'.$frontstartcellno, ucwords(strtolower($customsubjgrade->subjdesc)));
                                $sheet->setCellValue('AT'.$frontstartcellno, $customsubjgrade->q1);
                                if($customsubjgrade->q1<75) { $sheet->getStyle('AT'.$frontstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);}
                                $sheet->setCellValue('AY'.$frontstartcellno, $customsubjgrade->q2);
                                if($customsubjgrade->q2<75) { $sheet->getStyle('AY'.$frontstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);}
                                $sheet->setCellValue('BD'.$frontstartcellno, $customsubjgrade->finalrating);
                                $sheet->setCellValue('BI'.$frontstartcellno, $customsubjgrade->actiontaken);
                                $frontstartcellno+=1;
                            }
                        }

                    $frontstartcellno+=2;

                        $sheet->setCellValue('E'.$frontstartcellno, $frontrecords[1]->remarks);

                    $frontstartcellno+=4;

                        $sheet->setCellValue('A'.$frontstartcellno, $frontrecords[1]->teachername);
                        $sheet->setCellValue('Y'.$frontstartcellno, $frontrecords[1]->recordincharge);
                        $sheet->setCellValue('AZ'.$frontstartcellno, $frontrecords[1]->datechecked);

                

                    $sheet = $spreadsheet->getSheet(1);

                    $backrecords = $records[1];

                    foreach($backrecords as $backrecord)
                    {
                        foreach($backrecord as $key => $value)
                        {
                            if($value == null)
                            {   
                                if($key == 'grades' || $key == 'remedials')
                                {
                                    $backrecord->$key = array();
                                }else{
                                    // $frontrecord->$key = '_______________';
                                }
                                // return $key;
                                // $frontrecord->$key;
                            }
                        }
                    }
                    // return $backrecords;
                    $backstartcellno = 4;
                    ///// FIRST GRADES TABLE
                    
                        $sheet->setCellValue('E'.$backstartcellno, $backrecords[0]->schoolname);
                        $sheet->setCellValue('AF'.$backstartcellno, $backrecords[0]->schoolid);
                        $sheet->setCellValue('AS'.$backstartcellno, preg_replace('/\D+/', '', $backrecords[0]->levelname));
                        $sheet->setCellValue('BA'.$backstartcellno, $backrecords[0]->sydesc);
                        if($backrecords[0]->semid == 1)
                        {
                            $sheet->setCellValue('BK'.$backstartcellno, '1st');
                        }elseif($backrecords[0]->semid == 2)
                        {
                            $sheet->setCellValue('BK'.$backstartcellno, '2nd');
                        }
                        
                    $backstartcellno += 1;

                        $sheet->setCellValue('G'.$backstartcellno, $backrecords[0]->strandname);
                        $sheet->setCellValue('AS'.$backstartcellno, $backrecords[0]->sectionname);

                    $backstartcellno += 6;
                    
                        
                        if(count(collect($backrecords[0]->grades)->where('semid',$backrecords[0]->semid))>0)
                        {
                            $frontcountsubj = 0;
                            foreach(collect($backrecords[0]->grades)->where('semid',$backrecords[0]->semid) as $key => $g12sem1grade)
                            {
                                // return $g12sem1grade->subjcode;
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                                $sheet->setCellValue('A'.$backstartcellno, $g12sem1grade->subjcode);
                                $sheet->setCellValue('I'.$backstartcellno, ucwords(strtolower($g12sem1grade->subjdesc)));
                                $sheet->setCellValue('AT'.$backstartcellno, $g12sem1grade->q1);
                                if($g12sem1grade->q1 <= 74) {
                                    $sheet->getStyle('AT'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                                }
                                $sheet->setCellValue('AY'.$backstartcellno, $g12sem1grade->q2);
                                if($g12sem1grade->q2 <= 74) {
                                    $sheet->getStyle('AY'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                                }
                                $sheet->setCellValue('BD'.$backstartcellno, $g12sem1grade->finalrating);
                                $sheet->setCellValue('BI'.$backstartcellno, $g12sem1grade->remarks);
                                $backstartcellno+=1;
                                $frontcountsubj+=1;
                                if($key != collect($backrecords[0]->grades)->where('semid',$backrecords[0]->semid)->reverse()->keys()->first())
                                {
                                    $sheet->insertNewRowBefore($backstartcellno, 1);
                                    $sheet->getStyle('AT'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                                    $sheet->getStyle('AY'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                                }
                            }
                            
                            // for($x = $frontcountsubj; $x<$maxgradecount ; $x++)
                            // {
                            //     $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                            //     $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                            //     $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                            //     $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                            //     $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                            //     $backstartcellno += 1;
                            // }
                            // return $backstartcellno;
                        }else{
                            for($x = 0; $x<$maxgradecount ; $x++)
                            {
                                $backstartcellno += 1;
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);

                            }
                        }
                        if(count($backrecords[0]->subjaddedforauto) > 0)
                        {
                            foreach($backrecords[0]->subjaddedforauto as $customsubjgrade)
                            {
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                                $sheet->setCellValue('A'.$backstartcellno, $customsubjgrade->subjcode);
                                $sheet->setCellValue('I'.$backstartcellno, ucwords(strtolower($customsubjgrade->subjdesc)));
                                $sheet->setCellValue('AT'.$backstartcellno, $customsubjgrade->q1);
                                if($customsubjgrade->q1 < 75) {
                                    $sheet->getStyle('AT'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                                }
                                $sheet->setCellValue('AY'.$backstartcellno, $customsubjgrade->q2);
                                if($customsubjgrade->q2 < 75) {
                                    $sheet->getStyle('AY'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                                }
                                $sheet->setCellValue('BD'.$backstartcellno, $customsubjgrade->finalrating);
                                $sheet->setCellValue('BI'.$backstartcellno, $customsubjgrade->actiontaken);
                                $backstartcellno+=1;
                            }
                        }
                        if(count($backrecords[0]->generalaverage)>0)
                        {
                            $sheet->setCellValue('BD'.$backstartcellno, $backrecords[0]->generalaverage[0]->finalrating);
                        }
                        
                    $backstartcellno+=2;

                        $sheet->setCellValue('E'.$backstartcellno, $backrecords[0]->remarks);

                    $backstartcellno+=4;
                    
                        $sheet->setCellValue('A'.$backstartcellno, $backrecords[0]->teachername);
                        $sheet->setCellValue('Y'.$backstartcellno, $backrecords[0]->recordincharge);
                        $sheet->setCellValue('AZ'.$backstartcellno, $backrecords[0]->datechecked);

                    $backstartcellno+=17;
                    ///// SECOND GRADES TABLE
                    
                        $sheet->setCellValue('E'.$backstartcellno, $backrecords[1]->schoolname);
                        $sheet->setCellValue('AF'.$backstartcellno, $backrecords[1]->schoolid);
                        $sheet->setCellValue('AS'.$backstartcellno, preg_replace('/\D+/', '', $backrecords[1]->levelname));
                        $sheet->setCellValue('BA'.$backstartcellno, $backrecords[1]->sydesc);
                        if($backrecords[1]->semid == 1)
                        {
                            $sheet->setCellValue('BK'.$backstartcellno, '1st');
                        }elseif($backrecords[1]->semid == 2)
                        {
                            $sheet->setCellValue('BK'.$backstartcellno, '2nd');
                        }
                        
                    $backstartcellno += 2;

                        $sheet->setCellValue('G'.$backstartcellno, $backrecords[1]->strandname);
                        $sheet->setCellValue('AS'.$backstartcellno, $backrecords[1]->sectionname);

                    $backstartcellno += 6;
                    
                        // $sheet->insertNewRowBefore(($backstartcellno+1), ($maxgradecount-2));
                        // return $backstartcellno;
                        if(count(collect($backrecords[1]->grades)->where('semid',$backrecords[1]->semid))>0)
                        {
                            $frontcountsubj = 0;
                            foreach(collect($backrecords[1]->grades)->where('semid',$backrecords[1]->semid) as $key => $g12sem2grade)
                            {
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                                $sheet->setCellValue('A'.$backstartcellno, $g12sem2grade->subjcode);
                                $sheet->setCellValue('I'.$backstartcellno, ucwords(strtolower($g12sem2grade->subjdesc)));
                                $sheet->setCellValue('AT'.$backstartcellno, $g12sem2grade->q1);
                                if($g12sem2grade->q1 < 75) {
                                    $sheet->getStyle('AT'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                                }
                                $sheet->setCellValue('AY'.$backstartcellno, $g12sem2grade->q2);
                                if($g12sem2grade->q2 < 75) {
                                    $sheet->getStyle('AY'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                                }
                                $sheet->setCellValue('BD'.$backstartcellno, $g12sem2grade->finalrating);
                                $sheet->setCellValue('BI'.$backstartcellno, $g12sem2grade->remarks);
                                $backstartcellno+=1;
                                $frontcountsubj+=1;
                                if($key != collect($backrecords[1]->grades)->where('semid',$backrecords[1]->semid)->reverse()->keys()->first())
                                {
                                    $sheet->insertNewRowBefore($backstartcellno, 1);
                                    $sheet->getStyle('AT'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                                    $sheet->getStyle('AY'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                                }
                            }
                            // for($x = $frontcountsubj; $x<$maxgradecount ; $x++)
                            // {
                            //     $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                            //     $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                            //     $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                            //     $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                            //     $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                            //     $backstartcellno += 1;
                            // }
                            // $sheet->setCellValue('K'.$firstgradescellno, collect($backrecords[0]->grades)->where('inMAPEH',0)->avg('finalrating'));
                        }else{
                            for($x = 0; $x<$maxgradecount ; $x++)
                            {
                                $backstartcellno += 1;
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);

                            }
                        }
                        if(count($backrecords[1]->subjaddedforauto) > 0)
                        {
                            foreach($backrecords[1]->subjaddedforauto as $customsubjgrade)
                            {
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                                $sheet->setCellValue('A'.$backstartcellno, $customsubjgrade->subjcode);
                                $sheet->setCellValue('I'.$backstartcellno, ucwords(strtolower($customsubjgrade->subjdesc)));
                                $sheet->setCellValue('AT'.$backstartcellno, $customsubjgrade->q1);
                                if($customsubjgrade->q1 < 75) { 
                                    $sheet->getStyle('AT'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                                }
                                $sheet->setCellValue('AY'.$backstartcellno, $customsubjgrade->q2);
                                if($customsubjgrade->q2 < 75) { 
                                    $sheet->getStyle('AY'.$backstartcellno)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                                }
                                $sheet->setCellValue('BD'.$backstartcellno, $customsubjgrade->finalrating);
                                $sheet->setCellValue('BI'.$backstartcellno, $customsubjgrade->actiontaken);
                                $backstartcellno+=1;
                            }
                        }
                        if(count($backrecords[1]->generalaverage)>0)
                        {
                            $sheet->setCellValue('BD'.$backstartcellno, $backrecords[1]->generalaverage[0]->finalrating);
                        }
                        
                    $backstartcellno+=2;
                    
                        $sheet->setCellValue('E'.$backstartcellno, $backrecords[1]->remarks);

                    $backstartcellno+=4;
                        
                        $sheet->setCellValue('A'.$backstartcellno, $backrecords[1]->teachername);
                        $sheet->setCellValue('Y'.$backstartcellno, $backrecords[1]->recordincharge);
                        $sheet->setCellValue('AZ'.$backstartcellno, $backrecords[1]->datechecked);
                        
                    //// F o o t e r
                    $backstartcellno+=18;
                        $firstsemattletter = 12;
                        $secondsemattletter = 41;
                        if($backrecords[0]->attendance>0)
                        {
                            if($backrecords[0]->attendance[0]>0)
                            {
                                foreach($backrecords[0]->attendance[0] as $firstsematt)
                                {
                                    $sheet->setCellValue(getNameFromNumber($firstsemattletter).$backstartcellno, $firstsematt->days);
                                    $firstsemattletter+=4;
                                }
                                $sheet->setCellValue(getNameFromNumber($firstsemattletter).$backstartcellno, collect($backrecords[0]->attendance[0])->sum('days'));
                            }
                            if($backrecords[0]->attendance[1]>0)
                            {
                                foreach($backrecords[0]->attendance[1] as $secondsematt)
                                {
                                    $sheet->setCellValue(getNameFromNumber($secondsemattletter).$backstartcellno, $secondsematt->days);
                                    $secondsemattletter+=4;
                                }
                                $sheet->setCellValue(getNameFromNumber($secondsemattletter).$backstartcellno, collect($backrecords[0]->attendance[1])->sum('days'));
                            }
                        }
                    $backstartcellno+=1;
                        $firstsemattletter = 12;
                        $secondsemattletter = 41;
                        if($backrecords[0]->attendance>0)
                        {
                            if($backrecords[0]->attendance[0]>0)
                            {
                                foreach($backrecords[0]->attendance[0] as $firstsematt)
                                {
                                    $sheet->setCellValue(getNameFromNumber($firstsemattletter).$backstartcellno, $firstsematt->present);
                                    $firstsemattletter+=4;
                                }
                                $sheet->setCellValue(getNameFromNumber($firstsemattletter).$backstartcellno, collect($backrecords[0]->attendance[0])->sum('present'));
                            }
                            if($backrecords[0]->attendance[1]>0)
                            {
                                foreach($backrecords[0]->attendance[1] as $secondsematt)
                                {
                                    $sheet->setCellValue(getNameFromNumber($secondsemattletter).$backstartcellno, $secondsematt->present);
                                    $secondsemattletter+=4;
                                }
                                $sheet->setCellValue(getNameFromNumber($secondsemattletter).$backstartcellno, collect($backrecords[0]->attendance[1])->sum('present'));
                            }
                        }
                    $backstartcellno+=1;
                        $firstsemattletter = 12;
                        $secondsemattletter = 41;
                        if($backrecords[0]->attendance>0)
                        {
                            if($backrecords[0]->attendance[0]>0)
                            {
                                foreach($backrecords[0]->attendance[0] as $firstsematt)
                                {
                                    $sheet->setCellValue(getNameFromNumber($firstsemattletter).$backstartcellno, $firstsematt->absent);
                                    $firstsemattletter+=4;
                                }
                                $sheet->setCellValue(getNameFromNumber($firstsemattletter).$backstartcellno, collect($backrecords[0]->attendance[0])->sum('absent'));
                            }
                            if($backrecords[0]->attendance[1]>0)
                            {
                                foreach($backrecords[0]->attendance[1] as $secondsematt)
                                {
                                    $sheet->setCellValue(getNameFromNumber($secondsemattletter).$backstartcellno, $secondsematt->absent);
                                    $secondsemattletter+=4;
                                }
                                $sheet->setCellValue(getNameFromNumber($secondsemattletter).$backstartcellno, collect($backrecords[0]->attendance[1])->sum('absent'));
                            }
                        }
                    $backstartcellno+=4;

                        $sheet->setCellValue('I'.$backstartcellno, $footer->strandaccomplished);
                        $sheet->setCellValue('BK'.$backstartcellno, $footer->shsgenave);

                    $backstartcellno+=1;

                        $sheet->setCellValue('I'.$backstartcellno, $footer->honorsreceived);
                        $sheet->setCellValue('BI'.$backstartcellno, $footer->shsgraduationdate);

                    $backstartcellno+=3;

                        $sheet->setCellValue('A'.$backstartcellno, $schoolinfo->authorized);
                        $sheet->setCellValue('T'.$backstartcellno, date('m/d/Y'));

                    $backstartcellno+=18;

                        $sheet->setCellValue('A'.$backstartcellno, $footer->copyforupper);

                    $backstartcellno+=2;

                        $sheet->setCellValue('J'.$backstartcellno, date('m/d/Y'));
                    
                

                }
                elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                {
                    
                        //from bottom to top
                    $sheet->setCellValue('F8', $studinfo->lastname);
                    $sheet->setCellValue('Y8', $studinfo->firstname);
                    $sheet->setCellValue('AZ8', $studinfo->middlename);
                    $sheet->setCellValue('C9', $studinfo->lrn);
                    $sheet->setCellValue('AA9', date('m/d/Y', strtotime($studinfo->dob)));
                    $sheet->setCellValue('AN9', $studinfo->gender);

                    
                    if($eligibility->completerhs == 1)
                    {
                        $sheet->setCellValue('A13', '/');
                    }
                    $sheet->setCellValue('N13', $eligibility->genavehs);
                    if($eligibility->completerjh == 1)
                    {
                        $sheet->setCellValue('S13', '/');
                    }
                    $sheet->setCellValue('AH13', $eligibility->genavejh);

                    if($eligibility->graduationdate != null)
                    {
                        $sheet->setCellValue('P14', date('m/d/Y', strtotime($eligibility->graduationdate)));
                    }
                    $sheet->setCellValue('Z14', $eligibility->schoolname);
                    $sheet->setCellValue('AW14', $eligibility->schooladdress);

                    if($eligibility->peptpasser == 1)
                    {
                        $sheet->setCellValue('A16', '/');
                    }
                    $sheet->setCellValue('K16', $eligibility->peptrating);
                    if($eligibility->alspasser == 1)
                    {
                        $sheet->setCellValue('S16', '/');
                    }
                    $sheet->setCellValue('AC16', $eligibility->alsrating);
                    $sheet->setCellValue('AP16', $eligibility->others);

                    if($eligibility->examdate != null)
                    {
                        $sheet->setCellValue('P17',  date('m/d/Y', strtotime($eligibility->examdate)));
                    }
                    $sheet->setCellValue('AM17', $eligibility->centername);
                    
                    $recordsfirstpage = $records[0];
                    if(count($recordsfirstpage)>0)
                    {

                        $firstsem = $recordsfirstpage[0];
                        $secondsem = $recordsfirstpage[1];

                        //ATTENDANCE
                        $firstattendance = $firstsem->attendance;

                        if(count($firstattendance)>0)
                        {
                            if(collect($firstattendance)->where('monthdesc', 'JUNE')->count() > 0)
                            {
                                $sheet->setCellValue('M102', collect($firstattendance)->where('monthdesc', 'JUNE')->first()->numdays);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'JULY')->count() > 0)
                            {
                                $sheet->setCellValue('Q102', collect($firstattendance)->where('monthdesc', 'JULY')->first()->numdays);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'AUGUST')->count() > 0)
                            {
                                $sheet->setCellValue('U102', collect($firstattendance)->where('monthdesc', 'AUGUST')->first()->numdays);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'SEPTEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('Y102', collect($firstattendance)->where('monthdesc', 'SEPTEMBER')->first()->numdays);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'OCTOBER')->count() > 0)
                            {
                                $sheet->setCellValue('AC102', collect($firstattendance)->where('monthdesc', 'OCTOBER')->first()->numdays);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'NOVEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('AG102', collect($firstattendance)->where('monthdesc', 'NOVEMBER')->first()->numdays);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'DECEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('AK102', collect($firstattendance)->where('monthdesc', 'DECEMBER')->first()->numdays);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'JANUARY')->count() > 0)
                            {
                                $sheet->setCellValue('AO102', collect($firstattendance)->where('monthdesc', 'JANUARY')->first()->numdays);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'FEBRUARY')->count() > 0)
                            {
                                $sheet->setCellValue('AS102', collect($firstattendance)->where('monthdesc', 'FEBRUARY')->first()->numdays);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'MARCH')->count() > 0)
                            {
                                $sheet->setCellValue('AW102', collect($firstattendance)->where('monthdesc', 'MARCH')->first()->numdays);
                            }
                            $sheet->setCellValue('BA102', collect($firstattendance)->sum('numdays'));
                            //DAYSPRESENT
                            if(collect($firstattendance)->where('monthdesc', 'JUNE')->count() > 0)
                            {
                                $sheet->setCellValue('M103', collect($firstattendance)->where('monthdesc', 'JUNE')->first()->numdayspresent);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'JULY')->count() > 0)
                            {
                                $sheet->setCellValue('Q103', collect($firstattendance)->where('monthdesc', 'JULY')->first()->numdayspresent);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'AUGUST')->count() > 0)
                            {
                                $sheet->setCellValue('U103', collect($firstattendance)->where('monthdesc', 'AUGUST')->first()->numdayspresent);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'SEPTEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('Y103', collect($firstattendance)->where('monthdesc', 'SEPTEMBER')->first()->numdayspresent);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'OCTOBER')->count() > 0)
                            {
                                $sheet->setCellValue('AC103', collect($firstattendance)->where('monthdesc', 'OCTOBER')->first()->numdayspresent);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'NOVEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('AG103', collect($firstattendance)->where('monthdesc', 'NOVEMBER')->first()->numdayspresent);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'DECEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('AK103', collect($firstattendance)->where('monthdesc', 'DECEMBER')->first()->numdayspresent);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'JANUARY')->count() > 0)
                            {
                                $sheet->setCellValue('AO103', collect($firstattendance)->where('monthdesc', 'JANUARY')->first()->numdayspresent);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'FEBRUARY')->count() > 0)
                            {
                                $sheet->setCellValue('AS103', collect($firstattendance)->where('monthdesc', 'FEBRUARY')->first()->numdayspresent);
                            }
                            if(collect($firstattendance)->where('monthdesc', 'MARCH')->count() > 0)
                            {
                                $sheet->setCellValue('AW103', collect($firstattendance)->where('monthdesc', 'MARCH')->first()->numdayspresent);
                            }
                            $sheet->setCellValue('BA103', collect($firstattendance)->sum('numdayspresent'));
                        }
                        //secondsem
                        $sheet->setCellValue('A93', $secondsem->teachername);
                        $sheet->setCellValue('Y93', $secondsem->recordincharge);
                        $sheet->setCellValue('AZ93', date('m/d/Y',strtotime($secondsem->datechecked)));
                        $sheet->setCellValue('F89', $secondsem->remarks);

                        $secondsemgrades = $secondsem->grades;
                        $secondsemgenave = $secondsem->generalaverage;
                        if(count($secondsemgenave) == 0)
                        {  
                            $secondsemgenave = collect($secondsemgrades)->filter(function($eachgrade){
                                return strstr(strtolower($eachgrade->subjdesc), 'general average');
                            })->values();
                        }
                        
                        if(count($secondsemgenave)>0)
                        {                            
                            $sheet->setCellValue('BD87', $secondsemgenave[0]->finalrating);
                            $sheet->setCellValue('BI87', $secondsemgenave[0]->remarks);
                        }
                        $startcell = 77;
                        if(count($secondsemgrades)>0)
                        {
                            foreach($secondsemgrades as $key=>$secondsemgrade)
                            {
                                if(strtolower($secondsemgrade->subjdesc) != 'general average')
                                {
                                    $sheet->setCellValue('A'.$startcell, $secondsemgrade->subjcode);
                                    $sheet->setCellValue('I'.$startcell, $secondsemgrade->subjdesc);
                                    $sheet->setCellValue('AT'.$startcell, $secondsemgrade->q1);
                                    $sheet->setCellValue('AY'.$startcell, $secondsemgrade->q2);
                                    $sheet->setCellValue('BD'.$startcell, $secondsemgrade->finalrating);
                                    $sheet->setCellValue('BI'.$startcell, $secondsemgrade->remarks);
    
                                    if($startcell > 82)
                                    {                                    
                                        $sheet->insertNewRowBefore(($startcell+1),1);
                                        $sheet->mergeCells('A'.($startcell+1).':H'.($startcell+1));
                                        $sheet->mergeCells('I'.($startcell+1).':AS'.($startcell+1));
                                        $sheet->mergeCells('AT'.($startcell+1).':AX'.($startcell+1));
                                        $sheet->mergeCells('AY'.($startcell+1).':BC'.($startcell+1));
                                        $sheet->mergeCells('BD'.($startcell+1).':BH'.($startcell+1));
                                        $sheet->mergeCells('BI'.($startcell+1).':BO'.($startcell+1));
                                    }
                                    if(isset($secondsemgrades[$key+1]))
                                    {
                                        $startcell += 1;
                                    }
                                }
                            }

                        }
                        
                        $sheet->setCellValue('G71', $secondsem->trackname.'/'.$secondsem->strandname);
                        $sheet->setCellValue('AS71', $secondsem->sectionname);
                        $sheet->setCellValue('E69', $secondsem->schoolname);
                        $sheet->setCellValue('AF69', $secondsem->schoolid);
                        $sheet->setCellValue('BA69', $secondsem->sydesc);

                        //firstsem
                        $sheet->setCellValue('A52', $firstsem->teachername);
                        $sheet->setCellValue('Y52', $firstsem->recordincharge);
                        $sheet->setCellValue('F48', $firstsem->datechecked);
                        $sheet->setCellValue('AZ52', date('m/d/Y',strtotime($firstsem->datechecked)));

                        $firstsemgrades = $firstsem->grades;
                        $firstsemgenave = $firstsem->generalaverage;
                        if(count($firstsemgenave) == 0)
                        {  
                            $firstsemgenave = collect($firstsemgrades)->filter(function($eachgrade){
                                return strstr(strtolower($eachgrade->subjdesc), 'general average');
                            })->values();
                        }
                        
                        if(count($firstsemgenave)>0)
                        {                            
                            $sheet->setCellValue('BD46', $firstsemgenave[0]->finalrating);
                            $sheet->setCellValue('BI46', $firstsemgenave[0]->remarks);
                        }
                        $startcell = 31;
                        if(count($firstsemgrades)>0)
                        {
                            foreach($firstsemgrades as $key=>$firstsemgrade)
                            {
                                if(strtolower($firstsemgrade->subjdesc) != 'general average')
                                {
                                    $sheet->setCellValue('A'.$startcell, $firstsemgrade->subjcode);
                                    $sheet->setCellValue('I'.$startcell, $firstsemgrade->subjdesc);
                                    $sheet->setCellValue('AT'.$startcell, $firstsemgrade->q1);
                                    $sheet->setCellValue('AY'.$startcell, $firstsemgrade->q2);
                                    $sheet->setCellValue('BD'.$startcell, $firstsemgrade->finalrating);
                                    $sheet->setCellValue('BI'.$startcell, $firstsemgrade->remarks);
    
                                    if($startcell > 40)
                                    {                                    
                                        $sheet->insertNewRowBefore(($startcell+1),1);
                                        $sheet->mergeCells('A'.($startcell+1).':H'.($startcell+1));
                                        $sheet->mergeCells('I'.($startcell+1).':AS'.($startcell+1));
                                        $sheet->mergeCells('AT'.($startcell+1).':AX'.($startcell+1));
                                        $sheet->mergeCells('AY'.($startcell+1).':BC'.($startcell+1));
                                        $sheet->mergeCells('BD'.($startcell+1).':BH'.($startcell+1));
                                        $sheet->mergeCells('BI'.($startcell+1).':BO'.($startcell+1));
                                    }
                                    if(isset($firstsemgrades[$key+1]))
                                    {
                                        $startcell += 1;
                                    }
                                }
                            }

                        }
                        
                        $sheet->setCellValue('G25', $secondsem->trackname.'/'.$secondsem->strandname);
                        $sheet->setCellValue('AS25', $secondsem->sectionname);
                        $sheet->setCellValue('E23', $secondsem->schoolname);
                        $sheet->setCellValue('AF23', $secondsem->schoolid);
                        $sheet->setCellValue('BA23', $secondsem->sydesc);

                        
                    }
                    $sheet = $spreadsheet->getSheet(1);
                    $recordssecondpage = $records[1];
                    if(count($recordssecondpage)>0)
                    {

                        $firstsem = $recordssecondpage[0];
                        $secondsem = $recordssecondpage[1];

                        //ATTENDANCE
                        $secondattendance = $firstsem->attendance;

                        if(count($secondattendance)>0)
                        {
                            if(collect($secondattendance)->where('monthdesc', 'JUNE')->count() > 0)
                            {
                                $sheet->setCellValue('M118', collect($secondattendance)->where('monthdesc', 'JUNE')->first()->numdays);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'JULY')->count() > 0)
                            {
                                $sheet->setCellValue('Q118', collect($secondattendance)->where('monthdesc', 'JULY')->first()->numdays);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'AUGUST')->count() > 0)
                            {
                                $sheet->setCellValue('U118', collect($secondattendance)->where('monthdesc', 'AUGUST')->first()->numdays);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'SEPTEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('Y118', collect($secondattendance)->where('monthdesc', 'SEPTEMBER')->first()->numdays);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'OCTOBER')->count() > 0)
                            {
                                $sheet->setCellValue('AC118', collect($secondattendance)->where('monthdesc', 'OCTOBER')->first()->numdays);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'NOVEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('AG118', collect($secondattendance)->where('monthdesc', 'NOVEMBER')->first()->numdays);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'DECEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('AK118', collect($secondattendance)->where('monthdesc', 'DECEMBER')->first()->numdays);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'JANUARY')->count() > 0)
                            {
                                $sheet->setCellValue('AO118', collect($secondattendance)->where('monthdesc', 'JANUARY')->first()->numdays);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'FEBRUARY')->count() > 0)
                            {
                                $sheet->setCellValue('AS118', collect($secondattendance)->where('monthdesc', 'FEBRUARY')->first()->numdays);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'MARCH')->count() > 0)
                            {
                                $sheet->setCellValue('AW118', collect($secondattendance)->where('monthdesc', 'MARCH')->first()->numdays);
                            }
                            $sheet->setCellValue('BA118', collect($secondattendance)->sum('numdays'));
                            //DAYSPRESENT
                            if(collect($secondattendance)->where('monthdesc', 'JUNE')->count() > 0)
                            {
                                $sheet->setCellValue('M119', collect($secondattendance)->where('monthdesc', 'JUNE')->first()->numdayspresent);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'JULY')->count() > 0)
                            {
                                $sheet->setCellValue('Q119', collect($secondattendance)->where('monthdesc', 'JULY')->first()->numdayspresent);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'AUGUST')->count() > 0)
                            {
                                $sheet->setCellValue('U119', collect($secondattendance)->where('monthdesc', 'AUGUST')->first()->numdayspresent);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'SEPTEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('Y119', collect($secondattendance)->where('monthdesc', 'SEPTEMBER')->first()->numdayspresent);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'OCTOBER')->count() > 0)
                            {
                                $sheet->setCellValue('AC119', collect($secondattendance)->where('monthdesc', 'OCTOBER')->first()->numdayspresent);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'NOVEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('AG119', collect($secondattendance)->where('monthdesc', 'NOVEMBER')->first()->numdayspresent);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'DECEMBER')->count() > 0)
                            {
                                $sheet->setCellValue('AK119', collect($secondattendance)->where('monthdesc', 'DECEMBER')->first()->numdayspresent);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'JANUARY')->count() > 0)
                            {
                                $sheet->setCellValue('AO119', collect($secondattendance)->where('monthdesc', 'JANUARY')->first()->numdayspresent);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'FEBRUARY')->count() > 0)
                            {
                                $sheet->setCellValue('AS119', collect($secondattendance)->where('monthdesc', 'FEBRUARY')->first()->numdayspresent);
                            }
                            if(collect($secondattendance)->where('monthdesc', 'MARCH')->count() > 0)
                            {
                                $sheet->setCellValue('AW119', collect($secondattendance)->where('monthdesc', 'MARCH')->first()->numdayspresent);
                            }
                            $sheet->setCellValue('BA119', collect($secondattendance)->sum('numdayspresent'));
                        }
                        
                        $sheet->setCellValue('A94', DB::table('schoolinfo')->first()->authorized);
                        $sheet->setCellValue('I91', $footer->honorsreceived);
                        $sheet->setCellValue('BI91',  $footer->shsgraduationdate);
                        $sheet->setCellValue('I90', $footer->strandaccomplished);
                        $sheet->setCellValue('I90', $footer->strandaccomplished);
                        $sheet->setCellValue('BK90', $footer->shsgenave);

                        //secondsem
                        $sheet->setCellValue('A72', $secondsem->teachername);
                        $sheet->setCellValue('Y72', $secondsem->recordincharge);
                        $sheet->setCellValue('AZ72', date('m/d/Y',strtotime($secondsem->datechecked)));
                        $sheet->setCellValue('F68', $secondsem->remarks);

                        $secondsemgrades = $secondsem->grades;
                        $secondsemgenave = $secondsem->generalaverage;
                        if(count($secondsemgenave) == 0)
                        {  
                            $secondsemgenave = collect($secondsemgrades)->filter(function($eachgrade){
                                return strstr(strtolower($eachgrade->subjdesc), 'general average');
                            })->values();
                        }
                        
                        if(count($secondsemgenave)>0)
                        {                            
                            $sheet->setCellValue('BD66', $secondsemgenave[0]->finalrating);
                            $sheet->setCellValue('BI66', $secondsemgenave[0]->remarks);
                        }
                        $startcell = 46;
                        if(count($secondsemgrades)>0)
                        {
                            foreach($secondsemgrades as $key=>$secondsemgrade)
                            {
                                if(strtolower($secondsemgrade->subjdesc) != 'general average')
                                {
                                    $sheet->setCellValue('A'.$startcell, $secondsemgrade->subjcode);
                                    $sheet->setCellValue('I'.$startcell, $secondsemgrade->subjdesc);
                                    $sheet->setCellValue('AT'.$startcell, $secondsemgrade->q1);
                                    $sheet->setCellValue('AY'.$startcell, $secondsemgrade->q2);
                                    $sheet->setCellValue('BD'.$startcell, $secondsemgrade->finalrating);
                                    $sheet->setCellValue('BI'.$startcell, $secondsemgrade->remarks);
    
                                    if($startcell > 54)
                                    {                                    
                                        $sheet->insertNewRowBefore(($startcell+1),1);
                                        $sheet->mergeCells('A'.($startcell+1).':H'.($startcell+1));
                                        $sheet->mergeCells('I'.($startcell+1).':AS'.($startcell+1));
                                        $sheet->mergeCells('AT'.($startcell+1).':AX'.($startcell+1));
                                        $sheet->mergeCells('AY'.($startcell+1).':BC'.($startcell+1));
                                        $sheet->mergeCells('BD'.($startcell+1).':BH'.($startcell+1));
                                        $sheet->mergeCells('BI'.($startcell+1).':BO'.($startcell+1));
                                    }
                                    if(isset($secondsemgrades[$key+1]))
                                    {
                                        $startcell += 1;
                                    }
                                }
                            }

                        }
                        
                        $sheet->setCellValue('G38', $secondsem->trackname.'/'.$secondsem->strandname);
                        $sheet->setCellValue('AS38', $secondsem->sectionname);
                        $sheet->setCellValue('E37', $secondsem->schoolname);
                        $sheet->setCellValue('AF37', $secondsem->schoolid);
                        $sheet->setCellValue('BA37', $secondsem->sydesc);

                        //firstsem
                        $sheet->setCellValue('A23', $firstsem->teachername);
                        $sheet->setCellValue('Y23', $firstsem->recordincharge);
                        $sheet->setCellValue('AZ23', date('m/d/Y',strtotime($firstsem->datechecked)));
                        $sheet->setCellValue('F19', $firstsem->remarks);

                        $firstsemgrades = $firstsem->grades;
                        $firstsemgenave = $firstsem->generalaverage;
                        if(count($firstsemgenave) == 0)
                        {  
                            $firstsemgenave = collect($firstsemgrades)->filter(function($eachgrade){
                                return strstr(strtolower($eachgrade->subjdesc), 'general average');
                            })->values();
                        }
                        
                        if(count($firstsemgenave)>0)
                        {                            
                            $sheet->setCellValue('BD17', $firstsemgenave[0]->finalrating);
                            $sheet->setCellValue('BI17', $firstsemgenave[0]->remarks);
                        }
                        $startcell = 11;
                        if(count($firstsemgrades)>0)
                        {
                            foreach($firstsemgrades as $key=>$firstsemgrade)
                            {
                                if(strtolower($firstsemgrade->subjdesc) != 'general average')
                                {
                                    $sheet->setCellValue('A'.$startcell, $firstsemgrade->subjcode);
                                    $sheet->setCellValue('I'.$startcell, $firstsemgrade->subjdesc);
                                    $sheet->setCellValue('AT'.$startcell, $firstsemgrade->q1);
                                    $sheet->setCellValue('AY'.$startcell, $firstsemgrade->q2);
                                    $sheet->setCellValue('BD'.$startcell, $firstsemgrade->finalrating);
                                    $sheet->setCellValue('BI'.$startcell, $firstsemgrade->remarks);
    
                                    if($startcell > 15)
                                    {                                    
                                        $sheet->insertNewRowBefore(($startcell+1),1);
                                        $sheet->mergeCells('A'.($startcell+1).':H'.($startcell+1));
                                        $sheet->mergeCells('I'.($startcell+1).':AS'.($startcell+1));
                                        $sheet->mergeCells('AT'.($startcell+1).':AX'.($startcell+1));
                                        $sheet->mergeCells('AY'.($startcell+1).':BC'.($startcell+1));
                                        $sheet->mergeCells('BD'.($startcell+1).':BH'.($startcell+1));
                                        $sheet->mergeCells('BI'.($startcell+1).':BO'.($startcell+1));
                                    }
                                    if(isset($firstsemgrades[$key+1]))
                                    {
                                        $startcell += 1;
                                    }
                                }
                            }

                        }
                        
                        $sheet->setCellValue('G5', $secondsem->trackname.'/'.$secondsem->strandname);
                        $sheet->setCellValue('AS5', $secondsem->sectionname);
                        $sheet->setCellValue('E4', $secondsem->schoolname);
                        $sheet->setCellValue('AF4', $secondsem->schoolid);
                        $sheet->setCellValue('BA4', $secondsem->sydesc);
                    }
                }else
                {
                    $maxgradecount = 12;
                    $numofrecords = 0;
                    // return $inputFileName;

                    // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    // $drawing->setName('Logo');
                    // $drawing->setDescription('Logo');
                    // $drawing->setPath(base_path().'/public/assets/images/department_of_Education.png');
                    // $drawing->setHeight(100);
                    // $drawing->setWorksheet($sheet);
                    // $drawing->setCoordinates('A1');
                    // $drawing->setOffsetX(20);
                    // $drawing->setOffsetY(20);

                    $sheet->setCellValue('F8', $studinfo->lastname);
                    $sheet->setCellValue('Y8', $studinfo->firstname);
                    $sheet->setCellValue('AZ8', $studinfo->middlename);

                    $sheet->setCellValue('C9', $studinfo->lrn);
                    $sheet->getStyle('C9')->getNumberFormat()->setFormatCode('0');
                    $sheet->setCellValue('AA9', date('m/d/Y', strtotime($studinfo->dob)));
                    $sheet->setCellValue('AN9', $studinfo->gender);
                    
                    // E L I G I B I L I T Y
                    // return collect($eligibility);
                    if($eligibility->completerhs == 1)
                    {
                        $sheet->setCellValue('A13', '/');
                    }
                    $sheet->setCellValue('N13', $eligibility->genavehs);
                    if($eligibility->completerjh == 1)
                    {
                        $sheet->setCellValue('S13', '/');
                    }
                    $sheet->setCellValue('AH13', $eligibility->genavejh);

                    if($eligibility->graduationdate != null)
                    {
                        $sheet->setCellValue('P14', date('m/d/Y', strtotime($eligibility->graduationdate)));
                    }
                    $sheet->setCellValue('Z14', $eligibility->schoolname);
                    $sheet->setCellValue('AW14', $eligibility->schooladdress);

                    // $sheet->setCellValue('A16', $eligibility->schoolname);
                    $sheet->getStyle('A16')->getAlignment()->setHorizontal('center');
                    if($eligibility->peptpasser == 1)
                    {
                        $sheet->setCellValue('A16', '/');
                    }
                    $sheet->setCellValue('K16', $eligibility->peptrating);
                    if($eligibility->alspasser == 1)
                    {
                        $sheet->setCellValue('S16', '/');
                    }
                    $sheet->setCellValue('AC16', $eligibility->alsrating);
                    $sheet->setCellValue('AP16', $eligibility->others);
                    
                    if($eligibility->examdate != null)
                    {
                        $sheet->setCellValue('P17',  date('m/d/Y', strtotime($eligibility->examdate)));
                    }
                    $sheet->setCellValue('AN17', $eligibility->centername);


                    $frontrecords = $records[0];
                    
                    foreach($frontrecords as $frontrecord)
                    {
                        foreach($frontrecord as $key => $value)
                        {
                            if($value == null)
                            {   
                                if($key == 'grades' || $key == 'remedials')
                                {
                                    $frontrecord->$key = array();
                                }else{
                                    // $frontrecord->$key = '_______________';
                                }
                                // return $key;
                                // $frontrecord->$key;
                            }
                        }
                    }
                    // return $frontrecords;
                    $frontstartcellno = 23;
                    ///// FIRST GRADES TABLE
                    
                        $sheet->setCellValue('E'.$frontstartcellno, $frontrecords[0]->schoolname);
                        $sheet->setCellValue('AF'.$frontstartcellno, $frontrecords[0]->schoolid);
                        $sheet->setCellValue('AS'.$frontstartcellno, preg_replace('/\D+/', '', $frontrecords[0]->levelname));
                        $sheet->setCellValue('BA'.$frontstartcellno, $frontrecords[0]->sydesc);
                        if($frontrecords[0]->semid == 1)
                        {
                            $sheet->setCellValue('BK'.$frontstartcellno, '1st');
                        }elseif($frontrecords[0]->semid == 2)
                        {
                            $sheet->setCellValue('BK'.$frontstartcellno, '2nd');
                        }
                        
                    $frontstartcellno += 2;

                        $sheet->setCellValue('G'.$frontstartcellno, $frontrecords[0]->strandname);
                        $sheet->setCellValue('AS'.$frontstartcellno, $frontrecords[0]->sectionname);

                    $frontstartcellno += 6;
                    
                        // $sheet->insertNewRowBefore(($frontstartcellno+1), ($maxgradecount-2));
                        
                        if(count(collect($frontrecords[0]->grades)->where('semid',$frontrecords[0]->semid))>0)
                        {
                            $numofrecords += 1;
                            foreach(collect($frontrecords[0]->grades)->where('semid',$frontrecords[0]->semid) as $key => $g11sem1grade)
                            {
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('I'.$frontstartcellno.':AS'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);
                                $sheet->setCellValue('A'.$frontstartcellno, $g11sem1grade->subjcode);
                                $sheet->setCellValue('I'.$frontstartcellno, $g11sem1grade->subjdesc);
                                $sheet->setCellValue('AT'.$frontstartcellno, $g11sem1grade->q1);
                                $sheet->setCellValue('AY'.$frontstartcellno, $g11sem1grade->q2);
                                $sheet->setCellValue('BD'.$frontstartcellno, $g11sem1grade->finalrating);
                                $sheet->setCellValue('BI'.$frontstartcellno, $g11sem1grade->remarks);
                                $frontstartcellno+=1;
                                if($key != collect($frontrecords[0]->grades)->where('semid',$frontrecords[0]->semid)->reverse()->keys()->first())
                                {
                                    $sheet->insertNewRowBefore($frontstartcellno, 1);
                                }
                            }
                            // $sheet->setCellValue('K'.$firstgradescellno, collect($frontrecords[0]->grades)->where('inMAPEH',0)->avg('finalrating'));
                        }else{
                            for($x = 0; $x<$maxgradecount ; $x++)
                            {
                                $frontstartcellno += 1;
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('I'.$frontstartcellno.':AS'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);

                            }
                        }
                        
                        if(count($frontrecords[0]->subjaddedforauto) > 0)
                        {
                            foreach($frontrecords[0]->subjaddedforauto as $customsubjgrade)
                            {
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('I'.$frontstartcellno.':AS'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);
                                $sheet->setCellValue('A'.$frontstartcellno, $customsubjgrade->subjcode);
                                $sheet->setCellValue('I'.$frontstartcellno, $customsubjgrade->subjdesc);
                                $sheet->setCellValue('AT'.$frontstartcellno, $customsubjgrade->q1);
                                $sheet->setCellValue('AY'.$frontstartcellno, $customsubjgrade->q2);
                                $sheet->setCellValue('BD'.$frontstartcellno, $customsubjgrade->finalrating);
                                $sheet->setCellValue('BI'.$frontstartcellno, $customsubjgrade->actiontaken);
                                $frontstartcellno+=1;
                            }
                        }

                    if(count($frontrecords[0]->generalaverage) > 0)
                    {                        
                        $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                        $sheet->setCellValue('BD'.$frontstartcellno, $frontrecords[0]->generalaverage[0]->finalrating);
                    }
                        // return $frontstartcellno;
                        // return $frontrecords[0]->generalaverage;

                    $frontstartcellno+=2;

                        $sheet->setCellValue('E'.$frontstartcellno, $frontrecords[0]->remarks);

                    $frontstartcellno+=4;

                        $sheet->setCellValue('A'.$frontstartcellno, $frontrecords[0]->teachername);
                        $sheet->setCellValue('Y'.$frontstartcellno, $frontrecords[0]->recordincharge);
                        $sheet->setCellValue('AZ'.$frontstartcellno, $frontrecords[0]->datechecked);

                    $frontstartcellno+=17;
                    ///// SECOND GRADES TABLE
                    
                        $sheet->setCellValue('E'.$frontstartcellno, $frontrecords[1]->schoolname);
                        $sheet->setCellValue('AF'.$frontstartcellno, $frontrecords[1]->schoolid);
                        $sheet->setCellValue('AS'.$frontstartcellno, preg_replace('/\D+/', '', $frontrecords[1]->levelname));
                        $sheet->setCellValue('BA'.$frontstartcellno, $frontrecords[1]->sydesc);
                        if($frontrecords[1]->semid == 1)
                        {
                            $sheet->setCellValue('BK'.$frontstartcellno, '1st');
                        }elseif($frontrecords[1]->semid == 2)
                        {
                            $sheet->setCellValue('BK'.$frontstartcellno, '2nd');
                        }
                        
                    $frontstartcellno += 2;

                        $sheet->setCellValue('G'.$frontstartcellno, $frontrecords[1]->strandname);
                        $sheet->setCellValue('AS'.$frontstartcellno, $frontrecords[1]->sectionname);

                    $frontstartcellno += 6;
                    
                        // $sheet->insertNewRowBefore(($frontstartcellno+1), ($maxgradecount-2));
                        
                        if(count(collect($frontrecords[1]->grades)->where('semid',$frontrecords[1]->semid))>0)
                        {
                            $numofrecords += 1;
                            foreach(collect($frontrecords[1]->grades)->where('semid',$frontrecords[1]->semid) as $key => $g11sem2grade)
                            {
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('I'.$frontstartcellno.':AS'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);
                                $sheet->setCellValue('A'.$frontstartcellno, $g11sem2grade->subjcode);
                                $sheet->setCellValue('I'.$frontstartcellno, $g11sem2grade->subjdesc);
                                $sheet->setCellValue('AT'.$frontstartcellno, $g11sem2grade->q1);
                                $sheet->setCellValue('AY'.$frontstartcellno, $g11sem2grade->q2);
                                $sheet->setCellValue('BD'.$frontstartcellno, $g11sem2grade->finalrating);
                                $sheet->setCellValue('BI'.$frontstartcellno, $g11sem2grade->remarks);
                                $frontstartcellno+=1;
                                if($key != collect($frontrecords[1]->grades)->where('semid',$frontrecords[1]->semid)->reverse()->keys()->first())
                                {
                                    $sheet->insertNewRowBefore($frontstartcellno, 1);
                                }
                            }
                            // $sheet->setCellValue('K'.$firstgradescellno, collect($frontrecords[0]->grades)->where('inMAPEH',0)->avg('finalrating'));
                        }else{
                            
                            for($x = 0; $x<$maxgradecount ; $x++)
                            {
                                $frontstartcellno += 1;
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('I'.$frontstartcellno.':AS'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);

                            }
                        }
                        if(count($frontrecords[1]->subjaddedforauto) > 0)
                        {
                            foreach($frontrecords[1]->subjaddedforauto as $customsubjgrade)
                            {
                                $sheet->mergeCells('A'.$frontstartcellno.':H'.$frontstartcellno);
                                $sheet->mergeCells('I'.$frontstartcellno.':AS'.$frontstartcellno);
                                $sheet->mergeCells('AT'.$frontstartcellno.':AX'.$frontstartcellno);
                                $sheet->mergeCells('AY'.$frontstartcellno.':BC'.$frontstartcellno);
                                $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                                $sheet->mergeCells('BI'.$frontstartcellno.':BO'.$frontstartcellno);
                                $sheet->setCellValue('A'.$frontstartcellno, $customsubjgrade->subjcode);
                                $sheet->setCellValue('I'.$frontstartcellno, $customsubjgrade->subjdesc);
                                $sheet->setCellValue('AT'.$frontstartcellno, $customsubjgrade->q1);
                                $sheet->setCellValue('AY'.$frontstartcellno, $customsubjgrade->q2);
                                $sheet->setCellValue('BD'.$frontstartcellno, $customsubjgrade->finalrating);
                                $sheet->setCellValue('BI'.$frontstartcellno, $customsubjgrade->actiontaken);
                                $frontstartcellno+=1;
                            }
                        }

                        if(count($frontrecords[1]->generalaverage) > 0)
                        {                        
                            $sheet->mergeCells('BD'.$frontstartcellno.':BH'.$frontstartcellno);
                            $sheet->setCellValue('BD'.$frontstartcellno, $frontrecords[1]->generalaverage[0]->finalrating);
                        }
                    $frontstartcellno+=2;

                        $sheet->setCellValue('E'.$frontstartcellno, $frontrecords[1]->remarks);

                    $frontstartcellno+=4;

                        $sheet->setCellValue('A'.$frontstartcellno, $frontrecords[1]->teachername);
                        $sheet->setCellValue('Y'.$frontstartcellno, $frontrecords[1]->recordincharge);
                        $sheet->setCellValue('AZ'.$frontstartcellno, $frontrecords[1]->datechecked);

                        

                    $sheet = $spreadsheet->getSheetByName('BACK');

                    $backrecords = $records[1];

                    foreach($backrecords as $backrecord)
                    {
                        foreach($backrecord as $key => $value)
                        {
                            if($value == null)
                            {   
                                if($key == 'grades' || $key == 'remedials')
                                {
                                    $backrecord->$key = array();
                                }else{
                                    // $frontrecord->$key = '_______________';
                                }
                                // return $key;
                                // $frontrecord->$key;
                            }
                        }
                    }
                    // return $backrecords;
                    $backstartcellno = 4;
                    ///// FIRST GRADES TABLE
                    
                        $sheet->setCellValue('E'.$backstartcellno, $backrecords[0]->schoolname);
                        $sheet->setCellValue('AF'.$backstartcellno, $backrecords[0]->schoolid);
                        $sheet->setCellValue('AS'.$backstartcellno, preg_replace('/\D+/', '', $backrecords[0]->levelname));
                        $sheet->setCellValue('BA'.$backstartcellno, $backrecords[0]->sydesc);
                        if($backrecords[0]->semid == 1)
                        {
                            $sheet->setCellValue('BK'.$backstartcellno, '1ST');
                        }elseif($backrecords[0]->semid == 2)
                        {
                            $sheet->setCellValue('BK'.$backstartcellno, '2ND');
                        }
                        
                    $backstartcellno += 1;

                        $sheet->setCellValue('G'.$backstartcellno, $backrecords[0]->strandname);
                        $sheet->setCellValue('AS'.$backstartcellno, $backrecords[0]->sectionname);

                    $backstartcellno += 6;
                    
                        
                        if(count(collect($backrecords[0]->grades)->where('semid',$backrecords[0]->semid))>0)
                        {
                            $numofrecords += 1;
                            $frontcountsubj = 0;
                            foreach(collect($backrecords[0]->grades)->where('semid',$backrecords[0]->semid) as $key => $g12sem1grade)
                            {
                                // return $g12sem1grade->subjcode;
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                                $sheet->setCellValue('A'.$backstartcellno, $g12sem1grade->subjcode);
                                $sheet->setCellValue('I'.$backstartcellno, $g12sem1grade->subjdesc);
                                $sheet->setCellValue('AT'.$backstartcellno, $g12sem1grade->q1);
                                $sheet->setCellValue('AY'.$backstartcellno, $g12sem1grade->q2);
                                $sheet->setCellValue('BD'.$backstartcellno, $g12sem1grade->finalrating);
                                $sheet->setCellValue('BI'.$backstartcellno, $g12sem1grade->remarks);
                                $backstartcellno+=1;
                                $frontcountsubj+=1;
                                if($key != collect($backrecords[0]->grades)->where('semid',$backrecords[0]->semid)->reverse()->keys()->first())
                                {
                                    $sheet->insertNewRowBefore($backstartcellno, 1);
                                }
                            }
                            
                            // for($x = $frontcountsubj; $x<$maxgradecount ; $x++)
                            // {
                            //     $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                            //     $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                            //     $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                            //     $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                            //     $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                            //     $backstartcellno += 1;
                            // }
                            // return $backstartcellno;
                        }else{
                            for($x = 0; $x<$maxgradecount ; $x++)
                            {
                                $backstartcellno += 1;
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);

                            }
                        }
                        if(count($backrecords[0]->subjaddedforauto) > 0)
                        {
                            foreach($backrecords[0]->subjaddedforauto as $customsubjgrade)
                            {
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                                $sheet->setCellValue('A'.$backstartcellno, $customsubjgrade->subjcode);
                                $sheet->setCellValue('I'.$backstartcellno, $customsubjgrade->subjdesc);
                                $sheet->setCellValue('AT'.$backstartcellno, $customsubjgrade->q1);
                                $sheet->setCellValue('AY'.$backstartcellno, $customsubjgrade->q2);
                                $sheet->setCellValue('BD'.$backstartcellno, $customsubjgrade->finalrating);
                                $sheet->setCellValue('BI'.$backstartcellno, $customsubjgrade->actiontaken);
                                $backstartcellno+=1;
                            }
                        }
                        if(count($backrecords[0]->generalaverage)>0)
                        {
                            $sheet->setCellValue('BD'.$backstartcellno, $backrecords[0]->generalaverage[0]->finalrating);
                        }
                        
                    $backstartcellno+=2;

                        $sheet->setCellValue('E'.$backstartcellno, $backrecords[0]->remarks);

                    $backstartcellno+=4;
                    
                        $sheet->setCellValue('A'.$backstartcellno, $backrecords[0]->teachername);
                        $sheet->setCellValue('Y'.$backstartcellno, $backrecords[0]->recordincharge);
                        $sheet->setCellValue('AZ'.$backstartcellno, $backrecords[0]->datechecked);

                    $backstartcellno+=17;
                    ///// SECOND GRADES TABLE
                    
                        $sheet->setCellValue('E'.$backstartcellno, $backrecords[1]->schoolname);
                        $sheet->setCellValue('AF'.$backstartcellno, $backrecords[1]->schoolid);
                        $sheet->setCellValue('AS'.$backstartcellno, preg_replace('/\D+/', '', $backrecords[1]->levelname));
                        $sheet->setCellValue('BA'.$backstartcellno, $backrecords[1]->sydesc);
                        if($backrecords[1]->semid == 1)
                        {
                            $sheet->setCellValue('BK'.$backstartcellno, '1ST');
                        }elseif($backrecords[1]->semid == 2)
                        {
                            $sheet->setCellValue('BK'.$backstartcellno, '2ND');
                        }
                        
                    $backstartcellno += 2;

                        $sheet->setCellValue('G'.$backstartcellno, $backrecords[1]->strandname);
                        $sheet->setCellValue('AS'.$backstartcellno, $backrecords[1]->sectionname);

                    $backstartcellno += 6;
                    
                        // $sheet->insertNewRowBefore(($backstartcellno+1), ($maxgradecount-2));
                        // return $backstartcellno;
                        if(count(collect($backrecords[1]->grades)->where('semid',$backrecords[1]->semid))>0)
                        {
                            $numofrecords += 1;
                            $frontcountsubj = 0;
                            foreach(collect($backrecords[1]->grades)->where('semid',$backrecords[1]->semid) as $key => $g12sem2grade)
                            {
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('I'.$backstartcellno.':AS'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                                $sheet->setCellValue('A'.$backstartcellno, $g12sem2grade->subjcode);
                                $sheet->setCellValue('I'.$backstartcellno, $g12sem2grade->subjdesc);
                                $sheet->setCellValue('AT'.$backstartcellno, $g12sem2grade->q1);
                                $sheet->setCellValue('AY'.$backstartcellno, $g12sem2grade->q2);
                                $sheet->setCellValue('BD'.$backstartcellno, $g12sem2grade->finalrating);
                                $sheet->setCellValue('BI'.$backstartcellno, $g12sem2grade->remarks);
                                $backstartcellno+=1;
                                $frontcountsubj+=1;
                                if($key != collect($backrecords[1]->grades)->where('semid',$backrecords[1]->semid)->reverse()->keys()->first())
                                {
                                    $sheet->insertNewRowBefore($backstartcellno, 1);
                                }
                            }
                            // for($x = $frontcountsubj; $x<$maxgradecount ; $x++)
                            // {
                            //     $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                            //     $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                            //     $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                            //     $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                            //     $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                            //     $backstartcellno += 1;
                            // }
                            // $sheet->setCellValue('K'.$firstgradescellno, collect($backrecords[0]->grades)->where('inMAPEH',0)->avg('finalrating'));
                        }else{
                            for($x = 0; $x<$maxgradecount ; $x++)
                            {
                                $backstartcellno += 1;
                                $sheet->insertNewRowBefore($backstartcellno, 1);
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('I'.$backstartcellno.':AS'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);

                            }
                        }
                        if(count($backrecords[1]->subjaddedforauto) > 0)
                        {
                            foreach($backrecords[1]->subjaddedforauto as $customsubjgrade)
                            {
                                $sheet->mergeCells('A'.$backstartcellno.':H'.$backstartcellno);
                                $sheet->mergeCells('I'.$backstartcellno.':AS'.$backstartcellno);
                                $sheet->mergeCells('AT'.$backstartcellno.':AX'.$backstartcellno);
                                $sheet->mergeCells('AY'.$backstartcellno.':BC'.$backstartcellno);
                                $sheet->mergeCells('BD'.$backstartcellno.':BH'.$backstartcellno);
                                $sheet->mergeCells('BI'.$backstartcellno.':BO'.$backstartcellno);
                                $sheet->setCellValue('A'.$backstartcellno, $customsubjgrade->subjcode);
                                $sheet->setCellValue('I'.$backstartcellno, $customsubjgrade->subjdesc);
                                $sheet->setCellValue('AT'.$backstartcellno, $customsubjgrade->q1);
                                $sheet->setCellValue('AY'.$backstartcellno, $customsubjgrade->q2);
                                $sheet->setCellValue('BD'.$backstartcellno, $customsubjgrade->finalrating);
                                $sheet->setCellValue('BI'.$backstartcellno, $customsubjgrade->actiontaken);
                                $backstartcellno+=1;
                            }
                        }
                        if(count($backrecords[1]->generalaverage)>0)
                        {
                            $sheet->setCellValue('BD'.$backstartcellno, $backrecords[1]->generalaverage[0]->finalrating);
                        }
                        
                    $backstartcellno+=2;
                    
                        $sheet->setCellValue('E'.$backstartcellno, $backrecords[1]->remarks);

                    $backstartcellno+=4;
                        
                        $sheet->setCellValue('A'.$backstartcellno, $backrecords[1]->teachername);
                        $sheet->setCellValue('Y'.$backstartcellno, $backrecords[1]->recordincharge);
                        $sheet->setCellValue('AZ'.$backstartcellno, $backrecords[1]->datechecked);

                    //// F o o t e r
                    $backstartcellno+=19;
                    // return $backstartcellno;
                        $sheet->setCellValue('I'.$backstartcellno, $numofrecords >= 4 ? $footer->strandaccomplished : '');
                        $sheet->setCellValue('BK'.$backstartcellno, $footer->shsgenave);

                    $backstartcellno+=1;

                        $sheet->setCellValue('I'.$backstartcellno, $footer->honorsreceived);
                        $sheet->setCellValue('BI'.$backstartcellno, $footer->shsgraduationdate);

                    $backstartcellno+=5;
                        $sheet->setCellValue('A'.$backstartcellno, $footer->registrar ?? '');
                        $sheet->setCellValue('T'.$backstartcellno, date('m/d/Y'));

                    $backstartcellno+=18;

                        $sheet->setCellValue('A'.$backstartcellno, $footer->copyforupper);

                    $backstartcellno+=2;

                        $sheet->setCellValue('J'.$backstartcellno, date('m/d/Y'));          
                }
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.xlsx"');
                $writer->save("php://output");
                exit;
            }
            
            
        }else{
            $records = collect($records)->sortBy('sort')->values();
            if(count($gradelevels)>0)
            {
                foreach($gradelevels as $gradelevel)
                {
                    $gradelevel->records = collect($records)->where('levelid', $gradelevel->id)->values()->groupBy('sydesc');
                }
            }

            // return $gradelevels;
            // $records = $records->sortBy('levelid')->sortBy('sydesc')->sortBy('semid')->values()->all();
            // return view('registrar.forms.form10.gradessenior')
            // return view('registrar.forms.form10.shs.gradestable')
            
            //return view('registrar.forms.form10.v3.records_shs_other')
            return view('registrar.forms.form10.v3.records_shs')
            ->with('acadprogid', $acadprogid)
            ->with('studentid', $studinfo->id)
            ->with('studinfo', $studinfo)
            ->with('eligibility', $eligibility)
            // return view('registrar.forms.form10.gradeselem')
            ->with('records', $records->sortByDesc('sydesc'))
            ->with('footer', $footer)
            ->with('eachsemsignatories', $eachsemsignatories)
            ->with('gradelevels', collect($gradelevels)->sortBy('sortid'));
        }

    }
    public function submitnewform(Request $request)
    {
        // return $request->all();
        $studentid              = $request->get('studentid');
        $acadprogid             = $request->get('acadprogid');

        $subjects               = json_decode($request->get('subjects'));
        
        if($acadprogid == 2)
        {
            $tablename = 'sf10grades_elem';
        }
        elseif($acadprogid == 3)
        {
            $tablename = 'sf10grades_elem';
        }
        elseif($acadprogid == 4)
        {
            $tablename = 'sf10grades_junior';
        }
        elseif($acadprogid == 5)
        {
            $tablename = 'sf10grades_senior';
        }
        if($acadprogid == 3 || $acadprogid == 4)
        {
            $credit_advance            = $request->get('credit_advance');
            $credit_lacks            = $request->get('credit_lacks');
            $noofyears            = $request->get('noofyears');
            
            $schoolname             = $request->get('schoolname');
            $schoolid               = $request->get('schoolid');
            $schooldistrict         = $request->get('district');
            $schooldivision         = $request->get('division');
            $schoolregion           = $request->get('region');
            $gradelevelid           = $request->get('gradelevelid');
            $sectionname            = $request->get('sectionname');
            $schoolyear             = $request->get('schoolyear');
            $teachername            = $request->get('teachername');
            $generalaverageval      = $request->get('generalaverageval');

            $checkifexists          = DB::table('sf10')
                                        ->where('studid',$studentid)
                                        ->where('sydesc',$schoolyear)
                                        ->where('levelid',$gradelevelid)
                                        ->where('deleted','0')
                                        ->first();
            
            if($checkifexists)
            {
                $gethederid = $checkifexists->id;
            }else{
                $gethederid             = DB::table('sf10')
                                            ->insertGetId([
                                                'studid'            =>  $studentid,
                                                'syid'              =>  null,
                                                'sydesc'            =>  $schoolyear,
                                                'yearfrom'          =>  null,
                                                'yearto'            =>  null,
                                                'levelid'           =>  $gradelevelid,
                                                'levelname'         =>  null,
                                                'sectionid'         =>  null,
                                                'sectionname'       =>  $sectionname,
                                                'teachername'       =>  $teachername,
                                                'principalname'     =>  null,
                                                'acadprogid'        =>  $acadprogid,
                                                'schoolid'          =>  $schoolid,
                                                'schoolname'        =>  $schoolname,
                                                'schooladdress'     =>  null,
                                                'schooldistrict'    =>  $schooldistrict,
                                                'schooldivision'    =>  $schooldivision,
                                                'schoolregion'      =>  $schoolregion,
                                                'unitsearned'       =>  null,
                                                'noofyears'         =>  null,
                                                'remarks'           =>  $request->get('remarks'),
                                                'recordincharge'    =>  $request->get('recordsincharge'),
                                                'datechecked'       =>  $request->get('datechecked'),
                                                'credit_advance'    =>  $credit_advance,
                                                'credit_lack'       =>  $credit_lacks,
                                                'noofyears'         =>  $noofyears,
                                                'createdby'         =>  auth()->user()->id,
                                                'createddatetime'   =>  date('Y-m-d H:i:s')
                                            ]);
            }

            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                        if(!$request->has('intle'))
                        {
                            $subject->intle = 0;
                        }
                        if(!$request->has('inmapeh'))
                        {
                            $subject->inmapeh = 0;
                        }
                        
                        DB::table($tablename)
                            ->insert([
                                'headerid'          =>  $gethederid,
                                'subjectid'         =>  null,
                                'subjectname'       =>  $subject->subjdesc,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'q3'                =>  $subject->q3,
                                'q4'                =>  $subject->q4,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                'credits'           =>  $subject->credits,
                                'fromsystem'        =>  $subject->fromsystem,
                                'editablegrades'    =>  $subject->editablegrades,
                                'inTLE'             =>  $subject->intle,
                                'inMAPEH'           =>  $subject->indentsubj,
                                'createdby'         =>  auth()->user()->id,
                                'createddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                }
                return 1;
            }else{                
                
                return 0;
            }

            
        }elseif($acadprogid == 5)
        {

            // return $subjects;
            $schoolname             = $request->get('schoolname');
            $schoolid               = $request->get('schoolid');
            $gradelevelid           = $request->get('gradelevelid');
            $trackname              = $request->get('trackname');
            $strandname             = $request->get('strandname');
            $sectionname            = $request->get('sectionname');
            $schoolyear             = $request->get('schoolyear');
            $semester               = $request->get('semester');
            $teachername            = $request->get('teachername');
            $recordsincharge        = $request->get('recordsincharge');
            // $indications            = $request->get('indications');
            // $subjects               = $request->get('subjects');
            // $q1                     = $request->get('q1');
            // $q2                     = $request->get('q2');
            // $final                  = $request->get('final');
            // $remarks                = $request->get('remarks');
            $generalaverageval      = $request->get('generalaverageval');
            $generalaveragerem      = $request->get('generalaveragerem');
            $semesterremarks        = $request->get('semesterremarks');
            $datechecked            = $request->get('datechecked');
            
            $checkifexists          = DB::table('sf10')
                                        ->where('studid',$studentid)
                                        ->where('sydesc',$schoolyear)
                                        ->where('levelid',$gradelevelid)
                                        ->where('semid',$semester)
                                        ->where('deleted','0')
                                        ->first();
            
            if($checkifexists)
            {
                $gethederid = $checkifexists->id;
            }else{
                $gethederid             = DB::table('sf10')
                                            ->insertGetId([
                                                'studid'            =>  $studentid,
                                                'syid'              =>  null,
                                                'sydesc'            =>  $schoolyear,
                                                'yearfrom'          =>  null,
                                                'yearto'            =>  null,
                                                'semid'             =>  $semester,
                                                'levelid'           =>  $gradelevelid,
                                                'levelname'         =>  null,
                                                'sectionid'         =>  null,
                                                'sectionname'       =>  $sectionname,
                                                'trackid'           =>  null,
                                                'trackname'         =>  $trackname,
                                                'strandid'          =>  null,
                                                'strandname'        =>  $strandname,
                                                'teachername'       =>  $teachername,
                                                'principalname'     =>  null,
                                                'acadprogid'        =>  $acadprogid,
                                                'schoolid'          =>  $schoolid,
                                                'schoolname'        =>  $schoolname,
                                                'schooladdress'     =>  null,
                                                'unitsearned'       =>  null,
                                                'noofyears'         =>  null,
                                                'remarks'           =>  $semesterremarks,
                                                'recordincharge'    =>  $recordsincharge,
                                                'datechecked'       =>  $datechecked,
                                                'createdby'         =>  auth()->user()->id,
                                                'createddatetime'   =>  date('Y-m-d H:i:s')
                                            ]);
            }
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    try{
                        if(!$request->has('intle'))
                        {
                            $subject->intle = 0;
                        }
                        if(!$request->has('inmapeh'))
                        {
                            $subject->inmapeh = 0;
                        }
                        
                        DB::table('sf10grades_senior')
                            ->insert([
                                'headerid'          =>  $gethederid,
                                'subjdesc'          =>  $subject->subjdesc,
                                'subjcode'          =>  $subject->subjcode,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                'inMAPEH'           =>  $subject->inmapeh,
                                'inTLE'           =>  $subject->intle,
                                'fromsystem'        =>  $subject->fromsystem,
                                'editablegrades'        =>  $subject->editablegrades,
                                'createdby'         =>  auth()->user()->id,
                                'createddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                    }catch(\Exception $error)
                    {
                        // DB::table('sf10grades_senior')
                        //     ->insert([
                        //         'headerid'          =>  $gethederid,
                        //         'subjdesc'          =>  $subject['subjdesc'],
                        //         'subjcode'          =>  $subject['subjcode'],
                        //         'q1'                =>  $subject['q1'],
                        //         'q2'                =>  $subject['q2'],
                        //         'finalrating'       =>  $subject['final'],
                        //         'remarks'           =>  $subject['remarks'],
                        //         'inMAPEH'           =>  $subject['inmapeh'],
                        //         'inTLE'           =>  $subject['intle'],
                        //         'fromsystem'        =>  $subject['fromsystem'],
                        //         'editablegrades'        =>  $subject['editablegrades'],
                        //         'createdby'         =>  auth()->user()->id,
                        //         'createddatetime'   =>  date('Y-m-d H:i:s')
                        //     ]);
                    }
                }
                return 1;
            }else{
                return 0;
            }
        }
        
    }
    public function updateform(Request $request)
    {
        // return $request->all();
        $recordid              = $request->get('id');
        $studentid              = $request->get('studentid');
        $acadprogid             = $request->get('acadprogid');
        $schoolname             = $request->get('schoolname');
        $schoolid               = $request->get('schoolid');
        $gradelevelid           = $request->get('gradelevelid');
        $trackname              = $request->get('trackname');
        $strandname             = $request->get('strandname');
        $sectionname            = $request->get('sectionname');
        $schoolyear             = $request->get('schoolyear');
        $semester               = $request->get('semester');
        $teachername            = $request->get('teachername');
        $recordsincharge        = $request->get('recordsincharge');
        // $indications            = $request->get('indications');
        // $subjects               = $request->get('subjects');
        // $q1                     = $request->get('q1');
        // $q2                     = $request->get('q2');
        // $final                  = $request->get('final');
        // $remarks                = $request->get('remarks');
        $generalaverageval      = $request->get('generalaverageval');
        $generalaveragerem      = $request->get('generalaveragerem');
        $datechecked            = $request->get('datechecked');
        $credit_advance            = $request->get('credit_advance');
        $credit_lacks            = $request->get('credit_lacks');
        $noofyears            = $request->get('noofyears');

        $subjects               = json_decode($request->get('subjects'));
        
        if($acadprogid == 5)
        {
            $semesterremarks        = $request->get('semesterremarks');
            DB::table('sf10')
            ->where('id', $recordid)
            ->update([
                'sydesc'            =>  $schoolyear,
                'semid'             =>  $semester,
                'levelid'           =>  $gradelevelid,
                'levelname'         =>  null,
                'sectionid'         =>  null,
                'sectionname'       =>  $sectionname,
                'trackid'           =>  null,
                'trackname'         =>  $trackname,
                'strandid'          =>  null,
                'strandname'        =>  $strandname,
                'teachername'       =>  $teachername,
                'principalname'     =>  null,
                'acadprogid'        =>  $acadprogid,
                'schoolid'          =>  $schoolid,
                'schoolname'        =>  $schoolname,
                'schooladdress'     =>  null,
                'unitsearned'       =>  null,
                'noofyears'         =>  null,
                'remarks'           =>  $semesterremarks,
                'recordincharge'    =>  $recordsincharge,
                'datechecked'       =>  $datechecked,
                'updatedby'         =>  auth()->user()->id,
                'updateddatetime'   =>  date('Y-m-d H:i:s')
            ]);
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    if($subject->id == 0)
                    {
                        DB::table('sf10grades_senior')
                            ->insert([
                                'headerid'          =>  $recordid,
                                'subjdesc'          =>  $subject->subjdesc,
                                'subjcode'          =>  $subject->subjcode,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                'inMAPEH'           =>  $subject->inmapeh,
                                // 'inTLE'           =>  $subject->intle,
                                // 'fromsystem'        =>  $subject->fromsystem,
                                // 'editablegrades'        =>  $subject->editablegrades,
                                'createdby'         =>  auth()->user()->id,
                                'createddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                    }else{
                        DB::table('sf10grades_senior')
                            ->where('id', $subject->id)
                            ->update([
                                'subjdesc'          =>  $subject->subjdesc,
                                'subjcode'          =>  $subject->subjcode,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                'inMAPEH'           =>  $subject->inmapeh,
                                // 'inTLE'           =>  $subject->intle,
                                // 'fromsystem'        =>  $subject->fromsystem,
                                // 'editablegrades'        =>  $subject->editablegrades,
                                'updatedby'         =>  auth()->user()->id,
                                'updateddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                    }
                    
                }
            }
        }
        elseif($acadprogid == 4)
        {
            $remarks        = $request->get('remarks');
            $schooldistrict        = $request->get('district');
            $schooldivision        = $request->get('division');
            $schoolregion        = $request->get('region');
            // return $request->all();
            DB::table('sf10')
            ->where('id', $recordid)
            ->update([
                'sydesc'            =>  $schoolyear,
                'semid'             =>  $semester,
                'levelid'           =>  $gradelevelid,
                'levelname'         =>  null,
                'sectionid'         =>  null,
                'sectionname'       =>  $sectionname,
                'teachername'       =>  $teachername,
                'principalname'     =>  null,
                'acadprogid'        =>  $acadprogid,
                'schoolid'          =>  $schoolid,
                'schoolname'        =>  $schoolname,
                'schooldistrict'    =>  $schooldistrict,
                'schooldivision'    =>  $schooldivision,
                'schoolregion'      =>  $schoolregion,
                'schooladdress'     =>  null,
                'unitsearned'       =>  null,
                'noofyears'         =>  null,
                'remarks'           =>  $remarks,
                'recordincharge'    =>  $recordsincharge,
                'datechecked'       =>  $datechecked,
                'credit_advance'    =>  $credit_advance,
                'credit_lack'       =>  $credit_lacks,
                'noofyears'         =>  $noofyears,
                'updatedby'         =>  auth()->user()->id,
                'updateddatetime'   =>  date('Y-m-d H:i:s')
            ]);
            
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    if($subject->id == 0)
                    {
                        DB::table('sf10grades_junior')
                            ->insert([
                                'headerid'          =>  $recordid,
                                'subjectname'          =>  $subject->subjdesc,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'q3'                =>  $subject->q3,
                                'q4'                =>  $subject->q4,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                'credits'           =>  $subject->credits,
                                'inMAPEH'           =>  $subject->indentsubj,
                                // 'inTLE'           =>  $subject->intle,
                                // 'fromsystem'        =>  $subject->fromsystem,
                                // 'editablegrades'        =>  $subject->editablegrades,
                                'createdby'         =>  auth()->user()->id,
                                'createddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                    }else{
                        DB::table('sf10grades_junior')
                            ->where('id', $subject->id)
                            ->update([
                                'subjectname'          =>  $subject->subjdesc,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'q3'                =>  $subject->q3,
                                'q4'                =>  $subject->q4,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                'credits'           =>  $subject->credits,
                                'inMAPEH'           =>  $subject->indentsubj,
                                // 'inTLE'           =>  $subject->intle,
                                // 'fromsystem'        =>  $subject->fromsystem,
                                // 'editablegrades'        =>  $subject->editablegrades,
                                'updatedby'         =>  auth()->user()->id,
                                'updateddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                    }
                    
                }
            }
        }
        elseif($acadprogid == 3)
        {
            // return $subjects;
            $remarks        = $request->get('remarks');
            $schooldistrict        = $request->get('district');
            $schooldivision        = $request->get('division');
            $schoolregion        = $request->get('region');
            // return $request->all();
            DB::table('sf10')
            ->where('id', $recordid)
            ->update([
                'sydesc'            =>  $schoolyear,
                'semid'             =>  $semester,
                'levelid'           =>  $gradelevelid,
                'levelname'         =>  null,
                'sectionid'         =>  null,
                'sectionname'       =>  $sectionname,
                'teachername'       =>  $teachername,
                'principalname'     =>  null,
                'acadprogid'        =>  $acadprogid,
                'schoolid'          =>  $schoolid,
                'schoolname'        =>  $schoolname,
                'schooldistrict'    =>  $schooldistrict,
                'schooldivision'    =>  $schooldivision,
                'schoolregion'      =>  $schoolregion,
                'schooladdress'     =>  null,
                'unitsearned'       =>  null,
                'noofyears'         =>  null,
                'remarks'           =>  $remarks,
                'recordincharge'    =>  $recordsincharge,
                'datechecked'       =>  $datechecked,
                'credit_advance'    =>  $credit_advance,
                'credit_lack'       =>  $credit_lacks,
                'noofyears'         =>  $noofyears,
                'updatedby'         =>  auth()->user()->id,
                'updateddatetime'   =>  date('Y-m-d H:i:s')
            ]);
            
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    if($subject->id == 0)
                    {
                        DB::table('sf10grades_elem')
                            ->insert([
                                'headerid'          =>  $recordid,
                                'subjectname'          =>  $subject->subjdesc,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'q3'                =>  $subject->q3,
                                'q4'                =>  $subject->q4,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                'credits'           =>  $subject->credits,
                                'inMAPEH'           =>  $subject->indentsubj,
                                // 'inTLE'           =>  $subject->intle,
                                // 'fromsystem'        =>  $subject->fromsystem,
                                // 'editablegrades'        =>  $subject->editablegrades,
                                'createdby'         =>  auth()->user()->id,
                                'createddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                    }else{
                        DB::table('sf10grades_elem')
                            ->where('id', $subject->id)
                            ->update([
                                'subjectname'          =>  $subject->subjdesc,
                                'q1'                =>  $subject->q1,
                                'q2'                =>  $subject->q2,
                                'q3'                =>  $subject->q3,
                                'q4'                =>  $subject->q4,
                                'finalrating'       =>  $subject->final,
                                'remarks'           =>  $subject->remarks,
                                'credits'           =>  $subject->credits,
                                'inMAPEH'           =>  $subject->indentsubj,
                                // 'inTLE'           =>  $subject->intle,
                                // 'fromsystem'        =>  $subject->fromsystem,
                                // 'editablegrades'        =>  $subject->editablegrades,
                                'updatedby'         =>  auth()->user()->id,
                                'updateddatetime'   =>  date('Y-m-d H:i:s')
                            ]);
                    }
                    
                }
            }
        }
        return 1;
    }
    public function updateattendance(Request $request)
    {
        $studentid = $request->get('studentid');
        $acadprogid = $request->get('acadprogid');
        $semid = $request->get('semid');
        $sydesc = $request->get('sydesc');
        $action = $request->get('action');
        $id = $request->get('id');
        $attendance = json_decode($request->get('attendance'));
        // return $attendance;
        $months = collect($attendance)->pluck('monthdesc')->toArray();
        if(!$request->has('action')) //toschools that's not updated to the latest version of sf10
        {
            if(count($attendance)==0)
            {
                
                $existing = DB::table('sf10attendance')
                    ->where('headerid', $id)
                    ->where('acadprogid', $acadprogid)
                    ->where('deleted','0')
                    ->get();
        
                if(count($existing)>0)
                {
                    // return $months;
                    foreach($existing as $ex)
                    {
                        if($acadprogid == 5)
                        {
                            DB::table('sf10attendance')
                                ->where('studentid', $request->get('studentid'))
                                ->where('sydesc', $request->get('sydesc'))
                                ->update([
                                    'deleted'           => 1,
                                    'deletedby'         => auth()->user()->id,
                                    'deleteddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }else{
                            DB::table('sf10attendance')
                                ->where('headerid', $id)
                                ->where('acadprogid', $acadprogid)
                                ->update([
                                    'deleted'           => 1,
                                    'deletedby'         => auth()->user()->id,
                                    'deleteddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }
            }else{
                
                if($acadprogid == 5)
                {
                    // return  DB::table('sf10attendance')->get();
                    
                    $existing = DB::table('sf10attendance')
                        ->where('studentid', $request->get('studentid'))
                        ->where('sydesc', $request->get('sydesc'))
                        ->where('acadprogid', $acadprogid)
                        ->where('deleted','0')
                        ->get();
                        
                    if(count($existing)>0)
                    {
                        // return $months;
                        foreach($existing as $ex)
                        {
                            if (!in_array($ex->monthdesc, $months)) {
                                DB::table('sf10attendance')
                                    ->where('id', $ex->id)
                                    ->update([
                                        'deleted'           => 1,
                                        'deletedby'         => auth()->user()->id,
                                        'deleteddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                    
                    foreach($attendance as $att)
                    {
                        $checkifexists = DB::table('sf10attendance')
                            ->where('studentid', $request->get('studentid'))
                            ->where('sydesc', $request->get('sydesc'))
                            ->where('acadprogid', $acadprogid)
                            ->where('monthdesc','like','%'.$att->monthdesc.'%')
                            ->where('deleted','0')
                            ->first();   
                            
                        if($checkifexists)
                        {
                            DB::table('sf10attendance')
                                ->where('id', $checkifexists->id)
                                ->update([
                                    'monthdesc'       => $att->monthdesc,
                                    'numdays'         => $att->numdays,
                                    'numdayspresent'  => $att->numdayspresent,
                                    'updatedby'       => auth()->user()->id,
                                    'updateddatetime' => date('Y-m-d H:i:s')
                                ]);
                        }else{
                            DB::table('sf10attendance')
                                ->insert([
                                    'studentid'       => $request->get('studentid'), 
                                    'sydesc'          => $request->get('sydesc'), 
                                    'acadprogid'      => $acadprogid,
                                    'monthdesc'       => $att->monthdesc,
                                    'numdays'         => $att->numdays,
                                    'numdayspresent'  => $att->numdayspresent,
                                    'createdby'       => auth()->user()->id,
                                    'createddatetime' => date('Y-m-d H:i:s')
                                ]);
    
                        }
                    }
                }else{
                    
                    $existing = DB::table('sf10attendance')
                        ->where('headerid', $id)
                        ->where('acadprogid', $acadprogid)
                        ->where('deleted','0')
                        ->get();
    
                    if(count($existing)>0)
                    {
                        // return $months;
                        foreach($existing as $ex)
                        {
                            if (!in_array($ex->monthdesc, $months)) {
                                DB::table('sf10attendance')
                                    ->where('id', $ex->id)
                                    ->update([
                                        'deleted'           => 1,
                                        'deletedby'         => auth()->user()->id,
                                        'deleteddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                    
                    foreach($attendance as $att)
                    {
                        $checkifexists = DB::table('sf10attendance')
                            ->where('headerid', $id)
                            ->where('acadprogid', $acadprogid)
                            ->where('monthdesc','like','%'.$att->monthdesc.'%')
                            ->where('deleted','0')
                            ->first();   
                            
                        if($checkifexists)
                        {
                            DB::table('sf10attendance')
                                ->where('id', $checkifexists->id)
                                ->update([
                                    'monthdesc'       => $att->monthdesc,
                                    'numdays'         => $att->schooldays,
                                    'numdayspresent'  => $att->dayspresent,
                                    'numdaysabsent'   => $att->daysabsent,
                                    'numtimestardy'   => $att->timestardy,
                                    'updatedby'       => auth()->user()->id,
                                    'updateddatetime' => date('Y-m-d H:i:s')
                                ]);
                        }else{
                            DB::table('sf10attendance')
                                ->insert([
                                    'headerid'        => $id,
                                    'acadprogid'      => $acadprogid,
                                    'monthdesc'       => $att->monthdesc,
                                    'numdays'         => $att->schooldays,
                                    'numdayspresent'  => $att->dayspresent,
                                    'numdaysabsent'   => $att->daysabsent,
                                    'numtimestardy'   => $att->timestardy,
                                    'createdby'       => auth()->user()->id,
                                    'createddatetime' => date('Y-m-d H:i:s')
                                ]);
    
                        }
                    }
                }
            }
        }else{
            if($action == 'reset')
            {
                if($acadprogid == 5)
                {
                    DB::table('sf10attendance')
                        ->where('studentid', $request->get('studentid'))
                        ->where('sydesc', $request->get('sydesc'))
                        ->where('semid', $request->get('semid'))
                        ->update([
                            'deleted'           => 1,
                            'deletedby'         => auth()->user()->id,
                            'deleteddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }else{
                if(count($attendance)==0)
                {
                    
                    $existing = DB::table('sf10attendance')
                        ->where('headerid', $id)
                        ->where('acadprogid', $acadprogid)
                        ->where('deleted','0')
                        ->get();
            
                    if(count($existing)>0)
                    {
                        // return $months;
                        foreach($existing as $ex)
                        {
                            if($acadprogid == 5)
                            {
                                DB::table('sf10attendance')
                                    ->where('studentid', $request->get('studentid'))
                                    ->where('sydesc', $request->get('sydesc'))
                                    ->where('semid', $request->get('semid'))
                                    ->update([
                                        'deleted'           => 1,
                                        'deletedby'         => auth()->user()->id,
                                        'deleteddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }else{
                                DB::table('sf10attendance')
                                    ->where('headerid', $id)
                                    ->where('acadprogid', $acadprogid)
                                    ->update([
                                        'deleted'           => 1,
                                        'deletedby'         => auth()->user()->id,
                                        'deleteddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                }else{
                    
                    if($acadprogid == 5)
                    {
                        // return  DB::table('sf10attendance')->get();
                        
                        $existing = DB::table('sf10attendance')
                            ->where('studentid', $request->get('studentid'))
                            ->where('sydesc', $request->get('sydesc'))
                            ->where('semid', $semid)
                            ->where('acadprogid', $acadprogid)
                            ->where('deleted','0')
                            ->get();
                            
                        if(count($existing)>0)
                        {
                            // return $months;
                            foreach($existing as $ex)
                            {
                                if (!in_array($ex->monthdesc, $months)) {
                                    DB::table('sf10attendance')
                                        ->where('id', $ex->id)
                                        ->update([
                                            'deleted'           => 1,
                                            'deletedby'         => auth()->user()->id,
                                            'deleteddatetime'   => date('Y-m-d H:i:s')
                                        ]);
                                }
                            }
                        }
                        
                        foreach($attendance as $att)
                        {
                            $checkifexists = DB::table('sf10attendance')
                                ->where('studentid', $request->get('studentid'))
                                ->where('sydesc', $request->get('sydesc'))
                                ->where('semid', $semid)
                                ->where('acadprogid', $acadprogid)
                                ->where('monthdesc','like','%'.$att->monthdesc.'%')
                                ->where('deleted','0')
                                ->first();   
                                
                            if($checkifexists)
                            {
                                DB::table('sf10attendance')
                                    ->where('id', $checkifexists->id)
                                    ->update([
                                        'monthdesc'       => $att->monthdesc,
                                        'numdays'         => $att->numdays ?? $att->schooldays,
                                        'numdayspresent'  => $att->numdayspresent ?? $att->dayspresent,
                                        'numdaysabsent'  => $att->numdaysabsent ?? $att->daysabsent,
                                        'numtimestardy'  => $att->timestardy ?? '',
                                        'updatedby'       => auth()->user()->id,
                                        'updateddatetime' => date('Y-m-d H:i:s')
                                    ]);
                            }else{
                                DB::table('sf10attendance')
                                    ->insert([
                                        'studentid'       => $request->get('studentid'), 
                                        'sydesc'          => $request->get('sydesc'), 
                                        'semid'           => $semid, 
                                        'acadprogid'      => $acadprogid,
                                        'monthdesc'       => $att->monthdesc,
                                        'numdays'         => $att->numdays ?? $att->schooldays,
                                        'numdayspresent'  => $att->numdayspresent ?? $att->dayspresent,
                                        'numdaysabsent'  => $att->numdaysabsent ?? $att->daysabsent,
                                        'numtimestardy'  => $att->timestardy ?? '',
                                        'createdby'       => auth()->user()->id,
                                        'createddatetime' => date('Y-m-d H:i:s')
                                    ]);
        
                            }
                        }
                    }else{
                        
                        $existing = DB::table('sf10attendance')
                            ->where('headerid', $id)
                            ->where('acadprogid', $acadprogid)
                            ->where('deleted','0')
                            ->get();
        
                        if(count($existing)>0)
                        {
                            // return $months;
                            foreach($existing as $ex)
                            {
                                if (!in_array($ex->monthdesc, $months)) {
                                    DB::table('sf10attendance')
                                        ->where('id', $ex->id)
                                        ->update([
                                            'deleted'           => 1,
                                            'deletedby'         => auth()->user()->id,
                                            'deleteddatetime'   => date('Y-m-d H:i:s')
                                        ]);
                                }
                            }
                        }
                        
                        foreach($attendance as $att)
                        {
                            $checkifexists = DB::table('sf10attendance')
                                ->where('headerid', $id)
                                ->where('acadprogid', $acadprogid)
                                ->where('monthdesc','like','%'.$att->monthdesc.'%')
                                ->where('deleted','0')
                                ->first();   
                                
                            if($checkifexists)
                            {
                                DB::table('sf10attendance')
                                    ->where('id', $checkifexists->id)
                                    ->update([
                                        'monthdesc'       => $att->monthdesc,
                                        'numdays'         => $att->schooldays,
                                        'numdayspresent'  => $att->dayspresent,
                                        'numdaysabsent'   => $att->daysabsent,
                                        'numtimestardy'   => $att->timestardy,
                                        'updatedby'       => auth()->user()->id,
                                        'updateddatetime' => date('Y-m-d H:i:s')
                                    ]);
                            }else{
                                DB::table('sf10attendance')
                                    ->insert([
                                        'headerid'        => $id,
                                        'acadprogid'      => $acadprogid,
                                        'monthdesc'       => $att->monthdesc,
                                        'numdays'         => $att->schooldays,
                                        'numdayspresent'  => $att->dayspresent,
                                        'numdaysabsent'   => $att->daysabsent,
                                        'numtimestardy'   => $att->timestardy,
                                        'createdby'       => auth()->user()->id,
                                        'createddatetime' => date('Y-m-d H:i:s')
                                    ]);
        
                            }
                        }
                    }
                }
            }
        }
        return 1;
    }
    
    public function record_delete(Request $request)
    {
        if($request->has('action'))
        {
            // return $request->all();
            if($request->get('acadprogid') == 5)
            {
                
                DB::table('sf10grades_senior')
                    ->where('id', $request->get('id'))
                    ->update([
                        'deleted'           => 1,
                        'deletedby'         =>  auth()->user()->id,
                        'deleteddatetime'   =>  date('Y-m-d H:i:s')
                    ]);

                return 1;
            }
            elseif($request->get('acadprogid') == 4)
            {
                
                DB::table('sf10grades_junior')
                    ->where('id', $request->get('id'))
                    ->update([
                        'deleted'           => 1,
                        'deletedby'         =>  auth()->user()->id,
                        'deleteddatetime'   =>  date('Y-m-d H:i:s')
                    ]);

                return 1;
            }
            elseif($request->get('acadprogid') == 3)
            {
                
                DB::table('sf10grades_elem')
                    ->where('id', $request->get('id'))
                    ->update([
                        'deleted'           => 1,
                        'deletedby'         =>  auth()->user()->id,
                        'deleteddatetime'   =>  date('Y-m-d H:i:s')
                    ]);

                return 1;
            }
        }else{
            DB::table('sf10')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }
    }
    public function updatesigneachsem(Request $request)
    { 
        date_default_timezone_set('Asia/Manila');
        $checkifexists = DB::table('sf10bylevelsign')
            ->where('studid', $request->get('studentid'))
            ->where('levelid', $request->get('levelid'))
            ->where('semid', $request->get('semid'))
            ->where('sydesc', $request->get('sydesc'))
            ->where('deleted','0')
            ->first();

        if($checkifexists)
        {
            DB::table('sf10bylevelsign')
            ->where('id', $checkifexists->id)
            ->update([
                'remarks'  => $request->get('remarks'),
                'teachername'  => $request->get('teachername'),
                // 'teacherdesc'  => $request->get('teacherdesc'),
                'certncorrectdesc'  => $request->get('certncorrectdesc'),
                'datechecked'       => $request->get('datechecked'),
                'updatedby'         => auth()->user()->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);
        }else{
            DB::table('sf10bylevelsign')
            ->insert([
                'remarks'  => $request->get('remarks'),
                'teachername'  => $request->get('teachername'),
                // 'teacherdesc'  => $request->get('teacherdesc'),
                'studid'            => $request->get('studentid'),
                'levelid'           => $request->get('levelid'),
                'semid'             => $request->get('semid'),
                'sydesc'             => $request->get('sydesc'),
                'certncorrectname'  => $request->get('certncorrectname'),
                'certncorrectdesc'  => $request->get('certncorrectdesc'),
                'datechecked'       => $request->get('datechecked'),
                'createdby'         => auth()->user()->id,
                'createddatetime'   => date('Y-m-d H:i:s')
            ]);
        }
        return 1;
    }
    
    public function remedial_updateheader(Request $request)
    {
        if($request->get('acadprogid') == 3)
        {
            $checkifexists = DB::table('sf10remedial_elem')
                ->where('studid', $request->get('studentid'))
                ->where('levelid', $request->get('levelid'))                
                ->where('sydesc', $request->get('sydesc'))                
                ->where('type','2')
                ->where('deleted','0')
                ->first();

            if($checkifexists)
            {
                DB::table('sf10remedial_elem')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'datefrom'          =>  $request->get('conductdatefrom'),
                        'dateto'            =>  $request->get('conductdateto'),
                        // 'teachername'          =>  $request->get('teachername'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10remedial_elem')
                    ->insert([
                        'datefrom'          =>  $request->get('conductdatefrom'),
                        'dateto'            =>  $request->get('conductdateto'),
                        'sydesc'          =>  $request->get('sydesc'),
                        'studid'          =>  $request->get('studentid'),
                        'levelid'          =>  $request->get('levelid'),
                        // 'teachername'          =>  $request->get('teachername'),
                        'type'              =>  2,
                        'createdby'         =>  auth()->user()->id,
                        'createddatetime'   =>  date('Y-m-d H:i:s')
                    ]);
            }
            return 1;
        }elseif($request->get('acadprogid') == 4)
        {
            $checkifexists = DB::table('sf10remedial_junior')
                ->where('studid', $request->get('studentid'))
                ->where('levelid', $request->get('levelid'))                
                ->where('sydesc', $request->get('sydesc'))                
                ->where('type','2')
                ->where('deleted','0')
                ->first();

            if($checkifexists)
            {
                DB::table('sf10remedial_junior')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'datefrom'          =>  $request->get('conductdatefrom'),
                        'dateto'            =>  $request->get('conductdateto'),
                        // 'teachername'          =>  $request->get('teachername'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10remedial_junior')
                    ->insert([
                        'datefrom'          =>  $request->get('conductdatefrom'),
                        'dateto'            =>  $request->get('conductdateto'),
                        'sydesc'          =>  $request->get('sydesc'),
                        'studid'          =>  $request->get('studentid'),
                        'levelid'          =>  $request->get('levelid'),
                        // 'teachername'          =>  $request->get('teachername'),
                        'type'              =>  2,
                        'createdby'         =>  auth()->user()->id,
                        'createddatetime'   =>  date('Y-m-d H:i:s')
                    ]);
            }
            return 1;
        }
        elseif($request->get('acadprogid') == 5)
        {

            // return $request->all();
            $checkifexists = DB::table('sf10remedial_senior')
                ->where('semid', $request->get('semid'))
                ->where('studid', $request->get('studentid'))
                ->where('levelid', $request->get('levelid'))                
                ->where('sydesc', $request->get('sydesc'))                
                ->where('type','2')
                ->where('deleted','0')
                ->first();

            if($checkifexists)
            {
                DB::table('sf10remedial_senior')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'datefrom'          =>  $request->get('conductdatefrom'),
                        'dateto'            =>  $request->get('conductdateto'),
                        'schoolname'        =>  $request->get('schoolname'),
                        'schoolid'          =>  $request->get('schoolid'),
                        'teachername'          =>  $request->get('teachername'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10remedial_senior')
                    ->insert([
                        'datefrom'          =>  $request->get('conductdatefrom'),
                        'dateto'            =>  $request->get('conductdateto'),
                        'schoolname'        =>  $request->get('schoolname'),
                        'schoolid'          =>  $request->get('schoolid'),
                        'semid'          =>  $request->get('semid'),
                        'sydesc'          =>  $request->get('sydesc'),
                        'studid'          =>  $request->get('studentid'),
                        'levelid'          =>  $request->get('levelid'),
                        'teachername'          =>  $request->get('teachername'),
                        'type'              =>  2,
                        'createdby'         =>  auth()->user()->id,
                        'createddatetime'   =>  date('Y-m-d H:i:s')
                    ]);
            }
            return 1;
        }
    }
    public function remedial_add(Request $request)
    {
        if($request->get('acadprogid') == 3)
        {
            $id = DB::table('sf10remedial_elem')
                ->insertGetId([
                    'sydesc'          =>  $request->get('sydesc'),
                    'studid'          =>  $request->get('studentid'),
                    'levelid'          =>  $request->get('levelid'),
                    'subjectname'       =>  $request->get('subject'),
                    'finalrating'       =>  $request->get('finalrating'),
                    'remclassmark'      =>  $request->get('remclassmark'),
                    'recomputedfinal'   =>  $request->get('recomputedfinal'),
                    'remarks'           =>  $request->get('remarks'),
                    'type'              => 1,
                    'createdby'         =>  auth()->user()->id,
                    'createddatetime'   =>  date('Y-m-d H:i:s')
                ]);
            return $id;
        }
        elseif($request->get('acadprogid') == 4)
        {
            // if($request->get('action') == 'add')
            // {
                $id = DB::table('sf10remedial_junior')
                    ->insertGetId([
                        'sydesc'          =>  $request->get('sydesc'),
                        'studid'          =>  $request->get('studentid'),
                        'levelid'          =>  $request->get('levelid'),
                        'subjectname'       =>  $request->get('subject'),
                        'finalrating'       =>  $request->get('finalrating'),
                        'remclassmark'      =>  $request->get('remclassmark'),
                        'recomputedfinal'   =>  $request->get('recomputedfinal'),
                        'remarks'           =>  $request->get('remarks'),
                        'type'              => 1,
                        'createdby'         =>  auth()->user()->id,
                        'createddatetime'   =>  date('Y-m-d H:i:s')
                    ]);
                return $id;
            // }
        }
        elseif($request->get('acadprogid') == 5)
        {
            // if($request->get('action') == 'add')
            // {
                $id = DB::table('sf10remedial_senior')
                    ->insertGetId([
                        'sydesc'          =>  $request->get('sydesc'),
                        'semid'          =>  $request->get('semid'),
                        'studid'          =>  $request->get('studentid'),
                        'levelid'          =>  $request->get('levelid'),
                        'subjectname'       =>  $request->get('subject'),
                        'subjectcode'       =>  $request->get('subjectcode'),
                        'finalrating'       =>  $request->get('finalrating'),
                        'remclassmark'      =>  $request->get('remclassmark'),
                        'recomputedfinal'   =>  $request->get('recomputedfinal'),
                        'remarks'           =>  $request->get('remarks'),
                        'type'              => 1,
                        'createdby'         =>  auth()->user()->id,
                        'createddatetime'   =>  date('Y-m-d H:i:s')
                    ]);
                return $id;
            // }
        }
    }
    public function remedial_edit(Request $request)
    {
        if($request->get('acadprogid') == 3)
        {
            DB::table('sf10remedial_elem')
                ->where('id', $request->get('remedialid'))
                ->update([
                    'subjectname'    => $request->get('subject'),
                    'finalrating'    => $request->get('finalrating'),
                    'remclassmark'   => $request->get('remclassmark'),
                    'recomputedfinal'=> $request->get('recomputedfinal'),
                    'remarks'        => $request->get('remarks'),
                    'type'           => 1,
                    'updatedby'      => auth()->user()->id,
                    'updateddatetime'=> date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('acadprogid') == 4)
        {
            DB::table('sf10remedial_junior')
                ->where('id', $request->get('remedialid'))
                ->update([
                    'subjectname'    => $request->get('subject'),
                    'finalrating'    => $request->get('finalrating'),
                    'remclassmark'   => $request->get('remclassmark'),
                    'recomputedfinal'=> $request->get('recomputedfinal'),
                    'remarks'        => $request->get('remarks'),
                    'type'           => 1,
                    'updatedby'      => auth()->user()->id,
                    'updateddatetime'=> date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('acadprogid') == 5)
        {
            DB::table('sf10remedial_senior')
                ->where('id', $request->get('remedialid'))
                ->update([
                    'subjectcode'    => $request->get('subjectcode'),
                    'subjectname'    => $request->get('subject'),
                    'finalrating'    => $request->get('finalrating'),
                    'remclassmark'   => $request->get('remclassmark'),
                    'recomputedfinal'=> $request->get('recomputedfinal'),
                    'remarks'        => $request->get('remarks'),
                    'type'           => 1,
                    'updatedby'      => auth()->user()->id,
                    'updateddatetime'=> date('Y-m-d H:i:s')
                ]);
        }
        return 1;
    }
    public function remedial_delete(Request $request)
    {
        if($request->get('acadprogid') == 3)
        {
            DB::table('sf10remedial_elem')
                ->where('id', $request->get('remedialid'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }elseif($request->get('acadprogid') == 4)
        {
            DB::table('sf10remedial_junior')
                ->where('id', $request->get('remedialid'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('acadprogid') == 5)
        {
            DB::table('sf10remedial_senior')
                ->where('id', $request->get('remedialid'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        return 1;
    
    }
    public function certification(Request $request)
    {

        $studentid = $request->get('studentid');
        $acadprogid = $request->get('acadprogid');
        if($acadprogid == 5)
        {
            $strandaccomplished     = $request->get('footerstrandaccomplished');
            $shsgenave              = $request->get('footergenave');
            $honorsreceived         = $request->get('footerhonorsreceived');
            $shsgraduationdate      = $request->get('footerdategrad');
            $datecertified          = $request->get('footerdatecertified');
            $certifiedby          = $request->get('footercertifiedby');
            $copyforupper           = $request->get('footercopyforupper');
            $copyforlower           = $request->get('footercopyforlower');
            $footerregistrar           = $request->get('footerregistrar');

            $checkifexists = Db::table('sf10_footer_senior')
                ->where('studid', $studentid)
                ->where('deleted', 0)
                ->first();

            if($checkifexists)
            {
                Db::table('sf10_footer_senior')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'strandaccomplished'    => $strandaccomplished,
                        'shsgenave'             => $shsgenave,
                        'honorsreceived'        => $honorsreceived,
                        'shsgraduationdate'     => $shsgraduationdate,
                        'datecertified'         => $datecertified,
                        'copyforupper'          => $copyforupper,
                        'certifiedby'          => $certifiedby,
                        'copyforlower'          => $copyforlower,
                        'registrar'          => $footerregistrar,
                        'updatedby'             => auth()->user()->id,
                        'updateddatetime'       => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10_footer_senior')
                    ->insert([
                        'studid'                => $studentid,
                        'strandaccomplished'    => $strandaccomplished,
                        'shsgenave'             => $shsgenave,
                        'honorsreceived'        => $honorsreceived,
                        'shsgraduationdate'     => $shsgraduationdate,
                        'datecertified'         => $datecertified,
                        'copyforupper'          => $copyforupper,
                        'certifiedby'          => $certifiedby,
                        'copyforlower'          => $copyforlower,
                        'registrar'          => $footerregistrar,
                        'createdby'             => auth()->user()->id,
                        'createddatetime'       => date('Y-m-d H:i:s')
                    ]);
            }
        }
        elseif($acadprogid == 4)
        {
            $purpose     = $request->get('purpose');
            $classadviser              = $request->get('classadviser');
            $recordsincharge         = $request->get('recordsincharge');
            $lastsy         = $request->get('lastsy');
            $admissiontograde         = $request->get('admissiontograde');
            $certcopysentto         = $request->get('certcopysentto');
            $certaddress         = $request->get('certaddress');

            $checkifexists = Db::table('sf10_footer_junior')
                ->where('studid', $studentid)
                ->where('deleted', 0)
                ->first();

            if($checkifexists)
            {
                Db::table('sf10_footer_junior')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'purpose'    => $purpose,
                        'classadviser'             => $classadviser,
                        'recordsincharge'        => $recordsincharge,
                        'lastsy'        => $lastsy,
                        'admissiontograde'        => $admissiontograde,
                        'copysentto'        => $certcopysentto,
                        'address'        => $certaddress,
                        'updatedby'             => auth()->user()->id,
                        'updateddatetime'       => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10_footer_junior')
                    ->insert([
                        'studid'                => $studentid,
                        'purpose'    => $purpose,
                        'classadviser'             => $classadviser,
                        'recordsincharge'        => $recordsincharge,
                        'lastsy'        => $lastsy,
                        'admissiontograde'        => $admissiontograde,
                        'copysentto'        => $certcopysentto,
                        'address'        => $certaddress,
                        'createdby'             => auth()->user()->id,
                        'createddatetime'       => date('Y-m-d H:i:s')
                    ]);
            }
        }
        elseif($acadprogid == 3)
        {
            $purpose     = $request->get('purpose');
            $classadviser              = $request->get('classadviser');
            $recordsincharge         = $request->get('recordsincharge');
            $lastsy         = $request->get('lastsy');
            $admissiontograde         = $request->get('admissiontograde');
            $certcopysentto         = $request->get('certcopysentto');
            $certaddress         = $request->get('certaddress');

            $checkifexists = Db::table('sf10_footer_elem')
                ->where('studid', $studentid)
                ->where('deleted', 0)
                ->first();

            if($checkifexists)
            {
                Db::table('sf10_footer_elem')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'purpose'    => $purpose,
                        'classadviser'             => $classadviser,
                        'recordsincharge'        => $recordsincharge,
                        'lastsy'        => $lastsy,
                        'admissiontograde'        => $admissiontograde,
                        'copysentto'        => $certcopysentto,
                        'address'        => $certaddress,
                        'updatedby'             => auth()->user()->id,
                        'updateddatetime'       => date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('sf10_footer_elem')
                    ->insert([
                        'studid'                => $studentid,
                        'purpose'    => $purpose,
                        'classadviser'             => $classadviser,
                        'recordsincharge'        => $recordsincharge,
                        'lastsy'        => $lastsy,
                        'admissiontograde'        => $admissiontograde,
                        'copysentto'        => $certcopysentto,
                        'address'        => $certaddress,
                        'createdby'             => auth()->user()->id,
                        'createddatetime'       => date('Y-m-d H:i:s')
                    ]);
            }
        }
    }
    public function addsubjgradesinauto(Request $request)
    {
        $studentid      = $request->get('studentid');
        $syid           = $request->get('syid');
        $semid          = $request->get('semid');
        $levelid        = $request->get('levelid');
        $subjcode       = $request->get('subjcode');
        $subjdesc       = $request->get('subjdesc');
        $q1             = $request->get('subjq1');
        $q2             = $request->get('subjq2');
        $q3             = $request->get('subjq3');
        $q4             = $request->get('subjq4');
        $finalrating    = $request->get('subjfinalrating');
        $actiontaken    = $request->get('subjremarks');
        
        try{

            $subjgradeautoid = DB::table('sf10grades_subjauto')
                ->insertGetId([
                    'studid'         => $studentid,
                    'syid'           => $syid,
                    'semid'          => $semid,
                    'levelid'        => $levelid,
                    'subjcode'       => $subjcode,
                    'subjdesc'       => $subjdesc,
                    'q1'             => $q1,
                    'q2'             => $q2,
                    'q3'             => $q3,
                    'q4'             => $q4,
                    'finalrating'    => $finalrating,
                    'actiontaken'    => $actiontaken,
                    'createdby'      => auth()->user()->id,
                    'createddatetime'=> date('Y-m-d H:i:s')
                ]);

            return $subjgradeautoid;

        }catch(\Exception $error)
        {

            return 0;

        }
    }
    public function updatesubjgradesinauto(Request $request)
    {
        $studentid      = $request->get('studentid');
        $id             = $request->get('id');
        $subjcode       = $request->get('subjcode');
        $subjdesc       = $request->get('subjdesc');
        $q1             = $request->get('subjq1');
        $q2             = $request->get('subjq2');
        $q3             = $request->get('subjq3');
        $q4             = $request->get('subjq4');
        $finalrating    = $request->get('subjfinalrating');
        $actiontaken    = $request->get('subjremarks');
        
        try{

            DB::table('sf10grades_subjauto')
                ->where('id', $id)
                ->update([
                    'subjcode'       => $subjcode,
                    'subjdesc'       => $subjdesc,
                    'q1'             => $q1,
                    'q2'             => $q2,
                    'q3'             => $q3,
                    'q4'             => $q4,
                    'finalrating'    => $finalrating,
                    'actiontaken'    => $actiontaken,
                    'updatedby'      => auth()->user()->id,
                    'updateddatetime'=> date('Y-m-d H:i:s')
                ]);

            return 1;

        }catch(\Exception $error)
        {

            return 0;

        }
    }
    public function deletesubjgradesinauto(Request $request)
    {
        if($request->ajax())
        {
            $id             = $request->get('id');
            DB::table('sf10grades_subjauto')
            ->where('id', $id)
            ->update([
                'deleted'    =>     1,
                'deletedby'      => auth()->user()->id,
                'deleteddatetime'=> date('Y-m-d H:i:s')
            ]);
        }
        
    }
    
    public function reportsschoolform10addinauto(Request $request)
    {
        // return $request->all();
        $studentid  = $request->get('studentid');
        $subjectid  = $request->get('subjectid');
        $quarter    = $request->get('quarter');
        $syid       = $request->get('syid');
        $semid      = $request->get('semid');
        $levelid    = $request->get('levelid');
        $grade      = $request->get('gradevalue');
        
        try{

            DB::table('sf10grades_addinauto')
                ->insert([
                    'syid'              => $syid,
                    'semid'             => $semid,
                    'levelid'           => $levelid,
                    'acadprogid'        => $request->get('acadprogid'),
                    'studid'            => $studentid,
                    'subjid'            => $subjectid,
                    'quarter'           => $quarter,
                    'grade'             => $grade,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;

        }catch(\Exception $error)
        {

            return 0;

        }
    }
    public function reportsschoolform10editinauto(Request $request)
    {
        // return $request->all();
        $studentid  = $request->get('studentid');
        $subjectid  = $request->get('subjectid');
        $quarter    = $request->get('quarter');
        $syid       = $request->get('syid');
        $semid      = $request->get('semid');
        $levelid    = $request->get('levelid');
        $grade      = $request->get('gradevalue');
        
        try{

            if($grade == 0)
            {
                $checkifexists = DB::table('sf10grades_addinauto')
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('levelid', $levelid)
                    ->where('studid', $studentid)
                    ->where('subjid', $subjectid)
                    ->where('quarter', $quarter)
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    DB::table('sf10grades_addinauto')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'deleted'         => 1,
                            'deletedby'       => auth()->user()->id,
                            'deleteddatetime' => date('Y-m-d H:i:s')
                        ]);
                    
                }
            }else{
                $checkifexists = DB::table('sf10grades_addinauto')
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('levelid', $levelid)
                    ->where('studid', $studentid)
                    ->where('subjid', $subjectid)
                    ->where('quarter', $quarter)
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    DB::table('sf10grades_addinauto')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'grade'           => $grade,
                            'updatedby'       => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                    
                }else{
                    DB::table('sf10grades_addinauto')
                        ->insert([
                            'syid'              => $syid,
                            'semid'             => $semid,
                            'levelid'           => $levelid,
                            'acadprogid'        => $request->get('acadprogid'),
                            'studid'            => $studentid,
                            'subjid'            => $subjectid,
                            'quarter'           => $quarter,
                            'grade'             => $grade,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);

                }

                
                    // ->update([
                    //     'grade'           => $grade,
                    //     'updatedby'       => auth()->user()->id,
                    //     'updateddatetime' => date('Y-m-d H:i:s')
                    // ]);
            }
            
            return 1;

        }catch(\Exception $error)
        {

            return 0;

        }
    }
}
