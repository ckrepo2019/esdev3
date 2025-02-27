<?php

namespace App\Http\Controllers\TeacherControllers;
use DB;
use PDF;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\AttendanceReport;
use App\GenerateGrade;
use App\Models\Principal\SPP_Attendance;
class FormReportCardController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        
        $sem = DB::table('semester')
            ->where('isactive','1')
            ->get();

        $getSyId = DB::table('sy')
            ->select('id','sydesc')
            ->where('isactive',1)
            ->get();

        $getId = DB::table('teacher')
            ->select(
                'teacher.id',
                'sections.id as sectionid',
                'academicprogram.progname'
                )
            ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('sectiondetail.syid',$getSyId[0]->id)
            ->where('teacher.userid',auth()->user()->id)
            ->get();

        if(count($getId)==0){
            
            return view('teacher.reportcard');

        }
        else{

            if($getId[0]->progname == "SENIOR HIGH SCHOOL"){

                $getStudentsId = DB::table('sh_enrolledstud')
                    ->select(
                        'sh_enrolledstud.studid'
                        )
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                    ->where('sections.teacherid',$getId[0]->id)
                    ->where('sh_enrolledstud.sectionid',$getId[0]->sectionid)
                    ->where('sh_enrolledstud.syid',$getSyId[0]->id)
                    ->where('sh_enrolledstud.semid',$sem[0]->id)
                    ->where('sh_enrolledstud.studstatus','!=','0')
                    ->orderBy('studinfo.lastname','asc')
                    ->distinct()
                    ->get();
                }

            else{
                
                $getStudentsId = DB::table('enrolledstud')
                    ->select(
                        'enrolledstud.studid'
                        )
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    // ->where('enrolledstud.teacherid',$getId[0]->id)
                    ->where('enrolledstud.sectionid',$getId[0]->sectionid)
                    ->where('enrolledstud.syid',$getSyId[0]->id)
                    ->where('enrolledstud.studstatus','!=','0')
                    ->orderBy('studinfo.lastname','asc')
                    ->distinct()
                    ->get();

            }
            
            $enrolledstudentsid = array();

            array_push($enrolledstudentsid,$getStudentsId->unique());

            $studentinfo = array();
    
            foreach($enrolledstudentsid[0] as $student_id){

                $get_student = DB::table('studinfo')
                    ->where('id',$student_id->studid)
                    ->orderBy('lastname','asc')
                    ->get();

                array_push($studentinfo,$get_student);

            }

            return view('teacher.reportcard')
                ->with('students',$studentinfo);

        }

    }
    function viewSchoolForm4($id, Request $request){
        if($request->get('schoolhead') == true){
            if($request->get('schoolhead') != ""){
                DB::table('schoolinfo')
                    ->update([
                        'authorized'    => strtoupper($request->get('schoolhead'))
                    ]);
            }
        }
        $getSchoolYear = DB::table('sy')
        ->select('id','sydesc')
        ->where('isactive',1)
        ->get();
        $getSectionAndLevel = DB::table('users')
            ->select('teacher.id','sections.levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
            ->join('teacher','users.id','=','teacher.userid')
            ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->where('sectiondetail.syid',$getSchoolYear[0]->id)
            ->where('users.id',auth()->user()->id)
            ->get();
                // return $getSectionAndLevel;
        $getSchoolInfo = DB::table('schoolinfo')
            ->select('region','division','district','schoolname','schoolid')
            ->get();
        $getTeacherName = DB::table('users')
            ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
            ->join('teacher','users.id','=','teacher.userid')
            ->where('users.id',auth()->user()->id)
            ->get();
        $getPrincipal = DB::table('gradelevel')
            ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->join('teacher','academicprogram.principalid','=','teacher.id')
            ->where('gradelevel.id',$getSectionAndLevel[0]->levelid)
            ->get();

        if($id == 'show'){
            // return $finalGrades;

            return view('teacher.showschoolform4')
                ->with('school',$getSchoolInfo)
                ->with('sy',$getSchoolYear[0]->sydesc);
        }
        elseif($id == 'preview'){

            $pdf = PDF::loadview('teacher/pdf/schoolform4preview',compact('getSchoolInfo','getSchoolYear','getSectionAndLevel','getTeacherName','getPrincipal'))->setPaper('legal','landscape');

            return $pdf->stream('Class Record');
        }
    }
    function viewSchoolForm5(Request $request, $id){
        if($request->get('schoolhead') == true){
            if($request->get('schoolhead') != ""){
                DB::table('schoolinfo')
                    ->update([
                        'authorized'    => strtoupper($request->get('schoolhead'))
                    ]);
            }
        }
        $getSchoolYear = DB::table('sy')
        ->select('id','sydesc')
        ->where('isactive',1)
        ->get();
        $getSectionAndLevel = DB::table('teacher')
            ->select('teacher.id','sections.levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname','academicprogram.progname')
            ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('teacher.userid',auth()->user()->id)
            ->where('sectiondetail.syid',$getSchoolYear[0]->id)
            ->get();
        
            $getSchoolInfo = DB::table('schoolinfo')
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
            ->join('refregion','schoolinfo.region','=','refregion.regCode')
            ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->get();
            $getTeacherName = DB::table('users')
                ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                ->join('teacher','users.id','=','teacher.userid')
                ->where('users.id',auth()->user()->id)
                ->get();
                // return $getSectionAndLevel;
        if(count($getSectionAndLevel)==0){
            // return $getSectionAndLevel;
            return view('teacher.showschoolform5')
                ->with('school',$getSchoolInfo)
                ->with('sy',$getSchoolYear)
                ->with('teachername',$getTeacherName);
        }
        else{
                // return $getSectionAndLevel[0]->progname;
            if($getSectionAndLevel[0]->progname == "SENIOR HIGH SCHOOL"){
                // return 'asdsad';

                $getStudents = DB::table('sh_enrolledstud')
                    ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','academicprogram.id as acadprogid','gradelevel.id as levelid','sections.id as sectionid','sections.blockid','sh_enrolledstud.sectionid as ensectid','sh_enrolledstud.levelid as enlevelid')
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('sections.id',$getSectionAndLevel[0]->sectionid)
                    ->where('sh_enrolledstud.id',$getSchoolYear[0]->id)
                    ->distinct()
                    ->orderBy('studinfo.lastname','asc')
                    ->get();
            }
            else{
                $getStudents = DB::table('enrolledstud')
                    ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','academicprogram.id as acadprogid','gradelevel.id as levelid','sections.id as sectionid','sections.blockid', 'enrolledstud.sectionid as ensectid', 'enrolledstud.levelid as enlevelid')
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    ->join('sections','enrolledstud.sectionid','=','sections.id')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('enrolledstud.sectionid',$getSectionAndLevel[0]->sectionid)
                    ->where('enrolledstud.syid',$getSchoolYear[0]->id)
                    ->where('enrolledstud.studstatus','1')
                    ->distinct()
                    ->orderBy('studinfo.lastname','asc')
                    ->get();
            }

            // return count($getStudents);
            $finalGrades = array();

            foreach($getStudents as $student){
                // return $student;
                $generalAve = GenerateGrade::generalAverage($student);
                array_push($finalGrades,array($generalAve[0],$student));
                // array_push($finalGrades,$generalAve[0]);
                // array_push($finalGrades,$student);
                // return $generalAve;
            }
            // return $finalGrades;
            $getPrincipal = DB::table('gradelevel')
                ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->join('teacher','academicprogram.principalid','=','teacher.id')
                ->where('gradelevel.id',$getSectionAndLevel[0]->levelid)
                ->get();
            $getGrades = DB::table('grades')
                ->select('gradesdetail.studid','gradesdetail.qg')
                ->join('gradesdetail','grades.id','=','gradesdetail.headerid')
                ->where('grades.levelid', $getSectionAndLevel[0]->levelid)
                ->where('grades.sectionid', $getSectionAndLevel[0]->sectionid)
                ->where('grades.syid', $getSchoolYear[0]->id)
                ->get();
            $getValues = Db::table('observedvaluesdetail')
                ->select('observedvalues.quarter','observedvaluesdetail.makaDiyos_1','observedvaluesdetail.makaDiyos_2','observedvaluesdetail.makaTao_1','observedvaluesdetail.makaKalikasan_1','observedvaluesdetail.makaKalikasan_2','observedvaluesdetail.makaBansa_1','observedvaluesdetail.makaBansa_2')
                ->join('observedvalues','observedvaluesdetail.headerid','=','observedvalues.id')
                ->where('studid',$id)
                ->where('observedvalues.id',$getSchoolYear[0]->id)
                ->get();
            if($id == 'show'){
                $generalAverage = array();
                foreach($finalGrades as $key=>$promotion){
                    // return $promotion[0]->Final;
                    $failed = array();
                    $generateGrade = GenerateGrade::finalGrades($promotion[1]);
                    // return $generateGrade;
                    $numFailed = count(collect($generateGrade)->where('remarks','FAILED'));
                    // return $$generateGrade;
                    if(count($generateGrade)==0 || $promotion[0]->Final == null){
                        array_push($finalGrades[$key],"");
                    }
                    else{
                        if($numFailed == 0){
                            array_push($finalGrades[$key],"PROMOTED");
                            $getFailedSubjects = collect($generateGrade)->where('remarks','FAILED');
                            $failed = array();
                            foreach($getFailedSubjects as $failedSubjects){
                                $subjects  = $failedSubjects->assignsubject;
                                array_push($failed, array($subjects));
                            }
                            array_push($finalGrades[$key],$failed);
                        }
                        elseif($numFailed == 2 || $numFailed == 1) {
                            array_push($finalGrades[$key],"CONDITIONAL");
                            $getFailedSubjects = collect($generateGrade)->where('remarks','FAILED');
                            $failed = array();
        
                            foreach($getFailedSubjects as $failedSubjects){
                                $subjects  = $failedSubjects->assignsubject;
                                
                                array_push($failed, array($subjects));
                            }
                            array_push($finalGrades[$key],$failed);
        
                        }
                        elseif($numFailed >= 3){
                            array_push($finalGrades[$key],"RETAINED");
                            $getFailedSubjects = collect($generateGrade)->where('remarks','FAILED');
                            $failed = array();
                            foreach($getFailedSubjects as $failedSubjects){
                                $subjects  = $failedSubjects->assignsubject;
                                array_push($failed, array($subjects));
                            }
                            array_push($finalGrades[$key],$failed);
                        }
                    }
                    
                }
                // return $finalGrades;
                    return view('teacher.showschoolform5')
                        ->with('school',$getSchoolInfo)
                        ->with('sy',$getSchoolYear)
                        ->with('gradeAndLevel',$getSectionAndLevel)
                        ->with('students',$finalGrades)
                        ->with('teachername',$getTeacherName)
                        ->with('principalname',$getPrincipal);
            }
            elseif($id == 'print'){
                
                $curriculum = $request->get('curriculum');
                $divisionRep = $request->get('divisionRep');
                foreach($finalGrades as $key=>$promotion){
                    $failed = array();
                    $generateGrade = GenerateGrade::finalGrades($promotion[1]);
                    $numFailed = count(collect($generateGrade)->where('remarks','FAILED'));
                    if(count($generateGrade)==0){
                        array_push($finalGrades[$key],"");
                    }
                    else{
                        if($numFailed == 0){
                            array_push($finalGrades[$key],"PROMOTED");
                            $getFailedSubjects = collect($generateGrade)->where('remarks','FAILED');
                            $failed = array();
                            foreach($getFailedSubjects as $failedSubjects){
                                $subjects  = $failedSubjects->assignsubject;
                                array_push($failed, array($subjects));
                            }
                            array_push($finalGrades[$key],$failed);
                        }
                        elseif($numFailed == 2 || $numFailed == 1) {
                            array_push($finalGrades[$key],"CONDITIONAL");
                            $getFailedSubjects = collect($generateGrade)->where('remarks','FAILED');
                            $failed = array();
        
                            foreach($getFailedSubjects as $failedSubjects){
                                $subjects  = $failedSubjects->assignsubject;
                                
                                array_push($failed, array($subjects));
                            }
                            array_push($finalGrades[$key],$failed);
        
                        }
                        elseif($numFailed >= 3){
                            array_push($finalGrades[$key],"RETAINED");
                            $getFailedSubjects = collect($generateGrade)->where('remarks','FAILED');
                            $failed = array();
                            foreach($getFailedSubjects as $failedSubjects){
                                $subjects  = $failedSubjects->assignsubject;
                                array_push($failed, array($subjects));
                            }
                            array_push($finalGrades[$key],$failed);
                        }
                    }
                    
                }
                
                $pdf = PDF::loadview('teacher/pdf/schoolform5preview',compact('getSchoolInfo','getSchoolYear','getSectionAndLevel','curriculum','divisionRep','finalGrades','getTeacherName','getPrincipal','getValues'))->setPaper('legal','landscape');

                return $pdf->stream('School Form 5.pdf');
            }
        }
    }
    function viewSchoolForm6(Request $request, $id){
        if($request->get('schoolhead') == true){
            if($request->get('schoolhead') != ""){
                DB::table('schoolinfo')
                    ->update([
                        'authorized'    => strtoupper($request->get('schoolhead'))
                    ]);
            }
        }
        $getSchoolYear = DB::table('sy')
        ->select('id','sydesc')
        ->where('isactive',1)
        ->get();
        $getSectionAndLevel = DB::table('users')
            ->select('teacher.id','sections.levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname','academicprogram.progname')
            ->join('teacher','users.id','=','teacher.userid')
            ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('users.id',auth()->user()->id)
            ->where('sectiondetail.syid',$getSchoolYear[0]->id)
            ->get();
                // return $getSectionAndLevel;
        $getSchoolInfo = DB::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'refregion.regDesc as region'
            )
            ->join('refregion','schoolinfo.region','=','refregion.regCode')
            ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->get();
        $getTeacherName = DB::table('users')
            ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
            ->join('teacher','users.id','=','teacher.userid')
            ->where('users.id',auth()->user()->id)
            ->get();
        if(count($getSectionAndLevel)==0){
                // return $getSectionAndLevel;
                return view('teacher.showschoolform6')
                    ->with('school',$getSchoolInfo)
                    ->with('sy',$getSchoolYear)
                    ->with('teachername',$getTeacherName);
        }
        else{
            if($getSectionAndLevel[0]->progname == "SENIOR HIGH SCHOOL"){
                $getStudents = DB::table('sh_enrolledstud')
                    ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','academicprogram.id as acadprogid','studinfo.levelid as levelid','sections.id as sectionid','sections.blockid','studinfo.levelid as enlevelid','sections.id as ensectid')
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                    ->join('gradelevel','sections.levelid','=','gradelevel.id')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('sh_enrolledstud.studstatus','1')
                    ->where('studinfo.studstatus','!=', '0')
                    ->where('sh_enrolledstud.syid',$getSchoolYear[0]->id)
                    ->distinct()
                    ->where('sections.id',$getSectionAndLevel[0]->sectionid)
                    ->get();
            }
            else{
                $getStudents = DB::table('enrolledstud')
                ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','academicprogram.id as acadprogid','studinfo.levelid as levelid','sections.id as sectionid','sections.blockid','studinfo.levelid as enlevelid','sections.id as ensectid')
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    ->join('sections','enrolledstud.sectionid','=','sections.id')
                    ->join('gradelevel','sections.levelid','=','gradelevel.id')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('enrolledstud.sectionid',$getSectionAndLevel[0]->sectionid)
                    ->where('enrolledstud.studstatus','1')
                    ->where('studinfo.studstatus','!=', '0')
                    ->where('enrolledstud.syid',$getSchoolYear[0]->id)
                    ->distinct()
                    ->get();
            }
            // return $getStudents;
            $finalGrades = array();
            foreach($getStudents as $student){
                $generalAve = GenerateGrade::generalAverage($student);
                array_push($finalGrades,array($generalAve[0],$student));
                // array_push($finalGrades,$generalAve[0]);
                // array_push($finalGrades,$student);
            }
            // return $finalGrades;
            $getPrincipal = DB::table('gradelevel')
                ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->join('teacher','academicprogram.principalid','=','teacher.id')
                ->where('gradelevel.id',$getSectionAndLevel[0]->levelid)
                ->get();
            $getGrades = DB::table('grades')
                ->select('gradesdetail.studid','gradesdetail.qg')
                ->join('gradesdetail','grades.id','=','gradesdetail.headerid')
                ->where('grades.levelid', $getSectionAndLevel[0]->levelid)
                ->where('grades.sectionid', $getSectionAndLevel[0]->sectionid)
                ->where('grades.syid', $getSchoolYear[0]->id)
                ->get();
            if($id == 'show'){
                foreach($finalGrades as $key=>$promotion){

                    $generateGrade = GenerateGrade::finalGrades($promotion[1]);
                    // return $generateGrade;
                    $numFailed = count(collect($generateGrade)->where('remarks','FAILED'));
                    // $failedSubjects = collect($generateGrade)->where('remarks','PASSED');
                    // return $numFailed;
                    if($numFailed == 0){
                        array_push($finalGrades[$key],"PROMOTED");
                        $getFailedSubjects = collect($generateGrade)->where('remarks','FAILED');
                        $failed = array();
                        foreach($getFailedSubjects as $failedSubjects){
                            $subjects  = $failedSubjects->subject;
                            array_push($failed, array($subjects));
                        }
                        array_push($finalGrades[$key],$failed);
                    }
                    elseif($numFailed == 2 || $numFailed == 1) {
                        array_push($finalGrades[$key],"CONDITIONAL");
                    }
                    elseif($numFailed >= 3){
                        array_push($finalGrades[$key],"RETAINED");
                    }
                }
                // return $finalGrades;
                return view('teacher.showschoolform6')
                    ->with('school',$getSchoolInfo)
                    ->with('sy',$getSchoolYear)
                    ->with('gradeAndLevel',$getSectionAndLevel)
                    ->with('students',$finalGrades)
                    ->with('teachername',$getTeacherName)
                    ->with('principalname',$getPrincipal);
            }
            elseif($id == 'preview'){
                $divisionRep = $request->get('divisionRep');
                $divisionSup = $request->get('divisionSup');
                foreach($finalGrades as $key=>$promotion){
                    
                    $generateGrade = GenerateGrade::finalGrades($promotion[1]);
                    // return $generateGrade;
                    $numFailed = count(collect($generateGrade)->where('remarks','FAILED'));
                    // $failedSubjects = collect($generateGrade)->where('remarks','PASSED');
                    // return $failedSubjects;
                    if($numFailed == 0){
                        array_push($finalGrades[$key],"PROMOTED");
                        $getFailedSubjects = collect($generateGrade)->where('remarks','FAILED');
                        $failed = array();
                        foreach($getFailedSubjects as $failedSubjects){
                            $subjects  = $failedSubjects->subject;
                            array_push($failed, array($subjects));
                        }
                        array_push($finalGrades[$key],$failed);
                    }
                    elseif($numFailed == 2 || $numFailed == 1) {
                        array_push($finalGrades[$key],"CONDITIONAL");
                    }
                    elseif($numFailed >= 3){
                        array_push($finalGrades[$key],"RETAINED");
                    }
                }
                $pdf = PDF::loadview('teacher/pdf/schoolform6preview',compact('getSchoolInfo','getSchoolYear','getSectionAndLevel','divisionRep','divisionSup','getStudents','getTeacherName','getPrincipal','finalGrades'))->setPaper('legal','landscape');

                return $pdf->stream('Class Record');
            }
        }
    }
    function viewSchoolForm9($action,$id)
    {
        // return $id;
        
        date_default_timezone_set('Asia/Manila');

        $sem = DB::table('semester')
            ->where('isactive','1')
            ->get();
        $getSyId = DB::table('sy')
            ->select('id','sydesc')
            ->where('isactive',1)
            ->get();
        $getId = DB::table('teacher')
            ->select('teacher.id','sections.id as sectionid','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix','academicprogram.progname','academicprogram.acadprogcode')
            ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('teacher.userid',auth()->user()->id)
            ->where('sectiondetail.syid',$getSyId[0]->id)
            ->get();
        // return $getId;
        $getHeaderId = DB::table('grades')
            ->select('id','subjid','quarter')
            ->where('sectionid',$getId[0]->sectionid)
            ->where('syid',$getSyId[0]->id)
            ->get();

        $dt = date('Y-m-d');
        // return $dt;
        $student_info = DB::table('sectiondetail')
            ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.dob','academicprogram.acadprogcode','academicprogram.progname as progname','studinfo.levelid as levelid','studinfo.sectionid as sectionid','sections.blockid','studinfo.sectionid as ensectid','enrolledstud.levelid as enlevelid','enrolledstud.syid','enrolledstud.promotionstatus')
            ->join('teacher','sectiondetail.teacherid','teacher.id')
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
            ->join('enrolledstud','sections.id','enrolledstud.sectionid')
            ->join('studinfo','enrolledstud.studid','studinfo.id')
            // ->where('sectiondetail.syid',$getSyId[0]->id)
            ->where('enrolledstud.syid',$getSyId[0]->id)
            ->where('enrolledstud.sectionid',$getId[0]->sectionid)
            ->where('enrolledstud.studid',$id)
            ->get();
        // return $studentinfo;
        if(count($student_info)==0){
            
        // return 'sdfg';
            $student_info = DB::table('sectiondetail')
                ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.dob','academicprogram.acadprogcode','academicprogram.progname as progname','studinfo.levelid as levelid','studinfo.sectionid as sectionid','sections.blockid','studinfo.sectionid as ensectid','studinfo.levelid as enlevelid','sh_enrolledstud.syid','sh_enrolledstud.promotionstatus')
                ->join('teacher','sectiondetail.teacherid','teacher.id')
                ->join('sections','sectiondetail.sectionid','=','sections.id')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
                ->join('sh_enrolledstud','sections.id','sh_enrolledstud.sectionid')
                ->join('studinfo','sh_enrolledstud.studid','studinfo.id')
                // ->where('sectiondetail.syid',$getSyId[0]->id)
                ->where('sh_enrolledstud.syid',$getSyId[0]->id)
                ->where('sh_enrolledstud.semid',$sem[0]->id)
                ->where('sh_enrolledstud.sectionid',$getId[0]->sectionid)
                ->where('sh_enrolledstud.studid',$id)
                ->distinct()
                ->get();
        }
        
        if($student_info[0]->dob == null){
            $age = "";
        }else{
            $result = substr($student_info[0]->dob, 0, 4);
            $current_year = substr($dt, 0, 4);
            $age = $current_year - $result;
        }
        
        $arrayForm = array();

        $section = DB::table('sections')
            ->select('sections.sectionname','gradelevel.id as levelid','academicprogram.acadprogcode')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('sections.deleted',0)
            ->where('sections.id',$getId[0]->sectionid)
            ->get();
            
        // $studattrep = AttendanceReport::schoolYearBasedAttendanceReport($student_info[0]);

        // return $student_info[0]->id;
        $studattrep = SPP_Attendance::getStudentAttendance($student_info[0]->id);

        // return $studattrep;

        if($section[0]->acadprogcode != 'SHS'){

            $generateGrade = GenerateGrade::finalGrades($student_info[0]);
            $generalAverage = GenerateGrade::generalAverage($student_info[0]);

        }
        // return $generalAverage;
        $gradelevel = DB::table('gradelevel')
            ->select('gradelevel.levelname','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->join('teacher','academicprogram.principalid','=','teacher.id')
            ->where('gradelevel.id',$section[0]->levelid)
            ->get();

        array_push($arrayForm,$student_info);
        array_push($arrayForm,$age);
        array_push($arrayForm,$gradelevel);
        array_push($arrayForm,$section);
        array_push($arrayForm,$getSyId[0]->sydesc);
        array_push($arrayForm,$getId);

        $getSchoolInfo = DB::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'refregion.regDesc as region'
            )
            ->join('refregion','schoolinfo.region','=','refregion.regCode')
            ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->get();

        $getValues = Db::table('observedvaluesdetail')
            ->select('observedvalues.quarter','observedvaluesdetail.makaDiyos_1','observedvaluesdetail.makaDiyos_2','observedvaluesdetail.makaTao_1','observedvaluesdetail.makaTao_2','observedvaluesdetail.makaKalikasan_1','observedvaluesdetail.makaKalikasan_2','observedvaluesdetail.makaBansa_1','observedvaluesdetail.makaBansa_2')
            ->join('observedvalues','observedvaluesdetail.headerid','=','observedvalues.id')
            ->where('studid',$id)
            ->where('observedvalues.syid',$getSyId[0]->id)
            ->get();
                // return $getValues;
        if(count($getValues) == 0){
            $getValues->push((object)array(
                'quarter'=>'1',
                'makaDiyos_1'=>' ',
                'makaDiyos_2'=>' ',
                'makaTao_1'=>' ',
                'makaTao_2'=>' ',
                'makaKalikasan_1'=>' ',
                'makaKalikasan_2'=>' ',
                'makaBansa_1'=>' ',
                'makaBansa_2'=>' '
            ));
            $getValues->push((object)array(
                'quarter'=>'2',
                'makaDiyos_1'=>' ',
                'makaDiyos_2'=>' ',
                'makaTao_1'=>' ',
                'makaTao_2'=>' ',
                'makaKalikasan_1'=>' ',
                'makaKalikasan_2'=>' ',
                'makaBansa_1'=>' ',
                'makaBansa_2'=>' '
            ));
            $getValues->push((object)array(
                'quarter'=>'3',
                'makaDiyos_1'=>' ',
                'makaDiyos_2'=>' ',
                'makaTao_1'=>' ',
                'makaTao_2'=>' ',
                'makaKalikasan_1'=>' ',
                'makaKalikasan_2'=>' ',
                'makaBansa_1'=>' ',
                'makaBansa_2'=>' '
            ));
            $getValues->push((object)array(
                'quarter'=>'4',
                'makaDiyos_1'=>' ',
                'makaDiyos_2'=>' ',
                'makaTao_1'=>' ',
                'makaTao_2'=>' ',
                'makaKalikasan_1'=>' ',
                'makaKalikasan_2'=>' ',
                'makaBansa_1'=>' ',
                'makaBansa_2'=>' '
            ));
        }
        elseif(count($getValues) != 0){
            if(count($getValues->where('quarter','2')) == 0){
                $getValues->push((object)array(
                    'quarter'=>'2',
                    'makaDiyos_1'=>' ',
                    'makaDiyos_2'=>' ',
                    'makaTao_1'=>' ',
                    'makaTao_2'=>' ',
                    'makaKalikasan_1'=>' ',
                    'makaKalikasan_2'=>' ',
                    'makaBansa_1'=>' ',
                    'makaBansa_2'=>' '
                ));
            }
            if(count($getValues->where('quarter','3')) == 0){
                $getValues->push((object)array(
                    'quarter'=>'3',
                    'makaDiyos_1'=>' ',
                    'makaDiyos_2'=>' ',
                    'makaTao_1'=>' ',
                    'makaTao_2'=>' ',
                    'makaKalikasan_1'=>' ',
                    'makaKalikasan_2'=>' ',
                    'makaBansa_1'=>' ',
                    'makaBansa_2'=>' '
                ));
    
            }
            if(count($getValues->where('quarter','4')) == 0){
                $getValues->push((object)array(
                    'quarter'=>'4',
                    'makaDiyos_1'=>' ',
                    'makaDiyos_2'=>' ',
                    'makaTao_1'=>' ',
                    'makaTao_2'=>' ',
                    'makaKalikasan_1'=>' ',
                    'makaKalikasan_2'=>' ',
                    'makaBansa_1'=>' ',
                    'makaBansa_2'=>' '
                ));
            }
        }
        
        if($getId[0]->acadprogcode == 'SHS'){
            
            // $gradesarray = array();
            // $firstsemArray = array();
            // $secondsemArray = array();
            
            $gradesfirstsem = DB::table('tempgradesum')
                ->select(
                    'tempgradesum.q1',
                    'tempgradesum.q2',
                    'tempgradesum.semid',
                    'sh_subjects.subjtitle as assignsubject',
                    'sh_subjects.subjcode'
                )
                ->join('sh_subjects','tempgradesum.subjid','=','sh_subjects.id')
                ->where('tempgradesum.studid',$id)
                ->where('tempgradesum.semid',1)
                ->get();

            $gradessecondsem = DB::table('tempgradesum')
                ->select(
                    'tempgradesum.q1',
                    'tempgradesum.q2',
                    'tempgradesum.semid',
                    'sh_subjects.subjtitle as assignsubject',
                    'sh_subjects.subjcode'
                )
                ->join('sh_subjects','tempgradesum.subjid','=','sh_subjects.id')
                ->where('tempgradesum.studid',$id)
                ->where('tempgradesum.semid',2)
                ->get();
                
            $shs_enrolledstud = DB::table('sh_enrolledstud')
                // ->join('grades','')
                ->where('sh_enrolledstud.studid',$id)
                ->where('sh_enrolledstud.syid',$getSyId[0]->id)
                ->get();
                // return $shs_enrolledstud;
            $strandTrack = DB::table('sh_strand')
                ->select('sh_track.trackname','sh_strand.strandname')
                ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                ->where('sh_strand.id',$shs_enrolledstud[0]->strandid)
                ->where('sh_strand.deleted','0')
                ->get();
                array_push($arrayForm,$strandTrack[0]);
        }
        elseif($getId[0]->acadprogcode!='SHS'){

            foreach($generateGrade as $grades){
                $grades->firstsem = array(["quarter1" => $grades->quarter1, "quarter2" => $grades->quarter2]);
                $grades->secondsem = array(["quarter3" => $grades->quarter3, "quarter4" => $grades->quarter4]);
            }
            
            $quarter1 = array();
            $quarter2 = array();
            $quarter3 = array();
            $quarter4 = array();
            foreach($getValues as $values){
                if($values->quarter == 1){
                    array_push($quarter1, $values);
                }
                elseif($values->quarter == 2){
                    array_push($quarter2, $values);
                    // return $values;
                }
                elseif($values->quarter == 3){
                    array_push($quarter3, $values);
                }
                elseif($values->quarter == 4){
                    array_push($quarter4, $values);
                }
            }
            // return $getValues;
            $values = array();
            array_push($values, array(
                "quarter1" => $quarter1,
                "quarter2" => $quarter2,
                "quarter3" => $quarter3,
                "quarter4" => $quarter4
            ));
        }
        if($action == 'preview'){
            // return $studattrep[2]->month;;
            if($getId[0]->acadprogcode == 'SHS'){
                // return 'asda';
                // return $generateGrade[0]->firstsem[0]['quarter1;
                // return $arrayForm[0][0]->promotionstatus;
                return view('teacher.pdf.reportcardpreview')
                    ->with('getSchoolInfo',$getSchoolInfo)
                    ->with('arrayForm',$arrayForm)
                    // ->with('generalAverage',$generalAverage)
                    // ->with('grades', $shs_grades)
                    ->with('firstsem',$gradesfirstsem)
                    ->with('secondsem',$gradessecondsem)
                    ->with('teacher',$getId[0])
                    ->with('gradelevel',$gradelevel)
                    ->with('studattrep',$studattrep)
                    ->with('studentid',$id)
                    ->with('progname',$student_info[0]->progname)
                    ->with('getValues',$getValues);
            }
            else{
                
                return view('teacher.pdf.reportcardpreview')
                    ->with('getSchoolInfo',$getSchoolInfo)
                    ->with('arrayForm',$arrayForm)
                    ->with('generateGrade',$generateGrade)
                    ->with('generalAverage',$generalAverage)
                    // ->with('firstsem',$firstsem)
                    // ->with('secondsem',$secondsem)
                    ->with('teacher',$getId[0])
                    ->with('gradelevel',$gradelevel)
                    ->with('studattrep',$studattrep)
                    ->with('studentid',$id)
                    ->with('progname',$student_info[0]->progname)
                    ->with('getValues',$values[0]);
            }

        }
        elseif($action == 'print'){
            // return $arrayForm[2];
            // foreach($arrayForm[0][0] as $key => $value){
            //     if($key == 'dob'){
            //         // return Carbon::create($value)->isoFormat('MM-DD-YYYY');
            //         $age = (int) Carbon::now()->isoFormat('YYYY') - (int) Carbon::create($value)->isoFormat('YYYY');
            //         // $arrayForm[0]->push($age);
            //     }
            // }
            // return $arrayForm;
            if($student_info[0]->progname == "SENIOR HIGH SCHOOL"){
                $pdf = PDF::loadview('teacher/pdf/pdf_form9senior',compact('getSchoolInfo','arrayForm','generateGrade','getId','studattrep','values','shs_grades','getSyId'))->setPaper('8.5x14','portrait');
            }
            else{
                $pdf = PDF::loadview('teacher/pdf/reportcarddownload',compact('getSchoolInfo','arrayForm','generateGrade','getId','studattrep','values','getSyId'))->setPaper('8.5x14','landscape');
            }

            return $pdf->stream('Report Card');
        }

        
    }
    function updateObservedValues($id, Request $request)
    {
        $getSyId = DB::table('sy')
            ->select('id')
            ->where('isactive',1)
            ->get();
        $section_id = $request->get('sectionid');
        $levelid = DB::table('sections')
            ->select('levelid')
            ->where('id',$section_id)
            ->get();
        $field = $request->get('field');
        $quarter = $request->get('quarter');
        
        $behavior = $request->get('behavior');
        $observedValues = DB::table('observedvalues')
            ->select('id')
            ->where('syid', $getSyId[0]->id)
            ->where('levelid', $levelid[0]->levelid)
            ->where('sectionid', $section_id)
            ->where('quarter', $quarter)
            ->get();
            
        if(count($observedValues)==0){

            DB::insert('insert into observedvalues (syid,levelid,sectionid,quarter,submitted) values (?,?,?,?,?)',[$getSyId[0]->id,$levelid[0]->levelid,$section_id,$quarter,1]);
            
            $getObservedValues = DB::table('observedvalues')
                ->select('id')
                ->where('syid', $getSyId[0]->id)
                ->where('levelid', $levelid[0]->levelid)
                ->where('sectionid', $section_id)
                ->where('quarter', $quarter)
                ->get();
            $observedValuesDetail = DB::table('observedvaluesdetail')
                ->select('id')
                ->where('headerid', $getObservedValues[0]->id)
                ->where('studid', $id)
                ->get();
            if(count($observedValuesDetail)==0){
                if($field == 'makaDiyos_1'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaDiyos_1) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaDiyos_2'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaDiyos_2) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaTao_1'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaTao_1) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaTao_2'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaTao_2) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaKalikasan_1'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaKalikasan_1) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaKalikasan_2'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaKalikasan_2) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaBansa_1'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaBansa_1) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaBansa_2'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaBansa_2) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                // DB::update('update observedvaluesdetail set '.$field.' = ?  where studid = ? and headerid = ?', [$behavior , $id , $getObservedValues[0]->id]);
            }
        }else{
            $valuesDetailWithHeader = DB::table('observedvaluesdetail')
                ->select('id')
                ->where('headerid', $observedValues[0]->id)
                ->where('studid', $id)
                ->get();

            $getObservedValues = DB::table('observedvalues')
                ->select('id')
                ->where('syid', $getSyId[0]->id)
                ->where('levelid', $levelid[0]->levelid)
                ->where('sectionid', $section_id)
                ->where('quarter', $quarter)
                ->get();
                
            if(count($valuesDetailWithHeader)==0){
                if($field == 'makaDiyos_1'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaDiyos_1) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaDiyos_2'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaDiyos_2) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaTao_1'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaTao_1) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaTao_2'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaTao_2) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaKalikasan_1'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaKalikasan_1) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaKalikasan_2'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaKalikasan_2) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaBansa_1'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaBansa_1) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
                elseif($field == 'makaBansa_2'){
                    DB::insert('insert into observedvaluesdetail (headerid,studid,makaBansa_2) values (?,?,?)',[$getObservedValues[0]->id,$id,$behavior]);
                }
            }else{
                DB::update('update observedvaluesdetail set '.$field.' = ?  where studid = ? and headerid = ?', [$behavior, $id, $observedValues[0]->id]);
            }
        }

    }
}
// $first_day_of_the_current_month = Carbon::today()->startOfMonth()->isoFormat('D');
//         $last_day_of_the_current_month = Carbon::today()->endOfMonth()->isoFormat('D');